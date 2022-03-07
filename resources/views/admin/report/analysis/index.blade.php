@extends('layouts.main')
@section('parentPageTitle', 'Report')
@section('title', 'Collection Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <style>
    .box-border
    {
        border: 2px solid #1e5f63 !important;
    }
    </style>

@stop
@section('content')
    <div class="row clearfix report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>INF Analysis Report</strong></h2>
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
                            <div class="col-md-3">
                                <form method="post" autocomplete="off" action="">
                                    <input type="text" name="" class="form-control mb-3" value="" id="myInput" placeholder="Search by name and mobile">
                                </form>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <form method="post" autocomplete="off" action="">
                                        {{Form::select("plan_type",$planType,null,['class'=>'form-control select-padding-0 plan-type','placeholder'=>'Select Plan Type','data-live-search'=>"true"])}}

                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                    <div class="tab-content m-t-10">
                        
                        
                        <div class="analysis-report-data table-responsive active">
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
        // var fromdate = moment(new Date()).format('YYYY-MM-DD');
        // var todate = moment(new Date()).format('YYYY-MM-DD');
        var search = '';
        var key = 'total';
        var currentTime = new Date();
        // First Date Of the month 
        var fromdate = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
        // Last Date Of the Month 
        var todate = new Date();
        var currentData = 'total';
        var plan_type = '';
        var qstring = '?fromdate=' + fromdate + '&todate=' + todate + '&search='+search+ '&key='+key+'&plan_type='+plan_type;

        $(document).ready(function () {

            $('input[name="daterange"]').daterangepicker({
                startDate: fromdate,
                endDate: todate,
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
                qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+ '&key='+key+'&plan_type='+plan_type;
                getAnalysisData(qstring);

            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data
                fromdate = '';
                todate = '';
                qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+ '&key='+key+'&plan_type='+plan_type;
                getAnalysisData(qstring);
            });
            getAnalysisData(qstring);
        });
        
        $(document).on("keyup",'#myInput', function() {
            search = $(this).val();
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search;
            getAnalysisData(qstring)
        });
        $(document).on('click','.card.iui-box',function(){
            // $('select.plan-type').val('');
            // $('.plan-type').selectpicker('refresh');
            $('.card.iui-box').removeClass('box-border');
            currentData = $(this).data("key");
            key = $(this).data('key');
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key;
            getAnalysisData(qstring)
            // $(this).addClass('box-border');
        });
        $(document).on('change','select.plan-type',function(){
            plan_type = $(this).val();
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key;
            getAnalysisData(qstring)
            // $(this).addClass('box-border');
        });
        // get all collection report data
        function getAnalysisData(qstring) {
            $('.reportdata-loader').removeClass('d-none');
            $.ajax({
                url: "{{URL::to('analysis-report')}}" + qstring,
                dataType: 'json',
            }).done(function (data) {
                console.log(currentData);
                $('.analysis-report-data').html(data.report_data);
                $('.reportdata-loader').addClass('d-none');
                $("div[data-key='" + currentData + "']").addClass("box-border");
            }).fail(function () {

            });
        }

    </script>

@stop
