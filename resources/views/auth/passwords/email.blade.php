<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>Forgot Password | SevenUPP</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{url('css/dashlite.css')}}">
    <link id="skin-default" rel="stylesheet" href="{{url('css/theme.css')}}">
</head>
<body class="nk-body bg-login npc-default pg-auth">
    <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="login-wrap">
                        <div class="login-box">
                            <div class="login-box-row">
                                <div class="login-box-col-left">
                                    <div class="login-form">
                                        <div class="brand-logo">
                                            <a href="javascript:window.location.reload(true)" class="logo-link">
                                                <img class="logo-light logo-img logo-img-lg" src="{{url('images/logo-dark.png')}}" srcset="{{url('images/logo-dark.png')}}" alt="logo">
                                                <img class="logo-dark logo-img logo-img-lg" src="{{url('images/logo-dark.png')}}" srcset="{{url('images/logo-dark.png')}}" alt="logo-dark">
                                            </a>
                                        </div>
                                        <h5 class="nk-block-title">Cloud B2B Order Management Application for Single or Multi-brand Distribution Companies</h5>
                                        <div class="login-graphic m-hide">
                                            <img src="{{url('images/b2b.png')}}" alt="" />
                                        </div>
                                        <div class="login-form-bottom">
                                            <h5>A simple and powerful App to automate and accelerate your business</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="login-box-col-right">
                                    <div class="login-form">
                                        <div class="nk-block-head">
                                            <div class="nk-block-head-content">
                                                <h5 class="nk-block-title">Reset password</h5>
                                            </div>
                                        </div><!-- .nk-block-head -->
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
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
                                        <form method="POST" action="{{ url('password/send-link') }}">
                                            @csrf
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label for="email" class="form-label">{{ __('E-Mail Address') }} <span class="text-danger">*</span></label>
                                                </div>
                                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-lg btn-orange btn-block">Send Reset Link</button>
                                            </div>
                                        </form><!-- form -->
                                        <div class="form-note-s2 pt-2 text-center">
                                            <a href="{{ url('login') }}"><strong>Back to login</strong></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-block nk-auth-footer">
                                
                                <div class="mt-3">
                                    <p>&copy; 2024 laravel-forms. All Rights Reserved.</p>÷
                                </div>
                            </div><!-- .nk-block -->
                        </div>
                    </div>
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
    <script src="{{url('js/bundle.js')}}"></script>
    <script src="{{url('js/parsley.min.js')}}"></script>
    <script src="{{url('js/scripts.js')}}"></script>

    <!-- <script src="./assets/js/charts/chart-ecommerce.js?ver=1.9.0"></script> -->
</body>
</html>