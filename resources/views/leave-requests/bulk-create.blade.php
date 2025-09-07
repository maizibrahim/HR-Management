@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">

        <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Bulk Leave Request Management</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('bulk_results'))
                        @php $results = session('bulk_results'); @endphp
                        <div class="alert alert-info">
                            <h5>Bulk Processing Results:</h5>
                            <p>Total: {{ $results['total'] }} | Success: {{ count($results['success']) }} | Errors: {{ count($results['errors']) }}</p>

                            @if(!empty($results['errors']))
                                <h6>Errors:</h6>
                                <ul>
                                    @foreach($results['errors'] as $error)
                                        <li>Row {{ $error['index'] + 1 }}: {{ $error['error'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    @if(session('csv_errors'))
                        <div class="alert alert-danger">
                            <h5>CSV Import Errors:</h5>
                            <ul>
                                @foreach(session('csv_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- CSV Import Section -->
                    <div class="mb-4">
                        <h5>Import from CSV</h5>
                        <form action="{{ route('leave-requests.bulk-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="csv_file">CSV File</label>
                                        <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv,.txt" required>
                                        <small class="form-text text-muted">
                                            <a href="{{ route('leave-requests.download-template') }}">Download CSV Template</a>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="override_balance_check" id="override_balance_check" class="form-check-input">
                                        <label class="form-check-label" for="override_balance_check">Override Balance Check</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="auto_deduct_balance" id="auto_deduct_balance" class="form-check-input">
                                        <label class="form-check-label" for="auto_deduct_balance">Auto Deduct Balance</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Import CSV</button>
                        </form>
                    </div>

                    <hr>

                    <!-- Manual Bulk Entry Section -->
                    <h5>Manual Bulk Entry</h5>
                    <form action="{{ route('leave-requests.bulk-store') }}" method="POST" id="bulk-form">
                        @csrf
                        <div id="requests-container">
                            <!-- Dynamic request rows will be added here -->
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-secondary" onclick="addRequestRow()">Add Request</button>
                            <button type="submit" class="btn btn-primary">Submit All Requests</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



       </div>
    </div>

    <script>
let requestIndex = 0;

function addRequestRow() {
    const container = document.getElementById('requests-container');
    const users = @json($users);
    const leaveTypes = @json($leaveTypes);

    const row = document.createElement('div');
    row.className = 'card mb-3';
    row.innerHTML = `
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label>User</label>
                    <select name="requests[${requestIndex}][user_id]" class="form-control" required>
                        <option value="">Select User</option>
                        ${users.map(user => `<option value="${user.id}">${user.name} (${user.email})</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Leave Type</label>
                    <select name="requests[${requestIndex}][leave_type_id]" class="form-control" required>
                        <option value="">Select Type</option>
                        ${leaveTypes.map(type => `<option value="${type.id}">${type.leave_name} (${type.leave_group.name})</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-1">
                    <label>Start Date</label>
                    <input type="date" name="requests[${requestIndex}][start_date]" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <label>End Date</label>
                    <input type="date" name="requests[${requestIndex}][end_date]" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <label>Status</label>
                    <select name="requests[${requestIndex}][status]" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Reason</label>
                    <input type="text" name="requests[${requestIndex}][reason]" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Review Comments</label>
                    <input type="text" name="requests[${requestIndex}][review_comments]" class="form-control">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <div>
                        <div class="form-check">
                            <input type="checkbox" name="requests[${requestIndex}][override_balance_check]" class="form-check-input">
                            <label class="form-check-label">Override Balance</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="requests[${requestIndex}][auto_deduct_balance]" class="form-check-input">
                            <label class="form-check-label">Auto Deduct</label>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger mt-1" onclick="removeRequestRow(this)">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.appendChild(row);
    requestIndex++;
}

function removeRequestRow(button) {
    button.closest('.card').remove();
}

// Add initial row
addRequestRow();
</script>
@endsection
