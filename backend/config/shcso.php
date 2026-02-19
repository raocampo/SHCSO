<?php

return [
    'institution' => [
        'name' => env('SHCSO_INSTITUTION_NAME', 'SHCSO'),
        'subtitle' => env('SHCSO_INSTITUTION_SUBTITLE', 'Sistema de Historias Clinicas y Salud Ocupacional'),
        'city' => env('SHCSO_INSTITUTION_CITY', 'Quito'),
    ],
    'pdf_certificate' => [
        'logo_path' => env('SHCSO_CERTIFICATE_LOGO_PATH'),
        'seal_path' => env('SHCSO_CERTIFICATE_SEAL_PATH'),
        'signature_path' => env('SHCSO_CERTIFICATE_SIGNATURE_PATH'),
        'signature_name' => env('SHCSO_CERTIFICATE_SIGNATURE_NAME', 'MEDICO OCUPACIONAL'),
        'signature_title' => env('SHCSO_CERTIFICATE_SIGNATURE_TITLE', 'Responsable de Salud Ocupacional'),
        'footer_note' => env('SHCSO_CERTIFICATE_FOOTER_NOTE', 'Documento confidencial de uso medico ocupacional.'),
    ],
];
