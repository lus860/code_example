<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repository\PulsePointRepository;

class AccountController extends Controller
{
    protected $pulsePointRepository;
    public $DB_DATE_TIME_FORMAT = "Y-m-d H:i:s";
    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->user->getName();
            $this->user->getRole();
            $this->user->getAvatar();
            $this->mondayUTC = $this->getMondayUTC();
            $this->sundayUTC = $this->getSundayUTC();
            $this->pulsePointRepository = new PulsePointRepository();
            return $next($request);
        });

    }

    public function getSundayUTC()
    {
        $sunday = Carbon::now('UTC')->endOfWeek(Carbon::SUNDAY);
        return $sunday->format($this->DB_DATE_TIME_FORMAT);
    }

    public function getMondayUTC()
    {
        $monday = Carbon::now('UTC')->startOfWeek(Carbon::MONDAY);
        return $monday->format($this->DB_DATE_TIME_FORMAT);
    }

}
