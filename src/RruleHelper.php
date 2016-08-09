<?php

namespace jpmurray\LaravelRrule;

//use Recurr\Rule;
//use Recurr\Transformer\ArrayTransformer;
use Recurr\Frequency;

class RruleHelper
{
    public function hello()
    {
        return Frequency::YEARLY;
    }
}
