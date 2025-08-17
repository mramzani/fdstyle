<?php

namespace App\Services\Payment\Gateway\Zibal;

use Exception;
use Modules\Order\Entities\SubMerchants;

class ZibalCheckout
{
    const REPORT_TRANSACTION_URL = "https://api.zibal.ir/v1/gateway/report/transaction";
    const REQUEST_CHECKOUT_URL = "https://api.zibal.ir/v1/wallet/checkout";
    const CHECKOUT_SUCCESS = 'checkout.success';
    const CHECKOUT_FAILED = 'checkout.failed';
    const SUCCESS_STATUS_CODE = 1;

    private string $apiKey;
    private string $walletId;
    private string $merchantId;
    private string $description_prefix;

    public function __construct()
    {
        $this->apiKey = config('front.gateway.zibal.api_key');
        $this->walletId = config('front.gateway.zibal.wallet_id');
        $this->merchantId = config('front.gateway.zibal.merchantID');
        $this->description_prefix = 'تسویه حساب ';
    }

    /**
     * get total and sum transaction
     * @return array
     */
    public function reportTransaction(): array
    {
        $data = [
            'merchantId' => $this->merchantId,
        ];

        $response = $this->sendRequest(self::REPORT_TRANSACTION_URL, $data);
        
        if ($response['result'] == self::SUCCESS_STATUS_CODE) {
            return [
                'total' => $response['total'],
                'sum' => 0,
            ];
        }
        return [
            'total' => 'error',
            'sum' => 'error',
        ];
    }

    /**
     * send checkout request
     * @param SubMerchants $merchant
     * @return array|string[]
     * @throws Exception
     */
    public function requestCheckout(SubMerchants $merchant): array
    {
        $this->checkUserRequestCheckout($merchant);

        $this->checkMerchantBalance($merchant);

        $data = [
            'amount' => $merchant->balance,
            'id' => $this->walletId,
            'subMerchantId' => $merchant->merchant_ID,
            'description' => $this->description_prefix . ($merchant->user_id != null
                    ? $merchant->user->full_name
                    : $merchant->title)
        ];
        $response = $this->sendRequest(self::REQUEST_CHECKOUT_URL, $data);
        if ($response['result'] == self::SUCCESS_STATUS_CODE) {
            return [
                'result' => self::CHECKOUT_SUCCESS,
                'message' => 'درخواست تسویه حساب با موفقیت ثبت شد',
            ];
        } else {
            return [
                'result' => self::CHECKOUT_FAILED,
                'message' => $response['message'],
            ];
        }
    }

    /**
     * authorization zibal platform with api key
     * @return string[]
     */
    private function authorization(): array
    {
        return ["Authorization" => "Bearer " . $this->apiKey];
    }

    /**
     * handle request
     * @param string $url
     * @param array $data
     * @return array
     */
    private function sendRequest(string $url, array $data): array
    {
        return \Http::withHeaders($this->authorization())->post($url, $data)->json();
    }

    /**
     * check user submit self request
     * @param SubMerchants $merchant
     * @return void
     * @throws Exception
     */
    private function checkUserRequestCheckout(SubMerchants $merchant)
    {
        if ($merchant->type === "merchant" or $merchant->type === "subMerchant") {
            if ($merchant->user != auth()->user()) {
                throw new \Exception('شما فقط می‌توانید درخواست تسویه مربوط به خودتان را ارسال کنید');
            }
        }
    }

    /**
     * check merchant balance
     * @throws Exception
     */
    private function checkMerchantBalance(SubMerchants $merchant)
    {
        if ($merchant->balance <= 0) throw new Exception('موجودی حساب سهام‌دار صفر میباشد.');
    }
}
