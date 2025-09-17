<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\leave\LeaveRequest;
use App\Notifications\LeaveReminder;
use Carbon\Carbon;

class SendLeaveReminders extends Command
{


    protected $signature = 'app:send-leave-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send leave reminders to employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $tomorrow = Carbon::tomorrow();
        $dayAfterTomorrow = Carbon::tomorrow()->addDay();

        // Leaves starting tomorrow
        $leavesStartingTomorrow = LeaveRequest::where('start_date', $tomorrow)
            ->where('status', 'approved')
            ->with('user')
            ->get();

        foreach ($leavesStartingTomorrow as $leave) {
            $leave->user->notify(new LeaveReminder($leave, 'starting_tomorrow'));
        }

    }
}
