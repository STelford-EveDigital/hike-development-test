<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class sendInternalConfirmationEmail extends Notification
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
        $customer = $this->booking->customer;
        $vehicle = $this->booking->vehicle;
        $bookingTime = date($this->booking->date .' '. $this->booking->time);
        $date = date('Y/m/d H:i', strtotime($bookingTime));

        return (new MailMessage)
                    ->line('New booking recieved for:')
                    ->line($customer->name)
                    ->line($customer->email_address)
                    ->line($customer->phone)
                    ->line($vehicle->make .' '. $vehicle->model)
                    ->line($date);
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
