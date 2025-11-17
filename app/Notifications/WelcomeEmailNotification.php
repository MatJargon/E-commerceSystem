<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmailNotification extends Notification{

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)
                        ->subject('Welcome to GadgetGo' . ' - Your Account is Ready!')
                        ->greeting('Hello ' . $notifiable->name . '!')
                        ->line('🎉 Welcome to GadgetGo' . '! We\'re thrilled to have you join our community.')
                        ->line('Your account has been successfully created and you\'re now ready to explore all the features we offer.')
                        ->line('If you have any questions or need assistance, don\'t hesitate to reach out to our support team.')
                        ->line('Happy exploring!')
                        ->action('Get Started', url('/'))
                        ->salutation('Best regards, The GadgetGo' . ' Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
                //
        ];
    }

}
