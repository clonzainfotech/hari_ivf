@extends(isset($printPreview) && $printPreview == 1 ? 'layouts.printpreview' : 'layouts.printPreviewBlank')
@if(!isset($printPreview))
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
@endif
<style>
.degree {
    font-weight: 500;
    text-transform: uppercase;
    font-size: 12px;
}
.proffesion {
    font-weight: 500;
    text-transform: uppercase;
}
.ivf-report-print {
    padding: 20px;
    font-size: 18px;
    /* margin-top :250px; */
}
.doctor-info {
    padding: 50px 0;
    display: flex;
}
h6 {
    text-transform: uppercase;
}
.p-name{
    font-size: 18px;
}
.drname{
    text-transform: uppercase;
    font-size: 16px;
}

/* body {} */
.pick-up-table
{
    /* width: 50% !important; */
}
.pick-up-table th, .pick-up-table td
{
    padding: .45rem !important;
}
.ivf-report-print .d-flex
{
    display: flex;
}
.mt-3
{
    margin-top: 1.7rem !important;
}
.candor-color
{
 color: #1e5f63;
}
.er_image
    {
        width: 130px;
        border-radius: 10%;
        -webkit-print-color-adjust: exact;
    }
    .er_image_name{
        position: absolute;
        top: 40%;
        right: 35%;
        font-weight: bold;
        -webkit-print-color-adjust: exact;
    }
    .er-image-div
    {
        display: flex !important;
    }
.view-file-modal-dialog .ivf-report-print
{
    font-size: 16px !important;
    margin-top: 0px !important
}
.view-file-modal-dialog .ivf-report-print .er_image
{
    width: 50% !important;
}
</style>
@php
    // if($isAppointmentView)
    // {
    //     $font_size = ""
    // }
@endphp
   
@if(isset($printPreview) && $printPreview != 0)
    @section('content')
@endif  

    @php
    $ivfReportData = json_decode($ivfReport->description);    
    // dd($ivfReportData->ovum->erphoto);
    @endphp

    <div class="ivf-report-print">
        <div class="row d-flex">
            <div class="col-md-10 text-left">
                <span>Patient Name:</span>
                <strong class="p-name">{{ ucwords(strtolower($ivfReport->getPatients['name']))}}</strong>
            </div>
            <div class="col-md-2 col-sm-2 text-right">
                <span><strong>Age:</strong></span>
                <span>{{!empty($ivfReport->getPatients['age']) ? $ivfReport->getPatients['age'].' Year' : '-'}}</span><br>
            </div>
        </div>
        <div class="row d-flex">
            @if(!empty($ivfReportData->is_donor->status) && $ivfReportData->is_donor->status == 'yes')
                <div class="col-md-2">
                    <span>Surgery/Donor : </span>
                    <span>{{'Yes'}}</span>
                </div>
            @endif
            @if (isset($ivfReportData->is_donor->status) && !empty($ivfReportData->is_donor->status) && $ivfReportData->is_donor->status =='yes')
                <div class="col-md-8">
                    <span>Donor Name:</span>
                    <span>{{ucwords(strtolower($ivfReportData->donor->name))}}</span>
                </div>
                <div class="col-md-2">
                    <span>Age:</span>
                    <span>{{$ivfReportData->donor->age}}</span>
                </div>
            @endif
        </div>

        <div class="row d-flex" style="padding-bottom: 20px">
            <div class="col-md-8 col-sm-8 text-left">
                <span>Indication:</span>
                <span>{{!empty($ivfReportData->indication) ? $ivfReportData->indication : ''}}</span>
            </div>
            <div class="col-md-4 col-sm-4 text-right">
                <span><strong>Weight:</strong></span>
                <span>{{!empty($ivfReport->getPatients['weight']) ? $ivfReport->getPatients['weight'].' KG' : '-'}}</span><br>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div><h6 class="candor-color"><strong><u>Stimulation:</u></strong></h6></div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-sm-6">
                <table class="table table-bordered pick-up-table" width="50">
                    <tbody>
                        <tr>
                            <td><strong>Protocol</strong></td>
                            <td>{{!empty($ivfReportData->simulation->protocol) ? $ivfReportData->simulation->protocol : '-'}}</td>
                        </tr>
                        @if(!isset($pt_view) || $pt_view != 1)
                        <tr>
                            <td><strong>Injection</strong></td>
                            <td>{{!empty($ivfReportData->simulation->injection) ? $ivfReportData->simulation->injection : '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Antagonist</strong></td>
                            <td>{{!empty($ivfReportData->simulation->antagonist) ? $ivfReportData->simulation->antagonist : '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Stimulation days</strong></td>
                            <td>{{!empty($ivfReportData->simulation->simulation_days) ? $ivfReportData->simulation->simulation_days : '-'}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Total ACF</strong></td>
                            <td colspan="3">{{!empty($ivfReportData->simulation->totalacf) ? $ivfReportData->simulation->totalacf : '-'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6 col-sm-6">
                <table class="table table-bordered pick-up-table" width="50">
                    <tbody>
                        <tr>
                            <td><strong>Trigger</strong></td>
                            <td colspan="3">{{!empty($ivfReportData->simulation->trigger->trigger) ? $ivfReportData->simulation->trigger->trigger : '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Date</strong></td>
                            <td>{{!empty($ivfReportData->simulation->trigger->date) ? $ivfReportData->simulation->trigger->date : '-'}}</td>
                            <td><strong>Time</strong></td>
                            <td>{{!empty($ivfReportData->simulation->trigger->time) ? $ivfReportData->simulation->trigger->time : '-'}}</td>
                        </tr>
                    
                        <tr>
                            <td><strong>ET:</strong></td>
                            <td colspan="3">{{!empty($ivfReportData->simulation->et) ? $ivfReportData->simulation->et : '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>RT</strong></td>
                            <td colspan="">{{!empty($ivfReportData->simulation->rt) ? $ivfReportData->simulation->rt : '-'}}</td>
                            <td><strong>LT</strong></td>
                            <td colspan="">{{!empty($ivfReportData->simulation->lt) ? $ivfReportData->simulation->lt : '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>sp2:</strong></td>
                            <td colspan="">{{!empty($ivfReportData->simulation->sp2) ? $ivfReportData->simulation->sp2 : '-'}}</td>
                            <td><strong>Date</strong></td>
                            <td colspan="">{{!empty($ivfReportData->simulation->sp2date) ? $ivfReportData->simulation->sp2date : '-'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
            <div class="col-md-6 col-sm-6">
                    <table class="table table-bordered pick-up-table" width="50">
                        <thead>
                            <tr><h6 class="candor-color"><strong><u>Ovum pick up:</u></strong></h6></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Date</strong></td>
                                <td colspan="">{{!empty($ivfReportData->ovum->date) ? $ivfReportData->ovum->date : '-'}}</td>
                                <td><strong>Time</strong></td>
                                <td colspan="">{{!empty($ivfReportData->ovum->time) ? $ivfReportData->ovum->time : '-'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Total OCC:</strong></td>
                                <td colspan="3">{{!empty($ivfReportData->ovum->totalocc) ? $ivfReportData->ovum->totalocc : '-'}}</td>
                            </tr>
                            @if(!isset($pt_view) || $pt_view != 1)
                                <tr>
                                    <td><strong>MII</strong></td>
                                    <td colspan="">{{!empty($ivfReportData->ovum->mii) ? $ivfReportData->ovum->mii : '-'}}</td>
                                    <td><strong>MII Rate</strong></td>
                                    <td colspan="">{{!empty($ivfReportData->ovum->mii_rate) ? $ivfReportData->ovum->mii_rate : '-'}}</td>
                                </tr>
                                <tr>
                                    <td><strong>MI</strong></td>
                                    <td colspan="">{{!empty($ivfReportData->ovum->mi) ? $ivfReportData->ovum->mi : '-'}}</td>
                                    <td><strong>GV</strong></td>
                                    <td colspan="">{{!empty($ivfReportData->ovum->gv) ? $ivfReportData->ovum->gv : '-'}}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>Hysteroscopy</strong></td>
                                <td colspan="3">{{!empty($ivfReportData->ovum->hysteroscopy_status) ? ucfirst($ivfReportData->ovum->hysteroscopy_status) : '-'}}</td>
                            </tr>
                        </tbody>
                    </table>

            </div>
            <div class="col-md-6 col-sm-6">
                    <table class="table table-bordered pick-up-table mt-3" width="50">
                        <tbody>
                            <tr>
                                <td><strong>Semen Report</strong></td>
                                <td colspan="3">{{!empty($ivfReportData->ovum->semenreport) ? $ivfReportData->ovum->semenreport : '-'}}</td>
                            </tr>
                            <tr>
                                <td colspan=""><strong>Sperm Morphology (ICSI)</strong></td>
                                <td>{{!empty($ivfReportData->ovum->sperm) ? $ivfReportData->ovum->sperm : '-'}}</td>
                                <td><strong>Count</strong></td>
                                <td>{{!empty($ivfReportData->ovum->count) ? $ivfReportData->ovum->count : '-'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Motility</strong></td>
                                <td colspan="">{{!empty($ivfReportData->ovum->motility) ? $ivfReportData->ovum->motility : '-'}}</td>
                                <td><strong>Active</strong></td>
                                <td colspan="">{{!empty($ivfReportData->ovum->active) ? $ivfReportData->ovum->active : '-'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Oocyte Quality</strong></td>
                                <td colspan="3">{{!empty($ivfReportData->ovum->quality) ? $ivfReportData->ovum->quality : '-'}}</td>
                            </tr>
                            @if(!isset($pt_view) || $pt_view != 1)
                                {{-- <tr>
                                    <td><strong>Total Blastcyst</strong></td>
                                    <td colspan="3">{{!empty($ivfReportData->ovum->total_blastcyst) ? $ivfReportData->ovum->total_blastcyst : '-'}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Blastcyst Rate:</strong></td>
                                    <td colspan="3">{{!empty($ivfReportData->ovum->blastcyst_rate) ? $ivfReportData->ovum->blastcyst_rate : '-'}}</td>
                                </tr> --}}
                            @endif
                        </tbody>
                    </table>
            </div>
        </div>
        {{-- </div> --}}
        @php
            $erImageArray = ['good','medium','poor','gv','m1'];
            $baseUrl = asset('assets/images');
            // echo $baseUrl;
        @endphp
        <div class="row mb-2 er-image-div">
            @foreach($erImageArray as $erImage)
                @if(getimagesize($baseUrl.'/'.$erImage.'.jpg'))
                    <div class="col-md-2 col-sm-2">
                        {{-- <div class="input-group"> --}}
                            <span class="mt-5 er_image_name"><strong>{{ucwords($erImage)}}</strong></span>
                            <br>
                            <img src="{{asset('assets/images/'.$erImage.'.jpg')}}" class="er_image">
                            
                            
                            <br>
                            <span>{{isset($ivfReportData->er->$erImage) && !empty($ivfReportData->er->$erImage) ? $ivfReportData->er->$erImage : null}}</span>
                        {{-- </div> --}}
                    </div>
                @endif
            @endforeach
        </div>
        @if((isset($ivfReportData->pt_remark) && !empty($ivfReportData->pt_remark)) || !empty($ivfReportData->remark))
        <div class="row" style="padding: 20px 0">
                <div class="col-md-12 text-left">
                    <span class="candor-color"><strong>Remark:<strong></span>
                    @if(isset($pt_view) && $pt_view == 1)
                    <span>{{isset($ivfReportData->pt_remark) && !empty($ivfReportData->pt_remark) ? $ivfReportData->pt_remark : '-'}}</span>
                    @else
                    <span>{{!empty($ivfReportData->remark) ? $ivfReportData->remark : '-'}}</span>
                    @endif
                </div>
        </div>
        @endif

        <div class="row doctor-info">
                <div class="col-md-4">
                    <div class='drname'>{{$ivfReport->getPatients->getHospitalDoctor['name'] }}</div>
                    <div class='degree'>{{$ivfReport->getPatients->getHospitalDoctor['degree'] }}</div>
                    <div class='proffesion'>{{$ivfReport->getPatients->getHospitalDoctor['designation'] }}</div>
                </div>
                <div class="col-md-4">
                    <div class='drname'>Dr. juhi Dhameliya</div>
                    <div class='degree'></div>
                    <div class='proffesion'>embryologist</div>
                </div>
                <div class="col-md-4">
                    <div class='drname'>{{config('app.embroyologist_doctor')}}</div>
                    <div class='degree'>({{config('app.embroyologist_degree')}})</div>
                    <div class='proffesion'>embryologist</div>
                </div>
        </div>

    </div>
    @if(isset($printPreview) && $printPreview != 0)

    @endsection
@endif

