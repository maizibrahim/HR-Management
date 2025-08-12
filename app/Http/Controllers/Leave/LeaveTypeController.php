<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\leave\LeaveGroup;
use App\Models\leave\LeaveType;
use App\Models\leave\LeaveRequest;
use Symfony\Contracts\Service\Attribute\Required;

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

        LeaveType::create($validated);

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type created successfully.');
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

        $leaveType->update($validated);

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */



    public function destroy(LeaveType $leaveType)
{
    try {
        $leaveType->delete();
        return redirect()->back()
            ->with('success', 'Leave type deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to delete leave type. It may be in use.');
    }
}



}
