@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
     <br>     <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Leave Record Details</h3>
                    <a href="{{ route('hr.leave-records.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Records
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Employee Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $leaveRequest->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $leaveRequest->user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Join Date:</strong></td>
                                    <td>{{ $leaveRequest->user->join_date->format('M d, Y') }}</td>
                                </tr>
                                @if($leaveRequest->user->supervisor)
                                <tr>
                                    <td><strong>Supervisor:</strong></td>
                                    <td>{{ $leaveRequest->user->supervisor->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Leave Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Leave Type:</strong></td>
                                    <td><span class="badge bg-info">{{ $leaveRequest->leaveType->name }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Start Date:</strong></td>
                                    <td>{{ $leaveRequest->start_date->format('M d, Y (l)') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>End Date:</strong></td>
                                    <td>{{ $leaveRequest->end_date->format('M d, Y (l)') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Days Requested:</strong></td>
                                    <td><span class="badge bg-secondary">{{ $leaveRequest->days_requested }} days</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge bg-success">{{ ucfirst($leaveRequest->status) }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Leave Details</h5>

                            @if($leaveRequest->reason)
                            <div class="mb-3">
                                <strong>Reason:</strong>
                                <p class="mt-1">{{ $leaveRequest->reason }}</p>
                            </div>
                            @endif

                            @if($leaveRequest->documentation_path)
                            <div class="mb-3">
                                <strong>Documentation:</strong>
                                <p class="mt-1">
                                    <a href="{{ Storage::url($leaveRequest->documentation_path) }}"
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file"></i> View Document
                                    </a>
                                </p>
                            </div>
                            @endif

                            @if($leaveRequest->reviewer)
                            <div class="mb-3">
                                <strong>Approval Information:</strong>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Approved By:</strong></td>
                                        <td>{{ $leaveRequest->reviewer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Approved Date:</strong></td>
                                        <td>{{ $leaveRequest->reviewed_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @if($leaveRequest->review_comments)
                                    <tr>
                                        <td><strong>Comments:</strong></td>
                                        <td>{{ $leaveRequest->review_comments }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <!--end breadcrumb-->
       </div>
    </div>


@endsection
