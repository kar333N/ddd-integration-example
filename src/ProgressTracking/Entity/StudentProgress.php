<?php
declare(strict_types=1);

namespace App\ProgressTracking\Entity;

use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\LectureId;
use App\SharedKernel\ValueObject\StudentId;

class StudentProgress
{
    private StudentId $studentId;
    private CourseId $courseId;
    /** @var array<string, bool> lectureId => completedStatus */
    private array $completedLectures = [];

    public function __construct(StudentId $studentId, CourseId $courseId)
    {
        $this->studentId = $studentId;
        $this->courseId = $courseId;
    }

    public function markLectureAsCompleted(LectureId $lectureId): void
    {
        $this->completedLectures[$lectureId->toString()] = true;
    }

    public function isLectureCompleted(LectureId $lectureId): bool
    {
        return $this->completedLectures[$lectureId->toString()] ?? false;
    }

    public function getStudentId(): StudentId { return $this->studentId; }
    public function getCourseId(): CourseId { return $this->courseId; }
}