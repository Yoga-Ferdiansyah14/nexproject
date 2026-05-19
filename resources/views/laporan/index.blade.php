@extends('layouts.app')
@section('title', 'Laporan Progress')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-headline font-bold tracking-tight text-on-surface">Laporan Progress</h2>
        <p class="text-on-surface-variant mt-1">Semua laporan progress dari seluruh proyek, diurutkan dari terbaru.</p>
    </div>

    <div class="space-y-4">
        @forelse($reports as $report)
        <div class="bg-white border border-outline rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('projects.show', $report->project) }}" class="text-lg font-bold text-on-surface hover:text-primary transition-colors">
                            {{ $report->project->name ?? '-' }}
                        </a>
                        <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full">Minggu ke-{{ $report->week_number }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                        @if($report->project->client)
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">business</span>
                            {{ $report->project->client->name }}
                        </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">person</span>
                            {{ $report->reporter->name ?? '-' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span>
                            {{ $report->created_at->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="text-3xl font-bold text-primary">{{ $report->percentage }}%</span>
                    <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $report->percentage }}%"></div>
                    </div>
                </div>
            </div>
            <p class="text-on-surface-variant leading-relaxed mt-3">{{ $report->description }}</p>

            <div class="mt-4 pt-3 border-t border-outline-variant flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-700',
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'on_hold' => 'bg-yellow-100 text-yellow-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                        $status = $report->project->status ?? 'draft';
                        $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $colorClass }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </span>
                </div>
                <a href="{{ route('reports.index', $report->project) }}" class="text-xs text-primary hover:underline font-medium flex items-center gap-1">
                    Lihat semua laporan proyek ini
                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-on-surface-variant bg-white rounded-xl border border-outline">
            <span class="material-symbols-outlined text-5xl mb-3 block text-on-surface-variant/50">description</span>
            <p class="text-lg font-medium">Belum ada laporan progress.</p>
            <p class="text-sm mt-1">Laporan akan muncul setelah team leader menginput progress proyek.</p>
        </div>
        @endforelse
    </div>

    @if($reports->hasPages())
    <div class="mt-8">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection
