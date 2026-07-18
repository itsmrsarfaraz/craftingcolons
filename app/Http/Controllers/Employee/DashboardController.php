<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $employee = $request->user()->employee()->with('manager')->first();

        return view('dashboards.employee', compact('employee'));
    }
}