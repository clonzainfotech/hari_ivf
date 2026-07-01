@extends('layouts.main')
@section('parentPageTitle', 'Report')
@section('title', 'OPD 3C Report')
@section('page-style')


    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')
    <div class="row opd-3c-report report clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Opd 3C Report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-report">
                                    Print
                                </button> </a> --}}</li>
                    </ul>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <form method="post" autocomplete="off" action="">
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
                                </form>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                {{Form::select('report_days',[1=>'Daily',2=>'Monthly',3=>'Yearly'],'',['class'=>'form-control select-padding-0 report-days','placeholder'=>'Select report days'])}}
                            </div>
                        </div>
                    </div>
                    <div class="tab-content m-t-10">
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

                        <div class="report-data table-responsive active">
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
    </div>
@stop
@section('page-script')
<script type="text/javascript">
    var page = '';
    var search = '';
    var reportId = '';
    var fromdate = moment(new Date()).format('YYYY-MM-DD');
    var todate = moment(new Date()).format('YYYY-MM-DD');

    var report_days = '';
    var qstring = 'fromdate='+fromdate+'&todate='+todate;

    $(document).ready(function(){
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
            qstring = 'fromdate='+fromdate+'&todate='+todate;
            getReportData(qstring);

        });
        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {

            $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');

            $("#daterange").val('');


            fromdate = '';
            todate = '';
            qstring = 'fromdate='+fromdate+'&todate='+todate;
            getReportData(qstring);
        });

        getReportData(qstring);


        $(document).on('change','select.report-days',function(e){
            e.preventDefault();
            report_days = $(this).val();
            qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+'&todate='+todate+'&report_days='+report_days;
            getReportData(qstring);
        });

        $(document).on('click', '.pagination a',function(event){
            event.preventDefault();
            page=$(this).attr('href').split('page=')[1];
            qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+'&todate='+todate+'&report_days='+report_days;
            getReportData(qstring);
        });
    });

    $(document).on('click','.print-report',function(){
        var isprint= 1;
        $.ajax({
            url: "{{URL::to('report')}}?"+qstring,
            data:{isprint},
            dataType: 'json',
        }).done(function(data) {
            w = window.open(window.location.href,"_blank");
            w.document.open();
            w.document.write(data);
            w.document.close();
            w.window.print();
        });
    });

    // get all report data
    function getReportData(qstring){
        $('.reportdata-loader').removeClass('d-none');
        $('.reportdata').addClass('d-none');
        $('.pagination').addClass('d-none');
        $.ajax({
            url: "{{URL::to('report')}}?"+qstring,
            dataType: 'json',
        }).done(function(data) {
            $('.report-data').html(data);
            $('.reportdata-loader').addClass('d-none');
        }).fail(function() {

        });
    }
</script>
@stop
