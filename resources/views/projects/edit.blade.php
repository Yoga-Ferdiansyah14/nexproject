@extends('layouts.app')
@section('title', 'Edit Proyek')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-8">
        <a class="text-on-surface-variant hover:text-primary flex items-center gap-1 transition-colors" href="{{ route('projects.show', $project) }}">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span class="text-sm">Kembali ke Detail</span>
        </a>
    </div>
    <div class="bg-surface border border-outline rounded-xl overflow-hidden shadow-sm">
        <div class="px-8 py-6 border-b border-outline">
            <h2 class="text-2xl font-headline font-bold tracking-tight text-on-surface">Edit Proyek: {{ $project->name }}</h2>
        </div>
        <form class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12" method="POST" action="{{ route('projects.update', $project) }}">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Nama Proyek</label>
                    <input class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none" name="name" value="{{ old('name', $project->name) }}" required/>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Klien</label>
                    <select class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none" name="client_id" required>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $project->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Deskripsi</label>
                    <textarea class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none resize-none" name="description" rows="6">{{ old('description', $project->description) }}</textarea>
                </div>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Team Leader</label>
                    <select class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none" name="team_leader_id">
                        <option value="">Pilih Team Leader</option>
                        @foreach($teamLeaders as $leader)
                        <option value="{{ $leader->id }}" {{ $project->team_leader_id == $leader->id ? 'selected' : '' }}>{{ $leader->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">Tanggal Mulai</label>
                        <input class="w-full bg-white border border-outline rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary outline-none" name="start_date" type="date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"/>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">Deadline</label>
                        <input class="w-full bg-white border border-outline rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary outline-none" name="end_date" type="date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"/>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Status</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['aktif' => 'Aktif', 'tertunda' => 'Tertunda', 'selesai' => 'Selesai'] as $val => $lbl)
                        <label class="relative cursor-pointer">
                            <input class="sr-only peer" name="status" type="radio" value="{{ $val }}" {{ old('status', $project->status) === $val ? 'checked' : '' }}/>
                            <div class="px-4 py-2 border border-outline rounded-full text-xs font-medium bg-white peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-all">{{ $lbl }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-span-1 md:col-span-2 flex justify-between items-center pt-6 border-t border-outline">
                <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Yakin hapus proyek ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-error hover:bg-error-container rounded-lg text-sm font-medium transition-all">Hapus Proyek</button>
                </form>
                <div class="flex gap-4">
                    <a href="{{ route('projects.show', $project) }}" class="px-6 py-2.5 rounded-lg text-on-surface-variant hover:bg-surface-container-high font-medium">Cancel</a>
                    <button type="submit" class="px-8 py-2.5 rounded-lg bg-primary text-on-primary font-bold shadow-md active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span> Update Proyek
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
