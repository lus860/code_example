<?php

namespace App\Repository;

use App\Models\IfStatement;
use Illuminate\Support\Facades\Auth;
use AppDateFormat;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    protected $model;
    public $date_format;
    public $user;
    public $company;
    public $dbDateTimeFormat;
    public $dbDateFormat;
    public $dbDateTimeFormatJs;
    public $dbDateFormatJs;
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
        $this->date_format = config('date_format.DB_DATE_TIME_FORMAT');
        $this->dbDateTimeFormat = config('date_format.DB_DATE_TIME_FORMAT');
        $this->dbDateFormat = config('date_format.DB_DATE_FORMAT');
        $this->dbDateTimeFormatJs = config('date_format.DB_DATE_TIME_FORMAT_JS');
        $this->dbDateFormatJs = config('date_format.DB_DATE_FORMAT_JS');
        $this->user = Auth::user();
        $this->company = $this->user->_getCompany();
    }

    abstract protected function getModelClass();

    protected function startCondition()
    {
        return clone $this->model;
    }

    public function getModelPluck($query, $pluck)
    {
        return $query->pluck($pluck)->toArray();
    }

}
