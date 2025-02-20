@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add Employee Salary</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/employee/salary">All Employee Salary</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Employee Salary</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Add Employee Salary</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{route('salary.store')}}" >
                                @csrf
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Full Name</label>

                                    <div class="col-sm-8">
                                        <select name="employee_id" class="form-select" id="single-select-field" >
                                            <option selected="" disabled="">Select Name</option>
                                            @foreach ($user as $users)
                                                <option value="{{$users->id}}">{{$users->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Basic Salary</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="basic_salary" name="basic_salary" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Attendance Allowance</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="attendance_allowance" name="attendance_allowance" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Service Allowance</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="service_allowance" name="service_allowance" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-4 col-form-label">Job Allowance</label>
                                    <div class="col-sm-8">
                                        <input  type="text" class="form-control" id="job_allowance" name="job_allowance" >
                                    </div>
                                </div>


                                <div class="row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4" name="submit">Save</button>
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
