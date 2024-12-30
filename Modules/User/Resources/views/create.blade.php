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
                <h3 class="nk-block-title page-title"><a href="javascript:history.back()" class="pt-3"><em class="icon ni ni-chevron-left back-icon"></em> </a> Edit User</h3>
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
                                
                                <h6 class="o-5">Personal Information</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Email" for="email" suggestion="Specify the email of the user." required="true" />
                                </div>
                                <div class="col-lg-7">


                                    <input type="email" required="true" data-parsley-errors-container=".parsley-container-email" id="email" value="{{ isset($user) ? $user->email : old('email') }}" name="email" maxlength="50" class="form-control" readonly autocomplete="off">

                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="First Name" for="first_name" suggestion="Specify the first_name of the user."  />
                                </div>
                                <div class="col-lg-7">


                                    <input type="first_name" data-parsley-errors-container=".parsley-container-first_name" id="first_name" value="{{ isset($user) ? $user->first_name : old('first_name') }}" name="first_name" maxlength="50" class="form-control" autocomplete="off">

                                    @if ($errors->has('first_name'))
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Last Name" for="last_name" suggestion="Specify the last_name of the user."  />
                                </div>
                                <div class="col-lg-7">


                                    <input type="last_name" data-parsley-errors-container=".parsley-container-last_name" id="last_name" value="{{ isset($user) ? $user->last_name : old('last_name') }}" name="last_name" maxlength="50" class="form-control" autocomplete="off">

                                    @if ($errors->has('last_name'))
                                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
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
                                <h6 class="o-5">Subject & Grade</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Subject" for="subject" required="true" suggestion="Select the subject of the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.select  size="sm" required="true" name="subject" for="subject" data-search="on" class="roles">
                                        <option value="">Select subject</option>
                                        
                                        @foreach ($subjects as $subject)

                                        <option
                                        @if(isset($user) && $user->subject_id == $subject->id)
                                        selected
                                        @elseif(old('subject') == $subject->id) 
                                        selected
                                        @endif
                                        value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </x-inputs.select>
                                    @if ($errors->has('subject'))
                                        <span class="text-danger">{{ $errors->first('subject') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Grade" for="grade" required="true" suggestion="Select the grade of the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.select  size="sm" required="true" name="grade" for="grade" data-search="on" class="roles">
                                        <option value="">Select grade</option>
                                        
                                        @foreach ($grades as $grade)

                                        <option
                                        @if(isset($user) && $user->grade_id == $grade->id)
                                        selected
                                        @elseif(old('grade') == $grade->id) 
                                        selected
                                        @endif
                                        value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @endforeach
                                    </x-inputs.select>
                                    @if ($errors->has('grade'))
                                        <span class="text-danger">{{ $errors->first('grade') }}</span>
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
                                <h6 class="o-5">Country & State</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Country" for="country" required="true" suggestion="Select the country of the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.select  size="sm" required="true" name="country" for="country" data-search="on" class="roles">
                                        <option value="">Select Country</option>
                                        
                                        @foreach ($countries as $country)

                                        <option
                                        @if(isset($user) && $user->country == $country->id)
                                        selected
                                        @elseif(old('country') == $country->id) 
                                        selected
                                        @endif
                                        value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </x-inputs.select>
                                    @if ($errors->has('country'))
                                        <span class="text-danger">{{ $errors->first('country') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="State" for="state" required="true" suggestion="Select the state of the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.select  size="sm" required="true" name="state" for="state" data-search="on" class="roles">
                                        <option value="">Select state</option>
                                        
                                        @foreach ($states as $state)

                                        <option
                                        @if(isset($user) && $user->state == $state->id)
                                        selected
                                        @elseif(old('state') == $state->id) 
                                        selected
                                        @endif
                                        value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </x-inputs.select>
                                    @if ($errors->has('state'))
                                        <span class="text-danger">{{ $errors->first('state') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="District" for="district" suggestion="Specify the district of the user."  />
                                </div>
                                <div class="col-lg-7">


                                    <input type="district" data-parsley-errors-container=".parsley-container-district" id="district" value="{{ isset($user) ? $user->district : old('district') }}" name="district" maxlength="50" class="form-control" autocomplete="off">

                                    @if ($errors->has('district'))
                                        <span class="text-danger">{{ $errors->first('district') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="City" for="city" suggestion="Specify the city of the user."  />
                                </div>
                                <div class="col-lg-7">


                                    <input type="city" data-parsley-errors-container=".parsley-container-city" id="city" value="{{ isset($user) ? $user->city : old('city') }}" name="city" maxlength="50" class="form-control" autocomplete="off">

                                    @if ($errors->has('city'))
                                        <span class="text-danger">{{ $errors->first('city') }}</span>
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
                                <h6 class="o-5">Status</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="sp-plan-info card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Status" for="Status" suggestion="Specify the status of the user." />
                                </div>
                                <div class="col-lg-7">

                                    @php
                                        if(isset($user) && !is_null($user->verified_at)){
                                            $checked = 'true';
                                        }else{
                                            $checked = '';
                                        }
                                    @endphp
                                    <x-inputs.switch for="status" size="md" name="status" checked={{$checked}}/>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
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
   


    <script type="text/javascript">

        $(document).ready(function(){
            var root_url = "<?php echo url('/'); ?>";

            

        });


    </script>
@endsection
