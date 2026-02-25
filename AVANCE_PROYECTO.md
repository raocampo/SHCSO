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
- [x] Ajustes finales de UX/UI (tablas paginadas, mensajes de validacion mas completos y exportaciones)
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

1. Sin pendientes criticos abiertos en este corte.
   - cerrado: ajuste visual del modulo de operacion
   - cerrado: parametrizacion institucional del PDF (logo/sello/firma)

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

## Actualizacion ejecutada (2026-02-19 - bloque UX)

### Implementado

- Paginacion:
  - backend con `page` y `per_page` en listados de trabajadores, evaluaciones, certificados y usuarios
  - respuesta con `meta` (`total`, `total_pages`, `has_next`, `has_prev`)
  - controles Anterior/Siguiente en UI para tablas principales
- Validaciones:
  - manejo de errores de validacion por campo en frontend (mensajes mas claros para usuario)
- Exportaciones:
  - exportacion CSV (pagina actual) en listados de trabajadores, evaluaciones, certificados y usuarios
- Pruebas:
  - pruebas de paginacion en `WorkerHistoryApiTest` y `UserManagementApiTest`
  - validacion local completa: `13 passed (72 assertions)`

### Bloque 3 - Cierre de sprint (prioridad media)

1. Paginacion y filtros avanzados en tablas grandes.
2. Ajustes de PDF institucional (logo/sello/firma).
3. Actualizacion de `README.md` y bitacora con evidencias del avance.

## Actualizacion ejecutada (2026-02-19 - cierre operativo y PDF institucional)

### Implementado

- UI operacion:
  - panel "Pulso operativo" con indicadores de evaluaciones/certificados filtrados y pendientes por emitir
  - tarjetas de operacion con jerarquia visual y guia de flujo `1/2` y `2/2`
  - codificacion visual por aptitud (`APTO`, `APTO_OBSERVACION`, `APTO_LIMITACIONES`, `NO_APTO`) en historiales y tablas
- PDF institucional:
  - nueva configuracion `backend/config/shcso.php`
  - plantilla `pdf/certificate.blade.php` mejorada con:
    - datos institucionales
    - soporte de logo/sello/firma desde `.env`
    - placeholders cuando no existen imagenes
  - controlador de certificados actualizado para resolver rutas de assets publicos
- Entorno/documentacion:
  - agregado `backend/.env.example` con variables de PostgreSQL y variables institucionales del PDF
  - `README.md` actualizado con seccion de personalizacion institucional del certificado

## Actualizacion ejecutada (2026-02-19 - estabilizacion final de jornada)

### Implementado

- Assets institucionales temporales para certificacion PDF:
  - `backend/public/assets/pdf/logo.svg`
  - `backend/public/assets/pdf/sello.svg`
  - `backend/public/assets/pdf/firma.svg`
- Configuracion local validada para PDF institucional en `backend/.env` con variables `SHCSO_*`.
- Prueba funcional de generacion de certificado:
  - PDF generado correctamente en `backend/storage/app/public/certificates/`.
- Correccion de bloqueo de ingreso en `/sistema`:
  - fix JS en `normalizePageMeta` por conflicto de operadores `??` y `||`.
  - validacion de sintaxis de script y despliegue de correccion en `master`.

### Pendiente para manana

1. Reemplazar assets temporales (`logo/sello/firma`) por imagenes institucionales oficiales del cliente.
2. Ejecutar QA rapido de interfaz en `/sistema`, `/sistema/trabajadores`, `/sistema/operacion`, `/sistema/usuarios`.
3. Validar flujo completo en ambiente local:
   - crear evaluacion
   - emitir certificado
   - generar/descargar PDF con branding oficial
4. Definir cierre de sprint y preparar mini acta de entrega tecnica (alcance implementado + riesgos remanentes).

## Actualizacion ejecutada (2026-02-20 - QA tecnico y acta de cierre)

### Implementado

- QA tecnico automatizado reforzado:
  - nueva prueba `CertificateFlowApiTest` para flujo completo:
    - crear evaluacion
    - emitir certificado
    - generar PDF
    - descargar PDF
  - ampliacion de prueba web para incluir `/sistema/usuarios`.
- Validacion local de regresion:
  - `php artisan test`
  - resultado: `15 passed (89 assertions)`.
- Documento de cierre tecnico agregado:
  - `ACTA_CIERRE_TECNICO.md` con alcance validado, evidencia, riesgos remanentes y pendientes de cierre con cliente.

### Pendiente actual

1. Reemplazar assets temporales por logo/sello/firma oficiales del cliente.
2. Ejecutar validacion visual final del PDF con branding oficial.
3. Cerrar UAT final con cliente y registrar aceptacion funcional.

## Actualizacion ejecutada (2026-02-20 - adjuntos clinicos y soporte DICOM)

### Implementado

- Backend evaluaciones:
  - ampliacion de adjuntos para examenes clinicos y radiologia (incluye DICOM)
  - validacion de formatos: `pdf`, `jpg`, `jpeg`, `png`, `dcm`, `dicom`, `ima`, `zip`
  - metadatos en adjunto:
    - `attachment_type`
    - `exam_date`
    - `notes`
    - `file_size_bytes`
    - `original_extension`
  - nuevo endpoint de descarga segura:
    - `GET /api/evaluations/attachments/{attachmentId}/download`
- Frontend `/sistema`:
  - formulario de adjuntos ampliado con tipo de examen, fecha y notas
  - soporte de seleccion de archivos DICOM/ZIP desde UI
  - listado de adjuntos por evaluacion en historial del trabajador con boton de descarga
- Pruebas:
  - nuevo `EvaluationAttachmentApiTest` para:
    - carga de DICOM con metadatos
    - descarga de adjunto

### Pendiente actual

1. Sustituir branding temporal por archivos institucionales oficiales.
2. Validar visualmente PDF institucional final.
3. UAT final con cliente (incluyendo flujo de adjuntos DICOM en operacion real).

## Actualizacion ejecutada (2026-02-20 - recuperacion de contrasena y reset administrativo)

### Implementado

- Opcion 1 (usuario final):
  - `POST /api/auth/forgot-password` para solicitud de token por correo.
  - `POST /api/auth/reset-password` para definir nueva contrasena con token.
  - enlace de recuperacion directo generado hacia `/sistema` con `email` y `reset_token`.
  - UI en login con formularios:
    - "Olvide mi contrasena"
    - "Ya tengo token"
- Opcion 2 (administrador):
  - `PUT /api/users/{userId}/reset-password` para generar clave temporal.
  - boton "Reset clave" en tabla de usuarios del modulo `/sistema/usuarios`.
- Seguridad y operacion:
  - al resetear contrasena se invalidan tokens API activos del usuario.
  - eventos de auditoria registrados para solicitud/reset de contrasena.
- Pruebas:
  - nuevo `AuthPasswordRecoveryApiTest` (forgot + reset con token valido/invalido).
  - `UserManagementApiTest` ampliado para reset administrativo.
  - validacion completa: `22 passed (120 assertions)`.

### Pendiente actual

1. Sustituir branding temporal por archivos institucionales oficiales.
2. Validar visualmente PDF institucional final.
3. UAT final con cliente (incluyendo flujo de adjuntos DICOM y recuperacion de contrasena).

## Actualizacion ejecutada (2026-02-20 - flujo por vinetas en modulo trabajadores)

### Implementado

- Frontend `/sistema/trabajadores` reorganizado en flujo paso a paso por vinetas:
  - `1. Nuevo trabajador`
  - `2. Trabajadores recientes`
  - `3. Ficha y edicion`
  - `4. Historia clinica ampliada`
  - `5. Historial clinico`
- Separacion de secciones que antes estaban en una sola tarjeta:
  - ficha/edicion en panel independiente
  - historia clinica ampliada en panel independiente
  - historial clinico en panel independiente
- Comportamiento guiado:
  - al crear trabajador se avanza automaticamente a "Trabajadores recientes"
  - al usar "Ver historial" se abre automaticamente la vineta "Historial clinico"
- Validaciones:
  - `php artisan test`
  - resultado: `22 passed (124 assertions)`.

### Pendiente actual

1. Sustituir branding temporal por archivos institucionales oficiales.
2. Validar visualmente PDF institucional final.
3. UAT final con cliente (incluyendo flujo de adjuntos DICOM, recuperacion de contrasena y navegacion por vinetas en trabajadores).

## Actualizacion ejecutada (2026-02-20 - reorganizacion avanzada del modulo trabajadores)

### Implementado

- Reorden de flujo por vinetas en `/sistema/trabajadores`:
  - `1. Trabajadores recientes`
  - `2. Nuevo trabajador`
  - `3. Historia clinica ampliada`
  - `4. Historial clinico`
- Eliminacion de redundancia de "Ficha y edicion" como tab separado:
  - se unifico en la vineta `2. Nuevo trabajador` con formulario unico crear/editar.
- Vineta `2. Nuevo trabajador` ahora incluye:
  - lista de trabajadores con acciones `Ver`, `Editar`, `Eliminar`
  - boton `Crear trabajador`
  - formulario unificado de datos personales/laborales para alta y edicion.
- Mejora de distribucion en escritorio del formulario:
  - grilla de campos por filas (2-6 inputs por fila segun tamano del campo)
  - en movil se conserva vista vertical.
- Backend API:
  - nuevo endpoint `DELETE /api/workers/{workerId}`.
- Pruebas:
  - `WorkerHistoryApiTest` ampliado con eliminacion de trabajador.
  - validacion local completa: `23 passed (127 assertions)`.

## Actualizacion ejecutada (2026-02-20 - ajuste visual de nuevo trabajador)

### Implementado

- Mejora visual del paso `2. Nuevo trabajador` en `/sistema/trabajadores`:
  - panel de trabajo con mayor ancho util y mejor distribucion en escritorio
  - espaciado y jerarquia visual mejorados en toolbar, tabla y formulario
  - formulario con grilla mas respirada para evitar campos "apretados"
  - mantenimiento de comportamiento vertical en movil
- La experiencia conserva el flujo operativo actual:
  - crear/editar desde formulario unificado
  - acciones `Ver`, `Editar`, `Eliminar` por trabajador

## Actualizacion ejecutada (2026-02-20 - consulta medica estructurada SOAP)

### Implementado

- Backend:
  - nueva tabla `evaluation_prescriptions` para receta medica por evaluacion.
  - nuevo modelo `EvaluationPrescription` y relacion en `OccupationalEvaluation`.
  - `POST /api/evaluations` ampliado para guardar:
    - signos vitales
    - secciones SOAP (`consultation_reason`, `physical_exam`, `exam_results`, `recommendations`)
    - diagnosticos CIE-10
    - receta medica opcional (multiples medicamentos)
  - `GET /api/evaluations/{evaluationId}` y `GET /api/workers/{workerId}/history` incluyen prescripciones.
  - nuevo endpoint `GET /api/catalog/diagnoses` para buscador CIE-10 por codigo/descripcion.
- Frontend `/sistema` (modulo operacion):
  - formulario de consulta medica con secciones:
    - paciente (filtro + selector)
    - signos vitales
    - metodo SOAP
    - buscador CIE-10 y seleccion de diagnosticos
    - receta medica dinamica (agregar/quitar medicamentos)
  - historial clinico del trabajador muestra resumen SOAP y receta registrada por evaluacion.
- Pruebas:
  - nuevo `MedicalConsultationApiTest` con cobertura de:
    - alta de consulta con receta
    - busqueda CIE-10 en catalogo
  - validacion local completa: `25 passed (141 assertions)`.
