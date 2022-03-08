@extends('layouts.main')
@section('parentPageTitle', 'Indoor')
@section('title', 'Discharge Card')

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">

@stop
@section('content')
    <div class="row clearfix indoor discharge-edit">
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
                        <li>
                            @php
                                $birthCertificate = !empty($dischargedata->birth_certificate) ? json_decode($dischargedata->birth_certificate): null;
                                $birth_image = !empty($birthCertificate) ? $birthCertificate->image : null;
                                $medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
                                $medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
                                $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
                                $dose = ['' => 'Select Dose',"1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
                            @endphp
                            @if(!empty($birth_image) && in_array($dischargedata->getIndoorBook->getprocedure['id'],[1,2,11]))
                            <a href="{{URl::to($birth_image)}}" target="_blank">
                                <button class="btn btn-primary">
                                    Birth Certificate
                                </button>
                            </a>
                            @endif
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
                        {{-- {{Form::open(['url'=>'indoor/discardupdate/'.$dischargedata->id,'method'=>'post','class'=>'form discharge-form','files'=>'true'])}} --}}
                        {{Form::open(['class'=>'form','id'=>'discharge-form','files'=>'true'])}}
                        {{ Form::hidden('discharge_id', !empty($dischargedata->id) ? encrypt($dischargedata->id) : null, [
                            'id' => 'discharge_id'
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
                                                                aria-controls="patients"> Patients Information</a> </h4>
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
                                                    {{Form::text('code', !empty($patientdata->code) ? $patientdata->code : null, ['class'=>'form-control code','placeholder'=>'Reg. No','readonly'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('code')}}
                                            </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Ward:</span>
                                                    {{Form::text('ward', !empty($bedNumber->getRoom['room_no']) ? $bedNumber->getRoom['room_no'] : null, [
                                                        'class'=>'form-control ward',
                                                        'placeholder'=>'Ward',
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
                                                    {{Form::text('room_type',!empty($bedNumber->getRoomType['name']) ? $bedNumber->getRoomType['name'] : null,['class'=>'form-control','disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Date of Admission : &nbsp;</span>
                                                    {{Form::text('admitdate',\Carbon\Carbon::Parse($bedNumber->doa_date)->format('D d M Y'),[
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
                                                    <span class="input-group-addon unik-lbl-spn">Name:</span>
                                                    {{Form::text('firstname',!empty($patientdata->name) ? $patientdata->name : null, ['class'=>'form-control firstname','placeholder'=>'Name'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('firstname')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Date of Discharge : &nbsp;</span>
                                                    {{Form::text('dischargedate',$bedNumber->dod_date ? \Carbon\Carbon::Parse($bedNumber->dod_date)->format('D d M Y') : null,[
                                                        'class'=>'form-control datetimepicker dischargedate',
                                                        'placeholder'=>'Date of Discharge',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg dischargedate-error"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Admission :</span>
                                                    {{ Form::text('admissiontime',$bedNumber->admit_time ? \Carbon\Carbon::Parse($bedNumber->admit_time)->format('h:i a') : null, [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Discharge :</span>
                                                    {{ Form::text('dischargetime',$bedNumber->discharge_time ? \Carbon\Carbon::Parse($bedNumber->discharge_time)->format('h:i a') : null, [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Gender:</span>
                                                    {{Form::text('Gender',!empty($gender) ? $gender : null, ['class'=>'form-control Gender','placeholder'=>'Gender','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('Gender')}}
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('age',!empty($patientdata['age']) ? $patientdata['age'] : null,['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength' => 4,'disabled'])}}
                                                </div>
                                                <span class="form-error-msg age-error"></span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('months',!empty($patientdata['months']) ? $patientdata['months'] : null,['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('days',!empty($patientdata['days']) ? $patientdata['days'] : null,['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Weight:</span>
                                                    {{Form::text('wt',!empty($patientdata->weight) ? $patientdata->weight : null, [
                                                        'class'=>'form-control wt',
                                                        'placeholder'=>'Weight',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('wt')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Doctor Incharge:</span>
                                                    {{Form::text('drincharge',!empty($patientdata->getHospitalDoctor['name']) ? $patientdata->getHospitalDoctor['name'] : null, ['class'=>'form-control drincharge','placeholder'=>'Doctor Incharge'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('drincharge')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Date of Surgery :</span>
                                                    {{Form::text('surgerydate',$dischargedata->dos_date ? \Carbon\Carbon::parse($dischargedata->dos_date)->format('D d M Y') : null,[
                                                        'class'=>'form-control datetimepicker surgerydate',
                                                        'placeholder'=>'Date of Surgery',
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
                                                {{Form::select('diagnosis[diagnosis][]',$diagnosis, !empty($diagnosisData) ? $diagnosisData : null,[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Diagnosis',
                                                    'multiple'=>true,
                                                    'required'
                                                    ])}}
                                                <span class="form-error-msg">
                                                {{$errors->first('rxtreatment')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Summary :</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('summary',!empty($dischargedata->summary) ? $dischargedata->summary : null,[
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
                                                {{Form::select('treatments[treatments][]',$givenTreatment, !empty($giventreatmentData) ? $giventreatmentData : null,[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Treatment Given',
                                                    'multiple'=>true,
                                                    'required'
                                                    ])}}
                                                <span class="form-error-msg">
                                                {{$errors->first('rxtreatment')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-2 unik-lbl-spn">
                                                        <label>Surgical Note :</label>        
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            {{Form::textarea('surgicalnote',!empty($dischargedata->surgical_note) ? $dischargedata->surgical_note : null,[
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
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-1 unik-lbl-spn">
                                                        <label>HPE:</label>        
                                                    </div>
                                                    <div class="col-sm-11">
                                                        <div class="form-group">
                                                            {{Form::textarea('hpe',!empty($dischargedata->hpe) ? $dischargedata->hpe : null,[
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
                                                            {{Form::textarea('report',!empty($dischargedata->report) ? $dischargedata->report : null,[
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
                                            
                                            <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                                {{Form::select('treatment[medicinedata][]',$medicines,null,[
                                                    'class'=>'form-control select-padding-0 medicine',
                                                    'id' => 'treatment-medicine',
                                                    'placeholder'=>'Enter medicine name',
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
                                            @php
                                                
                                                $treatmentData = json_decode($treatmentData);
                                                unset($treatmentData->medicinedata);
                                            @endphp
                                            @if(!empty($treatmentData))
                                                
                                                @foreach($treatmentData as $key=>$row)
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
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row clearfix">
                                            {{-- <div class="col-md-6 complain-mulit">
                                                {{Form::select('treatment[medicinedata][]',$medicines, !empty($treatmentData) ? $treatmentData : null,[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Rx(Treatment)',
                                                    'multiple'=>true,
                                                    'required'
                                                ])}}
                                                <span class="form-error-msg">
                                                    {{$errors->first('rxtreatment')}}
                                                </span>
                                            </div> --}}
                                            <div class="col-md-6 complain-mulit">
                                                <div class="form-group">
                                                </div>
                                                {{Form::select('complaints[]',$complaint,!empty($dischargedata->complaints) ? explode(',',$dischargedata->complaints) : null,[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=> 'Complaint',
                                                    'multiple'=>true,'
                                                    required'
                                                ])}}
                                            </div>
                                        </div>
                                        {{Form::hidden('old_medicine_data',null,['class'=>'old-medicine-data'])}}
                                        <div class="row clearfix mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-3 unik-lbl-spn">
                                                        <label>Admission Vitals :</label>        
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="form-group">
                                                            {{Form::textarea('vitals',!empty($dischargedata->admission_vitals) ? $dischargedata->admission_vitals : null,[
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
                                                            {{Form::textarea('examination',!empty($dischargedata->examination) ? $dischargedata->examination : null,[
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
                                                            {{Form::textarea('clinicalsummary',!empty($dischargedata->clinical_summary) ? $dischargedata->clinical_summary : null,[
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
                                                            {{Form::textarea('dischargevital',!empty($dischargedata->vital_on_discharge) ? $dischargedata->vital_on_discharge : null,[
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
                                                            {{Form::textarea('condition',!empty($dischargedata->cond_on_discharge) ? $dischargedata->cond_on_discharge : null,[
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
                                                    <span class="input-group-addon unik-lbl-spn">Follow Up:</span>
                                                    {{Form::text('followup',!empty($dischargedata->follow_up) ? $dischargedata->follow_up : null, [
                                                        'class'=>'form-control followup',
                                                        'placeholder'=>'Follow Up'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('followup')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Follow up date : &nbsp;</span>
                                                    {{Form::text('followdate',$dischargedata->followup_date ? \Carbon\Carbon::parse($dischargedata->followup_date)->format('D d M Y') : null,[
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
                                            $is_birthCertificate = in_array($dischargedata->getIndoorBook->getprocedure['id'],[1,2,11]) ? '' : 'd-none';
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
                                                            {{Form::textarea("birth_certificate[remark]",!empty($dischargedata->birth_certificate) ? json_decode($dischargedata->birth_certificate)->remark : null,['class'=>'form-control',"rows"=>2,"placeholder"=>"Birth Remark"])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                            
                                @if($bookpatientdata->is_direct_discharge == 0)
                                    {{Form::submit('Save',['class'=>'btn btn-primary submit discharge-save'])}}
                                @endif
                                <button type="submit" data-id="" class="btn btn-primary submit discharge-save" value="1">Save & Preview</button>
                                @if($bookpatientdata->is_discharge_card == 1)
                                    <a data-id="{{$dischargedata->id}}" class="btn btn-primary send-discharge-msg" data-toggle="modal" data-target="#sendMsgModal" data-backdrop="static" data-keyboard="false">Send Message</a>
                                @endif
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

@section('modal')

<div class="modal fade" id="sendMsgModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{URL::to('send-sms')}}" method="post">
            @csrf
            <div class="modal-body text-left">

                <div class="mb-2">
                <span>Mobile Number : </span>                           
                <input type="hidden" name="mobile" class="mobile-number">
                <span class="mobilenumber"></span>
                </div>
               
                <div>Message :</div>
                <div class="Message mb-2">   
                <div class="textmessage">
                                             
                </div>
                    <textarea type="text" class="discharge-msg form-control" name="message" width="100%">

                            
                    </textarea>                                       
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary send-msg">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
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
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script src="{{URL::to('public/js/anc.js')}}"></script>

    <script>
        var dischargeId = '';
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
            weekStart: 1
        });

        $('.timepicker').bootstrapMaterialDatePicker({
            date: false,
            shortTime: true,
            format: 'hh:mm a',
            switchOnClick: true
        });

        // $(function () {
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
        var birthImagesData = @json($birthImagesData);
        $('.birth-images').imageUploader({
                preloaded: jQuery.parseJSON(birthImagesData),
                imagesInputName: 'birth_certificate[image]',
                preloadedInputName: 'birth_report_old'

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
                

                dischargeId = $('#discharge_id').val();
        
                if (this.value == 1) {
                    discharge.append('isprint',1);
                }
                dischargeFormData(discharge);
        });

        $(document).on('click','.send-discharge-msg', function() {
            dischargeId = $(this).data('id');
            getMsgData(dischargeId);
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
                    url: "{{URL::to('indoor/discardupdate/')}}" + '/' + dischargeId,
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
                alert(data);
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
        function getMsgData(data) {
            $.ajax({
                url: "{{URL::to('get-message-detail')}}",
                type: "GET",
                data: {id:data},
            }).done(function (data) {
                var smsdata=data.data;
                $('.discharge-msg').val(smsdata.message);
                $('.mobile-number').val(smsdata.mobile_number);
                $(".mobilenumber").text(smsdata.mobile_number);
            }).fail(function (error) {
            });
        }
       
    </script>
@stop
