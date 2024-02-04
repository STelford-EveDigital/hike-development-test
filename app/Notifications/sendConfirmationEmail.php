<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class sendConfirmationEmail extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->booking->vehicle;
        $bookingTime = date($this->booking->date .' '. $this->booking->time);
        $date = date('l jS F ', strtotime($bookingTime)); // 2024-02-04 => Sunday 4th February
        $time = date('h:i A', strtotime($bookingTime)); // 0930 => 9:30 AM

        return (new MailMessage)
                    ->line('Thanks for booking!')
                    ->line('')
                    ->line('We have successfully received your booking for:')
                    ->line($vehicle->make .' '. $vehicle->model)
                    ->line('')
                    ->line('We\'ll see you on the '. $date .' at '. $time);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
