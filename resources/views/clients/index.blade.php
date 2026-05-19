@extends('layouts.app')
@section('title', 'Daftar Klien')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h2 class="text-3xl font-headline font-extrabold tracking-tight text-on-surface">Daftar Klien</h2>
        <p class="text-on-surface-variant mt-1">Kelola data klien dan relasi bisnis CV Fenomena.</p>
    </div>
    <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-bold hover:opacity-90 transition-all shadow-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">add</span> Tambah Klien
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($clients as $client)
    <div class="bg-surface border border-outline rounded-xl p-6 hover:border-primary/40 hover:shadow-lg transition-all shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">{{ $client->type === 'instansi' ? 'business' : 'person' }}</span>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-tertiary-container text-tertiary border border-tertiary/20">{{ $client->type }}</span>
        </div>
        <h3 class="text-lg font-bold text-on-surface mb-1">{{ $client->name }}</h3>
        <p class="text-sm text-on-surface-variant mb-3">{{ $client->address ?? 'Alamat belum diisi' }}</p>
        <div class="flex items-center gap-4 text-xs text-on-surface-variant">
            @if($client->phone)
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs">phone</span> {{ $client->phone }}</span>
            @endif
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs">folder</span> {{ $client->projects_count }} proyek</span>
        </div>
    </div>
    @endforeach
</div>

<!-- Add Client Modal -->
<div id="addClientModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-on-surface/30 backdrop-blur-sm" onclick="document.getElementById('addClientModal').classList.add('hidden')"></div>
    <div class="relative bg-surface rounded-xl border border-outline shadow-2xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-on-surface mb-6">Tambah Klien Baru</h3>
        <form method="POST" action="{{ route('clients.store') }}" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Nama Klien</label>
                <input name="name" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="Nama klien" required/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Telepon</label>
                <input name="phone" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="08xxx"/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Alamat</label>
                <textarea name="address" rows="2" class="w-full border border-outline rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Alamat lengkap"></textarea>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-on-surface-variant">Tipe</label>
                <div class="flex gap-3">
                    <label class="relative cursor-pointer"><input class="sr-only peer" name="type" type="radio" value="instansi" checked/><div class="px-4 py-2 border border-outline rounded-full text-xs font-medium peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-all">Instansi</div></label>
                    <label class="relative cursor-pointer"><input class="sr-only peer" name="type" type="radio" value="perorangan"/><div class="px-4 py-2 border border-outline rounded-full text-xs font-medium peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-all">Perorangan</div></label>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('addClientModal').classList.add('hidden')" class="flex-1 border border-outline py-2.5 rounded-lg font-bold text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-lg font-bold text-sm shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
