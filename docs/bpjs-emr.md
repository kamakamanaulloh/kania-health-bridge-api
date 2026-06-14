# BPJS EMR / RME Bridge

Modul ini menambahkan layer bridging untuk kebutuhan pengiriman data EMR/RME dari SIMRS ke layanan BPJS yang dipakai fasyankes.

> Catatan: nama path endpoint EMR/RME BPJS dapat berbeda sesuai dokumentasi dan akses masing-masing fasyankes. Karena itu modul ini dibuat **configurable** lewat `.env` dan tetap menyediakan **RAW Mode**.

## Konfigurasi `.env`

```env
BPJS_EMR_CONS_ID=
BPJS_EMR_SECRET_KEY=
BPJS_EMR_USER_KEY=
BPJS_EMR_BASE_URL=

BPJS_EMR_ENDPOINT_KUNJUNGAN=emr/kunjungan
BPJS_EMR_ENDPOINT_DIAGNOSA=emr/diagnosa
BPJS_EMR_ENDPOINT_TINDAKAN=emr/tindakan
BPJS_EMR_ENDPOINT_RESEP=emr/resep
BPJS_EMR_ENDPOINT_LABORATORIUM=emr/laboratorium
BPJS_EMR_ENDPOINT_RADIOLOGI=emr/radiologi
BPJS_EMR_ENDPOINT_RESUME=emr/resume
```

Jika credential EMR sama dengan VClaim, kosongkan `BPJS_EMR_CONS_ID`, `BPJS_EMR_SECRET_KEY`, dan `BPJS_EMR_USER_KEY`. Sistem akan fallback ke `BPJS_CONS_ID`, `BPJS_SECRET_KEY`, dan `BPJS_USER_KEY`.

## Header

```http
X-BRIDGE-KEY: isi-key-rahasia
Accept: application/json
Content-Type: application/json
```

## Status Konfigurasi

```http
GET /api/v1/bpjs/emr/status
```

Endpoint ini hanya menampilkan apakah credential dan base URL sudah terbaca. Nilai rahasia tidak ditampilkan.

## Mapper Mode

Mapper mode menerima payload sederhana dari SIMRS, lalu bridge mengubahnya ke format payload internal yang lebih rapi sebelum dikirim ke endpoint EMR BPJS yang dikonfigurasi.

```http
POST /api/v1/bpjs/emr/kunjungan
POST /api/v1/bpjs/emr/diagnosa
POST /api/v1/bpjs/emr/tindakan
POST /api/v1/bpjs/emr/resep
POST /api/v1/bpjs/emr/laboratorium
POST /api/v1/bpjs/emr/radiologi
POST /api/v1/bpjs/emr/resume
```

### Override endpoint per request

Jika ingin mencoba endpoint lain tanpa mengubah `.env`, kirim field `endpoint`:

```json
{
  "endpoint": "path/resmi/dari/dokumentasi/bpjs",
  "no_rawat": "2026/06/11/000001",
  "no_kartu": "0001234567890",
  "nik": "357xxxxxxxxxxxxx",
  "nama_pasien": "Budi Santoso",
  "tgl_kunjungan": "2026-06-11"
}
```

Field `endpoint` tidak akan ikut dikirim ke BPJS; hanya dipakai oleh bridge untuk memilih path tujuan.

## RAW Mode

RAW Mode dipakai jika SIMRS sudah memiliki payload resmi dari BPJS dan hanya ingin memanfaatkan signature, decrypt, logging, dan standard response dari bridge.

```http
POST /api/v1/bpjs/emr/raw/{path}
GET  /api/v1/bpjs/emr/raw/{path}
PUT  /api/v1/bpjs/emr/raw/{path}
DELETE /api/v1/bpjs/emr/raw/{path}
```

Contoh:

```http
POST /api/v1/bpjs/emr/raw/emr/kunjungan
```

Body dikirim apa adanya sebagai JSON.

## Contoh Kunjungan

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
  "anamnesis": "Demam 2 hari disertai batuk",
  "pemeriksaan_fisik": "Keadaan umum cukup",
  "sistole": 120,
  "diastole": 80,
  "nadi": 88,
  "respirasi": 20,
  "suhu": 37.5,
  "spo2": 98
}
```

## Contoh Diagnosa

```json
{
  "no_rawat": "2026/06/11/000001",
  "no_sep": "0301R0010626V000001",
  "diagnosa": [
    {
      "kode": "J06.9",
      "nama": "Acute upper respiratory infection, unspecified",
      "tipe": "primer"
    }
  ]
}
```

## Contoh Tindakan

```json
{
  "no_rawat": "2026/06/11/000001",
  "tindakan": [
    {
      "kode": "89.01",
      "nama": "Interview and evaluation",
      "tanggal": "2026-06-11",
      "jumlah": 1,
      "dokter": "dr. Ahmad"
    }
  ]
}
```

## Contoh Resep

```json
{
  "no_rawat": "2026/06/11/000001",
  "no_resep": "RSP-000001",
  "tanggal_resep": "2026-06-11",
  "obat": [
    {
      "kode": "OBT001",
      "nama": "Paracetamol 500 mg",
      "jumlah": 10,
      "satuan": "tablet",
      "aturan_pakai": "3x1 sesudah makan"
    }
  ]
}
```

## Contoh Laboratorium

```json
{
  "no_rawat": "2026/06/11/000001",
  "tanggal_periksa": "2026-06-11",
  "hasil_lab": [
    {
      "kode": "HB",
      "nama": "Hemoglobin",
      "hasil": "13.2",
      "satuan": "g/dL",
      "nilai_rujukan": "13-17"
    }
  ]
}
```

## Contoh Radiologi

```json
{
  "no_rawat": "2026/06/11/000001",
  "tanggal_periksa": "2026-06-11",
  "hasil_radiologi": [
    {
      "kode": "THORAX",
      "nama": "Foto Thorax",
      "hasil": "Cor dan pulmo dalam batas normal",
      "kesan": "Tidak tampak kelainan aktif",
      "dokter": "dr. Radiologi"
    }
  ]
}
```

## Contoh Resume

```json
{
  "no_rawat": "2026/06/11/000001",
  "no_sep": "0301R0010626V000001",
  "no_kartu": "0001234567890",
  "nik": "357xxxxxxxxxxxxx",
  "nama_pasien": "Budi Santoso",
  "tanggal_masuk": "2026-06-11",
  "tanggal_keluar": "2026-06-11",
  "dokter_penanggung_jawab": "dr. Ahmad",
  "diagnosa": ["J06.9"],
  "tindakan": ["89.01"],
  "terapi": ["Paracetamol 3x1"],
  "kondisi_pulang": "Membaik",
  "instruksi_lanjutan": "Kontrol bila keluhan berlanjut"
}
```
