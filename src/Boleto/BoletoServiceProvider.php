<?php
namespace Diegomoura\LaravelBoleto\Boleto;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class BoletoServiceProvider extends BaseServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'BoletoHtmlRender');

        $this->publishes([
            __DIR__.'/views'      => resource_path('views/vendor/BoletoHtmlRender'),
        ]);
    }


}
