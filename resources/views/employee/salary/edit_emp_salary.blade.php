@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Edit Employee Salary</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/employee/salary">All Employee Salary</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Employee Salary</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">

                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{route('salary.update', $salaries->id)}}" >
                                @csrf


                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Full Name</label>

                                    <div class="col-sm-8">
                                        @foreach ($user as $users)
                                            <p class="text-secondary mb-1" ></p>
                                            <div class="col-sm-12 text-secondary">
                                                <h6> {{$salaries->employee_id == $users->id ? $users->name :''}} </h6>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Basic Salary</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="basic_salary" name="basic_salary" value="{{$salaries->basic_salary}}" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Attendance Allowance</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="attendance_allowance" name="attendance_allowance" value="{{$salaries->attendance_allowance}}" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Service Allowance</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="service_allowance" name="service_allowance" value="{{$salaries->service_allowance}}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Job Allowance</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="job_allowance" name="job_allowance" value="{{$salaries->job_allowance}}">
                                    </div>
                                </div>


                                <div class="row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4" name="submit">Save Changes</button>
                                            <a href="{{url()->previous()}}" type="reset" class="btn btn-secondary px-5">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>


            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->

@endsection
