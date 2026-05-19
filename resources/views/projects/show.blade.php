@extends('layouts.app')
@section('title', $project->name)

@section('content')
@php $progress = $project->progress; $user = auth()->user(); @endphp
<!-- Hero Section -->
<section class="mb-10 grid grid-cols-1 lg:grid-cols-4 gap-8 items-end">
    <div class="lg:col-span-3">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('projects.index') }}" class="text-on-surface-variant hover:text-primary flex items-center gap-1 transition-colors mr-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
            </a>
            @php
                $sBadge = ['aktif'=>'bg-tertiary text-on-tertiary','tertunda'=>'bg-orange-500 text-white','selesai'=>'bg-primary text-on-primary'];
                $sLabel = ['aktif'=>'In Progress','tertunda'=>'On Hold','selesai'=>'Completed'];
            @endphp
            <span class="{{ $sBadge[$project->status] ?? '' }} px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $sLabel[$project->status] ?? $project->status }}</span>
            <span class="text-on-surface-variant text-sm font-medium tracking-tight">Client: {{ $project->client->name ?? '-' }}</span>
        </div>
        <h2 class="text-4xl font-display font-extrabold tracking-tighter text-on-surface">{{ $project->name }}</h2>
    </div>
    <div class="flex justify-start lg:justify-end">
        <div class="relative w-32 h-32 flex items-center justify-center">
            <svg class="w-full h-full transform -rotate-90">
                <circle class="text-surface-container-highest" cx="64" cy="64" fill="transparent" r="58" stroke="currentColor" stroke-width="8"></circle>
                <circle class="text-primary" cx="64" cy="64" fill="transparent" r="58" stroke="currentColor" stroke-dasharray="364.4" stroke-dashoffset="{{ 364.4 - (364.4 * $progress / 100) }}" stroke-linecap="round" stroke-width="8"></circle>
            </svg>
            <div class="absolute flex flex-col items-center">
                <span class="text-2xl font-display font-bold text-on-surface">{{ $progress }}%</span>
                <span class="text-[10px] text-on-surface-variant font-bold uppercase">Progress</span>
            </div>
        </div>
    </div>
</section>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Summary Card -->
    <div class="lg:col-span-2 bg-white border border-outline rounded-xl p-6 flex flex-col shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-headline font-bold text-on-surface">Project Summary</h3>
            @if($user->role === 'admin')
            <a href="{{ route('projects.edit', $project) }}" class="text-primary text-sm font-bold hover:underline">Edit</a>
            @endif
        </div>
        <p class="text-on-surface-variant leading-relaxed mb-8">{{ $project->description ?? 'Tidak ada deskripsi.' }}</p>
        <div class="grid grid-cols-3 gap-4 mt-auto">
            <div class="bg-surface-container-high rounded-lg p-4 border border-outline">
                <span class="text-on-surface-variant text-xs block mb-1">Timeline</span>
                @if($project->start_date && $project->end_date)
                <span class="font-bold text-on-surface">{{ $project->start_date->diffInMonths($project->end_date) }} Bulan</span>
                @else
                <span class="font-bold text-on-surface">-</span>
                @endif
            </div>
            <div class="bg-surface-container-high rounded-lg p-4 border border-outline">
                <span class="text-on-surface-variant text-xs block mb-1">Mulai</span>
                <span class="font-bold text-on-surface">{{ $project->start_date ? $project->start_date->format('d M Y') : '-' }}</span>
            </div>
            <div class="bg-surface-container-high rounded-lg p-4 border border-outline">
                <span class="text-on-surface-variant text-xs block mb-1">Deadline</span>
                <span class="font-bold text-on-surface">{{ $project->end_date ? $project->end_date->format('d M Y') : '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Leader Card -->
    <div class="bg-white border border-outline rounded-xl p-6 relative overflow-hidden shadow-sm">
        <h3 class="text-lg font-headline font-bold mb-6 text-on-surface">Project Lead</h3>
        @if($project->teamLeader)
        <div class="flex flex-col items-center">
            <div class="w-24 h-24 rounded-full p-1 border-2 border-primary mb-4 bg-white shadow-md flex items-center justify-center">
                <div class="w-full h-full rounded-full bg-primary/10 flex items-center justify-center text-2xl font-bold text-primary">{{ $project->teamLeader->initials }}</div>
            </div>
            <h4 class="text-xl font-bold text-on-surface">{{ $project->teamLeader->name }}</h4>
            <p class="text-on-surface-variant text-sm mb-4">Team Leader</p>
        </div>
        @else
        <div class="flex flex-col items-center py-4">
            <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-3xl text-orange-500">person_search</span>
            </div>
            <p class="text-on-surface-variant text-center mb-4">Belum ada Team Leader ditunjuk.</p>

            {{-- Tombol Tunjuk Team Leader: hanya untuk direktur & admin --}}
            @if(in_array($user->role, ['direktur','admin']))
            <button onclick="document.getElementById('assignLeaderModal').classList.remove('hidden')" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary/90 transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">person_add</span>
                Tunjuk Team Leader
            </button>
            @endif
        </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- TEAM MEMBERS SECTION --}}
    {{-- ============================================ --}}
    <div class="lg:col-span-3 mt-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-headline font-bold text-on-surface">Team Members ({{ $project->members->count() }})</h3>
            {{-- Tombol Tambah Anggota: hanya team_leader proyek ini & admin --}}
            @if(($user->role === 'team_leader' && $project->team_leader_id === $user->id) || $user->role === 'admin')
            <button onclick="document.getElementById('addMemberModal').classList.remove('hidden')" class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg text-xs font-bold hover:bg-primary/90 transition-all active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-sm">person_add</span> Tambah Anggota
            </button>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($project->members as $member)
            <div class="bg-white border border-outline p-4 rounded-xl flex items-center gap-4 hover:border-primary hover:shadow-md transition-all shadow-sm relative group">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold border border-outline">{{ $member->user->initials }}</div>
                <div class="flex-1">
                    <h5 class="font-bold text-sm text-on-surface">{{ $member->user->name }}</h5>
                    <span class="bg-primary-container text-on-primary-container px-2 py-0.5 rounded text-[10px] font-bold">{{ $member->role_in_project ?? 'Member' }}</span>
                </div>
                {{-- Tombol hapus: hanya team_leader & admin, jangan hapus TL sendiri --}}
                @if((($user->role === 'team_leader' && $project->team_leader_id === $user->id) || $user->role === 'admin') && $member->user_id !== $project->team_leader_id)
                <form method="POST" action="{{ route('projects.removeMember', [$project, $member]) }}" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-6 h-6 rounded-full bg-error/10 flex items-center justify-center hover:bg-error/20 transition-colors" title="Hapus anggota" onclick="return confirm('Yakin hapus anggota ini?')">
                        <span class="material-symbols-outlined text-error text-xs">close</span>
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- TASKS SECTION --}}
    {{-- ============================================ --}}
    <div class="lg:col-span-3 mt-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-headline font-bold text-on-surface">Tasks ({{ $project->tasks->count() }})</h3>
            {{-- Tombol Tambah Tugas: hanya team_leader proyek ini & admin --}}
            @if(($user->role === 'team_leader' && $project->team_leader_id === $user->id) || $user->role === 'admin')
            <button onclick="document.getElementById('addTaskModal').classList.remove('hidden')" class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg text-xs font-bold hover:bg-primary/90 transition-all active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-sm">add_task</span> Tambah Tugas
            </button>
            @endif
        </div>
        <div class="bg-white border border-outline rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-high text-on-surface-variant text-xs uppercase tracking-wider border-b border-outline">
                        <th class="px-6 py-3 font-bold">Tugas</th>
                        <th class="px-6 py-3 font-bold">Ditugaskan Ke</th>
                        <th class="px-6 py-3 font-bold">Deadline</th>
                        <th class="px-6 py-3 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline">
                    @forelse($project->tasks as $task)
                    <tr class="hover:bg-surface-container-high transition-colors">
                        <td class="px-6 py-4 font-medium text-on-surface">{{ $task->title }}</td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $task->assignee->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            @php $tc = ['belum'=>'bg-surface-container-highest text-secondary','proses'=>'bg-tertiary/10 text-tertiary','selesai'=>'bg-primary/10 text-primary']; @endphp
                            <span class="px-2 py-1 rounded-full {{ $tc[$task->status] ?? '' }} text-[10px] font-bold uppercase">{{ $task->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">Belum ada tugas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- PROGRESS REPORTS SECTION --}}
    {{-- Tampilkan untuk: direktur, admin, team_leader --}}
    {{-- Marketing TIDAK bisa lihat --}}
    {{-- ============================================ --}}
    @if(in_array($user->role, ['direktur','admin','team_leader']))
    <div class="lg:col-span-3 mt-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-headline font-bold text-on-surface">Laporan Progress ({{ $project->progressReports->count() }})</h3>
            {{-- Tombol Input Laporan: hanya team_leader proyek ini & admin --}}
            @if(($user->role === 'team_leader' && $project->team_leader_id === $user->id) || $user->role === 'admin')
            <a href="{{ route('reports.create', $project) }}" class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg text-xs font-bold hover:bg-primary/90 transition-all active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-sm">add</span> Input Laporan
            </a>
            @endif
        </div>
        <div class="space-y-4">
            @forelse($project->progressReports->sortByDesc('week_number') as $report)
            <div class="bg-white border border-outline rounded-xl p-5 shadow-sm">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded">Minggu ke-{{ $report->week_number }}</span>
                        <span class="text-xs text-on-surface-variant ml-2">oleh {{ $report->reporter->name ?? '-' }}</span>
                    </div>
                    <span class="text-2xl font-bold text-primary">{{ $report->percentage }}%</span>
                </div>
                <p class="text-on-surface-variant text-sm">{{ $report->description }}</p>
                <p class="text-[10px] text-on-surface-variant mt-2">{{ $report->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <p class="text-on-surface-variant text-center py-4">Belum ada laporan progress.</p>
            @endforelse
        </div>
    </div>
    @endif
</div>

{{-- ============================================ --}}
{{-- MODAL: TUNJUK TEAM LEADER --}}
{{-- ============================================ --}}
@if(in_array($user->role, ['direktur','admin']) && !$project->teamLeader)
<div id="assignLeaderModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-on-surface/30 backdrop-blur-sm" onclick="document.getElementById('assignLeaderModal').classList.add('hidden')"></div>
    <div class="relative bg-surface rounded-xl border border-outline shadow-2xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-on-surface mb-2">Tunjuk Team Leader</h3>
        <p class="text-sm text-on-surface-variant mb-6">Pilih Team Leader untuk proyek <strong>{{ $project->name }}</strong>. Status proyek akan otomatis berubah ke <span class="text-tertiary font-bold">Aktif</span>.</p>
        <form method="POST" action="{{ route('projects.assignLeader', $project) }}" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Team Leader</label>
                <select name="team_leader_id" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" required>
                    <option value="">Pilih Team Leader</option>
                    @foreach($teamLeaders as $leader)
                    <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('assignLeaderModal').classList.add('hidden')" class="flex-1 border border-outline py-2.5 rounded-lg font-bold text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-lg font-bold text-sm shadow-sm">Tunjuk</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ============================================ --}}
{{-- MODAL: TAMBAH ANGGOTA TIM --}}
{{-- ============================================ --}}
@if(($user->role === 'team_leader' && $project->team_leader_id === $user->id) || $user->role === 'admin')
<div id="addMemberModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-on-surface/30 backdrop-blur-sm" onclick="document.getElementById('addMemberModal').classList.add('hidden')"></div>
    <div class="relative bg-surface rounded-xl border border-outline shadow-2xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-on-surface mb-6">Tambah Anggota Tim</h3>
        <form method="POST" action="{{ route('projects.addMember', $project) }}" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Pilih Anggota (Tenaga Ahli)</label>
                <select name="user_id" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" required>
                    <option value="">Pilih Anggota</option>
                    @foreach($tenagaAhli as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Role dalam Proyek</label>
                <input name="role_in_project" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="Contoh: Anggota, Surveyor, Drafter" value="Anggota"/>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('addMemberModal').classList.add('hidden')" class="flex-1 border border-outline py-2.5 rounded-lg font-bold text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-lg font-bold text-sm shadow-sm">Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL: TAMBAH TUGAS --}}
{{-- ============================================ --}}
<div id="addTaskModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-on-surface/30 backdrop-blur-sm" onclick="document.getElementById('addTaskModal').classList.add('hidden')"></div>
    <div class="relative bg-surface rounded-xl border border-outline shadow-2xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-on-surface mb-6">Tambah Tugas Baru</h3>
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}"/>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Judul Tugas</label>
                <input name="title" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="Nama tugas" required/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Assign ke Anggota</label>
                <select name="assigned_to" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" required>
                    <option value="">Pilih Anggota</option>
                    @foreach($project->members as $member)
                    <option value="{{ $member->user_id }}">{{ $member->user->name }} ({{ $member->role_in_project ?? 'Member' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Deskripsi tugas (opsional)"></textarea>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Deadline</label>
                <input name="deadline" type="date" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none"/>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('addTaskModal').classList.add('hidden')" class="flex-1 border border-outline py-2.5 rounded-lg font-bold text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-lg font-bold text-sm shadow-sm">Buat Tugas</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
