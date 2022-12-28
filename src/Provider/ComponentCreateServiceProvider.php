<?php

namespace JsCompCreator\Provider;

use JsCompCreator\Command\ComponentCreateCommand;
use Illuminate\Support\ServiceProvider;

class ComponentCreateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ComponentCreateCommand::class
            ]);
        }
    }

    public function register()
    {
        //
    }

}
