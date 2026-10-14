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
        $msg_reslt = $whatsapp->user_registration('Jyotiranjan Sahoo', '6371639109');
        // $msg_reslt = $whatsapp->sponsor_message('9668305876');
        //    $msg_reslt =  $whatsapp->pos_registration('AAMA GHARA HOTEL AND RESTAURANT', '9668305876');
        Log::info($msg_reslt);
    }
}
