@extends('layouts.main')
@section('parentPageTitle', 'ANC Appointment History')
@section('title', 'ANC Appointment History')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">
    <style>
        .fefal-visible {
            display: none;
        }
        .duration-data .dropdown-men ul{
            max-height: 190px !important;
        }
        .selectize-control.multi .selectize-input.disabled [data-value] {
            color: black !important;
        }
        .footer-top-border{
            border-top: 1px solid #9ea2a2 !important;
            padding: 1rem !important;
        }
        .header-bottom-border{
            border-bottom: 1px solid #9ea2a2 !important;
            padding: 1rem 1rem !important;
        }
        .anc-details-close{
            margin-top: -22px !important;
        }
        
        .G-sac-border
        {
            border: 2px solid #b9afaf !important;
        }
        .autoRemark
        {
            font-size: 1.3em;
            font-weight: 400;
        }
    </style>
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header d-flex">
                    <div class="col-md-6">
                        @php
                            $careOf = (!empty($ancData->getPatients['reference_doctor_id']) && isset($referenceDoctor[$ancData->getPatients['reference_doctor_id']])) ? $referenceDoctor[$ancData->getPatients['reference_doctor_id']] : '';
                        @endphp
                        <h2><strong class="text-secondary"> {{ucwords($ancPatients->name)}}</strong>{{' care of '.$careOf}}</h2>
                    </div>
                    <div class="col-md-6">
                        <a href="{{URL::to('get-all-report/'.encrypt($ancData->getPatients['id']).'?status=anc')}}" class="btn btn-primary pull-right">View Reports</a>
                        @if($isConceivedIUI)
                            <a href="{{URL::to('iui/history/'.encrypt($ancData->getPatients['id']))}}" target="_blank" class="btn btn-primary pull-right">IUI History</a>
                        @endif
                        @if($isConceivedIVF)
                            <a href="{{URL::to('ivf/history/'.encrypt($ancData->getPatients['id']))}}" target="_blank" class="btn btn-primary pull-right">IVF History</a>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <strong class="pr-3">ANC Previous Visit</strong>
                            <ul class="header-dropdown col-md-12 align-right">
                                <li class="w-50">
                                    @if(!empty($getTotalAncNumber))
                                    <li class="w-25">
                                        {{Form::select("previous_anc_id",$getTotalAncNumber,'',['class'=>'form-control select-padding-0 anc_visit_id','placeholder'=>'Select Previous Anc.','data-class'=>'previous'])}}
                                    </li>
                                        {{-- <button class="btn btn-primary preview-file" data-class="previous" data-id="{{encrypt($firstANCData->id)}}">Previous ANC</button> --}}
                                    @endif
                                </li>
                            </ul>
                            @if(count($date)>0)
                                <?php
                                $date = array_reverse($date);
                                $ii = 1;
                                ?>
                                @foreach($date as $k => $dt)
                                    <?php $ij = $ii++;?>
                                    {{Form::radio("date",$dt,'',[
                                            'id'=>'dt_'.$ij,
                                            'class'=>'anc-date',
                                        ])}}
                                    <label class="pl-0 pr-3" for="dt_{{$ij}}">
                                        {{\Carbon\Carbon::parse($dt)->format('d-m-Y') }}
                                    </label>
                                @endforeach
                            @endif
                            <a href="javascript:void(0)" class="preview-file mt-0" data-class="current" data-id="{{encrypt($ancCurrent->id)}}">Preview all</a>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">

                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form','files'=>true,'id'=>'anc-form'])}}
                                <div class="anc-history">

                                </div>
                                {{Form::hidden('patients_id',$patientsId,['class'=>'patients-id'])}}
                                <div class="col-sm-12">
                                    {{Form::hidden('next_date','',['class'=>'next-date-value'])}}
                                    {{Form::hidden('next_time','',['class'=>'next-time-value'])}}
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
                                    {{-- <a class="btn btn-primary next-appointment text-white">Save & Next Appointment</a> --}}
                                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                    <button type="submit" class="btn btn-primary admission-print submit d-none" value="3">Admission Print</button>

                                    <a href="{{URL::to('anc')}}" class="btn btn-default">Cancel</a>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('modal')
    <div class="modal fade" id="next-appointment-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    <h4 class="title" id="next-appointment">Next Appointment</h4>
                </div>
                <!-- body -->
                {{Form::open(['class'=>'form-inline','id'=>'next-appointment'])}}
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Day
                            </div>
                            <div class="col-md-5">
                                {{-- {{Form::number('day','',['class'=>'form-control next-day','placeholder'=>'Day','min'=>1])}} --}}
                                <span class="form-error-msg day w-100"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Date
                            </div>
                            <div class="col-md-5">
                                {{-- {{Form::date('date','',['class'=>'form-control next-date','placeholder'=>'Date','min'=>0])}} --}}
                                <span class="form-error-msg date"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                Time
                            </div>
                            <div class="col-md-5">
                                {{Form::select('next-time',$hospitalTime,'',['class'=>'form-control next-time','placeholder'=>'Time'])}}
                                <span class="form-error-msg date"></span>
                            </div>
                            <span class="form-error-msg time"></span>
                        </div>
                    </div>
                </div>
                {{Form::hidden('appointment-id','',['class'=>'appointment-id'])}}
                <!-- footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect next-appointment-form">SAVE CHANGES</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
    <div class="modal fade preview-file-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header header-bottom-border">

                
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" id="myModalLabel">ANC History</h5>
                    </div>
                </div>
                    <button type="button" class="close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="anc-details-data">
                    </div>
                </div>

                <div class="modal-footer footer-top-border text-right d-inline-block">
                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                    
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('public/js/anc.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
$.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>

<script type="text/javascript">
    var code = '';
    var patientsId = $('.patients-id').val();
    var date = '';
    var qstring = '';
    var ancQstring = '';
    var status = '1';
    var type = '1';
    var ancCycleStatus = 'current';
    var anc_id = '';
    $(document).ready(function(){
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        qstring = 'date=' + date;

        var ancDate = localStorage.getItem('anc_date');

        if (ancDate != null) {
            localStorage.removeItem('anc_date')
            qstring = 'date=' + ancDate;
            $('.anc-date').val(ancDate);
            $('.anc-date').selectpicker('refresh');
        }
       
        getAncHistoryData(qstring);

        $(document).on('click','.submit',function(e){
            e.preventDefault();
            var isError = errorMessage();
            if (isError == false) {
                return false;
            }
            var formData = new FormData($("#anc-form")[0]);
            if(this.value==1){
                formData.append('isprint', 1);
            }
            
            if(this.value==2){
                formData.append('is_pdf', 1);
                $(this).prop('disabled',true);
            }
            if(this.value==3){
                formData.append('isprint', 3);
            }
            ancFormData(formData);
        });

        $(document).on('change','select.anc_visit_id',function(e){
            e.preventDefault();
            anc_id = $(this).val();
            if(anc_id != '')
            {
                ancCycleStatus = $(this).data('class');
                $('.preview-file-modal').modal('hide');
                $('.anc-details-data').html('');
                $('.preview-file-modal').modal('show');
                
                ancQstring = 'patient_id='+patientsId+'&status='+status+'&type='+type+'&anc_id='+anc_id;
                getANCHistoryData(ancQstring);
            }
            
        });
        $(document).on('click','.preview-file',function(e){
            e.preventDefault();
            anc_id = $(this).data('id');
            ancCycleStatus = $(this).data('class');
            $('.preview-file-modal').modal('hide');
            $('.anc-details-data').html('');
            $('.preview-file-modal').modal('show');
            
            ancQstring = 'patient_id='+patientsId+'&status='+status+'&type='+type+'&anc_id='+anc_id;
            getANCHistoryData(ancQstring);
            
        });

        // $(document).on('click','.next-appointment-form',function(e){
        //     e.preventDefault();
        //     var date = $('.next-date-value').val();
        //     var time = $('select.next-time').find("option:selected").text();
        //     var timeVal = $('.next-time-value').val(time);
        //     var anc = $('#anc-form').serialize();
        //     anc = anc + '&isprint=' + '1';
        //     ancFormData(anc,'next');
        // });

        $(document).on('click','.next-appointment',function(){
            var isError = errorMessage();
            if (isError == true) {
                $('#next-appointment').trigger('reset');
                $('#next-appointment-modal').modal('show');
            }
        });

        $(document).on('change','input.anc-date',function(e){
            date = $(this).val();
            qstring = 'date='+date;
            getAncHistoryData(qstring);
        });

        $(document).on('change','select.duration-data',function(){
            var value = $(this).val();
            var dId = $(this).data('id');
            $('.'+dId).addClass('d-none');
            if(value == 'other'){
                $('.'+dId).removeClass('d-none');
            }

        });
    });

    $(document).on('click','.fcp-type-1',function(){
        checkGynec();
    });

    $(document).on('click','.blighted-ovum',function(){
        checkGynec();
    });

    $(document).on('click','.anc-details-close', function () {
        location.reload();
    });

    $(document).on('click','.remark-remove',function(){
        userId =document.getElementById("preId").value;
        tId=document.getElementById("tableId").value;
        showConfirmMessage();
    });

    $(document).on('click','.edit-btn',function(){
        date = $(this).data('date');
        qstring = 'date='+date;
        $("input:radio[name='date'][value='" + date + "']").attr('checked', 'checked');
        $('.preview-file-modal').modal('hide');
        getAncHistoryData(qstring);
    });

    $(document).on('click','.print-btn',function(){
        date = $(this).data('date');
        qstring = 'patient_id='+patientsId+'&history_date='+date+'&anc_id='+anc_id;
        getANCHistoryData(qstring);
    });

    function showConfirmMessage() {
        swal({
            title: "Are you sure?",
            text: "You want to hide remark",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#00CFD1",
            confirmButtonText: "Yes, Remove it!",
            closeOnConfirm: false
        }, function () {
            removeUser();
            $(".remarkhide").addClass("d-none");
            swal("Remove!", "Remark Has been Removed.", "success");
            // location.reload();
        });
    }

    function ancFormData(data,next=null){
        $('.submit').prop('disabled', true);
        var url = "{{URL::to('anc')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'{{URL::to("anc")}}',
            type:'POST',
            dataType:'json',
            data:data,
            enctype: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false
        }).done(function(data){
            $('.how-much-error').val('');
            if(data.status == 'true'){
                window.location.href = url;
            }else if(data.status == 1){
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data.data);
                w.document.close();
                w.window.print();
                $('#anc_history_id').val(data.id);
                // window.location.href = url;
                $('#next-appointment-modal').modal('hide');
            }else if(data.status == '2'){
                $('.how-much-error').text('Please enter valid number');
            }else if(data.status == '3'){
                $('#anc_history_id').val(data.id);
                window.location.href = url;
            }
            else{
                location.reload();
            }
        });
    }
    function errorMessage() {
        var valid = 1;
        $('.ho-data-msg').text('');
        $('.co-value-msg').text('');
        $('.gsac-no-data-followup').text('');
        $('.weight').text('');
        $('.ho-tab').removeClass('show');
        $('.p-info').removeClass('show');
        var weight=document.getElementById('weight').value;
        $('.fcp-error').text('');
        $('#oe').removeClass('show');
        weight = $('.weight').val();
        var ancHistoryId = $('.is-weight').val();
        if(ancHistoryId == 1){
            weight = $('.weight-2').val();
        }

        if (weight == '') {
            valid = 0;
            $('.weight').text('The weight field is required.');
            $('.p-info').addClass('show');
            $('.ho-tab').addClass('show');
        }
        if ($("input[name='oe[follow_up]']").val() == '' && $('.is-followup').val() == 1) {
            valid = 0;
            $('.gsac-no-data-followup').text('The Follow up Date is required.');
            $('html, body').animate({
                scrollTop: ($('.gsac-no-data-followup').offset().top - 150)
            }, 200);
            return false;
        }
        if(valid == 0){
            $('html, body').animate({
                scrollTop: ($('.weight').offset().top - 150)
            }, 1000);
            return false;
        }
        // if(!$(".fcp_type:checked").val() && $('select.ut-sac').val() >= 3){
        //     $('.fcp-error').text('Please select one option in FCP.');
        //     $('html, body').animate({
        //         scrollTop: ($('.fcp_type').offset().top - 150)
        //     }, 1000);
        //     $('#oe').addClass('show');
        //     return false;
        // }
        if($('select.seen-by').val() == ''){
            $('.seen-by-error').text('Please select doctor');
            $('html, body').animate({
                scrollTop: ($('.seen-by').offset().top - 150)
            }, 1000);
            return false;
        }
        return true;
    }

    function getAncHistoryData(qstring){
        $('.submit').prop('disabled', false);
        $.ajax({
            url:"{{URL::to('anc/history')}}"+'/'+patientsId+'?'+qstring,
            dataType:'json',
            type:'GET',
        }).done(function(data){
            if(data.anc != null){
                $('.patients-basic-information').removeClass('d-none');
                $('.investigation').removeClass('d-none');
                $('.injection').removeClass('d-none');
                $('.m-h').removeClass('d-none');
                $('.obstratics-history').removeClass('d-none');
            }
            $('.anc-history').html(data.editAnc);
            $('.ho-value .selectized').addClass('d-none');
            $(function () {
                //Datetimepicker plugin
                $('.datetimepicker').bootstrapMaterialDatePicker({
                    format: 'dddd DD MMMM YYYY',
                    // minDate:new Date(),
                    clearButton: true,
                    time:false,
                    weekStart: 1
                });
                $('.lmd-date').bootstrapMaterialDatePicker({
                    format: 'dddd DD MMMM YYYY',
                    clearButton: true,
                    time:false,
                    weekStart: 1
                });

                $('.timepicker').bootstrapMaterialDatePicker({
                    date: false,
                    shortTime: true,
                    format: 'hh:mm a',
                    switchOnClick: true
                });

                $('.co_value_data').selectize({
                    delimiter: ',',
                    persist: false,
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
            });

            $('.select-padding-0').selectpicker('refresh');
            var oeType = $('.oe-ut-gsac-type').val();
            if(typeof oeType == "undefined"){
                var oeType = $("input[name='oe[utdata][1][ut_type]']").val();
            }
            var weekValue = $("input[name='oe[utdata][1][oe_ut_sac_2]']").val();
            if(weekValue == ''){
                var weekValue = $("input[name='oe[utdata][1][oe_ut_sac]']").val();
            }

            utType(oeType);
            addOrRemoveClass(weekValue);
            var personalHistory = $('select.personal-history').val();
            if(personalHistory == 2 && personalHistory == 3){
                personalHistoryType($('select.personal-history').val());
            }

            hbDetails($('.hb-value').val());
            fbsDetails($('.fbs-value').val());
            pp2bsDetails($('.pp2bs-value').val());
            tshDetails($('.tsh-value').val());
            rbsDetails($('.rbs-value').val());
            hoType($('select.ho_type').val());
            hstType($('.hst-type-value:checked').val());
            // new function
            otherReport($('.other-report-type:checked').val());
            ancProfileData($('.anc-profile-type:checked').val());
            growthReportData($('.growth-report-type:checked').val());
            earlyScanData($('.early-scan-type:checked').val());
            utGsac(weekValue,1);
            fefalReductionType($('.fefal-reduction-type:checked').val());
            howMuchType($('.how-much-type:checked').val());
            regularType($('select.past-mh-2').val(),'past-ir-regular-data');
            regularType($('select.present-mh-2').val(),'present-ir-regular-data');
            $('.ho-past-personal-data .btn-group').addClass('d-none');
            var hoValue = $('select.ho_type_value').val();
            // if(typeof hoValue != 'undefined'){
            //     complaintWiseMedicines(null,'',2,hoValue);
            // }
            if(data.earlyScanImagesValue != 'null' && (data.ancHistoryId != null || data.utType == 'no')) {
                $('.early-scan-images').imageUploader({
                    preloaded: jQuery.parseJSON(data.earlyScanImagesValue),
                    imagesInputName: 'investigation[investigation_early_scan_type][images]',
                    preloadedInputName: 'early_old'
                });
            }

            if(data.ancImagesValue != 'null' && (data.ancHistoryId != null || data.utType == 'no')) {
                $('.anc-images-data').imageUploader({
                    preloaded: jQuery.parseJSON(data.ancImagesValue),
                    imagesInputName: 'investigation[anc][images]',
                    preloadedInputName: 'anc_old'
                });
            }
            if(data.growthImagesValue != 'null' && (data.ancHistoryId != null || data.utType == 'no')) {
                $('.growth-images').imageUploader({
                    preloaded: jQuery.parseJSON(data.growthImagesValue),
                    imagesInputName: 'investigation[growth_report][images]',
                    preloadedInputName: 'growth_old'
                });
            }
            if(data.otherImagesValue != 'null' && (data.ancHistoryId != null || data.utType == 'no')) {
                $('.other-report-images').imageUploader({
                    preloaded: jQuery.parseJSON(data.otherImagesValue),
                    imagesInputName: 'investigation[other_report_data][images]',
                    preloadedInputName: 'other_old'
                });
            }
            if(data.usgImagesValue != 'null' && (data.ancHistoryId != null || data.utType == 'no')){
                $('.usg-images').imageUploader({
                    preloaded: jQuery.parseJSON(data.usgImagesValue),
                    preloadedInputName: 'usg_old',
                    imagesInputName: 'usg[images]',
                });
            }
            $('.view-file-edit-modal').modal('hide');
        }).fail(function(error){

        });
    }

    function removeUser(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{URL::to('removeRemark')}}"+'/'+userId+'/'+tId,
            type:'POST',
            dataType: 'json',
        }).done(function(data) {
        }).fail(function() {
        });
    }

    function checkGynec(){
        $('.gynec-plan-no').text(6);
        $('.gynec-plan-tab').addClass('d-none');
        $('.injection-tab').removeClass('d-none');
        var ancHistoryId = $('#anc_history_id').val();
        var ancType = $('.anc_history_type').val();
        var isGynec = 0;
        var fcpValue = $('.fcp-type-1:checked').val();
        var gSacValue = $('.g-sac-1').val();
       $(".blighted-ovum:checked").each(function() {
                var isBlighted = $(this).val();
                
                if($(this).val() == 'no')
                {
                    isGynec = 0;
                    return false;
                }
                if($('select.oe-no').val() == 1 && $(this).val() == 'yes')
                {
                    isGynec = 1;
                    return false;
                }
                if($(this).val() == 'yes')
                {
                    isGynec = 1;
                }
            })
        if(fcpValue == 'absent'){
            isGynec = 1;
        }
        if(isGynec == 1){
            $('.gynec-plan-tab').removeClass('d-none');
            $('.injection-tab').addClass('d-none');
            if(ancType == 'anc'){
                $('.gynec-plan-no').text(9);
            }

        }
        $('.is-gynec').val(isGynec);
    }

    function getANCHistoryData(ancQstring){
        $('.submit').prop('disabled', false);
        $.ajax({
            url:'{{URL::to("get-anc-details")}}?'+ancQstring,
            type:'GET',
            dataType:'json'
        }).done(function(data){
            if(data.anc_type == 1){
                var ancPreview = $('.anc-details-data').html();
                var buttonHtml = '';
                var previewData = '';
                for(i=0; i<data.data.length;i++)
                {
                    if(typeof data.date[i] != 'undefined'){
                        var linkDate = moment(new Date(data.date[i])).format('YYYY-MM-DD HH:mm:ss');
                        var date = moment(new Date(data.date[i])).format('DD MMMM YYYY');
                    }
                    if(ancCycleStatus == 'current')
                    {
                        buttonHtml = ancPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn edit-btn btn-sm btn-primary" data-date="'+linkDate+'">Edit</a><a class="btn print-btn btn-sm btn-primary" data-date="'+linkDate+'">Print</a></div></div>';

                    }
                    else{
                        buttonHtml = ancPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn print-btn btn-sm btn-primary" data-date="'+linkDate+'">Print</a></div></div>';

                    }
                    ancPreview = buttonHtml + data.data[i];
                    $('.anc-details-data').html(ancPreview);
                    ancPreview = ancPreview + '<div class="row sepreator"></div>';
                }
            }
            if(data.anc_type == 2){
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data.data);
                w.document.close();
                w.window.print();
            }
        }).fail(function(error){

        });
    }

    var medicinesValue = @json($medicines);
    var weekData = @json($weekData);
    
</script>
<script src="{{URL::to('public/js/image-uploader.js')}}"></script>
@stop
