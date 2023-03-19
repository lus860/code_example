<?php

namespace App\Http\Controllers;

use App\Repository\BaseRepository;
use App\Repository\FeedRepository;
use Carbon\Carbon;
use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Repository\PulsePointRepository;

class AccountController extends Controller
{
    const PERMISSION_DENIED = 'permission denied';
    const NOT_FOUND = 'not found';
    const SOMETHING_WENT_WRONG = 'something went wrong';
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    protected $company;
    protected $user;
    protected $dateFormat;
    protected $dbDateTimeFormat;
    protected $dbDateFormat;
    protected $dbDateTimeFormatJs;
    protected $dbDateFormatJs;
    protected $monday;
    protected $pulsePointRepository;

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
            $this->pulsePointRepository = new PulsePointRepository();
            return $next($request);
        });

        $this->dbDateTimeFormat = config('date_format.DB_DATE_TIME_FORMAT');
        $this->dbDateFormat = config('date_format.DB_DATE_FORMAT');
        $this->dbDateTimeFormatJs = config('date_format.DB_DATE_TIME_FORMAT_JS');
        $this->dbDateFormatJs = config('date_format.DB_DATE_FORMAT_JS');
    }

    /**
     * @param $query
     * @param $permission_name
     * @param $model
     * @param null $error_message
     * @param false $forRepository
     * @return array
     */
    public static function permission($query, $permission_name, $model, $error_message = null, $forRepository = false)
    {
        if (!$error_message) {
            $error_message = self::PERMISSION_DENIED;
        }
        $response = Gate::inspect($permission_name, [$model, $query]);
        if ($response->denied()) {
            if (!$forRepository) {
                return self::httpBadRequest($error_message);
            } else {
                return [
                    'success_for_policy' => false,
                    'error_message_for_policy' => $error_message
                ];
            }
        }
    }

    /**
     * @param $error_message
     * @param $status
     * @return mixed
     */
    public static function httpBadRequest($error_message, $status = Response::HTTP_BAD_REQUEST)
    {
        if ($error_message == self::PERMISSION_DENIED) {
            return response()->json([
                'success' => false,
                'error_code' => Response::HTTP_MULTIPLE_CHOICES,
                'error_message' => __($error_message)
            ], Response::HTTP_MULTIPLE_CHOICES);
        } else {
            return response()->json([
                'success' => false,
                'error_code' => $status,
                'error_message' => __($error_message)
            ], $status);
        }
    }

}
