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

    public function routeNotificationForSlack($notification)
    {
        if ($this->company->slack_connected_id) {
            return $this->company->slack_connected_id;
        }
    }

}
