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
}
.doctor-info {
    padding: 50px 0;
}
h6 {
    text-transform: uppercase;
}
.p-name{
    font-size: 18px;
}
.drname{
    text-transform: uppercase;
    font-size: 18px;
}
body {margin-top :100px;}
</style>
@if(isset($printPreview) && $printPreview != 0)
    @section('content')
@endif  

    @php
    $ivfReportData = json_decode($ivfReport->description);    
    // dd($ivfReportData->ovum->erphoto);
    @endphp

    <div class="ivf-report-print">
    <div class="row">
        <div class="col-md-10">
            <span>Patient Name:</span>
            <strong class="p-name">{{ ucwords(strtolower($ivfReport->getPatients['name']))}}</strong>
        </div>
        <div class="col-md-2 col-sm-2">
            <span>Age:</span>
            <span>{{!empty($ivfReport->getPatients['age']) ? $ivfReport->getPatients['age'] : '-'}}</span>
        </div>
    </div>
    <div class="row">
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

    <div class="row" style="padding-bottom: 20px">
        <div class="col-md-12">
            <span>Indication:</span>
            <span>{{!empty($ivfReportData->indication) ? $ivfReportData->indication : ''}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div><h6><strong>Stimulation:</strong></h6></div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-sm-6">
        <div>
            <span>Protocol:</span>
            <span>{{!empty($ivfReportData->simulation->protocol) ? $ivfReportData->simulation->protocol : '-'}}</span>
        </div>
        <div>
                <span>Injection:</span>
                <span>{{!empty($ivfReportData->simulation->injection) ? $ivfReportData->simulation->injection : '-'}}</span>       
            </div>
            <div>
                <span>Antagonist:</span>
                <span>{{!empty($ivfReportData->simulation->antagonist) ? $ivfReportData->simulation->antagonist : '-'}}</span>
            </div>
            <div>
                <span>Stimulation days:</span>
                <span>{{!empty($ivfReportData->simulation->simulation_days) ? $ivfReportData->simulation->simulation_days : '-'}}</span>
            </div>
        </div>

        <div class="col-md-6 col-sm-6" style="border-left: 1px solid">
            <div>
                <span>Trigger:</span>
                <span>{{!empty($ivfReportData->simulation->trigger->trigger) ? $ivfReportData->simulation->trigger->trigger : '-'}}</span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span>Date:</span>
                    <span>{{!empty($ivfReportData->simulation->trigger->date) ? $ivfReportData->simulation->trigger->date : '-'}}</span>  
                </div>
                <div class="col-md-6">
                    <span>Time:</span>
                    <span>{{!empty($ivfReportData->simulation->trigger->time) ? $ivfReportData->simulation->trigger->time : '-'}}</span>
                </div>   
            </div>
            
            <div>
                <span>Total ACF:</span>
                <span>{{!empty($ivfReportData->simulation->totalacf) ? $ivfReportData->simulation->totalacf : '-'}}</span>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <span>Rt:</span>
                    <span>{{!empty($ivfReportData->simulation->rt) ? $ivfReportData->simulation->rt : '-'}}</span>  
                </div>
                <div class="col-md-6 col-sm-6">
                    <span>Lt:</span>
                    <span>{{!empty($ivfReportData->simulation->lt) ? $ivfReportData->simulation->lt : '-'}}</span>
                </div>   
            </div>
            <div>
                <span>ET:</span>
                <span>{{!empty($ivfReportData->simulation->et) ? $ivfReportData->simulation->et : '-'}}</span>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <span>sp2:</span>
                    <span>{{!empty($ivfReportData->simulation->sp2) ? $ivfReportData->simulation->sp2 : '-'}}</span>  
                </div>
                <div class="col-md-6 col-sm-6">
                    <span>Date:</span>
                    <span>{{!empty($ivfReportData->simulation->sp2date) ? $ivfReportData->simulation->sp2date : '-'}}</span>
                </div>   
            </div>
            
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div><h6><strong>Ovum pick up:</strong></h6></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <span>Date:</span>
                        <span>{{!empty($ivfReportData->ovum->date) ? $ivfReportData->ovum->date : '-'}}</span>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <span>Time:</span>
                        <span>{{!empty($ivfReportData->ovum->time) ? $ivfReportData->ovum->time : '-'}} </span>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-md-12">
                        <span>ER Photo:</span>
                        @php
                        $erPhoto = !empty($ivfReportData->ovum->erphoto) ? $ivfReportData->ovum->erphoto : null;    
                        @endphp
                    <span><img src="{{asset($erPhoto)}}" alt="" height="100px" width="100px"></span>
                    </div>
                </div> --}}

                <div>
                    <span>Total OCC:</span>
                    <span>{{!empty($ivfReportData->ovum->totalocc) ? $ivfReportData->ovum->totalocc : '-'}}</span>
                </div>
                <div>
                    <span>MII:</span>
                    <span>{{!empty($ivfReportData->ovum->mii) ? $ivfReportData->ovum->mii : '-'}}</span>
                </div>
                <div>
                    <span>MII Rate:</span>
                    <span>{{!empty($ivfReportData->ovum->mii_rate) ? $ivfReportData->ovum->mii_rate : '-'}}</span>
                </div>
                <div>
                    <span>MI:</span>
                    <span>{{!empty($ivfReportData->ovum->mi) ? $ivfReportData->ovum->mi : '-'}}</span>
                </div>
                <div>
                    <span>GV:</span>
                    <span>{{!empty($ivfReportData->ovum->gv) ? $ivfReportData->ovum->gv : '-'}}</span>
                </div>
                <div>
                    <span>Hysteroscopy:</span>
                    <span>{{!empty($ivfReportData->ovum->hysteroscopy_status) ? ucfirst($ivfReportData->ovum->hysteroscopy_status) : '-'}}</span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6" style="border-left: 1px solid">
                <div><strong>Semen Report:</strong>
                <span>{{!empty($ivfReportData->ovum->semenreport) ? $ivfReportData->ovum->semenreport : '-'}}</span>
                </div>

                <div>
                    <span>Count:</span>
                    <span>{{!empty($ivfReportData->ovum->count) ? $ivfReportData->ovum->count : '-'}}</span>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <span>Total Motility:</span>
                        <span>{{!empty($ivfReportData->ovum->motility) ? $ivfReportData->ovum->motility : '-'}}</span>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <span>Active:</span>
                        <span>{{!empty($ivfReportData->ovum->active) ? $ivfReportData->ovum->active : '-'}}</span>
                    </div>
                </div>

                <div>
                    <span>Sperm Morphology (ICSI):</span>
                    <span>{{!empty($ivfReportData->ovum->sperm) ? $ivfReportData->ovum->sperm : '-'}}</span>
                </div>

                <div class="row" style="padding: 20px;">
                    <span><strong>Oocyte Quality:</strong></span>
                    <span>{{!empty($ivfReportData->ovum->quality) ? $ivfReportData->ovum->quality : '-'}}</span>
                    <span><strong>Total Blastcyst:</strong></span>
                    <span>{{!empty($ivfReportData->ovum->total_blastcyst) ? $ivfReportData->ovum->total_blastcyst : '-'}}</span>
                    <span><strong>Blastcyst Rate:</strong></span>
                    <span>{{!empty($ivfReportData->ovum->blastcyst_rate) ? $ivfReportData->ovum->blastcyst_rate : '-'}}</span>
                </div>
            </div>   
        </div>

        <div class="row" style="padding: 20px 0">
            <div class="col-md-12">
                <span>Remark:</span>
                @if(isset($pt_view) && $pt_view == 1)
                <span>{{isset($ivfReportData->pt_remark) && !empty($ivfReportData->pt_remark) ? $ivfReportData->pt_remark : '-'}}</span>
                @else
                <span>{{!empty($ivfReportData->remark) ? $ivfReportData->remark : '-'}}</span>
                @endif
            </div>
        </div>

        <div class="row doctor-info">
            <div class="col-md-6 col-sm-6">
                <div class='drname'>{{config('app.doctor') }}</div>
                <div class='degree'>(M.B., D.G.O)</div>
                <div class='proffesion'>Chief consultant</div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class='drname'>bhavna borkhataria</div>
                <div class='degree'>(M.Sc., ph.D.)</div>
                <div class='proffesion'>embryologist</div>
            </div>
        </div>

    </div>
    @if(isset($printPreview) && $printPreview != 0)

    @endsection
@endif

