@extends('layouts.main')
@section('parentPageTitle', 'IUI')
@section('title', 'IUI')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix ">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>IUI List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3"><input type="text" class="form-control daterange" placeholder="Select Date"></div>
                                <div class="col-md-3 iui">
                                    {{ Form::select('patient_id',$patients,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </div>
                                <div class="col-md-3">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no" readonly="readonly" onfocus="this.removeAttribute('readonly')">
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

                        <div class="iui-data table-responsive active">
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
        var status = '';
        var date = $('.daterange').val();
        var qstring = 'date='+date;
        var hcg = '';
        var search = '';
        var type = '';

        $(document).ready(function(){
            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&hcg='+hcg+'&search='+search;
                getAdviceReportData(qstring);
            });

            $(document).on('change','select.hcg-type',function(e){
                e.preventDefault();
                hcg = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&hcg='+hcg+'&search='+search;
                getAdviceReportData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&hcg='+hcg+'&search='+search;
                getAdviceReportData(qstring);
            });


            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&hcg='+hcg+'&search='+search;
                getAdviceReportData(qstring);
            });

            getAdviceReportData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&hcg='+hcg+'&search='+search;
                getAdviceReportData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&hcg='+hcg+'&search='+search;
                getAdviceReportData(qstring);
            });
        });

        // get appointment data
        function getAdviceReportData(qstring){
            $('.iui-loader').removeClass('d-none');
            $('.iuidata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('advice-report-list')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.iui-data').html(data.adviceReport);
                    $('.iui-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.iui);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }

    </script>
@stop
