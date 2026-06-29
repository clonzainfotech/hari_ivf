@extends('layouts.main')
@section('parentPageTitle', 'Appointment')
@section('title', 'Add Appointment')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/date_picker.css')}}">
    <style>
        .ui-widget-content
        {
            background: white !important;
        }
        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #9ca683 !important;
            background: #9ca683 !important;
            color: white !important;
        }
        .ui-datepicker .ui-datepicker-calendar .ui-state-highlight a {
            background: #9ca683 !important;
        }
        a.ui-state-default:hover, a.ui-datepicker-prev.ui-corner-all:hover ,a.ui-datepicker-next.ui-corner-all:hover {
            background: #9ca683 !important;
        }
        
    </style>

@stop
@section('content')
<div class="row appointment">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>Add Appointment</strong>
                </h2>
                <ul class="header-dropdown">
                    <li>
                        <a href="{{URL::to('appointment')}}">
                            <button class="btn btn-primary">
                                Back
                            </button>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="col-md-12 col-lg-12">
                    <div class="panel-group"
                        id="accordion_1"
                        role="tablist"
                        aria-multiselectable="true">
                        @php
                            $referenceDoctorError = $errors->first('reference_doctor');
                            $hospitalDoctorError = $errors->first('hospital_doctor');
                            $otherMobileNumberError = $errors->first('other_mobile_number');
                        @endphp
                        {{Form::open(['url'=>'appointment','method'=>'post','class'=>'form appointment-form','files'=>'true'])}}

                            <!-- notification -->
                            @if(Session::has('msg'))
                            <div class="alert alert-warning">
                                {{Session::get('msg')}}
                                <button type="button"
                                    class="close"
                                    data-dismiss="alert"
                                    aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                            @endif
                            {{ Form::hidden('patient_id', !empty(Request::segment(3)) ? Request::segment(3) : null, [
                                'id' => 'patient_id'    
                            ]) }}
                        <!-- patients basic information -->
                        <div class="panel panel-primary">
                            <div class="panel-heading"
                                role="tab"
                                id="headingThree_1">
                                <h4 class="panel-title"> <a class="collapsed"
                                        role="button"
                                        data-toggle="collapse"
                                        data-parent="#accordion_1"
                                        href="#patients"
                                        aria-expanded="true"
                                        aria-controls="patients"> Patients Basic Information</a> </h4>
                            </div>
                            <div id="patients" class="panel-collapse collapse show patient-details" role="tabpanel" aria-labelledby="headingThree_1">
                                <div class="panel-body">

                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    Name: 
                                                </div>
                                                <div class="col-md-10 complain-multi input-group">

                                                        {{Form::select('name',$patients, null,[
                                                        'class'=>'form-control select-padding-0 patient-name',
                                                        'id' => 'patient-name',
                                                        'placeholder'=>'Enter name',
                                                    ])}}
                                                    
                                                    <span class="form-error-msg">
                                                        {{$errors->first('name')}}
                                                    </span>
                                                </div>
                                                {{Form::hidden('is_next',0,['class'=>'is-next'])}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                {{Form::select('code',$patientcode,'',['class'=>'form-control select-padding-0 patient-code','title'=>'Code','id'=>'patientcode','data-live-search'=>'true'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('code')}}
                                            </span>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Age : &nbsp;</span>
                                                {{Form::text('age',null,['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength'=>4])}}
                                            </div>
                                            <span class="form-error-msg age-error"></span>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Age : &nbsp;</span>
                                                {{Form::text('months',null,['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2])}}
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">Age : &nbsp;</span>
                                                {{Form::text('days',null,['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3])}}
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                {{Form::hidden('is_new_anc','0',['class'=>'form-control is_new_anc'])}}
                                                {{Form::select('category',$category,!empty($patientData->getAppointment['category_id']) ? $patientData->getAppointment['category_id'] : null,['class'=>'form-control select-padding-0 category_data','placeholder'=>'Select Category'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('category')}}
                                            </span>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Apt. Date : &nbsp;</span>
                                                {{-- <span class="input-group-addon">
                                                    <i class="zmdi zmdi-calendar"></i>
                                                </span> --}}
                                                {{Form::text('date',\Carbon\Carbon::now('Asia/Kolkata')->format('D d M Y'),[
                                                    'class'=>'form-control datetimepicker date next-date',
                                                    'placeholder'=>'Date',
                                                    'required'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('date')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Apt. Time : &nbsp;</span>
                                                {{Form::text('time', '', [
                                                    'class'=>'form-control timepicker time',
                                                    'placeholder'=>'Time'
                                                ])}}
                                                {{Form::hidden('next_time', '', ['class'=>'next-time col-md-9'])}}
                                                {{Form::hidden('is_create', 1)}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('time')}}
                                            </span>
                                        </div>
                                        {{Form::hidden('is_usg',$usgType)}}
                                        <div class="col-md-4 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Arrival Time : &nbsp;</span>
                                                {{Form::text('arrival_time', '', ['class'=>'form-control timepicker arrival_time','placeholder'=>'Arrival Time'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('arrival_time')}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Mobile : &nbsp;</span>
                                                {{Form::text('mobile_number',!empty($patientData->mobile_number) ? $patientData->mobile_number : null,[
                                                    'class'=>'form-control mobile_number',
                                                    'placeholder'=>'Mobile Number',
                                                    'maxlength' => 10,
                                                    'required',
                                                    'oninput' => 'appointmentMobileNumber(this.value)'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg mobileNumber">
                                                {{$errors->first('mobile_number')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Other Mobile : &nbsp;</span>
                                                {{Form::text('other_mobile_number',!empty($patientData->other_mobile_number) ? $patientData->other_mobile_number : null,[
                                                    'class'=>'form-control other_mobile_number',
                                                    'placeholder'=>'Other Mobile Number',
                                                    'maxlength' => 10,
                                                    'oninput' => 'otherMobileNumber(this.value)'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$otherMobileNumberError}}
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{Form::select('gender',['2'=>'Female','1'=>'Male'], !empty($patientData->gender) ? $patientData->gender : null,['class'=>'form-control select-padding-0','id'=>'gender','required'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('gender')}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-sm-1 unik-lbl-spn">
                                                <label>Remark :</label>        
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="form-group">
                                                    {{Form::textarea('remark',!empty($patientData->getAppointment['remark']) ? $patientData->getAppointment['remark'] : null,[
                                                        'class'=>'form-control no-resize remark',
                                                        'placeholder'=>'Remark',
                                                        'rows'=>'5'
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- doctor -->
                        <div class="panel panel-primary">
                            <div class="panel-heading"
                                role="tab"
                                id="headingThree_1">
                                <h4 class="panel-title"> <a class="collapsed"
                                        role="button"
                                        data-toggle="collapse"
                                        data-parent="#accordion_1"
                                        href="#doctor"
                                        aria-expanded="false"
                                        aria-controls="doctor"> Doctor</a> </h4>
                            </div>
                            <div id="doctor"
                                class="panel-collapse collapse show doctor-details"
                                role="tabpanel"
                                aria-labelledby="headingThree_1">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{Form::select('reference_doctor',$referenceDoctor,!empty($patientData->getReferenceDoctor['id']) ? $patientData->getReferenceDoctor['id'] : null,[
                                                    'class'=>'form-control select-padding-0 reference_doctor',
                                                    'placeholder'=>'Select Reference Doctor',
                                                    'data-live-search'=>'true',
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$referenceDoctorError}}
                                            </span>
                                        </div>
                                        <div class="col-md-6 col-sm-6 doctor-name d-none">
                                            <div class="form-group">
                                                {{Form::text('doctor_name', '', [
                                                    'class'=>'form-control doctor',
                                                    'placeholder'=>'Doctor Name'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('doctor_name')}}
                                            </span>
                                        </div>
                                        <div class=" col-md-6 col-sm-6 doctor-mobile-number d-none">
                                            <div class="form-group">
                                                {{Form::text('doctor_mobile_number','',[
                                                    'class'=>'form-control doctor',
                                                    'placeholder'=>'Doctor Mobile Number',
                                                    'oninput' => 'doctorMobileNumber(this.value)',
                                                    'maxlength' => 10
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('doctor_mobile_number')}}
                                            </span>
                                        </div>
                                        {{-- if self then enter reference pt name --}}
                                        <div class="col-md-6 col-sm-6 ref-pt-name d-none">
                                            <div class="form-group">
                                                {{Form::text('ref_pt_name', '', [
                                                    'class'=>'form-control ref_pt',
                                                    'placeholder'=>'Reference Patient Name'
                                                ])}}
                                            </div>
                                            {{-- <span class="form-error-msg">
                                                {{$errors->first('ref_pt_name')}}
                                            </span> --}}
                                        </div>
                                        <div class=" col-md-6 col-sm-6 ref-pt-mobile d-none">
                                            <div class="form-group">
                                                {{Form::text('ref_pt_mobile','',[
                                                    'class'=>'form-control doctor',
                                                    'placeholder'=>'Reference Patient Mobile Number',
                                                    'oninput' => 'doctorMobileNumber(this.value)',
                                                    'maxlength' => 10
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('ref_pt_mobile')}}
                                            </span>
                                        </div>

                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{Form::select('hospital_doctor',$hospitalDoctor,!empty($patientData->getHospitalDoctor['id']) ? $patientData->getHospitalDoctor['id'] : null,['class'=>'form-control select-padding-0 hospital_doctor','placeholder'=>'Select Hospital Doctor','data-live-search'=>'true'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$hospitalDoctorError }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{Form::select('pro_reference_doctor',$proReferenceDoctor,'',['class'=>'form-control select-padding-0 pro-ref-hospital-doctor','placeholder'=>'Select Pro Reference Doctor','data-live-search'=>'true'])}}
                                            </div>
                                        </div>
                                        <div class=" col-md-6 col-sm-6 ref-pro-doctor-data d-none">
                                            <div class="form-group">
                                                {{Form::text('pro_reference_doctor_name','',[
                                                    'class'=>'form-control',
                                                    'placeholder'=>'Pro Doctor Name',
                                                ])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{Form::select('seen_by',$hospitalDoctor,!empty($patientData->getHospitalDoctor['id']) ? $patientData->getHospitalDoctor['id'] : null,['class'=>'form-control select-padding-0 seenBy_doctor','placeholder'=>'Select Seen By Doctor','data-live-search'=>'true'])}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class=" col-md-6 col-sm-6 ref-pro-doctor-data d-none">
                                            <div class="form-group">
                                                {{Form::text('pro_reference_doctor_mobile_number','',[
                                                    'class'=>'form-control ref-pro-doctor checkvalue',
                                                    'placeholder'=>'Pro Doctor Mobile Number',
                                                    'oninput' => 'otherProMobileNumber(this.value)',
                                                    'maxlength' => 10
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('pro_reference_doctor_mobile_number')}}
                                            </span>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{Form::textarea('other_patient_reference',!empty($patientData->other_patient_reference) ? $patientData->other_patient_reference : null,[
                                                'class'=>'form-control no-resize remark',
                                                'placeholder'=>'Other atient Reference',
                                                'rows'=>'2'
                                            ])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- location and communication -->
                        <div class="panel panel-primary">
                            <div class="panel-heading"
                                role="tab"
                                id="headingThree_1">
                                <h4 class="panel-title"> <a class="collapsed"
                                        role="button"
                                        data-toggle="collapse"
                                        data-parent="#accordion_1"
                                        href="#collapseThree_1"
                                        aria-expanded="false"
                                        aria-controls="collapseThree_1">Location</a> </h4>
                            </div>
                            <div id="collapseThree_1"
                                class="panel-collapse collapse show location-details"
                                role="tabpanel"
                                aria-labelledby="headingThree_1">
                                <div class="panel-body">
                                    <div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Residence : &nbsp;</span>
                                                    {{Form::text('residence',!empty($patientData->residence) ? $patientData->residence : null,[
                                                        'class'=>'form-control residence',
                                                        'placeholder'=>'Residence',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('residence')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Main Area : &nbsp;</span>
                                                    {{Form::text('main_area',!empty($patientData->main_area) ? $patientData->main_area : null,[
                                                        'class'=>'form-control main_area',
                                                        'placeholder'=>'Main Area',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('main_area')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{Form::select('city_1',$city,!empty($patientData->city) ? $patientData->city : null,['class'=>'form-control select-padding-0 city-name','placeholder'=>'City','data-live-search'=>'true'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('city_1')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 city-text d-none">
                                            <div class="form-group">
                                                {{Form::text('city_2','',['class'=>'form-control city city-2','placeholder'=>'City'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('city_2')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{Form::select('state',$state,!empty($patientData->getState['id']) ? $patientData->getState['id'] : 7, ['class'=>'form-control select-padding-0 state','placeholder'=>'State','data-live-search'=>'true'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('state')}}
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- other -->
                        <div class="panel panel-primary">
                            <div class="panel-heading"
                                role="tab"
                                id="headingThree_1">
                                <h4 class="panel-title"> <a class="collapsed"
                                        role="button"
                                        data-toggle="collapse"
                                        data-parent="#accordion_1"
                                        href="#other"
                                        aria-expanded="false"
                                        aria-controls="other"> Other</a> </h4>
                            </div>
                            <div id="other"
                                class=""
                                role="tabpanel"
                                aria-labelledby="headingThree_1">
                                <div class="panel-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="row imp-note-list d-none">
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-1 unik-lbl-spn">
                                                    <label>Important Note :</label>        
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        {{Form::textarea('important_note',null,[
                                                            'class'=>'form-control no-resize important_note',
                                                            'placeholder'=>'Important Note',
                                                            'rows'=>'2'
                                                        ])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Weight : &nbsp;</span>
                                                {{Form::text('weight',!empty($patientData->weight) ? $patientData->weight : null,[
                                                    'class'=>'form-control weight',
                                                    'placeholder'=>'Please enter weight in KG',
                                                    'maxlength' => 3,
                                                    'oninput' => 'checkWeight(this.value)'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('weight')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Occupation : &nbsp;</span>
                                                {{Form::text('occupation',!empty($patientData->occupation) ? $patientData->occupation : null,[
                                                    'class'=>'form-control occupation',
                                                    'placeholder'=>'Occupation'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('occupation')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{Form::select('pregnant',[1=>'Yes',2=>'No'],!empty($patientData->is_pregnant) ? $patientData->is_pregnant : null,['class'=>'form-control pregnant select-padding-0','placeholder'=>'Pregnant'])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('pregnant')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn">Height : &nbsp;</span>
                                                {{Form::text('height',!empty($patientData->height) ? $patientData->height : null,[
                                                    'class'=>'form-control height',
                                                    'placeholder'=>'Height'
                                                ])}}
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('height')}}
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon unik-lbl-spn ">Birth Date : &nbsp;</span>
                                                {{Form::text('dob','',[
                                                    'id'=>'birthdate',
                                                    'class'=>'dob border-color border-1 form-control',
                                                    'autocomplete'=>'off'
                                                ])}}
                                                {{-- <input name="dob" id="birthdate" class="form-control" placeholder='BirthDate'> --}}
                                               
                                            </div>
                                            <span class="form-error-msg">
                                                {{$errors->first('dob')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            {{Form::submit('Save',['class'=>'btn btn-primary appointment-save'])}}
                            <a href="{{URL::to('appointment')}}"
                                class="btn btn-default">Cancel</a>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <input id="birthdate"> --}}
@stop

@section('page-script')
<script type="text/javascript" src="{{url('assets/js/jquery.date-dropdowns.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script>
    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js"></script>
<script type="text/javascript">
    $(".patient-name").select2({
        tags: true
    });
    var code = '';
    var patinet ='';
       
    $(document).ready(function(){
        $(function () {
            $("#birthdate").datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
        });
        @if(Session::has('already'))
            var already = "{{Session::get('already')}}"
            showConfirmMessage(already);
        @endif
        $(".appointment-form").submit(function() {
            $(".appointment-save").attr("disabled", true); 
        });

        proReferenceDoctor($('select.pro-ref-hospital-doctor').val());

        $('.appointment-save').click(function(){
            $('.patient-details').removeClass('show');
            $('.doctor-details').removeClass('show');
            $('.location-details').removeClass('show');
            // var age = $('.age').val();
            // var category = $('select.category_data').val();
            // var refDoc = $('select.reference_doctor').val();
            // var hospitalDoctor = $('select.hospital_doctor').val();
            var mobileNumber = $('.mobile_number').val();
            // var residence = $('.residence').val();
            // var mainArea = $('.main_area').val();
            // var cityName = $('select.city-name').val();
            // var state = $('select.state').val();
            // var years = $('.years').val();
            // var months = $('.months').val();
            // var days = $('.days').val();
            // $('.age-error').text('');
            // if(years == '' && months == '' && days == ''){
            //     $('.age-error').text('Please enter age');
            //     $('.patient-details').addClass('show');
            //     return false;
            // }
            // if(category == '' || mobileNumber == ''){
            if(mobileNumber == ''){
                $('.patient-details').addClass('show');
            }
            // if(refDoc == '' || hospitalDoctor == ''){
            //     $('.doctor-details').addClass('show');
            // }
            // if(residence == '' || mainArea == '' || cityName == '' || state == ''){
            //     $('.location-details').addClass('show');
            // }
        });
        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            // minDate:new Date(),
            clearButton: true,
            time: false,
            weekStart: 1
        });
        $('.timepicker').bootstrapMaterialDatePicker({
            date: false,
            shortTime: true,
            format: 'hh:mm a',
            switchOnClick: true,
            clearButton: true,
        });
        // reference doctor
        $('select[name="reference_doctor"]').on('change', function() {
            referenceDoctorField($(this).val());
        });
        if($('#patient_id').val() != '') {
            $('.code').attr('readonly', true);
        }
        referenceDoctorField($('select[name="reference_doctor"]').val());
        $('select.patient-code').on('keyup blur change', function () {
            var code = $("#patientcode option:selected").text();
            if(code != null){
                getPatientsDetails(code);
            }
        });
        $('select.patient-name').on('keyup blur change', function () {
            var patient = $(this).val();
            var skillsSelect = $("#patient-name option:selected").text();
            if(patient != null){
            if(!isNaN(patient)){
                if(patient != null){
                    getPatientsDetails(patient);
                    getImpNoteList(patient);
                    $('.patient-code').removeAttr('disabled', false);
                }
            }else{ 
                $('.patient-code').attr('disabled', 'disabled');
                    $('.reference_doctor').val("");
                    $('.reference_doctor').selectpicker('refresh');
                    $('.hospital_doctor').val("");
                    $('.hospital_doctor').selectpicker('refresh');
                    $('.other_mobile_number').val("");
                    $('.mobile_number').val("");
                    $('.age').val("");
                    $('#gender').val("");
                    $('.weight').val("");
                    $('.state').val("");
                    $('.category_data').val("");
                    $('.patient-code').val("");
                    $('.patient-code').selectpicker('refresh');
                    $('.state').val("");
                    $('.state').selectpicker('refresh');
                    $('.city-name').val("");
                    $('.residence').val("");
                    $('.location').val("");
                    $('.pregnant').val("");
                    $('.main_area').val("");
                    $('.occupation').val("");
                    $('.category_data').selectpicker('refresh');
                    $("#gender").val('').selectpicker("refresh");
                    $(".city-name").val('default').selectpicker("refresh");
                    $(".residence").val('').selectpicker("refresh");
                }
            }
        });
        $('.mobile_number').blur(function(e) {
            var mobilenumber = $(this).val();
                var patient = $('select.patient-name').val();
                if((isNaN(patient) == false)){
                    if(mobilenumber != ''){
                        getPatientsDetails(mobilenumber);
                    }
                }else{
                    $('.mobileNumber').text('');
                    var csrf = "{{csrf_token()}}";
                    $.ajax({
                        url:'{{URL::to("appointment")}}',
                        type:'POST',
                        dataType:'json',
                        data:{_token:csrf,mobile_number:mobilenumber},
                        }).done(function(data){
                        if(data.status == 1){
                            $('.mobileNumber').text('Mobile number already exist.');
                        }
                    });
                }
            
        });
        $(document).on('change', 'select.city-name', function() {
            var value = $(this).val();

            if (value == 'Other') {
                $('.city-2').prop('required', 'required');
                $('.city-text').removeClass('d-none');
            } else {
                $('.city-2').prop('required', false);
                $('.city-text').addClass('d-none');
            }
        });
        // next appointment
        $(document).on('change','.next-date',function(e){
            var dateValue = $(this).val();
            // var selectedAppointmentId = $('.selected-tr').data('id');
            var day = $('.next-day').val();
            var time = '';
            if(dateValue){
                var time = $(this).find("option:selected").text();
            }
            getNextAppointmentDate(null,day,dateValue,time);
        });
        
        $(document).on('keyup','.valid-age',function(){
            var value = $(this).val();
            $(this).val(validMobileNumber(value));
        });

        $(document).on('change','select.pro-ref-hospital-doctor',function(){
            var value = $(this).val();
            proReferenceDoctor(value);
        });
    });

    // next appointment function
    function getNextAppointmentDate(appointmentId,day,date,time){
        var token = "{{csrf_token()}}";
        $.ajax({
            url: "{{URL::to('next-appointment')}}",
            dataType: 'json',
            type: 'post',
            data:{appointmentId:appointmentId,day:day,_token:token,date:date,time:time}
        }).done(function(data) {
            if(data.status == null){
                var date = moment(data.date).format('dddd DD MMMM YYYY');
                $('.next-date').val(date);
                $('.next-day').val(data.diff);
                $('.next-time').val(data.time);
            }
        }).fail(function(error) {
            $('.form-error-msg').empty();
            if(error.responseJSON != null){
                var formError = error.responseJSON.errors;
                $.each(formError,function(key,value){
                    $('.'+key).text(value);
                });
            }
        });
    }

    function referenceDoctorField(value){
        if (value == 'other') {
            $('input[type="text"][name="doctor_name"]').prop('required', 'required');
            $('input[type="text"][name="doctor_mobile_number"]').prop('required', 'required');
            $('.doctor-name').removeClass('d-none');
            $('.doctor-mobile-number').removeClass('d-none');
        }
        else if(value == '1')//self
        {
            // $('input[type="text"][name="ref_pt_name"]').prop('required', 'required');
            // $('input[type="text"][name="ref_pt_mobile"]').prop('required', 'required');
            $('.ref-pt-name').removeClass('d-none');
            $('.ref-pt-mobile').removeClass('d-none');
        }
        else {
            $('input[type="text"][name="doctor_name"]').prop('required', false);
            $('input[type="text"][name="doctor_mobile_number"]').prop('required', false);
            $('.doctor-name').addClass('d-none');
            $('.doctor-mobile-number').addClass('d-none');

            // $('input[type="text"][name="ref_pt_name"]').prop('required', false);
            // $('input[type="text"][name="ref_pt_mobile"]').prop('required', false);
            $('.ref-pt-name').addClass('d-none');
            $('.ref-pt-mobile').addClass('d-none');
        }
    }

    function getPatientsDetails(patient,code,mobilenumber) {
        if(patient != null){
            var url = patient + "/edit";
        }else if(code != null){
            var url = code + "/edit";
        }else if(mobilenumber != null){
            var url = mobilenumber + "/edit";            
        }
        $.ajax({
            url: 'appointment'+'/'+url,
            type: "GET",
        }).done(function(data) {
            $('.error-code').text('');
            if (data['patients'] != null) {
                $('.age').val(data['patients']['age']);
                $('.patient-name').val(data['patients']['id']).prop('selected',true);
                $('.select2-selection__rendered').text(data['patients']['name']);
                $(".patient-code").val(data['patients']['code']);
                $('.patient-code').selectpicker('refresh');
                
                $('.reference_doctor').val(data['patients']['reference_doctor_id']);
                //if self then display refernce pt name and mobile number
                if(data['patients']['reference_doctor_id'] == 1)
                {
                    $('.ref-pt-name').removeClass('d-none');
                    $('.ref-pt-mobile').removeClass('d-none');
                    $('input[type="text"][name="ref_pt_name"]').val(data['patients']['reference_pt_name']);
                    $('input[type="text"][name="ref_pt_mobile"]').val(data['patients']['reference_pt_mobile']);
                }
                $('.reference_doctor').selectpicker('refresh');
                $('.pro-ref-hospital-doctor').val(data['patients']['reference_doctor_pro_id']);
                $('.pro-ref-hospital-doctor').selectpicker('refresh');
                $('.hospital_doctor').val(data['patients']['hospital_doctor_id']);
                $('.hospital_doctor').selectpicker('refresh');
                $('.seenBy_doctor').val(data['patients']['hospital_doctor_id']);
                $('.seenBy_doctor').selectpicker('refresh');
                $('.other_mobile_number').val(data['patients']['other_mobile_number']);
                $('.mobile_number').val(data['patients']['mobile_number']);
                $('#gender').val(data['patients']['gender']);
                $('#gender').selectpicker('refresh');
                $('.weight').val(data['patients']['weight']);
                $('.state').val(data['patients']['state']);
                $('.state').selectpicker('refresh');
                $('.category_data').val(data['appointmentCategoryId']);
                $('.category_data').selectpicker('refresh');
                $('.city-name').val(data['patients']['city']);
                $('.city-name').selectpicker('refresh');
                $('.residence').val(data['patients']['residence']);
                $('.location').val(data['patients']['location']);
                $('.pregnant').val(data['patients']['is_pregnant']);
                $('.pregnant').selectpicker('refresh');
                $('.main_area').val(data['patients']['main_area']);
                $('.occupation').val(data['patients']['occupation']);
                if(data['patients']['dob'] != null)
                {
                    $('.dob').val(moment(data['patients']['dob']).format('DD-MM-YYYY'));
                }

            } else {
                $( "#patient-name" ).val('').trigger('change');
                $("select").val('default').selectpicker("refresh");
                resetForm();
            }
        }).fail(function(error) {
        });
    }
    $('select[name="category"]').on('change', function(){
        $('.is_new_anc').val(0);
        if($(this).val() == 5)
        {
            ancConfirmMessage();
        }
    })
    function ancConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "Is this new Pregnency?",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function () {
                $('.is_new_anc').val(1);
            });
        }
    function proReferenceDoctor(value){
        $('.ref-pro-doctor-data').addClass('d-none');
        if(value == 'other'){
            $('.ref-pro-doctor-data').removeClass('d-none');
        }
    }
    function resetForm() {
        var code = $('.code').val();
        $('.appointment-form').trigger('reset');
        // $('.code').val(code);
    }
    function appointmentMobileNumber(value) {
        // var mobileNumber = value;
        $('.mobile_number').val(validMobileNumber(value));
    }
    function otherMobileNumber(value) {
        // var mobileNumber = value;
        $('.other_mobile_number').val(validMobileNumber(value));
    }

    function otherProMobileNumber(value) {
        // var mobileNumber = value;
        $('.checkvalue').val(validMobileNumber(value));
    }
    function doctorMobileNumber(value) {
        // var mobileNumber = value;
        $('input[type="text"][name="doctor_mobile_number"]').val(validMobileNumber(value));
    }
    function validMobileNumber(value) {
        if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
            return '';
        } else {
            return value;
        }
    }
    // function checkAge(value) {
    //     $('.age').val(validMobileNumber(value));
    // }
    function checkWeight(value) {
        $('.weight').val(validMobileNumber(value));
    }

    function showConfirmMessage(already) {
        $('.is-next').val(0);
        // var dateTime = "{{Session::get('datetime')}}";
        var date = "{{Session::get('date')}}";
        var time = "{{Session::get('time')}}";
        var holiday = "{{Session::get('holidayName')}}";
        var name = "{{Session::get('p_name')}}";
        var patientId = "{{Session::get('patientId')}}";
        var dateTime = date+' '+time;
        var appointmentId = "{{Session::get('appointmentId')}}";
        var msg = 'Appointment already booked!';
        var text = "Appointment has been already booked date on "+dateTime+" Are you sure you want to update this appointment??";
        if(already == 2){
            if(patientId == ''){
                $('.patient-code').attr('disabled',true);
                $("select.patient-name").append('<option value="'+name+'" selected="">'+name+'</option>');
                $(".patient-name").selectpicker("refresh");
            }
            msg = 'Sunday Appointment';
            text = "This is Appointment of Sunday, Are you sure you want to appointment??";
        }
        if(already == 3){
            msg = 'Holiday Appointment';
            text = "This Appointment of Holiday "+holiday+", Are you sure you want to appointment??";
        }
        if(already == 4)
        {
            patientId = '';
            var d_name = "{{Session::get('doctor')}}";
            var apt_date = "{{Session::get('apt_date')}}";
            msg = d_name;
            text = d_name+" is not available on "+apt_date+" , Are you sure you want to appointment??";
        }
        swal({
            title: msg,
            text: text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#00cfd1",
            confirmButtonText: "Yes!",
            closeOnConfirm: false,
            cancelButtonClass: 'btn btn-danger',
        }, function () {
            if(patientId == ''){
                $('.is-next').val(1);
                $(".appointment-form").submit();
                $('.showSweetAlert').hide();
            }else{
                $('.showSweetAlert').hide();
                updateAppointmentDate(appointmentId,date,time);
                window.location.href = "{{URL::to('appointment')}}";
            }
        });
    }

    function updateAppointmentDate(aId,date,time){
        var patientId = "{{Session::get('patientId')}}";
        var date = $('.next-date').val();
        
        $.ajax({
            url: "{{URL::to('update-appointment-date-time')}}",
            type: "GET",
            data:{appointment_id:aId,date:date,time:time,patient_id:patientId}
        }).done(function(data) {
            // window.location.href = "{{URL::to('appointment')}}";
        }).fail(function(error){
            
        });
    }
    
</script>
@stop
