<?php

namespace App\Http\Controllers;

use App\Models\WeeklyTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeeklyStatusController extends AccountController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAccomplishments(Request $request)
    {
        $start = $request->start ?? $this->monday;
        $end = $request->end ?? $this->friday;

        return response()->json([
            'success' => true,
            'tasks' => $this->weeklyTaskRepository->getWeeklyItem($start, $end, WeeklyTask::ACCOMPLISHMENT, $this->company->id, $this->user->id)
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(Request $request)
    {
        $start = $request->start ?? $this->monday;
        $end = $request->end ?? $this->friday;

        return response()->json([
            'success' => true,
            'tasks' => $this->weeklyTaskRepository->getWeeklyItem($start, $end, WeeklyTask::PLAN, $this->company->id, $this->user->id)
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChallenges(Request $request)
    {
        $start = $request->start ?? $this->monday;
        $end = $request->end ?? $this->friday;

        return response()->json([
            'success' => true,
            'tasks' => $this->weeklyTaskRepository->getWeeklyItem($start, WeeklyTask::CHALLENGE, $this->company->id, $this->user->id)
        ], Response::HTTP_OK);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComingUp(Request $request)
    {
        $start = $request->start ?? $this->monday;

        return response()->json([
            'success' => true,
             'tasks' => $this->weeklyTaskRepository->getComingUp($start, $this->company->id, $this->user->id)
        ], Response::HTTP_OK);
    }

}
