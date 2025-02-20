@extends('admin.admin_master')
@section('admin')


<!--start page wrapper -->
<div class="page-wrapper" xmlns="http://www.w3.org/1999/html">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 text-secondary">
                        <a href="{{route('user.edit')}}" type="button" class="btn btn-primary px-6 left">Update Profile</a>
                    </div>
                </div>
            </div><br>

            <!--end breadcrumb-->
            <div class="container">
                <div class="main-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="{{ (!empty($userData->profile))? url('upload/profile_images/'.$userData->profile):url('upload/no_image.jpg') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                                        <div class="mt-3">
                                            <h4>{{$userData->name}}</h4>
                                            @foreach ($rank as $ranks)
                                                <p class="text-secondary mb-1" ></p>
                                                <div class="col-sm-12 text-secondary">
                                                    <h7> {{$userData->rank_id == $ranks->id ? $ranks->name :''}}</h7>
                                                </div>
                                            @endforeach

                                            @foreach ($classification as $clas)
                                                <p class="text-secondary mb-1" ></p>
                                                <div class="col-sm-12 text-secondary">
                                                    <h7> {{$userData->classification_id == $clas->id ? $clas->name :''}} </h7>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    <hr class="my-4" />
                                    <div class="row mb-12">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email </h6>
                                        </div>
                                    </div>
                                    <div class="col-sm-10 text-secondary">
                                        <h7> {{$userData->email}}</h7>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Personal Information</h5><hr>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">ID/PP Number </h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <h7> {{$userData->idnumber}}</h7>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Full Name</h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <h7> {{$userData->name}}</h7>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Gender</h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <h7> {{$userData->gender}}</h7>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">User Name</h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <h7> {{$userData->username}}</h7>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Phone Number </h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <h7> {{$userData->phoneNo}}</h7>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Date of Birth </h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <h7> {{$userData->dob}}</h7>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Address Information</h5><hr>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0">Permanent Address </h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <h7> {{$userData->paddress}}</h7>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0">Present Address </h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <h7> {{$userData->caddress}}</h7>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Country</h6>
                                        </div>
                                        <div class="col-sm-7 text-secondary">
                                            <h7> {{$userData->country}}</h7>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">

                                <div class="card-body">
                                    <h5>Job Detail</h5><hr>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Classification</h6>
                                        </div>
                                        <div class="col-sm-7 text-secondary">
                                            @foreach ($classification as $class)
                                                <h7> {{$userData->classification_id == $class->id ? $class->name :''}} </h7>
                                            @endforeach
                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Rank</h6>
                                        </div>

                                        <div class="col-sm-7 text-secondary">
                                            @foreach ($rank as $ranks)
                                                <h7> {{$userData->rank_id  == $ranks->id ? $ranks->name :''}} </h7>
                                            @endforeach
                                        </div>

                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Role</h6>
                                        </div>
                                        <div class="col-sm-7 text-secondary">
                                            <h7> {{$userData->role}}</h7>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->








@endsection
