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
                    <h2><strong>Collection report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a class="text-white">
                                <button class="btn btn-primary export-collection-report">
                                    Export
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <button class="btn btn-primary print-collection-report">
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
                                <div class="col-md-6 col-sm-12">
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
                                    {{Form::checkbox('all_type_payment','0','',[
                                        'id'=>'all_box',
                                        'class'=>'all_type_payment',
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
                        <div class="collection-report-data table-responsive active">
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

        var usg = '';
        var hormon = '';
        var iui = '';
        var ivf = '';
        var mainCollection = '';
        var income = '';
        var expense = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var reportPaymentType = 2;
        var referenceDoctorId = '';
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate +'&payment_type='+reportPaymentType;

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
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType;
                if($('.all_type_payment').is(':checked'))
                {
                    qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                    getAllCollectionReportData(qstring);
                }
                else{
                    getCollectionReportData(qstring);
                }
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data

                fromdate = '';
                todate = '';
                if($('.all_type_payment').is(':checked'))
                {
                    qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                    getAllCollectionReportData(qstring);
                }
                else{
                    qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType;
                    getCollectionReportData(qstring);
                }
            });
            getCollectionReportData(qstring);

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var keyValue = $(this).attr('href').split('?')[1];
                var keyword  = keyValue.split('=')[0];
                var page = keyValue.split('=')[1];

                switch(true) {
                    case ('usg' == keyword):
                        usg = page;
                        break;
                    case ('hormon' == keyword):
                        hormon = page;
                        break;
                    case ('iui' == keyword):
                        // code block
                        iui = page;
                        break;
                    case ('ivf' == keyword):
                        // code block
                        ivf = page;
                        break;
                    case ('mainCollection' == keyword):
                        mainCollection = page;
                        break;
                    case ('income' == keyword):
                        // code block
                        income = page;
                        break;
                    case ('expense' == keyword):
                        // code block
                        expense = page;
                        break;

                }
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType;
                getCollectionReportData(qstring);
            });

            $(document).on('change', 'select.reference-doctor', function () {
                referenceDoctorId = $(this).val();
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType;
                getCollectionReportData(qstring);
            });
        });

        $(document).on('click', '.print-collection-report', function () {

            if($('.all_type_payment').is(':checked'))
                {
                    var isprint = 1;
                    qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                    $.ajax({
                        url: "{{URL::to('all-collection-report')}}" + qstring,
                        data: {isprint},
                        dataType: 'json',
                    }).done(function (data) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data);
                        w.document.close();
                        w.window.print();
                    });
                }
                else{
                    var isprint = 1;
                    $.ajax({
                        url: "{{URL::to('collection-report')}}?" + qstring,
                        data: {isprint},
                        dataType: 'json',
                    }).done(function (data) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data);
                        w.document.close();
                        w.window.print();
                    });
                }
        });

        $(document).on('click','.report-payment-type',function(){
            reportPaymentType = 2;
            $('.report-payment-type').not(this).prop('checked', false);
            $('.all_type_payment').prop('checked', false);
            if($(this).is(':checked')){
                reportPaymentType = $(this).val();
            }
            qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType;
            getCollectionReportData(qstring);
        });
        $('.all_type_payment').on('change', function() {
            $('.report-payment-type').prop('checked', false);
            if($(this).is(':checked')){
                qstring = '?fromdate=' + fromdate + '&todate=' + todate;
                getAllCollectionReportData(qstring);
            }
            else{
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType;
                getCollectionReportData(qstring);
            }

        });

        $(document).on('click', '.export-collection-report', function () {
            qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&isexport=1';
            var exportUrl = "{{URL::to('collection-report?')}}" + qstring;
            window.location.href = exportUrl;
        });

        // get all collection report data
        function getCollectionReportData(qstring) {
            $('.report-loader').css('display','block');
            $('.collection-report-data').addClass('d-none');
            $.ajax({
                url: "{{URL::to('collection-report')}}?" + qstring,
                dataType: 'json',
            }).done(function (data) {
                $('.report-loader').css('display','none');
                $('.collection-report-data').removeClass('d-none');
                $('.collection-report-data').html(data);
            }).fail(function () {

            });
        }
        //get all type of collection
        function getAllCollectionReportData(qstring) {
            $('.report-loader').css('display','block');
            $('.collection-report-data').addClass('d-none');
            $.ajax({
                url: "{{URL::to('all-collection-report')}}" + qstring+'&all_type_payment=1',
                dataType: 'json',
            }).done(function (data) {
                $('.report-loader').css('display','none');
                $('.collection-report-data').removeClass('d-none');
                $('.collection-report-data').html(data);
            }).fail(function () {

            });
        }
    </script>

@stop
