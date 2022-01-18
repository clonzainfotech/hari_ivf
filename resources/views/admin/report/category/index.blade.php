@extends('layouts.main')
@section('parentPageTitle', 'Report')
@section('title', 'Category Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <style>
        .form-inline .form-group {
            flex: none !important;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Category Report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="javascript:void(0);">
                                <button class="btn btn-primary print-category-report">
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
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-group daterange">
                                        <form method="post" autocomplete="off" action="">
                                        {{ Form::text('daterange', '',  [
                                            'id' => 'daterange',
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Date',
                                            'data-date-container' => '#myModalId',
                                            'data-provide'=> 'datepicker',
                                            'autocomplete'=>'off'
                                        ]) }}
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    {{Form::select('category',$category,'',[
                                        'class'=>'form-control select-padding-0 category',
                                        'placeholder'=>'Select Category',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    {{Form::select('hospital_doctor',$hospitalDoctor,11,[
                                        'class'=>'form-control select-padding-0 hospital-doctor',
                                        'placeholder'=>'Select Hospital Doctor',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-6">
                                    {{Form::select('report_type',[1=>'Details',2=>'Summary'],'1',[
                                        'class'=>'form-control select-padding-0 report-type',
                                        'placeholder'=>'Select Report type',
                                    ])}}
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
                        <div class="category-report-data table-responsive active">
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
        var categoryId = '';
        var hospitalDoctorId = $('.hospital-doctor').val();
        var reportType = $('.report-type').val();
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;

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
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate +'&categoryId=' + categoryId + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;
                getCategoryReportData(qstring);
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data

                fromdate = '';
                todate = '';
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate +'&categoryId=' + categoryId + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;
                getCategoryReportData(qstring);
            });

            getCategoryReportData(qstring);

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate +'&categoryId=' + categoryId + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;
                getCategoryReportData(qstring);
            });

            $(document).on('change', 'select.category', function () {
                categoryId = $(this).val();
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate +'&categoryId=' + categoryId + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;
                getCategoryReportData(qstring);
            });

            $(document).on('change', 'select.hospital-doctor', function () {
                hospitalDoctorId = $(this).val();
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&categoryId=' + categoryId + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;
                getCategoryReportData(qstring);
            });

            $(document).on('change', 'select.report-type', function () {
                reportType = $(this).val();
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&categoryId=' + categoryId + '&hospital_doctor_id=' + hospitalDoctorId + '&reportType=' + reportType;
                getCategoryReportData(qstring);
            });
        });

        $(document).on('click', '.print-category-report', function () {
            var isprint = 1;
            $.ajax({
                url: "{{URL::to('category-report')}}?" + qstring,
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

        // get all category wise report data
        function getCategoryReportData(qstring) {
            $('.categorydata-loader').removeClass('d-none');
            $('.categorydata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('category-report')}}?" + qstring,
                dataType: 'json',
            }).done(function (data) {
                $('.category-report-data').html(data);
                $('.categorydata-loader').addClass('d-none');
            }).fail(function () {

            });
        }
    </script>
@stop
