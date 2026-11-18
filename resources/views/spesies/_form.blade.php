@php
    $m = $spesies ?? null;
    $habitatTerpilih = old('habitat_ids', $m ? $m->habitat->pluck('id')->all() : []);
@endphp
<div class="card space-y-6 p-6">
    <div class="grid gap-5 md:grid-cols-2">
        <x-field label="Nama umum" name="nama_umum" required>
            <input id="nama_umum" name="nama_umum" type="text" value="{{ old('nama_umum', $m?->nama_umum) }}" class="field" maxlength="150">
        </x-field>
        <x-field label="Nama ilmiah" name="nama_ilmiah" required hint="Binomial, mis. Hylobates moloch.">
            <input id="nama_ilmiah" name="nama_ilmiah" type="text" value="{{ old('nama_ilmiah', $m?->nama_ilmiah) }}" class="field italic" maxlength="150">
        </x-field>
        <x-field label="Kategori" name="kategori_spesies_id" required>
            <select id="kategori_spesies_id" name="kategori_spesies_id" class="field">
                <option value="">— Pilih kategori —</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected((string) old('kategori_spesies_id', $m?->kategori_spesies_id) === (string) $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </x-field>
        <x-field label="Status konservasi (IUCN)" name="status_konservasi" required>
            <select id="status_konservasi" name="status_konservasi" class="field">
                @foreach ($statusKonservasi as $kode => $label)
                    <option value="{{ $kode }}" @selected(old('status_konservasi', $m?->status_konservasi ?? 'LC') === $kode)>{{ $kode }} — {{ $label }}</option>
                @endforeach
            </select>
        </x-field>
    </div>

    <x-field label="Habitat" name="habitat_ids" required hint="Pilih satu atau lebih habitat tempat spesies ditemukan.">
        <div class="mt-1.5">
            <x-multi-select name="habitat_ids" :items="$habitat->map(fn ($h) => ['id' => $h->id, 'label' => $h->nama])"
                            :selected="$habitatTerpilih" judul="habitat" placeholder="Cari habitat…" />
        </div>
    </x-field>

    <x-field label="Deskripsi" name="deskripsi">
        <textarea id="deskripsi" name="deskripsi" rows="4" class="field" maxlength="2000">{{ old('deskripsi', $m?->deskripsi) }}</textarea>
    </x-field>
</div>
