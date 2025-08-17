<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //!$this->app->environment('build')

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        Paginator::useBootstrap();

        Collection::macro('flattenTree', function() {
            $items = [];
            foreach ($this->items as $node) {
                $items = array_merge($items, $this->flattenNode($node));
            }

            return new static($items);
        });

        Collection::macro('flattenNode', function($node) {
            $items = [];
            $items[] = $node;
            foreach ($node->children as $childNode) {
                $items = array_merge($items, $this->flattenNode($childNode));
            }
            $node->unsetRelation('children');
            return $items;
        });
    }
}
