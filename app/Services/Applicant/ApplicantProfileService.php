<?php

namespace App\Services\Applicant;

use App\Models\ApplicantDocument;
use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApplicantProfileService
{
    public function updateProfile(User $user, array $data): ApplicantProfile
    {
        return $user->applicantProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }

    public function uploadDocument(User $user, UploadedFile $file, string $type): ApplicantDocument
    {
        $path = $file->store("applicants/{$user->id}/documents", 'local');

        return $user->applicantDocuments()->create([
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function deleteDocument(ApplicantDocument $document): void
    {
        Storage::disk('local')->delete($document->file_path);
        $document->delete();
    }
}