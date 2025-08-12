@extends('admin.admin_master')
@section('admin')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Assign Supervisor</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="/dashboard"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">All Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Assign Supervisor</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Employee: <strong>{{ $user->name }}</h5>
                        </div>
                        <div class="card-body p-4">
                           <form method="POST" action="{{ route('users.update-supervisor', $user) }}">
                         @csrf
                         @method('PATCH')

                          <div class="mb-3">
                        <label for="supervisor_id" class="form-label">Current Supervisor</label>
                        <div class="alert alert-info">
                            @if($user->supervisor)
                                <i class="fas fa-user"></i> {{ $user->supervisor->name }}
                            @else
                                <i class="fas fa-exclamation-circle"></i> No supervisor assigned
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="supervisor_id" class="form-label">New Supervisor</label>
                        <select class="form-select @error('supervisor_id') is-invalid @enderror"
                                id="supervisor_id" name="supervisor_id">
                            <option value="">Remove Supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}"
                                        {{ $user->supervisor_id == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->name }}
                                    @if($supervisor->subordinates->count() > 0)
                                        ({{ $supervisor->subordinates->count() }} subordinates)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('supervisor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Select a supervisor who will be responsible for approving this employee's leave requests.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Supervisor
                        </button>
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
