<?php

namespace App\Notifications\TeamsAndPeople;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TeamsAndPeopleTeamCRUDBroadcastingNotifications extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $team;
    public $action;

    public function __construct($team, $action)
    {
        $this->team = $team;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * @param $notifiable
     * @return BroadcastMessage
     */

    public function toBroadcast($notifiable)
    {
        if ($this->action == 'add') {
            $message = 'Teams & People: ' . '"' . $this->team->name . '"' . ' team has been successfully created.';
        } elseif ($this->action == 'edit') {
            $message = 'Teams & People: ' . '"' . $this->team->name . '"' . ' team has been successfully updated.';
        } elseif ($this->action == 'delete') {
            $message = 'Teams & People: ' . '"' . $this->team->name . '"' . ' team has been successfully deleted.';
        }

        return new BroadcastMessage([
            'item_id' => $this->team->id,
            'action' => $this->action,
            'type' => 'team_' . $this->action,
            'section' => 'teams-people',
            'message' => $message,
            'notification' => 'Creators get notified when they successfully create/edit/delete a team',
        ]);
    }

    public function broadcastType()
    {
        return 'broadcast.message';
    }

}
