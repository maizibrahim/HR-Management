@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User Management</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Users</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{ route('users.create') }}" type="button" class="btn btn-primary px-4 right">Add New User</a>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif


                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                     <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Leave Group</th>
                                        <th>Supervisor</th>
                                        <th>Subordinates</th>
                                        <th>Join Date</th>
                                        <th>Actions</th>
                                     </tr>
                                </thead>
                                <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->leaveGroup)
                                    <span class="badge bg-info">{{ $user->leaveGroup->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Not Assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($user->supervisor)
                                    <span class="badge bg-success">{{ $user->supervisor->name }}</span>
                                @else
                                    <span class="badge bg-warning">No Supervisor</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $user->subordinates->count() }}</span>
                            </td>
                            <td>{{ $user->join_date->format('d M, Y') ?? 'Not Set' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('users.assign-supervisor', $user) }}" class="btn btn-sm btn-outline-info" title="Assign Supervisor">
                                        <i class="fas fa-user-tie"></i>
                                    </a>
                                    <a href="{{ route('users.leave-balances', $user) }}" class="btn btn-sm btn-outline-success" title="Manage Leave">
                                        <i class="fas fa-calendar-check"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
