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
        .operationFlow { display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px; }
        .operationFlowTab { border:1px solid var(--line); background:#fff; color:var(--ink); border-radius:999px; padding:8px 12px; cursor:pointer; font-weight:700; }
        .operationFlowTab.active { background:#0f8b8d; border-color:#0f8b8d; color:#fff; }
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
        .btn.danger { background:#e53935; color:#fff; }
        .btn.danger:hover { background:#c62828; }
        .tableCompact { font-size:.82rem; }
        .tableCompact th { background:var(--color-bg-alt,#f5f5f5); font-weight:600; padding:6px 8px; text-align:left; border-bottom:2px solid var(--color-border,#ddd); }
        .tableCompact td { padding:5px 8px; border-bottom:1px solid var(--color-border,#ddd); vertical-align:middle; }
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
        .workerFormLocked { opacity:.78; }
        .workerFormLocked .field label { color:#7a8a90; }
        .meta { margin:4px 0; color:var(--muted); font-size:.86rem; }
        .meta strong { color:var(--ink); }
        .historyList { display:grid; gap:8px; }
        .historyCard { border:1px solid #e4ece9; border-radius:10px; padding:10px; background:#fbfefd; }
        .subCard { border:1px solid #dde9e4; border-radius:12px; padding:16px; background:#f9fdfb; }
        .subCardTitle { font-size:.9rem; font-weight:700; color:var(--ink); margin-bottom:12px; padding-bottom:6px; border-bottom:2px solid var(--accent); display:inline-block; }
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
        .operationStepPanel { width:100%; }
        .grid3[data-worker-panel-host] > .view-operations.operationStepPanel,
        .grid2[data-worker-panel-host] > .view-operations.operationStepPanel { grid-column:1 / -1; }
        .operationHint { margin:-3px 0 10px; font-size:.78rem; color:#3f5f67; }
        .consultBlock { border:1px solid #d7e4df; border-radius:12px; padding:12px; background:#fbfefd; margin-bottom:10px; }
        .consultHead { margin:0 0 10px; font-size:.86rem; text-transform:uppercase; letter-spacing:.04em; color:#0e5a5e; }
        .consultGrid2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .consultGrid3 { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
        .consultGridVitals { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
        .consultGridSoap { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .diagnosisSearchWrap { border:1px solid #d3dfec; border-radius:10px; background:#f5f8ff; padding:10px; }
        .diagnosisResults { margin-top:8px; display:grid; gap:6px; }
        .diagnosisResultItem { display:flex; justify-content:space-between; align-items:center; gap:10px; border:1px solid #d7e4ef; border-radius:8px; background:#fff; padding:7px 8px; }
        .diagnosisSelected { display:grid; gap:8px; }
        .diagnosisSelectedItem { border:1px solid #d7e4ef; border-radius:8px; background:#fff; padding:8px; display:grid; grid-template-columns:1.5fr 1fr auto; gap:8px; align-items:end; }
        .prescriptionGrid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:10px; }
        .prescriptionList { display:grid; gap:8px; margin-top:10px; }
        .prescriptionItem { border:1px solid #d7e4df; border-radius:8px; background:#fff; padding:8px; display:grid; grid-template-columns:2fr 1fr 1fr 1fr 2fr auto; gap:8px; align-items:center; }
        .rxMedItem { padding:8px 12px; cursor:pointer; font-size:.88rem; border-bottom:1px solid #f0f4f8; }
        .rxMedItem:last-child { border-bottom:none; }
        .rxMedItem:hover { background:#eef4ff; }
        .rxMedItem strong { display:block; color:#0e5a5e; }
        .rxMedItem span { color:#7a8fa6; font-size:.8rem; }
        .autocompleteDropdown { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #cde0d9; border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,.12); z-index:500; max-height:200px; overflow-y:auto; }
        .autocompleteDropdown.hidden { display:none; }
        /* Modal overlay */
        .modalOverlay { position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; display:flex; align-items:center; justify-content:center; }
        .modalBox { background:#fff; border-radius:14px; padding:24px; max-width:520px; width:94%; box-shadow:0 8px 32px rgba(0,0,0,.18); position:relative; max-height:90vh; overflow-y:auto; }
        .modalBox h3 { margin:0 0 16px; font-size:1.05rem; color:#0e5a5e; }
        .modalClose { position:absolute; top:12px; right:14px; background:none; border:none; font-size:1.3rem; cursor:pointer; color:#7a8fa6; }
        .soapHelp { display:grid; gap:12px; }
        .soapHelpItem { border-left:3px solid #0e5a5e; padding-left:12px; }
        .soapHelpItem strong { display:block; color:#0e5a5e; margin-bottom:3px; }
        .soapHelpItem p { margin:0; font-size:.88rem; color:#444; }
        .profileWarnBanner { background:#fff8e1; border:1px solid #ffe082; border-radius:8px; padding:10px 14px; margin-bottom:12px; font-size:.88rem; color:#6d4c00; display:flex; align-items:center; gap:8px; }
        .helpBtn { background:none; border:1px solid #b0c8c8; border-radius:50%; width:20px; height:20px; font-size:.75rem; cursor:pointer; color:#0e5a5e; line-height:1; padding:0; margin-left:6px; vertical-align:middle; }
        .helpBtn:hover { background:#e8f5f5; }
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
        @media (max-width:980px) { .toolbar{grid-template-columns:1fr 1fr;} .toolbar.compact{grid-template-columns:1fr;} .consultGrid2{grid-template-columns:1fr;} .consultGrid3{grid-template-columns:1fr;} .consultGridVitals{grid-template-columns:1fr 1fr;} .consultGridSoap{grid-template-columns:1fr;} .prescriptionGrid{grid-template-columns:1fr 1fr;} .prescriptionItem{grid-template-columns:1fr 1fr;} .diagnosisSelectedItem{grid-template-columns:1fr;} }
        @media (max-width:720px) { .top{flex-direction:column; align-items:flex-start;} .actions{width:100%;} .actions .btn{flex:1;} .tabs{width:100%;} .tabs .tab{flex:1;} .workerFlow .workerFlowTab,.operationFlow .operationFlowTab{flex:1;} .workerFormGrid{grid-template-columns:1fr;} .workerFormGrid [class*="span-"]{grid-column:span 1;} .workerManagePanel{padding:14px;} .workerManagePanel table{min-width:680px;} }
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
            <button id="miPerfilBtn" class="btn small hidden" type="button">👤 Mi Perfil</button>
            <a href="/api/manual/download" target="_blank" class="btn small" title="Descargar manual de usuario" style="text-decoration:none;">📖 Manual</a>
            <button id="refreshBtn" class="btn hidden" type="button">Refrescar</button>
            <button id="logoutBtn" class="btn warn hidden" type="button">Cerrar sesion</button>
        </div>
    </header>

    <div id="statusBox" class="status">Inicia sesion para cargar la informacion.</div>

    <section id="loginSection" class="card login">
        <h2 class="section">Acceso</h2>
        <form id="loginForm" method="post" onsubmit="return false;">
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
            <button class="tab" data-view="agenda" type="button">📅 Agenda</button>
            <button class="tab" data-view="workers" type="button">Trabajadores</button>
            <button class="tab" data-view="operations" type="button">Operacion</button>
            <button class="tab" data-view="users" type="button">Usuarios</button>
            <button class="tab" data-view="empresa" type="button">🏢 Empresas</button>
            <button class="tab" data-view="settings" type="button">⚙️ Configuración</button>
        </nav>
        <nav class="workerFlow view-workers">
            <button class="workerFlowTab active" data-worker-step="recent" type="button">1. Trabajadores recientes</button>
            <button class="workerFlowTab" data-worker-step="manage" type="button">2. Nuevo trabajador</button>
            <button class="workerFlowTab" data-worker-step="clinical" type="button">3. Historia clinica ampliada</button>
            <button class="workerFlowTab" data-worker-step="history" type="button">4. Historial clinico</button>
            <button class="workerFlowTab" data-worker-step="evolutions" type="button">5. Evoluciones y Prescripciones</button>
            <button class="workerFlowTab" data-worker-step="studies" type="button">6. Estudios Médicos</button>
            <button class="workerFlowTab" data-worker-step="vaccines" type="button">7. Vacunación</button>
            <button class="workerFlowTab" data-worker-step="accidents" type="button">8. Accidentes Laborales</button>
        </nav>
        <nav class="operationFlow view-operations">
            <button class="operationFlowTab active" data-operation-step="consult" type="button">1. Consulta medica</button>
            <button class="operationFlowTab" data-operation-step="certificate" type="button">2. Certificado y adjunto</button>
            <button class="operationFlowTab" data-operation-step="evaluations" type="button">3. Evaluaciones recientes</button>
            <button class="operationFlowTab" data-operation-step="certificates" type="button">4. Certificados recientes</button>
        </nav>

        <div id="statsGrid" class="stats view-dashboard"></div>

        <!-- Alertas: certificados por vencer -->
        <div id="alertsBanner" class="view-dashboard" style="display:none;margin-bottom:1rem;">
            <article class="card" style="border-left:4px solid #e53935;">
                <h2 class="section" style="color:#e53935;">🔔 Alertas — Certificados por vencer
                    <span id="alertsCount" class="sectionBadge" style="background:#e53935;color:#fff;">0</span>
                </h2>
                <div id="alertsList" style="font-size:.84rem;"></div>
                <p id="alertsEmpty" class="hint" style="display:none;">Sin certificados próximos a vencer.</p>
            </article>
        </div>

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

        <!-- Widget: Citas de hoy -->
        <div class="view-dashboard" style="margin-bottom:1rem;">
            <article class="card">
                <h2 class="section">📅 Citas de hoy
                    <span id="todayApptsCount" class="sectionBadge">0</span>
                </h2>
                <div id="todayApptsList" style="font-size:.84rem;"></div>
                <p id="todayApptsEmpty" class="hint">Sin citas programadas para hoy.</p>
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

            <article class="card view-operations operationCard operationStepPanel" data-operation-panel="consult">
                <h2 class="section">Nueva consulta medica <span class="sectionBadge">flujo 1/2</span></h2>
                <p class="operationHint">Registra la consulta estructurada (SOAP) para habilitar certificado y adjuntos.</p>
                <form id="evaluationForm">
                    <div class="consultBlock">
                        <h3 class="consultHead">Paciente</h3>
                        <div class="consultGrid2">
                            <div class="field"><label>Buscar</label><input id="evaluationWorkerSearch" type="text" placeholder="Filtrar por nombre o cedula"></div>
                            <div class="field"><label>Paciente *</label><select id="evaluationWorker" name="worker_id" required></select></div>
                        </div>
                    </div>
                    <div class="consultBlock">
                        <h3 class="consultHead">Signos vitales</h3>
                        <div class="consultGridVitals">
                            <div class="field"><label>Presion arterial</label><input name="vs_bp" placeholder="120/80"></div>
                            <div class="field"><label>Temperatura (C)</label><input name="vs_temp" type="number" step="0.1" min="30" max="45" placeholder="36.5"></div>
                            <div class="field"><label>Frecuencia cardiaca</label><input name="vs_hr" type="number" min="20" max="250" placeholder="72"></div>
                            <div class="field"><label>Frecuencia respiratoria</label><input name="vs_rr" type="number" min="6" max="80" placeholder="16"></div>
                            <div class="field"><label>Peso (kg)</label><input name="vs_weight" type="number" step="0.1" min="1" max="500" placeholder="70"></div>
                            <div class="field"><label>Talla (cm)</label><input name="vs_height" type="number" step="0.1" min="30" max="260" placeholder="170"></div>
                        </div>
                    </div>
                    <div class="consultBlock">
                        <h3 class="consultHead">Metodo SOAP <button class="helpBtn" id="soapHelpBtn" type="button" title="¿Que es SOAP?">?</button></h3>
                        <div class="consultGridSoap">
                            <div class="field"><label>S - Subjetivo *</label><textarea name="soap_s" placeholder="Motivo de consulta y sintomas del paciente" required></textarea></div>
                            <div class="field"><label>O - Objetivo *</label><textarea name="soap_o" placeholder="Hallazgos fisicos, signos y resultados relevantes" required></textarea></div>
                        </div>
                        <div class="diagnosisSearchWrap">
                            <div class="field"><label>Buscador CIE-10</label><input id="diagnosisSearchInput" type="text" placeholder="Buscar codigo o descripcion (ej: J06, lumbalgia)"></div>
                            <div id="diagnosisSearchResults" class="diagnosisResults"></div>
                        </div>
                        <div id="selectedDiagnosesList" class="diagnosisSelected"><p class="empty">Sin diagnosticos seleccionados.</p></div>
                        <div class="consultGridSoap">
                            <div class="field"><label>A - Analisis (Diagnostico) *</label><textarea name="soap_a" placeholder="Analisis clinico, diagnostico principal y diferenciales" required></textarea></div>
                            <div class="field"><label>P - Plan (Tratamiento) *</label><textarea name="soap_p" placeholder="Plan terapeutico, indicaciones y seguimiento" required></textarea></div>
                        </div>
                    </div>
                    <div class="consultBlock">
                        <h3 class="consultHead">Receta medica (opcional)</h3>
                        <div class="prescriptionGrid">
                            <div class="field"><label>Medicamento *</label>
                                <div style="position:relative;">
                                    <input id="rxMedication" type="text" placeholder="Paracetamol, Amoxicilina..." autocomplete="off">
                                    <div id="rxMedicationResults" style="display:none;position:absolute;z-index:100;left:0;right:0;background:#fff;border:1px solid #d3dfec;border-radius:8px;box-shadow:0 4px 14px rgba(0,0,0,.12);max-height:220px;overflow-y:auto;margin-top:2px;"></div>
                                </div>
                            </div>
                            <div class="field"><label>Dosis *</label><input id="rxDosage" type="text" placeholder="500 mg"></div>
                            <div class="field"><label>Frecuencia</label><input id="rxFrequency" type="text" placeholder="Cada 8 horas"></div>
                            <div class="field"><label>Duracion</label><input id="rxDuration" type="text" placeholder="7 dias"></div>
                        </div>
                        <div class="field"><label>Indicaciones</label><input id="rxIndications" type="text" placeholder="Tomar despues de comidas"></div>
                        <button id="addPrescriptionBtn" class="btn" type="button">+ Agregar medicamento</button>
                        <div id="prescriptionList" class="prescriptionList"><p class="empty">Sin medicamentos agregados.</p></div>
                    </div>
                    <div class="consultGrid3">
                        <div class="field"><label>Tipo</label><select name="evaluation_type"><option>INGRESO</option><option>PERIODICO</option><option>REINTEGRO</option><option>RETIRO</option></select></div>
                        <div class="field"><label>Aptitud</label><select name="medical_aptitude"><option>APTO</option><option>APTO_OBSERVACION</option><option>APTO_LIMITACIONES</option><option>NO_APTO</option></select></div>
                        <div class="field"><label>Fecha atencion</label><input name="attention_date" type="date"></div>
                        <div class="field"><label>Profesional</label><input id="evalProfName" name="professional_name" required></div>
                        <div class="field"><label>Codigo profesional</label><input id="evalProfCode" name="professional_code" required></div>
                    </div>
                    <button class="btn accent" type="submit">Guardar consulta</button>
                </form>
            </article>

            <article class="card view-operations operationCard operationStepPanel" data-operation-panel="certificate">
                <h2 class="section">Certificado y adjunto <span class="sectionBadge">flujo 2/2</span></h2>
                <p class="operationHint">Genera el certificado desde una evaluacion y luego carga evidencia documental.</p>
                <form id="certificateForm">
                    <div class="field"><label>Evaluacion</label><select id="certificateEvaluation" name="evaluation_id" required></select></div>
                    <div class="field"><label>Observaciones</label><textarea name="observations">Apto para labores</textarea></div>
                    <div class="field"><label>Recomendaciones</label><textarea name="recommendations">Control anual</textarea></div>
                    <button id="certificateCreateBtn" class="btn accent" type="submit">Crear certificado</button>
                </form>
                <p id="certificateFlowHint" class="hint" style="margin:8px 0 0;"></p>
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
                    <div class="field"><label>Empresa</label>
                        <select id="workerCompanyFilter" style="min-width:140px;">
                            <option value="">Todas</option>
                        </select>
                    </div>
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
            <article class="card view-operations operationCard operationStepPanel" data-operation-panel="evaluations">
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

            <!-- Exportación Excel -->
            <article class="card view-operations operationCard operationStepPanel" data-operation-panel="evaluations" style="margin-top:1rem;">
                <h2 class="section">📊 Exportar a Excel</h2>
                <div class="toolbar" style="flex-wrap:wrap;gap:.6rem;align-items:flex-end;">
                    <div class="field">
                        <label>Tipo de reporte</label>
                        <select id="xlsType">
                            <option value="evaluations">Evaluaciones</option>
                            <option value="workers">Trabajadores</option>
                            <option value="certificates">Certificados</option>
                            <option value="accidents">Accidentes</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Desde</label>
                        <input type="date" id="xlsFrom">
                    </div>
                    <div class="field">
                        <label>Hasta</label>
                        <input type="date" id="xlsTo">
                    </div>
                    <button class="btn accent" type="button" id="xlsExportBtn">⬇️ Descargar Excel</button>
                </div>
                <p id="xlsMsg" class="hint" style="margin-top:.4rem;"></p>
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
            <h2 class="section">Historial clinico del trabajador
                <button id="workerCardBtn" class="btn small" type="button" style="float:right;font-size:.78rem;margin-left:6px;" title="Descargar carnet del trabajador en PDF">🪪 Carnet</button>
                <button id="workerHistoryPdfBtn" class="btn small" type="button" style="float:right;font-size:.78rem;" title="Descargar historia clínica completa en PDF">📄 HC PDF</button>
            </h2>
            <div id="workerHistoryEval" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
            <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
            <div id="workerHistoryCert" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
            <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
            <h3 class="section" style="font-size:.9rem;">Linea de tiempo clinica</h3>
            <div id="workerTimeline" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
        </article>

        <article class="card view-workers workerStepPanel" data-worker-panel="evolutions">
            <h2 class="section">Seguimiento del Trabajador <span class="sectionBadge">seguimiento</span></h2>

            <!-- ===== CARD PRESCRIPCIONES ===== -->
            <div class="subCard" style="margin-bottom:24px;">
                <h3 class="subCardTitle">💊 Prescripciones</h3>

                <!-- Historial de prescripciones de evaluaciones -->
                <div id="workerPrescriptionsList" class="historyList" style="margin-bottom:16px;"><p class="empty">Sin trabajador seleccionado.</p></div>

                <hr style="border:none;border-top:1px solid var(--line);margin:16px 0;">

                <!-- Formulario nueva prescripción -->
                <h4 style="font-size:.85rem;font-weight:600;margin-bottom:10px;color:var(--accent);">➕ Nueva prescripción</h4>
                <form id="prescriptionForm">
                    <div class="consultGrid2" style="margin-bottom:8px;">
                        <div class="field"><label>Evaluación relacionada (opcional)</label>
                            <select id="rxEvaluation"><option value="">-- Sin evaluación --</option></select>
                        </div>
                        <div class="field"><label>Notas / indicaciones generales</label>
                            <input id="rxGeneralNotes" type="text" placeholder="Ej: Tomar con abundante agua...">
                        </div>
                    </div>

                    <!-- Líneas de medicamentos -->
                    <div id="rxMedLines">
                        <!-- template: rxMedLine -->
                    </div>

                    <button class="btn" type="button" id="rxAddMedBtn" style="margin-bottom:10px;">+ Agregar medicamento</button>

                    <!-- Template oculto de línea de medicamento -->
                    <template id="rxMedLineTemplate">
                        <div class="rxMedLine" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 2fr auto;gap:6px;align-items:end;margin-bottom:6px;">
                            <div class="field" style="margin:0;position:relative;">
                                <label>Medicamento</label>
                                <input type="text" class="rxMedInput" placeholder="Buscar medicamento..." autocomplete="off">
                                <div class="rxMedSuggestions autocompleteDropdown hidden"></div>
                            </div>
                            <div class="field" style="margin:0;"><label>Dosis</label><input type="text" class="rxDose" placeholder="500mg"></div>
                            <div class="field" style="margin:0;"><label>Frecuencia</label><input type="text" class="rxFreq" placeholder="c/8h"></div>
                            <div class="field" style="margin:0;"><label>Duración</label><input type="text" class="rxDuration" placeholder="7 días"></div>
                            <div class="field" style="margin:0;"><label>Instrucciones</label><input type="text" class="rxInstructions" placeholder="Tomar con alimentos"></div>
                            <div class="field" style="margin:0;"><label>&nbsp;</label><button type="button" class="btn rxRemoveMedBtn" style="color:var(--danger);">✕</button></div>
                        </div>
                    </template>

                    <div class="rowActions">
                        <button class="btn accent" type="submit" id="rxSubmitBtn">💾 Guardar prescripción</button>
                        <button class="btn" type="button" id="rxCancelBtn" style="display:none;">Cancelar edición</button>
                    </div>
                    <input type="hidden" id="rxEditId">
                </form>
            </div>

            <!-- ===== CARD EVOLUCIONES ===== -->
            <div class="subCard">
                <h3 class="subCardTitle">📋 Evoluciones clínicas</h3>

                <div id="workerEvolutionsList" class="historyList" style="margin-bottom:16px;"><p class="empty">Sin trabajador seleccionado.</p></div>

                <hr style="border:none;border-top:1px solid var(--line);margin:16px 0;">

                <h4 style="font-size:.85rem;font-weight:600;margin-bottom:10px;color:var(--accent);" id="evoFormTitle">➕ Nueva evolución</h4>
                <form id="evolutionForm">
                    <div class="consultGrid2" style="margin-bottom:8px;">
                        <div class="field"><label>Tipo</label>
                            <select id="evoType">
                                <option value="SEGUIMIENTO">Seguimiento</option>
                                <option value="NOTA">Nota clínica</option>
                                <option value="INTERCONSULTA">Interconsulta</option>
                                <option value="URGENCIA">Urgencia</option>
                            </select>
                        </div>
                        <div class="field"><label>Evaluación relacionada (opcional)</label>
                            <select id="evoEvaluation"><option value="">-- Sin evaluación --</option></select>
                        </div>
                    </div>
                    <div class="consultGridSoap" style="margin-bottom:8px;">
                        <div class="field"><label>S - Subjetivo</label><textarea id="evoSubjective" rows="3" placeholder="Síntomas referidos por el paciente"></textarea></div>
                        <div class="field"><label>O - Objetivo</label><textarea id="evoObjective" rows="3" placeholder="Signos vitales y hallazgos"></textarea></div>
                        <div class="field"><label>A - Análisis</label><textarea id="evoAssessment" rows="3" placeholder="Análisis clínico y diagnóstico"></textarea></div>
                        <div class="field"><label>P - Plan</label><textarea id="evoPlan" rows="3" placeholder="Plan terapéutico y seguimiento"></textarea></div>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-bottom:8px;">
                        <div class="field"><label>PA (mmHg)</label><input id="evoBP" type="text" placeholder="120/80"></div>
                        <div class="field"><label>Temp (°C)</label><input id="evoTemp" type="text" placeholder="36.5"></div>
                        <div class="field"><label>FC (lpm)</label><input id="evoHR" type="text" placeholder="72"></div>
                        <div class="field"><label>FR (rpm)</label><input id="evoRR" type="text" placeholder="16"></div>
                        <div class="field"><label>Peso (kg)</label><input id="evoWeight" type="text" placeholder="70"></div>
                        <div class="field"><label>Talla (cm)</label><input id="evoHeight" type="text" placeholder="170"></div>
                    </div>
                    <div class="field" style="margin-bottom:8px;"><label>Notas adicionales</label><textarea id="evoNotes" rows="2" placeholder="Observaciones adicionales"></textarea></div>
                    <div class="rowActions">
                        <button class="btn accent" type="submit" id="evoSubmitBtn">💾 Guardar evolución</button>
                        <button class="btn" type="button" id="evoCancelBtn" style="display:none;">Cancelar edición</button>
                    </div>
                    <input type="hidden" id="evoEditId">
                </form>
            </div>
        </article>

        <!-- ===== TAB 6: ESTUDIOS MÉDICOS ===== -->
        <article class="card view-workers workerStepPanel" data-worker-panel="studies">
            <h2 class="section">Estudios Médicos <span class="sectionBadge">diagnóstico</span></h2>

            <!-- Sub-card: Pedidos de Exámenes -->
            <div class="subCard" style="margin-bottom:24px;">
                <h3 class="subCardTitle">📋 Pedidos de Exámenes</h3>

                <!-- Lista de pedidos -->
                <div id="examOrdersList" class="historyList" style="margin-bottom:16px;"><p class="empty">Sin trabajador seleccionado.</p></div>

                <hr style="border:none;border-top:1px solid var(--line);margin:16px 0;">

                <!-- Formulario nuevo pedido -->
                <h4 style="font-size:.85rem;font-weight:600;margin-bottom:10px;color:var(--accent);" id="examOrderFormTitle">➕ Nuevo pedido</h4>
                <form id="examOrderForm">
                    <div class="consultGrid2" style="margin-bottom:8px;">
                        <div class="field"><label>Tipo de pedido</label>
                            <select id="orderType">
                                <option value="LAB">🔬 Laboratorio clínico</option>
                                <option value="IMAGING">🩻 Imágenes diagnósticas</option>
                                <option value="PATHOLOGY">🔭 Anatomía patológica</option>
                                <option value="FUNCTIONAL">🫁 Pruebas funcionales</option>
                            </select>
                        </div>
                        <div class="field"><label>Prioridad</label>
                            <select id="orderPriority">
                                <option value="NORMAL">Normal</option>
                                <option value="URGENT">⚠️ Urgente</option>
                                <option value="ROUTINE">Rutina</option>
                            </select>
                        </div>
                        <div class="field"><label>Fecha del pedido</label>
                            <input type="date" id="orderDate">
                        </div>
                        <div class="field"><label>Evaluación relacionada (opcional)</label>
                            <select id="orderEvaluation"><option value="">-- Sin evaluación --</option></select>
                        </div>
                    </div>

                    <div class="field" style="margin-bottom:8px;">
                        <label>Indicación clínica</label>
                        <input type="text" id="orderClinicalIndication" placeholder="Ej: Control anual, sospecha de hipoacusia laboral...">
                    </div>

                    <!-- Estudios seleccionados -->
                    <div style="margin-bottom:10px;">
                        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:6px;">Estudios solicitados</label>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                            <select id="studiesPresetType" style="min-width:180px;">
                                <option value="LAB">Laboratorio</option>
                                <option value="IMAGING">Imágenes</option>
                                <option value="PATHOLOGY">Patología</option>
                                <option value="FUNCTIONAL">Funcionales</option>
                            </select>
                            <button type="button" id="addPresetStudyBtn" class="btn">+ Estudio rápido</button>
                            <button type="button" id="addCustomStudyBtn" class="btn">+ Personalizado</button>
                        </div>
                        <!-- Dropdown de estudios predefinidos -->
                        <div id="studyPresetDropdown" class="hidden" style="border:1px solid var(--line);border-radius:8px;background:#fff;max-height:200px;overflow-y:auto;padding:4px 0;margin-bottom:8px;"></div>
                        <div id="examStudiesList" style="display:grid;gap:4px;"></div>
                    </div>

                    <div class="field" style="margin-bottom:8px;">
                        <label>Notas adicionales</label>
                        <textarea id="orderAdditionalNotes" rows="2" placeholder="Observaciones para el laboratorio / centro de imágenes..."></textarea>
                    </div>

                    <div class="rowActions">
                        <button class="btn accent" type="submit" id="orderSubmitBtn">💾 Guardar pedido</button>
                        <button class="btn" type="button" id="orderCancelBtn" style="display:none;">Cancelar edición</button>
                        <input type="hidden" id="orderEditId">
                    </div>
                </form>
            </div>

            <!-- Sub-card: Archivos y Resultados -->
            <div class="subCard">
                <h3 class="subCardTitle">📁 Archivos y Resultados</h3>
                <p class="hint" style="margin-bottom:10px;">Resultados de laboratorio, imágenes (Rx, RM, ecografías), reportes patológicos y archivos DICOM subidos durante las consultas.</p>

                <!-- Filtro por tipo -->
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;" id="attachmentTypeFilters">
                    <button class="btn small active" data-filter="ALL" type="button">Todos</button>
                    <button class="btn small" data-filter="LAB_EXAM" type="button">🔬 Lab</button>
                    <button class="btn small" data-filter="IMAGING" type="button">🩻 Imágenes</button>
                    <button class="btn small" data-filter="DICOM" type="button">💿 DICOM</button>
                    <button class="btn small" data-filter="GENERAL" type="button">📄 General</button>
                    <button class="btn small" data-filter="OTHER" type="button">📎 Otros</button>
                </div>

                <div id="workerAttachmentGallery" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>

                <!-- Upload dentro de la galería -->
                <details style="margin-top:14px;">
                    <summary class="btn" style="display:inline-block;cursor:pointer;">📤 Subir archivo a evaluación</summary>
                    <div style="margin-top:10px;padding:12px;border:1px solid var(--line);border-radius:8px;background:#f9fdfb;">
                        <div class="consultGrid2" style="margin-bottom:8px;">
                            <div class="field"><label>Evaluación</label>
                                <select id="galleryUploadEval"><option value="">-- Seleccionar --</option></select>
                            </div>
                            <div class="field"><label>Tipo</label>
                                <select id="galleryUploadType">
                                    <option value="GENERAL">General</option>
                                    <option value="LAB_EXAM">Laboratorio</option>
                                    <option value="IMAGING">Imagen</option>
                                    <option value="DICOM">DICOM</option>
                                    <option value="OTHER">Otro</option>
                                </select>
                            </div>
                            <div class="field"><label>Fecha del examen (opcional)</label>
                                <input type="date" id="galleryUploadDate">
                            </div>
                            <div class="field"><label>Notas</label>
                                <input type="text" id="galleryUploadNotes" placeholder="Descripción del archivo...">
                            </div>
                        </div>
                        <div class="field" style="margin-bottom:8px;">
                            <label>Archivo (PDF, JPG, PNG, DCM, DICOM, IMA, ZIP — máx 50 MB)</label>
                            <input type="file" id="galleryUploadFile" accept=".pdf,.jpg,.jpeg,.png,.dcm,.dicom,.ima,.zip">
                        </div>
                        <button class="btn accent" type="button" id="galleryUploadBtn">⬆️ Subir archivo</button>
                        <span id="galleryUploadStatus" style="margin-left:10px;font-size:.82rem;"></span>
                    </div>
                </details>
            </div>
        </article>

        <!-- Tab 7: Vacunación Laboral -->
        <article class="card view-workers workerStepPanel" data-worker-panel="vaccines">
            <h2 class="section">💉 Vacunación Laboral</h2>
            <div class="subCard">
                <div class="subCardTitle">Historial de Vacunas</div>
                <div id="vaccinesList"><p class="hint">Sin registros de vacunación.</p></div>
            </div>
            <div class="subCard" style="margin-top:1.2rem;">
                <div class="subCardTitle">Registrar Vacuna</div>
                <form id="vaccineForm">
                    <input type="hidden" id="vaccineId">
                    <div class="fieldRow">
                        <div class="field">
                            <label>Vacuna *</label>
                            <input type="text" id="vaccineNameInput" placeholder="Nombre de la vacuna" autocomplete="off" list="vaccineDatalist">
                            <datalist id="vaccineDatalist"></datalist>
                        </div>
                        <div class="field">
                            <label>Dosis #</label>
                            <input type="number" id="vaccineDoseNumber" min="1" max="10" value="1">
                        </div>
                    </div>
                    <div class="fieldRow">
                        <div class="field">
                            <label>Fecha aplicación *</label>
                            <input type="date" id="vaccineAppliedDate">
                        </div>
                        <div class="field">
                            <label>Próxima dosis</label>
                            <input type="date" id="vaccineNextDoseDate">
                        </div>
                    </div>
                    <div class="fieldRow">
                        <div class="field">
                            <label>Lote</label>
                            <input type="text" id="vaccineLotNumber" placeholder="Número de lote (opcional)">
                        </div>
                        <div class="field">
                            <label>Laboratorio</label>
                            <input type="text" id="vaccineManufacturer" placeholder="Fabricante (opcional)">
                        </div>
                    </div>
                    <div class="field">
                        <label>Notas</label>
                        <textarea id="vaccineNotes" rows="2" placeholder="Observaciones adicionales"></textarea>
                    </div>
                    <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
                        <button class="btn" type="submit" id="vaccineSubmitBtn">💉 Guardar vacuna</button>
                        <button class="btn secondary" type="button" id="vaccineCancelBtn" style="display:none;">✕ Cancelar</button>
                    </div>
                    <p id="vaccineFormMsg" class="formMsg"></p>
                </form>
            </div>
        </article>

        <!-- Tab 8: Accidentes Laborales -->
        <article class="card view-workers workerStepPanel" data-worker-panel="accidents">
            <h2 class="section">⚠️ Accidentes Laborales (AT-01)</h2>
            <div class="subCard">
                <div class="subCardTitle">Registro de Accidentes / Incidentes</div>
                <div id="accidentsList"><p class="hint">Sin accidentes registrados.</p></div>
            </div>
            <div class="subCard" style="margin-top:1.2rem;">
                <div class="subCardTitle" id="accidentFormTitle">Nuevo Reporte AT-01</div>
                <form id="accidentForm">
                    <input type="hidden" id="accidentId">
                    <div class="fieldRow">
                        <div class="field">
                            <label>Fecha del accidente *</label>
                            <input type="date" id="accidentDate">
                        </div>
                        <div class="field">
                            <label>Hora</label>
                            <input type="time" id="accidentTime">
                        </div>
                    </div>
                    <div class="fieldRow">
                        <div class="field">
                            <label>Tipo de evento *</label>
                            <select id="accidentType">
                                <option value="ACCIDENT">Accidente de trabajo</option>
                                <option value="NEAR_MISS">Casi-accidente / Incidente</option>
                                <option value="OCCUPATIONAL_DISEASE">Enfermedad ocupacional</option>
                                <option value="COMMUTING">Accidente in itinere</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Severidad *</label>
                            <select id="accidentSeverity">
                                <option value="MINOR">Leve</option>
                                <option value="MODERATE">Moderado</option>
                                <option value="SERIOUS">Grave</option>
                                <option value="FATAL">Fatal</option>
                            </select>
                        </div>
                    </div>
                    <div class="fieldRow">
                        <div class="field">
                            <label>Lugar del accidente</label>
                            <input type="text" id="accidentLocation" placeholder="Área / sección donde ocurrió">
                        </div>
                        <div class="field">
                            <label>Parte del cuerpo afectada</label>
                            <input type="text" id="accidentBodyPart" placeholder="Ej: mano derecha, columna lumbar">
                        </div>
                    </div>
                    <div class="fieldRow">
                        <div class="field">
                            <label>Tipo de lesión</label>
                            <input type="text" id="accidentInjuryType" placeholder="Ej: corte, fractura, contusión">
                        </div>
                        <div class="field">
                            <label>Días de incapacidad</label>
                            <input type="number" id="accidentLostDays" min="0" value="0">
                        </div>
                    </div>
                    <div class="field">
                        <label>Descripción del evento *</label>
                        <textarea id="accidentDescription" rows="3" placeholder="Describa cómo ocurrió el accidente..."></textarea>
                    </div>
                    <div class="field">
                        <label>Causa inmediata</label>
                        <textarea id="accidentImmediateCause" rows="2" placeholder="Condición o acto inseguro que causó el accidente"></textarea>
                    </div>
                    <div class="field">
                        <label>Causa raíz</label>
                        <textarea id="accidentRootCause" rows="2" placeholder="Factor de gestión o causa básica subyacente"></textarea>
                    </div>
                    <div class="field">
                        <label>Acciones correctivas</label>
                        <textarea id="accidentCorrectiveActions" rows="2" placeholder="Medidas implementadas o programadas"></textarea>
                    </div>
                    <div class="field">
                        <label>Acciones preventivas</label>
                        <textarea id="accidentPreventiveActions" rows="2" placeholder="Medidas para evitar recurrencia"></textarea>
                    </div>
                    <div class="fieldRow">
                        <div class="field">
                            <label>Estado del caso</label>
                            <select id="accidentStatus">
                                <option value="OPEN">Abierto</option>
                                <option value="INVESTIGATING">En investigación</option>
                                <option value="CLOSED">Cerrado</option>
                            </select>
                        </div>
                        <div class="field" style="display:flex;align-items:center;gap:.5rem;padding-top:1.4rem;">
                            <input type="checkbox" id="accidentIessReported" style="width:auto;">
                            <label for="accidentIessReported" style="margin:0;">Reportado al IESS</label>
                        </div>
                    </div>
                    <div class="fieldRow" id="accidentIessRow" style="display:none;">
                        <div class="field">
                            <label>Número AT-01</label>
                            <input type="text" id="accidentAt01Number" placeholder="Nro. formulario IESS">
                        </div>
                        <div class="field">
                            <label>Fecha reporte IESS</label>
                            <input type="date" id="accidentIessDate">
                        </div>
                    </div>
                    <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:.8rem;">
                        <button class="btn" type="submit" id="accidentSubmitBtn">💾 Guardar reporte</button>
                        <button class="btn secondary" type="button" id="accidentCancelBtn" style="display:none;">✕ Cancelar</button>
                    </div>
                    <p id="accidentFormMsg" class="formMsg"></p>
                </form>
            </div>
        </article>

        <!-- Modal: Visor de imágenes (lightbox) -->
        <div id="imageLightboxModal" class="modalOverlay hidden" style="z-index:2000;">
            <div style="background:#000;border-radius:12px;max-width:92vw;max-height:90vh;position:relative;overflow:hidden;">
                <button id="lightboxClose" style="position:absolute;top:8px;right:12px;background:rgba(0,0,0,.7);border:none;color:#fff;font-size:1.4rem;cursor:pointer;z-index:1;border-radius:50%;width:32px;height:32px;">✕</button>
                <img id="lightboxImg" src="" style="max-width:90vw;max-height:88vh;display:block;border-radius:8px;">
                <div id="lightboxCaption" style="color:#ccc;font-size:.8rem;padding:6px 12px;text-align:center;"></div>
            </div>
        </div>

        <!-- Modal: Visor DICOM (dwv.js) -->
        <div id="dicomViewerModal" class="modalOverlay hidden" style="z-index:2000;">
            <div style="background:#1a1a2e;border-radius:12px;width:min(900px,95vw);max-height:92vh;position:relative;overflow:hidden;display:flex;flex-direction:column;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 16px;background:#0f172a;border-bottom:1px solid #334155;">
                    <span style="color:#7dd3fc;font-weight:600;font-size:.9rem;">💿 Visor DICOM</span>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <span id="dicomFileName" style="color:#94a3b8;font-size:.8rem;"></span>
                        <button id="dicomViewerClose" style="background:rgba(255,255,255,.1);border:none;color:#fff;font-size:1.2rem;cursor:pointer;border-radius:50%;width:28px;height:28px;">✕</button>
                    </div>
                </div>
                <div id="dwvContainer" style="flex:1;min-height:500px;position:relative;overflow:hidden;">
                    <div id="dwvLoadingMsg" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#7dd3fc;font-size:.9rem;">Cargando archivo DICOM...</div>
                    <div id="layerGroup0" style="width:100%;height:100%;"></div>
                </div>
                <div style="padding:8px 16px;background:#0f172a;border-top:1px solid #334155;display:flex;gap:8px;flex-wrap:wrap;">
                    <button class="btn small" id="dwvResetBtn" type="button">🔄 Reset</button>
                    <button class="btn small" id="dwvZoomInBtn" type="button">🔍+</button>
                    <button class="btn small" id="dwvZoomOutBtn" type="button">🔍-</button>
                    <span id="dwvToolInfo" style="color:#94a3b8;font-size:.78rem;align-self:center;margin-left:auto;"></span>
                </div>
            </div>
        </div>
        <article class="card view-operations operationCard operationStepPanel" data-operation-panel="certificates">
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

<!-- Modal: Nueva/Editar Empresa -->
<div id="companyModal" class="modalOverlay hidden">
    <div class="modalBox" style="max-width:520px;">
        <button class="modalClose" id="companyModalClose" type="button" title="Cerrar">&times;</button>
        <h3 id="companyModalTitle">🏢 Nueva Empresa</h3>
        <form id="companyForm" autocomplete="off">
            <input type="hidden" id="companyFormId" value="">
            <div class="field"><label>Razón Social <span style="color:var(--error)">*</span></label><input id="companyFormName" name="business_name" type="text" required placeholder="Nombre de la empresa"></div>
            <div class="field"><label>RUC</label><input id="companyFormRuc" name="ruc" type="text" maxlength="13" placeholder="0999999999001"></div>
            <div class="field"><label>Centro de Trabajo</label><input id="companyFormWorkCenter" name="work_center" type="text" placeholder="Ej: Planta Norte"></div>
            <div class="field"><label>Dirección</label><input id="companyFormAddress" name="address" type="text" placeholder="Dirección principal"></div>
            <div class="field"><label>Código CIIU</label><input id="companyFormCiiu" name="ciiu" type="text" maxlength="12" placeholder="Código actividad económica"></div>
            <div style="display:flex;gap:8px;margin-top:12px;">
                <button class="btn accent" type="submit" id="companyFormSubmitBtn">💾 Guardar</button>
                <button class="btn" type="button" id="companyModalCancel">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Mi Perfil -->
<div id="miPerfilModal" class="modalOverlay hidden">
    <div class="modalBox">
        <button class="modalClose" id="miPerfilModalClose" type="button" title="Cerrar">&times;</button>
        <h3>👤 Mi Perfil Profesional</h3>
        <p class="hint" style="margin-bottom:12px;">Actualiza tu nombre y codigo profesional. Estos datos aparecen automaticamente en los formularios de consulta medica.</p>
        <div id="miPerfilWarn" class="profileWarnBanner hidden">
            ⚠️ Tu codigo profesional esta vacio. Completalo para que aparezca en las consultas y recetas medicas.
        </div>
        <form id="miPerfilForm">
            <div class="field"><label>Nombre completo</label><input id="perfilFullName" type="text" required></div>
            <div class="field"><label>Codigo profesional (ej: MED-12345)</label><input id="perfilProfCode" type="text" placeholder="MED-00000"></div>
            <div class="field"><label>Nueva contrasena (dejar en blanco para no cambiar)</label><input id="perfilPassword" type="password" placeholder="Minimo 8 caracteres"></div>
            <button class="btn accent" type="submit">Guardar cambios</button>
        </form>
    </div>
</div>

<!-- ─── EMPRESA (PORTAL POR EMPRESA) ─────────────────────────────── -->
<div class="view-empresa hidden" style="padding:0 8px;">

    <!-- Panel lista de empresas -->
    <div id="empresaListPanel">
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:14px;flex-wrap:wrap;">
            <h2 class="section" style="margin:0;flex:1;">🏢 Portal por Empresa</h2>
            <button class="btn accent" type="button" id="newCompanyBtn">+ Nueva Empresa</button>
            <input id="empresaSearch" type="text" placeholder="Buscar empresa..." style="min-width:200px;max-width:280px;">
            <input id="empresaFilterFrom" type="date" title="Desde">
            <input id="empresaFilterTo"   type="date" title="Hasta">
            <button class="btn accent" type="button" id="empresaFilterBtn">🔍 Aplicar</button>
        </div>
        <div id="empresaGrid" class="grid3" style="margin-bottom:1rem;">
            <div style="color:var(--muted);text-align:center;padding:40px;grid-column:1/-1;">Cargando empresas...</div>
        </div>
    </div>

    <!-- Panel detalle empresa -->
    <div id="empresaDetailPanel" style="display:none;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
            <button class="btn" type="button" id="empresaBackBtn">← Volver</button>
            <div>
                <h2 id="empresaDetailName" class="section" style="margin:0;"></h2>
                <div id="empresaDetailMeta" style="font-size:.8rem;color:var(--muted);margin-top:2px;"></div>
            </div>
            <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap;">
                <input id="empresaDetailFrom" type="date" title="Desde">
                <input id="empresaDetailTo"   type="date" title="Hasta">
                <button class="btn accent" type="button" id="empresaDetailFilterBtn">🔍 Filtrar</button>
                <button class="btn" type="button" id="empresaDetailExcelBtn">📥 Excel</button>
            </div>
        </div>

        <!-- Stats cards empresa -->
        <div id="empresaDetailStats" class="stats" style="margin-bottom:1.2rem;"></div>

        <!-- Distribución aptitud + Tendencia mensual -->
        <div class="grid2" style="margin-bottom:1.2rem;">
            <article class="card">
                <h3 style="font-size:.9rem;font-weight:600;margin:0 0 12px;">📊 Distribución de aptitud</h3>
                <div id="empresaAptitudeDist"></div>
            </article>
            <article class="card">
                <h3 style="font-size:.9rem;font-weight:600;margin:0 0 12px;">📈 Actividad mensual (6m)</h3>
                <div id="empresaMonthlyBars" class="bars" style="min-height:80px;"></div>
            </article>
        </div>

        <!-- Evaluaciones recientes -->
        <article class="card">
            <h3 style="font-size:.9rem;font-weight:600;margin:0 0 12px;">📋 Evaluaciones recientes</h3>
            <div style="overflow-x:auto;">
                <table class="tableCompact" style="width:100%;">
                    <thead>
                        <tr><th>Trabajador</th><th>Doc.</th><th>Fecha</th><th>Tipo</th><th>Aptitud</th></tr>
                    </thead>
                    <tbody id="empresaEvalsBody">
                        <tr><td colspan="5" style="text-align:center;color:var(--muted);">-</td></tr>
                    </tbody>
                </table>
            </div>
        </article>
    </div>
</div>

<!-- ─── AGENDA VIEW ──────────────────────────────────────────────── -->
<div class="view-agenda hidden" style="padding:0 8px;">

    <!-- Widget citas de hoy (también aparece en dashboard) -->
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-start;margin-bottom:1.2rem;">
        <article class="card" style="flex:1;min-width:260px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <h2 class="section" style="margin:0;">📅 Citas de hoy</h2>
                <span id="todayDateLabel" style="font-size:.8rem;color:var(--muted);"></span>
            </div>
            <div id="todayAppointmentsList" style="font-size:.85rem;color:var(--muted);">Cargando...</div>
        </article>

        <article class="card" style="flex:1;min-width:260px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <h2 class="section" style="margin:0;">🔜 Próximas citas</h2>
                <button class="btn" type="button" id="newApptBtn" style="font-size:.78rem;padding:4px 10px;">+ Nueva</button>
            </div>
            <div id="upcomingAppointmentsList" style="font-size:.85rem;color:var(--muted);">Cargando...</div>
        </article>
    </div>

    <!-- Formulario nueva/editar cita -->
    <article class="card view-agenda" id="apptFormCard" style="display:none;margin-bottom:1.2rem;">
        <h3 id="apptFormTitle" style="font-size:.95rem;font-weight:600;margin:0 0 14px;">Nueva cita</h3>
        <form id="apptForm" style="display:grid;gap:10px;max-width:620px;">
            <input type="hidden" id="apptId">
            <div class="field">
                <label>Trabajador <span style="color:red">*</span></label>
                <input id="apptWorkerSearch" type="text" placeholder="Buscar por nombre o cédula..." autocomplete="off">
                <input type="hidden" id="apptWorkerId">
                <div id="apptWorkerResults" style="position:relative;"></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="field"><label>Fecha <span style="color:red">*</span></label><input id="apptDate" type="date" required></div>
                <div class="field"><label>Hora <span style="color:red">*</span></label><input id="apptTime" type="time" required></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="field">
                    <label>Tipo de cita</label>
                    <select id="apptType">
                        <option value="CONSULTA">Consulta general</option>
                        <option value="EXAMEN_PREOCUPACIONAL">Examen preocupacional</option>
                        <option value="EXAMEN_PERIODICO">Examen periódico</option>
                        <option value="EXAMEN_RETIRO">Examen de retiro</option>
                        <option value="SEGUIMIENTO">Seguimiento</option>
                        <option value="INTERCONSULTA">Interconsulta</option>
                        <option value="VACUNACION">Vacunación</option>
                    </select>
                </div>
                <div class="field">
                    <label>Estado</label>
                    <select id="apptStatus">
                        <option value="PROGRAMADA">Programada</option>
                        <option value="CONFIRMADA">Confirmada</option>
                        <option value="ATENDIDA">Atendida</option>
                        <option value="CANCELADA">Cancelada</option>
                        <option value="NO_ASISTIO">No asistió</option>
                    </select>
                </div>
            </div>
            <div class="field"><label>Motivo de la cita</label><input id="apptReason" type="text" placeholder="Ej: Control anual, seguimiento lumbalgia..."></div>
            <div class="field"><label>Notas adicionales</label><textarea id="apptNotes" rows="2" style="resize:vertical;"></textarea></div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn accent" type="submit">💾 Guardar cita</button>
                <button class="btn" type="button" id="apptFormCancel">Cancelar</button>
            </div>
        </form>
    </article>

    <!-- Lista / filtros de citas -->
    <article class="card view-agenda">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;margin-bottom:12px;">
            <h2 class="section" style="margin:0;flex:1;">Lista de citas</h2>
            <div class="field" style="margin:0;min-width:140px;"><label style="font-size:.75rem;">Desde</label><input id="apptFilterFrom" type="date"></div>
            <div class="field" style="margin:0;min-width:140px;"><label style="font-size:.75rem;">Hasta</label><input id="apptFilterTo" type="date"></div>
            <div class="field" style="margin:0;min-width:130px;">
                <label style="font-size:.75rem;">Estado</label>
                <select id="apptFilterStatus">
                    <option value="">Todos</option>
                    <option value="PROGRAMADA">Programada</option>
                    <option value="CONFIRMADA">Confirmada</option>
                    <option value="ATENDIDA">Atendida</option>
                    <option value="CANCELADA">Cancelada</option>
                    <option value="NO_ASISTIO">No asistió</option>
                </select>
            </div>
            <button class="btn accent" type="button" id="apptFilterBtn">🔍 Filtrar</button>
        </div>
        <div style="overflow-x:auto;">
            <table class="tableCompact" style="width:100%;">
                <thead>
                    <tr>
                        <th>Fecha</th><th>Hora</th><th>Trabajador</th><th>Empresa</th>
                        <th>Tipo</th><th>Estado</th><th>Motivo</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="appointmentsBody">
                    <tr><td colspan="8" style="text-align:center;color:var(--muted);">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
        <div style="display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap;">
            <button class="btn" id="apptPrevBtn" type="button">← Anterior</button>
            <span id="apptPageInfo" style="font-size:.8rem;color:var(--muted);">Página 1</span>
            <button class="btn" id="apptNextBtn" type="button">Siguiente →</button>
        </div>
    </article>
</div>

<!-- ─── SETTINGS VIEW ─────────────────────────────────────────────── -->
<div class="view-settings hidden" style="padding:0 8px;">

    <article class="card view-settings" style="margin-bottom:1.5rem;">
        <h2 class="section">🏥 Datos de la Institución</h2>
        <form id="settingsForm" style="display:grid;gap:12px;max-width:640px;">
            <div class="field"><label>Nombre de la institución</label><input id="cfgInstitutionName" type="text" placeholder="Ej: Clínica Salud Ocupacional"></div>
            <div class="field"><label>Subtítulo / especialidad</label><input id="cfgInstitutionSubtitle" type="text" placeholder="Ej: Medicina Ocupacional y Salud Laboral"></div>
            <div class="field"><label>Ciudad</label><input id="cfgInstitutionCity" type="text" placeholder="Ej: Quito – Ecuador"></div>
            <div class="field"><label>RUC</label><input id="cfgInstitutionRuc" type="text" placeholder="Ej: 0101010101001"></div>
            <div class="field"><label>Dirección</label><input id="cfgInstitutionAddress" type="text" placeholder="Ej: Av. República 123 y Naciones Unidas"></div>
            <div class="field"><label>Teléfono</label><input id="cfgInstitutionPhone" type="text" placeholder="Ej: 02-2345678 / 099-8765432"></div>
            <div class="field"><label>Correo electrónico</label><input id="cfgInstitutionEmail" type="email" placeholder="Ej: info@clinica.com"></div>
            <div class="field"><label>Representante legal</label><input id="cfgInstitutionRepresentative" type="text" placeholder="Ej: Dra. María García López"></div>
            <div class="field"><label>Nota al pie de PDFs</label><input id="cfgFooterNote" type="text" placeholder="Ej: Documento confidencial de uso médico."></div>
            <hr style="border:none;border-top:1px solid var(--border);margin:4px 0;">
            <h3 style="font-size:.9rem;font-weight:600;margin:0;">Médico responsable</h3>
            <div class="field"><label>Nombre del médico (firma)</label><input id="cfgSignatureName" type="text" placeholder="Ej: Dra. María García López"></div>
            <div class="field"><label>Título / cargo</label><input id="cfgSignatureTitle" type="text" placeholder="Ej: Médico Ocupacional - Responsable de SSO"></div>
            <div class="field"><label>Código profesional</label><input id="cfgProfessionalCode" type="text" placeholder="Ej: MED-12345"></div>
            <div class="field"><label>Tratamiento (Dr./Dra.)</label><input id="cfgProfessionalTitle" type="text" placeholder="Dr./Dra."></div>
            <button class="btn accent" type="submit" style="width:fit-content;">💾 Guardar configuración</button>
        </form>
    </article>

    <!-- Preview de configuración guardada -->
    <article class="card view-settings" style="margin-bottom:1.5rem;background:var(--bg);" id="settingsPreviewCard">
        <h2 class="section">✅ Configuración activa</h2>
        <div id="settingsPreview" style="font-size:.87rem;color:var(--muted);">Cargando…</div>
    </article>

    <div class="grid3 view-settings" style="margin-bottom:1.5rem;">
        <!-- Logo -->
        <article class="card">
            <h3 style="font-size:.9rem;font-weight:600;margin:0 0 12px;">🖼 Logo institucional</h3>
            <div id="logoPreview" style="min-height:80px;display:flex;align-items:center;justify-content:center;border:1px dashed var(--border);border-radius:8px;margin-bottom:12px;background:var(--bg);">
                <span style="color:var(--muted);font-size:.8rem;">Sin logo</span>
            </div>
            <input id="logoFileInput" type="file" accept="image/*" style="display:none;">
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn" type="button" onclick="document.getElementById('logoFileInput').click()">📂 Seleccionar</button>
                <button class="btn accent" type="button" id="logoUploadBtn">⬆️ Subir logo</button>
                <button class="btn danger" type="button" id="logoDeleteBtn">🗑</button>
            </div>
        </article>

        <!-- Firma -->
        <article class="card">
            <h3 style="font-size:.9rem;font-weight:600;margin:0 0 12px;">✍️ Firma del médico</h3>
            <div id="signaturePreview" style="min-height:80px;display:flex;align-items:center;justify-content:center;border:1px dashed var(--border);border-radius:8px;margin-bottom:12px;background:var(--bg);">
                <span style="color:var(--muted);font-size:.8rem;">Sin firma</span>
            </div>
            <input id="signatureFileInput" type="file" accept="image/*" style="display:none;">
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn" type="button" onclick="document.getElementById('signatureFileInput').click()">📂 Seleccionar</button>
                <button class="btn accent" type="button" id="signatureUploadBtn">⬆️ Subir firma</button>
                <button class="btn danger" type="button" id="signatureDeleteBtn">🗑</button>
            </div>
        </article>

        <!-- Sello -->
        <article class="card">
            <h3 style="font-size:.9rem;font-weight:600;margin:0 0 12px;">🔏 Sello del médico</h3>
            <div id="sealPreview" style="min-height:80px;display:flex;align-items:center;justify-content:center;border:1px dashed var(--border);border-radius:8px;margin-bottom:12px;background:var(--bg);">
                <span style="color:var(--muted);font-size:.8rem;">Sin sello</span>
            </div>
            <input id="sealFileInput" type="file" accept="image/*" style="display:none;">
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn" type="button" onclick="document.getElementById('sealFileInput').click()">📂 Seleccionar</button>
                <button class="btn accent" type="button" id="sealUploadBtn">⬆️ Subir sello</button>
                <button class="btn danger" type="button" id="sealDeleteBtn">🗑</button>
            </div>
        </article>
    </div>

</div>

<!-- Modal: Explicacion SOAP -->
<div id="soapHelpModal" class="modalOverlay hidden">
    <div class="modalBox">
        <button class="modalClose" id="soapHelpModalClose" type="button" title="Cerrar">&times;</button>
        <h3>📋 Metodo SOAP — Nota Clinica Estructurada</h3>
        <p class="hint" style="margin-bottom:14px;">El metodo SOAP es el estandar internacional para documentar consultas medicas de forma ordenada y reproducible.</p>
        <div class="soapHelp">
            <div class="soapHelpItem">
                <strong>S — Subjetivo</strong>
                <p>Lo que el paciente refiere: motivo de consulta, sintomas, historia del problema actual, como se siente. Incluye tiempo de evolucion, intensidad y factores que lo mejoran o empeoran.<br><em>Ej: "Dolor lumbar de 3 dias, 7/10 de intensidad, aumenta con movimiento."</em></p>
            </div>
            <div class="soapHelpItem">
                <strong>O — Objetivo</strong>
                <p>Lo que el medico observa y mide: signos vitales, examen fisico, resultados de examenes de laboratorio o imagen. Datos cuantificables y reproducibles.<br><em>Ej: "PA 130/85, FC 88, contraccion muscular paravertebral L4-L5."</em></p>
            </div>
            <div class="soapHelpItem">
                <strong>A — Analisis (Diagnostico)</strong>
                <p>Interpretacion clinica: diagnostico principal (con codigo CIE-10), diagnosticos diferenciales y evaluacion del estado del paciente. Combina S + O para concluir.<br><em>Ej: "M54.5 - Lumbago no especificado. Probable contractura muscular."</em></p>
            </div>
            <div class="soapHelpItem">
                <strong>P — Plan (Tratamiento)</strong>
                <p>Acciones a tomar: medicamentos prescritos, indicaciones, reposo, fisioterapia, interconsultas, examenes adicionales, y seguimiento programado.<br><em>Ej: "Ibuprofeno 400mg c/8h x5 dias. Reposo relativo 48h. Control en 1 semana."</em></p>
            </div>
        </div>
        <button class="btn" style="margin-top:16px;" id="soapHelpModalClose2" type="button">Entendido</button>
    </div>
</div>

<script>
const state = {
    token:null, user:null, workers:[], evaluations:[], certificates:[], companies:[], positions:[], users:[], roles:[], dashboard:null, monthly:[], aptitude:[],
    selectedWorkerId:null, selectedWorkerName:null, selectedWorkerHistory:null, selectedWorkerEvolutions:[], activeView:"dashboard", workerStep:"recent", operationStep:"consult", workerQuery:"", workerCompanyId:"",
    setupStatus:{ admin_exists:true, bootstrap_required:false, users_count:0 },
    consultation:{ worker_search:"", diagnosis_results:[], selected_diagnoses:[], prescriptions:[] },
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
    operationFlowTabs: document.querySelectorAll(".operationFlowTab"), operationStepPanels: document.querySelectorAll("[data-operation-panel]"),
    dashboardViews: document.querySelectorAll(".view-dashboard"), workerViews: document.querySelectorAll(".view-workers"), operationViews: document.querySelectorAll(".view-operations"), userViews: document.querySelectorAll(".view-users"), settingsViews: document.querySelectorAll(".view-settings"), agendaViews: document.querySelectorAll(".view-agenda"), empresaViews: document.querySelectorAll(".view-empresa"),
    statsGrid: document.getElementById("statsGrid"), monthlyChart: document.getElementById("monthlyChart"), aptitudeBody: document.getElementById("aptitudeBody"),
    todayApptsList: document.getElementById("todayApptsList"), todayApptsCount: document.getElementById("todayApptsCount"), todayApptsEmpty: document.getElementById("todayApptsEmpty"),
    operationsEvalTotal: document.getElementById("operationsEvalTotal"), operationsCertTotal: document.getElementById("operationsCertTotal"), operationsPendingTotal: document.getElementById("operationsPendingTotal"),
    workersBody: document.getElementById("workersBody"), evaluationsBody: document.getElementById("evaluationsBody"), certificatesBody: document.getElementById("certificatesBody"), usersBody: document.getElementById("usersBody"),
    workersPrevBtn: document.getElementById("workersPrevBtn"), workersNextBtn: document.getElementById("workersNextBtn"), workersPageInfo: document.getElementById("workersPageInfo"), workersExportBtn: document.getElementById("workersExportBtn"),
    evaluationsPrevBtn: document.getElementById("evaluationsPrevBtn"), evaluationsNextBtn: document.getElementById("evaluationsNextBtn"), evaluationsPageInfo: document.getElementById("evaluationsPageInfo"), evaluationsExportBtn: document.getElementById("evaluationsExportBtn"),
    certificatesPrevBtn: document.getElementById("certificatesPrevBtn"), certificatesNextBtn: document.getElementById("certificatesNextBtn"), certificatesPageInfo: document.getElementById("certificatesPageInfo"), certificatesExportBtn: document.getElementById("certificatesExportBtn"),
    usersPrevBtn: document.getElementById("usersPrevBtn"), usersNextBtn: document.getElementById("usersNextBtn"), usersPageInfo: document.getElementById("usersPageInfo"), usersExportBtn: document.getElementById("usersExportBtn"),
    workerSearchInput: document.getElementById("workerSearchInput"), workerSearchBtn: document.getElementById("workerSearchBtn"), workerCompanyFilter: document.getElementById("workerCompanyFilter"),
    evaluationFilterForm: document.getElementById("evaluationFilterForm"), certificateFilterForm: document.getElementById("certificateFilterForm"),
    workerForm: document.getElementById("workerForm"), workerCompany: document.getElementById("workerCompany"), workerPosition: document.getElementById("workerPosition"),
    workerDetailBox: document.getElementById("workerDetailBox"), workersManageBody: document.getElementById("workersManageBody"), workerClinicalForm: document.getElementById("workerClinicalForm"), workerFormSubmitBtn: document.getElementById("workerFormSubmitBtn"), workerFormResetBtn: document.getElementById("workerFormResetBtn"), workerCreateBtn: document.getElementById("workerCreateBtn"), workerFormModeHint: document.getElementById("workerFormModeHint"),
    workerHistoryEval: document.getElementById("workerHistoryEval"), workerHistoryCert: document.getElementById("workerHistoryCert"), workerTimeline: document.getElementById("workerTimeline"), workerHistoryPdfBtn: document.getElementById("workerHistoryPdfBtn"), workerCardBtn: document.getElementById("workerCardBtn"),
    workerEvolutionsList: document.getElementById("workerEvolutionsList"), evolutionForm: document.getElementById("evolutionForm"), evoType: document.getElementById("evoType"), evoEvaluation: document.getElementById("evoEvaluation"), evoSubjective: document.getElementById("evoSubjective"), evoObjective: document.getElementById("evoObjective"), evoAssessment: document.getElementById("evoAssessment"), evoPlan: document.getElementById("evoPlan"), evoBP: document.getElementById("evoBP"), evoTemp: document.getElementById("evoTemp"), evoHR: document.getElementById("evoHR"), evoRR: document.getElementById("evoRR"), evoWeight: document.getElementById("evoWeight"), evoHeight: document.getElementById("evoHeight"), evoNotes: document.getElementById("evoNotes"), evoSubmitBtn: document.getElementById("evoSubmitBtn"), evoCancelBtn: document.getElementById("evoCancelBtn"), evoEditId: document.getElementById("evoEditId"), evoFormTitle: document.getElementById("evoFormTitle"),
    workerPrescriptionsList: document.getElementById("workerPrescriptionsList"), prescriptionForm: document.getElementById("prescriptionForm"), rxEvaluation: document.getElementById("rxEvaluation"), rxGeneralNotes: document.getElementById("rxGeneralNotes"), rxMedLines: document.getElementById("rxMedLines"), rxAddMedBtn: document.getElementById("rxAddMedBtn"), rxSubmitBtn: document.getElementById("rxSubmitBtn"), rxCancelBtn: document.getElementById("rxCancelBtn"), rxEditId: document.getElementById("rxEditId"),
    // Tab 6 — Estudios Médicos
    examOrdersList: document.getElementById("examOrdersList"), examOrderForm: document.getElementById("examOrderForm"), orderType: document.getElementById("orderType"), orderPriority: document.getElementById("orderPriority"), orderDate: document.getElementById("orderDate"), orderEvaluation: document.getElementById("orderEvaluation"), orderClinicalIndication: document.getElementById("orderClinicalIndication"), orderAdditionalNotes: document.getElementById("orderAdditionalNotes"), orderSubmitBtn: document.getElementById("orderSubmitBtn"), orderCancelBtn: document.getElementById("orderCancelBtn"), orderEditId: document.getElementById("orderEditId"), examOrderFormTitle: document.getElementById("examOrderFormTitle"),
    examStudiesList: document.getElementById("examStudiesList"), addPresetStudyBtn: document.getElementById("addPresetStudyBtn"), addCustomStudyBtn: document.getElementById("addCustomStudyBtn"), studiesPresetType: document.getElementById("studiesPresetType"), studyPresetDropdown: document.getElementById("studyPresetDropdown"),
    workerAttachmentGallery: document.getElementById("workerAttachmentGallery"), attachmentTypeFilters: document.getElementById("attachmentTypeFilters"),
    galleryUploadEval: document.getElementById("galleryUploadEval"), galleryUploadType: document.getElementById("galleryUploadType"), galleryUploadDate: document.getElementById("galleryUploadDate"), galleryUploadNotes: document.getElementById("galleryUploadNotes"), galleryUploadFile: document.getElementById("galleryUploadFile"), galleryUploadBtn: document.getElementById("galleryUploadBtn"), galleryUploadStatus: document.getElementById("galleryUploadStatus"),
    imageLightboxModal: document.getElementById("imageLightboxModal"), lightboxClose: document.getElementById("lightboxClose"), lightboxImg: document.getElementById("lightboxImg"), lightboxCaption: document.getElementById("lightboxCaption"),
    dicomViewerModal: document.getElementById("dicomViewerModal"), dicomViewerClose: document.getElementById("dicomViewerClose"), dicomFileName: document.getElementById("dicomFileName"), dwvContainer: document.getElementById("dwvContainer"), dwvLoadingMsg: document.getElementById("dwvLoadingMsg"), dwvResetBtn: document.getElementById("dwvResetBtn"), dwvZoomInBtn: document.getElementById("dwvZoomInBtn"), dwvZoomOutBtn: document.getElementById("dwvZoomOutBtn"), dwvToolInfo: document.getElementById("dwvToolInfo"),
    rxMedicationResults: document.getElementById("rxMedicationResults"),
    evalProfName: document.getElementById("evalProfName"), evalProfCode: document.getElementById("evalProfCode"),
    miPerfilBtn: document.getElementById("miPerfilBtn"), miPerfilModal: document.getElementById("miPerfilModal"), miPerfilModalClose: document.getElementById("miPerfilModalClose"), miPerfilForm: document.getElementById("miPerfilForm"), perfilFullName: document.getElementById("perfilFullName"), perfilProfCode: document.getElementById("perfilProfCode"), perfilPassword: document.getElementById("perfilPassword"), miPerfilWarn: document.getElementById("miPerfilWarn"),
    soapHelpBtn: document.getElementById("soapHelpBtn"), soapHelpModal: document.getElementById("soapHelpModal"), soapHelpModalClose: document.getElementById("soapHelpModalClose"), soapHelpModalClose2: document.getElementById("soapHelpModalClose2"),
    evaluationWorker: document.getElementById("evaluationWorker"), evaluationWorkerSearch: document.getElementById("evaluationWorkerSearch"), diagnosisSearchInput: document.getElementById("diagnosisSearchInput"), diagnosisSearchResults: document.getElementById("diagnosisSearchResults"), selectedDiagnosesList: document.getElementById("selectedDiagnosesList"),
    rxMedication: document.getElementById("rxMedication"), rxDosage: document.getElementById("rxDosage"), rxFrequency: document.getElementById("rxFrequency"), rxDuration: document.getElementById("rxDuration"), rxIndications: document.getElementById("rxIndications"), addPrescriptionBtn: document.getElementById("addPrescriptionBtn"), prescriptionList: document.getElementById("prescriptionList"),
    certificateEvaluation: document.getElementById("certificateEvaluation"), attachmentEvaluation: document.getElementById("attachmentEvaluation"), certificateCreateBtn: document.getElementById("certificateCreateBtn"), certificateFlowHint: document.getElementById("certificateFlowHint"),
    userForm: document.getElementById("userForm"), userEditForm: document.getElementById("userEditForm"), userRoleSelect: document.getElementById("userRoleSelect"), userEditRoleSelect: document.getElementById("userEditRoleSelect"),
    loginForm: document.getElementById("loginForm"), loginHint: document.getElementById("loginHint"), firstAdminBox: document.getElementById("firstAdminBox"), firstAdminForm: document.getElementById("firstAdminForm"),
    authRecoveryActions: document.getElementById("authRecoveryActions"), showForgotPasswordBtn: document.getElementById("showForgotPasswordBtn"), showResetPasswordBtn: document.getElementById("showResetPasswordBtn"),
    forgotPasswordBox: document.getElementById("forgotPasswordBox"), forgotPasswordForm: document.getElementById("forgotPasswordForm"), cancelForgotPasswordBtn: document.getElementById("cancelForgotPasswordBtn"),
    resetPasswordBox: document.getElementById("resetPasswordBox"), resetPasswordForm: document.getElementById("resetPasswordForm"), cancelResetPasswordBtn: document.getElementById("cancelResetPasswordBtn"),
    companyModal: document.getElementById('companyModal'), companyForm: document.getElementById('companyForm'), companyFormId: document.getElementById('companyFormId'), newCompanyBtn: document.getElementById('newCompanyBtn')
};
let diagnosisSearchTimer = null;

function status(msg, type="info"){ refs.status.textContent = msg; refs.status.classList.remove("ok","error"); if(type==="ok") refs.status.classList.add("ok"); if(type==="error") refs.status.classList.add("error"); }
// Alias: showStatus("text", "success"|"error"|"warn") — usado por módulos nuevos
function showStatus(msg, type="info"){ status(msg, type === "success" ? "ok" : type); }
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
function hasAnyRole(roles){
    if(!Array.isArray(state.user?.roles)) return false;
    return roles.some((role) => state.user.roles.includes(role));
}
function canIssueCertificates(){ return hasAnyRole(["ADMIN","MEDICO_OCUPACIONAL"]); }
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
function toNullableNumber(value){
    const raw = String(value ?? "").trim();
    if(raw === "") return null;
    const num = Number(raw);
    return Number.isFinite(num) ? num : null;
}
function normalizeDiagnosisCode(value){
    return String(value || "").trim().toUpperCase();
}
function renderDiagnosisSearchResults(){
    if(!refs.diagnosisSearchResults) return;
    refs.diagnosisSearchResults.innerHTML = "";
    if(!state.consultation.diagnosis_results.length){
        refs.diagnosisSearchResults.innerHTML = `<p class="empty">Sin resultados.</p>`;
        return;
    }
    state.consultation.diagnosis_results.forEach(item => {
        const row = document.createElement("div");
        row.className = "diagnosisResultItem";
        row.innerHTML = `<div><strong>${esc(item.code)}</strong> - ${esc(item.description)}</div>
        <button class="btn small" type="button" data-act="add-diagnosis" data-code="${esc(item.code)}" data-description="${esc(item.description)}">Agregar</button>`;
        refs.diagnosisSearchResults.appendChild(row);
    });
}
function renderSelectedDiagnoses(){
    if(!refs.selectedDiagnosesList) return;
    refs.selectedDiagnosesList.innerHTML = "";
    if(!state.consultation.selected_diagnoses.length){
        refs.selectedDiagnosesList.innerHTML = `<p class="empty">Sin diagnosticos seleccionados.</p>`;
        return;
    }
    state.consultation.selected_diagnoses.forEach((item, idx) => {
        const row = document.createElement("div");
        row.className = "diagnosisSelectedItem";
        row.innerHTML = `
            <div class="field"><label>Diagnostico CIE-10</label><input type="text" value="${esc(item.code)} - ${esc(item.description)}" disabled></div>
            <div class="field"><label>Tipo</label><select data-act="diagnosis-type" data-index="${idx}"><option value="PRE" ${item.diagnosis_type === "PRE" ? "selected" : ""}>PRE</option><option value="DEF" ${item.diagnosis_type === "DEF" ? "selected" : ""}>DEF</option></select></div>
            <button class="btn small" type="button" data-act="remove-diagnosis" data-index="${idx}">Quitar</button>`;
        refs.selectedDiagnosesList.appendChild(row);
    });
}
function renderPrescriptionList(){
    if(!refs.prescriptionList) return;
    refs.prescriptionList.innerHTML = "";
    if(!state.consultation.prescriptions.length){
        refs.prescriptionList.innerHTML = `<p class="empty">Sin medicamentos agregados.</p>`;
        return;
    }
    state.consultation.prescriptions.forEach((item, idx) => {
        const row = document.createElement("div");
        row.className = "prescriptionItem";
        row.innerHTML = `
            <span><strong>${esc(item.medication)}</strong></span>
            <span>${esc(item.dosage)}</span>
            <span>${esc(item.frequency || "-")}</span>
            <span>${esc(item.duration || "-")}</span>
            <span>${esc(item.indications || "-")}</span>
            <button class="btn small" type="button" data-act="remove-rx" data-index="${idx}">Quitar</button>
        `;
        refs.prescriptionList.appendChild(row);
    });
}
function resetConsultationState(){
    state.consultation.diagnosis_results = [];
    state.consultation.selected_diagnoses = [];
    state.consultation.prescriptions = [];
    renderDiagnosisSearchResults();
    renderSelectedDiagnoses();
    renderPrescriptionList();
    if(refs.diagnosisSearchInput) refs.diagnosisSearchInput.value = "";
    if(refs.rxMedication) refs.rxMedication.value = "";
    if(refs.rxDosage) refs.rxDosage.value = "";
    if(refs.rxFrequency) refs.rxFrequency.value = "";
    if(refs.rxDuration) refs.rxDuration.value = "";
    if(refs.rxIndications) refs.rxIndications.value = "";
}
function filterEvaluationWorkerOptions(){
    if(!refs.evaluationWorker) return;
    const search = mbLower(String(state.consultation.worker_search || ""));
    refs.evaluationWorker.innerHTML = "";
    const options = state.workers.filter(w => {
        if(search === "") return true;
        const fullName = `${w.first_name || ""} ${w.last_name || ""}`.trim();
        return mbLower(fullName).includes(search) || mbLower(w.document_number || "").includes(search);
    });
    options.forEach(w => refs.evaluationWorker.appendChild(makeOpt(w.id, `${w.first_name} ${w.last_name} (${w.document_number})`)));
}
function mbLower(value){
    return String(value || "").toLocaleLowerCase();
}
async function searchDiagnosisCatalog(){
    const query = String(refs.diagnosisSearchInput?.value || "").trim();
    if(query.length < 2){
        state.consultation.diagnosis_results = [];
        renderDiagnosisSearchResults();
        return;
    }
    try{
        const res = await api(`/api/catalog/diagnoses?${buildQueryString({ q:query, limit:8 })}`);
        const rows = Array.isArray(res?.data) ? res.data : [];
        const selectedCodes = new Set(state.consultation.selected_diagnoses.map(x => x.code));
        state.consultation.diagnosis_results = rows.filter(x => !selectedCodes.has(normalizeDiagnosisCode(x.code)));
        renderDiagnosisSearchResults();
    } catch {
        state.consultation.diagnosis_results = [];
        renderDiagnosisSearchResults();
    }
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
    if(p.startsWith("/sistema/trabajadores"))  return "workers";
    if(p.startsWith("/sistema/operacion"))     return "operations";
    if(p.startsWith("/sistema/usuarios"))      return "users";
    if(p.startsWith("/sistema/configuracion")) return "settings";
    if(p.startsWith("/sistema/agenda"))        return "agenda";
    if(p.startsWith("/sistema/empresas"))      return "empresa";
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
    const allowed = new Set(["recent","manage","clinical","history","evolutions","studies","vaccines","accidents"]);
    state.workerStep = allowed.has(step) ? step : "recent";
    applyWorkerStepVisibility();
}

function applyOperationStepVisibility(){
    const operationsView = state.activeView === "operations";
    refs.operationFlowTabs.forEach(tab => tab.classList.toggle("active", tab.getAttribute("data-operation-step") === state.operationStep));
    refs.operationStepPanels.forEach(panel => {
        const panelStep = panel.getAttribute("data-operation-panel");
        panel.classList.toggle("hidden", !operationsView || panelStep !== state.operationStep);
    });
    refs.workerPanelHosts.forEach(host => {
        if(!operationsView) return;
        const hasOperationPanels = !!host.querySelector(".operationStepPanel[data-operation-panel]");
        if(!hasOperationPanels) return;
        const hasVisibleOperationPanel = !!host.querySelector(".operationStepPanel[data-operation-panel]:not(.hidden)");
        host.classList.toggle("hidden", !hasVisibleOperationPanel);
    });
}

function setOperationStep(step){
    const allowed = new Set(["consult","certificate","evaluations","certificates"]);
    state.operationStep = allowed.has(step) ? step : "consult";
    applyOperationStepVisibility();
}

function applyViewVisibility(){
    const dashboard  = state.activeView === "dashboard";
    const workers    = state.activeView === "workers";
    const operations = state.activeView === "operations";
    const users      = state.activeView === "users" && canManageUsers();
    const settings   = state.activeView === "settings";
    const agenda     = state.activeView === "agenda";
    const empresa    = state.activeView === "empresa";
    refs.dashboardViews.forEach(el => el.classList.toggle("hidden", !dashboard));
    refs.workerViews.forEach(el => el.classList.toggle("hidden", !workers));
    refs.operationViews.forEach(el => el.classList.toggle("hidden", !operations));
    refs.userViews.forEach(el => el.classList.toggle("hidden", !users));
    refs.settingsViews.forEach(el => el.classList.toggle("hidden", !settings));
    refs.agendaViews.forEach(el => el.classList.toggle("hidden", !agenda));
    refs.empresaViews.forEach(el => el.classList.toggle("hidden", !empresa));
    if(refs.userTab) refs.userTab.classList.toggle("hidden", !canManageUsers());
    refs.tabs.forEach(tab => tab.classList.toggle("active", tab.getAttribute("data-view") === state.activeView));
    applyWorkerStepVisibility();
    applyOperationStepVisibility();
}

function setView(view, updateHistory=true){
    if(view === "users" && !canManageUsers()){
        view = "dashboard";
    }
    state.activeView = view;
    applyViewVisibility();
    if(view === "settings") loadSettings();
    if(view === "agenda")   loadAgendaView();
    if(view === "empresa")  loadEmpresaList();
    if(!updateHistory) return;
    const paths = {workers:"/sistema/trabajadores", operations:"/sistema/operacion", users:"/sistema/usuarios",
                   settings:"/sistema/configuracion", agenda:"/sistema/agenda", empresa:"/sistema/empresas"};
    const target = paths[view] || "/sistema";
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
        company_id: state.workerCompanyId || undefined,
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
    // Store worker name for PDF filename
    const w = res.data?.worker;
    if(w) state.selectedWorkerName = `${w.last_name||''}-${w.first_name||''}`.replace(/\s+/g,'').substring(0,30);
}

function renderStats(){
    const t = state.dashboard?.totals || {};
    const cards = [
        ["👥 Trabajadores",         t.workers             ?? 0],
        ["📋 Evaluaciones",         t.evaluations         ?? 0],
        ["📜 Certificados",         t.certificates        ?? 0],
        ["⏳ Pendientes de cert.",  t.pending_certificates ?? 0],
        ["📅 Citas hoy",            t.today_appointments  ?? 0],
        ["🩺 Evaluaciones (mes)",   t.month_evaluations   ?? 0],
        ["⚠️ Accidentes (año)",    t.year_accidents       ?? 0],
    ];
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

function setWorkerFormEnabled(enabled){
    if(!refs.workerForm) return;
    refs.workerForm.classList.toggle("workerFormLocked", !enabled);
    refs.workerForm.querySelectorAll("input,select,textarea,button").forEach((control) => {
        if(control.getAttribute("name") === "worker_id") return;
        control.disabled = !enabled;
    });
    if(!enabled && refs.workerFormModeHint){
        refs.workerFormModeHint.textContent = "Formulario desactivado. Usa Crear trabajador o Editar.";
    }
}

function resetWorkerForm(keepHistory=false){
    if(!refs.workerForm) return;
    refs.workerForm.reset();
    refs.workerForm.worker_id.value = "";
    refs.workerForm.document_type.value = "CEDULA";
    refs.workerForm.sex.value = "M";
    refs.workerForm.company_id.value = "";
    refs.workerForm.job_position_id.value = "";
    setWorkerFormMode("create");
    setWorkerFormEnabled(false);
    if(!keepHistory){
        refs.workerDetailBox.innerHTML = `<p class="empty">Selecciona un trabajador para ver ficha completa.</p>`;
    }
}

function fillWorkerForm(worker, enableEditing=false){
    if(!refs.workerForm) return;
    refs.workerForm.worker_id.value = worker.id || "";
    refs.workerForm.document_type.value = worker.document_type || "CEDULA";
    refs.workerForm.document_number.value = worker.document_number || "";
    refs.workerForm.first_name.value = worker.first_name || "";
    refs.workerForm.last_name.value = worker.last_name || "";
    refs.workerForm.birth_date.value = worker.birth_date || "";
    refs.workerForm.sex.value = worker.sex || "M";
    refs.workerForm.email.value = worker.email || "";
    refs.workerForm.phone.value = worker.phone || "";
    refs.workerForm.blood_type.value = worker.blood_type || "";
    refs.workerForm.laterality.value = worker.laterality || "";
    refs.workerForm.company_id.value = worker.company_id || "";
    refs.workerForm.job_position_id.value = worker.job_position_id || "";
    setWorkerFormMode("edit");
    setWorkerFormEnabled(enableEditing);
    if(!enableEditing && refs.workerFormModeHint){
        refs.workerFormModeHint.textContent = "Ficha cargada en solo lectura. Usa Editar para habilitar.";
    }
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
    // Calcular edad
    let age = "";
    if(w.birth_date){
        const born = new Date(w.birth_date);
        const today = new Date();
        let a = today.getFullYear() - born.getFullYear();
        const m = today.getMonth() - born.getMonth();
        if(m < 0 || (m === 0 && today.getDate() < born.getDate())) a--;
        age = ` · ${a} años`;
    }
    refs.workerDetailBox.innerHTML = `
        <p class="meta"><strong>Nombre:</strong> ${esc(w.last_name)}, ${esc(w.first_name)}${age}</p>
        <p class="meta"><strong>Documento:</strong> ${esc(w.document_type)} ${esc(w.document_number)}</p>
        <p class="meta"><strong>Sexo / Sangre:</strong> ${esc(w.sex || "—")} / ${esc(w.blood_type || "—")}</p>
        <p class="meta"><strong>Historia:</strong> ${esc(w.history_number)} &nbsp;|&nbsp; <strong>Archivo:</strong> ${esc(w.file_number)}</p>
        <p class="meta"><strong>Empresa:</strong> ${esc(w.company?.business_name || "Sin empresa")}</p>
        <p class="meta"><strong>Puesto:</strong> ${esc(w.job_position?.name || "Sin puesto")}</p>
        ${w.email ? `<p class="meta"><strong>Email:</strong> ${esc(w.email)}</p>` : ""}
        ${w.phone ? `<p class="meta"><strong>Teléfono:</strong> ${esc(w.phone)}</p>` : ""}
        <div class="chips"><span class="chip">Evaluaciones: ${(history.evaluations || []).length}</span><span class="chip">Certificados: ${(history.certificates || []).length}</span></div>
    `;

    fillWorkerForm(w, false);
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
            const prescriptions = e.prescriptions || [];
            const attachmentRows = attachments.map((a) => {
                const type = a.attachment_type || "GENERAL";
                const examDate = a.exam_date ? fmtDate(a.exam_date) : "-";
                const notes = a.notes ? ` | ${esc(a.notes)}` : "";
                return `<p class="meta"><strong>${esc(type)}</strong> | ${esc(a.file_name || "archivo")} | ${examDate} | ${formatBytes(a.file_size_bytes)}${notes}
                <button class="btn small" data-act="download-attachment" data-attachment-id="${a.id}" data-file-name="${esc(a.file_name || "archivo")}" type="button">Descargar</button></p>`;
            }).join("");
            const prescriptionRows = prescriptions.map((p) => `<p class="meta"><strong>${esc(p.medication)}</strong> | ${esc(p.dosage)} | ${esc(p.frequency || "-")} | ${esc(p.duration || "-")} | ${esc(p.indications || "-")}</p>`).join("");
            const soapObjective = e.physical_exam?.soap_o || "-";
            const soapAnalysis = e.exam_results?.soap_a || e.current_problem || "-";
            const soapPlan = e.recommendations || "-";
            card.className = "historyCard";
            card.innerHTML = `<p class="meta"><strong>${esc(e.evaluation_type)}</strong> - ${fmtDate(e.attention_date)} <span class="pill ${aptitudePillClass(e.medical_aptitude)}">${esc(e.medical_aptitude)}</span></p>
            <p class="meta"><strong>Motivo:</strong> ${esc(e.consultation_reason || "-")}</p>
            <p class="meta"><strong>SOAP-O:</strong> ${esc(soapObjective)}</p>
            <p class="meta"><strong>SOAP-A:</strong> ${esc(soapAnalysis)}</p>
            <p class="meta"><strong>SOAP-P:</strong> ${esc(soapPlan)}</p>
            <p class="meta"><strong>Profesional:</strong> ${esc(e.professional_name || "-")} (${esc(e.professional_code || "-")})</p>
            <p class="meta"><strong>Adjuntos:</strong> ${attachments.length}</p>
            ${attachmentRows || '<p class="meta">Sin adjuntos de examenes.</p>'}
            <p class="meta"><strong>Receta:</strong> ${prescriptions.length} medicamento(s)</p>
            ${prescriptionRows || '<p class="meta">Sin receta medica registrada.</p>'}
            ${prescriptions.length > 0 ? `<button class="btn small" data-act="print-prescription" data-eval-id="${e.id}" type="button">🖨️ Imprimir receta</button>` : ""}
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

/* ─── EVOLUCIONES CLINICAS ─── */
async function loadWorkerEvolutions(workerId){
    if(!workerId){ renderEvolutionsList([]); return; }
    try{
        const res = await api(`/api/workers/${workerId}/evolutions`);
        state.selectedWorkerEvolutions = Array.isArray(res?.data) ? res.data : (Array.isArray(res) ? res : []);
        renderEvolutionsList(state.selectedWorkerEvolutions.filter(e => e.evolution_type !== 'PRESCRIPCION'));
        renderWorkerPrescriptions();
        populateEvoEvaluationSelect();
        populateRxEvaluationSelect();
    } catch(err){
        state.selectedWorkerEvolutions = [];
        renderEvolutionsList([]);
    }
}

function populateEvoEvaluationSelect(){
    if(!refs.evoEvaluation) return;
    refs.evoEvaluation.innerHTML = '<option value="">-- Sin evaluacion --</option>';
    const evals = state.selectedWorkerHistory?.evaluations || [];
    evals.forEach(e => {
        const opt = document.createElement("option");
        opt.value = e.id;
        opt.textContent = `${fmtDate(e.attention_date)} - ${esc(e.evaluation_type)}`;
        refs.evoEvaluation.appendChild(opt);
    });
}

const EVO_TYPE_LABELS = { SEGUIMIENTO:"Seguimiento", NOTA:"Nota clínica", INTERCONSULTA:"Interconsulta", URGENCIA:"Urgencia", PRESCRIPCION:"Prescripción" };
function renderEvolutionsList(evolutions){
    if(!refs.workerEvolutionsList) return;
    refs.workerEvolutionsList.innerHTML = "";
    if(!state.selectedWorkerId){
        refs.workerEvolutionsList.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`;
        return;
    }
    if(!evolutions.length){
        refs.workerEvolutionsList.innerHTML = `<p class="empty">No hay evoluciones registradas para este trabajador.</p>`;
        return;
    }
    evolutions.forEach(ev => {
        const card = document.createElement("div");
        card.className = "historyCard";
        const vs = ev.vital_signs || {};
        const vitals = [vs.bp&&`PA: ${vs.bp}`, vs.temp&&`T: ${vs.temp}°C`, vs.hr&&`FC: ${vs.hr}`, vs.rr&&`FR: ${vs.rr}`, vs.weight&&`${vs.weight}kg`, vs.height&&`${vs.height}cm`].filter(Boolean).join(" | ");
        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                <p class="meta" style="margin:0;"><strong>${esc(EVO_TYPE_LABELS[ev.evolution_type]||ev.evolution_type)}</strong> &mdash; ${fmtDate(ev.created_at)}</p>
                <div class="rowActions">
                    <button class="btn small" data-act="edit-evo" data-evo-id="${ev.id}" type="button">Editar</button>
                    <button class="btn small" data-act="delete-evo" data-evo-id="${ev.id}" type="button">Eliminar</button>
                </div>
            </div>
            ${ev.subjective ? `<p class="meta"><strong>S:</strong> ${esc(ev.subjective)}</p>` : ""}
            ${ev.objective ? `<p class="meta"><strong>O:</strong> ${esc(ev.objective)}</p>` : ""}
            ${ev.assessment ? `<p class="meta"><strong>A:</strong> ${esc(ev.assessment)}</p>` : ""}
            ${ev.plan ? `<p class="meta"><strong>P:</strong> ${esc(ev.plan)}</p>` : ""}
            ${vitals ? `<p class="meta"><strong>Signos vitales:</strong> ${vitals}</p>` : ""}
            ${ev.notes ? `<p class="meta"><strong>Notas:</strong> ${esc(ev.notes)}</p>` : ""}`;
        refs.workerEvolutionsList.appendChild(card);
    });
}

function resetEvolutionForm(){
    if(!refs.evolutionForm) return;
    refs.evoEditId.value = "";
    refs.evoType.value = "SEGUIMIENTO";
    refs.evoEvaluation.value = "";
    refs.evoSubjective.value = "";
    refs.evoObjective.value = "";
    refs.evoAssessment.value = "";
    refs.evoPlan.value = "";
    refs.evoBP.value = "";
    refs.evoTemp.value = "";
    refs.evoHR.value = "";
    refs.evoRR.value = "";
    refs.evoWeight.value = "";
    refs.evoHeight.value = "";
    refs.evoNotes.value = "";
    refs.evoSubmitBtn.textContent = "Guardar evolucion";
    refs.evoCancelBtn.style.display = "none";
    refs.evoFormTitle.textContent = "Nueva evolucion";
}

function fillEvolutionForm(ev){
    refs.evoEditId.value = ev.id;
    refs.evoType.value = ev.evolution_type || "SEGUIMIENTO";
    refs.evoEvaluation.value = ev.evaluation_id || "";
    refs.evoSubjective.value = ev.subjective || "";
    refs.evoObjective.value = ev.objective || "";
    refs.evoAssessment.value = ev.assessment || "";
    refs.evoPlan.value = ev.plan || "";
    const vs = ev.vital_signs || {};
    refs.evoBP.value = vs.bp || "";
    refs.evoTemp.value = vs.temp || "";
    refs.evoHR.value = vs.hr || "";
    refs.evoRR.value = vs.rr || "";
    refs.evoWeight.value = vs.weight || "";
    refs.evoHeight.value = vs.height || "";
    refs.evoNotes.value = ev.notes || "";
    refs.evoSubmitBtn.textContent = "Actualizar evolucion";
    refs.evoCancelBtn.style.display = "";
    refs.evoFormTitle.textContent = "Editar evolucion";
}

/* ─── PRESCRIPCIONES (Tab 5) ─── */
function renderWorkerPrescriptions(){
    const list = refs.workerPrescriptionsList;
    if(!list) return;
    list.innerHTML = "";
    if(!state.selectedWorkerId){ list.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`; return; }

    // Prescriptions from evaluations (read-only history)
    const evals = state.selectedWorkerHistory?.evaluations || [];
    const evalRx = evals.filter(e => e.prescriptions?.length > 0);

    // Standalone prescriptions saved as PRESCRIPCION evolutions
    const standaloneRx = (state.selectedWorkerEvolutions || []).filter(e => e.evolution_type === 'PRESCRIPCION');

    if(!evalRx.length && !standaloneRx.length){
        list.innerHTML = `<p class="empty">No hay prescripciones registradas para este trabajador.</p>`;
        return;
    }

    // From evaluations
    evalRx.forEach(ev => {
        const card = document.createElement("div");
        card.className = "historyCard";
        const rows = (ev.prescriptions||[]).map(rx =>
            `<tr><td>${esc(rx.medication||"")}</td><td>${esc(rx.dose||"")}</td><td>${esc(rx.frequency||"")}</td><td>${esc(rx.duration||"")}</td><td>${esc(rx.instructions||"")}</td></tr>`
        ).join("");
        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                <p class="meta" style="margin:0;">🩺 <strong>Receta en consulta</strong> &mdash; ${fmtDate(ev.attention_date)} &mdash; ${esc(ev.evaluation_type||"")}</p>
                <button class="btn small" data-act="print-rx" data-eval-id="${ev.id}" type="button">🖨️ Imprimir</button>
            </div>
            <table style="width:100%;font-size:.78rem;border-collapse:collapse;">
                <thead><tr style="background:#edf7f3;"><th style="padding:4px 6px;text-align:left;">Medicamento</th><th>Dosis</th><th>Frecuencia</th><th>Duración</th><th>Indicaciones</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>`;
        list.appendChild(card);
    });

    // From standalone evolutions type PRESCRIPCION
    standaloneRx.forEach(ev => {
        const card = document.createElement("div");
        card.className = "historyCard";
        const meds = ev.medications || [];
        const rows = meds.map(rx =>
            `<tr><td>${esc(rx.medication||"")}</td><td>${esc(rx.dose||"")}</td><td>${esc(rx.frequency||"")}</td><td>${esc(rx.duration||"")}</td><td>${esc(rx.instructions||"")}</td></tr>`
        ).join("");
        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                <p class="meta" style="margin:0;">💊 <strong>Prescripción directa</strong> &mdash; ${fmtDate(ev.created_at)} &mdash; ${esc(ev.author?.name||"")}</p>
                <div class="rowActions">
                    <button class="btn small" data-act="edit-rx-evo" data-evo-id="${ev.id}" type="button">Editar</button>
                    <button class="btn small" data-act="delete-rx-evo" data-evo-id="${ev.id}" type="button">Eliminar</button>
                </div>
            </div>
            ${ev.notes ? `<p class="meta" style="margin-bottom:6px;"><strong>Notas:</strong> ${esc(ev.notes)}</p>` : ""}
            ${rows ? `<table style="width:100%;font-size:.78rem;border-collapse:collapse;">
                <thead><tr style="background:#edf7f3;"><th style="padding:4px 6px;text-align:left;">Medicamento</th><th>Dosis</th><th>Frecuencia</th><th>Duración</th><th>Indicaciones</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>` : ""}`;
        list.appendChild(card);
    });
}

function populateRxEvaluationSelect(){
    if(!refs.rxEvaluation) return;
    refs.rxEvaluation.innerHTML = '<option value="">-- Sin evaluación --</option>';
    const evals = state.selectedWorkerHistory?.evaluations || [];
    evals.forEach(e => {
        const opt = document.createElement("option");
        opt.value = e.id;
        opt.textContent = `${fmtDate(e.attention_date)} - ${esc(e.evaluation_type)}`;
        refs.rxEvaluation.appendChild(opt);
    });
}

function addRxMedLine(data = {}){
    if(!refs.rxMedLines) return;
    const tmpl = document.getElementById("rxMedLineTemplate");
    if(!tmpl) return;
    const clone = tmpl.content.cloneNode(true);
    const line = clone.querySelector(".rxMedLine");
    if(data.medication) line.querySelector(".rxMedInput").value = data.medication;
    if(data.dose)        line.querySelector(".rxDose").value = data.dose;
    if(data.frequency)   line.querySelector(".rxFreq").value = data.frequency;
    if(data.duration)    line.querySelector(".rxDuration").value = data.duration;
    if(data.instructions) line.querySelector(".rxInstructions").value = data.instructions;

    // Autocomplete for this line
    const medInput = line.querySelector(".rxMedInput");
    const suggBox  = line.querySelector(".rxMedSuggestions");
    let medTimer = null;
    medInput.addEventListener("input", () => {
        clearTimeout(medTimer);
        medTimer = setTimeout(async () => {
            const q = medInput.value.trim();
            if(q.length < 2){ suggBox.classList.add("hidden"); return; }
            try{
                const res = await api(`/api/catalog/medications?${buildQueryString({ q, limit:6 })}`);
                const rows = Array.isArray(res?.data) ? res.data : (Array.isArray(res) ? res : []);
                if(!rows.length){ suggBox.classList.add("hidden"); return; }
                suggBox.innerHTML = "";
                rows.forEach(m => {
                    const item = document.createElement("div");
                    item.className = "rxMedItem";
                    item.style.cssText = "padding:6px 10px;cursor:pointer;font-size:.82rem;border-bottom:1px solid #eee;";
                    item.innerHTML = `<strong>${esc(m.generic_name)}${m.concentration ? " " + esc(m.concentration) : ""}</strong> <span style="color:#888;">${esc(m.pharmaceutical_form||"")}</span>`;
                    item.addEventListener("mousedown", e => {
                        e.preventDefault();
                        medInput.value = m.generic_name + (m.concentration ? " " + m.concentration : "");
                        const doseField = line.querySelector(".rxDose");
                        if(!doseField.value) doseField.value = m.concentration || "";
                        suggBox.classList.add("hidden");
                    });
                    suggBox.appendChild(item);
                });
                suggBox.classList.remove("hidden");
            } catch { suggBox.classList.add("hidden"); }
        }, 250);
    });
    medInput.addEventListener("blur", () => setTimeout(() => suggBox.classList.add("hidden"), 200));

    // Remove line
    line.querySelector(".rxRemoveMedBtn").addEventListener("click", () => line.remove());

    refs.rxMedLines.appendChild(line);
}

function getRxMedLines(){
    if(!refs.rxMedLines) return [];
    return Array.from(refs.rxMedLines.querySelectorAll(".rxMedLine")).map(line => ({
        medication:   line.querySelector(".rxMedInput")?.value?.trim() || "",
        dose:         line.querySelector(".rxDose")?.value?.trim() || "",
        frequency:    line.querySelector(".rxFreq")?.value?.trim() || "",
        duration:     line.querySelector(".rxDuration")?.value?.trim() || "",
        instructions: line.querySelector(".rxInstructions")?.value?.trim() || "",
    })).filter(rx => rx.medication);
}

function resetPrescriptionForm(){
    if(!refs.prescriptionForm) return;
    refs.rxEditId.value = "";
    refs.rxEvaluation.value = "";
    refs.rxGeneralNotes.value = "";
    refs.rxMedLines.innerHTML = "";
    refs.rxSubmitBtn.textContent = "💾 Guardar prescripción";
    refs.rxCancelBtn.style.display = "none";
    addRxMedLine(); // start with one empty line
}

function fillPrescriptionForm(ev){
    if(!refs.prescriptionForm) return;
    refs.rxEditId.value = ev.id;
    refs.rxEvaluation.value = ev.evaluation_id || "";
    refs.rxGeneralNotes.value = ev.notes || "";
    refs.rxMedLines.innerHTML = "";
    (ev.medications || []).forEach(m => addRxMedLine(m));
    if(!refs.rxMedLines.children.length) addRxMedLine();
    refs.rxSubmitBtn.textContent = "✏️ Actualizar prescripción";
    refs.rxCancelBtn.style.display = "";
    refs.prescriptionForm.scrollIntoView({ behavior:"smooth", block:"center" });
}

if(refs.rxAddMedBtn) refs.rxAddMedBtn.addEventListener("click", () => addRxMedLine());
if(refs.rxCancelBtn) refs.rxCancelBtn.addEventListener("click", () => resetPrescriptionForm());

if(refs.prescriptionForm){
    refs.prescriptionForm.addEventListener("submit", async e => {
        e.preventDefault();
        const medications = getRxMedLines();
        if(!medications.length){ showToast("Agrega al menos un medicamento.", "warn"); return; }
        const isEdit = refs.rxEditId.value;
        const payload = {
            evolution_type: "PRESCRIPCION",
            evaluation_id:  refs.rxEvaluation.value || null,
            notes:          refs.rxGeneralNotes.value.trim() || null,
            medications,
        };
        try{
            refs.rxSubmitBtn.disabled = true;
            if(isEdit){
                await api(`/api/workers/${state.selectedWorkerId}/evolutions/${isEdit}`, "PUT", payload);
            } else {
                await api(`/api/workers/${state.selectedWorkerId}/evolutions`, "POST", payload);
            }
            showToast(isEdit ? "Prescripción actualizada." : "Prescripción guardada.", "success");
            resetPrescriptionForm();
            await loadWorkerEvolutions(state.selectedWorkerId);
            renderWorkerPrescriptions();
        } catch(err){
            showToast("Error al guardar prescripción: " + (err.message||""), "error");
        } finally { refs.rxSubmitBtn.disabled = false; }
    });
}

if(refs.workerPrescriptionsList){
    refs.workerPrescriptionsList.addEventListener("click", async e => {
        const btn = e.target.closest("[data-act]");
        if(!btn) return;
        const act = btn.dataset.act;
        if(act === "print-rx"){
            const evalId = btn.dataset.evalId;
            window.open(`/api/evaluations/${evalId}/prescription-pdf`, "_blank");
        } else if(act === "edit-rx-evo"){
            const evoId = btn.dataset.evoId;
            const ev = (state.selectedWorkerEvolutions||[]).find(x => String(x.id) === String(evoId));
            if(ev) fillPrescriptionForm(ev);
        } else if(act === "delete-rx-evo"){
            const evoId = btn.dataset.evoId;
            if(!confirm("¿Eliminar esta prescripción?")) return;
            try{
                await api(`/api/workers/${state.selectedWorkerId}/evolutions/${evoId}`, "DELETE");
                showToast("Prescripción eliminada.", "success");
                await loadWorkerEvolutions(state.selectedWorkerId);
                renderWorkerPrescriptions();
            } catch { showToast("Error al eliminar.", "error"); }
        }
    });
}




/* ─── TAB 6: ESTUDIOS MÉDICOS ─── */

/* ---- Catálogos de estudios predefinidos ---- */
const STUDY_PRESETS = {
    LAB: [
        "Biometría hemática completa (BHC)",
        "Glucosa en ayunas",
        "Urea y creatinina",
        "Perfil lipídico (Colesterol total, HDL, LDL, Triglicéridos)",
        "Transaminasas (AST/ALT)",
        "Ácido úrico",
        "Proteínas totales y albúmina",
        "Calcio sérico",
        "Hemoglobina glicosilada (HbA1c)",
        "Examen general de orina (EGO)",
        "Coprocultivo",
        "Urocultivo",
        "VDRL",
        "VIH (Confidencial)",
        "HBsAg (Hepatitis B)",
        "TSH (Tiroides)",
        "Ferritina y hierro sérico",
        "Proteína C reactiva (PCR)",
    ],
    IMAGING: [
        "Radiografía de tórax PA y lateral",
        "Radiografía columna lumbar AP y lateral",
        "Radiografía columna cervical AP y lateral",
        "Radiografía de manos bilateral",
        "Radiografía de rodillas bilateral",
        "Radiografía de cadera AP",
        "Ecografía abdominal",
        "Ecografía renal",
        "Ecografía musculoesquelética",
        "Tomografía de tórax (TC)",
        "Tomografía de abdomen y pelvis (TC)",
        "Resonancia magnética columna lumbar (RM)",
        "Resonancia magnética columna cervical (RM)",
        "Resonancia magnética cerebral (RM)",
        "Ecocardiograma",
        "Doppler venoso miembros inferiores",
    ],
    PATHOLOGY: [
        "Citología cervicovaginal (Papanicolaou)",
        "Biopsia de piel",
        "Biopsia de nódulo/masa",
        "Biopsia de endometrio",
        "Estudio anatomopatológico",
        "Cultivo de secreción",
        "Raspado de lesión cutánea",
    ],
    FUNCTIONAL: [
        "Audiometría tonal (OD y OI)",
        "Espirometría / Prueba de función pulmonar",
        "Electrocardiograma (ECG)",
        "Electroencefalograma (EEG)",
        "Ergometría / Prueba de esfuerzo",
        "Optometría / Agudeza visual",
        "Rinoscopia",
        "Electromiografía (EMG)",
        "Velocidad de conducción nerviosa",
    ],
};
const ORDER_TYPE_LABELS = { LAB:"Laboratorio", IMAGING:"Imágenes", PATHOLOGY:"Patología", FUNCTIONAL:"Funcionales" };
const ORDER_PRIORITY_LABELS = { URGENT:"⚠️ URGENTE", NORMAL:"Normal", ROUTINE:"Rutina" };
const ORDER_STATUS_LABELS = { PENDING:"Pendiente", COMPLETED:"Completado", PARTIAL:"Parcial", CANCELLED:"Cancelado" };

const state_studies = { examOrders:[], workerAttachments:[], attachmentFilter:"ALL" };
const state_vaccines = { list:[] };
const state_accidents = { list:[] };

const COMMON_VACCINES = [
    "Hepatitis B","Influenza estacional","Tétanos (Td)","Fiebre amarilla","Fiebre tifoidea",
    "Hepatitis A","Varicela","MMR (SRP)","COVID-19","Neumococo 23v","Meningococo",
    "Ántrax","Rabia (pre-exposición)","Polio (OPV/IPV)","DPT (difteria, pertusis, tétanos)"
];

/* --- Exam Orders --- */
async function loadExamOrders(workerId){
    if(!workerId){ renderExamOrders([]); return; }
    try{
        const res = await api(`/api/workers/${workerId}/exam-orders`);
        state_studies.examOrders = Array.isArray(res?.data) ? res.data : [];
        renderExamOrders(state_studies.examOrders);
        populateOrderEvaluationSelect();
    } catch { state_studies.examOrders = []; renderExamOrders([]); }
}

function renderExamOrders(orders){
    const list = refs.examOrdersList;
    if(!list) return;
    list.innerHTML = "";
    if(!state.selectedWorkerId){ list.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`; return; }
    if(!orders.length){ list.innerHTML = `<p class="empty">No hay pedidos registrados.</p>`; return; }
    orders.forEach(o => {
        const card = document.createElement("div");
        card.className = "historyCard";
        const studies = (o.studies || []).map((s,i) => `<li>${i+1}. ${esc(s.name)}${s.notes ? ` <em style="color:#888;font-size:.78rem;">(${esc(s.notes)})</em>` : ""}</li>`).join("");
        const statusColor = { PENDING:"#f59e0b", COMPLETED:"#10b981", PARTIAL:"#3b82f6", CANCELLED:"#ef4444" };
        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                <div>
                    <p class="meta" style="margin:0;font-weight:700;">${esc(ORDER_TYPE_LABELS[o.order_type]||o.order_type)} &mdash; ${fmtDate(o.order_date)}</p>
                    <span style="font-size:.75rem;color:${statusColor[o.status]||"#888"};">${esc(ORDER_STATUS_LABELS[o.status]||o.status)}</span>
                    <span style="font-size:.75rem;color:#888;margin-left:8px;">${esc(ORDER_PRIORITY_LABELS[o.priority]||o.priority)}</span>
                </div>
                <div class="rowActions">
                    <button class="btn small" data-act="print-order" data-order-id="${o.id}" type="button">🖨️ Imprimir</button>
                    <button class="btn small" data-act="edit-order" data-order-id="${o.id}" type="button">Editar</button>
                    <button class="btn small" data-act="delete-order" data-order-id="${o.id}" type="button">Eliminar</button>
                </div>
            </div>
            ${o.clinical_indication ? `<p class="meta"><strong>Indicación:</strong> ${esc(o.clinical_indication)}</p>` : ""}
            <ul style="margin:4px 0 0 14px;font-size:.82rem;">${studies}</ul>
            ${o.ordered_by?.name ? `<p class="meta" style="margin-top:4px;font-size:.78rem;color:#888;">Solicitado por: ${esc(o.ordered_by.name)}</p>` : ""}`;
        list.appendChild(card);
    });
}

function populateOrderEvaluationSelect(){
    const sel = refs.orderEvaluation;
    if(!sel) return;
    sel.innerHTML = '<option value="">-- Sin evaluación --</option>';
    const evals = state.selectedWorkerHistory?.evaluations || [];
    evals.forEach(e => {
        const opt = document.createElement("option");
        opt.value = e.id;
        opt.textContent = `${fmtDate(e.attention_date)} — ${esc(e.evaluation_type)}`;
        sel.appendChild(opt);
    });
}

/* --- Study lines --- */
let selectedStudies = [];
function renderStudiesList(){
    if(!refs.examStudiesList) return;
    refs.examStudiesList.innerHTML = "";
    selectedStudies.forEach((s, i) => {
        const row = document.createElement("div");
        row.style.cssText = "display:grid;grid-template-columns:1fr 1fr auto;gap:6px;align-items:center;background:#f0f9f8;border-radius:6px;padding:5px 8px;";
        row.innerHTML = `<span style="font-size:.82rem;">${esc(s.name)}</span><input type="text" class="studyNoteInput" value="${esc(s.notes||"")}" placeholder="Indicación específica..." style="font-size:.8rem;"><button type="button" class="btn small" data-si="${i}" style="color:var(--danger);">✕</button>`;
        row.querySelector(".studyNoteInput").addEventListener("change", ev => { selectedStudies[i].notes = ev.target.value; });
        row.querySelector(`[data-si="${i}"]`).addEventListener("click", () => { selectedStudies.splice(i,1); renderStudiesList(); });
        refs.examStudiesList.appendChild(row);
    });
}

function showPresetDropdown(){
    const type = refs.studiesPresetType?.value || "LAB";
    const presets = STUDY_PRESETS[type] || [];
    const box = refs.studyPresetDropdown;
    if(!box) return;
    box.innerHTML = "";
    presets.forEach(name => {
        const already = selectedStudies.some(s => s.name === name);
        const item = document.createElement("div");
        item.style.cssText = `padding:6px 12px;cursor:pointer;font-size:.82rem;border-bottom:1px solid #eee;${already?"opacity:.4;":""}`;
        item.textContent = (already ? "✓ " : "") + name;
        if(!already) item.addEventListener("click", () => {
            selectedStudies.push({ name, notes:"" });
            renderStudiesList();
            box.classList.add("hidden");
        });
        box.appendChild(item);
    });
    box.classList.remove("hidden");
}

if(refs.addPresetStudyBtn) refs.addPresetStudyBtn.addEventListener("click", e => { e.stopPropagation(); showPresetDropdown(); });
if(refs.studiesPresetType) refs.studiesPresetType.addEventListener("change", () => { if(!refs.studyPresetDropdown?.classList.contains("hidden")) showPresetDropdown(); });
document.addEventListener("click", () => refs.studyPresetDropdown?.classList.add("hidden"));

if(refs.addCustomStudyBtn) refs.addCustomStudyBtn.addEventListener("click", () => {
    const name = prompt("Nombre del estudio / examen:");
    if(name?.trim()) { selectedStudies.push({ name: name.trim(), notes:"" }); renderStudiesList(); }
});

/* --- Exam order form --- */
function resetExamOrderForm(){
    if(!refs.examOrderForm) return;
    refs.orderEditId.value = "";
    refs.orderType.value = "LAB";
    refs.orderPriority.value = "NORMAL";
    refs.orderDate.value = new Date().toISOString().split("T")[0];
    refs.orderEvaluation.value = "";
    refs.orderClinicalIndication.value = "";
    refs.orderAdditionalNotes.value = "";
    selectedStudies = [];
    renderStudiesList();
    refs.orderSubmitBtn.textContent = "💾 Guardar pedido";
    refs.orderCancelBtn.style.display = "none";
    refs.examOrderFormTitle.textContent = "➕ Nuevo pedido";
}

function fillExamOrderForm(o){
    refs.orderEditId.value = o.id;
    refs.orderType.value = o.order_type || "LAB";
    refs.orderPriority.value = o.priority || "NORMAL";
    refs.orderDate.value = o.order_date || "";
    refs.orderEvaluation.value = o.evaluation_id || "";
    refs.orderClinicalIndication.value = o.clinical_indication || "";
    refs.orderAdditionalNotes.value = o.additional_notes || "";
    selectedStudies = (o.studies || []).map(s => ({ name: s.name||"", notes: s.notes||"" }));
    renderStudiesList();
    refs.orderSubmitBtn.textContent = "✏️ Actualizar pedido";
    refs.orderCancelBtn.style.display = "";
    refs.examOrderFormTitle.textContent = "Editar pedido";
    refs.examOrderForm.scrollIntoView({ behavior:"smooth", block:"center" });
}

if(refs.orderCancelBtn) refs.orderCancelBtn.addEventListener("click", () => resetExamOrderForm());

if(refs.examOrderForm){
    // init date
    if(refs.orderDate) refs.orderDate.value = new Date().toISOString().split("T")[0];

    refs.examOrderForm.addEventListener("submit", async e => {
        e.preventDefault();
        if(!selectedStudies.length){ showToast("Agrega al menos un estudio.", "warn"); return; }
        const payload = {
            order_type:          refs.orderType.value,
            priority:            refs.orderPriority.value,
            order_date:          refs.orderDate.value,
            evaluation_id:       refs.orderEvaluation.value || null,
            clinical_indication: refs.orderClinicalIndication.value.trim() || null,
            studies:             selectedStudies,
            additional_notes:    refs.orderAdditionalNotes.value.trim() || null,
        };
        const isEdit = refs.orderEditId.value;
        try{
            refs.orderSubmitBtn.disabled = true;
            if(isEdit){
                await api(`/api/workers/${state.selectedWorkerId}/exam-orders/${isEdit}`, "PUT", payload);
            } else {
                await api(`/api/workers/${state.selectedWorkerId}/exam-orders`, "POST", payload);
            }
            showToast(isEdit ? "Pedido actualizado." : "Pedido guardado.", "success");
            resetExamOrderForm();
            await loadExamOrders(state.selectedWorkerId);
        } catch(err){ showToast("Error: " + (err.message||""), "error"); }
        finally { refs.orderSubmitBtn.disabled = false; }
    });
}

if(refs.examOrdersList){
    refs.examOrdersList.addEventListener("click", async e => {
        const btn = e.target.closest("[data-act]");
        if(!btn) return;
        const act = btn.dataset.act;
        const orderId = btn.dataset.orderId;
        if(act === "print-order"){
            window.open(`/api/workers/${state.selectedWorkerId}/exam-orders/${orderId}/pdf`, "_blank");
        } else if(act === "edit-order"){
            const o = state_studies.examOrders.find(x => String(x.id) === String(orderId));
            if(o) fillExamOrderForm(o);
        } else if(act === "delete-order"){
            if(!confirm("¿Eliminar este pedido?")) return;
            try{
                await api(`/api/workers/${state.selectedWorkerId}/exam-orders/${orderId}`, "DELETE");
                showToast("Pedido eliminado.", "success");
                await loadExamOrders(state.selectedWorkerId);
            } catch { showToast("Error al eliminar.", "error"); }
        }
    });
}

/* --- Attachment Gallery --- */
async function loadWorkerAttachments(workerId){
    const gallery = refs.workerAttachmentGallery;
    if(!gallery) return;
    if(!workerId){ gallery.innerHTML = `<p class="empty">Sin trabajador seleccionado.</p>`; return; }
    try{
        const res = await api(`/api/workers/${workerId}/attachments`);
        state_studies.workerAttachments = Array.isArray(res?.data) ? res.data : [];
        renderAttachmentGallery();
        populateGalleryUploadEval();
    } catch { state_studies.workerAttachments = []; renderAttachmentGallery(); }
}

function renderAttachmentGallery(){
    const gallery = refs.workerAttachmentGallery;
    if(!gallery) return;
    gallery.innerHTML = "";
    const filter = state_studies.attachmentFilter;
    const attachments = filter === "ALL" ? state_studies.workerAttachments
        : state_studies.workerAttachments.filter(a => a.attachment_type === filter);
    if(!attachments.length){
        gallery.innerHTML = `<p class="empty">${state_studies.workerAttachments.length ? "Sin archivos de este tipo." : "No hay archivos subidos para este trabajador."}</p>`;
        return;
    }
    attachments.forEach(att => {
        const card = document.createElement("div");
        card.className = "historyCard";
        const ext = (att.original_extension||"").toLowerCase();
        const isImage = ["jpg","jpeg","png"].includes(ext);
        const isDicom = ["dcm","dicom","ima"].includes(ext);
        const isPdf   = ext === "pdf";
        const sizeLabel = att.file_size_bytes ? `${(att.file_size_bytes/1024/1024).toFixed(1)} MB` : "";
        const typeIcons = { GENERAL:"📄", LAB_EXAM:"🔬", IMAGING:"🩻", DICOM:"💿", AUDIO:"🔊", OTHER:"📎" };
        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap;">
                <div>
                    <p class="meta" style="margin:0;font-weight:600;">${typeIcons[att.attachment_type]||"📎"} ${esc(att.file_name)}</p>
                    <p class="meta" style="margin:2px 0;font-size:.78rem;color:#888;">${esc(att.attachment_type)} &middot; ${sizeLabel}${att.exam_date ? " &middot; Examen: " + fmtDate(att.exam_date) : ""}</p>
                    ${att.notes ? `<p class="meta" style="font-size:.8rem;">${esc(att.notes)}</p>` : ""}
                    <p class="meta" style="font-size:.75rem;color:#aaa;">Consulta: ${fmtDate(att.evaluation_date||att.created_at)}</p>
                </div>
                <div class="rowActions">
                    ${isImage ? `<button class="btn small" data-act="view-image" data-url="${att.file_url}" data-name="${esc(att.file_name)}" type="button">🔍 Ver</button>` : ""}
                    ${isPdf ? `<button class="btn small" data-act="view-pdf" data-url="${att.file_url}" type="button">📄 Ver PDF</button>` : ""}
                    ${isDicom ? `<button class="btn small" data-act="view-dicom" data-download="${att.download_path}" data-name="${esc(att.file_name)}" type="button">💿 Ver DICOM</button>` : ""}
                    <a href="${att.download_path}" class="btn small" target="_blank">⬇️ Descargar</a>
                </div>
            </div>
            ${isImage ? `<div style="margin-top:8px;"><img src="${att.file_url}" alt="${esc(att.file_name)}" style="max-height:120px;max-width:100%;border-radius:6px;cursor:pointer;object-fit:cover;" data-act="view-image" data-url="${att.file_url}" data-name="${esc(att.file_name)}"></div>` : ""}`;
        card.addEventListener("click", e => {
            const btn2 = e.target.closest("[data-act]");
            if(!btn2) return;
            const act2 = btn2.dataset.act;
            if(act2 === "view-image") openLightbox(btn2.dataset.url, btn2.dataset.name);
            else if(act2 === "view-pdf") window.open(btn2.dataset.url, "_blank");
            else if(act2 === "view-dicom") openDicomViewer(btn2.dataset.download, btn2.dataset.name);
        });
        gallery.appendChild(card);
    });
}

function populateGalleryUploadEval(){
    const sel = refs.galleryUploadEval;
    if(!sel) return;
    sel.innerHTML = '<option value="">-- Seleccionar consulta --</option>';
    const evals = state.selectedWorkerHistory?.evaluations || [];
    evals.forEach(e => {
        const opt = document.createElement("option");
        opt.value = e.id;
        opt.textContent = `${fmtDate(e.attention_date)} — ${esc(e.evaluation_type)}`;
        sel.appendChild(opt);
    });
}

/* Attachment type filter buttons */
if(refs.attachmentTypeFilters){
    refs.attachmentTypeFilters.addEventListener("click", e => {
        const btn = e.target.closest("[data-filter]");
        if(!btn) return;
        state_studies.attachmentFilter = btn.dataset.filter;
        refs.attachmentTypeFilters.querySelectorAll("[data-filter]").forEach(b => b.classList.toggle("active", b === btn));
        renderAttachmentGallery();
    });
}

/* Gallery upload */
if(refs.galleryUploadBtn){
    refs.galleryUploadBtn.addEventListener("click", async () => {
        const evalId = refs.galleryUploadEval?.value;
        const file   = refs.galleryUploadFile?.files?.[0];
        if(!evalId){ showToast("Selecciona una consulta.", "warn"); return; }
        if(!file)  { showToast("Selecciona un archivo.", "warn"); return; }
        const formData = new FormData();
        formData.append("file", file);
        formData.append("attachment_type", refs.galleryUploadType?.value || "GENERAL");
        if(refs.galleryUploadDate?.value) formData.append("exam_date", refs.galleryUploadDate.value);
        if(refs.galleryUploadNotes?.value) formData.append("notes", refs.galleryUploadNotes.value);
        refs.galleryUploadBtn.disabled = true;
        if(refs.galleryUploadStatus) refs.galleryUploadStatus.textContent = "Subiendo...";
        try{
            const token = localStorage.getItem("shcso_token");
            const res = await fetch(`/api/evaluations/${evalId}/attachments`, {
                method:"POST",
                headers:{ Authorization:`Bearer ${token}` },
                body: formData,
            });
            const data = await res.json();
            if(!data.ok) throw new Error(data.message || "Error al subir");
            showToast("Archivo subido correctamente.", "success");
            if(refs.galleryUploadStatus) refs.galleryUploadStatus.textContent = "✅ Subido";
            if(refs.galleryUploadFile) refs.galleryUploadFile.value = "";
            await loadWorkerAttachments(state.selectedWorkerId);
        } catch(err){
            showToast("Error: " + (err.message||""), "error");
            if(refs.galleryUploadStatus) refs.galleryUploadStatus.textContent = "❌ Error";
        } finally { refs.galleryUploadBtn.disabled = false; }
    });
}

/* --- Lightbox --- */
function openLightbox(url, name){
    if(!refs.imageLightboxModal) return;
    refs.lightboxImg.src = url;
    refs.lightboxCaption.textContent = name || "";
    refs.imageLightboxModal.classList.remove("hidden");
}
if(refs.lightboxClose) refs.lightboxClose.addEventListener("click", () => refs.imageLightboxModal?.classList.add("hidden"));
if(refs.imageLightboxModal) refs.imageLightboxModal.addEventListener("click", e => { if(e.target === refs.imageLightboxModal) refs.imageLightboxModal.classList.add("hidden"); });

/* --- DICOM Viewer (dwv.js) --- */
let dwvApp = null;
let dwvLoaded = false;

function loadDwvScript(){
    return new Promise((resolve) => {
        if(window.dwv){ resolve(); return; }
        const s = document.createElement("script");
        s.src = "https://cdn.jsdelivr.net/npm/dwv@0.31.0/dist/dwv.min.js";
        s.onload = resolve;
        s.onerror = () => { console.warn("dwv.js no se pudo cargar"); resolve(); };
        document.head.appendChild(s);
    });
}

async function openDicomViewer(downloadPath, fileName){
    if(!refs.dicomViewerModal) return;
    refs.dicomFileName.textContent = fileName || "";
    refs.dwvLoadingMsg.style.display = "flex";
    if(refs.layerGroup0) refs.layerGroup0.innerHTML = "";
    refs.dicomViewerModal.classList.remove("hidden");

    await loadDwvScript();

    if(!window.dwv){
        refs.dwvLoadingMsg.textContent = "El visor DICOM no pudo cargarse. Descarga el archivo para verlo con un visor local.";
        return;
    }

    try{
        const token = localStorage.getItem("shcso_token");
        // Fetch file as ArrayBuffer
        const res = await fetch(downloadPath, { headers:{ Authorization:`Bearer ${token}` } });
        if(!res.ok) throw new Error("No se pudo obtener el archivo");
        const buffer = await res.arrayBuffer();

        // Destroy previous app
        if(dwvApp){ try{ dwvApp.reset(); } catch{} dwvApp = null; }

        const layerDiv = document.getElementById("layerGroup0");
        if(!layerDiv){ refs.dwvLoadingMsg.textContent = "Error interno del visor."; return; }

        dwvApp = new dwv.App();
        dwvApp.init({
            dataViewConfigs: { "*": [{ divId: "layerGroup0" }] },
            tools: { ZoomAndPan: {}, WindowLevel: {} },
        });

        dwvApp.addEventListener("load", () => {
            refs.dwvLoadingMsg.style.display = "none";
            if(refs.dwvToolInfo) refs.dwvToolInfo.textContent = "Usa la rueda del mouse para zoom | Arrastra para mover | Clic derecho: contraste";
        });
        dwvApp.addEventListener("error", () => {
            refs.dwvLoadingMsg.style.display = "flex";
            refs.dwvLoadingMsg.textContent = "Error al renderizar DICOM. El archivo puede estar comprimido o no ser compatible.";
        });

        dwvApp.loadArrayBuffer([{ filename: fileName||"file.dcm", data: buffer }]);
        dwvApp.activateTool("ZoomAndPan");

    } catch(err){
        refs.dwvLoadingMsg.style.display = "flex";
        refs.dwvLoadingMsg.textContent = "Error: " + (err.message||"No se pudo cargar el DICOM");
    }
}

if(refs.dicomViewerClose) refs.dicomViewerClose.addEventListener("click", () => refs.dicomViewerModal?.classList.add("hidden"));
if(refs.dicomViewerModal) refs.dicomViewerModal.addEventListener("click", e => { if(e.target === refs.dicomViewerModal) refs.dicomViewerModal.classList.add("hidden"); });

if(refs.dwvResetBtn) refs.dwvResetBtn.addEventListener("click", () => { try{ dwvApp?.resetLayout(); } catch{} });
if(refs.dwvZoomInBtn) refs.dwvZoomInBtn.addEventListener("click", () => { try{ dwvApp?.zoom(0.1); } catch{} });
if(refs.dwvZoomOutBtn) refs.dwvZoomOutBtn.addEventListener("click", () => { try{ dwvApp?.zoom(-0.1); } catch{} });

/* Wire Tab 6 to loader */

async function searchMedicationCatalog(){
    const query = String(refs.rxMedication?.value || "").trim();
    if(query.length < 2){ hideRxMedResults(); return; }
    try{
        const res = await api(`/api/catalog/medications?${buildQueryString({ q:query, limit:8 })}`);
        const rows = Array.isArray(res?.data) ? res.data : (Array.isArray(res) ? res : []);
        renderRxMedResults(rows);
    } catch { hideRxMedResults(); }
}
function hideRxMedResults(){
    if(refs.rxMedicationResults) refs.rxMedicationResults.style.display = "none";
}
function renderRxMedResults(rows){
    if(!refs.rxMedicationResults) return;
    if(!rows.length){ refs.rxMedicationResults.style.display = "none"; return; }
    refs.rxMedicationResults.innerHTML = "";
    rows.forEach(m => {
        const item = document.createElement("div");
        item.className = "rxMedItem";
        item.innerHTML = `<strong>${esc(m.generic_name)}${m.concentration ? " " + esc(m.concentration) : ""}</strong><span>${esc(m.pharmaceutical_form||"")}${m.commercial_name ? " | " + esc(m.commercial_name) : ""}</span>`;
        item.addEventListener("mousedown", (e) => {
            e.preventDefault();
            const full = m.generic_name + (m.concentration ? " " + m.concentration : "");
            refs.rxMedication.value = full;
            if(refs.rxDosage && !refs.rxDosage.value) refs.rxDosage.value = m.concentration || "";
            hideRxMedResults();
        });
        refs.rxMedicationResults.appendChild(item);
    });
    refs.rxMedicationResults.style.display = "block";
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
        const canGeneratePdf = canIssueCertificates();
        const generateLabel = canGeneratePdf ? "Generar PDF" : "Sin permiso PDF";
        const row = document.createElement("tr");
        row.innerHTML = `<td>${esc(c.certificate_code)}</td><td>${fmtDate(c.issue_date)}</td><td>${esc(w.first_name || "")} ${esc(w.last_name || "")}</td><td><span class="pill ${aptitudePillClass(c.medical_aptitude)}">${esc(c.medical_aptitude)}</span></td>
        <td><div class="rowActions"><button class="btn" data-act="gen" data-id="${c.id}" type="button" ${canGeneratePdf ? "" : "disabled"}>${generateLabel}</button><button class="btn" data-act="down" data-id="${c.id}" type="button">Descargar</button></div></td>`;
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
    // Filtro por empresa en el listado de trabajadores
    if(refs.workerCompanyFilter){
        const prev = refs.workerCompanyFilter.value;
        refs.workerCompanyFilter.innerHTML = `<option value="">Todas</option>`;
        state.companies.forEach(c => refs.workerCompanyFilter.appendChild(makeOpt(c.id, c.business_name)));
        if(prev) refs.workerCompanyFilter.value = prev;
    }
    filterEvaluationWorkerOptions();
    [refs.certificateEvaluation, refs.attachmentEvaluation].forEach(sel => { sel.innerHTML=""; state.evaluations.forEach(e=>{ const w=e.worker||{}; sel.appendChild(makeOpt(e.id, `${e.evaluation_type} - ${w.first_name || ""} ${w.last_name || ""}`)); }); });
    if(refs.userRoleSelect && refs.userEditRoleSelect){
        refs.userRoleSelect.innerHTML = "";
        refs.userEditRoleSelect.innerHTML = "";
        state.roles.forEach(role => {
            refs.userRoleSelect.appendChild(makeOpt(role.name, role.name));
            refs.userEditRoleSelect.appendChild(makeOpt(role.name, role.name));
        });
    }
    applyCertificateFlowState();
}

function applyCertificateFlowState(){
    if(!refs.certificateCreateBtn) return;
    const canCreate = canIssueCertificates();
    const hasEvaluations = !!refs.certificateEvaluation && refs.certificateEvaluation.options.length > 0;
    refs.certificateCreateBtn.disabled = !canCreate || !hasEvaluations;
    if(refs.certificateFlowHint){
        if(!canCreate){
            refs.certificateFlowHint.textContent = "Tu rol no puede emitir certificados. Requiere ADMIN o MEDICO_OCUPACIONAL.";
        } else if(!hasEvaluations){
            refs.certificateFlowHint.textContent = "No hay evaluaciones disponibles para certificar.";
        } else {
            refs.certificateFlowHint.textContent = "Selecciona evaluacion y emite certificado.";
        }
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
    renderDiagnosisSearchResults();
    renderSelectedDiagnoses();
    renderPrescriptionList();
    renderWorkerHistory();
    applyPagerInfo("workers");
    applyPagerInfo("evaluations");
    applyPagerInfo("certificates");
    applyPagerInfo("users");
}
function showApp(){
    refs.loginSection.classList.add("hidden");
    refs.appSection.classList.remove("hidden");
    refs.refreshBtn.classList.remove("hidden");
    refs.logoutBtn.classList.remove("hidden");
    refs.miPerfilBtn.classList.remove("hidden");
    fillProfessionalFields();
}

function fillProfessionalFields(){
    if(!state.user) return;
    const name = state.user.full_name || "";
    const code = state.user.professional_code || "";
    if(refs.evalProfName) refs.evalProfName.value = name;
    if(refs.evalProfCode) refs.evalProfCode.value = code;
    if(refs.perfilFullName) refs.perfilFullName.value = name;
    if(refs.perfilProfCode) refs.perfilProfCode.value = code;
    // Show warning if professional code is missing
    const missingCode = !code.trim();
    if(refs.miPerfilWarn) refs.miPerfilWarn.classList.toggle("hidden", !missingCode);
}
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
        // Load authenticated-only widgets after login confirmed
        loadExpiringAlerts();
        loadTodayAppts();
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
    setWorkerFormEnabled(true);
    if(refs.workerFormModeHint) refs.workerFormModeHint.textContent = "Modo nuevo trabajador. Completa y guarda.";
    setWorkerStep("manage");
    if(refs.workerForm?.document_number) refs.workerForm.document_number.focus();
});

refs.workerFormResetBtn.addEventListener("click", () => {
    resetWorkerForm(true);
    status("Edicion cancelada. Puedes crear un nuevo trabajador.", "ok");
});

refs.evaluationWorkerSearch.addEventListener("input", (e) => {
    state.consultation.worker_search = e.target.value || "";
    filterEvaluationWorkerOptions();
});

refs.diagnosisSearchInput.addEventListener("input", () => {
    clearTimeout(diagnosisSearchTimer);
    diagnosisSearchTimer = setTimeout(searchDiagnosisCatalog, 260);
});

refs.diagnosisSearchResults.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-act='add-diagnosis']");
    if(!btn) return;
    const code = normalizeDiagnosisCode(btn.getAttribute("data-code"));
    const description = String(btn.getAttribute("data-description") || "").trim();
    if(code === "" || state.consultation.selected_diagnoses.some(x => x.code === code)) return;
    state.consultation.selected_diagnoses.push({ code, description, diagnosis_type:"DEF" });
    state.consultation.diagnosis_results = state.consultation.diagnosis_results.filter(x => normalizeDiagnosisCode(x.code) !== code);
    renderDiagnosisSearchResults();
    renderSelectedDiagnoses();
});

refs.selectedDiagnosesList.addEventListener("change", (e) => {
    const select = e.target.closest("select[data-act='diagnosis-type']");
    if(!select) return;
    const idx = Number(select.getAttribute("data-index"));
    if(!Number.isInteger(idx) || !state.consultation.selected_diagnoses[idx]) return;
    state.consultation.selected_diagnoses[idx].diagnosis_type = select.value === "PRE" ? "PRE" : "DEF";
});

refs.selectedDiagnosesList.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-act='remove-diagnosis']");
    if(!btn) return;
    const idx = Number(btn.getAttribute("data-index"));
    if(!Number.isInteger(idx) || !state.consultation.selected_diagnoses[idx]) return;
    state.consultation.selected_diagnoses.splice(idx, 1);
    renderSelectedDiagnoses();
});

refs.addPrescriptionBtn.addEventListener("click", () => {
    const medication = String(refs.rxMedication.value || "").trim();
    const dosage = String(refs.rxDosage.value || "").trim();
    if(medication.length < 2 || dosage.length < 1){
        status("Medicamento y dosis son obligatorios para agregar receta.", "error");
        return;
    }
    state.consultation.prescriptions.push({
        medication,
        dosage,
        frequency: compactText(refs.rxFrequency.value),
        duration: compactText(refs.rxDuration.value),
        indications: compactText(refs.rxIndications.value),
    });
    refs.rxMedication.value = "";
    refs.rxDosage.value = "";
    refs.rxFrequency.value = "";
    refs.rxDuration.value = "";
    refs.rxIndications.value = "";
    renderPrescriptionList();
});

refs.prescriptionList.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-act='remove-rx']");
    if(!btn) return;
    const idx = Number(btn.getAttribute("data-index"));
    if(!Number.isInteger(idx) || !state.consultation.prescriptions[idx]) return;
    state.consultation.prescriptions.splice(idx, 1);
    renderPrescriptionList();
});

/* ─── MEDICATION AUTOCOMPLETE EVENTS ─── */
refs.rxMedication.addEventListener("input", () => {
    clearTimeout(rxMedTimer);
    rxMedTimer = setTimeout(searchMedicationCatalog, 220);
});
refs.rxMedication.addEventListener("blur", () => {
    setTimeout(hideRxMedResults, 150);
});

/* ─── VACUNACIÓN ─── */
function populateVaccineDatalist(){
    const dl = document.getElementById("vaccineDatalist");
    if(!dl) return;
    dl.innerHTML = COMMON_VACCINES.map(v => `<option value="${v}">`).join("");
}

async function loadVaccinations(workerId){
    try{
        const res = await api(`/api/workers/${workerId}/vaccinations`);
        state_vaccines.list = res.data || [];
        renderVaccinations(state_vaccines.list);
    } catch(err){ console.error("Error cargando vacunas:", err); }
}

function renderVaccinations(list){
    const el = document.getElementById("vaccinesList");
    if(!el) return;
    if(!list.length){ el.innerHTML = "<p class='hint'>Sin registros de vacunación.</p>"; return; }
    el.innerHTML = `
    <table class="tableCompact" style="width:100%;border-collapse:collapse;">
        <thead><tr>
            <th>Vacuna</th><th>Dosis</th><th>Aplicada</th><th>Próx. dosis</th><th>Lote</th><th>Notas</th><th></th>
        </tr></thead>
        <tbody>
        ${list.map(v => `
            <tr>
                <td>${e(v.vaccine_name)}</td>
                <td style="text-align:center;">${v.dose_number}</td>
                <td>${v.applied_date || '-'}</td>
                <td>${v.next_dose_date ? `<span style="color:${isOverdue(v.next_dose_date)?'#e53935':'inherit'}">${v.next_dose_date}</span>` : '-'}</td>
                <td>${e(v.lot_number||'-')}</td>
                <td>${e(v.notes||'')}</td>
                <td style="white-space:nowrap;">
                    <button class="btn small" onclick="editVaccine(${v.id})">✏️</button>
                    <button class="btn small danger" onclick="deleteVaccine(${v.id})">🗑</button>
                </td>
            </tr>`).join('')}
        </tbody>
    </table>`;
}

function isOverdue(dateStr){ return dateStr && new Date(dateStr) < new Date(); }
function e(s){ return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function resetVaccForm(){
    document.getElementById("vaccineId").value = "";
    document.getElementById("vaccineNameInput").value = "";
    document.getElementById("vaccineDoseNumber").value = "1";
    document.getElementById("vaccineAppliedDate").value = "";
    document.getElementById("vaccineNextDoseDate").value = "";
    document.getElementById("vaccineLotNumber").value = "";
    document.getElementById("vaccineManufacturer").value = "";
    document.getElementById("vaccineNotes").value = "";
    const msg = document.getElementById("vaccineFormMsg");
    if(msg) msg.textContent = "";
    const cancelBtn = document.getElementById("vaccineCancelBtn");
    if(cancelBtn) cancelBtn.style.display = "none";
}

async function editVaccine(id){
    const v = state_vaccines.list.find(x => x.id === id);
    if(!v) return;
    document.getElementById("vaccineId").value = v.id;
    document.getElementById("vaccineNameInput").value = v.vaccine_name;
    document.getElementById("vaccineDoseNumber").value = v.dose_number;
    document.getElementById("vaccineAppliedDate").value = v.applied_date || "";
    document.getElementById("vaccineNextDoseDate").value = v.next_dose_date || "";
    document.getElementById("vaccineLotNumber").value = v.lot_number || "";
    document.getElementById("vaccineManufacturer").value = v.manufacturer || "";
    document.getElementById("vaccineNotes").value = v.notes || "";
    const cancelBtn = document.getElementById("vaccineCancelBtn");
    if(cancelBtn) cancelBtn.style.display = "";
    document.getElementById("vaccineNameInput").focus();
}

async function deleteVaccine(id){
    if(!confirm("¿Eliminar este registro de vacuna?")) return;
    const workerId = state.selectedWorkerId;
    try{
        await api(`/api/workers/${workerId}/vaccinations/${id}`, "DELETE");
        status("Vacuna eliminada.", "ok");
        await loadVaccinations(workerId);
    } catch(err){ status(err.message || "No se pudo eliminar.", "error"); }
}

const vaccineForm = document.getElementById("vaccineForm");
if(vaccineForm){
    vaccineForm.addEventListener("submit", async (ev) => {
        ev.preventDefault();
        const workerId = state.selectedWorkerId;
        if(!workerId){ status("Selecciona un trabajador primero.", "error"); return; }
        const vaccineId = document.getElementById("vaccineId").value;
        const payload = {
            vaccine_name:   document.getElementById("vaccineNameInput").value.trim(),
            dose_number:    Number(document.getElementById("vaccineDoseNumber").value),
            applied_date:   document.getElementById("vaccineAppliedDate").value || null,
            next_dose_date: document.getElementById("vaccineNextDoseDate").value || null,
            lot_number:     document.getElementById("vaccineLotNumber").value.trim() || null,
            manufacturer:   document.getElementById("vaccineManufacturer").value.trim() || null,
            notes:          document.getElementById("vaccineNotes").value.trim() || null,
        };
        const msg = document.getElementById("vaccineFormMsg");
        if(!payload.vaccine_name){ if(msg) msg.textContent = "El nombre de la vacuna es requerido."; return; }
        try{
            if(vaccineId){
                await api(`/api/workers/${workerId}/vaccinations/${vaccineId}`, "PUT", payload);
                status("Vacuna actualizada.", "ok");
            } else {
                await api(`/api/workers/${workerId}/vaccinations`, "POST", payload);
                status("Vacuna registrada.", "ok");
            }
            resetVaccForm();
            await loadVaccinations(workerId);
        } catch(err){ status(err.message || "No se pudo guardar.", "error"); }
    });
    const cancelVacc = document.getElementById("vaccineCancelBtn");
    if(cancelVacc) cancelVacc.addEventListener("click", () => resetVaccForm());
}
populateVaccineDatalist();

/* ─── ACCIDENTES LABORALES ─── */
async function loadAccidents(workerId){
    try{
        const res = await api(`/api/workers/${workerId}/accidents`);
        state_accidents.list = res.data || [];
        renderAccidents(state_accidents.list);
    } catch(err){ console.error("Error cargando accidentes:", err); }
}

const SEVERITY_LABELS = { MINOR:"Leve", MODERATE:"Moderado", SERIOUS:"Grave", FATAL:"Fatal" };
const ACCIDENT_TYPE_LABELS = { ACCIDENT:"Accidente", NEAR_MISS:"Casi-accidente", OCCUPATIONAL_DISEASE:"Enf. Ocupacional", COMMUTING:"In itinere" };
const ACCIDENT_STATUS_LABELS = { OPEN:"Abierto", INVESTIGATING:"Investigando", CLOSED:"Cerrado" };
const ACCIDENT_STATUS_COLORS = { OPEN:"#e53935", INVESTIGATING:"#fb8c00", CLOSED:"#2e7d32" };

function renderAccidents(list){
    const el = document.getElementById("accidentsList");
    if(!el) return;
    if(!list.length){ el.innerHTML = "<p class='hint'>Sin accidentes registrados.</p>"; return; }
    el.innerHTML = `
    <table class="tableCompact" style="width:100%;border-collapse:collapse;">
        <thead><tr>
            <th>Fecha</th><th>Tipo</th><th>Severidad</th><th>Estado</th><th>Días</th><th>IESS</th><th></th>
        </tr></thead>
        <tbody>
        ${list.map(a => `
            <tr>
                <td>${a.accident_date || '-'}</td>
                <td>${ACCIDENT_TYPE_LABELS[a.accident_type] || a.accident_type}</td>
                <td>${SEVERITY_LABELS[a.severity] || a.severity}</td>
                <td><span style="color:${ACCIDENT_STATUS_COLORS[a.status]||'inherit'};font-weight:600;">${ACCIDENT_STATUS_LABELS[a.status]||a.status}</span></td>
                <td style="text-align:center;">${a.lost_days ?? '-'}</td>
                <td>${a.iess_reported ? `✅ ${a.at01_number||''}` : '—'}</td>
                <td style="white-space:nowrap;">
                    <button class="btn small" onclick="editAccident(${a.id})">✏️</button>
                    <button class="btn small" onclick="printAccidentPdf(${a.id})">🖨</button>
                    <button class="btn small danger" onclick="deleteAccident(${a.id})">🗑</button>
                </td>
            </tr>`).join('')}
        </tbody>
    </table>`;
}

function resetAccidentForm(){
    document.getElementById("accidentId").value = "";
    ["accidentDate","accidentTime","accidentLocation","accidentBodyPart","accidentInjuryType",
     "accidentDescription","accidentImmediateCause","accidentRootCause","accidentCorrectiveActions",
     "accidentPreventiveActions","accidentAt01Number","accidentIessDate"].forEach(id => {
         const el = document.getElementById(id);
         if(el) el.value = "";
     });
    document.getElementById("accidentLostDays").value = "0";
    document.getElementById("accidentType").value = "ACCIDENT";
    document.getElementById("accidentSeverity").value = "MINOR";
    document.getElementById("accidentStatus").value = "OPEN";
    const iessCheck = document.getElementById("accidentIessReported");
    if(iessCheck){ iessCheck.checked = false; }
    document.getElementById("accidentIessRow").style.display = "none";
    const msg = document.getElementById("accidentFormMsg");
    if(msg) msg.textContent = "";
    const cancelBtn = document.getElementById("accidentCancelBtn");
    if(cancelBtn) cancelBtn.style.display = "none";
    const title = document.getElementById("accidentFormTitle");
    if(title) title.textContent = "Nuevo Reporte AT-01";
}

function editAccident(id){
    const a = state_accidents.list.find(x => x.id === id);
    if(!a) return;
    document.getElementById("accidentId").value = a.id;
    document.getElementById("accidentDate").value = a.accident_date || "";
    document.getElementById("accidentTime").value = a.accident_time || "";
    document.getElementById("accidentType").value = a.accident_type || "ACCIDENT";
    document.getElementById("accidentSeverity").value = a.severity || "MINOR";
    document.getElementById("accidentLocation").value = a.accident_location || "";
    document.getElementById("accidentBodyPart").value = a.body_part_affected || "";
    document.getElementById("accidentInjuryType").value = a.injury_type || "";
    document.getElementById("accidentLostDays").value = a.lost_days ?? 0;
    document.getElementById("accidentDescription").value = a.description || "";
    document.getElementById("accidentImmediateCause").value = a.immediate_cause || "";
    document.getElementById("accidentRootCause").value = a.root_cause || "";
    document.getElementById("accidentCorrectiveActions").value = a.corrective_actions || "";
    document.getElementById("accidentPreventiveActions").value = a.preventive_actions || "";
    document.getElementById("accidentStatus").value = a.status || "OPEN";
    const iessCheck = document.getElementById("accidentIessReported");
    if(iessCheck){ iessCheck.checked = !!a.iess_reported; }
    document.getElementById("accidentIessRow").style.display = a.iess_reported ? "" : "none";
    document.getElementById("accidentAt01Number").value = a.at01_number || "";
    document.getElementById("accidentIessDate").value = a.iess_report_date || "";
    const cancelBtn = document.getElementById("accidentCancelBtn");
    if(cancelBtn) cancelBtn.style.display = "";
    const title = document.getElementById("accidentFormTitle");
    if(title) title.textContent = "Editar Reporte AT-01";
    document.getElementById("accidentDate").focus();
}

async function deleteAccident(id){
    if(!confirm("¿Eliminar este reporte de accidente?")) return;
    const workerId = state.selectedWorkerId;
    try{
        await api(`/api/workers/${workerId}/accidents/${id}`, "DELETE");
        status("Reporte eliminado.", "ok");
        await loadAccidents(workerId);
    } catch(err){ status(err.message || "No se pudo eliminar.", "error"); }
}

async function printAccidentPdf(id){
    const workerId = state.selectedWorkerId;
    const token = localStorage.getItem("shcso_token");
    const url = `${window.location.origin}/api/workers/${workerId}/accidents/${id}/pdf`;
    try{
        const response = await fetch(url, { headers:{ Authorization:`Bearer ${token}` } });
        if(!response.ok) throw new Error("Error al generar PDF");
        const blob = await response.blob();
        const blobUrl = URL.createObjectURL(blob);
        window.open(blobUrl, "_blank");
    } catch(err){ status(err.message || "No se pudo generar el PDF.", "error"); }
}

const accidentForm = document.getElementById("accidentForm");
if(accidentForm){
    const iessCheck = document.getElementById("accidentIessReported");
    if(iessCheck){
        iessCheck.addEventListener("change", () => {
            document.getElementById("accidentIessRow").style.display = iessCheck.checked ? "" : "none";
        });
    }

    accidentForm.addEventListener("submit", async (ev) => {
        ev.preventDefault();
        const workerId = state.selectedWorkerId;
        if(!workerId){ status("Selecciona un trabajador primero.", "error"); return; }
        const accidentId = document.getElementById("accidentId").value;
        const payload = {
            accident_date:       document.getElementById("accidentDate").value || null,
            accident_time:       document.getElementById("accidentTime").value || null,
            accident_type:       document.getElementById("accidentType").value,
            severity:            document.getElementById("accidentSeverity").value,
            accident_location:   document.getElementById("accidentLocation").value.trim() || null,
            body_part_affected:  document.getElementById("accidentBodyPart").value.trim() || null,
            injury_type:         document.getElementById("accidentInjuryType").value.trim() || null,
            lost_days:           Number(document.getElementById("accidentLostDays").value) || 0,
            description:         document.getElementById("accidentDescription").value.trim(),
            immediate_cause:     document.getElementById("accidentImmediateCause").value.trim() || null,
            root_cause:          document.getElementById("accidentRootCause").value.trim() || null,
            corrective_actions:  document.getElementById("accidentCorrectiveActions").value.trim() || null,
            preventive_actions:  document.getElementById("accidentPreventiveActions").value.trim() || null,
            status:              document.getElementById("accidentStatus").value,
            iess_reported:       document.getElementById("accidentIessReported").checked,
            at01_number:         document.getElementById("accidentAt01Number").value.trim() || null,
            iess_report_date:    document.getElementById("accidentIessDate").value || null,
        };
        if(!payload.accident_date || !payload.description){
            const msg = document.getElementById("accidentFormMsg");
            if(msg) msg.textContent = "La fecha y descripción del accidente son requeridas.";
            return;
        }
        try{
            if(accidentId){
                await api(`/api/workers/${workerId}/accidents/${accidentId}`, "PUT", payload);
                status("Reporte actualizado.", "ok");
            } else {
                await api(`/api/workers/${workerId}/accidents`, "POST", payload);
                status("Reporte AT-01 guardado.", "ok");
            }
            resetAccidentForm();
            await loadAccidents(workerId);
        } catch(err){ status(err.message || "No se pudo guardar.", "error"); }
    });

    const cancelAcc = document.getElementById("accidentCancelBtn");
    if(cancelAcc) cancelAcc.addEventListener("click", () => resetAccidentForm());
}

/* ─── EVOLUCIONES EVENT LISTENERS ─── */
refs.workerFlowTabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const step = tab.getAttribute("data-worker-step");
        if(step === "evolutions" && state.selectedWorkerId){
            loadWorkerEvolutions(state.selectedWorkerId).then(() => {
                if(!refs.rxMedLines?.children.length) addRxMedLine();
            });
        }
        if(step === "studies" && state.selectedWorkerId){
            loadExamOrders(state.selectedWorkerId);
            loadWorkerAttachments(state.selectedWorkerId);
            resetExamOrderForm();
        }
        if(step === "vaccines" && state.selectedWorkerId){
            loadVaccinations(state.selectedWorkerId);
        }
        if(step === "accidents" && state.selectedWorkerId){
            loadAccidents(state.selectedWorkerId);
        }
    });
});

if(refs.evolutionForm){
    refs.evolutionForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const workerId = state.selectedWorkerId;
        if(!workerId){ status("Selecciona un trabajador primero.", "error"); return; }
        const payload = {
            evolution_type: refs.evoType.value,
            evaluation_id: refs.evoEvaluation.value || null,
            subjective: compactText(refs.evoSubjective.value),
            objective: compactText(refs.evoObjective.value),
            assessment: compactText(refs.evoAssessment.value),
            plan: compactText(refs.evoPlan.value),
            notes: compactText(refs.evoNotes.value),
            vital_signs: {
                bp: compactText(refs.evoBP.value),
                temp: compactText(refs.evoTemp.value),
                hr: compactText(refs.evoHR.value),
                rr: compactText(refs.evoRR.value),
                weight: compactText(refs.evoWeight.value),
                height: compactText(refs.evoHeight.value),
            },
        };
        const editId = refs.evoEditId.value;
        try{
            if(editId){
                await api(`/api/workers/${workerId}/evolutions/${editId}`, "PUT", payload);
                status("Evolución actualizada.", "ok");
            } else {
                await api(`/api/workers/${workerId}/evolutions`, "POST", payload);
                status("Evolución guardada.", "ok");
            }
            resetEvolutionForm();
            await loadWorkerEvolutions(workerId);
        } catch(err){ status(err.message || "No se pudo guardar la evolucion.", "error"); }
    });
}

if(refs.evoCancelBtn){
    refs.evoCancelBtn.addEventListener("click", () => { resetEvolutionForm(); });
}

if(refs.workerEvolutionsList){
    refs.workerEvolutionsList.addEventListener("click", async (e) => {
        const btn = e.target.closest("button[data-act]");
        if(!btn) return;
        const evoId = btn.getAttribute("data-evo-id");
        const act = btn.getAttribute("data-act");
        const workerId = state.selectedWorkerId;
        if(!workerId || !evoId) return;
        if(act === "delete-evo"){
            if(!window.confirm("Eliminar esta evolucion?")) return;
            try{
                await api(`/api/workers/${workerId}/evolutions/${evoId}`, "DELETE");
                status("Evolución eliminada.", "ok");
                await loadWorkerEvolutions(workerId);
            } catch(err){ status(err.message || "No se pudo eliminar.", "error"); }
        }
        if(act === "edit-evo"){
            const ev = state.selectedWorkerEvolutions.find(x => String(x.id) === String(evoId));
            if(ev){ fillEvolutionForm(ev); refs.evoSubmitBtn.scrollIntoView({ behavior:"smooth" }); }
        }
    });
}

/* ─── IMPRIMIR RECETA ─── */
if(refs.workerHistoryEval){
    refs.workerHistoryEval.addEventListener("click", async (e) => {
        const btn = e.target.closest("button[data-act='print-prescription']");
        if(!btn) return;
        const evalId = btn.getAttribute("data-eval-id");
        if(!evalId) return;
        btn.disabled = true;
        status("Generando receta PDF...");
        try{
            await downloadWithToken(`/api/evaluations/${evalId}/prescription-pdf`, `receta-${evalId}.pdf`);
            status("Receta descargada.", "ok");
        } catch(err){ status(err.message || "No se pudo generar receta.", "error"); }
        finally { btn.disabled = false; }
    });
}

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
        setWorkerFormEnabled(true);
        setWorkerFormMode("edit");
        setWorkerStep("manage");
        status("Trabajador listo para edicion.", "ok");
        if(refs.workerForm?.first_name) refs.workerForm.first_name.focus();
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
    const soapS = String(f.get("soap_s") || "").trim();
    const soapO = String(f.get("soap_o") || "").trim();
    const soapA = String(f.get("soap_a") || "").trim();
    const soapP = String(f.get("soap_p") || "").trim();
    if(soapS.length < 5 || soapO.length < 5 || soapA.length < 5 || soapP.length < 5){
        status("Completa los campos SOAP obligatorios.", "error");
        return;
    }
    if(!state.consultation.selected_diagnoses.length){
        status("Agrega al menos un diagnostico CIE-10.", "error");
        return;
    }
    const vitalSigns = {
        blood_pressure: compactText(f.get("vs_bp")),
        temperature_c: toNullableNumber(f.get("vs_temp")),
        heart_rate: toNullableNumber(f.get("vs_hr")),
        respiratory_rate: toNullableNumber(f.get("vs_rr")),
        weight_kg: toNullableNumber(f.get("vs_weight")),
        height_cm: toNullableNumber(f.get("vs_height")),
    };
    const payload = {
        worker_id:f.get("worker_id"),
        evaluation_type:f.get("evaluation_type"),
        attention_date:f.get("attention_date") || null,
        consultation_reason:soapS,
        current_problem:soapA,
        physical_exam:{ soap_o: soapO },
        exam_results:{ soap_a: soapA },
        medical_aptitude:f.get("medical_aptitude"),
        recommendations:soapP,
        professional_name:f.get("professional_name"),
        professional_code:f.get("professional_code"),
        vital_signs:vitalSigns,
        diagnoses:state.consultation.selected_diagnoses.map((d) => ({
            code:d.code,
            description:d.description,
            diagnosis_type:d.diagnosis_type || "DEF",
            notes:soapA,
        })),
        prescriptions:state.consultation.prescriptions,
    };
    try{
        const res = await api("/api/evaluations",{method:"POST", body:payload});
        if(res?.data?.worker_id){ await loadWorkerHistory(res.data.worker_id); }
        status("Consulta medica registrada.", "ok");
        e.target.reset();
        resetConsultationState();
        setOperationStep("certificate");
        await refreshData();
    } catch(err){ status(err.message || "No se pudo guardar consulta medica.", "error"); }
});

document.getElementById("certificateForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const f = new FormData(e.target);
    const evaluationId = String(f.get("evaluation_id") || "").trim();
    if(!canIssueCertificates()){
        status("Sin permiso para crear certificados. Requiere rol ADMIN o MEDICO_OCUPACIONAL.", "error");
        return;
    }
    if(evaluationId === ""){
        status("Selecciona una evaluacion para crear el certificado.", "error");
        return;
    }
    if(refs.certificateCreateBtn) refs.certificateCreateBtn.disabled = true;
    status("Creando certificado...");
    try{
        await api(`/api/certificates/from-evaluation/${evaluationId}`,{method:"POST", body:{observations:f.get("observations"), recommendations:f.get("recommendations")}});
        status("Certificado creado.", "ok");
        setOperationStep("certificates");
        await refreshData();
    } catch(err){
        if(err?.status === 403){
            status("Sin permiso para crear certificados. Requiere rol ADMIN o MEDICO_OCUPACIONAL.", "error");
            return;
        }
        status(err.message || "No se pudo crear certificado.", "error");
    } finally {
        applyCertificateFlowState();
    }
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

refs.operationFlowTabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const nextStep = tab.getAttribute("data-operation-step");
        if(nextStep) setOperationStep(nextStep);
    });
});

window.addEventListener("popstate", () => {
    setView(resolveViewFromPath(), false);
});

/* ─── ALERTAS: CERTIFICADOS POR VENCER ─── */
async function loadExpiringAlerts(){
    try{
        const res = await api("/api/certificates/expiring?days=30");
        const list = res.data || [];
        const banner = document.getElementById("alertsBanner");
        const countEl = document.getElementById("alertsCount");
        const listEl = document.getElementById("alertsList");
        const emptyEl = document.getElementById("alertsEmpty");
        if(!banner) return;
        if(!list.length){
            banner.style.display = "none";
            return;
        }
        banner.style.display = "";
        if(countEl) countEl.textContent = list.length;
        if(!listEl) return;
        if(emptyEl) emptyEl.style.display = "none";
        listEl.innerHTML = `
        <table class="tableCompact" style="width:100%;border-collapse:collapse;">
            <thead><tr>
                <th>Trabajador</th><th>Empresa</th><th>Certificado</th><th>Aptitud</th><th>Vence</th><th>Días restantes</th>
            </tr></thead>
            <tbody>
            ${list.map(c => {
                const daysLeft = c.days_left;
                const color = daysLeft <= 7 ? '#e53935' : daysLeft <= 14 ? '#fb8c00' : '#1565c0';
                return `<tr>
                    <td>${e(c.worker?.full_name||'-')}</td>
                    <td>${e(c.worker?.company||'-')}</td>
                    <td>${e(c.certificate_code)}</td>
                    <td>${e(c.medical_aptitude)}</td>
                    <td>${c.valid_until}</td>
                    <td style="font-weight:700;color:${color};">${daysLeft} días</td>
                </tr>`;
            }).join('')}
            </tbody>
        </table>`;
    } catch(err){ console.warn("No se pudieron cargar alertas:", err.message); }
}

/* ─── EXCEL EXPORT ─── */
const xlsExportBtn = document.getElementById("xlsExportBtn");
if(xlsExportBtn){
    xlsExportBtn.addEventListener("click", async () => {
        const type  = document.getElementById("xlsType").value;
        const from  = document.getElementById("xlsFrom").value;
        const to    = document.getElementById("xlsTo").value;
        const msg   = document.getElementById("xlsMsg");
        const token = localStorage.getItem("shcso_token");
        let url = `/api/reports/export-excel?type=${encodeURIComponent(type)}`;
        if(from) url += `&date_from=${encodeURIComponent(from)}`;
        if(to)   url += `&date_to=${encodeURIComponent(to)}`;
        if(msg){ msg.textContent = "⏳ Generando Excel..."; msg.style.color = "#475569"; }
        try{
            const response = await fetch(url, { headers:{ Authorization:`Bearer ${token}` } });
            if(!response.ok){ const err = await response.json().catch(()=>({})); throw new Error(err.message || "Error generando reporte"); }
            const blob = await response.blob();
            const cd   = response.headers.get("Content-Disposition") || "";
            const match = cd.match(/filename="?([^";\n]+)"?/);
            const filename = match?.[1] || `reporte-${type}.xlsx`;
            const blobUrl = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = blobUrl; a.download = filename; a.click();
            URL.revokeObjectURL(blobUrl);
            if(msg){ msg.textContent = "✅ Descarga iniciada."; msg.style.color = "#2e7d32"; }
            setTimeout(() => { if(msg) msg.textContent = ""; }, 4000);
        } catch(err){
            if(msg){ msg.textContent = "❌ " + (err.message || "Error al exportar."); msg.style.color = "#e53935"; }
        }
    });
}

/* ─── CITAS DE HOY (DASHBOARD WIDGET) ─── */
async function loadTodayAppts(){
    try{
        const res = await api("/api/appointments/today");
        const list = (res.data || []);
        if(!refs.todayApptsList) return;
        refs.todayApptsCount.textContent = list.length;
        if(!list.length){
            refs.todayApptsList.innerHTML = "";
            refs.todayApptsEmpty.style.display = "";
            return;
        }
        refs.todayApptsEmpty.style.display = "none";
        const statusColor = { PROGRAMADA:'#1565c0', CONFIRMADA:'#2e7d32', CANCELADA:'#e53935', COMPLETADA:'#546e7a', EN_CURSO:'#f57c00' };
        refs.todayApptsList.innerHTML = `
        <table class="tableCompact" style="width:100%;border-collapse:collapse;">
            <thead><tr>
                <th>Hora</th><th>Trabajador</th><th>Tipo</th><th>Estado</th><th>Motivo</th>
            </tr></thead>
            <tbody>
            ${list.map(a => {
                const hour = a.appointment_time ? a.appointment_time.substring(0,5) : (a.scheduled_at ? a.scheduled_at.substring(11,16) : '—');
                const col  = statusColor[a.status] || '#546e7a';
                return `<tr>
                    <td style="font-weight:700;">${hour}</td>
                    <td>${e(a.worker_name || '—')}</td>
                    <td>${e(a.appointment_type || '—')}</td>
                    <td style="color:${col};font-weight:600;">${e(a.status)}</td>
                    <td>${e(a.reason || '—')}</td>
                </tr>`;
            }).join('')}
            </tbody>
        </table>`;
    } catch(err){ console.warn("No se pudieron cargar citas de hoy:", err.message); }
}

/* ─── CONFIGURACIÓN DEL SISTEMA ─── */
let _settingsLoaded = false;
async function loadSettings(){
    if(_settingsLoaded) return;
    try{
        const res = await api("/api/settings");
        const d = res.data || {};
        document.getElementById("cfgInstitutionName").value    = d.institution_name     || "";
        document.getElementById("cfgInstitutionSubtitle").value= d.institution_subtitle || "";
        document.getElementById("cfgInstitutionCity").value    = d.institution_city     || "";
        document.getElementById("cfgFooterNote").value         = d.footer_note          || "";
        document.getElementById("cfgSignatureName").value      = d.signature_name       || "";
        document.getElementById("cfgSignatureTitle").value     = d.signature_title      || "";
        document.getElementById("cfgProfessionalCode").value   = d.professional_code    || "";
        document.getElementById("cfgProfessionalTitle").value  = d.professional_title   || "";
        // Nuevos campos de institución
        const cfgRuc  = document.getElementById("cfgInstitutionRuc");
        const cfgAddr = document.getElementById("cfgInstitutionAddress");
        const cfgPhone= document.getElementById("cfgInstitutionPhone");
        const cfgEmail= document.getElementById("cfgInstitutionEmail");
        const cfgRep  = document.getElementById("cfgInstitutionRepresentative");
        if(cfgRuc)   cfgRuc.value   = d.institution_ruc            || "";
        if(cfgAddr)  cfgAddr.value  = d.institution_address        || "";
        if(cfgPhone) cfgPhone.value = d.institution_phone          || "";
        if(cfgEmail) cfgEmail.value = d.institution_email          || "";
        if(cfgRep)   cfgRep.value   = d.institution_representative || "";
        renderSettingsImagePreview("logo",      d.logo_url);
        renderSettingsImagePreview("signature", d.signature_url);
        renderSettingsImagePreview("seal",      d.seal_url);
        // Panel de confirmación visual
        const prev = document.getElementById("settingsPreview");
        if(prev){
            const rows = [
                ["Institución",         d.institution_name           || "(sin configurar)"],
                ["Subtítulo",           d.institution_subtitle       || "(sin configurar)"],
                ["Ciudad",              d.institution_city           || "(sin configurar)"],
                ["RUC",                 d.institution_ruc            || "(sin configurar)"],
                ["Dirección",           d.institution_address        || "(sin configurar)"],
                ["Teléfono",            d.institution_phone          || "(sin configurar)"],
                ["Email",               d.institution_email          || "(sin configurar)"],
                ["Representante legal", d.institution_representative || "(sin configurar)"],
                ["Médico responsable",  d.signature_name             || "(sin configurar)"],
                ["Cargo / título",      d.signature_title            || "(sin configurar)"],
                ["Código profesional",  d.professional_code          || "(sin configurar)"],
            ];
            prev.innerHTML = `<table style="border-collapse:collapse;width:100%;font-size:.86rem;">
                ${rows.map(([k,v]) => `<tr>
                    <td style="padding:4px 10px 4px 0;color:var(--muted);font-weight:600;width:38%;vertical-align:top;">${e(k)}</td>
                    <td style="padding:4px 0;color:var(--text);">${e(v)}</td>
                </tr>`).join('')}
            </table>`;
        }
        _settingsLoaded = true;
    } catch(err){ status("Error cargando configuración: " + err.message, "error"); }
}

function renderSettingsImagePreview(type, url){
    const el = document.getElementById(`${type}Preview`);
    if(!el) return;
    if(url){
        el.innerHTML = `<img src="${url}" alt="${type}" style="max-height:80px;max-width:100%;border-radius:6px;object-fit:contain;">`;
    } else {
        el.innerHTML = `<span style="color:var(--muted);font-size:.8rem;">Sin ${type}</span>`;
    }
}

async function uploadSettingImage(type){
    const input = document.getElementById(`${type}FileInput`);
    if(!input?.files?.length){ showStatus("Selecciona un archivo primero.", "error"); return; }
    const form = new FormData();
    form.append("image", input.files[0]);
    try {
        const res = await api(`/api/settings/upload-image/${type}`, {method:"POST", body:form, form:true});
        renderSettingsImagePreview(type, res.url);
        _settingsLoaded = false;
        showStatus(`${type.charAt(0).toUpperCase()+type.slice(1)} actualizado correctamente.`, "success");
    } catch(err){ showStatus("Error subiendo imagen: " + err.message, "error"); }
}

async function deleteSettingImage(type){
    if(!confirm(`¿Eliminar ${type}?`)) return;
    try {
        await api(`/api/settings/image/${type}`, {method:"DELETE"});
        renderSettingsImagePreview(type, null);
        _settingsLoaded = false;
        showStatus(`${type} eliminado.`, "success");
    } catch(err){ showStatus("Error eliminando imagen: " + err.message, "error"); }
}

document.getElementById("settingsForm")?.addEventListener("submit", async e => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    if(btn){ btn.disabled = true; btn.textContent = "Guardando…"; }
    const body = {
        institution_name:           document.getElementById("cfgInstitutionName").value.trim(),
        institution_subtitle:       document.getElementById("cfgInstitutionSubtitle").value.trim(),
        institution_city:           document.getElementById("cfgInstitutionCity").value.trim(),
        institution_ruc:            document.getElementById("cfgInstitutionRuc")?.value.trim() || "",
        institution_address:        document.getElementById("cfgInstitutionAddress")?.value.trim() || "",
        institution_phone:          document.getElementById("cfgInstitutionPhone")?.value.trim() || "",
        institution_email:          document.getElementById("cfgInstitutionEmail")?.value.trim() || "",
        institution_representative: document.getElementById("cfgInstitutionRepresentative")?.value.trim() || "",
        footer_note:                document.getElementById("cfgFooterNote").value.trim(),
        signature_name:             document.getElementById("cfgSignatureName").value.trim(),
        signature_title:            document.getElementById("cfgSignatureTitle").value.trim(),
        professional_code:          document.getElementById("cfgProfessionalCode").value.trim(),
        professional_title:         document.getElementById("cfgProfessionalTitle").value.trim(),
    };
    try {
        await api("/api/settings", {method:"PUT", body});
        _settingsLoaded = false;
        await loadSettings();   // Recarga los datos desde el servidor para confirmar el guardado
        showStatus("✅ Configuración guardada correctamente.", "success");
    } catch(err){ showStatus("Error: " + err.message, "error"); }
    finally { if(btn){ btn.disabled = false; btn.textContent = "Guardar configuración"; } }
});

document.getElementById("logoUploadBtn")?.addEventListener("click", () => uploadSettingImage("logo"));
document.getElementById("signatureUploadBtn")?.addEventListener("click", () => uploadSettingImage("signature"));
document.getElementById("sealUploadBtn")?.addEventListener("click", () => uploadSettingImage("seal"));
document.getElementById("logoDeleteBtn")?.addEventListener("click", () => deleteSettingImage("logo"));
document.getElementById("signatureDeleteBtn")?.addEventListener("click", () => deleteSettingImage("signature"));
document.getElementById("sealDeleteBtn")?.addEventListener("click", () => deleteSettingImage("seal"));

/* ─── AGENDA DE CITAS ─── */
const apptState = {
    page: 1, per_page: 15, total_pages: 1, has_next: false, has_prev: false,
    filters: { date_from: '', date_to: '', status: '' },
    editingId: null,
};

const APPT_STATUS_COLORS = {
    PROGRAMADA: '#3b82f6', CONFIRMADA: '#059669', ATENDIDA: '#6366f1',
    CANCELADA: '#ef4444', NO_ASISTIO: '#f59e0b'
};

async function loadAgendaView(){
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('todayDateLabel').textContent = new Date().toLocaleDateString('es-EC', {weekday:'long', year:'numeric', month:'long', day:'numeric'});
    document.getElementById('apptFilterFrom').value = document.getElementById('apptFilterFrom').value || today;
    await Promise.all([loadTodayAppointments(), loadUpcomingAppointments(), loadAppointmentsList()]);
}

async function loadTodayAppointments(){
    try {
        const res = await api('/api/appointments/today');
        const list = document.getElementById('todayAppointmentsList');
        if(!res.data?.length){ list.innerHTML = '<span style="color:var(--muted)">No hay citas para hoy.</span>'; return; }
        list.innerHTML = res.data.map(a => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid var(--border);">
                <div>
                    <strong>${a.appointment_time.slice(0,5)}</strong>
                    <span style="margin-left:8px;">${a.worker_name}</span>
                    <span style="margin-left:6px;font-size:.75rem;color:var(--muted);">${a.type_label}</span>
                </div>
                <span style="font-size:.72rem;padding:2px 7px;border-radius:99px;background:${APPT_STATUS_COLORS[a.status] || '#94a3b8'}20;color:${APPT_STATUS_COLORS[a.status] || '#64748b'};">${a.status_label}</span>
            </div>`).join('');
    } catch(e){ document.getElementById('todayAppointmentsList').textContent = 'Error: ' + e.message; }
}

async function loadUpcomingAppointments(){
    try {
        const res = await api('/api/appointments/upcoming');
        const list = document.getElementById('upcomingAppointmentsList');
        if(!res.data?.length){ list.innerHTML = '<span style="color:var(--muted)">No hay citas próximas.</span>'; return; }
        list.innerHTML = res.data.map(a => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid var(--border);">
                <div>
                    <strong>${a.appointment_date_display}</strong>
                    <span style="margin-left:6px;font-size:.78rem;">${a.appointment_time.slice(0,5)}</span>
                    <span style="margin-left:6px;">${a.worker_name}</span>
                </div>
                <span style="font-size:.72rem;padding:2px 7px;border-radius:99px;background:${APPT_STATUS_COLORS[a.status] || '#94a3b8'}20;color:${APPT_STATUS_COLORS[a.status] || '#64748b'};">${a.type_label}</span>
            </div>`).join('');
    } catch(e){ document.getElementById('upcomingAppointmentsList').textContent = 'Error: ' + e.message; }
}

async function loadAppointmentsList(){
    const q = new URLSearchParams({ page: apptState.page, per_page: apptState.per_page });
    if(apptState.filters.date_from) q.set('date_from', apptState.filters.date_from);
    if(apptState.filters.date_to)   q.set('date_to',   apptState.filters.date_to);
    if(apptState.filters.status)    q.set('status',    apptState.filters.status);
    try {
        const res = await api('/api/appointments?' + q.toString());
        const tbody = document.getElementById('appointmentsBody');
        if(!res.data?.length){
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--muted);">Sin resultados.</td></tr>';
        } else {
            tbody.innerHTML = res.data.map(a => `
                <tr>
                    <td>${a.appointment_date_display}</td>
                    <td>${a.appointment_time.slice(0,5)}</td>
                    <td><strong>${a.worker_name}</strong><br><small style="color:var(--muted)">${a.worker_document}</small></td>
                    <td style="font-size:.8rem;">${a.worker_company}</td>
                    <td style="font-size:.8rem;">${a.type_label}</td>
                    <td><span style="font-size:.75rem;padding:2px 7px;border-radius:99px;background:${APPT_STATUS_COLORS[a.status] || '#94a3b8'}20;color:${APPT_STATUS_COLORS[a.status] || '#64748b'};">${a.status_label}</span></td>
                    <td style="font-size:.8rem;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${a.reason || '-'}</td>
                    <td style="white-space:nowrap;">
                        <button class="btn" style="font-size:.75rem;padding:3px 8px;" onclick="editAppointment('${a.id}')">✏️</button>
                        <button class="btn danger" style="font-size:.75rem;padding:3px 8px;" onclick="deleteAppointment('${a.id}')">🗑</button>
                    </td>
                </tr>`).join('');
        }
        const m = res.meta || {};
        apptState.total_pages = m.total_pages || 1;
        apptState.has_next    = m.has_next || false;
        apptState.has_prev    = m.has_prev || false;
        document.getElementById('apptPageInfo').textContent = `Página ${m.page || 1} de ${m.total_pages || 1} (${m.total || 0} citas)`;
        document.getElementById('apptPrevBtn').disabled = !apptState.has_prev;
        document.getElementById('apptNextBtn').disabled = !apptState.has_next;
    } catch(e){ document.getElementById('appointmentsBody').innerHTML = `<tr><td colspan="8" style="color:red;">${e.message}</td></tr>`; }
}

function showApptForm(appt = null){
    apptState.editingId = appt?.id || null;
    document.getElementById('apptFormTitle').textContent = appt ? 'Editar cita' : 'Nueva cita';
    document.getElementById('apptId').value              = appt?.id || '';
    document.getElementById('apptWorkerSearch').value   = appt?.worker_name || '';
    document.getElementById('apptWorkerId').value        = appt?.worker_id || '';
    document.getElementById('apptDate').value            = appt?.appointment_date || new Date().toISOString().split('T')[0];
    document.getElementById('apptTime').value            = appt?.appointment_time?.slice(0,5) || '08:00';
    document.getElementById('apptType').value            = appt?.type || 'CONSULTA';
    document.getElementById('apptStatus').value          = appt?.status || 'PROGRAMADA';
    document.getElementById('apptReason').value          = appt?.reason || '';
    document.getElementById('apptNotes').value           = appt?.notes || '';
    document.getElementById('apptFormCard').style.display = '';
    document.getElementById('apptWorkerResults').innerHTML = '';
    document.getElementById('apptFormCard').scrollIntoView({ behavior:'smooth', block:'nearest' });
}

async function editAppointment(id){
    try {
        const res = await api(`/api/appointments/${id}`);
        showApptForm(res.data);
    } catch(e){ showStatus('Error: ' + e.message, 'error'); }
}

async function deleteAppointment(id){
    if(!confirm('¿Eliminar esta cita?')) return;
    try {
        await api(`/api/appointments/${id}`, {method:'DELETE'});
        showStatus('Cita eliminada.', 'success');
        await loadAgendaView();
    } catch(e){ showStatus('Error: ' + e.message, 'error'); }
}

// Worker search for appointment form
let apptWorkerSearchTimer = null;
document.getElementById('apptWorkerSearch')?.addEventListener('input', e => {
    clearTimeout(apptWorkerSearchTimer);
    const q = e.target.value.trim();
    if(q.length < 2){ document.getElementById('apptWorkerResults').innerHTML = ''; return; }
    apptWorkerSearchTimer = setTimeout(async () => {
        try {
            const res = await api(`/api/workers?q=${encodeURIComponent(q)}&per_page=6`);
            const results = document.getElementById('apptWorkerResults');
            if(!res.data?.length){ results.innerHTML = '<div style="padding:6px;color:var(--muted);font-size:.8rem;">Sin resultados</div>'; return; }
            results.innerHTML = `<div style="position:absolute;z-index:99;background:var(--card);border:1px solid var(--border);border-radius:8px;width:100%;box-shadow:0 4px 12px rgba(0,0,0,.1);max-height:200px;overflow-y:auto;">${
                res.data.map(w => `<div style="padding:8px 12px;cursor:pointer;font-size:.85rem;border-bottom:1px solid var(--border);" 
                    onmousedown="selectApptWorker('${w.id}','${(w.full_name||'').replace(/'/g,"\\'")}')">
                    <strong>${w.full_name}</strong> <span style="color:var(--muted)">${w.document_number} · ${w.company_name || ''}</span></div>`).join('')
            }</div>`;
        } catch(e){}
    }, 300);
});

function selectApptWorker(id, name){
    document.getElementById('apptWorkerId').value = id;
    document.getElementById('apptWorkerSearch').value = name;
    document.getElementById('apptWorkerResults').innerHTML = '';
}

document.getElementById('apptForm')?.addEventListener('submit', async e => {
    e.preventDefault();
    const workerId = document.getElementById('apptWorkerId').value;
    if(!workerId){ showStatus('Selecciona un trabajador de la lista.', 'error'); return; }
    const body = {
        worker_id:        workerId,
        appointment_date: document.getElementById('apptDate').value,
        appointment_time: document.getElementById('apptTime').value,
        type:             document.getElementById('apptType').value,
        status:           document.getElementById('apptStatus').value,
        reason:           document.getElementById('apptReason').value.trim() || null,
        notes:            document.getElementById('apptNotes').value.trim() || null,
    };
    try {
        if(apptState.editingId){
            await api(`/api/appointments/${apptState.editingId}`, {method:'PUT', body});
            showStatus('✅ Cita actualizada.', 'success');
        } else {
            await api('/api/appointments', {method:'POST', body});
            showStatus('✅ Cita creada.', 'success');
        }
        document.getElementById('apptFormCard').style.display = 'none';
        apptState.editingId = null;
        await loadAgendaView();
    } catch(err){ showStatus('Error: ' + err.message, 'error'); }
});

document.getElementById('newApptBtn')?.addEventListener('click', () => showApptForm());
document.getElementById('apptFormCancel')?.addEventListener('click', () => {
    document.getElementById('apptFormCard').style.display = 'none';
    apptState.editingId = null;
});
document.getElementById('apptFilterBtn')?.addEventListener('click', async () => {
    apptState.page = 1;
    apptState.filters.date_from = document.getElementById('apptFilterFrom').value;
    apptState.filters.date_to   = document.getElementById('apptFilterTo').value;
    apptState.filters.status    = document.getElementById('apptFilterStatus').value;
    await loadAppointmentsList();
});
document.getElementById('apptPrevBtn')?.addEventListener('click', async () => {
    if(!apptState.has_prev) return;
    apptState.page--; await loadAppointmentsList();
});
document.getElementById('apptNextBtn')?.addEventListener('click', async () => {
    if(!apptState.has_next) return;
    apptState.page++; await loadAppointmentsList();
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
    state.workerCompanyId = refs.workerCompanyFilter?.value || "";
    state.pagination.workers.page = 1;
    await refreshData();
});

refs.workerSearchInput.addEventListener("keydown", async (e) => {
    if(e.key !== "Enter") return;
    e.preventDefault();
    state.workerQuery = refs.workerSearchInput.value.trim();
    state.workerCompanyId = refs.workerCompanyFilter?.value || "";
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

// HC PDF button
if(refs.workerHistoryPdfBtn){
    refs.workerHistoryPdfBtn.addEventListener("click", async () => {
        const wid = state.selectedWorkerId;
        if(!wid){ status("Selecciona un trabajador primero.", "warn"); return; }
        const wname = state.selectedWorkerName || wid.substring(0,8);
        refs.workerHistoryPdfBtn.disabled = true;
        refs.workerHistoryPdfBtn.textContent = "⏳";
        try{
            await downloadWithToken(`/api/workers/${wid}/history-pdf`, `HC-${wname}.pdf`);
            status("Historia clínica PDF descargada.", "ok");
        } catch(err){ status(err.message || "Error generando PDF.", "error"); }
        finally{ refs.workerHistoryPdfBtn.disabled = false; refs.workerHistoryPdfBtn.textContent = "📄 HC PDF"; }
    });
}

// Carnet button
if(refs.workerCardBtn){
    refs.workerCardBtn.addEventListener("click", async () => {
        const wid = state.selectedWorkerId;
        if(!wid){ status("Selecciona un trabajador primero.", "warn"); return; }
        refs.workerCardBtn.disabled = true;
        refs.workerCardBtn.textContent = "⏳";
        try{
            await downloadWithToken(`/api/workers/${wid}/card`, `carnet-${wid.substring(0,8)}.pdf`);
            status("Carnet PDF descargado.", "ok");
        } catch(err){ status(err.message || "Error generando carnet.", "error"); }
        finally{ refs.workerCardBtn.disabled = false; refs.workerCardBtn.textContent = "🪪 Carnet"; }
    });
}

refs.certificatesBody.addEventListener("click", async (e)=>{
    const b = e.target.closest("button[data-act]"); if(!b) return;
    const id = b.getAttribute("data-id"); const act = b.getAttribute("data-act");
    if(!id) return;
    const prevDisabled = b.disabled;
    b.disabled = true;
    try{
        if(act==="gen"){
            if(!canIssueCertificates()){
                status("Sin permiso para generar PDF. Requiere rol ADMIN o MEDICO_OCUPACIONAL.", "error");
                return;
            }
            status("Generando PDF del certificado...");
            await api(`/api/certificates/${id}/generate-pdf`,{method:"POST"});
            status("PDF generado.", "ok");
            await refreshData();
            return;
        }
        if(act==="down"){
            status("Descargando PDF...");
            await downloadWithToken(`/api/certificates/${id}/download-pdf`, `certificado-${id}.pdf`);
            status("PDF descargado.", "ok");
        }
    } catch(err){
        if(err?.status === 403 && act === "gen"){
            status("Sin permiso para generar PDF. Requiere rol ADMIN o MEDICO_OCUPACIONAL.", "error");
            return;
        }
        status(err.message || "Operacion no completada.", "error");
    } finally {
        b.disabled = prevDisabled;
    }
});

refs.refreshBtn.addEventListener("click", refreshData);
refs.logoutBtn.addEventListener("click", logout);

/* ─── PORTAL POR EMPRESA ─── */
const empresaPortalState = {
    companies: [],
    searchTerm: '',
    dateFrom: '',
    dateTo: '',
    selectedCompanyId: null,
};

const APT_COLORS = {
    APTO:               { bg:'#d9f7e7', color:'#166534' },
    APTO_OBSERVACION:   { bg:'#fff4d8', color:'#8a5a00' },
    APTO_LIMITACIONES:  { bg:'#ffe9d8', color:'#9a3412' },
    NO_APTO:            { bg:'#ffe0e7', color:'#9f1239' },
};
const APT_LABELS = {
    APTO:'Apto', APTO_OBSERVACION:'Apto c/ observación',
    APTO_LIMITACIONES:'Apto c/ limitaciones', NO_APTO:'No apto'
};

async function loadEmpresaList(){
    try {
        const q = new URLSearchParams({ limit: 100 });
        if(empresaPortalState.dateFrom) q.set('date_from', empresaPortalState.dateFrom);
        if(empresaPortalState.dateTo)   q.set('date_to',   empresaPortalState.dateTo);
        const res = await api('/api/reports/aptitude-by-company?' + q.toString());
        empresaPortalState.companies = res.data || [];
        renderEmpresaGrid();
        document.getElementById('empresaListPanel').style.display = '';
        document.getElementById('empresaDetailPanel').style.display = 'none';
    } catch(e){ showStatus('Error cargando empresas: ' + e.message, 'error'); }
}

function renderEmpresaGrid(){
    const search = empresaPortalState.searchTerm.toLowerCase();
    const grid = document.getElementById('empresaGrid');
    const filtered = empresaPortalState.companies.filter(c =>
        !search || c.company_name.toLowerCase().includes(search)
    );
    if(!filtered.length){
        grid.innerHTML = '<div style="color:var(--muted);text-align:center;padding:40px;grid-column:1/-1;">Sin empresas registradas.</div>';
        return;
    }
    grid.innerHTML = filtered.map(c => {
        const apt = c.totals_by_aptitude || {};
        const aptoN = apt['APTO'] || 0;
        const noAptoN = apt['NO_APTO'] || 0;
        const obsN = apt['APTO_OBSERVACION'] || 0;
        const limN = apt['APTO_LIMITACIONES'] || 0;
        const total = c.total_evaluations || 0;
        const pct = (v) => total > 0 ? Math.round(v / total * 100) : 0;
        return `<article class="card" style="cursor:pointer;transition:box-shadow .15s;" onmouseenter="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'" onmouseleave="this.style.boxShadow=''" onclick="openEmpresaDetail(${c.company_id})">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                <div>
                    <div style="font-weight:700;font-size:.95rem;color:var(--text);">${c.company_name}</div>
                    <div style="font-size:.75rem;color:var(--muted);">ID: ${c.company_id}</div>
                </div>
                <span style="font-size:.8rem;background:#e0e7ff;color:#3730a3;padding:3px 9px;border-radius:99px;">${total} eval.</span>
            </div>
            <div style="display:flex;gap:4px;margin-bottom:10px;border-radius:6px;overflow:hidden;height:8px;">
                ${aptoN ? `<div style="flex:${aptoN};background:#22c55e;" title="Apto: ${aptoN}"></div>` : ''}
                ${obsN  ? `<div style="flex:${obsN};background:#f59e0b;" title="Obs: ${obsN}"></div>` : ''}
                ${limN  ? `<div style="flex:${limN};background:#f97316;" title="Lim: ${limN}"></div>` : ''}
                ${noAptoN ? `<div style="flex:${noAptoN};background:#ef4444;" title="No apto: ${noAptoN}"></div>` : ''}
                ${!total ? '<div style="flex:1;background:var(--border);"></div>' : ''}
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                ${aptoN ? `<span style="font-size:.72rem;padding:2px 7px;border-radius:99px;background:#d9f7e7;color:#166534;">✅ ${aptoN} (${pct(aptoN)}%)</span>` : ''}
                ${obsN  ? `<span style="font-size:.72rem;padding:2px 7px;border-radius:99px;background:#fff4d8;color:#8a5a00;">⚠️ ${obsN}</span>` : ''}
                ${limN  ? `<span style="font-size:.72rem;padding:2px 7px;border-radius:99px;background:#ffe9d8;color:#9a3412;">🔸 ${limN}</span>` : ''}
                ${noAptoN ? `<span style="font-size:.72rem;padding:2px 7px;border-radius:99px;background:#ffe0e7;color:#9f1239;">❌ ${noAptoN}</span>` : ''}
            </div>
            <div style="margin-top:10px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:.75rem;color:var(--muted);">Ver detalle →</span>
                <div style="display:flex;gap:4px;" onclick="event.stopPropagation()">
                    <button class="btn small" onclick="openEditCompany(${c.company_id})" style="font-size:.75rem;padding:4px 8px;">✏️</button>
                    <button class="btn small" onclick="deleteCompany(${c.company_id})" style="font-size:.75rem;padding:4px 8px;background:#fff0f3;border-color:#fca5a5;color:#b91c1c;">🗑️</button>
                </div>
            </div>
        </article>`;
    }).join('');
}

async function openEmpresaDetail(companyId){
    if(!companyId || companyId === 0) return;
    empresaPortalState.selectedCompanyId = companyId;
    document.getElementById('empresaListPanel').style.display   = 'none';
    document.getElementById('empresaDetailPanel').style.display = '';
    document.getElementById('empresaEvalsBody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted);">Cargando...</td></tr>';
    document.getElementById('empresaDetailStats').innerHTML = '<div style="color:var(--muted)">Cargando...</div>';
    try {
        const q = new URLSearchParams();
        const from = document.getElementById('empresaDetailFrom').value;
        const to   = document.getElementById('empresaDetailTo').value;
        if(from) q.set('date_from', from);
        if(to)   q.set('date_to', to);
        const res = await api(`/api/reports/company/${companyId}?` + q.toString());
        const d = res.data;
        // Header
        document.getElementById('empresaDetailName').textContent = d.company.name;
        document.getElementById('empresaDetailMeta').textContent =
            [d.company.ruc && `RUC: ${d.company.ruc}`, d.company.work_center, d.company.address].filter(Boolean).join(' · ');
        // Stats
        const s = d.stats || {};
        document.getElementById('empresaDetailStats').innerHTML = [
            { label:'Trabajadores',  value: s.workers      || 0, icon:'👷' },
            { label:'Evaluaciones',  value: s.evaluations  || 0, icon:'📋' },
            { label:'Certificados',  value: s.certificates || 0, icon:'📄' },
            { label:'Cert. por vencer', value: s.expiring_certs || 0, icon:'⚠️', warn: s.expiring_certs > 0 },
            { label:'Accidentes',    value: s.accidents    || 0, icon:'🚨' },
        ].map(st => `<div class="stat${st.warn ? '" style="border-color:#ef4444' : ''}">
            <h4>${st.icon} ${st.label}</h4><p>${st.value}</p></div>`).join('');
        // Aptitude distribution
        const apt = d.aptitude_dist || {};
        document.getElementById('empresaAptitudeDist').innerHTML = Object.entries(APT_LABELS).map(([key, label]) => {
            const n = apt[key] || 0;
            const total = Object.values(apt).reduce((a,b) => a + b, 0);
            const pct = total > 0 ? Math.round(n / total * 100) : 0;
            const c = APT_COLORS[key] || {bg:'#e2e8f0', color:'#64748b'};
            return `<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span style="font-size:.78rem;padding:2px 9px;border-radius:99px;background:${c.bg};color:${c.color};min-width:140px;text-align:center;">${label}</span>
                <div style="flex:1;background:var(--border);border-radius:4px;height:10px;">
                    <div style="width:${pct}%;background:${c.color};height:10px;border-radius:4px;transition:width .4s;"></div>
                </div>
                <span style="font-size:.78rem;color:var(--muted);min-width:40px;text-align:right;">${n} (${pct}%)</span>
            </div>`;
        }).join('') || '<span style="color:var(--muted);font-size:.85rem;">Sin datos</span>';
        // Monthly trend (bars)
        const trend = d.monthly_trend || [];
        const maxT = Math.max(...trend.map(t => t.total), 1);
        document.getElementById('empresaMonthlyBars').innerHTML = trend.length
            ? trend.map(t => `<div class="bar" style="--pct:${Math.round(t.total/maxT*100)}%">
                <span class="barLabel">${t.total}</span>
                <span class="barMonth">${t.month}</span></div>`).join('')
            : '<span style="color:var(--muted);font-size:.8rem;">Sin actividad en los últimos 6 meses.</span>';
        // Recent evaluations table
        const evals = d.recent_evals || [];
        document.getElementById('empresaEvalsBody').innerHTML = evals.length
            ? evals.map(e => {
                const ac = APT_COLORS[e.medical_aptitude] || {bg:'#e2e8f0', color:'#64748b'};
                return `<tr>
                    <td><strong>${e.worker_name}</strong></td>
                    <td style="font-size:.8rem;">${e.worker_document}</td>
                    <td style="font-size:.8rem;">${e.attention_date || '-'}</td>
                    <td style="font-size:.78rem;">${e.evaluation_type || '-'}</td>
                    <td><span style="font-size:.72rem;padding:2px 8px;border-radius:99px;background:${ac.bg};color:${ac.color};">${APT_LABELS[e.medical_aptitude] || e.medical_aptitude || '-'}</span></td>
                </tr>`;
            }).join('')
            : '<tr><td colspan="5" style="text-align:center;color:var(--muted);">Sin evaluaciones en el período.</td></tr>';
    } catch(e){ showStatus('Error cargando detalle: ' + e.message, 'error'); }
}

document.getElementById('empresaBackBtn')?.addEventListener('click', () => {
    document.getElementById('empresaListPanel').style.display = '';
    document.getElementById('empresaDetailPanel').style.display = 'none';
    empresaPortalState.selectedCompanyId = null;
});
document.getElementById('empresaFilterBtn')?.addEventListener('click', () => {
    empresaPortalState.searchTerm = document.getElementById('empresaSearch').value.trim();
    empresaPortalState.dateFrom   = document.getElementById('empresaFilterFrom').value;
    empresaPortalState.dateTo     = document.getElementById('empresaFilterTo').value;
    loadEmpresaList();
});
document.getElementById('empresaSearch')?.addEventListener('input', e => {
    empresaPortalState.searchTerm = e.target.value;
    renderEmpresaGrid();
});
document.getElementById('empresaDetailFilterBtn')?.addEventListener('click', () => {
    if(empresaPortalState.selectedCompanyId) openEmpresaDetail(empresaPortalState.selectedCompanyId);
});
document.getElementById('empresaDetailExcelBtn')?.addEventListener('click', async () => {
    const id = empresaPortalState.selectedCompanyId;
    if(!id) return;
    const token = localStorage.getItem('shcso_token');
    const from = document.getElementById('empresaDetailFrom').value;
    const to   = document.getElementById('empresaDetailTo').value;
    let url = `/api/reports/export-excel?type=evaluations&company_id=${id}`;
    if(from) url += `&date_from=${from}`;
    if(to)   url += `&date_to=${to}`;
    try {
        const response = await fetch(url, { headers:{ Authorization:`Bearer ${token}` }});
        if(!response.ok) throw new Error('Error exportando');
        const blob = await response.blob();
        const cd = response.headers.get('Content-Disposition') || '';
        const match = cd.match(/filename="?([^";\n]+)"?/);
        const filename = match?.[1] || 'reporte-empresa.xlsx';
        const blobUrl = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = blobUrl; a.download = filename; a.click();
        URL.revokeObjectURL(blobUrl);
    } catch(e){ showStatus('Error: ' + e.message, 'error'); }
});
refs.miPerfilBtn.addEventListener("click", () => {
    fillProfessionalFields();
    refs.miPerfilModal.classList.remove("hidden");
    refs.perfilPassword.value = "";
});
refs.miPerfilModalClose.addEventListener("click", () => refs.miPerfilModal.classList.add("hidden"));
refs.miPerfilModal.addEventListener("click", (e) => {
    if(e.target === refs.miPerfilModal) refs.miPerfilModal.classList.add("hidden");
});
refs.miPerfilForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const payload = {};
    const name = refs.perfilFullName.value.trim();
    const code = refs.perfilProfCode.value.trim();
    const pw = refs.perfilPassword.value;
    if(name.length >= 3) payload.full_name = name;
    payload.professional_code = code;
    if(pw.length >= 8) payload.password = pw;
    if(pw && pw.length < 8){ status("La contrasena debe tener minimo 8 caracteres.", "error"); return; }
    try{
        const res = await api("/api/auth/me", { method:"PUT", body:payload });
        // Use /api/auth/me PUT route
        state.user = { ...state.user, full_name: res.data.full_name, professional_code: res.data.professional_code };
        fillProfessionalFields();
        refs.miPerfilModal.classList.add("hidden");
        status(`Perfil actualizado: ${state.user.full_name}`, "ok");
    } catch(err){ status(err.message || "No se pudo actualizar el perfil.", "error"); }
});

/* ─── COMPANY CRUD ─── */
function openNewCompanyModal() {
    refs.companyFormId.value = '';
    refs.companyForm.reset();
    document.getElementById('companyModalTitle').textContent = '🏢 Nueva Empresa';
    document.getElementById('companyFormSubmitBtn').textContent = '💾 Guardar';
    refs.companyModal.classList.remove('hidden');
}

function openEditCompany(id) {
    const co = state.companies.find(c => String(c.id) === String(id));
    if (!co) return;
    refs.companyFormId.value = co.id;
    document.getElementById('companyFormName').value = co.business_name || '';
    document.getElementById('companyFormRuc').value = co.ruc || '';
    document.getElementById('companyFormWorkCenter').value = co.work_center || '';
    document.getElementById('companyFormAddress').value = co.address || '';
    document.getElementById('companyFormCiiu').value = co.ciiu || '';
    document.getElementById('companyModalTitle').textContent = '✏️ Editar Empresa';
    document.getElementById('companyFormSubmitBtn').textContent = '💾 Actualizar';
    refs.companyModal.classList.remove('hidden');
}

async function deleteCompany(id) {
    const co = state.companies.find(c => String(c.id) === String(id));
    const name = co ? co.business_name : 'esta empresa';
    if (!confirm(`¿Eliminar la empresa "${name}"?\n\nEsta acción no se puede deshacer. Si tiene trabajadores asignados, no se podrá eliminar.`)) return;
    try {
        await api('/api/catalog/companies/' + id, { method: 'DELETE' });
        showStatus('Empresa eliminada correctamente.', 'success');
        await refreshData();
    } catch(err) {
        showStatus(err.message || 'No se pudo eliminar la empresa.', 'error');
    }
}

// Company Modal event listeners
refs.newCompanyBtn.addEventListener('click', openNewCompanyModal);
refs.companyModal.addEventListener('click', (e) => { if (e.target === refs.companyModal) refs.companyModal.classList.add('hidden'); });
document.getElementById('companyModalClose').addEventListener('click', () => refs.companyModal.classList.add('hidden'));
document.getElementById('companyModalCancel').addEventListener('click', () => refs.companyModal.classList.add('hidden'));

refs.companyForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(refs.companyForm);
    const id = refs.companyFormId.value;
    const body = {
        business_name: fd.get('business_name'),
        ruc: fd.get('ruc') || null,
        work_center: fd.get('work_center') || null,
        address: fd.get('address') || null,
        ciiu: fd.get('ciiu') || null,
    };
    try {
        if (id) {
            await api('/api/catalog/companies/' + id, { method: 'PUT', body });
            showStatus('Empresa actualizada correctamente.', 'success');
        } else {
            await api('/api/catalog/companies', { method: 'POST', body });
            showStatus('Empresa creada correctamente.', 'success');
        }
        refs.companyModal.classList.add('hidden');
        await refreshData();
    } catch(err) {
        showStatus(err.message || 'Error al guardar empresa.', 'error');
    }
});

/* ─── SOAP HELP MODAL ─── */
refs.soapHelpBtn.addEventListener("click", () => refs.soapHelpModal.classList.remove("hidden"));
refs.soapHelpModalClose.addEventListener("click", () => refs.soapHelpModal.classList.add("hidden"));
refs.soapHelpModalClose2.addEventListener("click", () => refs.soapHelpModal.classList.add("hidden"));
refs.soapHelpModal.addEventListener("click", (e) => {
    if(e.target === refs.soapHelpModal) refs.soapHelpModal.classList.add("hidden");
});

(async function init(){
    setView(resolveViewFromPath(), false);
    resetConsultationState();
    const t = localStorage.getItem("shcso_token");
    if(!t){ await prepareLoginSection(); return; }
    state.token=t;
    await refreshData();
    setView(resolveViewFromPath(), false);
})();
</script>
</body>
</html>
