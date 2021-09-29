@extends('layouts.main')
@section('parentPageTitle', 'Remark Appointment Report')
@section('title', 'Remark Appointmen Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Remark Appointment Report</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="form-group daterange">
                                    {{ Form::text('daterange', '',  [
                                        'id' => 'daterange',
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Date',
                                        'data-date-container' => '#myModalId',
                                        'data-provide'=> 'datepicker',
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 anc">
                                {{ Form::select('patient_id',$patients,'',[
                                    'class'=>'form-control select-padding-0 patient-id',
                                    'placeholder'=>'Select Patient',
                                    'id' => 'patient_id',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-remark" placeholder="Search by Remark" readonly="readonly" onfocus="this.removeAttribute('readonly')">
                                        <span class="input-group-addon search-border">
                                            <i class="zmdi zmdi-search"></i>
                                        </span>
                                    </div>
                                </ul>
                            </div>
                            <div class="col-md-5"></div>
                            {{-- <div class="col-md-1">
                                <a href="javascript:void(0);">
                                    <button class="btn btn-primary print-remark-report m-0">
                                        Print
                                    </button>
                                </a>    
                            </div> --}}
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

                        <div class="ref-doctor-pro-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="popupForButtons">
        <button class="btn btn-info next-anc">Next</button>
    </div>

@stop
@section('page-script')
    <script type="text/javascript">
        $(".daterange").daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });
        var qstring = '';
        var page = '';
        var patientId = '';
        var categoryId = '';
        var status = '';
        var qstring = '';
        var refProId = '';
        var search = '';
        var patientStatus = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate ;

        $(document).ready(function(){

            $('.next-button').hide();
            $('input[name="daterange"]').daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                    container: '#myModalId'
                }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {

                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&fromdate=' + fromdate + '&todate=' + todate;
                getRemarkAppointmentData(qstring);
            });

            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $("#daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&fromdate=' + fromdate + '&todate=' + todate;
                getRemarkAppointmentData(qstring);
            });
            getRemarkAppointmentData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&fromdate=' + fromdate + '&todate=' + todate;
                getRemarkAppointmentData(qstring);
            });

            $(document).on('keyup', '.search-remark',function(event){
                event.preventDefault();
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&fromdate=' + fromdate + '&todate=' + todate;
                getRemarkAppointmentData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&fromdate=' + fromdate + '&todate=' + todate;
                getRemarkAppointmentData(qstring);
            });

            $(document).on('click', '.print-remark-report', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&isprint=1'+'&fromdate=' + fromdate + '&todate=' + todate;
                getRemarkAppointmentData(qstring);
            });
            
        });

        // get all category data
        function getRemarkAppointmentData(qstring){
            // $('.anc-loader').removeClass('d-none');
            // $('.ancdata').addClass('d-none');
            // $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('remark-appointment-report')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.ref-doctor-pro-data').html(data.remark_appointment);
                    $('.anc-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.remark_appointment);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 50);
                }
            }).fail(function() {

            });
        }

    </script>
@stop
