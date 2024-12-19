\@extends('layouts.app')
<style type="text/css">
    .nk-tb-list{
        font-size: 16px !important;
    }
    .card-title-sm .title,.card-inner{
        font-size: 24px !important;
    }
</style>
@section('content')
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title"> Dashboard</h3>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
@endsection
@push('footerScripts')
<script src="{{url('js/APIDataTable.js')}}"></script>
@endpush