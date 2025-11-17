<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    // App/Http/Controllers/WhatsAppController.php

    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Put exactly this token in Meta dashboard
        $expectedToken = env('WHATSAPP_VERIFY_TOKEN'); // e.g., "mysecretverifytoken123"

        if ($mode === 'subscribe' && $token === $expectedToken) {
            // MUST return challenge as plain text, 200 status
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request)
    {
        Log::info('WhatsApp Webhook Payload:', $request->all());
        // your normal webhook logic here
        return response()->json(['status' => 'ok']);
    }
}
