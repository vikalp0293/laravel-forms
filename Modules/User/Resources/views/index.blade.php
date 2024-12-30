@extends('layouts.app')

@section('content')
@php
    $userPermission = \Session::get('userPermission');
@endphp
	<div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Users</h3>
                <p>You have total <span class="record_count">{{ $usersCount }}</span> users.</p>
            </div><!-- .nk-block-head-content -->
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                    <div class="toggle-expand-content" data-content="more-options">
                        <ul class="nk-block-tools g-3">
                            <li>
                                <div class="form-inline flex-nowrap input-group gx-3">
                                    <select class="form-select form-select-sm" data-search="off" data-placeholder="Bulk Action" id="mass-status">
                                        <option value="" selected disabled>Bulk Action</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="input-group-prepend">
                                        <span class="d-none d-md-block"><button class="btn btn-primary" name="submit_btn" id="mass-update" onclick="updateMassItems()">Apply</button></span>
                                        <span class="d-md-none"><button class="btn btn-dim btn-outline-light btn-icon disabled" onclick="updateMassItems()"><em class="icon ni ni-arrow-right"></em></button></span>
                                    </div>
                                </div>
                            </li>
                            <!-- <li>
                                <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="modal" title="filter" data-target="#modalFilterUser">
                                    <div class="dot dot-primary"></div>
                                    <em class="icon ni ni-filter-alt"></em>
                                </a>
                            </li> -->

                            {{-- <li>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                        <em class="icon ni ni-setting"></em>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                        <ul class="link-check">
                                            <li><span>Actions</span></li>
                                            <li><a href="{{ url('user/import') }}"><em class="icon ni ni-upload m-r10"></em> Import</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li> --}}

                            @if(isset($userPermission['customer']) && ($userPermission['customer']['edit_all'] || $userPermission['customer']['edit_own']))
                            <li class="nk-block-tools-opt">
                                {{-- <a href="{{url('/user/create')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Customer</span></a> --}}
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
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
                        <th class="nk-tb-col nk-tb-col-check">
                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                <input type="checkbox" class="custom-control-input" id="check-all" name="check_all"><label class="custom-control-label" for="check-all"></label>
                            </div>
                        </th>
                        <th class="nk-tb-col"><span class="sub-text">Email</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Subject</span></th>
                        <th class="nk-tb-col"><span class="sub-text">Grade</span></th>
                        <th class="nk-tb-col w-1 text-center" nowrap="true"><span class="sub-text">Status</span></th>
                        <th class="nk-tb-col w-1" nowrap="true"><span class="sub-text">Created At</span></th>
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
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="Created at" for="createdAt" suggestion="Select the dates of created at." />
                                </div>
                                <div class="col-lg-7">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" class="form-control date-picker" placeholder="Form Date" data-date-format="yyyy-mm-dd" id="fromDate" name="fromDate">
                                            </div>
                                            <!-- <div class="form-note mt-0">Form Date</div> -->
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" class="form-control date-picker" placeholder="To Date" data-date-format="yyyy-mm-dd"  id="toDate" name="toDate">
                                            </div>
                                            <!-- <div class="form-note mt-0">To Date</div> -->
                                        </div>
                                    </div>
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

        <div class="modal fade zoom" tabindex="-1" id="modalUserPassword">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Password</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <form role="form" class="mb-0" method="post" action="{{ url('user/update-user-password') }}">
                    @csrf
                    <div class="modal-body modal-body-lg">
                        <div class="gy-3">
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <x-inputs.verticalFormLabel label="New Password" for="newPassword" suggestion="Specify the new password the user." />
                                </div>
                                <div class="col-lg-7">
                                    <x-inputs.password value="" for="newPassword" icon="lock-alt" placeholder="New Password" name="newPassword" required="true"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="password_user_id" name="password_user_id" value="0">
                    <div class="modal-footer bg-light">
                        <div class="row">
                            <div class="col-lg-12 p-0 text-right">
                                <button class="btn btn-outline-light" data-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" class="btn btn-primary" type="button">Submit</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade zoom" tabindex="-1" id="modalLogs">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Audit Logs</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <div class="modal-body modal-body-lg">
                        <div class="timeline">                                  
                            <ul id="orderLogs" class="timeline-list">                                            
                                {{-- <li class="timeline-item">
                                    <div class="timeline-status bg-primary is-outline"></div>
                                    <div class="timeline-date logDate">23 Nov 2020 <em class="icon ni ni-alarm-alt"></em></div>
                                    <div class="timeline-data">
                                        <div class="timeline-des">
                                            <p class="logText">Task created and assigned to Sonu Goyal</p>
                                            <span class="text-muted fs-10px">by <span class="logBy">SIPL ADMIN</span> at <span class="logTime">04:46 PM</span></span>
                                        </div>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <div class="row">
                            <div class="col-lg-12 p-0 text-right">
                                <button class="btn btn-outline-light" data-dismiss="modal" aria-label="Close">Close</button>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
<script type="text/javascript">
    $('.eg-swal-av3').on("click", function (e) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
          if (result.value) {
            Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
          }
        });
        e.preventDefault();
      });
</script>

@endsection
@push('footerScripts')
<script src="{{url('js/tableFlow.js')}}"></script>
<script type="text/javascript">

    function updateMassItems() {
        var arr = [];
        $('input.cb-check:checkbox:checked').each(function() {
            arr.push($(this).val());
        });

        var status = $('#mass-status').find(":selected").val();
        if (arr.length == 0) {
            Swal.fire("Please select a customer first !");
        } else if (status == "") {
            Swal.fire("Please select bulk status !");
        } else {
            var root_url = "<?php echo url('/'); ?>";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: root_url + '/user/bulk-update',
                data: {
                    'ids': arr,
                    'status': status
                },
                //dataType: "html",
                method: "POST",
                cache: false,
                success: function(data) {
                    console.log(data);
                    
                    if(data.success){
                        $('#check-all').prop('checked', false);
                        var table = $('.broadcast-init').DataTable();
                        table.ajax.reload();

                        Swal.fire(
                            'Good job!',
                            'Status updated successfully.',
                            'success'
                        )

                    } else {

                        $('#check-all').prop('checked', false);
                        var table = $('.broadcast-init').DataTable();
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.msg,
                            //footer: '<a href>Why do I have this issue?</a>'
                        })
                    }
                }
            });
        }
    }


    $(function() {

        

        var root_url = "<?php echo url('/'); ?>";

        var logUrl = root_url + '/user/logs';
        NioApp.getAuditLogs('.broadcast-init','.audit_logs','resourceid',logUrl,'#modalLogs');

        var items = [
            '#firstName',
            '#contact_number',
            '#fromDate',
            '#toDate'
        ];
        var user_table = "";
        user_table = new CustomDataTable({
            tableElem: '.broadcast-init',
            option: {
                processing: true,
                serverSide: true,
                ajax: {
                    type:"GET",
                    url: "{{ url('user') }}",
                },
                columns: [{
                        "class": "nk-tb-col  nk-tb-col-check",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return '<td class="nk-tb-col nk-tb-col-check"><div class="custom-control custom-control-sm custom-checkbox notext"><input type="checkbox" class="custom-control-input cb-check" id="cb-' + row.id + '" value="' + row.id + '" name="checked_items[]"><label class="custom-control-label" for="cb-' + row.id + '"></label></div></td>'
                        }
                    },
                    
                    {
                        "class": "nk-tb-col ",
                        data: 'email',
                        name: 'email'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        "class": "nk-tb-col ",
                        data: 'grade',
                        name: 'grade'
                    },
                    {
                        "class": "nk-tb-col  text-center",
                        data: 'verified_at',
                        name: 'verified_at'
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