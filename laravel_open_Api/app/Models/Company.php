<?php

namespace App\Models;


class Company extends BaseModel
{
    protected $fillable = ['id_time_zone', 'id_language', 'date_format', 'file_path', 'file_size', 'file_type', 'file_name', 'slack_connected', 'slack_connected_id'];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'id_company');
    }

    /**
     * @return string
     */
    public function URL()
    {
        if (env('APP_ENV') == 'local') {

            return self::getPort() . config('config.double_directory_separator') . $this->domain . '.' . 'localhost:3000';
        }

        return self::getPort() . config('config.double_directory_separator') . $this->domain . '.' . env('SITE_URL');
    }

    /**
     * @return string
     */
    public function URL_APM()
    {
        return $this->URL();
    }

    public static function getPort()
    {
        $port = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $port = 'https';
        }

        return $port;
    }

}
