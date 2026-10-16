<?php
namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:150'],
            'quest_type' => ['required', Rule::in(['Web Development', 'Desktop App', 'IoT/Hardware', 'AI Integration', 'Custom Software'])],
            'message'    => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        ContactMessage::create($data + ['ip' => $request->ip()]);

        return response()->json(['message' => 'Transmission sent. The guild will reply to ' . $data['email'] . ' soon.']);
    }
}