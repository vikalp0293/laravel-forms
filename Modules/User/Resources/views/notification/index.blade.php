@extends('layouts.app')
@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Notification</h3>
            <p>You have total <span id="count"></span> notifications.</p>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary" id="mark_all_as_read_1"><em class="icon ni ni-check-round"></em><span>Mark all as read</span></a></li>
                    </ul>
                </div>
            </div><!-- .toggle-wrap -->
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="nk-notification" id="notifications_table"></div>

    <div class="row g-3">
        <div class="col-lg-7 offset-lg-5">
            <div class="form-group mt-2">
                <button type="button" class="btn btn-primary" id="load-more"><em class="icon ni ni-reload"></em><span>Load More</span></button>
            </div>
        </div>
    </div>
</div><!-- .nk-block -->
@endsection

@push('footerScripts')
<script src="{{url('js/all-notifications.js')}}"></script>
@endpush