@if($status == 2)
    <link rel="stylesheet" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
@endif
<style>
    .patient-name {
        text-transform: uppercase;
    }
    .patient-detail {
        /* margin-bottom: 20px; */
    }
    /* @media (min-width: 576px){
        .view-file-modal-dialog {
            max-width: 1800px !important;
            margin: 1.75rem auto;
        }
    } */
    .modal-contnet{
        width: 1500px !important;
    }
</style>
@php
    $todayDate = Carbon\Carbon::Now();
    $planValueData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
    $ivfPlanReportValue = null;
    if(!empty($ivfPlanReport)){
        $ivfPlanReportValue = json_decode($ivfPlanReport->description);
    }
@endphp
<div class="print-ivf-div">
    <div class="panel panel-primary">
        <h3 class="text-center">{{config('app.hospitalname1')}}</h3>
        <div class="row patient-detail mt-4">
            <div class="col-md-6">
                <span class="label-name">Patinet Name :</span>
                <strong class="patient-name">{{ ucwords(strtolower($patient['name']))}}</strong>
            </div>
            <div class="col-md-6">
                <span class="label-name">Attempt Of Cycle :</span>
                <strong class="cycle-name">{{'1st cycle'}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="label-name">Age :</span>
                <strong>{{$patient['age'] }}</strong>
            </div>
            <div class="col-md-6">
                <span class="label-name">Type Cycle :</span>
                <strong class="value">{{$planValueData[$seconivfHistoryData->plan]}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="label-name">No Of Embreyo :</span>
                @php
                    $isEmbroy = 'Not Ready';
                    if(!empty($ivfPlanReportValue->loop_1) || !empty($ivfPlanReportValue->loop_2) || !empty($ivfPlanReportValue->loop_3) || !empty($ivfPlanReportValue->loop_4)){
                        $isEmbroy = 'Ready';
                    }
                @endphp
                <strong class="value">{{$isEmbroy}}</strong>
            </div>
            <div class="col-md-6">
                @php
                    $mediciesDataValue = json_decode($seconivfHistoryData->description); 
                    $secondVisitValueMediciesData = [];
                    if(!empty($mediciesDataValue->medicinedata)){
                        foreach ($mediciesDataValue->medicinedata as $key => $value) {
                            $mData = $value->medicine;
                            if(!empty($value->injection_status) && $value->injection_status == 1){
                                $mData .= ' State';
                            }else{
                                switch($value->medicine_status){
                                    case '1':
                                        $mData .= ' જમ્યા પછી';
                                        break;
                                    case '2':
                                        $mData .= ' જમ્યા પહેલાં';
                                        break;
                                    case '3':
                                        $mData .= ' માસિકની જગ્યાએ મુકવી';
                                        break;
                                }
                                $timeData = [];
                                if(!empty($value->medicine_time)){
                                    foreach($value->medicine_time as $key => $timeValue) {
                                        switch($timeValue){
                                            case '1':
                                                $time = 'સવારે';
                                                break;
                                            case '2':
                                                $time = 'બપોરે';
                                                break;
                                            case '3':
                                                $time = 'સાંજે';
                                                break;
                                            case '4':
                                                $time = 'રાત્રે';
                                                break;
                                        }
                                        $timeData[] = $time;
                                    }
                                    $timeData = implode(',',$timeData);
                                    $mData .= $timeData;
                                }
                                $mData .= ' '.$value->quantity;
                                $mData .= ' ગોળી';
                                $mData .= ' '.$mediciesDataValue->follow_up;
                                $mData .= ' દિવસ સુધિ લેવી';
                            }
                            $secondVisitValueMediciesData[] = $mData;
                        }
                    }
                @endphp
                <span class="label-name">Medicines :</span>
                <br>
                <strong class="value">{!! implode('<br>',$secondVisitValueMediciesData) !!}</strong>
            </div>
        </div>
        <table class='table m-b-0 mt-4'>
            <thead>
                <tr>
                    <th>Day Of Mense</th>
                    <th>Day</th>
                    <th>Date</th>
                    <th>ET</th>
                    <th>Drugs</th>
                    <th>Date of pg start</th>
                    <th>Lx. S. Pg</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ivfHistoryData as $row)
                    @php
                        $data = json_decode($row->description);
                        $historyLmdDiff = null;
                        if(!empty($data->lmp->date)){
                            $historyLmddateDate = cdate($data->lmp->date);
                            $now = cdate($row->created_at);
                            $historyLmdDiff = $historyLmddateDate->diffInDays($now);
                            $historyLmdDiff = $historyLmdDiff + 1;
                        }
                        $medicinesData = [];
                        $progestoneStart = !empty($data->progesterone->type) ? 'Yes' : null;
                        if(!empty($data->medicinedata)){
                            foreach ($data->medicinedata as $key => $value) {
                                $mData = $value->medicine;
                                if(!empty($value->injection_status) && $value->injection_status == 1){
                                    $mData .= ' State';
                                }else{
                                    switch($value->medicine_status){
                                        case '1':
                                            $mData .= ' જમ્યા પછી';
                                            break;
                                        case '2':
                                            $mData .= ' જમ્યા પહેલાં';
                                            break;
                                        case '3':
                                            $mData .= ' માસિકની જગ્યાએ મુકવી';
                                            break;
                                    }
                                    $timeData = [];
                                    if(!empty($value->medicine_time)){
                                        foreach($value->medicine_time as $key => $timeValue) {
                                            switch($timeValue){
                                                case '1':
                                                    $time = 'સવારે';
                                                    break;
                                                case '2':
                                                    $time = 'બપોરે';
                                                    break;
                                                case '3':
                                                    $time = 'સાંજે';
                                                    break;
                                                case '4':
                                                    $time = 'રાત્રે';
                                                    break;
                                            }
                                            $timeData[] = $time;
                                        }
                                        $timeData = implode(',',$timeData);
                                        $mData .= $timeData;
                                    }
                                    $mData .= ' '.$value->quantity;
                                    $mData .= ' ગોળી';
                                    $mData .= ' '.$data->follow_up;
                                    $mData .= ' દિવસ સુધિ લેવી';
                                }
                                $medicinesData[] = $mData;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{$historyLmdDiff}}</td>
                        <td>{{cdate($row->created_at)->format('l')}}</td>
                        <td>{{cdate($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{!empty($data->oe->endometrial_cavity->size) ? $data->oe->endometrial_cavity->size : null}}</td>
                        <td>{!! implode('<br><br>',$medicinesData) !!}</td>
                        <td>{{$progestoneStart}}</td>
                        <td>{{!empty($data->progesterone->type) ? $data->progesterone->type : null}}</td>
                        <td>{{$data->remark}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>