@extends(isset($printPreview) && $printPreview == 1 ? 'layouts.printpreview' : 'layouts.printPreviewBlank')
@php
    $class = '';
    $hoNo = 2;
    $coNo = 3;
    $patientsHoNo = 6;
    $oeNo = 7;
    $investigationNo = 8;
    $injectionNo = 9;
    $usgNo = 10;
    $treatmentNo = 11;
    $weightClass = 'd-none';
    $gynecPlan = 9;
    $hoType = [];
    if($ancHistory){
        $weightClass = '';
        $class = 'd-none';
        $hoNo = 1;
        $coNo = 2;
        $patientsHoNo = 3;
        $oeNo = 4;
        $investigationNo = 5;
        $injectionNo = 6;
        $usgNo = 7;
        $treatmentNo = 8;
        $gynecPlan = 6;
    }
    $instra = "";
    $psize = "";
    $patientsInfo = !empty($ancData->patients_info) ? json_decode($ancData->patients_info) : null;
    $ho = !empty($ancData->h_o) ? json_decode($ancData->h_o) : null;
    $co = !empty($ancData->c_o) ? json_decode($ancData->c_o) : null;
    $patientsObstratics = !empty($ancData->patients_obstratics) ? json_decode($ancData->patients_obstratics) : null;
    $mh = !empty($ancData->m_h) ? json_decode($ancData->m_h) : null;
    $patientsDetails = !empty($ancData->patients_details_ho) ? json_decode($ancData->patients_details_ho) : null;
    $oe = !empty($ancData->o_e) ? json_decode($ancData->o_e) : null;
    $patientsInvestigation = !empty($ancData->investigation) ? json_decode($ancData->investigation) : null;
    $patientsInjection = !empty($ancData->injection) ? json_decode($ancData->injection) : null;
    $usg = !empty($ancData->usg) ? json_decode($ancData->usg) : null;
    $treatment = !empty($ancData->treatment) ? json_decode($ancData->treatment) : null;
    $remark=!empty($previousAnc->o_e) ? json_decode($previousAnc->o_e) : null;
    $blood=!empty($previousAnc->investigation) ? json_decode($previousAnc->investigation) : null;
    $ancCreatedDate = (!empty($ancData)) ? $ancData->created_at : null;
    $ancFirst_patientsObstratics = isset($ancFirstVisitData) ? json_decode($ancFirstVisitData->patients_obstratics) : null;

    $contraceptionData = ['barrier_method'=>'Barrier Method','cu_t'=>'Cu - T','tl_done'=>'TL Done ','occipill'=>'Occipill','other_contraception'=>'Other'];
    $utsizearray = ["Normal Size","Just Bulky","6 Weeks","6-8 Weeks","8 Weeks","8-10 Weeks","10-12 Weeks"];
    $utsizearray1 = ["12 Weeks","Uterus Just Palpable","14 Weeks","16 Weeks","18 Weeks","20 Weeks","22 Weeks","24 Weeks","26 Weeks","28 Weeks","30 Weeks","32 Weeks","34 Weeks","36 Weeks","Full Term"];
    $oe_number = ['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'];
    $childType = ['1'=>'Monochorionic Monochorionic Twin',
                    '2'=>'Dichorionic Diamniotic Twin',
                    '3'=>'Trichorionic Triamniotic Triplets',
                    '4'=>'DCDA Twin Spontaneously Reduced to Singleton',
                    '5'=>'Quadruchorionic Quadruamniotic Quadruplets',
                    '6'=>'Triplets with Fetus A and B monochorionic Pair',
                    '7'=>'Twins Reduced to Singleton',
                    '8'=>'Monochorionic Diamniotic'
                ];
    $medqty = ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    $terminationtype = ['Delivery'=>"Delivery",
                        'Obseravation'=>"Obseravation",
                        'Termination'=>"Termination",
                        'Operation'=>"Operation"];
@endphp
<style type="text/css">
    .module-report-table {
        text-align: left;
        width: 100%;
    }
    td, th{
        padding: 1px 0px 4px 0px;
    }
    .header-print-title{
        font-size: 18px;
        background-color: #e2e2e2;
        color: #212122;
        width: 100%;
        padding: 2px;
        border: 1px solid;
        text-indent: 5px;
        -webkit-print-color-adjust: exact;
        display: inherit;
    }
    .main-print-anc-div{
        margin: 0 auto;
        width: 100%;
    }
    .table th,.table td {
        border-top: none !important;
    }
    .text-danger{
        color:red;
    }
    .anc-label {
        font-weight: normal;
    }
    .w-300{
        width: 300px !important;
    }
    .lmd-lable{
        font-size: 17px;
    }
    .green-lable{
        color: #03c262 !important;
    }
    .medicine-table td{
        padding: 2px 5px !important;
        text-transform: capitalize;
    }
    .medicine-table tr{
        text-align:center !important;
    }
    .panel-primary
    {
        border:none !important;
    }
    span.admission-detail
    {
        display: block;
        width: 250px;
        word-break: break-word;
    }
    .font-bold
    {
        font-weight : 600 !important;
    }
    .text-center
    {
        text-align: center !important;
    }
    /* @page { margin-top :200px; margin-left : 100px;} */
    
</style>
@if(isset($printPreview) && $printPreview != 0)
    @section('content')
@endif  
<div class="main-print-anc-div mb-5">
    @if(isset($anc_print) && $anc_print== '3')
    <style>
        @page { margin-top : 10px; margin-bottom : 80px;}
    </style>
        <div class="ivf-print-data">
            <div class="row mb-2 patient-detail">
                <div class="col-md-12">
                    @if(@$usg->termination_type && !empty($usg->termination_type))
                        
                        <strong>{{isset($terminationtype[$usg->termination_type]) ? ucwords(strtolower('Admission for '.$terminationtype[$usg->termination_type])) : ''}}</strong>
                    @endif
                </div>
                <div class="col-md-12 patient-name">
                    <strong>{{ ucwords(strtolower($patients->name))}}</strong>
                </div>
                <div class="col-md-12">
                    <strong>{{ $patients->mobile_number}}</strong>
                </div>
            </div>
            <br>
            <div class="row mb-2">
                <div class="col-md-12">
                    <span>Admission Date : </span><span> {{ isset($oe->follow_up) && !empty($oe->follow_up) ? $oe->follow_up : '-' }}</span>
                </div>
                <div class="col-md-12">
                    @if(!empty($usg->termination_type_trem))
                        @php
                        $tremination_term = ($usg->termination_type_trem == 'full') ? 'Full Term' : 'Pre Term';
                            switch($usg->termination_type_trem){
                                case 'full':
                                $tremination_term = 'Full Term';
                                    break;
                                case 'pre':
                                $tremination_term = 'Pre Term';
                                    break;
                                case 'lscs':
                                $tremination_term = 'LSCS';
                                    break;
                            }
                        @endphp
                    @endif
                    @if(!empty($usg->termination_detail) || isset($tremination_term))
                        <span class="admission-detail"><strong>Admission Detail : </strong></span><span> {{$usg->termination_detail.(isset($tremination_term) ? ' - '.$tremination_term : '')}}</span>
                    @endif
                </div>
            </div>
        </div>
    @else
    <style>
        @page { margin-top : 200px; margin-left : 100px;}
    </style>
        <div class="{{'panel panel-primary print-panle-primary '.(isset($printPreview) && $printPreview == 1 ? 'watermark' : '')}}">
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                <tbody>
                    <tr>
                        <th style="padding-bottom: 30px;">Name: {{ucwords(strtolower($patients->name))}}
                            @if (!empty($oe->oe_no) && (!in_array($oe->oe_no, ['1'])) && (!empty($oe->oe_child_type) && array_key_exists($oe->oe_child_type, $childType)))
                                <br>
                                    {{ $childType[$oe->oe_child_type] }}
                            @endif
                            @php
                                $gender = ($patients->gender == 2) ? 'F' : 'M';
                            @endphp
                            <br>Age: {{$patients->age.' | '.$gender}}
                            <br>Seen By: {{isset($ancData->getSeenBy->name) ? $ancData->getSeenBy->name : ''}}
                        </th>
                        <th style="padding-bottom: 30px;text-align: justify;float: right;">Visit Date: {{cdate($ancData->created_at)->format('d-m-Y') }}
                            <br>Weight: {{ $weight." kg." }}</th>
                    </tr>
                </tbody>
            </table>
            @if($isGsac == true && $isFirstVisit == false)
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                    <tbody>
                    <tr>
                        <td colspan="8">
                            <div class="panel-title header-print-title">1. O/E
                                <span class="text-danger">{{!empty($oe->late_concept) && $oe->late_concept == 1 ? 'Late Conception' : null}}</span>
                                @if(!empty($lmdDate))
                                    &nbsp&nbsp L.M.D Date:  <span class="text-danger">{{cdate($lmdDate)->format('d-m-Y') }}</span>
                                @endif
                                @if(!empty($eddDate))
                                    &nbsp&nbsp EDD Date: <span class="text-danger">{{cdate($eddDate)->format('d-m-Y') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @if(!empty($oe->oe_type) || !empty($oe->oe_no))
                        <tr>
                            <th>
                                OE :
                            </th>
                            <td colspan="6">
                                {{ !empty($oe->oe_type) && $oe->oe_type == 'tvs' ? 'TVS' : 'PA'  }}
                                @if (!empty($oe->oe_no) && array_key_exists($oe->oe_no, $oe_number))
                                    {{ ' | ' . $oe_number[$oe->oe_no] }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    @foreach($oe->utdata as $key=>$value)
                        <tr>
                            @if(!empty($value->ut_type))
                                <th>
                                    {{strtoupper($value->ut_type)}}  {{$value->ut_type == 'ut' ? '(wks)' : '(mm)'}} :
                                </th>
                            @endif
                            <td>
                                {{!empty($value->oe_ut_sac) ? $value->oe_ut_sac : null}}
                                @if(!empty($value->oe_ut_sac_2))
                                    {{!empty($value->ut_type) && $value->ut_type == 'ut' ? '-' : '.'}}
                                    {{$value->oe_ut_sac_2}}
                                @endif
                            </td>
                        </tr>
                        @if(!empty($value->ut_type) && $value->ut_type == 'ut')
                            <tr>
                                <th class=" green-lable">
                                    FCA :
                                </th>
                                <td>
                                    {{  !empty($value->fcp) ? $value->fcp : '-'}}
                                </td>
                                @if (!empty($value->liquor_type))
                                    <th class=" green-lable">
                                        Liquor :
                                    </th>
                                    <td>
                                        @switch($value->liquor_type)
                                            @case('normal')
                                                Normal
                                                @break
                                            @case('oligo')
                                                Oligo
                                                @break
                                            @case('poly')
                                                Poly
                                                @break
                                            @case('none')
                                                None
                                                @break
                                        @endswitch
                                        @if(!empty($value->liquor_sub_type) && ($value->liquor_type == 'oligo' || $value->liquor_type == 'poly'))
                                            @switch($value->liquor_sub_type)
                                                @case('mild')
                                                    &nbsp;&nbsp; Mild
                                                    @break
                                                @case('moderate')
                                                    &nbsp;&nbsp; Moderate
                                                    @break
                                                @case('severe')
                                                    &nbsp;&nbsp; Severe
                                                    @break
                                            @endswitch
                                        @endif
                                    </td>
                                @endif
                                @if (!empty($value->position_type))
                                    <th class=" green-lable">
                                        Position :
                                    </th>
                                    <td>
                                        @switch($value->position_type)
                                            @case('vertex')
                                                Vertex
                                                @break
                                            @case('breech')
                                                Breech
                                                @break
                                            @case('transverse')
                                                Transverse
                                                @break
                                            @case('oblique')
                                                Oblique
                                                @break
                                            @case('none')
                                                None
                                                @break
                                        @endswitch
                                    </td>
                                @endif
                            </tr>
                            @if ($key == 1 && $value->ut_type == 'ut' && (($value->oe_ut_sac >= 11 || $value->oe_ut_sac_2 >= 11)) && isset($oe->fefal_reduction))
                                <tr>
                                    @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes')
                                        <th>
                                            Fefal Reduction :
                                            {{'No'}}
                                        </th>
                                    @endif
                                    @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes')
                                        <th>
                                            Date :
                                        </th>
                                        <td>
                                            @if (isset($oe->fefal_reduction->date))
                                                {{ !empty($oe->fefal_reduction->date) ? $oe->fefal_reduction->date : '-' }}
                                            @else
                                                {{'-'}}
                                            @endif
                                        </td>
                                    @endif
                                    @if (isset($oe->how_much))
                                        @if (isset($oe->how_much->type))
                                            <th>
                                                How Many :
                                            </th>
                                            <td>
                                                @if (!empty($oe->how_much->type))
                                                    {{ $oe->how_much->type == 'yes' ? 'Yes' : 'No' }}
                                                @else
                                                    {{'-'}}
                                                @endif
                                            </td>
                                        @endif
                                        @if (isset($oe->how_much->type) && $oe->how_much->type == 'yes')
                                            <th>
                                                How Many Value :
                                            </th>
                                            <td>
                                                {{ isset($oe->fefal_reduction->how_much_value) && !empty($oe->fefal_reduction->how_much_value) ? $oe->fefal_reduction->how_much_value : '-' }}
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endif
                        @else
                            <tr>
                                <th>
                                    Yolk Sac :
                                </th>
                                <td>
                                    {{  !empty($value->yalk_sac) ? $value->yalk_sac : null}}
                                </td>
                                <th>
                                    Fetal Pole :
                                </th>
                                <td>
                                    {{!empty($value->fefal_pole) ? $value->fefal_pole : null}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @php
                        $isMedical = 'd-none';
                        $isSurgical = '';
                        $ecTopic = (isset($oe->ec_topic)) && (($oe->ec_topic) == 'yes') ? '' : 'd-none';
                        $treact = '';
                        if ((isset($oe->treact->type))) {
                            $treact = ($oe->treact->type == 'medical') ? 'medical' : 'surgical';
                            $isMedical = ($treact == 'medical') ? '' : 'd-none';
                            $isSurgical = ($treact == 'surgical') ? '' : 'd-none';
                        }
                    @endphp
                        <tr>
                            <th>
                                EC Topic :
                            </th>
                            <td>
                                {{ isset($oe->ec_topic) && $oe->ec_topic == 'yes' ? 'Yes' : 'No'}}
                            </td>
                            <th>
                                Expert USG :
                            </th>
                            <td>
                                {{ (isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics)) ? 'Yes' : 'No' }}
                            </td>
                            @if (isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics))
                                <th>
                                    Expert USG Details :
                                </th>
                                <td>
                                    {{ (isset($oe->expert_usg) && !empty($oe->expert_usg)) ? $oe->expert_usg : '-' }}
                                </td>
                                <th>
                                    Date :
                                </th>
                                <td>
                                    {{ (isset($oe->expert_usg_date) && !empty($oe->expert_usg_date)) ? $oe->expert_usg_date : '-' }}
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <th>
                                Blood Report :
                            </th>
                            <td>
                                {{ (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics)) ? 'Yes' : 'No' }}
                            </td>
                            @if (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics))
                                <th>
                                    Blood Report Details :
                                </th>
                                <td>
                                    {{ (isset($oe->blood_report) && !empty($oe->blood_report)) ? $oe->blood_report : '-' }}
                                </td>
                                <th>
                                    Date :
                                </th>
                                <td>
                                    {{ (isset($oe->blood_report_date) && !empty($oe->blood_report_date)) ? $oe->blood_report_date : '-' }}
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <th>
                                Treact :
                            </th>
                            <td>
                                @if (isset($oe->treact->type))
                                    {{ ($oe->treact->type == 'medical') ? 'Medical' : 'Surgical' }}
                                @else
                                    -
                                @endif
                            </td>
                            @if (isset($oe->treact->type))
                                @if ($oe->treact->type == 'medical')
                                    <th>
                                        Medicine Details :
                                    </th>
                                    <td>
                                        @if (isset($oe->treact->medicine_details))
                                            {{ !empty($oe->treact->medicine_details) ? $oe->treact->medicine_details : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <th>
                                        Medicine Dose:
                                    </th>
                                    <td>
                                        @if (isset($oe->treact->medicine_dose))

                                            @switch($oe->treact->medicine_dose)
                                                @case('1')
                                                    OD
                                                    @break
                                                @case('2')
                                                    BD
                                                    @break
                                                @case('3')
                                                    TDS
                                                    @break
                                                @case('4')
                                                    ADS
                                                    @break
                                                @case('5')
                                                    Weekly / 1 time
                                                    @break
                                                @case('6')
                                                    Weekly / 2 time
                                                    @break
                                                @case('7')
                                                    Stat
                                                @case('8')
                                                    SOS
                                            @endswitch
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif

                                @if ($oe->treact->type == 'surgical')
                                    <th>
                                        Operation Details :
                                    </th>
                                    <td>
                                        @if (isset($oe->treact->surgical_details))
                                            {{ !empty($oe->treact->surgical_details) ? $oe->treact->surgical_details : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                            @endif
                        </tr>
                        <tr>
                            <th>
                                Ovary:
                            </th>
                            <td>
                                @if (isset($oe->ovary->ovary_type))
                                    @foreach($oe->ovary->ovary_type as $key => $value)
                                        @if ($value == 'left')
                                            <td>
                                                Left
                                            </td>
                                            <td>
                                                @if (!empty($oe->ovary->left->type))
                                                    {{ ($oe->ovary->left->type == '1') ? 'Normal' : 'Abnormal' }}
                                                @endif
                                            </td>
                                            @if ($oe->ovary->left->type == '2')
                                                <td colspan="6">
                                                    @if (!empty($oe->ovary->left->updated_details))
                                                        @foreach ($oe->ovary->left->updated_details as $key => $value)
                                                            @php
                                                                echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                </td>
                                            @endif
                                        @endif

                                        @if ($value == 'right')
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                    Right
                                                </td>
                                                <td>
                                                    @if (!empty($oe->ovary->right->type))
                                                        {{ ($oe->ovary->right->type == '1') ? 'Normal' : 'Abnormal' }}
                                                    @endif
                                                </td>

                                                @if ($oe->ovary->right->type == '2')
                                                    <td colspan="6">
                                                        @if (!empty($oe->ovary->right->updated_details))
                                                            @foreach ($oe->ovary->right->updated_details as $key => $value)
                                                                @php
                                                                    echo !empty($value) ? $value .  '<br />' : '-' . '<br />';
                                                                @endphp
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Remark</th>
                            <td colspan="8">
                                @if (isset($patientsObstratics->remark))
                                    {{ !empty($patientsObstratics->remark) ? $patientsObstratics->remark : '-' }}
                                @else
                                    -
                                @endif

                            </td>
                        </tr>
                    </tbody>
                </table>
                @if(!empty($treatment))
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">2. Treatment </div>
                                </td>
                            </tr>
                            <?php
                                unset($treatment->medicinedata);
                            ?>
                            @if(!empty($treatment))
                                <table class="medicine-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Dose</th>
                                            <th>Timing</th>
                                            <th>Freq.</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($treatment as $key=>$row)
                                        <tr>
                                            <?php
                                                $medicine_status = '';
                                                $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                $firstCharacter = strtoupper(substr($mId, 0, 3));
                                                if($firstCharacter == "INJ"){
                                                    if(!empty($row->medicine_time))
                                                    {
                                                        switch($row->medicine_time){
                                                            case '1':
                                                                $medicine_status = 'IV';
                                                                break;
                                                            case '2':
                                                                $medicine_status = 'IM';
                                                                break;
                                                            case '3':
                                                                $medicine_status = 'SC';
                                                                break;
                                                            case '4':
                                                                $medicine_status = 'Oral';
                                                                break;
                                                            case '5':
                                                                $medicine_status = 'P/V';
                                                                break;
                                                            case '6':
                                                                $medicine_status = 'P/A';
                                                                break;
                                                        }
                                                    }
                                                    $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                                    if($mData==$medicine_status) {
                                                        $medicine_status = "-";
                                                    }
                                                }else{
                                                    $mData = [0,0,0,0];

                                                    if(@$row->quantity>0) {
                                                        $mData[0] = $row->quantity;
                                                    }
                                                    if(@$row->quantity_2>0) {
                                                        $mData[1] = $row->quantity_2;
                                                    }
                                                    if(@$row->quantity_3>0) {
                                                        $mData[2] = $row->quantity_3;
                                                    }
                                                    if(@$row->quantity_4>0) {
                                                        $mData[3] = $row->quantity_4;
                                                    }
                                                    $mData = implode('-',$mData);
                                                    switch($row->medicine_status){
                                                        case '1':
                                                            $medicine_status = 'જમ્યા પછી';
                                                            break;
                                                        case '2':
                                                            $medicine_status = 'જમ્યા પહેલાં';
                                                            break;
                                                        case '3':
                                                            $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                                            break;
                                                    }
                                                }
                                            ?>
                                            <td>{{$row->medicine}}</td>
                                            <td>{{$mData}}</td>
                                            <td>{{$medicine_status}}</td>
                                            <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                                            <td>{{(!empty($row->no)) ? $row->no.' days' : ''}}</td>
                                            <td>{{isset($row->note) && !empty($row->note) ? $row->note : '-'}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </tbody>
                    </table>
                @endif
            @else
                <!-- H/O -->
                @if($ho)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            @if(isset($oe->pt_remark) && !empty($oe->pt_remark) && isset($patient_preview) && $patient_preview == 1)
                                <tr>
                                        <th class="text-danger">
                                            <span class="anc-label ">Remark :</span>
                                            {{$oe->pt_remark}}
                                        </th>
                                </tr>
                            @endif
                            @if(!isset($patient_preview) || $patient_preview != 1)
                            <tr>
                                @if(!empty($oe->remark))
                                    <th class="text-danger">
                                        <span class="anc-label ">O/E Remark :</span>
                                        {{$oe->remark}}
                                    </th>
                                @endif
                            </tr>
                            <tr>
                                <th class="text-danger">
                                @if (isset($ancFirst_patientsObstratics->gpal_status) && !empty($ancFirst_patientsObstratics->gpal_status))
                                <span class="anc-label ">*GPAL Status:</span>
                                                {{$ancFirst_patientsObstratics->gpal_status}}
                                @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['blood_group']) &&  (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['blood_group_date'])))
                                        <span class="anc-label ">*Blood Group:</span>
                                            {{$ancAutoRemark['blood_group']}}
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['hbsag']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hbsag_date'])))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*HBSAG:</span>
                                            {{$ancAutoRemark['hbsag']}}
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['hiv']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hiv_date'])))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*HIV:</span>
                                            {{$ancAutoRemark['hiv']}}
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['vdrl']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['vdrl_date'])))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*VDRL:</span>
                                            {{$ancAutoRemark['vdrl']}}
                                    @endif
                                    
                                    @if($ancAutoRemark && !empty($ancAutoRemark['late_concept']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['late_concept_date'])))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Late Conception:</span>
                                        Yes
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['cesarean']))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Previous:</span>
                                            {{$ancAutoRemark['cesarean']. ' - LSCS'}}
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['position']) && ($ancAutoRemark['position'] == 'breech' || $ancAutoRemark['position'] == 'transverse' || $ancAutoRemark['position'] == 'oblique'))
                                        @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['position_date']))    
                                            <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Position:</span>
                                            {{$ancAutoRemark['position']}}
                                        @endif
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['liquor']) && ($ancAutoRemark['liquor'] == 'oligo' || $ancAutoRemark['liquor'] == 'poly') && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['liquor_date'])))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Liquor:</span>
                                            {{$ancAutoRemark['liquor']}}
                                    @endif
                                    @if($ancAutoRemark && !empty($ancAutoRemark['placenta']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['placenta_date'])))
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Placenta:</span>
                                            {{$ancAutoRemark['placenta']}}
                                    @endif
                                    
                                    </th>
                            </tr>
                            @endif

                            <tr>
                                <th>
                                    <span class="anc-label">H/O: </span>
                                    {{!empty($ho->ho_details) ? $ho->ho_details.' Amenorrhoea' : '-' }}
                                    @if (!empty($ho->ho_type))
                                            {{-- <span class="anc-label "></span> --}}
                                            @if ($ho->ho_type == '1')
                                                {{ 'Conceived Naturally' }}
                                            @elseif ($ho->ho_type == '2')
                                                {{ 'Conceived with Medicine' }}
                                            @elseif ($ho->ho_type == '3')
                                                {{ 'Conceived With IUI' }}
                                            @elseif ($ho->ho_type == '4')
                                                {{ 'Conceived With IVF' }}
                                            @endif
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                @if(!empty($patientsObstratics->upt_type))
                                    <th>
                                        <span class="anc-label ">UTP :</span>
                                        {{$patientsObstratics->upt_type  == 'positive' ? 'Positive' : 'Negative'}}
                                        {{'done on '.$patientsObstratics->upt_details}}
                                    </th>
                                @endif
                                @if (!empty($ho->ho_type) && $ho->ho_type != 1 && !empty($ho->when_where))
                                    <th>
                                        <span class="anc-label ">At :</span>
                                        {{ !empty($ho->when_where) ? $ho->when_where : '-'}}
                                    </th>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                @endif
                    
                <!-- C/O -->
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                    <tbody>
                        <tr>
                            <th>
                                <span class="anc-label">C/O :</span>
                                @if(!empty($co->co_type))
                                    {{ (isset($co->co_type) && is_array($co->co_type)) ? implode(', ', $co->co_type) : '-' }}
                                    @if(!empty($co->since))
                                        <span class="anc-label">Since </span>
                                        {{ !empty($co->since) ? $co->since : '-' }}
                                    @endif
                                @else
                                    None
                                @endif
                            </th>
                        </tr>
                    </tbody>
                </table>
                <!-- obstratics history -->
                @php
                    $noValueData = [];
                    $secondNoValueData = [];
                    $ancFirst_mh_date = isset($ancFirstVisitData) ? json_decode($ancFirstVisitData->m_h) : null;
                    $ancFirstlmdDate = !empty($ancFirst_mh_date->last_menstrual_date) ? cdate($ancFirst_mh_date->last_menstrual_date)->format('d/m/Y'): null;
                    $ancFirsteddDate = !empty($ancFirst_mh_date->edd) ? cdate($ancFirst_mh_date->edd)->format('D d M Y') : null;
                    $ancFirstusgEddDate = !empty($ancFirst_mh_date->usg_edd) ? cdate($ancFirst_mh_date->usg_edd)->format('D d M Y'): null;
                @endphp
                @if($patientsObstratics)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table '.$class}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">Obstetric History</div>
                                </td>
                            </tr>
                            @if(!empty($patientsObstratics->first_marriage_life))
                                <tr>
                                    @if(!empty($patientsObstratics->first_marriage_life))
                                        <th>
                                            <span class="anc-label ">Marriage Life :</span>
                                            {{$patientsObstratics->first_marriage_life}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if(!empty($patientsObstratics->child_no) && $patientsObstratics->child_no != 0)
                                @foreach($patientsObstratics->child->child_data as $key=>$row)
                                    <tr>
                                        <th>
                                            <span class="anc-label ">H/O : {{addOrdinalNumberSuffix($key)}}</span>
                                            @php
                                                $hoValue = null;
                                                $ho_term_details = '';
                                                if(!empty($row->ho_term)){
                                                    $hoValue.= $row->ho_term  == 'full' ? 'FT' : 'Pre Term';
                                                }
                                                if(!empty($row->ho_type_value)){
                                                    if($row->ho_type_value == 'normal'){
                                                        $hoValue.= ' ND';
                                                    }elseif($row->ho_type_value == 'cesarean'){
                                                        $hoValue.= ' LSCS';
                                                    }elseif ($row->ho_type_value == 'instrumental'){
                                                        $hoValue.= ' Instrumental Delivery';
                                                    }
                                                }
                                                if(!empty($row->ho_gender)){
                                                    if(is_array($row->ho_gender))
                                                    {
                                                        $hoValue.= ' '.implode(',',array_filter(array_map("ucfirst", $row->ho_gender)));
                                                    }
                                                    else
                                                    {
                                                        $hoValue.= $row->ho_gender == 'female' ? ' Female' : ' Male';
                                                    }
                                                }
                                                if(isset($row->child_type) && !empty($row->child_type))
                                                {
                                                    $hoValue.= '('.$row->child_type.') ';
                                                }
                                                if(!empty($row->ho_birth_type)){
                                                    if($row->ho_birth_type == 'live_health'){
                                                        $hoValue.= '/Live';
                                                    }
                                                    if($row->ho_birth_type == 'stil_birth'){
                                                        $hoValue.= '/Stil Birth';
                                                    }
                                                    if($row->ho_birth_type == 'expired'){
                                                        $hoValue.= '/Expired';
                                                        if($row->expired_reason){
                                                            $hoValue.= '('.$row->expired_reason.')';
                                                        }
                                                    }
                                                }
                                                if(!empty($row->live_health_year)){
                                                    $hoValue.= '-'.$row->live_health_year.' year';
                                                }
                                                if(!empty($row->expired_year)){
                                                    $hoValue.= '-'.$row->expired_year.' year';
                                                }
                                                if(!empty($row->ho_type) && $patientsObstratics->child_no != 0 ){
                                                    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                    if(array_key_exists($row->ho_type, $ho_type_array)){
                                                        $hoValue.= ' ('.$ho_type_array[$row->ho_type].')';
                                                        $hoType = [2,3,4];
                                                        $dNone = '';
                                                        if (!in_array($row->ho_type,$hoType)) {
                                                            $dNone = 'd-none';
                                                        }
                                                        if($patientsObstratics->child_no != null && $patientsObstratics->child_no != 0 && $dNone == '' && !empty($row->when_where)){
                                                            $hoValue.= ' '.$row->when_where;
                                                        }
                                                    }
                                                }
                                                $ho_term_details = isset($row->ho_term_details) && !empty($row->ho_term_details) ? ' - '.$row->ho_term_details : '';
                                            @endphp
                                            {{$hoValue.$ho_term_details}}
                                        </th>
                                    </tr>
                                @endforeach
                            @endif
                            @if(!empty($patientsObstratics->mtp_no) && $patientsObstratics->mtp_no != 0 )
                                @foreach($patientsObstratics->mtp->mtp_data as $key=>$row)
                                {{-- @if($row->mtp_status == 'yes')
                                    <tr>
                                        <th>
                                            <span class="anc-label ">H/O MTP Done</span>
                                            @php
                                                $mtpStatus = 'd-none';
                                                if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                                    $mtpStatus = '';
                                                }
                                            @endphp
                                            @if (!empty($row->mtp_status) && $row->mtp_status == 'yes' && !empty($row->mtp_type))
                                                @php
                                                    if(!empty($row->mtp_type)) {
                                                        echo ($row->mtp_type == 'medically') ? 'Medically' : 'Surgically';
                                                    }
                                                @endphp
                                                @if(!empty($row->mtp_month_of_pregancy))
                                                    at
                                                    {{$row->mtp_month_of_pregancy}}
                                                @endif
                                            @endif
                                            @php
                                                $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if (!in_array($row->ho_type,$hoTypeValue)) {
                                                    $dNone = 'd-none';
                                                }
                                            @endphp
                                            @if(!empty($patientsObstratics->mtp_no) && $patientsObstratics->mtp_no != 0 )
                                                    @if (array_key_exists($row->ho_type, $ho_type_array))
                                                        {{ $ho_type_array[$row->ho_type] }}
                                                    @endif
                                            @endif
                                            @if ($dNone == '' && !empty($row->when_where))
                                                at
                                                {{!empty($row->when_where) ? $row->when_where : ''}}
                                            @endif
                                        </th>
                                    </tr>
                                @endif --}}
                                    @php
                                        $numberKey = addOrdinalNumberSuffix($key);
                                        $firstAbortionData = 'H/O '.$numberKey;
                                    @endphp
                                    <tr>
                                        @php
                                            $isBracket = 0;
                                            if(!empty($row->mtp_status) && $row->mtp_status == 'yes') {
                                                $firstAbortionData .= ' MTP';
                                            }
                                            if(!empty($row->spontancous_abortion_month_of_pregancy)){
                                                $firstAbortionData .= ' at ' . $row->spontancous_abortion_month_of_pregancy.' MOA';
                                            }
                                            if(!empty($row->spontancous_abortion_before)){
                                                $firstAbortionData .= ' before ' . $row->spontancous_abortion_before;
                                            }
                                            if(!empty($row->mtp_type)){
                                                $firstAbortionData .= ' ('. $row->mtp_type;
                                                $isBracket = 1;
                                            }
                                            $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                            if(!empty($row->ho_type) && !empty($patientsObstratics->abortion_no) && $patientsObstratics->abortion_no != 0 ){
                                                if (array_key_exists($row->ho_type, $abortion_type_array)){
                                                    $firstAbortionData .= ' AND '.$abortion_type_array[$row->ho_type];
                                                }
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if(!empty($row->ho_type) && !in_array($row->ho_type,$hoTypeValue)){
                                                    $dNone = 'd-none';
                                                }
                                                if($patientsObstratics->abortion_no != null && $patientsObstratics->abortion_no != 0 && $dNone == '' && !empty($row->when_where)){
                                                    $firstAbortionData .= ' - '.$row->when_where;
                                                }
                                            }
                                            if($isBracket == 1){
                                                $firstAbortionData .= ')';
                                            }
                                        @endphp
                                        <td>
                                            {{$firstAbortionData}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $noValueData[] = ' MTP';
                                @endphp
                            @endif
                            @if(empty($patientsObstratics->abortion_no))
                                @php
                                    $noValueData[] = ' Abortion';
                                @endphp
                            @endif
                            @if(!empty($patientsObstratics->abortion_no) && $patientsObstratics->abortion_no != 0 )
                                @foreach($patientsObstratics->abortion->abortion_data as $key=>$value)
                                    @php
                                        $numberKey = addOrdinalNumberSuffix($key);
                                        $firstAbortionData = 'H/O '.$numberKey;
                                    @endphp
                                    <tr>
                                        @php
                                            $isBracket = 0;
                                            if(!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes') {
                                                $firstAbortionData .= ' Spontaneous Abortion';
                                            }
                                            if(!empty($value->spontancous_abortion_month_of_pregancy)){
                                                $firstAbortionData .= ' at ' . $value->spontancous_abortion_month_of_pregancy.' MOA';
                                            }
                                            if(!empty($value->spontancous_abortion_before)){
                                                $firstAbortionData .= ' before ' . $value->spontancous_abortion_before;
                                            }
                                            if(!empty($value->spontancous_abortion_type)){
                                                $firstAbortionData .= ' ('. $value->spontancous_abortion_type;
                                                $isBracket = 1;
                                            }
                                            $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                            if(!empty($value->ho_type) && !empty($patientsObstratics->abortion_no) && $patientsObstratics->abortion_no != 0 ){
                                                if (array_key_exists($value->ho_type, $abortion_type_array)){
                                                    $firstAbortionData .= ' AND '.$abortion_type_array[$value->ho_type];
                                                }
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if(!empty($value->ho_type) && !in_array($value->ho_type,$hoTypeValue)){
                                                    $dNone = 'd-none';
                                                }
                                                if($patientsObstratics->abortion_no != null && $patientsObstratics->abortion_no != 0 && $dNone == '' && !empty($value->when_where)){
                                                    $firstAbortionData .= ' - '.$value->when_where;
                                                }
                                            }
                                            if($isBracket == 1){
                                                $firstAbortionData .= ')';
                                            }
                                        @endphp
                                        <td>
                                            {{$firstAbortionData}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(!isset($patientsObstratics->ectopic_no) && empty($patientsObstratics->ectopic_no))
                                @php
                                    $noValueData[] = ' Ectopic';
                                @endphp
                            @endif
                            @if(!empty($patientsObstratics) && isset($patientsObstratics->ectopic_no) && ($patientsObstratics->ectopic_no != null && $patientsObstratics->ectopic_no != 0 ))
                                @foreach($patientsObstratics->ectopic->ectopic_data as $key=>$value)
                                    @php
                                        $numberKey = addOrdinalNumberSuffix($key);
                                        $firstEctopicData = 'H/O '.$numberKey;
                                    @endphp
                                    <tr>
                                        @php
                                            $isBracket = 0;
                                            $firstEctopicData .= ' Ectopic';
                                            if(isset($value->tube) && !empty($value->tube))
                                            {
                                                
                                                if(in_array('right',$value->tube) && in_array('left',$value->tube))
                                                {
                                                    $firstEctopicData .= ' Both';
                                                }
                                                else
                                                {
                                                    $firstEctopicData .= in_array('right',$value->tube) ? ' Right' : ' Left';
                                                }
                                                $firstEctopicData .= ' Tube';
                                            }
                                            if(!empty($value->spontancous_ectopic_before)){
                                                $firstEctopicData .= ' before ' . $value->spontancous_ectopic_before;
                                            }
                                            if(!empty($value->spontancous_ectopic_type)){
                                                $firstEctopicData .= ' ('. $value->spontancous_ectopic_type.' Management';
                                                $isBracket = 1;
                                            }
                                            $ectopic_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                            if(!empty($value->ho_type) && !empty($patientsObstratics->ectopic_no) && $patientsObstratics->ectopic_no != 0 ){
                                                if (array_key_exists($value->ho_type, $ectopic_type_array)){
                                                    $firstEctopicData .= ' AND '.$ectopic_type_array[$value->ho_type];
                                                }
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if(!empty($value->ho_type) && !in_array($value->ho_type,$hoTypeValue)){
                                                    $dNone = 'd-none';
                                                }
                                                if($patientsObstratics->ectopic_no != null && $patientsObstratics->ectopic_no != 0 && $dNone == '' && !empty($value->when_where)){
                                                    $firstEctopicData .= ' - '.$value->when_where;
                                                }
                                            }
                                            if($isBracket == 1){
                                                $firstEctopicData .= ')';
                                            }
                                        @endphp
                                        <td>
                                            {{$firstEctopicData}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(isset($patientsObstratics) && !empty($patientsObstratics->contraception) && !empty($patientsObstratics->contraception->contraception_status) && $patientsObstratics->contraception->contraception_status == 'yes' &&  !empty($patientsObstratics->contraception->contraception_data))
                                <tr>
                                    <th>
                                        <span class="anc-label">Contraception Method :</span> {{$contraceptionData[$patientsObstratics->contraception->contraception_data]}}
                                    </th>
                                </tr>
                            @else
                                @php
                                    $noValueData[] = '';
                                @endphp
                            @endif
                            @if(!empty($noValueData))
                                <tr>
                                    <th>
                                        {{'No H/O '.implode(',',$noValueData)}}
                                    </th>
                                </tr>
                            @endif
                            @if (isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes')
                                <tr>
                                    <th class=" w-300">
                                        <span class="anc-label">Second merriage since {{!empty($patientsObstratics->second_marriage_life)?$patientsObstratics->second_marriage_details:''}}</span>
                                    </th>
                                </tr>
                            @endif
                            @if (isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics->second_marriage->child_no))
                                <tr>
                                    @if(!empty($patientsObstratics->second_marriage->child_no) )
                                        <th>
                                            <span class="anc-label ">Child No : </span>
                                            {{$patientsObstratics->second_marriage->child_no}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if(isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics->second_marriage->child_no) && $patientsObstratics->second_marriage->child_no != 0)
                                @foreach($patientsObstratics->second_marriage->child->child_data as $key=>$row)
                                    <tr>
                                        <th>
                                            <span class="anc-label ">H/O: </span>
                                            @php
                                                $secondHoValue = addOrdinalNumberSuffix($key);
                                                $second_ho_term_details = '';
                                                if(!empty($row->ho_term)){
                                                    $secondHoValue.= $row->ho_term  == 'full' ? 'FT' : 'Pre Term';
                                                }
                                                if(!empty($row->ho_type_value)){
                                                    if($row->ho_type_value == 'normal'){
                                                        $secondHoValue.= ' ND';
                                                    }elseif($row->ho_type_value == 'cesarean'){
                                                        $secondHoValue.= ' LSCS';
                                                    }elseif ($row->ho_type_value == 'instrumental'){
                                                        $secondHoValue.= ' Instrumental Delivery';
                                                    }
                                                }
                                                if(!empty($row->ho_gender)){
                                                    if(is_array($row->ho_gender))
                                                    {
                                                        $secondHoValue.= ' '.implode(',',array_filter(array_map("ucfirst", $row->ho_gender)));
                                                    }
                                                    else
                                                    {
                                                        $secondHoValue.= $row->ho_gender == 'female' ? ' Female' : ' Male';
                                                    }
                                                }
                                                if(isset($row->child_type) && !empty($row->child_type))
                                                {
                                                    $secondHoValue.= '('.$row->child_type.') ';
                                                }
                                                if(!empty($row->ho_birth_type)){
                                                    if($row->ho_birth_type == 'live_health'){
                                                        $secondHoValue.= '/Live';
                                                    }
                                                    if($row->ho_birth_type == 'stil_birth'){
                                                        $secondHoValue.= '/Stil Birth';
                                                    }
                                                    if($row->ho_birth_type == 'expired'){
                                                        $secondHoValue.= '/Expired';
                                                        if($row->expired_reason){
                                                            $secondHoValue.= '('.$row->expired_reason.')';
                                                        }
                                                    }
                                                }
                                                if(!empty($row->live_health_year)){
                                                    $secondHoValue.= '-'.$row->live_health_year;
                                                }
                                                if(!empty($row->expired_year)){
                                                    $secondHoValue.= '-'.$row->expired_year;
                                                }
                                                if(!empty($row->ho_type) && $patientsObstratics->second_marriage->child_no != 0 ){
                                                    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                    if(array_key_exists($row->ho_type, $ho_type_array)){
                                                        $secondHoValue.= ' ('.$ho_type_array[$row->ho_type].')';
                                                        $hoType = [2,3,4];
                                                        $dNone = '';
                                                        if (!in_array($row->ho_type,$hoType)) {
                                                            $dNone = 'd-none';
                                                        }
                                                        if($patientsObstratics->second_marriage->child_no != null && $patientsObstratics->second_marriage->child_no != 0 && $dNone == '' && !empty($row->when_where)){
                                                            $secondHoValue.= ' '.$row->when_where;
                                                        }
                                                    }
                                                    $second_ho_term_details = isset($row->ho_term_details) && !empty($row->ho_term_details) ? ' - '.$row->ho_term_details : '';
                                                    
                                                }
                                            @endphp
                                            {{$secondHoValue.$second_ho_term_details}}
                                        </th>
                                    </tr>
                                @endforeach
                            @endif
                            @if(isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics->second_marriage->mtp_no) && $patientsObstratics->second_marriage->mtp_no != 0)
                                @foreach($patientsObstratics->second_marriage->mtp->mtp_data as $key=>$row)
                                    @if($row->mtp_status == 'yes')
                                        <tr>
                                            <th>
                                                <span class="anc-label ">H/O MTP Done</span>
                                                @php
                                                    $mtpStatus = 'd-none';
                                                    if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                                        $mtpStatus = '';
                                                    }
                                                @endphp
                                                @if (!empty($row->mtp_status) && $row->mtp_status == 'yes' && !empty($row->mtp_type))
                                                    @php
                                                        if(!empty($row->mtp_type)) {
                                                            echo ($row->mtp_type == 'medically') ? 'Medically' : 'Surgically';
                                                        }
                                                    @endphp
                                                    @if(!empty($row->mtp_month_of_pregancy))
                                                        {{'at '.$row->mtp_month_of_pregancy}}
                                                    @endif
                                                @endif
                                                @php
                                                    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                @endphp
                                                @if(!empty($patientsObstratics->second_marriage->mtp_no) && $patientsObstratics->second_marriage->mtp_no != 0 )
                                                    @if (array_key_exists($row->ho_type, $ho_type_array))
                                                        {{ $ho_type_array[$row->ho_type] }}
                                                    @endif
                                                @endif
                                                @php
                                                    $hoTypeValue = [2,3,4];
                                                    $dNone = '';
                                                    if (!in_array($row->ho_type,$hoTypeValue)) {
                                                        $dNone = 'd-none';
                                                    }
                                                @endphp
                                                @if ($dNone == '' && !empty($row->when_where))
                                                    {{!empty($row->when_where) ? 'at '.$row->when_where : ''}}
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @php
                                    $secondNoValueData[] = ' MTP';
                                @endphp
                            @endif
                            @if(isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics) && !empty($patientsObstratics->second_marriage->abortion_no))
                                <tr>
                                    <th>
                                        <span class="anc-label ">Abortion :</span>
                                    </th>
                                    @php
                                        $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                    @endphp
                                </tr>
                            @else
                                @php
                                    $secondNoValueData[] = ' Abortion';
                                @endphp
                            @endif
                            @if(isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics->second_marriage->abortion_no) && $patientsObstratics->second_marriage->abortion_no != 0 )
                                @foreach($patientsObstratics->second_marriage->abortion->abortion_data as $key=>$value)
                                    @if($value->spontancous_abortion_status == 'yes')
                                        <tr>
                                            @php
                                                $numberKey = addOrdinalNumberSuffix($key);
                                                $secondAbortionData = $numberKey;

                                                $isBracket = 0;
                                                if(!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes') {
                                                    $secondAbortionData .= ' H/O Spontaneous Abortion';
                                                }
                                                if(!empty($value->spontancous_abortion_month_of_pregancy)){
                                                    $secondAbortionData .= ' at ' . $value->spontancous_abortion_month_of_pregancy.' MOA';
                                                }
                                                if(!empty($value->spontancous_abortion_before)){
                                                    $secondAbortionData .= ' before ' . $value->spontancous_abortion_before;
                                                }
                                                if(!empty($value->spontancous_abortion_type)){
                                                    $secondAbortionData .= ' ('. $value->spontancous_abortion_type;
                                                    $isBracket = 1;
                                                }
                                                $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                if(!empty($value->ho_type) && !empty($patientsObstratics->second_marriage->abortion_no) && $patientsObstratics->second_marriage->abortion_no != 0 ){
                                                    if (array_key_exists($value->ho_type, $abortion_type_array)){
                                                        $secondAbortionData .= ' AND '.$abortion_type_array[$value->ho_type];
                                                    }
                                                    $hoTypeValue = [2,3,4];
                                                    $dNone = '';
                                                    if (!in_array($value->ho_type,$hoTypeValue)) {
                                                        $dNone = 'd-none';
                                                    }
                                                    if($patientsObstratics->second_marriage->abortion_no != null && $patientsObstratics->second_marriage->abortion_no != 0 && $dNone == '' && !empty($value->when_where)){
                                                        $secondAbortionData .= ' '.$value->when_where;
                                                    }
                                                }
                                                if($isBracket == 1){
                                                    $secondAbortionData .= ')';
                                                }
                                            @endphp
                                            <td>
                                                {{$secondAbortionData}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            @if(isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics) && isset($patientsObstratics->second_marriage->ectopic_no) && !empty($patientsObstratics->second_marriage->ectopic_no))
                                <tr>
                                    <th>
                                        <span class="anc-label ">Ectopic :</span>
                                    </th>
                                    @php
                                        $ectopic_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                    @endphp
                                </tr>
                            @else
                                @php
                                    $secondNoValueData[] = ' Ectopic';
                                @endphp
                            @endif
                            @if(isset($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && isset($patientsObstratics->second_marriage->ectopic_no) && ($patientsObstratics->second_marriage->ectopic_no != null && $patientsObstratics->second_marriage->ectopic_no != 0 ))
                                @foreach($patientsObstratics->second_marriage->ectopic->ectopic_data as $key=>$value)
                                    @php
                                        $numberKey = addOrdinalNumberSuffix($key);
                                        $secondEctopicData = 'H/O '.$numberKey;
                                    @endphp
                                    <tr>
                                        @php
                                            $isBracket = 0;
                                            $secondEctopicData .= ' Ectopic';
                                            if(isset($value->tube) && !empty($value->tube))
                                            {
                                                
                                                if(in_array('right',$value->tube) && in_array('left',$value->tube))
                                                {
                                                    $secondEctopicData .= ' Both';
                                                }
                                                else
                                                {
                                                    $secondEctopicData .= in_array('right',$value->tube) ? ' Right' : ' Left';
                                                }
                                                $secondEctopicData .= ' Tube';
                                            }
                                            if(!empty($value->spontancous_ectopic_before)){
                                                $secondEctopicData .= ' before ' . $value->spontancous_ectopic_before;
                                            }
                                            if(!empty($value->spontancous_ectopic_type)){
                                                $secondEctopicData .= ' ('. $value->spontancous_ectopic_type.' Management';
                                                $isBracket = 1;
                                            }
                                            $ectopic_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                            if(!empty($value->ho_type) && !empty($patientsObstratics->second_marriage->ectopic_no) && $patientsObstratics->second_marriage->ectopic_no != 0 ){
                                                if (array_key_exists($value->ho_type, $ectopic_type_array)){
                                                    $secondEctopicData .= ' AND '.$ectopic_type_array[$value->ho_type];
                                                }
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if(!empty($value->ho_type) && !in_array($value->ho_type,$hoTypeValue)){
                                                    $dNone = 'd-none';
                                                }
                                                if($patientsObstratics->second_marriage->ectopic_no != null && $patientsObstratics->second_marriage->ectopic_no != 0 && $dNone == '' && !empty($value->when_where)){
                                                    $secondEctopicData .= ' - '.$value->when_where;
                                                }
                                            }
                                            if($isBracket == 1){
                                                $secondEctopicData .= ')';
                                            }
                                        @endphp
                                        <td>
                                            {{$secondEctopicData}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            @if(isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics) && !empty($patientsObstratics->second_marriage->contraception) && !empty($patientsObstratics->second_marriage->contraception->contraception_status) && $patientsObstratics->second_marriage->contraception->contraception_status == 'yes' && !empty($patientsObstratics->second_marriage->contraception->contraception_data))
                                <tr>
                                    <th>
                                        <span class="anc-label">Contraception Method : </span> {{$contraceptionData[$patientsObstratics->second_marriage->contraception->contraception_data]}}
                                    </th>
                                </tr>
                            @else
                                @php
                                    $secondNoValueData[] = '';
                                @endphp
                            @endif
                            <br>
                            @if(!empty($secondNoValueData) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && ('No '.implode(',',$noValueData)!='No H/O '.implode(',',$secondNoValueData)))
                                <tr>
                                    <th>
                                        {{'No H/O '.implode(',',$secondNoValueData)}}
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($patientsObstratics->remark))
                                <tr>
                                    <th>
                                        <span class="anc-label">Remark :</span>
                                        {{$patientsObstratics->remark}}
                                    </th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif

                @if($mh)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table '.$class}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">Menstrual History</div>
                                </td>
                            </tr>
                            @if(!empty($mh->age_of_menarchy) || !empty($mh->since_year))
                                <tr>
                                    @if (!empty($mh->age_of_menarchy))
                                        <th>
                                            <span class="anc-label ">Age Of Menarchy : </span>
                                            {{ $mh->age_of_menarchy }}
                                        </th>
                                    @endif

                                    @if (!empty($mh->since_year))
                                        <th>
                                            <span class="anc-label ">Since Year :</span>
                                            {{ $mh->since_year }}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if (!empty($mh->age_of_manopause) || !empty($mh->manopause_since_year))
                                <tr>
                                    @if (!empty($mh->age_of_manopause))
                                        <th>
                                            <span class="ivf-label">Age Of Manopause : </span>
                                            {{ $mh->age_of_manopause }}
                                        </th>
                                    @endif

                                    @if (!empty($mh->manopause_since_year))
                                        <th>
                                            <span class="ivf-label">Since Year :</span>
                                            {{ $mh->manopause_since_year }}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            <tr>
                                <th>
                                    <span class="anc-label">
                                        @if(!empty($mh->same_past) && $mh->same_past == 'same')
                                            Present / Past M/H :
                                        @endif
                                    </span>
                                    @if (!empty($mh->past_interval_of_day) || $mh->past_mh_2 == 'regular')
                                    Duration Of Menstruation : {{$mh->past_mh_2 == 'regular' ? '3 - 4 day' : $mh->past_interval_of_day}}
                                    @endif
                                    @if (!empty($mh->past_duration_of_day) || $mh->past_mh_2 == 'regular')
                                    at Interval Of : {{$mh->past_mh_2 == 'regular' ? '28 - 30 day' : $mh->past_duration_of_day}}
                                    @endif
                                    @if($mh->past_mh_2 != 'regular')
                                    | Irregular {{  !empty($mh->past_month) ? ucwords($mh->past_month) : ''}}
                                    @else
                                        Regular, Moderate, Painless
                                    @endif
                                </th>
                            </tr>
                            @if(!empty($mh->same_past) && $mh->same_past == 'exit')
                                <tr>
                                    <th>
                                        <span class="anc-label ">Present M/H : </span>
                                        {{ ucwords($mh->present_mh_1) }}
                                        | @if ($mh->present_mh_2 == 'regular')Regular @else IR Regular @endif
                                        @if (!empty($mh->present_duration_of_day) || $mh->present_mh_2 == 'regular')
                                        | Duration Of Menstruation : {{$mh->present_mh_2 == 'regular' ? '28 - 30 day' : $mh->present_duration_of_day}}
                                        @endif
                                        @if (!empty($mh->present_interval_of_day) || $mh->present_mh_2 == 'regular')
                                        at Interval Of : {{$mh->present_mh_2 == 'regular' ? '3 - 4 day' : $mh->present_interval_of_day}}
                                        @endif
                                        @if(!empty($mh->present_mh_2) && $mh->present_mh_2 != 'regular' && !empty($mh->present_month))
                                            | Irregular {{!empty($mh->present_month) ? ucwords($mh->present_month) : ''}}
                                        @else
                                            Regular, Moderate, Painless
                                        @endif
                                    </th>
                                </tr>
                            @endif
                            @php
                                
                                $lmddate = !empty($mh->last_menstrual_date) ? cdate($mh->last_menstrual_date)->format('d/m/Y') : null;
                                $date = !empty($mh->edd) ? cdate($mh->edd)->format('D d M Y') : null;
                                $usgDate = !empty($mh->usg_edd) ? cdate($mh->usg_edd)->format('D d M Y') : null;
                            @endphp
                            @if($lmddate || $date)
                                <tr>
                                    <th colspan="6">
                                        @if($lmddate)
                                            <span class="anc-label lmd-lable">Last Menstrual Date : </span>{{$lmddate}}
                                        @endif
                                        @if($date)
                                            <br><br>
                                                <span class="anc-label lmd-lable">Expected Date of Delivery : </span> {{$date}}
                                        @if($usgDate) <br><br>
                                                <span class="anc-label lmd-lable">Corrected USG EDD :</span> {{$usgDate}}
                                            @endif
                                        @endif
                                        @if (!empty($mh->since_month))
                                            | Since Month : {{$mh->since_month}}
                                        @endif
                                        @if (!empty($mh->since_cycle))
                                            | Since Cycle : {{$mh->since_cycle}}
                                        @endif
                                    </th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif
                @if($ancFirst_mh_date && $isFirstVisit == false)
                    <table cellspacing="0" cellpadding="0" class="table m-b-0  module-report-table">
                        @if($ancFirstlmdDate)
                        <tr>
                            <th>
                                <span class="anc-label lmd-lable">Last Menstrual Date : </span>{{$ancFirstlmdDate}}
                            </th>
                        </tr>
                        @endif
                        @if($ancFirsteddDate)
                        <tr>
                            <th>
                                <span class="anc-label lmd-lable">Expected Date of Delivery : </span> {{$ancFirsteddDate}}
                            </th>
                        </tr>
                        @if($ancFirstusgEddDate)
                        <tr>
                            <th>
                                <span class="anc-label lmd-lable">Corrected USG EDD :</span> {{$ancFirstusgEddDate}}
                            </th>
                        </tr>
                            @endif
                        @endif
                    </table>
                @endif
                @php
                    $pHistoryStatus = false;
                    $pastHistoryStatus = false;
                    $pfHistoryStatus = false;
                    if(!empty($patientsDetails->personal_history_history_type)){
                        $personalHistory = is_array($patientsDetails->personal_history_history_type) ? $patientsDetails->personal_history_history_type : array($patientsDetails->personal_history_history_type);
                        $pHistoryData = strtolower(implode(',',$personalHistory));
                        $pHistoryStatus = true;
                        if(strpos($pHistoryData, 'nad') !== false) {
                            $pHistoryStatus = false;
                        }
                    }
                    if(!empty($patientsDetails->family_history)){
                        $pfHistoryData = $patientsDetails->family_history;
                        if(is_array($patientsDetails->family_history)){
                            $pfHistoryData = strtolower(implode(',',$patientsDetails->family_history));
                            $pfHistoryStatus = true;
                        }
                        if(strpos($pfHistoryData, 'nad') !== false) {
                            $pfHistoryStatus = false;
                        }
                    }
                    if(!empty($patientsDetails->past_history_type)){
                        $pastHistoryData = $patientsDetails->past_history_type;
                        if(is_array($patientsDetails->past_history_type)){
                            $pastHistoryData = strtolower(implode(',',$patientsDetails->past_history_type));
                            $pastHistoryStatus = true;
                        }
                        if(strpos($pastHistoryData, 'nad') !== false) {
                            $pastHistoryStatus = false;
                        }
                    }
                @endphp
                <!-- Patients Detailes --}}  -->
                @if($patientsDetails && ($pastHistoryStatus == true  || $pfHistoryStatus == true || $pHistoryStatus == true))
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            @php
                                // $personal_history_type = ['1'=>'NAD','2'=>"Diabetes Mellitus",'3'=>"Thyroid",'4'=>"Heart Disease",'5'=>"Hypertension"];
                            @endphp
                            @if($pHistoryStatus == true)
                                <tr>
                                    <th>
                                        <span class="anc-label ">Personal History :</span>
                                        {{implode(',',is_array($patientsDetails->personal_history_history_type) ? $patientsDetails->personal_history_history_type : (array)$patientsDetails->personal_history_history_type)}}
                                    </th>
                                </tr>
                            @endif
                            @if(isset($patientsDetails->personal_history_detail) && !empty($patientsDetails->personal_history_detail))
                                <tr>
                                    <th>
                                        <span class="anc-label">Personal History Detail:</span>
                                        {{$patientsDetails->personal_history_detail}}
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($patientsDetails->personal_history_date))
                                <tr>
                                    <th>
                                        <span class="anc-label">Date :</span>
                                        {{cdate($patientsDetails->personal_history_date)->format('D d M Y')}}
                                    </th>
                                </tr>
                            @endif
                            @if($pfHistoryStatus == true)
                                <tr>
                                    <th>
                                        <span class="anc-label">Family History :</span>
                                        {{implode(',',is_array($patientsDetails->family_history) ? $patientsDetails->family_history : (array)$patientsDetails->family_history)}}
                                    </th>
                                </tr>
                            @endif
                            @if(isset($patientsDetails->family_history_detail) && !empty($patientsDetails->family_history_detail))
                                <tr>
                                    <th>
                                        <span class="anc-label">Family History Detail:</span>
                                        {{$patientsDetails->personal_history_detail}}
                                    </th>
                                </tr>
                            @endif   
                             
                            @if($pastHistoryStatus == true)
                                <tr>
                                    <th>
                                        <span class="anc-label">Past History :</span>
                                        {{implode(',',is_array($patientsDetails->past_history_type) ? $patientsDetails->past_history_type : (array)$patientsDetails->past_history_type)}}
                                    </th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif

                <!-- OE  -->
                @if(!empty($oe))
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <div class="panel-title header-print-title">On Examination
                                        <span class="text-danger">{{!empty($oe->late_concept) && $oe->late_concept == 1 ? 'Late Conception' : null}}</span>
                                    </div>
                                </td>
                            </tr>
                            @if(!empty($oe->le->bp) || !empty($oe->le->temp) || !empty($oe->le->pulse))
                                <tr>
                                    <th>
                                        <br>
                                        <span class="anc-label">Vitals :</span>
                                        @if(!empty($oe->le->temp))
                                            <br>
                                            <span class="anc-label">Temp :</span>
                                            {{$oe->le->temp}}
                                        @endif
                                        @if(!empty($oe->le->pulse))
                                            <br>
                                            <span class="anc-label">Pulse :</span>
                                            {{$oe->le->pulse ? $oe->le->pulse : '80'}} / Min
                                        @endif
                                        @if(!empty($oe->le->bp))
                                            <br>
                                            <span class="anc-label">B.P :</span>
                                            {{$oe->le->bp ? $oe->le->bp : '110/70'}} MMHG
                                        @endif
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($oe->oe_type) || !empty($oe->oe_no) || !empty($oe->oe_child_type))
                                <tr>
                                    <th>
                                        @if(isset($oe->oe_type))
                                            <span class="anc-label "> OE :</span>
                                            {{ !empty($oe->oe_type) && $oe->oe_type == 'tvs' ? 'TVS' : 'PA'  }}
                                        @endif

                                        @if (!empty($oe->oe_no) && (!in_array($oe->oe_no, ['1'])))
                                            @if (!empty($oe->oe_child_type) && array_key_exists($oe->oe_child_type, $childType))
                                                {{ ' | ' . $childType[$oe->oe_child_type] }}
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                            @endif
                            @php
                                $gSacNo = 'd-none';
                            @endphp
                            @if(!empty($oe->late_data) && !empty($oe->late_data->late_concept) && $oe->late_data->late_concept == 'Yes')
                                <tr>
                                    @if(!empty($oe->late_data->late_concept_week))
                                        <th>
                                            <span class="anc-label"> Possibility of late conception by</span>
                                            {{$oe->late_data->late_concept_week}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if(!empty($oe->utdata))
                                @foreach($oe->utdata as $key=>$value)
                                    @php
                                        if($key == 1) {
                                            $gSacNo = (isset($patientsObstratics->upt_type)) && ($patientsObstratics->upt_type == 'positive') &&  (((isset($value->oe_ut_sac) && strtolower($value->oe_ut_sac) == 'no') || (isset($value->oe_ut_sac_2)) &&  strtolower($value->oe_ut_sac_2) == 'no')) ? '' : 'd-none';
                                        }
                                    @endphp
                                    {{--                                @if(!empty($oe->p_a->type) && $oe->p_a->type == 'yes')--}}
                                        @if($key == 1)
                                            <tr>
                                                <th colspan="2">
                                                    <span class="anc-label ">P/A : </span>
                                                    @if(!empty($oe->oe_no) && in_array($oe->oe_no,$utsizearray1))
                                                        <?=$oe->oe_no?>
                                                    @else
                                                        Soft
                                                    @endif
                                                    {{!empty($oe->p_a->details) ? $oe->p_a->details : null}}
                                                </th>
                                            </tr>
                                        @endif
                                    {{--                                @endif--}}
                                    @if(!empty($oe->p_s->type) && $oe->p_s->type == 'yes')
                                        <tr>
                                            <th colspan="2">
                                                <span class="anc-label ">P/S : </span>
                                                    {{!empty($oe->p_s->details) ? $oe->p_s->details : null}}
                                            </th>
                                        </tr>
                                    @endif
                                    @if(!empty($oe->p_v->type) && $oe->p_v->type == 'yes' && !empty($oe->p_v->details))
                                        <tr>
                                            <th colspan="2">
                                                <span class="anc-label ">P/V: </span>
                                                    {{!empty($oe->p_v->details) ? $oe->p_v->details : ''}}
                                            </th>
                                        </tr>
                                    @endif
                                    @if($key == 1)

                                        @if(!empty($oe->uterus->type) && !empty($value->female_type) && ($value->female_type == 'Ectopic' || $value->female_type == 'Molar Pregnancy' || $value->female_type == 'No intrauterine or extrauterine G- Sac seen at present'))
                                        <br>
                                            <tr>
                                                <th>
                                                <span class="anc-label">Uterus : </span>{{($oe->uterus->type) && !empty($oe->uterus->type) && $oe->uterus->type == 1 ? 'Normal' : 'Abnormal'.(!empty($oe->uterus->details) ? ' / '.$oe->uterus->details : '')}}
                                                </th>
                                            </tr>
                                        @endif
                                        @if(!empty($oe->endometrial_thickness))
                                            <br>
                                            <tr>
                                                <th>
                                                <span class="anc-label">Endometrial Thickness : </span>{{$oe->endometrial_thickness}}
                                                </th>
                                            </tr>
                                            <br>
                                        @endif
                                        @if(!empty($oe->uterus->type) && !empty($value->female_type) && ($value->female_type == 'Ectopic' || $value->female_type == 'Molar Pregnancy' || $value->female_type == 'No intrauterine or extrauterine G- Sac seen at present'))

                                            @if(!empty($oe->uterus))
                                                <tr><th><span class="anc-label">Ovary : </span></th></tr>
                                            @endif
                                            <tr>
                                                <th>

                                                    @if(!empty($oe->uterus->right))
                                                            <span class="anc-label">Right Ovary: </span>{{($oe->uterus->right->adnexia == 1) ? 'Normal' : ''}}
                                                            @if(!empty($oe->uterus->right->adnexia_details))
                                                                <br><span class='anc-label pl-2'>Adnexia Details:</span>{{$oe->uterus->right->adnexia_details}}
                                                            @endif
                                                            @if(!empty($oe->ovary->right->details))
                                                                <br><span class='anc-label pl-2'>Details:</span>{{implode(',',$oe->ovary->right->details)}}
                                                            @endif
                                                        @if(!empty($oe->ovary->right->afcs) )
                                                        <br>
                                                        <span class="anc-label">Follicle numbers per ovary: </span>
                                                            {{$oe->ovary->right->afcs}}
                                                        @endif
                                                    @endif
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @if(!empty($oe->uterus->left))
                                                            <span class="anc-label">Left Ovary: </span>{{($oe->uterus->left->adnexia == 1) ? 'Normal' : ''}}
                                                            @if(!empty($oe->uterus->left->adnexia_details))
                                                                <br><span class='anc-label pl-2'>Adnexia Details:</span>{{$oe->uterus->left->adnexia_details}}
                                                            @endif
                                                            @if(!empty($oe->ovary->left->details))
                                                                <br><span class='anc-label pl-2'>Details:</span>{{implode(',',$oe->ovary->left->details)}}
                                                            @endif
                                                        @if(!empty($oe->ovary->left->afcs) )
                                                        <br>
                                                        <span class="anc-label">Follicle numbers per ovary: </span>
                                                            {{$oe->ovary->left->afcs}}
                                                        @endif
                                                    @endif
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th colspan="2">
                                                <span class="anc-label">
                                                    @if(!empty($oe->oe_no) && array_key_exists($oe->oe_no,$oe_number))
                                                        <?="Uterus ".$oe->oe_no?>
                                                    @endif

                                                    @if((!empty($value->female_type) && ($value->female_type == 'Intrauterine')) || empty($value->female_type))
                                                        @if(@$value->oe_ut_sac_1)
                                                            @if(!in_array($value->oe_ut_sac_1,[1,2]))
                                                                @php $psize = " pregnant size"; @endphp
                                                            @endif
                                                            {{!empty($value->oe_ut_sac_1) ? "Uterus ".$weekData[$value->oe_ut_sac_1].$psize : null}}
                                                            {{!empty($oe->oe_no) && $oe->oe_no == 2 && isset($oe_number[$oe->oe_no]) ? '- '.$oe_number[$oe->oe_no] : null}}
                                                        @endif
                                                    @endif
                                                    @if(@$value->oe_ut_sac_1 && $value->oe_ut_sac_1>=13)
                                                    <br>
                                                        @php
                                                            $instra = "પેટમાં કે કમરમાં થોડી થોડી વારે દુખાવો આવે એટલે કે ડિલિવરીનો દુખાવો હોય, ખુન પડે, પાણી પડે કે બાળકો ઓછું ફરકે તો હોસ્પિટલ તાત્કાલિક તપાસ માટે આવવું.";
                                                        @endphp
                                                    @endif
                                                </span>
                                            </th>
                                        </tr>
                                        
                                    @endif
                                        {{-- @if (!empty($oe->oe_no) && ($oe->oe_no >= 3))
                                            <tr>
                                                <td>
                                                    {{!empty($value->oe_ut_sac_details) ? $value->oe_ut_sac_details : null}} &nbsp;&nbsp;&nbsp;
                                                    @php
                                                    $childType = [
                                                        '1'=>'Monochorionic Monochorionic Twin',
                                                        '2'=>'Dichorionic Diamniotic Twin',
                                                        '3'=>'Trichorionic Triamniotic Triplets',
                                                        '4'=>'DCDA Twin Spontaneously Reduced to Singleton',
                                                        '5'=>'Quadruchorionic Quadruamniotic Quadruplets',
                                                        '6'=>'Triplets with Fetus A and B monochorionic Pair',
                                                        '7'=>'Twins Reduced to Singleton',
                                                        '8'=>'Monochorionic Diamniotic'
                                                    ];
                                                    @endphp
                                                    @if (!empty($value->child_type) && array_key_exists($value->child_type, $childType))
                                                        {{ 'Child Type: ' . $childType[$value->] }}
                                                    @else
                                                        {{ 'Child Type: -'}}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif --}}
                                        @if ($key == 1 && isset($oe->fefal_reduction))
                                            <tr>
                                                @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes')
                                                    <th>
                                                        <span class="anc-label ">Fetal Reduction :</span>
                                                        {{ $oe->fefal_reduction->type == 'yes' ? 'Yes' : 'No' }}
                                                    </th>
                                                @endif
                                                @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes' && !empty($oe->fefal_reduction->date))
                                                    <th>
                                                        <span class="anc-label ">Date :</span>
                                                        {{$oe->fefal_reduction->date}}
                                                    </th>
                                                @endif
                                                @if (isset($oe->how_much) && !empty($oe->how_much->type))
                                                    @if (isset($oe->how_much->type))
                                                        <th>
                                                            <span class="anc-label ">How Many :</span>
                                                            {{$oe->how_much->type == 'yes' ? 'Yes' : 'No'}}
                                                        </th>
                                                    @endif
                                                    @if (isset($oe->how_much->type) && $oe->how_much->type == 'yes'  && !empty($oe->fefal_reduction->how_much_value))
                                                        <th>
                                                            <span class="anc-label "> How Many Value :</span>
                                                            {{$oe->fefal_reduction->how_much_value}}
                                                        </th>
                                                    @endif
                                                @endif
                                            </tr>
                                        @endif
                                    @if ((!empty($value->fcp) || !empty($value->position_type) || !empty($value->liquor_type) || !empty($value->liquor_sub_type)) && (!empty($value->oe_ut_sac_1)) &&  ($weekData[$value->oe_ut_sac_1] >= 20 ))
                                        <tr>
                                            <th>
                                                @if(!empty($value->fcp))
                                                    <span class="anc-label ">FCA: </span>
                                                    @if (isset($value->fcp))
                                                        {{ !empty($value->fcp) ? $value->fcp : '-' }}
                                                    @endif
                                                @endif
                                                @if(!empty($value->position_type))
                                                    <br>
                                                    <span class="anc-label ">Position: </span>
                                                    @if (isset($value->position_type))
                                                        {{ !empty($value->position_type) ? $value->position_type : '-' }}
                                                    @endif
                                                @endif
                                                @if(!empty($value->liquor_type))
                                                    <br>
                                                    <span class="anc-label ">Liquor : </span>
                                                    @if (isset($value->liquor_type))
                                                        {{ !empty($value->liquor_type) ? $value->liquor_type : '-' }}
                                                    @endif
                                                @elseif(!empty($value->liquor_sub_type))
                                                    <br>
                                                    <span class="anc-label ">Liquor : </span>
                                                    @if (isset($value->liquor_sub_type))
                                                        {{ !empty($value->liquor_sub_type) ? $value->liquor_sub_type : '-' }}
                                                    @endif
                                                @endif
                                                @if(isset($value->expected_birth_weight) && !empty($value->expected_birth_weight))
                                                <br>
                                                <span class="anc-label ">Expected Birth Weight : </span>
                                                    {{ !empty($value->expected_birth_weight) ? $value->expected_birth_weight : '-' }}
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif

                            @php
                                $isMedical = 'd-none';
                                $isSurgical = '';
                                $ecTopic = (isset($oe->ec_topic)) && (($oe->ec_topic) == 'yes') ? '' : 'd-none';
                                $treact = '';
                                if ((isset($oe->treact->type))) {
                                    $treact = ($oe->treact->type == 'medical') ? 'medical' : 'surgical';
                                    $isMedical = ($treact == 'medical') ? '' : 'd-none';
                                    $isSurgical = ($treact == 'surgical') ? '' : 'd-none';
                                }
                            @endphp
                            @if($gSacNo == '')
                                <tr>
                                    @if(!empty($oe->ec_topic))
                                        <th>
                                            <span class="anc-label ">EC Topic :</span>
                                            {{ isset($oe->ec_topic) && $oe->ec_topic == 'yes' ? 'Yes' : 'No'}}
                                        </th>
                                    @endif
                                    @if((isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics)))
                                        <th>
                                            <span class="anc-label ">Expert USG :</span>
                                            {{'Yes'}}
                                        </th>
                                    @endif
                                    @if(isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics) || (!empty($oe->expert_usg)) || !empty($oe->expert_usg_date))
                                        @if(!empty($oe->expert_usg))
                                            <th>
                                                <span class="anc-label ">Expert USG Details :</span>
                                                {{$oe->expert_usg}}
                                            </th>
                                        @endif
                                        @if(!empty($oe->expert_usg_date))
                                            <th>
                                                <span class="anc-label ">Date :</span>
                                                {{$oe->expert_usg_date}}
                                            </th>
                                        @endif
                                    @endif
                                </tr>
                            @endif
                            @if($gSacNo == '' && (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics) || !empty($oe->blood_report)))
                                <tr>
                                    @if(isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics))
                                        <th>
                                            <span class="anc-label ">Blood Report :</span>
                                            {{'Yes'}}
                                        </th>
                                    @endif
                                    @if (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics))
                                        @if(!empty($oe->blood_report))
                                            <th>
                                                <span class="anc-label ">Blood Report Details :</span>
                                                {{$oe->blood_report}}
                                            </th>
                                        @endif
                                        @if(!empty($oe->blood_report_date))
                                            <th>
                                                <span class="anc-label ">Date :</span>
                                                {{$oe->blood_report_date}}
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endif

                            @if($gSacNo == '')
                                <tr>
                                    @if (isset($oe->treact->type))
                                        <th>
                                            <span class="anc-label ">Treact :</span>
                                            {{ ($oe->treact->type == 'medical') ? 'Medical' : 'Surgical' }}
                                        </th>
                                    @endif
                                    @if (isset($oe->treact->type))
                                        @if ($oe->treact->type == 'medical')
                                            @if(!empty($oe->treact->medicine_details))
                                                <th>
                                                    <span class="anc-label ">Medicine Details :</span>
                                                    {{$oe->treact->medicine_details}}
                                                </th>
                                            @endif
                                            <td>
                                                @if (isset($oe->treact->medicine_dose))
                                                    <span class="anc-label ">Medicine Dose: </span>
                                                    @switch($oe->treact->medicine_dose)
                                                        @case('1')
                                                            OD
                                                            @break
                                                        @case('2')
                                                            BD
                                                            @break
                                                        @case('3')
                                                            TDS
                                                            @break
                                                        @case('4')
                                                            ADS
                                                            @break
                                                        @case('5')
                                                            Weekly / 1 time
                                                            @break
                                                        @case('6')
                                                            Weekly / 2 time
                                                            @break
                                                        @case('7')
                                                            Stat
                                                            @break
                                                        @case('8')
                                                            SOS
                                                            @break
                                                    @endswitch
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endif

                                        @if ($oe->treact->type == 'surgical' && !empty($oe->treact->surgical_details))
                                            <th>
                                                <span class="anc-label "> Operation Details :</span>
                                                {{$oe->treact->surgical_details}}
                                            </th>
                                        @endif
                                    @endif
                                </tr>
                            @endif
                            @if($gSacNo == '')
                                <tr>
                                    <th>
                                        <span class="anc-label "> Follow-up Date :</span>
                                        {{ isset($oe->follow_up) && !empty($oe->follow_up) ? $oe->follow_up : '-' }}
                                    </td>
                                </tr>
                            @endif
                            <tr>

                            </tr>
                            {{-- @if(!empty($oe->remark))
                                <tr>
                                    <th>
                                        <span class="anc-label">Remark : </span>
                                        {{$oe->remark}}
                                    </th>
                                </tr>
                            @endif --}}
                        </tbody>
                    </table>
                @endif
                @if(!$ancHistory)
                    <div class="page-break"></div>
                @endif

                @if($ancData->is_gynec == 1 && !empty($oe->plan_medically_type->type))
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">Plan </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <span class="anc-label ">Plan Type :</span>
                                    {{ucfirst($oe->plan_medically_type->type)}}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                @endif

                {{-- usg data --}}
                @php
                    $usgData = array_filter((array)$usg);
                    $ntScanDate = !empty($usg->nt_scan) ? cdate($usg->nt_scan)->format('Y-m-d') : null;
                    $earlyScanDataValue = !empty($usg->early_scan) ? cdate($usg->early_scan)->format('Y-m-d') : null;
                    $anomaliesMilesScanDateValue = !empty($usg->anomalies_miles) ? cdate($usg->anomalies_miles)->format('Y-m-d') : null;
                    $growthScanDate = !empty($usg->growth_scan) ? cdate($usg->growth_scan)->format('Y-m-d') : null;
                    $nowDate = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                @if(!empty($usgData) && !empty($usg->is_usg_print) && $usg->is_usg_print == 1 && (!empty($usg->early_scan) || !empty($usg->early_scan) || !empty($usg->nt_scan && (strtotime($ntScanDate) > strtotime($nowDate))) || !empty($usg->anomalies_miles)))
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">USG</div>
                                </td>
                            </tr>
                            
                                @if (!empty($usg->early_scan) && (strtotime($earlyScanDataValue) >= strtotime($nowDate)))
                                <tr>
                                    <th>
                                        <span class="anc-label">
                                            @if($usgStatus == 1)
                                                Early Scan :
                                            @else
                                                Approx Appointment For Early Scan :
                                            @endif
                                        </span>
                                        {{cdate($usg->early_scan)->format('D d M Y')}}
                                    </th>
                                </tr>
                                @endif
                                @if (!empty($usg->nt_scan) && (strtotime($ntScanDate) >= strtotime($nowDate)))
                                <tr>
                                    <th>
                                        <span class="anc-label">
                                            @if(strtotime($usg->nt_scan) == strtotime($oe->follow_up))
                                                N.T Scan :
                                            @else
                                                Approx Appointment For N.T Scan :
                                            @endif
                                        </span>
                                        {{cdate($usg->nt_scan)->format('D d M Y')}}
                                    </th>
                                </tr>
                                @endif
                            
                                @if (!empty($usg->anomalies_miles) && (strtotime($anomaliesMilesScanDateValue) >= strtotime($nowDate)))
                                <tr>
                                    <th>
                                        <span class="anc-label">
                                            @if(strtotime($usg->anomalies_miles) == strtotime($oe->follow_up))
                                                Anomaly Scan :
                                            @else
                                                Approx Appointment For Anomaly Scan :
                                            @endif
                                        </span>
                                        {{cdate($usg->anomalies_miles)->format('D d M Y')}}
                                    </th>
                                </tr>
                                @endif
                                @if (!empty($usg->growth_scan) && (strtotime($growthScanDate) >= strtotime($nowDate)))
                                <tr>
                                    <th>
                                        <span class="anc-label">
                                            @if(strtotime($usg->growth_scan) == strtotime($oe->follow_up))
                                                Growth Scan :
                                            @else
                                                Approx Appointment For Growth Scan :
                                            @endif
                                        </span>
                                        {{cdate($usg->growth_scan)->format('D d M Y')}}
                                    </th>
                                </tr>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                @endif
                <?php
                    unset($treatment->medicinedata);
                ?>
                @if(count((array)$treatment) > 0)
                    <br>
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">Treatment (Medicine)</div>
                                </td>
                            </tr>
                            @if(!empty($treatment))
                                <table class="medicine-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Dose</th>
                                            <th>Timing</th>
                                            <th>Freq.</th>
                                            <th>Duration</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($treatment as $key=>$row)
                                        <tr>
                                            <?php
                                                $medicine_status = '';
                                                $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                $firstCharacter = strtoupper(substr($mId, 0, 3));
                                                if($firstCharacter == "INJ"){
                                                    if(!empty($row->medicine_time))
                                                    {
                                                        switch($row->medicine_time){
                                                        case '1':
                                                            $medicine_status = 'IV';
                                                            break;
                                                        case '2':
                                                            $medicine_status = 'IM';
                                                            break;
                                                        case '3':
                                                            $medicine_status = 'SC';
                                                            break;
                                                        case '4':
                                                            $medicine_status = 'Oral';
                                                            break;
                                                        case '5':
                                                            $medicine_status = 'P/V';
                                                            break;
                                                        case '6':
                                                            $medicine_status = 'P/A';
                                                            break;
                                                        }
                                                    }
                                                    
                                                    $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                                    if($mData==$medicine_status) {
                                                        $medicine_status = "-";
                                                    }
                                                }else{
                                                    $mData = [0,0,0,0];

                                                    if(@$row->quantity>0) {
                                                        $mData[0] = $row->quantity;
                                                    }
                                                    if(@$row->quantity_2>0) {
                                                        $mData[1] = $row->quantity_2;
                                                    }
                                                    if(@$row->quantity_3>0) {
                                                        $mData[2] = $row->quantity_3;
                                                    }
                                                    if(@$row->quantity_4>0) {
                                                        $mData[3] = $row->quantity_4;
                                                    }
                                                    $mData = implode('-',$mData);
                                                    switch($row->medicine_status){
                                                        case '1':
                                                            $medicine_status = 'જમ્યા પછી';
                                                            break;
                                                        case '2':
                                                            $medicine_status = 'જમ્યા પહેલાં';
                                                            break;
                                                        case '3':
                                                            $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                                            break;
                                                    }
                                                }
                                            ?>
                                            <td>{{$row->medicine}}</td>
                                            <td>{{$mData}}</td>
                                            <td>{{$medicine_status}}</td>
                                            <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                                            <td>
                                            {{(!empty($row->no)) ? $row->no.' days' : ''}}
                                            </td>
                                            <td>{{isset($row->note) && !empty($row->note) ? $row->note : '-'}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            
                        </tbody>
                    </table>
                @endif
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                    <tbody>
                        @if(@$usg->termination_type && !empty($usg->termination_type) || (@$usg->termination_detail))
                            <tr>
                                <th>
                                    <?php
                                    
                                        if(isset($terminationtype[$usg->termination_type]))
                                        {
                                            echo 'Admission for '.$terminationtype[$usg->termination_type].!empty($usg->termination_detail) ? ' - '.$usg->termination_detail : '';
                                        }
                                    ?>
                                </th>
                            </tr>
                            @endif
                            @if(!empty(@$usg->referfor))
                                <tr>
                                    <th>
                                        Patient is referred for {{$usg->referfor}} @if(!empty(@$usg->referto)) {{' to '.$usg->referto}} @endif
                                    </th>
                                </tr>
                            @endif
                    </tbody>
                </table>
                {{-- investigation --}}
                @if(!empty($patientsInvestigation->investigation_data) || (!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes') || (!empty($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type == 'yes') || (!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes') || !empty($patientsInvestigation->investigation_extra) || !empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes')
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                            @if(!empty($patientsInvestigation->investigation_data))
                                <tr>
                                    <th>
                                        @php
                                            $investigationData = [];
                                            $investigationValueDetails = [];
                                            $investigationReport = $investigationReport['reportData'];
                                            $data = $patientsInvestigation->investigation_data;
                                            $investigationValueData = (array)($patientsInvestigation->investigation_details ?? []);
                                            foreach($data as $key => $value){
                                                if(!isset($investigationReport[$value])){
                                                    continue;
                                                }
                                                if(!empty($investigationValueData[$value])){
                                                    $investigationValueDetails[$investigationReport[$value]] = $investigationValueData[$value];
                                                }else{
                                                    $investigationData[] = $investigationReport[$value];
                                                }
                                            }
                                        @endphp
                                        @if(count($investigationData)>0)
                                            <span class="anc-label">Investigation Advise : {{implode(',',$investigationData)}}</span>
                                        @endif
                                    </th>
                                </tr>
                                @if(!empty($investigationValueDetails))
                                    <tr>
                                        <th>
                                            <span class="anc-label">Investigation Done :</span>
                                            @foreach($investigationValueDetails as $key => $value)
                                                {!! '<span class="anc-label">'.$key.'</span> :' .  $value !!}
                                            @endforeach
                                        </th>
                                    </tr>
                                @endif
                            @endif
                            @if(empty($oe->is_lft) || (!empty($oe->is_lft) && $oe->is_lft == 0))
                                @if(!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="anc-label ">Early Scan Report :</span>
                                            {{ !empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes' ? 'Yes' : 'No' }}
                                            @if(!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes')
                                                <span class="anc-label">| Date : </span>&nbsp;
                                                {{!empty($patientsInvestigation->investigation_early_scan_date) ? cdate($patientsInvestigation->investigation_early_scan_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y')}}
                                                @if (!empty($patientsInvestigation->investigation_early_scan_hb))
                                                <span class="anc-label">| HB : </span>{{$patientsInvestigation->investigation_early_scan_hb}}
                                                @endif
                                                @if(!empty($patientsInvestigation->investigation_early_scan_hb_details))
                                                <span class="anc-label">| HB Details : </span>{{$patientsInvestigation->investigation_early_scan_hb_details}}
                                                @endif
                                                @if (!empty($patientsInvestigation->investigation_tsh))
                                                <span class="anc-label">| TSH : </span>{{$patientsInvestigation->investigation_tsh}}
                                                @endif
                                                @if(!empty($patientsInvestigation->investigation_tsh_details))
                                                <span class="anc-label">| RX : </span>{{$patientsInvestigation->investigation_tsh_details}}
                                                @endif
                                                @if(!empty($patientsInvestigation->investigation_rbs))
                                                <span class="anc-label">| RBS : </span>{{$patientsInvestigation->investigation_rbs}}
                                                @endif
                                                @if(!empty($patientsInvestigation->investigation_rbs_details))
                                                <span class="anc-label">| RBS Details : </span>{{$patientsInvestigation->investigation_rbs_details}}
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            @endif
                            @if(@$patientsInvestigation->is_print)
                                <tr>
                                    <th>
                                        <span class="anc-label ">ANC Profile :</span>
                                        @php
                                            $wnlType = ['1'=>"WNL",'2'=>"Abnormal"];
                                        @endphp
                                        @if (isset($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type  == 'yes')
                                            <br>
                                            <span class="anc-label">Date : </span>
                                            {{!empty($patientsInvestigation->investigation_anc_date) ? cdate($patientsInvestigation->investigation_anc_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y')}}
                                            @if (!empty($patientsInvestigation->investigation_cbc_mp->status))
                                                <br>
                                                <span class="anc-label">CBC MP Type: </span>{{$wnlType[$patientsInvestigation->investigation_cbc_mp->status]}}
                                            @endif
                                        @endif
                                        @if (!empty($patientsInvestigation->investigation_urine->status))
                                            <br>
                                            <span class="anc-label">Urine Type: </span>{{$wnlType[$patientsInvestigation->investigation_urine->status]}}
                                            @if($patientsInvestigation->investigation_urine->status == 2)
                                                @if(!empty($patientsInvestigation->investigation_urine->type))
                                                    <br>
                                                    <span class="anc-label">Puccell  :</span> {{$patientsInvestigation->investigation_urine->type}}
                                                    @if($patientsInvestigation->investigation_urine->type == 'present')
                                                        <br>
                                                        <span class="anc-label">Puscell Details : </span>{{$patientsInvestigation->investigation_urine->puscell}}
                                                    @endif
                                                @endif
                                                @if(!empty($patientsInvestigation->investigation_urine->urine_albumine))
                                                    <br>
                                                    <span class="anc-label">Urine Albumine : </span>{{$patientsInvestigation->investigation_urine->urine_albumine}}
                                                @endif
                                            @endif
                                        @endif
                                        @if (!empty($patientsInvestigation->investigation_blood_group))
                                            <br>
                                            <span class="anc-label">Blood Group : </span>{{$patientsInvestigation->investigation_blood_group}}
                                        @endif
                                        @if (!empty($patientsInvestigation->investigation_anc_rbs))
                                            <br>
                                            <span class="anc-label">RBS : </span>{{$patientsInvestigation->investigation_anc_rbs}}
                                        @endif
                                        @if (!empty($patientsInvestigation->anc_hiv))
                                            <br>
                                            <span class="anc-label">HIV : </span>{{ $patientsInvestigation->anc_hiv == 'positive' ? 'Positive' : 'Negative' }}
                                        @endif
                                        @if (!empty($patientsInvestigation->anc_hbsag))
                                            <br>
                                            <span class="anc-label">HBSAG : </span>{{ $patientsInvestigation->anc_hbsag == 'positive' ? 'Positive' : 'Negative' }}
                                        @endif
                                        @if (!empty($patientsInvestigation->anc_vdrl))
                                            <br>
                                            <span class="anc-label">VDRL : </span>{{ $patientsInvestigation->anc_vdrl == 'positive' ? 'Positive' : 'Negative' }}
                                        @endif
                                    </th>
                                </tr>
                            @endif
                            @if(empty($oe->is_lft) || (!empty($oe->is_lft) && $oe->is_lft == 0))
                                @if(!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="anc-label ">Growth Report :</span>
                                            {{ !empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes' ? 'Yes' : 'No' }}
                                            @if (!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes')
                                                <span class="anc-label ">| Date : </span>
                                                {{!empty($patientsInvestigation->investigation_growth_date) ? cdate($patientsInvestigation->investigation_growth_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y')}}
                                                @if (!empty($patientsInvestigation->investigation_growth_hb))
                                                <span class="anc-label ">| HB : </span>{{$patientsInvestigation->investigation_growth_hb}}
                                                @endif
                                                @if (!empty($patientsInvestigation->investigation_growth_fbs))
                                                <span class="anc-label ">| FBS : </span>{{$patientsInvestigation->investigation_growth_fbs}}
                                                @endif
                                                @if (!empty($patientsInvestigation->investigation_growth_fbs_details))
                                                <span class="anc-label ">| FBS Details : </span>{{$patientsInvestigation->investigation_growth_fbs_details}}
                                                @endif
                                                @if (!empty($patientsInvestigation->investigation_growth_pp2bs))
                                                <span class="anc-label ">| PP2BS : </span>{{$patientsInvestigation->investigation_growth_pp2bs}}
                                                @endif
                                                @if (!empty($patientsInvestigation->investigation_growth_pp2bs_details))
                                                <span class="anc-label ">| PP2BS Details: </span>{{$patientsInvestigation->investigation_growth_pp2bs_details}}
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            @endif
                            @if(!empty($oe->is_lft) && $oe->is_lft == 1)
                                @if(!empty($patientsInvestigation->anc_lft_normal_status))
                                    <tr>
                                        <th >
                                            <span class="anc-label ">LFT :</span>
                                            {{$patientsInvestigation->anc_lft_normal_status == 1 ? 'Normal' : 'Abnormal'}}
                                            @if(!empty($patientsInvestigation->anc_lft_normal_data))
                                                {{$patientsInvestigation->anc_lft_normal_data}}
                                            @endif
                                            @if(!empty($patientsInvestigation->anc_lft_ab_normal_data_bita_hcg))
                                                {{$patientsInvestigation->anc_lft_ab_normal_data_bita_hcg}}
                                            @endif
                                        </th >
                                    </tr>
                                @endif
                            @endif
                            @if(!empty($patientsInvestigation->investigation_extra))
                                <tr >
                                    <th>
                                        <span class="anc-label ">Other Report :</span>
                                        {{$patientsInvestigation->investigation_extra}}
                                    </th>
                                </tr>
                            @endif
                            @if(empty($oe->is_lft) || (!empty($oe->is_lft) && $oe->is_lft == 0))
                                @if(!empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="anc-label">Report Upload: </span>
                                            @php
                                                $otherReport = !empty($patientsInvestigation->other_report) ? $patientsInvestigation->other_report : [];
                                            @endphp
                                            {{ !empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes' ? 'Yes' : 'No' }}
                                            @if (!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->other_report_type == 'yes')
                                                <span class="anc-label ">| Double Marker : </span> @if (in_array('double_marker',$otherReport)) Yes @else No @endif
                                                <span class="anc-label ">| Double Marker Date: </span>{{ !empty($patientsInvestigation->d_m_date) ? cdate($patientsInvestigation->d_m_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y') }}
                                                <span class="anc-label ">| Genetic Test : </span>@if (in_array('genetic_test', $otherReport)) Yes @else No @endif
                                                <span class="anc-label ">| Amniocentesis : </span>@if (in_array('amniocentesis', $otherReport)) Yes @else No @endif
                                                <span class="anc-label ">| Amniocentesis Date: </span>{{ !empty($patientsInvestigation->amniocentesis_date) ? cdate($patientsInvestigation->amniocentesis_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y') }}
                                                @if (!empty($patientsInvestigation->investigation_extra))
                                                    Extra : {{ $patientsInvestigation->investigation_extra }}
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                @endif

                @php
                    $patientsInjectionData = array_filter((array)$patientsInjection);
                @endphp
                @if($ancData->is_gynec == 0 && !empty($patientsInjectionData))
                <br>
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0  module-report-table'}}">
                        <tbody>
                        @if(!empty($patientsInjection->tt1) || !empty($patientsInjection->tt2) )
                            <tr>
                                @if(!empty($patientsInjection->tt1))
                                    <th>
                                        {{-- <span class="anc-label ">TT1 :</span> --}}
                                        @php
                                        $tt1Value = ' taken ';
                                            if(cdate($patientsInjection->tt1)->format('Y-m-d') > date('Y-m-d'))
                                            {
                                                $tt1Value = ' advice ';
                                            }
                                        @endphp
                                        {{'TT 1st dose'.$tt1Value.cdate($patientsInjection->tt1)->format('d/m/Y')}}
                                    </th>
                                @endif

                                @if(!empty($patientsInjection->tt2))
                                    <th>
                                        {{-- <span class="anc-label ">TT2 :</span> --}}
                                        @php
                                        $tt2Value = ' taken ';
                                            if(cdate($patientsInjection->tt2)->format('Y-m-d') > date('Y-m-d'))
                                            {
                                                $tt2Value = ' advice ';
                                            }
                                        @endphp
                                        {{'TT 2nd dose'.$tt2Value.cdate($patientsInjection->tt2)->format('d/m/Y')}}
                                    </th>
                                @endif
                            </tr>
                        @endif
                        @if(!empty($patientsInjection->betnasol_1) || !empty($patientsInjection->betnasol_2))
                            <tr>
                                @if(!empty($patientsInjection->betnasol_1))
                                    @php 
                                        $betnasol_1Value = ' taken ';
                                        if(cdate($patientsInjection->betnasol_1)->format('Y-m-d') > date('Y-m-d'))
                                        {
                                            $betnasol_1Value = ' advice ';
                                        }
                                    @endphp
                                    <th>
                                        {{-- <span class="anc-label ">Betnasol 1 :</span> --}}
                                        {{'Betnasol 1st dose'.$betnasol_1Value.$patientsInjection->betnasol_1}}
                                    </th>
                                @endif
                                @if(!empty($patientsInjection->betnasol_2))
                                <br>
                                    @php 
                                        $betnasol_2Value = ' taken ';
                                        if(cdate($patientsInjection->betnasol_2)->format('Y-m-d') > date('Y-m-d'))
                                        {
                                            $betnasol_2Value = ' advice ';
                                        }
                                    @endphp
                                    <th>
                                        {{-- <span class="anc-label ">Betnasol 2 :</span> --}}
                                        {{'Betnasol 2nd dose'.$betnasol_2Value.$patientsInjection->betnasol_2}}
                                    </th>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                @endif
                    <br>
                @if(!empty($usgStatus) && $usgStatus == 1 && cdate($oe->follow_up)->format('Y-m-d') > date('Y-m-d'))
                    <h5 class="lmd-lable font-bold text-center">{{"ફરીવાર સોનોગ્રાફી માટે ".cdate($oe->follow_up)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h5>
                @elseif(isset($oe->follow_up) && !empty($oe->follow_up))
                    <h5 class="lmd-lable font-bold text-center">{{"ફરીવાર ".cdate($oe->follow_up)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h5>
                @elseif($isNextAppointment == 1)
                    <h4 class="lmd-lable font-bold text-center">{{"ફરીવાર ".cdate($nextAppointmentDate)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h4>
                @endif
                <h6 class="lmd-lable font-bold text-center">{{$instra}}</h6>
            @endif
        </div>
    @endif
</div>
@if(isset($printPreview) && $printPreview != 0)

    @endsection
@endif
