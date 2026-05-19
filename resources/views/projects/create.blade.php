@extends('layouts.app')
@section('title', 'Tambah Proyek')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-2 mb-8">
        <a class="text-on-surface-variant hover:text-primary flex items-center gap-1 transition-colors" href="{{ route('projects.index') }}">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span class="text-sm">Kembali ke Proyek</span>
        </a>
    </div>
    <div class="bg-surface border border-outline rounded-xl overflow-hidden shadow-sm">
        <div class="px-8 py-6 border-b border-outline">
            <h2 class="text-2xl font-headline font-bold tracking-tight text-on-surface">Informasi Proyek Baru</h2>
            <p class="text-on-surface-variant text-sm">Lengkapi detail proyek. Team Leader akan ditunjuk oleh Direktur setelah proyek dibuat.</p>
        </div>
        <form class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12" method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Nama Proyek</label>
                    <input class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" name="name" placeholder="Masukkan nama proyek" type="text" value="{{ old('name') }}" required/>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Klien</label>
                    <select class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none" name="client_id" required>
                        <option value="">Pilih Klien</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-on-surface-variant">Deskripsi</label>
                    <textarea class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none resize-none" name="description" placeholder="Jelaskan ruang lingkup proyek..." rows="6">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">Tanggal Mulai</label>
                        <input class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none" name="start_date" type="date" value="{{ old('start_date') }}"/>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">Deadline</label>
                        <input class="w-full bg-white border border-outline rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none" name="end_date" type="date" value="{{ old('end_date') }}"/>
                    </div>
                </div>
                <div class="mt-4 p-4 rounded-lg bg-orange-50 border border-orange-200 flex gap-4">
                    <span class="material-symbols-outlined text-orange-500">info</span>
                    <div>
                        <p class="text-xs text-orange-800 leading-relaxed font-medium">Proyek baru akan dibuat dengan status <strong>Tertunda</strong>.</p>
                        <p class="text-xs text-orange-700 leading-relaxed mt-1">Direktur akan menunjuk Team Leader, dan status akan otomatis berubah ke <strong>Aktif</strong>.</p>
                    </div>
                </div>
            </div>
            <div class="col-span-1 md:col-span-2 flex justify-end gap-4 pt-6 border-t border-outline">
                <a href="{{ route('projects.index') }}" class="px-6 py-2.5 rounded-lg text-on-surface-variant hover:bg-surface-container-high font-medium transition-all">Cancel</a>
                <button type="submit" class="px-8 py-2.5 rounded-lg bg-primary text-on-primary font-bold shadow-md shadow-primary/20 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span> Simpan Proyek
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
