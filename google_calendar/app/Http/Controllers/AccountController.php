<?php

namespace App\Http\Controllers;

use App\Repository\FeedRepository;
use Carbon\Carbon;
use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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
    protected $feedRepository;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->user->getName();
            $this->user->getRole();
            $this->user->getAvatar();
            return $next($request);
        });

        $this->dbDateTimeFormat = config('date_format.DB_DATE_TIME_FORMAT');
        $this->dbDateFormat = config('date_format.DB_DATE_FORMAT');
        $this->dbDateTimeFormatJs = config('date_format.DB_DATE_TIME_FORMAT_JS');
        $this->dbDateFormatJs = config('date_format.DB_DATE_FORMAT_JS');
    }

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

    public function getGoogleClientWithUncheckedAccessToken($userId)
    {
        $client = new Google_Client();
        $client->setApplicationName(__('Google Calendar for ' . $this->company->URL_DOMAIN()));
        $client->setScopes(\Google\Service\Calendar::CALENDAR . ' email');
        //$client->setAuthConfig(storage_path('app/public/companies/' . $this->company->id . '/google_calendar/google_credentials.json'));
        $client->setAuthConfig(base_path('google_credentials.json'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        // Using "consent" ensures that your application always receives a refresh token.
        // If you are not using offline access, you can omit this.
        $client->setPrompt('consent');
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $redirectUrl = $this->company->URL_APM(). '/check-ins';
        //$redirectUrl = 'https://eleapdemo.2leapappdev.com/check-ins';
        $client->setRedirectUri($redirectUrl);

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = FileController::getStorageGoogleTokenPath($userId, $this->company->id);

        if (Storage::exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents(storage_path('app/' . $tokenPath)), true);
            $client->setAccessToken($accessToken);
        }
        return $client;
    }

}
