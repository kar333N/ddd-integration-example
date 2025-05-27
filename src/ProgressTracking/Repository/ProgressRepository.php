<?php
declare(strict_types=1);

namespace App\ProgressTracking\Repository;

use App\ProgressTracking\Entity\StudentProgress;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\StudentId;

interface ProgressRepository
{
    public function findByStudentAndCourse(StudentId $studentId, CourseId $courseId): ?StudentProgress;
    public function save(StudentProgress $progress): void;
}