@extends('layouts.main')
@section('parentPageTitle', 'Expense Manager')
@section('title', 'Expense Manager')
@section('page-style')



@stop
@section('content')

    <div class="row clearfix expense">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Expense Manager list</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('expense-manager/create')}}">
                                <button class="btn btn-primary">
                                    Add Expence
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="{{URL::to('expense-category')}}">
                                <button class="btn btn-primary">
                                    Expence Category
                                </button>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="#"> <button class="btn btn-primary print-expense">
                                    Print
                                </button> </a> --}}</li>
                    </ul>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <form method="post" autocomplete="off" action="">
                                        <div class="input-group">
                                            <input type="text" class="form-control daterange" autocomplete="off" placeholder="Select Date">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{Form::select('categoryId',$expensecategory,'',['class'=>'form-control select-padding-0 category','placeholder'=>'Select Category'])}}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{Form::select('payment_method',[1=>'Cash',2=>'Debit Card',3=>'Credit Card',4=>'Cheque'],'',['class'=>'form-control select-padding-0 payment-method','placeholder'=>'Payment Method'])}}
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <form method="post" autocomplete="off" action="">
                                        <ul class="nav nav-tabs padding-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control search" placeholder="Search..." autocomplete="off">
                                                <span class="input-group-addon search-border">
                                                    <i class="zmdi zmdi-search"></i>
                                                </span>
                                            </div>
                                        </ul>
                                    </form>
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

                        <div class="expense-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script type="text/javascript">
        var page = '';
        var search = '';
        var expenseId = '';
        var payment_method = '';
        var category = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate;

        $(document).ready(function(){

            $('.daterange').daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y'
                }
            });
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getExpenseData(qstring);
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(".daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getExpenseData(qstring);
            });

            $('.date').change(function(){
                date = $('.date').val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getExpenseData(qstring);
            });
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&categoryId='+category+'&payment_method='+payment_method;
                getExpenseData(qstring);
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(".daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&categoryId='+category+'&payment_method='+payment_method;
                getExpenseData(qstring);
            });

            $('.date').change(function(){
                date = $('.date').val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&categoryId='+category+'&payment_method='+payment_method;
                getExpenseData(qstring);
            });

            getExpenseData(qstring);
            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&category='+category+'&payment_method='+payment_method;
                getExpenseData(qstring);
            });
            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+'&categoryId='+category;
                getExpenseData(qstring);
            });
            $(document).on('click','.delete-expense',function(){
                expenseId = $(this).data('id');
                showConfirmMessage();
            });
            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+'&categoryId='+category;
                getExpenseData(qstring);
            });
            $(document).on('change','select.payment-method',function(e){
                e.preventDefault();
                payment_method = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+'&categoryId='+category;
                getExpenseData(qstring);
            });
            $(document).on('change','select.category',function(e){
                e.preventDefault();
                category = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&categoryId='+category+ '&payment_method='+payment_method;
                getExpenseData(qstring);
            });
            $(document).on('click', '.print-expense', function () {
                qstring = 'fromdate='+fromdate+ '&todate=' + todate+'&isprint=1';
                getExpenseData(qstring);
            });
        });
            $(document).on('click', '#expense-table tbody tr', function(event) {
                var expenseId = $(this).data('id');
                if(typeof(expenseId) !== 'undefined'){
                    $('#expense-table tbody tr').removeClass('selected-tr');
                    $(this).addClass('selected-tr');
                }
            });

            $(document).on('dblclick', '#expense-table tbody tr', function(event) {
                var expenseId = $(this).data('id');
                if(typeof(expenseId) !== 'undefined'){
                    var url = 'expense-manager/'+expenseId+'/edit';
                    window.location.href = url;
                }
            });

        // get all expense data
        function getExpenseData(qstring){
            $.ajax({
                url: "{{URL::to('expense-manager')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.expense);
                    w.document.close();
                    w.window.print();
                    return true;
                }
                $('.expense-data').html(data);
            }).fail(function() {

            });
        }
        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to delete this expense!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                removeExpense();
                swal("Deleted!", "Your expense has been deleted.", "success");
            });
        }
        // remove expense
        function removeExpense(){
            $.ajax({
                url: "{{URL::to('expense-manager/delete/')}}"+'/'+expenseId,
                dataType: 'json',
            }).done(function(data) {
                getExpenseData(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
