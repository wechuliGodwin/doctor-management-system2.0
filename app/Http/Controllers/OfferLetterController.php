<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use PDF;

class OfferLetterController extends Controller
{
    public function download($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if (!$supplier) {
            abort(404);
        }

        $html = view('emails.offer-letter', compact('supplier'))->render();

        $pdf = PDF::loadHTML($html)
                    ->setPaper('a4', 'portrait')
                    ->setOptions([
                        'defaultFont' => 'DejaVu Sans',
                        'isRemoteEnabled' => true, // In case images are not local
                        'dpi' => 96,
                        'isHtml5ParserEnabled' => true,
                        'isPhpEnabled' => true,
                        'no-header' => true,
                        'no-footer' => true
                    ]);

        // Ensure base path is set correctly for images
        $pdf->getDomPDF()->set_base_path(public_path());

        $fileName = 'offer-letter-' . str_replace(' ', '-', $supplier->name) . '.pdf';
        
        return $pdf->download($fileName);
    }
}