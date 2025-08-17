<?php

namespace App\Services\Payment\Gateway;

use App\Services\Cost\Facades\Cost;
use App\Services\Payment\Gateway\Contract\GatewayInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Order\Entities\Order;

class Zibal implements GatewayInterface
{
    private string $merchantID;
    private string $callbackUrl;
    private string $requestPaymentUrl;
    private string $startPaymentUrl;
    private string $verifyPaymentUrl;

    private const SUCCESS_STATUS = 100;
    private const previously_verified_status = 201;

    public function __construct()
    {
        $this->merchantID = config('front.gateway.zibal.merchantID');
        $this->requestPaymentUrl = config('front.gateway.zibal.requestPaymentUrl');;
        $this->callbackUrl = route('front.payment.verify', $this->getName());
        $this->startPaymentUrl = config('front.gateway.zibal.startPaymentUrl');;
        $this->verifyPaymentUrl = config('front.gateway.zibal.verifyPaymentUrl');;
    }

    /**
     * @param Order $order
     * @param string $gateway
     * @param int $amount
     * @return RedirectResponse
     */
    public function pay(Order $order, string $gateway, int $amount): RedirectResponse
    {
        $response = Http::post($this->requestPaymentUrl, [
            'merchant' => $this->merchantID,
            'amount' => $order->total * 10,
            'callbackUrl' => $this->callbackUrl,
            'orderId' => $order->id,
            'description' => ' پرداخت سفارش ' . $order->invoice_number,
            'mobile' => $order->customer->mobile,
        ])->json();

        if ($response['result'] === self::SUCCESS_STATUS) {
            return redirect()->away($this->startPaymentUrl . $response['trackId']);
        } else {
            return redirect()->route('front.home');
        }
    }

    /**
     * @param Request $request
     * @param int $amount
     * @return array
     */
    public function verify(Request $request, int $amount): array
    {
        $response = Http::post($this->verifyPaymentUrl, [
            'merchant' => $this->merchantID,
            'trackId' => $request->trackId,
        ])->json();

        if ($response['result'] === self::SUCCESS_STATUS and $response['amount'] == $amount * 10) {
            return $this->transactionSuccessResponse($request->orderId, $response);
        } elseif ($response['result'] === self::previously_verified_status) {
            return $this->transactionFailedResponse($request->orderId, self::TRANSACTION_APPROVED);
        } else {
            return $this->transactionFailedResponse($request->orderId, self::TRANSACTION_FAILED);
        }
    }

    public function getName(): string
    {
        return 'zibal';
    }


    public function fee(int $amount): int
    {
        $fee = ($amount * config('front.gateway.fee'));

        if ($fee >= config('front.gateway.max_fee')) {
            $fee = config('front.gateway.max_fee');
        }

        return $fee;
    }

    /**
     * @param $orderId
     * @param array $result
     * @return array
     */
    private function transactionSuccessResponse($orderId, array $result): array
    {
        return [
            'status' => self::TRANSACTION_SUCCESS,
            'order_id' => (int)$orderId,
            'refNum' => $result['refNumber'],
            'gateway' => $this->getName(),
            'description' => $result['description']
        ];
    }

    /**
     * @param $orderId
     * @param $status
     * @return array
     */
    private function transactionFailedResponse($orderId, $status): array
    {
        return [
            'status' => $status,
            'order_id' => (int)$orderId,
        ];
    }

}
