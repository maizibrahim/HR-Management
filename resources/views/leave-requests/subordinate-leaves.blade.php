@extends('admin.admin_master')
@section('admin')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="container">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Team Leave Management</h1>
                <p class="text-gray-600 mt-2">View and manage leave requests from your team members</p>
            </div>

            <div class="col-lg-13 mx-auto">

                <div class="card">
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('leave-requests.subordinate-leaves') }}">

                            <div class="row">

                                <!-- Employee Dropdown -->
                                <div class="col">
                                    <label for="employee" class="form-label">Employee Name</label>
                                    <select id="employee_id" class="form-select" name="employee_id">
                                        <option value="">Select Employee</option>
                                        @foreach ($subordinates as $subordinate)
                                            <option value="{{ $subordinate->id }}"
                                                {{ $selectedEmployeeId == $subordinate->id ? 'selected' : '' }}>
                                                {{ $subordinate->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Employee Name Filter
                                        <div class="col">
                                            <label for="leave_code" class="form-label">Employee Name</label>
                                            <input id="employee_name" type="text" class="form-control" name="employee_name"
                                                value="{{ request('employee_name') }}" placeholder="Search by name...">
                                        </div>
                                        -->

                                <!-- Status Filter -->
                                <div class="col">
                                    <label for="leave_group_id" class="form-label">Status </label>
                                    <select id="status" class="form-select" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="leave_code" class="form-label">From Date</label>
                                    <input id="start_date" type="date" class="form-control" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>

                                <!-- End Date Filter -->
                                <div class="col">
                                    <label for="leave_code" class="form-label">To Date</label>
                                    <input id="end_date" type="date" class="form-control" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>

                            </div>

                            <br>
                            <div class="row">
                                <!-- Filter Buttons -->
                                <div class="flex gap-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('leave-requests.subordinate-leaves') }}"
                                        class="btn btn-secondary px-4">
                                        Clear Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($selectedEmployee)
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-6 mb-6 border border-blue-200">


                        <div class="card">
                            <div class="card-body">
                                <!-- Leave Type Summary -->
                                @if ($employeeLeaveTypeSummary->isNotEmpty())
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Leave Usage Summary by Type
                                        </h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="bg-primary text-center text-white"> Leave Type </th>
                                                        <th class="bg-success text-center text-white"> Approved </th>
                                                        <th class="bg-warning text-center text-dark"> Pending </th>
                                                        <th class="bg-danger text-center text-white"> Rejected </th>
                                                        <th class="bg-info text-center text-white"> Total Requests </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employeeLeaveTypeSummary as $summary)
                                                        <tr >
                                                            <td class="bg-primary text-center">
                                                                <span class="text-center text-white">{{ $summary->leave_name }}</span>
                                                            </td>
                                                            <td class="bg-success text-center">
                                                                <div>
                                                                    <span class="text-center text-white">{{ $summary->approved_days }}</span>
                                                                    <span class="text-center text-white">days</span>
                                                                </div>
                                                                <div class="text-center text-white" >
                                                                    ({{ $summary->approved_count }}
                                                                    {{ Str::plural('request', $summary->approved_count) }})
                                                                </div>
                                                            </td>
                                                            <td class="bg-warning text-center">
                                                                <div class="text-center text-dark">
                                                                    <span>{{ $summary->pending_days }}</span>
                                                                    <span >days</span>
                                                                </div>
                                                                <div class="text-center text-dark" >
                                                                    ({{ $summary->pending_count }}
                                                                    {{ Str::plural('request', $summary->pending_count) }})
                                                                </div>
                                                            </td>
                                                            <td class="bg-danger text-center">
                                                                <div class="text-center text-white">
                                                                    <span>{{ $summary->rejected_days }}</span>
                                                                    <span >days</span>
                                                                </div>
                                                                <div class="text-center text-white">
                                                                    ({{ $summary->rejected_count }}
                                                                    {{ Str::plural('request', $summary->rejected_count) }})
                                                                </div>
                                                            </td>
                                                            <td class="bg-info text-center">
                                                                <span class="text-center text-dark">
                                                                    {{ $summary->approved_count + $summary->pending_count + $summary->rejected_count }}
                                                                </span>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    <tr >
                                                        <td class="bg-primary text-center text-white"> TOTAL </td>
                                                        <td class="bg-success text-center text-white">
                                                            <span>
                                                                {{ $employeeLeaveTypeSummary->sum('approved_days') }} days
                                                            </span>
                                                        </td>
                                                        <td class="bg-warning text-center text-dark">
                                                            <span >
                                                                {{ $employeeLeaveTypeSummary->sum('pending_days') }} days
                                                            </span>
                                                        </td>
                                                        <td class="bg-danger text-center text-white">
                                                            <span >
                                                                {{ $employeeLeaveTypeSummary->sum('rejected_days') }} days
                                                            </span>
                                                        </td>
                                                        <td class="bg-info text-center text-dark">
                                                            <span>
                                                                {{ $employeeLeaveTypeSummary->sum('approved_count') + $employeeLeaveTypeSummary->sum('pending_count') + $employeeLeaveTypeSummary->sum('rejected_count') }}
                                                            </span>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-white rounded-lg p-6 text-center">
                                        <p class="text-gray-500">No leave requests found for this employee.</p>
                                    </div>
                                @endif
                            </div>
                @endif
            </div>
        </div>

        <!-- Leave Requests Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $request)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div>{{ $request->user->name }}</div>
                                                <div>{{ $request->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{ $request->leaveType->leave_name }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $request->start_date->format('d M, Y') }} </div>
                                        <div>to {{ $request->end_date->format('d M, Y') }} </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $request->days_requested }}</span>
                                    </td>
                                    <td>
                                        @if ($request->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('leave-requests.show', $request) }}"
                                            class="btn btn-sm btn-warning" title="View Data"><i
                                                class="fadeIn animated bx bx-show-alt"></i></a>


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        No leave requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($leaveRequests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>


        </div>
    </div>

    </div>
    </div>
@endsection
