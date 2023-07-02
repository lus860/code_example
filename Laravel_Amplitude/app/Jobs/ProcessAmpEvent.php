<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\AmplitudeEvent as AmplitudeSession;
use \Zumba\Amplitude\Amplitude as Amplitude;


class ProcessAmpEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_params = [];
    private $_user = '';
    private $_eventType = '';
    private $_ip = '';
    private $_agent;
    private $_company = '';
    public $timeout = 120;
    public $failOnTimeout = true;

    const QUEUE_NAME = 'amplitude';
    const EVENT_LOGIN = 'Log In';
    const EVENT_ADD_WEEKLY_STASTUS_ITEM = 'Add Weekly Status Item';
    const EVENT_EDIT_WEEKLY_STATUS_ITEM = 'Edit Weekly Status Item';
    const EVENT_DELETE_WEEKLY_STATUS_ITEM = 'Delete Weekly Status Item';
    const EVENT_CHANGED_WEEKLY_STATUS_ITEM = 'Weekly Status Item Changes';
    const EVENT_COMMENT_IN_WEEKLY_STATUS_ITEM = 'Comment in Weekly Status Item';
    const EVENT_NEW_GOAL = 'Add New Goal';
    const EVENT_NEW_KR_IN_GOAL = 'Goal - Add KR';
    const EVENT_GOAL_NEW_COMMENT = 'Goal - New comment';
    const EVENT_GOAL_EDIT_COMMENT = 'Goal - Edit Comment';
    const EVENT_GOAL_DELETE_COMMENT = 'Goal - Delete Comment';
    const EVENT_LINK_WEEKLY_ITEM = 'Goal - Link weekly item';
    const EVENT_DELETE_WEEKLY_ITEM = 'Goal - Delete weekly item';
    const EVENT_GOAL_ADD_ATTACHMENT = 'Goal - Add attachment';
    const EVENT_GOAL_DELETE_ATTACHMENT = 'Goal - Delete attachment';
    const EVENT_GOAL_EDIT_KR = 'Goal - Edit KR';
    const EVENT_GOAL_DELETE_KR = 'Goal - Delete KR';
    const EVENT_DELETE_GOAL = 'Delete Goal';
    const EVENT_EDIT_GOAL = 'Edit Goal';
    const EVENT_REVIEW_GIVEN = 'Review given';
    const EVENT_NEW_USER = 'Add New User';
    const EVENT_NEW_TEAM = 'Add New Team';
    const EVENT_BULK_DELETE_USERS = 'Bulk delete users';
    const EVENT_DELETE_USER = 'Delete User';
    const EVENT_UPDATE_USER = 'User Update';
    const EVENT_DELETE_TEAM = 'Delete Team';
    const EVENT_TEAM_USERS_ADDED = 'Users added to team';
    const EVENT_TEAM_USERS_REMOVED = 'Users removed from team';
    const EVENT_TEAM_USER_REMOVED = 'User removed from team';
    const EVENT_USER_PASSWORD_CHANGED = 'User Password Changed';
    const EVENT_NEW_CHECKIN = 'Add Check-in';
    const EVENT_EDIT_CHECKIN = 'Edit Check-in';
    const EVENT_DELETED_CHECKIN = 'Delete Check-in';
    const EVENT_CHANGE_CHECKIN = 'Check-in Changes';
    const EVENT_NEW_RATING = 'Happy Score Added';
    const EVENT_CHECKIN_NEW_TALKING_POINT = 'New Talking point added to CheckIn';
    const EVENT_CHECKIN_TALKING_POINT_UPDATE = 'Edit Talking point';
    const EVENT_CHECKIN_TALKING_POINT_REMOVE = 'Talking point removed from CheckIn';
    const EVENT_CHECKIN_NEW_PLAN ='New Plan added to CheckIn';
    const EVENT_CHECKIN_PLAN_UPDATE ='Edit Plan';
    const EVENT_CHECKIN_PLAN_REMOVE ='Plan removed from CheckIn';
    const EVENT_CHECKIN_NEW_NOTE ='New Note added to CheckIn';
    const EVENT_CHECKIN_NOTE_REMOVE ='Note removed from CheckIn';
    const EVENT_CHECKIN_NOTE_UPDATE ='Edit Note';
    const EVENT_NEW_SURVEY = 'Add Survey';
    const EVENT_EDIT_SURVEY = 'Edit Survey';
    const EVENT_DELETE_SURVEY = 'Delete Survey';

    const EVENT_ADD_FORM_TEMPLATE = 'Add Form Template';
    const EVENT_SAVE_GENERAL_SETTINGS = 'Save General Setting';

    const EVENT_CUSTOM_FIELD_SETTINGS = 'Add custom fields Setting';
    const EVENT_BRANDING_SETTINGS = 'Branding Setting';
    const EVENT_SAVE_REVIEW_SETTINGS = 'Save Reviews General Setting';
    const EVENT_SAVE_PERIOD_SETTINGS = 'Save Review period Setting';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $company, $_eventType, $params, $ip, $browserAgent)
    {
        $this->_user = $user;
        $this->_company = $company;
        $this->_params = $this->setDefaultEventProperties($user, $params);
        $this->_eventType = $_eventType;
        $this->_ip = $ip;
        $this->_agent = $browserAgent;
    }

    public function setDefaultEventProperties($user, $params)
    {
        $user_data = [
            'company_name' =>  $user->company->name,
            'company_url' => $user->company->URL_DOMAIN(),
            'user_name' => $user->getName(),
            'user_email' => $user->email,
            'user_role' => $user->_getRole()->name,
        ];
        return array_merge($params, $user_data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $amplitude = new Amplitude();
        $amplitude->init(env('AMPLITUDE_API_KEY'));
        $event = $amplitude->event();
        $platform = $this->_agent->platform();
        $event->userId = "userId{$this->_user->id}";
        $event->set($this->_params);
        //session id 
        $last_login = $this->_user->last_login;
        $format = "m/d/Y";
        if ($this->_company->date_format) {
            $format = str_replace("%", "", $this->_company);
        }
        $time = is_numeric($last_login) ? $last_login : strtotime($last_login);
        $userProperties = [
            'company_created_date' => $this->_company->created,
            'company_id' => $this->_company->id,
            'company_name' => $this->_company->name,
            'email' => $this->_user->email,
            'pricing_plan' => $this->_company->id_pricing_plan,
            'user_id' => $this->_user->id,
            'user_name' => $this->_user->getName(),
            'user_role' => $this->_user->_getRole()->name,
            'device_type' => $this->_agent->device()
        ];
        $event->setUserProperties($userProperties);
        $event->eventType = $this->_eventType;
        $event->platform = $platform;
        $event->osName = $this->_agent->browser(); 
        $event->osVersion = $this->_agent->version($platform);
        $event->deviceType = $this->_agent->device();
        $event->ip = $this->_ip;
        $amplitude->event(new AmplitudeSession($event));
        $event = $amplitude->event();
        $event->session_id = $time;
        // Since we used $amplitude->event() to get event object, it will be the event to be sent when we call this
        $amplitude->logEvent();
    }
}
