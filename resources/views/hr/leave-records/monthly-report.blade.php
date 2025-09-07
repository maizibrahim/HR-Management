@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
       <div class="container">
        <!--breadcrumb-->
     <br>   <div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="card-title mb-0">Monthly Leave Report</h3>
                </div>
                <div class="col-auto">
                    <form method="GET" class="d-flex gap-2">
                        <input type="month"
                               name="month"
                               value="{{ $month }}"
                               class="form-control"
                               onchange="this.form.submit()">
                        <a href="{{ route('hr.leave-records.index') }}"
                           class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Monthly Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>{{ $monthlyStats['total_requests'] }}</h3>
                            <p class="mb-0">Total Requests</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>{{ $monthlyStats['total_leave_days'] }}</h3>
                            <p class="mb-0">Total Leave Days</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $monthlyStats['unique_employees'] }}</h3>
                            <p class="mb-0">Employees on Leave</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ $monthlyStats['leave_types_used'] }}</h3>
                            <p class="mb-0">Leave Types Used</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Leave Records Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRecords as $record)
                        <tr>
                            <td>{{ $record->user->name }}</td>
                            <td><span class="badge bg-info">{{ $record->leaveType->leave_name }}</span></td>
                            <td>{{ $record->start_date->format('M d, Y') }}</td>
                            <td>{{ $record->end_date->format('M d, Y') }}</td>
                            <td><span class="badge bg-secondary">{{ $record->days_requested }}</span></td>
                            <td><span class="badge bg-success">Approved</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
            <!--end breadcrumb-->
       </div>
    </div>


@endsection
