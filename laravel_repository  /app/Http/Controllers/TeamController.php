<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class TeamController extends AccountController
{
    public function add(TeamRequest $request)
    {
        $team = Team::createTeam($request, $this->user->id, $this->company->id);

        if (!$team) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        return response()->json([
            'success' => true,
            'teams' => $team,
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function teams()
    {
        $teams = $this->teamRepository->getTeams($this->company->id);

        if (!$teams)  {
            return self::httpBadRequest(self::NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'teams' => $teams
        ], Response::HTTP_OK);
    }

    public function team(Request $request)
    {
        $team = $this->teamRepository->getTeamById($request->id, $this->company->id);

        if (!$team)  {
            return self::httpBadRequest(self::NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'team' => $team
        ], Response::HTTP_OK);
    }

}
