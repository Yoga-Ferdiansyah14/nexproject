@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h2 class="text-3xl font-headline font-extrabold tracking-tight text-on-surface">User Management</h2>
        <p class="text-on-surface-variant mt-1">Kelola pengguna dan hak akses dalam sistem NexProject.</p>
    </div>
    <button onclick="document.getElementById('addUserPanel').classList.toggle('translate-x-full');document.getElementById('addUserOverlay').classList.toggle('opacity-0');document.getElementById('addUserOverlay').classList.toggle('pointer-events-none')"
            class="bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-bold hover:opacity-90 transition-all shadow-sm">
        Tambah Pengguna
    </button>
</div>

<!-- Table -->
<div class="bg-surface rounded-xl border border-outline overflow-hidden shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-high border-b border-outline">
                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">User</th>
                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Role</th>
                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email</th>
                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Status</th>
                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline">
            @foreach($users as $user)
            <tr class="hover:bg-surface-container-high/50 transition-colors">
                <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm border border-outline">{{ $user->initials }}</div>
                        <div>
                            <div class="font-bold text-on-surface">{{ $user->name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <form method="POST" action="{{ route('users.updateRole', $user) }}" class="inline">
                        @csrf @method('PATCH')
                        <select name="role" onchange="this.form.submit()" class="bg-primary-container text-primary border border-primary/20 px-2.5 py-0.5 rounded-full text-xs font-bold cursor-pointer focus:ring-2 focus:ring-primary outline-none">
                            @foreach(['direktur','marketing','team_leader','tenaga_ahli','admin'] as $role)
                            <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="px-6 py-5 text-sm text-on-surface-variant">{{ $user->email }}</td>
                <td class="px-6 py-5">
                    <span class="flex items-center gap-1.5 text-tertiary text-xs font-bold">
                        <span class="w-1.5 h-1.5 bg-tertiary rounded-full"></span> Active
                    </span>
                </td>
                <td class="px-6 py-5 text-right">
                    <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Stats Cards -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-1 md:col-span-2 bg-surface rounded-xl border border-outline p-6 relative overflow-hidden group shadow-sm">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <span class="material-symbols-outlined text-8xl">shield</span>
        </div>
        <h3 class="text-lg font-bold text-on-surface mb-2">Security Overview</h3>
        <p class="text-on-surface-variant text-sm mb-4 max-w-md">Semua aksi admin tercatat dalam audit log. Sistem menggunakan enkripsi password bcrypt untuk keamanan data pengguna.</p>
    </div>
    <div class="bg-primary text-on-primary rounded-xl p-6 flex flex-col justify-between shadow-md">
        <span class="material-symbols-outlined text-3xl">group_add</span>
        <div>
            <div class="text-3xl font-extrabold tracking-tighter">{{ $totalUsers }}</div>
            <div class="text-sm font-medium opacity-90">Total Pengguna</div>
            <div class="mt-4 w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                <div class="bg-white h-full" style="width: {{ min($totalUsers * 5, 100) }}%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Slide-over Panel -->
<div id="addUserOverlay" onclick="document.getElementById('addUserPanel').classList.add('translate-x-full');this.classList.add('opacity-0','pointer-events-none')"
     class="fixed inset-0 bg-on-surface/30 backdrop-blur-[2px] z-50 transition-opacity opacity-0 pointer-events-none"></div>
<div id="addUserPanel" class="fixed right-0 top-0 h-full w-full max-w-md bg-surface shadow-2xl z-[60] border-l border-outline flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 border-b border-outline flex justify-between items-center bg-surface-container-high">
        <h3 class="text-xl font-headline font-bold text-on-surface">Tambah Pengguna</h3>
        <button onclick="document.getElementById('addUserPanel').classList.add('translate-x-full');document.getElementById('addUserOverlay').classList.add('opacity-0','pointer-events-none')" class="hover:bg-surface-container-highest p-2 rounded-full transition-colors text-on-surface-variant">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <form method="POST" action="{{ route('users.store') }}" class="flex-1 flex flex-col">
        @csrf
        <div class="p-6 flex-1 space-y-6 overflow-y-auto">
            <div class="space-y-2">
                <label class="text-sm font-bold text-on-surface">Nama Lengkap</label>
                <input name="name" class="w-full bg-surface border border-outline rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Nama lengkap" required/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-on-surface">Email Address</label>
                <input name="email" type="email" class="w-full bg-surface border border-outline rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="email@fenomena.com" required/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-on-surface">Password</label>
                <input name="password" type="password" class="w-full bg-surface border border-outline rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Min. 6 karakter" required/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-on-surface">Role</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['direktur'=>'Direktur','marketing'=>'Marketing','team_leader'=>'Team Leader','tenaga_ahli'=>'Tenaga Ahli','admin'=>'Admin'] as $val => $lbl)
                    <label class="relative flex flex-col p-4 border border-outline rounded-xl cursor-pointer hover:bg-surface-container-high/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary-container/30">
                        <input {{ $loop->first ? 'checked' : '' }} class="absolute right-3 top-3 accent-primary" name="role" type="radio" value="{{ $val }}"/>
                        <span class="font-bold text-sm text-on-surface">{{ $lbl }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="p-6 border-t border-outline bg-surface-container-high flex gap-3">
            <button type="button" onclick="document.getElementById('addUserPanel').classList.add('translate-x-full');document.getElementById('addUserOverlay').classList.add('opacity-0','pointer-events-none')" class="flex-1 border border-outline py-2.5 rounded-lg font-bold text-sm hover:bg-surface-container-highest transition-colors text-on-surface">Batal</button>
            <button type="submit" class="flex-1 bg-primary text-on-primary py-2.5 rounded-lg font-bold text-sm hover:opacity-90 transition-opacity shadow-sm">Tambah</button>
        </div>
    </form>
</div>
@endsection
