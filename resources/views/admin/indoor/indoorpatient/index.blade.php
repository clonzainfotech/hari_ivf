@extends('layouts.main')
@section('parentPageTitle', 'Indoor')
@section('title', 'Indoor')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
@stop

@section('content')

    <div class="row clearfix indoor-summary">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Indoor Patient List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <button class="btn btn-primary print">
                                Print
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="card patient_list">
                        <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <input type="text" class="form-control daterange" placeholder="Select Date">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    {{ Form::select('patient_id',$patients,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    {{Form::select('room_type', $indoorType, '',[
                                        'class'=>'form-control select-padding-0 room_type',
                                        'placeholder'=>'Select Indoor Type',
                                    ])}}
                                </div>
                                <div class="col-md-3">
                                    <form method="post" autocomplete="off" action="">
                                        <ul class="nav nav-tabs padding-0">
                                            <div class="input-group">
                                                <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no" autocomplete="off">
                                                <span class="input-group-addon search-border">
                                                    <i class="zmdi zmdi-search"></i>
                                                </span>
                                            </div>
                                        </ul>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <ul class="nav nav-tabs padding-0">
                                        {{ Form::select('reference_doctor',$referenceDoctors,'',[
                                            'class'=>'form-control select-padding-0 reference-doctor-id',
                                            'placeholder'=>'Select Doctor',
                                            'id' => 'reference_doctor',
                                            'data-live-search' => 'true'
                                        ])}}
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <ul class="nav nav-tabs padding-0">
                                        {{ Form::select('procudure_search',$procedures,'',[
                                            'class'=>'form-control select-padding-0',
                                            'placeholder'=>'Select Procedure',
                                            'id' => 'procudure_search',
                                            'data-live-search' => 'true'
                                        ])}}
                                    </ul>
                                </div>
                            </div>
                        <div class="indoor-data table-responsive active">
                            <!-- table data here include -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    <div class="row">
                        <div class="page-loader-wrapper medicine-loader">
                            <div class="loader">
                                <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- sticker modal --}}
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
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="{{url('js/indoorprint.js')}}"></script>
    <script type="text/javascript">

        var page = '';
        var patientId = '';
        var date = '';
        var roomType = '';
        var search = '';
        var referenceDoctorId = '';
        var procudure_search = '';
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time:false,
            weekStart: 1
        });

        $('.daterange').daterangepicker({
            startDate: moment().subtract(30, 'day'),
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });

        date = $('.daterange').val();
        qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;

        $(document).ready(function() {
            getIndoorData(qstring);

            if (date != '') {
                $('.daterange').val(date);
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
            }

            $('.daterange').change(function(){
                date = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });


            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });

            $(document).on('click','.btn-success',function(e){
                e.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });
            $(document).on('change','select.reference-doctor-id',function(){
                referenceDoctorId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });
            $(document).on('change','select#procudure_search',function(){
                procudure_search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });

            $(document).on('change', 'select.room_type',function(e){
                e.preventDefault();
                roomType = $(this).val();
                // charge_text = $('select.charge_type option:selected').text();
                qstring = 'page='+page+'&patient_id='+patientId + '&date='+date+'&room_type='+roomType+'&search='+search+'&reference_doctor='+referenceDoctorId+'&procudure_search='+procudure_search;
                getIndoorData(qstring);
            });

            $(document).on('click','.print',function(){
                var isprint= 1;
                $.ajax({
                    url: "{{URL::to('patient-detail')}}?" + qstring,
                    data:{
                        is_print: isprint
                    },
                    dataType: 'json',
                }).done(function(data) {
                    w = window.open(window.location.href,"_blank");
                    w.document.open();
                    w.document.write(data);
                    w.document.close();
                    w.window.print();
                });
            });
            $(document).on('click','.print-deposit',function(){
                var depositId = $(this).data('id');
                depositId = depositId + '&isprint=1';
                printDepositData(depositId);
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
            $(document).on('click','.deposit-link',function(){
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
        });

        function getIndoorData(qstring){
            $('.indoorpatient-loader').removeClass('d-none');
            $('.indoorpatient').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('patient-detail')}}?" + qstring,
                dataType: 'json',
            }).done(function(data) {
                $('.indoor-data').html(data.indoorData);
                $('.indoorpatient-loader').addClass('d-none');
            }).fail(function(error) {
            });
        }

        function changeDepositeType(){
            if($('#add-edit-icon').text() == 'add'  && $('#current_deposit').val() > 0){
                $('#add-edit-icon').text('close');
                $('#deposit-type').prop('checked', true);
                $('label[for=add-edit-deposit]').html('Update Deposit');
            }else{
                $('#add-edit-icon').text('add');
                $('#deposit-type').prop('checked', false);
                $('label[for=add-edit-deposit]').html('Add Deposit');
            }
            // deposit.classList.toggle("fa-thumbs-down");
        }
        function checkAmount(value) {
            $('.deposit_amount').val(validAmount(value));
        }
        function validAmount(value){
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            }else{
                return value;
            }
        }
        function resetAddDepositForm() {
            $('#depositModal').modal('hide');
            $('#store_deposite').trigger('reset');
            $('#deposit-type').prop('checked', true);
            $('.payment-type').selectpicker('refresh');
            $('label[for=add-edit-deposit]').html('Add Deposit');
            $('#add-edit-icon').text('add');
            $('.form-error-msg').text('');
        }
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

        function storeDepositData(depositData) {
            var depositType = ($('input[type="checkbox"][name="deposit-type"]').prop('checked') == true) ? 'credit' : 'debit';
            $('.payment-type-error').text('');
            $('.deposit_amount').text('');
            if ($('.deposit_amount').val() == '') {
                $('.deposit_amount').text('Please enter amount.');
                return false;
            }
            if ($('select.payment-type').val() == ''){
                $('.payment-type-error').text('Please select payment type.');
                return false;
            }
            if ($('.deposit_amount').val() <= 0) {
                $('.deposit_amount').text('Please enter amount greater then 0.');
                return false;
            }
            if (depositType == 'debit') {
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
        $(document).on('dblclick', '#bookpatient-table tbody tr', function(event) {
            var id = $(this).data('id');
            if (typeof(id) !== 'undefined') {
                var url = 'indoor/'+id+'/bookingedit';
                window.location.href = url;
            }
        });
</script>
@stop
