@extends('layouts.main')
@section('parentPageTitle', 'Reference Doctor Pro')
@section('title', 'Reference Doctor Pro')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Reference Doctor Pro List</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                           <!--  <div class="col-lg-3 col-md-3 col-sm-3">
                                <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                            </div> -->
                            <div class="col-ms-3 col-sm-3">
                                <div class="form-group daterange">
                                    <form method="post" autocomplete="off" action="">
                                    {{ Form::text('daterange', '',  [
                                        'id' => 'daterange',
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Date',
                                        'data-date-container' => '#myModalId',
                                        'data-provide'=> 'datepicker',
                                        'autocomplete'=>'off'
                                    ]) }}
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 anc">

                                {{ Form::select('patient_id',$appointment,'',[
                                    'class'=>'form-control select-padding-0 patient-id',
                                    'placeholder'=>'Select Patient',
                                    'id' => 'patient_id',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 anc">
                                {{ Form::select('ref_pro_doctor',$referenceDoctorPro,'',[
                                    'class'=>'form-control select-padding-0 ref-pro',
                                    'placeholder'=>'Select Reference Doctor Pro',
                                    'id' => 'reference-doctor-pro',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6 anc">
                                {{ Form::select('ref_doctor',$referenceDoctor,'',[
                                    'class'=>'form-control select-padding-0 ref-doctor-id',
                                    'placeholder'=>'Select Reference Doctor',
                                    'id' => 'reference-doctor',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-md-1">
                                {{-- <a href="javascript:void(0);"> <button class="btn btn-primary print-infertility m-0">
                                        Print
                                    </button> </a> --}}</div>
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

                        <div class="ref-doctor-pro-data table-responsive active" id="tabledata">
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
        /*$(".daterange").daterangepicker({
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
        var refProId = '';
        var refDocId = '';
        var search = '';
        var patientStatus = '';
*/

        var qstring = '';
        var page = '';
        var status = '';
        var refProId = '';
        var refDocId = '';
        var search = '';
        var patientStatus = '';
        var patientId = '';
        var fromdate = moment(new Date()).format('YYYY-MM-DD');
        var todate = moment(new Date()).format('YYYY-MM-DD');
        var categoryId = '';
        var referenceDoctorId = '';
        var qstring = 'fromdate=' + fromdate + '&todate=' + todate ;

        $(document).ready(function () {
            $('input[name="daterange"]').daterangepicker({
                locale: {
                    direction: 'drop-down-date-range',
                    cancelLabel: 'Clear',
                    format: 'D/M/Y',
                    container: '#myModalId'
                }
            });
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {

                fromdate = picker.startDate.format('YYYY-MM-DD');
                todate = picker.endDate.format('YYYY-MM-DD');
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getReferenceDoctorProData(qstring);
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $("#daterange").val('');
                fromdate = '';
                todate = '';
                qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getReferenceDoctorProData(qstring);
            });
            getReferenceDoctorProData(qstring);


            $('.next-button').hide();
            getReferenceDoctorProData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&ref_doc_id='+refDocId+'&fromdate=' + fromdate + '&todate=' + todate;
                getReferenceDoctorProData(qstring);
            });

             $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getReferenceDoctorProData(qstring);
            });

            $(document).on('change','select.ref-pro',function(){
                refProId = $(this).val();
                 qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId +'&ref_pro_id='+refProId;
                getReferenceDoctorProData(qstring);
            });


            $(document).on('change','select.ref-doctor-id',function(){
                refDocId = $(this).val();
                 qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId +'&ref_pro_id='+refProId+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            $(document).on('click', '.ivf-package', function () {
                var packageId = $(this).data('id');
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&package_id='+packageId;
                getReferenceDoctorProData(qstring);
            });
        });

         $(document).on('click', '.print-infertility', function () {
            var isprint = 1;
            $.ajax({
                url: "{{URL::to('ref-pro-doctor-report')}}?" + qstring,
                data: {isprint},
                dataType: 'json',
            }).done(function (data) {
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data);
                w.document.close();
                w.window.print();
            });
        });

        // get all category data
        function getReferenceDoctorProData(qstring){
            $('.anc-loader').removeClass('d-none');
            $('.ancdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ref-pro-doctor-report')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.ref-doctor-pro-data').html(data.report_data);
                    $('.anc-loader').addClass('d-none');
                }
            }).fail(function() {

            });
        }

    </script>
@stop
