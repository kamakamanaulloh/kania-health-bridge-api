# Kania Health Bridge API

API bridge ringan dan open-source untuk membantu SIMRS/RME/HIS berkomunikasi dengan **BPJS VClaim**, **EMR/RME BPJS**, **Antrol**, **iCare**, **Apotek Online**, dan **SATUSEHAT**.

Project ini **tanpa login dan tanpa portal**. Fokusnya adalah menjadi middleware/API gateway yang bisa di-clone dari GitHub, dipasang di server RS/klinik/vendor, lalu dipanggil dari SIMRS.

## Pembeda Utama

- **RAW Mode**: SIMRS mengirim payload asli sesuai API tujuan.
- **MAPPER Mode**: SIMRS mengirim payload sederhana, bridge yang mengubah ke format BPJS/SATUSEHAT.
- **Standard Response**: semua response sukses/error dibuat seragam.
- **Request-Response Log**: setiap hit ke BPJS/SATUSEHAT tersimpan di database.
- **Token Cache SATUSEHAT**: token disimpan agar tidak request token terus-menerus.
- **BPJS Response Decrypt Helper**: helper decrypt response BPJS + LZString tersedia.
- **EMR/RME BPJS Bridge**: endpoint raw dan mapper untuk kunjungan, diagnosa, tindakan, resep, lab, radiologi, dan resume medis.
- **Contoh Integrasi**: tersedia contoh PHP native, CodeIgniter, Laravel, dan Postman.

## Stack

- Laravel 10
- PHP 8.1+
- MySQL/MariaDB
- Guzzle HTTP

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

Tes:

```bash
curl http://localhost:8000/api/v1/health
```

## Konfigurasi

Edit `.env`:

```env
BRIDGE_KEY=isi-key-rahasia
BRIDGE_ENV=sandbox

BPJS_CONS_ID=...
BPJS_SECRET_KEY=...
BPJS_USER_KEY=...
BPJS_BASE_URL=...

ANTROL_CONS_ID=...
ANTROL_SECRET_KEY=...
ANTROL_USER_KEY=...
ANTROL_BASE_URL=...

BPJS_EMR_BASE_URL=...
BPJS_EMR_ENDPOINT_KUNJUNGAN=emr/kunjungan
BPJS_EMR_ENDPOINT_DIAGNOSA=emr/diagnosa
BPJS_EMR_ENDPOINT_TINDAKAN=emr/tindakan
BPJS_EMR_ENDPOINT_RESEP=emr/resep
BPJS_EMR_ENDPOINT_LABORATORIUM=emr/laboratorium
BPJS_EMR_ENDPOINT_RADIOLOGI=emr/radiologi
BPJS_EMR_ENDPOINT_RESUME=emr/resume

SATUSEHAT_CLIENT_ID=...
SATUSEHAT_CLIENT_SECRET=...
SATUSEHAT_AUTH_URL=...
SATUSEHAT_BASE_URL=...
SATUSEHAT_ORGANIZATION_ID=...
```

Setiap request selain health wajib mengirim header:

```http
X-BRIDGE-KEY: isi-key-rahasia
Accept: application/json
Content-Type: application/json
```

## Endpoint Utama

### Health

```http
GET /api/v1/health
```

### BPJS VClaim

```http
GET  /api/v1/bpjs/peserta/nik/{nik}
GET  /api/v1/bpjs/peserta/noka/{no_kartu}
GET  /api/v1/bpjs/referensi/poli/{keyword}
GET  /api/v1/bpjs/referensi/diagnosa/{keyword}
POST /api/v1/bpjs/sep
```


### BPJS EMR / RME

```http
GET  /api/v1/bpjs/emr/status
POST /api/v1/bpjs/emr/kunjungan
POST /api/v1/bpjs/emr/diagnosa
POST /api/v1/bpjs/emr/tindakan
POST /api/v1/bpjs/emr/resep
POST /api/v1/bpjs/emr/laboratorium
POST /api/v1/bpjs/emr/radiologi
POST /api/v1/bpjs/emr/resume
POST /api/v1/bpjs/emr/raw/{path}
```

Contoh EMR Kunjungan Mapper:

```json
{
  "no_rawat": "2026/06/11/000001",
  "no_sep": "0301R0010626V000001",
  "no_kartu": "0001234567890",
  "nik": "357xxxxxxxxxxxxx",
  "nama_pasien": "Budi Santoso",
  "tgl_kunjungan": "2026-06-11",
  "kode_poli": "INT",
  "nama_poli": "Penyakit Dalam",
  "kode_dokter": "12345",
  "nama_dokter": "dr. Ahmad",
  "keluhan_utama": "Demam dan batuk",
  "sistole": 120,
  "diastole": 80,
  "nadi": 88,
  "respirasi": 20,
  "suhu": 37.5,
  "spo2": 98
}
```

Dokumentasi lengkap ada di `docs/bpjs-emr.md`.

### Antrol

```http
POST /api/v1/antrol/antrean
POST /api/v1/antrol/taskid
POST /api/v1/antrol/batal
```

### SATUSEHAT Mapper Mode

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

Contoh RAW BPJS:

```http
POST /api/v1/raw/bpjs/vclaim/SEP/2.0/insert
```

Contoh RAW SATUSEHAT:

```http
POST /api/v1/raw/satusehat/Encounter
```

## Contoh Request SATUSEHAT Encounter Mapper

```json
{
  "no_rawat": "2026/06/11/000001",
  "patient_ihs": "P020123456",
  "practitioner_ihs": "100009880728",
  "organization_id": "761ecdde-9a94-499e-8594-13075fa82dbd",
  "period_start": "2026-06-11 08:00:00",
  "period_end": "2026-06-11 08:30:00"
}
```

## Contoh Response Sukses

```json
{
  "success": true,
  "service": "satusehat",
  "module": "encounter",
  "message": "Request SATUSEHAT berhasil",
  "data": {},
  "meta": {
    "request_id": "REQ-20260611-101010-ABC123",
    "duration_ms": 845
  }
}
```

## Contoh Response Error

```json
{
  "success": false,
  "service": "satusehat",
  "module": "encounter",
  "message": "Payload tidak valid",
  "error": {
    "code": "VALIDATION_ERROR",
    "detail": "patient_ihs wajib diisi"
  },
  "meta": {
    "request_id": "REQ-20260611-101010-ABC123"
  }
}
```

## Catatan Penting

Project ini sudah menyiapkan struktur, endpoint, mapping, logging, security key, token cache, dan HTTP client. Agar koneksi benar-benar berjalan ke BPJS/SATUSEHAT production, Anda tetap harus mengisi credential resmi dari BPJS/Kemenkes dan menyesuaikan base URL serta path endpoint sesuai environment dan dokumentasi resmi. Khusus modul EMR/RME BPJS, path endpoint dibuat configurable karena implementasi dan akses dapat berbeda pada masing-masing fasyankes.

## License

MIT License
