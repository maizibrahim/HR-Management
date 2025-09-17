<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\leave\LeaveBalance;


class LeaveBalanceLow extends Notification
{
    use Queueable;

    protected $lowBalances;

    /**
     * Create a new notification instance.
     */
    public function __construct($lowBalances)
    {
         $this->lowBalances = $lowBalances;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       $mailMessage = (new MailMessage)
            ->subject('Low Leave Balance Alert')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We noticed that some of your leave balances are running low:');

        foreach ($this->lowBalances as $balance) {
            $mailMessage->line('• ' . $balance['leave_type'] . ': ' . $balance['days_remaining'] . ' days remaining');
        }

        $mailMessage->line('Consider planning your remaining leave days before the reset date.')
                   ->action('View Leave Balances', url('/leave-requests'))
                   ->line('Remember to maintain a healthy work-life balance!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
       return [
            'low_balances' => $this->lowBalances,
            'message' => 'Some of your leave balances are running low'
        ];
    }
}
