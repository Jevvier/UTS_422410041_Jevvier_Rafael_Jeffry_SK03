<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard)
    {
    }

    public function index(): View
    {
        return view('dashboard.index', [
            'statistik' => $this->dashboard->statistik(),
            'perBulan' => $this->dashboard->rekamanPerBulan(),
            'perKategori' => $this->dashboard->spesiesPerKategori(),
            'kualitas' => $this->dashboard->distribusiKualitas(),
            'terbaru' => $this->dashboard->rekamanTerbaru(),
        ]);
    }
}
