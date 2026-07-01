@extends('layouts.main')
@section('parentPageTitle', 'Donor')
@section('title', 'Donor')
@section('page-style')
    {{-- <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet"> --}}
@stop
@section('content')
    <div class="row clearfix appointment">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Donor List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('create-donor')}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="#"> <button class="btn btn-primary print-donar">Print</button> </a> --}}</li>
                    </ul>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <input type="text" class="form-control daterange" placeholder="Select Date">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
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
                                            <input type="number" class="form-control search-mobile-number" placeholder="Search by mobile no">
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

                        <div class="donor-data table-responsive active">
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

        var page = '';
        var patientId = '';
        var date = '';
        var search = '';

        $('.daterange').daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });

        date = $('.daterange').val();
        qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;

        $(document).ready(function() {

            getDonorData(qstring);

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;
                getDonorData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;
                getDonorData(qstring);
            });

            $('.daterange').change(function(){
                date = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;
                getDonorData(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;
                getDonorData(qstring);
            });

            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;
                getDonorData(qstring);
            });

            $(document).on('click','.btn-success',function(e){
                e.preventDefault();
                date = $('.daterange').val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date+'&search='+search;
                getDonorData(qstring);
            });

            $(document).on('click','.print-donar',function(){
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&is_print=1';
                getDonorData(qstring);
            });

            $(document).on('dblclick', '#donor-table tbody tr', function(event) {
                return false;
                var appointmentId = $(this).data('id');
                if(typeof(appointmentId) !== 'undefined'){
                    var url = 'edit-donor/'+appointmentId+'/edit';
                    window.location.href = url;
                }
            });

            $(document).on('click','.delete-donor-appointment',function(){
                var appointmentId = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: 'You want to delete this record',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00cfd1',
                    confirmButtonText: 'Yes, delete it!',
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{URL::to('donor-delete')}}",
                        data: {
                            appointment_id: appointmentId,
                        },
                        dataType: 'json',
                        type: 'POST'
                    }).done(function(data) {
                        if (data.status == true) {
                            swal('Deleted!', data.message, 'success');
                            page = 1;
                            qstring = 'page=' + page + '&patient_id=' + patientId + '&date=' + date;
                            getDonorData(qstring);
                        } else {
                            swal('Oops!', data.message, 'error');
                        }
                    }).fail(function() {
                    });

                });
            });
        });


        // get all donor data
        function getDonorData(qstring){
            $('.donor-loader').removeClass('d-none');
            $('.donordata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('donor')}}?"+qstring,
                dataType: 'json',
            }).done(function(data){
                if(data.status == 1){
                    $('.donor-data').html(data.data);
                    $('.donor-loader').addClass('d-none');
                }else{
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
