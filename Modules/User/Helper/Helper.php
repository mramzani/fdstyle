<?php

namespace Modules\User\Helper;

use Modules\User\Entities\Customer;

class Helper
{
    public static function getCustomer(): ?Customer
    {
        $customer = auth('customer')->user();

        if ($customer){
            return $customer->load('detail');
        }
        return null;
    }
}
