<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveBalanceReset extends Notification implements ShouldQueue
{
    use Queueable;

    protected $balances;

    public function __construct($balances)
    {
        $this->balances = $balances;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Leave Balances Reset')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your leave balances have been reset for the new year!');

        foreach ($this->balances as $balance) {
            $mailMessage->line($balance['leave_type'] . ': ' . $balance['days'] . ' days');
        }

        $mailMessage->line('You can now submit new leave requests.')
                   ->action('View Leave Balances', url('/leave-requests'));

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'balances' => $this->balances,
            'message' => 'Your leave balances have been reset for the new year'
        ];
    }
}
