<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PulsePointController extends AccountController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function productivityEmotionSchedule(Request $request)
    {
        $start = $request->start ?? $this->mondayUTC;
        $end = $request->end ?? $this->sundayUTC;
        $dateStart = Carbon::parse($start);
        $diffInDays = Carbon::parse($start)->diffInDays($end);

        for ($i = 0; $i <= $diffInDays; $i++) {
            $feeling = $this->pulsePointRepository->emotionSchedule(Carbon::parse($dateStart)->addDays($i), $this->company->id);
            $productivity = $this->pulsePointRepository->productivitySchedule(Carbon::parse($dateStart)->addDays($i), $this->company->id);
            $pulsePointForWeek = [
                'x' => Carbon::parse($start)->addDays($i),
                'value' => round($productivity, 2),
                'type' => 'Productivity',
            ];
            $schedule[] = $pulsePointForWeek;
            $pulsePointForWeek = [
                'x' => Carbon::parse($start)->addDays($i),
                'value' => round($feeling, 2),
                'type' => 'Feeling',
            ];
            $schedule[] = $pulsePointForWeek;
        }

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
        ], Response::HTTP_OK);
    }

}
