<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ManualController extends Controller
{
    public function download(): Response
    {
        $pdf = Pdf::loadView('pdf.user-manual')
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'       => 'DejaVu Sans',
                'isRemoteEnabled'   => false,
                'isHtml5ParserEnabled' => true,
                'dpi'               => 96,
            ]);

        return $pdf->download('SHCSO-Manual-de-Usuario.pdf');
    }

    public function preview(): Response
    {
        $pdf = Pdf::loadView('pdf.user-manual')
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'       => 'DejaVu Sans',
                'isRemoteEnabled'   => false,
                'isHtml5ParserEnabled' => true,
                'dpi'               => 96,
            ]);

        return $pdf->stream('SHCSO-Manual-de-Usuario.pdf');
    }
}
