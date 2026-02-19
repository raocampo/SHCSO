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
        .btn.primary { background:var(--ink); border-color:var(--ink); color:#fff; }
        .btn.accent { background:var(--accent); border-color:var(--accent); color:#fff; }
        .btn.warn { background:var(--warn); border-color:var(--warn); color:#2a1b08; }
        .tabs { display:flex; gap:8px; flex-wrap:wrap; margin:0 0 12px; }
        .tab { border:1px solid var(--line); background:#fff; color:var(--ink); border-radius:999px; padding:8px 12px; cursor:pointer; font-weight:700; }
        .tab.active { background:var(--ink); border-color:var(--ink); color:#fff; }
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
        .workerSplit { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px; }
        .meta { margin:4px 0; color:var(--muted); font-size:.86rem; }
        .meta strong { color:var(--ink); }
        .historyList { display:grid; gap:8px; }
        .historyCard { border:1px solid #e4ece9; border-radius:10px; padding:10px; background:#fbfefd; }
        .chips { display:flex; gap:6px; flex-wrap:wrap; margin-top:6px; }
        .chip { display:inline-block; border:1px solid #cfe0da; border-radius:999px; padding:2px 8px; font-size:.72rem; background:#f0f7f4; color:#115f61; }
        .toolbar { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:8px; margin-bottom:10px; }
        .toolbar.compact { grid-template-columns:2fr repeat(3,minmax(0,1fr)) auto; align-items:end; }
        .toolbar .btn { padding:9px 10px; }
        .empty { color:var(--muted); font-style:italic; padding:10px 0; }
        @media (max-width:1120px) { .stats{grid-template-columns:repeat(2,minmax(0,1fr));} .grid2{grid-template-columns:1fr;} .grid3{grid-template-columns:1fr;} .workerSplit{grid-template-columns:1fr;} }
        @media (max-width:980px) { .toolbar{grid-template-columns:1fr 1fr;} .toolbar.compact{grid-template-columns:1fr;} }
        @media (max-width:720px) { .top{flex-direction:column; align-items:flex-start;} .actions{width:100%;} .actions .btn{flex:1;} .tabs{width:100%;} .tabs .tab{flex:1;} }
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

        <div id="statsGrid" class="stats view-dashboard"></div>

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

        <div class="grid3">
            <article class="card view-workers">
                <h2 class="section">Nuevo trabajador</h2>
                <form id="workerForm">
                    <div class="field"><label>Documento</label><input name="document_number" value="1723456701" required></div>
                    <div class="field"><label>Nombres</label><input name="first_name" value="Juan" required></div>
                    <div class="field"><label>Apellidos</label><input name="last_name" value="Perez" required></div>
                    <div class="field"><label>Nacimiento</label><input type="date" name="birth_date" value="1990-05-10" required></div>
                    <div class="field"><label>Sexo</label><select name="sex"><option>M</option><option>F</option><option>O</option></select></div>
                    <div class="field"><label>Empresa</label><select id="workerCompany" name="company_id"></select></div>
                    <div class="field"><label>Puesto</label><select id="workerPosition" name="job_position_id"></select></div>
                    <button class="btn accent" type="submit">Guardar trabajador</button>
                </form>
            </article>

            <article class="card view-operations">
                <h2 class="section">Nueva evaluacion</h2>
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

            <article class="card view-operations">
                <h2 class="section">Certificado y adjunto</h2>
                <form id="certificateForm">
                    <div class="field"><label>Evaluacion</label><select id="certificateEvaluation" name="evaluation_id" required></select></div>
                    <div class="field"><label>Observaciones</label><textarea name="observations">Apto para labores</textarea></div>
                    <div class="field"><label>Recomendaciones</label><textarea name="recommendations">Control anual</textarea></div>
                    <button class="btn accent" type="submit">Crear certificado</button>
                </form>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <form id="attachmentForm">
                    <div class="field"><label>Evaluacion para adjunto</label><select id="attachmentEvaluation" name="evaluation_id" required></select></div>
                    <div class="field"><label>Archivo</label><input name="file" type="file" required></div>
                    <button class="btn" type="submit">Subir adjunto</button>
                </form>
            </article>
        </div>

        <div class="grid2">
            <article class="card view-workers">
                <h2 class="section">Trabajadores recientes</h2>
                <div class="toolbar compact">
                    <div class="field"><label>Buscar</label><input id="workerSearchInput" placeholder="Documento o nombre"></div>
                    <button id="workerSearchBtn" class="btn" type="button">Buscar</button>
                </div>
                <div class="tableWrap"><table><thead><tr><th>Documento</th><th>Nombre</th><th>Empresa</th><th>Historia</th><th>Accion</th></tr></thead><tbody id="workersBody"></tbody></table></div>
            </article>
            <article class="card view-operations">
                <h2 class="section">Evaluaciones recientes</h2>
                <form id="evaluationFilterForm" class="toolbar">
                    <div class="field"><label>Tipo</label><select name="evaluation_type"><option value="">Todos</option><option>INGRESO</option><option>PERIODICO</option><option>REINTEGRO</option><option>RETIRO</option></select></div>
                    <div class="field"><label>Aptitud</label><select name="medical_aptitude"><option value="">Todas</option><option>APTO</option><option>APTO_OBSERVACION</option><option>APTO_LIMITACIONES</option><option>NO_APTO</option></select></div>
                    <div class="field"><label>Desde</label><input type="date" name="date_from"></div>
                    <div class="field"><label>Hasta</label><input type="date" name="date_to"></div>
                    <button class="btn" type="submit">Filtrar</button>
                </form>
                <div class="tableWrap"><table><thead><tr><th>Fecha</th><th>Trabajador</th><th>Tipo</th><th>Aptitud</th></tr></thead><tbody id="evaluationsBody"></tbody></table></div>
            </article>
        </div>

        <div class="workerSplit view-workers">
            <article class="card view-workers">
                <h2 class="section">Ficha y edicion de trabajador</h2>
                <div id="workerDetailBox" class="empty">Selecciona un trabajador para ver ficha completa.</div>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <form id="workerEditForm">
                    <input type="hidden" name="worker_id">
                    <div class="field"><label>Tipo documento</label><select name="document_type"><option>CEDULA</option><option>PASAPORTE</option></select></div>
                    <div class="field"><label>Documento</label><input name="document_number" required></div>
                    <div class="field"><label>Nombres</label><input name="first_name" required></div>
                    <div class="field"><label>Apellidos</label><input name="last_name" required></div>
                    <div class="field"><label>Nacimiento</label><input type="date" name="birth_date" required></div>
                    <div class="field"><label>Sexo</label><select name="sex"><option>M</option><option>F</option><option>O</option></select></div>
                    <div class="field"><label>Email</label><input name="email" type="email"></div>
                    <div class="field"><label>Telefono</label><input name="phone"></div>
                    <div class="field"><label>Tipo de sangre</label><input name="blood_type"></div>
                    <div class="field"><label>Lateralidad</label><input name="laterality"></div>
                    <div class="field"><label>Empresa</label><select id="workerEditCompany" name="company_id"></select></div>
                    <div class="field"><label>Puesto</label><select id="workerEditPosition" name="job_position_id"></select></div>
                    <button class="btn" type="submit">Actualizar trabajador</button>
                </form>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <h3 class="section" style="font-size:.9rem;">Historia clinica ampliada</h3>
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

            <article class="card view-workers">
                <h2 class="section">Historial clinico del trabajador</h2>
                <div id="workerHistoryEval" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <div id="workerHistoryCert" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
                <hr style="border:none;border-top:1px solid var(--line);margin:12px 0;">
                <h3 class="section" style="font-size:.9rem;">Linea de tiempo clinica</h3>
                <div id="workerTimeline" class="historyList"><p class="empty">Sin trabajador seleccionado.</p></div>
            </article>
        </div>

        <article class="card view-operations">
            <h2 class="section">Certificados recientes</h2>
            <form id="certificateFilterForm" class="toolbar">
                <div class="field"><label>Aptitud</label><select name="medical_aptitude"><option value="">Todas</option><option>APTO</option><option>APTO_OBSERVACION</option><option>APTO_LIMITACIONES</option><option>NO_APTO</option></select></div>
                <div class="field"><label>Desde</label><input type="date" name="date_from"></div>
                <div class="field"><label>Hasta</label><input type="date" name="date_to"></div>
                <div class="field"><label>&nbsp;</label><button class="btn" type="submit">Filtrar</button></div>
            </form>
            <div class="tableWrap"><table><thead><tr><th>Codigo</th><th>Fecha</th><th>Trabajador</th><th>Aptitud</th><th>Acciones</th></tr></thead><tbody id="certificatesBody"></tbody></table></div>
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
        </article>
    </section>
</div>

<script>
const state = {
    token:null, user:null, workers:[], evaluations:[], certificates:[], companies:[], positions:[], users:[], roles:[], dashboard:null, monthly:[], aptitude:[],
    selectedWorkerId:null, selectedWorkerHistory:null, activeView:"dashboard", workerQuery:"",
    setupStatus:{ admin_exists:true, bootstrap_required:false, users_count:0 },
    evaluationFilters:{ evaluation_type:"", medical_aptitude:"", date_from:"", date_to:"" },
    certificateFilters:{ medical_aptitude:"", date_from:"", date_to:"" }
};
const refs = {
    status: document.getElementById("statusBox"), loginSection: document.getElementById("loginSection"), appSection: document.getElementById("appSection"),
    refreshBtn: document.getElementById("refreshBtn"), logoutBtn: document.getElementById("logoutBtn"),
    tabs: document.querySelectorAll(".tab"), userTab: document.querySelector('.tab[data-view="users"]'),
    dashboardViews: document.querySelectorAll(".view-dashboard"), workerViews: document.querySelectorAll(".view-workers"), operationViews: document.querySelectorAll(".view-operations"), userViews: document.querySelectorAll(".view-users"),
    statsGrid: document.getElementById("statsGrid"), monthlyChart: document.getElementById("monthlyChart"), aptitudeBody: document.getElementById("aptitudeBody"),
    workersBody: document.getElementById("workersBody"), evaluationsBody: document.getElementById("evaluationsBody"), certificatesBody: document.getElementById("certificatesBody"), usersBody: document.getElementById("usersBody"),
    workerSearchInput: document.getElementById("workerSearchInput"), workerSearchBtn: document.getElementById("workerSearchBtn"),
    evaluationFilterForm: document.getElementById("evaluationFilterForm"), certificateFilterForm: document.getElementById("certificateFilterForm"),
    workerCompany: document.getElementById("workerCompany"), workerPosition: document.getElementById("workerPosition"), workerEditCompany: document.getElementById("workerEditCompany"), workerEditPosition: document.getElementById("workerEditPosition"),
    workerDetailBox: document.getElementById("workerDetailBox"), workerHistoryEval: document.getElementById("workerHistoryEval"), workerHistoryCert: document.getElementById("workerHistoryCert"), workerTimeline: document.getElementById("workerTimeline"), workerClinicalForm: document.getElementById("workerClinicalForm"),
    evaluationWorker: document.getElementById("evaluationWorker"), certificateEvaluation: document.getElementById("certificateEvaluation"), attachmentEvaluation: document.getElementById("attachmentEvaluation"),
    userForm: document.getElementById("userForm"), userEditForm: document.getElementById("userEditForm"), userRoleSelect: document.getElementById("userRoleSelect"), userEditRoleSelect: document.getElementById("userEditRoleSelect"),
    loginForm: document.getElementById("loginForm"), loginHint: document.getElementById("loginHint"), firstAdminBox: document.getElementById("firstAdminBox"), firstAdminForm: document.getElementById("firstAdminForm")
};

function status(msg, type="info"){ refs.status.textContent = msg; refs.status.classList.remove("ok","error"); if(type==="ok") refs.status.classList.add("ok"); if(type==="error") refs.status.classList.add("error"); }
function fmtDate(v){ if(!v) return "-"; try { return new Date(v).toLocaleDateString(); } catch { return v; } }
function makeOpt(value,label){ const o=document.createElement("option"); o.value=value; o.textContent=label; return o; }
function esc(v){ return String(v ?? "").replaceAll("&","&amp;").replaceAll("<","&lt;").replaceAll(">","&gt;").replaceAll('"',"&quot;").replaceAll("'","&#039;"); }
function buildQueryString(filters){ const p = new URLSearchParams(); Object.entries(filters).forEach(([k,v]) => { if(v!==null && v!==undefined && String(v).trim()!=="") p.set(k, String(v)); }); return p.toString(); }
function canManageUsers(){ return Array.isArray(state.user?.roles) && state.user.roles.includes("ADMIN"); }
function compactText(value){ const v = String(value ?? "").trim(); return v === "" ? null : v; }
function applyAuthBootstrapView(){
    const bootstrapRequired = !!state.setupStatus?.bootstrap_required;
    refs.loginForm.classList.toggle("hidden", bootstrapRequired);
    refs.firstAdminBox.classList.toggle("hidden", !bootstrapRequired);
    if(refs.loginHint){
        refs.loginHint.textContent = bootstrapRequired
            ? "Crea el primer usuario ADMIN para habilitar el acceso."
            : "Ingresa con tu usuario para continuar.";
    }
}

function resolveViewFromPath(){
    const p = window.location.pathname;
    if(p.startsWith("/sistema/trabajadores")) return "workers";
    if(p.startsWith("/sistema/operacion")) return "operations";
    if(p.startsWith("/sistema/usuarios")) return "users";
    return "dashboard";
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
    if (!res.ok) { const err = new Error(data.message || `HTTP ${res.status}`); err.status = res.status; throw err; }
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

    const workersQuery = buildQueryString({limit:25, q: state.workerQuery});
    const evaluationsQuery = buildQueryString({limit:20, ...state.evaluationFilters});
    const certificatesQuery = buildQueryString({limit:20, ...state.certificateFilters});
    const [dashboard, monthly, aptitude, workers, evaluations, certificates, companies, positions] = await Promise.all([
        api("/api/reports/dashboard"), api("/api/reports/monthly-activity?months=6"), api("/api/reports/aptitude-by-company?limit=8"),
        api(`/api/workers?${workersQuery}`), api(`/api/evaluations?${evaluationsQuery}`), api(`/api/certificates?${certificatesQuery}`), api("/api/catalog/companies"), api("/api/catalog/job-positions")
    ]);
    state.dashboard = dashboard.data; state.monthly = monthly.data || []; state.aptitude = aptitude.data || [];
    state.workers = workers.data || []; state.evaluations = evaluations.data || []; state.certificates = certificates.data || []; state.companies = companies.data || []; state.positions = positions.data || [];

    if(canManageUsers()){
        const [users, roles] = await Promise.all([
            api("/api/users?limit=100"),
            api("/api/users/roles"),
        ]);
        state.users = users.data || [];
        state.roles = roles.data || [];
    } else {
        state.users = [];
        state.roles = [];
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
    refs.workersBody.innerHTML = "";
    if(!state.workers.length){ refs.workersBody.innerHTML = `<tr><td colspan="5" class="empty">Sin trabajadores.</td></tr>`; return; }
    state.workers.forEach(w => {
        const row = document.createElement("tr"); const company = w.company?.business_name || w.business_name || "N/A";
        row.innerHTML = `<td>${esc(w.document_number)}</td><td>${esc(w.first_name)} ${esc(w.last_name)}</td><td>${esc(company)}</td><td><span class="pill">${esc(w.history_number)}</span></td>
        <td><button class="btn small" data-act="open-worker" data-worker-id="${w.id}" type="button">Ver historial</button></td>`;
        refs.workersBody.appendChild(row);
    });
}

function fillWorkerEditForm(worker){
    const form = document.getElementById("workerEditForm");
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

    fillWorkerEditForm(w);
    fillWorkerClinicalForm(history.clinical_history, w.id);

    refs.workerHistoryEval.innerHTML = "";
    const evals = history.evaluations || [];
    if(!evals.length){
        refs.workerHistoryEval.innerHTML = `<p class="empty">No hay evaluaciones para este trabajador.</p>`;
    } else {
        evals.forEach(e => {
            const card = document.createElement("div");
            const diagnoses = (e.diagnoses || []).map(d => `<span class="chip">${esc(d.diagnosis_code)} (${esc(d.diagnosis_type)})</span>`).join("");
            card.className = "historyCard";
            card.innerHTML = `<p class="meta"><strong>${esc(e.evaluation_type)}</strong> - ${fmtDate(e.attention_date)} <span class="pill">${esc(e.medical_aptitude)}</span></p>
            <p class="meta"><strong>Motivo:</strong> ${esc(e.consultation_reason || "-")}</p>
            <p class="meta"><strong>Profesional:</strong> ${esc(e.professional_name || "-")} (${esc(e.professional_code || "-")})</p>
            <p class="meta"><strong>Adjuntos:</strong> ${(e.attachments || []).length}</p>
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
            card.innerHTML = `<p class="meta"><strong>${esc(c.certificate_code)}</strong> - ${fmtDate(c.issue_date)} <span class="pill">${esc(c.medical_aptitude)}</span></p>
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
        row.innerHTML = `<td>${fmtDate(e.attention_date)}</td><td>${esc(w.first_name || "")} ${esc(w.last_name || "")}</td><td>${esc(e.evaluation_type)}</td><td><span class="pill">${esc(e.medical_aptitude)}</span></td>`;
        refs.evaluationsBody.appendChild(row);
    });
}

function renderCertificates(){
    refs.certificatesBody.innerHTML = "";
    if(!state.certificates.length){ refs.certificatesBody.innerHTML = `<tr><td colspan="5" class="empty">Sin certificados.</td></tr>`; return; }
    state.certificates.forEach(c => {
        const w = c.worker || {};
        const row = document.createElement("tr");
        row.innerHTML = `<td>${esc(c.certificate_code)}</td><td>${fmtDate(c.issue_date)}</td><td>${esc(w.first_name || "")} ${esc(w.last_name || "")}</td><td><span class="pill">${esc(c.medical_aptitude)}</span></td>
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
                    <button class="btn small" data-act="toggle-user" data-id="${u.id}" data-next="${u.is_active ? "0" : "1"}" type="button">${toggleLabel}</button>
                </div>
            </td>`;
        refs.usersBody.appendChild(row);
    });
}

function fillSelects(){
    refs.workerCompany.innerHTML = ""; refs.workerCompany.appendChild(makeOpt("", "Sin empresa")); state.companies.forEach(c=>refs.workerCompany.appendChild(makeOpt(c.id, c.business_name)));
    refs.workerPosition.innerHTML = ""; refs.workerPosition.appendChild(makeOpt("", "Sin puesto")); state.positions.forEach(p=>refs.workerPosition.appendChild(makeOpt(p.id, p.name)));
    refs.workerEditCompany.innerHTML = ""; refs.workerEditCompany.appendChild(makeOpt("", "Sin empresa")); state.companies.forEach(c=>refs.workerEditCompany.appendChild(makeOpt(c.id, c.business_name)));
    refs.workerEditPosition.innerHTML = ""; refs.workerEditPosition.appendChild(makeOpt("", "Sin puesto")); state.positions.forEach(p=>refs.workerEditPosition.appendChild(makeOpt(p.id, p.name)));
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

function renderAll(){ renderStats(); renderMonthly(); renderAptitude(); renderWorkers(); renderEvaluations(); renderCertificates(); renderUsers(); fillSelects(); renderWorkerHistory(); }
function showApp(){ refs.loginSection.classList.add("hidden"); refs.appSection.classList.remove("hidden"); refs.refreshBtn.classList.remove("hidden"); refs.logoutBtn.classList.remove("hidden"); }
function showLogin(){ refs.loginSection.classList.remove("hidden"); refs.appSection.classList.add("hidden"); refs.refreshBtn.classList.add("hidden"); refs.logoutBtn.classList.add("hidden"); applyAuthBootstrapView(); }
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
    const doc = String(f.get("document_number") || "").trim();
    if(doc.length < 8){ status("Documento invalido: usa al menos 8 caracteres.", "error"); return; }
    const payload = {
        document_type:"CEDULA",
        document_number:doc,
        first_name:f.get("first_name"),
        last_name:f.get("last_name"),
        birth_date:f.get("birth_date"),
        sex:f.get("sex"),
        company_id:f.get("company_id")||null,
        job_position_id:f.get("job_position_id")||null
    };
    try{
        const res = await api("/api/workers",{method:"POST", body:payload});
        status("Trabajador creado.", "ok");
        if(res?.data?.id){ await loadWorkerHistory(res.data.id); }
        await refreshData();
    } catch(err){ status(err.message || "No se pudo crear trabajador.", "error"); }
});

document.getElementById("workerEditForm").addEventListener("submit", async (e)=>{
    e.preventDefault();
    const f = new FormData(e.target);
    const workerId = f.get("worker_id");
    if(!workerId){ status("Primero selecciona un trabajador.", "error"); return; }
    const doc = String(f.get("document_number") || "").trim();
    if(doc.length < 8){ status("Documento invalido: usa al menos 8 caracteres.", "error"); return; }
    const payload = {
        document_type:f.get("document_type"),
        document_number:doc,
        first_name:f.get("first_name"),
        last_name:f.get("last_name"),
        birth_date:f.get("birth_date"),
        sex:f.get("sex"),
        email:f.get("email") || null,
        phone:f.get("phone") || null,
        blood_type:f.get("blood_type") || null,
        laterality:f.get("laterality") || null,
        company_id:f.get("company_id") || null,
        job_position_id:f.get("job_position_id") || null
    };
    try{
        await api(`/api/workers/${workerId}`,{method:"PUT", body:payload});
        await loadWorkerHistory(workerId);
        status("Trabajador actualizado.", "ok");
        await refreshData();
    } catch(err){ status(err.message || "No se pudo actualizar trabajador.", "error"); }
});

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
    try{
        await api(`/api/evaluations/${f.get("evaluation_id")}/attachments`,{method:"POST", body:data, form:true});
        status("Adjunto cargado.", "ok");
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
    }
});

refs.tabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const next = tab.getAttribute("data-view");
        if(next) setView(next, true);
    });
});

window.addEventListener("popstate", () => {
    setView(resolveViewFromPath(), false);
});

refs.workerSearchBtn.addEventListener("click", async () => {
    state.workerQuery = refs.workerSearchInput.value.trim();
    await refreshData();
});

refs.workerSearchInput.addEventListener("keydown", async (e) => {
    if(e.key !== "Enter") return;
    e.preventDefault();
    state.workerQuery = refs.workerSearchInput.value.trim();
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
    await refreshData();
});

refs.workersBody.addEventListener("click", async (e)=>{
    const b = e.target.closest("button[data-act='open-worker']");
    if(!b) return;
    try{
        await loadWorkerHistory(b.getAttribute("data-worker-id"));
        renderWorkerHistory();
        status("Historial cargado.", "ok");
    } catch(err){ status(err.message || "No se pudo cargar historial.", "error"); }
});

refs.certificatesBody.addEventListener("click", async (e)=>{
    const b = e.target.closest("button[data-act]"); if(!b) return;
    const id = b.getAttribute("data-id"); const act = b.getAttribute("data-act");
    try{
        if(act==="gen"){ await api(`/api/certificates/${id}/generate-pdf`,{method:"POST"}); status("PDF generado.", "ok"); await refreshData(); return; }
        if(act==="down"){
            const res = await fetch(`/api/certificates/${id}/download-pdf`, {headers:{Authorization:`Bearer ${state.token}`}});
            if(!res.ok) throw new Error("No se pudo descargar PDF.");
            const blob = await res.blob(); const url = URL.createObjectURL(blob); const a = document.createElement("a"); a.href=url; a.download=`certificado-${id}.pdf`; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url); status("PDF descargado.", "ok");
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
