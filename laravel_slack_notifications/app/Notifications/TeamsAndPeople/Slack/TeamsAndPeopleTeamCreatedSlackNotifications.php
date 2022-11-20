<?php

namespace App\Notifications\TeamsAndPeople\Slack;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class TeamsAndPeopleTeamCreatedSlackNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $url;
    public $name;
    public $channel;
    public $team;

    public function __construct($url, $name, $channel, $team)
    {
        $this->url = $url;
        $this->name = $name;
        $this->channel = $channel;
        $this->team = $team;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSlack($notifiable)
    {
        $message = '"' . $this->team->name . '"' . ' team has been successfully created.';

        $url = $this->url;
        $name = $this->name;
        $channel = $this->channel;

        if ($channel) {
            return (new SlackMessage)
                // ->from("@admin")
                ->to('#'.$channel)
                ->success()
                ->content("Hello! :wave:")
                ->attachment(function ($attachment) use ($url, $message, $name) {
                    $attachment->title($name, $url . '/teams-people')
                        ->fields([
                            'Teams & People' => $message,
                        ]);
                });
        } else {
            return (new SlackMessage)
                // ->from("@admin")
                ->success()
                ->content("Hello! :wave:")
                ->attachment(function ($attachment) use ($url, $message, $name) {
                    $attachment->title($name, $url . '/teams-people')
                        ->fields([
                            'Teams & People' => $message,
                        ]);
                });
        }
    }
}
