<?php

namespace Mramzani\LaravelController;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    protected $default = ["id"];

    protected $hidden = ["created_at", "updated_at"];

    protected $guarded = [];

    protected $appends = [];

    public static function getDefaultFields()
    {
        return (new static)->default;
    }




/*    public static function relationExists($relation)
    {
        return method_exists(new static(), $relation);
    }
    public static function getAppendFields()
    {
        return (new static)->appends;
    }*/


}
