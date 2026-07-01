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
                    <h2><strong>Appointment Request List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            {{-- <a href="#"> <button class="btn btn-primary print-appointmentrequest">Print</button> </a> --}}</li>
                    </ul>
                </div>

                <div class="body">

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

                            <table class="table m-b-0 table-hover" id="appointment-request-table">
                                <thead>
                                    <tr>
                                        <th>Appointment</th>
                                        <th>Time</th>
                                        <th>Patient Name</th>
                                        <th>Create On</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointmentRequest as $requests)
                                        <tr>
                                            <td>{{$requests->appointment_date}}</td>
                                            <td>{{$requests->appointment_time}}</td>
                                            <td class="patient_name">{{strtolower($requests->getPatients['name'])}}</td>
                                            <td >{{ cdate($requests->created_at)->format('d-m-Y h:i A')}}</td>
                                            <td>
                                                @if($requests->getPatients['is_approved'] == 1)
                                                    <a class="apt-approve" data-id="{{encrypt($requests->id)}}"><span class="badge is-bill badge-success">Approve</span></a>
                                                    <a class="apt-reject" data-id="{{encrypt($requests->id)}}" data-target="#reject-modal" data-toggle="modal"><span class="badge is-bill badge-danger">Reject</span></a>
                                                @else
                                                    <a class="" data-id=""><span class="badge badge-danger">New Patient</span></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <td colspan='4'
                                            class="text-center">No records available</td>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('modal')
    <div class="modal fade" id="reject-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Appointment Reject</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline','id'=>'reject-form'])}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="col-md-2 form-padding">
                                    Language:
                                </div>
                                <div class="col-md-8 form-padding">
                                    {{Form::select('language',['en'=>'English','gu'=>'Gujarati','hn'=>'Hindi'],'',['class'=>'form-control language w-inherit'])}}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-2 form-padding">
                                    Reason:
                                </div>
                                <div class="col-md-8 form-padding d-none other-reason">
                                    {{form::textarea('remark','',['class'=>'form-control other','rows'=>2])}}
                                </div>
                                <div class="col-md-8 form-padding reason gu d-none">
                                    {{Form::select('remark',config('app.reject_apt_reason_gu'),'',['class'=>' select-padding-0 select-reason gu'])}}
                                </div>
                                <div class="col-md-8 form-padding reason en">
                                    {{Form::select('remark',config('app.reject_apt_reason_en'),'',['class'=>'select-padding-0 select-reason en'])}}
                                </div>
                                <div class="col-md-8 form-padding reason hn d-none">
                                    {{Form::select('remark',config('app.reject_apt_reason_hn'),'',['class'=>'select-padding-0 select-reason hn'])}}
                                </div>
                            </div>
                        </div>
                        <span class="form-error-msg remark-error d-none">This Field is Required</span>
                    </div>
                    <!-- footer -->
                    <div class="modal-footer w-100 justify-content-center">
                        <button type="button" class="btn btn-primary waves-effect reject-apt" data-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-default waves-effect ml-3" data-dismiss="modal">Close</button>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script type="text/javascript">

        var apRequestId = '';
        var qstring = '';
        $('.apt-approve').click(function () {
            apRequestId = $(this).data('id');
            qstring = 'appointment_req_id='+apRequestId;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'appointment-request/'+ apRequestId +'/approve?'+qstring,
                type: "POST",
                dataType: 'json',
            }).done(function() {
                location.reload();
            }).fail(function(){

            });
        });

        $('.apt-reject').click(function () {
            apRequestId = $(this).data('id');
        });
        $('.reject-apt').click(function() {
            $('.remark-error').addClass('d-none');
            $('.language').selectpicker('refresh');
            var lan = $('select.language').val();
            
            if($('.other-reason').hasClass('d-none'))
            {
                var reason = $("select."+lan+" option:selected").text();
            }
            else
            {
                var reason = $(".other").val();
            }
            
            if(reason == '')
            {
                $('.remark-error').removeClass('d-none');
                return false;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'appointment-request/'+ apRequestId +'/reject',
                type: "POST",
                dataType: 'json',
                data:{reason:reason},
            }).done(function() {
                location.reload();
            }).fail(function() {
            });
        });
        $(document).on('click', '.print-appointmentrequest', function () {
            $.ajax({
                url: "{{URL::to('appointment-request')}}?isprint=1",
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.appointmentData);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {
                
            });
        });
        $(document).on('change','select.language',function(){
            var lan = $(this).val();
            $('.other-reason').addClass('d-none');
            $('.reason').addClass('d-none');
            $('.reason.'+lan).removeClass('d-none');
        })
        $(document).on('change','select.select-reason',function(){
            $('.other-reason').addClass('d-none');
            if($(this).val() == 'other')
            {
                $('.other-reason').removeClass('d-none');
                $('.reason').addClass('d-none');
            }
        })
    </script>

@stop
