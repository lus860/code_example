<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;

class UserPolicy
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
     * get list users for teams&people->people page by role
     * permission-by-role-list-users
     */
    public function permissionByRoleListUsers(User $user, $query)
    {
        if ($user->isSeniorManager()) {
            $id_users = DB::table('team_users')->pluck('id_user')->toArray();
            if (!in_array($user->id, $id_users)) {
                $id_users[] = $user->id;
            }
            $query->whereIn('users.id', $id_users);
            return Response::allow();
        } else if ($user->isManager()) {
            $teamIds = $user->teams()->pluck('teams.id')->toArray();
            $userIds = \DB::table('team_users')->whereIn('id_team', $teamIds)->pluck('team_users.id_user')->toArray();
            if (!in_array($user->id, $userIds)) {
                $userIds[] = $user->id;
            }
            $query->whereIn('users.id', $userIds);
            return Response::allow();
        } else if ($user->isMember()) {
            $query->where('users.id', $user->id);
            return Response::allow();
        }
        return Response::deny();
    }

}
