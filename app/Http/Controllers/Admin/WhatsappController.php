<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    // App/Http/Controllers/WhatsAppController.php

    public function handle(Request $request)
    {
        // Log::info('Webhook Received:', $request->all());

        $value = $request->input('entry.0.changes.0.value');

        // Incoming message
        if (isset($value['messages'])) {
            $message = $value['messages'][0];
            $from = $message['from'];
            $text = $message['text']['body'] ?? '';

            Log::info("Incoming message from {$from}: {$text}");

            // you can reply here
        }

        // Message status update
        if (isset($value['statuses'])) {
            $status = $value['statuses'][0];

            Log::info('Message status update', [
                'message_id' => $status['id'] ?? null,
                'status'     => $status['status'] ?? null,
                'timestamp'  => $status['timestamp'] ?? null,
            ]);
        }

        return response()->json(['status' => 'ok'], 200);
    }


    // for webhook verification
    public function verify(Request $request)
    {
        $verify_token = env("WHATSAPP_VERIFY_TOKEN");

        if (
            $request->hub_mode == 'subscribe' &&
            $request->hub_verify_token == $verify_token
        ) {

            return $request->hub_challenge;
        }
        return response('Invalid verify token', 403);
    }
}
