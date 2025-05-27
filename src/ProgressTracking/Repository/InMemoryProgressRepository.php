<?php
declare(strict_types=1);

namespace App\ProgressTracking\Repository;

use App\ProgressTracking\Entity\StudentProgress;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\StudentId;

class InMemoryProgressRepository implements ProgressRepository
{
    /** @var StudentProgress[] - ключ это "studentId_courseId" */
    private array $progressRecords = [];

    public function findByStudentAndCourse(StudentId $studentId, CourseId $courseId): ?StudentProgress
    {
        $key = $studentId->toString() . '_' . $courseId->toString();
        return $this->progressRecords[$key] ?? null;
    }

    public function save(StudentProgress $progress): void
    {
        $key = $progress->getStudentId()->toString() . '_' . $progress->getCourseId()->toString();
        $this->progressRecords[$key] = $progress;
    }
}