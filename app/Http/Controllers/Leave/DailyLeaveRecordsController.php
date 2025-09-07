<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\leave\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyLeaveRecordsController extends Controller
{
    /**
     * Display daily leave records with filtering options
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::query()
            ->with(['user', 'leaveType', 'reviewer'])
            ->where('status', 'approved');

        // Get filter parameters
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $employeeName = $request->input('employee_name');
        $leaveTypeId = $request->input('leave_type_id');
        $departmentId = $request->input('department_id');

        // Filter by date - check if the selected date falls within the leave period
        if ($selectedDate) {
            $query->whereDate('start_date', '<=', $selectedDate)
                  ->whereDate('end_date', '>=', $selectedDate);
        }

        // Filter by employee name
        if ($employeeName) {
            $query->whereHas('user', function ($q) use ($employeeName) {
                $q->where('name', 'LIKE', '%' . $employeeName . '%')
                  ->orWhere('email', 'LIKE', '%' . $employeeName . '%');
            });
        }

        // Filter by leave type
        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        // Filter by department (assuming you add department_id to users table)
        if ($departmentId) {
            $query->whereHas('user', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $leaveRecords = $query->orderBy('start_date', 'desc')
                             ->paginate(20)
                             ->withQueryString();

        // Get filter options for dropdowns
        $leaveTypes = \App\Models\leave\LeaveType::all();
        $employees = User::select('id', 'name', 'email')->orderBy('name')->get();

        // Calculate statistics for the selected date
        $statistics = $this->calculateDailyStatistics($selectedDate);

        return view('hr.leave-records.index', compact(
            'leaveRecords',
            'selectedDate',
            'employeeName',
            'leaveTypeId',
            'departmentId',
            'leaveTypes',
            'employees',
            'statistics'
        ));
    }

    /**
     * Export daily leave records to CSV
     */
    public function export(Request $request)
    {
        $query = LeaveRequest::query()
            ->with(['user', 'leaveType', 'reviewer'])
            ->where('status', 'approved');

        // Apply same filters as index method
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $employeeName = $request->input('employee_name');
        $leaveTypeId = $request->input('leave_type_id');

        if ($selectedDate) {
            $query->whereDate('start_date', '<=', $selectedDate)
                  ->whereDate('end_date', '>=', $selectedDate);
        }

        if ($employeeName) {
            $query->whereHas('user', function ($q) use ($employeeName) {
                $q->where('name', 'LIKE', '%' . $employeeName . '%');
            });
        }

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        $leaveRecords = $query->orderBy('start_date', 'desc')->get();

        $fileName = 'leave_records_' . $selectedDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($leaveRecords) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Employee Name',
                'Email',
                'Leave Type',
                'Start Date',
                'End Date',
                'Days Requested',
                'Reason',
                'Approved By',
                'Approved Date'
            ]);

            // CSV Data
            foreach ($leaveRecords as $record) {
                fputcsv($file, [
                    $record->user->name,
                    $record->user->email,
                    $record->leaveType->leave_name,
                    $record->start_date->format('Y-m-d'),
                    $record->end_date->format('Y-m-d'),
                    $record->days_requested,
                    $record->reason ?? 'N/A',
                    $record->reviewer->name ?? 'N/A',
                    $record->reviewed_at ? $record->reviewed_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get leave records for a specific date (AJAX endpoint)
     */
    public function getRecordsForDate(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $records = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->get();

        $statistics = $this->calculateDailyStatistics($date);

        return response()->json([
            'records' => $records,
            'statistics' => $statistics,
            'date' => $date
        ]);
    }

    /**
     * Calculate daily statistics
     */
    private function calculateDailyStatistics($date)
    {
        $totalOnLeave = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->count();

        $totalEmployees = User::count();
        $presentEmployees = $totalEmployees - $totalOnLeave;

        // Leave breakdown by type
        $leaveBreakdown = LeaveRequest::select('leave_type_id', DB::raw('count(*) as count'))
            ->with('leaveType')
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->groupBy('leave_type_id')
            ->get();

        return [
            'total_employees' => $totalEmployees,
            'present_employees' => $presentEmployees,
            'total_on_leave' => $totalOnLeave,
            'leave_breakdown' => $leaveBreakdown,
            'attendance_percentage' => $totalEmployees > 0 ? round(($presentEmployees / $totalEmployees) * 100, 2) : 0
        ];
    }

    /**
     * Show detailed view of a specific leave record
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['user', 'leaveType', 'reviewer']);

        return view('hr.leave-records.show', compact('leaveRequest'));
    }

    /**
     * Generate monthly leave report
     */
    public function monthlyReport(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        // Get all approved leave requests for the month
        $leaveRecords = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->orderBy('start_date')
            ->get();

        // Calculate monthly statistics
        $monthlyStats = [
            'total_leave_days' => $leaveRecords->sum('days_requested'),
            'total_requests' => $leaveRecords->count(),
            'unique_employees' => $leaveRecords->pluck('user_id')->unique()->count(),
            'leave_types_used' => $leaveRecords->pluck('leave_type_id')->unique()->count()
        ];

        return view('hr.leave-records.monthly-report', compact(
            'leaveRecords',
            'monthlyStats',
            'month',
            'startDate',
            'endDate'
        ));
    }
}
