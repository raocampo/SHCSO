<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Historia Clinica</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #1f2937; margin: 16px; }
    h1,h2,h3,p { margin: 0; }
    .paper { border: 1px solid #d1d5db; padding: 14px; }

    /* HEADER */
    .header { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 14px; }
    .brand { width: 60%; vertical-align: top; }
    .meta  { width: 40%; text-align: right; vertical-align: top; font-size: 9.5px; color: #334155; }
    .logo { max-height: 50px; max-width: 170px; margin-bottom: 4px; }
    .logo-placeholder { border: 1px dashed #94a3b8; color: #64748b; font-size: 9px; padding: 5px 8px; }
    .org-name { font-size: 14px; font-weight: 700; color: #0f172a; }
    .doc-title { font-size: 12px; font-weight: 700; text-transform: uppercase; margin-top: 6px; letter-spacing: .03em; }

    /* SECTIONS */
    .section { margin-top: 10px; border: 1px solid #d1d5db; padding: 8px 9px; page-break-inside: avoid; }
    .section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #0f172a; letter-spacing: .04em; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 7px; }

    /* TABLES */
    table { width: 100%; border-collapse: collapse; }
    td { padding: 3px 2px; vertical-align: top; }
    .lbl { width: 36%; color: #475569; font-weight: 600; }
    .val { width: 64%; color: #0f172a; }

    /* EVALUATION CARD */
    .eval-card { border: 1px solid #d1d5db; border-radius: 5px; margin-bottom: 8px; padding: 8px 9px; page-break-inside: avoid; }
    .eval-header { background: #f1f5f9; padding: 5px 8px; margin: -8px -9px 7px -9px; border-bottom: 1px solid #d1d5db; border-radius: 5px 5px 0 0; }
    .eval-type { font-size: 10px; font-weight: 700; color: #1e3a5f; }
    .eval-date { font-size: 9px; color: #64748b; float: right; }
    .aptitude-badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 9.5px; font-weight: 700; margin-left: 6px; }
    .APTO { background: #dcfce7; color: #166534; }
    .APTO_OBSERVACION { background: #fef3c7; color: #92400e; }
    .APTO_LIMITACIONES { background: #ffedd5; color: #7c2d12; }
    .NO_APTO { background: #ffe4e6; color: #9f1239; }

    /* PRESCRIPTION ROW */
    .rx-table th { background: #f8fafc; font-size: 9px; text-transform: uppercase; color: #64748b; padding: 3px 4px; border: 1px solid #e5e7eb; text-align: left; }
    .rx-table td { border: 1px solid #e5e7eb; padding: 3px 4px; font-size: 9.5px; }

    /* VACCINE / ACCIDENT ROW */
    .list-row { border-bottom: 1px solid #f0f0f0; padding: 3px 0; }

    /* CERT BADGE */
    .cert-row { border: 1px solid #d1d5db; border-radius: 4px; padding: 5px 8px; margin-bottom: 5px; page-break-inside: avoid; }
    .cert-valid { color: #15803d; font-weight: 700; }
    .cert-expired { color: #dc2626; font-weight: 700; }

    /* FOOTER */
    .foot { margin-top: 12px; font-size: 9px; color: #64748b; border-top: 1px solid #d1d5db; padding-top: 6px; line-height: 1.4; }

    .page-break { page-break-before: always; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .clearfix::after { content: ''; display: table; clear: both; }
</style>
</head>
<body>
@php
    $org = $institution ?? [];
    $w   = $worker;
    $now = now();
@endphp
<div class="paper">

    {{-- ===== HEADER ===== --}}
    <table class="header">
        <tr>
            <td class="brand">
                @if(!empty($org['logo_path']))
                    <img class="logo" src="{{ $org['logo_path'] }}" alt="Logo">
                @else
                    <span class="logo-placeholder">Logo</span>
                @endif
                <div class="org-name">{{ $org['name'] ?? 'SHCSO' }}</div>
                <div class="doc-title">Historia Clinica Ocupacional</div>
            </td>
            <td class="meta">
                <p><strong>N° Historia:</strong> {{ $w->history_number ?? 'N/A' }}</p>
                <p><strong>N° Archivo:</strong> {{ $w->file_number ?? 'N/A' }}</p>
                <p><strong>Emitido:</strong> {{ $now->format('d/m/Y H:i') }}</p>
                <p><strong>Ciudad:</strong> {{ $org['city'] ?? 'N/A' }}</p>
            </td>
        </tr>
    </table>

    {{-- ===== DATOS DEL TRABAJADOR ===== --}}
    <div class="section">
        <div class="section-title">Datos del Trabajador</div>
        <table>
            <tr>
                <td class="lbl">Apellidos y Nombres</td>
                <td class="val"><strong>{{ $w->last_name }} {{ $w->first_name }}</strong></td>
                <td class="lbl">Empresa</td>
                <td class="val">{{ $w->company?->business_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="lbl">N° Documento</td>
                <td class="val">{{ $w->document_number ?? 'N/A' }}</td>
                <td class="lbl">RUC empresa</td>
                <td class="val">{{ $w->company?->ruc ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="lbl">Sexo / Sangre</td>
                <td class="val">{{ $w->sex ?? 'N/A' }} / {{ $w->blood_type ?? 'N/A' }}</td>
                <td class="lbl">Cargo</td>
                <td class="val">{{ $w->jobPosition?->name ?? $w->job_title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="lbl">Fecha Nacimiento</td>
                <td class="val">{{ $w->birth_date ? \Carbon\Carbon::parse($w->birth_date)->format('d/m/Y') : 'N/A' }}</td>
                <td class="lbl">Teléfono</td>
                <td class="val">{{ $w->phone ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    {{-- ===== ANTECEDENTES ===== --}}
    @if($clinicalHistory)
    <div class="section">
        <div class="section-title">Antecedentes Clinicos</div>
        <table>
            @if($clinicalHistory->personal_history)
            <tr>
                <td class="lbl" style="width:22%">Personales</td>
                <td class="val" style="width:78%" colspan="3">{{ $clinicalHistory->personal_history }}</td>
            </tr>
            @endif
            @if($clinicalHistory->family_history)
            <tr>
                <td class="lbl">Familiares</td>
                <td class="val" colspan="3">{{ $clinicalHistory->family_history }}</td>
            </tr>
            @endif
            @if($clinicalHistory->surgical_history)
            <tr>
                <td class="lbl">Quirurgicos</td>
                <td class="val" colspan="3">{{ $clinicalHistory->surgical_history }}</td>
            </tr>
            @endif
            @if($clinicalHistory->allergies)
            <tr>
                <td class="lbl">Alergias</td>
                <td class="val" colspan="3">{{ $clinicalHistory->allergies }}</td>
            </tr>
            @endif
            @if($clinicalHistory->current_medications)
            <tr>
                <td class="lbl">Medicacion actual</td>
                <td class="val" colspan="3">{{ $clinicalHistory->current_medications }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- ===== EVALUACIONES ===== --}}
    @if($evaluations->isNotEmpty())
    <div class="section">
        <div class="section-title">Evaluaciones Medicas ({{ $evaluations->count() }})</div>
        @foreach($evaluations as $eval)
        <div class="eval-card">
            <div class="eval-header clearfix">
                <span class="eval-type">{{ $eval->evaluation_type }}</span>
                @php $apt = $eval->medical_aptitude ?? 'N/A'; @endphp
                <span class="aptitude-badge {{ $apt }}">{{ $apt }}</span>
                <span class="eval-date">{{ $eval->attention_date?->format('d/m/Y') ?? 'N/A' }}</span>
            </div>
            <table>
                @if($eval->consultation_reason)
                <tr><td class="lbl" style="width:26%">Motivo</td><td class="val" colspan="3">{{ $eval->consultation_reason }}</td></tr>
                @endif
                @if($eval->recommendations)
                <tr><td class="lbl">Recomendaciones</td><td class="val" colspan="3">{{ $eval->recommendations }}</td></tr>
                @endif
                @if($eval->diagnoses->isNotEmpty())
                <tr>
                    <td class="lbl">Diagnósticos</td>
                    <td class="val" colspan="3">
                        @foreach($eval->diagnoses as $dx)
                            <span>{{ $dx->diagnosis_code }} — {{ $dx->diagnosisCatalog?->description ?? $dx->diagnosis_code }}
                            @if($dx->diagnosis_type) ({{ $dx->diagnosis_type }}) @endif</span>
                            @if(!$loop->last)<br>@endif
                        @endforeach
                    </td>
                </tr>
                @endif
            </table>

            {{-- Prescripciones de esta evaluación --}}
            @if($eval->prescriptions->isNotEmpty())
            <div style="margin-top:6px;">
                <div style="font-size:9px;font-weight:700;color:#1e3a5f;margin-bottom:3px;">PRESCRIPCIONES</div>
                <table class="rx-table">
                    <thead>
                        <tr>
                            <th>Medicamento</th>
                            <th>Dosis</th>
                            <th>Frecuencia</th>
                            <th>Duración</th>
                            <th>Indicaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eval->prescriptions as $rx)
                        <tr>
                            <td>{{ $rx->medication }}</td>
                            <td>{{ $rx->dosage ?? '—' }}</td>
                            <td>{{ $rx->frequency ?? '—' }}</td>
                            <td>{{ $rx->duration ?? '—' }}</td>
                            <td>{{ $rx->indications ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- ===== CERTIFICADOS ===== --}}
    @if($certificates->isNotEmpty())
    <div class="section">
        <div class="section-title">Certificados Medicos ({{ $certificates->count() }})</div>
        @foreach($certificates as $cert)
        @php
            $isValid = $cert->valid_until && \Carbon\Carbon::parse($cert->valid_until)->isFuture();
        @endphp
        <div class="cert-row">
            <table>
                <tr>
                    <td style="width:50%">
                        <strong>{{ $cert->certificate_code }}</strong>
                        <span class="aptitude-badge {{ $cert->medical_aptitude ?? '' }}">{{ $cert->medical_aptitude ?? 'N/A' }}</span>
                    </td>
                    <td style="width:25%;font-size:9.5px;">Emitido: {{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d/m/Y') : 'N/A' }}</td>
                    <td style="width:25%;font-size:9.5px;">
                        Vence:
                        @if($cert->valid_until)
                            <span class="{{ $isValid ? 'cert-valid' : 'cert-expired' }}">
                                {{ \Carbon\Carbon::parse($cert->valid_until)->format('d/m/Y') }}
                                {{ $isValid ? '✓' : '(VENCIDO)' }}
                            </span>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @if($cert->observations)
                <tr>
                    <td colspan="3" style="font-size:9.5px;color:#475569;padding-top:2px;">
                        Obs: {{ $cert->observations }}
                    </td>
                </tr>
                @endif
            </table>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ===== VACUNAS ===== --}}
    @if($vaccinations->isNotEmpty())
    <div class="section">
        <div class="section-title">Vacunacion ({{ $vaccinations->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;font-size:9px;color:#64748b;padding:2px 3px;border-bottom:1px solid #e5e7eb;">Vacuna</th>
                    <th style="text-align:left;font-size:9px;color:#64748b;padding:2px 3px;border-bottom:1px solid #e5e7eb;">Dosis</th>
                    <th style="text-align:left;font-size:9px;color:#64748b;padding:2px 3px;border-bottom:1px solid #e5e7eb;">Fecha</th>
                    <th style="text-align:left;font-size:9px;color:#64748b;padding:2px 3px;border-bottom:1px solid #e5e7eb;">Lote</th>
                    <th style="text-align:left;font-size:9px;color:#64748b;padding:2px 3px;border-bottom:1px solid #e5e7eb;">Refuerzo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vaccinations as $vac)
                <tr class="list-row">
                    <td style="padding:3px;font-size:9.5px;">{{ $vac->vaccine_name }}</td>
                    <td style="padding:3px;font-size:9.5px;">{{ $vac->dose_number ?? '—' }}</td>
                    <td style="padding:3px;font-size:9.5px;">{{ $vac->vaccination_date ? \Carbon\Carbon::parse($vac->vaccination_date)->format('d/m/Y') : '—' }}</td>
                    <td style="padding:3px;font-size:9.5px;">{{ $vac->lot_number ?? '—' }}</td>
                    <td style="padding:3px;font-size:9.5px;">{{ $vac->next_dose_date ? \Carbon\Carbon::parse($vac->next_dose_date)->format('d/m/Y') : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ===== ACCIDENTES ===== --}}
    @if($accidents->isNotEmpty())
    <div class="section">
        <div class="section-title">Accidentes Laborales ({{ $accidents->count() }})</div>
        @foreach($accidents as $acc)
        <div class="list-row" style="padding:4px 0;">
            <table>
                <tr>
                    <td style="width:50%;font-size:9.5px;">
                        <strong>{{ $acc->accident_date ? \Carbon\Carbon::parse($acc->accident_date)->format('d/m/Y') : 'N/A' }}</strong>
                        — {{ $acc->accident_type ?? 'N/A' }}
                        @if($acc->body_part_affected) · {{ $acc->body_part_affected }} @endif
                    </td>
                    <td style="width:25%;font-size:9.5px;">Dias perdidos: {{ $acc->lost_working_days ?? 0 }}</td>
                    <td style="width:25%;font-size:9.5px;color:#64748b;">AT-01: {{ $acc->at01_number ?? 'N/A' }}</td>
                </tr>
                @if($acc->accident_description)
                <tr>
                    <td colspan="3" style="font-size:9px;color:#475569;padding-top:1px;">{{ Str::limit($acc->accident_description, 180) }}</td>
                </tr>
                @endif
            </table>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ===== FIRMA ===== --}}
    <table style="width:100%;margin-top:16px;">
        <tr>
            <td style="width:50%;text-align:center;vertical-align:bottom;padding:0 10px;">
                <div style="min-height:52px;border:1px solid #d1d5db;margin-bottom:5px;">
                    @if(!empty($org['signature_path']))
                        <img src="{{ $org['signature_path'] }}" style="max-height:46px;max-width:180px;margin-top:3px;" alt="Firma">
                    @endif
                </div>
                <div style="border-top:1px solid #475569;padding-top:4px;font-size:9px;">
                    <strong>{{ $org['signature_name'] ?? 'MEDICO OCUPACIONAL' }}</strong><br>
                    {{ $org['signature_title'] ?? 'Responsable de Salud Ocupacional' }}
                </div>
            </td>
            <td style="width:50%;text-align:center;vertical-align:bottom;padding:0 10px;">
                <div style="min-height:52px;border:1px solid #d1d5db;margin-bottom:5px;">
                    @if(!empty($org['seal_path']))
                        <img src="{{ $org['seal_path'] }}" style="max-height:46px;max-width:100px;margin-top:3px;" alt="Sello">
                    @endif
                </div>
                <div style="border-top:1px solid #475569;padding-top:4px;font-size:9px;">
                    Sello institucional
                </div>
            </td>
        </tr>
    </table>

    <div class="foot">
        Documento confidencial de uso exclusivo médico-ocupacional. Emitido por {{ $org['name'] ?? 'SHCSO' }}
        el {{ $now->format('d/m/Y \a \l\a\s H:i') }}.
    </div>
</div>
</body>
</html>
