@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add Employee</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/employee">All Employees</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add Employee</h5>
                </div>
                <div class="card-body grid auto-cols-max grid-flow-col">
                    <form method="POST" action="{{route('employee.store')}}" >
                    @csrf
                        <div class="card-title">
                            <h6 class="mb-0">Personal Information</h6>
                        </div>

                            <div class="row mb-12">
                                <label for="input35" class="col-sm-2 col-form-label">User Name</label>
                                <div class="col-sm-2">
                                    <input  type="text" class="form-control" id="username" name="username" >
                                </div>
                                <label for="input35" class="col-sm-2 col-form-label">Full Name</label>
                                <div class="col-sm-3">
                                    <input  type="text" class="form-control" id="name" name="name" >
                                </div>
                                <label for="input35" class="col-sm-1 col-form-label">Gender</label>
                                <div class="col-sm-2">
                                    <select name="gender" class="form-select" >
                                        <option selected="" disabled="">Select Gender</option>
                                        <option  value="Male">Male</option>
                                        <option  value="Female">Female</option>
                                    </select>
                                </div>
                            </div><br>
                            <div class="row mb-12">

                                <label for="input35" class="col-sm-2 col-form-label">ID/PP Number</label>
                                <div class="col-sm-2">
                                    <input  type="text" class="form-control" id="idnumber" name="idnumber" >
                                </div>
                                <label for="input35" class="col-sm-2 col-form-label">Date of Birth</label>
                                <div class="col-sm-2">
                                    <input  type="date" class="form-control" id="dob" name="dob" >
                                </div>

                            </div><br>
                        <div class="row mb-12">
                            <label for="input35" class="col-sm-2 col-form-label">Email Address</label>
                            <div class="col-sm-3">
                                <input  type="text" class="form-control" id="email" name="email" >
                            </div>
                            <label for="input35" class="col-sm-2 col-form-label">Phone No</label>
                            <div class="col-sm-2">
                                <input  type="text" class="form-control" id="phoneNo" name="phoneNo" >
                            </div>
                            <label for="input35" class="col-sm-1 col-form-label">Country</label>
                            <div class="col-sm-2">
                                <select name="country" class="form-select" id="single-select-field" >
                                    <option selected="" disabled="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><br>


                        <div class="card-title">
                            <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="row mb-12">
                            <label for="input35" class="col-sm-2 col-form-label">Permanent Address</label>
                            <div class="col-sm-4">
                                <input  type="text" class="form-control" id="paddress" name="paddress" >
                            </div>
                            <label for="input35" class="col-sm-2 col-form-label">Present Address</label>
                            <div class="col-sm-4">
                                <input  type="text" class="form-control" id="caddress" name="caddress" >
                            </div>

                        </div><br>
                        <div class="card-title">
                            <h6 class="mb-0">Job Detail</h6>
                        </div>
                        <div class="row mb-12">
                            <label for="input35" class="col-sm-2 col-form-label">Classification</label>
                            <div class="col-sm-3">
                                <select name="classification_id" class="form-select" id="single-select-field" >
                                    <option selected="" disabled="">Select Classification</option>
                                    @foreach ($classification as $class)
                                        <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="input35" class="col-sm-1 col-form-label">Rank</label>
                            <div class="col-sm-3">
                                <select name="rank_id" class="form-select" id="single-select-field" >
                                    <option selected="" disabled="">Select Rank</option>
                                    @foreach ($rank as $ranks)
                                        <option value="{{$ranks->id}}">{{$ranks->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="input35" class="col-sm-1 col-form-label">Join date</label>
                            <div class="col-sm-2">
                                <input  type="date" class="form-control" id="join_date" name="join_date" >
                            </div>
                        </div><br>

                        <div class="row mb-12">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-5" name="submit">Save</button>
                                    <a href="{{url()->previous()}}" type="reset" class="btn btn-secondary px-5">Cancel</a>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>



            <!-- end of body -->
        </div>
    </div>

    <!--end page wrapper -->

@endsection
