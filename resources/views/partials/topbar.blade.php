<header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/80 px-4 backdrop-blur-md sm:px-6 dark:border-slate-800 dark:bg-slate-900/80">
    <button type="button" @click="sidebar = !sidebar"
            class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden dark:text-slate-300 dark:hover:bg-slate-800"
            aria-label="Buka menu">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z"/></svg>
    </button>

    <div class="min-w-0">
        <h1 class="truncate text-base font-bold">@yield('title', 'Beranda')</h1>
        <p class="hidden truncate text-xs text-slate-500 sm:block">@yield('subtitle', 'Administrasi data laboratorium')</p>
    </div>

    <div class="ml-auto flex items-center gap-2">
        <button type="button" @click="toggleTheme()"
                class="rounded-xl border border-slate-200 p-2 text-slate-600 transition hover:bg-slate-100 active:scale-90 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                :title="dark ? 'Mode terang' : 'Mode gelap'" aria-label="Ganti tema">
            <svg x-show="!dark" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.39 5.39 0 01-4.4 2.26 5.4 5.4 0 01-3.14-9.8c-.44-.06-.9-.1-1.36-.1z"/></svg>
            <svg x-show="dark" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 100 10 5 5 0 000-10zM2 13h2v-2H2v2zm18 0h2v-2h-2v2zM11 2v2h2V2h-2zm0 18v2h2v-2h-2zM5.6 4.2L4.2 5.6 5.6 7 7 5.6 5.6 4.2zm12.7 12.7l-1.4 1.4 1.4 1.4 1.4-1.4-1.4-1.4zM18.4 4.2L17 5.6l1.4 1.4 1.4-1.4-1.4-1.4zM7 17l-1.4 1.4 1.4 1.4L8.4 18.4 7 17z"/></svg>
        </button>
        <a href="{{ route('rekaman.create') }}" class="btn-primary hidden sm:inline-flex">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Rekaman baru
        </a>
    </div>
</header>
