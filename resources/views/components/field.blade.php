@props(['label', 'name', 'required' => false, 'hint' => null])
<div {{ $attributes->class(['space-y-0']) }}>
    <label for="{{ $name }}" class="label">
        {{ $label }}@if ($required)<span class="text-rose-500"> *</span>@endif
    </label>
    {{ $slot }}
    @error($name)
        <p class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600 dark:text-rose-400">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            {{ $message }}
        </p>
    @enderror
    @if ($hint)<p class="mt-1.5 text-xs text-slate-500">{{ $hint }}</p>@endif
</div>
