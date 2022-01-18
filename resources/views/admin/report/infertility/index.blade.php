@extends('layouts.main')
@section('parentPageTitle', 'Infertility Patients')
@section('title', 'Infertility Patients')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Infertility Patients List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <form method="post" autocomplete="off" action="">
                            <div class="row">
                                <div class="col-lg-2 col-md-3">
                                    <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-6 anc">
                                    {{ Form::select('patient_id',$patients,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 anc">
                                    {{ Form::select('patient_status',[1=>'Active',2=>'Deactive'],'',[
                                        'class'=>'form-control select-padding-0 patient-status',
                                        'placeholder'=>'Select Patient Status',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no" autocomplete="off">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-6 anc">
                                    {{ Form::select('report_type',[1=>'IVF',2=>'IUI'],'1',[
                                        'class'=>'form-control select-padding-0 report_type',
                                        'placeholder'=>'Select Report Type',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0);">
                                        <button class="btn btn-primary print-infertility m-0" type="button">
                                            Print
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </form>
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

                        <div class="infertility-data table-responsive active">
                            <div class="row">
                                <div class="page-loader-wrapper medicine-loader">
                                    <div class="loader">
                                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="popupForButtons">
        <button class="btn btn-info next-anc">Next</button>
    </div>

@stop
@section('page-script')
    <script type="text/javascript">
        $(".daterange").daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });
        var qstring = '';
        var page = '';
        var patientId = '';
        var categoryId = '';
        var status = '';
        var date = $('.daterange').val();
        var report_type = $('select.report_type').val();
        var qstring = 'date='+date+'&report_type='+report_type;
        var usg = '';
        var search = '';
        var patientStatus = '';
        // var report_type = '';

        $(document).ready(function(){
            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });

            $(document).on('change','select.patient-status',function(e){
                e.preventDefault();
                patientStatus = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });
            $(document).on('change','select.report_type',function(e){
                e.preventDefault();
                report_type = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });

            $('.next-button').hide();
            getInfertilityData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&patient_status='+patientStatus+'&report_type='+report_type;
                getInfertilityData(qstring);
            });

            $(document).on('click', '.print-infertility', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&isprint=1';
                getInfertilityData(qstring);
            });

            $(document).on('click', '.ivf-package', function () {
                var packageId = $(this).data('id');
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&package_id='+packageId;
                getInfertilityData(qstring);
            });
        });

        // get all category data
        function getInfertilityData(qstring){
            $('.anc-loader').removeClass('d-none');
            $('.ancdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('infertility-report')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.infertility-data').html(data.infertility_data);
                    $('.anc-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.infertility_data);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 50);
                }
            }).fail(function() {

            });
        }

    </script>
@stop
