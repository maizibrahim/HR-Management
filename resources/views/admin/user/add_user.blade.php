@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/user">All users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add User</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Add User</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{route('users.store')}}" >
                                @csrf
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Full Name</label>
                                    <div class="col-sm-9">

                                        <input  type="text" class="form-control" id="name" name="name" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">User Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" name="username" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Email Address</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" >
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password" >
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">PhoneNo</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="phoneNo" name="phoneNo" value="">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="single-select-field" class="col-sm-3 col-form-label">Role Name</label>
                                    <div class="col-sm-9">
                                        <select name="roles" class="form-select" id="single-select-field" >
                                            <option selected="" disabled="">Select Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{$role->name}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>

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
