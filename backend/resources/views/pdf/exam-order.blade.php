<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
    @font-face { font-family: "DejaVu Sans"; src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype"); }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }
    .paper { max-width: 780px; margin: 0 auto; padding: 28px 32px; }

    /* HEADER */
    .header { display: table; width: 100%; border-bottom: 3px solid #0f8b8d; padding-bottom: 14px; margin-bottom: 16px; }
    .header-logo { display: table-cell; width: 70px; vertical-align: middle; }
    .header-logo .logo-placeholder { width: 58px; height: 58px; border: 2px solid #0f8b8d; border-radius: 50%; display: table-cell; vertical-align: middle; text-align: center; font-size: 9px; color: #0f8b8d; font-weight: bold; }
    .header-info { display: table-cell; vertical-align: middle; padding-left: 12px; }
    .header-info .inst-name { font-size: 16px; font-weight: bold; color: #0f172a; }
    .header-info .inst-sub  { font-size: 10px; color: #64748b; margin-top: 2px; }
    .header-title { display: table-cell; text-align: right; vertical-align: middle; }
    .order-type-badge { background: #0f8b8d; color: #fff; border-radius: 6px; padding: 4px 10px; font-size: 13px; font-weight: bold; letter-spacing: .5px; display: inline-block; }
    .order-date-num { font-size: 10px; color: #64748b; margin-top: 6px; }

    /* PRIORITY */
    .priority-urgent { background: #ef4444; }
    .priority-routine { background: #64748b; }

    /* PATIENT BOX */
    .patient-box { border: 1px solid #b6d9cd; border-radius: 8px; background: #f0f9f8; padding: 12px 14px; margin-bottom: 14px; }
    .patient-box h3 { font-size: 10px; color: #0f8b8d; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; border-bottom: 1px solid #b6d9cd; padding-bottom: 4px; }
    .patient-grid { display: table; width: 100%; }
    .patient-row  { display: table-row; }
    .patient-cell { display: table-cell; padding: 2px 0; width: 50%; }
    .patient-cell .label { color: #64748b; font-size: 9.5px; }
    .patient-cell .value { font-weight: bold; font-size: 11px; }

    /* STUDIES TABLE */
    .studies-section { margin-bottom: 14px; }
    .studies-section h3 { font-size: 10px; color: #0f8b8d; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
    table.studies { width: 100%; border-collapse: collapse; }
    table.studies thead tr { background: #0f8b8d; color: #fff; }
    table.studies thead th { padding: 5px 8px; font-size: 10px; text-align: left; }
    table.studies tbody tr { border-bottom: 1px solid #e2eff0; }
    table.studies tbody tr:nth-child(even) { background: #f0f9f8; }
    table.studies tbody td { padding: 5px 8px; font-size: 11px; vertical-align: top; }
    table.studies tbody td.num { width: 28px; text-align: center; color: #64748b; font-size: 10px; }
    table.studies tbody td.study-notes { font-size: 9.5px; color: #475569; font-style: italic; }

    /* CLINICAL INDICATION */
    .indication-box { border: 1px solid #fed7aa; border-left: 4px solid #f97316; background: #fff7ed; border-radius: 6px; padding: 8px 12px; margin-bottom: 14px; }
    .indication-box .label { font-size: 9.5px; color: #9a3412; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
    .indication-box .value { font-size: 11px; color: #431407; }

    /* NOTES */
    .notes-box { border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; padding: 8px 12px; margin-bottom: 14px; }
    .notes-box .label { font-size: 9.5px; color: #475569; font-weight: bold; margin-bottom: 3px; }

    /* SIGNATURES */
    .sig-section { display: table; width: 100%; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 14px; }
    .sig-col { display: table-cell; width: 50%; text-align: center; padding: 0 10px; }
    .sig-line { border-top: 1px solid #334155; margin: 30px 10px 4px; }
    .sig-name { font-weight: bold; font-size: 11px; color: #0f172a; }
    .sig-sub  { font-size: 9px; color: #64748b; margin-top: 2px; }

    /* FOOTER */
    .footer { margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 8px; text-align: center; font-size: 9px; color: #94a3b8; }
</style>
</head>
<body>
<div class="paper">

    <!-- HEADER -->
    <div class="header">
        <div class="header-logo">
            @if(!empty($config['logo_path']) && file_exists(public_path($config['logo_path'])))
                <img src="{{ public_path($config['logo_path']) }}" width="56" height="56" style="border-radius:50%;object-fit:cover;">
            @else
                <div class="logo-placeholder">SHCSO</div>
            @endif
        </div>
        <div class="header-info">
            <div class="inst-name">{{ $config['name'] ?? 'SHCSO' }}</div>
            <div class="inst-sub">{{ $config['subtitle'] ?? 'Sistema de Historias Clínicas en Salud Ocupacional' }}</div>
            @if(!empty($config['city'])) <div class="inst-sub">{{ $config['city'] }}</div> @endif
        </div>
        <div class="header-title">
            <div class="order-type-badge {{ $order->priority === 'URGENT' ? 'priority-urgent' : ($order->priority === 'ROUTINE' ? 'priority-routine' : '') }}">
                Pedido de {{ $orderTitle }}
            </div>
            <div class="order-date-num">
                Fecha: {{ $fecha }} &nbsp;|&nbsp; N° PED-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
                Prioridad: <strong>{{ $priority }}</strong>
            </div>
        </div>
    </div>

    <!-- PATIENT DATA -->
    <div class="patient-box">
        <h3>Datos del Paciente / Trabajador</h3>
        <div class="patient-grid">
            <div class="patient-row">
                <div class="patient-cell"><span class="label">Paciente:</span><br><span class="value">{{ strtoupper($worker['full_name']) }}</span></div>
                <div class="patient-cell"><span class="label">Cédula / Doc.:</span><br><span class="value">{{ $worker['document_number'] }}</span></div>
            </div>
            <div class="patient-row">
                <div class="patient-cell"><span class="label">Edad:</span><br><span class="value">{{ $worker['age'] }}</span></div>
                <div class="patient-cell"><span class="label">Sexo:</span><br><span class="value">{{ $worker['sex'] }}</span></div>
            </div>
            <div class="patient-row">
                <div class="patient-cell"><span class="label">Empresa:</span><br><span class="value">{{ $worker['company'] }}</span></div>
                <div class="patient-cell"><span class="label">Cargo:</span><br><span class="value">{{ $worker['job_position'] }}</span></div>
            </div>
        </div>
    </div>

    <!-- CLINICAL INDICATION -->
    @if(!empty($order->clinical_indication))
    <div class="indication-box">
        <div class="label">Indicación Clínica</div>
        <div class="value">{{ $order->clinical_indication }}</div>
    </div>
    @endif

    <!-- STUDIES TABLE -->
    <div class="studies-section">
        <h3>Estudios Solicitados ({{ count($order->studies) }})</h3>
        <table class="studies">
            <thead>
                <tr>
                    <th class="num">#</th>
                    <th>Estudio / Examen</th>
                    <th>Observaciones / Indicaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->studies as $i => $study)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $study['name'] ?? '' }}</td>
                    <td class="study-notes">{{ $study['notes'] ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ADDITIONAL NOTES -->
    @if(!empty($order->additional_notes))
    <div class="notes-box">
        <div class="label">Notas Adicionales</div>
        <div>{{ $order->additional_notes }}</div>
    </div>
    @endif

    <!-- SIGNATURE AREA -->
    <div class="sig-section">
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-name">{{ $doctor['name'] }}</div>
            <div class="sig-sub">Médico Ocupacional</div>
            @if(!empty($doctor['code'])) <div class="sig-sub">Reg. Prof: {{ $doctor['code'] }}</div> @endif
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-name">{{ strtoupper($worker['full_name']) }}</div>
            <div class="sig-sub">Paciente / Trabajador</div>
            <div class="sig-sub">C.I.: {{ $worker['document_number'] }}</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        {{ $config['footer_note'] ?? 'Documento confidencial de uso médico exclusivo.' }}
        &nbsp;|&nbsp; Generado el {{ now()->format('d/m/Y H:i') }}
        &nbsp;|&nbsp; Válido con firma del profesional responsable.
    </div>

</div>
</body>
</html>
