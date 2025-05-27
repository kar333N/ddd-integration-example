<?php
declare(strict_types=1);

namespace App\CourseManagement\Service;

use App\CourseManagement\Repository\CourseRepository;
use App\SharedKernel\ValueObject\CourseId;
use App\CourseManagement\PublishedLanguage\CourseDTO;

class CourseCatalogOHS
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getCourseDetails(CourseId $courseId): ?CourseDTO
    {
        $course = $this->courseRepository->findById($courseId);
        if (!$course) {
            return null;
        }

        // Преобразование внутренней модели Course в CourseDTO (Published Language)
        return new CourseDTO(
            $course->getId(),
            $course->getTitle(),
            count($course->getLectures()) // Пример упрощенного поля
        );
    }

    /**
     * @return CourseDTO[]
     */
    public function getAllCourses(): array
    {
        // В реальном приложении у репозитория был бы метод findAll()
        // Для примера, мы эмулируем это, если есть такая возможность
        $allInternalCourses = []; // Предположим, что InMemoryCourseRepository может вернуть все
        if (method_exists($this->courseRepository, 'findAll')) {
            $allInternalCourses = $this->courseRepository->findAll();
        } else {
            // Эмуляция для InMemoryCourseRepository без findAll()
            // Это плохая практика для реального кода, но для примера сойдет
            $reflection = new \ReflectionClass($this->courseRepository);
            if ($reflection->hasProperty('courses')) {
                $property = $reflection->getProperty('courses');
                $property->setAccessible(true);
                $allInternalCourses = $property->getValue($this->courseRepository);
            }
        }


        $dtos = [];
        foreach ($allInternalCourses as $course) {
            $dtos[] = new CourseDTO(
                $course->getId(),
                $course->getTitle(),
                count($course->getLectures())
            );
        }
        return $dtos;
    }
}