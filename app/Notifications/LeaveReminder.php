<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\leave\LeaveRequest;


class LeaveReminder extends Notification
{
    use Queueable;

    protected $leaveRequest;
    protected $reminderType;

    public function __construct(LeaveRequest $leaveRequest, $reminderType = 'upcoming')
    {
         $this->leaveRequest = $leaveRequest;
        $this->reminderType = $reminderType; // 'upcoming', 'starting_tomorrow', 'ending_tomorrow'

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];

    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
              $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.leave-reminder', [
                'user' => $notifiable,
                'leaveRequest' => $this->leaveRequest,
                'reminderType' => $this->reminderType,
                'greeting' => $greeting,
                'message' => $message
            ]);
    }


    private function getSubject()
    {
        switch ($this->reminderType) {
            case 'starting_tomorrow':
                return 'Your Leave Starts Tomorrow';
            case 'ending_tomorrow':
                return 'Your Leave Ends Tomorrow';
            default:
                return 'Upcoming Leave Reminder';
        }
    }

    private function getGreeting()
    {
        switch ($this->reminderType) {
            case 'starting_tomorrow':
                return 'Your leave starts tomorrow!';
            case 'ending_tomorrow':
                return 'Welcome back! Your leave ends tomorrow.';
            default:
                return 'You have upcoming approved leave.';
        }
    }

    private function getMessage()
    {
        switch ($this->reminderType) {
            case 'starting_tomorrow':
                return 'Please ensure all handovers are complete and your out-of-office is set up.';
            case 'ending_tomorrow':
                return 'Hope you had a great time off! Please prepare to return to work.';
            default:
                return 'This is a reminder about your upcoming leave. Please prepare accordingly.';
        }
    }



    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'reminder_type' => $this->reminderType,
            'message' => $this->getMessage()
        ];
    }
}
