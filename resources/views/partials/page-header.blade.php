<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h2 class="text-2xl font-extrabold tracking-tight">{{ $title }}</h2>
        @isset($subtitle)<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>@endisset
    </div>
    <div class="flex flex-wrap gap-2">
        {!! $actions ?? "" !!}
    </div>
</div>
