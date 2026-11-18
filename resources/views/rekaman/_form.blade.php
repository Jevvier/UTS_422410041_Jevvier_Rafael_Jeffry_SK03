@php
    $m = $rekaman ?? null;

    $pesertaTerpilih = old('peneliti_ids', $m ? $m->peneliti->pluck('id')->all() : []);
    $pjAwal = old('penanggung_jawab_id', $m ? optional($m->peneliti->firstWhere('pivot.peran', 'Penanggung Jawab'))->id : null);

    $analisisAwal = old('analisis', $m
        ? $m->analisis->map(fn ($a) => [
            'jenis_analisis' => $a->jenis_analisis,
            'frekuensi_dominan_hz' => $a->frekuensi_dominan_hz,
            'tingkat_keyakinan' => $a->tingkat_keyakinan,
            'hasil' => $a->hasil,
        ])->values()->all()
        : []);

    $errAnalisis = collect($errors->keys())->first(fn ($k) => str_starts_with($k, 'analisis.'));
    $spesiesDipilih = old('spesies_id', $m?->spesies_id ?? request('spesies_id'));
@endphp

{{-- 1. Data sesi --}}
<section class="card space-y-6 p-6">
    <div class="flex items-center gap-3">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">1</span>
        <h3 class="font-bold">Data sesi perekaman</h3>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <x-field label="Spesies" name="spesies_id" required>
            <select id="spesies_id" name="spesies_id" class="field">
                <option value="">— Pilih spesies —</option>
                @foreach ($spesies as $s)
                    <option value="{{ $s->id }}" @selected((string) $spesiesDipilih === (string) $s->id)>{{ $s->nama_umum }} ({{ $s->nama_ilmiah }})</option>
                @endforeach
            </select>
        </x-field>
        <x-field label="Lokasi" name="lokasi_id" required>
            <select id="lokasi_id" name="lokasi_id" class="field">
                <option value="">— Pilih lokasi —</option>
                @foreach ($lokasi as $l)
                    <option value="{{ $l->id }}" @selected((string) old('lokasi_id', $m?->lokasi_id) === (string) $l->id)>{{ $l->nama }} · {{ $l->provinsi }}</option>
                @endforeach
            </select>
        </x-field>
        <x-field label="Kualitas" name="kualitas" required>
            <select id="kualitas" name="kualitas" class="field">
                @foreach ($kualitasOptions as $k)
                    <option value="{{ $k }}" @selected(old('kualitas', $m?->kualitas ?? 'Baik') === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </x-field>

        <x-field label="Tanggal rekam" name="tanggal_rekam" required>
            <input id="tanggal_rekam" name="tanggal_rekam" type="date" max="{{ now()->toDateString() }}"
                   value="{{ old('tanggal_rekam', $m?->tanggal_rekam?->toDateString() ?? now()->toDateString()) }}" class="field">
        </x-field>
        <x-field label="Waktu mulai" name="waktu_mulai" hint="Waktu lokal (WIB).">
            <input id="waktu_mulai" name="waktu_mulai" type="time" value="{{ old('waktu_mulai', $m?->waktu_mulai ? substr($m->waktu_mulai, 0, 5) : '') }}" class="field">
        </x-field>
        <x-field label="Durasi (detik)" name="durasi_detik" required>
            <div x-data="durasi({{ (int) old('durasi_detik', $m?->durasi_detik ?? 300) }})" class="flex items-center gap-3">
                <input id="durasi_detik" name="durasi_detik" type="number" min="1" max="86400" x-model="detik" class="field">
                <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1 font-mono text-sm font-bold dark:bg-slate-800" x-text="format"></span>
            </div>
        </x-field>

        <x-field label="Sample rate (Hz)" name="sample_rate" required>
            <select id="sample_rate" name="sample_rate" class="field">
                @foreach ($sampleRates as $sr)
                    <option value="{{ $sr }}" @selected((int) old('sample_rate', $m?->sample_rate ?? 48000) === $sr)>{{ number_format($sr, 0, ',', '.') }}</option>
                @endforeach
            </select>
        </x-field>
        <x-field label="Format berkas" name="format" required>
            <select id="format" name="format" class="field">
                @foreach ($formatOptions as $f)
                    <option value="{{ $f }}" @selected(old('format', $m?->format ?? 'WAV') === $f)>{{ $f }}</option>
                @endforeach
            </select>
        </x-field>
        <x-field label="Kondisi cuaca" name="kondisi_cuaca">
            <input id="kondisi_cuaca" name="kondisi_cuaca" type="text" value="{{ old('kondisi_cuaca', $m?->kondisi_cuaca) }}" class="field" maxlength="60" placeholder="Cerah, hujan ringan…">
        </x-field>

        <div class="md:col-span-2 xl:col-span-3">
            <x-field label="Catatan lapangan" name="catatan">
                <textarea id="catatan" name="catatan" rows="3" class="field" maxlength="2000">{{ old('catatan', $m?->catatan) }}</textarea>
            </x-field>
        </div>
    </div>
</section>

{{-- 2. Pilihan jamak: peneliti + penanggung jawab --}}
<section class="card space-y-4 p-6">
    <div class="flex items-center gap-3">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">2</span>
        <div>
            <h3 class="font-bold">Tim peneliti</h3>
            <p class="text-xs text-slate-500">Pilih satu atau lebih peneliti. Tandai satu sebagai penanggung jawab (★).</p>
        </div>
    </div>
    @error('peneliti_ids')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
    <x-multi-select name="peneliti_ids"
                    :items="$peneliti->map(fn ($p) => ['id' => $p->id, 'label' => $p->nama, 'sub' => $p->institusi])"
                    :selected="$pesertaTerpilih"
                    pj-name="penanggung_jawab_id"
                    :pj-selected="$pjAwal"
                    judul="peneliti" placeholder="Cari nama atau institusi…" />
    @error('penanggung_jawab_id')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
</section>

{{-- 3. Detail analisis: one-to-many, baris dinamis --}}
<section class="card space-y-4 p-6" x-data="analisisRows(@js($analisisAwal))">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">3</span>
            <div>
                <h3 class="font-bold">Detail analisis</h3>
                <p class="text-xs text-slate-500">Hasil analisis awal per rekaman (maksimal 20 baris).</p>
            </div>
        </div>
        <button type="button" @click="tambah()" class="btn-ghost text-xs">+ Tambah analisis</button>
    </div>

    @if ($errAnalisis)
        <p class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 dark:bg-rose-950/50 dark:text-rose-300">{{ $errors->first($errAnalisis) }}</p>
    @endif

    <datalist id="jenis-analisis-list">
        <option value="Identifikasi spesies"></option>
        <option value="Deteksi vokalisasi"></option>
        <option value="Pengukuran frekuensi dominan"></option>
        <option value="Hitung panggilan"></option>
    </datalist>

    <div class="space-y-3">
        <template x-for="(row, i) in rows" :key="i">
            <div class="grid gap-3 rounded-2xl border border-slate-200 bg-slate-50/50 p-4 transition dark:border-slate-800 dark:bg-slate-950/40 md:grid-cols-12"
                 x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 -translate-y-1">
                <div class="md:col-span-4">
                    <label class="label text-xs">Jenis analisis *</label>
                    <input type="text" list="jenis-analisis-list" :name="`analisis[${i}][jenis_analisis]`" x-model="row.jenis_analisis" class="field" maxlength="100">
                </div>
                <div class="md:col-span-2">
                    <label class="label text-xs">Frek. dominan (Hz)</label>
                    <input type="number" step="0.01" min="0" :name="`analisis[${i}][frekuensi_dominan_hz]`" x-model="row.frekuensi_dominan_hz" class="field">
                </div>
                <div class="md:col-span-2">
                    <label class="label text-xs">Keyakinan (%)</label>
                    <div class="flex items-center gap-2">
                        <input type="range" min="0" max="100" x-model.number="row.tingkat_keyakinan" class="w-full accent-emerald-600">
                        <input type="number" min="0" max="100" step="0.01" :name="`analisis[${i}][tingkat_keyakinan]`" x-model="row.tingkat_keyakinan" class="field w-20 px-2">
                    </div>
                </div>
                <div class="md:col-span-3">
                    <label class="label text-xs">Hasil / catatan</label>
                    <input type="text" :name="`analisis[${i}][hasil]`" x-model="row.hasil" class="field" maxlength="1000">
                </div>
                <div class="flex items-end md:col-span-1">
                    <button type="button" @click="hapus(i)" class="w-full rounded-xl px-2 py-2.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/50" aria-label="Hapus baris analisis">Hapus</button>
                </div>
            </div>
        </template>
    </div>
</section>
