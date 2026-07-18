<?php

namespace App\Services\Assessments;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AttemptService
{
    /**
     * Start (or resume) the single attempt for this application.
     * Throws if an attempt already exists in a final state — enforces
     * "Single Session" from the spec at the service layer, not just the DB.
     */
    public function startOrResume(JobApplication $jobApplication, Request $request): Attempt
    {
        $existing = $jobApplication->attempt;

        if ($existing) {
            if ($existing->status === AttemptStatus::InProgress && $existing->isExpired()) {
                $this->autoSubmit($existing);
            }

            $existing->refresh();

            if ($existing->status->isFinal()) {
                throw ValidationException::withMessages([
                    'attempt' => 'You have already completed this assessment.',
                ]);
            }

            return $existing; // still in_progress, not expired — resume it
        }

        $assessment = $jobApplication->jobPosting->assessment;
        $questions = $assessment->questions()->pluck('id');

        $order = $assessment->shuffle_questions
            ? $questions->shuffle()->values()->all()
            : $questions->values()->all();

        $startedAt = now();

        return Attempt::create([
            'job_application_id' => $jobApplication->id,
            'assessment_id' => $assessment->id,
            'user_id' => $jobApplication->user_id,
            'status' => AttemptStatus::InProgress,
            'question_order' => $order,
            'started_at' => $startedAt,
            'expires_at' => $startedAt->clone()->addMinutes($assessment->duration_minutes),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Upsert a single answer. Called repeatedly as the candidate progresses —
     * this is the auto-save mechanism.
     */
    public function saveAnswer(Attempt $attempt, array $data, ?UploadedFile $file = null): void
    {
        $this->assertActiveAndNotExpired($attempt);

        $payload = [
            'selected_option_ids' => $data['selected_option_ids'] ?? null,
            'text_answer' => $data['text_answer'] ?? null,
        ];

        if ($file) {
            $payload['file_path'] = $file->store(
                "attempts/{$attempt->id}/answers",
                'local'
            );
        }

        $attempt->answers()->updateOrCreate(
            ['question_id' => $data['question_id']],
            $payload
        );
    }

    public function submit(Attempt $attempt): Attempt
    {
        $this->assertActiveAndNotExpired($attempt);

        $attempt->update([
            'status' => AttemptStatus::Submitted,
            'submitted_at' => now(),
        ]);

        return $attempt;
    }

    public function autoSubmit(Attempt $attempt): Attempt
    {
        $attempt->update([
            'status' => AttemptStatus::AutoSubmitted,
            'submitted_at' => $attempt->expires_at,
        ]);

        return $attempt;
    }

    private function assertActiveAndNotExpired(Attempt $attempt): void
    {
        if ($attempt->status !== AttemptStatus::InProgress) {
            throw ValidationException::withMessages([
                'attempt' => 'This attempt is no longer active.',
            ]);
        }

        if ($attempt->isExpired()) {
            $this->autoSubmit($attempt);

            throw ValidationException::withMessages([
                'attempt' => 'Time is up. Your attempt has been auto-submitted.',
            ]);
        }
    }
}