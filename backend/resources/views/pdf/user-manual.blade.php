<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Manual de Usuario — SHCSO</title>
<style>
/* ── Variables ──────────────────────────────────────────── */
:root {
    --primary: #0f172a;
    --accent:  #3b82f6;
    --green:   #16a34a;
    --muted:   #64748b;
    --border:  #e2e8f0;
    --light:   #f8fafc;
    --warn:    #f59e0b;
    --danger:  #ef4444;
}

/* ── Base ───────────────────────────────────────────────── */
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 10px;
    color: #1e293b;
    background: #fff;
    line-height: 1.5;
}
h1 { font-size: 22px; color: var(--primary); }
h2 { font-size: 15px; color: var(--primary); border-bottom: 2px solid var(--accent); padding-bottom: 4px; margin: 20px 0 10px; }
h3 { font-size: 12px; color: var(--accent); margin: 14px 0 6px; }
h4 { font-size: 10.5px; color: var(--primary); margin: 10px 0 4px; }
p  { margin: 0 0 7px; }
ul, ol { margin: 4px 0 8px 18px; }
li { margin-bottom: 3px; }
strong { color: var(--primary); }

/* ── Cover ──────────────────────────────────────────────── */
.cover {
    background: var(--primary);
    color: #fff;
    padding: 50px 40px 40px;
    text-align: center;
    page-break-after: always;
}
.cover h1 { font-size: 28px; color: #fff; letter-spacing: .04em; margin-bottom: 8px; }
.cover .subtitle { font-size: 13px; color: #94a3b8; margin-bottom: 30px; }
.cover .version { font-size: 10px; color: #64748b; margin-top: 30px; }
.cover-logo { font-size: 54px; margin-bottom: 20px; }
.cover-line { border: 1px solid #334155; margin: 20px auto; width: 80px; }

/* ── Page container ─────────────────────────────────────── */
.page { padding: 20px 30px; }

/* ── TOC ────────────────────────────────────────────────── */
.toc { page-break-after: always; }
.toc-entry { display: flex; justify-content: space-between; border-bottom: 1px dotted var(--border); padding: 3px 0; }
.toc-entry.main { font-weight: bold; font-size: 11px; margin-top: 6px; }
.toc-entry.sub  { font-size: 10px; padding-left: 16px; color: var(--muted); }

/* ── Section boxes ──────────────────────────────────────── */
.section-block { page-break-inside: avoid; margin-bottom: 16px; }
.card {
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 12px 14px;
    margin: 8px 0;
    background: var(--light);
}
.note {
    background: #eff6ff;
    border-left: 3px solid var(--accent);
    padding: 8px 12px;
    border-radius: 0 6px 6px 0;
    margin: 8px 0;
    font-size: 9.5px;
}
.warn-box {
    background: #fffbeb;
    border-left: 3px solid var(--warn);
    padding: 8px 12px;
    border-radius: 0 6px 6px 0;
    margin: 8px 0;
    font-size: 9.5px;
}
.tip {
    background: #f0fdf4;
    border-left: 3px solid var(--green);
    padding: 8px 12px;
    border-radius: 0 6px 6px 0;
    margin: 8px 0;
    font-size: 9.5px;
}

/* ── Steps ──────────────────────────────────────────────── */
.steps { counter-reset: step; }
.step {
    counter-increment: step;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    margin-bottom: 8px;
}
.step-num {
    min-width: 22px;
    height: 22px;
    background: var(--accent);
    color: #fff;
    border-radius: 50%;
    font-weight: bold;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.step-body { padding-top: 3px; }

/* ── Roles table ─────────────────────────────────────────── */
.roles-table { width: 100%; border-collapse: collapse; font-size: 9.5px; margin: 8px 0; }
.roles-table th { background: var(--primary); color: #fff; padding: 5px 8px; text-align: left; }
.roles-table td { padding: 4px 8px; border-bottom: 1px solid var(--border); }
.roles-table tr:nth-child(even) td { background: var(--light); }

/* ── Module header ──────────────────────────────────────── */
.module-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
    color: #fff;
    padding: 14px 18px;
    border-radius: 6px;
    margin: 0 0 12px;
    page-break-after: avoid;
}
.module-header h2 { color: #fff; border: none; margin: 0; font-size: 14px; }
.module-header .mod-desc { font-size: 9px; color: #94a3b8; margin-top: 3px; }

/* ── Feature list ─────────────────────────────────────────── */
.feature-grid { display: table; width: 100%; border-spacing: 6px; }
.feature-row  { display: table-row; }
.feature-cell { display: table-cell; width: 50%; vertical-align: top; }
.feature-item { background: #fff; border: 1px solid var(--border); border-radius: 5px; padding: 8px 10px; margin-bottom: 6px; }
.feature-item .icon { font-size: 14px; }
.feature-item strong { font-size: 10px; }
.feature-item p { font-size: 9px; color: var(--muted); margin: 2px 0 0; }

/* ── Badge ───────────────────────────────────────────────── */
.badge {
    display: inline-block;
    font-size: 8.5px;
    padding: 2px 7px;
    border-radius: 99px;
    font-weight: bold;
}
.badge-blue   { background: #dbeafe; color: #1d4ed8; }
.badge-green  { background: #dcfce7; color: #15803d; }
.badge-yellow { background: #fef3c7; color: #92400e; }
.badge-red    { background: #fee2e2; color: #b91c1c; }
.badge-gray   { background: #f1f5f9; color: #475569; }

/* ── SOAP table ─────────────────────────────────────────── */
.soap-table { width: 100%; border-collapse: collapse; margin: 8px 0; }
.soap-table td { padding: 7px 10px; border: 1px solid var(--border); vertical-align: top; font-size: 9.5px; }
.soap-table .soap-letter { font-size: 20px; font-weight: bold; color: var(--accent); text-align: center; width: 30px; }
.soap-table .soap-title  { font-weight: bold; font-size: 10.5px; }

/* ── Footer ──────────────────────────────────────────────── */
.footer { text-align: center; font-size: 8.5px; color: var(--muted); border-top: 1px solid var(--border); padding-top: 6px; margin-top: 30px; }
</style>
</head>
<body>

{{-- ─────────────────────── PORTADA ──────────────────────────────── --}}
<div class="cover">
    <div class="cover-logo">🏥</div>
    <h1>SHCSO</h1>
    <div class="subtitle">Sistema de Historias Clínicas en Salud Ocupacional</div>
    <div class="cover-line"></div>
    <h2 style="font-size:18px;color:#e2e8f0;font-weight:400;margin:20px 0 8px;border:none;">Manual de Usuario</h2>
    <p style="color:#94a3b8;font-size:11px;">Guía completa para el uso del sistema</p>
    <div class="version">
        Versión 1.0 · {{ now()->format('F Y') }}<br>
        Módulos: Dashboard · Trabajadores · Operación · Empresas · Agenda · Configuración
    </div>
</div>

<div class="page">

{{-- ─────────────────────── ÍNDICE ──────────────────────────────── --}}
<div class="toc">
    <h2 style="margin-top:0;">Índice de contenido</h2>
    <div class="toc-entry main"><span>1. Introducción al sistema</span></div>
    <div class="toc-entry sub"><span>1.1 ¿Qué es SHCSO?</span></div>
    <div class="toc-entry sub"><span>1.2 Requisitos técnicos</span></div>
    <div class="toc-entry sub"><span>1.3 Acceso y roles de usuario</span></div>
    <div class="toc-entry main"><span>2. Primeros pasos</span></div>
    <div class="toc-entry sub"><span>2.1 Inicio de sesión</span></div>
    <div class="toc-entry sub"><span>2.2 Configuración inicial</span></div>
    <div class="toc-entry sub"><span>2.3 Navegación general</span></div>
    <div class="toc-entry main"><span>3. Módulo Dashboard</span></div>
    <div class="toc-entry sub"><span>3.1 Estadísticas generales</span></div>
    <div class="toc-entry sub"><span>3.2 Actividad mensual</span></div>
    <div class="toc-entry sub"><span>3.3 Alertas de certificados por vencer</span></div>
    <div class="toc-entry main"><span>4. Módulo Trabajadores</span></div>
    <div class="toc-entry sub"><span>4.1 Buscar y listar trabajadores</span></div>
    <div class="toc-entry sub"><span>4.2 Registrar un nuevo trabajador</span></div>
    <div class="toc-entry sub"><span>4.3 Historia clínica</span></div>
    <div class="toc-entry sub"><span>4.4 Evoluciones clínicas</span></div>
    <div class="toc-entry sub"><span>4.5 Vacunación laboral</span></div>
    <div class="toc-entry sub"><span>4.6 Accidentes laborales (AT-01)</span></div>
    <div class="toc-entry main"><span>5. Módulo Operación (Consulta Médica)</span></div>
    <div class="toc-entry sub"><span>5.1 Nueva evaluación ocupacional</span></div>
    <div class="toc-entry sub"><span>5.2 Método SOAP</span></div>
    <div class="toc-entry sub"><span>5.3 Diagnósticos CIE-10</span></div>
    <div class="toc-entry sub"><span>5.4 Prescripción médica y receta PDF</span></div>
    <div class="toc-entry sub"><span>5.5 Órdenes de exámenes (laboratorio/imagen)</span></div>
    <div class="toc-entry sub"><span>5.6 Certificado médico ocupacional</span></div>
    <div class="toc-entry sub"><span>5.7 Archivos DICOM y adjuntos</span></div>
    <div class="toc-entry main"><span>6. Módulo Empresas</span></div>
    <div class="toc-entry sub"><span>6.1 Portal por empresa</span></div>
    <div class="toc-entry sub"><span>6.2 Exportación Excel por empresa</span></div>
    <div class="toc-entry main"><span>7. Módulo Agenda de Citas</span></div>
    <div class="toc-entry sub"><span>7.1 Crear una cita</span></div>
    <div class="toc-entry sub"><span>7.2 Gestionar citas</span></div>
    <div class="toc-entry main"><span>8. Módulo Configuración</span></div>
    <div class="toc-entry sub"><span>8.1 Datos de la institución</span></div>
    <div class="toc-entry sub"><span>8.2 Firma, sello y logo</span></div>
    <div class="toc-entry main"><span>9. Gestión de Usuarios</span></div>
    <div class="toc-entry main"><span>10. Reportes y exportación</span></div>
    <div class="toc-entry main"><span>11. Catálogo CIE-10 y medicamentos</span></div>
    <div class="toc-entry main"><span>12. Preguntas frecuentes</span></div>
</div>

{{-- ─────────────────────── 1. INTRODUCCIÓN ───────────────────────── --}}
<h2>1. Introducción al sistema</h2>

<div class="section-block">
<h3>1.1 ¿Qué es SHCSO?</h3>
<p><strong>SHCSO</strong> (Sistema de Historias Clínicas en Salud Ocupacional) es una plataforma web diseñada para gestionar de forma integral la atención médica ocupacional de una organización. Permite registrar trabajadores, realizar evaluaciones médicas, emitir certificados, prescribir medicamentos, gestionar vacunación, reportar accidentes laborales y administrar citas médicas.</p>

<div class="feature-grid">
    <div class="feature-row">
        <div class="feature-cell">
            <div class="feature-item">
                <div class="icon">👷</div>
                <strong>Gestión de trabajadores</strong>
                <p>Registro completo con historia clínica, datos laborales y seguimiento.</p>
            </div>
            <div class="feature-item">
                <div class="icon">📋</div>
                <strong>Evaluaciones médicas</strong>
                <p>Ingreso, periódicas, reintegro y retiro con método SOAP.</p>
            </div>
            <div class="feature-item">
                <div class="icon">💊</div>
                <strong>Prescripción y receta</strong>
                <p>Emisión de recetas en PDF con QR de verificación.</p>
            </div>
        </div>
        <div class="feature-cell">
            <div class="feature-item">
                <div class="icon">📄</div>
                <strong>Certificados médicos</strong>
                <p>Generación de certificados de aptitud con firma y sello.</p>
            </div>
            <div class="feature-item">
                <div class="icon">📅</div>
                <strong>Agenda de citas</strong>
                <p>Programación y seguimiento de citas médicas.</p>
            </div>
            <div class="feature-item">
                <div class="icon">📊</div>
                <strong>Reportes y estadísticas</strong>
                <p>Dashboards por empresa y exportación a Excel.</p>
            </div>
        </div>
    </div>
</div>
</div>

<div class="section-block">
<h3>1.2 Requisitos técnicos</h3>
<ul>
    <li><strong>Navegador:</strong> Chrome 90+, Firefox 90+, Edge 90+ (recomendado Chrome)</li>
    <li><strong>Resolución:</strong> 1280 × 720 px mínimo</li>
    <li><strong>Conexión:</strong> El sistema corre en red local (intranet) o en servidor con acceso a internet</li>
    <li><strong>Sin instalación:</strong> Funciona directamente en el navegador</li>
</ul>
</div>

<div class="section-block">
<h3>1.3 Acceso y roles de usuario</h3>
<p>El sistema cuenta con 4 roles con diferentes niveles de acceso:</p>
<table class="roles-table">
    <thead>
        <tr><th>Rol</th><th>Descripción</th><th>Accesos principales</th></tr>
    </thead>
    <tbody>
        <tr>
            <td><span class="badge badge-red">ADMIN</span></td>
            <td>Administrador del sistema</td>
            <td>Todos los módulos, gestión de usuarios, configuración del sistema</td>
        </tr>
        <tr>
            <td><span class="badge badge-blue">MÉDICO OCUPACIONAL</span></td>
            <td>Profesional médico responsable</td>
            <td>Evaluaciones, prescripciones, certificados, diagnósticos, configuración</td>
        </tr>
        <tr>
            <td><span class="badge badge-green">ENFERMERÍA</span></td>
            <td>Personal de enfermería</td>
            <td>Registro de trabajadores, vacunación, exámenes, visualización</td>
        </tr>
        <tr>
            <td><span class="badge badge-gray">AUDITOR</span></td>
            <td>Auditor / consultor</td>
            <td>Visualización de reportes y estadísticas (solo lectura)</td>
        </tr>
    </tbody>
</table>
</div>

{{-- ─────────────────────── 2. PRIMEROS PASOS ─────────────────────── --}}
<h2>2. Primeros pasos</h2>

<div class="section-block">
<h3>2.1 Inicio de sesión</h3>
<div class="steps">
    <div class="step"><div class="step-num">1</div><div class="step-body">Abra el navegador y acceda a la dirección del sistema (p.ej: <strong>http://192.168.1.10:8000</strong> en red local)</div></div>
    <div class="step"><div class="step-num">2</div><div class="step-body">Se presentará la pantalla de inicio de sesión. Ingrese su <strong>correo electrónico</strong> y <strong>contraseña</strong></div></div>
    <div class="step"><div class="step-num">3</div><div class="step-body">Haga clic en <strong>"Iniciar sesión"</strong>. El sistema verificará sus credenciales y lo dirigirá al dashboard principal</div></div>
</div>
<div class="note">💡 Si es la primera vez que accede al sistema, el administrador habrá creado su cuenta previamente con un correo y contraseña temporal. Se recomienda cambiar la contraseña en <strong>"Mi perfil"</strong>.</div>
</div>

<div class="section-block">
<h3>2.2 Configuración inicial (solo Administrador)</h3>
<p>Antes de comenzar a registrar datos, el administrador debe configurar la información institucional:</p>
<ol>
    <li>Ir al tab <strong>⚙️ Configuración</strong> en el menú principal</li>
    <li>Completar el formulario de <strong>Datos de la Institución</strong> (nombre, ciudad, subtítulo)</li>
    <li>Completar los datos del <strong>Médico responsable</strong> (nombre, código profesional, cargo)</li>
    <li>Subir el <strong>Logo institucional</strong>, <strong>Firma del médico</strong> y <strong>Sello</strong> en la sección de imágenes</li>
    <li>Guardar los cambios con el botón <strong>"💾 Guardar configuración"</strong></li>
</ol>
<div class="tip">✅ Una vez configurados estos datos, aparecerán automáticamente en todos los PDFs: recetas, certificados y reportes de accidentes.</div>
</div>

<div class="section-block">
<h3>2.3 Navegación general</h3>
<p>El sistema cuenta con un menú de pestañas en la parte superior:</p>
<table class="roles-table">
    <thead><tr><th>Tab</th><th>Función</th></tr></thead>
    <tbody>
        <tr><td><strong>Dashboard</strong></td><td>Estadísticas generales, actividad mensual, alertas</td></tr>
        <tr><td><strong>Trabajadores</strong></td><td>Registro y consulta de trabajadores, historia clínica</td></tr>
        <tr><td><strong>Operación</strong></td><td>Nueva consulta/evaluación médica</td></tr>
        <tr><td><strong>🏢 Empresas</strong></td><td>Dashboard de aptitud y estadísticas por empresa</td></tr>
        <tr><td><strong>📅 Agenda</strong></td><td>Programación y gestión de citas médicas</td></tr>
        <tr><td><strong>Usuarios</strong></td><td>Gestión de cuentas de usuario (solo ADMIN)</td></tr>
        <tr><td><strong>⚙️ Configuración</strong></td><td>Datos institucionales, firma, sello y logo</td></tr>
    </tbody>
</table>
</div>

{{-- ─────────────────────── 3. DASHBOARD ─────────────────────────── --}}
<div class="module-header">
    <h2>3. Módulo Dashboard</h2>
    <div class="mod-desc">Visión general del estado del sistema y actividad reciente</div>
</div>

<div class="section-block">
<h3>3.1 Estadísticas generales</h3>
<p>Al ingresar al sistema, el Dashboard muestra 4 indicadores clave en tarjetas:</p>
<ul>
    <li><strong>Total trabajadores</strong> — número total de trabajadores registrados en el sistema</li>
    <li><strong>Evaluaciones</strong> — total de evaluaciones médicas realizadas</li>
    <li><strong>Certificados emitidos</strong> — certificados de aptitud generados</li>
    <li><strong>Sin certificado</strong> — evaluaciones que aún no tienen certificado emitido</li>
</ul>
</div>

<div class="section-block">
<h3>3.2 Actividad mensual y aptitud por empresa</h3>
<p>El dashboard incluye dos secciones de análisis:</p>
<ul>
    <li><strong>Gráfico de barras mensual</strong> — muestra el número de evaluaciones por mes en los últimos 6 meses</li>
    <li><strong>Tabla de aptitud por empresa</strong> — resumen de evaluaciones por empresa con desglose APTO / NO APTO</li>
</ul>
</div>

<div class="section-block">
<h3>3.3 Alertas de certificados por vencer</h3>
<p>El sistema revisa automáticamente los certificados que vencen en los próximos <strong>30 días</strong> y muestra una alerta en el Dashboard. Cada alerta indica:</p>
<ul>
    <li>Nombre del trabajador</li>
    <li>Empresa</li>
    <li>Fecha de vencimiento del certificado</li>
</ul>
<div class="warn-box">⚠️ Los certificados vencidos no son válidos para presentar ante auditorías del Ministerio de Trabajo o el IESS. Renueve con anticipación.</div>
</div>

{{-- ─────────────────────── 4. TRABAJADORES ─────────────────────── --}}
<div class="module-header">
    <h2>4. Módulo Trabajadores</h2>
    <div class="mod-desc">Registro, historia clínica, evoluciones, vacunación y accidentes laborales</div>
</div>

<div class="section-block">
<h3>4.1 Buscar y listar trabajadores</h3>
<p>En la pestaña <strong>Trabajadores → 1. Trabajadores recientes</strong> encontrará la lista de trabajadores ordenados por los más recientes. Para buscar un trabajador específico:</p>
<div class="steps">
    <div class="step"><div class="step-num">1</div><div class="step-body">Ingrese el nombre o número de cédula en el campo de búsqueda</div></div>
    <div class="step"><div class="step-num">2</div><div class="step-body">Haga clic en el botón 🔍 o presione Enter</div></div>
    <div class="step"><div class="step-num">3</div><div class="step-body">Haga clic en <strong>"Ver historial"</strong> para acceder a la historia clínica completa del trabajador</div></div>
</div>
</div>

<div class="section-block">
<h3>4.2 Registrar un nuevo trabajador</h3>
<p>En la pestaña <strong>Trabajadores → 2. Nuevo trabajador</strong>:</p>
<ul>
    <li><strong>Datos obligatorios:</strong> Tipo y número de documento, primer apellido, primer nombre</li>
    <li><strong>Datos opcionales:</strong> Empresa, cargo, email, teléfono, fecha de nacimiento, sexo, grupo sanguíneo, lateralidad</li>
    <li><strong>Condiciones especiales:</strong> Embarazo, discapacidad, enfermedad catastrófica, adulto mayor</li>
</ul>
<div class="note">💡 El <strong>Número de historia</strong> y el <strong>Número de expediente</strong> se asignan automáticamente, pero pueden ser editados manualmente si necesita seguir una numeración específica.</div>
</div>

<div class="section-block">
<h3>4.3 Historia clínica</h3>
<p>Al hacer clic en <strong>"Ver historial"</strong> de un trabajador, se abre la historia clínica organizada en pestañas:</p>
<table class="roles-table">
    <thead><tr><th>Pestaña</th><th>Contenido</th></tr></thead>
    <tbody>
        <tr><td><strong>Historia</strong></td><td>Antecedentes personales y familiares, datos del puesto de trabajo</td></tr>
        <tr><td><strong>Evaluaciones</strong></td><td>Lista de evaluaciones médicas con botón "Imprimir receta" y "Ver certificado"</td></tr>
        <tr><td><strong>Evoluciones</strong></td><td>Notas de seguimiento clínico (SOAP)</td></tr>
        <tr><td><strong>Prescripciones</strong></td><td>Historial de medicamentos prescritos</td></tr>
        <tr><td><strong>Exámenes</strong></td><td>Órdenes de laboratorio, imágenes médicas y resultados</td></tr>
        <tr><td><strong>Certificados</strong></td><td>Certificados de aptitud emitidos</td></tr>
        <tr><td><strong>Vacunación</strong></td><td>Registro del esquema de vacunación laboral</td></tr>
        <tr><td><strong>Accidentes</strong></td><td>Accidentes laborales reportados (AT-01)</td></tr>
    </tbody>
</table>
</div>

<div class="section-block">
<h3>4.4 Evoluciones clínicas</h3>
<p>Las evoluciones son notas de seguimiento entre consultas formales. Se registran usando el <strong>método SOAP</strong>:</p>
<table class="soap-table">
    <tr>
        <td class="soap-letter">S</td>
        <td><span class="soap-title">Subjetivo</span><br>Lo que el paciente refiere: síntomas, motivo de consulta, tiempo de evolución.</td>
    </tr>
    <tr>
        <td class="soap-letter">O</td>
        <td><span class="soap-title">Objetivo</span><br>Datos medibles: signos vitales, hallazgos del examen físico.</td>
    </tr>
    <tr>
        <td class="soap-letter">A</td>
        <td><span class="soap-title">Análisis / Diagnóstico</span><br>Impresión diagnóstica o diagnóstico definitivo con código CIE-10.</td>
    </tr>
    <tr>
        <td class="soap-letter">P</td>
        <td><span class="soap-title">Plan</span><br>Tratamiento, medicamentos, indicaciones, seguimiento programado.</td>
    </tr>
</table>
</div>

<div class="section-block">
<h3>4.5 Vacunación laboral</h3>
<p>En la pestaña <strong>Vacunación</strong> dentro de la historia clínica:</p>
<ul>
    <li>Seleccione el tipo de vacuna del listado (hepatitis B, tétanos, influenza, etc.) o ingrese una personalizada</li>
    <li>Ingrese el lote, fecha de aplicación, dosis y número de dosis del esquema</li>
    <li>El sistema lleva control automático del número de dosis</li>
</ul>
</div>

<div class="section-block">
<h3>4.6 Accidentes laborales (AT-01)</h3>
<p>En la pestaña <strong>Accidentes</strong>:</p>
<ul>
    <li>Complete el formulario con fecha, tipo de accidente, severidad, descripción</li>
    <li>Para accidentes <strong>reportados al IESS</strong>, active el interruptor y complete el número AT-01</li>
    <li>Use el botón <strong>"🖨️ PDF AT-01"</strong> para generar el formulario oficial imprimible</li>
</ul>
<div class="note">💡 El formulario AT-01 incluye automáticamente los datos del trabajador, empresa, médico responsable y firma.</div>
</div>

{{-- ─────────────────────── 5. OPERACIÓN ─────────────────────────── --}}
<div class="module-header">
    <h2>5. Módulo Operación (Consulta Médica)</h2>
    <div class="mod-desc">Evaluaciones médicas, diagnósticos, prescripciones, certificados y archivos</div>
</div>

<div class="section-block">
<h3>5.1 Nueva evaluación ocupacional</h3>
<p>En la pestaña <strong>Operación → 1. Consulta</strong>:</p>
<div class="steps">
    <div class="step"><div class="step-num">1</div><div class="step-body">Busque al trabajador por nombre o cédula en el campo de búsqueda</div></div>
    <div class="step"><div class="step-num">2</div><div class="step-body">Seleccione el <strong>tipo de evaluación</strong>: Ingreso, Periódica, Reintegro o Retiro</div></div>
    <div class="step"><div class="step-num">3</div><div class="step-body">Complete los campos SOAP: motivo de consulta, signos vitales, examen físico, diagnóstico y plan</div></div>
    <div class="step"><div class="step-num">4</div><div class="step-body">Agregue los <strong>diagnósticos CIE-10</strong> usando el buscador</div></div>
    <div class="step"><div class="step-num">5</div><div class="step-body">Defina la <strong>aptitud médica</strong>: APTO, APTO c/ observación, APTO c/ limitaciones, o NO APTO</div></div>
    <div class="step"><div class="step-num">6</div><div class="step-body">Haga clic en <strong>"Registrar evaluación"</strong></div></div>
</div>
</div>

<div class="section-block">
<h3>5.2 Aptitud médica — valores y colores</h3>
<table class="roles-table">
    <thead><tr><th>Aptitud</th><th>Significado</th><th>Indicador</th></tr></thead>
    <tbody>
        <tr><td><span class="badge badge-green">APTO</span></td><td>El trabajador puede realizar sus funciones sin restricciones</td><td>🟢 Verde</td></tr>
        <tr><td><span class="badge badge-yellow">APTO c/ observación</span></td><td>Puede trabajar pero requiere seguimiento médico</td><td>🟡 Amarillo</td></tr>
        <tr><td><span class="badge badge-red" style="background:#ffe9d8;color:#9a3412;">APTO c/ limitaciones</span></td><td>Puede trabajar con restricciones específicas</td><td>🟠 Naranja</td></tr>
        <tr><td><span class="badge badge-red">NO APTO</span></td><td>No puede realizar sus funciones actuales</td><td>🔴 Rojo</td></tr>
    </tbody>
</table>
</div>

<div class="section-block">
<h3>5.3 Diagnósticos CIE-10</h3>
<p>El sistema incluye el <strong>Catálogo CIE-10 completo</strong> cargado localmente. Para agregar un diagnóstico:</p>
<div class="steps">
    <div class="step"><div class="step-num">1</div><div class="step-body">En la sección de diagnósticos de la evaluación, escriba el <strong>código</strong> (p.ej: M54.5) o parte de la <strong>descripción</strong> (p.ej: "lumbar")</div></div>
    <div class="step"><div class="step-num">2</div><div class="step-body">Aparecerá una lista de sugerencias. Seleccione el diagnóstico correcto</div></div>
    <div class="step"><div class="step-num">3</div><div class="step-body">Puede agregar <strong>múltiples diagnósticos</strong> por evaluación</div></div>
</div>
<div class="tip">✅ La base de datos CIE-10 puede actualizarse desde ⚙️ Configuración usando la función de importación CSV o desde la API de la OMS.</div>
</div>

<div class="section-block">
<h3>5.4 Prescripción médica y receta PDF</h3>
<p>En la pestaña <strong>Operación → 4. Prescripciones</strong>:</p>
<ul>
    <li>Agregue medicamentos usando el campo con <strong>autocomplete</strong> (busca por nombre genérico o comercial)</li>
    <li>Complete: dosis, frecuencia, duración e indicaciones adicionales</li>
    <li>Una vez guardada la evaluación, use el botón <strong>"🖨️ Receta"</strong> para generar el PDF</li>
</ul>
<p>El PDF de la receta incluye:</p>
<ul>
    <li>Logo y datos institucionales configurados</li>
    <li>Datos del paciente (nombre, cédula, empresa, diagnóstico)</li>
    <li>Tabla de medicamentos con dosis y frecuencia</li>
    <li>Firma y sello del médico</li>
    <li><strong>Código QR</strong> de verificación de autenticidad</li>
</ul>
</div>

<div class="section-block">
<h3>5.5 Órdenes de exámenes</h3>
<p>En la pestaña <strong>Operación → 5. Exámenes médicos</strong>, puede:</p>
<ul>
    <li>Crear <strong>órdenes de laboratorio</strong>: hemograma, bioquímica, orina, etc.</li>
    <li>Crear <strong>órdenes de imagen</strong>: radiografías, ecografías, resonancias</li>
    <li>Subir <strong>resultados de exámenes</strong> (PDF, imágenes)</li>
    <li>Visualizar <strong>archivos DICOM</strong> (radiografías digitales) directamente en el navegador</li>
    <li>Generar la orden en <strong>PDF</strong> para entregar al laboratorio o centro de imagen</li>
</ul>
</div>

<div class="section-block">
<h3>5.6 Certificado médico ocupacional</h3>
<p>En la pestaña <strong>Operación → 6. Certificados</strong>:</p>
<div class="steps">
    <div class="step"><div class="step-num">1</div><div class="step-body">Seleccione la evaluación base del certificado</div></div>
    <div class="step"><div class="step-num">2</div><div class="step-body">Verifique o ajuste la <strong>aptitud médica</strong>, observaciones y restricciones</div></div>
    <div class="step"><div class="step-num">3</div><div class="step-body">Haga clic en <strong>"Emitir certificado"</strong>. El sistema generará un código único (p.ej: CERT-2024-001234)</div></div>
    <div class="step"><div class="step-num">4</div><div class="step-body">Descargue el certificado en PDF con firma, sello y datos institucionales</div></div>
</div>
<div class="note">💡 El sistema registra automáticamente la fecha de vencimiento del certificado (1 año desde la emisión) y genera una alerta antes del vencimiento.</div>
</div>

{{-- ─────────────────────── 6. EMPRESAS ──────────────────────────── --}}
<div class="module-header">
    <h2>6. Módulo Empresas</h2>
    <div class="mod-desc">Dashboard de aptitud laboral y estadísticas por empresa</div>
</div>

<div class="section-block">
<h3>6.1 Portal por empresa</h3>
<p>El módulo <strong>🏢 Empresas</strong> permite visualizar el estado de salud ocupacional de cada empresa:</p>
<ul>
    <li>Vista de <strong>cards</strong> con barra de color proporcional a la distribución de aptitud</li>
    <li>Filtros por rango de fechas</li>
    <li>Búsqueda instantánea por nombre de empresa</li>
</ul>
<p>Al hacer clic en una empresa accede al <strong>detalle</strong> que muestra:</p>
<ul>
    <li>Total de trabajadores, evaluaciones, certificados y accidentes</li>
    <li>Alertas de certificados por vencer</li>
    <li>Distribución de aptitud con barras proporcionales</li>
    <li>Tendencia de actividad mensual (últimos 6 meses)</li>
    <li>Tabla de evaluaciones recientes</li>
</ul>
</div>

<div class="section-block">
<h3>6.2 Exportación Excel por empresa</h3>
<p>Desde el detalle de empresa, use el botón <strong>"📥 Excel"</strong> para descargar un archivo con todas las evaluaciones de esa empresa en el período seleccionado. El Excel incluye: trabajador, documento, tipo de evaluación, fecha, aptitud y médico.</p>
</div>

{{-- ─────────────────────── 7. AGENDA ─────────────────────────────── --}}
<div class="module-header">
    <h2>7. Módulo Agenda de Citas</h2>
    <div class="mod-desc">Programación y seguimiento de citas médicas ocupacionales</div>
</div>

<div class="section-block">
<h3>7.1 Crear una cita</h3>
<div class="steps">
    <div class="step"><div class="step-num">1</div><div class="step-body">Vaya a la pestaña <strong>📅 Agenda</strong></div></div>
    <div class="step"><div class="step-num">2</div><div class="step-body">Haga clic en el botón <strong>"+ Nueva"</strong></div></div>
    <div class="step"><div class="step-num">3</div><div class="step-body">Busque y seleccione el trabajador con el buscador (nombre o cédula)</div></div>
    <div class="step"><div class="step-num">4</div><div class="step-body">Seleccione la fecha, hora y tipo de cita</div></div>
    <div class="step"><div class="step-num">5</div><div class="step-body">Ingrese el motivo de la cita y notas adicionales si es necesario</div></div>
    <div class="step"><div class="step-num">6</div><div class="step-body">Haga clic en <strong>"💾 Guardar cita"</strong></div></div>
</div>
</div>

<div class="section-block">
<h3>7.2 Tipos y estados de cita</h3>
<table class="roles-table">
    <thead><tr><th>Tipo</th><th>Estado</th><th>Descripción</th></tr></thead>
    <tbody>
        <tr><td>Consulta general</td><td><span class="badge badge-blue">Programada</span></td><td>La cita ha sido registrada</td></tr>
        <tr><td>Examen preocupacional</td><td><span class="badge badge-green">Confirmada</span></td><td>El paciente ha confirmado asistencia</td></tr>
        <tr><td>Examen periódico</td><td><span class="badge badge-blue" style="background:#ddd1ff;color:#5b21b6;">Atendida</span></td><td>La cita fue realizada</td></tr>
        <tr><td>Seguimiento</td><td><span class="badge badge-red">Cancelada</span></td><td>La cita fue anulada</td></tr>
        <tr><td>Vacunación</td><td><span class="badge badge-yellow">No asistió</span></td><td>El paciente no se presentó</td></tr>
    </tbody>
</table>
</div>

{{-- ─────────────────────── 8. CONFIGURACIÓN ─────────────────────── --}}
<div class="module-header">
    <h2>8. Módulo Configuración</h2>
    <div class="mod-desc">Personalización de la institución y datos del médico para los PDFs</div>
</div>

<div class="section-block">
<h3>8.1 Datos de la institución</h3>
<p>Acceda a <strong>⚙️ Configuración</strong> para completar:</p>
<ul>
    <li><strong>Nombre de la institución</strong> — aparece en el encabezado de todos los PDFs</li>
    <li><strong>Subtítulo / especialidad</strong> — línea debajo del nombre</li>
    <li><strong>Ciudad</strong> — aparece en el encabezado junto con la fecha</li>
    <li><strong>Nota al pie</strong> — texto que aparece al final de los PDFs</li>
</ul>
</div>

<div class="section-block">
<h3>8.2 Médico responsable</h3>
<ul>
    <li><strong>Nombre del médico</strong> — aparece en la línea de firma en los PDFs</li>
    <li><strong>Título / cargo</strong> — aparece debajo del nombre en la firma</li>
    <li><strong>Código profesional</strong> — número de registro médico</li>
</ul>
</div>

<div class="section-block">
<h3>8.3 Logo, firma y sello</h3>
<p>En la sección de imágenes, puede subir tres archivos (JPG, PNG, SVG — máx. 2 MB):</p>
<ul>
    <li><strong>🖼 Logo institucional</strong> — aparece en la esquina superior izquierda de todos los PDFs</li>
    <li><strong>✍️ Firma del médico</strong> — imagen escaneada de la firma, aparece sobre la línea de firma</li>
    <li><strong>🔏 Sello del médico</strong> — imagen del sello profesional</li>
</ul>
<div class="tip">✅ Use imágenes con fondo transparente (PNG) para mejores resultados en los PDFs.</div>
</div>

{{-- ─────────────────────── 9. USUARIOS ──────────────────────────── --}}
<div class="module-header">
    <h2>9. Gestión de Usuarios</h2>
    <div class="mod-desc">Crear y administrar cuentas de acceso al sistema (solo ADMIN)</div>
</div>

<div class="section-block">
<p>En la pestaña <strong>Usuarios</strong> (visible solo para ADMIN):</p>
<ul>
    <li><strong>Crear usuario</strong>: ingrese nombre, correo, contraseña y asigne un rol</li>
    <li><strong>Editar usuario</strong>: modifique nombre, correo, rol y código profesional</li>
    <li><strong>Desactivar / reactivar</strong>: un usuario desactivado no puede iniciar sesión</li>
</ul>
<div class="warn-box">⚠️ El rol <strong>MÉDICO OCUPACIONAL</strong> otorga acceso completo a funciones clínicas. Asígnelo únicamente al profesional médico responsable.</div>
</div>

{{-- ─────────────────────── 10. REPORTES ─────────────────────────── --}}
<div class="module-header">
    <h2>10. Reportes y exportación Excel</h2>
    <div class="mod-desc">Generación de reportes en Excel para auditorías y seguimiento</div>
</div>

<div class="section-block">
<p>Desde la pestaña <strong>Operación → Evaluaciones</strong> puede exportar en Excel:</p>
<table class="roles-table">
    <thead><tr><th>Tipo de reporte</th><th>Contenido</th></tr></thead>
    <tbody>
        <tr><td>Trabajadores</td><td>Lista completa con empresa, cargo, datos personales</td></tr>
        <tr><td>Evaluaciones</td><td>Todas las evaluaciones con aptitud, fecha y médico</td></tr>
        <tr><td>Certificados</td><td>Certificados emitidos con fechas de emisión y vencimiento</td></tr>
        <tr><td>Accidentes</td><td>Accidentes laborales con tipo, severidad y estado IESS</td></tr>
    </tbody>
</table>
<p style="margin-top:8px;">También puede exportar por empresa desde el módulo <strong>🏢 Empresas → Detalle → 📥 Excel</strong>.</p>
</div>

{{-- ─────────────────────── 11. CIE-10 ──────────────────────────── --}}
<div class="module-header">
    <h2>11. Catálogo CIE-10 y medicamentos</h2>
    <div class="mod-desc">Base de datos de diagnósticos y cuadro básico de medicamentos</div>
</div>

<div class="section-block">
<h3>Catálogo CIE-10</h3>
<p>El sistema incluye el catálogo completo CIE-10 cargado en la base de datos local. Para mantenerlo actualizado:</p>
<ul>
    <li><strong>Importación CSV</strong>: El administrador puede cargar un archivo CSV con nuevos códigos</li>
    <li><strong>API OMS</strong>: El sistema puede consultar la API oficial de la Organización Mundial de la Salud para obtener la versión más reciente (requiere conexión a internet y token de la API)</li>
</ul>
</div>

<div class="section-block">
<h3>Cuadro básico de medicamentos</h3>
<p>El sistema incluye un catálogo de medicamentos del Cuadro Nacional de Medicamentos Básicos. Al escribir el nombre de un medicamento en el formulario de prescripción, el sistema sugiere automáticamente opciones con el nombre genérico y comercial.</p>
<div class="tip">✅ El administrador puede agregar medicamentos personalizados desde la gestión del catálogo para incluir fármacos específicos de uso en la institución.</div>
</div>

{{-- ─────────────────────── 12. FAQ ──────────────────────────────── --}}
<div class="module-header">
    <h2>12. Preguntas frecuentes</h2>
    <div class="mod-desc">Respuestas a dudas comunes de los usuarios</div>
</div>

<div class="section-block">
<h4>¿Puedo acceder al sistema desde otro dispositivo en la red?</h4>
<p>Sí. El sistema está disponible en toda la red local. Pida al administrador de TI la dirección IP del servidor (p.ej: http://192.168.1.10:8000) e ingrésela en el navegador.</p>

<h4>¿Cómo recupero mi contraseña?</h4>
<p>Contacte al administrador del sistema para que restablezca su contraseña. Luego, cambie la contraseña temporal desde <strong>"Mi perfil" → "Nueva contraseña"</strong>.</p>

<h4>¿Los PDFs guardan la firma automáticamente?</h4>
<p>Sí, siempre que el administrador haya subido la imagen de la firma en <strong>⚙️ Configuración → Firma del médico</strong>. Si no hay imagen, aparecerá la línea de firma en blanco.</p>

<h4>¿Puedo imprimir el certificado en cualquier momento?</h4>
<p>Sí. Desde <strong>Operación → Certificados</strong> puede ver todos los certificados emitidos y descargarlos en PDF cuando lo necesite.</p>

<h4>¿Qué ocurre si un trabajador cambia de empresa?</h4>
<p>Edite el perfil del trabajador en <strong>Trabajadores → 3. Editar trabajador</strong> y cambie el campo <strong>Empresa</strong>. El historial anterior se conserva completo.</p>

<h4>¿El sistema funciona sin internet?</h4>
<p>Sí. El sistema funciona completamente en red local (intranet) sin necesidad de internet. Solo requiere conexión a internet para: actualizaciones del catálogo CIE-10 vía API y para el código QR de verificación en las recetas (servicio externo).</p>

<h4>¿Cómo reporto un accidente laboral al IESS?</h4>
<p>Registre el accidente en <strong>Historia Clínica → Accidentes</strong>, active "Reportado al IESS" e ingrese el número AT-01 proporcionado por el IESS. Luego use "🖨️ PDF AT-01" para imprimir el formulario.</p>
</div>

<div class="footer">
    SHCSO — Sistema de Historias Clínicas en Salud Ocupacional | Manual de Usuario v1.0 | {{ now()->format('Y') }}<br>
    Para soporte técnico, contacte al administrador del sistema.
</div>

</div>{{-- .page --}}
</body>
</html>
