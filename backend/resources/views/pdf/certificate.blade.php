<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Certificado Ocupacional</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 24px;
        }
        h1, h2, h3 {
            margin: 0;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .title {
            font-size: 18px;
            font-weight: 700;
        }
        .subtitle {
            font-size: 12px;
            color: #374151;
            margin-top: 4px;
        }
        .section {
            margin-top: 14px;
            border: 1px solid #d1d5db;
            padding: 10px 12px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            width: 40%;
            color: #4b5563;
            font-weight: 600;
        }
        .value {
            width: 60%;
            color: #111827;
        }
        .aptitude {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            padding: 10px;
            border: 2px solid #0f172a;
            margin-top: 4px;
        }
        .foot {
            margin-top: 20px;
            font-size: 10px;
            color: #4b5563;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Certificado de Evaluacion Medica Ocupacional</h1>
        <div class="subtitle">
            Codigo: {{ $certificate->certificate_code }} |
            Fecha emision: {{ optional($certificate->issue_date)->format('Y-m-d') }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Datos del establecimiento y trabajador</div>
        <table>
            <tr>
                <td class="label">Empresa</td>
                <td class="value">{{ $certificate->worker?->company?->business_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">RUC / CIIU</td>
                <td class="value">
                    {{ $certificate->worker?->company?->ruc ?? 'N/A' }} /
                    {{ $certificate->worker?->company?->ciiu ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="label">Centro de trabajo</td>
                <td class="value">{{ $certificate->worker?->company?->work_center ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Trabajador</td>
                <td class="value">
                    {{ $certificate->worker?->first_name }} {{ $certificate->worker?->last_name }}
                </td>
            </tr>
            <tr>
                <td class="label">Documento</td>
                <td class="value">{{ $certificate->worker?->document_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Historia Clinica / Archivo</td>
                <td class="value">
                    {{ $certificate->worker?->history_number ?? 'N/A' }} /
                    {{ $certificate->worker?->file_number ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="label">Tipo de evaluacion</td>
                <td class="value">{{ $certificate->evaluation?->evaluation_type ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Dictamen de aptitud</div>
        <div class="aptitude">{{ $certificate->medical_aptitude }}</div>
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

    <div class="foot">
        Documento confidencial de uso medico ocupacional. Emitido por SHCSO.
    </div>
</body>
</html>

