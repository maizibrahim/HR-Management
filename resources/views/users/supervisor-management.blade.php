@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Supervisor Management</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">All Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Assign Supervisor</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-chart-line"></i> Organizational Hierarchy</h6>
    </div>
    <div class="card-body">
        @php
            $topLevelUsers = $users->whereNull('supervisor_id');
        @endphp

        @foreach($topLevelUsers as $topUser)
            <div class="org-chart">
                <div class="org-node top-level">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $topUser->name }}</strong>
                            <br><small class="text-muted">{{ $topUser->email }}</small>
                            @if($topUser->leaveGroup)
                                <br><span class="badge bg-info">{{ $topUser->leaveGroup->name }}</span>
                            @endif
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('users.show', $topUser) }}" class="btn btn-sm btn-outline-light">View</a>
                            <a href="{{ route('users.assign-supervisor', $topUser) }}" class="btn btn-sm btn-outline-light">Assign Supervisor</a>
                        </div>
                    </div>
                </div>

                @if($topUser->subordinates->count() > 0)
                    <div class="subordinates ms-4 mt-2">
                        @foreach($topUser->subordinates as $subordinate)
                            <div class="org-node subordinate">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $subordinate->name }}</strong>
                                        <br><small class="text-muted">{{ $subordinate->email }}</small>
                                        @if($subordinate->leaveGroup)
                                            <br><span class="badge bg-secondary">{{ $subordinate->leaveGroup->name }}</span>
                                        @endif
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('users.show', $subordinate) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('users.assign-supervisor', $subordinate) }}" class="btn btn-sm btn-outline-info">Change Supervisor</a>
                                    </div>
                                </div>

                                @if($subordinate->subordinates->count() > 0)
                                    <div class="subordinates ms-4 mt-2">
                                        @foreach($subordinate->subordinates as $subSubordinate)
                                            <div class="org-node sub-subordinate">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $subSubordinate->name }}</strong>
                                                        <br><small class="text-muted">{{ $subSubordinate->email }}</small>
                                                    </div>
                                                    <div class="btn-group">
                                                        <a href="{{ route('users.show', $subSubordinate) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                        <a href="{{ route('users.assign-supervisor', $subSubordinate) }}" class="btn btn-sm btn-outline-info">Change Supervisor</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Quick Assignment Tools -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-users-cog"></i> Bulk Supervisor Assignment</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.bulk-assign-supervisor') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="supervisor_id" class="form-label">Select Supervisor</label>
                        <select class="form-select" id="supervisor_id" name="supervisor_id" required>
                            <option value="">Choose Supervisor</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Employees to Assign</label>
                        <div class="user-selection" style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px;">
                            @foreach($users as $user)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="user_{{ $user->id }}">
                                    <label class="form-check-label" for="user_{{ $user->id }}">
                                        {{ $user->name }}
                                        @if($user->supervisor)
                                            <small class="text-muted">(Currently: {{ $user->supervisor->name }})</small>
                                        @else
                                            <small class="text-warning">(No supervisor)</small>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check"></i> Assign Selected Users to Supervisor
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-exclamation-triangle"></i> Users Without Supervisors</h6>
            </div>
            <div class="card-body">
                @php
                    $usersWithoutSupervisors = $users->whereNull('supervisor_id');
                @endphp

                @if($usersWithoutSupervisors->count() > 0)
                    @foreach($usersWithoutSupervisors as $user)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <br><small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <a href="{{ route('users.assign-supervisor', $user) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-user-plus"></i> Assign
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p>All users have supervisors assigned!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>





            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->

@endsection
