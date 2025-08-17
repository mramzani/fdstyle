<?php

namespace Modules\Product\Exceptions;

class InvalidVariantException extends \Exception
{

    public function report()
    {
        \Log::warning('Invalid Variant');
    }
}
