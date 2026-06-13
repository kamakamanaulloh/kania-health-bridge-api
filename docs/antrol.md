# Antrol

## Tambah Antrean

```http
POST /api/v1/antrol/antrean
```

Payload mengikuti mapper `App\Mappers\Bpjs\AntrolMapper`.

## Update Task ID

```http
POST /api/v1/antrol/taskid
```

```json
{
  "kodebooking": "ABC123",
  "taskid": 3,
  "waktu": 1718070000000
}
```

## Batal Antrean

```http
POST /api/v1/antrol/batal
```

```json
{
  "kodebooking": "ABC123",
  "keterangan": "Pasien batal"
}
```
