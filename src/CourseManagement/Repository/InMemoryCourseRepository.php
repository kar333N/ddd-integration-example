<?php
declare(strict_types=1);

namespace App\CourseManagement\Repository;

use App\CourseManagement\Entity\Course;
use App\SharedKernel\ValueObject\CourseId;

class InMemoryCourseRepository implements CourseRepository
{
    /** @var Course[] */
    private array $courses = [];

    public function findById(CourseId $id): ?Course
    {
        return $this->courses[$id->toString()] ?? null;
    }

    public function save(Course $course): void
    {
        $this->courses[$course->getId()->toString()] = $course;
    }

    /** @return Course[] */
    public function findAll(): array // Новый метод
    {
        return array_values($this->courses);
    }
}