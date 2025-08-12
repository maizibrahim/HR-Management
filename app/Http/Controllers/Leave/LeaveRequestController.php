<?php

namespace App\Http\Controllers\Leave;

use App\Models\leave\LeaveRequest;
use App\Models\leave\LeaveType;
use App\Models\leave\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class LeaveRequestController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display employee's own leave requests
     * Shows: All requests with status, dates, and reviewer information
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's leave requests with related data, ordered by newest first
        $leaveRequests = $user->leaveRequests()
            ->with(['leaveType', 'reviewer'])
            ->latest()
            ->get();

        return view('leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new leave request
     * Provides: Available leave types and current balances
     */
    public function create()
    {
        $user = Auth::user();

        // Check if user has a leave group assigned
        if (!$user->leaveGroup) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'You are not assigned to any leave group. Please contact HR.');
        }

        // Get leave types available to this user's leave group
        $leaveTypes = $user->leaveGroup->leaveTypes;

        // Get user's current leave balances, keyed by leave type ID for easy lookup
        $leaveBalances = $user->leaveBalances()
            ->with('leaveType')
            ->get()
            ->keyBy('leave_type_id');

        return view('leave-requests.create', compact('leaveTypes', 'leaveBalances'));
    }

    /**
     * Store a newly created leave request
     * Validation: Dates, balance checking, documentation requirements
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Basic validation
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'documentation' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);

        // Security check: Ensure leave type belongs to user's leave group
        if ($leaveType->leave_group_id !== $user->leave_group_id) {
            return redirect()->back()
                ->withErrors(['leave_type_id' => 'This leave type is not available for your leave group.']);
        }

        // Calculate working days (excluding weekends)
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $daysRequested = 0;
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekday()) { // Monday to Friday only
                $daysRequested++;
            }
        }

        // Check if user has sufficient leave balance
        $leaveBalance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$leaveBalance || $leaveBalance->days_remaining < $daysRequested) {
            return redirect()->back()
                ->withErrors(['leave_type_id' => 'You do not have enough leave balance for this request.']);
        }

        // Handle file upload if documentation is provided or required
        $documentationPath = null;
        if ($request->hasFile('documentation')) {
            $documentationPath = $request->file('documentation')
                ->store('leave-documentation', 'public');
        } else if ($leaveType->requires_documentation) {
            return redirect()->back()
                ->withErrors(['documentation' => 'Documentation is required for this leave type.']);
        }

        // Create the leave request
        $leaveRequest = LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_requested' => $daysRequested,
            'reason' => $validated['reason'],
            'documentation_path' => $documentationPath,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully and awaiting approval.');
    }

    /**
     * Display the specified leave request
     * Authorization: Only request owner or their supervisor can view
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        // ✅ Load the relationship if not already loaded
        $leaveRequest->load(['user', 'leaveType', 'reviewer']);

        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Cancel a pending leave request
     * Authorization: Only request owner can cancel their own pending requests
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        $this->authorize('cancel', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending leave requests can be cancelled.');
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request cancelled successfully.');
    }

    /**
     * Display pending leave requests for supervisor approval
     * Shows: All pending requests from current user's subordinates
     */
    public function approvalList()
    {
        $user = Auth::user();

        // Get pending requests from users who report to the current user
        $pendingRequests = LeaveRequest::whereHas('user', function ($query) use ($user) {
            $query->where('supervisor_id', $user->id);
        })
        ->where('status', 'pending')
        ->with(['user', 'leaveType'])
        ->orderBy('created_at', 'asc') // Oldest requests first (FIFO)
        ->get();

        return view('leave-requests.approval-list', compact('pendingRequests','user'));
    }

    /**
     * Approve a leave request
     * Actions: Update status, record reviewer, deduct from leave balance
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('review', $leaveRequest);

        $validated = $request->validate([
            'review_comments' => 'nullable|string|max:1000',
        ]);

        // Update leave request status and reviewer information
        $leaveRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $validated['review_comments'],
        ]);

        // Deduct days from user's leave balance
        $leaveBalance = LeaveBalance::where('user_id', $leaveRequest->user_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->first();

        if ($leaveBalance) {
            $leaveBalance->update([
                'days_remaining' => $leaveBalance->days_remaining - $leaveRequest->days_requested
            ]);
        }

        return redirect()->route('leave-requests.approval-list')
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request
     * Actions: Update status, record reviewer, require rejection reason
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('review', $leaveRequest);

        $validated = $request->validate([
            'review_comments' => 'required|string|max:1000', // Required for rejection
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $validated['review_comments'],
        ]);

        // Note: No balance deduction for rejected requests

        return redirect()->route('leave-requests.approval-list')
            ->with('success', 'Leave request rejected successfully.');
    }
}
