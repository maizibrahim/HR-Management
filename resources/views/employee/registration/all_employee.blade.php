@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">All Employees</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Employees</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{route('employee.add')}}" type="button" class="btn btn-primary px-4 right">Add Employee</a>


            <br><br><div class="card">
                <div class="card-body" >
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>ID/PP NO</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Join Date</th>
                                @if(Auth::user()->role == "admin")
                                <th>Code</th>
                                @endif
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user as $key => $item)
                                <tr>
                                    <td> {{ $key+1}} </td>
                                    <td><img src="{{ (!empty($item->profile))? url('upload/profile_images/'.$item->profile):url('upload/no_image.jpg') }}" style="width: 60px; height: 40px;" > </td>
                                    <td> {{ $item->idnumber }} </td>
                                    <td> {{ $item->name}} </td>
                                    <td> {{ $item->gender}} </td>
                                    <td> {{ $item->join_date}} </td>
                                    @if(Auth::user()->role == "admin")
                                        <td> {{ $item->code }} </td>
                                    @endif

                                    <td>
                                        <a href="{{route('employee.edit',$item->id)}}" class="btn btn-info sm" title="Edit Data"><i class="lni lni-highlight-alt"></i></a>
                                       <a href="{{route('employee.detail',$item->id)}}" class="btn btn-success sm" id="fin" title="View Data"><i class="lni lni-eye"></i></a>
                                        <a href="{{route('employee.delete',$item->id)}}" class="btn btn-danger sm"  id="delete" title="Delete Data"><i class="lni lni-trash"></i></a>

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
