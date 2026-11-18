@php $m = $kategori ?? null; @endphp
<div class="card space-y-5 p-6">
    <x-field label="Nama kategori" name="nama" required>
        <input id="nama" name="nama" type="text" value="{{ old('nama', $m?->nama) }}" class="field mt-0" maxlength="100" autofocus>
    </x-field>
    <x-field label="Deskripsi" name="deskripsi" hint="Opsional. Jelaskan jenis vokalisasi yang tercakup.">
        <textarea id="deskripsi" name="deskripsi" rows="4" class="field" maxlength="1000">{{ old('deskripsi', $m?->deskripsi) }}</textarea>
    </x-field>
</div>
