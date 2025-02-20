@extends('admin.admin_master')
@section('admin')




    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Edit Role Permission</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="/all/role/permission">Role Permission</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Role Permission</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Role Permission</h5>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="{{route('role.permission.update',$role->id)}}" >
                                @csrf

                                <h4>Permission Role: <B>{{$role->name}}</B></h4>

                                <div class="row mb-3">
                                    <div class="col-sm-9">

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Permission all
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @foreach($permission_groups as $group)
                                    <div class="row">
                                        @php
                                            $permissions = \App\Models\User::getpermissionByGroupName($group->group_name)

                                        @endphp

                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="flexCheckDefault" {{App\Models\User::roleHasPermissions($role,$permissions) ? 'checked' : ''}} >
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{$group->group_name}}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-9">
                                            <div class="col-sm-3">
                                                @foreach($permissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permission[]" id="flexCheckDefault {{$permission->id}}" value="{{$permission->name}}" {{$role->hasPermissionTo($permission->name) ? 'checked' : ''}} >
                                                        <label class="form-check-label" for="flexCheckDefault{{$permission->id}}">
                                                            {{$permission->name}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>

                                @endforeach
                                <div class="row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4" name="submit">Save changes</button>
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

    <script type="text/javascript">

        $('#flexCheckDefault').click(function (){
            if ($(this).is(':checked')){
                $('input[type= checkbox]').prop('checked',true);
            }else {
                $('input[type= checkbox]').prop('checked',false);
            }
        });

    </script>


@endsection
