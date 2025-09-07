<?php

namespace App\Policies;

use App\Models\leave\LeaveRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

   /**
     * Determine if the user can view the leave request
     */
    public function view(User $user, LeaveRequest $leaveRequest)
    {
        // User can view their own leave requests
        if ($user->id === $leaveRequest->user_id) {
            return true;
        }

        // Supervisor can view leave requests from their subordinates
        if ($user->id === $leaveRequest->user->supervisor_id) {
            return true;
        }

        // Admin/HR can view all leave requests (implement role-based check)
       if ($user->hasRole('admin') || $user->hasRole('hr')) {
          return true;
        }

        return false;
    }

    /**
     * Determine if the user can update/edit the leave request
     */
    public function update(User $user, LeaveRequest $leaveRequest)
    {
        // Only the request owner can edit their own pending requests
        return $user->id === $leaveRequest->user_id &&
               $leaveRequest->status === 'pending';
    }

    /**
     * Determine if the user can cancel the leave request
     */
    public function cancel(User $user, LeaveRequest $leaveRequest)
    {
        // Only the request owner can cancel their own pending requests
        return $user->id === $leaveRequest->user_id &&
               $leaveRequest->status === 'pending';
    }

    /**
     * Determine if the user can review/approve/reject the leave request
     */
    public function review(User $user, LeaveRequest $leaveRequest)
    {
        // Only the supervisor can review leave requests
        return $user->id === $leaveRequest->user->supervisor_id &&
               $leaveRequest->status === 'pending' &&
               $this->hasRequiredDocumentation($leaveRequest);
    }

    /**
     * Determine if the user can approve the leave request
     */
    public function approve(User $user, LeaveRequest $leaveRequest)
    {
        return $this->review($user, $leaveRequest);
    }

    /**
     * Determine if the user can reject the leave request
     */
    public function reject(User $user, LeaveRequest $leaveRequest)
    {
        // Supervisors can reject even without documentation
        return $user->id === $leaveRequest->user->supervisor_id &&
               $leaveRequest->status === 'pending';
    }

    /**
     * Determine if the user can download documentation
     */
    public function downloadDocumentation(User $user, LeaveRequest $leaveRequest)
    {
        // Same as view permission
        return $this->view($user, $leaveRequest);
    }

    /**
     * Check if the leave request has required documentation
     */
    private function hasRequiredDocumentation(LeaveRequest $leaveRequest): bool
    {
        // If documentation is required, it must be present
        if ($leaveRequest->leaveType->requires_documentation) {
            return !empty($leaveRequest->documentation_path);
        }

        // If documentation is not required, approval can proceed
        return true;
    }

    /**
     * Determine what actions are available for a leave request
     */
    public function getAvailableActions(User $user, LeaveRequest $leaveRequest): array
    {
        $actions = [];

        if ($this->view($user, $leaveRequest)) {
            $actions[] = 'view';
        }

        if ($this->update($user, $leaveRequest)) {
            $actions[] = 'update';
        }

        if ($this->cancel($user, $leaveRequest)) {
            $actions[] = 'cancel';
        }

        if ($this->approve($user, $leaveRequest)) {
            $actions[] = 'approve';
        }

        if ($this->reject($user, $leaveRequest)) {
            $actions[] = 'reject';
        }

        if ($this->downloadDocumentation($user, $leaveRequest) && $leaveRequest->documentation_path) {
            $actions[] = 'download';
        }

        return $actions;
    }

    /**
     * Get user-friendly messages for policy restrictions
     */
    public function getRestrictionMessage(User $user, LeaveRequest $leaveRequest, string $action): string
    {
        switch ($action) {
            case 'update':
                if ($leaveRequest->status !== 'pending') {
                    return 'Only pending leave requests can be edited.';
                }
                if ($user->id !== $leaveRequest->user_id) {
                    return 'You can only edit your own leave requests.';
                }
                break;

            case 'approve':
                if ($user->id !== $leaveRequest->user->supervisor_id) {
                    return 'Only the employee\'s supervisor can approve this request.';
                }
                if ($leaveRequest->status !== 'pending') {
                    return 'Only pending requests can be approved.';
                }
                if (!$this->hasRequiredDocumentation($leaveRequest)) {
                    return 'Required documentation must be uploaded before approval.';
                }
                break;

            case 'cancel':
                if ($leaveRequest->status !== 'pending') {
                    return 'Only pending requests can be cancelled.';
                }
                if ($user->id !== $leaveRequest->user_id) {
                    return 'You can only cancel your own requests.';
                }
                break;

            default:
                return 'You do not have permission to perform this action.';
        }

        return 'Action not allowed.';
    }

    public function bulkCreate(User $user)
    {
        // Add your admin/HR role check here
        // For example, if you have a 'role' field:
        // return in_array($user->role, ['admin', 'hr']);

        // For now, we'll assume any user with supervisor privileges can do bulk operations
        // You should replace this with your actual role-based logic
        return $user->subordinates()->exists() || $user->email === 'admin@company.com';
    }
}
