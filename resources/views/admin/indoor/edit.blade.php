@extends('layouts.main')
{{--@section('parentPageTitle', 'Indoor')--}}
@section('title', 'Room Registration')

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <style>
        .bootstrap-select.btn-group.disabled, .bootstrap-select.btn-group > .disabled {
            color: black !important;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix indoor-edit indoor">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Room Registration Form</strong>
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
                        {{Form::open(['url'=>'indoor/update/'.$bookingdata->id,'method'=>'post','class'=>'form appointment-form patient-form','files'=>'true'])}}

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
                            {{ Form::hidden('type_id', !(empty($typeId)) ? encrypt($typeId) : null, [
                                'id' => 'type_id'
                            ]) }}
                            {{ Form::hidden('booking_id', !(empty($bookingdata->id)) ? $bookingdata->id : null, [
                                'id' => 'booking_id'
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
                                <div id="patients"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">

                                        <div class="row clearfix">
                                            <div class="col-md-12">
                                                {{ Form::select('name',$patients,$appointment->getPatientsDetails['id'],[
                                                    'class'=>'form-control select-padding-0 patient-id name',
                                                    'placeholder'=>'Select Patient',
                                                    'id' => 'patient_id',
                                                    'data-live-search' => 'true',
                                                    'disabled'
                                                ])}}
                                                {{-- <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Name : &nbsp;</span>
                                                    {{Form::text('name',$appointment->getPatientsDetails['name'], [
                                                        'class'=>'form-control name',
                                                        'placeholder'=>'Name'
                                                    ])}}
                                                </div> --}}
                                                <span class="form-error-msg">
                                                {{$errors->first('name')}}
                                            </span>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-3 col-sm-12">
                                                {{Form::select('code',$patientcode,$appointment->getPatientsDetails['code'],['class'=>'form-control select-padding-0 patient-code code','title'=>'Code','id'=>'patientcode','data-live-search'=>'true','disabled'])}}
                                                {{-- <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Code : &nbsp;</span>
                                                    {{Form::text('code',$appointment->getPatientsDetails['code'], [
                                                        'class'=>'form-control code',
                                                        'placeholder'=>'code',
                                                        'readonly'
                                                    ])}}
                                                </div> --}}
                                                <span class="form-error-msg">
                                                {{$errors->first('patient')}}
                                            </span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('age',!empty($appointment->getPatientsDetails['age']) ? $appointment->getPatientsDetails['age'] : null,['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength' => 4,'disabled'])}}
                                                </div>
                                                <span class="form-error-msg age-error"></span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('months',!empty($appointment->getPatientsDetails['months']) ? $appointment->getPatientsDetails['months'] : null,['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('days',!empty($appointment->getPatientsDetails['days']) ? $appointment->getPatientsDetails['days'] : null,['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {{Form::select('category',$category,$appointment->category_id,['class'=>'form-control select-padding-0 category_data','placeholder'=>'Select Category','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('category')}}
                                            </span>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Mobile : &nbsp;</span>
                                                    {{Form::text('mobile_number',$appointment->getPatientsDetails['mobile_number'],[
                                                        'class'=>'form-control mobile_number',
                                                        'placeholder'=>'Mobile Number',
                                                        'maxlength' => 10,
                                                        'oninput' => 'appointmentMobileNumber(this.value)',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('mobile_number')}}
                                            </span>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Other Mobile : &nbsp;</span>
                                                    {{Form::text('other_mobile_number',$appointment->getPatientsDetails['other_mobile_number'],[
                                                        'class'=>'form-control other_mobile_number',
                                                        'placeholder'=>'Other Mobile Number',
                                                        'maxlength' => 10,
                                                        'oninput' => 'otherMobileNumber(this.value)',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$otherMobileNumberError}}
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {{Form::select('gender',['2'=>'Female','1'=>'Male'], $appointment->getPatientsDetails['gender'],['class'=>'form-control select-padding-0','id'=>'gender','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('gender')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{Form::select('reference_doctor',$referenceDoctor,$appointment->getPatientsDetails['reference_doctor_id'],[
                                                        'class'=>'form-control select-padding-0 reference_doctor',
                                                        'placeholder'=>'Select Reference Doctor',
                                                        'data-live-search'=>'true',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$referenceDoctorError}}
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-sm-6 doctor-name d-none">
                                                <div class="form-group">
                                                    {{Form::text('doctor_name',null, [
                                                        'class'=>'form-control doctor',
                                                        'placeholder'=>'Doctor Name',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('doctor_name')}}
                                                </span>
                                            </div>
                                            <div class=" col-md-6col-sm-6 doctor-mobile-number d-none">
                                                <div class="form-group">
                                                    {{Form::text('doctor_mobile_number','',[
                                                        'class'=>'form-control doctor',
                                                        'placeholder'=>'Doctor Mobile Number',
                                                        'oninput' => 'doctorMobileNumber(this.value)',
                                                        'maxlength' => 10,
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('doctor_mobile_number')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{Form::select('hospital_doctor', $hospitalDoctor,$appointment->getPatientsDetails['hospital_doctor_id'],['class'=>'form-control select-padding-0 hospital_doctor','placeholder'=>'Select Hospital Doctor','data-live-search'=>'true','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$hospitalDoctorError }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Residence : &nbsp;</span>
                                                    {{Form::text('residence',$appointment->getPatientsDetails['residence'],[
                                                        'class'=>'form-control residence',
                                                        'placeholder'=>'Residence',
                                                        'disabled'
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
                                                        'class'=>'form-control main_area',
                                                        'placeholder'=>'Main Area',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('main_area')}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{Form::select('city_1',$city,$appointment->getPatientsDetails['city'],['class'=>'form-control select-padding-0 city-name','placeholder'=>'City','data-live-search'=>'true','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('city_1')}}
                                            </span>
                                            </div>
                                            <div class="col-md-4 col-sm-4 city-text d-none">
                                                <div class="form-group">
                                                    {{Form::text('city_2',$appointment->getPatientsDetails['city'],['class'=>'form-control city city-2','placeholder'=>'City','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('city_2')}}
                                            </span>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{Form::select('state',$state,$appointment->getPatientsDetails['state'], ['class'=>'form-control select-padding-0 state','placeholder'=>'State','data-live-search'=>'true','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('state')}}
                                            </span>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-4 col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Weight : &nbsp;</span>
                                                    {{Form::text('weight',$appointment->getPatientsDetails['weight'],[
                                                        'class'=>'form-control weight',
                                                        'placeholder'=>'Please enter weight in KG',
                                                        'maxlength' => 3,
                                                        'oninput' => 'checkWeight(this.value)',
                                                        'disabled'
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
                                                        'placeholder'=>'Occupation',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('occupation')}}
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{Form::select('pregnant',[1=>'Yes',2=>'No'],$appointment->getPatientsDetails['is_pregnant'],['class'=>'form-control pregnant select-padding-0','placeholder'=>'Pregnant','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('pregnant')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading"
                                     role="tab"
                                     id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#remark" aria-expanded="true"
                                                                aria-controls="patients">Patients Important Note</a> </h4>
                                </div>
                                <div id="remark"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row clearfix">
                                                @if(count($appointmentRemark) > 0)
                                                    @foreach($appointmentRemark as $key => $value)
                                                        <div class="col-md-6">
                                                            <div class="remark-details mb-2">
                                                                <div class="font-bold">{{$key}} :</div>
                                                                <div>{{$value}}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-md-12 text-center">No Important Notes</div>
                                                @endif
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
                                                                aria-controls="doctor"> Room Detail</a> </h4>
                                </div>
                                <div id="doctor"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix">
                                            <div class="col-md-6">
                                                <div class="form-group indoor-select-room-type">
                                                    {{Form::select('room_type', $roomTypes, (!empty($bookingdata['type_id'])) ? $bookingdata['type_id'] : null, [
                                                        'class'=>'form-control select-padding-0 room-type',
                                                        'placeholder'=>'Select Room Type',
                                                        'id'=>'room_type',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('room_type')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::select('room', $indoorRoom,$bookingdata->room_id, [
                                                        'class'=>'form-control select-padding-0 room dynamic',
                                                        'placeholder'=>'Select Room Number',
                                                        'id'=>'room',
                                                        'data-dependent'=>'bed',
                                                        'required'
                                                    ])}}
                                                </div>

                                                <span class="form-error-msg">
                                                {{$errors->first('room')}}
                                                </span>
                                            </div>
                                            {{-- <div class="col-md-4 indoor-beds">
                                                <div class="form-group indoor-select-bed">
                                                    {{Form::select('room_bed', $indoorBed, $bookingdata->bed_id, [
                                                        'class'=>'form-control select-padding-0 room-bed',
                                                        'placeholder'=>'Select Bed Number',
                                                        'id'=>'room_bed',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('room_bed')}}
                                                </span>
                                            </div> --}}
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-3 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">DOA Date : &nbsp;</span>
                                                    {{Form::text('date',\Carbon\Carbon::Parse($bookingdata->doa_date)->format('D d M Y'),[
                                                        'class'=>'form-control datetimepicker date',
                                                        'placeholder'=>'Date',
                                                        'required'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('date')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">DOD date : &nbsp;</span>
                                                    {{ Form::text('doddate', (!empty($bookingdata->dod_date)) ? \Carbon\Carbon::Parse($bookingdata->dod_date)->format('D d M Y') : null, [
                                                        'class'=>'form-control datetimepicker date',
                                                        'placeholder'=>'Date',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('time')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Admission :</span>
                                                    {{ Form::text('admissiontime',(!empty($bookingdata->admit_time)) ? \Carbon\Carbon::Parse($bookingdata->admit_time)->format('h:i a') : null, [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Discharge :</span>
                                                    {{ Form::text('dischargetime',(!empty($bookingdata->discharge_time)) ? \Carbon\Carbon::Parse($bookingdata->discharge_time)->format('h:i a') : null, [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
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
                                    <h4 class="panel-title">
                                        <a class="collapsed"
                                            role="button"
                                            data-toggle="collapse"
                                            data-parent="#accordion_1"
                                            href="#collapseThree_1"
                                            aria-expanded="false"
                                            aria-controls="collapseThree_1">
                                            Procedure / Surgery
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_1"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix">
                                            <div class="col-md-6 complain-mulit mb-3">
                                                {{Form::select('pro[pro_type][]',$procedures,!empty($procedureData) ? $procedureData : null,[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=>'Enter Procedure/Surgery',
                                                    'multiple'=>true
                                                ])}}
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox">
                                                    {{Form::checkbox('is_pediatric_patient',$bookingdata->is_pediatric_patient,$bookingdata->is_pediatric_patient == 1 ? true : false,['class'=>'is_pediatric_patient','id'=>'is_pediatric_patient'])}}
                                                    <label for="is_pediatric_patient">
                                                        Pediatric Patient
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox">
                                                    {{Form::checkbox('is_medicare_patient',$bookingdata->is_medicare_patient,$bookingdata->is_medicare_patient == 1 ? true : false,['class'=>'is_medicare_patient','id'=>'is_medicare_patient'])}}
                                                    <label for="is_medicare_patient">
                                                        Medicare Patient
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-sm-1 unik-lbl-spn">
                                                    <label>Remark :</label>        
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        {{Form::textarea('remark', (!empty($bookingdata->remark)) ? $bookingdata->remark : null, [
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
                            <div class="col-sm-12">
                                @if($bookingdata->is_final_invoice == 0)
                                {{Form::submit('Save',['class'=>'btn btn-primary patient-save'])}}
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
@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>
        $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
        $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
    </script>
    <script type="text/javascript">
        var code = '';

        $(function() {
            //Datetimepicker plugin
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

            // reference doctor
            $('select[name="reference_doctor"]').on('change', function() {
                referenceDoctorField($(this).val());
            });

            $('select[name="room_type"]').on('change', function() {
                var typeId = $(this).val();
                $.ajax({
                    url: "{{URL::to('get-indoor-rooms')}}",
                    type: 'GET',
                    data: {
                        type_id: typeId,
                    },
                }).done(function(data) {
                    if (data.rooms) {
                        $('#room').empty();
                        $.each(data.rooms, function(key, value){
                            $('#room').append('<option value="' + key + '">' + value + '</option>');

                        });
                    }
                    $('#room').selectpicker('refresh');

                    if (data.beds) {
                        $('#room_bed').empty();
                        $.each(data.beds, function(key, value){
                            $('#room_bed').append('<option value="' + key + '">' + value + '</option>');

                        });
                    }
                    $('#room_bed').selectpicker('refresh');
                }).fail(function(error) {

                });
            });
            // getIndoorBed(typeId, roomId);

            $('select[name="room"]').on('change', function() {
                var typeId = $('#room_type').val();

                var roomId = $('#room').val();
                $.ajax({
                    url: "{{URL::to('get-indoor-beds')}}",
                    type: 'GET',
                    data: {
                        type_id: typeId,
                        room_id: roomId,
                    },
                }).done(function(data) {
                    $('#room_bed').empty();

                    if (data) {
                        $.each(data, function(key, value){
                            $('#room_bed').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                    $('#room_bed').selectpicker('refresh');
                }).fail(function(error) {

                });
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

        $(document).ready(function(){
            $(".patient-form").submit(function() { $(".patient-save").attr("disabled", true); });
            referenceDoctorField($('select[name="reference_doctor"]').val());
        });


        $('.code').blur(function(e) {
            code = $(this).val();
            getPatientsDetails(code);
        });


        $('.code').keypress(function(e) {
            code = $(this).val();
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                getPatientsDetails(code);
                e.preventDefault();
            }
        });
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
        function appointmentMobileNumber(value) {
            // var mobileNumber = value;
            $('.mobile_number').val(validMobileNumber(value));
        }
        function otherMobileNumber(value) {
            // var mobileNumber = value;
            $('.other_mobile_number').val(validMobileNumber(value));
        }
        function doctorMobileNumber(value) {
            // var mobileNumber = value;
            $('input[type="text"][name="doctor_mobile_number"]').val(validMobileNumber(value));
        }
        function validMobileNumber(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
        }
        function checkAge(value) {
            $('.age').val(validMobileNumber(value));
        }
        function checkWeight(value) {
            $('.weight').val(validMobileNumber(value));
        }
        $(document).on('click','.is_pediatric_patient,.is_medicare_patient',function(){
            $(this).val(0);
            if($(this).prop('checked') == true)
            {
                $(this).val(1);
            }
        });
    </script>
@stop
