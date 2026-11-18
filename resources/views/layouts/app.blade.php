<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') · SK03 Bioakustik</title>

    <script>
        // Tema gelap: ikuti preferensi sistem kecuali pengguna sudah memilih.
        (function () {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        hutan: { 50: '#ecfdf3', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857', 900: '#064e3b' }
                    }
                }
            }
        };
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .field { @apply w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100; }
            .label { @apply mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-200; }
            .card { @apply rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900; }
            .btn-primary { @apply inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 active:scale-95; }
            .btn-ghost { @apply inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800; }
            .btn-danger { @apply inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 active:scale-95; }
            .th { @apply px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500; }
            .td { @apply px-4 py-3 text-sm text-slate-700 dark:text-slate-300; }
        }
        [x-cloak] { display: none !important; }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        // Komponen Alpine kustom didaftarkan sebelum Alpine berjalan.
        document.addEventListener('alpine:init', () => {
            // Multi-select dengan pencarian, pilih semua, dan penanggung jawab (atribut pivot 'peran').
            Alpine.data('multiSelect', (cfg) => ({
                items: cfg.items,
                selected: cfg.selected,
                pj: cfg.pj,
                usePj: cfg.usePj,
                cari: '',
                init() {
                    if (this.usePj && this.pj === null && this.selected.length) this.pj = this.selected[0];
                },
                get filtered() {
                    const q = this.cari.trim().toLowerCase();
                    if (!q) return this.items;
                    return this.items.filter(i => (i.label + ' ' + (i.sub || '')).toLowerCase().includes(q));
                },
                has(id) { return this.selected.includes(id); },
                toggle(id) {
                    if (this.has(id)) {
                        this.selected = this.selected.filter(x => x !== id);
                        if (this.pj === id) this.pj = this.selected[0] ?? null;
                    } else {
                        this.selected.push(id);
                        if (this.usePj && this.pj === null) this.pj = id;
                    }
                },
                semua() {
                    this.selected = this.items.map(i => i.id);
                    if (this.usePj && this.pj === null) this.pj = this.selected[0] ?? null;
                },
                kosongkan() { this.selected = []; this.pj = null; },
            }));

            // Baris detail analisis dinamis (tambah/hapus tanpa reload).
            Alpine.data('analisisRows', (awal) => ({
                rows: awal.length ? awal : [{ jenis_analisis: '', frekuensi_dominan_hz: '', tingkat_keyakinan: 80, hasil: '' }],
                blank() { return { jenis_analisis: '', frekuensi_dominan_hz: '', tingkat_keyakinan: 80, hasil: '' }; },
                tambah() { if (this.rows.length < 20) this.rows.push(this.blank()); },
                hapus(i) { this.rows.splice(i, 1); if (!this.rows.length) this.rows.push(this.blank()); },
            }));

            // Pratinjau durasi detik -> mm:ss secara langsung.
            Alpine.data('durasi', (awal) => ({
                detik: awal,
                get format() {
                    const d = Math.max(0, parseInt(this.detik || 0, 10));
                    return String(Math.floor(d / 60)).padStart(2, '0') + ':' + String(d % 60).padStart(2, '0');
                },
            }));

            // Animasi angka pada kartu statistik.
            Alpine.data('countUp', (target) => ({
                display: 0,
                init() {
                    const dur = 900, start = performance.now();
                    const step = (now) => {
                        const p = Math.min((now - start) / dur, 1);
                        this.display = Math.round(target * (1 - Math.pow(1 - p, 3)));
                        if (p < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                },
            }));

            // Pencarian langsung: kirim form otomatis saat berhenti mengetik.
            Alpine.data('liveSearch', () => ({
                timer: null,
                kirim(el) {
                    clearTimeout(this.timer);
                    this.timer = setTimeout(() => el.form.requestSubmit(), 450);
                },
            }));
        });

        function appShell() {
            return {
                dark: document.documentElement.classList.contains('dark'),
                sidebar: false,
                toggleTheme() {
                    this.dark = !this.dark;
                    document.documentElement.classList.toggle('dark', this.dark);
                    localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: this.dark }));
                },
            };
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased transition-colors dark:bg-slate-950 dark:text-slate-100"
      x-data="appShell()">

    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('partials.topbar')

            <main class="mx-auto w-full max-w-7xl flex-1 space-y-6 p-4 sm:p-6 lg:p-8">
                @include('partials.flash')
                @yield('content')
            </main>

            <footer class="border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-500 dark:border-slate-800">
                SK03 · Laboratorium Bioakustik · Laravel {{ app()->version() }}
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
