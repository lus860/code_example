<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Notifications\TeamsAndPeople\TeamsAndPeopleTeamCRUDNotifications;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

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

        return response()->json([
            'success' => true,
            'teams' => $this->teamRepository->getTeams($this->company->id),
        ], Response::HTTP_OK);
    }

}
