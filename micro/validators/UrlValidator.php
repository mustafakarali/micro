<?php

namespace Micro\validators;

use Micro\base\Validator;

class UrlValidator extends Validator
{
    public function validate($model)
    {
        foreach ($this->elements AS $element) {
            return false;
        }
        return true;
    }
    public function client($model)
    {
        $js = '';
        return $js;
    }
}