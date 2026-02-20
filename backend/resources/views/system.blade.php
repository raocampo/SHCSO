<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SHCSO Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --ink:#102127; --muted:#4b5f66; --paper:#f4f4f1; --card:#fff; --line:#d3ddd8; --ok:#1f9c6d; --error:#b9375d; --accent:#0f8b8d; --warn:#f2a65a; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:"Space Grotesk",sans-serif; color:var(--ink); background:radial-gradient(circle at 15% 15%, #d8ebe9 0, transparent 42%),radial-gradient(circle at 85% 8%, #f8dfc2 0, transparent 28%),linear-gradient(150deg,#f4f2ee 0%,#ebf1ee 100%); }
        .shell { max-width:1320px; margin:0 auto; padding:20px 14px 40px; }
        .top { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:10px; }
        .title { margin:0; font-size:clamp(1.2rem,2.5vw,2rem); text-transform:uppercase; letter-spacing:.03em; }
        .subtitle { margin:4px 0 0; color:var(--muted); }
        .actions { display:flex; gap:8px; }
        .btn { border:1px solid var(--line); background:#fff; color:var(--ink); border-radius:10px; padding:10px 13px; cursor:pointer; font-weight:700; }
        .btn:disabled { opacity:.55; cursor:not-allowed; }
        .btn.primary { background:var(--ink); border-color:var(--ink); color:#fff; }
        .btn.accent { background:var(--accent); border-color:var(--accent); color:#fff; }
        .btn.warn { background:var(--warn); border-color:var(--warn); color:#2a1b08; }
        .tabs { display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px; }
        .tab { border:1px solid var(--line); background:#fff; color:var(--ink); border-radius:999px; padding:8px 12px; cursor:pointer; font-weight:700; }
        .tab.active { background:var(--ink); border-color:var(--ink); color:#fff; }
        .workerFlow { display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px; }
        .workerFlowTab { border:1px solid var(--line); background:#fff; color:var(--ink); border-radius:999px; padding:8px 12px; cursor:pointer; font-weight:700; }
        .workerFlowTab.active { background:var(--accent); border-color:var(--accent); color:#fff; }
        .card { background:var(--card); border:1px solid var(--line); border-radius:14px; padding:14px; box-shadow:0 8px 18px rgba(16,33,39,.05); }
        .status { min-height:40px; display:flex; align-items:center; border:1px solid var(--line); border-radius:10px; padding:8px 12px; margin-bottom:12px; background:#fff; }
        .status.ok { border-color:#9cd8be; color:#14573b; background:#ecf9f2; }
        .status.error { border-color:#efb2c4; color:#7b1e3a; background:#fff0f5; }
        .hidden { display:none !important; }
        .login { max-width:540px; margin:12px auto 0; }
        .field { margin-bottom:9px; }
        .field label { display:block; margin-bottom:5px; font-size:.82rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; }
        input, select, textarea { width:100%; border:1px solid var(--line); border-radius:10px; padding:10px 12px; font-family:"Space Grotesk",sans-serif; }
        textarea { min-height:70px; resize:vertical; }
        .workerFormGrid { display:grid; grid-template-columns:repeat(12,minmax(0,1fr)); gap:12px; }
        .workerFormGrid .field { margin-bottom:0; }
        .workerFormGrid .span-1 { grid-column:span 1; }
        .workerFormGrid .span-2 { grid-column:span 2; }
        .workerFormGrid .span-3 { grid-column:span 3; }
        .workerFormGrid .span-4 { grid-column:span 4; }
        .workerFormGrid .span-6 { grid-column:span 6; }
        .workerFormGrid .span-12 { grid-column:span 12; }
        .hint { font-family:"IBM Plex Mono",monospace; font-size:.75rem; color:var(--muted); }
        .stats { display:grid; grid-template-columns:repeat(4,minmax(120px,1fr)); gap:10px; margin-bottom:12px; }
        .stat { border:1px solid #c5d8d3; border-radius:12px; padding:10px; background:linear-gradient(160deg,#fff,#f0f7f4); }
        .stat h4 { margin:0; font-size:.75rem; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
        .stat p { margin:8px 0 0; font-size:1.35rem; font-weight:700; }
        .grid2 { display:grid; grid-template-columns:1.2fr 1fr; gap:10px; margin-bottom:12px; }
        .grid3 { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; margin-bottom:12px; }
        .section { margin:0 0 10px; font-size:1rem; text-transform:uppercase; letter-spacing:.04em; }
        .bars { display:flex; align-items:flex-end; gap:8px; min-height:210px; }
        .barItem { flex:1; min-width:22px; }
        .barCol { width:100%; background:linear-gradient(180deg,#0f8b8d,#7cc6b5); border-radius:8px 8px 0 0; min-height:2px; }
        .barTag { margin-top:5px; text-align:center; font-size:.72rem; color:var(--muted); }
        .tableWrap { overflow:auto; }
        table { width:100%; border-collapse:collapse; font-size:.86rem; }
        th, td { border-bottom:1px solid #e4ece9; padding:7px 6px; text-align:left; vertical-align:top; }
        th { font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); background:#f8fbfa; position:sticky; top:0; }
        .pill { display:inline-block; background:#d9f1ec; color:#115f61; border-radius:999px; padding:2px 8px; font-size:.72rem; font-weight:700; }
        .rowActions { display:flex; gap:6px; flex-wrap:wrap; }
        .btn.small { padding:6px 8px; border-radius:8px; font-size:.78rem; }
        .workerStepPanel { margin-bottom:12px; }
        .workerStepPanel[data-worker-panel="manage"], .workerStepPanel[data-worker-panel="recent"] { grid-column:1 / -1; }
        .workerManagePanel { padding:18px 20px; }
        .workerManageToolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; }
        .workerManageToolbar .hint { font-size:.8rem; }
        .workerDetail { border:1px dashed #cfe0da; border-radius:12px; background:#f8fcfa; padding:10px 12px; }
        .workerManagePanel .tableWrap { border:1px solid #dce8e4; border-radius:12px; background:#fff; padding:4px; }
        .workerManagePanel table { min-width:900px; }
        .workerFormActions { margin-top:12px; }
        .workerManagePanel .rowActions .btn.small { min-width:80px; }
        .meta { margin:4px 0; color:var(--muted); font-size:.86rem; }
        .meta strong { color:var(--ink); }
        .historyList { display:grid; gap:8px; }
        .historyCard { border:1px solid #e4ece9; border-radius:10px; padding:10px; background:#fbfefd; }
        .chips { display:flex; gap:6px; flex-wrap:wrap; margin-top:6px; }
        .chip { display:inline-block; border:1px solid #cfe0da; border-radius:999px; padding:2px 8px; font-size:.72rem; background:#f0f7f4; color:#115f61; }
        .sectionBadge { font-family:"IBM Plex Mono",monospace; font-size:.66rem; background:#e9f5f0; border:1px solid #b6d9cd; color:#115f61; border-radius:999px; padding:2px 8px; vertical-align:middle; margin-left:6px; }
        .operationPulse { margin-bottom:12px; }
        .operationKpi { border-color:#b6d9cd; background:linear-gradient(145deg,#ffffff,#edf7f3); }
        .operationKpiGrid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:8px; }
        .operationKpiItem { border:1px solid #c8ddd5; border-radius:12px; padding:9px 10px; background:#fff; }
        .operationKpiItem p { margin:0; color:var(--muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; }
        .operationKpiItem strong { display:block; margin-top:7px; font-size:1.25rem; line-height:1; }
        .operationCard { border-color:#c3ddd4; background:linear-gradient(160deg,#ffffff,#f4fbf8); }
        .operationHint { margin:-3px 0 10px; font-size:.78rem; color:#3f5f67; }
        .pill.apt-apto { background:#d9f7e7; color:#166534; border:1px solid #93d7b0; }
        .pill.apt-observacion { background:#fff4d8; color:#8a5a00; border:1px solid #f3c777; }
        .pill.apt-limitaciones { background:#ffe9d8; color:#9a3412; border:1px solid #f1b58d; }
        .pill.apt-no-apto { background:#ffe0e7; color:#9f1239; border:1px solid #f3a4b8; }
        .toolbar { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:8px; margin-bottom:10px; }
        .toolbar.compact { grid-template-columns:2fr repeat(3,minmax(0,1fr)) auto; align-items:end; }
        .toolbar .btn { padding:9px 10px; }
        .pager { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-top:8px; }
        .pager .hint { margin:0; }
        .empty { color:var(--muted); font-style:italic; padding:10px 0; }
        @media (max-width:1120px) { .stats{grid-template-columns:repeat(2,minmax(0,1fr));} .grid2{grid-template-columns:1fr;} .grid3{grid-template-columns:1fr;} .operationKpiGrid{grid-template-columns:1fr;} }
        @media (max-width:980px) { .toolbar{grid-template-columns:1fr 1fr;} .toolbar.compact{grid-template-columns:1fr;} }
        @media (max-width:720px) { .top{flex-direction:column; align-items:flex-start;} .actions{width:100%;} .actions .btn{flex:1;} .tabs{width:100%;} .tabs .tab{flex:1;} .workerFormGrid{grid-template-columns:1fr;} .workerFormGrid [class*="span-"]{grid-column:span 1;} .workerManagePanel{padding:14px;} .workerManagePanel table{min-width:680px;} }
    </style>
</head>
<body>
<div class="shell">
    <header class="top">
        <div>
            <h1 class="title">SHCSO Panel Operativo</h1>
            <p class="subtitle">Vista inicial del sistema para operacion clinica ocupacional</p>
        </div>
        <div class="actions">
            <button id="refreshBtn" class="btn hidden" type="button">Refrescar</button>
            <button id="logoutBtn" class="btn warn hidden" type="button">Cerrar sesion</button>
        </div>
    </header>

    <div id="statusBox" class="status">Inicia sesion para cargar la informacion.</div>

    <section id="loginSection" class="card login">
        <h2 class="section">Acceso</h2>
        <form id="loginForm">
            <div class="field">
                <label>Email</label>
                <input name="email" type="email" value="admin@shcso.local" required>
            </div>
            <div class="field">
                <label>Contrasena</label>
                <input name="password" type="password" value="PasswordSeguro123" required>
            </div>
            <button class="btn primary" type="submit">Entrar</button>
            <p id="loginHint" class="hint">Si es tu primer ingreso y no tienes cuenta activa, solicita al administrador del sistema la habilitacion de usuario.</p>
        </form>
        <div id="authRecoveryActions" class="rowActions" style="margin-top:8px;">
            <button id="showForgotPasswordBtn" class="btn small" type="button">Olvide mi contrasena</button>
            <button id="showResetPasswordBtn" class="btn small" type="button">Ya tengo token</button>
        </div>
        <div id="forgotPasswordBox" class="hidden" style="margin-top:10px;">
            <p class="hint">Opcion 1: solicita un token de recuperacion con tu correo.</p>
            <form id="forgotPasswordForm">
                <div class="field">
                    <label>Email de acceso</label>
                    <input name="email" type="email" required>
                </div>
                <div class="rowActions">
                    <button class="btn" type="submit">Solicitar token</button>
                    <button id="cancelForgotPasswordBtn" class="btn small" type="button">Cancelar</button>
                </div>
            </form>
        </div>
        <div id="resetPasswordBox" class="hidden" style="margin-top:10px;">
            <p class="hint">Opcion 2: ingresa token y define nueva contrasena.</p>
            <form id="resetPasswordForm">
                <div class="field">
                    <label>Email</label>
                    <input name="email" type="email" required>
                </div>
                <div class="field">
                    <label>Token</label>
                    <input name="token" type="text" required>
                </div>
                <div class="field">
                    <label>Nueva contrasena</label>
                    <input name="password" type="password" minlength="8" required>
                </div>
                <div class="field">
                    <label>Confirmar contrasena</label>
                    <input name="password_confirmation" type="password" minlength="8" required>
                </div>
                <div class="rowActions">
                    <button class="btn primary" type="submit">Actualizar contrasena</button>
                    <button id="cancelResetPasswordBtn" class="btn small" type="button">Cancelar</button>
                </div>
            </form>
        </div>
        <div id="firstAdminBox" class="hidden">
            <p class="hint">No existe un usuario administrador. Crea el primer usuario para iniciar el sistema.</p>
            <form id="firstAdminForm">
                <div class="field">
                    <label>Nombre completo</label>
                    <input name="full_name" type="text" required>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input name="email" type="email" required>
                </div>
                <div class="field">
                    <label>Contrasena</label>
                    <input name="password" type="password" minlength="8" required>
                </div>
                <div class="field">
                    <label>Confirmar contrasena</label>
                    <input name="password_confirm" type="password" minlength="8" required>
                </div>
                <button class="btn primary" type="submit">Crear primer administrador</button>
            </form>
        </div>
    </section>

    <section id="appSection" class="hidden">
        <nav class="tabs">
            <button class="tab" data-view="dashboard" type="button">Dashboard</button>
            <button class="tab" data-view="workers" type="button">Trabajadores</button>
            <button class="tab" data-view="operations" type="button">Operacion</button>
            <button class="tab" data-view="users" type="button">Usuarios</button>
        </nav>
        <nav class="workerFlow view-workers">
            <button class="workerFlowTab active" data-worker-step="recent" type="button">1. Trabajadores recientes</button>
            <button class="workerFlowTab" data-worker-step="manage" type="button">2. Nuevo trabajador</button>
            <button class="workerFlowTab" data-worker-step="clinical" type="button">3. Historia clinica ampliada</button>
            <button class="workerFlowTab" data-worker-step="history" type="button">4. Historial clinico</button>
        </nav>

        <div id="statsGrid" class="stats view-dashboard"></div>

        <div class="operationPulse view-operations">
            <article class="card operationKpi">
                <h2 class="section">Pulso operativo <span class="sectionBadge">tiempo real</span></h2>
                <div class="operationKpiGrid">
                    <div class="operationKpiItem">
                        <p>Evaluaciones filtradas</p>
                        <strong id="operationsEvalTotal">0</strong>
                    </div>
                    <div class="operationKpiItem">
                        <p>Certificados filtrados</p>
                        <strong id="operationsCertTotal">0</strong>
                    </div>
                    <div class="operationKpiItem">
                        <p>Pendientes por emitir</p>
                        <strong id="operationsPendingTotal">0</strong>
                    </div>
                </div>
            </article>
        </div>

        <div class="grid2 view-dashboard">
            <article class="card">
                <h2 class="section">Actividad mensual</h2>
                <div id="monthlyChart" class="bars"></div>
            </article>
            <article class="card">
                <h2 class="section">Aptitud por empresa</h2>
                <div class="tableWrap">
                    <table>
                        <thead><tr><th>Empresa</th><th>Total</th><th>APTO</th><th>NO APTO</th></tr></thead>
                        <tbody id="aptitudeBody"></tbody>
                    </table>
                </div>
            </article>
        </div>

        <div class="grid3" data-worker-panel-host>
            <article class="card view-workers workerStepPanel workerManagePanel" data-worker-panel="manage">
                <h2 class="section">Nuevo trabajador y ficha/edicion</h2>
                <p class="operationHint">Selecciona un trabajador para ver/editar o usa "Crear trabajador" para un nuevo registro.</p>
                <div class="rowActions workerManageToolbar">
                    <button id="workerCreateBtn" class="btn accent" type="button">Crear trabajador</button>
                    <span id="workerFormModeHint" class="hint">Modo nuevo trabajador.</span>
                </div>
                <div id="workerDetailBox" class="empty workerDetail">Selecciona un trabajador para ver ficha completa.</div>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <div class="tableWrap">
                    <table>
                        <thead><tr><th>Documento</th><th>Nombre</th><th>Empresa</th><th>Historia</th><th>Acciones</th></tr></thead>
                        <tbody id="workersManageBody"></tbody>
                    </table>
                </div>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <form id="workerForm">
                    <input type="hidden" name="worker_id">
                    <div class="workerFormGrid">
                        <div class="field span-2"><label>Tipo documento</label><select name="document_type"><option>CEDULA</option><option>PASAPORTE</option></select></div>
                        <div class="field span-3"><label>Documento</label><input name="document_number" required></div>
                        <div class="field span-3"><label>Nombres</label><input name="first_name" required></div>
                        <div class="field span-4"><label>Apellidos</label><input name="last_name" required></div>
                        <div class="field span-2"><label>Nacimiento</label><input type="date" name="birth_date" required></div>
                        <div class="field span-1"><label>Sexo</label><select name="sex"><option>M</option><option>F</option><option>O</option></select></div>
                        <div class="field span-3"><label>Email</label><input name="email" type="email"></div>
                        <div class="field span-2"><label>Telefono</label><input name="phone"></div>
                        <div class="field span-2"><label>Tipo de sangre</label><input name="blood_type"></div>
                        <div class="field span-2"><label>Lateralidad</label><input name="laterality"></div>
                        <div class="field span-6"><label>Empresa</label><select id="workerCompany" name="company_id"></select></div>
                        <div class="field span-6"><label>Puesto</label><select id="workerPosition" name="job_position_id"></select></div>
                    </div>
                    <div class="rowActions workerFormActions">
                        <button id="workerFormSubmitBtn" class="btn accent" type="submit">Guardar trabajador</button>
                        <button id="workerFormResetBtn" class="btn small hidden" type="button">Cancelar edicion</button>
                    </div>
                </form>
            </article>

            <article class="card view-operations operationCard">
                <h2 class="section">Nueva evaluacion <span class="sectionBadge">flujo 1/2</span></h2>
                <p class="operationHint">Registra primero la evaluacion del trabajador para habilitar certificado y adjuntos.</p>
                <form id="evaluationForm">
                    <div class="field"><label>Trabajador</label><select id="evaluationWorker" name="worker_id" required></select></div>
                    <div class="field"><label>Tipo</label><select name="evaluation_type"><option>INGRESO</option><option>PERIODICO</option><option>REINTEGRO</option><option>RETIRO</option></select></div>
                    <div class="field"><label>Motivo</label><textarea name="consultation_reason" required>Evaluacion de ingreso</textarea></div>
                    <div class="field"><label>Aptitud</label><select name="medical_aptitude"><option>APTO</option><option>APTO_OBSERVACION</option><option>APTO_LIMITACIONES</option><option>NO_APTO</option></select></div>
                    <div class="field"><label>Profesional</label><input name="professional_name" value="Dra. Maria Lopez" required></div>
                    <div class="field"><label>Codigo</label><input name="professional_code" value="MED-12345" required></div>
                    <button class="btn accent" type="submit">Guardar evaluacion</button>
                </form>
            </article>

            <article class="card view-operations operationCard">
                <h2 class="section">Certificado y adjunto <span class="sectionBadge">flujo 2/2</span></h2>
                <p class="operationHint">Genera el certificado desde una evaluacion y luego carga evidencia documental.</p>
                <form id="certificateForm">
                    <div class="field"><label>Evaluacion</label><select id="certificateEvaluation" name="evaluation_id" required></select></div>
                    <div class="field"><label>Observaciones</label><textarea name="observations">Apto para labores</textarea></div>
                    <div class="field"><label>Recomendaciones</label><textarea name="recommendations">Control anual</textarea></div>
                    <button class="btn accent" type="submit">Crear certificado</button>
                </form>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <form id="attachmentForm">
                    <div class="field"><label>Evaluacion para adjunto</label><select id="attachmentEvaluation" name="evaluation_id" required></select></div>
                    <div class="field">
                        <label>Tipo de examen/adjunto</label>
                        <select name="attachment_type">
                            <option value="GENERAL">General</option>
                            <option value="LAB_EXAM">Laboratorio</option>
                            <option value="IMAGING">Imagen</option>
                            <option value="DICOM">DICOM</option>
                            <option value="OTHER">Otro</option>
                        </select>
                    </div>
                    <div class="field"><label>Fecha del estudio (opcional)</label><input name="exam_date" type="date"></div>
                    <div class="field"><label>Notas del examen (opcional)</label><textarea name="notes" placeholder="Detalle relevante del examen"></textarea></div>
                    <div class="field"><label>Archivo</label><input name="file" type="file" accept=".pdf,.jpg,.jpeg,.png,.dcm,.dicom,.ima,.zip" required></div>
                    <p class="hint">Formatos: PDF, JPG, PNG, DCM, DICOM, IMA, ZIP. Max 50 MB.</p>
                    <button class="btn" type="submit">Subir adjunto</button>
                </form>
            </article>
        </div>

        <div class="grid2" data-worker-panel-host>
            <article class="card view-workers workerStepPanel" data-worker-panel="recent">
                <h2 class="section">Trabajadores recientes</h2>
                <div class="toolbar compact">
                    <div class="field"><label>Buscar</label><input id="workerSearchInput" placeholder="Documento o nombre"></div>
                    <button id="workerSearchBtn" class="btn" type="button">Buscar</button>
                    <button id="workersExportBtn" class="btn" type="button">Exportar CSV</button>
                </div>
                <div class="tableWrap"><table><thead><tr><th>Documento</th><th>Nombre</th><th>Empresa</th><th>Historia</th><th>Acciones</th></tr></thead><tbody id="workersBody"></tbody></table></div>
                <div class="pager">
                    <div class="rowActions">
                        <button id="workersPrevBtn" class="btn small" type="button">Anterior</button>
                        <button id="workersNextBtn" class="btn small" type="button">Siguiente</button>
                    </div>
                    <p id="workersPageInfo" class="hint">Pagina 1 de 1</p>
                </div>
            </article>
            <article class="card view-operations operationCard">
                <h2 class="section">Evaluaciones recientes <span class="sectionBadge">operacion</span></h2>
                <form id="evaluationFilterForm" class="toolbar">
                    <div class="field"><label>Tipo</label><select name="evaluation_type"><option value="">Todos</option><option>INGRESO</option><option>PERIODICO</option><option>REINTEGRO</option><option>RETIRO</option></select></div>
                    <div class="field"><label>Aptitud</label><select name="medical_aptitude"><option value="">Todas</option><option>APTO</option><option>APTO_OBSERVACION</option><option>APTO_LIMITACIONES</option><option>NO_APTO</option></select></div>
                    <div class="field"><label>Desde</label><input type="date" name="date_from"></div>
                    <div class="field"><label>Hasta</label><input type="date" name="date_to"></div>
                    <button class="btn" type="submit">Filtrar</button>
                    <button id="evaluationsExportBtn" class="btn" type="button">Exportar CSV</button>
                </form>
                <div class="tableWrap"><table><thead><tr><th>Fecha</th><th>Trabajador</th><th>Tipo</th><th>Aptitud</th></tr></thead><tbody id="evaluationsBody"></tbody></table></div>
                <div class="pager">
                    <div class="rowActions">
                        <button id="evaluationsPrevBtn" class="btn small" type="button">Anterior</button>
                        <button id="evaluationsNextBtn" class="btn small" type="button">Siguiente</button>
                    </div>
                    <p id="evaluationsPageInfo" class="hint">Pagina 1 de 1</p>
                </div>
            </article>
        </div>

        <article class="card view-workers workerStepPanel" data-worker-panel="clinical">
            <h2 class="section">Historia clinica ampliada</h2>
            <form id="workerClinicalForm">
                <input type="hidden" name="worker_id">
                <div class="field"><label>Antecedentes personales</label><textarea name="personal_background" placeholder="Antecedentes personales relevantes"></textarea></div>
                <div class="field"><label>Antecedentes familiares</label><textarea name="family_background" placeholder="Antecedentes familiares"></textarea></div>
                <div class="field"><label>Alergias</label><textarea name="allergies" placeholder="Alergias conocidas"></textarea></div>
                <div class="field"><label>Medicacion habitual</label><textarea name="current_medication" placeholder="Medicacion actual"></textarea></div>
                <div class="field"><label>Patologias previas</label><textarea name="pathological_history" placeholder="Patologias previas"></textarea></div>
                <div class="field"><label>Antecedentes quirurgicos</label><textarea name="surgical_history" placeholder="Cirugias previas"></textarea></div>
                <div class="field"><label>Historia ocupacional</label><textarea name="occupational_history" placeholder="Antecedentes laborales relevantes"></textarea></div>
                <div class="field"><label>Habitos y estilo de vida</label><textarea name="lifestyle_notes" placeholder="Habitos, deporte, consumo"></textarea></div>
                <div class="field"><label>Evolucion longitudinal</label><textarea name="longitudinal_notes" placeholder="Notas de seguimiento clinico"></textarea></div>
                <button class="btn" type="submit">Guardar historia clinica</button>
            </form>
        </article>

        <article class="card view-workers workerStepPanel" data-worker-panel="history">
            <h2 class="section">Historial clinico del trabajador</h2>
            <div id="workerHistoryEval" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
            <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
            <div id="workerHistoryCert" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
            <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
            <h3 class="section" style="font-size:.9rem;">Linea de tiempo clinica</h3>
            <div id="workerTimeline" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
        </article>

        <article class="card view-operations operationCard">
            <h2 class="section">Certificados recientes <span class="sectionBadge">documental</span></h2>
            <form id="certificateFilterForm" class="toolbar">
                <div class="field"><label>Aptitud</label><select name="medical_aptitude"><option value="">Todas</option><option>APTO</option><option>APTO_OBSERVACION</option><option>APTO_LIMITACIONES</option><option>NO_APTO</option></select></div>
                <div class="field"><label>Desde</label><input type="date" name="date_from"></div>
                <div class="field"><label>Hasta</label><input type="date" name="date_to"></div>
                <div class="field"><label>&nbsp;</label><button class="btn" type="submit">Filtrar</button></div>
                <button id="certificatesExportBtn" class="btn" type="button">Exportar CSV</button>
            </form>
            <div class="tableWrap"><table><thead><tr><th>Codigo</th><th>Fecha</th><th>Trabajador</th><th>Aptitud</th><th>Acciones</th></tr></thead><tbody id="certificatesBody"></tbody></table></div>
            <div class="pager">
                <div class="rowActions">
                    <button id="certificatesPrevBtn" class="btn small" type="button">Anterior</button>
                    <button id="certificatesNextBtn" class="btn small" type="button">Siguiente</button>
                </div>
                <p id="certificatesPageInfo" class="hint">Pagina 1 de 1</p>
            </div>
        </article>

        <div class="grid2 view-users">
            <article class="card">
                <h2 class="section">Nuevo usuario</h2>
                <form id="userForm">
                    <div class="field"><label>Nombre completo</label><input name="full_name" required></div>
                    <div class="field"><label>Email</label><input name="email" type="email" required></div>
                    <div class="field"><label>Contrasena</label><input name="password" type="password" minlength="8" required></div>
                    <div class="field"><label>Rol</label><select id="userRoleSelect" name="role_name" required></select></div>
                    <div class="field"><label>Estado</label><select name="is_active"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                    <button class="btn accent" type="submit">Crear usuario</button>
                </form>
            </article>

            <article class="card">
                <h2 class="section">Editar usuario</h2>
                <form id="userEditForm">
                    <input type="hidden" name="user_id">
                    <div class="field"><label>Nombre completo</label><input name="full_name" required></div>
                    <div class="field"><label>Email</label><input name="email" type="email" required></div>
                    <div class="field"><label>Nueva contrasena (opcional)</label><input name="password" type="password" minlength="8"></div>
                    <div class="field"><label>Rol</label><select id="userEditRoleSelect" name="role_name" required></select></div>
                    <div class="field"><label>Estado</label><select name="is_active"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
                    <button class="btn" type="submit">Actualizar usuario</button>
                </form>
            </article>
        </div>

        <article class="card view-users">
            <h2 class="section">Usuarios del sistema</h2>
            <div class="tableWrap"><table><thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="usersBody"></tbody></table></div>
            <div class="pager">
                <div class="rowActions">
                    <button id="usersExportBtn" class="btn small" type="button">Exportar CSV</button>
                    <button id="usersPrevBtn" class="btn small" type="button">Anterior</button>
                    <button id="usersNextBtn" class="btn small" type="button">Siguiente</button>
                </div>
                <p id="usersPageInfo" class="hint">Pagina 1 de 1</p>
            </div>
        </article>
    </section>
</div>

<script>
const state = {
    token:null, user:null, workers:[], evaluations:[], certificates:[], companies:[], positions:[], users:[], roles:[], dashboard:null, monthly:[], aptitude:[],
    selectedWorkerId:null, selectedWorkerHistory:null, activeView:"dashboard", workerStep:"recent", workerQuery:"",
    setupStatus:{ admin_exists:true, bootstrap_required:false, users_count:0 },
    pagination:{
        workers:{ page:1, per_page:10, total:0, total_pages:1, has_next:false, has_prev:false },
        evaluations:{ page:1, per_page:10, total:0, total_pages:1, has_next:false, has_prev:false },
        certificates:{ page:1, per_page:10, total:0, total_pages:1, has_next:false, has_prev:false },
        users:{ page:1, per_page:10, total:0, total_pages:1, has_next:false, has_prev:false },
    },
    evaluationFilters:{ evaluation_type:"", medical_aptitude:"", date_from:"", date_to:"" },
    certificateFilters:{ medical_aptitude:"", date_from:"", date_to:"" }
};
const refs = {
    status: document.getElementById("statusBox"), loginSection: document.getElementById("loginSection"), appSection: document.getElementById("appSection"),
    refreshBtn: document.getElementById("refreshBtn"), logoutBtn: document.getElementById("logoutBtn"),
    tabs: document.querySelectorAll(".tab"), userTab: document.querySelector('.tab[data-view="users"]'),
    workerFlowTabs: document.querySelectorAll(".workerFlowTab"), workerStepPanels: document.querySelectorAll("[data-worker-panel]"), workerPanelHosts: document.querySelectorAll("[data-worker-panel-host]"),
    dashboardViews: document.querySelectorAll(".view-dashboard"), workerViews: document.querySelectorAll(".view-workers"), operationViews: document.querySelectorAll(".view-operations"), userViews: document.querySelectorAll(".view-users"),
    statsGrid: document.getElementById("statsGrid"), monthlyChart: document.getElementById("monthlyChart"), aptitudeBody: document.getElementById("aptitudeBody"),
    operationsEvalTotal: document.getElementById("operationsEvalTotal"), operationsCertTotal: document.getElementById("operationsCertTotal"), operationsPendingTotal: document.getElementById("operationsPendingTotal"),
    workersBody: document.getElementById("workersBody"), evaluationsBody: document.getElementById("evaluationsBody"), certificatesBody: document.getElementById("certificatesBody"), usersBody: document.getElementById("usersBody"),
    workersPrevBtn: document.getElementById("workersPrevBtn"), workersNextBtn: document.getElementById("workersNextBtn"), workersPageInfo: document.getElementById("workersPageInfo"), workersExportBtn: document.getElementById("workersExportBtn"),
    evaluationsPrevBtn: document.getElementById("evaluationsPrevBtn"), evaluationsNextBtn: document.getElementById("evaluationsNextBtn"), evaluationsPageInfo: document.getElementById("evaluationsPageInfo"), evaluationsExportBtn: document.getElementById("evaluationsExportBtn"),
    certificatesPrevBtn: document.getElementById("certificatesPrevBtn"), certificatesNextBtn: document.getElementById("certificatesNextBtn"), certificatesPageInfo: document.getElementById("certificatesPageInfo"), certificatesExportBtn: document.getElementById("certificatesExportBtn"),
    usersPrevBtn: document.getElementById("usersPrevBtn"), usersNextBtn: document.getElementById("usersNextBtn"), usersPageInfo: document.getElementById("usersPageInfo"), usersExportBtn: document.getElementById("usersExportBtn"),
    workerSearchInput: document.getElementById("workerSearchInput"), workerSearchBtn: document.getElementById("workerSearchBtn"),
    evaluationFilterForm: document.getElementById("evaluationFilterForm"), certificateFilterForm: document.getElementById("certificateFilterForm"),
    workerCompany: document.getElementById("workerCompany"), workerPosition: document.getElementById("workerPosition"),
    workerDetailBox: document.getElementById("workerDetailBox"), workersManageBody: document.getElementById("workersManageBody"), workerClinicalForm: document.getElementById("workerClinicalForm"), workerFormSubmitBtn: document.getElementById("workerFormSubmitBtn"), workerFormResetBtn: document.getElementById("workerFormResetBtn"), workerCreateBtn: document.getElementById("workerCreateBtn"), workerFormModeHint: document.getElementById("workerFormModeHint"),
    workerHistoryEval: document.getElementById("workerHistoryEval"), workerHistoryCert: document.getElementById("workerHistoryCert"), workerTimeline: document.getElementById("workerTimeline"),
    evaluationWorker: document.getElementById("evaluationWorker"), certificateEvaluation: document.getElementById("certificateEvaluation"), attachmentEvaluation: document.getElementById("attachmentEvaluation"),
    userForm: document.getElementById("userForm"), userEditForm: document.getElementById("userEditForm"), userRoleSelect: document.getElementById("userRoleSelect"), userEditRoleSelect: document.getElementById("userEditRoleSelect"),
    loginForm: document.getElementById("loginForm"), loginHint: document.getElementById("loginHint"), firstAdminBox: document.getElementById("firstAdminBox"), firstAdminForm: document.getElementById("firstAdminForm"),
    authRecoveryActions: document.getElementById("authRecoveryActions"), showForgotPasswordBtn: document.getElementById("showForgotPasswordBtn"), showResetPasswordBtn: document.getElementById("showResetPasswordBtn"),
    forgotPasswordBox: document.getElementById("forgotPasswordBox"), forgotPasswordForm: document.getElementById("forgotPasswordForm"), cancelForgotPasswordBtn: document.getElementById("cancelForgotPasswordBtn"),
    resetPasswordBox: document.getElementById("resetPasswordBox"), resetPasswordForm: document.getElementById("resetPasswordForm"), cancelResetPasswordBtn: document.getElementById("cancelResetPasswordBtn")
};

function status(msg, type="info"){ refs.status.textContent = msg; refs.status.classList.remove("ok","error"); if(type==="ok") refs.status.classList.add("ok"); if(type==="error") refs.status.classList.add("error"); }
function fmtDate(v){ if(!v) return "-"; try { return new Date(v).toLocaleDateString(); } catch { return v; } }
function formatBytes(value){
    const bytes = Number(value || 0);
    if(!Number.isFinite(bytes) || bytes <= 0) return "-";
    if(bytes < 1024) return `${bytes} B`;
    const kb = bytes / 1024;
    if(kb < 1024) return `${kb.toFixed(1)} KB`;
    const mb = kb / 1024;
    return `${mb.toFixed(2)} MB`;
}
function makeOpt(value,label){ const o=document.createElement("option"); o.value=value; o.textContent=label; return o; }
function esc(v){ return String(v ?? "").replaceAll("&","&amp;").replaceAll("<","&lt;").replaceAll(">","&gt;").replaceAll('"',"&quot;").replaceAll("'","&#039;"); }
function buildQueryString(filters){ const p = new URLSearchParams(); Object.entries(filters).forEach(([k,v]) => { if(v!==null && v!==undefined && String(v).trim()!=="") p.set(k, String(v)); }); return p.toString(); }
function canManageUsers(){ return Array.isArray(state.user?.roles) && state.user.roles.includes("ADMIN"); }
function compactText(value){ const v = String(value ?? "").trim(); return v === "" ? null : v; }
function normalizeFieldName(field){ return String(field || "").replaceAll("_"," "); }
function aptitudePillClass(aptitude){
    switch(String(aptitude || "").toUpperCase()){
        case "APTO": return "apt-apto";
        case "APTO_OBSERVACION": return "apt-observacion";
        case "APTO_LIMITACIONES": return "apt-limitaciones";
        case "NO_APTO": return "apt-no-apto";
        default: return "";
    }
}
function extractApiErrorMessage(data, fallback){
    if(data?.errors && typeof data.errors === "object"){
        const firstKey = Object.keys(data.errors)[0];
        const firstArray = firstKey ? data.errors[firstKey] : null;
        const firstMessage = Array.isArray(firstArray) ? firstArray[0] : null;
        if(firstMessage) return `${normalizeFieldName(firstKey)}: ${firstMessage}`;
    }
    return data?.message || fallback;
}
function normalizePageMeta(meta, fallbackPage=1, fallbackPerPage=10){
    const page = Math.max(1, Number(meta?.page ?? fallbackPage) || fallbackPage);
    const perPage = Math.max(1, Number(meta?.per_page ?? fallbackPerPage) || fallbackPerPage);
    const total = Math.max(0, Number(meta?.total ?? 0) || 0);
    const defaultTotalPages = Math.max(1, Math.ceil(total / perPage));
    const totalPages = Math.max(1, Number(meta?.total_pages ?? defaultTotalPages) || 1);
    return {
        page: Math.min(page, totalPages),
        per_page: perPage,
        total,
        total_pages: totalPages,
        has_next: page < totalPages,
        has_prev: page > 1,
    };
}
function applyPagerInfo(type){
    const meta = state.pagination[type];
    const map = {
        workers:[refs.workersPrevBtn, refs.workersNextBtn, refs.workersPageInfo],
        evaluations:[refs.evaluationsPrevBtn, refs.evaluationsNextBtn, refs.evaluationsPageInfo],
        certificates:[refs.certificatesPrevBtn, refs.certificatesNextBtn, refs.certificatesPageInfo],
        users:[refs.usersPrevBtn, refs.usersNextBtn, refs.usersPageInfo],
    };
    const [prevBtn, nextBtn, info] = map[type];
    if(!prevBtn || !nextBtn || !info) return;
    prevBtn.disabled = !meta.has_prev;
    nextBtn.disabled = !meta.has_next;
    info.textContent = `Pagina ${meta.page} de ${meta.total_pages} (${meta.total} registros)`;
}
function toCsvValue(value){
    const raw = String(value ?? "");
    const escaped = raw.replaceAll('"', '""');
    return `"${escaped}"`;
}
function exportCsv(filename, headers, rows){
    const lines = [];
    lines.push(headers.map(toCsvValue).join(","));
    rows.forEach(row => lines.push(row.map(toCsvValue).join(",")));
    const csv = "\uFEFF" + lines.join("\n");
    const blob = new Blob([csv], { type:"text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}
async function downloadWithToken(path, filename){
    const res = await fetch(path, { headers:{ Authorization:`Bearer ${state.token}` } });
    if(!res.ok) throw new Error("No se pudo descargar archivo.");
    const blob = await res.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename || "archivo";
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}
function showRecoveryMode(mode){
    if(!refs.forgotPasswordBox || !refs.resetPasswordBox) return;
    refs.forgotPasswordBox.classList.toggle("hidden", mode !== "forgot");
    refs.resetPasswordBox.classList.toggle("hidden", mode !== "reset");
}
function applyAuthBootstrapView(){
    const bootstrapRequired = !!state.setupStatus?.bootstrap_required;
    refs.loginForm.classList.toggle("hidden", bootstrapRequired);
    refs.firstAdminBox.classList.toggle("hidden", !bootstrapRequired);
    if(refs.authRecoveryActions){
        refs.authRecoveryActions.classList.toggle("hidden", bootstrapRequired);
    }
    if(bootstrapRequired){
        showRecoveryMode("none");
    }
    if(refs.loginHint){
        refs.loginHint.textContent = bootstrapRequired
            ? "Crea el primer usuario ADMIN para habilitar el acceso."
            : "Ingresa con tu usuario para continuar.";
    }
}
function applyResetQueryFromUrl(){
    const params = new URLSearchParams(window.location.search);
    const token = params.get("token") || params.get("reset_token");
    const email = params.get("email");
    if(!token && !email) return;
    showRecoveryMode("reset");
    if(refs.resetPasswordForm){
        if(email) refs.resetPasswordForm.email.value = email;
        if(token) refs.resetPasswordForm.token.value = token;
    }
}

function resolveViewFromPath(){
    const p = window.location.pathname;
    if(p.startsWith("/sistema/trabajadores")) return "workers";
    if(p.startsWith("/sistema/operacion")) return "operations";
    if(p.startsWith("/sistema/usuarios")) return "users";
    return "dashboard";
}

function applyWorkerStepVisibility(){
    const workersView = state.activeView === "workers";
    refs.workerFlowTabs.forEach(tab => tab.classList.toggle("active", tab.getAttribute("data-worker-step") === state.workerStep));
    refs.workerStepPanels.forEach(panel => {
        const panelStep = panel.getAttribute("data-worker-panel");
        panel.classList.toggle("hidden", !workersView || panelStep !== state.workerStep);
    });
    refs.workerPanelHosts.forEach(host => {
        if(!workersView){
            host.classList.toggle("hidden", state.activeView !== "operations");
            return;
        }
        const hasStepPanel = !!host.querySelector(`[data-worker-panel="${state.workerStep}"]`);
        host.classList.toggle("hidden", !hasStepPanel);
    });
}

function setWorkerStep(step){
    const allowed = new Set(["recent","manage","clinical","history"]);
    state.workerStep = allowed.has(step) ? step : "recent";
    applyWorkerStepVisibility();
}

function applyViewVisibility(){
    const dashboard = state.activeView === "dashboard";
    const workers = state.activeView === "workers";
    const operations = state.activeView === "operations";
    const users = state.activeView === "users" && canManageUsers();
    refs.dashboardViews.forEach(el => el.classList.toggle("hidden", !dashboard));
    refs.workerViews.forEach(el => el.classList.toggle("hidden", !workers));
    refs.operationViews.forEach(el => el.classList.toggle("hidden", !operations));
    refs.userViews.forEach(el => el.classList.toggle("hidden", !users));
    if(refs.userTab) refs.userTab.classList.toggle("hidden", !canManageUsers());
    refs.tabs.forEach(tab => tab.classList.toggle("active", tab.getAttribute("data-view") === state.activeView));
    applyWorkerStepVisibility();
}

function setView(view, updateHistory=true){
    if(view === "users" && !canManageUsers()){
        view = "dashboard";
    }
    state.activeView = view;
    applyViewVisibility();
    if(!updateHistory) return;
    const target = view === "workers"
        ? "/sistema/trabajadores"
        : (view === "operations"
            ? "/sistema/operacion"
            : (view === "users" ? "/sistema/usuarios" : "/sistema"));
    if(window.location.pathname !== target) window.history.pushState({view}, "", target);
}

async function api(path, {method="GET", body=null, form=false}={}) {
    const headers = {"Accept":"application/json"};
    if (state.token) headers["Authorization"] = `Bearer ${state.token}`;
    let payload = null;
    if (body) { if (form) payload = body; else { headers["Content-Type"]="application/json"; payload=JSON.stringify(body);} }
    const res = await fetch(path, {method, headers, body:payload});
    const ctype = res.headers.get("content-type") || "";
    const data = ctype.includes("application/json") ? await res.json() : {message: await res.text()};
    if (!res.ok) {
        const err = new Error(extractApiErrorMessage(data, `HTTP ${res.status}`));
        err.status = res.status;
        err.errors = data?.errors || null;
        throw err;
    }
    return data;
}

async function loadSetupStatus(){
    const res = await api("/api/auth/setup-status");
    state.setupStatus = res.data || { admin_exists:true, bootstrap_required:false, users_count:0 };
    applyAuthBootstrapView();
}

async function loadAll(){
    const me = await api("/api/auth/me");
    state.user = me.data;

    const workersQuery = buildQueryString({
        q: state.workerQuery,
        page: state.pagination.workers.page,
        per_page: state.pagination.workers.per_page,
    });
    const evaluationsQuery = buildQueryString({
        ...state.evaluationFilters,
        page: state.pagination.evaluations.page,
        per_page: state.pagination.evaluations.per_page,
    });
    const certificatesQuery = buildQueryString({
        ...state.certificateFilters,
        page: state.pagination.certificates.page,
        per_page: state.pagination.certificates.per_page,
    });
    const [dashboard, monthly, aptitude, workers, evaluations, certificates, companies, positions] = await Promise.all([
        api("/api/reports/dashboard"), api("/api/reports/monthly-activity?months=6"), api("/api/reports/aptitude-by-company?limit=8"),
        api(`/api/workers?${workersQuery}`), api(`/api/evaluations?${evaluationsQuery}`), api(`/api/certificates?${certificatesQuery}`), api("/api/catalog/companies"), api("/api/catalog/job-positions")
    ]);
    state.dashboard = dashboard.data; state.monthly = monthly.data || []; state.aptitude = aptitude.data || [];
    state.workers = workers.data || []; state.evaluations = evaluations.data || []; state.certificates = certificates.data || []; state.companies = companies.data || []; state.positions = positions.data || [];
    state.pagination.workers = normalizePageMeta(workers.meta, state.pagination.workers.page, state.pagination.workers.per_page);
    state.pagination.evaluations = normalizePageMeta(evaluations.meta, state.pagination.evaluations.page, state.pagination.evaluations.per_page);
    state.pagination.certificates = normalizePageMeta(certificates.meta, state.pagination.certificates.page, state.pagination.certificates.per_page);

    if(canManageUsers()){
        const usersQuery = buildQueryString({
            page: state.pagination.users.page,
            per_page: state.pagination.users.per_page,
        });
        const [users, roles] = await Promise.all([
            api(`/api/users?${usersQuery}`),
            api("/api/users/roles"),
        ]);
        state.users = users.data || [];
        state.roles = roles.data || [];
        state.pagination.users = normalizePageMeta(users.meta, state.pagination.users.page, state.pagination.users.per_page);
    } else {
        state.users = [];
        state.roles = [];
        state.pagination.users = normalizePageMeta({ page:1, per_page: state.pagination.users.per_page, total:0, total_pages:1 }, 1, state.pagination.users.per_page);
    }
}

async function loadWorkerHistory(workerId){
    const res = await api(`/api/workers/${workerId}/history`);
    state.selectedWorkerId = workerId;
    state.selectedWorkerHistory = res.data;
}

function renderStats(){
    const t = state.dashboard?.totals || {};
    const cards = [["Trabajadores", t.workers ?? 0], ["Evaluaciones", t.evaluations ?? 0], ["Certificados", t.certificates ?? 0], ["Pendientes", t.pending_certificates ?? 0]];
    refs.statsGrid.innerHTML = "";
    cards.forEach(([k,v]) => { const el = document.createElement("article"); el.className="stat"; el.innerHTML=`<h4>${k}</h4><p>${v}</p>`; refs.statsGrid.appendChild(el); });
}

function renderOperationKpi(){
    if(!refs.operationsEvalTotal || !refs.operationsCertTotal || !refs.operationsPendingTotal) return;
    refs.operationsEvalTotal.textContent = String(state.pagination.evaluations.total ?? 0);
    refs.operationsCertTotal.textContent = String(state.pagination.certificates.total ?? 0);
    refs.operationsPendingTotal.textContent = String(state.dashboard?.totals?.pending_certificates ?? 0);
}

function renderMonthly(){
    refs.monthlyChart.innerHTML = "";
    if(!state.monthly.length){ refs.monthlyChart.innerHTML = `<p class="empty">Sin datos.</p>`; return; }
    const max = Math.max(...state.monthly.map(x=>x.total), 1);
    state.monthly.forEach(item => {
        const pct = Math.max(4, Math.round((item.total / max) * 100));
        const box = document.createElement("div"); box.className = "barItem";
        box.innerHTML = `<div class="barCol" style="height:${pct}%"></div><div class="barTag">${item.month}<br><strong>${item.total}</strong></div>`;
        refs.monthlyChart.appendChild(box);
    });
}

function renderAptitude(){
    refs.aptitudeBody.innerHTML = "";
    if(!state.aptitude.length){ refs.aptitudeBody.innerHTML = `<tr><td colspan="4" class="empty">Sin datos.</td></tr>`; return; }
    state.aptitude.forEach(item => {
        const row = document.createElement("tr"); const t = item.totals_by_aptitude || {};
        row.innerHTML = `<td>${item.company_name}</td><td>${item.total_evaluations}</td><td>${t.APTO ?? 0}</td><td>${t.NO_APTO ?? 0}</td>`;
        refs.aptitudeBody.appendChild(row);
    });
}

function renderWorkers(){
    const renderTableBody = (bodyEl) => {
        if(!bodyEl) return;
        bodyEl.innerHTML = "";
        if(!state.workers.length){
            bodyEl.innerHTML = `<tr><td colspan="5" class="empty">Sin trabajadores.</td></tr>`;
            return;
        }
        state.workers.forEach(w => {
            const row = document.createElement("tr");
            const company = w.company?.business_name || w.business_name || "N/A";
            row.innerHTML = `<td>${esc(w.document_number)}</td><td>${esc(w.first_name)} ${esc(w.last_name)}</td><td>${esc(company)}</td><td><span class="pill">${esc(w.history_number)}</span></td>
            <td><div class="rowActions"><button class="btn small" data-act="view-worker" data-worker-id="${w.id}" type="button">Ver</button><button class="btn small" data-act="edit-worker" data-worker-id="${w.id}" type="button">Editar</button><button class="btn small" data-act="delete-worker" data-worker-id="${w.id}" type="button">Eliminar</button></div></td>`;
            bodyEl.appendChild(row);
        });
    };
    renderTableBody(refs.workersBody);
    renderTableBody(refs.workersManageBody);
}

function setWorkerFormMode(mode){
    const editMode = mode === "edit";
    if(refs.workerFormSubmitBtn) refs.workerFormSubmitBtn.textContent = editMode ? "Actualizar trabajador" : "Guardar trabajador";
    if(refs.workerFormResetBtn) refs.workerFormResetBtn.classList.toggle("hidden", !editMode);
    if(refs.workerFormModeHint) refs.workerFormModeHint.textContent = editMode ? "Modo edicion de trabajador." : "Modo nuevo trabajador.";
}

function resetWorkerForm(keepHistory=false){
    const form = document.getElementById("workerForm");
    if(!form) return;
    form.reset();
    form.worker_id.value = "";
    form.document_type.value = "CEDULA";
    form.sex.value = "M";
    form.company_id.value = "";
    form.job_position_id.value = "";
    setWorkerFormMode("create");
    if(!keepHistory){
        refs.workerDetailBox.innerHTML = `<p class="empty">Selecciona un trabajador para ver ficha completa.</p>`;
    }
}

function fillWorkerForm(worker){
    const form = document.getElementById("workerForm");
    if(!form) return;
    form.worker_id.value = worker.id || "";
    form.document_type.value = worker.document_type || "CEDULA";
    form.document_number.value = worker.document_number || "";
    form.first_name.value = worker.first_name || "";
    form.last_name.value = worker.last_name || "";
    form.birth_date.value = worker.birth_date || "";
    form.sex.value = worker.sex || "M";
    form.email.value = worker.email || "";
    form.phone.value = worker.phone || "";
    form.blood_type.value = worker.blood_type || "";
    form.laterality.value = worker.laterality || "";
    form.company_id.value = worker.company_id || "";
    form.job_position_id.value = worker.job_position_id || "";
    setWorkerFormMode("edit");
}

function fillWorkerClinicalForm(clinicalHistory, workerId){
    const form = refs.workerClinicalForm;
    if(!form) return;
    const data = clinicalHistory || {};
    form.worker_id.value = workerId || "";
    form.personal_background.value = data.personal_background || "";
    form.family_background.value = data.family_background || "";
    form.allergies.value = data.allergies || "";
    form.current_medication.value = data.current_medication || "";
    form.pathological_history.value = data.pathological_history || "";
    form.surgical_history.value = data.surgical_history || "";
    form.occupational_history.value = data.occupational_history || "";
    form.lifestyle_notes.value = data.lifestyle_notes || "";
    form.longitudinal_notes.value = data.longitudinal_notes || "";
}

function renderWorkerHistory(){
    const history = state.selectedWorkerHistory;
    if(!history || !history.worker){
        refs.workerDetailBox.innerHTML = `<p class="empty">Selecciona un trabajador para ver ficha completa.</p>`;
        refs.workerHistoryEval.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`;
        refs.workerHistoryCert.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`;
        refs.workerTimeline.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`;
        resetWorkerForm(true);
        fillWorkerClinicalForm(null, "");
        return;
    }

    const w = history.worker;
    refs.workerDetailBox.innerHTML = `
        <p class="meta"><strong>Nombre:</strong> ${esc(w.first_name)} ${esc(w.last_name)}</p>
        <p class="meta"><strong>Documento:</strong> ${esc(w.document_type)} ${esc(w.document_number)}</p>
        <p class="meta"><strong>Historia:</strong> ${esc(w.history_number)}</p>
        <p class="meta"><strong>Archivo:</strong> ${esc(w.file_number)}</p>
        <p class="meta"><strong>Empresa:</strong> ${esc(w.company?.business_name || "Sin empresa")}</p>
        <p class="meta"><strong>Puesto:</strong> ${esc(w.job_position?.name || "Sin puesto")}</p>
        <div class="chips"><span class="chip">Evaluaciones: ${(history.evaluations || []).length}</span><span class="chip">Certificados: ${(history.certificates || []).length}</span></div>
    `;

    fillWorkerForm(w);
    fillWorkerClinicalForm(history.clinical_history, w.id);

    refs.workerHistoryEval.innerHTML = "";
    const evals = history.evaluations || [];
    if(!evals.length){
        refs.workerHistoryEval.innerHTML = `<p class="empty">No hay evaluaciones para este trabajador.</p>`;
    } else {
        evals.forEach(e => {
            const card = document.createElement("div");
            const diagnoses = (e.diagnoses || []).map(d => `<span class="chip">${esc(d.diagnosis_code)} (${esc(d.diagnosis_type)})</span>`).join("");
            const attachments = e.attachments || [];
            const attachmentRows = attachments.map((a) => {
                const type = a.attachment_type || "GENERAL";
                const examDate = a.exam_date ? fmtDate(a.exam_date) : "-";
                const notes = a.notes ? ` | ${esc(a.notes)}` : "";
                return `<p class="meta"><strong>${esc(type)}</strong> | ${esc(a.file_name || "archivo")} | ${examDate} | ${formatBytes(a.file_size_bytes)}${notes}
                <button class="btn small" data-act="download-attachment" data-attachment-id="${a.id}" data-file-name="${esc(a.file_name || "archivo")}" type="button">Descargar</button></p>`;
            }).join("");
            card.className = "historyCard";
            card.innerHTML = `<p class="meta"><strong>${esc(e.evaluation_type)}</strong> - ${fmtDate(e.attention_date)} <span class="pill ${aptitudePillClass(e.medical_aptitude)}">${esc(e.medical_aptitude)}</span></p>
            <p class="meta"><strong>Motivo:</strong> ${esc(e.consultation_reason || "-")}</p>
            <p class="meta"><strong>Profesional:</strong> ${esc(e.professional_name || "-")} (${esc(e.professional_code || "-")})</p>
            <p class="meta"><strong>Adjuntos:</strong> ${attachments.length}</p>
            ${attachmentRows || '<p class="meta">Sin adjuntos de examenes.</p>'}
            <div class="chips">${diagnoses || '<span class="chip">Sin diagnosticos</span>'}</div>`;
            refs.workerHistoryEval.appendChild(card);
        });
    }

    refs.workerHistoryCert.innerHTML = "";
    const certs = history.certificates || [];
    if(!certs.length){
        refs.workerHistoryCert.innerHTML = `<p class="empty">No hay certificados para este trabajador.</p>`;
    } else {
        certs.forEach(c => {
            const card = document.createElement("div");
            card.className = "historyCard";
            card.innerHTML = `<p class="meta"><strong>${esc(c.certificate_code)}</strong> - ${fmtDate(c.issue_date)} <span class="pill ${aptitudePillClass(c.medical_aptitude)}">${esc(c.medical_aptitude)}</span></p>
            <p class="meta"><strong>Observaciones:</strong> ${esc(c.observations || "-")}</p>
            <p class="meta"><strong>Recomendaciones:</strong> ${esc(c.recommendations || "-")}</p>`;
            refs.workerHistoryCert.appendChild(card);
        });
    }

    refs.workerTimeline.innerHTML = "";
    const timeline = history.clinical_timeline || [];
    if(!timeline.length){
        refs.workerTimeline.innerHTML = `<p class="empty">Sin eventos clinicos en la linea de tiempo.</p>`;
    } else {
        timeline.forEach(item => {
            const card = document.createElement("div");
            card.className = "historyCard";
            const typeLabel = item.event_type === "CERTIFICATE" ? "Certificado" : "Evaluacion";
            card.innerHTML = `<p class="meta"><strong>${typeLabel}</strong> - ${fmtDate(item.event_date)} <span class="pill">${esc(item.subtitle || "-")}</span></p>
            <p class="meta"><strong>${esc(item.title || "-")}</strong></p>
            <p class="meta"><strong>Detalle:</strong> ${esc(item.notes || "-")}</p>`;
            refs.workerTimeline.appendChild(card);
        });
    }
}

function renderEvaluations(){
    refs.evaluationsBody.innerHTML = "";
    if(!state.evaluations.length){ refs.evaluationsBody.innerHTML = `<tr><td colspan="4" class="empty">Sin evaluaciones.</td></tr>`; return; }
    state.evaluations.forEach(e => {
        const w = e.worker || {};
        const row = document.createElement("tr");
        row.innerHTML = `<td>${fmtDate(e.attention_date)}</td><td>${esc(w.first_name || "")} ${esc(w.last_name || "")}</td><td>${esc(e.evaluation_type)}</td><td><span class="pill ${aptitudePillClass(e.medical_aptitude)}">${esc(e.medical_aptitude)}</span></td>`;
        refs.evaluationsBody.appendChild(row);
    });
}

function renderCertificates(){
    refs.certificatesBody.innerHTML = "";
    if(!state.certificates.length){ refs.certificatesBody.innerHTML = `<tr><td colspan="5" class="empty">Sin certificados.</td></tr>`; return; }
    state.certificates.forEach(c => {
        const w = c.worker || {};
        const row = document.createElement("tr");
        row.innerHTML = `<td>${esc(c.certificate_code)}</td><td>${fmtDate(c.issue_date)}</td><td>${esc(w.first_name || "")} ${esc(w.last_name || "")}</td><td><span class="pill ${aptitudePillClass(c.medical_aptitude)}">${esc(c.medical_aptitude)}</span></td>
        <td><div class="rowActions"><button class="btn" data-act="gen" data-id="${c.id}" type="button">Generar PDF</button><button class="btn" data-act="down" data-id="${c.id}" type="button">Descargar</button></div></td>`;
        refs.certificatesBody.appendChild(row);
    });
}

function fillUserEditForm(user){
    if(!refs.userEditForm) return;
    refs.userEditForm.user_id.value = user.id || "";
    refs.userEditForm.full_name.value = user.full_name || "";
    refs.userEditForm.email.value = user.email || "";
    refs.userEditForm.password.value = "";
    refs.userEditForm.role_name.value = user.roles?.[0] || "";
    refs.userEditForm.is_active.value = user.is_active ? "1" : "0";
}

function renderUsers(){
    if(!refs.usersBody) return;
    refs.usersBody.innerHTML = "";
    if(!canManageUsers()){
        refs.usersBody.innerHTML = `<tr><td colspan="5" class="empty">Solo administradores.</td></tr>`;
        return;
    }
    if(!state.users.length){
        refs.usersBody.innerHTML = `<tr><td colspan="5" class="empty">Sin usuarios registrados.</td></tr>`;
        return;
    }
    state.users.forEach(u => {
        const role = esc(u.roles?.[0] || "SIN_ROL");
        const stateLabel = u.is_active ? "Activo" : "Inactivo";
        const toggleLabel = u.is_active ? "Desactivar" : "Activar";
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${esc(u.full_name)}</td>
            <td>${esc(u.email)}</td>
            <td><span class="pill">${role}</span></td>
            <td>${stateLabel}</td>
            <td>
                <div class="rowActions">
                    <button class="btn small" data-act="edit-user" data-id="${u.id}" type="button">Editar</button>
                    <button class="btn small" data-act="reset-user-password" data-id="${u.id}" data-email="${esc(u.email)}" type="button">Reset clave</button>
                    <button class="btn small" data-act="toggle-user" data-id="${u.id}" data-next="${u.is_active ? "0" : "1"}" type="button">${toggleLabel}</button>
                </div>
            </td>`;
        refs.usersBody.appendChild(row);
    });
}

function fillSelects(){
    refs.workerCompany.innerHTML = ""; refs.workerCompany.appendChild(makeOpt("", "Sin empresa")); state.companies.forEach(c=>refs.workerCompany.appendChild(makeOpt(c.id, c.business_name)));
    refs.workerPosition.innerHTML = ""; refs.workerPosition.appendChild(makeOpt("", "Sin puesto")); state.positions.forEach(p=>refs.workerPosition.appendChild(makeOpt(p.id, p.name)));
    refs.evaluationWorker.innerHTML = ""; state.workers.forEach(w=>refs.evaluationWorker.appendChild(makeOpt(w.id, `${w.first_name} ${w.last_name} (${w.document_number})`)));
    [refs.certificateEvaluation, refs.attachmentEvaluation].forEach(sel => { sel.innerHTML=""; state.evaluations.forEach(e=>{ const w=e.worker||{}; sel.appendChild(makeOpt(e.id, `${e.evaluation_type} - ${w.first_name || ""} ${w.last_name || ""}`)); }); });
    if(refs.userRoleSelect && refs.userEditRoleSelect){
        refs.userRoleSelect.innerHTML = "";
        refs.userEditRoleSelect.innerHTML = "";
        state.roles.forEach(role => {
            refs.userRoleSelect.appendChild(makeOpt(role.name, role.name));
            refs.userEditRoleSelect.appendChild(makeOpt(role.name, role.name));
        });
    }
}

function renderAll(){
    renderStats();
    renderOperationKpi();
    renderMonthly();
    renderAptitude();
    renderWorkers();
    renderEvaluations();
    renderCertificates();
    renderUsers();
    fillSelects();
    renderWorkerHistory();
    applyPagerInfo("workers");
    applyPagerInfo("evaluations");
    applyPagerInfo("certificates");
    applyPagerInfo("users");
}
function showApp(){ refs.loginSection.classList.add("hidden"); refs.appSection.classList.remove("hidden"); refs.refreshBtn.classList.remove("hidden"); refs.logoutBtn.classList.remove("hidden"); }
function showLogin(){ refs.loginSection.classList.remove("hidden"); refs.appSection.classList.add("hidden"); refs.refreshBtn.classList.add("hidden"); refs.logoutBtn.classList.add("hidden"); showRecoveryMode("none"); applyAuthBootstrapView(); applyResetQueryFromUrl(); }
async function prepareLoginSection(){
    showLogin();
    try{
        await loadSetupStatus();
    } catch {
        state.setupStatus = { admin_exists:true, bootstrap_required:false, users_count:0 };
        applyAuthBootstrapView();
    }
}

async function refreshData(){
    status("Cargando datos...");
    try {
        const prev = state.selectedWorkerId;
        await loadAll();
        if(state.activeView === "users" && !canManageUsers()){
            setView("dashboard", true);
        } else {
            applyViewVisibility();
        }
        if(prev && state.workers.some(w => w.id === prev)){
            await loadWorkerHistory(prev);
        } else if (prev) {
            state.selectedWorkerId = null;
            state.selectedWorkerHistory = null;
        }
        renderAll();
        showApp();
        status(`Sesion activa: ${state.user.full_name}`, "ok");
    }
    catch (e) { if(e.status===401){ await logout(); status("Token invalido o expirado.", "error"); } else status(e.message || "Error al cargar datos.", "error"); }
}

async function login(email,password){ const res = await api("/api/auth/login",{method:"POST", body:{email,password}}); state.token = res.data.token; localStorage.setItem("shcso_token", state.token); }
async function logout(){ try{ await api("/api/auth/logout",{method:"POST"}); } catch{} state.token=null; localStorage.removeItem("shcso_token"); await prepareLoginSection(); status("Sesion cerrada.", "ok"); }

document.getElementById("loginForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    if(state.setupStatus?.bootstrap_required){
        status("Primero crea el primer usuario administrador.", "error");
        return;
    }
    const fd = new FormData(e.target);
    status("Validando credenciales...");
    try{
        await login(fd.get("email"), fd.get("password"));
        await refreshData();
        setView(resolveViewFromPath(), false);
    }
    catch(err){ status(err.message || "No se pudo iniciar sesion.", "error"); }
});

refs.showForgotPasswordBtn.addEventListener("click", () => {
    showRecoveryMode("forgot");
});

refs.showResetPasswordBtn.addEventListener("click", () => {
    showRecoveryMode("reset");
});

refs.cancelForgotPasswordBtn.addEventListener("click", () => {
    showRecoveryMode("none");
});

refs.cancelResetPasswordBtn.addEventListener("click", () => {
    showRecoveryMode("none");
});

refs.forgotPasswordForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const email = String(fd.get("email") || "").trim().toLowerCase();
    if(email === ""){ status("Ingresa un correo valido.", "error"); return; }

    status("Solicitando token de recuperacion...");
    try{
        const res = await api("/api/auth/forgot-password", { method:"POST", body:{ email } });
        const resetUrl = res?.data?.reset_url;
        const debugToken = res?.data?.reset_token;
        if(debugToken || resetUrl){
            if(resetUrl){
                window.prompt("Enlace de recuperacion (modo local). Copialo y abrelo:", resetUrl);
            }
            status(`Recuperacion generada (modo local).`, "ok");
            showRecoveryMode("reset");
            refs.resetPasswordForm.email.value = email;
            if(debugToken) refs.resetPasswordForm.token.value = debugToken;
        } else {
            status(res?.message || "Si el correo existe, se envio token de recuperacion.", "ok");
            showRecoveryMode("reset");
            refs.resetPasswordForm.email.value = email;
        }
    } catch(err){
        status(err.message || "No se pudo solicitar recuperacion de contrasena.", "error");
    }
});

refs.resetPasswordForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const email = String(fd.get("email") || "").trim().toLowerCase();
    const token = String(fd.get("token") || "").trim();
    const password = String(fd.get("password") || "");
    const passwordConfirm = String(fd.get("password_confirmation") || "");

    if(email === "" || token === ""){ status("Email y token son obligatorios.", "error"); return; }
    if(password.length < 8){ status("Contrasena invalida: minimo 8 caracteres.", "error"); return; }
    if(password !== passwordConfirm){ status("Las contrasenas no coinciden.", "error"); return; }

    status("Actualizando contrasena...");
    try{
        await api("/api/auth/reset-password", {
            method:"POST",
            body:{
                email,
                token,
                password,
                password_confirmation: passwordConfirm,
            },
        });
        status("Contrasena actualizada. Ahora inicia sesion.", "ok");
        showRecoveryMode("none");
        refs.loginForm.email.value = email;
        refs.loginForm.password.value = "";
        refs.resetPasswordForm.reset();
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, "", cleanUrl);
    } catch(err){
        status(err.message || "No se pudo restablecer contrasena.", "error");
    }
});

refs.firstAdminForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const fullName = String(fd.get("full_name") || "").trim();
    const email = String(fd.get("email") || "").trim().toLowerCase();
    const password = String(fd.get("password") || "");
    const passwordConfirm = String(fd.get("password_confirm") || "");

    if(fullName.length < 3){ status("Nombre invalido: minimo 3 caracteres.", "error"); return; }
    if(password.length < 8){ status("Contrasena invalida: minimo 8 caracteres.", "error"); return; }
    if(password !== passwordConfirm){ status("Las contrasenas no coinciden.", "error"); return; }

    status("Creando primer administrador...");
    try{
        await api("/api/auth/register-admin", {
            method:"POST",
            body:{ full_name: fullName, email, password }
        });
        status("Administrador creado. Iniciando sesion...", "ok");
        await login(email, password);
        await refreshData();
        setView(resolveViewFromPath(), false);
    } catch(err){
        if(err.status === 409){
            await loadSetupStatus();
            status("Ya existe un administrador. Inicia sesion con tus credenciales.", "error");
            return;
        }
        status(err.message || "No se pudo crear el primer administrador.", "error");
    }
});

document.getElementById("workerForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const f = new FormData(e.target);
    const workerId = String(f.get("worker_id") || "").trim();
    const doc = String(f.get("document_number") || "").trim();
    if(doc.length < 8){ status("Documento invalido: usa al menos 8 caracteres.", "error"); return; }
    const payload = {
        document_type:f.get("document_type") || "CEDULA",
        document_number:doc,
        first_name:f.get("first_name"),
        last_name:f.get("last_name"),
        birth_date:f.get("birth_date"),
        sex:f.get("sex"),
        email:compactText(f.get("email")),
        phone:compactText(f.get("phone")),
        blood_type:compactText(f.get("blood_type")),
        laterality:compactText(f.get("laterality")),
        company_id:f.get("company_id")||null,
        job_position_id:f.get("job_position_id")||null
    };
    try{
        if(workerId){
            await api(`/api/workers/${workerId}`,{method:"PUT", body:payload});
            await loadWorkerHistory(workerId);
            status("Trabajador actualizado.", "ok");
            setWorkerStep("manage");
        } else {
            const res = await api("/api/workers",{method:"POST", body:payload});
            status("Trabajador creado.", "ok");
            if(res?.data?.id){ await loadWorkerHistory(res.data.id); }
            resetWorkerForm(true);
            setWorkerStep("recent");
        }
        renderWorkerHistory();
        await refreshData();
    } catch(err){ status(err.message || "No se pudo guardar trabajador.", "error"); }
});

refs.workerCreateBtn.addEventListener("click", () => {
    resetWorkerForm();
    setWorkerStep("manage");
});

refs.workerFormResetBtn.addEventListener("click", () => {
    resetWorkerForm(true);
    status("Edicion cancelada. Puedes crear un nuevo trabajador.", "ok");
});

async function handleWorkerAction(workerId, action){
    if(!workerId) return;
    if(action === "view-worker"){
        await loadWorkerHistory(workerId);
        renderWorkerHistory();
        setWorkerStep("history");
        status("Historial cargado.", "ok");
        return;
    }
    if(action === "edit-worker"){
        await loadWorkerHistory(workerId);
        renderWorkerHistory();
        setWorkerStep("manage");
        status("Trabajador listo para edicion.", "ok");
        return;
    }
    if(action === "delete-worker"){
        const ok = window.confirm("Se eliminara el trabajador y su historial asociado. Continuar?");
        if(!ok) return;
        await api(`/api/workers/${workerId}`, { method:"DELETE" });
        if(state.selectedWorkerId === workerId){
            state.selectedWorkerId = null;
            state.selectedWorkerHistory = null;
            renderWorkerHistory();
        }
        status("Trabajador eliminado.", "ok");
        state.pagination.workers.page = 1;
        await refreshData();
        setWorkerStep("recent");
    }
}

refs.workerClinicalForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const f = new FormData(e.target);
    const workerId = f.get("worker_id");
    if(!workerId){ status("Primero selecciona un trabajador para historia clinica.", "error"); return; }
    const payload = {
        personal_background: compactText(f.get("personal_background")),
        family_background: compactText(f.get("family_background")),
        allergies: compactText(f.get("allergies")),
        current_medication: compactText(f.get("current_medication")),
        pathological_history: compactText(f.get("pathological_history")),
        surgical_history: compactText(f.get("surgical_history")),
        occupational_history: compactText(f.get("occupational_history")),
        lifestyle_notes: compactText(f.get("lifestyle_notes")),
        longitudinal_notes: compactText(f.get("longitudinal_notes")),
    };
    try{
        await api(`/api/workers/${workerId}/clinical-history`, { method:"PUT", body:payload });
        await loadWorkerHistory(workerId);
        renderWorkerHistory();
        status("Historia clinica ampliada guardada.", "ok");
    } catch(err){ status(err.message || "No se pudo guardar historia clinica.", "error"); }
});

document.getElementById("evaluationForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const f = new FormData(e.target);
    const payload = {
        worker_id:f.get("worker_id"),
        evaluation_type:f.get("evaluation_type"),
        consultation_reason:f.get("consultation_reason"),
        medical_aptitude:f.get("medical_aptitude"),
        professional_name:f.get("professional_name"),
        professional_code:f.get("professional_code"),
        vital_signs:{pa:"120/80",fc:72},
        diagnoses:[{code:"Z00.0", diagnosis_type:"DEF", notes:"Control de rutina"}]
    };
    try{
        const res = await api("/api/evaluations",{method:"POST", body:payload});
        if(res?.data?.worker_id){ await loadWorkerHistory(res.data.worker_id); }
        status("Evaluacion creada.", "ok");
        await refreshData();
    } catch(err){ status(err.message || "No se pudo crear evaluacion.", "error"); }
});

document.getElementById("certificateForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const f = new FormData(e.target);
    try{
        await api(`/api/certificates/from-evaluation/${f.get("evaluation_id")}`,{method:"POST", body:{observations:f.get("observations"), recommendations:f.get("recommendations")}});
        status("Certificado creado.", "ok");
        await refreshData();
    } catch(err){ status(err.message || "No se pudo crear certificado.", "error"); }
});

document.getElementById("attachmentForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const f = new FormData(e.target);
    const data = new FormData();
    data.append("file", f.get("file"));
    data.append("attachment_type", f.get("attachment_type") || "GENERAL");
    data.append("exam_date", f.get("exam_date") || "");
    data.append("notes", f.get("notes") || "");
    try{
        await api(`/api/evaluations/${f.get("evaluation_id")}/attachments`,{method:"POST", body:data, form:true});
        status("Examen/adjunto cargado.", "ok");
        e.target.reset();
        await refreshData();
    } catch(err){ status(err.message || "No se pudo subir adjunto.", "error"); }
});

refs.userForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const f = new FormData(e.target);
    const payload = {
        full_name: String(f.get("full_name") || "").trim(),
        email: String(f.get("email") || "").trim(),
        password: String(f.get("password") || ""),
        role_name: f.get("role_name"),
        is_active: f.get("is_active") === "1",
    };
    if(payload.full_name.length < 3){ status("Nombre de usuario invalido.", "error"); return; }
    if(payload.password.length < 8){ status("Contrasena invalida: minimo 8 caracteres.", "error"); return; }
    try{
        await api("/api/users", { method:"POST", body:payload });
        status("Usuario creado.", "ok");
        e.target.reset();
        await refreshData();
    } catch(err){ status(err.message || "No se pudo crear usuario.", "error"); }
});

refs.userEditForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const f = new FormData(e.target);
    const userId = f.get("user_id");
    if(!userId){ status("Selecciona un usuario para editar.", "error"); return; }
    const payload = {
        full_name: String(f.get("full_name") || "").trim(),
        email: String(f.get("email") || "").trim(),
        role_name: f.get("role_name"),
        is_active: f.get("is_active") === "1",
    };
    const password = String(f.get("password") || "");
    if(password.trim() !== "") payload.password = password;
    try{
        await api(`/api/users/${userId}`, { method:"PUT", body:payload });
        status("Usuario actualizado.", "ok");
        await refreshData();
    } catch(err){ status(err.message || "No se pudo actualizar usuario.", "error"); }
});

refs.usersBody.addEventListener("click", async (e) => {
    const btn = e.target.closest("button[data-act]");
    if(!btn) return;
    const userId = btn.getAttribute("data-id");
    const act = btn.getAttribute("data-act");
    if(!userId) return;

    if(act === "edit-user"){
        const user = state.users.find(x => x.id === userId);
        if(!user){ status("Usuario no encontrado en memoria.", "error"); return; }
        fillUserEditForm(user);
        status(`Editando usuario: ${user.full_name}`, "ok");
        return;
    }

    if(act === "toggle-user"){
        const next = btn.getAttribute("data-next") === "1";
        try{
            await api(`/api/users/${userId}/status`, { method:"PUT", body:{ is_active: next } });
            status(next ? "Usuario activado." : "Usuario desactivado.", "ok");
            await refreshData();
        } catch(err){ status(err.message || "No se pudo actualizar estado del usuario.", "error"); }
        return;
    }

    if(act === "reset-user-password"){
        const email = btn.getAttribute("data-email") || "";
        const ok = window.confirm(`Se generara una clave temporal para ${email}. Continuar?`);
        if(!ok) return;
        try{
            const res = await api(`/api/users/${userId}/reset-password`, { method:"PUT", body:{} });
            const temp = res?.data?.temporary_password;
            if(temp){
                window.prompt(`Clave temporal para ${email}. Copiala y compartela de forma segura:`, temp);
                status(`Clave temporal generada para ${email}.`, "ok");
            } else {
                status(`Contrasena restablecida para ${email}.`, "ok");
            }
        } catch(err){ status(err.message || "No se pudo restablecer contrasena del usuario.", "error"); }
    }
});

refs.tabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const next = tab.getAttribute("data-view");
        if(next) setView(next, true);
    });
});

refs.workerFlowTabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const nextStep = tab.getAttribute("data-worker-step");
        if(nextStep) setWorkerStep(nextStep);
    });
});

window.addEventListener("popstate", () => {
    setView(resolveViewFromPath(), false);
});

refs.workersPrevBtn.addEventListener("click", async () => {
    if(!state.pagination.workers.has_prev) return;
    state.pagination.workers.page -= 1;
    await refreshData();
});
refs.workersNextBtn.addEventListener("click", async () => {
    if(!state.pagination.workers.has_next) return;
    state.pagination.workers.page += 1;
    await refreshData();
});
refs.evaluationsPrevBtn.addEventListener("click", async () => {
    if(!state.pagination.evaluations.has_prev) return;
    state.pagination.evaluations.page -= 1;
    await refreshData();
});
refs.evaluationsNextBtn.addEventListener("click", async () => {
    if(!state.pagination.evaluations.has_next) return;
    state.pagination.evaluations.page += 1;
    await refreshData();
});
refs.certificatesPrevBtn.addEventListener("click", async () => {
    if(!state.pagination.certificates.has_prev) return;
    state.pagination.certificates.page -= 1;
    await refreshData();
});
refs.certificatesNextBtn.addEventListener("click", async () => {
    if(!state.pagination.certificates.has_next) return;
    state.pagination.certificates.page += 1;
    await refreshData();
});
refs.usersPrevBtn.addEventListener("click", async () => {
    if(!state.pagination.users.has_prev) return;
    state.pagination.users.page -= 1;
    await refreshData();
});
refs.usersNextBtn.addEventListener("click", async () => {
    if(!state.pagination.users.has_next) return;
    state.pagination.users.page += 1;
    await refreshData();
});

refs.workerSearchBtn.addEventListener("click", async () => {
    state.workerQuery = refs.workerSearchInput.value.trim();
    state.pagination.workers.page = 1;
    await refreshData();
});

refs.workerSearchInput.addEventListener("keydown", async (e) => {
    if(e.key !== "Enter") return;
    e.preventDefault();
    state.workerQuery = refs.workerSearchInput.value.trim();
    state.pagination.workers.page = 1;
    await refreshData();
});

refs.evaluationFilterForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    state.evaluationFilters = {
        evaluation_type: fd.get("evaluation_type") || "",
        medical_aptitude: fd.get("medical_aptitude") || "",
        date_from: fd.get("date_from") || "",
        date_to: fd.get("date_to") || ""
    };
    state.pagination.evaluations.page = 1;
    await refreshData();
});

refs.certificateFilterForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    state.certificateFilters = {
        medical_aptitude: fd.get("medical_aptitude") || "",
        date_from: fd.get("date_from") || "",
        date_to: fd.get("date_to") || ""
    };
    state.pagination.certificates.page = 1;
    await refreshData();
});

refs.workersExportBtn.addEventListener("click", () => {
    if(!state.workers.length){ status("No hay trabajadores para exportar en la pagina actual.", "error"); return; }
    const rows = state.workers.map(w => [
        w.document_number || "",
        `${w.first_name || ""} ${w.last_name || ""}`.trim(),
        w.company?.business_name || w.business_name || "",
        w.history_number || "",
        w.file_number || "",
    ]);
    exportCsv(`trabajadores-pagina-${state.pagination.workers.page}.csv`, ["Documento","Nombre","Empresa","Historia","Archivo"], rows);
    status("Exportacion CSV de trabajadores completada.", "ok");
});

refs.evaluationsExportBtn.addEventListener("click", () => {
    if(!state.evaluations.length){ status("No hay evaluaciones para exportar en la pagina actual.", "error"); return; }
    const rows = state.evaluations.map(e => {
        const w = e.worker || {};
        return [
            fmtDate(e.attention_date),
            `${w.first_name || ""} ${w.last_name || ""}`.trim(),
            e.evaluation_type || "",
            e.medical_aptitude || "",
            e.professional_name || "",
        ];
    });
    exportCsv(`evaluaciones-pagina-${state.pagination.evaluations.page}.csv`, ["Fecha","Trabajador","Tipo","Aptitud","Profesional"], rows);
    status("Exportacion CSV de evaluaciones completada.", "ok");
});

refs.certificatesExportBtn.addEventListener("click", () => {
    if(!state.certificates.length){ status("No hay certificados para exportar en la pagina actual.", "error"); return; }
    const rows = state.certificates.map(c => {
        const w = c.worker || {};
        return [
            c.certificate_code || "",
            fmtDate(c.issue_date),
            `${w.first_name || ""} ${w.last_name || ""}`.trim(),
            c.medical_aptitude || "",
            c.observations || "",
        ];
    });
    exportCsv(`certificados-pagina-${state.pagination.certificates.page}.csv`, ["Codigo","Fecha","Trabajador","Aptitud","Observaciones"], rows);
    status("Exportacion CSV de certificados completada.", "ok");
});

refs.usersExportBtn.addEventListener("click", () => {
    if(!state.users.length){ status("No hay usuarios para exportar en la pagina actual.", "error"); return; }
    const rows = state.users.map(u => [
        u.full_name || "",
        u.email || "",
        u.roles?.[0] || "",
        u.is_active ? "Activo" : "Inactivo",
        fmtDate(u.created_at),
    ]);
    exportCsv(`usuarios-pagina-${state.pagination.users.page}.csv`, ["Nombre","Email","Rol","Estado","Creado"], rows);
    status("Exportacion CSV de usuarios completada.", "ok");
});

async function onWorkerTableClick(e){
    const btn = e.target.closest("button[data-act]");
    if(!btn) return;
    const workerId = btn.getAttribute("data-worker-id");
    const action = btn.getAttribute("data-act");
    try{
        await handleWorkerAction(workerId, action);
    } catch(err){
        status(err.message || "No se pudo completar la accion del trabajador.", "error");
    }
}

refs.workersBody.addEventListener("click", onWorkerTableClick);
refs.workersManageBody.addEventListener("click", onWorkerTableClick);

refs.workerHistoryEval.addEventListener("click", async (e) => {
    const btn = e.target.closest("button[data-act='download-attachment']");
    if(!btn) return;
    const attachmentId = btn.getAttribute("data-attachment-id");
    const fileName = btn.getAttribute("data-file-name") || `adjunto-${attachmentId}`;
    if(!attachmentId) return;
    try{
        await downloadWithToken(`/api/evaluations/attachments/${attachmentId}/download`, fileName);
        status("Adjunto descargado.", "ok");
    } catch(err){ status(err.message || "No se pudo descargar adjunto.", "error"); }
});

refs.certificatesBody.addEventListener("click", async (e)=>{
    const b = e.target.closest("button[data-act]"); if(!b) return;
    const id = b.getAttribute("data-id"); const act = b.getAttribute("data-act");
    try{
        if(act==="gen"){ await api(`/api/certificates/${id}/generate-pdf`,{method:"POST"}); status("PDF generado.", "ok"); await refreshData(); return; }
        if(act==="down"){
            await downloadWithToken(`/api/certificates/${id}/download-pdf`, `certificado-${id}.pdf`);
            status("PDF descargado.", "ok");
        }
    } catch(err){ status(err.message || "Operacion no completada.", "error"); }
});

refs.refreshBtn.addEventListener("click", refreshData);
refs.logoutBtn.addEventListener("click", logout);

(async function init(){
    setView(resolveViewFromPath(), false);
    const t = localStorage.getItem("shcso_token");
    if(!t){ await prepareLoginSection(); return; }
    state.token=t;
    await refreshData();
    setView(resolveViewFromPath(), false);
})();
</script>
</body>
</html>
