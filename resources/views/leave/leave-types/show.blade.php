@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->

            <!--end breadcrumb-->

            <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Leave Type: {{ $leaveType->leave_name }}</h5>
                        <div>
                            <a href="{{ route('leave.leave-types.edit', $leaveType) }}" class="btn btn-primary btn-sm me-2">Edit Leave Type</a>
                            <a href="{{ route('leave.leave-types.index') }}" class="btn btn-secondary btn-sm">Back to Leave Types</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <!-- Leave Type Details -->
                        <div class="col-md-6">
                            <h6>Leave Type Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $leaveType->leave_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Leave Group:</strong></td>
                                    <td>
                                        <a href="{{ route('leave.leave-groups.show', $leaveType->leaveGroup) }}" class="text-decoration-none">
                                            {{ $leaveType->leaveGroup->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Days Allowed:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $leaveType->days_allowed }} days</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Requires Documentation:</strong></td>
                                    <td>
                                        @if ($leaveType->requires_documentation)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-file-alt"></i> Required
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times"></i> Not Required
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $leaveType->description ?? 'No description provided' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Timestamps and Statistics -->
                        <div class="col-md-6">
                            <h6>System Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $leaveType->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $leaveType->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Requests:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $leaveType->leaveRequests->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Pending Requests:</strong></td>
                                    <td>
                                        <span class="badge bg-warning">{{ $leaveType->leaveRequests->where('status', 'pending')->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Active Users:</strong></td>
                                    <td>
                                        <span class="badge bg-success">{{ $leaveType->leaveBalances->count() }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Users with this Leave Type -->
                    @if($leaveType->leaveBalances->count() > 0)
                        <div class="mt-4">
                            <h6>Users with this Leave Type ({{ $leaveType->leaveBalances->count() }})</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Email</th>
                                            <th>Days Remaining</th>
                                            <th>Reset Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaveType->leaveBalances as $balance)
                                            <tr>
                                                <td>
                                                    <strong>{{ $balance->user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $balance->user->leaveGroup->name ?? 'No Group' }}</small>
                                                </td>
                                                <td>{{ $balance->user->email }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $balance->days_remaining > 5 ? 'success' : ($balance->days_remaining > 0 ? 'warning' : 'danger') }}">
                                                        {{ $balance->days_remaining }} days
                                                    </span>
                                                </td>
                                                <td>{{ $balance->reset_date->format('M d, Y') }}</td>
                                                <td>
                                                    @if(Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'hr'))
                                                        <a href="{{ route('users.show', $balance->user) }}" class="btn btn-sm btn-outline-primary">View Profile</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No users are currently assigned to this leave type.
                            </div>
                        </div>
                    @endif

                    <!-- Recent Leave Requests -->
                    @if($leaveType->leaveRequests->count() > 0)
                        <div class="mt-4">
                            <h6>Recent Leave Requests (Last 10)</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaveType->leaveRequests->sortByDesc('created_at')->take(10) as $request)
                                            <tr>
                                                <td>
                                                    <strong>{{ $request->user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $request->user->email }}</small>
                                                </td>
                                                <td>{{ $request->start_date->format('M d, Y') }}</td>
                                                <td>{{ $request->end_date->format('M d, Y') }}</td>
                                                <td>{{ $request->days_requested }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'danger') }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    @if(Auth::user() && (Auth::user()->canApproveLeave() || Auth::id() === $request->user_id))
                                                        <a href="{{ route('leave.leave-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($leaveType->leaveRequests->count() > 10)
                                <div class="text-center mt-3">
                                    <small class="text-muted">Showing 10 of {{ $leaveType->leaveRequests->count() }} total requests</small>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No leave requests have been submitted for this leave type yet.
                            </div>
                        </div>
                    @endif

                    <!-- Usage Statistics -->
                    <div class="mt-4">
                        <h6>Usage Statistics</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary">
                                    <div class="card-body text-center">
                                        <h4>{{ $leaveType->leaveRequests->count() }}</h4>
                                        <small>Total Requests</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning">
                                    <div class="card-body text-center">
                                        <h4>{{ $leaveType->leaveRequests->where('status', 'pending')->count() }}</h4>
                                        <small>Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success">
                                    <div class="card-body text-center">
                                        <h4>{{ $leaveType->leaveRequests->where('status', 'approved')->count() }}</h4>
                                        <small>Approved</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-danger">
                                    <div class="card-body text-center">
                                        <h4>{{ $leaveType->leaveRequests->where('status', 'rejected')->count() }}</h4>
                                        <small>Rejected</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <h6>Quick Actions</h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('leave.leave-types.edit', $leaveType) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Leave Type
                            </a>
                            <a href="{{ route('leave.leave-groups.show', $leaveType->leaveGroup) }}" class="btn btn-info">
                                <i class="fas fa-users"></i> View Leave Group
                            </a>
                            @if(Auth::user() && Auth::user()->role === 'admin')
                                <form action="{{ route('leave.leave-types.destroy', $leaveType) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this leave type? This will affect all users with this leave type.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete Leave Type
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



         </div>
    </div>


    <!--end page wrapper -->

@endsection
