<?php

namespace App\Notifications\CheckIn;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\BaseNotification;

class CheckInNoteNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $check_in;
    public $noteName;
    public $action;
    public $checkInOwner;
    public $dateFormat;

    public function __construct($check_in, $dateFormat, $noteName, $action)
    {
        $this->check_in = $check_in;
        $this->dateFormat = $dateFormat;
        $this->noteName = $noteName;
        $this->action = $action;
        $this->checkInOwner = $this->check_in->owner;
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

    public function failed(\Exception $e)
    {
        Log::channel('notification')->debug('User failed to send notification', [
            'notification' => 'Check-in members are notified when Note is added/edited/deleted to the check-in meeting.',
            'error_message' => $e->getMessage(),
        ]);
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
            $message = 'Check-in: ' . '"' . $this->noteName->name . '"' . ' note have been added to ' . '"' . $this->check_in->name . '".';
        } elseif ($this->action == 'delete') {
            $message = 'Check-in: ' . '"' . $this->noteName->name . '"' . ' note have been deleted from ' . '"' . $this->check_in->name . '".';
        } elseif ($this->action == 'edit') {
            $message = 'Check-in: ' . '"' . $this->noteName->name . '"' . ' note in ' . '"' . $this->check_in->name . '"' . ' has been edited.';
        }

        return [
            'item_id' => $this->check_in->id,
            'check_in_name' => $this->check_in->name,
            'check_in_note_name' => $this->noteName->name,
            'check_in_attached_createdAt' => Carbon::parse($this->noteName->created_at)->format($this->dateFormat . ' H:i:s'),
            'action' => $this->action,
            'type' => 'check_in_note_'.$this->action,
            'section' => 'check-ins',
            'message' => $message,
            'notification' => 'Check-in members are notified when Note is added/edited/deleted to the check-in meeting.',
        ];
    }
}
