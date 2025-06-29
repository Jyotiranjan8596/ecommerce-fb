<?php

namespace App\Services;

use Zxing\QrReader;
use Illuminate\Support\Facades\Storage;

class QrDecoderService
{
    /**
     * Decode a QR image and extract UPI ID if available
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array ['upi_id' => string|null, 'raw_data' => string|null]
     */
    public static function extractUpiIdFromImage($file): array
    {
        // Store the image temporarily
        $path = $file->store('qr_codes', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Decode QR
        $qr = new QrReader($fullPath);
        // dd($qr);
        $text = $qr->text(); // full QR content

        // Clean up the file (optional)
        Storage::disk('public')->delete($path);
        if ($text && str_starts_with($text, 'upi://')) {
            parse_str(parse_url($text, PHP_URL_QUERY), $params);
            return [
                'upi_id' => $params['pa'] ?? null,
                'raw_data' => $text,
            ];
        }

        return [
            'upi_id' => null,
            'raw_data' => $text,
        ];
    }
}
