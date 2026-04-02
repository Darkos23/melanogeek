<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Catalogue public
    public function index(Request $request)
    {
        $query = Course::published()->with('instructor');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $courses = $query->latest()->paginate(12)->withQueryString();

        return view('courses.index', compact('courses'));
    }

    // Détail d'un cours
    public function show(Course $course)
    {
        if ($course->status !== 'published' && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        $course->load(['instructor', 'sections.lessons', 'reviews.user']);

        $isEnrolled = auth()->check() && auth()->user()->isEnrolledIn($course);
        $progress = $isEnrolled ? $course->getProgressFor(auth()->user()) : 0;

        return view('courses.show', compact('course', 'isEnrolled', 'progress'));
    }
}
