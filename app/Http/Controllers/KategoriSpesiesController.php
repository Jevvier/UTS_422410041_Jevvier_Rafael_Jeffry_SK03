<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\KategoriSpesies;
use App\Services\KategoriSpesiesService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriSpesiesController extends Controller
{
    public function __construct(private KategoriSpesiesService $service)
    {
    }

    public function index(Request $request): View
    {
        return view('kategori.index', [
            'items' => $this->service->search($request->string('q')->toString()),
            'q' => $request->string('q')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('kategori.create');
    }

    public function store(StoreKategoriRequest $request): RedirectResponse
    {
        $kategori = $this->service->create($request->validated());

        return redirect()->route('kategori.index')
            ->with('success', "Kategori \"{$kategori->nama}\" berhasil ditambahkan.");
    }

    public function edit(KategoriSpesies $kategori): View
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, KategoriSpesies $kategori): RedirectResponse
    {
        $kategori = $this->service->update($kategori, $request->validated());

        return redirect()->route('kategori.index')
            ->with('success', "Kategori \"{$kategori->nama}\" berhasil diperbarui.");
    }

    public function destroy(KategoriSpesies $kategori): RedirectResponse
    {
        try {
            $this->service->delete($kategori);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
