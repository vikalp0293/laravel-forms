<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Registration form for new users.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>Register | Forms</title>
    <!-- StyleSheets  -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/dashlite.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


 
    <link id="skin-default" rel="stylesheet" href="{{url('css/theme.css')}}">
    {!! RecaptchaV3::initJs() !!}
</head>
<body class="nk-body bg-login npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content">
                    <div class="login-wrap">
                        <div class="login-box">
                            <div class="login-box-row">
                                <div class="login-box-col-left" style="flex: 0 0 calc(100% - 700px); max-width: calc(100% - 700px);">
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
                                <div class="login-box-col-right" style="padding-top: 2rem; flex: 0 0 700px; max-width: 700px;">
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

                                        @if (session()->has('success'))
                                            <div class="alert alert-success alert-icon alert-dismissible">
                                                <em class="icon ni ni-check-circle"></em>
                                                {{ session('success') }}<button class="close" data-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ url('registeruser') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                                                        </div>
                                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="email">E-Mail Address <span class="text-danger">*</span></label>
                                                        </div>
                                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required
                                                            data-parsley-minlength="8" 
                                                            data-parsley-minlength-message="Password must be at least 8 characters long"
                                                            >
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                                                            data-parsley-equalto="#password" 
                                                            data-parsley-equalto-message="Passwords must match"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <select id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" required>
                                                                <option value="" selected disabled>Select Subject</option>
                                                                @forelse($subjects as $key => $subject)
                                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="grade-confirm">Grade <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <select id="grade" name="grade" class="form-control @error('grade') is-invalid @enderror" required>
                                                                <option value="" selected disabled>Select Grade</option>
                                                                @foreach($grades as $id => $grade)
                                                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="country">Country <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Country" data-parsley-errors-container=".categoryParsley" name="country_id" id="parentCat" data-search='on' onchange="getStates(this.options[this.selectedIndex].value)" required> 
                                                                <option value="" selected disabled>Select Country</option>
                                                                @foreach($countries as $id => $country)
                                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="state">State <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <select id="state" name="state" class="form-control @error('state') is-invalid @enderror" required>
                                                                <option value="" selected disabled> Select State </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="form-control-wrap">
                                                            <x-inputs.datePicker for="dob" size="sm" placeholder="Select Date" name="dob" icon="calendar" required='true'/>
                                                            @error('dob')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" style="margin-top: 30px;">
                                                    <div class="form-group">
                                                        <div class="form-label-group">
                                                            <label class="form-label" for="terms">
                                                                <input type="checkbox" id="terms" name="terms" class="@error('terms') is-invalid @enderror" required>
                                                                I accept the <a href="{{ url('terms-and-conditions') }}" target="_blank">Terms and Conditions</a> <span class="text-danger">*</span>
                                                            </label>
                                                        </div>
                                                        @error('terms')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                                    <div class="col-md-6">
                                                        {!! RecaptchaV3::field('register') !!}
                                                        @if ($errors->has('g-recaptcha-response'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <br>          
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-orange btn-block">Register</button>
                                                <p></p>
                                                <a class="btn-link" href="{{url('login')}}">Already have an account? Login</a></p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
    <script>
        $(document).ready(function(){
            $('form').parsley();
        });

        function getStates(country_id) {
            var root_url = "<?php echo Request::root(); ?>";
            //var country_id = $(".country_id").val();
            $.ajax({
                url: root_url + '/get-states-by-country/' + country_id,
                data: {},
                //dataType: "html",
                method: "GET",
                cache: false,
                success: function(response) {
                    $("#state").html('');
                    $("#state").append($('<option value="" selected disabled></option>').val('').html('Select State'));

                    $.each(response.states, function(key, value) {
                        if (value.id != 0) {
                            if (value.id == country_id) {
                                $("#state").append($('<option></option>').val(value.id).html(value.name).prop('selected', 'selected'));
                            } else {
                                $("#state").append($('<option></option>').val(value.id).html(value.name));
                            }
                        }
                    });
                }
            });

        }

    </script>
</body>
</html>
