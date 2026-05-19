<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>NexProject - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Geist', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .floating-label-group:focus-within label,
        .floating-label-group input:not(:placeholder-shown) + label {
            transform: translateY(-1.25rem) scale(0.85);
            color: #166534;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#166534",
                        "on-primary": "#ffffff",
                        "on-surface": "#0f172a",
                        "on-surface-variant": "#4a6070",
                        "surface": "#ffffff",
                        "surface-container-low": "#f8f9fa",
                        "outline": "#94a3b8",
                        "outline-variant": "#e2e8f0",
                        "background": "#f8f7f4",
                        "error": "#b91c1c"
                    },
                    "fontFamily": {
                        "headline": ["Geist"],
                        "body": ["Geist"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-surface min-h-screen flex selection:bg-primary/20">
<main class="flex w-full min-h-screen">
    <!-- Left: Branding Panel -->
    <section class="hidden lg:flex flex-col justify-between w-1/2 bg-surface-container-low p-16 relative overflow-hidden border-r border-outline-variant">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
            <div class="w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23166534\' fill-opacity=\'0.15\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-repeat: repeat;"></div>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary font-bold" style="font-variation-settings: 'FILL' 1;">apartment</span>
                </div>
                <span class="text-2xl font-headline font-bold tracking-tighter text-on-surface">NexProject</span>
            </div>
        </div>
        <div class="relative z-10 max-w-md">
            <h1 class="text-5xl font-headline font-bold tracking-tight text-on-surface leading-tight mb-4">
                Kelola Proyek Lebih Cerdas
            </h1>
            <p class="text-on-surface-variant text-lg font-body">
                Platform manajemen proyek terpadu untuk meningkatkan efisiensi dan transparansi dalam setiap fase konstruksi Anda.
            </p>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-4 text-sm text-on-surface-variant">
                <div class="w-8 h-[1px] bg-outline"></div>
                <p class="font-medium tracking-wide uppercase">Trusted by CV Fenomena Professionals</p>
            </div>
        </div>
    </section>

    <!-- Right: Login Form -->
    <section class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 md:p-16 lg:p-24 bg-background">
        <div class="w-full max-w-sm">
            <!-- Mobile Branding -->
            <div class="lg:hidden flex items-center gap-2 mb-12">
                <div class="w-8 h-8 bg-primary rounded flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-sm" style="font-variation-settings: 'FILL' 1;">apartment</span>
                </div>
                <span class="text-xl font-headline font-bold tracking-tighter text-on-surface">NexProject</span>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-headline font-bold text-on-surface tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-on-surface-variant mt-2 font-body">Silakan masuk untuk melanjutkan pekerjaan Anda.</p>
            </div>

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('login.post') }}">
                @csrf
                <!-- Email Field -->
                <div class="relative floating-label-group">
                    <input class="peer w-full bg-surface border border-outline rounded-lg px-4 py-4 pt-6 text-on-surface placeholder-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                           id="email" name="email" placeholder=" " type="email" value="{{ old('email') }}" required/>
                    <label class="absolute left-4 top-4 text-on-surface-variant pointer-events-none transition-all duration-200 origin-left" for="email">
                        Alamat Email
                    </label>
                </div>
                <!-- Password Field -->
                <div class="relative floating-label-group">
                    <input class="peer w-full bg-surface border border-outline rounded-lg px-4 py-4 pt-6 text-on-surface placeholder-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                           id="password" name="password" placeholder=" " type="password" required/>
                    <label class="absolute left-4 top-4 text-on-surface-variant pointer-events-none transition-all duration-200 origin-left" for="password">
                        Kata Sandi
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input class="w-4 h-4 rounded border-outline bg-surface text-primary focus:ring-primary" type="checkbox" name="remember"/>
                        <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat saya</span>
                    </label>
                </div>
                <button class="w-full bg-primary hover:bg-opacity-90 text-on-primary font-bold py-4 rounded-lg transition-all active:scale-[0.98] flex items-center justify-center gap-2 shadow-sm" type="submit">
                    <span>Masuk ke NexProject</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </form>

            <div class="mt-12 pt-12 border-t border-outline-variant text-center">
                <p class="text-sm text-on-surface-variant">
                    Belum memiliki akun?
                    <span class="text-primary font-medium">Hubungi administrator</span>
                </p>
            </div>
            <footer class="mt-24 text-center">
                <p class="text-xs text-on-surface-variant tracking-wider font-semibold">© 2026 CV FENOMENA</p>
            </footer>
        </div>
    </section>
</main>
</body>
</html>
