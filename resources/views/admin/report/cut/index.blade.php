@extends('layouts.main')
@section('parentPageTitle', 'Report')
@section('title', 'CUT Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')
    <div class="row clearfix report cut cut-report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Cut Report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-cut-report">
                                    Print
                                </button> </a> --}}</li>
                    </ul>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5 col-sm-5">
                                    <div class="form-group daterange">
                                        <form method="post" autocomplete="off" action="">
                                        {{ Form::text('daterange', '',  [
                                            'id' => 'daterange',
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Date',
                                            'data-date-container' => '#myModalId',
                                            'data-provide'=> 'datepicker',
                                            'autocomplete' => 'off'
                                        ]) }}
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-7">
                                    {{Form::select('reference_doctor',$doctor,'',[
                                        'class'=>'form-control select-padding-0 reference-doctor',
                                        'placeholder'=>'Select Reference Doctor',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                            </div>
                        </div>
                    <div class="tab-content m-t-10">
                        <div class="cut-report-data table-responsive active">
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
@stop
@section('page-script')
    <script type="text/javascript">
        var page = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var referenceDoctorId = '';
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate ;

        $(document).ready(function () {

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
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getCutReportData(qstring);
            });

            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $("#daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getCutReportData(qstring);
            });

            getCutReportData(qstring);

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getCutReportData(qstring);
            });

            $(document).on('change', 'select.reference-doctor', function () {
                referenceDoctorId = $(this).val();
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getCutReportData(qstring);
            });
        });

        $(document).on('click', '.print-cut-report', function () {
            var isprint = 1;
            $.ajax({
                url: "{{URL::to('cut-report')}}?" + qstring,
                data: {isprint},
                dataType: 'json',
            }).done(function (data) {
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
            });
        });

        // get all cut wise report data
        function getCutReportData(qstring) {
            $('.cutdata-loader').removeClass('d-none');
            $('.cutdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('cut-report')}}?" + qstring,
                dataType: 'json',
            }).done(function (data) {
                $('.cut-report-data').html(data);
                $('.cutdata-loader').addClass('d-none');
            }).fail(function () {

            });
        }
    </script>

@stop
