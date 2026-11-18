@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas Laboratorium Bioakustik')

@section('content')
    @php
        $jam = intdiv($statistik['total_durasi'], 3600);
        $menit = intdiv($statistik['total_durasi'] % 3600, 60);
    @endphp

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 p-6 text-white shadow-xl sm:p-8">
        <div class="absolute inset-0 opacity-20" aria-hidden="true">
            <div class="absolute -left-10 bottom-0 flex h-full items-end gap-1.5" x-data="{ t: 0 }" x-init="setInterval(() => t++, 120)">
                @for ($i = 0; $i < 28; $i++)
                    <span class="w-2 rounded-t bg-white transition-all duration-300"
                          :style="`height: ${20 + Math.abs(Math.sin(t / 6 + {{ $i }} * 0.6)) * 70}%`"></span>
                @endfor
            </div>
        </div>
        <div class="relative">
            <p class="text-sm font-semibold text-emerald-100">Selamat datang 👋</p>
            <h2 class="mt-1 text-2xl font-extrabold sm:text-3xl">Pantau suara alam, kelola data dengan rapi.</h2>
            <p class="mt-2 max-w-2xl text-sm text-emerald-50/90">
                {{ number_format($statistik['rekaman'], 0, ',', '.') }} rekaman tersimpan dari
                {{ $statistik['spesies'] }} spesies di {{ $statistik['lokasi'] }} lokasi perekaman.
                Total durasi {{ $jam }} jam {{ $menit }} menit.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('rekaman.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-emerald-700 shadow transition hover:-translate-y-0.5">+ Rekaman baru</a>
                <a href="{{ route('spesies.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/30 transition hover:bg-white/25">+ Spesies</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
        <x-stat-card label="Rekaman" :value="$statistik['rekaman']" tone="emerald" />
        <x-stat-card label="Spesies" :value="$statistik['spesies']" tone="teal" />
        <x-stat-card label="Lokasi" :value="$statistik['lokasi']" tone="sky" />
        <x-stat-card label="Peneliti" :value="$statistik['peneliti']" tone="emerald" />
        <x-stat-card label="Total detik" :value="$statistik['total_durasi']" hint="Akumulasi durasi" tone="amber" />
        <x-stat-card label="Terancam" :value="$statistik['terancam']" hint="VU / EN / CR" tone="rose" />
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="card p-5 xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold">Rekaman per bulan</h3>
                    <p class="text-xs text-slate-500">6 bulan terakhir</p>
                </div>
            </div>
            <div class="h-64"><canvas id="chartBulan" aria-label="Grafik rekaman per bulan"></canvas></div>
        </div>

        <div class="card p-5">
            <h3 class="font-bold">Spesies per kategori</h3>
            <p class="mb-4 text-xs text-slate-500">Komposisi data taksonomi</p>
            <div class="h-64"><canvas id="chartKategori" aria-label="Grafik spesies per kategori"></canvas></div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="card overflow-hidden xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <h3 class="font-bold">Rekaman terbaru</h3>
                <a href="{{ route('rekaman.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-300">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-950/50">
                        <tr>
                            <th class="th">Tanggal</th>
                            <th class="th">Spesies</th>
                            <th class="th">Lokasi</th>
                            <th class="th">Kualitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($terbaru as $r)
                            <tr class="transition hover:bg-emerald-50/50 dark:hover:bg-slate-800/50">
                                <td class="td whitespace-nowrap">{{ $r->tanggal_rekam->translatedFormat('d M Y') }}</td>
                                <td class="td font-semibold">
                                    <a href="{{ route('rekaman.show', $r) }}" class="hover:text-emerald-700 dark:hover:text-emerald-300">{{ $r->spesies->nama_umum }}</a>
                                </td>
                                <td class="td">{{ $r->lokasi->nama }}</td>
                                <td class="td"><span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold dark:bg-slate-800">{{ $r->kualitas }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="td text-center text-slate-500">Belum ada rekaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-5">
            <h3 class="font-bold">Kualitas rekaman</h3>
            <p class="mb-4 text-xs text-slate-500">Distribusi seluruh rekaman</p>
            <div class="h-56"><canvas id="chartKualitas" aria-label="Grafik kualitas rekaman"></canvas></div>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ route('spesies.index') }}" class="btn-ghost text-xs">Kelola spesies</a>
                <a href="{{ route('peneliti.index') }}" class="btn-ghost text-xs">Kelola peneliti</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const palet = ['#10b981', '#14b8a6', '#0ea5e9', '#f59e0b', '#f43f5e', '#8b5cf6', '#64748b'];
        const grid = () => document.documentElement.classList.contains('dark') ? 'rgba(148,163,184,.15)' : 'rgba(100,116,139,.15)';
        const teks = () => document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569';
        const instances = [];

        function buat(id, config) {
            const el = document.getElementById(id);
            if (el) instances.push(new Chart(el, config));
        }

        const bulan = @json($perBulan);
        const kategori = @json($perKategori);
        const kualitas = @json($kualitas);

        buat('chartBulan', {
            type: 'line',
            data: {
                labels: bulan.labels,
                datasets: [{
                    label: 'Rekaman',
                    data: bulan.data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,.18)',
                    fill: true,
                    tension: .4,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                }],
            },
            options: {
                maintainAspectRatio: false,
                animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: teks() }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { color: teks(), precision: 0 }, grid: { color: grid() } },
                },
            },
        });

        buat('chartKategori', {
            type: 'doughnut',
            data: { labels: kategori.labels, datasets: [{ data: kategori.data, backgroundColor: palet, borderWidth: 0 }] },
            options: {
                maintainAspectRatio: false, cutout: '68%',
                animation: { animateRotate: true, duration: 1100 },
                plugins: { legend: { position: 'bottom', labels: { color: teks(), boxWidth: 10 } } },
            },
        });

        buat('chartKualitas', {
            type: 'bar',
            data: { labels: kualitas.labels, datasets: [{ label: 'Jumlah', data: kualitas.data, backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'], borderRadius: 8 }] },
            options: {
                maintainAspectRatio: false,
                animation: { duration: 1000 },
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: teks() }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { color: teks(), precision: 0 }, grid: { color: grid() } },
                },
            },
        });

        // Perbarui warna grafik ketika tema berubah.
        window.addEventListener('theme-changed', () => {
            instances.forEach(c => {
                const s = c.options.scales;
                if (s) Object.values(s).forEach(ax => {
                    if (ax.ticks) ax.ticks.color = teks();
                    if (ax.grid && ax.grid.color !== undefined) ax.grid.color = grid();
                });
                if (c.options.plugins?.legend?.labels) c.options.plugins.legend.labels.color = teks();
                c.update();
            });
        });
    })();
</script>
@endpush
