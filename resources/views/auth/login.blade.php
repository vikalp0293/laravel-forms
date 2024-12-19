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
    <title>Login | Forms</title>
    <!-- StyleSheets  -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/dashlite.css')}}">
    <link id="skin-default" rel="stylesheet" href="{{url('css/theme.css')}}">
</head>
<body class="nk-body bg-login npc-default pg-auth">
    <div class="nk-app-root">
            <!-- main @s -->
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
                                        <h5 class="nk-block-title"></h5>
                                        <div class="login-graphic m-hide">
                                            <img src="{{url('images/b2b.png')}}" alt="" />
                                        </div>
                                        <div class="login-form-bottom">
                                            <h5></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="login-box-col-right">
                                    <div class="login-form">
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
                                        <form method="POST" action="{{ url('post-login') }}">
                                            @csrf
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="default-01">{{ __('E-Mail Address') }} <span class="text-danger">*</span></label>
                                                </div>

                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div><!-- .foem-group -->
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                                                    @if (Route::has('password.request'))
                                                        <a class="btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password?') }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="form-control-wrap">
                                                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                    </a>
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div><!-- .foem-group -->
                                            <div class="form-group">
                                                <button type="submit"  class="btn btn-lg btn-orange btn-block">{{ __('Login') }}</button>
                                                <p></p>
                                                <a class="btn-link" href="{{url('register')}}">Don't have an account? Sign Up</a></p>
                                            </div>
                                        </form><!-- form -->
                                    </div>
                                </div>
                            </div>
                            <div class="nk-block nk-auth-footer">
                                <div class="nk-block-between">
                                </div>
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
        <!-- main @e -->
    </div>
    <script src="{{url('js/bundle.js')}}"></script>
        <script src="{{url('js/parsley.min.js')}}"></script>
        <script src="{{url('js/scripts.js')}}"></script>
        <!-- <script src="./assets/js/charts/chart-ecommerce.js?ver=1.9.0"></script> -->
        <script>
            $(document).ready(function(){
                $('form').parsley();
            });
        </script>
</body>
</html>