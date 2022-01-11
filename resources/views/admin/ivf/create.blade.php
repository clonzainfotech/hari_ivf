@extends('layouts.main')
@section('parentPageTitle', 'IVF Appointment')
@section('title', 'Add IVF Appointment')

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">
    <style>
        .overy-popup{
            cursor: pointer;
        }
    </style>
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header">
                    @php
                        $careof = isset($referenceDoctor[$ancPatients->reference_doctor_id]) ? $referenceDoctor[$ancPatients->reference_doctor_id]: '';
                        if(!empty($ancPatients->reference_doctor_id) && $ancPatients->reference_doctor_id == 1)
                        {
                            $careof = !empty($ancPatients->reference_pt_name) && !empty($ancPatients->reference_pt_mobile) ? $ancPatients->reference_pt_name.'('.$ancPatients->reference_pt_mobile.')' :'SELF';
                        }
                    @endphp
                    <h2><strong class="text-secondary">{{ucwords($ancPatients->name)}}</strong>{{' care of '.$careof}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix ivf">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>IVF Appointment</strong>
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
                        @php
                            $donar = !empty($appointmentData) && ($appointmentData->category_id == 11 || $appointmentData->category_id == 14) ? 'd-none' : '';
                            $dTab = !empty($donar) ? '' : 'd-none';
                            $dStatus = !empty($donar) ? '1' : '0';
                        @endphp
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-form'])}}
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
                                {{Form::hidden('ivf_id', null, ['id' => 'ivf_id'])}}
                                {{Form::hidden('donor[is_donors]', $dStatus)}}
                                    <!--1 patients basic information -->
                                <div class="{{'panel panel-primary'}}">
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
                                                    {{Form::text('code',$ancPatients['code'],['class'=>'form-control code','disabled'])}}
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
                                                    {{Form::number("weight",$ancPatients->weight,['class'=>'form-control weight','id'=>'weight'])}}
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
                                                        {{Form::select('category',$category,$appointmentData['category_id'],['class'=>'form-control select-padding-0 category_data ctgry','placeholder'=>'Select Category'])}}
                                                    </div>
                                                </div>
                                                {{Form::hidden('appointment_id',encrypt($appointmentData['id']),['id'=>'aId'])}}
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
                                                    {{Form::select('rd_reference',$referenceDoctor,$ancPatients->reference_doctor_id,['class'=>'form-control select-padding-0 refence-doctor','placeholder'=>'Reference Name'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('rd_reference')}}
                                                </span>
                                            </div>
                                            @if($ancPatients->reference_doctor_id == 1)
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Reference Patient Name : &nbsp;</span>
                                                        {{Form::text('ref_pt_name',$ancPatients->reference_pt_name,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Reference Mobile : &nbsp;</span>
                                                    {{Form::number('rd_mobile_number',$ancPatients->reference_doctor_id == 1 ?  $ancPatients->getReferenceDoctor['reference_pt_mobile'] : $ancPatients->getReferenceDoctor['mobile_number'],['class'=>'form-control ref-mobile-number'])}}
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
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        {{Form::textarea("p_info[other_info]",'',['class'=>'form-control no-resize other_info','placeholder'=>'Other Information','rows'=>'5'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('other_info')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        {{Form::textarea("p_info[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('remark')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="{{'panel panel-primary '.$dTab}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#donor" aria-expanded="true"
                                            aria-controls="donor">2. Donar Information</a> </h4>
                                    </div>
                                    <div id="donor" class="panel-collapse collapse p-info" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">
                                                            Face Color : &nbsp;
                                                        </span>
                                                        {{Form::text("donor[face_color]",'',[
                                                            'class'=>'form-control',
                                                            'placeholder' => 'Face Color'
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon  unik-lbl-spn">
                                                            Hair Color : &nbsp;
                                                        </span>
                                                        {{Form::text("donor[hair_color]",'',[
                                                            'class'=>'form-control',
                                                            'placeholder' => 'Hair Color'
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon  unik-lbl-spn">
                                                            Eye Color : &nbsp;
                                                        </span>
                                                        {{Form::text("donor[eye_color]",'',[
                                                            'class'=>'form-control',
                                                            'placeholder' => 'Eye Color'
                                                        ])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon  unik-lbl-spn">
                                                            CBC MP : &nbsp;
                                                        </span>
                                                        {{Form::text("donor[cbc_mp]",'',[
                                                            'class'=>'form-control',
                                                            'placeholder' => 'CBC MP'
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon  unik-lbl-spn">
                                                            Urine : &nbsp;
                                                        </span>
                                                        {{Form::text("donor[urine]",'',[
                                                            'class'=>'form-control',
                                                            'placeholder' => 'Urine'
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-1 unik-lbl-spn">
                                                    Blood Group
                                                </div>
                                                <div class="col-sm-1">
                                                    {{Form::select('donor[blood_group]',[
                                                        'A+' => 'A+',
                                                        'A-' => 'A-',
                                                        'B+' => 'B+',
                                                        'B-' => 'B-',
                                                        'O+' => 'O+',
                                                        'O-' => 'O-',
                                                        'AB+' => 'AB+',
                                                        'AB-' => 'AB-',
                                                    ],[
                                                        'class'=>'form-control select-padding-0 blood-group',
                                                    ])}}
                                                </div>
                                                
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon  unik-lbl-spn">
                                                            RBS : &nbsp;
                                                        </span>
                                                        {{Form::text("donor[rbs]",'',[
                                                            'class'=>'form-control',
                                                            'placeholder' => 'RBS'
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0 unik-lbl-spn">
                                                        HIV :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("donor[hiv]",1,true,[
                                                            'id'=>'hiv_positive',
                                                            'class'=>'hiv'
                                                        ])}}
                                                        <label for="hiv_positive">
                                                            Positive
                                                        </label>
                                            
                                                        {{Form::radio("donor[hiv]",0,'',[
                                                            'id'=>'hiv_nagative',
                                                            'class'=>'hiv'
                                                        ])}}
                                                        <label for="hiv_nagative">
                                                            Negative
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0 unik-lbl-spn">
                                                        HBSAG :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("donor[hbsag]",1,true,[
                                                            'id'=>'hbsag_positive',
                                                            'class'=>'hbsag'
                                                        ])}}
                                                        <label for="hbsag_positive">
                                                            Positive
                                                        </label>
                                            
                                                        {{Form::radio("donor[hbsag]",0,'',[
                                                            'id'=>'hbsag_nagative',
                                                            'class'=>'hbsag'
                                                        ])}}
                                                        <label for="hbsag_nagative">
                                                            Negative
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0 unik-lbl-spn">
                                                        VDRL :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("donor[vdrl]",1,true,[
                                                            'id'=>'vdrl_positive',
                                                            'class'=>'vdrl'
                                                        ])}}
                                                        <label for="vdrl_positive">
                                                            Positive
                                                        </label>
                                            
                                                        {{Form::radio("donor[vdrl]",0,'',[
                                                            'id'=>'vdrl_nagative',
                                                            'class'=>'vdrl'
                                                        ])}}
                                                        <label for="vdrl_nagative">
                                                            Negative
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-md-2 pr-0">
                                                    <label class="vertical-form-label pr-0 unik-lbl-spn">
                                                        Aadhar Card :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("donor[is_aadhar]",1,true,[
                                                            'id'=>'is_aadhar_yes',
                                                            'class'=>'aadhar-card'
                                                        ])}}
                                                        <label for="is_aadhar_yes">
                                                            Yes
                                                        </label>
                                            
                                                        {{Form::radio("donor[is_aadhar]",0,'',[
                                                            'id'=>'is_aadhar_no',
                                                            'class'=>'aadhar-card'
                                                        ])}}
                                                        <label for="is_aadhar_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 pr-0">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">
                                                            Adhar Image : &nbsp;
                                                        </span>
                                                        {{Form::file('donor[aadhar_image[]]',[
                                                            'class'=>'form-control',
                                                            'accept' => 'image/png,image/jpeg,image/jpg',
                                                            'multiple' => true
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 pr-0">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">
                                                            Image : &nbsp;
                                                        </span>
                                                        {{Form::file('donor[image]',[
                                                            'class'=>'form-control',
                                                            'accept' => 'image/png,image/jpeg,image/jpg'
                                                        ])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--2 H/O -->
                                <div class="{{'panel panel-primary '.$donar}}">
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
                                                        {{Form::text("ho[ho_details]",'',['class'=>'form-control ho-data'])}}
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
                                                <div class='col-md-11 complain-multi duration-value'>
                                                    {{Form::select('ho[ho_details]',$hoData,'',['class'=>'form-control ho-data select-padding-0 anc-dose-val duration-data ho_type_value','placeholder'=>'Select H/O','data-medicine'=>2])}}
                                                    <span class="form-error-msg ho-data-msg">
                                                        {{$errors->first('ho_details')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--3 C/O -->
                                <div class="{{'panel panel-primary '.$donar}}">
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

                                <!--4 O/H -->
                                <div class="{{'panel panel-primary'}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#accordion_1" href="#obstratics_history"
                                                               aria-expanded="false"
                                                               aria-controls="obstratics_history">4. O/H</a></h4>
                                    </div>
                                    <div id="obstratics_history" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">First Marriage Life : &nbsp;</span>
                                                        {{Form::text("oh[first_marriage_life]",'',['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('marriage_life')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Active Marriage Life : &nbsp;</span>
                                                        {{Form::text("oh[active_marriage_life]",'',['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('active_marriage_life')}}
                                                    </span>
                                                </div>
                                                {{-- <div class="col-md-1">
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
                                                <div class="col-md-2">
                                                    <div class="from-group">
                                                        {{Form::text('oh[upt_details]','',[
                                                            'class'=>'form-control upt_details',
                                                            'placeholder' => 'UPT Details'
                                                        ])}}
                                                    </div>
                                                </div> --}}
                                                <div class="col-md-1 d-none upt_details_label">
                                                    <label class="vertical-form-label pr-0">
                                                        Days Before
                                                    </label>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{Form::select("oh[type_of_infertility]",[1=>'Primary',2=>'Secondary'],'',['class'=>'form-control select-padding-0','placeholder'=>'Type Of Infertility'])}}
                                                    </div>
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
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[child][child_data][1][ho_type_value]",'normal',true,['id'=>'normal'])}}
                                                        <label for="normal">
                                                            Normal
                                                        </label>

                                                        {{Form::radio("oh[child][child_data][1][ho_type_value]",'cesarean','',['id'=>'cesarean'])}}
                                                        <label for="cesarean">
                                                            Cesarean
                                                        </label>

                                                        {{Form::radio("oh[child][child_data][1][ho_type_value]",'instrumental','',['id'=>'instrumental'])}}
                                                        <label for="instrumental">
                                                            Instrumental
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[child][child_data][1][ho_gender]",'male','',['id'=>'ho_male'])}}
                                                        <label for="ho_male">
                                                            Male
                                                        </label>

                                                        {{Form::radio("oh[child][child_data][1][ho_gender]",'female','',['id'=>'ho_female'])}}
                                                        <label for="ho_female">
                                                            Female
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br />
                                            <div class="row child-data-parent d-none">
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
                                                <div class="col-sm-2">
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
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">MOA &nbsp;</span>
                                                            {{Form::text("oh[mtp][mtp_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Before &nbsp;</span>
                                                            {{Form::text("oh[mtp][mtp_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
                                                        </div>
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
                                            </div>
                                            {{-- end contraception --}}

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
                                                <div class="col-sm-3">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[second_marriage][child][child_data][1][ho_type_value]",'normal',true,['id'=>'second_normal'])}}
                                                        <label for="second_normal">
                                                            Normal
                                                        </label>

                                                        {{Form::radio("oh[second_marriage][child][child_data][1][ho_type_value]",'cesarean','',['id'=>'second_cesarean'])}}
                                                        <label for="second_cesarean">
                                                            Cesarean
                                                        </label>

                                                        {{Form::radio("oh[second_marriage][child][child_data][1][ho_type_value]",'instrumental','',['id'=>'second_instrumental'])}}
                                                        <label for="second_instrumental">
                                                            Instrumental
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oh[second_marriage][child][child_data][1][ho_gender]",'male','',['id'=>'second_ho_male'])}}
                                                        <label for="second_ho_male">
                                                            Male
                                                        </label>
                                                        {{Form::radio("oh[second_marriage][child][child_data][1][ho_gender]",'female','',['id'=>'second_ho_female'])}}
                                                        <label for="second_ho_female">
                                                            Female
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row second-marriage-life-data second-child-data-parent d-none">
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
                                                        {{Form::text("oh[second_marriage][child][child_data][1][expired_reason]",'',['class'=>'form-control','placeholder'=>'Reason'])}}
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
                                                    {{-- <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Month Of Pregnancy &nbsp;</span>
                                                            {{Form::text("oh[second_marriage][mtp][mtp_data][1][mtp_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">MOA &nbsp;</span>
                                                            {{Form::text("oh[second_marriage][mtp][mtp_data][1][spontancous_abortion_month_of_pregancy]",'',['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Before &nbsp;</span>
                                                            {{Form::text("oh[second_marriage][mtp][mtp_data][1][spontancous_abortion_before]",'',['class'=>'form-control'])}}
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
                                            </div>
                                            {{-- end contraception second marriage --}}

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{Form::textarea('oh[remark]', null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            {{Form::hidden('patients_id',$patientsId,['id'=>'pId'])}}
                                        </div>
                                    </div>
                                </div>

                                <!--5 Personal history  -->
                                <div class="{{'panel panel-primary'}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#personal-history" href="#personal-history" aria-expanded="false"
                                                aria-controls="personal-history ">5. M/H</a></h4>
                                    </div>
                                    <div id="personal-history" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">

                                            <div class="row">
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{Form::select("mh[type_and_year_of_infertility]",['primary'=>'Primary','secondary'=>'Secondary'],'',['class'=>'form-control select-padding-0','placeholder'=>'Type And Year Of Infertility'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('type_and_year_of_infertility')}}
                                                    </span>
                                                </div> --}}
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        {{Form::select("mh[age_of_menarchy]",[11=>11,12=>12,13=>13,14=>14,15=>15],'',['class'=>'form-control select-padding-0','placeholder'=>'Age Of Menarchy'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('age_of_menarchy')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Since Year : &nbsp;</span>
                                                        {{Form::text("mh[since_year]",'',['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('since_year')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
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
                                                <div class="col-md-2 past-ir-regular-data d-none">
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

                                                <div class="col-md-2 present-ir-regular-data d-none">
                                                    {{Form::select("mh[present_mh_1]",['scanty'=>'Scanty','mod'=>'Mod','heavy'=>'Heavy'],'',['class'=>'form-control select-padding-0 present-mh-1'])}}
                                                    <span class="form-error-msg">
                                                        {{$errors->first('present_mh_1')}}
                                                    </span>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        {{Form::select("mh[present_mh_2]",['regular'=>'Regular','irregular'=>'IR Regular'],'',['class'=>'form-control select-padding-0 present-mh-2 present-mh-2 regular-type','data-id'=>'present-ir-regular-data'])}}
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
                                            <div class="row present-ir-regular-data d-none">
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
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Last Menstrual Date : &nbsp;</span>
                                                        {{Form::text("mh[last_menstrual_date]",!empty($lastAppointment->date) ? \Carbon\Carbon::parse($lastAppointment->date)->format('D d M Y') : null,['class'=>'form-control lmd-date','required'])}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-2 lmd-date-diff d-none"></span>
                                                {{Form::hidden('mh[lmd_date_diff]','',['class'=>'lmd-date-diff-val'])}}
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Since Month : &nbsp;</span>
                                                        {{Form::text("mh[since_month]",'',['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('since_month')}}
                                                    </span>
                                                </div>

                                                <div class="col-md-2">
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

                                <!--6. H/O rx -->
                                <div class="{{'panel panel-primary '.$donar}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#ho-data" href="#ho-data" aria-expanded="false"
                                                aria-controls="ho-data">6. H/O Rx.</a></h4>
                                    </div>
                                    <div id="ho-data" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        H/O Taken :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("ho_rx[taken][status]",'yes','',['id'=>'ho_taken_yes','class'=>'iui-yes-no-status','data-type'=>'ho-taken-type'])}}
                                                        <label for="ho_taken_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("ho_rx[taken][status]",'no',true,['id'=>'ho_taken_no','class'=>'iui-yes-no-status','data-type'=>'ho-taken-type'])}}
                                                        <label for="ho_taken_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 ho-taken-type d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            How Much : &nbsp;
                                                        </span>
                                                        {{Form::number("ho_rx[taken][how_much_no]",'',['class'=>'form-control how-much-taken','data-id'=>'taken','onwheel'=>'this.blur()'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row taken-data"></div>
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        I.U.I :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("ho_rx[iui][status]",'yes','',['id'=>'ho_iui_yes','class'=>'iui-yes-no-status','data-type'=>'ho-iui-type'])}}
                                                        <label for="ho_iui_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("ho_rx[iui][status]",'no',true,['id'=>'ho_iui_no','class'=>'iui-yes-no-status','data-type'=>'ho-iui-type'])}}
                                                        <label for="ho_iui_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 ho-iui-type d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            How Much : &nbsp;
                                                        </span>
                                                        {{Form::number("ho_rx[iui][how_much_no]",'',['class'=>'form-control how-much-taken','data-id'=>'iui','onwheel'=>'this.blur()'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 ho-iui-type d-none">
                                                    <div class="form-group">
                                                        {{Form::text("ho_rx[iui][details]",'',['class'=>'form-control','placeholder'=>'Medicine'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row iui-data"></div>
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        I.V.F :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("ho_rx[ivf][status]",'yes','',['id'=>'ho_ivf_yes','class'=>'iui-yes-no-status','data-type'=>'ho-ivf-type'])}}
                                                        <label for="ho_ivf_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("ho_rx[ivf][status]",'no',true,['id'=>'ho_ivf_no','class'=>'iui-yes-no-status','data-type'=>'ho-ivf-type'])}}
                                                        <label for="ho_ivf_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 ho-ivf-type d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            How Much : &nbsp;
                                                        </span>
                                                        {{Form::number("ho_rx[ivf][how_much_no]",'',['class'=>'form-control how-much-taken','data-id'=>'ivf','onwheel'=>'this.blur()'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ivf-data"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 7. investigation --}}
                                <div class="{{'panel panel-primary '.$donar}}">
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
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("investigation[hystroscopy][type]",'yes','',['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                                                        <label for="hystroscopy_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("investigation[hystroscopy][type]",'no',true,['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
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
                                                        {{Form::text("investigation[laproscopy][finding_date]",'',['class'=>'form-control datetimepicker date'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("investigation[laproscopy][type]",'yes','',['id'=>'laproscopy_type_yes','class'=>'iui-yes-no-status','data-type'=>'laproscopy-status-type'])}}
                                                        <label for="laproscopy_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("investigation[laproscopy][type]",'no',true,['id'=>'laproscopy_type_no','class'=>'iui-yes-no-status','data-type'=>'laproscopy-status-type'])}}
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
                                                        {{Form::text("investigation[hcg][date]",'',['class'=>'form-control datetimepicker date'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("investigation[hcg][type]",'yes','',['id'=>'hcg_laproscopy_type_yes','class'=>'iui-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
                                                        <label for="hcg_laproscopy_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("investigation[hcg][type]",'no',true,['id'=>'hcg_laproscopy_type_no','class'=>'iui-yes-no-status','data-type'=>'hcg-laproscopy-status-type'])}}
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
                                            </div>
                                            <div class="row hcg-laproscopy-type hcg-laproscopy-status-type-abnormal d-none">
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
                                            </div>
                                            {{-- end hcg  --}}
                                            <br>
                                            <div class="row">
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
                                                            LH : &nbsp;
                                                        </span>
                                                        {{Form::text("investigation[lh]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
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
                                            </div>
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
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Blood Report :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("investigation[blood_report][type]",'yes','',['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                                        <label for="blood_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("investigation[blood_report][type]",'no',true,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                                        <label for="blood_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 pr-0 blood-type d-none">
                                                    <div class="blood-images"></div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
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
                                
                                <!--8. Husband Factor -->
                                <div class="{{'panel panel-primary '.$donar}}">
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
                                                        {{Form::text("h_factor[occupation]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{Form::select("h_factor[seman_analysis]",['1'=>'WNL','2'=>'Oligospermia','3'=>'Azoospermic'],'',['class'=>'form-control select-padding-0 seman-analysis','placeholder'=>'Seman Analysis'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Age : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[age]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Habbit : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[habbit]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Amount In Ml : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[amount_in_ml]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Sperm Count : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[sperm_count]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Motility : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[motility]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Date : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[personal_history_date]",'',['class'=>'form-control datetimepicker date'])}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row seman-analysis-type d-none">
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Medicine : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[medicine]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Duration : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[duration]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            Sperm Report : &nbsp;
                                                        </span>
                                                        {{Form::text("h_factor[sperm_report]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        HSA Report :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("investigation[hsa_report][type]",'yes','',['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                                                        <label for="hsa_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("investigation[hsa_report][type]",'no',true,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                                                        <label for="hsa_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 pr-0 hsa-type d-none">
                                                    <div class="hsa-images"></div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        {{Form::textarea("h_factor[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--9. Patients Detailed H/O  -->
                                <div class="{{'panel panel-primary '.$donar}}">
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

                                {{-- 10. o/e--}}
                                <div class="{{'panel panel-primary'}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#o_e" href="#o_e" aria-expanded="false"
                                                aria-controls="past-history">10. O/E</a></h4>
                                    </div>
                                    <div id="o_e" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body" id="parent">
                                            <div class="{{$donar.' row'}}">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        P/S :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[p_s][type]",'yes','',['id'=>'ps_type_yes','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                                        <label for="ps_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("oe[p_s][type]",'no',true,['id'=>'ps_type_no','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
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
                                            <div class="{{$donar.' row'}}">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Cervix :
                                                    </label>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        {{Form::text("oe[cervix][details]",'',['class'=>'form-control','placeholder'=>'Cervix Details'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        TVS :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[tvs][type]",'yes','',['id'=>'tvs_type_yes','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                                        <label for="tvs_type_yes">
                                                            Yes
                                                        </label>
                                                        {{Form::radio("oe[tvs][type]",'no',true,['id'=>'tvs_type_no','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                                        <label for="tvs_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row tvs-details d-none">
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
                                                <div class="col-md-2 uterus-abnormal-type d-none">
                                                    <div class="form-group">
                                                        {{Form::text("oe[uterus][details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-2 uterus-abnormal-type d-none">LG</span>
                                            </div>
                                            <div class="row tvs-details d-none">
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
                                            <div class="row tvs-details d-none">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 pr-0 tvs-details d-none">
                                                    <label class="vertical-form-label pr-0">
                                                        Ovary :
                                                    </label>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{-- {{Form::checkbox('oe[ovary][type][]','right','',['id'=>'right','class'=>'plan-management','data-id'=>'right-details'])}} --}}
                                                        {{Form::checkbox('oe[ovary][type][]','right','',['id'=>'right','class'=>'plan-management'])}}
                                                        <label for="right">
                                                            Right
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-3 right-details">
                                                    <div class="form-group">
                                                        {{Form::select("oe[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}  
                                                    </div>
                                                </div> --}}
                                                <div class='col-md-4 d-none afcs-details'>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">AFCS : &nbsp;</span>
                                                        {{Form::text("oe[ovary][right][afcs]",'',['class'=>'form-control right-ovary-data-text'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:void(0)" class="right-ovary-data overy-popup" data-class="right-ovary-data">Keyboard</a>
                                                </div>
                                            </div>
                                            <div class="row tvs-details">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9 right-details">
                                                    <div class="row">
                                                        <div class="col-md-5 complain-multi ovary-right-abnormal-type tvs-details mt-1 d-none">
                                                            {{Form::select("oe[ovary][right][details][]",$rightOvaryData,'',[
                                                                'class'=>'form-control co-value co_value_data history-oe-ovary-right-details',
                                                                'placeholder'=>'Abnormal Details',
                                                                'id' => 'oe_ovary_right_details',
                                                                'data-type'=>'oe',
                                                                'multiple'=>true
                                                            ])}}
                                                        </div>
                                                        <div class="col-md-6 complain-multi ovary-right-abnormal-type tvs-details d-none">
                                                            <div class="row edit_oe_ovary_right_details">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row tvs-details d-none">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{-- {{Form::checkbox('oe[ovary][type][]','left','',['id'=>'left','class'=>'plan-management','data-id'=>'left-details'])}} --}}
                                                        {{Form::checkbox('oe[ovary][type][]','left','',['id'=>'left','class'=>'plan-management'])}}
                                                        <label for="left">
                                                            Left
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-3 left-details">
                                                    <div class="form-group">
                                                        {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}  
                                                    </div>
                                                </div> --}}
                                                <div class='col-md-4 d-none afcs-details'>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">AFCS : &nbsp;</span>
                                                        {{Form::text("oe[ovary][left][afcs]",'',['class'=>'form-control left-ovary-data-text'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:void(0)" class="left-ovary-data overy-popup" data-class="left-ovary-data">Keyboard</a>
                                                </div>
                                            </div>
                                            <div class="row tvs-details">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9 left-details">
                                                    <div class="row">
                                                        <div class="col-md-5 complain-multi ovary-left-abnormal-type tvs-details d-none">
                                                            {{Form::select("oe[ovary][left][details][]",$leftOvaryData,'',[
                                                                'class'=>'form-control co-value co_value_data history-oe-ovary-left-details',
                                                                'placeholder'=>'Abnormal Details',
                                                                'id' => 'oe_ovary_left_details',
                                                                'data-type'=>'oe',
                                                                'multiple'=>true
                                                            ])}}
                                                        </div>
                                                        <div class="col-md-6 complain-multi ovary-left-abnormal-type tvs-details d-none">
                                                            <div class="row edit_oe_ovary_left_details">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="{{$donar.' row'}}">
                                                <div class="col-md-1"> 
                                                    <div class="checkbox">
                                                        {{Form::checkbox('oe[le][vitals_status]','yes','',['class'=>'vitals_status','id'=>'oe_vitals_status','data-id'=>'oe_vitals_status_data'])}}
                                                        <label for="oe_vitals_status">
                                                            Vitals
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 oe_vitals_status_data d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                                        {{Form::text("oe[le][bp]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-2 oe_vitals_status_data d-none">MMHG</span>
                                                <div class="col-md-2 oe_vitals_status_data d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                                        {{Form::text("oe[le][temp]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 oe_vitals_status_data d-none">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                                        {{Form::text("oe[le][pulse]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-2 oe_vitals_status_data d-none">/ Min</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{Form::textarea('oe[remark]',null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--11. Possible cause of infertility  -->
                                <div class="{{'panel panel-primary '.$donar}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#pmanagement" href="#pmanagement" aria-expanded="false"
                                                aria-controls="past-history">11. Plan Of Management</a></h4>
                                    </div>
                                    <div id="pmanagement" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body" id="parent">
                                            <div class="row category-iui d-none">
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','counceling','',['id'=>'counceling','class'=>'plan-management','data-id'=>'counceling'])}}
                                                        <label for="counceling">
                                                            Counceling
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 counceling d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[counceling_details]",'',['class'=>'form-control','placeholder'=>'Counceling Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','wait_watch','',['id'=>'wait_watch','class'=>'plan-management','data-id'=>'wait-watch'])}}
                                                        <label for="wait_watch">
                                                            Wait Watch
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 wait-watch d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[wait_watch_details]",'',['class'=>'form-control','placeholder'=>'Wait Watch Details'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-iui d-none">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','management_by_rx','',['id'=>'management_by_rx','class'=>'plan-management','data-id'=>'management-by-rx-details'])}}
                                                        <label for="management_by_rx">
                                                            Management by Rx.
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 management-by-rx-details d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[management_by_rx_details]",'',['class'=>'form-control','placeholder'=>'Management By Rx. Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 management-by-rx-details d-none">
                                                    <div class="form-group">
                                                        {{Form::select("plan_of_management[management_by_rx_data][]",['1'=>'Clomiphene Citrate','2'=>'Letroze'],'',['class'=>'form-control select-padding-0','title'=>'Management By Rx. Details','multiple'=>true])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-iui d-none">
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','hyperstimulation_iui','',['id'=>'hyperstimulation_iui','class'=>'plan-management','data-id'=>'hyperstimulation-iui-details'])}}
                                                        <label for="hyperstimulation_iui">
                                                            Controlled Overian Hyperstimulation With I.U.I
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 hyperstimulation-iui-details d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[hyperstimulation_iui_details]",'',['class'=>'form-control','placeholder'=>'Controlled Overian Hyperstimulation With I.U.I'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 hyperstimulation-iui-details d-none">
                                                    <div class="form-group">
                                                        {{Form::select("plan_of_management[hyperstimulation_iui_data][]",['1'=>'Only Medicine','2'=>'Medicine + Gonadotropins','3'=>'Only Gonadotropins'],'',['class'=>'form-control select-padding-0','title'=>'Controlled Overian Hyperstimulation I.U.I Data','multiple'=>true])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-iui d-none">
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','laproscopy','',['id'=>'laproscopy','class'=>'plan-management','data-id'=>'laproscopy-data'])}}
                                                        <label for="laproscopy">
                                                            Laproscopy
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 laproscopy-data d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[laproscopy_details]",'',['class'=>'form-control','placeholder'=>'Laproscopy Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 laproscopy-data d-none">
                                                    <div class="form-group">
                                                        {{Form::select("plan_of_management[laproscopy_data][]",['1'=>'HSG','2'=>'Hystroscopy','3'=>'DHL','4'=>'Oher'],'',['class'=>'form-control select-padding-0','title'=>'Laproscopy Data','multiple'=>true])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-iui d-none">
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','ivf','',['id'=>'ivf','class'=>'plan-management','data-id'=>'ivf-details'])}}
                                                        <label for="ivf">
                                                            I.V.F
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 ivf-details d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[ivf_details]",'',['class'=>'form-control','placeholder'=>'IVF Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 ivf-details d-none">
                                                    <div class="form-group">
                                                        {{Form::select("plan_of_management[ivf_data][]",['1'=>'Self','2'=>'OD','3'=>'ED'],'',['class'=>'form-control select-padding-0','title'=>'I.V.F Data','multiple'=>true])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-iui d-none">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','male_factor','',['id'=>'male_factor','class'=>'plan-management','data-id'=>'male-factor-data'])}}
                                                        <label for="male_factor">
                                                            Rx. Of Male Factor
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 male-factor-data d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[male_factor_details]",'',['class'=>'form-control','placeholder'=>'Rx. Of Male Factor Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','reports','',['id'=>'reports','class'=>'plan-management','data-id'=>'reports-data'])}}
                                                        <label for="reports">
                                                            Reports
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 reports-data d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[reports_details]",'',['class'=>'form-control','placeholder'=>'Reports Details'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-iui d-none">
                                                <div class="col-md-3">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','induction_gonadotropins_cycle','',['id'=>'induction_gonadotropins_cycle','class'=>'plan-management','data-id'=>'induction-gonadotropins-cycle-details'])}}
                                                        <label for="induction_gonadotropins_cycle">
                                                            Induction Gonadotropins Cycle
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 induction-gonadotropins-cycle-details d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[induction_gonadotropins_cycle_details]",'',['class'=>'form-control','placeholder'=>'Induction Gonadotropins Cycle Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','other','',['id'=>'plan_of_management_other','class'=>'plan-management','data-id'=>'plan_of_management_other_data'])}}
                                                        <label for="plan_of_management_other">
                                                            Other
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 plan_of_management_other_data d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[other_details]",'',['class'=>'form-control','placeholder'=>'Other Details'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row category-ivf">
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[is_print]','is_print','',['id'=>'is_print','class'=>'plan-management'])}}
                                                        <label for="is_print">
                                                            Is Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[plan_of_management_data][]','ivf','',['id'=>'ivf','class'=>'plan-management','data-id'=>'ivf-details'])}}
                                                        <label for="ivf">
                                                            I.V.F
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 ivf-details d-none">
                                                    <div class="form-group">
                                                        {{Form::text("plan_of_management[ivf_details]",'',['class'=>'form-control','placeholder'=>'IVF Details'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{Form::select("plan_of_management[plan]",['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'],'',[
                                                            'class'=>'form-control select-padding-0 plan ivf-plan',
                                                            'placeholder'=>'Plan'
                                                        ])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-1 div-pick-with-sd d-none">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('plan_of_management[pick_with_sd]','yes','',['id'=>'pick_with_sd','class'=>'plan-management'])}}
                                                        <label for="pick_with_sd">
                                                            IVF-SD
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--12. possible_cause_of_infertility --}}
                                <div class="{{'panel panel-primary '.$donar}}">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#possible_cause_of_infertility" href="#possible_cause_of_infertility" aria-expanded="false"
                                                aria-controls="past-history">12. Possible Cause Of Infertility</a></h4>
                                    </div>
                                    <div id="possible_cause_of_infertility" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body" id="parent">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','ovarian','',['id'=>'ovarian'])}}
                                                        <label for="ovarian">
                                                            Ovarian
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','uterine','',['id'=>'uterine'])}}
                                                        <label for="uterine">
                                                            Uterine
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','endometrial','',['id'=>'endometrial'])}}
                                                        <label for="endometrial">
                                                            Endometrial
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','tubal','',['id'=>'tubal'])}}
                                                        <label for="tubal">
                                                            Tubal
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','cervical','',['id'=>'cervical'])}}
                                                        <label for="cervical">
                                                            Cervical
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','male','',['id'=>'male'])}}
                                                        <label for="male">
                                                            Male
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','unexplained','',['id'=>'unexplained'])}}
                                                        <label for="unexplained">
                                                            UnExplained
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','coital','',['id'=>'coital'])}}
                                                        <label for="coital">
                                                            Coital
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('possible_case_of_infertility[infertility_type][]','other','',['id'=>'other'])}}
                                                        <label for="other">
                                                            Other
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        {{Form::textarea("possible_case_of_infertility[other]",'',['class'=>'form-control no-resize','placeholder'=>'Other','rows'=>'5'])}}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!--13. Treatment history  -->
                                <div class="{{'panel panel-primary'}}">
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
                                                <div class="col-md-9 complain-multi medicine-picker">
                                                    {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co','placeholder'=>'Enter Medicine name'])}}
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
                                
                                
                                {{Form::hidden('next_date','',['class'=>'next-date-value'])}}
                                {{Form::hidden('next_time','',['class'=>'next-time-value'])}}
                                <div class="col-sm-12">
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
                                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                    <a href="{{URL::to('ivf')}}" class="btn btn-default">Cancel</a>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('modal')
        <div class="modal fade" id="overy-data-popup" tabindex="-1" role="dialog">
            <div class="modal-dialog ovary-modal-dialog" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header justify-content-center">
                        {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> --}}
                        {{-- <h4 class="title" id="overy-popup-title"></h4> --}}
                    </div>
                    <!-- body -->
                    <div class="modal-body">
                        <table class="table m-b-0" id="ovary-table">
                            <tbody>
                                <tr>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="8"><span class="ovary-value-number ovary-number-8 odd-padding ovary-pre-selected-value">8</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="9"><span class="ovary-value-number ovary-number-9 odd-padding">9</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="10"><span class="ovary-value-number ovary-number-10 odd-padding">10</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="11"><span class="ovary-value-number ovary-number-11 odd-padding">11</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="12"><span class="ovary-value-number ovary-number-12 odd-padding">12</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="13"><span class="ovary-value-number ovary-number-13 odd-padding">13</span></td>
                                </tr>
                                <tr>
                                    <td class="ovary-value" data-type="" data-class="" data-value="13.5"><span class="ovary-value-number ovary-number-13-5 odd-padding">13.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="14"><span class="ovary-value-number ovary-number-14 odd-padding ovary-pre-selected-value">14</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="14.5"><span class="ovary-value-number ovary-number-14-5 odd-padding">14.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="15"><span class="ovary-value-number ovary-number-15 odd-padding ovary-pre-selected-value">15</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="15.5"><span class="ovary-value-number ovary-number-15-5 odd-padding">15.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="16"><span class="ovary-value-number ovary-number-16 odd-padding ovary-pre-selected-value">16</span></td>
                                </tr>
                                <tr>
                                    
                                    <td class="ovary-value" data-type="" data-class="" data-value="16.5"><span class="ovary-value-number ovary-number-16-5 odd-padding">16.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="17"><span class="ovary-value-number ovary-number-17 odd-padding ovary-pre-selected-value">17</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="17.5"><span class="ovary-value-number ovary-number-17-5 odd-padding">17.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="18"><span class="ovary-value-number ovary-number-13 odd-padding ovary-pre-selected-value">18</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="18.5"><span class="ovary-value-number ovary-number-18-5 odd-padding">18.5</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="19"><span class="ovary-value-number ovary-number-19 odd-padding">19</span></td>
                                </tr>
                                <tr>
                                    <td class="ovary-value" data-type="" data-class="" data-value="19.5"><span class="ovary-value-number ovary-number-19-5 odd-padding">19.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="20"><span class="ovary-value-number ovary-number-20 odd-padding ovary-pre-selected-value">20</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="20.5"><span class="ovary-value-number ovary-number-20-5 odd-padding">20.5</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="21"><span class="ovary-value-number ovary-number-21 odd-padding">21</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="21.5"><span class="ovary-value-number ovary-number-21-5 odd-padding">21.5</span></td>
                                    <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="22"><span class="ovary-value-number ovary-number-22 odd-padding ovary-pre-selected-value">22</span></td>
                                </tr>
                                <tr>
                                    <td class="ovary-value" data-type="" data-class="" data-value="22.5"><span class="ovary-value-number ovary-number-22-5 odd-padding">22.5</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="23"><span class="ovary-value-number ovary-number-23 odd-padding">23</span></td>
                                    <td class="ovary-value" data-type="" data-class="" data-value="24"><span class="ovary-value-number ovary-number-24 odd-padding">24</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                    </div>
                    <!-- footer -->
                    <div class="modal-footer next-appointment-footer">
                        {{-- <a href="#" class="btn btn-primary waves-effect save-btn disabled next-appointment-form">Save</a>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> --}}
                    </div>
                </div>
            </div>
        </div>
    @endsection
@stop
@section('page-script')
    <script src="{{asset('public/js/ivf.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script type="text/javascript">
        var durationData = @json($durationOfData);
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
        $('#treatment-medicine').select2();
        $('.duration-data').selectize({
            create: true,
            sortField: 'text'
        });
        $('.hystroscopy-images').imageUploader({
            imagesInputName: 'investigation[hystroscopy][images]',
        });
        $('.laproscopy-images').imageUploader({
            imagesInputName: 'investigation[laproscopy][images]',
        });
        $('.hcg-images').imageUploader({
            imagesInputName: 'investigation[hcg][images]',
        });
        $('.blood-images').imageUploader({
            imagesInputName: 'investigation[blood_report][image]',
        });
        $('.hsa-images').imageUploader({
            imagesInputName: 'investigation[hsa_report][images]',
        });
        $(document).ready(function(){
            setAppointmentDate();
            $('.complain-multi .show-tick').addClass('d-none');
            $('.duration-value .btn-group').addClass('d-none');
            $('.select2-search__field').css('width','280px');
            $(document).on('click','.next-appointment-form',function(e){
                e.preventDefault();
                var date = $('.next-date-value').val();
                var time = $('select.next-time').find("option:selected").text();
                var timeVal = $('.next-time-value').val(time);
                var ivf = $('#ivf-form').serialize();
                ivf = ivf + '&isprint=' + '1';
                ivfFormData(ivf);
            });

            $(document).on('click','.submit',function(e){
                e.preventDefault();
               
                var data = new FormData();
                var form_data =  $("#ivf-form").serializeArray();
                $.each(form_data, function (key, input) {
                    data.append(input.name, input.value);
                });
                if(this.value==1){
                    data.append('isprint', 1);
                }
                var file_data = $('input[name="investigation[hystroscopy][images][]"]')[0].files;
                for (var i = 0; i < file_data.length; i++) {
                    data.append("investigation[hystroscopy][images][]", file_data[i]);
                }
                var file_data = $('input[name="investigation[laproscopy][images][]"]')[0].files;
                for (var i = 0; i < file_data.length; i++) {
                    data.append("investigation[laproscopy][images][]", file_data[i]);
                }
                var file_data = $('input[name="investigation[blood_report][image][]"]')[0].files;
                for (var i = 0; i < file_data.length; i++) {
                    data.append("investigation[blood_report][image][]", file_data[i]);
                }
                var file_data = $('input[name="investigation[hsa_report][images][]"]')[0].files;
                for (var i = 0; i < file_data.length; i++) {
                    data.append("investigation[hsa_report][images][]", file_data[i]);
                }
                ivfFormData(data);
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
                    if(data.mobile_number != null){
                        $('.ref-mobile-number').val(data.mobile_number);
                    }else{
                        $('.ref-mobile-number').val('');
                    }
                }).fail(function() {

                });

            });
            $(document).on('change','select.ivf-plan',function(e){
                var plan = $(this).val();
                $('.div-pick-with-sd').addClass('d-none');
                if(plan == '1')
                {
                    $('.div-pick-with-sd').removeClass('d-none');
                }
            });
            $(document).on('keyup','.next-day',function(){
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                if (day) {
                    var time = $(this).find("option:selected").text();
                    getNextAppointmentDate(selectedAppointmentId,day,null,time);
                }
            });

            $(document).on('change','.next-date',function(e){
                var dateValue = $(this).val();
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                var time = '';
                if(dateValue){
                    var time = $(this).find("option:selected").text();
                }
                getNextAppointmentDate(selectedAppointmentId,day,dateValue,time);
            });


            $(document).on('change','select.next-time',function(){
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                var time = $(this).find("option:selected").text();
                var date = $('.next-date').val();
                if (day) {
                    getNextAppointmentDate(selectedAppointmentId,day,date,time);
                }
            });
            
        });


        function setAppointmentDate(){
            var value = new Date($('.lmd-date').val());
            var diffDay = (new Date() - value) / 1000 / 60 / 60 / 24;
            var dateValue = diffDay.toString().split('.');
            diffDay = dateValue[0];
            if (diffDay >= 0 && diffDay != "-0") {
                diffDay = parseInt(diffDay) + 1;
            } else {
                diffDay = parseInt(diffDay) - 2;
            }
            diffDay = diffDay == -0 ? 0 : 3;
            $('.lmd-date-diff').removeClass('d-none');
            $('.lmd-date-diff').text(diffDay + ' Day');
            $('.lmd-date-diff-val').val(diffDay);
            if(diffDay == 2 || diffDay == 3 || diffDay == 4){
                $('.afcs-details').removeClass('d-none');
            }
            if (value == 'Invalid Date') {
                $('.lmd-date-diff').addClass('d-none');
                $('.lmd-date-diff').text('');
                $('.afcs-details').addClass('d-none');
            }
        }

        function ivfFormData(data){
            var valid = 1;
            $('.seen-by-error').text('');
            $('.ho-data-msg').text('');
            $('.co-value-msg').text('');
            $('.weight').text('');
            // $('.ho-tab').removeClass('show');
            $('.p-info').removeClass('show');
            var weight=document.getElementById('weight').value;
            if(weight == ''){
                // document.getElementById('error_weight').innerHTML="The weight is required";
                $('.weight').text('The weight is required');
                valid = 0;
                $('.p-info').addClass('show');
            }
            if(valid == 0){
                $('html, body').animate({
                    scrollTop: ($('.weight').offset().top - 150)
                }, 1000);
                return true;
            }
            if($('select.seen-by').val() == ''){
                $('.seen-by-error').text('Please select doctor');
                $('html, body').animate({
                    scrollTop: ($('.seen-by').offset().top - 150)
                }, 1000);
                return true;
            }
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'{{URL::to("ivf")}}',
                    type:'POST',
                    enctype: 'multipart/form-data',
                    dataType:'json',
                    data:data,
                    cache: false,
                    contentType: false,
                    processData: false,
            }).done(function(data){
                if(data.status == 'true'){
                    var url = "{{URL::to('ivf')}}";
                    window.location.href = url;
                }else if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    $('#anc_id').val(data.id);
                }
                else{
                    location.reload();
                }
            });
        }

        function getNextAppointmentDate(appointmentId,day,date,time){
            var token = "{{csrf_token()}}";
            $.ajax({
                url: "{{URL::to('next-appointment')}}",
                dataType: 'json',
                type: 'post',
                data:{appointmentId:appointmentId,day:day,_token:token,date:date,time:time}
            }).done(function(data) {
                if(data.status == null){
                    $('.next-date').val(data.date);
                    $('.next-day').val(data.diff);
                    $('.next-time').val(data.time);
                    $('.next-time').selectpicker('refresh');
                    $('.next-date-value').val(data.date);
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
        $(document).on('change','select.category_data',function(){
            $('.category-iui').addClass('d-none')
            $('.category-ivf').removeClass('d-none')
            if($(this).val() == 3 || $(this).val() == 4)
            {
                $('.category-iui').removeClass('d-none')
                $('.category-ivf').addClass('d-none')
            }
        })
        var medicinesValue = @json($medicines);
    </script>
@stop
