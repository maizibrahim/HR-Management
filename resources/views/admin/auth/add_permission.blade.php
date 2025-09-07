@extends('admin.admin_master')
@section('admin')




    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add Permission</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/permission">Permissions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Permission</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Add Permission</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{route('permission.store')}}" >
                                @csrf
                                <div class="row mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Permission Name</label>
                                    <div class="col-sm-9">
                                        <input  type="text" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="input37" class="col-sm-3 col-form-label">Group Name</label>
                                    <div class="col-sm-9">
                                        <select name="group_name" class="form-select">
                                            <option selected="" disabled="">Select Group</option>
                                           <optgroup label="Leave Management">
											<option value="LeaveApproval">leave Approval</option>
											<option value="LeaveBulk">Leave Bulk Upload</option>
											<option value="LeaveRecords">Leave Records</option>
                                            <option value="LeaveSetup">Leave Step</option>
										</optgroup>
										<optgroup label="Role & Permission">
                                            <option value="authMenu">Authentication Menu</option>
											<option value="role">Role</option>
											<option value="permission">Permission</option>
                                            	<option value="userAuth">User Authentication</option>
										</optgroup>
                                        <optgroup label="System Configuration">
                                            <option value="AdminMenu">Admin Menu</option>
											<option value="LeaveGroup">Leave Group</option>
                                            <option value="LeaveType">Leave Type</option>
										</optgroup>




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
