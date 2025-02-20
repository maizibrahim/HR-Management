

<body>
<!--wrapper-->
<div class="wrapper">
    <!--sidebar wrapper -->
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="{{asset('assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text">HRM</h4>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
            </div>
        </div>
        <!--navigation-->

        <ul class="metismenu" id="menu">
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i>
                    </div>
                    <div class="menu-title">Dashboard</div>
                </a>
                <ul>
                    <li> <a href="index.html"><i class='bx bx-radio-circle'></i>Default</a>
                    </li></ul>
            </li>

            <li class="menu-label">Employee Management</li>

            <li>
                <a href="{{route('employee.all')}}">
                    <div class="parent-icon"><i class='bx bx-group'></i>
                    </div>
                    <div class="menu-title">Employee</div>
                </a>
            </li>
            <li>
                <a href="{{route('salary.all')}}">
                    <div class="parent-icon"><i class='bx bx-money'></i>
                    </div>
                    <div class="menu-title">Salary</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="parent-icon"><i class='bx bxs-ambulance'></i>
                    </div>
                    <div class="menu-title">Leave</div>
                </a>
            </li>


            <li class="menu-label">Leave Management</li>
            <li>
                <a href="">
                    <div class="parent-icon"><i class='bx bxs-first-aid'></i>
                    </div>
                    <div class="menu-title">Leave Request</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="parent-icon"><i class='bx lni-first-aid'></i>
                    </div>
                    <div class="menu-title">Leave Approval</div>
                </a>
            </li>

            <li class="menu-label">System Management</li>

            <li> <a class="has-arrow" >
                    <div class="parent-icon"><i class='bx bx-briefcase'></i></div>
                    <div class="menu-title">Designation</div>
                </a>
                <ul>
                    <li> <a href="{{route('classification.all')}}"><i class='bx bx-radio-circle'></i>Classification</a></li>
                    <li> <a href="{{route('rank.all')}}"><i class='bx bx-radio-circle'></i>Rank</a></li>
                </ul>
            </li>
            <li> <a href="{{route('country.all')}}">
                    <div class="parent-icon"><i class='bx bx-world'></i></div>
                    <div class="menu-title">Country</div>
                </a>
            </li>
            <li> <a class="has-arrow" >
                    <div class="parent-icon"><i class='bx lni-ambulance'></i></div>
                    <div class="menu-title">Leave</div>
                </a>
                <ul>
                    <li> <a href="{{route('leave.type.all')}}"><i class='bx bx-radio-circle'></i>Leave Type</a></li>
                    <li> <a href=""><i class='bx bx-radio-circle'></i>Leave Group</a></li>
                </ul>
            </li>

            <li class="menu-label">Roles & Permission</li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-lock"></i></div>
                    <div class="menu-title">Authentication</div>
                </a>
                <ul>
                    <li><a href="{{route('permission.all')}}"><i class='bx bx-radio-circle'></i>Permission</a></li>
                    <li><a href="{{route('roles.all')}}"><i class='bx bx-radio-circle'></i>Roles</a></li>
                    <li><a href="{{route('role.permission.all')}}"><i class='bx bx-radio-circle'></i>Roles Permission</a></li>

                </ul>
            </li>
            <li>
                <a href="{{route('user.all')}}">
                    <div class="parent-icon"><i class="bx bx-user-circle"></i>
                    </div>
                    <div class="menu-title">Manage Users</div></a>

            </li>



        </ul>

        <!--end navigation-->
    </div>
    <!--end sidebar wrapper -->

    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button-->
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->

</div>
<!--end wrapper-->





</body>

</html>
