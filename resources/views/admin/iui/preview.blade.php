@extends(isset($printPreview) && $printPreview == 1 ? 'layouts.printpreview' : 'layouts.printPreviewBlank')

@php
    use App\Models\IuiHistory;
    $patientsInfo = !empty($iui->patients_info) ? json_decode($iui->patients_info) : null;
    $ho = !empty($iui->h_o) ? json_decode($iui->h_o) : null;
    $co = !empty($iui->c_o) ? json_decode($iui->c_o) : null;
    $oh = !empty($iui->o_h) ? json_decode($iui->o_h) : null;
    $mh = !empty($iui->m_h) ? json_decode($iui->m_h) : null;
    $hoRx = !empty($iui->ho_rx) ? json_decode($iui->ho_rx) : null;
    $investigation = !empty($iui->investigation) ? json_decode($iui->investigation) : null;
    $husbandFactor = !empty($iui->husband_factor) ? json_decode($iui->husband_factor) : null;
    $patientDetailedHO = !empty($iui->patients_details_ho) ? json_decode($iui->patients_details_ho) : null;
    $oe = !empty($iui->o_e) ? json_decode($iui->o_e) : null;
    $planManagement = !empty($iui->plan_management) ? json_decode($iui->plan_management) : null;
    $possibleCaseOfInfertility = !empty($iui->possible_case_of_infertility) ? json_decode($iui->possible_case_of_infertility) : null;
    $treatment = !empty($iui->treatment) ? json_decode($iui->treatment) : null;
    $visit = $iui->visit;
    $description = json_decode($iui->description);
    $lmp = !empty($description->lmp) ? $description->lmp : null;
    $plan = !empty($description->plan) ? $description->plan : null;
    $inducing = !empty($description->inducing) ? $description->inducing : null;
    $ovary = !empty($description->ovary) ? $description->ovary : null;
    $hcg = !empty($description->hcg) ? $description->hcg : null;
    if($visit == 2 || $visit == 3 || $visit == 4){
        $patientsInfo = $iui->getPatientsInfoData;
        $oe = !empty($description->oe) ? $description->oe : null;
        $coData = !empty($description->co_type) ? $description->co_type : null;
        $treatment = !empty($description->treatment) ?$description->treatment : null;
    }
    if($visit == 2){
        $treatmentNo = 5;
    }
    if($visit == 3){
        $treatmentNo = 3;
    }
    $contraceptionData = ['barrier_method'=>'Barrier Method','cu_t'=>'Cu - T','tl_done'=>'TL Done ','occipill'=>'Occipill','other_contraception'=>'Other'];
    $noValueData = [];
    $secondNoValueData = [];
    $medqty = ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    $follow_up_case = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI'];
    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];

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
    .main-print-iui-div{
        margin: 0 auto;
        width: 100%;
    }
    @media print {
     {page-break-after: always;}
    }
    .iui-print-data{
        text-align: left;
        width: 50%;
        margin: 0 auto;
    }
    .study-report{
        border: 1px solid black;
        margin: 20px;
    }
    .study-form{
        padding: 7px !important;
    }
    .report-title{
        padding: 4px 10px !important;
        text-align: right;
        font-weight: bold;
    }
    .study-report-table{
        font-family: auto !important;
    }
    .study-report-table th{
        padding: 8px !important;
    }
    .study-report-table td{
        padding: 8px !important;
    }
    .iui-label{
        font-weight: normal;
    }
    .w-200{
        width: 200px !important;
    }
    .w-300{
        width: 300px !important;
    }
    /* th
    {
        font-weight: 100;
    } */

    .print-panel-primary{
        border: 1px solid;
        padding: 11px;
    }
    /* .follicular-table .follicular_div_1
    {
        width: 65%;
    }
    .follicular-table .follicular_div_2
    {
        width: 35%;
    } */
    .follicular-table .visit-lable
    {   font-weight: bold;
        color: black !important;
        font-size: 16px;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
        -webkit-print-color-adjust: exact;
    }
    .follicular-table td,.follicular-table th
    {   
        padding: .8rem .2rem !important;
        text-align: center !important;
        border: 1px solid black !important;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        -webkit-print-color-adjust: exact;
        white-space: inherit !important;
    }
    .medicine-table td{
        padding: 2px 15px;
        text-transform: capitalize;
    }
    .remark-text
    {
        color: red;
    }
    .pl-2
    {
        padding-left: 2.5rem;
    }
    .panel-primary
    {
        border:none !important;
    }
    .font-bold
    {
        font-weight : 700px;
    }
    
    @page { margin-top : 200px; margin-left : 100px;}
    .card .body .follicular-table th,  .card .body .table .follicular-table td{
    padding: .8rem .2rem !important;
    text-align: center !important;
    white-space: inherit !important;
}
.follicular-table tbody, .follicular-table thead
{
    background-color: #f7fdf7 !important;
    -webkit-print-color-adjust: exact;
}
.card .body .follicular-table th{
    white-space: inherit !important;
    font-size: 16px;
    line-height: 18px !important;
    font-weight: bold !important;
}
.follicular-table .visit-lable
{
    font-weight: bold;
    color: black !important;
    font-size: 16px;
}
.table.follicular-table th, .table.follicular-table td{
    padding: .8rem .2rem !important;
    text-align: center !important;
    white-space: inherit !important;
}
    
</style>
@if(isset($printPreview) && $printPreview != 0)
    @section('content')
@endif  
@if(empty($iui->study_report))
    <div class="main-print-iui-div">
        @if(!$iui->iui_print)
            <div class="{{'panel print-panel-primary '.(isset($printPreview) && $printPreview == 1 ? 'watermark' : '')}}">
                @if($visit == null)
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                        <tbody>
                        @if($patientsInfo)
                            <tr>
                                <th>
                                    <span class="iui-label" style="padding-bottom: 30px;">Name : </span>{{ ucwords(strtolower($iui->getPatientsInfo->name)) . ' / ' . $patientsInfo->age.' years' }}
                                </th>
                                <td>
                                <th style="padding-bottom: 30px;text-align: right"><span class="iui-label">Visit Date:  </span>{{Carbon\Carbon::parse($iui->created_at)->format('d/m/Y')}}
                                    @if($iui->getPatientsInfo->weight)
                                    <br>Weight: {{$iui->getPatientsInfo->weight.' kg'}}
                                    @endif
                                </th>
                            </tr>
                        @endif
                        @if(isset($patients_remark) && !empty($patients_remark))
                            <tr>
                            <th><span class="iui-label">Remark : </span>{{$patients_remark}}</th>
                            </tr>
                        @endif
                        @if($ho)
                            @if(!empty($ho->ho_details))
                            <tr>
                                <th>
                                    <span class="iui-label">H/O : </span>
                                    {{!empty($ho->ho_details) ? $ho->ho_details : '-' }}
                                </th>
                            </tr>
                            @endif
                            @if (!empty($ho->ho_type))
                                <tr>
                                    <th>
                                        <span class="iui-label">H/O Type :</span>
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
                                </tr>
                            @endif
                            @if(!empty($ho->le->bp) && !empty($ho->le->vitals_status) && $ho->le->vitals_status == 'yes' && (!empty($ho->le->temp) || !empty($ho->le->pulse)))
                                <tr>
                                    <th>
                                        <span class="iui-label">Vitals :</span>
                                        @if(!empty($ho->le->temp))
                                            <br>
                                            <span class="iui-label">Temp :</span>
                                            {{$ho->le->temp}}
                                        @endif
                                        @if(!empty($ho->le->bp))
                                            <br>
                                            <span class="iui-label">B.P :</span>
                                            {{$ho->le->bp}} MMHG
                                        @endif
                                        @if(!empty($ho->le->pulse))
                                            <br>
                                            <span class="iui-label">Pulse :</span>
                                            {{$ho->le->pulse}} / Min
                                        @endif
                                    </th>
                                </tr>
                            @endif
                        @endif
                        <tr>
                            <th>
                                <span class="iui-label">C/O :</span>
                                {{ (isset($co->co_type) && is_array($co->co_type)) ? implode(', ', $co->co_type) : 'None' }}
                                @if(!empty($co->since))
                                    <span class="iui-label">Since </span>
                                    {{ !empty($co->since) ? $co->since : '-' }}
                                @endif
                            </th>
                        </tr>
                        </tbody>
                    </table>

                    @if($oh)
                        <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                            <tbody>
                                <tr>
                                    <td colspan="6">
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
                                                <span class="iui-label">Marriage Life :</span>
                                                {{$oh->first_marriage_life}}
                                            </th>
                                        @endif
                                        @if(!empty($oh->active_marriage_life))
                                            <th>
                                                <span class="iui-label">Active Marriage Life :</span>
                                                {{$oh->active_marriage_life}}
                                            </th>
                                        @endif
                                        @if(!empty($oh->upt_type))
                                            <th>
                                                <span class="iui-label">UTP :</span>
                                                {{$oh->upt_type  == 'positive' ? 'Positive' : 'Negative'}}
                                            </th>
                                        @endif
                                        @if(!empty($oh->type_of_infertility))
                                            <th>
                                                <span class="iui-label">Type Of Infertility :</span>
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
                                                <span class="iui-label ">H/O :</span>
                                                @php
                                                    $hoValue = null;
                                                    if(!empty($row->ho_term)){
                                                        $hoValue.= $row->ho_term  == 'full' ? 'FTND' : 'PT';
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
                                                    }
                                                @endphp
                                                {{$hoValue}}
                                            </th>
                                        </tr>
                                    @endforeach
                                @else
                                    @php
                                        $noValueData[] = ' Child';
                                    @endphp
                                @endif
                                @if(!empty($oh)  && $oh->mtp_no != null && $oh->mtp_no != 0 )
                                    <tr>
                                        @if(!empty($oh->mtp->mtp_data))
                                            <th>
                                                MTP :
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($oh)  && $oh->mtp_no != null && $oh->mtp_no != 0 )
                                    @foreach($oh->mtp->mtp_data as $key=>$row)
                                    {{-- @if($row->mtp_status == 'yes')
                                        <tr>
                                            <th>
                                                <span class="iui-label ">MTP :</span>
                                                {{ !empty($row->mtp_status) && $row->mtp_status == 'yes' ? 'Yes' : 'No' }}
                                                @if (array_key_exists($row->ho_type, $ho_type_array))
                                                    {{ '('.$ho_type_array[$row->ho_type].')' }}
                                                @endif
                                            </th>
                                            @php
                                                $mtpStatus = 'd-none';
                                                if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                                    $mtpStatus = '';
                                                }
                                            @endphp
                                            @if (!empty($row->mtp_status) && $row->mtp_status == 'yes' && !empty($row->mtp_type))
                                                <th>
                                                    <span class="iui-label ">MTP Type : </span>
                                                    @php
                                                        if(!empty($row->mtp_type)) {
                                                            echo ($row->mtp_type == 'medically') ? 'Medically' : 'Surgically';
                                                        }
                                                    @endphp
                                                </th>
                                                @if(!empty($row->mtp_month_of_pregancy))
                                                    <th>
                                                        <span class="iui-label ">Month Of Pregnancy : </span>
                                                        {{$row->mtp_month_of_pregancy}}
                                                    </th>
                                                @endif
                                            @endif
                                            
                                            @php
                                                $hoTypeValue = [2,3,4];
                                                $dNone = '';
                                                if (!in_array($row->ho_type,$hoTypeValue)) {
                                                    $dNone = 'd-none';
                                                }
                                            @endphp
                                            @if($dNone == '' && !empty($row->when_where))
                                                <th>
                                                    <span class="iui-label ">When / Where :</span>
                                                    {{!empty($row->when_where) ? $row->when_where : ''}}
                                                </th>
                                            @endif
                                        </tr>
                                    @endif --}}
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
                                @if(!empty($oh->abortion_no) && !empty($oh->abortion_no)|| !empty($oh->abortion_no) || !empty($oh->abortion->when_where))
                                    <tr>
                                        @if(!empty($oh->abortion_no))
                                            <th>
                                                Abortion :
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($oh) && ($oh->abortion_no != null && $oh->abortion_no != 0 ))
                                    @foreach($oh->abortion->abortion_data as $key=>$value)
                                        @php
                                            $numberKey = addOrdinalNumberSuffix($key);
                                            $firstAbortionData = $numberKey;
                                        @endphp
                                        <tr>
                                            {{-- <th>
                                                <span class="iui-label ">Spontancous Abortion :</span> --}}
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
                                                            $firstAbortionData .= ' '.$value->when_where;
                                                        }
                                                    }
                                                    if($isBracket == 1){
                                                        $firstAbortionData .= ')';
                                                    }
                                                @endphp
                                                <td >
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
                                            <span class="iui-label">Contraception Method :</span> {{$contraceptionData[$oh->contraception->contraception_data]}}
                                        </th>
                                    </tr>
                                @else
                                    @php
                                        $noValueData[] = ' Contraception';
                                    @endphp
                                @endif
                                @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes')
                                    <tr>
                                        <th class=" w-300">
                                            <span class="iui-label">Second Merriage Life :</span>
                                            @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life))
                                                {{ $oh->second_marriage_life == 'yes' ? 'Yes' : 'No' }}
                                            @else
                                                -
                                            @endif
                                        </th>
                                        @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh->second_marriage_details))
                                            <th>
                                                <span class="iui-label ">Second Merriage Details :</span>
                                                {{$oh->second_marriage_details}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if (isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh->second_marriage->child_no))
                                    <tr>
                                        @if(!empty($oh->second_marriage->child_no) )
                                            <th>
                                                <span class="iui-label ">Child No : </span>
                                                {{$oh->second_marriage->child_no}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh) && $oh->second_marriage->child_no != null && $oh->second_marriage->child_no != 0)
                                    @foreach($oh->second_marriage->child->child_data as $key=>$row)
                                        <tr>
                                            <th>
                                                <span class="iui-label ">H/O :</span>
                                                @php
                                                    $secondHoValue = null;
                                                    if(!empty($row->ho_term)){
                                                        $secondHoValue.= $row->ho_term  == 'full' ? 'FT' : 'PT';
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
                                                    if(!empty($row->expired_year)){
                                                        $secondHoValue.= '-'.$row->expired_year;
                                                    }
                                                    if(!empty($row->ho_type) && $oh->second_marriage->child_no != 0 ){
                                                        if(array_key_exists($row->ho_type, $ho_type_array)){
                                                            $secondHoValue.= ' ('.$ho_type_array[$row->ho_type].')';
                                                            $hoType = [2,3,4];
                                                            $dNone = '';
                                                            if (!in_array($row->ho_type,$hoType)) {
                                                                $dNone = 'd-none';
                                                            }
                                                            if($oh->second_marriage->child_no != null && $oh->second_marriage->child_no != 0 && $dNone == '' && !empty($row->when_where)){
                                                                $secondHoValue.= ' - '.$row->when_where;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                {{$secondHoValue}}
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
                                                <span class="iui-label ">MTP : </span>{{$oh->second_marriage->mtp_no}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($oh->second_marriage_life) && !empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' && !empty($oh)  && $oh->second_marriage->mtp_no != null && $oh->second_marriage->mtp_no != 0)
                                    @foreach($oh->second_marriage->mtp->mtp_data as $key=>$row)
                                        {{-- @if($row->mtp_status == 'yes')
                                            <tr>
                                                <th>
                                                    <span class="iui-label ">MTP :</span>
                                                    {{ !empty($row->mtp_status) && $row->mtp_status == 'yes' ? 'Yes' : 'No' }}
                                                </th>
                                                @php
                                                    $mtpStatus = 'd-none';
                                                    if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                                        $mtpStatus = '';
                                                    }
                                                @endphp
                                                @if (!empty($row->mtp_status) && $row->mtp_status == 'yes' && !empty($row->mtp_type))
                                                    <th>
                                                        <span class="iui-label ">MTP Type : </span>
                                                        @php
                                                            if(!empty($row->mtp_type)) {
                                                                echo ($row->mtp_type == 'medically') ? 'Medically' : 'Surgically';
                                                            }
                                                        @endphp
                                                    </th>
                                                    @if(!empty($row->mtp_month_of_pregancy))
                                                        <th>
                                                            <span class="iui-label ">Month Of Pregnancy : </span>
                                                            {{$row->mtp_month_of_pregancy}}
                                                        </th>
                                                    @endif
                                                @endif
                                                
                                                @if(!empty($oh->second_marriage->mtp_no) && $oh->second_marriage->mtp_no != 0 )
                                                    <th>
                                                        <span class="iui-label ">MTP HO Type :</span>
                                                        @if (array_key_exists($row->ho_type, $ho_type_array))
                                                            {{ $ho_type_array[$row->ho_type] }}
                                                        @endif
                                                    </th>
                                                @endif
                                                @php
                                                    $hoTypeValue = [2,3,4];
                                                    $dNone = '';
                                                    if (!in_array($row->ho_type,$hoTypeValue)) {
                                                        $dNone = 'd-none';
                                                    }
                                                @endphp
                                                @if ($dNone == '' && !empty($row->when_where))
                                                    <th>
                                                        <span class="iui-label ">When / Where :</span>
                                                        {{!empty($row->when_where) ? $row->when_where : ''}}
                                                    </th>
                                                @endif
                                            </tr>
                                        @endif --}}
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
                                            <span class="iui-label ">Abortion :</span>
                                            {{-- {{!empty($oh->second_marriage->abortion_no) ? $oh->second_marriage->abortion_no : 0}} --}}
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
                                                    $isBracket = 0;
                                                    $secondAbortionData = $numberKey;
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
                                            <span class="iui-label">Contraception Method : </span> {{$contraceptionData[$oh->second_marriage->contraception->contraception_data]}}
                                        </th>
                                    </tr>
                                @else
                                    @php
                                        $secondNoValueData[] = ' Contraception';
                                    @endphp
                                @endif
                                @if(!empty($noValueData))
                                    <tr>
                                        <th>
                                            {{'No '.implode(',',$noValueData)}}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($oh->remark))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Remark :</span>
                                            {{$oh->remark}}
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif

                    @if($mh)
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">

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
                                                <span class="iui-label">Age Of Menarchy : </span>
                                                {{ $mh->age_of_menarchy }}
                                            </th>
                                        @endif

                                        @if (!empty($mh->since_year))
                                            <th>
                                                <span class="iui-label">Since Year :</span>
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
                                            <span class="iui-label">Present / Past M/H :</span>
                                        @endif
                                        @if ($mh->past_mh_2 != 'regular') | IR Regular @endif
                                            @if (!empty($mh->past_interval_of_day) || $mh->past_mh_2 == 'regular')
                                        | Duration Of Menstruation: {{$mh->past_mh_2 == 'regular' ? '3 - 4 day' : $mh->past_interval_of_day}}
                                        @endif
                                        @if (!empty($mh->past_duration_of_day) || $mh->past_mh_2 == 'regular')
                                                at Interval Of : {{$mh->past_mh_2 == 'regular' ? '28 - 30 day' : $mh->past_duration_of_day}}
                                        @endif
                                        @if($mh->past_mh_2 != 'regular')
                                        | {{ !empty($mh->past_month) ? ucwords($mh->past_month) : ''}}
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
                                            <span class="iui-label">Present M/H : </span>
                                            {{ ucwords($mh->present_mh_1) }}
                                            | @if ($mh->present_mh_2 == 'regular')Regular @else IR Regular @endif
                                            @if (!empty($mh->present_duration_of_day) || $mh->present_mh_2 == 'regular')
                                            | Duration Of Menstruation : {{$mh->present_mh_2 == 'regular' ? '28 - 30 day' : $mh->present_duration_of_day}}
                                            @endif
                                            @if (!empty($mh->present_interval_of_day) || $mh->present_mh_2 == 'regular')
                                            at Interval Of : {{$mh->present_mh_2 == 'regular' ? '3 - 4 day' : $mh->present_interval_of_day}}
                                            @endif
                                            @if($mh->present_mh_2 != 'regular')
                                                | {{ !empty($mh->present_month) ? ucwords($mh->present_month) : ''}}
                                            @else
                                                Regular, Moderate, Painless
                                            @endif
                                            @if(!empty($mh->present_withdrawal_medicine) && $mh->present_withdrawal_medicine == 'yes')
                                                | Withdrawal by Medicine 
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    @if(!empty($mh->last_menstrual_date))
                                        <th>
                                            <span class="iui-label">Last Menstrual Date :</span>
                                            {{!empty($mh->last_menstrual_date) ?  \Carbon\Carbon::parse($mh->last_menstrual_date)->format('d/m/Y') : '-' }}
                                            <br>
                                            @if (isset($mh->lmd_date_diff) && !empty($mh->lmd_date_diff))
                                            <span class="iui-label">Day of mense :</span>
                                            {{ $mh->lmd_date_diff}}
                                            @endif
                                        </th>
                                    @endif
                                    @if(!empty($mh->since_cycle))
                                        <th>
                                            <span class="iui-label">Since Month :</span>
                                            {{!empty($mh->since_month) ?  $mh->since_month : '-' }}
                                        </th>
                                    @endif
                                    @if(!empty($mh->since_cycle))
                                        <th>
                                            <span class="iui-label">Since Cycle :</span>
                                            {{!empty($mh->since_cycle) ?  $mh->since_cycle : '-' }}
                                        </th>
                                    @endif
                                </tr>

                            </tbody>
                        </table>
                    @endif

                    @if($hoRx && (!empty($hoRx->taken) && !empty($hoRx->taken->status) && $hoRx->taken->status == 'yes') || (!empty($hoRx->iui) && !empty($hoRx->iui->status) && $hoRx->iui->status == 'yes') || (!empty($hoRx->ivf) && !empty($hoRx->ivf->status) && $hoRx->ivf->status == 'yes'))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">History of Rx taken</div>
                                    </td>
                                </tr>
                                @if(isset($hoRx->taken->status) && ($hoRx->taken->status == 'yes') && isset($hoRx->taken->how_much_no) && $hoRx->taken->how_much_no > 0)
                                    <tr>
                                        @if(isset($hoRx->taken->how_much))
                                            <td style="width: 10%">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @foreach($hoRx->taken->how_much as $key => $value)
                                                        @if(!empty($value))
                                                            <tr>
                                                                <td >
                                                                    {{$value}}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            </td>
                                        @endif
                                        @if (isset($hoRx->taken->type))
                                            <td colspan="4">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @if (isset($hoRx->taken->type))
                                                        @foreach($hoRx->taken->type as $key => $value)
                                                            @if(!empty(array_filter($value)))
                                                                <tr>
                                                                    <td >
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
                                                </table>
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($hoRx->iui->status) && $hoRx->iui->status == 'yes')
                                    @if(!empty($hoRx->iui->details))
                                        <tr>
                                            <th>
                                                <span class="iui-label"></span>
                                                {{$hoRx->iui->details}}
                                            </th>
                                        </tr>
                                    @endif
                                    @if(isset($hoRx->iui->how_much_no) && ($hoRx->iui->how_much_no > 0))
                                        <tr>
                                            @if (isset($hoRx->iui->how_much))
                                                <td style="width: 10%">
                                                    <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                        @foreach($hoRx->iui->how_much as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td >
                                                                        {{$value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                </td>
                                            @endif
                                            @if (isset($hoRx->iui->when_where))
                                                <td>
                                                    <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @foreach($hoRx->iui->when_where as $key => $value)
                                                        @if(!empty($value))
                                                            <tr>
                                                                <td >
                                                                    {{$value}}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </table>
                                                </td>
                                           @endif
                                           @if (isset($hoRx->iui->type))
                                                <td colspan="4">
                                                    <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @foreach($hoRx->iui->type as $key => $value)
                                                        @if(!empty(array_filter($value)))
                                                            <tr>
                                                                <td >
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
                                                    </table>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                @endif
                                @if($hoRx->ivf->status == 'yes')
                                    @if(isset($hoRx->ivf->status) && isset($hoRx->ivf->how_much_no) && $hoRx->ivf->how_much_no > 0)
                                        <tr>
                                            <td style="width: 10%">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @if (isset($hoRx->ivf->how_much))
                                                        @foreach($hoRx->ivf->how_much as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td >
                                                                        {{$value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @if (isset($hoRx->ivf->when_where))
                                                        @foreach($hoRx->ivf->when_where as $key => $value)
                                                            @if(!empty($value))
                                                                <tr>
                                                                    <td >
                                                                        {{'When / Where: ' . $value}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </td>
                                            <td colspan="4">
                                                <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                                                    @php
                                                        if (isset($hoRx->ivf->type)) {
                                                            $medicines = collect($hoRx->ivf->type)->toArray();
                                                            $medicineKeys = array_keys($medicines);
                                                        }
                                                    @endphp
                                                    @if (isset($hoRx->ivf->type))
                                                        @for ($i = 1; $i <= $hoRx->ivf->how_much_no; $i++)
                                                            <tr>
                                                                <td >
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
                                @endif
                            </tbody>
                        </table>
                    @endif

                    @if($husbandFactor && !empty($husbandFactor->occupation) || !empty($husbandFactor->seman_analysis) || !empty($husbandFactor->habbit) || !empty($husbandFactor->sperm_count) || !empty($husbandFactor->personal_history_date) || !empty($husbandFactor->remark))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
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
                                                <span class="iui-label">Occupation:  </span>
                                                {{ !empty($husbandFactor->occupation) ? $husbandFactor->occupation : '-' }}
                                                <br>
                                                @if(!empty($husbandFactor->age))
                                                    <span class="iui-label">Age:  </span>
                                                    {{ !empty($husbandFactor->age) ? $husbandFactor->age : '-' }}
                                                @endif
                                                <br>
                                                @if(!empty($husbandFactor->habbit))
                                                    <span class="iui-label"> Habbit:  </span>
                                                    {{ !empty($husbandFactor->habbit) ? $husbandFactor->habbit : '-' }}
                                                @endif
                                            </th>
                                        @endif
                                        @if(!empty($husbandFactor->seman_analysis))
                                            <th>
                                                <span class="iui-label">Semen Analysis:  </span>
                                                @if (!empty($husbandFactor->seman_analysis))
                                                    @if ($husbandFactor->seman_analysis == 1)
                                                        WNL
                                                    @elseif ($husbandFactor->seman_analysis == 2)
                                                        Oligospermia
                                                    @elseif ($husbandFactor->seman_analysis == 3)
                                                        Azoospermic
                                                    @endif
                                                @endif
                                                <br>
                                                @if(!empty($husbandFactor->sperm_count))
                                                    <span class="iui-label"> Sperm Count:  </span>
                                                    {{ !empty($husbandFactor->sperm_count) ? $husbandFactor->sperm_count : '-' }}
                                                @endif
                                                <br>
                                                @if(!empty($husbandFactor->motility))
                                                    <span class="iui-label"> Motility:  </span>
                                                    {{ !empty($husbandFactor->motility) ? $husbandFactor->motility : '-' }}
                                                @endif
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($husbandFactor->amount_in_ml) || !empty($husbandFactor->personal_history_date))
                                    <tr>
                                        @if(!empty($husbandFactor->amount_in_ml))
                                            <th>
                                                <span class="iui-label">Amount in ML:  </span>
                                                {{ !empty($husbandFactor->amount_in_ml) ? $husbandFactor->amount_in_ml : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($husbandFactor->personal_history_date))
                                            <th>
                                                <span class="iui-label"> Date:  </span>
                                                {{ !empty($husbandFactor->personal_history_date) ? $husbandFactor->personal_history_date : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if ($husbandFactor->seman_analysis == 2 && (!empty($husbandFactor->medicine) || !empty($husbandFactor->duration) || !empty($husbandFactor->sperm_report)))
                                    <tr>
                                        @if( !empty($husbandFactor->medicine) )
                                            <th>
                                                <span class="iui-label">Medicine:  </span>
                                                {{ !empty($husbandFactor->medicine) ? $husbandFactor->medicine : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($husbandFactor->duration))
                                            <th>
                                                <span class="iui-label">  Duration:  </span>
                                                {{ !empty($husbandFactor->duration) ? $husbandFactor->duration : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($husbandFactor->sperm_report))
                                            <th>
                                                <span class="iui-label">Sperm Report:  </span>
                                                {{ !empty($husbandFactor->sperm_report) ? $husbandFactor->sperm_report : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                <tr>
                                    <th>
                                        <span class="ivf-label">Husband Factor Remark : </span>
                                        {{ !empty($husbandFactor->remark) ? $husbandFactor->remark : '-' }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    @if($patientDetailedHO && (!empty($patientDetailedHO->personal_history_history_type) || !empty($patientDetailedHO->personal_history_date) || !empty($patientDetailedHO->family_history) || !empty($patientDetailedHO->past_history_type)))
                        <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                            <tbody>
                                @php
                                    // $personal_history_type = ['1'=>'NAD','2'=>"Diabetes Mellitus",'3'=>"Thyroid",'4'=>"Heart Disease",'5'=>"Hypertension"];
                                @endphp
                                @if(!empty($patientDetailedHO->personal_history_history_type) && count((array)$patientDetailedHO->personal_history_history_type) > 1)
                                    <tr>
                                        <th>
                                            <span class="iui-label">Personal History :</span>
                                            {{implode(',',$patientDetailedHO->personal_history_history_type)}}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($patientDetailedHO->personal_history_date))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Date :</span>
                                            {{\Carbon\Carbon::parse($patientDetailedHO->personal_history_date)->format('D d M Y')}}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($patientDetailedHO->family_history) && count((array)$patientDetailedHO->family_history) > 1)
                                    <tr>
                                        <th>
                                            <span class="iui-label">Family History :</span>
                                            {{implode(',',$patientDetailedHO->family_history)}}
                                        </th>
                                    </tr>
                                @endif
                                @php
                                    // $personal_past_history_type = ['nad'=>'NAD','tuberculosis_bacillus'=>"Tuberculosis Bacillus",'hypertension'=>"Hypertension",'thyroid'=>"Thyroid",'dm'=>"DM",'appendectomy'=>'Appendectomy','laparoscopy'=>'Laparoscopy'];
                                @endphp

                                @if(!empty($patientDetailedHO->past_history_type) && count((array)$patientDetailedHO->past_history_type) > 1)
                                    <tr>
                                        <th>
                                            <span class="iui-label">Past History :</span>
                                            {{implode(',',$patientDetailedHO->past_history_type)}}
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif

                    @if($oe  && ($oe->tvs->type == 'yes' || $oe->p_s->type == 'yes' || !empty($oe->cervix->details) || !empty($oe->le->bp) || !empty($oe->le->temp) || !empty($oe->le->pulse)))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">On Examination</div>
                                    </td>
                                </tr>
                                @if($oe->p_s->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label">P / S:</span>
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
                                            <span class="iui-label">Cervix:  </span>
                                            {{ !empty($oe->cervix->details) ? $oe->cervix->details : '-' }}
                                        </th>
                                    </tr>
                                @endif
                                @if($oe->tvs->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label">Transvaginal Ultrasonography :</span>
                                        </th>
                                    </tr>
                                @endif
                                @if ($oe->tvs->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label">Uterus:  </span>
                                            {{ !empty($oe->uterus->type == '2') ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($oe->uterus->type == '2')
                                            <th>
                                                <span class="iui-label">Abnormal Details:  </span>
                                                {{ !empty($oe->uterus->details) ? $oe->uterus->details : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if ($oe->tvs->type == 'yes' && !empty($oe->endometrial_thickness))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Endometrial Thickness:  </span>
                                            {{ !empty($oe->endometrial_thickness) ? $oe->endometrial_thickness : '-' }}
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>
                                        <span class="iui-label">Right Ovary</span>
                                        @if (!empty($oe->ovary->right->details))
                                            @foreach ($oe->ovary->right->details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if(!empty($oe->ovary->right->afcs) && isset($mh->lmd_date_diff) && in_array($mh->lmd_date_diff,['2','3','4']))
                                            Follicle numbers per ovary
                                            {{$oe->ovary->right->afcs}}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <span class="iui-label">Left Ovary</span>
                                        @if (!empty($oe->ovary->left->details))
                                            @foreach ($oe->ovary->left->details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if(!empty($oe->ovary->left->afcs) && isset($mh->lmd_date_diff) && in_array($mh->lmd_date_diff,['2','3','4']))
                                            Follicle numbers per ovary
                                            {{$oe->ovary->left->afcs}}
                                        @endif
                                    </th>
                                </tr>
                                {{-- @endif --}}
                                @if(!empty($oe->le) && !empty($oe->le->vitals_status) && $oe->le->vitals_status == 'yes' && (!empty($oe->le->bp) || !empty($oe->le->temp) || !empty($oe->le->pulse)))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Vitals</span>
                                            @if(!empty($oe->le->temp))
                                                <br>
                                                <span class="iui-label">&nbsp;Temp :</span>
                                                {{$oe->le->temp}}
                                            @endif
                                            @if(!empty($oe->le->pulse))
                                                <br>
                                                <span class="iui-label">&nbsp;Pulse :</span>
                                                {{$oe->le->pulse}} / Min
                                            @endif
                                            @if(!empty($oe->le->bp))
                                                <br>
                                                <span class="iui-label"> B.P :</span>
                                                {{$oe->le->bp}} MMHG
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif

                    @if($planManagement && isset($planManagement->plan_of_management_data))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">Plan Management</div>
                                    </td>
                                </tr>
                                @if (isset($planManagement->plan_of_management_data))
                                    @if (in_array('counceling', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Counceling
                                            </th>
                                            <td  colspan="6">
                                                {{ !empty($planManagement->counceling_details) ? $planManagement->counceling_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (in_array('wait_watch', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Wait Watch
                                            </th>
                                            <td  colspan="6">
                                                {{ !empty($planManagement->wait_watch_details) ? $planManagement->wait_watch_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (in_array('management_by_rx', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Management by Rx. {{ !empty($planManagement->management_by_rx_details) ? $planManagement->management_by_rx_details : '-' }}
                                                @if (!empty($planManagement->management_by_rx_data))
                                                    @foreach($planManagement->management_by_rx_data as $key => $value)
                                                        @switch($value)
                                                            @case('1')
                                                            Clomiphene Citrate <br />
                                                            @break
                                                            @case('2')
                                                            Letroze <br />
                                                            @break
                                                        @endswitch
                                                    @endforeach
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if (in_array('hyperstimulation_iui', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Controlled Overian Hyperstimulation With I.U.I
                                            </th>
                                            <td >
                                                {{ !empty($planManagement->hyperstimulation_iui_details) ? $planManagement->hyperstimulation_iui_details : '-' }}
                                            </td>
                                            @if (!empty($planManagement->hyperstimulation_iui_data))
                                                <td >
                                                    @foreach($planManagement->hyperstimulation_iui_data as $key => $value)
                                                        @switch($value)
                                                            @case('1')
                                                                Only Medicine <br />
                                                                @break
                                                            @case('2')
                                                                Medicine + Gonadotropins <br />
                                                                @break
                                                            @case('3')
                                                                Only Gonadotropins <br />
                                                            @break
                                                        @endswitch
                                                    @endforeach
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if (in_array('laproscopy', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Laproscopy
                                            </th>
                                            <td >
                                                {{ !empty($planManagement->laproscopy_details) ? $planManagement->laproscopy_details : '-' }}
                                            </td>
                                            @if (!empty($planManagement->laproscopy_data))
                                                <td >
                                                    @foreach($planManagement->laproscopy_data as $key => $value)
                                                        @switch($value)
                                                            @case('1')
                                                                HSG <br />
                                                                @break
                                                            @case('2')
                                                                Hystroscopy <br />
                                                                @break
                                                            @case('3')
                                                                DHL <br />
                                                            @break
                                                            @case('4')
                                                                Other <br />
                                                            @break
                                                        @endswitch
                                                    @endforeach
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if (in_array('ivf', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                            IVF
                                            </th>
                                            <td >
                                                {{ !empty($planManagement->ivf_details) ? $planManagement->ivf_details : '-' }}
                                            </td>
                                            @if (!empty($planManagement->ivf_data))
                                                <td >
                                                    @foreach($planManagement->ivf_data as $key => $value)
                                                        @switch($value)
                                                            @case('1')
                                                                Self <br />
                                                                @break
                                                            @case('2')
                                                                OD <br />
                                                                @break
                                                            @case('3')
                                                                ED <br />
                                                            @break
                                                        @endswitch
                                                    @endforeach
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if (in_array('male_factor', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Rx. Of Male Factor
                                            </th>
                                            <td  colspan="6">
                                                {{ !empty($planManagement->male_factor_details) ? $planManagement->male_factor_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (in_array('reports', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Reports
                                            </th>
                                            <td  colspan="6">
                                                {{ !empty($planManagement->reports_details) ? $planManagement->reports_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (in_array('induction_gonadotropins_cycle', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Induction Gonadotropins Cycle
                                            </th>
                                            <td  colspan="6">
                                                {{ !empty($planManagement->induction_gonadotropins_cycle_details) ? $planManagement->induction_gonadotropins_cycle_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (in_array('other', $planManagement->plan_of_management_data))
                                        <tr>
                                            <th>
                                                Other
                                            </th>
                                            <td  colspan="6">
                                                {{ !empty($planManagement->other_details) ? $planManagement->other_details : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    @endif

                    @if($possibleCaseOfInfertility && (!empty($possibleCaseOfInfertility->other) || !empty($possibleCaseOfInfertility->infertility_type)))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
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
                                @if(!empty($infertilityType))
                                    <tr>
                                        <th>
                                            {{ ucwords($infertilityType) }}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($possibleCaseOfInfertility->other))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Other </span>
                                            {{ !empty($possibleCaseOfInfertility->other) ? $possibleCaseOfInfertility->other : '-' }}
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    <?php
                        unset($treatment->medicinedata);
                    ?>
                    @if(!empty($treatment) && count((array)$treatment) > 0)
                        <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
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
                                                        if(!empty($row->medicine_time)){
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
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </tbody>
                        </table>
                    @endif

                    @if($investigation || (!empty($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes' || (!empty($investigation->laproscopy) && $investigation->laproscopy->type == 'yes') || (!empty($investigation->hcg) && $investigation->hcg->type == 'yes')))
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="panel-title header-print-title">Investigation</div>
                                    </td>
                                </tr>
                                @if(!empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label">Hystroscopy: </span>
                                            {{($investigation->hystroscopy->type == 'yes') ? 'Yes' : 'No' }}
                                        </th>
                                        @if(isset($investigation->hystroscopy->type) && ($investigation->hystroscopy->type == 'yes'))
                                            <th>
                                                <span class="iui-label">Finding Type: </span>
                                                {{ ($investigation->hystroscopy->finding_type == 1) ? 'Normal' : 'Abnormal' }}
                                            </th>
                                            @if ($investigation->hystroscopy->finding_type == 2)
                                                <th>
                                                    <span class="iui-label">Abnormal Details: </span>
                                                    {{!empty($investigation->hystroscopy->abnormal_details) ? $investigation->hystroscopy->abnormal_details : '-' }}
                                                </th>
                                            @endif
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes')
                                    <tr>
                                        @if($investigation->hystroscopy->finding_date)
                                            <th>
                                                <span class="iui-label">Finding Date:</span>
                                                {{$investigation->hystroscopy->finding_date}}
                                            </th>
                                        @endif
                                        @if($investigation->hystroscopy->finding_details)
                                            <th>
                                                <span class="iui-label">Details: </span>
                                                {{$investigation->hystroscopy->finding_details}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if($investigation->laproscopy->type == 'yes')
                                    <tr>
                                        <th  colspan="9">
                                            <span class="iui-label">Laproscopy:  </span>
                                            @if (!empty($investigation->laproscopy->type))
                                                {{$investigation->laproscopy->type == 'yes' ? 'Yes' : 'No' }}
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->laproscopy->finding_date) || !empty($investigation->laproscopy->laproscopy_type))
                                    <tr>
                                        @if(!empty($investigation->laproscopy->finding_date))
                                            <th>
                                                <span class="iui-label">Date: </span>
                                                {{$investigation->laproscopy->finding_date}}
                                            </th>
                                        @endif

                                        @if (!empty($investigation->laproscopy->laproscopy_type) &&  $investigation->laproscopy->type == 'yes')
                                            <td >
                                                {{ ($investigation->laproscopy->laproscopy_type == 2) ? 'Abnormal' : 'Normal' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="iui-label">RT Tube: </span>
                                            {{($investigation->laproscopy->rt_tube_type == 2) ? 'Abnormal' : 'Normal'}}
                                        </th>
                                        @if ($investigation->laproscopy->rt_tube_type == 2)
                                            <td  colspan="6">
                                                {{ !empty($investigation->laproscopy->rt_tube_details) ? $investigation->laproscopy->rt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="iui-label"> Uterus: </span>
                                            {{ ($investigation->laproscopy->uterus_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->laproscopy->uterus_type == 2)
                                            <td  colspan="9">
                                                {{ !empty($investigation->laproscopy->uterus_details) ? $investigation->laproscopy->uterus_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="iui-label">LT Tube:  </span>
                                            {{ ($investigation->laproscopy->lt_tube_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->laproscopy->lt_tube_type == 2)
                                            <td  colspan="6">
                                                {{ !empty($investigation->laproscopy->lt_tube_details) ? $investigation->laproscopy->lt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->laproscopy->laproscopy_type == 2 && !empty($investigation->laproscopy->other))
                                    <tr>
                                        <th>
                                            <span class="iui-label"> Other:  </span>
                                            {{$investigation->laproscopy->other}}
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->hcg) && $investigation->hcg->type == 'yes')
                                    <tr>
                                        <th  colspan="9">
                                            <span class="iui-label">  HSG: </span>
                                            @if (!empty($investigation->hcg->type))
                                                {{ $investigation->hcg->type == 'yes' ? 'Yes' : 'No' }}
                                            @endif
                                            @if (!empty($investigation->hcg->type) && $investigation->hcg->type == 'yes')
                                                {{ ($investigation->hcg->laproscopy_type == 2) ? ' Abnormal' : ' Normal' }}
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                @if(!empty($investigation->hcg->date) || !empty($investigation->hcg->type))
                                    <tr>
                                        @if(!empty($investigation->hcg->date))
                                            <th>
                                                <span class="iui-label">Date: </span>
                                                {{$investigation->hcg->date}}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->hcg->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="iui-label">RT Tube: </span>
                                            {{ ($investigation->hcg->rt_tube_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->hcg->rt_tube_type == 2)
                                            <td  colspan="9">
                                                {{ !empty($investigation->hcg->rt_tube_details) ? $investigation->hcg->rt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->hcg->laproscopy_type == 2 && $investigation->hcg->uterus_type)
                                    <tr>
                                        <th>
                                            <span class="iui-label"> Uterus: </span>
                                            {{ ($investigation->hcg->uterus_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if($investigation->hcg->uterus_type == 2)
                                            <td  colspan="9">
                                                {{!empty($investigation->hcg->uterus_details) ? $investigation->hcg->uterus_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if ($investigation->hcg->laproscopy_type == 2)
                                    <tr>
                                        <th>
                                            <span class="iui-label"> LT Tube:  </span>
                                            {{ ($investigation->hcg->lt_tube_type == 2) ? 'Abnormal' : 'Normal' }}
                                        </th>
                                        @if ($investigation->hcg->lt_tube_type == 2)
                                            <td  colspan="9">
                                                {{ !empty($investigation->hcg->lt_tube_details) ? $investigation->hcg->lt_tube_details : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->fsh) || !empty($investigation->prolectin) || !empty($investigation->lh))
                                    <tr>
                                        @if(!empty($investigation->fsh))
                                            <th>
                                                <span class="iui-label">  FSH:  </span>
                                                {{ !empty($investigation->fsh) ? $investigation->fsh : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->prolectin))
                                            <th>
                                                <span class="iui-label"> Prolectin: </span>
                                                {{ !empty($investigation->prolectin) ? $investigation->prolectin : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->lh))
                                            <th>
                                                <span class="iui-label">  LH: </span>
                                                {{ !empty($investigation->lh) ? $investigation->lh : '-' }}
                                            </th>
                                        @endif
                                    </tr>
                                @endif
                                @if(!empty($investigation->amh) || !empty($investigation->e2) || !empty($investigation->p2) || !empty($investigation->date_2))
                                    <tr>
                                        @if(!empty($investigation->amh))
                                            <th>
                                                <span class="iui-label"> AMH:  </span>
                                                {{ !empty($investigation->amh) ? $investigation->amh : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->e2))
                                            <th>
                                                <span class="iui-label"> E2:  </span>
                                                {{ !empty($investigation->e2) ? $investigation->e2 : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->p2))
                                            <th>
                                                <span class="iui-label">  P2:  </span>
                                                {{ !empty($investigation->p2) ? $investigation->p2 : '-' }}
                                            </th>
                                        @endif
                                        @if(!empty($investigation->date_2))
                                            <th>
                                                <span class="iui-label">  Date 2:  </span>
                                                {{ !empty($investigation->date_2) ? $investigation->date_2 : '-' }}
                                            </th>
                                        @endif
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
                                                <span class="iui-label">Investigation Advise : {{implode(',',$investigationData)}}</span>
                                            @endif
                                        </th>
                                    </tr>
                                    @if(!empty($investigationValueDetails))
                                        <tr>
                                            <th>
                                                <span class="iui-label">Investigation Done :</span>
                                                @foreach($investigationValueDetails as $key => $value)
                                                    {!! '<span class="iui-label">'.$key.'</span> :' .  $value !!}
                                                @endforeach
                                            </th>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    @endif
                @endif
                @if(isset($visit) && $visit != null)
                    @if($patientsInfo)
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <th>
                                        <span class="iui-label">Name:</span>
                                        {{ ucwords(strtolower($patientsInfo->name)) }}
                                    </th>
                                    <th>
                                        <span class="iui-label">Date: </span>
                                        {{ \Carbon\Carbon::parse( $iui->created_at)->format('D d M Y') }}
                                    </th>
                                    {{-- @if(($visit == 3 || $visit == 4) && !empty($iui->cycle_no))
                                        <th>
                                            <span class="iui-label">Cycle No:</span>
                                            {{$iui->cycle_no}}
                                        </th>
                                    @endif --}}
                                </tr>
                                @if($visit == 4)
                                <tr>
                                    <th>
                                        <span class="iui-label">Age:</span> {{$patientsInfo->age}}
                                    </th>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    
                    <br><br><br>
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                @if(isset($patients_remark) && !empty($patients_remark))
                                    <tr>
                                        <th><span class="iui-label">Remark : </span>{{$patients_remark}}</th>
                                    </tr>
                                @endif
                                <tr>
                                    {{-- @if(!empty($coData)) --}}
                                        <th>
                                            <span class="iui-label">C/O :</span>
                                            @if(!empty($coData))
                                                {{implode(',',$coData)}}
                                            @else
                                                'None'
                                            @endif
                                        </th>
                                    {{-- @endif --}}
                                </tr>
                                @if(!empty($lmp->le) && !empty($lmp->le->vitals_status) && $lmp->le->vitals_status == 'yes' && (!empty($lmp->le->bp) || !empty($lmp->le->temp) || !empty($lmp->le->pulse)))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Vitals</span>
                                            @if(!empty($lmp->le->temp))
                                                <br>
                                                <span class="iui-label">Temp :</span>
                                                {{$lmp->le->temp}}
                                            @endif
                                            @if(!empty($lmp->le->pulse))
                                                <br>
                                                <span class="iui-label">Pulse :</span>
                                                {{$lmp->le->pulse}} / Min
                                            @endif
                                            @if(!empty($lmp->le->bp))
                                                <br>
                                                <span class="iui-label"> B.P :</span>
                                                {{$lmp->le->bp}} MMHG
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                @endif
                @if(isset($visit) && $visit == 2)
                    @if($lmp)
                        <br><br>
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <th>
                                        <span class="iui-label">LMP Date :</span>
                                        {{ !empty($lmp->date) ? \Carbon\Carbon::parse($lmp->date)->format('d/m/Y').' '.(!empty($lmp->lmp_date_diff) ? ' Day of menses '.$lmp->lmp_date_diff.'' : ''): '-' }}
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    @endif

                    @if($oe)
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <th>
                                        @if((isset($oe->oe_type) && !empty($oe->oe_type)))
                                            <span class="iui-label"> O / E :</span>
                                            {{-- {{ (isset($oe->oe_type) && !empty($oe->oe_type)) ? (($oe->oe_type == 'tvs') ? 'dfdf' : 'sdf') : '-' }} --}}
                                            <?php
                                            if((isset($oe->oe_type)) && !empty($oe->oe_type))
                                            {
                                                if($oe->oe_type->type == 'tvs'){
                                                    echo "Transvaginal sonography";
                                                }
                                                if($oe->oe_type->type == 'pa')
                                                {
                                                    echo "Transabdominal sonography";
                                                }
                                                
                                            }
                                            ?>
                                        @endif
                                        @if(isset($oe->ut) && !empty($oe->ut))
                                            @if (isset($oe->ut) && !empty($oe->ut))
                                                <br>
                                                <span class="iui-label"> Uterus:</span>
                                                {{ ($oe->ut->ut_type == 1) ? 'Normal' : 'Abnormal' }}
                                            @endif
                                        @endif
                                        @if ($oe->ut->ut_type == 2 && !empty($oe->ut->details))
                                                <br>
                                            <span class="iui-label"> UT Details: </span>
                                            {{ !empty($oe->ut->details) ? $oe->ut->details : '-' }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if((isset($oe->endometrial_cavity) && !empty($oe->endometrial_cavity->cavity)))
                                            <span class="iui-label"> Endometrial Cavity :</span>
                                        
                                            @if((isset($oe->endometrial_cavity) && !empty($oe->endometrial_cavity->cavity)))
                                                <br>
                                                    <span class="iui-label"> Cavity :</span>
                                                    {{$oe->endometrial_cavity->cavity}}
                                            @endif
                                            @if((isset($oe->endometrial_cavity) && !empty($oe->endometrial_cavity->size)))
                                            <br>
                                                    <span class="iui-label"> Size: </span>
                                                    {{$oe->endometrial_cavity->size}}
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        
                                        @if (!empty($oe->ovary->right->details))
                                        <span class="iui-label">Right Ovary :</span>
                                            @foreach ($oe->ovary->right->details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '-' . '<br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if(!empty($oe->ovary->right->afcs))
                                            <span class="iui-label"> Follicle numbers per ovary: </span>
                                            {{$oe->ovary->right->afcs}}
                                        @endif
                                        <br>
                                        @if(!empty($oe->ovary->right->residual_follicale))
                                            <span class="iui-label"> Right Residual follicle: </span>
                                            {{$oe->ovary->right->residual_follicale}}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        
                                        @if (!empty($oe->ovary->left->updated_details))
                                        <span class="iui-label"> Left Ovary :</span>
                                            @foreach ($oe->ovary->left->updated_details as $key => $value)
                                                @php
                                                    echo !empty($value) ? $value .  '<br />' : '- <br />';
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if(!empty($oe->ovary->left->afcs) )
                                        <span class="iui-label">Follicle numbers per ovary: </span>
                                            {{$oe->ovary->left->afcs}}
                                        @endif
                                        <br>
                                        @if(!empty($oe->ovary->left->residual_follicale))
                                            <span class="iui-label"> Left Residual follicle: </span>
                                            {{$oe->ovary->left->residual_follicale}}
                                        @endif
                                    </th>
                                </tr>
                                <tr><th></th><tr>
                                <tr><th></th><tr>
                                <tr><th></th><tr>
                                @if(!empty($oe->p_s->type))
                                    <tr>
                                        <th>
                                            <span class="iui-label">P/S</span>
                                        </th>
                                        <td >{{$oe->p_s->details}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    @if($plan)
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    @if(!empty($plan->plan_type))
                                        <th>
                                            <span class="iui-label">Plan: </span>
                                            {{$plan->plan_type}}
                                        </th>
                                    @endif
                                    @if(!empty($plan->other) )
                                        <th>
                                            <span class="iui-label"> Other: </span>
                                            {{$plan->other}}
                                        </th>
                                    @endif
                                </tr>
                                <tr><th></th></tr>
                                <tr><th></th></tr>
                                @if (isset($plan->agenet) && !empty($plan->agenet))
                                    <tr>
                                        <th>
                                            
                                            @php
                                            if(isset($inducing) && !empty($inducing))
                                            {
                                                $dateAndInjectionData = [];
                                                // $menses_Day = [];
                                                $dateAndInjectionData[] = (array)$inducing;
                                                foreach ($plan->agenet as $value){
                                                    echo $value . '<br />';
                                                    if(!empty($value) && strpos($value,'+') !== false)
                                                    {
                                                        $is_inj = 1;
                                                        $injection_name = explode('+',$value)[1];
                                                        $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                        $inj_name = explode($spilt_from,$injection_name)[0];
                                                        if(strpos($value,'3/5/7') !== false)
                                                        {
                                                            $menses_Day = ['0'=>'3','1'=>'5','2'=>'7'];
                                                        }
                                                        if(strpos($value,'2 ') !== false)
                                                        {
                                                            $menses_Day = ['0'=>'2'];
                                                        }
                                                        if(strpos($value,'6 ') !== false)
                                                        {
                                                            $menses_Day = ['0'=>'6'];
                                                        }
                                                    }
                                                    else {
                                                        $is_inj = 0;
                                                    }
                                                }
                                                
                                                foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                                {
                                                    if(!empty($value) && $is_inj == 1)
                                                    {
                                                        echo '<span class="iui-label"> INJ '.$inj_name.' - '.date('d/m/Y - l',strtotime($valueData->date)).' - '.$menses_Day[$keyValue].'rd day' . '</span><br />';
                                                    }
                                                }
                                            }
                                            @endphp
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    @endif

                @endif
                @if(isset($visit) && $visit == 3)
                    <style>
                        @page { margin-top : 200px; margin-bottom : 100px;}
                    </style>
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                        <tbody>
                            <tr>
                                <th>
                                    <span class="iui-label">LMP Date :</span>
                                    {{!empty($lmp->date) ? $lmp->date .' '.(!empty($lmp->lmp_date_diff) ? '('.$lmp->lmp_date_diff.')' : ''): '-'}}
                                </th>
                            </tr>
                            @if (isset($plan->inducing_agent) && !empty($plan->inducing_agent))
                                <tr>
                                    <th>
                                        <span class="iui-label">Inducing Agent :</span>
                                        @php
                                            $agentKeys = array_keys($inducingInjectionData);
                                            $agentInjection = [];
                                            foreach($plan->inducing_agent as $inducingAgent){
                                                if(in_array($inducingAgent, $agentKeys)){
                                                    $agentInjection[] = $inducingInjectionData[$inducingAgent];
                                                }
                                            }
                                        @endphp
                                        {{implode(',',$agentInjection)}}
                                    </th>
                                </tr>
                            @endif
                            <tr>
                                @if (!empty($description->cycle->type))
                                    <th class=" w-200">
                                        <span class="iui-label">Cycle Type :</span>
                                        @php
                                            $cycleType = ['1'=>'Natural Cycle','2'=>'Tablets','3'=>'Gonadotropin','4'=>'Tablets & Injection'];
                                        @endphp
                                        {{ (array_key_exists($description->cycle->type, $cycleType)) ? $cycleType[$description->cycle->type] : '-' }}
                                    </th>
                                @endif
                                @if(!empty($description->endometrial->type) )
                                    <th>
                                        <span class="iui-label"> Endometrial Thickness: </span>
                                        {{$description->endometrial->type}}
                                    </th>
                                @endif
                            </tr>
                            @if(!empty($description->le) && !empty($description->le->vitals_status) && $description->le->vitals_status == 'yes' && (!empty($description->le->bp) || !empty($description->le->temp) || !empty($description->le->pulse)))
                                <tr>
                                    <th>
                                        <span class="iui-label"> Vitals : </span>
                                        @if(!empty($description->le->temp))
                                            <br>
                                            <span class="iui-label">  Temp : </span>
                                            {{$description->le->temp}}
                                        @endif
                                        @if(!empty($description->le->pulse))
                                            <br>
                                            <span class="iui-label">Pulse :</span>
                                            {{$description->le->pulse}} / Min
                                        @endif
                                        @if(!empty($description->le->bp))
                                            <br>
                                            <span class="iui-label"> B.P :</span>
                                            {{$description->le->bp}} MMHG
                                        @endif
                                    </th>
                                </tr>
                            @endif
                            @if(isset($ovary->ovary_status))
                                <tr>
                                    <th  >
                                        <span class="iui-label"> Ovary:  </span>
                                    </th>
                                    @if(!empty($ovary->ovary_type->right->details))
                                        <th>
                                            <span class="iui-label">Right :</span>
                                            {{ !empty($ovary->ovary_type->right->details) ? $ovary->ovary_type->right->details : '-' }}
                                        </th>
                                    @endif
                                    <th>
                                        <span class="iui-label"> Left :</span>
                                        {{!empty($ovary->ovary_type->left->details) ? $ovary->ovary_type->left->details : '-' }}
                                    </th>
                                </tr>
                            @endif

                            @if (!empty($hcg->type) && $hcg->type == 'yes')
                                @if ($hcg->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label">HCG: </span>
                                            @if(!empty($hcg->injection->data))
                                                <span class="iui-label"> Injection:  </span>
                                                @php
                                                    $hcgInjectionValue = [];
                                                    $hcgInjection = [1=>'IUI HCG 5000',2=>'IUI HCG 10000',3=>'INJ 2 DECA',4=>'INJ 1 DECA',5=>'INJ Pitocin'];
                                                    foreach($hcg->injection->data as $key => $value){
                                                        $hcgInjectionValue[] = $hcgInjection[$value];
                                                    }
                                                @endphp
                                                    {{implode(',',$hcgInjectionValue)}}
                                            @endif
                                        </th>
                                        <br>
                                    </tr>
                                    <tr>
                                        <th>
                                            <span class="iui-label">HCG Time</span>
                                            {{$hcg->time }}
                                        </th>
                                    </tr>
                                @endif
                            @endif
                            @if (isset($hcg->iui->status) && $hcg->iui->status == 'yes' && $hcg->type == 'yes')
                                @if (isset($hcg->iui->status) && $hcg->iui->status == 'yes' && $hcg->type == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label"> IUI Type:</span>
                                            @if ($hcg->iui->type == 1)
                                                Husband
                                            @elseif ($hcg->iui->type == 2)
                                                Donar
                                            @elseif ($hcg->iui->type == 3)
                                                Both
                                            @endif
                                        </th>
                                    </tr>
                                @endif
                                @if($hcg->type == 'yes' && $hcg->iui->status == 'yes')
                                    <tr>
                                        <th>
                                            <span class="iui-label">IUI Time:</span>
                                            @php
                                                $cDate = date('Y-m-d').' '.$hcg->time;
                                                $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('Y-m-d H:i:s');
                                            @endphp
                                            {{\Carbon\Carbon::parse($iuiDtaeAndTime)->format('g:i a')}}
                                        </th>
                                    </tr>
                                @endif
                            @endif
                            <tr>
                                @if(!empty($description->no_follicle) )
                                    <th>
                                        <span class="iui-label"> No. Follicle: </span>
                                        {{$description->no_follicle}}
                                    </th>
                                @endif
                                @if(isset($description->ovalution) && $description->ovalution == 'yes')
                                    <th  >
                                        <span class="iui-label"> Ovalution: </span>
                                        {{'Yes'}}
                                    </th>
                                @endif
                                <th  >
                                    <span class="iui-label">Follow Up: </span>
                                    {{ (isset($description->follow_up) && !empty($description->follow_up)) ? \Carbon\Carbon::parse($description->follow_up)->format('d/m/Y') : (!empty($description->new_follow_up) ? \Carbon\Carbon::parse($description->new_follow_up)->format('d/m/Y') : '-') }}
                                </th>
                            </tr>
                            @if(!empty($description->p_s->type))
                                <tr>
                                    <th  >
                                        <span class="iui-label">P/S:</span>
                                        {{$description->p_s->details}}
                                    </th>
                                </tr>
                            @endif
                            {{-- @if(!empty($coData)) --}}
                                <th>
                                    <span class="iui-label">C/O :</span>
                                    @if(!empty($coData))
                                        {{implode(',',$coData)}}
                                    @else
                                        'None'
                                    @endif
                                </th>
                            {{-- @endif --}}
                            @if(!empty($description->remark))
                                <tr>
                                    <th>
                                        <span class="iui-label"> Remark: </span>
                                        {{$description->remark}}
                                    </th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif
                @if(isset($visit) && $visit == 4)
                    <style>
                        @page { margin-top : 10px; margin-bottom : 80px;}
                    </style>
                    <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                        <tbody>
                            <tr>
                                <th>
                                    @if(!empty($description->ho_type))
                                    <span class="iui-label">Follow Up case of : </span> {{isset($follow_up_case[$description->ho_type]) ? $follow_up_case[$description->ho_type] : ''}}
                                    @endif
                                </th>
                            <tr>
                                <tr>
                                    <th>
                                        @if(!empty($description->upt_type))
                                        <span class="iui-label">UPT : </span> {{ucwords(strtolower(str_replace('_',' ',$description->upt_type)))}}
                                        @endif
                                    </th>
                                <tr>
                                @if(!empty($description->le) && !empty($description->le->vitals_status) && $description->le->vitals_status == 'yes' && (!empty($description->le->bp) || !empty($description->le->temp) || !empty($description->le->pulse)))
                                    <tr>
                                        <th>
                                            <span class="iui-label">Vitals : </span>
                                            @if(!empty($description->le->temp))
                                                <br>
                                                <span class="iui-label">Temp :</span>
                                                {{$description->le->temp}}
                                            @endif
                                            @if(!empty($description->le->pulse))
                                                <br>
                                                <span class="iui-label"> Pulse :</span>
                                                {{$description->le->pulse}} / Min
                                            @endif
                                            @if(!empty($description->le->bp))
                                                <br>
                                                <span class="iui-label"> B.P :</span>
                                                {{$description->le->bp}} MMHG
                                            @endif
                                        </th>
                                    <tr>
                                @endif
                                @if((!empty($description->p_s->type) && $description->p_a->type == 'yes') || (!empty($description->p_a->type) && $description->p_a->type == 'yes') || (!empty($description->tvs->type) && $description->tvs->type == 'yes'))
                                <tr>
                                    <th>
                                        <span class="iui-label">O/E : </span>
                                        @if(!empty($description->p_s->type) && $description->p_a->type == 'yes')
                                        <br>
                                            <span class="iui-label">P/S : </span>{{$description->p_s->details}}
                                        @endif
                                        @if(!empty($description->p_a->type) && $description->p_a->type == 'yes')
                                        <br>
                                            <span class="iui-label">P/A : </span>{{$description->p_a->details}}
                                        
                                        @endif
                                        @if(!empty($description->tvs->type) && $description->tvs->type == 'yes')
                                        <br>
                                            <span class="iui-label">Uterus : </span>{{($description->uterus->type) && !empty($description->uterus->type) && $description->uterus->type == 1 ? 'Normal' : 'Abnormal'.(!empty($description->uterus->details) ? ' / '.$description->uterus->details : '')}}
                                        
                                        @endif
                                        @if(!empty($description->endometrial_thickness))
                                        <br>
                                            <span class="iui-label">Endometrial Thickness : </span>{{$description->endometrial_thickness}}
                                        
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if((!empty($description->tvs->type) && $description->tvs->type == 'yes') && ((!empty($description->ovary->type) && in_array('right', $description->ovary->type)) || (!empty($description->ovary->type) && in_array('left', $description->ovary->type))))
                                        <span class="iui-label">Ovary : </span>
                                        @endif
                                        @if(!empty($description->ovary->type))
                                            @if(in_array('right', $description->ovary->type))
                                            <br>
                                                <span class="iui-label">Right Ovary: </span>{{($description->ovary->right->type == 1) ? 'Normal' : ''}}
                                                @if(!empty($description->ovary->right->details))
                                                    <br><span class='iui-label pl-2'>Details:</span>{{implode(',',$description->ovary->right->details)}}
                                                @endif
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if(!empty($description->ovary->type))
                                            @if(in_array('left', $description->ovary->type))
                                                <span class="iui-label">Left Ovary: </span>{{($description->ovary->left->type == 1) ? 'Normal' : ''}}
                                                @if(!empty($description->ovary->left->details))
                                                    <br><span class='iui-label pl-2'>Details:</span>{{implode(',',$description->ovary->left->details)}}
                                                @endif
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                                @endif
                                <th>
                                    <span class="iui-label">Result :</span>
                                    @if(!empty($description->result))
                                        {{ ($description->result == 'fail') ? 'Fail' : 'Conceive' }}
                                    @endif
                                    {{-- &nbsp;&nbsp;&nbsp;&nbsp;
                                    <span class="iui-label">Date</span>
                                    {{!empty($description->date) ? $description->date : '-' }} --}}
                                </th>
                                
                            </tr>
                            <tr>
                                @if(!empty($description->remark))
                                <th>
                                    <span class="iui-label">Remark :</span>
                                    <span class="remark-text m-0">{{ !empty($description->remark) ? $description->remark : '-' }}</span>
                                </th>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                @endif
                @if($visit == 2 || $visit == 3 || $visit == 4)
                <?php
                    unset($treatment->medicinedata);
                ?>
                    @if(!empty($treatment) && count((array)$treatment) > 0)
                    <br>
                        <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                            <tbody>
                                <tr>
                                    <td colspan="9">
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
                                                        if(!empty($row->medicine_time)){
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
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    @if($visit == 2 || $visit == 4)
                    <br>
                        <table cellspacing="0" cellpadding="0" class="table m-b-0 module-report-table">
                            <tbody>
                                <tr>
                                    <th>
                                        {{-- <span class="iui-label">Follow Up: </span> --}}
                                        @if(!empty($plan->follow_up) && $visit == 2)
                                        <h5>{{"ફરીવાર ".\Carbon\Carbon::parse($plan->follow_up)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h5>
                                        @elseif(!empty($description->date) && $visit == 4)
                                        <h5>{{"ફરીવાર ".\Carbon\Carbon::parse($description->date)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h5>
                                        @else
                                        <h5>-</h5>
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                @endif
                @if(isset($patients_remark) && !empty($patients_remark))
                <!-- <div class="row"> -->
                <span class="font-bold">Remark : </span>{{$patients_remark}}
                <!-- </div> -->
                @endif
            </div>

        @else
            <style>
                @page { margin-top : 5px; margin-bottom : 80px;}
            </style>
            <div class="iui-print-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="patients-name">
                            {{strtoupper($iui->getPatientsInfoData['name'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="patients-mobile-number">
                            {{$iui->getPatientsInfoData['mobile_number']}} {{!empty($iui->getPatientsInfoData['other_mobile_number']) ? '/ '.$iui->getPatientsInfoData['other_mobile_number'] : null}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-1">
                        <div class="current-date hsg-injection">
                            {!! \Carbon\Carbon::parse(!empty($description->hcg_date) ? $description->hcg_date : null)->format('d M Y').' &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.\Carbon\Carbon::parse(!empty($description->hcg_date) ? $description->hcg_date : null)->format('l') !!}
                        </div>
                    </div>
                </div>
                @php
                    $hcgInjectionData = [];
                    if(!empty($hcg->injection->data)){
                        $hcgInjection = [1=>'IUI HCG 5000',2=>'IUI HCG 10000',3=>'INJ 2 DECA',4=>'INJ 1 DECA',5=>'INJ Pitocin'] ;
                        array_filter($hcg->injection->data,function($value) use($hcgInjection,&$hcgInjectionData){
                            $hcgInjectionData[$value]=$hcgInjection[$value];
                        });
                    }
                @endphp
                <div class="row">
                    <div class="col-md-4">
                        <div class="hsg-time-data">
                            {{$iui->hcg_time}} <span class="hsg-injection">{{\Carbon\Carbon::parse($hcg->time)->format('g:i')}}</span> વાગ્યે  <span class="hsg-injection">{{implode(',',$hcgInjectionData)}}&nbsp</span> માટે આવશે
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-1">
                        <div class="current-date hsg-injection">
                            @php
                                $cDate = \Carbon\Carbon::parse(!empty($description->hcg_date) ? $description->hcg_date : null)->format('Y-m-d') .' '.$hcg->time;
                                $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('Y-m-d H:i:s');
                            @endphp
                            {!! \Carbon\Carbon::parse($iuiDtaeAndTime)->format('d M Y') .' &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.\Carbon\Carbon::parse($iuiDtaeAndTime)->format('l') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="hsg-time-data">
                            {{$iui->iui_time}} <span class="hsg-injection">{{\Carbon\Carbon::parse($iuiDtaeAndTime)->format('g:i')}}&nbsp</span> વાગ્યે  <span class="hsg-injection">IUI</span> માટે આવશે
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@else
@if(!isset($printPreview))
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" >
@endif
{{-- <link rel="stylesheet" href="{{asset('assets/css/themes.css')}}"> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}


    @php
        $hTime = !empty($hcg->time) ? $hcg->time : null;
        $typeOfData = [1=>'Primary',2=>'Secondary'];
        $ohData = json_decode($iuiFirstVisit->o_h);
        $possibleFactorData = json_decode($iuiFirstVisit->possible_case_of_infertility);
        $possibleFactorData = !empty($possibleFactorData->infertility_type) ? $possibleFactorData->infertility_type : [];
        $dateAndInjectionData = [];
    @endphp
    {{-- <div class="study-report">
        <div class="study-report-data">
            <div class="patient-details">
                @php
                    $iuiData = json_decode($iui->description);
                @endphp
                <div class="row">
                    <div class="col-md-6 study-form">
                        <span class="col-md-5">Patients Name :
                            <span>{{strtoupper($iuiFirstVisit->getPatientsInfo['name'])}}</span>
                        </span>
                    </div>
                    <div class="row">

                    </div>
                    <div class="col-md-4 study-form">
                        <span class="col-md-3 form-group">Date :
                            @if(!empty($iuiThirdVisit->hcg->type) && $iuiThirdVisit->hcg->type == 'yes')
                                {{!empty($iuiThirdVisit->hcg->iui->status) && $iuiThirdVisit->hcg->iui->status == 'yes' && !empty($iuiData->last_appointment_date) ? \Carbon\Carbon::parse($iuiData->last_appointment_date.' '.$hTime)->addHours(36)->format('d/m/Y') : \Carbon\Carbon::parse($lastAppointmentData->date)->format('d/m/Y') }}
                            @elseif(!empty($iuiData->hcg->type) && $iuiData->hcg->type == 'yes')
                                {{!empty($iuiData->hcg->iui->status) && $iuiData->hcg->iui->status == 'yes' && !empty($iuiData->last_appointment_date) ? \Carbon\Carbon::parse($iuiData->last_appointment_date.' '.$hTime)->addHours(36)->format('d/m/Y') : \Carbon\Carbon::parse($lastAppointmentData->date)->format('d/m/Y') }}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 study-form">
                        <span class="col-md-3">LMP Date :
                            <span>{{!empty($iuiSecondVisit->lmp->date) ? \Carbon\Carbon::parse($iuiSecondVisit->lmp->date)->format('d-m-Y') : null}}</span>
                        </span>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3 study-form">
                        <span class="col-md-3">Follow Date :
                            <span>{{!empty($iuiThirdVisit->follow_up) ? \Carbon\Carbon::parse($iuiThirdVisit->follow_up)->format('d-m-Y') : null}}</span>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 study-form">
                        <span class="col-md-5">Medicine With :
                            <span>{{!empty($iuiSecondVisit->plan->plan_type) ? $iuiSecondVisit->plan->plan_type : null}}</span>
                        </span>
                    </div>
                    <div class="col-md-1 study-form">
                        <span>Cycel : {{$iui->cycle_no}}</span>
                    </div>
                </div>
            </div>
            <hr>
                <div class="report-title">
                    <h5>Follicular Study Report</h5>
                </div>
            <hr>
            <div class="follicular-report">
                <div class="row">
                    <div class="col-md-6 study-form">
                        <span class="col-md-6">{{!empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility] : 'Primary'}} &nbsp;&nbsp;
                            <span>{{!empty($ohData->first_marriage_life) ? 'First Marriage Life : '.$ohData->first_marriage_life : null}} {{!empty($ohData->second_marriage_details) ? 'Second Marriage Life :'.$ohData->second_marriage_details : null}}</span>
                        </span>
                    </div>
                    <div class="col-md-6 study-form">
                        <span class="col-md-6">Possible Factor :
                            <span>{{implode(',',$possibleFactorData)}}</span>
                        </span>
                    </div>
                </div>
                <table class="module-report-table study-report-table ">
                    <thead>
                        <tr>
                            <th>Study Date</th>
                            <th>Days</th>
                            <th>Rt Ovary</th>
                            <th>Lt Ovary</th>
                            <th>ET</th>
                            <th>Gondotropin Trigger</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($iuiHistoryData as $key=>$row)
                            @php
                                $iuiPrevVisit = IuiHistory::where('patients_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','DESC')->first();
                                if($iuiPrevVisit){
                                    $prevData = json_decode($iuiPrevVisit->description);
                                    $prevAppointmentDate = !empty($prevData->new_follow_up) ? \Carbon\Carbon::parse($prevData->new_follow_up)->format('d-m-Y') : null;
                                }
                                $data = json_decode($row->description);
                                $agentData = !empty($data->plan->inducing_agent) ? $data->plan->inducing_agent : [];
                                $lmpDate = \Carbon\Carbon::parse($data->lmp->date)->format('d-m-Y');
                                $createdAt = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                $appointmentDate = !empty($data->new_follow_up) ? \Carbon\Carbon::parse($data->new_follow_up)->format('d-m-Y') : \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                $diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($createdAt));
                                $diff = $diff + 1;
                                if(!empty($prevAppointmentDate)){
                                    $appointmentDate = $prevAppointmentDate;
                                }
                                if($row->visit == 2){
                                    $appointmentDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                }
                            @endphp
                            <tr >
                                <td>{{$appointmentDate}}</td>
                                <td>{{$diff}}</td>
                                <td>
                                    @if($row->visit == 3)
                                        @php
                                            if(!empty($data->inducing)){
                                                $agentDataValue = [];
                                                foreach($data->inducing as $key => $value) {
                                                    $agentDataValue = !empty($data->plan->inducing_agent) ? $data->plan->inducing_agent : [];
                                                    $value->injection = $agentDataValue;
                                                }
                                                $dateAndInjectionData[] = (array)$data->inducing;
                                            }
                                        @endphp
                                        {{!empty($data->ovary->ovary_type->right->details) ? $data->ovary->ovary_type->right->details : ''}}
                                    @endif
                                </td>
                                <td>
                                    @if($row->visit == 3)
                                        {{!empty($data->ovary->ovary_type->left->details) ? $data->ovary->ovary_type->left->details : ''}}
                                    @endif
                                </td>
                                <td>{{!empty($data->endometrial->type) ? $data->endometrial->type : ''}}</td>
                                <td>
                                    @if($row->visit == 2)
                                        {{!empty($data->plan->agenet) ? implode(',',$data->plan->agenet) : ''}}
                                    @endif
                                </td>
                                <td>{{!empty($data->remark) ? $data->remark : ''}}</td>
                            </tr>
                        @endforeach
                        @if(!empty($dateAndInjectionData))
                            @foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                <tr  >
                                    <td>{{\Carbon\Carbon::parse($valueData->date)->format('d-m-Y')}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @php
                                        $inducingAgentDataValue = [];
                                        foreach($valueData->injection as $injectionValue){
                                            $inducingAgentDataValue[] = $inducingInjectionData[$injectionValue];
                                        }
                                    @endphp
                                    <td>{{implode(',',$inducingAgentDataValue)}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="module-report-table study-report-table " >
                    <thead>
                        <tr>
                            <th>HCG</th>
                            <th>IUI</th>
                            <th>No Of Follicle </th>
                            <th>Ovaluation</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $result = json_decode($iui->description);
                        @endphp
                        <tr >
                            <td>
                                @if($iui->visit == 4)
                                    {{!empty($iuiThirdVisit->hcg->type) && $iuiThirdVisit->hcg->type == 'yes' && !empty($iuiData->last_appointment_date) ? 'YES '.\Carbon\Carbon::parse($iuiData->last_appointment_date)->format('d/m/Y') : ''}}
                                @else
                                    {{!empty($iuiData->hcg->type) && $iuiData->hcg->type == 'yes' && !empty($iuiData->last_appointment_date) ? 'YES '.\Carbon\Carbon::parse($iuiData->last_appointment_date)->format('d/m/Y') : ''}}
                                @endif
                            </td>
                            <td>
                                @if($iui->visit == 4)
                                    {{!empty($iuiThirdVisit->hcg->iui->status) && $iuiThirdVisit->hcg->iui->status == 'yes' && !empty($iuiData->last_appointment_date) ? 'YES '.\Carbon\Carbon::parse($iuiData->last_appointment_date.' '.$hTime)->addHours(36)->format('d/m/Y') : ''}}
                                @else
                                    {{!empty($iuiData->hcg->iui->status) && $iuiData->hcg->iui->status == 'yes' && !empty($iuiData->last_appointment_date) ? 'YES '.\Carbon\Carbon::parse($iuiData->last_appointment_date.' '.$hTime)->addHours(36)->format('d/m/Y') : ''}}
                                @endif
                            </td>
                            <td>{{!empty($iuiThirdVisit->no_follicle) ? $iuiThirdVisit->no_follicle : ''}}</td>
                            <td>{{!empty($iuiThirdVisit) ? 'YES' : ''}}</td>
                            <td>{{!empty($result->result) ? $result->result : ''}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    <div class="">
        @php
            $agentData = [];
            $hcgDataArray = [];
            if(!empty($iuiSecondVisit->plan->agenet)){
                $agentData = $iuiSecondVisit->plan->agenet;
            }
        @endphp
        <div class="row mb-5 do_print">
            <div class="col-md-12"><h4 class="text-center font-22"><u><b>TRANSVAGINAL FOLLICULAR STUDY</b></u></h4></div>
        </div>
        <div class="row follicular-table mb-3">
            <div class="col-md-6 col-sm-6 follicular_div_1 mr-15">
                <div class="mb-2">
                    <span class="visit-lable">Name :- </span> 
                    <span class="visit-lable-value">{{ucwords(strtolower($iuiFirstVisit->getPatientsInfo['name']))}}</span>
                </div>
                <div class="mb-2">
                        <span class="visit-lable">AGE :- </span> 
                        <span class="visit-lable-value">{{$iuiFirstVisit->getPatientsInfo['age']}}</span>
                </div>
                <div class="mb-2">
                        <span class="visit-lable">Type & Year of infertility :- </span> 
                        <span class="visit-lable-value">{{!empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility] : 'Primary'}} / {{!empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' years' : null}} {{!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' years' : null}}</span>
                </div>
                <div class="mb-2">
                        <span class="visit-lable">L.M.P :- </span> 
                        <span class="visit-lable-value">{{!empty($iuiSecondVisit->lmp->date) ? $iuiSecondVisit->lmp->date : null}}</span>
                </div>
                <div class="mb-2">
                        <span class="visit-lable">Plan :- </span> 
                        <span class="visit-lable-value">{{isset($iuiSecondVisit->iui) && ($iuiSecondVisit->iui == 'yes') ? 'COH+IUI ' : ''}} {{!empty($iuiSecondVisit->plan->plan_type) ? $iuiSecondVisit->plan->plan_type : null}}</span>
                </div>
                <div class="mb-2">
                        <span class="visit-lable">Induction With :- </span> 
                        <span class="visit-lable-value">{{(isset($agentData[0])) ? $agentData[0] : null}}</span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 follicular_div_2">
                <div class="mb-2">
                    <span class="visit-lable">UTERUS :- </span> 
                    <span class="visit-lable-value">{{!empty($iuiSecondVisit->oe->ut->ut_type) && $iuiSecondVisit->oe->ut->ut_type == 1 ? 'Normal' : 'Abnormal'}}</span>
                </div>
                <div class="mb-2">
                    <span class="visit-lable">TUBES :- </span> 
                    <span class="visit-lable-value"></span>
                </div>
                <div class="mb-2">
                    <div class="row">
                        <div class="col-md-3 visit-lable">
                        OVARIES:- {{!empty($iuiFirstVisit->ovary->right) ? $iuiFirstVisit->ovary->right : null}}
                        </div>
                        <div class="col-md-9 pl-10">
                            <div class="mb-2">R :- {{!empty($iuiSecondVisit->oe->ovary->right->afcs) ? $iuiSecondVisit->oe->ovary->right->afcs : null}} / RF:- {{!empty($iuiSecondVisit->oe->ovary->right->residual_follicale) ? $iuiSecondVisit->oe->ovary->right->residual_follicale : null}}</div>
                            <div>L :- {{!empty($iuiSecondVisit->oe->ovary->left->afcs) ? $iuiSecondVisit->oe->ovary->left->afcs : null}} / RF:- {{!empty($iuiSecondVisit->oe->ovary->left->residual_follicale) ? $iuiSecondVisit->oe->ovary->left->residual_follicale : null}}</div>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="visit-lable">CYCLE NO :- </span> 
                    <span class="visit-lable-value">{{$iui->cycle_no}}</span>
                </div>
            </div>
        </div>
        <div class="row follicular-table mb-3">
            <div class="col-md-12">
                <table class="module-report-table study-report-table mb-2">
                    <thead>
                        <tr>
                            <th style="width:8% !important;">Date</th>
                            <th style="width:5% !important">Day of Menses</th>
                            <th style="">Rt. Ovary</th>
                            <th style="">Lt. Ovary</th>
                            <th style="width:15% !important">Endometrial Thickness / Pattern</th>
                            <th style="width: 20% !important;">Gonodotropin </th>
                            <th style="width:10px;">Vascularity of Endometrium</th>
                            <th style="width: 20% !important;">Remark</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($iuiHistoryData as $key=>$row)
                        @if($row->visit != 4)
                            @php
                                $iuiPrevVisit = IuiHistory::where('patients_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','DESC')->first();
                                if($iuiPrevVisit){
                                    $prevData = json_decode($iuiPrevVisit->description);
                                    $prevAppointmentDate = !empty($prevData->new_follow_up) ? \Carbon\Carbon::parse($prevData->new_follow_up)->format('d-m-Y') : null;
                                }
                                $data = json_decode($row->description);
                                $agentData = !empty($data->plan->inducing_agent) ? $data->plan->inducing_agent : [];
                                $lmpDate = \Carbon\Carbon::parse($data->lmp->date)->format('d-m-Y');
                                $createdAt = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                $appointmentDate = !empty($data->new_follow_up) ? \Carbon\Carbon::parse($data->new_follow_up)->format('d-m-Y') : \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                $diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($createdAt));
                                $diff = $diff + 1;
                                $currentDateDiff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse(date('d-m-Y')));
                                $left_class_name = 'td-left-overy-'.$row->id.'-text';
                                $right_class_name = 'td-right-overy-'.$row->id.'-text';
                                $vascularity_of_endo = ['1' => "Up to Zone 1",'2' => "Up to Zone 2",'3' => "Up to Zone 3",'4' => "Up to Zone 4"];
                                $dateAndInjectionData = [];
                                $inducingDateArray = [];
                                if(!empty($prevAppointmentDate)){
                                    $appointmentDate = $prevAppointmentDate;
                                }
                                if($row->visit == 2){
                                    $appointmentDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                    $agentData = !empty($data->plan->agenet) ? $data->plan->agenet: [];
                                }
                                if($row->visit != 1)
                                {
                                    if(!empty($data->inducing)){
                                        $agentDataValue = [];
                                        foreach($data->inducing as $key => $value) {
                                            $inducingDateArray[] = \Carbon\Carbon::parse($value->date)->format('d-m-Y');
                                            $agentDataValue = !empty($data->plan->inducing_agent) ? $data->plan->inducing_agent : [];
                                            $value->injection = $agentDataValue;
                                        }
                                        $dateAndInjectionData[] = (array)$data->inducing;
                                    }
                                }
                            @endphp
                            @if(!empty($dateAndInjectionData))
                                @foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                @php
                                    $date = \Carbon\Carbon::parse($valueData->date)->format('d-m-Y');
                                    $inducing_diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($valueData->date));
                                    $inducing_diff = $inducing_diff + 1;
                                    if($row->visit == 2)
                                    {
                                        $inducingAgentDataValue = [];
                                        if(!empty($agentData))
                                        {
                                            foreach($agentData as $injection)
                                            {
                                                if((!empty($injection)) && strpos($injection,'+') !== false)
                                                {
                                                    $injection_name = explode('+',$injection)[1];
                                                    $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                    $inj_name = explode($spilt_from,$injection_name)[0];
                                                    $inducingAgentDataValue[] = $inj_name;
                                                }
                                                else {
                                                    $inducingAgentDataValue[] = $injection;
                                                }
                                            }
                                        }
                                        $inducing_agent = !empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : '';
                                    }
                                    if($row->visit == 3)
                                    {
                                        $inducingAgentDataValue = [];
                                        foreach($valueData->injection as $injectionValue){
                                            $inducingAgentDataValue[] = $inducingInjectionData[$injectionValue];
                                        }
                                        $inducing_agent = implode(',',$inducingAgentDataValue);
                                    }
                                @endphp
                                    @if(!empty($inducing_agent) && ($inducing_diff < $diff) && (!empty($valueData->date)) && ($valueData->date != $appointmentDate))
                                        <tr >
                                            <td>{{$date}}</td>
                                            <td>{{$inducing_diff}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$inducing_agent}}</td>
                                            <td></td>
                                            <td></td>
                                            <td class=""></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr >
                                <td>{{$appointmentDate}}</td>
                                <td>{{$diff}}</td>
                                <td class="{{$right_class_name}}" id="{{$row->id}}">
                                    @if($row->visit != 1)
                                        @php
                                            
                                            if(!empty($data->hcg->type) && $data->hcg->type == 'yes')
                                            {
                                                $hcgDataArray[] = (array)$data->hcg;
                                            }
                                        @endphp
                                        @if($row->visit == 2)
                                        {{!empty($iuiSecondVisit->oe->ovary->right->afcs) ? $iuiSecondVisit->oe->ovary->right->afcs : null}}
                                        {{!empty($iuiSecondVisit->oe->ovary->right->residual_follicale) ? '/ RF : '.$iuiSecondVisit->oe->ovary->right->residual_follicale : null}}
                                        @endif
                                        @if($row->visit == 3)
                                            <span class="span">{{!empty($data->ovary->ovary_type->right->details) ? $data->ovary->ovary_type->right->details : ''}}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="{{$left_class_name}}">
                                    {{-- @if($row->visit == 2)
                                        {{!empty($data->oe->ovary->right->afcs) ? $data->oe->ovary->right->afcs : null}}
                                        {{!empty($data->oe->ovary->right->residual_follicale) ? '/ RF : '.$data->oe->ovary->right->residual_follicale : null}}
                                    @endif --}}
                                    @if($row->visit == 2)
                                        {{!empty($iuiSecondVisit->oe->ovary->left->afcs) ? $iuiSecondVisit->oe->ovary->left->afcs : null}}
                                        {{!empty($iuiSecondVisit->oe->ovary->left->residual_follicale) ? '/ RF : '.$iuiSecondVisit->oe->ovary->left->residual_follicale : null}}
                                    @endif
                                    @if($row->visit == 3)
                                        <span class="span">{{!empty($data->ovary->ovary_type->left->details) ? $data->ovary->ovary_type->left->details : ''}}</span>
                                    @endif
                                </td>
                                <td class="">{{!empty($data->endometrial->type) ? $data->endometrial->type : ''}}</td>
                                <td class="">
                                    @if($row->visit == 2 && in_array($appointmentDate,$inducingDateArray))
                                        @php
                                            $inducingAgentDataValue = [];
                                            foreach($agentData as $injection)
                                            {
                                                if((!empty($injection)) && strpos($injection,'+') !== false)
                                                {
                                                    $injection_name = explode('+',$injection)[1];
                                                    $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                    $inj_name = explode($spilt_from,$injection_name)[0];
                                                    $inducingAgentDataValue[] = $inj_name;
                                                }
                                                else {
                                                    $inducingAgentDataValue[] = $injection;
                                                }
                                            }
                                        @endphp
                                        {{!empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : ''}}
                                    @endif
                                    @if($row->visit == 3)
                                    @php
                                    $InjectionData = '';
                                    if(!empty($agentData))
                                    {
                                        foreach($agentData as $agentData)
                                        {
                                            $InjectionData = !empty($InjectionData) ? $InjectionData.','.$inducingInjectionData[$agentData] : $inducingInjectionData[$agentData];
                                        }
                                    }
                                    @endphp
                                    {{$InjectionData}}
                                    @endif
                                </td>
                                <td>
                                    {{isset($vascularity_of_endo[$row->vascularity_of_endo]) ? $vascularity_of_endo[$row->vascularity_of_endo] : null}}
                                </td>
                                <td class="editStudyReport">{{!empty($data->remark) ? $data->remark : ''}}</td>
                            </tr>
                            @if(!empty($dateAndInjectionData))
                                @foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                @php
                                    $date = \Carbon\Carbon::parse($valueData->date)->format('d-m-Y');
                                    $inducing_diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($valueData->date));
                                    $inducing_diff = $inducing_diff + 1;
                                    if($row->visit == 2)
                                    {
                                            $inducingAgentDataValue = [];
                                            foreach($agentData as $injection)
                                            {
                                                if((!empty($injection)) && strpos($injection,'+') !== false)
                                                {
                                                    $injection_name = explode('+',$injection)[1];
                                                    $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                    $inj_name = explode($spilt_from,$injection_name)[0];
                                                    $inducingAgentDataValue[] = $inj_name;
                                                }
                                                else {
                                                    $inducingAgentDataValue[] = $injection;
                                                }
                                            }
                                            $inducing_agent = !empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : '';
                                        
                                    }
                                    if($row->visit == 3)
                                    {
                                        $inducingAgentDataValue = [];
                                        foreach($valueData->injection as $injectionValue){
                                            $inducingAgentDataValue[] = $inducingInjectionData[$injectionValue];
                                        }
                                        $inducing_agent = implode(',',$inducingAgentDataValue);
                                        
                                    }
                                @endphp
                                    @if(!empty($inducing_agent) && ($inducing_diff > $diff) && (!empty($valueData->date)) && ($valueData->date != $appointmentDate))
                                        <tr  >
                                            <td>{{$date}}</td>
                                            <td>{{$inducing_diff}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$inducing_agent}}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @if(!empty($hcgDataArray))
                    <table class="module-report-table study-report-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>HCG</th>
                                <th>IUI</th>
                                <th>No Of Follicle </th>
                                <th>Ovaluation</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iuiHistoryData as $key=>$row)
                            @php
                            // echo $row->visit;
                               $iui_decription =  json_decode($row->description);
                            @endphp
                            @if(((!empty($iui_decription->hcg) && ($iui_decription->hcg->type == 'yes' || $iui_decription->hcg->iui->status == 'yes')) || (!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes')) || ($row->visit == 4))
                            <tr>
                                <td>
                                    @if(!empty($iui_decription->hcg->type) && $iui_decription->hcg->type == 'yes' && !empty($iui_decription->hcg_date))
                                        {{\Carbon\Carbon::parse($iui_decription->hcg_date)->format('d/m/Y')}}
                                    
                                    @elseif(!empty($iui_decription->hcg->iui->status) && $iui_decription->hcg->iui->status == 'yes' && !empty($iui_decription->hcg_date))
                                        {{\Carbon\Carbon::parse($iui_decription->hcg_date.' '.$iui_decription->hcg->time)->addHours(36)->format('d/m/Y')}}
                                   
                                    @elseif($row->visit == 4 && !empty($iui_decription->result))
                                        {{$iui_decription->date}}
                                   
                                    @elseif((!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes'))
                                        {{\Carbon\Carbon::parse($row->created_at)->format('d/m/Y')}}
                                    @endif
                                </td>
                                <td>
                                        {{!empty($iui_decription->hcg->type) && $iui_decription->hcg->type == 'yes' && !empty($iui_decription->hcg_date) ? 'YES ': ''}}
                                        {{-- {{$row->visit == 4 && !empty($iui_decription->result) ? $iui_decription->date : ''}} --}}
                                        {{-- {{(!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes') ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') : ''}} --}}
                                </td>
                                <td>
                                        {{!empty($iui_decription->hcg->iui->status) && $iui_decription->hcg->iui->status == 'yes' && !empty($iui_decription->hcg_date) ? 'YES ' : ''}}
                                </td>
                                <td>{{!empty($iui_decription->no_follicle) ? $iui_decription->no_follicle : ''}}</td>
                                <td>{{(!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes') ? 'YES' : ''}}</td>
                                <td>{{($row->visit == 4 && !empty($iui_decription->result)) ? ($iui_decription->result == 'consive' ? 'Conceived' : 'Fail') : ''}}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <h4 class="mt-2 text-left"><u>Medicine:</u></h4>
                <table class="module-report-table study-report-table">
                    <thead>
                        <tr>
                            <th class="font-bold"> Date</th>
                            <th class="font-bold"> Medicine</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($iuiHistoryData as $key=>$row)
                            @php
                                $iuiPrevVisit = IuiHistory::where('patients_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','DESC')->first();
                                $data = json_decode($row->description);
                                
                                $historyTreatmentView = null;
                                if(!empty($data->treatment) || !empty($data->old_treatment)){
                                    $historyTreatmentView = !empty($data->treatment) ? $data->treatment : $data->old_treatment;
                                }
                            @endphp
                            @if(!empty($historyTreatmentView) && (!empty($historyTreatmentView->medicinedata[0])))
                            <tr>
                                <td>{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                                <td class="text-justify">
                                    
                                    @if($historyTreatmentView)
                                        @php
                                            $old_dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4">"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
                                            $old_medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
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
                                            @if (!empty($row->no)) | No : {{ $row->no }} @endif
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
                                                |
                                                        {{-- @foreach ($row->medicine_time as $time) --}}
                                                            {{$old_medicine_time[$row->medicine_time]}}
                                                        {{-- @endforeach --}}
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
        <br>
        <div class="row">
            <div class="col-md-12">
                @if(isset($patients_remark) && !empty($patients_remark))
                    <span class="font-bold">Remark : {{$patients_remark}}</span>
                @endif
                </div>
        </div>
        @if(!empty($description->hcg) && $description->hcg->type == 'yes')
            <div class="row follicular-iui-print">
                {{-- <div class="row"> --}}
                    <div class="col-md-12">
                        <div class="current-date hsg-injection">
                            <span class="fa fa-arrow-right"></span>&nbsp;&nbsp;{!! \Carbon\Carbon::parse(!empty($description->hcg_date) ? $description->hcg_date : null)->format('d M Y').' &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.\Carbon\Carbon::parse(!empty($description->hcg_date) ? $description->hcg_date : null)->format('l') !!}
                        </div>
                    </div>
                {{-- </div> --}}
                    @php
                        $hcgInjectionData = [];
                        if(!empty($hcg->injection->data)){
                            $hcgInjection = [1=>'IUI HCG 5000',2=>'IUI HCG 10000',3=>'INJ 2 DECA',4=>'INJ 1 DECA',5=>'INJ Pitocin'] ;
                            array_filter($hcg->injection->data,function($value) use($hcgInjection,&$hcgInjectionData){
                                $hcgInjectionData[$value]=$hcgInjection[$value];
                            });
                        }
                    @endphp
                    {{-- <div class="row"> --}}
                        <div class="col-md-12">
                            <div class="hsg-time-data ml-4">
                                {{$iui->hcg_time}} <span class="hsg-injection">{{\Carbon\Carbon::parse($hcg->time)->format('g:i')}}</span> વાગ્યે  <span class="hsg-injection">{{implode(',',$hcgInjectionData)}}&nbsp</span> માટે આવવુ
                            </div>
                        </div>
                    {{-- </div> --}}
                    <br><br>
                    @if(!empty($description->hcg->iui) && $description->hcg->iui->status == 'yes')
                        <div class="col-md-12">
                            <div class="current-date hsg-injection">
                                @php
                                    $cDate = \Carbon\Carbon::parse(!empty($description->hcg_date) ? $description->hcg_date : null)->format('Y-m-d') .' '.$hcg->time;
                                    $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('Y-m-d H:i:s');
                                @endphp
                                <span class="fa fa-arrow-right"></span>&nbsp;&nbsp;{!! \Carbon\Carbon::parse($iuiDtaeAndTime)->format('d M Y') .' &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.\Carbon\Carbon::parse($iuiDtaeAndTime)->format('l') !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="hsg-time-data ml-4">
                                {{$iui->iui_time}} <span class="hsg-injection">{{\Carbon\Carbon::parse($iuiDtaeAndTime)->format('g:i')}}&nbsp</span> વાગ્યે  <span class="hsg-injection">IUI</span> માટે આવવુ
                            </div>
                        </div>
                    @endif
            </div>
        @else
            @if(isset($description->follow_up) && !empty($description->follow_up))
                <h4 class="text-center">{{"ફરીવાર ".\Carbon\Carbon::parse($description->follow_up)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h4>
            @endif
        @endif
        
    </div>

@endif
@if(isset($printPreview) && $printPreview != 0)

    @endsection
@endif
