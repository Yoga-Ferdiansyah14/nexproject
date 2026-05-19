@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="mb-8">
    <h2 class="text-3xl font-headline font-bold tracking-tight text-on-surface">Selamat Datang, {{ $user->name }}</h2>
    <div class="flex items-center gap-2 mt-1 text-on-surface-variant">
        <span class="material-symbols-outlined text-sm">calendar_today</span>
        <span class="text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>

<!-- Metric Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Proyek Aktif -->
    <div class="bg-surface border border-outline rounded-lg p-5 flex flex-col gap-4 shadow-soft">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary">rocket_launch</span>
            </div>
            <span class="text-tertiary text-xs font-bold bg-tertiary/10 px-2 py-1 rounded">Aktif</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-medium">Proyek Aktif</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $totalProyekAktif }}</h3>
        </div>
    </div>
    <!-- Proyek Selesai -->
    <div class="bg-surface border border-outline rounded-lg p-5 flex flex-col gap-4 shadow-soft">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">task_alt</span>
            </div>
            <span class="text-primary text-xs font-bold bg-primary/10 px-2 py-1 rounded">Selesai</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-medium">Proyek Selesai</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $totalProyekSelesai }}</h3>
        </div>
    </div>
    <!-- Total Klien -->
    <div class="bg-surface border border-outline rounded-lg p-5 flex flex-col gap-4 shadow-soft">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-indigo-600">handshake</span>
            </div>
            <span class="text-indigo-600 text-xs font-bold bg-indigo-50 px-2 py-1 rounded">Total</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-medium">Total Klien</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $totalKlien }}</h3>
        </div>
    </div>
    <!-- Anggota Tim -->
    <div class="bg-surface border border-outline rounded-lg p-5 flex flex-col gap-4 shadow-soft">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-600">group</span>
            </div>
            <span class="text-on-surface-variant text-xs">Total Aktif</span>
        </div>
        <div>
            <p class="text-on-surface-variant text-sm font-medium">Anggota Tim</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $totalAnggota }}</h3>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Proyek Berjalan Table -->
    <div class="lg:w-[65%] bg-surface rounded-xl border border-outline overflow-hidden shadow-soft">
        <div class="p-6 border-b border-outline flex justify-between items-center bg-surface">
            <h4 class="text-lg font-bold text-on-surface">Proyek Berjalan</h4>
            <a href="{{ route('projects.index') }}" class="text-sm text-primary font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-high/50 text-on-surface-variant text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Nama Proyek</th>
                        <th class="px-6 py-4 font-bold">Klien</th>
                        <th class="px-6 py-4 font-bold">Leader</th>
                        <th class="px-6 py-4 font-bold">Progress</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline">
                    @forelse($proyekBerjalan as $proyek)
                    <tr class="hover:bg-surface-container-high transition-colors cursor-pointer" onclick="window.location='{{ route('projects.show', $proyek) }}'">
                        <td class="px-6 py-4 font-medium text-on-surface">
                            <a href="{{ route('projects.show', $proyek) }}" class="hover:text-primary">{{ $proyek->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $proyek->client->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($proyek->teamLeader)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-surface-container-highest flex items-center justify-center text-[10px] border border-outline text-on-surface font-bold">{{ $proyek->teamLeader->initials }}</div>
                                <span class="text-sm text-on-surface">{{ $proyek->teamLeader->name }}</span>
                            </div>
                            @else
                            <span class="text-sm text-orange-500 font-medium">Belum ditunjuk</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php $progress = $proyek->progress; @endphp
                            <div class="w-full bg-surface-container-highest rounded-full h-1.5 max-w-[100px]">
                                <div class="bg-tertiary h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-[10px] text-tertiary font-bold mt-1 block">{{ $progress }}%</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'aktif' => 'bg-tertiary/10 text-tertiary',
                                    'selesai' => 'bg-primary/10 text-primary',
                                    'tertunda' => 'bg-orange-100 text-orange-600',
                                ];
                                $statusLabels = ['aktif' => 'On Track', 'selesai' => 'Selesai', 'tertunda' => 'Tertunda'];
                            @endphp
                            <span class="px-2 py-1 rounded-full {{ $statusColors[$proyek->status] ?? '' }} text-[10px] font-bold uppercase tracking-tight">
                                {{ $statusLabels[$proyek->status] ?? $proyek->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">Belum ada proyek berjalan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="lg:w-[35%] bg-surface rounded-xl border border-outline flex flex-col shadow-soft">
        <div class="p-6 border-b border-outline">
            <h4 class="text-lg font-bold text-on-surface">Aktivitas Terbaru</h4>
        </div>
        <div class="p-6 flex flex-col gap-6 flex-1 overflow-y-auto max-h-[400px]">
            @forelse($recentActivities as $activity)
            <div class="flex gap-4">
                <div class="relative shrink-0">
                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline flex items-center justify-center text-xs font-bold text-primary">
                        {{ $activity['initials'] }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-{{ $activity['type'] === 'success' ? 'tertiary' : 'primary' }} rounded-full border-2 border-surface flex items-center justify-center">
                        <span class="material-symbols-outlined text-[10px] text-white" style="font-variation-settings: 'wght' 700;">{{ $activity['type'] === 'success' ? 'check' : 'edit' }}</span>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-on-surface">
                        <span class="font-bold text-primary">{{ $activity['user'] }}</span>
                        {{ $activity['action'] }}
                        <span class="text-on-surface-variant font-medium">{{ $activity['target'] }}</span>
                    </p>
                    <span class="text-[10px] text-on-surface-variant mt-1 block">{{ $activity['time'] }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant text-center py-4">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
