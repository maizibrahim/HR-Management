@extends('admin.admin_master')
@section('admin')

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Leave Request</h4>
            </div>
            <div class="card-body">
                <!-- Leave Request Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="alert alert-light">
                            <h6 class="mb-2"><i class="fas fa-info-circle"></i> Request Details</h6>
                            <p class="mb-1"><strong>Leave Type:</strong> {{ $leaveRequest->leaveType->leave_name }}</p>
                            <p class="mb-1"><strong>Duration:</strong> {{ $leaveRequest->start_date->format('M d, Y') }} - {{ $leaveRequest->end_date->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Days:</strong> {{ $leaveRequest->days_requested }} working days</p>
                            <p class="mb-0"><strong>Status:</strong>
                                <span class="badge bg-warning">{{ ucfirst($leaveRequest->status) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($leaveRequest->leaveType->requires_documentation)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Documentation Required</strong><br>
                            This leave type requires supporting documentation before approval.
                        </div>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('leave-requests.update', $leaveRequest) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="3"
                                  placeholder="Provide details about your leave request">{{ old('reason', $leaveRequest->reason) }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Documentation Status -->
                    @if($leaveRequest->documentation_path)
                    <div class="mb-3">
                        <label class="form-label">Current Documentation</label>
                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-check"></i>
                                <strong>File uploaded:</strong> {{ basename($leaveRequest->documentation_path) }}
                                <small class="text-muted">
                                    ({{ date('M d, Y H:i', strtotime($leaveRequest->updated_at)) }})
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('leave-requests.download-documentation', $leaveRequest) }}"
                                   class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="remove_existing_doc"
                                           id="remove_existing_doc" value="1">
                                    <label class="form-check-label text-danger" for="remove_existing_doc">
                                        Remove file
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Documentation Upload -->
                    <div class="mb-3">
                        <label for="documentation" class="form-label">
                            @if($leaveRequest->documentation_path)
                                Replace Documentation
                            @else
                                Add Documentation
                            @endif
                            @if($leaveRequest->leaveType->requires_documentation)
                                <span class="text-danger">*</span>
                            @else
                                <span class="text-muted">(Optional)</span>
                            @endif
                        </label>
                        <input type="file" class="form-control @error('documentation') is-invalid @enderror"
                               id="documentation" name="documentation" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">
                            Supported formats: PDF, JPG, PNG. Maximum file size: 10MB.
                        </div>
                        @error('documentation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($leaveRequest->leaveType->requires_documentation && !$leaveRequest->documentation_path)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Important:</strong> Your supervisor cannot approve this request until you upload the required documentation.
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeCheckbox = document.getElementById('remove_existing_doc');
    const fileInput = document.getElementById('documentation');

    // Handle remove existing document checkbox
    if (removeCheckbox) {
        removeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // If removing existing file, make new upload required for required leave types
                @if($leaveRequest->leaveType->requires_documentation)
                    fileInput.required = true;
                @endif
            } else {
                fileInput.required = false;
            }
        });
    }
});
</script>
@endpush
