<?php

namespace App\Http\Controllers\Leave;

use App\Models\leave\LeaveRequest;
use App\Models\leave\LeaveType;
use App\Models\leave\LeaveBalance;
use App\Models\PublicHoliday;
use App\Services\LeaveReportService;
use App\Providers\LeaveReportServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use App\Notifications\LeaveRequestSubmitted;
use App\Notifications\LeaveRequestStatusChanged;
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

        if (!$user->leaveGroup) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'You are not assigned to any leave group. Please contact HR.');
        }

        // Sync user's leave balances to ensure they have balances for all leave types
        $userLeaveController = new UserLeaveController();
        $syncedCount = $userLeaveController->syncUserLeaveBalances($user);

        if ($syncedCount > 0) {
            session()->flash('info', "Created {$syncedCount} missing leave balance(s) for new leave types.");
        }

        $leaveTypes = $user->leaveGroup->leaveTypes;
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

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:' . Carbon::yesterday()->toDateString(),
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'documentation' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);

        // Check if the leave type belongs to the user's leave group
        if ($leaveType->leave_group_id !== $user->leave_group_id) {
            return redirect()->back()->withErrors(['leave_type_id' => 'This leave type is not available for your leave group.']);
        }

        // Calculate the number of days requested (excluding weekends and public holidays)
        $daysRequested = $this->calculateLeaveDays($validated['start_date'], $validated['end_date']);

        // Check if the user has enough leave balance
        $leaveBalance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$leaveBalance || $leaveBalance->days_remaining < $daysRequested) {
            return redirect()->back()->withErrors(['leave_type_id' => 'You do not have enough leave balance for this request.']);
        }

        // Handle file upload if documentation is required or provided
        $documentationPath = null;
        if ($request->hasFile('documentation')) {
            $documentationPath = $request->file('documentation')->store('leave-documentation', 'public');
        } else if ($leaveType->requires_documentation) {
            return redirect()->back()->withErrors(['documentation' => 'Document required.']);
        }

        // Create leave request
        $leaveRequest = LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_requested' => $daysRequested,
            'reason' => $validated['reason'],
            'documentation_path' => $documentationPath,
        ]);

        $message = 'Leave request submitted successfully and awaiting approval.';
        if ($leaveType->requires_documentation && !$documentationPath) {
            $message .= ' Note: Documentation is required for this leave type. You can add it before supervisor approval.';
        }

        // Notify supervisor
        if ($user->supervisor) {
            $user->supervisor->notify(new LeaveRequestSubmitted($leaveRequest));
        }

        return redirect()->route('leave-requests.index')
            ->with('success', $message);

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
     * Show form to edit/add documentation to pending leave request
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Only pending leave requests can be edited.');
        }

        return view('leave-requests.edit', compact('leaveRequest'));
    }

    /**
     * Update leave request (primarily for adding/updating documentation)
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Only pending leave requests can be updated.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string',
            'documentation' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'remove_existing_doc' => 'nullable|boolean'
        ]);

        $updateData = [
            'reason' => $validated['reason'] ?? $leaveRequest->reason,
        ];

        // Handle documentation removal
        if ($request->boolean('remove_existing_doc') && $leaveRequest->documentation_path) {
            Storage::disk('public')->delete($leaveRequest->documentation_path);
            $updateData['documentation_path'] = null;
        }

        // Handle new documentation upload
        if ($request->hasFile('documentation')) {
            // Delete old file if exists and not already removed
            if ($leaveRequest->documentation_path && !$request->boolean('remove_existing_doc')) {
                Storage::disk('public')->delete($leaveRequest->documentation_path);
            }

            $updateData['documentation_path'] = $request->file('documentation')->store('leave-documentation', 'public');
        }

        $leaveRequest->update($updateData);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request updated successfully.');
    }

     /**
     * Download documentation file
     */
    public function downloadDocumentation(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        if (!$leaveRequest->documentation_path || !Storage::disk('public')->exists($leaveRequest->documentation_path)) {
            return redirect()->back()
                ->with('error', 'Documentation file not found.');
        }

        $filePath = Storage::disk('public')->path($leaveRequest->documentation_path);
        $fileName = 'leave_documentation_' . $leaveRequest->id . '.' . pathinfo($leaveRequest->documentation_path, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
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

         // Notify employee
        $leaveRequest->user->notify(new LeaveRequestStatusChanged($leaveRequest, 'approved'));

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

        // Notify employee
        $leaveRequest->user->notify(new LeaveRequestStatusChanged($leaveRequest, 'rejected'));


        // Note: No balance deduction for rejected requests
        return redirect()->route('leave-requests.approval-list')
            ->with('success', 'Leave request rejected successfully.');
    }

    /**
     * Show bulk leave request form (Admin/HR only)
     */
    public function bulkCreate()
    {
        // Check if user has admin/HR permissions
      //  $this->authorize('bulkCreate', LeaveRequest::class);

        $users = User::with('leaveGroup')->whereNotNull('leave_group_id')->get();
        $leaveTypes = LeaveType::with('leaveGroup')->get();

        return view('leave-requests.bulk-create', compact('users', 'leaveTypes'));
    }

    /**
     * Store bulk leave requests (Admin/HR only)
     */
    public function bulkStore(Request $request)
    {
        // Check if user has admin/HR permissions
      //  $this->authorize('bulkCreate', LeaveRequest::class);

        $validated = $request->validate([
            'requests' => 'required|array|min:1',
            'requests.*.user_id' => 'required|exists:users,id',
            'requests.*.leave_type_id' => 'required|exists:leave_types,id',
            'requests.*.start_date' => 'required|date',
            'requests.*.end_date' => 'required|date|after_or_equal:requests.*.start_date',
            'requests.*.reason' => 'nullable|string',
            'requests.*.status' => 'required|in:pending,approved,rejected',
            'requests.*.review_comments' => 'nullable|string',
            'requests.*.auto_deduct_balance' => 'boolean',
            'requests.*.override_balance_check' => 'boolean',
        ]);

        $results = [
            'success' => [],
            'errors' => [],
            'total' => count($validated['requests'])
        ];

        DB::beginTransaction();

        try {
            foreach ($validated['requests'] as $index => $requestData) {
                try {
                    $result = $this->processBulkLeaveRequest($requestData, $index);
                    if ($result['success']) {
                        $results['success'][] = $result;
                    } else {
                        $results['errors'][] = $result;
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = [
                        'index' => $index,
                        'user_id' => $requestData['user_id'],
                        'error' => 'Processing failed: ' . $e->getMessage()
                    ];
                }
            }

            if (empty($results['errors'])) {
                DB::commit();
                return redirect()->route('leave-requests.bulk-create')
                    ->with('success', "Successfully processed {$results['total']} leave requests.");
            } else {
                DB::rollback();
                return redirect()->back()
                    ->withInput()
                    ->with('bulk_results', $results)
                    ->with('error', 'Some requests failed. Please check the details below.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bulk processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Process individual bulk leave request
     */
    private function processBulkLeaveRequest(array $requestData, int $index)
    {
        $user = User::with('leaveGroup')->findOrFail($requestData['user_id']);
        $leaveType = LeaveType::findOrFail($requestData['leave_type_id']);

        // Validate that leave type belongs to user's leave group
        if (!$user->leaveGroup || $leaveType->leave_group_id !== $user->leaveGroup->id) {
            return [
                'success' => false,
                'index' => $index,
                'user_id' => $requestData['user_id'],
                'error' => 'Leave type does not belong to user\'s leave group'
            ];
        }

         // Calculate the number of days requested (excluding weekends and public holidays)
        $daysRequested = $this->calculateLeaveDays($requestData['start_date'], $requestData['end_date']);



        // Check leave balance if not overriding
        if (!($requestData['override_balance_check'] ?? false)) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $requestData['leave_type_id'])
                ->first();

            if (!$leaveBalance) {
                return [
                    'success' => false,
                    'index' => $index,
                    'user_id' => $requestData['user_id'],
                    'error' => 'No leave balance found for this leave type'
                ];
            }

            if ($leaveBalance->days_remaining < $daysRequested) {
                return [
                    'success' => false,
                    'index' => $index,
                    'user_id' => $requestData['user_id'],
                    'error' => "Insufficient leave balance. Available: {$leaveBalance->days_remaining}, Requested: {$daysRequested}"
                ];
            }
        }

        // Create the leave request
        $leaveRequest = LeaveRequest::create([
            'user_id' => $requestData['user_id'],
            'leave_type_id' => $requestData['leave_type_id'],
            'start_date' => $requestData['start_date'],
            'end_date' => $requestData['end_date'],
            'days_requested' => $daysRequested,
            'status' => $requestData['status'],
            'reason' => $requestData['reason'] ?? null,
            'reviewed_by' => ($requestData['status'] !== 'pending') ? Auth::id() : null,
            'reviewed_at' => ($requestData['status'] !== 'pending') ? now() : null,
            'review_comments' => $requestData['review_comments'] ?? null,
        ]);

        // Auto-deduct from leave balance if approved and auto_deduct is enabled
        if (($requestData['status'] === 'approved') && ($requestData['auto_deduct_balance'] ?? false)) {
            $leaveBalance = LeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $requestData['leave_type_id'])
                ->first();

            if ($leaveBalance) {
                $leaveBalance->update([
                    'days_remaining' => $leaveBalance->days_remaining - $daysRequested
                ]);
            }
        }

        return [
            'success' => true,
            'index' => $index,
            'user_id' => $requestData['user_id'],
            'leave_request_id' => $leaveRequest->id,
            'days_requested' => $daysRequested
        ];
    }

    /**
     * Import leave requests from CSV file
     */
    public function bulkImport(Request $request)
    {
        // Check if user has admin/HR permissions
      //  $this->authorize('bulkCreate', LeaveRequest::class);

        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
            'override_balance_check' => 'boolean',
            'auto_deduct_balance' => 'boolean',
        ]);

        try {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getPathname()));
            $headers = array_shift($csvData); // Remove header row

            // Expected CSV headers: user_email, leave_type_name, start_date, end_date, status, reason, review_comments
            $expectedHeaders = ['user_email', 'leave_type_name', 'start_date', 'end_date', 'status', 'reason', 'review_comments'];

            // Validate headers
            $missingHeaders = array_diff($expectedHeaders, $headers);
            if (!empty($missingHeaders)) {
                return redirect()->back()
                    ->with('error', 'Missing required CSV headers: ' . implode(', ', $missingHeaders));
            }

            $requests = [];
            $errors = [];

            foreach ($csvData as $index => $row) {
                $rowData = array_combine($headers, $row);

                // Find user by email
                $user = User::where('email', $rowData['user_email'])->first();
                if (!$user) {
                    $errors[] = "Row " . ($index + 2) . ": User not found with email {$rowData['user_email']}";
                    continue;
                }

                // Find leave type by name and user's leave group
                $leaveType = null;
                if ($user->leaveGroup) {
                    $leaveType = $user->leaveGroup->leaveTypes()
                        ->where('leave_name', $rowData['leave_type_name'])
                        ->first();
                }

                if (!$leaveType) {
                    $errors[] = "Row " . ($index + 2) . ": Leave type '{$rowData['leave_type_name']}' not found for user's leave group";
                    continue;
                }

                // Validate dates
                try {
                    $startDate = Carbon::parse($rowData['start_date'])->format('Y-m-d');
                    $endDate = Carbon::parse($rowData['end_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid date format";
                    continue;
                }

                // Validate status
                if (!in_array($rowData['status'], ['pending', 'approved', 'rejected'])) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid status '{$rowData['status']}'";
                    continue;
                }

                $requests[] = [
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $rowData['status'],
                    'reason' => $rowData['reason'] ?? '',
                    'review_comments' => $rowData['review_comments'] ?? '',
                    'override_balance_check' => $validated['override_balance_check'] ?? false,
                    'auto_deduct_balance' => $validated['auto_deduct_balance'] ?? false,
                ];
            }

            if (!empty($errors)) {
                return redirect()->back()
                    ->with('csv_errors', $errors)
                    ->with('error', 'CSV validation failed. Please fix the errors and try again.');
            }

            // Process the requests using the existing bulk store logic
            $fakeRequest = new Request();
            $fakeRequest->merge(['requests' => $requests]);

            return $this->bulkStore($fakeRequest);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'CSV import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template for bulk import
     */
    public function downloadTemplate()
    {
      //  $this->authorize('bulkCreate', LeaveRequest::class);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leave_requests_template.csv"',
        ];

        $csvData = [
            ['user_email', 'leave_type_name', 'start_date', 'end_date', 'status', 'reason', 'review_comments'],
            ['john.doe@company.com', 'Annual Leave', '2024-12-01', '2024-12-05', 'approved', 'Year-end vacation', 'Approved for year-end'],
            ['jane.smith@company.com', 'Sick Leave', '2024-11-15', '2024-11-16', 'approved', 'Medical appointment', 'Medical certificate provided'],
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculate leave days excluding weekends and public holidays
     */
    private function calculateLeaveDays($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Get public holidays between the dates
        $publicHolidays = PublicHoliday::getHolidaysBetweenDates($startDate, $endDate);
        $publicHolidayDates = $publicHolidays->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        $daysRequested = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Count only weekdays that are not public holidays
            $dayOfWeek = $date->dayOfWeek;

            if ($date->dayOfWeek !== 5 && $date->dayOfWeek !== 6 && !in_array($date->format('Y-m-d'), $publicHolidayDates)) {
                $daysRequested++;
            }
        }

        return $daysRequested;
    }
    /**
     * Get working days count for AJAX requests (for frontend calculations)
     */
    /**
     * Get leave days breakdown for preview (AJAX endpoint)
     */
    public function calculateDaysPreview(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $weekends = 0;
        $publicHolidays = 0;
        $leaveDays = 0;

        // Get public holidays between the dates
        $publicHolidaysData = PublicHoliday::getHolidaysBetweenDates($startDate, $endDate);
        $publicHolidayDates = $publicHolidaysData->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        $breakdown = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayInfo = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->format('l'),
                'type' => 'leave_day'
            ];

            if ($date->dayOfWeek === 5 || $date->dayOfWeek === 6){
                $weekends++;
                $dayInfo['type'] = 'weekend';
            } elseif (in_array($date->format('Y-m-d'), $publicHolidayDates)) {
                $publicHolidays++;
                $dayInfo['type'] = 'public_holiday';
                $holiday = $publicHolidaysData->where('date', $date->format('Y-m-d'))->first();
                $dayInfo['holiday_name'] = $holiday ? $holiday->name : '';
            } else {
                $leaveDays++;
            }

            $breakdown[] = $dayInfo;
        }

        return response()->json([
            'total_days' => $totalDays,
            'weekends' => $weekends,
            'public_holidays' => $publicHolidays,
            'leave_days' => $leaveDays,
            'breakdown' => $breakdown
        ]);
    }



}





