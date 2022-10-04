<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\CheckinTalkingPoint;
use Illuminate\Http\Response;

class CheckinController extends AccountController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getCheckins(Request $request)
    {
        $checkins = $this->checkinRepository->getCheckins($request, $this->company->id);
        if (!$checkins) {
            return self::httpBadRequest(self::NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'checkins' => $checkins,
            'count_total' => $this->checkinRepository->checkinTotalCount($this->company->id) ?? __(self::NO_DATA),
        ], HTTPStatusCode::OK);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function changeTalkingPointStatus(Request $request, $id, $id_apm_checkins)
    {
        $apmCheckinTalkingPoint = $this->checkinTalkingPointRepository->getCheckinTalkingPointById($id);

        if ($apmCheckinTalkingPoint) {

            if (AccountController::permission($apmCheckinTalkingPoint->checkin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckinTalkingPoint->checkin->created_by, "permission-for-action", Checkin::class);
            }

            if ($request->status) {
                CheckInTalkingPoint::where('id', $id)->update(['status' => CheckInTalkingPoint::DONE]);
            } else {
                CheckInTalkingPoint::where('id', $id)->update(['status' => CheckInTalkingPoint::NOT_DONE]);
            }

            return $this->doneTalkingPoint($id_apm_checkins);
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function changeAllTalkingPointsStatus(Request $request, $id_apm_checkins)
    {
        $apmCheckinTalkingPoints = $this->checkinTalkingPointRepository->getCheckinTalkingPointByIdCheckin($id_apm_checkins);
        if ($apmCheckinTalkingPoints) {
            $created_by = $this->checkinRepository->getCheckinById($id_apm_checkins)->created_by;
            if (AccountController::permission($created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($created_by, "permission-for-action", Checkin::class);
            }

            if ($request->status) {
                CheckInTalkingPoint::where('id_checkins', $id_apm_checkins)->update(['status' => CheckInTalkingPoint::DONE]);
            } else {
                CheckInTalkingPoint::where('id_checkins', $id_apm_checkins)->update(['status' => CheckInTalkingPoint::NOT_DONE]);
            }

            return $this->talkingPointGet($id_apm_checkins);
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $id_apm_checkins
     * @return mixed
     */
    public function doneTalkingPoint($id_apm_checkins)
    {
        $checked_talking_point_ids = $this->checkinTalkingPointRepository->getCheckinTalkingPointIds($id_apm_checkins);
        return response()->json([
            'success' => true,
            'checked_talking_point_ids' => $checked_talking_point_ids ?? __(self::NO_DATA),
        ], HTTPStatusCode::OK);
    }

    /**
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function talkingPointDelete($id, $id_apm_checkins)
    {
        $apmCheckinTalkingPoint = $this->checkinTalkingPointRepository->getCheckinTalkingPointById($id);

        if ($apmCheckinTalkingPoint) {

            if (AccountController::permission($apmCheckinTalkingPoint->checkin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckinTalkingPoint->checkin->created_by, "permission-for-action", Checkin::class);
            }

            if ($apmCheckinTalkingPoint->delete()) {
                return $this->talkingPointGet($id_apm_checkins);
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param CheckinTalkingPointRequest $request
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function talkingPointAdd(CheckinTalkingPointRequest $request, $id_apm_checkins)
    {
        $apmCheckin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);

        if ($apmCheckin) {
            if (AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class);
            }

            if (!empty($request)) {
                $apmCheckinTalkingPoint = CheckInTalkingPoint::createCheckinTalkingPoint($request, $this->user->id, $id_apm_checkins);
                if ($apmCheckinTalkingPoint) {
                    return $this->talkingPointGet($id_apm_checkins);
                }
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param CheckinTalkingPointEditRequest $request
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function talkingPointUpdate(CheckinTalkingPointEditRequest $request, $id, $id_apm_checkins)
    {
        $apmCheckinTalkingPoint = $this->checkinTalkingPointRepository->getCheckinTalkingPointById($id);

        if ($apmCheckinTalkingPoint) {
            if (AccountController::permission($apmCheckinTalkingPoint->checkin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckinTalkingPoint->checkin->created_by, "permission-for-action", Checkin::class);
            }

            if (!empty($request)) {
                $updateCheckInTalkingPoint = CheckInTalkingPoint::updateCheckinTalkingPoint($id, $request);
                if ($updateCheckInTalkingPoint) {
                    return $this->talkingPointGet($id_apm_checkins);
                }
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $id_apm_checkins
     * @return mixed
     */
    public function talkingPointGet($id_apm_checkins)
    {
        $talking_points = $this->checkinTalkingPointRepository->getCheckinTalkingPointPaginate($id_apm_checkins);
        return response()->json([
            'success' => true,
            'talking_points' => $talking_points ?? __(self::NO_DATA),
        ], HTTPStatusCode::OK);
    }

    /**
     * @param CheckinPlanRequest $request
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function planAdd(CheckinPlanRequest $request, $id_apm_checkins)
    {
        $apmCheckin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);

        if ($apmCheckin) {
            if (AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class);
            }

            if (!empty($request)) {
                $apmCheckinPlan = CheckinPlan::createCheckinPlan($request, $this->user->id, $id_apm_checkins);
                if ($apmCheckinPlan) {
                    return $this->plansGet($id_apm_checkins);
                }
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param CheckinPlanEditRequest $request
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function planUpdate(CheckinPlanEditRequest $request, $id, $id_apm_checkins)
    {
        $apmCheckin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);

        if ($apmCheckin) {
            if (AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class);
            }

            if (!empty($request)) {
                $apmCheckinPlan = $this->checkinPlanRepository->getCheckinPlanById($id);
                $apmCheckinPlan = CheckinPlan::updateCheckinPlan($request, $apmCheckinPlan, $id_apm_checkins);
                if ($apmCheckinPlan) {
                    return $this->plansGet($id_apm_checkins);
                }
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function planDelete($id, $id_apm_checkins)
    {
        $apmCheckinPlan = $this->checkinPlanRepository->getCheckinPlanById($id);

        if ($apmCheckinPlan) {
            if (AccountController::permission($apmCheckinPlan->checkin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckinPlan->checkin->created_by, "permission-for-action", Checkin::class);
            }

            if ($apmCheckinPlan->delete()) {
                return $this->plansGet($id_apm_checkins);
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $id_apm_checkins
     * @return mixed
     */
    public function plansGet($id_apm_checkins)
    {
        $plans = $this->checkinPlanRepository->getCheckinPlanPaginate($id_apm_checkins);
        foreach ($plans as $plan) {
            if ($plan->user) {
                $plan->user->getName("{f} {l}");
                $plan->user->getAvatar();
            }

            if ($plan->owner) {
                $plan->owner->getName("{f} {l}");
                $plan->owner->getAvatar();
            }

        }
        return response()->json([
            'success' => true,
            'plans' => $plans ?? __(self::NO_DATA),
        ], HTTPStatusCode::OK);
    }

    /**
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function noteDelete($id, $id_apm_checkins)
    {
        $apmCheckinNote = $this->checkinNoteRepository->getCheckinNoteById($id);

        if ($apmCheckinNote) {

            if (AccountController::permission($apmCheckinNote->checkin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckinNote->checkin->created_by, "permission-for-action", Checkin::class);
            }

            if ($apmCheckinNote->delete()) {
                return $this->notesGet($id_apm_checkins);
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param CheckinNoteEditRequest $request
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function noteUpdate(CheckinNoteEditRequest $request, $id, $id_apm_checkins)
    {
        $apmCheckin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);

        if ($apmCheckin) {
            if (AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class);
            }

            if (!empty($request)) {
                $apmCheckinNote = $this->checkinNoteRepository->getCheckinNoteById($id);
                $apmCheckinNote = CheckInNote::updateCheckinNote($request, $apmCheckinNote, $id_apm_checkins);
                if ($apmCheckinNote) {
                    return $this->notesGet($id_apm_checkins);
                }
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param CheckinNoteRequest $request
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function noteAdd(CheckinNoteRequest $request, $id_apm_checkins)
    {
        $apmCheckin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);

        if ($apmCheckin) {
            if (AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class)) {
                return AccountController::permission($apmCheckin->created_by, "permission-for-action", Checkin::class);
            }

            if (!empty($request)) {
                $apmCheckinNote = CheckInNote::createCheckinNote($request, $this->user->id, $id_apm_checkins);
                if ($apmCheckinNote) {
                    return $this->notesGet($id_apm_checkins);
                }
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $id_apm_checkins
     * @return mixed
     */
    public function notesGet($id_apm_checkins)
    {
        $userNotesIds = $this->checkinNoteRepository->getCheckinNotePluck($id_apm_checkins, $this->user->id);
        $notes = $this->checkinNoteRepository->getCheckinNotePaginate($id_apm_checkins, $userNotesIds);

        foreach ($notes as $note) {
            if ($note->user) {
                $note->user->getName("{f} {l}");
                $note->user->getAvatar();
            }

            if ($note->owner) {
                $note->owner->getName("{f} {l}");
                $note->owner->getAvatar();
            }

        }
        return response()->json([
            'success' => true,
            'notes' => $notes ?? __(self::NO_DATA),
        ], HTTPStatusCode::OK);
    }

}
