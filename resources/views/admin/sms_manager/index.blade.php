@extends('layouts.main')
@section('parentPageTitle', 'SMS Manager')
@section('title', 'SMS Manager')

@section('page-style')


@stop

@section('content')

    <div class="row clearfix sms-manager">
        <div class="card">
            <div class="header">
                <h2><strong>SMS Manager</strong></h2>
                <ul class="header-dropdown">
                    <li>
                        {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-sms-report">
                                Print
                            </button> </a> --}}</li>
                </ul>
            </div>
            <div class="body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <input type="text" class="form-control daterange" placeholder="Select Date">
                            </div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    {{ Form::select('reference_doctor',$referenceDoctors,'',[
                                        'class'=>'form-control select-padding-0 reference-doctor',
                                        'placeholder'=>'Select Doctor',
                                        'id' => 'reference_doctor',
                                        'data-live-search' => 'true'
                                    ])}}
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-mobile-number" placeholder="Search by mobile no">
                                        <span class="input-group-addon search-border">
                                            <i class="zmdi zmdi-search"></i>
                                        </span>
                                    </div>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-msg-number" placeholder="Search by Message">
                                        <span class="input-group-addon search-border">
                                            <i class="zmdi zmdi-search"></i>
                                        </span>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                <div class="tab-content m-t-10">
                    <div class="sms-data">
                        <div class="row">
                            <div class="page-loader-wrapper medicine-loader">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script type="text/javascript">
        var page = '';
        var referenceDoctorId = '';
        var patientId = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var search = '';
        var msg = '';
        var qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;

        $(document).ready(function(){
            $(".daterange").daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y'
                }
            });

             $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });

             $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                 qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });

            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });

            $(document).on('change','select.reference-doctor',function(){
                referenceDoctorId = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });
            $(document).on('keyup','.search-msg-number',function(){
                msg = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId;
                getSMSdata(qstring);
            });

            $(document).on('click', '.print-sms-report', function () {
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&msg='+msg+'&reference_doctor_id='+referenceDoctorId+'&isprint=1';
                getSMSdata(qstring);
            });
            getSMSdata(qstring);
        });

        // get all referance doctor data
        function getSMSdata(qstring){
            $('.smsdata-loader').removeClass('d-none');
            $('.smsdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('sms-manager')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.sms-data').html(data.sms);
                    $('.smsdata-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.sms);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
    </script>
@stop
