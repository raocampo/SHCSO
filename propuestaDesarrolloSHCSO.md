# Propuesta – Sistema de Historias Clínicas Digitales (EHR/EMR) con Evaluación Médica Ocupacional y Certificados

**Versión:** 1.0  
**Fecha:** 2026-02-16  
**Contexto:** Propuesta funcional y técnica para un sistema EHR/EMR con énfasis en **Salud Ocupacional**, basado en los formularios proporcionados en Excel (Evaluación Médica Ocupacional y Certificado).

---

## 1. Resumen Ejecutivo
Se propone el desarrollo de un **Sistema de Historias Clínicas Digitales (EHR/EMR)** para gestionar la información clínica de pacientes/trabajadores de forma segura, centralizada y accesible, incorporando un módulo completo de **Evaluación Médica Ocupacional (HCU081/2025)** y emisión de **Certificados de Aptitud** (Apto/No Apto, etc.).

El sistema moderniza la operación clínica y ocupacional:
- elimina expedientes en papel,
- reduce tiempos de registro y búsqueda,
- aumenta la trazabilidad (auditoría),
- y permite generar documentos oficiales (PDF) con firma/sello.

---

## 2. Objetivos
- Centralizar la historia clínica y ocupacional del paciente/trabajador.
- Estandarizar el registro de Evaluaciones Ocupacionales conforme al formulario **SNS-MSP/Form.HCU081/2025**.
- Emitir **certificados** y documentos imprimibles (PDF) de forma rápida y consistente.
- Asegurar confidencialidad e integridad de datos (RBAC, cifrado, auditoría).
- Facilitar reportes operativos y estadísticos (salud ocupacional, riesgos, diagnósticos, aptitud).

---

## 3. Alcance Funcional (Módulos)

### 3.1 Autenticación, Seguridad y Accesos
- Login seguro (usuario/contraseña) con contraseñas **hasheadas** (bcrypt/argon2).
- Sesiones con expiración e inactividad.
- Recuperación de contraseña (email/token).
- **Control de acceso por roles (RBAC)**.
- Auditoría obligatoria: quién hizo qué y cuándo (accesos, ediciones, emisiones, anulaciones).

### 3.2 Catálogos Base (configurables)
- Catálogo de **Establecimientos / Centros de trabajo** (RUC, CIIU, razón social, ubicación).
- Catálogo de **Puestos de trabajo** (CIUO) y perfiles.
- Catálogo de **Factores de riesgo** por tipo (físico, químico, biológico, ergonómico, psicosocial, mecánico, etc.).
- Catálogo de **Diagnósticos CIE-10** (ICD-10).
- Catálogo de **Exámenes** (laboratorio, imagen, otros).
- Catálogo de **Aptitudes** y dictámenes (Apto, Apto con limitaciones, Apto en observación, No apto).

### 3.3 Gestión de Pacientes/Trabajadores (Ficha)
- Datos demográficos y contacto.
- Datos ocupacionales: empresa/centro, puesto, CIUO, CIIU.
- Condiciones relevantes: grupo sanguíneo, lateralidad, embarazo (si aplica), discapacidad, enfermedad catastrófica, adulto mayor, etc.
- Historial clínico y ocupacional centralizado.
- Documentos adjuntos por paciente (PDF/imágenes).

### 3.4 Módulo de Evaluación Médica Ocupacional (HCU081/2025)
Este módulo implementa el formulario de **Evaluación Médica Ocupacional** en secciones, siguiendo el contenido observado en los archivos Excel:

**A. Datos del establecimiento – datos del usuario**
- Institución del sistema, RUC, CIIU, establecimiento/centro de trabajo
- Número de historia clínica, número de archivo
- Identificación del paciente: apellidos, nombres
- Grupo de atención prioritaria (según campos del formulario)
- Sexo, fecha de nacimiento, edad (año/mes/día)
- Grupo sanguíneo, lateralidad
- Condiciones: embarazo, discapacidad, enfermedad catastrófica, adulto mayor (según aplica)

**B. Motivo de consulta**
- Motivo
- Puesto de trabajo (CIUO)
- Fecha de atención

**C. Antecedentes personales**
- Antecedentes patológicos, quirúrgicos, alérgicos, etc. (configurable)

**D. Enfermedad o problema actual**
- Descripción clínica estructurada + texto libre

**E. Constantes vitales y antropometría**
- Signos vitales y medidas (según práctica clínica: PA, FC, FR, Temp, SpO₂, peso, talla, IMC, etc.)

**F. Examen físico regional**
- Registro por regiones/sistemas (check + observaciones)

**G. Factores de riesgo del trabajo actual**
- Puesto de trabajo y actividades importantes dentro de la jornada laboral
- Matriz de factores de riesgo con selección/observaciones (por tipo)

**H. Actividad laboral / incidentes / accidentes / enfermedades ocupacionales**
- Antecedentes de empleos anteriores y/o trabajos
- Centro de trabajo y actividades desempeñadas
- Incidentes/accidentes y eventos relevantes
- Observaciones

**I. Actividades extra laborales**
- Registro de actividades fuera del trabajo con posibles riesgos asociados

**J. Resultados de exámenes generales y específicos**
- Imagen, laboratorio y otros
- Adjuntos por evaluación (PDF, JPG, PNG)

**K. Diagnóstico (PRE: presuntivo / DEF: definitivo)**
- Diagnósticos CIE-10 asociados a la evaluación
- Estado presuntivo/definitivo y observaciones

**L. Aptitud médica para el trabajo**
- Dictamen de aptitud:
  - Apto
  - Apto en observación
  - Apto con limitaciones
  - No apto
- Restricciones/limitaciones (si aplica)

**M. Recomendaciones y/o tratamiento**
- Recomendaciones, tratamiento y seguimiento
- Derivaciones (si aplica)

**N. Retiro (evaluación)**
- Sección específica para evaluación de retiro (cuando corresponda)

**O. Datos del profesional**
- Médico ocupacional: nombres, código médico, firma/sello
- Registro de responsable y validación de credenciales

**P. Firma o huella del trabajador**
- Captura/registro de firma o huella (imagen) con fecha

> **Tipos de evaluación soportados** (según certificado): **Ingreso, Periódico, Reintegro, Retiro**.

### 3.5 Módulo de Certificados (PDF) – Evaluación Médica Ocupacional
Generación automática del **Certificado de Evaluación Médica Ocupacional** basado en el Excel “CERTIFICADO”, incluyendo:
- A. Datos del establecimiento – datos del usuario (Institución, RUC, CIIU, centro de trabajo, N° historia clínica, N° archivo)
- B. Datos generales: fecha de emisión, tipo de evaluación (Ingreso/Periódico/Reintegro/Retiro), identificación del trabajador, puesto (CIUO), sexo
- C. Aptitud médica: **Apto / Apto en observación / Apto con limitaciones / No apto**
- Detalle de observaciones y recomendaciones/observaciones
- E. Datos del profesional: código médico, firma y sello
- F. Firma o huella del trabajador
- Leyendas de confidencialidad y referencia a formulario de evaluación ocupacional

**Salida:** PDF imprimible con encabezado institucional, numeración y código QR (opcional).

### 3.6 Agenda y Citas (opcional recomendado)
- Agenda diaria/semanal/mensual.
- Citas por profesional, consultorio, empresa.
- Recordatorios por email/WhatsApp (opcional).
- Estados: pendiente, confirmada, cancelada, asistió, no asistió.

### 3.7 Reportes y Panel de Control
- Dashboard: citas del día, evaluaciones por vencer, pendientes de firma, etc.
- Reportes ocupacionales:
  - aptitud por puesto/empresa
  - diagnósticos más frecuentes (CIE-10)
  - riesgos más reportados
  - tendencias por periodos
- Exportación: PDF/Excel.

### 3.8 Auditoría, Trazabilidad y Cumplimiento
- Bitácora de:
  - accesos
  - creación/edición de evaluaciones
  - emisión de certificados
  - descarga de documentos
- Configuración de retención y backups.

### 3.9 Portal del Paciente/Trabajador (opcional)
- Ver evaluaciones realizadas (según permisos).
- Descargar certificados autorizados.
- Solicitar citas.
- Aceptación de consentimientos (si se habilita).

### 3.10 Administración del Sistema
- Gestión de usuarios y roles.
- Parámetros generales (logos, firmas, numeraciones).
- Catálogos (CIIU/CIUO, riesgos, diagnósticos, exámenes).

---

## 4. Roles de Usuario (propuestos)
- **Administrador**: configuración total, usuarios, catálogos, auditoría.
- **Médico Ocupacional**: crea/edita evaluaciones, diagnósticos, aptitud, emite certificados.
- **Enfermería/Asistente**: signos vitales, carga de exámenes, apoyo en agenda.
- **Recepción**: agenda, registro demográfico, asignación de citas.
- **Paciente/Trabajador** (opcional): consulta de documentos autorizados.
- **Auditor/Calidad** (opcional): lectura de auditoría y reportes (sin editar clínica).

---

## 5. Requerimientos No Funcionales
- **Seguridad**: HTTPS, cifrado en tránsito, hash de contraseñas, RBAC, MFA (opcional).
- **Privacidad**: cifrado en reposo (dependiendo del hosting), control estricto de acceso, auditoría obligatoria.
- **Rendimiento**: paginación, índices, optimización de búsquedas.
- **Disponibilidad**: backups automáticos, monitoreo, recuperación ante fallos.
- **UX/UI**: interfaz responsive, formularios rápidos, autoguardado (opcional), validaciones.

---

## 6. Propuesta Técnica (Arquitectura)
- **Aplicación Web (Panel clínico/administrativo):** PHP 8.2+ con **Laravel** (MVC) y formularios guiados para el personal.
- **API (opcional):** servicios REST para integraciones (agenda, notificaciones, interoperabilidad).
- **Base de datos:** **PostgreSQL** (recomendado por integridad, auditoría y reportes) o **MySQL** (compatibilidad/hospedaje).
- **Almacenamiento de archivos:** S3 compatible (AWS S3/Wasabi/MinIO) o almacenamiento local controlado con permisos.
- **Generación de PDF:** certificados/evaluaciones con plantillas + firma/sello (PDF imprimible).
- **Seguridad y cumplimiento:** RBAC, cifrado, auditoría, políticas de retención y backups.

### Stack sugerido (PHP Profesional – Recomendado)
- **Backend:** PHP 8.2+ con **Laravel**
- **Frontend (panel interno):** Laravel Blade + TailwindCSS/Bootstrap *(o Inertia.js + Vue/React si se requiere SPA)*
- **Base de Datos:** **PostgreSQL** *(recomendado)* o **MySQL**
- **Autenticación:** Laravel Breeze/Jetstream + **RBAC** (Spatie Laravel Permission) + MFA (opcional)
- **Almacenamiento de archivos:** S3 compatible o almacenamiento local controlado
- **PDF:** Snappy (wkhtmltopdf) / Dompdf / TCPDF (según formato requerido)
- **Logs/Auditoría:** Monolog + tabla de auditoría (quién/cuándo/qué cambió)
- **Despliegue:** VPS/Cloud (Nginx + PHP-FPM) o cPanel; CI/CD (opcional)

---

## 7. Entregables
- Panel administrativo y clínico (EHR/EMR).
- Módulo de Evaluación Médica Ocupacional (HCU081/2025) digital.
- Módulo de Certificados (PDF) de evaluación ocupacional.
- Gestión de pacientes/trabajadores.
- Reportes básicos + exportación.
- Auditoría completa.
- Manual de usuario básico + capacitación (opcional).

---

## 8. Plan de Implementación (MVP → v1 → v2)

### MVP (funcional mínimo)
- Usuarios/roles (RBAC) + auditoría básica
- Pacientes/trabajadores
- Evaluación Médica Ocupacional digital (A–P)
- Generación de Certificado PDF
- Adjuntos de exámenes
- Reportes básicos

### v1 (profesional)
- Agenda y recordatorios
- Catálogos completos (CIE-10, CIUO/CIIU, riesgos)
- Dashboard avanzado
- Auditoría detallada y exportaciones PDF/Excel

### v2 (avanzado)
- Portal del paciente/trabajador
- Firma digital avanzada (si se requiere)
- Integraciones externas (email/WhatsApp, interoperabilidad)

---

## 9. Próximos Pasos
1. Validar alcance final (módulos obligatorios vs opcionales).
2. Confirmar catálogos y reglas (CIE-10, riesgos, CIUO/CIIU).
3. Wireframes de pantallas (evaluación, certificados, pacientes, reportes).
4. Implementación del MVP y pruebas con casos reales.
