<?php

namespace Modules\Product\Exceptions;

class InvalidAttributeException extends \Exception
{
    public function report()
    {
        \Log::warning('Invalid Attribute');
    }
}
