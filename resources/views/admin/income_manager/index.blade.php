@extends('layouts.main')
@section('parentPageTitle', 'Income Manager')
@section('title', 'Income Manager')
@section('page-style')



@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Income List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('income-manager/create')}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="{{URL::to('income-category')}}">
                                <button class="btn btn-primary">
                                    Income Category
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="input-group"><input type="text" class="form-control daterange" autocomplete="off" placeholder="Select Date">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{Form::select('categoryId',$expensecategory,'',['class'=>'form-control select-padding-0 category','placeholder'=>'Select Category'])}}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{Form::select('payment_method',[1=>'Cash',2=>'Debit Card',3=>'Credit Card',4=>'Cheque'],'',['class'=>'form-control select-padding-0 payment-method','placeholder'=>'Select Payment Method'])}}
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search" placeholder="Search..." readonly="readonly" onfocus="this.removeAttribute('readonly')">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
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

                        <div class="income-data table-responsive active">
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
        var incomeId = '';
        var category = '';
        var payment_method = $('.payment-method').val();
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate;

        $(document).ready(function(){
             $('.daterange').daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                }
            });
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {

                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+'&categoryId='+category;;
                getIncomeData(qstring);
            });
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(".daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getIncomeData(qstring);
            });
            getIncomeData(qstring);
            $('.date').change(function(){
                date = $('.date').val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getIncomeData(qstring);
            });
            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getIncomeData(qstring);
            });
            $(document).on('click','.delete-income',function(){
                incomeId = $(this).data('id');
                showConfirmMessage();
            });
            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getIncomeData(qstring);
            });
            $(document).on('change','select.payment-method',function(e){
                e.preventDefault();
                payment_method = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getIncomeData(qstring);
            });
            $(document).on('change','select.category',function(e){
                e.preventDefault();
                category = $(this).val();
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getIncomeData(qstring);
            });
        });
            $(document).on('click', '#income-table tbody tr', function(event) {
                var incomeId = $(this).data('id');
                if(typeof(incomeId) !== 'undefined'){
                    $('#income-table tbody tr').removeClass('selected-tr');
                    $(this).addClass('selected-tr');
                }
            });

            $(document).on('dblclick', '#income-table tbody tr', function(event) {
                var incomeId = $(this).data('id');
                if(typeof(incomeId) !== 'undefined'){
                    var url = 'income-manager/'+incomeId+'/edit';
                    window.location.href = url;
                }
            });

        // get all income data
        function getIncomeData(qstring){
            $.ajax({
                url: "{{URL::to('income-manager')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                $('.income-data').html(data);

            }).fail(function() {

            });
        }
        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to delete this income!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                removeIncome();
                swal("Deleted!", "Your income has been deleted.", "success");
            });
        }
        // remove income
        function removeIncome(){
            $.ajax({
                url: "{{URL::to('income-manager/delete/')}}"+'/'+incomeId,
                dataType: 'json',
            }).done(function(data) {
                getIncomeData(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
