<?php

namespace Tests\Unit;

use App\Services\WhatsappMessageService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class WhatsappMessageServiceTest extends TestCase
{
    public function test_it_sends_an_approved_template_with_body_parameters(): void
    {
        config([
            'services.whatsapp.token' => 'test-token',
            'services.whatsapp.phone_number_id' => '123456',
            'services.whatsapp.graph_version' => 'v22.0',
        ]);
        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.test']]], 200),
        ]);

        app(WhatsappMessageService::class)->sendApprovedTemplate(
            '(987) 654-3210',
            'order_update',
            'en_US',
            ['Hello Ada', 'ORDER-1']
        );

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://graph.facebook.com/v22.0/123456/messages'
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && $request['to'] === '919876543210'
                && $request['template']['name'] === 'order_update'
                && $request['template']['language']['code'] === 'en_US'
                && $request['template']['components'][0]['parameters'][0]['text'] === 'Hello Ada'
                && $request['template']['components'][0]['parameters'][1]['text'] === 'ORDER-1';
        });
    }

    public function test_it_reports_missing_credentials_without_making_a_request(): void
    {
        config([
            'services.whatsapp.token' => null,
            'services.whatsapp.phone_number_id' => null,
        ]);
        Http::fake();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('WhatsApp API credentials are not configured.');

        app(WhatsappMessageService::class)->sendApprovedTemplate('5551234567', 'order_update', 'en_US');
    }

    public function test_it_reports_provider_rejections(): void
    {
        config([
            'services.whatsapp.token' => 'test-token',
            'services.whatsapp.phone_number_id' => '123456',
        ]);
        Http::fake([
            'graph.facebook.com/*' => Http::response(['error' => ['message' => 'Template not found']], 400),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Template not found');

        app(WhatsappMessageService::class)->sendApprovedTemplate('5551234567', 'order_update', 'en_US');
    }
}
