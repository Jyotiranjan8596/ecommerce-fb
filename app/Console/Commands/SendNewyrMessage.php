<?php

namespace App\Console\Commands;

use App\Services\WhatsappMessageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SendNewyrMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-newyr-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $fileName = 'user_wallets.csv';
        $fileName = 'user_wallets.csv';
        $filePath = public_path($fileName);
        if (!file_exists($filePath)) {
            throw new \Exception("Excel file not found.");
        }

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true); // 🔥 Optimization
        $spreadsheet = $reader->load($filePath);

        $sheet = $spreadsheet->getActiveSheet();

        // Get header row
        $header = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $header[] = strtolower(trim($cell->getValue()));
            }
        }

        $mobileIndex = array_search('mobilenumber', $header);
        $nameIndex   = array_search('name', $header);

        if ($mobileIndex === false || $nameIndex === false) {
            throw new \Exception("Required columns not found.");
        }

        // Iterate rows (start from row 2)
        foreach ($sheet->getRowIterator(2) as $row) {
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(true);

            $data = [];
            foreach ($cells as $cell) {
                $colIndex = Coordinate::columnIndexFromString($cell->getColumn()) - 1;
                $data[$colIndex] = $cell->getValue();
            }

            $mobile = $data[$mobileIndex] ?? null;
            $name   = $data[$nameIndex] ?? null;
            Log::info($mobile);
            if ($mobile && $name) {
                WhatsappMessageService::promotion_msg($mobile,$name);
            }
        }

        // Free memory
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}
