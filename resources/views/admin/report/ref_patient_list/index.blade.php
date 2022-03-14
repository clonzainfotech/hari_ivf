@extends('layouts.main')
@section('parentPageTitle', 'Patient')
@section('title', 'Patient')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@endsection
@section('content')

    <div class="row clearfix appointment">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Patients List</strong></h2>
                    <ul class="header-dropdown">
                        {{-- <li>
                            <a href="{{URL::to('create-patient')}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <button class="btn btn-primary patients-print">
                                    Print
                                </button>
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="post" autocomplete="off" action="">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search-mobile-number" placeholder="Search by Reference" autocomplete="off">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                    </form>
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
                        {{-- <div class="alert alert-success message d-none">
                            <strong>Success! </strong>Patient has been updated
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="zmdi zmdi-close"></i>
                                </span>
                            </button>
                        </div> --}}

                        <div class="patient-data table-responsive active">
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

@endsection
@section('page-script')
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        var page = '';
        var patientId = '';
        var referenceDoctorId = '';
        var search = '';
        var date = '';
        var qstring = 'page=' + page +'&search='+search;
        var label_name = '';


        $(document).ready(function(){

            $(".daterange").daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                }
            });
            getPatientData(qstring);


            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page=' + page +'&search='+search+'&date='+date;
                getPatientData(qstring);
            });

            
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page=' + page+'&search='+search;
                getPatientData(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                qstring = 'page=' + page+'&search='+search;
                getPatientData(qstring);
            });

        });
        function getPatientData(qstring){
            $('.patient-loader').removeClass('d-none');
            $('.patientdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ref-patient-report')}}?" + qstring,
                dataType: 'json',
            }).done(function(data){
                if(data.status == 1){
                    $('.patient-data').html(data.patients);
                    $('.patient-loader').addClass('d-none');
                }else{
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.patients);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        
    </script>
@stop
