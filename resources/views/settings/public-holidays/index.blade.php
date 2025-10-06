@extends('admin.admin_master')
@section('admin')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="container">
            <!--breadcrumb-->

            <!--end breadcrumb-->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                   <br> <h2 class="mb-0">Public Holidays</h2>
                    <p class="text-muted mb-0">Manage company public holidays for {{ $year }}</p>
                </div>
                <div class="d-flex gap-2">
                    <select id="yearSelect" class="form-select" style="width: auto;">
                        @foreach($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                        <i class="fas fa-upload"></i> Bulk Import
                    </button>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#recurringModal">
                        <i class="fas fa-sync"></i> Generate Recurring
                    </button>
                    <a href="{{ route('settings.public-holidays.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Holiday
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Holidays Table -->
            <div class="card">
                <div class="card-body">
                    @if($holidays->count() > 0)
                        <div class="table-responsive">
                           <table id="example" class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th >Date</th>
                                        <th >Holiday Name</th>
                                        <th >Description</th>
                                        <th  class="text-center">Day</th>
                                        <th class="text-center">Recurring</th>
                                        <th  class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($holidays as $holiday)
                                        <tr>
                                            <td>
                                                <strong>{{ $holiday->date->format('d M, Y') }}</strong>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $holiday->name }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $holiday->description ?? 'No description' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $holiday->date->format('l') }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($holiday->is_recurring)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-sync"></i> Yes
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('settings.public-holidays.edit', $holiday) }}"
                                                       class="btn btn-sm btn-primary"
                                                       title="Edit">
                                                        <i class="fadeIn animated bx bx-edit-alt"></i>
                                                    </a>

                                                     <a href="{{ route('settings.public-holidays.delete', $holiday->id) }}"
                                                         class="btn btn-sm btn-danger" title="Delete"
                                                         id="delete"><i class="fadeIn animated bx bx-trash-alt"></i></a>

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Public Holidays Found</h5>
                            <p class="text-muted">No holidays are configured for {{ $year }}.</p>
                            <a href="{{ route('settings.public-holidays.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Holiday
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Import Modal -->
<div class="modal fade" id="bulkImportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Import Holidays</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('settings.public-holidays.bulk-import') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_year" class="form-label">Year</label>
                        <input type="number" class="form-control" id="import_year" name="year"
                               value="{{ $year }}" min="2020" max="2050" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Holidays</label>
                        <div id="holidaysContainer">
                            <div class="holiday-row border p-3 mb-2 rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="holidays[0][name]"
                                               placeholder="Holiday Name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" class="form-control" name="holidays[0][date]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                   name="holidays[0][is_recurring]" value="1">
                                            <label class="form-check-label small">Recurring</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <input type="text" class="form-control" name="holidays[0][description]"
                                               placeholder="Description (optional)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addHolidayRow()">
                            <i class="fas fa-plus"></i> Add Another Holiday
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Holidays</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Recurring Modal -->
<div class="modal fade" id="recurringModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Recurring Holidays</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('settings.public-holidays.generate-recurring') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="target_year" class="form-label">Target Year</label>
                        <input type="number" class="form-control" id="target_year" name="target_year"
                               value="{{ $year + 1 }}" min="2020" max="2050" required>
                        <div class="form-text">This will copy all recurring holidays to the selected year.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Generate Holidays</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Year selection
document.getElementById('yearSelect').addEventListener('change', function() {
    window.location.href = '{{ route("settings.public-holidays.index") }}?year=' + this.value;
});

// Delete confirmation
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete the holiday "${name}"?`)) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Add holiday row for bulk import
let holidayRowCount = 1;
function addHolidayRow() {
    const container = document.getElementById('holidaysContainer');
    const newRow = `
        <div class="holiday-row border p-3 mb-2 rounded">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="holidays[${holidayRowCount}][name]"
                           placeholder="Holiday Name" required>
                </div>
                <div class="col-md-4">
                    <input type="date" class="form-control" name="holidays[${holidayRowCount}][date]" required>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input"
                               name="holidays[${holidayRowCount}][is_recurring]" value="1">
                        <label class="form-check-label small">Recurring</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-10">
                    <input type="text" class="form-control" name="holidays[${holidayRowCount}][description]"
                           placeholder="Description (optional)">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeHolidayRow(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newRow);
    holidayRowCount++;
}

function removeHolidayRow(button) {
    button.closest('.holiday-row').remove();
}
</script>





                    </div>
                </div>


        </div>
    </div>
</div>

   @endsection


