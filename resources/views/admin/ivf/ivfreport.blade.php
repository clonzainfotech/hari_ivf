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

if(!empty($ivfReport)) {
    $volume=json_decode($ivfReport->volume);
    $sperm_count=json_decode($ivfReport->sperm_count);
    $total_motility	=json_decode($ivfReport->total_motility);
    $actively=json_decode($ivfReport->actively);
    $sluggishly=json_decode($ivfReport->sluggishly);
    $non_motile=json_decode($ivfReport->non_motile);
    $morphology=json_decode($ivfReport->morphology);  
    $pus_cells=json_decode($ivfReport->pus_cells);
}

@endphp
<div class="row clearfix ivf">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>
                    <strong>IVF Report</strong>
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
                {{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-report'])}}
                {{Form::hidden('plan-id',!empty($planId) ? $planId : null,['class'=>'plan-id'])}}
                {{Form::hidden('patient-id',!empty($patientId) ? $patientId : null,['class'=>'patient-id'])}}
                <div class="row">
                    <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Reason : &nbsp;</span>
                                {{Form::text('reason',!empty($ivfReport->reason) ? $ivfReport->reason : null ,['class'=>'form-control reason'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Date : &nbsp;</span>
                            {{Form::text("report_date",cdate(!empty($ivfReport->date) ? $ivfReport->date : null)->format('D d M Y'),['class'=>'form-control datetimepicker report_date','required'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('report_date')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Volume : &nbsp;</span>
                                {{Form::text('volume[pre]',!empty($volume->pre) ? $volume->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                {{Form::text('volume[post]',!empty($volume->post) ? $volume->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Sperm Count/ml : &nbsp;</span>
                                {{Form::text('sperm[pre]',!empty($sperm_count->pre) ? $sperm_count->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                {{Form::text('sperm[post]',!empty($sperm_count->post) ? $sperm_count->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Total Motility (%): &nbsp;</span>
                                {{Form::text('motility[pre]',!empty($total_motility->pre) ? $total_motility->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                {{Form::text('motility[post]',!empty($total_motility->post) ? $total_motility->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Actively Motile (%) : &nbsp;</span>
                                {{Form::text('actively[pre]',!empty($actively->pre) ? $actively->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                {{Form::text('actively[post]',!empty($actively->post) ? $actively->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Sliggishly Motile (%) : &nbsp;</span>
                            {{Form::text('sluggishly[pre]',!empty($sluggishly->pre) ? $sluggishly->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                            {{Form::text('sluggishly[post]',!empty($sluggishly->post) ? $sluggishly->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('name')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Non-motile (%) : &nbsp;</span>
                            {{Form::text('motile[pre]',!empty($non_motile->pre) ? $non_motile->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                            {{Form::text('motile[post]',!empty($non_motile->post) ? $non_motile->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('name')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Normal Morphology : &nbsp;</span>
                                {{Form::text('morphology[pre]',!empty($morphology->pre) ? $morphology->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                {{Form::text('morphology[post]',!empty($morphology->post) ? $morphology->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
                            </span>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">Pus cells/hpf : &nbsp;</span>
                                {{Form::text('cells[pre]',!empty($pus_cells->pre) ? $pus_cells->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                                {{Form::text('cells[post]',!empty($pus_cells->post) ? $pus_cells->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('name')}}
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

            $(document).ready(function(){            
                $(document).on('click','.submit',function(e){
                    e.preventDefault();

                    planId = $('.plan-id').val();
                    patientId = $('.patient-id').val();
                    var reportData = $('#ivf-report').serialize();
        
                    if(this.value == 1) {
                        reportData = reportData + '&isprint=1';
                    }
                   
                    ivfReportData(reportData);
                });
            });

            function ivfReportData(data) {
                console.log(data);
                $.ajax({
                    url:'{{URL::to("ivf-report")}}' +'/'+ planId + '/' + patientId,
                    type:'POST',
                    dataType:'json',
                    data:data,
                
                }).done(function(data){
                    console.log(data);
                    if(data.status == 'true'){
                        var url = '{{URL::to("ivf-report")}}'+'/'+ planId + '/' + patientId;
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
