<?php

namespace Modules\User\Services\Notifications\Providers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\User\Entities\Customer;
use Modules\User\Services\Notifications\Providers\Contracts\Provider;

class SmsProvider implements Provider
{
    private Customer $customer;
    private array $text;


    public function __construct(Customer $customer, array $text)
    {
        $this->customer = $customer;
        $this->text = $text;
    }


    /**
     * @throws Exception
     */
    public function send()
    {
        $pattern = $this->makePattern();

        $apiKey = "AccessKey " . config('user.sms.api_key');
        $response = Http::withHeaders(["Authorization" => $apiKey])
            ->post(config('user.sms.url'), [
                'pattern_code' => $pattern['pattern_id'],
                'originator' => config('user.sms.send_from'),
                'recipient' => $this->customer->mobile,
                'values' => $pattern['values'],
            ])->json();

        //Log::info($response);
        if ($response['status'] !== "OK") {
            Log::warning($response['message']);
            throw new Exception($response['message']);
        }

    }


    private function makePattern(): array
    {
        $values = [];
        switch ($this->text['pattern_type']) {
            case 'delivery_shipping_alert_pattern':
                $values = [
                    'pattern_id' => config('user.sms.delivery_shipping_alert_pattern'),
                    'values' => [
                        'shop_name' => config('dashboard.short_shop_title'),
                        'name' => $this->customer->full_name,
                        'shipping_company' => $this->text['shipping_company'],
                        'orderId' => $this->text['orderId'],
                        'tracking_number' => $this->text['tracking_number'],
                    ],
                ];
                break;
            case 'notification_order_status_pattern':
                $values = [
                    'pattern_id' => config('user.sms.notification_order_status_pattern'),
                    'values' => [
                        'shop_name' => config('dashboard.short_shop_title'),
                        'name' => $this->customer->full_name,
                        'do' => $this->text['do'],
                        'status' => $this->text['status'],
                        'orderId' => $this->text['orderId'],
                    ],
                ];
                break;
            case 'thank_you_after_sale_pattern':
                $values = [
                    'pattern_id' => config('user.sms.thank_you_after_sale_pattern'),
                    'values' => [
                        'shop_name' => config('dashboard.short_shop_title'),
                        'name' => $this->customer->full_name,
                        'factor_link' => $this->text['factor_link'],
                    ],
                ];
                break;
            case 'send_otp_for_customer':
                $values = [
                    'pattern_id' => config('user.sms.pattern_id'),
                    'values' => [
                        'full_name' => ($this->customer->first_name == null && $this->customer->last_name == null) ? 'مشتری' : $this->customer->full_name,
                        'code' => (string) $this->text['code'],
                    ]
                ];
                break;
            case 'send_coupon_for_customer':
                $values = [
                    'pattern_id' => config('user.sms.send_coupon_for_customer'),
                    'values' => [
                        'amount' => (string) $this->text['amount'],
                        'coupon' => (string) $this->text['coupon'],
                    ]
                ];
                break;
        }

        return $values;
    }

}
