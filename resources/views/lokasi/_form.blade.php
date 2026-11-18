@php $m = $lokasi ?? null; @endphp
<div class="card grid gap-5 p-6 md:grid-cols-2">
    <x-field label="Nama lokasi" name="nama" required>
        <input id="nama" name="nama" type="text" value="{{ old('nama', $m?->nama) }}" class="field" maxlength="150">
    </x-field>
    <x-field label="Provinsi" name="provinsi" required>
        <input id="provinsi" name="provinsi" type="text" value="{{ old('provinsi', $m?->provinsi) }}" class="field" maxlength="100">
    </x-field>
    <x-field label="Latitude" name="latitude" hint="Contoh: -6.7000000">
        <input id="latitude" name="latitude" type="number" step="0.0000001" value="{{ old('latitude', $m?->latitude) }}" class="field">
    </x-field>
    <x-field label="Longitude" name="longitude" hint="Contoh: 106.5000000">
        <input id="longitude" name="longitude" type="number" step="0.0000001" value="{{ old('longitude', $m?->longitude) }}" class="field">
    </x-field>
    <div class="md:col-span-2">
        <x-field label="Deskripsi" name="deskripsi">
            <textarea id="deskripsi" name="deskripsi" rows="3" class="field">{{ old('deskripsi', $m?->deskripsi) }}</textarea>
        </x-field>
    </div>
</div>
