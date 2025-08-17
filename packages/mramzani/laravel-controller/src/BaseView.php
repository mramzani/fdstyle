<?php

namespace Mramzani\LaravelController;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseView
{
    public static function make($message = null, $data = null, $view = null)
    {


        $response = [];

        if (!empty($message)) {
            $response["message"] = $message;
        }

        if ($data instanceof Collection or $data instanceof Model or $data instanceof LengthAwarePaginator) {
            $response["data"] = $data;
        }

        return view($view, ['data' => $response['data'] ?? null, 'message' => $response['message'] ?? '']);

    }

}
