@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Assign Leave Group</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">All Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Assign leave Group</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Employee: <strong>{{ $user->name }}</h5>
                        </div>
                        <div class="card-body p-4">
                     <form method="POST" action="{{ route('users.assign-leave-group', $user) }}">
                     @csrf
                     @method('PATCH')

                     <div class="mb-3">
                        <label class="form-label">Current Leave Group</label>
                        <div class="alert alert-info">
                            @if($user->leaveGroup)
                                <i class="fas fa-users"></i> {{ $user->leaveGroup->name }}
                                <br><small class="text-muted">{{ $user->leaveGroup->description }}</small>
                            @else
                                <i class="fas fa-exclamation-circle"></i> No leave group assigned
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="leave_group_id" class="form-label">New Leave Group</label>
                        <select class="form-select @error('leave_group_id') is-invalid @enderror"
                                id="leave_group_id" name="leave_group_id" required>
                            <option value="">Select Leave Group</option>
                            @foreach($leaveGroups as $group)
                                <option value="{{ $group->id }}"
                                        {{ $user->leave_group_id == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                    @if($group->description)
                                        - {{ $group->description }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('leave_group_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Changing leave group will reset all leave balances for this user.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Leave Group
                        </button>
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
