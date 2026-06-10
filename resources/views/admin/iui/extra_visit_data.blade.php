    @php
        $lmp = !empty($iuiHistoryData) ? json_decode($iuiHistoryData->lmp) : null;
        $co = !empty($iuiHistoryData) ? json_decode($iuiHistoryData->co) : null;
        $oe = !empty($iuiHistoryData) ? json_decode($iuiHistoryData->oe) : null;
        $treatment = !empty($iuiHistoryData) ? json_decode($iuiHistoryData->treatment) : null;
        $medicineKey = [];
        if(!empty($treatment)){
            $medicineKey = (array)$treatment;
            unset($treatment->medicinedata);
            $medicineKey = array_column($medicineKey,'medicine');
            if(!empty($medicineKey)){
                $medicineKey = array_combine($medicineKey,$medicineKey);
            }
        }
        $mData = $medicineKey;
        $medqty = ['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'];
        $medicine_status = ['' => 'Select Medicine Status','1'=>'જમ્યા પછી','2'=>'જમ્યા પહેલાં','3'=>'માસિકની જગ્યાએ મુકવી'];
        $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
        $dose = ['' => 'Select Dose',"1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
    @endphp
        <!--1 C/O -->
        {{Form::hidden('iui_extra_visit_id',!empty($iuiHistoryData) ? encrypt($iuiHistoryData->id) : null)}}
        <div class="row">
            <div class="col-md-1">
                <label class="vertical-form-label pr-0">
                    Seen By :
                </label>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{Form::select('seen_by',$hospitalDoctor, (!empty($iuiHistoryData)) ? $iuiHistoryData->seen_by : null,['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
                </div>
                <span class="seen-by-error text-danger mb-2"></span>
            </div>
            <div class="col-md-1">
                <label class="vertical-form-label pr-0">
                    RMO Doctor :
                </label>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{Form::select('rmo_doctor',$rmoDoctor,(!empty($iuiHistoryData)) ? $iuiHistoryData->rmo_doctor : null,['class'=>'form-control select-padding-0','placeholder'=>'Select RMO Doctor'])}}
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="headingThree_1">
            <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                       data-parent="#co" href="#co" aria-expanded="false"
                        aria-controls="co">1. C/O</a></h4>
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
                        <div class="col-md-8 complain-multi">
                            {{Form::select('co[co_type][]',$complaints,!empty($co->co_type) ? $co->co_type : null,[
                                'class'=>'form-control co-value co_value_data complaint-data',
                                'id' => 'scroll_error',
                                'placeholder'=>'Enter complain',
                                'multiple'=>true,
                                'data-type'=>'0',
                                'data-medicine'=>1
                            ])}}
                            <span class="form-error-msg co-value-msg">
                                {{$errors->first('since')}}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon">Since : &nbsp;</span>
                                {{Form::text("co[since]",!empty($co->since) ? $co->since : null,['class'=>'form-control'])}}
                            </div>
                            <span class="form-error-msg">
                                {{$errors->first('since')}}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- 2. LMP --}}
        <div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="headingThree_1">
                <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#lmp" href="#lmp" aria-expanded="false"
                    aria-controls="lmp">2. L.M.P</a></h4>
                </div>
            <div id="lmp" class="panel-collapse collapse lmp-tab" role="tabpanel"
            aria-labelledby="headingThree_1">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">L.M.P Date : &nbsp;</span>
                            {{Form::text("lmp[date]",!empty($lmp->date) ? cdate($lmp->date)->format('D d M Y') : null,['class'=>'form-control lmd-date second-visit-lmd-date'])}}
                        </div>
                        <span class="form-error-msg lmp-date-msg"></span>
                    </div>
                        <span class="col-md-1 p-2 lmd-date-diff"></span>
                        {{Form::hidden('lmp[lmp_date_diff]',!empty($lmp->lmp_date_diff) ? $lmp->lmp_date_diff : null,['class'=>'lmd-date-diff-val'])}}
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="headingThree_1">
                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#oe" href="#oe" aria-expanded="false"
                        aria-controls="past-history">3. O/E</a></h4>
            </div>
            @php
                $psType = !empty($oe->p_s->type) && $oe->p_s->type == 'yes' ? '' : 'd-none';
                $tvsType = !empty($oe->tvs->type) && $oe->tvs->type == 'yes' ? '' : 'd-none';
                $uterusType = !empty($oe->uterus->type) && $oe->uterus->type == 2 ? '' : 'd-none';
                $leVitalsStatus = !empty($oe->le->vitals_status) && $oe->le->vitals_status == 'yes' ? '' : 'd-none';
            @endphp
            <div id="oe" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                <div class="panel-body" id="parent">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="checkbox">
                                {{Form::checkbox('oe[le][vitals_status]','yes',!empty($leVitalsStatus) ? false : true,['class'=>'vitals_status','id'=>'oe_vitals_status','data-id'=>'oe_vitals_status_data'])}}
                                <label for="oe_vitals_status">
                                    Vitals
                                </label>
                            </div>
                        </div>
                        <div class="{{'col-md-2 oe_vitals_status_data '.$leVitalsStatus}}">
                            <div class="input-group">
                                <span class="input-group-addon">B.P : &nbsp;</span>
                                {{Form::text("oe[le][bp]",!empty($oe->le->bp) ? $oe->le->bp : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <span class="{{'col-md-1 p-2 oe_vitals_status_data '.$leVitalsStatus}}">MMHG</span>
                        <div class="{{'col-md-2 oe_vitals_status_data '.$leVitalsStatus}}">
                            <div class="input-group">
                                <span class="input-group-addon">Temp : &nbsp;</span>
                                {{Form::text("oe[le][temp]",!empty($oe->le->temp) ? $oe->le->temp : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class="{{'col-md-2 oe_vitals_status_data '.$leVitalsStatus}}">
                            <div class="input-group">
                                <span class="input-group-addon">Pulse : &nbsp;</span>
                                {{Form::text("oe[le][pulse]",!empty($oe->le->pulse) ? $oe->le->pulse : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                        <span class="{{'col-md-1 p-2 oe_vitals_status_data '.$leVitalsStatus}}">/ Min</span>
                    </div>
                    <div class="row">
                        <div class="col-md-1 pr-0">
                            <label class="vertical-form-label pr-0">
                                P/S :
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[p_s][type]",'yes',!empty($psType) ? false : true,['id'=>'ps_type_yes','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                <label for="ps_type_yes">
                                    Yes
                                </label>

                                {{Form::radio("oe[p_s][type]",'no',!empty($psType) ? true : false,['id'=>'ps_type_no','class'=>'iui-yes-no-status','data-type'=>'ps-details'])}}
                                <label for="ps_type_no">
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="{{'col-md-4 ps-details '.$psType}}">
                            <div class="form-group">
                                {{Form::text("oe[p_s][details]",!empty($oe->p_s->details) ? $oe->p_s->details : null,['class'=>'form-control','placeholder'=>'Details'])}}
                            </div>
                        </div>
                        <div class="{{'col-md-4 ps-details '.$psType}}">
                            <div class="form-group">
                                {{Form::text("oe[cervix][details]",!empty($oe->cervix->details) ? $oe->cervix->details : null,['class'=>'form-control','placeholder'=>'Cervix Details'])}}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-1 pr-0">
                            <label class="vertical-form-label pr-0">
                                Cervix :
                            </label>
                        </div>
                        
                    </div> --}}
                    <div class="row">
                        <div class="col-md-1 pr-0">
                            <label class="vertical-form-label pr-0">
                                TVS :
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <div class="radio is-conceived">
                                {{Form::radio("oe[tvs][type]",'yes',!empty($tvsType) ? false : true,['id'=>'tvs_type_yes','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                <label for="tvs_type_yes">
                                    Yes
                                </label>
                                {{Form::radio("oe[tvs][type]",'no',!empty($tvsType) ? true : false,['id'=>'tvs_type_no','class'=>'iui-yes-no-status','data-type'=>'tvs-details'])}}
                                <label for="tvs_type_no">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="{{'row tvs-details '.$tvsType}}">
                        <div class="col-md-1"></div>
                        <div class="col-md-1 pr-0">
                            <label class="vertical-form-label pr-0">
                                Uterus :
                            </label>
                        </div>
                        <div class="col-md-2 tvs-details">
                            <div class="form-group">
                                {{Form::select("oe[uterus][type]",['1'=>'Normal','2'=>"Abnormal"],!empty($oe->uterus->type) ? $oe->uterus->type : null,['class'=>'form-control select-padding-0 abnormal','data-type'=>'uterus-abnormal-type'])}}
                            </div>
                        </div>
                        <div class="{{'col-md-2 uterus-abnormal-type '.$uterusType}}">
                            <div class="form-group">
                                {{Form::text("oe[uterus][details]",'',['class'=>'form-control','placeholder'=>'Abnormal Details'])}}
                            </div>
                        </div>
                        <span class="{{'col-md-1 p-2 uterus-abnormal-type '.$uterusType}}">LG</span>
                    </div>
                    <div class="{{'row tvs-details '.$uterusType}}">
                        <div class="col-md-1"></div>
                        <div class="col-md-2 pr-0">
                            <label class="vertical-form-label pr-0">
                                Endometrial Thickness :
                            </label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::text("oe[endometrial_thickness]",!empty($oe->endometrial_thickness) ? $oe->endometrial_thickness : null,['class'=>'form-control','placeholder'=>'Endometrial Thickness Details'])}}
                            </div>
                        </div>
                    </div>

                    <div class="{{'row tvs-details '.$tvsType}}">
                        <div class="col-md-1"></div>
                        <div class="col-md-1 pr-0 tvs-details d-none">
                            <label class="vertical-form-label pr-0">
                                Ovary :
                            </label>
                        </div>
                        <div class="col-md-1">
                            <div class="checkbox">
                                {{Form::checkbox('oe[ovary][type][]','left',!empty($oe->ovary->type) && in_array('left',$oe->ovary->type) ? true : false,['id'=>'left','class'=>'plan-management'])}}
                                <label for="left">
                                    Left
                                </label>
                            </div>
                        </div>
                        {{-- <div class="col-md-3 left-details">
                            <div class="form-group">
                                {{Form::select("oe[ovary][left][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-left-abnormal-type'])}}
                            </div>
                        </div> --}}
                        <div class='col-md-3'>
                            <div class="input-group">
                                <span class="input-group-addon">AFCS : &nbsp;</span>
                                {{Form::text("oe[ovary][left][afcs]",!empty($oe->ovary->left->afcs) ? $oe->ovary->left->afcs : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row tvs-details">
                        <div class="col-md-3"></div>
                        <div class="col-md-9 left-details">
                            <div class="row">
                                <div class="{{'col-md-5 complain-multi tvs-details '.$tvsType}}">
                                    {{Form::select("oe[ovary][left][details][]",$leftOvaryData,!empty($oe->ovary->left->details) ? $oe->ovary->left->details : null,[
                                        'class'=>'form-control co-value co_value_data oe_ovary_left_details',
                                        'placeholder'=>'Abnormal Details',
                                        'id' => 'oe_ovary_left_details',
                                        'data-type' => 'oe',
                                        'multiple'=>true
                                    ])}}
                                </div>
                                <div class="col-md-6 complain-multi ovary-left-abnormal-type">
                                    <div class="row edit_oe_ovary_left_details">
                                        @if (isset($oe->ovary->left->updated_details))
                                            @foreach ($oe->ovary->left->updated_details as $key => $value)
                                                <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key]) . '_left' }}">
                                                    {{Form::text('oe[ovary][left][updated_details][]', !empty($value) ? $value : null, [
                                                        'class' => 'form-control edited_oe_ovary_left_details',
                                                        'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->left->details[$key])
                                                    ])}}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="{{'row tvs-details '.$tvsType}}">
                        <div class="col-md-2"></div>
                        <div class="col-md-1">
                            <div class="checkbox">
                                {{Form::checkbox('oe[ovary][type][]','right',!empty($oe->ovary->type) && in_array('right',$oe->ovary->type) ? true : false,['id'=>'right','class'=>'plan-management'])}}
                                <label for="right">
                                    Right
                                </label>
                            </div>
                        </div>
                        {{-- <div class="col-md-3 right-details">
                            <div class="form-group">
                                {{Form::select("oe[ovary][right][type]",['1'=>'Normal','2'=>"Abnormal"],'',['class'=>'form-control select-padding-0 abnormal','data-type'=>'ovary-right-abnormal-type'])}}
                            </div>
                        </div> --}}
                        <div class='col-md-3'>
                            <div class="input-group">
                                <span class="input-group-addon">AFCS : &nbsp;</span>
                                {{Form::text("oe[ovary][right][afcs]",!empty($oe->ovary->right->afcs) ? $oe->ovary->right->afcs : null,['class'=>'form-control'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row tvs-details">
                        <div class="col-md-3"></div>
                        <div class="col-md-9 right-details">
                            <div class="row">
                                <div class="{{'col-md-5 complain-multi tvs-details mt-1 '.$tvsType}}">
                                    {{Form::select("oe[ovary][right][details][]",$rightOvaryData,!empty($oe->ovary->right->details) ? $oe->ovary->right->details : null,[
                                        'class'=>'form-control co-value co_value_data oe_ovary_right_details',
                                        'placeholder'=>'Abnormal Details',
                                        'id' => 'oe_ovary_right_details',
                                        'data-type' => 'oe',
                                        'multiple'=>true
                                    ])}}
                                </div>
                                <div class="{{'col-md-6 complain-multi tvs-details'}}">
                                    <div class="row edit_oe_ovary_right_details">
                                        @if (isset($oe->ovary->right->updated_details))
                                            @foreach ($oe->ovary->right->updated_details as $key => $value)
                                                <div class="form-group col-md-12" id="{{ preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key]) . '_right' }}">
                                                    {{Form::text('oe[ovary][right][updated_details][]', !empty($value) ? $value : null, [
                                                        'class' => 'form-control edited_oe_ovary_right_details',
                                                        'id' => preg_replace('/[^a-zA-Z0-9]/','_',$oe->ovary->right->details[$key])
                                                    ])}}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    
                </div>
            </div>
        </div>

         <!--4. Treatment history  -->
        <div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="headingThree_1">
                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                        aria-controls="past-history">4. Treatment</a></h4>
            </div>
            <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                <div class="panel-body" id="parent">

                    <div class="row treatment-data" id="t_data_1">
                            <div class="col-md-2 pr-0">
                                <label class="vertical-form-label pr-0">
                                    Select Medicine :
                                </label>
                            </div>
                        <div class="col-md-9 complain-multi medicine-picker">
                            {{Form::select("treatment[medicinedata][]",$medicines,$mData,['id'=>'treatment-medicine','class'=>'form-control co-value medicine medicine-co',"placeholder" => 'Select Medicine'])}}
                        </div>
                    </div>
                    <div class="page-loader-wrapper medicine-loader d-none">
                        <div class="loader">
                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                        </div>
                    </div>
                    <div class="medicine-data">
                        @if(!empty($treatment))
                        @foreach($treatment as $key=>$row)
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
                                        {{Form::text('treatment['.$mId.'][medicine]',ucwords($row->medicine),['class'=>'form-control','readonly'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity]',$medqty,$row->quantity,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity_2]',$medqty,@$row->quantity_2,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity_3]',$medqty,@$row->quantity_3,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][quantity_4]',$medqty,@$row->quantity_4,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2 notinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][medicine_status]',$medicine_status,$row->medicine_status,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2 isinject'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][medicine_time]',$medicine_time,@$row->medicine_time,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='form-group'>
                                        {{Form::select('treatment['.$mId.'][dose]',$dose,$row->dose,['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>Day :</span>
                                        {{Form::number('treatment['.$mId.'][no]',$row->no,['class'=>'form-control '.$till_follow_up])}}
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <div class='form-group'>
                                        <!-- <span class='input-group-addon'>Day :</span> -->
                                        {{Form::number('treatment['.$mId.'][note]',isset($row->note) ? $row->note : '',['class'=>'form-control','placeholder'=>'Note'])}}
                                    </div>
                                </div>
                                <div class='col-md-1 medicine-data-remove'>
                                    <span class=""><i class="material-icons">close</i></span>
                                </div>
                            </div>
                            {{-- @endif --}}
                        @endforeach
                    @endif
                    </div>
                    {{Form::hidden('old_medicine_data',!empty($medicineKey) ? implode(',',$medicineKey) : null,['class'=>'old-medicine-data'])}}
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-5">
                <div class="input-group">
                    <span class="input-group-addon">
                        Other Report : &nbsp;
                    </span>
                    {{Form::text("oe[investigation_extra]",!empty($oe->investigation_extra) ? $oe->investigation_extra : null,['class'=>'form-control'])}}
                </div>
            </div>
        </div>
        @php
            $bloodReportClass = !empty($oe->blood_report) && !empty($oe->blood_report->type) && $oe->blood_report->type == 'yes' ? true : false;
            $bloodReportClassName = $bloodReportClass ? '' : 'd-none';
        @endphp
        <div class="row">
                                                <div class=" pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Blood Report :
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("oe[blood_report][type]",'yes',$bloodReportClass,['id'=>'blood_type_yes','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                                        <label for="blood_type_yes">
                                                            Yes
                                                        </label>

                                                        {{Form::radio("oe[blood_report][type]",'no',!empty($oe->blood_report) && !empty($oe->blood_report->type) && $oe->blood_report->type == 'no' ? true : false,['id'=>'blood_type_no','class'=>'blood-type iui-yes-no-status','data-type'=>'blood-type'])}}
                                                        <label for="blood_type_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="{{'col-md-8 pr-0 blood-type extra-blood-images '.$bloodReportClassName}}">
                                                    <div class="blood-images"></div>
                                                </div>
                                                
                                            </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    {{Form::textarea("oe[remark]",!empty($oe->remark) ? $oe->remark : '',['class'=>'form-control no-resize remark','placeholder'=>'Remark','rows'=>'2'])}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    {{Form::textarea("oe[pt_remark]",!empty($oe->pt_remark) ? $oe->pt_remark : '',['class'=>'form-control no-resize remark','placeholder'=>'Patient Remark','rows'=>'2'])}}
                </div>
            </div>
        </div>
        <br>
        @if(empty($iuiHistoryData))
            <div class="row">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Follow Up: &nbsp;</span>
                        {{Form::text("oe[follow_up]",'',['class'=>'form-control datetimepicker next-date'])}}
                    </div>
                    <span class="form-error-msg follow-error"></span>
                </div>
            </div>
        @else
            {{Form::hidden("oe[follow_up]", isset($oe->follow_up) && !empty($oe->follow_up) ? $oe->follow_up : '',[
                                'class'=>'form-control next-date'
                            ])}}
        @endif
        <br>
        <br>
        <script src="{{URL::to('js/image-uploader.js')}}"></script>
    <script type="text/javascript">
        var code = '';
        var patientsId = $('.patients-id').val();
        var bloodReportImages = @json($bloodReportImagesArray);
        console.log(bloodReportImages);
        $(document).ready(function(){
            $('.extra-blood-images').imageUploader({
                imagesInputName: 'oe[blood_report][image]',
            });
            if(bloodReportImages != 'null') {
                $('.extra-blood-images').imageUploader({
                    preloaded: jQuery.parseJSON(bloodReportImages),
                    imagesInputName: 'oe[blood_report][image]',
                    preloadedInputName: 'extraVisit_blood_report_old'
                });
            }
        });
</script>        

