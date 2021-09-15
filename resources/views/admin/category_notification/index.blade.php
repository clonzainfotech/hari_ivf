@extends('layouts.main')
@section('parentPageTitle', 'Notification')
@section('title', 'Notification')
@section('page-style')
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> --}}
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Notification List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <ul class="dropdown-menu dropdown-menu-right slideUp">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="body">
                        <div class="col-md-8 float-left">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link notification-li active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" data-type="today" aria-controls="nav-home" aria-selected="true">Today</a>
                                    <a class="nav-item nav-link notification-li" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" data-type="next" aria-controls="nav-profile" aria-selected="false">Reminder</a>
                                    <a class="nav-item nav-link notification-li" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" data-type="future" aria-controls="nav-contact" aria-selected="false">Coming</a>
                                    <a class="nav-item nav-link float-right candor-color font-bold d-none" id="mark-all" href="{{URL::to('notification-all-read')}}" >Mark All as Read</a>
                                </div>
                            </nav>
                        </div>
                        <div class="col-md-4 float-right">
                            <nav class="">
                                <div class="nav nav-tabs float-right" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link notification-type-li active" id="nav-home-tab" data-toggle="tab" data-type="category" role="tab" aria-controls="nav-home" aria-selected="true">Category</a>
                                    <a class="nav-item nav-link notification-type-li" id="nav-profile-tab" data-toggle="tab" data-type="payment" role="tab" aria-controls="nav-profile" aria-selected="false">Payment</a>
                                </div>
                            </nav>
                        </div>
                    <!-- Nav tabs -->
                    
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

                        <div class="notification-data table-responsive active">
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
        var qstring = 'type=today&notificationType=category';
        var page = '';
        var search = '';
        var categoryId = '';
        var status = '';
        var type = 'today';
        var notificationType = "category";

        $(document).ready(function(){

            getNotificationData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&type='+type+'&notificationType='+notificationType;
                getNotificationData(qstring);
            });

            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&type='+type+'&notificationType='+notificationType;
                getNotificationData(qstring);
            });
            $(document).on('click', '.print-category', function () {
                qstring = 'page='+page+'&search='+search+'&type='+type+'&notificationType='+notificationType;
                getNotificationData(qstring);
            });
            $(document).on('click', '.notification-li', function () {
                type = $(this).data('type');
                qstring = 'page='+page+'&search='+search+'&type='+type+'&notificationType='+notificationType;
                getNotificationData(qstring);
                if(type == 'today' && notificationType == 'category')
                {
                    $('#mark-all').removeClass('d-none');
                }
                $('.notification-li').removeClass('active');
                $(this).addClass('active');
            });
            $(document).on('click','.notification-type-li',function(){
                $('#mark-all').addClass('d-none');
                notificationType = $(this).data('type');
                qstring = 'page='+page+'&search='+search+'&type='+type+'&notificationType='+notificationType;
                getNotificationData(qstring);
                if(type == 'today' && notificationType == 'category')
                {
                    $('#mark-all').removeClass('d-none');
                }
            })
        });
        // get all category data
        function getNotificationData(qstring){
            $('#mark-all').addClass('d-none');
            $.ajax({
                url: "{{URL::to('notification')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                    $('.notification-data').html(data.notification);
                    
                   
                    if(type == 'today' && data.type == 'category')
                    {
                        $('#mark-all').removeClass('d-none');
                    }
                    
            }).fail(function() {

            });
        }
    </script>
@stop
