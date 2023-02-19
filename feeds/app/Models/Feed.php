<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = ['id_weekly_task', 'created_by', 'id_user', 'id_goal_key_result', 'id_goal', 'id_company', 'name', 'type', 'read_at'];

    public function weekly_status()
    {
        return $this->belongsTo('App\Models\WeeklyTask', 'id_weekly_task');
    }

    public function key_result()
    {
        return $this->belongsTo('App\Models\GoalKeyResult', 'id_goal_key_result');
    }

    public function goal()
    {
        return $this->belongsTo('App\Models\Goal', 'id_goal');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

}
