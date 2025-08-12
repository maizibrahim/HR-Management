@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">My Leave Request</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                         </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{ route('leave-requests.create') }}" type="button" class="btn btn-primary px-4 right">New Request</a>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                @if($leaveRequests->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Reviewer</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                            <tr>
                                <td>{{ $request->leaveType->leave_name }}</td>
                                <td>{{ $request->start_date->format('M d, Y') }}</td>
                                <td>{{ $request->end_date->format('M d, Y') }}</td>
                                <td>{{ $request->days_requested }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->reviewer)
                                        {{ $request->reviewer->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('leave-requests.show', $request) }}"
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($request->status == 'pending')
                                            <form method="POST" action="{{ route('leave-requests.cancel', $request) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5>No Leave Requests</h5>
                <p class="text-muted">You haven't submitted any leave requests yet.</p>
                <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Submit Your First Request
                </a>
            </div>
        @endif

            </div>
        </div>
    </div>
</div>
@endsection


