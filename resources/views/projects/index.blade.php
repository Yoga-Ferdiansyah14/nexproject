@extends('layouts.app')
@section('title', 'Daftar Proyek')

@section('content')
<!-- Header Section -->
<div class="flex flex-col gap-6 mb-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-headline font-bold tracking-tight text-on-surface">
                @if(auth()->user()->role === 'team_leader')
                    Proyek Saya
                @elseif(auth()->user()->role === 'marketing')
                    Proyek
                @else
                    Daftar Proyek
                @endif
            </h1>
            <p class="text-on-surface-variant text-sm mt-1">Kelola dan pantau seluruh progres pengerjaan di satu tempat.</p>
        </div>
    </div>
    <!-- Filter Chips Row -->
    <div class="flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('projects.index') }}" class="px-4 py-1.5 rounded-full {{ !request('status') || request('status') === 'all' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface border border-outline text-on-surface-variant hover:text-primary hover:border-primary/50' }} text-sm font-medium transition-all">All</a>
            <a href="{{ route('projects.index', ['status' => 'aktif']) }}" class="px-4 py-1.5 rounded-full {{ request('status') === 'aktif' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface border border-outline text-on-surface-variant hover:text-primary hover:border-primary/50' }} text-sm font-medium transition-all">Aktif</a>
            <a href="{{ route('projects.index', ['status' => 'tertunda']) }}" class="px-4 py-1.5 rounded-full {{ request('status') === 'tertunda' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface border border-outline text-on-surface-variant hover:text-primary hover:border-primary/50' }} text-sm font-medium transition-all">Tertunda</a>
            <a href="{{ route('projects.index', ['status' => 'selesai']) }}" class="px-4 py-1.5 rounded-full {{ request('status') === 'selesai' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface border border-outline text-on-surface-variant hover:text-primary hover:border-primary/50' }} text-sm font-medium transition-all">Selesai</a>
        </div>
        {{-- Tombol Tambah Proyek: hanya marketing & admin --}}
        @if(in_array(auth()->user()->role, ['admin','marketing']))
        <a href="{{ route('projects.create') }}" class="flex items-center gap-2 text-primary hover:bg-primary/5 px-4 py-2 rounded-lg transition-all font-bold text-sm">
            <span class="material-symbols-outlined text-lg font-bold">add</span>
            Tambah Proyek
        </a>
        @endif
    </div>
</div>

<!-- Project Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($projects as $project)
    @php
        $progress = $project->progress;
        $statusConfig = [
            'aktif' => ['label' => 'Aktif', 'bg' => 'bg-tertiary-container', 'text' => 'text-tertiary', 'border' => 'border-tertiary/20'],
            'tertunda' => ['label' => 'Tertunda', 'bg' => 'bg-error-container', 'text' => 'text-error', 'border' => 'border-error/20'],
            'selesai' => ['label' => 'Selesai', 'bg' => 'bg-surface-container-highest', 'text' => 'text-secondary', 'border' => 'border-outline'],
        ];
        $sc = $statusConfig[$project->status] ?? $statusConfig['aktif'];
    @endphp
    <a href="{{ route('projects.show', $project) }}" class="bg-surface p-5 rounded-xl border border-outline hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5 transition-all group relative overflow-hidden block">
        <div class="absolute top-4 right-4">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $sc['bg'] }} {{ $sc['text'] }} border {{ $sc['border'] }}">{{ $sc['label'] }}</span>
        </div>
        <div class="flex flex-col gap-4">
            <div>
                <h3 class="text-lg font-headline font-bold text-on-surface group-hover:text-primary transition-colors">{{ $project->name }}</h3>
                <p class="text-sm text-on-surface-variant font-medium">{{ $project->client->name ?? '-' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex -space-x-2">
                    @if($project->teamLeader)
                    <div class="w-8 h-8 rounded-full border-2 border-surface bg-primary/10 flex items-center justify-center text-[10px] text-primary font-bold">{{ $project->teamLeader->initials }}</div>
                    @endif
                    @if($project->members->count() > 1)
                    <div class="w-8 h-8 rounded-full border-2 border-surface bg-surface-container-highest flex items-center justify-center text-[10px] text-on-surface-variant font-bold">+{{ $project->members->count() - 1 }}</div>
                    @endif
                </div>
                @if($project->teamLeader)
                <div class="text-[11px] text-on-surface-variant uppercase tracking-tighter font-bold">Lead: {{ $project->teamLeader->name }}</div>
                @else
                <div class="text-[11px] text-orange-500 uppercase tracking-tighter font-bold">Belum ada Team Leader</div>
                @endif
            </div>
            <div class="space-y-2">
                @if($project->status === 'selesai')
                <div class="flex justify-between text-xs font-medium">
                    <span class="text-tertiary">Proyek Berhasil Diselesaikan</span>
                    <span class="text-tertiary">100%</span>
                </div>
                <div class="h-1.5 w-full bg-tertiary/10 rounded-full overflow-hidden">
                    <div class="h-full bg-tertiary" style="width: 100%;"></div>
                </div>
                @elseif($project->status === 'tertunda')
                <div class="flex justify-between text-xs font-medium text-on-surface-variant italic">
                    <span>Menunggu penunjukan Team Leader</span>
                </div>
                <div class="h-1.5 w-full bg-outline rounded-full overflow-hidden opacity-50">
                    <div class="h-full bg-secondary" style="width: {{ $progress }}%;"></div>
                </div>
                @else
                <div class="flex justify-between text-xs font-medium">
                    <span class="text-on-surface-variant">Progress</span>
                    <span class="text-on-surface">{{ $progress }}%</span>
                </div>
                <div class="h-1.5 w-full bg-outline rounded-full overflow-hidden">
                    <div class="h-full bg-primary transition-all duration-500" style="width: {{ $progress }}%;"></div>
                </div>
                @endif
            </div>
            <div class="pt-4 border-t border-outline flex justify-between items-center">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                    <span class="text-[11px] font-medium">{{ $project->start_date ? $project->start_date->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>
    </a>
    @endforeach

    <!-- Add New Project Card: hanya marketing & admin -->
    @if(in_array(auth()->user()->role, ['admin','marketing']))
    <a href="{{ route('projects.create') }}" class="border-2 border-dashed border-outline hover:border-primary hover:bg-primary/5 rounded-xl p-5 flex flex-col items-center justify-center gap-3 transition-all group min-h-[200px]">
        <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center group-hover:bg-primary/20 transition-all">
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">add</span>
        </div>
        <span class="text-sm font-bold text-on-surface-variant group-hover:text-primary">Buat Proyek Baru</span>
    </a>
    @endif
</div>
@endsection
