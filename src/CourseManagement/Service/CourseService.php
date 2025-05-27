<?php
declare(strict_types=1);

namespace App\CourseManagement\Service;

use App\CourseManagement\Entity\Course;
use App\CourseManagement\Repository\CourseRepository;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\LectureId;

class CourseService
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function createCourse(string $title): CourseId
    {
        $courseId = CourseId::generate();
        $course = new Course($courseId, $title);
        $this->courseRepository->save($course);
        echo "Курс '{$title}' создан с ID: {$courseId->toString()}.\n";
        return $courseId;
    }

    public function addLectureToCourse(CourseId $courseId, string $lectureTitle): ?LectureId
    {
        $course = $this->courseRepository->findById($courseId);
        if (!$course) {
            echo "Ошибка: Курс с ID {$courseId->toString()} не найден.\n";
            return null;
        }
        $lectureId = LectureId::generate();
        $course->addLecture($lectureId, $lectureTitle); // В реальном приложении Lecture был бы сущностью
        $this->courseRepository->save($course); // Обновляем курс
        return $lectureId;
    }
}