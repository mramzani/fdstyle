<?php

namespace Modules\Product\Exceptions;

class CantChangeCategoryException extends \Exception
{
    public function report()
    {
        \Log::warning('به دلیل وجود تنوع محصول، امکان تغییر دسته‌بندی برای این محصول وجود ندارد.');
    }
}
