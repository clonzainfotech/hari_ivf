@extends('layouts.main')
@section('parentPageTitle', 'Indoor')
@section('title', 'Discharge Card')

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('css/image-uploader.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="row clearfix discharge-add indoor">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Discharge card</strong>
                    </h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::previous()}}">
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
                        {{Form::open([
                           'class'=>'form',
                            'id'=>'discharge-form',
                            'files'=>'true'
                        ])}}

                        {{ Form::hidden('booked_id', !empty($bookedPatientData->id) ? encrypt($bookedPatientData->id) : null, [
                            'id' => 'booked_id'
                        ]) }}

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
                            {{ Form::hidden('patient_id', !empty($bookedPatientData->getPatientsDetails) ? $bookedPatientData->getPatientsDetails['id'] : null, [
                                'id' => 'patient_id'
                            ]) }}
                        <!-- patients basic information -->
                            <div class="panel panel-primary">
                                <div class="panel-heading"
                                     role="tab"
                                     id="headingThree_1">
                                    <h4 class="panel-title">
                                        <a class="collapsed"
                                            role="button"
                                            data-toggle="collapse"
                                            data-parent="#accordion_1"
                                            href="#patients"
                                            aria-expanded="true"
                                            aria-controls="patients">Patients Information
                                        </a>
                                    </h4>
                                </div>
                                <div id="patients"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">

                                        <div class="row clearfix">

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Code:</span>
                                                    {{Form::text('code',!empty($bookedPatientData->getPatientsDetails->code) ? $bookedPatientData->getPatientsDetails->code : null, [
                                                        'class'=>'form-control code',
                                                        'placeholder'=>'Reg. No',
                                                        'required',
                                                        'readonly'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('code')}}
                                            </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Ward:</span>
                                                    {{Form::text('ward', !empty($bookedPatientData->getRoom['room_no']) ? $bookedPatientData->getRoom['room_no'] : null, [
                                                        'class'=>'form-control ward',
                                                        'placeholder'=>'Ward',
                                                        'required',
                                                        'readonly'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('ward')}}
                                            </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Room Type: &nbsp;</span>
                                                    {{Form::text('room_type',!empty($bookedPatientData->getRoomType['name']) ? $bookedPatientData->getRoomType['name'] : null,['class'=>'form-control','disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Date of Admission : &nbsp;</span>
                                                    {{Form::text('admitdate',cdate($bookedPatientData->doa_date)->format('D d M Y'),[
                                                        'class'=>'form-control datetimepicker admitdate',
                                                        'placeholder'=>'Date of Admission',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('admitdate')}}
                                                </span>

                                            </div>
                                        </div>
                                        <div class="row clearfix">

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Name:</span>
                                                    {{Form::text('firstname',!empty($bookedPatientData->getPatientsDetails['name']) ? $bookedPatientData->getPatientsDetails['name'] : null, [
                                                        'class'=>'form-control firstname',
                                                        'placeholder'=>'Name',
                                                        'required'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('firstname')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Date of Discharge  :</span>
                                                {{ Form::text('dischargedate', cdate($bookedPatientData->dod_date)->format('D d M Y'), [
                                                       'class'=>'form-control datetimepicker dischargedate',
                                                       'placeholder'=>'Date',
                                                   ])}}
                                                </div>
                                                <span class="form-error-msg dischargedate-error"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Admission :</span>
                                                    {{ Form::text('admissiontime',$bookedPatientData->admit_time ? cdate($bookedPatientData->admit_time)->format('h:i a') : null, [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Discharge :</span>
                                                    {{ Form::text('dischargetime',$bookedPatientData->discharge_time ? cdate($bookedPatientData->discharge_time)->format('h:i a') : null, [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Gender:</span>
                                                    {{Form::text('Gender',$bookedPatientData->getPatientsDetails['gender'] == 2 ? 'Female' : 'Male', ['class'=>'form-control Gender','placeholder'=>'Gender','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('Gender')}}
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('age',!empty($bookedPatientData->getPatientsDetails['age']) ? $bookedPatientData->getPatientsDetails['age'] : null,['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength' => 4,'disabled'])}}
                                                </div>
                                                <span class="form-error-msg age-error"></span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('months',!empty($bookedPatientData->getPatientsDetails['months']) ? $bookedPatientData->getPatientsDetails['months'] : null,['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('days',!empty($bookedPatientData->getPatientsDetails['days']) ? $bookedPatientData->getPatientsDetails['days'] : null,['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Weight:</span>
                                                    {{Form::text('wt',!empty($bookedPatientData->getPatientsDetails['weight']) ? $bookedPatientData->getPatientsDetails['weight'] : null, ['class'=>'form-control wt','placeholder'=>'Weight','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('wt')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Doctor Incharge:</span>
                                                    {{Form::text('drincharge',!empty($bookedPatientData->getPatientsDetails->getHospitalDoctor['name']) ? $bookedPatientData->getPatientsDetails->getHospitalDoctor['name'] : null, ['class'=>'form-control drincharge','placeholder'=>'Doctor Incharge'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('drincharge')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Date of Surgery :</span>
                                                    {{ Form::text('surgerydate', '', [
                                                    'class'=>'form-control datetimepicker surgerydate',
                                                    'placeholder'=>'Date',
                                                ])}}
                                                </div>
                                                <span class="form-error-msg surgerydate-error"></span>
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
                                                                aria-controls="doctor">Other</a> </h4>
                                </div>
                                <div id="doctor"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix">
                                            <div class="col-md-6 complain-mulit">
                                                {{Form::select('diagnosis[diagnosis][]',$diagnosis,'',[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Diagnosis',
                                                    'multiple'=>true,'
                                                    required'
                                                    ])}}
                                                <span class="form-error-msg">
                                                {{$errors->first('diagnosis')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Summary :</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('summary','',[
                                                            'class'=>'form-control summary',
                                                            'placeholder'=>'Summary',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('summary')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-md-6 complain-mulit">
                                                {{Form::select('treatments[treatments][]',$treatments,'',[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Treatment Given',
                                                    'multiple'=>true,'
                                                    required'
                                                    ])}}
                                                <span class="form-error-msg">
                                                {{$errors->first('treatment')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Surgical Note :</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('surgicalnote','',[
                                                            'class'=>'form-control surgicalnote',
                                                            'placeholder'=>'Surgical Note',
                                                            'rows' =>2,
                                                            ])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            {{-- <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">HPE :</span>
                                                    {{Form::text('hpe',null, [
                                                        'class'=>'form-control hpe',
                                                        'placeholder'=>'HPE',
                                                        'required'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('hpe')}}
                                                </span>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-1 unik-lbl-spn">
                                                        <label>HPE:</label>        
                                                    </div>
                                                    <div class="col-sm-11">
                                                        <div class="form-group">
                                                            {{Form::textarea('hpe','',[
                                                            'class'=>'form-control hpe',
                                                            'placeholder'=>'HPE',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('hpe')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Report :</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('report','',[
                                                            'class'=>'form-control report',
                                                            'placeholder'=>'Report',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('report')}}
                                                    </span>
                                                </div>
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
                                                    'placeholder'=>'Rx(Treatment)',
                                                    'data-live-search'=>"true",
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
                                        {{Form::hidden('old_medicine_data',null,['class'=>'old-medicine-data'])}}
                                        {{-- <span class="form-error-msg">
                                            {{$errors->first('rxtreatment')}}
                                            </span> --}}
                                        <div class="row clearfix">
                                            {{-- <div class="col-md-6 complain-mulit">
                                                <div class="form-group">
                                                </div>
                                                {{Form::select('treatment[medicinedata][]',$medicines,'',[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Rx(Treatment)',
                                                    'multiple'=>true,'
                                                    required'
                                                    ])}}
                                                
                                            </div> --}}
                                            <div class="col-md-6 complain-mulit">
                                                <div class="form-group">
                                                </div>
                                                {{Form::select('complaints[]',$complaint,'',[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Complaint',
                                                    'multiple'=>true,'
                                                    required'
                                                ])}}
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Complaint :</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('complaint','',[
                                                            'class'=>'form-control diagnosis',
                                                            'placeholder'=>'Admitted for cheif Complaint',
                                                            'rows' =>3,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('complaint')}}
                                                    </span>
                                                </div>
                                            </div> --}}
                                        </div>

                                        <div class="row clearfix mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-3 unik-lbl-spn">
                                                        <label>Admission Vitals :</label>        
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="form-group">
                                                            {{Form::textarea('vitals','BP 110/60 mmhg, spo2 94%, p/a- 22Weeks fhs - present,',[
                                                            'class'=>'form-control vitals',
                                                            'placeholder'=>'Admission Vitals',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('vitals')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Examination:</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('examination','CVS/RS NAD',[
                                                            'class'=>'form-control examination',
                                                            'placeholder'=>'Systemic Examination',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('examination')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-3 unik-lbl-spn">
                                                        <label>Clinical Summary :</label>        
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="form-group">
                                                            {{Form::textarea('clinicalsummary','Same as above',[
                                                            'class'=>'form-control clinicalsummary',
                                                            'placeholder'=>'Clinical Summary',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('clinicalsummary')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-3 unik-lbl-spn">
                                                        <label>Vital on Discharge :</label>        
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="form-group">
                                                            {{Form::textarea('dischargevital','BP 110/60 mmhg, spo2 94%, p/a- 22Weeks fhs - present,',[
                                                            'class'=>'form-control dischargevital',
                                                            'placeholder'=>'Vital on Discharge',
                                                            'rows' =>2,
                                                            ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('dischargevital')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-3 unik-lbl-spn">
                                                        <label>Condition on Discharge :</label>        
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="form-group">
                                                            {{Form::textarea('condition','Patient is vitally stable at candor hospital on',[
                                                            'class'=>'form-control condition',
                                                            'placeholder'=>'Condition on Discharge',
                                                            'rows' =>2,
                                                        ])}}
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('condition')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Follow Up :</span>
                                                    {{Form::text('followup',null, [
                                                        'class'=>'form-control followup',
                                                        'placeholder'=>'Follow Up',
                                                        'required'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('followup')}}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-3">

                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Follow up date : &nbsp;</span>
                                                    {{Form::text('followdate',\Carbon\Carbon::now('Asia/Kolkata')->format('D d M Y'),[
                                                        'class'=>'form-control datetimepicker followdate',
                                                        'placeholder'=>'Follow up date',
                                                        'required'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('followdate')}}
                                                </span>
                                            </div>
                                        </div>
                                        @php
                                            $is_birthCertificate = in_array($bookedPatientData->getprocedure['id'],[1,2,11]) ? '' : 'd-none';
                                        @endphp
                                            <div class="{{'row clearfix '.$is_birthCertificate}}">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-sm-3 unik-lbl-spn">
                                                            <label>Birth Certificate :</label>        
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <div class="birth-images">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-sm-3 unik-lbl-spn">
                                                            <label>Birth Remark :</label>        
                                                        </div>
                                                        <div class="col-sm-9">
                                                            {{Form::textarea("birth_certificate[remark]",'',['class'=>'form-control',"rows"=>2,"placeholder"=>"Birth Remark"])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                @if($bookedPatientData->is_direct_discharge == 0)
                                    {{Form::submit('Save',['class'=>'submit btn btn-primary discharge-save'])}}
                                @endif
                                <button type="submit" data-id="" class="btn btn-primary submit discharge-save" value="1">Save & Preview</button>
                                <a href="{{URL::previous()}}" class="btn  btn-default">Cancel</a>
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
    <script>
        $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
        $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
    </script>
    <script src="{{URL::to('js/image-uploader.js')}}"></script>
    <script src="{{URL::to('js/anc.js')}}"></script>
    <script>
        var bookingId = '';

        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
            weekStart: 1
        });

        $('.timepicker').bootstrapMaterialDatePicker({
            date: false,
            shortTime: true,
            clearButton: true,
            format: 'hh:mm a',
            switchOnClick: true
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
        $('.birth-images').imageUploader({
            imagesInputName: 'birth_certificate[image]',
            maxFiles : 1,
        });

        $('.code').keypress(function(e) {
            code = $(this).val();
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                getPatientsDetails(code);
                e.preventDefault();
            }
        });

        $(document).on('click','.submit',function(e) {
            e.preventDefault();
            $('.discharge-save').attr("disabled", true);
            var discharge = new FormData($("#discharge-form")[0]);

            bookingId = $('#booked_id').val();
    
            if (this.value == 1) {
                discharge.append('isprint',1);
            }
            dischargeFormData(discharge);
        });

        function dischargeFormData(data) {
            $('.dischargedate-error').text('');
            $('.surgerydate-error').text('');
            if($('.dischargedate').val() != '' && new Date($('.admitdate').val()) > new Date($('.dischargedate').val())){
                $('.dischargedate-error').text('DOD should not be less than DOA');
                $('html, body').animate({
                scrollTop: ($('.dischargedate-error').offset().top - 150)
                }, 1000);
                return false;
            }
            if($('.surgerydate').val() != '' && new Date($('.admitdate').val()) > new Date($('.surgerydate').val())){
                $('.surgerydate-error').text('Date of Surgery should not be less than DOA');
                $('html, body').animate({
                scrollTop: ($('.surgerydate-error').offset().top - 150)
                }, 1000);
                return false;
            }
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('indoor/discharge/')}}" + '/' + bookingId,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(data){
                    var url = "{{URL::to('/indoor')}}";
                    if (data.status == 1){
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        setTimeout(function () {
                            w.window.print();
                            window.location.href = url;
                        }, 100);
                        // w.window.print();
                        // window.location.href = url;
                    } else {
                        window.location.href = url;
                    }
                }).fail(function(error){
                });
        }

        function getPatientsDetails(code) {
            var url = code + "/dischargeedit";
            $.ajax({
                url: url,
                type: "GET",
            }).done(function (data) {
                if (data['patients'] != null) {
                    $('.firstname').val(data['patients']['name']);
                    $('.age').val(data['patients']['age']);
                    $('.gender').val(data['patients']['gender']);
                    $('.wt').val(data['patients']['weight']);
                    $('.drincharge').val(data['patients']['hospital_doctor_id']);
                    $('.drincharge').selectpicker('refresh');

                } else {
                    resetForm();
                }
            }).fail(function (error) {
            });
        }
        function resetForm() {
            var code = $('.code').val();
            $('.discharge-form').trigger('reset');
            $('.code').val(code);
        }


    </script>
@stop
