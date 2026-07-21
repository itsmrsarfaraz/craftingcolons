<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use App\Services\Admin\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserManagementService $userManagementService)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $users = User::with('roles')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->userManagementService->create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('status', "User \"{$user->name}\" created. Login credentials have been emailed to {$user->email}.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $request->validate([
            'role' => ['required', 'string', 'exists:roles,slug'],
        ]);

        $this->userManagementService->changeRole($user, $request->input('role'));

        return back()->with('status', "{$user->name}'s role updated.");
    }
}