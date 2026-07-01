@extends('layouts.main')
@section('parentPageTitle', 'Plan')
@section('title', 'Plan')
@section('page-style')

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Plan List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <ul class="dropdown-menu dropdown-menu-right slideUp">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <button class="btn btn-primary plan-modal">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="#"> <button class="btn btn-primary print-plan">
                                    Print
                                </button> </a> --}}</li>
                    </ul>
                </div>

                <div class="body">

                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control search" placeholder="Search...">
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

                        <div class="plan-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('modal')
    <div class="modal fade" id="plan-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title">Plan</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline'])}}
                <span class="form-error-msg expense-error w-100"></span>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Plan :
                                </div>
                                <div class="col-md-6">
                                    {{Form::text('plan','',['class'=>'form-control plan_name form-required','placeholder'=>'Plan Name'])}}
                                </div>
                                <span class="form-error-msg plan-error w-100"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    Category :
                                </div>
                                <div class="col-md-6">
                                {{Form::select('category',[''=>'Select Type','1'=>'IUI', '2'=>'IVF'],'',[
                                    'class'=>'form-control category', 
                                    'id' => 'category',
                                    'data-errorclass' =>'category-error'
                                ])}}
                                </div>
                                <span class="form-error-msg category-error w-100"></span>
                            </div>
                        </div>
                        <!-- footer -->
                        <div class="modal-footer mt-3 time-footer float-right">
                            <button type="button" class="btn btn-primary waves-effect plan-save mr-1">Save</button>
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
        var qstring = '';
        var page = '';
        var search = '';
        var status = '';
        var plan = '';
        var injId = '';

        $(document).ready(function(){

            getPlanData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&plan='+plan;
                getPlanData(qstring);
            });

            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&plan='+plan;
                getPlanData(qstring);
            });


            $(document).on('click', '.print-plan', function () {
                qstring = 'page='+page+'&isprint=1';
                getPlanData(qstring);
            });
            $(document).on('click', '.plan-modal', function () {
                var plan_name = $('.plan_name').val('');
                var category = $('select.category').val('');
                $('.category').selectpicker('refresh');
                $('#plan-modal').modal('show');
            });
        });
        $(document).on('click', '.plan-edit', function () {
            injId = $(this).data('id');
            var plan_name = $('.plan_name').val('');
            var category = $('select.category').val('');
            $('.category').selectpicker('refresh');
            $('.plan-error').html('');
            $('.category-error').html('');
            $.ajax({
            url: "{{URL::to('plan/edit')}}/" + injId,
            dataType: 'json',
            }).done(function (data) {
                if(data.status == 1)
                {
                    $('#plan-modal').modal('show');   
                    $('.plan_name').val(data.injection.type);
                    $('select.category').val(data.injection.category);
            $('.category').selectpicker('refresh');

                }
                else{

                }
            }).fail(function () {

            });
        });
        $(document).on('click','.plan-save',function(){
            var plan_name = $('.plan_name').val();
            var category = $('select.category').val();
            var hasNoValue = 0;
            if(plan_name == '')
            {
                hasNoValue = 1;
                $('.expense-error').html('This field is required');
                return false;
            }
            if(category == '')
            {
                hasNoValue = 1;
                $('.category-error').html('This field is required');
                return false;
            }
            if(hasNoValue == 0)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'plan/store',
                    type: 'POST',
                    data: {
                        plan:plan_name,
                        category:category,
                        injId:injId
                    },
                    dataType: 'json',
                }).done(function(data) {
                    if(data.status == 1)
                    {
                        $('#plan-modal').modal('hide');
                        showNotification('bg-blue', 'Plan successfully added.', 'bottom', 'right', "", "");
                        qstring = 'page='+page+'&search='+search+'&plan='+plan;
                        getPlanData(qstring);
                    }
                    else if(data.status == 2)
                    {
                        $('.plan-error').html('plan already exists');
                    }
                    else{
                        $('#plan-modal').modal('hide');
                        showNotification('bg-red', 'Something Went wrong! Please try again.', 'bottom', 'right', "", "");
                    }
                });
            }
            
        });
        // get all category data
        function getPlanData(qstring){
            $.ajax({
                url: "{{URL::to('plan')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.plan-data').html(data.planList);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.planList);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        
    </script>
@stop
