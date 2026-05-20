<?php

namespace Tests\Feature;

use App\Services\SmsService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set standard config values for testing matching the user's provider parameters
        config(['services.sms.url' => 'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS']);
        config(['services.sms.user' => 'JainSangh']);
        config(['services.sms.password' => 'Jain@12']);
        config(['services.sms.sender' => 'ABSJHO']);
        config(['services.sms.channel' => 'trans']);
        config(['services.sms.dcs' => '0']);
        config(['services.sms.flashsms' => '0']);
        config(['services.sms.route' => '4']);
        config(['services.sms.peid' => '1001071123690830532']);
        config(['services.sms.template_id' => '1007421822718405594']);
    }

    public function test_sms_sending_skipped_when_credentials_are_missing()
    {
        config(['services.sms.user' => '']);
        config(['services.sms.password' => '']);
        
        $smsService = new SmsService();
        $result = $smsService->sendApprovalSms('9876543210');

        $this->assertFalse($result);
    }

    public function test_single_sms_sends_successfully()
    {
        Http::fake([
            'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS*' => Http::response('success', 200),
        ]);

        $smsService = new SmsService();
        $result = $smsService->sendApprovalSms('9876543210');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            $url = $request->url();
            $queryStr = parse_url($url, PHP_URL_QUERY);
            parse_str($queryStr, $query);

            return str_starts_with($url, 'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS') &&
                   $request->method() === 'GET' &&
                   ($query['user'] ?? null) === 'JainSangh' &&
                   ($query['password'] ?? null) === 'Jain@12' &&
                   ($query['senderid'] ?? null) === 'ABSJHO' &&
                   ($query['channel'] ?? null) === 'trans' &&
                   ($query['DCS'] ?? null) === '0' &&
                   ($query['flashsms'] ?? null) === '0' &&
                   ($query['route'] ?? null) === '4' &&
                   ($query['PEID'] ?? null) === '1001071123690830532' &&
                   ($query['DLTTemplateId'] ?? null) === '1007421822718405594' &&
                   ($query['number'] ?? null) === '919876543210' &&
                   str_contains(($query['text'] ?? ''), 'Sadhumargi SPF');
        });
    }

    public function test_bulk_sms_sends_successfully()
    {
        Http::fake([
            'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS*' => Http::response('success', 200),
        ]);

        $smsService = new SmsService();
        $smsService->sendApprovalSmsBulk(['9876543210', '911234567890']);

        Http::assertSent(function ($request) {
            $url = $request->url();
            $queryStr = parse_url($url, PHP_URL_QUERY);
            parse_str($queryStr, $query);

            return str_starts_with($url, 'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS') &&
                   ($query['number'] ?? null) === '919876543210';
        });

        Http::assertSent(function ($request) {
            $url = $request->url();
            $queryStr = parse_url($url, PHP_URL_QUERY);
            parse_str($queryStr, $query);

            return str_starts_with($url, 'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS') &&
                   ($query['number'] ?? null) === '911234567890';
        });
    }
}
