# Ringkasan alur kerja (lihat README untuk detail)
1. Route → Resource Controller
2. Controller → Form Request (validasi)
3. Controller → Service (logika bisnis + DB::transaction)
4. Service → Eloquent Model (relasi) → MySQL
5. Blade: layout + partial + component → respons
