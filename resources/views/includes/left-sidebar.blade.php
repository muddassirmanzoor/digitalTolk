<!-- Left Sidebar End -->
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-light">
                    <span class="logo-lg">
                        <img class="logo-inner" src="{{ asset('assets/images/logo.jpg') }}" alt="">
                    </span>
        <span class="logo-sm">
                        <img class="logo-sm-inner" src="{{ asset('assets/images/logo.jpg') }}" alt="" height="16">
                    </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">Navigation</li>
            @php
                $userRole = auth()->user()->getRoleNames()->first(); // Assuming role is stored in user table
            @endphp
            @if ($userRole == 'deo' || $userRole == 'reviewer'  || $userRole == 'manager')
                <li class="side-nav-item">
                    <a href="{{url('data-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Data List</span>
                    </a>
                </li>
                @elseif( $userRole == 'transport')
                <li class="side-nav-item">
                    <a href="{{url('driver-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Driver List</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('operations-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Operations List</span>
                    </a>
                </li>
            @elseif ($userRole == 'admin')
                <li class="side-nav-item">
                    <a href="{{url('data-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Data List</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('user-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span> User List</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('agent-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Agent List</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('driver-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Driver List</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('operations-list')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Operations List</span>
                    </a>
                </li>
            @elseif ($userRole == 'minister')
                <li class="side-nav-item">
                    <a href="{{url('minister-dashboard')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @elseif ($userRole == 'operations')
                <li class="side-nav-item">
                    <a href="{{url('add-interviewer')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>Add Interviewer</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('list-interviewer')}}" class="side-nav-link">
                        <i class="uil-copy-alt"></i>
                        <span>List Interviewer</span>
                    </a>
                </li>
            @else
            <li class="side-nav-item">
                <a href="{{url('dashboard')}}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false" aria-controls="sidebarPages" class="side-nav-link">
                    <i class="uil-copy-alt"></i>
                    <span>All Reports Listing </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{url('district-list')}}">District Listing</a>
                        </li>
                        <li>
                            <a href="{{url('school-list')}}">Schools Listing</a>
                        </li>
                        <li>
                            <a href="{{url('teacher-list')}}">Teachers Listing</a>
                        </li>
                        <li>
                            <a href="{{url('ranking-detail')}}">Ranking Detail</a>
                        </li>
                        <li>
                            <a href="{{url('teacher-appeared')}}">Teacher Appeared</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="side-nav-item">
                <a href="{{url('logout')}}" class="side-nav-link">
                    <i class="mdi mdi-logout me-1"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
