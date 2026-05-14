# SHCSO

Sistema de Historias Clinicas y Salud Ocupacional, implementado en:

- `Laravel 12` (API backend)
- `PostgreSQL` (base de datos)

## Estructura actual

- `backend/`: proyecto Laravel principal
- `propuestaDesarrolloSHCSO.md`: documento base funcional/técnico
- `AVANCE_PROYECTO.md`: bitácora de avance del proyecto

## Requisitos

- PHP 8.2+ (ok en tu caso: 8.3.16)
- Composer 2+
- PostgreSQL 14+

## Configuración rápida (Laragon + DBeaver)

1. Crear base de datos `shcso` en DBeaver.
2. Crear archivo de entorno del backend:

```bash
cd backend
copy .env.example .env
```

3. En `backend/.env` configurar:
   - `DB_CONNECTION=pgsql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=5432`
   - `DB_DATABASE=shcso`
   - `DB_USERNAME=postgres` (o tu usuario)
   - `DB_PASSWORD=...`
4. Ejecutar migraciones y seed:

```bash
cd backend
php artisan migrate --seed
```

5. Levantar API:

```bash
php artisan serve
```

6. Crear enlace publico de archivos (adjuntos/PDF):

```bash
php artisan storage:link
```

7. Abrir vista web del sistema:

```text
http://127.0.0.1:8000/sistema
```

Si no existe un usuario `ADMIN`, la pantalla de login muestra automaticamente el formulario para crear el primer administrador.

Vistas web disponibles:

- `GET /sistema` (dashboard operativo)
- `GET /sistema/trabajadores` (misma app con foco en ficha e historial)
- `GET /sistema/operacion` (misma app con foco en evaluaciones/certificados)
- `GET /sistema/usuarios` (gestion de usuarios, solo `ADMIN`)

Funciones web actuales:

- Navegacion por modulos con tabs y rutas directas (`dashboard`, `trabajadores`, `operacion`, `usuarios`)
- Flujo guiado en modulo trabajadores por vinetas:
  - `1. Trabajadores recientes`
  - `2. Nuevo trabajador` (lista + crear/editar en formulario unificado)
  - `3. Historia clinica ampliada`
  - `4. Historial clinico`
- Busqueda de trabajadores por documento o nombre en UI
- Acciones de trabajador en UI: `Ver`, `Editar`, `Eliminar`
- Filtros de evaluaciones por tipo, aptitud y rango de fechas
- Filtros de certificados por aptitud y rango de fechas
- Gestion de usuarios desde interfaz web (crear, editar, activar/desactivar) para perfil `ADMIN`
- Historia clinica ampliada por trabajador (antecedentes estructurados y notas de evolucion longitudinal)
- Linea de tiempo clinica consolidada (evaluaciones y certificados) en ficha de trabajador
- Carga de examenes/adjuntos por evaluacion con metadatos:
  - tipo de adjunto (laboratorio, imagen, DICOM, etc.)
  - fecha del estudio
  - notas clinicas del examen
  - descarga segura del adjunto desde API
- Recuperacion de contrasena:
  - opcion "Olvide mi contrasena" con token de recuperacion
  - generacion de enlace directo clickeable a `/sistema` con `email` + `reset_token`
  - opcion "Ya tengo token" para establecer nueva clave
  - reseteo administrativo de clave temporal desde modulo de usuarios
- Consulta medica estructurada en UI de operacion:
  - datos de paciente y filtro rapido por nombre/cedula
  - captura de signos vitales
  - secciones SOAP (Subjetivo, Objetivo, Analisis, Plan)
  - buscador CIE-10 en linea para seleccionar diagnosticos
  - receta medica opcional con multiples medicamentos por consulta
- Paginacion en listados operativos (trabajadores, evaluaciones, certificados, usuarios)
- Exportacion CSV basica (pagina actual) para trabajadores, evaluaciones, certificados y usuarios
- Mensajes de validacion de API mostrados por campo en la UI

## Endpoints principales (MVP)

- `POST /api/auth/register-admin`
- `GET /api/auth/setup-status`
- `POST /api/auth/login`
- `POST /api/auth/forgot-password`
- `POST /api/auth/reset-password`
- `GET /api/auth/me`
- `POST /api/auth/logout`
- `GET /api/users` (`ADMIN`)
- `GET /api/users/roles` (`ADMIN`)
- `POST /api/users` (`ADMIN`)
- `PUT /api/users/{userId}` (`ADMIN`)
- `PUT /api/users/{userId}/status` (`ADMIN`)
- `PUT /api/users/{userId}/reset-password` (`ADMIN`)
- `GET|POST /api/catalog/companies`
- `GET|POST /api/catalog/job-positions` (puestos/actividades desde CIIU Rev. 4.0 INEC; acepta `q`, `level`, `limit`)
- `GET /api/catalog/diagnoses` (busqueda CIE-10)
- `GET|POST /api/workers`
- `PUT /api/workers/{workerId}`
- `DELETE /api/workers/{workerId}`
- `GET /api/workers/{workerId}`
- `GET /api/workers/{workerId}/clinical-history`
- `PUT /api/workers/{workerId}/clinical-history`
- `GET /api/workers/{workerId}/history`
- `POST /api/evaluations`
- `GET /api/evaluations` (filtros por `company_id`, `medical_aptitude`, fechas, etc.)
- `GET /api/evaluations/{evaluationId}`
- `POST /api/evaluations/{evaluationId}/attachments` (multipart/form-data, campo `file`)
- `GET /api/evaluations/{evaluationId}/attachments`
- `GET /api/evaluations/attachments/{attachmentId}/download`
- `POST /api/certificates/from-evaluation/{evaluationId}`
- `GET /api/certificates`
- `GET /api/certificates/{certificateId}`
- `POST /api/certificates/{certificateId}/generate-pdf`
- `GET /api/certificates/{certificateId}/download-pdf`
- `GET /api/reports/dashboard`
- `GET /api/reports/aptitude-by-company`
- `GET /api/reports/top-diagnoses`
- `GET /api/reports/monthly-activity`

Estados de agenda soportados:

- `PENDIENTE`
- `CONFIRMADA`
- `CANCELADA`
- `CANCELADA_PACIENTE`
- `NO_ASISTIO`

Notas de paginacion en listados:

- Endpoints de listado aceptan `page` y `per_page` (compatibles con `limit`).
- Respuesta incluye `meta` con: `page`, `per_page`, `total`, `total_pages`, `has_next`, `has_prev`.

## PDF institucional (logo, sello, firma)

El certificado PDF ya acepta configuracion institucional por `.env` del backend:

- `SHCSO_INSTITUTION_NAME`
- `SHCSO_INSTITUTION_SUBTITLE`
- `SHCSO_INSTITUTION_CITY`
- `SHCSO_CERTIFICATE_LOGO_PATH`
- `SHCSO_CERTIFICATE_SEAL_PATH`
- `SHCSO_CERTIFICATE_SIGNATURE_PATH`
- `SHCSO_CERTIFICATE_SIGNATURE_NAME`
- `SHCSO_CERTIFICATE_SIGNATURE_TITLE`
- `SHCSO_CERTIFICATE_FOOTER_NOTE`

Rutas recomendadas para imagenes (relativas a `backend/public/`):

- `assets/pdf/logo.png`
- `assets/pdf/sello.png`
- `assets/pdf/firma.png`

## Adjuntos de examenes y DICOM

La carga de adjuntos en evaluaciones acepta formatos:

- `pdf`, `jpg`, `jpeg`, `png`, `dcm`, `dicom`, `ima`, `zip`

Campos adicionales soportados en `POST /api/evaluations/{evaluationId}/attachments`:

- `attachment_type`: `GENERAL`, `LAB_EXAM`, `IMAGING`, `DICOM`, `AUDIO`, `OTHER`
- `exam_date` (opcional)
- `notes` (opcional)
- `file` (obligatorio, max 50 MB)

## Consulta medica (SOAP + receta)

El endpoint `POST /api/evaluations` soporta ahora, ademas de los campos existentes:

- `vital_signs`: objeto con signos vitales (`blood_pressure`, `temperature_c`, `heart_rate`, etc.)
- `physical_exam`: objeto para hallazgos objetivos (ej. `soap_o`)
- `exam_results`: objeto para analisis/documentacion de resultados (ej. `soap_a`)
- `diagnoses`: arreglo con CIE-10 (`code`, `description`, `diagnosis_type`, `notes`)
- `prescriptions`: arreglo opcional de receta:
  - `medication` (obligatorio)
  - `dosage` (obligatorio)
  - `frequency` (opcional)
  - `duration` (opcional)
  - `indications` (opcional)

## Estado

El MVP backend Laravel ya está implementado y probado localmente con:

- `php artisan migrate:fresh --seed`
- `php artisan route:list`
- `php artisan test`

## Nota de testing

- Los tests están configurados para PostgreSQL en `backend/phpunit.xml` con base `shcso_test`.
- Crea la base `shcso_test` antes de ejecutar `php artisan test` para no usar la base operativa `shcso`.
