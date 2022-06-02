@php
$medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
$medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
$medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
$dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day"];
@endphp
<div class="row">
    <div class="col-md-1">
        <label class="vertical-form-label pr-0">
            Seen By :
        </label>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {{Form::hidden('gunec_id',isset($gynecId) ? $gynecId : null,['class'=>'form-control gynec_id'])}}

            {{Form::select('seen_by',$hospitalDoctor,isset($gynecId) && !empty($gynecData->seen_by) ? $gynecData->seen_by : null,['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
        </div>
        <span class="seen-by-error text-danger mb-2"></span>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingThree_1">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                aria-controls="patients">Patients Basic Information</a> </h4>
    </div>
    <div id="patients" class="panel-collapse collapse p-info" role="tabpanel" aria-labelledby="headingThree_1">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon">Name : &nbsp;</span>
                        {{Form::text('name',$gynecData->getGynecPatients->name,['class'=>'form-control name'])}}
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
                        {{Form::text('code',$gynecData->getGynecPatients['code'],['class'=>'form-control code','disabled'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('code')}}
                    </span>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Age : &nbsp;</span>
                        {{Form::number("p_info[age]",!empty($gynecData->getGynecPatients->age) ? $gynecData->getGynecPatients->age  : null,['class'=>'form-control age'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('age')}}
                    </span>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">weight : &nbsp;</span>
                        {{Form::text("p_info[weight]",$gynecData->getGynecPatients->weight,['class'=>'form-control weight','id'=>'weight'])}}
                    </div>
                    <span class="form-error-msg weight"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Mobile : &nbsp;</span>
                        {{Form::number('mobile_number',$gynecData->getGynecPatients['mobile_number'],['class'=>'form-control mobile_number'])}}
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
                        {{Form::select('rd_reference',$referenceDoctor,!empty($gynecData->getGynecPatients['reference_doctor_id']) ? $gynecData->getGynecPatients['reference_doctor_id'] : null,['class'=>'form-control select-padding-0 refence-doctor','placeholder'=>'Rd Reference'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('rd_reference')}}
                    </span>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Rd Mobile : &nbsp;</span>
                        {{Form::number('rd_mobile_number',!empty($gynecData->getGynecPatients->getReferenceDoctor['mobile_number']) ? $gynecData->getGynecPatients->getReferenceDoctor['mobile_number'] : null,['class'=>'form-control ref-mobile-number'])}}
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
                        {{Form::text('residence',!empty($gynecData->getGynecPatients['residence']) ? $gynecData->getGynecPatients['residence'] : null,['class'=>'form-control'])}}
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
                        {{Form::text('main_area',!empty($gynecData->getGynecPatients['main_area']) ? $gynecData->getGynecPatients['main_area'] : null,['class'=>'form-control'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('main_area')}}
                    </span>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">City : &nbsp;</span>
                        {{Form::text('city',!empty($gynecData->getGynecPatients['city']) ? $gynecData->getGynecPatients['city'] : null,['class'=>'form-control'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('city')}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingThree_1">
    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                               data-parent="#ho_data" href="#ho_data" aria-expanded="false"
                aria-controls="ho_data">1. H/O</a></h4>
    </div>
    <div id="ho_data" class="panel-collapse collapse" role="tabpanel"
        aria-labelledby="headingThree_1">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-1 pr-0">
                    <label class="vertical-form-label pr-0">
                        H/O :
                    </label>
                </div>
                @php
                    $hoClass = !empty($ho->ho_details) ? '' : 'ho_type_value';
                @endphp
                @if($isGynec == 1)
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon">Follow Up Case Of {{!empty($ho->gynec_type) ? $ho->gynec_type : ''}} : &nbsp;</span>
                            {{Form::text("ho[ho_details]",!empty($ho->ho_details) ? $ho->ho_details : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                @else
                    <div class='col-md-11 complain-multi duration-value'>
                        {{Form::select('ho[ho_details]',$hoData,!empty($ho->ho_details) ? $ho->ho_details : null,['class'=>'form-control ho-data select-padding-0 duration-data anc-dose-val '.$hoClass,'placeholder'=>'Select H/O','data-medicine'=>2])}}
                        <span class="form-error-msg ho-data-msg">
                            {{$errors->first('ho_details')}}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{Form::hidden('is_gynec',$isGynec)}}
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
                <div class="col-md-8 complain-multi">
                    @php
                        $coClass = 'complaint-data';
                        if(!empty($gynecId)){
                            $coClass = '';
                        }
                    @endphp
                    {{Form::select('co[co_type][]',$complaints,!empty($co->co_type) ? $co->co_type : null,['class'=>'form-control co-value co_value_data co_value_data '.$coClass,'placeholder'=>'Enter complain','multiple'=>true,'data-medicine'=>1])}}
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
@if($isGynec != 1 )
    <!-- O/H -->
    @php
        $displayO_H = !empty($gynecId) && $isFirstVisit ==  true ? '' : 'd-none';
    @endphp
    <div class="{{'panel panel-primary '.$displayO_H}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                data-parent="#oh" href="#oh" aria-expanded="false"
                    aria-controls="oh">3. O/H</a></h4>
        </div>
        <div id="oh" class="panel-collapse collapse" role="tabpanel"
            aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    @php
                        $marriedType = !empty($oh->married_type) && $oh->married_type == 'married' ? '' : 'd-none';
                    @endphp
                    <div class="col-sm-3">
                        <div class="radio is-conceived">
                            {{Form::radio("oh[married_type]",'married',!empty($marriedType) ? false : true,[
                                'id'=>'married',
                                'class'=>'married-type',
                                isset($gynecId) ? 'disabled' : ''
                            ])}}
                            <label for="married">
                                Married
                            </label>

                            {{Form::radio("oh[married_type]",'unmarried',!empty($marriedType) ? true : false,[
                                'id'=>'unmarried',
                                'class'=>'married-type',
                                isset($gynecId) ? 'disabled' : ''
                            ])}}
                            <label for="unmarried">
                                Unmarried
                            </label>
                        </div>
                    </div>
                    {{Form::hidden('oh[married_type]',!empty($marriedType) ? $marriedType : null,['class'=>'form-control gynec_id'])}}

                </div>
                <div class="{{'married-data '.$marriedType}}">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">First Marriage Life : &nbsp;</span>
                                {{Form::text("oh[marriage_life]",!empty($oh->marriage_life) ? $oh->marriage_life : null,['class'=>'form-control'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('marriage_life')}}
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">PLED : &nbsp;</span>
                                {{Form::text("oh[pled]",!empty($oh->pled) ? $oh->pled : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
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

                        <div class="col-md-3">
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
                                    <div class="col-sm-2">
                                        <div class="radio is-conceived">
                                            {{Form::radio("oh[child][child_data][".$key."][child_type]",'single',!empty($row->child_type) && $row->child_type == 'single' ? true : false,['id'=>'child_type_single_'.$key])}}
                                            <label for="{{'child_type_single_'.$key}}">
                                                Single
                                            </label>
    
                                            {{Form::radio("oh[child][child_data][".$key."][child_type]",'twins',!empty($row->child_type) && $row->child_type == 'twins' ? true : false,['id'=>'child_type_twins_'.$key])}}
                                            <label for="{{'child_type_twins_'.$key}}">
                                                Twins
                                            </label>
                                            {{Form::radio("oh[child][child_data][".$key."][child_type]",'triple',!empty($row->child_type) && $row->child_type == 'triple' ? true : false,['id'=>'child_type_triple_'.$key])}}
                                            <label for="{{'child_type_triple_'.$key}}">
                                                Triple
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        {{Form::select("oh[child][child_data][".$key."][ho_gender][]",['male'=>'Male','female'=>'Female'],isset($row->ho_gender)  ? (is_array($row->ho_gender) ? array_filter($row->ho_gender) : array($row->ho_gender)) : '',['class'=>'form-control select-padding-0','data-id'=>'','multiple','title'=>'Select Child Gender'])}}
                                        {{-- <div class="radio is-conceived">
                                            {{Form::radio("oh[child][child_data][1][ho_gender]",'male','',['id'=>'ho_male'])}}
                                            <label for="ho_male">
                                                Male
                                            </label>
    
                                            {{Form::radio("oh[child][child_data][1][ho_gender]",'female','',['id'=>'ho_female'])}}
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
                                            {{Form::radio("oh[child][child_data][".$key."][ho_type]",'normal',!empty($row->ho_type) && $row->ho_type == 'normal' ? true : false,['id'=>'normal_'.$key])}}
                                            <label for={{'normal_'.$key}}>
                                                Normal
                                            </label>

                                            {{Form::radio("oh[child][child_data][".$key."][ho_type]",'cesarean',!empty($row->ho_type) && $row->ho_type == 'cesarean' ? true : false,['id'=>'cesarean_'.$key])}}
                                            <label for={{'cesarean_'.$key}}>
                                                Cesarean
                                            </label>

                                            {{Form::radio("oh[child][child_data][".$key."][ho_type]",'instrumental',!empty($row->ho_type) && $row->ho_type == 'instrumental' ? true : false,['id'=>'instrumental_'.$key])}}
                                            <label for="{{'instrumental_'.$key}}">
                                                Instrumental
                                            </label>
                                        </div>
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
                                    <div class="{{'col-sm-2 expired-reason-'.$key.' '.$reasonStatus}}">
                                        <div class="form-group">
                                            {{Form::text("oh[child][child_data][".$key."][expired_reason]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Reason'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-sm-2 expired-reason-'.$key.' '.$reasonStatus}}">
                                        <div class="form-group">
                                            {{Form::text("oh[child][child_data][".$key."][expired_year]",!empty($row->expired_year) ? $row->expired_year : null ,['class'=>'form-control','placeholder'=>'Expired Year'])}}
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
                                            {{Form::select("oh[child][child_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type p-ho-type','data-id'=>'child-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
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
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                {{Form::text("oh[mtp][mtp_data][".$key."][mtp_month_of_pregancy]",!empty($row->mtp_month_of_pregancy) ? $row->mtp_month_of_pregancy : null,['class'=>'form-control'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('mtp_month_of_pregancy')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="{{'col-md-4 mtp-naturally '.$mtpNaturally}}">
                                        <div class="form-group">
                                            {{Form::select("oh[mtp][mtp_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 mtp-ho-type p-ho-type','data-id'=>'mtp-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
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
                                    <div class="{{'col-md-3 abortion-naturally '.$abortionNaturally}}">
                                        <div class="form-group">
                                            {{Form::select("oh[abortion][abortion_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 abortion-ho-type p-ho-type','data-id'=>'abortion-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
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
                                    <div class='col-md-4'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Abortion Reason : &nbsp;</span>
                                            {{Form::text("oh[abortion][abortion_data][".$key."][reason]",isset($value->reason) && !empty($value->reason) ? $value->reason : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
                            <div class="radio is-conceived d-flex">
                                {{-- {{Form::radio("oh[contraception][contraception_data]",'barrier_method',$contraceptionValue == 'barrier_method' ? true : false,['class'=>'mr-2','id'=>'barrier_method'])}}
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
                                </label> --}}
                                {{Form::radio("oh[contraception][contraception_data]",'barrier_method',$contraceptionValue == 'barrier_method' ? true : false,['class'=>'mr-2 contraception_radio','data-type'=>'barrier-method-detail','id'=>'barrier_method'])}}
                                <label for="barrier_method" class="barrier_method">
                                    Barrier Method
                                </label>
                                @if($contraceptionValue == 'barrier_method')
                                    {{Form::text("oh[contraception][detail]",isset($oh->contraception->detail) ? $oh->contraception->detail : '',['class'=>'form-control col-md-3 contraception-radio-radio barrier-method-detail '])}}
                                @endif

                                {{Form::radio("oh[contraception][contraception_data]",'cu_t',$contraceptionValue == 'cu_t' ? true : false,['class'=>'mr-2 contraception_radio','id'=>'cu_t','data-type'=>'cu-t-detail'])}}
                                <label for="cu_t" class="cu_t">
                                    Cu - T
                                </label>
                                @if($contraceptionValue == 'cu_t')
                                    {{Form::text("oh[contraception][detail]",isset($oh->contraception->detail) ? $oh->contraception->detail : '',['class'=>'form-control col-md-3 contraception-radio-radio cu-t-detail '])}}
                                @endif

                                {{Form::radio("oh[contraception][contraception_data]",'tl_done',$contraceptionValue == 'tl_done' ? true : false,['class'=>'mr-2 contraception_radio','id'=>'tl_done','data-type'=>'tl-done-detail'])}}
                                <label for="tl_done" class="tl_done">
                                TL Done
                                </label>
                                @if($contraceptionValue == 'tl_done')
                                    {{Form::text("oh[contraception][detail]",isset($oh->contraception->detail) ? $oh->contraception->detail : '',['class'=>'form-control col-md-3 contraception-radio-radio tl-done-detail '])}}
                                @endif

                                {{Form::radio("oh[contraception][contraception_data]",'occipill',$contraceptionValue == 'occipill' ? true : false,['class'=>'mr-2 contraception_radio','id'=>'occipill','data-type'=>'occipill-detail'])}}
                                <label for="occipill" class="occipill">
                                    Occipill
                                </label>
                                @if($contraceptionValue == 'occipill')
                                    {{Form::text("oh[contraception][detail]",isset($oh->contraception->detail) ? $oh->contraception->detail : '',['class'=>'form-control col-md-3 contraception-radio-radio occipill-detail '])}}
                                @endif 

                                {{Form::radio("oh[contraception][contraception_data]",'other_contraception',$contraceptionValue == 'other_contraception' ? true : false,['class'=>'mr-2 contraception_radio','id'=>'other_contraception','data-type'=>'other-detail'])}}
                                <label for="other_contraception" class="other_contraception">
                                    Other
                                </label> 
                                @if($contraceptionValue == 'other_contraception')
                                    {{Form::text("oh[contraception][detail]",isset($oh->contraception->detail) ? $oh->contraception->detail : '',['class'=>'form-control col-md-3 contraception-radio-radio other-detail '])}}
                                @endif
                            </div>
                        </div>
                        <div class="{{'col-sm-3 contraception-data ' . $contraceptionStatusClass}}">
                            <div class='input-group'>
                                <span class='input-group-addon'>Detail : &nbsp;</span>
                                {{Form::text("oh[contraception][detail]",!empty($oh->contraception) && isset($oh->contraception->detail) ? $oh->contraception->detail : '',['class'=>'form-control'])}}
                            </div>
                        </div>
                    </div>
                    {{-- end contraception marriage --}}
                    <!-- for ectopic -->
                    <div class="row mt-3">
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-addon">Ectopic : &nbsp;</span>
                                {{Form::number("oh[ectopic_no]",isset($oh->ectopic_no) && !empty($oh->ectopic_no) ? $oh->ectopic_no : 0,['class'=>'form-control ectopic-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('ectopic')}}
                            </span>
                        </div>
                        @php
                            $ectopicNaturally = isset($oh->ectopic_no) && !empty($oh->ectopic_no) && $oh->ectopic_no != 0 ? '' : 'd-none';
                        @endphp
                    </div>
                    {{-- for ectopic data --}}
                    <div class="ectopic-data">
                        @if(!empty($oh && isset($oh->ectopic_no) &&  ($oh->ectopic_no != null && $oh->ectopic_no != 0 )))
                            @foreach($oh->ectopic->ectopic_data as $key=>$value)
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="vertical-form-label pr-0">
                                            Ectopic :
                                        </label>
                                    </div>
                                    
                                    <div class="{{'row col-md-8 ectopic-visible-'.$key}}">
                                        <div class="col-sm-3">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oh[ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'medically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type == 'medically' ? true : false,['id'=>'spontancous_ectopic_medically_'.$key])}}
                                                <label for="{{'spontancous_ectopic_medically_'.$key}}">
                                                    Medically
                                                </label>
                                                {{Form::radio("oh[ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'surgically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type== 'surgically' ? true : false,['id'=>'spontancous_ectopic_surgically_'.$key])}}
                                                <label for="{{'spontancous_ectopic_surgically_'.$key}}">
                                                    Surgically
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Before&nbsp;</span>
                                                {{Form::text("oh[ectopic][ectopic_data][".$key."][spontancous_ectopic_before]",!empty($value->spontancous_ectopic_before) ? $value->spontancous_ectopic_before : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                {{Form::checkbox('oh[ectopic][ectopic_data]['.$key.'][tube][]','right',isset($value->tube) && in_array('right',$value->tube) ? true : false,['id'=>'right_tube_'.$key])}}
                                                <label for="{{'right_tube_'.$key}}">
                                                    Right Tube
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                {{Form::checkbox('oh[ectopic][ectopic_data]['.$key.'][tube][]','left',isset($value->tube) && in_array('left',$value->tube) ? true : false,['id'=>'left_tube_'.$key])}}
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
                                            {{Form::select("oh[ectopic][ectopic_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 ectopic-ho-type p-ho-type','data-id'=>'ectopic-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                        </div>
                                    </div>
                                    @php
                                        $dNone = '';
                                        if(!empty($oh->ectopic) && !empty($value->ho_type) && !in_array($value->ho_type,$hoType)){
                                            $dNone = 'd-none';
                                        }
                                    @endphp

                                    <div class="{{'col-md-4 ectopic-when-where-'.$key.' '.$dNone}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">When / Where : &nbsp;</span>
                                            {{Form::text("oh[ectopic][ectopic_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Ectopic Detail : &nbsp;</span>
                                            {{Form::text("oh[ectopic][ectopic_data][".$key."][detail]",!empty($value->detail) ? $value->detail : null,['class'=>'form-control'])}}
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
                                    <div class="col-sm-2">
                                        <div class="radio is-conceived">
                                            {{Form::radio("oh[second_marriage][child][child_data][".$key."][child_type]",'single',!empty($row->child_type) && $row->child_type == 'single' ? true : false,['id'=>'child_type_single_'.$key])}}
                                            <label for="{{'child_type_single_'.$key}}">
                                                Single
                                            </label>
    
                                            {{Form::radio("oh[second_marriage][child][child_data][".$key."][child_type]",'twins',!empty($row->child_type) && $row->child_type == 'twins' ? true : false,['id'=>'child_type_twins_'.$key])}}
                                            <label for="{{'child_type_twins_'.$key}}">
                                                Twins
                                            </label>
                                            {{Form::radio("oh[second_marriage][child][child_data][".$key."][child_type]",'triple',!empty($row->child_type) && $row->child_type == 'triple' ? true : false,['id'=>'child_type_triple_'.$key])}}
                                            <label for="{{'child_type_triple_'.$key}}">
                                                Triple
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        {{Form::select("oh[second_marriage][child][child_data][".$key."][ho_gender][]",['male'=>'Male','female'=>'Female'],isset($row->ho_gender)  ? (is_array($row->ho_gender) ? array_filter($row->ho_gender) : array($row->ho_gender)) : '',['class'=>'form-control select-padding-0','data-id'=>'','multiple','title'=>'Select Child Gender'])}}
                                        {{-- <div class="radio is-conceived">
                                            {{Form::radio("oh[child][child_data][1][ho_gender]",'male','',['id'=>'ho_male'])}}
                                            <label for="ho_male">
                                                Male
                                            </label>
    
                                            {{Form::radio("oh[child][child_data][1][ho_gender]",'female','',['id'=>'ho_female'])}}
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
                                            {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_type]",'normal',!empty($row->ho_type) && $row->ho_type == 'normal' ? true : false,['id'=>'second_normal_'.$key])}}
                                            <label for={{'second_normal_'.$key}}>
                                                Normal
                                            </label>

                                            {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_type]",'cesarean',!empty($row->ho_type) && $row->ho_type == 'cesarean' ? true : false,['id'=>'second_cesarean_'.$key])}}
                                            <label for={{'second_cesarean_'.$key}}>
                                                Cesarean
                                            </label>

                                            {{Form::radio("oh[second_marriage][child][child_data][".$key."][ho_type]",'instrumental',!empty($row->ho_type) && $row->ho_type == 'instrumental' ? true : false,['id'=>'second_instrumental_'.$key])}}
                                            <label for="{{'second_instrumental_'.$key}}">
                                                Instrumental
                                            </label>
                                        </div>
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
                                    <div class="{{'col-sm-2 expired-reason-'.$key.' '.$reasonStatus}}">
                                        <div class="form-group">
                                            {{Form::text("oh[second_marriage][child][child_data][".$key."][expired_reason]",!empty($row->expired_reason) ? $row->expired_reason : null ,['class'=>'form-control','placeholder'=>'Reason'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-sm-2 expired-reason-'.$key.' '.$reasonStatus}}">
                                        <div class="form-group">
                                            {{Form::text("oh[second_marriage][child][child_data][".$key."][expired_year]",!empty($row->expired_year) ? $row->expired_year : null ,['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                        </div>
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
                                            {{Form::select("oh[second_marriage][child][child_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 child-ho-type second-p-ho-type','data-id'=>'second-child-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
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
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                {{Form::text("oh[second_marriage][mtp][mtp_data][".$key."][mtp_month_of_pregancy]",!empty($row->mtp_month_of_pregancy) ? $row->mtp_month_of_pregancy : null,['class'=>'form-control'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('mtp_month_of_pregancy')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row second-marriage-life-data">
                                    <div class="col-md-1"></div>
                                    <div class="{{'col-md-4 second-marriage-life-data second-mtp-naturally '.$mtpNaturally}}">
                                        <div class="form-group">
                                            {{Form::select("oh[second_marriage][mtp][mtp_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($row->ho_type) ? $row->ho_type : null,['class'=>'form-control select-padding-0 mtp-ho-type second-p-ho-type','data-id'=>'second-mtp-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
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
                                                <span class="input-group-addon">MOA &nbsp;</span>
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
                                            {{Form::select("oh[second_marriage][abortion][abortion_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 abortion-ho-type second-p-ho-type','data-id'=>'second-abortion-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
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
                                    <div class='col-md-4 second-marriage-life-data'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Abortion Reason : &nbsp;</span>
                                            {{Form::text("oh[second_marriage][abortion][abortion_data][".$key."][reason]",isset($value->reason) && !empty($value->reason) ? $value->reason : null,['class'=>'form-control'])}}
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
                            <div class="radio is-conceived d-flex">
                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'barrier_method',$secondContraceptionValue == 'barrier_method' ? true : false,['class'=>'mr-2 second_contraception_radio','data-type'=>'second-barrier-method-detail','id'=>'second_barrier_method'])}}
                                <label for="second_barrier_method" class="second_barrier_method">
                                    Barrier Method
                                </label>
                                @if($secondContraceptionValue == 'barrier_method')
                                    {{Form::text("oh[second_marriage][contraception][detail]",isset($oh->second_marriage->contraception->detail) ? $oh->second_marriage->contraception->detail : '',['class'=>'form-control col-md-3 second-contraception-radio-radio second-barrier-method-detail '])}}
                                @endif

                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'cu_t',$secondContraceptionValue == 'cu_t' ? true : false,['class'=>'mr-2 second_contraception_radio','id'=>'second_cu_t','data-type'=>'second-cu-t-detail'])}}
                                <label for="second_cu_t" class="second_cu_t">
                                    Cu - T
                                </label>
                                @if($secondContraceptionValue == 'cu_t')
                                    {{Form::text("oh[second_marriage][contraception][detail]",isset($oh->second_marriage->contraception->detail) ? $oh->second_marriage->contraception->detail : '',['class'=>'form-control col-md-3 second-contraception-radio-radio second-cu-t-detail '])}}
                                @endif

                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'tl_done',$secondContraceptionValue == 'tl_done' ? true : false,['class'=>'mr-2 second_contraception_radio','id'=>'second_tl_done','data-type'=>'second-tl-done-detail'])}}
                                <label for="second_tl_done" class="second_tl_done">
                                TL Done
                                </label>
                                @if($secondContraceptionValue == 'tl_done')
                                    {{Form::text("oh[second_marriage][contraception][detail]",isset($oh->second_marriage->contraception->detail) ? $oh->second_marriage->contraception->detail : '',['class'=>'form-control col-md-3 second-contraception-radio-radio second-tl-done-detail '])}}
                                @endif

                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'occipill',$secondContraceptionValue == 'occipill' ? true : false,['class'=>'mr-2 second_contraception_radio','id'=>'second_occipill','data-type'=>'second-occipill-detail'])}}
                                <label for="second_occipill" class="second_occipill">
                                    Occipill
                                </label>
                                @if($secondContraceptionValue == 'occipill')
                                    {{Form::text("oh[second_marriage][contraception][detail]",isset($oh->second_marriage->contraception->detail) ? $oh->second_marriage->contraception->detail : '',['class'=>'form-control col-md-3 second-contraception-radio-radio second-occipill-detail '])}}
                                @endif 

                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'other_contraception',$secondContraceptionValue == 'other_contraception' ? true : false,['class'=>'mr-2 second_contraception_radio','id'=>'second_other_contraception','data-type'=>'second-other-detail'])}}
                                <label for="second_other_contraception" class="second_other_contraception">
                                    Other
                                </label> 
                                @if($secondContraceptionValue == 'other_contraception')
                                    {{Form::text("oh[second_marriage][contraception][detail]",isset($oh->second_marriage->contraception->detail) ? $oh->second_marriage->contraception->detail : '',['class'=>'form-control col-md-3 second-contraception-radio-radio second-other-detail '])}}
                                @endif
                            </div>
                        </div>
                        <div class="{{'col-sm-3 second-contraception-data ' . $secondContraceptionStatusClass}}">
                            <div class='input-group'>
                                <span class='input-group-addon'>Detail : &nbsp;</span>
                                {{Form::text("oh[second_marriage][contraception][detail]",!empty($oh->second_marriage->contraception) && isset($oh->second_marriage->contraception->detail) ? $oh->second_marriage->contraception->detail : '',['class'=>'form-control'])}}
                            </div>
                        </div>
                    </div>
                    {{-- end contraception second marriage --}}
                    <!-- for Ectopic -->
                    <div class="{{'second-marriage-life row mt-3 ' . $secondMerrageStatus}}">
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-addon">Ectopic : &nbsp;</span>
                                {{Form::number("oh[second_marriage][ectopic_no]",isset($oh->second_marriage->ectopic_no) && !empty($oh->second_marriage->ectopic_no) ? $oh->second_marriage->ectopic_no : 0,['class'=>'form-control second-ectopic-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-type'=>'1'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('ectopic')}}
                            </span>
                        </div>
                        @php
                            $abortionNaturally = isset($oh->second_marriage->ectopic_no) && !empty($oh->second_marriage->ectopic_no) && $oh->second_marriage->ectopic_no != 0 ? '' : 'd-none';
                        @endphp
                    </div>
                    {{-- for ectopic data --}}
                    <div class="{{'second-marriage-life second-ectopic-data ' . $secondMerrageStatus }}">
                        @if(!empty($oh && isset($oh->second_marriage) && isset($oh->second_marriage->ectopic_no) && ($oh->second_marriage->ectopic_no != null && $oh->second_marriage->ectopic_no != 0 )))
                            @foreach($oh->second_marriage->ectopic->ectopic_data as $key=>$value)
                                <div class="row second-marriage-life-data">
                                    <div class="col-md-2">
                                        <label class="vertical-form-label pr-0">
                                            Ectopic :
                                        </label>
                                    </div>
                                    <div class="{{'row second-marriage-life-data col-md-8 second-ectopic-visible-'.$key}}">
                                        <div class="col-sm-3">
                                            <div class="radio is-conceived">
                                                {{Form::radio("oh[second_marriage][ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'medically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type == 'medically' ? true : false,['id'=>'second_spontancous_ectopic_medically_'.$key])}}
                                                <label for="{{'second_spontancous_ectopic_medically_'.$key}}">
                                                    Medically
                                                </label>
                                                {{Form::radio("oh[second_marriage][ectopic][ectopic_data][".$key."][spontancous_ectopic_type]",'surgically',!empty($value->spontancous_ectopic_type) && $value->spontancous_ectopic_type== 'surgically' ? true : false,['id'=>'second_spontancous_ectopic_surgically_'.$key])}}
                                                <label for="{{'second_spontancous_ectopic_surgically_'.$key}}">
                                                    Surgically
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Before &nbsp;</span>
                                                {{Form::text("oh[second_marriage][ectopic][ectopic_data][".$key."][spontancous_ectopic_before]",isset($value->spontancous_ectopic_before) && !empty($value->spontancous_ectopic_before) ? $value->spontancous_ectopic_before : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                {{Form::checkbox('oh[second_marriage][ectopic][ectopic_data]['.$key.'][tube][]','right',isset($value->tube) && in_array('right',$value->tube) ? true : false,['id'=>'second_right_tube_'.$key])}}
                                                <label for="{{'second_right_tube_'.$key}}">
                                                    Right Tube
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                {{Form::checkbox('oh[second_marriage][ectopic][ectopic_data]['.$key.'][tube][]','left',isset($value->tube) && in_array('left',$value->tube) ? true : false,['id'=>'second_left_tube_'.$key])}}
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
                                            {{Form::select("oh[second_marriage][ectopic][ectopic_data][".$key."][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'gynecData','4'=>'IVF'],!empty($value->ho_type) ? $value->ho_type : null,['class'=>'form-control select-padding-0 ectopic-ho-type second-p-ho-type','data-id'=>'second-ectopic-when-where-'.$key,'placeholder'=>'Select Conceived By'])}}
                                        </div>
                                    </div>
                                    @php
                                        $dNone = 'd-none';
                                        if(!empty($oh->second_marriage->ectopic) && !empty($value->ho_type) && in_array($value->ho_type,$hoType)){
                                            $dNone = '';
                                        }
                                    @endphp
                                    <div class="{{'col-md-4 second-marriage-life-data second-ectopic-when-where-'.$key.' '.$dNone.' '.$ectopicNaturally}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">When / Where: &nbsp;</span>
                                            {{Form::text("oh[second_marriage][ectopic][ectopic_data][".$key."][when_where]",!empty($value->when_where) ? $value->when_where : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class='input-group'>
                                            <span class='input-group-addon'>Ectopic Detail : &nbsp;</span>
                                            {{Form::text("oh[second_marriage][ectopic][ectopic_data][".$key."][detail]",!empty($value->detail) ? $value->detail : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        {{Form::textarea("oh[remark]",!empty($oh->remark) ? $oh->remark : null,['class'=>'form-control no-resize','placeholder'=>'Remark','rows'=>'5'])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($isGynec != 1)
    <!-- Personal history  -->
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#personal-history" href="#personal-history" aria-expanded="false"
                    aria-controls="personal-history">4. M/H</a></h4>
        </div>
        <div id="personal-history" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">

                <div class="row">
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            {{Form::select("mh[type_and_year_of_infertility]",['primary'=>'Primary','secondary'=>'Secondary'],!empty($mh->type_and_year_of_infertility) ? $mh->type_and_year_of_infertility : null,['class'=>'form-control select-padding-0','placeholder'=>'Type And Year Of Infertility'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('type_and_year_of_infertility')}}
                        </span>
                    </div> --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{Form::select("mh[age_of_menarchy]",[11=>11,12=>12,13=>13,14=>14,15=>15],!empty($mh->age_of_menarchy) ? $mh->age_of_menarchy : null,['class'=>'form-control select-padding-0','placeholder'=>'Age Of Menarchy'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('age_of_menarchy')}}
                        </span>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Since Year : &nbsp;</span>
                            {{Form::text("mh[since_year]",!empty($mh->since_year) ? $mh->since_year : null,['class'=>'form-control'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('since_year')}}
                        </span>
                    </div> --}}
                    @if((!empty($oh->married_type) && $oh->married_type == 'married'))
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon">Manopause Since Year : &nbsp;</span>
                                {{Form::text("mh[manopause_since_year]",!empty($mh->manopause_since_year) ? $mh->manopause_since_year : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    {{-- <div class="col-sm-5">
                        <div class="form-group">
                            {{Form::text("mh[age_of_manopause]",!empty($mh->age_of_manopause) ? $mh->age_of_manopause : null,['class'=>'form-control','placeholder'=>'Age Of Manopause'])}}
                        </div>
                        
                    </div> --}}
                    
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
                            {{Form::select("mh[past_mh_2]",[''=>'Select Past MH','regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->past_mh_2) ? $mh->past_mh_2 : null,['class'=>'form-control select-padding-0 past-mh-2 regular-type','data-id'=>'past-ir-regular-data'])}}
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
                            {{Form::select("mh[present_mh_2]",[''=>'Select Past MH','regular'=>'Regular','irregular'=>'IR Regular'],!empty($mh->present_mh_2) ? $mh->present_mh_2 : null,['class'=>'form-control select-padding-0 present-mh-2 regular-type','data-id'=>'present-ir-regular-data'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('present_mh_2')}}
                        </span>
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
                            {{Form::radio("mh[present_month]",'month',!empty($mh->present_month) && $mh->present_month == 'painful' ? true : false,['id'=>'pr_painful','class'=>'present-m'])}}
                            <label for="pr_painful">
                                Painful
                            </label>

                            {{Form::radio("mh[present_month]",'day',!empty($mh->present_month) && $mh->present_month == 'painless' ? true : false,['id'=>'pr_painless','class'=>'present-m'])}}
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


                    {{-- <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">EDD : &nbsp;</span>
                            @php
                                $date = !empty($mh->edd) ? \Carbon\Carbon::parse($mh->edd)->format('D d M Y') : null;
                            @endphp
                            {{Form::text("mh[edd]",$date,['class'=>'form-control datetimepicker date edd-date','disabled'])}}
                        </div>
                        {{Form::hidden("mh[edd]",$date,['class'=>'edd-date'])}}
                        <span class="form-error-msg">
                            {{$errors->first("p_details[edd]")}}
                        </span>
                    </div> --}}
                    {{-- <div class="col-md-3">
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
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
@endif
@if($isGynec != 1)
@php
        $displayH_o = !empty($gynecId) && $isFirstVisit ==  true ? '' : 'd-none';
    @endphp
    <div class="{{'panel panel-primary '.$displayH_o}}">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#patients-detailed-ho" aria-expanded="false"
                    aria-controls="patients-detailed-ho">5. Patients Detailed H/O</a></h4>
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
                        {{Form::select('p_detailes[personal_history_history_type][]',$personalData,!empty($patientsDetails->personal_history_history_type) ? $patientsDetails->personal_history_history_type : null,['class'=>'form-control co-value co_value_data personal-history mb-3','placeholder'=>'Select Personal History','multiple'=>true])}}
                    </div>
                    
                    {{-- <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("p_detailes[personal_history_date]",!empty($patientsDetailsHo->personal_history_date) ? \Carbon\Carbon::parse($patientsDetailsHo->personal_history_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div> --}}
                    <div class="col-md-4 ho-past-personal-data">
                        {{Form::text('p_detailes[personal_history_detail]',(isset($patientsDetails->personal_history_detail) && !empty($patientsDetails->personal_history_detail)) ? $patientsDetails->personal_history_detail : null,['class'=>'form-control ','placeholder'=>'Personal History Detail'])}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Family History :
                        </label>
                    </div>
                    <div class="col-md-4 complain-multi">
                        {{Form::select('p_detailes[family_history][]',$familyData,!empty($patientsDetails->family_history) ? $patientsDetails->family_history : null,['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Family History','multiple'=>true])}}
                    </div>
                    <div class="col-md-4 ho-past-personal-data">
                        {{Form::text('p_detailes[family_history_detail]',(isset($patientsDetails->family_history_detail) && !empty($patientsDetails->family_history_detail)) ? $patientsDetails->family_history_detail : null,['class'=>'form-control ','placeholder'=>'Family History Detail'])}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Past History :
                        </label>
                    </div>
                    <div class="col-md-4 complain-multi ho-past-personal-data">
                        {{Form::select('p_detailes[past_history_type][]',$pastData,!empty($patientsDetails->past_history_type) ? $patientsDetails->past_history_type : null,['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Past History','multiple'=>true])}}
                    </div>
                    <div class="col-md-4 ho-past-personal-data">
                        {{Form::text('p_detailes[past_history_detail]',(isset($patientsDetails->past_history_detail) && !empty($patientsDetails->past_history_detail)) ? $patientsDetails->past_history_detail : null,['class'=>'form-control ','placeholder'=>'Past History Detail'])}}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif
@if($isGynec != 1)
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#oe_tab" href="#oe_tab" aria-expanded="false"
                    aria-controls="past-history">6. O/E</a></h4>
        </div>
        <div id="oe_tab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body" id="parent">
                {{Form::hidden("married_type",!empty($oh->married_type) ? $oh->married_type : null,['class'=>'form-control input-married-type','placeholder'=>'UT Details'])}}
                <div class="row">
                @php
                    $paType = isset($gynecId) &&  !empty($oe->p_a->type) && $oe->p_a->type == 'yes' ? '' : 'd-none';
                    $utersDetail = isset($gynecId) && ((!empty($oh->married_type) && $oh->married_type == 'unmarried') ||  $oe->p_a->type == 'yes') ? '' : 'd-none';
                @endphp
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            P/A :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[p_a][type]",'yes',empty($paType) ? true : false,['id'=>'pa_type_yes','class'=>'pa-type','data-type'=>'pa-details'])}}
                            <label for="pa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[p_a][type]",'no',!empty($paType) ? true : false,['id'=>'pa_type_no','class'=>'pa-type','data-type'=>'pa-details'])}}
                            <label for="pa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 pa-details '.$paType}}">
                        <div class="form-group">
                            {{Form::text("oe[p_a][details]",isset($gynecId) && !empty($oe->p_a->details) ? $oe->p_a->details : null,['class'=>'form-control','placeholder'=>'UT Details'])}}
                        </div>
                    </div>
                    
                </div>
                @php
                    $left = isset($gynecId) && in_array('left',!empty($oe->ovary->type) ? $oe->ovary->type : []) ? '' : 'd-none';
                    $right = isset($gynecId) && in_array('right',!empty($oe->ovary->type) ? $oe->ovary->type : []) ? '' : 'd-none';
                    $ovaryLeftType = isset($gynecId) && !empty($oe->ovary->left->type) && $oe->ovary->left->type == '2' ? '' : 'd-none';
                    $ovaryRightType = isset($gynecId) && !empty($oe->ovary->right->type) && $oe->ovary->right->type == '2' ? '' : 'd-none';
                @endphp
                <div class="{{'row pa-details unmarried-data '.$utersDetail}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Ovary :
                        </label>
                    </div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('oe[ovary][type][]','right',in_array('right',isset($gynecId) && !empty($oe->ovary->type) ? $oe->ovary->type : []),['id'=>'right','class'=>'plan-management'])}}
                            <label for="right">
                                Right
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 right-details'}}">
                        <div class="form-group">
                            {{Form::select("oe[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],isset($gynecId) && !empty($oe->ovary->right->type) ? $oe->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-6 right-details'}}">
                        <div class="row">
                            <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type mt-1 '.$ovaryRightType}}">
                                {{Form::select("oe[ovary][right][details][]",$rightOvaryData,isset($gynecId) && !empty($oe->ovary->right->details) ? $oe->ovary->right->details : null,[
                                    'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                    'placeholder'=>'Abnormal Details',
                                    'id' => 'oe_ovary_right_details',
                                    'multiple'=>true
                                ])}}
                            </div>
                            <div class="{{'col-md-6 complain-multi ovary-right-abnormal-type '.$ovaryRightType}}">
                                <div class="row edit_oe_ovary_right_details">
                                    @if (isset($oe->ovary->right->updated_details))
                                        @foreach ($oe->ovary->right->updated_details as $key => $value)
                                        @if(isset($oe->ovary->right->details[$key]))
                                            <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key]) . '_right' }}">
                                                {{Form::text('oe[ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                    'class' => 'form-control edited_oe_ovary_right_details',
                                                    'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key])
                                                ])}}
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{'row pa-details unmarried-data '.$utersDetail}}">
                    <div class="col-md-2"></div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('oe[ovary][type][]','left',in_array('left',isset($gynecId) &&!empty($oe->ovary->type) ? $oe->ovary->type : []),['id'=>'left','class'=>'plan-management'])}}
                            <label for="left">
                                Left
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 left-details'}}">
                        <div class="form-group">
                            {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],isset($gynecId) && !empty($oe->ovary->left->type) ? $oe->ovary->left->type : null,[
                                'class'=>'form-control select-padding-0 abnormal',
                                'data-type'=>'ovary-left-abnormal-type'
                            ])}}
                        </div>
                    </div>
                    <div class="{{'col-md-6 left-details'}}">
                        <div class="row">
                            <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type '.$ovaryLeftType}} ">
                                {{Form::select("oe[ovary][left][details][]",$leftOvaryData,!empty($oe->ovary->left->details) ? $oe->ovary->left->details : null,[
                                    'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                    'placeholder'=>'Abnormal Details',
                                    'id' => 'oe_ovary_left_details',
                                    'multiple'=>true
                                ])}}
                            </div>
                            <div class="{{'col-md-6 complain-multi ovary-left-abnormal-type '.$ovaryLeftType}}">
                                <div class="row edit_oe_ovary_left_details">
                                    @if (isset($oe->ovary->left->updated_details))
                                        @foreach ($oe->ovary->left->updated_details as $key => $value)
                                            @if(isset($oe->ovary->left->details[$key]))
                                                <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key]) . '_left' }}">
                                                    {{Form::text('oe[ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                        'class' => 'form-control edited_oe_ovary_left_details',
                                                        'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key])
                                                    ])}}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $leType = !empty($oe->l_s->type) && $oe->l_s->type == 'yes' ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            L/E :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[l_s][type]",'yes',!empty($leType) ? false : true,['id'=>'ls_type_yes','class'=>'gynec-yes-no-status','data-type'=>'ls-details'])}}
                            <label for="ls_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[l_s][type]",'no',!empty($leType) ? true : false,['id'=>'ls_type_no','class'=>'gynec-yes-no-status','data-type'=>'ls-details'])}}
                            <label for="ls_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 ls-details '.$leType}}">
                        <div class="form-group">
                            {{Form::text("oe[l_s][details]",!empty($oe->l_s->details) ? $oe->l_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            P/S:
                        </label>
                    </div>
                    @php
                        $psType = !empty($oe->p_s->type) && $oe->p_s->type == 'yes' ? '' : 'd-none';
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[p_s][type]",'yes',isset($gynecId) && !empty($psType) ? false : true,['id'=>'ps_type_yes','class'=>'gynec-yes-no-status','data-type'=>'ps-details'])}}
                            <label for="ps_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[p_s][type]",'no',isset($gynecId) && !empty($psType) ? true : false,['id'=>'ps_type_no','class'=>'gynec-yes-no-status','data-type'=>'ps-details'])}}
                            <label for="ps_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 ps-details '.$psType}}">
                        <div class="form-group">
                            {{Form::text("oe[p_s][details]",isset($gynecId) && !empty($oe->p_s->details) ? $oe->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-1 ps-details '.$psType}}">
                        <label class="vertical-form-label pr-0">
                            Cervix :
                        </label>
                    </div>
                    <div class="{{'col-md-3 ps-details '.$psType}}">
                        <div class="form-group">
                            {{Form::text("oe[cervix][details]",isset($gynecId) && !empty($oe->cervix->details) ? $oe->cervix->details : null,['class'=>'form-control','placeholder'=>'Cervix Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Right Tube :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::text("oe[right_tube]",isset($gynecId) && isset($oe->right_tube) ? $oe->right_tube : null,['class'=>'form-control','placeholder'=>'Right Tube Details'])}}
                        </div>
                    </div>
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Left Tube :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::text("oe[left_tube]",isset($gynecId) && isset($oe->left_tube) ? $oe->left_tube : null,['class'=>'form-control','placeholder'=>'Left Tube Details'])}}
                        </div>
                    </div>
                   
                </div>
                @php
                    $tvsType = !empty($oe->tvs->type) && $oe->tvs->type == 'yes' ? '' : 'd-none';
                    $breastType = !empty($oe->breast->type) && $oe->breast->type == 'yes' ? '' : 'd-none';
                    $tvsDisplay = isset($oh->married_type) && $oh->married_type == 'married' ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Breast :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[breast][type]",'yes',isset($breastType) && empty($breastType) ? true:false,['id'=>'breast_type_yes','class'=>'gynec-yes-no-status','data-type'=>'breast-details'])}}
                            <label for="breast_type_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[breast][type]",'no',isset($breastType) && !empty($breastType) ? true:false,['id'=>'breast_type_no','class'=>'gynec-yes-no-status','data-type'=>'breast-details'])}}
                            <label for="breast_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-sm-3 breast-details '.$breastType}}">
                        <div class="form-group">
                            {{Form::text("oe[breast][right]", isset($oe->breast->right) ? $oe->breast->right:null,['class'=>'form-control','placeholder'=>'Right'])}}
                        </div>
                    </div>
                    <div class="col-sm-3 breast-details d-none">
                        <div class="form-group">
                            {{Form::text("oe[breast][left]",isset($oe->breast->left) ? $oe->breast->left:null,['class'=>'form-control','placeholder'=>'Left'])}}
                        </div>
                    </div>
                </div>

                <div class="{{'row married-data '.$tvsDisplay}}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            TVS :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[tvs][type]",'yes',!empty($tvsType) ? false : true,['id'=>'tvs_type_yes','class'=>'gynec-yes-no-status','data-type'=>'tvs-details'])}}
                            <label for="tvs_type_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[tvs][type]",'no',!empty($tvsType) ? true : false,['id'=>'tvs_type_no','class'=>'gynec-yes-no-status','data-type'=>'tvs-details'])}}
                            <label for="tvs_type_no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                    
                @if(isset($oh->married_type) && $oh->married_type == 'married')
                    <div class="{{'row tvs-details married-data '.$tvsType}}">
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
                        {{-- <div class="{{'col-md-2 uterus-abnormal-type '.$uterusType}}">
                            <div class="form-group">
                                {{Form::text("oe[uterus][details]",!empty($oe->uterus->details) ? $oe->uterus->details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                            </div>
                        </div> --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                {{Form::text("oe[uterus][details]",'',['class'=>'form-control','placeholder'=>'Uterus Details'])}}
                            </div>
                        </div>
                        {{-- <span class="{{'col-md-1 p-2 uterus-abnormal-type '.$uterusType}}">LG</span> --}}
                    </div>
                    <div class="{{'row tvs-details married-data '.$tvsType}}">
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
                    <div class="{{'row tvs-details married-data '.$tvsType}}">
                        <div class="col-md-1"></div>
                        <div class="col-md-1 pr-0">
                            <label class="vertical-form-label pr-0">
                                Adnexa :
                            </label>
                        </div>
                        @php
                            $adnexaType = !empty($oe->adnexa->type) && $oe->adnexa->type == 'yes' ? '' : 'd-none';
                        @endphp
                        <div class="col-sm-2">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[adnexa][type]",'yes',!empty($adnexaType) ? false : true,['id'=>'adnexa_type_yes','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                                <label for="adnexa_type_yes">
                                    Yes
                                </label>

                                {{Form::radio("oe[adnexa][type]",'no',!empty($adnexaType) ? true : false,['id'=>'adnexa_type_no','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                                <label for="adnexa_type_no">
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="{{'col-md-5 adnexa-details '.$adnexaType}}">
                            <div class="form-group">
                                {{Form::text("oe[adnexa][details]",!empty($oe->adnexa->details) ? $oe->adnexa->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                            </div>
                        </div>
                    </div>
                    @php
                        $left = in_array('left',!empty($oe->ovary->type) ? $oe->ovary->type : []) ? '' : 'd-none';
                        $right = in_array('right',!empty($oe->ovary->type) ? $oe->ovary->type : []) ? '' : 'd-none';
                        $ovaryLeftType = !empty($oe->ovary->left->type) && $oe->ovary->left->type == '2' ? '' : 'd-none';
                        $ovaryRightType = !empty($oe->ovary->right->type) && $oe->ovary->right->type == '2' ? '' : 'd-none';
                    @endphp
                    <div class="{{'row tvs-details married-data '.$tvsType}}">
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
                        <div class="{{'col-md-6 right-details'}}">
                            <div class="row">
                                <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type mt-1 '.$ovaryRightType}}">
                                    {{Form::select("oe[ovary][right][details][]",$rightOvaryData,!empty($oe->ovary->right->details) ? $oe->ovary->right->details : null,[
                                        'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                        'placeholder'=>'Abnormal Details',
                                        'id' => 'oe_ovary_right_details',
                                        'multiple'=>true
                                    ])}}
                                </div>
                                <div class="{{'col-md-6 complain-multi ovary-right-abnormal-type '.$ovaryRightType}}">
                                    <div class="row edit_oe_ovary_right_details">
                                        @if (isset($oe->ovary->right->updated_details))
                                            @foreach ($oe->ovary->right->updated_details as $key => $value)
                                            @if(isset($oe->ovary->right->details[$key]))
                                                <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key]) . '_right' }}">
                                                    {{Form::text('oe[ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                        'class' => 'form-control edited_oe_ovary_right_details',
                                                        'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key])
                                                    ])}}
                                                </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="{{'row tvs-details married-data '.$tvsType}}">
                        <div class="col-md-2"></div>
                        <div class="col-md-1">
                            <div class="checkbox">
                                {{Form::checkbox('oe[ovary][type][]','left',in_array('left',isset($gynecId) && !empty($oe->ovary->type) ? $oe->ovary->type : []),['id'=>'left','class'=>'plan-management'])}}
                                <label for="left">
                                    Left
                                </label>
                            </div>
                        </div>
                        <div class="{{'col-md-3 left-details'}}">
                            <div class="form-group">
                                {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],isset($gynecId) && !empty($oe->ovary->left->type) ? $oe->ovary->left->type : null,[
                                    'class'=>'form-control select-padding-0 abnormal',
                                    'data-type'=>'ovary-left-abnormal-type'
                                ])}}
                            </div>
                        </div>
                        <div class="{{'col-md-6 left-details'}}">
                            <div class="row">
                                <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type '.$ovaryLeftType}} ">
                                    {{Form::select("oe[ovary][left][details][]",$leftOvaryData,isset($gynecId) && !empty($oe->ovary->left->details) ? $oe->ovary->left->details : null,[
                                        'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                        'placeholder'=>'Abnormal Details',
                                        'id' => 'oe_ovary_left_details',
                                        'multiple'=>true
                                    ])}}
                                </div>
                                <div class="{{'col-md-6 complain-multi ovary-left-abnormal-type '.$ovaryLeftType}}">
                                    <div class="row edit_oe_ovary_left_details">
                                        @if (isset($oe->ovary->left->updated_details))
                                            @foreach ($oe->ovary->left->updated_details as $key => $value)
                                                @if(isset($oe->ovary->left->details[$key]))
                                                    <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key]) . '_left' }}">
                                                        {{Form::text('oe[ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                            'class' => 'form-control edited_oe_ovary_left_details',
                                                            'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key])
                                                        ])}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif
@if($isGynec == 1)
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#oe_tab" href="#oe_tab" aria-expanded="false"
                    aria-controls="past-history">3. O/E</a></h4>
        </div>
        <div id="oe_tab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body" id="parent">
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            TVS :
                        </label>
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Uterus:
                        </label>
                    </div>
                    @php
                        $utDetails = !empty($oe->gynec_tvs->type) && $oe->gynec_tvs->type == 'yes' ? '' : 'd-none';
                        $psDetails = !empty($oe->gynec_p_s->type) && $oe->gynec_p_s->type == 'yes' ? '' : 'd-none';
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[gynec_tvs][type]",'yes',!empty($utDetails) ? false : true,['id'=>'gynec_tvs_type_yes','class'=>'gynec-yes-no-status','data-type'=>'ut-details'])}}
                            <label for="gynec_tvs_type_yes">
                                Yes
                            </label>
                            {{Form::radio("oe[gynec_tvs][type]",'no',!empty($utDetails) ? true : false,['id'=>'gynec_tvs_type_no','class'=>'gynec-yes-no-status','data-type'=>'ut-details'])}}
                            <label for="gynec_tvs_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-5 ut-details '.$utDetails}}">
                        <div class="form-group">
                            {{Form::text("oe[gynec_ut][details]",!empty($oe->gynec_ut->details) ? $oe->gynec_ut->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Endometrial Cavity :
                        </label>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            {{Form::text("oe[gynec_endometrial_cavity][details]",!empty($oe->gynec_endometrial_cavity->details) ? $oe->gynec_endometrial_cavity->details : null,['class'=>'form-control','placeholder'=>'Endometrial Cavity'])}}
                        </div>
                    </div>
                </div>
                <div class="row unmarried-data">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            P/S :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[gynec_p_s][type]",'yes',!empty($psDetails) ? false : true,['id'=>'gynec_ps_type_yes','class'=>'gynec-yes-no-status','data-type'=>'gynec-ps-details'])}}
                            <label for="gynec_ps_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[gynec_p_s][type]",'no',!empty($psDetails) ? true : false,['id'=>'gynec_ps_type_no','class'=>'gynec-yes-no-status','data-type'=>'gynec-ps-details'])}}
                            <label for="gynec_ps_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-5 gynec-ps-details '.$psDetails}}">
                        <div class="form-group">
                            {{Form::text("oe[gynec_p_s][details]",!empty($oe->gynec_p_s->details) ? $oe->gynec_p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Adnexa :
                        </label>
                    </div>
                    @php
                        $adnexaType = !empty($oe->adnexa->type) && $oe->adnexa->type == 'yes' ? '' : 'd-none';
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("oe[adnexa][type]",'yes',!empty($adnexaType) ? false : true,['id'=>'adnexa_type_yes','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                            <label for="adnexa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("oe[adnexa][type]",'no',!empty($adnexaType) ? true : false,['id'=>'adnexa_type_no','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                            <label for="adnexa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-5 adnexa-details '.$adnexaType}}">
                        <div class="form-group">
                            {{Form::text("oe[adnexa][details]",!empty($oe->adnexa->details) ? $oe->adnexa->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Vitals :
                        </label>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">B.P : &nbsp;</span>
                            {{Form::text("oe[gynec_le][bp]",!empty($oe->gynec_le->bp) ? $oe->gynec_le->bp : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <span class="col-md-1 p-2">MMHG</span>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Temp : &nbsp;</span>
                            {{Form::text("oe[gynec_le][temp]",!empty($oe->gynec_le->temp) ? $oe->gynec_le->temp : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-addon">Pulse : &nbsp;</span>
                            {{Form::text("oe[gynec_le][pulse]",!empty($oe->gynec_le->pulse) ? $oe->gynec_le->pulse : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <span class="col-md-1 p-2">/ Min</span>
                </div>
            </div>
        </div>
    </div>
@endif
@if($isGynec != 1)
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#plan_management" href="#plan_management" aria-expanded="false"
                    aria-controls="past-history">7. Plan Of Management</a></h4>
        </div>
        <div id="plan_management" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                @php
                    $medicalData = !empty($planOfManagement->plan_of_management_data) ? $planOfManagement->plan_of_management_data : [];
                    $surgicallyDataStatus = in_array('surgically',$medicalData) ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('plan_of_management[plan_of_management_data][]','medically',isset($gynecId) ? in_array('medically',$medicalData) : false,['id'=>'medically','class'=>'plan-management','data-id'=>'medically'])}}
                            <label for="medically">
                                Medically
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('plan_of_management[plan_of_management_data][]','surgically',isset($gynecId) ? in_array('surgically',$medicalData) : false,['id'=>'surgically-type','class'=>'plan-management','data-id'=>'surgically-type'])}}
                            <label for="surgically-type">
                                Surgically
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 complain-multi surgically-details '.$surgicallyDataStatus}}">
                        <div class="form-group">
                            {{Form::select('plan_of_management[surgically_details][]',$surgicallyData,isset($gynecId) && !empty($planOfManagement->surgically_details) ? $planOfManagement->surgically_details : null,['class'=>'form-control co-value co_value_data surgically_type','placeholder'=>'Surgically Type','multiple'=>true])}}
                        </div> 
                            <span class="surgically-type-error form-error-msg">
                            </span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endif
@if($isGynec != 1)
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#investigation" href="#investigation" aria-expanded="false"
                    aria-controls="investigation">8. Investigation</a></h4>
        </div>
        <div id="investigation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            USG :
                        </label>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[usg][date]",!empty($investigation->usg->date) ? \Carbon\Carbon::parse($investigation->usg->date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-3'}}">
                        <div class="form-group">
                            {{Form::text("investigation[usg][usg_details]",!empty($investigation->usg->usg_details) ? $investigation->usg->usg_details : null,['class'=>'form-control','placeholder'=>'USG Details'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Hystroscopy :
                        </label>
                    </div>
                    @php
                        $hystroscopyClass = !empty($investigation) && isset($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'yes' ? true : false;
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[hystroscopy][type]",'yes',$hystroscopyClass,['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type gynec-yes-no-status','data-type'=>'hystroscopy-type'])}}
                            <label for="hystroscopy_type_yes">
                                Yes
                            </label>
                            {{Form::radio("investigation[hystroscopy][type]",'no',!empty($investigation) && isset($investigation->hystroscopy) && !empty($investigation->hystroscopy->type) && $investigation->hystroscopy->type == 'no' ? true : false,['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type gynec-yes-no-status','data-type'=>'hystroscopy-type'])}}
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
                            {{Form::select("investigation[hystroscopy][finding_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) && isset($investigation->hystroscopy) ? $investigation->hystroscopy->finding_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'finding-type'])}}
                        </div>
                    </div>
                    @php
                        $fType = !empty($investigation) && isset($investigation->hystroscopy) && $investigation->hystroscopy->finding_type == '1' ? 'd-none' : '';
                    @endphp
                    <div class="{{'col-sm-3 finding-type hystroscopy-type-abnormal '.$fType.' '.$hystroscopyClassName}}">
                        <div class="form-group">
                            {{Form::text("investigation[hystroscopy][abnormal_details]",!empty($investigation) && isset($investigation->hystroscopy) ? $investigation->hystroscopy->abnormal_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
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
                            {{Form::text("investigation[hystroscopy][finding_date]",!empty($investigation) && isset($investigation->hystroscopy) ? \Carbon\Carbon::parse($investigation->hystroscopy->finding_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{Form::text("investigation[hystroscopy][finding_details]",!empty($investigation) && isset($investigation->hystroscopy) ? $investigation->hystroscopy->finding_details : null,['class'=>'form-control date','placeholder'=>'Details'])}}
                        </div>
                    </div>
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
                            {{Form::text("investigation[laproscopy][finding_date]",!empty($investigation) && isset($investigation->laproscopy) ? \Carbon\Carbon::parse($investigation->laproscopy->finding_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    @php
                        $investigationType = !empty($investigation) && isset($investigation->laproscopy) && !empty($investigation->laproscopy->type) && $investigation->laproscopy->type == 'yes' ? true : false;
                    @endphp
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[laproscopy][type]",'yes',$investigationType,['id'=>'laproscopy_type_yes','class'=>'gynec-yes-no-status','data-type'=>'laproscopy-status-type'])}}
                            <label for="laproscopy_type_yes">
                                Yes
                            </label>

                            {{Form::radio("investigation[laproscopy][type]",'no',!empty($investigation) && isset($investigation->laproscopy) && !empty($investigation->laproscopy->type) && $investigation->laproscopy->type == 'no' ?  true : false,['id'=>'laproscopy_type_no','class'=>'gynec-yes-no-status','data-type'=>'laproscopy-status-type'])}}
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
                            {{Form::select("investigation[laproscopy][laproscopy_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) && isset($investigation->laproscopy) ? $investigation->laproscopy->laproscopy_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'laproscopy-type'])}}
                        </div>
                    </div>
                </div>
                @php
                    // laproscopy
                    $laproscopyType = !empty($investigation) && isset($investigation->laproscopy) && $investigation->laproscopy->laproscopy_type == 2 ? '' : 'd-none';
                    $lrtTube =  !empty($investigation) && isset($investigation->laproscopy) && $investigation->laproscopy->rt_tube_type == 2 ? '' : 'd-none';
                    $luterus  = !empty($investigation) && isset($investigation->laproscopy) && $investigation->laproscopy->uterus_type == 2 ? '' : 'd-none';
                    $llttube  = !empty($investigation) && isset($investigation->laproscopy) && $investigation->laproscopy->lt_tube_type == 2 ? '' : 'd-none';
                    // // hcg data
                    // $hcgStatus = !empty($investigation) && !empty($investigation->hcg->type) && $investigation->hcg->type == 'yes' ? true : false;
                    // $hStatus =  $hcgStatus ? '' : 'd-none';
                    // $hcgType = !empty($investigation) && $investigation->hcg->laproscopy_type == 2 ? '' : 'd-none';
                    // $hrtTube = !empty($investigation) && $investigation->hcg->rt_tube_type == 2 ? '' : 'd-none';
                    // $huterus  = !empty($investigation) && $investigation->hcg->uterus_type == 2 ? '' : 'd-none';
                    // $hlttube  = !empty($investigation) && $investigation->hcg->lt_tube_type == 2 ? '' : 'd-none';
                @endphp
                <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType.' '.$investigationTypeClass}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            RT Tube :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select("investigation[laproscopy][rt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) && isset($investigation->laproscopy) ? $investigation->laproscopy->rt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'rt-tube-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 rt-tube-type '.$lrtTube}}">
                        <div class="form-group">
                            {{Form::text("investigation[laproscopy][rt_tube_details]",isset($investigation->laproscopy) &&!empty($investigation->laproscopy->rt_tube_details) ? $investigation->laproscopy->rt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType.' '.$investigationTypeClass}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Uterus :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select("investigation[laproscopy][uterus_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) && isset($investigation->laproscopy) ? $investigation->laproscopy->uterus_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 uterus-type '.$luterus}}">
                        <div class="form-group">
                            {{Form::text("investigation[laproscopy][uterus_details]",isset($investigation->laproscopy) && !empty($investigation->laproscopy->uterus_details) ? $investigation->laproscopy->uterus_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType.' '.$investigationTypeClass}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            LT Tube :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select("investigation[laproscopy][lt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) && isset($investigation->laproscopy) ? $investigation->laproscopy->lt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'lt-tube-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 lt-tube-type '.$llttube}}">
                        <div class="form-group">
                            {{Form::text("investigation[laproscopy][lt_tube_details]",isset($investigation->laproscopy) && !empty($investigation->laproscopy->lt_tube_details) ? $investigation->laproscopy->lt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row laproscopy-type laproscopy-status-type-abnormal '.$laproscopyType.' '.$investigationTypeClass}}">
                    <div class="col-md-1"></div>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other : &nbsp;
                            </span>
                            {{Form::text("investigation[laproscopy][other]",!empty($investigation->laproscopy->other) && isset($investigation->laproscopy) ? $investigation->laproscopy->other : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                {{-- end laproscopy  --}}

                <div class="row">
                    <div class="col-md-2">
                        <label class="vertical-form-label">
                            Report Images :
                        </label>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="report-images"></div>
                </div>
                {{-- begin hcg  --}}
                {{-- <div class="row">
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
                            {{Form::text("investigation[hcg][date]",!empty($investigation->hcg->date) ? \Carbon\Carbon::parse($investigation->hcg->date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[hcg][type]",'yes',$hcgStatus,['id'=>'hcg_laproscopy_type_yes','class'=>'gynec-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                            <label for="hcg_laproscopy_type_yes">
                                Yes
                            </label>

                            {{Form::radio("investigation[hcg][type]",'no',$hcgStatus ? false : true,['id'=>'hcg_laproscopy_type_no','class'=>'gynec-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                            <label for="hcg_laproscopy_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 hcg-laproscopy-status-type '.$hStatus}}">
                        <div class="form-group">
                            {{Form::select("investigation[hcg][laproscopy_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) ? $investigation->hcg->laproscopy_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-laproscopy-type'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal '.$hcgType.' '.$hStatus}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            RT Tube :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select("investigation[hcg][rt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) ? $investigation->hcg->rt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-rt-tube-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 hcg-rt-tube-type '.$hrtTube}}">
                        <div class="form-group">
                            {{Form::text("investigation[hcg][rt_tube_details]",!empty($investigation->hcg->rt_tube_details) ?  $investigation->hcg->rt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal '.$hcgType.' '.$hStatus}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Uterus :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select("investigation[hcg][uterus_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) ? $investigation->hcg->uterus_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-uterus-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 hcg-uterus-type '.$huterus}}">
                        <div class="form-group">
                            {{Form::text("investigation[hcg][uterus_details]",!empty($investigation->hcg->uterus_details) ? $investigation->hcg->uterus_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal '.$hcgType.' '.$hStatus}}">
                    <div class="col-md-1"></div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            LT Tube :
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select("investigation[hcg][lt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($investigation) ? $investigation->hcg->lt_tube_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-lt-tube-type'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-4 hcg-lt-tube-type '.$hlttube}}">
                        <div class="form-group">
                            {{Form::text("investigation[hcg][lt_tube_details]",!empty($investigation->hcg->lt_tube_details) ? $investigation->hcg->lt_tube_details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                        </div>
                    </div>
                </div> --}}
                {{-- end hcg  --}}
                <br>
                {{-- <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                CBC : &nbsp;
                            </span>
                            {{Form::text("investigation[cbc]",!empty($investigation->cbc) ? $investigation->cbc : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Urine : &nbsp;
                            </span>
                            {{Form::text("investigation[urine]",!empty($investigation->urine) ? $investigation->urine : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                RBS : &nbsp;
                            </span>
                            {{Form::text("investigation[rbs]",!empty($investigation->rbs) ? $investigation->rbs : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                HIV : &nbsp;
                            </span>
                            {{Form::text("investigation[hiv]",!empty($investigation->hiv) ? $investigation->hiv : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Hbs Ag : &nbsp;
                            </span>
                            {{Form::text("investigation[hbs_ag]",!empty($investigation->hbs_ag) ? $investigation->hbs_ag : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[date_1]",!empty($investigation->date_1) ? \Carbon\Carbon::parse($investigation->date_1)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                </div>
                <br> --}}

                <div class="row">
                    {{-- <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                TSH : &nbsp;
                            </span>
                            {{Form::text("investigation[tsh]",!empty($investigation->tsh) ? $investigation->tsh : null,['class'=>'form-control'])}}
                        </div>
                    </div> --}}
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
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[date_2]",!empty($investigation->date_2) ? \Carbon\Carbon::parse($investigation->date_2)->format('D d M Y') : null,['class'=>'form-control datetimepicker date'])}}
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                LH : &nbsp;
                            </span>
                            {{Form::text("investigation[lh]",!empty($investigation->lh) ? $investigation->lh : null,['class'=>'form-control'])}}
                        </div>
                    </div> --}}
                </div>
                {{-- <div class="row">
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
                </div> --}}
                @php
                    $investigationValue = !empty($investigation->investigation_details) ? (array)$investigation->investigation_details : [];
                    $investigation = !empty($investigation) ? $investigation : [];
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
                <div class="row mt-1">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_extra]",isset($investigation->investigation_extra) && !empty($investigation->investigation_extra) ? $investigation->investigation_extra : '',['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($isGynec == 1)
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#plan-investigation" href="#plan-investigation" aria-expanded="false"
                    aria-controls="past-history">4. Investigation</a></h4>
        </div>
        <div id="plan-investigation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body" id="parent">
                @php
                    $ancProfileStatus = !empty($investigation->anc_profile_type) && $investigation->anc_profile_type == 'yes' ? '' : 'd-none';
                @endphp
                <div class="row anc-profile">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0 pre-operative-label">
                            ANC Profile :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[anc_profile_type]",'yes',!empty($ancProfileStatus) ? false : true,['id'=>'anc_profile_type_yes','class'=>'anc-profile-type'])}}
                            <label for="anc_profile_type_yes">
                                Yes
                            </label>

                            {{Form::radio("investigation[anc_profile_type]",'no',!empty($ancProfileStatus) ? true : false,['id'=>'anc_profile_type_no','class'=>'anc-profile-type'])}}
                            <label for="anc_profile_type_no">
                                No
                            </label>
                        </div>
                    </div>

                    <div class="{{'col-md-3 anc-data '.$ancProfileStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_anc_date]",!empty($investigation->investigation_anc_date) ? \Carbon\Carbon::parse($investigation->investigation_anc_date)->format('D d M Y') : null,['class'=>'form-control datetimepicker date f-date'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-3 anc-data '.$ancProfileStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Blood Group : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_blood_group]",!empty($investigation->investigation_blood_group) ? $investigation->investigation_blood_group : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-sm-3 anc-data '.$ancProfileStatus}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                RBS : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_anc_rbs]",!empty($investigation->investigation_anc_rbs) ? $investigation->investigation_anc_rbs : null ,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row anc-data '.$ancProfileStatus}}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            CBC MP :
                        </label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_cbc_mp][status]",['1'=>"WNL",'2'=>"Abnormal"],!empty($investigation->investigation_cbc_mp->status) ? $investigation->investigation_cbc_mp->status : null,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-data','placeholder'=>'Select CBC MB Type'])}}
                        </div>
                    </div>
                    @php
                        $cbcType = !empty($investigation->investigation_cbc_mp->status) && $investigation->investigation_cbc_mp->status == 2 ? '' : 'd-none';
                        $urineType = !empty($investigation->investigation_urine->status) && $investigation->investigation_urine->status == 2 ? '' : 'd-none';
                        $urineSubType = !empty($investigation->investigation_urine->type) && $investigation->investigation_urine->type == 'present' ?  '' : 'd-none';
                    @endphp
                    <div class="{{'col-md-3 cbc-mb-type-data '.$cbcType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Aneamia : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_cbc_mp][aneamia]",!empty($investigation->investigation_cbc_mp->aneamia) ? $investigation->investigation_cbc_mp->aneamia : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 cbc-mb-type-data '.$cbcType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Leacocytosis : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_cbc_mp][leacocytosis]",!empty($investigation->investigation_cbc_mp->leacocytosis) ? $investigation->investigation_cbc_mp->leacocytosis : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row anc-data '.$ancProfileStatus}}">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Urine :
                        </label>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("investigation[investigation_urine][status]",['1'=>"WNL",'2'=>"Abnormal"],!empty($investigation->investigation_urine->status) ? $investigation->investigation_urine->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-type-data','placeholder'=>'Select Urine Type'])}}
                        </div>
                    </div>
                    <div class="col-md-1 pr-0 urine-type-data d-none">
                        <label class="vertical-form-label pr-0">
                            Puccell :
                        </label>
                    </div>
                    <div class="{{'col-sm-2 urine-type-data '.$urineType}}">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[investigation_urine][type]",'present',!empty($investigation->investigation_urine->type) && $investigation->investigation_urine->type == 'present' ?  true : false,['id'=>'investigation_urine_present','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                            <label for="investigation_urine_present">
                                Present
                            </label>

                            {{Form::radio("investigation[investigation_urine][type]",'absent',!empty($investigation->investigation_urine->type) && $investigation->investigation_urine->type == 'absent' ?  true : false,['id'=>'investigation_urine_absent','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                            <label for="investigation_urine_absent">
                                Absent
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3 present-data urine-type-data-data '.$urineType}}">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Puscell : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_urine][puscell]",!empty($investigation->investigation_urine->puscell) ? $investigation->investigation_urine->puscell : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="{{'col-md-3 urine-type-data '.$urineType}}">
                        <div class="{{'input-group '.$urineSubType}}">
                            <span class="input-group-addon">
                                Urine Albumine : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_urine][urine_albumine]",!empty($investigation->investigation_urine->urine_albumine) ? $investigation->investigation_urine->urine_albumine : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="{{'row anc-data '.$ancProfileStatus}}">
                    {{-- anc profile --}}
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HIV :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("investigation[anc_hiv]",'positive',!empty($investigation->anc_hiv) && $investigation->anc_hiv == 'positive' ? true : false,['id'=>'anc_profile_hiv_positive','class'=>'anc-profile-hiv'])}}
                            <label for="anc_profile_hiv_positive">
                                Positive
                            </label>

                            {{Form::radio("investigation[anc_hiv]",'negative',!empty($investigation->anc_hiv) && $investigation->anc_hiv == 'negative' ? true : false,['id'=>'anc_profile_hiv_nagative','class'=>'anc-profile-hiv'])}}
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
                            {{Form::radio("investigation[anc_hbsag]",'positive',!empty($investigation->anc_hbsag) && $investigation->anc_hbsag == 'positive' ? true : false,['id'=>'anc_profile_hbsag_positive','class'=>'anc-profile-hbsag'])}}
                            <label for="anc_profile_hbsag_positive">
                                Positive
                            </label>

                            {{Form::radio("investigation[anc_hbsag]",'negative',!empty($investigation->anc_hbsag) && $investigation->anc_hbsag == 'negative' ? true : false,['id'=>'anc_profile_hbsag_nagative','class'=>'anc-profile-hbsag'])}}
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
                            {{Form::radio("investigation[anc_vdrl]",'positive',!empty($investigation->anc_vdrl) && $investigation->anc_vdrl == 'positive' ? true : false,['id'=>'anc_profile_vdrl_positive','class'=>'anc-profile-vdrl'])}}
                            <label for="anc_profile_vdrl_positive">
                                Positive
                            </label>

                            {{Form::radio("investigation[anc_vdrl]",'negative',!empty($investigation->anc_vdrl) && $investigation->anc_vdrl == 'negative' ? true : false,['id'=>'anc_profile_vdrl_nagative','class'=>'anc-profile-vdrl'])}}
                            <label for="anc_profile_vdrl_nagative">
                                Negative
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="{{'mb-2 anc-data '.$ancProfileStatus}}">
                    <div class="anc-images-data"></div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("investigation[investigation_extra]",isset($investigation->investigation_extra) && !empty($investigation->investigation_extra) ? $investigation->investigation_extra : '',['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

 <!-- Treatment history  -->
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingThree_1">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                aria-controls="past-history">{{$isGynec == 1 ? 5 : 9}}. Treatment</a></h4>
    </div>
    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
        <div class="panel-body" id="parent">
            <div class="row">
                <div class="col-md-2 ipd-detail">
                    <label for="termination">
                        Admission for
                    </label>
                </div>
                <div class="col-md-2">
                    <div class="form-group">

                        {{Form::select("plan_of_management[termination_type]",[''=>'Select Reason','Obseravation'=>"Obseravation",'Surgery'=>"Surgery"],isset($gynecId) && !empty($planOfManagement->termination_type) ? $planOfManagement->termination_type : null,['class'=>'form-control termination_type'])}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">
                            Admission Date : &nbsp;
                        </span>
                        {{Form::text("plan_of_management[surgically_date]",isset($gynecId) && !empty($planOfManagement->surgically_date) ? $planOfManagement->surgically_date : null,['class'=>'form-control datetimepicker surgically_date'])}}
                    </div>
                    <span class="surgically-date-error form-error-msg"></span>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">
                            Admission Time : &nbsp;
                        </span>
                        {{Form::text("plan_of_management[surgically_time]",isset($gynecId) && !empty($planOfManagement->surgically_time) ? $planOfManagement->surgically_time : null,['class'=>'form-control timepicker surgically_time'])}}
                    </div>
                    <span class="surgically-time-error form-error-msg"></span>
                </div>
            </div>
            <div class="row treatment-data" id="t_data_1">
                    <div class="col-md-2 pr-0">
                            <label class="vertical-form-label pr-0">
                                Select Medicine :
                            </label>
                        </div>
                <div class="col-md-9 complain-multi medicine-picker">
                    {{Form::select("treatment[medicinedata][]",$medicines,!empty($medicineKey) ? $medicineKey : null,['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co','placeholder'=> "Enter medicine name"])}}
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
                        <div class='col-md-2'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Day :</span>
                                {{Form::number('treatment['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-group'>
                                <!-- <span class='input-group-addon'>Day :</span> -->
                                {{Form::text('treatment['.$mId.'][note]',isset($row->note) ? $row->note : '',['class'=>'form-control','placeholder'=>'Note'])}}
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
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">
                            Follow Up : &nbsp;
                        </span>
                        {{Form::text("ho[follow_up]",!empty($ho->follow_up) ? $ho->follow_up : null,['class'=>'form-control datetimepicker followup followup-date'])}}
                    </div>
                    <span class="gsac-no-data-followup form-error-msg"></span>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        {{Form::textarea("ho[remark]",!empty($ho->remark) ? $ho->remark : null,['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('remark')}}
                    </span>
                    
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        {{Form::textarea("ho[pt_remark]",isset($ho->pt_remark) && !empty($ho->pt_remark) ? $ho->pt_remark : null,['class'=>'form-control no-resize','placeholder'=>"Patient'sRemark",'rows'=>'5'])}}
                    </div>
                    {{-- <label>Patient'sRemark:</label>
                    <div class="input-group">
                        {{Form::textarea("data[pt_remark]",!empty($ivfData->pt_remark) ? $ivfData->pt_remark : '',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'3'])}}
                    </div> --}}
                </div>
                {{Form::hidden('gynec_id',!empty($gynecId) ? encrypt($gynecId) : null,['class'=>'gynecId'])}}
            </div>
        </div>
    </div>
    
</div>
{{-- {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}} --}}

<script type="text/javascript">
    $('.duration-data').selectize({
        create: true,
        sortField: 'text'
    });
</script>
