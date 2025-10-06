@extends('admin.admin_master')
@section('admin')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="container">
            <div class="card">
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
                                        @foreach ($leaveTypes as $type)
                                            <option value="{{ $type->id }}"
                                                data-requires-docs="{{ $type->requires_documentation ? 'true' : 'false' }}"
                                                data-balance="{{ isset($leaveBalances[$type->id]) ? $leaveBalances[$type->id]->days_remaining : 0 }}"
                                                data-include-weekends="{{ $type->include_weekends ? 'true' : 'false' }}"
                                                data-count-type="{{ $type->count_type }}"
                                                {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>

                                                {{ $type->leave_name }}
                                                @if (isset($leaveBalances[$type->id]))
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
                                        min="{{ date('Y-m-d', strtotime('-2 day')) }}" required>
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



                        <!-- Days Calculation Display -->
                        <div class="mb-3" id="days-info" style="display: none;">
                            <div class="alert alert-light border">
                                <h6 class="mb-2"><i class="fas fa-calculator"></i> Leave Days Calculation</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Total Days:</small>
                                        <div class="fw-bold" id="total-days">0</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Working Days:</small>
                                        <div class="fw-bold text-primary" id="working-days">0</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Weekend Days:</small>
                                        <div class="fw-bold text-secondary" id="weekend-days">0</div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Remaining Balance:</small>
                                        <div class="fw-bold" id="remaining-balance">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason *</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3"
                                placeholder="Provide details about your leave request" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3" id="documentation-field">
                            <label for="documentation" class="form-label">
                                Documentation (Optional)
                                <span class="text-info" id="documentation-note"></span>
                            </label>
                            <input type="file" class="form-control @error('documentation') is-invalid @enderror"
                                id="documentation" name="documentation" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i>
                                You can upload supporting documents now or add them later before supervisor approval.
                                Supported formats: PDF, JPG, PNG. Maximum file size: 10MB.
                            </div>
                            @error('documentation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Your leave request will be sent to your supervisor for approval.
                            Only weekdays are counted as leave days.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-paper-plane"></i> Submit Request
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
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const documentationField = document.getElementById('documentation-field');
            const documentationRequired = document.getElementById('documentation-required');
            const documentationInput = document.getElementById('documentation');
            const daysInfo = document.getElementById('days-info');
            const submitBtn = document.getElementById('submit-btn');
            const balanceInfo = document.getElementById('balance-info');

            // Leave balances data from the controller
            const leaveBalances = @json($leaveBalances);

            // Handle leave type change
            leaveTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const requiresDocumentation = selectedOption.dataset.requiresDocs === 'true';
                const balance = selectedOption.dataset.balance;
                const includeWeekends = selectedOption.dataset.includeWeekends === 'true';
                const countType = selectedOption.dataset.countType || 'working_days';

                // Update documentation note based on leave type
                const documentationNote = document.getElementById('documentation-note');
                if (requiresDocumentation) {
                    documentationNote.innerHTML = '- <strong>Required for this leave type</strong>';
                    documentationNote.className = 'text-warning';
                } else {
                    documentationNote.innerHTML = '';
                    documentationNote.className = 'text-info';
                }

                // Update balance info
                if (this.value) {
                    const dayLabel = countType === 'calendar_days' ? 'calendar days' : 'working days';
                    balanceInfo.innerHTML =
                        `<i class="fas fa-calendar-check"></i> Available Balance: <strong>${balance} ${dayLabel}</strong>`;
                    balanceInfo.className = balance > 0 ? 'alert alert-success' : 'alert alert-warning';
                } else {
                    balanceInfo.innerHTML = 'Select a leave type to see your balance';
                    balanceInfo.className = 'alert alert-info';
                }

                calculateDays();
            });

            // Handle date changes
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
                calculateDays();
            });

            endDateInput.addEventListener('change', calculateDays);

            function calculateDays() {
                const leaveTypeId = leaveTypeSelect.value;
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (!leaveTypeId || !startDate || !endDate) {
                    daysInfo.style.display = 'none';
                    return;
                }

                // Get leave type settings
                const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
                const includeWeekends = selectedOption.dataset.includeWeekends === 'true';
                const countType = selectedOption.dataset.countType || 'working_days';

                // Calculate days based on leave type settings
                const start = new Date(startDate);
                const end = new Date(endDate);
                let totalDays = 0;
                let workingDays = 0;
                let weekendDays = 0;

                for (let date = new Date(start); date <= end; date.setDate(date.getDate() + 1)) {
                    totalDays++;
                    // Check if it's a weekend (Friday=5 or Saturday=6)
                    // Weekend days in this country are Friday and Saturday
                    const isWeekend = date.getDay() === 5 || date.getDay() === 6;

                    if (isWeekend) {
                        weekendDays++;
                    } else {
                        workingDays++;
                    }
                }

                // Determine which count to use based on leave type configuration
                let daysToDeduct;
                let displayLabel;

                if (countType === 'all_days') {
                    // Count every single day (including weekends and public holidays)
                    daysToDeduct = totalDays;
                    displayLabel = 'All Days (including weekends & holidays)';
                } else if (countType === 'calendar_days') {
                    // Use all calendar days
                    daysToDeduct = totalDays;
                    displayLabel = 'Calendar Days';
                } else if (includeWeekends) {
                    // Working days mode but include weekends in the count
                    daysToDeduct = totalDays;
                    displayLabel = 'Total Days (including weekends)';
                } else {
                    // Traditional working days only (exclude weekends)
                    daysToDeduct = workingDays;
                    displayLabel = 'Working Days Only';
                }

                // Update display
                document.getElementById('total-days').textContent = totalDays;
                document.getElementById('working-days').textContent = workingDays;
                document.getElementById('weekend-days').textContent = weekendDays;

                // Update the label to show what type of counting is being used
                const daysLabel = document.getElementById('days-calculation-label');
                if (daysLabel) {
                    daysLabel.textContent = `Days to be deducted (${displayLabel}): ${daysToDeduct}`;
                }

                // Get current balance for selected leave type
                const currentBalance = parseInt(selectedOption.dataset.balance) || 0;
                const remainingAfter = currentBalance - daysToDeduct;

                document.getElementById('remaining-balance').textContent = remainingAfter;

                // Update a hidden field with the actual days to deduct
                let daysToDeductInput = document.getElementById('days_to_deduct');
                if (!daysToDeductInput) {
                    daysToDeductInput = document.createElement('input');
                    daysToDeductInput.type = 'hidden';
                    daysToDeductInput.id = 'days_to_deduct';
                    daysToDeductInput.name = 'days_requested';
                    document.querySelector('form').appendChild(daysToDeductInput);
                }
                daysToDeductInput.value = daysToDeduct;

                // Show warning if insufficient balance
                const balanceSpan = document.getElementById('remaining-balance');
                if (remainingAfter < 0) {
                    balanceSpan.className = 'fw-bold text-danger';
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Insufficient Balance';
                    submitBtn.className = 'btn btn-danger';
                } else {
                    balanceSpan.className = 'fw-bold text-success';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Request';
                    submitBtn.className = 'btn btn-primary';
                }

                daysInfo.style.display = 'block';

                // Add additional info about the counting method
                const countingInfo = document.getElementById('counting-method-info');
                if (countingInfo) {
                    let methodText = '';
                    if (countType === 'all_days') {
                        methodText =
                            '<i class="fas fa-info-circle"></i> This leave type counts ALL days including weekends and public holidays.';
                    } else if (countType === 'calendar_days') {
                        methodText =
                            '<i class="fas fa-info-circle"></i> This leave type uses calendar days (all days including weekends).';
                    } else if (includeWeekends) {
                        methodText =
                            '<i class="fas fa-info-circle"></i> This leave type includes weekends in the count.';
                    } else {
                        methodText =
                            '<i class="fas fa-info-circle"></i> This leave type uses working days only (weekends excluded).';
                    }
                    countingInfo.innerHTML = methodText;
                    countingInfo.className = 'alert alert-info mt-2';
                }
            }

            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                // Check if there's sufficient balance
                const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
                const currentBalance = parseInt(selectedOption.dataset.balance) || 0;
                const daysToDeduct = parseInt(document.getElementById('days_to_deduct').value) || 0;

                if (currentBalance < daysToDeduct) {
                    e.preventDefault();
                    alert('You do not have sufficient leave balance for this request.');
                    return false;
                }

                // Additional validation for documentation
                const requiresDocumentation = selectedOption.dataset.requiresDocs === 'true';
                const documentationInput = document.getElementById('documentation');

                if (requiresDocumentation && documentationInput && !documentationInput.files.length) {
                    e.preventDefault();
                    alert('Documentation is required for this leave type.');
                    return false;
                }
            });

            // Initialize form state
            if (leaveTypeSelect.value) {
                leaveTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
