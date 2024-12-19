@extends('layouts.app')

@section('content')
@php
    $userPermission = \Session::get('userPermission');
@endphp
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Banners</h3>
                @php 
                    if(count($banners) > 1) 
                        $count = 'banners';
                    else
                        $count = 'banner';                       
                @endphp
                <p>You have total <span class="record_count">{{ count($banners) }}</span> {{ $count }}.</p>
            </div><!-- .nk-block-head-content -->
            <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="more-options">
                    <ul class="nk-block-tools g-3">
                        <li>
                            <div class="form-wrap 50px mr-2">
                               <select class="form-select form-select-sm" data-search="off" data-placeholder="Bulk Action" id="mass-status">
                                    <option value="" selected disabled>Bulk Action</option>
                                    <option value="active" >Active</option>
                                    <option value="inactive" >Inactive</option>
                                    
                                </select>
                            </div>
                            <div class="btn-wrap">
                                <span class="d-none d-md-block"><button class="btn btn-primary" name="submit_btn" id="mass-update" onclick="updateMassItems()">Apply</button></span>
                                <span class="d-md-none"><button class="btn btn-dim btn-outline-light btn-icon disabled"><em class="icon ni ni-arrow-right"></em></button></span>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="modal" title="filter" data-target="#modalFilterManu">
                                <div class="dot dot-primary"></div>
                                <em class="icon ni ni-filter-alt"></em>
                            </a>
                        </li>
                        <li class="nk-block-tools-opt">
                            <a href="#" data-target="addDrawer" class="toggle btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add </span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <!--  Filter Tag List -->
    <div id="filter_tag_list" class="filter-tag-list"></div>
    <div class="nk-block table-compact">
        <Drawer class="nk-tb-list is-separate mb-3">
            <table class="table-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col nk-tb-col-check">
                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                <input type="checkbox" class="custom-control-input" id="check-all" name="check_all"><label class="custom-control-label" for="check-all"></label>
                            </div>
                        </th>
                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Name</span></th>
                        <th class="nk-tb-col tb-col-mb nk-tb-action-col text-center" nowrap="true"><span class="sub-text">Status</span></th>
                        <th class="nk-tb-col tb-col-mb nk-tb-action-col" nowrap="true"><span class="sub-text">Created at</span></th>
                        <th class="nk-tb-col tb-col-mb nk-tb-action-col" nowrap="true"><span class="sub-text">Updated at</span></th>
                        <th class="nk-tb-col nk-tb-col-tools nk-tb-action-col text-right" nowrap="true">
                            <span class="sub-text">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- .nk-tb-list -->
                <div class="nk-add-product toggle-slide toggle-slide-right" data-content="addDrawer" data-toggle-screen="any" data-toggle-overlay="true" data-toggle-body="true" data-simplebar id="modal">
        <form role="form" id="addForm" method="post" action="{{ url('masters/banner/add') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="0" name="id" id="itemId">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title modalTitle">Add</h5>
                    <div class="nk-block-des">
                        <p id="drawerDesc">Add information and add new item.</p>
                    </div>
                </div>
            </div><!-- .nk-block-head -->
            <div class="nk-block">
                <form action="#">
                <div class="row g-3">
                    <div class="col-12">
                        <x-inputs.text for="name" label="Name" Drawer="true" icon="sign-dash" placeholder="Name" name="name" minlength="3" maxlength="200" autocomplete="off"/>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="tags">Image</label>
                            <div class="form-control-wrap">
                                <div class="custom-file">
                                    <input name="file" type="file" class="custom-file-input" id="customFile" required>
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                            <div id="image_file">
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="category">Status</label>
                            <div class="form-control-wrap">
                                <x-inputs.switch for="manufStatus" size="md" label="" name="status" value='1' checked />
                            </div>
                        </div>
                    </div>

                    

                    <!-- <div class="col-12">
                        <x-inputs.textarea  for="description" label="Description" size="sm" name="description" maxlength="500"/>
                    </div> -->
                    <div class="col-12 text-right">
                        <a data-target='addDrawer' class="btn btn-outline-light cancel">Cancel</a>
                            <button class="btn btn-primary" name="submit"><span>Submit</span></button>
                    </div>
                </div>
                </form>
            </div><!-- .nk-block -->
        </div>
    </div><!-- .nk-block -->
<div class="modal fade zoom" tabindex="-1" id="modalFilterManu">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form action="#" role="form" class="mb-0" method="get">
                @csrf
                <div class="modal-body modal-body-lg">
                    <div class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <x-inputs.verticalFormLabel label="Name" for="name" suggestion="Specify the name of the item." />
                            </div>
                            <div class="col-lg-7">
                                <x-inputs.text value="" for="name" icon="sign-dash" name="name" />
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <x-inputs.verticalFormLabel label="Status" for="status" suggestion="Select the status of the." />
                            </div>
                            <div class="col-lg-7">
                                <x-inputs.select size="sm" name="status" for="filterstatus">
                                <option></option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </x-inputs.select>
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
    function resetValues(){
        $('.submitBtn').text('Submit');
        $('.modalTitle').text('Add');
        $('#itemId').val(0);
        $('#name').val('');
        $('#slug').val('');
        $('#itemId').val(0);
        $('#addForm')[0].reset();
        $('#addForm').parsley().reset();
    }
    $('.cancel').on('click', function() {
            resetValues();
            NioApp.Toggle.collapseDrawer('addDrawer')
    })
    function updateMassItems() {
        var arr = [];
        $('input.cb-check:checkbox:checked').each(function () {
            arr.push($(this).val());
        });

        //
        var status = $('#mass-status').find(":selected").val();

        console.log(status);
        console.log(arr.length);

        if(arr.length==0){
            Swal.fire("Please select a item first !");
        }else if(status == 0){
            Swal.fire("Please select bulk status !");
        }else{
            var root_url = "<?php echo url('/'); ?>";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: root_url + '/masters/banner/mass-update',
                data: {'ids':arr,'status':status},
                //dataType: "html",
                method: "POST",
                cache: false,
                success: function (data) {
                    console.log(data);
                    
                    if(data.success){
                        
                        $('#check-all').prop('checked', false);
                        var table = $('.table-init').DataTable();
                        table.ajax.reload();

                        Swal.fire(
                          'Good job!',
                          'Item Updated Successfully.',
                          'success'
                        )

                    }else{

                        $('#check-all').prop('checked', false);
                        var table = $('.table-init').DataTable();
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
        
        return false;
    }

    $(function() {
    
        $('.table-init').on("change","#check-all",function() {
            if (this.checked)
                    $('.cb-check').prop('checked', true);
            else
                    $('.cb-check').prop('checked', false);
        })

        var root_url = "<?php echo url('/'); ?>";
        
        $('.table-init').on('click', '.editItem', function() {
            var id = $(this).attr('data-id');
            console.log(id);
        
            $.ajax({
                url: root_url + '/masters/banner/get-banner',
                data: {'id':id},
                //dataType: "html",
                method: "GET",
                cache: false,
                success: function (data) {
                    console.log(data);
                    
                    if(data.success){
                        $('#itemId').val(id);
                        $('#name').val(data.banner.name);
                        $('#image_file').html('<img height="100" width="150" src="'+data.banner.file+'">');
                        $('.submitBtn').text('Update');
                        
                        $('#manufStatus').attr('checked', data.banner.status == "active"? true : false);
                        $('.modalTitle').text('Edit');
                        $('#drawerDesc').hide();
                    }else{
                        Swal.fire('Details not found!')
                    }
                }
            });
        });

        $('.table-init').on('click','.eg-swal-av3', function (e) {
        var id = $(this).attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
          if (result.value) {
            console.log('catId-' + id);
            $.ajax({
                url: root_url + '/masters/banner/delete',
                data: {
                    'id': id
                },
                //dataType: "html",
                method: "GET",
                cache: false,
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        Swal.fire('Deleted!', 'Your item has been deleted.', 'success');
                        var table = $('.table-init').DataTable();
                        table.ajax.reload();
                    } else {
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
        });
        e.preventDefault();
      });

        var items = [
            '#name',
            '#filterstatus'
        ];
        var dt='';
        dt = new CustomDataTable({
            tableElem: '.table-init',
            option: {
                processing: true,
                serverSide: true,
                ajax: {
                    type:"GET",
                    url: "{{ url('masters/banner') }}",
                },
                columns: [{
                        "class": "nk-tb-col tb-col-lg nk-tb-col-check",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return '<td class="nk-tb-col nk-tb-col-check"><div class="custom-control custom-control-sm custom-checkbox notext"><input type="checkbox" class="custom-control-input cb-check" id="cb-'+ row.id +'" value="'+ row.id +'" name="checked_items[]"><label class="custom-control-label" for="cb-'+ row.id +'"></label></div></td>'
                        }
                    },
                    {
                        "class": "nk-tb-col tb-col-lg",
                        data: 'name',
                        name: 'name'
                    },
                    {
                        "class": "nk-tb-col tb-col-lg text-center",
                        data: 'status',
                        name: 'status'
                    },
                    {
                        "class": "nk-tb-col tb-col-lg",
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        "class": "nk-tb-col tb-col-lg",
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        "class": "nk-tb-col tb-col-lg text-right nk-tb-col-tools",
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "fnDrawCallback":function(){
                NioApp.TGL.content('.editItem',{
                        onCloseCallback: resetValues
                    });
                NioApp.BS.tooltip('[data-toggle="tooltip"]');   
                }
            },
            filterSubmit: '.submitBtn',
            filterSubmitCallback: function(){
                $('#modalFilterManu').modal('toggle');
            },
            filterClearSubmit: '.resetFilter',
            filterModalId: '#modalFilterManu',
            filterItems: items,
            tagId: '#filter_tag_list',
        });

        // console.log(dt['.table-init'],'table data');
        // $('.submitBtn').click(function(){
        //     dt['.table-init'].draw();
        //     $('#modalFilterManu').modal('toggle');
        // });
        // NioApp.resetModalForm('#modalFilterManu', dt['.table-init'], '.resetFilter');
    });
</script>
@endpush
