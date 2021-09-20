
@extends(isset($printPreview) && $printPreview == 1 ? 'layouts.printpreview' : 'layouts.printPreviewBlank')
{{-- <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" > --}}
@php
    use App\Models\IvfExtraVisit;

if(!isset($isExtraVisit) || $isExtraVisit == 0)
{
// echo $printPreview;
    $patientsInfo = !empty($ivf->patients_info) ? json_decode($ivf->patients_info) : null;
    $ho = !empty($ivf->h_o) ? json_decode($ivf->h_o) : null;
    $co = !empty($ivf->c_o) ? json_decode($ivf->c_o) : null;
    $oh = !empty($ivf->o_h) ? json_decode($ivf->o_h) : null;
    $mh = !empty($ivf->m_h) ? json_decode($ivf->m_h) : null;
    $hoRx = !empty($ivf->ho_rx) ? json_decode($ivf->ho_rx) : null;
    $investigation = !empty($ivf->investigation) ? json_decode($ivf->investigation) : null;
    $husbandFactor = !empty($ivf->husband_factor) ? json_decode($ivf->husband_factor) : null;
    $patientDetailedHO = !empty($ivf->patients_details_ho) ? json_decode($ivf->patients_details_ho) : null;
    $oe = !empty($ivf->o_e) ? json_decode($ivf->o_e) : null;
    $planManagement = !empty($ivf->plan_management) ? json_decode($ivf->plan_management) : null;
    // dd($planManagement);
    $possibleCaseOfInfertility = !empty($ivf->possible_case_of_infertility) ? json_decode($ivf->possible_case_of_infertility) : null;
    $treatment = !empty($ivf->treatment) ? json_decode($ivf->treatment) : null;
    $visit = $ivf->visit;
    $description = json_decode($ivf->description);
    $lmp = !empty($description->lmp) ? $description->lmp : null;
    $plan = !empty($description->plan) ? $description->plan : null;
    $ovary = !empty($description->ovary) ? $description->ovary : null;
    $hcg = !empty($description->hcg) ? $description->hcg : null;
    $todayDate = Carbon\Carbon::Now();
    if($isIvfHistory == '2'){
        $lmp = !empty($historyData->lmp) ? $historyData->lmp : null;
        $oe = !empty($historyData->oe) ? $historyData->oe : null;
        $etDetails = !empty($historyData->et_details) ? $historyData->et_details : null;
        $collectionData = !empty($historyData->collection) ? $historyData->collection : [];
        $injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'Clomiphene Citrate + HMG','8'=>'Clomiphene Citrate + FSH','9'=>'Antagonist'];
    }
    $dataa = !empty($historyData->collected) ? $historyData->collected : [];
    $plan=$ivf->plan;
    $cycle=$ivf->cycle_no;
}
    $contraceptionData = ['barrier_method'=>'Barrier Method','cu_t'=>'Cu - T','tl_done'=>'TL Done ','occipill'=>'Occipill','other_contraception'=>'Other'];
    $medqty = ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
    $old_dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    $typeOfData = [1=>'Primary',2=>'Secondary'];
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
    .main-print-ivf-div{
        margin: 0 auto;
        width: 100%;
    }
    @media print {
    {page-break-after: always;}
    }
    .ivf-print-data{
        text-align: left;
        width: 100%;
        margin: 0 auto;
    }
    .w-100{
        width: 100px !important;
    }
    .w-300{
        width: 300px !important;
    }
    .ivf-label{
        font-weight: normal;
    }
    .panel-primary{
        border: 1px solid;
        padding: 11px;
    }
    .p-main-title{
        font-size: 20px;
    }
    .transfer-print{
        margin-top: 0px !important;
    }
    .medicine-table td{
        padding: 4px 5px;
        text-transform: capitalize;
    }
    .medicine-table tr{
        text-align:center !important;
    }
    .pb-1{
        padding-bottom: 30px;
    }
    .w-50
    {
        width: 60%;
    }
    .panel-primary
    {
        border:none !important;
    }
    .follicular-table tbody, .follicular-table thead
    {
        background-color: #f3fbfa !important;
        -webkit-print-color-adjust: exact;
        font-size: 18px;
        color: black !important;
        font-weight: 600;
    }
    .follicular-table .visit-lable
    {   font-weight: bold;
        color: black !important;
        font-size: 20px;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
        -webkit-print-color-adjust: exact;
    }
    .follicular-table td,.follicular-table th
    {   
        padding: .2rem .2rem !important;
        text-align: center !important;
        border: 1px solid black !important;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        -webkit-print-color-adjust: exact;
        white-space: inherit !important;
        color: black !important;
    }
    .medicine-table td{
        padding: 2px 15px;
        text-transform: capitalize;
    }
    .visit-lable-value{
        font-size: 18px;
        font-weight: 600 !important;
    }
    .display
    {
        display: flex;
    }
    /* .visit-lable
    {
        font-size: 22px !important;
    } */
    /* @page { margin-top : 20px; margin-left : 100px;} */
</style>
@if(isset($printPreview) && $printPreview != 0)
    @section('content')
@endif    
<div class="main-print-ivf-div">
    @if((!isset($isTableView) || $isTableView == 0) && (!isset($isExtraVisit) || $isExtraVisit == 0))
        @if ($isIvfHistory == '1')
        <style>
            @page { margin-top : 200px; margin-bottom : 80px;}
        </style>
            <div class="{{'panel panel-primary '.(isset($printPreview) && $printPreview == 1 ? 'watermark' : '')}}">
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                    <tbody>
                        <tr>
                            <th>
                                <span class="pb-1 font-bold ivf-label">Name : {{ ucwords(strtolower($ivf->getPatientsInfoData->name)) . ' / ' . $patientsInfo->age. ' years' }}</span>
                            </th>
                            <th>
                            <th class="pb-1 float-right font-bold ivf-label">Visit Date:  {{Carbon\Carbon::parse($ivf->created_at)->format('d/m/Y')}}
                                @if($ivf->getPatientsInfoData->weight)
                                    <br>Weight: {{$ivf->getPatientsInfoData['weight'].' kg'}}
                                @endif
                            </th>
                        </tr>
                    </tbody>
                </table>
                @if($ho)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                        <tbody>
                                @if(!empty($ho->ho_details))
                                    <th class="w-250 ">
                                        <span class="ivf-label">H/O : </span>
                                        {{!empty($ho->ho_details) ? $ho->ho_details : '-' }}
                                    </th>
                                @endif
                                @if (!empty($ho->ho_type))
                                    <th>
                                        <span class="ivf-label">H/O Type :</span>
                                        @if ($ho->ho_type == '1')
                                            {{ 'Conceived Naturally' }}
                                        @elseif ($ho->ho_type == '2')
                                            {{ 'Conceived With Medicine' }}
                                        @elseif ($ho->ho_type == '3')
                                            {{ 'Conceived With IUI' }}
                                        @elseif ($ho->ho_type == '4')
                                            {{ 'Conceived With IVF' }}
                                        @endif
                                    </th>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                @endif
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                        <tbody>
                        @if(!empty($co) && !empty($co->co_type) || !empty($co->since))
                            <tr>
                                <th>
                                    <span class="ivf-label">C/O :</span>
                                    {{ (isset($co->co_type) && is_array($co->co_type)) ? implode(', ', $co->co_type) : 'None' }}
                                    @if(!empty($co->since))
                                        <span class="ivf-label">Since </span>
                                        {{ !empty($co->since) ? $co->since : '-' }}
                                    @endif
                                </th>
                            </tr>
                        @endif
                        @php
                            $noValueData = [];
                            $secondNoValueData = [];
                        @endphp
                        @if($oh)
                            <tr>
                                <td>
                                    <div class="panel-title header-print-title">Obstetric History</div>
                                </td>
                            </tr>
                            @php
                                $hoType = [2,3,4];
                            @endphp
                            @if(!empty($oh->first_marriage_life) || !empty($oh->upt_type) || !empty($oh->active_marriage_life) || !empty($oh->type_of_infertility))
                                <tr>
                                    @if(!empty($oh->first_marriage_life))
                                        <th>
                                            <span class="ivf-label">Marriage Life :</span>
                                            {{$oh->first_marriage_life}}
                                        </th>
                                    @endif
                                    @if(!empty($oh->active_marriage_life))
                                        <th>
                                            <span class="ivf-label">Active Marriage Life :</span>
                                            {{$oh->active_marriage_life}}
                                        </th>
                                    @endif
                                    @if(!empty($oh->upt_type))
                                        <th>
                                            <span class="ivf-label">UTP :</span>
                                            {{$oh->upt_type  == 'positive' ? 'Positive' : 'Negative'}}
                                        </th>
                                    @endif
                                    @if(!empty($oh->type_of_infertility))
                                        <th>
                                            <span class="ivf-label">Type Of Infertility :</span>
                                            @if(!empty($oh->type_of_infertility))
                                                @switch($oh->type_of_infertility)
                                                    @case(1)
                                                    Primary
                                                    @break
                                                    @case(2)
                                                    Secondary
                                                    @break
                                                    @default
                                                    -
                                                @endswitch
                                            @endif
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if(!empty($oh) && ($oh->child_no != null && $oh->child_no != 0))
                                @foreach($oh->child->child_data as $key=>$row)
                                    <tr>
                                        <th>
                                            <span class="ivf-label ">H/O :</span>
                                            @php
                                                $hoValue = null;
                                                $ho_term_details = null;
                                                if(!empty($row->ho_term)){
                                                    $hoValue.= $row->ho_term  == 'full' ? 'Full Term' : 'Pre Trem';
                                                }
                                                if(!empty($row->ho_type_value)){
                                                    if($row->ho_type_value == 'normal'){
                                                        $hoValue.= 'ND';
                                                    }elseif($row->ho_type_value == 'cesarean'){
                                                        $hoValue.= ' LSCS';
                                                    }elseif ($row->ho_type_value == 'instrumental'){
                                                        $hoValue.= ' Instrumental Delivery';
                                                    }
                                                }
                                                if(!empty($row->ho_gender)){
                                                    $hoValue.= $row->ho_gender == 'female' ? ' Female' : ' Male';
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
                                                    $hoValue.= '-'.$row->live_health_year;
                                                }
                                                if(!empty($row->expired_year)){
                                                    $hoValue.= '-'.$row->expired_year;
                                                }
                                                if(!empty($row->ho_type) && $oh->child_no != 0 ){
                                                    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                    if(array_key_exists($row->ho_type, $ho_type_array)){
                                                        $hoValue.= ' ('.$ho_type_array[$row->ho_type].')';
                                                        $hoType = [2,3,4];
                                                        $dNone = '';
                                                        if (!in_array($row->ho_type,$hoType)) {
                                                            $dNone = 'd-none';
                                                        }
                                                        if($oh->child_no != null && $oh->child_no != 0 && $dNone == '' && !empty($row->when_where)){
                                                            $hoValue.= ' '.$row->when_where;
                                                        }
                                                    }
                                                    $ho_term_details = isset($row->ho_term_details) && !empty($row->ho_term_details) ? ' - '.$row->ho_term_details : '';

                                                }
                                            @endphp
                                            {{$hoValue.$ho_term_details}}
                                        </th>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $noValueData[] = ' Child';
                                @endphp
                            @endif
                            @if(!empty($oh)  && $oh->mtp_no != null && $oh->mtp_no != 0 )
                                @foreach($oh->mtp->mtp_data as $key=>$row)
                                    @php
                                    $numberKey = addOrdinalNumberSuffix($key);
                                    $firstAbortionData = $numberKey;
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
                                            if(!empty($row->ho_type)){
                                                if (array_key_exists($row->ho_type, $abortion_type_array)){
                                                    $firstAbortionData .= ' AND '.$abortion_type_array[$row->ho_type];
                                                }
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if(!empty($row->ho_type) && !in_array($row->ho_type,$hoTypeValue)){
                                                    $dNone = 'd-none';
                                                }
                                                if($oh->mtp_no != null && $oh->mtp_no != 0 && $dNone == '' && !empty($row->when_where)){
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
                            @if(!empty($oh) && ($oh->abortion_no != null && $oh->abortion_no != 0 ))
                                @foreach($oh->abortion->abortion_data as $key=>$value)
                                    @php
                                        $numberKey = addOrdinalNumberSuffix($key);
                                        $firstAbortionData = $numberKey;
                                    @endphp
                                    <tr>
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
                                            $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                            if(!empty($value->ho_type) && !empty($oh->abortion_no) && $oh->abortion_no != 0 ){
                                                if (array_key_exists($value->ho_type, $abortion_type_array)){
                                                    $firstAbortionData .= ' AND '.$abortion_type_array[$value->ho_type];
                                                }
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if(!empty($value->ho_type) && !in_array($value->ho_type,$hoType)){
                                                    $dNone = 'd-none';
                                                }
                                                if($oh->abortion_no != null && $oh->abortion_no != 0 && $dNone == '' && !empty($value->when_where)){
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
                            @else
                                @php
                                    $noValueData[] = ' Abortion';
                                @endphp
                            @endif
                            @if(isset($oh) && !empty($oh->contraception) && !empty($oh->contraception->contraception_status) && $oh->contraception->contraception_status == 'yes' &&  !empty($oh->contraception->contraception_data))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Contraception Method :</span> {{$contraceptionData[$oh->contraception->contraception_data]}}
                                    </th>
                                </tr>
                            @else
                                @php
                                    $noValueData[] = ' Contraception';
                                @endphp
                            @endif
                            @if(!empty($noValueData))
                            <tr>
                                <th>
                                    {{'No '.implode(',',$noValueData)}}
                                </th>
                            </tr>
                            @endif
                            @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes')
                                <tr>
                                    <th class=" w-300">
                                        <span class="ivf-label">Second Merriage Life :</span>
                                        @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life))
                                            {{ $oh->second_marriage_life == 'yes' ? 'Yes' : 'No' }}
                                        @else
                                            -
                                        @endif
                                    </th>
                                    @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh->second_marriage_details))
                                        <th>
                                            <span class="ivf-label ">Second Merriage Details :</span>
                                            {{$oh->second_marriage_details}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh->second_marriage->child_no))
                                <tr>
                                    @if(!empty($oh->second_marriage->child_no) )
                                        <th>
                                            <span class="ivf-label ">Child No : </span>
                                            {{$oh->second_marriage->child_no}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh) && $oh->second_marriage->child_no != null && $oh->second_marriage->child_no != 0)
                                @foreach($oh->second_marriage->child->child_data as $key=>$row)
                                    <tr>
                                        <th>
                                            <span class="ivf-label ">H/O :</span>
                                            @php
                                                $secondHoValue = null;
                                                $second_ho_term_details = null;
                                                if(!empty($row->ho_term)){
                                                    $secondHoValue.= $row->ho_term  == 'full' ? 'Full Term' : 'Pre Term';
                                                }
                                                if(!empty($row->ho_type_value)){
                                                    if($row->ho_type_value == 'normal'){
                                                        $secondHoValue.= 'ND';
                                                    }elseif($row->ho_type_value == 'cesarean'){
                                                        $secondHoValue.= ' LSCS';
                                                    }elseif ($row->ho_type_value == 'instrumental'){
                                                        $secondHoValue.= ' Instrumental Delivery';
                                                    }
                                                }
                                                if(!empty($row->ho_gender)){
                                                    $secondHoValue.= $row->ho_gender == 'female' ? ' Female' : ' Male';
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
                                                if(!empty($row->ho_type) && $oh->second_marriage->child_no != 0 ){
                                                    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                    if(array_key_exists($row->ho_type, $ho_type_array)){
                                                        $secondHoValue.= ' ('.$ho_type_array[$row->ho_type].')';
                                                        $hoType = [2,3,4];
                                                        $dNone = '';
                                                        if (!in_array($row->ho_type,$hoType)) {
                                                            $dNone = 'd-none';
                                                        }
                                                        if($oh->second_marriage->child_no != null && $oh->second_marriage->child_no != 0 && $dNone == '' && !empty($row->when_where)){
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
                            @else
                                @php
                                    $secondNoValueData[] = ' Child';
                                @endphp
                            @endif
                            @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh && !empty($oh->second_marriage->mtp_no)))
                                <tr>
                                    @if(!empty($oh->second_marriage->mtp_no))
                                        <th>
                                            <span class="ivf-label ">MTP : </span>{{$oh->second_marriage->mtp_no}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                            @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh)  && $oh->second_marriage->mtp_no != null && $oh->second_marriage->mtp_no != 0)
                                @foreach($oh->second_marriage->mtp->mtp_data as $key=>$row)
                                    
                                    @php
                                        $numberKey = addOrdinalNumberSuffix($key);
                                        $firstAbortionData = $numberKey;
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
                                                if(!empty($row->ho_type)){
                                                    if (array_key_exists($row->ho_type, $abortion_type_array)){
                                                        $firstAbortionData .= ' AND '.$abortion_type_array[$row->ho_type];
                                                    }
                                                    $hoTypeValue = [2,3,4];
                                                    $dNone = '';
                                                    if(!empty($row->ho_type) && !in_array($row->ho_type,$hoTypeValue)){
                                                        $dNone = 'd-none';
                                                    }
                                                    if($oh->mtp_no != null && $oh->mtp_no != 0 && $dNone == '' && !empty($row->when_where)){
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
                                    $secondNoValueData[] = ' MTP';
                                @endphp
                            @endif
                            @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh) && !empty($oh->second_marriage->abortion_no))
                                <tr>
                                    <th>
                                        <span class="ivf-label ">Abortion :</span>
                                    </th>
                                    @php
                                        $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                    @endphp
                                </tr>
                            @endif

                            @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh) && $oh->second_marriage->abortion_no != null && $oh->second_marriage->abortion_no != 0 )
                                @foreach($oh->second_marriage->abortion->abortion_data as $key=>$value)
                                    @if($value->spontancous_abortion_status == 'yes')
                                        <tr>
                                            @php
                                                $secondAbortionData = null;
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
                                                $abortion_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
                                                if(!empty($value->ho_type) && !empty($oh->second_marriage->abortion_no) && $oh->second_marriage->abortion_no != 0 ){
                                                    if (array_key_exists($value->ho_type, $abortion_type_array)){
                                                        $secondAbortionData .= ' AND '.$abortion_type_array[$value->ho_type];
                                                    }
                                                    $hoTypeValue = [2,3,4];
                                                    $dNone = '';
                                                    if (!in_array($value->ho_type,$hoTypeValue)) {
                                                        $dNone = 'd-none';
                                                    }
                                                    if($oh->second_marriage->abortion_no != null && $oh->second_marriage->abortion_no != 0 && $dNone == '' && !empty($value->when_where)){
                                                        $secondAbortionData .= ' - '.$value->when_where;
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
                            @else
                                @php
                                    $secondNoValueData[] = ' Abortion';
                                @endphp
                            @endif
                            @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh) && !empty($oh->second_marriage->contraception) && !empty($oh->second_marriage->contraception->contraception_status) && $oh->second_marriage->contraception->contraception_status == 'yes' && !empty($oh->second_marriage->contraception->contraception_data))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Contraception Method : </span> {{$contraceptionData[$oh->second_marriage->contraception->contraception_data]}}
                                    </th>
                                </tr>
                            @else
                                @php
                                    $secondNoValueData[] = ' Contraception';
                                @endphp
                            @endif
                        
                            <br>
                            @if(!empty($secondNoValueData) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes')
                                <tr>
                                    <th>
                                        {{'No '.implode(',',$secondNoValueData)}}
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($oh->remark))
                                    <tr>
                                        <th>
                                            <span class="ivf-label">Remark :</span>
                                            {{$oh->remark}}
                                        </th>
                                    </tr>
                                @endif
                        @endif
                        </tbody>
                    </table>
                @if($mh)
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                        <tbody>
                            <tr>
                                <td colspan="9">
                                    <div class="panel-title header-print-title">Menstrual History</div>
                                </td>
                            </tr>
                            @if (!empty($mh->age_of_menarchy) || !empty($mh->since_year))
                                <tr>
                                    @if (!empty($mh->age_of_menarchy))
                                        <th>
                                            <span class="ivf-label">Age Of Menarchy : </span>
                                            {{ $mh->age_of_menarchy }}
                                        </th>
                                    @endif

                                    @if (!empty($mh->since_year))
                                        <th>
                                            <span class="ivf-label">Since Year :</span>
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
                                    @if(!empty($mh->same_past) && $mh->same_past == 'same')
                                        <span class="ivf-label">Present / Past M/H :</span>
                                    @endif
                                    @if ($mh->past_mh_2 == 'regular') @else | IR Regular @endif
                                        @if (!empty($mh->past_interval_of_day) || $mh->past_mh_2 == 'regular')
                                    | Duration Of Menstruation : {{$mh->past_mh_2 == 'regular' ? '3 - 4 day' : $mh->past_interval_of_day}}
                                    @endif
                                    @if (!empty($mh->past_duration_of_day) || $mh->past_mh_2 == 'regular')
                                    at Interval Of : {{$mh->past_mh_2 == 'regular' ? '28 - 30 day' : $mh->past_duration_of_day}}
                                    @endif
                                    @if($mh->past_mh_2 != 'regular')
                                    | {{ucwords($mh->past_month) ? $mh->past_month : ''}}
                                    @else
                                        Regular, Moderate, Painless
                                    @endif
                                    @if(!empty($mh->present_withdrawal_medicine) && $mh->present_withdrawal_medicine == 'yes')
                                        | Withdrawal by Medicine 
                                    @endif
                                </th>
                            </tr>
                            @if(!empty($mh->same_past) && $mh->same_past == 'exit')
                                <tr>
                                    <th>
                                        <span class="ivf-label ">Present M/H : </span>
                                        {{ ucwords($mh->present_mh_1) }}
                                        | @if ($mh->present_mh_2 == 'regular')Regular @else IR Regular @endif
                                        @if (!empty($mh->present_duration_of_day) || $mh->present_mh_2 == 'regular')
                                        | Duration Of Menstruation : {{$mh->present_mh_2 == 'regular' ? '28 - 30 day' : $mh->present_duration_of_day}}
                                        @endif
                                        @if (!empty($mh->present_interval_of_day) || $mh->present_mh_2 == 'regular')
                                        at Interval Of : {{$mh->present_mh_2 == 'regular' ? '3 - 4 day' : $mh->present_interval_of_day}}
                                        @endif
                                        @if($mh->present_mh_2 != 'regular')
                                            | {{ !empty($mh->present_month) ? $mh->present_month : ''}}
                                        @else
                                            Regular, Moderate, Painless
                                        @endif
                                        @if(!empty($mh->present_withdrawal_medicine) && $mh->present_withdrawal_medicine == 'yes')
                                            | Withdrawal by Medicine 
                                        @endif
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($mh->last_menstrual_date) || !empty($mh->since_month))
                                <tr>
                                    @if(!empty($mh->last_menstrual_date))
                                        <th class=" w-300">
                                            <span class="ivf-label">Last Menstrual Date :</span>
                                            {{!empty($mh->last_menstrual_date) ?  \Carbon\Carbon::parse($mh->last_menstrual_date)->format('d/m/Y') : '-' }}
                                            <br>
                                            @if(isset($mh->lmd_date_diff) && !empty($mh->lmd_date_diff))
                                                <span class="ivf-label">Day of menses:</span>
                                                {{ $mh->lmd_date_diff }}
                                            @endif
                                        </th>

                                    @endif
                                    @if(!empty($mh->since_month) || !empty($mh->since_cycle))
                                        @if(!empty($mh->since_month))
                                            <th>
                                                <span class="ivf-label">Since Month :</span>
                                                {{!empty($mh->since_month) ?  $mh->since_month : '-' }}
                                            </th>
                                        @endif
                                        <th>
                                            <span class="ivf-label">Since Cycle :</span>
                                            {{!empty($mh->since_cycle) ?  $mh->since_cycle : '-' }}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif
                @if($hoRx && (!empty($hoRx->taken) && !empty($hoRx->taken->status) && $hoRx->taken->status == 'yes') || (!empty($hoRx->iui) && !empty($hoRx->iui->status) && $hoRx->iui->status == 'yes') || (!empty($hoRx->ivf) && !empty($hoRx->ivf->status) && $hoRx->ivf->status == 'yes'))
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table iui-inner-data">
                        <tbody>
                            <tr>
                                <td colspan="9">
                                    <div class="panel-title header-print-title">History of Rx taken elsewhere</div>
                                </td>
                            </tr>
                            @if($hoRx->taken->status == 'yes')
                                <tr>
                                    @if(isset($hoRx->taken->status) && ($hoRx->taken->status == 'yes') && isset($hoRx->taken->how_much_no) && $hoRx->taken->how_much_no > 0)
                                        <tr>
                                            {{-- <td> --}}
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @if(isset($hoRx->taken->how_much))
                                                        @foreach($hoRx->taken->how_much as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td>
                                                                        {{$value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                                @if (isset($hoRx->taken->type))
                                                {{-- <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table"> --}}
                                                    @if (isset($hoRx->taken->type))
                                                        @foreach($hoRx->taken->type as $key => $value)
                                                            @if(!empty(array_filter($value)))
                                                                <tr>
                                                                    <td>
                                                                        @if ($value[0] == 1)
                                                                            Ovulation induction done with Clomiphene
                                                                        @elseif ($value[0] == 2)
                                                                            Ovulation induction done with Letroz
                                                                        @elseif ($value[0] == 3)
                                                                            Ovulation induction done with both Clomiphene and letroze
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                {{-- </table> --}}
                                                @endif
                                            {{-- </td> --}}
                                        </tr>
                                    @endif
                                </tr>
                            @endif
                            @if($hoRx->iui->status == 'yes')
                                <tr>
                                    @if(isset($hoRx->iui->status) && ($hoRx->iui->status == 'yes'))
                                        @if(!empty($hoRx->iui->details))
                                            <th>
                                                <span class="ivf-label">Details :</span>
                                                {{$hoRx->iui->details}}
                                            </th>
                                        @endif
                                    @endif
                                    @if(isset($hoRx->iui->status) && ($hoRx->iui->status == 'yes') && isset($hoRx->iui->how_much_no) && ($hoRx->iui->how_much_no > 0))
                                        <tr>
                                            <td style="width: 10%">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @if (isset($hoRx->iui->how_much))
                                                        @foreach($hoRx->iui->how_much as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td>
                                                                        {{$value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @if (isset($hoRx->iui->when_where))
                                                        @foreach($hoRx->iui->when_where as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td>
                                                                        {{$value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            <td colspan="4">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @if (isset($hoRx->iui->type))
                                                        @foreach($hoRx->iui->type as $key => $value)
                                                            @if(!empty(array_filter($value)))
                                                                <tr>
                                                                    <td>
                                                                        @if ($value[0] == 1)
                                                                            IUI-H
                                                                        @elseif ($value[0] == 2)
                                                                            IUI-D
                                                                        @elseif ($value[0] == 3)
                                                                            Both
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                </tr>
                            @endif
                            @if($hoRx->ivf->status == 'yes')
                                <tr>
                                    @if(isset($hoRx->ivf->status) && ($hoRx->ivf->status == 'yes') && isset($hoRx->ivf->how_much_no) && $hoRx->ivf->how_much_no > 0)
                                        <tr>
                                            <td style="width: 10%">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @if (isset($hoRx->ivf->how_much))
                                                        @foreach($hoRx->ivf->how_much as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td>
                                                                        {{$value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @if (isset($hoRx->ivf->when_where))
                                                        @foreach($hoRx->ivf->when_where as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td>
                                                                        {{'When / Where: ' . $value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            <td colspan="4">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                                    @php
                                                        if(isset($hoRx->ivf->type)) {
                                                            $medicines = collect($hoRx->ivf->type)->toArray();
                                                            $medicineKeys = array_keys($medicines);
                                                        }
                                                    @endphp
                                                    @if (isset($hoRx->ivf->type))
                                                        @for ($i = 1; $i <= $hoRx->ivf->how_much_no; $i++)
                                                            <tr>
                                                                <td>
                                                                    @if (in_array($i, $medicineKeys))
                                                                        @foreach($medicines[$i] as $value)
                                                                            @if ($value == 1)
                                                                                IVF-SELF
                                                                            @elseif ($value == 2)
                                                                                IVF-OD
                                                                            @elseif ($value == 3)
                                                                                IVF-ED
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif
                <div class="display">
                    @if($oe  && ($oe->tvs->type == 'yes' || $oe->p_s->type == 'yes' || !empty($oe->cervix->details) || !empty($oe->le->bp) || !empty($oe->le->temp) || !empty($oe->le->pulse)))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">O/E</div>
                                    </td>
                                </tr>
                                <tr>
                                    @if(!empty($oe->le->temp) || !empty($oe->le->bp) || !empty($oe->le->pulse))
                                        <th class=" w-100">
                                            Vitals
                                            @if(!empty($oe->le->temp))
                                                <br>
                                                <span class="ivf-label">&nbsp;Temp :</span>
                                                {{$oe->le->temp}}
                                            @endif
                                            @if(!empty($oe->le->pulse))
                                                <br>
                                                <span class="ivf-label">&nbsp;Pulse :</span>
                                                {{$oe->le->pulse ? $oe->le->pulse : '80'}} / Min
                                            @endif
                                            @if(!empty($oe->le->bp))
                                                <br>
                                                <span class="ivf-label">B.P :</span>
                                                {{$oe->le->bp ? $oe->le->bp : '110/70'}} MMHG
                                            @endif
                                        </th>
                                    @endif
                                </tr>
                                @if($oe->p_s->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="ivf-label">P / S:</span>
                                            {{ !empty($oe->p_s->type == 'yes') ? 'Yes' : 'No' }}
                                            @if ($oe->p_s->type == 'yes')
                                                {{!empty($oe->p_s->details) ? '| '.$oe->p_s->details : '-' }}
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($oe->cervix->details))
                                    <tr>
                                        <th>
                                            <span class="ivf-label">Cervix:  </span>
                                            {{ !empty($oe->cervix->details) ? $oe->cervix->details : '-' }}
                                        </th>
                                    </tr>
                                @endif
                                @if($oe->tvs->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="ivf-label">Transvaginal Ultrasonography:</span>
                                        </th>
                                    </tr>
                                @endif
                                @if ($oe->tvs->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="ivf-label">Uterus:  </span>
                                            {{ !empty($oe->uterus->type == '2') ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($oe->uterus->type == '2')
                                            <th>
                                                <span class="ivf-label">Abnormal Details:  </span>
                                                {{ !empty($oe->uterus->details) ? $oe->uterus->details : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if ($oe->tvs->type == 'yes' && !empty($oe->endometrial_thickness))
                                    <tr>
                                        <th>
                                            <span class="ivf-label">Endometrial Thickness:  </span>
                                            {{ !empty($oe->endometrial_thickness) ? $oe->endometrial_thickness : '-' }}
                                        </th>
                                    </tr>
                                @endif
                                @if (!empty($oe->ovary->right->updated_details) || !empty($oe->ovary->right->afcs))
                                <tr>
                                    <th>
                                        @if (!empty($oe->ovary->right->updated_details))
                                        <span class="ivf-label">Right Ovary</span>
                                            @foreach ($oe->ovary->right->updated_details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if(!empty($oe->ovary->right->afcs) && isset($mh->lmd_date_diff) && in_array($mh->lmd_date_diff,['2','3','4']))
                                            <span class="ivf-label">Follicle numbers per ovaryy</span>
                                            {{$oe->ovary->right->afcs}}
                                        @endif
                                    </th>
                                </tr>
                                @endif
                                @if(!empty($oe->ovary->left->updated_details) || !empty($oe->ovary->left->afcs))
                                <tr>
                                    <th>
                                        
                                        @if(!empty($oe->ovary->left->updated_details))
                                        <span class="ivf-label">Left Ovary</span>
                                            @foreach($oe->ovary->left->updated_details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if(!empty($oe->ovary->left->afcs) && isset($mh->lmd_date_diff) && in_array($mh->lmd_date_diff,['2','3','4']))
                                            <span class="ivf-label">Follicle numbers per ovaryy</span>
                                            {{$oe->ovary->left->afcs}}
                                        @endif
                                    </th>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    @if($husbandFactor && !empty($husbandFactor->occupation) || !empty($husbandFactor->seman_analysis) || !empty($husbandFactor->habbit) || !empty($husbandFactor->sperm_count) || !empty($husbandFactor->personal_history_date) || !empty($husbandFactor->remark))
                            <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                <tbody>
                                    <tr>
                                        <td colspan="9">
                                            <div class="panel-title header-print-title">Husband Factor</div>
                                        </td>
                                    </tr>
                                    @if(!empty($husbandFactor->occupation) || !empty($husbandFactor->seman_analysis) || !empty($husbandFactor->age) || !empty($husbandFactor->habbit))
                                        <tr>
                                            @if(!empty($husbandFactor->occupation))
                                                <th>
                                                    <span class="ivf-label">Occupation:  </span>
                                                    {{ !empty($husbandFactor->occupation) ? $husbandFactor->occupation : '-' }}
                                                    <br>
                                                    @if(!empty($husbandFactor->age))
                                                        <span class="ivf-label">Age:  </span>
                                                        {{ !empty($husbandFactor->age) ? $husbandFactor->age : '-' }}
                                                    @endif
                                                    <br>
                                                    @if(!empty($husbandFactor->habbit))
                                                        <span class="ivf-label"> Habbit:  </span>
                                                        {{ !empty($husbandFactor->habbit) ? $husbandFactor->habbit : '-' }}
                                                    @endif
                                                </th>
                                            @endif
                                        </tr>
                                        <tr>
                                                <th>
                                                    @if (!empty($husbandFactor->seman_analysis))
                                                        <span class="ivf-label">Semen Analysis:  </span>
                                                    
                                                        @if ($husbandFactor->seman_analysis == 1)
                                                            WNL
                                                        @elseif ($husbandFactor->seman_analysis == 2)
                                                            Oligospermia
                                                        @elseif ($husbandFactor->seman_analysis == 3)
                                                            Azoospermic
                                                        @endif
                                                    @endif
                                                    @if(!empty($husbandFactor->sperm_count))
                                                        <br>
                                                        <span class="ivf-label"> Sperm Count:  </span>
                                                        {{ !empty($husbandFactor->sperm_count) ? $husbandFactor->sperm_count : '-' }}
                                                    @endif
                                                    @if(!empty($husbandFactor->motility))
                                                        <br>
                                                        <span class="ivf-label"> Motility:  </span>
                                                        {{ !empty($husbandFactor->motility) ? $husbandFactor->motility : '-' }}
                                                    @endif
                                                    </th>
                                        </tr>
                                    @endif
                                    @if(!empty($husbandFactor->amount_in_ml) ||!empty($husbandFactor->personal_history_date))
                                        <tr>
                                            @if(!empty($husbandFactor->amount_in_ml))
                                                <th>
                                                    <span class="ivf-label">Amount in ML:  </span>
                                                    {{ !empty($husbandFactor->amount_in_ml) ? $husbandFactor->amount_in_ml : '-' }}
                                                </th>
                                            @endif

                                            @if(!empty($husbandFactor->personal_history_date))
                                                <th>
                                                    <span class="ivf-label"> Date:  </span>
                                                    {{ !empty($husbandFactor->personal_history_date) ? $husbandFactor->personal_history_date : '-' }}
                                                </th>
                                            @endif
                                        </tr>
                                    @endif
                                    @if ($husbandFactor->seman_analysis == 2 && (!empty($husbandFactor->medicine) || !empty($husbandFactor->duration) || !empty($husbandFactor->sperm_report)))
                                        <tr>
                                            @if( !empty($husbandFactor->medicine) )
                                                <th>
                                                    <span class="ivf-label">Medicine:  </span>
                                                    {{ !empty($husbandFactor->medicine) ? $husbandFactor->medicine : '-' }}
                                                </th>
                                            @endif
                                            @if(!empty($husbandFactor->duration))
                                                <th>
                                                    <span class="ivf-label">  Duration:  </span>
                                                    {{ !empty($husbandFactor->duration) ? $husbandFactor->duration : '-' }}
                                                </th>
                                            @endif
                                            @if(!empty($husbandFactor->sperm_report))
                                                <th>
                                                    <span class="ivf-label">Sperm Report:  </span>
                                                    {{ !empty($husbandFactor->sperm_report) ? $husbandFactor->sperm_report : '-' }}
                                                </th>
                                            @endif
                                        </tr>
                                    @endif
                                    @if(!empty($husbandFactor->remark))
                                        <tr>
                                            <th>
                                                <span class="ivf-label">Husband Factor Remark : </span>
                                                {{  $husbandFactor->remark }}
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                    @endif
                    @if($patientDetailedHO && (!empty($patientDetailedHO->personal_history_history_type) || !empty($patientDetailedHO->personal_history_date) || !empty($patientDetailedHO->family_history) || !empty($patientDetailedHO->past_history_type)))
                            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                                <tbody>
                                    @if(!empty($patientDetailedHO->personal_history_history_type))
                                        @php
                                            $patientDetailedHO->personal_history_history_type = is_array($patientDetailedHO->personal_history_history_type) ? $patientDetailedHO->personal_history_history_type : (array)$patientDetailedHO->personal_history_history_type;
                                        @endphp
                                        <tr>
                                            <th>
                                                <span class="ivf-label">Personal History :</span>
                                                {{implode(',',$patientDetailedHO->personal_history_history_type)}}
                                            </th>
                                        </tr>
                                    @endif
                                    @if(!empty($patientDetailedHO->personal_history_date))
                                        <tr>
                                            <th>
                                                <span class="ivf-label">Date :</span>
                                                {{\Carbon\Carbon::parse($patientDetailedHO->personal_history_date)->format('D d M Y')}}
                                            </th>
                                        </tr>
                                    @endif
                                    @if(!empty($patientDetailedHO->family_history))
                                        @php
                                            $patientDetailedHO->family_history = is_array($patientDetailedHO->family_history) ? $patientDetailedHO->family_history : (array)$patientDetailedHO->family_history;
                                        @endphp
                                        <tr>
                                            <th>
                                                <span class="ivf-label">Family History :</span>
                                                {{implode(',',$patientDetailedHO->family_history)}}
                                            </th>
                                        </tr>
                                    @endif
                                    @php
                                        // $personal_past_history_type = ['nad'=>'NAD','tuberculosis_bacillus'=>"Tuberculosis Bacillus",'hypertension'=>"Hypertension",'thyroid'=>"Thyroid",'dm'=>"DM",'appendectomy'=>'Appendectomy','laparoscopy'=>'Laparoscopy'];
                                    @endphp

                                    @if(!empty($patientDetailedHO->past_history_type))
                                        @php
                                            $patientDetailedHO->past_history_type = is_array($patientDetailedHO->past_history_type) ? $patientDetailedHO->past_history_type : (array)$patientDetailedHO->past_history_type;
                                        @endphp
                                        <tr>
                                            <th>
                                                <span class="ivf-label">Past History :</span>
                                                {{implode(',',$patientDetailedHO->past_history_type)}}
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                    @endif
                    
                </div>
                <div class="display">
                    {{-- $pt_view is set bcz don't want to display in patients application --}}
                    @if($planManagement && (!isset($pt_view) || $pt_view != 1) && (isset($planManagement->plan_of_management_data) || (isset($planManagement->plan) && !empty($planManagement->plan))))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">Plan Management</div>
                                    </td>
                                </tr>
                                @if (isset($planManagement->plan_of_management_data))
                                    @if (in_array('ivf', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                <span class="ivf-label"> IVF</span>
                                                {{!empty($planManagement->ivf_details) && !empty($planManagement->is_print) ? $planManagement->ivf_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                @if(isset($planManagement->plan) && !empty($planManagement->plan))
                                    <tr>
                                        <th>
                                            <span class="ivf-label"> Plan</span>
                                            @if (isset($planManagement->plan) && !empty($planManagement->plan))
                                                @switch($planManagement->plan)
                                                    @case('1')
                                                        IVF Self
                                                        @break
                                                    @case('2')
                                                        FET Self
                                                        @break
                                                    @case('3')
                                                        FET-OD
                                                        @break
                                                    @case('4')
                                                        FET-ED
                                                        @break
                                                @endswitch
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    
                    @if($possibleCaseOfInfertility && (!empty($possibleCaseOfInfertility->other) || !empty($possibleCaseOfInfertility->infertility_type)))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">Possible Cause of Infertility</div>
                                    </td>
                                </tr>
                                @php
                                    $infertilityType = null;
                                    if(!empty($possibleCaseOfInfertility->infertility_type)) {
                                        $infertilityType = implode(', ', $possibleCaseOfInfertility->infertility_type);
                                    }
                                @endphp
                                <tr>
                                    @if(!empty($infertilityType))
                                        <th>
                                            {{ ucwords($infertilityType) }}
                                        </th>
                                    @endif
                                </tr>
                                <tr>
                                    @if(!empty($possibleCaseOfInfertility->other))
                                        <th>
                                            <span class="ivf-label">Other </span>
                                            {{ !empty($possibleCaseOfInfertility->other) ? $possibleCaseOfInfertility->other : '-' }}
                                        </th>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    @endif
                    @if($investigation && (!empty($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes' || (!empty($investigation->laproscopy) && $investigation->laproscopy->type == 'yes') || (!empty($investigation->hcg) && $investigation->hcg->type == 'yes') || isset($investigation->investigation_extra) && !empty($investigation->investigation_extra)))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">Investigation Done Outside</div>
                                    </td>
                                </tr>
                                @if(!empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="ivf-label">Hystroscopy: </span>
                                            {{($investigation->hystroscopy->type == 'yes') ? 'Yes' : 'No' }}
                                        </th>
                                        @if(isset($investigation->hystroscopy->type) && ($investigation->hystroscopy->type == 'yes'))
                                            <th>
                                                <span class="ivf-label">Finding Type: </span>
                                                {{ ($investigation->hystroscopy->finding_type == 1) ? 'Normal' : 'Abnormal' }}
                                            </th>
                                            @if ($investigation->hystroscopy->finding_type == 2)
                                                <th>
                                                    <span class="ivf-label">Abnormal Details: </span>
                                                    {{!empty($investigation->hystroscopy->abnormal_details) ? $investigation->hystroscopy->abnormal_details : '-' }}
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes')
                                    <tr>
                                        @if($investigation->hystroscopy->finding_date)
                                            <th>
                                                <span class="ivf-label">Finding Date:</span>
                                                {{$investigation->hystroscopy->finding_date}}
                                            </th>
                                        @endif
                                        @if($investigation->hystroscopy->finding_details)
                                            <th>
                                                <span class="ivf-label">Details: </span>
                                                {{$investigation->hystroscopy->finding_details}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->laproscopy) && !empty($investigation->laproscopy->type) && $investigation->laproscopy->type == 'yes')
                                    <tr>
                                        <th colspan="9">
                                            <span class="ivf-label">Laproscopy:  </span>
                                            @if (!empty($investigation->laproscopy) && !empty($investigation->laproscopy->type))
                                                {{$investigation->laproscopy->type == 'yes' ? 'Yes' : 'No' }}
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->laproscopy) && ((!empty($investigation->laproscopy->finding_date) || !empty($investigation->laproscopy->laproscopy_type))))
                                    <tr>
                                        @if(!empty($investigation->laproscopy->finding_date))
                                            <th>
                                                <span class="ivf-label">Date: </span>
                                                {{$investigation->laproscopy->finding_date}}
                                            </th>
                                        @endif

                                        @if (!empty($investigation->laproscopy) && !empty($investigation->laproscopy->laproscopy_type) && !empty($investigation->laproscopy->type) && $investigation->laproscopy->type == 'yes')
                                            <td>
                                                {{ ($investigation->laproscopy->laproscopy_type == 2) ? 'Abnormal' : 'Normal' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="ivf-label">RT Tube: </span>
                                            {{($investigation->laproscopy->rt_tube_type == 2) ? 'Abnormal' : 'Normal'}}
                                        </th>
                                        @if ($investigation->laproscopy->rt_tube_type == 2)
                                            <td colspan="6">
                                                {{ !empty($investigation->laproscopy->rt_tube_details) ? $investigation->laproscopy->rt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="ivf-label"> Uterus: </span>
                                            {{ ($investigation->laproscopy->uterus_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->laproscopy->uterus_type == 2)
                                            <td colspan="9">
                                                {{ !empty($investigation->laproscopy->uterus_details) ? $investigation->laproscopy->uterus_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="ivf-label">LT Tube:  </span>
                                            {{ ($investigation->laproscopy->lt_tube_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->laproscopy->lt_tube_type == 2)
                                            <td colspan="6">
                                                {{ !empty($investigation->laproscopy->lt_tube_details) ? $investigation->laproscopy->lt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2 && !empty($investigation->laproscopy->other))
                                    <tr>
                                        <th>
                                            <span class="ivf-label"> Other:  </span>
                                            {{$investigation->laproscopy->other}}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->hcg) && $investigation->hcg->type == 'yes')
                                    <tr>
                                        <th colspan="9">
                                            <span class="ivf-label">  HSG: </span>
                                            @if (!empty($investigation->hcg->type))
                                                {{ $investigation->hcg->type == 'yes' ? 'Yes' : 'No' }}
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->hcg->date) || !empty($investigation->hcg->type))
                                    <tr>
                                        @if(!empty($investigation->hcg->date))
                                            <th>
                                                <span class="ivf-label">Date: </span>
                                                {{$investigation->hcg->date}}
                                            </th>
                                        @endif
                                        @if (!empty($investigation->hcg->type) && $investigation->hcg->type == 'yes')
                                            <td>
                                                {{ ($investigation->hcg->laproscopy_type == 2) ? 'Abnormal' : 'Normal' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->hcg->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="ivf-label">RT Tube: </span>
                                            {{ ($investigation->hcg->rt_tube_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->hcg->rt_tube_type == 2)
                                            <td colspan="9">
                                                {{ !empty($investigation->hcg->rt_tube_details) ? $investigation->hcg->rt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->hcg->laproscopy_type == 2 && $investigation->hcg->uterus_type)
                                    <tr>
                                        <th>
                                            <span class="ivf-label"> Uterus: </span>
                                            {{ ($investigation->hcg->uterus_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if($investigation->hcg->uterus_type == 2)
                                            <td colspan="9">
                                                {{!empty($investigation->hcg->uterus_details) ? $investigation->hcg->uterus_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->hcg->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="ivf-label"> LT Tube:  </span>
                                            {{ ($investigation->hcg->lt_tube_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->hcg->lt_tube_type == 2)
                                            <td colspan="9">
                                                {{ !empty($investigation->hcg->lt_tube_details) ? $investigation->hcg->lt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->cbc) || !empty($investigation->urine) || !empty($investigation->rbs) || !empty($investigation->hiv))
                                    <tr>
                                        @if(!empty($investigation->cbc))
                                            <th>
                                                <span class="ivf-label"> CBC:  </span>
                                                {{$investigation->cbc}}
                                            </td>
                                        @endif
                                        @if(!empty($investigation->urine))
                                            <th>
                                                <span class="ivf-label">  Urine:  </span>
                                                {{$investigation->urine}}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->rbs))
                                            <th>
                                                <span class="ivf-label">  RBS:  </span>
                                                {{$investigation->rbs}}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->hiv))
                                            <th>
                                                <span class="ivf-label">  HIV: </span>
                                                {{ !empty($investigation->hiv) ? $investigation->hiv : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->hbs_ag) || !empty($investigation->date_1))
                                    <tr>
                                        @if(!empty($investigation->hbs_ag))
                                            <th>
                                                <span class="ivf-label"> Hbs Ag:  </span>
                                                {{$investigation->hbs_ag}}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->date_1))
                                            <th>
                                                <span class="ivf-label">Date 1:</span>
                                                {{$investigation->date_1}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->tsh) || !empty($investigation->fsh) || !empty($investigation->prolectin) || !empty($investigation->lh))
                                    <tr>
                                        @if(!empty($investigation->tsh))
                                            <th>
                                                <span class="ivf-label">   TSH:  </span>
                                                {{ !empty($investigation->tsh) ? $investigation->tsh : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->fsh))
                                            <th>
                                                <span class="ivf-label">  FSH:  </span>
                                                {{ !empty($investigation->fsh) ? $investigation->fsh : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->prolectin))
                                            <th>
                                                <span class="ivf-label"> Prolectin: </span>
                                                {{ !empty($investigation->prolectin) ? $investigation->prolectin : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->lh))
                                            <th>
                                                <span class="ivf-label">  LH: </span>
                                                {{ !empty($investigation->lh) ? $investigation->lh : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->amh) || !empty($investigation->e2) || !empty($investigation->p2) || !empty($investigation->date_2))
                                    <tr>
                                        @if(!empty($investigation->amh))
                                            <th>
                                                <span class="ivf-label"> AMH:  </span>
                                                {{ !empty($investigation->amh) ? $investigation->amh : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->e2))
                                            <th>
                                                <span class="ivf-label"> E2:  </span>
                                                {{ !empty($investigation->e2) ? $investigation->e2 : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->p2))
                                            <th>
                                                <span class="ivf-label">  P2:  </span>
                                                {{ !empty($investigation->p2) ? $investigation->p2 : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->date_2))
                                            <th>
                                                <span class="ivf-label">  Date 2:  </span>
                                                {{!empty($investigation->date_2) ? $investigation->date_2 : '-'}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($investigation->investigation_extra) && !empty($investigation->investigation_extra)) 
                                    <tr >
                                        <th>
                                            <span class="ivf-label">Other Report :</span>
                                            {{$investigation->investigation_extra}}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->investigation_data))
                                    <tr>
                                        <th>
                                            @php
                                                $investigationData = [];
                                                $investigationValueDetails = [];
                                                $investigationReport = $investigationReport['reportData'];
                                                $data = $investigation->investigation_data;
                                                $investigationValueData = (array)$investigation->investigation_details;
                                                foreach($data as $key => $value){
                                                    if(!empty($investigationValueData[$value])){
                                                        $investigationValueDetails[$investigationReport[$value]] = $investigationValueData[$value];
                                                    }else{
                                                        $investigationData[] = $investigationReport[$value];
                                                    }
                                                }
                                            @endphp
                                            @if(count($investigationData)>0)
                                                <span class="ivf-label">Investigation Advise : {{implode(',',$investigationData)}}</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @if(!empty($investigationValueDetails))
                                        <tr>
                                            <th>
                                                <span class="ivf-label">Investigation Done :</span>
                                                @foreach($investigationValueDetails as $key => $value)
                                                    {!! '<span class="ivf-label">'.$key.'</span> :' .  $value !!}
                                                @endforeach
                                            </th>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
                <?php
                unset($treatment->medicinedata);
                ?>
                {{-- treatment tab --}}
                @if(count((array)$treatment) > 0)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="9">
                                    <div class="panel-title header-print-title">Treatment</div>
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
                                            <td>{{$row->no.' days'}}</td>
                                            <td>{{isset($row->note) && !empty($row->note) ? $row->note : '-'}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </tbody>
                    </table>
                @endif
                
                {{-- investigation --}}
                
            </div>
        @endif
        @if($isIvfHistory == '2')
            <style>
                @page { margin-top : 200px; margin-bottom : 80px;}
            </style>
            <div class="panel panel-primary">
            @if(@$historyData->is_transfer == 'no' || !isset($historyData->is_transfer_print) || $historyData->is_transfer_print == 'no')
                @if($lmp)
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                        <tbody>
                            <tr>
                                <th>
                                    <span class="ivf-label">Name : </span>{{ ucwords(strtolower($ivf->getPatientsDetails['name']))}}
                                        @php
                                            $gender = ($ivf->getPatientsDetails['gender'] == 2) ? 'F' : 'M';
                                        @endphp
                                        <br><span class="ivf-label">Age : </span>{{ $ivf->getPatientsDetails['age']. ' years | '.$gender }}
                                </th>
                                <th class="float-right">
                                    <span class="ivf-label">Visit Date:  </span>{{Carbon\Carbon::parse($ivf->created_at)->format('d/m/Y')}}
                                    @if($ivf->getPatientsDetails['weight'])
                                        <br><span class="ivf-label">Weight: </span>{{$ivf->getPatientsDetails['weight'].' kg'}}
                                    @endif
                                </th>
                            </tr>
                            @if(!empty($lmp->date))
                                <tr>
                                    <th>
                                        <br>
                                        <span class="ivf-label"> LMP Date: </span>
                                        {{ !empty($lmp->date) ? \Carbon\Carbon::parse($lmp->date)->format('d/m/Y').' '.(!empty($lmp->lmp_date_diff) ? 'Day of menses '.$lmp->lmp_date_diff.'' : '') : '-' }}
                                    </th>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                @endif
                @if(!empty($historyData->co_type))
                <br>
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                        <tbody>
                            <tr>
                                <th>
                                    <span class="ivf-label">C/O :</span>
                                    @if(!empty($historyData->co_type))
                                        {{implode(',',$historyData->co_type)}}
                                    @else
                                        'None'
                                    @endif
                                </th>
                            </tr>
                        </tbody>
                    </table>
                @endif
                @if(!empty($historyData->le) && !empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' && (!empty($historyData->le->bp) || !empty($historyData->le->temp) || !empty($historyData->le->pulse)))
                <br>
                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                    <tbody>
                        <tr><th><span class="ivf-label">Vitals :</span></th></tr>
                        <tr>
                            <th>
                                
                                @if(!empty($historyData->le->temp))
                                    <span class="ivf-label">Temp : </span>
                                    {{$historyData->le->temp}}
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @if(!empty($historyData->le->pulse))
                                    <span class="ivf-label">Pulse :</span>
                                    {{$historyData->le->pulse ? $historyData->le->pulse : '80'}} / Min
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @if(!empty($historyData->le->bp))
                                    <span class="ivf-label">BP :</span>
                                    {{$historyData->le->bp ? $historyData->le->bp : '110/70'}} MMHG
                                @endif
                            </th>
                        </tr>
                    </tbody>
                </table>
                @endif
                {{-- @if($plan != 1)
                @else --}}
                    @if($visit == 2 && $cycle == $cycle)
                        @if($oe)
                        <br>
                            <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                <tbody>
                                    <tr>
                                        @if ((isset($oe->oe_type) && !empty($oe->oe_type)))
                                            <th>
                                                <span class="ivf-label"> O / E :</span>
                                                {{-- {{strtoupper($oe->oe_type->type)}} --}}
                                                @php
                                                    if($oe->oe_type->type == 'tvs'){
                                                        echo "Transvaginal sonography";
                                                    }
                                                    if($oe->oe_type->type == 'pa')
                                                    {
                                                        echo "Transabdominal sonography";
                                                    }
                                                @endphp
                                            </th>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if (isset($oe->ut->ut_type) && !empty($oe->ut->ut_type))
                                            <th>
                                                <br>
                                                <span class="ivf-label">Uterus: </span>
                                                {{ ($oe->ut->ut_type == 1) ? 'Normal' : 'Abnormal' }}
                                            </th>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if (isset($oe->ut->ut_type) && $oe->ut->ut_type == 2 && !empty($oe->ut->details))
                                            <th>
                                                <span class="ivf-label"> UT Details :</span>
                                                {{  $oe->ut->details }}
                                            </th>
                                        @endif
                                    </tr>
                                    <tr>
                                    @if(!empty($ovary->ovary_type->right->details))
                                        <th>
                                            <span class="ivf-label">Overy Right:</span>
                                            {{$ovary->ovary_type->right->details}}
                                        </th>
                                    @endif
                                    @if(!empty($ovary->ovary_type->left->details))
                                        <th>
                                            <span class="ivf-label">Overy Left: </span>
                                            {{$ovary->ovary_type->left->details}}
                                        </th>
                                    @endif
                                    </tr>
                                    @if (!empty($oe->ovary->right->updated_details) || !empty($oe->ovary->right->afcs))
                                        <tr>
                                            <th>
                                                
                                                    <span class="ivf-label">OvaryRight :</span>
                                                    <br>
                                                @if (!empty($oe->ovary->right->updated_details))
                                                <br>
                                                    @foreach ($oe->ovary->right->updated_details as $key => $value)
                                                        @php
                                                            echo !empty($value) ? $value .  '<br />' : '-' . '<br />';
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                @if(!empty($oe->ovary->right->afcs))
                                                    <span class="ivf-label">Follicle numbers per ovary: </span>
                                                    {{$oe->ovary->right->afcs}}
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if (!empty($oe->ovary->left->updated_details) || !empty($oe->ovary->left->afcs))
                                        <tr>
                                            <th>
                                                    <span class="ivf-label">OvaryLeft :</span>
                                                    <br>
                                                @if (!empty($oe->ovary->left->updated_details))
                                                
                                                        @foreach($oe->ovary->left->updated_details as $key => $value)
                                                            @php
                                                                echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                            @endphp
                                                        @endforeach
                                                @endif
                                                @if (!empty($oe->ovary->left->afcs))
                                                    <span class="ivf-label">Follicle numbers per ovary: </span>
                                                    {{ $oe->ovary->left->afcs }}
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if(!empty($description->oe->endometrial_cavity->cavity) || !empty($description->oe->endometrial_cavity->size))
                                        {{-- <tr>
                                            @if(!empty($description->oe->endometrial_cavity->cavity))
                                                <th>
                                                    <span class="ivf-label"> Endometrial Cavity :</span>
                                                    <th>{{$description->oe->endometrial_cavity->cavity}}</th>
                                                </th>
                                            @endif
                                        </tr> --}}
                                        <tr>
                                            @if(!empty($description->oe->endometrial_cavity->size))
                                                <th>
                                                    <span class="ivf-label"> Endometrial Thickness:</span>
                                                    {{$description->oe->endometrial_cavity->size}}
                                                </th>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        @endif
                    @else
                        @if($oe)
                        <br>
                            <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                                <tbody>
                                    @if((isset($oe->oe_type) && !empty($oe->oe_type)))
                                    <tr>
                                        <th>
                                            <span class="ivf-label"> O / E :</span>
                                            {{-- {{ strtoupper($oe->oe_type->type) }} --}}
                                            @php
                                                if($oe->oe_type->type == 'tvs'){
                                                    echo "Transvaginal sonography";
                                                }
                                                if($oe->oe_type->type == 'pa')
                                                {
                                                    echo "Transabdominal sonography";
                                                }
                                            @endphp
                                        </th>
                                    </tr>
                                    @endif
                                    @if (isset($oe->ut->ut_type) && !empty($oe->ut->ut_type))
                                        <tr>
                                                <th>
                                                    <br>
                                                    <span class="ivf-label">Uterus: </span>
                                                    {{ ($oe->ut->ut_type == 1) ? 'Normal' : 'Abnormal' }}
                                                </th>
                                        
                                        </tr>
                                    @endif
                                    @if (isset($oe->ut->ut_type) && $oe->ut->ut_type == 2 && !empty($oe->ut->details))
                                        <tr>
                                            
                                                <th>
                                                    <span class="ivf-label"> UT Details:</span>
                                                    {{  $oe->ut->details }}
                                                </th>
                                        
                                        </tr>
                                    @endif
                                    @if(!empty($ovary->ovary_type->right->details))
                                    <tr>
                                            <th>
                                                <span class="ivf-label"> Overy Right:</span>
                                                {{$ovary->ovary_type->right->details}}
                                            </th>
                                    </tr>
                                    @endif
                                    @if(!empty($ovary->ovary_type->left->details))
                                    <tr>
                                        
                                            <th>
                                                <span class="ivf-label"> Overy Left:</span>
                                                {{$ovary->ovary_type->left->details}}
                                            </th>
                                    
                                    </tr>
                                    @endif
                                    
                                        {{-- @if(!empty($description->oe->endometrial_cavity->cavity))
                                            <tr>
                                                <th>
                                                    <span class="ivf-label">Endometrial Cavity :</span>
                                                    {{$description->oe->endometrial_cavity->cavity}}
                                                </th>
                                            </tr>
                                        @endif --}}
                                        @if(!empty($description->oe->endometrial_cavity->size))
                                        <tr>
                                            <th>
                                                <span class="ivf-label"> Endometrial Thickness: </span>
                                                {{$description->oe->endometrial_cavity->size}}
                                            </th>
                                        </tr>
                                        @endif
                                </tbody>
                            </table>
                        @endif
                    @endif
                {{-- @endif --}}
                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                    <tbody>
                        
                        @if($visit != 2 && $cycle == $cycle)
                            @if(!empty($dataa->date))
                                <tr>
                                    <th>
                                        <span class="ivf-label"> Date </span>
                                        {{!empty($dataa->date) ? $dataa->date : (!empty($lastAppointmentData->date) ? \Carbon\Carbon::parse($lastAppointmentData->date)->format('D d M Y') : '-')}}
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($dataa->report->type) && $dataa->report->type == 'report')
                                <tr>
                                    <th>
                                        <span class="ivf-label"> Report </span>
                                        {{'Yes'}}
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($dataa->report->embroy->type) && $dataa->report->embroy->type =='yes')
                                <tr>
                                    <th>
                                        <span class="ivf-label"> Is Embroy ? </span>
                                        {{'Yes'}}
                                    </th>
                                </tr>
                            @endif
                            @if(in_array('progesterone',$collectionData))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Do satrting progesterone ?</span>
                                        {{'Yes'}}
                                    </th>
                                </tr>
                            @endif
                            @if(!empty($historyData->progesterone->status) && $historyData->progesterone->status == 'yes')
                                <tr>
                                    <th>
                                        <span class="ivf-label">Same Cycle Transfer?</span>
                                        {{!empty($historyData->progesterone->status) ? ucwords($historyData->progesterone->status) : 'No'}}
                                    </th>
                                </tr>
                            @endif
                            @if (in_array('progesterone',$collectionData) && !empty($historyData->progesterone->type))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Progesterone Days ?</span>
                                        {{!empty($historyData->progesterone->type) ? ucwords($historyData->progesterone->type) : '-'}}
                                    </th>
                                </tr>
                            @endif
                            @if(in_array('trigger',$collectionData))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Trigger</span>
                                            {{'Yes'}}
                                    </th>
                                    @if(!empty($historyData->trigger->hcg->status))
                                        <th>
                                            <span class="ivf-label"> HCG : </span>
                                        </th>
                                        <td>{{!empty($historyData->trigger->hcg->status) && $historyData->trigger->hcg->status == 'hcg' ? 'Yes' : 'No'}}</td>
                                        @if(!empty($historyData->trigger->hcg->status) && $historyData->trigger->hcg->status == 'hcg')
                                            @if(!empty($historyData->trigger->hcg->time))
                                                <th>
                                                    <span class="ivf-label">HCG Time : </span>
                                                    {{$historyData->trigger->hcg->time}}
                                                </th>
                                            @endif
                                            @if(!empty($historyData->trigger->hcg->dose))
                                                <th>
                                                    <span class="ivf-label">HCG Dose : </span>
                                                    {{$historyData->trigger->hcg->dose}}
                                                </th>
                                            @endif
                                            @if(!empty($historyData->trigger->hcg->brand))
                                                <th>
                                                    <span class="ivf-label">HCG Brand : </span>
                                                    {{$historyData->trigger->hcg->brand}}
                                                </th>
                                            @endif
                                        @endif
                                    @endif
                                </tr>
                            @endif
                            @if(in_array('trigger',$collectionData))
                                @if(!empty($historyData->trigger->decapeptyl->status))
                                    <tr>
                                        @if(!empty($historyData->trigger->decapeptyl->status) && $historyData->trigger->decapeptyl->status == 'decapeptyl')
                                            <th colspan="2">
                                                <span class="ivf-label">Decapeptyl :</span>
                                                {{'Yes'}}
                                            </th>
                                        @endif
                                        @if(!empty($historyData->trigger->decapeptyl->status) && $historyData->trigger->decapeptyl->status == 'decapeptyl')
                                            @if(!empty($historyData->trigger->decapeptyl->time))
                                                <th>
                                                    <span class="ivf-label">  Decapeptyl Time : </span>
                                                    {{$historyData->trigger->decapeptyl->time}}
                                                </th>
                                            @endif
                                            @if(!empty($historyData->trigger->decapeptyl->dose) )
                                                <th>
                                                    <span class="ivf-label">  Decapeptyl Dose : </span>
                                                    {{$historyData->trigger->decapeptyl->dose}}
                                                </th>
                                            @endif
                                            @if(!empty($historyData->trigger->decapeptyl->brand) )
                                                <th>
                                                    <span class="ivf-label">  Decapeptyl Brand : </span>
                                                    {{$historyData->trigger->decapeptyl->brand}}</td>
                                                </th>
                                            @endif
                                        @endif
                                    </tr>
                                @endif
                            @endif
                            @if(in_array('trigger',$collectionData))
                                @if(!empty($historyData->trigger->dualtrigger->stauts) && $historyData->trigger->dualtrigger->stauts == 'dualtrigger')
                                    <tr>
                                        <th colspan="2">
                                            <span class="ivf-label"> DualTrigger :</span>
                                            {{'Yes'}}
                                        </th>
                                    </tr>
                                @endif
                            @endif
                            @if(in_array('blood',$collectionData))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Blood : </span>
                                        {{'Yes'}}
                                    </th>
                                    @if(!empty($historyData->blood->report))
                                        <th>
                                            <span class="ivf-label">Blood Report Details</span>
                                            {{$historyData->blood->report}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                        @endif

                        @if($plan != 3)
                            @if (in_array('usg',$collectionData))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Usg</span>
                                        {{'Yes'}}
                                    </th>
                                </tr>
                            @endif
                        @endif
                        @if(!empty($historyData->p_s->type))
                            <tr>
                                <th>
                                    <span class="ivf-label">P/S:</span>
                                    {{$historyData->p_s->details}}
                                </th>
                            </tr>
                        @endif
                        @if($plan != 1 )
                            @if(in_array('transfer',$collectionData))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Transfer</span>
                                        {{'Yes'}}
                                    </th>
                                    @if(!empty($historyData->transfer->payment))
                                        <th>
                                            <span class="ivf-label">Payment</span>
                                            {{$historyData->transfer->payment}}
                                        </th>
                                    @endif
                                    @if(!empty($historyData->transfer->payby))
                                        <th>
                                            <span class="ivf-label">Payment By</span>
                                            {{$historyData->transfer->payby}}
                                        </th>
                                    @endif
                                </tr>
                            @endif
                        @endif
                        @if(!empty($historyData->skip_reason))
                            <tr>
                                <th>
                                    <span class="ivf-label">Reason</span>
                                    {{$historyData->skip_reason}}
                                </th>
                            </tr>
                        @endif
                        @if($visit != 2  && $cycle == $cycle)
                            @if(!empty($historyData->remark))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Remark</span>
                                        {{$historyData->remark}}
                                    </th>
                                </tr>
                            @endif
                        @endif

                        @php
                            $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
                        @endphp

                        {{-- @if(!empty($historyData->plan))
                            <tr>
                                <th>
                                    <span class="ivf-label">Transfer Plan</span>
                                    {{$planData[$historyData->plan] }}
                                </th>
                            </tr>
                        @endif --}}
                    </tbody>
                </table>
                        @if($plan != 2 && $cycle == $cycle)
                            <table class="module-report-table">
                                <tbody>
                                    @if(!empty($ivf->investigation))
                                        @php
                                            $historyWifeInvestigation = (isset(json_decode($ivf->investigation)->wife)) ? json_decode($ivf->investigation)->wife : null;
                                            $historyHubInvestigation = (isset(json_decode($ivf->investigation)->hub)) ? json_decode($ivf->investigation)->hub : null;
                                            $investigationValueDetails = [];
                                            $investigationReport = $investigationReport['reportData'];
                                        @endphp
                                        @if(!empty($historyWifeInvestigation))
                                            <tr>
                                                <th>
                                                    @php
                                                        
                                                        $investigationData = [];
                                                        $investigationValueDetails['wife'] = [];
                                                        $data = (!empty($historyWifeInvestigation->investigation_data)) ? $historyWifeInvestigation->investigation_data : [];
                                                        $investigationValueData = (array)$historyWifeInvestigation->investigation_details;
                                                        foreach($data as $key => $value){
                                                            if(!empty($investigationValueData[$value])){
                                                                $investigationValueDetails['wife'][$investigationReport[$value]] = $investigationValueData[$value];
                                                            }else{
                                                                $investigationData[] = $investigationReport[$value];
                                                            }
                                                        }
                                                    @endphp
                                                    @if(count($investigationData)>0)
                                                        <span class="ivf-label">Investigation Advise for wife : </span>{{implode(',',$investigationData)}}
                                                    @endif
                                                </th>
                                            </tr>
                                        @endif
                                        @if(!empty($historyHubInvestigation))
                                            <tr>
                                                <th>
                                                    @php
                                                        
                                                        $investigationData = [];
                                                        $investigationValueDetails['hub'] = [];
                                                        $data = !empty($historyHubInvestigation->investigation_data) ? $historyHubInvestigation->investigation_data : [];
                                                        $investigationValueData = (array)$historyHubInvestigation->investigation_details;
                                                        foreach($data as $key => $value){
                                                            if(!empty($investigationValueData[$value])){
                                                                $investigationValueDetails['hub'][$investigationReport[$value]] = $investigationValueData[$value];
                                                            }else{
                                                                $investigationData[] = $investigationReport[$value];
                                                            }
                                                        }
                                                    @endphp
                                                    @if(count($investigationData)>0)
                                                        <span class="ivf-label">Investigation Advise for Husband: </span>{{implode(',',$investigationData)}}
                                                    @endif
                                                </th>
                                            </tr>
                                        @endif
                                        @if(!empty($investigationValueDetails['hub']) || !empty($investigationValueDetails['wife']))
                                            <br>
                                            <table class="module-report-table w-30">
                                                <thead>
                                                    <tr>
                                                        <th>Investigation</th>
                                                        <th>Wife</th>
                                                        <th>Husband</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($investigationReport as $key => $value)
                                                        @if(array_key_exists($value,$investigationValueDetails['hub']) || array_key_exists($value,$investigationValueDetails['wife']))
                                                        <tr>
                                                            <td>{{$value}}</td>
                                                            <td>{{!empty($investigationValueDetails['wife'][$value]) ? $investigationValueDetails['wife'][$value] : '-'}}</td>
                                                            <td>{{!empty($investigationValueDetails['hub'][$value]) ? $investigationValueDetails['hub'][$value] : '-'}}</td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        @endif
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
                @if(!empty($historyData->protocol) && (!empty($injectionArray) || !empty($hmgArray) || !empty($hmgBrandArray) || !empty($fshArray) || !empty($antagonistArray) || !empty($fshBrandArray)))
                    <table class="module-report-table">
                        <thead>
                            <tr>
                                <th>Cycle Days</th>
                                <th>S.Days</th>
                                <th>Date</th>
                                <th>Injection</th>
                                <th>HMG</th>
                                <th>H.Brand Name</th>
                                <th>FSH</th>
                                <th>F.Brand Name</th>
                                <th>Antagonist</th>
                                {{-- <th>Time</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historyData->protocol as $row)
                                @if(!empty($row->hmg) || !empty($row->hmg_brand_name) || !empty($row->fsh) || !empty($row->fsh_brand_name) || !empty($row->antagonist))
                                    <tr>
                                        <td>{{!empty($row->day) ? $row->day : '-'}}</td>
                                        <td>{{!empty($row->s_day) ? 's'.$row->s_day : '-'}}</td>
                                        <td>{{$row->date}}</td>
                                        <td>{{!empty($row->injection) ? $injectionData[$row->injection] : '-'}}</td>
                                        <td>{{!empty($row->hmg) ? $row->hmg : '-'}}</td>
                                        <td>{{!empty($row->hmg_brand_name) ? $row->hmg_brand_name : '-'}}</td>
                                        <td>{{!empty($row->fsh) ? $row->fsh : '-'}}</td>
                                        <td>{{!empty($row->fsh_brand_name) ? $row->fsh_brand_name : '-'}}</td>
                                        <td>{{!empty($row->antagonist) ? $row->antagonist : '-'}}</td>
                                        {{-- <td>{{$row->time}}</td> --}}
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
                
                @if(!empty($historyData->medicinedata))
                    @php
                        $historyTreatmentView = null;
                        if(!empty($historyData->medicinedata)){
                            $historyTreatmentView = $historyData->medicinedata;
                        }
                    @endphp
                    @if($historyTreatmentView)
                    <table class="module-report-table">
                        <thead>
                            
                            <tr>
                                <th>
                                    Treatment (Medicine)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
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
                                    @foreach ($historyData->medicinedata as $row)
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
                        </tbody>
                    </table>
                    @endif
                @endif
                <tr>
                    <th>
                        <br>
                        <span class="ivf-label">Follow Up:</span>
                        {{!empty($historyData->follow_up) ? \Carbon\Carbon::parse($historyData->follow_up)->format('D d M Y') : '-'}}
                    </th>
                </tr>
            @else
                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                    <tbody>
                        @if(!empty($historyData->transfer->upt_type))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Upt Type : </span>&nbsp;
                                    </th>
                                    <td colspan="8">
                                        {{ ucfirst($historyData->transfer->upt_type)}}
                                    </td>
                                </tr>
                        @endif
                        @if(!empty($historyData->transfer->result_type))
                                <tr>
                                    <th>
                                        <span class="ivf-label">Result Type : </span>&nbsp;
                                    </th>
                                    <td colspan="8">
                                        {{ ucfirst($historyData->transfer->result_type)}}
                                    </td>
                                </tr>
                        @endif
                        @if(!empty($historyData->transfer->follow_up))
                            <tr>
                                <th>
                                    <span class="ivf-label">Follow Up : </span>&nbsp;
                                </th>
                                <td colspan="8">
                                    {{ ucfirst($historyData->transfer->follow_up)}}
                                </td>
                            </tr>
                        @endif
                        @if(!empty($historyData->co_type))
                            <tr>
                                <th>
                                    <span class="ivf-label">C/O :</span>
                                    @if(!empty($historyData->co_type))
                                        {{implode(',',$historyData->co_type)}}
                                    @else
                                        'None'
                                    @endif
                                </th>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if(!empty($historyData->medicinedata))
                        <h5 class="col-md-2 ">Treatment</h5>
                    @php
                        $historyTreatmentView = null;
                        if(!empty($historyData->medicinedata)){
                            $historyTreatmentView = $historyData->medicinedata;
                        }
                    @endphp
                    @if($historyTreatmentView)
                    <table class="module-report-table">
                        <thead>
                            @php
                                $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>'Weekly / 1','6'=>'Weekly / 2','7'=>'Stat','8'=>'SOS'];
                                // unset($historyTreatmentView->medicinedata);
                            @endphp
                            <tr>
                                <tr>
                                    <th>Name</th>
                                    <th>Dose</th>
                                    <th>Timing</th>
                                    <th>Freq.</th>
                                    <th>Duration</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historyData->medicinedata as $row)
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
                @endif
            @endif
            </div>
        @endif
        @if($isIvfHistory == '3')
            <style>
                @page { margin-top : 5px; margin-bottom : 80px;}
            </style>
            <div class="ivf-print-data">
                <div class="row mb-2 patient-detail">
                    <div class="col-md-12">
                        <strong>{{ ucwords(strtolower('Coming for transfer'))}}</strong>
                    </div>
                    <div class="col-md-12 patient-name">
                        <strong>{{ ucwords(strtolower($ivf->getPatients['name']))}}</strong>
                    </div>
                    <div class="col-md-12">
                        <strong>{{ $ivf->getPatients['mobile_number'] }}</strong>
                    </div>
                </div>
                <br>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <span>Transfer Date : </span><span> {{$transferDate}}</span>
                    </div>
                    <div class="col-md-12">
                        {{!empty($remark) ? $remark : ''}}
                    </div>
                </div>
            </div>
        @endif
        @if($isIvfHistory == '4')
            <style>
                @page { margin-top : 5px; margin-bottom : 80px;}
            </style>
            <div class="panel panel-primary transfer-print">
                <table class="table m-b-0 table-hover ivf-print-table">
                    <tbody>
                    <tr>
                        <th class="p-main-title">
                            {{ucwords(strtolower($ivf->getPatients['name']))}}
                        </th>
                    </tr>
                    <tr>
                        <th class="p-main-title">
                            {{$ivf->getPatients['mobile_number'] }}
                        </th>
                    </tr>
                    <tr>
                        <td>
                            Trigger date :- &nbsp;&nbsp;
                            {{(\Carbon\Carbon::parse($historyData->trigger_date)->format('D d M Y'))}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Time :- &nbsp;&nbsp;
                            {{!empty($historyData->trigger->hcg->time) ? $historyData->trigger->hcg->time : (!empty($historyData->trigger->decapeptyl->time) ? $historyData->trigger->decapeptyl->time : null)}}
                        </td>
                    </tr>

                    {{-- @if(!empty($historyData->trigger->decapeptyl->time)) --}}
                    <tr>
                        <td>
                            Pickup date :- &nbsp;&nbsp;
                            @php
                                $nowDate = \Carbon\Carbon::parse($historyData->trigger_date)->format('Y-m-d');
                                $nowTime = \Carbon\Carbon::parse(!empty($historyData->trigger->hcg->time) ? $historyData->trigger->hcg->time : (!empty($historyData->trigger->decapeptyl->time) ? $historyData->trigger->decapeptyl->time : null))->format('H:i:s');
                                $triggerDateTime = \Carbon\Carbon::parse($nowDate.' '.$nowTime)->addHours(35)->format('Y-m-d H:i:s');
                                $triggerDate = \Carbon\Carbon::parse($triggerDateTime)->format('D d M Y');
                            @endphp
                            {{$triggerDate}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Time  :- &nbsp;&nbsp;
                            {{\Carbon\Carbon::parse($triggerDateTime)->format('h:i a')}}
                        </td>
                    </tr>
                    @if(!empty($historyData->trigger->hcg->status))
                        <tr>
                            <td>Injection Name :- &nbsp;&nbsp; HCG</td>
                            @if(!empty($historyData->trigger->hcg->dose))
                                <td>Dose :- &nbsp;&nbsp;  {{$historyData->trigger->hcg->dose}}</td>
                            @endif
                            @if(!empty($historyData->trigger->hcg->brand))
                                <td>Brand :- &nbsp;&nbsp;  {{$historyData->trigger->hcg->brand}}</td>
                            @endif
                        </tr>
                    @endif
                    @if(!empty($historyData->trigger->decapeptyl->status))
                        <tr>
                            <td>Injection Name:- &nbsp;&nbsp; Decapeptyl</td>
                            @if(!empty($historyData->trigger->decapeptyl->dose))
                                <td>Dose :- &nbsp;&nbsp;{{$historyData->trigger->decapeptyl->dose}}</td>
                            @endif
                            @if(!empty($historyData->trigger->decapeptyl->brand))
                                <td>Brand :- &nbsp;&nbsp;{{$historyData->trigger->decapeptyl->brand}}</td>
                            @endif
                        </tr>
                    @endif
                    @if(isset($historyData->trigger->ovutring) && !empty($historyData->trigger->ovutring->status))
                        <tr>
                            <td>Injection Name:- &nbsp;&nbsp; Ovutring</td>
                            @if(!empty($historyData->trigger->ovutring->dose))
                                <td>Dose :- &nbsp;&nbsp;{{$historyData->trigger->ovutring->dose}}</td>
                            @endif
                            @if(!empty($historyData->trigger->ovutring->brand))
                                <td>Brand :- &nbsp;&nbsp;{{$historyData->trigger->ovutring->brand}}</td>
                            @endif
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
    @elseif(isset($isTableView) && $isTableView == '1')
        <style>
            @page { margin-top : 200px; margin-bottom : 80px;}
        </style>
        @if(!isset($printPreview) && (!isset($isAppointmentView) || $isAppointmentView == false))
            <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" >
        @endif
        @if($isTableView == '1' && $plan == 1)
            <?php
                $hmgDose = 0;
                $antaDose = 0;
                $fshDose = 0;
                $se2Data = [];
                $sp2Data = [];
                $slhData = [];
                $bloodReport = [];
                $collectionData = [];
                $i=0;
                $cycle_no = count($ivfCycleData);
                $lastHistory = $ivfCycleData[count($ivfCycleData)-1];
                $lastHistoryData = !empty($lastHistory->description) ? json_decode($lastHistory->description) : null;
            ?>
            <div class="row mb-5 do_print">
                <div class="col-md-12"><h4 class="text-center font-22"><u><b>FROZEN EMBRYO TRANSFER STUDY</b></u></h4></div>
            </div>
            <div class="row follicular-table mb-15 mb-5 text-left">{{--mb-15 is used in print--}}
                <div class="col-md-7 col-sm-7 follicular_div_1">
                    <div class="mb-2">
                        <span class="visit-lable">Name :- </span> 
                        <span class="visit-lable-value">{{ucwords($ivf->getPatientsDetails['name'])}}</span>
                    </div>
                    <div class="mb-2">
                            <span class="visit-lable">AGE :- </span> 
                            <span class="visit-lable-value">{{$ivf->getPatientsDetails['age']}}</span>
                    </div>
                    <div class="mb-2">
                            <span class="visit-lable">Type & Year of infertility :- </span> 
                            <span class="visit-lable-value">{{!empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility] : 'Primary'}} / {{!empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' years' : null}} {{!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' years' : null}}</span>
                    </div>
                    <div class="mb-2">
                            <span class="visit-lable">L.M.P :- </span> 
                            <span class="visit-lable-value">{{!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : null}}</span>
                    </div>
                    <div class="mb-2">
                        <span class="visit-lable">Weight :- </span> 
                        <span class="visit-lable-value">{{isset($lastHistoryData->weight) && !empty($lastHistoryData->weight) ? $lastHistoryData->weight.' kg' : ''}}</span>
                </div>
                </div>
                <div class="col-md-5 col-sm-5 follicular_div_2">
                    <div class="mb-2">
                        <span class="visit-lable">Plan :- </span> 
                        <span class="visit-lable-value">ovarian stimulation</span>
                </div>
                    <div class="mb-2">
                        <span class="visit-lable">UTERUS :- </span> 
                        <span class="visit-lable-value">{{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ut->details) && $ivfSecondVisitData->oe->ut->ut_type == 2 ? $ivfSecondVisitData->oe->ut->details : 'Normal'}}</span>
                    </div>
                    <div class="mb-2">
                        <div class="row">
                            <div class="col-md-3 visit-lable">
                            OVARIES :- 
                            </div>
                            <div class="col-md-9 pl-15">
                                <div class="mb-2">R :- {{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ovary->right->afcs) ? $ivfSecondVisitData->oe->ovary->right->afcs : null}}</div>
                                <div>L :- {{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ovary->left->afcs) ? $ivfSecondVisitData->oe->ovary->left->afcs : null}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row follicular-table mb-3">
                <div class="col-md-12">
                    
                    <table class="module-report-table study-report-table mb-2">
                        <thead>
                            <tr>
                                <th class="text-secondary">Visit Date</th>
                                @if(!isset($pt_view) || $pt_view != 1) 
                                    <th class="text-secondary">Day of <br> menses</th>
                                    <th class="text-secondary">Simulation<br> Days</th>
                                    <th class="text-secondary">Date</th>
                                    <th class="text-secondary">Injection</th>
                                    <th class="text-secondary">HMG</th>
                                    <th class="text-secondary">HMG Brand Name</th>
                                    <th class="text-secondary">FSH</th>
                                    <th class="text-secondary">FSH Brand Name</th>
                                    <th class="text-secondary">Antagonist</th>
                                @endif
                                <th class="text-secondary">Rt. Ovary</th>
                                <th class="text-secondary">Lt. Ovary</th>
                                <th class="text-secondary">ET</th>
                                <th class="text-secondary">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ivfCycleData as $row)
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
                                    if(!empty($data->s_p2)){
                                        $sp2Data[] = $data->s_p2;
                                    }
                                    if(!empty($data->blood->report)){
                                        $bloodReport[] = $data->blood->report;
                                    }
                                    $skipValue = 0;
                                    $duringPickupStatus = !empty($data->during_pickup) ? ucfirst($data->during_pickup) : null;
                                    $collectionData = !empty($historyData->collection) ? $historyData->collection : [];
                                    if((!empty($historyData->plan) || !empty($historyData->follow_up)) && !empty($historyData->skip_reason))
                                    {
                                        $skipValue = 1;
                                    }
                                @endphp
                                @if($row->visit == 2)
                                    @php
                                        $ivfExtraVisit = IvfExtraVisit::where('patient_id',$row->patients_id)->whereCycleNo($row->cycle_no)->where('plan',$row->plan)->where('created_at','<',$row->created_at)->orderBy('id','ASC')->get();
                                    @endphp
                                    @if(!empty($ivfExtraVisit))
                                            @foreach($ivfExtraVisit as $ivfExtra)
                                            <tr >
                                                <td>{{\Carbon\Carbon::parse($ivfExtra->created_at)->format('d-m-Y')}}</td>
                                                @if(!isset($pt_view) || $pt_view != 1)
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>{{'Extra Visit'}}</td>
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
                                        {{-- @if($pStatus == 1) --}}
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
                                                @php
                                                    $j=0;
                                                @endphp
                                                @if(!isset($pt_view) || $pt_view != 1)
                                                    @foreach ($historyData->protocol as $row)
                                                        @php
                                                            $j++;
                                                        @endphp
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
                                                                        {{isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? ' Other Report: '.$historyData->investigation_extra : ''}}
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
                                                   
                                                @else
                                                <tr>
                                                    <td>{{!empty($datarow->created_at) ? \Carbon\Carbon::parse($datarow->created_at)->format('d-m-Y'):''}}</td>
                                                    <td>
                                                            @if($datarow->visit == 2)
                                                                {{!empty($data->oe->ovary->right->afcs) ? $data->oe->ovary->right->afcs : '-'}}
                                                            @else
                                                                {{!empty($data->ovary->ovary_type->right->details) ? $data->ovary->ovary_type->right->details : '-'}}
                                                            @endif
                                                    </td>
                                                    <td>
                                                        
                                                            @if($datarow->visit == 2)
                                                                {{!empty($data->oe->ovary->left->afcs) ? $data->oe->ovary->left->afcs : '-'}}
                                                            @else
                                                                {{!empty($data->ovary->ovary_type->left->details) ? $data->ovary->ovary_type->left->details : '-'}}
                                                            @endif
                                                    </td>
                                                    <td>{{!empty($data->et_details) ? $data->et_details : '-'}}
                                                    </td>
                                                    <td>
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
                                                            {{(!isset($pt_view) || $pt_view != 1) ? $historyData->remark : (isset($historyData->pt_remark) ? $historyData->pt_remark : '')}}
                                                            {{-- {{!empty($historyData->remark) ? $historyData->remark : ''}} --}}

                                                    </td>
                                                </tr>
                                                @endif
                                            @endif
                                            
                                        {{-- @endif --}}
                                    @endif
                                    
                                {{-- </div> --}}
                            @endforeach
                            @php
                                $ivfExtraVisit = IvfExtraVisit::where('patient_id',$lastHistory->patients_id)->whereCycleNo($lastHistory->cycle_no)->where('plan',$lastHistory->plan)->where('created_at','>',$datarow->created_at)->orderBy('id','ASC')->get();
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
                                        
                                    </tr>
                                    @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                @if (in_array('trigger',$collectionData) && !empty($historyData->trigger_date))
                    <div class="col-md-12">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            @php
                                $nowDate = \Carbon\Carbon::parse($historyData->trigger_date)->format('Y-m-d');
                                $nowTime = \Carbon\Carbon::parse(!empty($historyData->trigger->hcg->time) ? $historyData->trigger->hcg->time : (!empty($historyData->trigger->decapeptyl->time) ? $historyData->trigger->decapeptyl->time : null))->format('H:i:s');
                                $triggerDateTime = \Carbon\Carbon::parse($nowDate.' '.$nowTime)->addHours(35)->format('Y-m-d H:i:s');
                                $triggerDate = \Carbon\Carbon::parse($triggerDateTime)->format('D d M Y');
                            @endphp
                            
                            <span>Trigger date :- &nbsp;&nbsp; {{(\Carbon\Carbon::parse($historyData->trigger_date)->format('D d M Y'))}}</span><br>
                            <span>Time :- &nbsp;&nbsp;  {{!empty($historyData->trigger->hcg->time) ? $historyData->trigger->hcg->time : (!empty($historyData->trigger->decapeptyl->time) ? $historyData->trigger->decapeptyl->time : null)}}</span><br>
                            <span>Pickup date :- &nbsp;&nbsp; {{$triggerDate}}</span><br>
                            <span>Time  :- &nbsp;&nbsp;{{\Carbon\Carbon::parse($triggerDateTime)->format('h:i a')}}</span><br>
                            @if(!empty($historyData->trigger->hcg->status))
                                        <span>Injection Name :- &nbsp;&nbsp; HCG</span>
                                            @if(!empty($historyData->trigger->hcg->dose))
                                                <span>Dose :- &nbsp;&nbsp;  {{$historyData->trigger->hcg->dose}}</span>
                                            @endif
                                            @if(!empty($historyData->trigger->hcg->brand))
                                                <span>Brand :- &nbsp;&nbsp;  {{$historyData->trigger->hcg->brand}}</span>
                                            @endif
                            @endif
                            
                            @if(!empty($historyData->trigger->decapeptyl->status))
                                        <span>Injection Name:- &nbsp;&nbsp; Decapeptyl</span>
                                            @if(!empty($historyData->trigger->decapeptyl->dose))
                                                <span>Dose :- &nbsp;&nbsp;{{$historyData->trigger->decapeptyl->dose}}</span>
                                            @endif
                                            @if(!empty($historyData->trigger->decapeptyl->brand))
                                                <span>Brand :- &nbsp;&nbsp;{{$historyData->trigger->decapeptyl->brand}}</span>
                                            @endif
                            @endif
                            @if(isset($historyData->trigger->ovutring) && !empty($historyData->trigger->ovutring->status))
                                        <span>Injection Name:- &nbsp;&nbsp; Ovutring</span>
                                            @if(!empty($historyData->trigger->ovutring->dose))
                                                <span>Dose :- &nbsp;&nbsp;{{$historyData->trigger->ovutring->dose}}</span>
                                            @endif
                                            @if(!empty($historyData->trigger->ovutring->brand))
                                                <span>Brand :- &nbsp;&nbsp;{{$historyData->trigger->ovutring->brand}}</span>
                                            @endif
                            @endif 
                        </div> 
                    </div>
                @endif
                @if(!empty($lastHistoryData->plan) && $lastHistory->cycle_status == 2 && (!isset($pt_view) || $pt_view != 1))
                    <div class="col-md-12 mt-3 text-left">
                        <span class="visit-lable">Transfer Plan :- </span> 
                        <span class="visit-lable-value">{{isset($planData[$lastHistoryData->plan])? $planData[$lastHistoryData->plan] : ''}}</span>
                    </div>
                @endif
                @if($skipValue == 1) {{-- skip cycle --}}
                    @php
                            $visitDate = \Carbon\Carbon::parse($datarow->created_at)->format('d-m-Y');
                            $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $datarow->created_at)->diffInDays(\Carbon\Carbon::parse($visitDate));
                            $diff = $diff + 1;
                    @endphp
                    <div class="col-md-12 mt-3">
                        <h5 class="">Skip Cycle:</h5>
                        <table class="module-report-table study-report-table mt-3">
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
                    </div>
                @endif
                @if(!empty($lastHistoryData->transfer->result_type))
                    @php
                        $visitDate = \Carbon\Carbon::parse($datarow->created_at)->format('d-m-Y');
                        $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $datarow->created_at)->diffInDays(\Carbon\Carbon::parse($visitDate));
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
                                    <td>{{$lastHistoryData->transfer->upt_type}}</td>
                                    <td>{{$lastHistoryData->transfer->result_type}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="col-md-12 mt-3">
                    <h3 class="text-left"><u>Medicine:</u></h3>
                    <table class="module-report-table study-report-table">
                        <thead>
                            <tr>
                                <th style="font-weight: bold !important;"> Date</th>
                                <th style="font-weight: bold !important;"> Medicine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ivfCycleData as $row)
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
                                                                |{{$medicine_time[$time]}}
                                                                @endforeach
                                                            @else
                                                            |{{$medicine_time[$row->medicine_time]}}
                                                            @endif
                                                    @endif
                                                @endif
                                            </div>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if($isTableView == '1' && $plan != 1)
        @php 
            $lastHistory = $ivfCycleData[count($ivfCycleData)-1];
            $lastHistoryData = !empty($lastHistory->description) ? json_decode($lastHistory->description) : null;
        @endphp
            {{-- {{print_r($ivfCycleData)}} --}}
            <div class="row mb-5 do_print">
                <div class="col-md-12"><h4 class="text-center font-22"><u><b>FROZEN EMBRYO TRANSFER STUDY</b></u></h4></div>
            </div>
            <div class="row follicular-table mb-15 mb-5 text-left">
                <div class="col-md-7 col-sm-7 follicular_div_1">
                    <div class="mb-2">
                        <span class="visit-lable">Name :- </span> 
                        <span class="visit-lable-value">{{ucwords(strtolower($ivf->getPatientsDetails['name']))}}</span>
                    </div>
                    <div class="mb-2">
                            <span class="visit-lable">Age / Weight :- </span> 
                            <span class="visit-lable-value">{{$ivf->getPatientsDetails['age'].' / '.(isset($lastHistoryData->weight) && !empty($lastHistoryData->weight) ? $lastHistoryData->weight.' kg' : '')}}</span>
                    </div>
                    <div class="mb-2">
                            <span class="visit-lable">Type & Year of infertility :- </span> 
                            <span class="visit-lable-value">{{!empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility] : 'Primary'}} / {{!empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' years' : null}} {{!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' years' : null}}</span>
                    </div>
                    <div class="mb-2">
                            <span class="visit-lable">L.M.P :- </span> 
                            <span class="visit-lable-value">{{!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : null}}</span>
                    </div>
                    
                    @if($plan == 3)
                        <div class="mb-2">
                            <span class="visit-lable">Semen Freezing :- </span> 
                            {{-- <span class="visit-lable-value">{{$historySemenFreezing}}</span> --}}
                        </div>
                    @endif
                </div>
                <div class="col-md-5 col-sm-5 follicular_div_2">
                    <div class="mb-2">
                        <span class="visit-lable">UTERUS :- </span> 
                        <span class="visit-lable-value">{{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ut->details) && $ivfSecondVisitData->oe->ut->ut_type == 2 ? $ivfSecondVisitData->oe->ut->details : 'Normal'}}</span>
                    </div>
                    <div class="mb-2">
                        <div class="row">
                            <div class="col-md-3 visit-lable">
                            OVARIES :- 
                            </div>
                            <div class="col-md-9 pl-15">
                                <div class="mb-2">R :- {{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ovary->right->afcs) ? $ivfSecondVisitData->oe->ovary->right->afcs : null}}</div>
                                <div>L :- {{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->ovary->left->afcs) ? $ivfSecondVisitData->oe->ovary->left->afcs : null}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <span class="visit-lable">Endometrial Thickness :- </span> 
                        <span class="visit-lable-value">{{isset($ivfSecondVisitData->oe) && !empty($ivfSecondVisitData->oe->endometrial_cavity->size) ? $ivfSecondVisitData->oe->endometrial_cavity->size : ''}}</span>
                    </div>
                    @if($plan == 3 || $plan == 4)
                        <div class="mb-2">
                            <span class="visit-lable">Embroy Ready :- </span> 
                            {{-- <span class="visit-lable-value">{{$historyEmbroyReady}}</span> --}}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row follicular-table mb-3">
                <div class="col-md-12">
                    <table class="module-report-table study-report-table mb-2">
                        <thead>
                            <tr>
                                <th style="width:8% !important;">Date</th>
                                <th style="width:5% !important">Day of Menses</th>
                                <th style="width:15% !important">Endometrial Thickness / Pattern</th>
                                <th style="width:5px;">Vascularity of Endometrium</th>
                                <th style="width: 20% !important;">Drugs </th>
                                <th style="width: 20% !important;">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($ivfCycleData as $row)
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
                                    $pStatus = $row->plan;
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
                                            </tr>
                                            @endforeach
                                    @endif
                                @endif
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
                                                <a href="javascript:void(0);" id="ivf_transfer_report_update">
                                                    IVF Transfer Report
                                                </a>
                                        @endif
                                    </td>
                                    <td class="">
                                        {{(!isset($pt_view) || $pt_view != 1) ? (isset($historyData->remark) ? $historyData->remark : '') : (isset($historyData->pt_remark) ? $historyData->pt_remark : '')}}
                                        {{isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? ' Other Report: '.$historyData->investigation_extra : ''}}

                                    </td>
                                </tr>
                                @if(isset($historyData->progesterone_date) && (!empty($historyData->progesterone->type)) && (!empty($historyData->progesterone_date)))
                                    <tr>
                                        <td>{{\Carbon\Carbon::parse($historyData->progesterone_date)->format('d-m-Y')}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{'Progesterone start'}}</td>
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
                                            </tr>
                                            @endforeach
                                    @endif
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(!empty($lastHistoryData->plan) && $lastHistory->cycle_status == 2 && (!isset($pt_view) || $pt_view != 1))
                    <div class="col-md-12 mt-3 text-left">
                        <span class="visit-lable">Transfer Plan :- </span> 
                        <span class="visit-lable-value">{{isset($planData[$lastHistoryData->plan])? $planData[$lastHistoryData->plan] : ''}}</span>
                    </div>
                @endif
                @if($skipValue == 1) {{-- skip cycle --}}
                    @php
                            $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                            $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $row->created_at)->diffInDays(\Carbon\Carbon::parse($visitDate));
                            $diff = $diff + 1;
                    @endphp
                    <div class="col-md-12 mt-3">
                        <h3 class="text-left"><u>Skip Cycle:</u></h3>
                        <table class="module-report-table study-report-table mt-3">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    @if((!isset($pt_view) || $pt_view != 1))
                                        <th>Transfer Plan</th>
                                    @endif
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$visitDate}}</td>
                                    @if((!isset($pt_view) || $pt_view != 1))
                                        <td>{{$planData[$lastHistoryData->plan]}}</td>
                                    @endif
                                    <td>{{$lastHistoryData->skip_reason}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(!empty($lastHistoryData->transfer->result_type))
                    @php
                        $visitDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                        $diff = \Carbon\Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : $row->created_at)->diffInDays(\Carbon\Carbon::parse($visitDate));
                        $diff = $diff + 1;
                    @endphp
                    <div class="col-md-12 mt-3">
                        <h3 class="">Result:</h3>
                        <table class="module-report-table study-report-table">
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
                    </div>
                @endif
                <div class="col-md-12 mt-3">
                    <h3 class="text-left"><u>Medicine:</u></h3>
                    <table class="module-report-table study-report-table">
                        <thead>
                            <tr>
                                <th style="font-weight: bold !important;"> Date</th>
                                <th style="font-weight: bold !important;"> Medicine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ivfCycleData as $row)
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
                                                                |{{$medicine_time[$time]}}
                                                                @endforeach
                                                            @else
                                                            |{{$medicine_time[$row->medicine_time]}}
                                                            @endif
                                                    @endif
                                                @endif
                                                @if(isset($row->note) && !empty($row->note))
                                                | Note: {{$row->note}}
                                                @endif
                                            </div>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    @else
        @php
        $co = !empty($ivfExtraVisit->co) ? json_decode($ivfExtraVisit->co) : null;
        $lmp = !empty($ivfExtraVisit->lmp) ? json_decode($ivfExtraVisit->lmp): null;
        $oe = !empty($ivfExtraVisit->oe) ? json_decode($ivfExtraVisit->oe) : null;
        $treatment = !empty($ivfExtraVisit->treatment) ? json_decode($ivfExtraVisit->treatment) : null;
        
        @endphp
        <style>
         @page { margin-top : 200px; margin-bottom : 80px;}
         </style>
        <div class="{{'panel panel-primary '.(isset($printPreview) && $printPreview == 1 ? 'watermark' : '')}}">
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                        <tr>
                            <th>
                                <span class="ivf-label">Name : </span>{{ ucwords(strtolower($ivfPatients->name))}}
                                    @php
                                        $gender = ($ivfPatients->gender == 2) ? 'F' : 'M';
                                    @endphp
                                    <br><span class="ivf-label">Age : </span>{{ $ivfPatients->age. ' years | '.$gender }}
                            </th>
                            <th class="float-right">
                                <span class="ivf-label">Visit Date:  </span>{{Carbon\Carbon::parse($ivfExtraVisit->created_at)->format('d/m/Y')}}
                                @if($ivfPatients->weight)
                                    <br><span class="ivf-label">Weight: </span>{{isset($lmp->weight) && !empty($lmp->weight) ? $lmp->weight.' kg' : $ivfPatients->weight.' kg'}}
                                @endif
                            </th>
                        </tr>
                        
                </tbody>
            </table>
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                    @if(!empty($co) && !empty($co->co_type) || !empty($co->since))
                        <tr>
                            <th>
                                <span class="ivf-label">C/O :</span>
                                {{ (isset($co->co_type) && is_array($co->co_type)) ? implode(', ', $co->co_type) : 'None' }}
                                @if(!empty($co->since))
                                    <span class="ivf-label">Since </span>
                                    {{ !empty($co->since) ? $co->since : '-' }}
                                @endif
                            </th>
                        </tr>
                    @endif
                    @if(!empty($lmp->date))
                        <tr>
                            <th>
                                <br>
                                <span class="ivf-label"> LMP Date: </span>
                                {{ !empty($lmp->date) ? \Carbon\Carbon::parse($lmp->date)->format('d/m/Y') : '-' }}
                            </th>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if($oe  && ($oe->tvs->type == 'yes' || $oe->p_s->type == 'yes' || !empty($oe->cervix->details) || !empty($oe->le->bp) || !empty($oe->le->temp) || !empty($oe->le->pulse)))
              
                <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                    <tbody>
                        <tr>
                            <br>  
                            <td colspan="9">
                                <div class="panel-title header-print-title">O/E</div>
                            </td>
                        </tr>
                        <tr>
                            @if(!empty($oe->le->temp) || !empty($oe->le->bp) || !empty($oe->le->pulse))
                                <th class=" w-100">
                                    Vitals
                                    @if(!empty($oe->le->temp))
                                        <br>
                                        <span class="ivf-label">Temp : </span>
                                        {{$oe->le->temp}}
                                    @endif
                                    @if(!empty($oe->le->pulse))
                                        <br>
                                        <span class="ivf-label">Pulse : </span>
                                        {{$oe->le->pulse ? $oe->le->pulse : '80'}} / Min
                                    @endif
                                    @if(!empty($oe->le->bp))
                                        <br>
                                        <span class="ivf-label">B.P :</span>
                                        {{$oe->le->bp ? $oe->le->bp : '110/70'}} MMHG
                                    @endif
                                </th>
                            @endif
                        </tr>
                        @if($oe->p_s->type == 'yes')
                            <tr>
                                <th>
                                    <span class="ivf-label">P / S:</span>
                                    {{ !empty($oe->p_s->type == 'yes') ? 'Yes' : 'No' }}
                                    @if ($oe->p_s->type == 'yes')
                                        {{!empty($oe->p_s->details) ? '| '.$oe->p_s->details : '-' }}
                                    @endif
                                </th>
                            </tr>
                        @endif
                        @if(!empty($oe->cervix->details))
                            <tr>
                                <th>
                                    <span class="ivf-label">Cervix:  </span>
                                    {{ !empty($oe->cervix->details) ? $oe->cervix->details : '-' }}
                                </th>
                            </tr>
                        @endif
                        @if($oe->tvs->type == 'yes')
                            <tr>
                                <th>
                                    <span class="ivf-label">Transvaginal Ultrasonography:</span>
                                </th>
                            </tr>
                        @endif
                        @if ($oe->tvs->type == 'yes')
                            <tr>
                                <th>
                                    <span class="ivf-label">Uterus:  </span>
                                    {{ !empty($oe->uterus->type == '2') ? 'Abnormal' : 'Normal' }}
                                </th>
                                @if ($oe->uterus->type == '2')
                                    <th>
                                        <span class="ivf-label">Abnormal Details:  </span>
                                        {{ !empty($oe->uterus->details) ? $oe->uterus->details : '-' }}
                                    </th>
                                @endif
                            </tr>
                        @endif
                        @if ($oe->tvs->type == 'yes' && !empty($oe->endometrial_thickness))
                            <tr>
                                <th>
                                    <span class="ivf-label">Endometrial Thickness:  </span>
                                    {{ !empty($oe->endometrial_thickness) ? $oe->endometrial_thickness : '-' }}
                                </th>
                            </tr>
                        @endif
                        @if (!empty($oe->ovary->right->updated_details) || !empty($oe->ovary->right->afcs))
                        <tr>
                            <th>
                                @if (!empty($oe->ovary->right->updated_details))
                                <span class="ivf-label">Right Ovary : </span>
                                    @foreach ($oe->ovary->right->updated_details as $key => $value)
                                        @php
                                            echo !empty($value) ? $value .  '<br />' : '- <br />';
                                        @endphp
                                    @endforeach
                                @endif
                                @if(!empty($oe->ovary->right->afcs))
                                    <span class="ivf-label">Follicle numbers per ovary : </span>
                                    {{$oe->ovary->right->afcs}}
                                @endif
                            </th>
                        </tr>
                        @endif
                        @if(!empty($oe->ovary->left->updated_details) || !empty($oe->ovary->left->afcs))
                        <tr>
                            <th>
                                @if(!empty($oe->ovary->left->updated_details))
                                <span class="ivf-label">Left Ovary : </span>
                                    @foreach($oe->ovary->left->updated_details as $key => $value)
                                        @php
                                            echo !empty($value) ? $value .  '<br />' : '- <br />';
                                        @endphp
                                    @endforeach
                                @endif
                                @if(!empty($oe->ovary->left->afcs))
                                    <span class="ivf-label">Follicle numbers per ovary : </span>
                                    {{$oe->ovary->left->afcs}}
                                @endif
                            </th>
                        </tr>
                        @endif
                        
                        
                    </tbody>
                </table>
            @endif
            @if(!empty($treatment))
            @php
                unset($treatment->medicinedata);
            @endphp
                @if(count((array)$treatment) > 0)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                        <tbody>
                            <tr>
                                <br>
                                <td colspan="9">
                                    <div class="panel-title header-print-title">Treatment</div>
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
                                            <td>{{$row->no.' days'}}</td>
                                            <td>{{isset($row->note) && !empty($row->note) ? $row->note : '-'}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </tbody>
                    </table>
                @endif
            @endif
            @if(isset($oe->investigation_extra) && !empty($oe->investigation_extra))
                <span class="font-bold">Other Reports : </span>{{$oe->investigation_extra}}
            @endif
            <br><span class="font-bold">Remark : </span>{{isset($oe->pt_remark) && !empty($oe->pt_remark) ? $oe->pt_remark : ''}}
            
        </div>
        
        @if(isset($oe->follow_up) && !empty($oe->follow_up))
                    <h3 class="text-center">{{"ફરીવાર ".\Carbon\Carbon::parse($oe->follow_up)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h3>
        @endif
    @endif

</div>
@if(isset($printPreview) && $printPreview != 0)

    @endsection
@endif

