@extends('layouts.main')
@section('parentPageTitle', 'IVF History Appointment')
@section('title', 'IVF History Appointment')
@section('page-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
<style>
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
@php
if(!empty($iuireport)) {
    $iuiReportdata = json_decode($iuireport->description);

    if (!empty($iuiReportdata->ovum->erphoto))
    {
        $file = cdnUrl($iuiReportdata->ovum->erphoto, null);
    }
}
@endphp
<div class="row clearfix ivf">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>IUI Report</strong>
                </h2>
                <ul class="header-dropdown">
                    <li>
                        <a href="{{URL::previous()}}">
                            <button class="btn btn-primary">
                                Back
                            </button>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="body">
                {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'iui-report'])}}
                {{Form::hidden('patient-id',!empty($patientId) ? encrypt($patientId) : null,['class'=>'patient-id'])}}
                {{Form::hidden('cycle-no',!empty($cycleNo) ? encrypt($cycleNo) : null,['class'=>'cycle-no'])}}
                <div class="row">
                    <div class="col-md-3">
                                <span class="">Surgery/Donor Name : &nbsp;</span>
                                {{Form::radio("donor",'yes','',['id'=>'donor_yes','class'=>'donorinfo'])}}
                                <label for="donor_yes">yes</label>                       
                                {{Form::radio("donor",'no','',['id'=>'donor_no','class'=>'donorinfo'])}}
                                <label for="donor_no">No</label>
                    </div>
                    <div class="col-md-9">
                        <div class="row donor-info d-none">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Name : &nbsp;</span>
                                    {{Form::text("donorname",!empty($iuireport->donor_name) ? $iuireport->donor_name : null,['class'=>'form-control  donor-name'])}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Age : &nbsp;</span>
                                    {{Form::text("donorage",!empty($iuireport->age) ? $iuireport->donor_age : null,['class'=>'form-control  donor-age'])}}
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Indication : &nbsp;</span>
                                {{Form::text('indication',!empty($iuireport->indication) ? $iuireport->indication : null,['class'=>'form-control indication col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('indication')}}
                            </span>
                    </div>
                </div>

                <div class="pt-3 pb-3"><h6><strong>Stimulation</strong></h5></div>
                    
                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Protocol : &nbsp;</span>
                                {{Form::text('data[simulation][protocol]',!empty($iuiReportdata->simulation->protocol) ? $iuiReportdata->simulation->protocol : null,['class'=>'form-control protocol col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('protocol')}}
                            </span>
                        </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Injection : &nbsp;</span>
                                {{Form::text('data[simulation][injection]',!empty($iuiReportdata->simulation->injection) ? $iuiReportdata->simulation->injection : null,['class'=>'form-control injection col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('injection')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Antagonist : &nbsp;</span>
                                {{Form::text('data[simulation][antagonist]',!empty($iuiReportdata->simulation->antagonist) ? $iuiReportdata->simulation->antagonist : null,['class'=>'form-control antagonist col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('antagonist')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Stimulation days : &nbsp;</span>
                                {{Form::text('data[simulation][simulation_days]',!empty($iuiReportdata->simulation->simulation_days) ? $iuiReportdata->simulation->simulation_days : null,['class'=>'form-control simulation_days col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('simulation_days')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Trigger : &nbsp;</span>
                                {{Form::text('data[simulation][trigger][trigger]',!empty($iuiReportdata->simulation->trigger->trigger) ? $iuiReportdata->simulation->trigger->trigger : null,['class'=>'form-control trigger col-md-8'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('trigger')}}
                            </span>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon col-md-2">Date: &nbsp;</span>
                                    {{Form::text("data[simulation][trigger][date]",\Carbon\Carbon::parse(!empty($iuiReportdata->simulation->trigger->date) ? $iuiReportdata->simulation->trigger->date : null)->format('D d M Y'),['class'=>'form-control datetimepicker triggerdate'])}}
                                    {{-- {{Form::text('data[ovum][trigger][date]','',['class'=>'form-control trigger col-md-10'])}} --}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('trigger')}}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon col-md-2">Time : &nbsp;</span>
                                    {{ Form::text('data[simulation][trigger][time]', !empty($iuiReportdata->simulation->trigger->time) ? $iuiReportdata->simulation->trigger->time : null, [
                                        'class'=>'form-control timepicker triggertime',
                                        'placeholder'=>'Time'
                                    ])}}
                                    {{-- {{Form::text('data[ovum][trigger][time]','',['class'=>'form-control trigger col-md-10'])}} --}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('trigger')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Total ACF : &nbsp;</span>
                                {{Form::text('data[simulation][totalacf]',!empty($iuiReportdata->simulation->totalacf) ? $iuiReportdata->simulation->totalacf : null,['class'=>'form-control total-acf col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('total-acf')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">RT : &nbsp;</span>
                                {{Form::text('data[simulation][rt]',!empty($iuiReportdata->simulation->rt) ? $iuiReportdata->simulation->rt : null,['class'=>'form-control rt col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('rt')}}
                            </span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">LT : &nbsp;</span>
                            {{Form::text('data[simulation][lt]',!empty($iuiReportdata->simulation->lt) ? $iuiReportdata->simulation->lt : null,['class'=>'form-control lt col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('lt')}}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">ET : &nbsp;</span>
                            {{Form::text('data[simulation][et]',!empty($iuiReportdata->simulation->et) ? $iuiReportdata->simulation->et : null,['class'=>'form-control et col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('et')}}
                        </span>
                    </div>
            
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">sp2 : &nbsp;</span>
                            {{Form::text('data[simulation][sp2]',!empty($iuiReportdata->simulation->sp2) ? $iuiReportdata->simulation->sp2 : null,['class'=>'form-control sp2 col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('sp2')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Sp2 Date: &nbsp;</span>
                            {{Form::text("data[simulation][sp2date]",\Carbon\Carbon::parse($iuiReportdata->simulation->sp2date ? $iuiReportdata->simulation->sp2date : $lastAppointmentData->date)->format('D d M Y'),['class'=>'form-control datetimepicker sp2date'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('sp2date')}}
                        </span>
                    </div>
                </div>

                <div class="pt-3 pb-3"><h6><strong>Ovem pick up</strong></h5></div>
                <div class="row">
                    <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Date : &nbsp;</span>
                                {{Form::text("data[ovum][date]",\Carbon\Carbon::parse(!empty($iuiReportdata->ovum->date) ? $iuiReportdata->ovum->date : null)->format('D d M Y'),['class'=>'form-control datetimepicker ovumdate'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('ovumdate')}}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Time : &nbsp;</span>
                                {{ Form::text('data[ovum][time]', !empty($iuiReportdata->ovum->time) ? $iuiReportdata->ovum->time : null, [
                                    'class'=>'form-control timepicker ovumtime',
                                    'placeholder'=>'Time'
                                ])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('ovumtime')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Er Photo : </span>
                            <span>{{Form::file('data[ovum][erphoto]',['class'=>'form-control report-file'])}}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                        <span>
                            @if (!empty($iuiReportdata->ovum->erphoto))
                            <img src="{{$file}}" class="mt-2 mb-2" width="100px"/>
                            @endif
                        </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Total OCC : &nbsp;</span>
                                {{Form::text('data[ovum][totalocc]',!empty($iuiReportdata->ovum->totalocc) ? $iuiReportdata->ovum->totalocc : null,['class'=>'form-control total-occ col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('total-occ')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Semen Report : &nbsp;</span>
                            {{Form::text('data[ovum][semenreport]',!empty($iuiReportdata->ovum->semenreport) ? $iuiReportdata->ovum->semenreport : null,['class'=>'form-control semen-report col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('semen-report')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Count : &nbsp;</span>
                            {{Form::text('data[ovum][count]',!empty($iuiReportdata->ovum->count) ? $iuiReportdata->ovum->count : null,['class'=>'form-control count col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('count')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Total Motility : &nbsp;</span>
                            {{Form::text('data[ovum][motility]',!empty($iuiReportdata->ovum->motility) ? $iuiReportdata->ovum->motility : null,['class'=>'form-control motility col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('motility')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Active : &nbsp;</span>
                            {{Form::text('data[ovum][active]',!empty($iuiReportdata->ovum->active) ? $iuiReportdata->ovum->active : null,['class'=>'form-control active col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('active')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Sperm Morphology : &nbsp;</span>
                            {{Form::text('data[ovum][sperm]',!empty($iuiReportdata->ovum->sperm) ? $iuiReportdata->ovum->sperm : null,['class'=>'form-control sperm col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('sperm')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Oocyte Quality : &nbsp;</span>
                            {{Form::text('data[ovum][quality]',!empty($iuiReportdata->ovum->quality) ? $iuiReportdata->ovum->quality : null,['class'=>'form-control quality col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('quality')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::textarea('remark',!empty($iuireport->remark) ? $iuireport->remark : null,['class'=>'form-control remark','placeholder'=>'remark','rows'=>'5'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {{-- {{Form::button('submit',['class'=>'btn btn-primary submit'])}} --}}
                        <button type="submit" class="btn btn-primary submit" value="1">Preivew</button>
                    </div>
                </div>
                {{Form::close()}}
            
            
            
            </div>
        </div>
    </div>
</div>

@stop

@section('page-script')
    <script src="{{url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script type="text/javascript">       
        var qstring = '';
        var patientsId = $('.patients-id').val();
        var ivfHistoryId = '';
        var patientId = '';
        var cycleNo = '';
        var planId = '';
        $(function () {
        //Datetimepicker plugin
            $('.datetimepicker').bootstrapMaterialDatePicker({
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

            $(document).on('change','.donorinfo', function() {
                if($('#donor_yes').is(":checked")) {
                    $(".donor-info").removeClass("d-none");
                    $(".donor-info").addClass("d-blick");
                }else{
                    $(".donor-info").removeClass("d-block");
                    $(".donor-info").addClass("d-none");
                }
            });

            $(document).ready(function(){            
                $(document).on('click','.submit',function(e){
                    e.preventDefault();
                    patientId = $('.patient-id').val();
                    cycleNo = $('.cycle-no').val();

                    var reportData = new FormData($("#iui-report")[0]);;
                    // return false;
                    if(this.value == 1) {
                        reportData.append('isprint', 1);
                    }
                   
                    iuiReportData(reportData);
                });
            });

            function iuiReportData(data) {
                console.log(data);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'{{URL::to("iui-report")}}'+'/'+ patientId + '/' + cycleNo,
                    enctype: 'multipart/form-data',
                    type:'POST',
                    dataType:'json',
                    data:data,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(data){
                    console.log(data);
                    if(data.status == 'true'){
                        var url = '{{URL::to("iui-report")}}'+'/'+ patientId + '/' + cycleNo;
                        window.location.href = url;
                    }else if(data.status == 1){                 
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        w.window.print();
                        // $('#ivf_history_id').val(data.id);
                    }else{
                        location.reload();
                    }
                });
            }
        });
    </script>
@stop
