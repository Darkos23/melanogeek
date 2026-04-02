<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public function show(Certificate $certificate)
    {
        // Vérification que c'est bien le bon user ou admin
        abort_unless(
            auth()->id() === $certificate->user_id || auth()->user()->isAdmin(),
            403
        );

        $certificate->load(['user', 'course.instructor']);

        return view('certificates.show', compact('certificate'));
    }

    // Télécharger le PDF (généré à la volée)
    public function download(Certificate $certificate)
    {
        abort_unless(
            auth()->id() === $certificate->user_id || auth()->user()->isAdmin(),
            403
        );

        $certificate->load(['user', 'course']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', compact('certificate'));
        $filename = 'certificat-' . $certificate->certificate_number . '.pdf';

        return $pdf->download($filename);
    }
}
