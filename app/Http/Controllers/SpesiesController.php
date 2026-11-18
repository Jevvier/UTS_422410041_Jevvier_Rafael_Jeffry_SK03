<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpesiesRequest;
use App\Http\Requests\UpdateSpesiesRequest;
use App\Models\Habitat;
use App\Models\KategoriSpesies;
use App\Models\Spesies;
use App\Services\SpesiesService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpesiesController extends Controller
{
    public function __construct(private SpesiesService $service)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'kategori_spesies_id', 'status_konservasi']);

        return view('spesies.index', [
            'items' => $this->service->search($filters),
            'filters' => $filters,
            'kategori' => KategoriSpesies::orderBy('nama')->get(['id', 'nama']),
            'statusKonservasi' => Spesies::STATUS_KONSERVASI,
        ]);
    }

    public function create(): View
    {
        return view('spesies.create', $this->formData());
    }

    public function store(StoreSpesiesRequest $request): RedirectResponse
    {
        $spesies = $this->service->create($request->validated());

        return redirect()->route('spesies.show', $spesies)
            ->with('success', "Spesies \"{$spesies->nama_umum}\" berhasil ditambahkan.");
    }

    public function show(Spesies $spesies): View
    {
        $spesies->load(['kategori', 'habitat'])
            ->loadCount('rekaman');

        return view('spesies.show', [
            'spesies' => $spesies,
            'rekaman' => $spesies->rekaman()->with('lokasi:id,nama')->latest('tanggal_rekam')->limit(10)->get(),
        ]);
    }

    public function edit(Spesies $spesies): View
    {
        return view('spesies.edit', array_merge(['spesies' => $spesies->load('habitat')], $this->formData()));
    }

    public function update(UpdateSpesiesRequest $request, Spesies $spesies): RedirectResponse
    {
        $spesies = $this->service->update($spesies, $request->validated());

        return redirect()->route('spesies.show', $spesies)
            ->with('success', 'Data spesies berhasil diperbarui.');
    }

    public function destroy(Spesies $spesies): RedirectResponse
    {
        try {
            $this->service->delete($spesies);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('spesies.index')->with('success', 'Spesies berhasil dihapus.');
    }

    private function formData(): array
    {
        return [
            'kategori' => KategoriSpesies::orderBy('nama')->get(['id', 'nama']),
            'habitat' => Habitat::orderBy('nama')->get(['id', 'nama']),
            'statusKonservasi' => Spesies::STATUS_KONSERVASI,
        ];
    }
}
