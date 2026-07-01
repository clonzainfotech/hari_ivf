@extends('layouts.main')
@section('parentPageTitle', 'Call Reminder')
@section('title', 'Call Reminder')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <style>
         .btn-neutral:hover, .btn-neutral:focus{
             color:#00cfd1 !important;
         }
         .patient .dropdown-menu ul{
            max-height: 142.2px !important;
         }
         .call-patients .dropdown-menu ul{
            max-height: 95px !important;
         }
         .call-reminder-edit:hover{
             color: #267072 !important;
         }
         .text-wrraping{
            width: 460px !important;
            white-space: inherit !important;
            line-height: 20px !important;
         }
         .card .body .table td, .card .body .table th{
            white-space: inherit !important;
         }
    </style>
@stop
@section('content')

<div class="row clearfix">
    <div class="col-md-12">
        <div class="card call-reminder">
            <div class="header">
                <h2><strong>Add Call Reminder</strong></h2>
            </div>

            <div class="body">
                <div class="col-md-12 col-lg-12">
                    {{Form::open([
                        'class' => 'form appointment-form',
                        'id' => 'call-reminder'
                    ])}}
                        <div class="row">
                            <div class="col-sm-6">
                                {{Form::select('patient', $patients, '',[
                                    'class' => 'form-control select-padding-0 patient call-patients',
                                    'placeholder' => 'Select Patient',
                                    'id' => 'patient',
                                    'data-live-search' => 'true',
                                    'required'
                                ])}}
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        Date : &nbsp;
                                    </span>
                                    {{Form::text('date', \Carbon\Carbon::now()->format('D d M Y'), [
                                        'class' => 'form-control date',
                                        'required'
                                    ])}}
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::textarea('response','', [
                                        'class' => 'form-control no-resize remark call-response',
                                        'placeholder' => 'Response',
                                        'rows' => '5'
                                    ])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {{Form::submit('Submit',[
                                    'class' => 'btn btn-primary'
                                ])}}
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix call-reminder">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>Call Reminder List</strong></h2>
            </div>
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control daterange" placeholder="Select Date">
                        </div>
                        <div class="col-md-4">
                            {{Form::select('patient_id', $patients, '',[
                                'class' => 'form-control select-padding-0 call-patients patient-id',
                                'placeholder' => 'Select Patient',
                                'id' => 'patient_id',
                                'data-live-search' => 'true',
                                'required'
                            ])}}
                        </div>
                        <div class="col-md-3">
                            <ul class="nav nav-tabs padding-0">
                                <div class="input-group">
                                    <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no">
                                    <span class="input-group-addon search-border">
                                        <i class="zmdi zmdi-search"></i>
                                    </span>
                                </div>
                            </ul>
                        </div>
                        <div class="col-md-1">
                            {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-call-data m-0">
                                    Print
                                </button> </a> --}}</div>
                    </div>
                </div>
                <div class="tab-content m-t-10">
                    @if(Session::has('msg'))
                    <div class="alert alert-success">
                        <strong>Success!</strong> {{Session::get('msg')}}
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

                    <div class="call-reminder-data table-responsive active">
                        <!-- table data here include -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('page-script')
<script> 
$.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
$.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
</script>
<script type="text/javascript">
    
    $(document).ready(function() {

        var qstring = '';
        var page = '';
        var patientId = '';
        var category = '';
        
        $('.daterange').daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });
        
        var date = $('.daterange').val();
        var search =$('.search-mobile-number').val();
        var qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;

        $('.date').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
        });

        $(document).on('click','.cancelBtn',function(e){ 
            e.preventDefault();
            $('.daterange').val('');
            date = $('.daterange').val();
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
            getCallReminderData(qstring);
        });

        $(document).on('change','select.usg',function(e){
            e.preventDefault();
            usg = $(this).val();
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
            getCallReminderData(qstring);
        });

        $(document).on('click','.applyBtn',function(e){
            event.preventDefault();
            date = $('.daterange').val();
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
            getCallReminderData(qstring);
        });
        $(document).on('keyup','.search-mobile-number',function(){
            search = $(this).val();
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
            getCallReminderData(qstring);
        });

        getCallReminderData(qstring);

        $(document).on('click', '.pagination a',function(event){
            event.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
            getCallReminderData(qstring);
        });

        $(document).on('change', 'select.patient-id',function(){
            patientId = $(this).val();
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
            getCallReminderData(qstring);
        });

        $(document).on('click', '.print-call-data', function () {
            qstring = 'patient_id=' + patientId + '&date=' + date + '&page='+'&search='+search + page+'&isprint=1';
            getCallReminderData(qstring);
        });
        $('#call-reminder').submit(function(event) {
            event.preventDefault(); 
           
            var formData = $('#call-reminder').serialize();

            $.ajax({
                type: 'POST',
                url: "{{URL::to('/add-call-reminder')}}",
                dataType: 'json',
                data: formData
            }).done(function (data) {
                if (data.status == true) {
                    swal('Added!', data.message, 'success');
                    $('#call-reminder')[0].reset();
                    $('#patient').selectpicker('refresh');
                    page = 1;
                    qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
                    getCallReminderData(qstring);
                } else {
                    swal('Oops!', data.message, 'error');
                }

            }).fail(function () {

            });
        });

        $(document).on('click','.call-reminder-delete', function() {
            callReminderId = $(this).data('id');
            swal({
                title: 'Are you sure?',
                text: 'You want to delete this call reminder!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('/delete-call-reminder')}}",
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        call_reminder_id: callReminderId
                    },
                }).done(function(data) {
                    if (data.status == true) {
                        swal('Deleted!', data.message, 'success');
                        page = 1;
                        qstring = 'patient_id=' + patientId + '&date=' + date + '&page=' + page+'&search='+search;
                        getCallReminderData(qstring);
                    } else {
                        swal('Oops!', data.message, 'error');
                    }
                }).fail(function() {
                    
                });
            });
        });
    });

    function getCallReminderData(qstring){
        $.ajax({
            url: "{{URL::to('call-reminder')}}?" + qstring,
            dataType: 'json',
        }).done(function(data) {
            if(data.status == 1){
                $('.call-reminder-data').html(data.calldata);
            }
            if(data.status == 2){
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data.calldata);
                w.document.close();
                w.window.print();
            }
        }).fail(function() {
            
        });
    }

</script>
@stop
