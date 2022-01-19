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
    <div class="row clearfix indoor indoor-create">
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
                        {{Form::open(['url'=>'indoor/'.$roomTypeId,'method'=>'post','class'=>'form appointment-form patient-form','files'=>'true'])}}
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
                            {{ Form::hidden('patient_id', !empty(Request::segment(3)) ? Request::segment(3) : null, ['id' => 'patient_id']) }}
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
                                                {{ Form::select('name',$patients,'',[
                                                    'class'=>'form-control select-padding-0 patient-id name',
                                                    'placeholder'=>'Select Patient',
                                                    'id' => 'patient_id',
                                                    'data-live-search' => 'true'
                                                ])}}
                                                {{-- <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Name : &nbsp;</span>
                                                    {{Form::text('name',!empty($patientData->name) ? $patientData->name : null, [
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
                                                <div class="form-group">
                                                    {{Form::select('code',$patientcode,'',['class'=>'form-control select-padding-0 patient-code code','title'=>'Code','id'=>'patientcode','data-live-search'=>'true'])}}
                                                </div>
                                                {{-- <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Code : &nbsp;</span>
                                                    {{Form::text('code',!empty($patientData->getAppointment['code']) ? $patientData->getAppointment['code'] : null, [
                                                        'class'=>'form-control code',
                                                        'placeholder'=>'code',
                                                        'required'
                                                    ])}}
                                                </div> --}}
                                                <span class="form-error-msg">
                                                {{$errors->first('patient')}}
                                            </span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('age',!empty($patientData['age']) ? $patientData['age'] : null,['class'=>'form-control age valid-age years','placeholder'=>'Years','maxlength' => 4,'disabled'])}}
                                                </div>
                                                <span class="form-error-msg age-error"></span>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('months',!empty($patientData['months']) ? $patientData['months'] : null,['class'=>'form-control valid-age months','placeholder'=>'Months','maxlength'=>2,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon unik-lbl-spn">Age : &nbsp;</span>
                                                    {{Form::text('days',!empty($patientData['days']) ? $patientData['days'] : null,['class'=>'form-control valid-age days','placeholder'=>'Days','maxlength'=>3,'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {{Form::select('category',$category,!empty($patientData->getAppointment['category_id']) ? $patientData->getAppointment['category_id'] : null,['class'=>'form-control select-padding-0 category_data','placeholder'=>'Select Category','disabled'])}}
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
                                                    {{Form::text('mobile_number',!empty($patientData->mobile_number) ? $patientData->mobile_number : null,[
                                                        'class'=>'form-control mobile_number',
                                                        'placeholder'=>'Mobile Number',
                                                        'maxlength' => 10,
                                                        'oninput' => 'appointmentMobileNumber(this.value)'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
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
                                                    {{Form::select('gender',['2'=>'Female','1'=>'Male'], !empty($patientData->gender) ? $patientData->gender : null,['class'=>'form-control select-padding-0','id'=>'gender','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('gender')}}
                                            </span>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{Form::select('reference_doctor',$referenceDoctor,!empty($patientData->getReferenceDoctor['id']) ? $patientData->getReferenceDoctor['id'] : null,[
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
                                                    {{Form::text('doctor_name', '', [
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
                                                    {{Form::select('hospital_doctor',$hospitalDoctor,!empty($patientData->getHospitalDoctor['id']) ? $patientData->getHospitalDoctor['id'] : null,['class'=>'form-control select-padding-0 hospital_doctor','placeholder'=>'Select Hospital Doctor','data-live-search'=>'true','disabled'])}}
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
                                                    {{Form::text('residence',!empty($patientData->residence) ? $patientData->residence : null,[
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
                                                    {{Form::text('main_area',!empty($patientData->main_area) ? $patientData->main_area : null,[
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
                                                    {{Form::select('city_1',$city,!empty($patientData->city) ? $patientData->city : null,[
                                                        'class'=>'form-control select-padding-0 city-name',
                                                        'placeholder'=>'City',
                                                        'data-live-search'=>'true',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('city_1')}}
                                            </span>
                                            </div>
                                            <div class="col-md-4 col-sm-4 city-text d-none">
                                                <div class="form-group">
                                                    {{Form::text('city_2','',['class'=>'form-control city city-2','placeholder'=>'City','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('city_2')}}
                                            </span>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{Form::select('state',$state,!empty($patientData->getState['id']) ? $patientData->getState['id'] : 7, ['class'=>'form-control select-padding-0 state','placeholder'=>'State','data-live-search'=>'true','disabled'])}}
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
                                                    {{Form::text('weight',!empty($patientData->weight) ? $patientData->weight : null,[
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
                                                    {{Form::text('occupation',!empty($patientData->occupation) ? $patientData->occupation : null,[
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
                                                    {{Form::select('pregnant',[1=>'Yes',2=>'No'],!empty($patientData->is_pregnant) ? $patientData->is_pregnant : null,['class'=>'form-control pregnant select-padding-0','placeholder'=>'Pregnant','disabled'])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('pregnant')}}
                                                </span>
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
                                                                aria-controls="doctor"> Room Detail</a> </h4>
                                </div>
                                <div id="doctor"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    {{Form::text('room_type',!empty($roomtype) ? $roomtype : null,[
                                                        'class'=>'form-control room_type',
                                                        'placeholder'=>'Room Type',
                                                        'id'=>'room_type',
                                                        'required',
                                                        'readonly'
                                                    ])}}
                                                </div>

                                                <span class="form-error-msg">
                                                {{$errors->first('room_type')}}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{Form::select('room',$indoorRoom,!empty($roomData->id) ? $roomData->id : null,[
                                                        'class'=>'form-control select-padding-0 room dynamic',
                                                        'placeholder'=>'Select Room Number',
                                                        'id'=>'room',
                                                        'data-dependent'=>'bed',
                                                        'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                {{$errors->first('room')}}
                                                </span>
                                            </div>
                                            {{Form::hidden('room',!empty($roomData->id) ? $roomData->id : null)}}
                                            {{-- <div class="col-md-4 indoor-beds">
                                                <div class="form-group indoor-select-bed">
                                                    {{Form::select('room_bed', $indoorBed['indoor_bed'], !empty($indoorBed['bed_no']) ? $indoorBed['bed_no']->getRoomBed['id'] : null, [
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
                                                    <span class="input-group-addon  unik-lbl-spn">DOA Date : &nbsp;</span>
                                                    {{Form::text('date',\Carbon\Carbon::now('Asia/Kolkata')->format('D d M Y'),[
                                                        'class'=>'form-control datetimepicker date',
                                                        'placeholder'=>'Date',
                                                        'required',
                                                        'disabled' => 'disabled'
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg">
                                                    {{$errors->first('date')}}
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">DOD date : &nbsp;</span>
                                                    {{ Form::text('doddate', '', [
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
                                                    {{ Form::text('admissiontime',\Carbon\Carbon::now()->format('h:i a'), [
                                                       'class'=>'form-control timepicker',
                                                       'placeholder'=>'Time',
                                                   ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Time of Discharge :</span>
                                                    {{ Form::text('dischargetime','', [
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
                                            aria-controls="collapseThree_1">Procedure / Surgery
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_1"
                                     class=""
                                     role="tabpanel"
                                     aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix mb-3">
                                            <div class="col-md-8 complain-mulit">
                                                {{Form::select('pro[pro_type][]',$procedures,'',[
                                                    'class'=>'form-control co-value co_value_data',
                                                    'placeholder'=>'Enter Procedure/Surgery',
                                                    'multiple'=>true,'
                                                    required'
                                                ])}}
                                            </div>
                                            <div class="col-md-4">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('is_pediatric_patient',0,'',['class'=>'is_pediatric_patient','id'=>'is_pediatric_patient'])}}
                                                        <label for="is_pediatric_patient">
                                                            Pediatric Patient
                                                        </label>
                                                    </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="checkbox">
                                                    {{Form::checkbox('is_medicare_patient',0,'',['class'=>'is_medicare_patient','id'=>'is_medicare_patient'])}}
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
                                                        {{Form::textarea('remark', null, [
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
                            {{ Form::hidden('type_id', !empty($roomTypeId) ? $roomTypeId : null, ['id' => 'type_id'])}}
                            <div class="col-sm-12">
                                {{Form::submit('Save',['class'=>'btn btn-primary patient-save'])}}
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
    <script src="{{asset('assets/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
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


            $('select[name="room"]').on('change', function() {
                var typeId = $('#type_id').val();
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

            $('#room').on('change', function(event) {
                event.preventDefault();

                $(".room-bed").val($(this).val());
                $('.room-bed').selectpicker('refresh');
            });
        });

        function getIndoorBed(typeId, roomId) {
            $.ajax({
                url: "{{URL::to('get-indoor-beds')}}",
                type: 'GET',
                data: {
                    type_id: typeId,
                    room_id: roomId,
                },
            }).done(function(data) {
                $('.room-bed').empty();
                $('.indoor-beds').html(data.indoor_beds);
                $('.room-bed').selectpicker('refresh');
            }).fail(function(error) {

            });
        }
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
        function getPatientsDetails(code) {
            var url = code + "/edit";
            // var url = 'indoor/create/'.code;
            $.ajax({
                url: "{{URL::to('get-patient-data')}}"+'/'+url,
                type: "GET",
            }).done(function(data) {
                if (data['patients'] != null) {
                    var name =  $('.name').val(data['patients']['id']);
                    $('.name').selectpicker('refresh');
                    $('.years').val(data['patients']['age']);
                    $('.months').val(data['patients']['months']);
                    $('.days').val(data['patients']['days']);
                    $('.reference_doctor').val(data['patients']['reference_doctor_id']);
                    $('.reference_doctor').selectpicker('refresh');
                    $('.hospital_doctor').val(data['patients']['hospital_doctor_id']);
                    $('.hospital_doctor').selectpicker('refresh');
                    $('.other_mobile_number').val(data['patients']['other_mobile_number']);
                    $('.mobile_number').val(data['patients']['mobile_number']);
                    $('#gender').val(data['patients']['gender']);
                    $('#gender').selectpicker('refresh');
                    $('.weight').val(data['patients']['weight']);
                    $('.state').val(data['patients']['state']);
                    $('.state').selectpicker('refresh');
                    $('.city-name').val(data['patients']['city']);
                    $('.city-name').selectpicker('refresh');
                    $('.residence').val(data['patients']['residence']);
                    $('.location').val(data['patients']['location']);
                    $('.pregnant').val(data['patients']['is_pregnant']);
                    $('.pregnant').selectpicker('refresh');
                    $('.code').val(data['patients']['code']);
                    $('.code').selectpicker('refresh');
                    $('.category_data').val(data['patients']['last_appointment_data']['category_id']);
                    $('.category_data').selectpicker('refresh');
                    $('.main_area').val(data['patients']['main_area']);
                    $('.occupation').val(data['patients']['occupation']);
                } else {
                    resetForm();
                }
            }).fail(function(error) {

            });
        }

        $('.code').change(function(e) {
            code = $(this).val();
            getPatientsDetails(code);
        });
        $('.name').change(function(e) {
            code = $(this).val();
            getPatientsDetails(code);
        });
        $('.mobile_number').blur(function(e) {
            code = $(this).val();
            getPatientsDetails(code);
        });


        // $('.code').keypress(function(e) {
        //     code = $(this).val();
        //     var keycode = (event.keyCode ? event.keyCode : event.which);
        //     if (keycode == '13') {
        //         getPatientsDetails(code);
        //         e.preventDefault();
        //     }
        // });

        function resetForm() {
            var code = $('.code').val();
            $('.code').val('');
            $('.code').selectpicker('refresh');
            $('.name').val('');
            $('.name').selectpicker('refresh');
            $('.category_data').val('');
            $('.category_data').selectpicker('refresh');
            $('.reference_doctor').val('');
            $('.reference_doctor').selectpicker('refresh');
            $('.appointment-form').trigger('reset');
            // $('.code').val(code);
        }

        function appointmentMobileNumber(value) {
        // var mobileNumber = value;
            $('.mobile_number').val(validMobileNumber(value));
        }
        function validMobileNumber(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
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
