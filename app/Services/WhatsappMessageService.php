<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappMessageService
{
    public static function send()
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
            $to = '917978017858'; // Verified test number

            $url = "https://graph.facebook.com/v22.0/886143784580503/messages";
            $template = 'hello_world';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "template",
                "template" => [
                    "name" => "address_update2",
                    "language" => ["code" => "en_US"],
                    "components" => [
                        [
                            "type" => "body",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => "Confirmed"   // {{1}} Order Status
                                ],
                                [
                                    "type" => "text",
                                    "text" => "Jyotiranjan" // {{2}} Customer Name
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::withToken($token)
                ->post($url, $payload)
                ->json();

            Log::info('Whatsapp Message' ,['data'=>$response]);

            return $response;
        } catch (Exception $e) {
            Log::info('Whatsapp Error' . $e->getMessage());
        }
    }
}
