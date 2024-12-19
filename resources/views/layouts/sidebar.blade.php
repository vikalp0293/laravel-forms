@php
$userRole = \Session::get('role');
$userRole = $userRole[0];
@endphp



<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{url('/dashboard')}}" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{url('images/logo-dark.png')}}" srcset="{{url('images/logo-dark.png')}}" alt="logo">
                <img class="logo-dark logo-img" src="{{url('images/logo-dark.png')}}" srcset="{{url('images/logo-dark.png')}}" alt="logo-dark">
                <!-- <img class="logo-small logo-img logo-img-small" src="{{url('images/logo-small.png')}}" srcset="{{url('images/logo-small2x.png 2x')}}" alt="logo-small"> -->
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <!-- <li class="nk-menu-heading">
                                    <h6 class="overline-title text-primary-alt">Dashboard</h6>
                                </li> -->
                    <!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{url('/dashboard')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-growth-fill"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->

                    @if($userRole == 'superadmin')
                    <li class="nk-menu-item">
                        <a href="{{url('/user')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Users</span>
                        </a>
                    </li>
                   

                    <!-- <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Users</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{url('user')}}" class="nk-menu-link"><span class="nk-menu-text">Customers</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{url('user/staff/')}}" class="nk-menu-link"><span class="nk-menu-text">Staff</span></a>
                            </li>
                        </ul>
                    </li> -->

                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-activity-round-fill"></em></span>
                            <span class="nk-menu-text">Masters</span>
                        </a>
                        <ul class="nk-menu-sub">
                            
                            <li class="nk-menu-item">
                                <a href="{{url('/masters/subject')}}" class="nk-menu-link"><span class="nk-menu-text">Subjects</span></a>
                            </li>

                            <li class="nk-menu-item">
                                <a href="{{url('/masters/grade')}}" class="nk-menu-link"><span class="nk-menu-text">Grades</span></a>
                            </li>

                            <li class="nk-menu-item">
                                <a href="{{url('/masters/country')}}" class="nk-menu-link"><span class="nk-menu-text">Countries</span></a>
                            </li>

                            <li class="nk-menu-item">
                                <a href="{{url('/masters/state')}}" class="nk-menu-link"><span class="nk-menu-text">States</span></a>
                            </li>

                            
                        </ul>
                    </li>

                    @endif
                    
                    
                </ul>
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>