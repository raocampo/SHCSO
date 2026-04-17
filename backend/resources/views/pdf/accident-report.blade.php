<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
    @font-face { font-family: "DejaVu Sans"; src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype"); }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "DejaVu Sans", sans-serif; font-size: 10px; color: #1a1a2e; background: #fff; }
    .paper { max-width: 780px; margin: 0 auto; padding: 22px 28px; }

    /* HEADER */
    .header { border: 2px solid #1e3a5f; margin-bottom: 0; }
    .header-top { background: #1e3a5f; color: #fff; padding: 8px 12px; display: table; width: 100%; }
    .header-top .logo-cell { display: table-cell; width: 60px; vertical-align: middle; }
    .header-top .title-cell { display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px; }
    .header-top .title-cell .doc-title { font-size: 13px; font-weight: bold; letter-spacing: .5px; }
    .header-top .title-cell .doc-sub { font-size: 9px; color: #b0c4de; margin-top: 2px; }
    .header-top .num-cell { display: table-cell; width: 130px; text-align: right; vertical-align: middle; }
    .header-top .num-cell .folio { font-size: 11px; font-weight: bold; }
    .header-top .num-cell .folio-sub { font-size: 8.5px; color: #b0c4de; }
    .header-meta { display: table; width: 100%; }
    .header-meta .cell { display: table-cell; padding: 5px 10px; border-right: 1px solid #c0d0e0; border-bottom: 1px solid #c0d0e0; font-size: 9px; }
    .header-meta .cell:last-child { border-right: none; }
    .header-meta .cell .lbl { color: #666; display: block; }
    .header-meta .cell .val { font-weight: bold; font-size: 10px; display: block; }

    /* SECTIONS */
    .section-box { border: 1px solid #c0d0e0; margin-bottom: 8px; }
    .section-title { background: #2d5fa0; color: #fff; padding: 4px 10px; font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; }
    .section-body { padding: 8px 10px; }
    .grid2 { display: table; width: 100%; }
    .grid2-row { display: table-row; }
    .grid2-cell { display: table-cell; width: 50%; padding: 3px 6px; vertical-align: top; }
    .field-lbl { font-size: 8.5px; color: #666; display: block; }
    .field-val { font-size: 10px; font-weight: bold; display: block; min-height: 14px; border-bottom: 1px dotted #ccc; padding-bottom: 2px; margin-bottom: 4px; }
    .field-full { margin-bottom: 6px; }
    .field-full .field-lbl { font-size: 8.5px; color: #666; }
    .field-full .field-val { font-size: 10px; font-weight: bold; min-height: 14px; border-bottom: 1px dotted #ccc; padding-bottom: 2px; }

    /* SEVERITY BADGE */
    .badge { display: inline-block; border-radius: 4px; padding: 2px 8px; font-size: 9px; font-weight: bold; color: #fff; }
    .badge-minor    { background: #22c55e; }
    .badge-moderate { background: #f59e0b; }
    .badge-serious  { background: #ef4444; }
    .badge-fatal    { background: #7f1d1d; }

    /* SIGNATURES */
    .sig-section { display: table; width: 100%; margin-top: 14px; }
    .sig-col { display: table-cell; width: 33%; text-align: center; padding: 0 6px; }
    .sig-line { border-top: 1px solid #334155; margin: 24px 6px 3px; }
    .sig-name { font-weight: bold; font-size: 9px; }
    .sig-sub  { font-size: 8px; color: #64748b; }

    /* FOOTER */
    .footer { margin-top: 10px; border-top: 1px solid #ccc; padding-top: 5px; font-size: 8px; color: #888; text-align: center; }
    .text-block { font-size: 10px; min-height: 18px; border: 1px dotted #bbb; padding: 3px 5px; border-radius: 3px; background: #fafafa; margin-top: 2px; white-space: pre-wrap; }
</style>
</head>
<body>
<div class="paper">

    <!-- HEADER IESS-style -->
    <div class="header">
        <div class="header-top">
            <div class="logo-cell">
                @if(!empty($config['logo_path']) && file_exists(public_path($config['logo_path'])))
                    <img src="{{ public_path($config['logo_path']) }}" width="48" height="48">
                @else
                    <div style="width:48px;height:48px;border:2px solid #b0c4de;border-radius:50%;display:table-cell;vertical-align:middle;text-align:center;font-size:8px;color:#b0c4de;">SHCSO</div>
                @endif
            </div>
            <div class="title-cell">
                <div class="doc-title">REPORTE DE ACCIDENTE / INCIDENTE LABORAL</div>
                <div class="doc-sub">{{ $config['name'] ?? 'Sistema de Historias Clínicas en Salud Ocupacional' }}</div>
                <div class="doc-sub" style="margin-top:1px;">Formulario AT-01 — Aviso de Accidente de Trabajo</div>
            </div>
            <div class="num-cell">
                <div class="folio">N° {{ str_pad($accident->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="folio-sub">Fecha emisión: {{ $fecha }}</div>
                <div class="folio-sub" style="margin-top:3px;">Estado: <strong>{{ $statusLabel }}</strong></div>
            </div>
        </div>
        <div class="header-meta">
            <div class="cell"><span class="lbl">Empresa / Empleador</span><span class="val">{{ $worker['company'] }}</span></div>
            <div class="cell"><span class="lbl">Área / Lugar del accidente</span><span class="val">{{ $accident->accident_location ?: '—' }}</span></div>
            <div class="cell"><span class="lbl">Fecha del accidente</span><span class="val">{{ \Carbon\Carbon::parse($accident->accident_date)->format('d/m/Y') }}{{ $accident->accident_time ? ' ' . substr($accident->accident_time,0,5) : '' }}</span></div>
            <div class="cell"><span class="lbl">Severidad</span><span class="val"><span class="badge badge-{{ strtolower($accident->severity) }}">{{ $severityLabel }}</span></span></div>
        </div>
    </div>

    <!-- SECCIÓN 1: DATOS DEL TRABAJADOR -->
    <div class="section-box">
        <div class="section-title">1. Datos del Trabajador Accidentado</div>
        <div class="section-body">
            <div class="grid2">
                <div class="grid2-row">
                    <div class="grid2-cell"><span class="field-lbl">Apellidos y Nombres</span><span class="field-val">{{ strtoupper($worker['full_name']) }}</span></div>
                    <div class="grid2-cell"><span class="field-lbl">Cédula / RUC</span><span class="field-val">{{ $worker['document_number'] }}</span></div>
                </div>
                <div class="grid2-row">
                    <div class="grid2-cell"><span class="field-lbl">Cargo / Puesto de trabajo</span><span class="field-val">{{ $worker['job_position'] }}</span></div>
                    <div class="grid2-cell"><span class="field-lbl">Edad</span><span class="field-val">{{ $worker['age'] }}</span></div>
                </div>
                <div class="grid2-row">
                    <div class="grid2-cell"><span class="field-lbl">Sexo</span><span class="field-val">{{ $worker['sex'] }}</span></div>
                    <div class="grid2-cell"><span class="field-lbl">Días perdidos</span><span class="field-val">{{ $accident->lost_days ?: '0' }} día(s)</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: DATOS DEL ACCIDENTE -->
    <div class="section-box">
        <div class="section-title">2. Datos del Accidente / Incidente</div>
        <div class="section-body">
            <div class="grid2">
                <div class="grid2-row">
                    <div class="grid2-cell"><span class="field-lbl">Tipo de evento</span><span class="field-val">{{ $typeLabel }}</span></div>
                    <div class="grid2-cell"><span class="field-lbl">Parte del cuerpo afectada</span><span class="field-val">{{ $accident->body_part_affected ?: '—' }}</span></div>
                </div>
                <div class="grid2-row">
                    <div class="grid2-cell"><span class="field-lbl">Tipo de lesión</span><span class="field-val">{{ $accident->injury_type ?: '—' }}</span></div>
                    <div class="grid2-cell"><span class="field-lbl">Reportado al IESS</span><span class="field-val">{{ $accident->iess_reported ? 'SÍ' : 'NO' }}{{ $accident->at01_number ? ' — N° ' . $accident->at01_number : '' }}</span></div>
                </div>
            </div>
            <div class="field-full" style="margin-top:6px;">
                <span class="field-lbl">Descripción del accidente</span>
                <div class="text-block">{{ $accident->description }}</div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 3: CAUSAS -->
    <div class="section-box">
        <div class="section-title">3. Análisis de Causas</div>
        <div class="section-body">
            <div class="field-full">
                <span class="field-lbl">Causa inmediata</span>
                <div class="text-block">{{ $accident->immediate_cause ?: '—' }}</div>
            </div>
            <div class="field-full" style="margin-top:4px;">
                <span class="field-lbl">Causa raíz / Causa básica</span>
                <div class="text-block">{{ $accident->root_cause ?: '—' }}</div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 4: ACCIONES -->
    <div class="section-box">
        <div class="section-title">4. Acciones Correctivas y Preventivas</div>
        <div class="section-body">
            <div class="field-full">
                <span class="field-lbl">Acciones correctivas inmediatas</span>
                <div class="text-block">{{ $accident->corrective_actions ?: '—' }}</div>
            </div>
            <div class="field-full" style="margin-top:4px;">
                <span class="field-lbl">Acciones preventivas</span>
                <div class="text-block">{{ $accident->preventive_actions ?: '—' }}</div>
            </div>
        </div>
    </div>

    <!-- FIRMAS -->
    <div class="sig-section">
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-name">TRABAJADOR ACCIDENTADO</div>
            <div class="sig-sub">{{ strtoupper($worker['full_name']) }}</div>
            <div class="sig-sub">C.I.: {{ $worker['document_number'] }}</div>
        </div>
        <div class="sig-col">
            @if(!empty($config['signature_path']) && file_exists($config['signature_path']))
                <div style="text-align:center;margin-bottom:4px;"><img src="{{ $config['signature_path'] }}" style="max-height:44px;max-width:120px;" alt="Firma"></div>
            @endif
            <div class="sig-line"></div>
            <div class="sig-name">MÉDICO OCUPACIONAL</div>
            <div class="sig-sub">{{ $doctor['name'] }}</div>
            @if(!empty($doctor['code'])) <div class="sig-sub">Reg.: {{ $doctor['code'] }}</div> @endif
            @if(!empty($config['signature_name'])) <div class="sig-sub">{{ $config['signature_name'] }}</div> @endif
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-name">RESPONSABLE DE SEGURIDAD</div>
            <div class="sig-sub">Nombre: ___________________________</div>
            <div class="sig-sub">Firma: ____________________________</div>
        </div>
    </div>

    <div class="footer">
        Documento de uso interno — {{ $config['name'] ?? 'SHCSO' }} | Generado: {{ now()->format('d/m/Y H:i') }} | Formulario AT-01 / Reporte de accidente laboral
        @if(!empty($config['footer_note'])) | {{ $config['footer_note'] }} @endif
    </div>

</div>
</body>
</html>
