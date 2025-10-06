@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">View Leave Group</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('leave.leave-groups.index') }}">All Leave Groups</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Leave Group</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

             <div class="card-body">
                    <div class="mb-4">
                        <h6>Assigned Leave Types</h6>

                        @if ($leaveGroup->leaveTypes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Days Allowed</th>
                                            <th>Requires Documentation</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveGroup->leaveTypes as $leaveType)
                                            <tr>
                                                <td>{{ $leaveType->leave_name }}</td>
                                                <td>{{ $leaveType->days_allowed }}</td>
                                                <td>
                                                    @if ($leaveType->requires_documentation)
                                                        <span class="badge bg-primary">Required</span>
                                                    @else
                                                        <span class="badge bg-secondary">Not Required</span>
                                                    @endif
                                                </td>
                                                <td>{{ $leaveType->description ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn btn-sm btn-primary">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                No leave types have been assigned to this group.
                                <a href="{{ route('leave-types.create') }}" class="btn btn-sm btn-primary ms-2">Add Leave Type</a>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h6>Employees in this Leave Group</h6>

                        @if ($leaveGroup->users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Join Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveGroup->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->join_date ? $user->join_date->format('M d, Y') : 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                No employees have been assigned to this leave group yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->

@endsection
