@extends('admin.admin_master')
@section('admin')


    <!--start page wrapper -->
    <div class="page-wrapper" xmlns="http://www.w3.org/1999/html">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Leave Group Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{url()->previous()}}">All Leave Group</a></li>
                            @foreach($leaveallocation as $key => $item)
                            <li class="breadcrumb-item active" aria-current="page">{{$item->leavegroup_id == $leavegroup->id ? $leavegroup->leave_group :''}}</li>
                            @endforeach
                        </ol>

                    </nav>
                </div>s
            </div>
            <!--end breadcrumb-->


            <a href="" type="button" class="btn btn-primary px-4 right">Add Allocation</a>
 @foreach($leavegroup as $key => $item)
             <h5 class="mb-0">Leave Group: {{$item->leavegroup_id == $leavegroup->id ? $leavegroup->name :''}}</h5>
@endforeach


            <br><br><div class="card">
                <div class="card-body" >
                    <div class="card-header px-4 py-3">
                        @foreach($leaveallocation as $key => $item)
                            <div class="col-sm-12 text-secondary">
                        <h5 class="mb-0">Leave Group:  {{ $item->leavegroup_id = $group->id ? $group->leave_group:''}}</h5>
                            </div>
                        @endforeach
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Employee Name</th>
                                <th>Leave Group</th>
                                <th>Leave Types</th>
                                <th>Total</th>
                                <th>Supervisor</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leaveallocation as $key => $item)
                                <tr>
                                    <td> {{ $key+1}} </td>
                                    <td> {{ $item ['EmployeeName']['name']}} </td>
                                    <td> {{ $item ['EmployeeLeaveGroup']['leave_group']}} </td>
                                    <td> {{ $item ['EmployeeLeavetype']['Leave_name']}} </td>
                                    <td>{{ $item->total }}</td>
                                    <td> {{ $item ['SupervisorName']['supervisors_name']}} </td>


                                    <td>
                                        <a href="" class="btn btn-info sm" title="Edit Data"><i class="lni lni-highlight-alt"></i></a>
                                        <a href="" class="btn btn-danger sm"  id="delete"><i class="lni lni-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>





                <!-- end of body -->

            </div>


        </div>
    </div>
    <!--end page wrapper -->








@endsection
