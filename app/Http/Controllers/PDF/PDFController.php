<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;

class PDFController extends Controller
{
    public function show(string $filename)
    {
        // Strip potential path traversal sequences (e.g., ../)
        $fileName = basename($filename);

        // Ensure the filename ends with .pdf extension
        if (! str_ends_with(strtolower($fileName), '.pdf')) {
            $fileName .= '.pdf';
        }

        // Target path inside public/product/ folder
        $filePath = public_path('product/'.$fileName);

        // Abort with a 404 if the requested PDF doesn't exist
        if (! file_exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        // Stream PDF inline to open directly in browser / QR code readers
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }
}
