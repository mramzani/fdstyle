<?php

namespace Mramzani\LaravelController\Exception;

class ViewNotFoundException extends BaseException
{


    protected $statusCode = 404;

    protected $code = ErrorCodes::VIEW_NOT_FOUND_EXCEPTION;

    protected $message = "View not found... Please Create View";
}
