@extends('layouts.main')
@section('parentPageTitle', 'Report')
@section('title', 'CA Expense Report')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@stop
@section('content')
    <div class="row clearfix report">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>CA Expense report</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a class="text-white">
                                <button class="btn btn-primary export-collection-report">
                                    Export
                                </button>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-collection-report">
                                    Print
                                </button> </a> --}}</li>
                        <li>
                            <a href="{{URL::to('bank')}}">
                                <button class="btn btn-primary">
                                    Bank
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group daterange">
                                        <form method="post" autocomplete="off" action="">
                                        {{ Form::text('daterange', '',  [
                                            'id' => 'daterange',
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Date',
                                            'data-date-container' => '#myModalId',
                                            'data-provide'=> 'datepicker',
                                            'autocomplete'=>'off'
                                        ]) }}
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-6">
                                    {{Form::select('select_bank_id',$bank_details,'',[
                                        'class'=>'form-control select-padding-0',
                                        'placeholder'=>'Select Bank',
                                        'id' => 'select_bank_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','2',true,[
                                        'id'=>'cash_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="cash_box">
                                        Cash
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','1','',[
                                        'id'=>'swipe_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="swipe_box">
                                        Swipe
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','5','',[
                                        'id'=>'neft_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="neft_box">
                                        NEFT
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','3','',[
                                        'id'=>'cheque_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="cheque_box">
                                        Cheque
                                    </label>
                                </div>
                                <div class="col-md-1 checkbox">
                                    {{Form::checkbox('report_payment_type','4','',[
                                        'id'=>'upi_box',
                                        'class'=>'report-payment-type',
                                    ])}}
                                    <label for="upi_box">
                                        UPI
                                    </label>
                                </div>
                            </div>
                        </div>
                    <div class="tab-content m-t-10">
                        <div class="row">
                            <div class="page-loader-wrapper medicine-loader report-loader">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                        </div>
                        <div class="caExpense-report-data table-responsive active">
                            <!-- table data here include -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('modal')
        <div class="modal fade" id="expense-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog update-time" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header justify-content-center">
                        <h4 class="title" id="next-appointment">CA Expense</h4>
                    </div>
                    <!-- body -->
                    {{Form::open(['class'=>'form-inline'])}}
                    <span class="form-error-msg expense-error w-100"></span>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                        Txt Amount:
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::number('txt_amount','',['class'=>'form-control txt_amount form-required','placeholder'=>'Txt Amount','min'=>1])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                        Invoice No:
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::text('invoice_no','',['class'=>'form-control invoice_no form-required','placeholder'=>'Invoice No'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-12">
                                    <div class="col-md-3">
                                        Bank:
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::select('bank_id',$bank_details,null,['class'=>'form-control bank_id select-padding-0','placeholder'=>"Select Bank"])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-12">
                                    <div class="form-group">
                                        {{Form::textarea('detail', null, ['class'=>'form-control no-resize detail','placeholder'=>'Detail','rows'=>'5'])}}
                                    </div>
                                </div>
                            </div>
                            <!-- footer -->
                            <div class="modal-footer mt-3 time-footer">
                                <button type="button" class="btn btn-primary waves-effect ca-expense-save">Save</button>
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    @stop
@stop
@section('page-script')
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        var page = '';

        var usg = '';
        var hormon = '';
        var iui = '';
        var ivf = '';
        var mainCollection = '';
        var income = '';
        var expense = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var reportPaymentType = 2;
        var referenceDoctorId = '';
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate +'&payment_type='+reportPaymentType;
        var ca_expense_table='';
        var ca_expense_row_id='';
        var bank_id='';

        $(document).ready(function () {

            $('input[name="daterange"]').daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                    container: '#myModalId'
                }
            });
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {

                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
                getCollectionReportData(qstring);
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                // Reset values
                $('#daterange #input-text').html('<span class="text-muted">Filter op datum..</span>');
                $("#daterange").val('');
                // Destroy and rebuild daterangepicker to clear data

                fromdate = '';
                todate = '';
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
                getCollectionReportData(qstring);
            });
            getCollectionReportData(qstring);

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var keyValue = $(this).attr('href').split('?')[1];
                var keyword  = keyValue.split('=')[0];
                var page = keyValue.split('=')[1];

                switch(true) {
                    case ('usg' == keyword):
                        usg = page;
                        break;
                    case ('hormon' == keyword):
                        hormon = page;
                        break;
                    case ('iui' == keyword):
                        // code block
                        iui = page;
                        break;
                    case ('ivf' == keyword):
                        // code block
                        ivf = page;
                        break;
                    case ('mainCollection' == keyword):
                        mainCollection = page;
                        break;
                    case ('income' == keyword):
                        // code block
                        income = page;
                        break;
                    case ('expense' == keyword):
                        // code block
                        expense = page;
                        break;

                }
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
                getCollectionReportData(qstring);
            });

            $(document).on('change', 'select.reference-doctor', function () {
                referenceDoctorId = $(this).val();
                qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
                getCollectionReportData(qstring);
            });
        });

        $(document).on('click', '.print-collection-report', function () {

            var isprint = 1;
            $.ajax({
                url: "{{URL::to('ca-expense-report')}}?" + qstring,
                data: {isprint},
                dataType: 'json',
            }).done(function (data) {
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
            });
        });

        $(document).on('click','.report-payment-type',function(){
            reportPaymentType = 2;
            $('.report-payment-type').not(this).prop('checked', false);
            if($(this).is(':checked')){
                reportPaymentType = $(this).val();
            }
            qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
            getCollectionReportData(qstring);
        });
        $('#select_bank_id').on('change', function() {
            bank_id = $(this).val();
            qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
            getCollectionReportData(qstring);
        });

        $(document).on('click', '.export-collection-report', function () {
            qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id+'&isexport=1';
            var exportUrl = "{{URL::to('ca-expense-report?')}}" + qstring;
            window.location.href = exportUrl;
        });

        // get all collection report data
        function getCollectionReportData(qstring) {
            $('table td.expense-icon').removeClass('d-none');
            $('table th.expense-icon').removeClass('d-none');
            $('.report-loader').css('display','block');
            $('.caExpense-report-data').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ca-expense-report')}}?" + qstring,
                dataType: 'json',
            }).done(function (data) {
                $('.report-loader').css('display','none');
                $('.caExpense-report-data').removeClass('d-none');

                $('.caExpense-report-data').html(data);
                if(reportPaymentType == 2)
                {
                    $('table td.expense-icon').addClass('d-none');
                    $('table th.expense-icon').addClass('d-none');
                }

            }).fail(function () {

            });
        }

        $(document).on('click', '.expense-modal', function () {
           ca_expense_row_id = $(this).data('id');
           ca_expense_table = $(this).data('class');
           var getDataString = 'ca_expense_row_id='+ ca_expense_row_id + '&ca_expense_table='+ ca_expense_table;
           $('.txt_amount').val('');
           $('.invoice_no').val('');
           $('select.bank_id').val('');
           $('textarea.detail').val('');

           $.ajax({
                url: "{{URL::to('ca-expense-report/getCaExpense')}}?" + getDataString,
                dataType: 'json',
            }).done(function (data) {
                if(data.status == 1)
                {
                $('#expense-modal').modal('show');
                $('.txt_amount').val(data.data.txt_amount);
                $('.invoice_no').val(data.data.invoice_no);
                $('select.bank_id').val(data.data.bank_id);
                if(data.data.bank_id == null)
                {
                    if(reportPaymentType == 3 || reportPaymentType == 4 || reportPaymentType == 5)
                    {
                        $('select.bank_id').val(3);
                    }
                }
                // $('select.bank_id option[value="'+data.data.bank_id+'"]').attr('selected','selected');
                $('.bank_id').selectpicker('refresh');
                $('textarea.detail').val(data.data.detail);
                }
                else{

                }
            }).fail(function () {

            });

        });
        $(document).on('click','.ca-expense-save',function(){
            var txt_amount = $('.txt_amount').val();
            var select_bank_id = $('select.bank_id').val();
            var invoice_no = $('.invoice_no').val();
            var detail = $('textarea.detail').val();
            var hasNoValue = 0;
            if(txt_amount == '' || select_bank_id == '' || invoice_no == '')
            {
                hasNoValue = 1;
                $('.expense-error').html('All fields are required');
                return false;
            }
            if(hasNoValue == 0)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'ca-expense-report/store',
                    type: 'POST',
                    data: {
                        ca_expense_row_id: ca_expense_row_id,
                        ca_expense_table:ca_expense_table,
                        txt_amount:txt_amount,
                        bank_id:select_bank_id,
                        invoice_no:invoice_no,
                        detail:detail
                    },
                    dataType: 'json',
                }).done(function(data) {
                    if(data.status == 1)
                    {
                        $('#expense-modal').modal('hide');
                        showNotification('bg-blue', 'CA Expense successfully added.', 'bottom', 'right', "", "");
                        qstring = 'usg=' + usg + '&hormon=' + hormon + '&iui=' + iui + '&ivf=' + ivf + '&mainCollection=' + mainCollection + '&income=' + income + '&expense=' + expense + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId+'&payment_type='+reportPaymentType+'&bank_id='+bank_id;
                        getCollectionReportData(qstring);
                    }
                    else{
                        $('#expense-modal').modal('hide');
                        showNotification('bg-red', 'Something Went wrong! Please try again.', 'bottom', 'right', "", "");
                    }
                });
            }

        });
    </script>

@stop
