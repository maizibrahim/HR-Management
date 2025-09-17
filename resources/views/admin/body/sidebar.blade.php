

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
                <a href="{{route('dashboard')}}" >
                    <div class="parent-icon"><i class='bx bx-home-alt'></i>
                    </div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>


                <li class="menu-label">Leave Management</li>
            @if(Auth::user()->can('leave.request.menu'))
            <li>
                <a href="{{route ('leave-requests.index')}}">
                    <div class="parent-icon"><i class='bx bxs-first-aid'></i>
                    </div>
                    <div class="menu-title">Leave Request</div>
                </a>
            </li>
            @endif
            @if(Auth::user()->can('leave-requests.approval-menu'))
            <li>
                <a href="{{route ('leave-requests.approval-list')}}">
                    <div class="parent-icon"><i class='bx lni-first-aid'></i>
                    </div>
                    <div class="menu-title">Leave Approval</div>
                </a>
            </li>
            @endif

            @if(Auth::user()->can('leave.bulk-menu'))
             <li>
                <a href="{{route ('leave-requests.bulk-create')}}">
                    <div class="parent-icon"><i class='bx bxs-network-chart'></i>
                    </div>
                    <div class="menu-title">Leave Bulk</div>
                </a>
            </li>
            @endif

            @if(Auth::user()->can('leave.records-menu'))
            <li>
                <a href="{{route ('hr.leave-records.index')}}">
                    <div class="parent-icon"><i class='bx bxs-ambulance'></i>
                    </div>
                    <div class="menu-title">Leave Records</div>
                </a>
            </li>
            @endif

             @if(Auth::user()->can('setup-menu'))
            <li class="menu-label">System Management</li>
             @if(Auth::user()->can('designation.setup-menu'))
            <li> <a class="has-arrow" >
                    <div class="parent-icon"><i class='bx bx-briefcase'></i></div>
                    <div class="menu-title">Designation</div>
                </a>
                <ul>
                    <li> <a href="{{route('classification.all')}}"><i class='bx bx-radio-circle'></i>Classification</a></li>
                    <li> <a href="{{route('rank.all')}}"><i class='bx bx-radio-circle'></i>Rank</a></li>
                </ul>
            </li>
            @endif
             @endif
            @if(Auth::user()->can('country.setup-menu'))
            <li> <a href="{{route('country.all')}}">
                    <div class="parent-icon"><i class='bx bx-world'></i></div>
                    <div class="menu-title">Country</div>
                </a>
            </li>
            @endif
            @if(Auth::user()->can('leave.setup-menu'))
            <li> <a class="has-arrow" >
                    <div class="parent-icon"><i class='bx lni-ambulance'></i></div>
                    <div class="menu-title">Leave</div>
                </a>
                <ul>
                    @if(Auth::user()->can('leave.group-menu'))
                     <li> <a href="{{route('leave-groups.index')}}"><i class='bx bx-radio-circle'></i>Leave Group</a></li>
                    @endif
                     @if(Auth::user()->can('leave.types-menu'))
                    <li> <a href="{{route('leave-types.index')}}"><i class='bx bx-radio-circle'></i>Leave Type</a></li>
                    @endif
                </ul>
            </li>
             @if(Auth::user()->can('public.holiday-menu'))
            <li>
                <a href="{{ route('public-holidays.index') }}">
                    <div class="parent-icon"><i class='lni lni-island'></i>
                    </div>
                    <div class="menu-title">Public Holiday</div>
                </a>
            </li>
            @endif
            @endif
            @if(Auth::user()->can('role.permission-menu'))

            <li class="menu-label">Roles & Permission</li>
              @if(Auth::user()->can('authentication-menu'))
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-lock"></i></div>
                    <div class="menu-title">Authentication</div>
                </a>
                <ul>
                    <li><a href="{{route('permission.all')}}"><i class='bx bx-radio-circle'></i>Permission</a></li>
                    <li><a href="{{route('roles.all')}}"><i class='bx bx-radio-circle'></i>Roles</a></li>
                    <li><a href="{{route('role.permission.all')}}"><i class='bx bx-radio-circle'></i>Roles Permission</a></li>
                    <li><a href="{{route('user.all')}}"><i class='bx bx-radio-circle'></i>User Roles</a></li>

                </ul>
            </li>
            @endif
            @endif
              @if(Auth::user()->can('emp-mngment-menu'))
            <li><a class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-group'></i>
                    </div><div class="menu-title">Employee Management</div>
                </a>
                <ul>
                    <li> <a href="{{route('users.index')}}"><i class='bx bx-radio-circle'></i>User Management</a></li>
                    <li> <a href="{{route('supervisor-management')}}"><i class='bx bx-radio-circle'></i>Supervisor Management</a></li>
                </ul>
            </li>
            @endif
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
