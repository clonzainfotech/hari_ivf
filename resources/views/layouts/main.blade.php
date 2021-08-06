<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<?php
$systemSetting = systemSetting();
$htmlFavicon = isset($systemSetting->html_favicon) && !empty($systemSetting->html_favicon) ? $systemSetting->html_favicon : 'favicon.ico';
$title = isset($systemSetting->html_title) && !empty($systemSetting->html_title) ? $systemSetting->html_title : null;
$primary = isset($systemSetting->primary) && !empty($systemSetting->primary) ? $systemSetting->primary : null;
$secondary = isset($systemSetting->secondary) && !empty($systemSetting->secondary) ? $systemSetting->secondary : null;
$link = isset($systemSetting->link) && !empty($systemSetting->link) ? $systemSetting->link : null;
$before_visits = isset($systemSetting->before_visits) && !empty($systemSetting->before_visits) ? $systemSetting->before_visits : null;
$after_visits = isset($systemSetting->after_visits) && !empty($systemSetting->after_visits) ? $systemSetting->after_visits : null;
$unpaid_opd = isset($systemSetting->unpaid_opd) && !empty($systemSetting->unpaid_opd) ? $systemSetting->unpaid_opd : null;

$footer_1 = isset($systemSetting->footer_1) && !empty($systemSetting->footer_1) ? $systemSetting->footer_1 : null;
$footer_2 = isset($systemSetting->footer_2) && !empty($systemSetting->footer_2) ? $systemSetting->footer_2 : null;
$docter_1 = isset($systemSetting->docter_1) && !empty($systemSetting->docter_1) ? $systemSetting->docter_1 : null;
$docter_2 = isset($systemSetting->docter_2) && !empty($systemSetting->docter_2) ? $systemSetting->docter_2 : null;
$water_mark = isset($systemSetting->water_mark) && !empty($systemSetting->water_mark) ? $systemSetting->water_mark : null;
?>
{{--added by developer <html lang="{{ app()->getLocale() }}" oncontextmenu="return false;">--}}
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($htmlFavicon) && !empty($htmlFavicon))
        <link rel="icon" href="{{URL::to('assets/' . $htmlFavicon)}}" type="image/x-icon"> <!-- Favicon-->
    @endif

    <style>
        .theme-cyan .btn-primary, .w_calender .data, .dashboard-sms, .progress .progress-bar, .w_calender span+span, .dtp table.dtp-picker-days tr > td > a.selected, .dtp .p10 > a, .dtp .dtp-content .dtp-buttons button, .dtp div.dtp-date, .dtp div.dtp-time, .dtp > .dtp-content > .dtp-date-view > header.dtp-header, .btn-primary, .candor-bg-color,.pagination .page-item.active>.page-link, .pagination .page-item.active>.page-link:focus, .pagination .page-item.active>.page-link:hover{
            background-color:{{$primary}} !important;
        }
        .w_calender span, .theme-cyan .card .header h2 strong, .candor-color, .search-border:focus, .search-border:focus, .cust-delete-icon, .reviews .zmdi.zmdi-star, .event-dashboard i,            .add-items i,.nav-menu-font .active,.a-time,            .edit-remark-icon,            .appointment .total-opd{
            color:{{$primary}} !important;
        }
        .form-control:focus, .appointment .total-opd,
        .ivf .input-group.input-group-focus .input-group-addon,
        .iui-history .input-group.input-group-focus .input-group-addon,
        .anc .input-group.input-group-focus .input-group-addon,
        .input-group.input-group-focus .input-group-addon, .theme-cyan .sidebar .menu .list li.active>:first-child span {
            border-color:{{$primary}} !important;
        }
        .btn-default, .navbar .navbar-nav>a.btn-default{
            background:{{$secondary}} !important;
        }
        .theme-cyan .sidebar .menu .list a:hover,a:hover.nav-menu-font .active, .theme-cyan .sidebar .menu .list li.sub.active > a,.theme-cyan .sidebar .menu .list li.sub.active > a span, .nav-menu-font a:hover, .nav-menu-font a.active.open{
            color: #fff !important;
            background: {{$primary}} !important;
        }
        .loader p{
            color: {{$primary}} !important;
        }
        .appointment-opd-tr, .selected-tr{
            background-color:{{$unpaid_opd}}!important;
        }
        .is_arrival{
            background-color:{{$before_visits}}!important;
        }
        .is-done{
            background-color: <?php echo $after_visits ?> !important;
        }

    
    </style>
    <title>@yield('title') {{ !empty($title) ? ' - ' . $title : null }}</title>
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    @yield('meta')
    @stack('before-styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    @if (trim($__env->yieldContent('page-style')))
        @yield('page-style')
    @endif

<!-- Custom Css -->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/color_skins.css')}}">
    @stack('after-styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-select/css/bootstrap-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/themes.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('public/css/bootstrap-notifications.min.css')}}">
    

</head>
<?php
$setting = !empty($_GET['theme']) ? $_GET['theme'] : '';
$theme = "theme-cyan";
$menu = "";
?>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo" ></div>
    {{--                <div class="m-t-30"><img class="zmdi-hc-spin" src="{{URL::to('assets/images/logo.svg')}}" width="48" height="48" alt="Oreo"></div>--}}
    <!-- <p>Please wait...</p> -->
    </div>
</div>
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
@include('layouts.navbar')
@include('layouts.sidebar')
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                @if (Request::segment(2) === 'doctors' or Request::segment(2) === 'add-doctor' or Request::segment(2) === 'all-patients' or Request::segment(2) === 'add-patients' or Request::segment(2) === 'add-payment' or Request::segment(2) === 'all-departments' or Request::segment(2) === 'add-department')
                    <button class="btn btn-white btn-icon d-none d-md-inline-block float-right m-l-10" type="button">
                        <i class="zmdi zmdi-plus"></i>
                    </button>
                @endif

                @if (Request::segment(2) === 'profile' or Request::segment(2) === 'patients-profile')
                    <button class="btn btn-white btn-icon d-none d-md-inline-block float-right m-l-10" type="button">
                        <i class="zmdi zmdi-edit"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="container-fluid">
        @yield('content')
    </div>
</section>
@yield('modal')
@include('admin.collection_password_modal')
@stack('before-scripts')
<script src="{{asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{asset('assets/js/raphael-min.js')}}"></script>
<script src="{{asset('assets/js/morris.min.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="{{asset('assets/plugins/momentjs/moment.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/theme.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js"></script>
<link href="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.css" rel="stylesheet"/>
<link href="https://code.jquery.com/ui/1.12.1/themes/pepper-grinder/jquery-ui.css" rel="stylesheet"/>
{{-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> --}}

<script type="text/javascript">
    // $(document).on('click','.notify-patient',function(){
    //     $(this).parent().html("reuqest send!");

    // });
    function callPatient(name, cat, e) {
        // $(this).removeClass('notify-patient');
        swal({
                title: "OPD Area",
                text: "Enter OPD",
                type: "input",
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Send',
                showCancelButton: true,
                animation: "slide-from-top",
                closeOnConfirm: false,
                html: true,
            },
            function(title){
                if (title === false)
                {
                    return false;
                }
                if (title === "") {
                    swal.showInputError("Please enter OPD area!");
                    return false;
                }
                else
                {
                    swal("Thank You!", "", "success");
                    $(e).parent().html("reuqest send!");
                    $.ajax({
                        url:"{{url('patient_notification')}}",
                        data:{name:name,cat:cat,title:title},
                    });
                }
        });
        
    }
</script>
@stack('after-scripts')
@if (trim($__env->yieldContent('page-script')))
    @yield('page-script')
@endif
@if(in_array(Auth::user()->role,[1,2]))
    @php
        $stopNotification = config('app.stopNotification');
    @endphp

    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
        var main_url = window.location.href;
        let anc_url_name = main_url.split('/').pop();
        var stopNotification = '{{json_encode($stopNotification)}}';
        console.log(stopNotification);
        var check_url = stopNotification.includes(anc_url_name);
        if (!check_url) {
            var pusher = new Pusher('{{env("MIX_PUSHER_APP_KEY")}}', {
                cluster: '{{env("PUSHER_APP_CLUSTER")}}',
                encrypted: true
            });

            var channel = pusher.subscribe('notify-channel');
            channel.bind('App\\Events\\Notify', function(data) {
                showConfirmNotify(data);
            });
        }
        function showConfirmNotify(data){
            if ('speechSynthesis' in window) {
                var msg = new SpeechSynthesisUtterance();
                msg.text = data.name;
                window.speechSynthesis.speak(msg);
            }
            swal({
                    title: "<span class='callpatient-name'>"+data.name+"<span>",
                    text: "<h6 class = 'callpatient-category'>"+data.user+" CALLING FOR "+data.category + "</h6><br><h6>Area : "+data.opd_area+"</h6>",
                    type: "warning",
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    closeOnConfirm: false,
                    html: true,
                },
                function(isConfirm){
                    if (isConfirm){
                        swal("Thank You!", "", "success");
                        removeNotify();
                    }
                });
        };

        function removeNotify(){
            $.ajax({
                url: "{{url('remove_notification')}}",
                dataType: 'json',
            }).done(function(data) {
            }).fail({

            });
        }
        // <script type="text/javascript">
    displayNotification();
    $(document).on('click','.patient-category-notification',function(){
        displayNotification();
    });
    function displayNotification()
    {
            $.ajax({
                    url: "{{url('get-category-notification')}}",
                    dataType: 'json',
                }).done(function(data) {
                    // var existingNotifications = notifications.html();
                    var newNotificationHtml = '';
                    var notificationsCount = 0;
                    for(var i=0; i< data.data.length; i++)
                    {
                        if(data.data[i] != null)
                        {
                            newNotificationHtml += '<li class="category-notification notification active">'+
                            '<div class="media mb-1">'+
                            '<div class="media-left"><div class="media-object">'+
                            ''+
                            '</div></div>'+
                            '<div class="media-body">'+
                            '<strong class="notification-title">'+data.data[i].patient_name+'</strong><br>'+
                            '<p class="notification-desc">'+data.data[i].date+' - '+data.data[i].message+'</p>'+
                            '</div></div></li>';
                            notificationsCount += 1;
                        }
                    }
                    if(data.data.length == 0)
                    {
                        $('a.mark-all').addClass('d-none');
                        newNotificationHtml +=
                        newNotificationHtml += '<li class="category-notification notification active">'+
                        '<div class="media mb-1">'+
                        '<div class="media-left"><div class="media-object">'+
                        ''+
                        '</div></div>'+
                        '<div class="media-body">'+
                        '<p class="notification-desc">No record Available</p>'+
                        '</div></div></li>';
                    }
                    // $(newNotificationHtml).insertAfter($('ul.notification-menu li.notification-head'));
                    $('.notification-menu').html(newNotificationHtml);
                    $('.notification-count').text(notificationsCount);
                }).fail({

                });
    }
    </script>
@endif
</body>
</html>
