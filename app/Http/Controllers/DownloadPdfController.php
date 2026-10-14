<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PosModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DownloadPdfController extends Controller
{
    public function downloadLedgerPdf(Request $request)
    {
        $rules = [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ];
        if ($request->routeIs('admin.ledger.download-pdf')) {
            $rules['pos_id'] = ['required', 'integer', 'exists:pos_systems,id'];
        }
        $request->validate($rules);

        $pos = $request->routeIs('admin.ledger.download-pdf')
            ? PosModel::findOrFail($request->integer('pos_id'))
            : null;
        $ledger = Payment::getLedgerDataExport($request, $pos?->user_id, $pos?->name);
        $pdf = Pdf::loadView('pdf.ledgerpdf', $ledger)->setPaper('a4', 'landscape');
        $accountName = $pos?->name ?? auth()->user()->name;
        $filename = 'AccountLedger_' . Str::slug($accountName) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
