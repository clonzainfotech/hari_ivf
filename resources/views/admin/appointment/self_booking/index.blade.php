@extends('layouts.main')
@section('parentPageTitle', 'Appointment')
@section('title', 'Appointment')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop

@section('content')
    <div class="row clearfix appointment">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Patient Booking List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="#">
                                {{-- <button class="btn btn-primary print-appointmentrequest">Print</button> --}}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="body">
                    {{-- <div class="col-md-12"> --}}
                        <div class="col-md-3">
                            <ul class="nav nav-tabs padding-0">
                                <div class="input-group">
                                    <input type="text" class="form-control search-word" placeholder="Search by word">
                                    <span class="input-group-addon search-border">
                                        <i class="zmdi zmdi-search"></i>
                                    </span>
                                </div>
                            </ul>
                        </div>
                    {{-- </div> --}}
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">
                        <div class="appointment-request-data table-responsive active">
                            <!-- table data here include -->
                            @if(Session::has('msg'))
                                <div class="alert alert-warning">
                                   {{Session::get('msg')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">
                                            <i class="zmdi zmdi-close"></i>
                                        </span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-self-booking">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('modal')
@stop
@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script type="text/javascript">

        var apRequestId = '';
        var qstring = '';
        var search = '';
        var page='';
        getSelfBookingData(qstring);

        $(document).on('click', '.pagination a',function(event){
            event.preventDefault();
            page=$(this).attr('href').split('page=')[1];
            qstring = 'page='+page+'&search='+search;
            getSelfBookingData(qstring);
        });
        $(document).on('keyup','.search-word',function(){
            search = $(this).val();
            qstring = 'page='+page+'&search='+search;
            getSelfBookingData(qstring);
        });
        function getSelfBookingData(qstring){
            $.ajax({
                url: "{{URL::to('self-booking')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.table-self-booking').html(data.selfBookingList);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.category);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        $(document).on('click','.apt-approve',function () {
            var patient_signup_id = $(this).data('id');
            swal({
                title: 'Are you sure?',
                text: "You want to Approve this Patient !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes!",
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                window.location.href = '{{URL::to("/create-patient")}}?booking_id='+patient_signup_id;
                $('.showSweetAlert').hide();
            });
        });
        
    </script>

@stop
