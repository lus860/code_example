<?php

namespace App\Http\Controllers;

use App\Http\Requests\Goals\GoalRequest;
use Illuminate\Support\Facades\Notification;

class  GoalController extends AccountController
{
    /**
     * @param GoalRequest $request
     * @return JsonResponse
     */
    public function store(GoalRequest $request)
    {
        $goal = Goal::createGoal($request, $this->company->id);

        if ($goal) {
            $user = $this->companyFirstUser;
            $url = $this->company->URL_APM();
            $name = $this->company->name;
            $logo_path = $this->company->logo_path;
            $webhook_url = env('WEBHOOK_URL');

            Notification::send($user, new GoalCreatedTeamsNotifications($logo_path, $url, $name, $webhook_url, $goal));
            return response()->json(['success' => true, 'goal' => $goal], Response::HTTP_CREATED);

        }

        return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
    }

}
