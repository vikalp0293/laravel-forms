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
                            <h4 class="nk-block-title">Address</h4>
                        
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
                                    <span class="data-value data-input">
                                        <div class="my-1">
                                            <x-inputs.text value="{{ $user->address1 }}" required="true" for="address1" name="address1" label="Address 1" />
                                        </div>
                                        <div class="my-1">
                                            <x-inputs.text value="{{ $user->address2 }}" for="address2" name="address2" label="Address 2" />
                                        </div>
                                        <div class="row my-1">
                                            <div class="col-sm-6">
                                                <x-inputs.select size="md" required="true" name="country" for="country" label="Country" data-search="on">
                                                    <option value="103">India</option>
                                                </x-inputs.select>
                                            </div>
                                            <div class="col-sm-6">

                                                <x-inputs.select size="md" name="state" required="true" for="state" data-search="on" label="State">
                                                    @foreach ($states as $key => $state)
                                                    <option @if(isset($user) && $user->state == $state->id)
                                                        selected
                                                        @elseif(old('state') == $state->id)
                                                        selected
                                                        @endif
                                                        value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach
                                                </x-inputs.select>
                                            </div>
                                        </div>
                                        <div class="row my-1">
                                            <div class="col-sm-6">
                                                <x-inputs.select size="md" name="district" required="true" for="district" label="District" data-search="on">
                                                    @if(isset($user) && $user->district)
                                                    @foreach ($districts as $key => $district)
                                                    <option @if(isset($user) && $user->district == $district->id)
                                                        selected
                                                        @elseif(old('district') == $district->id)
                                                        selected
                                                        @endif
                                                        value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </x-inputs.select>
                                            </div>
                                            <div class="col-sm-6">
                                                <x-inputs.select size="md" name="city" required="true" for="city" label="City" data-search="on">
                                                    @if(isset($user) && $user->city)
                                                    @foreach ($cities as $key => $city)
                                                    <option @if(isset($user) && $user->city == $city->id)
                                                        selected
                                                        @elseif(old('city') == $city->id)
                                                        selected
                                                        @endif
                                                        value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </x-inputs.select>
                                            </div>
                                        </div>
                                        <x-inputs.text value="{{ $user->pincode }}" for="pincode" icon="map-pin" required="true" label="Pincode" placeholder="Pincode" name="pincode" data-parsley-pattern="{{ \Config::get('constants.REGEX.VALIDATE_ZIP_CODE') }}"/>
                                    </span>
                                </div>
                                
                            </div><!-- data-item -->
                            <div class="form-group text-right my-2" id="action_user_details">
                                <a class="btn btn- btn-outline-light" href="javascript:history.back()">Cancel</a>
                                <x-button buttonType="primary" type="submit">
                                    Update Address
                                </x-button>
                            </div>
                        </form>
                    </div><!-- data-list -->
                </div><!-- .nk-block -->
            </div>
            <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                <div class="card-inner-group" data-simplebar>
                    <div class="card-inner">
                        <div class="user-card">
                            <div class="user-avatar bg-primary">
                                <span>{{ \Helpers::getAcronym($user->FullName) }}</span>
                            </div>
                            <div class="user-info">
                                <span class="lead-text">{{ ucfirst($user->FullName) }}</span>
                                <span class="sub-text">{{ $user->email }}</span>
                            </div>
                            {{-- <div class="user-action">
                                <div class="dropdown">
                                    <a class="btn btn-icon btn-trigger mr-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><em class="icon ni ni-camera-fill"></em><span>Change Photo</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-edit-fill"></em><span>Update Profile</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div> --}}
                        </div><!-- .user-card -->
                    </div><!-- .card-inner -->

                    <div class="card-inner p-0">
                        <ul class="link-list-menu">
                            <li><a class="" href="{{url('/profile')}}"><em class="icon ni ni-user-fill-c"></em><span>Profile</span></a></li>
                            <li><a class="active" href="{{url('/profile/address')}}"><em class="icon ni ni-info-fill"></em><span>Address</span></a></li>
                            <!-- <li><a href="{{url('/user/profile/notification')}}"><em class="icon ni ni-bell-fill"></em><span>Notifications</span></a></li> -->
                            <li><a href="{{url('/profile/setting')}}"><em class="icon ni ni-lock-alt-fill"></em><span>Security Settings</span></a></li>
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card-inner-group -->
            </div><!-- card-aside -->
        </div><!-- .card-aside-wrap -->
    </div><!-- .card -->
</div><!-- .nk-block -->
@endsection


@push('footerScripts')
<script type="text/javascript">

    $("#state").on("change", function() {
        changeDistrict();
    });

    function changeDistrict(userDistrict = 0) {
        var state = $('#state').val();
        var root_url = "<?php echo url('/'); ?>";
        $.ajax({
            url: root_url + '/user/districts/' + state,
            data: {},
            //dataType: "html",
            method: "GET",
            cache: false,
            success: function(response) {
                $("#district").html('');
                $("#district").append($('<option></option>').val('').html('Select district'));
                $.each(response.districts, function(key, value) {
                    if (value.id != 0) {
                        $("#district").append($('<option></option>').val(value.id).html(value.name));
                    }
                });
            }
        });
    }

    $("#district").on("change", function() {
        var district = $('#district').val();
        changeCity(district);
    });

    function changeCity(district, userCity = 0) {
        var root_url = "<?php echo url('/'); ?>";

        $.ajax({
            url: root_url + '/user/cities/' + district,
            data: {},
            //dataType: "html",
            method: "GET",
            cache: false,
            success: function(response) {
                $("#city").html('');
                $("#city").append($('<option></option>').val('').html('Select city'));
                $.each(response.cities, function(key, value) {
                    if (value.id != 0) {
                        $("#city").append($('<option></option>').val(value.id).html(value.name));
                    }
                });
            }
        });
    }
</script>
@endpush