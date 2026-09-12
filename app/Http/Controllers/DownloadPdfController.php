<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DownloadPdfController extends Controller
{
    public function downloadLedgerPdf(Request $request)
    {
        $ledger = Payment::getLedgerDataExport($request);
        $pdf = Pdf::loadView('pdf.ledgerpdf', $ledger);
        return $pdf->download('Account_Ledger.pdf');
    }
}
