@extends('layouts.main')
@section('parentPageTitle', 'Injection Charges')
@section('title', 'Injection Charges')
@section('page-style')

@stop
@php
  $type= ["1"=>'Hormon','2'=>'IVF','3'=>'IUI'];  
@endphp
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Hormon Injection Charge List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <ul class="dropdown-menu dropdown-menu-right slideUp">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <button class="btn btn-primary inj-modal">
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

                        <div class="injection-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('modal')
    <div class="modal fade" id="inj-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog update-time" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Injection Charge</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline'])}}
                {{-- <span class="form-error-msg expense-error w-100"></span> --}}
                    <div class="modal-body">
                        <div class="row">
                            {{Form::hidden('injId','',['class'=>'form-control injId'])}}

                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Injection
                                </div>
                                <div class="col-md-6">
                                    {{Form::text('name','',['class'=>'form-control inj_name form-required','placeholder'=>'Injection Name','data-errorclass'=>'inj-error'])}}
                                </div>
                                <span class="form-error-msg inj-error w-100"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Net Amount
                                </div>
                                <div class="col-md-6">
                                    {{Form::number('net_amount','',['class'=>'form-control net_amount form-required','placeholder'=>'Net Amount','data-errorclass'=>'net-error'])}}
                                </div>
                                <span class="form-error-msg net-error w-100"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    MRP
                                </div>
                                <div class="col-md-6">
                                    {{Form::number('mrp','',['class'=>'form-control mrp form-required','placeholder'=>' MRP','data-errorclass'=>'mrp-error'])}}
                                </div>
                                <span class="form-error-msg mrp-error w-100"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Quanity
                                </div>
                                <div class="col-md-6">
                                    {{Form::number('quantity','',['class'=>'form-control quantity','placeholder'=>' quantity','data-errorclass'=>'quantity-error'])}}
                                </div>
                                <span class="form-error-msg quantity-error w-100"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Quanity Type
                                </div>
                                <div class="col-md-6">
                                    {{Form::select('qty_type',['1'=>'QTY','2'=>'VIAL'],'',['class'=>'qty_type'])}}
                                </div>
                                <span class="form-error-msg quantity-error w-100"></span>
                            </div>
                            
                        </div>
                        <!-- footer -->
                        <div class="modal-footer mt-3 time-footer">
                            <button type="button" class="btn btn-primary waves-effect inj-save">Save</button>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
@stop
@stop
@section('page-script')
<script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        var qstring = '';
        var page = '';
        var search = '';
        var status = '';
        var plan = '';
        var injId = '';

        $(document).ready(function(){

            getInjectionData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search;
                getInjectionData(qstring);
            });

            $(document).on('click','.injection-delete',function(){
                injId = $(this).data('id');
                showConfirmMessage();
            });
            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search;
                getInjectionData(qstring);
            });

            $(document).on('click', '.inj-modal', function () {
                var inj_name = $('.inj_name').val('');
                var net_amount = $('.net_amount').val('');
                var mrp = $('.mrp').val('');
                var quantity = $('.quantity').val('');
                var qty_type = $('select.qty_type').val('');
                $('.qty_type').selectpicker('refresh');
                $('#inj-modal').modal('show');
            });
        });
        $(document).on('click', '.injection-edit', function () {
            injId = $(this).data('id');
            var inj_name = $('.inj_name').val('');
            var net_amount = $('.net_amount').val('');
            var mrp = $('.mrp').val('');
            var quantity = $('.quantity').val('');
            var qty_type = $('select.qty_type').val('');
            $('.qty_type').selectpicker('refresh');
            $('.inj-error').html('');
            $.ajax({
            url: "{{URL::to('inj-charge/edit')}}/" + injId,
            dataType: 'json',
            }).done(function (data) {
                if(data.status == 1)
                {
                    $('#inj-modal').modal('show');   
                    $('.inj_name').val(data.injection.name);
                    $('.net_amount').val(data.injection.net_price);
                    $('.mrp').val(data.injection.mrp);
                    $('.quantity').val(data.injection.quantity);
                    $('select.qty_type').val(data.injection.qty_type);
                    $('.qty_type').selectpicker('refresh');
                    $('.injId').val(injId);
                }
                else{

                }
            }).fail(function () {

            });
        });
        $(document).on('click','.inj-save',function(){
            $('.form-error-msg').html('');
            var inj_name = $('.inj_name').val();
            var net_amount = $('.net_amount').val();
            var mrp = $('.mrp').val();
            var quantity = $('.quantity').val();
            var injId = $('.injId').val();
            var qty_type = $('select.qty_type').val();
            $('.qty_type').selectpicker('refresh');
            var hasNoValue = 0;
            $(".form-required").each(function() {
                if($(this).val() == '')
                {
                    var errclass = $(this).data('errorclass');
                    hasNoValue = 1;
                    $('.'+errclass).html('This Field is required');
                    return false;
                }
            });
            if($('select.type').val() == '')
            {
                var errclass = $('select.type').data('errorclass');
                hasNoValue = 1;
                $('.'+errclass).html('This Field is required');
                return false;
            }
            if(hasNoValue == 0)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'inj-charge/store',
                    type: 'POST',
                    data: {
                        inj_name: inj_name,
                        net_amount:net_amount,
                        mrp:mrp,
                        quantity:quantity,
                        injId:injId,
                        qty_type:qty_type
                    },
                    dataType: 'json',
                }).done(function(data) {
                    if(data.status == 1)
                    {
                        $('#inj-modal').modal('hide');
                        showNotification('bg-blue', 'Injection successfully added.', 'bottom', 'right', "", "");
                        qstring = 'page='+page+'&search='+search+'&plan='+plan;
                        getInjectionData(qstring);
                    }
                    else if(data.status == 2)
                    {
                        $('.inj-error').html('Injection already exists');
                    }
                    else{
                        $('#inj-modal').modal('hide');
                        showNotification('bg-red', 'Something Went wrong! Please try again.', 'bottom', 'right', "", "");
                    }
                });
            }
            
        });
        // get all category data
        function getInjectionData(qstring){
            $.ajax({
                url: "{{URL::to('inj-charge')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.injection-data').html(data.injection);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.injection);
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
                removeCaExpense();
                $('.showSweetAlert').hide();
                // location.reload();
                // swal("Deleted!", "Your category has been deleted.", "success");
            });
        }
        // remove category
        function removeCaExpense(){
            $.ajax({
                url: "{{URL::to('inj-charge/delete')}}"+'/'+injId,
                dataType: 'json',
            }).done(function(data) {

                getInjectionData(qstring);
                location.reload();
            }).fail(function() {

            });
        }
    </script>
@stop
