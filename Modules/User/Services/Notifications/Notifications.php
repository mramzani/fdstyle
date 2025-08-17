<?php

namespace Modules\User\Services\Notifications;



use Exception;
use Modules\User\Entities\Customer;
use Modules\User\Services\Notifications\Providers\Contracts\Provider;


/**
 * @method sendSms(Customer $customer, array $text)
 * @method sendTelegram(string $text)
 */
class Notifications
{
    /**
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        $providerPath = __NAMESPACE__ . '\Providers\\' . substr($method,4) . 'Provider';

        if (!class_exists($providerPath)) {
            throw new Exception("Class does not Exists");
        }

        $providerInstance = new $providerPath(...$arguments);

        if(!is_subclass_of($providerInstance,Provider::class)){
            throw new Exception("class must implements Modules\User\Services\Notifications\Providers\Contracts");
        }

        $providerInstance->send();
    }

}
