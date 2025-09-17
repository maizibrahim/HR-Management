<?php

namespace App\Notifications;

use App\Models\leave\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leaveRequest;
    protected $status;

    public function __construct(LeaveRequest $leaveRequest, $status)
    {
        $this->leaveRequest = $leaveRequest;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = 'Leave Request ' . ucfirst($this->status);
        $statusColor = $this->status === 'approved' ? '#10B981' : '#EF4444';

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your leave request has been ' . $this->status . '.')
            ->line('Leave Type: ' . $this->leaveRequest->leaveType->leave_name)
            ->line('Duration: ' . $this->leaveRequest->start_date->format('M d, Y') . ' to ' . $this->leaveRequest->end_date->format('M d, Y'))
            ->line('Days: ' . $this->leaveRequest->days_requested);

        if ($this->leaveRequest->review_comments) {
            $mailMessage->line('Comments: ' . $this->leaveRequest->review_comments);
        }

        $mailMessage->action('View Details', url('/leave-requests/' . $this->leaveRequest->id));

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->status,
            'leave_type' => $this->leaveRequest->leaveType->leave_name,
            'start_date' => $this->leaveRequest->start_date,
            'end_date' => $this->leaveRequest->end_date,
            'message' => 'Your leave request has been ' . $this->status
        ];
    }
}
