# BPJS VClaim

## Cek Peserta by NIK

```http
GET /api/v1/bpjs/peserta/nik/{nik}
```

## Cek Peserta by No Kartu

```http
GET /api/v1/bpjs/peserta/noka/{no_kartu}
```

## Referensi Poli

```http
GET /api/v1/bpjs/referensi/poli/{keyword}
```

## Buat SEP Mapper

```http
POST /api/v1/bpjs/sep
```

Bridge akan mapping payload sederhana ke format `request.t_sep` BPJS.
