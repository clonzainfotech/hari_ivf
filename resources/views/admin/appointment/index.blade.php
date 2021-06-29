@extends('layouts.main')
@section('parentPageTitle', 'Appointment')
@section('title', 'Appointment')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single{
            height:42px !important;
            font-size: 13px;
        }
        .select2-container--default .select2-selection--single{
            border: 1px solid #E3E3E3 !important;
            border-radius: 0px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #999 !important;
            line-height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            right: 5px;
        }
        #next-appointment{
            display: block !important;
        }
        .next-time .dropdown-menu ul{
            max-height: 140.8px !important;
        }
        .update-time{
           width: 500px !important;
       }
       .time-footer{
           display: block !important;
           text-align: center !important;
       }
       .next-appointment-footer{
            justify-content: center !important;
       }
       .u-time .dropdown-menu ul{
            max-height: 120.8px !important;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix appointment">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Appointment List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <span class="total-opd badge badge-warning"><span>
                            </div>
                        </li>
                        <li>
                            @php
                                $createUrl = $isUsg ? url('appointment-create?usgType='.$isUsg) : url('appointment-create');
                            @endphp
                            <a href="{{$createUrl}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <button class="btn btn-primary print-appointmentprint">Print</button>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <input type="text" class="form-control daterange" placeholder="Select Date">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    {{Form::select('patient_id',$patientsData,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-md-4">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-md- col-sm-6">
                                    {{Form::select('reference_doctor',$referenceDoctor, '',[
                                        'class'=>'form-control select-padding-0 reference-doctor',
                                        'placeholder'=>'Select Reference',
                                        'id' => 'reference_doctor',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    {{Form::select('hospital_doctor',$hospitalDoctor, '',[
                                        'class'=>'form-control select-padding-0 hospital-doctor',
                                        'placeholder'=>'Select Hospital Doctor',
                                        'data-live-search' => 'true'
                                    ])}}

                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 ">
                                    {{Form::select('category',$category,'',[
                                        'class'=>'form-control select-padding-0 category',
                                        'placeholder'=>'Select Category',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                            </div>
                        </div>
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">
                        <!-- notification -->
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{Session::get('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                        @endif
                        <div class="appointment-data table-responsive active">
                            <div class="row">
                                <div class="page-loader-wrapper medicine-loader">
                                    <div class="loader">
                                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- table data here include -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('modal')
        <!-- appointment charges Size -->
        <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header">
                        <h4 class="title" id="defaultModalLabel">OPD Charges</h4>
                    </div>
                    <!-- body -->

                    {{Form::open(['class'=>'form-inline','id'=>'appointment-charges'])}}
                        <div class="modal-body">
                            <span class="form-error-msg minimum-charge"></span>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Consulting Charges
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('consulting_charges','',['class'=>'form-control charges-changes change consulting-charges','placeholder'=>'Consulting Charges','min'=>0])}}
                                        <span class="form-error-msg consulting_charges"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="col-md-5">
                                        {{Form::text('extra_field1[]','',['class'=>'form-control extra-field-1','placeholder'=>'Extra Field'])}}
                                    </label>
                                    <div class="col-md-6">
                                        {{Form::text('extra_field1[]','',['class'=>'form-control charges-changes extra-field-value-1','placeholder'=>'Extra Field Value'])}}
                                        <span class="form-error-msg extra_field"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="col-md-5">
                                        {{Form::text('extra_field2[]','',['class'=>'form-control extra-field-2','placeholder'=>'Extra Field','min'=>0])}}
                                    </label>
                                    <div class="col-md-6">
                                        {{Form::text('extra_field2[]','',['class'=>'form-control charges-changes extra-field-value-2','placeholder'=>'Extra Field Value','min'=>0])}}
                                        <span class="form-error-msg extra_field"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        NST
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('nst','',['class'=>'form-control charges-changes nst','placeholder'=>'NST','min'=>0])}}
                                        <span class="form-error-msg nst"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        CUT
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('cut','',['class'=>'form-control charges-changes cut','placeholder'=>'CUT','min'=>0])}}
                                        <span class="form-error-msg cut"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        USG
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('usg','',['class'=>'form-control charges-changes usg','placeholder'=>'USG','min'=>0])}}
                                        <span class="form-error-msg usg"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        IVF
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('ivf','',['class'=>'form-control charges-changes ivf','placeholder'=>'IVF','min'=>0])}}
                                        <span class="form-error-msg ivf"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Dressing
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('dressing','',['class'=>'form-control charges-changes dressing','placeholder'=>'Dressing','min'=>0])}}
                                        <span class="form-error-msg drashing"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Payment Mode
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::select('payment_mode',['2'=>'Cash','1'=>'Swipe','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],'',[
                                            'class'=>'form-control payment-mode select-padding-0 col-md-11'
                                        ])}}
                                        <span class="form-error-msg payment_mode"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Total
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('total','',['class'=>'form-control total','placeholder'=>'Total','readonly'])}}
                                        <span class="form-error-msg drashing"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Discount
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::number('discount','',['class'=>'form-control discount','placeholder'=>'Discount'])}}
                                        <span class="form-error-msg discount"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Net Amount
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::text('netamount','',['class'=>'form-control netamount','placeholder'=>'Net Amount','readonly'])}}
                                        <span class="form-error-msg netamount"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-5">
                                        Select Amount
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 charge">
                                    <div class="col-md-2">
                                        {!! Form::button('0', [
                                            'class' => 'btn btn-primary btn-sm amount button button',
                                            'id' => '0',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('100', [
                                            'class' => 'btn btn-primary btn-sm amount button button',
                                            'id' => '100',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('150', [
                                            'class' => 'btn btn-primary btn-sm amount button',
                                            'id' => '150',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('200', [
                                            'class' => 'btn btn-primary btn-sm amount button',
                                            'id' => '200',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('250', [
                                            'class' => 'btn btn-primary btn-sm amount button',
                                            'id' => '250',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('300', [
                                            'class' => 'btn btn-primary btn-sm amount button',
                                            'id' => '300',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 charge">
                                    <div class="col-md-2">
                                        {!! Form::button('500', [
                                            'class' => 'btn btn-primary btn-sm amount button',
                                            'id' => '500',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('700', [
                                            'class' => 'btn btn-primary btn-sm amount button',
                                            'id' => '700',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('1200', [
                                            'class' => 'btn btn-primary btn-sm amount',
                                            'id' => '1200',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('1800', [
                                            'class' => 'btn btn-primary btn-sm amount',
                                            'id' => '1800',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('2000', [
                                            'class' => 'btn btn-primary btn-sm amount',
                                            'id' => '2000',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::button('4000', [
                                            'class' => 'btn btn-primary btn-sm amount',
                                            'id' => '4000',
                                            'onclick' => 'getConsultingCharges(this.id)'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            {{Form::hidden('select_appointment_id','',['class'=>'selected-appointment-id'])}}
                        </div>
                        <!-- footer -->
                        <div class="modal-footer d-inline-block center-footer">
                            <button type="submit" class="btn btn-primary waves-effect charges-table submit-button">Save</button>
                            <button type="button" class="btn btn-primary waves-effect no-opd">No OPD</button>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
        <!-- next appointment -->
        <div class="modal fade" id="next-appointment-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header justify-content-center">
                        <h4 class="title" id="next-appointment">Next Appointment</h4>
                    </div>
                    <!-- body -->
                    {{Form::open(['class'=>'form-inline','id'=>'next-appointment'])}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Day
                                </div>
                                <div class="col-md-5">
                                    {{Form::number('day','',['class'=>'form-control next-day','placeholder'=>'Day','min'=>1,'oninput'=>"validity.valid||(value='');"])}}
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
                                    {{Form::date('date','',['class'=>'form-control next-date','placeholder'=>'Date','min'=>date("Y-m-d", strtotime("+ 1 day"))])}}
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
                                    {{Form::select('next-time', $hospitalTime, '', [
                                        'class'=>'next-time select-padding-0',
                                        'placeholder'=>'Time'
                                    ])}}
                                    <span class="form-error-msg date"></span>
                                </div>
                                <span class="form-error-msg time"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-11 pl-4">
                                    <div class="checkbox">
                                        {{Form::checkbox('is_usg','1','',['id'=>'is_usg'])}}
                                        <label for="is_usg">
                                            Is USG Appointment
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::textarea('next-remark', null, ['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{Form::hidden('appointment-id','',['class'=>'appointment-id'])}}
                    <!-- footer -->
                    <div class="modal-footer next-appointment-footer">
                        <a href="#" class="btn btn-primary waves-effect save-btn disabled next-appointment-form">Save</a>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-appointment-time" tabindex="-1" role="dialog">
            <div class="modal-dialog update-time" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header justify-content-center">
                        <h4 class="title" id="next-appointment">Appointment Date & Time</h4>
                    </div>
                    <!-- body -->
                    {{Form::open(['class'=>'form-inline'])}}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    Appointment Date
                                </div>
                                <div class="col-md-6">
                                    {{Form::text("appointment_date",'',['class'=>'form-control datetimepicker update-appointment-date w-100'])}}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                Appointment Time
                                </div>
                                <div class="col-md-5">
                                    {{Form::select('appointment_time', $hospitalTime, '', ['class'=>'appointment-time select-padding-0 u-time','placeholder'=>'Appintment Time'])}}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                Arrival Time
                                </div>
                                <div class="col-md-5">
                                    {{Form::select('arrival_time', $hospitalTime, '', ['class'=>'arrival-time u-time select-padding-0','placeholder'=>'Arrival Time'])}}
                                </div>
                                {{Form::hidden('appointment_id','',['class'=>'a-id'])}}
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-8">
                                    <span class="form-error-msg update-time-msg d-none">Please enter date.</span>
                                </div>
                            </div>
                            <!-- footer -->
                            <div class="modal-footer mt-3 time-footer">
                                <button type="button" class="btn btn-primary waves-effect update-time-save">Save</button>
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>

        </div>
    @stop
@stop
@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <script type="text/javascript">

        var page = '';
        var patientId = '';
        var appointmentId = '';
        var date = '';
        var time = '';
        var search = '';
        var usg_status = "{{$usgStatus == 1 ? 'yes' : ''}}";
        var categoryId ='';
        var nextAppointmentQueryString = 'date=' + date + '&time=' + time;
        $('.daterange').daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });

        var referenceDoctorId = '';
        var hospitalDoctorId = '';
        date = $('.daterange').val();

        var selectedAppointmentId = '';
        qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date + '&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;



        $(document).ready(function(){
            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                // minDate:new Date(),
                time:false,
                weekStart: 1
            });
            sessionDate = "{{session('date')}}";
            {{session()->forget('date')}}
            if(sessionDate != ''){
                $('.daterange').val(date);
            }
            $('.total').val(0);

            $('.daterange').change(function(){
                date = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date + '&reference_doctor_id=' + referenceDoctorId + '&hospital_doctor_id=' + hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            getAppointmentData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('click','.btn-success',function(e){
                e.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('click','.delete-appointment',function(){
                $('#collection_report').modal('show');
                var deleteAppointmentId = $(this).data('id');
                var url = "{{URL::to('appointment/delete/')}}"+'/'+deleteAppointmentId;
                $('.collectionurl').val(url);
                // appointmentId = $(this).data('id');
                // showConfirmMessage();
            });

            $(document).on('change','.usg-status',function(){
                usg_status = 'no';
                if ($(this).prop("checked")) {
                    usg_status = 'yes';
                }
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('change','select.reference-doctor',function(){
                referenceDoctorId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('change','select.hospital-doctor',function(){
                hospitalDoctorId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });
            $(document).on('change', 'select.category', function () {
                categoryId = $(this).val();
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });

            $(document).on('blur','.next-day',function(){
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                if (day) {
                    // var time = $('select.next-time').find("option:selected").text();
                    getNextAppointmentDate(selectedAppointmentId,day,null,null);
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

            $(document).on('click','.next-appointment',function(e){
                e.preventDefault();
                $('#next-appointment').trigger('reset');
                $('.form-error-msg').empty();
                $('.next-day').val('');
                $('.next-date').val('');
                $('.next-time').val('');
                $('.save-btn').addClass('disabled');
                $('.appointment-id').val($(this).attr('id'));
            });

            $(document).on('click','.next-appointment-form',function(e){
                var selectedAppointmentId = $('.appointment-id').val();
                var day = $('.next-day').val();
                var date = $('.next-date').val();
                var remark = $('.remark').val();
                // var is_usg = 0;
                if($('#is_usg:checked').prop('checked'))
                {
                    usg_status = 1;
                }
                var time = $('select.next-time').find("option:selected").text();
                var token = "{{csrf_token()}}";

                $.ajax({
                    url: "{{URL::to('next-appointment-store')}}",
                    dataType: 'json',
                    type: 'post',
                    data:{appointmentId:selectedAppointmentId,day:day,_token:token,date:date,time:time,remark:remark,usg_status:usg_status}
                }).done(function(data) {
                    if(data.status == 1){
                        $(".daterange").daterangepicker({
                            locale: {
                                direction: 'drop-down-date-range',
                                cancelLabel: 'Clear',
                                format: 'D/M/Y'
                            }
                        });
                        $('.daterange').val(data.date + ' - ' +data.date);
                        $("input[name=daterangepicker_end]").val(data.date);
                        $("input[name=daterangepicker_start]").val(data.date);
                        date = $('.daterange').val();
                        qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&usg_status='+usg_status;
                        $('#next-appointment-modal').modal('hide');
                        showNotification('bg-blue', 'Next appointment successfully added.', 'bottom', 'right', "", "");
                        getAppointmentData(qstring);
                    }
                    if(typeof data.main_status != 'undefined' && data.main_status== 2){
                        showHodidayAppointment(data.status);
                    }
                }).fail(function(error) {
                    $('.form-error-msg').text('');
                    if(error.responseJSON != null){
                        var formError = error.responseJSON.errors;
                        $.each(formError,function(key,value){
                            $('.'+key).text(value);
                        });
                    }
                });
            });

            $(document).on('click', '.print-appointmentprint', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&isprint=1'+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });
        });

        $(document).on('click','.print-opd-charge', function(){

            var appointmentId = $(this).attr('id');
            var isprint = 1;
            if(typeof(appointmentId) !== 'undefined'){
                $('#appointment-table tbody tr').removeClass('selected-tr');
                $(this).addClass('selected-tr');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('get-appointment-charges')}}",
                    type: "POST",
                    data: {appointment_id:appointmentId,isprint:isprint},
                    dataType: 'json',
                }).done(function(data) {
                    w = window.open(window.location.href,"_blank");
                    w.document.open();
                    w.document.write(data);
                    w.document.close();
                    w.window.print();
                });
            }
        });
        // $(document).on('click','.send-opd', function(){

        //     var appointmentId = $(this).attr('id');
        //     if(typeof(appointmentId) !== 'undefined'){
        //         $('#appointment-table tbody tr').removeClass('selected-tr');
        //         $(this).addClass('selected-tr');
        //         $.ajax({
        //             url: "{{URL::to('send-opd')}}",
        //             data: {appointment_id:appointmentId},
        //             dataType: 'json',
        //         }).done(function(data) {
        //             // w = window.open(window.location.href,"_blank");
        //             // w.document.open();
        //             // w.document.write(data);
        //             // w.document.close();
        //             // w.window.print();
        //         });
        //     }
        // });

        $(document).on('click', '#appointment-table tbody tr', function(event){
            $('#appointment-table tbody tr').removeClass('appointment-selected-tr');
            $(this).addClass('appointment-selected-tr');
        });

        $(document).on('submit','#appointment-charges',function(e){
            e.preventDefault();
            var emptyCharge = $('.charges-changes').filter(function() {
                return this.value != ''
            });

            if (emptyCharge.length == 0) {
                $('.minimum-charge').text('Please enter minimum 0 charges');
                return false;
            }
            $('.submit-button').prop('disabled',true);
            var chargesData = $(this).serialize();
            appointmentCharges(chargesData);
        });

        $(document).on('dblclick', '#appointment-table tbody tr', function(event){
            var appointmentId = $(this).data('id');
            if(typeof(appointmentId) !== 'undefined'){
                var url = "{{URL::to('appointment')}}"+'/'+appointmentId+'/edit';
                window.location.href = url;
            }
        });

        $(document).on('click','.opd-patients',function(){
            selectedAppointmentId = this.id;
            $('.minimum-charge').text('');
            $('.selected-appointment-id').val(selectedAppointmentId);
            getAppointmentCharges(selectedAppointmentId);
            $('.minimum-charge').text('');
            $('#appointment-charges').trigger('reset');
        });

        $(document).on('click','.no-opd',function(e){
            e.preventDefault();
            var token = "{{csrf_token()}}";
            var apId = $('.selected-appointment-id').val();
            var chargesData = 'no_opd=true&_token='+token+'&select_appointment_id='+apId;
            appointmentCharges(chargesData);
        });

        $(document).on('keypress','.daterange',function(e){
            return false;
        });

        $(document).on('keyup','.charges-changes',function(e){
            var sum = 0;
            $(".charges-changes").each(function(){
                sum += +$(this).val();
            });
            $(".total").val(sum);
            $(".netamount").val(sum);

            var discountValue = $('.discount').val();
            if(discountValue != ''){
                discount(discountValue);
            }

        });

        $(document).on('keyup','.discount',function(e){
            discount($(this).val());
        });

        $(document).on('click','.sticker-appointment',function(){
            var appointmentId = this.id;
            $.ajax({
                url: "{{URL::to('appointment-sticker')}}",
                data:{appointmentId},
                dataType: 'json',
            }).done(function(data) {
                w = window.open(window.location.href,"_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
            });
        });

        $(document).on('click','.print-appointment',function(){
            var appointmentId =  this.id;
            $.ajax({
                url: "{{URL::to('appointment-printview')}}",
                data:{appointmentId},
                dataType: 'json',
            }).done(function(data) {
                w = window.open(window.location.href,"_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
            });
        });

        $(document).on('click','.edit-remark-icon',function(e){
            e.preventDefault();
            $(this).addClass('appointment-selected-tr');
            var dId = $(this).data('id');
            var appointmentId = $(this).data('appointmentid');
            var value = $(this).data('value');
            if($('.remark-data').hasClass('remark-val')){
                var previousId = $('.remark-val').data('id');
                var previousRemark = $('.remark-val').data('value');
                var data = "<div class='edit-remark-data edit-remark-'"+previousId+"'>"+
                    wordwrap(""+previousRemark+"", 30,'<br>\n')+
                    "<span class='edit-remark'>"+
                        "<i class='material-icons edit-remark-icon' data-value="+previousRemark+" data-id="+previousId+">edit</i>"+
                    "</span>"+
                "</div>";
                $('.edit-remark-'+previousId).html(data);
            }
            var remarkData = "<input type ='text' name='total' value='"+value+"' class='form-control remark-val remark-data remark-value-"+dId+"' data-appointmentid='"+appointmentId+"' data-value='"+value+"' data-id="+dId+">";
            $('.edit-remark-'+dId).html(remarkData);
        });

        $(document).on('blur','.remark-data',function(){
            var remark = $(this).val();
            var appointmentId = $(this).data('appointmentid');
            var remarkValue = 'remark='+remark+'&appointmet_id='+appointmentId;
            updateRemark(remarkValue,'blur');
        });

        $(document).on('keyup','.remark-data',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var remark = $(this).val();
                var appointmentId = $(this).data('appointmentid');
                var remarkValue = 'remark='+remark+'&appointmet_id='+appointmentId;
                updateRemark(remarkValue,'keyup');
            }
        });

        $(document).on('click','.add-arrival',function(){
            var appointmentId =  $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'appointment/update-arrival-time',
                type: 'POST',
                data: {
                    appointment_id: appointmentId
                },
                dataType: 'json',
            }).done(function(data) {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
            });
        });
        $(document).on('click','.a-time',function(){
            var appointmentId =  $(this).data('appointmentid');
            var appointmentTime =  $(this).data('time');
            var arrivalTime =  $(this).data('arrival');
            var date =  $(this).data('date');
            $('.a-id').val(appointmentId);
            if(appointmentTime != '' || typeof appointmentTime != 'undefined'){
                $('.appointment-time').val(appointmentTime);
                $('.appointment-time').selectpicker('refresh');
                $('.arrival-time').val(arrivalTime);
                $('.arrival-time').selectpicker('refresh');
                $('.update-appointment-date').val(date);
            }
        });

        $(document).on('click','.update-time-save',function(){
            var time = $('select.appointment-time').val();
            var atime = $('select.arrival-time').val();
            var appointmentId = $('.a-id').val();
            var date = $('.update-appointment-date').val();
            $('.update-time-msg').addClass('d-none');
            // if(time == '' && atime == ''){
            //     $('.update-time-msg').removeClass('d-none');
            //     return true;
            // }
            if($('.update-appointment-date').val() == ''){
                $('.update-time-msg').removeClass('d-none');
                return true;
            }
            $.ajax({
                url: "{{URL::to('update-appointment-time')}}",
                data:{appointment_id:appointmentId,time:time,arrival:atime,date:date},
                dataType: 'json',
            }).done(function(data) {
                $('#edit-appointment-time').modal('hide');
                getAppointmentData(qstring);
                showNotification('bg-blue', 'Time added successfully.', 'bottom', 'right', "", "");
            });
        });

        function discount(discount){
            var total = $('.total').val();
            var discount = (total*discount) / 100;
            $('.netamount').val(total - discount);
        }

        function wordwrap( str, width, brk, cut){
            brk = brk || '\n';
            width = width || 75;
            cut = cut || false;

            if (!str) { return str; }

            var regex = '.{1,' +width+ '}(\\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\\S+?(\\s|$)');

            return str.match( RegExp(regex, 'g') ).join( brk );
        }

        function updateRemark(remarkValue,type){
            $.ajax({
                url: "{{URL::to('appointment-update-remark')}}?"+remarkValue,
                dataType: 'json',
            }).done(function(data) {
                if(type == 'blur'){
                    showNotification('bg-blue', 'Remark changed successfully.', 'bottom', 'right', "", "");
                }
                getAppointmentData(qstring);
            }).fail(function() {

            });
        }

        // get all appointment data
        function getAppointmentData(qstring){
            $('.appointment-loader').removeClass('d-none');
            $('.appointmentdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('appointment')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    var opd = data.totalOpd;
                    $('.total-opd').text('Total OPD : '+opd);
                    $('.appointment-data').html(data.appointmentData);
                    var pData = '';
                    $('.appointment-loader').addClass('d-none');
                    pData += '<option value="">Select Patient</option>';
                    $.each(data.patientsData, function(key, value) {
                        pData +=  '<option value="' + key + '">'+value+'</option>';
                    });
                    $('select.patient-id').html(pData);
                    if(typeof data.pId != 'undefined' && data.pId != '' || data.pId != null){
                        $('.patient-id').val(data.pId);
                    }
                    $('.patient-id').selectpicker('refresh');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.write(data.appointmentData);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

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
                    $('.save-btn').removeClass('disabled');
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

        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to delete this appointment!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                removeAppointment();
                // swal("Deleted!", "Your appointment has been deleted.", "success");
                $('.showSweetAlert').hide();
                location.reload();
            });
        }

        // remove appointment
        function removeAppointment(){
            $.ajax({
                url: "{{URL::to('appointment/delete/')}}"+'/'+appointmentId,
                dataType: 'json',
            }).done(function(data) {
                getAppointmentData(qstring);
            }).fail(function() {

            });
        }

        function appointmentCharges(chargesData){
            $.ajax({
                url: "{{URL::to('appointment-charges/store')}}",
                dataType: 'json',
                type: 'POST',
                data:chargesData
            }).done(function(data) {
                $('#defaultModal').modal('hide');
                showNotification('bg-blue', 'Appointment charges successfully added.', 'bottom', 'right', "", "");
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&usg_status='+usg_status;
                getAppointmentData(qstring);
                $('.submit-button').prop('disabled',false);
            }).fail(function(error) {
                $('.form-error-msg').text('');
                if(error.responseJSON != null){
                    var formError = error.responseJSON.errors;
                    $.each(formError,function(key,value){
                        $('.'+key).text(value);
                    });
                }
            });
        }

        function getAppointmentCharges(appointmentId){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{URL::to('get-appointment-charges')}}",
                type: "POST",
                data: {appointment_id:appointmentId},
                dataType: 'json',
            }).done(function(data) {
                var extraField1 = data.extraField1;
                var extraField2 = data.extraField2;
                var appointmentCharges = data.appointmentData;
                $('.submit-button').show();
                if(appointmentCharges != null){
                    $('.consulting-charges').val(appointmentCharges.consulting_charges);
                    $('.cut').val(appointmentCharges.cut);
                    $('.discount').val(appointmentCharges.discount);
                    $('.dressing').val(appointmentCharges.dressing);
                    $('.ivf').val(appointmentCharges.ivf);
                    $('.netamount').val(appointmentCharges.netamount);
                    $('.nst').val(appointmentCharges.nst);
                    $('.payment-mode').val(appointmentCharges.payment_mode);
                    $('.payment-mode').selectpicker('refresh');
                    $('.total').val(appointmentCharges.total);
                    $('.usg').val(appointmentCharges.usg);

                    if(extraField1 != null){
                        $('.extra-field-1').val(extraField1[0]);
                        $('.extra-field-value-1').val(extraField1[1]);
                    }
                    if(extraField1 != null){
                        $('.extra-field-2').val(extraField2[0]);
                        $('.extra-field-value-2').val(extraField2[1]);
                    }

                    if(appointmentCharges.is_submit_button == 'yes'){
                        $('.submit-button').hide();
                    }
                }
            }).fail(function(error) {

            });
        }

        function getConsultingCharges(id) {
            $('input[type="number"][name="consulting_charges"]').val(id);
            $('.amount').css({'border-color': '',
                'background-color': '#00cfd1',
            });
            $('#' + id).css({'border-color': '#000000',
                'background-color': '#01aeaf',
            });
            var sum = 0;
            $('.charges-changes').each(function() {
                sum += +$(this).val();
            });
            $('.total').val(sum);
            $('.netamount').val(sum);

            var discountValue = $('.discount').val();
            if (discountValue != '') {
                discount(discountValue);
            }
        }

        function showHodidayAppointment(status){
            var date = "{{Session::get('date')}}";
            var time = "{{Session::get('time')}}";
            var holiday = "{{Session::get('name')}}";
            var dateTime = date+' '+time;
            var appointmentId = "{{Session::get('appointmentId')}}";
            if(status == 2){
                msg = 'Sunday Appointment';
                text = "This is Appointment of Sunday, Are you sure you want to appointment??";
            }
            if(status == 3){
                msg = 'Holiday Appointment';
                text = "This Appointment of Holiday "+holiday+", Are you sure you want to appointment??";
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
                updateAppointmentDate(appointmentId,date,time);
                $('.showSweetAlert').hide();
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
                window.location.href = "{{URL::to('appointment')}}";
            }).fail(function(error){

            });
        }

    </script>
@stop
