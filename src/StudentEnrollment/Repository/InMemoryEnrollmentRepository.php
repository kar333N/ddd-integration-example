<?php
declare(strict_types=1);

namespace App\StudentEnrollment\Repository;

use App\StudentEnrollment\Entity\Enrollment;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\StudentId;

class InMemoryEnrollmentRepository implements EnrollmentRepository
{
    /** @var Enrollment[] - ключ это Enrollment->id */
    private array $enrollments = [];

    public function findById(string $enrollmentId): ?Enrollment
    {
        return $this->enrollments[$enrollmentId] ?? null;
    }

    public function findByStudentAndCourse(StudentId $studentId, CourseId $courseId): ?Enrollment
    {
        foreach ($this->enrollments as $enrollment) {
            if ($enrollment->getStudentId()->equals($studentId) && $enrollment->getCourseId()->equals($courseId)) {
                return $enrollment;
            }
        }
        return null;
    }

    /** @return Enrollment[] */
    public function findByStudent(StudentId $studentId): array
    {
        $results = [];
        foreach ($this->enrollments as $enrollment) {
            if ($enrollment->getStudentId()->equals($studentId)) {
                $results[] = $enrollment;
            }
        }
        return $results;
    }

    /** @return Enrollment[] */
    public function findByCourse(CourseId $courseId): array
    {
        $results = [];
        foreach ($this->enrollments as $enrollment) {
            if ($enrollment->getCourseId()->equals($courseId)) {
                $results[] = $enrollment;
            }
        }
        return $results;
    }

    public function save(Enrollment $enrollment): void
    {
        $this->enrollments[$enrollment->getId()] = $enrollment;
    }
}