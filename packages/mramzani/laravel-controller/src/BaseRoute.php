<?php

namespace Mramzani\LaravelController;

class BaseRoute
{
    public static function make($message = null,$route = null,$message_type = "alertSuccess")
    {
        if ($message_type === "alertDanger"){
            abort($route,$message);
        }
        return redirect()->route($route)->with($message_type,$message);
    }


}
