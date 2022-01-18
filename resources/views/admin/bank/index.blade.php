@extends('layouts.main')
@section('parentPageTitle', 'Bank Detail')
@section('title', 'Bank Detail')
@section('page-style')

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Bank List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <ul class="dropdown-menu dropdown-menu-right slideUp">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#bank-modal">
                                    Add
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="body">

                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="post" autocomplete="off" action="">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search" placeholder="Search..." autocomplete="off">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">

                        <!-- notification -->
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{Session::get('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                        @endif

                        <div class="bank-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('modal')
    <div class="modal fade" id="bank-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Bank Detail</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline','id'=>'medicine-form'])}}
                    {{Form::hidden('bank_id','',['class'=>'bank_id'])}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-2 form-padding">
                                    Name
                                </div>
                                <div class="col-md-8 form-padding" >
                                    {{Form::text('name','',['class'=>'form-control name','placeholder'=>'Bank Name',"style"=>"width: inherit !important;"])}}
                                </div>
                            </div>
                        </div>
                        <span class="form-error-msg name-error"></span>
                    </div>
                    <!-- footer -->
                    <div class="modal-footer w-100 justify-content-center">
                        <button type="button" class="btn btn-primary waves-effect bank-submit" data-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-default waves-effect ml-3" data-dismiss="modal">Close</button>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script type="text/javascript">
        var qstring = '';
        var page = '';
        var search = '';
        var bankId = '';
        var status = '';

        $(document).ready(function(){

            getBankData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search;
                getBankData(qstring);
            });

            $(document).on('click','.delete-bank',function(){
                bankId = $(this).data('id');
                showConfirmMessage();
            });

            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search;
                getBankData(qstring);
            });

            $(document).on('click','.editBank',function(){
                bankId = $(this).data('id');
                $.ajax({
                        url: "{{URL::to('bank/getBank')}}"+'/'+bankId,
                        dataType: 'json',
                    }).done(function(data) {
                        console.log(data);
                        if(data.status == 1){
                           $('#bank-modal').modal('show');
                           $('.name').val('');
                           $('.name').val(data.bank_detail.name);
                           $('.bank_id').val(data.bank_detail.id);
                        }
                        if(data.status == 2){

                        }
                    }).fail(function() {

                    });
            });
        });
        // get all category data
        function getBankData(qstring){
            $.ajax({
                url: "{{URL::to('bank')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.bank-data').html(data.bank_detail);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.category);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to delete this record",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                removeBank();
                $('.showSweetAlert').hide();
                location.reload();
                // swal("Deleted!", "Your category has been deleted.", "success");
            });
        }
        // remove bank
        function removeBank(){
            $.ajax({
                url: "{{URL::to('bank/delete/')}}"+'/'+bankId,
                dataType: 'json',
            }).done(function(data) {
                getBankData(qstring);
            }).fail(function() {

            });
        }
        //add bankdetail
        $(document).ready(function(){
            $('.bank-submit').on('click',function(){
                var name = $('.name').val();
                var edit_bankId = $('.bank_id').val();
                if(name.length == 0)
                {
                    $('.name').focus();
                    return false;
                }
                $.ajax({
                url: "{{URL::to('bank/storeBank')}}?"+qstring,
                type: 'post',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data:{name : name,bank_id: edit_bankId},
                dataType:"json",
                }).done(function(data) {
                    if(data.status == 1)
                    {
                        showNotification('bg-green', 'Bank detail added successfully');
                    }
                    else
                    {
                        showNotification('bg-green', 'Bank detail updated successfully');
                    }
                    getBankData(qstring);
                }).fail(function() {
                    showNotification('bg-red', 'Something went wrong! please try again');
                });
            });
        });

        //edit bank


    </script>
@stop
