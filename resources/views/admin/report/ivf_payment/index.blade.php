@extends('layouts.main')
@section('parentPageTitle', 'IVF Payment')
@section('title', 'IVF Payment')

@section('page-style')
<style>
    .ivf-payment-table{
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    .text-wrraping{
        width: 460px !important;
        white-space: inherit !important;
        line-height: 20px !important;
    }
    .card .body .table td, .card .body .table th{
        white-space: inherit !important;
        /* width: 100% !important; */
    }
    .table-hover tbody tr:hover {
        background-color: rgb(151 131 131 / 8%) !important;
    }
</style>

@stop

@section('content')

    <div class="row clearfix sms-manager">
        <div class="card">
            <div class="header">
                <h2><strong>IVF Payment</strong></h2>
                {{-- <ul class="header-dropdown">
                    <li>
                        {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-sms-report">
                                Print
                            </button> </a> --}}</li>
                </ul> --}}
            </div>
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <form method="post" autocomplete="off" action="">
                                <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                            </form>
                        </div>
                        <div class="col-md-3 anc">
                            {{ Form::select('patient_id',$patients,'',[
                                'class'=>'form-control select-padding-0 patient-id',
                                'placeholder'=>'Select Patient',
                                'id' => 'patient_id',
                                'data-live-search' => 'true'
                            ])}}
                        </div>
                        <div class="col-md-6 text-right">

                            {{-- <a href="{{url('ivf-payment-report/remaining_payment')}}">
                                <button class="btn btn-primary">
                                    Remaining Payment
                                </button>
                            </a> --}}
                             {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-ivf-payment-report">
                                    Print
                                </button> </a> --}}</div>
                    </div>
                </div>
                <div class="tab-content m-t-10">
                    <div class="ivf-payment-data">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        var page = '';
        var pId = '';

        $(document).ready(function(){
            $(".daterange").daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y'
                }
            });

            var date = $('.daterange').val();
            var qstring = 'date='+date;
            getIVFPaymentReport(qstring);

            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFPaymentReport(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFPaymentReport(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page= $(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFPaymentReport(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                pId = $(this).val();
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFPaymentReport(qstring);
            });

            $(document).on('click', '.print-ivf-payment-report', function () {
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date+'&isprint=1';
                getIVFPaymentReport(qstring);
            });

            $(document).on('click','.change-payment',function(){
                var id = $(this).data('paymentid');
                getIVFPayment(id);
                $(this).addClass('disabled');
            });

            $(document).on('click','.save-payment',function(){
                var payemntId = $(this).data('paymentid');
                var id = $(this).data('id');
                var cycleNo = $('#cycle-no-'+id).val();
                var payment = $('#payment-'+id).val();
                var package = $('#package-'+id).val();
                var pType = $('select#payment-type-'+id).val();
                var time = $('#time-'+id).val();
                var condition = $('#condition-'+id).val();
                var data = {id:payemntId,cycle_no:cycleNo,payment:payment,package:package,payment_type:pType,time:time,condition:condition};
                updateIvfPayment(data);
                $(this).addClass('disabled');
            });

        });

        // get all referance doctor data
        function getIVFPaymentReport(qstring){
            // $('.ivfpayment-loader').removeClass('d-none');
            // $('.ivfpayment').addClass('d-none');
            // $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ivf-payment-report')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.ivf-payment-data').html(data.ivfpayment);
                    // $('.ivfpayment-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.ivfpayment);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }

        function getIVFPayment(paymentId){
            $.ajax({
                url: "{{URL::to('get-ivf-payment-report')}}"+'/'+paymentId,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    data = data.data;
                    setTextBox('cycle-no-'+data.id,data.cycle_no,'cycle_no',1);
                    setTextBox('payment-'+data.id,data.payment,'payment',1);
                    setTextBox('package-'+data.id,data.package,'package',1);
                    setTextBox('time-'+data.id,data.time,'time',1);
                    setTextBox('condition-'+data.id,data.condition,'condition',1);
                    setTextBox('date-'+data.id,data.created_at,'date',2);
                    setTextBox('payment-type-'+data.id,data.payment_type,'payment_type',3);
                    $('.change-ivfpayment-'+data.id).text('Save');
                    $('.change-ivfpayment-'+data.id).removeClass('change-payment');
                    $('.change-ivfpayment-'+data.id).addClass('save-payment');
                    $('.change-ivfpayment-'+data.id).removeClass('disabled');
                }
            }).fail(function() {

            });
        }

        function setTextBox(textClass,value,name,type){
            if(value == null || typeof value == 'undefined'){
                value = "";
            }
            if(type == 1){
                var tData = "<input type='text' name="+name+" value='"+value+"' class='form-control "+textClass+"' id='"+textClass+"'>";
            }
            // if(type == 2){
            //     var tData = "<input type='text' name="+name+" value='"+value+"' class='form-control datetimepicker'>";
            // }
            if(type == 3){
                var tData = "<select name="+name+" class='form-control' id='"+textClass+"'><option value=''>Payment Type</option><option value='1'>Card</option><option value='2'>Cash</option><select>";
            }
           $('.'+textClass).html(tData);
           if(type == 3){
                $('#'+textClass).val(value);
                $('#'+textClass).selectpicker('refresh');
           }
        }

        function updateIvfPayment(data){
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{URL::to('update-ivf-payment-report')}}",
            type: "POST",
            dataType: 'json',
            data:data
            }).done(function(data) {
                if(data.status == 1){
                    var data = data.data;
                    setTextBoxToText(data.id,data.cycle_no,data.payment,data.package,data.payment_type,data.time,data.condition);
                    showNotification('bg-blue', 'IVF payment successfully updated.', 'bottom', 'right', "", "");
                }
            }).fail({

            });

        }

        function setTextBoxToText(payemntId,cycleNo,payment,package,paymentType,time,condition){
            $('.cycle-no-'+payemntId).text(cycleNo);
            $('.payment-'+payemntId).text(payment);
            $('.package-'+payemntId).text(package);
            paymentType = paymentType == 1 ? 'Card' : 'Cash';
            $('.payment-type-'+payemntId).text(paymentType);
            $('.time-'+payemntId).text(time);
            $('.condition-'+payemntId).text(condition);
            $('.change-ivfpayment-'+payemntId).text('Change');
            $('.change-ivfpayment-'+payemntId).removeClass('disabled');
        }
    </script>
@stop
