@extends('layouts.app')
@section('content')
<div class="nk-block">
    <div class="card">
        <div class="card-aside-wrap">
            <div class="card-inner card-inner-lg">
                <div class="nk-block-head nk-block-head-lg">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h4 class="nk-block-title">Security Settings</h4>
                            <div class="nk-block-des">
                                <p>These settings are helps you keep your account secure.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content align-self-start d-lg-none">
                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card">
                        <div class="card-inner-group">
                            <div class="card-inner">
                                <div class="between-center flex-wrap g-3">
                                    <div class="nk-block-text">
                                        <h6>Change Password</h6>
                                        <p>Set a unique password to protect your account.</p>
                                    </div>
                                    <div class="nk-block-actions flex-shrink-sm-0">
                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                            <li class="order-md-last">
                                                <a href="#" class="btn btn-primary" data-toggle="modal" title="filter" data-target="#reset_password">Change Password</a>
                                            </li>
                                            {{-- <li>
                                                <em class="text-soft text-date fs-12px">Last changed: <span>Oct 2, 2019</span></em>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                        {{-- @if (count($errors))
                            <p class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{$error}}</p>
                            @endforeach
                            </p>
                        @endif --}} 
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div><!-- .card-inner -->
            <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                <div class="card-inner-group">
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
                        </div>
                    </div><!-- .card-inner -->
                    
                    <div class="card-inner p-0">
                        <ul class="link-list-menu">
                            <li><a class="" href="{{url('/profile')}}"><em class="icon ni ni-user-fill-c"></em><span>Profile</span></a></li>
                            {{-- <li><a href="{{url('/profile/address')}}"><em class="icon ni ni-info-fill"></em><span>Address</span></a></li> --}}
                            <!-- <li><a href="{{url('/user/profile/notification')}}"><em class="icon ni ni-bell-fill"></em><span>Notifications</span></a></li> -->
                            <li><a class="active" href="{{url('/profile/setting')}}"><em class="icon ni ni-lock-alt-fill"></em><span>Security Settings</span></a></li>
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card-inner-group -->
            </div><!-- .card-aside -->
        </div><!-- .card-aside-wrap -->
    </div><!-- .card -->
</div><!-- .nk-block -->
@php
$filterAction = array(
array(
'label' => 'Submit',
'type' => 'primary',
'click' => '',
)
);
@endphp

<x-ui.modal modalId="reset_password" title="Change Password" :footerActions="$filterAction">
    <form action="" role="form" class="mb-0" method="post">
        @csrf

        <div class="modal-body modal-body-lg">
            <div class="gy-3">
                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <x-inputs.verticalFormLabel label="Old Password" for="oldPassword" required="true" />
                    </div>
                    <div class="col-lg-7">
                    <x-inputs.password value="" for="oldPassword" icon="unlock-fill" required="true" name="oldPassword" />
                    </div>
                </div>
                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <x-inputs.verticalFormLabel label="New Password" for="newPassword" required="true" />
                    </div>
                    <div class="col-lg-7">
                    <x-inputs.password value="" for="newPassword" icon="lock-fill" required="true" name="newPassword" />
                    </div>
                </div>
                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <x-inputs.verticalFormLabel label="Confirm New Password" for="confirmPassword" required="true" />
                    </div>
                    <div class="col-lg-7">
                    <x-inputs.password value="" for="confirmPassword" icon="lock-fill" required="true" name="confirmPassword" />
                    </div>
                </div>
            </div>
        </div>
</x-ui.modal>
@endsection