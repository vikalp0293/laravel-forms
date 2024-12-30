@extends('layouts.app')
@section('content')
<style>
    .data-item .data-label {
        width: 30%
    }

    .data-item .data-value {
        width: 100%
    }

    .data-item .data-col-end {
        width: 50px
    }

    .data-item .data-value .select2,
    .data-item .data-value .select2 span {
        display: grid;
    }
</style>
<div class="nk-block">
    <div class="card">
        <div class="card-aside-wrap">
            <div class="card-inner card-inner-lg">
                <div class="nk-block-head nk-block-head-lg">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h4 class="nk-block-title">Personal Information</h4>
                            <div class="nk-block-des">
                            </div>
                        </div>
                        <div class="nk-block-head-content align-self-start d-lg-none">
                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="nk-data data-list">
                        <form role="form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="data-item inputs_user_details">
                                <div class="data-col">
                                    <span class="data-label">Full Name</span>
                                    <span class="data-value data-input">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <x-inputs.text value="{{ $user->first_name }}" for="first_name" name="first_name" label="First Name" />
                                                </div>
                                                <div class="col">
                                                    <x-inputs.text value="{{ $user->last_name }}" for="lastName" name="last_name" label="Last Name" />
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>

                            <div class="data-item inputs_user_details">
                                <div class="data-col">
                                    <span class="data-label">Email</span>
                                    <span class="data-value data-input">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <x-inputs.text value="{{ $user->email }}" required="true" for="email" name="email" label="Email" onkeyup="checkEmail(this.value)"/>
                                                    <small style="display: none;" id="email-error" class="text-danger">Verify the new email before updating the profile.</small>
                                                    <span style="display: none;" id="email-success" class="text-warning">A verification email is sent on you new email please verify.</span>
                                                </div>
                                                <div class="col">
                                                    <br>
                                                    @if(!is_null($user->verified_at))
                                                    <button id="verifiedTag"  style="margin-top: 8px;" class="btn btn-success">Verified</button>
                                                    @endif

                                                    <input type="hidden" value="{{ $user->email }}" id="existingEmail">

                                                    <button id="verifyBtn" onclick="verifyEmail()" style="display: none;" style="margin-top: 8px;" href="javascript:void(0);" class="btn btn-secondary">Verify Email</button>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="data-item inputs_user_details">
                                <div class="data-col">
                                    <span class="data-label">Grade & Subject</span>
                                    <span class="data-value data-input">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <select id="grade" name="grade" class="form-control @error('grade') is-invalid @enderror" required>
                                                        <option value="" selected disabled>Select Grade</option>
                                                        @foreach($grades as $id => $grade)
                                                            <option 
                                                            @if($user->grade_id == $grade->id)
                                                            selected
                                                            @endif
                                                            value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <select id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" required>
                                                        <option value="" selected disabled>Select Subject</option>
                                                        @forelse($subjects as $key => $subject)
                                                            <option 
                                                            @if($user->subject_id == $subject->id)
                                                            selected
                                                            @endif
                                                            value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>

                            <div class="data-item inputs_user_details">
                                <div class="data-col">
                                    <span class="data-label">Country & State</span>
                                    <span class="data-value data-input">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">

                                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Country" data-parsley-errors-container=".categoryParsley" name="country_id" id="parentCat" data-search='on' onchange="getStates(this.options[this.selectedIndex].value)" required> 
                                                        <option value="" selected disabled>Select Country</option>
                                                        @foreach($countries as $id => $country)
                                                            <option 
                                                            @if($user->country == $country->id)
                                                            selected
                                                            @endif
                                                            value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">

                                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select State" data-parsley-errors-container=".categoryParsley" name="state" id="state" data-search='on' required> 
                                                        <option value="" selected disabled>Select State</option>
                                                        @foreach($states as $id => $state)
                                                            <option 
                                                            @if($user->state == $state->id)
                                                            selected
                                                            @endif
                                                            value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>  
                            </div>

                            <div class="data-item inputs_user_details">
                                <div class="data-col">
                                    <span class="data-label">School District & City</span>
                                    <span class="data-value data-input">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <x-inputs.text value="{{ $user->district }}"  for="district" name="district" label="School District" />
                                                </div>
                                                <div class="col">
                                                    <x-inputs.text value="{{ $user->city }}"  for="city" name="city" label="City" />
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group text-right my-2" id="action_user_details">
                                <a class="btn btn- btn-outline-light" href="javascript:history.back()">Cancel</a>
                                <x-button buttonType="primary" id='submitBtn' type="submit">
                                    Update Profile
                                </x-button>
                            </div>
                        </form>
                    </div><!-- data-list -->
                </div><!-- .nk-block -->
            </div>
        </div><!-- .card-aside-wrap -->
    </div><!-- .card -->
</div><!-- .nk-block -->


<script type="text/javascript">

    
    function checkEmail(email) {
        var root_url = "<?php echo Request::root(); ?>";
        var existingEmail = $('#existingEmail').val();
        if(email != existingEmail){
            $('#email-error').show();
            $('#verifiedTag').hide();
            $('#verifyBtn').show();

            $('#submitBtn').prop('disabled', true);
        }else{
            $('#email-error').hide();
            $('#verifiedTag').show();
            $('#verifyBtn').hide();

            $('#submitBtn').prop('disabled', false);
        }
        
    }

    
    function verifyEmail() {
        var root_url = "<?php echo Request::root(); ?>";
        var email = $("#email").val();
        $.ajax({
            url: root_url + '/profile/trigger-verification-email/' + email,
            data: {},
            //dataType: "html",
            method: "GET",
            cache: false,
            success: function(response) {
                $('#email-error').hide();
                $('#email-success').show();
            }
        });

    }

    function getStates(country_id) {
        var root_url = "<?php echo Request::root(); ?>";
        //var country_id = $(".country_id").val();
        $.ajax({
            url: root_url + '/profile/get-states/' + country_id,
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

@endsection


