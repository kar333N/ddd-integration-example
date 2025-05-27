<?php
declare(strict_types=1);

namespace App\Reporting\ValueObject;

// Эта модель специфична для контекста Reporting
class ReportableCourse
{
    public string $id;
    public string $title;
    // Могут быть другие поля, специфичные для отчетов, например, количество студентов

    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }
}