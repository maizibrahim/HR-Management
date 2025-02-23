@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">All Leave Groups</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Leave Groups</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{route('leave.group.add')}}" type="button" class="btn btn-primary px-4 right">Add Leave Group</a>


            <br><br><div class="card">
                <div class="card-body" >
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Leave Group</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leavegroup as $key => $item)
                                <tr>
                                    <td> {{ $key+1}} </td>
                                    <td> {{ $item->leave_group }} </td>

                                    <td>
                                        <a href="{{route('leave.group.edit', $item->id)}}" class="btn btn-info sm" title="Edit Data"><i class="lni lni-highlight-alt"></i></a>
                                        <a href="{{route('leave.group.detail',$item->id)}}" class="btn btn-success sm" id="fin" title="View Data"><i class="lni lni-eye"></i></a>
                                        <a href="{{route('leave.group.delete', $item->id)}}" class="btn btn-danger sm"  id="delete"><i class="lni lni-trash"></i></a>
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
