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
                                <p>Basic info, like your name and address.</p>
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
                                                    <x-inputs.text value="{{ $user->name }}" required="true" for="firstName" name="name" label="First Name" />
                                                </div>
                                                <div class="col">
                                                    <x-inputs.text value="{{ $user->last_name }}" required="true" for="lastName" name="last_name" label="Last Name" />
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div><!-- data-item -->
                            <div class="data-item">
                                <div class="data-col">
                                    <span class="data-label">Email</span>
                                    <span class="data-value">{{ $user->email }}</span>
                                </div>
                                <div class="data-col data-col-end">
                                    <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                </div>
                            </div><!-- data-item -->
                            <div class="data-item">
                                <div class="data-col">
                                    <span class="data-label">Phone Number</span>
                                    <span class="data-value text-soft">{{ $user->phone_number }}</span>
                                </div>
                                <div class="data-col data-col-end">
                                    <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                </div>
                            </div><!-- data-item -->
                            <div class="data-item inputs_user_details">
                                <div class="data-col">
                                    <span class="data-label">Profile Image</span>

                                    @if(isset($user) && !is_null($user->file))
                                    <span class="data-value data-value-text">
                                        <img src="{{url('uploads/users/'.$user->file)}}" width="60" class="rounded-circle z-depth-2" />
                                    </span>
                                    @else
                                    <span class="data-value data-value-text">
                                        <img src="https://cdn4.iconfinder.com/data/icons/small-n-flat/24/user-alt-512.png" width="60" class="rounded-circle z-depth-2" />
                                    </span>
                                    @endif

                                    <span class="data-value data-input">
                                        <div class="form-control-wrap">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile" name="file"><label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div><!-- data-item -->
                            <div class="form-group text-right my-2" id="action_user_details">
                                <a class="btn btn- btn-outline-light" href="javascript:history.back()">Cancel</a>
                                <x-button buttonType="primary" type="submit">
                                    Update Profile
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
                            <li><a class="active" href="{{url('/profile')}}"><em class="icon ni ni-user-fill-c"></em><span>Profile</span></a></li>
                            {{-- <li><a href="{{url('/profile/address')}}"><em class="icon ni ni-info-fill"></em><span>Address</span></a></li> --}}
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


