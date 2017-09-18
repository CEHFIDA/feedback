<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Channels\HttpChannel;
Use App\Channels\Messages\HttpMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;
    protected $info;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        //
        $this->info = $info;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', HttpChannel::class];
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
                ->subject($info['subject'])
                ->greeting('Ответ поддержки BlockDash.io')
                ->line($info['message']);
    }

    public function toHttp($notifiable){   
        $params = [];
        if(isset($this->info)){
            $params = $this->info;
        }
        if(isset($notifiable->notiffication_status)){
            $HttpParam = json_decode($notifiable->notiffication_status);
            return HttpMessage::create()->method($HttpParam->method)->url($HttpParam->url)->params($params);
        }else{
            return null;
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
        return [
            //
        ];
    }
}
