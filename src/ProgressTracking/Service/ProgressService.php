<?php
declare(strict_types=1);

namespace App\ProgressTracking\Service;

use App\ProgressTracking\Entity\StudentProgress;
use App\ProgressTracking\Repository\ProgressRepository;
use App\SharedKernel\ValueObject\CourseId;
use App\SharedKernel\ValueObject\LectureId;
use App\SharedKernel\ValueObject\StudentId;

class ProgressService
{
    private ProgressRepository $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function recordLectureCompleted(StudentId $studentId, CourseId $courseId, LectureId $lectureId): void
    {
        $progress = $this->progressRepository->findByStudentAndCourse($studentId, $courseId);
        if (!$progress) {
            $progress = new StudentProgress($studentId, $courseId);
        }
        $progress->markLectureAsCompleted($lectureId);
        $this->progressRepository->save($progress);

        echo "Прогресс: Студент {$studentId->toString()} завершил лекцию {$lectureId->toString()} на курсе {$courseId->toString()}.\n";
    }

    public function getStudentProgress(StudentId $studentId, CourseId $courseId): ?StudentProgress
    {
        return $this->progressRepository->findByStudentAndCourse($studentId, $courseId);
    }
}