@php $m = $peneliti ?? null; @endphp
<div class="card grid gap-5 p-6 md:grid-cols-2">
    <x-field label="Nama lengkap" name="nama" required>
        <input id="nama" name="nama" type="text" value="{{ old('nama', $m?->nama) }}" class="field" maxlength="150">
    </x-field>
    <x-field label="Email" name="email" required>
        <input id="email" name="email" type="email" value="{{ old('email', $m?->email) }}" class="field" maxlength="150">
    </x-field>
    <x-field label="Institusi" name="institusi">
        <input id="institusi" name="institusi" type="text" value="{{ old('institusi', $m?->institusi) }}" class="field" maxlength="150">
    </x-field>
    <x-field label="Keahlian" name="keahlian" hint="Mis. Analisis spektrogram, Bioakustik burung.">
        <input id="keahlian" name="keahlian" type="text" value="{{ old('keahlian', $m?->keahlian) }}" class="field" maxlength="150">
    </x-field>
</div>
