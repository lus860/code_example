<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Repository\Api\MetricRepository;
use AppDateFormat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    const PERMISSION_DENIED = 'permission denied';
    const NOT_FOUND = 'not found';
    const NO_DATA = 'no data';
    const REQUESTED_ITEM_NO_DATA = 'Requested item(s) not found';

    const ACTIVE = 1;
    const NOT_ACTIVE = 0;
    const SOMETHING_WENT_WRONG = 'something went wrong';
    const INVALID_ID = 'invalid id';
    const ALREADY_SUBMITTED = 'already submitted';
    const INACTIVE_USER = 'Inactive user';

    protected $request;
    protected $company;
    protected $metricRepository;
    protected $dbDateTimeFormat;
    protected $dbDateFormat;
    protected $dbDateTimeFormatJs;
    protected $dbDateFormatJs;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->middleware('api_token_auth');
        $this->company = Company::where('api_token', $request->api_token)->first();
        $this->metricRepository = new MetricRepository();
        $this->dbDateTimeFormat = config('date_format.DB_DATE_TIME_FORMAT');
        $this->dbDateFormat = config('date_format.DB_DATE_FORMAT');
        $this->dbDateTimeFormatJs = config('date_format.DB_DATE_TIME_FORMAT_JS');
        $this->dbDateFormatJs = config('date_format.DB_DATE_FORMAT_JS');
    }

    public static function httpBadRequest($message = self::REQUESTED_ITEM_NO_DATA, $status = 4041)
    {
        return response()->json([
            'status' => 'fail',
            'code' => $status,
            'message' => __($message)
        ], Response::HTTP_BAD_REQUEST);
    }

}
