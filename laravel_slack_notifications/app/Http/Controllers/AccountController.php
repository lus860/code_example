<?php

namespace App\Http\Controllers;

use App\Repository\CheckinNoteRepository;
use App\Repository\CheckinPlanRepository;
use App\Repository\CheckinRepository;
use App\Repository\CheckinTalkingPointRepository;
use App\Repository\UserRepository;
use App\Repository\TeamRepository;
use App\Repository\WeeklyTaskRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use AppDateFormat;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    const PERMISSION_DENIED = 'permission denied';
    const NOT_FOUND = 'not found';
    const NO_DATA = 'no data';
    const SOMETHING_WENT_WRONG = 'something went wrong';
    const INVALID_ID = 'invalid id';
    const ALREADY_SUBMITTED = 'already submitted';

    protected $userRepository;
    protected $teamRepository;
    public $user;
    public $company;
    protected $dateFormat;
    protected $monday;
    protected $friday;
    protected $nextMonday;
    protected $nextFriday;
    protected $weeklyTaskRepository;
    protected $checkinRepository;
    protected $checkinTalkingPointRepository;
    protected $checkinPlanRepository;
    protected $checkinNoteRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->teamRepository = new TeamRepository();
        $this->user = Auth::user();
        $this->company = $this->user->company;
    }

    /**
     * @param $error_message
     * @param $status
     * @return \Illuminate\Http\JsonResponse
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
