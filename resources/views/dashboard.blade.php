@extends('admin.admin_master')
@section('admin')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="text-muted">Welcome back, {{ Auth::user()->name }}</p>
    </div>
</div>

<div class="row mb-4">
    <!-- Employee Dashboard Cards -->
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                <h5>{{ Auth::user()->leaveBalances->sum('days_remaining') }}</h5>
                <p class="text-muted mb-0">Total Leave Days</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h5>{{ Auth::user()->leaveRequests()->where('status', 'pending')->count() }}</h5>
                <p class="text-muted mb-0">Pending Requests</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h5>{{ Auth::user()->leaveRequests()->where('status', 'approved')->count() }}</h5>
                <p class="text-muted mb-0">Approved Requests</p>
            </div>
        </div>
    </div>

    @if(Auth::user()->subordinates->count() > 0)
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-info mb-2"></i>
                    <h5>{{ Auth::user()->subordinates()->whereHas('leaveRequests', function($q) { $q->where('status', 'pending'); })->count() }}</h5>
                    <p class="text-muted mb-0">Team Approvals</p>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <!-- Leave Balance Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-chart-pie"></i> My Leave Balance</h6>
            </div>
            <div class="card-body">
                @if(Auth::user()->leaveBalances->count() > 0)
                    @foreach(Auth::user()->leaveBalances as $balance)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>{{ $balance->leaveType->name }}</strong>
                                <br>
                                <small class="text-muted">Resets: {{ $balance->reset_date->format('M d, Y') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $balance->days_remaining > 5 ? 'success' : ($balance->days_remaining > 0 ? 'warning' : 'danger') }} fs-6">
                                    {{ $balance->days_remaining }} / {{ $balance->leaveType->days_allowed }} days
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-3">
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Request Leave
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-info-circle"></i>
                        <p>No leave balances assigned. Contact HR.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-history"></i> Recent Activity</h6>
            </div>
            <div class="card-body">
                @php
                    $recentRequests = Auth::user()->leaveRequests()->latest()->take(5)->get();
                @endphp

                @if($recentRequests->count() > 0)
                    @foreach($recentRequests as $request)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $request->leaveType->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    @endforeach

                    <div class="text-center mt-3">
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-primary btn-sm">
                            View All Requests
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-calendar-times"></i>
                        <p>No leave requests yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->subordinates->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-clipboard-list"></i> Team Leave Requests Requiring Action</h6>
                </div>
                <div class="card-body">
                    @php
                        $teamPendingRequests = \App\Models\LeaveRequest::whereHas('user', function($q) {
                            $q->where('supervisor_id', Auth::id());
                        })->where('status', 'pending')->with(['user', 'leaveType'])->latest()->get();
                    @endphp

                    @if($teamPendingRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Leave Type</th>
                                        <th>Dates</th>
                                        <th>Days</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamPendingRequests->take(10) as $request)
                                        <tr>
                                            <td>{{ $request->user->name }}</td>
                                            <td><span class="badge bg-info">{{ $request->leaveType->name }}</span></td>
                                            <td>{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d') }}</td>
                                            <td>{{ $request->days_requested }}</td>
                                            <td>
                                                <a href="{{ route('leave-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('leave-requests.approval-list') }}" class="btn btn-warning">
                                <i class="fas fa-clipboard-check"></i> View All Pending ({{ $teamPendingRequests->count() }})
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-check-circle"></i>
                            <p>No pending leave requests from your team.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
