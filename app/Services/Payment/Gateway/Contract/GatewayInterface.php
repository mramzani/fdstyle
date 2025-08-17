<?php

namespace App\Services\Payment\Gateway\Contract;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;

interface GatewayInterface
{
    const TRANSACTION_FAILED = 'transaction.failed';
    const TRANSACTION_SUCCESS = 'transaction.success';
    const TRANSACTION_APPROVED = 'transaction.approved';

    public function pay(Order $order,string $gateway,int $amount);
    public function verify(Request $request,int $amount);
    public function getName(): string ;
    public function fee(int $amount): int;
}
