<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 9pt;
    background: #fff;
    width: 283pt;
    height: 198pt;
    overflow: hidden;
  }

  .card {
    width: 283pt;
    height: 198pt;
    border: 1.5pt solid #1a6b6b;
    border-radius: 6pt;
    overflow: hidden;
    display: block;
    background: #fff;
  }

  /* Header strip */
  .header {
    background: #1a6b6b;
    color: #fff;
    padding: 5pt 8pt 4pt;
    text-align: center;
    line-height: 1.25;
  }
  .header .inst-name {
    font-size: 8pt;
    font-weight: bold;
    letter-spacing: 0.3pt;
    text-transform: uppercase;
  }
  .header .inst-sub {
    font-size: 6.5pt;
    opacity: 0.88;
    margin-top: 1pt;
  }

  /* Body layout */
  .body {
    display: table;
    width: 100%;
    height: 152pt;
  }
  .col-left {
    display: table-cell;
    width: 33%;
    background: #f0f7f7;
    vertical-align: middle;
    text-align: center;
    padding: 8pt 4pt;
    border-right: 1pt solid #c8e0e0;
  }
  .col-right {
    display: table-cell;
    width: 67%;
    vertical-align: middle;
    padding: 8pt 10pt;
  }

  /* Avatar */
  .avatar-wrap { margin-bottom: 6pt; }
  .avatar-svg {
    display: block;
    margin: 0 auto;
  }

  /* History number */
  .history-num {
    font-size: 7pt;
    font-weight: bold;
    color: #1a6b6b;
    background: #d4ecec;
    border-radius: 3pt;
    padding: 2pt 4pt;
    display: inline-block;
    letter-spacing: 0.5pt;
    margin-top: 2pt;
  }

  /* Worker info */
  .worker-name {
    font-size: 11pt;
    font-weight: bold;
    color: #0d3d3d;
    line-height: 1.2;
    margin-bottom: 4pt;
  }
  .info-row {
    font-size: 7.5pt;
    color: #333;
    margin-bottom: 2.5pt;
    line-height: 1.3;
  }
  .info-label {
    color: #777;
    font-size: 6.5pt;
    text-transform: uppercase;
    letter-spacing: 0.3pt;
  }
  .info-value {
    color: #111;
    font-weight: bold;
  }

  /* Blood type badge */
  .blood-badge {
    display: inline-block;
    background: #c0392b;
    color: #fff;
    font-size: 7pt;
    font-weight: bold;
    border-radius: 3pt;
    padding: 1pt 5pt;
    margin-left: 4pt;
    vertical-align: middle;
  }

  /* Footer strip */
  .footer {
    background: #1a6b6b;
    color: #fff;
    height: 22pt;
    display: table;
    width: 100%;
    padding: 0 8pt;
  }
  .footer-left {
    display: table-cell;
    vertical-align: middle;
    width: 50%;
  }
  .footer-right {
    display: table-cell;
    vertical-align: middle;
    width: 50%;
    text-align: right;
  }
  .footer-text {
    font-size: 6pt;
    opacity: 0.85;
    letter-spacing: 0.2pt;
  }

  /* Aptitude badge */
  .apt-badge {
    display: inline-block;
    font-size: 7pt;
    font-weight: bold;
    border-radius: 3pt;
    padding: 1.5pt 6pt;
    letter-spacing: 0.3pt;
    text-transform: uppercase;
  }
  .apt-apto        { background: #27ae60; color: #fff; }
  .apt-observacion { background: #f39c12; color: #fff; }
  .apt-limitaciones{ background: #e67e22; color: #fff; }
  .apt-no-apto     { background: #c0392b; color: #fff; }
  .apt-sin-cert    { background: #7f8c8d; color: #fff; }
</style>
</head>
<body>
<?php
  $fullName = trim(($worker->first_name ?? '') . ' ' . ($worker->last_name ?? ''));
  $histNum  = $worker->history_number ?? '—';
  $position = $worker->jobPosition
      ? trim((($worker->jobPosition->ciiu_code ?? null) ? $worker->jobPosition->ciiu_code . ' - ' : '') . $worker->jobPosition->name)
      : null;
  $company  = $worker->company->business_name ?? '—';
  $blood    = $worker->blood_type ?? null;

  // Certificate aptitude
  $aptitude = null;
  $validUntil = null;
  if ($certificate) {
    $aptitude   = $certificate->medical_aptitude ?? null;
    $validUntil = $certificate->valid_until ?? null;
  }

  $aptClass = 'apt-sin-cert';
  $aptLabel = 'Sin Certificado';
  if ($aptitude === 'APTO') {
    $aptClass = 'apt-apto'; $aptLabel = 'Apto';
  } elseif ($aptitude === 'APTO_OBSERVACION') {
    $aptClass = 'apt-observacion'; $aptLabel = 'Apto c/ Observación';
  } elseif ($aptitude === 'APTO_LIMITACIONES') {
    $aptClass = 'apt-limitaciones'; $aptLabel = 'Apto c/ Restricciones';
  } elseif ($aptitude === 'NO_APTO') {
    $aptClass = 'apt-no-apto'; $aptLabel = 'No Apto';
  }

  $instName = $institution['name'] ?? 'Servicio de Salud Ocupacional';
  $sigName  = $institution['signature_name'] ?? ($institution['representative'] ?? '');
  $sigTitle = $institution['signature_title'] ?? ($institution['professional_title'] ?? '');
?>

<div class="card">
  <!-- Header -->
  <div class="header">
    <div class="inst-name">{{ $instName }}</div>
    @if($sigName)
    <div class="inst-sub">{{ $sigTitle ? $sigTitle.' — ' : '' }}{{ $sigName }}</div>
    @endif
  </div>

  <!-- Body -->
  <div class="body">
    <!-- Left column -->
    <div class="col-left">
      <div class="avatar-wrap">
        <svg class="avatar-svg" width="48" height="48" viewBox="0 0 48 48"
             xmlns="http://www.w3.org/2000/svg">
          <circle cx="24" cy="48" r="20" fill="#1a6b6b" opacity="0.15"/>
          <circle cx="24" cy="17" r="11" fill="#1a6b6b" opacity="0.7"/>
          <ellipse cx="24" cy="39" rx="16" ry="10" fill="#1a6b6b" opacity="0.5"/>
        </svg>
      </div>
      <div class="history-num">{{ $histNum }}</div>
      @if($blood)
      <br>
      <span class="blood-badge">{{ $blood }}</span>
      @endif
    </div>

    <!-- Right column -->
    <div class="col-right">
      <div class="worker-name">{{ $fullName }}</div>

      @if($worker->document_number)
      <div class="info-row">
        <span class="info-label">Cédula: </span>
        <span class="info-value">{{ $worker->document_number }}</span>
      </div>
      @endif

      @if($position)
      <div class="info-row">
        <span class="info-label">Cargo: </span>
        <span class="info-value">{{ $position }}</span>
      </div>
      @endif

      <div class="info-row">
        <span class="info-label">Empresa: </span>
        <span class="info-value">{{ $company }}</span>
      </div>

      @if($validUntil)
      <div class="info-row" style="margin-top:3pt;">
        <span class="info-label">Válido hasta: </span>
        <span class="info-value">
          {{ \Carbon\Carbon::parse($validUntil)->format('d/m/Y') }}
        </span>
      </div>
      @endif
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <div class="footer-left">
      <span class="apt-badge {{ $aptClass }}">{{ $aptLabel }}</span>
    </div>
    <div class="footer-right">
      <span class="footer-text">Sistema de Salud Ocupacional</span>
    </div>
  </div>
</div>
</body>
</html>
