@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Edit Leave Type</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/leave/type">All Leave Types</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Leave Type</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Leave Type</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{route('leave.type.update', $leavetype->id)}}" >
                                @csrf
                                <input type="hidden" name="id"  value="{{$leavetype->id}}">


                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Leave Code</label>
                                    <div class="col-sm-9">
                                        <input  type="text" class="form-control" id="leave_code" name="leave_code" value="{{$leavetype->leave_code}}" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Leave Detail</label>
                                    <div class="col-sm-9">
                                        <input  type="text" class="form-control" id="Leave_name" name="Leave_name" value="{{$leavetype->Leave_name}}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Leave Total</label>
                                    <div class="col-sm-9">
                                        <input  type="text" class="form-control" id="leave_total" name="leave_total" value="{{$leavetype->leave_total}}">
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
