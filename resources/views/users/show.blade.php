@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">View User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">All Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View User</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->
            <div class="col-md-11">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4><i class="fas fa-user"></i> {{ $user->name }}</h4>
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('users.assign-supervisor', $user) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-user-tie"></i> Assign Supervisor
                    </a>
                </div>
            </div>

            <!-- start of body -->
 <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Personal Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Join Date:</strong></td>
                                <td>{{ $user->join_date->format('d M, Y') ?? 'Not Set' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Leave Group:</strong></td>
                                <td>
                                    @if($user->leaveGroup)
                                        <span class="badge bg-info">{{ $user->leaveGroup->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">Not Assigned</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6>Hierarchy</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Supervisor:</strong></td>
                                <td>
                                    @if($user->supervisor)
                                        <span class="badge bg-success">{{ $user->supervisor->name }}</span>
                                    @else
                                        <span class="badge bg-warning">No Supervisor</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Subordinates:</strong></td>
                                <td>
                                    @if($user->subordinates->count() > 0)
                                        @foreach($user->subordinates as $subordinate)
                                            <span class="badge bg-primary me-1">{{ $subordinate->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-calendar-check"></i> Leave Balances</h6>
            </div>
            <div class="card-body">
                @if($user->leaveBalances->count() > 0)
                    @foreach($user->leaveBalances as $balance)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small>{{ $balance->leaveType->leave_name }}</small>
                            <span class="badge bg-{{ $balance->days_remaining > 5 ? 'success' : ($balance->days_remaining > 0 ? 'warning' : 'danger') }}">
                                {{ $balance->days_remaining }} days
                            </span>
                        </div>
                    @endforeach
                    <hr>
                    <a href="{{ route('users.leave-balances', $user) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-cog"></i> Manage Balances
                    </a>
                @else
                    <p class="text-muted text-center">No leave balances assigned</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-history"></i> Recent Leave Requests</h6>
            </div>
            <div class="card-body">
                @if($user->leaveRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Dates</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->leaveRequests->take(10) as $request)
                                    <tr>
                                        <td>{{ $request->leaveType->leave_name}}</td>
                                        <td>{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</td>
                                        <td>{{ $request->days_requested }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">No leave requests found</p>
                @endif
            </div>



            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->
<div class="mt-3">
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>
@endsection
