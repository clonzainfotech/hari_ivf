@php
use App\Models\IuiHistory;
use App\Models\IuiExtraVisit;
$medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
        $medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
        $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
        $dose = ['' => 'Select Dose',"1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
@endphp
{{-- <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet"/> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
        {{Form::open(['class'=>'form iui','files'=>'true','id'=>'iui-form'])}}
            {{Form::hidden('patients_id',encrypt($iui->patients_id), ['id' => 'patients_id'])}}
            {{Form::hidden('cycle_no', $cycleNo, ['id' => 'cycle_no'])}}
            @php
                // $lmddate = !empty($iuiSecondVisitDate->date) ? \Carbon\Carbon::parse($iuiSecondVisitDate->date)->format('D d M Y') : null;
                $lmddate = !empty($firstVisitLmpDate) ? \Carbon\Carbon::parse($firstVisitLmpDate) : null;
                $lmdDiff = null;
                if(!empty($lmddate)){
                    // $lmddateData = \Carbon\Carbon::parse($lmddate);
                    $now = \Carbon\Carbon::now();
                    $lmdDiff = $lmddate->diffInDays($now);
                    $lmdDiff = $lmdDiff + 1;
                    $lmddate = \Carbon\Carbon::parse($lmddate)->format('D d M Y');
                }
            @endphp
            {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
            @if($visitNo == 2 && $old_edit_cycle == false)
                
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Seen By :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('seen_by_2',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by-2','placeholder'=>'Select Doctor'])}}
                        </div>
                        <span class="seen-by-error-2 text-danger mb-2"></span>
                    </div>
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            RMO Doctor :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('rmo_doctor',$rmoDoctor,'',['class'=>'form-control select-padding-0','placeholder'=>'Select RMO Doctor'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" id="saverecordname" value="{{\Carbon\Carbon::now()->format('d-m-Y').", ".ucwords($iui->getPatientsInfo->name).""}}">
                        <script src="{{url('public/js/record/recorder.js')}}" defer></script>
                        <script src="{{url('public/js/record/Fr.voice.js')}}" defer></script>
                        <script src="{{url('public/js/record/recordapp.js')}}" defer></script>
                        <a class="btn btn-danger btn-sm text-white" id="record" data-action="start">Start Recording</a>
                        <input type="hidden" id="saverecurl" value="{{URL::to("saverec")}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-2">
                        <a href="{{URL::to('iui/extra-visit/'.encrypt($iui->patients_id).'/'.encrypt($cycleNo))}}" class="btn btn-primary btn-ivf-report">Extra Visit</a>
                    </div>
                </div>
                
                @if($remark && !$iuiHistoryId)
                    <span class="remark-text">Remark: {{$remark}}</span>
                    <br>
                    <br>
                @endif
                <div class="row">
                    {{-- <div class="col-md-12"> --}}
                        {{-- <h6>Husband Factor :</h6>  --}}
                        <div class="col-md-3"><span class="font-bold font-16">Husband Factor : </span>{{isset($husbandFactor->remark) ? $husbandFactor->remark : ''}}</div>
                        <div class="col-md-3">
                            <span class="font-bold">Age : </span>{{isset($husbandFactor->age) ? $husbandFactor->age : ''}}
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold">Sperm Count : </span>{{isset($husbandFactor->sperm_count) ? $husbandFactor->sperm_count : ''}}
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold">Motility : </span>{{isset($husbandFactor->motility) ? $husbandFactor->motility : ''}}
                        </div>
                    {{-- </div>  --}}
                    
                </div>
                <br>
                {{--1 lmd date --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#lmp" href="#lmp" aria-expanded="false"
                            aria-controls="lmp">1. L.M.P</a></h4>
                        </div>
                    <div id="lmp" class="panel-collapse collapse lmp-tab" role="tabpanel"
                    aria-labelledby="headingThree_1">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                                    {{Form::text("data[lmp][date]",$lmddate,['class'=>'form-control lmd-date second-visit-lmd-date'])}}
                                </div>
                                <span class="form-error-msg lmp-date-msg"></span>
                            </div>
                                <span class="col-md-1 p-2 lmd-date-diff">{{isset($lmdDiff) ? $lmdDiff." Day" : null }}</span>
                                {{Form::hidden('data[lmp][lmp_date_diff]',$lmdDiff,['class'=>'lmd-date-diff-val'])}}
                                {{Form::hidden('visit',2,['class'=>'visit-value'])}}
                                {{Form::hidden('iui_history_id',$iuiHistoryId, ['id' => 'iui_history_id'])}}
                                {{-- {{ Form::hidden('iui_id', null, ['id' => 'iui_id']) }} --}}
                                <div class="col-md-1 text-right">
                                    <label class="vertical-form-label">
                                        Weight :
                                    </label>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        {{Form::text('data[weight]','',['class'=>'form-control weight','placeholder'=>'Enter Weight'])}}
                                    </div>
                                    <span class="weight-by-error text-danger mb-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 2.Co --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#co" href="#co" aria-expanded="false"
                                aria-controls="co">2. C/O</a></h4>
                    </div>
                    <div id="co" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        C/O :
                                    </label>
                                </div>
                                <div class="col-md-10 complain-multi">
                                    @php
                                        $cClass = $iuiHistoryId ? '' : 'complaint-data';
                                    @endphp
                                    {{Form::select('data[co_type][]',$complaints,!empty($historyCo) ? $historyCo : null,['class'=>'form-control co-value co_value_data '.$cClass,'placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'2'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--3 oe --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#o_e_second" href="#o_e_second" aria-expanded="false"
                                                aria-controls="o_e_second">3. O/E</a></h4>
                    </div>
                    <div id="o_e_second" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            @php
                                $vitlasClass = !empty($historyLmp->le->vitals_status) && $historyLmp->le->vitals_status == 'yes' ? '' : 'd-none';
                                $adnexaStaus = !empty($historyOe->adnexa->type) && $historyOe->adnexa->type == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[lmp][le][vitals_status]','yes',!empty($historyLmp->le->vitals_status) && $historyLmp->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'vitals_status','data-id'=>'vitals_status_data'])}}
                                        <label for="vitals_status">
                                            Vitals
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                        {{Form::text("data[lmp][le][bp]",!empty($historyLmp->le->bp) ? $historyLmp->le->bp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">MMHG</span>
                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                        {{Form::text("data[lmp][le][temp]",!empty($historyLmp->le->temp) ? $historyLmp->le->temp : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                        {{Form::text("data[lmp][le][pulse]",!empty($historyLmp->le->pulse) ? $historyLmp->le->pulse : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">/ Min</span>
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Adnexa :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[oe][adnexa][type]",'yes',!empty($adnexaStaus) ? false : true,['id'=>'adnexa_type_yes','class'=>'iui-yes-no-status','data-type'=>'adnexa-details'])}}
                                        <label for="adnexa_type_yes">
                                            Yes
                                        </label>
            
                                        {{Form::radio("data[oe][adnexa][type]",'no',!empty($adnexaStaus) ? true : false,['id'=>'adnexa_type_no','class'=>'iui-yes-no-status','data-type'=>'adnexa-details'])}}
                                        <label for="adnexa_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-5 adnexa-details '.$adnexaStaus}}">
                                    <div class="form-group">
                                        {{Form::text("data[oe][adnexa][details]",!empty($historyData->adnexa->details) ? $historyData->adnexa->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        OE :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[oe][oe_type][type]",'tvs',!empty($historyOe->oe_type->type) && $historyOe->oe_type->type == 'tvs' ? true : false,['id'=>'tvs'])}}
                                        <label for="tvs">
                                            TVS
                                        </label>

                                        {{Form::radio("data[oe][oe_type][type]",'pa',!empty($historyOe->oe_type->type) && $historyOe->oe_type->type == 'pa' ? true : false,['id'=>'pa'])}}
                                        <label for="pa">
                                            PA
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        UT :
                                    </label>
                                </div>
                                @php
                                    $utType = 'd-none';
                                    if(!empty($historyOe->ut->ut_type) && $historyOe->ut->ut_type == 2){
                                        $utType = '';
                                    }
                                    $ovaryType = !empty($historyOe->ovary->ovary_type) ? $historyOe->ovary->ovary_type : [];
                                    $ovaryLeftType = !empty($historyOe->ovary->left->type) && $historyOe->ovary->left->type == 1 ? 'd-none' : null;
                                    $ovaryRightType = !empty($historyOe->ovary->right->type) && $historyOe->ovary->right->type == 1 ? 'd-none' : null;
                                    if(!$iuiHistoryId){
                                        $ovaryLeftType = 'd-none';
                                        $ovaryRightType = 'd-none';
                                    }
                                @endphp
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select("data[oe][ut][ut_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ut->ut_type) ? $historyOe->ut->ut_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ut-type'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-3 ut-type '.$utType}}">
                                    <div class="form-group">
                                        {{Form::text("data[oe][ut][details]",!empty($historyOe->ut->details) ? $historyOe->ut->details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                            </div>
                            @php
                                $leftData = in_array('left',$ovaryType) ? null : 'd-none';
                                $rightData = in_array('right',$ovaryType) ? null : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        Ovary :
                                    </label>
                                </div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[oe][ovary][ovary_type][]','right',in_array('right',$ovaryType),['id'=>'oe_right','class'=>'plan-management'])}}
                                        <label for="oe_right">
                                            Right
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-10 oe-right-details'}}">
                                    <div class="row">
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::select("data[oe][ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ovary->right->type) ? $historyOe->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                            </div>
                                        </div> --}}
                                        <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type'}} ">
                                            {{Form::select("data[oe][ovary][right][details][]",$rightOvaryData,!empty($historyOe->ovary->right->details) ? $historyOe->ovary->right->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_right_details',
                                                'data-id' => '2',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">AFCS : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][right][afcs]",!empty($historyOe->ovary->right->afcs) ? $historyOe->ovary->right->afcs : null,['class'=>'form-control second-right-ovary-data-text'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="javascript:void(0)" class="second-right-ovary-data overy-popup" data-class='second-right-ovary-data'>Keyboard</a>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Residual follicale : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][right][residual_follicale]",!empty($historyOe->ovary->right->residual_follicale) ? $historyOe->ovary->right->residual_follicale : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="{{ 'row oe-right-details'}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-7 complain-multi ovary-right-abnormal-type ml-5">
                                    <div class="row edit_oe_ovary_right_details">
                                        @if (isset($historyOe->ovary->right->updated_details) && !empty($historyOe->ovary->right->updated_details))
                                            @foreach ($historyOe->ovary->right->updated_details as $key => $value)
                                                <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->right->details[$key]) . '_right' }}">
                                                    {{Form::text('data[oe][ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                        'class' => 'form-control edited_oe_ovary_right_details',
                                                        'id' => preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->right->details[$key])
                                                    ])}}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[oe][ovary][ovary_type][]','left',in_array('left',$ovaryType),['id'=>'oe_left','class'=>'plan-management'])}}
                                        <label for="oe_left">
                                            Left
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-10 oe-left-details'}}">
                                    <div class="row">
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                {{Form::select("data[oe][ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($historyOe->ovary->left->type) ? $historyOe->ovary->left->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                            </div>
                                        </div> --}}
                                        <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type'}} ">
                                            {{Form::select("data[oe][ovary][left][details][]",$leftOvaryData,!empty($historyOe->ovary->left->details) ? $historyOe->ovary->left->details : null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_left_details',
                                                'multiple'=>true,
                                                'data-id' => '2',
                                            ])}}
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="input-group">
                                                <span class="input-group-addon">AFCS : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][left][afcs]",!empty($historyOe->ovary->left->afcs) ? $historyOe->ovary->left->afcs : null,['class'=>'form-control second-left-ovary-data-text'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="javascript:void(0)" class="second-left-ovary-data overy-popup" data-class='second-left-ovary-data'>Keyboard</a>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Residual follicale : &nbsp;</span>
                                                {{Form::text("data[oe][ovary][left][residual_follicale]",!empty($historyOe->ovary->left->residual_follicale) ? $historyOe->ovary->left->residual_follicale : null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="{{ 'row oe-left-details'}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-7 complain-multi ovary-left-abnormal-type ml-5">
                                    <div class="row edit_oe_ovary_left_details">
                                        @if (isset($historyOe->ovary->left->updated_details) && !empty($historyOe->ovary->left->updated_details))
                                            @foreach ($historyOe->ovary->left->updated_details as $key => $value)
                                                <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->left->details[$key]) . '_left' }}">
                                                    {{Form::text('data[oe][ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                        'class' => 'form-control edited_oe_ovary_left_details',
                                                        'id' => preg_replace('/[^a-zA-Z0-9]/','_',$historyOe->ovary->left->details[$key])
                                                    ])}}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>

                            <br>
                            <div class="row">
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Endometrial Cavity :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::text("data[oe][endometrial_cavity][cavity]",!empty($historyOe->endometrial_cavity->cavity) ? $historyOe->endometrial_cavity->cavity : null,['class'=>'form-control','placeholder'=>'Endometrial Cavity Details'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Size : &nbsp;</span>
                                        {{Form::text("data[oe][endometrial_cavity][size]",!empty($historyOe->endometrial_cavity->size) ? $historyOe->endometrial_cavity->size : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="col-md-1 p-2">M</span>
                            </div>
                            @php
                                $pStatus = !empty($historyOe->p_s->type) && $historyOe->p_s->type == 'yes' ? '' : 'd-none';
                            @endphp
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[oe][p_s][type]','yes',!empty($pStatus) ? false : true,['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                                        <label for="ps_status">
                                            P/S :
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 ps-details '.$pStatus}}">
                                    <div class="form-group">
                                        {{Form::text("data[oe][p_s][details]",!empty($historyOe->p_s->details) ? $historyOe->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 4 plan --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#paln" href="#paln" aria-expanded="false"
                                                aria-controls="paln">4. Plan</a></h4>
                    </div>
                    <div id="paln" class="panel-collapse collapse plan-tab" role="tabpanel"
                        aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- @if (!empty($historyPlan->plan_type))
                                        <div class="col-md-8 complain-multi mb-3">
                                            Plan Type:
                                            {{Form::label('plan-type',!empty($historyPlan->plan_type) ? $historyPlan->plan_type : null)}}
                                            {{Form::hidden('data[plan][plan_type]', !empty($historyPlan->plan_type) ? $historyPlan->plan_type : null) }}
                                        </div>
                                    @else --}}
                                        <div class="form-group">
                                            {{Form::select("data[plan][plan_type]",$planType,!empty($historyPlan->plan_type) ? $historyPlan->plan_type : null,['class'=>'form-control select-padding-0 plan-type','placeholder'=>'Select Plan Type','data-live-search'=>"true"])}}
                                        </div>
                                        <span class="form-error-msg plan-type-msg"></span>
                                    {{-- @endif --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::text("data[plan][other]",!empty($historyPlan->other) ? $historyPlan->other : null,['class'=>'form-control','placeholder'=>'Other'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Inducing Agent :
                                    </label>
                                </div>
                                {{-- @if (!empty($historyPlan->agenet))
                                    <div class="col-md-8 complain-multi mb-3">
                                        {{Form::select('data[plan][agenet][]',!empty($historyPlan->agenet) ? $historyPlan->agenet : null,range(0,count($historyPlan->agenet)),['class'=>'form-control medicine co_value_data remove-border','multiple'=>true,'disabled'])}}
                                    </div>
                                @else --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::select("data[plan][agenet][]",!empty($historyPlan->plan_type) ? $planData : [], !empty($historyPlan->agenet) ? $historyPlan->agenet : null,['class'=>'form-control select-padding-0 plan-data plan-follow-up','title'=>'Select Plan','data-live-search'=>"true"])}}
                                        </div>
                                        <span class="plan-data-error form-error-msg"></span>
                                    </div>
                                {{-- @endif --}}
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select("data[plan][agenet][]",!empty($historyPlan->plan_type) ? $planData : [],!empty($historyPlan->agenet) ? $historyPlan->agenet : null,['class'=>'form-control select-padding-0 plan-data plan-follow-up','multiple','title'=>'Select Plan','data-live-search'=>"true", !empty($historyPlan->agenet) ? 'disabled' : ''])}}
                                    </div>
                                </div> --}}
                                @php
                                $agentDataValue = [];
                                $dateAndInjectionData = [];
                                    if(!empty($historyInducingDate)){
                                        
                                        $dateAndInjectionData[] = (array)$historyInducingDate;
                                    } 
                                    foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                    {
                                        array_push($agentDataValue,$valueData->date);
                                    }
                                @endphp
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        Inducing Date :
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::text("data[inducing][date]",!empty($agentDataValue) ? implode(',',$agentDataValue) : '',['class'=>'form-control datetime second-visit-inducing','placeholder'=>'Inducing Agent Date','autocomplete'=>'on'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">Follow Up: &nbsp;</span>
                                        @if(!empty($historyPlan->follow_up))
                                            {{Form::text("follow_up",!empty($historyPlan->follow_up) ? \Carbon\Carbon::parse($historyPlan->follow_up)->format('D d M Y') : null,['class'=>'form-control datetimepicker follow-up-date next-date','disabled'])}}
                                            {{Form::hidden('data[plan][follow_up]',$historyPlan->follow_up)}}
                                            {{Form::hidden('data[new_follow_up]',$historyPlan->follow_up)}}
                                            {{Form::hidden('is_notAvailable',0,['class'=>'is-notAvailable'])}}
                                        @else
                                            {{Form::text("data[plan][follow_up]",!empty($historyPlan->follow_up) ? \Carbon\Carbon::parse($historyPlan->follow_up)->format('D d M Y') : null,['class'=>'form-control datetimepicker follow-up-date next-date'])}}
                                            {{Form::hidden('is_notAvailable',0,['class'=>'is-notAvailable'])}}
                                        @endif
                                    </div>
                                    <span class="follow-date-msg form-error-msg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            I.U.I :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[iui]",'yes',!empty($historyData->iui) && $historyData->iui == 'yes' ? true : false,[
                                'id'=>'iui_status_yes',
                                !empty($historyData->iui) && $historyData->iui == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="iui_status_yes">
                                Yes
                            </label>
                            {{Form::radio("data[iui]",'no',!empty($historyData->iui) && $historyData->iui == 'no' ? true : false,[
                                'id'=>'iui_status_no',
                                !empty($historyData->iui) && $historyData->iui == 'yes' ? 'disabled' : ''
                            ])}}
                            <label for="iui_status_no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                {{-- 5 .treatment --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                aria-controls="past-history">5. Treatment</a></h4>
                    </div>
                    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row treatment-data" id="t_data_1">
                                    <div class="col-md-2 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Select Medicine :
                                        </label>
                                    </div>
                                <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                    {{Form::select('data[treatment][medicinedata][]',$medicines,$historyMedicineKey,['id'=>'treatment-medicine','class'=>'form-control medicine','placeholder'=>"Enter medicine name",'data-type'=>'iui-cycle'])}}
                                </div>
                            </div>
                            <div class="page-loader-wrapper medicine-loader d-none">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                            
                            @if(!empty($historyTreatment))
                                @foreach($historyTreatment as $key=>$row)
                                {{-- @if(isset($medicines[$row->medicine])) --}}
                                    <?php
                                    $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                    $firstCharacter = substr($mId, 0, 3);
                                    $notinject = "";
                                    if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                        $notinject = "is-inj";
                                    }
                                    $till_follow_up = (empty($row->no)) ? 'till-follow-up' : '';
                                    ?>
                                    <div class="{{'row mt-2 '.$notinject}}" data-id="{{$mId}}">
                                        <div class='col-md-2'>
                                            <div class='input-group'>
                                                <span class='input-group-addon'>M : </span>
                                                {{Form::text('data[treatment]['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-1 notinject'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-1 notinject'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-1 notinject'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-1 notinject'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-2 notinject'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-2 isinject'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-2'>
                                            <div class='form-group'>
                                                {{Form::select('data[treatment]['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-2'>
                                            <div class='input-group'>
                                                <span class='input-group-addon'>Day :</span>
                                                {{Form::number('data[treatment]['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                                            </div>
                                        </div>
                                        <div class='col-md-4'>
                                            <div class='form-group'>
                                                <!-- <span class='input-group-addon'>Day :</span> -->
                                                {{Form::text('data[treatment]['.$mId.'][note]',isset($row->note) ? $row->note : '',['class'=>'form-control','placeholder'=>'Note'])}}
                                            </div>
                                        </div>
                                        <div class='col-md-1 medicine-data-remove'>
                                            <span class=""><i class="material-icons">close</i></span>
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                            <div class="medicine-data"></div>
                            {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}}
                        </div>
                    </div>
                </div>
                {{-- 6 .husband_factor --}}
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#husband_factor" href="#husband_factor" aria-expanded="false"
                                aria-controls="husband_factor">6. Husband Factor</a></h4>
                    </div>
                    <div id="husband_factor" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Age : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[age]",'',['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Sperm Count : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[sperm_count]",'',['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Motility : &nbsp;
                                        </span>
                                        {{Form::text("h_factor[motility]",'',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        {{Form::textarea("h_factor[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            I.V.F :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[ivf]",'yes',!empty($historyData->ivf) && $historyData->ivf == 'yes' ? true : false,[
                                'class'=>'iui-yes-no-status ivf-transfer',
                                'id'=>'ivf_status_yes',
                                !empty($historyData->ivf) && $historyData->ivf == 'yes' ? 'disabled' : '','data-type'=>'ivf-plans'
                            ])}}
                            <label for="ivf_status_yes">
                                Yes
                            </label>
                            {{Form::radio("data[ivf]",'no',!empty($historyData->ivf) && $historyData->ivf == 'no' ? true : false,[
                                'class'=>'iui-yes-no-status ivf-transfer',
                                'id'=>'ivf_status_no',
                                !empty($historyData->ivf) && $historyData->ivf == 'yes' ? 'disabled' : '',
                                'data-type'=>'ivf-plans'
                            ])}}
                            <label for="ivf_status_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-5 ivf-plans d-none'}}">
                        <div class="form-group">
                            {{Form::select("data[ivf_plan]",['1'=>'Self','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'],null,['class'=>'form-control select-padding-0 ivf-transfer-plan','placeholder'=>'select IVF Plan'])}}
                        </div>
                        <span class="form-error-msg ivf-plans-msg"></span>
                    </div>
                </div>
                @php
                $bloodReportClass = !empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'yes' ? true : false;
                $bloodReportClassName = $bloodReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Blood Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[blood_report][type]",'yes',$bloodReportClass,['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[blood_report][type]",'no',!empty($historyData->blood_report) && !empty($historyData->blood_report->type) && $historyData->blood_report->type == 'no' ? true : false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 blood-type '.$bloodReportClassName}}">
                        <div class="data-blood-images"></div>
                    </div>
                </div>
                @php
                        $hsaReportClass = !empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'yes' ? true : false;
                        $hsaReportClassName = $hsaReportClass ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HSA Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hsa_report][type]",'yes',$hsaReportClass,['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[hsa_report][type]",'no',!empty($historyData->hsa_report) && !empty($historyData->hsa_report->type) && $historyData->hsa_report->type == 'no' ? true : false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 hsa-type '.$hsaReportClassName}}">
                        <div class="data-hsa-images"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("data[investigation_extra]",isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? $historyData->investigation_extra : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <!-- {{-- 4 .Remark --}} -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            {{Form::textarea("data[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'5'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                </div>
            @endif
            @if($visitNo == 3 || $old_edit_cycle == true)
                <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                @if($remark && !$iuiHistoryId)
                    <span class="remark-text m-0">Remark: {{$remark}}</span>
                    <br>
                    <br>
                @endif
                
                {{Form::hidden('visit',3, ['id' => 'visit','class'=>'visit-value'])}}
                {{Form::hidden('iui_history_id',$iuiHistoryId, ['id' => 'iui_history_id'])}}
                {{-- {{Form::hidden('iui_history_id',$iuiHistoryId)}} --}}
                @php
                    $vitlasClass = !empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-2 d-none">
                        <div class="input-group">
                            <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                            {{Form::text("data[lmp][date]",$lmddate,['class'=>'form-control lmd-date'])}}
                        </div>
                    </div>
                    @php
                        $lmpDataDiff = isset($lmdDiff) ? $lmdDiff : 0;
                        if(!isset($lmpDataDiff)){
                            $lmpDataDiff = !isset($iuiSecondVisitData->lmp->lmp_date_diff) ? $iuiSecondVisitData->lmp->lmp_date_diff : 0;
                        }
                    @endphp
                    {{Form::hidden('data[lmp][lmp_date_diff]',$lmpDataDiff,['class'=>'lmd-date-diff-val'])}}
            @endif
            @if($visitNo == 4 && $old_edit_cycle == false)
                <div class="row">
                    <div class="col-md-5">
                        <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-2 text-right">
                        <a href="{{URL::to('iui/extra-visit/'.encrypt($iui->patients_id).'/'.encrypt($cycleNo))}}" class="btn btn-primary btn-ivf-report">Extra Visit</a>
                    </div>
                </div>
                @if($remark && !$iuiHistoryId)
                    <span class="remark-text m-0">Remark: {{$remark}}</span>
                    <br>
                    <br>
                @endif
                {{-- {{Form::hidden('iui_history_id',$iuiHistoryId)}} --}}
                {{Form::hidden('iui_history_id',$iuiHistoryId, ['id' => 'iui_history_id'])}}
                {{Form::hidden('visit',4,['class'=>'visit-value'])}}
                <div class="row">
                    <span class="col-md-2 visit-lable">Patients Name :</span>
                    <span class="col-md-4 visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['name']}}</span>
                    <span class="col-md-2 visit-lable">Age : &nbsp<span class="col-md-2 visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['age']}}</span></span>
                </div>
                <br />
                <div class="row">
                    <span class="col-md-2 visit-lable">No. Follicle :</span>
                    <span class="col-md-4 visit-lable-value">{{$follicleString}}</span>
                    <span class="col-md-2 visit-lable">Plan : &nbsp<span class="col-md-2 visit-lable-value">{{$planOfSecondVisit}}</span></span>
                </div>
                <br />
                <div class="row">
                    <span class="col-md-2 visit-lable">Cycle No :</span>
                    <span class="col-md-4 visit-lable-value">{{$cycleNo}}</span>
                </div>
                <br />
                <br />
                <div class="row mt-3">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Seen By :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('seen_by_4',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by-4','placeholder'=>'Select Doctor'])}}
                        </div>
                        <span class="seen-by-error-4 text-danger mb-2"></span>
                    </div>
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            RMO Doctor :
                        </label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::select('rmo_doctor',$rmoDoctor,'',['class'=>'form-control select-padding-0','placeholder'=>'Select RMO Doctor'])}}
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#h_o" href="#h_o" aria-expanded="false"
                                aria-controls="past-history">1. H/O</a></h4>
                    </div>
                    <div id="h_o" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                       Follow Up Case: 
                                    </label>
                                </div>
                                @php
                                //check iui=yes in 3 visit
                                foreach($iuiThirdVisitData as $iuiThirdVisitData)
                                {
                                    if(!empty($iuiThirdVisitData->hcg->iui->status) && $iuiThirdVisitData->hcg->iui->status == 'yes')
                                    {
                                        $follow_up_case_iui = 1;
                                    }
                                }
                                if(isset($iuiSecondVisitData->iui) && ($iuiSecondVisitData->iui == 'yes') || (isset($follow_up_case_iui) && $follow_up_case_iui == 1))    
                                {
                                    $follow_up_case = 3;
                                }
                                elseif(isset($iuiSecondVisitData) && !empty($iuiSecondVisitData->plan->plan_type) && $iuiSecondVisitData->plan->plan_type == 'Natural')
                                {
                                    $follow_up_case = 1;
                                }
                                else
                                {
                                    $follow_up_case = 2;
                                }
                                @endphp
                                <div class="col-md-4 child-naturally">
                                    <div class="form-group">
                                        {{Form::select("data[ho_type]",['1'=>'Naturally','2'=>'Medicine','3'=>'IUI'],$follow_up_case,['class'=>'form-control select-padding-0 follow-up-case child-ho-type p-ho-type'])}}
                                    </div>
                                </div>
                                <div class="">
                                    <label class="vertical-form-label">
                                        Weight :
                                    </label>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        {{Form::text('data[weight]','',['class'=>'form-control weight','placeholder'=>'Enter Weight'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="vertical-form-label pr-0">
                                        UPT :
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[upt_type]",'positive','',[
                                            'id'=>'positive',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="positive">
                                            Positive
                                        </label>

                                        {{Form::radio("data[upt_type]",'weak_positive','',[
                                            'id'=>'weak_positive',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="weak_positive">
                                            Weak Positive
                                        </label>

                                        {{Form::radio("data[upt_type]",'negative','',[
                                            'id'=>'negative',
                                            'class'=>'upt-type',
                                        ])}}
                                        <label for="negative">
                                            Negative
                                        </label>
                                    </div>
                                    <span class="form-error-msg upt-type-msg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#c_o" href="#c_o" aria-expanded="false"
                                aria-controls="past-history">2. C/O</a></h4>
                    </div>
                    <div id="c_o" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row mt-3">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        C/O :
                                    </label>
                                </div>
                                <div class="col-md-10 complain-multi">
                                    {{Form::select('data[co_type][]',$complaints,'',['class'=>'form-control co-value co_value_data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'0'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#o_e" href="#o_e" aria-expanded="false"
                                aria-controls="past-history">3. O/E</a></h4>
                    </div>
                    <div id="o_e" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[le][vitals_status]','yes',false,['class'=>'vitals_status','id'=>'re_vitals_status','data-id'=>'re_vitals_status_data'])}}
                                        <label for="re_vitals_status">
                                            Vitals
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-2 re_vitals_status_data d-none'}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                        {{Form::text("data[le][bp]",'',['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 re_vitals_status_data d-none'}}">MMHG</span>
                                <div class="{{'col-md-2 re_vitals_status_data d-none'}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                        {{Form::text("data[le][temp]",null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="{{'col-md-2 re_vitals_status_data d-none'}}">
                                    <div class="input-group">
                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                        {{Form::text("data[le][pulse]",null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 re_vitals_status_data d-none'}}">/ Min</span>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        P/S :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[p_s][type]",'yes','',['id'=>'ps_type_yes','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                        <label for="ps_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("data[p_s][type]",'no',true,['id'=>'ps_type_no','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                        <label for="ps_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-5 ps-details d-none'}}">
                                    <div class="form-group">
                                        {{Form::text("data[p_s][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        P/A :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[p_a][type]",'yes','',['id'=>'pa_type_yes','class'=>'iui-yes-no-status','data-type'=>'pa-details'])}}
                                        <label for="pa_type_yes">
                                            Yes
                                        </label>

                                        {{Form::radio("data[p_a][type]",'no',true,['id'=>'pa_type_no','class'=>'iui-yes-no-status','data-type'=>'pa-details'])}}
                                        <label for="pa_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-5 pa-details d-none'}}">
                                    <div class="form-group">
                                        {{Form::text("data[p_a][details]",'',['class'=>'form-control','placeholder'=>'Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        TVS :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[tvs][type]",'yes','',['id'=>'tvs_type_yes','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                        <label for="tvs_type_yes">
                                            Yes
                                        </label>
                                        {{Form::radio("data[tvs][type]",'no',true,['id'=>'tvs_type_no','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                        <label for="tvs_type_no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details d-none'}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Uterus :
                                    </label>
                                </div>
                                <div class="col-md-2 tvs-details">
                                    <div class="form-group">
                                        {{Form::select("data[uterus][type]",['1'=>'Normal','2'=>"Abnormal"],null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
                                    </div>
                                </div>
                                {{-- @php
                                    $uterusType = !empty($oe->uterus->type) && $oe->uterus->type == '2' ? '' : 'd-none';
                                @endphp --}}
                                <div class="{{'col-md-2 uterus-abnormal-type d-none'}}">
                                    <div class="form-group">
                                        {{Form::text("data[uterus][details]",null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                                    </div>
                                </div>
                                <span class="{{'col-md-1 p-2 uterus-abnormal-type d-none'}}">LG</span>
                            </div>
                            <div class="{{'row tvs-details d-none'}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-2 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Endometrial Thickness :
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::text("data[endometrial_thickness]",null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details d-none'}}">
                                <div class="col-md-1"></div>
                                <div class="col-md-1 pr-0">
                                    <label class="vertical-form-label pr-0">
                                        Ovary :
                                    </label>
                                </div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[ovary][type][]','right','',['id'=>'right','class'=>'plan-management'])}}
                                        <label for="right">
                                            Right
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 right-details'}}">
                                    <div class="form-group">
                                        {{Form::select("data[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="{{'col-md-9 right-details tvs-details d-none'}}">
                                    <div class="row">
                                        <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type mt-1 d-none'}}">
                                            {{Form::select("data[ovary][right][details][]",$rightOvaryData,null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_right_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-6 complain-multi ovary-right-abnormal-type">
                                            <div class="row edit_oe_ovary_right_details">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details mt-3 d-none'}}">
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        {{Form::checkbox('data[ovary][type][]','left','',['id'=>'left','class'=>'plan-management'])}}
                                        <label for="left">
                                            Left
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-3 left-details'}}">
                                    <div class="form-group">
                                        {{Form::select("data[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="{{'row tvs-details d-none'}}">
                                <div class="col-md-3"></div>
                                <div class="{{'col-md-9 left-details'}}">
                                    <div class="row">
                                        <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type d-none'}} ">
                                            {{Form::select("data[ovary][left][details][]",$leftOvaryData,null,[
                                                'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                                'placeholder'=>'Abnormal Details',
                                                'id' => 'oe_ovary_left_details',
                                                'multiple'=>true
                                            ])}}
                                        </div>
                                        <div class="col-md-6 complain-multi ovary-left-abnormal-type">
                                            <div class="row edit_oe_ovary_left_details">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#result" href="#result" aria-expanded="false"
                                aria-controls="past-history">5. Result</a></h4>
                    </div>
                    <div id="result" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row mt-3">
                                <div class="col-md-1">
                                    <label class="vertical-form-label pr-0">
                                        Result :
                                    </label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="radio is-conceived">
                                        {{Form::radio("data[result]",'fail',!empty($historyData->result) && $historyData->result == 'fail' ? true : false,['id'=>'fail','class'=>'r-type result_fail'])}}
                                        <label for="fail">
                                            Fail
                                        </label>

                                        {{Form::radio("data[result]",'consive',!empty($historyData->result) && $historyData->result == 'consive' ? true : false,['id'=>'consive','class'=>'r-type result_conceive'])}}
                                        <label for="consive">
                                            Conceive
                                        </label>
                                    </div>
                                    <span class="form-error-msg r-type-msg"></span>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingThree_1">
                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                aria-controls="past-history">6. Treatment</a></h4>
                    </div>
                    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                        <div class="panel-body" id="parent">
                            <div class="row treatment-data" id="t_data_1">
                                    <div class="col-md-2 pr-0">
                                        <label class="vertical-form-label pr-0">
                                            Select Medicine :
                                        </label>
                                    </div>
                                <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                    {{Form::select('data[treatment][medicinedata][]',$medicines,'',['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co','placeholder'=>"Enter Medicine",'data-type'=>'iui-cycle'])}}
                                </div>
                            </div>
                            <div class="page-loader-wrapper medicine-loader d-none">
                                <div class="loader">
                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                </div>
                            </div>
                            <div class="medicine-data"></div>
                        </div>
                    </div>
                </div> 
                
                <div class="row mt-1">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Blood Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[blood_report][type]",'yes','',['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[blood_report][type]",'no',false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                            <label for="blood_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 blood-type d-none'}}">
                        <div class="data-blood-images"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            HSA Report :
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="radio is-conceived">
                            {{Form::radio("data[hsa_report][type]",'yes','',['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_yes">
                                Yes
                            </label>

                            {{Form::radio("data[hsa_report][type]",'no',false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                            <label for="hsa_type_no">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-8 pr-0 hsa-type d-none'}}">
                        <div class="data-hsa-images"></div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Other Report : &nbsp;
                            </span>
                            {{Form::text("data[investigation_extra]",isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? $historyData->investigation_extra : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                Date : &nbsp;
                            </span>
                            @if(!empty($historyData->date))
                                {{Form::hidden('data[new_follow_up]',$historyData->date)}}
                            @endif
                            {{Form::text("data[date]",!empty($historyData->date) ? \Carbon\Carbon::parse($historyData->date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker date next-date',!empty($historyData->date) ? 'disabled' : ''])}}
                            {{Form::hidden('is_notAvailable',0,['class'=>'is-notAvailable'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            {{Form::textarea("data[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Dr. Remark','rows'=>'5'])}}
                        </div>
                        <span class="form-error-msg">
                            {{$errors->first('remark')}}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{Form::textarea("data[pt_remark]",'',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'5'])}}
                        </div>
                    </div>
                </div>
            @endif
            @php
                $hTime = !empty($hcg->time) ? $hcg->time : null;
                $typeOfData = [1=>'Primary',2=>'Secondary'];
                $ohData = json_decode($iuiFirstVisit->o_h);
                $possibleFactorData = json_decode($iuiFirstVisit->possible_case_of_infertility);
                $possibleFactorData = !empty($possibleFactorData->infertility_type) ? $possibleFactorData->infertility_type : [];
                $dateAndInjectionData = [];
                
            @endphp
            @if($visitNo == 3 || $old_edit_cycle == true)
            @php
                $lastHistoryData = json_decode($iuiHistoryData[count($iuiHistoryData)-1]['description']);
                $secondVisitHusbandFactor = json_decode($iuiSecondVisit->husband_factor);
                $husbandFactor = !empty($iuiSecondVisit->husband_factor) && (isset($secondVisitHusbandFactor->sperm_count) || !empty($secondVisitHusbandFactor->remark) || isset($secondVisitHusbandFactor->motility)) ? json_decode($iuiSecondVisit->husband_factor) : json_decode($iuiFirstVisit->husband_factor);
            @endphp
            <div class=" col-md-12 follicular_table_print">
                <div class="row mb-15 mb-5 do_print">{{--mb-15 is used in print--}}
                    <div class="col-md-12 text-center"><h4><u><b>TRANSVAGINAL FOLLICULAR STUDY</b></u></h4></div>
                </div>
                <div class="row follicular-table mb-15 mb-5">{{--mb-15 is used in print--}}
                    <div class="col-md-6 follicular_div_1">
                        <div class="mb-3">
                            <span class="visit-lable">Name :- </span> 
                            <span class="visit-lable-value">{{ucwords(strtolower($iuiSecondVisit->getPatientsInfoData['name']))}}</span>
                        </div>
                        <div class="mb-3">
                                <span class="visit-lable">AGE / Weight :- </span> 
                                <span class="visit-lable-value">{{$iuiSecondVisit->getPatientsInfoData['age'].' Year / '.(isset($lastHistoryData->weight) && !empty($lastHistoryData->weight) ? $lastHistoryData->weight.' kg' : '')}}</span>
                        </div>
                        <div class="mb-3">
                                <span class="visit-lable">Type & Year of infertility :- </span> 
                                <span class="visit-lable-value">{{!empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility] : 'Primary'}} / {{!empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' years' : null}} {{!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' years' : null}}</span>
                        </div>
                        <div class="mb-3">
                                <span class="visit-lable">L.M.P :- </span> 
                                <span class="visit-lable-value">{{!empty($iuiSecondVisitData->lmp->date) ? $iuiSecondVisitData->lmp->date : null}}</span>
                        </div>
                        <div class="mb-3">
                                <span class="visit-lable">Plan :- </span> 
                                <span class="visit-lable-value">{{isset($iuiSecondVisitData->iui) && ($iuiSecondVisitData->iui == 'yes') ? 'COH+IUI ' : ''}} {{!empty($iuiSecondVisitData->plan->plan_type) ? $iuiSecondVisitData->plan->plan_type : null}}</span>
                        </div>
                        <div class="mb-3">
                            <span class="visit-lable">Induction With :- </span> 
                            <span class="visit-lable-value">{{!empty($iuiSecondVisitData->plan->agenet) ? $iuiSecondVisitData->plan->agenet[0] : ''}}</span>
                        </div>
                        @if(!empty($husbandFactor) && isset($husbandFactor->sperm_count) && isset($husbandFactor->motility))
                            <div class="mb-3">
                                <span class="visit-lable">Male Age :- </span> 
                                <span class="visit-lable-value">{{!empty($husbandFactor) && isset($husbandFactor->age) ? $husbandFactor->age : ''}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="visit-lable">Male Factor Remark :- </span> 
                                <span class="visit-lable-value">{{!empty($husbandFactor) && isset($husbandFactor->remark) ? $husbandFactor->remark : ''}}</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 follicular_div_2">
                        <div class="mb-3">
                            <span class="visit-lable">UTERUS :- </span> 
                            <span class="visit-lable-value">{{!empty($iuiSecondVisitData->oe->ut->ut_type) && $iuiSecondVisitData->oe->ut->ut_type == 1 ? 'Normal' : 'Abnormal'}}</span>
                        </div>
                        <div class="mb-3">
                            <span class="visit-lable">TUBES :- </span> 
                            <span class="visit-lable-value"></span>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2 visit-lable">
                                OVARIES :- {{!empty($iuiFirstVisit->ovary->right) ? $iuiFirstVisit->ovary->right : null}}
                                </div>
                                <div class="col-md-10 pl-15">
                                    <div class="mb-2">R :- {{!empty($iuiSecondVisitData->oe->ovary->right->afcs) ? $iuiSecondVisitData->oe->ovary->right->afcs : null}} / RF :- {{!empty($iuiSecondVisitData->oe->ovary->right->residual_follicale) ? $iuiSecondVisitData->oe->ovary->right->residual_follicale : null}}</div>
                                    @if(!empty($iuiSecondVisitData->oe->ovary->right->details))
                                        <div class="mb-2">Right Detail :- {{implode(',',$iuiSecondVisitData->oe->ovary->right->details)}}</div>
                                    @endif
                                    <div class="mb-2">L :- {{!empty($iuiSecondVisitData->oe->ovary->left->afcs) ? $iuiSecondVisitData->oe->ovary->left->afcs : null}} / RF :- {{!empty($iuiSecondVisitData->oe->ovary->left->residual_follicale) ? $iuiSecondVisitData->oe->ovary->left->residual_follicale : null}}</div>
                                    @if(!empty($iuiSecondVisitData->oe->ovary->left->details))
                                        <div class="mb-2">Left Detail :- {{implode(',',$iuiSecondVisitData->oe->ovary->left->details)}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="visit-lable">CYCLE NO :- </span> 
                            <span class="visit-lable-value">{{$cycleNo}}</span>
                        </div>
                        @if(!empty($husbandFactor) && isset($husbandFactor->sperm_count) && isset($husbandFactor->motility))
                            <div class="mb-3">
                                <span class="visit-lable">Sperm Count :- </span> 
                                <span class="visit-lable-value">{{!empty($husbandFactor) ? $husbandFactor->sperm_count : ''}}</span>
                            </div>
                            <div class="mb-3">
                                <span class="visit-lable">Motility :- </span> 
                                <span class="visit-lable-value">{{!empty($husbandFactor) ? $husbandFactor->motility : ''}}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    @if($iuiHistoryData[count($iuiHistoryData)-1]['cycle_status'] != 2)
                        <div class="col-md-1 div-seen-by">
                            <label class="vertical-form-label pr-0">
                                Seen By:
                            </label>
                        </div>
                        <div class="col-md-3 div-seen-by">
                            <div class="form-group">
                                {{Form::select('seen_by_3',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by-3','placeholder'=>'Select Doctor'])}}
                            </div>
                            <span class="seen-by-error-3 text-danger mb-2"></span>
                        </div>
                        <div class="col-md-1 div-seen-by">
                            <label class="vertical-form-label pr-0">
                                RMO Doctor :
                            </label>
                        </div>
                        <div class="col-md-3 div-seen-by">
                            <div class="form-group">
                                {{Form::select('rmo_doctor',$rmoDoctor,'',['class'=>'form-control select-padding-0','placeholder'=>'Select RMO Doctor'])}}
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12 table-responsive">
                        <table class="table follicular-table table-bordered ">
                            <thead>
                                <tr>
                                    <th style="width:8% !important;">Date</th>
                                    <th style="width:5% !important">Day of Menses</th>
                                    <th style="">Rt. Ovary</th>
                                    <th style="">Lt. Ovary</th>
                                    <th style="width:15% !important">Endometrial Thickness / Pattern</th>
                                    <th style="width: 20% !important;">Gonodotropin </th>
                                    <th style="width:10px;">Vascularity of Endometrium</th>
                                    <th style="width: 20% !important;">Dr. Remark</th>
                                    <th style="width: 10% !important;" class="">Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @php
                                    $ovaryType = !empty($historyData->ovary->ovary_status) ? $historyData->ovary->ovary_status : [];
                                    $iuiStatus = !empty($historyData->hcg->iui->status) && $historyData->hcg->iui->status == 'yes' ? '' : 'd-none';
                                    $hcgType = !empty($historyData->hcg->type) && $historyData->hcg->type == 'yes' ? '' : 'd-none';
                                    if(!$iuiHistoryId){
                                        $iuiStatus = 'd-none';
                                        $hcgType = 'd-none';
                                    }
                                    $hcgDataArray = [];
                                @endphp

                               
                                @foreach($iuiHistoryData as $key=>$row)
                                    {{-- @if($row->visit != 4) --}}
                                        @php
                                            $iuiPrevVisit = IuiHistory::where('patients_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','DESC')->first();
                                            if($iuiPrevVisit){
                                                $prevData = json_decode($iuiPrevVisit->description);
                                                $prevAppointmentDate = !empty($prevData->new_follow_up) ? \Carbon\Carbon::parse($prevData->new_follow_up)->format('d-m-Y') : null;
                                            }
                                            $data = json_decode($row->description);
                                            $agentData = !empty($data->plan->inducing_agent) ? $data->plan->inducing_agent : [];
                                            $lmpDate = !isset($data->lmp->date) ?\Carbon\Carbon::parse($iuiSecondVisitData->lmp->date)->format('d-m-Y') : \Carbon\Carbon::parse($data->lmp->date)->format('d-m-Y');
                                            // $lmpDate = \Carbon\Carbon::parse($data->new_follow_up)->format('d-m-Y');
                                            $createdAt = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                            $appointmentDate = !empty($data->new_follow_up) ? \Carbon\Carbon::parse($data->new_follow_up)->format('d-m-Y') : \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                            $diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($createdAt));
                                            $diff = $diff + 1;
                                            $currentDateDiff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse(date('d-m-Y')));
                                            $isCycleComplete = false;
                                            $left_class_name = 'td-left-overy-'.$row->id.'-text';
                                            $right_class_name = 'td-right-overy-'.$row->id.'-text';
                                            $vascularity_of_endo = ['1' => "Up to Zone 1",'2' => "Up to Zone 2",'3' => "Up to Zone 3",'4' => "Up to Zone 4"];
                                            $dateAndInjectionData = [];
                                            $iuiData = [];
                                            $inducingDateArray = [];
                                            $iuiExtraVisit = null;
                                            $skipValue = 0;
                                            if(!empty($prevAppointmentDate)){
                                                $appointmentDate = $prevAppointmentDate;
                                            }
                                            if(isset($data->skip_cycle) && $data->skip_cycle == 'yes')
                                            {
                                                $skipValue = 1;
                                            }
                                            if($row->visit == 2){
                                                $appointmentDate = \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                                                $agentData = !empty($data->plan->agenet) ? $data->plan->agenet: [];
                                            }
                                            if($row->visit == 4 && !empty($data->result))
                                            {
                                                $isCycleComplete = true;
                                            }
                                            if($row->visit != 1)
                                            {
                                                if(!empty($data->inducing)){
                                                    $agentDataValue = [];
                                                    foreach($data->inducing as $key => $value) {
                                                        $inducingDateArray[] = \Carbon\Carbon::parse($value->date)->format('d-m-Y');
                                                        $agentDataValue = !empty($data->plan->inducing_agent) ? $data->plan->inducing_agent : [];
                                                        $value->injection = $agentDataValue;
                                                    }
                                                    $dateAndInjectionData[] = (array)$data->inducing;
                                                    // $dateAnd[] = (array)$data->inducing;
                                                }
                                            }
                                        @endphp
                                        @if(!empty($dateAndInjectionData))
                                            @foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                            @php
                                                $date = \Carbon\Carbon::parse($valueData->date)->format('d-m-Y');
                                                $inducing_diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($valueData->date));
                                                $inducing_diff = $inducing_diff + 1;
                                                if($row->visit == 2)
                                                {
                                                    $inducingAgentDataValue = [];
                                                    if(!empty($agentData))
                                                    {
                                                        foreach($agentData as $injection)
                                                        {
                                                            if((!empty($injection)) && strpos($injection,'+') !== false)
                                                            {
                                                                $injection_name = explode('+',$injection)[1];
                                                                $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                                $inj_name = explode($spilt_from,$injection_name)[0];
                                                                $inducingAgentDataValue[] = $inj_name;
                                                            }
                                                            else {
                                                                $inducingAgentDataValue[] = $injection;
                                                            }
                                                        }
                                                    }
                                                    $inducing_agent = !empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : '';
                                                }
                                                if($row->visit == 3)
                                                {
                                                    $inducingAgentDataValue = [];
                                                    foreach($valueData->injection as $injectionValue){
                                                        $inducingAgentDataValue[] = $inducingInjectionData[$injectionValue];
                                                    }
                                                    $inducing_agent = implode(',',$inducingAgentDataValue);
                                                }
                                            @endphp
                                                @if(!empty($inducing_agent) && ($inducing_diff < $diff) && (!empty($valueData->date)) && ($valueData->date != $appointmentDate))
                                                    <tr >
                                                        <td>{{$date}}</td>
                                                        <td>{{$inducing_diff}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{$inducing_agent}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class=""></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if($row->visit == 2)
                                            @php
                                                $iuiExtraVisit = IuiExtraVisit::where('patient_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','ASC')->get();
                                            @endphp
                                            @if(!empty($iuiExtraVisit))
                                                    @foreach($iuiExtraVisit as $iuiExtra)
                                                    <tr >
                                                        <td>{{\Carbon\Carbon::parse($iuiExtra->created_at)->format('d-m-Y')}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{'Extra Visit'}}</td>
                                                        <td>
                                                            <a href="{{URL::to('iui/extra-visit/'.encrypt($iui->patients_id).'/'.encrypt($cycleNo))}}" class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-iui-data" data-id="{{encrypt($row->id)}}">
                                                                <i class="zmdi zmdi-edit material-icons"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                            @endif
                                        @endif
                                        <tr >
                                            <td>{{$createdAt}}</td>
                                            <td>{{$diff}}</td>
                                            <td class="{{$right_class_name}}" id="{{$row->id}}">
                                                @if($row->visit != 1)
                                                    @php
                                                    if((!empty($data->hcg->type) && $data->hcg->type == 'yes') || !empty($data->ovalution) && $data->ovalution == 'yes')
                                                    {
                                                        $hcgDataArray[] = (array)$data->hcg;
                                                    }
                                                    @endphp
                                                    @if($row->visit == 2)
                                                        {{!empty($iuiSecondVisitData->oe->ovary->right->afcs) ? 'AFCS : '.$iuiSecondVisitData->oe->ovary->right->afcs : null}}
                                                        <!-- {{!empty($iuiSecondVisitData->oe->ovary->right->details) ? implode(',',$iuiSecondVisitData->oe->ovary->right->details) : null}} -->
                                                        {{!empty($iuiSecondVisitData->oe->ovary->right->residual_follicale) ? '/ RF : '.$iuiSecondVisitData->oe->ovary->right->residual_follicale : null}}
                                                    @else
                                                        {{!empty($data->ovary->ovary_type->right->details) ? $data->ovary->ovary_type->right->details : ''}}
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="{{$left_class_name}}">
                                                @if($row->visit == 2)
                                                    {{!empty($iuiSecondVisitData->oe->ovary->left->afcs) ? 'AFCS : '.$iuiSecondVisitData->oe->ovary->left->afcs : null}}
                                                    {{!empty($iuiSecondVisitData->oe->ovary->left->residual_follicale) ? ' / RF : '.$iuiSecondVisitData->oe->ovary->left->residual_follicale : null}}
                                                @else 
                                                    {{!empty($data->ovary->ovary_type->left->details) ? $data->ovary->ovary_type->left->details : ''}}
                                                @endif
                                            </td>
                                            <td class="">{{!empty($data->endometrial->type) ? $data->endometrial->type : ''}}</td>
                                            <td class="">
                                                @if($row->visit == 2 && in_array($appointmentDate,$inducingDateArray))
                                                    @php
                                                        $inducingAgentDataValue = [];
                                                        
                                                        if(!empty($agentData))
                                                        {
                                                            foreach($agentData as $injection)
                                                            {
                                                                if((!empty($injection)) && strpos($injection,'+') !== false)
                                                                {
                                                                    $injection_name = explode('+',$injection)[1];
                                                                    $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                                    $inj_name = explode($spilt_from,$injection_name)[0];
                                                                    $inducingAgentDataValue[] = $inj_name;
                                                                    
                                                                }
                                                                else {
                                                                    $inducingAgentDataValue[] = $injection;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    {{!empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : ''}}
                                                    {{-- {{!empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : ''}} --}}
                                                @endif
                                                @if($row->visit == 3)
                                                @php
                                                $InjectionData = '';
                                                if(!empty($agentData))
                                                {
                                                    foreach($agentData as $agentData)
                                                    {
                                                        $InjectionData = !empty($InjectionData) ? $InjectionData.','.$inducingInjectionData[$agentData] : $inducingInjectionData[$agentData];
                                                    }
                                                }
                                                @endphp
                                                {{$InjectionData}}
                                                @endif
                                            </td>
                                            <td>
                                                {{isset($vascularity_of_endo[$row->vascularity_of_endo]) ? $vascularity_of_endo[$row->vascularity_of_endo] : null}}
                                            </td>
                                            <td class="editStudyReport">
                                                {{!empty($data->remark) ? $data->remark : ''}}
                                            {{isset($data->investigation_extra) && !empty($data->investigation_extra) ? ' Other Report :'.$data->investigation_extra : ''}}

                                            </td>
                                            <td class="editStudyReport text-center">
                                                <a href="#" class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-iui-history" data-id="{{ encrypt($row->id) }}">
                                                    <i class="zmdi zmdi-delete material-icons"></i>
                                                </a>
                                                <a class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-iui-data" data-id="{{encrypt($row->id)}}">
                                                    <i class="zmdi zmdi-edit material-icons"></i>
                                                </a>
                                                @if((isset($data->hsa_report->images) && !empty($data->hsa_report->images)) || (isset($data->blood_report->image) && !empty($data->blood_report->image)) || (isset($data->usg->images) && !empty($data->usg->images)))
                                                
                                                <a href="#" class="btn btn-icon btn-neutral candor-color btn-icon-mini report-btn" data-id="{{ encrypt($row->id) }}" data-date="{{\Carbon\Carbon::parse($row->created_at)->format('d M Y')}}">
                                                    <i class="zmdi zmdi-camera material-icons"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @if(!empty($dateAndInjectionData))
                                            @foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                            @php
                                                $date = \Carbon\Carbon::parse($valueData->date)->format('d-m-Y');
                                                $inducing_diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($valueData->date));
                                                $inducing_diff = $inducing_diff + 1;
                                                if($row->visit == 2)
                                                {
                                                    $inducingAgentDataValue = [];
                                                    if(!empty($agentData))
                                                    {
                                                        foreach($agentData as $injection)
                                                        {
                                                            if((!empty($injection)) && strpos($injection,'+') !== false)
                                                            {
                                                                $injection_name = explode('+',$injection)[1];
                                                                $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                                $inj_name = explode($spilt_from,$injection_name)[0];
                                                                $inducingAgentDataValue[] = $inj_name;
                                                            }
                                                            else {
                                                                $inducingAgentDataValue[] = $injection;
                                                            }
                                                        }
                                                    }
                                                    $inducing_agent = !empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : '';
                                                }
                                                if($row->visit == 3)
                                                {
                                                    $inducingAgentDataValue = [];
                                                    foreach($valueData->injection as $injectionValue){
                                                        $inducingAgentDataValue[] = $inducingInjectionData[$injectionValue];
                                                    }
                                                    $inducing_agent = implode(',',$inducingAgentDataValue);
                                                }
                                            @endphp
                                                @if(!empty($inducing_agent) && ($inducing_diff > $diff) && (!empty($valueData->date)) && ($valueData->date != $appointmentDate))
                                                    <tr >
                                                        <td>{{$date}}</td>
                                                        <td>{{$inducing_diff}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{$inducing_agent}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class=""></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(!empty($data->ovalution) && $data->ovalution == 'yes')
                                            @php
                                                $iuiExtraVisit = IuiExtraVisit::where('patient_id',$row->patients_id)->where('created_at','>',$row->created_at)->orderBy('id','ASC')->get();
                                            @endphp
                                            @if(!empty($iuiExtraVisit))
                                                    @foreach($iuiExtraVisit as $iuiExtra)
                                                    <tr >
                                                        <td>{{\Carbon\Carbon::parse($iuiExtra->created_at)->format('d-m-Y')}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{'Extra Visit'}}</td>
                                                        <td>
                                                            <a href="{{URL::to('iui/extra-visit/'.encrypt($iui->patients_id).'/'.encrypt($cycleNo))}}" class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-iui-data" data-id="{{encrypt($row->id)}}">
                                                                <i class="zmdi zmdi-edit material-icons"></i>
                                                            </a>
                                                    </tr>
                                                    @endforeach
                                            @endif
                                        @endif
                                    {{-- @endif --}}
                                @endforeach
                                
                                @php
                                    $iuiLastVisit = IuiHistory::where('patients_id',$row->patients_id)->orderBy('created_at','DESC')->first();
                                    if($iuiLastVisit){
                                        $lastVisitData = json_decode($iuiLastVisit->description);
                                        $prevAppointmentDate = !empty($lastVisitData->new_follow_up) ? \Carbon\Carbon::parse($lastVisitData->new_follow_up)->format('d-m-Y') : null;
                                        $currentDateDiff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($prevAppointmentDate));
                                        
                                    }
                                @endphp
                                    {{-- nextAppoinment Data --}}
                                    @if($iuiHistoryData[count($iuiHistoryData)-1]['visit'] != 4 && $prevAppointmentDate && $iuiHistoryData[count($iuiHistoryData)-1]['cycle_status'] != 2)
                                        @php
                                            $left_class_name = 'td-left-overy-'.$prevAppointmentDate.'-text';
                                            $right_class_name = 'td-right-overy-'.$prevAppointmentDate.'-text';
                                        @endphp
                                        <tr class="">
                                            {{-- <td>{{\Carbon\Carbon::parse($prevAppointmentDate)->format('d-m-Y')}}</td> --}}
                                            <td>
                                                <div class="{{'edit-follow-data edit-follow-'.$iuiHistoryData[count($iuiHistoryData)-1]['id']}}">
                                                    {{$prevAppointmentDate}}
                                                        <span class="edit-follow">
                                                            <i class="material-icons edit-remark-icon pencil-icon ml-0" data-value="{{$prevAppointmentDate}}" data-id="{{$iuiHistoryData[count($iuiHistoryData)-1]['id']}}">edit</i>
                                                        </span>
                                                </div>
                                            </td>
                                            <td> {{$currentDateDiff+1}}</td>
                                            <td class=""> 
                                                <textarea class="{{$right_class_name.' border-none bg-transparent'}}" name="data[ovary][ovary_type][right][details]" type="text" rows="2"></textarea>
                                                <i class="material-icons td-right-overy-{{$prevAppointmentDate}} overy-popup" data-class='{{'td-right-overy-'.$prevAppointmentDate}}'>keyboard</i>
                                            </td>
                                            <td class="">
                                                <textarea class="{{$left_class_name.' border-none bg-transparent'}}" name="data[ovary][ovary_type][left][details]" type="text"  rows="2"></textarea>
                                                <i class="material-icons td-left-overy-{{$prevAppointmentDate}} overy-popup" data-class='{{'td-left-overy-'.$prevAppointmentDate}}'>keyboard</i>
                                            </td>
                                            <td class=""> 
                                                {{Form::text("data[endometrial][type]",!empty($historyData->endometrial->type) ? $historyData->endometrial->type : null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                                            </td>
                                            <td>
                                                {{Form::select("data[plan][inducing_agent][]",$inducingInjectionData,null,[
                                                    'class'=>'form-control select-padding-0 plan-data',
                                                    'multiple',
                                                    'title'=>'Select Injection',
                                                    'data-live-search'=>"true",
                                                    'id' => 'plan-data'
                                                ])}}
                                            </td>
                                            <td>
                                                {{Form::select('data[vascularity_of_endo]',$vascularity_of_endo,'',['class'=>'form-control select-padding-0 vascularity_of_endo','placeholder'=>'Select Vascularity'])}}
                                            </td>
                                            <td class="">{{Form::textarea("data[remark]",'',['class'=>'form-control no-resize remark','placeholder'=>'Dr. Remark','rows'=>'2'])}}</td>
                                            <td class=""></td>
                                        </tr>
                                    @endif
                                {{-- @endif --}}
                            </tbody>
                            <tfoot class="">
                                <td colspan="9">
                                    @if(!empty($hcgDataArray))
                                        <table class="table follicular-table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>HCG</th>
                                                    <th>IUI</th>
                                                    <th>No Of Follicle </th>
                                                    <th>Ovaluation</th>
                                                    <th>Result</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($iuiHistoryData as $key=>$row)
                                                    @php
                                                    $iui_decription =  json_decode($row->description);
                                                    @endphp
                                                    
                                                    @if(((!empty($iui_decription->hcg) && ($iui_decription->hcg->type == 'yes')) || (!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes')) || ($row->visit == 4))
                                                    <tr>
                                                        <td>
                                                            @if(!empty($iui_decription->hcg->type) && $iui_decription->hcg->type == 'yes' && !empty($iui_decription->hcg_date))
                                                                {{\Carbon\Carbon::parse($iui_decription->hcg_date)->format('d-m-Y')}}
                                                            
                                                            @elseif($row->visit == 4 && !empty($iui_decription->result))
                                                                {{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}
                                                        
                                                            @elseif((!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes'))
                                                                {{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{!empty($iui_decription->hcg->type) && $iui_decription->hcg->type == 'yes' && !empty($iui_decription->hcg_date) ? 'YES /': ''}}
                                                            @php
                                                            $hcgInjectionData = [];
                                                                if(!empty($iui_decription->hcg->injection->data)){
                                                                    $hcgInjection = [1=>'IUI HCG 5000',2=>'IUI HCG 10000',3=>'INJ 2 DECA',4=>'INJ 1 DECA',5=>'INJ Pitocin'] ;
                                                                    array_filter($iui_decription->hcg->injection->data,function($value) use($hcgInjection,&$hcgInjectionData){
                                                                        $hcgInjectionData[$value]=$hcgInjection[$value];
                                                                    });
                                                                }
                                                            @endphp
                                                            {{implode(',',$hcgInjectionData)}}
                                                        </td>
                                                        <td></td>
                                                        <td>{{!empty($iui_decription->no_follicle) ? $iui_decription->no_follicle : ''}}</td>
                                                        <td>{{(!empty($iui_decription->ovalution) && $iui_decription->ovalution == 'yes') ? 'YES' : ''}}</td>
                                                        <td>{{($row->visit == 4 && !empty($iui_decription->result)) ? ($iui_decription->result == 'consive' ? 'Conceived' : 'Fail') : ''}}</td>
                                                    </tr>
                                                    @endif
                                                    @if(!empty($iui_decription->hcg) && ($iui_decription->hcg->iui->status == 'yes'))
                                                        <tr>
                                                            <td>
                                                               
                                                                @if(!empty($iui_decription->hcg->iui->status) && $iui_decription->hcg->iui->status == 'yes' && !empty($iui_decription->hcg_date))
                                                                    @php
                                                                        $cDate = \Carbon\Carbon::parse(!empty($iui_decription->hcg_date) ? $iui_decription->hcg_date : null)->format('d-m-Y') .' '.$iui_decription->hcg->time;
                                                                        $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('d-m-Y');
                                                                    @endphp
                                                                    {{$iuiDtaeAndTime}}
                                                                @endif
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                                    {{!empty($iui_decription->hcg->iui->status) && $iui_decription->hcg->iui->status == 'yes' && !empty($iui_decription->hcg_date) ? 'YES ' : ''}}
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    @if(!$isCycleComplete && $iuiHistoryData[count($iuiHistoryData)-1]['visit'] != 4 && $iuiHistoryData[count($iuiHistoryData)-1]['cycle_status'] != 2)
                                        <div class="">
                                            <div class="row child-no-box">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        HCG
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[hcg][type]",'yes',!empty($hcgType) ? false : true,['id'=>'hcg_yes','class'=>'hcg-type-value'])}}
                                                        <label for="hcg_yes">
                                                            Yes
                                                        </label>
                                                        {{Form::radio("data[hcg][type]",'no',!empty($hcgType) ? true : false,['id'=>'hcg_no','class'=>'hcg-type-value'])}}
                                                        <label for="hcg_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-9"></div>
                                                <div class="{{'col-md-1 hcg-type pr-0 '.$hcgType}}">
                                                    <label class="vertical-form-label pr-0">
                                                        Injection :
                                                    </label>
                                                </div>
                                                <div class="{{'col-md-3 hcg-type '.$hcgType}}">
                                                    <div class="form-group">
                                                        {{Form::select("data[hcg][injection][data][]",[1=>'HCG 5000',2=>'HCG 10000',3=>'INJ 2 DECA',4=>'INJ 1 DECA',5=>'INJ Pitocin'],!empty($historyData->hcg->injection->data) ? $historyData->hcg->injection->data : null,['class'=>'form-control select-padding-0 hsg-injection','multiple','title'=>'Select Injection'])}}
                                                    </div>
                                                    <span class="hsg-injection-type-msg form-error-msg"></span>
                                                </div>
                                                <div class="{{'col-md-1 hcg-type pr-0 '.$hcgType}}">
                                                    <label class="vertical-form-label pr-0">
                                                        I.U.I :
                                                    </label>
                                                </div>
                                                <div class="{{'col-sm-2 hcg-type '.$hcgType}}">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[hcg][iui][status]",'yes',!empty($iuiStatus) ? false : true,['id'=>'hcg_iui_yes','class'=>'iui-yes-no-status','data-type'=>'hcg-iui-type'])}}
                                                        <label for="hcg_iui_yes">
                                                            Yes
                                                        </label>
                                                        {{Form::radio("data[hcg][iui][status]",'no',!empty($iuiStatus) ? true : false,['id'=>'hcg_iui_no','class'=>'iui-yes-no-status','data-type'=>'hcg-iui-type'])}}
                                                        <label for="hcg_iui_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-2 hcg-iui-type '.$iuiStatus.' '.$hcgType}}">
                                                    <div class="form-group">
                                                        {{Form::select("data[hcg][iui][type]",['1'=>'Husband','2'=>'Donar','3'=>'Both'],!empty($historyData->hcg->iui->type) ? $historyData->hcg->iui->type : null,['class'=>'form-control select-padding-0', 'id'=>'hcg-iui-type', 'title'=>'Select Type'])}}
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-2 col-sm-12 hcg-type pr-0 '.$hcgType}}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Date : &nbsp;</span>
                                                        {{Form::text("data[hcg_date]",!empty($historyData->hcg_date) ? \Carbon\Carbon::parse($historyData->hcg_date)->format('D d M Y') : \Carbon\Carbon::now()->format('D d M Y'),['class'=>'form-control datetimepicker hcg-date'])}}
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-2 col-sm-12 hcg-type pr-0 '.$hcgType}}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">HCG Time : &nbsp;</span>
                                                        {{ Form::text('data[hcg][time]', !empty($historyData->hcg->time) ? \Carbon\Carbon::parse($historyData->hcg->time)->format('g:i a') : \Carbon\Carbon::now()->format('g:i a'), ['class'=>'form-control timepicker time hcg-time'])}}
                                                    </div>
                                                    <span class="hcg_time form-error-msg"></span>
                                                </div>
                                            </div>
                                            @php
                                                $ovalution = 'no';
                                                if(!empty($historyData->ovalution) && $historyData->ovalution == 'yes'){
                                                    $ovalution = 'yes';
                                                }
                                                $hcgIuiDate =  $ovalution == 'yes' || !empty($historyData->follow_up) ? '' : 'hcg-iui-date';
                                            @endphp
                                            <div class="row child-no-box">
                                                <div class="col-md-1">
                                                    <label class="vertical-form-label pr-0">
                                                        No.Follicle :
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{Form::number("data[no_follicle]",!empty($historyData->no_follicle) ? $historyData->no_follicle : null,['class'=>'form-control follicle', $ovalution == 'yes' ? 'disabled' : null])}}
                                                        @if($ovalution == 'yes')
                                                            {{Form::hidden('data[no_follicle]',!empty($historyData->no_follicle) ? $historyData->no_follicle : null)}}
                                                            {{Form::hidden('data[ovalution]',!empty($historyData->ovalution) ? $historyData->ovalution : null)}}
                                                            {{Form::hidden('data[follow_up]',!empty($historyData->follow_up) ? \Carbon\Carbon::parse($historyData->follow_up)->format('D d M Y') : null)}}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Ovalution :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[ovalution]",'yes', $ovalution == 'yes' ? true : false,['class'=>'ovalution-type','id'=>'ovalution_yes','disabled'])}}
                                                        <label for="ovalution_yes">
                                                            Yes
                                                        </label>
                                                        {{Form::radio("data[ovalution]",'no',!empty($historyData->ovalution) && $historyData->ovalution == 'no' ? true : false,['class'=>'ovalution-type','id'=>'ovalution_no',$ovalution == 'yes' ? 'disabled' : null])}}
                                                        <label for="ovalution_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <label class="vertical-form-label">
                                                        Weight :
                                                    </label>
                                                </div>
                                                <div class="col-md-3 ">
                                                    <div class="form-group">
                                                        {{Form::text('data[weight]','',['class'=>'form-control weight','placeholder'=>'Enter Weight'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row child-no-box">
                                                
                                                @php
                                                    $pStatus = !empty($historyData->p_s->type) && $historyData->p_s->type == 'yes' ? '' : 'd-none';
                                                @endphp
                                                <div class="col-md-1 pr-0">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[p_s][type]','yes',!empty($pStatus) ? false : true,['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                                                        <label for="ps_status">
                                                            P/S :
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-4 ps-details'}}">
                                                    <div class="form-group">
                                                        {{Form::text("data[p_s][details]",!empty($historyData->p_s->details) ? $historyData->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                                                    </div>
                                                </div>
                                                @if(!$iuiHistoryId)
                                                    <div class="col-md-1">
                                                        <label class="vertical-form-label pr-0">
                                                            Inducing Date :
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            {{Form::text("data[inducing][date]",'',['class'=>'form-control datetime second-visit-inducing','placeholder'=>'Inducing Agent Date','autocomplete'=>'off'])}}
                                                        </div>
                                                    </div>
                                                    {{Form::hidden('data[last_appointment_date]',!empty($lastAppointmentData->date) ? $lastAppointmentData->date : null)}}
                                                @endif
                                                <div class="col-md-1">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('data[le][vitals_status]','yes',!empty($historyData->le->vitals_status) && $historyData->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'vitals_status','data-id'=>'vitals_status_data'])}}
                                                        <label for="vitals_status">
                                                            Vitals
                                                        </label>
                                                    </div>
                                                </div>
                                            
                                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                                        {{Form::text("data[le][bp]",!empty($historyData->le->bp) ? $historyData->le->bp : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">MMHG</span>
                                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                                        {{Form::text("data[le][pulse]",!empty($historyData->le->pulse) ? $historyData->le->pulse : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">/ Min</span>
                                                <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                                        {{Form::text("data[le][temp]",!empty($historyData->le->temp) ? $historyData->le->temp : null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row child-no-box">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        I.V.F :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[ivf]",'yes', false,[
                                                            'class'=>'iui-yes-no-status ivf-transfer',
                                                            'id'=>'ivf_status_yes_third',
                                                            'data-type'=>'ivf-plans'
                                                        ])}}
                                                        <label for="ivf_status_yes_third">
                                                            Yes
                                                        </label>
                                                        {{Form::radio("data[ivf]",'no',true,[
                                                            'class'=>'iui-yes-no-status ivf-transfer',
                                                            'id'=>'ivf_status_no_third',
                                                            'data-type'=>'ivf-plans'
                                                        ])}}
                                                        <label for="ivf_status_no_third">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-5 ivf-plans d-none'}}">
                                                    <div class="form-group">
                                                        {{Form::select("data[ivf_plan]",['1'=>'Self','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'],null,['class'=>'form-control select-padding-0 ivf-transfer-plan','placeholder'=>'select IVF Plan'])}}
                                                    </div>
                                                    <span class="form-error-msg ivf-plans-msg"></span>
                                                </div>
                                            </div>
                                            <div class="row child-no-box">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Blood Report :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[blood_report][type]",'yes','',['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                                        <label for="blood_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("data[blood_report][type]",'no',false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                                        <label for="blood_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-8 pr-0 blood-type d-none'}}">
                                                    <div class="data-blood-images"></div>
                                                </div>
                                            </div>
                                            <div class="row child-no-box">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                       USG Report :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[usg][type]",'yes','',['id'=>'usg_type_yes','class'=>'usg-type iui-yes-no-status','data-type'=>'usg-type'])}}
                                                        <label for="usg_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("data[usg][type]",'no',false,['id'=>'usg_type_no','class'=>'usg-type iui-yes-no-status','data-type'=>'usg-type'])}}
                                                        <label for="usg_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-8 pr-0 usg-type d-none'}}">
                                                    <div class="data-usg-images"></div>
                                                </div>
                                            </div>
                                        
                                            <div class="row child-no-box">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        HSA Report :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[hsa_report][type]",'yes','',['id'=>'hsa_type_yes','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                                                        <label for="hsa_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("data[hsa_report][type]",'no',false,['id'=>'hsa_type_no','class'=>'hsa-type iui-yes-no-status','data-type'=>'hsa-type'])}}
                                                        <label for="hsa_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-8 pr-0 hsa-type d-none'}}">
                                                    <div class="data-hsa-images"></div>
                                                </div>
                                                
                                            </div>
                                            <div class="row child-no-box">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Naturally Conceive :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("data[naturally_conceive]",'yes','',['id'=>'naturally_conceive_yes','class'=>'naturally_conceive iui-yes-no-status','data-type'=>'naturally_conceive'])}}
                                                        <label for="naturally_conceive_yes">
                                                            Yes
                                                        </label>
                                                        {{Form::radio("data[naturally_conceive]",'no','',['id'=>'naturally_conceive_no','class'=>'naturally_conceive iui-yes-no-status','data-type'=>'naturally_conceive'])}}
                                                        <label for="naturally_conceive_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- <div class="row"> --}}
                                                    <div class="col-md-1 pr-0">
                                                        <label class="vertical-form-label pr-0">
                                                            Skip Cycle :
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("data[skip_cycle]",'yes',false,['id'=>'skip_type_yes','class'=>'iui-yes-no-status skip_cycle','data-type'=>'skip-details'])}}
                                                            <label for="skip_type_yes">
                                                                Yes
                                                            </label>
                                
                                                            {{Form::radio("data[skip_cycle]",'no',true,['id'=>'skip_type_no','class'=>'iui-yes-no-status skip_cycle','data-type'=>'skip-details'])}}
                                                            <label for="skip_type_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="{{'col-md-5 skip-details d-none'}}">
                                                        <div class="form-group">
                                                            {{Form::text("data[skip_reason]",null,['class'=>'form-control skip_reason','placeholder'=>'Skip cycle Reason'])}}
                                                        </div>
                                                    </div>
                                                    <div class="text-danger skip-cycle-error-msg"></div>
                                                </div>
                                                <div class="row child-no-box">
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Other Report : &nbsp;
                                                            </span>
                                                            {{Form::text("data[investigation_extra]",isset($historyData->investigation_extra) && !empty($historyData->investigation_extra) ? $historyData->investigation_extra : null,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    
                                                {{-- </div> --}}
                                                <div class="col-md-1">
                                                    <label class="vertical-form-label pr-0">
                                                        Follow Up :
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        @if(!empty($historyData->follow_up))
                                                            {{Form::hidden('data[new_follow_up]',$historyData->follow_up)}}
                                                        @endif
                                                        {{Form::text("data[follow_up]",!empty($historyData->follow_up) ? \Carbon\Carbon::parse($historyData->follow_up)->format('D d M Y') : \Carbon\Carbon::now()->addHours(35)->format('D d M Y'),['class'=>'form-control datetimepicker follow-up-date next-date '.$hcgIuiDate,$ovalution == 'yes' || !empty($historyData->follow_up) ? 'disabled' : null])}}
                                                        {{Form::hidden('is_notAvailable',0,['class'=>'is-notAvailable'])}}
                                                    </div>
                                                    <span class="follow-date-msg form-error-msg"></span>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        {{Form::text("data[follow_up_date_diff]",'',['class'=>'form-control next-day','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                                                        {{-- {{Form::hidden('appointment_date',$lastAppointment->date,['class'=>'last-appointment-date'])}} --}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-3 history-lmp-date">Day</span>
                                                <div class="col-md-12">
                                                    
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        {{Form::textarea("data[pt_remark]",'',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'2'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tfoot>
                        </table>
                        @if($skipValue == 1) 
                            @php
                                    $visitDate = \Carbon\Carbon::parse($iuiHistoryData[count($iuiHistoryData)-1]['created_at'])->format('d-m-Y');
                                    $diff = \Carbon\Carbon::parse(!empty($lmpDate) ? $lmpDate : $iuiHistoryData[count($iuiHistoryData)-1]['created_at'])->diffInDays(\Carbon\Carbon::parse($visitDate));
                                    $diff = $diff + 1;
                            @endphp
                            {{-- <div class="col-md-12"> --}}
                                <h5 class=""><u>Skip Cycle:</u></h5>
                                <table class="table follicular-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Follow UP</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$visitDate}}</td>
                                            <td>{{$lastHistoryData->skip_reason}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            {{-- </div> --}}
                        @endif
                        @if(isset($lastHistoryData->naturally_conceive) && $lastHistoryData->naturally_conceive == 'yes' && $iuiHistoryData[count($iuiHistoryData)-1]['cycle_status'] == 2) 
                                <h6><span class="font-"><b>Result : </b>Naturally Conceive</span></h6>
                        @endif
                        <h4 class=""><u>Medicine:</u></h4>
                        <table class="table follicular-table table-bordered">
                            <thead>
                                <tr>
                                    <th class="font-bold"> Date</th>
                                    <th class="font-bold"> Medicine</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($iuiHistoryData as $key=>$row)
                                    @php
                                        $iuiPrevVisit = IuiHistory::where('patients_id',$row->patients_id)->where('created_at','<',$row->created_at)->orderBy('id','DESC')->first();
                                        $data = json_decode($row->description);
                                        
                                        $historyTreatmentView = null;
                                        if(!empty($data->treatment) || !empty($data->old_treatment)){
                                            $historyTreatmentView = !empty($data->treatment) ? $data->treatment : $data->old_treatment;
                                        }
                                    @endphp
                                    @if(!empty($historyTreatmentView) && (!empty($historyTreatmentView->medicinedata[0])))
                                    <tr>
                                        <td>{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                                        <td class="text-justify">
                                            
                                            @if($historyTreatmentView)
                                                @php
                                                    $old_dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4">"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
                                                    $old_medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
                                                    unset($historyTreatmentView->medicinedata);
                                                @endphp
                                                @foreach($historyTreatmentView as $key=>$row)
                                                @php
                                                
                                                    $firstCharacter = substr($row->medicine, 0, 3);
                                                    $notinject = "";
                                                    if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                                        $notinject = "is-inj";
                                                    } 
                                                @endphp
                                                <div class="mb-1">
                                                    <span class="visit-lable"> Medicine : &nbsp</span>
                                                        {{ $row->medicine }}
                                                        @if($notinject != "is-inj")
                                                            | @switch($row->medicine_status)
                                                                    @case('1')
                                                                        જમ્યા પછી
                                                                        @break
                                                                    @case('2')
                                                                        જમ્યા પહેલાં
                                                                        @break
                                                                    @case('3')
                                                                        માસિકની જગ્યાએ મુકવી
                                                                    @break
                                                            @endswitch
                                                        @endif
                                                    @if(!empty($row->dose))
                                                        @if (array_key_exists($row->dose, $old_dose))
                                                            | {{ $old_dose[$row->dose] }}
                                                        @endif
                                                    @endif
                                                    @if (!empty($row->no)) | No : {{ $row->no }} @endif
                                                    @if($notinject != "is-inj")
                                                    @php
                                                        $qty = (!empty($row->quantity)) ? $row->quantity : 0;
                                                        $qty_2 = (!empty($row->quantity_2)) ? $row->quantity_2 : 0;
                                                        $qty_3 = (!empty($row->quantity_3)) ? $row->quantity_3 : 0;
                                                        $qty_4 = (!empty($row->quantity_4)) ? $row->quantity_4 : 0;
                                                    @endphp | Quantity : {{$qty.'-'.$qty_2.'-'.$qty_3.'-'.$qty_4}}
                                                    @endif
                                                    @if($notinject == "is-inj")
                                                        @if (!empty($row->medicine_time))
                                                        |
                                                                {{-- @foreach ($row->medicine_time as $time) --}}
                                                                    {{$old_medicine_time[$row->medicine_time]}}
                                                                {{-- @endforeach --}}
                                                        @endif
                                                    @endif
                                                    @if(isset($row->note) && !empty($row->note))
                                                    | Note: {{$row->note}}
                                                    @endif
                                                </div>
                                                    <br>
                                                @endforeach
                                                
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                            </tbody>
                            @if($iuiHistoryData[count($iuiHistoryData)-1]['visit'] != 4 && $prevAppointmentDate && $iuiHistoryData[count($iuiHistoryData)-1]['cycle_status'] != 2)
                                <tfoot>
                                    <td colspan="8">
                                        <div class="row treatment-data" id="t_data_1">
                                            <div class="col-md-2 pr-2">
                                                <label class="vertical-form-label pr-0">
                                                    Select Medicine :
                                                </label>
                                            </div>
                                            <div class="col-md-8 complain-multi mb-3 medicine-picker">
                                                {{Form::select('data[treatment][medicinedata][]',$medicines,$historyMedicineKey,['id'=>'treatment-medicine','class'=>'form-control medicine','placeholder'=>"Enter medicine name",'data-type'=>'iui-cycle'])}}
                                            </div>
                                        </div>
                                        <div class="page-loader-wrapper medicine-loader d-none">
                                            <div class="loader">
                                                <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                            </div>
                                        </div>
                                        @if(!empty($historyTreatment))
                                            @foreach($historyTreatment as $key=>$row)
                                            {{-- @if(isset($medicines[$row->medicine])) --}}
                                                <?php
                                                $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                $firstCharacter = substr($mId, 0, 3);
                                                $notinject = "";
                                                if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                                                    $notinject = "is-inj";
                                                }
                                                $till_follow_up = (empty($row->no)) ? 'till-follow-up' : '';
                                                ?>
                                                <div class="{{'row mt-2 '.$notinject}}" data-id="{{$mId}}">
                                                    <div class='col-md-2'>
                                                        <div class='input-group'>
                                                            <span class='input-group-addon'>M : </span>
                                                            {{Form::text('data[treatment]['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-1 notinject'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-1 notinject'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-1 notinject'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-1 notinject'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-2 notinject'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-2 isinject'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-2'>
                                                        <div class='form-group'>
                                                            {{Form::select('data[treatment]['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-2'>
                                                        <div class='input-group'>
                                                            <span class='input-group-addon'>Day :</span>
                                                            {{Form::number('data[treatment]['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-4'>
                                                        <div class='form-group'>
                                                            <!-- <span class='input-group-addon'>Day :</span> -->
                                                            {{Form::text('data[treatment]['.$mId.'][note]',isset($row->note) ? $row->note : '',['class'=>'form-control','placeholder'=>'Note'])}}
                                                        </div>
                                                    </div>
                                                    <div class='col-md-1 medicine-data-remove'>
                                                        <span class=""><i class="material-icons">close</i></span>
                                                    </div>
                                                </div>
                                                {{-- @endif --}}
                                            @endforeach
                                        @endif
                                        <div class="medicine-data"></div>
                                        {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}}
                                    </td>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    
                        <div class="col-md-12 d-none">
                            @if(isset($iuiLastVisit) && isset($lastVisitData->follow_up) && !empty($lastVisitData->follow_up))
                                <h4>{{"ફરીવાર ".\Carbon\Carbon::parse($lastVisitData->follow_up)->format('d-m-Y')." તારીખે બતાવવા આવવું."}}</h4>
                            @endif
                        </div>
                    {{-- @endif --}}
                </div>
            </div> {{--end follicular-print--}}
            
            @endif
            @if(($visitNo == 2 || $visitNo == 4 ) || ($visitNo == 3 && $iuiHistoryData[count($iuiHistoryData)-1]['cycle_status'] != 2))
                <div class="col-sm-12 div-btn">
                    {{Form::submit('Submit',['class'=>'btn btn-primary submit'])}}
                    @if($visitNo == 2 || $visitNo == 4)
                        <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                    @endif
                    @if($visitNo == 3)
                        <button type="submit" class="btn btn-primary final-iui submit" value="6">Follicular Study Report</button>
                        <button type="button" class="btn btn-primary follicular-print d-none" value="7">Follicular Study Print</button>
                    @endif
                    <button type="submit" class="btn btn-primary iui-print submit d-none" value="2">IUI Print</button>
                    <button type="button" class="btn btn-primary iui-deposit-print d-none" value="3">IUI Bill</button>
                    <a href="{{URL::to('iui')}}" class="btn btn-default">Cancel</a>
                </div>
            @endif
        {{Form::close()}}
    </div>
    <script type="text/javascript">
        var iuiHistoryId = ''; 
        $('.ho-data-value').selectize({
            create: true,
            sortField: 'text'
        });
        $('.hystroscopy-images').imageUploader({
            imagesInputName: 'investigation[hystroscopy][images]',
        });
        $('.hcg-images').imageUploader({
            imagesInputName: 'investigation[hcg][images]',
        });
        $('.laproscopy-images').imageUploader({
            imagesInputName: 'investigation[laproscopy][images]',
        });
        $('.blood-images').imageUploader({
            imagesInputName: 'investigation[blood_report][image]',
        });
        $('.data-blood-images').imageUploader({
            imagesInputName: 'data[blood_report][image]',
        });
        $('.data-usg-images').imageUploader({
            imagesInputName: 'data[usg][images]',
        });
        $('.data-hsa-images').imageUploader({
            imagesInputName: 'data[hsa_report][images]',
        });
        $(document).on('click', '.add-row', function() {
            addRow();
        });
        $(document).on('click','.upt-type',function(){
            $('.result_fail').prop("checked", false);
            if($(this).val() == 'negative')
            {
                $('.result_fail').prop("checked", true);
            }
            if($(this).val() == 'positive')
            {
                $('.result_conceive').prop("checked", true);
            }
        })
        $(document).ready(function(){
            $('.datetime').multiDatesPicker({
                // minDate: 0
                // dateFormat: 'd/mm/y'
            });
        });
        $(document).on('click','.delete-iui-history',function(){
            iuiHistoryId = $(this).data('id');
            showConfirmMessage(iuiHistoryId);
        });
        function showConfirmMessage(iuiHistoryId) {
            swal({
                title: 'Are you sure?',
                text: 'You want to delete this record',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00cfd1',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false
            }, function () {
                removeIUIHistory(iuiHistoryId);
            });
        }

        function removeIUIHistory(){
            $.ajax({
                url: "{{URL::to('iui/history/delete')}}",
                dataType: 'json',
                method: 'POST',
                data : {
                    _token: '{{ csrf_token() }}',
                    id: iuiHistoryId
                }
            }).done(function(data) {
                if(data.success == false) {
                    swal('Error', data.message, 'error')
                }
                else {
                    swal('Success', data.message, 'success');
                    location.reload(true);
                }

            }).fail(function(error) {
            });
        }
        
    </script>
