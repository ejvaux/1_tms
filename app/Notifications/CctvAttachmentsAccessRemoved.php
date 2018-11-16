<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\CctvReview;
use App\Events\triggerEvent;

class CctvAttachmentsAccessRemoved extends Notification implements ShouldQueue
{
    use Queueable;    

    protected $ticket_id;
    protected $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tid,$uname)
    {
        $this->ticket_id = $tid;
        $this->name = $uname;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/cr/crv/'.$this->ticket_id);
        $t = CctvReview::where('id',$this->ticket_id)->first();
        return (new MailMessage)
                ->greeting('Hello! ' .$this->name)
                ->line('Your access to view attachments on CCTV Review Request #'.$t->request_id.' has been removed.')
                ->action('View Request', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        event(new triggerEvent('refresh'));
        $t = CctvReview::where('id',$this->ticket_id)->first();
        return [
            'message' => 'Request Attachment Access Removed.',
            'mod' => 'request',
            'tid' => $this->ticket_id,
            'series' => $t->request_id
        ];
    }
}
