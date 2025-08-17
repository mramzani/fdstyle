<?php

namespace App\Services\Storage;

use App\Services\Storage\Contracts\StorageInterface;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(StorageInterface::class,function (){
            return new SessionStorage('cart');
        });
    }
}
