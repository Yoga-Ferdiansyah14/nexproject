<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\ProjectMember;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Project::with(['client', 'teamLeader', 'members.user', 'progressReports']);

        // Role-based filtering
        if ($user->role === 'marketing') {
            // Marketing hanya lihat proyek yang dia buat
            $query->where('created_by', $user->id);
        } elseif ($user->role === 'team_leader') {
            // Team leader hanya lihat proyek yang ditugaskan kepadanya
            $query->where('team_leader_id', $user->id);
        }
        // direktur & admin lihat semua

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $projects = $query->latest()->get();
        $clients = Client::all();
        $teamLeaders = User::where('role', 'team_leader')->get();

        return view('projects.index', compact('projects', 'clients', 'teamLeaders'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Marketing membuat proyek → status otomatis tertunda, belum ada team leader
        $validated['status'] = 'tertunda';
        $validated['team_leader_id'] = null;
        $validated['created_by'] = auth()->id();

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dibuat. Menunggu penunjukan Team Leader oleh Direktur.');
    }

    public function show(Project $project)
    {
        $user = auth()->user();

        // Team leader hanya bisa lihat proyek yang ditugaskan kepadanya
        if ($user->role === 'team_leader' && $project->team_leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        // Marketing hanya bisa lihat proyek yang dia buat
        if ($user->role === 'marketing' && $project->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $project->load(['client', 'teamLeader', 'members.user', 'tasks.assignee', 'progressReports.reporter', 'schedules']);

        // Data tambahan untuk fitur di halaman detail
        $teamLeaders = User::where('role', 'team_leader')->get();
        $tenagaAhli = User::where('role', 'tenaga_ahli')->get();

        // Anggota tim yang bisa di-assign tugas (members dari proyek ini)
        $projectMembers = $project->members()->with('user')->get();

        return view('projects.show', compact('project', 'teamLeaders', 'tenagaAhli', 'projectMembers'));
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        $teamLeaders = User::where('role', 'team_leader')->get();
        return view('projects.edit', compact('project', 'clients', 'teamLeaders'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:aktif,selesai,tertunda',
            'team_leader_id' => 'nullable|exists:users,id',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    public function assignTeamLeader(Request $request, Project $project)
    {
        $validated = $request->validate([
            'team_leader_id' => 'required|exists:users,id',
        ]);

        // Verify the user is actually a team_leader
        $leader = User::findOrFail($validated['team_leader_id']);
        if ($leader->role !== 'team_leader') {
            return back()->withErrors(['team_leader_id' => 'User yang dipilih bukan Team Leader.']);
        }

        // Update team leader dan otomatis ubah status ke aktif
        $project->update([
            'team_leader_id' => $validated['team_leader_id'],
            'status' => 'aktif',
        ]);

        // Ensure they are also a member
        ProjectMember::firstOrCreate([
            'project_id' => $project->id,
            'user_id' => $validated['team_leader_id'],
        ], [
            'role_in_project' => 'Team Leader',
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Team Leader berhasil ditunjuk. Status proyek diubah ke Aktif.');
    }

    public function addMember(Request $request, Project $project)
    {
        $user = auth()->user();

        // Team leader hanya bisa kelola anggota proyek yang dia pimpin
        if ($user->role === 'team_leader' && $project->team_leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola anggota proyek ini.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_in_project' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah menjadi member
        $exists = ProjectMember::where('project_id', $project->id)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['user_id' => 'User sudah menjadi anggota proyek ini.']);
        }

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $validated['user_id'],
            'role_in_project' => $validated['role_in_project'] ?? 'Anggota',
        ]);

        return back()->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function removeMember(Request $request, Project $project, ProjectMember $member)
    {
        $user = auth()->user();

        // Team leader hanya bisa kelola anggota proyek yang dia pimpin
        if ($user->role === 'team_leader' && $project->team_leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola anggota proyek ini.');
        }

        // Jangan hapus team leader dari members
        if ($member->user_id === $project->team_leader_id) {
            return back()->withErrors(['member' => 'Tidak dapat menghapus Team Leader dari anggota proyek.']);
        }

        $member->delete();

        return back()->with('success', 'Anggota tim berhasil dihapus.');
    }
}
