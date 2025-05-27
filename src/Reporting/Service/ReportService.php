<?php
declare(strict_types=1);

namespace App\Reporting\Service;

use App\Reporting\AntiCorruptionLayer\CourseManagementACL;
use App\StudentEnrollment\Repository\EnrollmentRepository; // Может понадобиться для отчетов о студентах
use App\SharedKernel\ValueObject\CourseId;

class ReportService
{
    private CourseManagementACL $courseACL;
    private EnrollmentRepository $enrollmentRepository; // Пример зависимости от другого контекста для данных

    public function __construct(CourseManagementACL $courseACL, EnrollmentRepository $enrollmentRepository)
    {
        $this->courseACL = $courseACL;
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function generateCoursePopularityReport(): void
    {
        echo "[Reporting - ACL] Генерация отчета о популярности курсов:\n";
        $reportableCourses = $this->courseACL->getAllReportableCourses();

        if (empty($reportableCourses)) {
            echo "Нет курсов для отчета.\n";
            return;
        }

        foreach ($reportableCourses as $reportableCourse) {
            // Для подсчета популярности, нам нужен EnrollmentRepository
            $enrollments = $this->enrollmentRepository->findByCourse(CourseId::fromString($reportableCourse->id));
            $enrollmentCount = count($enrollments);
            echo "- Курс: '{$reportableCourse->title}' (ID: {$reportableCourse->id}), Записей: {$enrollmentCount}\n";
        }
    }

    public function getSpecificCourseReport(CourseId $courseId): void
    {
        $reportableCourse = $this->courseACL->getReportableCourse($courseId);
        if ($reportableCourse) {
            echo "[Reporting - ACL] Отчет по курсу '{$reportableCourse->title}': ID {$reportableCourse->id}.\n";
        } else {
            echo "[Reporting - ACL] Ошибка: Курс {$courseId->toString()} не найден для отчета.\n";
        }
    }
}