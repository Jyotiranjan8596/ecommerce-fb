<?php

namespace App\Console\Commands;

use App\Services\WhatsappMessageService;
use Illuminate\Console\Command;

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
        // WhatsappMessageService::send();
        WhatsappMessageService::promotion_msg('90400 30361','Rakesh Mohanty');
    }
}
