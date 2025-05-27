<?php
declare(strict_types=1);

namespace App\StudentEnrollment\Service;

use App\StudentEnrollment\Entity\Enrollment;
use App\StudentEnrollment\Repository\EnrollmentRepository;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\StudentId;
use App\CourseManagement\Repository\CourseRepository as CourseManagementRepository; // Используем репозиторий напрямую

class EnrollmentService
{
    private EnrollmentRepository $enrollmentRepository;
    private CourseManagementRepository $courseManagementRepository; // Зависимость от репозитория другого контекста

    public function __construct(
        EnrollmentRepository $enrollmentRepository,
        CourseManagementRepository $courseManagementRepository
    ) {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->courseManagementRepository = $courseManagementRepository;
    }

    public function enrollStudent(StudentId $studentId, CourseId $courseId): ?Enrollment
    {
        // Конформист: проверяем существование курса, используя модель Поставщика
        $course = $this->courseManagementRepository->findById($courseId);
        if (!$course) {
            echo "[StudentEnrollment - Конформист] Ошибка: Курс {$courseId->toString()} не существует.\n";
            return null;
        }
        // Здесь мы можем даже использовать $course->getTitle() или другие поля, если нужно
        // что демонстрирует полную зависимость от модели Поставщика.

        $existingEnrollment = $this->enrollmentRepository->findByStudentAndCourse($studentId, $courseId);
        if ($existingEnrollment) {
            echo "[StudentEnrollment - Конформист] Студент {$studentId->toString()} уже записан на курс '{$course->getTitle()}'.\n";
            return $existingEnrollment;
        }

        $enrollment = new Enrollment($studentId, $courseId);
        $this->enrollmentRepository->save($enrollment);

        echo "[StudentEnrollment - Конформист] Студент {$studentId->toString()} записан на курс '{$course->getTitle()}'.\n";
        return $enrollment;
    }
}