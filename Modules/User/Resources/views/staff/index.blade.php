@extends('layouts.app')

@section('content')
@php
    $userPermission = \Session::get('userPermission');
@endphp
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Staff</h3>
            <p>You have total <span class="record_count">{{ $usersCount }}</span> users.</p>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="more-options">
                    <ul class="nk-block-tools g-3">
                        <li class="mr-2">
                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                <input type="checkbox" class="custom-control-input" id="check-all" name="check_all"><label class="custom-control-label" for="check-all">Check All</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-inline flex-nowrap input-group gx-3">
                                <select class="form-select" data-search="off" data-placeholder="Bulk Action" id="mass-status">
                                    <option value="" selected disabled>Bulk Action</option>
                                    <option value="1">Approve</option>
                                    <option value="0">Unapprove</option>
                                </select>
                                <div class="input-group-prepend">
                                    <span class="d-none d-md-block"><button class="btn btn-primary" name="submit_btn" id="mass-update" onclick="updateMassItems()">Apply</button></span>
                                    <span class="d-md-none"><button class="btn btn-dim btn-outline-light btn-icon disabled"><em class="icon ni ni-arrow-right"></em></button></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="modal" title="filter" data-target="#modalFilterUser">
                                <div class="dot dot-primary"></div>
                                <em class="icon ni ni-filter-alt"></em>
                            </a>
                        </li>
                        {{-- @if(isset($userPermission['staff']) && ($userPermission['staff']['edit_all'] || $userPermission['staff']['edit_own'])) --}}
                        <li class="nk-block-tools-opt">
                            <a href="{{url('/user/staff/create-staff')}}" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                            <a href="{{url('/user/staff/create-staff')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add User</span></a>
                        </li>
                        {{-- @endif --}}
                    </ul>
                </div>
            </div>
        </div>
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<!--  Filter Tag List -->
<div id="filter_tag_list" class="filter-tag-list"></div>

<div class="nk-block broadcast-init">
    <div class="row g-gs">
        @forelse ($users as $key => $user)
            <div class="col-sm-6 col-lg-4 col-xxl-3">
                <div class="card">
                    <div class="card-inner">
                        <div class="team">
                            <div class="team-status">
                                <div class="custom-control custom-control-sm custom-checkbox notext"><input type="checkbox" class="custom-control-input cb-check" id="cb-{{ $user->id }}" value="{{ $user->id }}" name="checked_items[]"><label class="custom-control-label" for="cb-{{ $user->id }}"></label>
                                </div>
                            </div>
                            <div class="team-options">
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ url('/').'/user/staff/edit-staff/'.$user->id }}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                            <li><a href="{{ url('/').'/user/staff/delete-staff/'.$user->id }}" onclick='return confirm("Are you sure, you want to delete this user?")'  class='delete'><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                            {{-- <li><a href="#" data-resourceId="{{ $user->id }}" class="audit_logs"><em class="icon ni ni-list"></em><span>Audit Logs</span></a></li> --}}
                                            <li><a href="#" data-resourceId="{{ $user->id }}" class="changePassword"><em class="icon ni ni-lock-alt"></em><span>Update Password</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="user-card user-card-s2">
                                <div class="user-avatar lg bg-primary">

                                    @php
                                        $username = $user->name.' '.$user->last_name;
                                        if(!is_null($user->file)){
                                            $file = public_path('uploads/users/') . $user->file;
                                        }

                                        if(!is_null($user->file) && file_exists($file)){
                                            $avatar = "<img src=".url('uploads/users/'.$user->file).">";
                                        }
                                        else{
                                            $avatar = "<span>".\Helpers::getAcronym($username)."</span>";
                                        }
                                    @endphp 

                                    <span>{!! $avatar !!}</span>
                                    {{-- <div class="status dot dot-lg dot-success"></div> --}}
                                </div>
                                <div class="user-info">
                                    @if($user->status == '1')
                                    <h6>{{ ucfirst($user->name.' '.$user->last_name) }} <span class="badge badge-success mb-0">Approved</span></h6>
                                    @else
                                    <h6>{{ ucfirst($user->name.' '.$user->last_name) }} <span class="badge badge-danger mb-0">Not Approved</span></h6>
                                    @endif
                                    <span class="sub-text">{{ $user->email }}</span>
                                </div>
                            </div>
                            <ul class="team-statistics pb-0">
                                <li><span>{{ ucfirst($user->roleName) }}</span><span>Role</span></li>
                                <li><span>{{ $user->phone_number }}</span><span>Contact</span></li>
                            </ul>
                            <ul class="team-info">
                                <li><span>Created At</span><span>{{ date(\Config::get('constants.DATE.DATE_FORMAT') , strtotime($user->created_at)) }}</span></li>
                                <li><span>Updated At</span><span>{{ date(\Config::get('constants.DATE.DATE_FORMAT') , strtotime($user->updated_at)) }}</span></li>
                            </ul>
                            <div class="team-view">
                                {{-- <a href="{{ url('user/staff/staff-detail/'.$user->id) }}" class="btn btn-round btn-outline-light w-150px"><span>View Profile</span></a> --}}
                            </div>
                        </div><!-- .team -->
                    </div><!-- .card-inner -->
                </div><!-- .card -->
            </div><!-- .col -->
        @empty
            <p>No Users found</p>
        @endforelse
    </div>
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
            <form role="form" class="mb-0" method="post" action="{{ url('user/staff') }}">
                @csrf
                <div class="modal-body modal-body-lg">
                    <div class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <x-inputs.verticalFormLabel label="Name" for="firstName" suggestion="Specify the name of the user." />
                            </div>
                            <div class="col-lg-7">
                                @php
                                    $firstname="";
                                    if(isset($filterRequests['firstname']))
                                        $firstname=$filterRequests['firstname'];
                                @endphp
                                <x-inputs.text value="{{ $firstname }}" for="firstName" icon="user" placeholder="First name" name="firstname" />
                            </div>
                        </div>
                        
                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <x-inputs.verticalFormLabel label="Mobile Number" for="mobileNumber" suggestion="Specify the mobile number of the user." />
                            </div>
                            <div class="col-lg-7">
                                @php
                                    $mobileNumber="";
                                    if(isset($filterRequests['mobileNumber']))
                                        $mobileNumber=$filterRequests['mobileNumber'];
                                @endphp
                                <x-inputs.text value="{{ $mobileNumber }}" for="mobileNumber" icon="call" placeholder="Mobile Number" name="mobileNumber" 
                                data-parsley-pattern="{{ \Config::get('constants.REGEX.VALIDATE_MOBILE_NUMBER_LENGTH') }}"
                                />
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <x-inputs.verticalFormLabel label="Role" for="role" suggestion="Select the role of the user." />
                            </div>
                            <div class="col-lg-7">
                                @php
                                    $filterrole="";
                                    if(isset($filterRequests['role']))
                                        $filterrole=$filterRequests['role'];
                                @endphp
                                <x-inputs.select size="sm" name="role" for="role">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                    <option @if($filterrole == $role) selected @endif value="{{ $role->name }}">{{ $role->label }}</option>
                                    @endforeach
                                </x-inputs.select>
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
                                            @php
                                                $fromDate="";
                                                if(isset($filterRequests['fromDate']))
                                                    $fromDate=$filterRequests['fromDate'];
                                            @endphp
                                            <input value="{{ $fromDate }}" type="text" class="form-control date-picker" id="fromDate" placeholder="Form Date" data-date-format="yyyy-mm-dd" name="fromDate">
                                        </div>
                                        <!-- <div class="form-note mt-0">Form Date</div> -->
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            @php
                                                $toDate="";
                                                if(isset($filterRequests['toDate']))
                                                    $toDate=$filterRequests['toDate'];
                                            @endphp
                                            <input value="{{ $toDate }}" type="text" class="form-control date-picker" id="toDate" placeholder="To Date" data-date-format="yyyy-mm-dd" name="toDate">
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
                            <button class="btn btn-primary submitBtn" type="submit">Submit</button>
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
                            <x-inputs.password value="" for="newPassword" icon="lock-alt" placeholder="New Password" name="newPassword"/>
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
        $('.eg-swal-av3').on("click", function(e) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function(result) {
                if (result.value) {
                    Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                }
            });
            e.preventDefault();
        });
    </script>
    @endsection
    @push('footerScripts')
    <script type="text/javascript">
        function updateMassItems() {
            var arr = [];
            $('input.cb-check:checkbox:checked').each(function() {
                arr.push($(this).val());
            });

            var status = $('#mass-status').find(":selected").val();
            if (arr.length == 0) {
                Swal.fire("Please select an user first !");
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
                    url: root_url + '/user/staff/staff-bulk-update',
                    data: {
                        'ids': arr,
                        'status': status
                    },
                    //dataType: "html",
                    method: "POST",
                    cache: false,
                    success: function(data) {
                        console.log(data);

                        if (data.success) {
                            Swal.fire(
                                'Good job!',
                                'Status updated successfully.',
                                'success'
                            )
                            setTimeout(function(){
                                location.reload();
                            }, 3000);

                        } else {

                            $('#check-all').prop('checked', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.msg,
                                //footer: '<a href>Why do I have this issue?</a>'
                            })

                            setTimeout(function(){
                                location.reload();
                            }, 3000);
                        }
                    }
                });
            }
        }

        class ResetData {
            draw(){
                console.log('Hey!!');
                $('.submitBtn').click();
            }
        }
        var RData = new ResetData();

        $(document).ready(function(){
            $('.changePassword').click(function(){
                var resourceId = $(this).attr('data-resourceid');
                $('#password_user_id').val(resourceId);
                $('#modalUserPassword').modal('show');
            });

            var items = [
                '#firstName',
                '#lastName',
                '#mobileNumber',
                '#role',
                '#fromDate',
                '#toDate'
            ];
            NioApp.filterTag(items, RData, '#filter_tag_list');

            var root_url = "<?php echo url('/'); ?>";
            var logUrl = root_url + '/user/logs';
            NioApp.getAuditLogs('.broadcast-init','.audit_logs','resourceid',logUrl,'#modalLogs');

        });

        $(function() {
            // console.log(user_table['.broadcast-init'], 'table data');
            
            // $('.submitBtn').click(function() {
            //     // user_table['.broadcast-init'].draw();
            //     $('#modalFilterUser').modal('toggle');
            // });

            $('.resetFilter').on('click', function() {
                $('#firstName, #lastName, #mobileNumber, #role, #fromDate, #toDate').val('');
                $('.submitBtn').click();
                // NioApp.resetModalForm('#modalFilterUser', RData, '.resetFilter');
                // $('#modalFilterUser').modal('toggle');
            });

            

            $("#check-all").on("change", function() {
                // If checked
                if (this.checked)
                    $('.cb-check').prop('checked', true);
                else
                    $('.cb-check').prop('checked', false);
            });
        });
    </script>
    @endpush