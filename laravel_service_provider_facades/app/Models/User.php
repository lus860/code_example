<?php

namespace App\Models;

use Auth;
use App\Mail\UserPasswordMail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;

class User extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'id_apm_acl_role',
        'id_company',
        'active',
        'title',
        'telephone',
        'address',
        'city',
        'region',
        'country',
        'zip',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'team_users', 'id_user', 'id_team')->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id_company');
    }

    /**
     * @param $request
     * @param $companyId
     * @return User|false
     */
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

    /**
     * @param $user
     * @param null $file
     * @param null $dirPath
     * @param null $filename
     * @param null $name
     * @return false
     */
    public static function updateUserAvatar($user, $file = null, $dirPath = null, $filename = null, $name = null)
    {
        if ($file && $dirPath && $filename && $name) {
            $user->file_path = $dirPath . '/' . $filename;
            $user->file_size = $file->getSize();
            $user->file_type = $file->extension();
            $user->file_name = $name;
            $user->lms_file = User::LMS_FILE_NOT_ACTIVE;
            if ($user->save()) {
                return $user;
            }
            return false;
        } else {
            $user->file_path = null;
            $user->file_size = null;
            $user->file_type = null;
            $user->file_name = null;
            if ($user->save()) {
                return $user;
            }
            return false;
        }
    }

    /**
     * @param $user
     * @param $request
     * @return false
     */
    public static function updateUser($user, $request)
    {
        $fields = $request->only($user->getFillable());
        $user->fill($fields);

        if ($user->save()) {
            return $user;
        }

        return false;
    }

    /**
     * @param $user
     * @param $request
     * @return false
     */
    public static function updateProfile($user, $request)
    {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;

        if ($user->isAdministrator()) {
            $user->email = $request->email;
        }

        if ($user->save()) {
            return $user;
        }

        return false;
    }

}
