@extends('layouts.app')

@section('content')
@php
    $userPermission = \Session::get('userPermission');
    $userRole = \Session::get('role');
    $userRole = $userRole[0];
@endphp

    <div class="nk-block table-compact pt-28">
        <h5>Email : {{ \Auth::user()->email }}</h5>        
    </div><!-- .nk-block -->

    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Student Results</h3>
                <p>You have total <span class="record_count">{{ $resultsCount }}</span> results.</p>
            </div><!-- .nk-block-head-content -->
            
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    
    <div class="nk-block table-compact pt-28">
        <!--  Filter Tag List -->
        <div id="filter_tag_list" class="filter-tag-list"></div>
        <!-- -->
        <div class="nk-tb-list is-separate mb-3">
            <table class="broadcast-init nowrap nk-tb-list is-separate" data-auto-responsive="true">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col"><span class="sub-text">First Name</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Last Name</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Student Report</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Test Results</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Time(s) Taken</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Last Attempted On</span></th>
                        <th class="nk-tb-col nk-tb-col-tools text-right w-1" nowrap="true">
                            <span class="sub-text">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div><!-- .nk-block -->
        <div class="modal fade zoom" tabindex="-1" id="modalFilterUser">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <form role="form" class="mb-0" method="get" action="#">
                    @csrf
                    <div class="modal-body modal-body-lg">
                        <div class="gy-3">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="First Name" for="firstName" suggestion="Specify the name of the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.text value="" for="firstName" icon="user" placeholder="Name" name="name"/>
                                </div>
                            </div>
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Mobile Number" for="contact_number" suggestion="Specify the mobile number of the user."  />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.text value="" for="contact_number" icon="call" placeholder="Mobile Number" name="contact_number"
                                    data-parsley-pattern="{{ \Config::get('constants.REGEX.VALIDATE_MOBILE_NUMBER_LENGTH') }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="userId" name="user_id" value="0">
                    <div class="modal-footer bg-light">
                        <div class="row">
                            <div class="col-lg-12 p-0 text-right">
                                <button class="btn btn-outline-light" data-dismiss="modal" aria-label="Close">Cancel</button>
                                <button class="btn btn-danger resetFilter" data-dismiss="modal" aria-label="Close">Clear Filter</button>
                                <button class="btn btn-primary submitBtn" type="button">Submit</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@push('footerScripts')
<script src="{{url('js/tableFlow.js')}}"></script>
<script type="text/javascript">

    


    $(function() {

        

        var root_url = "<?php echo url('/'); ?>";

        var logUrl = root_url + '/user/logs';
        NioApp.getAuditLogs('.broadcast-init','.audit_logs','resourceid',logUrl,'#modalLogs');

        var items = [
            '#firstName'
        ];
        var user_table = "";
        user_table = new CustomDataTable({
            tableElem: '.broadcast-init',
            option: {
                processing: true,
                serverSide: true,
                ajax: {
                    type:"GET",
                    url: "{{ url('stats/student-results') }}",
                },
                columns: [
                    {
                        "class": "nk-tb-col ",
                        data: 'f_name',
                        name: 'f_name'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'l_name',
                        name: 'l_name'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'student_report',
                        name: 'student_report'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'test_result',
                        name: 'test_result'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'times_taken',
                        name: 'times_taken'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'created_at',
                        name: 'created_at'
                    },
                    
                    {
                        "class": "nk-tb-col  text-right",
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "fnDrawCallback":function(){
                    NioApp.BS.tooltip('[data-toggle="tooltip"]'); 
                    $('.changePassword').click(function(){
                        var resourceId = $(this).attr('data-resourceid');
                        $('#password_user_id').val(resourceId);
                        $('#modalUserPassword').modal('show');
                    });
                }
            },
            filterSubmit: '.submitBtn',
            filterSubmitCallback: function(){
                $('#modalFilterUser').modal('toggle');
            },
            filterClearSubmit: '.resetFilter',
            filterModalId: '#modalFilterUser',
            filterItems: items,
            tagId: '#filter_tag_list',
        });

        $('.broadcast-init').on("change","#check-all",function() {
            // If checked
            if (this.checked)
                $('.cb-check').prop('checked', true);
            else
                $('.cb-check').prop('checked', false);
        });

    });
</script>
@endpush