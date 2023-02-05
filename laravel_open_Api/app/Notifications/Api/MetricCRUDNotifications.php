<?php

namespace App\Notifications\Goal;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MetricCRUDNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $metric;
    public $action;

    public function __construct($metric, $action)
    {
        $this->metric = $metric;
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
    public function toArray($notifiable)
    {
        if ($this->action == 'add') {
            $message = 'Settings: ' . '"' . $this->metric->name . '"' . ' category has been successfully created.';
        } elseif ($this->action == 'edit') {
            $message = 'Settings: ' . '"' . $this->metric->name . '"' . ' category has been successfully updated.';
        } elseif ($this->action == 'delete') {
            $message = 'Settings: ' . '"' . $this->metric->name . '"' . ' category has been successfully deleted.';
        }

        return [
            'item_id' => $this->metric->id,
            'action' => $this->action,
            'type' => 'category_' . $this->action,
            'section' => 'settings',
            'message' => $message,
            'notification' => 'Creators get notified when they successfully create/edit/delete a metric',
        ];
    }
}
