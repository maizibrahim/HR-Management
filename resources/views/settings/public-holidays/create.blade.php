@extends('admin.admin_master')
@section('admin')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="container">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add Public Holidays</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Holidays</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Add Public Holiday</h2>
                    <p class="text-muted mb-0">Create a new public holiday</p>
                </div>
                <a href="{{ route('settings.public-holidays.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Holidays
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('settings.public-holidays.store') }}" method="POST">
                        @csrf

                        <!-- Holiday Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label required">Holiday Name</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="e.g., New Year's Day, Christmas"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Holiday Date -->
                        <div class="mb-3">
                            <label for="date" class="form-label required">Date</label>
                            <input type="date"
                                   class="form-control @error('date') is-invalid @enderror"
                                   id="date"
                                   name="date"
                                   value="{{ old('date') }}"
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select the date for this holiday.</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Optional description or notes about this holiday">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Recurring -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_recurring"
                                       name="is_recurring"
                                       value="1"
                                       {{ old('is_recurring') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_recurring">
                                    <strong>Recurring Holiday</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i>
                                Check this if the holiday occurs on the same date every year
                                (e.g., Christmas, New Year's Day). Recurring holidays can be
                                automatically generated for future years.
                            </small>
                        </div>

                        <!-- Preview -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-eye"></i> Preview
                                    </h6>
                                    <div id="preview">
                                        <span class="text-muted">Select a date to see the preview</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('settings.public-holidays.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Holiday
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                    </div></div>
<script>
// Preview functionality
document.getElementById('date').addEventListener('change', updatePreview);
document.getElementById('name').addEventListener('input', updatePreview);

function updatePreview() {
    const name = document.getElementById('name').value;
    const dateInput = document.getElementById('date').value;
    const preview = document.getElementById('preview');

    if (name && dateInput) {
        const date = new Date(dateInput);
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
        const formattedDate = date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        preview.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                <div>
                    <div class="fw-semibold">${name}</div>
                    <div class="text-muted small">${formattedDate} (${dayName})</div>
                </div>
            </div>
        `;
    } else {
        preview.innerHTML = '<span class="text-muted">Select a date to see the preview</span>';
    }
}
</script>





        </div>
    </div>
</div>

   @endsection


