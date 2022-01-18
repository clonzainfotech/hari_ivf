@extends('layouts.main')
@section('parentPageTitle', 'Injection')
@section('title', 'Injection')
@section('page-style')

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Injection List</strong></h2>
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
                        <li>
                            <a href="{{URL::to('plan')}}">
                                <button class="btn btn-primary ">
                                    Plan
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <button class="btn btn-primary print-injection">
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
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <form method="post" autocomplete="off" action="">
                                {{Form::select('plan',$planList,'',[
                                    'class'=>'form-control select-padding-0 select_plan',
                                    'placeholder'=>'Select Plan',
                                    'id' => 'select_plan',
                                    'data-live-search' => 'true'
                                ])}}
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
                    <h4 class="title" id="next-appointment">Injection</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline'])}}
                <span class="form-error-msg expense-error w-100"></span>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Injection
                                </div>
                                <div class="col-md-6">
                                    {{Form::text('name','',['class'=>'form-control inj_name form-required','placeholder'=>'Injection Name'])}}
                                </div>
                                <span class="form-error-msg inj-error w-100"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Plan
                                </div>
                                <div class="col-md-6">
                                    {{Form::select('plan',$planList,null,['class'=>'form-control plan_name select-padding-0','placeholder'=>"Select plan"])}}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Net Price
                                </div>
                                <div class="col-md-6">
                                    {{Form::number('net_price','',['class'=>'form-control net_price form-required','placeholder'=>'Net Price'])}}
                                </div>
                                <span class="form-error-msg netPrice-error w-100"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Quantity
                                </div>
                                <div class="col-md-6">
                                    {{Form::number('quantity','',['class'=>'form-control quantity form-required','placeholder'=>'Quantity'])}}
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
                qstring = 'page='+page+'&search='+search+'&plan='+plan;
                getInjectionData(qstring);
            });

            $(document).on('click','.injection-delete',function(){
                injId = $(this).data('id');
                showConfirmMessage();
            });
            $(document).on('change','select.select_plan',function(){
                plan = $(this).val();
                plan = plan.replace("+", "/");
                plan = plan.replace(/[!@#$&()`.,"%\-*{}[|:;'<>~?^_=\] ]/g, '_');
                qstring = 'page='+page+'&search='+search+'&plan='+plan;
                getInjectionData(qstring);
            });
            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&plan='+plan;
                getInjectionData(qstring);
            });


            $(document).on('click', '.print-injection', function () {
                qstring = 'page='+page+'&search='+search+'&plan='+plan+'&isprint=1';
                getInjectionData(qstring);
            });
            $(document).on('click', '.inj-modal', function () {
                var inj_name = $('.inj_name').val('');
                var qty = $('.quantity').val('');
                var net_price = $('.net_price').val('');
                var plan_name = $('select.plan_name').val('');
                $('.plan_name').selectpicker('refresh');
                $('#inj-modal').modal('show');
            });
        });
        $(document).on('click', '.injection-edit', function () {
            injId = $(this).data('id');
            var inj_name = $('.inj_name').val('');
            var plan_name = $('select.plan_name').val('');
            var qty = $('.quantity').val('');
            var net_price = $('.net_price').val('');
            $('.inj-error').html('');
            $('.netPrice-error').html('');
            $('.quantity-error').html('');
            $.ajax({
            url: "{{URL::to('injection/edit')}}/" + injId,
            dataType: 'json',
            }).done(function (data) {
                if(data.status == 1)
                {
                    $('#inj-modal').modal('show');
                    $('.inj_name').val(data.injection.name);
                    $('.quantity').val(data.injection.quantity);
                    $('.net_price').val(data.injection.net_price);
                    $('select.plan_name').val(data.injection.type);
                    $('.plan_name').selectpicker('refresh');
                }
                else{

                }
            }).fail(function () {

            });
        });
        $(document).on('click','.inj-save',function(){
            var inj_name = $('.inj_name').val();
            var plan_name = $('select.plan_name').val();
            $('.plan_name').selectpicker('refresh');
            var qty = $('.quantity').val();
            var net_price = $('.net_price').val();
            var hasNoValue = 0;
            if(inj_name == '' || inj_name == '')
            {
                hasNoValue = 1;
                $('.expense-error').html('All fields are required');
                return false;
            }
            if(qty == '')
            {
                hasNoValue = 1;
                $('.quantity-error').html('All fields are required');
                return false;
            }
            if(net_price == '')
            {
                hasNoValue = 1;
                $('.netPrice-error').html('All fields are required');
                return false;
            }
            if(hasNoValue == 0)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'injection/store',
                    type: 'POST',
                    data: {
                        inj_name: inj_name,
                        plan:plan_name,
                        qty:qty,
                        net_price:net_price,
                        injId:injId
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
                url: "{{URL::to('injection')}}?"+qstring,
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
                removeInjection();
                $('.showSweetAlert').hide();
                // location.reload();
                // swal("Deleted!", "Your category has been deleted.", "success");
            });
        }
        // remove category
        function removeInjection(){
            $.ajax({
                url: "{{URL::to('injection/delete')}}"+'/'+injId,
                dataType: 'json',
            }).done(function(data) {
                getInjectionData(qstring);
                location.reload();

            }).fail(function() {

            });
        }
    </script>
@stop
