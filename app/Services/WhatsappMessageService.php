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
                    "name" => "hello_world",
                    "language" => ["code" => "en_US"]
                ]

            ];

            $response = Http::withToken($token)
                ->post($url, $payload)
                ->json();

            Log::info('Whatsapp Message', ['data' => $response]);

            return $response;
        } catch (Exception $e) {
            Log::info('Whatsapp Error' . $e->getMessage());
        }
    }

    public static function password_reset($mob, $otp)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

            $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";
            $template = 'pswd_reset';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $mob,
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => ["code" => "en_US"],
                    'components' => [
                        [
                            "type" => 'body',
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => (string) $otp
                                ]
                            ]
                        ],
                        [
                            "type" => "button",
                            "sub_type" => "url",
                            "index" => "0",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => (string) $otp
                                ]
                            ]
                        ]
                    ]
                ]

            ];

            $response = Http::withToken($token)
                ->post($url, $payload)
                ->json();

            Log::info('Whatsapp Message', ['data' => $response]);

            return $response;
        } catch (Exception $e) {
            Log::info('pswd rst Whatsapp Error' . $e->getMessage());
        }
    }
}
