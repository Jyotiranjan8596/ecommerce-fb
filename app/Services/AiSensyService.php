<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiSensyService
{
    protected $apiUrl = 'https://backend.aisensy.com/campaign/t1/api/v2';

    /**
     * Send a WhatsApp template message using AiSensy API.
     *
     * @param string $phone        Receiver phone number in international format (e.g., 918765432100)
     * @param array $parameters    Template parameters (for {{1}}, {{2}}, etc.)
     * @return array               API response as an array
     */
    public function sendTransactionMessage($phone, $parameters)
    {
        try {
            // Ensure phone number is in international format (without +)
            $formattedPhone = preg_replace('/\D/', '', $phone);
            if (! str_starts_with($formattedPhone, '91')) {
                $formattedPhone = '91' . $formattedPhone;
            }

            $payload = [
                'apiKey'         => env('AISENSY_API_KEY'), // Set in .env
                'campaignName'   => env('AISENSY_CAMPAIGN_NAME', 'freebazar_transaction'),
                'destination'    => $formattedPhone,
                'userName'       => $parameters[0],
                'source'         => 'transaction',
                'templateParams' => $parameters,
            ];

            $response = Http::post($this->apiUrl, $payload);
            return true;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    public function send_registration($phone, $parameters)
    {
        try {
            $formattedPhone = preg_replace('/\D/', '', $phone);
            if (! str_starts_with($formattedPhone, '91')) {
                $formattedPhone = '91' . $formattedPhone;
            }

            $payload = [
                'apiKey'         => env('AISENSY_API_KEY'), // Set in .env
                'campaignName'   => env('AISENSY_CAMPAIGN_NAME', 'user_signup_v3'),
                'destination'    => $formattedPhone,
                'userName'       => $parameters[0],
                'source'         => 'registration',
                'templateParams' => $parameters,
            ];
            $response = Http::post($this->apiUrl, $payload);
            return $response;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return $e->getMessage();
        }
    }
}
