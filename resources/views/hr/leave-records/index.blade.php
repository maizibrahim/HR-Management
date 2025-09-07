@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
     <br>       <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Leave Records</h3>
                    <div class="btn-group">
                        <a href="{{ route('hr.leave-records.export', request()->query()) }}"
                           class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                        <a href="{{ route('hr.monthly-leave-report') }}"
                           class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Monthly Report
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['total_employees'] }}</h4>
                                            <p class="mb-0">Total Employees</p>
                                        </div>
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['present_employees'] }}</h4>
                                            <p class="mb-0">Present Today</p>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['total_on_leave'] }}</h4>
                                            <p class="mb-0">On Leave Today</p>
                                        </div>
                                        <i class="fas fa-calendar-times fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['attendance_percentage'] }}%</h4>
                                            <p class="mb-0">Attendance Rate</p>
                                        </div>
                                        <i class="fas fa-percentage fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Filters</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('hr.leave-records.index') }}" id="filterForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="date" class="form-label">Select Date</label>
                                        <input type="date"
                                               class="form-control"
                                               id="date"
                                               name="date"
                                               value="{{ $selectedDate }}"
                                               onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="employee_name" class="form-label">Employee Name</label>
                                        <input type="text"
                                               class="form-control"
                                               id="employee_name"
                                               name="employee_name"
                                               value="{{ $employeeName }}"
                                               placeholder="Search by name or email">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="leave_type_id" class="form-label">Leave Type</label>
                                        <select class="form-control" id="leave_type_id" name="leave_type_id">
                                            <option value="">All Leave Types</option>
                                            @foreach($leaveTypes as $leaveType)
                                                <option value="{{ $leaveType->id }}"
                                                        {{ $leaveTypeId == $leaveType->id ? 'selected' : '' }}>
                                                    {{ $leaveType->leave_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <a href="{{ route('hr.leave-records.index') }}"
                                               class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Leave Records Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Leave Period</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Approved By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveRecords as $record)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2">
                                                    {{ strtoupper(substr($record->user->name, 0, 1)) }}
                                                </div>
                                                <strong>{{ $record->user->name }}</strong>
                                            </div>
                                        </td>
                                         <td>
                                            <span class="badge bg-info">{{ $record->leaveType->leave_name }}</span>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $record->start_date->format('M d, Y') }} -
                                                {{ $record->end_date->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $record->days_requested }} days</span>
                                        </td>
                                        <td>
                                            <span title="{{ $record->reason }}">
                                                {{ Str::limit($record->reason ?? 'N/A', 30) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $record->reviewer->name ?? 'N/A' }}
                                            @if($record->reviewed_at)
                                                <br><small class="text-muted">{{ $record->reviewed_at->format('M d, Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Approved</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('hr.leave-records.show', $record) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No leave records found</h5>
                                                <p class="text-muted">No employees are on leave for the selected date and filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($leaveRecords->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $leaveRecords->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.375rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    margin-left: 0.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when date changes
    document.getElementById('date').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Auto-submit form when employee name is typed (with debounce)
    let employeeNameTimeout;
    document.getElementById('employee_name').addEventListener('input', function() {
        clearTimeout(employeeNameTimeout);
        employeeNameTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
});
</script>
            </div>
            <!--end breadcrumb-->
       </div>
    </div>


@endsection
