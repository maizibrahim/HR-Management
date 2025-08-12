@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">



<div class="card-header">
                <h4><i class="fas fa-calendar-plus"></i> Submit Leave Request</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="leave_type_id" class="form-label">Leave Type *</label>
                                <select class="form-select @error('leave_type_id') is-invalid @enderror"
                                        id="leave_type_id" name="leave_type_id" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}"
                                                data-requires-docs="{{ $type->requires_documentation ? 'true' : 'false' }}"
                                                {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->leave_name }}
                                            @if(isset($leaveBalances[$type->id]))
                                                ({{ $leaveBalances[$type->id]->days_remaining }} days remaining)
                                            @else
                                                (No balance)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Available Balance</label>
                                <div class="alert alert-info" id="balance-info">
                                    Select a leave type to see your balance
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date" value="{{ old('start_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date" value="{{ old('end_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="3"
                                  placeholder="Optional: Provide additional details about your leave request">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="documentation-section" style="display: none;">
                        <label for="documentation" class="form-label">
                            Documentation <span class="text-danger" id="docs-required">*</span>
                        </label>
                        <input type="file" class="form-control @error('documentation') is-invalid @enderror"
                               id="documentation" name="documentation" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">
                            Upload supporting documents (PDF, JPG, PNG). Maximum file size: 10MB.
                        </div>
                        @error('documentation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Your leave request will be sent to your supervisor for approval.
                        Only weekdays (Monday-Friday) are counted as leave days.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const leaveTypeSelect = document.getElementById('leave_type_id');
    const balanceInfo = document.getElementById('balance-info');
    const documentationSection = document.getElementById('documentation-section');
    const docsRequired = document.getElementById('docs-required');

    const balances = @json($leaveBalances);

    leaveTypeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const leaveTypeId = this.value;
        const requiresDocs = selectedOption.getAttribute('data-requires-docs') === 'true';

        // Update balance info
        if (leaveTypeId && balances[leaveTypeId]) {
            const balance = balances[leaveTypeId];
            balanceInfo.innerHTML = `<strong>${balance.days_remaining}</strong> days remaining`;
            balanceInfo.className = `alert ${balance.days_remaining > 5 ? 'alert-success' : (balance.days_remaining > 0 ? 'alert-warning' : 'alert-danger')}`;
        } else if (leaveTypeId) {
            balanceInfo.innerHTML = '<strong>No balance available</strong>';
            balanceInfo.className = 'alert alert-danger';
        } else {
            balanceInfo.innerHTML = 'Select a leave type to see your balance';
            balanceInfo.className = 'alert alert-info';
        }

        // Show/hide documentation section
        if (requiresDocs) {
            documentationSection.style.display = 'block';
            docsRequired.style.display = 'inline';
            document.getElementById('documentation').required = true;
        } else {
            documentationSection.style.display = 'none';
            docsRequired.style.display = 'none';
            document.getElementById('documentation').required = false;
        }
    });

    // Trigger change event if there's a selected value (for form validation errors)
    if (leaveTypeSelect.value) {
        leaveTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
