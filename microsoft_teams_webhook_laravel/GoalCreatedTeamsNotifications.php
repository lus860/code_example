<?php

namespace App\Notifications\Goal\MicrosoftTeams;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class GoalCreatedTeamsNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $goal;
    public $url;
    public $name;
    public $webhook_url;
    public $logo_path;

    public function __construct($logo_path, $url, $name, $webhook_url, $goal)
    {
        $this->url = $url;
        $this->name = $name;
        $this->goal = $goal;
        $this->logo_path = $logo_path;
        $this->webhook_url = $webhook_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MicrosoftTeamsChannel::class];
    }

    public function toMicrosoftTeams($notifiable)
    {
        $message = '"' . $this->goal->name . '"' . ' goal has been successfully created.';

        $url = $this->url;
        $name = $this->name;

        $webhook_url = $this->webhook_url;
        $logo_path = $this->logo_path;

        $check_url = $url . '/goals/' . $this->goal->id;
        return MicrosoftTeamsMessage::create()
            ->to($webhook_url)
            ->type('success')
            ->content("Hello! 👋")
            ->activity($logo_path, "**[$name]($check_url)**", '**Goals**', $message, 1);
    }

}
