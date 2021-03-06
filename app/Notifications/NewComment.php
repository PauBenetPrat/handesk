<?php

namespace App\Notifications;

use App\Requester;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewComment extends Notification
{
    use Queueable;

    public $ticket;
    public $comment;

    public function __construct($ticket, $comment) {
        $this->ticket   = $ticket;
        $this->comment  = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ( method_exists($notifiable, 'routeNotificationForSlack' ) && $notifiable->routeNotificationForSlack() != null) ? ['slack'] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $mail = (new MailMessage)
            ->subject("Ticket updated: #" .$this->ticket->id . ": ". $this->ticket->title)
            ->replyTo(config('mail.fetch.username'))
            ->view( "emails.ticket" ,[
                    "title"  => "Ticket updated",
                    "ticket" => $this->ticket,
                    "comment" => $this->comment,
                    "url"    => $notifiable instanceof Requester ? route("requester.tickets.show", $this->ticket->public_token) : route("tickets.show", $this->ticket),
                ]
            );
        if($this->comment->author()->email){
            $mail->from($this->comment->author()->email, $this->comment->author()->name);
        }
        return $mail;
    }


    public function toSlack($notifiable) {
        return (new BaseTicketSlackMessage(null, $notifiable))
                ->content('Ticket updated')
                ->attachment(function ($attachment)  {
                    $attachment->title($this->ticket->requester->name . " : " . $this->ticket->title, route("tickets.show", $this->ticket))
                               ->content("Status: " . $this->ticket->statusName() . "\n\n" . $this->comment->body);
                });
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
