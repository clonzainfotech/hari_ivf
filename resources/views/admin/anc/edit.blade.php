@php
    $class = 'panel panel-primary d-none';
    $type = 'anc_history';
    $coNo = 2;
    $hoNo = 1;
    $oeNo = 4;
    $investigationNo = 5;
    $treatmentNo = 8;
    $patientsHoNo = 3;
    $injectionNo = 6;
    $usgNo = 7;
    $ancId = null;
    if($anc){
        $type = 'anc';
        $class = 'panel panel-primary';
        $coNo = 3;
        $hoNo = 2;
        $oeNo = 7;
        $investigationNo = 8;
        $patientsHoNo = 6;
        $usgNo = 10;
        $treatmentNo = 11;
        $injectionNo = 9;
        $ancId = $ancData->id;
    }
    $ancCreatedDate = (!empty($ancId) || !empty($ancHistoryId)) ? $ancData->created_at : null;
    $ancFirst_patientsObstratics = isset($ancFirstVisit) ? json_decode($ancFirstVisit->patients_obstratics) : null;
    $previousAncRemark = isset($previousAncRemark->remark) && !empty($previousAncRemark->remark) ? $previousAncRemark->remark : null;
    if(!empty($previousAnc->id)){
        $preId=$previousAnc->id;
    }else{
        $preId="";
    }
    if(isset($previousAnc->patients_obstratics)){
        $tableId="1";
    }else{
        $tableId="2";
    }
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
    $terminationtype = [''=>'Select Reason','Delivery'=>"Delivery",
                        'Obseravation'=>"Obseravation",
                        'Termination'=>"Termination",
                        'Operation'=>"Operation"];
    $selectAttr = (!empty($ancId) || !empty($ancHistoryId)) ? 'disabled' : '-';

    $childStatusType = 'd-none';
    if(!empty($oe->oe_no) && $oe->oe_no > 1){
        $childStatusType = '';
    }
    $oeutdt = [];
    if(@$oe->utdata) {
        $oeutdt = (array)$oe->utdata;
    }
    $medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
    $medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day"];
$abArray = ['1'=>"Normal",'2'=>"Abnormal"];
$wnlArray = ['1'=>"WNL",'2'=>"Abnormal"];
@endphp
{{Form::hidden('anc_history_type',$type,['class'=>'anc_history_type'])}}
{{Form::hidden('anc_id',!empty($ancId) ? encrypt($ancId) : null), [
    'id' => 'anc_id'
]}}

{{Form::hidden('anc_history_id', !empty($ancHistoryId) ? encrypt($ancHistoryId) : null, [
    'id' => 'anc_history_id'
])}}

{{Form::hidden('is_gsac', $isGsac, ['id' => 'is_gsac'])}}
{{Form::hidden('tableId', $tableId, ['id' => 'tableId'])}}
{{Form::hidden('preId', $preId, ['id' => 'preId'])}}
<span class="form-error-msg">
    @if (!empty($previousAncOe->remark) && $previousAnc->status == 0)
        <h5 class="remarkhide">  O/E Remark of Last Visit:
            <small>
                <span>{{$previousAncOe->remark}}</span>
                <span aria-hidden="true" class="remark-remove button text-black">&times;</span>
            </small>
        </h5>
    @endif

    <h5 class="autoRemark">
    @if (isset($ancFirst_patientsObstratics->gpal_status) && !empty($ancFirst_patientsObstratics->gpal_status))
            *GPAL Status:
                <small>
                    {{$ancFirst_patientsObstratics->gpal_status}}
                </small>
    @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['blood_group']))
        @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['blood_group_date']))
            &nbsp;&nbsp;&nbsp;*Blood Group:
            <small>
                {{$ancAutoRemark['blood_group']}}
            </small>
            @endif
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['hbsag']))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hbsag_date']))
                &nbsp;&nbsp;&nbsp;*HBSAG:
                <small>
                    {{$ancAutoRemark['hbsag']}}
                </small>
            @endif
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['hiv']))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hiv_date']))
            &nbsp;&nbsp;&nbsp;*HIV:
            <small>
                {{$ancAutoRemark['hiv']}}
            </small>
            @endif
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['vdrl']))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['vdrl_date']))
                &nbsp;&nbsp;&nbsp;*VDRL:
                <small>
                    {{$ancAutoRemark['vdrl']}}
                </small>
            @endif
        @endif

        @if($ancAutoRemark && !empty($ancAutoRemark['late_concept']))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['late_concept_date']))
                &nbsp;&nbsp;&nbsp;*Late Conception:
                <small>Yes</small>
            @endif
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['cesarean']))
                &nbsp;&nbsp;&nbsp;*Previous:
                <small>
                    {{$ancAutoRemark['cesarean']. ' - LSCS'}}
                </small>
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['position']) && ($ancAutoRemark['position'] == 'breech' || $ancAutoRemark['position'] == 'transverse' || $ancAutoRemark['position'] == 'oblique'))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['position_date']))
                &nbsp;&nbsp;&nbsp;*Position:
                <small>
                    {{$ancAutoRemark['position']}}
                </small>
            @endif
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['liquor']) && ($ancAutoRemark['liquor'] == 'oligo' || $ancAutoRemark['liquor'] == 'poly'))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['liquor_date']))
            &nbsp;&nbsp;&nbsp;*Liquor:
            <small>
                {{$ancAutoRemark['liquor']}}
            </small>
            @endif
        @endif
        @if($ancAutoRemark && !empty($ancAutoRemark['placenta']))
            @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['placenta_date']))
                &nbsp;&nbsp;&nbsp;*Placenta:
                <small>
                    {{$ancAutoRemark['placenta']}}
                </small>
            @endif
        @endif

    </h5>
</span>
@if ($isGsac == true && empty($ancId))
    {{Form::hidden('oe[is_gsac_no]', 'yes')}}
    <div class="panel panel-primary o-e iui">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#oe" href="#oe" aria-expanded="true"
                                        aria-controls="oe">1. O/E
                    @if(!empty($oe->late_concept) && $oe->late_concept == 1)
                        <span class="text-danger">Late Conception</span>
                        {{Form::hidden('oe[late_concept]',1,['class'=>'late-concept'])}}
                    @endif
                    @if($utType == 'yes')
                        @if(!empty($lmdDate))
                            &nbsp&nbsp<span class="lmd-text">{{'L.M.D Date: ' .\Carbon\Carbon::parse($lmdDate)->format('d-m-Y') }}</span>
                            {{Form::hidden('oe_lmd_date',\Carbon\Carbon::parse($lmdDate)->format('d-m-Y'))}}
                        @endif
                        @if(!empty($eddDate))
                            &nbsp&nbsp<span class="lmd-text">{{'EDD Date: ' .\Carbon\Carbon::parse($eddDate)->format('d-m-Y') }}</span>
                            {{Form::hidden('oe_edd_date',\Carbon\Carbon::parse($eddDate)->format('d-m-Y'))}}
                        @endif
                    @endif
                </a></h4>
        </div>
        <div id="oe" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            OE :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[oe_type]",'tvs',!empty($oe->oe_type) && $oe->oe_type == 'tvs' ? true : false,['id'=>'tvs','class'=>'oe-type'])}}
                            <label for="tvs">
                                TVS
                            </label>

                            {{Form::radio("oe[oe_type]",'pa',!empty($oe->oe_type) && $oe->oe_type == 'pa' ? true : false,['id'=>'pa','class'=>'oe-type'])}}
                            <label for="pa">
                                PA
                            </label>
                        </div>
                    </div>
                    {{Form::hidden('oe[oe_no]',!empty($oe->oe_no) ? $oe->oe_no : 0, [
                        'id' => 'oe_child_number'
                    ])}}
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select("oe[oe_no]",['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'],!empty($oe->oe_no) ? $oe->oe_no : null,[
                                'class'=>'form-control select-padding-0 oe-no',
                                'data-type'=>'ut-g-sac',
                                $selectAttr
                            ])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('oe_no')}}
                        </span>
                    </div>
                    @if(!empty($oe->oe_child_type))
                        <div class="col-md-3 oe-child-type">
                            <div class="form-group">
                                {{Form::select("oe[oe_child_type]",$childType,$oe->oe_child_type,['class'=>'form-control select-padding-0','placeholder'=>'Select Conceived By'])}}
                            </div>
                        </div>
                    @endif
                </div>
                {{Form::hidden('how_much_number_data',$oeDataCount,['class'=>'child-count'])}}
                <div class="oe-data">
                    @php
                        $gSacNo = 'd-none';
                    @endphp
                    @foreach($oe->utdata as $key=>$value)
                        @php
                            if ($key == 1) {
                                $gSacNo = (isset($patientsObstratics->upt_type)) && ($patientsObstratics->upt_type == 'positive') &&  (((isset($value->oe_ut_sac) && strtolower($value->oe_ut_sac) == 'no') || (isset($value->oe_ut_sac_2)) &&  strtolower($value->oe_ut_sac_2) == 'no')) ? '' : 'd-none';
                            }
                        @endphp
                        @if($utType == 'yes')
                            <div class="{{ 'row fefal-reduction-' .$key}}">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        P/A Uterus:
                                    </label>
                                </div>
                                <div class="col-md-2 g-sac ut-g-sac">
                                    <div class="form-group">
                                        {{Form::select("oe[utdata][".$key."][oe_ut_sac_1]",$weekData,!empty($value->oe_ut_sac_1) ? $value->oe_ut_sac_1 : null,['class'=>'form-control ut-sac max-1 female-type-week-'.$key.' crl-number g-sac-no','onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'data-id'=>$key,'data-value'=>'anc-history','placeholder'=>'Select Week'])}}
                                        {{Form::hidden('oe[utdata]['.$key.'][oe_ut_sac_1_status]','yes')}}
                                    </div>
                                </div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        G-sac(MM) :
                                    </label>
                                </div>
                                <div class="col-md-1 g-sac ut-g-sac">
                                    <div class="form-group">
                                        {{Form::text("oe[utdata][".$key."][oe_ut_sac_2]",!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null,['class'=>'form-control g-sac-'.$key,'onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'data-id'=>'1','data-value'=>'anc-history'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 d-none ut-g-sac-details-'.$key}}">
                                    <div class="form-group">
                                        {{Form::text("oe[utdata][".$key."][oe_ut_sac_details]",!empty($value->oe_ut_sac_details) ? $value->oe_ut_sac_details : null,['class'=>'form-control ut-sac-details','placeholder'=>'Details'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('oe_ut_sac')}}
                                    </span>
                                </div>
                                <div class="{{'col-md-3 child-type-status '.$childStatusType}}">
                                    <div class="form-group">
                                        {{Form::select("oe[utdata][".$key."][child_type]",$childType,!empty($value->child_type) ? $value->child_type : null,['class'=>'form-control select-padding-0','placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                {{-- @if(($value->oe_ut_sac < 13 || $value->oe_ut_sac_2 < 13) || $value->ut_type == 'g-sac') --}}
                                <div class="{{'col-md-1 pr-0 crl-data-value-'.$key}}">
                                    <label class='vertical-form-label pr-0'>CRL :</label>
                                </div>
                                <div class="{{'col-md-2 crl-data-value-'.$key}}">
                                    <div class="form-group">
                                        {{Form::number("oe[utdata][".$key."][crl]",!empty($value->crl) ? $value->crl : null,['class'=>'form-control crl-data','data-id'=>$key])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-1 p-1 crl-data-value-'.$key}}">
                                    <span class="{{'crl-text-'.$key}}">{{!empty($value->crl_details) ? $value->crl_details : null}}</span>
                                    {{Form::hidden("oe[utdata][".$key."][crl_details]",!empty($value->crl_details) ? $value->crl_details : null,['class'=>'crl-val-'.$key])}}
                                </div>
                                {{-- @endif --}}

                            </div>

                        @else
                            <div class="{{ 'row fefal-reduction-' . $key}}">
                                <div class="col-md-2 ut-g-sac pr-0 mt-2">
                                    {{Form::hidden("oe[utdata][".$key."][ut_type]", !empty($value->ut_type) ? $value->ut_type : null,['class'=>'ut-type oe-ut-gsac-type'])}}
                                    {{Form::hidden("oe[utdata][".$key."][oe_ut_sac]",!empty($value->oe_ut_sac) ? $value->oe_ut_sac : null,['class'=>'form-control ut-sac'])}}
                                    {{Form::hidden("oe[utdata][".$key."][oe_ut_sac_2]",!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null,['class'=>'form-control ut-sac-2'])}}
                                    <label>
                                        @if(!empty($value->ut_type))
                                            {{strtoupper($value->ut_type)}}  {{$value->ut_type == 'ut' ? '(wks)' : '(mm)'}} :
                                        @endif
                                    </label>
                                </div>
                                <div class="col-md-2">
                                    <label>
                                        {{!empty($value->oe_ut_sac) ? $value->oe_ut_sac : null}}@if(!empty($value->oe_ut_sac_2)){{!empty($value->ut_type) && $value->ut_type == 'ut' ? '-' : '.'}}{{!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null}}@endif
                                    </label>
                                </div>
                                <div class="{{'col-md-2 d-none ut-g-sac-details-'.$key}}">
                                    <div class="form-group">
                                        {{Form::text("oe[utdata][".$key."][oe_ut_sac_details]",!empty($value->oe_ut_sac_details) ? $value->oe_ut_sac_details : null,['class'=>'form-control ut-sac-details','placeholder'=>'Details'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('oe_ut_sac')}}
                                    </span>
                                </div>
                                <div class="{{'col-md-3 child-type-status '.$childStatusType}}">
                                    <div class="form-group">
                                        {{Form::select("oe[utdata][".$key."][child_type]",$childType,!empty($value->child_type) ? $value->child_type : null,['class'=>'form-control select-padding-0','placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-1 pr-0 d-none crl-data-value-'.$key}}">
                                    <label class='vertical-form-label pr-0'>CRL :</label>
                                </div>
                                <div class="{{'col-md-2 d-none crl-data-value-'.$key}}">
                                    <div class="form-group">
                                        {{Form::number("oe[utdata][".$key."][crl]",!empty($value->crl) ? $value->crl : null,['class'=>'form-control crl-data','data-id'=>$key])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-1 p-1 crl-data-value-'.$key}}">
                                    <span class="{{'crl-text-'.$key}}">{{!empty($value->crl_details) ? $value->crl_details : null}}</span>
                                    {{Form::hidden("oe[utdata][".$key."][crl_details]",!empty($value->crl_details) ? $value->crl_details : null,['class'=>'crl-val-'.$key])}}
                                </div>
                            </div>
                        @endif
                        <div class="{{'row wks-data-'.$key.' fefal-reduction-' . $key}}">
                            <div class="col-md-1 pr-0">
                                <label class="vertical-form-label pr-0 green-lable">
                                    FCA :
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <div class="radio is-conceived">
                                    {{Form::radio("oe[utdata][".$key."][fcp]",'present',(!empty($value->fcp) && !empty($ancHistoryId)) && $value->fcp == 'present' ? true : false,['id'=>'fcp_present_'.$key,'class'=>'fcp_type fcp-type-'.$key])}}
                                    <label for="{{'fcp_present_'.$key}}">
                                        Present
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][fcp]",'absent',(!empty($value->fcp) && !empty($ancHistoryId)) && $value->fcp == 'absent' ? true : false,['id'=>'fcp_absent_'.$key,'class'=>'fcp_type fcp-type-'.$key])}}
                                    <label for="{{'fcp_absent_'.$key}}">
                                        Absent
                                    </label>
                                    {{Form::radio("oe[utdata][".$key."][fcp]",'none',(!empty($value->fcp) && !empty($ancHistoryId)) && $value->fcp == 'none' ? true : false,['id'=>'none_wk_data_'.$key,'class'=>'fcp_type fcp-type-'.$key])}}
                                    <label for="{{'none_wk_data_'.$key}}">
                                        None
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 pr-0">
                                <label class="vertical-form-label pr-0 green-lable">
                                    Liquor :
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <div class="radio is-conceived">
                                    {{Form::radio("oe[utdata][".$key."][liquor_type]",'normal',!empty($value->liquor_type) && $value->liquor_type == 'normal' ? true : false,['id'=>'liquor_normal_'.$key,'class'=>'liquor'])}}
                                    <label for="{{'liquor_normal_'.$key}}">
                                        Normal
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][liquor_type]",'oligo',!empty($value->liquor_type) && $value->liquor_type == 'oligo' ? true : false,['id'=>'liquor_oligo_'.$key,'class'=>'liquor'])}}
                                    <label for="{{'liquor_oligo_'.$key}}">
                                        Oligo
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][liquor_type]",'poly',!empty($value->liquor_type) && $value->liquor_type == 'poly' ? true : false,['id'=>'liquor_poly_'.$key,'class'=>'liquor'])}}
                                    <label for="{{'liquor_poly_'.$key}}">
                                        Poly
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][liquor_type]",'none',!empty($value->liquor_type) && $value->liquor_type == 'none' ? true : false,['id'=>'none_data_'.$key,'class'=>'liquor'])}}
                                    <label for="{{'none_data_'.$key}}">
                                        None
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 pr-0">
                                <label class="vertical-form-label pr-0 green-lable">
                                    Position :
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <div class="radio is-conceived">
                                    {{Form::radio("oe[utdata][".$key."][position_type]",'vertex',!empty($value->position_type) && $value->position_type == 'vertex' ? true : false,['id'=>'position_nertex_'.$key,'class'=>'position'])}}
                                    <label for="{{'position_nertex_'.$key}}">
                                        Vertex
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][position_type]",'breech',!empty($value->position_type) && $value->position_type == 'breech' ? true : false,['id'=>'position_breech_'.$key,'class'=>'position'])}}
                                    <label for="{{'position_breech_'.$key}}">
                                        Breech
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][position_type]",'transverse',!empty($value->position_type) && $value->position_type == 'transverse' ? true : false,['id'=>'position_transverse_'.$key,'class'=>'position'])}}
                                    <label for="{{'position_transverse_'.$key}}">
                                        Transverse
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][position_type]",'oblique',!empty($value->position_type) && $value->position_type == 'oblique' ? true : false,['id'=>'position_oblique_'.$key,'class'=>'position'])}}
                                    <label for="{{'position_oblique_'.$key}}">
                                        Oblique
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][position_type]",'none',!empty($value->position_type) && $value->position_type == 'none' ? true : false,['id'=>'none-2-'.$key,'class'=>'position'])}}
                                    <label for="{{'none-2-'.$key}}">
                                        None
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="{{'row yalk-sac-'.$key.' fefal-reduction-' . $key}}">
                            <div class="col-md-1 pr-0">
                                <label class="vertical-form-label pr-0">
                                    Yolk Sac :
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <div class="radio is-conceived">
                                    {{Form::radio("oe[utdata][".$key."][yalk_sac]",'present',!empty($value->yalk_sac) && $value->yalk_sac == 'present' ? true : false,['id'=>'present_'.$key,'class'=>'yalk_sac'])}}
                                    <label for="{{'present_'.$key}}">
                                        Present
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][yalk_sac]",'absent',!empty($value->yalk_sac) && $value->yalk_sac == 'absent' ? true : false,['id'=>'absent_'.$key,'class'=>'yalk_sac'])}}
                                    <label for="{{'absent_'.$key}}">
                                        Absent
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][yalk_sac]",'none',!empty($value->yalk_sac) && $value->yalk_sac == 'none' ? true : false,['id'=>'none_'.$key,'class'=>'yalk_sac'])}}
                                    <label for="{{'none_'.$key}}">
                                        None
                                    </label>
                                </div>
                            </div>

                            <div class="{{'col-md-1 pr-0 fefal-pole-data-'.$key}}">
                                <label class="vertical-form-label pr-0">
                                    Fetal Pole :
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <div class="radio is-conceived">
                                    {{Form::radio("oe[utdata][".$key."][fefal_pole]",'seen',!empty($value->fefal_pole) && $value->fefal_pole == 'seen' ? true : false,['id'=>'seen_'.$key,'class'=>'fefal-pole','data-id'=>$key])}}
                                    <label for="{{'seen_'.$key}}">
                                        Seen
                                    </label>

                                    {{Form::radio("oe[utdata][".$key."][fefal_pole]",'notseen',!empty($value->fefal_pole) && $value->fefal_pole == 'notseen' ? true : false,['id'=>'unseen_'.$key,'class'=>'fefal-pole','data-id'=>$key])}}
                                    <label for="{{'unseen_'.$key}}">
                                        Not Seen
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- @endif --}}
                    @endforeach
                </div>
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
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            EC Topic:
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[ec_topic]",'yes',isset($oe->ec_topic) && ($oe->ec_topic == 'yes') ? true : false,['id'=>'ec_topic_yes','class'=>'ec-topic-type'])}}
                            <label for="ec_topic_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[ec_topic]",'no', isset($oe->ec_topic) && ($oe->ec_topic == 'no') ? true : false,['id'=>'ec_topic_no','class'=>'ec-topic-type'])}}
                            <label for="ec_topic_no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @php
                        $ecData = !empty($oe->ec_topics) ? $oe->ec_topics : [];
                        $expertUsg = in_array('expert_usg',$ecData) ? '' : 'd-none';
                        $bloodReport = in_array('blood_report',$ecData) ? '' : 'd-none';
                    @endphp
                    <div class="{{ 'col-md-9 ec-topic-data ' . $ecTopic }}">
                        <div class="row">
                            <div class="col-md-2 checkbox">
                                {{Form::checkbox('oe[ec_topics][]','expert_usg', !empty($expertUsg) ? false : true,[
                                    'id'=>'expert_usg',
                                    'class'=>'ec-topic',
                                    'data-id' => 'expert-usg-details'
                                ])}}
                                <label for="expert_usg">
                                    Expert USG
                                </label>
                            </div>
                            <div class="{{ 'col-md-3 expert-usg-details ' . $expertUsg }} ">
                                <div class="form-group">
                                    {{Form::text("oe[expert_usg]", !empty($oe->expert_usg) ? $oe->expert_usg : null,[
                                        'class'=>'form-control',
                                        'placeholder'=>'Expert USG Details'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 expert-usg-details ' . $expertUsg }} ">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        Date : &nbsp;
                                    </span>
                                    {{Form::text("oe[expert_usg_date]", !empty($oe->expert_usg_date) ? $oe->expert_usg_date : \Carbon\Carbon::now()->format('D d M Y'),[
                                        'class'=>'form-control datetimepicker expert-usg-date'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 expert-usg-details ' . $expertUsg }} ">
                                <div class="form-group">
                                    {{Form::file('oe[expert_usg_image]',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Select Expert USG Image',
                                        'accept' => 'image/png,image/jpeg,image/jpg'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-1 expert-usg-details ' . $expertUsg }} ">
                                @if (isset($oe->expert_usg_image) && !empty($oe->expert_usg_image))
                                    <img src="{{url($oe->expert_usg_image)}}" class="anc-images"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="{{ 'col-md-9 ec-topic-data ' . $ecTopic }}">
                        <div class="row">
                            <div class=" col-md-2 checkbox">
                                {{Form::checkbox('oe[ec_topics][]','blood_report', !empty($bloodReport) ? false : true,[
                                    'id'=>'blood_report',
                                    'class'=>'ec-topic',
                                    'data-id' => 'blood-report-details'
                                ])}}
                                <label for="blood_report">
                                    Blood Report
                                </label>
                            </div>
                            <div class="{{ 'col-md-3 blood-report-details ' .  $bloodReport }}">
                                <div class="form-group">
                                    {{Form::text("oe[blood_report]", !empty($oe->blood_report) ? $oe->blood_report : null,[
                                        'class'=>'form-control',
                                        'placeholder'=>'Blood Report'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 blood-report-details ' .  $bloodReport }}">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        Date : &nbsp;
                                    </span>
                                    {{Form::text("oe[blood_report_date]", !empty($oe->blood_report_date) ? $oe->blood_report_date : \Carbon\Carbon::now()->format('D d M Y'),[
                                        'class'=>'form-control datetimepicker blood-report-date'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 blood-report-details ' .  $bloodReport }}">
                                <div class="form-group">
                                    {{Form::file('oe[blood_report_image]',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Select Blood Report Image',
                                        'accept' => 'image/png,image/jpeg,image/jpg'
                                    ])}}
                                </div>
                            </div>
                            {{-- {{ dd($oe)}} --}}
                            <div class="{{ 'col-md-1 blood-report-details ' .  $bloodReport }}">
                                @if (isset($oe->blood_report_image) && !empty($oe->blood_report_image))
                                    <img src="{{url($oe->blood_report_image)}}" class="anc-images"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Treact:
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[treact][type]",'medical', ($treact == 'medical') ? true : false, [
                                'id'=>'treact_medical',
                                'class'=>'treact-type'
                            ])}}
                            <label for="treact_medical">
                                Medical
                            </label>
                            {{Form::radio("oe[treact][type]",'surgical',  ($treact == 'surgical') ? true : false,['id'=>'treact_surgical','class'=>'treact-type'])}}
                            <label for="treact_surgical">
                                Surgical
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9 treact-data">
                        <div class="{{ 'row treact-medically ' . $isMedical }}">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Medicine Details : &nbsp;</span>
                                    {{Form::text("oe[treact][medicine_details]",isset($oe->treact->medicine_details) && !empty($oe->treact->medicine_details) ? $oe->treact->medicine_details : null,[
                                        'class'=>'form-control treact-medicine-details',
                                        'placeholder' => 'Medicine Details'
                                    ])}}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{Form::select("oe[treact][medicine_dose]", [
                                        '' => 'Select Dose',
                                        '1'=>'Daily',
                                        '2'=>"Once a week",
                                        '3'=>"Twice a week",
                                        '4'=>"Stat",
                                        '5'=>"SOS",
                                        '6'=>"Alternate Day",
                                        '7'=>"6 hourly",
                                        '8'=>"8 hourly",
                                        '9'=>"12 hourly",
                                        '10'=>"24 hourly"
                                    ], isset($oe->treact->medicine_dose) && !empty($oe->treact->medicine_dose) ? $oe->treact->medicine_dose : null ,[
                                        'class'=>'form-control select-padding-0',
                                        'title'=>'Doses'
                                    ])}}
                                </div>
                            </div>
                        </div>
                        <div class="{{ 'row treact-surgically ' . $isSurgical }}">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Operation Details : &nbsp;</span>
                                    {{Form::text("oe[treact][surgical_details]", isset($oe->treact->surgical_details) && !empty($oe->treact->surgical_details) ? $oe->treact->surgical_details : null,[
                                        'class'=>'form-control treact-surgical-details',
                                        'placeholder' => 'Operation Details'
                                    ])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gsac-no-data">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Follow Up Date : &nbsp;
                            </span>
                            {{Form::text("oe[follow_up]", isset($oe->follow_up) && !empty($oe->follow_up) ? $oe->follow_up : \Carbon\Carbon::now()->format('D d M Y'),[
                                'class'=>'form-control datetimepicker followup'
                            ])}}
                        </div>
                        <span class="gsac-no-data-followup form-error-msg"></span>
                    </div>
                </div>
                @php
                    $utType = 'd-none';
                    if(!empty($oe->ut->ut_type) && $oe->ut->ut_type == 2){
                        $utType = '';
                    }
                    $ovaryType = !empty($oe->ovary->ovary_type) ? $oe->ovary->ovary_type : [];
                    $ovaryLeftType = !empty($oe->ovary->left->type) && $oe->ovary->left->type == 2 ? '' : 'd-none';
                    $ovaryRightType = !empty($oe->ovary->right->type) && $oe->ovary->right->type == 2 ? '' : 'd-none';
                    // dd($ovaryRightType);
                    // if(!$ancHistoryId){
                    //     $ovaryLeftType = 'd-none';
                    //     $ovaryRightType = 'd-none';
                    // }
                    // dd($ovaryLeftType);
                @endphp
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Ovary :
                        </label>
                    </div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('oe[ovary][ovary_type][]','left',in_array('left',$ovaryType) ? true : false,['id'=>'oe_left','class'=>'plan-management','data-id'=>'oe-left-details'])}}
                            <label for="oe_left">
                                Left
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9 oe-left-details">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::select("oe[ovary][left][type]",$abArray,!empty($oe->ovary->left->type) ? $oe->ovary->left->type : null,[
                                        'class'=>'form-control select-padding-0 abnormal',
                                        'data-type'=>'ovary-left-abnormal-type'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{'col-md-4 complain-multi ovary-left-abnormal-type '.$ovaryLeftType}} ">
                                {{Form::select("oe[ovary][left][details][]",$ovaryData,!empty($oe->ovary->left->details) ? $oe->ovary->left->details : null,[
                                    'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                    'placeholder'=>'Abnormal Details',
                                    'id' => 'oe_ovary_left_details',
                                    'multiple'=>true,
                                    'data-id' => '2',
                                ])}}
                            </div>
                            <div class="col-md-4 complain-multi ovary-left-abnormal-type ml-5">
                                <div class="row edit_oe_ovary_left_details">
                                    @if (isset($oe->ovary->left->updated_details) && !empty($oe->ovary->left->updated_details))
                                        @foreach ($oe->ovary->left->updated_details as $key => $value)
                                            <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key]) . '_left' }}">
                                                {{Form::text('oe[ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                    'class' => 'form-control edited_oe_ovary_left_details',
                                                    'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key])
                                                ])}}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('oe[ovary][ovary_type][]','right',in_array('right',$ovaryType) ? true : false,[
                                'id'=>'oe_right',
                                'class'=>'plan-management',
                                'data-id'=>'oe-right-details'
                            ])}}
                            <label for="oe_right">
                                Right
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9 oe-right-details">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::select("oe[ovary][right][type]",$abArray,!empty($oe->ovary->right->type) ? $oe->ovary->right->type : null,[
                                        'class'=>'form-control select-padding-0 abnormal',
                                        'data-type'=>'ovary-right-abnormal-type'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{'col-md-4 complain-multi ovary-right-abnormal-type '.$ovaryRightType}} ">
                                {{Form::select("oe[ovary][right][details][]",$ovaryData,!empty($oe->ovary->right->details) ? $oe->ovary->right->details : null,[
                                    'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                    'placeholder'=>'Abnormal Details',
                                    'id' => 'oe_ovary_right_details',
                                    'data-id' => '2',
                                    'multiple'=>true
                                ])}}
                            </div>
                            <div class="{{ 'col-md-4 complain-multi ovary-right-abnormal-type ml-5 '.$ovaryRightType}} ">
                                <div class="row edit_oe_ovary_right_details">
                                    @if (isset($oe->ovary->right->updated_details) && !empty($oe->ovary->right->updated_details))
                                        @foreach ($oe->ovary->right->updated_details as $key => $value)
                                            <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key]) . '_right' }}">
                                                {{Form::text('oe[ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                    'class' => 'form-control edited_oe_ovary_right_details',
                                                    'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key])
                                                ])}}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <br />
                <div class="row">
                    <div class="col-md-1">
                        Adenexa:
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">Left: &nbsp;</span>
                            {{Form::text("oe[adenexa][left]",isset($oe->adenexa->left) && !empty($oe->adenexa->left) ? $oe->adenexa->left : null,[
                                'class'=>'form-control'
                            ])}}
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">Right: &nbsp;</span>
                            {{Form::text("oe[adenexa][right]",isset($oe->adenexa->right) && !empty($oe->adenexa->right) ? $oe->adenexa->left : null,[
                                'class'=>'form-control'
                            ])}}
                        </div>
                    </div>
                </div>
            </div>
            @php
                $isFefalReduction = 'd-none';
                $fefalDate = '-';
                if (!empty($oe->fefal_reduction) && isset($oe->fefal_reduction) && isset($oe->fefal_reduction->date) && !empty($oe->fefal_reduction->date)) {
                    $isFefalReduction = !empty($oe->fefal_reduction) && isset($oe->fefal_reduction->date) && (\Carbon\Carbon::parse($oe->fefal_reduction->date)->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d')) ? '' : 'd-none';
                    $fefalDate = !empty($oe->fefal_reduction) ? \Carbon\Carbon::parse($oe->fefal_reduction->date)->format('D d m Y') : null;
                }
            @endphp
            <div class="{{ 'row fefal-reduction-data ' . $isFefalReduction }} ">

                <div class="col-md-2 pr-0">
                    <label class="vertical-form-label pr-0">
                        Fetal Reduction:
                    </label>
                </div>
                <div class="col-sm-2">
                    <div class="radio is-conceived">
                        {{Form::radio("oe[fefal_reduction][type]",'yes',!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes' ? true : false,['id'=>'fefal_reduction_yes','class'=>'fefal-reduction-type','data-id'=>'1',!empty($ancId) || !empty($ancHistoryId) ? 'disabled' : ''])}}
                        <label for="fefal_reduction_yes">
                            Yes
                        </label>
                        {{Form::radio("oe[fefal_reduction][type]",'no',!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'no' ? true : false,['id'=>'fefal_reduction_no','class'=>'fefal-reduction-type','data-id'=>'1',!empty($ancId) || !empty($ancHistoryId) ? 'disabled' : ''])}}
                        <label for="fefal_reduction_no">
                            No
                        </label>
                    </div>
                </div>
                @if(!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && (!empty($ancId) || !empty($ancHistoryId)))
                    {{Form::hidden('oe[fefal_reduction][type]',$oe->fefal_reduction->type)}}
                @endif
                <div class="col-md-3 fefal-date">
                    <div class="input-group">
                        <span class="input-group-addon">Date: &nbsp;</span>
                        {{Form::text("oe[fefal_reduction][date]",!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date) ? \Carbon\Carbon::parse($oe->fefal_reduction->date)->format('D d M Y') : null,[
                            'class'=>'form-control datetimepicker',!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date) && (!empty($ancId) || !empty($ancHistoryId)) ? 'disabled' : ''])}}
                    </div>
                </div>
                @if(!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date) && (!empty($ancId) || !empty($ancHistoryId)))
                    {{Form::hidden('oe[fefal_reduction][date]',$oe->fefal_reduction->date)}}
                @endif

                @php
                    $followDate = null;
                    $howMuch = 'no';
                    if(!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date)){
                        $followDate = strtotime(\Carbon\Carbon::parse($oe->fefal_reduction->date)->format('Y-m-d'));
                    }
                    $currentDate = strtotime(\Carbon\Carbon::now()->format('Y-m-d'));
                    if(!empty($followDate) && ($followDate <= $currentDate)){
                        $howMuch = 'yes';
                    }
                @endphp
                @if($howMuch == 'yes' && $oeDataCount > 0 && (!empty($ancId) || !empty($ancHistoryId)))
                    <div class="col-md-1 pr-0 how-data how-much-data">
                        <label class="vertical-form-label pr-0">
                            How Much:
                        </label>
                    </div>
                    <div class="col-sm-2 how-data how-much-data">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[how_much][type]",'yes',!empty($oe->how_much->type) && $oe->how_much->type == 'yes' ? true : false,['id'=>'how_much_yes','class'=>'how-much-type','data-id'=>'1'])}}
                            <label for="how_much_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[how_much][type]",'no',!empty($oe->how_much->type) && $oe->how_much->type == 'no' ? true : false,['id'=>'how_much_no','class'=>'how-much-type','data-id'=>'1'])}}
                            <label for="how_much_no">
                                No
                            </label>
                        </div>
                    </div>
                    @php
                        $isFefalHowMuch = !empty($oe->fefal_reduction) && isset($oe->fefal_reduction->date) && (\Carbon\Carbon::parse($oe->fefal_reduction->date)->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d')) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes' ? '' : 'd-none';
                    @endphp
                    <div class="{{ 'col-md-2 how-much-type-data update-fefal-how-much ' . $isFefalHowMuch }}">
                        <div class="input-group">
                            <span class="input-group-addon">How Much: &nbsp;</span>
                            {{Form::number("oe[fefal_reduction][how_much_value]", !empty($oe->fefal_reduction) && isset($oe->fefal_reduction->how_much_value) && !empty($oe->fefal_reduction->how_much_value) ? $oe->fefal_reduction->how_much_value : null,[
                                'class'=>'form-control fefal-how-much-value',
                                'onwheel' => 'this.blur()',
                                'oninput' => 'maxLengthCheck(this)',
                                'maxlength' => '1',
                                'oninput' => 'checkFefalHowMuch(this.value)'
                            ])}}
                        </div>
                        <span class="form-error-msg how-much-error"></span>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-2 checkbox">
                    {{Form::checkbox('is_patient_remark',!empty($oe->is_patient_remark) && $oe->is_patient_remark == '1' ? '1' : '0',!empty($oe->is_patient_remark) && $oe->is_patient_remark == 1 ? true : false,[
                        'id'=>'is_patient_remark',
                        'class'=>'gynec-remark'
                    ])}}
                    <label for="is_patient_remark">

                    </label>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {{Form::textarea('oe[remark]', !empty($oe->remark) ? $oe->remark : null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"><span class="font-12 text-danger">*If Add Patient's Reamrk then remark is display in  patient's Application*</span></div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="headingThree_1">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="true"
                                                aria-controls="past-history">2. Treatment</a></h4>
                </div>
                <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                    <div class="panel-body" id="parent">
                        <div class="row treatment-data" id="t_data_1">
                            <div class="col-md-2 pr-0">
                                <label class="vertical-form-label pr-0">
                                    Select Medicine :
                                </label>
                            </div>
                            <div class="col-md-8 complain-multi mb-3">
                                {{Form::select('treatment[medicinedata][]',$medicines,null,['class'=>'form-control medicine co_value_data','placeholder'=>'Select Medicine'])}}
                            </div>

                        </div>
                        @if(!empty($treatment))
                            @foreach($treatment as $key=>$row)
                                <div class='row'>
                                    <div class='col-md-3'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Medicine : &nbsp</span>
                                            {{Form::text('old_treatment['.$key.'][medicine]',$row->medicine,['class'=>'form-control','disabled'])}}
                                            {{Form::hidden('old_treatment['.$key.'][medicine]',$row->medicine)}}
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Quantity : &nbsp</span>
                                            {{Form::text('old_treatment['.$key.'][quantity]',@$row->quantity,['class'=>'form-control','disabled'])}}
                                            {{Form::hidden('old_treatment['.$key.'][quantity]',@$row->quantity)}}
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class='form-group'>
                                            {{Form::select('old_treatment['.$key.'][medicine_status]',[''=>"Select Medicine Status","1"=>"જમ્યા પછી","2"=>"જમ્યા પહેલાં","3"=>"માસિકની જગ્યાએ મુકવી"],$row->medicine_status,['class'=>'form-control select-padding-0','disabled'])}}
                                            {{Form::hidden('old_treatment['.$key.'][medicine_status]',$row->medicine_status)}}
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class='form-group'>
                                            {{Form::select('old_treatment['.$key.'][dose]',['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day",'7'=>"6 hourly",'8'=>"8 hourly",'9'=>"12 hourly",'10'=>"24 hourly"],$row->dose,['class'=>'form-control select-padding-0','disabled'])}}
                                            {{Form::hidden('old_treatment['.$key.'][dose]',$row->dose)}}
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Days : &nbsp</span>
                                            {{Form::number('old_treatment['.$key.'][no]',$row->no,['class'=>'form-control','disabled'])}}
                                            {{Form::hidden('old_treatment['.$key.'][no]',$row->no)}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="medicine-data">

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                Patient is referred
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">for : &nbsp;</span>
                                    {{Form::text("usg[referfor]", !empty(@$usg->referfor) ? @$usg->referfor :'',['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">to : &nbsp;</span>
                                    {{Form::text("usg[referto]", !empty(@$usg->referto) ? @$usg->referto :'',['class'=>'form-control'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@else
    <div class="row">
        <div class="col-md-1">
            <label class="vertical-form-label pr-0">
                Seen By :
            </label>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{Form::select('seen_by',$hospitalDoctor, (!empty($ancId) || !empty($ancHistoryId)) ? $ancData->seen_by : null,['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
            </div>
            <span class="seen-by-error text-danger mb-2"></span>
        </div>
        <div class="col-md-1">
            <label class="vertical-form-label pr-0">
                RMO Doctor :
            </label>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{Form::select('rmo_doctor',$rmoDoctor,(!empty($ancId) || !empty($ancHistoryId)) ? $ancData->rmo_doctor : null,['class'=>'form-control select-padding-0','placeholder'=>'Select RMO Doctor'])}}
            </div>
        </div>
        <div class="col-md-4">
            <input type="hidden" id="saverecordname" value="{{\Carbon\Carbon::now()->format('d-m-Y').", ".ucwords($ancPatients->name).""}}">
            <script src="{{url('public/js/record/recorder.js')}}" defer></script>
            <script src="{{url('public/js/record/Fr.voice.js')}}" defer></script>
            <script src="{{url('public/js/record/recordapp.js')}}" defer></script>
            <a class="btn btn-danger btn-sm text-white" id="record" data-action="start">Start Recording</a>
            <input type="hidden" id="saverecurl" value="{{URL::to("saverec")}}">
        </div>
    </div>
    <div class="{{$class}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                        aria-controls="patients">1. Patients Basic Information</a> </h4>
        </div>
        <div id="patients" class="panel-collapse collapse p-info" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">Name : &nbsp;</span>
                            {{Form::text('name',$ancData->getPatients->name,['class'=>'form-control name'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('name')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Code : &nbsp;</span>
                            {{Form::text('code',$ancData->getPatients['code'],['class'=>'form-control code','disabled'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('code')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Age : &nbsp;</span>
                            {{Form::number("p_info[age]",!empty($patientsInfo->age) ? $patientsInfo->age : null,['class'=>'form-control age'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('age')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">weight : &nbsp;</span>
                            {{Form::text("p_info[weight]",$ancData->getPatients['weight'],['class'=>'form-control weight','id'=>'weight'])}}
                        </div>
                        <span class="form-error-msg weight">
                            {{$errors->first('weight')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Mobile : &nbsp;</span>
                            {{Form::number('mobile_number',$ancData->getPatients['mobile_number'],['class'=>'form-control mobile_number'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('mobile_number')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Visit Date : &nbsp;
                            </span>
                            {{Form::text("p_info[visit_date]",!empty($patientsInfo->visit_date) ? \Carbon\Carbon::parse($patientsInfo->visit_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('date')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{Form::select('rd_reference',$referenceDoctor,!empty($ancData->getPatients['reference_doctor_id']) ? $ancData->getPatients['reference_doctor_id'] : null,['class'=>'form-control select-padding-0 refence-doctor','placeholder'=>'Rd Reference'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('rd_reference')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Rd Mobile : &nbsp;</span>
                            {{Form::number('rd_mobile_number',!empty($ancData->getPatients->getReferenceDoctor['mobile_number']) ? $ancData->getPatients->getReferenceDoctor['mobile_number'] : null,['class'=>'form-control ref-mobile-number'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('rd_mobile_number')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">Residence : &nbsp;</span>
                            {{Form::text('residence',!empty($ancData->getPatients['residence']) ? $ancData->getPatients['residence'] : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('residence')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Area : &nbsp;</span>
                            {{Form::text('main_area',!empty($ancData->getPatients['main_area']) ? $ancData->getPatients['main_area'] : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('main_area')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">City : &nbsp;</span>
                            {{Form::text('city',!empty($ancData->getPatients['city']) ? $ancData->getPatients['city'] : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('city')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            {{Form::textarea("p_info[other_info]",!empty($patientsInfo->other_info) ? $patientsInfo->other_info : null,['class'=>'form-control no-resize other_info','placeholder'=>'Other Information','rows'=>'2'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('other_info')}}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- H/O -->
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                       data-parent="#patients-detailed-ho" href="#ho"
                                       aria-expanded="true"
                                       aria-controls="patients-detailed-ho">{{$hoNo}}. H/O</a></h4>
        </div>
        <div id="ho" class="panel-collapse collapse ho-tab" role="tabpanel"
             aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    @php
                        $hoDetails = !empty($ho->ho_details) ? $ho->ho_details : null;
                        $hoClass = '';
                        if($hoMonth == 'yes'){
                            $hoDetails = $hoDate;
                            $hoClass = 'ho_type_value';
                        }
                    @endphp
                    <div class="{{'col-md-4 complain-multi ho-value duration-value'}}">
                        {{Form::select('ho[ho_details]',$hoData,$hoDetails,['class'=>'form-control ho-data select-padding-0 anc-dose-val duration-data '.$hoClass,'placeholder'=>'Select H/O','data-medicine'=>2])}}
                        <span class="form-error-msg ho-data-msg">
                            {{$errors->first('ho_details')}}
                        </span>
                    </div>
                    {{-- </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::text("ho[amenorrhoea]",'Amenorrhoea',['class'=>'form-control','readonly'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('amenorrhoea')}}
                        </span>
                    </div>
                    @if($utType == 'yes')
                        {{Form::hidden('is_history_weight',1,['class'=>'is-weight'])}}
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-addon">Weight : &nbsp;</span>
                                {{Form::text("ho[weight]",!empty($ho->weight) ? $ho->weight  : (!empty(json_decode($ancData->patients_info)->weight) ? json_decode($ancData->patients_info)->weight : $ancData->getPatients['weight']),['class'=>'form-control weight-2','id'=>'weight'])}}
                            </div>
                            <span class="form-error-msg weight">
                                    {{$errors->first('weight')}}
                                </span>
                        </div>
                    @endif
                </div>
                @if($utType != 'yes')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{-- {{Form::text("ho[ho_details_2]",'',['class'=>'form-control','placeholder'=>'H/O Details'])}} --}}
                                {{Form::select("ho[ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($ho->ho_type) ? $ho->ho_type : null,['class'=>'form-control select-padding-0 ho_type','disabled','placeholder'=>'Select Conceived By'])}}
                                {{Form::hidden('ho[ho_type]',!empty($ho->ho_type) ? $ho->ho_type : null)}}
                            </div>
                            <span class="form-error-msg">
                                    {{$errors->first('ho_type')}}
                                </span>
                        </div>
                        <div class="col-md-6 d-none when-where">
                            <div class="input-group">
                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                {{Form::text("ho[when_where]",!empty($ho->when_where) ? $ho->when_where : null,['class'=>'form-control'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('when_where')}}
                            </span>
                        </div>
                    </div>
                @endif
                @if(!empty($ancId))
                    <div class="row">
                        <div class="col-md-1">
                            <label class="vertical-form-label pr-0">
                                UPT :
                            </label>
                        </div>

                        <div class="col-sm-2">
                            <div class="radio is-conceived">
                                {{Form::radio("p_obstratics[upt_type]",'positive',!empty($patientsObstratics->upt_type) && $patientsObstratics->upt_type  == 'positive' ? true : false,[
                                    'id'=>'positive',
                                    'class'=>'upt-type',
                                ])}}
                                <label for="positive">
                                    Positive
                                </label>

                                {{Form::radio("p_obstratics[upt_type]",'negative',!empty($patientsObstratics->upt_type) && $patientsObstratics->upt_type  == 'negative' ? true : false,[
                                    'id'=>'negative',
                                    'class'=>'upt-type',
                                ])}}
                                <label for="negative">
                                    Negative
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="from-group">
                                {{Form::text('p_obstratics[upt_details]', !empty($patientsObstratics->upt_details) ? $patientsObstratics->upt_details : null, [
                                    'class'=>'form-control upt_details datetimepicker date',
                                    'placeholder' => 'UPT Details'
                                ])}}
                            </div>
                        </div>
                        @php
                            $showUptLabel = 'd-none';
                            if (!empty($patientsObstratics->upt_details)) {
                                $showUptLabel = (is_numeric($patientsObstratics->upt_details)) ? '' : 'd-none';
                            }
                        @endphp
                        <div class="{{ 'col-md-1 upt_details_label ' . $showUptLabel }}">
                            <label class="vertical-form-label pr-0">
                                Days Before
                            </label>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- C/O -->
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#co" href="#co" aria-expanded="true"
                                        aria-controls="co">{{$coNo}}. C/O</a></h4>
        </div>
        <div id="co" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            C/O :
                        </label>
                    </div>
                    {{-- <div class="col-md-5">
                        <div class="form-group">
                            {{Form::select("co[co_type]",['nausea'=>"Nausea",'vomitting'=>"Vomitting",'giddiness'=>"Giddiness",'cold'=>"Cold",'cough'=>"Cough",'constipation'=>"Constipation",'lowerabdpain'=>"Lowerabdpain",'headache'=>"Headache",'anorexia'=>"Anorexia",'looss_motion'=>"Loossmotion",'backache'=>"Backache"],$co->co_type,['class'=>'form-control select-padding-0','disabled'])}}
                            {{Form::hidden('co[co_type]',$co->co_type)}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('co_type')}}
                        </span>
                    </div> --}}
                    @php
                        $coClass = 'complaint-data';
                        if(!empty($ancHistoryId) || !empty($ancId)){
                            $coClass = '';
                        }
                    @endphp
                    <div class="col-md-8 complain-multi">
                        {{Form::select('co[co_type][]',$complaints,!empty($co->co_type) && (!empty($ancHistoryId) || !empty($ancId)) ? $co->co_type : null,['class'=>'form-control co-value co_value_data '.$coClass,'placeholder'=>'Select complaint','multiple'=>true,'data-type'=>'0'])}}
                        <span class="form-error-msg co-value-msg">
                            {{$errors->first('since')}}
                        </span>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Since : &nbsp;</span>
                            {{Form::text("co[since]",!empty($co->since) && (!empty($ancHistoryId) || !empty($ancId)) ? $co->since : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('since')}}
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- obstratics history -->
    <div class="{{$class}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#obstratics_history" aria-expanded="true"
                                        aria-controls="obstratics_history">4. Obstetric History</a> </h4>
        </div>
        <div id="obstratics_history" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">First Marriage Life : &nbsp;</span>
                            {{Form::text("p_obstratics[first_marriage_life]",!empty($patientsObstratics->first_marriage_life) ? $patientsObstratics->first_marriage_life : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('first_marriage_life')}}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Gravida/Para/Abortaion/Live Status : &nbsp;</span>
                                {{Form::text("p_obstratics[gpal_status]",isset($patientsObstratics->gpal_status) && !empty($patientsObstratics->gpal_status) ? $patientsObstratics->gpal_status : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>

                <!-- for child -->
                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("p_obstratics[child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],!empty($patientsObstratics->child_no) ? $patientsObstratics->child_no : null,['class'=>'form-control child-no select-padding-0','placeholder'=>'Child No','data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('child_no')}}
                        </span>
                    </div>
                    @php
                        $hoType = [2,3,4];
                        $childNaturally = !empty($patientsObstratics->child_no) && $patientsObstratics->child_no != 0 ? '' : 'd-none';
                    @endphp
                </div>

                <div class="child-data">
                    {{-- for child data--}}
                    @if(!empty($patientsObstratics) && ($patientsObstratics->child_no != null && $patientsObstratics->child_no != 0))
                        @foreach($patientsObstratics->child->child_data as $key=>$row)
                            <div class="row">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        H/O :
                                    </label>
                                </div>

                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_term]",'full',!empty($row->ho_term) && $row->ho_term  == 'full' ? true : false,['id'=>'full_'.$key])}}
                                        <label for={{'full_'.$key}}>
                                            Fullterm
                                        </label>
                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_term]",'pre',!empty($row->ho_term) && $row->ho_term == 'pre' ? true : false,['id'=>'pre_'.$key])}}
                                        <label for={{'pre_'.$key}}>
                                            Preterm
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {{Form::text("p_obstratics[child][child_data][".$key."][ho_term_details]", !empty($row->ho_term_details) ? $row->ho_term_details : null, [
                                        'placeholder' => 'Term Details',
                                        'class'=>'form-control',
                                    ])}}
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[child][child_data][".$key."][child_type]",'single',!empty($row->child_type) && $row->child_type == 'single' ? true : false,['id'=>'child_type_single_'.$key])}}
                                        <label for="{{'child_type_single_'.$key}}">
                                            Single
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][".$key."][child_type]",'twins',!empty($row->child_type) && $row->child_type == 'twins' ? true : false,['id'=>'child_type_twins_'.$key])}}
                                        <label for="{{'child_type_twins_'.$key}}">
                                            Twins
                                        </label>
                                        {{Form::radio("p_obstratics[child][child_data][".$key."][child_type]",'triple',!empty($row->child_type) && $row->child_type == 'triple' ? true : false,['id'=>'child_type_triple_'.$key])}}
                                        <label for="{{'child_type_triple_'.$key}}">
                                            Triple
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    {{Form::select("p_obstratics[child][child_data][".$key."][ho_gender][]",['male'=>'Male','female'=>'Female'],isset($row->ho_gender)  ? (is_array($row->ho_gender) ? array_filter($row->ho_gender) : array($row->ho_gender)) : '',['class'=>'form-control select-padding-0','data-id'=>'','multiple','title'=>'Select Child Gender'])}}
                                    {{-- <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[child][child_data][1][ho_gender]",'male','',['id'=>'ho_male'])}}
                                        <label for="ho_male">
                                            Male
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][1][ho_gender]",'female','',['id'=>'ho_female'])}}
                                        <label for="ho_female">
                                            Female
                                        </label>
                                    </div> --}}
                                </div>
                                
                                
                            </div>
                            <br />
                            <div class="row child-data-parent">
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_type_value]",'normal',!empty($row->ho_type_value) && $row->ho_type_value == 'normal' ? true : false,['id'=>'normal_'.$key])}}
                                        <label for={{'normal_'.$key}}>
                                            Normal
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_type_value]",'cesarean',!empty($row->ho_type_value) && $row->ho_type_value == 'cesarean' ? true : false,['id'=>'cesarean_'.$key])}}
                                        <label for={{'cesarean_'.$key}}>
                                            Cesarean
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_type_value]",'instrumental',!empty($row->ho_type_value) && $row->ho_type_value == 'instrumental' ? true : false,['id'=>'instrumental_'.$key])}}
                                        <label for="{{'instrumental_'.$key}}">
                                            Instrumental
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_birth_type]",'live_health',!empty($row->ho_birth_type) && $row->ho_birth_type == 'live_health' ? true : false,['id'=>'live_health_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                        <label for={{'live_health_'.$key}}>
                                            Live Health
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_birth_type]",'stil_birth',!empty($row->ho_birth_type) && $row->ho_birth_type == 'stil_birth' ? true : false,['id'=>'stil_birth_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                        <label for={{'stil_birth_'.$key}}>
                                            Stil Birth
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][".$key."][ho_birth_type]",'expired',!empty($row->ho_birth_type) && $row->ho_birth_type == 'expired' ? true : false,['id'=>'expired_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                        <label for={{'expired_'.$key}}>
                                            Expired
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $reasonStatus = 'd-none';
                                    if(!empty($row->ho_birth_type) && $row->ho_birth_type == 'expired'){
                                        $reasonStatus = '';
                                    }
                                @endphp
                                <div class="{{'col-sm-4 expired-reason-'.$key.' '.$reasonStatus}}">
                                    <div class="form-group">
                                        {{Form::text("p_obstratics[child][child_data][".$key."][expired_reason]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Reason'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('reason')}}
                                    </span>
                                </div>
                                <div class="{{'col-sm-3 expired-reason-'.$key.' '.$reasonStatus}}">
                                    <div class="form-group">
                                        {{Form::text("p_obstratics[child][child_data][".$key."][expired_year]",!empty($row->expired_year) ? $row->expired_year : null ,['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                        {{Form::text("p_obstratics[child][child_data][".$key."][live_health_year]",!empty($row->live_health_year) ? $row->live_health_year : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('live_heatlh_year')}}
                                    </span>
                                </div>
                            </div>
                            <div class="row child-data-parent">
                                <div class="col-md-1"></div>
                                <div class="{{'col-md-4 child-naturally '.$childNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[child][child_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type p-ho-type','data-id'=>'child-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                @php
                                    $dNone = '';
                                    if(!empty($patientsObstratics->child) && !empty($row->ho_type) && !in_array($row->ho_type,$hoType)){
                                        $dNone = 'd-none';
                                    }
                                @endphp
                                <div class="{{'col-md-4 child-when-where-'.$key.' '.$dNone}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where : &nbsp;</span>
                                        {{Form::text("p_obstratics[child][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!-- for mtp -->
                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">MTP : &nbsp;</span>
                            {{Form::number("p_obstratics[mtp_no]",!empty($patientsObstratics->mtp_no) ? $patientsObstratics->mtp_no : 0,['class'=>'form-control oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('mtp')}}
                        </span>
                    </div>
                    @php
                        $mtpNaturally = !empty($patientsObstratics->mtp_no) && $patientsObstratics->mtp_no != 0 ? '' : 'd-none';
                    @endphp
                </div>

                {{-- mtp data  --}}
                <div class="mtp-data">
                    @if(!empty($patientsObstratics  && ($patientsObstratics->mtp_no != null && $patientsObstratics->mtp_no != 0 )))
                        @foreach($patientsObstratics->mtp->mtp_data as $key=>$row)
                            {{-- for mtp data --}}
                            <div class="row">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        MTP :
                                    </label>
                                </div>

                                @php
                                    $mtpStatus = 'd-none';
                                if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                    $mtpStatus = '';
                                }
                                @endphp

                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[mtp][mtp_data][".$key."][mtp_status]",'yes',!empty($row->mtp_status) && $row->mtp_status == 'yes' ? true : false,['id'=>'history_yes_'.$key,'class'=>'mtp-status','data-id'=>$key])}}
                                        <label for={{'history_yes_'.$key}}>
                                            Yes
                                        </label>

                                        {{Form::radio("p_obstratics[mtp][mtp_data][".$key."][mtp_status]",'no',!empty($row->mtp_status) && $row->mtp_status == 'no' ? true : false,['id'=>'history_no_'.$key,'class'=>'mtp-status','data-id'=>$key])}}
                                        <label for={{'history_no_'.$key}}>
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'row col-md-9 '.$mtpStatus.' mtp-visible-'.$key}}">
                                    <div class="col-sm-3">
                                        <div class="radio is-conceived">
                                            {{Form::radio("p_obstratics[mtp][mtp_data][".$key."][mtp_type]",'medically',!empty($row->mtp_type) && $row->mtp_type == 'medically' ? true : false,['id'=>'Medically_'.$key])}}
                                            <label for={{'Medically_'.$key}}>
                                                Medically
                                            </label>
                                            {{Form::radio("p_obstratics[mtp][mtp_data][".$key."][mtp_type]",'surgically',!empty($row->mtp_type) && $row->mtp_type == 'surgically' ? true : false,['id'=>'surgically_'.$key])}}
                                            <label for={{'surgically_'.$key}}>
                                                Surgically
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">MOA &nbsp;</span>
                                            {{Form::text("p_obstratics[mtp][mtp_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($row->spontancous_abortion_month_of_pregancy) ? $row->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                        </div>
                                        {{-- <span class="form-error-msg">
                                            {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                        </span> --}}
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Before&nbsp;</span>
                                            {{Form::text("p_obstratics[mtp][mtp_data][".$key."][spontancous_abortion_before]",!empty($row->spontancous_abortion_before) ? $row->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="{{'col-md-3 mtp-naturally '.$mtpNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[mtp][mtp_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 mtp-ho-type p-ho-type','data-id'=>'mtp-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('ho_details_2')}}
                                    </span>
                                </div>
                                @php
                                    $dNone = '';
                                    if(!empty($patientsObstratics->mtp) && !empty($row->ho_type) && !in_array($row->ho_type,$hoType)){
                                        $dNone = 'd-none';
                                    }
                                @endphp
                                <div class="{{'col-md-3 mtp-when-where-'.$key.' '.$dNone}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where : &nbsp;</span>
                                        {{Form::text("p_obstratics[mtp][mtp_data][".$key."][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Before&nbsp;</span>
                                        {{Form::text("p_obstratics[mtp][mtp_data][".$key."][mtp_before]",!empty(@$row->mtp_before) ? $row->mtp_before : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!-- for Abortion -->
                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Abortion : &nbsp;</span>
                            {{Form::number("p_obstratics[abortion_no]",!empty($patientsObstratics->abortion_no) ? $patientsObstratics->abortion_no : 0,['class'=>'form-control abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('abortion')}}
                        </span>
                    </div>
                    @php
                        $abortionNaturally = !empty($patientsObstratics->abortion_no) && $patientsObstratics->abortion_no != 0 ? '' : 'd-none';
                    @endphp
                </div>

                {{-- for abortion data--}}
                <div class="abortion-data">
                    @if(!empty($patientsObstratics && ($patientsObstratics->abortion_no != null && $patientsObstratics->abortion_no != 0 )))
                        @foreach($patientsObstratics->abortion->abortion_data as $key=>$value)
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Spontaneous Abortion :
                                    </label>
                                </div>
                                @php
                                    $abortionStatus = 'd-none';
                                    if(!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes'){
                                        $abortionStatus = '';
                                    }
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[abortion][abortion_data][".$key."][spontancous_abortion_status]",'yes',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes' ? true : false,['id'=>'spontancous_abortion_yes_'.$key,'class'=>'abortion-status','data-id'=>$key])}}
                                        <label for="{{'spontancous_abortion_yes_'.$key}}">
                                            Yes
                                        </label>

                                        {{Form::radio("p_obstratics[abortion][abortion_data][".$key."][spontancous_abortion_status]",'no',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'no' ? true : false,['id'=>'spontancous_abortion_no_'.$key,'class'=>'abortion-status','data-id'=>$key])}}
                                        <label for="{{'spontancous_abortion_no_'.$key}}">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'row col-md-8 '.$abortionStatus.' abortion-visible-'.$key}}">
                                    <div class="col-sm-3">
                                        <div class="radio is-conceived">
                                            {{Form::radio("p_obstratics[abortion][abortion_data][".$key."][spontancous_abortion_type]",'medically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type == 'medically' ? true : false,['id'=>'spontancous_abortion_medically_'.$key])}}
                                            <label for="{{'spontancous_abortion_medically_'.$key}}">
                                                Medically
                                            </label>
                                            {{Form::radio("p_obstratics[abortion][abortion_data][".$key."][spontancous_abortion_type]",'surgically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type== 'surgically' ? true : false,['id'=>'spontancous_abortion_surgically_'.$key])}}
                                            <label for="{{'spontancous_abortion_surgically_'.$key}}">
                                                Surgically
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">MOA &nbsp;</span>
                                            {{Form::text("p_obstratics[abortion][abortion_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($value->spontancous_abortion_month_of_pregancy) ? $value->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                        </div>
                                        <span class="form-error-msg">
                                            {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                        </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Before&nbsp;</span>
                                            {{Form::text("p_obstratics[abortion][abortion_data][".$key."][spontancous_abortion_before]",!empty($value->spontancous_abortion_before) ? $value->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="{{'col-md-4 abortion-naturally '.$abortionNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[abortion][abortion_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 abortion-ho-type p-ho-type','data-id'=>'abortion-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                @php
                                    $dNone = '';
                                    if(!empty($patientsObstratics->abortion) && !empty($value->ho_type) && !in_array($value->ho_type,$hoType)){
                                        $dNone = 'd-none';
                                    }
                                @endphp

                                <div class="{{'col-md-4 abortion-when-where-'.$key.' '.$dNone}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where : &nbsp;</span>
                                        {{Form::text("p_obstratics[abortion][abortion_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>Abortion Reason : &nbsp;</span>
                                        {{Form::text("p_obstratics[abortion][abortion_data][".$key."][reason]",!empty($value->reason) ? $value->reason : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!-- for ectopic -->
                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Ectopic : &nbsp;</span>
                            {{Form::number("p_obstratics[ectopic_no]",isset($patientsObstratics->ectopic_no) && !empty($patientsObstratics->ectopic_no) ? $patientsObstratics->ectopic_no : 0,['class'=>'form-control ectopic-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('ectopic')}}
                        </span>
                    </div>
                    @php
                        $ectopicNaturally = isset($patientsObstratics->ectopic_no) && !empty($patientsObstratics->ectopic_no) && $patientsObstratics->ectopic_no != 0 ? '' : 'd-none';
                    @endphp
                </div>
                {{-- for ectopic data --}}
                <div class="ectopic-data">
                    @if(!empty($patientsObstratics && isset($patientsObstratics->ectopic_no) &&  ($patientsObstratics->ectopic_no != null && $patientsObstratics->ectopic_no != 0 )))
                        @foreach($patientsObstratics->ectopic->ectopic_data as $key=>$value)
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Ectopic :
                                    </label>
                                </div>
                                
                                <div class="{{'row col-md-8 ectopic-visible-'.$key}}">
                                    <div class="col-sm-3">
                                        <div class="radio is-conceived">
                                            {{Form::radio("p_obstratics[ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'medically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type == 'medically' ? true : false,['id'=>'spontancous_ectopic_medically_'.$key])}}
                                            <label for="{{'spontancous_ectopic_medically_'.$key}}">
                                                Medically
                                            </label>
                                            {{Form::radio("p_obstratics[ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'surgically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type== 'surgically' ? true : false,['id'=>'spontancous_ectopic_surgically_'.$key])}}
                                            <label for="{{'spontancous_ectopic_surgically_'.$key}}">
                                                Surgically
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Before&nbsp;</span>
                                            {{Form::text("p_obstratics[ectopic][ectopic_data][".$key."][spontancous_ectopic_before]",!empty($value->spontancous_ectopic_before) ? $value->spontancous_ectopic_before : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('p_obstratics[ectopic][ectopic_data]['.$key.'][tube][]','right',isset($value->tube) && in_array('right',$value->tube) ? true : false,['id'=>'right_tube_'.$key])}}
                                            <label for="{{'right_tube_'.$key}}">
                                                Right Tube
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('p_obstratics[ectopic][ectopic_data]['.$key.'][tube][]','left',isset($value->tube) && in_array('left',$value->tube) ? true : false,['id'=>'left_tube_'.$key])}}
                                            <label for="{{'left_tube_'.$key}}">
                                                Left Tube
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="{{'col-md-4 ectopic-naturally '.$ectopicNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[ectopic][ectopic_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 ectopic-ho-type p-ho-type','data-id'=>'ectopic-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                @php
                                    $dNone = '';
                                    if(!empty($patientsObstratics->ectopic) && !empty($value->ho_type) && !in_array($value->ho_type,$hoType)){
                                        $dNone = 'd-none';
                                    }
                                @endphp

                                <div class="{{'col-md-4 ectopic-when-where-'.$key.' '.$dNone}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where : &nbsp;</span>
                                        {{Form::text("p_obstratics[ectopic][ectopic_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>Ectopic Detail : &nbsp;</span>
                                        {{Form::text("p_obstratics[ectopic][ectopic_data][".$key."][detail]",!empty($value->detail) ? $value->detail : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                {{-- for second merraige life--}}
                <div class="row">
                    <div class="col-md-2">
                        <label class="vertical-form-label pr-0">
                            Second Merriage Life :
                        </label>
                    </div>
                    @php
                        $secondMerrageStatus = 'd-none';
                        if(!empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes'){
                            $secondMerrageStatus = '';
                        }
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("p_obstratics[second_marriage_life]",'yes',!empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'yes' ? true : false,['id'=>'second_marriage_life_yes','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'2'])}}
                            <label for="second_marriage_life_yes">
                                Yes
                            </label>

                            {{Form::radio("p_obstratics[second_marriage_life]",'no',!empty($patientsObstratics->second_marriage_life) && $patientsObstratics->second_marriage_life == 'no' ? true : false,['id'=>'second_marriage_life_no','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'2'])}}
                            <label for="second_marriage_life_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{ 'col-sm-4 second-marriage-life ' . $secondMerrageStatus }}">
                        <div class="input-group">
                            <span class="input-group-addon">Second Marriage Life : &nbsp;</span>
                            {{Form::text("p_obstratics[second_marriage_details]", !empty($patientsObstratics->second_marriage_details) ? $patientsObstratics->second_marriage_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>

                <!-- for child -->
                <div class="{{'row mt-3 second-marriage-life ' . $secondMerrageStatus }}">
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("p_obstratics[second_marriage][child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],!empty($patientsObstratics->second_marriage->child_no) ? $patientsObstratics->second_marriage->child_no : null,['class'=>'form-control second-child-no select-padding-0','placeholder'=>'Child No','data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('child_no')}}
                        </span>
                    </div>
                    @php
                        $childNaturally = !empty($patientsObstratics->second_marriage->child_no) && $patientsObstratics->second_marriage->child_no != 0 ? '' : 'd-none';
                    @endphp
                </div>

                <div class="{{'second-marriage-life second-child-data ' . $secondMerrageStatus}}">
                    {{-- for child data--}}
                    @if(!empty($patientsObstratics) && isset($patientsObstratics->second_marriage) && ($patientsObstratics->second_marriage->child_no != null && $patientsObstratics->second_marriage->child_no != 0))
                        @foreach($patientsObstratics->second_marriage->child->child_data as $key=>$row)
                            <div class="row second-marriage-life-data">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        H/O :
                                    </label>
                                </div>
 
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_term]",'full',!empty($row->ho_term) && $row->ho_term  == 'full' ? true : false,['id'=>'second_full_'.$key])}}
                                        <label for={{'second_full_'.$key}}>
                                            Fullterm
                                        </label>
                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_term]",'pre',!empty($row->ho_term) && $row->ho_term == 'pre' ? true : false,['id'=>'second_pre_'.$key])}}
                                        <label for={{'second_pre_'.$key}}>
                                            Preterm
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    {{Form::text("p_obstratics[second_marriage][child][child_data][".$key."][ho_term_details]", !empty($row->ho_term_details) ? $row->ho_term_details : null, [
                                        'placeholder' => 'Term Details',
                                        'class'=>'form-control',
                                    ])}}
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][child_type]",'single',!empty($row->child_type) && $row->child_type == 'single' ? true : false,['id'=>'child_type_single_'.$key])}}
                                        <label for="{{'child_type_single_'.$key}}">
                                            Single
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][child_type]",'twins',!empty($row->child_type) && $row->child_type == 'twins' ? true : false,['id'=>'child_type_twins_'.$key])}}
                                        <label for="{{'child_type_twins_'.$key}}">
                                            Twins
                                        </label>
                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][child_type]",'triple',!empty($row->child_type) && $row->child_type == 'triple' ? true : false,['id'=>'child_type_triple_'.$key])}}
                                        <label for="{{'child_type_triple_'.$key}}">
                                            Triple
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    {{Form::select("p_obstratics[second_marriage][child][child_data][".$key."][ho_gender][]",['male'=>'Male','female'=>'Female'],isset($row->ho_gender)  ? (is_array($row->ho_gender) ? array_filter($row->ho_gender) : array($row->ho_gender)) : '',['class'=>'form-control select-padding-0','data-id'=>'','multiple','title'=>'Select Child Gender'])}}
                                    {{-- <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[child][child_data][1][ho_gender]",'male','',['id'=>'ho_male'])}}
                                        <label for="ho_male">
                                            Male
                                        </label>

                                        {{Form::radio("p_obstratics[child][child_data][1][ho_gender]",'female','',['id'=>'ho_female'])}}
                                        <label for="ho_female">
                                            Female
                                        </label>
                                    </div> --}}
                                </div>
                                
                            </div>
                            <br />
                            <div class="row second-marriage-life-data child-data-parent">
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_type_value]",'normal',!empty($row->ho_type_value) && $row->ho_type_value == 'normal' ? true : false,['id'=>'second_normal_'.$key])}}
                                        <label for={{'second_normal_'.$key}}>
                                            Normal
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_type_value]",'cesarean',!empty($row->ho_type_value) && $row->ho_type_value == 'cesarean' ? true : false,['id'=>'second_cesarean_'.$key])}}
                                        <label for={{'second_cesarean_'.$key}}>
                                            Cesarean
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_type_value]",'instrumental',!empty($row->ho_type_value) && $row->ho_type_value == 'instrumental' ? true : false,['id'=>'second_instrumental_'.$key])}}
                                        <label for="{{'second_instrumental_'.$key}}">
                                            Instrumental
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_birth_type]",'live_health',!empty($row->ho_birth_type) && $row->ho_birth_type == 'live_health' ? true : false,['id'=>'second_live_health_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                        <label for={{'second_live_health_'.$key}}>
                                            Live Health
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_birth_type]",'stil_birth',!empty($row->ho_birth_type) && $row->ho_birth_type == 'stil_birth' ? true : false,['id'=>'second_stil_birth_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                        <label for={{'second_stil_birth_'.$key}}>
                                            Stil Birth
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][child][child_data][".$key."][ho_birth_type]",'expired',!empty($row->ho_birth_type) && $row->ho_birth_type == 'expired' ? true : false,['id'=>'second_expired_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                        <label for={{'second_expired_'.$key}}>
                                            Expired
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $reasonStatus = 'd-none';
                                    if(!empty($row->ho_birth_type) && $row->ho_birth_type == 'expired'){
                                        $reasonStatus = '';
                                    }
                                @endphp
                                <div class="{{'col-sm-4 expired-reason-'.$key.' '.$reasonStatus}}">
                                    <div class="form-group">
                                        {{Form::text("p_obstratics[second_marriage][child][child_data][".$key."][expired_reason]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Reason'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('reason')}}
                                    </span>
                                </div>
                                <div class="{{'col-sm-2 expired-reason-'.$key.' '.$reasonStatus}}">
                                    <div class="form-group">
                                        {{Form::text("p_obstratics[second_marriage][child][child_data][".$key."][expired_year]",!empty($row->expired_year) ? $row->expired_year : null ,['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][child][child_data][".$key."][live_health_year]",!empty($row->live_health_year) ? $row->live_health_year : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('live_heatlh_year')}}
                                    </span>
                                </div>
                            </div>
                            <div class="row second-marriage-life-data child-data-parent">
                                <div class="col-md-1"></div>
                                <div class="{{'col-md-4 second-marriage-life-data second-child-naturally '.$childNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[second_marriage][child][child_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type second-p-ho-type','data-id'=>'second-child-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('ho_details_1')}}
                                    </span>
                                </div>
                                @php
                                    $hoType = [2,3,4];
                                    $dNone = 'd-none';
                                    if(!empty($patientsObstratics->second_marriage->child) && !empty($row->ho_type) && in_array($row->ho_type,$hoType)){
                                        $dNone = '';
                                    }
                                @endphp
                                <div class="{{'col-md-4 second-child-when-where-'.$key.' second-marriage-life-data '.$dNone.' '.$childNaturally}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where : &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][child][child_data][".$key."][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('when_where')}}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- for mtp -->
                <div class="{{'row mt-3 second-marriage-life ' . $secondMerrageStatus}}">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">MTP : &nbsp;</span>
                            {{Form::number("p_obstratics[second_marriage][mtp_no]",!empty($patientsObstratics->second_marriage->mtp_no) ? $patientsObstratics->second_marriage->mtp_no : 0,['class'=>'form-control second_oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('mtp')}}
                        </span>
                    </div>
                    @php
                        $mtpNaturally = !empty($patientsObstratics->second_marriage->mtp_no) && $patientsObstratics->second_marriage->mtp_no != 0 ? '' : 'd-none';
                    @endphp
                </div>

                {{-- mtp data  --}}
                <div class="{{'second-marriage-life second-mtp-data ' . $secondMerrageStatus }}">
                    @if(!empty($patientsObstratics) && isset($patientsObstratics->second_marriage) && ($patientsObstratics->second_marriage->mtp_no != null && $patientsObstratics->second_marriage->mtp_no != 0 ))
                        @foreach($patientsObstratics->second_marriage->mtp->mtp_data as $key=>$row)
                            {{-- for mtp data --}}
                            <div class="row second-marriage-life-data">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        MTP :
                                    </label>
                                </div>

                                @php
                                    $mtpStatus = 'd-none';
                                    if(!empty($row->mtp_status) && $row->mtp_status == 'yes'){
                                        $mtpStatus = '';
                                    }
                                @endphp

                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][".$key."][mtp_status]",'yes',!empty($row->mtp_status) && $row->mtp_status == 'yes' ? true : false,['id'=>'second_history_yes_'.$key,'class'=>'second-mtp-status','data-id'=>$key])}}
                                        <label for={{'second_history_yes_'.$key}}>
                                            Yes
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][".$key."][mtp_status]",'no',!empty($row->mtp_status) && $row->mtp_status == 'no' ? true : false,['id'=>'second_history_no_'.$key,'class'=>'second-mtp-status','data-id'=>$key])}}
                                        <label for={{'second_history_no_'.$key}}>
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'row second-marriage-life-data col-md-9 '.$mtpStatus.' second-mtp-visible-'.$key}}">
                                    <div class="col-sm-3">
                                        <div class="radio is-conceived">
                                            {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][".$key."][mtp_type]",'medically',!empty($row->mtp_type) && $row->mtp_type == 'medically' ? true : false,['id'=>'second_Medically_'.$key])}}
                                            <label for={{'second_Medically_'.$key}}>
                                                Medically
                                            </label>
                                            {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][".$key."][mtp_type]",'surgically',!empty($row->mtp_type) && $row->mtp_type == 'surgically' ? true : false,['id'=>'second_surgically_'.$key])}}
                                            <label for={{'second_surgically_'.$key}}>
                                                Surgically
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">MOA &nbsp;</span>
                                            {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($row->spontancous_abortion_month_of_pregancy) ? $row->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                        </div>
                                        <span class="form-error-msg">
                                            {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                        </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Before &nbsp;</span>
                                            {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_before]",!empty($row->spontancous_abortion_before) ? $row->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row second-marriage-life-data">
                                <div class="col-md-1"></div>
                                <div class="{{'col-md-4 second-marriage-life-data second-mtp-naturally '.$mtpNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[second_marriage][mtp][mtp_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 mtp-ho-type second-p-ho-type','data-id'=>'second-mtp-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                @php
                                    $dNone = 'd-none';
                                    if(!empty($patientsObstratics->second_marriage->mtp) && !empty($row->ho_type) && in_array($row->ho_type,$hoType)){
                                        $dNone = '';
                                    }
                                @endphp
                                <div class="{{'col-md-4 second-mtp-when-where-'.$key. ' second-marriage-life-data '.$dNone.' '.$mtpNaturally}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where : &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][mtp][mtp_data][".$key."][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- for Abortion -->
                <div class="{{'second-marriage-life row mt-3 ' . $secondMerrageStatus}}">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Abortion : &nbsp;</span>
                            {{Form::number("p_obstratics[second_marriage][abortion_no]",!empty($patientsObstratics->second_marriage->abortion_no) ? $patientsObstratics->second_marriage->abortion_no : 0,['class'=>'form-control second-abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('abortion')}}
                        </span>
                    </div>
                    @php
                        $abortionNaturally = !empty($patientsObstratics->second_marriage->abortion_no) && $patientsObstratics->second_marriage->abortion_no != 0 ? '' : 'd-none';
                    @endphp
                </div>

                {{-- for abortion data--}}
                <div class="{{'second-marriage-life second-abortion-data ' . $secondMerrageStatus }}">
                    @if(!empty($patientsObstratics && isset($patientsObstratics->second_marriage) && ($patientsObstratics->second_marriage->abortion_no != null && $patientsObstratics->second_marriage->abortion_no != 0 )))
                        @foreach($patientsObstratics->second_marriage->abortion->abortion_data as $key=>$value)
                            <div class="row second-marriage-life-data">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Spontaneous Abortion :
                                    </label>
                                </div>
                                @php
                                    $abortionStatus = 'd-none';
                                    if(!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes'){
                                        $abortionStatus = '';
                                    }
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_status]",'yes',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes' ? true : false,['id'=>'second_spontancous_abortion_yes_'.$key,'class'=>'second-abortion-status','data-id'=>$key])}}
                                        <label for="{{'second_spontancous_abortion_yes_'.$key}}">
                                            Yes
                                        </label>

                                        {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_status]",'no',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'no' ? true : false,['id'=>'second_spontancous_abortion_no_'.$key,'class'=>'second-abortion-status','data-id'=>$key])}}
                                        <label for="{{'second_spontancous_abortion_no_'.$key}}">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'row second-marriage-life-data col-md-8 '.$abortionStatus.' second-abortion-visible-'.$key}}">
                                    <div class="col-sm-3">
                                        <div class="radio is-conceived">
                                            {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_type]",'medically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type == 'medically' ? true : false,['id'=>'second_spontancous_abortion_medically_'.$key])}}
                                            <label for="{{'second_spontancous_abortion_medically_'.$key}}">
                                                Medically
                                            </label>
                                            {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_type]",'surgically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type== 'surgically' ? true : false,['id'=>'second_spontancous_abortion_surgically_'.$key])}}
                                            <label for="{{'second_spontancous_abortion_surgically_'.$key}}">
                                                Surgically
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">MOA &nbsp;</span>
                                            {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($value->spontancous_abortion_month_of_pregancy) ? $value->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                        </div>
                                        <span class="form-error-msg">
                                            {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                        </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Before &nbsp;</span>
                                            {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_before]",!empty($value->spontancous_abortion_before) ? $value->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row second-marriage-life-data">
                                <div class="col-md-1"></div>
                                <div class="{{'col-md-4 second-marriage-life-data second-abortion-naturally '.$abortionNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[second_marriage][abortion][abortion_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 abortion-ho-type second-p-ho-type','data-id'=>'second-abortion-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                @php
                                    $dNone = 'd-none';
                                    if(!empty($patientsObstratics->second_marriage->abortion) && !empty($value->ho_type) && in_array($value->ho_type,$hoType)){
                                        $dNone = '';
                                    }
                                @endphp
                                <div class="{{'col-md-4 second-marriage-life-data second-abortion-when-where-'.$key.' '.$dNone.' '.$abortionNaturally}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where: &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-4 second-marriage-life-data'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>Abortion Reason : &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][".$key."][reason]",!empty($value->reason) ? $value->reason : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!-- for Ectopic -->
                <div class="{{'second-marriage-life row mt-3 ' . $secondMerrageStatus}}">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Ectopic : &nbsp;</span>
                            {{Form::number("p_obstratics[second_marriage][ectopic_no]",isset($patientsObstratics->second_marriage->ectopic_no) && !empty($patientsObstratics->second_marriage->ectopic_no) ? $patientsObstratics->second_marriage->ectopic_no : 0,['class'=>'form-control second-ectopic-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('ectopic')}}
                        </span>
                    </div>
                    @php
                        $abortionNaturally = isset($patientsObstratics->second_marriage->ectopic_no) && !empty($patientsObstratics->second_marriage->ectopic_no) && $patientsObstratics->second_marriage->ectopic_no != 0 ? '' : 'd-none';
                    @endphp
                </div>
                {{-- for ectopic data --}}
                <div class="{{'second-marriage-life second-ectopic-data ' . $secondMerrageStatus }}">
                    @if(!empty($patientsObstratics && isset($patientsObstratics->second_marriage) && isset($patientsObstratics->second_marriage->ectopic_no) && ($patientsObstratics->second_marriage->ectopic_no != null && $patientsObstratics->second_marriage->ectopic_no != 0 )))
                        @foreach($patientsObstratics->second_marriage->ectopic->ectopic_data as $key=>$value)
                            <div class="row second-marriage-life-data">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Ectopic :
                                    </label>
                                </div>
                                <div class="{{'row second-marriage-life-data col-md-8 second-ectopic-visible-'.$key}}">
                                    <div class="col-sm-3">
                                        <div class="radio is-conceived">
                                            {{Form::radio("p_obstratics[second_marriage][ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'medically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type == 'medically' ? true : false,['id'=>'second_spontancous_ectopic_medically_'.$key])}}
                                            <label for="{{'second_spontancous_ectopic_medically_'.$key}}">
                                                Medically
                                            </label>
                                            {{Form::radio("p_obstratics[second_marriage][ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'surgically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type== 'surgically' ? true : false,['id'=>'second_spontancous_ectopic_surgically_'.$key])}}
                                            <label for="{{'second_spontancous_ectopic_surgically_'.$key}}">
                                                Surgically
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Before &nbsp;</span>
                                            {{Form::text("p_obstratics[second_marriage][ectopic][ectopic_data][".$key."][spontancous_ectopic_before]",isset($value->spontancous_ectopic_before) && !empty($value->spontancous_ectopic_before) ? $value->spontancous_ectopic_before : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('p_obstratics[second_marriage][ectopic][ectopic_data]['.$key.'][tube][]','right',isset($value->tube) && in_array('right',$value->tube) ? true : false,['id'=>'second_right_tube_'.$key])}}
                                            <label for="{{'second_right_tube_'.$key}}">
                                                Right Tube
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('p_obstratics[second_marriage][ectopic][ectopic_data]['.$key.'][tube][]','left',isset($value->tube) && in_array('left',$value->tube) ? true : false,['id'=>'second_left_tube_'.$key])}}
                                            <label for="{{'second_left_tube_'.$key}}">
                                                Left Tube
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row second-marriage-life-data">
                                <div class="{{'col-md-4 second-marriage-life-data second-ectopic-naturally '.$ectopicNaturally}}">
                                    <div class="form-group">
                                        {{Form::select("p_obstratics[second_marriage][ectopic][ectopic_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 ectopic-ho-type second-p-ho-type','data-id'=>'second-ectopic-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                    </div>
                                </div>
                                @php
                                    $dNone = 'd-none';
                                    if(!empty($patientsObstratics->second_marriage->ectopic) && !empty($value->ho_type) && in_array($value->ho_type,$hoType)){
                                        $dNone = '';
                                    }
                                @endphp
                                <div class="{{'col-md-4 second-marriage-life-data second-ectopic-when-where-'.$key.' '.$dNone.' '.$ectopicNaturally}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">When / Where: &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][ectopic][ectopic_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>Ectopic Detail : &nbsp;</span>
                                        {{Form::text("p_obstratics[second_marriage][ectopic][ectopic_data][".$key."][detail]",!empty($value->detail) ? $value->detail : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::textarea('p_obstratics[remark]',!empty($patientsObstratics->remark) ? $patientsObstratics->remark : null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal history  -->
    <div class="{{$class}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#personal-history" href="#personal-history" aria-expanded="true"
                                        aria-controls="personal-history">5. M/H</a></h4>
        </div>
        <div id="personal-history" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{Form::select("mh[age_of_menarchy]",[11=>11,12=>12,13=>13,14=>14,15=>15],!empty($mh->age_of_menarchy) ? $mh->age_of_menarchy : null,['class'=>'form-control select-padding-0','placeholder'=>'Age Of Menarchy'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('age_of_menarchy')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Since Year : &nbsp;</span>
                            {{Form::text("mh[since_year]",!empty($mh->since_year) ? $mh->since_year : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('since_year')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            {{Form::text("mh[age_of_manopause]",!empty($mh->age_of_manopause) ? $mh->age_of_manopause : null,['class'=>'form-control','placeholder'=>'Age Of Manopause'])}}
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Since Year : &nbsp;</span>
                            {{Form::text("mh[manopause_since_year]",!empty($mh->manopause_since_year) ? $mh->manopause_since_year : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label">
                            Past M/H :
                        </label>
                    </div>
                    <div class="col-md-1 past-ir-regular-data">
                        {{Form::select("mh[past_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],!empty($mh->past_mh_1) ? $mh->past_mh_1 : null,['class'=>'form-control select-padding-0 past-mh-1'])}}
                        <span class="form-error-msg">
                            {{$errors->first('past_mh_1')}}
                        </span>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {{Form::select("mh[past_mh_2]",['regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->past_mh_2) ? $mh->past_mh_2 : null,['class'=>'form-control select-padding-0 past-mh-2 regular-type','data-id'=>'past-ir-regular-data'])}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {{Form::select("mh[past_mh2_2]",['regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->past_mh2_2) ? $mh->past_mh2_2 : null,['class'=>'form-control select-padding-0'])}}
                        </div>
                    </div>
                    <div class="col-md-2 past-ir-regular-data">
                        <div class="form-group">
                            {{Form::select('mh[past_duration_of_day]',$durationOfData,!empty($mh->past_duration_of_day) ? $mh->past_duration_of_day : null,['class'=>'form-control select-padding-0 duration-data past-duration-of-day','title'=>'Select Duration Of Day','data-id'=>'past-details','data-live-search'=>'true'])}}
                        </div>
                    </div>
                    <div class="col-md-2 d-none past-details">
                        {{Form::text("mh[past_duration_of_day_details]",'',['placeholder' => 'Past Duration Of Day','class'=>'form-control past-duration-details'])}}
                    </div>
                    <div class="col-md-2 past-ir-regular-data">
                        <div class="input-group">
                            <span class="input-group-addon">Interval Of Day : &nbsp;</span>
                            {{Form::text("mh[past_interval_of_day]",!empty($mh->past_interval_of_day) ? $mh->past_interval_of_day : null,['class'=>'form-control past-interval-of-day'])}}
                        </div>
                    </div>
                    <div class="col-sm-2 past-ir-regular-data">
                        <div class="radio is-conceived">
                            {{Form::radio("mh[past_month]",'painful',!empty($mh->past_month) && $mh->past_month == 'painful' ? true : false,['id'=>'painful','class'=>'past-month past-m'])}}
                            <label for="painful">
                                Painful
                            </label>

                            {{Form::radio("mh[past_month]",'painless',!empty($mh->past_month) && $mh->past_month == 'painless' ? true : false,['id'=>'painless','class'=>'past-day past-m'])}}
                            <label for="painless">
                                Painless
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="radio is-conceived">
                            {{Form::radio("mh[same_past]",'same',!empty($mh->same_past) && $mh->same_past == 'same' ? true : false,['id'=>'same','class'=>'same'])}}
                            <label for="same">
                                Same as Past M/H
                            </label>

                            {{Form::radio("mh[same_past]",'exit',!empty($mh->same_past) && $mh->same_past == 'exit' ? true : false,['id'=>'exit','class'=>'same'])}}
                            <label for="exit">
                                Exit
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label">
                            Present M/H:
                        </label>
                    </div>

                    <div class="col-md-1 present-ir-regular-data">
                        {{Form::select("mh[present_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],!empty($mh->present_mh_1) ? $mh->present_mh_1 : null,['class'=>'form-control select-padding-0 present-mh-1'])}}
                        <span class="form-error-msg">
                            {{$errors->first('present_mh_1')}}
                        </span>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            {{Form::select("mh[present_mh_2]",['regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->present_mh_2) ? $mh->present_mh_2 : null,['class'=>'form-control select-padding-0 present-mh-2 regular-type','data-id'=>'present-ir-regular-data'])}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {{Form::select("mh[present_mh2_2]",['regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->present_mh2_2) ? $mh->present_mh2_2 : null,['class'=>'form-control select-padding-0'])}}
                        </div>
                    </div>
                    <div class="col-md-2 present-ir-regular-data">
                        <div class="form-group">
                            {{Form::select('mh[present_duration_of_day]',$durationOfData,!empty($mh->present_duration_of_day) ? $mh->present_duration_of_day : null,['class'=>'form-control select-padding-0 present-duration-of-day duration-data','title'=>'Select Duration Of Day','data-id'=>'present-details','data-live-search'=>'true'])}}
                        </div>
                    </div>
                    <div class="col-md-2 d-none present-details">
                        {{Form::text("mh[present_duration_of_day_details]",'',['placeholder' => 'Present Duration Of Day','class'=>'form-control present-duration-details'])}}
                    </div>
                    <div class="col-md-2 present-ir-regular-data">
                        <div class="input-group">
                            <span class="input-group-addon">Interval Of Day : &nbsp;</span>
                            {{Form::text("mh[present_interval_of_day]",!empty($mh->present_interval_of_day) ? $mh->present_interval_of_day : null,['class'=>'form-control present-interval-of-day'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('present_interval_of_day')}}
                        </span>
                    </div>
                    <div class="col-sm-2 present-ir-regular-data">
                        <div class="radio is-conceived">
                            {{Form::radio("mh[present_month]",'painful',!empty($mh->present_month) && $mh->present_month == 'painful' ? true : false,['id'=>'pr_painful','class'=>'present-m'])}}
                            <label for="pr_painful">
                                Painful
                            </label>

                            {{Form::radio("mh[present_month]",'painless',!empty($mh->present_month) && $mh->present_month == 'painless' ? true : false,['id'=>'pr_painless','class'=>'present-m'])}}
                            <label for="pr_painless">
                                Painless
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">Last Menstrual Date : &nbsp;</span>
                            @php
                                $lmddate = !empty($mh->last_menstrual_date) ? \Carbon\Carbon::parse($mh->last_menstrual_date)->format('D d M Y') : null;
                            @endphp
                            {{Form::text("mh[last_menstrual_date]",$lmddate,['class'=>'form-control lmd-date','required'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('last_mentsrual_date')}}
                        </span>
                    </div>


                    <div class="col-md-4">
                        <div class="input-group edd-week-data">
                            <span class="input-group-addon">EDD : &nbsp;</span>
                            @php
                                $date = !empty($mh->edd) ? \Carbon\Carbon::parse($mh->edd)->format('D d M Y') : null;
                                // $usgEddDate = !empty($mh->usg_edd) ? \Carbon\Carbon::parse($mh->usg_edd)->format('D d M Y') : null;
                            @endphp
                            {{Form::text("mh[edd]",$date,['class'=>'form-control date edd-date','disabled'])}}
                        </div>
                        {{Form::hidden("mh[edd]",$date,['class'=>'edd-date'])}}
                        <span class="form-error-msg">
                            {{$errors->first("p_details[edd]")}}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group edd-week-data">
                            <span class="input-group-addon">USG EDD : &nbsp;</span>
                            {{Form::text("mh[usg_edd]",!empty($mh->usg_edd) ? \Carbon\Carbon::parse($mh->usg_edd)->format('D d M Y') : null,['class'=>'form-control datetimepicker usg-edd-date'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Since Month : &nbsp;</span>
                            {{Form::text("mh[since_month]",!empty($mh->since_month) ? $mh->since_month : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('since_month')}}
                        </span>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Since Cycle : &nbsp;</span>
                            {{Form::text("mh[since_cycle]",!empty($mh->since_cycle) ? $mh->since_cycle : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('since_cycle')}}
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Patients Detailes --}}
    @php
        // $display_personal_history = (isset($patientsDetails->personal_history_history_type) && count($patientsDetails->personal_history_history_type) == 1 && ($patientsDetails->personal_history_history_type[0] == 'NAD' || $patientsDetails->personal_history_history_type[0] == 'nad') ) ? 'd-none' : '';
        // $display_family_history = (isset($patientsDetails->family_history) && count($patientsDetails->family_history) == 1 && ($patientsDetails->family_history[0] == 'NAD' || $patientsDetails->family_history[0] == 'nad')) ? 'd-none' : '';
        // $display_past_history = (isset($patientsDetails->past_history_type) && count($patientsDetails->past_history_type) == 1 && ($patientsDetails->past_history_type[0] == 'NAD' || $patientsDetails->past_history_type[0] == 'nad')) ? 'd-none' : '';
        $display_personal_history = '';
        $display_family_history = '';
        $display_past_history = '';
    @endphp
    <div class="panel panel-primary ho">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#patients-detailed-ho" aria-expanded="true"
                                        aria-controls="patients-detailed-ho">{{$patientsHoNo}}. Patients Detailed H/O</a></h4>
        </div>
        <div id="patients-detailed-ho" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">

                <div class="row {{$display_personal_history}}">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Personal History :
                        </label>
                    </div>
                    <div class="col-md-4 complain-multi ho-past-personal-data">
                        @if($utType != 'yes')
                            @php
                                $ancPData = (isset($patientsDetails->personal_history_history_type) && !empty($patientsDetails->personal_history_history_type)) ? $patientsDetails->personal_history_history_type : [];
                                if(!is_array($ancPData)){
                                    $ancPData = [$ancPData=>$ancPData];
                                }
                            @endphp
                            @if(!empty($ancPData))
                                @foreach($ancPData as $value)
                                    {{Form::hidden('p_detailes[personal_history_history_type][]',$value)}}
                                @endforeach
                            @endif
                        @endif
                        {{Form::select('p_detailes[personal_history_history_type][]',$personalData,(isset($patientsDetails->personal_history_history_type) && !empty($patientsDetails->personal_history_history_type)) ? $patientsDetails->personal_history_history_type : null,['class'=>'form-control co-value co_value_data personal-history mb-3',$utType == 'yes' ? '' : 'disabled','placeholder'=>'Select Personal History','multiple'=>true])}}
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                                <span class="input-group-addon">
                                    Date : &nbsp;
                                </span>
                            {{Form::text("p_detailes[personal_history_date]",(isset($patientsDetails->personal_history_date) && !empty($patientsDetails->personal_history_date)) ? \Carbon\Carbon::parse($patientsDetails->personal_history_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                </div>

                <div class="row {{$display_family_history}}">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Family History :
                        </label>
                    </div>
                    <div class="col-md-4 complain-multi">
                        {{Form::select('p_detailes[family_history][]',$familyData,(isset($patientsDetails->family_history) && !empty($patientsDetails->family_history)) ? $patientsDetails->family_history : null,['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Family History','multiple'=>true])}}
                    </div>
                </div>

                <div class="row {{$display_past_history}}">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Past History :
                        </label>
                    </div>
                    <div class="col-md-4 complain-multi ho-past-personal-data">
                        {{Form::select('p_detailes[past_history_type][]',$pastData,(isset($patientsDetails->past_history_type) && !empty($patientsDetails->past_history_type)) ? $patientsDetails->past_history_type : null,['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Past History','multiple'=>true])}}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- OE  -->
    <div class="panel panel-primary o-e">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title tab-highlight-green"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#oe" href="#oe" aria-expanded="true"
                                                            aria-controls="oe">{{$oeNo}}. O/E
                    @php
                        $msgClass = 'd-none';
                        if(!empty($oe->late_data) && !empty($oe->late_data->late_concept) && $oe->late_data->late_concept == 'Yes' && !empty($oe->late_data->late_concept_week)){
                            $msgClass = '';
                        }
                        // print_r($usgEddDate);
                    @endphp
                    {{-- @if(!empty($oe->late_concept) && $oe->late_concept == 1)       --}}
                    <span class="{{'text-danger week-message '.$msgClass}}">Late Conception</span>
                    {{-- {{Form::hidden('oe[late_concept]',1,['class'=>'late-concept'])}}
                @endif --}}
                    @if($utType == 'yes')
                        @if(!empty($lmdDate))
                            &nbsp&nbsp<span class="lmd-text">{{'( L.M.P Date: ' .\Carbon\Carbon::parse($lmdDate)->format('d-m-Y') . ' ) '}}</span>
                            {{Form::hidden('oe_lmd_date',\Carbon\Carbon::parse($lmdDate)->format('d-m-Y'))}}
                        @endif
                        @if(!empty($eddDate))
                            &nbsp&nbsp<span class="lmd-text" style="color: red;">{{'( EDD Date: ' .\Carbon\Carbon::parse($eddDate)->format('d-m-Y') . ' )' }}</span>
                            {{Form::hidden('oe_edd_date',\Carbon\Carbon::parse($eddDate)->format('d-m-Y'))}}
                        @endif
                        @if(!empty($usgEddDate))
                            &nbsp&nbsp<span class="lmd-text">{{'( USG EDD Date: ' .\Carbon\Carbon::parse($usgEddDate)->format('d-m-Y') . ' ) ' }}</span>
                            {{Form::hidden('oe_usg_edd_date',\Carbon\Carbon::parse($usgEddDate)->format('d-m-Y'))}}
                        @endif
                        @if(!empty($utersWeek) && empty($ancHistoryId) && empty($ancId))
                            &nbsp&nbsp<span class="lmd-text">{{'(Approx Uters Week: ' .$utersWeek . ' week) ' }}</span>
                        @endif
                    @endif
                </a></h4>
        </div>
        <div id="oe" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Vitals :
                        </label>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Temp : &nbsp;</span>
                            {{Form::text("oe[le][temp]",!empty($oe->le->temp) ? $oe->le->temp : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Pulse : &nbsp;</span>
                            {{Form::text("oe[le][pulse]",!empty($oe->le->pulse) ? $oe->le->pulse : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <span class="col-md-1 p-2">/ Min</span>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">B.P : &nbsp;</span>
                            {{Form::text("oe[le][bp]",!empty($oe->le->bp) ? $oe->le->bp : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <span class="col-md-1 p-2">MMHG</span>
                </div>

                @if(!empty($oe->utdata) || empty($oe->utdata))
                    <div class="row female-type-data fefal-reduction-1 d-none">
                        <div class="col-md-12 col-sm-12">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[utdata][1][female_type]",'Intrauterine',!empty($oeutdt[1]->female_type) && $oeutdt[1]->female_type == 'Intrauterine' ? true : false,['id'=>'female-intrauterine-1','class'=>'female-type','data-id'=>1])}}
                                <label for="{{'female-intrauterine-1'}}">
                                    Intrauterine
                                </label>

                                {{Form::radio("oe[utdata][1][female_type]",'Ectopic',!empty($oeutdt[1]->female_type) && $oeutdt[1]->female_type == 'Ectopic' ? true : false,['id'=>'female-ectopic-1','class'=>'female-type','data-id'=>1])}}
                                <label for="female-ectopic-1">
                                    Ectopic
                                </label>

                                {{Form::radio("oe[utdata][1][female_type]",'Molar Pregnancy',!empty($oeutdt[1]->female_type) && $oeutdt[1]->female_type == 'Molar Pregnancy' ? true : false,['id'=>'female-mp-1','class'=>'female-type','data-id'=>1])}}
                                <label for="female-mp-1">
                                    Molar Pregnancy
                                </label>

                                {{Form::radio("oe[utdata][1][female_type]",'No intrauterine or extrauterine G- Sac seen at present',!empty(@$oeutdt[1]->female_type) && $oeutdt[1]->female_type == 'No intrauterine or extrauterine G- Sac seen at present' ? true : false,['id'=>'female-gsac-1','class'=>'female-type','data-id'=>1])}}
                                <label for="female-gsac-1">
                                    No intrauterine or extrauterine G- Sac seen at present
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="no-intrauterine overy-data-1 d-none">
                        <div class="row">
                            <div class="col-md-1 pr-0">
                                <label class="vertical-form-label pr-0">
                                    Uterus :
                                </label>
                            </div>
                            <div class="col-md-2 tvs-details">
                                <div class="form-group">
                                    {{Form::select("oe[uterus][type]",$abArray,!empty(@$oe->ovary->left->type) ? $oe->ovary->left->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
                                </div>
                            </div>
                            <div class="col-md-2 uterus-abnormal-type d-none">
                                <div class="form-group">
                                    {{Form::text("oe[uterus][details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="vertical-form-label">
                                    Endometrial Thickness :
                                </label>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::text("oe[endometrial_thickness]",'',['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        Right Ovary
                                    </div>
                                    <div class='col-md-9 afcs-details'>
                                        <div class="form-group">
                                            {{Form::text("oe[ovary][right][afcs]",!empty(@$oe->ovary->right->afcs) ? $oe->ovary->right->afcs : null,['class'=>'form-control create-right-ovary-data-text'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-12 right-details">
                                        <div class="complain-multi tvs-details mt-1">
                                            {{Form::select("oe[ovary][right][details][]",$rightOvaryData,!empty(@$oe->ovary->right->details) ? $oe->ovary->right->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_right_details mb-2',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_right_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                    </div>
                                    <div class="col-md-12 complain-multi tvs-details">
                                        <div class="edit_oe_ovary_right_details">
                                        </div>
                                    </div>
                                    <div class="col-md-3 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Right Adnexia :
                                        </label>
                                    </div>
                                    <div class="col-md-4 tvs-details">
                                        <div class="form-group">
                                            {{Form::select("oe[uterus][right][adnexia]",$abArray,!empty(@$oe->ovary->right->adnexia) ? $oe->ovary->right->adnexia : null,['class'=>'form-control abnormal','data-type'=>'adnexia-right-abnormal-type'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-5 adnexia-right-abnormal-type d-none">
                                        <div class="form-group">
                                            {{Form::text("oe[uterus][right][adnexia_details]",!empty(@$oe->ovary->right->adnexia_details) ? $oe->ovary->right->adnexia_details : null,['class'=>'form-control','placeholder'=>'Adnexia Details'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 left-overy-border">
                                <div class="row">
                                    <div class="col-md-3">
                                        Left Ovary
                                    </div>
                                    <div class='col-md-9 afcs-details'>
                                        <div class="form-group">
                                            {{Form::text("oe[ovary][left][afcs]",!empty(@$oe->ovary->left->afcs) ? $oe->ovary->left->afcs : null,['class'=>'form-control create-left-ovary-data-text'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-12 left-details">
                                        <div class="complain-multi tvs-details">
                                            {{Form::select("oe[ovary][left][details][]",$leftOvaryData,!empty(@$oe->ovary->left->details) ? $oe->ovary->left->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_left_details mb-2',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_left_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                    </div>
                                    <div class="col-md-12 complain-multi tvs-details">
                                        <div class="edit_oe_ovary_left_details">
                                        </div>
                                    </div>
                                    <div class="col-md-3 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Left Adnexia :
                                        </label>
                                    </div>
                                    <div class="col-md-4 tvs-details">
                                        <div class="form-group">
                                            {{Form::select("oe[uterus][left][adnexia]",$abArray,!empty(@$oe->uterus->left->adnexia) ? $oe->uterus->left->adnexia : null,['class'=>'form-control abnormal','data-type'=>'adnexia-left-abnormal-type'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-5 adnexia-left-abnormal-type d-none">
                                        <div class="form-group">
                                            {{Form::text("oe[uterus][left][adnexia_details]",!empty(@$oe->uterus->left->adnexia_details) ? $oe->uterus->left->adnexia_details : null,['class'=>'form-control','placeholder'=>'Adnexia Details'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Select child :
                        </label>
                    </div>
                    {{Form::hidden('oe[oe_no]',!empty($oe->oe_no) ? $oe->oe_no : 1, [
                        'id' => 'oe_child_number'
                    ])}}
                    {{-- remove blighted ovum child --}}
                    @if(!empty($oe->utdata))
                        @foreach($oe->utdata as $key=>$value)
                            @if(!empty($value->blighted_ovum) && $value->blighted_ovum == 'yes' &&  empty($ancHistoryId))
                                @php
                                    $oe->oe_no = $oe->oe_no - 1;
                                @endphp
                            @endif
                        @endforeach
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select("oe[oe_no]",['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'],!empty($oe->oe_no) ? $oe->oe_no : null,[
                                'class'=>'form-control select-padding-0 oe-no',
                                'data-type'=>'ut-g-sac',
                                $selectAttr
                            ])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('oe_no')}}
                        </span>
                    </div>
                    <div class="{{'col-md-3 oe-child-type '.$childStatusType}}">
                        <div class="form-group">
                            {{Form::select("oe[oe_child_type]",$childType, !empty($oe->oe_child_type)?$oe->oe_child_type:'',['class'=>'form-control select-padding-0','placeholder'=>'Select Conceived By'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            P/A Uterus:
                        </label>
                    </div>
                    <div class="col-md-2 ut-selectpicker-1 g-sac ut-g-sac">
                        <div class="form-group">
                            {{Form::select("oe[utdata][1][oe_ut_sac_1]",$weekData,!empty($oeutdt[1]->oe_ut_sac_1) ? $oeutdt[1]->oe_ut_sac_1 : null,['class'=>'form-control select-padding-0 ut-sac max-1 crl-number g-sac-no female-type-week-1','onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'data-id'=>1,'data-value'=>'anc-history','placeholder'=>'Select Week'])}}
                        </div>
                    </div>
                    <div class="col-md-5 pa-details">
                        <div class="form-group">
                            {{Form::text("oe[p_a][details]",!empty($oe->p_a->details) ? $oe->p_a->details : null,['class'=>'form-control','placeholder'=>'P/A details'])}}
                        </div>
                    </div>
                </div>
                @php
                    $psStaus = !empty($oe->p_s->type) && $oe->p_s->type == 'yes' ? '' : 'd-none';
                    $pvStaus = !empty($oe->p_v->type) && $oe->p_v->type == 'yes' ? '' : 'd-none';
                @endphp

                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            P/S :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[p_s][type]",'yes',!empty($psStaus) ? false : true,['id'=>'ps_type_yes','class'=>'anc-status','data-type'=>'ps-details'])}}
                            <label for="ps_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[p_s][type]",'no',!empty($psStaus) ? true : false,['id'=>'ps_type_no','class'=>'anc-status','data-type'=>'ps-details'])}}
                            <label for="ps_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-5 ps-details '.$psStaus}}">
                        <div class="form-group">
                            {{Form::text("oe[p_s][details]",!empty($oe->p_s->details) ? $oe->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="text-danger p_s_no_followup"></div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            P/V :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[p_v][type]",'yes',!empty($pvStaus) ? false : true,['id'=>'pv_type_yes','class'=>'anc-status','data-type'=>'pv-details'])}}
                            <label for="pv_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[p_v][type]",'no',!empty($pvStaus) ? true : false,['id'=>'pv_type_no','class'=>'anc-status','data-type'=>'pv-details'])}}
                            <label for="pv_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-5 pv-details '.$pvStaus}}">
                        <div class="form-group">
                            {{Form::text("oe[p_v][details]",!empty($oe->p_v->details) ? $oe->p_v->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="text-danger p_v_no_followup"></div>
                </div>
                {{Form::hidden('how_much_number_data',$oeDataCount,['class'=>'child-count'])}}
                <div class="oe-data">
                    @php
                        $gSacNo = 'd-none';
                        $utdataArray = [];
                        //reset array for blighted ovum = yes
                        if(!empty($oe->utdata))
                        {
                            foreach($oe->utdata as $key=>$value)
                            {
                                if(empty($ancHistoryId))
                                {
                                    if((!isset($value->blighted_ovum)) || (empty($value->blighted_ovum) && $value->blighted_ovum != 'yes') || (!empty($value->blighted_ovum) &&  $value->blighted_ovum == 'no'))
                                    {
                                        array_push($utdataArray,$value);
                                    }
                                }
                                else {
                                    array_push($utdataArray,$value);
                                }

                            }
                        }
                    @endphp
                    @if(!empty($utdataArray))
                        @foreach($utdataArray as $key=>$value)
                            @php
                            //reset key for utdata
                            $key = $key+1;
                                if($key == 1) {
                                    $gSacNo = (isset($patientsObstratics->upt_type)) && ($patientsObstratics->upt_type == 'positive') &&  (((isset($value->oe_ut_sac) && strtolower($value->oe_ut_sac) == 'no') || (isset($value->oe_ut_sac_2)) &&  strtolower($value->oe_ut_sac_2) == 'no')) ? '' : 'd-none';
                                }
                                $yalkData =  empty($value->oe_ut_sac_1) || (!empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] < 14 && $value->oe_ut_sac_1 != 22)) ? '' : 'd-none';
                                $utWeekStatus = 'd-none';
                                $utWeekStatus2 = '';
                                if(!empty($value->female_type) && ($value->female_type == 'Ectopic' || $value->female_type == 'Molar Pregnancy')){
                                    $utWeekStatus = '';
                                    $utWeekStatus2 = 'd-none';
                                }

                            @endphp
                                <div class="{{'child-no-box G-sac-border'}}">
                                    @if($utType == 'yes')
                                        <div class="{{ 'row fefal-reduction-' .$key}}">
                                            {{Form::hidden('oe[utdata]['.$key.'][oe_ut_sac_1_status]','yes')}}
                                            <div class="{{'col-md-1 pr-0 female-type-data-'.$key.' gsac-data-'.$key.' '. $yalkData.' '.$utWeekStatus2}}">
                                                <label class="vertical-form-label pr-0">
                                                    G-sac(MM) :
                                                </label>
                                            </div>
                                            <div class="{{'col-md-2 female-type-data-'.$key.' g-sac ut-g-sac gsac-data-'.$key.' '.$yalkData.' '.$utWeekStatus2}}">
                                                <div class="form-group">
                                                    {{Form::text("oe[utdata][".$key."][oe_ut_sac_2]",!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null,['class'=>'form-control g-sac-'.$key,'onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'data-id'=>'1','data-value'=>'anc-history'])}}
                                                </div>
                                            </div>
                                            <div class="{{'col-md-2 d-none ut-g-sac-details-'.$key}}">
                                                <div class="form-group">
                                                    {{Form::text("oe[utdata][".$key."][oe_ut_sac_details]",!empty($value->oe_ut_sac_details) ? $value->oe_ut_sac_details : null,['class'=>'form-control ut-sac-details','placeholder'=>'Details'])}}
                                                </div>
                                            </div>
                                            <div class="{{'col-md-1 female-type-data-'.$key.' pr-0 crl-data-value-'.$key.' '.$yalkData.' '.$utWeekStatus2}}">
                                                <label class='vertical-form-label pr-0'>CRL :</label>
                                            </div>
                                            <div class="{{'col-md-2 female-type-data-'.$key.' crl-data-value-'.$key.' '.$yalkData.' '.$utWeekStatus2}}">
                                                <div class="form-group">
                                                    {{Form::number("oe[utdata][".$key."][crl]",!empty($value->crl) ? $value->crl : null,['class'=>'form-control crl-data','data-id'=>$key])}}
                                                </div>
                                            </div>
                                            <div class="{{'col-md-2 p-1 female-type-data-'.$key.' crl-data-value-'.$key.' '.$yalkData.' '.$utWeekStatus2}}">
                                                <span class="{{'crl-text-'.$key}}">{{!empty($value->crl_details) ? $value->crl_details : null}}</span>
                                                {{Form::hidden("oe[utdata][".$key."][crl_details]",!empty($value->crl_details) ? $value->crl_details : null,['class'=>'crl-val-'.$key])}}
                                            </div>
                                            <div class="{{'col-md-1 female-type-data-'.$key.' blighted-ovum-data pr-0 blighted-ovum-data-'.$key.' d-none'}}">
                                                <label class="vertical-form-label pr-0">
                                                    Blighted Ovum :
                                                </label>
                                            </div>
                                            <div class="{{'col-sm-3 female-type-data-'.$key.' blighted-ovum-data blighted-ovum-data-'.$key.' d-none'}}">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[utdata][".$key."][blighted_ovum]",'yes',!empty($value->blighted_ovum) && $value->blighted_ovum == 'yes' ? true : false,['id'=>'blighted-ovum-yes-'.$key,'class'=>'blighted-ovum'])}}
                                                    <label for="{{'blighted-ovum-yes-'.$key}}">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oe[utdata][".$key."][blighted_ovum]",'no',!empty($value->blighted_ovum) && $value->blighted_ovum == 'no' ? true : false,['id'=>'blighted-ovum-no-'.$key,'class'=>'blighted-ovum'])}}
                                                    <label for="{{'blighted-ovum-no-'.$key}}">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="{{ 'row fefal-reduction-' . $key}}">
                                            <div class="col-md-3 ut-g-sac pr-0 mt-2">
                                                {{Form::hidden("oe[utdata][".$key."][oe_ut_sac_1]",!empty($value->oe_ut_sac_1) ? $value->oe_ut_sac_1 : null,['class'=>'form-control ut-sac'])}}
                                                {{Form::hidden("oe[utdata][".$key."][oe_ut_sac_2]",!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null,['class'=>'form-control ut-sac-2'])}}
                                                {{strtoupper('ut(wks)')}}:- &nbsp;{{!empty($value->oe_ut_sac_1) ? $weekData[$value->oe_ut_sac_1] : null}}&nbsp;&nbsp;&nbsp;
                                            </div>
                                            <div class="col-md-3">
                                                <label>
                                                    {{strtoupper('g-sac(mm)')}}:-&nbsp;{{!empty($value->oe_ut_sac_2) ? $value->oe_ut_sac_2 : null}}
                                                </label>
                                            </div>
                                            <div class="{{'col-md-2 d-none ut-g-sac-details-'.$key}}">
                                                <div class="form-group">
                                                    {{Form::text("oe[utdata][".$key."][oe_ut_sac_details]",!empty($value->oe_ut_sac_details) ? $value->oe_ut_sac_details : null,['class'=>'form-control ut-sac-details','placeholder'=>'Details'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('oe_ut_sac')}}
                                            </span>
                                            </div>
                                            <div class="{{'col-md-1 pr-0 crl-data-value-'.$key}}">
                                                <label class='vertical-form-label pr-0'>CRL :</label>
                                            </div>
                                            <div class="{{'col-md-2 crl-data-value-'.$key}}">
                                                <div class="form-group">
                                                    {{Form::number("oe[utdata][".$key."][crl]",!empty($value->crl) ? $value->crl : null,['class'=>'form-control crl-data','data-id'=>$key])}}
                                                </div>
                                            </div>
                                            <div class="{{'col-md-1 p-1 crl-data-value-'.$key}}">
                                                <span class="{{'crl-text-'.$key}}">{{!empty($value->crl_details) ? $value->crl_details : null}}</span>
                                                {{Form::hidden("oe[utdata][".$key."][crl_details]",!empty($value->crl_details) ? $value->crl_details : null,['class'=>'crl-val-'.$key])}}
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="{{'col-md-1 female-type-data-'.$key.' blighted-ovum-data pr-0 blighted-ovum-data-'.$key.' d-none'}}">
                                                <label class="vertical-form-label pr-0">
                                                    Blighted Ovum :
                                                </label>
                                            </div>
                                            <div class="{{'col-sm-3 female-type-data-'.$key.' blighted-ovum-data blighted-ovum-data-'.$key.' d-none'}}">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[utdata][".$key."][blighted_ovum]",'yes',!empty($value->blighted_ovum) && $value->blighted_ovum == 'yes' ? true : false,['id'=>'blighted-ovum-yes-'.$key,'class'=>'blighted-ovum'])}}
                                                    <label for="{{'blighted-ovum-yes-'.$key}}">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oe[utdata][".$key."][blighted_ovum]",'no',!empty($value->blighted_ovum) && $value->blighted_ovum == 'no' ? true : false,['id'=>'blighted-ovum-no-'.$key,'class'=>'blighted-ovum'])}}
                                                    <label for="{{'blighted-ovum-no-'.$key}}">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($key == 1 && $utType != 'yes')
                                        <div class="row">
                                            <div class="col-md-2 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Late Conception :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[late_data][late_concept]",'Yes',!empty($oe->late_data->late_concept) && $oe->late_data->late_concept == 'Yes' ? true : false,['id'=>'l-yes','class'=>'late-conception-type'])}}
                                                    <label for="l-yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oe[late_data][late_concept]",'no',!empty($oe->late_data->late_concept) && $oe->late_data->late_concept == 'No' ? true : false,['id'=>'l-no','class'=>'late-conception-type'])}}
                                                    <label for="l-no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            @php
                                                $lateClass = !empty($oe->late_data->late_concept) && $oe->late_data->late_concept == 'Yes' ? '' : 'd-none';
                                            @endphp
                                            <div class="{{'col-md-2 late-concepte-week '.$lateClass}}">
                                                <div class="form-group">
                                                    {{Form::text("oe[late_data][late_concept_week]",!empty($oe->late_data->late_concept_week) ? $oe->late_data->late_concept_week : null,['class'=>'form-control late-week','onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'placeholder'=>'Enter Week'])}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @php
                                        $fefalData = !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] == 9 || ($value->oe_ut_sac_1 >= 12 || $value->oe_ut_sac_1 <= 14)) ? '' : 'd-none';
                                        $fcpLiquorData = !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] >= 18 || $weekData[$value->oe_ut_sac_1] >= 6 || $value->oe_ut_sac_1 == 22 || $value->oe_ut_sac_1 == 9) ? '' : 'd-none';
                                        $pData = !empty($value->oe_ut_sac_1) && ($weekData[$value->oe_ut_sac_1] >= 30 || $value->oe_ut_sac_1 == 22) ? '' : 'd-none';
                                        $liquorSubData = !empty($value->liquor_type) && ($value->liquor_type == 'oligo' || $value->liquor_type == 'poly') ? '' : 'd-none';
                                    @endphp

                                    <div class="{{'row fcp-data-'.$key.' wks-data-'.$key.' fefal-reduction-' . $key.' '.$fcpLiquorData}}">
                                        <div class="{{'col-md-1 pr-0 liquor-data-'.$key .' '.$fcpLiquorData}}">
                                            <label class="vertical-form-label pr-0 green-lable">
                                                Liquor :
                                            </label>
                                        </div>
                                        <div class="{{'col-sm-3 liquor-data-'.$key .' '.$fcpLiquorData}}">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oe[utdata][".$key."][liquor_type]",'normal',!empty($value->liquor_type) && $value->liquor_type == 'normal' ? true : false,['id'=>'liquor_normal_'.$key,'class'=>'liquor','data-id'=>$key])}}
                                                <label for="{{'liquor_normal_'.$key}}">
                                                    Normal
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][liquor_type]",'oligo',!empty($value->liquor_type) && $value->liquor_type == 'oligo' ? true : false,['id'=>'liquor_oligo_'.$key,'class'=>'liquor','data-id'=>$key])}}
                                                <label for="{{'liquor_oligo_'.$key}}">
                                                    Oligo
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][liquor_type]",'poly',!empty($value->liquor_type) && $value->liquor_type == 'poly' ? true : false,['id'=>'liquor_poly_'.$key,'class'=>'liquor','data-id'=>$key])}}
                                                <label for="{{'liquor_poly_'.$key}}">
                                                    Poly
                                                </label>
                                            </div>
                                        </div>
                                        <div class="{{'col-sm-3 liquor-data-'.$key .' '.$fcpLiquorData}}">
                                            <div class="{{'radio is-conceived liquor-sub-type-data-'.$key. ' '.$liquorSubData}}">
                                                {{Form::radio("oe[utdata][".$key."][liquor_sub_type]",'mild',!empty($value->liquor_sub_type) && $value->liquor_sub_type == 'mild' ? true : false,['id'=>'mild_'.$key,'class'=>'mild'])}}
                                                <label for="{{'mild_'.$key}}">
                                                    Mild
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][liquor_sub_type]",'moderate',!empty($value->liquor_sub_type) && $value->liquor_sub_type == 'moderate' ? true : false,['id'=>'moderate_'.$key,'class'=>'moderate'])}}
                                                <label for="{{'moderate_'.$key}}">
                                                    Moderate
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][liquor_sub_type]",'severe',!empty($value->liquor_sub_type) && $value->liquor_sub_type == 'severe' ? true : false,['id'=>'severe_'.$key,'class'=>'severe'])}}
                                                <label for="{{'severe_'.$key}}">
                                                    Severe
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Position--}}
                                    <div class="{{'row fcp-data-'.$key.' wks-data-'.$key.' fefal-reduction-' . $key.' '.$fcpLiquorData}}">
                                        <div class="{{'col-md-1 pr-0 position-data-'.$key.' '.$pData}}">
                                            <label class="vertical-form-label pr-0 green-lable">
                                                Position :
                                            </label>
                                        </div>
                                        <div class="{{'col-sm-4 position-data-'.$key.' '.$pData}}">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oe[utdata][".$key."][position_type]",'vertex',!empty($value->position_type) && $value->position_type == 'vertex' ? true : false,['id'=>'position_nertex_'.$key,'class'=>'position'])}}
                                                <label for="{{'position_nertex_'.$key}}">
                                                    Vertex
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][position_type]",'breech',!empty($value->position_type) && $value->position_type == 'breech' ? true : false,['id'=>'position_breech_'.$key,'class'=>'position'])}}
                                                <label for="{{'position_breech_'.$key}}">
                                                    Breech
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][position_type]",'transverse',!empty($value->position_type) && $value->position_type == 'transverse' ? true : false,['id'=>'position_transverse_'.$key,'class'=>'position'])}}
                                                <label for="{{'position_transverse_'.$key}}">
                                                    Transverse
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][position_type]",'oblique',!empty($value->position_type) && $value->position_type == 'oblique' ? true : false,['id'=>'position_oblique_'.$key,'class'=>'position'])}}
                                                <label for="{{'position_oblique_'.$key}}">
                                                    Oblique
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][position_type]",'none',!empty($value->position_type) && $value->position_type == 'none' ? true : false,['id'=>'none-2-'.$key,'class'=>'position'])}}
                                                <label for="{{'none-2-'.$key}}">
                                                    None
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Yalk--}}
                                    <div class="{{'row female-type-data-'.$key.' yalk-sac-'.$key.' fefal-reduction-' . $key.' '. $yalkData.' gsac-data-'.$key.' '.$utWeekStatus2}}">
                                        <div class="col-md-1 pr-0">
                                            <label class="vertical-form-label pr-0">
                                                Yolk Sac :
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oe[utdata][".$key."][yalk_sac]",'present',!empty($value->yalk_sac) && $value->yalk_sac == 'present' ? true : false,['id'=>'present_'.$key,'class'=>'yalk_sac'])}}
                                                <label for="{{'present_'.$key}}">
                                                    Present
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][yalk_sac]",'absent',!empty($value->yalk_sac) && $value->yalk_sac == 'absent' ? true : false,['id'=>'absent_'.$key,'class'=>'yalk_sac'])}}
                                                <label for="{{'absent_'.$key}}">
                                                    Absent
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][yalk_sac]",'none',!empty($value->yalk_sac) && $value->yalk_sac == 'none' ? true : false,['id'=>'none_'.$key,'class'=>'yalk_sac'])}}
                                                <label for="{{'none_'.$key}}">
                                                    None
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{Form::text("oe[utdata][".$key."][yalk_sac_size]",!empty(@$value->yalk_sac_size) ? @$value->yalk_sac_size: '',['class'=>'form-control','placeholder'=>'Yolk Sac Size'])}}
                                            </div>
                                        </div>
                                        <div class="{{'col-md-1 pr-0 fefal-pole-data-'.$key .' '. $fefalData}}">
                                            <label class="vertical-form-label pr-0">
                                                Fefal Pole :
                                            </label>
                                        </div>
                                        <div class="{{'col-sm-3 fefal-pole-data-'.$key .' '. $fefalData}}">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oe[utdata][".$key."][fefal_pole]",'seen',!empty($value->fefal_pole) && $value->fefal_pole == 'seen' ? true : false,['id'=>'seen_'.$key,'class'=>'fefal-pole','data-id'=>$key])}}
                                                <label for="{{'seen_'.$key}}">
                                                    Seen
                                                </label>

                                                {{Form::radio("oe[utdata][".$key."][fefal_pole]",'notseen',!empty($value->fefal_pole) && $value->fefal_pole == 'notseen' ? true : false,['id'=>'unseen_'.$key,'class'=>'fefal-pole','data-id'=>$key])}}
                                                <label for="{{'unseen_'.$key}}">
                                                    Not Seen
                                                </label>
                                                {{Form::radio("oe[utdata][".$key."][fefal_pole]",'none',!empty($value->fefal_pole) && $value->fefal_pole == 'none' ? true : false,['id'=>'none-fefa','class'=>'fefal-pole','data-id'=>$key])}}
                                                <label for="none-fefa">
                                                    None
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="{{'row fcp-data-'.$key.' wks-data-'.$key.' fefal-reduction-' . $key.' '.$fcpLiquorData}}">
                                        <div class="col-md-1 pr-0">
                                            <label class="vertical-form-label pr-0 green-lable">
                                                FCA :
                                            </label>
                                        </div>
                                        <div class="{{'col-sm-2 fcp-data-'.$key .' '.$fcpLiquorData}}">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oe[utdata][".$key."][fcp]",'present',(!empty($value->fcp) && (!empty($ancHistoryId) || !empty($ancId))) && $value->fcp == 'present' ? true : false,['id'=>'fcp_present_'.$key,'class'=>'fcp_type fcp-type-'.$key])}}
                                                <label for="{{'fcp_present_'.$key}}">
                                                    Present
                                                </label>
                                                {{Form::radio("oe[utdata][".$key."][fcp]",'absent',(!empty($value->fcp) && (!empty($ancHistoryId) || !empty($ancId))) && $value->fcp == 'absent' ? true : false,['id'=>'fcp_absent_'.$key,'class'=>'fcp_type fcp-type-'.$key])}}
                                                <label for="{{'fcp_absent_'.$key}}">
                                                    Absent
                                                </label>
                                            </div>
                                            <span class="fcp-error text-danger"></span>
                                        </div>
                                    </div>
                                    {{-- Placenta--}}
                                    <div class="{{'row p-data-'.$key. ' fefal-reduction-' . $key.' '.$pData}}">
                                        <div class="col-md-1">
                                            <label class="vertical-form-label pr-0 green-lable">
                                                Placenta:
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::select("oe[utdata][".$key."][placenta][]", $placenta, !empty($value->placenta) ? $value->placenta : null ,['class'=>'form-control select-padding-0','multiple'=> 'multiple','title'=>'Placenta Details'])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Color Dropler : &nbsp;</span>
                                                {{Form::text("oe[utdata][".$key."][color_dropler]", !empty($value->color_dropler) ? $value->color_dropler : null,[
                                                    'class'=>'form-control',
                                                    'placeholder' => 'Color Dropler'
                                                ])}}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Cervical--}}
                                    <div class="row">
                                        @if($key == 1)
                                        <div class="col-md-1 pr-0 extra-female-data-{{$key}} cervical-data-{{$key}} d-none">
                                            <label class="vertical-form-label pr-0 green-lable">
                                                Cervical length :
                                            </label>
                                        </div>
                                        <div class="col-sm-2 extra-female-data-{{$key}} cervical-data-{{$key}} d-none">
                                            {{Form::text("oe[utdata][".$key."][cervical_length]",@$value->cervical_length && !empty($value->cervical_length) ? $value->cervical_length:'',['id'=>'cervical_length','class'=>'form-control cervical_length','data-id'=>1])}}
                                        </div>
                                        @endif
                                        <div class="col-md-2 pr-0 extra-female-data-{{$key}} expected-data-{{$key}} d-none">
                                            <label class="vertical-form-label pr-0 green-lable">
                                                Expected Birth Weight :
                                            </label>
                                        </div>
                                        <div class="col-sm-2 extra-female-data-{{$key}} expected-data-{{$key}} d-none">
                                            {{Form::text("oe[utdata][".$key."][expected_birth_weight]",@$value->expected_birth_weight && !empty($value->expected_birth_weight) ? $value->expected_birth_weight:'',['id'=>'expected_birth_weight','class'=>'form-control expected_birth_weight','data-id'=>1])}}
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    @endif
                </div>
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
                <div class="{{ 'row gsac-no-data ' . $gSacNo }}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            EC Topic:
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[ec_topic]",'yes',isset($oe->ec_topic) && ($oe->ec_topic == 'yes') ? true : false,['id'=>'ec_topic_yes','class'=>'ec-topic-type'])}}
                            <label for="ec_topic_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[ec_topic]",'no', isset($oe->ec_topic) && ($oe->ec_topic == 'no') ? true : false,['id'=>'ec_topic_no','class'=>'ec-topic-type'])}}
                            <label for="ec_topic_no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="{{ 'row gsac-no-data ' . $gSacNo }}">
                    @php
                        $ecData = !empty($oe->ec_topics) ? $oe->ec_topics : [];
                        $expertUsg = in_array('expert_usg',$ecData) ? '' : 'd-none';
                        $bloodReport = in_array('blood_report',$ecData) ? '' : 'd-none';
                    @endphp
                    <div class="{{ 'col-md-9 ec-topic-data ' . $ecTopic }}">
                        <div class="row">
                            <div class="col-md-2 checkbox">
                                {{Form::checkbox('oe[ec_topics][]','expert_usg', !empty($expertUsg) ? false : true,[
                                    'id'=>'expert_usg',
                                    'class'=>'ec-topic',
                                    'data-id' => 'expert-usg-details'
                                ])}}
                                <label for="expert_usg">
                                    Expert USG
                                </label>
                            </div>
                            <div class="{{ 'col-md-3 expert-usg-details ' . $expertUsg }} ">
                                <div class="form-group">
                                    {{Form::text("oe[expert_usg]", !empty($oe->expert_usg) ? $oe->expert_usg : null,[
                                        'class'=>'form-control',
                                        'placeholder'=>'Expert USG Details'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 expert-usg-details ' . $expertUsg }} ">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        Date : &nbsp;
                                    </span>
                                    {{Form::text("oe[expert_usg_date]", !empty($oe->expert_usg_date) ? $oe->expert_usg_date : \Carbon\Carbon::now()->format('D d M Y'),[
                                        'class'=>'form-control datetimepicker expert-usg-date'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 expert-usg-details ' . $expertUsg }} ">
                                <div class="form-group">
                                    {{Form::file('oe[expert_usg_image]',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Select Expert USG Image',
                                        'accept' => 'image/png,image/jpeg,image/jpg'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-1 expert-usg-details ' . $expertUsg }} ">
                                @if (isset($oe->expert_usg_image) && !empty($oe->expert_usg_image))
                                    <img src="{{url($oe->expert_usg_image)}}" class="anc-images"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{ 'row gsac-no-data ' . $gSacNo }}">
                    <div class="{{ 'col-md-9 ec-topic-data ' . $ecTopic }}">
                        <div class="row">
                            <div class=" col-md-2 checkbox">
                                {{Form::checkbox('oe[ec_topics][]','blood_report', !empty($bloodReport) ? false : true,[
                                    'id'=>'blood_report',
                                    'class'=>'ec-topic',
                                    'data-id' => 'blood-report-details'
                                ])}}
                                <label for="blood_report">
                                    Blood Report
                                </label>
                            </div>
                            <div class="{{ 'col-md-3 blood-report-details ' .  $bloodReport }}">
                                <div class="form-group">
                                    {{Form::text("oe[blood_report]", !empty($oe->blood_report) ? $oe->blood_report : null,[
                                        'class'=>'form-control',
                                        'placeholder'=>'Blood Report'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 blood-report-details ' .  $bloodReport }}">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        Date : &nbsp;
                                    </span>
                                    {{Form::text("oe[blood_report_date]", !empty($oe->blood_report_date) ? $oe->blood_report_date : \Carbon\Carbon::now()->format('D d M Y'),[
                                        'class'=>'form-control datetimepicker blood-report-date'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-3 blood-report-details ' .  $bloodReport }}">
                                <div class="form-group">
                                    {{Form::file('oe[blood_report_image]',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Select Blood Report Image',
                                        'accept' => 'image/png,image/jpeg,image/jpg'
                                    ])}}
                                </div>
                            </div>
                            <div class="{{ 'col-md-1 blood-report-details ' .  $bloodReport }}">
                                @if (isset($oe->blood_report_image) && !empty($oe->blood_report_image))
                                    <img src="{{url($oe->blood_report_image)}}" class="anc-images"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{ 'row gsac-no-data ' . $gSacNo }}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Treact:
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[treact][type]",'medical', ($treact == 'medical') ? true : false, [
                                'id'=>'treact_medical',
                                'class'=>'treact-type'
                            ])}}
                            <label for="treact_medical">
                                Medical
                            </label>
                            {{Form::radio("oe[treact][type]",'surgical',  ($treact == 'surgical') ? true : false,['id'=>'treact_surgical','class'=>'treact-type'])}}
                            <label for="treact_surgical">
                                Surgical
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9 treact-data">
                        <div class="{{ 'row treact-medically ' . $isMedical }}">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Medicine Details : &nbsp;</span>
                                    {{Form::text("oe[treact][medicine_details]",isset($oe->treact->medicine_details) && !empty($oe->treact->medicine_details) ? $oe->treact->medicine_details : null,[
                                        'class'=>'form-control treact-medicine-details',
                                        'placeholder' => 'Medicine Details'
                                    ])}}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{Form::select("oe[treact][medicine_dose]", [
                                        '1' => 'OD',
                                        '2' => 'BD',
                                        '3' => 'TDS',
                                        '4' => 'ADS',
                                        '5' => 'Weekly / 1 time',
                                        '6' => 'Weekly / 2 time',
                                        '7' => 'Stat',
                                        '8' => 'SOS'
                                    ], isset($oe->treact->medicine_dose) && !empty($oe->treact->medicine_dose) ? $oe->treact->medicine_dose : null ,[
                                        'class'=>'form-control select-padding-0',
                                        'title'=>'Doses'
                                    ])}}
                                </div>
                            </div>
                        </div>
                        <div class="{{ 'row treact-surgically ' . $isSurgical }}">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Operation Details : &nbsp;</span>
                                    {{Form::text("oe[treact][surgical_details]", isset($oe->treact->surgical_details) && !empty($oe->treact->surgical_details) ? $oe->treact->surgical_details : null,[
                                        'class'=>'form-control treact-surgical-details',
                                        'placeholder' => 'Operation Details'
                                    ])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $isFefalReduction = 'd-none';
                    $fefalDate = '-';
                    if (!empty($oe->fefal_reduction) && isset($oe->fefal_reduction->date) && !empty($oe->fefal_reduction->date)) {
                        $isFefalReduction = !empty($oe->fefal_reduction) && isset($oe->fefal_reduction->date) && (\Carbon\Carbon::parse($oe->fefal_reduction->date)->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d')) ? '' : 'd-none';
                        $fefalDate = !empty($oe->fefal_reduction) ? \Carbon\Carbon::parse($oe->fefal_reduction->date)->format('D d m Y') : null;
                    }
                @endphp
                <div class="{{ 'row fefal-reduction-data ' . $isFefalReduction }} ">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Fetal Reduction:
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[fefal_reduction][type]",'yes',!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes' ? true : false,['id'=>'fefal_reduction_yes','class'=>'fefal-reduction-type','data-id'=>'1',(!empty($ancId) || !empty($ancHistoryId)) && (!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes') ? 'disabled' : ''])}}
                            <label for="fefal_reduction_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[fefal_reduction][type]",'no',!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'no' ? true : false,['id'=>'fefal_reduction_no','class'=>'fefal-reduction-type','data-id'=>'1',(!empty($ancId) || !empty($ancHistoryId)) && (!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes') ? 'disabled' : ''])}}
                            <label for="fefal_reduction_no">
                                No
                            </label>
                        </div>
                    </div>
                    @if(!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->type) && (!empty($ancId) || !empty($ancHistoryId)))
                        {{Form::hidden('oe[fefal_reduction][type]',$oe->fefal_reduction->type)}}
                    @endif
                    <div class="col-md-3 fefal-date">
                        <div class="input-group">
                            <span class="input-group-addon">Date: &nbsp;</span>
                            {{Form::text("oe[fefal_reduction][date]",!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date) ? \Carbon\Carbon::parse($oe->fefal_reduction->date)->format('D d M Y') : null,[
                                'class'=>'form-control datetimepicker',!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date) && (!empty($ancId) || !empty($ancHistoryId)) ? 'disabled' : ''])}}
                        </div>
                    </div>
                    @if(!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date) && (!empty($ancId) || !empty($ancHistoryId)))
                        {{Form::hidden('oe[fefal_reduction][date]',$oe->fefal_reduction->date)}}
                    @endif

                    @php
                        $followDate = null;
                        $howMuch = 'no';
                        if(!empty($oe->fefal_reduction) && !empty($oe->fefal_reduction->date)){
                            $followDate = strtotime(\Carbon\Carbon::parse($oe->fefal_reduction->date)->format('Y-m-d'));
                        }
                        $currentDate = strtotime(\Carbon\Carbon::now()->format('Y-m-d'));
                        if(!empty($followDate) && ($followDate <= $currentDate)){
                            $howMuch = 'yes';
                        }
                    @endphp
                    @if($howMuch == 'yes' && $oeDataCount > 0 && (!empty($ancId) || !empty($ancHistoryId)))
                        {{-- <div class="row"> --}}
                        <div class="col-md-1 pr-0 how-data how-much-data">
                            <label class="vertical-form-label pr-0">
                                How Much:
                            </label>
                        </div>
                        <div class="col-sm-2 how-data how-much-data">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[how_much][type]",'yes',!empty($oe->how_much->type) && $oe->how_much->type == 'yes' ? true : false,['id'=>'how_much_yes','class'=>'how-much-type','data-id'=>'1'])}}
                                <label for="how_much_yes">
                                    Yes
                                </label>
                                {{Form::radio("oe[how_much][type]",'no',!empty($oe->how_much->type) && $oe->how_much->type == 'no' ? true : false,['id'=>'how_much_no','class'=>'how-much-type','data-id'=>'1'])}}
                                <label for="how_much_no">
                                    No
                                </label>
                            </div>
                        </div>
                        @php

                            $isFefalHowMuch = !empty($oe->fefal_reduction) && isset($oe->fefal_reduction->date) && (\Carbon\Carbon::parse($oe->fefal_reduction->date)->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d')) && !empty($oe->fefal_reduction->type) && $oe->fefal_reduction->type == 'yes' ? '' : 'd-none';

                        @endphp
                        <div class="{{ 'col-md-2 how-much-type-data update-fefal-how-much ' . $isFefalHowMuch }}">
                            <div class="input-group">
                                <span class="input-group-addon">How Much: &nbsp;</span>
                                {{Form::number("oe[fefal_reduction][how_much_value]",!empty($oe->fefal_reduction) && isset($oe->fefal_reduction->how_much_value) && !empty($oe->fefal_reduction->how_much_value) ? $oe->fefal_reduction->how_much_value : null,[
                                    'class'=>'form-control fefal-how-much-value',
                                    'onwheel' => 'this.blur()',
                                    'oninput' => 'maxLengthCheck(this)',
                                    'maxlength' => '1',
                                    'oninput' => 'checkFefalHowMuch(this.value)'
                                ])}}
                            </div>
                            <span class="form-error-msg how-much-error"></span>
                        </div>
                    @endif
                </div>


                <div class="row">
                    {{-- <div class="col-md-2 checkbox">
                        {{Form::checkbox('oe[is_patient_remark]',!empty($oe->is_patient_remark) && $oe->is_patient_remark == '1' ? '1' : '0',!empty($oe->is_patient_remark) && $oe->is_patient_remark == 1 ? true : false,[
                            'id'=>'is_patient_remark',
                            'class'=>'anc-remark'
                        ])}}
                        <label for="is_patient_remark">
                          Patient's Remark
                        </label>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            {{Form::textarea('oe[pt_remark]', isset($oe->pt_remark) && !empty($oe->pt_remark) ? $oe->pt_remark : null, ['class'=>'form-control no-resize remark','placeholder'=>'Patient Remark','rows'=>'2'])}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{Form::textarea('oe[remark]', !empty($oe->remark) ? $oe->remark : null, ['class'=>'form-control no-resize remark','placeholder'=>'Dr. Remark','rows'=>'2'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"><span class="font-12 text-danger">*If Add Patient's Reamrk then remark is display in  patient's Application*</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Investigation  -->
    <div class="panel panel-primary investigation">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#investigation" href="#investigation" aria-expanded="true"
                                        aria-controls="investigation">{{$investigationNo}}. Investigation</a></h4>
        </div>
        <div id="investigation" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                @php
                    $lftStatus = !empty($patientsInvestigation->anc_lft) && $patientsInvestigation->anc_lft == 'lft' ? true : false;
                    $lftAbStatus = !empty($patientsInvestigation->anc_lft_normal_status) && $patientsInvestigation->anc_lft_normal_status == '2' ? '' : 'd-none';
                    $lftData = $lftStatus ? '' : 'd-none';
                    $oeUtData = [];
                    $oeUtData = !empty($oe->utdata) ? (array)$oe->utdata : [];
                    $lftDataStatus = 'd-none';
                    $lftDataStatusType = '';
                    $oeFemaleStatus = 0;
                    $ancLabel = 'ANC Profile';
                    if(!empty($oeUtData)){
                        $childNumber = !empty($oe->oe_no) && $oe->oe_no == 1 ? 1 : 0;
                        if($childNumber){
                            $oeFemaleStatus =  !empty($oeUtData[1]->female_type) && ($oeUtData[1]->female_type == 'Ectopic' || $oeUtData[1]->female_type == 'Molar Pregnancy') ? 1 : 0;
                        }
                        if($oeFemaleStatus == 1){
                            $ancLabel = 'Pre Operative';
                            $lftDataStatus = '';
                            $lftDataStatusType = 'd-none';
                        }
                    }
                    $investigationValue = !empty($patientsInvestigation->investigation_details) ? (array)$patientsInvestigation->investigation_details : [];
                    $cbcWnlStatus = !empty($patientsInvestigation->investigation_cbc_mp_details->status) && $patientsInvestigation->investigation_cbc_mp_details->status == '2' ? '' : 'd-none';
                    $urinWnlStatus = !empty($patientsInvestigation->investigation_urine_value_details->status) && $patientsInvestigation->investigation_urine_value_details->status == '2' ? '' : 'd-none';
                    $tshWnlStatus = !empty($patientsInvestigation->investigation_tsh_value_details->status) && $patientsInvestigation->investigation_tsh_value_details->status == '2' ? '' : 'd-none';
                @endphp
                {{-- report data --}}
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','1',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(1,$patientsInvestigation) : null,['id'=>'cbc_mp','class'=>'plan-management','data-id'=>'cbc-mp-details'])}}
                            <label for="cbc_mp">
                                CBC / MP
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 cbc-mp-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(1,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][1]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[1]) ? $investigationValue[1] : null,['class'=>'form-control','placeholder'=>'CBC MP Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','2',(!empty($ancHistoryId) || !empty($ancId)) && (!empty($ancHistoryId) || !empty($ancId)) ? checkData(2,$patientsInvestigation) : null,['id'=>'fbs','class'=>'plan-management','data-id'=>'fbs-data-details'])}}
                            <label for="fbs">
                                FBS
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 fbs-data-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(2,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][2]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[2]) ? $investigationValue[2] : null,['class'=>'form-control','placeholder'=>'FBS Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row cbc-mp-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(1,$patientsInvestigation) ? '' : 'd-none')}}">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_cbc_mp_details][status]",$wnlArray,(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_cbc_mp_details->status) ? $patientsInvestigation->investigation_cbc_mp_details->status : null,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 cbc-mb-type-details-value '.$cbcWnlStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Aneamia : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_cbc_mp_details][aneamia]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_cbc_mp_details->aneamia) ? $patientsInvestigation->investigation_cbc_mp_details->aneamia : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 cbc-mb-type-details-value '.$cbcWnlStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Leacocytosis : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_cbc_mp_details][leacocytosis]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_cbc_mp_details->leacocytosis) ? $patientsInvestigation->investigation_cbc_mp_details->leacocytosis : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','3',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(3,$patientsInvestigation) : null,['id'=>'urine_r','class'=>'plan-management','data-id'=>'urine-details'])}}
                            <label for="urine_r">
                                Urine - R
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 urine-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(3,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][3]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[3]) ? $investigationValue[3] : null,['class'=>'form-control','placeholder'=>'Urine Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','4',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(4,$patientsInvestigation) : null,['id'=>'ppbs','class'=>'plan-management','data-id'=>'ppbs-data-details'])}}
                            <label for="ppbs">
                                PPBS
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 ppbs-data-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(4,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][4]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[4]) ? $investigationValue[4] : null,['class'=>'form-control','placeholder'=>'PPBS Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row urine-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(3,$patientsInvestigation) ? '' : 'd-none')}}">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_urine_value_details][status]",$wnlArray,(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_urine_value_details->status) ? $patientsInvestigation->investigation_urine_value_details->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-details-value','placeholder'=>'Select CBC MB Type'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 urine-details-value '.$urinWnlStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Aneamia : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_urine_value_details][aneamia]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_urine_value_details->aneamia) ? $patientsInvestigation->investigation_urine_value_details->aneamia : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 urine-details-value '.$urinWnlStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Leacocytosis : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_urine_value_details][leacocytosis]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_urine_value_details->leacocytosis) ? $patientsInvestigation->investigation_urine_value_details->leacocytosis : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','5',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(5,$patientsInvestigation) : null,['id'=>'esr','class'=>'plan-management','data-id'=>'esr-details'])}}
                            <label for="esr">
                                ESR
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 esr-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(5,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][5]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[5]) ? $investigationValue[5] : null,['class'=>'form-control','placeholder'=>'ESR Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','6',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(6,$patientsInvestigation) : null,['id'=>'rbs','class'=>'plan-management','data-id'=>'rbs-data-details'])}}
                            <label for="rbs">
                                RBS
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 rbs-data-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(6,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][6]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[6]) ? $investigationValue[6] : null,['class'=>'form-control','placeholder'=>'RBS Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','7',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(7,$patientsInvestigation) : null,['id'=>'sgpt','class'=>'plan-management','data-id'=>'sgpt-details'])}}
                            <label for="sgpt">
                                SGPT
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 sgpt-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(7,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][7]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[7]) ? $investigationValue[7] : null,['class'=>'form-control','placeholder'=>'SGPT Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','8',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(8,$patientsInvestigation) : null,['id'=>'hbsag','class'=>'plan-management','data-id'=>'hbsag-details'])}}
                            <label for="hbsag">
                                HBsAg
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 hbsag-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(8,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][8]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[8]) ? $investigationValue[8] : null,['class'=>'form-control','placeholder'=>'HBsAg Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','9',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(9,$patientsInvestigation) : null,['id'=>'screatinine','class'=>'plan-management','data-id'=>'screatinine-details'])}}
                            <label for="screatinine">
                                S.Creatinine
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 screatinine-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(9,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][9]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[9]) ? $investigationValue[9] : null,['class'=>'form-control','placeholder'=>'S. Creatinine Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','10',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(10,$patientsInvestigation) : null,['id'=>'hiv','class'=>'plan-management','data-id'=>'hiv-details'])}}
                            <label for="hiv">
                                HIV
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 hiv-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(10,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][10]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[10]) ? $investigationValue[10] : null,['class'=>'form-control','placeholder'=>'HIV Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','11',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(11,$patientsInvestigation) : null,['id'=>'crp','class'=>'plan-management','data-id'=>'crp-details'])}}
                            <label for="crp">
                                CRP
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 crp-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(11,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][11]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[11]) ? $investigationValue[11] : null,['class'=>'form-control','placeholder'=>'CRP Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','12',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(12,$patientsInvestigation) : null,['id'=>'blood_group','class'=>'plan-management','data-id'=>'blood-details'])}}
                            <label for="blood_group">
                                Blood Group
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 blood-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(12,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][12]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[12]) ? $investigationValue[12] : null,['class'=>'form-control','placeholder'=>'Blood Group Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','13',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(13,$patientsInvestigation) : null,['id'=>'slied','class'=>'plan-management','data-id'=>'slied-details'])}}
                            <label for="slied">
                                Serum Widal
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 slied-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(13,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][13]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[13]) ? $investigationValue[13] : null,['class'=>'form-control','placeholder'=>'Serum Widal Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','14',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(14,$patientsInvestigation) : null,['id'=>'tsh','class'=>'plan-management','data-id'=>'tsh-data-details'])}}
                            <label for="tsh">
                                TSH
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 tsh-data-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(14,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][14]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[14]) ? $investigationValue[14] : null,['class'=>'form-control','placeholder'=>'TSH Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row tsh-data-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(14,$patientsInvestigation) ? '' : 'd-none')}}">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_tsh_value_details][status]",$wnlArray,(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_tsh_value_details->status) ? $patientsInvestigation->investigation_tsh_value_details->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>'tsh-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 tsh-type-details-value '.$tshWnlStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Aneamia : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_tsh_value_details][aneamia]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_tsh_value_details->aneamia) ? $patientsInvestigation->investigation_tsh_value_details->aneamia : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 tsh-type-details-value '.$tshWnlStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Leacocytosis : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_tsh_value_details][leacocytosis]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->investigation_tsh_value_details->leacocytosis) ? $patientsInvestigation->investigation_tsh_value_details->leacocytosis : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','15',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(15,$patientsInvestigation) : null,['id'=>'typhidot','class'=>'plan-management','data-id'=>'typhidot-lgm-details'])}}
                            <label for="typhidot">
                                Typhidot lgM
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 typhidot-lgm-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(15,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][15]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[15]) ? $investigationValue[15] : null,['class'=>'form-control','placeholder'=>'Typhidot lgM Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','16',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(16,$patientsInvestigation) : null,['id'=>'t3','class'=>'plan-management','data-id'=>'t3-details'])}}
                            <label for="t3">
                                T3, T4, TSH
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 t3-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(16,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][16]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[16]) ? $investigationValue[16] : null,['class'=>'form-control','placeholder'=>'T3, T4, TSH Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','17',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(17,$patientsInvestigation) : null,['id'=>'lipid_profile','class'=>'plan-management','data-id'=>'lipid-profile-details'])}}
                            <label for="lipid_profile">
                                Lipid Profile
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 lipid-profile-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(17,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][17]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[17]) ? $investigationValue[17] : null,['class'=>'form-control','placeholder'=>'Lipid Profile Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','18',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(18,$patientsInvestigation) : null,['id'=>'vitb12','class'=>'plan-management','data-id'=>'vit-b12-details'])}}
                            <label for="vitb12">
                                Vit B-12
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 vit-b12-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(18,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][18]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[18]) ? $investigationValue[18] : null,['class'=>'form-control','placeholder'=>'Vit B-12 Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','19',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(19,$patientsInvestigation) : null,['id'=>'tube-widal','class'=>'plan-management','data-id'=>'tube-widal-details'])}}
                            <label for="tube-widal">
                                Tube Widal
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 tube-widal-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(19,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][19]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[19]) ? $investigationValue[19] : null,['class'=>'form-control','placeholder'=>'Tube Widal Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','20',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(20,$patientsInvestigation) : null,['id'=>'vitd3','class'=>'plan-management','data-id'=>'vit-d3-details'])}}
                            <label for="vitd3">
                                Vit D-3
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 vit-d3-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(20,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][20]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[20]) ? $investigationValue[20] : null,['class'=>'form-control','placeholder'=>'Vit D-3 Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','21',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(21,$patientsInvestigation) : null,['id'=>'lft','class'=>'plan-management','data-id'=>'lft-details'])}}
                            <label for="lft">
                                LFT
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 lft-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(21,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][21]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[21]) ? $investigationValue[21] : null,['class'=>'form-control','placeholder'=>'LFT Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','22',(!empty($ancHistoryId) || !empty($ancId)) && (!empty($ancHistoryId) || !empty($ancId)) ? checkData(22,$patientsInvestigation) : null,['id'=>'anc_profile','class'=>'plan-management','data-id'=>'anc-profile-details'])}}
                            <label for="anc_profile">
                                ANC Profile
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 anc-profile-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(22,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][22]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[22]) ? $investigationValue[22] : null,['class'=>'form-control','placeholder'=>'ANC Profile Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','23',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(23,$patientsInvestigation) : null,['id'=>'rft','class'=>'plan-management','data-id'=>'rft-details'])}}
                            <label for="rft">
                                RFT
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 rft-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(23,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][23]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[23]) ? $investigationValue[23] : null,['class'=>'form-control','placeholder'=>'RFT Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','24',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(24,$patientsInvestigation) : null,['id'=>'pre_major','class'=>'plan-management','data-id'=>'pre-major-profile-details'])}}
                            <label for="pre_major">
                                Pre oper.Profile(Major)
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 pre-major-profile-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(24,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][24]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[24]) ? $investigationValue[24] : null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Major) Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','25',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(25,$patientsInvestigation) : null,['id'=>'scalcium','class'=>'plan-management','data-id'=>'scalcium-details'])}}
                            <label for="scalcium">
                                S.Calcium
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 scalcium-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(25,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][25]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[25]) ? $investigationValue[25] : null,['class'=>'form-control','placeholder'=>'S. Calcium Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','26',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(26,$patientsInvestigation) : null,['id'=>'pre_minor','class'=>'plan-management','data-id'=>'pre-minor-profile-details'])}}
                            <label for="pre_minor">
                                Pre oper.Profile(Minor)
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 pre-minor-profile-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(26,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][26]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[26]) ? $investigationValue[26] : null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Minor) Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','27',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(27,$patientsInvestigation) : null,['id'=>'eletrolytes','class'=>'plan-management','data-id'=>'eletrolytes-details'])}}
                            <label for="eletrolytes">
                                S.Eletrolytes
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 eletrolytes-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(27,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][27]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[27]) ? $investigationValue[27] : null,['class'=>'form-control','placeholder'=>'S. Eletrolytes Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','28',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(28,$patientsInvestigation) : null,['id'=>'denue_duo','class'=>'plan-management','data-id'=>'denue-duo-details'])}}
                            <label for="denue_duo">
                                Dengue Duo
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 denue-duo-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(28,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][28]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[28]) ? $investigationValue[28] : null,['class'=>'form-control','placeholder'=>'Dengue Duo Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','29',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(29,$patientsInvestigation) : null,['id'=>'billirubin','class'=>'plan-management','data-id'=>'billirubin-details'])}}
                            <label for="billirubin">
                                S.Billirubin
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 billirubin-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(29,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][29]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[29]) ? $investigationValue[29] : null,['class'=>'form-control','placeholder'=>'S. billirubin Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','30',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(30,$patientsInvestigation) : null,['id'=>'denue_ns1','class'=>'plan-management','data-id'=>'denue-ns1-details'])}}
                            <label for="denue_ns1">
                                Dengue NS1
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 denue-ns1-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(30,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][30]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[30]) ? $investigationValue[30] : null,['class'=>'form-control','placeholder'=>'Dengue NS1 Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[investigation_data][]','31',(!empty($ancHistoryId) || !empty($ancId)) ? checkData(31,$patientsInvestigation) : null,['id'=>'hb','class'=>'plan-management','data-id'=>'hb-data-details'])}}
                            <label for="hb">
                                HB
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 hb-data-details '.((!empty($ancHistoryId) || !empty($ancId)) && checkData(31,$patientsInvestigation) ? '' : 'd-none')}}">
                        <div class="form-group">
                            {{Form::text("investigation[investigation_details][31]",(!empty($ancHistoryId) || !empty($ancId)) && !empty($investigationValue[31]) ? $investigationValue[31] : null,['class'=>'form-control','placeholder'=>'HB Details'])}}
                        </div>
                    </div>
                </div>
                {{-- early scan data --}}
                <div class="{{'row investigation-report '.$lftDataStatusType}}">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Early Scan Report :
                        </label>
                    </div>
                    @php
                        $id1 = 'early_scan_type_yes';
                        $id2 = 'early_scan_type_no';
                        $earlyScanType = 'd-none';
                        if(!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes'){
                            $id1 = 'early_scan_yes';
                            $id2 = 'early_scan_no';
                            $earlyScanType = '';
                        }
                        $ancProfieType = !empty($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type == 'yes' ? '' : 'd-none';
                        $otherReportType = !empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes' ? '' : 'd-none';
                    @endphp
                    <div class="{{'col-sm-2 investigation-report '.$lftDataStatusType}}">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[early_scan_type]",'yes',!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'yes' ? true : false,['id'=>$id1,'class'=>'early-scan-type'])}}
                            <label for="{{$id1}}">
                                Yes
                            </label>

                            {{Form::radio("investigation[early_scan_type]",'no',!empty($patientsInvestigation->early_scan_type) && $patientsInvestigation->early_scan_type == 'no' ? true : false,['id'=>$id2,'class'=>'early-scan-type'])}}
                            <label for="{{$id2}}">
                                No
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 early-scan-data d-none">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_early_scan_date]",!empty($patientsInvestigation->investigation_early_scan_date) ? \Carbon\Carbon::parse($patientsInvestigation->investigation_early_scan_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 early-scan-data d-none">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                HB : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_early_scan_hb]",!empty($patientsInvestigation->investigation_early_scan_hb) ? $patientsInvestigation->investigation_early_scan_hb : null,['class'=>'form-control hb-value'])}}
                        </div>
                    </div>
                    <div class="col-sm-2 hb-extra-details d-none">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                HB Details : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_early_scan_hb_details]",!empty($patientsInvestigation->investigation_early_scan_hb_details) ? $patientsInvestigation->investigation_early_scan_hb_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>

                <div class="{{'row early-scan-data '.$earlyScanType.' '.$lftDataStatusType}}">
                    <div class="col-sm-3 investigation-report">
                        <div class="input-group">
                            <span class="input-group-addon">
                                TSH : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_tsh]",!empty($patientsInvestigation->investigation_tsh) ? $patientsInvestigation->investigation_tsh : null,['class'=>'form-control tsh-value'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 investigation-report tsh-details d-none">
                        <div class="input-group">
                            <span class="input-group-addon">
                                RX : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_tsh_details]",!empty($patientsInvestigation->investigation_tsh_details) ? $patientsInvestigation->investigation_tsh_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 rbs-data">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                RBS : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_rbs]",!empty($patientsInvestigation->investigation_rbs) ? $patientsInvestigation->investigation_rbs : null,['class'=>'form-control rbs-value'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 rbs-details d-none">
                        <div class="form-group investigation-report">
                            {{Form::text("investigation[investigation_rbs_details]",!empty($patientsInvestigation->investigation_rbs_details) ? $patientsInvestigation->investigation_rbs_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>

                <div class="{{'mb-2 investigation-report '.$lftDataStatusType}}">
                    <div class="{{'early-scan-images early-scan-data '.$earlyScanType}}"></div>
                </div>
                {{-- anc profile data --}}
                <div class="row anc-profile">
                    <div class="col-md-1 pr-0">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[is_print]','yes','',[
                                                   'id'=>'is_anc_print',
                                                   'class'=>'plan-management'
                                               ])}}

                            <label for="is_anc_print">
                                Is print?
                            </label>
                        </div>
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0 pre-operative-label">
                            {{$ancLabel}} :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[anc_profile_type]",'yes',!empty($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type == 'yes' ? true : false,['id'=>'anc_profile_type_yes','class'=>'anc-profile-type'])}}
                            <label for="anc_profile_type_yes">
                                Yes
                            </label>

                            {{Form::radio("investigation[anc_profile_type]",'no',!empty($patientsInvestigation->anc_profile_type) && $patientsInvestigation->anc_profile_type == 'no' ? true : false,['id'=>'anc_profile_type_no','class'=>'anc-profile-type'])}}
                            <label for="anc_profile_type_no">
                                No
                            </label>
                        </div>
                    </div>

                    <div class="{{'col-md-3 anc-data '.$ancProfieType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_anc_date]",!empty($patientsInvestigation->investigation_anc_date) ? \Carbon\Carbon::parse($patientsInvestigation->investigation_anc_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 anc-data '.$ancProfieType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Blood Group : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_blood_group]",!empty($patientsInvestigation->investigation_blood_group) ? $patientsInvestigation->investigation_blood_group : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 anc-data '.$ancProfieType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                RBS : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_anc_rbs]",!empty($patientsInvestigation->investigation_anc_rbs) ? $patientsInvestigation->investigation_anc_rbs : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                @php
                    $cbcType =  !empty($patientsInvestigation->investigation_cbc_mp->status) && $patientsInvestigation->investigation_cbc_mp->status == 2 ? '' : 'd-none';
                    $urineType =  !empty($patientsInvestigation->investigation_urine->status) && $patientsInvestigation->investigation_urine->status == 2 ? '' : 'd-none';
                    $urinePresentType = !empty($patientsInvestigation->investigation_urine->type) && $patientsInvestigation->investigation_urine->type == 'present' ? '' : 'd-none';
                @endphp
                <div class="{{'row anc-data '.$ancProfieType}}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            CBC MP :
                        </label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_cbc_mp][status]",$wnlArray,!empty($patientsInvestigation->investigation_cbc_mp->status) ? $patientsInvestigation->investigation_cbc_mp->status : null,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-data','placeholder'=>'Select CBC MB Type'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 cbc-mb-type-data '.$cbcType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Aneamia : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_cbc_mp][aneamia]",!empty($patientsInvestigation->investigation_cbc_mp->aneamia) ? $patientsInvestigation->investigation_cbc_mp->aneamia : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 cbc-mb-type-data '.$cbcType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Leacocytosis : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_cbc_mp][leacocytosis]",!empty($patientsInvestigation->investigation_cbc_mp->leacocytosis) ? $patientsInvestigation->investigation_cbc_mp->leacocytosis : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row anc-data '.$ancProfieType}}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Urine :
                        </label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_urine][status]",$wnlArray,!empty($patientsInvestigation->investigation_urine->status) ? $patientsInvestigation->investigation_urine->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-type-data','placeholder'=>'Select Urine Type'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-1 pr-0 urine-type-data '.$urineType}}">
                        <label class="vertical-form-label pr-0">
                            Puccell :
                        </label>
                    </div>
                    <div class="{{'col-sm-2 urine-type-data '.$urineType}}">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[investigation_urine][type]",'present',!empty($patientsInvestigation->investigation_urine->type) && $patientsInvestigation->investigation_urine->type == 'present' ? true : false,['id'=>'investigation_urine_present','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                            <label for="investigation_urine_present">
                                Present
                            </label>

                            {{Form::radio("investigation[investigation_urine][type]",'absent',!empty($patientsInvestigation->investigation_urine->type) && $patientsInvestigation->investigation_urine->type == 'absent' ? true : false,['id'=>'investigation_urine_absent','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                            <label for="investigation_urine_absent">
                                Absent
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 present-data urine-type-data-data '.$urinePresentType.' '.$urineType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Puscell : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_urine][puscell]",!empty($patientsInvestigation->investigation_urine->puscell) ? $patientsInvestigation->investigation_urine->puscell : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-3 urine-type-data '.$urineType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Urine Albumine : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_urine][urine_albumine]",!empty($patientsInvestigation->investigation_urine->urine_albumine) ? $patientsInvestigation->investigation_urine->urine_albumine : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row anc-data '.$ancProfieType}}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HIV :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[anc_hiv]",'positive',!empty($patientsInvestigation->anc_hiv) && $patientsInvestigation->anc_hiv == 'positive' ? true : false,['id'=>'anc_profile_hiv_positive','class'=>'anc-profile-hiv'])}}
                            <label for="anc_profile_hiv_positive">
                                Positive
                            </label>

                            {{Form::radio("investigation[anc_hiv]",'negative',!empty($patientsInvestigation->anc_hiv) && $patientsInvestigation->anc_hiv == 'negative' ? true : false,['id'=>'anc_profile_hiv_nagative','class'=>'anc-profile-hiv'])}}
                            <label for="anc_profile_hiv_nagative">
                                Negative
                            </label>
                        </div>
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HBSAG :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[anc_hbsag]",'positive',!empty($patientsInvestigation->anc_hbsag) && $patientsInvestigation->anc_hbsag == 'positive' ? true : false,['id'=>'anc_profile_hbsag_positive','class'=>'anc-profile-hbsag'])}}
                            <label for="anc_profile_hbsag_positive">
                                Positive
                            </label>

                            {{Form::radio("investigation[anc_hbsag]",'negative',!empty($patientsInvestigation->anc_hbsag) && $patientsInvestigation->anc_hbsag == 'negative' ? true : false,['id'=>'anc_profile_hbsag_nagative','class'=>'anc-profile-hbsag'])}}
                            <label for="anc_profile_hbsag_nagative">
                                Negative
                            </label>
                        </div>
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            VDRL :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[anc_vdrl]",'positive',!empty($patientsInvestigation->anc_vdrl) && $patientsInvestigation->anc_vdrl == 'positive' ? true : false,['id'=>'anc_profile_vdrl_positive','class'=>'anc-profile-vdrl'])}}
                            <label for="anc_profile_vdrl_positive">
                                Positive
                            </label>
                            {{Form::radio("investigation[anc_vdrl]",'negative',!empty($patientsInvestigation->anc_vdrl) && $patientsInvestigation->anc_vdrl == 'negative' ? true : false,['id'=>'anc_profile_vdrl_nagative','class'=>'anc-profile-vdrl'])}}
                            <label for="anc_profile_vdrl_nagative">
                                Negative
                            </label>
                        </div>
                    </div>
                </div>
                <div class="{{'mb-2 anc-data '.$ancProfieType}}">
                    <div class="anc-images-data"></div>
                </div>
                <div class="{{'row lft-data '.$lftDataStatus}}">
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[anc_lft]','lft',$lftStatus,['id'=>'lft_status','class'=>'lft-status'])}}
                            <label for="lft_status">
                                LFT
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-2 lft-status-data '.$lftData}}">
                        <div class="form-group">
                            {{Form::select("investigation[anc_lft_normal_status]",['1'=>"Normal",'2'=>"Abnormal"],!empty($patientsInvestigation->anc_lft_normal_status) ? $patientsInvestigation->anc_lft_normal_status : 1,['class'=>'form-control select-padding-0 lft-type investigation-type','data-id'=>'lft-abnormal-type-data'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-2 lft-abnormal-type-data '.$lftAbStatus}}">
                        {{Form::text('investigation[anc_lft_normal_data]',!empty($patientsInvestigation->anc_lft_normal_data) ? $patientsInvestigation->anc_lft_normal_data : null,['class'=>'form-control'])}}
                    </div>
                    <div class="{{'col-md-1 pr-0 lft-abnormal-type-data '.$lftAbStatus}}">
                        <label class="vertical-form-label pr-0">
                            BITA- HCG :
                        </label>
                    </div>
                    <div class="{{'col-md-2 lft-abnormal-type-data '.$lftAbStatus}}">
                        {{Form::text('investigation[anc_lft_ab_normal_data_bita_hcg]',!empty($patientsInvestigation->anc_lft_ab_normal_data_bita_hcg) ? $patientsInvestigation->anc_lft_ab_normal_data_bita_hcg : null,['class'=>'form-control'])}}
                    </div>
                </div>
                {{-- growth report --}}
                <div class="{{'row investigation-report '.$lftDataStatusType}}">
                    <div class="col-md-2 pr-0 investigation-report">
                        <label class="vertical-form-label pr-0">
                            Growth Report :
                        </label>
                    </div>
                    @php
                        $id1 = 'growth_report_type_yes';
                        $id2 = 'growth_report_type_no';
                        $growthReportType = 'd-none';
                        if(!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes'){
                            $id1 = 'growth_report_yes';
                            $id2 = 'growth_report_no';
                            $growthReportType = '';
                        }
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[growth_report_type]",'yes',!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'yes' ? true : false,['id'=>$id1,'class'=>'growth-report-type'])}}
                            <label for="{{$id1}}">
                                Yes
                            </label>

                            {{Form::radio("investigation[growth_report_type]",'no',!empty($patientsInvestigation->growth_report_type) && $patientsInvestigation->growth_report_type == 'no' ? true : false,['id'=>$id2,'class'=>'growth-report-type'])}}
                            <label for="{{$id2}}">
                                No
                            </label>
                        </div>
                    </div>

                    <div class="{{'col-md-4 growth-report-data '.$growthReportType}}">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_growth_date]",!empty($patientsInvestigation->investigation_growth_date) ? \Carbon\Carbon::parse($patientsInvestigation->investigation_growth_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 growth-report-data '.$growthReportType}}">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                HB : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_growth_hb]",!empty($patientsInvestigation->investigation_growth_hb) ? $patientsInvestigation->investigation_growth_hb : null,['class'=>'form-control'])}}
                        </div>
                    </div>

                </div>
                <div class="{{'row investigation-report '.$lftDataStatusType}}">
                    <div class="{{'col-sm-3 growth-report-data fbs-data '.$growthReportType}}">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                FBS : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_growth_fbs]",!empty($patientsInvestigation->investigation_growth_fbs) ? $patientsInvestigation->investigation_growth_fbs : null,['class'=>'form-control fbs-value'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 fbs-details d-none">
                        <div class="form-group investigation-report">
                            {{Form::text("investigation[investigation_growth_fbs_details]",!empty($patientsInvestigation->investigation_growth_fbs_details) ? $patientsInvestigation->investigation_growth_fbs_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-3 growth-report-data '.$growthReportType}}">
                        <div class="input-group investigation-report">
                            <span class="input-group-addon">
                                PP2BS : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_growth_pp2bs]",!empty($patientsInvestigation->investigation_growth_pp2bs) ? $patientsInvestigation->investigation_growth_pp2bs : null,['class'=>'form-control pp2bs-value'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 pp2bs-details d-none">
                        <div class="form-group investigation-report">
                            {{Form::text("investigation[investigation_growth_pp2bs_details]",!empty($patientsInvestigation->investigation_growth_pp2bs_details) ? $patientsInvestigation->investigation_growth_pp2bs_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'mb-2 investigation-report '.$lftDataStatusType}}">
                    <div class="{{'growth-images growth-report-data '.$growthReportType}}"></div>
                </div>


                {{-- other report --}}
                <div class="{{'row investigation-report '.$lftDataStatusType}}">
                    @php
                        $otherReport = !empty($patientsInvestigation->other_report) ? $patientsInvestigation->other_report : [];
                    @endphp
                    <div class="{{'col-md-1 pr-0 investigation-report'}}">
                        <label class="vertical-form-label pr-0">
                            Report Upload:
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="{{'radio is-conceived investigation-report'}}">
                            {{Form::radio("investigation[other_report_type]",'yes',(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'yes' ? true : false,['id'=>'other_report_type_yes','class'=>'other-report-type'])}}
                            <label for="other_report_type_yes">
                                Yes
                            </label>

                            {{Form::radio("investigation[other_report_type]",'no',(!empty($ancHistoryId) || !empty($ancId)) && !empty($patientsInvestigation->other_report_type) && $patientsInvestigation->other_report_type == 'no' ? true : false,['id'=>'other_report_type_no','class'=>'other-report-type'])}}
                            <label for="other_report_type_no">
                                No
                            </label>
                        </div>
                    </div>

                    <div class="{{'col-md-2 '.$otherReportType}}">
                        <div class="checkbox or-details">
                            {{Form::checkbox('investigation[other_report][]','double_marker',in_array('double_marker',$otherReport),['id'=>'d-marker'])}}
                            <label for="d-marker">
                                Double marker
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 investigation-report '.$otherReportType}}">
                        <div class="input-group or-details">
                            <span class="input-group-addon">
                                Double Marker Date: &nbsp;
                            </span>
                            {{Form::text("investigation[d_m_date]",!empty($patientsInvestigation->d_m_date) ? \Carbon\Carbon::parse($patientsInvestigation->d_m_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-2 investigation-report '.$otherReportType}}">
                        <div class="checkbox or-details">
                            {{Form::checkbox('investigation[other_report][]','genetic_test',in_array('genetic_test',$otherReport),['id'=>'genetic-test'])}}
                            <label for="genetic-test">
                                Genetic Test
                            </label>
                        </div>
                    </div>
                </div>

                <div class="{{'row investigation-report '.$otherReportType}}">
                    <div class="col-md-2 or-details">
                        <div class="checkbox">
                            {{Form::checkbox('investigation[other_report][]','amniocentesis',in_array('amniocentesis',$otherReport),['id'=>'amniocentesis'])}}
                            <label for="amniocentesis">
                                Amniocentesis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 or-details">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Amniocentesis Date: &nbsp;
                            </span>
                            {{Form::text("investigation[amniocentesis_date]",!empty($patientsInvestigation->amniocentesis_date) ? \Carbon\Carbon::parse($patientsInvestigation->amniocentesis_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'mb-2 or-details investigation-report '.$otherReportType}}">
                    <div class="other-report-images"></div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_extra]",!empty($patientsInvestigation->investigation_extra) ? $patientsInvestigation->investigation_extra : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- plan tab --}}
    {{Form::hidden('oe[is_plan]',!empty($oe->is_plan) ? $oe->is_plan : 0,['class'=>'is_plan'])}}
    {{Form::hidden('oe[is_lft]',!empty($oe->is_lft) ? $oe->is_lft : 0,['class'=>'is_lft'])}}
    @if(!empty($oe->is_plan) && $oe->is_plan == 1)
        <div class="panel panel-primary plan-tab d-none">
            <div class="panel-heading" role="tab" id="headingThree_1">
                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#injection" href="#injection" aria-expanded="true"
                                            aria-controls="past-history">{{$injectionNo}}. Plan</a></h4>
            </div>
            <div id="injection" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[usg][type]",'usg_inside',!empty($oe->usg->type) && $oe->usg->type == 'usg_inside' ? true : false ,['id'=>'usg_inside','class'=>'usg-status','data-type'=>'usg-details'])}}
                                <label for="usg_inside">
                                    USG Inside
                                </label>

                                {{Form::radio("oe[usg][type]",'usg_outside',!empty($oe->usg->type) && $oe->usg->type == 'usg_outside' ? true : false,['id'=>'usg_outside','class'=>'usg-status','data-type'=>'usg-details'])}}
                                <label for="usg_outside">
                                    USG Outside
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{Form::text("oe[usg][details]",!empty($oe->usg_details) ? $oe->usg_details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[medically_type][type]",'medically',!empty($oe->usg->type) && $oe->usg->type == 'medically' ? true : false,['id'=>'medically_value','class'=>'medically-status'])}}
                                <label for="medically_value">
                                    Medically
                                </label>

                                {{Form::radio("oe[medically_type][type]",'surgically',!empty($oe->usg->type) && $oe->usg->type == 'surgically' ? true : false,['id'=>'surgically_value','class'=>'medically-status'])}}
                                <label for="surgically_value">
                                    Surgically
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @php
        $checkInjectionClass = (!empty($ancHistoryId) || !empty($ancId)) && $ancData->is_gynec == 1 ? 'd-none' : '';
    @endphp
    <!-- Injection history  -->
    <div class="{{'panel panel-primary injection injection-tab '.$checkInjectionClass}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#injection" href="#injection" aria-expanded="true"
                                        aria-controls="past-history">{{$injectionNo}}. Injection</a></h4>
        </div>
        <div id="injection" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                TT1 : &nbsp;
                            </span>
                            {{Form::text("injection[tt1]",!empty($patientsInjection->tt1) ? $patientsInjection->tt1 : null,['class'=>'form-control datetimepicker date tt1-date'])}}
                        </div>
                    </div>
                    @php
                        // $tt2 = 'tt2 d-none';
                        // if(!empty($patientsInjection->tt1)){
                        //     $tt1Date = \Carbon\Carbon::parse($patientsInjection->tt1)->addMonths(1)->format('Y-m-d');
                        //     $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
                        //     if($currentDate == $tt1Date){
                        //         $tt2 = '';
                        //     }
                        // }
                        $oeUtData = !empty($oe->utdata) ? (array)$oe->utdata : [];
                        $bClass = 'd-none';
                        if(!empty($oeUtData)){
                            $utValue = !empty($oeUtData[1]->oe_ut_sac_1) ? $oeUtData[1]->oe_ut_sac_1 : null;
                            if($utValue >= 17 && $utValue <= 22){
                                $bClass = '';
                            }
                        }
                    @endphp
                    <div class="{{'col-md-3'}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                TT2 : &nbsp;
                            </span>
                            {{Form::text("injection[tt2]",!empty($patientsInjection->tt2) ? \Carbon\Carbon::parse($patientsInjection->tt2)->format('D d M Y') : null,['class'=>'form-control datetimepicker date tt2-date'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 betnasol '.$bClass}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Betnasol : &nbsp;
                            </span>
                            {{Form::text("injection[betnasol_1]",!empty($patientsInjection->betnasol_1) ? \Carbon\Carbon::parse($patientsInjection->betnasol_1)->format('D d M Y') : null,['class'=>'form-control b1 datetimepicker date betnasol-date'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 betnasol '.$bClass}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Betnasol : &nbsp;
                            </span>
                            {{Form::text("injection[betnasol_2]",!empty($patientsInjection->betnasol_2) ? \Carbon\Carbon::parse($patientsInjection->betnasol_2)->format('D d M Y') : null,['class'=>'form-control b2 datetimepicker date betnasol-date'])}}
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    @php
        $checkPlanClass = (!empty($ancHistoryId) || !empty($ancId)) && $ancData->is_gynec == 1 ? '' : 'd-none';
    @endphp
    <div class="{{'panel panel-primary gynec-plan-tab '.$checkPlanClass}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#plan_gynec" href="#plan_gynec" aria-expanded="true"
                                        aria-controls="past-history"><span class="gynec-plan-no">{{!empty($ancHistoryId) ? 6 : 9}}</span>. Plan</a></h4>
        </div>
        <div id="plan_gynec" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-3">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[plan_medically_type][type]",'medically',!empty($oe->plan_medically_type->type) && $oe->plan_medically_type->type == 'medically' ? true : false,['id'=>'plan_medically_value','class'=>'plan-medically-status'])}}
                            <label for="plan_medically_value">
                                Medically
                            </label>

                            {{Form::radio("oe[plan_medically_type][type]",'surgically',!empty($oe->plan_medically_type->type) && $oe->plan_medically_type->type == 'surgically' ? true : false,['id'=>'plan_surgically_value','class'=>'plan-medically-status'])}}
                            <label for="plan_surgically_value">
                                Surgically
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- usg data --}}
    <div class="panel panel-primary usg-tab">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#usg" href="#usg" aria-expanded="true"
                                        aria-controls="past-history">{{$usgNo}}. USG</a></h4>
        </div>
        <div id="usg" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="checkbox">
                    {{Form::checkbox('usg[is_usg_print]','1','',['id'=>'is_usg_print'])}}
                    <label for="is_usg_print">
                        Is Print
                    </label>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Early Scan : &nbsp;
                            </span>
                            {{Form::text("usg[early_scan]",!empty($usg->early_scan) ? \Carbon\Carbon::parse($usg->early_scan)->format('D d M Y') : null ,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group nt-data">
                            <span class="input-group-addon">
                                N.T Scan : &nbsp;
                            </span>
                            @php
                                $type = null;
                                if($utType == 'yes'){
                                    $type = 'anc_history';
                                }
                            @endphp
                            {{Form::hidden('date_type',$type,['class'=>'date-type'])}}
                            {{Form::text("usg[nt_scan]",!empty($usg->nt_scan) ? \Carbon\Carbon::parse($usg->nt_scan)->format('D d M Y') : null,['class'=>'form-control datetimepicker date nt-scan-date'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group anomalies-data">
                            <span class="input-group-addon">
                                Anomalies Miles : &nbsp;
                            </span>
                            {{Form::text("usg[anomalies_miles]",!empty($usg->anomalies_miles) ?  \Carbon\Carbon::parse($usg->anomalies_miles)->format('D d M Y') : null,['class'=>'form-control datetimepicker date anomalies-scan-date'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Growth Scan : &nbsp;
                            </span>
                            {{Form::text("usg[growth_scan]",!empty($usg->growth_scan) ? \Carbon\Carbon::parse($usg->growth_scan)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="usg-images"></div>
                </div>

            </div>
        </div>
    </div>

    {{-- treatment --}}
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="true"
                                        aria-controls="past-history">{{$treatmentNo}}. Treatment</a></h4>
        </div>
        <div id="treatment" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body" id="parent">
                <div class="row">
                    <div class="col-md-2 ipd-detail">
                        <label for="termination">
                            Admission for
                        </label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">

                            {{Form::select("usg[termination_type]",$terminationtype,@$usg->termination_type,['class'=>'form-control termination_type'])}}
                        </div>
                    </div>
                    @php
                        $terminationTypeTermStatus = !empty($ancHistoryId) && !empty($usg->termination_type) && $usg->termination_type == 'Delivery' ? '' : 'd-none';
                    @endphp
                    <div class="{{'col-sm-3 termination_type_term '.$terminationTypeTermStatus}}">
                        <div class="radio is-conceived">
                            {{Form::radio("usg[termination_type_trem]",'full',!empty($ancHistoryId) && !empty($usg->termination_type_trem) && $usg->termination_type_trem == 'full' ? true : false,['id'=>'termination_term_yes','class'=>'termination_type_term'])}}
                            <label for="termination_term_yes">
                                Full Term
                            </label>

                            {{Form::radio("usg[termination_type_trem]",'pre',!empty($ancHistoryId) && !empty($usg->termination_type_trem) && $usg->termination_type_trem == 'pre' ? true : false,['id'=>'termination_term_no','class'=>'termination_type_term'])}}
                            <label for="termination_term_no">
                                Pre Term
                            </label>

                            {{Form::radio("usg[termination_type_trem]",'lscs',!empty($ancHistoryId) && !empty($usg->termination_type_trem) && $usg->termination_type_trem == 'lscs' ? true : false,['id'=>'termination_term_lscs','class'=>'termination_type_term'])}}
                            <label for="termination_term_lscs">
                               LSCS
                            </label>

                        </div>
                    </div>
                    <div class="col-md-4">
                        {{Form::text("usg[termination_detail]",!empty(@$usg->termination_detail) ? $usg->termination_detail : null,['class'=>'form-control'])}}
                    </div>
                </div>
                <div class="row treatment-data" id="t_data_1">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Select Medicine :
                        </label>
                    </div>
                    @php
                        $mData = '';
                        $mData = $medicineKey;
                    @endphp
                    <div class="col-md-8 complain-multi mb-3 medicine-picker">
                        {{Form::select('treatment[medicinedata][]',$medicines,null,[
                            'class'=>'form-control select-padding-0 medicine',
                            'id' => 'treatment-medicine',
                            'placeholder'=>'Enter medicine name',
                        ])}}
                        {{-- {{Form::select("treatment[medicinedata][]",$medicines,$mData,['id'=>'treatment-medicine','class'=>'form-control medicine','multiple'=>true])}} --}}
                        {{-- {{Form::select('treatment[medicinedata][]',$medicines,$mData,['class'=>'form-control medicine co_value_data medicine-co','placeholder'=>'Select Medicine','multiple'=>true])}} --}}
                    </div>
                </div>
                <div class="page-loader-wrapper medicine-loader d-none">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
                <div class="medicine-data">
                    @if(!empty($treatment))
                        @foreach($treatment as $key=>$row)
                        {{-- @if(isset($medicines[$row->medicine])) --}}
                            <?php
                            $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                            $firstCharacter = substr($mId, 0, 3);
                            $notinject = "";
                            if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                $notinject = "is-inj";
                                $dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day",'7'=>"6 hourly",'8'=>"8 hourly",'9'=>"12 hourly",'10'=>"24 hourly"];

                            }
                            $till_follow_up = (isset($medicinesDays[$row->medicine]) && empty($medicinesDays[$row->medicine])) ? 'till-follow-up' : '';
                            ?>
                            <div class="{{'row '.$notinject}}" data-id="{{$mId}}">
                                <div class='col-md-2'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>M : </span>
                                        {{Form::text('treatment['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2 isinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>Day :</span>
                                        {{Form::number('treatment['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <div class='form-group'>
                                        <!-- <span class='input-group-addon'>Day :</span> -->
                                        {{Form::number('treatment['.$mId.'][note]',isset($row->note) ? $row->note : '',['class'=>'form-control','placeholder'=>'Note'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 medicine-data-remove'>
                                    <span class=""><i class="material-icons">close</i></span>
                                </div>
                            </div>
                            {{-- @endif --}}
                        @endforeach
                    @endif
                </div>
                {{Form::hidden('old_medicine_data',!empty($medicineKey) ? implode(',',$medicineKey) : null,['class'=>'old-medicine-data'])}}
                <div class="row">
                    <div class="col-md-2">
                        Patient is referred
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">for : &nbsp;</span>
                            {{Form::text("usg[referfor]", !empty(@$usg->referfor) ? $usg->referfor: null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">to : &nbsp;</span>
                            {{Form::text("usg[referto]", !empty(@$usg->referto) ? $usg->referto: null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{Form::hidden('is_gynec',empty($ancHistoryId) ? 0 : $ancData->is_gynec,['class'=>'is-gynec'])}}
    @if($utType == 'yes' && empty($ancHistoryId))
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-addon">
                        Follow Up : &nbsp;
                    </span>
                    {{-- {{Form::text("oe[follow_up]", '',['class'=>'form-control datetimepicker followup followup-date'])}} --}}
                    {{Form::text("oe[follow_up]", '',['class'=>'form-control datetimepicker followup next-date'])}}

                    {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
                    {{Form::hidden('is_follow_up',1,['class'=>'is-followup'])}}
                    {{Form::hidden('is_notAvailable',0,['class'=>'is-notAvailable'])}}
                </div>
                <span class="not-available-error form-error-msg"></span>
                <span class="gsac-no-data-followup form-error-msg"></span>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    {{Form::text("oe[follow_up_date_diff]",'',['class'=>'form-control next-day','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                    {{Form::hidden('appointment_date',$lastAppointment->date,['class'=>'last-appointment-date'])}}
                </div>
            </div>
            <span class="col-md-1 p-2 history-lmp-date">Day</span>
        </div>
    @else
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-addon">
                        Follow Up : &nbsp;
                    </span>
                    {{Form::text("oe[follow_up]", !empty($oe->follow_up) ? $oe->follow_up : null,['class'=>'form-control datetimepicker followup next-date'])}}
                </div>
            </div>
        </div>
        {{Form::hidden('is_follow_up',0,['class'=>'is-followup'])}}
    @endif
@endif
<script type="text/javascript">
    $('#treatment-medicine').select2();

    $('.ho-data').selectize({
        create: true,
        sortField: 'text'
    });
    $('.usg-images').imageUploader({
        imagesInputName: 'usg[images]',
    });
    $('.ho-past-data').selectize({
        create: true,
        sortField: 'text'
    });
    $(document).ready(function(){
        $checkdtlen = $('input.anc-date:checked').attr('id');
        if($('input.anc-date').length<3 || $checkdtlen == 'dt_1' && $checkdtlen == 'dt_2'){
            $(".female-type-data.fefal-reduction-1").removeClass('d-none');
        }
    });
    //set g-sac value based on P/A uterus week
    $(document).ready(function(){
        var value = $('select.ut-sac').val();
        var weekNo = value;
        value = weekData[value];
        var dId = $('select.ut-sac').data('id');
        if(value.indexOf('Weeks') != -1){
            value = value.match(/([\d.]+) *Weeks/)[1];
            if(value != ''){
                var date = $('.edd-date').val();
                var oldWeekValue = $('select.ut-sac').data('value');
                var oldWeek = $('select.ut-sac').data('value',weekNo);
                var week2 = $('.ut-sac-2').val() && typeof $('.ut-sac-2').val() != 'undefined' ? $('.ut-sac-2').val() : 0;
                if($.isNumeric(weekNo)){
                    if(dId == 1){
                        utGsac(value,dId);
                    }
                    addOrRemoveClass(value,dId);
                }
            }
        }else{
            addOrRemoveClass(value,dId,weekNo);
        }
    })
    //set the female type value
    femaleteType($('.female-type').val(),$('.female-type').data('id'));
</script>
