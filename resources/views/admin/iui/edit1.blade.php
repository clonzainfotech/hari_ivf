@php
use App\Models\IuiHistory;
$medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
$medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
$medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
$dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day"];
@endphp

    <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">

        {{Form::open(['class'=>'form iui','files'=>'true','id'=>'iui-form'])}}
            {{Form::hidden('patients_id',encrypt($iui->patients_id), ['id' => 'patients_id'])}}
            {{Form::hidden('cycle_no', $cycleNo, ['id' => 'cycle_no'])}}
            @php
                // $lmddate = !empty($iuiSecondVisitDate->date) ? \Carbon\Carbon::parse($iuiSecondVisitDate->date)->format('D d M Y') : null;
                $lmddate = !empty($firstVisitLmpDate) ? \Carbon\Carbon::parse($firstVisitLmpDate) : null;
                $lmdDiff = null;
                if(!empty($lmddate)){
                    // $lmddateData = \Carbon\Carbon::parse($lmddate);
                    $now = \Carbon\Carbon::now();
                    $lmdDiff = $lmddate->diffInDays($now);
                    $lmdDiff = $lmdDiff + 1;
                    $lmddate = \Carbon\Carbon::parse($lmddate)->format('D d M Y');
                }
            @endphp
            @if($visitNo == 1)
                <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Seen By :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('seen_by',$hospitalDoctor,isset($iui) && !empty($iui->seen_by) ? $iui->seen_by : '',['class'=>'form-control select-padding-0','placeholder'=>'Select Doctor'])}}
                        </div>
                    </div>
                </div>
                {{Form::hidden('iui_id',$iui->id)}}
                {{Form::hidden('visit',1)}}
                <!--1 patients basic information -->
                <div class="panel panel-primary">
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
                                        {{Form::text('name',$iui->getPatientsInfo->name,['class'=>'form-control name'])}}
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
                                        {{Form::text('code',$iui->getPatientsInfo->getAppointment['code'],['class'=>'form-control code','disabled'])}}
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
                                        {{Form::text("weight",$iui->getPatientsInfo->weight,['class'=>'form-control weight','id'=>'weight'])}}
                                    </div>
                                    <span class="form-error-msg weight"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Mobile : &nbsp;</span>
                                        {{Form::number('mobile_number',$iui->getPatientsInfo['mobile_number'],['class'=>'form-control mobile_number'])}}
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
                                        {{Form::select('rd_reference',$referenceDoctor,!empty($iui->getPatientsInfo['reference_doctor_id']) ? $iui->getPatientsInfo['reference_doctor_id'] : null,['class'=>'form-control select-padding-0 refence-doctor','placeholder'=>'Rd Reference'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('rd_reference')}}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rd Mobile : &nbsp;</span>
                                        {{Form::number('rd_mobile_number',!empty($iui->getPatientsInfo->getReferenceDoctor['mobile_number']) ? $iui->getPatientsInfo->getReferenceDoctor['mobile_number'] : null,['class'=>'form-control ref-mobile-number'])}}
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
                                        {{Form::text('residence',!empty($iui->getPatientsInfo['residence']) ? $iui->getPatientsInfo['residence'] : null,['class'=>'form-control'])}}
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
                                        {{Form::text('main_area',!empty($iui->getPatientsInfo['main_area']) ? $iui->getPatientsInfo['main_area'] : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('main_area')}}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">City : &nbsp;</span>
                                        {{Form::text('city',!empty($iui->getPatientsInfo['city']) ? $iui->getPatientsInfo['city'] : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('city')}}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        {{Form::textarea("p_info[other_info]",!empty($patientsInfo->other_info) ? $patientsInfo->other_info : null,['class'=>'form-control no-resize other_info','placeholder'=>'Other Information','rows'=>'5'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('other_info')}}
                                    </span>
                                </div>
                                 <div class="col-md-6">
                                    <div class="input-group">
                                        {{Form::textarea("p_info[remark]",!empty($patientsInfo->remark) ? $patientsInfo->remark : null,['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('remark')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--2 H/O -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#ho" aria-expanded="false"
                                                aria-controls="patients-detailed-ho">2. H/O</a></h4>
                    </div>
                    <div id="ho" class="panel-collapse collapse ho-tab" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                {{-- <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">H/O : &nbsp;</span>
                                        {{Form::text("ho[ho_details]",!empty($ho->ho_details) ? $ho->ho_details : null,['class'=>'form-control ho-data'])}}
                                    </div>
                                    <span class="form-error-msg ho-data-msg">
                                        {{$errors->first('ho_details')}}
                                    </span>
                                </div> --}}
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        H/O :
                                    </label>
                                </div>
                                <div class='col-md-8 complain-multi ho-value duration-value'>
                                    {{Form::select('ho[ho_details]',$hoData,!empty($ho->ho_details) ? $ho->ho_details : null,['class'=>'form-control ho-data select-padding-0 anc-dose-val ho-data-value','placeholder'=>'Select H/O','data-medicine'=>2])}}
                                    <span class="form-error-msg ho-data-msg">
                                        {{$errors->first('ho_details')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--3 C/O -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#co" href="#co" aria-expanded="false"
                                aria-controls="co">3. C/O</a></h4>
                    </div>
                    <div id="co" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        C/O :
                                    </label>
                                </div>
                                <div class="col-md-8 complain-multi">
                                    {{Form::select('co[co_type][]',$complaints,!empty($co->co_type) ? $co->co_type : null,['class'=>'form-control co-value co_value_data','placeholder'=>'Enter complain','multiple'=>true,'data-medicine'=>1])}}
                                    <span class="form-error-msg co-value-msg">
                                        {{$errors->first('since')}}
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Since : &nbsp;</span>
                                        {{Form::text("co[since]",!empty($co->since) ? $co->since : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('since')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--4 O/H -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#accordion_1" href="#obstratics_history"
                                                aria-expanded="false"
                                                aria-controls="obstratics_history">4. Obstetric History</a></h4>
                    </div>
                    <div id="obstratics_history" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">First Marriage Life : &nbsp;</span>
                                        {{Form::text("oh[first_marriage_life]",!empty($oh->first_marriage_life) ? $oh->first_marriage_life : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('first_marriage_life')}}
                                    </span>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Active Marriage Life : &nbsp;</span>
                                        {{Form::text("oh[active_marriage_life]",!empty($oh->active_marriage_life) ? $oh->active_marriage_life : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                {{-- <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Second Marriage Life : &nbsp;</span>
                                        {{Form::text("oh[second_marriage_life]",!empty($oh->second_marriage_life) ? $oh->second_marriage_life : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('second_marriage_life')}}
                                    </span>
                                </div> --}}
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        UPT :
                                    </label>
                                </div>

                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oh[upt_type]",'positive',!empty($oh->upt_type) && $oh->upt_type  == 'positive' ? true : false,[
                                            'id'=>'positive',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="positive">
                                            Positive
                                        </label>

                                        {{Form::radio("oh[upt_type]",'negative',!empty($oh->upt_type) && $oh->upt_type  == 'negative' ? true : false,[
                                            'id'=>'negative',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="negative">
                                            Negative
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="from-group">
                                        {{Form::text('oh[upt_details]', !empty($oh->upt_details) ? $oh->upt_details : null, [
                                            'class'=>'form-control upt_details',
                                            'placeholder' => 'UPT Details'
                                        ])}}
                                    </div>
                                </div>
                                @php
                                    $showUptLabel = 'd-none';
                                    if (!empty($oh->upt_details)) {
                                        $showUptLabel = (is_numeric($oh->upt_details)) ? '' : 'd-none';
                                    }
                                @endphp
                                <div class="{{ 'col-md-1 upt_details_label ' . $showUptLabel }}">
                                    <label class="vertical-form-label pr-0">
                                        Days Before
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select("oh[type_of_infertility]",[1=>'Primary',2=>'Secondary'],!empty($oh->type_of_infertility) ? $oh->type_of_infertility : null,['class'=>'form-control select-padding-0','placeholder'=>'Type Of Infertility'])}}
                                    </div>
                                </div>
                            </div>

                            <!-- for child -->
                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{Form::select("oh[child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],!empty($oh->child_no) ? $oh->child_no : null,['class'=>'form-control child-no select-padding-0','placeholder'=>'Child No','data-type'=>'1'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('child_no')}}
                                    </span>
                                </div>
                                @php
                                    $hoType = [2,3,4];
                                    $childNaturally = !empty($oh->child_no) && $oh->child_no != 0 ? '' : 'd-none';
                                @endphp
                            </div>

                            <div class="child-data">
                                {{-- for child data--}}
                                @if(!empty($oh) && ($oh->child_no != null && $oh->child_no != 0))
                                    @foreach($oh->child->child_data as $key=>$row)
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label class="vertical-form-label pr-0">
                                                    H/O :
                                                </label>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[child][child_data][".$key."][ho_term]",'full',!empty($row->ho_term) && $row->ho_term  == 'full' ? true : false,['id'=>'full_'.$key])}}
                                                    <label for={{'full_'.$key}}>
                                                        Fullterm
                                                    </label>
                                                    {{Form::radio("oh[child][child_data][".$key."][ho_term]",'pre',!empty($row->ho_term) && $row->ho_term == 'pre' ? true : false,['id'=>'pre_'.$key])}}
                                                    <label for={{'pre_'.$key}}>
                                                        Preterm
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                {{Form::text("oh[child][child_data][".$key."][ho_term_details]", !empty($row->ho_term_details) ? $row->ho_term_details : null, [
                                                    'placeholder' => 'Term Details',
                                                    'class'=>'form-control',
                                                ])}}
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[child][child_data][".$key."][ho_type_value]",'normal',!empty($row->ho_type_value) && $row->ho_type_value == 'normal' ? true : false,['id'=>'normal_'.$key])}}
                                                    <label for={{'normal_'.$key}}>
                                                        Normal
                                                    </label>

                                                    {{Form::radio("oh[child][child_data][".$key."][ho_type_value]",'cesarean',!empty($row->ho_type_value) && $row->ho_type_value == 'cesarean' ? true : false,['id'=>'cesarean_'.$key])}}
                                                    <label for={{'cesarean_'.$key}}>
                                                        Cesarean
                                                    </label>

                                                    {{Form::radio("oh[child][child_data][".$key."][ho_type_value]",'instrumental',!empty($row->ho_type_value) && $row->ho_type_value == 'instrumental' ? true : false,['id'=>'instrumental_'.$key])}}
                                                    <label for="{{'instrumental_'.$key}}">
                                                        Instrumental
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[child][child_data][".$key."][ho_gender]",'male',!empty($row->ho_gender) && $row->ho_gender == 'male' ? true : false,['id'=>'ho_male_'.$key])}}
                                                    <label for={{'ho_male_'.$key}}>
                                                        Male
                                                    </label>

                                                    {{Form::radio("oh[child][child_data][".$key."][ho_gender]",'female',!empty($row->ho_gender) && $row->ho_gender == 'female' ? true : false,['id'=>'ho_female_'.$key])}}
                                                    <label for={{'ho_female_'.$key}}>
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row child-data-parent">
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[child][child_data][".$key."][ho_birth_type]",'live_health',!empty($row->ho_birth_type) && $row->ho_birth_type == 'live_health' ? true : false,['id'=>'live_health_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                                    <label for={{'live_health_'.$key}}>
                                                        Live Health
                                                    </label>

                                                    {{Form::radio("oh[child][child_data][".$key."][ho_birth_type]",'stil_birth',!empty($row->ho_birth_type) && $row->ho_birth_type == 'stil_birth' ? true : false,['id'=>'stil_birth_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                                    <label for={{'stil_birth_'.$key}}>
                                                        Stil Birth
                                                    </label>

                                                    {{Form::radio("oh[child][child_data][".$key."][ho_birth_type]",'expired',!empty($row->ho_birth_type) && $row->ho_birth_type == 'expired' ? true : false,['id'=>'expired_'.$key,'class'=>'health-type','data-id'=>$key])}}
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
                                                    {{Form::text("oh[child][child_data][".$key."][expired_reason]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Reason'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('reason')}}
                                                </span>
                                            </div>
                                            <div class="{{'col-sm-2 expired-reason-'.$key.' '.$reasonStatus}}">
                                                <div class="form-group">
                                                    {{Form::text("oh[child][child_data][".$key."][expired_year]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Expired Reason'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                                    {{Form::text("oh[child][child_data][".$key."][live_health_year]",!empty($row->live_health_year) ? $row->live_health_year : null,['class'=>'form-control'])}}
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
                                                    {{Form::select("oh[child][child_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type p-ho-type','data-id'=>'child-when-where-'.$key,'placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            @php
                                                $dNone = '';
                                                if(!empty($oh->child) && !empty($row->ho_type) && !in_array($row->ho_type,$hoType)){
                                                    $dNone = 'd-none';
                                                }
                                            @endphp
                                            <div class="{{'col-md-4 child-when-where-'.$key.' '.$dNone}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("oh[child][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
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
                                        {{Form::number("oh[mtp_no]",!empty($oh->mtp_no) ? $oh->mtp_no : 0,['class'=>'form-control oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('mtp')}}
                                    </span>
                                </div>
                                @php
                                    $mtpNaturally = !empty($oh->mtp_no) && $oh->mtp_no != 0 ? '' : 'd-none';
                                @endphp
                            </div>

                            {{-- mtp data  --}}
                            <div class="mtp-data">
                                @if(!empty($oh)  && ($oh->mtp_no != null && $oh->mtp_no != 0))
                                    @foreach($oh->mtp->mtp_data as $key=>$row)
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
                                                    {{Form::radio("oh[mtp][mtp_data][".$key."][mtp_status]",'yes',!empty($row->mtp_status) && $row->mtp_status == 'yes' ? true : false,['id'=>'history_yes_'.$key,'class'=>'mtp-status','data-id'=>$key])}}
                                                    <label for={{'history_yes_'.$key}}>
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oh[mtp][mtp_data][".$key."][mtp_status]",'no',!empty($row->mtp_status) && $row->mtp_status == 'no' ? true : false,['id'=>'history_no_'.$key,'class'=>'mtp-status','data-id'=>$key])}}
                                                    <label for={{'history_no_'.$key}}>
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="{{'row col-md-9 '.$mtpStatus.' mtp-visible-'.$key}}">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[mtp][mtp_data][".$key."][mtp_type]",'medically',!empty($row->mtp_type) && $row->mtp_type == 'medically' ? true : false,['id'=>'Medically_'.$key])}}
                                                        <label for={{'Medically_'.$key}}>
                                                            Medically
                                                        </label>
                                                        {{Form::radio("oh[mtp][mtp_data][".$key."][mtp_type]",'surgically',!empty($row->mtp_type) && $row->mtp_type == 'surgically' ? true : false,['id'=>'surgically_'.$key])}}
                                                        <label for={{'surgically_'.$key}}>
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                        {{Form::text("oh[mtp][mtp_data][".$key."][mtp_month_of_pregancy]",!empty($row->mtp_month_of_pregancy) ? $row->mtp_month_of_pregancy : null,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('mtp_month_of_pregancy')}}
                                                    </span>
                                                </div> --}}
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("oh[mtp][mtp_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($row->spontancous_abortion_month_of_pregancy) ? $row->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                                    </div>
                                                    {{-- <span class="form-error-msg">
                                                        {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                                    </span> --}}
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before&nbsp;</span>
                                                        {{Form::text("oh[mtp][mtp_data][".$key."][spontancous_abortion_before]",!empty($row->spontancous_abortion_before) ? $row->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="{{'col-md-4 mtp-naturally '.$mtpNaturally}}">
                                                <div class="form-group">
                                                    {{Form::select("oh[mtp][mtp_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 mtp-ho-type p-ho-type','data-id'=>'mtp-when-where-'.$key,'placeholder'=>'Select Child Status'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('ho_details_2')}}
                                                </span>
                                            </div>
                                            @php
                                                $dNone = '';
                                                if(!empty($oh->mtp) && !empty($row->ho_type) && !in_array($row->ho_type,$hoType)){
                                                    $dNone = 'd-none';
                                                }
                                            @endphp
                                            <div class="{{'col-md-4 mtp-when-where-'.$key.' '.$dNone}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("oh[mtp][mtp_data][".$key."][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
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
                                        {{Form::number("oh[abortion_no]",!empty($oh->abortion_no) ? $oh->abortion_no : 0,['class'=>'form-control abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('abortion')}}
                                    </span>
                                </div>
                                @php
                                    $abortionNaturally = !empty($oh->abortion_no) && $oh->abortion_no != 0 ? '' : 'd-none';
                                @endphp
                            </div>

                            {{-- for abortion data--}}
                            <div class="abortion-data">
                                @if(!empty($oh) && ($oh->abortion_no != null && $oh->abortion_no != 0 ))
                                    @foreach($oh->abortion->abortion_data as $key=>$value)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="vertical-form-label pr-0">
                                                    Spontancous Abortion :
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
                                                    {{Form::radio("oh[abortion][abortion_data][".$key."][spontancous_abortion_status]",'yes',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes' ? true : false,['id'=>'spontancous_abortion_yes_'.$key,'class'=>'abortion-status','data-id'=>$key])}}
                                                    <label for="{{'spontancous_abortion_yes_'.$key}}">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oh[abortion][abortion_data][".$key."][spontancous_abortion_status]",'no',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'no' ? true : false,['id'=>'spontancous_abortion_no_'.$key,'class'=>'abortion-status','data-id'=>$key])}}
                                                    <label for="{{'spontancous_abortion_no_'.$key}}">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="{{'row col-md-8 '.$abortionStatus.' abortion-visible-'.$key}}">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[abortion][abortion_data][".$key."][spontancous_abortion_type]",'medically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type == 'medically' ? true : false,['id'=>'spontancous_abortion_medically_'.$key])}}
                                                        <label for="{{'spontancous_abortion_medically_'.$key}}">
                                                            Medically
                                                        </label>
                                                        {{Form::radio("oh[abortion][abortion_data][".$key."][spontancous_abortion_type]",'surgically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type== 'surgically' ? true : false,['id'=>'spontancous_abortion_surgically_'.$key])}}
                                                        <label for="{{'spontancous_abortion_surgically_'.$key}}">
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("oh[abortion][abortion_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($value->spontancous_abortion_month_of_pregancy) ? $value->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("oh[abortion][abortion_data][".$key."][spontancous_abortion_before]",!empty($value->spontancous_abortion_before) ? $value->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="{{'col-md-4 abortion-naturally '.$abortionNaturally}}">
                                                <div class="form-group">
                                                    {{Form::select("oh[abortion][abortion_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 abortion-ho-type p-ho-type','data-id'=>'abortion-when-where-'.$key,'placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            @php
                                                $dNone = '';
                                                if(!empty($oh->abortion) && !empty($value->ho_type) && !in_array($value->ho_type,$hoType)){
                                                    $dNone = 'd-none';
                                                }
                                            @endphp

                                            <div class="{{'col-md-4 abortion-when-where-'.$key.' '.$dNone}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("oh[abortion][abortion_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach`
                                @endif
                            </div>
                            {{-- contraception marriage start --}}
                            <div class='row'>
                                @php
                                    $contraceptionStatus = !empty($oh->contraception) && !empty($oh->contraception->contraception_status) && $oh->contraception->contraception_status == 'yes' ? true : false;
                                    $contraceptionStatusClass = $contraceptionStatus ? '' : 'd-none';
                                    $contraceptionValue = !empty($oh->contraception->contraception_data) ? $oh->contraception->contraception_data : null;
                                @endphp
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Contraception :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oh[contraception][contraception_status]",'yes',$contraceptionStatus,['id'=>'contraception_yes','class'=>'contraception-status','data-classname'=>'contraception-data'])}}
                                        <label for="contraception_yes">
                                            Yes
                                        </label>
                                        {{Form::radio("oh[contraception][contraception_status]",'no',!$contraceptionStatus ? true : false,['id'=>'contraception_no','class'=>'contraception-status','data-classname'=>'contraception-data'])}}
                                        <label for="contraception_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-sm-5 contraception-data ' . $contraceptionStatusClass}}">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oh[contraception][contraception_data]",'barrier_method',$contraceptionValue == 'barrier_method' ? true : false,['class'=>'mr-2','id'=>'barrier_method'])}}
                                        <label for="barrier_method">
                                            Barrier Method
                                        </label>
                                        {{Form::radio("oh[contraception][contraception_data]",'cu_t',$contraceptionValue == 'cu_t' ? true : false,['class'=>'mr-2','id'=>'cu_t'])}}
                                        <label for="cu_t">
                                            Cu - T
                                        </label>
                                        {{Form::radio("oh[contraception][contraception_data]",'tl_done',$contraceptionValue == 'tl_done' ? true : false,['class'=>'mr-2','id'=>'tl_done'])}}
                                        <label for="tl_done">
                                        TL Done
                                        </label>
                                        {{Form::radio("oh[contraception][contraception_data]",'occipill',$contraceptionValue == 'occipill' ? true : false,['class'=>'mr-2','id'=>'occipill'])}}
                                        <label for="occipill">
                                            Occipill
                                        </label>
                                        {{Form::radio("oh[contraception][contraception_data]",'other_contraception',$contraceptionValue == 'other_contraception' ? true : false,['class'=>'mr-2','id'=>'other_contraception'])}}
                                        <label for="other_contraception">
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- end contraception marriage --}}

                            {{-- for second merraige life--}}
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Second Merriage Life :
                                    </label>
                                </div>
                                @php
                                    $secondMerrageStatus = 'd-none';
                                    if(!empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes'){
                                        $secondMerrageStatus = '';
                                    }
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oh[second_marriage_life]",'yes',!empty($oh->second_marriage_life) && $oh->second_marriage_life == 'yes' ? true : false,['id'=>'second_marriage_life_yes','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'2'])}}
                                        <label for="second_marriage_life_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("oh[second_marriage_life]",'no',!empty($oh->second_marriage_life) && $oh->second_marriage_life == 'no' ? true : false,['id'=>'second_marriage_life_no','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'2'])}}
                                        <label for="second_marriage_life_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{ 'col-sm-4 second-marriage-life ' . $secondMerrageStatus }}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Second Marriage Life : &nbsp;</span>
                                        {{Form::text("oh[second_marriage_details]", !empty($oh->second_marriage_details) ? $oh->second_marriage_details : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>

                            <!-- for child -->
                            <div class="{{ 'row mt-3 second-marriage-life ' . $secondMerrageStatus }}">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{Form::select("oh[second_marriage][child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],!empty($oh->second_marriage->child_no) ? $oh->second_marriage->child_no : null,['class'=>'form-control second-child-no select-padding-0','placeholder'=>'Child No','data-type'=>'1'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('child_no')}}
                                    </span>
                                </div>
                                @php
                                    $childNaturally = !empty($oh->second_marriage->child_no) && $oh->second_marriage->child_no != 0 ? '' : 'd-none';
                                @endphp
                            </div>

                            <div class="{{ 'second-marriage-life second-child-data ' . $secondMerrageStatus}}">
                                {{-- for child data--}}
                                @if(!empty($oh) && isset($oh->second_marriage) && ($oh->second_marriage->child_no != null && $oh->second_marriage->child_no != 0))
                                    @foreach($oh->second_marriage->child->child_data as $key=>$row)
                                        <div class="row second-marriage-life-data">
                                            <div class="col-md-1">
                                                <label class="vertical-form-label pr-0">
                                                    H/O :
                                                </label>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_term]",'full',!empty($row->ho_term) && $row->ho_term  == 'full' ? true : false,['id'=>'second_full_'.$key])}}
                                                    <label for={{'second_full_'.$key}}>
                                                        Fullterm
                                                    </label>
                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_term]",'pre',!empty($row->ho_term) && $row->ho_term == 'pre' ? true : false,['id'=>'second_pre_'.$key])}}
                                                    <label for={{'second_pre_'.$key}}>
                                                        Preterm
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                {{Form::text("oh[second_marriage][child][child_data][".$key."][ho_term_details]", !empty($row->ho_term_details) ? $row->ho_term_details : null, [
                                                    'placeholder' => 'Term Details',
                                                    'class'=>'form-control',
                                                ])}}
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_type_value]",'normal',!empty($row->ho_type_value) && $row->ho_type_value == 'normal' ? true : false,['id'=>'second_normal_'.$key])}}
                                                    <label for={{'second_normal_'.$key}}>
                                                        Normal
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_type_value]",'cesarean',!empty($row->ho_type_value) && $row->ho_type_value == 'cesarean' ? true : false,['id'=>'second_cesarean_'.$key])}}
                                                    <label for={{'second_cesarean_'.$key}}>
                                                        Cesarean
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_type_value]",'instrumental',!empty($row->ho_type_value) && $row->ho_type_value == 'instrumental' ? true : false,['id'=>'second_instrumental_'.$key])}}
                                                    <label for="{{'second_instrumental_'.$key}}">
                                                        Instrumental
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_gender]",'male',!empty($row->ho_gender) && $row->ho_gender == 'male' ? true : false,['id'=>'second_ho_male_'.$key])}}
                                                    <label for={{'second_ho_male_'.$key}}>
                                                        Male
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_gender]",'female',!empty($row->ho_gender) && $row->ho_gender == 'female' ? true : false,['id'=>'second_ho_female_'.$key])}}
                                                    <label for={{'second_ho_female_'.$key}}>
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row second-marriage-life-data child-data-parent">
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_birth_type]",'live_health',!empty($row->ho_birth_type) && $row->ho_birth_type == 'live_health' ? true : false,['id'=>'second_live_health_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                                    <label for={{'second_live_health_'.$key}}>
                                                        Live Health
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_birth_type]",'stil_birth',!empty($row->ho_birth_type) && $row->ho_birth_type == 'stil_birth' ? true : false,['id'=>'second_stil_birth_'.$key,'class'=>'health-type','data-id'=>$key])}}
                                                    <label for={{'second_stil_birth_'.$key}}>
                                                        Stil Birth
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_birth_type]",'expired',!empty($row->ho_birth_type) && $row->ho_birth_type == 'expired' ? true : false,['id'=>'second_expired_'.$key,'class'=>'health-type','data-id'=>$key])}}
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
                                                    {{Form::text("oh[second_marriage][child][child_data][".$key."][expired_reason]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Reason'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('reason')}}
                                                </span>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                                    {{Form::text("oh[second_marriage][child][child_data][".$key."][live_health_year]",!empty($row->live_health_year) ? $row->live_health_year : null,['class'=>'form-control'])}}
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
                                                    {{Form::select("oh[second_marriage][child][child_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type second-p-ho-type','data-id'=>'second-child-when-where-'.$key,'placeholder'=>'Select Child Status'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('ho_details_1')}}
                                                </span>
                                            </div>
                                            @php
                                                $hoType = [2,3,4];
                                                $dNone = 'd-none';
                                                if(!empty($oh->second_marriage->child) && !empty($row->ho_type) && in_array($row->ho_type,$hoType)){
                                                    $dNone = '';
                                                }
                                            @endphp
                                            <div class="{{'col-md-4 second-child-when-where-'.$key.' second-marriage-life-data '.$dNone.' '.$childNaturally}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("oh[second_marriage][child][child_data][".$key."][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
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
                            <div class="{{ 'row mt-3 second-marriage-life ' . $secondMerrageStatus}}">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">MTP : &nbsp;</span>
                                        {{Form::number("oh[second_marriage][mtp_no]",!empty($oh->second_marriage->mtp_no) ? $oh->second_marriage->mtp_no : 0,['class'=>'form-control second_oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('mtp')}}
                                    </span>
                                </div>
                                @php
                                    $mtpNaturally = !empty($oh->second_marriage->mtp_no) && $oh->second_marriage->mtp_no != 0 ? '' : 'd-none';
                                @endphp
                            </div>

                            {{-- mtp data  --}}
                            <div class="{{ 'second-marriage-life second-mtp-data ' . $secondMerrageStatus }}">
                                @if(!empty($oh) && isset($oh->second_marriage) && ($oh->second_marriage->mtp_no != null && $oh->second_marriage->mtp_no != 0 ))
                                    @foreach($oh->second_marriage->mtp->mtp_data as $key=>$row)
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
                                                    {{Form::radio("oh[second_marriage][mtp][mtp_data][".$key."][mtp_status]",'yes',!empty($row->mtp_status) && $row->mtp_status == 'yes' ? true : false,['id'=>'second_history_yes_'.$key,'class'=>'second-mtp-status','data-id'=>$key])}}
                                                    <label for={{'second_history_yes_'.$key}}>
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][mtp][mtp_data][".$key."][mtp_status]",'no',!empty($row->mtp_status) && $row->mtp_status == 'no' ? true : false,['id'=>'second_history_no_'.$key,'class'=>'second-mtp-status','data-id'=>$key])}}
                                                    <label for={{'second_history_no_'.$key}}>
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="{{'row second-marriage-life-data col-md-9 '.$mtpStatus.' second-mtp-visible-'.$key}}">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[second_marriage][mtp][mtp_data][".$key."][mtp_type]",'medically',!empty($row->mtp_type) && $row->mtp_type == 'medically' ? true : false,['id'=>'second_Medically_'.$key])}}
                                                        <label for={{'second_Medically_'.$key}}>
                                                            Medically
                                                        </label>
                                                        {{Form::radio("oh[second_marriage][mtp][mtp_data][".$key."][mtp_type]",'surgically',!empty($row->mtp_type) && $row->mtp_type == 'surgically' ? true : false,['id'=>'second_surgically_'.$key])}}
                                                        <label for={{'second_surgically_'.$key}}>
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("oh[second_marriage][mtp][mtp_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($row->spontancous_abortion_month_of_pregancy) ? $row->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("oh[second_marriage][mtp][mtp_data][".$key."][spontancous_abortion_before]",!empty($row->spontancous_abortion_before) ? $row->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                        {{Form::text("oh[second_marriage][mtp][mtp_data][".$key."][mtp_month_of_pregancy]",!empty($row->mtp_month_of_pregancy) ? $row->mtp_month_of_pregancy : null,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('mtp_month_of_pregancy')}}
                                                    </span>
                                                </div> --}}
                                            </div>
                                        </div>
                                        <div class="row second-marriage-life-data">
                                            <div class="col-md-1"></div>
                                            <div class="{{'col-md-4 second-marriage-life-data second-mtp-naturally '.$mtpNaturally}}">
                                                <div class="form-group">
                                                    {{Form::select("oh[second_marriage][mtp][mtp_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 mtp-ho-type second-p-ho-type','data-id'=>'second-mtp-when-where-'.$key,'placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            @php
                                                $dNone = 'd-none';
                                                if(!empty($oh->second_marriage->mtp) && !empty($row->ho_type) && in_array($row->ho_type,$hoType)){
                                                    $dNone = '';
                                                }
                                            @endphp
                                            <div class="{{'col-md-4 second-mtp-when-where-'.$key. ' second-marriage-life-data '.$dNone.' '.$mtpNaturally}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("oh[second_marriage][mtp][mtp_data][".$key."][when_where]",!empty($row->when_where) ? $row->when_where : null,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- for Abortion -->
                            <div class="{{ 'second-marriage-life row mt-3 ' . $secondMerrageStatus}}">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Abortion : &nbsp;</span>
                                        {{Form::number("oh[second_marriage][abortion_no]",!empty($oh->second_marriage->abortion_no) ? $oh->second_marriage->abortion_no : 0,['class'=>'form-control second-abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('abortion')}}
                                    </span>
                                </div>
                                @php
                                    $abortionNaturally = !empty($oh->second_marriage->abortion_no) && $oh->second_marriage->abortion_no != 0 ? '' : 'd-none';
                                @endphp
                            </div>

                            {{-- for abortion data--}}
                            <div class="{{ 'second-marriage-life second-abortion-data ' . $secondMerrageStatus }}">
                                @if(!empty($oh) && isset($oh->second_marriage) && ($oh->second_marriage->abortion_no != null && $oh->second_marriage->abortion_no != 0 ))
                                    @foreach($oh->second_marriage->abortion->abortion_data as $key=>$value)
                                        <div class="row second-marriage-life-data">
                                            <div class="col-md-2">
                                                <label class="vertical-form-label pr-0">
                                                    Spontancous Abortion :
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
                                                    {{Form::radio("oh[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_status]",'yes',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'yes' ? true : false,['id'=>'second_spontancous_abortion_yes_'.$key,'class'=>'second-abortion-status','data-id'=>$key])}}
                                                    <label for="{{'second_spontancous_abortion_yes_'.$key}}">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oh[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_status]",'no',!empty($value->spontancous_abortion_status) && $value->spontancous_abortion_status == 'no' ? true : false,['id'=>'second_spontancous_abortion_no_'.$key,'class'=>'second-abortion-status','data-id'=>$key])}}
                                                    <label for="{{'second_spontancous_abortion_no_'.$key}}">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="{{'row second-marriage-life-data col-md-8 '.$abortionStatus.' second-abortion-visible-'.$key}}">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_type]",'medically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type == 'medically' ? true : false,['id'=>'second_spontancous_abortion_medically_'.$key])}}
                                                        <label for="{{'second_spontancous_abortion_medically_'.$key}}">
                                                            Medically
                                                        </label>
                                                        {{Form::radio("oh[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_type]",'surgically',!empty($value->spontancous_abortion_type) && $value->spontancous_abortion_type== 'surgically' ? true : false,['id'=>'second_spontancous_abortion_surgically_'.$key])}}
                                                        <label for="{{'second_spontancous_abortion_surgically_'.$key}}">
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Month Of Pregancy &nbsp;</span>
                                                        {{Form::text("oh[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_month_of_pregancy]",!empty($value->spontancous_abortion_month_of_pregancy) ? $value->spontancous_abortion_month_of_pregancy : null,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('spontancous_abortion_month_of_pregancy')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("oh[second_marriage][abortion][abortion_data][".$key."][spontancous_abortion_before]",!empty($value->spontancous_abortion_before) ? $value->spontancous_abortion_before : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row second-marriage-life-data">
                                            <div class="col-md-1"></div>
                                            <div class="{{'col-md-4 second-marriage-life-data second-abortion-naturally '.$abortionNaturally}}">
                                                <div class="form-group">
                                                    {{Form::select("oh[second_marriage][abortion][abortion_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 abortion-ho-type second-p-ho-type','data-id'=>'second-abortion-when-where-'.$key,'placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            @php
                                                $dNone = 'd-none';
                                                if(!empty($oh->second_marriage->abortion) && !empty($value->ho_type) && in_array($value->ho_type,$hoType)){
                                                    $dNone = '';
                                                }
                                            @endphp
                                            <div class="{{'col-md-4 second-marriage-life-data second-abortion-when-where-'.$key.' '.$dNone.' '.$abortionNaturally}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("oh[second_marriage][abortion][abortion_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            {{-- contraception second marriage start --}}
                            <div class="{{'row second-marriage-life ' . $secondMerrageStatus }}">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Contraception :
                                    </label>
                                </div>
                                @php
                                    $secondContraceptionStatus = !empty($oh->second_marriage->contraception) && !empty($oh->second_marriage->contraception->contraception_status) && $oh->second_marriage->contraception->contraception_status == 'yes' ? true : false;
                                    $secondContraceptionStatusClass = $secondContraceptionStatus ? '' : 'd-none';
                                    $secondContraceptionValue = !empty($oh->second_marriage->contraception->contraception_data) ? $oh->second_marriage->contraception->contraception_data : null;
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oh[second_marriage][contraception][contraception_status]",'yes',$secondContraceptionStatus,['id'=>'second_contraception_yes','class'=>'contraception-status','data-classname'=>'second-contraception-data'])}}
                                        <label for="second_contraception_yes">
                                            Yes
                                        </label>
                                        {{Form::radio("oh[second_marriage][contraception][contraception_status]",'no',!$secondContraceptionStatus ? true : false,['id'=>'second_contraception_no','class'=>'contraception-status','data-classname'=>'second-contraception-data'])}}
                                        <label for="second_contraception_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-sm-5 second-contraception-data ' . $secondContraceptionStatusClass}}">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oh[second_marriage][contraception][contraception_data]",'barrier_method',$secondContraceptionValue == 'barrier_method' ? true : false,['class'=>'mr-2','id'=>'second_barrier_method'])}}
                                        <label for="second_barrier_method">
                                            Barrier Method
                                        </label>
                                        {{Form::radio("oh[second_marriage][contraception][contraception_data]",'cu_t',$secondContraceptionValue == 'cu_t' ? true : false,['class'=>'mr-2','id'=>'second_cu_t'])}}
                                        <label for="second_cu_t">
                                            Cu - T
                                        </label>
                                        {{Form::radio("oh[second_marriage][contraception][contraception_data]",'tl_done',$secondContraceptionValue == 'tl_done' ? true : false,['class'=>'mr-2','id'=>'second_tl_done'])}}
                                        <label for="second_tl_done">
                                        TL Done
                                        </label>
                                        {{Form::radio("oh[second_marriage][contraception][contraception_data]",'occipill',$secondContraceptionValue == 'occipill' ? true : false,['class'=>'mr-2','id'=>'second_occipill'])}}
                                        <label for="second_occipill">
                                            Occipill
                                        </label>
                                        {{Form::radio("oh[second_marriage][contraception][contraception_data]",'other_contraception',$secondContraceptionValue == 'other_contraception' ? true : false,['class'=>'mr-2','id'=>'second_other_contraception'])}}
                                        <label for="second_other_contraception">
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- end contraception second marriage --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{Form::textarea("oh[remark]",!empty($oh->remark) ? $oh->remark : null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{Form::textarea('oh[remark]',!empty($oh->remark) ? $oh->remark : null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group drop-down-contra">
                                        {{Form::select("oh[ho_of_contraception]",['oc_pills'=>'OC Pills','iucd'=>'IUCD','condoms'=>'Condoms','coitus_intruptus'=>'Coitus Intruptus'],!empty($oh->ho_of_contraception) ? $oh->ho_of_contraception : 'oc_pills',['class'=>'form-control select-padding-0 ho-contraception','placeholder'=>'H/O Of Contraception'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('ho_of_contraception')}}
                                    </span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!--5 Personal history  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#personal-history" href="#personal-history" aria-expanded="false"
                                aria-controls="personal-history ">5. M/H</a></h4>
                    </div>
                    <div id="personal-history" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-5">
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
                                    <span class="form-error-msg">
                                        {{$errors->first('past_mh_2')}}
                                    </span>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        {{Form::select("mh[past_mh2_2]",['regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->past_mh2_2) ? $mh->past_mh2_2 : null,['class'=>'form-control select-padding-0'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 present-ir-regular-data">
                                    <div class="form-group">
                                        {{Form::select('mh[present_duration_of_day]',$durationOfData,!empty($mh->present_duration_of_day) ? $mh->present_duration_of_day : null,['class'=>'form-control select-padding-0 present-duration-of-day duration-data','title'=>'Select Duration Of Day','data-id'=>'present-details','data-live-search'=>'true'])}}
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
                                    <span class="form-error-msg">
                                        {{$errors->first('past_interval_of_day')}}
                                    </span>
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
                                    <span class="form-error-msg">
                                        {{$errors->first('present_mh_2')}}
                                    </span>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        {{Form::select("mh[present_mh2_2]",['regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->present_mh2_2) ? $mh->present_mh2_2 : null,['class'=>'form-control select-padding-0'])}}
                                    </div>
                                </div>

                                <div class="col-md-2 present-ir-regular-data">
                                    <div class="form-group">
                                        {{Form::select('mh[present_duration_of_day]',$durationOfData,!empty($mh->present_duration_of_day) ? $mh->present_duration_of_day : null,['class'=>'form-control select-padding-0 present-duration-of-day duration-data','placeholder'=>'Select Duration Of Day','data-id'=>'present-details','data-live-search'=>'true'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 d-none present-details">
                                    {{Form::text("mh[present_duration_of_day_details]",'',['placeholder' => 'Present Duration Of Day','class'=>'form-control present-duration-details'])}}
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group present-ir-regular-data">
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
                            <div class="row present-ir-regular-data">
                                <div class="col-md-2">
                                    <label class="vertical-form-label">
                                        Withdrawal By Medicine :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("mh[present_withdrawal_medicine]",'yes',!empty($mh->present_withdrawal_medicine) && $mh->present_withdrawal_medicine == 'yes' ? true : false,['id'=>'withdrawal_medicine_yes','class'=>'present-m'])}}
                                        <label for="withdrawal_medicine_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("mh[present_withdrawal_medicine]",'no',!empty($mh->present_withdrawal_medicine) && $mh->present_withdrawal_medicine == 'no' ? true : false,['id'=>'withdrawal_medicine_no','class'=>'present-m'])}}
                                        <label for="withdrawal_medicine_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Last Menstrual Date : &nbsp;</span>
                                        @php
                                            $lmddate = !empty($mh->last_menstrual_date) ? \Carbon\Carbon::parse($mh->last_menstrual_date) : null;
                                            $lmdDiff = null;
                                            if($lmddate){
                                                $now = \Carbon\Carbon::now();
                                                $lmdDiff = $lmddate->diffInDays($now);
                                                $lmdDiff = $lmdDiff + 1;
                                                $lmddate = \Carbon\Carbon::parse($lmddate)->format('D d M Y');
                                            }
                                        @endphp
                                        {{Form::text("mh[last_menstrual_date]",$lmddate,['class'=>'form-control lmd-date','required'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('last_mentsrual_date')}}
                                    </span>
                                </div>
                                <span class="col-md-1 p-2 lmd-date-diff">{{!empty($lmdDiff) ? $lmdDiff." Day" : null}}</span>
                                {{Form::hidden('mh[lmd_date_diff]','',['class'=>'lmd-date-diff-val'])}}

                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Since Month : &nbsp;</span>
                                        {{Form::text("mh[since_month]",!empty($mh->since_month) ? $mh->since_month : null,['class'=>'form-control'])}}
                                    </div>
                                    <span class="form-error-msg">
                                        {{$errors->first('since_month')}}
                                    </span>
                                </div>

                                <div class="col-md-2">
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

                <!--6. H/O rx -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#patients-detailed-ho" aria-expanded="false"
                                aria-controls="patients-detailed-ho">6. H/O Rx.</a></h4>
                    </div>
                    <div id="patients-detailed-ho" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        H/O Taken :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("ho_rx[taken][status]",'yes',!empty($hoRx->taken->status) && $hoRx->taken->status == 'yes' ? true : false,['id'=>'ho_taken_yes','class'=>'iui-yes-no-status','data-type'=>'ho-taken-type'])}}
                                        <label for="ho_taken_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("ho_rx[taken][status]",'no',!empty($hoRx->taken->status) && $hoRx->taken->status == 'no' ? true : false,['id'=>'ho_taken_no','class'=>'iui-yes-no-status','data-type'=>'ho-taken-type'])}}
                                        <label for="ho_taken_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $takenClass= '';
                                    $iuiClass = '';
                                    $ivfClass = '';
                                    if(!empty($hoRx->taken->status)){
                                        $takenClass = $hoRx->taken->status == 'no' ? 'd-none' : '';
                                    }
                                    if(!empty($hoRx->iui->status)){
                                        $iuiClass = $hoRx->iui->status == 'no' ? 'd-none' : '';
                                    }
                                    if(!empty($hoRx->ivf->status)){
                                        $ivfClass = $hoRx->ivf->status == 'no' ? 'd-none' : '';
                                    }

                                @endphp
                                <div class="{{'col-md-3 ho-taken-type '.$takenClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            How Much : &nbsp;
                                        </span>
                                        {{Form::number("ho_rx[taken][how_much_no]",!empty($hoRx->taken->how_much_no) ? $hoRx->taken->how_much_no : null,['class'=>'form-control how-much-taken','data-id'=>'taken','onwheel'=>'this.blur()'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{ 'row taken-data ho-taken-type ' . $takenClass }}">
                                @if(!empty($hoRx->taken->how_much_no))
                                    @for($i=1; $i<=$hoRx->taken->how_much_no; $i++)
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                {{Form::text('ho_rx[taken][how_much]['.$i.']',!empty($hoRx->taken->how_much->$i) ? $hoRx->taken->how_much->$i : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='input-group'>
                                                <span class='input-group-addon'>
                                                    When/Where : &nbsp;
                                                </span>
                                                {{Form::text('ho_rx[taken][when_where]['.$i.']',!empty($hoRx->taken->when_where->$i) ? $hoRx->taken->when_where->$i : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                {{Form::select('ho_rx[taken][type]['.$i.'][]',["1"=>"CC","2"=>"Letroz","3"=>"Both"],!empty($hoRx->taken->type->$i) ? $hoRx->taken->type->$i : null,['class'=>'form-control select-padding-0'])}}
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        I.U.I :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("ho_rx[iui][status]",'yes',!empty($hoRx->iui) && $hoRx->iui->status == 'yes' ? true : false,['id'=>'ho_iui_yes','class'=>'iui-yes-no-status','data-type'=>'ho-iui-type'])}}
                                        <label for="ho_iui_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("ho_rx[iui][status]",'no',!empty($hoRx->iui) && $hoRx->iui->status == 'no' ? true : false,['id'=>'ho_iui_no','class'=>'iui-yes-no-status','data-type'=>'ho-iui-type'])}}
                                        <label for="ho_iui_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ho-iui-type '.$iuiClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            How Much : &nbsp;
                                        </span>
                                        {{Form::number("ho_rx[iui][how_much_no]",!empty($hoRx->iui->how_much_no) ? $hoRx->iui->how_much_no : null,['class'=>'form-control how-much-taken','data-id'=>'iui','onwheel'=>'this.blur()'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ho-iui-type '.$iuiClass}}">
                                    <div class="form-group">
                                        {{Form::text("ho_rx[iui][details]",!empty($hoRx->iui->details) ? $hoRx->iui->details : null,['class'=>'form-control','placeholder'=>'Medicine'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{ 'row iui-data ho-iui-type ' . $iuiClass}}">
                                @if(!empty($hoRx->iui->how_much_no))
                                    @for($i=1; $i<=$hoRx->iui->how_much_no; $i++)
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                {{Form::text('ho_rx[iui][how_much]['.$i.']',!empty($hoRx->iui->how_much->$i) ? $hoRx->iui->how_much->$i : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='input-group'>
                                                <span class='input-group-addon'>
                                                    When/Where : &nbsp;
                                                </span>
                                                {{Form::text('ho_rx[iui][when_where]['.$i.']',!empty($hoRx->iui->when_where->$i) ? $hoRx->iui->when_where->$i : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                {{Form::select('ho_rx[iui][type]['.$i.'][]',["1"=>"IUI-H","2"=>"IUI-D","3"=>"Both"],!empty($hoRx->iui->type->$i) ? $hoRx->iui->type->$i : null,['class'=>'form-control  select-padding-0'])}}
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        I.V.F :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("ho_rx[ivf][status]",'yes',!empty($hoRx->ivf) && $hoRx->ivf->status == 'yes' ? true : false,['id'=>'ho_ivf_yes','class'=>'iui-yes-no-status','data-type'=>'ho-ivf-type'])}}
                                        <label for="ho_ivf_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("ho_rx[ivf][status]",'no',!empty($hoRx->ivf) && $hoRx->ivf->status == 'no' ? true : false,['id'=>'ho_ivf_no','class'=>'iui-yes-no-status','data-type'=>'ho-ivf-type'])}}
                                        <label for="ho_ivf_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ho-ivf-type '.$ivfClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            How Much : &nbsp;
                                        </span>
                                        {{Form::number("ho_rx[ivf][how_much_no]",!empty($hoRx->ivf->how_much_no) ? $hoRx->ivf->how_much_no : null,['class'=>'form-control how-much-taken','data-id'=>'ivf','onwheel'=>'this.blur()'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{ 'row ivf-data ho-ivf-type ' . $ivfClass }}">
                                @if(!empty($hoRx->ivf->how_much_no))
                                    @for($i=1; $i<=$hoRx->ivf->how_much_no; $i++)
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                {{Form::text('ho_rx[ivf][how_much]['.$i.']',!empty($hoRx->ivf->how_much->$i) ? $hoRx->ivf->how_much->$i : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='input-group'>
                                                <span class='input-group-addon'>
                                                    When/Where : &nbsp;
                                                </span>
                                                {{Form::text('ho_rx[ivf][when_where]['.$i.']',!empty($hoRx->ivf->when_where->$i) ? $hoRx->ivf->when_where->$i : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                {{Form::select('ho_rx[ivf][type]['.$i.'][]',["1"=>"IVF Self","2"=>"IVF-OD","3"=>"IVF-ED"],!empty($hoRx->ivf->type->$i) ? $hoRx->ivf->type->$i : null,['class'=>'form-control  select-padding-0','multiple'])}}
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!--7. Investigation  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#investigation" href="#investigation" aria-expanded="false"
                                aria-controls="investigation">7. Investigation</a></h4>
                    </div>
                    <div id="investigation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">

                            {{-- begin Hystroscopy --}}
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Hystroscopy :
                                    </label>
                                </div>
                                @php
                                    $hystroscopyClass = !empty($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes' ? true : false;
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("investigation[hystroscopy][type]",'yes',$hystroscopyClass,['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                        <label for="hystroscopy_type_yes">
                                            Yes
                                        </label>
                                        {{Form::radio("investigation[hystroscopy][type]",'no',!empty($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'no' ? true : false,['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                        <label for="hystroscopy_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $hystroscopyClassName = $hystroscopyClass ? '' : 'd-none';
                                @endphp
                                <div class="{{'col-md-1 pr-0 hystroscopy-type '.$hystroscopyClassName}}">
                                    <label class="vertical-form-label pr-0">
                                        Finding :
                                    </label>
                                </div>
                                <div class="{{'col-md-3 hystroscopy-type '.$hystroscopyClassName}}">
                                    <div class="form-group">
                                        {{Form::select("investigation[hystroscopy][finding_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->hystroscopy) ? $investigation->hystroscopy->finding_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'finding-type'])}}
                                    </div>
                                </div>
                                @php
                                    $fType = !empty($investigation->hystroscopy) && $investigation->hystroscopy->finding_type == '1' ? 'd-none' : '';
                                @endphp
                                <div class="{{'col-sm-3 finding-type hystroscopy-type-abnormal '.$fType.' '.$hystroscopyClassName}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[hystroscopy][abnormal_details]",!empty($investigation->hystroscopy) ? $investigation->hystroscopy->abnormal_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row hystroscopy-type '.$hystroscopyClassName}}">
                                <div class="col-md-3"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Finding Date:
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date : &nbsp;
                                        </span>
                                        {{Form::text("investigation[hystroscopy][finding_date]",!empty($investigation->hystroscopy) ? \Carbon\Carbon::parse($investigation->hystroscopy->finding_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        {{Form::text("investigation[hystroscopy][finding_details]",!empty($investigation->hystroscopy) ? $investigation->hystroscopy->finding_details : null,['class'=>'form-control date','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'mb-2 hystroscopy-type '.$hystroscopyClassName}}">
                                <div class="hystroscopy-images"></div>
                            </div>
                            {{-- end Hystroscopy  --}}

                            {{-- begin laproscopy  --}}
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        laproscopy :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date : &nbsp;
                                        </span>
                                        {{Form::text("investigation[laproscopy][finding_date]",!empty($investigation->laproscopy) ? \Carbon\Carbon::parse($investigation->laproscopy->finding_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                                    </div>
                                </div>
                                @php
                                    $investigationType = !empty($investigation->laproscopy) && !empty($investigation->laproscopy->type) && $investigation->laproscopy->type == 'yes' ? true : false;
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("investigation[laproscopy][type]",'yes',$investigationType,['id'=>'laproscopy_type_yes','class'=>'iui-yes-no-status','data-type'=>'laproscopy-status-type'])}}
                                        <label for="laproscopy_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("investigation[laproscopy][type]",'no',empty($investigation->laproscopy) || (!empty($investigation->laproscopy->type) && $investigation->laproscopy->type == 'no') ?  true : false,['id'=>'laproscopy_type_no','class'=>'iui-yes-no-status','data-type'=>'laproscopy-status-type'])}}
                                        <label for="laproscopy_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $investigationTypeClass = $investigationType ? '' : 'd-none';
                                @endphp
                                <div class="{{'col-md-4 laproscopy-status-type '.$investigationTypeClass}}">
                                    <div class="form-group">
                                        {{Form::select("investigation[laproscopy][laproscopy_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->laproscopy) ? $investigation->laproscopy->laproscopy_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'laproscopy-type'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                // laproscopy
                                $laproscopyType = empty($investigation->laproscopy) || $investigation->laproscopy->laproscopy_type == 1 ? 'd-none' : '';
                                $lrtTube = !empty($investigation->laproscopy) && $investigation->laproscopy->rt_tube_type == 1 ? 'd-none' : '';
                                $luterus  = !empty($investigation->laproscopy) && $investigation->laproscopy->uterus_type == 1 ? 'd-none' : '';
                                $llttube  = !empty($investigation->laproscopy) && $investigation->laproscopy->lt_tube_type == 1 ? 'd-none' : '';
                                // hcg data
                                $hcgStatus = !empty($investigation->hcg) && !empty($investigation->hcg->type) && $investigation->hcg->type == 'yes' ? true : false;
                                $hStatus =  $hcgStatus ? '' : 'd-none';
                                $hcgType = !empty($investigation->hcg) && $investigation->hcg->laproscopy_type == 1 ? 'd-none' : '';
                                $hrtTube = !empty($investigation->hcg) && $investigation->hcg->rt_tube_type == 1 ? 'd-none' : '';
                                $huterus  = !empty($investigation->hcg) && $investigation->hcg->uterus_type == 1 ? 'd-none' : '';
                                $hlttube  = !empty($investigation->hcg) && $investigation->hcg->lt_tube_type == 1 ? 'd-none' : '';
                            @endphp
                            <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        RT Tube :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("investigation[laproscopy][rt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->laproscopy) && $investigation->laproscopy->rt_tube_type,['class'=>'form-control select-padding-0 abnormal','data-type'=>'rt-tube-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-sm-4 rt-tube-type '.$lrtTube}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[laproscopy][rt_tube_details]",!empty($investigation->laproscopy) && !empty($investigation->laproscopy->rt_tube_details) ? $investigation->laproscopy->rt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Uterus :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("investigation[laproscopy][uterus_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->laproscopy) ? $investigation->laproscopy->uterus_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-sm-4 uterus-type '.$luterus}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[laproscopy][uterus_details]",!empty($investigation->laproscopy) && !empty($investigation->laproscopy->uterus_details) ? $investigation->laproscopy->uterus_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        LT Tube :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("investigation[laproscopy][lt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->laproscopy) ? $investigation->laproscopy->lt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'lt-tube-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-sm-4 lt-tube-type '.$llttube}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[laproscopy][lt_tube_details]",!empty($investigation->laproscopy) && !empty($investigation->laproscopy->lt_tube_details) ? $investigation->laproscopy->lt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType}}">
                                <div class="col-md-1"></div>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Other : &nbsp;
                                        </span>
                                        {{Form::text("investigation[laproscopy][other]",!empty($investigation->laproscopy) && !empty($investigation->laproscopy->other) ? $investigation->laproscopy->other : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'mb-2 laproscopy-status-type '.$investigationTypeClass}}">
                                <div class="laproscopy-images"></div>
                            </div>
                            {{-- end laproscopy  --}}


                            {{-- begin hcg  --}}
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        HSG  :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date : &nbsp;
                                        </span>
                                        {{Form::text("investigation[hcg][date]",!empty($investigation->hcg) && !empty($investigation->hcg->date) ? \Carbon\Carbon::parse($investigation->hcg->date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("investigation[hcg][type]",'yes',$hcgStatus,['id'=>'hcg_laproscopy_type_yes','class'=>'iui-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                                        <label for="hcg_laproscopy_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("investigation[hcg][type]",'no',$hcgStatus ? false : true,['id'=>'hcg_laproscopy_type_no','class'=>'iui-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                                        <label for="hcg_laproscopy_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 hcg-laproscopy-status-type '.$hStatus}}">
                                    <div class="form-group">
                                        {{Form::select("investigation[hcg][laproscopy_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->hcg) ? $investigation->hcg->laproscopy_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-laproscopy-type'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal '.$hcgType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        RT Tube :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("investigation[hcg][rt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->hcg) ? $investigation->hcg->rt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-rt-tube-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-sm-4 hcg-rt-tube-type '.$hrtTube}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[hcg][rt_tube_details]",!empty($investigation->hcg) && !empty($investigation->hcg->rt_tube_details) ?  $investigation->hcg->rt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal '.$hcgType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Uterus :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("investigation[hcg][uterus_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->hcg) ? $investigation->hcg->uterus_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-uterus-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-sm-4 hcg-uterus-type '.$huterus}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[hcg][uterus_details]",!empty($investigation->hcg) && !empty($investigation->hcg->uterus_details) ? $investigation->hcg->uterus_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal '.$hcgType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        LT Tube :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("investigation[hcg][lt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation->hcg) ? $investigation->hcg->lt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-lt-tube-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-sm-4 hcg-lt-tube-type '.$hlttube}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[hcg][lt_tube_details]",!empty($investigation->hcg) && !empty($investigation->hcg->lt_tube_details) ? $investigation->hcg->lt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'mb-2 hcg-laproscopy-status-type '.$hStatus}}">
                                <div class="hcg-images"></div>
                            </div>
                            {{-- end hcg  --}}
                            <br>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            AMH : &nbsp;
                                        </span>
                                        {{Form::text("investigation[amh]",!empty($investigation->amh) ? $investigation->amh : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            FSH : &nbsp;
                                        </span>
                                        {{Form::text("investigation[fsh]",!empty($investigation->fsh) ? $investigation->fsh : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Prolectin : &nbsp;
                                        </span>
                                        {{Form::text("investigation[prolectin]",!empty($investigation->prolectin) ? $investigation->prolectin : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            LH : &nbsp;
                                        </span>
                                        {{Form::text("investigation[lh]",!empty($investigation->lh) ? $investigation->lh : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            E2 : &nbsp;
                                        </span>
                                        {{Form::text("investigation[e2]",!empty($investigation->e2) ? $investigation->e2 : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            P2 : &nbsp;
                                        </span>
                                        {{Form::text("investigation[p2]",!empty($investigation->p2) ? $investigation->p2 : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date : &nbsp;
                                        </span>
                                        {{Form::text("investigation[date_2]",!empty($investigation->date_2) ? \Carbon\Carbon::parse($investigation->date_2)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $investigationValue = !empty($investigation->investigation_details) ? (array)$investigation->investigation_details : [];
                                $cbcWnlStatus = !empty($investigation->investigation_cbc_mp_details->status) && $investigation->investigation_cbc_mp_details->status == '2' ? '' : 'd-none';
                                $urinWnlStatus = !empty($investigation->investigation_urine_value_details->status) && $investigation->investigation_urine_value_details->status == '2' ? '' : 'd-none';
                                $tshWnlStatus = !empty($investigation->investigation_tsh_value_details->status) && $investigation->investigation_tsh_value_details->status == '2' ? '' : 'd-none';
                            @endphp
                            {{-- report data --}}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','1',checkData(1,$investigation),['id'=>'cbc_mp','class'=>'plan-management','data-id'=>'cbc-mp-details'])}}
                                        <label for="cbc_mp">
                                            CBC / MP
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 cbc-mp-details '.(checkData(1,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][1]",!empty($investigationValue[1]) ? $investigationValue[1] : null,['class'=>'form-control','placeholder'=>'CBC MP Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','2',checkData(2,$investigation),['id'=>'fbs','class'=>'plan-management','data-id'=>'fbs-data-details'])}}
                                        <label for="fbs">
                                            FBS
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 fbs-data-details '.(checkData(2,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][2]",!empty($investigationValue[2]) ? $investigationValue[2] : null,['class'=>'form-control','placeholder'=>'FBS Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row cbc-mp-details '.(checkData(1,$investigation) ? '' : 'd-none')}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{Form::select("investigation[investigation_cbc_mp_details][status]",['1'=>"WNL",'2'=>"Abnormal"],!empty($investigation->investigation_cbc_mp_details->status) ? $investigation->investigation_cbc_mp_details->status : null,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 cbc-mb-type-details-value '.$cbcWnlStatus}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Aneamia : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_cbc_mp_details][aneamia]",!empty($investigation->investigation_cbc_mp_details->aneamia) ? $investigation->investigation_cbc_mp_details->aneamia : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 cbc-mb-type-details-value '.$cbcWnlStatus}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Leacocytosis : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_cbc_mp_details][leacocytosis]",!empty($investigation->investigation_cbc_mp_details->leacocytosis) ? $investigation->investigation_cbc_mp_details->leacocytosis : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','3',checkData(3,$investigation),['id'=>'urine_r','class'=>'plan-management','data-id'=>'urine-details'])}}
                                        <label for="urine_r">
                                            Urine - R
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 urine-details '.(checkData(3,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][3]",!empty($investigationValue[3]) ? $investigationValue[3] : null,['class'=>'form-control','placeholder'=>'Urine Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','4',checkData(4,$investigation),['id'=>'ppbs','class'=>'plan-management','data-id'=>'ppbs-data-details'])}}
                                        <label for="ppbs">
                                            PPBS
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 ppbs-data-details '.(checkData(4,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][4]",!empty($investigationValue[4]) ? $investigationValue[4] : null,['class'=>'form-control','placeholder'=>'PPBS Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row urine-details '.(checkData(3,$investigation) ? '' : 'd-none')}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{Form::select("investigation[investigation_urine_value_details][status]",['1'=>"WNL",'2'=>"Abnormal"],!empty($investigation->investigation_urine_value_details->status) ? $investigation->investigation_urine_value_details->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-details-value','placeholder'=>'Select CBC MB Type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 urine-details-value '.$urinWnlStatus}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Aneamia : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_urine_value_details][aneamia]",!empty($investigation->investigation_urine_value_details->aneamia) ? $investigation->investigation_urine_value_details->aneamia : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 urine-details-value '.$urinWnlStatus}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Leacocytosis : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_urine_value_details][leacocytosis]",!empty($investigation->investigation_urine_value_details->leacocytosis) ? $investigation->investigation_urine_value_details->leacocytosis : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','5',checkData(5,$investigation),['id'=>'esr','class'=>'plan-management','data-id'=>'esr-details'])}}
                                        <label for="esr">
                                            ESR
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 esr-details '.(checkData(5,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][5]",!empty($investigationValue[5]) ? $investigationValue[5] : null,['class'=>'form-control','placeholder'=>'ESR Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','6',checkData(6,$investigation),['id'=>'rbs','class'=>'plan-management','data-id'=>'rbs-data-details'])}}
                                        <label for="rbs">
                                            RBS
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 rbs-data-details '.(checkData(6,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][6]",!empty($investigationValue[6]) ? $investigationValue[6] : null,['class'=>'form-control','placeholder'=>'RBS Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','7',checkData(7,$investigation),['id'=>'sgpt','class'=>'plan-management','data-id'=>'sgpt-details'])}}
                                        <label for="sgpt">
                                            SGPT
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 sgpt-details '.(checkData(7,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][7]",!empty($investigationValue[7]) ? $investigationValue[7] : null,['class'=>'form-control','placeholder'=>'SGPT Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','8',checkData(8,$investigation),['id'=>'hbsag','class'=>'plan-management','data-id'=>'hbsag-details'])}}
                                        <label for="hbsag">
                                            HBsAg
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 hbsag-details '.(checkData(8,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][8]",!empty($investigationValue[8]) ? $investigationValue[8] : null,['class'=>'form-control','placeholder'=>'HBsAg Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','9',checkData(9,$investigation),['id'=>'screatinine','class'=>'plan-management','data-id'=>'screatinine-details'])}}
                                        <label for="screatinine">
                                            S.Creatinine
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 screatinine-details '.(checkData(9,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][9]",!empty($investigationValue[9]) ? $investigationValue[9] : null,['class'=>'form-control','placeholder'=>'S. Creatinine Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','10',checkData(10,$investigation),['id'=>'hiv','class'=>'plan-management','data-id'=>'hiv-details'])}}
                                        <label for="hiv">
                                            HIV
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 hiv-details '.(checkData(10,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][10]",!empty($investigationValue[10]) ? $investigationValue[10] : null,['class'=>'form-control','placeholder'=>'HIV Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','11',checkData(11,$investigation),['id'=>'crp','class'=>'plan-management','data-id'=>'crp-details'])}}
                                        <label for="crp">
                                            CRP
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 crp-details '.(checkData(11,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][11]",!empty($investigationValue[11]) ? $investigationValue[11] : null,['class'=>'form-control','placeholder'=>'CRP Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','12',checkData(12,$investigation),['id'=>'blood_group','class'=>'plan-management','data-id'=>'blood-details'])}}
                                        <label for="blood_group">
                                            Blood Group
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 blood-details '.(checkData(12,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][12]",!empty($investigationValue[12]) ? $investigationValue[12] : null,['class'=>'form-control','placeholder'=>'Blood Group Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','13',checkData(13,$investigation),['id'=>'slied','class'=>'plan-management','data-id'=>'slied-details'])}}
                                        <label for="slied">
                                            Serum Widal
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 slied-details '.(checkData(13,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][13]",!empty($investigationValue[13]) ? $investigationValue[13] : null,['class'=>'form-control','placeholder'=>'Serum Widal Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','14',checkData(14,$investigation),['id'=>'tsh','class'=>'plan-management','data-id'=>'tsh-data-details'])}}
                                        <label for="tsh">
                                            TSH
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 tsh-data-details '.(checkData(14,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][14]",!empty($investigationValue[14]) ? $investigationValue[14] : null,['class'=>'form-control','placeholder'=>'TSH Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tsh-data-details '.(checkData(14,$investigation) ? '' : 'd-none')}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{Form::select("investigation[investigation_tsh_value_details][status]",['1'=>"WNL",'2'=>"Abnormal"],!empty($investigation->investigation_tsh_value_details->status) ? $investigation->investigation_tsh_value_details->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>'tsh-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 tsh-type-details-value '.$tshWnlStatus}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Aneamia : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_tsh_value_details][aneamia]",!empty($investigation->investigation_tsh_value_details->aneamia) ? $investigation->investigation_tsh_value_details->aneamia : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 tsh-type-details-value '.$tshWnlStatus}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Leacocytosis : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_tsh_value_details][leacocytosis]",!empty($investigation->investigation_tsh_value_details->leacocytosis) ? $investigation->investigation_tsh_value_details->leacocytosis : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','15',checkData(15,$investigation),['id'=>'typhidot','class'=>'plan-management','data-id'=>'typhidot-lgm-details'])}}
                                        <label for="typhidot">
                                            Typhidot lgM
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 typhidot-lgm-details '.(checkData(15,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][15]",!empty($investigationValue[15]) ? $investigationValue[15] : null,['class'=>'form-control','placeholder'=>'Typhidot lgM Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','16',checkData(16,$investigation),['id'=>'t3','class'=>'plan-management','data-id'=>'t3-details'])}}
                                        <label for="t3">
                                            T3, T4, TSH
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 t3-details '.(checkData(16,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][16]",!empty($investigationValue[16]) ? $investigationValue[16] : null,['class'=>'form-control','placeholder'=>'T3, T4, TSH Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','17',checkData(17,$investigation),['id'=>'lipid_profile','class'=>'plan-management','data-id'=>'lipid-profile-details'])}}
                                        <label for="lipid_profile">
                                            Lipid Profile
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 lipid-profile-details '.(checkData(17,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][17]",!empty($investigationValue[17]) ? $investigationValue[17] : null,['class'=>'form-control','placeholder'=>'Lipid Profile Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','18',checkData(18,$investigation),['id'=>'vitb12','class'=>'plan-management','data-id'=>'vit-b12-details'])}}
                                        <label for="vitb12">
                                            Vit B-12
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 vit-b12-details '.(checkData(18,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][18]",!empty($investigationValue[18]) ? $investigationValue[18] : null,['class'=>'form-control','placeholder'=>'Vit B-12 Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','19',checkData(19,$investigation),['id'=>'tube-widal','class'=>'plan-management','data-id'=>'tube-widal-details'])}}
                                        <label for="tube-widal">
                                            Tube Widal
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 tube-widal-details '.(checkData(19,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][19]",!empty($investigationValue[19]) ? $investigationValue[19] : null,['class'=>'form-control','placeholder'=>'Tube Widal Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','20',checkData(20,$investigation),['id'=>'vitd3','class'=>'plan-management','data-id'=>'vit-d3-details'])}}
                                        <label for="vitd3">
                                            Vit D-3
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 vit-d3-details '.(checkData(20,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][20]",!empty($investigationValue[20]) ? $investigationValue[20] : null,['class'=>'form-control','placeholder'=>'Vit D-3 Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','21',checkData(21,$investigation),['id'=>'lft','class'=>'plan-management','data-id'=>'lft-details'])}}
                                        <label for="lft">
                                            LFT
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 lft-details '.(checkData(21,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][21]",!empty($investigationValue[21]) ? $investigationValue[21] : null,['class'=>'form-control','placeholder'=>'LFT Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','22',checkData(22,$investigation),['id'=>'anc_profile','class'=>'plan-management','data-id'=>'anc-profile-details'])}}
                                        <label for="anc_profile">
                                            ANC Profile
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 anc-profile-details '.(checkData(22,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][22]",!empty($investigationValue[22]) ? $investigationValue[22] : null,['class'=>'form-control','placeholder'=>'ANC Profile Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','23',checkData(23,$investigation),['id'=>'rft','class'=>'plan-management','data-id'=>'rft-details'])}}
                                        <label for="rft">
                                            RFT
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 rft-details '.(checkData(23,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][23]",!empty($investigationValue[23]) ? $investigationValue[23] : null,['class'=>'form-control','placeholder'=>'RFT Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','24',checkData(24,$investigation),['id'=>'pre_major','class'=>'plan-management','data-id'=>'pre-major-profile-details'])}}
                                        <label for="pre_major">
                                            Pre oper.Profile(Major)
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 pre-major-profile-details '.(checkData(24,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][24]",!empty($investigationValue[24]) ? $investigationValue[24] : null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Major) Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','25',checkData(25,$investigation),['id'=>'scalcium','class'=>'plan-management','data-id'=>'scalcium-details'])}}
                                        <label for="scalcium">
                                            S.Calcium
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 scalcium-details '.(checkData(25,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][25]",!empty($investigationValue[25]) ? $investigationValue[25] : null,['class'=>'form-control','placeholder'=>'S. Calcium Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','26',checkData(26,$investigation),['id'=>'pre_minor','class'=>'plan-management','data-id'=>'pre-minor-profile-details'])}}
                                        <label for="pre_minor">
                                            Pre oper.Profile(Minor)
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 pre-minor-profile-details '.(checkData(26,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][26]",!empty($investigationValue[26]) ? $investigationValue[26] : null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Minor) Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','27',checkData(27,$investigation),['id'=>'eletrolytes','class'=>'plan-management','data-id'=>'eletrolytes-details'])}}
                                        <label for="eletrolytes">
                                            S.Eletrolytes
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 eletrolytes-details '.(checkData(27,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][27]",!empty($investigationValue[27]) ? $investigationValue[27] : null,['class'=>'form-control','placeholder'=>'S. Eletrolytes Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','28',checkData(28,$investigation),['id'=>'denue_duo','class'=>'plan-management','data-id'=>'denue-duo-details'])}}
                                        <label for="denue_duo">
                                            Dengue Duo
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 denue-duo-details '.(checkData(28,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][28]",!empty($investigationValue[28]) ? $investigationValue[28] : null,['class'=>'form-control','placeholder'=>'Dengue Duo Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','29',checkData(29,$investigation),['id'=>'billirubin','class'=>'plan-management','data-id'=>'billirubin-details'])}}
                                        <label for="billirubin">
                                            S.Billirubin
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 billirubin-details '.(checkData(29,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][29]",!empty($investigationValue[29]) ? $investigationValue[29] : null,['class'=>'form-control','placeholder'=>'S. billirubin Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('investigation[investigation_data][]','30',checkData(30,$investigation),['id'=>'denue_ns1','class'=>'plan-management','data-id'=>'denue-ns1-details'])}}
                                        <label for="denue_ns1">
                                            Dengue NS1
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 denue-ns1-details '.(checkData(30,$investigation) ? '' : 'd-none')}}">
                                    <div class="form-group">
                                        {{Form::text("investigation[investigation_details][30]",!empty($investigationValue[30]) ? $investigationValue[30] : null,['class'=>'form-control','placeholder'=>'Dengue NS1 Details'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                            $bloodReportClass = !empty($investigation->blood_report) && !empty($investigation->blood_report->type) && $investigation->blood_report->type == 'yes' ? true : false;
                            $bloodReportClassName = $bloodReportClass ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Blood Report :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("investigation[blood_report][type]",'yes',$bloodReportClass,['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                        <label for="blood_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("investigation[blood_report][type]",'no',!empty($investigation->blood_report) && !empty($investigation->blood_report->type) && $investigation->blood_report->type == 'no' ? true : false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                        <label for="blood_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-8 pr-0 blood-type '.$bloodReportClassName}}">
                                    <div class="blood-images"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Other Report : &nbsp;
                                        </span>
                                        {{Form::text("investigation[investigation_extra]",isset($investigation->investigation_extra) && !empty($investigation->investigation_extra) ? $investigation->investigation_extra : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--8. Patients Detailed H/O  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#husband_factor" href="#husband_factor" aria-expanded="false"
                                aria-controls="husband_factor">8. Husband Factor</a></h4>
                    </div>
                    <div id="husband_factor" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Occupation : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[occupation]",!empty($husbandFactor->occupation) ? $husbandFactor->occupation : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select("h_factor[seman_analysis]",['1'=>'WNL','2'=>'Oligospermia','3'=>'Azoospermic'],!empty($husbandFactor->seman_analysis) ? $husbandFactor->seman_analysis : null,['class'=>'form-control select-padding-0 seman-analysis','placeholder'=>'Seman Analysis'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Age : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[age]",!empty($husbandFactor->age) ? $husbandFactor->age : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Habbit : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[habbit]",!empty($husbandFactor->habbit) ? $husbandFactor->habbit : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Amount In Ml : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[amount_in_ml]",!empty($husbandFactor->amount_in_ml) ? $husbandFactor->amount_in_ml : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Sperm Count : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[sperm_count]",!empty($husbandFactor->sperm_count) ? $husbandFactor->sperm_count : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Motility : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[motility]",!empty($husbandFactor->motility) ? $husbandFactor->motility : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[personal_history_date]",!empty($husbandFactor->personal_history_date) ? \Carbon\Carbon::parse($husbandFactor->personal_history_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $semanTypeClass = 'd-none';
                                if(!empty($husbandFactor->seman_analysis) && $husbandFactor->seman_analysis == 2){
                                    $semanTypeClass = '';
                                }
                            @endphp
                            <div class="{{'row seman-analysis-type '.$semanTypeClass}}">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Medicine : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[medicine]",!empty($husbandFactor->medicine) ? $husbandFactor->medicine : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Duration : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[duration]",!empty($husbandFactor->duration) ? $husbandFactor->duration : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Sperm Report : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[sperm_report]",!empty($husbandFactor->sperm_report) ? $husbandFactor->sperm_report : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                    $hsaReportClass = !empty($investigation->hsa_report) && !empty($investigation->hsa_report->type) && $investigation->hsa_report->type == 'yes' ? true : false;
                                    $hsaReportClassName = $hsaReportClass ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        HSA Report :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("investigation[hsa_report][type]",'yes',$hsaReportClass,['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                                        <label for="hsa_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("investigation[hsa_report][type]",'no',!empty($investigation->hsa_report) && !empty($investigation->hsa_report->type) && $investigation->hsa_report->type == 'no' ? true : false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                                        <label for="hsa_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-8 pr-0 hsa-type '.$hsaReportClassName}}">
                                    <div class="hsa-images"></div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        {{Form::textarea("h_factor[remark]",!empty($husbandFactor->remark) ? $husbandFactor->remark : null,['class'=>'form-control remark','placeholder'=>'Remark','rows'=>'5'])}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--9. Patients Detailed H/O  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#patients-detailed-ho" aria-expanded="false"
                                aria-controls="patients-detailed-ho">9. Patients Detailed H/O</a></h4>
                    </div>
                    <div id="patients-detailed-ho" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Personal History :
                                    </label>
                                </div>
                                <div class="col-md-4 complain-multi ho-past-personal-data">
                                    {{Form::select('p_detailes[personal_history_history_type][]',$personalData,!empty($patientsDetailsHo->personal_history_history_type) ? $patientsDetailsHo->personal_history_history_type : null,['class'=>'form-control co-value co_value_data personal-history mb-3','placeholder'=>'Select Personal History','multiple'=>true])}}
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date : &nbsp;
                                        </span>
                                        {{Form::text("p_detailes[personal_history_date]",!empty($patientsDetailsHoHo->personal_history_date) ? \Carbon\Carbon::parse($patientsDetailsHoHo->personal_history_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Family History :
                                    </label>
                                </div>
                                <div class="col-md-4 complain-multi">
                                    {{Form::select('p_detailes[family_history][]',$familyData,!empty($patientsDetailsHo->family_history) ? $patientsDetailsHo->family_history : null,['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Family History','multiple'=>true])}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Past History :
                                    </label>
                                </div>
                                <div class="col-md-4 complain-multi ho-past-personal-data">
                                    {{Form::select('p_detailes[past_history_type][]',$pastData,!empty($patientsDetailsHo->past_history_type) ? $patientsDetailsHo->past_history_type : null,['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Past History','multiple'=>true])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 10. o/e--}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#o_e" href="#o_e" aria-expanded="false"
                                aria-controls="past-history">10. O/E</a></h4>
                    </div>
                    <div id="o_e" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        P/S :
                                    </label>
                                </div>
                                @php
                                    $psType = !empty($oe->p_s->type) && $oe->p_s->type == 'yes' ? '' : 'd-none';
                                @endphp
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oe[p_s][type]",'yes',!empty($psType) ? false : true,['id'=>'ps_type_yes','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                        <label for="ps_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("oe[p_s][type]",'no',!empty($psType) ? true : false,['id'=>'ps_type_no','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                        <label for="ps_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-5 ps-details '.$psType}}">
                                    <div class="form-group">
                                        {{Form::text("oe[p_s][details]",!empty($oe->p_s->details) ? $oe->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Cervix :
                                    </label>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        {{Form::text("oe[cervix][details]",!empty($oe->cervix->details) ? $oe->cervix->details : null,['class'=>'form-control','placeholder'=>'Cervix Details'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $tvsType = !empty($oe->tvs->type) && $oe->tvs->type == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        TVS :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("oe[tvs][type]",'yes',!empty($tvsType) ? false : true,['id'=>'tvs_type_yes','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                        <label for="tvs_type_yes">
                                            Yes
                                        </label>
                                        {{Form::radio("oe[tvs][type]",'no',!empty($tvsType) ? true : false,['id'=>'tvs_type_no','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                        <label for="tvs_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Uterus :
                                    </label>
                                </div>
                                <div class="col-md-2 tvs-details">
                                    <div class="form-group">
                                        {{Form::select("oe[uterus][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($oe->uterus->type) ? $oe->uterus->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
                                    </div>
                                </div>
                                @php
                                    $uterusType = !empty($oe->uterus->type) && $oe->uterus->type == '2' ? '' : 'd-none';
                                @endphp
                                <div class="{{'col-md-2 uterus-abnormal-type '.$uterusType}}">
                                    <div class="form-group">
                                        {{Form::text("oe[uterus][details]",!empty($oe->uterus->details) ? $oe->uterus->details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 uterus-abnormal-type '.$uterusType}}">LG</span>
                            </div>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Endometrial Thickness :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::text("oe[endometrial_thickness]",!empty($oe->endometrial_thickness) ? $oe->endometrial_thickness : null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                                    </div>
                                </div>
                            </div>

                            @php
                                $left = in_array('left',!empty($oe->ovary->type) ? $oe->ovary->type : []) ? '' : 'd-none';
                                $right = in_array('right',!empty($oe->ovary->type) ? $oe->ovary->type : []) ? '' : 'd-none';
                                $afcsOption = isset($mh->lmd_date_diff) && in_array($mh->lmd_date_diff,['2','3','4']) ? '' : 'd-none';
                                $ovaryLeftType = !empty($oe->ovary->left->type) && $oe->ovary->left->type == '2' ? '' : 'd-none';
                                $ovaryRightType = !empty($oe->ovary->right->type) && $oe->ovary->right->type == '2' ? '' : 'd-none';
                            @endphp
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Ovary :
                                    </label>
                                </div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('oe[ovary][type][]','right',in_array('right',!empty($oe->ovary->type) ? $oe->ovary->type : []),['id'=>'right','class'=>'plan-management'])}}
                                        <label for="right">
                                            Right
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 right-details'}}">
                                    <div class="form-group">
                                        {{Form::select("oe[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($oe->ovary->right->type) ? $oe->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 afcs-details '.$afcsOption}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">AFCS : &nbsp;</span>
                                        {{Form::text("oe[ovary][right][afcs]",!empty($oe->ovary->right->afcs) ? $oe->ovary->right->afcs : null,['class'=>'form-control right-ovary-data-text'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-1 afcs-details '.$afcsOption}}">
                                    <a href="javascript:void(0)" class="right-ovary-data overy-popup" data-class='right-ovary-data'>Keyboard</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="{{'col-md-9 right-details tvs-details '.$tvsType}}">
                                    <div class="row">
                                        <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type mt-1 '.$ovaryRightType}}">
                                            {{Form::select("oe[ovary][right][details][]",$rightOvaryData,!empty($oe->ovary->right->details) ? $oe->ovary->right->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_right_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-6 complain-multi ovary-right-abnormal-type">
                                            <div class="row edit_oe_ovary_right_details">
                                                @if (isset($oe->ovary->right->updated_details))
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
                            <br>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('oe[ovary][type][]','left',in_array('left',!empty($oe->ovary->type) ? $oe->ovary->type : []),['id'=>'left','class'=>'plan-management'])}}
                                        <label for="left">
                                            Left
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 left-details'}}">
                                    <div class="form-group">
                                        {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($oe->ovary->left->type) ? $oe->ovary->left->type : null,[
                                            'class'=>'form-control select-padding-0 abnormal',
                                            'data-type'=>'ovary-left-abnormal-type'
                                        ])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 afcs-details '.$afcsOption}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">AFCS : &nbsp;</span>
                                        {{Form::text("oe[ovary][left][afcs]",!empty($oe->ovary->left->afcs) ? $oe->ovary->left->afcs : null,['class'=>'form-control left-ovary-data-text'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-1 afcs-details '.$afcsOption}}">
                                    <a href="javascript:void(0)" class="left-ovary-data overy-popup" data-class='left-ovary-data'>Keyboard</a>
                                </div>
                            </div>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-3"></div>
                                <div class="{{'col-md-9 left-details'}}">
                                    <div class="row">
                                        <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type'}} ">
                                            {{Form::select("oe[ovary][left][details][]",$leftOvaryData,!empty($oe->ovary->left->details) ? $oe->ovary->left->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_left_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-6 complain-multi ovary-left-abnormal-type">
                                            <div class="row edit_oe_ovary_left_details">
                                                @if (isset($oe->ovary->left->updated_details))
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
                            @php
                                $oeVitlasClass = !empty($oe->le->vitals_status) && $oe->le->vitals_status == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('oe[le][vitals_status]','yes',!empty($oe->le->vitals_status) && $oe->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'oe_vitals_status','data-id'=>'oe_vitals_status_data'])}}
                                        <label for="oe_vitals_status">
                                            Vitals
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-2 oe_vitals_status_data '.$oeVitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                        {{Form::text("oe[le][bp]",!empty($oe->le->bp) ? $oe->le->bp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 oe_vitals_status_data '.$oeVitlasClass}}">MMHG</span>
                                <div class="{{'col-md-2 oe_vitals_status_data '.$oeVitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                        {{Form::text("oe[le][temp]",!empty($oe->le->temp) ? $oe->le->temp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 oe_vitals_status_data '.$oeVitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                        {{Form::text("oe[le][pulse]",!empty($oe->le->pulse) ? $oe->le->pulse : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 oe_vitals_status_data '.$oeVitlasClass}}">/ Min</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!--11. Possible cause of infertility  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#plan_management" href="#plan_management" aria-expanded="false"
                                aria-controls="past-history">11. Plan Of Management</a></h4>
                    </div>
                    <div id="plan_management" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            @php
                                $planData = !empty($planManagement->plan_of_management_data) ? $planManagement->plan_of_management_data : [];
                                $waitWatch = in_array('wait_watch',$planData) ? '' : 'd-none';
                                $ivf = in_array('ivf',$planData) ? '' : 'd-none';
                                $iui = in_array('iui',$planData) ? '' : 'd-none';
                                $inductionGonadotropinsCycle = in_array('induction_gonadotropins_cycle',$planData) ? '' : 'd-none';
                                $hyperstimulation = in_array('hyperstimulation_iui',$planData) ? '' : 'd-none';
                                $laproscopy = in_array('laproscopy',$planData) ? '' : 'd-none';
                                $maleFactor = in_array('male_factor',$planData) ? '' : 'd-none';
                                $other = in_array('other',$planData) ? '' : 'd-none';
                                $counceling = in_array('counceling',$planData) ? '' : 'd-none';
                                $management = in_array('management_by_rx',$planData) ? '' : 'd-none';
                                $reports = in_array('reports',$planData) ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','counceling',!empty($counceling) ? false : true,['id'=>'counceling','class'=>'plan-management','data-id'=>'counceling'])}}
                                        <label for="counceling">
                                            Counceling
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 counceling '.$counceling}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[counceling_details]",!empty($planManagement->counceling_details) ? $planManagement->counceling_details : null,['class'=>'form-control','placeholder'=>'Counceling Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','wait_watch',!empty($waitWatch) ? false : true,['id'=>'wait_watch','class'=>'plan-management','data-id'=>'wait-watch'])}}
                                        <label for="wait_watch">
                                            Wait Watch
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 wait-watch '.$waitWatch}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[wait_watch_details]",!empty($planManagement->wait_watch_details) ? $planManagement->wait_watch_details : null,['class'=>'form-control','placeholder'=>'Wait Watch Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','management_by_rx',!empty($management) ? false : true,['id'=>'management_by_rx','class'=>'plan-management','data-id'=>'management-by-rx-details'])}}
                                        <label for="management_by_rx">
                                            Management by Rx.
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 management-by-rx-details '.$management}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[management_by_rx_details]",!empty($planManagement->management_by_rx_details) ? $planManagement->management_by_rx_details : null,['class'=>'form-control','placeholder'=>'Management By Rx. Details'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 management-by-rx-details '.$management}}">
                                    <div class="form-group">
                                        {{Form::select("plan_of_management[management_by_rx_data][]",['1'=>'CC','2'=>'Ltz'],!empty($planManagement->management_by_rx_data) ? $planManagement->management_by_rx_data : null,['class'=>'form-control select-padding-0','title'=>'Management By Rx. Details','multiple'=>true])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','hyperstimulation_iui',!empty($hyperstimulation) ? false : true,['id'=>'hyperstimulation_iui','class'=>'plan-management','data-id'=>'hyperstimulation-iui-details'])}}
                                        <label for="hyperstimulation_iui">
                                            Controlled Overian Hyperstimulation With I.U.I
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 hyperstimulation-iui-details '.$hyperstimulation}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[hyperstimulation_iui_details]",!empty($planManagement->hyperstimulation_iui_details) ? $planManagement->hyperstimulation_iui_details : null,['class'=>'form-control','placeholder'=>'Controlled Overian Hyperstimulation With I.U.I'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 hyperstimulation-iui-details '.$hyperstimulation}}">
                                    <div class="form-group">
                                        {{Form::select("plan_of_management[hyperstimulation_iui_data][]",['1'=>'Only Medicine','2'=>'Medicine + Gonadotropins','3'=>'Only Gonadotropins'],!empty($planManagement->hyperstimulation_iui_data) ? $planManagement->hyperstimulation_iui_data : null,['class'=>'form-control select-padding-0','title'=>'Controlled Overian Hyperstimulation I.U.I Data','multiple'=>true])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','laproscopy',!empty($laproscopy) ? false : true,['id'=>'laproscopy','class'=>'plan-management','data-id'=>'laproscopy-data'])}}
                                        <label for="laproscopy">
                                            Laproscopy
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 laproscopy-data '.$laproscopy}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[laproscopy_details]",!empty($planManagement->laproscopy_details) ? $planManagement->laproscopy_details : null,['class'=>'form-control','placeholder'=>'Laproscopy Details'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 laproscopy-data '.$laproscopy}}">
                                    <div class="form-group">
                                        {{Form::select("plan_of_management[laproscopy_data][]",['1'=>'HSG','2'=>'Hystroscopy','3'=>'DHL','4'=>'Oher'],!empty($planManagement->laproscopy_data) ? $planManagement->laproscopy_data : null,['class'=>'form-control select-padding-0','title'=>'Laproscopy Data','multiple'=>true])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','ivf',!empty($ivf) ? false : true,['id'=>'ivf','class'=>'plan-management','data-id'=>'ivf-details'])}}
                                        <label for="ivf">
                                            I.V.F
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ivf-details '.$ivf}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[ivf_details]",!empty($planManagement->ivf_details) ? $planManagement->ivf_details : null,['class'=>'form-control','placeholder'=>'IVF Details'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ivf-details '.$ivf}}">
                                    <div class="form-group">
                                        {{Form::select("plan_of_management[ivf_data][]",['1'=>'Self','2'=>'OD','3'=>'ED'],!empty($planManagement->ivf_data) ? $planManagement->ivf_data : null,['class'=>'form-control select-padding-0','title'=>'I.V.F Data','multiple'=>true])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','male_factor',!empty($maleFactor) ? false : true,['id'=>'male_factor','class'=>'plan-management','data-id'=>'male-factor-data'])}}
                                        <label for="male_factor">
                                            Rx. Of Male Factor
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 male-factor-data '.$maleFactor}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[male_factor_details]",!empty($planManagement->male_factor_details) ? $planManagement->male_factor_details : null,['class'=>'form-control','placeholder'=>'Rx. Of Male Factor Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','reports',!empty($reports) ? false : true,['id'=>'reports','class'=>'plan-management','data-id'=>'reports-data'])}}
                                        <label for="reports">
                                            Reports
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 reports-data '.$reports}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[reports_details]",!empty($planManagement->reports_details) ? $planManagement->reports_details : null,['class'=>'form-control','placeholder'=>'Reports Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','induction_gonadotropins_cycle',!empty($inductionGonadotropinsCycle) ? false : true,['id'=>'induction_gonadotropins_cycle','class'=>'plan-management','data-id'=>'induction-gonadotropins-cycle-details'])}}
                                        <label for="induction_gonadotropins_cycle">
                                            Induction Gonadotropins Cycle
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 induction-gonadotropins-cycle-details '.$inductionGonadotropinsCycle}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[induction_gonadotropins_cycle_details]",!empty($planManagement->induction_gonadotropins_cycle_details) ? $planManagement->induction_gonadotropins_cycle_details : null,['class'=>'form-control','placeholder'=>'Induction Gonadotropins Cycle Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','other',!empty($other) ? false : true,['id'=>'plan_of_management_other','class'=>'plan-management','data-id'=>'plan_of_management_other_data'])}}
                                        <label for="plan_of_management_other">
                                            Other
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 plan_of_management_other_data '.$other}}">
                                    <div class="form-group">
                                        {{Form::text("plan_of_management[other_details]",!empty($planManagement->other_details) ? $planManagement->other_details : null,['class'=>'form-control','placeholder'=>'Other Details'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--12. Possible cause of infertility  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#possible_cause_of_infertility" href="#possible_cause_of_infertility" aria-expanded="false"
                                aria-controls="past-history">12. Possible Cause Of Infertility</a></h4>
                    </div>
                    <div id="possible_cause_of_infertility" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            @php
                                $infertilityTypeData = !empty($possibleCaseOfInfertility->infertility_type) ? $possibleCaseOfInfertility->infertility_type : [];
                            @endphp
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','ovarian',in_array('ovarian',$infertilityTypeData),['id'=>'ovarian'])}}
                                        <label for="ovarian">
                                            Ovarian
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','uterine',in_array('uterine',$infertilityTypeData),['id'=>'uterine'])}}
                                        <label for="uterine">
                                            Uterine
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','endometrial',in_array('endometrial',$infertilityTypeData),['id'=>'endometrial'])}}
                                        <label for="endometrial">
                                            Endometrial
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','tubal',in_array('tubal',$infertilityTypeData),['id'=>'tubal'])}}
                                        <label for="tubal">
                                            Tubal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','cervical',in_array('cervical',$infertilityTypeData),['id'=>'cervical'])}}
                                        <label for="cervical">
                                            Cervical
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','male',in_array('male',$infertilityTypeData),['id'=>'male'])}}
                                        <label for="male">
                                            Male
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','unexplained',in_array('unexplained',$infertilityTypeData),['id'=>'unexplained'])}}
                                        <label for="unexplained">
                                            UnExplained
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','coital',in_array('coital',$infertilityTypeData),['id'=>'coital'])}}
                                        <label for="coital">
                                            Coital
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','other',in_array('other',$infertilityTypeData),['id'=>'other'])}}
                                        <label for="other">
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        {{Form::textarea("possible_case_of_infertility[other]",!empty($possibleCaseOfInfertility->other) ? $possibleCaseOfInfertility->other : null,['class'=>'form-control no-resize','placeholder'=>'Other','rows'=>'5'])}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--13. Treatment history  -->
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                aria-controls="past-history">13. Treatment</a></h4>
                    </div>
                    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row treatment-data" id="t_data_1">
                                    <div class="col-md-2 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Select Medicine :
                                        </label>
                                    </div>
                                <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                    {{Form::select('treatment[medicinedata][]',$medicines,$medicineKey,['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co','placeholder'=>"Enter Medicine"])}}
                                </div>
                            </div>
                            <div class="page-loader-wrapper medicine-loader d-none">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
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
                                    $till_follow_up = (empty($row->no)) ? 'till-follow-up' : '';
                                    ?>
                                    <div class="{{'row mt-2 '.$notinject}}" data-id="{{$mId}}">
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
                                        <div class='col-md-1'>
                                            <div class='input-group'>
                                                <span class='input-group-addon'>Day :</span>
                                                {{Form::number('treatment['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
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
                            {{Form::hidden('old_medicine_data',!empty($medicineKey) ? implode(',',$medicineKey) : null,['class'=>'old-medicine-data'])}}
                        </div>
                    </div>
                </div>
                {{Form::hidden('next_date','',['class'=>'next-date-value'])}}
                {{Form::hidden('next_time','',['class'=>'next-time-value'])}}
            @endif
            {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
            @if($visitNo == 2)
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Seen By :
                        </label>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('seen_by_2',$hospitalDoctor,isset($iui) && !empty($iui->seen_by) ? $iui->seen_by : '',['class'=>'form-control select-padding-0 seen-by-2','placeholder'=>'Select Doctor'])}}
                        </div>
                        <span class="seen-by-error-2 text-danger mb-2"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-2">
                        <a href="{{URL::to('iui/extra-visit/'.encrypt($iui->patients_id))}}" class="btn btn-primary btn-ivf-report">Extra Visit</a>
                    </div>
                </div>
                @if($remark && !$iuiHistoryId)
                    <span class="remark-text">Remark: {{$remark}}</span>
                    <br>
                    <br>
                @endif
                {{--1 lmd date --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#lmp" href="#lmp" aria-expanded="false"
                            aria-controls="lmp">1. L.M.P</a></h4>
                        </div>
                    <div id="lmp" class="panel-collapse collapse lmp-tab" role="tabpanel"
                    aria-labelledby="headingThree_1">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                                    {{Form::text("data[lmp][date]",$lmddate,['class'=>'form-control lmd-date second-visit-lmd-date'])}}
                                </div>
                                <span class="form-error-msg lmp-date-msg"></span>
                            </div>
                                <span class="col-md-1 p-2 lmd-date-diff">{{isset($lmdDiff) ? $lmdDiff." Day" : null }}</span>
                                {{Form::hidden('data[lmp][lmp_date_diff]',$lmdDiff,['class'=>'lmd-date-diff-val'])}}
                                {{Form::hidden('visit',2,['class'=>'visit-value'])}}
                                {{Form::hidden('iui_history_id',$iuiHistoryId, ['id' => 'iui_history_id'])}}
                                {{-- {{ Form::hidden('iui_id', null, ['id' => 'iui_id']) }} --}}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 2.Co --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#co" href="#co" aria-expanded="false"
                                aria-controls="co">2. C/O</a></h4>
                    </div>
                    <div id="co" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        C/O :
                                    </label>
                                </div>
                                <div class="col-md-10 complain-multi">
                                    @php
                                        $cClass = $iuiHistoryId ? '' : 'complaint-data';
                                    @endphp
                                    {{Form::select('data[co_type][]',$complaints,!empty($historyCo) ? $historyCo : null,['class'=>'form-control co-value co_value_data '.$cClass,'placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'2'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--3 oe --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#o_e_second" href="#o_e_second" aria-expanded="false"
                                                aria-controls="o_e_second">3. O/E</a></h4>
                    </div>
                    <div id="o_e_second" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            @php
                                $vitlasClass = !empty($historyLmp->le->vitals_status) && $historyLmp->le->vitals_status == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[lmp][le][vitals_status]','yes',!empty($historyLmp->le->vitals_status) && $historyLmp->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'vitals_status','data-id'=>'vitals_status_data'])}}
                                        <label for="vitals_status">
                                            Vitals
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                        {{Form::text("data[lmp][le][bp]",!empty($historyLmp->le->bp) ? $historyLmp->le->bp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">MMHG</span>
                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                        {{Form::text("data[lmp][le][temp]",!empty($historyLmp->le->temp) ? $historyLmp->le->temp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                        {{Form::text("data[lmp][le][pulse]",!empty($historyLmp->le->pulse) ? $historyLmp->le->pulse : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">/ Min</span>
                            </div>
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
                                    $utType = 'd-none';
                                    if(!empty($historyOe->ut->ut_type) && $historyOe->ut->ut_type == 2){
                                        $utType = '';
                                    }
                                    $ovaryType = !empty($historyOe->ovary->ovary_type) ? $historyOe->ovary->ovary_type : [];
                                    $ovaryLeftType = !empty($historyOe->ovary->left->type) && $historyOe->ovary->left->type == 1 ? 'd-none' : null;
                                    $ovaryRightType = !empty($historyOe->ovary->right->type) && $historyOe->ovary->right->type == 1 ? 'd-none' : null;
                                    if(!$iuiHistoryId){
                                        $ovaryLeftType = 'd-none';
                                        $ovaryRightType = 'd-none';
                                    }
                                @endphp
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select("data[oe][ut][ut_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ut->ut_type) ? $historyOe->ut->ut_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ut-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ut-type '.$utType}}">
                                    <div class="form-group">
                                        {{Form::text("data[oe][ut][details]",!empty($historyOe->ut->details) ? $historyOe->ut->details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $leftData = in_array('left',$ovaryType) ? null : 'd-none';
                                $rightData = in_array('right',$ovaryType) ? null : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        Ovary :
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
                                <div class="{{'col-md-10 oe-right-details'}}">
                                    <div class="row">
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::select("data[oe][ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ovary->right->type) ? $historyOe->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                            </div>
                                        </div> --}}
                                        <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type'}} ">
                                            {{Form::select("data[oe][ovary][right][details][]",$rightOvaryData,!empty($historyOe->ovary->right->details) ? $historyOe->ovary->right->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_right_details',
                                                'data-id' => '2',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">AFCS : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][right][afcs]",!empty($historyOe->ovary->right->afcs) ? $historyOe->ovary->right->afcs : null,['class'=>'form-control second-right-ovary-data-text'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="javascript:void(0)" class="second-right-ovary-data overy-popup" data-class='second-right-ovary-data'>Keyboard</a>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Residual follicale : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][right][residual_follicale]",!empty($historyOe->ovary->right->residual_follicale) ? $historyOe->ovary->right->residual_follicale : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="{{ 'row oe-right-details'}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-7 complain-multi ovary-right-abnormal-type ml-5">
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
                                <div class="{{'col-md-10 oe-left-details'}}">
                                    <div class="row">
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::select("data[oe][ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ovary->left->type) ? $historyOe->ovary->left->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                            </div>
                                        </div> --}}
                                        <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type'}} ">
                                            {{Form::select("data[oe][ovary][left][details][]",$leftOvaryData,!empty($historyOe->ovary->left->details) ? $historyOe->ovary->left->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_left_details',
                                                'multiple'=>true,
                                                'data-id' => '2',
                                            ])}}
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="input-group">
                                                <span class="input-group-addon">AFCS : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][left][afcs]",!empty($historyOe->ovary->left->afcs) ? $historyOe->ovary->left->afcs : null,['class'=>'form-control second-left-ovary-data-text'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="javascript:void(0)" class="second-left-ovary-data overy-popup" data-class='second-left-ovary-data'>Keyboard</a>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Residual follicale : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][left][residual_follicale]",!empty($historyOe->ovary->left->residual_follicale) ? $historyOe->ovary->left->residual_follicale : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="{{ 'row oe-left-details'}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-7 complain-multi ovary-left-abnormal-type ml-5">
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

                            <br>
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
                            @php
                                $pStatus = !empty($historyOe->p_s->type) && $historyOe->p_s->type == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[oe][p_s][type]','yes',!empty($pStatus) ? false : true,['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                                        <label for="ps_status">
                                            P/S :
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 ps-details '.$pStatus}}">
                                    <div class="form-group">
                                        {{Form::text("data[oe][p_s][details]",!empty($historyOe->p_s->details) ? $historyOe->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 4 plan --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#paln" href="#paln" aria-expanded="false"
                                                aria-controls="paln">4. Plan</a></h4>
                    </div>
                    <div id="paln" class="panel-collapse collapse plan-tab" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- @if (!empty($historyPlan->plan_type))
                                        <div class="col-md-8 complain-multi mb-3">
                                            Plan Type:
                                            {{Form::label('plan-type',!empty($historyPlan->plan_type) ? $historyPlan->plan_type : null)}}
                                            {{Form::hidden('data[plan][plan_type]', !empty($historyPlan->plan_type) ? $historyPlan->plan_type : null) }}
                                        </div>
                                    @else --}}
                                        <div class="form-group">
                                            {{Form::select("data[plan][plan_type]",$planType,!empty($historyPlan->plan_type) ? $historyPlan->plan_type : null,['class'=>'form-control select-padding-0 plan-type','placeholder'=>'Select Plan Type','data-live-search'=>"true"])}}
                                        </div>
                                        <span class="form-error-msg plan-type-msg"></span>
                                    {{-- @endif --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::text("data[plan][other]",!empty($historyPlan->other) ? $historyPlan->other : null,['class'=>'form-control','placeholder'=>'Other'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Inducing Agent :
                                    </label>
                                </div>
                                {{-- @if (!empty($historyPlan->agenet))
                                    <div class="col-md-8 complain-multi mb-3">
                                        {{Form::select('data[plan][agenet][]',!empty($historyPlan->agenet) ? $historyPlan->agenet : null,range(0,count($historyPlan->agenet)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                                    </div>
                                @else --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::select("data[plan][agenet][]",!empty($historyPlan->plan_type) ? $planData : [], !empty($historyPlan->agenet) ? $historyPlan->agenet : null,['class'=>'form-control select-padding-0 plan-data plan-follow-up','title'=>'Select Plan','data-live-search'=>"true"])}}
                                        </div>
                                        <span class="plan-data-error form-error-msg"></span>
                                    </div>
                                {{-- @endif --}}
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("data[plan][agenet][]",!empty($historyPlan->plan_type) ? $planData : [],!empty($historyPlan->agenet) ? $historyPlan->agenet : null,['class'=>'form-control select-padding-0 plan-data plan-follow-up','multiple','title'=>'Select Plan','data-live-search'=>"true", !empty($historyPlan->agenet) ? 'disabled' : ''])}}
                                    </div>
                                </div> --}}
                                @php
                                $agentDataValue = [];
                                $dateAndInjectionData = [];
                                    if(!empty($historyInducingDate)){
                                        
                                        $dateAndInjectionData[] = (array)$historyInducingDate;
                                    } 
                                    foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                    {
                                        array_push($agentDataValue,$valueData->date);
                                    }
                                @endphp
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Inducing Date :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::text("data[inducing][date]",implode(',',$agentDataValue),['class'=>'form-control datetime second-visit-inducing','placeholder'=>'Inducing Agent Date'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Follow Up: &nbsp;</span>
                                        @if(!empty($historyPlan->follow_up))
                                            {{Form::text("follow_up",!empty($historyPlan->follow_up) ? \Carbon\Carbon::parse($historyPlan->follow_up)->format('D d M Y') : null,['class'=>'form-control datetimepicker follow-up-date'])}}
                                            {{Form::hidden('data[plan][follow_up]',$historyPlan->follow_up)}}
                                            {{Form::hidden('data[new_follow_up]',$historyPlan->follow_up)}}
                                        @else
                                            {{Form::text("data[plan][follow_up]",!empty($historyPlan->follow_up) ? \Carbon\Carbon::parse($historyPlan->follow_up)->format('D d M Y') : null,['class'=>'form-control datetimepicker follow-up-date next-date'])}}
                                        @endif
                                    </div>
                                    <span class="follow-date-msg form-error-msg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            I.U.I :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[iui]",'yes',!empty($historyData->iui) && $historyData->iui == 'yes' ? true : false,[
                                'id'=>'iui_status_yes',
                                !empty($historyData->iui) && $historyData->iui == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="iui_status_yes">
                                Yes
                            </label>
                            {{Form::radio("data[iui]",'no',!empty($historyData->iui) && $historyData->iui == 'no' ? true : false,[
                                'id'=>'iui_status_no',
                                !empty($historyData->iui) && $historyData->iui == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="iui_status_no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                {{-- 5 .treatment --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                aria-controls="past-history">5. Treatment</a></h4>
                    </div>
                    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row treatment-data" id="t_data_1">
                                    <div class="col-md-2 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Select Medicine :
                                        </label>
                                    </div>
                                <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                    {{Form::select('data[treatment][medicinedata][]',$medicines,$historyMedicineKey,['id'=>'treatment-medicine','class'=>'form-control medicine','placeholder'=>"Enter medicine",'data-type'=>'iui-cycle'])}}
                                </div>
                            </div>
                            <div class="page-loader-wrapper medicine-loader d-none">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
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
                            {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            I.V.F :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[ivf]",'yes',!empty($historyData->ivf) && $historyData->ivf == 'yes' ? true : false,[
                                'id'=>'ivf_status_yes',
                                !empty($historyData->ivf) && $historyData->ivf == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="ivf_status_yes">
                                Yes
                            </label>
                            {{Form::radio("data[ivf]",'no',!empty($historyData->ivf) && $historyData->ivf == 'no' ? true : false,[
                                'id'=>'ivf_status_no',
                                !empty($historyData->ivf) && $historyData->ivf == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="ivf_status_no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                @php
                $bloodReportClass = !empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'yes' ? true : false;
                $bloodReportClassName = $bloodReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Blood Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[blood_report][type]",'yes',$bloodReportClass,['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[blood_report][type]",'no',!empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'no' ? true : false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 blood-type '.$bloodReportClassName}}">
                        <div class="data-blood-images"></div>
                    </div>
                </div>
                @php
                        $hsaReportClass = !empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'yes' ? true : false;
                        $hsaReportClassName = $hsaReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HSA Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hsa_report][type]",'yes',$hsaReportClass,['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[hsa_report][type]",'no',!empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'no' ? true : false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 hsa-type '.$hsaReportClassName}}">
                        <div class="data-hsa-images"></div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("data[investigation_extra]",isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? $historyData->investigation_extra : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <!-- {{-- 4 .Remark --}} -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            {{Form::textarea("data[remark]",!empty($historyData->remark) ? $historyData->remark : '',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{Form::textarea("data[pt_remark]",!empty($historyData->pt_remark) ? $historyData->pt_remark : '',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'5'])}}
                        </div>
                    </div>
                </div>
            @endif
            @if($visitNo == 3)
                <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                @if($remark && !$iuiHistoryId)
                    <span class="remark-text m-0">Remark: {{$remark}}</span>
                    <br>
                    <br>
                @endif
                {{Form::hidden('visit',3, ['id' => 'visit','class'=>'visit-value'])}}
                {{Form::hidden('iui_history_id',$iuiHistoryId, ['id' => 'iui_history_id'])}}
                {{-- {{Form::hidden('iui_history_id',$iuiHistoryId)}} --}}
                <div class="row">
                    <span class="col-md-1 visit-lable">Patients Name :</span>
                    <span class="col-md-2 visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['name']}}</span>
                    <span class="col-md-1 visit-lable">Age : &nbsp<span class="col-md-2 visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['age']}}</span></span>
                </div>
                <br>
                <div class="iui-history-visit-data">
                    {{-- lmp table --}}
                    <h5>L.M.P</h5>
                    <table cellspacing="0" cellpadding="0" class="table iui-table-visit-data  m-b-0">
                        <tbody>
                            <tr><th class="visit-lable width-iui">L.M.P Date</th><td class="visit-lable-value width-iui">{{!empty($iuiSecondVisitData->lmp->date) ? $iuiSecondVisitData->lmp->date : null}}</td>
                            <th class="visit-lable width-iui">| Diff Day</th><td class="visit-lable-value width-iui" colspan="12">{{!empty($iuiSecondVisitData->lmp->lmp_date_diff) ?  $iuiSecondVisitData->lmp->lmp_date_diff : 0}} Days</td></tr>
                        </tbody>
                    </table>
                    <br>

                    {{-- oe table --}}
                    <h5>O/E</h5>
                    @php
                        $ovaryType = !empty($iuiSecondVisitData->oe->ovary->ovary_type) ? $iuiSecondVisitData->oe->ovary->ovary_type : [];
                        $leftAbnormal = 0;
                        $rightAbnormal = 0;
                        if(!empty($iuiSecondVisitData->oe->ovary->left->type) && $iuiSecondVisitData->oe->ovary->left->type == 2){
                            $leftAbnormal = 1;
                        }
                        if(!empty($iuiSecondVisitData->oe->ovary->right->type) && $iuiSecondVisitData->oe->ovary->right->type == 2){
                            $rightAbnormal = 1;
                        }
                    @endphp
                    <table cellspacing="0" cellpadding="0" class="table iui-table-visit-data m-b-0">
                        <tbody>
                            <tr>
                                <th class="visit-lable">OE :</th>
                                <td class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->oe_type->type) ? ucfirst($iuiSecondVisitData->oe->oe_type->type) : null}}</td>
                                <th class="visit-lable">| UT :</th>
                                <td class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->ut->ut_type) && $iuiSecondVisitData->oe->ut->ut_type == 1 ? 'Normal' : 'Abnormal'}}</td>

                                <th class="visit-lable">| UT Abnormal Details :</th>
                                <td class="visit-lable-value">
                                    @if(!empty($iuiSecondVisitData->oe->ut->ut_type) && $iuiSecondVisitData->oe->ut->ut_type == 2)
                                        {{!empty($iuiSecondVisitData->oe->ut->details) ? $iuiSecondVisitData->oe->ut->details : null}}
                                    @endif
                                </td>
                                <th class="visit-lable">| Ovary :</th>
                                <td class="visit-lable-value">
                                    @if(!empty($ovaryType))
                                        {{implode(',',$ovaryType)}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>OE Right Data</th>
                                <th class="visit-lable"> OE Right Type:</th>
                                <td class="visit-lable-value">{{$rightAbnormal == 1 ? 'Abnormal' : 'Normal'}}</td>
                                <th class="visit-lable">| Right AFCS</th>
                                <td class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->ovary->right->afcs) ? $iuiSecondVisitData->oe->ovary->right->afcs : null}}</td>
                            </tr>
                            <tr>
                                <th>OE Left Data</th>
                                <th class="visit-lable"> OE left Type:</th>
                                <td class="visit-lable-value">{{$leftAbnormal == 1 ? 'Abnormal' : 'Normal'}}</td>
                                <th class="visit-lable">| Left AFCS</th>
                                <td class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->ovary->left->afcs) ? $iuiSecondVisitData->oe->ovary->left->afcs : null}}</td>
                            </tr>
                            <tr>
                                <th class="visit-lable">Endometrial Cavity</th>
                                <td class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->endometrial_cavity->cavity) ? $iuiSecondVisitData->oe->endometrial_cavity->cavity : null}}</td>
                                <th class="visit-lable">| Size</th>
                                <td class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->endometrial_cavity->size) ? $iuiSecondVisitData->oe->endometrial_cavity->size : null}} M</td>
                            </tr>
                        </tbody>
                    </table>
                    @php
                        $rightData = [];
                        $leftData = [];
                        $agentData = [];
                        if(isset($iuiSecondVisitData->oe->ovary->right->updated_details) && !empty($iuiSecondVisitData->oe->ovary->right->updated_details)) {
                            $rightData = $iuiSecondVisitData->oe->ovary->right->updated_details;
                        }
                        if(isset($iuiSecondVisitData->oe->ovary->left->updated_details) && !empty($iuiSecondVisitData->oe->ovary->left->updated_details)){
                            $leftData = $iuiSecondVisitData->oe->ovary->left->updated_details;
                        }
                        if(!empty($iuiSecondVisitData->plan->agenet)){
                            $agentData = $iuiSecondVisitData->plan->agenet;
                        }
                    @endphp
                    <div class="row treatment-data" id="t_data_1">
                        <div class="col-md-2 pr-0">
                            <label class="vertical-form-label pr-0">
                                Right Abnormal Details:
                            </label>
                        </div>
                        <div class="col-md-8 complain-multi mb-1">
                            @if(isset($iuiSecondVisitData->oe->ovary->right->updated_details))
                                {{Form::select('right_abnormal_details',$rightData,range(0,count($rightData)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                            @endif
                        </div>
                    </div>
                    <div class="row treatment-data" id="t_data_1">
                        <div class="col-md-2 pr-0">
                            <label class="vertical-form-label pr-0">
                                Left Abnormal Details :
                            </label>
                        </div>
                        <div class="col-md-8 complain-multi mb-1">
                            @if(isset($iuiSecondVisitData->oe->ovary->left->updated_details))
                                {{Form::select('left_abnormal_details',$leftData,range(0,count($leftData)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                            @endif
                        </div>
                    </div>
                    {{-- plan table --}}
                    <h5>Plan</h5>
                    <table cellspacing="0" cellpadding="0" class='table m-b-0 table-layout'>
                        <tbody>
                            <tr>
                                <th class="visit-lable" class="seperator">
                                    Plan Type :
                                </th>
                                <td class="visit-lable-value">
                                    {{!empty($iuiSecondVisitData->plan->plan_type) ? $iuiSecondVisitData->plan->plan_type : null}}
                                </td>
                                <th class="visit-lable" class="seperator">
                                |  Other :
                                </th>
                                <td class="visit-lable-value">
                                    {{!empty($iuiSecondVisitData->plan->other) ? $iuiSecondVisitData->plan->other : null}}
                                </td>

                                <th class="visit-lable" class="seperator">
                                | Follow Up :
                                </th>
                                <td class="visit-lable-value">
                                    {{!empty($iuiSecondVisitData->plan->follow_up) ? \Carbon\Carbon::parse($iuiSecondVisitData->plan->follow_up)->format('D d M Y') : null}}
                                </td>
                            </tr>
                        <tbody>
                    </table>
                    <hr>
                        <div class="row treatment-data" id="t_data_1">
                            <div class="col-md-1 pr-0">
                                <label class="vertical-form-label pr-0">
                                    Inducing Agent:
                                </label>
                            </div>
                            <div class="col-md-8 complain-multi mb-3">
                                {{Form::select('inducing_agent',$agentData,range(0,count($agentData)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                            </div>
                            </div>
                            {{-- <input type="text" class="date-data" />   --}}
                    {{-- treatment table --}}
                    <h5>Treatment</h5>
                    @php
                        $historyTreatmentView = null;
                        if(!empty($iuiSecondVisitData->treatment) || !empty($iuiSecondVisitData->old_treatment)){
                            $historyTreatmentView = !empty($iuiSecondVisitData->treatment) ? $iuiSecondVisitData->treatment : $iuiSecondVisitData->old_treatment;
                        }
                    @endphp
                    @if($historyTreatmentView)
                        <table cellspacing="0" cellpadding="0" class="table m-b-0">
                            <tbody>
                                @php
                                    $old_dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>'Weekly / 1','6'=>'Weekly / 2','7'=>'Stat','8'=>'SOS'];
                                    $old_medicine_time = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                                    unset($historyTreatmentView->medicinedata);
                                @endphp
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
                                        <br>
                                    @endforeach
                                    
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
                <br>
                <br>
                <div class="row mt-3">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Seen By :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('seen_by_3',$hospitalDoctor,isset($iui) && !empty($iui->seen_by) ? $iui->seen_by : '',['class'=>'form-control select-padding-0 seen-by-3','placeholder'=>'Select Doctor'])}}
                        </div>
                        <span class="seen-by-error-3 text-danger mb-2"></span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                            {{Form::text("data[lmp][date]",$lmddate,['class'=>'form-control lmd-date'])}}
                        </div>
                    </div>
                    @php
                        $lmpDataDiff = isset($lmdDiff) ? $lmdDiff : 0;
                        if(!isset($lmpDataDiff)){
                            $lmpDataDiff = !isset($iuiSecondVisitData->lmp->lmp_date_diff) ? $iuiSecondVisitData->lmp->lmp_date_diff : 0;
                        }
                    @endphp
                    {{Form::hidden('data[lmp][lmp_date_diff]',$lmpDataDiff,['class'=>'lmd-date-diff-val'])}}
                    <span class="col-md-1 p-2 lmd-date-diff">{{$lmpDataDiff ." Day"}}</span>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Cycle No. : &nbsp;</span>
                            {{Form::text("cycle",$cycleNo,['class'=>'form-control','placeholder'=>'Cycle No','disabled'])}}
                        </div>
                    </div>
                    <span class="form-error-msg plan-type-msg"></span>
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Inducing Agent :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select("data[plan][inducing_agent][]",$inducingInjectionData,!empty($historyPlan->inducing_agent) ? $historyPlan->inducing_agent : null,[
                                'class'=>'form-control select-padding-0 plan-data',
                                'multiple',
                                'title'=>'Select Injection',
                                'data-live-search'=>"true",
                                'id' => 'plan-data'
                            ])}}
                        </div>
                    </div>
                </div>
                <br>
                <br>
                @if(!$iuiHistoryId)
                
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Inducing Date :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::text("data[inducing][date]",'',['class'=>'form-control datetime second-visit-inducing','placeholder'=>'Inducing Agent Date','autocomplete'=>'off'])}}
                        </div>
                    </div>
                    {{Form::hidden('data[last_appointment_date]',!empty($lastAppointmentData->date) ? $lastAppointmentData->date : null)}}
                @else
                    @php
                        $agentDataValue = [];
                        $dateAndInjectionData = [];
                        if(!empty($historyInducingDate)){

                            $dateAndInjectionData[] = (array)$historyInducingDate;
                        } 
                        foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                        {
                            array_push($agentDataValue,$valueData->date);
                        }
                    @endphp
                    <div class="col-md-2">
                        <label class="vertical-form-label pr-0">
                            Inducing Date :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::text("data[inducing][date]",!empty($agentDataValue) ? implode(',',$agentDataValue) : '',['class'=>'form-control datetime second-visit-inducing','placeholder'=>'Inducing Agent Date'])}}
                        </div>
                    </div>
                    {{Form::hidden('data[last_appointment_date]',!empty($historyData->last_appointment_date) ? $historyData->last_appointment_date : null)}}
                @endif
                @php
                    $vitlasClass = !empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[le][vitals_status]','yes',!empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'vitals_status','data-id'=>'vitals_status_data'])}}
                            <label for="vitals_status">
                                Vitals
                            </label>
                        </div>
                    </div>
                    {{-- <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Vitals :
                        </label>
                    </div> --}}
                    <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                        <div class="input-group">
                            <span class="input-group-addon">B.P : &nbsp;</span>
                            {{Form::text("data[le][bp]",!empty($historyData->le->bp) ? $historyData->le->bp : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">MMHG</span>
                    <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                        <div class="input-group">
                            <span class="input-group-addon">Temp : &nbsp;</span>
                            {{Form::text("data[le][temp]",!empty($historyData->le->temp) ? $historyData->le->temp : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                        <div class="input-group">
                            <span class="input-group-addon">Pulse : &nbsp;</span>
                            {{Form::text("data[le][pulse]",!empty($historyData->le->pulse) ? $historyData->le->pulse : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">/ Min</span>
                </div>
                <br>
                {{-- plan --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{Form::select("data[cycle][type]",[1=>'Natural Cycle',2=>'Tablets',3=>'Gonadotropin',4=>'Tablets & Injection'],(!empty($historyData->cycle->type) ? $historyData->cycle->type : (!empty($thirdDescription->cycle->type) ? $thirdDescription->cycle->type : null)) ,['class'=>'form-control select-padding-0','placeholder'=>'Select Cycle Type'])}}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-addon">Endometrial Thickness : &nbsp;</span>
                            {{Form::text("data[endometrial][type]",!empty($historyData->endometrial->type) ? $historyData->endometrial->type : null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                        </div>
                    </div>
                    <span class="col-md-1 p-2">MM</span>
                </div>
                <br>
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
                        if(!$iuiHistoryId){
                            $iuiStatus = 'd-none';
                            $hcgType = 'd-none';
                        }
                    @endphp
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[ovary][ovary_status][]','right',in_array('right',$ovaryType),['class'=>'plan-management','id'=>'oe_right','data-id'=>'oe-right-details'])}}
                            <label for="oe_right">
                                Right
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::text("data[ovary][ovary_type][right][details]",!empty($historyData->ovary->ovary_type->right->details) ? $historyData->ovary->ovary_type->right->details : null,['class'=>'form-control right-ovary-data-text','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="col-md-1 mt-2">
                        <a href="javascript:void(0)" class="right-ovary-data overy-popup" data-class='right-ovary-data'>Keyboard</a>
                    </div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[ovary][ovary_status][]','left',in_array('left',$ovaryType),['id'=>'oe_left','data-type'=>1])}}
                            <label for="oe_left">
                                Left
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::text("data[ovary][ovary_type][left][details]",!empty($historyData->ovary->ovary_type->left->details) ? $historyData->ovary->ovary_type->left->details : null,['class'=>'form-control left-ovary-data-text','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="col-md-1 mt-2">
                        <a href="javascript:void(0)" class="left-ovary-data overy-popup" data-class='left-ovary-data'>Keyboard</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HCG
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hcg][type]",'yes',!empty($hcgType) ? false : true,['id'=>'hcg_yes','class'=>'hcg-type-value'])}}
                            <label for="hcg_yes">
                                Yes
                            </label>
                            {{Form::radio("data[hcg][type]",'no',!empty($hcgType) ? true : false,['id'=>'hcg_no','class'=>'hcg-type-value'])}}
                            <label for="hcg_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-1 hcg-type pr-0 '.$hcgType}}">
                        <label class="vertical-form-label pr-0">
                            Injection :
                        </label>
                    </div>
                    <div class="{{'col-md-3 hcg-type '.$hcgType}}">
                        <div class="form-group">
                            {{Form::select("data[hcg][injection][data][]",[1=>'HCG 5000',2=>'HCG 10000',3=>'INJ 2 DECA',4=>'INJ 1 DECA',5=>'INJ Pitocin'],!empty($historyData->hcg->injection->data) ? $historyData->hcg->injection->data : null,['class'=>'form-control select-padding-0 hsg-injection','multiple','title'=>'Select Injection'])}}
                        </div>
                        <span class="hsg-injection-type-msg form-error-msg"></span>
                    </div>
                    <div class="{{'col-md-1 hcg-type pr-0 '.$hcgType}}">
                        <label class="vertical-form-label pr-0">
                            I.U.I :
                        </label>
                    </div>
                    <div class="{{'col-sm-2 hcg-type '.$hcgType}}">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hcg][iui][status]",'yes',!empty($iuiStatus) ? false : true,['id'=>'hcg_iui_yes','class'=>'iui-yes-no-status','data-type'=>'hcg-iui-type'])}}
                            <label for="hcg_iui_yes">
                                Yes
                            </label>
                            {{Form::radio("data[hcg][iui][status]",'no',!empty($iuiStatus) ? true : false,['id'=>'hcg_iui_no','class'=>'iui-yes-no-status','data-type'=>'hcg-iui-type'])}}
                            <label for="hcg_iui_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-2 hcg-iui-type '.$iuiStatus.' '.$hcgType}}">
                        <div class="form-group">
                            {{Form::select("data[hcg][iui][type]",['1'=>'Husband','2'=>'Donar','3'=>'Both'],!empty($historyData->hcg->iui->type) ? $historyData->hcg->iui->type : null,['class'=>'form-control select-padding-0', 'id'=>'hcg-iui-type', 'title'=>'Select Type'])}}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="{{'col-md-4 col-sm-12 hcg-type pr-0 '.$hcgType}}">
                        <div class="input-group">
                            <span class="input-group-addon">Date : &nbsp;</span>
                            {{Form::text("data[hcg_date]",!empty($historyData->hcg_date) ? \Carbon\Carbon::parse($historyData->hcg_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker hcg-date'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-4 col-sm-12 hcg-type pr-0 '.$hcgType}}">
                        <div class="input-group">
                            <span class="input-group-addon">HCG Time : &nbsp;</span>
                            {{ Form::text('data[hcg][time]', !empty($historyData->hcg->time) ? \Carbon\Carbon::parse($historyData->hcg->time)->format('g:i a') : \Carbon\Carbon::now()->format('g:i a'), ['class'=>'form-control timepicker time hcg-time'])}}
                        </div>
                        <span class="hcg_time form-error-msg"></span>
                    </div>
                    {{-- <div class="{{'col-md-4 col-sm-12 hcg-iui-type '.$iuiStatus.' '.$hcgType}}">
                        <div class="input-group">
                            <span class="input-group-addon">IUI Time : &nbsp;</span>
                            {{ Form::text('data[hcg][iui][time]', !empty($historyData->hcg->iui->time) ? \Carbon\Carbon::parse($historyData->hcg->iui->time)->format('g:i a') : \Carbon\Carbon::now()->format('g:i a'), ['class'=>'form-control timepicker time'])}}
                        </div>
                        <span class="iui_time form-error-msg"></span>
                    </div> --}}
                </div>
                <br>
                @php
                    $ovalution = 'no';
                    if(!empty($historyData->ovalution) && $historyData->ovalution == 'yes'){
                        $ovalution = 'yes';
                    }
                    $hcgIuiDate =  $ovalution == 'yes' || !empty($historyData->follow_up) ? '' : 'hcg-iui-date';
                @endphp
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">No.Follicle : &nbsp;</span>
                            {{Form::number("data[no_follicle]",!empty($historyData->no_follicle) ? $historyData->no_follicle : null,['class'=>'form-control follicle', $ovalution == 'yes' ? 'disabled' : null])}}
                            @if($ovalution == 'yes')
                                {{Form::hidden('data[no_follicle]',!empty($historyData->no_follicle) ? $historyData->no_follicle : null)}}
                                {{Form::hidden('data[ovalution]',!empty($historyData->ovalution) ? $historyData->ovalution : null)}}
                                {{Form::hidden('data[follow_up]',!empty($historyData->follow_up) ? \Carbon\Carbon::parse($historyData->follow_up)->format('D d M Y') : null)}}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Ovalution :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[ovalution]",'yes', $ovalution == 'yes' ? true : false,['class'=>'ovalution-type','id'=>'ovalution_yes','disabled'])}}
                            <label for="ovalution_yes">
                                Yes
                            </label>
                            {{Form::radio("data[ovalution]",'no',!empty($historyData->ovalution) && $historyData->ovalution == 'no' ? true : false,['class'=>'ovalution-type','id'=>'ovalution_no',$ovalution == 'yes' ? 'disabled' : null])}}
                            <label for="ovalution_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">Follow Up: &nbsp;</span>
                            @if(!empty($historyData->follow_up))
                                {{Form::hidden('data[new_follow_up]',$historyData->follow_up)}}
                            @endif
                            {{Form::text("data[follow_up]",!empty($historyData->follow_up) ? \Carbon\Carbon::parse($historyData->follow_up)->format('D d M Y') : \Carbon\Carbon::now()->addHours(35)->format('D d M Y'),['class'=>'form-control datetimepicker follow-up-date next-date '.$hcgIuiDate,$ovalution == 'yes' || !empty($historyData->follow_up) ? 'disabled' : null])}}
                        </div>
                        <span class="follow-date-msg form-error-msg"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            I.V.F :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[ivf]",'yes',!empty($historyData->ivf) && $historyData->ivf == 'yes' ? true : false,[
                                'id'=>'ivf_status_yes',
                                !empty($historyData->ivf) && $historyData->ivf == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="ivf_status_yes">
                                Yes
                            </label>
                            {{Form::radio("data[ivf]",'no',!empty($historyData->ivf) && $historyData->ivf == 'no' ? true : false,[
                                'id'=>'ivf_status_no',
                                !empty($historyData->ivf) && $historyData->ivf == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="ivf_status_no">
                                No
                            </label>
                        </div>
                    </div>
                    @php
                        $pStatus = !empty($historyData->p_s->type) && $historyData->p_s->type == 'yes' ? '' : 'd-none';
                    @endphp
                    <div class="col-md-1 pr-0">
                        <div class="checkbox">
                            {{Form::checkbox('data[p_s][type]','yes',!empty($pStatus) ? false : true,['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                            <label for="ps_status">
                                P/S :
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 ps-details '.$pStatus}}">
                        <div class="form-group">
                            {{Form::text("data[p_s][details]",!empty($historyData->p_s->details) ? $historyData->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            C/O :
                        </label>
                    </div>
                    @php
                        $cClass = $iuiHistoryId ? '' : 'complaint-data';
                    @endphp
                    <div class="col-md-10 complain-multi">
                        {{Form::select('data[co_type][]',$complaints,!empty($historyData->co_type) ? $historyData->co_type : null,['class'=>'form-control co-value co_value_data '.$cClass,'placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'2'])}}
                    </div>
                </div>
                <br>
                <div class="row treatment-data" id="t_data_1">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Select Medicine :
                        </label>
                    </div>
                    <div class="col-md-8 complain-multi mb-3 medicine-picker">
                        {{Form::select('data[treatment][medicinedata][]',$medicines,$historyMedicineKey,['id'=>'treatment-medicine','class'=>'form-control medicine','placeholder'=>"Enter medicine name",'data-type'=>'iui-cycle'])}}
                    </div>
                </div>
                <div class="page-loader-wrapper medicine-loader d-none">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
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
                {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}}
                {{-- {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}} --}}
                     <!-- {{-- 4 .Remark --}} -->
                     @php
                $bloodReportClass = !empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'yes' ? true : false;
                $bloodReportClassName = $bloodReportClass ? '' : 'd-none';
                $usgReportClass = !empty($historyData->usg) && !empty($historyData->usg->type) && $historyData->usg->type == 'yes' ? true : false;
                $usgReportClassName = $usgReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Blood Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[blood_report][type]",'yes',$bloodReportClass,['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[blood_report][type]",'no',!empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'no' ? true : false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 blood-type '.$bloodReportClassName}}">
                        <div class="data-blood-images"></div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            USG Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[usg][type]",'yes',$usgReportClass,['id'=>'blood_type_yes','class'=>'usg-type iui-yes-no-status','data-type'=>'usg-type'])}}
                            <label for="usg_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[usg][type]",'no',!empty($historyData->usg) && !empty($historyData->usg->type) && $historyData->usg->type == 'no' ? true : false,['id'=>'usg_type_no','class'=>'usg-type iui-yes-no-status','data-type'=>'usg-type'])}}
                            <label for="usg_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 blood-type '.$usgReportClassName}}">
                        <div class="data-usg-images"></div>
                    </div>
                </div>     
                @php
                        $hsaReportClass = !empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'yes' ? true : false;
                        $hsaReportClassName = $hsaReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HSA Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hsa_report][type]",'yes',$hsaReportClass,['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[hsa_report][type]",'no',!empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'no' ? true : false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 hsa-type '.$hsaReportClassName}}">
                        <div class="data-hsa-images"></div>
                    </div>
                    
                </div>  
                <div class="row">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("data[investigation_extra]",isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? $historyData->investigation_extra : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            {{Form::textarea("data[remark]",!empty($historyData->remark) ? $historyData->remark : '',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{Form::textarea("data[pt_remark]",!empty($historyData->pt_remark) ? $historyData->pt_remark : '',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'5'])}}
                        </div>
                    </div>
                </div>
            @endif
            
            @if($visitNo == 4)
                <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                @if($remark && !$iuiHistoryId)
                    <span class="remark-text m-0">Remark: {{$remark}}</span>
                    <br>
                    <br>
                @endif
                {{-- {{Form::hidden('iui_history_id',$iuiHistoryId)}} --}}
                {{Form::hidden('iui_history_id',$iuiHistoryId, ['id' => 'iui_history_id'])}}
                {{Form::hidden('visit',4,['class'=>'visit-value'])}}
                <div class="row">
                    <span class="col-md-2 visit-lable">Patients Name :</span>
                    <span class="col-md-4 visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['name']}}</span>
                    <span class="col-md-2 visit-lable">Age : &nbsp<span class="col-md-2 visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['age']}}</span></span>
                </div>
                <br />
                <div class="row">
                    <span class="col-md-2 visit-lable">No. Follicle :</span>
                    <span class="col-md-4 visit-lable-value">{{$follicleString}}</span>
                    <span class="col-md-2 visit-lable">Plan : &nbsp<span class="col-md-2 visit-lable-value">{{$planOfSecondVisit}}</span></span>
                </div>
                <br />
                <div class="row">
                    <span class="col-md-2 visit-lable">Cycle No :</span>
                    <span class="col-md-4 visit-lable-value">{{$cycleNo}}</span>
                </div>
                <br />
                <br />
                <div class="row mt-3">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Seen By :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('seen_by_4',$hospitalDoctor,isset($iui) && !empty($iui->seen_by) ? $iui->seen_by : '',['class'=>'form-control select-padding-0 seen-by-4','placeholder'=>'Select Doctor'])}}
                        </div>
                        <span class="seen-by-error-4 text-danger mb-2"></span>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#h_o" href="#h_o" aria-expanded="false"
                                aria-controls="past-history">1. H/O</a></h4>
                    </div>
                    <div id="h_o" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                       Follow Up Case: 
                                    </label>
                                </div>
                                <div class="col-md-4 child-naturally">
                                    <div class="form-group">
                                        {{Form::select("data[ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI'],!empty($historyData->ho_type) ? $historyData->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type p-ho-type','placeholder'=>'Select Follow Up case of'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        UPT :
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[upt_type]",'positive',!empty($historyData->upt_type) && $historyData->upt_type == 'positive' ? true : false,[
                                            'id'=>'positive',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="positive">
                                            Positive
                                        </label>

                                        {{Form::radio("data[upt_type]",'weak_positive',!empty($historyData->upt_type) && $historyData->upt_type == 'weak_positive' ? true : false,[
                                            'id'=>'weak_positive',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="weak_positive">
                                            Weak Positive
                                        </label>

                                        {{Form::radio("data[upt_type]",'negative',!empty($historyData->upt_type) && $historyData->upt_type == 'negative' ? true : false,[
                                            'id'=>'negative',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="negative">
                                            Negative
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#c_o" href="#c_o" aria-expanded="false"
                                aria-controls="past-history">2. C/O</a></h4>
                    </div>
                    <div id="c_o" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row mt-3">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        C/O :
                                    </label>
                                </div>
                                <div class="col-md-10 complain-multi">
                                    {{Form::select('data[co_type][]',$complaints,!empty($historyData->co_type) ? $historyData->co_type : null,['class'=>'form-control co-value co_value_data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'0'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#o_e" href="#o_e" aria-expanded="false"
                                aria-controls="past-history">3. O/E</a></h4>
                    </div>
                    <div id="o_e" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            @php
                                $vitlasClass = !empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[le][vitals_status]','yes',!empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'re_vitals_status','data-id'=>'re_vitals_status_data'])}}
                                        <label for="re_vitals_status">
                                            Vitals
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-2 re_vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                        {{Form::text("data[le][bp]",!empty($historyData->le->bp) ? $historyData->le->bp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 re_vitals_status_data '.$vitlasClass}}">MMHG</span>
                                <div class="{{'col-md-2 re_vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                        {{Form::text("data[le][temp]",!empty($historyData->le->temp) ? $historyData->le->temp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 re_vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                        {{Form::text("data[le][pulse]",!empty($historyData->le->pulse) ? $historyData->le->pulse : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 re_vitals_status_data '.$vitlasClass}}">/ Min</span>
                            </div>
                            @php
                                $pStatus = !empty($historyData->p_s->type) && $historyData->p_s->type == 'yes' ? '' : 'd-none';
                                $paStatus = !empty($historyData->p_a->type) && $historyData->p_a->type == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row mt-3">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        P/S :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[p_s][type]",'yes',!empty($historyData->p_s->type) && $historyData->p_s->type == 'yes' ? true : false,['id'=>'ps_type_yes','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                        <label for="ps_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("data[p_s][type]",'no',!empty($historyData->p_s->type) && $historyData->p_s->type == 'no' ? true : false,['id'=>'ps_type_no','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                        <label for="ps_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-5 ps-details '.$pStatus}}">
                                    <div class="form-group">
                                        {{Form::text("data[p_s][details]",!empty($historyData->p_s->details) ? $historyData->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        P/A :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[p_a][type]",'yes',!empty($historyData->p_a->type) && $historyData->p_a->type == 'yes' ? true : false,['id'=>'pa_type_yes','class'=>'iui-yes-no-status','data-type'=>'pa-details'])}}
                                        <label for="pa_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("data[p_a][type]",'no',!empty($historyData->p_a->type) && $historyData->p_a->type == 'no' ? true : false,['id'=>'pa_type_no','class'=>'iui-yes-no-status','data-type'=>'pa-details'])}}
                                        <label for="pa_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-5 pa-details '.$paStatus}}">
                                    <div class="form-group">
                                        {{Form::text("data[p_a][details]",!empty($historyData->p_a->details) ? $historyData->p_a->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $tvsType = !empty($historyData->tvs->type) && $historyData->tvs->type == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        TVS :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[tvs][type]",'yes',!empty($tvsType) ? false : true,['id'=>'tvs_type_yes','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                        <label for="tvs_type_yes">
                                            Yes
                                        </label>
                                        {{Form::radio("data[tvs][type]",'no',!empty($tvsType) ? true : false,['id'=>'tvs_type_no','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                        <label for="tvs_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Uterus :
                                    </label>
                                </div>
                                <div class="col-md-2 tvs-details">
                                    <div class="form-group">
                                        {{Form::select("data[uterus][type]",['1'=>'Normal','2'=>"Abnormal"],null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
                                    </div>
                                </div>
                                @php
                                    $uterusType = !empty($historyData->uterus->type) && $historyData->uterus->type == '2' ? '' : 'd-none';
                                @endphp
                                <div class="{{'col-md-2 uterus-abnormal-type '.$uterusType}}">
                                    <div class="form-group">
                                        {{Form::text("data[uterus][details]",!empty($historyData->uterus->type) ? $historyData->uterus->type  : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 uterus-abnormal-type '.$uterusType}}">LG</span>
                            </div>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Endometrial Thickness :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::text("data[endometrial_thickness]",!empty($historyData->endometrial_thickness) ? $historyData->endometrial_thickness : null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $left = in_array('left',!empty($historyData->ovary->type) ? $historyData->ovary->type : []) ? '' : 'd-none';
                                $right = in_array('right',!empty($historyData->ovary->type) ? $historyData->ovary->type : []) ? '' : 'd-none';
                                $ovaryLeftType = !empty($historyData->ovary->left->type) && $historyData->ovary->left->type == '2' ? '' : 'd-none';
                                $ovaryRightType = !empty($historyData->ovary->right->type) && $historyData->ovary->right->type == '2' ? '' : 'd-none';
                            @endphp
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Ovary :
                                    </label>
                                </div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[ovary][type][]','right',in_array('right',!empty($historyData->ovary->type) ? $historyData->ovary->type : []),['id'=>'right','class'=>'plan-management'])}}
                                        <label for="right">
                                            Right
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 right-details'}}">
                                    <div class="form-group">
                                        {{Form::select("data[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyData->ovary->right->type) ? $historyData->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="{{'col-md-9 right-details tvs-details '.$tvsType}}">
                                    <div class="row">
                                        <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type mt-1 '.$ovaryRightType}}">
                                            {{Form::select("data[ovary][right][details][]",$rightOvaryData,!empty($historyData->ovary->right->details) ? $historyData->ovary->right->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_right_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-6 complain-multi ovary-right-abnormal-type">
                                            <div class="row edit_oe_ovary_right_details">
                                                @if (isset($historyData->ovary->right->updated_details))
                                                    @foreach ($historyData->ovary->right->updated_details as $key => $value)
                                                        <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$historyData->ovary->right->details[$key]) . '_right' }}">
                                                            {{Form::text('data[ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                                'class' => 'form-control edited_oe_ovary_right_details',
                                                                'id' => preg_replace('/[^a-zA-Z0-9]/','_',$historyData->ovary->right->details[$key])
                                                            ])}}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details mt-3 '.$tvsType}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[ovary][type][]','left',in_array('left',!empty($historyData->ovary->type) ? $historyData->ovary->type : []),['id'=>'left','class'=>'plan-management'])}}
                                        <label for="left">
                                            Left
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 left-details'}}">
                                    <div class="form-group">
                                        {{Form::select("data[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyData->ovary->left->type) ? $historyData->ovary->left->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details '.$tvsType}}">
                                <div class="col-md-3"></div>
                                <div class="{{'col-md-9 left-details'}}">
                                    <div class="row">
                                        <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type'}} ">
                                            {{Form::select("data[ovary][left][details][]",$leftOvaryData,!empty($historyData->ovary->left->details) ? $historyData->ovary->left->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_left_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-6 complain-multi ovary-left-abnormal-type">
                                            <div class="row edit_oe_ovary_left_details">
                                                @if (isset($historyData->ovary->left->updated_details))
                                                    @foreach ($historyData->ovary->left->updated_details as $key => $value)
                                                        <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$historyData->ovary->left->details[$key]) . '_left' }}">
                                                            {{Form::text('data[ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                                'class' => 'form-control edited_oe_ovary_left_details',
                                                                'id' => preg_replace('/[^a-zA-Z0-9]/','_',$historyData->ovary->left->details[$key])
                                                            ])}}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#result" href="#result" aria-expanded="false"
                                aria-controls="past-history">5. Result</a></h4>
                    </div>
                    <div id="result" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row mt-3">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        Result :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[result]",'fail',!empty($historyData->result) && $historyData->result == 'fail' ? true : false,['id'=>'fail','class'=>'r-type'])}}
                                        <label for="fail">
                                            Fail
                                        </label>

                                        {{Form::radio("data[result]",'consive',!empty($historyData->result) && $historyData->result == 'consive' ? true : false,['id'=>'consive','class'=>'r-type'])}}
                                        <label for="consive">
                                            Consive
                                        </label>
                                    </div>
                                    <span class="form-error-msg r-type-msg"></span>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                aria-controls="past-history">6. Treatment</a></h4>
                    </div>
                    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row treatment-data" id="t_data_1">
                                    <div class="col-md-2 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Select Medicine :
                                        </label>
                                    </div>
                                <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                    {{Form::select('data[treatment][medicinedata][]',$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co','placeholder'=>"Enter Medicine",'data-type'=>'iui-cycle'])}}
                                </div>
                            </div>
                            <div class="page-loader-wrapper medicine-loader d-none">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                            @php
                                $historyTreatment  =  (!empty($historyData->treatment)) ? $historyData->treatment : [];
                            @endphp
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
                        </div>
                    </div>
                </div>  
                @php
                $bloodReportClass = !empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'yes' ? true : false;
                $bloodReportClassName = $bloodReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Blood Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[blood_report][type]",'yes',$bloodReportClass,['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[blood_report][type]",'no',!empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'no' ? true : false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 blood-type '.$bloodReportClassName}}">
                        <div class="data-blood-images"></div>
                    </div>
                </div>
                @php
                        $hsaReportClass = !empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'yes' ? true : false;
                        $hsaReportClassName = $hsaReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HSA Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hsa_report][type]",'yes',$hsaReportClass,['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[hsa_report][type]",'no',!empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'no' ? true : false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 hsa-type '.$hsaReportClassName}}">
                        <div class="data-hsa-images"></div>
                    </div>
                    
                </div>     
                <div class="row">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("data[investigation_extra]",isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? $historyData->investigation_extra : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            @if(!empty($historyData->date))
                                {{Form::hidden('data[new_follow_up]',$historyData->date)}}
                            @endif
                            {{Form::text("data[date]",!empty($historyData->date) ? \Carbon\Carbon::parse($historyData->date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date next-date',!empty($historyData->date) ? 'disabled' : ''])}}
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                {{Form::textarea("data[remark]",!empty($historyData->remark) ? $historyData->remark : '',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('remark')}}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                {{Form::textarea("data[pt_remark]",!empty($historyData->pt_remark) ? $historyData->pt_remark : '',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'5'])}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php
                $hTime = !empty($hcg->time) ? $hcg->time : null;
                $typeOfData = [1=>'Primary',2=>'Secondary'];
                $ohData = json_decode($iuiFirstVisit->o_h);
                $possibleFactorData = json_decode($iuiFirstVisit->possible_case_of_infertility);
                $possibleFactorData = !empty($possibleFactorData->infertility_type) ? $possibleFactorData->infertility_type : [];
                $dateAndInjectionData = [];
            @endphp
            <div class="col-sm-12">
                {{Form::submit('Submit',['class'=>'btn btn-primary submit'])}}
                @if($visitNo != 3)
                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                @endif
                @if($visitNo == 3)
                    <button type="submit" class="btn btn-primary final-iui submit" value="6">Follicular Study Report</button>
                @endif
                <button type="submit" class="btn btn-primary iui-print submit d-none" value="2">IUI Print</button>
                <button type="button" class="btn btn-primary iui-deposit-print d-none" value="3">IUI Bill</button>
                <a href="{{URL::to('iui')}}" class="btn btn-default">Cancel</a>
            </div>
        {{Form::close()}}
    </div>
    <script type="text/javascript">
        $('.ho-data-value').selectize({
            create: true,
            sortField: 'text'
        });
        $('.hystroscopy-images').imageUploader({
            imagesInputName: 'investigation[hystroscopy][images]',
        });
        $('.hcg-images').imageUploader({
            imagesInputName: 'investigation[hcg][images]',
        });
        $('.laproscopy-images').imageUploader({
            imagesInputName: 'investigation[laproscopy][images]',
        });
        $('.blood-images').imageUploader({
            imagesInputName: 'investigation[blood_report][image]',
        });
        $('.hsa-images').imageUploader({
            imagesInputName: 'investigation[hsa_report][images]',
        });
        $('.data-blood-images').imageUploader({
            imagesInputName: 'data[blood_report][image]',
        });
        $('.data-usg-images').imageUploader({
            imagesInputName: 'data[usg][images]',
        });
        $('.data-hsa-images').imageUploader({
            imagesInputName: 'data[hsa_report][images]',
        });
        $(document).on('click', '.add-row', function() {
            addRow();
        });
        function changeDateFormat(d){
            var yyyy = d.getFullYear().toString();
            var mm = (d.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = d.getDate().toString();
            var date = (dd[1]?dd:"0"+dd[0]) + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + yyyy;
            return date;
        }
        $(document).ready(function(){
            $('.datetime').multiDatesPicker({
                // minDate: 0
            });
        });
    

