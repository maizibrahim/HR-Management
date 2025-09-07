@extends('admin.admin_master')
@section('admin')

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-clipboard-check"></i> Pending Leave Approvals</h4>
            </div>
            <div class="card-body">
                @if($pendingRequests->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-muted">No Pending Approvals</h5>
                        <p class="text-muted">All leave requests have been processed.</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($pendingRequests as $request)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-start border-warning border-4">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user"></i> {{ $request->user->name }}
                                        </h6>
                                        <small class="text-muted">
                                            Submitted {{ $request->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Leave Details -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Leave Type:</strong><br>
                                            <span class="badge bg-info">{{ $request->leaveType->leave_name }}</span>
                                            @if($request->leaveType->requires_documentation)
                                                <i class="fas fa-paperclip text-warning ms-1"
                                                   title="Documentation required"></i>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Duration:</strong><br>
                                            {{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}
                                            <br><small class="text-muted">{{ $request->days_requested }} working days</small>
                                        </div>
                                    </div>

                                    @if($request->reason)
                                    <div class="mb-3">
                                        <strong>Reason:</strong>
                                        <p class="text-muted mb-0">{{ $request->reason }}</p>
                                    </div>
                                    @endif

                                    <!-- Documentation Status -->
                                    <div class="mb-3">
                                        <strong>Documentation:</strong>
                                        @if($request->documentation_path)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-file-check"></i> Uploaded
                                                </span>
                                                <a href="{{ route('leave-requests.download-documentation', $request) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        @else
                                            @if($request->leaveType->requires_documentation)
                                                <div class="alert alert-warning alert-sm mb-0">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <strong>Documentation Required:</strong> Employee must upload documentation before approval.
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted">
                                                    <i class="fas fa-file-minus"></i> Not required
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Employee Balance Info -->
                                    @php
                                        $balance = $request->user->leaveBalances()
                                            ->where('leave_type_id', $request->leave_type_id)
                                            ->first();
                                        $remainingBalance = $balance ? $balance->days_remaining - $request->days_requested : 0;
                                    @endphp
                                    <div class="mb-3">
                                        <strong>Leave Balance:</strong>
                                        <div class="d-flex justify-content-between">
                                            <span>Current: {{ $balance ? $balance->days_remaining : 0 }} days</span>
                                            <span class="{{ $remainingBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                After approval: {{ $remainingBalance }} days
                                            </span>
                                        </div>
                                        @if($remainingBalance < 0)
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Insufficient balance
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('leave-requests.show', $request) }}"
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        @if($request->leaveType->requires_documentation && !$request->documentation_path)
                                            <!-- Cannot approve without documentation -->
                                            <button class="btn btn-sm btn-secondary" disabled title="Documentation required">
                                                <i class="fas fa-clock"></i> Waiting for docs
                                            </button>
                                        @elseif($remainingBalance < 0)
                                            <!-- Show warning for insufficient balance but allow approval -->
                                            <button type="button" class="btn btn-sm btn-warning"
                                                    onclick="showApprovalModal({{ $request->id }}, true)">
                                                <i class="fas fa-exclamation-triangle"></i> Approve (Deficit)
                                            </button>
                                        @else
                                            <!-- Normal approval -->
                                            <button type="button" class="btn btn-sm btn-success"
                                                    onclick="showApprovalModal({{ $request->id }}, false)">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        @endif

                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="showRejectModal({{ $request->id }})">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div id="deficitWarning" class="alert alert-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> This approval will result in a negative leave balance for the employee.
                    </div>
                    <div class="mb-3">
                        <label for="approval_comments" class="form-label">Comments (Optional)</label>
                        <textarea class="form-control" id="approval_comments" name="review_comments"
                                  rows="3" placeholder="Add any comments for the employee"></textarea>
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
            <div class="modal-header">
                <h5 class="modal-title">Reject Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label">Reason for Rejection *</label>
                        <textarea class="form-control" id="reject_reason" name="review_comments"
                                  rows="3" placeholder="Please provide a reason for rejecting this request" required></textarea>
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

@endsection

@push('scripts')
<script>
function showApprovalModal(requestId, hasDeficit) {
    const form = document.getElementById('approvalForm');
    const deficitWarning = document.getElementById('deficitWarning');

    form.action = `/leave-requests/${requestId}/approve`;
    deficitWarning.style.display = hasDeficit ? 'block' : 'none';

    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
}

function showRejectModal(requestId) {
    const form = document.getElementById('rejectForm');
    form.action = `/leave-requests/${requestId}/reject`;

    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Clear form data when modals are hidden
document.getElementById('approvalModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('approval_comments').value = '';
});

document.getElementById('rejectModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('reject_reason').value = '';
});
</script>
@endpush

@push('styles')
<style>
.alert-sm {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}
.border-4 {
    border-width: 4px !important;
}
.card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush
