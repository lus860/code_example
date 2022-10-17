<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\CheckinNote;
use App\Models\CheckinTalkingPoint;
use App\Notifications\CheckIn\CheckInNoteNotifications;
use App\Notifications\CheckIn\CheckInPlansNotifications;
use App\Notifications\CheckIn\CheckInTaggingNotifications;
use App\Notifications\CheckIn\CheckInTalkingPointNotifications;
use Illuminate\Http\Response;

class CheckinController extends AccountController
{
    /**
     * @param $id
     * @param $id_apm_checkins
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function talkingPointDelete($id, $id_apm_checkins)
    {
        $apmCheckinTalkingPoint = $this->checkinTalkingPointRepository->getCheckinTalkingPointById($id);

        if ($apmCheckinTalkingPoint) {
            if ($apmCheckinTalkingPoint->delete()) {
                $checkin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);
                $checkin_users = $this->getCheckinAssignees($checkin);
                foreach ($checkin_users as $checkin_user) {
                    $checkin_user->notify(new CheckInTalkingPointNotifications($checkin, $this->dateFormat, $apmCheckinTalkingPoint->name, 'delete'));
                }
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
            if (!empty($request)) {
                $apmCheckinTalkingPoint = CheckInTalkingPoint::createCheckinTalkingPoint($request, $this->user->id, $id_apm_checkins);
                if ($apmCheckinTalkingPoint) {
                    $checkin_users = $this->getCheckinAssignees($apmCheckin);
                    foreach ($checkin_users as $checkin_user) {
                        $checkin_user->notify(new CheckInTalkingPointNotifications($apmCheckin, $this->dateFormat, $apmCheckinTalkingPoint->name, 'add'));
                    }
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
            if (!empty($request)) {
                $updateCheckInTalkingPoint = CheckInTalkingPoint::updateCheckinTalkingPoint($id, $request);
                if ($updateCheckInTalkingPoint) {
                    $checkin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);

                    $checkin_users = $this->getCheckinAssignees($checkin);
                    foreach ($checkin_users as $checkin_user) {
                        $checkin_user->notify(new CheckInTalkingPointNotifications($checkin, $this->dateFormat, $apmCheckinTalkingPoint->name, 'edit'));
                    }
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
            if (!empty($request)) {
                $apmCheckinPlan = CheckinPlan::createCheckinPlan($request, $this->user->id, $id_apm_checkins);

                if ($apmCheckinPlan) {
                    $checkin_users = $this->getCheckinAssignees($apmCheckin);
                    foreach ($checkin_users as $checkin_user) {
                        $checkin_user->notify(new CheckInPlansNotifications($apmCheckin, $this->dateFormat, $apmCheckinPlan, 'add'));
                    }
                }

                return $this->plansGet($id_apm_checkins);
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
            if (!empty($request)) {
                $apmCheckinPlan = $this->checkinPlanRepository->getCheckinPlanById($id);
                $apmCheckinPlan = CheckinPlan::updateCheckinPlan($request, $apmCheckinPlan, $id_apm_checkins);
                if ($apmCheckinPlan) {

                    $checkin_users = $this->getCheckinAssignees($apmCheckin);
                    foreach ($checkin_users as $checkin_user) {
                        $checkin_user->notify(new CheckInPlansNotifications($apmCheckin, $this->dateFormat, $apmCheckinPlan, 'edit'));
                    }

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
            if ($apmCheckinPlan->delete()) {
                $checkin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);
                $checkin_users = $this->getCheckinAssignees($checkin);
                foreach ($checkin_users as $checkin_user) {
                    $checkin_user->notify(new CheckInPlansNotifications($checkin, $this->dateFormat, $apmCheckinPlan, 'delete'));
                }
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
            if ($apmCheckinNote->delete()) {
                $checkin = $this->checkinRepository->getCheckinByIdWithOut($id_apm_checkins);
                $checkin_users = $this->getCheckinAssignees($checkin);
                if ($apmCheckinNote->status == CheckInNote::PRIVATE) {
                    $this->user->notify(new CheckInNoteNotifications($checkin, $this->dateFormat, $apmCheckinNote, 'delete'));
                } else {
                    foreach ($checkin_users as $checkin_user) {
                        $checkin_user->notify(new CheckInNoteNotifications($checkin, $this->dateFormat, $apmCheckinNote, 'delete'));
                    }
                }
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
            if (!empty($request)) {
                $apmCheckinNote = $this->checkinNoteRepository->getCheckinNoteById($id);
                $apmCheckinNote = CheckInNote::updateCheckinNote($request, $apmCheckinNote, $id_apm_checkins);
                if ($apmCheckinNote) {

                    $checkin_users = $this->getCheckinAssignees($apmCheckin);

                    if ($apmCheckinNote->status == CheckInNote::PRIVATE) {
                        $this->user->notify(new CheckInNoteNotifications($apmCheckin, $this->dateFormat, $apmCheckinNote, 'edit'));
                    } else {
                        foreach ($checkin_users as $checkin_user) {
                            $checkin_user->notify(new CheckInNoteNotifications($apmCheckin, $this->dateFormat, $apmCheckinNote, 'edit'));
                        }
                    }
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
            if (!empty($request)) {
                $apmCheckinNote = CheckInNote::createCheckinNote($request, $this->user->id, $id_apm_checkins);
                if ($apmCheckinNote) {

                    $checkin_users = $this->getCheckinAssignees($apmCheckin);

                    if ($apmCheckinNote->status == CheckInNote::PRIVATE) {
                        $this->user->notify(new CheckInNoteNotifications($apmCheckin, $this->dateFormat, $apmCheckinNote, 'add'));
                    } else {
                        foreach ($checkin_users as $checkin_user) {
                            $checkin_user->notify(new CheckInNoteNotifications($apmCheckin, $this->dateFormat, $apmCheckinNote, 'add'));
                        }
                    }
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

    /**
     * @param $checkin
     * @return mixed
     */
    public function getCheckinAssignees($checkin, $forTagging = false)
    {
        $allUsersIds = [];

        if ($checkin->is_company == Checkin::IS_COMPANY_YES) {
            return $this->company->users;
        } else {
            if ($checkin->checkin_users) {
                $userIds = $this->checkinRepository->getModelPluck($checkin->checkin_users(), 'users.id');
                $allUsersIds = array_unique(array_merge($allUsersIds, $userIds));
            }
            if ($checkin->checkin_teams) {
                $allTeamsUsersIds = [];
                foreach ($checkin->checkin_teams as $team) {
                    $teamUserIds = $this->checkinRepository->getModelPluck($team->users(), 'users.id');
                    $allTeamsUsersIds = array_unique(array_merge($allTeamsUsersIds, $teamUserIds));
                }
                $allUsersIds = array_unique(array_merge($allUsersIds, $allTeamsUsersIds));
            }
            return $this->userRepository->getUsersByIdsForCompany($allUsersIds, $this->company->id, $forTagging);
        }
    }
}
