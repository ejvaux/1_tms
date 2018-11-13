<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;
use App\User;
use App\Events\triggerEvent;
use App\Ticket;

class TicketAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket_id;
    protected $name;
    protected $type;
    protected $assigner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tid,$uname,$type,$assigner = '')
    {        
        $this->ticket_id = $tid;
        $this->name = $uname;
        $this->type = $type;
        $this->assigner = $assigner;
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
        $t = Ticket::where('id',$this->ticket_id)->first();
        if($this->type == 'user'){
            $url = url('/it/vt/'.$this->ticket_id);
            $turl = '/1_atms/public/it/vt/'.$this->ticket_id;
            return (new MailMessage)
                    ->greeting('Hello! ' .$this->name)
                    ->line('Your ticket <b>#'.$t->ticket_id.'</b> is now on queue.')
                    ->line('Ticket is assigned to <b>'.$t->assign->name.'</b>.')
                    ->action('View Ticket', $url)
                    ->line('Thank you for using our application!');
        }
        else{
            $url = url('/it/vt/'.$this->ticket_id);
            $turl = '/1_atms/public/it/htv/'.$this->ticket_id;
            return (new MailMessage)
                    ->greeting('Hello! ' . $this->name)
                    ->line('Ticket <b>#' . $t->ticket_id . '</b> is assigned to you by <b>'.$this->assigner.'</b>.')
                    ->action('View Ticket', $url)
                    ->line('Your immediate response is highly appreciated.');
        }
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
        $t = Ticket::where('id',$this->ticket_id)->first();
        if($this->type == 'user'){
            $url = url('/it/vt/'.$this->ticket_id);
            return [
                'message' => 'Ticket #'.$t->ticket_id.' Assigned.',
                'mod' => 'user',
                'tid' => $this->ticket_id,
                'series' => $t->ticket_id
            ];
        }
        else{
            $url = url('/it/htv/'.$this->ticket_id);
            return [
                'message' => 'New Ticket Assignment.',
                'mod' => 'assign_admin',
                'tid' => $this->ticket_id,
                'series' => $t->ticket_id
            ];            
        }
    }
}
