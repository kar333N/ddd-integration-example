<?php
declare(strict_types=1);

namespace App\Reporting\AntiCorruptionLayer;

use App\CourseManagement\Repository\CourseRepository as ExternalCourseRepository;
use App\SharedKernel\ValueObject\CourseId;
use App\Reporting\ValueObject\ReportableCourse;

class CourseManagementACL
{
    private ExternalCourseRepository $externalCourseRepository;

    public function __construct(ExternalCourseRepository $externalCourseRepository)
    {
        $this->externalCourseRepository = $externalCourseRepository;
    }

    public function getReportableCourse(CourseId $courseId): ?ReportableCourse
    {
        $externalCourse = $this->externalCourseRepository->findById($courseId);
        if (!$externalCourse) {
            return null;
        }

        // Преобразование из внешней модели в модель, удобную для Reporting
        return new ReportableCourse(
            $externalCourse->getId()->toString(),
            $externalCourse->getTitle()
        // Здесь мы могли бы проигнорировать $externalCourse->getLectures(), если они не нужны для отчетов
        );
    }

    /**
     * @return ReportableCourse[]
     */
    public function getAllReportableCourses(): array
    {
        $reportableCourses = [];
        // Предположим, что у репозитория CourseManagement есть findAll()
        if (method_exists($this->externalCourseRepository, 'findAll')) {
            $externalCourses = $this->externalCourseRepository->findAll();
            foreach ($externalCourses as $externalCourse) {
                $reportableCourses[] = new ReportableCourse(
                    $externalCourse->getId()->toString(),
                    $externalCourse->getTitle()
                );
            }
        }
        return $reportableCourses;
    }
}