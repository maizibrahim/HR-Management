@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->

            <!--end breadcrumb-->

             <div class="card-body">
                    <div class="mb-4">
                        <h6>leave Request Detail</h6>
                        <span class="badge bg-{{ $leaveRequest->status == 'approved' ? 'success' : ($leaveRequest->status == 'rejected' ? 'danger' : 'warning') }} fs-6">
                        {{ ucfirst($leaveRequest->status) }}
                    </span>
                    </div>
                    <div class="col-md-6">
                        <h6>Request Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Employee:</strong></td>
                                <td>{{ $leaveRequest->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Leave Type:</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $leaveRequest->leaveType->leave_name }}</span>
                                </td>
                                <td><strong>Day Counting Method:</strong></td>
                                <td>
                                     @if($leaveRequest->leaveType->count_type === 'all_days')
                                    <span class="badge bg-info">All Days (Inc. Weekends)</span>
                                @else
                                    <span class="badge bg-secondary">Weekdays Only</span>
                                @endif
                                </td>

                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td>{{ $leaveRequest->start_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td>{{ $leaveRequest->end_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Days Requested:</strong></td>
                                <td><span class="badge bg-primary">{{ $leaveRequest->days_requested }} days</span></td>
                            </tr>
                            <tr>
                                <td><strong>Submitted:</strong></td>
                                <td>{{ $leaveRequest->created_at->format('M d, Y g:i A') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6>Review Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $leaveRequest->status == 'approved' ? 'success' : ($leaveRequest->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($leaveRequest->status) }}
                                    </span>
                                </td>
                            </tr>
                            @if($leaveRequest->reviewer)
                                <tr>
                                    <td><strong>Reviewed By:</strong></td>
                                    <td>{{ $leaveRequest->reviewer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reviewed At:</strong></td>
                                    <td>{{ $leaveRequest->reviewed_at->format('M d, Y g:i A') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($leaveRequest->reason)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Employee's Reason</h6>
                            <div class="alert alert-light">
                                {{ $leaveRequest->reason }}
                            </div>
                        </div>
                    </div>
                @endif

                @if($leaveRequest->review_comments)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Review Comments</h6>
                            <div class="alert alert-{{ $leaveRequest->status == 'approved' ? 'success' : 'danger' }}">
                                {{ $leaveRequest->review_comments }}
                            </div>
                        </div>
                    </div>
                @endif

                @if($leaveRequest->documentation_path)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Documentation</h6>
                            <a href="{{ Storage::url($leaveRequest->documentation_path) }}"
                               target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-file"></i> View Attached Document
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Requests
                    </a>

                    @if($leaveRequest->status == 'pending')
                        @can('review', $leaveRequest)
                            <div>
                                <button type="button" class="btn btn-success me-2"
                                        data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        @endcan

                        @can('cancel', $leaveRequest)
                            <form method="POST" action="{{ route('leave-requests.cancel', $leaveRequest) }}"
                                  style="display: inline;"
                                  onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i> Cancel Request
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>


@if($leaveRequest->status == 'pending' && Gate::allows('review', $leaveRequest))
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Leave Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('leave-requests.approve', $leaveRequest) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
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
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Leave Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('leave-requests.reject', $leaveRequest) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
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



                </div>
            </div>
        </div>
    </div>
</div>


    <!--end page wrapper -->
@endif
@endsection
