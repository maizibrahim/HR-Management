@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">All Leave Groups</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Leave Group</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{ route('leave.leave-groups.create') }}" type="button" class="btn btn-primary px-4 right">Add New Leave Group</a>

<br><br>    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($leaveGroups) > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Leave Types</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveGroups as $leaveGroup)
                                        <tr>
                                            <td>{{ $leaveGroup->name }}</td>
                                            <td>{{ $leaveGroup->description ?? 'N/A' }}</td>
                                            <td>
                                                @if ($leaveGroup->leaveTypes->count() > 0)
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($leaveGroup->leaveTypes as $leaveType)
                                                            <li>{{ $leaveType->leave_name }} ({{ $leaveType->days_allowed }} days)</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted">No leave types defined</span>
                                                @endif
                                            </td>
                                            <td>{{ $leaveGroup->created_at->format('M d, Y') }}</td>

                                            <td>

                                                <div class="btn-group" role="group">
                                                   <a href="{{route('leave.leave-groups.show', $leaveGroup)}}" class="btn btn-sm btn-warning" title="View"><i class="fadeIn animated bx bx-show-alt"></i></a>
                                                    <a href="{{route('leave.leave-groups.edit', $leaveGroup)}}" class="btn btn-sm btn-primary" title="Edit Data"><i class="fadeIn animated bx bx-edit-alt"></i></a>
                                                    <a href="{{route('leave.leave-groups.delete', $leaveGroup->id)}}" class="btn btn-sm btn-danger" title="Delete" id="delete"><i class="fadeIn animated bx bx-trash-alt"></i></a>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No leave groups have been created yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
