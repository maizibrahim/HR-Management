@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Manage leave Balance</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">All Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Leave Balance</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-balance-scale"></i> Manage Leave Balances</h3>
    <div>
        <span class="badge bg-info">{{ $user->name }}</span>
        <span class="badge bg-secondary">{{ $user->leaveGroup->name ?? 'No Group' }}</span>
    </div>
</div>

@if($leaveBalances->count() > 0)
    <div class="row">
        @foreach($leaveBalances as $balance)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ $balance->leaveType->leave_name }}</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('leave-balances.update', $balance) }}">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="days_remaining_{{ $balance->id }}" class="form-label">Days Remaining</label>
                                        <input type="number" class="form-control"
                                               id="days_remaining_{{ $balance->id }}"
                                               name="days_remaining"
                                               value="{{ $balance->days_remaining }}"
                                               min="0" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reset_date_{{ $balance->id }}" class="form-label">Reset Date</label>
                                        <input type="date" class="form-control"
                                               id="reset_date_{{ $balance->id }}"
                                               name="reset_date"
                                               value="{{ $balance->reset_date->format('Y-m-d') }}"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="row text-center">
                            <div class="col-6">
                                <small class="text-muted">Default Allowance</small>
                                <div><strong>{{ $balance->leaveType->days_allowed }} days</strong></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Next Reset</small>
                                <div><strong>{{ $balance->reset_date->format('M d, Y') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        <h5>No Leave Balances</h5>
        <p class="text-muted">This user doesn't have any leave balances assigned.</p>
        @if(!$user->leaveGroup)
            <p class="text-muted">Please assign a leave group to this user first.</p>
            <a href="{{ route('users.assign-leave-group', $user) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Assign Leave Group
            </a>
        @endif
    </div>
@endif

<div class="mt-4">
    <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to User
    </a>
</div>
        </div>

    <!--end page wrapper -->

@endsection
