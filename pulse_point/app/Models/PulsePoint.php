<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulsePoint extends Model
{
    use HasFactory;

    protected $fillable = ['productivity', 'feeling', 'feedback', 'id_company', 'id_user', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'id_apm_acl_role', 'last_name', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    /**
     * @param $request
     * @param $userId
     * @param $companyId
     * @return PulsePoint|false
     */
    public static function createPulsePoint($request, $userId, $companyId)
    {
        $pulsePoint = new self();

        if (isset($request->productivity) && $request->productivity) {
            $pulsePoint->productivity = $request->productivity;
        }
        if (isset($request->feeling) && $request->feeling) {
            $pulsePoint->feeling = $request->feeling;
        }
        if (isset($request->feedback) && $request->feedback) {
            $pulsePoint->feedback = $request->feedback;
        }
        $pulsePoint->id_company = $companyId;
        $pulsePoint->id_user = $userId;

        if ($pulsePoint->save()) {
            return $pulsePoint;
        }
        return false;
    }

    /**
     * @param $pulsePoint
     * @param $request
     * @return false
     */
    public static function updatePulsePoint($pulsePoint, $request)
    {
        if (isset($request->productivity) && $request->productivity) {
            $pulsePoint->productivity = $request->productivity;
        }
        if (isset($request->feeling) && $request->feeling) {
            $pulsePoint->feeling = $request->feeling;
        }
        if (isset($request->feedback) && $request->feedback) {
            $pulsePoint->feedback = $request->feedback;
        }

        if ($pulsePoint->save()) {
            return $pulsePoint;
        }
        return false;
    }

}
