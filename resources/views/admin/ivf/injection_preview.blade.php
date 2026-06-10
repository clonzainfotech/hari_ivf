<link rel="stylesheet" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<style>
    .injection-report-table {
        font-family: roboto-black;
        width: 100%;
    }
    .injection-report-table{
        margin-bottom: 10px;
    }
    .injection-report-table{
        text-align: left;
    }

    .doctor-category{
        color: #01d8da;
    }
    .injection-report-table thead th{
        height: 35px;
    }

    .injection-report-table tr {
        height: 27px;
    }
    .table-footer{
        font-weight: 900;
        color: #01d8da;
        height: 50px;
        font-size: 20px;
    }
    td {
        height: 25px;
        font-size: 14px;
        padding: 3px 3px;
    }
    th{
        text-align: left;
    }

    .report-header-tr {
        text-align: left;
        height: 35px;
    }
    .report-header-tr-th {
        background-color: #bdf3f5;
        font-size: 13px;
    }

    .white-font {
        color: #ffffff;
    }
    .header-print-title{
        font-size: 20px;
        background-color: #f5f5f5;
        color: #55555a;
        width: 100%;
    }
    .main-print-anc-div{
        margin: 0 auto;
        width: 100%;
    }
    .input-group-addon.title{
        font-size: 16px;
        font-weight: 900;
    }
    .seperator {
        border-top: 0.5px solid #dee2e6;
        border-left: 0.5px solid #dee2e6;
    }

    .p70 {
        padding-top: 70px;
    }

    .d-none {
        display: none !important;
    }
    .text-danger{
        color:red;
    }
    .f-date{
        font-weight: bold;
    }
    .anc-label {
        font-weight: normal;
    }
    .w-100{
        width: 100px !important;
    }
    .w-200{
        width: 200px !important;
    }
    .w-300{
        width: 300px !important;
    }
    .w-400{
        width: 400px !important;
    }
    .w-500{
        width: 500px !important;
    }
    .w-150{
        width: 150px !important;
    }
    .w-250{
        width: 250px !important;
    }
    .w-350{
        width: 350px !important;
    }
    .w-450{
        width: 450px !important;
    }
    .w-550{
        width: 550px !important;
    }
    .lmd-lable{
        font-size: 17px;
    }
    .panel-primary{
        border: 1px solid;
        padding: 11px;
    }
    .trigger-box{
        margin-left: 35%;
    }
    @page { margin-top : 120px; margin-bottom : 80px;}
</style>

<table class="table injection-report-table">
    <thead>
        <th class="seperator">Cycle Day</th>
        <th class="seperator">S</th>
        <th class="seperator">Date</th>
        <th class="seperator">Injection Name</th>
        <th class="seperator">Rt. Ovary</th>
        <th class="seperator">Lt. Ovary</th>
        <th class="seperator">ET</th>
        <th class="seperator">Remark</th>
    </thead>
    <tbody>
        @php
            $injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'CC + HMG','8'=>'CC + FSH','9'=>'Antagonist'];
            $hmgDose = 0;
            $antaDose = 0;
            $fshDose = 0;
            $se2Data = [];
            $slhData = [];
            $bloodReport = [];
        @endphp
        @if(count($ivfHistory) > 0)
            @foreach($ivfHistory as $row)
                @php
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
                @if(!empty($data->protocol))
                    @foreach($data->protocol as $key=>$value)
                        <tr>
                            <td class="seperator">{{!empty($value->day) ? $value->day : '-'}}</td>
                            <td class="seperator">{{!empty($value->s_day) ? $value->s_day : '-'}}</td>
                            <td class="seperator">{{!empty($value->date) ? $value->date : '-'}}</td>
                            <td class="seperator">{{!empty($value->injection) ? $injectionData[$value->injection] : null}}</td>
                            <td class="seperator">
                                @if($key == 1)
                                    @if($row->visit == 2)
                                        {{!empty($data->oe->ovary->left->afcs) ? $data->oe->ovary->left->afcs : '-'}}
                                    @else
                                        {{!empty($data->ovary->ovary_type->left->details) ? $data->ovary->ovary_type->left->details : '-'}}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="seperator">
                                @if($key == 1)
                                    @if($row->visit == 2)
                                        {{!empty($data->oe->ovary->right->afcs) ? $data->oe->ovary->right->afcs : '-'}}
                                    @else
                                        {{!empty($data->ovary->ovary_type->right->details) ? $data->ovary->ovary_type->right->details : '-'}}
                                    @endif
                                @else  
                                    -
                                @endif
                            </td>
                            <td class="seperator">{{$key == 1 && !empty($data->et_details) ? $data->et_details : '-'}}</td>
                            <td class="seperator">{{$key == 1 && !empty($data->remark) ? $data->remark : '-'}}</td>
                        </tr>
                        @php
                            $hmgDose += !empty($value->hmg) && is_numeric($value->hmg) ? $value->hmg : 0;
                            $antaDose += !empty($value->antagonist) && is_numeric($value->antagonist) ? $value->antagonist : 0;
                            $fshDose += !empty($value->fsh) && is_numeric($value->fsh) ? $value->fsh : 0;
                        @endphp
                    @endforeach
                @endif
            @endforeach
        @else
            <td colspan='8' class="text-center">No records available</td>
        @endif
    </tbody>
</table>
<div class="panel panel-primary transfer-print">
    <div class="row">
        <div class="col-md-3">
            <div>S.E2:- {{implode(',',$se2Data)}}</div>
            <div>S.LH:- {{implode(',',$slhData)}}</div>
            <div>S.P2:- {{implode(',',$bloodReport)}}</div>
            @if(!empty($duringPickupStatus))
                <div>Hystroscopy During Pickup :- {{$duringPickupStatus}}</div>
            @endif
        </div>
        <div class="col-md-4">
            <table class="table m-b-0 table-hover ivf-print-table trigger-box">
                <tbody>
                    @php
                        $triggerHistoryData = $triggerHistory ? json_decode($triggerHistory->description) : null;
                        $hcgTrigger = !empty($triggerHistoryData->trigger->hcg->status) ? $triggerHistoryData->trigger->hcg->status : null;
                        $dualTrigger = !empty($triggerHistoryData->trigger->decapeptyl->status) ? $triggerHistoryData->trigger->decapeptyl->status : null;
                    @endphp
                    <tr>
                        <td class="seperator">
                            Trigger :- &nbsp;&nbsp; {{$hcgTrigger.(!empty($hcgTrigger) ? '+' : '').$dualTrigger}}
                        </td>
                    </tr>
                    <tr>
                        <td class="seperator">
                            Date & Time :- &nbsp;&nbsp;
                            @if($triggerHistoryData)
                                {{$triggerHistory ? (cdate($triggerHistory->trigger_date)->format('D d M Y')) : ''}} {{!empty($triggerHistoryData->trigger->hcg->time) ? $triggerHistoryData->trigger->hcg->time : (!empty($triggerHistoryData->trigger->decapeptyl->time) ? $triggerHistoryData->trigger->decapeptyl->time : null)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="seperator">
                            OPU :- &nbsp;&nbsp;
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
                        <td class="seperator">
                            Date & Time  :- &nbsp;&nbsp;
                            @if($triggerHistoryData)
                                {{$triggerDate.' '.cdate($triggerDateTime)->format('h:i a')}}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <div class="extra-label">Total HMG Dose :- {{$hmgDose}}</div>
            <div class="extra-label">Total FSH Dose :- {{$fshDose}}</div>
            <div class="extra-label">Total Antagonist Dose :- {{$antaDose}}</div>
        </div>
    </div>
</div>