<?php

namespace jpmurray\LaravelRrule;

use Illuminate\Support\ServiceProvider;
use jpmurray\LaravelRrule\LaravelRrule;

class LaravelRruleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('laravel-rrule', function() {
            return new RruleHelper;
        });
    }
}