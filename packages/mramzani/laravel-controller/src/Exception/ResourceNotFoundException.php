<?php

namespace Mramzani\LaravelController\Exception;

class ResourceNotFoundException extends BaseException
{
    protected $statusCode = 404;

    protected $code = ErrorCodes::RESOURCE_NOT_FOUND_EXCEPTION;

    protected $message = "Requested resource not found";
}
