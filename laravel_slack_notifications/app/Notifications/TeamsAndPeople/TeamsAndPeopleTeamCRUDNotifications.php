<?php

namespace App\Notifications\TeamsAndPeople;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamsAndPeopleTeamCRUDNotifications extends Notification
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        if ($this->action == 'add') {
            $message = 'Teams & People: ' . '"' . $this->team->name . '"' . ' team has been successfully created.';
        } elseif ($this->action == 'edit') {
            $message = 'Teams & People: ' . '"' . $this->team->name . '"' . ' team has been successfully updated.';
        } elseif ($this->action == 'delete') {
            $message = 'Teams & People: ' . '"' . $this->team->name . '"' . ' team has been successfully deleted.';
        }

        return [
            'item_id' => $this->team->id,
            'action' => $this->action,
            'type' => 'team_' . $this->action,
            'section' => 'teams-people',
            'message' => $message,
            'notification' => 'Creators get notified when they successfully create/edit/delete a team',
        ];
    }
}
