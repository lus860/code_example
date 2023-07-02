<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    public static function createUser($request, $companyId)
    {
        $user = new self();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->id_apm_acl_role = $request->id_apm_acl_role;
        $user->id_company = $companyId;
        $user->active = self::ACTIVE;

        if ($user->save()) {
            return $user;
        }

        return false;
    }

    public static function updateUser($user, $request)
    {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->id_apm_acl_role = $request->id_apm_acl_role;

        if ($user->save()) {
            return $user;
        }

        return false;
    }

}
