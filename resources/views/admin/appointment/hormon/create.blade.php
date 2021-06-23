@extends('layouts.main')
@section('parentPageTitle', 'Hormon')
@section('title', 'Add Hormon ')

@section('page-style')

@stop
@section('content')
    <div class="row clearfix hormon">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add Hormon</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('hormon')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{Form::open(['url'=>'hormon/add','method'=>'post','class'=>'form','id'=>'hormon'])}}
                        <div class="row hormon-row">
                            <div class="col-md-6">
                                {{Form::select('hname',$patient,'',[
                                    'class'=>'form-control hname',
                                    'placeholder'=>'Select patient',
                                    'id'=>'hname',
                                    'data-live-search'=>'true',
                                ])}}
                                <span class="form-error-msg  hnameerror">
                                    {{$errors->first('hname')}}
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::select('htype',['1'=>'Hormon', '2'=>'IVF', '3'=>'IUI'],'',[
                                    'class'=>'form-control htype', 
                                    'id' => 'htype'
                                ])}}
                                <span class="form-error-msg htype">
                                    {{$errors->first('htype')}}
                                </span>
                            </div>
                        </div>
                        <div class="row hormon-row hinjection-data">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Injection : &nbsp;</span>
                                    {{Form::text('hinjection','',[
                                        'class'=>'form-control hinjection',
                                        'placeholder'=>'Injection',
                                        'id'=>'injection'
                                    ])}}
                                </div>
                                <span class="form-error-msg injection">
                                    {{$errors->first('hinjection')}}
                                </span>
                            </div>
                        </div>
                        <div class="row hormon-row mt-18">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Charge : &nbsp;</span>
                                    {{Form::text('hcharge','',[
                                        'class'=>'form-control hcharge',
                                        'placeholder'=>'Charge',
                                        'oninput' => 'checkCharge(this.value)',
                                        'maxlength' => 6,
                                        'onpaste' => 'return false',
                                        'min' => 1,
                                        'id'=>'hcharge'
                                    ])}}
                                </div>
                                <span class="form-error-msg hchargeerror">
                                    {{$errors->first('hcharge')}}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Date:</span>
                                    {{Form::text('date',\Carbon\Carbon::now()->format('l d M Y'),[
                                        'class'=>'form-control datetimepicker date',
                                        'placeholder'=>'Select Date',
                                    ])}}
                                </div>
                                <span class="form-error-msg date-error"></span>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="form-group col-md-6">
                                {{Form::select('payment_type',['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],'',['class'=>'form-control payment-method','placeholder'=>'Select Payment Type'])}}
                                <span class="form-error-msg payment_method_error"></span>
                            </div>
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon unik-lbl-spn">Remaining Day : &nbsp;</span>
                                {{Form::number('remaining_day','',['class'=>'form-control','placeholder'=>'Enter Only Day',])}}
                            </div>
                        </div>
                        </div>
                       <!--  <div class="row">
                            <div class="form-group col-md-6">
                                {{Form::select('payment_type',['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],'',['class'=>'form-control payment-method','placeholder'=>'Select Payment Type'])}}
                                <span class="form-error-msg payment_method_error"></span>
                            </div>
                        </div> -->
                        {{-- <div class="row hormon-row hcategory-data">
                            <div class="col-md-12">
                                {{Form::select('hcategory',$category,'',[
                                    'class'=>'form-control hormon-width-100 plr-0 hcategory',
                                    'placeholder'=>'Select Category',
                                    'id'=>'hcategory'
                                ])}}
                                <span class="form-error-msg hcategory">
                                    {{$errors->first('hcategory')}}
                                </span>
                            </div>
                        </div> --}}

                        {{-- <div class="row ivf-data">
                            <div class="form-group col-md-6">
                                {{Form::text('h_name','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Husband Name',
                                ])}}
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::number('sonography_charge','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Sonography Charge',
                                    'min' => 0
                                ])}}
                            </div>
                        </div>

                        <div class="row ivf-data">
                            <div class="form-group col-md-6">
                                {{Form::number('ivf_lab_charge','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'IVF Lab Charge',
                                    'min' => 0
                                ])}}
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::number('icsi_ivf_charge','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'ICSI - IVF',
                                    'min' => 0
                                ])}}
                            </div>
                        </div>
                        
                        <div class="row ivf-data">
                            <div class="form-group col-md-6">
                                {{Form::number('embryo_transfer_charge','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Embryo Transfer',
                                    'min' => 0
                                ])}}
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::number('embryo_freezing','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Embryo Freezing',
                                    'min' => 0
                                ])}}
                            </div>
                        </div>
                        
                        <div class="row ivf-data">
                            <div class="form-group col-md-6">
                                {{Form::number('hystrocopy','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Hystrocopy',
                                    'min' => 0
                                ])}}
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::number('donor_charge','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Donor Charge',
                                    'min' => 0
                                ])}}
                            </div>
                        </div>
                        
                        <div class="row ivf-data">
                            <div class="form-group col-md-6">
                                {{Form::number('medical_medicines','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Medical Medicines',
                                    'min' => 0
                                ])}}
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::number('anesthescis_doctor','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Anaithesia Charge ',
                                    'min' => 0
                                ])}}
                            </div>
                        </div>
                        
                        <div class="row ivf-data">
                            <div class="form-group col-md-6">
                                {{Form::number('blood_report','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Blood Report',
                                    'min' => 0
                                ])}}
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::number('tesa_pesa','',[
                                    'class'=>'form-control col-md-12',
                                    'placeholder'=>'Tesa Pesa Charge',
                                    'min' => 0
                                ])}}
                            </div>
                        </div> --}}

                        <div class="row hormon-row reference-doctor">
                            <div class="col-md-6">
                                {{Form::select('hreference_doctor_id', $referenceDoctor, '', [
                                    'class' => 'form-control w-100 plr-0 hreference-doctor ref-name',
                                    'id'=>'hreference-doctor',
                                    'placeholder'=>'Select Reference Doctor',
                                    'data-live-search'=>'true',
                                    'disabled'
                                ])}}
                                {{Form::hidden('hreference_doctor_id','',['class'=>'reference_doctor'])}}
                                <span class="form-error-msg hreference_doctor">
                                    {{$errors->first('hreference_doctor_id')}}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    {{Form::textarea("remark",'',['class'=>'form-control no-resize','placeholder'=>'Remark','rows'=>'5'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row hormon-row mt-30 doctor-name d-none">
                            <div class="form-group col-md-6">
                                {{Form::text('doctor_name','',[
                                    'class'=>'form-control doctor col-md-12',
                                    'placeholder'=>'Doctor Name',
                                    'onkeypress'=>'return /[a-z]/i.test(event.key)',
                                    'id'=>'doctor'
                                ])}}
                                <span class="form-error-msg doctorerror">
                                    {{$errors->first('doctor_name')}}
                                </span>
                            </div>

                            <div class="form-group col-md-6">
                                {{Form::text('doctor_mobile_number','',[
                                    'class'=>'form-control doctor col-md-12',
                                    'placeholder'=>'Doctor Mobile Number',
                                    'oninput' => 'doctorMobileNumber(this.value)',
                                    'maxlength' => 10,
                                    'id'=>'doctor_mobile'
                                ])}}
                                <span class="form-error-msg doctor_mobile">
                                    {{$errors->first('doctor_mobile_number')}}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            {{Form::submit('Save',['class'=>'btn btn-primary submit'])}}
                                <button type="submit" name="Save" class="btn btn-primary submit" value="1">Save & Preivew</button>
                            <a href="{{URL::to('hormon')}}" class="btn btn-default">Cancel</a>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript">

        var hormonId = '';
        var charge_type = $('.charge_type').val();
        var charge_text = $('.charge_type option:selected').val();
        var hname = '';
        var htype = 1;
        var hormonQueryString = 'hname=' + hname + '&htype=' + htype;
        var selectedHormonId = '';
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
            weekStart: 1
        });
        $(document).ready(function(){
            if($("#hreference-doctor option:selected" ).text() == 'other') {
                // $('input[type="text"][name="doctor_name"]').prop('required', 'required');
                // $('input[type="text"][name="doctor_mobile_number"]').prop('required', 'required');
                $('.doctor-name').removeClass('d-none');
                $('.doctor-mobile-number').removeClass('d-none');
            }
            setField($('select.htype').val());
            if($('#htype').find(":selected").text() == 'Hormon' || $('#htype').find(":selected").text() == 'IVF') {
                // $('select[name="hreference_doctor_id"]').prop('required', false);
            }else{
                // $('select[name="hreference_doctor_id"]').prop('required', true);
            }
            // ($('#htype').find(':selected').text() == 'Hormon') ? ($('.hinjection').prop('required', true)) : ($('.hinjection').prop('required', false));
            $('.add-hormon').on('click', function(event) {
                $('.doctor-name').addClass('d-none');
                $('.doctor-mobile-number').addClass('d-none');
                $('.erro').hide();
                $('#add-edit-hormon').trigger('reset');
                $('#hormon_hidden_id').val('');
            });
            $('select.hname').change(function(e) {
                e.preventDefault();
                hname = $(".hname option:selected").val();
                $.ajax({
                    url: "{{URL::to('get-hormon-data')}}",
                    dataType: 'json',
                    data: {
                        hname: hname
                    },
                    
                }).done(function(hormonData) {
                    $('.htype').val('');
                    $('.htype').selectpicker('refresh');
                    $('.hinjection').val('');
                    $('.hcharge').val('');
                    $('.hreference-doctor').val(1);
                    $('.hreference-doctor').selectpicker('refresh');
                    setField(1);
                    if(hormonData.patient.reference_doctor_id != null){
                        $('.ref-name').val(hormonData.patient.reference_doctor_id);
                        $('.reference_doctor').val(hormonData.patient.reference_doctor_id);
                        $('.ref-name').selectpicker('refresh');
                    }
                    if (hormonData.hormon != null) {
                        setField(hormonData.hormon.charge_type);
                        $('.htype').val(hormonData.hormon.charge_type);
                        $('.htype').selectpicker('refresh');
                        if (hormonData.injection_data != null) {
                            $('.hinjection').val(hormonData.injection_data.injection);
                        }

                        $('.hcharge').val(hormonData.hormon.charge);

                        // if (hormonData.reference_doctor_data != null) {
                        //     $('.hreference-doctor').val(hormonData.reference_doctor_data.reference_doctor_id);
                        // }

                        // $('.hreference-doctor').selectpicker('refresh');
                    }
                    
                }).fail(function() {
                });
            });
            $('select[name="hreference_doctor_id"]').on('change', function() {
                if (this.value == 'other') {
                    // $('input[type="text"][name="doctor_name"]').prop('required', 'required');
                    // $('input[type="text"][name="doctor_mobile_number"]').prop('required', 'required');
                    $('.doctor-name').removeClass('d-none');
                    $('.doctor-mobile-number').removeClass('d-none');

                } else {
                    // $('input[type="text"][name="doctor_name"]').prop('required', false);
                    // $('input[type="text"][name="doctor_mobile_number"]').prop('required', false);
                    $('.doctor-name').addClass('d-none');
                    $('.doctor-mobile-number').addClass('d-none');
                }
            });
            $('select[name="htype"]').on('change', function() {
                (this.value == 1 || this.value == 2) ? $('select[name="hreference_doctor_id"]').prop('required', false) : $('select[name="hreference_doctor_id"]').prop('required', true);
                (this.value == 1) ? ($('.hinjection').prop('required', true)) : ($('.hinjection').prop('required', false));
                setField($(this).val());
            });
            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var reportData = $('#hormon').serialize();
                if(this.value == 1) {
                    reportData = reportData + '&isprint=1';
                }
                hormonData(reportData);
            });
        });
        function setField(value){
            $('.ivf-data').addClass('d-none');
            if(value == '2' || value == '3'){
                if(value == '2'){
                    $('.ivf-data').removeClass('d-none');
                }
                $('.hinjection-data').addClass('d-none');
                $('.hinjection').prop('required', false);
                // $('.doctor-name').addClass('d-none');
                // $('.doctor-mobile-number').addClass('d-none');
            }

            if(value == '1'){
                $('.hinjection-data').removeClass('d-none');
                // $('.hinjection').prop('required', true);
                $('.doctor-name').addClass('d-none');
                $('.doctor-mobile-number').addClass('d-none');
            }

            if(value == '3' && $('select[name="hreference_doctor_id"]').val() == 'other'){
                $('.doctor-name').removeClass('d-none');
                $('.doctor-mobile-number').removeClass('d-none');
                
            }
        }
        function validCharge(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
        }
        function checkCharge(value) {
            $('.hcharge').val(validCharge(value));
        }
        function doctorMobileNumber(value) {
            $('input[type="text"][name="doctor_mobile_number"]').val(validCharge(value));
        }
        function hormonerrorMessage() {
            var valid = 1;
            var hname=document.getElementById('hname').value;
            var htype=document.getElementById('htype').value;
            var injection=document.getElementById('injection').value;
            var hcharge=document.getElementById('hcharge').value;
            var hreference_doctor=document.getElementById('hreference-doctor').value;
            var doctor=document.getElementById('doctor').value;
            var doctor_mobile=document.getElementById('doctor_mobile').value;
            var paymentMethod = $('select.payment-method').val();
            $('.form-error-msg').text('');
            $('.date-error').text('');
            if(hname == ''){
                valid = 0;
                $('.hnameerror').text('The hname field is required.');
            }
            if(paymentMethod == ''){
                valid = 0;
                $('.payment_method_error').text('The payment method field is required.');
            }
            if (htype == '') {
                valid = 0;
                $('.htype').text('The type field is required.');
            }
            if(htype == 1){
                if (injection == '') {
                    valid = 0;
                    $('.injection').text('The injection field is required.');
                }
            }
            if (hcharge == '') {
                valid = 0;
                $('.hchargeerror').text('The charge field is required.');
            }
            
            if (hreference_doctor == '') {
                valid = 0;
                $('.hreference_doctor').text('The referencedoctor field is required.');
            }
            if(hreference_doctor == 'other'){
                if (doctor == '') {
                    valid = 0;
                    $('.doctorerror').text('The doctor name field is required.');
                }
                if (doctor_mobile == '') {
                    valid = 0;
                    $('.doctor_mobile').text('The doctormobile field is required.');
                }
            }
            if($('.date').val() == ''){
                $('.date-error').text('The date field is required.');
            }
            if(valid == 0){
                return false;
            }
            return true;
        }
        function hormonData(data) {
            var isError = hormonerrorMessage();
            if(isError == false){
                return false;
            }
            hormanDataStore(data);
        }

        function hormanDataStore(data) {
            var amount = $('.hcharge').val();
            Swal.fire({
                title: "Are you sure?", 
                html: "To credit this amount <b>"+amount+"</b>",  
                confirmButtonText: "Confirm", 
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
            }).then(function(isConfirm) {
                if(isConfirm.value){
                    $.ajax({
                        url:'{{URL::to("hormon/add")}}',
                        type:'POST',
                        dataType:'json',
                        data:data,
                    }).done(function(data){
                        if(data.status == 2){
                            $('.doctor_mobile').text('Mobile number already exist.');
                        }
                        if(data.status == 'true'){
                            var url = '{{URL::to("hormon")}}';
                            window.location.href = url;
                        }else if(data.status == 1){              
                            w = window.open(window.location.href, "_blank");
                            w.document.open();
                            w.document.write(data.data);
                            w.document.close();
                            w.window.print();
                        }
                    });
                }
            });
        }
    </script>
@stop