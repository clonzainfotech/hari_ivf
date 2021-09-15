@extends('layouts.main')
@section('parentPageTitle', 'Hospital Charges')
@section('title', 'Hospital Charges')
@section('page-style')
<style>

</style>

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Hospital Charges List</strong></h2>
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
                                <button class="btn btn-primary" data-toggle="modal" data-target="#charge-modal">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <button class="btn btn-primary print-charge">
                                    Print
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
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control search" placeholder="Search..." readonly="readonly" onfocus="this.removeAttribute('readonly')">
                                        <span class="input-group-addon search-border">
                                            <i class="zmdi zmdi-search"></i>
                                        </span>
                                    </div>
                                </ul>
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

                        <div class="charge-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('modal')
    <div class="modal fade" id="charge-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Hospital Charge</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline','id'=>'charge-form'])}}
                    {{Form::hidden('charge_id','',['class'=>'charge_id'])}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-2 form-padding">
                                    Title
                                </div>
                                <div class="col-md-8 form-padding" >
                                    {{Form::text('title','',['class'=>'form-control chargeTitle w-inherit','placeholder'=>'Charge Title'])}}
                                </div>
                            </div>
                        </div>
                        <span class="form-error-msg title-error"></span>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-2 form-padding">
                                    Charge
                                </div>
                                <div class="col-md-8 form-padding" >
                                    {{Form::number('charge','',['class'=>'form-control charge w-inherit','placeholder'=>'Charge Value','min'=>1])}}
                                </div>
                            </div>
                        </div>
                        <span class="form-error-msg charge-error"></span>
                    </div>
                    <!-- footer -->
                    <div class="modal-footer w-100 justify-content-center">
                        <button type="button" class="btn btn-primary waves-effect charge-submit" data-dismiss="modal">Save</button>
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
        var chargeId = '';
        var status = '';

        $(document).ready(function(){

            getChargeData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search;
                getChargeData(qstring);
            });

            $(document).on('click','.delete-charge',function(){
                chargeId = $(this).data('id');
                showConfirmMessage();
            });

            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search;
                getChargeData(qstring);
            });

            $(document).on('click','.editCharge',function(){
                chargeId = $(this).data('id');
                $.ajax({
                        url: "{{URL::to('charge/getHospitalCharge')}}"+'/'+chargeId,
                        dataType: 'json',
                    }).done(function(data) {
                        console.log(data);
                        if(data.status == 1){
                           $('#charge-modal').modal('show');
                           $('.chargeTitle').val('');
                           $('.charge').val('');
                           $('.chargeTitle').val(data.hospital_charge.title);
                           $('.charge').val(data.hospital_charge.charge);
                           $('.charge_id').val(data.hospital_charge.id);
                        }
                        if(data.status == 2){
                           
                        }
                    }).fail(function() {

                    });
            });

            $(document).on('click', '.print-charge', function () {
                qstring = 'page='+page+'&search='+search+'&isprint=1';
                getChargeData(qstring);
            });
        });
        // get all charge data
        function getChargeData(qstring){
            $.ajax({
                url: "{{URL::to('charge')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.charge-data').html(data.charge);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.charge);
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
                removeCharge();
                $('.showSweetAlert').hide();
                location.reload();
                // swal("Deleted!", "Your category has been deleted.", "success");
            });
        }
        // remove charge
        function removeCharge(){
            $.ajax({
                url: "{{URL::to('charge/delete')}}"+'/'+chargeId,
                dataType: 'json',
            }).done(function(data) {
                getChargeData(qstring);
            }).fail(function() {

            });
        }
        $(document).ready(function(){
            $('.charge-submit').on('click',function(){
                $('.title-error').html('');
                $('.charge-error').html('');
                var title = $('.chargeTitle').val();
                var charge = $('.charge').val();
                var edit_chargeId = $('.charge_id').val();
                if(title.length == 0)
                {
                    $('.title').focus();
                    $('.title-error').html('This Field is Required');
                    return false;
                }
                if(charge.length == 0)
                {
                    $('.charge').focus();
                    $('.charge-error').html('This Field is Required');
                    return false;
                }
                $.ajax({
                url: "{{URL::to('charge/store')}}?"+qstring,
                type: 'post',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data:{title : title,charge:charge,charge_id: edit_chargeId},
                dataType:"json",
                }).done(function(data) {
                    if(data.status == 1)
                    {
                        showNotification('bg-green', 'Hospital charge added successfully');
                    }
                    else
                    {
                        showNotification('bg-green', 'Hospital charge updated successfully');
                    }
                    getChargeData(qstring);
                }).fail(function() {
                    showNotification('bg-red', 'Something went wrong! please try again');
                });
            });
        });
    </script>
@stop
