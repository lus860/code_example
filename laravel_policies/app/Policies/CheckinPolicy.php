<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CheckinPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user)
    {
        if ($user->isAdministrator()) {
            return Response::allow();
        }
    }

    /**
     * permission-for-action
     */
    public function permissionForAction(User $user, $user_id)
    {
        $creator = User::find($user_id);

        if ($user->isMember()) {
            if ($creator->isManager() || $creator->isSeniorManager() || $creator->isAdministrator() || ($creator->isMember() && $user_id == $user->id)) {
                return Response::allow();
            }
        } elseif ($user->isManager()) {
            if ($creator->isSeniorManager() || $creator->isAdministrator() || ($creator->isManager() && $user_id == $user->id)) {
                return Response::allow();
            }
        } elseif ($user->isSeniorManager()) {
            if ($creator->isAdministrator() || ($creator->isSeniorManager() && $user_id == $user->id)) {
                return Response::allow();
            }
        }

        return Response::deny();
    }

    /**
     * get users list by role
     * permission-by-role-for-users-list
     */
    public function permissionByRoleForUsersList(User $user, $query)
    {
        if ($user->isSeniorManager()) {
            $query->leftJoin('team_users', 'users.id', '=', 'team_users.id_user');
            $query->whereRaw('`team_users`.id_team is not null');
            return Response::allow();
        } elseif ($user->isManager() || $user->isMember()) {
            $teamIds = $user->teams()->pluck('teams.id')->toArray();
            $query->leftJoin('team_users', 'users.id', '=', 'team_users.id_user');
            $query->whereIn('team_users.id_team', $teamIds);
            return Response::allow();
        }
        return Response::deny();
    }

    /**
     * get Checkin list by role
     * permission-by-role-for-checkin
     */
    public function permissionByRoleForCheckin(User $user, $query)
    {
        if ($user->isSeniorManager()) {
            $query->where(function ($query) use ($user) {
                $query->whereNotNull('checkin_teams.id_team');
                $query->orWhere('checkins.created_by', $user->id);
                $query->orWhere('checkin_users.id_user', $user->id);
                $query->orWhere('checkins.is_company', 1);
            });
            return Response::allow();
        } elseif ($user->isManager()) {
            $teamIds = $user->teams()->pluck('team_users.id_team')->toArray();
            $userIds = \DB::table('team_users')->whereIn('id_team', $teamIds)->pluck('team_users.id_user')->toArray();
            $query->where(function ($query) use ($teamIds, $userIds, $user) {
                $query->whereIn('checkin_teams.id_team', $teamIds);
                $query->orWhere('checkins.created_by', $user->id);
                $query->orWhereIn('checkin_users.id_user', $userIds);
                $query->orWhere('checkin_users.id_user', $user->id);
                $query->orWhere('checkins.is_company', 1);
            });
            return Response::allow();
        } elseif ($user->isMember()) {
            $teamIds = $user->teams()->pluck('team_users.id_team')->toArray();
            $query->where(function ($query) use ($teamIds, $user) {
                $query->whereIn('checkin_teams.id_team', $teamIds);
                $query->orWhere('checkins.created_by', $user->id);
                $query->orWhere('checkin_users.id_user', $user->id);
                $query->orWhere('checkins.is_company', 1);
            });
            return Response::allow();
        }

        return Response::deny();
    }

}
