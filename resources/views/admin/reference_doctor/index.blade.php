@extends('layouts.main')
@section('parentPageTitle', 'Referance Doctor')
@section('title', 'Referance Doctor')

@section('page-style')


@stop

@section('content')

    <div class="row clearfix reference-doctor">
        <div class="card">
            <div class="header">
                <h2><strong>Referance Doctor List</strong></h2>
                <ul class="header-dropdown">
                    <li>
                        <a href="{{URL::to('reference-doctor/create')}}">
                            <button class="btn btn-primary">
                                Add
                            </button>
                        </a>
                    </li>
                    <li>
                        {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-reference-doctor">
                                Print
                            </button> </a> --}}</li>
                </ul>
            </div>
            <div class="body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    {{ Form::select('reference_doctor',$referenceDoctors,'',[
                                        'class'=>'form-control select-padding-0 reference-doctor-id',
                                        'placeholder'=>'Select Doctor',
                                        'id' => 'reference_doctor',
                                        'data-live-search' => 'true'
                                    ])}}
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <form method="post" autocomplete="off" action="">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search" placeholder="Search..." autocomplete="off">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                <div class="tab-content m-t-10">
                    <div class="reference-doctor-data">
                        <div class="row">
                            <div class="page-loader-wrapper medicine-loader">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script type="text/javascript">
        var qstring = '';
        var page = '';
        var search = '';
        var referenceDoctorId = '';
        var referanceDoctorId = '';

        $(document).ready(function(){

            getReferenceDoctorData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&reference_doctor_id='+referenceDoctorId+'&search='+search;
                getReferenceDoctorData(qstring);
            });

            $(document).on('click','.delete-reference-doctor',function(){
                referanceDoctorId = $(this).data('id');
                showConfirmMessage();
            });

            $(document).on('change','select.reference-doctor-id',function(){
                referenceDoctorId = $(this).val();
                qstring = 'page='+page+'&reference_doctor_id='+referenceDoctorId+'&search='+search;
                getReferenceDoctorData(qstring);
            });

            $(document).on('click','.edit-reference-doctor',function(){
                $('.id').val($(this).data('id'));
            });

            $(document).on('click', '.print-reference-doctor', function () {
                qstring = 'page='+page+'&search='+search+'&reference_doctor_id='+referenceDoctorId+'&isprint=1';
                getReferenceDoctorData(qstring);
            });
            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&reference_doctor_id='+referenceDoctorId;
               getReferenceDoctorData(qstring);
            });

        });
           $(document).on('dblclick', '#Reference-table tbody tr', function(event) {
                var referenceDoctorId = $(this).data('id');
                if(typeof(referenceDoctorId) !== 'undefined'){
                    var url = 'reference-doctor/'+referenceDoctorId+'/edit';
                    window.location.href = url;
                }
            });

        // get all referance doctor data
        function getReferenceDoctorData(qstring){
            $('.refbydata-loader').removeClass('d-none');
            $('.refbydata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('reference-doctor')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.reference-doctor-data').html(data.reference_data);
                    $('.refbydata-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.reference_data);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to delete this record",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                removeReferanceDoctor();
                $('.showSweetAlert').hide();
                location.reload();
                // swal("Deleted!", "Your referance doctor has been deleted.", "success");
            });
        }
        // remove ReferanceDoctor
        function removeReferanceDoctor(){
            $.ajax({
                url: "{{URL::to('reference-doctor/delete/')}}"+'/'+referanceDoctorId,
                dataType: 'json',
            }).done(function(data) {
                getReferenceDoctorData(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
