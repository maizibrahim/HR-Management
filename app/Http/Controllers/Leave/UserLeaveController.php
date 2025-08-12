<?php

namespace App\Http\Controllers\Leave;

use App\Models\User;
use App\Models\leave\LeaveGroup;
use App\Models\leave\LeaveType;
use App\Models\leave\LeaveBalance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;



class UserLeaveController extends Controller
{
    /**
     * Assign a leave group to a user and automatically create leave balances
     * This replaces any existing leave group assignment
     */
    public function assignLeaveGroup(Request $request, User $user)
    {
        $validated = $request->validate([
            'leave_group_id' => 'required|exists:leave_groups,id',
        ]);

        // Start transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Update user's leave group
            $user->update([
                'leave_group_id' => $validated['leave_group_id']
            ]);

            // Delete existing leave balances to avoid conflicts
            $user->leaveBalances()->delete();

            // Create new leave balances for all leave types in the new leave group
            $leaveGroup = LeaveGroup::with('leaveTypes')->findOrFail($validated['leave_group_id']);
            $resetDate = Carbon::parse($user->join_date)->addYear();

            foreach ($leaveGroup->leaveTypes as $leaveType) {
                LeaveBalance::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'days_remaining' => $leaveType->days_allowed,
                    'reset_date' => $resetDate,
                ]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Leave group assigned successfully and leave balances updated.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Failed to assign leave group: ' . $e->getMessage());
        }
    }

    /**
     * Display user's leave balances for administrative management
     * Shows: Current balances, reset dates, and edit forms
     */
    public function manageLeaveBalances(User $user)
    {
        if (!$user->leaveGroup) {
            return redirect()->back()
                ->with('error', 'User is not assigned to any leave group.');
        }

        $leaveBalances = $user->leaveBalances()
            ->with('leaveType')
            ->orderBy('leave_type_id')
            ->get();

        return view('users.leave-balances', compact('user', 'leaveBalances'));
    }

    /**
     * Update a specific leave balance (manual adjustment)
     * Used for: Corrections, manual adjustments, carry-over days
     */
    public function updateLeaveBalance(Request $request, LeaveBalance $leaveBalance)
    {
        $validated = $request->validate([
            'days_remaining' => 'required|integer|min:0|max:999',
            'reset_date' => 'required|date|after:today',
        ]);

        $leaveBalance->update($validated);

        return redirect()->back()
            ->with('success', 'Leave balance updated successfully.');
    }

    /**
     * Reset leave balances for users whose reset date is today
     * This method is called by scheduled command or manually by admin
     * Returns: Count of balances reset (for logging/notification)
     */
    public function resetLeaveBalances()
    {
        $today = Carbon::today();

        // Find all leave balances that should be reset today
        $balancesToReset = LeaveBalance::with(['user', 'leaveType'])
            ->whereDate('reset_date', $today)
            ->get();

        $resetCount = 0;

        foreach ($balancesToReset as $balance) {
            // Get the leave type's default days allowed
            $leaveType = $balance->leaveType;

            // Calculate next reset date (one year from today)
            $nextResetDate = $today->copy()->addYear();

            // Reset the balance to full allowance
            $balance->update([
                'days_remaining' => $leaveType->days_allowed,
                'reset_date' => $nextResetDate,
            ]);

            $resetCount++;
        }

        // Return count for logging purposes
        return $resetCount;
    }

    /**
     * Bulk reset leave balances for specific leave type
     * Used for: Company-wide policy changes, corrections
     */
    public function bulkResetLeaveType(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'reset_to_default' => 'boolean',
            'custom_days' => 'nullable|integer|min:0|max:999',
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);

        // Determine the new balance value
        $newBalance = $validated['reset_to_default']
            ? $leaveType->days_allowed
            : ($validated['custom_days'] ?? $leaveType->days_allowed);

        // Update all balances for this leave type
        $updatedCount = LeaveBalance::where('leave_type_id', $validated['leave_type_id'])
            ->update([
                'days_remaining' => $newBalance,
                'reset_date' => Carbon::today()->addYear(),
            ]);

        return redirect()->back()
            ->with('success', "Reset {$updatedCount} leave balances for {$leaveType->name}.");
    }

    /**
     * Generate leave balance report for all users
     * Shows: Current balances, usage patterns, upcoming resets
     */
    public function leaveBalanceReport()
    {
        $users = User::with(['leaveGroup', 'leaveBalances.leaveType'])
            ->whereNotNull('leave_group_id')
            ->orderBy('name')
            ->get();

        $reportData = [];

        foreach ($users as $user) {
            $userBalances = [];
            foreach ($user->leaveBalances as $balance) {
                $userBalances[] = [
                    'leave_type' => $balance->leaveType->name,
                    'days_remaining' => $balance->days_remaining,
                    'days_allowed' => $balance->leaveType->days_allowed,
                    'usage_percentage' => round(
                        (($balance->leaveType->days_allowed - $balance->days_remaining) / $balance->leaveType->days_allowed) * 100,
                        1
                    ),
                    'reset_date' => $balance->reset_date,
                ];
            }

            $reportData[] = [
                'user' => $user,
                'balances' => $userBalances,
            ];
        }

        return view('reports.leave-balances', compact('reportData'));
    }

    /**
     * Handle carry-over leave from previous year
     * Business rule: Allow carrying over unused leave (with limits)
     */
    public function processCarryOver(Request $request, User $user)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'carry_over_days' => 'required|integer|min:0|max:30', // Max 30 days carry-over
        ]);

        $leaveBalance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$leaveBalance) {
            return redirect()->back()
                ->with('error', 'Leave balance not found for this user and leave type.');
        }

        // Add carry-over days to current balance
        $leaveBalance->update([
            'days_remaining' => $leaveBalance->days_remaining + $validated['carry_over_days']
        ]);

        return redirect()->back()
            ->with('success', "Added {$validated['carry_over_days']} carry-over days to {$user->name}'s balance.");
    }

    /**
     * Preview what would happen if we reset balances today
     * Used for: Testing, verification before actual reset
     */
    public function previewBalanceReset()
    {
        $today = Carbon::today();

        $balancesToReset = LeaveBalance::with(['user', 'leaveType'])
            ->whereDate('reset_date', $today)
            ->get();

        $previewData = [];

        foreach ($balancesToReset as $balance) {
            $previewData[] = [
                'user_name' => $balance->user->name,
                'leave_type' => $balance->leaveType->name,
                'current_balance' => $balance->days_remaining,
                'new_balance' => $balance->leaveType->days_allowed,
                'difference' => $balance->leaveType->days_allowed - $balance->days_remaining,
            ];
        }

        return view('admin.balance-reset-preview', compact('previewData', 'today'));
    }

    /**
     * Emergency balance adjustment for exceptional circumstances
     * Requires: Admin authorization and detailed reason
     */
    public function emergencyBalanceAdjustment(Request $request, User $user)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'adjustment_days' => 'required|integer|min:-999|max:999',
            'reason' => 'required|string|max:500',
            'admin_password' => 'required|string', // Additional security
        ]);

        // Verify admin password (you might want to implement this differently)
        if (!Hash::check($validated['admin_password'], Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['admin_password' => 'Invalid admin password.']);
        }

        $leaveBalance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$leaveBalance) {
            return redirect()->back()
                ->with('error', 'Leave balance not found.');
        }

        $newBalance = max(0, $leaveBalance->days_remaining + $validated['adjustment_days']);

        $leaveBalance->update(['days_remaining' => $newBalance]);

        // Log this emergency adjustment (you might want to create an audit table)
        Log::info('Emergency balance adjustment', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'adjustment' => $validated['adjustment_days'],
            'reason' => $validated['reason'],
            'old_balance' => $leaveBalance->days_remaining - $validated['adjustment_days'],
            'new_balance' => $newBalance,
        ]);

        return redirect()->back()
            ->with('success', 'Emergency balance adjustment completed and logged.');
    }
}
