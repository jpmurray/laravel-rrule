<?php

namespace jpmurray\LaravelRrule;

use Illuminate\Support\Facades\Facade;

class LaravelRruleFacade extends Facade
{
    protected static function getFacadeAccessor() { 
        return 'laravel-rrule';
    }
}