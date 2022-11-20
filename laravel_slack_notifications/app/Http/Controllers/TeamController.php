<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Notifications\TeamsAndPeople\Slack\TeamsAndPeopleTeamCreatedSlackNotifications;
use App\Notifications\TeamsAndPeople\TeamsAndPeopleTeamCRUDNotifications;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class TeamController extends AccountController
{
    /**
     * @param TeamRequest $request
     * @return JsonResponse
     */
    public function add(TeamRequest $request)
    {
        $team = Team::createTeam($request, $this->user->id, $this->company->id);

        if (!$team) {
            $team->owner->notify(new TeamsAndPeopleTeamCRUDNotifications($team, 'add'));
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        if ($this->company->slack_connected && $this->company->slack_connected_id) {
                $url = $this->company->URL_APM();
                $name = $this->company->name;
                $channel = 'admin';
                Notification::send($team->owner, new TeamsAndPeopleTeamCreatedSlackNotifications($url, $name, $channel, $team));
        }

        return response()->json([
            'success' => true,
            'teams' => $this->teamRepository->getTeams($this->company->id),
        ], Response::HTTP_OK);
    }

}
