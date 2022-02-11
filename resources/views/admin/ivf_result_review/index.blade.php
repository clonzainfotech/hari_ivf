@extends('layouts.main')
@section('parentPageTitle', 'IVF')
@section('title', 'IVF')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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
                                        {{Form::select('patient_id',$patients,'',[
                                            'class'=>'form-control select-padding-0 patient-id',
                                            'placeholder'=>'Select Patient',
                                            'id' => 'patient_id',
                                            'data-live-search' => 'true'
                                        ])}}
                                    </form>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('ivf_type','1','',[
                                        'id'=>'transfer-box',
                                        'class'=>'ivf-type',
                                    ])}}
                                    <label for="transfer-box">
                                        Transfer
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('ivf_type','2','',[
                                        'id'=>'result-box',
                                        'class'=>'ivf-type',
                                    ])}}
                                    <label for="result-box">
                                        Result          
                                    </label>
                                </div>
                                
                                {{-- <div class="col-md-4">{{Form::select('usg',['1'=>'Early Scan','2'=>'NT Scan','3'=>'Anomalies Miles','4'=>'Growth Scan'],'',['class'=>'usg select-padding-0 w-100','placeholder'=>'Select USG Type'])}}</div> --}}
                            </div>
                        </div>

                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">
                        <div class="ivf-result-data table-responsive active">
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
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
<script type="text/javascript">
    var page = '';
    
    var currentTime = new Date();
    var ivf_review_type = '';
    var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
        // Last Date Of the Month 
        var startDateTo = new Date();
        var fromdate = moment(startDateFrom).format('YYYY-MM-DD');
    var todate = moment(startDateTo).format('YYYY-MM-DD');
    var qstring = 'fromdate=' + fromdate + '&todate=' + todate ;

    $(document).ready(function () {

        $('input[name="daterange"]').daterangepicker({
            startDate: startDateFrom,
            endDate: startDateTo,
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
            qstring = 'fromdate=' + fromdate + '&todate=' + todate+ '&ivf_review_type='+ivf_review_type;
            getIvfResultReviewData(qstring);

        });
        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            // Reset values
            $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
            $("#daterange").val('');
            // Destroy and rebuild daterangepicker to clear data
            fromdate = '';
            todate = '';
            qstring = 'fromdate=' + fromdate + '&todate=' + todate+ '&ivf_review_type='+ivf_review_type;
            getIvfResultReviewData(qstring);
        });
        
        getIvfResultReviewData(qstring);

    });
    $(document).on('click','.ivf-type',function(){
        $('.ivf-type').not(this).prop('checked', false);
        if($(this).is(':checked')){
            ivf_review_type = $(this).val();
        }
        qstring = 'fromdate=' + fromdate + '&todate=' + todate+ '&ivf_review_type='+ivf_review_type;
        getIvfResultReviewData(qstring);
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
    function getIvfResultReviewData(qstring) {
        $('.categorydata-loader').removeClass('d-none');
        $('.categorydata').addClass('d-none');
        $('.pagination').addClass('d-none');
        $.ajax({
            url: "{{URL::to('ivf-result-review')}}?" + qstring,
            dataType: 'json',
        }).done(function (data) {
            $('.ivf-result-data').html(data.data);
            $('.categorydata-loader').addClass('d-none');
        }).fail(function () {

        });
    }
</script>
@stop
