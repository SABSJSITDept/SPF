<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Pool;

class SmsService
{
    protected string $url;
    protected ?string $user;
    protected ?string $password;
    protected string $sender;
    protected string $channel;
    protected string $dcs;
    protected string $flashsms;
    protected string $route;
    protected string $peid;
    protected string $templateId;

    public function __construct()
    {
        $this->url        = config('services.sms.url', 'http://www.bulksms.saakshisoftware.in/api/mt/SendSMS');
        $this->user       = config('services.sms.user');
        $this->password   = config('services.sms.password');
        $this->sender     = config('services.sms.sender', 'ABSJHO');
        $this->channel    = config('services.sms.channel', 'trans');
        $this->dcs        = config('services.sms.dcs', '0');
        $this->flashsms   = config('services.sms.flashsms', '0');
        $this->route      = config('services.sms.route', '4');
        $this->peid       = config('services.sms.peid', '1001071123690830532');
        $this->templateId = config('services.sms.template_id', '1007421822718405594');
    }

    /**
     * Format a mobile number to ensure it has the 91 country code prefix.
     */
    protected function formatMobile(string $mobile): string
    {
        // Strip any non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $mobile);

        // If it's exactly 10 digits, prepend 91
        if (strlen($clean) === 10) {
            return '91' . $clean;
        }

        // If it already starts with 91 and is 12 digits, return as-is
        if (strlen($clean) === 12 && str_starts_with($clean, '91')) {
            return $clean;
        }

        return $clean;
    }

    /**
     * Build the standard parameter array for API requests.
     * Exact parameter names as required by Saakshi Software Bulk SMS API.
     */
    protected function buildParams(string $formattedMobile, string $message, ?string $templateId = null): array
    {
        return [
            'user'          => $this->user,
            'password'      => $this->password,
            'senderid'      => $this->sender,
            'channel'       => $this->channel,
            'DCS'           => $this->dcs,
            'flashsms'      => $this->flashsms,
            'number'        => $formattedMobile,
            'text'          => $message,
            'route'         => $this->route,
            'PEID'          => $this->peid,
            'DLTTemplateId' => $templateId ?? $this->templateId,
        ];
    }

    /**
     * Send a single approval SMS.
     */
    public function sendApprovalSms(string $mobile): bool
    {
        if (empty($this->user) || empty($this->password)) {
            Log::warning('SMS sending skipped: SMS credentials (user/password) are not configured.');
            return false;
        }

        $formattedMobile = $this->formatMobile($mobile);
        if (empty($formattedMobile)) {
            Log::warning('SMS sending failed: Invalid mobile number.', ['mobile' => $mobile]);
            return false;
        }

        $message = "JJ JGR, Your registration for the Sadhumargi SPF has been successfully completed. Thank you for joining us and becoming a part of our community.";

        try {
            $response = Http::get($this->url, $this->buildParams($formattedMobile, $message));

            if ($response->successful()) {
                Log::info('SMS sent successfully to ' . $formattedMobile, [
                    'response' => $response->body()
                ]);
                return true;
            }

            Log::error('SMS sending failed with status: ' . $response->status(), [
                'mobile' => $formattedMobile,
                'body'   => $response->body()
            ]);
        } catch (\Throwable $e) {
            Log::error('Exception while sending SMS', [
                'mobile' => $formattedMobile,
                'error'  => $e->getMessage()
            ]);
        }

        return false;
    }

    /**
     * Send bulk approval SMS in parallel using Http::pool.
     */
    public function sendApprovalSmsBulk(array $mobiles): void
    {
        if (empty($this->user) || empty($this->password)) {
            Log::warning('Bulk SMS sending skipped: SMS credentials (user/password) are not configured.');
            return;
        }

        $formattedMobiles = collect($mobiles)
            ->map(fn ($m) => $this->formatMobile($m))
            ->filter()
            ->unique()
            ->values();

        if ($formattedMobiles->isEmpty()) {
            return;
        }

        $message = "JJ JGR, Your registration for the Sadhumargi SPF has been successfully completed. Thank you for joining us and becoming a part of our community.";

        // Send requests in chunks of 25 to be polite to the gateway
        foreach ($formattedMobiles->chunk(25) as $chunk) {
            try {
                $responses = Http::pool(function (Pool $pool) use ($chunk, $message) {
                    foreach ($chunk as $mobile) {
                        $pool->as($mobile)
                            ->connectTimeout(5)
                            ->timeout(10)
                            ->get($this->url, $this->buildParams($mobile, $message));
                    }
                });

                foreach ($responses as $mobile => $response) {
                    if ($response->successful()) {
                        Log::info('Bulk SMS sent successfully to ' . $mobile, [
                            'response' => $response->body()
                        ]);
                    } else {
                        Log::warning('Bulk SMS sending failed for ' . $mobile, [
                            'status' => $response->status(),
                            'body'   => $response->body(),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Exception in Bulk SMS sending batch', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
