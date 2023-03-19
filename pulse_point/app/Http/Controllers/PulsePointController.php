<?php

namespace App\Http\Controllers;

use App\Http\Requests\PulsePoint\PulsePointRequest;
use App\Models\AuditLogEntry;
use App\Models\PulsePoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PulsePointController extends AccountController
{
    /**
     * @param PulsePointRequest $request
     * @return mixed
     */
    public function storeOrUpdate(PulsePointRequest $request)
    {
        $created_at = Carbon::today('UTC');
        $pulsePoint = $this->pulsePointRepository->getPulsePointByUserIdForToday($this->user->id, $this->company->id, $created_at);

        if ($pulsePoint) {
            $newPulsePoint = PulsePoint::updatePulsePoint($pulsePoint, $request);
            if ($newPulsePoint) {
                $action = __('{teams_and_people_icon} :name updated {pulse_point_icon} pulse point', ['name' => '"' . $this->user->getName("{f} {l}") . '"']);
                AuditLogEntry::saveLog($newPulsePoint, $action, AuditLogEntry::AUDIT_TRAIL_PULSE_POINTS, $this->company->id, null, null, null, null, null, $this->user->id);

                return response()->json([
                    'success' => true,
                    'pulse_point' => $pulsePoint,
                ], Response::HTTP_OK);
            }
        } elseif ($request->feeling || $request->feedback || $request->productivity) {
            $newPulsePoint = PulsePoint::createPulsePoint($request, $this->user->id, $this->company->id);
            if ($newPulsePoint) {
                $action = __('{teams_and_people_icon} :name added {pulse_point_icon} pulse point', ['name' => '"' . $this->user->getName("{f} {l}") . '"']);
                AuditLogEntry::saveLog($newPulsePoint, $action, AuditLogEntry::AUDIT_TRAIL_PULSE_POINTS, $this->company->id, null, null, null, null, null, $this->user->id);

                return response()->json([
                    'success' => true,
                    'pulse_point' => $pulsePoint,
                ], Response::HTTP_CREATED);
            }
        }

        return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPulsePointForUser(Request $request)
    {
        $created_at = $request->created ?? Carbon::today('UTC');
        $pulsePoint = $this->pulsePointRepository->getPulsePointByUserIdForToday($this->user->id, $this->company->id, $created_at);

        return response()->json([
            'success' => true,
            'pulse_point' => $pulsePoint ?? __(self::NO_DATA),
        ], Response::HTTP_OK);
    }

}
