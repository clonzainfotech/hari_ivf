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
@endphp

<style>
    .module-report-table {
        font-family: roboto-black;
        width: 100%;
    }
    .module-report-table{
        margin-bottom: 10px;
    }
    .module-report-table{
        text-align: left;
    }

    .doctor-category{
        color: #01d8da;
    }
    .module-report-table thead th{
        height: 35px;
    }

    .module-report-table tr {
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
        font-weight: bolder;
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
    /* .mt-30 {
        position:relative;
        display:table;
        table-layout:fixed;
        padding-top:30px;
        padding-bottom:30px;
        width: 94%;
        height:auto;
    } */
    .panel-primary{
        border: 1px solid;
        padding: 11px;
        /* margin-top: 100px; */
    }
    /* @media all {
        .page-break { display: none; }
    }
    @media print {
        .page-break { display: block; page-break-before: always; }
    } */
    .green-lable{
        color: #03c262 !important;
    }
    /* @page { margin-top : 120px; margin-bottom : 80px;} */
</style>

<div class="main-print-anc-div">
    <div class="panel panel-primary">
        @if($isGsac == true && $isFirstVisit == false)
            <div class="row">
                <span>
                    <div class="panel-title header-print-title">1. O/E
                        <span class="text-danger">{{!empty($oe->late_concept) && $oe->late_concept == 1 ? 'Late Conception' : null}}</span>
                        @if(!empty($lmdDate))
                            &nbsp&nbsp L.M.D Date:  <span class="text-danger">{{\Carbon\Carbon::parse($lmdDate)->format('d-m-Y') }}</span>
                        @endif
                        @if(!empty($eddDate))
                            &nbsp&nbsp EDD Date: <span class="text-danger">{{\Carbon\Carbon::parse($eddDate)->format('d-m-Y') }}</span>
                        @endif
                    </div>
                </span>
            </div>
            @if(!empty($oe->oe_type) || !empty($oe->oe_no) || !empty($oe->oe_child_type))
                <div class="row">
                    <div class="seperator">
                        OE :
                    </div>
                    <span class="seperator" colspan="6">
                        @if (isset($oe->oe_type))
                            {{ !empty($oe->oe_type) && $oe->oe_type == 'tvs' ? 'TVS' : 'PA'  }}
                        @else
                            {{ '-' }}
                        @endif
                        @php
                            $oe_number = ['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'];
                        @endphp
                        @if (!empty($oe->oe_no) && array_key_exists($oe->oe_no, $oe_number))
                            {{ ' | ' . $oe_number[$oe->oe_no] }}
                        @endif
                        @if (!empty($oe->oe_no) && (in_array($oe->oe_no, ['1', '2'])))
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
                            @if (!empty($oe->oe_child_type) && array_key_exists($oe->oe_child_type, $childType))
                                {{ ' | ' . $childType[$oe->oe_child_type] }}
                            @endif
                        @endif
                    </span>
                </div>
            @endif
            {{-- @php
                $gSacNo = 'd-none';
            @endphp --}}
            @foreach($oe->utdata as $key=>$value)

                {{-- @php
                    if ($key == 1) {
                        $gSacNo =  (isset($patientsObstratics->upt_type)) && ($patientsObstratics->upt_type == 'positive') &&  (((isset($value->oe_ut_sac) && strtolower($value->oe_ut_sac) == 'no') || (isset($value->oe_ut_sac_2)) &&  strtolower($value->oe_ut_sac_2) == 'no')) ? '' : 'd-none';
                    }
                @endphp --}}
                <div class="row">
                    @if(!empty($value->ut_type))
                        <div class="seperator">
                            {{strtoupper($value->ut_type)}}  {{$value->ut_type == 'ut' ? '(wks)' : '(mm)'}} :
                        </div>
                    @endif
                    <span class="seperator">
                        {{!empty($value->oe_ut_sac) ? $value->oe_ut_sac : null}}
                        @if(!empty($value->oe_ut_sac_2))
                            {{!empty($value->ut_type) && $value->ut_type == 'ut' ? '-' : '.'}}
                            {{!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null}}
                        @endif
                    </span>
                    @if (!empty($oe->oe_no) && ($oe->oe_no >= 3))
                        <span class="seperator">
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
                                {{ 'Child Type: ' . $childType[$value->child_type] }}
                            @else
                                {{ 'Child Type: -'}}
                            @endif
                        </span>
                    @endif
                    {{-- @if(($value->oe_ut_sac < 13 || $value->oe_ut_sac_2 < 13) || $value->ut_type == 'g-sac')
                        <div class="seperator">
                            CRL
                        </div>
                        <span class="seperator">
                            {{ !empty($value->crl) ? $value->crl : '-' }}
                        </span>
                        @if (isset($value->crl_details))
                            <span class="seperator" colspan="6">
                                {{ !empty($value->crl_details) ? $value->crl_details : '-' }}
                            </span>
                        @endif
                    @endif --}}
                </div>
                @if(!empty($value->ut_type) && $value->ut_type == 'ut')
                    <div class="row">
                        <div class="seperator green-lable">
                            FCP :
                        </div>
                        <span class="seperator">
                            {{  !empty($value->fcp) ? $value->fcp : '-'}}
                        </span>
                        @if (!empty($value->liquor_type))
                            <span class="seperator green-lable">
                                Liquor :
                            </span>
                            <span class="seperator">
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
                            </span>
                        @endif
                        @if (!empty($value->position_type))
                            <div class="seperator green-lable">
                                Position :
                            </div>
                            <span class="seperator">
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
                            </span>
                        @endif
                    </div>
                    @if ($key == 1 && $value->ut_type == 'ut' && (($value->oe_ut_sac >= 11 || $value->oe_ut_sac_2 >= 11)) && isset($oe->fefal_reduction))
                        <div class="row">
                            @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes')
                                <div class="seperator">
                                    Fefal Reduction :
                                    {{'No'}}
                                </div>
                            @endif
                            @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes')
                                <div class="seperator">
                                    Date :
                                </div>
                                <span class="seperator">
                                    @if (isset($oe->fefal_reduction->date))
                                        {{ !empty($oe->fefal_reduction->date) ? $oe->fefal_reduction->date : '-' }}
                                    @else
                                        {{'-'}}
                                    @endif
                                </span>
                            @endif
                            @if (isset($oe->how_much))
                                @if (isset($oe->how_much->type))
                                    <div class="seperator">
                                        How Much :
                                    </div>
                                    <span class="seperator">
                                        @if (!empty($oe->how_much->type))
                                            {{ $oe->how_much->type == 'yes' ? 'Yes' : 'No' }}
                                        @else
                                            {{'-'}}
                                        @endif
                                    </span>
                                @endif
                                @if (isset($oe->how_much->type) && $oe->how_much->type == 'yes')
                                    <div class="seperator">
                                        How Much Value :
                                    </div>
                                    <span class="seperator">
                                        {{ isset($oe->fefal_reduction->how_much_value) && !empty($oe->fefal_reduction->how_much_value) ? $oe->fefal_reduction->how_much_value : '-' }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    @endif
                @else
                    <div class="row">
                        <div class="seperator">
                            Yolk Sac :
                        </div>
                        <span class="seperator">
                            {{  !empty($value->yalk_sac) ? $value->yalk_sac : null}}
                        </span>
                        <div class="seperator">
                            Fefal Pole :
                        </div>
                        <span class="seperator">
                            {{!empty($value->fefal_pole) ? $value->fefal_pole : null}}
                        </span>
                    </div>
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
            {{-- @if ($gSacNo == '') --}}
                <div class="row">
                    <div class="seperator">
                        EC Topic :
                    </div>
                    <span class="seperator">
                        {{ isset($oe->ec_topic) && $oe->ec_topic == 'yes' ? 'Yes' : 'No'}}
                    </span>
                    <div class="seperator">
                        Expert USG :
                    </div>
                    <span class="seperator">
                        {{ (isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics)) ? 'Yes' : 'No' }}
                    </span>
                    @if (isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics))
                        <div class="seperator">
                            Expert USG Details :
                        </div>
                        <span class="seperator">
                            {{ (isset($oe->expert_usg) && !empty($oe->expert_usg)) ? $oe->expert_usg : '-' }}
                        </span>
                        <div class="seperator">
                            Date :
                        </div>
                        <span class="seperator">
                            {{ (isset($oe->expert_usg_date) && !empty($oe->expert_usg_date)) ? $oe->expert_usg_date : '-' }}
                        </span>
                    @endif
                </div>
            {{-- @endif --}}
            {{-- @if ($gSacNo == '') --}}
                <div class="row">
                    <div class="seperator">
                        Blood Report :
                    </div>
                    <span class="seperator">
                        {{ (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics)) ? 'Yes' : 'No' }}
                    </span>
                    @if (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics))
                        <div class="seperator">
                            Blood Report Details :
                        </div>
                        <span class="seperator">
                            {{ (isset($oe->blood_report) && !empty($oe->blood_report)) ? $oe->blood_report : '-' }}
                        </span>
                        <div class="seperator">
                            Date :
                        </div>
                        <span class="seperator">
                            {{ (isset($oe->blood_report_date) && !empty($oe->blood_report_date)) ? $oe->blood_report_date : '-' }}
                        </span>
                    @endif
                </div>
            {{-- @endif --}}
            {{-- @if ($gSacNo == '') --}}
                <div class="row">
                    <div class="seperator">
                        Treact :
                    </div>
                    <span class="seperator">
                        @if (isset($oe->treact->type))
                            {{ ($oe->treact->type == 'medical') ? 'Medical' : 'Surgical' }}
                        @else
                            -
                        @endif
                    </span>
                    @if (isset($oe->treact->type))
                        @if ($oe->treact->type == 'medical')
                            <div class="seperator">
                                Medicine Details :
                            </div>
                            <span class="seperator">
                                @if (isset($oe->treact->medicine_details))
                                    {{ !empty($oe->treact->medicine_details) ? $oe->treact->medicine_details : '-' }}
                                @else
                                    -
                                @endif
                            </span>
                            <div class="seperator">
                                Medicine Dose:
                            </div>
                            <span class="seperator">
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
                            </span>
                        @endif

                        @if ($oe->treact->type == 'surgical')
                            <div class="seperator">
                                Operation Details :
                            </div>
                            <span class="seperator">
                                @if (isset($oe->treact->surgical_details))
                                    {{ !empty($oe->treact->surgical_details) ? $oe->treact->surgical_details : '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        @endif
                    @endif
                </div>
            {{-- @endif --}}
            {{-- @if ($gSacNo == '') --}}
            {{-- @endif --}}
            <div class="row">
                <div class="seperator">
                    Ovary:
                </div>
                @if (isset($oe->ovary->ovary_type))
                    @foreach($oe->ovary->ovary_type as $key => $value)
                        @if ($value == 'left')
                            <span class="seperator">
                                Left
                            </span>
                            <span class="seperator">
                                @if (!empty($oe->ovary->left->type))
                                    {{ ($oe->ovary->left->type == '1') ? 'Normal' : 'Abnormal' }}
                                @endif
                            </span>
                            @if ($oe->ovary->left->type == '2')
                                <span class="seperator" colspan="6">
                                    @if (!empty($oe->ovary->left->updated_details))
                                        @foreach ($oe->ovary->left->updated_details as $key => $value)
                                            @php
                                                echo !empty($value) ? $value .  '<br />' : '- <br />';
                                            @endphp
                                        @endforeach
                                    @endif
                                </span>
                            @endif
                        @endif

                        @if ($value == 'right')
                            <tr>
                                <span class="seperator">
                                </span>
                                <span class="seperator">
                                    Right
                                </span>
                                <span class="seperator">
                                    @if (!empty($oe->ovary->right->type))
                                        {{ ($oe->ovary->right->type == '1') ? 'Normal' : 'Abnormal' }}
                                    @endif
                                </span>

                                @if ($oe->ovary->right->type == '2')
                                    <span class="seperator" colspan="6">
                                        @if (!empty($oe->ovary->right->updated_details))
                                            @foreach ($oe->ovary->right->updated_details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '-' . '<br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                    </span>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @else
                    <span class="seperator" colspan="6">
                        -
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="seperator">Remark</div>
                <span class="seperator" colspan="8">
                    @if (isset($patientsObstratics->remark))
                        {{ !empty($patientsObstratics->remark) ? $patientsObstratics->remark : '-' }}
                    @else
                        -
                    @endif

                </span>
            </div>
            @if(!empty($treatment))
                <div class="row">
                    <span colspan="6">
                        <div class="panel-title header-print-title">2. Treatment </div>
                    </span>
                </div>
                @php
                    $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                    $old_medicine_time = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    unset($treatment->medicinedata);
                @endphp
                @foreach($treatment as $key=>$row)
                    <div class="row">
                        <div class="seperator">
                            Medicine :
                        </div>
                        <span class="seperator">
                            {{ $row->medicine }}
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
                            @if (!empty($row->dose))
                                @if (array_key_exists($row->dose, $old_dose))
                                    | {{ $old_dose[$row->dose] }}
                                @endif
                            @endif
                            @if (!empty($row->no)) | Days : {{ $row->no }} @endif
                            @if (!empty($row->quantity)) | Quantity : {{ $row->quantity }} @endif
                            @if (!empty($row->medicine_time))
                            |
                                    @foreach ($row->medicine_time as $time)
                                        {{$old_medicine_time[$time]}}
                                    @endforeach
                                {{-- @if (array_key_exists($row->medicine_time, $old_medicine_time))
                                    | {{ $old_medicine_time[$row->medicine_time] }}
                                @endif --}}
                            @endif
                        </span>
                    </div>
                @endforeach
            @endif
        @else
            <!-- H/O -->
            <div class="row">
                @if(!empty($remark->remark))
                    <div class="text-danger">
                        <span class="anc-label ">O/E Remark :</span>
                        {{$remark->remark}}
                    </div>
                @endif
                @if(!empty($blood->investigation_blood_group) && substr ($blood->investigation_blood_group, -3) == '-VE')
                    <div class="text-danger">
                        <span class="anc-label ">Blood Group :</span>
                        {{$blood->investigation_blood_group}}
                    </div>
                @endif
                @if(!empty($blood->anc_hiv) && $blood->anc_hiv == 'positive')
                    <div class="text-danger">
                        <span class="anc-label ">HIV :</span>
                        {{$blood->anc_hiv}}
                    </div>
                @endif
                @if(!empty($blood->anc_hbsag) && $blood->anc_hbsag == 'positive')
                    <div class="text-danger">
                        <span class="anc-label ">HBSAG:</span>
                        {{$blood->anc_hbsag}}
                    </div>
                @endif
                @if(!empty($blood->anc_vdrl) && $blood->anc_vdrl == 'positive')
                    <div class="text-danger">
                        <span class="anc-label ">VDRL:</span>
                        {{$blood->anc_vdrl}}
                    </div>
                @endif
                @if(!empty($blood->investigation_growth_fbs) && $blood->investigation_growth_fbs > 100)
                    <div class="text-danger">
                        <span class="anc-label ">FBS:</span>
                        {{$blood->investigation_growth_fbs}}
                    </div>
                @endif
                @if(!empty($blood->investigation_growth_pp2bs) && $blood->investigation_growth_pp2bs > 150)
                    <div class="text-danger">
                        <span class="anc-label ">PP2BS:</span>
                        {{$blood->investigation_growth_pp2bs}}
                    </div>
                @endif
            </div>
            {{-- ho tab --}}
            @if($ho)
                <div class="main-tab">
                    <div class="row">
                        <span colspan="6">
                            <div class="panel-title header-print-title">{{$hoNo}}. H/O</div>
                        </span>
                    </div>
                    <div class="row">
                        <div class="w-250 seperator">
                            <span class="anc-label ">H/O : </span>
                            {{!empty($ho->ho_details) ? $ho->ho_details : '-' }}
                        </div>
                        @if (!empty($ho->ho_type))
                            <div class="seperator">
                                <span class="anc-label ">Conceived By :</span>
                                @if ($ho->ho_type == '1')
                                    {{ 'Naturally' }}
                                @elseif ($ho->ho_type == '2')
                                    {{ 'Medicine' }}
                                @elseif ($ho->ho_type == '3')
                                    {{ 'IUI' }}
                                @elseif ($ho->ho_type == '4')
                                    {{ 'IVF' }}
                                @endif
                            </div>
                        @endif
                        @if (!empty($ho->ho_type) && $ho->ho_type != 1 && !empty($ho->when_where))
                            <div class="seperator">
                                <span class="anc-label ">When / Where :</span>
                                {{ !empty($ho->when_where) ? $ho->when_where : '-'}}
                            </div>
                        @endif
                        <span class="{{$weightClass}}">
                            <span class="anc-label ">Weight :</span>
                            {{ $ancData->getPatients->weight }}
                        </span>
                    </div>
                </div>
                <br>
            @endif

            {{-- co tab --}}
            @if(!empty($co) && !empty($co->co_type) || !empty($co->since))
                <div class="row">
                    <span colspan="6">
                        <div class="panel-title header-print-title">{{$coNo}}. C/O</div>
                    </span>
                </div>
                @if(!empty($co->co_type) || !empty($co->since))
                    <div class="row">
                        @if(isset($co->co_type) && is_array($co->co_type))
                            <div class="seperator w-500">
                                <span class="anc-label">C/O :</span>
                                {{ (isset($co->co_type) && is_array($co->co_type)) ? implode(', ', $co->co_type) : '-' }}
                            </div>
                        @endif
                        @if(!empty($co->since))
                            <div class="seperator">
                                <span class="anc-label">Since :</span>
                                {{ !empty($co->since) ? $co->since : '-' }}
                            </div>
                        @endif
                    </div>
                @endif
                <br>
            @endif

            {{-- obstratics history --}}
            @if($patientsObstratics && (!empty($patientsObstratics->marriage_life)  || !empty($patientsObstratics->child_no) || !empty($patientsObstratics->mtp_no) || !empty($patientsObstratics->abortion_no) || (!empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes')))
                <div class="row">
                    <span colspan="6">
                        <div class="panel-title header-print-title">4. Obstetric History</div>
                    </span>
                </div>
                @if(!empty($patientsObstratics->marriage_life) || !empty($patientsObstratics->upt_type))
                    <div class="row">
                        @if(!empty($patientsObstratics->marriage_life))
                            <div class="seperator">
                                <span class="anc-label ">Marriage Life :</span>
                                {{$patientsObstratics->marriage_life}}
                            </div>
                        @endif
                        @if(!empty($patientsObstratics->upt_type))
                            <div class="seperator">
                                <span class="anc-label">UTP :</span>
                                {{$patientsObstratics->upt_type  == 'positive' ? 'Positive' : 'Negative'}}
                            </div>
                        @endif
                    </div>
                @endif
                @if(!empty($patientsObstratics) && ($patientsObstratics->child_no != null && $patientsObstratics->child_no != 0))
                    @foreach($patientsObstratics->child->child_data as $key=>$row)
                        <div class="row">
                            <div class="seperator">
                                <span class="anc-label ">H/O :</span>
                                @php
                                    $hoValue = null;
                                    if(!empty($row->ho_term)){
                                        $hoValue.= $row->ho_term  == 'full' ? 'FT' : 'PT';
                                    }
                                    if(!empty($row->ho_type)){
                                        if($row->ho_type == 'normal'){
                                            $hoValue.= 'ND';
                                        }elseif($row->ho_type == 'cesarean'){
                                            $hoValue.= 'LSCS';
                                        }elseif ($row->ho_type == 'instrumental'){
                                            $hoValue.= 'Instrumental Delivery';
                                        }
                                    }
                                    if(!empty($row->ho_gender)){
                                        $hoValue.= $row->ho_gender == 'female' ? ' F' : ' M';
                                    }
                                    if(!empty($row->ho_birth_type)){
                                        if($row->ho_birth_type == 'live_health'){
                                            $hoValue.= '/L';
                                        }
                                        if($row->ho_birth_type == 'stil_birth'){
                                            $hoValue.= '/StilBirth';
                                        }
                                        if($row->ho_birth_type == 'expired'){
                                            $hoValue.= '/E';
                                            if($row->expired_reason){
                                                $hoValue.= '('.$row->expired_reason.')';
                                            }
                                        }
                                    }
                                    if(!empty($row->live_health_year)){
                                        $hoValue.= '-'.$row->live_health_year;
                                    }
                                    if(!empty($row->ho_type) && $patientsObstratics->child_no != 0 ){
                                        $ho_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
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
                                @endphp
                                {{$hoValue}}
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(!empty($patientsObstratics)  && $patientsObstratics->mtp_no != null && $patientsObstratics->mtp_no != 0 )
                    @foreach($patientsObstratics->mtp->mtp_data as $key=>$row)
                        @if($row->mtp_status == 'yes')
                            <div class="row">
                                <div class="seperator">
                                    <span class="anc-label ">MTP :</span>
                                    {{ !empty($row->mtp_status) && $row->mtp_status == 'yes' ? 'Yes' : 'No' }}
                                </div>
                                @php
                                    $mtpStatus = 'd-none';
                                    if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                        $mtpStatus = '';
                                    }
                                @endphp
                                @if (!empty($row->mtp_status) && $row->mtp_status == 'yes' && !empty($row->mtp_type))
                                    <div class="seperator">
                                        <span class="anc-label ">MTP Type : </span>
                                        @php
                                            if(!empty($row->mtp_type)) {
                                                echo ($row->mtp_type == 'medically') ? 'Medically' : 'Surgically';
                                            }
                                        @endphp
                                    </div>
                                    @if(!empty($row->mtp_month_of_pregancy))
                                        <div class="seperator">
                                            <span class="anc-label ">Month Of Pregnancy : </span>
                                            {{$row->mtp_month_of_pregancy}}
                                        </div>
                                    @endif
                                @endif
                                @php
                                    $ho_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
                                @endphp
                                @if(!empty($patientsObstratics->mtp_no) && $patientsObstratics->mtp_no != 0 )
                                    <div class="seperator">
                                        <span class="anc-label ">MTP HO Type :</span>
                                        @if (array_key_exists($row->ho_type, $ho_type_array))
                                            {{ $ho_type_array[$row->ho_type] }}
                                        @endif
                                    </div>
                                @endif
                                @php
                                    $hoTypeValue = [2,3,4];
                                    $dNone = '';
                                    if (!in_array($row->ho_type,$hoTypeValue)) {
                                        $dNone = 'd-none';
                                    }
                                @endphp
                                @if ($dNone == '' && !empty($row->when_where))
                                    <div class="seperator">
                                        <span class="anc-label ">When / Where :</span>
                                        {{!empty($row->when_where) ? $row->when_where : ''}}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
                @if(!empty($patientsObstratics->abortion_no) && !empty($patientsObstratics->abortion_no)|| !empty($patientsObstratics->abortion_no) || !empty($patientsObstratics->abortion->when_where))
                    <div class="row">
                        @if(!empty($patientsObstratics->abortion_no))
                            <div class="seperator">
                                Abortion :
                            </div>
                        @endif
                    </div>
                @endif
                @if(!empty($patientsObstratics) && ($patientsObstratics->abortion_no != null && $patientsObstratics->abortion_no != 0 ))
                    @foreach($patientsObstratics->abortion->abortion_data as $key=>$value)
                        @php
                            $numberKey = addOrdinalNumberSuffix($key);
                            $firstAbortionData = $numberKey;
                        @endphp
                        <div class="row">
                            @php
                                $isBracket = 0;
                                if(!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes') {
                                    $firstAbortionData .= ' spontancous abortion';
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
                                $abortion_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
                                if(!empty($value->ho_type) && !empty($patientsObstratics->abortion_no) && $patientsObstratics->abortion_no != 0 ){
                                    if (array_key_exists($value->ho_type, $abortion_type_array)){
                                        $firstAbortionData .= ' OR '.$abortion_type_array[$value->ho_type];
                                    }
                                    $hoTypeValue = [2,3,4];
                                    $dNone = '';
                                    if(!empty($value->ho_type) && !in_array($value->ho_type,$hoType)){
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
                            <span class="seperator">
                                {{$firstAbortionData}}
                            </span>
                        </div>
                    @endforeach
                @endif
                @if (isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes')
                    <div class="row">
                        <div class="seperator w-300">
                            <span class="anc-label">Second Merriage Life :</span>
                            @if (isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life))
                                {{ $patientsObstratics->second_marriage_life == 'yes' ? 'Yes' : 'No' }}
                            @else
                                -
                            @endif
                        </div>
                        @if (isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics->second_marriage_details))
                            <div class="seperator">
                                <span class="anc-label ">Second Merriage Details :</span>
                                {{$patientsObstratics->second_marriage_details}}
                            </div>
                        @endif
                    </div>
                @endif
                @if (isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics->second_marriage->child_no))
                    <div class="row">
                        @if(!empty($patientsObstratics->second_marriage->child_no) )
                            <div class="seperator">
                                <span class="anc-label ">Child No : </span>
                                {{$patientsObstratics->second_marriage->child_no}}
                            </div>
                        @endif
                    </div>
                @endif
                @if(isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics) && $patientsObstratics->second_marriage->child_no != null && $patientsObstratics->second_marriage->child_no != 0)
                    @foreach($patientsObstratics->second_marriage->child->child_data as $key=>$row)
                        <div class="row">
                            <div class="seperator">
                                <span class="anc-label ">H/O :</span>
                                @php
                                    $secondHoValue = null;
                                    if(!empty($row->ho_term)){
                                        $secondHoValue.= $row->ho_term  == 'full' ? 'FT' : 'PT';
                                    }
                                    if(!empty($row->ho_type)){
                                        if($row->ho_type == 'normal'){
                                            $secondHoValue.= 'ND';
                                        }elseif($row->ho_type == 'cesarean'){
                                            $secondHoValue.= 'LSCS';
                                        }elseif ($row->ho_type == 'instrumental'){
                                            $secondHoValue.= 'Instrumental Delivery';
                                        }
                                    }
                                    if(!empty($row->ho_gender)){
                                        $secondHoValue.= $row->ho_gender == 'female' ? ' F' : ' M';
                                    }
                                    if(!empty($row->ho_birth_type)){
                                        if($row->ho_birth_type == 'live_health'){
                                            $secondHoValue.= '/L';
                                        }
                                        if($row->ho_birth_type == 'stil_birth'){
                                            $secondHoValue.= '/StilBirth';
                                        }
                                        if($row->ho_birth_type == 'expired'){
                                            $secondHoValue.= '/E';
                                            if($row->expired_reason){
                                                $secondHoValue.= '('.$row->expired_reason.')';
                                            }
                                        }
                                    }
                                    if(!empty($row->live_health_year)){
                                        $secondHoValue.= '-'.$row->live_health_year;
                                    }
                                    if(!empty($row->ho_type) && $patientsObstratics->second_marriage->child_no != 0 ){
                                        $ho_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
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
                                    }
                                @endphp
                                {{$secondHoValue}}
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics && !empty($patientsObstratics->second_marriage->mtp_no)))
                    <div class="row">
                        @if(!empty($patientsObstratics->second_marriage->mtp_no))
                            <div class="seperator">
                                <span class="anc-label ">MTP : </span>{{$patientsObstratics->second_marriage->mtp_no}}
                            </div>
                        @endif
                    </div>
                @endif
                @if(isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics)  && $patientsObstratics->second_marriage->mtp_no != null && $patientsObstratics->second_marriage->mtp_no != 0)
                    @foreach($patientsObstratics->second_marriage->mtp->mtp_data as $key=>$row)
                        @if($row->mtp_status == 'yes')
                            <div class="row">
                                <div class="seperator">
                                    <span class="anc-label ">MTP :</span>
                                    {{ !empty($row->mtp_status) && $row->mtp_status == 'yes' ? 'Yes' : 'No' }}
                                </div>
                                @php
                                    $mtpStatus = 'd-none';
                                    if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                        $mtpStatus = '';
                                    }
                                @endphp
                                @if (!empty($row->mtp_status) && $row->mtp_status == 'yes' && !empty($row->mtp_type))
                                    <div class="seperator">
                                        <span class="anc-label ">MTP Type : </span>
                                        @php
                                            if(!empty($row->mtp_type)) {
                                                echo ($row->mtp_type == 'medically') ? 'Medically' : 'Surgically';
                                            }
                                        @endphp
                                    </div>
                                    @if(!empty($row->mtp_month_of_pregancy))
                                        <div class="seperator">
                                            <span class="anc-label ">Month Of Pregnancy : </span>
                                            {{$row->mtp_month_of_pregancy}}
                                        </div>
                                    @endif
                                @endif
                                @php
                                    $ho_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
                                @endphp
                                @if(!empty($patientsObstratics->second_marriage->mtp_no) && $patientsObstratics->second_marriage->mtp_no != 0 )
                                    <div class="seperator">
                                        <span class="anc-label ">MTP HO Type :</span>
                                        @if (array_key_exists($row->ho_type, $ho_type_array))
                                            {{ $ho_type_array[$row->ho_type] }}
                                        @endif
                                    </div>
                                @endif
                                @php
                                    $hoTypeValue = [2,3,4];
                                    $dNone = '';
                                    if (!in_array($row->ho_type,$hoTypeValue)) {
                                        $dNone = 'd-none';
                                    }
                                @endphp
                                @if ($dNone == '' && !empty($row->when_where))
                                    <div class="seperator">
                                        <span class="anc-label ">When / Where :</span>
                                        {{!empty($row->when_where) ? $row->when_where : ''}}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
                @if(isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics) && !empty($patientsObstratics->second_marriage->abortion_no))
                    <div class="row">
                        <div class="seperator">
                           Abortion :
                        </div>
                        @php
                            $abortion_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
                        @endphp
                    </div>
                @endif
                @if(isset($patientsObstratics->second_marriage_life) && !empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' && !empty($patientsObstratics) && $patientsObstratics->second_marriage->abortion_no != null && $patientsObstratics->second_marriage->abortion_no != 0 )
                    @foreach($patientsObstratics->second_marriage->abortion->abortion_data as $key=>$value)
                        @if($value->spontancous_abortion_status == 'yes')
                            <div class="row">
                                @php
                                    $numberKey = addOrdinalNumberSuffix($key);
                                    $secondAbortionData = $numberKey;

                                    $isBracket = 0;
                                    if(!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes') {
                                        $secondAbortionData .= ' spontancous abortion';
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
                                    $abortion_type_array = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'];
                                    if(!empty($value->ho_type) && !empty($patientsObstratics->second_marriage->abortion_no) && $patientsObstratics->second_marriage->abortion_no != 0 ){
                                        if (array_key_exists($value->ho_type, $abortion_type_array)){
                                            $secondAbortionData .= ' OR '.$abortion_type_array[$value->ho_type];
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
                                <span>
                                    {{$secondAbortionData}}
                                </span>
                            </div>
                        @endif
                    @endforeach
                @endif
                @if(!empty($patientsObstratics->remark))
                    <div class="row">
                        <div>
                            <span class="anc-label ">Remark :</span>
                            {{$patientsObstratics->remark}}
                        </div>
                    </div>
                @endif
                <br>
            @endif

            {{-- M/H --}}
            @if($mh)
                <div class="row">
                    <span colspan="6">
                        <div class="panel-title header-print-title">5. M/H</div>
                    </span>
                </div>
                @if(!empty($mh->type_and_year_of_infertility) || !empty($mh->age_of_menarchy) || !empty($mh->since_year))
                    <div class="row">
                        @if (!empty($mh->type_and_year_of_infertility))
                            <div class="seperator">
                                <span class="anc-label ">Type And Year Of Infertility :</span>
                                @if($mh->type_and_year_of_infertility == "primary")Primary @else Secondary @endif
                            </div>
                        @endif

                        @if (!empty($mh->age_of_menarchy))
                            <div class="seperator">
                                <span class="anc-label ">Age Of Menarchy : </span>
                                {{ $mh->age_of_menarchy }}
                            </div>
                        @endif

                        @if (!empty($mh->since_year))
                            <div class="seperator">
                                <span class="anc-label ">Since Year :</span>
                                {{ $mh->since_year }}
                            </div>
                        @endif
                    </div>
                @endif
                <div class="row">
                    <div class="seperator">
                        <span class="anc-label">
                            @if(!empty($mh->same_past) && $mh->same_past == 'same')
                                Present / Past M/H :
                            @else
                                Past M/H :
                            @endif
                        </span>
                        {{ ucwords($mh->past_mh_1) }}
                        | @if ($mh->past_mh_2 == 'regular')Regular @else IR Regular @endif
                        @if (!empty($mh->past_duration_of_day) || $mh->past_mh_2 == 'regular')
                        | Duration Of Day : {{$mh->past_mh_2 == 'regular' ? '28 - 30 day' : $mh->past_duration_of_day}}
                        @endif
                        @if (!empty($mh->past_interval_of_day) || $mh->past_mh_2 == 'regular')
                        | Interval Of Day : {{$mh->past_mh_2 == 'regular' ? '3 - 4 day' : $mh->past_interval_of_day}}
                        @endif
                        @if($mh->past_mh_2 != 'regular')
                        | {{  $mh->past_month == 'month' ? 'Month' : 'Day'}}
                        @else
                            RMPL
                        @endif
                    </div>
                </div>
                @if(!empty($mh->same_past) && $mh->same_past == 'exit')
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">Present M/H : </span>
                            {{ ucwords($mh->present_mh_1) }}
                            | @if ($mh->present_mh_2 == 'regular')Regular @else IR Regular @endif
                            @if (!empty($mh->present_duration_of_day) || $mh->present_mh_2 == 'regular')
                            | Duration Of Day : {{$mh->present_mh_2 == 'regular' ? '28 - 30 day' : $mh->present_duration_of_day}}
                            @endif
                            @if (!empty($mh->present_interval_of_day) || $mh->present_mh_2 == 'regular')
                            | Interval Of Day : {{$mh->present_mh_2 == 'regular' ? '3 - 4 day' : $mh->present_interval_of_day}}
                            @endif
                            @if(!empty($mh->present_mh_2) && $mh->present_mh_2 != 'regular' && !empty($mh->present_month))
                                | {{$mh->present_month == 'month' ? 'Month' : 'Day'}}
                            @else
                                RMPL
                            @endif
                        </div>
                    </div>
                @endif
                @php
                    $lmddate = !empty($mh->last_menstrual_date) ? \Carbon\Carbon::parse($mh->last_menstrual_date)->format('D d M Y') : null;
                    $date = !empty($mh->edd) ? \Carbon\Carbon::parse($mh->edd)->format('D d M Y') : null;
                    $usgDate = !empty($mh->usg_edd) ? \Carbon\Carbon::parse($mh->usg_edd)->format('D d M Y') : null;
                @endphp
                @if($lmddate || $date)
                    <div class="row">
                        <span class="seperator" colspan="6">
                            @if($lmddate)
                                <span class="anc-label lmd-lable">Last Menstrual Date : </span><span class="text-danger">{{$lmddate}}</span>
                            @endif
                            @if($date)
                                | EDD : <span class="text-danger">{{$date}}</span>
                            @endif
                            @if($date)
                                | USG EDD : <span class="text-danger">{{$usgDate}}</span>
                            @endif
                            @if (!empty($mh->since_month))
                                | Since Month : {{$mh->since_month}}
                            @endif
                            @if (!empty($mh->since_cycle))
                                | Since Cycle : {{$mh->since_cycle}}
                            @endif
                        </span>
                    </div>
                @endif
                <br>
            @endif

            {{-- Patients Details H/O --}}
            @php
                $pHistoryStatus = false;
                $pastHistoryStatus = false;
                $pfHistoryStatus = false;
                if(!empty($patientsDetails->personal_history_history_type)){
                    $pHistoryData = strtolower(implode(',',$patientsDetails->personal_history_history_type));
                    $pHistoryStatus = true;
                    if(strpos($pHistoryData, 'nad') !== false) {
                        $pHistoryStatus = false;
                    }
                }
                if(!empty($patientsDetails->family_history)){
                    $pfHistoryData = strtolower(implode(',',$patientsDetails->family_history));
                    $pfHistoryStatus = true;
                    if(strpos($pfHistoryData, 'nad') !== false) {
                        $pfHistoryStatus = false;
                    }
                }
                if(!empty($patientsDetails->past_history_type)){
                    $pastHistoryData = strtolower(implode(',',$patientsDetails->past_history_type));
                    $pastHistoryStatus = true;
                    if(strpos($pastHistoryData, 'nad') !== false) {
                        $pastHistoryStatus = false;
                    }
                }
            @endphp
            <!-- Patients Detailes --}}  -->
            @if($patientsDetails && ($pastHistoryStatus == true  || $pfHistoryStatus == true || $pHistoryStatus == true))
                <div class="row">
                    <span colspan="6">
                        <div class="panel-title header-print-title">{{$patientsHoNo}}. Patients Detailed H/O</div>
                    </span>
                </div>
                @php
                    // $personal_history_type = ['1'=>'NAD','2'=>"Diabetes Mellitus",'3'=>"Thyroid",'4'=>"Heart Disease",'5'=>"Hypertension"];
                @endphp
                @if($pHistoryStatus == true)
                    <div class="row seperator">
                        <span>
                            <span class="anc-label">Personal History :</span>
                            {{implode(',',$patientsDetails->personal_history_history_type)}}
                        </span>
                    </div>
                @endif
                @if(!empty($patientsDetails->personal_history_date))
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label">Date :</span>
                            {{\Carbon\Carbon::parse($patientsDetails->personal_history_date)->format('D d M Y')}}
                        </div>
                    </div>
                @endif
                @if($pfHistoryStatus == true)
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">Family History :</span>
                            {{implode(',',$patientsDetails->family_history)}}
                        </div>
                    </div>
                @endif
                @php
                    // $personal_past_history_type = ['nad'=>'NAD','tuberculosis_bacillus'=>"Tuberculosis Bacillus",'hypertension'=>"Hypertension",'thyroid'=>"Thyroid",'dm'=>"DM",'appendectomy'=>'Appendectomy','laparoscopy'=>'Laparoscopy'];
                @endphp

                @if($pastHistoryStatus == true)
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">Past History :</span>
                            {{implode(',',$patientsDetails->past_history_type)}}
                        </div>
                    </div>
                @endif
                <br>
            @endif

            @if(!empty($oe))
                <div class="row">
                    <span colspan="8">
                        <div class="panel-title header-print-title">{{$oeNo}}. O/E
                            <span class="text-danger">{{!empty($oe->late_concept) && $oe->late_concept == 1 ? 'Late Conception' : null}}</span>
                            @if(!empty($lmdDate))
                                &nbsp&nbsp L.M.D Date:  <span class="text-danger">{{\Carbon\Carbon::parse($lmdDate)->format('d-m-Y') }}</span>
                            @endif
                            @if(!empty($eddDate))
                                &nbsp&nbsp EDD Date: <span class="text-danger">{{\Carbon\Carbon::parse($eddDate)->format('d-m-Y') }}</span>
                            @endif
                            @if(!empty($usgEddDate))
                                &nbsp&nbsp USG EDD Date: <span class="text-danger">{{\Carbon\Carbon::parse($usgEddDate)->format('d-m-Y') }}</span>
                            @endif
                        </div>
                    </span>
                </div>
                @if(!empty($oe->oe_type) || !empty($oe->oe_no) || !empty($oe->oe_child_type))
                    <div class="row">
                        <div class="seperator">
                            @if(isset($oe->oe_type))
                                <span class="anc-label "> OE :</span>
                                {{ !empty($oe->oe_type) && $oe->oe_type == 'tvs' ? 'TVS' : 'PA'  }}
                            @endif
                            @php
                                $oe_number = ['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'];
                            @endphp
                            @if (!empty($oe->oe_no) && (in_array($oe->oe_no, ['1', '2'])))
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
                                @if (!empty($oe->oe_child_type) && array_key_exists($oe->oe_child_type, $childType))
                                    {{ ' | ' . $childType[$oe->oe_child_type] }}
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                @php
                    $gSacNo = 'd-none';
                @endphp
                @if(!empty($oe->late_data) && !empty($oe->late_data->late_concept) && $oe->late_data->late_concept == 'Yes')
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label "> Late Conception :</span>
                            {{!empty($oe->late_data->late_concept) ? $oe->late_data->late_concept : 'No'}}
                        </div>
                        @if(!empty($oe->late_data->late_concept_week))
                            <div class="seperator">
                                <span class="anc-label "> Late Conception Week:</span>
                                {{$oe->late_data->late_concept_week}}
                            </div>
                        @endif
                    </div>
                @endif

                @foreach($oe->utdata as $key=>$value)
                    @php
                        if($key == 1) {
                            $gSacNo = (isset($patientsObstratics->upt_type)) && ($patientsObstratics->upt_type == 'positive') &&  (((isset($value->oe_ut_sac) && strtolower($value->oe_ut_sac) == 'no') || (isset($value->oe_ut_sac_2)) &&  strtolower($value->oe_ut_sac_2) == 'no')) ? '' : 'd-none';
                        }
                    @endphp
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label">ut (wks)</span>
                            @if(!empty($value->female_type) && ($value->female_type == 'Ectopic' || $value->female_type == 'Molar Pregnancy'))
                                {{!empty($value->oe_ut_sac_1_value) ? $value->oe_ut_sac_1_value : null}}
                            @else
                                {{!empty($value->oe_ut_sac_1) ? $weekData[$value->oe_ut_sac_1] : null}}
                            @endif

                            @if (!empty($oe->oe_no) && ($oe->oe_no >= 3))
                                <span>
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
                                        {{ 'Child Type: ' . $childType[$value->child_type] }}
                                    @else
                                        {{ 'Child Type: -'}}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    @if((!empty($value->fcp) || !empty($value->liquor_type) || !empty($value->position_type) )&& !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] >= 14 || $value->oe_ut_sac_1 == 22))
                        <div class="row">
                            @if(!empty($value->fcp))
                                <div class="seperator">
                                    <span class="anc-label green-lable">FCP :</span>
                                    {{$value->fcp}}
                                </div>
                            @endif
                            @if (!empty($value->liquor_type))
                                <div class="seperator">
                                    <span class="anc-label green-lable">Liquor : </span>
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
                                </div>
                            @endif
                            @if (!empty($value->position_type) && !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] >= 30 || $value->oe_ut_sac_1 == 22))
                                <div class="seperator">
                                    <span class="anc-label green-lable">Position : </span>
                                    @switch($value->position_type)
                                        @case('nertex')
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
                                </div>
                            @endif
                        </div>
                    @endif
                    @if ($key == 1 && isset($oe->fefal_reduction))
                        <div class="row">
                            @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes')
                                <div class="seperator">
                                    <span class="anc-label ">Fetal Reduction :</span>
                                    {{ $oe->fefal_reduction->type == 'yes' ? 'Yes' : 'No' }}
                                </div>
                            @endif
                            @if (isset($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes' && !empty($oe->fefal_reduction->date))
                                <div class="seperator">
                                    <span class="anc-label ">Date :</span>
                                    {{$oe->fefal_reduction->date}}
                                </div>
                            @endif
                            @if (isset($oe->how_much) && !empty($oe->how_much->type))
                                @if (isset($oe->how_much->type))
                                    <div class="seperator">
                                        <span class="anc-label ">How Much :</span>
                                        {{$oe->how_much->type == 'yes' ? 'Yes' : 'No'}}
                                    </div>
                                @endif
                                @if (isset($oe->how_much->type) && $oe->how_much->type == 'yes'  && !empty($oe->fefal_reduction->how_much_value))
                                    <div class="seperator">
                                        <span class="anc-label "> How Much Value :</span>
                                        {{$oe->fefal_reduction->how_much_value}}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                    @if((!empty($value->yalk_sac) || !empty($value->fefal_pole)) && !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] < 14 && $value->oe_ut_sac_1 != 22))
                        <div class="row">
                            @if(!empty($value->yalk_sac))
                                <div class="seperator">
                                    <span class="anc-label "> Yolk Sac : </span>
                                    {{$value->yalk_sac}}
                                </div>
                            @endif
                            @if(!empty($value->fefal_pole))
                                <div class="seperator">
                                    <span class="anc-label ">Fefal Pole : </span>
                                    {{$value->fefal_pole}}
                                </div>
                            @endif
                        </div>
                    @endif
                    @if (!empty($value->placenta) || !empty($value->color_dropler) && !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] >= 30 || $value->oe_ut_sac_1 == 22))
                        <div class="row">
                            @if (isset($value->placenta) && !empty($value->placenta))
                                <div class="seperator">
                                    <span class="anc-label green-lable">Placenta</span>
                                    @php
                                        $placentaKeys = array_keys($placenta);
                                    @endphp

                                    @foreach ($value->placenta as $placentaData)
                                        @if (in_array($placentaData, $placentaKeys))
                                            @php
                                                echo $placenta[$placentaData] . '<br />';
                                            @endphp
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @if(!empty($value->color_dropler))
                                <div class="seperator" >
                                    <span class="anc-label ">Color Dropler</span>
                                    @if (isset($value->color_dropler))
                                        {{ !empty($value->color_dropler) ? $value->color_dropler : '-' }}
                                    @else
                                        -
                                    @endif
                                </div>
                            @endif
                        </div>
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
                @if($gSacNo == '')
                    <div class="row">
                        @if(!empty($oe->ec_topic))
                            <div class="seperator">
                                <span class="anc-label ">EC Topic :</span>
                                {{ isset($oe->ec_topic) && $oe->ec_topic == 'yes' ? 'Yes' : 'No'}}
                            </div>
                        @endif
                        @if((isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics)))
                            <div class="seperator">
                                <span class="anc-label ">Expert USG :</span>
                                {{'Yes'}}
                            </div>
                        @endif
                        @if(isset($oe->ec_topics) && in_array('expert_usg', $oe->ec_topics) || (!empty($oe->expert_usg)) || !empty($oe->expert_usg_date))
                            @if(!empty($oe->expert_usg))
                                <div class="seperator">
                                    <span class="anc-label ">Expert USG Details :</span>
                                    {{$oe->expert_usg}}
                                </div>
                            @endif
                            @if(!empty($oe->expert_usg_date))
                                <div class="seperator">
                                    <span class="anc-label ">Date :</span>
                                    {{$oe->expert_usg_date}}
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                @if($gSacNo == '' && (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics) || !empty($oe->blood_report)))
                    <div class="row">
                        @if(isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics))
                            <div class="seperator">
                                <span class="anc-label ">Blood Report :</span>
                                {{'Yes'}}
                            </div>
                        @endif
                        @if (isset($oe->ec_topics) && in_array('blood_report', $oe->ec_topics))
                            @if(!empty($oe->blood_report))
                                <div class="seperator">
                                    <span class="anc-label ">Blood Report Details :</span>
                                    {{$oe->blood_report}}
                                </div>
                            @endif
                            @if(!empty($oe->blood_report_date))
                                <div class="seperator">
                                    <span class="anc-label ">Date :</span>
                                    {{$oe->blood_report_date}}
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                @if($gSacNo == '')
                    <div class="row">
                        @if (isset($oe->treact->type))
                            <div class="seperator">
                                <span class="anc-label ">Treact :</span>
                                {{ ($oe->treact->type == 'medical') ? 'Medical' : 'Surgical' }}
                            </div>
                        @endif
                        @if (isset($oe->treact->type))
                            @if($oe->treact->type == 'medical')
                                @if(!empty($oe->treact->medicine_details))
                                    <div class="seperator">
                                        <span class="anc-label ">Medicine Details :</span>
                                        {{$oe->treact->medicine_details}}
                                    </div>
                                @endif
                                <span class="seperator">
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
                                </span>
                            @endif

                            @if ($oe->treact->type == 'surgical' && !empty($oe->treact->surgical_details))
                                <div class="seperator">
                                    <span class="anc-label "> Operation Details :</span>
                                    {{$oe->treact->surgical_details}}
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                @if($gSacNo == '')
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label "> Follow-up Date :</span>
                            {{ isset($oe->follow_up) && !empty($oe->follow_up) ? $oe->follow_up : '-' }}
                        </div>
                    </div>
                @endif
                @if(!empty($oe->p_s->type) && $oe->p_s->type == 'yes')
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">P/S : </span>
                            {{$oe->p_s->type == 'yes' ? 'Yes' : 'No'}}
                            @if($oe->p_s->type == 'yes')
                                {{!empty($oe->p_s->details) ? '| '.$oe->p_s->details : null}}
                            @endif
                        </div>
                    </div>
                @endif
                @if(!empty($oe->p_v->type) && $oe->p_v->type == 'yes')
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">P/V : </span>
                            {{$oe->p_v->type == 'yes' ? 'Yes' : 'No'}}
                            @if($oe->p_v->type == 'yes')
                                {{!empty($oe->p_v->details) ? '| '.$oe->p_v->details : ''}}
                            @endif
                        </div>
                    </div>
                @endif
                @if(!empty($oe->le->bp) || !empty($oe->le->temp) || !empty($oe->le->pulse))
                    <div class="row">
                        <div class="seperator w-100">
                            Vitals
                        </div>
                        @if(!empty($oe->le->bp))
                            <div class="seperator">
                                <span class="anc-label ">B.P :</span>
                                {{$oe->le->bp}} MMHG
                            </div>
                        @endif
                        @if(!empty($oe->le->temp))
                            <div class="seperator">
                                <span class="anc-label ">Temp :</span>
                                {{$oe->le->temp}}
                            </div>
                        @endif
                        @if(!empty($oe->le->pulse))
                            <div class="seperator">
                                <span class="anc-label ">Pulse :</span>
                                {{$oe->le->pulse}} / Min
                            </div>
                        @endif
                    </div>
                @endif
                @if(!empty($oe->remark))
                    <div>
                        <div class="seperator">
                            <span class="anc-label">Remark</span>
                            {{$oe->remark}}
                        </div>
                    </div>
                @endif
                <br>
            @endif

            {{--Investigation  --}}
            @if($patientsInvestigation->early_scan_type == 'yes' || $patientsInvestigation->anc_profile_type == 'yes' || $patientsInvestigation->growth_report_type == 'yes' || !empty($patientsInvestigation->investigation_extra) || $patientsInvestigation->other_report_type == 'yes')
                <div class="row">
                    <span colspan="6">
                        <div class="panel-title header-print-title">{{$investigationNo}}. Investigation</div>
                    </span>
                </div>
                @if(empty($oe->is_lft) || (!empty($oe->is_lft) && $oe->is_lft == 0))
                    @if(!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes')
                        <div class="row">
                            <div class="seperator">
                                <span class="anc-label ">Early Scan Report :</span>
                                {{ !empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes' ? 'Yes' : 'No' }}
                                @if(!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes')
                                    | Date : &nbsp;
                                    {{!empty($patientsInvestigation->investigation_early_scan_date) ? \Carbon\Carbon::parse($patientsInvestigation->investigation_early_scan_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y')}}
                                    @if (!empty($patientsInvestigation->investigation_early_scan_hb))
                                    | HB : {{$patientsInvestigation->investigation_early_scan_hb}}
                                    @endif
                                    @if(!empty($patientsInvestigation->investigation_early_scan_hb_details))
                                    | HB Details : {{$patientsInvestigation->investigation_early_scan_hb_details}}
                                    @endif
                                    @if (!empty($patientsInvestigation->investigation_tsh))
                                    | TSH : {{$patientsInvestigation->investigation_tsh}}
                                    @endif
                                    @if(!empty($patientsInvestigation->investigation_tsh_details))
                                    | RX : {{$patientsInvestigation->investigation_tsh_details}}
                                    @endif
                                    @if(!empty($patientsInvestigation->investigation_rbs))
                                    | RBS : {{$patientsInvestigation->investigation_rbs}}
                                    @endif
                                    @if(!empty($patientsInvestigation->investigation_rbs_details))
                                    | RBS Details : {{$patientsInvestigation->investigation_rbs_details}}
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @if(!empty($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type == 'yes')
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">ANC Profile :</span>
                            @php
                                $wnlType = ['1'=>"WNL",'2'=>"Abnormal"];
                            @endphp
                            {{ isset($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type == 'yes' ? 'Yes' : 'No' }}
                            @if (isset($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type  == 'yes')
                                | Date :
                                {{!empty($patientsInvestigation->investigation_anc_date) ? \Carbon\Carbon::parse($patientsInvestigation->investigation_anc_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y')}}
                                @if (!empty($patientsInvestigation->investigation_cbc_mp->status))
                                    | CBC MP Type: {{$wnlType[$patientsInvestigation->investigation_cbc_mp->status]}}
                                    @if($patientsInvestigation->investigation_cbc_mp->status == 2)
                                        @if(!empty($patientsInvestigation->investigation_cbc_mp->aneamia))
                                            | Aneamia : {{$patientsInvestigation->investigation_cbc_mp->aneamia}}
                                        @endif
                                        @if(!empty($patientsInvestigation->investigation_cbc_mp->leacocytosis))
                                            | Leacocytosis : {{$patientsInvestigation->investigation_cbc_mp->leacocytosis}}
                                        @endif
                                    @endif
                                @endif
                        </div>
                        <div class="seperator">
                                @if (!empty($patientsInvestigation->investigation_urine->status))
                                    Urine Type: {{$wnlType[$patientsInvestigation->investigation_urine->status]}}
                                    @if($patientsInvestigation->investigation_urine->status == 2)
                                        @if(!empty($patientsInvestigation->investigation_urine->type))
                                            | Puccell  : {{$patientsInvestigation->investigation_urine->type}}
                                            @if($patientsInvestigation->investigation_urine->type == 'present')
                                                | Puscell Details : {{$patientsInvestigation->investigation_urine->puscell}}
                                            @endif
                                        @endif
                                        @if(!empty($patientsInvestigation->investigation_urine->urine_albumine))
                                            | Urine Albumine : {{$patientsInvestigation->investigation_urine->urine_albumine}}
                                        @endif
                                    @endif
                                @endif
                                @if (!empty($patientsInvestigation->investigation_blood_group))
                                | Blood Group : {{$patientsInvestigation->investigation_blood_group}}
                                @endif
                                @if (!empty($patientsInvestigation->investigation_anc_rbs))
                                | RBS : {{$patientsInvestigation->investigation_anc_rbs}}
                                @endif
                                @if (!empty($patientsInvestigation->anc_hiv))
                                | HIV : {{ $patientsInvestigation->anc_hiv == 'positive' ? 'Positive' : 'Nagative' }}
                                @endif
                                @if (!empty($patientsInvestigation->anc_hbsag))
                                | HBSAG : {{ $patientsInvestigation->anc_hbsag == 'positive' ? 'Positive' : 'Nagative' }}
                                @endif
                                @if (!empty($patientsInvestigation->anc_vdrl))
                                | VDRL : {{ $patientsInvestigation->anc_vdrl == 'positive' ? 'Positive' : 'Nagative' }}
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                @if(empty($oe->is_lft) || (!empty($oe->is_lft) && $oe->is_lft == 0))
                    @if(!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes')
                        <div class="row">
                            <div class="seperator">
                                <span class="anc-label ">Growth Report :</span>
                                {{ !empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes' ? 'Yes' : 'No' }}
                                @if (!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes')
                                    | Date :
                                    {{!empty($patientsInvestigation->investigation_growth_date) ? \Carbon\Carbon::parse($patientsInvestigation->investigation_growth_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y')}}
                                    @if (!empty($patientsInvestigation->investigation_growth_hb))
                                    | HB : {{$patientsInvestigation->investigation_growth_hb}}
                                    @endif
                                    @if (!empty($patientsInvestigation->investigation_growth_fbs))
                                    | FBS : {{$patientsInvestigation->investigation_growth_fbs}}
                                    @endif
                            </div>
                            <div class="seperator">
                                    @if (!empty($patientsInvestigation->investigation_growth_fbs_details))
                                        FBS Details : {{$patientsInvestigation->investigation_growth_fbs_details}}
                                    @endif
                                    @if (!empty($patientsInvestigation->investigation_growth_pp2bs))
                                    | PP2BS : {{$patientsInvestigation->investigation_growth_pp2bs}}
                                    @endif
                                    @if (!empty($patientsInvestigation->investigation_growth_pp2bs_details))
                                    | PP2BS Details: {{$patientsInvestigation->investigation_growth_pp2bs_details}}
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @if(!empty($oe->is_lft) && $oe->is_lft == 1)
                    @if(!empty($patientsInvestigation->anc_lft_normal_status))
                        <div class="row">
                            <div class="seperator">
                                <span class="anc-label ">LFT :</span>
                                {{$patientsInvestigation->anc_lft_normal_status == 1 ? 'Normal' : 'Abnormal'}}
                                @if(!empty($patientsInvestigation->anc_lft_normal_data))
                                    {{$patientsInvestigation->anc_lft_normal_data}}
                                @endif
                                @if(!empty($patientsInvestigation->anc_lft_ab_normal_data_bita_hcg))
                                    {{$patientsInvestigation->anc_lft_ab_normal_data_bita_hcg}}
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @if(!empty($patientsInvestigation->investigation_extra))
                    <div class="row">
                        <div class="seperator">
                            <span class="anc-label ">Extra :</span>
                            {{$patientsInvestigation->investigation_extra}}
                        </div>
                    </div>
                @endif
                @if(empty($oe->is_lft) || (!empty($oe->is_lft) && $oe->is_lft == 0))
                    @if(!empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes')
                        <div class="row">
                            <div class="seperator">
                                <span class="anc-label ">Other Report :</span>
                                @php
                                    $otherReport = !empty($patientsInvestigation->other_report) ? $patientsInvestigation->other_report : [];
                                @endphp
                                {{ !empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes' ? 'Yes' : 'No' }}
                                @if (!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->other_report_type == 'yes')
                                    | Double Marker :  @if (in_array('double_marker',$otherReport)) Yes @else No @endif
                                    | Double Marker Date: {{ !empty($patientsInvestigation->d_m_date) ? \Carbon\Carbon::parse($patientsInvestigation->d_m_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y') }}
                                    </div>
                                    <div class="seperator">
                                    | Genetic Test : @if (in_array('genetic_test', $otherReport)) Yes @else No @endif
                                    | Amniocentesis : @if (in_array('amniocentesis', $otherReport)) Yes @else No @endif
                                    | Amniocentesis Date: {{ !empty($patientsInvestigation->amniocentesis_date) ? \Carbon\Carbon::parse($patientsInvestigation->amniocentesis_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y') }}
                                    @if (!empty($patientsInvestigation->investigation_extra))
                                        Extra : {{ $patientsInvestigation->investigation_extra }}
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                <br>
            @endif

            {{-- injection --}}
            @php
                $patientsInjectionData = array_filter((array)$patientsInjection);
            @endphp
            @if($ancData->is_gynec == 0)
                @if(!empty($patientsInjectionData))
                    <div class="row">
                        <div colspan="6">
                            <div class="panel-title header-print-title">{{$injectionNo}}. Injection</div>
                        </div>
                    </div>
                    @if(!empty($patientsInjection->tt1) || !empty($patientsInjection->tt2) )
                        <div class="row">
                            @if(!empty($patientsInjection->tt1))
                                <div class="seperator">
                                    <span class="anc-label ">TT1 :</span>
                                    {{$patientsInjection->tt1}}
                                </div>
                            @endif

                            @if(!empty($patientsInjection->tt2))
                                <div class="seperator">
                                    <span class="anc-label ">TT2 :</span>
                                    {{$patientsInjection->tt2}}
                                </div>
                            @endif
                        </div>
                    @endif
                    @if(!empty($patientsInjection->betnasol_1) || !empty($patientsInjection->betnasol_2))
                        <div class="row">
                            @if(!empty($patientsInjection->betnasol_1))
                                <div class="seperator">
                                    <span class="anc-label ">Betnasol 1 :</span>
                                    {{$patientsInjection->betnasol_1}}
                                </div>
                            @endif
                            @if(!empty($patientsInjection->betnasol_2))
                                <div class="seperator">
                                    <span class="anc-label ">Betnasol 2 :</span>
                                    {{$patientsInjection->betnasol_2}}
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
                <br>
            @endif

            @if($ancData->is_gynec == 1 && !empty($oe->plan_medically_type->type))
                <div class="row">
                    <div>
                        <div class="panel-title header-print-title">{{$gynecPlan}}. Plan </div>
                    </div>
                </div>
                <div class="row">
                    <div class="seperator">
                        <span class="anc-label ">Plan Type :</span>
                        {{ucfirst($oe->plan_medically_type->type)}}
                    </div>
                </div>
                <br>
            @endif

            @php
                $usgData = array_filter((array)$usg);
                $ntScanDate = \Carbon\Carbon::parse($usg->nt_scan)->format('Y-m-d');
                $nowDate = \Carbon\Carbon::now()->format('Y-m-d');
            @endphp
            @if(!empty($usgData) && (!empty($usg->early_scan) || !empty($usg->early_scan) || !empty($usg->nt_scan && (strtotime($ntScanDate) > strtotime($nowDate))) || !empty($usg->anomalies_miles)))
                <div class="row">
                    <span>
                        <div class="panel-title header-print-title"> {{$usgNo}}. USG </div>
                    </span>
                </div>
                <div class="row">
                    @if (!empty($usg->early_scan))
                        <div class="seperator">
                            <span class="anc-label ">Early Scan :</span>
                            {{\Carbon\Carbon::parse($usg->early_scan)->format('D d M Y')}}
                        </div>
                    @endif
                    @if (!empty($usg->nt_scan) && (strtotime($ntScanDate) > strtotime($nowDate)))
                        <div class="seperator">
                            <span class="anc-label ">N.T Scan :</span>
                            {{\Carbon\Carbon::parse($usg->nt_scan)->format('D d M Y')}}
                        </div>
                    @endif
                </div>
                <div class="row">
                    @if (!empty($usg->anomalies_miles))
                        <div class="seperator">
                            <span class="anc-label ">Anomalies Miles :</span>
                            {{\Carbon\Carbon::parse($usg->anomalies_miles)->format('D d M Y')}}
                        </div>
                    @endif
                    @if (!empty($usg->growth_scan))
                        <div class="seperator">
                            <span class="anc-label ">Growth Scan :</span>
                            {{\Carbon\Carbon::parse($usg->growth_scan)->format('D d M Y')}}
                        </div>
                    @endif
                </div>
                <br>
            @endif

            @if(!empty($treatment))
                <div class="row">
                    <span>
                        <div class="panel-title header-print-title">{{$treatmentNo}}. Treatment (Medicine)</div>
                    </span>
                </div>
                @php
                    $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                    $old_medicine_time = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    unset($treatment->medicinedata);
                @endphp
                @foreach($treatment as $key=>$row)
                    <div class="row">
                        <span class="seperator">
                            {{ $row->medicine }}
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
                            @if (!empty($row->dose))
                                @if (array_key_exists($row->dose, $old_dose))
                                    | {{ $old_dose[$row->dose] }}
                                @endif
                            @endif
                            @if (!empty($row->no)) | Days : {{ $row->no }} @endif
                            @if (!empty($row->quantity)) | Quantity : {{ $row->quantity }} @endif
                            @if (!empty($row->medicine_time))
                            |
                                    @foreach ($row->medicine_time as $time)
                                        {{$old_medicine_time[$time]}}
                                    @endforeach
                                {{-- @if (array_key_exists($row->medicine_time, $old_medicine_time))
                                    | {{ $old_medicine_time[$row->medicine_time] }}
                                @endif --}}
                            @endif
                        </span>
                    </div>
                @endforeach
            @endif
            @if(!$ancHistory && isset($oe->follow_up))
                <div class="row">
                    <div class="col-md-1">
                        <span class="f-date">Follow up :</span>
                            <span class="col-md-2">
                                {{isset($oe->follow_up) && !empty($oe->follow_up) ? $oe->follow_up : '-' }}
                            </span>
                    </div>
                </div>
            @endif
            {{-- @if(!empty($usg->nt_scan) && !empty($oe->follow_up) && ($oe->follow_up == $usg->nt_scan)) --}}
            @if(!empty($usgStatus) && $usgStatus == 1)
                <h4>{{"Come to again for U.S.G on ".\Carbon\Carbon::parse($oe->follow_up)->format('d-m-Y')}}</h4>
            @endif
            @if($isNextAppointment == 1)
                <h4>{{"Come to again on ".\Carbon\Carbon::parse($nextAppointmentDate)->format('d-m-Y')}}</h4>
            @endif

        @endif

    </div>
</div>
