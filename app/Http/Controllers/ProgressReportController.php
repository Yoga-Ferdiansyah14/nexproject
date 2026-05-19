<?php

namespace App\Http\Controllers;

use App\Models\ProgressReport;
use App\Models\Project;
use Illuminate\Http\Request;

class ProgressReportController extends Controller
{
    public function index(Project $project)
    {
        $user = auth()->user();

        // Team leader hanya bisa lihat laporan proyek yang dia pimpin
        if ($user->role === 'team_leader' && $project->team_leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke laporan proyek ini.');
        }

        $reports = $project->progressReports()->with('reporter')->latest()->get();
        return view('reports.index', compact('project', 'reports'));
    }

    public function create(Project $project)
    {
        $user = auth()->user();

        // Team leader hanya bisa input laporan untuk proyek yang dia pimpin
        if ($user->role === 'team_leader' && $project->team_leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk membuat laporan proyek ini.');
        }

        $latestWeek = $project->progressReports()->max('week_number') ?? 0;
        $nextWeek = $latestWeek + 1;
        $latestPercentage = $project->progressReports()->latest()->value('percentage') ?? 0;

        return view('reports.create', compact('project', 'nextWeek', 'latestPercentage'));
    }

    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        // Team leader hanya bisa input laporan untuk proyek yang dia pimpin
        if ($user->role === 'team_leader' && $project->team_leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk membuat laporan proyek ini.');
        }

        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'description' => 'required|string',
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        $validated['project_id'] = $project->id;
        $validated['reported_by'] = auth()->id();

        ProgressReport::create($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Laporan progress berhasil disimpan.');
    }
}
