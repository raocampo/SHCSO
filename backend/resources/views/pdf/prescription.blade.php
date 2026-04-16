<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Receta Médica</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 16px; background: #fff; }
        h1, h2, h3, p { margin: 0; }
        .paper { border: 1px solid #d1d5db; padding: 14px; }
        .header { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 12px; }
        .header td { vertical-align: top; }
        .brand { width: 58%; }
        .meta { width: 42%; text-align: right; font-size: 10px; color: #334155; }
        .logo { max-height: 50px; max-width: 170px; margin-bottom: 4px; }
        .logo-placeholder { display:inline-block; border:1px dashed #94a3b8; color:#64748b; font-size:10px; padding:4px 7px; margin-bottom:4px; }
        .inst-name { font-size: 14px; font-weight: bold; color: #0f172a; }
        .inst-sub { font-size: 10px; color: #475569; margin-top: 2px; }
        .rx-title { text-align: center; font-size: 16px; font-weight: bold; color: #0f172a; letter-spacing: .06em; margin: 12px 0 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; }
        .patient-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 10px; margin-bottom: 12px; }
        .patient-box table { width: 100%; }
        .patient-box td { font-size: 10.5px; padding: 2px 4px; }
        .patient-box .label { color: #64748b; font-weight: bold; width: 100px; }
        .med-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .med-table th { background: #0f172a; color: #fff; font-size: 10px; padding: 5px 7px; text-align: left; }
        .med-table td { font-size: 10.5px; padding: 5px 7px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .med-table tr:nth-child(even) td { background: #f8fafc; }
        .med-num { font-weight: bold; color: #0f172a; }
        .med-name { font-weight: bold; }
        .notes-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 4px; padding: 7px 10px; font-size: 10px; color: #78350f; margin-bottom: 14px; }
        .sig-area { width: 100%; margin-top: 24px; }
        .sig-area td { width: 50%; vertical-align: bottom; text-align: center; padding: 0 16px; }
        .sig-line { border-top: 1px solid #475569; padding-top: 5px; font-size: 10px; color: #475569; margin-top: 50px; }
        .sig-img { max-height: 50px; margin-bottom: 4px; }
        .footer-note { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 14px; border-top: 1px solid #e2e8f0; padding-top: 6px; }
        .rx-symbol { font-size: 22px; font-weight: bold; color: #0f172a; float: left; margin-right: 8px; margin-top: -4px; }
    </style>
</head>
<body>
<div class="paper">

    {{-- ENCABEZADO --}}
    <table class="header">
        <tr>
            <td class="brand">
                @if(!empty($config['logo_path']) && file_exists(public_path($config['logo_path'])))
                    <img class="logo" src="{{ public_path($config['logo_path']) }}" alt="Logo">
                @else
                    <span class="logo-placeholder">LOGO</span>
                @endif
                <div class="inst-name">{{ $config['name'] ?? 'SHCSO' }}</div>
                <div class="inst-sub">{{ $config['subtitle'] ?? 'Sistema de Historias Clínicas y Salud Ocupacional' }}</div>
            </td>
            <td class="meta">
                <div><strong>RECETA MÉDICA</strong></div>
                <div>Fecha: {{ $fecha }}</div>
                <div>N°: RX-{{ $rx_number }}</div>
                @if(!empty($config['city']))
                    <div>{{ $config['city'] }}</div>
                @endif
                @if(!empty($qr_data_uri))
                    <div style="margin-top:6px;"><img src="{{ $qr_data_uri }}" alt="QR" style="width:72px;height:72px;border:1px solid #e2e8f0;"></div>
                @endif
            </td>
        </tr>
    </table>

    {{-- DATOS DEL PACIENTE --}}
    <div class="patient-box">
        <table>
            <tr>
                <td class="label">Paciente:</td>
                <td><strong>{{ strtoupper($worker['full_name']) }}</strong></td>
                <td class="label">Cédula:</td>
                <td>{{ $worker['document_number'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Edad:</td>
                <td>{{ $worker['age'] ?? '-' }}</td>
                <td class="label">Sexo:</td>
                <td>{{ $worker['sex'] ?? '-' }}</td>
            </tr>
            @if(!empty($worker['company']))
            <tr>
                <td class="label">Empresa:</td>
                <td colspan="3">{{ $worker['company'] }}</td>
            </tr>
            @endif
            @if(!empty($evaluation['diagnosis_summary']))
            <tr>
                <td class="label">Diagnóstico:</td>
                <td colspan="3">{{ $evaluation['diagnosis_summary'] }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- MEDICAMENTOS --}}
    <div class="rx-title"><span class="rx-symbol">Rx</span> Prescripción Médica</div>

    <table class="med-table">
        <thead>
            <tr>
                <th style="width:22px">#</th>
                <th style="width:32%">Medicamento</th>
                <th style="width:15%">Dosis</th>
                <th style="width:18%">Frecuencia</th>
                <th style="width:15%">Duración</th>
                <th>Indicaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescriptions as $i => $p)
            <tr>
                <td class="med-num">{{ $i + 1 }}</td>
                <td class="med-name">{{ $p['medication'] }}</td>
                <td>{{ $p['dosage'] ?? '-' }}</td>
                <td>{{ $p['frequency'] ?? '-' }}</td>
                <td>{{ $p['duration'] ?? '-' }}</td>
                <td>{{ $p['indications'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- NOTAS ADICIONALES --}}
    @if(!empty($evaluation['soap_p']))
    <div class="notes-box">
        <strong>Indicaciones adicionales:</strong> {{ $evaluation['soap_p'] }}
    </div>
    @endif

    {{-- ÁREA DE FIRMA --}}
    <table class="sig-area">
        <tr>
            <td>
                <div class="sig-line">
                    @if(!empty($config['signature_path']) && file_exists(public_path($config['signature_path'])))
                        <img class="sig-img" src="{{ public_path($config['signature_path']) }}" alt="Firma"><br>
                    @endif
                    <strong>{{ $config['signature_name'] ?? 'MÉDICO OCUPACIONAL' }}</strong><br>
                    {{ $config['signature_title'] ?? 'Responsable de Salud Ocupacional' }}<br>
                    @if(!empty($professional_code))Reg. Prof: {{ $professional_code }}@endif
                </div>
            </td>
            <td>
                <div class="sig-line">
                    Firma del Paciente<br><br>
                    C.I.: {{ $worker['document_number'] ?? '___________________' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- PIE --}}
    <div class="footer-note">
        {{ $config['footer_note'] ?? 'Documento confidencial de uso médico.' }}
        — Generado el {{ $fecha }} — Válido solo con firma y sello del profesional.
    </div>

</div>
</body>
</html>
