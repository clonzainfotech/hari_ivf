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
                <h2><strong>Remaining Payment</strong></h2>
            </div>
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <form method="post" autocomplete="off" action="">
                                <input type="text" class="form-control daterange" placeholder="Select Date">
                            </form>
                        </div>
                        <div class="col-md-7"></div>

                         <div class="">
                             {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-ivf-remaining-payment">
                                    Print
                                </button> </a> --}}<a href="{{url('ivf-payment-report')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </div>
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
            getIVFRemainingPayment(qstring);

            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFRemainingPayment(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFRemainingPayment(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page= $(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date;
                getIVFRemainingPayment(qstring);
            });

            $(document).on('click', '.print-ivf-remaining-payment', function () {
                qstring = 'page='+page+'&patient_id='+pId+'&date='+date+'&isprint=1';
                getIVFRemainingPayment(qstring);
            });
        });

        // get all referance doctor data
        function getIVFRemainingPayment(qstring){
            // $('.ivfpayment-loader').removeClass('d-none');
            // $('.ivfpayment').addClass('d-none');
            // $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ivf-remaining-payment')}}?"+qstring,
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


    </script>
@stop
