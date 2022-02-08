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
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <form method="post" autocomplete="off" action="">
                                    <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                                </form>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 anc">

                                {{ Form::select('patient_id',$patients,'',[
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
                                <a href="javascript:void(0);">
                                    <button class="btn btn-primary print-infertility m-0">
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
        var refProId = '';
        var refDocId = '';
        var search = '';
        var patientStatus = '';

       /* var qstring = '';
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
*/

        $(document).ready(function(){
            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            $(document).on('change','select.patient-status',function(e){
                e.preventDefault();
                patientStatus = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            $('.next-button').hide();
            getReferenceDoctorProData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            //    $(document).on('click', '.pagination a', function (event) {
            //     event.preventDefault();
            //     page = $(this).attr('href').split('page=')[1];
            //     qstring = 'page=' + page + '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
            //     getReferenceDoctorProData(qstring);
            // });


            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            //  $(document).on('change','select.patient-id',function(){
            //     patientId = $(this).val();
            //     qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
            //     getReferenceDoctorProData(qstring);
            // });

            $(document).on('change','select.ref-pro',function(){
                refProId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

             /*$(document).on('change','select.ref-doctor-id',function(){
                refDocId = $(this).val();
                qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId +'&ref_doc_id='+refDocId;;
                getReferenceDoctorProData(qstring);
            });*/

            $(document).on('change','select.ref-doctor-id',function(){
                refDocId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });


           /* $(document).on('click', '.print-infertility', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&isprint=1';
                getReferenceDoctorProData(qstring);
            });*/

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
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.report_data);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 50);
                }
            }).fail(function() {

            });
        }

    </script>
@stop



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
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <form method="post" autocomplete="off" action="">
                                    <input type="text" class="form-control daterange" placeholder="Select Date" autocomplete="off">
                                </form>
                            </div>
                            <!-- <div class="col-ms-3 col-sm-3">
                                <div class="form-group daterange">
                                    {{ Form::text('daterange', '',  [
                                        'id' => 'daterange',
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Date',
                                        'data-date-container' => '#myModalId',
                                        'data-provide'=> 'datepicker',
                                        'autocomplete'=>'off'
                                    ]) }}
                                </div>
                            </div> -->
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
                                <a href="javascript:void(0);">
                                    <button class="btn btn-primary print-infertility m-0">
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
        var refProId = '';
        var refDocId = '';
        var search = '';
        var patientStatus = '';

        /*var qstring = '';
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
            });*/
            // getReferenceDoctorProData(qstring);


        $(document).ready(function(){
            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

              $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });



            $(document).on('change','select.patient-status',function(e){
                e.preventDefault();
                patientStatus = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });


            $('.next-button').hide();
            getReferenceDoctorProData(qstring);

           /* $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });*/



               $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });


            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

             /*$(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId ;
                getReferenceDoctorProData(qstring);
            });
*/
            $(document).on('change','select.ref-pro',function(){
                refProId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

            /*$(document).on('change','select.ref-pro',function(){
                refProId = $(this).val();
                 qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId +'&ref_pro_id='+refProId;
                getReferenceDoctorProData(qstring);
            });*/

            $(document).on('change','select.ref-doctor-id',function(){
                refDocId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&patient_status='+patientStatus+'&ref_pro_id='+refProId+'&date='+date+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });

           /* $(document).on('change','select.ref-doctor-id',function(){
                refDocId = $(this).val();
                 qstring = 'page=' + page + '&patient_id='+patientId+ '&fromdate=' + fromdate + '&todate=' + todate + '&reference_doctor_id=' + referenceDoctorId +'&ref_pro_id='+refProId+'&ref_doc_id='+refDocId;
                getReferenceDoctorProData(qstring);
            });*/

           /* $(document).on('click', '.print-infertility', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&search='+search+'&isprint=1';
                getReferenceDoctorProData(qstring);
            });*/

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
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.report_data);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 50);
                }
            }).fail(function() {

            });
        }

    </script>
@stop


 /*public function referenceDoctorProReport(Request $request){
        try{
            $patients = $this->OpdPatients->where('is_approved',1)->pluck('name','id');
            $referenceDoctorPro = $this->ReferenceDoctorPro->pluck('name','id');
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            if($request->ajax()){
                $patients = $this->OpdPatients->whereNotIn('reference_doctor_id',[3,500,90,107,452,471,489,1,32,387,30]);


           /* $patients = $this->OpdPatients
                ->join('appointments','appointments.patients_id','=','patients.id')
                ->whereNotIn('patients.reference_doctor_id',[3,500,90,107,452,471,489,1,32,387,30]);

               if($request->date){
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d H:i:s');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d H:i:s');
                    if($date){
                        $patients = $patients->whereBetween('created_at', [$startDate, $endDate]);
                    }
                }*/
               /* $iuiReport = $this->IndoorDeposit
                    ->whereNotIn('reference_doctor_id', [1,12,32])
                    ->where([
                        ['charge_type', '=', 3],
                        ['case_type', '=', 'Credit'],
                    ])
                    ->orderBy('id', 'DESC');


                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $patients = $patients->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $iuiReport = $iuiReport->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);
                }

                $patientId = $request->patient_id;
                if($patientId) {
                    $patients = $patients->where('id',$patientId);
                }

                $refProId = $request->ref_pro_id;
                if($refProId){
                    $patients= $patients->where('reference_doctor_pro_id',$refProId);
                }
                $refDocId = $request->ref_doc_id;
                if($refDocId){
                    $patients= $patients->where('reference_doctor_id',$refDocId);
                }

                 if($request->isprint==1){
                    $patients = $patients->get();

                    return response()->json([
                       View::make('admin.report.ref_pro_doctor.preview',compact('patients'))->render()
                    ]);
                }*/

              /*  if($request->isprint == 1){
                    $patients = $patients->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['report_data'] = View::make('admin.report.ref_pro_doctor.preview',compact('patients'))->render();
                    return $data;
                }*/
             /*   $patients = $patients->orderBy('id','DESC')->paginate(50);
                $data['status'] = 1;
                $data['report_data'] = View::make('admin.report.ref_pro_doctor.data',compact('patients'))->render();
                return $data;
            }
            return view('admin.report.ref_pro_doctor.index',compact('patients','referenceDoctorPro','referenceDoctor'));
        }catch(Exception $e){
            abort(500);
        }
    }*/




            /*if($request->date){
                $date = explode("-",$request->date);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d H:i:s');
                $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d H:i:s');
                if($date){
                    $patients = $patients->whereBetween('patients.created_at', [$startDate, $endDate]);
                }
            }
            */
