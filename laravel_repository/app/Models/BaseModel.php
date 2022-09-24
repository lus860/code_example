<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    public function __construct()
    {
        parent::__construct();
        $this->dateFormat =  config('date_format.DB_DATE_TIME_FORMAT');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function getFillable()
    {
        return $this->fillable;
    }
}
