<?php

namespace App\Console\Commands;

use App\Services\WhatsappMessageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

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
        // WhatsappMessageService::send('7609942076');
        // WhatsappMessageService::promotion_msg('9336801361','Rakesh Mohanty');

        $whatsapp  = new WhatsappMessageService();
        // $msg_reslt = $whatsapp->user_registration('Jyotiranjan Sahoo', '7609942076');
        // Log::info($msg_reslt);
        $parameters = [
            'pos_name' => 'Test 9',
            'trans_date' => '31-07-2026',
            'settle_date' => '01-08-2026',
            'amount' => '45'
        ];
        $msg_res = $whatsapp->settlement_message('9040030361', $parameters);
        Log::info($msg_res);
    }
}
