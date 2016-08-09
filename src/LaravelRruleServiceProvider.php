<?php

namespace jpmurray\LaravelRrule;

use Illuminate\Support\ServiceProvider;
use jpmurray\LaravelRrule\LaravelRrule;

class LaravelRruleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //require_once __DIR__."/../vendor/autoload.php";

        $this->app->bind('laravel-rrule', function() {
            return new RruleHelper;
        });
    }
}