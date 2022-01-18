@extends('layouts.main')
@section('parentPageTitle', 'IVF')
@section('title', 'IVF')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <style>
        .payment-form{
            padding: 5px 0px 1px 10px !important;
        }
        @media (min-width: 576px){
            .modal-dialog {
                max-width: 800px !important;
            }
        }

    </style>
@stop
@section('content')

    <div class="row clearfix ivf">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>IVF List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="post" autocomplete="off" action="">
                                        <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    {{Form::select('patient_id',$patients,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-md-3">
                                    {{Form::select('plan',['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'],'',[
                                        'class'=>'form-control select-padding-0',
                                        'placeholder'=>'Select Plan',
                                        'id' => 'plan_data',
                                    ])}}
                                </div>
                                <div class="col-md-2">
                                    <form method="post" autocomplete="off" action="">
                                        <ul class="nav nav-tabs padding-0">
                                            <div class="input-group">
                                                <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no" autocomplete="off">
                                                <span class="input-group-addon search-border">
                                                    <i class="zmdi zmdi-search"></i>
                                                </span>
                                            </div>
                                        </ul>
                                    </form>
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0);">
                                        <button class="btn btn-primary print-ivf m-0">
                                            Print
                                        </button>
                                    </a>
                                </div>
                                {{-- <div class="col-md-4">{{Form::select('usg',['1'=>'Early Scan','2'=>'NT Scan','3'=>'Anomalies Miles','4'=>'Growth Scan'],'',['class'=>'usg select-padding-0 w-100','placeholder'=>'Select USG Type'])}}</div> --}}
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

                        <div class='alert alert-success d-none ivf-payment-msg'>
                            <strong>Success!</strong> Payment successfully added.
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>
                                    <i class='zmdi zmdi-close'></i>
                                </span>
                            </button>
                        </div>

                        <div class="ivf-data table-responsive active">
                            <div class="row">
                                <div class="page-loader-wrapper medicine-loader">
                                    <div class="loader">
                                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- @section('modal')
        <div class="modal fade" id="ivf-payment" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content"> -->
                    <!-- header -->
                   <!--  <div class="modal-header justify-content-center">
                        <h4 class="title" id="next-appointment">Payment</h4>
                    </div> -->
                    <!-- body -->
                    <!-- {{Form::open(['class'=>'form-inline','id'=>'ivf-payment-form'])}}
                        <div class="modal-body">
                            {{-- <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Deposit
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('deposit','0',['class'=>'form-control p-deposit','placeholder'=>'Deposit','min'=>1,'disabled'])}}
                                    </div>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Patient Name : &nbsp;</span>
                                        {{Form::text('p_name','',['class'=>'form-control p-name-value'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Husband Name : &nbsp;</span>
                                        {{Form::text('h_name','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Patient Name
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::text('p_name','',['class'=>'form-control','placeholder'=>'Enter Patient Name'])}}
                                    </div>
                                    <span class="form-error-msg cycle_error ml-5"></span>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">No. Cycle : &nbsp;</span>
                                        {{Form::number('no_cycle','',['class'=>'form-control p-no-cycle','min'=>1])}}
                                    </div>
                                    <span class="form-error-msg cycle_error m-0"></span>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Sonography : &nbsp;</span>
                                        {{Form::number('sonography','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">IVF Lab Charge : &nbsp;</span>
                                        {{Form::number('ivf_lab_charge','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">ICSI - IVF : &nbsp;</span>
                                        {{Form::text('icsi_ivf','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Embryo Transfer : &nbsp;</span>
                                        {{Form::text('embryo_transfer','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Embryo Freezing : &nbsp;</span>
                                        {{Form::text('embryo_freezing','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Hystrocopy : &nbsp;</span>
                                        {{Form::number('hystrocopy','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Embryo Freezing : &nbsp;</span>
                                        {{Form::number('embryo_freezing','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Medical Medicines : &nbsp;</span>
                                        {{Form::number('medical_medicines','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Anaithesia Charge : &nbsp;</span>
                                        {{Form::number('unconscious_charge','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Blood Report : &nbsp;</span>
                                        {{Form::number('blood_report','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Tesa Pesa Charge : &nbsp;</span>
                                        {{Form::number('tesa_pesa','',['class'=>'form-control','min'=>0])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Donor Charge : &nbsp;</span>
                                        {{Form::number('donor_charge','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Time : &nbsp;</span>
                                        {{Form::text('time','',['class'=>'form-control p-time'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Payment : &nbsp;</span>
                                        {{Form::number('payment','',['class'=>'form-control p-total-payment','min'=>1,'onkeypress'=>"return event.charCode >= 48 && event.charCode <= 57"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error m-0"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Package : &nbsp;</span>
                                        {{Form::number('package','',['class'=>'form-control','min'=>1,'onkeypress'=>"return event.charCode >= 48 && event.charCode <= 57"])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-padding">
                                        {{Form::select('payment_type',['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],'',['class'=>'form-control','placeholder'=>'Select Payment Type'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Condition : &nbsp;</span>
                                        {{Form::text('condition','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Remark : &nbsp;</span>
                                        {{Form::text('remark','',['class'=>'form-control p-remark','autocomplete'=>'off'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 text-left ml-2 total-paid-ivf-payment">

                                </div>
                            </div>
                            {{Form::hidden('patients_id','',['class'=>'patients-id'])}}
                        </div> -->
                        <!-- footer -->
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect ivf-payment-submit">Save</button>
                            <button type="button" class="btn btn-primary waves-effect ivf-payment-submit ml-3" value="1">Save & Preview</button>
                            <button type="button" class="btn btn-default waves-effect ml-3" data-dismiss="modal">Close</button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    @stop -->
@stop
@section('page-script')
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        $(".daterange").daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });
        var qstring = '';
        var plan = '';
        var page = '';
        var patientId = '';
        var status = '';
        var date = $('.daterange').val();
        var qstring = 'date='+date;
        var usg = '';
        var search = '';


        $(document).ready(function(){

            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });

            $(document).on('change','select.usg',function(e){
                e.preventDefault();
                usg = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });
            $(document).on('change','select#plan_data',function(e){
                e.preventDefault();
                plan = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });

            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });

            getIvfData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&plan='+plan;
                getIvfData(qstring);
            });

            $(document).on('click','.ivf-payment',function(){
                $('.p-name-value').val('');
                $('.p-r-amount').text('');
                $('.total-paid-ivf-payment').text('');
                $('.p-total-payment-error').text('');
                $('.cycle_error').text('');
                // depositData();
                $('.ivf-payment-msg').addClass('d-none');
                $('.patients-id').val($(this).data('id'));
                $('#ivf-payment-form').trigger('reset');
                $('.p-name-value').val($(this).data('name'));
                getIvfPaymentTotal($(this).data('id'));
                // getIvfPayment($(this).data('id'));
            });

            // $(document).on('keyup','.p-total-payment',function(){
            //     depositData();
            // });

            $(document).on('dblclick', '#appointment-table tbody tr', function(event) {
                var patientsId = $(this).data('id');
                if(typeof(patientsId) !== 'undefined'){
                    var type = 'create';
                    if($(this).hasClass('ivf-history')){
                        type = 'history';
                    }
                    var url = "{{URL::to('ivf')}}"+'/'+type+'/'+patientsId;
                    window.location.href=url;
                }
            });

            $(document).on('click', '.print-ivf', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&isprint=1';
                getIvfData(qstring);
            });

        });

       /* function getIvfPaymentTotal(pId){
            $.ajax({
                url: "{{URL::to('get-ivf-payment-total')}}",
                dataType: 'json',
                data:{patient_id:pId}
            }).done(function(data) {
                if(data.status == 1){
                    $('.total-paid-ivf-payment').html('Total Paid Amount : <b>'+ data.total+'</b>');
                }
            }).fail(function() {

            });
        }*/

        // get appointment data
        function getIvfData(qstring){
            $('.ivf-loader').removeClass('d-none');
            $('.ivfdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ivf')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.ivf-data').html(data.ivf);
                    $('.ivf-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.ivf);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }



        /*function getIvfPayment(pId){
            $.ajax({
                url: "{{URL::to('ivf-payment')}}"+'/'+pId,
                dataType: 'json',
            }).done(function(data) {
                $('.p-r-amount').text('');
                $('#ivf-payment-form').trigger('reset');
                var deposit = data.deposit;
                var paymentData = data.ivfPayment;
                if(deposit){
                    $('.p-deposit').val(deposit.total);
                }
                if(paymentData){
                    // $('.p-r-amount').text(paymentData.remaining_payment != null ? '₹'+paymentData.remaining_payment : '');
                    // $('.p-total-payment').val(paymentData.total_payment);
                    // $('.p-remark').val(paymentData.remark);
                    // $('.p-time').val(paymentData.time);
                    // $('.p-no-cycle').val(paymentData.cycle_no);
                }
            }).fail(function() {

            });
        }*/

        /*$(document).ready(function(){
            $(document).on('click','.ivf-payment-submit',function(e){
                e.preventDefault();
                var paymentData = $('#ivf-payment-form').serialize();
                if(this.value == 1) {
                    paymentData = paymentData + '&isprint=1';
                }

                storeIvfPayment(paymentData);
            });
        });

        function storeIvfPayment(data){
            $('.p-total-payment-error').text('');
            $('.cycle_error').text('');
            var isError = 0;
            if($('.p-total-payment').val() == ''){
                $('.p-total-payment-error').text('This field is required.');
                isError = 1;
            }
            if($('.p-no-cycle').val() == ''){
                $('.cycle_error').text('This field is required.');
                isError = 1;
            }
            if(isError == 1){
                return true;
            }
            $.ajax({
                url: "{{URL::to('ivf-store-payment')}}",
                dataType: 'json',
                type:"POST",
                data:data
            }).done(function(data) {
                if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 50);
                        // $('#ivf_history_id').val(data.id);
                }else if(data.status == 'true'){
                    $('.ivf-payment-msg').removeClass('d-none');
                    $('#ivf-payment').modal('hide');
                }else{
                    location.reload();
                }
            }).fail(function() {

            });
        }

        function depositData(){
            var value = $('.p-total-payment').val();
            var deposit = $('.p-deposit').val();
            var finalValue = deposit - value;
            $('.p-r-amount').text('');
            if(typeof finalValue != 'undefined' && $.isNumeric(finalValue) && deposit != finalValue){
                if(finalValue < 0){
                    finalValue = Math.abs(finalValue);
                }
                $('.p-r-amount').text('₹'+finalValue);
            }
        }
        */

    </script>
@stop
