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
2. En `backend/.env` configurar:
   - `DB_CONNECTION=pgsql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=5432`
   - `DB_DATABASE=shcso`
   - `DB_USERNAME=postgres` (o tu usuario)
   - `DB_PASSWORD=...`
3. Ejecutar migraciones y seed:

```bash
cd backend
php artisan migrate --seed
```

4. Levantar API:

```bash
php artisan serve
```

5. Crear enlace publico de archivos (adjuntos/PDF):

```bash
php artisan storage:link
```

6. Abrir vista web del sistema:

```text
http://127.0.0.1:8000/sistema
```

Puedes iniciar sesion con un usuario creado por API (`/api/auth/register-admin`).

Vistas web disponibles:

- `GET /sistema` (dashboard operativo)
- `GET /sistema/trabajadores` (misma app con foco en ficha e historial)
- `GET /sistema/operacion` (misma app con foco en evaluaciones/certificados)
- `GET /sistema/usuarios` (gestion de usuarios, solo `ADMIN`)

Funciones web actuales:

- Navegacion por modulos con tabs y rutas directas (`dashboard`, `trabajadores`, `operacion`, `usuarios`)
- Busqueda de trabajadores por documento o nombre en UI
- Filtros de evaluaciones por tipo, aptitud y rango de fechas
- Filtros de certificados por aptitud y rango de fechas
- Gestion de usuarios desde interfaz web (crear, editar, activar/desactivar) para perfil `ADMIN`

## Endpoints principales (MVP)

- `POST /api/auth/register-admin`
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`
- `GET /api/users` (`ADMIN`)
- `GET /api/users/roles` (`ADMIN`)
- `POST /api/users` (`ADMIN`)
- `PUT /api/users/{userId}` (`ADMIN`)
- `PUT /api/users/{userId}/status` (`ADMIN`)
- `GET|POST /api/catalog/companies`
- `GET|POST /api/catalog/job-positions`
- `GET|POST /api/workers`
- `PUT /api/workers/{workerId}`
- `GET /api/workers/{workerId}`
- `GET /api/workers/{workerId}/history`
- `POST /api/evaluations`
- `GET /api/evaluations` (filtros por `company_id`, `medical_aptitude`, fechas, etc.)
- `GET /api/evaluations/{evaluationId}`
- `POST /api/evaluations/{evaluationId}/attachments` (multipart/form-data, campo `file`)
- `GET /api/evaluations/{evaluationId}/attachments`
- `POST /api/certificates/from-evaluation/{evaluationId}`
- `GET /api/certificates`
- `GET /api/certificates/{certificateId}`
- `POST /api/certificates/{certificateId}/generate-pdf`
- `GET /api/certificates/{certificateId}/download-pdf`
- `GET /api/reports/dashboard`
- `GET /api/reports/aptitude-by-company`
- `GET /api/reports/top-diagnoses`
- `GET /api/reports/monthly-activity`

## Estado

El MVP backend Laravel ya está implementado y probado localmente con:

- `php artisan migrate:fresh --seed`
- `php artisan route:list`
- `php artisan test`
