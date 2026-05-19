<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Role-based project queries
        if ($user->role === 'marketing') {
            $totalProyekAktif = Project::where('status', 'aktif')->where('created_by', $user->id)->count();
            $totalProyekSelesai = Project::where('status', 'selesai')->where('created_by', $user->id)->count();
            $totalKlien = Client::count();
            $totalAnggota = User::count();

            $proyekBerjalan = Project::with(['client', 'teamLeader', 'progressReports'])
                ->where('created_by', $user->id)
                ->where('status', 'aktif')
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->role === 'team_leader') {
            $totalProyekAktif = Project::where('status', 'aktif')->where('team_leader_id', $user->id)->count();
            $totalProyekSelesai = Project::where('status', 'selesai')->where('team_leader_id', $user->id)->count();
            $totalKlien = Client::count();
            $totalAnggota = User::count();

            $proyekBerjalan = Project::with(['client', 'teamLeader', 'progressReports'])
                ->where('team_leader_id', $user->id)
                ->where('status', 'aktif')
                ->latest()
                ->take(5)
                ->get();
        } else {
            // direktur & admin lihat semua
            $totalProyekAktif = Project::where('status', 'aktif')->count();
            $totalProyekSelesai = Project::where('status', 'selesai')->count();
            $totalKlien = Client::count();
            $totalAnggota = User::count();

            $proyekBerjalan = Project::with(['client', 'teamLeader', 'progressReports'])
                ->where('status', 'aktif')
                ->latest()
                ->take(5)
                ->get();
        }

        $recentActivities = collect();

        // Get recent tasks updates
        $recentTasks = Task::with(['assignee', 'project'])
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($task) {
                return [
                    'user' => $task->assignee->name ?? 'System',
                    'initials' => $task->assignee->initials ?? 'SY',
                    'action' => match ($task->status) {
                        'selesai' => 'menyelesaikan task',
                        'proses' => 'memulai mengerjakan',
                        default => 'ditugaskan pada',
                    },
                    'target' => '"' . $task->title . '"',
                    'time' => $task->updated_at->diffForHumans(),
                    'type' => $task->status === 'selesai' ? 'success' : 'info',
                ];
            });

        $recentActivities = $recentTasks;

        return view('dashboard', compact(
            'user',
            'totalProyekAktif',
            'totalProyekSelesai',
            'totalKlien',
            'totalAnggota',
            'proyekBerjalan',
            'recentActivities'
        ));
    }
}
