<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token For security -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Laravel-Forms</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/dashlite.css?t='.time())}}">
    <link id="skin-default" rel="stylesheet" href="{{url('css/theme.css?t='.time())}}">
   
    <script src="{{url('js/jquery-3.5.1.min.js?t='.time())}}"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.3.1.min.js" integrity="sha256-APllMc0V4lf/Rb5Cz4idWUCYlBDG3b0EcN1Ushd3hpE=" crossorigin="anonymous"></script>

    <script src="{{url('js/firebase-app.js?t='.time()) }}"></script>
    <script src="{{url('js/firebase-auth.js?t='.time()) }}"></script>
    <script src="{{url('js/firebase-firestore.js?t='.time()) }}"></script>
    <script src="{{url('js/firebase-database.js?t='.time()) }}"></script>
    <script src="{{url('js/print.min.js?t='.time())}}"></script>

    @php
    $loggedInUser = \Auth::user();
    @endphp
    <script type="text/javascript">
        var APP_BASE_URL =  "{{ url('/') }}";
        var LOGIN_USER_ID = {{ $loggedInUser->id }};
        
    </script>

    @stack('headerScripts')
</head>

<body class="nk-body bg-lighter npc-default has-sidebar ui-bordered">
    <div class="overlay"></div>
    <style type="text/css">
        body.loading {
            overflow: hidden;
        }

        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay {
            display: block;
        }


        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.8) url("{{ asset('images/loader.gif') }}") center no-repeat;
        }
    </style>
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            @include('layouts.sidebar')
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                <div class="nk-header nk-header-fixed is-light">
                    <div class="container-fluid">
                        <div class="nk-header-wrap">
                            <div class="nk-menu-trigger d-xl-none ml-n1">
                                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                            </div>
                            <div class="nk-header-brand d-xl-none">
                                <a href="{{url('/dashboard')}}" class="logo-link">
                                    <img class="logo-light logo-img" src="{{url('images/logo-dark.png')}}" srcset="{{url('images/logo-dark.png')}}" alt="logo">
                                    <img class="logo-dark logo-img" src="{{url('images/logo-dark.png')}}" srcset="{{url('images/logo-dark.png')}}" alt="logo-dark">
                                </a>
                            </div><!-- .nk-header-brand -->
                            <!-- <div class="nk-header-search ml-3 ml-xl-0">
                                <em class="icon ni ni-search"></em>
                                <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search anything">
                            </div> -->

                            <!-- .nk-header-news -->
                            <div class="nk-header-tools">
                                <ul class="nk-quick-nav">
                                    @if(isset($organization_type) && $organization_type == 'MULTIPLE')
                                    @php
                                        $userOrganizations = \Session::get('userOrganizations');
                                    @endphp
                                    <li>
                                        Current Organization : {{ $currentOrganizationName }}
                                    </li>
                                    <li>
                                        <select class="form-control select"  size="sm" name="system_organization" id="system_organization">
                                            <option value="">Select Organization</option>
                                            @if(!empty($userOrganizations))
                                                @forelse ($userOrganizations as $userOrganization)
                                                <option 
                                                @if($currentOrganization == $userOrganization['id']) selected @endif
                                                value="{{ $userOrganization['id'] }}">{{ ucwords($userOrganization['name']) }}</option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                    </li>
                                    @endif
                                    <li class="dropdown notification-dropdown">
                                        {{-- <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                                            <div class="icon-status icon-status-info" id="ni-bell"><em class="icon ni ni-bell"></em></div>
                                        </a> --}}
                                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                            <div class="dropdown-head">
                                                <span class="sub-title nk-dropdown-title">Notifications</span>
                                                <a href="#" id="mark_all_as_read">Mark All as Read</a>
                                            </div>
                                            <div class="dropdown-body">
                                                <div class="nk-notification" id="nk-notification">

                                                </div><!-- .nk-notification -->
                                            </div><!-- .nk-dropdown-body -->
                                            <div class="dropdown-foot center">
                                                {{-- <a href="{{ url('/user/notification') }}">View All</a> --}}
                                                <a href="#">View All</a>
                                            </div>
                                        </div>
                                    </li>
                                    



                                    <li class="dropdown user-dropdown">
                                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                                            <div class="user-toggle">
                                                <div class="user-avatar sm">
                                                    <em class="icon ni ni-user-alt"></em>
                                                </div>
                                                <div class="user-info d-none d-xl-block">
                                                    {{-- <div class="user-status user-status-unverified">Unverified</div> --}}
                                                    <div class="user-name dropdown-indicator">{{ ucfirst(\Session::get('name')) }}</div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                                <div class="user-card">
                                                    <div class="user-avatar">
                                                        <span>{{ \Helpers::getAcronym(\Session::get('name')) }}</span>
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="lead-text">{{ ucfirst(\Session::get('name')) }}</span>
                                                        <span class="sub-text">{{ \Session::get('email') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li><a href="{{ url('profile') }}"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                                    <li><a href="{{ url('profile/setting') }}"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                                </ul>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li>
                                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();"><em class="icon ni ni-signout"></em><span>Sign out</span></a>
                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .nk-header-wrap -->
                    </div><!-- .container-fliud -->
                </div>
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                @if (session()->has('message'))
                                <div class="alert alert-success alert-icon alert-dismissible">
                                    <em class="icon ni ni-check-circle"></em>
                                    {{ session('message') }}<button class="close" data-dismiss="alert"></button>
                                </div>
                                @endif
                                @if (session()->has('error'))
                                <div class="alert alert-danger alert-icon alert-dismissible">
                                    <em class="icon ni ni-cross-circle"></em>
                                    {{ session('error') }}<button class="close" data-dismiss="alert"></button>
                                </div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger alert-icon alert-dismissible">
                                    <em class="icon ni ni-cross-circle"></em>
                                    @forelse ($errors->all() as $validation_error)
                                        <p>{{ $validation_error }}</p>
                                    @empty
                                        {{-- empty expr --}}
                                    @endforelse
                                    </div>
                                @endif

                                @yield('content')

                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer @s -->
                <div class="nk-footer">
                    <div class="container-fluid">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright"> &copy; {{ date('Y') }} Laravel-Forms.
                            </div>
                            <div class="nk-footer-links">
                                <ul class="nav nav-sm">
                                    <!-- <li class="nav-item"><a class="nav-link" href="{{ url('terms') }}">Terms</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('privacy') }}">Privacy</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('help') }}">Help</a></li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <script src="{{url('js/bundle.js?ver=1.9.0')}}"></script>
    <script src="{{url('js/scripts.js?ver=1.9.0')}}"></script>
    {{-- <script src="{{url('js/chart-ecommerce.js')}}"></script> --}}
    <script src="{{url('js/parsley.min.js')}}"></script>
    <script src="{{url('js/top-notifications.js')}}"></script>
    @stack('footerScripts')
    <script src="{{url('js/common.js?t='.time())}}"></script>
    <script src="{{url('js/jquery.simplePagination.js')}}"></script>
    <script type="text/javascript">
        $(document).on({
            ajaxStart: function() {
                $("body").addClass("loading");
            },
            ajaxStop: function() {
                $("body").removeClass("loading");
            }
        });
        $(document).ready(function(){
            $('#system_organization').change(function(){
                var root_url = "<?php echo url('/'); ?>";
                var org_id = $(this).val();
                var org_name = $( "#system_organization option:selected" ).text();

                $.ajax({
                    url: root_url + '/user/set-organization/?org_name='+org_name+'&org_id='+org_id,
                    data: {
                    },
                    method: "GET",
                    cache: false,
                    success: function (response) {
                        if(response.success){
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>