@extends('layouts.main')
@section('parentPageTitle', 'Patient History')
@section('title', 'Patient History')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/fontawesome.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
<style>
    .follicular-table tbody,.follicular-table thead
    {
        font-size: 16px !important;
    }
    .follicular-table .visit-lable {
        font-size: 16px !important;
    }
    span.ivf-label
    {
        font-weight: 700 !important;
    }
    table.medicine-table
    {
        width: 100%
    }
    .category-header
    {
        background-color : #d7e5e4;
        color : black;
    }
</style>

@stop

@section('content')

    <div class="row clearfix">
        <style type="text/css">
        </style>
        <div class="col-md-12 p-0">
            <div class="card pt-history">
                <div class="header">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h2><strong class="text-secondary">{{ucwords($patients->name)}}</strong>
                                @php
                                    $careof = (!empty($patients->reference_doctor_id) && isset($referenceDoctor[$patients->reference_doctor_id])) ? $referenceDoctor[$patients->reference_doctor_id] : '';
                                @endphp
                                {{' care of '.$careof}}</h2>
                                
                        </div>
                        <div class="col-md-6 pull-right">
                            <a href="{{URL::to('get-all-report/'.encrypt($patients->id).'?status=')}}" target="_blank" class="mb-1 ml-1">
                                <button class="btn btn-primary pull-right">View Reports</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card pt-history">
                @foreach($history as $key => $type)
                    @foreach($type as $category => $data)
                        @foreach($data as $cyclePlan => $visit)
                            @if(!empty($visit))
                            @php
                                $cycleNo = !empty($cyclePlan) && strpos('/',(string)$cyclePlan) === true ? explode('/',(string)$cyclePlan)[0] : $cyclePlan;
                                $plan = !empty($cyclePlan) && strpos('/',(string)$cyclePlan) === true ? explode('/',(string)$cyclePlan)[1] : null;
                                // $cycleNo = null;
                                // $plan = null;
                            @endphp
                                <div class="header category-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><strong>{{Carbon\Carbon::parse($key)->format('d/m/Y').' - '.$category}}</strong></h2>

                                        </div>
                                        <div class="col-md-6 text-right">
                                                @if(!empty($cycleNo))
                                                    <strong>Cycle No : </strong>{{$cycleNo}}
                                                @endif
                                                @if(!empty($plan))
                                                    <strong>Plan : </strong>{{$plan}}
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="col-md-12">
                                        @php echo htmlspecialchars_decode(stripslashes($visit)); @endphp
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            </div>
        </div>
    <div>
@stop

@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="{{asset('public/js/ivf.js')}}"></script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script type="text/javascript">
       var page = '';
        var patientId = '';
        var referenceDoctorId = '';
        var search = '';
        var date = '';

        $(document).ready(function(){

            $(".daterange").daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                }
            });
        });
    </script>
@stop
