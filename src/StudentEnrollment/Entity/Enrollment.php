<?php
declare(strict_types=1);

namespace App\StudentEnrollment\Entity;

use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\StudentId;

class Enrollment
{
    private string $id; // Enrollment ID
    private StudentId $studentId;
    private CourseId $courseId;
    private \DateTimeImmutable $enrolledAt;
    private string $status; // e.g., "active", "completed", "cancelled"

    public function __construct(StudentId $studentId, CourseId $courseId)
    {
        $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $this->studentId = $studentId;
        $this->courseId = $courseId;
        $this->enrolledAt = new \DateTimeImmutable();
        $this->status = 'active';
    }

    public function getId(): string // Добавлен этот метод
    {
        return $this->id;
    }

    public function getStudentId(): StudentId
    {
        return $this->studentId;
    }

    public function getCourseId(): CourseId
    {
        return $this->courseId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    // ... другие геттеры и методы
}