# SATUSEHAT

## Token

```http
GET /api/v1/satusehat/token
```

## Encounter Mapper

```http
POST /api/v1/satusehat/encounter
```

Payload minimal:

```json
{
  "no_rawat": "2026/06/11/000001",
  "patient_ihs": "P020123456",
  "practitioner_ihs": "100009880728",
  "organization_id": "ORG_ID",
  "period_start": "2026-06-11 08:00:00",
  "period_end": "2026-06-11 08:30:00"
}
```

## Condition Mapper

```http
POST /api/v1/satusehat/condition
```

```json
{
  "patient_ihs": "P020123456",
  "encounter_id": "ENCOUNTER_ID",
  "icd10_code": "I10",
  "diagnosis_name": "Essential hypertension"
}
```

## Observation Mapper

```http
POST /api/v1/satusehat/observation
```

```json
{
  "patient_ihs": "P020123456",
  "encounter_id": "ENCOUNTER_ID",
  "loinc_code": "8310-5",
  "display": "Body temperature",
  "value": 36.7,
  "unit": "Cel",
  "unit_code": "Cel"
}
```
