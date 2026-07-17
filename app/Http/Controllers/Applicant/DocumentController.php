<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\UploadDocumentRequest;
use App\Models\ApplicantDocument;
use App\Services\Applicant\ApplicantProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(private readonly ApplicantProfileService $profileService)
    {
    }

    public function store(UploadDocumentRequest $request): RedirectResponse
    {
        $this->profileService->uploadDocument(
            $request->user(),
            $request->file('file'),
            $request->validated('type')
        );

        return back()->with('status', 'Document uploaded successfully.');
    }

    public function download(Request $request, ApplicantDocument $document): StreamedResponse
    {
        $this->authorize('view', $document);

        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }

    public function destroy(Request $request, ApplicantDocument $document): RedirectResponse
    {
        $this->authorize('delete', $document);

        $this->profileService->deleteDocument($document);

        return back()->with('status', 'Document deleted.');
    }
}