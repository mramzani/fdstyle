<?php

namespace Mramzani\LaravelController\Exception;


class BaseException extends \Exception
{
    public static function ObjectNotFountException($message): self
    {
        return new BaseException($message,404);
    }

    public static function ViewNotFoundException($message): self
    {
        return new BaseException($message,404);
    }
}
