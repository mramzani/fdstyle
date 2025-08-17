<?php

namespace Mramzani\LaravelController\Providers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/base.php', 'baseConfig');
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'base');
    }
}
