<?php

namespace App\Services\AppDateFormat;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AppDateFormat
{
    public $dateFormat = "d-m-Y";
    public $DB_DATE_TIME_FORMAT = "Y-m-d H:i:s";
    public $DB_DATE_FORMAT = "Y-m-d";

    /**
     * Build the message.
     *
     * @return $this
     */

    public function __construct()
    {
        $this->DB_DATE_FORMAT = config('date_format.DB_DATE_FORMAT');
        $this->DB_DATE_TIME_FORMAT = config('date_format.DB_DATE_TIME_FORMAT');
    }

    public function getDateFormat($company = null)
    {
        $company = $company ?? self::getCompany();

        if ($company && $company->date_format && in_array($company->date_format, ["d/m/Y", "Y/m/d", "m/d/Y", "d-m-Y", "Y-m-d"])) {
            return $company->date_format;
        } else {
            return $this->dateFormat;
        }
    }

    public static function getCompany()
    {
        $company = Auth::user() ? Auth::user()->company()->with(['language', 'time_zone', 'current_pricing_plan', 'pending_pricing_plan'])->select(['id', 'id_current_pricing_plan', 'id_pending_pricing_plan', 'id_time_zone', 'id_language', 'date_format', 'session_lifetime', 'session_lifetime_forever', 'file_path', 'file_size', 'file_type', 'file_name', 'file_is_dark', 'lms_file'])->first() : null;

        if (!$company->session_lifetime) {
            $company->session_lifetime = config('session.lifetime') ? config('session.lifetime') : null;
        }

        return $company;
    }

    public function getMonday()
    {
        return Carbon::now()->startOfWeek(Carbon::MONDAY)->format($this->DB_DATE_TIME_FORMAT);
    }

    public function getFriday()
    {
        return Carbon::now()->endOfWeek(Carbon::FRIDAY)->format($this->DB_DATE_TIME_FORMAT);
    }

    public function getNextMonday()
    {
        return Carbon::parse(self::getMonday())->addWeeks()->format($this->DB_DATE_TIME_FORMAT);
    }

    public function getNextFriday()
    {
        return Carbon::parse(self::getFriday())->addWeeks()->format($this->DB_DATE_TIME_FORMAT);
    }
}
