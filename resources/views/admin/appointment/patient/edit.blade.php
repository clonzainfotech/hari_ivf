@extends('layouts.main')
@section('parentPageTitle', 'Appointment')
@section('title', 'Update Appointment')

@section('page-style')
<style>
        .ui-widget-content
        {
            background: white !important;
        }
        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #1e5f63 !important;
            background: #1e5f63 !important;
            color: white !important;
        }
        .ui-datepicker .ui-datepicker-calendar .ui-state-highlight a {
            background: #1e5f63 !important;
        }
        a.ui-state-default:hover, a.ui-datepicker-prev.ui-corner-all:hover ,a.ui-datepicker-next.ui-corner-all:hover {
            background: #1e5f63 !important;
        }
        
    </style>
@stop
@section('content')
    {{-- @php
        $file = url('public/images/default_user.png');
        if (file_exists($patient->image))
        {
            $file = url($patient->image);
        }
        if(is_null($patient->image)){
        $file = url('public/images/default_user.png');
        }
    @endphp --}}
    <div class="row clearfix appointment">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Edit Patient</strong>
                    </h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('patient')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open([
                                'url'=>!empty($patient) ? 'update-patient/'.encrypt($patient->id) : 'update-patient/',
                                'method'=>'put',
                                'class'=>'form patient-form',
                                'files'=>'true'
                            ])}}
                            {{Form::hidden('self_bookingId',!empty($self_bookingId) ? encrypt($self_bookingId) : null,[])}}
                                <!-- patients basic information -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                                aria-controls="patients"> Patients Basic Information</a> </h4>
                                    </div>
                                    <div id="patients" class="panel-collapse patient-details collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn ">Name : &nbsp;</span>
                                                        {{Form::text('name',!empty($patient->name) ? $patient->name : null,[
                                                            'class'=>'form-control name',
                                                            'placeholder'=>'Name',
                                                            'required'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('name')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-md-3 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn ">Code : &nbsp;</span>
                                                        {{Form::text('code',!empty($patient->code) ? $patient->code : null,[
                                                            'class'=>'form-control code',
                                                            'placeholder'=>'Code',
                                                            'disabled'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('code')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-2 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                        {{Form::text('age',!empty($patient['age']) ? $patient['age'] : (!empty($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->age : null),['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength' => 4])}}
                                                    </div>
                                                    <span class="form-error-msg age-error"></span>
                                                </div>
                                                <div class="col-md-2 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                        {{Form::text('months',!empty($patient['months']) ? $patient['months'] : null,['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                        {{Form::text('days',!empty($patient['days']) ? $patient['days'] : null,['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3])}}
                                                    </div>
                                                </div>
                                                <div class=" col-md-3 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::select('gender',[1=>'Male',2=>'Female'],!empty($patient->gender) ? $patient->gender : null,['class'=>'form-control select-padding-0','id'=>'gender','placeholder'=>'Select Gender'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('gender')}}
                                                    </span>
                                                </div>
                                             </div>
                                            <div class="row clearfix">
                                                <div class=" col-md-6 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Mobile : &nbsp;</span>
                                                        {{Form::text('mobile_number',!empty($patient->mobile_number) ? $patient->mobile_number : null,[
                                                            'class'=>'form-control mobile_number',
                                                            'placeholder'=>'Mobile Number',
                                                            'maxlength' => 10,
                                                            'required',
                                                            'oninput' => 'appointmentMobileNumber(this.value)'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('mobile_number')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Other Mobile : &nbsp;</span>
                                                        {{Form::text('other_mobile_number',!empty($patient->other_mobile_number) ? $patient->other_mobile_number : null,[
                                                            'class'=>'form-control other_mobile_number',
                                                            'placeholder'=>'Other Mobile Number',
                                                            'maxlength' => 10,
                                                            'oninput' => 'otherMobileNumber(this.value)'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('other_mobile_number')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- location and communication -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseThree_1" aria-expanded="false"
                                                aria-controls="collapseThree_1"> Location</a> </h4>
                                    </div>
                                    <div id="collapseThree_1" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon unik-lbl-spn">Residence : &nbsp;</span>
                                                            {{Form::text('residence',!empty($patient->residence) ? $patient->residence : null,[
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Residence'
                                                            ])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('residence')}}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon unik-lbl-spn">Main Area : &nbsp;</span>
                                                            {{Form::text('main_area',!empty($patient->main_area) ? $patient->main_area : null,[
                                                                'class'=>'form-control',
                                                                'placeholder'=>'Main Area'
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
                                                            {{Form::select('city_1',$city,!empty($patient->city) ? $patient->city : null,['class'=>'form-control select-padding-0 city-name','placeholder'=>'City','data-live-search'=>'true','required'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('city_1')}}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 city-text d-none">
                                                        <div class="form-group">
                                                            {{Form::text('city_2','',['class'=>'form-control','placeholder'=>'City'])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('city_2')}}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="form-group">
                                                            {{Form::select('state',$state,!empty($patient->state) ? $patient->state : null,['class'=>'form-control select-padding-0 state','placeholder'=>'State','data-live-search'=>'true'])}}
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
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#other" aria-expanded="false"
                                                aria-controls="other"> Other</a> </h4>
                                    </div>
                                    <div id="other" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Weight : &nbsp;</span>
                                                        {{Form::text('weight',!empty($patient->weight) ? $patient->weight : null,[
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
                                                        {{Form::text('occupation',!empty($patient->occupation) ? $patient->occupation : null,[
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
                                                        {{Form::select('pregnant',[1=>'Yes',2=>'No'],!empty($patient->is_pregnant) ? $patient->is_pregnant : null,['class'=>'form-control pregnant select-padding-0','placeholder'=>'Pregnant'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('pregnant')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Height : &nbsp;</span>
                                                        {{Form::text('height',!empty($patient->height) ? $patient->height : null,[
                                                            'class'=>'form-control height',
                                                            'placeholder'=>'height'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('height')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Birth Date : &nbsp;</span>
                                                        {{Form::text('dob',!empty($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->format('d-m-Y') : null,[
                                                        'id'=>'birthdate',
                                                            'class'=>'dob border-color border-1',
                                                            'placeholder'=>'BirthDate',
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                {{$errors->first('dob')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="input-group">
                                                            <span class="input-group-addon unik-lbl-spn">Email : &nbsp;</span>
                                                            {{Form::email('email',!empty($patient->email) ? $patient->email : null,[
                                                                'class'=>'form-control email',
                                                                'placeholder'=>'Please enter Email address',
                                                            ])}}
                                                    </div>
                                                </div>
                                            </div>
                                           <!--  <div class="row">
                                                <div class="col-md-4">
                                                    @if (!empty($patient->image))
                                                        <img src="{{ $file}}" class="mt-2 mb-2 profile_icon"/>
                                                    @endif
                                                    <div class="form-group">
                                                        {{Form::file('image',['class'=>'form-control','placeholder'=>'Select Profile Picture'])}}
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    {{Form::submit('Save',['class'=>'btn btn-primary patient-save'])}}
                                    <a href="{{URL::to('patient')}}" class="btn btn-default ">Cancel</a>
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
<script type="text/javascript">
    $("#birthdate").datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
     $('.datetimepicker').bootstrapMaterialDatePicker({
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
    $(document).ready(function(){
        $(".patient-form").submit(function() { 
            var years = $('.years').val();
            var months = $('.months').val();
            var days = $('.days').val();
            $('.age-error').text('');
            if(years == '' && months == '' && days == ''){
                $('.age-error').text('Please enter age');
                $('.patient-details').addClass('show');
                return false;
            }
            $(".patient-save").attr("disabled", true); 
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
    });
</script>
@stop
