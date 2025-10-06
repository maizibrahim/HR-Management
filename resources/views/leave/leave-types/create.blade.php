@extends('admin.admin_master')
@section('admin')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add Leave Type</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('leave.leave-types.index') }}">All Leave Type</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add Leave Type</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Add Leave Type</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('leave.leave-types.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="leave_group_id" class="form-label">Leave Group <span
                                            class="text-danger">*</span></label>
                                    <select id="leave_group_id"
                                        class="form-select @error('leave_group_id') is-invalid @enderror"
                                        name="leave_group_id" required>
                                        <option value="">Select Leave Group</option>
                                        @foreach ($leaveGroups as $leaveGroup)
                                            <option value="{{ $leaveGroup->id }}"
                                                {{ old('leave_group_id') == $leaveGroup->id ? 'selected' : '' }}>
                                                {{ $leaveGroup->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_group_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="leave_code" class="form-label">Code<span
                                            class="text-danger">*</span></label>
                                    <input id="leave_code" type="text"
                                        class="form-control @error('leave_code') is-invalid @enderror" name="leave_code"
                                        value="{{ old('leave_code') }}" required>
                                    @error('leave_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>



                                <div class="mb-3">
                                    <label for="leave_name" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input id="leave_name" type="text"
                                        class="form-control @error('leave_name') is-invalid @enderror" name="leave_name"
                                        value="{{ old('leave_name') }}" required>
                                    @error('leave_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="days_allowed" class="form-label">Days Allowed <span
                                            class="text-danger">*</span></label>
                                    <input id="days_allowed" type="number" min="1"
                                        class="form-control @error('days_allowed') is-invalid @enderror" name="days_allowed"
                                        value="{{ old('days_allowed') }}" required>
                                    @error('days_allowed')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="count_type" class="form-label">Day Counting Method *</label>
                                    <select name="count_type" id="count_type"
                                        class="form-select @error('count_type') is-invalid @enderror" required>
                                        <option value="weekdays_only"
                                            {{ old('count_type') == 'weekdays_only' ? 'selected' : '' }}>
                                            Weekdays (Fri-Sat) & Public Holiday
                                        </option>
                                        <option value="all_days" {{ old('count_type') == 'all_days' ? 'selected' : '' }}>
                                            All Days (Including Weekends & Holidays)
                                        </option>
                                    </select>
                                    <div class="form-text">
                                        Choose how days are counted for this leave type
                                    </div>
                                    @error('count_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('requires_documentation') is-invalid @enderror"
                                            type="checkbox" name="requires_documentation" id="requires_documentation"
                                            value="1" {{ old('requires_documentation') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_documentation">
                                            Requires Documentation
                                        </label>
                                        @error('requires_documentation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>








                                <div class="row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4" name="submit">Save</button>
                                            <a href="{{ url()->previous() }}" type="reset"
                                                class="btn btn-secondary px-5">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>


            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->
@endsection
