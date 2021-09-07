@extends('layouts.main')
@section('parentPageTitle', 'IVF History Appointment')
@section('title', 'IVF History Appointment')
@section('content')
@php
    $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
@endphp
    <div class="row clearfix">
        <div class="col-md-12">
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
        </div>
        {{-- Pick Up --}}
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 roomtype_name">
                    <h5>Pick Up</h5>
                </div>
            </div>
            @php
                $cycleNumber = 0;
            @endphp
                @foreach($pickupCycle as $key=>$row)
                    @php
                        $cycleNumber++;
                        $cycleNoKey = array_search('1',$dataForSkipPlans);
                        
                        $class = null;
                        if($lastPlan == 1 && $lastCycleNo == $row){
                            $class = 'current-cycle';
                            
                        }
                        $cycleNo = explode('_',$cycleNoKey);
                        $cycleNo = array_filter($cycleNo);
                        if(!empty($cycleNo)){
                            $cycleNo = (int)$cycleNo[1];
                            // $cycleNumber++;
                        }
                        if($cycleNo == $row){
                            unset($dataForSkipPlans[$cycleNoKey]);
                            $class = 'skip-cycle';
                            $cycleNumber--;
                        }
                    @endphp 
                    <div class="{{'card p-3 patient_name '.$class}}">
                        <span>{{isset($dataForSkipReason['1_'.$row]) ? 'Skip Reason : '.$dataForSkipReason['1_'.$row] : ''}}</span>
                        <span>{{isset($dataForSamecycle_value['1_'.$row]) &&  $dataForSamecycle_value['1_'.$row] == true ? 'Transfer : Same Cycle' : ''}}</span>
                        <div class="row">
                            <div class="col-md-12">
                                <a id="patient_name_display" class="ivf-patinent-name" href="{{URL::to('ivf/cycle/'.encrypt($key).'/'.$patientsId.'/'.encrypt(1).'/'.encrypt($row))}}">
                                    <div class="test">
                                        <div class="pt-1 pb-1">
                                            <span>Cycle {{isset($class) && $class == 'skip-cycle' ? '- Skip' : $cycleNumber}}</span>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{URL::to('ivf-plan-report/'.encrypt("1").'/'.$patientsId.'/'.encrypt($row))}}" class="btn btn-sm btn-primary btn-ivf-report">IVF Report</a>
                                <a href="#" class="btn btn-sm btn-primary btn-ivf-report injection-report" data-cycleno="{{$row}}" data-plan="1" data-pid="{{$patientsId}}">Injection Report</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-ivf-report preview-file-btn" data-cycleno="{{$row}}" data-plan="1" data-pid="{{$patientsId}}">View File</a>
                                        
                                
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
        {{--FET  --}}
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 roomtype_name">
                    <h5>FET</h5>
                </div>
            </div>
            @php
                $cycleNumber = 0;
            @endphp
                @foreach($fetCycle as $key=>$row)
                    @php
                        $cycleNumber++;
                        $cycleNoKey = array_search('2',$dataForSkipPlans);
                        $class = null;
                        if($lastPlan == 2 && $lastCycleNo == $row){
                            $class = 'current-cycle';
                            
                            
                        }
                        $cycleNo = explode('_',$cycleNoKey);
                        $cycleNo = array_filter($cycleNo);
                        if(!empty($cycleNo)){
                            $cycleNo = (int)$cycleNo[1];
                            // $cycleNumber++;
                        }
                        if($cycleNo == $row){
                            unset($dataForSkipPlans[$cycleNoKey]);
                            $class = 'skip-cycle';
                            $cycleNumber--;
                        }
                        
                    @endphp
                    <div class="{{'card p-3 patient_name '.$class}}">
                        <span>{{isset($dataForSkipReason['2_'.$row]) ? 'Skip Reason : '.$dataForSkipReason['2_'.$row] : ''}}</span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="test">
                                    <div class="pt-1 pb-1">
                                        <a id="patient_name_display" class="ivf-patinent-name" href="{{URL::to('ivf/cycle/'.encrypt($key).'/'.$patientsId.'/'.encrypt(2).'/'.encrypt($row))}}">
                                            <span>Cycle {{isset($class) && $class == 'skip-cycle' ? '- Skip' : $cycleNumber}}</span>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-ivf-report preview-file-btn" data-cycleno="{{$row}}" data-plan="2" data-pid="{{$patientsId}}">View File</a>
                                        <a href="{{URL::to('ivf-plan-report/'.encrypt("1").'/'.$patientsId.'/'.encrypt($row))}}" class="btn btn-sm btn-primary btn-ivf-report">IVF Report</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>    
        {{-- FET-OD --}}
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 roomtype_name">
                    <h5>FET-OD</h5>
                </div>
            </div>
            @php
                $cycleNumber = 0;
            @endphp
                @foreach($fetOdCycle as $key=>$row)
                    @php
                    $cycleNumber++;
                        $cycleNoKey = array_search('3',$dataForSkipPlans);
                        $class = null;
                        if($lastPlan == 3 && $lastCycleNo == $row){
                            $class = 'current-cycle';
                            
                        }
                        $cycleNo = explode('_',$cycleNoKey);
                        $cycleNo = array_filter($cycleNo);
                        if(!empty($cycleNo)){
                            $cycleNo = (int)$cycleNo[1];
                            // $cycleNumber++;
                        }
                        if($cycleNo == $row){
                            unset($dataForSkipPlans[$cycleNoKey]);
                            $class = 'skip-cycle';
                            $cycleNumber--;
                        }
                        // for display skip reason
                        
                    @endphp
                    <div class="{{'card p-3 patient_name '.$class}}">
                        <span>{{isset($dataForSkipReason['3_'.$row]) ? 'Skip Reason : '.$dataForSkipReason['3_'.$row] : ''}}</span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="test">
                                    <div class="pt-1 pb-1">
                                        <a id="patient_name_display" class="ivf-patinent-name" href="{{URL::to('ivf/cycle/'.encrypt($key).'/'.$patientsId.'/'.encrypt(3).'/'.encrypt($row))}}">
                                            <span>Cycle {{isset($class) && $class == 'skip-cycle' ? '- Skip' : $cycleNumber}}</span>    
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-ivf-report preview-file-btn" data-cycleno="{{$row}}" data-plan="3" data-pid="{{$patientsId}}">View File</a>
                                        <a href="{{URL::to('ivf-plan-report/'.encrypt("1").'/'.$patientsId.'/'.encrypt($row))}}" class="btn btn-sm btn-primary btn-ivf-report">IVF Report</a>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
        </div>    
        {{-- FET-ED	 --}}
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 roomtype_name">
                    <h5>FET-ED</h5>
                </div>
            </div>
            @php
                $cycleNumber = 0;
            @endphp
                @foreach($fetEdCycle as $key=>$row)
                    @php
                        $cycleNumber++;
                        $cycleNoKey = array_search('4',$dataForSkipPlans);
                        $class = null;
                        if($lastPlan == 4 && $lastCycleNo == $row){
                            $class = 'current-cycle';
                        }
                        $cycleNo = explode('_',$cycleNoKey);
                        $cycleNo = array_filter($cycleNo);
                        if(!empty($cycleNo)){
                            $cycleNo = (int)$cycleNo[1];
                            // $cycleNumber++;
                        }
                        if($cycleNo == $row){
                            unset($dataForSkipPlans[$cycleNoKey]);
                            $class = 'skip-cycle';
                            $cycleNumber--;
                        }
                    @endphp
                    <div class="{{'card p-3 patient_name '.$class}}">
                        <span>{{isset($dataForSkipReason['4_'.$row]) ? 'Skip Reason : '.$dataForSkipReason['4_'.$row] : ''}}</span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="test">
                                    <div class="pt-1 pb-1">
                                        <a id="patient_name_display" class="ivf-patinent-name" href="{{URL::to('ivf/cycle/'.encrypt($key).'/'.$patientsId.'/'.encrypt(4).'/'.encrypt($row))}}">
                                            <span>Cycle {{isset($class) && $class == 'skip-cycle' ? '- Skip' : $cycleNumber}}</span>    
                                        </a>
                                        <a href="{{URL::to('ivf-plan-report/'.encrypt("1").'/'.$patientsId.'/'.encrypt($row))}}" class="btn btn-sm btn-primary btn-ivf-report">IVF Report</a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-ivf-report preview-file-btn" data-cycleno="{{$row}}" data-plan="4" data-pid="{{$patientsId}}">View File</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>    
    </div>
@stop
@section('modal')
<div class="modal fade preview-file-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog view-file-modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-bottom-border">

            
            <div class="row">
                <div class="col-md-12">
                    <h5 class="modal-title" id="myModalLabel">IVF History</h5>
                </div>
            </div>
                <button type="button" class="close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            
            <div class="row">
                
            </div>
            <div class="modal-body">
                <div class="ivf-details-data">
                </div>
            </div>

            <div class="modal-footer footer-top-border text-right d-inline-block">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
    <script type="text/javascript">
        var injectionString = '';
        var ivfString = '';
        var ivfPId = "{{$patientsId}}";
        var ivfPlan = '';
        var ivfCycleNo = '';
        $(document).ready(function(){
            
            $(document).on('click','.injection-report',function(e){
                e.preventDefault();
                var cycleNo = $(this).data('cycleno');
                var plan = $(this).data('plan');
                var pId = $(this).data('pid');
                injectionString = 'cycle_no='+cycleNo+'&plan='+plan+'&patient_id='+pId;
                getCycleWiseInjection(injectionString);
            });
            $(document).on('click','.preview-file-btn',function(e){
                e.preventDefault();
                ivfPId = $(this).data('pid');
                ivfCycleNo = $(this).data('cycleno');
                ivfPlan = $(this).data('plan');
                $('.rm-btn').removeClass('d-none');
                $('.print-btn').removeClass('d-none');
                $('.print-fet-report').addClass('d-none');
                $('.preview-file-modal').modal('show');
                ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan;
                
                getIvfHistoryData(ivfString);
            });
        });
        $(document).on('click','.print-btn',function(e){
                e.preventDefault();
                var extraVisit = $(this).data('extravisit');
                var ivfreport = $(this).data('ivfreport');
                var visitDate = $(this).data('date')
                ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&visitDate='+visitDate+'&is_print=1&extraVisit='+extraVisit+'&ivfreport='+ivfreport;
                getIvfHistoryData(ivfString);
                $('.preview-file-modal').modal('hide');
            });
        function getIvfHistoryData(ivfString){
            $.ajax({
                url:'{{URL::to("get-ivf-details")}}?'+ivfString,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                $('.ivf-details-data').html('');
                $('.edit-btn').data('id','');
                if(data.ivf_type == 1){
                    var ivfPreview = $('.ivf-details-data').html();
                    var buttonHtml = '';
                    var previewData = '';
                    $('.edit-btn').data('id',data.enc_ivf_id);
                    // if(typeof data.date != 'undefined'){
                    //     var linkDate = moment(new Date(data.date)).format('YYYY-MM-DD HH:mm:ss');
                    //     var date = moment(new Date(data.date)).format('DD MMMM YYYY');
                    //     $('.ivf-appointment-date').text(date);
                    // }plan
                    $('.ivf-appointment-plan').html(data.plan);
                    $('.ivf-appointment-cycle-no').html(data.cycle);
                    for(i=0; i<data.data.length;i++)
                    {
                        if(typeof data.date[i] != 'undefined'){
                            var linkDate = moment(new Date(data.date[i])).format('YYYY-MM-DD HH:mm:ss');
                            var date = moment(new Date(data.date[i])).format('DD MMMM YYYY');
                        }
                        
                        if(data.extraVisit[i] == 1)
                        {
                            buttonHtml = ivfPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn print-btn btn-sm btn-primary" data-plan="'+data.plan+'" data-cycleno="'+data.cycle+'" data-date="'+linkDate+'" data-extravisit="1" data-ivfreport="">Print</a></div></div>';
                        }
                        if(data.isIvfReport[i] == 1)
                        {
                            buttonHtml = ivfPreview + '<div class="row mb-1"><div class="col-md-3 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div>'+
                            '<div class="col-md-3"><h5 class="modal-title rm-btn" id="myModalLabel">Plan:- <span class="ivf-appointment-plan">'+data.planArray[i]+'</span></h5></div>'+
                            '<div class="col-md-3"><h5 class="modal-title rm-btn" id="myModalLabel">CycleNo:- <span class="ivf-appointment-plan">'+data.cycleArray[i]+'</span></h5></div>'+
                            '<div class="col-md-3 text-right"><a class="btn print-btn btn-sm btn-primary" data-plan="'+data.plan+'" data-cycleno="'+data.cycle+'" data-date="'+linkDate+'" data-extraVisit="" data-ivfreport="1">Print</a></div></div>';
                        }
                        if(data.visitNumber[i] == 1)
                        {
                            buttonHtml = ivfPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div>'+
                            '<div class="col-md-6 text-right"><a class="btn edit-btn btn-sm btn-primary" data-visit="'+data.visitNumber[i]+'" data-id="'+data.enc_ivf_id[i]+'" data-date="'+linkDate+'">Edit</a><a class="btn print-btn btn-sm btn-primary" data-plan="'+data.plan+'" data-cycleno="'+data.cycle+'" data-date="'+linkDate+'" data-extraVisit="" data-ivfreport="">Print</a></div></div>';
                        }
                        if(data.visitNumber[i] != 1 && data.isIvfReport[i] != 1)
                        {
                        buttonHtml = ivfPreview + '<div class="row mb-1"><div class="col-md-3 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div>'+
                        '<div class="col-md-3"><h5 class="modal-title rm-btn" id="myModalLabel">Plan:- <span class="ivf-appointment-plan">'+data.planArray[i]+'</span></h5></div>'+
                        '<div class="col-md-3"><h5 class="modal-title rm-btn" id="myModalLabel">CycleNo:- <span class="ivf-appointment-plan">'+data.cycleArray[i]+'</span></h5></div>'+
                        '<div class="col-md-3 text-right"><a class="btn edit-btn btn-sm btn-primary" data-visit="'+data.visitNumber[i]+'" data-id="'+data.enc_ivf_id[i]+'" data-date="'+linkDate+'">Edit</a><a class="btn print-btn btn-sm btn-primary" data-plan="'+data.plan+'" data-cycleno="'+data.cycle+'" data-date="'+linkDate+'" data-extraVisit="" data-ivfreport="">Print</a></div></div>';

                        }

                        ivfPreview = buttonHtml + data.data[i];
                        $('.ivf-details-data').html(ivfPreview);
                        ivfPreview = ivfPreview + '<div class="row sepreator"></div>';
                    }
                }
                if(data.ivf_type == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function(error){
            });
        }
        function getCycleWiseInjection(injectionString){
            $.ajax({
                url:'{{URL::to("get-injection-details")}}?'+injectionString,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    setTimeout(function () {
                        w.window.print();
                    }, 100);
                }
            }).fail(function(error){
                
            });
        }
    </script>
@stop