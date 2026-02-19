# Avance del Proyecto SHCSO

## Estado general

- Fecha de inicio: 2026-02-17
- Stack actual: PHP 8.3 + Laravel 12 + PostgreSQL
- Estado: MVP backend + frontend operativo base implementado

## Avance consolidado (2026-02-18)

- [x] Analisis de `propuestaDesarrolloSHCSO.md`
- [x] Definicion de alcance MVP tecnico
- [x] Creacion de backend Laravel en `backend/`
- [x] Implementacion de modelo de datos ocupacional (migraciones)
- [x] Implementacion de autenticacion con Sanctum
- [x] Implementacion de RBAC basico con middleware de roles
- [x] Implementacion de modulos:
  - catalogos (empresas, puestos)
  - trabajadores
  - evaluaciones medicas ocupacionales
  - certificados desde evaluacion
  - auditoria de acciones
- [x] Listado de evaluaciones con filtros (`GET /api/evaluations`)
- [x] Carga/listado de adjuntos de evaluaciones (`/api/evaluations/{id}/attachments`)
- [x] Generacion y descarga de PDF de certificado (`/api/certificates/{id}/generate-pdf` y `/download-pdf`)
- [x] Reportes operativos y dashboard base (`/api/reports/*`)
- [x] Vista web inicial del sistema en Laravel (`/sistema`) con login, metricas y formularios operativos
- [x] Modulo web de trabajadores con historial clinico y edicion (`/sistema/trabajadores`)
- [x] Navegacion web por modulos (`/sistema`, `/sistema/trabajadores`, `/sistema/operacion`) con tabs y soporte de rutas
- [x] Filtros operativos en frontend:
  - busqueda de trabajadores por documento/nombre
  - filtros de evaluaciones por tipo, aptitud y rango de fechas
  - filtros de certificados por aptitud y rango de fechas
- [x] API de trabajadores extendida:
  - `PUT /api/workers/{workerId}`
  - `GET /api/workers/{workerId}/history`
- [x] Seed inicial de roles y catalogos base
- [x] Validaciones locales:
  - `php artisan migrate:fresh --seed`
  - `php artisan route:list`
  - `php artisan test` (incluyendo pruebas de historial/edicion de trabajador)
- [ ] Ajustes finales de UX/UI (tablas paginadas, mensajes de validacion mas completos y exportaciones)
- [x] Modulo de usuarios en interfaz (crear/editar/desactivar usuarios y roles desde web)
- [x] Historia clinica ampliada (antecedentes estructurados y evolucion longitudinal)

## Decisiones tecnicas

- Se migro a Laravel al quedar disponible PHP/Composer en el entorno del usuario.
- El modelo funcional respeta la propuesta HCU081/2025 y el certificado ocupacional.
- Se mantuvo enfoque API-first para facilitar frontend web posterior.

## Corte actual (2026-02-19)

### Realizado

- API principal de SHCSO funcional con autenticacion, roles, catalogos, trabajadores, evaluaciones, certificados, reportes y auditoria.
- Vista web operativa con login, dashboard, modulo trabajadores, modulo operacion y filtros.
- Historial por trabajador implementado (evaluaciones + certificados + edicion basica de ficha).
- Soporte PostgreSQL estabilizado (migraciones y consultas compatibles).

### Pendiente critico (producto cliente)

1. Mejoras de UX:
   - paginacion en tablas
   - validaciones y mensajes mas claros en formularios
   - exportaciones basicas (CSV/PDF) en listados clave

## Actualizacion ejecutada (2026-02-19)

### Analisis aplicado

- Se priorizo el Bloque 1 del plan por impacto directo en operacion diaria del cliente.
- Se mantuvo la arquitectura actual (API Laravel + vista Blade unica con JS embebido) para no introducir deuda de migracion en esta fase.
- Se implemento control estricto por rol `ADMIN` para gestion de usuarios.

### Entregable implementado

- Backend usuarios:
  - `GET /api/users`
  - `GET /api/users/roles`
  - `POST /api/users`
  - `PUT /api/users/{userId}`
  - `PUT /api/users/{userId}/status`
- Frontend usuarios en `/sistema/usuarios`:
  - listado de usuarios
  - alta de usuario con rol
  - edicion de datos/rol
  - activacion y desactivacion
- Login:
  - se retiro el texto orientado a Postman
  - se reemplazo por mensaje de flujo interno para usuario final
  - se agrego deteccion de estado inicial y formulario de primer administrador en la propia UI
- Pruebas:
  - se agrego `backend/tests/Feature/UserManagementApiTest.php`
  - ejecucion local bloqueada por falta de `pdo_sqlite` en PHP CLI del entorno actual

### Plan inmediato (siguiente bloque)

1. Historia clinica ampliada:
   - migracion y API para antecedentes estructurados
   - formulario dedicado en modulo trabajadores
2. UX:
   - paginacion de tablas principales
   - validaciones de formularios con mensajes detallados
3. Cierre documental:
   - actualizar `README.md` con endpoints de usuarios y flujo de acceso

## Plan de trabajo para manana

### Bloque 1 - Usuarios (prioridad alta)

1. Backend:
   - endpoints para listar, crear, actualizar y activar/inactivar usuarios
   - validaciones y reglas por rol (solo `ADMIN`)
2. Frontend:
   - nuevo modulo `/sistema/usuarios`
   - formulario de alta y tabla de usuarios
   - asignacion de roles desde UI
3. Mensajeria:
   - eliminar dependencia visual de Postman en login
   - texto orientado a cliente final

### Bloque 2 - Historia clinica extendida (prioridad alta)

1. Modelo de datos:
   - estructura para antecedentes y datos clinicos base
2. API:
   - guardar/consultar historia clinica extendida por trabajador
3. UI:
   - seccion dedicada en `/sistema/trabajadores` para edicion y consulta

## Actualizacion ejecutada (2026-02-19 - bloque historia clinica)

### Implementado

- Modelo de datos:
  - nueva tabla `worker_clinical_histories` (migracion aplicada)
- API trabajadores:
  - `GET /api/workers/{workerId}/clinical-history`
  - `PUT /api/workers/{workerId}/clinical-history`
  - `GET /api/workers/{workerId}/history` extendido con:
    - `clinical_history`
    - `clinical_timeline` (eventos de evaluaciones y certificados)
- Frontend:
  - formulario de historia clinica ampliada en `/sistema/trabajadores`
  - visualizacion de linea de tiempo clinica en ficha del trabajador
- Pruebas:
  - `WorkerHistoryApiTest` ampliado para guardar/consultar historia clinica
  - validacion local completa: `11 passed (54 assertions)`

### Bloque 3 - Cierre de sprint (prioridad media)

1. Paginacion y filtros avanzados en tablas grandes.
2. Ajustes de PDF institucional (logo/sello/firma).
3. Actualizacion de `README.md` y bitacora con evidencias del avance.
