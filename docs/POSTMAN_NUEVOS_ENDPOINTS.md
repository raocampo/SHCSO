# Probar nuevos endpoints en Postman

## 1) Listar evaluaciones con filtros

`GET {{base_url}}/api/evaluations?company_id=1&medical_aptitude=APTO&date_from=2026-01-01&date_to=2026-12-31&limit=20`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 2) Subir adjunto a una evaluacion

`POST {{base_url}}/api/evaluations/{{evaluation_id}}/attachments`

Body:

- `form-data`
- key `file` de tipo **File** (pdf/jpg/jpeg/png)

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 3) Listar adjuntos de una evaluacion

`GET {{base_url}}/api/evaluations/{{evaluation_id}}/attachments`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 4) Generar PDF de certificado

`POST {{base_url}}/api/certificates/{{certificate_id}}/generate-pdf`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 5) Descargar PDF generado

`GET {{base_url}}/api/certificates/{{certificate_id}}/download-pdf`

Headers:

- `Authorization: Bearer {{token}}`

## 6) Dashboard de reportes

`GET {{base_url}}/api/reports/dashboard`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 7) Aptitud por empresa

`GET {{base_url}}/api/reports/aptitude-by-company?limit=20`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 8) Diagnosticos mas frecuentes

`GET {{base_url}}/api/reports/top-diagnoses?limit=10`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`

## 9) Actividad mensual

`GET {{base_url}}/api/reports/monthly-activity?months=12`

Headers:

- `Accept: application/json`
- `Authorization: Bearer {{token}}`
