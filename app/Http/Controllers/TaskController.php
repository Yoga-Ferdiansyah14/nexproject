<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'tenaga_ahli') {
            // Tenaga ahli hanya lihat tugas yang di-assign ke dia
            $tasks = Task::with(['project.client', 'assignee'])
                ->where('assigned_to', $user->id)
                ->latest()
                ->get();
        } elseif ($user->role === 'team_leader') {
            // Team leader lihat tugas dari proyek yang dia pimpin
            $tasks = Task::with(['project.client', 'assignee'])
                ->whereHas('project', fn($q) => $q->where('team_leader_id', $user->id))
                ->latest()
                ->get();
        } else {
            // admin, direktur lihat semua
            $tasks = Task::with(['project.client', 'assignee'])
                ->latest()
                ->get();
        }

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'selesai')->count();
        $inProgressTasks = $tasks->where('status', 'proses')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        if ($user->role === 'tenaga_ahli') {
            $activeProjects = Project::whereHas('tasks', fn($q) => $q->where('assigned_to', $user->id))->where('status', 'aktif')->count();
        } elseif ($user->role === 'team_leader') {
            $activeProjects = Project::where('team_leader_id', $user->id)->where('status', 'aktif')->count();
        } else {
            $activeProjects = Project::where('status', 'aktif')->count();
        }

        return view('tasks.index', compact('tasks', 'totalTasks', 'completedTasks', 'inProgressTasks', 'completionRate', 'activeProjects'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:belum,proses,selesai',
        ]);

        // Team leader hanya bisa tambah tugas di proyek yang dia pimpin
        if ($user->role === 'team_leader') {
            $project = Project::findOrFail($validated['project_id']);
            if ($project->team_leader_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk menambah tugas di proyek ini.');
            }
        }

        $validated['status'] = $validated['status'] ?? 'belum';

        Task::create($validated);

        return back()->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();

        // Tenaga ahli hanya bisa update tugas miliknya sendiri
        if ($user->role === 'tenaga_ahli' && $task->assigned_to !== $user->id) {
            abort(403, 'Anda hanya bisa mengubah status tugas milik Anda.');
        }

        $validated = $request->validate([
            'status' => 'required|in:belum,proses,selesai',
        ]);

        $task->update($validated);

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }
}
