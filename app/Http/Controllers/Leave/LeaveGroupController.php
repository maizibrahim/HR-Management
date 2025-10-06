<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\leave\LeaveGroup;
use App\Models\leave\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveGroups = LeaveGroup::with('leaveTypes')->get();
        return view('leave.leave-groups.index', compact('leaveGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave.leave-groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_groups',
            'description' => 'nullable|string',
        ]);

        LeaveGroup::create($validated);

        return redirect()->route('leave.leave-groups.index')
            ->with('success', 'Leave group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveGroup $leaveGroup)
    {
        $leaveGroup->load(['leaveTypes', 'users']);
        return view('leave.leave-groups.show', compact('leaveGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveGroup $leaveGroup)
    {
        return view('leave.leave-groups.edit', compact('leaveGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveGroup $leaveGroup)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('leave_groups')->ignore($leaveGroup)
            ],
            'description' => 'nullable|string',
        ]);

        $leaveGroup->update($validated);

        return redirect()->route('leave.leave-groups.index')
            ->with('success', 'Leave group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

     public function deleteleaveGroup($id)
    {
        LeaveGroup::findOrFail($id)->delete();

          return redirect()->route('leave.leave-groups.index')
            ->with('success', 'Leave group deleted successfully.');



    }





}
