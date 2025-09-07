<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\leave\LeaveBalance;
use App\Models\leave\LeaveGroup;
use App\Models\leave\LeaveType;
use App\Models\User;
use App\Models\leave\LeaveRequest;
use Symfony\Contracts\Service\Attribute\Required;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $leaveTypes = LeaveType::with('leaveGroup')->get();
        return view('leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $leaveGroups = LeaveGroup::all();
        return view('leave-types.create', compact('leaveGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'leave_group_id' => 'required|exists:leave_groups,id',
            'leave_code' => 'required|string',
            'leave_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('leave_types')->where(function ($query) use ($request) {
                    return $query->where('leave_group_id', $request->leave_group_id);
                }),
            ],
            'days_allowed' => 'required|integer|min:1',
            'requires_documentation' => 'boolean',
            ]);

            DB::beginTransaction();


        try {
               // Create the leave type
            $leaveType = LeaveType::create($validated);

        // Create leave balances for all existing users in this leave group
            $this->createLeaveBalancesForExistingUsers($leaveType);

            DB::commit();

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type created successfully.');

        }    catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Failed to create leave type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveType $leaveType)
    {

        $leaveType->load(['leaveGroup', 'leaveRequests.user', 'leaveBalances.user']);
    return view('leave-types.show', compact('leaveType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        $leaveGroups = LeaveGroup::all();
        return view('leave-types.edit', compact('leaveType', 'leaveGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'leave_group_id' => 'required|exists:leave_groups,id',
            'leave_code' => 'required|string',
            'leave_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('leave_types')->where(function ($query) use ($request) {
                    return $query->where('leave_group_id', $request->leave_group_id);
                })->ignore($leaveType),
            ],
            'days_allowed' => 'required|integer|min:1',
            'requires_documentation' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $oldLeaveGroupId = $leaveType->leave_group_id;

            // Update the leave type
            $leaveType->update($validated);

            // If leave group changed, handle leave balance reassignment
            if ($oldLeaveGroupId !== $validated['leave_group_id']) {
                // Delete existing leave balances for this leave type
                LeaveBalance::where('leave_type_id', $leaveType->id)->delete();

                // Create new leave balances for users in the new leave group
                $this->createLeaveBalancesForExistingUsers($leaveType);
            } else {
                // If days_allowed changed, update existing balances proportionally
                if ($leaveType->wasChanged('days_allowed')) {
                    $this->updateExistingLeaveBalances($leaveType, $validated['days_allowed']);
                }
            }

            DB::commit();

            return redirect()->route('leave-types.index')
                ->with('success', 'Leave type updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Failed to update leave type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteleavetype($id)
{
    try {
        LeaveType::findOrFail($id)->delete();
        return redirect()->back()
            ->with('success', 'Leave type deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to delete leave type. It may be in use.');
    }
}


/**
     * Create leave balances for all existing users in the leave type's group
     */
    private function createLeaveBalancesForExistingUsers(LeaveType $leaveType)
    {
        $users = User::where('leave_group_id', $leaveType->leave_group_id)->get();

        foreach ($users as $user) {
            // Check if balance already exists (to prevent duplicates)
            $existingBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $leaveType->id)
                ->first();

            if (!$existingBalance) {
                // Calculate reset date based on user's join date
                $resetDate = Carbon::parse($user->join_date);
                $today = Carbon::today();

                // If the annual reset date has already passed this year, set it for next year
                if ($resetDate->dayOfYear <= $today->dayOfYear) {
                    $resetDate = $resetDate->addYear();
                } else {
                    // Set it for this year
                    $resetDate = $resetDate->setYear($today->year);
                }

                LeaveBalance::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'days_remaining' => $leaveType->days_allowed,
                    'reset_date' => $resetDate,
                ]);
            }
        }
    }

    /**
     * Update existing leave balances when days_allowed changes
     */
    private function updateExistingLeaveBalances(LeaveType $leaveType, int $newDaysAllowed)
    {
        $oldDaysAllowed = $leaveType->getOriginal('days_allowed');
        $difference = $newDaysAllowed - $oldDaysAllowed;

        // Update all existing leave balances for this leave type
        LeaveBalance::where('leave_type_id', $leaveType->id)
            ->increment('days_remaining', $difference);
    }



}
