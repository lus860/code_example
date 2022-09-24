<?php

namespace App\Models;


class Company extends BaseModel
{
    protected $fillable = ['id_time_zone', 'id_language', 'date_format', 'file_path', 'file_size', 'file_type', 'file_name'];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'id_company');
    }
}
