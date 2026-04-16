
const state = {
    token:null, user:null, workers:[], evaluations:[], certificates:[], companies:[], positions:[], users:[], roles:[], dashboard:null, monthly:[], aptitude:[],
    selectedWorkerId:null, selectedWorkerHistory:null, activeView:"dashboard", workerStep:"recent", operationStep:"consult", workerQuery:"",
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
    workerForm: document.getElementById("workerForm"), workerCompany: document.getElementById("workerCompany"), workerPosition: document.getElementById("workerPosition"),
    workerDetailBox: document.getElementById("workerDetailBox"), workersManageBody: document.getElementById("workersManageBody"), workerClinicalForm: document.getElementById("workerClinicalForm"), workerFormSubmitBtn: document.getElementById("workerFormSubmitBtn"), workerFormResetBtn: document.getElementById("workerFormResetBtn"), workerCreateBtn: document.getElementById("workerCreateBtn"), workerFormModeHint: document.getElementById("workerFormModeHint"),
    workerHistoryEval: document.getElementById("workerHistoryEval"), workerHistoryCert: document.getElementById("workerHistoryCert"), workerTimeline: document.getElementById("workerTimeline"),
    evaluationWorker: document.getElementById("evaluationWorker"), evaluationWorkerSearch: document.getElementById("evaluationWorkerSearch"), diagnosisSearchInput: document.getElementById("diagnosisSearchInput"), diagnosisSearchResults: document.getElementById("diagnosisSearchResults"), selectedDiagnosesList: document.getElementById("selectedDiagnosesList"),
    rxMedication: document.getElementById("rxMedication"), rxDosage: document.getElementById("rxDosage"), rxFrequency: document.getElementById("rxFrequency"), rxDuration: document.getElementById("rxDuration"), rxIndications: document.getElementById("rxIndications"), addPrescriptionBtn: document.getElementById("addPrescriptionBtn"), prescriptionList: document.getElementById("prescriptionList"),
    certificateEvaluation: document.getElementById("certificateEvaluation"), attachmentEvaluation: document.getElementById("attachmentEvaluation"), certificateCreateBtn: document.getElementById("certificateCreateBtn"), certificateFlowHint: document.getElementById("certificateFlowHint"),
    userForm: document.getElementById("userForm"), userEditForm: document.getElementById("userEditForm"), userRoleSelect: document.getElementById("userRoleSelect"), userEditRoleSelect: document.getElementById("userEditRoleSelect"),
    loginForm: document.getElementById("loginForm"), loginHint: document.getElementById("loginHint"), firstAdminBox: document.getElementById("firstAdminBox"), firstAdminForm: document.getElementById("firstAdminForm"),
    authRecoveryActions: document.getElementById("authRecoveryActions"), showForgotPasswordBtn: document.getElementById("showForgotPasswordBtn"), showResetPasswordBtn: document.getElementById("showResetPasswordBtn"),
    forgotPasswordBox: document.getElementById("forgotPasswordBox"), forgotPasswordForm: document.getElementById("forgotPasswordForm"), cancelForgotPasswordBtn: document.getElementById("cancelForgotPasswordBtn"),
    resetPasswordBox: document.getElementById("resetPasswordBox"), resetPasswordForm: document.getElementById("resetPasswordForm"), cancelResetPasswordBtn: document.getElementById("cancelResetPasswordBtn")
};
let diagnosisSearchTimer = null;

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
    applyOperationStepVisibility();
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
    refs.workerDetailBox.innerHTML = `
        <p class="meta"><strong>Nombre:</strong> ${esc(w.first_name)} ${esc(w.last_name)}</p>
        <p class="meta"><strong>Documento:</strong> ${esc(w.document_type)} ${esc(w.document_number)}</p>
        <p class="meta"><strong>Historia:</strong> ${esc(w.history_number)}</p>
        <p class="meta"><strong>Archivo:</strong> ${esc(w.file_number)}</p>
        <p class="meta"><strong>Empresa:</strong> ${esc(w.company?.business_name || "Sin empresa")}</p>
        <p class="meta"><strong>Puesto:</strong> ${esc(w.job_position?.name || "Sin puesto")}</p>
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

(async function init(){
    setView(resolveViewFromPath(), false);
    resetConsultationState();
    const t = localStorage.getItem("shcso_token");
    if(!t){ await prepareLoginSection(); return; }
    state.token=t;
    await refreshData();
    setView(resolveViewFromPath(), false);
})();

