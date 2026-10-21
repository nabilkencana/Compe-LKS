# Logistics REST API — Panduan Kode

Kode dirapikan agar konsisten dan mudah dipelajari: satu langkah per blok, nama variabel jelas, validasi di awal, lalu proses dan response JSON.

## Alur bisnis shipment

1. **Customer membuat shipment** — `ShipmentController::store()` memvalidasi input, memastikan layanan aktif, membuat nomor resi, dan menyimpan status awal `pending`.
2. **Staff menentukan biaya** — `StaffShipmentController::confirm()` mengisi `shipping_fee` dan mengubah status menjadi `confirmed`.
3. **Customer mengirim pembayaran** — `PaymentController::store()` membuat pembayaran berstatus `pending`.
4. **Admin memverifikasi pembayaran** — `PaymentController::verify()` mengubah status pembayaran menjadi `completed` atau `rejected`.
5. **Admin memilih kurir** — `AdminShipmentController::assignCourier()` memastikan pembayaran sudah selesai sebelum status menjadi `assigned`.
6. **Kurir memperbarui perjalanan** — `CourierController::updateStatus()` memeriksa transisi status dan mencatat riwayatnya.
7. **Kurir mengirim bukti** — `CourierController::proof()` menyimpan bukti dan nama penerima.
8. **Customer/staff melihat resi** — `TrackingController` mengambil status terkini beserta riwayatnya.

## Endpoint utama

- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout`
- `GET /api/v1/shipping-services`
- `GET|POST /api/v1/shipments`
- `GET /api/v1/me/shipments`
- `GET /api/v1/shipments/{shipment}`
- `PATCH /api/v1/shipments/{shipment}/cancel`
- `GET /api/v1/shipments/{shipment}/tracking`
- `GET /api/v1/tracking/{trackingNumber}`
- `PATCH /api/v1/staff/shipments/{shipment}/confirm`
- `POST /api/v1/shipments/{shipment}/payments`
- `PATCH /api/v1/admin/payments/{payment}/verify`
- `PATCH /api/v1/admin/shipments/{shipment}/assign-courier`
- `GET /api/v1/courier/shipments`
- `PATCH /api/v1/courier/shipments/{shipment}/status`
- `POST /api/v1/courier/shipments/{shipment}/proof`
- `POST|PATCH|DELETE /api/v1/admin/shipping-services`

## Urutan belajar yang disarankan

1. `ShipmentController::store()` — validasi, relasi, transaksi, dan nomor resi.
2. `TrackingController::show()` — mengambil data relasi dan membentuk response.
3. `StaffShipmentController::confirm()` — mengubah status serta mencatat riwayat.
4. `PaymentController::store()` dan `verify()` — alur pembayaran.
5. `AdminShipmentController::assignCourier()` — validasi peran dan prasyarat bisnis.
6. `CourierController::updateStatus()` — transisi status.
7. `ShipmentPolicy` dan `checkRole` — pembatasan akses.

## Catatan penting

- Migration dalam proyek ini ditujukan untuk database baru. Mengedit file migration tidak mengubah tabel yang sudah dibuat sebelumnya. Backup database dan buat migration alter-table jika database lama ingin dipertahankan.
- File upload disimpan pada disk `local` di `storage/app/private`, bukan folder publik.
- Tracking memerlukan login dan akses sesuai Policy.
- Riwayat perubahan status disimpan di `shipment_status_histories`.
- Sebelum dipakai di production, uji seluruh endpoint dan aturan bisnis dengan database development.

## Menjalankan dan memeriksa proyek

```bash
php artisan route:list --path=api/v1
php artisan migrate
php artisan test
```
