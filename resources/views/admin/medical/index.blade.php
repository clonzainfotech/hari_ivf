@extends('layouts.main')
@section('parentPageTitle', 'Medical')
@section('title', 'Medical')
@section('page-style')

    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix ivf">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Patient List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control daterange" placeholder="Select Date">
                            </div>
                            <div class="col-md-4">
                                {{ Form::select('patient_id',$patientsData,'',[
                                    'class'=>'form-control select-padding-0 patient-id',
                                    'placeholder'=>'Select Patient',
                                    'id' => 'patient_id',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no"  readonly="readonly" onfocus="this.removeAttribute('readonly')">
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
                        <div class="patients-data table-responsive active">
                            <!-- table data here include -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        var qstring = '';
        var page = '';
        var patientId = '';
        var status = '';
        var search = '';
        $(".daterange").daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });
        var date = $('.daterange').val();
        var qstring = 'date='+date;

        $(document).ready(function(){
            getPatientsData(qstring);
            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&date='+date;
                getPatientsData(qstring);
            });
            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&date='+date;
                getPatientsData(qstring);
            });

            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&date='+date;
                getPatientsData(qstring);
            });

            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&date='+date;
                getPatientsData(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&date='+date;
                getPatientsData(qstring);
            });

            $(document).on('dblclick', '#p-table tbody tr', function(event) {
                var patientsId = $(this).data('id');
                if(typeof(patientsId) !== 'undefined'){
                    var url = "{{URL::to('get-medicine')}}"+'/'+patientsId;
                    window.location.href=url;
                }
            });

        });

        // get appointment data
        function getPatientsData(qstring){
            $.ajax({
                url: "{{URL::to('medical')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.patients-data').html(data.patients);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.ivf);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }

    </script>
@stop
