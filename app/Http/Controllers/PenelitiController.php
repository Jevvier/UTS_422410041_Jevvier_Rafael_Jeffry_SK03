<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenelitiRequest;
use App\Http\Requests\UpdatePenelitiRequest;
use App\Models\Peneliti;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenelitiController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $items = Peneliti::query()
            ->withCount('rekaman')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('nama', 'like', '%'.addcslashes($q, '%_\\').'%')
                ->orWhere('email', 'like', '%'.addcslashes($q, '%_\\').'%')
                ->orWhere('institusi', 'like', '%'.addcslashes($q, '%_\\').'%')))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('peneliti.index', ['items' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        return view('peneliti.create');
    }

    public function store(StorePenelitiRequest $request): RedirectResponse
    {
        $peneliti = Peneliti::create($request->validated());

        return redirect()->route('peneliti.index')->with('success', "Peneliti \"{$peneliti->nama}\" berhasil ditambahkan.");
    }

    public function edit(Peneliti $peneliti): View
    {
        return view('peneliti.edit', compact('peneliti'));
    }

    public function update(UpdatePenelitiRequest $request, Peneliti $peneliti): RedirectResponse
    {
        $peneliti->update($request->validated());

        return redirect()->route('peneliti.index')->with('success', 'Data peneliti berhasil diperbarui.');
    }

    public function destroy(Peneliti $peneliti): RedirectResponse
    {
        // Relasi pivot rekaman_peneliti ikut terhapus (cascadeOnDelete).
        $peneliti->delete();

        return redirect()->route('peneliti.index')->with('success', 'Peneliti berhasil dihapus.');
    }
}
