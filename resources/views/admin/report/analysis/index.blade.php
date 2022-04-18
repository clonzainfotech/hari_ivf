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
    .default-box-border
    {
        border: 1px solid gray !important;

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
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="card default-box-border">
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="text-muted font-16 text-bold">Total IUI</span>
                                                <h4 class="number mt-0 mb-0">{{$total_IUI}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card default-box-border">
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="text-muted font-16 text-bold">Total Conceived</span>
                                                <h4 class="number mt-0 mb-0 candor-color">{{$total_consive}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card default-box-border">
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="text-muted font-16 text-bold">Total Fail</span>
                                                <h4 class="number mt-0 mb-0 text-danger">{{$total_fail}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card default-box-border">
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="text-muted font-16 text-bold">Total Pending Result</span>
                                                <h4 class="number mt-0 mb-0 candor-color">{{$data_pending_result}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <form method="post" autocomplete="off" action="">
                                        {{Form::select("injection_type", $planData, null,['class'=>'form-control select-padding-0 injection-type','title'=>'Select Plan','data-live-search'=>"true"])}}
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <form method="post" autocomplete="off" action="">
                                        {{Form::select("age",['20-25'=>'20-25','26-30'=>'26-30','31-35'=>'31-35','36-40'=>'36-40','41-45'=>'41-45','46-50'=>'46-50','51-55'=>'51-55','56-60'=>'56-60'], null,['class'=>'form-control select-padding-0 age','title'=>'Select Age','data-live-search'=>"true"])}}
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <form method="post" autocomplete="off" action="">
                                        {{Form::select("follicle",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'More than 5'], null,['class'=>'form-control select-padding-0 follicle','title'=>'Select Follicle Number','data-live-search'=>"true"])}}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <span class="text-danger">**Below All Analysis data is according to Above Filters**</span>
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
        var key = 'new-inf';
        var currentTime = new Date();
        // First Date Of the month 
        var fromdate = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
        // Last Date Of the Month 
        var todate = new Date();
        var currentData = 'new-inf';
        var plan_type = '';
        var injection_type = '';
        var age = '';
        var follicle ='';
        var qstring = '?fromdate=' + moment(fromdate).format('YYYY-MM-DD') + '&todate=' + moment(todate).format('YYYY-MM-DD') + '&search='+search+ '&key='+key+'&plan_type='+plan_type+'&injection_type='+injection_type+'&age='+age;

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
                qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+ '&key='+key+'&plan_type='+plan_type+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
                getAnalysisData(qstring);

            });
            
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data
                fromdate = '';
                todate = '';
                qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+ '&key='+key+'&plan_type='+plan_type+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
                getAnalysisData(qstring);
            });
            getAnalysisData(qstring);
        });
        
        $(document).on("keyup",'#myInput', function() {
            
            search = $(this).val();
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
            getAnalysisData(qstring)
        });
        $(document).on('click','.card.iui-box',function(){
            $('.card.iui-box').removeClass('box-border');
            currentData = $(this).data("key");
            key = $(this).data('key');
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
            getAnalysisData(qstring)
            
        });
        $(document).on('change','select.plan-type',function(){
            plan_type = $(this).val();
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
            getPlanData($("select.plan-type option:selected").html());
            getAnalysisData(qstring);
        });
        $(document).on('change','select.injection-type',function(){
            
            injection_type = $(this).val();
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
            getAnalysisData(qstring);
        });
        
        $(document).on('change','select.follicle',function(){
            follicle = $(this).val();
            qstring = '?fromdate=' + fromdate + '&todate=' + todate+ '&search='+search+'&plan_type='+plan_type+'&key='+key+'&injection_type='+injection_type+'&age='+age+'&follicle='+follicle;
            getAnalysisData(qstring);
        });
        // get all collection report data
        function getAnalysisData(qstring) {
            fromdate = moment(fromdate).format('YYYY-MM-DD');
            todate =  moment(todate).format('YYYY-MM-DD');
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
        function getPlanData(type){
            $.ajax({
                url: "{{URL::to('get-plandata')}}"+'/'+type,
                dataType: 'json',
                type:'GET',
            }).done(function(data){
                var planDataWithIds = [];
                $.each(data.planDataWithIds, function(key, value) {
                    planDataWithIds +=  '<option value="' + key + '">'+value+'</option>';
                });
                $('select.injection-type').html(planDataWithIds);
                $('.injection-type').selectpicker('refresh');
            }).fail(function(error){

            });
        }

    </script>

@stop
