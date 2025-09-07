@extends('admin.admin_master')
@section('admin')

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-calendar-alt"></i> My Leave Requests</h4>
                <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Request
                </a>
            </div>
            <div class="card-body">
                @if($leaveRequests->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Leave Requests</h5>
                        <p class="text-muted">You haven't submitted any leave requests yet.</p>
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Submit Your First Request
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table  id="example" class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Duration</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Documentation</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveRequests as $request)
                                <tr>
                                    <td>
                                        <strong>{{ $request->leaveType->leave_name }}</strong>
                                        @if($request->leaveType->requires_documentation)
                                            <i class="fas fa-paperclip text-info ms-1"
                                               title="Documentation required"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</div>
                                        @if($request->start_date->diffInDays($request->end_date) > 7)
                                            <small class="text-muted">Long duration</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $request->days_requested }} days</span>
                                    </td>
                                    <td>
                                        @switch($request->status)
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Approved
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times"></i> Rejected
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($request->documentation_path)
                                            <span class="badge bg-success">
                                                <i class="fas fa-file-check"></i> Uploaded
                                            </span>
                                        @else
                                            @if($request->leaveType->requires_documentation)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> Required
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted">
                                                    <i class="fas fa-file-minus"></i> None
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $request->created_at->format('M d, Y') }}<br>
                                            {{ $request->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('leave-requests.show', $request) }}"
                                               class="btn btn-sm btn-warning" title="View Details">
                                                <i class="fadeIn animated bx bx-show-alt"></i>
                                            </a>

                                            @if($request->status === 'pending')
                                                <!-- Edit Button (for pending requests) -->
                                                <a href="{{ route('leave-requests.edit', $request) }}"
                                                   class="btn btn-sm btn-primary" title="Edit Request">
                                                    <i class="fadeIn animated bx bx-edit-alt"></i>
                                                </a>

                                                <!-- Cancel Button -->
                                                <form method="POST" action="{{ route('leave-requests.cancel', $request) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Cancel Request">
                                                        <i class="fadeIn animated bx bx-shield-x"></i>
                                                    </button>
                                                </form>

                                                <!-- Documentation Status Alert for Pending -->
                                                @if($request->leaveType->requires_documentation && !$request->documentation_path)
                                                    <div class="w-100 mt-2">
                                                        <div class="alert alert-warning alert-sm mb-0 py-1">
                                                            <small>
                                                                <i class="fadeIn animated bx bx-cloud-download"></i>
                                                                <strong>Action Required:</strong>
                                                                Upload documentation for approval
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            @if($request->documentation_path)
                                                <!-- Download Documentation -->
                                                <a href="{{ route('leave-requests.download-documentation', $request) }}"
                                                   class="btn btn-sm btn-success" title="Download Documentation">
                                                    <i class="fadeIn animated bx bx-cloud-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>

                    <!-- Statistics Card -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Request Summary</h6>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="stat-item">
                                                <h4 class="text-primary">{{ $leaveRequests->count() }}</h4>
                                                <small class="text-muted">Total Requests</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-item">
                                                <h4 class="text-warning">{{ $leaveRequests->where('status', 'pending')->count() }}</h4>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-item">
                                                <h4 class="text-success">{{ $leaveRequests->where('status', 'approved')->count() }}</h4>
                                                <small class="text-muted">Approved</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="stat-item">
                                                <h4 class="text-danger">{{ $leaveRequests->where('status', 'rejected')->count() }}</h4>
                                                <small class="text-muted">Rejected</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.alert-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.stat-item h4 {
    margin-bottom: 0.25rem;
}
.table td {
    vertical-align: middle;
}
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush
