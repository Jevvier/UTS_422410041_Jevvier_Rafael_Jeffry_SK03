# SK03 — Aplikasi Administrasi Laboratorium Bioakustik

Laravel 12 · MySQL 8 · Blade (layout, partial, component) · Alpine.js · Tailwind (CDN) · Chart.js

## Fitur
- **Data utama — Rekaman**: CRUD sesi perekaman, dengan pencarian, filter (spesies, lokasi, peneliti, kualitas, rentang tanggal), dan ringkasan hasil.
- **Kategori**: CRUD kategori taksonomi spesies (dengan aturan: kategori yang masih dipakai tidak dapat dihapus).
- **Detail**: Analisis rekaman (one-to-many), dikelola dinamis di form rekaman (tambah/hapus baris, slider keyakinan).
- **Pilihan jamak**: Peneliti pada rekaman (multi-select + penanggung jawab disimpan di pivot `peran`) dan habitat pada spesies (multi-select).
- **Spesies**: CRUD dengan filter kategori & status konservasi IUCN.
- **Lokasi & Peneliti**: CRUD master.
- **Dashboard**: statistik animasi, grafik rekaman per bulan, komposisi kategori, dan distribusi kualitas.
- Validasi lewat Form Request (pesan Bahasa Indonesia), flash message, konfirmasi hapus, mode gelap.

## Instalasi
```bash
composer install
cp .env.example .env
php artisan key:generate
# Atur DB_* di .env ke database MySQL (buat database sk03_bioakustik terlebih dulu)
php artisan migrate --seed
php artisan serve
```
Buka http://localhost:8000.

Tes (memakai SQLite in-memory, tanpa MySQL): `php artisan test`

## Struktur utama
```
app/Http/Controllers   Resource controller (tipis, mendelegasikan ke service)
app/Http/Requests      Form Request: validasi aturan & pesan
app/Services           Logika bisnis & transaksi (RekamanService, SpesiesService, ...)
app/Models             Eloquent model + relasi (hasMany, belongsTo, belongsToMany, ...)
database/migrations    Skema tabel, foreign key, index
database/seeders       Data contoh (master dahulu, lalu data transaksi)
resources/views        layouts/, partials/, components/, dan halaman per modul
docs/                  Class diagram & ERD (PNG + PDF) beserta sumber .dot
```

## Alur kerja (untuk penjelasan)
**Contoh: membuat rekaman baru**
1. Pengguna membuka `GET /rekaman/create`; `RekamanController@create` mengambil daftar spesies, lokasi, dan peneliti.
2. Pengguna mengisi data sesi, memilih peneliti (multi-select), menentukan penanggung jawab, dan menambah baris analisis.
3. `POST /rekaman` masuk ke `StoreRekamanRequest`: aturan seperti `before_or_equal:today` dan `in_array:peneliti_ids.*` diperiksa di sini.
4. Controller memanggil `RekamanService::create()`, yang membungkus semua penulisan dalam satu `DB::transaction`: insert rekaman, sinkronisasi pivot `rekaman_peneliti` dengan peran, dan insert `analisis_rekaman`. Jika salah satu gagal, seluruh perubahan dibatalkan.
5. Pengguna diarahkan ke halaman detail dengan pesan sukses.

**Pencarian & filter**: parameter query (`q`, `spesies_id`, `dari`, dst.) dikumpulkan controller, lalu `RekamanService::search()` membangun query dengan `when()` dan `whereHas()`. Input pencarian mengirim form otomatis setelah jeda 450 ms (Alpine `liveSearch`).

**Aturan bisnis**: penghapusan spesies, lokasi, dan kategori yang masih dipakai ditolak di service/controller dengan pesan yang jelas, sebelum database menolak lewat foreign key `RESTRICT`.

## Catatan
- Data status konservasi di seeder adalah contoh; verifikasi ulang dengan IUCN sebelum digunakan.
- Autentikasi belum ditambahkan (sesuai cakupan tugas); dapat ditambahkan dengan Laravel Breeze/Fortify.
- Berkas audio tidak disimpan; pemutar pada halaman detail adalah simulasi visual.
