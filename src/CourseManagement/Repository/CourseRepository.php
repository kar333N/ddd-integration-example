<?php
declare(strict_types=1);

namespace App\CourseManagement\Repository;

use App\CourseManagement\Entity\Course;
use App\SharedKernel\ValueObject\CourseId;

interface CourseRepository
{
    public function findById(CourseId $id): ?Course;
    public function save(Course $course): void;

    public function findAll(): array;
}