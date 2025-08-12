<?php

namespace App\Policies;

use App\Models\leave\LeaveRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    public function view(User $user, LeaveRequest $leaveRequest)
    {
        if ($user->id === $leaveRequest->user_id) {
            return true;
        }

        if ($user->id === $leaveRequest->user->supervisor_id) {
            return true;
        }

        return false;
    }

    public function cancel(User $user, LeaveRequest $leaveRequest)
    {
        return $user->id === $leaveRequest->user_id && $leaveRequest->status === 'pending';
    }

    public function review(User $user, LeaveRequest $leaveRequest)
    {
        return $user->id === $leaveRequest->user->supervisor_id && $leaveRequest->status === 'pending';
    }
}
