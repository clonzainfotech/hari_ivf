@extends('layouts.main')
@section('parentPageTitle', 'Patient')
@section('title', 'Patient')
@section('page-style')


<link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@endsection
<style>
.dateto-word::before {
    position: absolute;
    content: 'To';
    font-size: 14px;
    right: -22px;
    top: 10px;
    letter-spacing: 0.5px;
}
</style>

@section('content')

    <div class="row clearfix report patient-report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Patient Report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="javascript:void(0);">
                                <button class="btn btn-primary print-report is-print" disabled>
                                    Print
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                        <div class="row">
                            <div  class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group daterange">
                                    <form method="post" autocomplete="off" action="">
                                    {{ Form::text('daterange', '',  [
                                        'id' => 'daterange',
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Date',
                                        'data-provide'=> 'datepicker',
                                        'autocomplete' => 'off'
                                    ]) }}
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                {{ Form::select('patient', $patient,'',[
                                    'class'=>'form-control select-padding-0 patient patient-1',
                                    'placeholder'=>'Select Patient',
                                    'data-live-search'=>'true',
                                    'data-id'=>'2'
                                ])}}
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                {{ Form::select('patient', $pMobileNumber,'',[
                                    'class'=>'form-control select-padding-0 patient patient-2',
                                    'placeholder'=>'Select Patient Mobile Number',
                                    'data-live-search'=>'true',
                                    'data-id'=>'1'
                                ])}}
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                {{ Form::select('category', $category,'',[
                                    'class'=>'form-control select-padding-0 category',
                                    'placeholder'=>'Select Category',
                                    'data-live-search'=>'true',
                                    'data-id'=>'3'
                                ])}}
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="checkbox">
                                    {{Form::checkbox('advanced_search','',false,[
                                        'id'=>'cash_box',
                                        'class'=>'advanced_search',
                                    ])}}
                                    <label for="cash_box">
                                        Advance Search
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="row advanced-search d-none">
                            <div class="col-lg-3 col-md-6 col-sm-3">
                                <div class="form-group dateto-word">
                                    {{ Form::date('allIncome_fromDate','',[
                                        'class'=>'form-control category allIncome_fromDate',
                                        'placeholder'=>'Select From Date',
                                    ])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-3">
                                <div class="form-group">
                                    {{ Form::date('allIncome_toDate','',[
                                        'class'=>'form-control category allIncome_toDate',
                                        'placeholder'=>'Select From Date',
                                    ])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-3">
                                <span class="candor-color">** Select Date for get recent patients all Income **</span>
                            </div>
                        </div>
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">
                        <div class="patient-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">

        var search = '';
        var pId = '';
        var category = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var allIncome_fromDate = '';
        var allIncome_toDate = '';

        var qstring = 'fromdate=' + fromdate + '&todate=' + todate ;

        $(document).ready(function(){

            getPatientData(qstring);
            $('input[name="daterange"]').daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {

                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category;
                getPatientData(qstring);
            });

            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $("#daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category;
                getPatientData(qstring);
            });

        });
            $(document).on('change','.advanced_search',function(){
                $('.advanced-search').addClass('d-none')
                if($(this).prop('checked') == true)
                {
                    $('.advanced-search').removeClass('d-none');
                }
            })
            $(document).on('change','.allIncome_fromDate', function() {
                allIncome_fromDate = $(this).val();
                qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category+'&allIncome_fromDate='+allIncome_fromDate+'&allIncome_toDate='+allIncome_toDate;
                getPatientData(qstring);

            });
            $(document).on('change','.allIncome_toDate', function() {
                allIncome_toDate = $(this).val();
                qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category+'&allIncome_fromDate='+allIncome_fromDate+'&allIncome_toDate='+allIncome_toDate;
                getPatientData(qstring);

            });

            $(document).on('change','select.patient', function() {
                var dId = $(this).data('id');
                $('.patient-'+dId).val('');
                $('.patient-'+dId).selectpicker('refresh');
                pId = $(this).val();
                qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category;
                getPatientData(qstring);
            });
            $(document).on('change','select.category',function(){
                category = $(this).val();
                qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category;
                getPatientData(qstring);
            })
            function getPatientData(qstring){
                $('.patientdata-loader').removeClass('d-none');
                $('.cutdata').addClass('d-none');
                $.ajax({
                    url: "{{URL::to('patient-report')}}?" + qstring,
                    dataType: 'json',
                    }).done(function(data) {

                        $('.patient-data').html(data);
                        $('.patientdata-loader').addClass('d-none');
                        $('.cutdata').removeClass('d-none');
                        $('.is-print').attr('disabled', true);
                        if ($('.print').val() != undefined) {
                            $('.is-print').removeAttr('disabled');
                        }
                    });
        }

        $(document).on('click','.print-report',function(){
            qstring = 'fromdate=' + fromdate + '&todate=' + todate+"&patient_id="+pId+'&category='+category+'&isprint=1';
            $.ajax({
                url: "{{URL::to('patient-report')}}?" + qstring,

                dataType: 'json',
            }).done(function(data) {
                w = window.open(window.location.href,"_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
            });
        });
    </script>
@stop
