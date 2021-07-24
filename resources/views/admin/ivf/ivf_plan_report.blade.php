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
    $ivfReportData = json_decode($ivfReport->description);

    if (!empty($ivfReportData->ovum->erphoto) && file_exists($ivfReportData->ovum->erphoto))
    {
        $file = url($ivfReportData->ovum->erphoto);
    }
}
$injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'CC + HMG','8'=>'CC + FSH','9'=>'Antagonist'];
$se2Data = [];
$slhData = [];
$bloodReport = [];
$protocols = [];
$injectionBrand = [];
$antagonist = [];
$simulationDay = null;
$triggerDate = null;
$hystrocopy = null;
foreach($ivf as $ivf)
{
    $ivfData = json_decode($ivf->description);
    if(!empty($ivfData->s_e2)){
        $se2Data[] = $ivfData->s_e2;
    }
    if(!empty($ivfData->s_lh)){
        $slhData[] = $ivfData->s_lh;
    }
    if(!empty($ivfData->blood->report)){
        $bloodReport[] = $ivfData->blood->report;
    }
    $duringPickupStatus = !empty($ivfData->during_pickup) ? ucfirst($ivfData->during_pickup) : null;
    $triggerDate = !empty($ivf->trigger_date) ? $ivf->trigger_date : null;
    if(!empty($ivfData->protocol))
    {
        foreach($ivfData->protocol as $key=>$value)
        {
            // $injectionBrand[] = !empty($value->hmg_brand_name) ? $value->hmg_brand_name : (!empty($value->fsh_brand_name) ? $value->fsh_brand_name : '');
            if(!empty($value->antagonist))
            {
                $antagonist[] = $value->antagonist.'('.$ivf->visit.')';
            }
           
            if(!empty($value->hmg_brand_name))
            {
                $injectionBrand[] = $value->hmg_brand_name;
                
            }
            if(!empty($value->fsh_brand_name))
            {
                $injectionBrand[] = $value->fsh_brand_name;
                
            }
            if(!empty($value->hmg))
            {
                $protocols[] = 'HMG -'.$value->hmg;
                
            }
            if(!empty($value->fsh))
            {
                $protocols[] = 'FSH -'.$value->fsh;
            }
            $simulationDay = !empty($ivf->trigger_date) ? 'S'.$value->s_day : null;
        }
    }
}
@endphp
<div class="row clearfix">
    <div class="col-md-12 p-0">
        <div class="card patients-list">
            <div class="header">
                <h2><strong class="text-secondary">{{ucwords($lastAppointmentData->getPatientsDetails['name'])}}</strong></h2>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix ivf">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>IVF Report</strong>
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
                {{Form::open([
                    'class'=>'form ivf',
                    'files'=>'true',
                    'id'=>'ivf-report'
                ])}}
                {{Form::hidden('patient_id', encrypt($patientId), ['class'=>'patient-id'])}}
                {{Form::hidden('cycle_no',encrypt($cycleNo) ,['class'=>'cycle-no'])}}
                {{Form::hidden('plan', encrypt($plan), ['class'=>'plan'])}}
                {{Form::hidden('ivf_report_plan_id', !empty($ivfReport->id) ? encrypt($ivfReport->id) : null, ['class'=>'ivf-report-plan-id'])}}
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="">Surgery / Donor Name : &nbsp;</span>
                            </div>
                            <div class="col-md-6">
                                <div class="radio">
                                    {{Form::radio("data[is_donor][status]",'yes', !empty($ivfReportData->is_donor->status) && $ivfReportData->is_donor->status == 'yes' ? true : false,[
                                        'id'=>'donor_yes',
                                        'class'=>'donorinfo form-control'
                                    ])}}
                                    <label for="donor_yes">Yes</label>
                                    {{Form::radio("data[is_donor][status]",'no',!empty($ivfReportData->is_donor->status) && $ivfReportData->is_donor->status == 'no' ? true : false,[
                                        'id'=>'donor_no',
                                        'class'=>'donorinfo form-control'
                                    ])}}
                                    <label for="donor_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $dNone = 'd-none';
                        if(isset($ivfReportData->is_donor->status) && !empty($ivfReportData->is_donor->status) && $ivfReportData->is_donor->status =='yes') {
                            $dNone = '';
                        }
                    @endphp
                    <div class="col-md-6">
                        <div class="{{'row donor-info ' . $dNone }}">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Name : &nbsp;</span>
                                    {{Form::text("data[donor][name]",!empty($ivfReportData->donor->name) ? $ivfReportData->donor->name : null,['class'=>'form-control  donor-name'])}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">Age : &nbsp;</span>
                                    {{Form::number("data[donor][age]",!empty($ivfReportData->donor->age) ? $ivfReportData->donor->age : null,['class'=>'form-control  donor-age'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Indication : &nbsp;</span>
                            {{Form::text('data[indication]',!empty($ivfReportData->indication) ? $ivfReportData->indication : null,['class'=>'form-control indication col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('indication')}}
                        </span>
                    </div>
                </div>

                <div class="pt-3 pb-3"><h6><strong>Stimulation</strong></h5></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Protocol : &nbsp;</span>
                            {{Form::text('data[simulation][protocol]',!empty($ivfReportData->simulation->protocol) ? $ivfReportData->simulation->protocol :implode(',',$protocols),['class'=>'form-control protocol col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('protocol')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">Injection : &nbsp;</span>
                            {{Form::text('data[simulation][injection]',!empty($ivfReportData->simulation->injection) ? $ivfReportData->simulation->injection : implode(',',$injectionBrand),['class'=>'form-control injection col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('injection')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Antagonist : &nbsp;</span>
                            {{Form::text('data[simulation][antagonist]',!empty($ivfReportData->simulation->antagonist) ? $ivfReportData->simulation->antagonist : implode(',',$antagonist),['class'=>'form-control antagonist col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('antagonist')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Stimulation days :</span>
                            {{Form::text('data[simulation][simulation_days]',!empty($ivfReportData->simulation->simulation_days) ? $ivfReportData->simulation->simulation_days : $simulationDay,['class'=>'form-control simulation_days col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('simulation_days')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Total ACF : &nbsp;</span>
                            {{Form::text('data[simulation][totalacf]',!empty($ivfReportData->simulation->totalacf) ? $ivfReportData->simulation->totalacf : null,['class'=>'form-control total-acf col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('total-acf')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">sp2 : &nbsp;</span>
                            {{Form::text('data[simulation][sp2]',!empty($ivfReportData->simulation->sp2) ? $ivfReportData->simulation->sp2 : null,['class'=>'form-control sp2 col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('sp2')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Trigger : &nbsp;</span>
                            {{Form::text('data[simulation][trigger][trigger]',!empty($ivfReportData->simulation->trigger->trigger) ? $ivfReportData->simulation->trigger->trigger :null,['class'=>'form-control trigger'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('trigger')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Date: &nbsp;</span>
                            {{Form::text("data[simulation][trigger][date]",\Carbon\Carbon::parse(!empty($ivfReportData->simulation->trigger->date) ? $ivfReportData->simulation->trigger->date :  $triggerDate)->format('D d M Y'),['class'=>'form-control datetimepicker triggerdate'])}}
                            {{-- {{Form::text('data[ovum][trigger][date]','',['class'=>'form-control trigger col-md-10'])}} --}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('trigger')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Time :</span>
                            {{ Form::text('data[simulation][trigger][time]', !empty($ivfReportData->simulation->trigger->time) ? $ivfReportData->simulation->trigger->time : null, [
                                'class'=>'form-control timepicker triggertime',
                                'placeholder'=>'Time'
                            ])}}
                            {{-- {{Form::text('data[ovum][trigger][time]','',['class'=>'form-control trigger col-md-10'])}} --}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('trigger')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Sp2 Date: &nbsp;</span>
                            {{Form::text("data[simulation][sp2date]",\Carbon\Carbon::parse(!empty($ivfReportData->simulation->sp2date) ? $ivfReportData->simulation->sp2date : $lastAppointmentData->date)->format('D d M Y'),['class'=>'form-control datetimepicker sp2date'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('sp2date')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon col-md-2">RT : &nbsp;</span>
                                {{Form::text('data[simulation][rt]',!empty($ivfReportData->simulation->rt) ? $ivfReportData->simulation->rt : null,['class'=>'form-control rt col-md-10'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('rt')}}
                            </span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">LT : &nbsp;</span>
                            {{Form::text('data[simulation][lt]',!empty($ivfReportData->simulation->lt) ? $ivfReportData->simulation->lt : null,['class'=>'form-control lt col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('lt')}}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon col-md-2">ET : &nbsp;</span>
                            {{Form::text('data[simulation][et]',!empty($ivfReportData->simulation->et) ? $ivfReportData->simulation->et : null,['class'=>'form-control et col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('et')}}
                        </span>
                    </div>

                </div>

                <div class="pt-3 pb-3"><h6><strong>Ovum pick up</strong></h5></div>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Date : &nbsp;</span>
                            {{Form::text("data[ovum][date]",\Carbon\Carbon::parse(!empty($ivfReportData->ovum->date) ? $ivfReportData->ovum->date : $lastAppointmentData->date)->format('D d M Y'),['class'=>'form-control datetimepicker ovumdate'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('ovumdate')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Time : &nbsp;</span>
                            {{Form::text('data[ovum][time]', !empty($ivfReportData->ovum->time) ? $ivfReportData->ovum->time : null, [
                                'class'=>'form-control timepicker ovumtime',
                                'placeholder'=>'Time'
                            ])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('ovumtime')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Total OCC : &nbsp;</span>
                            {{Form::text('data[ovum][totalocc]',!empty($ivfReportData->ovum->totalocc) ? $ivfReportData->ovum->totalocc : null,['class'=>'form-control total-occ col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('total-occ')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="radio">
                            <span class="">Hystroscopy : &nbsp;</span>
                            {{Form::radio("data[ovum][hysteroscopy_status]",'yes', !empty($ivfReportData->ovum->hysteroscopy_status) && $ivfReportData->ovum->hysteroscopy_status == 'yes' ? true : false,[
                                'id'=>'hysteroscopy_status_yes',
                                'class'=>'hysteroscopy_status form-control'
                            ])}}
                            <label for="hysteroscopy_status_yes">Yes</label>
                            {{Form::radio("data[ovum][hysteroscopy_status]",'no',!empty($ivfReportData->ovum->hysteroscopy_status) && $ivfReportData->ovum->hysteroscopy_status == 'no' ? true : false,[
                                'id'=>'hysteroscopy_status_no',
                                'class'=>'hysteroscopy_status form-control'
                            ])}}
                            <label for="hysteroscopy_status_no">No</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Er Photo : </span>
                            {{Form::file('data[ovum][erphoto]',['class'=>'form-control report-file'])}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                        <span>
                            @if (!empty($ivfReportData->ovum->erphoto))
                            <img src="{{$file}}" class="mt-2 mb-2" width="100px"/>
                            @endif
                        </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">MII : &nbsp;</span>
                            {{Form::text('data[ovum][mii]',!empty($ivfReportData->ovum->mii) ? $ivfReportData->ovum->mii : null,['class'=>'form-control mii'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">MII Rate: &nbsp;</span>
                            {{Form::text('data[ovum][mii_rate]',!empty($ivfReportData->ovum->mii_rate) ? $ivfReportData->ovum->mii_rate : null,['class'=>'form-control mii_rate '])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">MI : &nbsp;</span>
                            {{Form::text('data[ovum][mi]',!empty($ivfReportData->ovum->mi) ? $ivfReportData->ovum->mi : null,['class'=>'form-control mi'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">GV : &nbsp;</span>
                            {{Form::text('data[ovum][gv]',!empty($ivfReportData->ovum->gv) ? $ivfReportData->ovum->gv : null,['class'=>'form-control gv'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Semen Report : &nbsp;</span>
                            {{Form::text('data[ovum][semenreport]',!empty($ivfReportData->ovum->semenreport) ? $ivfReportData->ovum->semenreport : null,['class'=>'form-control semen-report col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('semen-report')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Count : &nbsp;</span>
                            {{Form::text('data[ovum][count]',!empty($ivfReportData->ovum->count) ? $ivfReportData->ovum->count : null,['class'=>'form-control count col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('count')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Total Motility : &nbsp;</span>
                            {{Form::text('data[ovum][motility]',!empty($ivfReportData->ovum->motility) ? $ivfReportData->ovum->motility : null,['class'=>'form-control motility col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('motility')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Active : &nbsp;</span>
                            {{Form::text('data[ovum][active]',!empty($ivfReportData->ovum->active) ? $ivfReportData->ovum->active : null,['class'=>'form-control active col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('active')}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Sperm Morphology : &nbsp;</span>
                            {{Form::text('data[ovum][sperm]',!empty($ivfReportData->ovum->sperm) ? $ivfReportData->ovum->sperm : null,['class'=>'form-control sperm col-md-10'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('sperm')}}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Oocyte Quality : &nbsp;</span>
                            {{Form::text('data[ovum][quality]',!empty($ivfReportData->ovum->quality) ? $ivfReportData->ovum->quality : null,['class'=>'form-control quality col-md-10'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Total Blastcyst : &nbsp;</span>
                            {{Form::text('data[ovum][total_blastcyst]',!empty($ivfReportData->ovum->total_blastcyst) ? $ivfReportData->ovum->total_blastcyst : null,['class'=>'form-control total_blastcyst col-md-10'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Blastcyst Rate: &nbsp;</span>
                            {{Form::text('data[ovum][blastcyst_rate]',!empty($ivfReportData->ovum->blastcyst_rate) ? $ivfReportData->ovum->blastcyst_rate : null,['class'=>'form-control blastcyst_rate col-md-10'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Loop 1 : &nbsp;</span>
                            {{Form::text('data[loop_1]',!empty($ivfReportData->loop_1) ? $ivfReportData->loop_1 : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Loop 2 : &nbsp;</span>
                            {{Form::text('data[loop_2]',!empty($ivfReportData->loop_2) ? $ivfReportData->loop_2 : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Loop 3 : &nbsp;</span>
                            {{Form::text('data[loop_3]',!empty($ivfReportData->loop_3) ? $ivfReportData->loop_3 : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Loop 4 : &nbsp;</span>
                            {{Form::text('data[loop_4]',!empty($ivfReportData->loop_4) ? $ivfReportData->loop_4 : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{-- <div class="checkbox">
                            {{Form::checkbox('plan_of_management[is_print]','is_print',!empty($planManagement->is_print) ? true : false,['id'=>'is_print','class'=>'plan-management'])}}
                            <label for="is_print">
                                Is Print
                            </label>
                        </div> --}}
                        <div class="form-group">
                            {{Form::textarea('data[remark]',!empty($ivfReportData->remark) ? $ivfReportData->remark : null,['class'=>'form-control remark','placeholder'=>'remark','rows'=>'2'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{Form::textarea('data[pt_remark]',isset($ivfReportData->pt_remark) && !empty($ivfReportData->pt_remark) ? $ivfReportData->pt_remark : null,['class'=>'form-control remark','placeholder'=>"Patients'sremark",'rows'=>'2'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {{-- {{Form::button('submit',['class'=>'btn btn-primary submit'])}} --}}
                        <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                    </div>
                </div>
                {{Form::close()}}



            </div>
        </div>
    </div>
</div>

@stop

@section('page-script')
    <script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script type="text/javascript">
        var qstring = '';
        var patientsId = $('.patients-id').val();
        var ivfHistoryId = '';
        var patientId = '';
        var cycleNo = '';
        var plan = '';
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
                    // patientId = $('.patient-id').val();
                    // cycleNo = $('.cycle-no').val();
                    // plan = $('.plan').val();

                    var reportData = new FormData($("#ivf-report")[0]);;
                    // return false;
                    if(this.value == 1) {
                        reportData.append('isprint', 1);
                    }

                    ivfReportData(reportData);
                });
            });

            function ivfReportData(data) {
                console.log(data);
                patientId = $('.patient-id').val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'{{URL::to("ivf-plan-report")}}',
                    enctype: 'multipart/form-data',
                    type:'POST',
                    dataType:'json',
                    data:data,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(data){
                    console.log(data);
                    // if(data.status == 'true'){
                    //     var url = '{{URL::to("ivf-report")}}'+'/'+ patientId + '/' + cycleNo;
                    //     window.location.href = url;
                    // }
                    if(data.status == 1){
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        $('.ivf-report-plan-id').val(data.ivf_plan_report_id);
                        setTimeout(function() {
                            w.window.print();
                        }, 800);
                    }
                    if (data.status == false) {
                        swal({
                            title: 'Oops!',
                            text: data.message,
                            type: 'error'
                        }, function() {
                            var url = "{{URL::to('ivf')}}"+'/history/' + patientsId;
                            window.location.href = url;
                            // window.location = 'redirectURL';
                        });
                    }
                });
            }
        });
    </script>
@stop
