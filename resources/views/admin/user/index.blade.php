@extends('layouts.main')
@section('parentPageTitle', 'User')
@section('title', 'Users')
@section('page-style')

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>User List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('user/create')}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-user">
                                    Print
                                </button> </a> --}}</li>
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
                                <div class="col-md-4">
                                    {{Form::select('status',['1'=>'Active','2'=>'Deactive'],'',['class'=>'form-control select-padding-0 user-status-value','placeholder'=>'Select Status'])}}
                                </div>
                                <div class="col-md-4">
                                    {{Form::select('role',$role,'',['class'=>'form-control select-padding-0 user-role','placeholder'=>'Select Role'])}}
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

                        <div class="user-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script type="text/javascript">
        var qstring = '';
        var page = '';
        var search = '';
        var userId = '';
        var role = '';
        var status = '';

        $(document).ready(function(){

            getUserData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&status='+status+'&role='+role;
                getUserData(qstring);
            });

            $(document).on('click','.delete-user',function(){
                userId = $(this).data('id');
                showConfirmMessage();
            });

            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&status='+status+'&role='+role;
                getUserData(qstring);
            });

            $(document).on('change','select.user-status-value',function(e){
                e.preventDefault();
                status = $(this).val();
                qstring = 'page='+page+'&search='+search+'&status='+status+'&role='+role;
                getUserData(qstring);
            });


            $(document).on('change','select.user-role',function(e){
                e.preventDefault();
                role = $(this).val();
                qstring = 'page='+page+'&search='+search+'&status='+status+'&role='+role;
                getUserData(qstring);
            });

            $(document).on('dblclick', '#user-table tbody tr', function(event) {
                var userId = $(this).data('id');
                if(typeof(userId) !== 'undefined'){
                    var url = 'user/'+userId+'/edit';
                    window.location.href = url;
                }
            });

            $(document).on('click', '.print-user', function () {
                qstring = 'page='+page+'&search='+search+'&status='+status+'&role='+role+'&isprint=1';
                getUserData(qstring);
            });
        });
        // get all user data
        function getUserData(qstring){

            $.ajax({
                url: "{{URL::to('user')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.user-data').html(data.data);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
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
                removeUser();
                swal("Deleted!", "Your user has been deleted.", "success");
            });
        }
        // remove user
        function removeUser(){
            $.ajax({
                url: "{{URL::to('user/delete/')}}"+'/'+userId,
                dataType: 'json',
            }).done(function(data) {
                getUserData(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
