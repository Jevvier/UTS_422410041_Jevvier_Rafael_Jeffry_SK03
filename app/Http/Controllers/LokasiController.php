<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LokasiController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $provinsi = (string) $request->query('provinsi', '');

        $items = Lokasi::query()
            ->withCount('rekaman')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('nama', 'like', '%'.addcslashes($q, '%_\\').'%')
                ->orWhere('provinsi', 'like', '%'.addcslashes($q, '%_\\').'%')))
            ->when($provinsi !== '', fn ($query) => $query->where('provinsi', $provinsi))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('lokasi.index', [
            'items' => $items,
            'filters' => ['q' => $q, 'provinsi' => $provinsi],
            'daftarProvinsi' => Lokasi::query()->distinct()->orderBy('provinsi')->pluck('provinsi'),
        ]);
    }

    public function create(): View
    {
        return view('lokasi.create');
    }

    public function store(StoreLokasiRequest $request): RedirectResponse
    {
        $lokasi = Lokasi::create($request->validated());

        return redirect()->route('lokasi.index')->with('success', "Lokasi \"{$lokasi->nama}\" berhasil ditambahkan.");
    }

    public function edit(Lokasi $lokasi): View
    {
        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi): RedirectResponse
    {
        $lokasi->update($request->validated());

        return redirect()->route('lokasi.index')->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi): RedirectResponse
    {
        if ($lokasi->rekaman()->exists()) {
            return back()->with('error', "Lokasi \"{$lokasi->nama}\" memiliki rekaman dan tidak dapat dihapus.");
        }

        $lokasi->delete();

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
