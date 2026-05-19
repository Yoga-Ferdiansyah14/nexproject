<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>NexProject - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "background": "#F8F7F4",
                        "surface": "#FFFFFF",
                        "surface-container": "#FFFFFF",
                        "surface-container-low": "#FFFFFF",
                        "surface-container-high": "#F3F4F6",
                        "surface-container-highest": "#E5E7EB",
                        "primary": "#166534",
                        "on-primary": "#FFFFFF",
                        "primary-container": "#dcfce7",
                        "on-primary-container": "#064e3b",
                        "secondary": "#4B5563",
                        "on-surface": "#111827",
                        "on-surface-variant": "#4B5563",
                        "outline": "#E5E7EB",
                        "outline-variant": "#F3F4F6",
                        "tertiary": "#059669",
                        "on-tertiary": "#FFFFFF",
                        "tertiary-container": "#d1fae5",
                        "on-tertiary-container": "#064e3b",
                        "error": "#DC2626",
                        "on-error": "#FFFFFF",
                        "error-container": "#fee2e2",
                        "on-error-container": "#991b1b",
                        "forest-accent": "#14532d"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Geist"],
                        "display": ["Geist"],
                        "body": ["Geist"],
                        "label": ["Geist"]
                    },
                    "boxShadow": {
                        "soft": "0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05)"
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Geist', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface">

<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-screen w-64 border-r border-outline bg-surface flex flex-col p-4 gap-2 z-20">
    <div class="flex items-center gap-3 px-3 py-4 mb-6">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary text-lg" style="font-variation-settings: 'FILL' 1;">apartment</span>
        </div>
        <div>
            <h1 class="text-xl font-headline font-bold tracking-tighter text-on-surface">NexProject</h1>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-bold">CV Fenomena</p>
        </div>
    </div>

    <nav class="flex-1 flex flex-col gap-1">
        @php $currentRoute = Route::currentRouteName(); @endphp

        {{-- ============================================ --}}
        {{-- SIDEBAR: DIREKTUR --}}
        {{-- Menu: Dashboard | Semua Proyek | Laporan --}}
        {{-- ============================================ --}}
        @if(auth()->user()->role === 'direktur')
            <a class="flex items-center gap-3 {{ $currentRoute === 'dashboard' ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'projects') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('projects.index') }}">
                <span class="material-symbols-outlined">apartment</span>
                <span>Semua Proyek</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'laporan') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('laporan.index') }}">
                <span class="material-symbols-outlined">assessment</span>
                <span>Laporan</span>
            </a>

        {{-- ============================================ --}}
        {{-- SIDEBAR: MARKETING --}}
        {{-- Menu: Dashboard | Proyek | Klien --}}
        {{-- ============================================ --}}
        @elseif(auth()->user()->role === 'marketing')
            <a class="flex items-center gap-3 {{ $currentRoute === 'dashboard' ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'projects') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('projects.index') }}">
                <span class="material-symbols-outlined">apartment</span>
                <span>Proyek</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'clients') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('clients.index') }}">
                <span class="material-symbols-outlined">groups</span>
                <span>Klien</span>
            </a>

        {{-- ============================================ --}}
        {{-- SIDEBAR: TEAM LEADER --}}
        {{-- Menu: Dashboard | Proyek Saya | Tugas | Laporan Progres --}}
        {{-- ============================================ --}}
        @elseif(auth()->user()->role === 'team_leader')
            <a class="flex items-center gap-3 {{ $currentRoute === 'dashboard' ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'projects') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('projects.index') }}">
                <span class="material-symbols-outlined">apartment</span>
                <span>Proyek Saya</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'tasks') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('tasks.index') }}">
                <span class="material-symbols-outlined">task_alt</span>
                <span>Tugas</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'laporan') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('laporan.index') }}">
                <span class="material-symbols-outlined">assessment</span>
                <span>Laporan Progres</span>
            </a>

        {{-- ============================================ --}}
        {{-- SIDEBAR: TENAGA AHLI --}}
        {{-- Menu: Tugas Saya (only) --}}
        {{-- ============================================ --}}
        @elseif(auth()->user()->role === 'tenaga_ahli')
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'tasks') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('tasks.index') }}">
                <span class="material-symbols-outlined">task_alt</span>
                <span>Tugas Saya</span>
            </a>

        {{-- ============================================ --}}
        {{-- SIDEBAR: ADMIN --}}
        {{-- Menu: Dashboard | Semua Proyek | Klien | Tasks | User Management --}}
        {{-- ============================================ --}}
        @elseif(auth()->user()->role === 'admin')
            <a class="flex items-center gap-3 {{ $currentRoute === 'dashboard' ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'projects') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('projects.index') }}">
                <span class="material-symbols-outlined">apartment</span>
                <span>Semua Proyek</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'clients') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('clients.index') }}">
                <span class="material-symbols-outlined">groups</span>
                <span>Klien</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'tasks') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('tasks.index') }}">
                <span class="material-symbols-outlined">task_alt</span>
                <span>Tasks</span>
            </a>
            <a class="flex items-center gap-3 {{ str_starts_with($currentRoute, 'users') ? 'bg-primary text-on-primary rounded-lg border-l-4 border-green-800' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high' }} px-3 py-2 rounded-lg font-body text-sm tracking-normal font-medium transition-colors duration-200 active:scale-95" href="{{ route('users.index') }}">
                <span class="material-symbols-outlined">settings</span>
                <span>User Management</span>
            </a>
        @endif
    </nav>

    <div class="pt-4 mt-4 border-t border-outline">
        @if(in_array(auth()->user()->role, ['admin','marketing']))
        <a href="{{ route('projects.create') }}" class="w-full bg-primary text-on-primary font-bold py-2.5 px-4 rounded-lg active:scale-95 transition-transform duration-150 mb-4 shadow-sm block text-center text-sm">
            New Project
        </a>
        @endif
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ auth()->user()->initials }}</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-on-surface-variant capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-on-surface-variant hover:text-error transition-colors" title="Logout">
                    <span class="material-symbols-outlined text-lg">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- TopAppBar -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] h-16 border-b border-outline z-10 bg-surface/80 backdrop-blur-md flex justify-between items-center px-8">
    <div class="flex items-center gap-4 flex-1">
        @if(auth()->user()->role !== 'tenaga_ahli')
        <div class="relative w-full max-w-md">
            <form method="GET" action="{{ route('projects.index') }}" id="searchForm">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full bg-surface-container-high border-none rounded-full pl-10 pr-4 py-1.5 text-sm focus:ring-2 focus:ring-primary" placeholder="Search projects..." type="text" onkeydown="if(event.key==='Enter'){document.getElementById('searchForm').submit();}"/>
            </form>
        </div>
        @endif
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <button class="hover:bg-surface-container-high rounded-full p-2 transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">notifications</span>
            </button>
            <button class="hover:bg-surface-container-high rounded-full p-2 transition-all">
                <span class="material-symbols-outlined text-on-surface-variant">help_outline</span>
            </button>
        </div>
        <div class="h-6 w-[1px] bg-outline"></div>
        <div class="flex items-center gap-3 ml-2">
            {{-- Tombol Create Project: hanya untuk marketing dan admin --}}
            @if(in_array(auth()->user()->role, ['admin','marketing']))
            <a href="{{ route('projects.create') }}" class="px-4 py-1.5 text-sm font-bold bg-primary text-on-primary rounded-lg shadow-sm">Create Project</a>
            @endif
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold border border-outline">{{ auth()->user()->initials }}</div>
        </div>
    </div>
</header>

<!-- Main Canvas -->
<main class="ml-64 mt-16 p-8 min-h-screen bg-background">
    @if(session('success'))
    <div class="mb-6 bg-tertiary-container border border-tertiary/20 text-on-tertiary-container px-4 py-3 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-tertiary">check_circle</span>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-error-container border border-error/20 text-on-error-container px-4 py-3 rounded-lg">
        <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
