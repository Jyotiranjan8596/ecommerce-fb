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
        Log::info('Webhook Received:', $request->all());

        return response()->json(['status' => 'ok'], 200);
    }

    // for webhook verification
    public function verify(Request $request)
    {
        $verify_token = "YOUR_VERIFY_TOKEN";

        if (
            $request->hub_mode == 'subscribe' &&
            $request->hub_verify_token == $verify_token
        ) {

            return $request->hub_challenge;
        }
        return response('Invalid verify token', 403);
    }
}
