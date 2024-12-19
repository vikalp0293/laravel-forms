@extends('layouts.app')
@php
$userPermission = \Session::get('userPermission');
$organization_type = \Session::get('organization_type');
$currentOrganization = \Session::get('currentOrganization');
@endphp
@section('content')
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title"><a href="javascript:history.back()" class="pt-3"><em class="icon ni ni-chevron-left back-icon"></em> </a> Add User</h3>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <form role="form" method="post" enctype="multipart/form-data" >
        @csrf
        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <div class="sp-plan-action card-inner">
                            <div class="icon">
                                <h6 class="o-5">Role Information</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Role" for="role" required="true" suggestion="Select the role of the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.select  size="sm" required="true" name="role" for="role" data-search="on" class="roles">
                                        @if(count($roles) > 1)
                                        <option value="">Select Role</option>
                                        @endif
                                        @foreach ($roles as $role)

                                        <option
                                        @if(isset($user) && $user->role == $role->id)
                                        selected
                                        @elseif(old('role') == $role->id) 
                                        selected
                                        @endif
                                        value="{{ $role->id }}">{{ $role->label }}</option>
                                        @endforeach
                                    </x-inputs.select>
                                    @if ($errors->has('role'))
                                        <span class="text-danger">{{ $errors->first('role') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->
        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <div class="sp-plan-action card-inner">
                            <div class="icon">
                                
                                <h6 class="o-5">Personal Information</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Name" for="name" suggestion="Specify the first name of the user." required="true" />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.text  value="{{ isset($user) ? $user->name : old('name') }}" for="name" icon="user" placeholder="Name" name="name" required="true" />
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Email" for="email" suggestion="Specify the email of the user." required="true" />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.email value="{{ isset($user) ? $user->email : old('email') }}" for="email" icon="mail" required="true" placeholder="Email" name="email" />
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Mobile Number" for="mobileNumber" suggestion="Specify the mobile number of the user." required="true" />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.text value="{{ isset($user) ? $user->phone_number : old('mobileNumber') }}" for="mobileNumber" icon="call" required="true" placeholder="Mobile Number" name="mobileNumber"
                                    data-parsley-pattern="{{ \Config::get('constants.REGEX.VALIDATE_MOBILE_NUMBER_LENGTH') }}"
                                    />
                                    @if ($errors->has('mobileNumber'))
                                        <span class="text-danger">{{ $errors->first('mobileNumber') }}</span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Approved" for="approved" suggestion="Specify the approval of the user." required="true" />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.switch for="approved" size="md" name="approved"/>
                                </div>
                            </div> -->
                            
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->
        
        @if(!isset($user))
        <div class="nk-block userFoundBox">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <div class="sp-plan-action card-inner">
                            <div class="icon">
                                <h6 class="o-5">Security</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Password" for="password" suggestion="Specify the password of the user." required="true" />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.password value="" for="password" required="true" icon="lock" placeholder="Password" name="password" class="userFoundInput"/>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Confirm Password" for="password_confirmation" suggestion="Specify the confirm password of the user." required="true" />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.password value="" for="password_confirmation" icon="lock" placeholder="Confirm password" name="password_confirmation" class="userFoundInput"/>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->
        @endif
        
        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <div class="sp-plan-action card-inner">
                            <div class="icon">
                                <h6 class="o-5">Approval</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Approved" for="approved" suggestion="Specify the approval of the user." />
                                </div>
                                <div class="col-lg-7">

                                    @php
                                        if(isset($user) && $user->status){
                                            $checked = 'true';
                                        }else{
                                            $checked = '';
                                        }
                                    @endphp
                                    <x-inputs.switch for="approved" size="md" name="approved" checked={{$checked}}/>
                                    @if ($errors->has('approved'))
                                        <span class="text-danger">{{ $errors->first('approved') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->        
        <div class="nk-block">
            @isset($user)
                <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
            @endisset
            <div class="row">
                <div class="col-md-12">
                    <div class="sp-plan-info pt-0 pb-0 card-inner">  
                            <div class="row">
                                <div class="col-lg-7 text-right offset-lg-5">
                                    <div class="form-group">
                                        <a href="javascript:history.back()" class="btn btn-outline-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                    </div><!-- .sp-plan-info -->
                </div><!-- .col -->
            </div><!-- .row -->
        </div>
        <input type="hidden" name="userFound" id="userFound" value="0">
    </form>
    <input type="hidden" name="role_type" id="role_type" value="{{old('role')}}">
    <input type="hidden" name="old_district" id="old_district" value="{{old('district')}}">
    <input type="hidden" name="old_city" id="old_city" value="{{old('city')}}">


    <script type="text/javascript">

        $(document).ready(function(){
            var root_url = "<?php echo url('/'); ?>";

            $('.checkUser').blur(function(){

                var userFound = $('#userFound').val();

                if(userFound == 0){
                    var root_url = "<?php echo url('/'); ?>";
                    $.ajax({
                        url: root_url + '/user/check-user',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "searchKey": $(this).val(),
                            "role": $('#role').val()
                        },
                        //dataType: "html",
                        method: "POST",
                        cache: false,
                        success: function (response) {
                            if(response.user){
                                var user = response.user;
                                if(confirm("A user already exists with the same information. do you want to populate the same data ?")){
                                    $('#firstName').val(user.name).prop('readonly',true);
                                    $('#lastName').val(user.last_name).prop('readonly',true);
                                    $('#email').val(user.email).prop('readonly',true);
                                    $('#mobileNumber').val(user.phone_number).prop('readonly',true);
                                    $('#shopName').val(user.shop_name).prop('readonly',true);
                                    $('#gst').val(user.gst).prop('readonly',true);
                                    $('#address1').val(user.address1).prop('readonly',true);
                                    $('#address2').val(user.address2).prop('readonly',true);
                                    $('#pincode').val(user.pincode).prop('readonly',true);
                                    $('#state').val(user.stateId).trigger('change');
                                    $('#userFound').val(user.id);
                                    
                                    changeDistrict(user.districtId);
                                    changeCity(user.districtId,user.cityId);

                                    $('#district').prop('readonly',true);
                                    $('#city').prop('readonly',true);
                                    $('#state').prop('readonly',true);
                                    $('#country').prop('readonly',true);

                                    $('.userFoundBox').hide();
                                    $('.userFoundInput').prop( "disabled", true );
                                    $('.userFoundInput').prop( "required", false );
                                }
                            }else{
                                $('#firstName').prop('readonly',false);
                                $('#lastName').prop('readonly',false);
                                $('#email').prop('readonly',false);
                                $('#mobileNumber').prop('readonly',false);
                                $('#shopName').prop('readonly',false);
                                $('#gst').prop('readonly',false);
                                $('#address1').prop('readonly',false);
                                $('#address2').prop('readonly',false);
                                $('#pincode').prop('readonly',false);
                                $('#userFound').val(0)

                                $('.userFoundBox').show();
                                $('.userFoundInput').prop( "disabled", false );
                                $('.userFoundInput').prop( "required", true );
                            }
                        }
                    });

                }

            });

            $('.removeMedia').click(function(){
                if(confirm("Are you sure you want to delete this?")){
                    var id = $(this).attr('data-id');
                    var root_url = "<?php echo url('/'); ?>";
                    $.ajax({
                        url: root_url + '/user/remove-image/'+id,
                        data: {
                        },
                        //dataType: "html",
                        method: "GET",
                        cache: false,
                        success: function (response) {
                            if(response.success){
                                $('.media_box').remove();
                            }
                        }
                    });
                }
                else{
                    return false;
                }
            });

            var distributorRole = "{{\Config::get('constants.ROLES.SELLER')}}";
            var retailerRole = "{{\Config::get('constants.ROLES.BUYER')}}";
            var dspRole = "{{\Config::get('constants.ROLES.SP')}}";

            var role_type = $('#role_type').val();
            var userRole = $( "#role option:selected" ).text().toLowerCase();
            var userRole = userRole.split(' ').join('_');
            if(userRole == dspRole){
                $('.brandsBox').show();
            }

            if(role_type == "" || role_type != retailerRole){
                $('.buyer-section').hide();
                $( ".buyerFileds" ).prop( "disabled", true );
                $( ".buyerFileds" ).prop( "required", false );
            }

            var checkrole = $( "#role option:selected" ).text().toLowerCase().split(' ').join('_');
            if(checkrole == dspRole){   
                var organizations = $('#organization').val();
                getOrganizationBrand(organizations);
            }

            $("#role").on("change", function () {
                var role = $( "#role option:selected" ).text().toLowerCase().split(' ').join('_');
                if(role != dspRole){
                    $('.brandsBox').hide();
                }else{
                    $('.brandsBox').show();
                    var organizations = $('#organization').val();
                    getOrganizationBrand(organizations);
                }
            });

            $("#organization").on("change", function () {
                var role = $( "#role option:selected" ).text().toLowerCase().split(' ').join('_');
                if(role == dspRole){
                    var organizations = $('#organization').val();
                    if(organizations != ""){
                        getOrganizationBrand(organizations);
                    }
                }
            });

            function getOrganizationBrand(organizations){
                $.ajax({
                    url: root_url + '/user/organization-brand/?organizations='+organizations,
                    data: {
                    },
                    //dataType: "html",
                    method: "GET",
                    cache: false,
                    success: function (response) {

                        console.log(response);
                        $("#brands").html('');

                        var mappedBrands = $('#mappedBrands').val();
                        var mappedBrandsArr = mappedBrands.split(",");

                        $.each(response.brands, function (key, value) {
                            if(mappedBrandsArr.includes(value.id.toString())){
                                var brand = '<div class="custom-control mr-2 mt-1 mb-1 custom-checkbox custom-control-md">\
                                            <input type="checkbox" class="custom-control-input" id="'+value.name+'" value="'+value.id+'" name="brands[]" checked>\
                                            <label class="custom-control-label" for="'+value.name+'">'+value.name+'\
                                            </label>\
                                            </div>';
                            }else{

                                var brand = '<div class="custom-control mr-2 mt-1 mb-1 custom-checkbox custom-control-md">\
                                            <input type="checkbox" class="custom-control-input" id="'+value.name+'" value="'+value.id+'" name="brands[]" >\
                                            <label class="custom-control-label" for="'+value.name+'">'+value.name+'\
                                            </label>\
                                            </div>';
                            }

                            $("#brands").append(brand); 
                        });
                    }
                });
            }
            
            var mappedBrands = $('#mappedBrands').val();
            if(mappedBrands != ""){
                var organizations = $('#organization').val();
                if(organizations != ""){
                    getOrganizationBrand(organizations);
                }
            }

            var old_district = $('#old_district').val();
            if(old_district != ""){
                changeDistrict();
            }

            var old_city = $('#old_city').val();
            if(old_city != ""){
                var old_district = $('#old_district').val();
                changeCity(old_district);
            }

            $("#state").on("change", function () {
                changeDistrict();
            });

            function changeDistrict(userDistrict = 0){
                var state = $('#state').val();
                $.ajax({
                    url: root_url + '/user/districts/'+state,
                    data: {
                    },
                    //dataType: "html",
                    method: "GET",
                    cache: false,
                    success: function (response) {
                        $("#district").html('');
                        $("#district").append($('<option></option>').val('').html('Select district'));
                        $.each(response.districts, function (key, value) {
                            if(value.id != 0) {
                                if(value.id == old_district || value.id == userDistrict) {
                                    $("#district").append($('<option></option>').val(value.id).html(value.name).prop('selected', 'selected'));    
                                } else {
                                    $("#district").append($('<option></option>').val(value.id).html(value.name));
                                }
                            }
                        });
                    }
                });
            }

            $("#district").on("change", function () {
                var district = $('#district').val();
                changeCity(district);
            });

            function changeCity(district,userCity = 0){
                var root_url = "<?php echo url('/'); ?>";
                
                $.ajax({
                    url: root_url + '/user/cities/'+district,
                    data: {
                    },
                    //dataType: "html",
                    method: "GET",
                    cache: false,
                    success: function (response) {
                        $("#city").html('');
                        $("#city").append($('<option></option>').val('').html('Select city'));
                        $.each(response.cities, function (key, value) {
                            if(value.id != 0) {
                                if(value.id == old_city || value.id == userCity) {
                                    $("#city").append($('<option></option>').val(value.id).html(value.name).prop('selected', 'selected'));    
                                } else {
                                    $("#city").append($('<option></option>').val(value.id).html(value.name));
                                }
                            }
                        });
                    }
                });
            }

        });


    </script>
@endsection
