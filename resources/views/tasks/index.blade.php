@extends('layouts.app')
@section('title', 'Tugas Saya')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Page Header -->
    <div class="flex items-end justify-between mb-8">
        <div>
            <h2 class="text-3xl font-headline font-bold tracking-tight text-on-surface">Tugas Saya</h2>
            <p class="text-on-surface-variant mt-1">Kelola daftar penugasan Anda hari ini.</p>
        </div>
    </div>

    <!-- Task List -->
    <div class="flex flex-col gap-4">
        @forelse($tasks as $task)
        @php
            $borderColor = match($task->status) {
                'proses' => 'bg-primary',
                'selesai' => 'bg-tertiary',
                default => 'bg-surface-container-highest',
            };
            $isUrgent = $task->deadline && $task->deadline->isToday();
        @endphp
        <div class="group relative bg-surface border border-outline rounded-xl overflow-hidden hover:border-primary/50 hover:shadow-md transition-all flex items-stretch">
            <div class="w-1.5 {{ $isUrgent ? 'bg-error' : $borderColor }}"></div>
            <div class="flex-1 p-5 flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-on-surface group-hover:text-primary transition-colors">{{ $task->title }}</h3>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-on-surface-variant font-medium">{{ $task->project->name ?? '-' }}</span>
                        <span class="w-1 h-1 bg-on-surface-variant rounded-full"></span>
                        <div class="flex items-center gap-1 {{ $isUrgent ? 'text-error font-semibold' : 'text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-xs">{{ $isUrgent ? 'priority_high' : 'calendar_today' }}</span>
                            <span>Deadline: {{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <form method="POST" action="{{ route('tasks.updateStatus', $task) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="bg-surface-container-high border border-outline px-4 py-2 rounded-lg text-sm font-medium focus:ring-2 focus:ring-primary outline-none cursor-pointer">
                            <option value="belum" {{ $task->status === 'belum' ? 'selected' : '' }}>Belum Dimulai</option>
                            <option value="proses" {{ $task->status === 'proses' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                            <option value="selesai" {{ $task->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="mt-8 flex flex-col items-center justify-center p-12 border-2 border-dashed border-outline rounded-3xl bg-surface-container-high/50">
            <div class="w-24 h-24 mb-6 relative">
                <div class="absolute inset-0 bg-primary/10 rounded-full blur-2xl"></div>
                <div class="relative flex items-center justify-center w-full h-full bg-white rounded-full border border-outline shadow-sm">
                    <span class="material-symbols-outlined text-4xl text-primary">playlist_add_check</span>
                </div>
            </div>
            <h4 class="text-xl font-bold text-on-surface mb-2">Belum Ada Tugas</h4>
            <p class="text-on-surface-variant text-center max-w-sm">Belum ada tugas yang ditugaskan kepada Anda. Hubungi Team Leader untuk informasi lebih lanjut.</p>
        </div>
        @endforelse
    </div>

    <!-- Stats Grid -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-xl border border-outline shadow-sm">
            <p class="text-sm text-on-surface-variant mb-1">Total Tugas</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-headline font-extrabold text-on-surface">{{ $totalTasks }}</span>
                <span class="text-sm text-primary font-medium">tugas</span>
            </div>
            <div class="mt-4 w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                <div class="bg-primary h-full" style="width: {{ $totalTasks > 0 ? 100 : 0 }}%"></div>
            </div>
        </div>
        <div class="p-6 bg-white rounded-xl border border-outline shadow-sm">
            <p class="text-sm text-on-surface-variant mb-1">Tingkat Penyelesaian</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-headline font-extrabold text-on-surface">{{ $completionRate }}</span>
                <span class="text-sm text-primary font-medium">%</span>
            </div>
            <div class="mt-4 w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                <div class="bg-primary h-full" style="width: {{ $completionRate }}%"></div>
            </div>
        </div>
        <div class="p-6 bg-white rounded-xl border border-outline shadow-sm">
            <p class="text-sm text-on-surface-variant mb-1">Project Aktif</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-headline font-extrabold text-on-surface">{{ str_pad($activeProjects, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="text-sm text-on-surface-variant font-medium">Tim</span>
            </div>
        </div>
    </div>
</div>
@endsection
