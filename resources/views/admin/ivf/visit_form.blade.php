@php
    $ivfData = json_decode($ivf->description);
    $volume = $ivfReport && $ivfReport->volume ? json_decode($ivfReport->volume) : null;
    $sperm_count = $ivfReport && $ivfReport->sperm_count ? json_decode($ivfReport->sperm_count) : null;
    $total_motility = $ivfReport && $ivfReport->total_motility ? json_decode($ivfReport->total_motility) : null;
    $actively = $ivfReport && $ivfReport->actively ? json_decode($ivfReport->actively) : null;
    $sluggishly = $ivfReport && $ivfReport->sluggishly ? json_decode($ivfReport->sluggishly) : null;
    $non_motile = $ivfReport && $ivfReport->non_motile ? json_decode($ivfReport->non_motile) : null;
    $morphology = $ivfReport && $ivfReport->morphology ? json_decode($ivfReport->morphology) : null;
    $pus_cells = $ivfReport && $ivfReport->pus_cells ? json_decode($ivfReport->pus_cells) : null;
    $medicinesValue = !empty($ivfData->medicinedata) ? $ivfData->medicinedata : null;
    $historyMedicineKey = [];
    if(!empty($medicines)){
        $historyMedicineKey = (array)$medicinesValue;
        $historyMedicineKey = array_column($historyMedicineKey,'medicine');
        if(!empty($historyMedicineKey)){
            $historyMedicineKey = array_combine($historyMedicineKey,$historyMedicineKey);
        }
    }
    $checkPrint = 'd-none';
    $simonReportType = 'd-none';
    $protocolDays = $ivf->visit == 2 ? 4 : 2;
    $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
    $injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'Clomiphene Citrate + HMG','8'=>'Clomiphene Citrate + FSH','9'=>'Antagonist'];
    $medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
    $medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day"];
    $abArray = ['1'=>"Normal",'2'=>"Abnormal"];
    $wnlArray = ['1'=>"WNL",'2'=>"Abnormal"];
    $investigationArray = ["1"=>'wife','2'=>'hub'];
    $blood_groupArray = ["1"=>'O+ve','2'=>'O-ve','3'=>'A+ve','4'=>'A-ve','5'=>'B+ve','6'=>'B-ve','7'=>'AB+ve','8'=>'AB-ve'];
@endphp
{{Form::open(['class'=>'form ivf','files'=>'true','id'=>'ivf-form'])}}
    <div class="row">
        <div class="col-md-1">
            <label class="vertical-form-label pr-0">
                Seen By :
            </label>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{Form::select('seen_by',$hospitalDoctor,$ivf->seen_by,['class'=>'form-control seen-by select-padding-0','placeholder'=>'Select Doctor'])}}
            </div>
            <span class="seen-by-error text-danger mb-2"></span>
        </div>
    </div>
    {{Form::hidden('visit',$ivf->visit,['class'=>'visit-no'])}}
    {{Form::hidden('plan_type',$ivf->plan)}}
    {{Form::hidden('cycle_no',$ivf->cycle_no)}}
    {{Form::hidden('ivf_visit_id',$ivf->id,['class'=>'ivf_visit_id'])}}
    {{Form::hidden("data[is_upt]",'no')}}
    {{Form::hidden('patients_id',encrypt($ivf->patients_id),['class'=>'patients-id'])}}
    <h4 class="col-md-2 visit-lable m-0">Visit :<span class="col-md-2 visit-lable-value">{{$ivf->visit}}
        {{-- <span class="plan-text">{{!empty($ivfSecondVisitData->plan) ? $planData[$ivfSecondVisitData->plan] : null}}</span> --}}
    </h4><br>
    @if(!$isTransfer)
        {{Form::hidden('data[is_transfer]','no',['class'=>'is-transfer'])}}
        {{Form::hidden('data[is_transfer_print]','no')}}
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                    @php
                        // $lmpDateValue = !empty($ivfData->lmp->date) ? \Carbon\Carbon::parse($ivfData->lmp->date)->format('Y-m-d') : null;
                        $currentDateValue = Carbon\Carbon::now()->format('Y-m-d');
                        // $lmpDateDiff = $currentDateValue->diffInDays($lmpDateValue);
                        $lmpDateValue = !empty($lmpDate) ? \Carbon\Carbon::parse($lmpDate) : null;
                        // $currentDateValue = Carbon\Carbon::now();
                        $lmpDateDiff = $lmpDateValue->diffInDays($currentDateValue);
                        $lmpDateDiff = $lmpDateDiff + 1;
                    @endphp
                    {{Form::text("data[lmp][date]",!empty($lmpDateValue) ? \Carbon\Carbon::parse($lmpDateValue)->format('D d M Y') : null ,['class'=>'form-control history-lmd-date'])}}
                </div>
                <span class="lmp-date-error form-error-msg"></span>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    {{Form::text("data[lmp][lmp_date_diff]",!empty($lmpDateDiff) ? $lmpDateDiff : null,['class'=>'form-control history-lmd-date-diff','maxlength'=>3,'placeholder'=>'Date Diff'])}}
                </div>
            </div>
            <span class="col-md-1 p-2 history-lmp-date">Day</span>
        </div>
        @php
            $vitlasClass = !empty($ivfData->le->vitals_status) && $ivfData->le->vitals_status == 'yes' ? '' : 'd-none';
        @endphp
        <div class="row">
            <div class="col-md-1">
                <div class="checkbox">
                    {{Form::checkbox('data[le][vitals_status]','yes',!empty($ivfData->le->vitals_status) && $ivfData->le->vitals_status == 'yes' ? true : false,['class'=>'vitals_status','id'=>'vitals_status','data-id'=>'vitals_status_data'])}}
                    <label for="vitals_status">
                        Vitals
                    </label>
                </div>
            </div>
            <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                <div class="input-group">
                    <span class="input-group-addon">B.P : &nbsp;</span>
                    {{Form::text("data[le][bp]",!empty($ivfData->le->bp) ? $ivfData->le->bp : null,['class'=>'form-control'])}}
                </div>
            </div>
            <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">MMHG</span>
            <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                <div class="input-group">
                    <span class="input-group-addon">Temp : &nbsp;</span>
                    {{Form::text("data[le][temp]",!empty($ivfData->le->temp) ? $ivfData->le->temp : null,['class'=>'form-control'])}}
                </div>
            </div>
            <div class="{{'col-md-2 vitals_status_data '.$vitlasClass}}">
                <div class="input-group">
                    <span class="input-group-addon">Pulse : &nbsp;</span>
                    {{Form::text("data[le][pulse]",!empty($ivfData->le->pulse) ? $ivfData->le->pulse : null,['class'=>'form-control'])}}
                </div>
            </div>
            <span class="{{'col-md-1 p-2 vitals_status_data '.$vitlasClass}}">/ Min</span>
        </div>
        {{-- @if($ivf->plan == 1) --}}
            <div class="row">
                <div class="col-md-1">
                    <label class="vertical-form-label pr-0">
                        OE :
                    </label>
                </div>
                <div class="col-sm-2">
                    <div class="radio is-conceived">
                        {{Form::radio("data[oe][oe_type][type]",'tvs',!empty($ivfData->oe->oe_type->type) && $ivfData->oe->oe_type->type == 'tvs' ? true : false,['id'=>'tvs'])}}
                        <label for="tvs">
                            TVS
                        </label>

                        {{Form::radio("data[oe][oe_type][type]",'pa',!empty($ivfData->oe->oe_type->type) && $ivfData->oe->oe_type->type == 'pa' ? true : false,['id'=>'pa'])}}
                        <label for="pa">
                            PA
                        </label>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="vertical-form-label pr-0">
                        Uterus:
                    </label>
                </div>
                @php
                    $utType = 'd-none';
                    if(!empty($ivfData->oe->ut->ut_type) && $ivfData->oe->ut->ut_type == 2){
                        $utType = '';
                    }
                    $collectionData = !empty($ivfData->collection) ? $ivfData->collection : [];
                    $ovaryType = !empty($ivfData->oe->ovary->ovary_type) ? $ivfData->oe->ovary->ovary_type : [];
                    $ovaryLeftType = !empty($ivfData->oe->ovary->left->type) && $ivfData->oe->ovary->left->type == 1 ? 'd-none' : null;
                    $ovaryRightType = !empty($ivfData->oe->ovary->right->type) && $ivfData->oe->ovary->right->type == 1 ? 'd-none' : null;
                @endphp
                <div class="col-md-3">
                    <div class="form-group">
                        {{Form::select("data[oe][ut][ut_type]",['1'=>'Normal','2'=>"Abnormal"],!empty($ivfData->oe->ut->ut_type) ? $ivfData->oe->ut->ut_type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ut-type','placeholder'=>'Select UT Type'])}}
                    </div>
                </div>
                <div class="{{'col-md-3 ut-type '.$utType}}">
                    <div class="form-group">
                        {{Form::text("data[oe][ut][details]",!empty($ivfData->oe->ut->details) ? $ivfData->oe->ut->details : null,['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                    </div>
                </div>
            </div>
            @if($ivf->visit == 2)
                @php
                    $leftDataStatus = in_array('left',$ovaryType) ? '' : 'd-none';
                    $rightDataStatus = in_array('right',$ovaryType) ? '' : 'd-none';
                @endphp
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[oe][ovary][ovary_type][]','right',in_array('right',$ovaryType),['id'=>'oe_right','class'=>'plan-management','data-id'=>'oe-right-details'])}}
                            <label for="oe_right">
                                Right
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-10'}}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::select("data[oe][ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($ivfData->oe->ovary->right->type) ? $ivfData->oe->ovary->right->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                                </div>
                            </div>
                            <div class="{{'col-md-5 complain-multi ovary-right-abnormal-type '.$ovaryRightType}}">
                                {{Form::select("data[oe][ovary][right][details][]",$rightOvaryData,!empty($ivfData->oe->ovary->right->details) ? $ivfData->oe->ovary->right->details : null,[
                                    'class'=>'form-control co-value co_value_data history-oe-ovary-right-details',
                                    'placeholder'=>'Abnormal Details',
                                    'id' => 'oe_ovary_right_details',
                                    'data-id' => '2',
                                    'multiple'=>true
                                ])}}
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">AFCS : &nbsp;</span>
                                    {{Form::text("data[oe][ovary][right][afcs]",!empty($ivfData->oe->ovary->right->afcs) ? $ivfData->oe->ovary->right->afcs : null,['class'=>'form-control edit-right-ovary-data-text'])}}
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0)" class="edit-right-ovary-data overy-popup" data-class='edit-right-ovary-data'>Keyboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="{{'row '.$ovaryRightType}}">
                    <div class="col-md-4"></div>
                    <div class="col-md-7 complain-multi ovary-right-abnormal-type ml-5">
                        <div class="row edit_oe_ovary_right_details">
                            @if (isset($ivfData->oe->ovary->right->updated_details) && !empty($ivfData->oe->ovary->right->updated_details))
                                @foreach ($ivfData->oe->ovary->right->updated_details as $key => $value)
                                    <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$ivfData->oe->ovary->right->details[$key]) . '_right' }}">
                                        {{Form::text('data[oe][ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                            'class' => 'form-control edited_oe_ovary_right_details',
                                            'id' => preg_replace('/[^a-zA-Z0-9]/','_',$ivfData->oe->ovary->right->details[$key])
                                        ])}}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Ovary:
                        </label>
                    </div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[oe][ovary][ovary_type][]','left',in_array('left',$ovaryType),['id'=>'oe_left','class'=>'plan-management','data-id'=>'oe-left-details'])}}
                            <label for="oe_left">
                                Left
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-10'}}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::select("data[oe][ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($ivfData->oe->ovary->left->type) ? $ivfData->oe->ovary->left->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                                </div>
                            </div>
                            <div class="{{'col-md-5 complain-multi ovary-left-abnormal-type '.$ovaryLeftType}} ">
                                {{Form::select("data[oe][ovary][left][details][]",$leftOvaryData,!empty($ivfData->oe->ovary->left->details) ? $ivfData->oe->ovary->left->details : null,[
                                    'class'=>'form-control co-value co_value_data history-oe-ovary-left-details',
                                    'placeholder'=>'Abnormal Details',
                                    'id' => 'oe_ovary_left_details',
                                    'multiple'=>true,
                                    'data-id' => '2',
                                ])}}
                            </div>
                            <div class='col-md-3'>
                                <div class="input-group">
                                    <span class="input-group-addon">AFCS : &nbsp;</span>
                                    {{Form::text("data[oe][ovary][left][afcs]",!empty($ivfData->oe->ovary->left->afcs) ? $ivfData->oe->ovary->left->afcs : null,['class'=>'form-control edit-left-ovary-data-text'])}}
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0)" class="edit-left-ovary-data overy-popup" data-class='edit-left-ovary-data'>Keyboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="{{'row '.$ovaryLeftType}}">
                    <div class="col-md-4"></div>
                    <div class="col-md-7 complain-multi ovary-left-abnormal-type ml-5">
                        <div class="row edit_oe_ovary_left_details">
                            @if(isset($ivfData->oe->ovary->left->updated_details) && !empty($ivfData->oe->ovary->left->updated_details))
                                @foreach ($ivfData->oe->ovary->left->updated_details as $key => $value)
                                    <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$ivfData->oe->ovary->left->details[$key]) . '_left' }}">
                                        {{Form::text('data[oe][ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                            'class' => 'form-control edited_oe_ovary_left_details',
                                            'id' => preg_replace('/[^a-zA-Z0-9]/','_',$ivfData->oe->ovary->left->details[$key])
                                        ])}}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            @else
                <div class="row">
                    <div class="col-md-1">
                        <label class="vertical-form-label pr-0">
                            Ovary :
                        </label>
                    </div>
                    @php
                        $ovaryType = !empty($ivfData->ovary->ovary_status) ? $ivfData->ovary->ovary_status : [];
                        $iuiStatus = !empty($ivfData->hcg->iui->status) && $ivfData->hcg->iui->status == 'yes' ? '' : 'd-none';
                        $hcgType = !empty($ivfData->hcg->type) && $ivfData->hcg->type == 'yes' ? '' : 'd-none';
                        $oeLeftType = in_array('left',$ovaryType) ? '' : 'd-none';
                        $oerightType = in_array('right',$ovaryType) ? '' : 'd-none';
                    @endphp
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[ovary][ovary_status][]','right',in_array('right',$ovaryType),['id'=>'oe_right','class'=>'plan-management','data-id'=>'right-data'])}}
                            <label for="oe_right">
                                Right
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3'}}">
                        <div class="form-group">
                            {{Form::text("data[ovary][ovary_type][right][details]",!empty($ivfData->ovary->ovary_type->right->details) ? $ivfData->ovary->ovary_type->right->details : null,['class'=>'form-control third-edit-right-ovary-data-text','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <a href="javascript:void(0)" class="third-edit-right-ovary-data overy-popup" data-class='third-edit-right-ovary-data'>Keyboard</a>
                    </div>
                    <div class="col-md-1">
                        <div class="checkbox">
                            {{Form::checkbox('data[ovary][ovary_status][]','left',in_array('left',$ovaryType),['id'=>'oe_left_data','class'=>'plan-management','data-id'=>'left-data'])}}
                            <label for="oe_left_data">
                                Left
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-3'}}">
                        <div class="form-group">
                            {{Form::text("data[ovary][ovary_type][left][details]",!empty($ivfData->ovary->ovary_type->left->details) ? $ivfData->ovary->ovary_type->left->details : null,['class'=>'form-control third-edit-left-ovary-data-text','placeholder'=>'Details'])}}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <a href="javascript:void(0)" class="third-edit-left-ovary-data overy-popup" data-class='third-edit-left-ovary-data'>Keyboard</a>
                    </div>
                </div>
                <br/>
            @endif

            <div class="row">
                <div class="col-md-2 pr-0">
                    <label class="vertical-form-label pr-0">
                        Endometrial Cavity :
                    </label>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{Form::text("data[oe][endometrial_cavity][cavity]",!empty($ivfData->oe->endometrial_cavity->cavity) ? $ivfData->oe->endometrial_cavity->cavity : null,['class'=>'form-control','placeholder'=>'Endometrial Cavity Details'])}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Size : &nbsp;</span>
                        {{Form::text("data[oe][endometrial_cavity][size]",!empty($ivfData->oe->endometrial_cavity->size) ? $ivfData->oe->endometrial_cavity->size : null,['class'=>'form-control'])}}
                    </div>
                </div>
                <span class="col-md-1 p-2">MM</span>
            </div>
            <br>
            @if($ivf->visit == 2)
                @php
                    $pStatus = !empty($ivfData->p_s->type) && $ivfData->p_s->type == 'yes' ? '' : 'd-none';
                @endphp
                {{-- <div class="row">
                    <div class="col-md-1 pr-0">
                        <div class="checkbox">
                            {{Form::checkbox('data[p_s][type]','yes',!empty($pStatus) ? false : true,['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                            <label for="ps_status">
                                P/S :
                            </label>
                        </div>
                    </div>
                    <div class="{{'col-md-4 ps-details '.$pStatus}}">
                        <div class="form-group">
                            {{Form::text("data[p_s][details]",!empty($ivfData->p_s->details) ? $ivfData->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                        </div>
                    </div>
                </div> --}}
            @endif
        {{-- @endif --}}
        {{-- ivf comman form --}}
        @php
            $semenFreezingValueData = ($ivf->plan == 3) && ($semenFreezing != 1) ? '' : 'd-none';
            $embroyReadyValueData = ($ivf->plan == 3 || $ivfData->plan == 4) && ($embroyReady != 1) ? '' : 'd-none';
        @endphp
        <div class="row mt-1">
            {{-- @if(($ivf->plan == 3) && (!isset($ivfData->collected->frozen->type) || empty($ivfData->collected->frozen->type) || $ivfData->collected->frozen->type != 'yes')) --}}
                <div class="{{'col-md-2 '.$semenFreezingValueData}}">
                    <label class="vertical-form-label pr-0">
                        Semen Freezing :
                    </label>
                </div>
                <div class="{{'col-md-1 '.$semenFreezingValueData}}">
                    <div class="radio is-conceived">
                        {{Form::radio("data[collected][frozen][type]",'yes',!empty($semenFreezing) && $semenFreezing == 'yes' ? true : false,['id'=>'progesteroneyes'])}}
                        <label for="progesteroneyes">
                            Yes
                        </label>
                        {{Form::radio("data[collected][frozen][type]",'no',!empty($embroyReady) && $embroyReady == 'no' ? true : false,['id'=>'progesteroneno'])}}
                        <label for="progesteroneno">
                            No
                        </label>
                    </div>
                </div>
                <div class="{{'col-md-2 '.$embroyReadyValueData}}">
                    <label class="vertical-form-label pr-0">
                        Embroy Readyy :
                    </label>
                </div>
                <div class="{{'col-md-2 '.$embroyReadyValueData}}">
                    <div class="radio is-conceived">
                        {{Form::radio("data[collected][report][embroy][type]",'yes',!empty($ivfData->collected->report->embroy->type) && $ivfData->collected->report->embroy->type == 'yes' ? true : false,['id'=>'embroyyes'])}}
                        <label for="embroyyes">
                            Yes
                        </label>
                        {{Form::radio("data[collected][report][embroy][type]",'no',!empty($ivfData->collected->report->embroy->type) && $ivfData->collected->report->embroy->type == 'no' ? true : false,['id'=>'embroyno'])}}
                        <label for="embroyno">
                            No
                        </label>
                    </div>
                </div>
            {{-- @endif --}}
        </div>
        @if($ivf->plan == 1)
            <div class="row mt-3 mb-3">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            S.E2 :
                        </label>
                    </div>
                    <div class="col-md-2">
                        {{Form::text("data[s_e2]",isset($ivfData->s_e2) &&!empty($ivfData->s_e2) ? $ivfData->s_e2 : '',['class'=>'form-control','placeholder'=>'S.E2'])}}
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            S.LH :
                        </label>
                    </div>
                    <div class="col-md-2">
                        {{Form::text("data[s_lh]",isset($ivfData->s_lh) &&!empty($ivfData->s_lh) ? $ivfData->s_lh : '',['class'=>'form-control','placeholder'=>'S.LH'])}}
                    </div>
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            S.P2 :
                        </label>
                    </div>
                    <div class="col-md-2">
                        {{Form::text("data[s_p2]",isset($ivfData->s_p2) &&!empty($ivfData->s_p2) ? $ivfData->s_p2 : '',['class'=>'form-control','placeholder'=>'S.P2'])}}
                    </div>
            </div>
            <div class="row">
                <label class="vertical-form-label pr-0">
                    Hystroscopy During Pickup :
                </label>
                <div class="col-sm-2">
                    <div class="radio is-conceived">
                        {{Form::radio("data[during_pickup]",'yes','',['id'=>'during_pickup_yes','class'=>'during-pickup'])}}
                        <label for="during_pickup_yes">
                            Yes
                        </label>

                        {{Form::radio("data[during_pickup]",'no','',['id'=>'during_pickup_no','class'=>'during-pickup'])}}
                        <label for="during_pickup_no">
                            No
                        </label>
                    </div>
                </div>
            </div>
        @endif
        @php
            $hystroscopy = !empty($ivf->investigation) && isset(json_decode($ivf->investigation)->hystroscopy) ? json_decode($ivf->investigation)->hystroscopy : null;
            $hystroscopy_detail = !empty($hystroscopy->type) && $hystroscopy->type == 'yes' ? '':'d-none';
            $laproscopy = !empty($ivf->investigation) && isset(json_decode($ivf->investigation)->laproscopy) ? json_decode($ivf->investigation)->laproscopy : null;
            $laproscopy_detail = !empty($laproscopy->type) && $laproscopy->type == 'yes' ? '':'d-none';
            $bloodStatus = in_array('blood',$collectionData) ? '' : 'd-none';
            $usgStatus = in_array('usg',$collectionData) ? '' : 'd-none';
        @endphp
        <div class="row mt-1">
            <div class="col-md-1 pr-0">
                <label class="vertical-form-label pr-0">
                    Hystroscopy :
                </label>
            </div>
            
            <div class="col-sm-2">
                <div class="radio is-conceived">
                    {{Form::radio("investigation[hystroscopy][type]",'yes',!empty($hystroscopy->type) && $hystroscopy->type == 'yes' ? true : false,['id'=>'hystroscopy_type_yes','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                    <label for="hystroscopy_type_yes">
                        Yes
                    </label>
                    {{Form::radio("investigation[hystroscopy][type]",'no',!empty($hystroscopy->type) && $hystroscopy->type == 'no' ? true : false,['id'=>'hystroscopy_type_no','class'=>'hystroscopy-type iui-yes-no-status','data-type'=>'hystroscopy-type'])}}
                    <label for="hystroscopy_type_no">
                        No
                    </label>
                </div>
            </div>
            <div class="{{'col-md-6 hystroscopy-type '.$hystroscopy_detail}}">
                <div class="edit-hystroscopy-images"></div>
                {{-- <div class="row">
                    <div class="col-md-4">
                        {{Form::file('investigation[hystroscopy][images][]',['class'=>'form-control hystroscopy-file',"multiple"=>'true'])}}
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-1 pr-0">
                <label class="vertical-form-label pr-0">
                    Laproscopy :
                </label>
            </div>
            
            <div class="col-sm-2">
                <div class="radio is-conceived">
                    {{Form::radio("investigation[laproscopy][type]",'yes',!empty($laproscopy->type) && $laproscopy->type == 'yes' ? true : false,['id'=>'laproscopy_type_yes','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                    <label for="laproscopy_type_yes">
                        Yes
                    </label>
                    {{Form::radio("investigation[laproscopy][type]",'no',!empty($laproscopy->type) && $laproscopy->type == 'no' ? true : false,['id'=>'laproscopy_type_no','class'=>'laproscopy-type iui-yes-no-status','data-type'=>'laproscopy-type'])}}
                    <label for="laproscopy_type_no">
                        No
                    </label>
                </div>
            </div>
            <div class="{{'col-md-6 laproscopy-type '.$laproscopy_detail}}">
                <div class="edit-laproscopy-images"></div>
                {{-- <div class="row">
                    <div class="col-md-4">
                        {{Form::file('investigation[laproscopy][images][]',['class'=>'form-control laproscopy-file',"multiple"=>'true'])}}
                    </div>
                </div> --}}
            </div>
        </div>
        
        @if($ivf->visit != 2)
            <div class="row mt-1">
                <div class="col-md-2 pr-0">
                    <label class="vertical-form-label pr-0">
                        ET :
                    </label>
                </div>
                <div class="col-md-4">
                    {{Form::text("data[et_details]",!empty($ivfData->et_details) ? $ivfData->et_details : null,['class'=>'form-control','placeholder'=>'Enter ET Details'])}}
                </div>
            </div>
            @php
                $collectedStatus = in_array('collected',$collectionData) ? '' : 'd-none';
                $transferStatus = in_array('transfer',$collectionData) ? '' : 'd-none';
                $triggerStatus = in_array('trigger',$collectionData) ? '' : 'd-none';
                $progesteroneStatus = in_array('progesterone',$collectionData) ? '' : 'd-none';
                $bloodStatus = in_array('blood',$collectionData) ? '' : 'd-none';
                $checkPrint = !empty($ivfData->progesterone->type) && empty($progesteroneStatus) ?  '' : 'd-none';
                $embroyStatus = !empty($ivfData->collected->report->embroy->status) && $ivfData->collected->report->embroy->status == 'embroy' ? '' : 'd-none';
                $embroyType = !empty($ivfData->collected->report->embroy->type) && $ivfData->collected->report->embroy->type == 'yes' ? '' : 'd-none';
                $progesteroneType = !empty($ivfData->progesterone->status) && $ivfData->progesterone->status == 'yes' ? '' : 'd-none';
            @endphp
            
            {{Form::hidden('is_trigger','no')}}
            
            <div class="{{'row embroy-button '.$simonReportType}}">
                {{Form::hidden('ivf_report_id',!empty($ivfReport->id) ? encrypt($ivfReport->id) : null)}}
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-addon">Reason : &nbsp;</span>
                        {{Form::text('reason',!empty($ivfReport->reason) ? $ivfReport->reason : null ,['class'=>'form-control reason'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Date : &nbsp;</span>
                        {{Form::text("report_date",!empty($ivfReport->date) ? \Carbon\Carbon::parse($ivfReport->date)->format('D d M Y') : null,['class'=>'form-control datetimepicker report_date','required'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('report_date')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Volume : &nbsp;</span>
                        {{Form::number('volume[pre]',!empty($volume->pre) ? $volume->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('volume[post]',!empty($volume->post) ? $volume->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Sperm Count/ml : &nbsp;</span>
                        {{Form::number('sperm[pre]',!empty($sperm_count->pre) ? $sperm_count->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('sperm[post]',!empty($sperm_count->post) ? $sperm_count->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Total Motility (%): &nbsp;</span>
                        {{Form::number('motility[pre]',!empty($total_motility->pre) ? $total_motility->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('motility[post]',!empty($total_motility->post) ? $total_motility->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Actively Motile (%) : &nbsp;</span>
                        {{Form::number('actively[pre]',!empty($actively->pre) ? $actively->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('actively[post]',!empty($actively->post) ? $actively->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Sliggishly Motile (%) : &nbsp;</span>
                        {{Form::number('sluggishly[pre]',!empty($sluggishly->pre) ? $sluggishly->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('sluggishly[post]',!empty($sluggishly->post) ? $sluggishly->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Non-motile (%) : &nbsp;</span>
                        {{Form::number('motile[pre]',!empty($non_motile->pre) ? $non_motile->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('motile[post]',!empty($non_motile->post) ? $non_motile->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Normal Morphology : &nbsp;</span>
                        {{Form::number('morphology[pre]',!empty($morphology->pre) ? $morphology->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('morphology[post]',!empty($morphology->post) ? $morphology->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="{{'row embroy-button '.$simonReportType}}">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="input-group">
                        <span class="input-group-addon col-md-2">Pus cells/hpf : &nbsp;</span>
                        {{Form::number('cells[pre]',!empty($pus_cells->pre) ? $pus_cells->pre : null,['class'=>'form-control name col-md-3','placeholder' => 'Pre-wash'])}}
                        {{Form::number('cells[post]',!empty($pus_cells->post) ? $pus_cells->post : null,['class'=>'form-control name col-md-3','placeholder' => 'Post-wash'])}}
                    </div>
                    <span class="form-error-msg">
                        {{$errors->first('name')}}
                    </span>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-2">
                    <div class="checkbox">
                        {{Form::checkbox('data[collection][]','progesterone',!empty($progesteroneStatus) ? false : true,['id'=>'progesterone'])}}
                        <label for="progesterone">
                            Progesterone supplementation?
                        </label>
                    </div>
                </div>
                @if($ivf->plan == 1)
                    <div class="{{'col-md-2 progesterone_data '.$progesteroneStatus}}">
                        <label for="progesterone">
                            Same Cycle Transfer?
                        </label>
                    </div>
                    <div class="{{'col-md-2 progesterone_data '.$progesteroneStatus}}">
                        <div class="radio is-conceived">
                            {{Form::radio("data[progesterone][status]",'yes',!empty($progesteroneType) ? false : true,['id'=>'progesterone_yes'])}}
                            <label for="progesterone_yes">
                                Yes
                            </label>
                            {{Form::radio("data[progesterone][status]",'no',!empty($progesteroneType) ? true : false,['id'=>'progesterone_no'])}}
                            <label for="progesterone_no">
                                No
                            </label>
                        </div>
                    </div>
                @endif
                @php
                    $class= 'progesterone_data';
                    $pTypeValue = 1;
                    if($ivf->plan == 1){
                        $class= 'progesterone_yes';
                        $pTypeValue = 0;
                    }
                    $progesterone_date = !empty($ivfData->progesterone->type) ? '' : 'd-none';
                @endphp
                <div class="{{'col-md-2 '.$class.' '.$progesteroneStatus}}">
                    {{Form::hidden('progesterone_status','yes')}}
                    <div class="radio is-conceived">
                        {{Form::radio("data[progesterone][type]",'day_3',!empty($ivfData->progesterone->type) && $ivfData->progesterone->type == 'day_3' ? true : false ,['id'=>'day_3','class'=>'progesterone-type'])}}
                        <label for="day_3">
                            Day-3
                        </label>
                        {{Form::radio("data[progesterone][type]",'day_5',!empty($ivfData->progesterone->type) && $ivfData->progesterone->type == 'day_5' ? true : false ,['id'=>'day_5','class'=>'progesterone-type'])}}
                        <label for="day_5">
                            Day-5
                        </label>
                    </div>
                </div>
                <div class="{{'col-md-2 progesterone_date_div ' .$progesterone_date}}">
                    <div class="form-group">
                            {{Form::text("data[progesterone_date]", !empty($ivfData->progesterone_date) ? $ivfData->progesterone_date: '',['class'=>'form-control datetimepicker progesterone_date'])}}
                    </div>
                </div>
            </div>
            {{-- @if(!empty($ivfData->trigger->hcg->status) || !empty($ivfData->trigger->decapeptyl->status) || !empty($ivfData->trigger->dualtrigger->stauts))
                @php
                    $trigger = 'trigger';
                    $hcg = !empty($ivfData->trigger->hcg->status) ? $ivfData->trigger->hcg->status : null;
                    $hcg_time = !empty($ivfData->trigger->hcg->time) ? $ivfData->trigger->hcg->time : null;
                    $hcg_dose = !empty($ivfData->trigger->hcg->dose) ? $ivfData->trigger->hcg->dose : null;
                    $hcg_brand = !empty($ivfData->trigger->hcg->brand) ? $ivfData->trigger->hcg->brand : null;
                    $decapeptyl = !empty($ivfData->trigger->decapeptyl->status) ? $ivfData->trigger->decapeptyl->status : null;
                    $decapeptyl_time = !empty($ivfData->trigger->decapeptyl->time) ? $ivfData->trigger->decapeptyl->time : null;
                    $decapeptyl_dose = !empty($ivfData->trigger->decapeptyl->dose) ? $ivfData->trigger->decapeptyl->dose : null;
                    $decapeptyl_brand = !empty($ivfData->trigger->decapeptyl->brand) ? $ivfData->trigger->decapeptyl->brand : null;
                    $dule = !empty($ivfData->trigger->dualtrigger->stauts) ? $ivfData->trigger->dualtrigger->stauts : null;
                @endphp
                {{Form::hidden("data[collection][]",$trigger)}}
                {{Form::hidden("data[trigger][hcg][status]",$hcg)}}
                {{Form::hidden("data[trigger][hcg][time]",$hcg_time)}}
                {{Form::hidden("data[trigger][hcg][dose]",$hcg_dose)}}
                {{Form::hidden("data[trigger][hcg][brand]",$hcg_brand)}}
                {{Form::hidden("data[trigger][decapeptyl][status]",$decapeptyl)}}
                {{Form::hidden("data[trigger][decapeptyl][time]",$decapeptyl_time)}}
                {{Form::hidden("data[trigger][decapeptyl][dose]",$decapeptyl_dose)}}
                {{Form::hidden("data[trigger][decapeptyl][brand]",$decapeptyl_brand)}}
                {{Form::hidden("data[trigger][dualtrigger][stauts]",$dule)}}
                {{Form::hidden("data[trigger][update_status]",'yes')}}
            @else --}}
                @php
                    $hcgStatus = !empty($ivfData->trigger->hcg->status ) && $ivfData->trigger->hcg->status == 'hcg' ? '' : 'd-none';
                    $decapeptylStatus = !empty($ivfData->trigger->decapeptyl->status) && $ivfData->trigger->decapeptyl->status == 'decapeptyl' ? '' : 'd-none';
                @endphp
                @if($ivf->plan == 1)
                    {{Form::hidden("data[trigger][update_status]",'no')}}
                    <div class="row mt-1">
                        <div class="col-md-2">
                            <div class="checkbox">
                                {{Form::checkbox('data[collection][]','trigger',!empty($triggerStatus) ? false : true ,['id'=>'trigger'])}}
                                <label for="trigger">
                                    Trigger
                                </label>
                            </div>
                        </div>
                        <div class="{{'col-md-3 trigger '.$triggerStatus}}">
                            <div class="input-group">
                                <span class="input-group-addon">Trigger Date: &nbsp;</span>
                                {{Form::text("data[trigger_date]", !empty($ivfData->trigger_date) ? \Carbon\Carbon::parse($ivfData->trigger_date)->format('D d M Y') : '', ['class'=>'form-control history-lmd-date'])}}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="{{'trigger ml-3 '.$triggerStatus}}">
                    <div class="row mt-1">
                        <div class="col-md-2">
                            <div class="checkbox">
                                {{Form::checkbox('data[trigger][hcg][status]','hcg',!empty($hcgStatus) ? false : true,['id'=>'hcg'])}}
                                <label for="hcg">
                                    HCG
                                </label>
                            </div>
                        </div>
                        <div class="{{'hcgtrigger '.$hcgStatus}}">
                            <div class="row ml-3">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Time : &nbsp;</span>
                                        {{Form::text("data[trigger][hcg][time]",!empty($ivfData->trigger->hcg->time) ? $ivfData->trigger->hcg->time : null,['class'=>'form-control timepicker time','id'=>'hcg_time','placeholsder'=>'Brand'])}}
                                    </div>
                                </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon">Dose : &nbsp;</span>
                                    {{Form::text("data[trigger][hcg][dose]",!empty($ivfData->trigger->hcg->dose) ? $ivfData->trigger->hcg->dose : null,['class'=>'form-control'])}}
                                </div>
                            </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Brand : &nbsp;</span>
                                        {{Form::text("data[trigger][hcg][brand]",!empty($ivfData->trigger->hcg->brand) ? $ivfData->trigger->hcg->brand : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-2">
                            <div class="checkbox">
                                {{Form::checkbox('data[trigger][decapeptyl][status]','decapeptyl',!empty($decapeptylStatus) ? false : true,['id'=>'decapeptyl'])}}
                                <label for="decapeptyl">
                                    Decapeptyl
                                </label>
                            </div>
                        </div>
                        <div class="{{'decapeptyltrigger '.$decapeptylStatus}}">
                            <div class="row ml-3">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Time : &nbsp;</span>
                                            {{Form::text("data[trigger][decapeptyl][time]",!empty($ivfData->trigger->decapeptyl->time) ? $ivfData->trigger->decapeptyl->time : null,['class'=>'form-control timepicker time','placeholsder'=>'Brand'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Dose : &nbsp;</span>
                                        {{Form::text("data[trigger][decapeptyl][dose]",!empty($ivfData->trigger->decapeptyl->dose) ? $ivfData->trigger->decapeptyl->dose : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Brand : &nbsp;</span>
                                        {{Form::text("data[trigger][decapeptyl][brand]",!empty($ivfData->trigger->decapeptyl->brand) ? $ivfData->trigger->decapeptyl->brand : null,['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-2">
                            <div class="checkbox">
                                {{Form::checkbox('data[trigger][dualtrigger][stauts]','dualtrigger',!empty($ivfData->trigger->dualtrigger->stauts) && $ivfData->trigger->dualtrigger->stauts == 'dualtrigger' ? true : false ,['id'=>'dualtrigger'])}}
                                <label for="dualtrigger">
                                    Dule Trigger
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @endif --}}
            
            @if($ivf->plan != 3 )
                {{-- <div class="row mt-1">
                    <div class="col-md-2">
                        <div class="checkbox">
                            {{Form::checkbox('data[collection][]','usg',in_array('usg',$collectionData),['id'=>'usg'])}}
                            <label for="usg">
                            USG
                            </label>
                        </div>
                    </div>
                </div> --}}
            @endif
            @php
                $pStatus = !empty($ivfData->p_s->type) && $ivfData->p_s->type == 'yes' ? '' : 'd-none';
            @endphp
            <div class="row">
                <div class="col-md-1 pr-0">
                    <div class="checkbox">
                        {{Form::checkbox('data[p_s][type]','yes',!empty($pStatus) ? false : true,['id'=>'ps_status','class'=>'ps-status','data-type'=>'ps-details'])}}
                        <label for="ps_status">
                            P/S :
                        </label>
                    </div>
                </div>
                <div class="{{'col-md-4 ps-details '.$pStatus}}">
                    <div class="form-group">
                        {{Form::text("data[p_s][details]",!empty($ivfData->p_s->details) ? $ivfData->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                    </div>
                </div>
            </div>
            @if($ivf->plan != 1 && in_array('transfer',$collectionData))
                {{Form::hidden('data[collection][]','transfer')}}
                {{Form::hidden('data[transfer][payment]',!empty($ivfData->transfer->payment) ? $ivfData->transfer->payment : null)}}
                {{Form::hidden('data[transfer][method]',!empty($ivfData->transfer->method) ? $ivfData->transfer->method : null)}}
            @endif
        @endif
        <div class="row mt-1">
            <div class="col-md-2">
                <div class="checkbox">
                    {{Form::checkbox('data[collection][]','blood',!empty($bloodStatus) ? false : true,['id'=>'blood'])}}
                    <label for="blood">
                        Blood Report
                    </label>
                </div>
            </div>
            <div class="{{'col-md-8 bloodreport '.$bloodStatus}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">Blood report: &nbsp;</span>
                            {{Form::text("data[blood_report][report]",!empty($ivfData->blood_report->report) ? $ivfData->blood_report->report : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="edit-blood-images"></div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-2">
                <div class="checkbox">
                    {{Form::checkbox('data[collection][]','usg',!empty($usgStatus) ? false : true,['id'=>'usg'])}}
                    <label for="usg">
                        USG Report
                    </label>
                </div>
            </div>
            <div class="{{'col-md-8 usgreport '.$usgStatus}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">USG report: &nbsp;</span>
                            {{Form::text("data[usg][report]",!empty($ivfData->usg->report) ? $ivfData->usg->report : null,['class'=>'form-control'])}}
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="edit-usg-images"></div>

                    </div>
                </div>
            </div>
        </div>
        
        {{-- end ivf comman form --}}
        <br>
        <div class="row">
            <div class="col-md-1 pr-0">
                <label class="vertical-form-label pr-0">
                    C/O :
                </label>
            </div>
            <div class="col-md-9 complain-multi">
                {{Form::select('data[co_type][]',$complaints,!empty($ivfData->co_type) ? $ivfData->co_type : null,['class'=>'form-control co-value co_value_data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'0'])}}
                <span class="form-error-msg co-value-msg">
                    {{$errors->first('since')}}
                </span>
            </div>
        </div>
        <br>
        {{-- pre operative data --}}
            @if(($ivf->plan != 2 || $ivf->plan != 3 || $ivf->plan != 4) && $ivf->visit == 2)
            
                @foreach($investigationArray as $key => $value)
                    @php
                        $patientsInvestigation = !empty($ivf->investigation) && isset(json_decode($ivf->investigation)->$value) ? json_decode($ivf->investigation)->$value : null;
                        $investigationValue = !empty($patientsInvestigation->investigation_details) ? (array)$patientsInvestigation->investigation_details : [];
                        
                        $cbcWnlStatus = !empty($patientsInvestigation->investigation_cbc_mp_details->status) && $patientsInvestigation->investigation_cbc_mp_details->status == '2' ? '' : 'd-none';
                        $urinWnlStatus = !empty($patientsInvestigation->investigation_urine_value_details->status) && $patientsInvestigation->investigation_urine_value_details->status == '2' ? '' : 'd-none';
                        $tshWnlStatus = !empty($patientsInvestigation->investigation_tsh_value_details->status) && $patientsInvestigation->investigation_tsh_value_details->status == '2' ? '' : 'd-none';
                    @endphp
                    <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-primary investigation">
                            <div class="panel-heading" role="tab" id="headingThree_1">
                                <h4 class="panel-title"> <a class="" role="button" data-toggle="collapse" data-parent="#investigation{{$key}}" href="#investigation{{$key}}" aria-expanded="true"
                                                            aria-controls="investigation">Investigation - {{($value == 'hub' ? 'Husband' : 'Wife')}}</a></h4>
                            </div>
                            <div id="investigation{{$key}}" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
                                <div class="panel-body">
                                <div class="row anc-profile">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','1',(!empty($patientsInvestigation)) ? checkData(1,$patientsInvestigation) : null,['id'=>$key.'cbc_mp','class'=>'plan-management','data-id'=>$key.'cbc-mp-details'])}}
                                            <label for="{{$key}}cbc_mp">
                                                CBC / MP
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'cbc-mp-details '.((!empty($patientsInvestigation) && checkData(1,$patientsInvestigation)) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][1]",(!empty($patientsInvestigation)) && !empty($investigationValue[1]) ? $investigationValue[1] : null,['class'=>'form-control','placeholder'=>'CBC MP Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','2',(!empty($patientsInvestigation)) ? checkData(2,$patientsInvestigation) : null,['id'=>$key.'fbs','class'=>'plan-management','data-id'=>$key.'fbs-data-details'])}}
                                            <label for="{{$key}}fbs">
                                                FBS
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'fbs-data-details '.(!empty($patientsInvestigation) && checkData(2,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][2]",(!empty($patientsInvestigation)) && !empty($investigationValue[2]) ? $investigationValue[2] : null,['class'=>'form-control','placeholder'=>'FBS Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="{{'row '.$key.'cbc-mp-details '.((!empty($patientsInvestigation)) && checkData(1,$patientsInvestigation) ? '' : 'd-none')}}">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{Form::select("investigation[".$value."][investigation_cbc_mp_details][status]",$wnlArray,(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_cbc_mp_details->status) ? $patientsInvestigation->investigation_cbc_mp_details->status : null,['class'=>'form-control select-padding-0 investigation-type cbc-mb-type','data-id'=>$key.'cbc-mb-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-md-3 '.$key.'cbc-mb-type-details-value '.$cbcWnlStatus}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Aneamia : &nbsp;
                                            </span>
                                            {{Form::text("investigation[".$value."][investigation_cbc_mp_details][aneamia]",(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_cbc_mp_details->aneamia) ? $patientsInvestigation->investigation_cbc_mp_details->aneamia : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-md-3 '.$key.'cbc-mb-type-details-value '.$cbcWnlStatus}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Leacocytosis : &nbsp;
                                            </span>
                                            {{Form::text("investigation[".$value."][investigation_cbc_mp_details][leacocytosis]",(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_cbc_mp_details->leacocytosis) ? $patientsInvestigation->investigation_cbc_mp_details->leacocytosis : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','3',(!empty($patientsInvestigation)) ? checkData(3,$patientsInvestigation) : null,['id'=>$key.'urine_r','class'=>'plan-management','data-id'=>$key.'urine-details'])}}
                                            <label for="{{$key}}urine_r">
                                                Urine - R
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'urine-details '.((!empty($patientsInvestigation)) && checkData(3,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][3]",(!empty($patientsInvestigation)) && !empty($investigationValue[3]) ? $investigationValue[3] : null,['class'=>'form-control','placeholder'=>'Urine Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','4',(!empty($patientsInvestigation)) ? checkData(4,$patientsInvestigation) : null,['id'=>$key.'ppbs','class'=>'plan-management','data-id'=>$key.'ppbs-data-details'])}}
                                            <label for="{{$key}}ppbs">
                                                PPBS
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'ppbs-data-details '.((!empty($patientsInvestigation)) && checkData(4,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][4]",(!empty($patientsInvestigation)) && !empty($investigationValue[4]) ? $investigationValue[4] : null,['class'=>'form-control','placeholder'=>'PPBS Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="{{'row '.$key.'urine-details '.((!empty($patientsInvestigation)) && checkData(3,$patientsInvestigation) ? '' : 'd-none')}}">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{Form::select("investigation[".$value."][investigation_urine_value_details][status]",$wnlArray,(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_urine_value_details->status) ? $patientsInvestigation->investigation_urine_value_details->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>$key.'urine-details-value','placeholder'=>'Select CBC MB Type'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-md-3 '.$key.'urine-details-value '.$urinWnlStatus}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Aneamia : &nbsp;
                                            </span>
                                            {{Form::text("investigation[".$value."][investigation_urine_value_details][aneamia]",(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_urine_value_details->aneamia) ? $patientsInvestigation->investigation_urine_value_details->aneamia : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-md-3 '.$key.'urine-details-value '.$urinWnlStatus}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Leacocytosis : &nbsp;
                                            </span>
                                            {{Form::text("investigation[".$value."][investigation_urine_value_details][leacocytosis]",(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_urine_value_details->leacocytosis) ? $patientsInvestigation->investigation_urine_value_details->leacocytosis : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','5',(!empty($patientsInvestigation)) ? checkData(5,$patientsInvestigation) : null,['id'=>$key.'esr','class'=>'plan-management','data-id'=>$key.'esr-details'])}}
                                            <label for="{{$key}}esr">
                                                ESR
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'esr-details '.((!empty($patientsInvestigation)) && checkData(5,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][5]",(!empty($patientsInvestigation)) && !empty($investigationValue[5]) ? $investigationValue[5] : null,['class'=>'form-control','placeholder'=>'ESR Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','6',(!empty($patientsInvestigation)) ? checkData(6,$patientsInvestigation) : null,['id'=>$key.'rbs','class'=>'plan-management','data-id'=>$key.'rbs-data-details'])}}
                                            <label for="{{$key}}rbs">
                                                RBS
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'rbs-data-details '.((!empty($patientsInvestigation)) && checkData(6,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][6]",(!empty($patientsInvestigation)) && !empty($investigationValue[6]) ? $investigationValue[6] : null,['class'=>'form-control','placeholder'=>'RBS Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','7',(!empty($patientsInvestigation)) ? checkData(7,$patientsInvestigation) : null,['id'=>$key.'sgpt','class'=>'plan-management','data-id'=>$key.'sgpt-details'])}}
                                            <label for="{{$key}}sgpt">
                                                SGPT
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'sgpt-details '.((!empty($patientsInvestigation)) && checkData(7,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][7]",(!empty($patientsInvestigation)) && !empty($investigationValue[7]) ? $investigationValue[7] : null,['class'=>'form-control','placeholder'=>'SGPT Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','8',(!empty($patientsInvestigation)) ? checkData(8,$patientsInvestigation) : null,['id'=>$key.'hbsag','class'=>'plan-management','data-id'=>$key.'hbsag-details'])}}
                                            <label for="{{$key}}hbsag">
                                                HBsAg
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'hbsag-details '.((!empty($patientsInvestigation)) && checkData(8,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            <div class="radio is-conceived">
                                                {{Form::radio("investigation[".$value."][investigation_details][8]",'positive',(!empty($patientsInvestigation)) && !empty($investigationValue[12]) && ($investigationValue[12] == 'positive') ? true : false,['id'=>$key.'positive','class'=>'hbsag-pickup'])}}
                                                <label for="{{$key.'positive'}}">
                                                Positive
                                                </label>
                                                {{Form::radio("investigation[".$value."][investigation_details][8]",'negative',(!empty($patientsInvestigation)) && !empty($investigationValue[12]) && ($investigationValue[12] == 'negative') ? true : false,['id'=>$key.'negative','class'=>'hbsag-pickup'])}}
                                                <label for="{{$key.'negative'}}">
                                                    Negative
                                                </label>
                                            </div>
                                            {{-- {{Form::text("investigation[".$value."][investigation_details][8]",(!empty($patientsInvestigation)) && !empty($investigationValue[8]) ? $investigationValue[8] : null,['class'=>'form-control','placeholder'=>'HBsAg Details'])}} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','9',(!empty($patientsInvestigation)) ? checkData(9,$patientsInvestigation) : null,['id'=>$key.'screatinine','class'=>'plan-management','data-id'=>$key.'screatinine-details'])}}
                                            <label for="{{$key}}screatinine">
                                                S.Creatinine
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'screatinine-details '.((!empty($patientsInvestigation)) && checkData(9,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][9]",(!empty($patientsInvestigation)) && !empty($investigationValue[9]) ? $investigationValue[9] : null,['class'=>'form-control','placeholder'=>'S. Creatinine Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','10',(!empty($patientsInvestigation)) ? checkData(10,$patientsInvestigation) : null,['id'=>$key.'hiv','class'=>'plan-management','data-id'=>$key.'hiv-details'])}}
                                            <label for="{{$key}}hiv">
                                                HIV
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'hiv-details '.((!empty($patientsInvestigation)) && checkData(10,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            <div class="radio is-conceived">
                                                {{Form::radio("investigation[".$value."][investigation_details][10]",'positive',(!empty($patientsInvestigation)) && !empty($investigationValue[12]) && ($investigationValue[12] == 'positive') ? true : false,['id'=>$key.'positive','class'=>'hbsag-pickup'])}}
                                                <label for="{{$key.'positive'}}">
                                                Positive
                                                </label>
                                                {{Form::radio("investigation[".$value."][investigation_details][10]",'negative',(!empty($patientsInvestigation)) && !empty($investigationValue[12]) && ($investigationValue[12] == 'negative') ? true : false,['id'=>$key.'negative','class'=>'hbsag-pickup'])}}
                                                <label for="{{$key.'negative'}}">
                                                    Negative
                                                </label>
                                            </div>
                                            {{-- {{Form::text("investigation[".$value."][investigation_details][10]",(!empty($patientsInvestigation)) && !empty($investigationValue[10]) ? $investigationValue[10] : null,['class'=>'form-control','placeholder'=>'HIV Details'])}} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','11',(!empty($patientsInvestigation)) ? checkData(11,$patientsInvestigation) : null,['id'=>$key.'crp','class'=>'plan-management','data-id'=>$key.'crp-details'])}}
                                            <label for="{{$key}}crp">
                                                CRP
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'crp-details '.((!empty($patientsInvestigation)) && checkData(11,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][11]",(!empty($patientsInvestigation)) && !empty($investigationValue[11]) ? $investigationValue[11] : null,['class'=>'form-control','placeholder'=>'CRP Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','12',(!empty($patientsInvestigation)) ? checkData(12,$patientsInvestigation) : null,['id'=>$key.'blood_group','class'=>'plan-management','data-id'=>$key.'blood-details'])}}
                                            <label for="{{$key}}blood_group">
                                                Blood Group
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-5 '.$key.'blood-details '.((!empty($patientsInvestigation)) && checkData(12,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            <div class="radio is-conceived">
                                                @foreach($blood_groupArray as $index => $blood_name)
                                                    {{Form::radio("investigation[".$value."][investigation_details][12]",$blood_name,(!empty($patientsInvestigation)) && !empty($investigationValue[12]) && ($investigationValue[12] == $blood_name) ? true : false,['id'=>$key.$blood_name,'class'=>'during-pickup'])}}
                                                    <label for="{{$key.$blood_name}}">
                                                       {{$blood_name}}
                                                    </label>
                                                @endforeach
                                            </div>
                                            {{-- {{Form::text("investigation[".$value."][investigation_details][12]",(!empty($patientsInvestigation)) && !empty($investigationValue[12]) ? $investigationValue[12] : null,['class'=>'form-control','placeholder'=>'Blood Group Details'])}} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','13',(!empty($patientsInvestigation)) ? checkData(13,$patientsInvestigation) : null,['id'=>$key.'slied','class'=>'plan-management','data-id'=>$key.'slied-details'])}}
                                            <label for="{{$key}}slied">
                                                Serum Widal
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'slied-details '.((!empty($patientsInvestigation)) && checkData(13,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][13]",(!empty($patientsInvestigation)) && !empty($investigationValue[13]) ? $investigationValue[13] : null,['class'=>'form-control','placeholder'=>'Slide Widal Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','14',(!empty($patientsInvestigation)) ? checkData(14,$patientsInvestigation) : null,['id'=>$key.'tsh','class'=>'plan-management','data-id'=>$key.'tsh-data-details'])}}
                                            <label for="{{$key}}tsh">
                                                TSH
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'tsh-data-details '.((!empty($patientsInvestigation)) && checkData(14,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][14]",(!empty($patientsInvestigation)) && !empty($investigationValue[14]) ? $investigationValue[14] : null,['class'=>'form-control','placeholder'=>'TSH Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="{{'row '.$key.'tsh-data-details '.((!empty($patientsInvestigation)) && checkData(14,$patientsInvestigation) ? '' : 'd-none')}}">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{Form::select("investigation[".$value."][investigation_tsh_value_details][status]",$wnlArray,(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_tsh_value_details->status) ? $patientsInvestigation->investigation_tsh_value_details->status : null,['class'=>'form-control select-padding-0 investigation-type','data-id'=>$key.'tsh-type-details-value','placeholder'=>'Select CBC MB Type'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-md-3 '.$key.'tsh-type-details-value '.$tshWnlStatus}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Aneamia : &nbsp;
                                            </span>
                                            {{Form::text("investigation[".$value."][investigation_tsh_value_details][aneamia]",(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_tsh_value_details->aneamia) ? $patientsInvestigation->investigation_tsh_value_details->aneamia : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="{{'col-md-3 '.$key.'tsh-type-details-value '.$tshWnlStatus}}">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Leacocytosis : &nbsp;
                                            </span>
                                            {{Form::text("investigation[".$value."][investigation_tsh_value_details][leacocytosis]",(!empty($patientsInvestigation)) && !empty($patientsInvestigation->investigation_tsh_value_details->leacocytosis) ? $patientsInvestigation->investigation_tsh_value_details->leacocytosis : null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','15',(!empty($patientsInvestigation)) ? checkData(15,$patientsInvestigation) : null,['id'=>$key.'typhidot','class'=>'plan-management','data-id'=>$key.'typhidot-lgm-details'])}}
                                            <label for="{{$key}}typhidot">
                                                Typhidot lgM
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'typhidot-lgm-details '.((!empty($patientsInvestigation)) && checkData(15,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][15]",(!empty($patientsInvestigation)) && !empty($investigationValue[15]) ? $investigationValue[15] : null,['class'=>'form-control','placeholder'=>'Typhidot lgM Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','16',(!empty($patientsInvestigation)) ? checkData(16,$patientsInvestigation) : null,['id'=>$key.'t3','class'=>'plan-management','data-id'=>$key.'t3-details'])}}
                                            <label for="{{$key}}t3">
                                                T3, T4, TSH
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'t3-details '.((!empty($patientsInvestigation)) && checkData(16,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][16]",(!empty($patientsInvestigation)) && !empty($investigationValue[16]) ? $investigationValue[16] : null,['class'=>'form-control','placeholder'=>'T3, T4, TSH Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','17',(!empty($patientsInvestigation)) ? checkData(17,$patientsInvestigation) : null,['id'=>$key.'lipid_profile','class'=>'plan-management','data-id'=>$key.'lipid-profile-details'])}}
                                            <label for="{{$key}}lipid_profile">
                                                Lipid Profile
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'lipid-profile-details '.((!empty($patientsInvestigation)) && checkData(17,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][17]",(!empty($patientsInvestigation)) && !empty($investigationValue[17]) ? $investigationValue[17] : null,['class'=>'form-control','placeholder'=>'Lipid Profile Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','18',(!empty($patientsInvestigation)) ? checkData(18,$patientsInvestigation) : null,['id'=>$key.'vitb12','class'=>'plan-management','data-id'=>$key.'vit-b12-details'])}}
                                            <label for="{{$key}}vitb12">
                                                Vit B-12
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'vit-b12-details '.((!empty($patientsInvestigation)) && checkData(18,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][18]",(!empty($patientsInvestigation)) && !empty($investigationValue[18]) ? $investigationValue[18] : null,['class'=>'form-control','placeholder'=>'Vit B-12 Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','19',(!empty($patientsInvestigation)) ? checkData(19,$patientsInvestigation) : null,['id'=>$key.'tube-widal','class'=>'plan-management','data-id'=>$key.'tube-widal-details'])}}
                                            <label for="{{$key}}tube-widal">
                                                Tube Widal
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'tube-widal-details '.((!empty($patientsInvestigation)) && checkData(19,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][19]",(!empty($patientsInvestigation)) && !empty($investigationValue[19]) ? $investigationValue[19] : null,['class'=>'form-control','placeholder'=>'Tube Widal Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','20',(!empty($patientsInvestigation)) ? checkData(20,$patientsInvestigation) : null,['id'=>$key.'vitd3','class'=>'plan-management','data-id'=>$key.'vit-d3-details'])}}
                                            <label for="{{$key}}vitd3">
                                                Vit D-3
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'vit-d3-details '.((!empty($patientsInvestigation)) && checkData(20,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][20]",(!empty($patientsInvestigation)) && !empty($investigationValue[20]) ? $investigationValue[20] : null,['class'=>'form-control','placeholder'=>'Vit D-3 Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','21',(!empty($patientsInvestigation)) ? checkData(21,$patientsInvestigation) : null,['id'=>$key.'lft','class'=>'plan-management','data-id'=>$key.'lft-details'])}}
                                            <label for="{{$key}}lft">
                                                LFT
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'lft-details '.((!empty($patientsInvestigation)) && checkData(21,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][21]",(!empty($patientsInvestigation)) && !empty($investigationValue[21]) ? $investigationValue[21] : null,['class'=>'form-control','placeholder'=>'LFT Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','22',(!empty($patientsInvestigation)) && (!empty($patientsInvestigation)) ? checkData(22,$patientsInvestigation) : null,['id'=>$key.'anc_profile','class'=>'plan-management','data-id'=>$key.'anc-profile-details'])}}
                                            <label for="{{$key}}anc_profile">
                                                ANC Profile
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'anc-profile-details '.((!empty($patientsInvestigation)) && checkData(22,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][22]",(!empty($patientsInvestigation)) && !empty($investigationValue[22]) ? $investigationValue[22] : null,['class'=>'form-control','placeholder'=>'ANC Profile Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','23',(!empty($patientsInvestigation)) ? checkData(23,$patientsInvestigation) : null,['id'=>$key.'rft','class'=>'plan-management','data-id'=>$key.'rft-details'])}}
                                            <label for="{{$key}}rft">
                                                RFT
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'rft-details '.((!empty($patientsInvestigation)) && checkData(23,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][23]",(!empty($patientsInvestigation)) && !empty($investigationValue[23]) ? $investigationValue[23] : null,['class'=>'form-control','placeholder'=>'RFT Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','24',(!empty($patientsInvestigation)) ? checkData(24,$patientsInvestigation) : null,['id'=>$key.'pre_major','class'=>'plan-management','data-id'=>$key.'pre-major-profile-details'])}}
                                            <label for="{{$key}}pre_major">
                                                Pre oper.Profile(Major)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'pre-major-profile-details '.((!empty($patientsInvestigation)) && checkData(24,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][24]",(!empty($patientsInvestigation)) && !empty($investigationValue[24]) ? $investigationValue[24] : null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Major) Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','25',(!empty($patientsInvestigation)) ? checkData(25,$patientsInvestigation) : null,['id'=>$key.'scalcium','class'=>'plan-management','data-id'=>$key.'scalcium-details'])}}
                                            <label for="{{$key}}scalcium">
                                                S.Calcium
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'scalcium-details '.((!empty($patientsInvestigation)) && checkData(25,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][25]",(!empty($patientsInvestigation)) && !empty($investigationValue[25]) ? $investigationValue[25] : null,['class'=>'form-control','placeholder'=>'S. Calcium Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','26',(!empty($patientsInvestigation)) ? checkData(26,$patientsInvestigation) : null,['id'=>$key.'pre_minor','class'=>'plan-management','data-id'=>$key.'pre-minor-profile-details'])}}
                                            <label for="{{$key}}pre_minor">
                                                Pre oper.Profile(Minor)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'pre-minor-profile-details '.((!empty($patientsInvestigation)) && checkData(26,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][26]",(!empty($patientsInvestigation)) && !empty($investigationValue[26]) ? $investigationValue[26] : null,['class'=>'form-control','placeholder'=>'Pre oper.Profile(Minor) Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','27',(!empty($patientsInvestigation)) ? checkData(27,$patientsInvestigation) : null,['id'=>$key.'eletrolytes','class'=>'plan-management','data-id'=>$key.'eletrolytes-details'])}}
                                            <label for="{{$key}}eletrolytes">
                                                S.Eletrolytes
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'eletrolytes-details '.((!empty($patientsInvestigation)) && checkData(27,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][27]",(!empty($patientsInvestigation)) && !empty($investigationValue[27]) ? $investigationValue[27] : null,['class'=>'form-control','placeholder'=>'S. Eletrolytes Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','28',(!empty($patientsInvestigation)) ? checkData(28,$patientsInvestigation) : null,['id'=>$key.'denue_duo','class'=>'plan-management','data-id'=>$key.'denue-duo-details'])}}
                                            <label for="{{$key}}denue_duo">
                                                Dengue Duo
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'denue-duo-details '.((!empty($patientsInvestigation)) && checkData(28,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][28]",(!empty($patientsInvestigation)) && !empty($investigationValue[28]) ? $investigationValue[28] : null,['class'=>'form-control','placeholder'=>'Dengue Duo Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','29',(!empty($patientsInvestigation)) ? checkData(29,$patientsInvestigation) : null,['id'=>$key.'billirubin','class'=>'plan-management','data-id'=>$key.'billirubin-details'])}}
                                            <label for="{{$key}}billirubin">
                                                S.Billirubin
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'billirubin-details '.((!empty($patientsInvestigation)) && checkData(29,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][29]",(!empty($patientsInvestigation)) && !empty($investigationValue[29]) ? $investigationValue[29] : null,['class'=>'form-control','placeholder'=>'S. billirubin Details'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','30',(!empty($patientsInvestigation)) ? checkData(30,$patientsInvestigation) : null,['id'=>$key.'denue_ns1','class'=>'plan-management','data-id'=>$key.'denue-ns1-details'])}}
                                            <label for="{{$key}}denue_ns1">
                                                Dengue NS1
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'denue-ns1-details '.((!empty($patientsInvestigation)) && checkData(30,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][30]",(!empty($patientsInvestigation)) && !empty($investigationValue[30]) ? $investigationValue[30] : null,['class'=>'form-control','placeholder'=>'Dengue NS1 Details'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox">
                                            {{Form::checkbox('investigation['.$value.'][investigation_data][]','31',(!empty($patientsInvestigation)) ? checkData(31,$patientsInvestigation) : null,['id'=>$key.'hb','class'=>'plan-management','data-id'=>$key.'hb-data-details'])}}
                                            <label for="{{$key}}hb">
                                                HB
                                            </label>
                                        </div>
                                    </div>
                                    <div class="{{'col-md-4 '.$key.'hb-data-details '.((!empty($patientsInvestigation)) && checkData(31,$patientsInvestigation) ? '' : 'd-none')}}">
                                        <div class="form-group">
                                            {{Form::text("investigation[".$value."][investigation_details][31]",(!empty($patientsInvestigation)) && !empty($investigationValue[31]) ? $investigationValue[31] : null,['class'=>'form-control','placeholder'=>'HB Details'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                @endforeach
               
            @endif
        {{-- @endif --}}
        {{-- end pre operative data --}}
        
        
        <div class="pt-3 pb-3 pl-2"> Treatment </div>
        <div id="treatment" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body" id="parent">
                <div class="row treatment-data" id="t_data_1">
                    <div class="col-md-2 pr-0">
                        <label class="vertical-form-label pr-0">
                            Select Medicine :
                        </label>
                    </div>

                    <div class="col-md-9 complain-multi medicine-picker">
                        {{Form::select("treatment[medicinedata][]",$medicines,$historyMedicineKey,['id'=>'treatment-medicine','class'=>'form-control co-value medicines-data','placeholder'=>'Enter Medicine'])}}
                    </div>
                </div>
                <div class="page-loader-wrapper medicine-loader d-none">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
                
                @if(!empty($ivfData->medicinedata))
                    @foreach($ivfData->medicinedata as $key=>$row)
                    <?php
                    $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                    $firstCharacter = substr($mId, 0, 3);
                    $notinject = "";
                    if($firstCharacter=="inj" || $firstCharacter=="INJ") {
                        $notinject = "is-inj";
                        $dose =  ['' => 'Select Dose','1'=>'Daily','2'=>"Once a week",'3'=>"Twice a week",'4'=>"Stat",'5'=>"SOS",'6'=>"Alternate Day",'7'=>"6 hourly",'8'=>"8 hourly",'9'=>"12 hourly",'10'=>"24 hourly"];

                    }
                    $till_follow_up = (empty($row->no)) ? 'till-follow-up' : '';
                    ?>
                    <div class="{{'row mt-2 '.$notinject}}" data-id="{{$mId}}">
                        <div class='col-md-2'>
                            <div class='input-group'>
                                <span class='input-group-addon'>M : </span>
                                {{Form::text('data[medicinedata]['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                            </div>
                        </div>
                        <div class='col-md-1 notinject'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-1 notinject'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-1 notinject'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-1 notinject'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-2 notinject'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-2 isinject'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-2'>
                            <div class='form-group'>
                                {{Form::select('data[medicinedata]['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class='col-md-1'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Day :</span>
                                {{Form::number('data[medicinedata]['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                            </div>
                        </div>
                        <div class='col-md-1 medicine-data-remove'>
                            <span class=""><i class="material-icons">close</i></span>
                        </div>
                    </div>
                    {{-- @endif --}}
                        {{-- <div class='row' data-id="{{$mId}}"> --}}
                            {{-- <div class='col-md-3'>
                                <div class='form-group'>
                                    {{Form::select('data[medicinedata]['.$mId.'][medicine_time][]',["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>"Night"],!empty($row->medicine_time) ? $row->medicine_time : null,['class'=>'form-control select-padding-0 dose','multiple'=>'true','title'=>'Select Medicine Time'])}}
                                </div>
                            </div> --}}
                            {{-- <div class='col-md-3'>
                                <div class='form-group'>
                                    {{Form::select('data[medicinedata]['.$mId.'][medicine_time][]',[""=>"Select Medicine route","1"=>"IV","2"=>"IM","3"=>"SC","4"=>'Oral',"5"=>'P/V',"6"=>"P/A"],!empty($row->medicine_time) ? $row->medicine_time : null,['class'=>'form-control select-padding-0 dose','title'=>'Select Medicine Time'])}}
                                </div>
                            </div>
                            <div class='col-md-1 medicine-data-remove'><span class=''><i class='material-icons'>close</i></span></div> --}}
                        {{-- </div> --}}
                    @endforeach
                @endif
                <div class="treatment-medicine-data"></div>
                {{Form::hidden('old_medicine_data',!empty($historyMedicineKey) ? implode(',',$historyMedicineKey) : null,['class'=>'old-medicine-data'])}}
            </div>
        </div>
        @if($ivf->plan == 1 && !empty($ivfData->protocol))
            <br>
            <a class="btn btn-primary btn-icon btn-icon-mini btn-round add-row" data-id="5" data-day="{{$protocolDays}}"><i class="material-icons">add</i></a>
            {{-- table append for protocol --}}
            <div class="protocol-table">
                <table class='table m-b-0'>
                    <thead>
                        <tr>
                            <th>Cycle Day</th>
                            <th>Simulation<br> Day</th>
                            <th>Date</th>
                            <th>Injecion</th>
                            <th>HMG</th>
                            <th>HMG Brand Name</th>
                            <th>FSH</th>
                            <th>FSH Brand Name</th>
                            <th>Antagonist</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody class='protocol-data-row'>
                        @foreach($ivfData->protocol as $key=>$row)
                            @if(!empty($row->day) || !empty($row->date) || !empty($row->injection) || !empty($row->hmg) || !empty($row->hmg_brand_name) || !empty($row->fsh) || !empty($row->fsh_brand_name) || !empty($row->antagonist))
                                <tr>
                                    <td class='width-80'>
                                        {{Form::text("data[protocol][".$key."][day]",!empty($row->day) ? $row->day : null,['class'=>'form-control'])}}
                                    </td>
                                    <td>
                                        <span class='days-number'>s{{!empty($row->s_day) ? $row->s_day : null}}</span>
                                        {{Form::hidden("data[protocol][".$key."][s_day]",!empty($row->s_day) ? $row->s_day : null,['class'=>'form-control','id'=>'s-days-'.$key])}}
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][date]",!empty($row->date) ? $row->date : null,['class'=>'form-control protocol-date datetimepicker','id'=>'history-lmpdate-'.$key])}}
                                    </td>
                                    <td>
                                        <div class='col-md-8'>
                                            <div class='form-group'>
                                                {{Form::select("data[protocol][".$key."][injection]",["1"=>"Only HMG","2"=>"Only FSH","3"=>"FSH + HMG","4"=>"Lupride","5"=>"Letrozole + HMG","6"=>"Letrozole + FSH","7"=>"Clomiphene Citrate + HMG","8"=>"Clomiphene Citrate + FSH","9"=>"Antagonist"],!empty($row->injection) ? $row->injection : null,['class'=>'form-control width-125 select-padding-0 dose injection-data injection-'.$key,'placeholder'=>'Select Injection'])}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][hmg]",!empty($row->hmg) ? $row->hmg : null,['class'=>'form-control hmg-data hmg-'.$key])}}
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][hmg_brand_name]",!empty($row->hmg_brand_name) ? $row->hmg_brand_name : null,['class'=>'form-control hmg-brand-data hmg-brand-'.$key])}}
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][fsh]",!empty($row->fsh) ? $row->fsh : null,['class'=>'form-control fsh-data fsh-'.$key])}}
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][fsh_brand_name]",!empty($row->fsh_brand_name) ? $row->fsh_brand_name : null,['class'=>'form-control fsh-brand-data fsh-brand-'.$key])}}
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][antagonist]",!empty($row->antagonist) ? $row->antagonist : null,['class'=>'form-control antagonist-data antagonist-'.$key])}}
                                    </td>
                                    <td>
                                        {{Form::text("data[protocol][".$key."][time]",!empty($row->time) ? $row->time : null,['class'=>'form-control timepicker width-80'])}}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <div class="protocol-table"></div> --}}
            <br>
        @endif
        <br>
        <div class="row mt-1">
            <div class="col-md-6">
                <label>Remark:</label>
                <div class="form-group">
                    {{Form::textarea('data[remark]',!empty($ivfData->remark) ? $ivfData->remark : null, ['class' => 'form-control no-resize call-response','placeholder' => 'Remark','rows' => '3'])}}
                </div>
            </div>

            <div class="col-md-6">
                <label>Patient'sRemark:</label>
                <div class="input-group">
                    {{Form::textarea("data[pt_remark]",!empty($ivfData->pt_remark) ? $ivfData->pt_remark : '',['class'=>'form-control no-resize pt_remark','placeholder'=>"Patient's Remark",'rows'=>'3'])}}
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">Follow Up: &nbsp;</span>
                    {{-- @if($ivf->plan != 1)
                        {{Form::text("data[follow_up]",\Carbon\Carbon::now()->addDays(1)->format('D d M Y'),['class'=>'form-control datetimepicker lmp-date-follow-up'])}}
                    @else
                        {{Form::text("data[follow_up]",'',['class'=>'form-control datetimepicker follow-up-date'])}}
                    @endif --}}
                    {{Form::hidden('appointment_date',$ivfData->follow_up,['class'=>'last-appointment-date'])}}
                    {{Form::text("data[follow_up]",\Carbon\Carbon::parse($ivfData->follow_up)->format('D d M Y'),['class'=>'form-control datetimepicker follow-up-date tranfer-follow-date next-date'])}}
                </div>
            </div>
            @if(!empty($ivfData->plan))
                {{Form::hidden('data[plan]',$ivfData->plan)}}
                {{Form::hidden('data[transfer_type]',!empty($ivfData->transfer_type) ? $ivfData->transfer_type : null)}}
            @else
                {{Form::hidden('data[plan]',null)}}
            @endif
            @if(!empty($ivfData->skip_cycle) && $ivfData->skip_cycle == 'yes')
                {{Form::hidden('data[skip_cycle]','yes')}}
                {{Form::hidden('data[skip_reason]',$ivfData->skip_reason)}}
                {{Form::hidden('data[plan]',$ivfData->plan)}}
            @else
                {{Form::hidden('data[plan]',null)}}
            @endif
        </div>
    @else
        {{Form::hidden("data[is_upt]",'yes')}}
        {{Form::hidden('data[is_transfer]','yes',['class'=>'is-transfer'])}}
        {{Form::hidden('data[is_transfer_print]','yes')}}
        <div class="row">
            {{-- upt --}}
            <div class="col-md-1">
                <label class="vertical-form-label pr-0">
                    UPT :
                </label>
            </div>

            <div class="col-sm-2">
                <div class="radio is-conceived">
                    {{Form::radio("data[transfer][upt_type]",'positive','',['id'=>'transfer-positive','class'=>'upt-type'])}}
                    <label for="transfer-positive">
                        Positive
                    </label>

                    {{Form::radio("data[transfer][upt_type]",'negative','',['id'=>'transfer-negative','class'=>'upt-type'])}}
                    <label for="transfer-negative">
                        Negative
                    </label>
                </div>
            </div>
            {{-- result --}}
            <div class="col-md-1">
                <label class="vertical-form-label pr-0">
                    Result :
                </label>
            </div>

            <div class="col-sm-2">
                <div class="radio is-conceived">
                    {{Form::radio("data[transfer][result_type]",'conceive','',['id'=>'transfer-conceive','class'=>'result-type'])}}
                    <label for="transfer-conceive">
                        Conceive
                    </label>

                    {{Form::radio("data[transfer][result_type]",'fail','',['id'=>'transfer-fail','class'=>'result-type'])}}
                    <label for="transfer-fail">
                        Fail
                    </label>
                </div>
            </div>
            {{-- end result --}}
        </div>
        <br>
        <div class="row">
            <div class="col-md-1">
                <label class="vertical-form-label pr-0">
                    Report :
                </label>
            </div>
            <div class="col-md-2">
                {{Form::file('data[transfer][report][]',['class'=>'form-control',"multiple"=>"multiple"])}}
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-addon">Follow Up: &nbsp;</span>
                    {{Form::text("data[transfer][follow_up]",\Carbon\Carbon::now()->addDays(7)->format('D d M Y'),['class'=>'form-control datetimepicker'])}}
                </div>
            </div>
        </div>
        <br>
        <div id="treatment" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="panel-body" id="parent">
                <div class="row treatment-data" id="t_data_1">
                    <div class="col-md-1 pr-0">
                        <label class="vertical-form-label pr-0">
                            Select Medicine :
                        </label>
                    </div>
                    <div class="col-md-9 complain-multi">
                        {{Form::select("treatment[medicinedata][]",$medicines,'',['class'=>'form-control co-value medicine-data co_value_data','placeholder'=>'Enter Medicine','multiple'=>true])}}
                    </div>
                </div>
                <div class="treatment-medicine-data">

                </div>
            </div>
        </div>
        <br>
    @endif
    {{Form::hidden('ivf_history_id', '' , ['id' => 'ivf_history_id'])}}
    {{Form::button('submit',['class'=>'btn btn-primary submit'])}}
    {{Form::hidden('ivf_transfer_report_id', '' , ['id' => 'ivf_transfer_report_id'])}}
    <button type="submit" class="btn btn-primary submit" value="1">Save & Preview</button>
    <button type="submit" class="btn btn-primary submit transfer-report d-none" value="5">Transfer Report Preview</button>
    <a class="{{'btn btn-primary t-print transfer-print '.$checkPrint}}" data-id="">Transfer Print</a>
    @if($ivf->visit != 2 && $ivf->plan == 1)
        <button type="submit" class="{{'btn btn-primary submit '.$triggerStatus}}" value="3" id="ivf_print">Print IVF</button>
    @endif
    <button type="submit" class="{{'btn btn-primary submit embroy-button '.$simonReportType}}" value="4" id="ivf_report_print"> Save & Print IVF Report</button>
    <a href="{{URL::previous()}}" class="btn btn-default">Cancel</a>
    {{Form::close()}}
    <script type="text/javascript">
    $('.edit-hystroscopy-images').imageUploader({
        imagesInputName: 'investigation[hystroscopy][images]',
    });
    $('.edit-laproscopy-images').imageUploader({
        imagesInputName: 'investigation[laproscopy][images]',
    });
    $('.edit-blood-images').imageUploader({
        imagesInputName: 'data[blood_report][image]',
    });
    $('.edit-usg-images').imageUploader({
        imagesInputName: 'data[usg][images]',
    });
    var hystroscopyImages = @json($hystroscopyImagesData);
    var laproscopyImages = @json($laproscopyImagesData);
    var bloodReport = @json($bloodReportImagesData);
    var usgReport = @json($usgReportImagesData);
        if(hystroscopyImages != 'null') {
            $('.edit-hystroscopy-images').imageUploader({
                preloaded: jQuery.parseJSON(hystroscopyImages),
                imagesInputName: 'investigation[hystroscopy][images]',
                preloadedInputName: 'hystroscopy_old'
            });
        }
        if(laproscopyImages != 'null') {
            $('.edit-laproscopy-images').imageUploader({
                preloaded: jQuery.parseJSON(laproscopyImages),
                imagesInputName: 'investigation[laproscopy][images]',
                preloadedInputName: 'laproscopy_old'
            });
        }
        if(bloodReport != 'null'){
            $('.edit-blood-images').imageUploader({
                preloaded: jQuery.parseJSON(bloodReport),
                imagesInputName: 'data[blood_report][image]',
                preloadedInputName: 'blood_report_old'

            });
        }
        if(usgReport != 'null'){
            $('.edit-usg-images').imageUploader({
                preloaded: jQuery.parseJSON(usgReport),
                imagesInputName: 'data[usg][images]',
                preloadedInputName: 'usg_old'

            });
        }
    </script>
