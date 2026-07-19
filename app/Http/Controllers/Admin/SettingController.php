<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Settings\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private readonly SettingsService $settingsService)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $settings = Setting::orderBy('key')->get();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string'],
            'type' => ['required', 'string', 'in:string,integer,boolean,json'],
        ]);

        $this->settingsService->set(
            $validated['key'],
            $validated['value'],
            SettingType::from($validated['type'])
        );

        return back()->with('status', "Setting \"{$validated['key']}\" updated.");
    }
}