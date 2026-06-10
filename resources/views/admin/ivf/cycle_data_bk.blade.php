@extends('layouts.main')
@section('parentPageTitle', 'Ivf Cycle')
@section('title', 'Cycle')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <style>
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
        .unik-header-table td, .unik-footer-table td, .unik-header-table th, .unik-footer-table th{
            border: none !important;
        }
        .unik-header-table th:first-child{
            width: 70%;
        }
        .unik-header-table th:second-child{
            width: 30%;
        }
        .unik-table-border td{
            border: 1px solid #000 !important;
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
    </style>
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-8">
                            <h2><strong class="text-secondary">{{ucwords($lastAppointment->getPatientsDetails->name)}}</strong></h2>
                        </div>
                        <div class="col-md-4">
                            @if($pStatus != 1)
                                <a href="#" class="mb-1">
                                    <button class="btn btn-primary fet-btn pull-right fet-report">FET Report</button>
                                </a>
                            @endif
                            <a href="#" class="mb-1">
                                <button class="btn btn-primary pull-right view-file-edit">View File & Edit</button>
                            </a>
                            <a href="{{url('ivf/ivfedit/'.encrypt($patient_id))}}" class="mb-1 ml-1">
                                <button class="btn btn-primary pull-right">Visit-1</button>
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
            $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
            $injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'CC + HMG','8'=>'CC + FSH','9'=>'Antagonist'];
            $historyLmdDiff = 0;
            $historyLmddateData = null;
            $historyLmddateDate = null;
            $historyLmdDiff = null;
            if($LMPDate){
                $historyLmddateDate = cdate($LMPDate);
                $now = \Carbon\Carbon::now();
                $historyLmdDiff = $historyLmddateDate->diffInDays($now);
                $historyLmdDiff = $historyLmdDiff + 1;
            }
            $hmgDose = 0;
            $antaDose = 0;
            $fshDose = 0;
            $se2Data = [];
            $slhData = [];
            $bloodReport = [];
            $triggerHistoryData = $triggerHistory ? json_decode($triggerHistory->description) : null;
            $hcgTrigger = !empty($triggerHistoryData->trigger->hcg->status) ? $triggerHistoryData->trigger->hcg->status : null;
            $dualTrigger = !empty($triggerHistoryData->trigger->decapeptyl->status) ? $triggerHistoryData->trigger->decapeptyl->status : null;
        @endphp
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-12">
                        <table class='unik-header-table table m-b-0'>
                            <thead>
                            <tr>
                                <th>Patient Name: {{ucwords($lastAppointment->getPatientsDetails->name)}}</th>
                                <th>Plan: Pickup</th>
                            </tr>
                            <tr>
                                <th>Seen By: </th>
                                <th>Age: {{ucwords($lastAppointment->getPatientsDetails->age)}}</th>
                            </tr>
                            <tr>
                                <th>LMP Date: {{$historyLmddateDate}}</th>
                                <div></div>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @php
                $i=0;
                $cycle_no = count($cycle);
                @endphp
                @foreach($cycle as $row)
                    @php
                        $i++;
                        $datarow = $row;
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
                        if(!empty($data->blood->report)){
                            $bloodReport[] = $data->blood->report;
                        }
                        $duringPickupStatus = !empty($data->during_pickup) ? ucfirst($data->during_pickup) : null;
                    @endphp
                    <div class="row">
                        <div class="col-md-12">
                            <div class="{{'visit-data-'.$row->id}}">
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
                                        @if($countProtocolTable > 0 && (!empty($hmgArray) || !empty($hmgBrandArray) || !empty($fshArray) || !empty($antagonistArray) || !empty($fshBrandArray)))
                                            @if($i===1)
                                            <table class='unik-table table m-b-0'>
                                                <thead>
                                                <tr>
                                                    <th class="text-secondary">Visit Date</th>
                                                    <th class="text-secondary">Day of <br> menses</th>
                                                    <th class="text-secondary">Simulation Days</th>
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
                                                    <th class="text-secondary">Time</th>
                                                    <th class="text-secondary">Remark</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                            @endif
                                            @php
                                                $j=0;
                                            @endphp
                                                @foreach ($historyData->protocol as $row)
                                                    @php
                                                        $j++;
                                                    @endphp
                                                    @if(!empty($row->hmg) || !empty($row->hmg_brand_name) || !empty($row->fsh) || !empty($row->fsh_brand_name) || !empty($row->antagonist))
                                                        <tr>
                                                            <td>{{($j===1)?$datarow->visit .' ('.cdate($datarow->created_at)->format('d-m-Y').')':''}}</td>
                                                            <td>{{!empty($row->day) ? $row->day : '-'}}</td>
                                                            <td>{{!empty($row->s_day) ? 's'.$row->s_day : '-'}}</td>
                                                            <td>{{!empty($row->date) ? cdate($row->date)->format('d/m/Y') : '-'}}</td>
                                                            <td>{{!empty($row->injection) ? $injectionData[$row->injection] : '-'}}</td>
                                                            <td>{{!empty($row->hmg) ? $row->hmg : '-'}}</td>
                                                            <td>{{!empty($row->hmg_brand_name) ? $row->hmg_brand_name : '-'}}</td>
                                                            <td>{{!empty($row->fsh) ? $row->fsh : '-'}}</td>
                                                            <td>{{!empty($row->fsh_brand_name) ? $row->fsh_brand_name : '-'}}</td>
                                                            <td>{{!empty($row->antagonist) ? $row->antagonist : '-'}}</td>
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
                                                            <td>{{$j == 1 && !empty($data->et_details) ? $data->et_details : '-'}}
                                                            </td>
                                                            <td>{{!empty($row->time) ? $row->time : '-'}}</td>
                                                            <td>{{$historyData->remark}}</td>
                                                            @php
                                                                $hmgDose += !empty($row->hmg) && is_numeric($row->hmg) ? $row->hmg : 0;
                                                                $antaDose += !empty($row->antagonist) && is_numeric($row->antagonist) ? $row->antagonist : 0;
                                                                $fshDose += !empty($row->fsh) && is_numeric($row->fsh) ? $row->fsh : 0;
                                                            @endphp
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @if($i===$cycle_no)
                                                </tbody>
                                            </table>
                                            @endif
                                        @endif
                                        <br>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <table class='unik-footer-table table m-b-0'>
                            <tbody>
                            <tr>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>On day of trigger</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>S. E2:</td>
                                                <td>{{implode(',',$se2Data)}}</td>
                                            </tr>

                                            <tr>
                                                <td>S. LH:</td>
                                                <td>{{implode(',',$slhData)}}</td>
                                            </tr>
                                            <tr>
                                                <td>S. P4:</td>
                                                <td>{{implode(',',$bloodReport)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table class="unik-table-border">
                                        <tbody>
                                            <tr>
                                                <td style="width: 30%">Trigger</td>
                                                <td style="width: 70%">{{$hcgTrigger.(!empty($hcgTrigger) ? '+' : '').$dualTrigger}}</td>
                                            </tr>
                                            <tr>
                                                <td>Date & Time</td>
                                                <td>
                                                    @if($triggerHistoryData)
                                                        {{$triggerHistory ? (cdate($triggerHistory->trigger_date)->format('D d M Y')) : ''}} {{!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null)}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>OPU</td>
                                                <td>
                                                    @if($triggerHistoryData)
                                                        @php
                                                            $nowDate = cdate($triggerHistory->trigger_date)->format('Y-m-d');
                                                            $nowTime = cdate(!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null))->format('H:i:s');
                                                            $triggerDateTime = cdate($nowDate.' '.$nowTime)->addHours(35)->format('Y-m-d H:i:s');
                                                            $triggerDate = cdate($triggerDateTime)->format('D d M Y');
                                                        @endphp
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Date & Time</td>
                                                <td>
                                                    @if($triggerHistoryData)
                                                        {{$triggerDate.' '.cdate($triggerDateTime)->format('h:i a')}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Hystroscopy</td>
                                                <td>
                                                    @if(!empty($duringPickupStatus))
                                                        {{$duringPickupStatus}}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td>Total HMG dose:</td>
                                            <td>{{$hmgDose}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Anta dose:</td>
                                            <td>{{$antaDose}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total FSH dose:</td>
                                            <td>{{$fshDose}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        /*recycle*/
        @foreach($cycle as $row)
            @php
                $historyData = json_decode($row->description);
                if($row->cycle_status == 2){
                    $visit = $row->visit + 1;
                }
            @endphp

            <div class="card">
                <div class="body">
                    <div class="col-md-12">
                        <div class="{{'visit-data-'.$row->id}}">
                            {{-- start all visit data display --}}
                            <div class="row">
                                <h4 class="col-md-4 visit-lable m-0">Visit :<span class="col-md-4 visit-lable-value">{{$row->visit .' ('.cdate($row->created_at)->format('d-m-Y').')'}}</span></h4>
                                <div class="col-md-6"></div>
                                @if($historyData->is_transfer == 'no' || $historyData->is_transfer_print == 'no')
                                    <div class="col-md-1"><button class="btn btn-primary edit-visit-data" data-id="{{encrypt($row->id)}}">Edit</button></div>
                                @endif
                                @if($row->visit == $lastVisitValue && $row->cycle_no == $lastCycleValue)
                                    <div class="col-md-1"><button class="btn btn-primary delete-visit-data" data-id="{{encrypt($row->id)}}">Delete</button></div>
                                @endif
                            </div><br>
                            @if($historyData->is_transfer == 'no' || $historyData->is_transfer_print == 'no')
                                @php
                                    {{$collectionData = !empty($historyData->collection) ? $historyData->collection : [];}}
                                    {{$dataa = !empty($historyData->collected) ? $historyData->collected : []; }}
                                @endphp
                                <div class="row">
                                    <span class="col-md-1 visit-lable">L.M.P Date :</span>
                                    <span class="col-md-2 visit-lable-value">{{$historyData->lmp->date}}</span>
                                    <span class="visit-lable"> Diff Day : <span class="visit-lable-value">{{!empty($historyData->lmp->lmp_date_diff) ?  $historyData->lmp->lmp_date_diff : 0}} Day</span></span>
                                </div>
                                <br>
                                @if(!empty($historyData->le) && (!empty($historyData->le->bp) || !empty($historyData->le->temp) || !empty($historyData->le->pulse)))
                                    <div class="row">
                                        <span class="col-md-1 visit-lable">Vitals :</span>
                                        @if(!empty($historyData->le->bp))
                                            <span class="col-md-3 visit-lable">BP :<span class="visit-lable-value">{{$historyData->le->bp}} MMHG</span></span>
                                        @endif
                                        @if(!empty($historyData->le->temp))
                                            <span class="col-md-3 visit-lable">Temp :<span class="visit-lable-value">{{$historyData->le->temp}}</span></span>
                                        @endif
                                        @if(!empty($historyData->le->pulse))
                                            <span class="col-md-3 visit-lable">Pulse :<span class="visit-lable-value">{{$historyData->le->pulse}} / Min</span></span>
                                        @endif
                                    </div>
                                @endif
                                @php
                                    $ovaryType = !empty($historyData->oe->ovary->ovary_type) ? $historyData->oe->ovary->ovary_type : [];
                                    $leftAbnormal = 0;
                                    $rightAbnormal = 0;
                                    if(!empty($historyData->oe->ovary->left->type) && $historyData->oe->ovary->left->type == 2){
                                        $leftAbnormal = 1;
                                    }
                                    if(!empty($historyData->oe->ovary->right->type) && $historyData->oe->ovary->right->type == 2){
                                        $rightAbnormal = 1;
                                    }
                                @endphp
                                @if(!empty($historyData->oe) && (!empty($historyData->oe->oe_type->type) || !empty($historyData->oe->ut->ut_type)))
                                    <div class="row">
                                        @if(!empty($historyData->oe->oe_type->type))
                                            <span class="col-md-1 visit-lable">OE :&nbsp;<span class="visit-lable-value">{{ ucfirst($historyData->oe->oe_type->type)}}</span></span>
                                        @endif
                                        @if(!empty($historyData->oe->ut->ut_type))
                                            <span class="col-md-2 visit-lable">UT : &nbsp; <span class="visit-lable-value">{{$historyData->oe->ut->ut_type == 1 ? 'Normal' : 'Abnormal'}}</span></span>
                                        @endif
                                        @if(!empty($historyData->oe->ut->ut_type) && $historyData->oe->ut->ut_type == 2)
                                            <span class="col-md-2 visit-lable">UT Abnormal Details :</span>
                                            <span class="col-md-3 visit-lable-value">
                                                {{!empty($historyData->oe->ut->details) ? $historyData->oe->ut->details : null}}
                                            </span>
                                        @endif
                                    </div>
                                    <br>
                                @endif
                                @if(!empty($historyData->oe->ovary))
                                    @if(!empty($ovaryType))
                                        <div class="row">
                                            <span class="visit-lable col-md-3">Ovary :
                                            <span class="visit-lable-value">
                                                {{implode(',',$ovaryType)}}
                                            </span></span>
                                        </div>
                                    @endif
                                    <br>
                                    @if(in_array('left', $ovaryType))
                                        <div class="row">
                                            <span class="col-md-1">OE Left Data</span>
                                            <span class="col-md-2 visit-lable"> OE left Type : <span class="visit-lable-value">{{$leftAbnormal == 1 ? 'Abnormal' : 'Normal'}}</span></span>
                                            @if (!empty($historyData->oe->ovary->left->afcs))
                                                <span class="col-md-1 visit-lable">Left AFCS:</span>
                                                <span class="col-md-2 visit-lable-value">{{$historyData->oe->ovary->left->afcs}}</span>
                                            @endif
                                        </div>
                                        <br>
                                    @endif
                                    @if(in_array('right', $ovaryType))
                                        <div class="row">
                                            <span class="col-md-1">OE Right Data</span>
                                            <span class="col-md-2 visit-lable"> OE Right Type : <span class="visit-lable-value">{{$rightAbnormal == 1 ? 'Abnormal' : 'Normal'}}</span></span>
                                            @if (!empty($historyData->oe->ovary->right->afcs))
                                                <span class="col-md-1 visit-lable">Right AFCS:</span>
                                                <span class="col-md-2 visit-lable-value">{{$historyData->oe->ovary->right->afcs}}</span>
                                            @endif
                                        </div>
                                        <br>
                                    @endif
                                    @php
                                        $rightData = [];
                                        $leftData = [];
                                        $agentData = [];
                                        if(isset($historyData->oe->ovary->right->updated_details) && !empty($historyData->oe->ovary->right->updated_details)) {
                                            $rightData = $historyData->oe->ovary->right->updated_details;
                                        }
                                        if(isset($historyData->oe->ovary->left->updated_details) && !empty($historyData->oe->ovary->left->updated_details)){
                                            $leftData = $historyData->oe->ovary->left->updated_details;
                                        }
                                        if(!empty($historyData->plan->agenet)){
                                            $agentData = $historyData->plan->agenet;
                                        }
                                    @endphp
                                    @if(in_array('left', $ovaryType) && isset($historyData->oe->ovary->left->updated_details))
                                        <div class="row">
                                            <div class="col-md-2 padding-6">
                                                <label class="vertical-form-label visit-lable">
                                                    Left Abnormal Details :
                                                </label>
                                            </div>
                                            <div class="col-md-8 complain-multi mb-1">
                                                {{Form::select('left_abnormal_details',$leftData,range(0,count($leftData)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                                            </div>
                                        </div>
                                    @endif
                                    @if(in_array('right', $ovaryType) && isset($historyData->oe->ovary->right->updated_details))
                                        <div class="row">
                                            <div class="col-md-2 padding-6">
                                                <label class="vertical-form-label pr-0 visit-lable">
                                                    Right Abnormal Details:
                                                </label>
                                            </div>
                                            <div class="col-md-8 complain-multi mb-1">
                                                {{Form::select('right_abnormal_details',$rightData,range(0,count($rightData)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                                            </div>
                                        </div>
                                        <br>
                                    @endif
                                @else
                                    @if(!empty($historyData->ovary))
                                        <div class="row">
                                            @if(!empty($historyData->ovary->ovary_status))
                                                <span class="col-md-1 visit-lable">Ovary :</span>
                                                <span class="col-md-2 visit-lable-value"> <span class="col-md-2 visit-lable-value">
                                                    {{implode(',',$historyData->ovary->ovary_status)}}
                                                </span></span>
                                            @endif
                                            @if(!empty($historyData->ovary->ovary_type->left->details) && !empty($historyData->ovary->ovary_status) && in_array('left',$historyData->ovary->ovary_status))
                                                <span class="col-md-1 visit-lable">Overy Left:</span>
                                                <span class="col-md-3 visit-lable-value"> {{$historyData->ovary->ovary_type->left->details}}</span>
                                            @endif
                                            @if(!empty($historyData->ovary->ovary_type->right->details) && !empty($historyData->ovary->ovary_status) && in_array('right',$historyData->ovary->ovary_status))
                                                <span class="col-md-1 visit-lable">Overy Right:</span>
                                                <span class="col-md-3 visit-lable-value"> {{$historyData->ovary->ovary_type->right->details}}</span>
                                            @endif
                                        </div>
                                        <br>
                                    @endif
                                @endif
                                @if(!empty($historyData->oe->endometrial_cavity->cavity) || !empty($historyData->oe->endometrial_cavity->size))
                                    <div class="row">
                                        @if(!empty($historyData->oe->endometrial_cavity->cavity))
                                            <span class="col-md-2 visit-lable">Endometrial Cavity:</span>
                                            <span class="col-md-3 visit-lable-value">{{$historyData->oe->endometrial_cavity->cavity}}</span>
                                        @endif
                                        @if(!empty($historyData->oe->endometrial_cavity->size))
                                            <span class="col-md-1 visit-lable">Size:</span>
                                            <span class="col-md-1 visit-lable-value">{{$historyData->oe->endometrial_cavity->size}} &nbsp;M</span>
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($historyData->p_s->type))
                                    <div class="row">
                                        <span class="col-md-1 visit-lable">P/S:</span>
                                        <span class="col-md-4 visit-lable-value">{{$historyData->p_s->details}}</span>
                                    </div>
                                @endif
                                {{-- @if($plan != 1 && $visit != 2)
                                    <br>
                                    @if(!empty($historyData->oe->et_details) || !empty($historyData->oe->other_details))
                                        <div class="row">
                                            <span class="col-md-1 visit-lable">O/E:</span>
                                            <span class="col-md-1 visit-lable">ET:</span>
                                            <span class="col-md-3 visit-lable-value">{{$historyData->oe->et_details}}</span>
                                            <span class="col-md-1 visit-lable">Other:</span>
                                            <span class="col-md-3 visit-lable-value">{{$historyData->oe->other_details}}</span>
                                        </div>
                                        <br>
                                    @endif
                                @endif --}}
                                @if(!empty($historyData->et_details))
                                    <br>
                                    <div class="row">
                                        <span class="col-md-1 visit-lable">ET:</span>
                                        <span class="col-md-3 visit-lable-value">{{$historyData->et_details}}</span>
                                    </div>
                                    <br>
                                @endif

                                @if(!empty($collectionData))
                                    <div class="row">
                                        @if(in_array('collected',$collectionData))
                                            <span class="col-md-2 visit-lable">* Is simen sample collected ? &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        @endif
                                        @if(!empty($dataa->frozen->type) && $dataa->frozen->type =='yes' )
                                            <span class="col-md-1 visit-lable">Frozen: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        @endif
                                        @if(!empty($dataa->date))
                                            <span class="col-md-2 visit-lable">Date: &nbsp;<span class="visit-lable-value">{{$dataa->date}}</span></span>
                                        @endif
                                        @if(!empty($dataa->report->type) && $dataa->report->type == 'report')
                                            <span class="col-md-2 visit-lable">Report: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                            <a href="javascript:void(0);" id="ivf_report_update" data-patient-id={{ encrypt($row->patients_id)}} data-cycle-no={{ encrypt($row->cycle_no)}} data-plan={{ encrypt($row->plan)}} data-visit={{ encrypt($row->visit)}}>
                                                IVF Report
                                            </a>
                                        @endif
                                        @if(!empty($dataa->report->embroy->type) && $dataa->report->embroy->type == 'yes')
                                            <span class="col-md-2 visit-lable">Embroy: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        @endif
                                    </div>
                                    <br>
                                    @if (in_array('progesterone',$collectionData))
                                        <div class="row">
                                            <span class="col-md-2 visit-lable">* Do satrting progesterone ? &nbsp;<span class="visit-lable-value">Yes</span></span>
                                            @if(in_array('progesterone',$collectionData) && !empty($historyData->progesterone->status))
                                                <span class="col-md-2 visit-lable"> Same Cycle Transfer?  &nbsp;<span class="visit-lable-value">{{ ucwords($historyData->progesterone->status) }}</span></span>
                                            @endif
                                            @if(in_array('progesterone',$collectionData) && !empty($historyData->progesterone->type))
                                                <span class="col-md-2 visit-lable">Progesterone Days: &nbsp;<span class="visit-lable-value">{{ ucwords($historyData->progesterone->type) }}</span></span>
                                            @endif
                                        </div>
                                        <br>
                                    @endif
                                @endif
                                @if($row->plan == 1)
                                    @if (in_array('trigger',$collectionData))
                                        <div class="row">
                                            <span class="col-md-2 visit-lable">* Trigger: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                            @if (!empty($historyData->trigger->hcg->status) && $historyData->trigger->hcg->status == 'hcg')
                                                <span class="col-md-2 visit-lable">HCG: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                            @endif
                                            @if(!empty($historyData->trigger->hcg->status) && $historyData->trigger->hcg->status == 'hcg')
                                                @if (!empty($historyData->trigger->hcg->time))
                                                    <span class="col-md-2 visit-lable">HCG Time: &nbsp;<span class="visit-lable-value">{{ $historyData->trigger->hcg->time }}</span></span>
                                                @endif
                                                @if (!empty($historyData->trigger->hcg->dose))
                                                    <span class="col-md-2 visit-lable">HCG Dose: &nbsp;<span class="visit-lable-value">{{ $historyData->trigger->hcg->dose }}</span></span>
                                                @endif
                                                @if (!empty($historyData->trigger->hcg->brand))
                                                    <span class="col-md-3 visit-lable">HCG Brand: &nbsp;<span class="visit-lable-value">{{ $historyData->trigger->hcg->brand }}</span></span>
                                                @endif
                                            @endif
                                        </div>
                                        <br>
                                    @endif
                                    @if(in_array('trigger',$collectionData) && !empty($historyData->trigger->decapeptyl->status) && $historyData->trigger->decapeptyl->status == 'decapeptyl')
                                        <div class="row">
                                            <span class="col-md-2 visit-lable">Decapeptyl: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                            @if (!empty($historyData->trigger->decapeptyl->time))
                                                <span class="col-md-2 visit-lable">Decapeptyl Time: &nbsp;<span class="visit-lable-value">{{ $historyData->trigger->decapeptyl->time }}</span></span>
                                            @endif
                                            @if (!empty($historyData->trigger->decapeptyl->dose))
                                                <span class="col-md-2 visit-lable">Decapeptyl Dose: &nbsp;<span class="visit-lable-value">{{ $historyData->trigger->decapeptyl->dose }}</span></span>
                                            @endif
                                            @if (!empty($historyData->trigger->decapeptyl->brand))
                                                <span class="col-md-3 visit-lable">Decapeptyl Brand: &nbsp;<span class="visit-lable-value">{{ $historyData->trigger->decapeptyl->brand }}</span></span>
                                            @endif
                                        </div>
                                        <br>
                                    @endif
                                    @if(in_array('trigger',$collectionData) && !empty($historyData->trigger->dualtrigger->status) && $historyData->trigger->dualtrigger->status == 'dualtrigger')
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                                <span class="col-md-2 visit-lable">Dule Trigger: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        </div>
                                        <br>
                                    @endif
                                @endif
                                @if(in_array('blood',$collectionData) && !empty($historyData->blood->report))
                                    <div class="row">
                                        <span class="col-md-2 visit-lable">* Blood Report: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        <span class="col-md-2 visit-lable">Blood Report Details:</span><span class="col-md-3 visit-lable-value">{{ $historyData->blood->report }}</span>
                                        @if(!empty($historyData->blood->image))
                                            <span class="col-md-2 visit-lable">Blood Report Image:</span>
                                            <span class="col-md-2 visit-lable-value">
                                                <img src="{{ cdnUrl($historyData->blood->image, null) }}" alt="" height="80px" width="100px">
                                            </span>
                                        @endif
                                    </div>
                                    <br>
                                @endif
                                @if(in_array('usg',$collectionData))
                                    <div class="row">
                                        <span class="col-md-2 visit-lable">* USG: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                    </div>
                                    <br>
                                @endif
                                @if(!empty($historyData->transfer))
                                    @if (in_array('transfer',$collectionData))
                                        <div class="row">
                                            <span class="col-md-2 visit-lable">* Transfer: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                            @if(!empty($historyData->transfer->payment))
                                                <span class="col-md-1 visit-lable">Payment:</span><span class="col-md-2 visit-lable-value">{{ $historyData->transfer->payment }}</span>
                                            @endif
                                            @if(!empty($historyData->transfer->method))
                                                <span class="col-md-1 visit-lable">Payment By: </span><span class="col-md-2 visit-lable-value">{{ $historyData->transfer->method }}</span>
                                            @endif
                                            <a href="javascript:void(0);" id="ivf_transfer_report_update" data-patient-id={{ encrypt($row->patients_id)}} data-cycle-no={{ encrypt($row->cycle_no)}} data-plan={{ encrypt($row->plan)}} data-visit={{ encrypt($row->visit)}}>
                                                IVF Transfer Report
                                            </a>
                                        </div>
                                        <br>
                                    @endif
                                @endif
                                @if(!empty($historyData->pre_operative->type) && $historyData->pre_operative->type == 'yes')
                                    <br>
                                    <div class="row">
                                        <span class="col-md-2 visit-lable">Pre Operative Minor: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        <span class="col-md-1">&nbsp;&nbsp;&nbsp;WIFE</span>
                                        @if(!empty($historyData->pre_operative->hiv_type))
                                            <span class="col-md-2 visit-lable">HIV :  &nbsp;<span class="visit-lable-value">{{ $historyData->pre_operative->hiv_type == 'positive' ? 'Positive' : 'Negative'}}</span></span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->hbsag_type))
                                            <span class="col-md-1 visit-lable">HBSAG :</span><span class="col-md-1 visit-lable-value">{{ $historyData->pre_operative->hbsag_type == 'positive' ? 'Positive' : 'Negative'}}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->vdrl))
                                            <span class="col-md-1 visit-lable">VDRL :</span><span class="col-md-1 visit-lable-value">{{ $historyData->pre_operative->vdrl == 'positive' ? 'Positive' : 'Negative'}}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->date))
                                            <span class="col-md-1 visit-lable">Date :</span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->date }}</span>
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($historyData->pre_operative->type) && $historyData->pre_operative->type == 'yes')
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        @if(!empty($historyData->pre_operative->cbc_mp))
                                            <span class="col-md-1 visit-lable">CBC MP :</span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->cbc_mp }}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->urine))
                                            <span class="col-md-1 visit-lable">Urine : </span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->urine }}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->blood_group))
                                            <span class="col-md-4 visit-lable">Blood Group : &nbsp;<span class="visit-lable-value">{{ $historyData->pre_operative->blood_group }}</span></span>
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($historyData->pre_operative->type) && $historyData->pre_operative->type == 'yes' && !empty($historyData->pre_operative->rbs))
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <span class="col-md-1 visit-lable">RBS :</span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->rbs }}</span>
                                    </div>
                                @endif
                                @if(!empty($historyData->pre_operative->hub->type) && $historyData->pre_operative->hub->type == 'yes')
                                    <br>
                                    <div class="row">
                                        <span class="col-md-2 visit-lable">Pre Operative Minor: &nbsp;<span class="visit-lable-value">Yes</span></span>
                                        <span class="col-md-1">HUSBAND</span>
                                        @if(!empty($historyData->pre_operative->hub->hiv_type))
                                            <span class="col-md-2 visit-lable">HIV :  &nbsp;<span class="visit-lable-value">{{ $historyData->pre_operative->hub->hiv_type == 'positive' ? 'Positive' : 'Negative'}}</span></span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->hub->hbsag_type))
                                            <span class="col-md-1 visit-lable">HBSAG :</span><span class="col-md-1 visit-lable-value">{{ $historyData->pre_operative->hub->hbsag_type == 'positive' ? 'Positive' : 'Negative'}}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->hub->vdrl))
                                            <span class="col-md-1 visit-lable">VDRL :</span><span class="col-md-1 visit-lable-value">{{ $historyData->pre_operative->hub->vdrl == 'positive' ? 'Positive' : 'Negative'}}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->hub->date))
                                            <span class="col-md-1 visit-lable">Date :</span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->hub->date }}</span>
                                        @endif
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        @if(!empty($historyData->pre_operative->hub->cbc_mp))
                                            <span class="col-md-1 visit-lable">CBC MP :</span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->hub->cbc_mp }}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->hub->urine))
                                            <span class="col-md-1 visit-lable">Urine : </span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->hub->urine }}</span>
                                        @endif
                                        @if(!empty($historyData->pre_operative->hub->blood_group))
                                            <span class="col-md-4 visit-lable">Blood Group : &nbsp;<span class="visit-lable-value">{{ $historyData->pre_operative->hub->blood_group }}</span></span>
                                        @endif
                                    </div>
                                @endif
                                @if(!empty($historyData->pre_operative->hub->type) && $historyData->pre_operative->hub->type == 'yes' && !empty($historyData->pre_operative->hub->rbs))
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <span class="col-md-1 visit-lable">RBS :</span><span class="col-md-2 visit-lable-value">{{ $historyData->pre_operative->hub->rbs }}</span>
                                    </div>
                                @endif
                                <br>
                                @if(!empty($historyData->plan) || !empty($historyData->follow_up) || !empty($historyData->skip_reason))
                                    <div class="row">
                                        @if(!empty($historyData->plan))
                                            <span class="col-md-2 visit-lable">Transfer Plan : &nbsp;<span class="visit-lable-value">{{$planData[$historyData->plan]}}</span></span>
                                        @endif
                                        @if(!empty($historyData->follow_up))
                                            <span class="col-md-1 visit-lable">Follow Up:&nbsp;</span><span class="col-md-2 visit-lable-value">{{$historyData->follow_up}}</span>
                                        @endif
                                        @if(!empty($historyData->skip_reason))
                                            <span class="col-md-1 visit-lable">Reason:&nbsp;</span><span class="col-md-3 visit-lable-value">{{$historyData->skip_reason}}</span>
                                        @endif
                                    </div><br>
                                @endif
                                @if(!empty($historyData->remark))
                                    <div class="row">
                                        <span class="col-md-1 visit-lable">Remark : &nbsp;</span>
                                        <span class="visit-lable-value">{{$historyData->remark}}</span>
                                    </div><br>
                                @endif
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
                                    @if($countProtocolTable > 0 && (!empty($hmgArray) || !empty($hmgBrandArray) || !empty($fshArray) || !empty($antagonistArray) || !empty($fshBrandArray)))
                                        <div class="row">
                                            <span class="col-md-1 visit-lable-value">Protocol Data</span><br><br>
                                        </div>
                                        <table class='table m-b-0'>
                                            <thead>
                                                <tr>
                                                    <th>Cycle Days</th>
                                                    <th>Simulation Days</th>
                                                    <th>Date</th>
                                                    <th>Injection</th>
                                                    <th>HMG</th>
                                                    <th>HMG Brand Name</th>
                                                    <th>FSH</th>
                                                    <th>FSH Brand Name</th>
                                                    <th>Antagonist</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($historyData->protocol as $row)
                                                    @if(!empty($row->hmg) || !empty($row->hmg_brand_name) || !empty($row->fsh) || !empty($row->fsh_brand_name) || !empty($row->antagonist))
                                                        <tr>
                                                            <td>{{!empty($row->day) ? $row->day : '-'}}</td>
                                                            <td>{{!empty($row->s_day) ? 's'.$row->s_day : '-'}}</td>
                                                            <td>{{!empty($row->date) ? $row->date : '-'}}</td>
                                                            <td>{{!empty($row->injection) ? $injectionData[$row->injection] : '-'}}</td>
                                                            <td>{{!empty($row->hmg) ? $row->hmg : '-'}}</td>
                                                            <td>{{!empty($row->hmg_brand_name) ? $row->hmg_brand_name : '-'}}</td>
                                                            <td>{{!empty($row->fsh) ? $row->fsh : '-'}}</td>
                                                            <td>{{!empty($row->fsh_brand_name) ? $row->fsh_brand_name : '-'}}</td>
                                                            <td>{{!empty($row->antagonist) ? $row->antagonist : '-'}}</td>
                                                            <td>{{!empty($row->time) ? $row->time : '-'}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    <br>
                                @endif
                                @if(!empty($historyData->co_type))
                                    <div class="row">
                                        <span class="col-md-1 visit-lable">C/O : &nbsp;</span>
                                        <span class="visit-lable-value">{{implode(',',$historyData->co_type)}}</span>
                                    </div><br>
                                @endif
                                @if(!empty($historyData->medicinedata))
                                    <div>
                                        <div class="row pl-2">
                                            <h5 class="col-md-2 visit-lable-value">Treatment</h5><br><br>
                                            <hr>
                                        </div>
                                        <hr>
                                        @php
                                            $historyTreatmentView = null;
                                            if(!empty($historyData->medicinedata)){
                                                $historyTreatmentView = $historyData->medicinedata;
                                            }
                                        @endphp
                                        @if($historyTreatmentView)
                                            <table class='table m-b-0 medicine-data-table'>
                                                <tbody>
                                                @php
                                                    $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>'Weekly / 1 time','6'=>'Weekly / 2 time','7'=>'Stat'];
                                                    $old_medicine_time = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                                                    // unset($historyTreatmentView->medicinedata);
                                                @endphp
                                                @foreach ($historyData->medicinedata as $row)
                                                    <tr>
                                                        <td><strong>Medicine :</strong> {{!empty($row->medicine) ?  $row->medicine : '-'}}</td>
                                                        <td><strong>Status : </strong>
                                                            @switch($row->medicine_status)
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
                                                        </td>
                                                        <td>
                                                            <strong>Dose : </strong>
                                                            @if(!empty($row->dose))
                                                                @if (array_key_exists($row->dose, $old_dose))
                                                                    {{ $old_dose[$row->dose] }}
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td><strong>Days : </strong>{{(!empty($row->no)) ? $row->no : '-' }}</td>
                                                        <td><strong>Quantity : </strong>{{(!empty($row->quantity)) ? $row->quantity : '-' }}</td>
                                                        <td>
                                                            <strong>Time : </strong>
                                                            @if (!empty($row->medicine_time))
                                                                @foreach ($row->medicine_time as $time)
                                                                    {{$old_medicine_time[$time]}}
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="row">
                                    @if(!empty($historyData->transfer->upt_type))
                                        <span class="col-md-2 visit-lable">Upt Type :&nbsp;<span class="visit-lable-value">{{ ucfirst($historyData->transfer->upt_type)}}</span></span>
                                    @endif
                                    @if(!empty($historyData->transfer->result_type))
                                        <span class="col-md-2 visit-lable">Result Type :&nbsp;<span class="visit-lable-value">{{ ucfirst($historyData->transfer->result_type)}}</span></span>
                                    @endif
                                    @if(!empty($historyData->transfer->follow_up))
                                        <span class="col-md-2 visit-lable">Follow Up :&nbsp;<span class="visit-lable-value">{{ ucfirst($historyData->transfer->follow_up)}}</span></span>
                                    @endif
                                </div>
                                @if(!empty($historyData->transfer->report))
                                    <div class="row">
                                        <h5 class="col-md-2 visit-lable-value">Report</h5><br><br>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        @foreach($historyData->transfer->report as $key=>$value)
                                            <div class="col-md-1 mt-1">
                                                <img src={{URL::to($value)}} class="report-image" height="100px" width="100px">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if(!empty($historyData->plan))
                                    <div class="row">
                                        <span class="col-md-2 visit-lable">Transfer Plan : &nbsp;<span class="visit-lable-value">{{$planData[$historyData->plan]}}</span></span>
                                    </div>
                                @endif
                                @if(!empty($historyData->co_type))
                                    <br>
                                    <div class="row">
                                        <span class="col-md-1 visit-lable">C/O : &nbsp;</span>
                                        <span class="visit-lable-value">{{implode(',',$historyData->co_type)}}</span>
                                    </div><br>
                                @endif
                                @if(!empty($historyData->medicinedata))
                                    <div>
                                        <br>
                                        <div class="row">
                                            <h5 class="col-md-2 visit-lable-value">Treatment</h5><br><br>
                                            <hr>
                                        </div>
                                        <hr>
                                        @php
                                            $historyTreatmentView = null;
                                            if(!empty($historyData->medicinedata)){
                                                $historyTreatmentView = $historyData->medicinedata;
                                            }
                                        @endphp
                                        @if($historyTreatmentView)
                                            <table class='table m-b-0 medicine-data-table'>
                                                <tbody>
                                                    @php
                                                        $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>'Weekly / 1 time','6'=>'Weekly / 2 time','7'=>'Stat'];
                                                        $old_medicine_time = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                                                    @endphp
                                                    @foreach ($historyData->medicinedata as $row)
                                                        <tr>
                                                            <td><strong>Medicine :</strong> {{!empty($row->medicine) ?  $row->medicine : '-'}}</td>
                                                            <td><strong>Status : </strong>
                                                                @if(!empty($row->medicine_status))
                                                                @switch($row->medicine_status)
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
                                                            </td>
                                                            <td>
                                                                <strong>Dose : </strong>
                                                                @if(!empty($row->dose))
                                                                    @if (array_key_exists($row->dose, $old_dose))
                                                                        {{ $old_dose[$row->dose] }}
                                                                    @endif
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td><strong>Days : </strong>{{(!empty($row->no)) ? $row->no : '-' }}</td>
                                                            <td><strong>Quantity : </strong>{{(!empty($row->quantity)) ? $row->quantity : '-' }}</td>
                                                            <td>
                                                                <strong>Time : </strong>
                                                                @if (!empty($row->medicine_time))
                                                                    @foreach ($row->medicine_time as $time)
                                                                        {{$old_medicine_time[$time]}}
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong>
                                                                    Route: {{!empty($row->route) ? $row->route : null}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($isForm && $skipPlan == $pStatus)
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
                                            {{Form::text("data[lmp][date]",!empty($historyLmddateDate) ? cdate($historyLmddateDate)->format('D d M Y') : null ,['class'=>'form-control history-lmd-date','autocomplete'=>'off'])}}
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
                                                UT :
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
                                        <span class="col-md-1 p-2">M</span>
                                    </div>
                                    @if($visit == 2)
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
                                    @endif
                                    <br>
                                {{-- @endif --}}
                                {{-- ivf comman form --}}
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
                                @if($visit != 2 &&   $cycleNumber == $cycleNumber)
                                    {{-- @if($pStatus != 1)
                                        <div class="row mt-1">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    O\E :
                                                </label>
                                            </div>
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">ET :</label>
                                            </div>
                                            <div class="col-md-4">
                                                {{Form::text("data[oe][et_details]",'',['class'=>'form-control','placeholder'=>'Enter ET Details'])}}
                                            </div>
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">Other :</label>
                                            </div>
                                            <div class="col-md-4">
                                                {{Form::text("data[oe][other_details]",'',['class'=>'form-control','placeholder'=>'Enter Other Details'])}}
                                            </div>
                                        </div>
                                    @else --}}

                                    {{-- @endif --}}
                                    {{Form::hidden('is_trigger','yes')}}
                                    @php
                                        $collectionEmbroyValueData = !empty($historyData->collected->report) && !empty($historyData->collected->report->embroy->type) && $historyData->collected->report->embroy->type == 'yes' ? false : true;
                                    @endphp
                                    @if($collectionEmbroyValueData)
                                        <div class="{{'row mt-1'}}">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[collection][]','collected','',['id'=>'collected','class'=>'simen-sample'])}}
                                                    <label for="collected">
                                                        Is simen sample collected ?
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="{{'simen_data d-none ml-3'}}">
                                            <div class="row mt-1">
                                                <div class="col-md-1">
                                                    <label for="collected pull-right">
                                                        Frozen?
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
                                                {{Form::hidden('ivf_report_id', '' , ['id' => 'ivf_report_id'])}}
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Date : &nbsp;</span>
                                                        {{Form::text("data[collected][date]",'',['class'=>'form-control datetimepicker date'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[collected][report][type]','report','',['id'=>'collected-report'])}}
                                                        <label for="collected-report">
                                                            Report
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[collected][report][embroy][status]','embroy','',['id'=>'embroy','class'=>'embroy'])}}
                                                        <label for="embroy">
                                                            Is Embroy Ready ?
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 embroy-yes d-none">
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

                                            </div>
                                        </div>
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
                                                    {{Form::text("report_date",cdate($lastAppointment->date)->format('D d M Y'),['class'=>'form-control datetimepicker report_date','required'])}}
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
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                {{Form::checkbox('data[collection][]','progesterone','',['id'=>'progesterone'])}}
                                                <label for="progesterone">
                                                    Do satrting progesterone?
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
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Blood report: &nbsp;</span>
                                                        {{Form::text("data[blood][report]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {{Form::file('data[blood][image]',['class'=>'form-control report-file'])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($pStatus != 3 )
                                        <div class="row mt-1">
                                            <div class="col-md-2">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[collection][]','usg','',['id'=>'usg'])}}
                                                    <label for="usg">
                                                    USG
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
                                            <div class="col-md-1">
                                                <div class="checkbox">
                                                    {{Form::checkbox('data[collection][]','transfer','',['id'=>'transfer','class'=>'transfer'])}}
                                                    <label for="transfer">
                                                        Transfer
                                                    </label>
                                                </div>
                                            </div>
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

                                {{-- end ivf comman form --}}
                                <br>
                                {{-- pre operative data --}}
                                {{Form::hidden("data[pre_operative][type]",'no')}}
                                {{Form::hidden("data[pre_operative][update_status]",'no')}}
                                {{Form::hidden("data[pre_operative][hub][type]",'no')}}
                                {{Form::hidden("data[pre_operative][hub][update_status]",'no')}}
                                @if(empty($historyData->pre_operative->type) OR $historyData->pre_operative->type == 'no' && $cycleNumber == $cycleNumber)
                                    @if($pStatus != 2)
                                        <div class="row anc-profile">
                                            <div class="col-md-2 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Pre Operative Minor:
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][type]",'yes','',['id'=>'pre_operative_type_yes','class'=>'pre-operative-type'])}}
                                                    <label for="pre_operative_type_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("data[pre_operative][type]",'no',true,['id'=>'pre_operative_type_no','class'=>'pre-operative-type'])}}
                                                    <label for="pre_operative_type_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                             <div class="col-sm-1 pr-0 pre-operative d-none">
                                                    Wife
                                            </div>
                                            <div class="col-md-1 pr-0 pre-operative d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HIV :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre-operative d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][hiv_type]",'positive','',['id'=>'pr_operative_hiv_positive','class'=>'pre-operative-hiv'])}}
                                                    <label for="pr_operative_hiv_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("data[pre_operative][hiv_type]",'negative','',['id'=>'pr_operative_hiv_nagative','class'=>'pre-operative-hiv'])}}
                                                    <label for="pr_operative_hiv_nagative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 pre-operative d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HBSAG :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre-operative d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][hbsag_type]",'positive','',['id'=>'pre_operative_hbsag_positive','class'=>'pre-operative-hbsag'])}}
                                                    <label for="pre_operative_hbsag_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("data[pre_operative][hbsag_type]",'negative','',['id'=>'pre_operative_hbsag_nagative','class'=>'pre-operative-hbsag'])}}
                                                    <label for="pre_operative_hbsag_nagative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-operative d-none">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Date : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][date]",\Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        CBC MP : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][cbc_mp]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Urine : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][urine]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-operative d-none">
                                            <div class="col-md-2"></div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                    Blood Group : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][blood_group]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        RBS : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][rbs]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    VDRL :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][vdrl]",'positive','',['id'=>'pre_operative_vdrl_positive','class'=>'pre-operative-vdrl'])}}
                                                    <label for="pre_operative_vdrl_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("data[pre_operative][vdrl]",'negative','',['id'=>'pre_operative_vdrl_negative','class'=>'pre-operative-vdrl'])}}
                                                    <label for="pre_operative_vdrl_negative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                    @endif
                                @else
                                    @php
                                        $pType = !empty($historyData->pre_operative->type) ? $historyData->pre_operative->type : null;
                                        $dType = !empty($historyData->pre_operative->date) ? $historyData->pre_operative->date : null;
                                        $dType = !empty($historyData->pre_operative->date) ? $historyData->pre_operative->date : null;
                                        $hivType = !empty($historyData->pre_operative->hiv_type) ? $historyData->pre_operative->hiv_type : null;
                                        $vdrlType = !empty($historyData->pre_operative->vdrl) ? $historyData->pre_operative->vdrl : null;
                                        $rbsType = !empty($historyData->pre_operative->rbs) ? $historyData->pre_operative->rbs : null;
                                        $bloodType = !empty($historyData->pre_operative->blood_group) ? $historyData->pre_operative->blood_group : null;
                                        $urineType = !empty($historyData->pre_operative->urine) ? $historyData->pre_operative->urine : null;
                                        $cbcType = !empty($historyData->pre_operative->cbc_mp) ? $historyData->pre_operative->cbc_mp : null;
                                    @endphp
                                    {{Form::hidden("data[pre_operative][type]",$pType)}}
                                    {{Form::hidden("data[pre_operative][date]",$dType)}}
                                    {{Form::hidden("data[pre_operative][hiv_type]",$hivType)}}
                                    {{Form::hidden("data[pre_operative][vdrl]",$vdrlType)}}
                                    {{Form::hidden("data[pre_operative][rbs]",$rbsType)}}
                                    {{Form::hidden("data[pre_operative][blood_group]",$bloodType)}}
                                    {{Form::hidden("data[pre_operative][urine]",$urineType)}}
                                    {{Form::hidden("data[pre_operative][cbc_mp]",$cbcType)}}
                                    {{Form::hidden("data[pre_operative][update_status]",'yes')}}
                                @endif
                                {{-- end pre operative data --}}
                                @if(empty($historyData->pre_operative->hub->type) OR $historyData->pre_operative->hub->type == 'no' && $cycleNumber == $cycleNumber)
                                    @if($pStatus != 2)
                                        <div class="row anc-profile">
                                            <div class="col-md-2 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Pre Operative Minor:
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][hub][type]",'yes','',['id'=>'pre_operative_type_yes_hub','class'=>'pre-operative-type-hub'])}}
                                                    <label for="pre_operative_type_yes_hub">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("data[pre_operative][hub][type]",'no',true,['id'=>'pre_operative_type_no_hub','class'=>'pre-operative-type-hub'])}}
                                                    <label for="pre_operative_type_no_hub">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 pr-0 pre_operative_type_hub d-none">
                                                    Husband
                                            </div>
                                            <div class="col-md-1 pr-0 pre_operative_type_hub d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HIV :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre_operative_type_hub d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][hub][hiv_type]",'positive','',['id'=>'pr_operative_hub_hiv_positive','class'=>'pre-operative-hub-hiv'])}}
                                                    <label for="pr_operative_hub_hiv_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("data[pre_operative][hub][hiv_type]",'negative','',['id'=>'pr_operative_hub_hiv_nagative','class'=>'pre-operative-hub-hiv'])}}
                                                    <label for="pr_operative_hub_hiv_nagative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 pre_operative_type_hub d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HBSAG :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre_operative_type_hub d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][hub][hbsag_type]",'positive','',['id'=>'pre_operative_hub_hbsag_positive','class'=>'pre-operative-hub-hbsag'])}}
                                                    <label for="pre_operative_hub_hbsag_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("data[pre_operative][hub][hbsag_type]",'negative','',['id'=>'pre_operative_hub_hbsag_nagative','class'=>'pre-operative-hub-hbsag'])}}
                                                    <label for="pre_operative_hub_hbsag_nagative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre_operative_type_hub d-none">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Date : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][hub][date]",\Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        CBC MP : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][hub][cbc_mp]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Urine : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][hub][urine]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre_operative_type_hub d-none">
                                            <div class="col-md-2"></div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                    Blood Group : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][hub][blood_group]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        RBS : &nbsp;
                                                    </span>
                                                    {{Form::text("data[pre_operative][hub][rbs]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    VDRL :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("data[pre_operative][hub][vdrl]",'positive','',['id'=>'pre_operative_hub_vdrl_positive','class'=>'pre-operative-hub-vdrl'])}}
                                                    <label for="pre_operative_hub_vdrl_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("data[pre_operative][hub][vdrl]",'negative','',['id'=>'pre_operative_hub_vdrl_negative','class'=>'pre-operative-hub-vdrl'])}}
                                                    <label for="pre_operative_hub_vdrl_negative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    @php
                                        $hType = !empty($historyData->pre_operative->hub->type) ? $historyData->pre_operative->hub->type : null;
                                        $dType = !empty($historyData->pre_operative->hub->date) ? $historyData->pre_operative->hub->date : null;
                                        $dType = !empty($historyData->pre_operative->hub->date) ? $historyData->pre_operative->hub->date : null;
                                        $hivType = !empty($historyData->pre_operative->hub->hiv_type) ? $historyData->pre_operative->hub->hiv_type : null;
                                        $vdrlType = !empty($historyData->pre_operative->hub->vdrl) ? $historyData->pre_operative->hub->vdrl : null;
                                        $rbsType = !empty($historyData->pre_operative->hub->rbs) ? $historyData->pre_operative->hub->rbs : null;
                                        $bloodType = !empty($historyData->pre_operative->hub->blood_group) ? $historyData->pre_operative->hub->blood_group : null;
                                        $urineType = !empty($historyData->pre_operative->hub->urine) ? $historyData->pre_operative->hub->urine : null;
                                        $cbcType = !empty($historyData->pre_operative->hub->cbc_mp) ? $historyData->pre_operative->hub->cbc_mp : null;
                                    @endphp
                                    {{Form::hidden("data[pre_operative][hub][type]",$hType)}}
                                    {{Form::hidden("data[pre_operative][hub][date]",$dType)}}
                                    {{Form::hidden("data[pre_operative][hub][hiv_type]",$hivType)}}
                                    {{Form::hidden("data[pre_operative][hub][vdrl]",$vdrlType)}}
                                    {{Form::hidden("data[pre_operative][hub][rbs]",$rbsType)}}
                                    {{Form::hidden("data[pre_operative][hub][blood_group]",$bloodType)}}
                                    {{Form::hidden("data[pre_operative][hub][urine]",$urineType)}}
                                    {{Form::hidden("data[pre_operative][hub][cbc_mp]",$cbcType)}}
                                    {{Form::hidden("data[pre_operative][hub][update_status]",'yes')}}
                                @endif
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
                                                    {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','multiple'=>true])}}
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
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::textarea('data[remark]','', ['class' => 'form-control no-resize remark call-response','placeholder' => 'Remark','rows' => '5'])}}
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
                                {{Form::hidden("data[pre_operative][type]",'no')}}
                                {{Form::hidden("data [pre_operative][update_status]",'no')}}
                                {{Form::hidden("data[pre_operative][hub][type]",'no')}}
                                {{Form::hidden("data[pre_operative][hub][update_status]",'no')}}
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
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Select Medicine :
                                                </label>
                                            </div>
                                            <div class="col-md-9 complain-multi medicine-picker">
                                                {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicine-data','multiple'=>true])}}
                                            </div>
                                        </div>
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
                    'id'=>'ivf-transfer-report-update'
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
                                <span class="input-group-addon">Pick up Date: &nbsp;</span>
                                {{Form::text("pick_up_date", '', ['class'=>'form-control datetimepicker pick_up_date'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Simulation Protocol: &nbsp;</span>
                                {{Form::text("simulation_protocol",'',['class'=>'form-control simulation_protocol', 'maxlength' => 250])}}
                            </div>
                        </div>
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Mll: &nbsp;</span>
                                {{Form::text("mll",'',['class'=>'form-control number mll', 'maxlength' => 250, 'id' => 'mll'])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Ml: $&nbsp;</span>
                                {{Form::text("ml",'',['class'=>'form-control number ml', 'maxlength' => 250, 'id' => 'ml'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Oocyte Quality: &nbsp;</span>
                                {{Form::text("oocycle_quality",'',['class'=>'form-control oocycle_quality', 'maxlength' => 250])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Sperm Quality: &nbsp;</span>
                                {{Form::text("sperm_quality",'',['class'=>'form-control sperm_quality', 'maxlength' => 250])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Fertilization Procedure: &nbsp;</span>
                                {{Form::text("fertilization_procedure",'',[
                                    'class'=>'form-control fertilization_procedure',
                                    'maxlength' => 250
                                ])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                    <button type="button" class="btn btn-primary waves-effect update-ivf-transfer-report">Save</button>
                    <button type="button" class="btn btn-primary waves-effect update-ivf-transfer-report" value="1">Save & Preview</button>
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
                    {{-- <a href="#" class="btn btn-primary waves-effect save-btn disabled next-appointment-form">Save</a>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- view file and edit data --}}
    <div class="modal fade view-file-edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog view-file-modal-dialog">
          <div class="modal-content">
            <div class="modal-header header-bottom-border">
              <button type="button" class="close anc-details-close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title rm-btn" id="myModalLabel">Date:- <span class="ivf-appointment-date"></span></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" id="myModalLabel">Cycle No:- <span class="ivf-appointment-cycle-no"></span></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title rm-btn" id="myModalLabel">Visit:- <span class="ivf-appointment-visit-no"></span></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mr-5">
                        <a class="btn edit-btn rm-btn btn-sm btn-primary">Edit</a>
                        <a class="btn print-btn btn-sm btn-primary">Print</a>
                        <a class="btn print-fet-report btn-sm btn-primary">Print</a>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="ivf-details-data"></div>
            </div>

            <div class="modal-footer footer-top-border text-right d-inline-block">
                <button type="button" class="btn btn-primary next-appointment-details rm-btn" data-type="1">Prev</button>
                <button type="button" class="btn btn-primary next-appointment2 next-appointment-details rm-btn" data-type="2">Next</button>
            </div>
          </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="{{url('js/ivf.js')}}"></script>
    <script type="text/javascript">
        var doseData = @json($doseData);
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
                ivfVisit = $('.next-appointment-details').data('visit');
                ivfString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&visit='+ivfVisit+'&is_print=1';
                getIvfHistoryData(ivfString);
            });

            $(document).on('click','.print-fet-report',function(e){
                e.preventDefault();
                fetReportString = 'patient_id='+ivfPId+'&cycle_no='+ivfCycleNo+'&plan='+ivfPlan+'&is_print=1';
                getFetReport(fetReportString);
            });

            $(document).on('click','.edit-btn',function(){
                ivfVisit = $('.next-appointment-details').data('visit');
                if(ivfVisit == null){
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
                    var now = new Date($('.last-appointment-date').val());
                    now.setDate(now.getDate()+days);
                    now = moment(now).format('ddd DD MMM YYYY');
                    $('.tranfer-follow-date').val(now);
                    $('.transfer-print').removeClass('d-none');
                    $('.transfer-print').addClass('d-inline-block');
                }
            });

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
                }else{
                    $('.progesterone_data').addClass('d-none');
                    $('.progesterone_yes').addClass('d-none');
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
                var formData = new FormData($("#ivf-form")[0]);
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
                if(transferPlan != '' && transferPlan == 2 && ivfReport == '' && pType == 1){
                    window.location.href  = "{{URL::to('ivf-plan-report/')}}"+"/"+plan+"/"+pId+"/"+cNumber;
                    return true;
                }
                var valid = 1;
                $('.lmp-date-error').text('');
                $('.skip-plan-error').text('');
                $('.skip-reason-error').text('');
                $('.seen-by-error').text('');
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
                if(valid == 0){
                    return true;
                }
                var frozen = $('#progesteroneyes:checked').val();
                var visitNo = $('.visit-no').val();
                var planType = $('.plan_type').val();
                if((frozen == 'no' || typeof frozen == 'undefined') && ($('.is-transfer').val() != 'yes') && (visitNo == 3 || visitNo == 4) && planType == 3){
                    if(visitNo == 3){
                        swal({
                            title: "Is simen sample not collected!",
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
                        swal("Is simen sample not collected!");
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

            $(document).on('click','.update-ivf-transfer-report',function(e){
                var ivfTransferReportId = $('#update_ivf_transfer_report_id');
                var ivfTransferReport = $('#ivf-transfer-report-update').serialize();
                if(this.value == 1){
                    ivfTransferReport = ivfTransferReport + '&is_print=1';
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('update-ivf-transfer-report-data')}}",
                    dataType: 'json',
                    type: 'POST',
                    data: ivfTransferReport
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
                $('.edit-btn').data('id','');
                if(data.ivf_type == 1){
                    $('.ivf-appointment-cycle-no').text(ivfCycleNo);
                    $('.ivf-appointment-date').text('');
                    $('.ivf-details-data').html(data.data);
                    var visitNumberValue = data.visit == null ? 1 : data.visit;
                    $('.ivf-appointment-visit-no').text(visitNumberValue);
                    $('.next-appointment-details').data('visit',data.visit);
                    $('.next-appointment2').data('type',2);
                    $('.edit-btn').data('id',data.enc_ivf_id);
                    if(typeof data.date != 'undefined'){
                        var linkDate = moment(new Date(data.date)).format('YYYY-MM-DD HH:mm:ss');
                        var date = moment(new Date(data.date)).format('DD MMMM YYYY');
                        $('.ivf-appointment-date').text(date);
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
</script>
@stop
