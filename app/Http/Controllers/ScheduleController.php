<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Project;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Project $project)
    {
        $schedules = $project->schedules()->orderBy('start_date')->get();
        return view('schedules.index', compact('project', 'schedules'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'phase_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:ontrack,delayed,done',
        ]);

        $validated['project_id'] = $project->id;

        Schedule::create($validated);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }
}
