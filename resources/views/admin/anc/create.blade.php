@extends('layouts.main')
@section('parentPageTitle', 'ANC Appointment')
@section('title', 'Add ANC Appointment')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@stop
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
$abArray = ['1'=>'Normal','2'=>"Abnormal"];
$wnlArray = ['1'=>"WNL",'2'=>"Abnormal"];
@endphp
@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong class="text-secondary">{{ucwords($ancPatients->name)}}</strong></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>ANC Previous Visit</strong></h2>
                    
                    <ul class="header-dropdown col-md-12 align-right">
                        <li class="w-50">
                            @if(!empty($getTotalAncNumber))
                                <li class="w-25">
                                    {{Form::select("previous_anc_id",$getTotalAncNumber,'',['class'=>'form-control select-padding-0 anc_visit_id','placeholder'=>'Select Previous Anc.','data-class'=>'previous'])}}
                                </li>
                            @endif
                        </li>
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
                            <input type="hidden" name="" value="{{encrypt($ancPatients->id)}}" id="pID">
                            <input type="hidden" name="" value="{{encrypt($appointmentData['id'])}}" id="aID">
                            {{Form::open(['class'=>'form anc','files'=>true,'id'=>'anc-form','enctype'=>'multipart/form-data'])}}
                            {{Form::hidden('anc_history_type','anc')}}
                            {{Form::hidden('anc_id', null, ['id' => 'anc_id'])}}
                            @if($isIvf)
                                <div class="row">
                                    <span class="col-md-1 text-danger ivf-remark">Remark</span>
                                    <div class="col-md-1 text-danger">Plan :- &nbsp;<span class="">{{$plan}}</span></div>
                                    <div class="col-md-1 text-danger">Frozen :- &nbsp;<span class="">{{ucwords($frozen)}}</span></div>
                                    <div class="col-md-2 text-danger">Transfer :- &nbsp;<span class="">{{ucwords(preg_replace("/_/"," ",$transfer))}}</span></div>
                                </div><br><br>
                            @endif
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
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        RMO Doctor :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select('rmo_doctor',$rmoDoctor,'',['class'=>'form-control select-padding-0','placeholder'=>'Select RMO Doctor'])}}
                                    </div>
                                </div>
                            </div>

                            <!-- patients basic information -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" href="#patients" aria-expanded="false"
                                                                aria-controls="patients">1. Patients Basic Information</a> </h4>
                                </div>
                                <div id="patients" class="panel-collapse collapse p-info" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Name : &nbsp;</span>
                                                    {{Form::text('name',$ancPatients->name,['class'=>'form-control name'])}}
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
                                                    {{Form::text('code',$ancPatients->code,['class'=>'form-control code','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('code')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Age : &nbsp;</span>
                                                    {{Form::number("p_info[age]",$ancPatients->age,['class'=>'form-control age'])}}
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
                                                    {{$errors->first('weight')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @php
                                                $classData = !empty($appointmentData) ? '3' : '6';
                                            @endphp
                                            <div class="{{'col-md-'.$classData}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Mobile : &nbsp;</span>
                                                    {{Form::number('mobile_number',$ancPatients->mobile_number,['class'=>'form-control mobile_number'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('mobile_number')}}
                                                </span>
                                            </div>
                                            @if(!empty($appointmentData))
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{Form::select('category',$category,$appointmentData['category_id'],['class'=>'form-control select-padding-0 category_data change-category ctgry','placeholder'=>'Select Category'])}}
                                                    </div>
                                                </div>
                                                {{Form::hidden('appointment_id',encrypt($appointmentData['id']))}}
                                            @endif
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
                                                    {{Form::select('rd_reference',$referenceDoctor,$ancPatients->reference_doctor_id,['class'=>'form-control select-padding-0 refence-doctor','placeholder'=>'Rd Reference'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('rd_reference')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rd Mobile : &nbsp;</span>
                                                    {{Form::number('rd_mobile_number',$ancPatients->getReferenceDoctor['mobile_number'],['class'=>'form-control ref-mobile-number'])}}
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
                                                    {{Form::text('residence',$ancPatients->residence,['class'=>'form-control'])}}
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
                                                    {{Form::text('main_area',$ancPatients->main_area,['class'=>'form-control'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('main_area')}}
                                                    </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">City : &nbsp;</span>
                                                    {{Form::text('city',$ancPatients->city,['class'=>'form-control'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('city')}}
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    {{Form::textarea("p_info[other_info]",'',['class'=>'form-control no-resize other_info','placeholder'=>'Other Information','rows'=>'2'])}}
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
                                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#ho" aria-expanded="false"
                                                               aria-controls="patients-detailed-ho">2. H/O</a></h4>
                                </div>
                                <div id="ho" class="panel-collapse collapse ho-tab" role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class='col-md-8 complain-multi duration-value'>
                                                {{Form::select('ho[ho_details]',$hoData,'',['class'=>'form-control ho-data select-padding-0 duration-data anc-dose-val ho_type_value','placeholder'=>'Select H/O','data-medicine'=>2])}}
                                                <span class="form-error-msg ho-data-msg">
                                                        {{$errors->first('ho_details')}}
                                                    </span>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{Form::text("ho[amenorrhoea]",'Amenorrhoea',['class'=>'form-control','readonly'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('amenorrhoea')}}
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::select("ho[ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 ho_type'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('ho_details_2')}}
                                                    </span>
                                            </div>
                                            <div class="col-md-6 d-none when-where">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("ho[when_where]",'',['class'=>'form-control'])}}
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
                                                    {{Form::radio("p_obstratics[upt_type]",'positive','',[
                                                        'id'=>'positive',
                                                        'class'=>'upt-type',
                                                    ])}}
                                                    <label for="positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("p_obstratics[upt_type]",'negative','',[
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
                                                    {{Form::text('p_obstratics[upt_details]','',[
                                                        'class'=>'form-control upt_details datetimepicker date',
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
                                    </div>
                                </div>
                            </div>

                            <!-- C/O -->
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

                            <!-- obstratics history -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#accordion_1" href="#obstratics_history"
                                                               aria-expanded="false"
                                                               aria-controls="obstratics_history">4. Obstetric History</a></h4>
                                </div>
                                <div id="obstratics_history" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">First Marriage Life : &nbsp;</span>
                                                    {{Form::text("p_obstratics[first_marriage_life]",'',['class'=>'form-control'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('marriage_life')}}
                                                    </span>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("p_obstratics[child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],'',['class'=>'form-control child-no select-padding-0','placeholder'=>'Child No'])}}
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
                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_term]",'full','',['id'=>'full'])}}
                                                    <label for="full">
                                                        Fullterm
                                                    </label>

                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_term]",'pre','',['id'=>'pre'])}}
                                                    <label for="pre">
                                                        Preterm
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                {{Form::text("p_obstratics[child][child_data][1][ho_term_details]", '', [
                                                    'placeholder' => 'Term Details',
                                                    'class'=>'form-control',
                                                    'id'=>'term_details'
                                                ])}}
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_type_value]",'normal',true,['id'=>'normal'])}}
                                                    <label for="normal">
                                                        Normal
                                                    </label>

                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_type_value]",'cesarean','',['id'=>'cesarean'])}}
                                                    <label for="cesarean">
                                                        Cesarean
                                                    </label>

                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_type_value]",'instrumental','',['id'=>'instrumental'])}}
                                                    <label for="instrumental">
                                                        Instrumental
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_gender]",'male','',['id'=>'ho_male'])}}
                                                    <label for="ho_male">
                                                        Male
                                                    </label>

                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_gender]",'female','',['id'=>'ho_female'])}}
                                                    <label for="ho_female">
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row child-data-parent d-none">
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_birth_type]",'live_health','',['id'=>'live_health','class'=>'health-type','data-id'=>1])}}
                                                    <label for="live_health">
                                                        Live Health
                                                    </label>

                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_birth_type]",'stil_birth','',['id'=>'stil_birth','class'=>'health-type','data-id'=>1])}}
                                                    <label for="stil_birth">
                                                        Stil Birth
                                                    </label>

                                                    {{Form::radio("p_obstratics[child][child_data][1][ho_birth_type]",'expired','',['id'=>'expired','class'=>'health-type','data-id'=>1])}}
                                                    <label for="expired">
                                                        Expired
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 expired-reason-1 d-none">
                                                <div class="form-group">
                                                    {{Form::text("p_obstratics[child][child_data][1][expired_reason]",'',['class'=>'form-control','placeholder'=>'Reason'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('reason')}}
                                                    </span>
                                            </div>
                                            <div class="col-sm-3 expired-reason-1 d-none">
                                                <div class="form-group">
                                                    {{Form::text("p_obstratics[child][child_data][1][expired_year]",'',['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                                    {{Form::text("p_obstratics[child][child_data][1][live_health_year]",'',['class'=>'form-control'])}}
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
                                                    {{Form::select("p_obstratics[child][child_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 child-ho-type p-ho-type','data-id'=>'child-when-where-1','placeholder'=>'Select Child Status'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('ho_details_1')}}
                                                    </span>
                                            </div>
                                            <div class="col-md-4 d-none child-when-where-1 ho-type-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("p_obstratics[child][child_data][1][when_where]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-data">
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">MTP : &nbsp;</span>
                                                    {{Form::number("p_obstratics[mtp_no]",'0',['class'=>'form-control oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
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
                                                    {{Form::radio("p_obstratics[mtp][mtp_data][1][mtp_status]",'yes','',['id'=>'history_yes','class'=>'mtp-status','data-id'=>1])}}
                                                    <label for="history_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("p_obstratics[mtp][mtp_data][1][mtp_status]",'no',true,['id'=>'history_no','class'=>'mtp-status','data-id'=>1])}}
                                                    <label for="history_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row col-md-9 d-none mtp-visible-1">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("p_obstratics[mtp][mtp_data][1][mtp_type]",'medically','',['id'=>'Medically'])}}
                                                        <label for="Medically">
                                                            Medically
                                                        </label>
                                                        {{Form::radio("p_obstratics[mtp][mtp_data][1][mtp_type]",'surgically','',['id'=>'surgically'])}}
                                                        <label for="surgically">
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("p_obstratics[mtp][mtp_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("p_obstratics[mtp][mtp_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mtp-data-parent d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-4 mtp-naturally d-none">
                                                <div class="form-group">
                                                    {{Form::select("p_obstratics[mtp][mtp_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 mtp-ho-type p-ho-type','data-id'=>'mtp-when-where-1','placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 ml-4 d-none mtp-when-where-1 when-where-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("p_obstratics[mtp][mtp_data][1][when_where]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mtp-data">
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Abortion : &nbsp;</span>
                                                    {{Form::number("p_obstratics[abortion_no]",'0',['class'=>'form-control abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
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
                                                    {{Form::radio("p_obstratics[abortion][abortion_data][1][spontancous_abortion_status]",'yes','',['id'=>'spontancous_abortion_yes','class'=>'abortion-status','data-id'=>1])}}
                                                    <label for="spontancous_abortion_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("p_obstratics[abortion][abortion_data][1][spontancous_abortion_status]",'no',true,['id'=>'spontancous_abortion_no','class'=>'abortion-status','data-id'=>1])}}
                                                    <label for="spontancous_abortion_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row col-md-8 d-none abortion-visible-1">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("p_obstratics[abortion][abortion_data][1][spontancous_abortion_type]",'medically','',['id'=>'spontancous_abortion_medically'])}}
                                                        <label for="spontancous_abortion_medically">
                                                            Medically
                                                        </label>
                                                        {{Form::radio("p_obstratics[abortion][abortion_data][1][spontancous_abortion_type]",'surgically','',['id'=>'spontancous_abortion_surgically'])}}
                                                        <label for="spontancous_abortion_surgically">
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("p_obstratics[abortion][abortion_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("p_obstratics[abortion][abortion_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row abortion-data-parent d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-4 abortion-naturally d-none">
                                                <div class="form-group">
                                                    {{Form::select("p_obstratics[abortion][abortion_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 abortion-ho-type p-ho-type','data-id'=>'abortion-when-where-1','placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-none abortion-when-where-1 when-where-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("p_obstratics[abortion][abortion_data][1][when_where]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="abortion-data">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="vertical-form-label pr-0">
                                                    Second Merriage Life :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[second_marriage_life]",'yes','',['id'=>'second_marriage_life_yes','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'1'])}}
                                                    <label for="second_marriage_life_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage_life]",'no',true,['id'=>'second_marriage_life_no','class'=>'second-marriage-life-type','data-id'=>1,'data-type'=>'1'])}}
                                                    <label for="second_marriage_life_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 second-marriage-life d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Second Marriage Life : &nbsp;</span>
                                                    {{Form::text("p_obstratics[second_marriage_details]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- for child -->
                                        <div class="row mt-3 second-marriage-life d-none">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("p_obstratics[second_marriage][child_no]",['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'],'',['class'=>'form-control second-child-no select-padding-0','placeholder'=>'Child No','data-status'=>'second'])}}
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
                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_term]",'full','',['id'=>'second_full'])}}
                                                    <label for="second_full">
                                                        Fullterm
                                                    </label>
                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_term]",'pre','',['id'=>'second_pre'])}}
                                                    <label for="second_pre">
                                                        Preterm
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                {{Form::text("p_obstratics[second_marriage][child][child_data][1][ho_term_details]", '', [
                                                    'placeholder' => 'Term Details',
                                                    'class'=>'form-control',
                                                    'id'=>'second_merriage_term_details'
                                                ])}}
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_type_value]",'normal',true,['id'=>'second_normal'])}}
                                                    <label for="second_normal">
                                                        Normal
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_type_value]",'cesarean','',['id'=>'second_cesarean'])}}
                                                    <label for="second_cesarean">
                                                        Cesarean
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_type_value]",'instrumental','',['id'=>'second_instrumental'])}}
                                                    <label for="second_instrumental">
                                                        Instrumental
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_gender]",'male','',['id'=>'second_ho_male'])}}
                                                    <label for="second_ho_male">
                                                        Male
                                                    </label>
                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_gender]",'female','',['id'=>'second_ho_female'])}}
                                                    <label for="second_ho_female">
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row second-marriage-life-data second-child-data-parent d-none">
                                            <div class="col-sm-1">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_birth_type]",'live_health','',['id'=>'second_live_health','class'=>'health-type','data-id'=>'second1'])}}
                                                    <label for="second_live_health">
                                                        Live Health
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_birth_type]",'stil_birth','',['id'=>'second_stil_birth','class'=>'health-type','data-id'=>'second1'])}}
                                                    <label for="second_stil_birth">
                                                        Stil Birth
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage][child][child_data][1][ho_birth_type]",'expired','',['id'=>'second_expired','class'=>'health-type','data-id'=>'second1'])}}
                                                    <label for="second_expired">
                                                        Expired
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 expired-reason-second1 d-none">
                                                <div class="form-group">
                                                    {{Form::text("p_obstratics[second_marriage][child][child_data][1][expired_reason]",'',['class'=>'form-control','placeholder'=>'Reason'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-2 expired-reason-second1 d-none">
                                                <div class="form-group">
                                                    {{Form::text("p_obstratics[second_marriage][child][child_data][1][expired_year]",'',['class'=>'form-control','placeholder'=>'Expired Year'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Live Health Year : &nbsp;</span>
                                                    {{Form::text("p_obstratics[second_marriage][child][child_data][1][live_health_year]",'',['class'=>'form-control'])}}
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
                                                    {{Form::select("p_obstratics[second_marriage][child][child_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 child-ho-type second-p-ho-type','data-id'=>'second-child-when-where-1','placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-none second-marriage-life-data second-child-when-where-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("p_obstratics[second_marriage][child][child_data][1][when_where]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="second-marriage-life second-child-data d-none">
                                        </div>
                                        <div class="second-marriage-life row mt-3 d-none">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">MTP : &nbsp;</span>
                                                    {{Form::number("p_obstratics[second_marriage][mtp_no]",'0',['class'=>'form-control second_oh_mtp','min'=>'1','max'=>'12','onwheel'=>"this.blur()",'data-status'=>'second'])}}
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
                                                    {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][1][mtp_status]",'yes','',['id'=>'second_history_yes','class'=>'second-mtp-status','data-id'=>1])}}
                                                    <label for="second_history_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][1][mtp_status]",'no',true,['id'=>'second_history_no','class'=>'second-mtp-status','data-id'=>1])}}
                                                    <label for="second_history_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row col-md-9 d-none second-mtp-visible-1">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][1][mtp_type]",'medically','',['id'=>'second_Medically'])}}
                                                        <label for="second_Medically">
                                                            Medically
                                                        </label>
                                                        {{Form::radio("p_obstratics[second_marriage][mtp][mtp_data][1][mtp_type]",'surgically','',['id'=>'second_surgically'])}}
                                                        <label for="second_surgically">
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("p_obstratics[second_marriage][mtp][mtp_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("p_obstratics[second_marriage][mtp][mtp_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row second-marriage-life-data second-mtp-data-parent d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-4 second-mtp-naturally second-marriage-life-data d-none">
                                                <div class="form-group">
                                                    {{Form::select("p_obstratics[second_marriage][mtp][mtp_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 mtp-ho-type second-p-ho-type','data-id'=>'second-mtp-when-where-1','placeholder'=>'Select Child Status'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('ho_details_2')}}
                                                    </span>
                                            </div>
                                            <div class="col-md-4 d-none second-marriage-life-data second-mtp-when-where-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("p_obstratics[second_marriage][mtp][mtp_data][1][when_where]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="second-marriage-life second-mtp-data d-none">
                                        </div>
                                        <div class="row second-marriage-life mt-3 d-none">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Abortion : &nbsp;</span>
                                                    {{Form::number("p_obstratics[second_marriage][abortion_no]",'0',['class'=>'form-control second-abortion-no','min'=>'1','max'=>'12','onwheel'=>"this.blur()"])}}
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
                                                    {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][1][spontancous_abortion_status]",'yes','',['id'=>'second_spontancous_abortion_yes','class'=>'second-abortion-status','data-id'=>1,'data-type'=>'second'])}}
                                                    <label for="second_spontancous_abortion_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][1][spontancous_abortion_status]",'no',true,['id'=>'second_spontancous_abortion_no','class'=>'second-abortion-status','data-id'=>1,'data-type'=>'second'])}}
                                                    <label for="second_spontancous_abortion_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row col-md-8 d-none second-abortion-visible-1">
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][1][spontancous_abortion_type]",'medically','',['id'=>'second_spontancous_abortion_medically'])}}
                                                        <label for="second_spontancous_abortion_medically">
                                                            Medically
                                                        </label>
                                                        {{Form::radio("p_obstratics[second_marriage][abortion][abortion_data][1][spontancous_abortion_type]",'surgically','',['id'=>'second_spontancous_abortion_surgically'])}}
                                                        <label for="second_spontancous_abortion_surgically">
                                                            Surgically
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">MOA &nbsp;</span>
                                                        {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Before &nbsp;</span>
                                                        {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row second-marriage-life-data second-abortion-data-parent d-none">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-4 second-abortion-naturally d-none second-marriage-life-data">
                                                <div class="form-group">
                                                    {{Form::select("p_obstratics[second_marriage][abortion][abortion_data][1][ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI','4'=>'IVF'],'',['class'=>'form-control select-padding-0 abortion-ho-type second-p-ho-type','data-id'=>'second-abortion-when-where-1','placeholder'=>'Select Child Status'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-none second-marriage-life-data second-abortion-when-where-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon">When / Where : &nbsp;</span>
                                                    {{Form::text("p_obstratics[second_marriage][abortion][abortion_data][1][when_where]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="second-marriage-life second-abortion-data d-none">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {{Form::textarea('oh[remark]', null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                                                </div>
                                            </div>
                                        </div>
                                        {{Form::hidden('patients_id',$patients_id)}}
                                    </div>
                                </div>
                            </div>

                            <!-- Personal history  -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#personal-history" href="#personal-history" aria-expanded="false"
                                                                aria-controls="personal-history ">5. M/H</a></h4>
                                </div>
                                <div id="personal-history" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    {{Form::select("mh[age_of_menarchy]",[11=>11,12=>12,13=>13,14=>14,15=>15],'',['class'=>'form-control select-padding-0','placeholder'=>'Age Of Menarchy'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Since Year : &nbsp;</span>
                                                    {{Form::text("mh[since_year]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    {{Form::text("mh[age_of_manopause]",'',['class'=>'form-control','placeholder'=>'Age Of Manopause'])}}
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Since Year : &nbsp;</span>
                                                    {{Form::text("mh[manopause_since_year]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label class="vertical-form-label">
                                                    Past M/H :
                                                </label>
                                            </div>
                                            <div class="col-md-1 past-ir-regular-data d-none">
                                                {{Form::select("mh[past_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],'',['class'=>'form-control select-padding-0 past-mh-1'])}}
                                                <span class="form-error-msg">
                                                        {{$errors->first('past_mh_1')}}
                                                    </span>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    {{Form::select("mh[past_mh_2]",['regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0 past-mh-2 regular-type','data-id'=>'past-ir-regular-data'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('past_mh_2')}}
                                                    </span>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    {{Form::select("mh[past_mh2_2]",['regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0'])}}
                                                </div>
                                            </div>
                                            <div class='col-md-2 complain-multi duration-value past-ir-regular-data d-none'>
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
                                            <div class="col-md-12 text-center">
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
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label">
                                                    Present M/H:
                                                </label>
                                            </div>

                                            <div class="col-md-1 present-ir-regular-data d-none">
                                                {{Form::select("mh[present_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],'',['class'=>'form-control select-padding-0 present-mh-1'])}}
                                                <span class="form-error-msg">
                                                        {{$errors->first('present_mh_1')}}
                                                    </span>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    {{Form::select("mh[present_mh_2]",['regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0 present-mh-2 regular-type','data-id'=>'present-ir-regular-data'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    {{Form::select("mh[present_mh2_2]",['regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0'])}}
                                                </div>
                                            </div>
                                            <div class='col-md-2 complain-multi duration-value present-duration-data present-ir-regular-data d-none'>
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

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Last Menstrual Date : &nbsp;</span>
                                                    {{Form::text("mh[last_menstrual_date]",$lastVisitLmpDate,['class'=>'form-control lmd-date','required'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('last_mentsrual_date')}}
                                                    </span>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group edd-week-data">
                                                    <span class="input-group-addon">EDD : &nbsp;</span>
                                                    {{Form::text("mh[edd]",'',['class'=>'form-control edd-date','readonly'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first("p_details[edd]")}}
                                                    </span>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group edd-week-data">
                                                    <span class="input-group-addon">USG EDD : &nbsp;</span>
                                                    {{Form::text("mh[usg_edd]",'',['class'=>'form-control datetimepicker usg-edd-date'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#patients-detailed-ho" href="#patients-detailed-ho" aria-expanded="false"
                                                                aria-controls="patients-detailed-ho">6. Patients Detailed H/O</a></h4>
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
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- OE  -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title tab-highlight-green"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#oe" href="#oe" aria-expanded="false"
                                                                aria-controls="oe">7. O/E <span class="week-message text-danger"> </span> </a>
                                    </h4>
                                </div>
                                <div id="oe" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
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
                                                    {{Form::text("oe[le][temp]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Pulse : &nbsp;</span>
                                                    {{Form::text("oe[le][pulse]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <span class="col-md-1 p-2">/ Min</span>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">B.P : &nbsp;</span>
                                                    {{Form::text("oe[le][bp]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <span class="col-md-1 p-2">MMHG</span>
                                        </div>
                                        <div class="row female-type-data">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[utdata][1][female_type]",'Intrauterine','',['id'=>'female-intrauterine','class'=>'female-type','data-id'=>1])}}
                                                    <label for="female-intrauterine">
                                                        Intrauterine
                                                    </label>

                                                    {{Form::radio("oe[utdata][1][female_type]",'Ectopic','',['id'=>'female-ectopic','class'=>'female-type','data-id'=>1])}}
                                                    <label for="female-ectopic">
                                                        Ectopic
                                                    </label>

                                                    {{Form::radio("oe[utdata][1][female_type]",'Molar Pregnancy','',['id'=>'female-mp-1','class'=>'female-type','data-id'=>1])}}
                                                    <label for="female-mp-1">
                                                        Molar Pregnancy
                                                    </label>
                                                    {{Form::radio("oe[utdata][1][female_type]",'No intrauterine or extrauterine G- Sac seen at present','',['id'=>'female-gsac-1','class'=>'female-type','data-id'=>1])}}
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
                                                        {{Form::select("oe[uterus][type]",$abArray,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
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
                                                                {{Form::text("oe[ovary][right][afcs]",'',['class'=>'form-control create-right-ovary-data-text'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 right-details">
                                                            <div class="complain-multi tvs-details mt-1">
                                                                {{Form::select("oe[ovary][right][details][]",$rightOvaryData,'',[
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
                                                                {{Form::select("oe[uterus][right][adnexia]",$abArray,'',['class'=>'form-control abnormal','data-type'=>'adnexia-right-abnormal-type'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 adnexia-right-abnormal-type d-none">
                                                            <div class="form-group">
                                                                {{Form::text("oe[uterus][right][adnexia_details]",'',['class'=>'form-control','placeholder'=>'Adnexia Details'])}}
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
                                                                {{Form::text("oe[ovary][left][afcs]",'',['class'=>'form-control create-left-ovary-data-text'])}}

                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 left-details">
                                                            <div class="complain-multi tvs-details">
                                                                {{Form::select("oe[ovary][left][details][]",$leftOvaryData,'',[
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
                                                        <div class="col-md-12 complain-multi tvs-details">
                                                            <div class="edit_oe_ovary_right_details">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 pr-0">
                                                            <label class="vertical-form-label pr-0">
                                                                Left Adnexia :
                                                            </label>
                                                        </div>
                                                        <div class="col-md-4 tvs-details">
                                                            <div class="form-group">
                                                                {{Form::select("oe[uterus][left][adnexia]",$abArray,'',['class'=>'form-control abnormal','data-type'=>'adnexia-left-abnormal-type'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 adnexia-left-abnormal-type d-none">
                                                            <div class="form-group">
                                                                {{Form::text("oe[uterus][left][adnexia_details]",'',['class'=>'form-control','placeholder'=>'Adnexia Details'])}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Select child :
                                                </label>
                                            </div>
                                            {{Form::hidden('oe[late_concept]','',['class'=>'late-concept'])}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{Form::select("oe[oe_no]",['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'],'1',['class'=>'form-control select-padding-0 oe-no'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('oe_no')}}
                                                    </span>
                                            </div>
                                            <div class="{{'col-md-4 oe-child-type d-none'}}">
                                                <div class="form-group">
                                                    {{Form::select("oe[oe_child_type]",$childType,'',[
                                                        'class'=>'form-control select-padding-0',
                                                        'placeholder'=>'Select Child Status'
                                                    ])}}
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
                                                    {{Form::select("oe[utdata][1][oe_ut_sac_1]",$weekData,'',['class'=>'form-control ut-sac max-1 crl-number g-sac-no female-type-week-1','onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'data-id'=>'1','data-value'=>'anc-history','placeholder'=>'Select Week'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                        {{$errors->first('oe_ut_sac')}}
                                                </span>
                                            </div>
                                            <div class="col-md-5 pa-details">
                                                <div class="form-group">
                                                    {{Form::text("oe[p_a][details]",'',['class'=>'form-control','placeholder'=>'P/A details'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Late Conception :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[late_data][late_concept]",'Yes','',['id'=>'l-yes','class'=>'late-conception-type'])}}
                                                    <label for="l-yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oe[late_data][late_concept]",'no',true,['id'=>'l-no','class'=>'late-conception-type'])}}
                                                    <label for="l-no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 late-concepte-week d-none">
                                                <div class="form-group">
                                                    {{Form::text("oe[late_data][late_concept_week]",'',['class'=>'form-control late-week','onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'placeholder'=>'Enter Week'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="child-no-box">
                                            <div class="row">
                                                <div class="col-md-2 ut-textbox-1 d-none">
                                                    {{Form::text('oe[utdata][1][oe_ut_sac_1_value]','',['class'=>'form-control'])}}
                                                    {{Form::hidden('oe[utdata][1][oe_ut_sac_1_status]','yes')}}
                                                </div>
                                                <div class="col-md-1 female-type-data-1 pr-0 gsac-data-1">
                                                    <label class="vertical-form-label pr-0">
                                                        G-sac(MM) :
                                                    </label>
                                                </div>
                                                <div class="col-md-1 female-type-data-1 g-sac ut-g-sac gsac-data-1">
                                                    <div class="form-group">
                                                        {{Form::text("oe[utdata][1][oe_ut_sac_2]",'',['class'=>'form-control g-sac-1','onwheel'=>"this.blur()",'oninput'=>"maxLengthCheck(this)",'data-id'=>'1','data-value'=>'anc-history'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-none ut-g-sac-details-1">
                                                    <div class="form-group">
                                                        {{Form::text("oe[utdata][1][oe_ut_sac_details]",'',['class'=>'form-control ut-sac-details','placeholder'=>'Details'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                            {{$errors->first('oe_ut_sac')}}
                                                        </span>
                                                </div>
                                                <div class='col-md-1 female-type-data-1 pr-0 crl-data-value-1 d-none'>
                                                    <label class='vertical-form-label pr-0'>CRL :</label>
                                                </div>
                                                <div class="col-md-2 female-type-data-1 crl-1 crl-data-value-1 d-none">
                                                    <div class="form-group">
                                                        {{Form::number("oe[utdata][1][crl]",'',['class'=>'form-control crl-data','data-id'=>1])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1 female-type-data-1 p-1 crl-1 crl-data-value-1 d-none">
                                                    <span class='crl-text-1'></span>
                                                    {{Form::hidden("oe[utdata][1][crl_details]",'',['class'=>'crl-val-1'])}}
                                                </div>
                                                <div class="col-md-1 female-type-data-1 pr-0 blighted-ovum-data blighted-ovum-data-1 d-none">
                                                    <label class="vertical-form-label pr-0">
                                                        Blighted Ovum :
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 female-type-data-1 blighted-ovum-data blighted-ovum-data-1 d-none">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[utdata][1][blighted_ovum]",'yes','',['id'=>'blighted-ovum-yes-1','class'=>'blighted-ovum'])}}
                                                        <label for="blighted-ovum-yes-1">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][blighted_ovum]",'no',true,['id'=>'blighted-ovum-no-1','class'=>'blighted-ovum'])}}
                                                        <label for="blighted-ovum-no-1">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row yalk-sac-1 female-type-data-1 gsac-data-1">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Yolk Sac :
                                                    </label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[utdata][1][yalk_sac]",'present','',['id'=>'present','class'=>'yalk_sac'])}}
                                                        <label for="present">
                                                            Present
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][yalk_sac]",'absent','',['id'=>'absent','class'=>'yalk_sac'])}}
                                                        <label for="absent">
                                                            Absent
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][yalk_sac]",'none','',['id'=>'none','class'=>'yalk_sac'])}}
                                                        <label for="none">
                                                            None
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {{Form::text("oe[utdata][1][yalk_sac_size]",'',['class'=>'form-control','placeholder'=>'Yolk Sac Size'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pr-0 gsac-data-1 fefal-pole-data-1 d-none">
                                                    <label class="vertical-form-label pr-0">
                                                        Fetal Pole :
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 fefal-pole-data-1 d-none">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[utdata][1][fefal_pole]",'seen','',['id'=>'seen','class'=>'fefal-pole','data-id'=>'1'])}}
                                                        <label for="seen">
                                                            Seen
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][fefal_pole]",'notseen','',['id'=>'unseen','class'=>'fefal-pole','data-id'=>'1'])}}
                                                        <label for="unseen">
                                                            Not Seen
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][fefal_pole]",'none','',['id'=>'none-fefa','class'=>'fefal-pole','data-id'=>'1'])}}
                                                        <label for="none-fefa">
                                                            None
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row wks-data-1">
                                                <div class="col-md-1 extra-female-data-1 fcp-data-1 pr-0 d-none">
                                                    <label class="vertical-form-label pr-0 green-lable">
                                                        FCA :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2 extra-female-data-1 fcp-data-1 d-none">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[utdata][1][fcp]",'present','',['id'=>'fcp_present','class'=>'fcp_type fcp-type-1'])}}
                                                        <label for="fcp_present">
                                                            Present
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][fcp]",'absent','',['id'=>'fcp_absent','class'=>'fcp_type fcp-type-1'])}}
                                                        <label for="fcp_absent">
                                                            Absent
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][fcp]",'none','',['id'=>'none_wk_data','class'=>'fcp_type fcp-type-1'])}}
                                                        <label for="none_wk_data">
                                                            None
                                                        </label>
                                                    </div>
                                                    <span class="fcp-error text-danger"></span>
                                                </div>
                                                <div class="col-md-1 pr-0 extra-female-data-1 liquor-data-1 d-none">
                                                    <label class="vertical-form-label pr-0 green-lable">
                                                        Liquor :
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 extra-female-data-1 liquor-data-1 d-none">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[utdata][1][liquor_type]",'normal','',['id'=>'liquor_normal','class'=>'liquor','data-id'=>1])}}
                                                        <label for="liquor_normal">
                                                            Normal
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][liquor_type]",'oligo','',['id'=>'liquor_oligo','class'=>'liquor','data-id'=>1])}}
                                                        <label for="liquor_oligo">
                                                            Oligo
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][liquor_type]",'poly','',['id'=>'liquor_poly','class'=>'liquor','data-id'=>1])}}
                                                        <label for="liquor_poly">
                                                            Poly
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 extra-female-data-1 liquor-data-1">
                                                    <div class="radio is-conceived liquor-sub-type-data-1 d-none">
                                                        {{Form::radio("oe[utdata][1][liquor_sub_type]",'mild','',['id'=>'mild','class'=>'mild'])}}
                                                        <label for="mild">
                                                            Mild
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][liquor_sub_type]",'moderate','',['id'=>'moderate','class'=>'moderate'])}}
                                                        <label for="moderate">
                                                            Moderate
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][liquor_sub_type]",'severe','',['id'=>'severe','class'=>'severe'])}}
                                                        <label for="severe">
                                                            Severe
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-1 pr-0 extra-female-data-1 cervical-data-1 d-none">
                                                    <label class="vertical-form-label pr-0 green-lable">
                                                        Cervical length :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2 extra-female-data-1 cervical-data-1 d-none">
                                                    {{Form::text("oe[utdata][1][cervical_length]",'',['id'=>'cervical_length','class'=>'form-control cervical_length','data-id'=>1])}}
                                                </div>
                                                <div class="col-md-2 pr-0 extra-female-data-1 expected-data-1 d-none">
                                                    <label class="vertical-form-label pr-0 green-lable">
                                                        Expected Birth Weight :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2 extra-female-data-1 expected-data-1 d-none">
                                                    {{Form::text("oe[utdata][1][expected_birth_weight]",'',['id'=>'expected_birth_weight','class'=>'form-control expected_birth_weight','data-id'=>1])}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1 pr-0 extra-female-data-1 position-data-1 d-none">
                                                    <label class="vertical-form-label pr-0 green-lable">
                                                        Position :
                                                    </label>
                                                </div>
                                                <div class="col-sm-4 extra-female-data-1 position-data-1 d-none">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[utdata][1][position_type]",'vertex','',['id'=>'position_nertex','class'=>'position'])}}
                                                        <label for="position_nertex">
                                                            Vertex
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][position_type]",'breech','',['id'=>'position_breech','class'=>'position'])}}
                                                        <label for="position_breech">
                                                            Breech
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][position_type]",'transverse','',['id'=>'position_transverse','class'=>'position'])}}
                                                        <label for="position_transverse">
                                                            Transverse
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][position_type]",'oblique','',['id'=>'oblique','class'=>'position'])}}
                                                        <label for="oblique">
                                                            Oblique
                                                        </label>

                                                        {{Form::radio("oe[utdata][1][position_type]",'none','',['id'=>'none-2','class'=>'position'])}}
                                                        <label for="none-2">
                                                            None
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row extra-female-data-1 p-data-1 d-none">
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="vertical-form-label pr-0 green-lable">
                                                            Placenta :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            {{Form::select("oe[utdata][1][placenta][]", $placenta, '' ,['class'=>'form-control select-padding-0','multiple'=> 'multiple','title'=>'Placenta Details'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Color Dropler : &nbsp;</span>
                                                    {{Form::text("oe[utdata][1][color_dropler]",'',[
                                                        'class'=>'form-control',
                                                        'placeholder' => 'Color Dropler'
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="oe-data"></div>

                                        <div class="row gsac-no-data d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    EC Topic:
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[ec_topic]",'yes','',['id'=>'ec_topic_yes','class'=>'ec-topic-type'])}}
                                                    <label for="ec_topic_yes">
                                                        Yes
                                                    </label>
                                                    {{Form::radio("oe[ec_topic]",'no', true,['id'=>'ec_topic_no','class'=>'ec-topic-type'])}}
                                                    <label for="ec_topic_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row gsac-no-data d-none">
                                            <div class="col-md-12 ec-topic-data d-none">
                                                <div class="row">
                                                    <div class=" col-md-2 checkbox">
                                                        {{Form::checkbox('oe[ec_topics][]','expert_usg','',[
                                                            'id'=>'expert_usg',
                                                            'class'=>'ec-topic',
                                                            'data-id' => 'expert-usg-details'
                                                        ])}}
                                                        <label for="expert_usg">
                                                            Expert USG
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 expert-usg-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[expert_usg]",'',[
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Expert USG Details'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 expert-usg-details d-none">
                                                        <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    Date : &nbsp;
                                                                </span>
                                                            {{Form::text("oe[expert_usg_date]", '',[
                                                                'class'=>'form-control datetimepicker expert-usg-date f-date'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 expert-usg-details d-none">
                                                        <div class="form-group">
                                                            {{Form::file('oe[expert_usg_image]',[
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Select Expert USG Image',
                                                                'accept' => 'image/png,image/jpeg,image/jpg'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gsac-no-data d-none">
                                            <div class="col-md-12 ec-topic-data d-none">
                                                <div class="row">
                                                    <div class=" col-md-2 checkbox">
                                                        {{Form::checkbox('oe[ec_topics][]','blood_report','',[
                                                            'id'=>'blood_report',
                                                            'class'=>'ec-topic',
                                                            'data-id' => 'blood-report-details'
                                                        ])}}
                                                        <label for="blood_report">
                                                            Blood Report
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 blood-report-details d-none">
                                                        <div class="form-group">
                                                            {{Form::text("oe[blood_report]",'',[
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Blood Report'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 blood-report-details d-none">
                                                        <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    Date : &nbsp;
                                                                </span>
                                                            {{Form::text("oe[blood_report_date]", '',[
                                                                'class'=>'form-control datetimepicker blood-report-date f-date'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 blood-report-details d-none">
                                                        <div class="form-group">
                                                            {{Form::file('oe[blood_report_image]',[
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Select Blood Report Image',
                                                                'accept' => 'image/png,image/jpeg,image/jpg'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gsac-no-data d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Treact:
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[treact][type]",'medical','',['id'=>'treact_medical','class'=>'treact-type'])}}
                                                    <label for="treact_medical">
                                                        Medical
                                                    </label>
                                                    {{Form::radio("oe[treact][type]",'surgical', true,['id'=>'treact_surgical','class'=>'treact-type'])}}
                                                    <label for="treact_surgical">
                                                        Surgical
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-9 treact-data">
                                                <div class="row treact-medically d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Medicine Details : &nbsp;</span>
                                                            {{Form::text("oe[treact][medicine_details]",'',[
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
                                                            ], '' ,[
                                                                'class'=>'form-control select-padding-0',
                                                                'title'=>'Doses'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row treact-surgically">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Operation : &nbsp;</span>
                                                            {{Form::text("oe[treact][surgical_details]",'',[
                                                                'class'=>'form-control treact-surgical-details',
                                                                'placeholder' => 'Operation Details'
                                                            ])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row fefal-reduction-data d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Fetal Reduction:
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[fefal_reduction][type]",'yes','',['id'=>'fefal_reduction_yes','class'=>'fefal-reduction-type','data-id'=>'1'])}}
                                                    <label for="fefal_reduction_yes">
                                                        Yes
                                                    </label>
                                                    {{Form::radio("oe[fefal_reduction][type]",'no', true,['id'=>'fefal_reduction_no','class'=>'fefal-reduction-type','data-id'=>'1'])}}
                                                    <label for="fefal_reduction_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 fefal-date d-none">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Date: &nbsp;</span>
                                                    {{Form::text("oe[fefal_reduction][date]",null,['class'=>'form-control datetimepicker'])}}
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
                                                    {{Form::radio("oe[p_s][type]",'yes','',['id'=>'ps_type_yes','class'=>'anc-status','data-type'=>'ps-details'])}}
                                                    <label for="ps_type_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oe[p_s][type]",'no',true,['id'=>'ps_type_no','class'=>'anc-status','data-type'=>'ps-details'])}}
                                                    <label for="ps_type_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-5 ps-details d-none">
                                                <div class="form-group">
                                                    {{Form::text("oe[p_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pv-data">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    P/V :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[p_v][type]",'yes','',['id'=>'pv_type_yes','class'=>'anc-status','data-type'=>'pv-details'])}}
                                                    <label for="pv_type_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("oe[p_v][type]",'no',true,['id'=>'pv_type_no','class'=>'anc-status','data-type'=>'pv-details'])}}
                                                    <label for="pv_type_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-5 pv-details d-none">
                                                <div class="form-group">
                                                    {{Form::text("oe[p_v][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-md-2 checkbox">
                                                {{Form::checkbox('oe[is_patient_remark]','0','',[
                                                    'id'=>'is_patient_remark',
                                                    'class'=>'anc-remark'
                                                ])}}
                                                <label for="is_patient_remark">
                                                  Patient's Remark
                                                </label>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::textarea('oe[pt_remark]', null, ['class'=>'form-control no-resize remark','placeholder'=>'Patient Remark','rows'=>'2'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::textarea('oe[remark]', null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12"><span class="font-12 text-danger">*If check Patient's Reamrk then remark is display in  patient's Application*</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Investigation  -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#investigation" href="#investigation" aria-expanded="false"
                                                                aria-controls="investigation">8. Investigation</a></h4>
                                </div>
                                <div id="investigation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">
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
                                                    {{Form::select("investigation[investigation_cbc_mp_details][status]",$wnlArray,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-details-value','placeholder'=>'Select CBC MB Type'])}}
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
                                                    {{Form::select("investigation[investigation_urine_value_details][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-details-value','placeholder'=>'Select CBC MB Type'])}}
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
                                                    {{Form::select("investigation[investigation_tsh_value_details][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'tsh-type-details-value','placeholder'=>'Select CBC MB Type'])}}
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
                                        <div class="row anc-profile anc-profile-details">
                                            <div class="col-md-3 anc-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Date : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_anc_date]",'',['class'=>'form-control datetimepicker date f-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 anc-profile-details d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Blood Group : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_blood_group]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 anc-profile-details d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            RBS : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_anc_rbs]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 anc-profile-details d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HIV :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 anc-profile-details d-none">
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
                                        </div>
                                        <div class="row anc-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    CBC MP :
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("investigation[investigation_cbc_mp][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'cbc-mb-type-data','placeholder'=>'Select CBC MB Type'])}}
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
                                        <div class="row anc-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Urine :
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("investigation[investigation_urine][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'urine-type-data','placeholder'=>'Select Urine Type'])}}
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
                                        <div class="row anc-profile-details d-none">
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
                                        <div class="row anc-profile pre-major-profile-details">
                                            <div class="col-md-3 anc-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Date : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_date]",'',['class'=>'form-control datetimepicker date f-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 pre-major-profile-details d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Blood Group : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_blood_group]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 pre-major-profile-details d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            RBS : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_rbs]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 pre-major-profile-details d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HIV :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre-major-profile-details d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("investigation[pre_minor_hiv]",'positive','',['id'=>'pre_minor_profile_hiv_positive','class'=>'anc-profile-hiv'])}}
                                                    <label for="pre_minor_profile_hiv_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("investigation[pre_minor_hiv]",'negative','',['id'=>'pre_minor_hiv_nagative','class'=>'anc-profile-hiv'])}}
                                                    <label for="pre_minor_hiv_nagative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-major-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    CBC MP :
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("investigation[investigation_pre_major_cbc_mp][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'pre-major-cbc-mb-type-data','placeholder'=>'Select CBC MB Type'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 pre-major-cbc-mb-type-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Aneamia : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_major_cbc_mp][aneamia]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 pre-major-cbc-mb-type-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Leacocytosis : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_major_cbc_mp][leacocytosis]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-major-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Urine :
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("investigation[investigation_pre_major_urine][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'pre-minor-urine-type-data','placeholder'=>'Select Urine Type'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 pre-minor-urine-type-data d-none">
                                                <label class="vertical-form-label pr-0">
                                                    Puccell :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre-major-urine-type-data d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("investigation[investigation_pre_major_urine][type]",'present','',['id'=>'pre_minor_investigation_urine_present','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                                                    <label for="pre_minor_investigation_urine_present">
                                                        Present
                                                    </label>

                                                    {{Form::radio("investigation[investigation_pre_major_urine][type]",'absent',true,['id'=>'pre_minor_investigation_urine_absent','class'=>'investigation-urine-type','data-id'=>'pre-minor-present-data'])}}
                                                    <label for="pre_minor_investigation_urine_absent">
                                                        Absent
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 pre-major-urine-type-data pre-minor-urine-type-data-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Puscell : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_major_urine][puscell]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 urine-type-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Urine Albumine : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_major_urine][urine_albumine]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-major-urine-type-data d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    HBSAG :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("investigation[pre_minor_hbsag]",'positive','',['id'=>'pre_major_hbsag_positive','class'=>'anc-profile-hbsag'])}}
                                                    <label for="pre_major_hbsag_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("investigation[pre_minor_hbsag]",'negative','',['id'=>'pre_minor_hbsag_nagative','class'=>'anc-profile-hbsag'])}}
                                                    <label for="pre_minor_hbsag_nagative">
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
                                                    {{Form::radio("investigation[pre_minor_vdrl]",'positive','',['id'=>'anc_profile_vdrl_positive','class'=>'anc-profile-vdrl'])}}
                                                    <label for="anc_profile_vdrl_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("investigation[pre_minor_vdrl]",'negative','',['id'=>'anc_profile_vdrl_nagative','class'=>'anc-profile-vdrl'])}}
                                                    <label for="anc_profile_vdrl_nagative">
                                                        Negative
                                                    </label>
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
                                        <div class="row anc-profile pre-minor-profile-details">
                                            <div class="col-md-3 anc-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Date : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_date]",'',['class'=>'form-control datetimepicker date f-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 pre-minor-profile-details d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Blood Group : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_blood_group]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 pre-minor-profile-details d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            RBS : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_rbs]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 pre-minor-profile-details d-none">
                                                <label class="vertical-form-label pr-0">
                                                    HIV :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre-minor-profile-details d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("investigation[pre_minor_hiv]",'positive','',['id'=>'pre_minor_profile_hiv_positive','class'=>'anc-profile-hiv'])}}
                                                    <label for="pre_minor_profile_hiv_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("investigation[pre_minor_hiv]",'negative','',['id'=>'pre_minor_hiv_nagative','class'=>'anc-profile-hiv'])}}
                                                    <label for="pre_minor_hiv_nagative">
                                                        Negative
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-minor-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    CBC MP :
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("investigation[investigation_pre_minor_cbc_mp][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>'pre-minor-cbc-mb-type-data','placeholder'=>'Select CBC MB Type'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 pre-minor-cbc-mb-type-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Aneamia : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_cbc_mp][aneamia]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 pre-minor-cbc-mb-type-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Leacocytosis : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_cbc_mp][leacocytosis]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-minor-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Urine :
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::select("investigation[investigation_pre_minor_urine][status]",$wnlArray,'',['class'=>'form-control select-padding-0 investigation-type','data-id'=>'pre-minor-urine-type-data','placeholder'=>'Select Urine Type'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-1 pr-0 pre-minor-urine-type-data d-none">
                                                <label class="vertical-form-label pr-0">
                                                    Puccell :
                                                </label>
                                            </div>
                                            <div class="col-sm-2 pre-minor-urine-type-data d-none">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("investigation[investigation_pre_minor_urine][type]",'present','',['id'=>'pre_minor_investigation_urine_present','class'=>'investigation-urine-type','data-id'=>'present-data'])}}
                                                    <label for="pre_minor_investigation_urine_present">
                                                        Present
                                                    </label>

                                                    {{Form::radio("investigation[investigation_pre_minor_urine][type]",'absent',true,['id'=>'pre_minor_investigation_urine_absent','class'=>'investigation-urine-type','data-id'=>'pre-minor-present-data'])}}
                                                    <label for="pre_minor_investigation_urine_absent">
                                                        Absent
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 pre-minor-present-data pre-minor-urine-type-data-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Puscell : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_urine][puscell]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 urine-type-data d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Urine Albumine : &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[investigation_pre_minor_urine][urine_albumine]",'',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pre-minor-profile-details d-none">
                                            <div class="col-md-1 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    HBSAG :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("investigation[pre_minor_hbsag]",'positive','',['id'=>'pre_minor_hbsag_positive','class'=>'anc-profile-hbsag'])}}
                                                    <label for="pre_minor_hbsag_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("investigation[pre_minor_hbsag]",'negative','',['id'=>'pre_minor_hbsag_nagative','class'=>'anc-profile-hbsag'])}}
                                                    <label for="pre_minor_hbsag_nagative">
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
                                                    {{Form::radio("investigation[pre_minor_vdrl]",'positive','',['id'=>'anc_profile_vdrl_positive','class'=>'anc-profile-vdrl'])}}
                                                    <label for="anc_profile_vdrl_positive">
                                                        Positive
                                                    </label>

                                                    {{Form::radio("investigation[pre_minor_vdrl]",'negative','',['id'=>'anc_profile_vdrl_nagative','class'=>'anc-profile-vdrl'])}}
                                                    <label for="anc_profile_vdrl_nagative">
                                                        Negative
                                                    </label>
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
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="checkbox">
                                                    {{Form::checkbox('investigation[investigation_data][]','31','',['id'=>'hb','class'=>'plan-management','data-id'=>'hb-data-details'])}}
                                                    <label for="hb">
                                                        HB
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 hb-data-details d-none">
                                                <div class="form-group">
                                                    {{Form::text("investigation[investigation_details][31]",'',['class'=>'form-control','placeholder'=>'HB Details'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row investigation-report">
                                            <div class="col-md-1 investigation-report pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Report Upload :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="radio is-conceived investigation-report">
                                                    {{Form::radio("investigation[other_report_type]",'yes','',['id'=>'other_report_type_yes','class'=>'other-report-type'])}}
                                                    <label for="other_report_type_yes">
                                                        Yes
                                                    </label>

                                                    {{Form::radio("investigation[other_report_type]",'no',true,['id'=>'other_report_type_no','class'=>'other-report-type'])}}
                                                    <label for="other_report_type_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-2 d-none or-details">
                                                <div class="checkbox investigation-report">
                                                    {{Form::checkbox('investigation[other_report][]','double_marker','',['id'=>'d-marker'])}}
                                                    <label for="d-marker">
                                                        Double marker
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-none or-details">
                                                <div class="input-group investigation-report">
                                                        <span class="input-group-addon">
                                                            Double Marker Date: &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[d_m_date]",'',['class'=>'form-control datetimepicker date f-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-none or-details">
                                                <div class="checkbox investigation-report">
                                                    {{Form::checkbox('investigation[other_report][]','genetic_test','',['id'=>'genetic-test'])}}
                                                    <label for="genetic-test">
                                                        Genetic Test
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row d-none or-details">
                                            <div class="col-md-2 investigation-report">
                                                <div class="checkbox">
                                                    {{Form::checkbox('investigation[other_report][]','amniocentesis','',['id'=>'amniocentesis'])}}
                                                    <label for="amniocentesis">
                                                        Amniocentesis
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 investigation-report">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Amniocentesis Date: &nbsp;
                                                        </span>
                                                    {{Form::text("investigation[amniocentesis_date]",'',['class'=>'form-control datetimepicker date f-date'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2 or-details d-none">
                                            <div class="investigation-report other-report-images"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('oe[is_plan]',0,['class'=>'is_plan'])}}
                            {{Form::hidden('oe[is_lft]',0,['class'=>'is_lft'])}}
                        <!-- Plan tab  -->
                            <div class="panel panel-primary plan-tab d-none">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#injection" href="#injection" aria-expanded="false"
                                                                aria-controls="past-history">9. Plan</a></h4>
                                </div>
                                <div id="injection" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[usg][type]",'usg_inside','',['id'=>'usg_inside','class'=>'usg-status','data-type'=>'usg-details'])}}
                                                    <label for="usg_inside">
                                                        USG Inside
                                                    </label>

                                                    {{Form::radio("oe[usg][type]",'usg_outside','',['id'=>'usg_outside','class'=>'usg-status','data-type'=>'usg-details'])}}
                                                    <label for="usg_outside">
                                                        USG Outside
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{Form::text("oe[usg][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[medically_type][type]",'medically','',['id'=>'medically_value','class'=>'medically-status'])}}
                                                    <label for="medically_value">
                                                        Medically
                                                    </label>

                                                    {{Form::radio("oe[medically_type][type]",'surgically','',['id'=>'surgically_value','class'=>'medically-status'])}}
                                                    <label for="surgically_value">
                                                        Surgically
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Injection history  -->
                            <div class="panel panel-primary injection-tab">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#injection" href="#injection" aria-expanded="false"
                                                                aria-controls="past-history">9. Injection</a></h4>
                                </div>
                                <div id="injection" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            TT1 : &nbsp;
                                                        </span>
                                                    {{Form::text("injection[tt1]",'',['class'=>'form-control datetimepicker date tt1-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            TT2 : &nbsp;
                                                        </span>
                                                    {{Form::text("injection[tt2]",'',['class'=>'form-control datetimepicker date tt2-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 betnasol d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Betnasol : &nbsp;
                                                        </span>
                                                    {{Form::text("injection[betnasol_1]",'',['class'=>'form-control b1 datetimepicker date betnasol-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 betnasol d-none">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Betnasol2 : &nbsp;
                                                        </span>
                                                    {{Form::text("injection[betnasol_2]",'',['class'=>'form-control b2 datetimepicker date betnasol-date'])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary gynec-plan-tab d-none">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#plan_gynec" href="#plan_gynec" aria-expanded="false"
                                                                aria-controls="past-history">9. Plan</a></h4>
                                </div>
                                <div id="plan_gynec" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("oe[plan_medically_type][type]",'medically','',['id'=>'plan_medically_value','class'=>'plan-medically-status'])}}
                                                    <label for="plan_medically_value">
                                                        Medically
                                                    </label>

                                                    {{Form::radio("oe[plan_medically_type][type]",'surgically','',['id'=>'plan_surgically_value','class'=>'plan-medically-status'])}}
                                                    <label for="plan_surgically_value">
                                                        Surgically
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- USG history  -->
                            <div class="panel panel-primary usg-tab">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#usg" href="#usg" aria-expanded="false"
                                                                aria-controls="past-history">10. USG </a> </h4>
                                </div>
                                <div id="usg" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
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
                                                    {{Form::text("usg[early_scan]",'',['class'=>'form-control datetimepicker date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group nt-data">
                                                        <span class="input-group-addon">
                                                            N.T Scan : &nbsp;
                                                        </span>
                                                    {{Form::text("usg[nt_scan]",'',['class'=>'form-control datetimepicker date nt-scan-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group anomalies-data">
                                                        <span class="input-group-addon">
                                                            Anomalies Miles : &nbsp;
                                                        </span>
                                                    {{Form::text("usg[anomalies_miles]",'',['class'=>'form-control datetimepicker date anomalies-scan-date'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Growth Scan : &nbsp;
                                                        </span>
                                                    {{Form::text("usg[growth_scan]",'',['class'=>'form-control datetimepicker date'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="usg-images"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Treatment history  -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                                                aria-controls="past-history">11. Treatment</a></h4>
                                </div>
                                <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body" id="parent">
                                        <div class="row">
                                            <div class="col-md-2 ipd-detail">
                                                <label for="termination">
                                                    Admission for
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?php
                                                    $terminationtype = ['Delivery'=>"Delivery",
                                                            'Obseravation'=>"Obseravation",
                                                            'Termination'=>"Termination",
                                                            'Operation'=>"Operation"];
                                                    ?>
                                                    {{Form::select("usg[termination_type]",$terminationtype,(@$usg->termination_type) && !empty($usg->termination_type) ? $terminationtype[$usg->termination_type]: null,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                {{Form::text("usg[termination_detail]",'',['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="row treatment-data" id="t_data_1">
                                            <div class="col-md-2 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    Select Medicine :
                                                </label>
                                            </div>
                                            <div class="col-md-9 complain-multi medicine-picker">
                                                {{-- {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control medicine','multiple'=>true,])}} --}}
                                                {{Form::select('treatment[medicinedata][]',$medicines, null,[
                                                    'class'=>'form-control select-padding-0 medicine',
                                                    'id' => 'treatment-medicine',
                                                    'placeholder'=>'Enter medicine name',
                                                ])}}
                                            </div>
                                        </div>
                                        <div class="page-loader-wrapper medicine-loader d-none">
                                            <div class="loader">
                                                <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                            </div>
                                        </div>
                                        <div class="medicine-data">

                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                Patient is referred
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">for : &nbsp;</span>
                                                    {{Form::text("usg[referfor]", '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">to : &nbsp;</span>
                                                    {{Form::text("usg[referto]", '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                            {{Form::hidden('is_gynec',0,['class'=>'is-gynec'])}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                Follow Up : &nbsp;
                                            </span>
                                        {{Form::text("oe[follow_up]", '',['class'=>'form-control datetimepicker followup next-date'])}}
                                        {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
                                    </div>
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
                            {{Form::hidden('next_date','',['class'=>'next-date-value'])}}
                            {{Form::hidden('next_time','',['class'=>'next-time-value'])}}
                            <div class="col-sm-12">
                                {{Form::submit('submit',['class'=>'btn btn-primary submit submit-data'])}}
                                <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                <a href="{{URL::to('anc')}}" class="btn btn-default">Cancel</a>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('modal')
    <div class="modal fade" id="next-appointment-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Next Appointment</h4>
                </div>
                {{Form::open(['class'=>'form-inline','id'=>'next-appointment'])}}
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Day
                            </div>
                            <div class="col-md-5">
                                {{Form::number('day','',['class'=>'form-control next-day','placeholder'=>'Day','min'=>1])}}
                                <span class="form-error-msg day w-100"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Date
                            </div>
                            <div class="col-md-5">
                                {{Form::date('date','',['class'=>'form-control next-date','placeholder'=>'Date','min'=>0])}}
                                <span class="form-error-msg date"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Time
                            </div>
                            <div class="col-md-5">
                                {{Form::select('next-time',$hospitalTime,'',['class'=>'form-control next-time','placeholder'=>'Time'])}}
                                <span class="form-error-msg date"></span>
                            </div>
                            <span class="form-error-msg time"></span>
                        </div>
                    </div>
                </div>
                {{Form::hidden('appointment-id','',['class'=>'appointment-id'])}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect next-appointment-form">SAVE CHANGES</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade preview-file-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header header-bottom-border">

                
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" id="myModalLabel">ANC History</h5>
                    </div>
                </div>
                    <button type="button" class="close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="anc-details-data">
                    </div>
                </div>

                <div class="modal-footer footer-top-border text-right d-inline-block">
                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                    
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
        var durationData = @json($durationOfData);
        var code = '';
        var isChangeCategory = 0;
        var ancQstring = '';
        var patientsId = $('#pID').val();
        var prevoiusAnc_id = '';
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

            $('.usg-images').imageUploader({
                imagesInputName: 'usg[images]',
            });
        });

        $('#treatment-medicine').select2();

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
        $('.duration-data').selectize({
            create: true,
            sortField: 'text'
        });
        $('.ho-past-data').selectize({
            create: true,
            sortField: 'text'
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
            $('.ho-past-personal-data .btn-group').addClass('d-none');
            $('.select2-search__field').css('width','280px');

            // add edd date and usg tab date
            var lmdDateValue = $('.lmd-date').val();
            if(lmdDateValue != '' && typeof lmdDateValue != 'undefined'){
                var value = new Date(lmdDateValue);
                eddDate(value,9,7,'edd-date');
                eddDate(new Date($('.lmd-date').val()),3,0,'nt-scan-date');
                eddDate(new Date($('.lmd-date').val()),5,0,'anomalies-scan-date');
            }
            $(document).on('click','.next-appointment',function(){
                var isError = errorMessage();
                if (isError == true) {
                    $('#next-appointment').trigger('reset');
                    $('#next-appointment-modal').modal('show');
                }
            });

            $(document).on('change','.change-category',function(){
                isChangeCategory = 1;
                $('.submit-data').click();
            });
            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var isError = errorMessage();
                if (isError == false) {
                    return false;
                }
                var formData = new FormData($("#anc-form")[0]);
                if(this.value==1){
                    formData.append('isprint', 1);
                }
                if(this.value==2){
                    formData.append('is_pdf', 1);
                    $(this).prop('disabled',true);
                }
                ancFormData(formData);
            });

            $(document).on('change','.followup',function(){
                var fDate = $(this).val();
                $('.f-date').val(fDate ? fDate : '');
            });

            $(document).on('change','select.refence-doctor',function(e){
                var refDoctorId = $(this).val();

                var token = "{{csrf_token()}}";
                $.ajax({
                    url: "{{URL::to('get-ref-doctor-mobile-number')}}",
                    dataType: 'json',
                    type: 'POST',
                    data:{refDoctorId:refDoctorId,_token:token}
                }).done(function(data) {
                    $('.ref-mobile-number').val('');
                    if(data.mobile_number != null){
                        $('.ref-mobile-number').val(data.mobile_number);
                    }
                }).fail(function() {

                });
            });

            $(document).on('click','.fcp-type-1',function(){
                checkGynec();
            });

            $(document).on('click','.blighted-ovum',function(){
                checkGynec();
            });
        });

        function ancFormData(data,next=null){
            $('.submit').prop('disabled', true);
            var url = "{{URL::to('anc')}}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'{{URL::to("anc")}}',
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'json',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == 'true'){
                    window.location.href = url;
                }else if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    $('#anc_id').val(data.id);
                    $('#next-appointment-modal').modal('hide');
                }else if(data.status == '2'){
                    $('.how-much-error').text('Please enter valid number');
                }else if(data.status == '3'){
                    $('#anc_history_id').val(data.id);
                    // if(isChangeCategory == 1){
                    //     window.location.href = data.url_value;
                    //     return true;
                    // }
                    window.location.href = url;
                }
                else{
                    location.reload();
                }
            });
        }

        function errorMessage() {
            var valid = 1;
            var weight=document.getElementById('weight').value;
            $('.ho-data-msg').text('');
            $('.gsac-no-data-followup').text('');
            $('.seen-by-error').text('');
            $('.fcp-error').text('');
            $('#oe').removeClass('show');
            $('.p-info').removeClass('show');
            if (weight == '') {
                valid = 0;
                $('.weight').text('The weight field is required.');
                $('.p-info').addClass('show');
            }
            if ($("input[name='oe[follow_up]']").val() == '') {
                $('.gsac-no-data-followup').text('The Follow up Date is required.');
                $('html, body').animate({
                    scrollTop: ($('.gsac-no-data-followup').offset().top - 150)
                }, 200);
                return false;
            }
            if(valid == 0){
                $('html, body').animate({
                    scrollTop: ($('.weight').offset().top - 150)
                }, 1000);
                return false;
            }
            // if(!$(".fcp_type:checked").val() && $('select.ut-sac').val() >= 3){
            //     $('.fcp-error').text('Please select one option in FCP.');
            //     $('html, body').animate({
            //         scrollTop: ($('.fcp_type').offset().top - 150)
            //     }, 1000);
            //     $('#oe').addClass('show');
            //     return false;
            // }
            if($('select.seen-by').val() == ''){
                $('.seen-by-error').text('Please select doctor');
                $('html, body').animate({
                    scrollTop: ($('.seen-by').offset().top - 150)
                }, 1000);
                return false;
            }
            return true;
        }
        
        function checkGynec(){
            $('.gynec-plan-tab').addClass('d-none');
            $('.injection-tab').removeClass('d-none');
            var isGynec = 0;
            var fcpValue = $('.fcp-type-1:checked').val();
           
            $(".blighted-ovum:checked").each(function() {
                var isBlighted = $(this).val();
                
                if($(this).val() == 'no')
                {
                    isGynec = 0;
                    return false;
                }
                if($('select.oe-no').val() == 1 && $(this).val() == 'yes')
                {
                    isGynec = 1;
                    return false;
                }
                if($(this).val() == 'yes')
                {
                    isGynec = 1;
                }
            })
            if(fcpValue == 'absent'){
                isGynec = 1;
            }

            if(isGynec == 1){
                $('.gynec-plan-tab').removeClass('d-none');
                $('.injection-tab').addClass('d-none');
            }
            $('.is-gynec').val(isGynec);
           
        }
        $(document).on('click','.print-btn',function(){
            date = $(this).data('date');
            qstring = 'patient_id='+patientsId+'&history_date='+date;
            getANCHistoryData(qstring);
        });
        $(document).on('change','select.anc_visit_id',function(e){
            e.preventDefault();
            prevoiusAnc_id = $(this).val();
            if(prevoiusAnc_id != '')
            {
                ancStatus = $(this).data('class');
                $('.preview-file-modal').modal('hide');
                $('.anc-details-data').html('');
                $('.preview-file-modal').modal('show');
                
                ancQstring = 'patient_id='+patientsId+'&anc_id='+prevoiusAnc_id;
                getANCHistoryData(ancQstring);
            }
            
        });
        function getANCHistoryData(ancQstring){
            $.ajax({
                url:'{{URL::to("get-anc-details")}}?'+ancQstring,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                if(data.anc_type == 1){
                    var ancPreview = $('.anc-details-data').html();
                    var buttonHtml = '';
                    var previewData = '';
                    for(i=0; i<data.data.length;i++)
                    {
                        if(typeof data.date[i] != 'undefined'){
                            var linkDate = moment(new Date(data.date[i])).format('YYYY-MM-DD HH:mm:ss');
                            var date = moment(new Date(data.date[i])).format('DD MMMM YYYY');
                        }
                        buttonHtml = ancPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn print-btn btn-sm btn-primary" data-date="'+linkDate+'">Print</a></div></div>';
                        ancPreview = buttonHtml + data.data[i];
                        $('.anc-details-data').html(ancPreview);
                        ancPreview = ancPreview + '<div class="row sepreator"></div>';
                    }
                }
                if(data.anc_type == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function(error){

            });
        }
        var medicinesValue = @json($medicines);
        var weekData = @json($weekData);
    </script>
    <script src="{{asset('public/js/anc.js')}}"></script>
@stop
