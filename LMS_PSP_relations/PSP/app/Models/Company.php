<?php

namespace App\Models;

use App\Services\CompanyService;
use Illuminate\Support\Str;

class Company extends BaseModel
{
    protected $fillable = ['id_time_zone', 'id_language', 'date_format', 'session_lifetime', 'session_lifetime_forever', 'file_path', 'file_size', 'file_type', 'file_name', 'file_is_dark', 'lms_file', 'expires', 'api', 'api_token', 'google_calendar_connected'];

    protected $casts = [
        'created' => 'date',
        'expires' => 'date',
        'billing_date' => 'date',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'id_company');
    }

}
