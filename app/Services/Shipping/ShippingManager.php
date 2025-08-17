<?php

namespace App\Services\Shipping;

use App\Services\Cart\Facades\Cart;
use Illuminate\Support\Facades\Http;
use Modules\Dashboard\Helper\Setting\ShippingSetting;
use Modules\Front\Entities\Address;
use phpDocumentor\Reflection\Types\This;
use function Ybazli\Faker\string;

class ShippingManager
{

    public function calculateShippingPrice(): int
    {

        if (!$this->CustomerHasDefaultAddress()){
            return 0;
        }

        return resolve(ShippingSetting::class)->shipping_cost;

        //if ($fixedPrice){
        //return $fixedPrice;
        //}
        //return  $this->calculatePrice($address);

    }

    private function CustomerHasDefaultAddress():bool
    {
       /* if ((request()->has('fingerprint') and request()->get('fingerprint')['path'] == "cart") or request()->path() == "cart"){
            return 0;
        }*/
        if (auth('customer')->check()){
            return auth('customer')->user()->addresses()->get()->contains('is_default',true);
        }
        return false;
    }

    /*private function getDefaultAddress()
    {
        return auth('customer')->user()->addresses()->where('is_default',true)->firstOrFail();
    }*/

   /* private function calculatePrice(Address $address = null)
    {
        if (is_null($address)){
            $address = $this->getDefaultAddress();
        }

        $sendType = 'otherprovince';
        if ($address->city->province->id == 21) {
            $sendType = 'inprovince';
        }
        $toProvince = $address->city->province->id <= 9 ? "IR-0" . $address->city->province->id : "IR-" . $address->city->province->id;
        $toCity = $address->city->name_en;
        $weight = Cart::weight();
        if ($weight == 0 ) return config('front.shipping_cost');
        if ($weight > 25000 ) return config('front.shipping_cost_large_weight');
        $type = "sefareshi";
        if ($weight > 5000) {
            $type = "pishtaz";
        }

        $url = 'https://core.jibres.ir/r10/irpost?weight=' . $weight . '&type=' . $type . '&from_province=IR-21&from_city=sari&send_type=' . $sendType . '&to_province=' . $toProvince . '&to_city=' . $toCity;
        $rate = 50;
        try {
            $response = Http::get($url)->json();
            if ($response['ok']) {
                $price = $response['result']['price'];
                $rate = ceil(($price / 10) * $rate / 100);
                $price = ($price / 10) + $rate;
                return (int) $this->roundPrice($price);
            } else {
                throw new \Exception('خطا');
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }

    private function roundPrice(int $amount)
    {
        if (fmod($amount,1000) < 500) {
            $newPrice = $amount - fmod($amount,1000) ;
        } else {
            $newPrice = $amount + (1000 - fmod($amount,1000));
        }
        return $newPrice;
    }*/
}
