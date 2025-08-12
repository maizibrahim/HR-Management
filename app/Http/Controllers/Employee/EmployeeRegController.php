<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\leave\LeaveGroup;
use App\Models\leave\LeaveType;
use App\Models\leave\LeaveBalance;
use App\Models\designation;
use App\Models\designation\classification;
use App\Models\designation\rank;
use App\Models\Permission;
use App\Models\country;
use App\Models\employee\employeesalarylog;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Validation\Rule;
use Carbon\Carbon;


class EmployeeRegController extends Controller
{
   /**
     * Display a listing of users with their relationships
     * Shows: All users with their leave groups, supervisors, and subordinates
     */
    public function index()
    {
        $users = User::with(['leaveGroup', 'supervisor', 'subordinates'])
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     * Prepares data for dropdowns: leave groups and potential supervisors
     */
    public function create()
    {
        $leaveGroups = LeaveGroup::all();

        // Only show users who can be supervisors:
        // 1. Users who already have subordinates (current supervisors)
        // 2. Users who don't have supervisors themselves (senior staff)
        $supervisors = User::whereHas('subordinates')
            ->orWhereDoesntHave('supervisor')
            ->orderBy('name')
            ->get();

        return view('users.create', compact('leaveGroups', 'supervisors'));
    }

    /**
     * Store a newly created user with proper leave balance setup
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'leave_group_id' => 'nullable|exists:leave_groups,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'join_date' => 'required|date|before_or_equal:today',
        ]);

        // Prevent self-supervision (user cannot supervise themselves)
        if ($validated['supervisor_id'] && $validated['supervisor_id'] == $request->user()->id) {
            return redirect()->back()
                ->withErrors(['supervisor_id' => 'A user cannot be their own supervisor.'])
                ->withInput();
        }

        // Use database transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'leave_group_id' => $validated['leave_group_id'],
                'supervisor_id' => $validated['supervisor_id'],
                'join_date' => $validated['join_date'],
            ]);

            // If leave group is assigned, automatically create leave balances
            if ($validated['leave_group_id']) {
                $this->createLeaveBalances($user, $validated['leave_group_id']);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user with all related information
     */
    public function show(User $user)
    {
        // Load all related data for comprehensive user view
        $user->load([
            'leaveGroup',
            'supervisor',
            'subordinates',
            'leaveBalances.leaveType',
            'leaveRequests.leaveType'
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        $leaveGroups = LeaveGroup::all();

        // Exclude the user being edited from supervisor options (prevent self-supervision)
        $supervisors = User::where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('users.edit', compact('user', 'leaveGroups', 'supervisors'));
    }

    /**
     * Update the specified user with validation and leave balance management
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user)
            ],
            'leave_group_id' => 'nullable|exists:leave_groups,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'join_date' => 'required|date|before_or_equal:today',
        ]);

        // Prevent self-supervision
        if ($validated['supervisor_id'] == $user->id) {
            return redirect()->back()
                ->withErrors(['supervisor_id' => 'A user cannot be their own supervisor.'])
                ->withInput();
        }

        // Prevent circular supervision (A supervises B, B cannot supervise A)
        if ($validated['supervisor_id'] && $this->wouldCreateCircularSupervision($user, $validated['supervisor_id'])) {
            return redirect()->back()
                ->withErrors(['supervisor_id' => 'This assignment would create a circular supervision relationship.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $oldLeaveGroupId = $user->leave_group_id;

            // Update user data
            $user->update($validated);

            // If leave group changed, update leave balances
            if ($oldLeaveGroupId != $validated['leave_group_id']) {
                if ($validated['leave_group_id']) {
                    $this->updateLeaveBalances($user, $validated['leave_group_id']);
                } else {
                    // Remove all leave balances if no leave group assigned
                    $user->leaveBalances()->delete();
                }
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user and handle dependent data
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            // Update subordinates to remove this supervisor (prevent orphaned records)
            $user->subordinates()->update(['supervisor_id' => null]);

            // Delete the user (leave requests and balances will be cascade deleted)
            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Show supervisor assignment form for specific user
     */
    public function assignSupervisor(User $user)
    {
        $supervisors = User::where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('users.assign-supervisor', compact('user', 'supervisors'));
    }

    /**
     * Update supervisor assignment with validation
     */
    public function updateSupervisor(Request $request, User $user)
    {
        $validated = $request->validate([
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        // Prevent self-supervision
        if ($validated['supervisor_id'] == $user->id) {
            return redirect()->back()
                ->with('error', 'A user cannot be their own supervisor.');
        }

        // Prevent circular supervision
        if ($validated['supervisor_id'] && $this->wouldCreateCircularSupervision($user, $validated['supervisor_id'])) {
            return redirect()->back()
                ->with('error', 'This assignment would create a circular supervision relationship.');
        }

        $user->update(['supervisor_id' => $validated['supervisor_id']]);

        $supervisorName = $validated['supervisor_id']
            ? User::find($validated['supervisor_id'])->name
            : 'None';

        return redirect()->route('users.show', $user)
            ->with('success', "Supervisor updated to: {$supervisorName}");
    }

    /**
     * Show leave group assignment form
     */
    public function assignLeaveGroup(User $user)
    {
        $leaveGroups = LeaveGroup::all();

        return view('users.assign-leave-group', compact('user', 'leaveGroups'));
    }

    /**
     * Bulk supervisor assignment for multiple users
     */
    public function bulkAssignSupervisor(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $supervisor = User::find($validated['supervisor_id']);
        $userIds = $validated['user_ids'];

        // Remove supervisor from the list if present (prevent self-supervision)
        $userIds = array_filter($userIds, function($id) use ($supervisor) {
            return $id != $supervisor->id;
        });

        if (empty($userIds)) {
            return redirect()->back()
                ->with('error', 'No valid users selected for supervisor assignment.');
        }

        User::whereIn('id', $userIds)->update(['supervisor_id' => $supervisor->id]);

        $count = count($userIds);
        return redirect()->route('users.index')
            ->with('success', "Assigned {$supervisor->name} as supervisor to {$count} users.");
    }

    /**
     * Private: Create leave balances for a user based on their leave group
     * Called when user is created or leave group is changed
     */
    private function createLeaveBalances(User $user, $leaveGroupId)
    {
        // Delete existing balances first to avoid duplicates
        $user->leaveBalances()->delete();

        $leaveGroup = LeaveGroup::with('leaveTypes')->find($leaveGroupId);

        // Calculate next reset date (one year from join date)
        $resetDate = Carbon::parse($user->join_date)->addYear();

        // Create balance for each leave type in the group
        foreach ($leaveGroup->leaveTypes as $leaveType) {
            LeaveBalance::create([
                'user_id' => $user->id,
                'leave_type_id' => $leaveType->id,
                'days_remaining' => $leaveType->days_allowed,
                'reset_date' => $resetDate,
            ]);
        }
    }

    /**
     * Private: Update leave balances when leave group changes
     */
    private function updateLeaveBalances(User $user, $leaveGroupId)
    {
        // Delete existing balances
        $user->leaveBalances()->delete();

        // Create new balances based on new leave group
        $this->createLeaveBalances($user, $leaveGroupId);
    }

    /**
     * Private: Check if assigning a supervisor would create circular supervision
     * Prevents: A supervises B, then B supervises A
     */
    private function wouldCreateCircularSupervision(User $user, $supervisorId)
    {
        $supervisor = User::find($supervisorId);

        // Check if the user is already supervising the proposed supervisor (directly or indirectly)
        return $this->isUserSupervisorOf($user, $supervisor);
    }

    /**
     * Private: Recursively check if user1 is a supervisor of user2
     * Used to prevent circular supervision chains
     */
    private function isUserSupervisorOf(User $user1, User $user2)
    {
        if (!$user2->supervisor_id) {
            return false; // user2 has no supervisor
        }

        if ($user2->supervisor_id == $user1->id) {
            return true; // user1 directly supervises user2
        }

        // Recursively check up the chain
        return $this->isUserSupervisorOf($user1, $user2->supervisor);
    }
}
