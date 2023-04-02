<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use App\Mail\UserPasswordMail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\TeamsAndPeople\TeamsAndPeopleUserRoleChangedMail;
use App\Mail\TeamsAndPeople\TeamsAndPeopleUsersAddedOrRemovedForAdminMail;
use App\Mail\TeamsAndPeople\TeamsAndPeopleUsersAddedTeamOrRemovedForAdminMail;
use App\Mail\CheckInOrGoalOrFormOrSurveyCreatedForAdminMail;
use App\Mail\TeamsAndPeople\TeamsAndPeopleUserPasswordChangedByAdminMail;
use App\Notifications\Profile\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\TeamsAndPeople\TeamsAndPeopleResendEmailConfirmationMail;
use Illuminate\Support\Facades\Log;
use AppDateFormat;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
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
        'active',
        'region',
        'country',
        'zip',
        'description',
        'timezone',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'last_activity' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id_company');
    }

    public function pulse_points()
    {
        return $this->hasMany('App\Models\PulsePoint', 'id_user');
    }

}
