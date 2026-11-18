<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paginasi memakai gaya Tailwind yang sudah tersedia di layout.
        Paginator::useTailwind();

        // Nama bulan/hari dalam Bahasa Indonesia untuk translatedFormat().
        Carbon::setLocale(config('app.locale', 'id'));
    }
}
