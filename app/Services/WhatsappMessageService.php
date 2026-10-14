<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsappMessageService
{
    public function sendApprovedTemplate(string $phone, string $templateName, string $language, array $bodyParameters = []): void
    {
        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        if (! $token || ! $phoneNumberId) {
            throw new RuntimeException('WhatsApp API credentials are not configured.');
        }

        $destination = preg_replace('/\D/', '', $phone);
        if (strlen($destination) === 10) {
            $destination = '91' . $destination;
        } elseif (str_starts_with($destination, '0') && strlen($destination) === 11) {
            $destination = '91' . substr($destination, 1);
        }

        if (! $destination || strlen($destination) < 11 || strlen($destination) > 15) {
            throw new RuntimeException('Recipient phone number is not a valid international number.');
        }

        $template = [
            'name' => $templateName,
            'language' => ['code' => $language],
        ];
        if ($bodyParameters !== []) {
            $template['components'] = [[
                'type' => 'body',
                'parameters' => array_map(static fn (string $text): array => [
                    'type' => 'text',
                    'text' => $text,
                ], $bodyParameters),
            ]];
        }

        $url = sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            config('services.whatsapp.graph_version', 'v22.0'),
            $phoneNumberId
        );
        $response = Http::withToken($token)
            ->timeout(20)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $destination,
                'type' => 'template',
                'template' => $template,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                $response->json('error.message')
                    ?? 'WhatsApp rejected the template message (HTTP ' . $response->status() . ').'
            );
        }

    }

    public static function send($to)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
            // $to = '7077783948'; // Verified test number

            $url = "https://graph.facebook.com/v22.0/886143784580503/messages";
            $template = 'happy_new_year';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "template",
                "template" => [
                    "name" => "happy_new_year",
                    "language" => ["code" => "en_US"],
                    'components' => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "image",
                                    "image" => [
                                        "link" => url('images/whatsapp/newyrmsg.jpeg')
                                    ]
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
            Log::info('Whatsapp Error' . $e->getMessage());
        }
    }

    public static function user_registration($name, $mob)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
            // $to = '7077783948'; // Verified test number

            $url = "https://graph.facebook.com/v22.0/886143784580503/messages";
            $template = 'register_user';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $mob,
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => ["code" => "en"],
                    'components' => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "image",
                                    "image" => [
                                        "link" => url('images/whatsapp/freebazarlogo.jpg')
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => 'body',
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $name
                                ],
                            ]
                        ],
                    ]
                ]

            ];

            $response = Http::withToken($token)
                ->post($url, $payload)
                ->json();

            Log::info('Whatsapp Message for user register', ['data' => $response]);

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
            $template = 'pswd_resets';
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

    public static function promotion_msg($mob, $name)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

            $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";
            $template = 'holi_message';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $mob,
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => ["code" => "en_US"],
                    'components' => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => (string) $name
                                ]
                            ]
                        ],
                        // [
                        //     "type" => 'body',
                        //     "parameters" => [
                        //         [
                        //             "type" => "text",
                        //             "text" => (string) $name
                        //         ]
                        //     ]
                        // ]
                        // ,
                        // [
                        //     "type" => "button",
                        //     "sub_type" => "url",
                        //     "index" => "0",
                        //     // "parameters" => [
                        //     //     [
                        //     //         "type" => "text",
                        //     //         "text" => 'login'
                        //     //     ]
                        //     // ]
                        // ]
                    ]
                ]

            ];

            $response = Http::withToken($token)
                ->post($url, $payload)
                ->json();

            Log::info('Whatsapp Message', ['data' => $response]);

            return $response;
        } catch (Exception $e) {
            Log::info('Marketing message Whatsapp Error' . $e->getMessage());
        }
    }

    public static function user_transaction($mob, $parameters)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

            $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";
            $template = 'user_transaction2';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $mob,
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => ["code" => "en_US"],
                    'components' => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $parameters['user_name']   // Jyotiranjan Sahoo
                                ]
                            ]
                        ],
                        [
                            "type" => 'body',
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $parameters['user_id']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['pos_name']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['trans_id']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['billing_amount']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['pay_by']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['paid_amount']
                                ],
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
            Log::info('USer Transaction Whatsapp Error' . $e->getMessage());
        }
    }

    public static function pos_transaction($mob, $parameters)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

            $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";
            $template = 'pos_transaction2';
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $mob,
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => ["code" => "en"],
                    'components' => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $parameters['user_name']   // Jyotiranjan Sahoo
                                ]
                            ]
                        ],
                        [
                            "type" => 'body',
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $parameters['user_id']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['pos_name']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['trans_id']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['billing_amount']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['paid_amount']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['by_cash']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['transaction_amount']
                                ],
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
            Log::info('USer Transaction Whatsapp Error' . $e->getMessage());
        }
    }

    public static function settlement_message($mob, $parameters)
    {
        try {
            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
            $url = "https://graph.facebook.com/v22.0/886143784580503/messages";
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => $mob,
                "type" => "template",
                "template" => [
                    "name" => "settlement_message",
                    "language" => ["code" => "en_US"],
                    'components' => [
                        [
                            "type" => "header",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $parameters['pos_name']   // Jyotiranjan Sahoo
                                ]
                            ]
                        ],
                        [
                            "type" => 'body',
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $parameters['amount']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['trans_date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $parameters['settle_date']
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
            Log::info('Whatsapp Error' . $e->getMessage());
        }
    }
}
