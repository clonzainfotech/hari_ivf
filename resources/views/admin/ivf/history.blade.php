@extends('layouts.main')
@section('parentPageTitle', 'IVF History Appointment')
@section('title', 'IVF History Appointment')
@section('page-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
<style>
    .history-lmd-date-diff{
        color:green !important;
    }
    .dose-border{
        border: 1px solid rgba(0,0,0,0.1) !important;
    }
    .dose-val .items{
        background: #fff !important;
    }
    .dose-width{
        width:220px !important;
    }
    .header-data{
        padding: 0px 21px !important;
        margin-top: 0px !important;
        color: #00cfd1;
    }
    .visit-lable{
        color:#999;
    }
    .visit-lable-value{
        color:black;
    }
    .selectize-control .selectize-input.disabled {
        opacity: 1 !important;
        background-color: #fff !important;

    }
    .selectize-control.multi .selectize-input.disabled [data-value]{
        color: black !important;
    }
    .remove-border{
        border : none !important;
    }
    .w-49{
        width: 49% !important;
    }
    .ivf-panel-title{
        background-color: #e0e0e0 !important;
        border-bottom: 2px solid #eee ;
        color: #3e3a3a !important;
    }
    .plan-picker button{
        width: 175px !important;
    }
    .plan-picker ul{
        width: 175px !important;
        height: 115px !important;
    }
    
</style>
@stop
@section('content')
    <div class="row clearfix ivf">
        <div class="col-md-12">
            {{-- <div class="card"> --}}
                <div class="header">
                    <h4 class="header-data">IVF Appointments
                    </h4>
                    <ul class="header-dropdown">

                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                    @if(Session::has('msg'))
                        <div class="alert alert-danger">
                            {{Session::get('msg')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="zmdi zmdi-close"></i>
                                </span>
                            </button>
                        </div>
                    @endif
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-form'])}}
                               <div class="history-data"></div>
                               
                               {{Form::hidden('patients_id',encrypt($id),['class'=>'patients-id'])}}
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
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
                                {{Form::number('day','',['class'=>'form-control next-day','placeholder'=>'Day','min'=>1])}}
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
                                {{Form::date('date','',['class'=>'form-control next-date','placeholder'=>'Date','min'=>0])}}
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
                            {{-- <div class="input-group clockpicker">
                                {{Form::text('appointment-time', '', [
                                    'class' => 'form-control next-time',
                                    'disabled' => true
                                ])}}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div> --}}

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
    </div>

    <div class="modal fade" id="ivfRemarkModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-sm" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header text-center">
                    <h5 class="title" id="defaultModalLabel">Remark</h5>
                    <button type="button" class="close deposit-close-button" data-dismiss="modal">&times;</button>
                </div>

                <!-- body -->

                {{Form::open(['class'=>'', 'id'=>'addRemark'])}}
                <div class="modal-body text-center">
                    <span class="form-error-msg"></span>
                {{ Form::textarea('remark','', [
                    'class' => 'form-control',
                    'id' => 'remark',
                    'rows' => '5',
                    'placeholder' => 'remark'
                ]) }}

                {{Form::hidden('ivf-history-id','',['class'=>'ivf-history-id'])}}

                <!-- footer -->
                    <div class="text-center">
                        {{-- <button type="submit" class="btn btn-primary waves-effect submit submit-button submit-transfer-print" value="4">submit</button> --}}
                    </div>

                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="overy-data-popup" tabindex="-1" role="dialog">
        <div class="modal-dialog ovary-modal-dialog" role="document">
            <div class="modal-content">
                <!-- header -->
                <div class="modal-header justify-content-center">
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> --}}
                    {{-- <h4 class="title" id="overy-popup-title"></h4> --}}
                </div>
                <!-- body -->
                <div class="modal-body">
                    <table class="table m-b-0" id="ovary-table">
                        <tbody>
                            <tr>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="8"><span class="ovary-value-number ovary-number-8 odd-padding ovary-pre-selected-value">8</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="9"><span class="ovary-value-number ovary-number-9 odd-padding">9</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="10"><span class="ovary-value-number ovary-number-10 odd-padding">10</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="11"><span class="ovary-value-number ovary-number-11 odd-padding">11</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="12"><span class="ovary-value-number ovary-number-12 odd-padding">12</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="13"><span class="ovary-value-number ovary-number-13 odd-padding">13</span></td>
                            </tr>
                            <tr>
                                <td class="ovary-value" data-type="" data-class="" data-value="13.5"><span class="ovary-value-number ovary-number-13-5 odd-padding">13.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="14"><span class="ovary-value-number ovary-number-14 odd-padding ovary-pre-selected-value">14</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="14.5"><span class="ovary-value-number ovary-number-14-5 odd-padding">14.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="15"><span class="ovary-value-number ovary-number-15 odd-padding ovary-pre-selected-value">15</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="15.5"><span class="ovary-value-number ovary-number-15-5 odd-padding">15.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="16"><span class="ovary-value-number ovary-number-16 odd-padding ovary-pre-selected-value">16</span></td>
                            </tr>
                            <tr>
                                
                                <td class="ovary-value" data-type="" data-class="" data-value="16.5"><span class="ovary-value-number ovary-number-16-5 odd-padding">16.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="17"><span class="ovary-value-number ovary-number-17 odd-padding ovary-pre-selected-value">17</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="17.5"><span class="ovary-value-number ovary-number-17-5 odd-padding">17.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="18"><span class="ovary-value-number ovary-number-13 odd-padding ovary-pre-selected-value">18</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="18.5"><span class="ovary-value-number ovary-number-18-5 odd-padding">18.5</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="19"><span class="ovary-value-number ovary-number-19 odd-padding">19</span></td>
                            </tr>
                            <tr>
                                <td class="ovary-value" data-type="" data-class="" data-value="19.5"><span class="ovary-value-number ovary-number-19-5 odd-padding">19.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="20"><span class="ovary-value-number ovary-number-20 odd-padding ovary-pre-selected-value">20</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="20.5"><span class="ovary-value-number ovary-number-20-5 odd-padding">20.5</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="21"><span class="ovary-value-number ovary-number-21 odd-padding">21</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="21.5"><span class="ovary-value-number ovary-number-21-5 odd-padding">21.5</span></td>
                                <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="22"><span class="ovary-value-number ovary-number-22 odd-padding ovary-pre-selected-value">22</span></td>
                            </tr>
                            <tr>
                                <td class="ovary-value" data-type="" data-class="" data-value="22.5"><span class="ovary-value-number ovary-number-22-5 odd-padding">22.5</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="23"><span class="ovary-value-number ovary-number-23 odd-padding">23</span></td>
                                <td class="ovary-value" data-type="" data-class="" data-value="24"><span class="ovary-value-number ovary-number-24 odd-padding">24</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </div>
                <!-- footer -->
                <div class="modal-footer next-appointment-footer">
                    {{-- <a href="#" class="btn btn-primary waves-effect save-btn disabled next-appointment-form">Save</a>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> --}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script type="text/javascript">       
        var qstring = '';
        var patientsId = $('.patients-id').val();
        var ivfHistoryId = '';
        var cycleData = '';
        $(function () {
            
            //Datetimepicker plugin
            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                // minDate:new Date(),
                time:false,
                weekStart: 1
            });
            $('.timepicker').bootstrapMaterialDatePicker({
                date: false,
                shortTime: true,
                format: 'hh:mm a',
                switchOnClick: true
            });
        });

        $(document).ready(function(){            
            $('.complain-multi .show-tick').addClass('d-none');
            $('.select2-search__field').css('width','280px');
            getIvfHistoryData(qstring);

            $(document).on('click','.next-appointment-form',function(e){
                e.preventDefault();
                var date = $('.next-date-value').val();
                var time = $('select.next-time').find("option:selected").text();
                var timeVal = $('.next-time-value').val(time);
                var anc = $('#anc-form').serialize();
                anc = anc + '&isprint=' + '1';
                ivfFormData(anc);
            });

            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var formData = new FormData($("#ivf-form")[0]);
                var remark = $('#remark').val();
                formData.append('remark',remark);
                if(this.value == 1){
                    formData.append('isprint', 1);
                }
                if(this.value == 2) {
                    formData = formData + '&isprint=2';
                }
                if(this.value == 3) {
                    formData.append('is_ivf_print', 3);
                }
                if(this.value == 4) {
                    formData.append('istransferprint', 4);
                }
                ivfFormData(formData);
            });

            // $(document).on('click','.ivf-report', function(){
            //     var planId = $(this).data('id');
            //     alert(planId);
            //     return false;
            // });

            $(document).on('change','select.refence-doctor',function(e){
                var refDoctorId = $(this).val();
                var token = "{{csrf_token()}}";
                $.ajax({
                    url: "{{URL::to('get-ref-doctor-mobile-number')}}",
                    dataType: 'json',
                    type: 'POST',
                    data:{refDoctorId:refDoctorId,_token:token}
                }).done(function(data) {
                    if(data.mobile_number != null){
                        $('.ref-mobile-number').val(data.mobile_number);
                    }else{
                        $('.ref-mobile-number').val('');
                    }
                }).fail(function() {

                });

            });

            $(document).on('keyup','.next-day',function(){
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                if (day) {
                    var time = $(this).find("option:selected").text();
                    getNextAppointmentDate(selectedAppointmentId,day,null,time);
                }
            });

            $(document).on('change','.next-date',function(e){
                var dateValue = $(this).val();
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                var time = '';
                if(dateValue){
                    var time = $(this).find("option:selected").text();
                }
                getNextAppointmentDate(selectedAppointmentId,day,dateValue,time);
            });

            $(document).on('change','select.next-time',function(){
                var selectedAppointmentId = $('.selected-tr').data('id');
                var day = $('.next-day').val();
                var time = $(this).find("option:selected").text();
                var date = $('.next-date').val();
                if(day){
                    getNextAppointmentDate(selectedAppointmentId,day,date,time);
                }
            });
            
        $(document).on('change','#progesterone',function(){
            $('.transfer-print').addClass('d-none');
            if($(this).is(":checked")) {
                $(".progesterone").removeClass("d-none");
            }else{
                $(".progesterone").addClass("d-none");
                $('.transfer-print').removeClass('d-inline-block');
                $('.transfer-print').addClass('d-none');
            }
        });

        $(document).on('change','#trigger',function(){
            if($(this).is(":checked")) {
                $(".trigger").removeClass("d-none");
            }else{
                $(".trigger").addClass("d-none");                                    
            }
        });

        $(document).on('change','#hcg',function(){
            if($(this).is(":checked")) {
                $(".hcgtrigger").removeClass("d-none");
                if($('#hcg_time').val() == '') {
                    $(document).on('change','#hcg_time',function() {
                        if($(this).val() == ''){
                            $('#ivf_print').addClass('d-none');
                        }
                        else {
                            $('#ivf_print').removeClass('d-none');
                            $('#ivf_print').addClass('d-inline-block');
                        }
                    });
                    $('#ivf_print').addClass('d-none');
                }
            }else{
                $('#ivf_print').removeClass('d-inline-block');
                $('#ivf_print').addClass('d-none');
                $(".hcgtrigger").addClass("d-none");
            }
        });

        $(document).on('change','#decapeptyl',function(){
            if($(this).is(":checked")) {
                $(".decapeptyltrigger").removeClass("d-none");
            }else{
                $(".decapeptyltrigger").addClass("d-none");
            }
        });

        $(document).on('change','#decapeptyl',function(){
            if($(this).is(":checked")) {
                $(".decapeptyltrigger").removeClass("d-none");
            }else{
                $(".decapeptyltrigger").addClass("d-none");
            }
        });
        
        $(document).on('click','#blood',function(){
            if($(this).is(":checked")) {
                $(".bloodreport").removeClass("d-none");
            }else{
                $(".bloodreport").addClass("d-none");
            }
        });
        $(document).on('change','#transfer',function(){
            if($(this).is(":checked")) {
                $(".transferdata").removeClass("d-none");
            }else{
                $(".transferdata").addClass("d-none");
            }
        });

        $(document).on('click','#blood',function(){
            if($(this).is(":checked")) {
                $(".bloodreport").removeClass("d-none");
            }else{
                $(".bloodreport").addClass("d-none");
            }
        });

        $(document).on('change','#transfer',function(){
            if($(this).is(":checked")) {
                $(".transferdata").removeClass("d-none");
            }else{
                $(".transferdata").addClass("d-none");
            }
        });

        $(document).on('click','.progesterone-type',function(){
            if($(this).is(":checked")) {
                $('.transfer-print').removeClass('d-none');
                $('.transfer-print').addClass('d-inline-block');
            }
        });

        $(document).on('click','#progesterone',function(e){
            if($(this).is(":checked")){
                $('.progesterone_data').removeClass('d-none');
            }else{
                $('.progesterone_data').addClass('d-none');
                $('.progesterone_yes').addClass('d-none');
            }
        });

        $(document).on('click','#progesterone_yes',function() {
            if($('#progesterone_yes').is(':checked')) {
                    $('.progesterone_yes').removeClass('d-none');
            }else{
                $('.progesterone_yes').addClass('d-none');
            }
        });

        $(document).on('click','#progesterone_no',function() {
            if($('#progesterone_no').is(':checked')) {
                    $('.progesterone_yes').addClass('d-none');
                    $('.transfer-print').removeClass('d-inline-block');
                    $('.transfer-print').addClass('d-none');
                }
                $('.progesterone_yes').addClass('d-none');
        });

        $(document).on('click','.skip-cycle',function(){
            $('.skip-cycle-data').addClass('d-none');
            if($(this).is(':checked')) {
                $('.skip-cycle-data').removeClass('d-none');
            }
        });

        $(document).on('change','select.cycle_number',function(){
            cycleData = 'cycle_no='+$(this).val()+'&plan_type='+$(this).data('plan');
            getCycleData(cycleData);
        });

    });


        function ivfFormData(data){
            var valid = 1;
            $('.lmp-date-error').text('');
            if($('.history-lmd-date').val() == ''){
                valid = 0;
                $('.lmp-date-error').text('This field is required.');
                $('html, body').animate({
                    scrollTop: ($('.history-lmd-date').offset().top - 150)
                }, 200);
                return false;
            }
            if($('.history-lmd-date').val() == ''){
                valid = 0;
                $('.lmp-date-error').text('This field is required.');
                $('html, body').animate({
                    scrollTop: ($('.history-lmd-date').offset().top - 150)
                }, 200);
                return false;
            }
            if(valid == 0){
                return true;
            }
            $('.submit').prop('disabled', true);

            $.ajax({
                url:'{{URL::to("ivf")}}',
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'json',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == 'true'){
                    var url = "{{URL::to('ivf')}}";
                    window.location.href = url;
                }else if(data.status == 1){                 
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    $('#ivf_history_id').val(data.id);
                }else{
                    location.reload();
                }
            });
        }

        function getNextAppointmentDate(appointmentId,day,date,time){
            var token = "{{csrf_token()}}";
            $.ajax({
                url: "{{URL::to('next-appointment')}}",
                dataType: 'json',
                type: 'post',
                data:{appointmentId:appointmentId,day:day,_token:token,date:date,time:time}
            }).done(function(data) {
                if(data.status == null){
                    $('.next-date').val(data.date);
                    $('.next-day').val(data.diff);
                    $('.next-time').val(data.time);
                    $('.next-time').selectpicker('refresh');
                    $('.next-date-value').val(data.date);
                }
            }).fail(function(error) {
                $('.form-error-msg').empty();
                if(error.responseJSON != null){
                    var formError = error.responseJSON.errors;
                    $.each(formError,function(key,value){
                        $('.'+key).text(value);
                    });
                }
            });
        }

        function getIvfHistoryData(qstring){
            var token = "{{csrf_token()}}";
            $.ajax({
                url: "{{URL::to('ivf/history')}}"+'/'+patientsId,
                dataType: 'json',
            }).done(function(data) {
                $('.history-data').html(data.history);
                $('.select-padding-0').selectpicker('refresh');
                $('.datetimepicker').bootstrapMaterialDatePicker({
                    format: 'dddd DD MMMM YYYY',
                    clearButton: true,
                    time:false,
                    weekStart: 1
                });
                $('.co_value_data').selectize({
                    delimiter: ',',
                    persist: false,
                    // create: function(input) {
                    //     return {
                    //         value: input,
                    //         text: input
                    //     }
                    // }
                });
                $('.timepicker').bootstrapMaterialDatePicker({
                    date: false,
                    shortTime: true,
                    format: 'hh:mm a',
                    switchOnClick: true
                });
                var lmdDate = $('.history-lmd-date').val();
                var lmdDateDiff = $('.history-lmd-date-diff-val').val();
                if(lmdDate != '' && lmdDateDiff != ''){
                    protocolTable(new Date(lmdDate),parseInt(lmdDateDiff),3);   
                }
            }).fail(function(error) {
                $('.form-error-msg').empty();
                if(error.responseJSON != null){
                    var formError = error.responseJSON.errors;
                    $.each(formError,function(key,value){
                        $('.'+key).text(value);
                    });
                }
            });
        }

        function getCycleData(cycleData){
            $.ajax({
                url: "{{URL::to('ivf/cycle')}}"+'/'+patientsId+'?'+cycleData,
                dataType: 'json',
            }).done(function(data){
                $('.cycle-data').html(data.cycle_data);
                $('.select-padding-0').selectpicker('refresh');
                $('.datetimepicker').bootstrapMaterialDatePicker({
                    format: 'dddd DD MMMM YYYY',
                    clearButton: true,
                    // minDate:new Date(),
                    time:false,
                    weekStart: 1
                });
                $('.co_value_data').selectize({
                    delimiter: ',',
                    persist: false,
                    // create: function(input) {
                    //     return {
                    //         value: input,
                    //         text: input
                    //     }
                    // }
                });
                $('.timepicker').bootstrapMaterialDatePicker({
                    date: false,
                    shortTime: true,
                    format: 'hh:mm a',
                    switchOnClick: true
                });
                var lmdDate = $('.history-lmd-date').val();
                var lmdDateDiff = $('.history-lmd-date-diff-val').val();
                if(lmdDate != '' && lmdDateDiff != ''){
                    protocolTable(new Date(lmdDate),parseInt(lmdDateDiff),3);   
                }
            }).fail(function(error){

            });
        }
        
    </script>
@stop
