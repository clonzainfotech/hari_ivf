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

    <div class="row clearfix appointment">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Edit Appointment</strong>
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
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open([
                                'url'=>'appointment/'.encrypt($appointment->id),
                                'method'=>'put',
                                'class'=>'form appointment-form',
                                'files'=>'true'
                            ])}}
                                <!-- patients basic information -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                                aria-controls="patients"> Patients Basic Information</a> </h4>
                                    </div>
                                    <div id="patients" class="panel-collapse collapse show patient-details" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row clearfix">

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn ">Name : &nbsp;</span>
                                                        {{Form::text('name',$appointment->getPatientsDetails['name'],[
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
                                                        {{Form::text('code',$appointment->getPatientsDetails['code'],[
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
                                                        {{Form::text('age',$appointment->getPatientsDetails['age'],['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength' => 4])}}
                                                    </div>
                                                    <span class="form-error-msg age-error"></span>
                                                </div>
                                                <div class="col-md-2 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                        {{Form::text('months',$appointment->getPatientsDetails['months'],['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                        {{Form::text('days',$appointment->getPatientsDetails['days'],['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3])}}
                                                    </div>
                                                </div>
                                                @if ($appointment->category_id != 7)
                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="form-group">
                                                            {{Form::select('category',$category,$appointment->category_id,[
                                                                'class'=>'form-control select-padding-0 category_data',
                                                                'placeholder'=>'Select Category',
                                                                'required',
                                                                ($appointment->category_id == 7) ? 'disabled' : ''
                                                            ])}}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('category')}}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if ($appointment->category_id == 7)
                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="form-group">
                                                            {{ 'Category : ' . $appointment->categoryDetails['name'] }}
                                                        </div>
                                                        <span class="form-error-msg">
                                                            {{$errors->first('category')}}
                                                        </span>
                                                    </div>
                                                    {{ Form::hidden('category', $appointment->category_id, [
                                                        'id' => 'category'
                                                    ]) }}
                                                @endif
                                                
                                             </div>
                                             <div class="row clearfix">
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Apt. Date : &nbsp;</span>
                                                        {{Form::text('date',\Carbon\Carbon::parse($appointment->date)->format('D d M Y'),['class'=>'form-control datetimepicker date','placeholder'=>'Date','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('date')}}
                                                    </span>
                                                </div>
                                                <div class=" col-md-4 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Apt. Time : &nbsp;</span>
                                                        {{Form::text('time',$appointment->time ? \Carbon\Carbon::parse($appointment->time)->format('h:i a') : '',['class'=>'form-control timepicker time','placeholder'=>'Time'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('time')}}
                                                    </span>
                                                </div>
                                                <div class=" col-md-4 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Arrival Time : &nbsp;</span>
                                                        {{-- <span class="input-group-addon">
                                                            <i class="zmdi zmdi-time"></i>
                                                        </span> --}}
                                                        {{Form::text('arrival_time',(!empty($appointment->arrival_time)) ? \Carbon\Carbon::parse($appointment->arrival_time)->format('h:i a') : null,['class'=>'form-control timepicker arrival_time','placeholder'=>'Arrival Time'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('arrival_time')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class=" col-md-4 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Mobile : &nbsp;</span>
                                                        {{Form::text('mobile_number',$appointment->getPatientsDetails['mobile_number'],[
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
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Other Mobile : &nbsp;</span>
                                                        {{Form::text('other_mobile_number',$appointment->getPatientsDetails['other_mobile_number'],[
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


                                                <div class=" col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::select('gender',[1=>'Male',2=>'Female'],$appointment->getPatientsDetails['gender'],['class'=>'form-control select-padding-0','id'=>'gender','placeholder'=>'Select Gender'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('gender')}}
                                                    </span>
                                                </div>
                                                </div>
                                                <div class="row clearfix">
                                                <div class="col-md-6"></div>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-1 unik-lbl-spn">
                                                            <label>Remark :</label>        
                                                        </div>
                                                        <div class="col-sm-11">
                                                            {{Form::textarea('remark',$appointment->remark,[
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
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#doctor" aria-expanded="false"
                                                aria-controls="doctor"> Doctor</a> </h4>
                                    </div>
                                    <div id="doctor" class="panel-collapse collapse show doctor-details" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::select('reference_doctor',$referenceDoctor,$appointment->getPatientsDetails['reference_doctor_id'],['class'=>'form-control select-padding-0 reference_doctor','placeholder'=>'Select Reference Doctor','data-live-search'=>'true'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('reference_doctor')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-6 col-sm-12 doctor-name d-none">
                                                    <div class="form-group">
                                                        {{Form::text('doctor_name','',[
                                                            'class'=>'form-control doctor',
                                                            'placeholder'=>'Doctor Name'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('doctor_name')}}
                                                    </span>
                                                </div>
                                                <div class=" col-md-6 col-sm-12 doctor-mobile-number d-none">
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
                                                <div class=" col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::select('hospital_doctor',$hospitalDoctor,$appointment->getPatientsDetails['hospital_doctor_id'],['class'=>'form-control select-padding-0 hospital_doctor','placeholder'=>'Select Hospital Doctor','data-live-search'=>'true'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('hospital_doctor')}}
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        {{Form::select('pro_reference_doctor',$proReferenceDoctor,$appointment->getPatientsDetails['reference_doctor_pro_id'],['class'=>'form-control select-padding-0 pro-ref-hospital-doctor','placeholder'=>'Select Pro Reference Doctor','data-live-search'=>'true'])}}
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
                                            </div>
                                            <div class="row">
                                                <div class=" col-md-6 col-sm-6 ref-pro-doctor-data d-none">
                                                    <div class="form-group">
                                                        {{Form::text('pro_reference_doctor_mobile_number','',[
                                                            'class'=>'form-control checkvalue',
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
                                        </div>
                                    </div>
                                </div>
                                <!-- location and communication -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseThree_1" aria-expanded="false"
                                                aria-controls="collapseThree_1"> Location</a> </h4>
                                    </div>
                                    <div id="collapseThree_1" class="panel-collapse collapse show location-details" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div>
                                                <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Residence : &nbsp;</span>
                                                        {{Form::text('residence',$appointment->getPatientsDetails['residence'],[
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
                                                        {{Form::text('main_area',$appointment->getPatientsDetails['main_area'],[
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
                                                        {{Form::select('city_1',$city,$appointment->getPatientsDetails['city'],['class'=>'form-control select-padding-0 city-name','placeholder'=>'City','data-live-search'=>'true','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('city_1')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-4 city-text d-none">
                                                    <div class="form-group">
                                                        {{Form::text('city_2',$appointment->getPatientsDetails['city'],['class'=>'form-control','placeholder'=>'City'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('city_2')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::select('state',$state,$appointment->getPatientsDetails['state'],['class'=>'form-control select-padding-0 state','placeholder'=>'State','data-live-search'=>'true'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('state')}}
                                                    </span>
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
                                    <div id="other" class="" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Weight : &nbsp;</span>
                                                        {{Form::text('weight',$appointment->getPatientsDetails['weight'],[
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
                                                        {{Form::text('occupation',$appointment->getPatientsDetails['occupation'],[
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
                                                        {{Form::select('pregnant',[1=>'Yes',2=>'No'],$appointment->getPatientsDetails['is_pregnant'],['class'=>'form-control pregnant select-padding-0','placeholder'=>'Pregnant'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('pregnant')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Height : &nbsp;</span>
                                                        {{Form::text('height',$appointment->getPatientsDetails['height'],[
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
                                                        {{Form::text('dob',$appointment->getPatientsDetails['dob'] ? \Carbon\Carbon::parse($appointment->getPatientsDetails['dob'])->format('d-m-Y') : null,[
                                                            'id' =>'birthdate',
                                                            'class'=>'dob border-color border-1',
                                                            'placeholder'=>'BirthDate',
                                                        ])}}
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
                                    <a href="{{URL::to('appointment')}}" class="btn btn-default ">Cancel</a>
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
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script type="text/javascript">
        var code = '';
        $(function () {
        //Datetimepicker plugin
            $("#birthdate").datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                // minDate:new Date(),
                time:false,
                weekStart: 1
            });

            $('.timepicker').bootstrapMaterialDatePicker({
                date: false,
                shortTime: true,
                format: 'hh:mm a',
                switchOnClick: true,
                clearButton: true,
            });

            $('select[name="reference_doctor"]').on('change', function() {
                referenceDoctorField($(this).val());
            });
        });

        $(document).ready(function(){
            proReferenceDoctor($('select.pro-ref-hospital-doctor').val());
            referenceDoctorField($('select[name="reference_doctor"]').val());
            $(".appointment-form").submit(function() { $(".appointment-save").attr("disabled", true); });
            $('.appointment-save').click(function(){
                $('.patient-details').removeClass('show');
                $('.doctor-details').removeClass('show');
                $('.location-details').removeClass('show');
                // var age = $('.age').val();
                var category = $('select.category_data').val();
                var refDoc = $('select.reference_doctor').val();
                var hospitalDoctor = $('select.hospital_doctor').val();
                var mobileNumber = $('.mobile_number').val();
                var residence = $('.residence').val();
                var mainArea = $('.main_area').val();
                var cityName = $('select.city-name').val();
                var state = $('select.state').val();
                var years = $('.years').val();
                var months = $('.months').val();
                var days = $('.days').val();
                $('.age-error').text('');
                if(years == '' && months == '' && days == ''){
                    $('.age-error').text('Please enter age');
                    $('.patient-details').addClass('show');
                    return false;
                }
                if(category == '' || mobileNumber == ''){
                    $('.patient-details').addClass('show');
                }
                if(refDoc == '' || hospitalDoctor == ''){
                    $('.doctor-details').addClass('show');
                }
                if(residence == '' || mainArea == '' || cityName == '' || state == ''){
                    $('.location-details').addClass('show');
                }
            });
        });

        $(document).on('change','select.city-name',function(){
            var value = $(this).val();
            if(value == 'Other'){
                $('.city-text').removeClass('d-none');
            }else{
                $('.city-text').addClass('d-none');
            }
        });

        $(document).on('keyup','.valid-age',function(){
            var value = $(this).val();
            $(this).val(validMobileNumber(value));
        });

        $(document).on('change','select.pro-ref-hospital-doctor',function(){
            var value = $(this).val();
            proReferenceDoctor(value);
        });

        function proReferenceDoctor(value){
            $('.ref-pro-doctor-data').addClass('d-none');
            if(value == 'other'){
                $('.ref-pro-doctor-data').removeClass('d-none');
            }
        }

        function appointmentMobileNumber(value) {
            var mobileNumber = value;
            $('.mobile_number').val(validMobileNumber(value));
        }
        function otherMobileNumber(value) {
            var mobileNumber = value;
            $('.other_mobile_number').val(validMobileNumber(value));
        }
        function doctorMobileNumber(value) {
            var mobileNumber = value;
            $('input[type="text"][name="doctor_mobile_number"]').val(validMobileNumber(value));
        }
        function otherProMobileNumber(value) {
            // var mobileNumber = value;
            $('.checkvalue').val(validMobileNumber(value));
        }
        function validMobileNumber(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
        }
        function referenceDoctorField(value){
            if (value == 'other') {
                $('input[type="text"][name="doctor_name"]').prop('required', 'required');
                $('input[type="text"][name="doctor_mobile_number"]').prop('required', 'required');
                $('.doctor-name').removeClass('d-none');
                $('.doctor-mobile-number').removeClass('d-none');
            } else {
                $('input[type="text"][name="doctor_name"]').prop('required', false);
                $('input[type="text"][name="doctor_mobile_number"]').prop('required', false);
                $('.doctor-name').addClass('d-none');
                $('.doctor-mobile-number').addClass('d-none');
            }
        }
        function validValue(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
        }
        function checkAge(value) {
            $('.age').val(validValue(value));
        }
        function checkWeight(value) {
            $('.weight').val(validValue(value));
        }
    </script>
@stop
