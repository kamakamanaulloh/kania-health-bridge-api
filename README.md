# Kania Health Bridge API

**Kania Health Bridge API** adalah project open-source berbasis **Laravel 10** yang dibuat sebagai API bridge ringan untuk membantu integrasi SIMRS/RME/HIS dengan layanan kesehatan Indonesia seperti **BPJS VClaim, Antrol, iCare, Apotek Online, dan SATUSEHAT**.

Project ini dibuat tanpa login dan tanpa portal dashboard, sehingga fokus utamanya adalah sebagai **middleware API** yang dapat dipasang di server lokal maupun server online untuk menjadi penghubung antara SIMRS dan layanan eksternal.

---

## Tujuan Project

Project ini dibuat untuk membantu developer SIMRS agar tidak perlu membangun proses bridging dari nol.

Dengan Kania Health Bridge API, SIMRS cukup mengirim request ke endpoint bridge, lalu sistem akan membantu proses:

* Validasi request
* Mapping payload sederhana dari SIMRS
* Pengiriman request ke BPJS / SATUSEHAT
* Token cache SATUSEHAT
* Signature BPJS
* Logging request dan response
* Standarisasi response API

---

## Cara Kerja

```text
SIMRS / RME / HIS
      ↓
Kania Health Bridge API
      ↓
BPJS / Antrol / iCare / Apotek Online / SATUSEHAT
```

---

## Fitur Utama

* API bridge tanpa login
* Middleware keamanan menggunakan `X-BRIDGE-KEY`
* Health check endpoint
* BPJS VClaim bridge
* Antrol bridge
* SATUSEHAT bridge
* RAW mode untuk payload asli
* MAPPER mode untuk payload sederhana dari SIMRS
* Standard response API
* Request dan response log
* Token cache SATUSEHAT
* Helper signature BPJS
* Helper decrypt response BPJS
* Mapper Encounter SATUSEHAT
* Mapper Condition SATUSEHAT
* Mapper Observation SATUSEHAT
* Mapper SEP BPJS
* Mapper Antrol
* Dokumentasi endpoint
* Contoh integrasi Native PHP
* Contoh integrasi CodeIgniter 3
* Contoh integrasi Laravel 10
* Postman Collection

---

## Pembeda Project Ini

Project ini tidak hanya menyediakan endpoint API biasa, tetapi juga menyediakan konsep **bridge adapter** untuk memudahkan SIMRS lain melakukan integrasi.

Pembeda utamanya adalah:

1. **RAW Mode**
   SIMRS dapat mengirim payload sesuai format asli BPJS atau SATUSEHAT.

2. **MAPPER Mode**
   SIMRS dapat mengirim payload sederhana, lalu bridge API akan membantu mengubahnya menjadi format yang dibutuhkan layanan tujuan.

3. **Standard Response**
   Semua response dibuat seragam agar lebih mudah dibaca oleh SIMRS.

4. **Request-Response Log**
   Setiap request dan response dapat dicatat untuk memudahkan debugging.

5. **Reusable untuk Banyak SIMRS**
   Project ini dapat digunakan oleh SIMRS berbasis Laravel, CodeIgniter, Native PHP, atau framework lain.

---

## Tech Stack

* Laravel 10
* PHP 8.1
* MySQL / MariaDB
* Guzzle HTTP Client
* Laravel Migration
* Laravel Middleware
* Laravel Service Layer

---

## Instalasi

```bash
git clone https://github.com/USERNAME/kania-health-bridge-api.git
cd kania-health-bridge-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## Konfigurasi ENV

Silakan isi credential sesuai layanan yang digunakan.

```env
APP_NAME="Kania Health Bridge API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

BRIDGE_ENV=sandbox
BRIDGE_KEY=your-secret-bridge-key

BPJS_CONS_ID=
BPJS_SECRET_KEY=
BPJS_USER_KEY=
BPJS_BASE_URL=

ANTROL_BASE_URL=
ANTROL_CONS_ID=
ANTROL_SECRET_KEY=
ANTROL_USER_KEY=

SATUSEHAT_CLIENT_ID=
SATUSEHAT_CLIENT_SECRET=
SATUSEHAT_AUTH_URL=
SATUSEHAT_BASE_URL=
SATUSEHAT_ORGANIZATION_ID=
```

---

## Header API

Setiap request ke endpoint bridge wajib menyertakan header berikut:

```http
X-BRIDGE-KEY: your-secret-bridge-key
Accept: application/json
Content-Type: application/json
```

---

## Health Check

```http
GET /api/v1/health
```

Contoh response:

```json
{
  "success": true,
  "message": "Kania Health Bridge API is running",
  "version": "1.0.0",
  "environment": "sandbox"
}
```

---

## Endpoint Utama

### BPJS

```http
GET  /api/v1/bpjs/peserta/nik/{nik}
GET  /api/v1/bpjs/peserta/noka/{no_kartu}
GET  /api/v1/bpjs/referensi/poli/{keyword}
GET  /api/v1/bpjs/referensi/diagnosa/{keyword}
POST /api/v1/bpjs/sep
```

### Antrol

```http
POST /api/v1/antrol/antrean
POST /api/v1/antrol/taskid
POST /api/v1/antrol/batal
```

### SATUSEHAT

```http
GET  /api/v1/satusehat/token
POST /api/v1/satusehat/encounter
POST /api/v1/satusehat/condition
POST /api/v1/satusehat/observation
```

### RAW Mode

```http
POST /api/v1/raw/bpjs/{profile}/{path}
POST /api/v1/raw/satusehat/{resource}
```

---

## Contoh Response Sukses

```json
{
  "success": true,
  "service": "satusehat",
  "module": "encounter",
  "message": "Encounter berhasil dikirim",
  "data": {
    "resource_id": "example-resource-id"
  },
  "meta": {
    "request_id": "REQ-20260611-000001",
    "duration_ms": 845
  }
}
```

---

## Contoh Response Error

```json
{
  "success": false,
  "service": "satusehat",
  "module": "encounter",
  "message": "Gagal mengirim Encounter",
  "error": {
    "code": "VALIDATION_ERROR",
    "detail": "patient_ihs wajib diisi"
  },
  "meta": {
    "request_id": "REQ-20260611-000002",
    "duration_ms": 120
  }
}
```

---

## Catatan Penting

Project ini hanya menyediakan engine/API bridge. Untuk dapat terhubung ke layanan production seperti BPJS dan SATUSEHAT, pengguna tetap membutuhkan credential resmi dari masing-masing layanan atau fasyankes terkait.

Credential seperti `cons_id`, `secret_key`, `user_key`, `client_id`, dan `client_secret` tidak disertakan dalam repository.

---

## Roadmap

### Version 1.0.0

* Core bridge API
* Bridge key middleware
* Standard response helper
* API log
* SATUSEHAT token cache
* BPJS request signer
* Health check endpoint
* RAW mode basic
* MAPPER mode basic

### Version 1.1.0

* BPJS peserta
* BPJS referensi
* BPJS SEP
* Antrol antrean
* SATUSEHAT Encounter
* SATUSEHAT Condition

### Version 1.2.0

* SATUSEHAT Observation
* SATUSEHAT Procedure
* SATUSEHAT MedicationRequest
* SATUSEHAT MedicationDispense
* iCare bridge
* Apotek Online bridge

### Version 2.0.0

* Retry failed request
* Queue support
* Webhook callback
* Multi credential profile
* Docker support
* More SIMRS adapter examples

---

## Target Pengguna

Project ini cocok digunakan oleh:

* Developer SIMRS
* Vendor RME
* Rumah sakit
* Klinik
* Puskesmas
* Komunitas IT kesehatan
* Mahasiswa atau programmer yang ingin belajar integrasi SIMRS

---

## License

MIT License

Project ini dirilis secara open-source untuk membantu ekosistem SIMRS dan integrasi kesehatan digital di Indonesia.

---

## Kontribusi

Kontribusi sangat terbuka untuk developer SIMRS, vendor RME, rumah sakit, klinik, puskesmas, dan komunitas IT kesehatan.

Silakan fork repository ini, buat branch baru, lalu ajukan pull request.
