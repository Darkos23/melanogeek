<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\Certificate;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        // Vérifier que la leçon appartient bien au cours
        abort_unless($lesson->section->course_id === $course->id, 404);

        // Preview libre ou inscrit
        if (!$lesson->is_preview && !$user->isEnrolledIn($course)) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Tu dois être inscrit pour accéder à cette leçon.');
        }

        $course->load('sections.lessons');
        $isCompleted = $lesson->isCompletedBy($user);
        $progress = UserProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->whereNotNull('completed_at')
            ->pluck('lesson_id');

        return view('lessons.show', compact('course', 'lesson', 'isCompleted', 'progress'));
    }

    public function complete(Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        abort_unless($user->isEnrolledIn($course), 403);

        UserProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completed_at' => now()]
        );

        // Vérifier si le cours est terminé à 100%
        $courseProgress = $course->getProgressFor($user);
        if ($courseProgress === 100) {
            $this->issueCertificate($user, $course);
        }

        return response()->json([
            'progress' => $courseProgress,
            'certificate_issued' => $courseProgress === 100,
        ]);
    }

    private function issueCertificate($user, $course): void
    {
        Certificate::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'certificate_number' => Certificate::generateNumber(),
                'issued_at'          => now(),
            ]
        );
    }
}
