<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use Illuminate\View\View;

class AttemptReviewController extends Controller
{
    public function show(Attempt $attempt): View
    {
        $this->authorize('view', $attempt);

        $attempt->load('violations', 'candidate', 'assessment', 'answers.question');

        return view('hr.attempts.show', compact('attempt'));
    }
}