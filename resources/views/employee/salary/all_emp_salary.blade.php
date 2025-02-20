@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">All Employees Salary</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Employees Salary</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{route('salary.add')}}" type="button" class="btn btn-primary px-4 right">Add Employee Salary</a>


            <br><br><div class="card">
                <div class="card-body" >
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>ID/PP NO</th>
                                <th>Join Date</th>
                                <th>Basic Salary</th>
                                <th>Job Allowance</th>
                                <th>Service Allowance</th>
                                <th>Attendance Benefit</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($salaries as $keys => $salary)
                                <tr>
                                    <td> {{ $keys+1}} </td>
                                    <td> {{ $salary ['EmploySalary']['idnumber']}} </td>
                                    <td> {{ $salary ['EmploySalary']['join_date']}} </td>
                                    <td> {{ $salary->basic_salary}} </td>
                                    <td>  {{ $salary->attendance_allowance}}</td>
                                    <td>  {{ $salary->service_allowance}} </td>
                                    <td>  {{ $salary->job_allowance}} </td>

                                    <td>
                                        <a href="{{route('salary.edit',$salary->id)}}" class="btn btn-info sm" title="Edit Data"><i class="lni lni-pencil-alt"></i></a>
                                        <a href="{{route('salary.delete',$salary->id)}}" class="btn btn-danger sm"  id="delete" title="Delete Data"><i class="lni lni-trash"></i></a>

                                    </td>
                                </tr>

                            @endforeach


                        </table>
                    </div>
                </div>
                <!-- end of body -->
            </div>
        </div>

        <!--end page wrapper -->
@endsection
