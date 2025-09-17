<?php

namespace App\Notifications;

use App\Models\leave\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Leave Request Submitted')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new leave request has been submitted by ' . $this->leaveRequest->user->name . '.')
            ->line('Leave Type: ' . $this->leaveRequest->leaveType->leave_name)
            ->line('Duration: ' . $this->leaveRequest->start_date->format('M d, Y') . ' to ' . $this->leaveRequest->end_date->format('M d, Y'))
            ->line('Days Requested: ' . $this->leaveRequest->days_requested)
            ->action('Review Request', url('/leave-approvals'))
            ->line('Please review and approve/reject this request.');
    }

    public function toArray($notifiable)
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'employee_name' => $this->leaveRequest->user->name,
            'leave_type' => $this->leaveRequest->leaveType->leave_name,
            'start_date' => $this->leaveRequest->start_date,
            'end_date' => $this->leaveRequest->end_date,
            'message' => 'New leave request submitted by ' . $this->leaveRequest->user->name
        ];
    }
}
