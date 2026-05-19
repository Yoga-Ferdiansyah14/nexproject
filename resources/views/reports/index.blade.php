@extends('layouts.app')
@section('title', 'Laporan Progress - ' . $project->name)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-8">
        <a class="text-on-surface-variant hover:text-primary flex items-center gap-1 transition-colors" href="{{ route('projects.show', $project) }}">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span class="text-sm">Kembali ke {{ $project->name }}</span>
        </a>
    </div>

    <div class="flex justify-between items-start mb-8">
        <div>
            <h2 class="text-3xl font-headline font-bold tracking-tight text-on-surface">Laporan Progress</h2>
            <p class="text-on-surface-variant mt-1">{{ $project->name }}</p>
        </div>
        {{-- Tombol Input Laporan: hanya team_leader proyek ini & admin (direktur hanya lihat) --}}
        @if(in_array(auth()->user()->role, ['admin','team_leader']))
        <a href="{{ route('reports.create', $project) }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-sm">add</span> Input Laporan Baru
        </a>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($reports as $report)
        <div class="bg-white border border-outline rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full">Minggu ke-{{ $report->week_number }}</span>
                    <span class="text-xs text-on-surface-variant">oleh {{ $report->reporter->name ?? '-' }}</span>
                    <span class="text-xs text-on-surface-variant">• {{ $report->created_at->format('d M Y H:i') }}</span>
                </div>
                <span class="text-3xl font-bold text-primary">{{ $report->percentage }}%</span>
            </div>
            <p class="text-on-surface-variant leading-relaxed">{{ $report->description }}</p>
        </div>
        @empty
        <div class="text-center py-12 text-on-surface-variant">
            <span class="material-symbols-outlined text-4xl mb-2 block">description</span>
            <p>Belum ada laporan progress untuk proyek ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
