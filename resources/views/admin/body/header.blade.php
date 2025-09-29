<!--start header -->
<header>
    <nav class="navbar navbar-expand-lg navbar-light rounded-0 bg-white fixed-top rounded-0 shadow-none border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/images/logo-img.png" width="140" alt="" />
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="#"><i
                                class='bx bx-home-alt me-1'></i>Personal</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#"><i class='bx bx-user me-1'></i>Device</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#"><i
                                class='bx bx-category-alt me-1'></i>Attendance</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#"><i
                                class='bx bx-microphone me-1'></i>Leave</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#"><i
                                class='bx bx-microphone me-1'></i>System</a>
                    </li>
                </ul>
            </div>
                        <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">

                    <li class="nav-item dropdown dropdown-large">
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-notifications-list">
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown dropdown-large">
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-message-list">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            @php
                $id = Auth::user()->id;
                $userData = \App\Models\User::find($id);
                $classification = \App\Models\designation\classification::all();

            @endphp

            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ !empty($userData->profile) ? url('upload/profile_images/' . $userData->profile) : url('upload/no_image.jpg') }}"
                        class="user-img" alt="user avatar">
                    <div class="user-info">
                        <p class="user-name mb-0">{{ $userData->name }}</p>
                        @foreach ($classification as $class)
                            <p class="designattion mb-0">
                                {{ $userData->classification_id == $class->id ? $class->name : '' }}</p>
                        @endforeach
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}"><i
                                class="bx bx-user fs-5"></i><span>Profile</span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('change.password') }}"><i
                                class="bx bx-cog fs-5"></i><span>change password</span></a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile.logout') }}"><i
                                class="bx bx-log-out-circle"></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>




        </div>
    </nav>







    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
            </div>





            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">

                    <li class="nav-item dropdown dropdown-large">
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-notifications-list">
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown dropdown-large">
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-message-list">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            @php
                $id = Auth::user()->id;
                $userData = \App\Models\User::find($id);
                $classification = \App\Models\designation\classification::all();

            @endphp

            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ !empty($userData->profile) ? url('upload/profile_images/' . $userData->profile) : url('upload/no_image.jpg') }}"
                        class="user-img" alt="user avatar">
                    <div class="user-info">
                        <p class="user-name mb-0">{{ $userData->name }}</p>
                        @foreach ($classification as $class)
                            <p class="designattion mb-0">
                                {{ $userData->classification_id == $class->id ? $class->name : '' }}</p>
                        @endforeach
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}"><i
                                class="bx bx-user fs-5"></i><span>Profile</span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('change.password') }}"><i
                                class="bx bx-cog fs-5"></i><span>change password</span></a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile.logout') }}"><i
                                class="bx bx-log-out-circle"></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<!--end header -->
