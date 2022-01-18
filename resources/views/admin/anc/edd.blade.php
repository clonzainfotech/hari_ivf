@extends('layouts.main')
@section('parentPageTitle', 'EDD Patients')
@section('title', 'EDD Patients')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>EDD Patients List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="post" autocomplete="off" action="">
                                    <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                                </form>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 anc">
                                {{ Form::select('patient_id',$patients,'',[
                                    'class'=>'form-control select-padding-0 patient-id',
                                    'placeholder'=>'Select Patient',
                                    'id' => 'patient_id',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-md-3">
                                <form method="post" autocomplete="off" action="">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </form>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);">
                                    <button class="btn btn-primary print-anc m-0">
                                        Print
                                    </button>
                                </a>
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

                        <div class="anc-data table-responsive active">
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
        var qstring = 'date='+date;
        var usg = '';
        var search = '';

        $(document).ready(function(){
            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search;
                getEddPatientList(qstring);
            });

            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search;
                getEddPatientList(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search;
                getEddPatientList(qstring);
            });

            $('.next-button').hide();
            getEddPatientList(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search;
                getEddPatientList(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search;
                getEddPatientList(qstring);
            });

            $(document).on('click', '.print-anc', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&isprint=1';
                getEddPatientList(qstring);
            });
        });

        // get all category data
        function getEddPatientList(qstring){
            $('.anc-loader').removeClass('d-none');
            $('.ancdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('edd-patient')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.anc-data').html(data.data);
                    $('.anc-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }

    </script>
@stop
