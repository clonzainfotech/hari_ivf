@php
    $patientsInfo = !empty($gynec->patients_info) ? json_decode($gynec->patients_info) : null;
    $ho = !empty($gynec->ho) ? json_decode($gynec->ho) : null;
    $co = !empty($gynec->co) ? json_decode($gynec->co) : null;
    $mh = !empty($gynec->mh) ? json_decode($gynec->mh) : null;
    $patientsDetails = !empty($gynec->patients_details_ho) ? json_decode($gynec->patients_details_ho) : null;
    $oe = !empty($gynec->oe) ? json_decode($gynec->oe) : null;
    $oh = !empty($gynec->oh) ? json_decode($gynec->oh) : null;
    $planManagement = !empty($gynec->plan_of_management	) ? json_decode($gynec->plan_of_management	) : null;
    $investigation = !empty($gynec->investigation) ? json_decode($gynec->investigation) : null;
    $treatment = !empty($gynec->treatment) ? json_decode($gynec->treatment) : null;
    $contraceptionData = ['barrier_method'=>'Barrier Method','cu_t'=>'Cu - T','tl_done'=>'TL Done ','occipill'=>'Occipill','other_contraception'=>'Other'];
    $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    $medqty = ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $follow_up_case = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI'];
    $ho_type_array = ['1'=>'Conceived Naturally','2'=>'Conceived With Medicine','3'=>'Conceived With IUI','4'=>'Conceived With IVF'];
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
    /* .f-date{
        font-weight: bold;
    } */
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
    .main-print-anc-div.panel-primary{
        border: 1px solid;
        padding: 11px;
        /* margin-top: 100px; */
    }
    .medicine-table td{
        padding: 2px 15px;
        text-transform: capitalize;
    }
    
    /* @media all {
        .page-break { display: none; }
    }
    @media print {
        .page-break { display: block; page-break-before: always; }
    } */
    @page { margin-top : 120px; margin-bottom : 80px;}
</style>

<div class="main-print-anc-div">
    <div class="panel panel-primary">
        <!-- H/O -->
        @if($ho && !empty($ho->ho_details))
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="panel-title header-print-title"> H/O</div>
                        </td>
                    </tr>
                    <th class="w-250 seperator">
                        <span class="anc-label ">H/O : </span>
                        {{!empty($ho->ho_details) ? $ho->ho_details : '-' }}
                    </th>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- C/O -->
        {{-- @if(!empty($co) && !empty($co->co_type) || !empty($co->since)) --}}
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="panel-title header-print-title"> C/O</div>
                        </td>
                    </tr>
                    {{-- @if(!empty($co->co_type) || !empty($co->since)) --}}
                        <tr>
                            {{-- @if(isset($co->co_type) && is_array($co->co_type)) --}}
                                <th class="seperator w-500">
                                    <span class="anc-label">C/O :</span>
                                    {{ (isset($co->co_type) && is_array($co->co_type)) ? implode(', ', $co->co_type) : 'None' }}
                                </th>
                            {{-- @endif --}}
                            @if(!empty($co->since))
                                <th class="seperator">
                                    <span class="anc-label">Since :</span>
                                    {{ !empty($co->since) ? $co->since : '-' }}
                                </th>
                            @endif
                        </tr>
                    {{-- @endif --}}
                </tbody>
            </table>
        {{-- @endif --}}
        @if($gynec->is_gynec == 1)
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="panel-title header-print-title">O/E</div>
                        </td>
                    </tr>
                    @if(!empty($oe->gynec_tvs->type) && $oe->gynec_tvs->type == 'yes' && empty($oe->gynec_ut->details))
                        <tr>
                            <th class="seperator w-500">
                                <span class="anc-label">TVS :</span>
                                <span class="anc-label">Uterus:</span>
                                {{$oe->gynec_ut->details}}
                            </th>
                        </tr>
                    @endif
                    @if(!empty($oe->gynec_endometrial_cavity->details))
                        <tr>
                            <th class="seperator w-500">
                                <span class="anc-label">Endometrial Cavity :</span>
                                <span>{{$oe->gynec_endometrial_cavity->details}}</span>
                            </th>
                        </tr>
                    @endif
                    @if(!empty($oe->gynec_p_s->type) && $oe->gynec_p_s->type == 'yes' && !empty($oe->gynec_p_s->details))
                        <tr>
                            <th class="seperator w-500">
                                <span class="anc-label">P/S Details :</span>
                                {{$oe->gynec_p_s->details}}
                            </th>
                        </tr>
                    @endif
                    {{-- @if(!empty($oe->gynec_le->bp) || (!empty($oe->gynec_le->temp) || !empty($oe->gynec_le->pulse))) --}}
                        <tr>
                            <th class="seperator w-500">
                                Vitals
                            </th>
                            {{-- @if(!empty($oe->gynec_le->bp)) --}}
                                <th class="seperator w-500">
                                    <span class="anc-label">B.P :</span>
                                    {{isset($oe->gynec_le->bp) ? $oe->gynec_le->bp : '110/70'}} MMHG
                                </td>
                            {{-- @endif --}}
                            @if(!empty($oe->gynec_le->temp))
                                <th class="seperator w-500">
                                    <span class="anc-label">&nbsp;Temp :</span>
                                    {{$oe->gynec_le->temp}}
                                </td>
                            @endif
                            {{-- @if(!empty($oe->gynec_le->pulse)) --}}
                                <th class="seperator w-500">
                                    <span class="anc-label">&nbsp;Pulse :</span>
                                    {{isset($oe->gynec_le->pulse) ? $oe->gynec_le->pulse : '80'}} / Min
                                </td>
                            {{-- @endif --}}
                        </tr>
                    {{-- @endif --}}
                </tbody>
            </table>
        @endif
        
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
                                    <span class="iui-label">Temp : </span>
                                    {{$oe->le->temp}}
                                @endif
                                @if(!empty($oe->le->pulse))
                                    <br>
                                    <span class="iui-label">Pulse : </span>
                                    {{$oe->le->pulse ? $oe->le->pulse : '80'}} / Min
                                @endif
                                @if(!empty($oe->le->bp))
                                    <br>
                                    <span class="iui-label">B.P :</span>
                                    {{$oe->le->bp ? $oe->le->bp : '110/70'}} MMHG
                                @endif
                            </th>
                        @endif
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
                                <span class="iui-label">Transvaginal Ultrasonography:</span>
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
                    @if (!empty($oe->ovary->right->updated_details) || !empty($oe->ovary->right->afcs))
                    <tr>
                        <th>
                            @if (!empty($oe->ovary->right->updated_details))
                            <span class="iui-label">Right Ovary : </span>
                                @foreach ($oe->ovary->right->updated_details as $key => $value)
                                    @php
                                        echo !empty($value) ? $value .  '<br />' : '- <br />';
                                    @endphp
                                @endforeach
                            @endif
                            @if(!empty($oe->ovary->right->afcs))
                                <span class="iui-label">Follicle numbers per ovary : </span>
                                {{$oe->ovary->right->afcs}}
                            @endif
                        </th>
                    </tr>
                    @endif
                    @if(!empty($oe->ovary->left->updated_details) || !empty($oe->ovary->left->afcs))
                    <tr>
                        <th>
                            @if(!empty($oe->ovary->left->updated_details))
                            <span class="iui-label">Left Ovary : </span>
                                @foreach($oe->ovary->left->updated_details as $key => $value)
                                    @php
                                        echo !empty($value) ? $value .  '<br />' : '- <br />';
                                    @endphp
                                @endforeach
                            @endif
                            @if(!empty($oe->ovary->left->afcs))
                                <span class="iui-label">Follicle numbers per ovary : </span>
                                {{$oe->ovary->left->afcs}}
                            @endif
                        </th>
                    </tr>
                    @endif
                    @if (isset($oe->breast) && $oe->breast->type == 'yes')
                    
                    <tr>
                        <th>Breast</th>
                    </tr>
                    <tr>   
                        <th> 
                            @if(!empty($oe->breast->right))
                            <span class="iui-label">Right : </span>{{$oe->breast->right}}
                            @endif
                            @if(!empty($oe->breast->left))
                            <br>
                            <span class="iui-label">Left : </span>{{$oe->breast->left}}
                            @endif
                            
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
                        @if(!empty($oh->marriage_life) || !empty($oh->upt_type) || !empty($oh->active_marriage_life) || !empty($oh->type_of_infertility))
                            <tr>
                                @if(!empty($oh->marriage_life))
                                    <th>
                                        <span class="iui-label">Marriage Life :</span>
                                        {{$oh->marriage_life}}
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
                                            $ho_term_details = '';
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
                                            $second_ho_term_details = '';
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
                                        <span class="iui-label ">MTP : </span>{{$oh->second_marriage->mtp_no}}
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
        @if($gynec->is_gynec == 0)
            @if(!empty($planManagement) && !empty($planManagement->plan_of_management_data))
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                    <tbody>
                        <tr>
                            <td colspan="6">
                                <div class="panel-title header-print-title">Plan Of Management</div>
                            </td>
                        </tr>
                        @if(!empty($planManagement->plan_of_management_data))
                            <tr>
                                <th class="seperator w-500">
                                    <span class="anc-label">Plan Of Management Type :</span>
                                    {{implode(',',$planManagement->plan_of_management_data)}}
                                </th>
                            </tr>
                            @if(!empty($planManagement->surgically_details))
                                @php
                                    $sData = [];
                                    foreach ($planManagement->surgically_details as $row) {
                                        $sData[] = $surgicallyData[$row];
                                    }
                                @endphp
                                <tr>
                                    <th class="seperator w-500">
                                        <span class="anc-label">Plan Of Management Type :</span>
                                        {{implode(',',$sData)}}
                                    </th>
                                </tr>
                            @endif
                        @endif
                    </tbody>
                </table>
            @endif
        @endif
        
        @if($investigation  && (!empty($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes' || (!empty($investigation->laproscopy) && $investigation->laproscopy->type == 'yes') || (!empty($investigation->hcg) && $investigation->hcg->type == 'yes') || (isset($investigation->investigation_extra) && !empty($investigation->investigation_extra))))
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
                                @if(isset($investigation->investigation_extra) && !empty($investigation->investigation_extra))
                                    <tr >
                                        <th>
                                            <span class="iui-label">Other Report :</span>
                                            {{$investigation->investigation_extra}}
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
        {{--gynec investigation tab --}}
        @if($gynec->is_gynec == 1)
            <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="panel-title header-print-title">Investigation</div>
                        </td>
                    </tr>
                    <tr>
                        <th>Investigation</th>
                    </tr>
                    <tr>
                        @if(!empty($investigation->investigation_anc_date))
                            <th class="seperator w-500">
                                <span class="anc-label">Date :</span>
                                {{$investigation->investigation_anc_date}}
                            </th>
                        @endif
                        @if(!empty($investigation->investigation_blood_group))
                            <th class="seperator w-500">
                                <span class="anc-label">Blood Group :</span>
                                {{$investigation->investigation_blood_group}}
                            </th>
                        @endif
                        @if(!empty($investigation->investigation_anc_rbs))
                            <th class="seperator w-500">
                                <span class="anc-label">RBS :</span>
                                {{$investigation->investigation_anc_rbs}}
                            </th>
                        @endif
                    </tr>
                    @if(!empty($investigation->investigation_cbc_mp->status))
                        <tr>
                            <th class="seperator w-500">
                                <span class="anc-label">CBC MP :</span>
                                {{$investigation->investigation_cbc_mp->status == 1 ? 'WNL' : 'Abnormal'}}
                            </th>
                            @if($investigation->investigation_cbc_mp->status == 2)
                                @if($investigation->investigation_cbc_mp->aneamia)
                                    <th>
                                        <span class="anc-label">Aneamia :</span>
                                        {{$investigation->investigation_cbc_mp->aneamia}}
                                    </th>
                                @endif
                                @if($investigation->investigation_cbc_mp->leacocytosis)
                                    <th>
                                        <span class="anc-label">Leacocytosis:</span>
                                        {{$investigation->investigation_cbc_mp->leacocytosis}}
                                    </th>
                                @endif
                            @endif
                        </tr>
                    @endif
                    @if(!empty($investigation->investigation_urine) && !empty($investigation->investigation_urine->status))
                        <tr>
                            <th class="seperator w-500">
                                <span class="anc-label">Urine :</span>
                                {{$investigation->investigation_urine->status == 1 ? 'WNL' : 'Abnormal'}}
                            </th>
                            @if($investigation->investigation_urine->status == 2)
                                @if($investigation->investigation_urine->type)
                                    <th>
                                        <span class="anc-label">Puccell  :</span>
                                        {{ucfirst($investigation->investigation_urine->type)}}
                                    </th>
                                    @if($investigation->investigation_urine->type == 'present')
                                        <th>
                                            <span class="anc-label">Puscell :</span>
                                            {{$investigation->investigation_urine->puscell}}
                                        </th>
                                    @endif
                                @endif
                                @if($investigation->investigation_urine->urine_albumine)
                                    <th>
                                        <span class="anc-label">Urine Albumine:</span>
                                        {{$investigation->investigation_urine->urine_albumine}}
                                    </th>
                                @endif
                            @endif
                        </tr>
                    @endif
                    <tr>
                        @if(!empty($investigation->anc_hiv))
                            <th class="seperator w-500">
                                <span class="anc-label">HIV :</span>
                                {{ucfirst($investigation->anc_hiv)}}
                            </th>
                        @endif
                        @if(!empty($investigation->anc_hbsag))
                            <th class="seperator w-500">
                                <span class="anc-label">HBSAG :</span>
                                {{ucfirst($investigation->anc_hbsag)}}
                            </th>
                        @endif
                        @if(!empty($investigation->anc_vdrl))
                            <th class="seperator w-500">
                                <span class="anc-label">VDRL  :</span>
                                {{ucfirst($investigation->anc_vdrl)}}
                            </th>
                        @endif
                    </tr>
                </tbody>
            </table>
        @endif

        {{-- treatment tab --}}
        @if(!empty($treatment))
            
                    @php
                        // $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                        // $old_medicine_time = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                        unset($treatment->medicinedata);
                    @endphp
                    @if(!empty($treatment) && count((array)$treatment) > 0)
                    <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="panel-title header-print-title">Treatment (Medicine)</div>
                                </td>
                            </tr>
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
                        </tbody>
                    </table>
                @endif
               
        @endif
        <br>
        <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 table-hover module-report-table'}}">
                <tbody>
                    <tr>
                        <td> Follow up :{{ isset($ho->follow_up) && !empty($ho->follow_up) ? $ho->follow_up : '-' }}<td>
                    </tr>
                    @if(isset($ho->remark) && !empty($ho->remark))
                    <tr>
                        <td><span class="f-date"> Remark :</span>{{ $ho->remark}}</td>
                    </tr>
                    @endif
                </tbody>
        </table>
    </div>
</div>
