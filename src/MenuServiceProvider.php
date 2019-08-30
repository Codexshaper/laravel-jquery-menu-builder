<?php

namespace CodexShaper\Menu;

use CodexShaper\Menu\Builder;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    { 
        $this->publishes([
            __DIR__.'/../config/menu.php' => config_path('menu.php'),
        ],'menu');

        $this->loadRoutesFrom(__DIR__.'/../routes/menu.php');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'menu');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('menu', function(){
            return new Builder(); 
        });

         $this->loadHelpers();
        
        // $this->mergeConfigFrom(
        //     __DIR__.'/config/woocommerce.php', 'woocommerce'
        // );

        // $this->app->singleton('WooCommerceApi', function(){
        //     return new WooCommerceApi(); 
        // });
        // $this->app->alias('Codexshaper\Woocommerce\WooCommerceApi', 'WoocommerceApi');
    }

    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}
