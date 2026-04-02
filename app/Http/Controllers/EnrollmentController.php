<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // Page de paiement
    public function checkout(Course $course)
    {
        abort_if(auth()->user()->isEnrolledIn($course), 302, route('lessons.show', [$course, $course->lessons()->first()]));

        if ($course->is_free) {
            return $this->enroll($course, 'free', 0);
        }

        return view('enrollments.checkout', compact('course'));
    }

    // Traitement paiement
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'payment_method' => 'required|in:wave,orange_money',
            'transaction_id' => 'required|string',
        ]);

        // TODO: vérifier le paiement via API Wave/Orange Money
        // Pour l'instant on fait confiance au transaction_id fourni

        $enrollment = $this->enroll(
            $course,
            $request->payment_method,
            $course->price,
            $request->transaction_id
        );

        return redirect()->route('courses.show', $course)
            ->with('success', 'Inscription confirmée ! Bonne formation.');
    }

    private function enroll(Course $course, string $method, float $amount, ?string $txId = null): Enrollment
    {
        return Enrollment::create([
            'user_id'        => auth()->id(),
            'course_id'      => $course->id,
            'payment_method' => $method,
            'amount'         => $amount,
            'currency'       => $course->currency,
            'transaction_id' => $txId,
            'status'         => 'active',
        ]);
    }
}
