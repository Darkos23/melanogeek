<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()->isInstructor() || auth()->user()->isAdmin(), 403);
            return $next($request);
        });
    }

    public function dashboard()
    {
        $courses = auth()->user()->courses()->withCount('enrollments')->latest()->get();
        return view('instructor.dashboard', compact('courses'));
    }

    // ── Cours ──

    public function createCourse()
    {
        return view('instructor.courses.create');
    }

    public function storeCourse(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'category'       => 'required|in:reseaux_securite,informatique_gestion,bases_informatiques',
            'level'          => 'required|in:debutant,intermediaire,avance',
            'language'       => 'required|in:fr,en,wo',
            'price'          => 'required|numeric|min:0',
            'is_free'        => 'boolean',
            'thumbnail'      => 'nullable|image|max:2048',
            'what_you_learn' => 'nullable|string',
            'requirements'   => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $data['slug']          = Str::slug($data['title']) . '-' . Str::random(6);
        $data['instructor_id'] = auth()->id();
        $data['what_you_learn'] = $data['what_you_learn']
            ? array_filter(array_map('trim', explode("\n", $data['what_you_learn'])))
            : null;
        $data['requirements'] = $data['requirements']
            ? array_filter(array_map('trim', explode("\n", $data['requirements'])))
            : null;

        $course = Course::create($data);

        return redirect()->route('instructor.courses.edit', $course)
            ->with('success', 'Cours créé ! Ajoutez maintenant les sections et leçons.');
    }

    public function editCourse(Course $course)
    {
        $this->authorizeCourse($course);
        $course->load('sections.lessons');
        return view('instructor.courses.edit', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'category'       => 'required|in:reseaux_securite,informatique_gestion,bases_informatiques',
            'level'          => 'required|in:debutant,intermediaire,avance',
            'language'       => 'required|in:fr,en,wo',
            'price'          => 'required|numeric|min:0',
            'is_free'        => 'boolean',
            'status'         => 'required|in:draft,published,archived',
            'thumbnail'      => 'nullable|image|max:2048',
            'what_you_learn' => 'nullable|string',
            'requirements'   => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $data['what_you_learn'] = $data['what_you_learn']
            ? array_filter(array_map('trim', explode("\n", $data['what_you_learn'])))
            : null;
        $data['requirements'] = $data['requirements']
            ? array_filter(array_map('trim', explode("\n", $data['requirements'])))
            : null;

        $course->update($data);

        return back()->with('success', 'Cours mis à jour.');
    }

    // ── Sections ──

    public function storeSection(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $request->validate(['title' => 'required|string|max:255']);

        $order = $course->sections()->max('order') + 1;
        $course->sections()->create(['title' => $request->title, 'order' => $order]);

        return back()->with('success', 'Section ajoutée.');
    }

    public function updateSection(Request $request, Section $section)
    {
        $this->authorizeCourse($section->course);
        $request->validate(['title' => 'required|string|max:255']);
        $section->update(['title' => $request->title]);
        return back()->with('success', 'Section mise à jour.');
    }

    public function destroySection(Section $section)
    {
        $this->authorizeCourse($section->course);
        $section->delete();
        return back()->with('success', 'Section supprimée.');
    }

    // ── Leçons ──

    public function storeLesson(Request $request, Section $section)
    {
        $this->authorizeCourse($section->course);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video'       => 'nullable|file|mimes:mp4,mov,avi,webm|max:2097152', // 2 GB
            'attachment'  => 'nullable|file|mimes:pdf,zip,doc,docx|max:51200',
            'is_preview'  => 'boolean',
        ]);

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('courses/videos', 'local');
            // Durée à extraire via ffprobe si disponible
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('courses/attachments', 'local');
        }

        $data['order'] = $section->lessons()->max('order') + 1;
        $lesson = $section->lessons()->create($data);

        // Mettre à jour le compteur du cours
        $this->updateCourseStats($section->course);

        return back()->with('success', 'Leçon ajoutée.');
    }

    public function destroyLesson(Lesson $lesson)
    {
        $this->authorizeCourse($lesson->section->course);
        $course = $lesson->section->course;
        $lesson->delete();
        $this->updateCourseStats($course);
        return back()->with('success', 'Leçon supprimée.');
    }

    private function updateCourseStats(Course $course): void
    {
        $total = $course->lessons()->count();
        $duration = $course->lessons()->sum('duration');
        $course->update(['total_lessons' => $total, 'total_duration' => $duration]);
    }

    private function authorizeCourse(Course $course): void
    {
        abort_unless(
            $course->instructor_id === auth()->id() || auth()->user()->isAdmin(),
            403
        );
    }
}
