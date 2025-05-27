<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\SharedKernel\ValueObject\StudentId;
use App\SharedKernel\ValueObject\CourseId;

// Инициализация репозиториев
$courseManagementRepository = new App\CourseManagement\Repository\InMemoryCourseRepository();
$enrollmentRepository = new App\StudentEnrollment\Repository\InMemoryEnrollmentRepository();
$progressRepository = new App\ProgressTracking\Repository\InMemoryProgressRepository();

// Инициализация сервисов
$courseService = new App\CourseManagement\Service\CourseService($courseManagementRepository);
// EnrollmentService теперь зависит от CourseManagementRepository (Конформист)
$enrollmentService = new App\StudentEnrollment\Service\EnrollmentService($enrollmentRepository, $courseManagementRepository);
$progressService = new App\ProgressTracking\Service\ProgressService($progressRepository);

// --- ACL и Reporting Context ---
$courseManagementACL = new App\Reporting\AntiCorruptionLayer\CourseManagementACL($courseManagementRepository);
$reportService = new App\Reporting\Service\ReportService($courseManagementACL, $enrollmentRepository);

// --- OHS для внешнего партнера ---
$courseCatalogOHS = new App\CourseManagement\Service\CourseCatalogOHS($courseManagementRepository);


echo "--- КОНТЕКСТ УПРАВЛЕНИЯ КУРСАМИ ---\n";
$phpCourseId = $courseService->createCourse("Основы PHP");
$lecture1Id = $courseService->addLectureToCourse($phpCourseId, "Введение в PHP");
$lecture2Id = $courseService->addLectureToCourse($phpCourseId, "Переменные и типы данных");

$dddCourseId = $courseService->createCourse("Domain-Driven Design");
$lecture3Id = $courseService->addLectureToCourse($dddCourseId, "Стратегическое проектирование");
$nonExistentCourseId = CourseId::generate();


echo "\n--- КОНТЕКСТ ЗАПИСИ СТУДЕНТОВ (Конформист) ---\n";
$student1Id = StudentId::generate();
$student2Id = StudentId::generate();

$enrollmentService->enrollStudent($student1Id, $phpCourseId);
$enrollmentService->enrollStudent($student2Id, $phpCourseId);
$enrollmentService->enrollStudent($student1Id, $dddCourseId);
// Попытка записаться на несуществующий курс (проверка конформистом)
$enrollmentService->enrollStudent($student1Id, $nonExistentCourseId);


echo "\n--- КОНТЕКСТ ОТСЛЕЖИВАНИЯ ПРОГРЕССА (без прямой интеграции с CourseManagement в этом примере) ---\n";
if ($lecture1Id) {
    $progressService->recordLectureCompleted($student1Id, $phpCourseId, $lecture1Id);
}


echo "\n--- КОНТЕКСТ ОТЧЕТНОСТИ (использует ACL) ---\n";
$reportService->generateCoursePopularityReport();
echo "Попытка получить отчет по несуществующему курсу:\n";
$reportService->getSpecificCourseReport($nonExistentCourseId);
echo "Попытка получить отчет по существующему курсу:\n";
$reportService->getSpecificCourseReport($phpCourseId);


echo "\n--- ВНЕШНИЙ КАТАЛОГ ПАРТНЕРОВ (использует OHS с Опубликованным Языком) ---\n";
echo "Получение всех курсов через OHS:\n";
$allCoursesDTO = $courseCatalogOHS->getAllCourses();
foreach ($allCoursesDTO as $courseDTO) {
    echo "- Название: {$courseDTO->title}, ID: {$courseDTO->id}, Лекций: {$courseDTO->numberOfLectures}\n";
}
echo "\nПолучение деталей конкретного курса через OHS:\n";
$phpCourseDTO = $courseCatalogOHS->getCourseDetails($phpCourseId);
if ($phpCourseDTO) {
    echo "Детали PHP курса: Название: {$phpCourseDTO->title}, ID: {$phpCourseDTO->id}, Лекций: {$phpCourseDTO->numberOfLectures}\n";
}
$nonExistentCourseDTO = $courseCatalogOHS->getCourseDetails($nonExistentCourseId);
if (!$nonExistentCourseDTO) {
    echo "Курс с ID {$nonExistentCourseId->toString()} не найден через OHS.\n";
}