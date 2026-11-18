<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRekamanRequest;
use App\Http\Requests\UpdateRekamanRequest;
use App\Models\Lokasi;
use App\Models\Peneliti;
use App\Models\Rekaman;
use App\Models\Spesies;
use App\Services\RekamanService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RekamanController extends Controller
{
    public function __construct(private RekamanService $service)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'spesies_id', 'lokasi_id', 'kualitas', 'peneliti_id', 'dari', 'sampai']);

        return view('rekaman.index', [
            'items' => $this->service->search($filters),
            'ringkasan' => $this->service->ringkasan($filters),
            'filters' => $filters,
            'spesies' => Spesies::orderBy('nama_umum')->get(['id', 'nama_umum']),
            'lokasi' => Lokasi::orderBy('nama')->get(['id', 'nama']),
            'peneliti' => Peneliti::orderBy('nama')->get(['id', 'nama']),
            'kualitas' => Rekaman::KUALITAS,
        ]);
    }

    public function create(): View
    {
        return view('rekaman.create', $this->formData());
    }

    public function store(StoreRekamanRequest $request): RedirectResponse
    {
        $rekaman = $this->service->create($request->validated());

        return redirect()->route('rekaman.show', $rekaman)
            ->with('success', 'Rekaman berhasil disimpan beserta detail analisisnya.');
    }

    public function show(Rekaman $rekaman): View
    {
        $rekaman->load(['spesies.kategori', 'lokasi', 'peneliti', 'analisis']);

        return view('rekaman.show', compact('rekaman'));
    }

    public function edit(Rekaman $rekaman): View
    {
        $rekaman->load(['peneliti', 'analisis']);

        return view('rekaman.edit', array_merge(['rekaman' => $rekaman], $this->formData()));
    }

    public function update(UpdateRekamanRequest $request, Rekaman $rekaman): RedirectResponse
    {
        $rekaman = $this->service->update($rekaman, $request->validated());

        return redirect()->route('rekaman.show', $rekaman)->with('success', 'Rekaman berhasil diperbarui.');
    }

    public function destroy(Rekaman $rekaman): RedirectResponse
    {
        $this->service->delete($rekaman);

        return redirect()->route('rekaman.index')->with('success', 'Rekaman berhasil dihapus.');
    }

    private function formData(): array
    {
        return [
            'spesies' => Spesies::orderBy('nama_umum')->get(['id', 'nama_umum', 'nama_ilmiah']),
            'lokasi' => Lokasi::orderBy('nama')->get(['id', 'nama', 'provinsi']),
            'peneliti' => Peneliti::orderBy('nama')->get(['id', 'nama', 'institusi']),
            'formatOptions' => Rekaman::FORMAT,
            'kualitasOptions' => Rekaman::KUALITAS,
            'sampleRates' => [8000, 16000, 22050, 44100, 48000, 96000, 192000],
        ];
    }
}
