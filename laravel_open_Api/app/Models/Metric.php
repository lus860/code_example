<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'created_by', 'id_company', 'type', 'custom', 'created_at', 'updated_at'];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')
            ->select(['id', 'id_company', 'email', 'first_name', 'middle_name', 'last_name', 'id_apm_acl_role', 'active', 'file_path', 'file_type', 'file_size', 'file_name', 'lms_file', 'timezone']);
    }

    public function goal_key_results()
    {
        return $this->hasMany('App\Models\GoalKeyResult', 'id_metric');
    }

    public static function createMetric($request, $userId, $companyId)
    {
        $metric = new self();
        $metric->name = $request->name;
        $metric->id_company = $companyId;
        $metric->created_by = $userId;
        if ($metric->save()) {
            return $metric;
        }
        return false;
    }

    public static function createMetricApi($request, $companyId)
    {
        $metric = new self();
        $metric->name = $request->name;
        $metric->id_company = $companyId;
        if ($metric->save()) {
            return $metric;
        }
        return false;
    }

    public static function updateMetric($metric, $request)
    {
        $metric->name = $request->name;

        if ($metric->save()) {
            return $metric;
        }
        return false;
    }
}
