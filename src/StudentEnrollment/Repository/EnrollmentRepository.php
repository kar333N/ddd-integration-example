<?php
declare(strict_types=1);

namespace App\StudentEnrollment\Repository;

use App\StudentEnrollment\Entity\Enrollment;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\StudentId;

interface EnrollmentRepository
{
    public function findById(string $enrollmentId): ?Enrollment;
    public function findByStudentAndCourse(StudentId $studentId, CourseId $courseId): ?Enrollment;
    /** @return Enrollment[] */
    public function findByStudent(StudentId $studentId): array;
    /** @return Enrollment[] */
    public function findByCourse(CourseId $courseId): array;
    public function save(Enrollment $enrollment): void;
}