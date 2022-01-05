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
                    <h2><strong>Procedure</strong></h2>
                    <ul class="header-dropdown">
                        
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

                        <div class="procedure-list table-responsive active">
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
                getProcedureListData(qstring);
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(".daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&payment_method='+payment_method+ '&categoryId='+category;
                getProcedureListData(qstring);
            });
            
            getProcedureListData(qstring);
            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&fromdate='+fromdate+ '&todate=' + todate + '&category='+category+'&payment_method='+payment_method;
                getProcedureListData(qstring);
            });
            
        });
        // get all expense data
        function getProcedureListData(qstring){
            $.ajax({
                url: "{{URL::to('procedures')}}?"+qstring,
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
                $('.procedure-list').html(data.data);
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
                getProcedureListData(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
