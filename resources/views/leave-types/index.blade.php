@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">All Leave Types</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Leave Types</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{ route('leave-types.create') }}" type="button" class="btn btn-primary px-4 right">Add New Leave Type</a>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($leaveTypes) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                   <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Leave Group</th>
                                        <th>Days Allowed</th>
                                        <th>Requires Documentation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveTypes as $leaveType)
                                        <tr>
                                            <td>{{ $leaveType->leave_code }}</td>
                                            <td>{{ $leaveType->leave_name }}</td>
                                            <td>{{ $leaveType->leaveGroup->name }}</td>
                                            <td>{{ $leaveType->days_allowed }}</td>
                                            <td>
                                                @if ($leaveType->requires_documentation)
                                                    <span class="badge bg-primary">Required</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Required</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('leave-types.show', $leaveType) }}" class="btn btn-sm btn-info">View</a>
                                                    <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="{{ route('leave-types.destroy', $leaveType) }}" class="btn btn-sm btn-danger" id="delete">Delete</a>

                                                    <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" >Delete</button>
                                                    </form>



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
