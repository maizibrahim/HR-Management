@extends('admin.admin_master')
@section('admin')




    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Edit Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/user/profile">User Profile</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Profile</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{route('user.store')}}" enctype="multipart/form-data">
                               @csrf
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Full Name</label>
                                    <div class="col-sm-9">

                                        <input  type="text" class="form-control" id="name" name="name" value="{{$userEdit->name}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Phone No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="phoneNo" name="phoneNo" value="{{$userEdit->phoneNo}}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Profile Picture</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="profile" name="profile">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <img id="showImage" src="{{ (!empty($userEdit->profile))? url('upload/profile_images/'.$userEdit->profile):url('upload/no_image.jpg') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Permanent Address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="paddress" name="paddress" value="{{$userEdit->paddress}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Present Address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="caddress" name="caddress" value="{{$userEdit->caddress}}">
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4" name="submit">Save Changes</button>
                                            <button type="reset" class="btn btn-secondary px-4">Cancel</button>
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


    <script type="text/javascript">

        $(document).ready(function(){
            $('#profile').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });

    </script>

@endsection
