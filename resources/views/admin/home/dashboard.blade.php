@extends('layouts.main')
@section('parentPageTitle', 'Dashboard')
@section('title', 'Dashboard')
@section('page-style')
    <link rel="stylesheet"
          href="{{url('assets/plugins/morrisjs/morris.min.css')}}" />
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">
      <link href="https://fonts.googleapis.com/css?family=Wendy+One" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<?php
    $systemSetting = systemSetting();
    $hospital_name = isset($systemSetting->hospital_name) && !empty($systemSetting->hospital_name) ? $systemSetting->hospital_name : null;
    $primary = isset($systemSetting->primary) && !empty($systemSetting->primary) ? $systemSetting->primary : null;
?>

@stop
@section('content')
<div class="row clearfix dashboard">
    <div class="col-sm-6 col-md-3">
        <div class="body">
            <strong>Hi, Welcome Back!</strong>
            <br />
            <p>{{ $hospital_name }} Dashboard,</p>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 ml-auto">
    </div>
    <div class="col-md-4">

    </div>
</div>

<div class="row clearfix dashboard">

        <div class="col-lg-3 col-md-6">
            <a href="{{URL::to('appointment')}}">
                <div class="card">
                    <div class="body">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted">Today's Appointments</p>
                                <h3 class="number m-b-0">{{ $totalAppointments }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{URL::to('patient')}}">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted">Today's New Patients</p>
                            <h3 class="number m-b-0">{{ $totalPatients }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{URL::to('anc-iui-ivf')}}">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted">Today's Opd Patients</p>
                            <h3 class="number m-b-0">{{ $totalOpds }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a href="{{URL::to('indoor')}}">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted">Today's Indoor</p>
                            <h3 class="number  m-b-0">{{$inddorcount}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@if(Auth::user()->role == 1)
    <div class="row clearfix">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Revenue Survey</strong></h2>
                </div>
                <div class="body">
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">
                        <div class="tab-pane active"
                            id="chart-view">
                            <div id="graph"
                                class="graph"></div>
                            <div class="xl-slategray">
                                <div class="body">
                                    <div class="row text-center">
                                        <div class="col-sm-3 col-6">
                                            <h4 class="margin-0">{{'₹' .$day}}</h4>
                                            <p class="text-muted margin-0">Today's</p>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <h4 class="margin-0">{{'₹' .$week}}</h4>
                                            <p class="text-muted margin-0">This Week's</p>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <h4 class="margin-0">{{'₹' .$month}}</h4>
                                            <p class="text-muted margin-0">This Month's</p>
                                        </div>
                                        <div class="col-sm-3 col-6">
                                            <h4 class="margin-0">{{'₹' .$year}}</h4>
                                            <p class="text-muted margin-0">This Year's</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 stretch-card user-note">
        </div>
    </div>
@endif
<div class="row clearfix">
    <div class="col-lg-4 col-md-12 ">
        <div class="card w_calender">
            <div class="date l-slategray">
                <span class="candor-color" >{{Carbon\Carbon::today()->format('l, F j')}}</span>
                <span style="background:<?php echo $primary ?> !important; ">{{Carbon\Carbon::now()->year}}</span>
            </div>
            <div class="body days">
                <ul class="m-t-10">
                    <li>SUN</li>
                    <li>MON</li>
                    <li>TUE</li>
                    <li>WED</li>
                    <li>THU</li>
                    <li>FRI</li>
                    <li>SAT</li>
                </ul>
                @php
                $today = Carbon\Carbon::today();
                $tempDate = Carbon\Carbon::createFromDate($today->year, $today->month, 1);
                $skip = $tempDate->dayOfWeek;
                for($i = 0; $i < $skip; $i++)
                {
                $tempDate->subDay();
                }
                do
                {
                    echo '<ul>';
                        for($i=0; $i < 7; $i++)
                        {
                           if($tempDate->format('d-m-y') == $today->format('d-m-y') ) {
                                echo '<li><x class="data">';
                                echo $tempDate->day;
                                echo '</x></li>';
                                $tempDate->addDay();
                            } else {
                                if($tempDate->month != $today->month ) {
                                    echo '<li class="month-days">';
                                    echo $tempDate->day;
                                    echo '</li>';
                                    $tempDate->addDay();
                                } else {
                                    echo '<li>';
                                    echo $tempDate->day;
                                    echo '</li>';
                                    $tempDate->addDay();
                                }
                            }
                        }
                    echo '</ul>';
                }while($tempDate->month == $today->month);
                @endphp
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 data">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 countlist">
                <div class="card top_counter">
                    <div class="body">
                        <div class="row ">
                            <div class="content col-sm-1 m-2 candor-color"><i class="material-icons">apps</i></div>
                            <div class="content col-sm-9">
                                <div class="text"><a href="{{URL::to('category')}}" class="candor-color"><span>Categories</span></a></div>
                                <h5 class="number candor-color">{{$category->count()}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 countlist">
                <div class="card top_counter">
                    <div class="body">
                        <div class="row">
                            <div class="content col-sm-1 m-2 candor-color"><i class="material-icons">supervisor_account</i></div>
                            <div class="content col-sm-9">
                                <div class="text"><a href="{{URL::to('user')}}" class="candor-color"><span>Users</span></a></div>
                                <h5 class="number candor-color">{{$usercount}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 countlist">
                <div class="card top_counter">
                    <div class="body">
                        <div class="row">
                            <div class="content col-sm-1 m-2 candor-color"><i class="material-icons">supervisor_account</i></div>
                            <div class="content col-sm-9">
                                <div class="text"><a href="{{URL::to('user')}}" class="candor-color"><span>Doctors</span></a></div>
                                <h5 class="number candor-color">{{$doctorcount}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 countlist">
                <div class="card top_counter">
                    <div class="body">
                        <div class="row">
                            <div class=" content col-sm-1 m-2 candor-color"><i class="material-icons">supervisor_account</i></div>
                            <div class=" content col-sm-9">
                                <div class="text"><a href="{{URL::to('reference-doctor')}}" class="candor-color"><span>Reference Doctors</span></a></div>
                                <h5 class="number candor-color">{{$referenceDoctor}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="card event-display">
                <div class="header">
                    <h2 class=""><strong>Events</strong></h2>
                </div>
                <div class="card-body event-dashboard" style="">
                    @if(count($event) >0)
                        @foreach($event as $events)
                            <div class="row clearfix event-list" >
                                <div class="col-md-2 text-center pr-0 pl-0">
                                    <img src="{{ cdnUrl($events->event_picture, null) }}" class="rounded-circle" alt="Cinque Terre" width="70" height="70">
                                </div>
                                <div class="col-md-10">
                                    <div>
                                        <a href="{{url('event/'.encrypt($events->id).'/edit')}}" class="EventEditPage" data-eventid="{{encrypt($events->id)}}"><span class="title">{{ucfirst($events->title)}}</span></a>
                                    </div>
                                    <div>
                                        <span>{{ str_limit($events->discription, $limit = 44, $end = '...') }}</span>
                                    </div>
                                    @if($events->start_date == Carbon\Carbon::today()->format('Y-m-d'))
                                        <div><small class="text-success"><span>{{cdate($events->start_date)->toFormattedDateString()}}</span></small></div>
                                    @else
                                        <div><small class="text-danger"><span>{{cdate($events->start_date)->toFormattedDateString()}}</span></small></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="demo-google-material-icon event_add text-center">
                            <a href="{{URL::to('event/create')}}" class="">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- <div class="card dashboard-sms">
            <div class="header">
                <h2><strong>Send messages to thousands</strong></h2>
            </div>
            <div class="body">
                <div class="col-md-12">
                    <p>You can send SMS for sending informative, advertising or events messages to patients and let
                        them know about special events in real time, and for gathering requests, info and comments
                        via SMS</p>
                </div>
                <div class="col-md-12 button">
                    <button type="button" class="btn btn-warning waves-effect" data-toggle="modal" data-target="#send-message" name="send-message-button" id="send-message-button">Send
                        Message
                    </button>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="header">
                    <h2 class=""><strong>New IVF Patients</strong></h2>
                </div>
                <div class="card-body new-patients-display" style="">
                    <table class="table m-b-0">
                        <tbody>
                        @foreach($newPatients as $newPatient)
                            <tr>
                                <td>{{((($newPatientData->currentPage() - 1 ) * $newPatientData->perPage() ) + $loop->iteration) . '.'}}</td>
                                <td>
                                    {{ ucfirst(strtolower($newPatient->name)) }}</td>
                                <td class="total-anc-patient">
                                    <span>@php echo cdate($newPatient->created_at)->format('d-M-Y'); @endphp</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

<div class="row dashboard">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative report-details category-height">
            <div class="header">
                <h2><strong>Category Patients Survey</strong></h2>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 ">
                    <div class="table-responsive">
                        <table class="table table-borderless report-table">
                            @if(!empty($categoryProgress))
                                @foreach ($categoryProgress as $key => $value)
                                    <tr>
                                        <td class="w-25">
                                            <a class="text-muted" href="{{url('category/'.encrypt($key).'/edit')}}">
                                                {{ ucfirst(strtolower($value['catname'])) }}
                                            </a>
                                        </td>
                                        <td class="w-50">
                                            <div class="progress progress-md mx-4">
                                                <div class="progress-bar progress-bar-striped"
                                                     role="progressbar"
                                                     style="width: {{ $patientCount == 0 ? 0 :(number_format((($value['catdata'] / $patientCount) * 100),2)) }}%"
                                                     aria-valuenow="{{ $value['catdata'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="{{$patientCount}}"
                                                ></div>
                                            </div>
                                        </td>
                                        <td class="w-25">
                                            <p class="category-progress">
                                                {{ $value['catdata'] }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td class="text-center"><p style="margin-top: 150px;">No  Category  Found</p></td></tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="col-md-4 total-patient-count text-center">
                    <h1 class="mb-2 candor-color">{{ $patientCount }}</h1>
                    <h3 class="category-progress mb-xl-4 candor-color">Total Patients</h3>
                    <div id="donut_chart" class="dashboard-donut-chart">
                        <svg height="265" version="1.1" width="610" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: scroll;">

                        </svg>
                    </div>
                    <table class="table m-b-0">
                        <tbody>
                        <tr>
                            <td>
                                <div class="anc-color"></div>
                            </td>
                            <td>ANC CYCLE</td>
                            <td class="total-anc-patient">{{$ancPatients}}</td>
                            <td>{{number_format((float)$ancCount, 2, '.', '')}}%</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="ivf-color"></div>
                            </td>
                            <td>IVF CYCLE</td>
                            <td>{{$ivfPatients}}</td>
                            <td>{{number_format((float)$ivfCount, 2, '.', '')}}%</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="iui-color"></div>
                            </td>
                            <td>IUI CYCLE</td>
                            <td>{{$iuiPatients}}</td>
                            <td>{{number_format((float)$iuiCount, 2, '.', '')}}%</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
</div>
 <div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>Popular Category Survey</strong></h2>
            </div>
            <div class="body">
                <div id="line_chart" class="graph"></div>
                <div class="card xl-slategray">
                    <ul class="row profile_state list-unstyled">
                        <li class="col-lg-4 col-md-4">
                            <div class="body">
                                <i class="zmdi"><div class="iui-color"></div></i>
                                <h4>{{round($iuiavg)}}</h4>
                                <span>IUI</span>
                            </div>
                        </li>
                        <li class="col-lg-4 col-md-4">
                            <div class="body">
                                    <i class="zmdi"><div class="ivf-color"></div></i>
                                <h4>{{round($ivfavg)}}</h4>
                                <span>IVF</span>
                            </div>
                        </li>
                        <li class="col-lg-4 col-md-4">
                            <div class="body">
                                <i class="zmdi"><div class="anc-color"></div></i>
                                <h4>{{round($ancavg)}}</h4>
                                <span>ANC</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row dashboard">
    <div class="col-md-4 grid-margin stretch-card">

        <div class="card position-relative report-details">
            <div class="header">
                <h2><strong>Patients Reviews Survey</strong></h2>
            </div>
            <div class="card-body">
                @foreach($reviewRoles as $role)
                    <div class="dash-patient-review">
                        <p class="text-muted dash-patient-name">
                            {{ ucfirst(strtolower($role->name)) }}
                        </p>
                        <div class="progress">
                            <div class="progress-bar l-green custom-progress" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: {{round($reviewAvg[$role->name])."%"}}"></div>
                        </div>
                        <small class="text-success candor-color">{{round($reviewAvg[$role->name])."%"}}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-8">
    <div class="card visitors-map dash-patient-location">
        <div class="header">
            <h2><strong>Patient Location Survey</strong><small>Highest no of patients appointment from top city and area</small></h2>
        </div>
        <div class="body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="body">
                        @if(empty($patientsReports))
                        <p class="text-center mt-5">No Record Found</p>
                        @else
                        <ul class="row location_list list-unstyled">
                            @foreach($patientsReports as $patientsReport)
                            <li class="col-lg-4 col-md-4 col-6 city-background">
                                <div class="body">
                                    <i class="zmdi zmdi-pin"></i>
                                    <h4 class="number count-to" data-from="0" data-to="{{$patientsReport->patients}}" data-speed="2500" data-fresh-interval="700">{{$patientsReport->patients}}</h4>
                                    <span class="city-area-name">
                                        @if(!empty($patientsReport->city))
                                            {{ ucfirst(strtolower($patientsReport->city)) }}
                                        @endif
                                    </span>
                                </div>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="body">
                        @if(empty($cityMainArea))
                        <p class="text-center mt-5">No Record Found</p>
                        @else
                        <ul class="row location_list list-unstyled">
                        @foreach($cityMainArea as $area)
                            <li class="col-lg-4 col-md-4 col-6 city-background">
                                <div class="body">
                                    <i class="zmdi zmdi-pin"></i>
                                    <h4 class="number count-to" data-from="0" data-to="{{$area->patients}}" data-speed="2500" data-fresh-interval="700">{{$area->patients}}</h4>
                                    <span class="city-area-name">
                                         {{ ucfirst(strtolower($area->main_area)) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                        @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>

@stop
@section('modal')
    <div class="modal fade" id="send-message" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h4 class="title">Send Custom SMS</h4>
                </div>
                {{ Form::open([
                    'class' => 'form-inline',
                    'id' => 'send-custom-sms'
                ])}}
                <div class="modal-body">
                    <a href="javascript:void(0)" class="mobile-categories">Send sms through categories</a>
                    <div class="row show-categories">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Mobile
                            </div>
                            <div class="col-md-9">
                                {{ Form::textarea('mobile', null, [
                                    'rows' => 2,
                                    'cols' => 75,
                                    'class' => 'form-control select-padding-0 mobile',
                                    'placeholder' => 'Mobile',
                                    'id' => 'mobile',
                                    'oninput' => 'checkMobile(this.value)',
                                    'required'
                                ]) }}
                                <span class="form-error-msg mobile"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row show-mobile">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Categories
                            </div>
                            <div class="col-md-9">
                                {{ Form::select('categories',$categories, null, [
                                    'class' => 'form-control select-padding-0 categories',
                                    'placeholder' => 'Select Cateogry',
                                    'data-live-search' => 'true',
                                    'id' => 'categories',
                                    'required'
                                ]) }}
                                <span class="form-error-msg categories"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Message
                            </div>
                            <div class="col-md-9">
                                {{ Form::textarea('message', null, [
                                    'class' => 'form-control select-padding-0 message',
                                    'placeholder' => 'Message',
                                    'rows' => 4,
                                    'cols' => 75,
                                    'required'
                                ]) }}
                                <span class="form-error-msg message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" class="btn btn-primary waves-effect send-sms">Send</button>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script type="text/javascript">
                        var totalanc = '';
                        $(document).ready(function(){
                            initDonutChart();
                            if($('#graph').length){
                                const monthNames = [
                                    '',
                                    'Jan',
                                    'Feb',
                                    'Mar',
                                    'Apr',
                                    'May',
                                    'Jun',
                                    'Jul',
                                    'Aug',
                                    'Sep',
                                    'Oct',
                                    'Nov',
                                    'Dec'
                                ];
                                Morris.Area({
                                    element: 'graph',
                                    data: [{
                                        y: 1,
                                        a: <?php echo $incomeArray[0]; ?> ,
                                        b : <?php echo $expense_array[0]; ?>
                                    },
                                        {
                                            y: 2,
                                            a: <?php echo $incomeArray[1]; ?> ,
                                            b : <?php echo $expense_array[1]; ?>
                                        },
                                        {
                                            y: 3,
                                            a: <?php echo $incomeArray[2]; ?> ,
                                            b : <?php echo $expense_array[2]; ?>
                                        },
                                        {
                                            y: 4,
                                            a: <?php echo $incomeArray[3]; ?> ,
                                            b : <?php echo $expense_array[3]; ?>
                                        },
                                        {
                                            y: 5,
                                            a: <?php echo $incomeArray[4]; ?> ,
                                            b : <?php echo $expense_array[4]; ?>
                                        },
                                        {
                                            y: 6,
                                            a: <?php echo $incomeArray[5]; ?> ,
                                            b : <?php echo $expense_array[5]; ?>
                                        },
                                        {
                                            y: 7,
                                            a: <?php echo $incomeArray[6]; ?> ,
                                            b : <?php echo $expense_array[6]; ?>
                                        },
                                        {
                                            y: 8,
                                            a: <?php echo $incomeArray[7]; ?> ,
                                            b : <?php echo $expense_array[7]; ?>
                                        },
                                        {
                                            y: 9,
                                            a: <?php echo $incomeArray[8]; ?> ,
                                            b : <?php echo $expense_array[8]; ?>
                                        },
                                        {
                                            y: 10,
                                            a: <?php echo $incomeArray[9]; ?> ,
                                            b : <?php echo $expense_array[9]; ?>
                                        },
                                        {
                                            y: 11,
                                            a: <?php echo $incomeArray[10]; ?> ,
                                            b : <?php echo $expense_array[10]; ?>
                                        },
                                        {
                                            y: 12,
                                            a: <?php echo $incomeArray[11]; ?> ,
                                            b : <?php echo $expense_array[11]; ?>
                                        },
                                    ],
                                    xkey: 'y',
                                    parseTime: false,
                                    ykeys: ['a', 'b'],
                                    xLabelFormat: function (x) {
                                        var index = parseInt(x.src.y);
                                        return monthNames[index];
                                    },
                                    xLabels: "month",
                                    resize: true,
                                    pointSize: 0,
                                    lineWidth: 0,
                                    labels: ['Income', 'expense'],
                                    fillOpacity: 0.8,
                                    behaveLikeLine: true,
                                    lineColors: ['#a890d3', '#f7d46a', '#666666'],
                                    gridLineColor: '#e0e0e0',
                                    hideHover: 'auto'
                                });
                            }
                        });

                        function initDonutChart() {
                            totalans = $('.total-anc-patient').text();
                            var anccnt = '<?php echo number_format($ancCount); ?>';
                            var iuicnt = '<?php echo number_format($iuiCount); ?>';
                            var ivfcnt = '<?php echo number_format($ivfCount); ?>';
                            if (anccnt==0 && iuicnt==0 && ivfcnt==0) {
                                Morris.Donut({
                                element: 'donut_chart',
                                data : [ { label:"No Data", value:100 } ],
                                colors: ['#f28c85','#afc966','#f99d4a'],
                                formatter: function (y) {
                                    return y + '%'
                                }
                            });
                            }else{
                                Morris.Donut({
                                element: 'donut_chart',
                                data:[ {
                                    label: 'ANC',
                                    value: <?php echo number_format($ancCount,2); ?>
                                }, {
                                    label: 'IUI',
                                    value: <?php echo number_format($iuiCount,2); ?>
                                }, {
                                    label: 'IVF',
                                    value: <?php echo number_format($ivfCount,2); ?>
                                }   ],
                                colors: ['#f28c85','#afc966','#f99d4a'],
                                formatter: function (y) {
                                    return y + '%'
                                }
                                });
                            }
                        }
                        $(document).on('click', '.editEvent', function(event) {
                            var eventId = $(this).data('id');
                            if(typeof(eventId) !== 'undefined'){
                                var url = 'event/'+eventId+'/edit';
                                window.location.href = url;
                            }
                        });
                        var qString = '';
                        var page = '';
                        var noteId = '';
                        qString = 'page=' + page;
                        $(document).ready(function () {
                            getNoteData(qString);
                            isRequired();
                            $(document).on('click', '.pagination a', function (event) {
                                event.preventDefault();
                                page = $(this).attr('href').split('page=')[1];
                                qString = 'page=' + page;
                                getNoteData(qString);
                            });

                            $('#send-message-button').click(function () {
                                $('.form-error-msg').text('');
                                $('.mobile').val('');
                                $('#categories').val('');
                                $('#categories').selectpicker('refresh');
                                $('.show-categories').addClass('d-none');
                                $('.message').val('');
                                isRequired();
                                $('#send-message').modal();
                            });

                            $('.mobile-categories').click(function(event) {
                                event.preventDefault();
                                isRequired();
                            });
                            // $('.send-sms-through').click(function(event) {
                            //     if ($('.show-categories').hasClass('d-none')) {
                            //         $('.show-categories').removeClass('d-none');
                            //         $('.show-mobile').addClass('d-none');
                            //         $('.categories').prop('required', false);
                            //         $('.mobile').prop('required', true);
                            //     }
                            //     else {
                            //         $('.show-mobile').removeClass('d-none');
                            //         $('.show-categories').addClass('d-none');
                            //         $('.categories').prop('required', true);
                            //         $('.mobile').prop('required', false);
                            //     }
                            // });
                            $('#send-custom-sms').submit(function(event) {
                                event.preventDefault();
                                if ($('.show-categories').hasClass('d-none')) {
                                    var checkValidMobileNumber = '';
                                    var mobileNumber = $('#mobile').val().split(',');
                                    $.each(mobileNumber, function(i) {
                                        if ($.trim(mobileNumber[i]).length < 10 || $.trim(mobileNumber[i]).length > 10) {

                                            checkValidMobileNumber = 1;
                                        }
                                        // $('.mobile').text('');
                                    });
                                    if (checkValidMobileNumber) {
                                        $('.mobile').text('Please enter valid mobile number');
                                        return false;
                                    }
                                }

                                var formData = $('#send-custom-sms').serialize();

                                $.ajax({
                                    type: 'POST',
                                    url: "{{URL::to('send-custom-sms')}}",
                                    dataType: 'json',
                                    data: formData
                                }).done(function (data) {
                                    if (data == true) {
                                        $('#send-message').modal('hide');
                                        swal('Success!', 'SMS has been sent.', 'success');
                                    }
                                }).fail(function () {

                                });
                            });
                        });

                        function isRequired() {
                            if ($('.show-categories').hasClass('d-none')) {
                                $('.show-categories').removeClass('d-none');
                                $('.show-mobile').addClass('d-none');
                                $('.categories').prop('required', false);
                                $('.mobile').prop('required', true);
                                $('.mobile-categories').text('Send sms through categories');
                            } else {
                                $('.show-mobile').removeClass('d-none');
                                $('.show-categories').addClass('d-none');
                                $('.categories').prop('required', true);
                                $('.mobile').prop('required', false);
                                $('.mobile-categories').text('Send sms through mobile number');
                            }
                        }
                        function validMobileNumber(value) {
                            if (/[a-zA-Z!@#$&()\\`.+/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                                return value.substring(0, (value.length - 1));
                            } else {
                                return value;
                            }
                        }

                        function checkMobile(value) {
                            $('.mobile').val(validMobileNumber(value));
                        }

                        function getNoteData(qString) {
                            $.ajax({
                                type: 'GET',
                                url: "{{URL::to('get-all-notes')}}?" + qString,
                                dataType: 'json',
                            }).done(function (data) {
                                $('.user-note').html(data);
                            }).fail(function () {

                            });
                        }

                        $(document).on('click', '#submit-list', function () {
                            var note = $('.todo-list-input').val();
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: "{{URL::to('note')}}",
                                dataType: 'json',
                                type: 'POST',
                                data: {
                                    note: note
                                },
                            }).done(function (data) {
                                if (data.status == true) {
                                    getNoteData();
                                }
                            }).fail(function (error) {
                                $('.form-error-msg').empty();
                                if (error.responseJSON != null) {
                                    var formError = error.responseJSON.errors;
                                    $.each(formError, function (key, value) {
                                        $('.' + key).text(value);
                                    });
                                }
                            });
                        });

                        $(document).on('keydown','.todo-list-input',function(evt){
                            var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
                            if(keyCode == 13)
                            {
                                var note = $('.todo-list-input').val();
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{URL::to('note')}}",
                                    dataType: 'json',
                                    type: 'POST',
                                    data: {
                                        note: note
                                    },
                                }).done(function (data) {
                                    if (data.status == true) {
                                        getNoteData();
                                    }
                                }).fail(function (error) {
                                    $('.form-error-msg').empty();
                                    if (error.responseJSON != null) {
                                        var formError = error.responseJSON.errors;
                                        $.each(formError, function (key, value) {
                                            $('.' + key).text(value);
                                        });
                                    }
                                });
                            }
                        });

                        $(document).on('click', 'input[type="checkbox"][name="note_list"]', function () {
                            var noteId = $(this).data('encrypt-id');
                            var id = $(this).data('id');
                            var isChecked = ($(this).is(':checked')) ? 1 : 0;
                            var checkBoxId = this.id;

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: "{{URL::to('update-note')}}",
                                dataType: 'json',
                                type: 'POST',
                                data: {
                                    note_id: noteId,
                                    is_checked: isChecked
                                },
                            }).done(function (success) {
                                data = JSON.parse(success.data);
                                if (data.is_checked == 1) {
                                    $('#' + checkBoxId + '_label').addClass('note-text');
                                    $('#note-close-button-' + id).removeClass('cust-delete-icon-red');
                                    $('#note-close-button-' + id).addClass('cust-delete-icon-color');
                                } else {
                                    $('#' + checkBoxId + '_label').removeClass('note-text');
                                    $('#note-close-button-' + id).addClass('cust-delete-icon-red');
                                    $('#note-close-button-' + id).removeClass('cust-delete-icon-color');
                                }
                            }).fail(function (error) {});
                        });

                        $('#myModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget);
                            var id = button.data('id');
                            var title = button.data('title');
                            var discription = button.data('discription'); // Extract info from data-* attributes
                            // Update the modal's content
                            var modal = $(this);
                            modal.find('#id').text(id);
                            modal.find('#title').text(title);
                            modal.find('#discription').text(discription);
                        });

                        $('#edit').on('show.bs.modal', function (event) {

                            var button = $(event.relatedTarget);
                            var id = button.data('id');
                            var title = button.data('title');
                            var discription = button.data('discription'); // Extract info from data-* attributes
                            // Update the modal's content
                            var modal = $(this);
                            modal.find('#id').val(id);
                            modal.find('#title').val(title);
                            modal.find('#discription').val(discription);
                        });

                        $(document).on('dblclick', '.note-data', function () {
                            var noteId = $(this).data('id');
                            $.ajax({
                                url: "{{URL::to('get-note-data')}}",
                                dataType: 'json',
                                data: {
                                    note_id: noteId
                                }
                            }).done(function (data) {
                                $('.title').val(data.title);
                                $('.discription').val(data.discription);
                                $('#note_id').val(data.id);
                                $('#edit-note').modal('show');
                                // $('.indoor-data').html(data.indoorData);
                            }).fail(function (error) {});
                        });

                        $('#update-note').on('submit', function (e) {
                            e.preventDefault();
                            $.ajax({
                                type: 'post',
                                url: '{{URL::to("update-note")}}',
                                data: $('#update-note').serialize(),
                                success: function (response) {
                                    if (response == 'true') {
                                        $('#edit-note').modal('hide');
                                        location.reload();
                                    }
                                },
                                error: function (error) {}
                            });
                        });

                        $(document).on('click', '#delete-note', function () {
                            noteId = $(this).data('id');
                            swal({
                                title: "Are you sure?",
                                text: "You want to delete it!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#00cfd1",
                                confirmButtonText: "Yes, Delete!",
                                closeOnConfirm: false,
                                cancelButtonClass: 'btn btn-danger',
                            }, function () {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "usernote/delete/" + noteId,
                                    type: "GET",
                                    dataType: 'json',
                                }).done(function (data) {

                                    if (data.status == true) {
                                        // swal('Delete!', data.message, 'success');
                                        $('.showSweetAlert').hide();
                                        location.reload();
                                        getNoteData();
                                    } else {
                                        swal('Oops!', data.message, 'error');
                                    }
                                }).fail(function () {

                                });
                            });
                        });
                         const monthName = [
                            '',
                            'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec'
                        ];

                        Morris.Line({
                        element: 'line_chart',
                        data: [
                                {
                                     y: 1,
                                    a: <?php echo $iuiArray[0]; ?> ,
                                    b: <?php echo $ancArray[0]; ?> ,
                                    c: <?php echo $ivfArray[0]; ?> ,
                                },
                                {
                                     y: 2,
                                    a: <?php echo $iuiArray[1]; ?> ,
                                    b: <?php echo $ancArray[1]; ?> ,
                                    c: <?php echo $ivfArray[1]; ?> ,
                                },
                                {
                                     y: 3,
                                    a: <?php echo $iuiArray[2]; ?> ,
                                    b: <?php echo $ancArray[2]; ?> ,
                                    c: <?php echo $ivfArray[2]; ?> ,
                                },
                                {
                                     y: 4,
                                    a: <?php echo $iuiArray[3]; ?> ,
                                    b: <?php echo $ancArray[3]; ?> ,
                                    c: <?php echo $ivfArray[3]; ?> ,
                                },
                                {
                                    y: 5,
                                    a: <?php echo $iuiArray[4]; ?> ,
                                    b: <?php echo $ancArray[4]; ?> ,
                                    c: <?php echo $ivfArray[4]; ?> ,
                                },
                                {
                                     y: 6,
                                    a: <?php echo $iuiArray[5]; ?> ,
                                    b: <?php echo $ancArray[5]; ?> ,
                                    c: <?php echo $ivfArray[5]; ?> ,
                                },
                                {
                                     y: 7,
                                    a: <?php echo $iuiArray[6]; ?> ,
                                    b: <?php echo $ancArray[6]; ?> ,
                                    c: <?php echo $ivfArray[6]; ?> ,
                                },
                                {
                                     y: 8,
                                    a: <?php echo $iuiArray[7]; ?> ,
                                    b: <?php echo $ancArray[7]; ?> ,
                                    c: <?php echo $ivfArray[7]; ?> ,
                                },
                                {
                                     y: 9,
                                    a: <?php echo $iuiArray[8]; ?> ,
                                    b: <?php echo $ancArray[8]; ?> ,
                                    c: <?php echo $ivfArray[8]; ?> ,
                                },
                                {
                                     y: 10,
                                    a: <?php echo $iuiArray[9]; ?> ,
                                    b: <?php echo $ancArray[9]; ?> ,
                                    c: <?php echo $ivfArray[9]; ?> ,
                                },
                                {
                                     y: 11,
                                    a: <?php echo $iuiArray[10]; ?> ,
                                    b: <?php echo $ancArray[10]; ?> ,
                                    c: <?php echo $ivfArray[10]; ?> ,
                                },
                                {
                                     y: 12,
                                    a: <?php echo $iuiArray[11]; ?> ,
                                    b: <?php echo $ancArray[11]; ?> ,
                                    c: <?php echo $ivfArray[11]; ?> ,
                                },
                        ],
                         xkey: 'y',
                        ykeys: ['a','b','c'],
                        labels: ['IUI','ANC','IVF'],
                        parseTime:false,
                        xLabelFormat: function (x) {
                                var index = parseInt(x.src.y);
                                return monthName[index];
                            },
                        xLabels: "month",
                        pointSize: 2,
                        fillOpacity: 0,
                        pointStrokeColors: ['#afc966', '#f28c85', '#f99d4a'],
                        behaveLikeLine: true,
                        gridLineColor: '#e0e0e0',
                        lineWidth: 2,
                        hideHover: 'auto',
                        lineColors: ['#afc966', '#f28c85', '#f99d4a'],
                        resize: true
                    });
                    </script>

@stop


