<?php

namespace App\Models;

use Auth;
use App\Mail\UserPasswordMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseModel implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SoftDeletes;
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
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

}
