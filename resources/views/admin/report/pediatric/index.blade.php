@extends('layouts.main')
@section('parentPageTitle', 'Report')
@section('title', 'Collection Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@stop
@section('content')
    <div class="row clearfix report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Pediatric Collection report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="javascript:void(0);">
                                <button class="btn btn-primary print-pedia-report">
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
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group daterange">
                                        {{ Form::text('daterange', '',  [
                                            'id' => 'daterange',
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Date',
                                            'data-date-container' => '#myModalId',
                                            'data-provide'=> 'datepicker',
                                            'autocomplete'=>'off'
                                        ]) }}
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <div class="tab-content m-t-10">
                        <div class="row">
                            <div class="page-loader-wrapper medicine-loader report-loader">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                        </div>
                        <div class="pediatric-report-data table-responsive active">
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
        
        var qstring = '?fromdate=' + fromdate + '&todate=' + todate;

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
                qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                getPedReportData(qstring);
                
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data
                fromdate = '';
                todate = '';
                qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                getPedReportData(qstring);
                
            });

            getPedReportData(qstring);
        });

        $(document).on('click', '.print-pedia-report', function () {

                    var isprint = 1;
                    qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                    $.ajax({
                        url: "{{URL::to('pediatric-report')}}" + qstring,
                        data: {isprint : isprint},
                        dataType: 'json',
                    }).done(function (data) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.report_data);
                        w.document.close();
                        w.window.print();
                    });
                
        });

        // get all collection report data
        function getPedReportData(qstring) {
            $('.report-loader').css('display','block');
            $.ajax({
                url: "{{URL::to('pediatric-report')}}" + qstring,
                dataType: 'json',
            }).done(function (data) {
                $('.report-loader').css('display','none');
                $('.pediatric-report-data').html(data.report_data);
            }).fail(function () {

            });
        }
       
    </script>

@stop
