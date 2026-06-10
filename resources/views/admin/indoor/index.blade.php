@extends('layouts.main')
@section('parentPageTitle', 'Indoor')
@section('title', 'Indoor')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <style>
       .sticker-modal-width{
           width: 350px !important;
       }
       .sticker-modal-footer{
           display: block !important;
           text-align: center !important;
       }
       .patient-room{
           cursor: pointer;
           
       }
       .p-name{
           color: #F96332 !important;
       }
       .room-head
       {
        background-color: #ccd4dc !important;
           -webkit-print-color-adjust: exact;
       }
       .p-empty
       {
           border: 1px solid #ccd4dc !important;
       }
       @media print {
     {page-break-after: always;}
     @page { margin-top : 50px;margin-bottom: 100px;}
    }
    </style>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary indoor-print" value="0">Indoor Print</button>
        <button class="btn btn-primary indoor-print" value="2">Indoor Floor-2</button>
        <button class="btn btn-primary indoor-print" value="3">Indoor Floor-3</button>
    </div>
</div>
    <div class="row clearfix indoor_detail">
        @foreach($indoorTypes as $indoor)
            @php
                $class = null;
                if($indoor->name == 'General'){
                    $class = 'general-cycle';
                }
            @endphp
            <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-3 patient_name patient-room room-head">
                            <div class="col-md-12 col-lg-12 col-sm-12 roomtype_name">
                                <h5>{{ $indoor->name }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card room_add">
                    <div class="">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @php
                                    $addDective = $indoorBed[$indoor->id];
                                    if($addDective <= 0) {
                                        $addDective = 'not-active';
                                    }
                                @endphp
                                <div class="demo-google-material-icon indoor_add">
                                    <a href="{{URL::to('indoor/create/'.encrypt($indoor->id))}}" class="{{$addDective}}">
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @foreach($indoor->TypeshasManyRooms as $key=>$patient)
                    @php
                        $roomData = $patient->getIndoorBookData;
                        $invoice = $roomData && $roomData->is_final_invoice ? $roomData->is_final_invoice : 0;
                        $invoice = ($invoice == 0) ? 'incomplete-invoice' : '';
                    @endphp
                    @if(!empty($roomData))
                        <div class="card p-3 patient_name {{$invoice}}">
                            <div class="row">
                                <div class="col-md-9">
                                    @php
                                        $discharge = $roomData->is_discharge_card;
                                        $discharge = ($discharge == 0) ? '' : 'discharge';
                                    @endphp
                                    <div class="{{$discharge}}">
                                        <div class="p-name">
                                            <a id="patient_name_display"
                                                data-id="{{encrypt($roomData->id)}}"
                                                role="button"
                                                data-toggle="collapse"
                                                data-target="#collapse_{{$roomData->id}}"
                                                aria-expanded="true"
                                                aria-controls="collapse_{{$roomData->id}}"
                                                href="#collapseOne_{{$roomData->id}}">
                                                    {{-- <span class="p-name"> --}}
                                                        {{ ucwords(strtolower($roomData->getPatientsDetails['name'])) }}
                                                    {{-- </span> --}}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 do-not-print">
                                    <div class="header custom-dropdown">
                                        <ul class="header-dropdown">
                                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                    <i class="zmdi zmdi-more"></i> </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    @php
                                                        $dischargeCard = $roomData->is_discharge_card;
                                                    @endphp
                                                    <li>
                                                        <a href="{{URL::to('indoor/'.encrypt($roomData->id).'/bookingedit')}}" class="room-registration">
                                                            Room Registration
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="sticker-link"  data-toggle="modal" data-target="#sticker-modal" data-date="{{$roomData->doa_date}}" data-appointmentid="{{isset($roomData->getPatientsDetails->getAppointment['id']) ? encrypt($roomData->getPatientsDetails->getAppointment['id']) : ''}}">
                                                            Sticker
                                                        </a>
                                                    </li>
                                                    <li>
                                                        @if($dischargeCard == 0)
                                                            <a href="{{URL::to('indoor/discardcreate/'.encrypt($roomData->id))}}" class="dischargecard" >
                                                                Discharge
                                                            </a>
                                                        @else
                                                            <a href="{{URL::to('indoor/'.encrypt($roomData->id) .'/discardedit/')}}" class="dischargecard" >
                                                                Discharge
                                                            </a>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        @php
                                                            $isInvoice = $roomData->is_invoice;
                                                        @endphp
                                                        @if($isInvoice == 0)
                                                            <a href="{{URL::to('indoor/invoicecreate/'.encrypt($roomData->id))}}" class="invoice-link">
                                                                Invoice
                                                            </a>
                                                        @else
                                                            <a href="{{URL::to('indoor/'.encrypt($roomData->id) .'/invoiceedit/')}}" class="invoice-link" >
                                                                Invoice
                                                            </a>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        <a class="deposit-link" data-toggle="modal" data-target="#depositModal" data-backdrop="static" data-keyboard="false" id="{{$roomData->patient_id}}" data-id="{{ encrypt($roomData->id) }}">
                                                            Deposit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a data-id="{{encrypt($roomData->id)}}" class="directdischarge dischargecard">Direct Discharge</a>
                                                    </li>

                                                    <li id="agreements">
                                                        <a>Agreements</a>
                                                        <i class="material-icons">chevron_right</i>
                                                        <div class="{{$class}}">
                                                        <ul class="agreement-menu">
                                                            <li>
                                                                <a data-id="{{$roomData->patient_id}}" class="admistion-consent">Admistion consent</a>
                                                            </li>
                                                            <li>
                                                                <a data-id="{{ encrypt($roomData->patient_id) }}" class="fetal-reducation">Fetal Reducation</a>
                                                            </li>
                                                            <li>
                                                                <a data-id="{{ encrypt($roomData->patient_id) }}" class="tl-recanalisation">TL Recanalisation</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/1st_lscs_and_2nd_ftnd.pdf', null) }}" target="_blank">LSCS & FTND</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/blood_consent.pdf', null) }}" target="_blank">Blood Consent</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/delivery_consent_for_room.pdf', null) }}" target="_blank">Delivery Consent for Room</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/hystrolapro.pdf', null) }}" target="_blank">Hystrolapro</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/induction_of_lab_consent.pdf', null) }}" target="_blank">Induction of Lab Consent</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/lap_tl_contsent.pdf', null) }}" target="_blank">Lap TL Contsent</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/os_tightning.pdf', null) }}" target="_blank">OS Tightning</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/tlh_consent.pdf', null) }}" target="_blank">TLH  Consent</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ cdnUrl('public/upload/indoor/vh_consent.pdf', null) }}" target="_blank">VH Consent</a>
                                                            </li>
                                                        </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @php
                                        $procedureId = !empty($roomData->procedure_id) ? explode(',',$roomData->procedure_id) : null;
                                        $procedureData = [];
                                        if($procedureId){
                                            $procedureData = array_map(function ($q) use ($proceduresId,$procedureData) {
                                                $procedureData = $proceduresId[$q];
                                                return $procedureData;
                                            }, $procedureId);
                                        }
                                        $procedureData = implode(',',$procedureData);
                                    @endphp
                                    @if (!empty($procedureData))
                                        <div class="procedure-data">
                                            <a  id="patient_name_display"
                                                data-id="{{encrypt($roomData->id)}}"
                                                role="button"
                                                data-toggle="collapse"
                                                data-target="#collapse_{{$roomData->id}}"
                                                aria-expanded="true"
                                                aria-controls="collapse_{{$roomData->id}}">
                                                {{ strtoupper($procedureData) }}

                                                <!-- {{ $subString = substr($procedureData, 0, 38) }} -->
                                            </a>
                                            @if (strlen ($subString) < strlen($procedureData))
                                                ...
                                                <a href="{{URL::to('indoor/' . encrypt($roomData->id) . '/bookingedit')}}" class="indoor-read-more">More</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <span>Room No: </span>{{$patient->room_no}} {{!empty($patient->remark) ? '( Floor - '.$patient->remark.')' : ''}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card p-3 patient_name patient-room p-empty" data-url="{{URL::to('indoor/create/'.encrypt($indoor->id).'/'.encrypt($patient->id))}}">
                            <div class="row justify-content-center">
                                {{-- <div class="col-md-9"> --}}
                                        {{$patient->room_no}}
                                        {{!empty($patient->remark) ? '( Floor - '.$patient->remark.')' : ''}}
                                {{-- </div> --}}
                                {{-- <div class="col-md-3">
                                    <div class="demo-google-material-icon indoor_add">
                                        <a href="{{URL::to('indoor/create/'.encrypt($indoor->id).'/'.encrypt($patient->id))}}">
                                            <i class="material-icons">add</i>
                                        </a>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
@stop

@section('modal')
    <div class="modal fade indoor-deposit" id="depositModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-sm" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header text-center">
                    <h5 class="title" id="defaultModalLabel">Add / Update Deposit</h5>
                    <button type="button" class="close deposit-close-button" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- body -->

                {{Form::open(['class'=>'', 'id'=>'store_deposite'])}}
                <div class="modal-body text-center">
                    <span class="form-error-msg"></span>
                    <div class="row form-group">
                        <div class="col-md-3 text-left unik-lbl-spn">
                            Name
                        </div>
                        <div class="col-md-8 text-left">
                            <div class="patientname"></div>
                        </div>
                    </div>
                    {{Form::hidden('current_deposit', !empty($patientdata->id) ? encrypt($patientdata->id) : null, [
                        'id' => 'current_deposit'
                    ])}}
                    {{Form::hidden('procedure_id', null, [
                        'id' => 'procedure_id'
                    ])}}
                    <div class="row form-group">
                        <div class="col-md-3 text-left unik-lbl-spn">
                            Deposit
                        </div>
                        <div class="col-md-8 text-left">
                            {{Form::label('current_deposit', null, ['class' => 'current_deposit'])}}
                        </div>
                        <label class="switch">
                            {{Form::checkbox('deposit-type', null, true, ['class' => 'deposit-type','id' => 'deposit-type'])}}
                            <span class="demo-google-material-icon">
                                <i class="material-icons" id="add-edit-icon" onclick="changeDepositeType(this)">add</i>
                            </span>
                        </label>
                    </div>

                    <div class="row form-group text-left">
                        <div class="col-md-3 unik-lbl-spn">
                            Select Payment Type
                        </div>
                        <div class="col-md-9">
                            {{Form::select('payment_type',['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],null, ['class'=>'form-control payment-type','placeholder'=>'Select Payment Type'])}}
                            <span class="payment-type-error form-error-msg"></span>
                        </div>
                    </div>
                    
                    <div class="row lessdepositamt form-group text-left unik-lbl-spn">
                        <div class="col-md-3">
                            {{Form::label('add-edit-deposit','Add Deposit', [
                                'id' => 'add-edit-deposit'
                            ])}}
                        </div>
                        
                        <div class="col-md-9">
                            {{Form::text('deposit_amount', null, [
                                'class'=>'form-control deposit_amount',
                                'placeholder'=>'Enter Deposit in Rs.',
                                'maxlength' => 6,
                                'oninput' => 'checkAmount(this.value)'
                            ])}}
                            <span class="deposit_amount form-error-msg"></span>
                        </div>
                    </div>

                    <div class="row form-group text-left">
                        <div class="col-md-3 unik-lbl-spn">
                            {{Form::label('comment','Remark')}}
                        </div>
                        <div class="col-md-9">
                            {{Form::text('comment',null, ['class'=>'form-control comment','placeholder'=>'Enter Description'])}}
                        </div>
                    </div>

                    <span id="depositerrmsg"></span>
                    {{Form::hidden('selected_patient_id','',['class'=>'selected_patient_id'])}}
                </div>

                <!-- footer -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary waves-effect submit-button submit">Save</button>
                    <button type="submit" class="btn btn-primary waves-effect deposit-print submit" value="1">Save & Preview</button>
                </div>

                {{Form::close()}}
                <div class="patient-deposits table-responsive active">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sticker-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog sticker-modal-width" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Register Date</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline','id'=>'next-appointment'])}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                Date
                            </div>
                            <div class="col-md-5">
                                {{Form::text("date",\Carbon\Carbon::now()->format('d-m-Y'),['class'=>'form-control datetimepicker re-date','required'])}}
                            </div>
                            {{Form::hidden('appointment_id','',['class'=>'appointment-id'])}}
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <span class="form-error-msg r-date d-none">The date field is required.</span>
                            </div>
                        </div>
                        <!-- footer -->
                        <div class="modal-footer mt-3 sticker-modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary waves-effect sticker sticker-print">Print</button>
                        </div>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
    
@stop

@section('page-script')
    <script src="{{url('js/indoorprint.js')}}"></script>
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script type="text/javascript">
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time:false,
            weekStart: 1
        });
        var bookingId = '';
        var patientId = '';
        var adddeposit = '';
        var deposit = '';
        var da = '';
        var totaldeposit = '';
        var lessdeposit ='';
        var totaldepositamt = '';
        var date = '';
        var label_name = '';

        $(document).on('dblclick', '#patient_name_display', function(event) {
            var Id = $(this).data('id');
            if(typeof(Id) !== 'undefined'){
                var url = 'indoor/'+Id+'/bookingedit';
                window.location.href = url;
            }
        });
        $(document).on('dblclick', '.patient-room', function(event) {
            var url = $(this).data('url');
            if(typeof(url) !== 'undefined'){
                window.location.href = url;
            }
        });
        
        $('.deposit-close-button').click(function() {
            resetAddDepositForm();
        });

        $('.deposit-link').click(function () {
            var patientId = this.id;
            var bookingId = $(this).data('id');
            $('.selected_patient_id').val(patientId);
            // changeDepositeType();
            $('.submit').attr('disabled', false);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{URL::to('get-bookingpatients')}}",
                type: "POST",
                data: {
                    patient_id: patientId,
                    booking_id: bookingId
                },
                dataType: 'json',
            }).done(function(data) {
                var deposite = data.deposite;
                var patientname = data.patientname;
                var depositData = data.depositData;
                $('#procedure_id').val(data.procedure_id);
                // $('label[for=current_deposit]').html(deposite.total);
                (deposite != null) ? $('label[for=current_deposit]').html(deposite.total) : $('label[for=current_deposit]').html('0');
                (deposite != null) ? $('#current_deposit').val(deposite.total) : $('#current_deposit').val('0');
                $('.patientname').text(': ' + patientname);
                if(deposite != null){
                    $('.deposit').text(deposite.total);
                    var ID =  $('.selected_patient_id').val(deposite.patient_id);
                }
                var i = 1;
                $('.patient-deposits').html('<table class="table m-b-0 table-hover" id="patient-deposit-list">\n' +
                    '<thead>\n' +
                    '<tr>\n' +
                    '<th>Date</th>\n' +
                    '<th>Amount</th>\n' +
                    '<th>Payment Type</th>\n'+
                    '<th>Total</th>\n' +
                    '<th></th>' +
                    '</tr>\n' +
                    '</thead>\n' +
                    '<tbody class="patient-dposit-data"></tbody>'
                   );
                if (depositData.length != 0) {
                    $.each( depositData, function( key, value ) {
                        var date = new Date(value.created_at);
                        var month = ((((date.getMonth() + 1).toString()).length) == 1) ? (0 + (date.getMonth() + 1).toString()) : (date.getMonth() + 1);
                        var depositDate = date.getDate() + '-' + month + '-' + date.getFullYear();
                        var paymenttype = "";

                        if(value.payment_type == 1) {
                            paymenttype = "Swipe";
                        }else if(value.payment_type == 2) {
                            paymenttype = "Cash";
                        }else if(value.payment_type == 3) {
                            paymenttype = "Cheque";
                        }else if(value.payment_type == 4) {
                            paymenttype = "UPI";
                        }else if(value.payment_type == 5) {
                            paymenttype = "NEFT";
                        }
                        $('.patient-dposit-data').val('');
                        $('.patient-dposit-data').append('<tr><td>'+ depositDate +'</td><td>'+ (value.case_type == 'Credit' ? '+&nbsp;&nbsp;&nbsp;&nbsp;' : '-&nbsp;&nbsp;&nbsp;&nbsp;') + value.amount +'</td><td>'+paymenttype+'</td><td>'+ value.total +'</td><td><i class="material-icons print-deposit" data-id="'+ value.id +'">print</i></td></tr>');
                    });
                } else {
                    $('.patient-dposit-data').append('<tr><td colspan="10" class="text-center">No records available</td></tr>');
                }
            });
        });
        $(document).on('click','.print-deposit',function(){
           var depositId = $(this).data('id');
            depositId = depositId + '&isprint=1';
            printDepositData(depositId);
        });
        $(document).on('click','.sticker-link',function(){
           date = $(this).data('date');
           var aId = $(this).data('appointmentid');
           date = moment(date).format('DD-MM-YYYY');
           $('.re-date').val(date);
           $('.appointment-id').val(aId);
        });
        $(document).on('click','.sticker-print',function(){
            var date = $('.re-date').val();
            var appointmentId = $('.appointment-id').val();
            $('.r-date').addClass('d-none');
            if(date == ''){
                $('.r-date').removeClass('d-none');
                return true;
            }
            $.ajax({
                url: "{{URL::to('appointment-sticker')}}",
                data:{appointmentId:appointmentId,date:date,'is_indoor':1},
                dataType: 'json',
            }).done(function(data) {
                w = window.open(window.location.href,"_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
                $('#sticker-modal').modal('hide');
            });
        });
        
        function printDepositData(depositId) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "indoor/printdeposit/" + depositId,
                dataType: 'json',
                type: 'POST',
                data:depositId
            }).done(function(data) {
                if (data.status == 1) {
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                } else {
                    $('#depositModal').modal('hide');
                    var url = "{{URL::to('/indoor')}}";
                    window.location.href = url;
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
        }
        $(document).on('click','.directdischarge',function(){
            bookingId = $(this).data('id');
            showConfirmMessage();
        });
        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to Direct Discharge!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, Direct Discharge!",
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                storeDirectDischarge();
                $('.showSweetAlert').hide();
                location.reload();
                // swal("Discharge!", "You have direct discharge.", "success");
            });
        }
        function storeDirectDischarge(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "indoor/storedirectdischarge/" + bookingId,
                type: "POST",
                dataType: 'json',
            }).done(function() {
                location.reload();
            }).fail(function() {
            });
        }
        $(".deposit").keypress(function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                $("#depositerrmsg").html("Enter digits only").show().fadeOut("slow");
                return false;
            }
        });
        $(document).on('click','.submit',function(e){
            e.preventDefault();
            var depositData = $('#store_deposite').serialize();
            patientId = $('.selected_patient_id').val();
            var currentDeposit = $('#current_deposit').val();
            deposit = depositData + '&current_deposit=' + currentDeposit;
            if (this.value == 1) {
                depositData = depositData + '&isprint=1';
            }
            storeDepositData(depositData);
        });
        function storeDepositData(depositData) {
            var depositType = ($('input[type="checkbox"][name="deposit-type"]').prop('checked') == true) ? 'credit' : 'debit';
            $('.payment-type-error').text('');
            $('.deposit_amount').text('');
            if($('.deposit_amount').val() == '') {
                $('.deposit_amount').text('Please enter amount.');
                return false;
            }
            if($('select.payment-type').val() == ''){
                $('.payment-type-error').text('Please select payment type.');
                return false;
            }
            if($('.deposit_amount').val() <= 0) {
                $('.deposit_amount').text('Please enter amount greater then 0.');
                return false;
            }
            if(depositType == 'debit') {
                if (parseInt($('.deposit_amount').val()) > parseInt($('.current_deposit').text())) {
                    $('.deposit_amount').text('Deposite Amount can not be greater than current deposit amount.');
                    return false;
                }
            }
            depositData = depositData + '&deposit_type=' + depositType;
            $('.submit').attr('disabled', true);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "indoor/storedeposit/" + patientId,
                dataType: 'json',
                type: 'POST',
                data:depositData
            }).done(function(data) {
                if (data.status == 1) {
                    resetAddDepositForm();
                    swal({ 
                        title: 'Success',
                        text: 'Deposit has been ' + depositType + 'ed.',
                        type: 'success' 
                    });
                }
                if (data.status == 2) {
                    w = window.open(window.location.href,"_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    window.location.reload();
                }
                if (data.status == 4) {
                    $('.deposit_amount').text(data.errors);
                    $('.submit').attr('disabled', false);
                }
                if (data.status == 3) {
                    swal({ 
                        title: 'Oops',
                        text: 'Something went wrong',
                        type: 'error' 
                    },
                    function(){
                        window.location.reload();
                    });
                }
            }).fail(function(error) {
            });
        }
        function resetAddDepositForm() {
            $('#depositModal').modal('hide');
            $('#store_deposite').trigger('reset');
            $('#deposit-type').prop('checked', true);
            $('label[for=add-edit-deposit]').html('Add Deposit');
            $('.payment-type').selectpicker('refresh');
            $('#add-edit-icon').text('add');
            $('.form-error-msg').text('');
        }
        function changeDepositeType() {
            if ($('#add-edit-icon').text() == 'add'  && $('#current_deposit').val() > 0){

                $('#add-edit-icon').text('close');
                $('#deposit-type').prop('checked', true);
                $('label[for=add-edit-deposit]').html('Update Deposit');
            } else {

                $('#add-edit-icon').text('add');
                $('#deposit-type').prop('checked', false);
                $('label[for=add-edit-deposit]').html('Add Deposit');
            }
            // deposit.classList.toggle("fa-thumbs-down");
        }
        function checkAmount(value) {
            $('.deposit_amount').val(validAmount(value));
        }
        function validAmount(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
        }
        // $(document).on('click','.indoor-print',function(){
        // //     w=window.open('Print_Page', 'scrollbars=yes');        
        // //     var myStyle = '<link rel="stylesheet" href="{{url("assets/css/follicular_report.css")}}" />';
        // //    console.log(myStyle);
        // //     w.document.write(myStyle + jQuery('.follicular_table_print').html());
        // //     w.document.close();
        // //     w.print();
        // var divContents = $('.indoor_detail').html();  
        //     var printWindow = window.open('', '');  
        //     printWindow.document.write('<html><head><title></title>');  
        //     // printWindow.document.write('<link rel="stylesheet" href="{{url("assets/css/follicular_report.css")}}" />');  
        //     printWindow.document.write('<style type="text/css">');
        //     var follicular_css =  '.sticker-modal-width{width: 350px !important;} .do-not-print{display:none !important;}'+
        //     '.sticker-modal-footer{display: block !important;text-align: center !important;}.patient-room{cursor: pointer;}.p-name{color: #F96332 !important;}'+
        //     '.room-head{background-color: #ccd4dc !important;-webkit-print-color-adjust: exact;}.p-empty{border: 1px solid #ccd4dc !important;}'+
        //     '@media print { {page-break-after: always;}@page { margin-top : 50px;margin-bottom: 100px;} }';
        //     printWindow.document.write(follicular_css);
        //     printWindow.document.write('</style>');
        //     printWindow.document.write('</head><body>');  
        //     printWindow.document.write(divContents);  
        //     printWindow.document.write('</body></html>');  
        //     printWindow.document.close();  
        //     printWindow.print();  
        // });
        $(document).on('click','.indoor-print',function(){
            var FloorId = $(this).val();
            console.log(FloorId);
            $.ajax({
                url: "indoor/indoor_preview/" + FloorId,
                type: "GET",
                dataType:'json',
            }).done(function(data) {
                if (data.status == 1) {
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {
            });
        });
    </script>
@stop
