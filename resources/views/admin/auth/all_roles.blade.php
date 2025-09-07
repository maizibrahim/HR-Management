@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">All Roles</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Roles</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <a href="{{route('roles.add')}}" type="button" class="btn btn-primary px-4 right">Add Role</a>


          <br><br><div class="card">
                <div class="card-body" >
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Roles Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $key => $item)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $item->name }} </td>

                                <td>
                                    <a href="{{route('roles.edit', $item->id)}}" class="btn btn-sm btn-primary" title="Edit Data"><i class="fadeIn animated bx bx-edit-alt"></i></a>
                                    <a href="{{route('roles.delete', $item->id)}}" class="btn btn-sm btn-danger"  id="delete"><i class="fadeIn animated bx bx-trash-alt"></i></a>
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
