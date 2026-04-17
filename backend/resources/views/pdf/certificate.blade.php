<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Certificado Ocupacional</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11.5px;
            color: #1f2937;
            margin: 18px;
            background: #ffffff;
        }
        h1, h2, h3, p {
            margin: 0;
        }
        .paper {
            border: 1px solid #d1d5db;
            padding: 14px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .header td {
            vertical-align: top;
        }
        .brand {
            width: 58%;
        }
        .meta {
            width: 42%;
            text-align: right;
            font-size: 10px;
            color: #334155;
        }
        .logo {
            max-height: 54px;
            max-width: 180px;
            margin-bottom: 5px;
        }
        .logo-placeholder {
            display: inline-block;
            border: 1px dashed #94a3b8;
            color: #64748b;
            font-size: 10px;
            padding: 6px 8px;
            margin-bottom: 5px;
        }
        .org-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        .org-subtitle {
            font-size: 10.5px;
            color: #334155;
            margin-top: 2px;
        }
        .doc-title {
            margin-top: 8px;
            font-size: 13px;
            letter-spacing: 0.02em;
            font-weight: 700;
            text-transform: uppercase;
        }
        .doc-subtitle {
            font-size: 10px;
            margin-top: 3px;
            color: #334155;
        }
        .section {
            margin-top: 10px;
            border: 1px solid #d1d5db;
            padding: 8px 9px;
        }
        .section-title {
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.04em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label {
            width: 38%;
            color: #475569;
            font-weight: 600;
        }
        .value {
            width: 62%;
            color: #0f172a;
        }
        .aptitude {
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            padding: 8px;
            border: 2px solid #0f172a;
            letter-spacing: 0.02em;
        }
        .aptitude.APTO {
            color: #166534;
            border-color: #166534;
            background: #ecfdf3;
        }
        .aptitude.APTO_OBSERVACION {
            color: #92400e;
            border-color: #b45309;
            background: #fffbeb;
        }
        .aptitude.APTO_LIMITACIONES {
            color: #7c2d12;
            border-color: #c2410c;
            background: #fff7ed;
        }
        .aptitude.NO_APTO {
            color: #9f1239;
            border-color: #be123c;
            background: #fff1f2;
        }
        .signatures {
            width: 100%;
            margin-top: 14px;
        }
        .signatures td {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
            padding: 0 8px;
        }
        .signature-box,
        .seal-box {
            min-height: 64px;
            border: 1px solid #d1d5db;
            margin-bottom: 6px;
            position: relative;
        }
        .signature-image {
            max-height: 58px;
            max-width: 200px;
            margin-top: 4px;
        }
        .seal-image {
            max-height: 58px;
            max-width: 120px;
            margin-top: 4px;
        }
        .placeholder-note {
            color: #64748b;
            font-size: 9px;
            padding-top: 24px;
        }
        .sign-line {
            border-top: 1px solid #475569;
            margin-top: 5px;
            padding-top: 4px;
            font-size: 9px;
            color: #0f172a;
        }
        .foot {
            margin-top: 12px;
            font-size: 9px;
            color: #475569;
            border-top: 1px solid #d1d5db;
            padding-top: 7px;
            line-height: 1.35;
        }
    </style>
</head>
<body>
@php
    $org = $institution ?? [];
    $issueDate = $certificate->issue_date ? $certificate->issue_date->format('Y-m-d') : now()->format('Y-m-d');
    $aptitude = $certificate->medical_aptitude ?? 'N/A';
    $attentionDateRaw = $certificate->evaluation?->attention_date;
    $attentionDate = $attentionDateRaw instanceof \Carbon\CarbonInterface
        ? $attentionDateRaw->format('Y-m-d')
        : ($attentionDateRaw ? substr((string) $attentionDateRaw, 0, 10) : 'N/A');
@endphp
<div class="paper">
    <table class="header">
        <tr>
            <td class="brand">
                @if(!empty($org['logo_path']))
                    <img class="logo" src="{{ $org['logo_path'] }}" alt="Logo institucional">
                @else
                    <span class="logo-placeholder">Logo institucional</span>
                @endif
                <div class="org-name">{{ $org['name'] ?? 'SHCSO' }}</div>
                <div class="org-subtitle">{{ $org['subtitle'] ?? 'Sistema de Historias Clinicas y Salud Ocupacional' }}</div>
                <div class="doc-title">Certificado de evaluacion medica ocupacional</div>
                <div class="doc-subtitle">Codigo {{ $certificate->certificate_code }}</div>
            </td>
            <td class="meta">
                <p><strong>Fecha emision:</strong> {{ $issueDate }}</p>
                <p><strong>Ciudad:</strong> {{ $org['city'] ?? 'N/A' }}</p>
                <p><strong>Historia clinica:</strong> {{ $certificate->worker?->history_number ?? 'N/A' }}</p>
                <p><strong>Archivo:</strong> {{ $certificate->worker?->file_number ?? 'N/A' }}</p>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Datos de establecimiento y trabajador</div>
        <table>
            <tr>
                <td class="label">Empresa</td>
                <td class="value">{{ $certificate->worker?->company?->business_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">RUC / CIIU</td>
                <td class="value">{{ $certificate->worker?->company?->ruc ?? 'N/A' }} / {{ $certificate->worker?->company?->ciiu ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Centro de trabajo</td>
                <td class="value">{{ $certificate->worker?->company?->work_center ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Trabajador</td>
                <td class="value">{{ $certificate->worker?->first_name }} {{ $certificate->worker?->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Documento</td>
                <td class="value">{{ $certificate->worker?->document_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Tipo de evaluacion</td>
                <td class="value">{{ $certificate->evaluation?->evaluation_type ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de atencion</td>
                <td class="value">{{ $attentionDate }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Dictamen de aptitud</div>
        <div class="aptitude {{ $aptitude }}">{{ $aptitude }}</div>
    </div>

    <div class="section">
        <div class="section-title">Observaciones y recomendaciones</div>
        <table>
            <tr>
                <td class="label">Observaciones</td>
                <td class="value">{{ $certificate->observations ?? 'Sin observaciones.' }}</td>
            </tr>
            <tr>
                <td class="label">Recomendaciones</td>
                <td class="value">{{ $certificate->recommendations ?? 'Sin recomendaciones.' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Profesional responsable</div>
        <table>
            <tr>
                <td class="label">Nombre</td>
                <td class="value">{{ $certificate->professional_name }}</td>
            </tr>
            <tr>
                <td class="label">Codigo profesional</td>
                <td class="value">{{ $certificate->professional_code }}</td>
            </tr>
        </table>
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div class="signature-box">
                    @if(!empty($org['signature_path']))
                        <img class="signature-image" src="{{ $org['signature_path'] }}" alt="Firma responsable">
                    @else
                        <p class="placeholder-note">Firma no cargada</p>
                    @endif
                </div>
                <div class="sign-line">
                    <strong>{{ $org['signature_name'] ?? 'MEDICO OCUPACIONAL' }}</strong><br>
                    {{ $org['signature_title'] ?? 'Responsable de Salud Ocupacional' }}
                </div>
            </td>
            <td>
                <div class="seal-box">
                    @if(!empty($org['seal_path']))
                        <img class="seal-image" src="{{ $org['seal_path'] }}" alt="Sello institucional">
                    @else
                        <p class="placeholder-note">Sello institucional</p>
                    @endif
                </div>
                <div class="sign-line">
                    Sello del establecimiento
                </div>
            </td>
        </tr>
    </table>

    <div class="foot">
        <table style="width:100%;">
            <tr>
                <td style="vertical-align:top;width:80%;">
                    {{ $org['footer_note'] ?? 'Documento confidencial de uso medico ocupacional.' }}<br>
                    Emitido por {{ $org['name'] ?? 'SHCSO' }}.
                </td>
                @if(!empty($qr_data_uri))
                <td style="vertical-align:top;text-align:right;width:20%;">
                    <img src="{{ $qr_data_uri }}" alt="QR verificacion" style="width:62px;height:62px;border:1px solid #e2e8f0;">
                    <div style="font-size:7px;color:#94a3b8;text-align:center;margin-top:2px;">Verificacion</div>
                </td>
                @endif
            </tr>
        </table>
    </div>
</div>
</body>
</html>
