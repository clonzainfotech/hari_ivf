@extends('layouts.main')
@section('parentPageTitle', 'Medicare Report')
@section('title', 'Medicare Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@stop
@section('content')
    <div class="row clearfix report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Medicare Collection report</strong></h2>
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
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','2',true,[
                                        'id'=>'cash_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="cash_box">
                                        Cash
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','1','',[
                                        'id'=>'swipe_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="swipe_box">
                                        Swipe
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','5','',[
                                        'id'=>'neft_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="neft_box">
                                        NEFT
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','3','',[
                                        'id'=>'cheque_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="cheque_box">
                                        Cheque
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','4','',[
                                        'id'=>'upi_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="upi_box">
                                        UPI
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report-payment-type','0','',[
                                        'id'=>'all_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="all_box">
                                        All
                                    </label>
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
                        <div class="medicare-report-data table-responsive active">
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
        var reportPaymentType = 2;
        var qstring = '?fromdate=' + fromdate + '&todate=' + todate+'&payment_type='+reportPaymentType;

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
                qstring = '?fromdate=' + fromdate + '&todate=' + todate+'&payment_type='+reportPaymentType;
                getPedReportData(qstring);

            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data
                fromdate = '';
                todate = '';
                qstring = '?fromdate=' + fromdate + '&todate=' + todate+'&payment_type='+reportPaymentType;
                getPedReportData(qstring);

            });

            getPedReportData(qstring);
        });

        $(document).on('click', '.print-pedia-report', function () {

                    var isprint = 1;
                    qstring = '?fromdate=' + fromdate + '&todate=' + todate+'&payment_type='+reportPaymentType;
                    $.ajax({
                        url: "{{URL::to('medicare-report')}}" + qstring,
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
        $(document).on('click','.report-payment-type',function(){
            reportPaymentType = 2;
            $('.report-payment-type').not(this).prop('checked', false);
            $('.all_type_payment').prop('checked', false);
            if($(this).is(':checked')){
                reportPaymentType = $(this).val();
            }
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+'&payment_type='+reportPaymentType;
            getPedReportData(qstring);
        });
        // get all collection report data
        function getPedReportData(qstring) {
            $('.report-loader').css('display','block');
            $.ajax({
                url: "{{URL::to('medicare-report')}}" + qstring,
                dataType: 'json',
            }).done(function (data) {
                $('.report-loader').css('display','none');
                $('.medicare-report-data').html(data.report_data);
            }).fail(function () {

            });
        }

    </script>

@stop
