@extends('layouts.app')
@section('title', 'Input Laporan - ' . $project->name)

@section('content')
<div class="flex justify-center">
    <div class="max-w-2xl w-full">
        <div class="bg-white border border-outline rounded-xl overflow-hidden shadow-sm">
            <!-- Banner -->
            <div class="relative h-48 w-full bg-gradient-to-br from-primary/20 via-tertiary/10 to-primary/5">
                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/40 to-transparent"></div>
                <div class="absolute bottom-6 left-8">
                    <h1 class="text-3xl font-display font-extrabold tracking-tight text-on-surface mb-1">{{ $project->name }}</h1>
                    <p class="text-secondary text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs">location_on</span>
                        {{ $project->client->name ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('reports.store', $project) }}" class="p-8 space-y-10">
                @csrf
                <!-- Week & Title -->
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-headline font-bold text-on-surface">Input Progress Report</h2>
                        <p class="text-on-surface-variant text-sm">Laporkan perkembangan proyek untuk minggu ini.</p>
                    </div>
                    <div class="flex flex-col items-center gap-1 bg-surface-container-high p-3 rounded-lg border border-outline">
                        <span class="text-[10px] uppercase tracking-widest text-secondary font-bold">Minggu ke-</span>
                        <input type="number" name="week_number" value="{{ old('week_number', $nextWeek) }}" min="1"
                               class="w-16 text-center text-2xl font-bold font-display text-primary bg-transparent border-none focus:ring-0 p-0"/>
                    </div>
                </div>

                <!-- Percentage Slider -->
                <div class="space-y-6">
                    <div class="flex items-end justify-between">
                        <label class="text-sm font-bold text-on-surface tracking-wide uppercase">Overall Completion</label>
                        <div class="flex items-baseline gap-1">
                            <span id="percentDisplay" class="text-6xl font-display font-extrabold text-primary tabular-nums">{{ old('percentage', $latestPercentage) }}</span>
                            <span class="text-2xl font-bold text-secondary">%</span>
                        </div>
                    </div>
                    <div class="relative pt-4">
                        <input type="range" name="percentage" min="0" max="100" value="{{ old('percentage', $latestPercentage) }}"
                               class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary"
                               oninput="document.getElementById('percentDisplay').textContent = this.value"/>
                        <div class="flex justify-between text-[10px] text-on-surface-variant font-bold mt-3 px-1">
                            <span>0%</span><span>25%</span><span>50%</span><span>75%</span><span>100%</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-primary text-lg">format_list_bulleted</span>
                        Deskripsi Pekerjaan
                    </label>
                    <textarea name="description" rows="5" required
                              class="w-full bg-white border border-outline rounded-lg p-4 text-on-surface focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none resize-none font-body leading-relaxed placeholder:text-on-surface-variant/50"
                              placeholder="Jelaskan progress dan milestone yang dicapai minggu ini...">{{ old('description') }}</textarea>
                </div>

                <!-- Submit -->
                <div class="space-y-4">
                    <button type="submit" class="w-full bg-primary text-on-primary font-display font-extrabold text-lg py-5 rounded-xl flex items-center justify-center gap-3 active:scale-[0.98] transition-all hover:bg-primary/90 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">send</span>
                        Submit Report
                    </button>
                    <p class="text-center text-xs text-on-surface-variant font-medium">
                        <a href="{{ route('projects.show', $project) }}" class="text-primary hover:underline">← Kembali ke detail proyek</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
