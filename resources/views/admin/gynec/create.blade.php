@extends('layouts.main')
@section('parentPageTitle', 'Gynec Appointment')
@section('title', 'Add Gynec Appointment')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header d-flex">
                    <div class="col-md-6 col-sm-6">
                        <h2><strong class="text-secondary">{{ucwords($patient->name)}}</strong>
                            @php
                            $careof = (!empty($patient->reference_doctor_id) && isset($referenceDoctor[$patient->reference_doctor_id])) ? $referenceDoctor[$patient->reference_doctor_id] : '';
                            if(!empty($patient->reference_doctor_id) && $patient->reference_doctor_id == '1' )
                            {
                                $careof = !empty($patient->reference_pt_name) ? $patient->reference_pt_name. (!empty($patient->reference_pt_mobile) ? '('.$patient->reference_pt_mobile.')' : '') :'SELF--';
                            }
                        @endphp
                        {{' care of '.$careof}}</h2>
                    </div>
                    <div class="col-md-6 col-sm-6 ">
                        @if($isIvfHistory)
                            <a href="{{URL::to('ivf/history/'.encrypt($patient->id))}}" target="_blank" class="btn btn-primary pull-right">IVF History</a>
                        @endif
                        @if($isAncHistory)
                            <a href="{{URL::to('anc/history/'.encrypt($patient->id))}}" target="_blank" class="btn btn-primary pull-right">ANC History</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Gynec Appointment</strong>
                    </h2>
                    <ul class="header-dropdown">

                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        @if(Session::has('msg'))
                            <div class="alert alert-danger">
                                {{Session::get('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                        @endif
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form anc','files'=>true,'id'=>'gynec-form','enctype'=>'multipart/form-data'])}}
                                {{Form::hidden('patients_id',$patientsId)}}
                                {{Form::hidden('gynec_id','',['class'=>'gynec-id'])}}
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="vertical-form-label pr-0">
                                            Seen By :
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{Form::select('seen_by',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
                                        </div>
                                        <span class="seen-by-error text-danger mb-2"></span>
                                    </div>
                                </div>
                                <!-- patients basic information -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" href="#patients" aria-expanded="false"
                                                                    aria-controls="patients">Patients Basic Information</a> </h4>
                                    </div>
                                    <div id="patients" class="panel-collapse collapse p-info" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Name : &nbsp;</span>
                                                        {{Form::text('name',$patient->name,['class'=>'form-control name'])}}
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
                                                        {{Form::text('code',$patient->code,['class'=>'form-control code','disabled'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('code')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Age : &nbsp;</span>
                                                        {{Form::number("p_info[age]",!empty($patient->age) ? $patient->age : (!empty($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->age : null),['class'=>'form-control age'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('age')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Weight : &nbsp;</span>
                                                        {{Form::number("p_info[weight]",'',['class'=>'form-control weight','id'=>'weight'])}}
                                                    </div>
                                                    <span class="form-error-msg weight">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                               
                                                <div class="{{'col-md-6'}}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Mobile : &nbsp;</span>
                                                        {{Form::number('mobile_number',$patient->mobile_number,['class'=>'form-control mobile_number'])}}
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
                                                        {{Form::text("p_info[visit_date]",\Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('date')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{Form::select('rd_reference',$referenceDoctor,$patient->reference_doctor_id,['class'=>'form-control select-padding-0 refence-doctor','placeholder'=>' Reference Name'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('rd_reference')}}
                                                    </span>
                                                </div>
                                                @if($patient->reference_doctor_id == 1) <!-- for self reference -->
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Reference Patient Name : &nbsp;</span>
                                                            {{Form::text('ref_pt_name',$patient->reference_pt_name,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Reference Mobile : &nbsp;</span>
                                                            {{Form::number('rd_mobile_number',$patient->getReferenceDoctor['mobile_number'],['class'=>'form-control ref-mobile-number'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('rd_mobile_number')}}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Reference Mobile : &nbsp;</span>
                                                            {{Form::number('rd_mobile_number',$patient->getReferenceDoctor['mobile_number'],['class'=>'form-control ref-mobile-number'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('rd_mobile_number')}}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Residence : &nbsp;</span>
                                                        {{Form::text('residence',$patient->residence,['class'=>'form-control'])}}
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
                                                        {{Form::text('main_area',$patient->main_area,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                            {{$errors->first('main_area')}}
                                                        </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">City : &nbsp;</span>
                                                        {{Form::text('city',$patient->city,['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                            {{$errors->first('city')}}
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
                                                {{Form::hidden('is_gynec',$isGynec)}}
                                                @if($isGynec == 1)
                                                    {{Form::hidden('ho[gynec_type]',!empty($oe->plan_medically_type->type) ? ($oe->plan_medically_type->type == 'surgically' ? 'D & E' : 'MTP KIT') : '')}}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Follow Up Case Of {{!empty($oe->plan_medically_type->type) ? ($oe->plan_medically_type->type == 'surgically' ? 'D & E' : 'MTP KIT') : ''}} : &nbsp;</span>
                                                            {{Form::text("'ho[ho_details]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                @else
                                                <div class='col-md-11 complain-multi duration-value'>
                                                    {{Form::select('ho[ho_details]',$hoData,null,['class'=>'form-control ho-data select-padding-0 duration-data anc-dose-val ho_type_value','placeholder'=>'Select H/O','data-medicine'=>2])}}
                                                    <span class="form-error-msg ho-data-msg">
                                                        {{-- {{$errors->first('ho_details')}} --}}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>

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
                                                    {{Form::select('co[co_type][]',$complaints,'',['class'=>'form-control co-value co_value_data complaint-data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'0','data-medicine'=>1])}}
                                                    <span class="form-error-msg co-value-msg">
                                                        {{$errors->first('since')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Since : &nbsp;</span>
                                                        {{Form::text("co[since]",'',['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('since')}}
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @if($isGynec != 1)
                                    <!-- O/H -->
                                    <div class="panel panel-primary">
                                        <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                                data-parent="#oh" href="#oh" aria-expanded="false"
                                                    aria-controls="oh">3. O/H</a></h4>
                                        </div>
                                        <div id="oh" class="panel-collapse collapse" role="tabpanel"
                                            aria-labelledby="headingThree_1">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oh[married_type]",'married','',[
                                                                'id'=>'married',
                                                                'class'=>'married-type',
                                                            ])}}
                                                            <label for="married">
                                                                Married
                                                            </label>

                                                            {{Form::radio("oh[married_type]",'unmarried','',[
                                                                'id'=>'unmarried',
                                                                'class'=>'married-type',
                                                            ])}}
                                                            <label for="unmarried">
                                                                Unmarried
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="married-data d-none">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">First Marriage Life : &nbsp;</span>
                                                                {{Form::text("oh[first_marriage_life]",'',['class'=>'form-control'])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('marriage_life')}}
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">PLED : &nbsp;</span>
                                                                {{Form::text("oh[pled]",'',['class'=>'form-control'])}}
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
                                                                {{Form::radio("oh[upt_type]",'positive','',[
                                                                    'id'=>'positive',
                                                                    'class'=>'upt-type',
                                                                ])}}
                                                                <label for="positive">
                                                                    Positive
                                                                </label>

                                                                {{Form::radio("oh[upt_type]",'negative','',[
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
                                                                {{Form::text('oh[upt_details]','',[
                                                                    'class'=>'form-control upt_details',
                                                                    'placeholder' => 'UPT Details'
                                                                ])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 d-none upt_details_label">
                                                            <label class="vertical-form-label pr-0">
                                                                Days Before
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- for child -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                {{Form::select("oh[child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],'',['class'=>'form-control child-no select-padding-0','placeholder'=>'Child No'])}}
                                                            </div>
                                                                {{$errors->first('child_no')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row child-data-parent d-none">
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                H/O :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[child][child_data][1][ho_term]",'full','',['id'=>'full'])}}
                                                                <label for="full">
                                                                    Fullterm
                                                                </label>

                                                                {{Form::radio("oh[child][child_data][1][ho_term]",'pre','',['id'=>'pre'])}}
                                                                <label for="pre">
                                                                    Preterm
                                                                </label>

                                                                {{-- {{Form::text("oh[child][child_data][1][ho_term_information]",'',[
                                                                    'placeholder' => 'Term Information',
                                                                    'class'=>'form-control',
                                                                    'id'=>'term_information'
                                                                ])}} --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            {{Form::text("oh[child][child_data][1][ho_term_details]", '', [
                                                                'placeholder' => 'Term Details',
                                                                'class'=>'form-control',
                                                                'id'=>'term_details'
                                                            ])}}
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[child][child_data][1][child_type]",'single','',['id'=>'child_type_single'])}}
                                                                <label for="child_type_single">
                                                                    Single
                                                                </label>
            
                                                                {{Form::radio("oh[child][child_data][1][child_type]",'twins','',['id'=>'child_type_twins'])}}
                                                                <label for="child_type_twins">
                                                                    Twins
                                                                </label>
                                                                {{Form::radio("oh[child][child_data][1][child_type]",'triple','',['id'=>'child_type_triple'])}}
                                                                <label for="child_type_triple">
                                                                    Triple
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            {{Form::select("oh[child][child_data][1][ho_gender][]",['male'=>'Male','female'=>'Female'],'',['class'=>'form-control','data-id'=>'','multiple','title'=>'Select Child Gender'])}}
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
                                                    <div class="row child-data-parent d-none">
                                                        <div class="col-sm-1">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[child][child_data][1][ho_type]",'normal',true,['id'=>'normal'])}}
                                                                <label for="normal">
                                                                    Normal
                                                                </label>

                                                                {{Form::radio("oh[child][child_data][1][ho_type]",'cesarean','',['id'=>'cesarean'])}}
                                                                <label for="cesarean">
                                                                    Cesarean
                                                                </label>

                                                                {{Form::radio("oh[child][child_data][1][ho_type]",'instrumental','',['id'=>'instrumental'])}}
                                                                <label for="instrumental">
                                                                    Instrumental
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[child][child_data][1][ho_birth_type]",'live_health','',['id'=>'live_health','class'=>'health-type','data-id'=>1])}}
                                                                <label for="live_health">
                                                                    Live Health
                                                                </label>

                                                                {{Form::radio("oh[child][child_data][1][ho_birth_type]",'stil_birth','',['id'=>'stil_birth','class'=>'health-type','data-id'=>1])}}
                                                                <label for="stil_birth">
                                                                    Stil Birth
                                                                </label>

                                                                {{Form::radio("oh[child][child_data][1][ho_birth_type]",'expired','',['id'=>'expired','class'=>'health-type','data-id'=>1])}}
                                                                <label for="expired">
                                                                    Expired
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 expired-reason-1 d-none">
                                                            <div class="form-group">
                                                                {{Form::text("oh[child][child_data][1][expired_reason]",'',['class'=>'form-control','placeholder'=>'Reason'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 expired-reason-1 d-none">
                                                            <div class="form-group">
                                                                {{Form::text("oh[child][child_data][1][expired_year]",'',['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                                                {{Form::text("oh[child][child_data][1][live_health_year]",'',['class'=>'form-control'])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('live_heatlh_year')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row child-data-parent d-none">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-4 child-naturally d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[child][child_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 child-ho-type p-ho-type','data-id'=>'child-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('ho_details_1')}}
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4 d-none child-when-where-1 ho-type-1">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[child][child_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="child-data">
                                                        <!--  append extra child data here-->
                                                    </div>
                                                    {{-- end child --}}

                                                    <!-- for mtp -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">MTP : &nbsp;</span>
                                                                {{Form::number("oh[mtp_no]",'0',['class'=>'form-control oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('mtp')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row mtp-data-parent d-none">
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                MTP :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[mtp][mtp_data][1][mtp_status]",'yes','',['id'=>'history_yes','class'=>'mtp-status','data-id'=>1])}}
                                                                <label for="history_yes">
                                                                    Yes
                                                                </label>

                                                                {{Form::radio("oh[mtp][mtp_data][1][mtp_status]",'no',true,['id'=>'history_no','class'=>'mtp-status','data-id'=>1])}}
                                                                <label for="history_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row col-md-9 d-none mtp-visible-1">
                                                            <div class="col-sm-3">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("oh[mtp][mtp_data][1][mtp_type]",'medically','',['id'=>'Medically'])}}
                                                                    <label for="Medically">
                                                                        Medically
                                                                    </label>
                                                                    {{Form::radio("oh[mtp][mtp_data][1][mtp_type]",'surgically','',['id'=>'surgically'])}}
                                                                    <label for="surgically">
                                                                        Surgically
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                                    {{Form::text("oh[mtp][mtp_data][1][mtp_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                                </div>
                                                                <span class="form-error-msg">
                                                                    {{$errors->first('mtp_month_of_pregancy')}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mtp-data-parent d-none">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-4 mtp-naturally d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[mtp][mtp_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 mtp-ho-type p-ho-type','data-id'=>'mtp-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 ml-4 d-none mtp-when-where-1 when-where-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[mtp][mtp_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mtp-data">
                                                        <!-- append extra mtp data -->
                                                    </div>
                                                    {{-- end mtp --}}

                                                    <!-- for Abortion -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Abortion : &nbsp;</span>
                                                                {{Form::number("oh[abortion_no]",'0',['class'=>'form-control abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('abortion')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row abortion-data-parent d-none">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Spontancous Abortion :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[abortion][abortion_data][1][spontancous_abortion_status]",'yes','',['id'=>'spontancous_abortion_yes','class'=>'abortion-status','data-id'=>1])}}
                                                                <label for="spontancous_abortion_yes">
                                                                    Yes
                                                                </label>

                                                                {{Form::radio("oh[abortion][abortion_data][1][spontancous_abortion_status]",'no',true,['id'=>'spontancous_abortion_no','class'=>'abortion-status','data-id'=>1])}}
                                                                <label for="spontancous_abortion_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row col-md-8 d-none abortion-visible-1">
                                                            <div class="col-sm-3">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("oh[abortion][abortion_data][1][spontancous_abortion_type]",'medically','',['id'=>'spontancous_abortion_medically'])}}
                                                                    <label for="spontancous_abortion_medically">
                                                                        Medically
                                                                    </label>
                                                                    {{Form::radio("oh[abortion][abortion_data][1][spontancous_abortion_type]",'surgically','',['id'=>'spontancous_abortion_surgically'])}}
                                                                    <label for="spontancous_abortion_surgically">
                                                                        Surgically
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">MOA &nbsp;</span>
                                                                    {{Form::text("oh[abortion][abortion_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Before &nbsp;</span>
                                                                    {{Form::text("oh[abortion][abortion_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row abortion-data-parent d-none">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-4 abortion-naturally d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[abortion][abortion_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 abortion-ho-type p-ho-type','data-id'=>'abortion-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 d-none abortion-when-where-1 when-where-3">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[abortion][abortion_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-4'>
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Abortion Reason : &nbsp;</span>
                                                                {{Form::text("oh[abortion][abortion_data][1][reason]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="abortion-data">
                                                        <!-- append extra abortion data -->
                                                    </div>
                                                    {{-- end abortion --}}
                                                    {{-- contraception start --}}
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Contraception :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[contraception][contraception_status]",'yes','',['id'=>'contraception_yes','class'=>'contraception-status','data-classname'=>'contraception-data'])}}
                                                                <label for="contraception_yes">
                                                                    Yes
                                                                </label>
                                                                {{Form::radio("oh[contraception][contraception_status]",'no',true,['id'=>'contraception_no','class'=>'contraception-status','data-classname'=>'contraception-data'])}}
                                                                <label for="contraception_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5 contraception-data d-none">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[contraception][contraception_data]",'barrier_method','',['class'=>'mr-2','id'=>'barrier_method'])}}
                                                                <label for="barrier_method">
                                                                    Barrier Method
                                                                </label>
                                                                {{Form::radio("oh[contraception][contraception_data]",'cu_t','',['class'=>'mr-2','id'=>'cu_t'])}}
                                                                <label for="cu_t">
                                                                    Cu - T
                                                                </label>
                                                                {{Form::radio("oh[contraception][contraception_data]",'tl_done','',['class'=>'mr-2','id'=>'tl_done'])}}
                                                                <label for="tl_done">
                                                                TL Done
                                                                </label>
                                                                {{Form::radio("oh[contraception][contraception_data]",'occipill','',['class'=>'mr-2','id'=>'occipill'])}}
                                                                <label for="occipill">
                                                                    Occipill
                                                                </label>
                                                                {{Form::radio("oh[contraception][contraception_data]",'other_contraception','',['class'=>'mr-2','id'=>'other_contraception'])}}
                                                                <label for="other_contraception">
                                                                    Other
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 contraception-data d-none">
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Detail : &nbsp;</span>
                                                                {{Form::text("oh[contraception][detail]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end contraception --}}
                                                    {{-- ectopic data --}}
                                                    <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Ectopic : &nbsp;</span>
                                                                {{Form::number("oh[ectopic_no]",'0',['class'=>'form-control ectopic-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row ectopic-data-parent d-none">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Ectopic :
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="row col-md-8 ectopic-visible-1">
                                                            <div class="col-sm-3">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("oh[ectopic][ectopic_data][1][spontancous_ectopic_type]",'medically','',['id'=>'spontancous_ectopic_medically'])}}
                                                                    <label for="spontancous_ectopic_medically">
                                                                        Medically
                                                                    </label>
                                                                    {{Form::radio("oh[ectopic][ectopic_data][1][spontancous_ectopic_type]",'surgically','',['id'=>'spontancous_ectopic_surgically'])}}
                                                                    <label for="spontancous_ectopic_surgically">
                                                                        Surgically
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Before &nbsp;</span>
                                                                    {{Form::text("oh[ectopic][ectopic_data][1][spontancous_ectopic_before]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('oh[ectopic][ectopic_data][1][tube][]','right','',['id'=>'right_tube_1'])}}
                                                                    <label for="right_tube_1">
                                                                        Right Tube
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('oh[ectopic][ectopic_data][1][tube][]','left','',['id'=>'left_tube_1'])}}
                                                                    <label for="left_tube_1">
                                                                        Left Tube
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row ectopic-data-parent d-none">
                                                        {{-- <div class="col-md-1"></div> --}}
                                                        <div class="col-md-4 ectopic-naturally d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[ectopic][ectopic_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 ectopic-ho-type p-ho-type','data-id'=>'ectopic-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 d-none ectopic-when-where-1 when-where-3">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[ectopic][ectopic_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-4'>
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Ectopic Detail : &nbsp;</span>
                                                                {{Form::text("oh[ectopic][ectopic_data][1][detail]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ectopic-data">
                                                    </div>
                                                    {{-- end ectopic data --}}
                                                    {{-- second marriage life --}}
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Second Merriage Life :
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage_life]",'yes','',['id'=>'second_marriage_life_yes','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'1'])}}
                                                                <label for="second_marriage_life_yes">
                                                                    Yes
                                                                </label>

                                                                {{Form::radio("oh[second_marriage_life]",'no',true,['id'=>'second_marriage_life_no','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'1'])}}
                                                                <label for="second_marriage_life_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 second-marriage-life d-none">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Second Marriage Life : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage_details]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- for child -->
                                                    <div class="row mt-3 second-marriage-life d-none">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                {{Form::select("oh[second_marriage][child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],'',['class'=>'form-control second-child-no select-padding-0','placeholder'=>'Child No','data-status'=>'second'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-child-data-parent d-none">
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                H/O :
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_term]",'full','',['id'=>'second_full'])}}
                                                                <label for="second_full">
                                                                    Fullterm
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_term]",'pre','',['id'=>'second_pre'])}}
                                                                <label for="second_pre">
                                                                    Preterm
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            {{Form::text("oh[second_marriage][child][child_data][1][ho_term_details]", '', [
                                                                'placeholder' => 'Term Details',
                                                                'class'=>'form-control',
                                                                'id'=>'second_merriage_term_details'
                                                            ])}}
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][child_type]",'single','',['id'=>'child_type_single'])}}
                                                                <label for="child_type_single">
                                                                    Single
                                                                </label>
            
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][child_type]",'twins','',['id'=>'child_type_twins'])}}
                                                                <label for="child_type_twins">
                                                                    Twins
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][child_type]",'triple','',['id'=>'child_type_triple'])}}
                                                                <label for="child_type_triple">
                                                                    Triple
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            {{Form::select("oh[second_marriage][child][child_data][1][ho_gender][]",['male'=>'Male','female'=>'Female'],'',['class'=>'form-control ','data-id'=>'','multiple','title'=>'Select Child Gender'])}}
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
                                                    <div class="row second-marriage-life-data second-child-data-parent d-none">
                                                        <div class="col-sm-1">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_type]",'normal',true,['id'=>'second_normal'])}}
                                                                <label for="second_normal">
                                                                    Normal
                                                                </label>

                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_type]",'cesarean','',['id'=>'second_cesarean'])}}
                                                                <label for="second_cesarean">
                                                                    Cesarean
                                                                </label>

                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_type]",'instrumental','',['id'=>'second_instrumental'])}}
                                                                <label for="second_instrumental">
                                                                    Instrumental
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_birth_type]",'live_health','',['id'=>'second_live_health','class'=>'health-type','data-id'=>'second1'])}}
                                                                <label for="second_live_health">
                                                                    Live Health
                                                                </label>

                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_birth_type]",'stil_birth','',['id'=>'second_stil_birth','class'=>'health-type','data-id'=>'second1'])}}
                                                                <label for="second_stil_birth">
                                                                    Stil Birth
                                                                </label>

                                                                {{Form::radio("oh[second_marriage][child][child_data][1][ho_birth_type]",'expired','',['id'=>'second_expired','class'=>'health-type','data-id'=>'second1'])}}
                                                                <label for="second_expired">
                                                                    Expired
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 expired-reason-second1 d-none">
                                                            <div class="form-group">
                                                                {{Form::text("oh[second_marriage][child][child_data][1][expired_reason]",'',['class'=>'form-control','placeholder'=>'Reason'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 expired-reason-second1 d-none">
                                                            <div class="form-group">
                                                                {{Form::text("oh[second_marriage][child][child_data][1][expired_year]",'',['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][child][child_data][1][live_health_year]",'',['class'=>'form-control'])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('live_heatlh_year')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-child-data-parent d-none">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-4 second-child-naturally second-marriage-life-data d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[second_marriage][child][child_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 child-ho-type second-p-ho-type','data-id'=>'second-child-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 d-none second-marriage-life-data second-child-when-where-1">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][child][child_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="second-marriage-life second-child-data d-none">
                                                        <!--  append extra child data here-->
                                                    </div>
                                                    {{-- end child --}}

                                                    <!-- for mtp -->
                                                    <div class="second-marriage-life row mt-3 d-none">
                                                        <div class="col-md-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">MTP : &nbsp;</span>
                                                                {{Form::number("oh[second_marriage][mtp_no]",'0',['class'=>'form-control second_oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-status'=>'second'])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('mtp')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-mtp-data-parent d-none">
                                                        <div class="col-md-1">
                                                            <label class="vertical-form-label pr-0">
                                                                MTP :
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][mtp][mtp_data][1][mtp_status]",'yes','',['id'=>'second_history_yes','class'=>'second-mtp-status','data-id'=>1])}}
                                                                <label for="second_history_yes">
                                                                    Yes
                                                                </label>

                                                                {{Form::radio("oh[second_marriage][mtp][mtp_data][1][mtp_status]",'no',true,['id'=>'second_history_no','class'=>'second-mtp-status','data-id'=>1])}}
                                                                <label for="second_history_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row col-md-9 d-none second-mtp-visible-1">
                                                            <div class="col-sm-3">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("oh[second_marriage][mtp][mtp_data][1][mtp_type]",'medically','',['id'=>'second_Medically'])}}
                                                                    <label for="second_Medically">
                                                                        Medically
                                                                    </label>
                                                                    {{Form::radio("oh[second_marriage][mtp][mtp_data][1][mtp_type]",'surgically','',['id'=>'second_surgically'])}}
                                                                    <label for="second_surgically">
                                                                        Surgically
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                                    {{Form::text("oh[second_marriage][mtp][mtp_data][1][mtp_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-mtp-data-parent d-none">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-4 second-mtp-naturally second-marriage-life-data d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[second_marriage][mtp][mtp_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 mtp-ho-type second-p-ho-type','data-id'=>'second-mtp-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                            <span class="form-error-msg">
                                                                {{$errors->first('ho_details_2')}}
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4 d-none second-marriage-life-data second-mtp-when-where-1">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][mtp][mtp_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="second-marriage-life second-mtp-data d-none">
                                                        <!-- append extra mtp data -->
                                                    </div>
                                                    {{-- end mtp --}}

                                                    <!-- for Abortion -->
                                                    <div class="row second-marriage-life mt-3 d-none">
                                                        <div class="col-md-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Abortion : &nbsp;</span>
                                                                {{Form::number("oh[second_marriage][abortion_no]",'0',['class'=>'form-control second-abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-abortion-data-parent d-none">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Spontancous Abortion :
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][abortion][abortion_data][1][spontancous_abortion_status]",'yes','',['id'=>'second_spontancous_abortion_yes','class'=>'second-abortion-status','data-id'=>1,'data-type'=>'second'])}}
                                                                <label for="second_spontancous_abortion_yes">
                                                                    Yes
                                                                </label>

                                                                {{Form::radio("oh[second_marriage][abortion][abortion_data][1][spontancous_abortion_status]",'no',true,['id'=>'second_spontancous_abortion_no','class'=>'second-abortion-status','data-id'=>1,'data-type'=>'second'])}}
                                                                <label for="second_spontancous_abortion_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row col-md-8 d-none second-abortion-visible-1">
                                                            <div class="col-sm-3">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("oh[second_marriage][abortion][abortion_data][1][spontancous_abortion_type]",'medically','',['id'=>'second_spontancous_abortion_medically'])}}
                                                                    <label for="second_spontancous_abortion_medically">
                                                                        Medically
                                                                    </label>
                                                                    {{Form::radio("oh[second_marriage][abortion][abortion_data][1][spontancous_abortion_type]",'surgically','',['id'=>'second_spontancous_abortion_surgically'])}}
                                                                    <label for="second_spontancous_abortion_surgically">
                                                                        Surgically
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">MOA &nbsp;</span>
                                                                    {{Form::text("oh[second_marriage][abortion][abortion_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Before &nbsp;</span>
                                                                    {{Form::text("oh[second_marriage][abortion][abortion_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-abortion-data-parent d-none">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-4 second-abortion-naturally d-none second-marriage-life-data">
                                                            <div class="form-group">
                                                                {{Form::select("oh[second_marriage][abortion][abortion_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 abortion-ho-type second-p-ho-type','data-id'=>'second-abortion-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 d-none second-marriage-life-data second-abortion-when-where-1">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][abortion][abortion_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-4 second-marriage-life-data'>
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Abortion Reason : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][abortion][abortion_data][1][reason]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="second-marriage-life second-abortion-data d-none">
                                                        <!-- append extra abortion data -->
                                                    </div>
                                                    {{-- end abortion --}}
                                                    {{-- contraception second marriage start --}}
                                                    <div class="row second-marriage-life d-none">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Contraception :
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-2">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_status]",'yes','',['id'=>'second_contraception_yes','class'=>'contraception-status','data-classname'=>'second-contraception-data'])}}
                                                                <label for="second_contraception_yes">
                                                                    Yes
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_status]",'no',true,['id'=>'second_contraception_no','class'=>'contraception-status','data-classname'=>'second-contraception-data'])}}
                                                                <label for="second_contraception_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5 second-contraception-data d-none">
                                                            <div class="radio is-conceived">
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'barrier_method','',['class'=>'mr-2','id'=>'second_barrier_method'])}}
                                                                <label for="second_barrier_method">
                                                                    Barrier Method
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'cu_t','',['class'=>'mr-2','id'=>'second_cu_t'])}}
                                                                <label for="second_cu_t">
                                                                    Cu - T
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'tl_done','',['class'=>'mr-2','id'=>'second_tl_done'])}}
                                                                <label for="second_tl_done">
                                                                TL Done
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'occipill','',['class'=>'mr-2','id'=>'second_occipill'])}}
                                                                <label for="second_occipill">
                                                                    Occipill
                                                                </label>
                                                                {{Form::radio("oh[second_marriage][contraception][contraception_data]",'other_contraception','',['class'=>'mr-2','id'=>'second_other_contraception'])}}
                                                                <label for="second_other_contraception">
                                                                    Other
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 second-contraception-data d-none">
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Detail : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][contraception][detail]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end contraception second marriage --}}
                                                    {{-- ectopic data --}}
                                                    <div class="row second-marriage-life mt-3 d-none">
                                                        <div class="col-md-2">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">Ectopic : &nbsp;</span>
                                                                {{Form::number("oh[second_marriage][ectopic_no]",'0',['class'=>'form-control second-ectopic-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-ectopic-data-parent d-none">
                                                        <div class="col-md-2">
                                                            <label class="vertical-form-label pr-0">
                                                                Ectopic :
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="row col-md-8 second-ectopic-visible-1">
                                                            <div class="col-sm-3">
                                                                <div class="radio is-conceived">
                                                                    {{Form::radio("oh[second_marriage][ectopic][ectopic_data][1][spontancous_ectopic_type]",'medically','',['id'=>'second_spontancous_ectopic_medically'])}}
                                                                    <label for="second_spontancous_ectopic_medically">
                                                                        Medically
                                                                    </label>
                                                                    {{Form::radio("oh[second_marriage][ectopic][ectopic_data][1][spontancous_ectopic_type]",'surgically','',['id'=>'second_spontancous_ectopic_surgically'])}}
                                                                    <label for="second_spontancous_ectopic_surgically">
                                                                        Surgically
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Before &nbsp;</span>
                                                                    {{Form::text("oh[second_marriage][ectopic][ectopic_data][1][spontancous_ectopic_before]",'',['class'=>'form-control'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('oh[second_marriage][ectopic][ectopic_data][1][tube][]','right','',['id'=>'second_right_tube_1'])}}
                                                                    <label for="second_right_tube_1">
                                                                        Right Tube
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <div class="checkbox">
                                                                    {{Form::checkbox('oh[second_marriage][ectopic][ectopic_data][1][tube][]','left','',['id'=>'second_left_tube_1'])}}
                                                                    <label for="second_left_tube_1">
                                                                        Left Tube
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row second-marriage-life-data second-ectopic-data-parent d-none">
                                                        {{-- <div class="col-md-1"></div> --}}
                                                        <div class="col-md-4 second-ectopic-naturally d-none">
                                                            <div class="form-group">
                                                                {{Form::select("oh[second_marriage][ectopic][ectopic_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 second-ectopic-ho-type p-ho-type','data-id'=>'second-ectopic-when-where-1','placeholder'=>'Select Conceived By'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 d-none ectopic-when-where-1 when-where-3">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">When / Where : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][ectopic][ectopic_data][1][when_where]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class='col-md-4'>
                                                            <div class='input-group'>
                                                                <span class='input-group-addon'>Ectopic Detail : &nbsp;</span>
                                                                {{Form::text("oh[second_marriage][ectopic][ectopic_data][1][detail]",'',['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="second-marriage-life second-ectopic-data d-none">
                                                    </div>
                                                    {{-- end ectopic data --}}
                                                </div>
                                                <div class="row">
                                                    <div class="input-group">
                                                        {{Form::textarea("oh[remark]",'',['class'=>'form-control no-resize','placeholder'=>'Remark','rows'=>'5'])}}
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

                                                <div class="row unmarried-data">
                                                    {{-- <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{Form::select("mh[type_and_year_of_infertility]",['primary'=>'Primary','secondary'=>'Secondary'],'',['class'=>'form-control select-padding-0','placeholder'=>'Type And Year Of Infertility'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            {{Form::select("mh[age_of_menarchy]",[11=>11,12=>12,13=>13,14=>14,15=>15],'',['class'=>'form-control select-padding-0','placeholder'=>'Age Of Menarchy'])}}
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Since Year : &nbsp;</span>
                                                            {{Form::text("mh[since_year]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Manopause Since Year : &nbsp;</span>
                                                            {{Form::text("mh[manopause_since_year]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    {{-- <div class="col-sm-5">
                                                        <div class="form-group">
                                                            {{Form::text("mh[age_of_manopause]",'',['class'=>'form-control','placeholder'=>'Age Of Manopause'])}}
                                                        </div>
                                                        
                                                    </div> --}}
                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label class="vertical-form-label">
                                                            Past M/H :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2 past-ir-regular-data d-none">
                                                        {{Form::select("mh[past_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],'',['class'=>'form-control select-padding-0 past-mh-1'])}}
                                                        <span class="form-error-msg">
                                                            {{$errors->first('past_mh_1')}}
                                                        </span>
                                                    </div>

                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            {{Form::select("mh[past_mh_2]",[''=>'Select Past MH','regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0 past-mh-2 regular-type','data-id'=>'past-ir-regular-data'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('past_mh_2')}}
                                                        </span>
                                                    </div>
                                                    <div class='col-md-3 complain-multi duration-value past-ir-regular-data d-none'>
                                                        {{Form::select('mh[past_duration_of_day]',$durationOfData,'',['class'=>'form-control past-duration-of-day anc-dose-val dose-data select-padding-0 duration-data','placeholder'=>'Select Duration Of Day'])}}
                                                    </div>
                                                    <div class="col-md-2 past-ir-regular-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Interval Of Day : &nbsp;</span>
                                                            {{Form::text("mh[past_interval_of_day]",'',['class'=>'form-control past-interval-of-day'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('past_interval_of_day')}}
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-2 past-ir-regular-data d-none">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("mh[past_month]",'painful','',['id'=>'painful','class'=>'past-month past-m'])}}
                                                            <label for="painful">
                                                                Painful
                                                            </label>

                                                            {{Form::radio("mh[past_month]",'painless',true,['id'=>'painless','class'=>'past-day past-m'])}}
                                                            <label for="painless">
                                                                Painless
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("mh[same_past]",'same','',['id'=>'same','class'=>'same'])}}
                                                            <label for="same">
                                                                Same as Past M/H
                                                            </label>

                                                            {{Form::radio("mh[same_past]",'exit','',['id'=>'exit','class'=>'same'])}}
                                                            <label for="exit">
                                                                Exit
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label">
                                                            Present M/H:
                                                        </label>
                                                    </div>

                                                    <div class="col-md-2 present-ir-regular-data d-none">
                                                        {{Form::select("mh[present_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],'',['class'=>'form-control select-padding-0 present-mh-1'])}}
                                                        <span class="form-error-msg">
                                                            {{$errors->first('present_mh_1')}}
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            {{Form::select("mh[present_mh_2]",[''=>'Select Present MH','regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0 present-mh-2 regular-type','data-id'=>'present-ir-regular-data'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('present_mh_2')}}
                                                        </span>
                                                    </div>
                                                    <div class='col-md-3 complain-multi duration-value present-duration-data present-ir-regular-data d-none'>
                                                        {{Form::select('mh[present_duration_of_day]',$durationOfData,'',['class'=>'form-control present-duration-of-day anc-dose-val dose-data select-padding-0 duration-data','placeholder'=>'Select Duration Of Day'])}}
                                                    </div>
                                                    <div class="col-md-2 present-ir-regular-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Interval Of Day : &nbsp;</span>
                                                            {{Form::text("mh[present_interval_of_day]",'',['class'=>'form-control present-interval-of-day'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('present_interval_of_day')}}
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-2 present-ir-regular-data d-none">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("mh[present_month]",'painful','',['id'=>'pr_painful','class'=>'present-m'])}}
                                                            <label for="pr_painful">
                                                                Painful
                                                            </label>

                                                            {{Form::radio("mh[present_month]",'painless',true,['id'=>'pr_painless','class'=>'present-m'])}}
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
                                                            {{Form::radio("mh[present_withdrawal_medicine]",'yes','',['id'=>'withdrawal_medicine_yes','class'=>'present-m'])}}
                                                            <label for="withdrawal_medicine_yes">
                                                                Yes
                                                            </label>
                    
                                                            {{Form::radio("mh[present_withdrawal_medicine]",'no',true,['id'=>'withdrawal_medicine_no','class'=>'present-m'])}}
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
                                                            {{Form::text("mh[last_menstrual_date]",'',['class'=>'form-control lmd-date','required'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('last_mentsrual_date')}}
                                                        </span>
                                                    </div>

                                                    {{-- <div class="col-md-3">
                                                        <div class="input-group edd-week-data">
                                                            <span class="input-group-addon">EDD : &nbsp;</span>
                                                            {{Form::text("mh[edd]",'',['class'=>'form-control edd-date','readonly'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first("p_details[edd]")}}
                                                        </span>
                                                    </div> --}}

                                                    {{-- <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Since Month : &nbsp;</span>
                                                            {{Form::text("mh[since_month]",'',['class'=>'form-control'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('since_month')}}
                                                        </span>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Since Cycle : &nbsp;</span>
                                                            {{Form::text("mh[since_cycle]",'',['class'=>'form-control'])}}
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
                                    <div class="panel panel-primary">
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
                                                        {{Form::select('p_detailes[personal_history_history_type][]',$personalData,'',['class'=>'form-control co-value co_value_data personal-history mb-3','placeholder'=>'Select Personal History','multiple'=>true])}}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Date : &nbsp;
                                                            </span>
                                                            {{Form::text("p_detailes[personal_history_date]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 ho-past-personal-data">
                                                        {{Form::text('p_detailes[personal_history_detail]','',['class'=>'form-control ','placeholder'=>'Personal History Detail'])}}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Family History :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4 complain-multi">
                                                        {{Form::select('p_detailes[family_history][]',$familyData,'',['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Family History','multiple'=>true])}}
                                                    </div>
                                                    <div class="col-md-4 ho-past-personal-data">
                                                        {{Form::text('p_detailes[family_history_detail]','',['class'=>'form-control ','placeholder'=>'Family History Detail'])}}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Past History :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4 complain-multi ho-past-personal-data">
                                                        {{Form::select('p_detailes[past_history_type][]',$pastData,'',['class'=>'form-control co-value co_value_data mb-3','placeholder'=>'Select Past History','multiple'=>true])}}
                                                    </div>
                                                    <div class="col-md-4 ho-past-personal-data">
                                                        {{Form::text('p_detailes[past_history_detail]',null,['class'=>'form-control ','placeholder'=>'Past History Detail'])}}
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
                                                
                                                <div class="row">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            P/A :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[p_a][type]",'yes','',['id'=>'pa_type_yes','class'=>'pa-type','data-type'=>'pa-details'])}}
                                                            <label for="pa_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("oe[p_a][type]",'no',true,['id'=>'pa_type_no','class'=>'pa-type','data-type'=>'pa-details'])}}
                                                            <label for="pa_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 pa-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[p_a][details]",'',['class'=>'form-control','placeholder'=>'UT Details'])}}
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row pa-details unmarried-data d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0 tvs-details d-none">
                                                        <label class="vertical-form-label pr-0">
                                                            Ovary :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('oe[ovary][type][]','right','',['id'=>'right','class'=>'plan-management'])}}
                                                            <label for="right">
                                                                Right
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 right-details">
                                                        <div class="form-group">
                                                            {{Form::select("oe[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 right-details">
                                                        <div class="row">
                                                            <div class="col-md-5 complain-multi ovary-right-abnormal-type d-none mt-1">
                                                                {{Form::select("oe[ovary][right][details][]",$rightOvaryData,'',[
                                                                    'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                                    'placeholder'=>'Abnormal Details',
                                                                    'id' => 'oe_ovary_right_details',
                                                                    'multiple'=>true
                                                                ])}}
                                                            </div>
                                                            <div class="col-md-6 complain-multi ovary-right-abnormal-type d-none">
                                                                <div class="row edit_oe_ovary_right_details">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row pa-details unmarried-data d-none">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-1">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('oe[ovary][type][]','left','',['id'=>'left','class'=>'plan-management'])}}
                                                            <label for="left">
                                                                Left
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 left-details">
                                                        <div class="form-group">
                                                            {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 left-details">
                                                        <div class="row">
                                                            <div class="col-md-5 complain-multi ovary-left-abnormal-type d-none">
                                                                {{Form::select("oe[ovary][left][details][]",$leftOvaryData,'',[
                                                                    'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                                    'placeholder'=>'Abnormal Details',
                                                                    'id' => 'oe_ovary_left_details',
                                                                    'multiple'=>true
                                                                ])}}
                                                            </div>
                                                            <div class="col-md-6 complain-multi ovary-left-abnormal-type d-none">
                                                                <div class="row edit_oe_ovary_left_details">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            L/E :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[l_s][type]",'yes','',['id'=>'ls_type_yes','class'=>'gynec-yes-no-status','data-type'=>'ls-details'])}}
                                                            <label for="ls_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("oe[l_s][type]",'no',true,['id'=>'ls_type_no','class'=>'gynec-yes-no-status','data-type'=>'ls-details'])}}
                                                            <label for="ls_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 ls-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[l_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            P/S :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[p_s][type]",'yes','',['id'=>'ps_type_yes','class'=>'gynec-yes-no-status','data-type'=>'ps-details'])}}
                                                            <label for="ps_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("oe[p_s][type]",'no',true,['id'=>'ps_type_no','class'=>'gynec-yes-no-status','data-type'=>'ps-details'])}}
                                                            <label for="ps_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 ps-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[p_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 pr-0 ps-details">
                                                        <label class="vertical-form-label pr-0">
                                                            Cervix :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4 ps-details">
                                                        <div class="form-group">
                                                            {{Form::text("oe[cervix][details]",'',['class'=>'form-control','placeholder'=>'Cervix Details'])}}
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
                                                            {{Form::text("oe[right_tube]",null,['class'=>'form-control','placeholder'=>'Right Tube Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Left Tube :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            {{Form::text("oe[left_tube]",null,['class'=>'form-control','placeholder'=>'Left Tube Details'])}}
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <div class="row married-data">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            TVS :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[tvs][type]",'yes','',['id'=>'tvs_type_yes','class'=>'gynec-yes-no-status','data-type'=>'tvs-details'])}}
                                                            <label for="tvs_type_yes">
                                                                Yes
                                                            </label>
                                                            {{Form::radio("oe[tvs][type]",'no',true,['id'=>'tvs_type_no','class'=>'gynec-yes-no-status','data-type'=>'tvs-details'])}}
                                                            <label for="tvs_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Breast :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[breast][type]",'yes','',['id'=>'breast_type_yes','class'=>'gynec-yes-no-status','data-type'=>'breast-details'])}}
                                                            <label for="breast_type_yes">
                                                                Yes
                                                            </label>
                                                            {{Form::radio("oe[breast][type]",'no',true,['id'=>'breast_type_no','class'=>'gynec-yes-no-status','data-type'=>'breast-details'])}}
                                                            <label for="breast_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 breast-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[breast][right]",'',['class'=>'form-control','placeholder'=>'Right'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 breast-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[breast][left]",'',['class'=>'form-control','placeholder'=>'Left'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row tvs-details married-data d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Uterus :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2 tvs-details">
                                                        <div class="form-group">
                                                            {{Form::select("oe[uterus][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-2 uterus-abnormal-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[uterus][details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {{Form::text("oe[uterus][details]",'',['class'=>'form-control','placeholder'=>'Uterus Details'])}}
                                                        </div>
                                                    </div>
                                                    {{-- <span class="col-md-1 p-2 uterus-abnormal-type d-none">LG</span> --}}
                                                </div>
                                                <div class="row tvs-details married-data d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Endometrial Thickness :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            {{Form::text("oe[endometrial_thickness]",'',['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row tvs-details married-data d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Adnexa :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[adnexa][type]",'yes','',['id'=>'adnexa_type_yes','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                                                            <label for="adnexa_type_yes">
                                                                Yes
                                                            </label>
        
                                                            {{Form::radio("oe[adnexa][type]",'no',true,['id'=>'adnexa_type_no','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                                                            <label for="adnexa_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 adnexa-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[adnexa][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row tvs-details married-data d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0 tvs-details d-none">
                                                        <label class="vertical-form-label pr-0">
                                                            Ovary :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('oe[ovary][type][]','right','',['id'=>'right','class'=>'plan-management'])}}
                                                            <label for="right">
                                                                Right
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 right-details">
                                                        <div class="form-group">
                                                            {{Form::select("oe[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 right-details">
                                                        <div class="row">
                                                            <div class="col-md-5 complain-multi ovary-right-abnormal-type d-none mt-1">
                                                                {{Form::select("oe[ovary][right][details][]",$rightOvaryData,'',[
                                                                    'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                                    'placeholder'=>'Abnormal Details',
                                                                    'id' => 'oe_ovary_right_details',
                                                                    'multiple'=>true
                                                                ])}}
                                                            </div>
                                                            <div class="col-md-6 complain-multi ovary-right-abnormal-type d-none">
                                                                <div class="row edit_oe_ovary_right_details">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row tvs-details married-data d-none">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-1">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('oe[ovary][type][]','left','',['id'=>'left','class'=>'plan-management'])}}
                                                            <label for="left">
                                                                Left
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 left-details">
                                                        <div class="form-group">
                                                            {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 left-details">
                                                        <div class="row">
                                                            <div class="col-md-5 complain-multi ovary-left-abnormal-type d-none">
                                                                {{Form::select("oe[ovary][left][details][]",$leftOvaryData,'',[
                                                                    'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                                    'placeholder'=>'Abnormal Details',
                                                                    'id' => 'oe_ovary_left_details',
                                                                    'multiple'=>true
                                                                ])}}
                                                            </div>
                                                            <div class="col-md-6 complain-multi ovary-left-abnormal-type d-none">
                                                                <div class="row edit_oe_ovary_left_details">
                                                                </div>
                                                            </div>
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
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[gynec_tvs][type]",'yes','',['id'=>'gynec_tvs_type_yes','class'=>'gynec-yes-no-status','data-type'=>'ut-details'])}}
                                                            <label for="gynec_tvs_type_yes">
                                                                Yes
                                                            </label>
                                                            {{Form::radio("oe[gynec_tvs][type]",'no',true,['id'=>'gynec_tvs_type_no','class'=>'gynec-yes-no-status','data-type'=>'ut-details'])}}
                                                            <label for="gynec_tvs_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 ut-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[gynec_ut][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
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
                                                            {{Form::text("oe[gynec_endometrial_cavity][details]",'',['class'=>'form-control','placeholder'=>'Endometrial Cavity'])}}
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
                                                            {{Form::radio("oe[gynec_p_s][type]",'yes','',['id'=>'gynec_ps_type_yes','class'=>'gynec-yes-no-status','data-type'=>'gynec-ps-details'])}}
                                                            <label for="gynec_ps_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("oe[gynec_p_s][type]",'no',true,['id'=>'gynec_ps_type_no','class'=>'gynec-yes-no-status','data-type'=>'gynec-ps-details'])}}
                                                            <label for="gynec_ps_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 gynec-ps-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[gynec_p_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Adnexa :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("oe[adnexa][type]",'yes','',['id'=>'adnexa_type_yes','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                                                            <label for="adnexa_type_yes">
                                                                Yes
                                                            </label>
        
                                                            {{Form::radio("oe[adnexa][type]",'no',true,['id'=>'adnexa_type_no','class'=>'gynec-yes-no-status','data-type'=>'adnexa-details'])}}
                                                            <label for="adnexa_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 adnexa-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[adnexa][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
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
                                                            {{Form::text("oe[gynec_le][bp]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <span class="col-md-1 p-2">MMHG</span>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Temp : &nbsp;</span>
                                                            {{Form::text("oe[gynec_le][temp]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Pulse : &nbsp;</span>
                                                            {{Form::text("oe[gynec_le][pulse]",'',['class'=>'form-control'])}}
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
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('plan_of_management[plan_of_management_data][]','medically','',['id'=>'medically','class'=>'plan-management','data-id'=>'medically'])}}
                                                            <label for="medically">
                                                                Medically
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('plan_of_management[plan_of_management_data][]','surgically','',['id'=>'surgically-type','class'=>'plan-management','data-id'=>'surgically-type'])}}
                                                            <label for="surgically-type">
                                                                Surgically
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 complain-multi surgically-details d-none">
                                                        {{Form::select('plan_of_management[surgically_details][]',$surgicallyData,'',['class'=>'form-control co-value co_value_data','placeholder'=>'Surgically Type','multiple'=>true])}}
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
                                                            {{Form::text("investigation[usg][date]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                    <div class="{{'col-sm-3'}}">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[usg][usg_details]",'',['class'=>'form-control','placeholder'=>'USG Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- begin Hystroscopy --}}
                                                <div class="row">
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Hystroscopy :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("investigation[hystroscopy][type]",'yes','',['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type gynec-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                            <label for="hystroscopy_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("investigation[hystroscopy][type]",'no',true,['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type gynec-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                            <label for="hystroscopy_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 pr-0 hystroscopy-type d-none">
                                                        <label class="vertical-form-label pr-0">
                                                            Finding :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 hystroscopy-type d-none">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[hystroscopy][finding_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'finding-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 finding-type d-none hystroscopy-type-abnormal">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[hystroscopy][abnormal_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row hystroscopy-type d-none">
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
                                                            {{Form::text("investigation[hystroscopy][finding_date]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[hystroscopy][finding_details]",'',['class'=>'form-control date','placeholder'=>'Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2 hystroscopy-type d-none">
                                                    <div class="hystroscopy-images"></div>
                                                </div>
                                                {{-- end Hystroscopy  --}}

                                            {{-- begin laproscopy  --}}
                                                <div class="row">
                                                    <div class="col-md-2 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            laproscopy :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Date : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[laproscopy][finding_date]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("investigation[laproscopy][type]",'yes','',['id'=>'laproscopy_type_yes','class'=>'gynec-yes-no-status','data-type'=>'laproscopy-status-type'])}}
                                                            <label for="laproscopy_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("investigation[laproscopy][type]",'no',true,['id'=>'laproscopy_type_no','class'=>'gynec-yes-no-status','data-type'=>'laproscopy-status-type'])}}
                                                            <label for="laproscopy_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 laproscopy-status-type d-none">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[laproscopy][laproscopy_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'laproscopy-type'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row laproscopy-type laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            RT Tube :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[laproscopy][rt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'rt-tube-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 rt-tube-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[laproscopy][rt_tube_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row laproscopy-type laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Uterus :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[laproscopy][uterus_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 uterus-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[laproscopy][uterus_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row laproscopy-type laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            LT Tube :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[laproscopy][lt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'lt-tube-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 lt-tube-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[laproscopy][lt_tube_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row laproscopy-type laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Other : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[laproscopy][other]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2 laproscopy-status-type d-none">
                                                    <div class="laproscopy-images"></div>
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
                                                            {{Form::text("investigation[hcg][date]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("investigation[hcg][type]",'yes','',['id'=>'hcg_laproscopy_type_yes','class'=>'gynec-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                                                            <label for="hcg_laproscopy_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("investigation[hcg][type]",'no',true,['id'=>'hcg_laproscopy_type_no','class'=>'gynec-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                                                            <label for="hcg_laproscopy_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 hcg-laproscopy-status-type d-none">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[hcg][laproscopy_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-laproscopy-type'])}}
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            RT Tube :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[hcg][rt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-rt-tube-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 hcg-rt-tube-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[hcg][rt_tube_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Uterus :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[hcg][uterus_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-uterus-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 hcg-uterus-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[hcg][uterus_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal d-none">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            LT Tube :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[hcg][lt_tube_type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'hcg-lt-tube-type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 hcg-lt-tube-type d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[hcg][lt_tube_details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 hcg-laproscopy-status-type d-none">
                                                    <div class="hcg-images"></div>
                                                </div> --}}
                                                {{-- end hcg  --}}
                                                <br>
                                                {{-- <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                CBC : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[cbc]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Urine : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[urine]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                RBS : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[rbs]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                HIV : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[hiv]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Hbs Ag : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[hbs_ag]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Date : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[date_1]",'',['class'=>'form-control datetimepicker date'])}}
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
                                                            {{Form::text("investigation[tsh]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                FSH : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[fsh]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Prolectin : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[prolectin]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Date : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[date_2]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                LH : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[lh]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                {{-- <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                AMH : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[amh]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                E2 : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[e2]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                P2 : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[p2]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Date : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[date_2]",'',['class'=>'form-control datetimepicker date'])}}
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- report data --}}
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','1','',['id'=>'cbc_mp','class'=>'plan-management','data-id'=>'cbc-mp-details'])}}
                                                            <label for="cbc_mp">
                                                                CBC / MP
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 cbc-mp-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][1]",'',['class'=>'form-control','placeholder'=>'CBC MP Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','2','',['id'=>'fbs','class'=>'plan-management','data-id'=>'fbs-data-details'])}}
                                                            <label for="fbs">
                                                                FBS
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 fbs-data-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][2]",'',['class'=>'form-control','placeholder'=>'FBS Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row cbc-mp-details d-none">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[investigation_cbc_mp_details][status]",['1'=>"WNL",'2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 cbc-mb-type-details-value d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Aneamia : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_cbc_mp_details][aneamia]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 cbc-mb-type-details-value d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Leacocytosis : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_cbc_mp_details][leacocytosis]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','3','',['id'=>'urine_r','class'=>'plan-management','data-id'=>'urine-details'])}}
                                                            <label for="urine_r">
                                                                Urine - R
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 urine-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][3]",'',['class'=>'form-control','placeholder'=>'Urine Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','4','',['id'=>'ppbs','class'=>'plan-management','data-id'=>'ppbs-data-details'])}}
                                                            <label for="ppbs">
                                                                PPBS
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 ppbs-data-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][4]",'',['class'=>'form-control','placeholder'=>'PPBS Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row urine-details d-none">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[investigation_urine_value_details][status]",['1'=>"WNL",'2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-details-value','placeholder'=>'Select CBC MB Type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 urine-details-value d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Aneamia : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_urine_value_details][aneamia]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 urine-details-value d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Leacocytosis : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_urine_value_details][leacocytosis]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','5','',['id'=>'esr','class'=>'plan-management','data-id'=>'esr-details'])}}
                                                            <label for="esr">
                                                                ESR
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 esr-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][5]",'',['class'=>'form-control','placeholder'=>'ESR Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','6','',['id'=>'rbs','class'=>'plan-management','data-id'=>'rbs-data-details'])}}
                                                            <label for="rbs">
                                                                RBS
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 rbs-data-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][6]",'',['class'=>'form-control','placeholder'=>'RBS Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','7','',['id'=>'sgpt','class'=>'plan-management','data-id'=>'sgpt-details'])}}
                                                            <label for="sgpt">
                                                                SGPT
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 sgpt-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][7]",'',['class'=>'form-control','placeholder'=>'SGPT Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','8','',['id'=>'hbsag','class'=>'plan-management','data-id'=>'hbsag-details'])}}
                                                            <label for="hbsag">
                                                                HBsAg
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 hbsag-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][8]",'',['class'=>'form-control','placeholder'=>'HBsAg Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','9','',['id'=>'screatinine','class'=>'plan-management','data-id'=>'screatinine-details'])}}
                                                            <label for="screatinine">
                                                                S.Creatinine
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 screatinine-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][9]",'',['class'=>'form-control','placeholder'=>'S. Creatinine Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','10','',['id'=>'hiv','class'=>'plan-management','data-id'=>'hiv-details'])}}
                                                            <label for="hiv">
                                                                HIV
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 hiv-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][10]",'',['class'=>'form-control','placeholder'=>'HIV Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','11','',['id'=>'crp','class'=>'plan-management','data-id'=>'crp-details'])}}
                                                            <label for="crp">
                                                                CRP
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 crp-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][11]",'',['class'=>'form-control','placeholder'=>'CRP Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','12','',['id'=>'blood_group','class'=>'plan-management','data-id'=>'blood-details'])}}
                                                            <label for="blood_group">
                                                                Blood Group
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 blood-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][12]",'',['class'=>'form-control','placeholder'=>'Blood Group Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','13','',['id'=>'slied','class'=>'plan-management','data-id'=>'slied-details'])}}
                                                            <label for="slied">
                                                                Serum Widal
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 slied-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][13]",'',['class'=>'form-control','placeholder'=>'Serum Widal Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','14','',['id'=>'tsh','class'=>'plan-management','data-id'=>'tsh-data-details'])}}
                                                            <label for="tsh">
                                                                TSH
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 tsh-data-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][14]",'',['class'=>'form-control','placeholder'=>'TSH Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row tsh-data-details d-none">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[investigation_tsh_value_details][status]",['1'=>"WNL",'2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'tsh-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 tsh-type-details-value d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Aneamia : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_tsh_value_details][aneamia]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 tsh-type-details-value d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Leacocytosis : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_tsh_value_details][leacocytosis]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','15','',['id'=>'typhidot','class'=>'plan-management','data-id'=>'typhidot-lgm-details'])}}
                                                            <label for="typhidot">
                                                                Typhidot lgM
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 typhidot-lgm-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][15]",'',['class'=>'form-control','placeholder'=>'Typhidot lgM Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','16','',['id'=>'t3','class'=>'plan-management','data-id'=>'t3-details'])}}
                                                            <label for="t3">
                                                                T3, T4, TSH
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 t3-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][16]",'',['class'=>'form-control','placeholder'=>'T3, T4, TSH Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','17','',['id'=>'lipid_profile','class'=>'plan-management','data-id'=>'lipid-profile-details'])}}
                                                            <label for="lipid_profile">
                                                                Lipid Profile
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 lipid-profile-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][17]",'',['class'=>'form-control','placeholder'=>'Lipid Profile Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','18','',['id'=>'vitb12','class'=>'plan-management','data-id'=>'vit-b12-details'])}}
                                                            <label for="vitb12">
                                                                Vit B-12
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 vit-b12-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][18]",'',['class'=>'form-control','placeholder'=>'Vit B-12 Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','19','',['id'=>'tube-widal','class'=>'plan-management','data-id'=>'tube-widal-details'])}}
                                                            <label for="tube-widal">
                                                                Tube Widal
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 tube-widal-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][19]",'',['class'=>'form-control','placeholder'=>'Tube Widal Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','20','',['id'=>'vitd3','class'=>'plan-management','data-id'=>'vit-d3-details'])}}
                                                            <label for="vitd3">
                                                                Vit D-3
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 vit-d3-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][20]",'',['class'=>'form-control','placeholder'=>'Vit D-3 Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','21','',['id'=>'lft','class'=>'plan-management','data-id'=>'lft-details'])}}
                                                            <label for="lft">
                                                                LFT
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 lft-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][21]",'',['class'=>'form-control','placeholder'=>'LFT Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','22','',['id'=>'anc_profile','class'=>'plan-management','data-id'=>'anc-profile-details'])}}
                                                            <label for="anc_profile">
                                                                ANC Profile
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 anc-profile-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][22]",'',['class'=>'form-control','placeholder'=>'ANC Profile Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','23','',['id'=>'rft','class'=>'plan-management','data-id'=>'rft-details'])}}
                                                            <label for="rft">
                                                                RFT
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 rft-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][23]",'',['class'=>'form-control','placeholder'=>'RFT Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','24','',['id'=>'pre_major','class'=>'plan-management','data-id'=>'pre-major-profile-details'])}}
                                                            <label for="pre_major">
                                                                Pre oper.Profile(Major)
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 pre-major-profile-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][24]",'',['class'=>'form-control','placeholder'=>'Pre oper.Profile(Major) Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','25','',['id'=>'scalcium','class'=>'plan-management','data-id'=>'scalcium-details'])}}
                                                            <label for="scalcium">
                                                                S.Calcium
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 scalcium-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][25]",'',['class'=>'form-control','placeholder'=>'S. Calcium Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','26','',['id'=>'pre_minor','class'=>'plan-management','data-id'=>'pre-minor-profile-details'])}}
                                                            <label for="pre_minor">
                                                                Pre oper.Profile(Minor)
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 pre-minor-profile-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][26]",'',['class'=>'form-control','placeholder'=>'Pre oper.Profile(Minor) Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','27','',['id'=>'eletrolytes','class'=>'plan-management','data-id'=>'eletrolytes-details'])}}
                                                            <label for="eletrolytes">
                                                                S.Eletrolytes
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 eletrolytes-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][27]",'',['class'=>'form-control','placeholder'=>'S. Eletrolytes Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','28','',['id'=>'denue_duo','class'=>'plan-management','data-id'=>'denue-duo-details'])}}
                                                            <label for="denue_duo">
                                                                Dengue Duo
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 denue-duo-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][28]",'',['class'=>'form-control','placeholder'=>'Dengue Duo Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','29','',['id'=>'billirubin','class'=>'plan-management','data-id'=>'billirubin-details'])}}
                                                            <label for="billirubin">
                                                                S.Billirubin
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 billirubin-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][29]",'',['class'=>'form-control','placeholder'=>'S. billirubin Details'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            {{Form::checkbox('investigation[investigation_data][]','30','',['id'=>'denue_ns1','class'=>'plan-management','data-id'=>'denue-ns1-details'])}}
                                                            <label for="denue_ns1">
                                                                Dengue NS1
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 denue-ns1-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("investigation[investigation_details][30]",'',['class'=>'form-control','placeholder'=>'Dengue NS1 Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-1">
                                                    <div class="col-sm-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Other Report : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_extra]",'',['class'=>'form-control'])}}
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
                                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#invest_tab" href="#invest_tab" aria-expanded="false"
                                                    aria-controls="past-history">4. Investigation</a></h4>
                                        </div>
                                        <div id="invest_tab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                            <div class="panel-body" id="parent">
                                                <div class="row anc-profile">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0 pre-operative-label">
                                                            ANC Profile :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("investigation[anc_profile_type]",'yes','',['id'=>'anc_profile_type_yes','class'=>'anc-profile-type'])}}
                                                            <label for="anc_profile_type_yes">
                                                                Yes
                                                            </label>

                                                            {{Form::radio("investigation[anc_profile_type]",'no',true,['id'=>'anc_profile_type_no','class'=>'anc-profile-type'])}}
                                                            <label for="anc_profile_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 anc-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Date : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_anc_date]",'',['class'=>'form-control datetimepicker date f-date'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 anc-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Blood Group : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_blood_group]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 anc-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                RBS : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_anc_rbs]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row anc-data d-none">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            CBC MP :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[investigation_cbc_mp][status]",['1'=>"WNL",'2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-data','placeholder'=>'Select CBC MB Type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 cbc-mb-type-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Aneamia : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_cbc_mp][aneamia]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 cbc-mb-type-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Leacocytosis : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_cbc_mp][leacocytosis]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row anc-data d-none">
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Urine :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            {{Form::select("investigation[investigation_urine][status]",['1'=>"WNL",'2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-type-data','placeholder'=>'Select Urine Type'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 pr-0 urine-type-data d-none">
                                                        <label class="vertical-form-label pr-0">
                                                            Puccell :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2 urine-type-data d-none">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("investigation[investigation_urine][type]",'present','',['id'=>'investigation_urine_present','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                                                            <label for="investigation_urine_present">
                                                                Present
                                                            </label>

                                                            {{Form::radio("investigation[investigation_urine][type]",'absent',true,['id'=>'investigation_urine_absent','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                                                            <label for="investigation_urine_absent">
                                                                Absent
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 present-data urine-type-data-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Puscell : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_urine][puscell]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 urine-type-data d-none">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Urine Albumine : &nbsp;
                                                            </span>
                                                            {{Form::text("investigation[investigation_urine][urine_albumine]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row anc-data d-none">
                                                    {{-- anc profile --}}
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            HIV :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("investigation[anc_hiv]",'positive','',['id'=>'anc_profile_hiv_positive','class'=>'anc-profile-hiv'])}}
                                                            <label for="anc_profile_hiv_positive">
                                                                Positive
                                                            </label>

                                                            {{Form::radio("investigation[anc_hiv]",'negative','',['id'=>'anc_profile_hiv_nagative','class'=>'anc-profile-hiv'])}}
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
                                                            {{Form::radio("investigation[anc_hbsag]",'positive','',['id'=>'anc_profile_hbsag_positive','class'=>'anc-profile-hbsag'])}}
                                                            <label for="anc_profile_hbsag_positive">
                                                                Positive
                                                            </label>

                                                            {{Form::radio("investigation[anc_hbsag]",'negative','',['id'=>'anc_profile_hbsag_nagative','class'=>'anc-profile-hbsag'])}}
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
                                                            {{Form::radio("investigation[anc_vdrl]",'positive','',['id'=>'anc_profile_vdrl_positive','class'=>'anc-profile-vdrl'])}}
                                                            <label for="anc_profile_vdrl_positive">
                                                                Positive
                                                            </label>

                                                            {{Form::radio("investigation[anc_vdrl]",'negative','',['id'=>'anc_profile_vdrl_nagative','class'=>'anc-profile-vdrl'])}}
                                                            <label for="anc_profile_vdrl_nagative">
                                                                Negative
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 anc-data d-none">
                                                    <div class="anc-images-data"></div>
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
                            
                                                        {{Form::select("plan_of_management[termination_type]",[''=>'Select Reason','Obseravation'=>"Obseravation",'Surgery'=>"Surgery"],null,['class'=>'form-control termination_type'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Admission Date : &nbsp;
                                                        </span>
                                                        {{Form::text("plan_of_management[surgically_date]",null,['class'=>'form-control datetimepicker surgically_date'])}}
                                                    </div>
                                                    <span class="surgically-date-error form-error-msg"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Admission Time : &nbsp;
                                                        </span>
                                                        {{Form::text("plan_of_management[surgically_time]",null,['class'=>'form-control timepicker surgically_time'])}}
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
                                                    {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co','placeholder'=>'Enter medicine name'])}}
                                                </div>
                                            </div>
                                            <div class="page-loader-wrapper medicine-loader d-none">
                                                <div class="loader">
                                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                                </div>
                                            </div>
                                            <div class="medicine-data">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Follow Up : &nbsp;
                                            </span>
                                            {{Form::text("ho[follow_up]", '',['class'=>'form-control datetimepicker followup followup-date'])}}
                                        </div>
                                        <span class="gsac-no-data-followup form-error-msg"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            {{Form::textarea("ho[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                        </div>
                                        <span class="form-error-msg">
                                            {{$errors->first('remark')}}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            {{Form::textarea("ho[pt_remark]",'',['class'=>'form-control no-resize','placeholder'=>"Patient'sRemark",'rows'=>'5'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
                                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                    <a href="{{URL::to('anc-iui-ivf')}}" class="btn btn-default">Cancel</a>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>$.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>

    <script type="text/javascript">
        var durationData = {};
        var code = '';
        $(function () {
        //Datetimepicker plugin
            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                // minDate:new Date(),
                time:false,
                weekStart: 1
            });
            $('.lmd-date').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                time:false,
                weekStart: 1
            });

            $('.timepicker').bootstrapMaterialDatePicker({
                date: false,
                shortTime: true,
                format: 'hh:mm a',
                switchOnClick: true
            });

        });

        $('.co_value_data').selectize({
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
        $('.report-images').imageUploader({
            imagesInputName: 'investigation[report][images]',
        });
        $('#treatment-medicine').select2();
        $('.surgicallty-data').selectize({
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
        $(document).ready(function(){
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
            $('.complain-multi .show-tick').addClass('d-none');
            $('.duration-value .btn-group').addClass('d-none');
            $('.select2-search__field').css('width','280px');
            $(document).on('click','.next-appointment',function(){
                var isError = errorMessage();
                if (isError == true) {
                    $('#next-appointment').trigger('reset');
                    $('#next-appointment-modal').modal('show');
                }
            });
            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var formData = new FormData($("#gynec-form")[0]);
                if(this.value==1){
                    formData.append('is_print', 1);
                }
                gynecFormData(formData);
            });

            $(document).on('change','.followup',function(){
                var fDate = $(this).val();
                $('.f-date').val(fDate ? fDate : '');
            });

        });
        function gynecFormData(data,next=null){
            $('.seen-by-error').text('');
            // var isError = errorMessage();
            // if (isError == false) {
            //     return false;
            // }
            $('.p-info').removeClass('show');
            var weight=$('#weight').val();
            if(weight == ''){
                $('.weight').text('The weight is required');
                $('.p-info').addClass('show');
                return false;
            }
            if($('select.seen-by').val() == ''){
                $('.seen-by-error').text('Please select doctor');
                $('html, body').animate({
                    scrollTop: ($('.seen-by').offset().top - 150)
                }, 1000);
                return false;
            }
            var urlData = "{{URL::to('anc-iui-ivf')}}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'{{URL::to("gynec")}}',
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'json',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == 'true'){
                    window.location.href = urlData;
                }else if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    $('.gynec-id').val(data.id);
                }else{
                    location.reload();
                }
            });
        }
        function errorMessage() {
            var valid = 1;
            if ($("input[name='ho[follow_up]']").val() == '') {
                $('.gsac-no-data-followup').text('The Follow up Date is required.');
                $('html, body').animate({
                    scrollTop: ($('.gsac-no-data-followup').offset().top - 150)
                }, 200);
                return false;
            }
            return true;
        }
        var medicinesValue = @json($medicines);
    </script>
    
<script type="text/javascript">
    $('.duration-data').selectize({
        create: true,
        sortField: 'text'
    });
</script>
    <script src="{{url('public/js/gynec.js')}}"></script>
@stop
