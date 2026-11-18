<?php

namespace App\Services;

use App\Models\KategoriSpesies;
use App\Models\Lokasi;
use App\Models\Peneliti;
use App\Models\Rekaman;
use App\Models\Spesies;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function statistik(): array
    {
        return [
            'spesies' => Spesies::count(),
            'rekaman' => Rekaman::count(),
            'lokasi' => Lokasi::count(),
            'peneliti' => Peneliti::count(),
            'total_durasi' => (int) Rekaman::sum('durasi_detik'),
            'terancam' => Spesies::whereIn('status_konservasi', ['VU', 'EN', 'CR'])->count(),
        ];
    }

    /** Data grafik: jumlah rekaman per bulan (6 bulan terakhir, MySQL). */
    public function rekamanPerBulan(int $bulan = 6): array
    {
        $rows = Rekaman::query()
            ->selectRaw("DATE_FORMAT(tanggal_rekam, '%Y-%m') AS periode, COUNT(*) AS jumlah")
            ->where('tanggal_rekam', '>=', now()->subMonths($bulan - 1)->startOfMonth())
            ->groupBy('periode')
            ->orderBy('periode')
            ->pluck('jumlah', 'periode');

        $labels = [];
        $data = [];
        for ($i = $bulan - 1; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $labels[] = now()->subMonths($i)->translatedFormat('M Y');
            $data[] = (int) ($rows[$key] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /** Jumlah spesies per kategori (grafik donat). */
    public function spesiesPerKategori(): array
    {
        $rows = KategoriSpesies::query()
            ->withCount('spesies')
            ->orderByDesc('spesies_count')
            ->get(['id', 'nama', 'spesies_count']);

        return [
            'labels' => $rows->pluck('nama')->all(),
            'data' => $rows->pluck('spesies_count')->map(fn ($n) => (int) $n)->all(),
        ];
    }

    public function distribusiKualitas(): array
    {
        $rows = Rekaman::query()
            ->select('kualitas', DB::raw('COUNT(*) AS jumlah'))
            ->groupBy('kualitas')
            ->pluck('jumlah', 'kualitas');

        return [
            'labels' => Rekaman::KUALITAS,
            'data' => array_map(fn ($k) => (int) ($rows[$k] ?? 0), Rekaman::KUALITAS),
        ];
    }

    public function rekamanTerbaru(int $limit = 5)
    {
        return Rekaman::with(['spesies:id,nama_umum', 'lokasi:id,nama'])
            ->orderByDesc('tanggal_rekam')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
