@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-clipboard-check"></i> Pending Leave Approvals</h3>
            <span class="badge bg-warning fs-6">{{ $pendingRequests->count() }} Pending</span>
        </div>

 @if($pendingRequests->count() > 0)
    <div class="row">
        @foreach($pendingRequests as $request)
            <div class="col-md-6 mb-4">
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-user"></i> {{ $request->user->name }}
                            </h6>
                            <span class="badge bg-info">{{ $request->leaveType->leave_name }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Start Date</small>
                                <div><strong>{{ $request->start_date->format('d M, Y') }}</strong></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">End Date</small>
                                <div><strong>{{ $request->end_date->format('d M, Y') }}</strong></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Days Requested</small>
                                <div><span class="badge bg-primary">{{ $request->days_requested }} days</span></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Submitted</small>
                                <div>{{ $request->created_at->format('d M, Y') }}</div>
                            </div>
                        </div>

                        @if($request->reason)
                            <div class="mb-3">
                                <small class="text-muted">Reason</small>
                                <div class="border rounded p-2 bg-light">{{ $request->reason }}</div>
                            </div>
                        @endif

                        @if($request->documentation_path)
                            <div class="mb-3">
                                <small class="text-muted">Documentation</small>
                                <div>
                                    <a href="{{ Storage::url($request->documentation_path) }}"
                                       target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file"></i> View Document
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Employee Leave Balance -->
                        <div class="mb-3">
                            <small class="text-muted">Employee's Balance</small>
                            <div>
                                @php
                                    $balance = $request->user->leaveBalances()
                                        ->where('leave_type_id', $request->leave_type_id)
                                        ->first();
                                @endphp
                                @if($balance)
                                    <span class="badge bg-{{ $balance->days_remaining >= $request->days_requested ? 'success' : 'danger' }}">
                                        {{ $balance->days_remaining }} days remaining
                                    </span>
                                @else
                                    <span class="badge bg-danger">No balance</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#approveModal{{ $request->id }}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button type="button" class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $request->id }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            <a href="{{ route('leave-requests.show', $request) }}"
                               class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Approve Leave Request</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('leave-requests.approve', $request) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <p><strong>Employee:</strong> {{ $request->user->name }}</p>
                                <p><strong>Leave Type:</strong> {{ $request->leaveType->leave_name }}</p>
                                <p><strong>Dates:</strong> {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}</p>
                                <p><strong>Days:</strong> {{ $request->days_requested }}</p>

                                <div class="mb-3">
                                    <label for="review_comments" class="form-label">Comments (Optional)</label>
                                    <textarea class="form-control" name="review_comments" rows="3"
                                              placeholder="Add any comments about this approval..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Approve Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Reject Leave Request</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('leave-requests.reject', $request) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <p><strong>Employee:</strong> {{ $request->user->name }}</p>
                                <p><strong>Leave Type:</strong> {{ $request->leaveType->leave_name }}</p>
                                <p><strong>Dates:</strong> {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}</p>

                                <div class="mb-3">
                                    <label for="review_comments" class="form-label">Reason for Rejection *</label>
                                    <textarea class="form-control" name="review_comments" rows="3"
                                              placeholder="Please explain why this request is being rejected..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Reject Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
        <h5>No Pending Approvals</h5>
        <p class="text-muted">All leave requests from your team have been reviewed.</p>
    </div>
@endif

         </div>
    </div>


@endsection


