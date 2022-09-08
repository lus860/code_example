<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\FormTemplatePolicy;
use App\Policies\WeeklyStatusPolicy;
use App\Policies\ReviewUserPolicy;
use App\Policies\CheckinPolicy;
use App\Policies\SurveyPolicy;
use App\Policies\GoalPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use App\Models\FormTemplate;
use App\Models\WeeklyTask;
use App\Models\ReviewUser;
use App\Models\Checkin;
use App\Models\Survey;
use App\Models\Goal;
use App\Models\Team;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        WeeklyTask::class => WeeklyStatusPolicy::class,
        ReviewUser::class => ReviewUserPolicy::class,
        Goal::class => GoalPolicy::class,
        FormTemplate::class => FormTemplatePolicy::class,
        Checkin::class => CheckinPolicy::class,
        Team::class => TeamPolicy::class,
        Survey::class => SurveyPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
