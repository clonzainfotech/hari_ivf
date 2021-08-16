@extends('layouts.main')
@section('parentPageTitle', 'Ivf Cycle')
@section('title', 'Cycle')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/fontawesome.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style type="text/css">
        .history-lmp-date{
            color:green !important;
        }
        .dose-border{
            border: 1px solid rgba(0,0,0,0.1) !important;
        }
        .dose-val .items{
            background: #fff !important;
        }
        .dose-width{
            width:220px !important;
        }
        .header-data{
            padding: 0px 21px !important;
            margin-top: 0px !important;
            color: #00cfd1;
        }
        .visit-lable{
            color:#999;
        }
        .visit-lable-value{
            color:black;
        }
        .selectize-control .selectize-input.disabled {
            opacity: 1 !important;
            background-color: #fff !important;

        }
        .selectize-control.multi .selectize-input.disabled [data-value]{
            color: black !important;
        }
        .remove-border{
            border : none !important;
        }
        .w-49{
            width: 49% !important;
        }
        .ivf-panel-title{
            background-color: #e0e0e0 !important;
            border-bottom: 2px solid #eee ;
            color: #3e3a3a !important;
        }
        .plan-picker button{
            width: 175px !important;
        }
        .plan-picker ul{
            width: 175px !important;
            height: 115px !important;
        }
        table{
            font-size: 14px !important;
        }
        .overy-popup{
            cursor: pointer;
        }
        .modal-dialog {
            width: 100%;
            padding: 0;
        }
        .resize-modal-dialog {
            width: 18%;
            padding: 0;
        }
        .unik-table th{
            border:1px solid #dee2e6;
        }
        .unik-table td{
            border:1px solid #dee2e6;
        }
        .unik-header-table td, .unik-header-table th{
            border: none !important;
        }
        .unik-footer-table td, .unik-footer-table th
        {
            border: none;
        }
        .unik-footer-table
        {
            border: 1px solid #dee2e6 !important;
        }
        .unik-header-table th:first-child{
            width: 70%;
        }
        .unik-header-table th:second-child{
            width: 30%;
        }
        .unik-table-border{
            width: 100%;
        }
        .unik-table-border td{
            border: 1px solid #dedede !important;
        }
        @media (min-width: 576px){
            .view-file-modal-dialog {
                max-width: 1800px !important;
                margin: 1.75rem auto;
            }
        }
        .ivf-label{
            font-weight: normal;
        }
        .fet-report-data{
            width: 1500px !important;
        }
        .ml-n5
        {
            margin-left: -5rem !important;
        }
        .frozen-table  tfoot td.frozen_table_footer
        {
            text-align: inherit !important; 
            padding-left:10px !important;
        }
        
        .close
        {
            opacity: 1 !important;
        }
        
    </style>
@stop
@php
    use App\Models\IvfExtraVisit;

    $abArray = ['1'=>"Normal",'2'=>"Abnormal"];
    $wnlArray = ['1'=>"WNL",'2'=>"Abnormal"];
    $investigationArray = ["1"=>'wife','2'=>'hub'];
    $blood_groupArray = ["1"=>'O+ve','2'=>'O-ve','3'=>'A+ve','4'=>'A-ve','5'=>'B+ve','6'=>'B-ve','7'=>'AB+ve','8'=>'AB-ve'];
    $old_dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    $old_medicine_time = ["1"=>"IV","2"=>"IM","3"=>"SC","4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
    $typeOfData = [1=>'Primary',2=>'Secondary'];
@endphp
@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-6">
                            <h2><strong class="text-secondary">{{ucwords($lastAppointment->getPatientsDetails->name)}}</strong>
                                @php
                                    $careof = (!empty($lastAppointment->getPatientsDetails->reference_doctor_id) && isset($referenceDoctor[$lastAppointment->getPatientsDetails->reference_doctor_id])) ? $referenceDoctor[$lastAppointment->getPatientsDetails->reference_doctor_id] : '';
                                @endphp
                                {{' care of '.$careof}}</h2>
                                
                        </div>
                        <div class="col-md-6">
                            {{-- @if($pStatus != 1)
                                <a href="#" class="mb-1">
                                    <button class="btn btn-primary fet-btn pull-right fet-report">FET Report</button>
                                </a>
                            @endif --}}
                            @if($visit == 2 || $isTransfer == true)
                                <a href="{{URL::to('ivf/extra-visit/'.encrypt($patient_id).'/'.encrypt($cycleNumber))}}" class="mb-1 ml-1"><button class="btn btn-primary pull-right">Extra Visit</button></a>
                            @endif
                            <a href="#" class="mb-1">
                                <button class="btn btn-primary pull-right view-file-edit">View File & Edit</button>
                            </a>
                            <a href="{{url('ivf/ivfedit/'.encrypt($patient_id))}}" class="mb-1 ml-1">
                                <button class="btn btn-primary pull-right">Visit-1</button>
                            </a>
                            <a href="{{URL::to('get-all-report/'.encrypt($patient_id).'?status=ivf')}}" class="mb-1 ml-1">
                                <button class="btn btn-primary pull-right">View Reports</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div class="row clearfix">
        @php
            $injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'Clomiphene Citrate + HMG','8'=>'Clomiphene Citrate + FSH','9'=>'Antagonist'];
            $historyLmdDiff = 0;
            $historyLmddateData = null;
            $historyLmddateDate = null;
            $historyLmdDiff = null;
            if($LMPDate){
                $historyLmddateDate = \Carbon\Carbon::parse($LMPDate);
                $now = \Carbon\Carbon::now();
                $historyLmdDiff = $historyLmddateDate->diffInDays($now);
                $historyLmdDiff = $historyLmdDiff + 1;
            }
            $hmgDose = 0;
            $antaDose = 0;
            $fshDose = 0;
            $se2Data = [];
            $sp2Data = [];
            $slhData = [];
            $bloodReport = [];
            $triggerHistoryData = $triggerHistory ? json_decode($triggerHistory->description) : null;
            $hcgTrigger = !empty($triggerHistoryData->trigger->hcg->status) ? $triggerHistoryData->trigger->hcg->status : null;
            $dualTrigger = !empty($triggerHistoryData->trigger->decapeptyl->status) ? $triggerHistoryData->trigger->decapeptyl->status : null;
            $i=0;
            $cycle_no = count($cycle);
        @endphp
        @if($cycle_no>0 && $pStatus == 1)
            @foreach($cycle as $row)
                <div class="card d-none {{'visit-card-'.$row->id}}">
                    <div class="body">
                        <div class="col-md-12">
                            <div class="{{'visit-data-'.$row->id}}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="card pick_up_table">
                <div class="body">
                    {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-form'])}}
                    <div class="row">
                        <div class="col-md-12">
                            <table class='unik-header-table table m-b-0'>
                                <thead>
                                <tr>
                                    <th class="font-15"> <span class="font-bold">Patient Name: </span>{{ucwords($lastAppointment->getPatientsDetails->name)}}</th>
                                    <th class="font-15"><span class="font-bold">Plan: </span> ovarian stimulation</th>
                                </tr>
                                <tr>
                                    <th class="font-15"><span class="font-bold ">Seen By: </span>{{isset($hospitalDoctor[$cycle[0]->seen_by]) ? $hospitalDoctor[$cycle[0]->seen_by] : ''}}</th>
                                    <th class="font-15"><span class="font-bold ">Age: </span>{{ucwords($lastAppointment->getPatientsDetails->age)}}</th>
                                </tr>
                                <tr>
                                    <th class="font-15"><span class="font-bold ">LMP Date: </span>{{\Carbon\Carbon::parse($historyLmddateDate)->format('d-m-Y')}}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <br>
                    @if(($cycle[count($cycle)-1]['cycle_status'] != 2))
                    <div class="row">
                        <div class="col-md-1">
                            <label class="vertical-form-label pr-0">
                                Seen By :
                            </label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::select('seen_by',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
                            </div>
                            <span class="seen-by-error text-danger mb-2"></span>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <table class='unik-table table m-b-0 table-responsive'>
                                <thead class="pick_up_table_thead">
                                <tr>
                                    <th class="text-secondary">Visit Date</th>
                                    <th class="text-secondary">Day of <br> menses</th>
                                    <th class="text-secondary">Simulation<br> Days</th>
                                    <th class="text-secondary">Date</th>
                                    <th class="text-secondary">Injection</th>
                                    <th class="text-secondary">HMG</th>
                                    <th class="text-secondary">HMG Brand Name</th>
                                    <th class="text-secondary">FSH</th>
                                    <th class="text-secondary">FSH Brand Name</th>
                                    <th class="text-secondary">Antagonist</th>
                                    <th class="text-secondary">Rt. Ovary</th>
                                    <th class="text-secondary">Lt. Ovary</th>
                                    <th class="text-secondary">ET</th>
                                    <th class="text-secondary">Remark</th>
                                    <th class="text-secondary">Action</th>
                                </tr>
                                </thead>
                                <tbody class="pick_up_table_tbody">
                                    @foreach($cycle as $row)
                                        @php
                                            $i++;
                                            $datarow = $row;
                                            $skipValue = 0;
                                            $resultValue = 0;

                                                $historyData = json_decode($row->description);
                                                if($row->cycle_status == 2){
                                                    $visit = $row->visit + 1;
                                                }

                                            $data = json_decode($row->description);
                                            if(!empty($data->s_e2)){
                                                $se2Data[] = $data->s_e2;
                                            }
                                            if(!empty($data->s_lh)){
                                                $slhData[] = $data->s_lh;
                                            }
                                            if(!empty($data->s_p2)){
                                                $sp2Data[] = $data->s_p2;
                                            }
                                            if(!empty($data->blood->report)){
                                                $bloodReport[] = $data->blood->report;
                                            }
                                            $duringPickupStatus = !empty($data->during_pickup) ? ucfirst($data->during_pickup) : null;
                                            if((!empty($historyData->plan) || !empty($historyData->follow_up)) && !empty($historyData->skip_reason))
                                            {
                                                $skipValue = 1;
                                            }
                                            //for result table
                                            if(isset($historyData->is_upt) && $historyData->is_upt == 'yes' && !empty($historyData->transfer->upt_type) && !empty($historyData->transfer->result_type))
                                            {
                                                $resultValue = 1;
                                            }
                                        @endphp
                                        @if($row->visit == 2)
                                            @php
                                                $ivfExtraVisit = IvfExtraVisit::where('patient_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','ASC')->get();
                                            @endphp
                                            @if(!empty($ivfExtraVisit))
                                                    @foreach($ivfExtraVisit as $ivfExtra)
                                                    <tr >
                                                        <td>{{\Carbon\Carbon::parse($ivfExtra->created_at)->format('d-m-Y')}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{'Extra Visit'}}</td>
                                                        <td>
                                                            <a href="{{URL::to('ivf/extra-visit/'.encrypt($patient_id).'/'.encrypt($cycleNumber))}}" class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-iui-data" data-id="{{encrypt($row->id)}}">
                                                                <i class="zmdi zmdi-edit material-icons"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                            @endif
                                        @endif
                                        {{-- <div> --}}
                                            @if($historyData->is_transfer == 'no' || $historyData->is_transfer_print == 'no')
                                                @php
                                                    {{$collectionData = !empty($historyData->collection) ? $historyData->collection : [];}}
                                                    {{$dataa = !empty($historyData->collected) ? $historyData->collected : []; }}
                                                @endphp
                                                @if($pStatus == 1)
                                                    @php
                                                        $protocolTable = !empty($historyData->protocol) ? $historyData->protocol : [];
                                                        $countProtocolTable = count((array)$protocolTable);
                                                        if($countProtocolTable > 0){
                                                            $protocolData = (array)$historyData->protocol;
                                                            $injectionArray = array_column($protocolData, 'injection');
                                                            $injectionArray = array_filter($injectionArray);
                                                            $hmgArray = array_column($protocolData, 'hmg');
                                                            $hmgArray = array_filter($hmgArray);
                                                            $hmgBrandArray = array_column($protocolData, 'hmg_brand_name');
                                                            $hmgBrandArray = array_filter($hmgBrandArray);
                                                            $fshArray = array_column($protocolData, 'fsh');
                                                            $fshArray = array_filter($fshArray);
                                                            $antagonistArray = array_column($protocolData, 'antagonist');
                                                            $antagonistArray = array_filter($antagonistArray);
                                                            $fshBrandArray = array_column($protocolData, 'fsh_brand_name');
                                                            $fshBrandArray = array_filter($fshBrandArray);
                                                        }
                                                    @endphp
                                                    @if($countProtocolTable > 0)
                                                        {{-- @if($i===1)
                                                        
                                                        @endif --}}
                                                        @php
                                                            $j=0;
                                                        @endphp
                                                            @foreach ($historyData->protocol as $row)
                                                                @php
                                                                    $j++;
                                                                @endphp
                                                                {{-- @if(!empty($row->hmg) || !empty($row->hmg_brand_name) || !empty($row->fsh) || !empty($row->fsh_brand_name) || !empty($row->antagonist)) --}}
                                                                    <tr>
                                                                        <td>{{($j===1)?\Carbon\Carbon::parse($datarow->created_at)->format('d-m-Y'):''}}</td>
                                                                        <td>{{!empty($row->day) ? $row->day : '-'}}</td>
                                                                        <td>{{!empty($row->s_day) ? 's'.$row->s_day : '-'}}</td>
                                                                        {{-- <td>{{!empty($row->date) ? \Carbon\Carbon::parse($row->date)->format('d/m/Y') : '-'}}</td> --}}
                                                                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y')}}</td>
                                                                        <td>{{!empty($row->injection) ? $injectionData[$row->injection] : '-'}}</td>
                                                                        <td>{{!empty($row->hmg) ? $row->hmg : '-'}}</td>
                                                                        <td>{{!empty($row->hmg_brand_name) ? $row->hmg_brand_name : '-'}}</td>
                                                                        <td>{{!empty($row->fsh) ? $row->fsh : '-'}}</td>
                                                                        <td>{{!empty($row->fsh_brand_name) ? $row->fsh_brand_name : '-'}}</td>
                                                                        <td>{{!empty($row->antagonist) ? $row->antagonist : '-'}}</td>
                                                                        
                                                                        <td>
                                                                            @if($j == 1)
                                                                                @if($datarow->visit == 2)
                                                                                    {{!empty($data->oe->ovary->right->afcs) ? $data->oe->ovary->right->afcs : '-'}}
                                                                                @else
                                                                                    {{!empty($data->ovary->ovary_type->right->details) ? $data->ovary->ovary_type->right->details : '-'}}
                                                                                @endif
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($j == 1)
                                                                                @if($datarow->visit == 2)
                                                                                    {{!empty($data->oe->ovary->left->afcs) ? $data->oe->ovary->left->afcs : '-'}}
                                                                                @else
                                                                                    {{!empty($data->ovary->ovary_type->left->details) ? $data->ovary->ovary_type->left->details : '-'}}
                                                                                @endif
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td>{{$j == 1 && !empty($data->et_details) ? $data->et_details : '-'}}
                                                                            @if (in_array('transfer',$collectionData) && $j == 1)
                                                                            <br>
                                                                                    <a href="javascript:void(0);" id="ivf_transfer_report_update" data-patient-id={{ encrypt($datarow->patients_id)}} data-cycle-no={{ encrypt($datarow->cycle_no)}} data-plan={{ encrypt($datarow->plan)}} data-visit={{ encrypt($datarow->visit)}}>
                                                                                        IVF Transfer Report
                                                                                    </a>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($j == 1)
                                                                                @if(!empty($se2Data))
                                                                                S.E2 : {{implode(',',$se2Data)}}
                                                                                @endif
                                                                                @if(!empty($slhData))
                                                                                <br>
                                                                                S.LH : {{implode(',',$slhData)}}
                                                                                @endif
                                                                                @if(!empty($sp2Data))
                                                                                <br>
                                                                                S.P2 : {{implode(',',$sp2Data)}}
                                                                                @endif
                                                                                {{!empty($historyData->remark) ? $historyData->remark : ''}}
                                                                            @endif
                                                                           
                                                                        </td>
                                                                        <td>
                                                                            @if($j == 1)
                                                                                <a href="#" class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-visit-data" data-id="{{ encrypt($datarow->id) }}">
                                                                                    <i class="zmdi zmdi-delete material-icons"></i>
                                                                                </a>
                                                                                @if(isset($historyData->is_transfer) && ($historyData->is_transfer == 'no' || $historyData->is_transfer_print == 'no') && !in_array('transfer',$collectionData))
                                                                                <a class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-visit-data" data-id="{{encrypt($datarow->id)}}"><i class="zmdi zmdi-edit material-icons"></i></a>
                                                                                @endif
                                                                                @if((isset($historyData->hsa_report->images) && !empty($historyData->hsa_report->images)) || (isset($historyData->blood_report->image) && !empty($historyData->blood_report->image)) || (isset($historyData->usg->images) && !empty($historyData->usg->images)) || (isset($investigationData->hystroscopy->images) && !empty($investigationData->hystroscopy->images)) || (isset($investigationData->laproscopy->images) && !empty($investigationData->laproscopy->images)))
                                                                                    <a href="#" class="btn btn-icon btn-neutral candor-color btn-icon-mini report-btn" data-id="{{ encrypt($datarow->id) }}" data-date="{{\Carbon\Carbon::parse($datarow->created_at)->format('d M Y')}}">
                                                                                        <i class="zmdi zmdi-camera material-icons"></i>
                                                                                    </a>
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        @php
                                                                            $hmgDose += !empty($row->hmg) && is_numeric($row->hmg) ? $row->hmg : 0;
                                                                            $antaDose += !empty($row->antagonist) && is_numeric($row->antagonist) ? $row->antagonist : 0;
                                                                            $fshDose += !empty($row->fsh) && is_numeric($row->fsh) ? $row->fsh : 0;
                                                                        @endphp
                                                                    </tr>
                                                                    
                                                                {{-- @endif --}}
                                                                @php
                                                                    $lastS_day = $row->s_day;
                                                                @endphp
                                                            @endforeach
                                                            @if(!empty($historyData->progesterone->status) && $historyData->progesterone->status == 'yes' && !empty($historyData->progesterone->type))
                                                                    <tr>
                                                                        <td>{{\Carbon\Carbon::parse($datarow->created_at)->format('d-m-Y')}}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>{{'Progesterone Start'}}</td>
                                                                        <td></td>
                                                                    </tr>
                                                                @endif
                                                        {{-- @if($i===$cycle_no) --}}
                                                        
                                                        {{-- @endif --}}
                                                    @endif
                                                @endif
                                            @endif
                                        {{-- </div> --}}
                                    @endforeach
                                    @php
                                        $lastCycleData = json_decode($cycle[count($cycle)-1]['description']);
                                        $nextVisitNo = count($cycle) + 2;
                                        $prevAppointmentDate = !empty($lastCycleData->follow_up) ? \Carbon\Carbon::parse($lastCycleData->follow_up)->format('d-m-Y') : null;
                                        $currentDateDiff = \Carbon\Carbon::parse(!empty($historyLmddateDate) ? $historyLmddateDate : $cycle[count($cycle)-1]['created_at'])->diffInDays(\Carbon\Carbon::parse($prevAppointmentDate));
                                        // $currentDateDiff = \Carbon\Carbon::parse(!empty($historyLmddateDate) ? $historyLmddateDate : $cycle[count($cycle)-1]['created_at'])->diffInDays(\Carbon\Carbon::parse($visitDate));

                                    @endphp
                                    @if(($cycle[count($cycle)-1]['cycle_status'] != 2) && $resultValue == 0)
                                        @php
                                            $left_class_name = 'td-left-overy-'.$prevAppointmentDate.'-text';
                                            $right_class_name = 'td-right-overy-'.$prevAppointmentDate.'-text';
                                        @endphp
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($lastCycleData->follow_up)->format('d-m-Y')}}</td>
                                            <td>{{$currentDateDiff + 1}}</td>
                                            <td>{{!empty($lastS_day) ? 's'.($lastS_day+1) : ''}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <input class="{{$right_class_name.' border-none bg-transparent form-control'}}" name="data[ovary][ovary_type][right][details]" type="text">
                                                <i class="material-icons td-right-overy-{{$prevAppointmentDate}} overy-popup" data-class='{{'td-right-overy-'.$prevAppointmentDate}}'>keyboard</i>
                                            </td>
                                            <td>
                                                <input class="{{$left_class_name.' border-none bg-transparent form-control'}}" name="data[ovary][ovary_type][left][details]" type="text">
                                                    <i class="material-icons td-left-overy-{{$prevAppointmentDate}} overy-popup" data-class='{{'td-left-overy-'.$prevAppointmentDate}}'>keyboard</i>
                                            </td>
                                            <td>{{Form::text("data[et_details]",'',['class'=>'form-control  border-none bg-transparent','placeholder'=>'Enter ET Details'])}}</td>
                                            <td>
                                                {{Form::textarea("data[remark]",'',['class'=>'form-control no-resize remark  border-none bg-transparent','placeholder'=>'Remark','rows'=>'2'])}}
                                                <span class="transfer-error text-danger mb-2"></span>
                                            
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    
                                    @if(($cycle[count($cycle)-1]['cycle_status'] != 2) && $resultValue == 0)
                                    <tfoot>
                                        <tr>
                                            <td colspan="15">
                                                {{Form::hidden('visit',$visit,['class'=>'visit-no'])}}
                                                {{Form::hidden('plan_type',$pStatus,['class'=>'plan_type'])}}
                                                {{Form::hidden('cycle_no',$cycleNumber)}}
                                                {{Form::hidden('cycle_no_data',encrypt($cycleNumber),['class'=>'cycle-no-data'])}}
                                                {{Form::hidden('pickup_pln',encrypt(1),['class'=>'pickup-plan'])}}
                                                {{Form::hidden('patients_id',$patientsId,['class'=>'patients-id'])}}
                                                {{Form::hidden('last_s_days',$sDay,['class'=>'last-s-days'])}}
                                                {{Form::hidden('last_protocol_date',$pDate,['class'=>'last-protocol-date'])}}
                                                @if(!$isTransfer)
                                                    {{Form::hidden('data[is_transfer]','no',['class'=>'is-transfer'])}}
                                                    {{Form::hidden('data[is_transfer_print]','no')}}
                                                   
                                                    {{Form::hidden("data[lmp][date]",!empty($historyLmddateDate) ? \Carbon\Carbon::parse($historyLmddateDate)->format('D d M Y') : null ,['class'=>'form-control history-lmd-date','autocomplete'=>'off'])}}
                                                    
                                                    {{Form::hidden("data[lmp][lmp_date_diff]",$historyLmdDiff,['class'=>'form-control history-lmd-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                                    {{Form::hidden('appointment_date',$lastAppointment->date,['class'=>'last-appointment-date'])}}
                                                    <div class="row mt-3 mb-3">
                                                        <div class="col-md-1 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                S.E2 :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{Form::text("data[s_e2]",'',['class'=>'form-control','placeholder'=>'S.E2'])}}
                                                        </div>
                                                        <div class="col-md-1 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                S.LH :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{Form::text("data[s_lh]",'',['class'=>'form-control','placeholder'=>'S.LH'])}}
                                                        </div>
                                                        <div class="col-md-1 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                S.P2 :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{Form::text("data[s_p2]",'',['class'=>'form-control','placeholder'=>'S.P2'])}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="vertical-form-label pr-0">
                                                            Hystroscopy During Pickup :
                                                        </label>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("data[during_pickup]",'yes','',['id'=>'during_pickup_yes','class'=>'during-pickup'])}}
                                                                <label for="during_pickup_yes">
                                                                    Yes
                                                                </label>
            
                                                                {{Form::radio("data[during_pickup]",'no','',['id'=>'during_pickup_no','class'=>'during-pickup'])}}
                                                                <label for="during_pickup_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-1 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                Hystroscopy :
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("investigation[hystroscopy][type]",'yes','',['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                                <label for="hystroscopy_type_yes">
                                                                    Yes
                                                                </label>
                                                                {{Form::radio("investigation[hystroscopy][type]",'no','',['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                                <label for="hystroscopy_type_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 hystroscopy-type d-none">
                                                            <div class="hystroscopy-images"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-1 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                Laproscopy :
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("investigation[laproscopy][type]",'yes','',['id'=>'laproscopy_type_yes','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                                                                <label for="laproscopy_type_yes">
                                                                    Yes
                                                                </label>
                                                                {{Form::radio("investigation[laproscopy][type]",'no','',['id'=>'laproscopy_type_no','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                                                                <label for="laproscopy_type_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 laproscopy-type d-none">
                                                            <div class="laproscopy-images"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    {{Form::hidden('is_trigger','yes')}}
                                                    @php
                                                        $collectionEmbroyValueData = !empty($historyData->collected->report) && !empty($historyData->collected->report->embroy->type) && $historyData->collected->report->embroy->type == 'yes' ? false : true;
                                                    @endphp
                                                    @if($collectionEmbroyValueData)
                                                        
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-5">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Reason : &nbsp;</span>
                                                                    {{Form::text('reason',!empty($ivfReport->reason) ? $ivfReport->reason : null ,['class'=>'form-control reason'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Date : &nbsp;</span>
                                                                    {{Form::text("report_date",\Carbon\Carbon::parse($lastAppointment->date)->format('D d M Y'),['class'=>'form-control datetimepicker report_date','required'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('report_date')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Volume : &nbsp;</span>
                                                                    {{Form::number('volume[pre]',!empty($volume->pre) ? $volume->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('volume[post]',!empty($volume->post) ? $volume->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Sperm Count/ml : &nbsp;</span>
                                                                    {{Form::number('sperm[pre]',!empty($sperm_count->pre) ? $sperm_count->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('sperm[post]',!empty($sperm_count->post) ? $sperm_count->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Total Motility (%): &nbsp;</span>
                                                                    {{Form::number('motility[pre]',!empty($total_motility->pre) ? $total_motility->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('motility[post]',!empty($total_motility->post) ? $total_motility->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Actively Motile (%) : &nbsp;</span>
                                                                    {{Form::number('actively[pre]',!empty($actively->pre) ? $actively->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('actively[post]',!empty($actively->post) ? $actively->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Sliggishly Motile (%) : &nbsp;</span>
                                                                    {{Form::number('sluggishly[pre]',!empty($sluggishly->pre) ? $sluggishly->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('sluggishly[post]',!empty($sluggishly->post) ? $sluggishly->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Non-motile (%) : &nbsp;</span>
                                                                    {{Form::number('motile[pre]',!empty($non_motile->pre) ? $non_motile->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('motile[post]',!empty($non_motile->post) ? $non_motile->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Normal Morphology : &nbsp;</span>
                                                                    {{Form::number('morphology[pre]',!empty($morphology->pre) ? $morphology->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('morphology[post]',!empty($morphology->post) ? $morphology->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row embroy-button d-none">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon col-md-2">Pus cells/hpf : &nbsp;</span>
                                                                    {{Form::number('cells[pre]',!empty($pus_cells->pre) ? $pus_cells->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                                    {{Form::number('cells[post]',!empty($pus_cells->post) ? $pus_cells->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('name')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{Form::hidden('data[collection][]','collected')}}
                                                        {{Form::hidden('data[collected][frozen][type]',!empty($historyData->collected->frozen->report) ? $historyData->collected->frozen->report : null)}}
                                                        {{Form::hidden('data[collected][date]',!empty($historyData->collected->date) ? $historyData->collected->date : null)}}
                                                        {{Form::hidden('data[collected][report][embroy][status]',!empty($historyData->collected->report->embroy->status) ? $historyData->collected->report->embroy->status : null)}}
                                                        {{Form::hidden('data[collected][report][embroy][type]',!empty($historyData->collected->report->embroy->type) ? $historyData->collected->report->embroy->type : null)}}
                                                    @endif
                                                    <div class="row mt-1">
                                                        <div class="col-md-4">
                                                            <div class="checkbox">
                                                                {{Form::checkbox('data[collection][]','progesterone','',['id'=>'progesterone'])}}
                                                                <label for="progesterone">
                                                                    Progesterone supplementation?
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if($pStatus == 1)
                                                            <div class="col-md-2 progesterone_data d-none">
                                                                <label for="progesterone">
                                                                    Same Cycle Transfer?
                                                                </label>
                                                            </div>
                                                            <div class="col-md-2 progesterone_data d-none">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("data[progesterone][status]",'yes','',['id'=>'progesterone_yes'])}}
                                                                    <label for="progesterone_yes">
                                                                        Yes
                                                                    </label>
                                                                    {{Form::radio("data[progesterone][status]",'no','',['id'=>'progesterone_no'])}}
                                                                    <label for="progesterone_no">
                                                                    No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @php
                                                            $class = 'progesterone_data';
                                                            if($pStatus == 1){
                                                                $class = 'progesterone_yes';
                                                            }
                                                        @endphp
                                                        <div class="{{'col-md-2 d-none '.$class}}">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("data[progesterone][type]",'day_3','',['id'=>'day_3','class'=>'progesterone-type'])}}
                                                                <label for="day_3">
                                                                    Day-3
                                                                </label>
                                                                {{Form::radio("data[progesterone][type]",'day_5','',['id'=>'day_5','class'=>'progesterone-type'])}}
                                                                <label for="day_5">
                                                                    Day-5
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <div class="checkbox">
                                                                {{Form::checkbox('data[collection][]','transfer','',['id'=>'transfer','class'=>'transfer'])}}
                                                                <label for="transfer">
                                                                    Transfer
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-4 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Indication: &nbsp;</span>
                                                                {{Form::text("indication",'',[
                                                                    'class'=>'form-control',
                                                                    'maxlength' => 250
                                                                ])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">ET Date: &nbsp;</span>
                                                                {{Form::text("et_date", \Carbon\Carbon::now()->addDays(1)->format('D d M Y'), ['class'=>'form-control datetimepicker'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Day: &nbsp;</span>
                                                                {{Form::text("day",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-4 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Endo. Thickness: &nbsp;</span>
                                                                {{Form::text("endo_thickness",'',[
                                                                    'class'=>'form-control',
                                                                    'maxlength' => 250
                                                                ])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">ET Procedure: &nbsp;</span>
                                                                {{Form::text("et_procedure",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Frozen Embryos: &nbsp;</span>
                                                                {{Form::text("frozen_embryos",'',[
                                                                    'class'=>'form-control',
                                                                    'maxlength' => 250
                                                                ])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-3 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Embryos Transferred: &nbsp;</span>
                                                                {{Form::text("embryos_transferred",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 transfer-data d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Embryos Transferred Image: &nbsp;</span>
                                                                {{Form::file('embryos_transferred_image',['class'=>'form-control embryos_transferred_image'])}}
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    {{Form::hidden("data[trigger][update_status]",'no')}}
                                                    <div class="row mt-1">
                                                        <div class="col-md-2">
                                                            <div class="checkbox">
                                                                {{Form::checkbox('data[collection][]','trigger','',['id'=>'trigger'])}}
                                                                <label for="trigger">
                                                                    Trigger
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 trigger d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Trigger Date: &nbsp;</span>
                                                                {{Form::text("data[trigger_date]",'', ['class'=>'form-control history-lmd-date'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="trigger d-none ml-3">
                                                        <div class="row mt-1">
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[trigger][hcg][status]','hcg','',['id'=>'hcg'])}}
                                                                    <label for="hcg">
                                                                        HCG
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="hcgtrigger d-none">
                                                                <div class="row ml-3">
                                                                    <div class="col-sm-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Time : &nbsp;</span>
                                                                            {{Form::text("data[trigger][hcg][time]",'',['class'=>'form-control timepicker time','id'=>'hcg_time','placeholsder'=>'Brand'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Dose : &nbsp;</span>
                                                                            {{Form::text("data[trigger][hcg][dose]",'10000',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Brand : &nbsp;</span>
                                                                            {{Form::text("data[trigger][hcg][brand]",'',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[trigger][decapeptyl][status]','decapeptyl','',['id'=>'decapeptyl'])}}
                                                                    <label for="decapeptyl">
                                                                        Decapeptyl
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="decapeptyltrigger d-none">
                                                                <div class="row ml-3">
                                                                    <div class="col-sm-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Time : &nbsp;</span>
                                                                                {{Form::text("data[trigger][decapeptyl][time]",'',['class'=>'form-control timepicker time','placeholsder'=>'Brand'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Dose : &nbsp;</span>
                                                                        {{Form::text("data[trigger][decapeptyl][dose]",'1,2',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Brand : &nbsp;</span>
                                                                        {{Form::text("data[trigger][decapeptyl][brand]",'',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[trigger][dualtrigger][stauts]','dualtrigger','',['id'=>'dualtrigger'])}}
                                                                    <label for="dualtrigger">
                                                                        Dule Trigger
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-2">
                                                            <div class="checkbox">
                                                                {{Form::checkbox('data[collection][]','blood','',['id'=>'blood'])}}
                                                                <label for="blood">
                                                                Blood Report
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 bloodreport d-none">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Blood report: &nbsp;</span>
                                                                        {{Form::text("data[blood_report][report]",'',['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                                    <div class="blood-images"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-2">
                                                            <div class="checkbox">
                                                                {{Form::checkbox('data[collection][]','hsa','',['id'=>'hsa'])}}
                                                                <label for="blood">
                                                                HSA Report
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 hsareport d-none">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">HSA report: &nbsp;</span>
                                                                        {{Form::text("data[hsa_report][report]",'',['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                                    <div class="hsa-images"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-md-2">
                                                            <div class="checkbox">
                                                                {{Form::checkbox('data[collection][]','usg','',['id'=>'usg'])}}
                                                                <label for="usg">
                                                                    USG Report
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 usgreport d-none">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">USG report: &nbsp;</span>
                                                                        {{Form::text("data[usg][report]",'',['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                                    <div class="usg-images"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <a class="btn btn-primary btn-icon btn-icon-mini btn-round add-row d-none" data-id="5" data-day="0"><i class="material-icons">add</i></a>
                                                        {{-- table append for protocol --}}
                                                        <div class="protocol-table"></div>
                                                        <br>
                                                    <br>
                                                    <div class="row">
                                                        {{-- <div class="col-md-12"> --}}
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Follow Up: &nbsp;</span>
                                                                            @if($pStatus != 1)
                                                                                {{Form::text("data[follow_up]",\Carbon\Carbon::now()->addDays(1)->format('D d M Y'),['class'=>'form-control tranfer-follow-date datetimepicker lmp-date-follow-up next-date'])}}
                                                                            @else
                                                                                {{Form::text("data[follow_up]",'',['class'=>'form-control datetimepicker tranfer-follow-date follow-up-date next-date'])}}
                                                                            @endif
                                                                            {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            {{Form::text("followUp-dateDiff",'',['class'=>'form-control history-follow-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 plan-transfer-data">
                                                                        <div class="form-group">
                                                                            {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan-transfer','placeholder'=>'Select Plan Transfer'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2 plan-transfer-data plan-transfer-data-type d-none">
                                                                        <div class="radio is-conceived">
                                                                            {{Form::radio("data[transfer_type]",'new',true,['id'=>'transfer-type-new','class'=>'upt-type'])}}
                                                                            <label for="transfer-type-new">
                                                                                NEW
                                                                            </label>
        
                                                                            {{Form::radio("data[transfer_type]",'old','',['id'=>'transfer-type-old','class'=>'upt-type'])}}
                                                                            <label for="transfer-type-old">
                                                                                OLD
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <br>
                                                            <div class="row skip-data">
                                                                <div class="col-md-6">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('data[skip_cycle]','yes','',['class'=>'skip-cycle','id'=>'skip-pick-up','data-id'=>'skip-cycle-data','data-plan="pick-up"'])}}
                                                                        <label for="skip-pick-up" class="text-danger">
                                                                            Do you want to skip this cycle?
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 skip-cycle-data d-none">
                                                                    <div class="form-group">
                                                                        {{Form::text("data[skip_reason]",'',['class'=>'form-control skip-reason','placeholder'=>'Enter Reason'])}}
                                                                    </div>
                                                                    <span class="skip-reason-error form-error-msg"></span>
                                                                </div>
                                                                <div class="col-md-3 skip-cycle-data d-none">
                                                                    <div class="form-group">
                                                                        {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan skip-plan','placeholder'=>'Select Plan'])}}
                                                                    </div>
                                                                    <span class="skip-plan-error form-error-msg"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        {{Form::textarea("data[pt_remark]",'',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'2'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-4 float-right">
                                                                <table class='unik-footer-table table m-b-0 float-right' border='1'>
                                                                    <tbody>
                                                                    <tr>
                                                                        @if(!empty($triggerHistoryData))
                                                                        <td style="">
                                                                            <table class="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="font-bold">Trigger : </td>
                                                                                        <td style="" >{{$hcgTrigger.(!empty($hcgTrigger) ? '+' : '').$dualTrigger}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Date & Time : </td>
                                                                                        <td>
                                                                                            @if($triggerHistoryData)
                                                                                                {{$triggerHistory ? (\Carbon\Carbon::parse($triggerHistory->trigger_date)->format('D d M Y')) : ''}} {{!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null)}}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="font-bold">Ovum Pick Up:</td>
                                                                                        <td>
                                                                                            @if($triggerHistoryData)
                                                                                                @php
                                                                                                    $nowDate = \Carbon\Carbon::parse($triggerHistory->trigger_date)->format('Y-m-d');
                                                                                                    $nowTime = \Carbon\Carbon::parse(!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null))->format('H:i:s');
                                                                                                    $triggerDateTime = \Carbon\Carbon::parse($nowDate.' '.$nowTime)->addHours(35)->format('Y-m-d H:i:s');
                                                                                                    $triggerDate = \Carbon\Carbon::parse($triggerDateTime)->format('D d M Y');
                                                                                                @endphp
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Date & Time</td>
                                                                                        <td>
                                                                                            @if($triggerHistoryData)
                                                                                                {{$triggerDate.' '.\Carbon\Carbon::parse($triggerDateTime)->format('h:i a')}}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="font-bold">Hystroscopy : </td>
                                                                                        <td>
                                                                                            @if(!empty($duringPickupStatus))
                                                                                                {{$duringPickupStatus}}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        @endif
                                                                        <td class="border-left">
                                                                            <table class=''>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="">
                                                                                        <table class="">
                                                                                            <tbody>
                                                                                            <tr>
                                                                                                <td>Total HMG dose:</td>
                                                                                                <td class="font-bold">{{$hmgDose}}</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>Total Anta dose:</td>
                                                                                                <td class="font-bold">{{$antaDose}}</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>Total FSH dose:</td>
                                                                                                <td class="font-bold">{{$fshDose}}</td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        {{-- </div> --}}
                                                    </div>
                                                    
                                                    {{Form::hidden("data[is_upt]",'no')}}
                                                {{-- @endif --}}
                                                @else
                                                    {{Form::hidden("data[is_upt]",'yes')}}
                                                    {{Form::hidden('data[is_transfer]','yes',['class'=>'is-transfer'])}}
                                                    {{Form::hidden('data[is_transfer_print]','yes')}}
                                                    <div class="row">
                                                        {{-- upt --}}
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                UPT :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("data[transfer][upt_type]",'positive','',['id'=>'transfer-positive','class'=>'upt-type'])}}
                                                                <label for="transfer-positive">
                                                                    Positive
                                                                </label>

                                                                {{Form::radio("data[transfer][upt_type]",'negative','',['id'=>'transfer-negative','class'=>'upt-type'])}}
                                                                <label for="transfer-negative">
                                                                    Negative
                                                                </label>
                                                            </div>
                                                        </div>
                                                        {{-- result --}}
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                Result :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("data[transfer][result_type]",'conceive','',['id'=>'transfer-conceive','class'=>'result-type'])}}
                                                                <label for="transfer-conceive">
                                                                    Conceive
                                                                </label>

                                                                {{Form::radio("data[transfer][result_type]",'fail','',['id'=>'transfer-fail','class'=>'result-type'])}}
                                                                <label for="transfer-fail">
                                                                    Fail
                                                                </label>
                                                            </div>
                                                        </div>
                                                        {{-- end result --}}
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                Report :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{Form::file('data[transfer][report][]',['class'=>'form-control',"multiple"=>"multiple"])}}
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Follow Up: &nbsp;</span>
                                                                {{Form::text("data[transfer][follow_up]",\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'form-control tf-date datetimepicker'])}}
                                                            </div>
                                                            {{Form::hidden('data[follow_up]',\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'t-follow-date'])}}
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-3 plan-transfer-data">
                                                            <div class="form-group">
                                                                {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan-transfer','placeholder'=>'Select Plan Transfer'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 plan-transfer-data plan-transfer-data-type d-none">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("data[transfer_type]",'new',true,['id'=>'transfer-type-new','class'=>'upt-type'])}}
                                                                <label for="transfer-type-new">
                                                                    NEW
                                                                </label>

                                                                {{Form::radio("data[transfer_type]",'old','',['id'=>'transfer-type-old','class'=>'upt-type'])}}
                                                                <label for="transfer-type-old">
                                                                    OLD
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                C/O :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-9 complain-multi">
                                                            {{Form::select('data[co_type][]',$complaints,'',['class'=>'form-control co-value co_value_data complaint-data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'1'])}}
                                                        </div>
                                                    </div>
                                                    <br>
                                                    {{-- <div id="treatment" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                                        <div class="panel-body" id="parent">
                                                            <div class="row treatment-data" id="t_data_1">
                                                                <div class="col-md-2 pr-0">
                                                                    <label class="vertical-form-label pr-0">
                                                                        Select Medicine :
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-9 complain-multi medicine-picker">
                                                                    {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','placeholder' =>'Enter medicine name'])}}
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="page-loader-wrapper medicine-loader d-none">
                                                                <div class="loader">
                                                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                                                </div>
                                                            </div>
                                                            <div class="medicine-data treatment-medicine-data">

                                                            </div>
                                                            {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                                                        </div>
                                                    </div> --}}
                                                    <br>
                                                @endif
                                                
                                                
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                            </table>
                        </div>
                        @if(!empty($lastCycleData->plan) && $cycle[count($cycle)-1]['cycle_status'] == 2)
                        <div class="col-md-12 mt-2">
                            <span class="font-bold font-16">Transfer Plan :- </span> 
                            <span class="visit-lable-value">{{isset($planData[$lastCycleData->plan])? $planData[$lastCycleData->plan] : ''}}</span>
                        </div>
                        @endif
                        <div class="col-md-12">
                        @if($cycle[count($cycle)-1]['cycle_status'] == 2)
                            <div class="col-md-6"></div>
                            <div class="col-md-6 float-right">
                                <table class='table table-responsive'>
                                    <tbody>
                                    <tr>
                                        @if(!empty($triggerHistoryData))
                                        <td >
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="font-bold border-none">Trigger : </td>
                                                        <td class="border-none">{{$hcgTrigger.(!empty($hcgTrigger) ? '+' : '').$dualTrigger}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date & Time : </td>
                                                        <td>
                                                            @if($triggerHistoryData)
                                                                {{$triggerHistory ? (\Carbon\Carbon::parse($triggerHistory->trigger_date)->format('D d M Y')) : ''}} {{!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null)}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-bold">Ovum Pick Up:</td>
                                                        <td>
                                                            @if($triggerHistoryData)
                                                                @php
                                                                    $nowDate = \Carbon\Carbon::parse($triggerHistory->trigger_date)->format('Y-m-d');
                                                                    $nowTime = \Carbon\Carbon::parse(!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null))->format('H:i:s');
                                                                    $triggerDateTime = \Carbon\Carbon::parse($nowDate.' '.$nowTime)->addHours(35)->format('Y-m-d H:i:s');
                                                                    $triggerDate = \Carbon\Carbon::parse($triggerDateTime)->format('D d M Y');
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date & Time</td>
                                                        <td>
                                                            @if($triggerHistoryData)
                                                                {{$triggerDate.' '.\Carbon\Carbon::parse($triggerDateTime)->format('h:i a')}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-bold">Hystroscopy : </td>
                                                        <td>
                                                            @if(!empty($duringPickupStatus))
                                                                {{$duringPickupStatus}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        @endif
                                        <td class="border-left">
                                        
                                            <table class="">
                                                <tbody>
                                                <tr>
                                                    <td class="border-none">Total HMG dose:</td>
                                                    <td class="font-bold border-none">{{$hmgDose}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Anta dose:</td>
                                                    <td class="font-bold">{{$antaDose}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total FSH dose:</td>
                                                    <td class="font-bold">{{$fshDose}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        
                        @endif
                        </div>
                        @if($skipValue == 1) {{-- skip cycle --}}
                            @php
                                    $visitDate = \Carbon\Carbon::parse($cycle[count($cycle)-1]['created_at'])->format('d-m-Y');
                                    $diff = \Carbon\Carbon::parse(!empty($historyLmddateDate) ? $historyLmddateDate : $cycle[count($cycle)-1]['created_at'])->diffInDays(\Carbon\Carbon::parse($visitDate));
                                    $diff = $diff + 1;
                            @endphp
                            <div class="col-md-12">
                                <h5 class=""><u>Skip Cycle:</u></h5>
                                <table class="table follicular-table frozen-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Follow UP</th>
                                            <th>Transfer Plan</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$visitDate}}</td>
                                            <td>{{$planData[$lastCycleData->plan]}}</td>
                                            <td>{{$lastCycleData->skip_reason}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if(!empty($lastCycleData->transfer->result_type))
                            @php
                                $visitDate = \Carbon\Carbon::parse($cycle[count($cycle)-1]['created_at'])->format('d-m-Y');
                                $diff = \Carbon\Carbon::parse(!empty($historyLmddateDate) ? $historyLmddateDate : $cycle[count($cycle)-1]['created_at'])->diffInDays(\Carbon\Carbon::parse($visitDate));
                                $diff = $diff + 1;
                            @endphp
                            <div class="col-md-12">
                                <h5 class=""><u>Result:</u></h5>
                                <table class="table follicular-table frozen-table table-bordered ">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>UPT</th>
                                            <th>Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$visitDate}}</td>
                                            <td>{{$lastCycleData->transfer->upt_type}}</td>
                                            <td>{{$lastCycleData->transfer->result_type}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <h5>Medicine:</h5>
                            <table class="unik-table table">
                                <thead class="pick_up_table_thead">
                                    <tr>
                                        <th class="text-secondary"> Date</th>
                                        <th class="text-secondary"> Medicine</th>
                                    </tr>
                                </thead>
                                <tbody class="pick_up_table_tbody">
                                    @foreach($cycle as $row)
                                    @php
                                        $data = json_decode($row->description);
                                        $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                        $historyTreatmentView = null;
                                        if(!empty($data->medicinedata)){
                                            $historyTreatmentView = !empty($data->medicinedata) ? $data->medicinedata : null;
                                        }
                                    @endphp
                                    @if(!empty($historyTreatmentView))
                                    <tr>
                                        <td class="">{{$visitDate}}</td>
                                        <td style="text-align: justify!important">
                                            
                                            @if($historyTreatmentView)
                                                @php
                                                    unset($historyTreatmentView->medicinedata);
                                                @endphp
                                                @foreach($historyTreatmentView as $key=>$row)
                                                @php
                                                
                                                    $firstCharacter = substr($row->medicine, 0, 3);
                                                    $notinject = "";
                                                    if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                                        $notinject = "is-inj";
                                                    } 
                                                @endphp
                                                <div class="mb-1">
                                                    <span class="font-bold"> Medicine : &nbsp</span>
                                                        {{ $row->medicine }}
                                                        @if($notinject != "is-inj")
                                                            | @switch($row->medicine_status)
                                                                    @case('1')
                                                                        જમ્યા પછી
                                                                        @break
                                                                    @case('2')
                                                                        જમ્યા પહેલાં
                                                                        @break
                                                                    @case('3')
                                                                        માસિકની જગ્યાએ મુકવી
                                                                    @break
                                                            @endswitch
                                                        @endif
                                                    @if(!empty($row->dose))
                                                        @if (array_key_exists($row->dose, $old_dose))
                                                            | {{ $old_dose[$row->dose] }}
                                                        @endif
                                                    @endif
                                                    @if (!empty($row->no)) | Days : {{ $row->no }} @endif
                                                    @if($notinject != "is-inj")
                                                    @php
                                                        $qty = (!empty($row->quantity)) ? $row->quantity : 0;
                                                        $qty_2 = (!empty($row->quantity_2)) ? $row->quantity_2 : 0;
                                                        $qty_3 = (!empty($row->quantity_3)) ? $row->quantity_3 : 0;
                                                        $qty_4 = (!empty($row->quantity_4)) ? $row->quantity_4 : 0;
                                                    @endphp | Quantity : {{$qty.'-'.$qty_2.'-'.$qty_3.'-'.$qty_4}}
                                                    @endif
                                                    @if($notinject == "is-inj")
                                                        @if (!empty($row->medicine_time))
                                                        
                                                            @if(is_array($row->medicine_time))
                                                                @foreach ($row->medicine_time as $time)
                                                                |{{$old_medicine_time[$time]}}
                                                                @endforeach
                                                            @else
                                                            |{{$old_medicine_time[$row->medicine_time]}}
                                                            @endif

                                                        @endif
                                                    @endif
                                                </div>
                                                    <br>
                                                @endforeach
                                                
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                                
                                @if(($cycle[count($cycle)-1]['cycle_status'] != 2))
                                    <tfoot>
                                        <td colspan="8">
                                            <div id="treatment" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                                <div class="panel-body" id="parent">
                                                    <div class="row treatment-data" id="t_data_1">
                                                        <div class="col-md-2 pr-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Select Medicine :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-9 complain-multi medicine-picker">
                                                            {{Form::select("data[treatment][medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','placeholder' =>'Enter medicine name'])}}
                                                        </div>
                                                    </div>
                                                    <div class="page-loader-wrapper medicine-loader d-none">
                                                        <div class="loader">
                                                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                                        </div>
                                                    </div>
                                                    <div class="treatment-medicine-data mt-1">
        
                                                    </div>
                                                    {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                                                </div>
                                            </div>
                                            
                                            @if(!empty($historyTreatment))
                                                @foreach($historyTreatment as $key=>$row)
                                                {{-- @if(isset($medicines[$row->medicine])) --}}
                                                    <?php
                                                    $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                    $firstCharacter = substr($mId, 0, 3);
                                                    $notinject = "";
                                                    if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                                        $notinject = "is-inj";
                                                        $dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day",'7'=>"6 hourly",'8'=>"8 hourly",'9'=>"12 hourly",'10'=>"24 hourly"];
    
                                                    }
                                                    $till_follow_up = (empty($row->no)) ? 'till-follow-up' : '';
                                                    ?>
                                                    <div class="{{'row mt-2 '.$notinject}}" data-id="{{$mId}}">
                                                        <div class='col-md-2'>
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>M : </span>
                                                                {{Form::text('data[treatment]['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-1 notinject'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-1 notinject'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-1 notinject'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-1 notinject'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-2 notinject'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-2 isinject'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-2'>
                                                            <div class='form-group'>
                                                                {{Form::select('data[treatment]['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-1'>
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Day :</span>
                                                                {{Form::number('data[treatment]['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-1 medicine-data-remove'>
                                                            <span class=""><i class="material-icons">close</i></span>
                                                        </div>
                                                    </div>
                                                    {{-- @endif --}}
                                                @endforeach
                                            @endif
                                            
                                            <div class="medicine-data"></div>
                                            {{-- {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}} --}}
                                        </td>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    @if(($cycle[count($cycle)-1]['cycle_status'] != 2))
                        {{Form::hidden('ivf_report',!empty($ivfReport) ? $ivfReport : null,['class'=>'ivf-report-status'])}}
                        {{Form::hidden('ivf_history_id', '' , ['id' => 'ivf_history_id'])}}
                        {{Form::button('submit',['class'=>'btn btn-primary submit'])}}
                        {{Form::hidden('ivf_transfer_report_id', '' , ['id' => 'ivf_transfer_report_id'])}}
                        <button type="submit" class="btn btn-primary submit" value="1">Save & Preview</button>
                        <button type="submit" class="btn btn-primary submit transfer-report d-none" value="5">Transfer Report Preview</button>
                        <a class="btn btn-primary t-print transfer-print d-none" data-id="">Transfer Print</a>
                        <button type="submit" class="btn btn-primary submit d-none" value="3" id="ivf_print"> Print IVF</button>
                        <button type="submit" class="btn btn-primary submit d-none" value="4" id="ivf_report_print"> Save & Print IVF Report</button>
                        <a href="{{URL::previous()}}" class="btn btn-default">Cancel</a>
                        {{Form::close()}}
                    @endif
                </div>
            </div>
        
        @else
            @if((!empty($cycleNumber) || ($plan == $pStatus) || ($cycleStatus == 2)) && ($pStatus != 1) && ($nextVisitValue >= 2))
            
                @foreach($cycle as $row)
                    <div class="card d-none {{'visit-card-'.$row->id}}">
                        <div class="body">
                            <div class="col-md-12">
                                <div class="{{'visit-data-'.$row->id}}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="card frozen-table">
                    <div class="body">
                        <div class="col-md-12">
                            {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-form'])}}
                            {{Form::hidden('visit',$visit,['class'=>'visit-no'])}}
                            {{Form::hidden('plan_type',$pStatus,['class'=>'plan_type'])}}
                            {{Form::hidden('cycle_no',$cycleNumber)}}
                            {{Form::hidden('cycle_no_data',encrypt($cycleNumber),['class'=>'cycle-no-data'])}}
                            {{Form::hidden('pickup_pln',encrypt(1),['class'=>'pickup-plan'])}}
                            {{Form::hidden('patients_id',$patientsId,['class'=>'patients-id'])}}
                            {{Form::hidden('last_s_days',$sDay,['class'=>'last-s-days'])}}
                            {{Form::hidden('last_protocol_date',$pDate,['class'=>'last-protocol-date'])}}
                            <div class="follicular_table_print">
                                <div class="row">{{--mb-15 is used in print--}}
                                    <div class="col-md-12" style="text-align:center"><h4><u><b> FROZEN EMBRYO TRANSFER STUDY</b></u></h4></div>
                                </div>
                                <div class="row follicular-table mb-15 mb-5">{{--mb-15 is used in print--}}
                                    <div class="col-md-6 follicular_div_1">
                                        <div class="mb-2">
                                            <span class="visit-lable">Name :- </span> 
                                            <span class="visit-lable-value">{{ucwords($lastAppointment->getPatientsDetails->name)}}</span>
                                        </div>
                                        <div class="mb-2">
                                                <span class="visit-lable">AGE :- </span> 
                                                <span class="visit-lable-value">{{$lastAppointment->getPatientsDetails->age}}</span>
                                        </div>
                                        <div class="mb-2">
                                                <span class="visit-lable">Type & Year of infertility :- </span> 
                                                <span class="visit-lable-value">{{!empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility] : 'Primary'}} / {{!empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' years' : null}} {{!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' years' : null}}</span>
                                        </div>
                                        <div class="mb-2">
                                                <span class="visit-lable">L.M.P :- </span> 
                                                <span class="visit-lable-value">{{!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : null}}</span>
                                        </div>
                                        @if($pStatus == 3)
                                            <div class="mb-2">
                                                <span class="visit-lable">Semen Freezing :- </span> 
                                                <span class="visit-lable-value">{{$historySemenFreezing}}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 follicular_div_2">
                                        <div class="mb-2">
                                            <span class="visit-lable">UTERUS :- </span> 
                                            <span class="visit-lable-value">{{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ut->details) && $ivfSecondVisitData->oe->ut->ut_type == 2 ? $ivfSecondVisitData->oe->ut->details : 'Normal'}}</span>
                                        </div>
                                        <div class="mb-2">
                                            <div class="row">
                                                <div class="col-md-2 visit-lable">
                                                OVARIES :- 
                                                </div>
                                                <div class="col-md-10 pl-15">
                                                    <div class="mb-2">R :- {{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ovary->right->afcs) ? $ivfSecondVisitData->oe->ovary->right->afcs : null}}</div>
                                                    <div>L :- {{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ovary->left->afcs) ? $ivfSecondVisitData->oe->ovary->left->afcs : null}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <span class="visit-lable">Endometrial Thickness :- </span> 
                                            <span class="visit-lable-value">{{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->endometrial_cavity->size) ? $ivfSecondVisitData->oe->endometrial_cavity->size : ''}}</span>
                                        </div>
                                        @if($pStatus == 3 || $pStatus == 4)
                                            <div class="mb-2">
                                                <span class="visit-lable">Embroy Ready :- </span> 
                                                <span class="visit-lable-value">{{$historyEmbroyReady}}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    {{-- <div class="col-md-12">
                                        <a class="btn btn-primary btn-icon btn-icon-mini btn-round add-row" data-id="5" data-day="0"><i class="material-icons">add</i></a>
                                    </div> --}}
                                    @if(($cycle[count($cycle)-1]['cycle_status'] != 2))
                                        <div class="col-md-1">
                                            <label class="vertical-form-label pr-0">
                                                Seen By :
                                            </label>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                {{Form::select('seen_by',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
                                            </div>
                                            <span class="seen-by-error text-danger mb-2"></span>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <table class="table follicular-table frozen-table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th style="width:8% !important;">Date</th>
                                                    <th style="width:5% !important">Day of Menses</th>
                                                    <th style="width:15% !important">Endometrial Thickness / Pattern</th>
                                                    <th style="width:5px;">Vascularity of Endometrium</th>
                                                    <th style="width: 20% !important;">Drugs </th>
                                                    <th style="width: 20% !important;">Remark</th>
                                                    <th style="width: 10% !important;" class="">Action</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                
                                                @php 
                                                    $lastHistory = $cycle[count($cycle)-1];
                                                    $lastHistoryData = !empty($lastHistory->description) ? json_decode($lastHistory->description) : null;
                                                    // print_r();
                                                @endphp
                                                @foreach($cycle as $row)
                                                    @php
                                                        $historyData = json_decode($row->description);
                                                        $investigationData = json_decode($row->investigation);
                                                        $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                                        $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $row->created_at)->diffInDays(\Carbon\Carbon::parse($row->created_at));
                                                        $diff = $diff + 1;
                                                        $vascularity_of_endo = ['1' => "Up to Zone 1",'2' => "Up to Zone 2",'3' => "Up to Zone 3",'4' => "Up to Zone 4"];
                                                    
                                                        $semenFreezingValueData = 'd-none';
                                                        $embroyReadyValueData = 'd-none';
                                                        $semenFreezingValue = 'yes';
                                                        $embroyReadyValue = 'yes';
                                                        $collectionData = !empty($historyData->collection) ? $historyData->collection : [];
                                                        $dataa = !empty($historyData->collected) ? $historyData->collected : []; 
                                                        $resultValue = 0;
                                                        $skipValue = 0;
                                                        if($pStatus == 3)
                                                        {
                                                            if(isset($historySemenFreezing) && $historySemenFreezing == 'No')
                                                            {
                                                                $semenFreezingValueData = '';
                                                                $semenFreezingValue = 'no';
                                                            }
                                                        }
                                                        if($pStatus == 3 || $pStatus == 4)
                                                        {
                                                            if(isset($historyEmbroyReady) && $historyEmbroyReady == 'No')
                                                            {
                                                                $embroyReadyValueData = '';
                                                                $embroyReadyValue = 'no';

                                                            }
                                                        }
                                                        //for result table
                                                        if(isset($historyData->is_upt) && $historyData->is_upt == 'yes' && !empty($historyData->transfer->upt_type) && !empty($historyData->transfer->result_type))
                                                        {
                                                            $resultValue = 1;
                                                        }
                                                        if((!empty($historyData->plan) || !empty($historyData->follow_up)) && !empty($historyData->skip_reason))
                                                        {
                                                            $skipValue = 1;
                                                        }
                                                    @endphp
                                                    @if($row->visit == 2)
                                                        @php
                                                            $ivfExtraVisit = IvfExtraVisit::where('patient_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','ASC')->get();
                                                        @endphp
                                                        @if(!empty($ivfExtraVisit))
                                                                @foreach($ivfExtraVisit as $ivfExtra)
                                                                <tr >
                                                                    <td>{{\Carbon\Carbon::parse($ivfExtra->created_at)->format('d-m-Y')}}</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{'Extra Visit'}}</td>
                                                                    <td>
                                                                        <a href="{{URL::to('ivf/extra-visit/'.encrypt($patient_id).'/'.encrypt($cycleNumber))}}" class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-iui-data" data-id="{{encrypt($row->id)}}">
                                                                            <i class="zmdi zmdi-edit material-icons"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                        @endif
                                                    @endif
                                                    @if($resultValue == 0)
                                                        <tr>
                                                            <td>{{$visitDate}}</td>
                                                            <td>{{$diff}}</td>
                                                            <td class="">{{!empty($historyData->oe->endometrial_cavity->size) ? $historyData->oe->endometrial_cavity->size : ''}}</td>
                                                            <td>
                                                                {{!empty($historyData->vascularity_of_endo) ? $vascularity_of_endo[$historyData->vascularity_of_endo] : null}}
                                                            </td>
                                                            <td>
                                                                @if(!empty($historyData->medicinedata))
                                                                    @foreach ($historyData->medicinedata as $medicine)
                                                                        @if( strpos( $medicine->medicine, 'ESTRADIOL' ) !== false)
                                                                        @php
                                                                            $qty = (!empty($medicine->quantity)) ? $medicine->quantity : 0;
                                                                            $qty_2 = (!empty($medicine->quantity_2)) ? $medicine->quantity_2 : 0;
                                                                            $qty_3 = (!empty($medicine->quantity_3)) ? $medicine->quantity_3 : 0;
                                                                            $qty_4 = (!empty($medicine->quantity_4)) ? $medicine->quantity_4 : 0;
                                                                        @endphp
                                                                            {{$medicine->medicine.' ('.$qty.'-'.$qty_2.'-'.$qty_3.'-'.$qty_4.')'}} 
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                                @if (in_array('transfer',$collectionData))
                                                                <br>
                                                                        <a href="javascript:void(0);" id="ivf_transfer_report_update" data-patient-id={{ encrypt($row->patients_id)}} data-cycle-no={{ encrypt($row->cycle_no)}} data-plan={{ encrypt($row->plan)}} data-visit={{ encrypt($row->visit)}}>
                                                                            IVF Transfer Report
                                                                        </a>
                                                                @endif
                                                            </td>
                                                            <td class="">
                                                                {{!empty($historyData->remark) ? $historyData->remark : ''}}
                                                                    
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="#" class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-visit-data" data-id="{{ encrypt($row->id) }}">
                                                                    <i class="zmdi zmdi-delete material-icons"></i>
                                                                </a>
                                                                @if(isset($historyData->is_transfer) && ($historyData->is_transfer == 'no' || $historyData->is_transfer_print == 'no') && !in_array('transfer',$collectionData))
                                                                <a class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-visit-data" data-id="{{encrypt($row->id)}}"><i class="zmdi zmdi-edit material-icons"></i></a>
                                                                @endif
                                                                @if((isset($historyData->hsa_report->images) && !empty($historyData->hsa_report->images)) || (isset($historyData->blood_report->image) && !empty($historyData->blood_report->image)) || (isset($historyData->usg->images) && !empty($historyData->usg->images)) || (isset($investigationData->hystroscopy->images) && !empty($investigationData->hystroscopy->images)) || (isset($investigationData->laproscopy->images) && !empty($investigationData->laproscopy->images)))
                                                                <a href="#" class="btn btn-icon btn-neutral candor-color btn-icon-mini report-btn" data-id="{{ encrypt($row->id) }}" data-date="{{\Carbon\Carbon::parse($row->created_at)->format('d M Y')}}">
                                                                    <i class="zmdi zmdi-camera material-icons"></i>
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if(isset($historyData->progesterone_date) && (!empty($historyData->progesterone->type)) && (!empty($historyData->progesterone_date)))
                                                        <tr>
                                                            <td>{{\Carbon\Carbon::parse($historyData->progesterone_date)->format('d-m-Y')}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{'Progesterone start'}}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endif
                                                    @if(isset($historyData->is_transfer) && $historyData->is_transfer == 'yes')
                                                        @php
                                                            $ivfExtraVisit = IvfExtraVisit::where('patient_id',$row->patients_id)->where('created_at','>',$row->created_at)->orderBy('id','ASC')->get();
                                                        @endphp
                                                        @if(!empty($ivfExtraVisit))
                                                                @foreach($ivfExtraVisit as $ivfExtra)
                                                                <tr >
                                                                    <td>{{\Carbon\Carbon::parse($ivfExtra->created_at)->format('d-m-Y')}}</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{'Extra Visit'}}</td>
                                                                    <td>
                                                                        <a href="{{URL::to('ivf/extra-visit/'.encrypt($patient_id).'/'.encrypt($cycleNumber))}}" class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-iui-data" data-id="{{encrypt($row->id)}}">
                                                                            <i class="zmdi zmdi-edit material-icons"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                        @endif
                                                    @endif
                                                @endforeach
                                            
                                                @if(!empty($lastHistoryData) && $resultValue == 0 && $skipValue == 0 && $isForm == true)
                                                    @php
                                                        $date = \Carbon\Carbon::parse($lastHistoryData->follow_up)->format('d-m-Y');
                                                        $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $lastHistory->created_at)->diffInDays(\Carbon\Carbon::parse($date));
                                                        $diff = $diff + 1;
                                                    @endphp
                                                    <tr class="">
                                                        <td>{{$date}}</td>
                                                        <td>{{$diff}}</td>
                                                        <td class=""> 
                                                            {{Form::text("data[oe][endometrial_cavity][size]",!empty($historyData->endometrial->type) ? $historyData->endometrial->type : null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details','autocomplete'=>"off"])}}
                                                        </td>
                                                        <td>
                                                            {{Form::select('data[vascularity_of_endo]',$vascularity_of_endo,'',['class'=>'form-control select-padding-0 vascularity_of_endo','placeholder'=>'Select Vascularity'])}}
                                                        </td>
                                                        <td>
                                                            
                                                        </td>
                                                        
                                                        <td class="">
                                                            {{Form::textarea("data[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                                                            <span class="transfer-error text-danger mb-2"></span>
                                                        </td>
                                                        <td class=""></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <td colspan="7" class="frozen_table_footer">
                                                    @if(!$isForm && !empty($lastHistoryData->plan) && $lastHistory->cycle_status == 2)
                                                    <div class="mb-2">
                                                        <span class="visit-lable">Transfer Plan :- </span> 
                                                        <span class="visit-lable-value">{{isset($planData[$lastHistoryData->plan])? $planData[$lastHistoryData->plan] : ''}}</span>
                                                    </div>
                                                    @endif
                                                    @if(!$isTransfer && $isForm && $skipPlan == $pStatus && $skipValue == 0 && $resultValue == 0)
                                                    
                                                        {{Form::hidden('data[is_transfer]','no',['class'=>'is-transfer'])}}
                                                        {{Form::hidden('data[is_transfer_print]','no')}}
                                                        {{Form::hidden('appointment_date',$lastAppointment->date,['class'=>'last-appointment-date'])}}
                                                        {{Form::hidden("data[lmp][date]",!empty($historyLmddateDate) ? \Carbon\Carbon::parse($historyLmddateDate)->format('D d M Y') : null ,['class'=>'form-control history-lmd-date','autocomplete'=>'off'])}}
                                                        {{Form::hidden("data[lmp][lmp_date_diff]",$historyLmdDiff,['class'=>'form-control history-lmd-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                            
                                            
                                                        <div class="row mt-1">
                                                            @if(($pStatus == 3))
                                                                <div class="{{'col-md-2 '.$semenFreezingValueData}}">
                                                                    <label class="vertical-form-label">
                                                                        Semen Freezing :
                                                                    </label>
                                                                </div>
                                                                <div class="{{'col-md-2 '.$semenFreezingValueData}}">
                                                                    <div class="radio is-conceived">
                                                                        {{Form::radio("data[collected][frozen][type]",'yes',!empty($semenFreezingValue) && $semenFreezingValue == 'yes' ? true : false,['id'=>'progesteroneyes'])}}
                                                                        <label for="progesteroneyes">
                                                                            Yes
                                                                        </label>
                                                                        {{Form::radio("data[collected][frozen][type]",'no',!empty($semenFreezingValue) && $semenFreezingValue == 'no' ? true : false,['id'=>'progesteroneno'])}}
                                                                        <label for="progesteroneno">
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            {{Form::hidden('ivf_report_id', '' , ['id' => 'ivf_report_id'])}}
                                                            @if(($pStatus == 3 || $pStatus == 4))
                                                                <div class="{{'col-md-2 '.$embroyReadyValueData}}">
                                                                    <label class="vertical-form-label">
                                                                        Embroy Ready :
                                                                    </label>
                                                                </div>
                                                                <div class="{{'col-md-2 embroy-yes '.$embroyReadyValueData}}">
                                                                    <div class="radio is-conceived">
                                                                        {{Form::radio("data[collected][report][embroy][type]",'yes',!empty($embroyReadyValue) && $embroyReadyValue == 'yes' ? true : false,['id'=>'embroyyes'])}}
                                                                        <label for="embroyyes">
                                                                            Yes
                                                                        </label>
                                                                        {{Form::radio("data[collected][report][embroy][type]",'no',!empty($embroyReadyValue) && $embroyReadyValue == 'no' ? true : false,['id'=>'embroyno'])}}
                                                                        <label for="embroyno">
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-4">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[collection][]','progesterone','',['id'=>'progesterone'])}}
                                                                    <label for="progesterone">
                                                                        Progesterone supplementation?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="{{'col-md-3 d-none progesterone_data'}}">
                                                                
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("data[progesterone][type]",'day_3','',['id'=>'day_3','class'=>'progesterone-type'])}}
                                                                    <label for="day_3">
                                                                        Day-3
                                                                    </label>
                                                                    {{Form::radio("data[progesterone][type]",'day_5','',['id'=>'day_5','class'=>'progesterone-type'])}}
                                                                    <label for="day_5">
                                                                        Day-5
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 d-none progesterone_date_div">
                                                                <div class="form-group">
                                                                        {{Form::text("data[progesterone_date]",\Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker progesterone_date'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[collection][]','blood','',['id'=>'blood'])}}
                                                                    <label for="blood">
                                                                        Blood Report                
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 bloodreport d-none">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Blood report: &nbsp;</span>
                                                                            {{Form::text("data[blood_report][report]",'',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                                        <div class="blood-images"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[collection][]','usg','',['id'=>'usg'])}}
                                                                    <label for="usg">
                                                                        USG Report
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 usgreport d-none">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">USG report: &nbsp;</span>
                                                                            {{Form::text("data[usg][report]",'',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                                        <div class="usg-images"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[collection][]','hsa','',['id'=>'hsa'])}}
                                                                    <label for="hsa">
                                                                        HSA Report
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 usgreport d-none">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">USG report: &nbsp;</span>
                                                                            {{Form::text("data[hsa_report][report]",'',['class'=>'form-control'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                                        <div class="hsa-images"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($pStatus != 1 )
                                                            <div class="row mt-1">
                                                                <div class="col-md-1">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('data[collection][]','transfer','',['id'=>'transfer','class'=>'transfer'])}}
                                                                        <label for="transfer">
                                                                            Transfer
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Indication: &nbsp;</span>
                                                                        {{Form::text("indication",'',[
                                                                            'class'=>'form-control',
                                                                            'maxlength' => 250
                                                                        ])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">ET Date: &nbsp;</span>
                                                                        {{Form::text("et_date", \Carbon\Carbon::now()->addDays(1)->format('D d M Y'), ['class'=>'form-control datetimepicker'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Day: &nbsp;</span>
                                                                        {{Form::text("day",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Endo. Thickness: &nbsp;</span>
                                                                        {{Form::text("endo_thickness",'',[
                                                                            'class'=>'form-control',
                                                                            'maxlength' => 250
                                                                        ])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">ET Procedure: &nbsp;</span>
                                                                        {{Form::text("et_procedure",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Frozen Embryos: &nbsp;</span>
                                                                        {{Form::text("frozen_embryos",'',[
                                                                            'class'=>'form-control',
                                                                            'maxlength' => 250
                                                                        ])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-3 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Embryos Transferred: &nbsp;</span>
                                                                        {{Form::text("embryos_transferred",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 transfer-data d-none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Embryos Transferred Image: &nbsp;</span>
                                                                        {{Form::file('embryos_transferred_image',['class'=>'form-control embryos_transferred_image'])}}
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        @endif
                                                        <div class="row mt-1">
                                                            <div class="col-md-2 pr-0">
                                                                <label class="vertical-form-label pr-0">
                                                                    Hystroscopy :
                                                                </label>
                                                            </div>
                                                            
                                                            <div class="col-sm-2">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("investigation[hystroscopy][type]",'yes','',['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                                    <label for="hystroscopy_type_yes">
                                                                        Yes
                                                                    </label>
                                                                    {{Form::radio("investigation[hystroscopy][type]",'no','',['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                                    <label for="hystroscopy_type_no">
                                                                        No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 hystroscopy-type d-none">
                                                                <div class="hystroscopy-images"></div>
                                                                {{-- <div class="row">
                                                                    <div class="col-md-4">
                                                                        {{Form::file('investigation[hystroscopy][images][]',['class'=>'form-control hystroscopy-file',"multiple"=>'true'])}}
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md-2 pr-0">
                                                                <label class="vertical-form-label pr-0">
                                                                    Laproscopy :
                                                                </label>
                                                            </div>
                                                            
                                                            <div class="col-sm-2">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("investigation[laproscopy][type]",'yes','',['id'=>'laproscopy_type_yes','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                                                                    <label for="laproscopy_type_yes">
                                                                        Yes
                                                                    </label>
                                                                    {{Form::radio("investigation[laproscopy][type]",'no','',['id'=>'laproscopy_type_no','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                                                                    <label for="laproscopy_type_no">
                                                                        No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 laproscopy-type d-none">
                                                                <div class="laproscopy-images"></div>
                                                                {{-- <div class="row">
                                                                    <div class="col-md-4">
                                                                        {{Form::file('investigation[laproscopy][images][]',['class'=>'form-control laproscopy-file',"multiple"=>'true'])}}
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[p_s][type]','yes','',['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                                                                    <label for="ps_status">
                                                                        P/S
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ps-details d-none">
                                                                <div class="form-group">
                                                                    {{Form::text("data[p_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                {{Form::textarea("data[pt_remark]",'',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'2'])}}
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-xs-6 col-md-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Follow Up: &nbsp;</span>
                                                                    @if($pStatus != 1)
                                                                        {{Form::text("data[follow_up]",\Carbon\Carbon::now()->addDays(1)->format('D d M Y'),['class'=>'form-control tranfer-follow-date datetimepicker lmp-date-follow-up next-date'])}}
                                                                    @else
                                                                        {{Form::text("data[follow_up]",'',['class'=>'form-control datetimepicker tranfer-follow-date follow-up-date next-date'])}}
                                                                    @endif
                                                                    {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group">
                                                                    {{Form::text("followUp-dateDiff",'',['class'=>'form-control history-follow-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 plan-transfer-data">
                                                                <div class="form-group">
                                                                    {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan-transfer','placeholder'=>'Select Plan Transfer'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2 plan-transfer-data plan-transfer-data-type d-none">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("data[transfer_type]",'new',true,['id'=>'transfer-type-new','class'=>'upt-type'])}}
                                                                    <label for="transfer-type-new">
                                                                        NEW
                                                                    </label>
                    
                                                                    {{Form::radio("data[transfer_type]",'old','',['id'=>'transfer-type-old','class'=>'upt-type'])}}
                                                                    <label for="transfer-type-old">
                                                                        OLD
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row skip-data">
                                                            <div class="col-md-3">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('data[skip_cycle]','yes','',['class'=>'skip-cycle','id'=>'skip-pick-up','data-id'=>'skip-cycle-data','data-plan="pick-up"'])}}
                                                                    <label for="skip-pick-up" class="text-danger">
                                                                        Do you want to skip this cycle?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 skip-cycle-data d-none">
                                                                <div class="form-group">
                                                                    {{Form::text("data[skip_reason]",'',['class'=>'form-control skip-reason','placeholder'=>'Enter Reason'])}}
                                                                </div>
                                                                <span class="skip-reason-error form-error-msg"></span>
                                                            </div>
                                                            <div class="col-md-3 skip-cycle-data d-none">
                                                                <div class="form-group">
                                                                    {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan skip-plan','placeholder'=>'Select Plan'])}}
                                                                </div>
                                                                <span class="skip-plan-error form-error-msg"></span>
                                                            </div>
                                                        </div>
                                                        {{Form::hidden("data[is_upt]",'no')}}
                                                    @elseif($skipValue == 1) {{-- skip cycle --}}
                                                        @php
                                                                $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                                                $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $row->created_at)->diffInDays(\Carbon\Carbon::parse($visitDate));
                                                                $diff = $diff + 1;
                                                        @endphp
                                                        <h5 class=""><u>Skip Cycle:</u></h5>
                                                        <table class="table follicular-table frozen-table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Follow UP</th>
                                                                    <th>Transfer Plan</th>
                                                                    <th>Reason</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>{{$visitDate}}</td>
                                                                    <td>{{$planData[$lastHistoryData->plan]}}</td>
                                                                    <td>{{$lastHistoryData->skip_reason}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    @else {{-- result --}}
                                                        {{Form::hidden("data[is_upt]",'yes')}}
                                                        {{Form::hidden('data[is_transfer]','yes',['class'=>'is-transfer'])}}
                                                        {{Form::hidden('data[is_transfer_print]','yes')}}
                                                        @if($resultValue == 0 && $isForm)
                                                            <div class="row">
                                                                    {{-- upt --}}
                                                                    <div class="col-md-1">
                                                                        <label class="vertical-form-label pr-0">
                                                                            UPT :
                                                                        </label>
                                                                    </div>
                                
                                                                    <div class="col-sm-2">
                                                                        <div class="radio is-conceived">
                                                                            {{Form::radio("data[transfer][upt_type]",'positive','',['id'=>'transfer-positive','class'=>'upt-type'])}}
                                                                            <label for="transfer-positive">
                                                                                Positive
                                                                            </label>
                                
                                                                            {{Form::radio("data[transfer][upt_type]",'negative','',['id'=>'transfer-negative','class'=>'upt-type'])}}
                                                                            <label for="transfer-negative">
                                                                                Negative
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    {{-- result --}}
                                                                    <div class="col-md-1">
                                                                        <label class="vertical-form-label pr-0">
                                                                            Result :
                                                                        </label>
                                                                    </div>
                                
                                                                    <div class="col-sm-2">
                                                                        <div class="radio is-conceived">
                                                                            {{Form::radio("data[transfer][result_type]",'conceive','',['id'=>'transfer-conceive','class'=>'result-type'])}}
                                                                            <label for="transfer-conceive">
                                                                                Conceive
                                                                            </label>
                                
                                                                            {{Form::radio("data[transfer][result_type]",'fail','',['id'=>'transfer-fail','class'=>'result-type'])}}
                                                                            <label for="transfer-fail">
                                                                                Fail
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    {{-- end result --}}
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                    <label class="vertical-form-label pr-0">
                                                                        Report :
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    {{Form::file('data[transfer][report][]',['class'=>'form-control',"multiple"=>"multiple"])}}
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Follow Up: &nbsp;</span>
                                                                        {{Form::text("data[transfer][follow_up]",\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'form-control tf-date datetimepicker'])}}
                                                                    </div>
                                                                    {{Form::hidden('data[follow_up]',\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'t-follow-date'])}}
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                    <div class="col-md-3 plan-transfer-data">
                                                                        <div class="form-group">
                                                                            {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan-transfer','placeholder'=>'Select Plan Transfer'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2 plan-transfer-data plan-transfer-data-type d-none">
                                                                        <div class="radio is-conceived">
                                                                            {{Form::radio("data[transfer_type]",'new',true,['id'=>'transfer-type-new','class'=>'upt-type'])}}
                                                                            <label for="transfer-type-new">
                                                                                NEW
                                                                            </label>
                                
                                                                            {{Form::radio("data[transfer_type]",'old','',['id'=>'transfer-type-old','class'=>'upt-type'])}}
                                                                            <label for="transfer-type-old">
                                                                                OLD
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                            </div>
                                                        @else
                                                            @if(!empty($lastHistoryData->transfer->result_type))
                                                                @php
                                                                    $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                                                    $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $row->created_at)->diffInDays(\Carbon\Carbon::parse($visitDate));
                                                                    $diff = $diff + 1;
                                                                @endphp
                                                                <h5 class=""><u>Result:</u></h5>
                                                                <table class="table follicular-table frozen-table table-bordered table-responsive">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>UPT</th>
                                                                            <th>Result</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>{{$visitDate}}</td>
                                                                            <td>{{$lastHistoryData->transfer->upt_type}}</td>
                                                                            <td>{{$lastHistoryData->transfer->result_type}}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        @endif
                                                        
                                                    @endif
                                                    {{-- {{Form::close()}} --}}
                                                </td>
                                                </tr>  
                                            </tfoot>
                                        </table>

                                        <h4 class=""><u>Medicine:</u></h4>
                                        <table class="table follicular-table frozen-table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: bold !important;"> Date</th>
                                                    <th style="font-weight: bold !important;"> Medicine</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cycle as $row)
                                                    @php
                                                        $data = json_decode($row->description);
                                                        $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                                        $historyTreatmentView = null;
                                                        if(!empty($data->medicinedata)){
                                                            $historyTreatmentView = !empty($data->medicinedata) ? $data->medicinedata : null;
                                                        }
                                                    @endphp
                                                    @if(!empty($historyTreatmentView))
                                                    <tr>
                                                        <td>{{$visitDate}}</td>
                                                        <td style="text-align: justify!important">
                                                            
                                                            @if($historyTreatmentView)
                                                                @php
                                                                    
                                                                    unset($historyTreatmentView->medicinedata);
                                                                @endphp
                                                                @foreach($historyTreatmentView as $key=>$row)
                                                                @php
                                                                
                                                                    $firstCharacter = substr($row->medicine, 0, 3);
                                                                    $notinject = "";
                                                                    if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                                                        $notinject = "is-inj";
                                                                    } 
                                                                @endphp
                                                                <div class="mb-1">
                                                                    <span class="visit-lable"> Medicine : &nbsp</span>
                                                                        {{ $row->medicine }}
                                                                        @if($notinject != "is-inj")
                                                                            | @switch($row->medicine_status)
                                                                                    @case('1')
                                                                                        જમ્યા પછી
                                                                                        @break
                                                                                    @case('2')
                                                                                        જમ્યા પહેલાં
                                                                                        @break
                                                                                    @case('3')
                                                                                        માસિકની જગ્યાએ મુકવી
                                                                                    @break
                                                                            @endswitch
                                                                        @endif
                                                                    @if(!empty($row->dose))
                                                                        @if (array_key_exists($row->dose, $old_dose))
                                                                            | {{ $old_dose[$row->dose] }}
                                                                        @endif
                                                                    @endif
                                                                    @if (!empty($row->no)) | Days : {{ $row->no }} @endif
                                                                    @if($notinject != "is-inj")
                                                                    @php
                                                                        $qty = (!empty($row->quantity)) ? $row->quantity : 0;
                                                                        $qty_2 = (!empty($row->quantity_2)) ? $row->quantity_2 : 0;
                                                                        $qty_3 = (!empty($row->quantity_3)) ? $row->quantity_3 : 0;
                                                                        $qty_4 = (!empty($row->quantity_4)) ? $row->quantity_4 : 0;
                                                                    @endphp | Quantity : {{$qty.'-'.$qty_2.'-'.$qty_3.'-'.$qty_4}}
                                                                    @endif
                                                                    @if($notinject == "is-inj")
                                                                        @if (!empty($row->medicine_time))
                                                                                @if(is_array($row->medicine_time))
                                                                                    @foreach ($row->medicine_time as $time)
                                                                                    |{{$old_medicine_time[$time]}}
                                                                                    @endforeach
                                                                                @else
                                                                                |{{$old_medicine_time[$row->medicine_time]}}
                                                                                @endif
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                    <br>
                                                                @endforeach
                                                                
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <td colspan="6">
                                                    @if($isForm == true && ($cycle[count($cycle)-1]['cycle_status'] != 2))
                                                    <div class="row treatment-data" id="t_data_1">
                                                        <div class="col-md-2 pr-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Select Medicine :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-9 complain-multi medicine-picker">
                                                            {{Form::select("data[treatment][medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','placeholder' =>'Enter medicine name'])}}
                                                        </div>
                                                    </div>
                                                    <div class="page-loader-wrapper medicine-loader d-none">
                                                        <div class="loader">
                                                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if(!empty($historyTreatment))
                                                        @foreach($historyTreatment as $key=>$row)
                                                        {{-- @if(isset($medicines[$row->medicine])) --}}
                                                            <?php
                                                            $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                            $firstCharacter = substr($mId, 0, 3);
                                                            $notinject = "";
                                                            if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                                                $notinject = "is-inj";
                                                                $dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day",'7'=>"6 hourly",'8'=>"8 hourly",'9'=>"12 hourly",'10'=>"24 hourly"];
                
                                                            }
                                                            $till_follow_up = (empty($row->no)) ? 'till-follow-up' : '';
                                                            ?>
                                                            <div class="{{'row mt-2 '.$notinject}}" data-id="{{$mId}}">
                                                                <div class='col-md-2'>
                                                                    <div class='input-group'>
                                                                        <span class='input-group-addon'>M : </span>
                                                                        {{Form::text('data[treatment]['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-1 notinject'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-1 notinject'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-1 notinject'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-1 notinject'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-2 notinject'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-2 isinject'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-2'>
                                                                    <div class='form-group'>
                                                                        {{Form::select('data[treatment]['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-1'>
                                                                    <div class='input-group'>
                                                                        <span class='input-group-addon'>Day :</span>
                                                                        {{Form::number('data[treatment]['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-1 medicine-data-remove'>
                                                                    <span class=""><i class="material-icons">close</i></span>
                                                                </div>
                                                            </div>
                                                            {{-- @endif --}}
                                                        @endforeach
                                                    @endif
                                                    
                                                    <div class="medicine-data treatment-medicine-data"></div>
                                                    
                                                    {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}}
                                                </td>
                                            </tfoot>
                                        </table>
                                    </div>
                                    
                                    {{-- @endif --}}
                                </div>
                            </div>
                            @if($isForm)
                                {{Form::hidden('ivf_report',!empty($ivfReport) ? $ivfReport : null,['class'=>'ivf-report-status'])}}
                                {{Form::hidden('ivf_history_id', '' , ['id' => 'ivf_history_id'])}}
                                {{Form::button('submit',['class'=>'btn btn-primary submit'])}}
                                {{Form::hidden('ivf_transfer_report_id', '' , ['id' => 'ivf_transfer_report_id'])}}
                                <button type="submit" class="btn btn-primary submit" value="1">Save & Preview</button>
                                <button type="submit" class="btn btn-primary submit transfer-report d-none" value="5">Transfer Report Preview</button>
                                <a class="btn btn-primary t-print transfer-print d-none" data-id="">Transfer Print</a>
                                <button type="submit" class="btn btn-primary submit d-none" value="3" id="ivf_print"> Print IVF</button>
                                <button type="submit" class="btn btn-primary submit d-none" value="4" id="ivf_report_print"> Save & Print IVF Report</button>
                                <a href="{{URL::previous()}}" class="btn btn-default">Cancel</a>
                            @endif
                        {{Form::close()}}
                        </div>
                    </div>
                </div>

            @endif
        @endif
        @if($isForm && $skipPlan == $pStatus && ($nextVisitValue < 2))
            <div class="card cycle-form">
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-form'])}}
                            <div class="row">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        Seen By :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select('seen_by',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
                                    </div>
                                    <span class="seen-by-error text-danger mb-2"></span>
                                </div>
                            </div>
                            {{Form::hidden('visit',$visit,['class'=>'visit-no'])}}
                            {{Form::hidden('plan_type',$pStatus,['class'=>'plan_type'])}}
                            {{Form::hidden('cycle_no',$cycleNumber)}}
                            {{Form::hidden('cycle_no_data',encrypt($cycleNumber),['class'=>'cycle-no-data'])}}
                            {{Form::hidden('pickup_pln',encrypt(1),['class'=>'pickup-plan'])}}
                            {{Form::hidden('patients_id',$patientsId,['class'=>'patients-id'])}}
                            {{Form::hidden('last_s_days',$sDay,['class'=>'last-s-days'])}}
                            {{Form::hidden('last_protocol_date',$pDate,['class'=>'last-protocol-date'])}}

                            <h4 class="col-md-2 visit-lable m-0">Visit :<span class="col-md-2 visit-lable-value">{{$visit}}
                                {{-- <span class="plan-text">{{!empty($ivfSecondVisitData->plan) ? $planData[$ivfSecondVisitData->plan] : null}}</span> --}}
                            </h4><br>
                            @if(!$isTransfer)
                                {{Form::hidden('data[is_transfer]','no',['class'=>'is-transfer'])}}
                                {{Form::hidden('data[is_transfer_print]','no')}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                                            {{Form::text("data[lmp][date]",!empty($historyLmddateDate) ? \Carbon\Carbon::parse($historyLmddateDate)->format('D d M Y') : null ,['class'=>'form-control history-lmd-date','autocomplete'=>'off'])}}
                                        </div>
                                        <span class="lmp-date-error form-error-msg"></span>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            {{Form::text("data[lmp][lmp_date_diff]",$historyLmdDiff,['class'=>'form-control history-lmd-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                            {{Form::hidden('appointment_date',$lastAppointment->date,['class'=>'last-appointment-date'])}}
                                        </div>
                                    </div>
                                    <span class="col-md-1 p-2 history-lmp-date">Day</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="checkbox">
                                            {{Form::checkbox('data[le][vitals_status]','yes','',['class'=>'vitals_status','id'=>'vitals_status','data-id'=>'vitals_status_data'])}}
                                            <label for="vitals_status">
                                                Vitals
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 vitals_status_data d-none">
                                        <div class="input-group">
                                            <span class="input-group-addon">B.P : &nbsp;</span>
                                            {{Form::text("data[le][bp]",'',['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <span class="col-md-1 p-2 vitals_status_data d-none">MMHG</span>
                                    <div class="col-md-2 vitals_status_data d-none">
                                        <div class="input-group vitals_status_data d-none">
                                            <span class="input-group-addon">Temp : &nbsp;</span>
                                            {{Form::text("data[le][temp]",'',['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 vitals_status_data d-none">
                                        <div class="input-group">
                                            <span class="input-group-addon">Pulse : &nbsp;</span>
                                            {{Form::text("data[le][pulse]",'',['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <span class="col-md-1 p-2 vitals_status_data d-none">/ Min</span>
                                </div>
                                {{-- @if($pStatus == 1) --}}
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label class="vertical-form-label pr-0">
                                                OE :
                                            </label>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="radio is-conceived">
                                                {{Form::radio("data[oe][oe_type][type]",'tvs',!empty($historyOe->oe_type->type) && $historyOe->oe_type->type == 'tvs' ? true : false,['id'=>'tvs'])}}
                                                <label for="tvs">
                                                    TVS
                                                </label>

                                                {{Form::radio("data[oe][oe_type][type]",'pa',!empty($historyOe->oe_type->type) && $historyOe->oe_type->type == 'pa' ? true : false,['id'=>'pa'])}}
                                                <label for="pa">
                                                    PA
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="vertical-form-label pr-0">
                                                Uterus:
                                            </label>
                                        </div>
                                        @php
                                            $utValues = $uterusData;
                                            $utType = 'd-none';
                                            if((!empty($historyOe->ut->ut_type) && $historyOe->ut->ut_type == 2) || $utValues){
                                                $utType = '';
                                            }
                                            $ovaryType = !empty($historyOe->ovary->ovary_type) ? $historyOe->ovary->ovary_type : [];
                                            $ovaryLeftType = !empty($historyOe->ovary->left->type) && $historyOe->ovary->left->type == 1 ? 'd-none' : null;
                                            $ovaryRightType = !empty($historyOe->ovary->right->type) && $historyOe->ovary->right->type == 1 ? 'd-none' : null;

                                        @endphp
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::select("data[oe][ut][ut_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ut->ut_type) ? $historyOe->ut->ut_type : ($utValues ? 2 : null),['class'=>'form-control select-padding-0 abnormal','data-type'=>'ut-type','placeholder'=>'Select UT Type'])}}
                                            </div>
                                        </div>
                                        <div class="{{'col-md-3 ut-type '.$utType}}">
                                            <div class="form-group">
                                                {{Form::text("data[oe][ut][details]",!empty($historyOe->ut->details) ? $historyOe->ut->details : ($utValues ? $utValues : null),['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty($cycleNumber) && $visit == 2)
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label class="vertical-form-label pr-0">
                                                    Ovary:
                                                </label>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[oe][ovary][ovary_type][]','right',in_array('right',$ovaryType),['id'=>'oe_right','class'=>'plan-management'])}}
                                                    <label for="oe_right">
                                                        Right
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="{{'col-md-10'}}">
                                                <div class="row">
                                                    {{-- <div class="col-md-3">
                                                        <div class="form-group">
                                                            {{Form::select("data[oe][ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ovary->right->type) ? $historyOe->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="{{'col-md-5 complain-multi'}} ">
                                                        {{Form::select("data[oe][ovary][right][details][]",$rightOvaryData,!empty($historyOe->ovary->right->details) ? $historyOe->ovary->right->details : null,[
                                                            'class'=>'form-control co-value co_value_data history-oe-ovary-right-details',
                                                            'placeholder'=>'Abnormal Details',
                                                            'id' => 'oe_ovary_right_details',
                                                            'data-id' => '2',
                                                            'multiple'=>true
                                                        ])}}
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon right-ovary-data overy-popup" data-class="right-ovary-data">AFCS : &nbsp;</span>
                                                            {{Form::text("data[oe][ovary][right][afcs]",!empty($historyOe->ovary->right->afcs) ? $historyOe->ovary->right->afcs : null,['class'=>'form-control right-ovary-data-text'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="{{'row oe-right-details'}}">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-7 complain-multi ml-5">
                                                <div class="row edit_oe_ovary_right_details">
                                                    @if (isset($historyOe->ovary->right->updated_details) && !empty($historyOe->ovary->right->updated_details))
                                                        @foreach ($historyOe->ovary->right->updated_details as $key => $value)
                                                            <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->right->details[$key]) . '_right' }}">
                                                                {{Form::text('data[oe][ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                                    'class' => 'form-control edited_oe_ovary_right_details',
                                                                    'id' => preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->right->details[$key])
                                                                ])}}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-1">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[oe][ovary][ovary_type][]','left',in_array('left',$ovaryType),['id'=>'oe_left','class'=>'plan-management'])}}
                                                    <label for="oe_left">
                                                        Left
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="{{'col-md-10'}}">
                                                <div class="row">
                                                    {{-- <div class="col-md-3">
                                                        <div class="form-group">
                                                            {{Form::select("data[oe][ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ovary->left->type) ? $historyOe->ovary->left->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="{{'col-md-5 complain-multi'}} ">
                                                        {{Form::select("data[oe][ovary][left][details][]",$leftOvaryData,!empty($historyOe->ovary->left->details) ? $historyOe->ovary->left->details : null,[
                                                            'class'=>'form-control co-value co_value_data history-oe-ovary-left-details',
                                                            'placeholder'=>'Abnormal Details',
                                                            'id' => 'oe_ovary_left_details',
                                                            'multiple'=>true,
                                                            'data-id' => '2',
                                                        ])}}
                                                    </div>
                                                    <div class='col-md-4'>
                                                        <div class="input-group">
                                                            <span class="input-group-addon left-ovary-data overy-popup" data-class="left-ovary-data">AFCS : &nbsp;</span>
                                                            {{Form::text("data[oe][ovary][left][afcs]",!empty($historyOe->ovary->left->afcs) ? $historyOe->ovary->left->afcs : null,['class'=>'form-control left-ovary-data-text'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="{{'row oe-left-details'}}">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-7 complain-multi ml-5">
                                                <div class="row edit_oe_ovary_left_details">
                                                    @if (isset($historyOe->ovary->left->updated_details) && !empty($historyOe->ovary->left->updated_details))
                                                        @foreach ($historyOe->ovary->left->updated_details as $key => $value)
                                                            <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->left->details[$key]) . '_left' }}">
                                                                {{Form::text('data[oe][ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                                    'class' => 'form-control edited_oe_ovary_left_details',
                                                                    'id' => preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->left->details[$key])
                                                                ])}}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    @else
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label class="vertical-form-label pr-0">
                                                    Ovary :
                                                </label>
                                            </div>
                                            @php
                                                $ovaryType = !empty($historyData->ovary->ovary_status) ? $historyData->ovary->ovary_status : [];
                                                $iuiStatus = !empty($historyData->hcg->iui->status) && $historyData->hcg->iui->status == 'yes' ? '' : 'd-none';
                                                $hcgType = !empty($historyData->hcg->type) && $historyData->hcg->type == 'yes' ? '' : 'd-none';
                                            @endphp
                                            <div class="col-md-1">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[ovary][ovary_status][]','right','',['id'=>'oe_right','class'=>'plan-management oe-type','data-id'=>'right-data'])}}
                                                    <label for="oe_right">
                                                        Right
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{Form::text("data[ovary][ovary_type][right][details]",'',['class'=>'form-control third-visit-right-ovary-data-text','placeholder'=>'Details'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0)" class="third-visit-right-ovary-data overy-popup" data-class='third-visit-right-ovary-data'>Keyboard</a>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[ovary][ovary_status][]','left','',['id'=>'oe_left_data','class'=>'plan-management oe-type','data-id'=>'left-data'])}}
                                                    <label for="oe_left_data">
                                                        Left
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{Form::text("data[ovary][ovary_type][left][details]",'',['class'=>'form-control third-visit-left-ovary-data-text','placeholder'=>'Details'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0)" class="third-visit-left-ovary-data overy-popup" data-class='third-visit-left-ovary-data'>Keyboard</a>
                                            </div>
                                        </div>
                                        <br/>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-2 pr-0">
                                            <label class="vertical-form-label pr-0">
                                                Endometrial Cavity :
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::text("data[oe][endometrial_cavity][cavity]",!empty($historyOe->endometrial_cavity->cavity) ? $historyOe->endometrial_cavity->cavity : null,['class'=>'form-control','placeholder'=>'Endometrial Cavity Details'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Size : &nbsp;</span>
                                                {{Form::text("data[oe][endometrial_cavity][size]",!empty($historyOe->endometrial_cavity->size) ? $historyOe->endometrial_cavity->size : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <span class="col-md-1 p-2">MM</span>
                                    </div>
                                    <br>
                                {{-- @endif --}}
                                {{-- ivf comman form --}}
                                
                                <div class="row mt-1">
                                    @if($pStatus == 3)
                                    <div class="col-md-2">
                                        <label class="vertical-form-label pr-0">
                                            Semen Freezing :
                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="radio is-conceived">
                                            {{Form::radio("data[collected][frozen][type]",'yes','',['id'=>'progesteroneyes'])}}
                                            <label for="progesteroneyes">
                                                Yes
                                            </label>
                                            {{Form::radio("data[collected][frozen][type]",'no','',['id'=>'progesteroneno'])}}
                                            <label for="progesteroneno">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    @if($pStatus == 3 || $pStatus == 4)
                                    {{Form::hidden('ivf_report_id', '' , ['id' => 'ivf_report_id'])}}
                                    <div class="col-md-2">
                                        <label class="vertical-form-label pr-0">
                                            Embroy Ready :
                                        </label>
                                    </div>
                                    <div class="col-md-2 embroy-yes">
                                        <div class="radio is-conceived">
                                            {{Form::radio("data[collected][report][embroy][type]",'yes','',['id'=>'embroyyes'])}}
                                            <label for="embroyyes">
                                                Yes
                                            </label>
                                            {{Form::radio("data[collected][report][embroy][type]",'no','',['id'=>'embroyno'])}}
                                            <label for="embroyno">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-1 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Hystroscopy :
                                        </label>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <div class="radio is-conceived">
                                            {{Form::radio("investigation[hystroscopy][type]",'yes','',['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                            <label for="hystroscopy_type_yes">
                                                Yes
                                            </label>
                                            {{Form::radio("investigation[hystroscopy][type]",'no','',['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                            <label for="hystroscopy_type_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 hystroscopy-type d-none">
                                        <div class="hystroscopy-images"></div>
                                        {{-- <div class="row">
                                            <div class="col-md-4">
                                                {{Form::file('investigation[hystroscopy][images][]',['class'=>'form-control hystroscopy-file',"multiple"=>'true'])}}
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-1 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Laproscopy :
                                        </label>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <div class="radio is-conceived">
                                            {{Form::radio("investigation[laproscopy][type]",'yes','',['id'=>'laproscopy_type_yes','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                                            <label for="laproscopy_type_yes">
                                                Yes
                                            </label>
                                            {{Form::radio("investigation[laproscopy][type]",'no','',['id'=>'laproscopy_type_no','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                                            <label for="laproscopy_type_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 laproscopy-type d-none">
                                        <div class="laproscopy-images"></div>
                                        {{-- <div class="row">
                                            <div class="col-md-4">
                                                {{Form::file('investigation[laproscopy][images][]',['class'=>'form-control laproscopy-file',"multiple"=>'true'])}}
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                
                                @if($visit != 2 &&   $cycleNumber == $cycleNumber)
                                    <div class="row mt-1">
                                        <div class="col-md-2 pr-0">
                                            <label class="vertical-form-label pr-0">
                                                ET :
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::text("data[et_details]",'',['class'=>'form-control','placeholder'=>'Enter ET Details'])}}
                                        </div>
                                    </div>
                                    
                                    {{Form::hidden('is_trigger','yes')}}
                                    @php
                                        $collectionEmbroyValueData = !empty($historyData->collected->report) && !empty($historyData->collected->report->embroy->type) && $historyData->collected->report->embroy->type == 'yes' ? false : true;
                                    @endphp
                                    @if($collectionEmbroyValueData)
                                        
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Reason : &nbsp;</span>
                                                    {{Form::text('reason',!empty($ivfReport->reason) ? $ivfReport->reason : null ,['class'=>'form-control reason'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Date : &nbsp;</span>
                                                    {{Form::text("report_date",\Carbon\Carbon::parse($lastAppointment->date)->format('D d M Y'),['class'=>'form-control datetimepicker report_date','required'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('report_date')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Volume : &nbsp;</span>
                                                    {{Form::number('volume[pre]',!empty($volume->pre) ? $volume->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('volume[post]',!empty($volume->post) ? $volume->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Sperm Count/ml : &nbsp;</span>
                                                    {{Form::number('sperm[pre]',!empty($sperm_count->pre) ? $sperm_count->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('sperm[post]',!empty($sperm_count->post) ? $sperm_count->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Total Motility (%): &nbsp;</span>
                                                    {{Form::number('motility[pre]',!empty($total_motility->pre) ? $total_motility->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('motility[post]',!empty($total_motility->post) ? $total_motility->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Actively Motile (%) : &nbsp;</span>
                                                    {{Form::number('actively[pre]',!empty($actively->pre) ? $actively->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('actively[post]',!empty($actively->post) ? $actively->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Sliggishly Motile (%) : &nbsp;</span>
                                                    {{Form::number('sluggishly[pre]',!empty($sluggishly->pre) ? $sluggishly->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('sluggishly[post]',!empty($sluggishly->post) ? $sluggishly->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Non-motile (%) : &nbsp;</span>
                                                    {{Form::number('motile[pre]',!empty($non_motile->pre) ? $non_motile->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('motile[post]',!empty($non_motile->post) ? $non_motile->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Normal Morphology : &nbsp;</span>
                                                    {{Form::number('morphology[pre]',!empty($morphology->pre) ? $morphology->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('morphology[post]',!empty($morphology->post) ? $morphology->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row embroy-button d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Pus cells/hpf : &nbsp;</span>
                                                    {{Form::number('cells[pre]',!empty($pus_cells->pre) ? $pus_cells->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                                    {{Form::number('cells[post]',!empty($pus_cells->post) ? $pus_cells->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        {{Form::hidden('data[collection][]','collected')}}
                                        {{Form::hidden('data[collected][frozen][type]',!empty($historyData->collected->frozen->report) ? $historyData->collected->frozen->report : null)}}
                                        {{Form::hidden('data[collected][date]',!empty($historyData->collected->date) ? $historyData->collected->date : null)}}
                                        {{Form::hidden('data[collected][report][embroy][status]',!empty($historyData->collected->report->embroy->status) ? $historyData->collected->report->embroy->status : null)}}
                                        {{Form::hidden('data[collected][report][embroy][type]',!empty($historyData->collected->report->embroy->type) ? $historyData->collected->report->embroy->type : null)}}
                                    @endif
                                    <div class="row mt-1">
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                {{Form::checkbox('data[collection][]','progesterone','',['id'=>'progesterone'])}}
                                                <label for="progesterone">
                                                    Progesterone supplementation?
                                                </label>
                                            </div>
                                        </div>
                                        @if($pStatus == 1)
                                            <div class="col-md-2 progesterone_data d-none">
                                                <label for="progesterone">
                                                    Same Cycle Transfer?
                                                </label>
                                            </div>
                                            <div class="col-md-2 progesterone_data d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[progesterone][status]",'yes','',['id'=>'progesterone_yes'])}}
                                                    <label for="progesterone_yes">
                                                        Yes
                                                    </label>
                                                    {{Form::radio("data[progesterone][status]",'no','',['id'=>'progesterone_no'])}}
                                                    <label for="progesterone_no">
                                                    No
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                        @php
                                            $class = 'progesterone_data';
                                            if($pStatus == 1){
                                                $class = 'progesterone_yes';
                                            }
                                        @endphp
                                        <div class="{{'col-md-2 d-none '.$class}}">
                                            <div class="radio is-conceived">
                                                {{Form::radio("data[progesterone][type]",'day_3','',['id'=>'day_3','class'=>'progesterone-type'])}}
                                                <label for="day_3">
                                                    Day-3
                                                </label>
                                                {{Form::radio("data[progesterone][type]",'day_5','',['id'=>'day_5','class'=>'progesterone-type'])}}
                                                <label for="day_5">
                                                    Day-5
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty($historyData->trigger->hcg->status) || !empty($historyData->trigger->decapeptyl->status) || !empty($historyData->trigger->dualtrigger->stauts) )
                                        @php
                                            $trigger='trigger';
                                            $hcg = !empty($historyData->trigger->hcg->status) ? $historyData->trigger->hcg->status : null;
                                            $hcg_time = !empty($historyData->trigger->hcg->time) ? $historyData->trigger->hcg->time : null;
                                            $hcg_dose = !empty($historyData->trigger->hcg->dose) ? $historyData->trigger->hcg->dose : null;
                                            $hcg_brand = !empty($historyData->trigger->hcg->brand) ? $historyData->trigger->hcg->brand : null;
                                        @endphp
                                        @php
                                            $decapeptyl = !empty($historyData->trigger->decapeptyl->status) ? $historyData->trigger->decapeptyl->status : null;
                                            $decapeptyl_time = !empty($historyData->trigger->decapeptyl->time) ? $historyData->trigger->decapeptyl->time : null;
                                            $decapeptyl_dose = !empty($historyData->trigger->decapeptyl->dose) ? $historyData->trigger->decapeptyl->dose : null;
                                            $decapeptyl_brand = !empty($historyData->trigger->decapeptyl->brand) ? $historyData->trigger->decapeptyl->brand : null;
                                        @endphp
                                        @php
                                            $dule = !empty($historyData->trigger->dualtrigger->stauts) ? $historyData->trigger->dualtrigger->stauts : null;
                                        @endphp
                                        {{Form::hidden("data[collection][]",$trigger)}}
                                        {{Form::hidden("data[trigger][hcg][status]",$hcg)}}
                                        {{Form::hidden("data[trigger][hcg][time]",$hcg_time)}}
                                        {{Form::hidden("data[trigger][hcg][dose]",$hcg_dose)}}
                                        {{Form::hidden("data[trigger][hcg][brand]",$hcg_brand)}}
                                        {{Form::hidden("data[trigger][decapeptyl][status]",$decapeptyl)}}
                                        {{Form::hidden("data[trigger][decapeptyl][time]",$decapeptyl_time)}}
                                        {{Form::hidden("data[trigger][decapeptyl][dose]",$decapeptyl_dose)}}
                                        {{Form::hidden("data[trigger][decapeptyl][brand]",$decapeptyl_brand)}}
                                        {{Form::hidden("data[trigger][dualtrigger][stauts]",$dule)}}
                                        {{Form::hidden("data[trigger][update_status]",'yes')}}
                                    @else
                                        @if($pStatus == 1)
                                            {{Form::hidden("data[trigger][update_status]",'no')}}
                                            <div class="row mt-1">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[collection][]','trigger','',['id'=>'trigger'])}}
                                                        <label for="trigger">
                                                            Trigger
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 trigger d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Trigger Date: &nbsp;</span>
                                                        {{Form::text("data[trigger_date]", \Carbon\Carbon::now()->format('D d M Y'), ['class'=>'form-control history-lmd-date'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="trigger d-none ml-3">
                                            <div class="row mt-1">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[trigger][hcg][status]','hcg','',['id'=>'hcg'])}}
                                                        <label for="hcg">
                                                            HCG
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="hcgtrigger d-none">
                                                    <div class="row ml-3">
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Time : &nbsp;</span>
                                                                {{Form::text("data[trigger][hcg][time]",'',['class'=>'form-control timepicker time','id'=>'hcg_time','placeholsder'=>'Brand'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Dose : &nbsp;</span>
                                                                {{Form::text("data[trigger][hcg][dose]",'10000',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Brand : &nbsp;</span>
                                                                {{Form::text("data[trigger][hcg][brand]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[trigger][decapeptyl][status]','decapeptyl','',['id'=>'decapeptyl'])}}
                                                        <label for="decapeptyl">
                                                            Decapeptyl
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="decapeptyltrigger d-none">
                                                    <div class="row ml-3">
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Time : &nbsp;</span>
                                                                    {{Form::text("data[trigger][decapeptyl][time]",'',['class'=>'form-control timepicker time','placeholsder'=>'Brand'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Dose : &nbsp;</span>
                                                            {{Form::text("data[trigger][decapeptyl][dose]",'1,2',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Brand : &nbsp;</span>
                                                            {{Form::text("data[trigger][decapeptyl][brand]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[trigger][dualtrigger][stauts]','dualtrigger','',['id'=>'dualtrigger'])}}
                                                        <label for="dualtrigger">
                                                            Dule Trigger
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                   
                                    @if($pStatus != 3 )
                                        {{-- <div class="row mt-1">
                                            <div class="col-md-2">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[collection][]','usg','',['id'=>'usg'])}}
                                                    <label for="usg">
                                                    USG
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --}}
                                    @endif
                                    <div class="row">
                                        <div class="col-md-1 pr-0">
                                            <div class="checkbox">
                                                {{Form::checkbox('data[p_s][type]','yes','',['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                                                <label for="ps_status">
                                                    P/S :
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 ps-details d-none">
                                            <div class="form-group">
                                                {{Form::text("data[p_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                            </div>
                                        </div>
                                    </div>
                                    @if($pStatus != 1 )
                                        <div class="row mt-1">
                                            {{-- <div class="col-md-1">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[collection][]','transfer','',['id'=>'transfer','class'=>'transfer'])}}
                                                    <label for="transfer">
                                                        Transfer
                                                    </label>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-5 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Payment By: &nbsp;</span>
                                                    {{Form::text("data[transfer][payment]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-5 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Payment Method: &nbsp;</span>
                                                    {{Form::text("data[transfer][method]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Indication: &nbsp;</span>
                                                    {{Form::text("indication",'',[
                                                        'class'=>'form-control',
                                                        'maxlength' => 250
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">ET Date: &nbsp;</span>
                                                    {{Form::text("et_date", \Carbon\Carbon::now()->addDays(1)->format('D d M Y'), ['class'=>'form-control datetimepicker'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Day: &nbsp;</span>
                                                    {{Form::text("day",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Endo. Thickness: &nbsp;</span>
                                                    {{Form::text("endo_thickness",'',[
                                                        'class'=>'form-control',
                                                        'maxlength' => 250
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">ET Procedure: &nbsp;</span>
                                                    {{Form::text("et_procedure",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Embryos Transferred: &nbsp;</span>
                                                    {{Form::text("embryos_transferred",'',['class'=>'form-control', 'maxlength' => 250])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Frozen Embryos: &nbsp;</span>
                                                    {{Form::text("frozen_embryos",'',[
                                                        'class'=>'form-control',
                                                        'maxlength' => 250
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Pick up Date: &nbsp;</span>
                                                    {{Form::text("pick_up_date",!empty($pickupDate) ? $pickupDate : \Carbon\Carbon::now()->addDays(1)->format('D d M Y'), ['class'=>'form-control datetimepicker'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Simulation Protocol: &nbsp;</span>
                                                    {{Form::text("simulation_protocol",$sProtocol,['class'=>'form-control', 'maxlength' => 250])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Total OCC: &nbsp;</span>
                                                    {{Form::text("total_occ",$totalocc,[
                                                        'class'=>'form-control number',
                                                        'maxlength' => 250,
                                                        'id' => 'total_occ'
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Mll: &nbsp;</span>
                                                    {{Form::text("mll",$mii,['class'=>'form-control number', 'maxlength' => 250, 'id' => 'mll'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Ml: &nbsp;</span>
                                                    {{Form::text("ml",$mi,['class'=>'form-control number', 'maxlength' => 250, 'id' => 'ml'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">GV: &nbsp;</span>
                                                    {{Form::text("gv",$gv,[
                                                        'class'=>'form-control number',
                                                        'maxlength' => 250,
                                                        'id' => 'gv'
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Oocyte Quality: &nbsp;</span>
                                                    {{Form::text("oocycle_quality",$quality,['class'=>'form-control', 'maxlength' => 250])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Sperm Quality: &nbsp;</span>
                                                    {{Form::text("sperm_quality",$sperm,['class'=>'form-control', 'maxlength' => 250])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-4 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Fertilization Procedure: &nbsp;</span>
                                                    {{Form::text("fertilization_procedure",'',[
                                                        'class'=>'form-control',
                                                        'maxlength' => 250
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-7 transfer-data d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Remark: &nbsp;</span>
                                                    {{Form::text("transfer_remark",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <div class="row mt-1">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('data[collection][]','blood','',['id'=>'blood'])}}
                                            <label for="blood">
                                            Blood Report
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 bloodreport d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Blood report: &nbsp;</span>
                                                    {{Form::text("data[blood_report][report]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                <div class="blood-images"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('data[collection][]','usg','',['id'=>'usg'])}}
                                            <label for="usg">
                                                USG Report
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 usgreport d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">USG report: &nbsp;</span>
                                                    {{Form::text("data[usg][report]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                <div class="usg-images"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('data[collection][]','hsa','',['id'=>'hsa'])}}
                                            <label for="hsa">
                                            HSA Report
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 hsareport d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">HSA report: &nbsp;</span>
                                                    {{Form::text("data[hsa_report][report]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                {{-- {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}} --}}
                                                <div class="hsa-images"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- end ivf comman form --}}
                                <br>
                                {{-- pre operative data --}}
                                <div class="row">
                                    <div class="col-md-1 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            C/O :
                                        </label>
                                    </div>
                                    <div class="col-md-9 complain-multi">
                                        {{Form::select('data[co_type][]',$complaints,'',['class'=>'form-control co-value co_value_data complaint-data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'1','data-medicine'=>1])}}
                                    </div>
                                </div>
                                <br>
                                @if(empty($historyData->pre_operative->type) OR $historyData->pre_operative->type == 'no' && $cycleNumber == $cycleNumber)
                                    @if($pStatus != 1 && $visit == 2)
                                        @foreach($investigationArray as $key => $value)
                                            <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                                                <div class="panel panel-primary investigation">
                                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                                        <h4 class="panel-title"> <a class="" role="button" data-toggle="collapse" data-parent="#investigation{{$key}}" href="#investigation{{$key}}" aria-expanded="true"
                                                                                    aria-controls="investigation">Investigation - {{($value == 'hub' ? 'Husband' : 'Wife')}}</a></h4>
                                                    </div>
                                                    <div id="investigation{{$key}}" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                                        <div class="panel-body">
                                                            {{-- report data --}}
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','1','',['id'=>$key.'cbc_mp','class'=>'plan-management','data-id'=>$key.'cbc-mp-details'])}}
                                                                        <label for="{{$key}}cbc_mp">
                                                                            CBC / MP
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'cbc-mp-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][1]",null,['class'=>'form-control','placeholder'=>'CBC MP Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','2',null,['id'=>$key.'fbs','class'=>'plan-management','data-id'=>$key.'fbs-data-details'])}}
                                                                        <label for="{{$key}}fbs">
                                                                            FBS
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'fbs-data-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][2]",null,['class'=>'form-control','placeholder'=>'FBS Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="{{'row '.$key.'cbc-mp-details d-none'}}">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        {{Form::select("investigation[".$value."][investigation_cbc_mp_details][status]",$wnlArray,null,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>$key.'cbc-mb-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-3 '.$key.'cbc-mb-type-details-value '}}">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            Aneamia : &nbsp;
                                                                        </span>
                                                                        {{Form::text("investigation[".$value."][investigation_cbc_mp_details][aneamia]",null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-3 '.$key.'cbc-mb-type-details-value '}}">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            Leacocytosis : &nbsp;
                                                                        </span>
                                                                        {{Form::text("investigation[".$value."][investigation_cbc_mp_details][leacocytosis]",null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','3',null,['id'=>$key.'urine_r','class'=>'plan-management','data-id'=>$key.'urine-details'])}}
                                                                        <label for="{{$key}}urine_r">
                                                                            Urine - R
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'urine-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][3]",null,['class'=>'form-control','placeholder'=>'Urine Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','4',null,['id'=>$key.'ppbs','class'=>'plan-management','data-id'=>$key.'ppbs-data-details'])}}
                                                                        <label for="{{$key}}ppbs">
                                                                            PPBS
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'ppbs-data-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][4]",null,['class'=>'form-control','placeholder'=>'PPBS Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="{{'row '.$key.'urine-details d-none'}}">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        {{Form::select("investigation[".$value."][investigation_urine_value_details][status]",$wnlArray,null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>$key.'urine-details-value','placeholder'=>'Select CBC MB Type'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-3 '.$key.'urine-details-value '}}">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            Aneamia : &nbsp;
                                                                        </span>
                                                                        {{Form::text("investigation[".$value."][investigation_urine_value_details][aneamia]",null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-3 '.$key.'urine-details-value '}}">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            Leacocytosis : &nbsp;
                                                                        </span>
                                                                        {{Form::text("investigation[".$value."][investigation_urine_value_details][leacocytosis]",null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','5',null,['id'=>$key.'esr','class'=>'plan-management','data-id'=>$key.'esr-details'])}}
                                                                        <label for="{{$key}}esr">
                                                                            ESR
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'esr-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][5]", null,['class'=>'form-control','placeholder'=>'ESR Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','6',null,['id'=>$key.'rbs','class'=>'plan-management','data-id'=>$key.'rbs-data-details'])}}
                                                                        <label for="{{$key}}rbs">
                                                                            RBS
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'rbs-data-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][6]", null,['class'=>'form-control','placeholder'=>'RBS Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','7',null,['id'=>$key.'sgpt','class'=>'plan-management','data-id'=>$key.'sgpt-details'])}}
                                                                        <label for="{{$key}}sgpt">
                                                                            SGPT
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'sgpt-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][7]",null,['class'=>'form-control','placeholder'=>'SGPT Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','8',null,['id'=>$key.'hbsag','class'=>'plan-management','data-id'=>$key.'hbsag-details'])}}
                                                                        <label for="{{$key}}hbsag">
                                                                            HBsAg
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'hbsag-details d-none'}}">
                                                                    <div class="form-group">
                                                                        <div class="radio is-conceived">
                                                                            {{Form::radio("investigation[".$value."][investigation_details][8]",'positive',false,['id'=>$key.'hbsag-positive','class'=>'hbsag-pickup'])}}
                                                                            <label for="{{$key.'hbsag-positive'}}">
                                                                            Positive
                                                                            </label>
                                                                            {{Form::radio("investigation[".$value."][investigation_details][8]",'negative',false,['id'=>$key.'hbsag-negative','class'=>'hbsag-pickup'])}}
                                                                            <label for="{{$key.'hbsag-negative'}}">
                                                                                Negative
                                                                            </label>
                                                                        </div>
                                                                        {{-- {{Form::text("investigation[".$value."][investigation_details][8]",null,['class'=>'form-control','placeholder'=>'HBsAg Details'])}} --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','9', null,['id'=>$key.'screatinine','class'=>'plan-management','data-id'=>$key.'screatinine-details'])}}
                                                                        <label for="{{$key}}screatinine">
                                                                            S.Creatinine
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'screatinine-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][9]",null,['class'=>'form-control','placeholder'=>'S. Creatinine Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','10',null,['id'=>$key.'hiv','class'=>'plan-management','data-id'=>$key.'hiv-details'])}}
                                                                        <label for="{{$key}}hiv">
                                                                            HIV
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'hiv-details d-none'}}">
                                                                    <div class="form-group">
                                                                        <div class="radio is-conceived">
                                                                            {{Form::radio("investigation[".$value."][investigation_details][10]",'positive',false,['id'=>$key.'hiv-positive','class'=>'hbsag-pickup'])}}
                                                                            <label for="{{$key.'hiv-positive'}}">
                                                                            Positive
                                                                            </label>
                                                                            {{Form::radio("investigation[".$value."][investigation_details][10]",'negative',false,['id'=>$key.'hiv-negative','class'=>'hbsag-pickup'])}}
                                                                            <label for="{{$key.'hiv-negative'}}">
                                                                                Negative
                                                                            </label>
                                                                        </div>
                                                                        {{-- {{Form::text("investigation[".$value."][investigation_details][10]",null,['class'=>'form-control','placeholder'=>'HIV Details'])}} --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','11',null,['id'=>$key.'crp','class'=>'plan-management','data-id'=>$key.'crp-details'])}}
                                                                        <label for="{{$key}}crp">
                                                                            CRP
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'crp-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][11]",null,['class'=>'form-control','placeholder'=>'CRP Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','12',null,['id'=>$key.'blood_group','class'=>'plan-management','data-id'=>$key.'blood-details'])}}
                                                                        <label for="{{$key}}blood_group">
                                                                            Blood Group
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-5 '.$key.'blood-details d-none'}}">
                                                                    <div class="form-group">
                                                                        <div class="radio is-conceived">
                                                                            @foreach($blood_groupArray as $index => $blood_name)
                                                                                {{Form::radio("investigation[".$value."][investigation_details][12]",$blood_name,'',['id'=>$key.$blood_name,'class'=>'during-pickup'])}}
                                                                                <label for="{{$key.$blood_name}}">
                                                                                {{$blood_name}}
                                                                                </label>
                                                                            @endforeach
                                                                        </div>
                                                                        {{-- {{Form::text("investigation[".$value."][investigation_details][12]",null,['class'=>'form-control','placeholder'=>'Blood Group Details'])}} --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','13',null,['id'=>$key.'slied','class'=>'plan-management','data-id'=>$key.'slied-details'])}}
                                                                        <label for="{{$key}}slied">
                                                                            Serum Widal
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'slied-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[$value][investigation_details][13]",null,['class'=>'form-control','placeholder'=>'Serum Widal Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','14',null,['id'=>$key.'tsh','class'=>'plan-management','data-id'=>$key.'tsh-data-details'])}}
                                                                        <label for="{{$key}}tsh">
                                                                            TSH
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'tsh-data-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][14]", null,['class'=>'form-control','placeholder'=>'TSH Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="{{'row '.$key.'tsh-data-details d-none'}}">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        {{Form::select("investigation[".$value."][investigation_tsh_value_details][status]",$wnlArray,null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>$key.'tsh-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-3 '.$key.'tsh-type-details-value d-none'}}">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            Aneamia : &nbsp;
                                                                        </span>
                                                                        {{Form::text("investigation[".$value."][investigation_tsh_value_details][aneamia]",null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-3 '.$key.'tsh-type-details-value d-none'}}">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            Leacocytosis : &nbsp;
                                                                        </span>
                                                                        {{Form::text("investigation[".$value."][investigation_tsh_value_details][leacocytosis]",null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','15', null,['id'=>$key.'typhidot','class'=>'plan-management','data-id'=>$key.'typhidot-lgm-details'])}}
                                                                        <label for="{{$key}}typhidot">
                                                                            Typhidot lgM
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'typhidot-lgm-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][15]",null,['class'=>'form-control','placeholder'=>'Typhidot lgM Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','16',null,['id'=>$key.'t3','class'=>'plan-management','data-id'=>$key.'t3-details'])}}
                                                                        <label for="{{$key}}t3">
                                                                            T3, T4, TSH
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'t3-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][16]", null,['class'=>'form-control','placeholder'=>'T3, T4, TSH Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','17',null,['id'=>$key.'lipid_profile','class'=>'plan-management','data-id'=>$key.'lipid-profile-details'])}}
                                                                        <label for="{{$key}}lipid_profile">
                                                                            Lipid Profile
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'lipid-profile-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][17]", null,['class'=>'form-control','placeholder'=>'Lipid Profile Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','18',null,['id'=>$key.'vitb12','class'=>'plan-management','data-id'=>$key.'vit-b12-details'])}}
                                                                        <label for="{{$key}}vitb12">
                                                                            Vit B-12
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'vit-b12-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][18]",null,['class'=>'form-control','placeholder'=>'Vit B-12 Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','19', null,['id'=>$key.'tube-widal','class'=>'plan-management','data-id'=>$key.'tube-widal-details'])}}
                                                                        <label for="{{$key}}tube-widal">
                                                                            Tube Widal
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'tube-widal-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][19]",null,['class'=>'form-control','placeholder'=>'Tube Widal Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','20',null,['id'=>$key.'vitd3','class'=>'plan-management','data-id'=>$key.'vit-d3-details'])}}
                                                                        <label for="{{$key}}vitd3">
                                                                            Vit D-3
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'vit-d3-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][20]",null,['class'=>'form-control','placeholder'=>'Vit D-3 Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','21',null,['id'=>$key.'lft','class'=>'plan-management','data-id'=>$key.'lft-details'])}}
                                                                        <label for="{{$key}}lft">
                                                                            LFT
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'lft-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][21]", null,['class'=>'form-control','placeholder'=>'LFT Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','22',null,['id'=>$key.'anc_profile','class'=>'plan-management','data-id'=>$key.'anc-profile-details'])}}
                                                                        <label for="{{$key}}anc_profile">
                                                                            ANC Profile
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'anc-profile-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][22]",null,['class'=>'form-control','placeholder'=>'ANC Profile Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','23',null,['id'=>$key.'rft','class'=>'plan-management','data-id'=>$key.'rft-details'])}}
                                                                        <label for="{{$key}}rft">
                                                                            RFT
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'rft-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][23]",null,['class'=>'form-control','placeholder'=>'RFT Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','24',null,['id'=>$key.'pre_major','class'=>'plan-management','data-id'=>$key.'pre-major-profile-details'])}}
                                                                        <label for="{{$key}}pre_major">
                                                                            Pre oper.Profile(Major)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'pre-major-profile-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][24]",null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Major) Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','25',null,['id'=>$key.'scalcium','class'=>'plan-management','data-id'=>$key.'scalcium-details'])}}
                                                                        <label for="{{$key}}scalcium">
                                                                            S.Calcium
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'scalcium-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][25]",null,['class'=>'form-control','placeholder'=>'S. Calcium Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','26',null,['id'=>$key.'pre_minor','class'=>'plan-management','data-id'=>$key.'pre-minor-profile-details'])}}
                                                                        <label for="{{$key}}pre_minor">
                                                                            Pre oper.Profile(Minor)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'pre-minor-profile-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][26]",null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Minor) Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','27',null,['id'=>$key.'eletrolytes','class'=>'plan-management','data-id'=>$key.'eletrolytes-details'])}}
                                                                        <label for="{{$key}}eletrolytes">
                                                                            S.Eletrolytes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'eletrolytes-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][27]",null,['class'=>'form-control','placeholder'=>'S. Eletrolytes Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','28',null,['id'=>$key.'denue_duo','class'=>'plan-management','data-id'=>$key.'denue-duo-details'])}}
                                                                        <label for="{{$key}}denue_duo">
                                                                            Dengue Duo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'denue-duo-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][28]",null,['class'=>'form-control','placeholder'=>'Dengue Duo Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','29',null,['id'=>$key.'billirubin','class'=>'plan-management','data-id'=>$key.'billirubin-details'])}}
                                                                        <label for="{{$key}}billirubin">
                                                                            S.Billirubin
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'billirubin-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][29]",null,['class'=>'form-control','placeholder'=>'S. billirubin Details'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','30',null,['id'=>$key.'denue_ns1','class'=>'plan-management','data-id'=>$key.'denue-ns1-details'])}}
                                                                        <label for="{{$key}}denue_ns1">
                                                                            Dengue NS1
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'denue-ns1-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][30]", null,['class'=>'form-control','placeholder'=>'Dengue NS1 Details'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="checkbox">
                                                                        {{Form::checkbox('investigation['.$value.'][investigation_data][]','31',null,['id'=>$key.'hb','class'=>'plan-management','data-id'=>$key.'hb-data-details'])}}
                                                                        <label for="{{$key}}hb">
                                                                            HB
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="{{'col-md-4 '.$key.'hb-data-details d-none'}}">
                                                                    <div class="form-group">
                                                                        {{Form::text("investigation[".$value."][investigation_details][31]",null,['class'=>'form-control','placeholder'=>'HB Details'])}}
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    
                                @endif
                                {{-- end pre operative data --}}
                                
                                    
                                    @if($pStatus == 1)
                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    S.E2 :
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                {{Form::text("data[s_e2]",'',['class'=>'form-control','placeholder'=>'S.E2'])}}
                                            </div>
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    S.LH :
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                {{Form::text("data[s_lh]",'',['class'=>'form-control','placeholder'=>'S.LH'])}}
                                            </div>
                                            <label class="vertical-form-label pr-0">
                                                Hystroscopy During Pickup :
                                            </label>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[during_pickup]",'yes','',['id'=>'during_pickup_yes','class'=>'during-pickup'])}}
                                                    <label for="during_pickup_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("data[during_pickup]",'no','',['id'=>'during_pickup_no','class'=>'during-pickup'])}}
                                                    <label for="during_pickup_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="pt-3 pb-3 pl-2"> Treatment </div>
                                    <div id="treatment" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body" id="parent">
                                            <div class="row treatment-data" id="t_data_1">
                                                <div class="col-md-2 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Select Medicine :
                                                    </label>
                                                </div>
                                                <div class="col-md-9 complain-multi medicine-picker">
                                                    {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','placeholder' =>'Enter medicine name'])}}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="page-loader-wrapper medicine-loader d-none">
                                                <div class="loader">
                                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                                </div>
                                            </div>
                                            <div class="medicine-data treatment-medicine-data">

                                            </div>
                                            {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                                        </div>
                                    </div>
                                    @if($pStatus == 1)
                                        <a class="btn btn-primary btn-icon btn-icon-mini btn-round add-row d-none" data-id="5" data-day="0"><i class="material-icons">add</i></a>
                                        {{-- table append for protocol --}}
                                        <div class="protocol-table"></div>
                                        <br>
                                    @endif
                                    <br>
                                     <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{Form::textarea('data[remark]','', ['class' => 'form-control no-resize remark call-response','placeholder' => 'Remark','rows' => '5'])}}
                                                <span class="transfer-error text-danger mb-2"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                {{Form::textarea("data[pt_remark]",'',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'5'])}}
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Follow Up: &nbsp;</span>
                                                @if($pStatus != 1)
                                                    {{Form::text("data[follow_up]",\Carbon\Carbon::now()->addDays(1)->format('D d M Y'),['class'=>'form-control tranfer-follow-date datetimepicker lmp-date-follow-up next-date'])}}
                                                @else
                                                    {{Form::text("data[follow_up]",'',['class'=>'form-control datetimepicker tranfer-follow-date follow-up-date next-date'])}}
                                                @endif
                                                {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                {{Form::text("followUp-dateDiff",'',['class'=>'form-control history-follow-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-3 plan-transfer-data">
                                            <div class="form-group">
                                                {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan-transfer','placeholder'=>'Select Plan Transfer'])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-2 plan-transfer-data plan-transfer-data-type d-none">
                                            <div class="radio is-conceived">
                                                {{Form::radio("data[transfer_type]",'new',true,['id'=>'transfer-type-new','class'=>'upt-type'])}}
                                                <label for="transfer-type-new">
                                                    NEW
                                                </label>

                                                {{Form::radio("data[transfer_type]",'old','',['id'=>'transfer-type-old','class'=>'upt-type'])}}
                                                <label for="transfer-type-old">
                                                    OLD
                                                </label>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row skip-data">
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                {{Form::checkbox('data[skip_cycle]','yes','',['class'=>'skip-cycle','id'=>'skip-pick-up','data-id'=>'skip-cycle-data','data-plan="pick-up"'])}}
                                                <label for="skip-pick-up" class="text-danger">
                                                    Do you want to skip this cycle?
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 skip-cycle-data d-none">
                                            <div class="form-group">
                                                {{Form::text("data[skip_reason]",'',['class'=>'form-control skip-reason','placeholder'=>'Enter Reason'])}}
                                            </div>
                                            <span class="skip-reason-error form-error-msg"></span>
                                        </div>
                                        <div class="col-md-3 skip-cycle-data d-none">
                                            <div class="form-group">
                                                {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan skip-plan','placeholder'=>'Select Plan'])}}
                                            </div>
                                            <span class="skip-plan-error form-error-msg"></span>
                                        </div>
                                    </div>
                                    <br>
                                    {{Form::hidden("data[is_upt]",'no')}}
                            @else
                                {{Form::hidden("data[is_upt]",'yes')}}
                                {{Form::hidden('data[is_transfer]','yes',['class'=>'is-transfer'])}}
                                {{Form::hidden('data[is_transfer_print]','yes')}}
                                
                                <div class="row">
                                    {{-- upt --}}
                                    <div class="col-md-1">
                                        <label class="vertical-form-label pr-0">
                                            UPT :
                                        </label>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="radio is-conceived">
                                            {{Form::radio("data[transfer][upt_type]",'positive','',['id'=>'transfer-positive','class'=>'upt-type'])}}
                                            <label for="transfer-positive">
                                                Positive
                                            </label>

                                            {{Form::radio("data[transfer][upt_type]",'negative','',['id'=>'transfer-negative','class'=>'upt-type'])}}
                                            <label for="transfer-negative">
                                                Negative
                                            </label>
                                        </div>
                                    </div>
                                    {{-- result --}}
                                    <div class="col-md-1">
                                        <label class="vertical-form-label pr-0">
                                            Result :
                                        </label>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="radio is-conceived">
                                            {{Form::radio("data[transfer][result_type]",'conceive','',['id'=>'transfer-conceive','class'=>'result-type'])}}
                                            <label for="transfer-conceive">
                                                Conceive
                                            </label>

                                            {{Form::radio("data[transfer][result_type]",'fail','',['id'=>'transfer-fail','class'=>'result-type'])}}
                                            <label for="transfer-fail">
                                                Fail
                                            </label>
                                        </div>
                                    </div>
                                    {{-- end result --}}
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="vertical-form-label pr-0">
                                            Report :
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::file('data[transfer][report][]',['class'=>'form-control',"multiple"=>"multiple"])}}
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Follow Up: &nbsp;</span>
                                            {{Form::text("data[transfer][follow_up]",\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'form-control tf-date datetimepicker'])}}
                                        </div>
                                        {{Form::hidden('data[follow_up]',\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'t-follow-date'])}}
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 plan-transfer-data">
                                        <div class="form-group">
                                            {{Form::select("data[plan]",$planData,'',['class'=>'form-control select-padding-0 plan-transfer','placeholder'=>'Select Plan Transfer'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-2 plan-transfer-data plan-transfer-data-type d-none">
                                        <div class="radio is-conceived">
                                            {{Form::radio("data[transfer_type]",'new',true,['id'=>'transfer-type-new','class'=>'upt-type'])}}
                                            <label for="transfer-type-new">
                                                NEW
                                            </label>

                                            {{Form::radio("data[transfer_type]",'old','',['id'=>'transfer-type-old','class'=>'upt-type'])}}
                                            <label for="transfer-type-old">
                                                OLD
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="row">
                                    <div class="col-md-1 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            C/O :
                                        </label>
                                    </div>
                                    <div class="col-md-9 complain-multi">
                                        {{Form::select('data[co_type][]',$complaints,'',['class'=>'form-control co-value co_value_data complaint-data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'1'])}}
                                    </div>
                                </div>
                                <br>
                                <div id="treatment" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body" id="parent">
                                        <div class="row treatment-data" id="t_data_1">
                                            <div class="col-md-2 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Select Medicine :
                                                </label>
                                            </div>
                                            <div class="col-md-9 complain-multi medicine-picker">
                                                {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','placeholder' =>'Enter medicine name'])}}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="page-loader-wrapper medicine-loader d-none">
                                            <div class="loader">
                                                <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                            </div>
                                        </div>
                                        <div class="medicine-data treatment-medicine-data">

                                        </div>
                                        {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                                    </div>
                                </div>
                                <br>
                            @endif
                            {{Form::hidden('ivf_report',!empty($ivfReport) ? $ivfReport : null,['class'=>'ivf-report-status'])}}
                            {{Form::hidden('ivf_history_id', '' , ['id' => 'ivf_history_id'])}}
                            {{Form::button('submit',['class'=>'btn btn-primary submit'])}}
                            {{Form::hidden('ivf_transfer_report_id', '' , ['id' => 'ivf_transfer_report_id'])}}
                            <button type="submit" class="btn btn-primary submit" value="1">Save & Preview</button>
                            <button type="submit" class="btn btn-primary submit transfer-report d-none" value="5">Transfer Report Preview</button>
                            <a class="btn btn-primary t-print transfer-print d-none" data-id="">Transfer Print</a>
                            <button type="submit" class="btn btn-primary submit d-none" value="3" id="ivf_print"> Print IVF</button>
                            <button type="submit" class="btn btn-primary submit d-none" value="4" id="ivf_report_print"> Save & Print IVF Report</button>
                            <a href="{{URL::previous()}}" class="btn btn-default">Cancel</a>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        @endif
    <div>
@stop
@section('modal')
    <div class="modal fade ivf-report-modal" id="ivfRemarkModal" tabindex="-1" role="dialog">
        <div class="modal-dialog resize-modal-dialog modal-lg modal-sm" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header text-center">
                    <h5 class="title" id="defaultModalLabel">Remark</h5>
                    <button type="button" class="close deposit-close-button" data-dismiss="modal">&times;</button>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'', 'id'=>'addRemark'])}}
                <div class="modal-body text-center">
                    <span class="form-error-msg"></span>
                    {{Form::textarea('remark','', ['class' => 'form-control','id' => 'remark','rows' => '5','placeholder' => 'Remark']) }}
                    {{Form::hidden('ivf-history-id','',['class'=>'ivf-history-id'])}}
                <!-- footer -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary waves-effect submit submit-button submit-transfer-print" value="2">Submit</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade ivf" id="ivf_report_model" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content ivf-report-model">
                <!-- header -->
                <div class="modal-header">
                    <h4 class="title" id="defaultModalLabel">IVF Report</h4>
                </div>
                <!-- body -->

                {{Form::open([
                    'class'=>'form-inline',
                    'id'=>'ivf-report'
                ])}}
                {{Form::hidden('update_ivf_report_id', null,['id'=>'update_ivf_report_id'])}}
                <div class="modal-body">
                    <span class="form-error-msg minimum-charge"></span>
                    {{-- {{Form::hidden('select_appointment_id','',['class'=>'selected-appointment-id'])}} --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Reason :</span>
                                {{Form::text('reason', null ,[
                                    'class'=>'form-control reason',
                                    'id' => 'reason'
                                ])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Date :</span>
                                {{ Form::text("report_date",'' ,[
                                    'class'=>'form-control datetimepicker report_date',
                                    'required',
                                    'id' => 'report_date'
                                ])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('report_date')}}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Volume :</span>
                                {{Form::text('volume[pre]', null, [
                                    'class'=>'form-control pre-volume col-md-3',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_volume'
                                ])}}
                                {{Form::text('volume[post]', null, [
                                    'class'=>'form-control post-volume col-md-3',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_volume'
                                ])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Sperm Count/ml : &nbsp;</span>
                                {{Form::text('sperm[pre]',null,[
                                    'class'=>'form-control pre-sperm col-md-3',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_sperm'
                                ])}}
                                {{Form::text('sperm[post]',null,[
                                    'class'=>'form-control name col-md-3 post-sperm',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_sperm'
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Total Motility (%): &nbsp;</span>
                                {{Form::text('motility[pre]', null,[
                                    'class'=>'form-control name col-md-3 pre-motility',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_motility'
                                ])}}
                                {{Form::text('motility[post]',null,[
                                    'class'=>'form-control name col-md-3 post-motility',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_motility'
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Actively Motile (%) : &nbsp;</span>
                                {{Form::text('actively[pre]',null,[
                                    'class'=>'form-control name col-md-3 pre-active-motle',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_actively_motile'
                                ])}}
                                {{Form::text('actively[post]',null,[
                                    'class'=>'form-control name col-md-3 post-active-motle',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_actively_motile'
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Sliggishly Motile (%) : &nbsp;</span>
                                {{Form::text('sluggishly[pre]',null,[
                                    'class'=>'form-control name col-md-3 pre-sliggishly-motile',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_sliggishly_motile'
                                ])}}
                                {{Form::text('sluggishly[post]', null,[
                                    'class'=>'form-control name col-md-3 post-sliggishly-motile',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_sliggishly_motile'
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Non-motile (%) : &nbsp;</span>
                                {{Form::text('motile[pre]', null,[
                                    'class'=>'form-control name col-md-3 pre-non-motile',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_non_motile'
                                ])}}
                                {{Form::text('motile[post]', null,[
                                    'class'=>'form-control name col-md-3 post-non-motile',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_non_motile'
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Normal Morphology : &nbsp;</span>
                                {{Form::text('morphology[pre]', null,[
                                    'class'=>'form-control name col-md-3 pre-normal-morphology',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_normal_morphology'
                                ])}}
                                {{Form::text('morphology[post]', null,[
                                    'class'=>'form-control name col-md-3  post-normal-morphology',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_normal_morphology'
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-5">Pus cells/hpf : &nbsp;</span>
                                {{Form::text('cells[pre]',null,[
                                    'class'=>'form-control name col-md-3 pre-pus-cells',
                                    'placeholder' => 'Pre-wash',
                                    'id' => 'pre_pus_cells'
                                ])}}
                                {{Form::text('cells[post]',null,[
                                    'class'=>'form-control name col-md-3 post-pus-cells',
                                    'placeholder' => 'Post-wash',
                                    'id' => 'post_pus_cells'
                                ])}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                <div class="modal-footer d-inline-block">
                    <button type="button" class="btn btn-primary waves-effect update-ivf-report">Save</button>
                    <button type="button" class="btn btn-primary waves-effect update-ivf-report" value="1">Save & Preview</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>

    {{-- ivf tranfer report --}}
    <div class="modal fade ivf" id="ivf_transfer_report_model" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content ivf-transfer-report-model">
                <!-- header -->
                <div class="modal-header">
                    <h4 class="title" id="defaultModalLabel">IVF Transfer Report</h4>
                </div>
                <!-- body -->
                {{Form::open([
                    'class'=>'form-inline',
                    'id'=>'ivf-transfer-report-update',
                    'files'=>'true',
                    'enctype'=>'multipart/form-data'
                ])}}
                {{Form::hidden('update_ivf_transfer_report_id', null,['id'=>'update_ivf_transfer_report_id'])}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Indication: &nbsp;</span>
                                {{Form::text("indication",'',[
                                    'class'=>'form-control indication',
                                    'maxlength' => 250
                                ])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">ET Date: &nbsp;</span>
                                {{Form::text("et_date", '', ['class'=>'form-control datetimepicker et_date'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Day: &nbsp;</span>
                                {{Form::text("day",'',['class'=>'form-control day', 'maxlength' => 250])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Endo. Thickness: &nbsp;</span>
                                {{Form::text("endo_thickness",'',[
                                    'class'=>'form-control endo_thickness',
                                    'maxlength' => 250
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">ET Procedure: &nbsp;</span>
                                {{Form::text("et_procedure",'',['class'=>'form-control et_procedure', 'maxlength' => 250])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Embryos Transferred: &nbsp;</span>
                                {{Form::text("embryos_transferred",'',['class'=>'form-control embryos_transferred', 'maxlength' => 250])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Frozen Embryos: &nbsp;</span>
                                {{Form::text("frozen_embryos",'',[
                                    'class'=>'form-control frozen_embryos',
                                    'maxlength' => 250
                                ])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"></span>
                                {{Form::file('embryos_transferred_image',['class'=>'form-control edit_embryos_transferred_image'])}}
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Pick up Date: &nbsp;</span>
                                {{Form::text("pick_up_date", '', ['class'=>'form-control datetimepicker pick_up_date'])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Simulation Protocol: &nbsp;</span>
                                {{Form::text("simulation_protocol",'',['class'=>'form-control simulation_protocol', 'maxlength' => 250])}}
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Total OCC: &nbsp;</span>
                                {{Form::text("total_occ",'',[
                                    'class'=>'form-control number total_occ',
                                    'maxlength' => 250,
                                    'id' => 'total_occ'
                                ])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Mll: &nbsp;</span>
                                {{Form::text("mll",'',['class'=>'form-control number mll', 'maxlength' => 250, 'id' => 'mll'])}}
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Ml: $&nbsp;</span>
                                {{Form::text("ml",'',['class'=>'form-control number ml', 'maxlength' => 250, 'id' => 'ml'])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">GV: &nbsp;</span>
                                {{Form::text("gv",'',[
                                    'class'=>'form-control number gv',
                                    'maxlength' => 250,
                                    'id' => 'gv'
                                ])}}
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Oocyte Quality: &nbsp;</span>
                                {{Form::text("oocycle_quality",'',['class'=>'form-control oocycle_quality', 'maxlength' => 250])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Sperm Quality: &nbsp;</span>
                                {{Form::text("sperm_quality",'',['class'=>'form-control sperm_quality', 'maxlength' => 250])}}
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Fertilization Procedure: &nbsp;</span>
                                {{Form::text("fertilization_procedure",'',[
                                    'class'=>'form-control fertilization_procedure',
                                    'maxlength' => 250
                                ])}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Remark: &nbsp;</span>
                                {{Form::text("remark",'',['class'=>'form-control transfer-report-remark'])}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                <div class="modal-footer d-inline-block">
                    <button type="submit" class="btn btn-primary waves-effect update-ivf-transfer-report">Save</button>
                    <button type="submit" class="btn btn-primary waves-effect update-ivf-transfer-report" value="1">Save & Preview</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="overy-data-popup" tabindex="-1" role="dialog">
        <div class="modal-dialog ovary-modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> --}}
                    {{-- <h4 class="title" id="overy-popup-title"></h4> --}}
                </div>
                <!-- body -->
                <div class="modal-body">
                    <table class="table m-b-0" id="ovary-table">
                        <tbody>
                            <tr>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="8"><span class="ovary-value-number ovary-number-8 odd-padding ovary-pre-selected-value">8</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="9"><span class="ovary-value-number ovary-number-9 odd-padding">9</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="10"><span class="ovary-value-number ovary-number-10 odd-padding">10</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="11"><span class="ovary-value-number ovary-number-11 odd-padding">11</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="12"><span class="ovary-value-number ovary-number-12 odd-padding">12</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="13"><span class="ovary-value-number ovary-number-13 odd-padding">13</span></td>
                            </tr>
                            <tr>
                                <td class="ovary-value" data-type="" data-class="" data-value="13.5"><span class="ovary-value-number ovary-number-13-5 odd-padding">13.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="14"><span class="ovary-value-number ovary-number-14 odd-padding ovary-pre-selected-value">14</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="14.5"><span class="ovary-value-number ovary-number-14-5 odd-padding">14.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="15"><span class="ovary-value-number ovary-number-15 odd-padding ovary-pre-selected-value">15</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="15.5"><span class="ovary-value-number ovary-number-15-5 odd-padding">15.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="16"><span class="ovary-value-number ovary-number-16 odd-padding ovary-pre-selected-value">16</span></td>
                            </tr>
                            <tr>
                                
                                <td class="ovary-value" data-type="" data-class="" data-value="16.5"><span class="ovary-value-number ovary-number-16-5 odd-padding">16.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="17"><span class="ovary-value-number ovary-number-17 odd-padding ovary-pre-selected-value">17</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="17.5"><span class="ovary-value-number ovary-number-17-5 odd-padding">17.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="18"><span class="ovary-value-number ovary-number-13 odd-padding ovary-pre-selected-value">18</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="18.5"><span class="ovary-value-number ovary-number-18-5 odd-padding">18.5</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="19"><span class="ovary-value-number ovary-number-19 odd-padding">19</span></td>
                            </tr>
                            <tr>
                                <td class="ovary-value" data-type="" data-class="" data-value="19.5"><span class="ovary-value-number ovary-number-19-5 odd-padding">19.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="20"><span class="ovary-value-number ovary-number-20 odd-padding ovary-pre-selected-value">20</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="20.5"><span class="ovary-value-number ovary-number-20-5 odd-padding">20.5</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="21"><span class="ovary-value-number ovary-number-21 odd-padding">21</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="21.5"><span class="ovary-value-number ovary-number-21-5 odd-padding">21.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="22"><span class="ovary-value-number ovary-number-22 odd-padding ovary-pre-selected-value">22</span></td>
                            </tr>
                            <tr>
                                <td class="ovary-value" data-type="" data-class="" data-value="22.5"><span class="ovary-value-number ovary-number-22-5 odd-padding">22.5</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="23"><span class="ovary-value-number ovary-number-23 odd-padding">23</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="24"><span class="ovary-value-number ovary-number-24 odd-padding">24</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </div>
                <!-- footer -->
                <div class="modal-footer next-appointment-footer">
                </div>
            </div>
        </div>
    </div>

    {{-- view file and edit data --}}
    <div class="modal fade view-file-edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog view-file-modal-dialog">
          <div class="modal-content">
            <div class="modal-header header-bottom-border">
              <button type="button" class="close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title rm-btn" id="myModalLabel">Plan:- <span class="ivf-appointment-plan"></span></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" id="myModalLabel">Cycle No:- <span class="ivf-appointment-cycle-no"></span></h5>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title rm-btn" id="myModalLabel">Visit:- <span class="ivf-appointment-visit-no"></span></h5>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12 mr-5">
                            {{-- <a class="btn edit-btn rm-btn btn-sm btn-primary">Edit</a>
                            <a class="btn print-btn btn-sm btn-primary">Print</a> --}}
                        <a class="btn print-fet-report btn-sm btn-primary">Print</a>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="ivf-details-data"></div>
            </div>

            <div class="modal-footer footer-top-border text-right d-inline-block">
                {{-- <button type="button" class="btn btn-primary next-appointment-details rm-btn" data-type="1">Prev</button>
                <button type="button" class="btn btn-primary next-appointment2 next-appointment-details rm-btn" data-type="2">Next</button> --}}
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade ivf-report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header header-bottom-border">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="modal-title font-20 ivf-report-title candor-color font-bold"></span>
            </div>
            <div class="modal-body">
                <div class="ivf-details-data">
                    <div class="w3-content w3-display-container">
                        <div class="report-image">
                            
                        </div>
                      
                        <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
                        <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
                      </div>
                </div>
            </div>

            <div class="modal-footer footer-top-border text-right d-inline-block">
            </div>
          </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="{{asset('public/js/ivf.js')}}"></script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script type="text/javascript">
        var doseData = @json($doseData);
        var ivfTransferReportPrint = 0;
        var slideIndex = 1;
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            // minDate:new Date(),
            time:false,
            weekStart: 1
        });
        $('.history-lmd-date').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time:false,
            weekStart: 1
        });
        $('.co_value_data').selectize({
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
        $('#treatment-medicine').select2();
        $('.timepicker').bootstrapMaterialDatePicker({
            date: false,
            shortTime: true,
            format: 'hh:mm a',
            switchOnClick: true
        });
        var ivfString = '';
        var ivfCycleNo = "{{$cycleNumber}}";
        var ivfPlan = "{{$pStatus}}";
        var ivfPId = "{{$patientsId}}";
        var fetReportString = '';
        $(document).ready(function(){

            $(document).on('click','.view-file-edit',function(e){
                e.preventDefault();
                $('.rm-btn').removeClass('d-none');
                $('.print-btn').removeClass('d-none');
                $('.print-fet-report').addClass('d-none');
                $('.view-file-edit-modal').modal('show');
                ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan;
                getIvfHistoryData(ivfString);
            });

            $(document).on('click','.fet-report',function(e){
                e.preventDefault();
                $('.view-file-edit-modal').modal('show');
                $('.print-fet-report').removeClass('d-none');
                $('.print-btn').addClass('d-none');
                fetReportString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan;
                $('.rm-btn').addClass('d-none');
                getFetReport(fetReportString);
            });

            $(document).on('click','.next-appointment-details',function(e){
                e.preventDefault();
                appintmentDate = $(this).data('date');
                type = $(this).data('type');
                ivfVisit = $(this).data('visit');
                ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&visit='+ivfVisit+'&type='+type;
                getIvfHistoryData(ivfString);
            });

            $(document).on('click','.print-btn',function(e){
                e.preventDefault();
                var extraVisit = $(this).data('extravisit');
                // ivfVisit = $('.next-appointment-details').data('visit');
                var visitDate = $(this).data('date');
                ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&visitDate='+visitDate+'&is_print=1&extraVisit='+extraVisit;
                // ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&visit='+ivfVisit+'&is_print=1';
                getIvfHistoryData(ivfString);
            });

            $(document).on('click','.print-fet-report',function(e){
                e.preventDefault();
                fetReportString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&is_print=1';
                getFetReport(fetReportString);
            });

            $(document).on('click','.edit-btn',function(){
                ivfVisit = $(this).data('visit');
                if(ivfVisit == 1){
                    window.location.href = "{{URL::to('ivf/ivfedit/')}}"+'/'+ivfPId;
                    return true;
                }
                var historyId = $(this).data('id');
                // $('.edit-visit-data').addClass('d-none');
                $('.cycle-form').remove();
                $('.view-file-edit-modal').modal('hide');
                getVisitData(historyId);
            });

            $(document).on('click','.update-ivf-report',function(e){
                // var token = "{{csrf_token()}}";
                var ivfReportId = $('#ivf_report_id');
                var ivfReport = $('#ivf-report').serialize();
                if(this.value == 1){
                    ivfReport = ivfReport + '&is_print=1';
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('edit-ivf-report-data')}}",
                    dataType: 'json',
                    type: 'POST',
                    data: ivfReport
                }).done(function(data) {
                    if (data.status == 1){
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        $('#ivf_report_model').modal('hide');
                        setTimeout(function() {
                            w.window.print();
                        }, 800);

                    } else if (data.status == 2) {
                        $('#ivf_report_model').modal('hide');
                        location.reload();
                    }else{
                        location.reload();
                    }
                    // if (data.status == false) {
                    //     swal({
                    //         title: 'Oops!',
                    //         text: data.message,
                    //         type: 'error'
                    //     }, function() {
                    //         window.location.reload();
                    //         // window.location = 'redirectURL';
                    //     });
                    // }
                })
            });
            $(document).on('click','#ivf_report_update',function(e){
                var token = "{{csrf_token()}}";
                var patientId = $(this).data('patient-id');
                var cycleNo = $(this).data('cycle-no');
                var plan = $(this).data('plan');
                var visit = $(this).data('visit');

                $.ajax({
                    url: "{{URL::to('get-ivf-report-data')}}",
                    dataType: 'json',
                    type: 'GET',
                    data:{
                        patient_id: patientId,
                        cycle_no: cycleNo,
                        plan: plan,
                        visit: visit,
                        _token:token
                    }
                }).done(function(data) {
                    if (data.status == false) {
                        swal({
                            title: 'Oops!',
                            text: data.message,
                            type: 'error'
                        }, function() {
                            window.location.reload();
                            // window.location = 'redirectURL';
                        });
                    }

                    if (data.status == 1 && data.ivf_report_id != null) {
                        $('#update_ivf_report_id').val(data.ivf_report_id);
                        $('#reason').val(data.data.reason);
                        if (data.data.date != null) {
                            $('#report_date').val(moment(data.data.date).format('ddd DD MMM YYYY'));
                        }
                        var volume = JSON.parse(data.data.volume);
                        $('#pre_volume').val(volume.pre);
                        $('#post_volume').val(volume.post);
                        var spermCount = JSON.parse(data.data.sperm_count);
                        $('#pre_sperm').val(spermCount.pre);
                        $('#post_sperm').val(spermCount.post);
                        var totalMotility = JSON.parse(data.data.total_motility);
                        $('#pre_motility').val(totalMotility.pre);
                        $('#post_motility').val(totalMotility.post);
                        var actively = JSON.parse(data.data.actively);
                        $('#pre_actively_motile').val(actively.pre);
                        $('#post_actively_motile').val(actively.post);
                        var sluggishly = JSON.parse(data.data.sluggishly);
                        $('#pre_sliggishly_motile').val(sluggishly.pre);
                        $('#post_sliggishly_motile').val(sluggishly.post);
                        var nonMotile = JSON.parse(data.data.non_motile);
                        $('#pre_non_motile').val(nonMotile.pre);
                        $('#post_non_motile').val(nonMotile.post);
                        var morphology = JSON.parse(data.data.morphology);
                        $('#pre_normal_morphology').val(morphology.pre);
                        $('#post_normal_morphology').val(morphology.post);
                        var pusCells = JSON.parse(data.data.pus_cells);
                        $('#pre_pus_cells').val(pusCells.pre);
                        $('#post_pus_cells').val(pusCells.post);
                        $('#ivf_report_model').modal('show');
                    } else {
                        swal({
                            title: 'Oops!',
                            text: 'Data not found',
                            type: 'error'
                        }, function() {
                            // window.location.reload();
                            // window.location = 'redirectURL';
                        });
                    }
                    // if(data.mobile_number != null){
                    //     $('.ref-mobile-number').val(data.mobile_number);
                    // }else{
                    //     $('.ref-mobile-number').val('');
                    // }
                }).fail(function() {

                });
                // if($(this).is(':checked')){
                //     $('.simen_data').removeClass('d-none');
                // }else{
                //     $('.simen_data').addClass('d-none');
                // }
            });
            $(document).on('click','.simen-sample',function(e){
                if($(this).is(':checked')){
                    $('.simen_data').removeClass('d-none');
                }else{
                    $('.simen_data').addClass('d-none');
                }
            });
            $(document).on('click','.pre-operative-type-hub',function(){
                // alert($(this).val());
                if($(this).val() == "yes") {
                    $(".pre_operative_type_hub").removeClass('d-none');
                }else{
                    $(".pre_operative_type_hub").addClass('d-none');
                }
            });

            $(document).on('change','.tf-date',function(){
                $('.t-follow-date').val($(this).val());
            });

            // $(document).on('click','#embroyno', function(){
            //     if($(this).val() == "no") {
            //         $(".embroy-button").addClass('d-none');
            //         $('#ivf_report_print').addClass('d-none');
            //     }
            // });
            $(document).on('click','#collected-report', function(){
                if($(this).is(':checked')){
                    $(".embroy-button").removeClass('d-none');
                    $('#ivf_report_print').removeClass('d-none');
                }else{
                    $(".embroy-button").addClass('d-none');
                    $('#ivf_report_print').addClass('d-none');
                }
            });
            // $(document).on('click','#embroyyes', function(){
            //     if($(this).val() == "yes") {
            //         $(".embroy-button").removeClass('d-none');
            //         $('#ivf_report_print').removeClass('d-none');
            //     } else {
            //         $('#ivf_report_print').addClass('d-none');
            //         $(".embroy-button").addClass('d-none');
            //     }
            // });
            $('.complain-multi .show-tick').addClass('d-none');
            $('.select2-search__field').css('width','280px');
            // append protocol table base on 2nd visit lmp date
            var lmdDate = $('.history-lmd-date').val();
            var lmdDateDiff = $('.history-lmd-date-diff').val();
            if(lmdDate != '' && lmdDateDiff != ''){
                protocolTable(new Date(lmdDate),parseInt(lmdDateDiff),3);
            }
            $(document).on('click','.skip-cycle',function(){
                $('.skip-cycle-data').addClass('d-none');
                $('.plan-transfer-data').removeClass('d-none');
                $('.plan-transfer-data-type').addClass('d-none');
                if($(this).is(':checked')) {
                    $('.plan-transfer-data').addClass('d-none');
                    $('.skip-cycle-data').removeClass('d-none');
                }
            });

            $(document).on('change','#progesterone',function(){
                $('.transfer-print').addClass('d-none');
                if($(this).is(":checked")) {
                    $(".progesterone").removeClass("d-none");
                }else{
                    $(".progesterone").addClass("d-none");
                    $('.transfer-print').removeClass('d-inline-block');
                    $('.transfer-print').addClass('d-none');
                }
            });

            $(document).on('change','#trigger',function(){
                var hovalue = null;
                var covalue = $('select.complaint-data').val();
                var type = 1;
                var mType = 2;
                var isIvf = false;
                var isSp2 = false;
                var isTrigger = false;
                var checkIsSp2 = $('#progesterone:checked').val();
                var ivfHId = $('.ivf_visit_id').val();
                if(checkIsSp2 == 'progesterone' && typeof checkIsSp2 != 'undefined'){
                    isSp2 = true;
                }
                if($(this).is(":checked")) {
                    isTrigger = true;
                    $(".trigger").removeClass("d-none");
                    $('#ivf_print').removeClass('d-none');
                }else{
                    $(".trigger").addClass("d-none");
                    $('#ivf_print').addClass('d-none');
                }
                if(typeof ivfHId == 'undefined' && ivfHId != ''){
                    complaintWiseMedicines(covalue,type,mType,hovalue,isIvf,isSp2,false,isTrigger);
                }
            });

            $(document).on('click','.simen-sample',function(e){
                if($(this).is(':checked')){
                    $('.simen_data').removeClass('d-none');
                }else{
                    $('.simen_data').addClass('d-none');
                    $('#ivf_report_print').addClass('d-none');
                    $('.embroy-button').addClass('d-none');
                }
            });

            $(document).on('change','#decapeptyl',function(){
                if($(this).is(":checked")) {
                    $(".decapeptyltrigger").removeClass("d-none");
                }else{
                    $(".decapeptyltrigger").addClass("d-none");
                }
            });

            $(document).on('change','#decapeptyl',function(){
                if($(this).is(":checked")) {
                    $(".decapeptyltrigger").removeClass("d-none");
                }else{
                    $(".decapeptyltrigger").addClass("d-none");
                }
            });

            $(document).on('click','#oe_left',function(){
                if($(this).is(":checked")) {
                    $(".leftovary").removeClass("d-none");
                }else{
                    $(".leftovary").addClass("d-none");
                }
            });

            $(document).on('click','#oe_right',function(){
                if($(this).is(":checked")) {
                    $(".rightovary").removeClass("d-none");
                }else{
                    $(".rightovary").addClass("d-none");
                }
            });

            $(document).on('click','#blood',function(){
                if($(this).is(":checked")) {
                    $(".bloodreport").removeClass("d-none");
                }else{
                    $(".bloodreport").addClass("d-none");
                }
            });

            $(document).on('change','#transfer',function(){
                var hovalue = null;
                var covalue = $('select.complaint-data').val();
                var type = 1;
                var mType = 2;
                var isIvf = false;
                var isSp2 = false;
                var isTrigger = false;
                var isTransfer = false;
                var checkIsSp2 = $('#progesterone:checked').val();
                if(checkIsSp2 == 'progesterone' && typeof checkIsSp2 != 'undefined'){
                    isSp2 = true;
                }
                if($(this).is(":checked")) {
                    isTransfer = true;
                    $(".transferdata").removeClass("d-none");
                    $(".transfer-report").removeClass("d-none");
                }else{
                    $(".transferdata").addClass("d-none");
                    $(".transfer-report").addClass("d-none");
                }
                complaintWiseMedicines(covalue,type,mType,hovalue,isIvf,isSp2,false,isTrigger,isTransfer);
            });

            $(document).on('click','#blood',function(){
                if($(this).is(":checked")) {
                    $(".bloodreport").removeClass("d-none");
                }else{
                    $(".bloodreport").addClass("d-none");
                }
            });

            $(document).on('change','#transfer',function(){
                if($(this).is(":checked")) {
                    $('.is-transfer').val('yes');
                    $(".transferdata").removeClass("d-none");
                }else{
                    $('.is-transfer').val('');
                    $(".transferdata").addClass("d-none");
                }
            });

            $(document).on('click','.progesterone-type',function(){
                if($(this).is(":checked")) {
                    var days = $(this).val();
                    days = days == 'day_3' ? 3 : 5;
                    // if(days != null)
                    // {
                        $('.progesterone_date_div').removeClass('d-none');
                    // }
                    var now = new Date($('.last-appointment-date').val());
                    if($('.progesterone_date').length)
                    {   
                        var progesteroneDate = new Date($('.progesterone_date').val());
                        if(progesteroneDate != null)
                        {
                            now = new Date($('.progesterone_date').val());
                        }
                    }
                    
                    now.setDate(now.getDate()+days);
                    now = moment(now).format('ddd DD MMM YYYY');
                    $('.tranfer-follow-date').val(now);
                    $('.transfer-print').removeClass('d-none');
                    $('.transfer-print').addClass('d-inline-block');
                }
            });
            $(document).on('change','.progesterone_date',function(){
                var days = $('.progesterone-type:checked').val();
                days = days == 'day_3' ? 3 : 5;
                var now = new Date($('.last-appointment-date').val());
                var progesteroneDate = new Date($('.progesterone_date').val());
                if(progesteroneDate != null)
                {
                    now = new Date($('.progesterone_date').val());
                }
                now.setDate(now.getDate()+days);
                now = moment(now).format('ddd DD MMM YYYY');
                $('.tranfer-follow-date').val(now);
            })
            $(document).on('click','#progesterone',function(e){
                var hovalue = null;
                var covalue = $('select.complaint-data').val();
                var type = 1;
                var mType = 2;
                var isIvf = false;
                var isTransfer = false;
                var isTrigger = false;
                var isSp2 = false;
                var checkTrigger = $('#trigger:checked').val();
                if(checkTrigger == 'trigger' && typeof checkTrigger != 'undefined'){
                    isTrigger = true;
                }
                var ivfHId = $('.ivf_visit_id').val();
                var checkTransfer = $('#transfer:checked').val();
                if(checkTransfer == 'transfer' && typeof checkTransfer != 'undefined'){
                    isTransfer = true;
                }
                if($(this).is(":checked")){
                    isSp2 = true;
                    if(typeof ivfHId == 'undefined' && ivfHId != ''){
                        complaintWiseMedicines(covalue,type,mType,hovalue,isIvf,isSp2,false,isTrigger,isTransfer);
                    }
                    $('.progesterone_data').removeClass('d-none');
                    $('.progesterone_date').removeClass('d-none');
                }else{
                    $('.progesterone_data').addClass('d-none');
                    $('.progesterone_yes').addClass('d-none');
                    $('.progesterone_date').addClass('d-none');
                    if(typeof ivfHId == 'undefined' && ivfHId != ''){
                        complaintWiseMedicines(covalue,type,mType,hovalue,isIvf,isSp2,false,isTrigger,isTransfer);
                    }
                }
            });

            $(document).on('click','#progesterone_yes',function() {
                if($('#progesterone_yes').is(':checked')) {
                    $('.progesterone_yes').removeClass('d-none');
                }else{
                    $('.progesterone_yes').addClass('d-none');
                }
            });

            $(document).on('click','#progesterone_no',function() {
                if($('#progesterone_no').is(':checked')) {
                    $('.progesterone_yes').addClass('d-none');
                    $('.transfer-print').removeClass('d-inline-block');
                    $('.transfer-print').addClass('d-none');
                }
                $('.progesterone_yes').addClass('d-none');
            });

            $(document).on('click','.skip-cycle',function(){
                $('.skip-cycle-data').addClass('d-none');
                if($(this).is(':checked')) {
                    $('.skip-cycle-data').removeClass('d-none');
                }
            });

            $(document).on('change','select.cycle_number',function(){
                cycleData = 'cycle_no='+$(this).val()+'&plan_type='+$(this).data('plan');
                getCycleData(cycleData);
            });

            $(document).on('click','.t-print',function(){
                var valid = 1;
                $('.lmp-date-error').text('');
                if($('.history-lmd-date').val() == ''){
                    valid = 0;
                    $('.lmp-date-error').text('This field is required.');
                    $('html, body').animate({
                        scrollTop: ($('.history-lmd-date').offset().top - 150)
                    }, 200);
                    return false;
                }
                if(valid == 0){
                    return true;
                }
                $('#ivfRemarkModal').modal('show');
            });

            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var formData = new FormData($(".form.ivf")[0]);
                var remark = $('#remark').val();
                formData.append('remark',remark);
                if(this.value == 1){
                    formData.append('isprint', 1);
                }
                if(this.value == 2){
                    formData.append('isprint', 2);
                }
                if(this.value == 3){
                    formData.append('isprint', 3);
                }
                if(this.value == 4){
                    formData.append('is_ivf_report_print', 4);
                }
                if(this.value == 5){
                    formData.append('is_ivf_transfer_print', 5);
                }
                var transferPlan = $('select.plan-transfer').val();
                var ivfReport = $('.ivf-report-status').val();
                var cNumber = $('.cycle-no-data').val();
                var plan = $('.pickup-plan').val();
                var pId = $('.patients-id').val();
                var pType = $('.plan_type').val();
                var resultType = $('.result-type').val();
                if(transferPlan != '' && transferPlan == 2 && ivfReport == '' && pType == 1 && resultType == ''){
                    window.location.href  = "{{URL::to('ivf-plan-report/')}}"+"/"+plan+"/"+pId+"/"+cNumber;
                    return true;
                }
                var valid = 1;
                $('.lmp-date-error').text('');
                $('.skip-plan-error').text('');
                $('.skip-reason-error').text('');
                $('.seen-by-error').text('');
                $('.transfer-error').text('');
                if($('.history-lmd-date').val() == ''){
                    valid = 0;
                    $('.lmp-date-error').text('This field is required.');
                    $('html, body').animate({
                        scrollTop: ($('.history-lmd-date').offset().top - 150)
                    }, 200);
                    return false;
                }
                if($('.skip-cycle').is(":checked")){
                    if($('select.skip-plan').val() == ''){
                        $('.skip-plan-error').text('This field is required.');
                        valid = 0;
                    }
                    if($('.skip-reason').val() == ''){
                        $('.skip-reason-error').text('This field is required.');
                        valid = 0;
                    }
                }
                if($('select.seen-by').val() == ''){
                    $('.seen-by-error').text('Please select doctor');
                    $('html, body').animate({
                        scrollTop: ($('.seen-by').offset().top - 150)
                    }, 1000);
                    valid = 0;
                }
                var plan_transfer = $('select.plan-transfer').val();
                if((plan_transfer != '')){
                    if($('.remark').val() == '')
                    {
                        $('.transfer-error').text('Please Enter Transfer Reason');
                        $('html, body').animate({
                            scrollTop: ($('.remark').offset().top - 150)
                        }, 1000);
                        valid = 0;
                    }
                    
                }
                if(valid == 0){
                    return true;
                }
                var frozen = $('#progesteroneyes:checked').val();
                var visitNo = $('.visit-no').val();
                var planType = $('.plan_type').val();
                // alert(planType);
                // return false;
                if((frozen == 'no' || typeof frozen == 'undefined') && ($('.is-transfer').val() != 'yes') && (visitNo == 3 || visitNo == 4) && planType == 3){
                    if(visitNo == 3){
                        swal({
                            title: "Is semen sample not collected!",
                            // type: "warning",
                            confirmButtonColor: "#00cfd1",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false,
                            cancelButtonClass: 'btn btn-danger',
                        }, function () {
                            $('.showSweetAlert').remove();
                            $('.sweet-overlay').remove();
                            ivfFormData(formData);
                        });
                    }
                    if(visitNo == 4){
                        swal("Is semen sample not collected!");
                    }
                    return true;
                }
                ivfFormData(formData);
            });

            $(document).on('click','.transfer',function(e){
                if($(this).is(':checked')){
                    $('.transfer-data').removeClass('d-none');
                }else{
                    $('.transfer-data').addClass('d-none');
                }
            });

            $(document).on('click','#trigger',function(e){
                if($(this).is(':checked')){
                    $('.trigger').removeClass('d-none');
                }else{
                    $('.trigger').addClass('d-none');
                }
            });

            $(document).on('click','#hcg',function(e){
                if($(this).is(':checked')){
                    $('.hcgtrigger').removeClass('d-none');
                }else{
                    $('.hcgtrigger').addClass('d-none');
                }
            });

            $(document).on('click','#decapeptyl',function(e){
                if($(this).is(':checked')){
                    $('.decapeptyltrigger').removeClass('d-none');
                }else{
                    $('.decapeptyltrigger').addClass('d-none');
                }
            });

            $(document).on('click','#blood',function(e){
                if($(this).is(':checked')){
                    $('.bloodreport').removeClass('d-none');
                }else{
                    $('.bloodreport').addClass('d-none');
                }
            });
            $(document).on('click','#usg',function(e){
                if($(this).is(':checked')){
                    $('.usgreport').removeClass('d-none');
                }else{
                    $('.usgreport').addClass('d-none');
                }
            });
            $(document).on('click','#hsa',function(e){
                if($(this).is(':checked')){
                    $('.hsareport').removeClass('d-none');
                }else{
                    $('.hsareport').addClass('d-none');
                }
            });

            $(document).on('click','#embroy',function(e){
                if($(this).is(':checked')){
                    $('.embroy-yes').removeClass('d-none');
                    // $('.embroy-button').removeClass('d-none');
                }else{
                    $('.embroy-yes').addClass('d-none');
                    // $('.embroy-button').addClass('d-none');
                    $('#ivf_report_print').addClass('d-none');
                }
            });

            $(document).on('click','.edit-visit-data',function(e){
                var historyId = $(this).data('id');
                // $('.edit-visit-data').addClass('d-none');
                $('.cycle-form').remove();
                getVisitData(historyId);
            });

            $(document).on('click','.transfer',function(e){
                if($(this).is(':checked')){
                    $('.transfer-data').removeClass('d-none');
                }else{
                    $('.transfer-data').addClass('d-none');
                }
            });

            $(document).on('click','.ps-status',function(e){
                if($(this).is(':checked')){
                    $('.ps-details').removeClass('d-none');
                }else{
                    $('.ps-details').addClass('d-none');
                }
            });

            $(document).on('change','select.plan-transfer',function(e){
                var value = $(this).val();
                $('.skip-data').removeClass('d-none');
                $('.plan-transfer-data-type').addClass('d-none');
                $('select.skip-plan').val('');
                if(value != ''){
                    $('.plan-transfer-data-type').removeClass('d-none');
                    $('select.skip-plan').val(value);
                    $('.skip-data').addClass('d-none');
                }
            });

            $(document).on('click', '#ivf_transfer_report_update', function(e){
                var token = "{{csrf_token()}}";
                var patientId = $(this).data('patient-id');
                var cycleNo = $(this).data('cycle-no');
                var plan = $(this).data('plan');
                var visit = $(this).data('visit');
                $.ajax({
                    url: "{{URL::to('get-ivf-transfer-report-data')}}",
                    dataType: 'json',
                    type: 'GET',
                    data:{
                        patient_id: patientId,
                        cycle_no: cycleNo,
                        plan: plan,
                        visit: visit,
                        _token:token
                    }
                }).done(function(data) {
                    if (data.status == false) {
                        swal({
                            title: 'Oops!',
                            text: data.message,
                            type: 'error'
                        }, function() {
                            // window.location.reload();
                        });
                    }
                    if (data.status == 1 && data.ivf_transfer_report_id != null) {
                        $('#update_ivf_transfer_report_id').val(data.ivf_transfer_report_id);
                        $('.indication').val(data.data.indication);
                        if (data.data.et_date != null) {
                            $('.et_date').val(moment(data.data.et_date).format('ddd DD MMM YYYY'));
                        }else{
                            $('.et_date').val("{{$lastAppointment->date}}");
                        }
                        $('.day').val(data.data.day);
                        $('.endo_thickness').val(data.data.endo_thickness);
                        $('.et_procedure').val(data.data.et_procedure);
                        $('.embryos_transferred').val(data.data.embryos_transferred);
                        $('.frozen_embryos').val(data.data.frozen_embryos);
                        if (data.data.pickup_date != null) {
                            $('.pick_up_date').val(moment(data.data.pickup_date).format('ddd DD MMM YYYY'));
                        }
                        $('.transfer-report-remark').val(data.data.remark);
                        $('.simulation_protocol').val(data.data.simulation_protocol);
                        $('.total_occ').val(data.data.total_occ);
                        $('.mll').val(data.data.mll);
                        $('.ml').val(data.data.ml);
                        $('.gv').val(data.data.gv);
                        $('.oocycle_quality').val(data.data.oocycle_quality);
                        $('.sperm_quality').val(data.data.sperm_quality);
                        $('.fertilization_procedure').val(data.data.fertilization_procedure);
                        $('#ivf_transfer_report_model').modal('show');
                    } else {
                        swal({
                            title: 'Oops!',
                            text: 'Data not found',
                            type: 'error'
                        }, function() {
                            // window.location.reload();
                        });
                    }
                }).fail(function() {
                });
            });
            $(document).on('click','.update-ivf-transfer-report',function(){
                ivfTransferReportPrint = $(this).val();
            })
            $(document).on('submit',' #ivf-transfer-report-update',function(e){
                e.preventDefault();
                
                var ivfTransferReport = new FormData($("#ivf-transfer-report-update")[0]);
                if(ivfTransferReportPrint == 1){
                    ivfTransferReport.append('is_print',ivfTransferReportPrint);
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('update-ivf-transfer-report-data')}}",
                    type:'POST',
                    data:ivfTransferReport,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(data) {
                    if (data.status == 2){
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        $('#ivf_transfer_report_model').modal('hide');
                        setTimeout(function() {
                            w.window.print();
                        }, 800);
                    } else if (data.status == 1) {
                        $('#ivf_transfer_report_model').modal('hide');
                        location.reload();
                    }else{
                        swal({
                            title: 'Oops!',
                            text: data.message,
                            type: 'error'
                        }, function() {
                            window.location.reload();
                        });
                    }
                    // if (data.status == false) {
                    //     swal({
                    //         title: 'Oops!',
                    //         text: data.message,
                    //         type: 'error'
                    //     }, function() {
                    //         window.location.reload();
                    //         // window.location = 'redirectURL';
                    //     });
                    // }
                })
            });

            $(document).on('keyup', '.number', function(e){
                var value = $(this).val();
                if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                    $('#' + this.id).val('');
                } else {
                    return value;
                }
            });


            $(document).on('click','.delete-visit-data',function(e){
                var visitId = $(this).data('id');
                showConfirmMessage(visitId);
            });
        });

        function ivfFormData(data){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'{{URL::to("ivf")}}',
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'json',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == 'true'){
                    // var url = "{{URL::to('ivf')}}";
                    var url = "{{URL::to('ivf/history')}}"+'/'+$('.patients-id').val();
                    window.location.href = url;
                }else if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    $('#ivf_history_id').val(data.id);
                    if(data.ivf_report_id != ''){
                        $('#ivf_report_id').val(data.ivf_report_id);
                    }
                    setTimeout(function() {
                        w.window.print();
                        window.location.href  = "{{URL::to('ivf/history')}}"+'/'+$('.patients-id').val();
                    }, 800);
                }else if(data.status == 4){
                    swal("This time is already booked for trigger!");
                    return true;
                }else if(data.status == 3){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    $('#ivf_history_id').val(data.id);
                    $('#ivf_transfer_report_id').val(data.ivf_transfer_report_id);
                    setTimeout(function(){
                        w.window.print();
                        window.location.href  = "{{URL::to('ivf/history')}}"+'/'+$('.patients-id').val();
                    }, 800);
                }else{
                    location.reload();
                }
            });
        }

        function getVisitData(ivfHistoryId){
            $.ajax({
                url: "{{URL::to('get-visit-data')}}" +'/'+ ivfHistoryId,
                dataType: 'json',
            }).done(function(data) {
                $('.frozen-table').addClass('d-none');
                $('.pick_up_table').addClass('d-none');
                $('.visit-card-'+data.id).removeClass('d-none');
                $('.visit-data-'+data.id).html(data.visitData);
                $('.datetimepicker').bootstrapMaterialDatePicker({
                    format: 'dddd DD MMMM YYYY',
                    clearButton: true,
                    // minDate:new Date(),
                    time:false,
                    weekStart: 1
                });
                $('.history-lmd-date').bootstrapMaterialDatePicker({
                    format: 'dddd DD MMMM YYYY',
                    clearButton: true,
                    time:false,
                    weekStart: 1
                });
                $('.co_value_data').selectize({
                    delimiter: ',',
                    persist: false,
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
                $('#treatment-medicine').select2();
                $('.complain-multi .show-tick').addClass('d-none');
                $('.select2-search__field').css('width','280px');
                $('.timepicker').bootstrapMaterialDatePicker({
                    date: false,
                    shortTime: true,
                    format: 'hh:mm a',
                    switchOnClick: true
                });
                $('.select-padding-0').selectpicker('refresh');
            }).fail(function() {

            });
        }

        function getIvfHistoryData(ivfString){
            $.ajax({
                url:'{{URL::to("get-ivf-details")}}?'+ivfString,
                type:'GET',
                dataType:'json'
            }).done(function(data){
               
                if(data.ivf_type == 1){
                $('.ivf-details-data').html('');

                    // $('.ivf-appointment-cycle-no').text(ivfCycleNo);
                    // $('.ivf-appointment-date').text('');
                    // $('.ivf-details-data').html(data.data);
                    // var visitNumberValue = data.visit == null ? 1 : data.visit;
                    // $('.ivf-appointment-visit-no').text(visitNumberValue);
                    // $('.next-appointment-details').data('visit',data.visit);
                    // $('.next-appointment2').data('type',2);
                    // $('.edit-btn').data('id',data.enc_ivf_id);
                    // if(typeof data.date != 'undefined'){
                    //     var linkDate = moment(new Date(data.date)).format('YYYY-MM-DD HH:mm:ss');
                    //     var date = moment(new Date(data.date)).format('DD MMMM YYYY');
                    //     $('.ivf-appointment-date').text(date);
                    // }
                    var ivfPreview = $('.ivf-details-data').html();
                    var buttonHtml = '';
                    var previewData = '';
                    
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
                            buttonHtml = ivfPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn print-btn btn-sm btn-primary" data-plan="'+data.plan+'" data-cycleno="'+data.cycle+'" data-date="'+linkDate+'" data-extravisit="1">Print</a></div></div>';
                        }
                        else{
                        buttonHtml = ivfPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn edit-btn btn-sm btn-primary" data-visit="'+data.visitNumber[i]+'" data-id="'+data.enc_ivf_id[i]+'" data-date="'+linkDate+'">Edit</a><a class="btn print-btn btn-sm btn-primary" data-plan="'+data.plan+'" data-cycleno="'+data.cycle+'" data-date="'+linkDate+'" data-extraVisit="">Print</a></div></div>';

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

        function getFetReport(fetReportString){
            $.ajax({
                url:'{{URL::to("get-fet-report")}}?'+fetReportString,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                $('.edit-btn').data('id','');
                if(data.status == 1){
                    $('.ivf-details-data').html('')
                    $('.ivf-report-model').addClass('fet-report-data');
                    $('.ivf-appointment-cycle-no').text(ivfCycleNo);
                    $('.ivf-appointment-date').text('');
                    $('.ivf-details-data').html(data.data);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 800);
                }
            }).fail(function(error){

            });
        }

        function showConfirmMessage(visitId) {
            swal({
                title: "Are you sure?",
                text: "You want to delete this visit!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                removeLastCycleVisit(visitId);
                $('.showSweetAlert').hide();
                location.reload();
            });
        }

        // remove last cycle
        function removeLastCycleVisit(visitId){
            $.ajax({
                url:'{{URL::to("remove-last-visit-cycle")}}'+'/'+visitId,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                if(data.status == 1){
                    location.reload();
                }
            }).fail(function(error){

            });
        }
        var medicinesValue = @json($medicines);
        $('.hystroscopy-images').imageUploader({
            imagesInputName: 'investigation[hystroscopy][images]',
        });
        $('.laproscopy-images').imageUploader({
            imagesInputName: 'investigation[laproscopy][images]',
        });
        $('.blood-images').imageUploader({
        imagesInputName: 'data[blood_report][image]',
        });
        $('.usg-images').imageUploader({
        imagesInputName: 'data[usg][images]',
        });
        $('.hsa-images').imageUploader({
        imagesInputName: 'data[hsa_report][images]',
        });
        $(document).on('click','.report-btn', function(){
            var ivfId = $(this).data('id');
            var date = $(this).data('date');
            var html = '';
            $.ajax({
                url:'{{URL::to("get-ivf-report")}}'+'/'+ivfId,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                $('.ivf-report').modal('show');
                $('.ivf-report-title').html('IVF Report of '+date);
                if(data.status == 1){
                    $.each(data, function() {
                        $.each(this, function(k, v) {
                            if(v.length > 0)
                            {
                                $.each(v, function(index,image) {
                                    var extension = image.substr( (image.lastIndexOf('.') +1) );
                                    var path = "{{url('')}}" + '/'+image;
                                    if(extension == 'pdf')
                                    {
                                        html += '<embed type="application/pdf" src="'+path+'" frameborder="0" height="100%" width="100%" class="mySlides">';
                                    }
                                    else
                                    {
                                        html += '<img class="mySlides" src="'+path+'">';
                                    }
                                });
                                
                            }
                        });
                        
                    });
                    $('.report-image').html(html);
                    slideIndex= 1;
                    showDivs(slideIndex);
                }
            }).fail(function(error){

            });
        });
        

        function plusDivs(n) {
        showDivs(slideIndex += n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) {slideIndex = 1}
            if (n < 1) {slideIndex = x.length}
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";  
            }
            x[slideIndex-1].style.display = "block";  
        }
</script>
@stop
