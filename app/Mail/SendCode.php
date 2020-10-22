<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('السلام عليكم ورحمة الله وبركاتة ')
            ->action($this->code ."  ".' استعادة كلمة مرور تطبيق قرية بشير  ',null)
            ->line('شكرا لاستخدامكم تطبيق قرية بشير');
    }
}
