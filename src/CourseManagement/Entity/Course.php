<?php
declare(strict_types=1);

namespace App\CourseManagement\Entity;

use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\LectureId;

class Course
{
    private CourseId $id;
    private string $title;
    /** @var LectureId[] */
    private array $lectures = [];

    public function __construct(CourseId $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): CourseId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function addLecture(LectureId $lectureId, string $lectureTitle): void
    {
        // В реальном приложении LectureId и Lecture были бы связаны
        $this->lectures[] = $lectureId; // Упрощено
        echo "Лекция '{$lectureTitle}' добавлена к курсу '{$this->title}'.\n";
    }

    /** @return LectureId[] */
    public function getLectures(): array
    {
        return $this->lectures;
    }
}