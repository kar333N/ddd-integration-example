<?php
declare(strict_types=1);

namespace App\CourseManagement\PublishedLanguage;

use App\SharedKernel\ValueObject\CourseId;

// Это Data Transfer Object (DTO) - часть Опубликованного Языка
class CourseDTO
{
    public string $id;
    public string $title;
    public int $numberOfLectures;
    // Можно добавить другие упрощенные поля, необходимые внешним системам

    public function __construct(CourseId $id, string $title, int $numberOfLectures)
    {
        $this->id = $id->toString();
        $this->title = $title;
        $this->numberOfLectures = $numberOfLectures;
    }
}