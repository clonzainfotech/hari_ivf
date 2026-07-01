@extends('layouts.main')
@section('parentPageTitle', 'ANC')
@section('title', 'ANC')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>ANC List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="post" autocomplete="off" action="">
                                        <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 anc">
                                    {{ Form::select('patient_id',$patients,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-md-2">
                                    {{Form::select('usg',['1'=>'Early Scan','2'=>'NT Scan','3'=>'Anomalies Miles','4'=>'Growth Scan'],'',[
                                        'class'=>'usg select-padding-0 w-100',
                                        'placeholder'=>'Select USG Type'])
                                    }}
                                </div>
                                <div class="col-md-3">
                                    <form method="post" autocomplete="off" action="">
                                        <ul class="nav nav-tabs padding-0">
                                            <div class="input-group">
                                                <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no" autocomplete="off">
                                                <span class="input-group-addon search-border">
                                                    <i class="zmdi zmdi-search"></i>
                                                </span>
                                            </div>
                                        </ul>
                                    </form>
                                </div>
                                <div class="col-md-1">
                                    {{-- <a href="javascript:void(0);">
                                        <button class="btn btn-primary print-anc m-0">
                                            Print
                                        </button>
                                    </a> --}}
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
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search;
                getAncData(qstring);
            });

            $(document).on('change','select.usg',function(e){
                e.preventDefault();
                usg = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search;
                getAncData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search;
                getAncData(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search;
                getAncData(qstring);
            });

            $('.next-button').hide();
            getAncData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search;
                getAncData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search;
                getAncData(qstring);
            });

            $(document).on('dblclick', '#appointment-table tbody tr', function(event) {
                var patientsId = $(this).data('id');
                if(typeof(patientsId) !== 'undefined'){
                    var type = 'next-appointment';
                    if($(this).hasClass('anc')){
                        var type = 'history';
                    }
                    var url = "{{URL::to('anc')}}"+'/'+type+'/'+patientsId;
                    window.location.href=url;
                    $('.footer-popup').removeClass('d-none');
                }
            });
            $(document).on('click', '.print-anc', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&usg='+usg+'&search='+search+'&isprint=1';
                getAncData(qstring);
            });
        });

        // get all category data
        function getAncData(qstring){
            $('.anc-loader').removeClass('d-none');
            $('.ancdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('anc')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.anc-data').html(data.anc);
                    $('.anc-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.anc);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }

        function openButtons(){
            var x = document.getElementById("popupForButtons");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }

    </script>
@stop
