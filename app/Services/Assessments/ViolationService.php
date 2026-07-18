<?php

namespace App\Services\Assessments;

use App\Enums\AttemptStatus;
use App\Enums\ViolationType;
use App\Models\Attempt;

class ViolationService
{
    /**
     * Record a violation and disqualify the attempt if the threshold
     * (or an instant-fail type) is crossed. Returns the fresh attempt
     * so the controller can tell the client whether the attempt just ended.
     */
    public function record(Attempt $attempt, ViolationType $type, ?array $metadata = null): Attempt
    {
        if ($attempt->status !== AttemptStatus::InProgress) {
            return $attempt;
        }

        $attempt->violations()->create([
            'type' => $type,
            'occurred_at' => now(),
            'metadata' => $metadata,
        ]);

        $attempt->increment('violation_count');
        $attempt->refresh();

        if ($type->isInstantFail() || $attempt->violation_count >= $attempt->max_violations_allowed) {
            $attempt->update(['status' => AttemptStatus::Disqualified, 'submitted_at' => now()]);
        }

        return $attempt->fresh();
    }
}