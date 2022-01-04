@extends('layouts.main')
@section('parentPageTitle', 'Ivf Result Review')
@section('title', 'Ivf Result Review')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@php
    $typeOfData = [1=>'Primary',2=>'Secondary'];
    $abArray = ['1'=>"Normal",'2'=>"Abnormal"];
    $o_h = !empty($ivf->o_h) ? json_decode($ivf->o_h) : null;
    $investigation = json_decode($ivf->investigation);
    $laproscopy = $investigation->laproscopy->type == 'yes' ? $abArray[$investigation->laproscopy->laproscopy_type] : null;
    $hcg = $investigation->hcg->type == 'yes' ? $abArray[$investigation->hcg->laproscopy_type] : null;
    $tubalFactor = !empty($laproscopy) && !empty($hcg) ? $laproscopy : (!empty($laproscopy) && empty($hcg) ? $laproscopy : $hcg);
    $o_e = !empty($ivf->o_e) ? json_decode($ivf->o_e) : null;
    $uterus = $o_e->uterus->type == 2 ? $o_e->uterus->details : 'Normal';
    $ovary = !empty($o_e->ovary) ? $o_e->ovary : null;
    $right_ovary = isset($ovary->right->details) ? implode(', ',$ovary->right->details) : null;
    $left_ovary = isset($ovary->left->details) ? implode(', ',$ovary->left->details) : null;
    $ivfReport = !empty($ivfReport) ? json_decode($ivfReport->description) : null;
    // dd($ivfReport);

@endphp
@section('content')
<div class="row clearfix anc">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <div class="col-md-4">
                    <h2><strong>Ivf Result Review</strong>
                    </h2>
                    </div>
                    <div class="col-md-8">
                    {{-- <ul class="header-dropdown">
                        <li> --}}
                                {{-- <a href="{{URL::to('ivf-result-review')}}" class="btn btn-primary pull-right">Back</a> --}}
                                <a href="{{URL::to('patient-history/'.encrypt($patient->id))}}" target="_blank" class="btn btn-primary pull-right">View History</a>
                                <a href="{{url('ivf/ivfedit/'.encrypt($patient->id))}}" class="" target="_blank">
                                    <button class="btn btn-primary pull-right">Visit-1</button>
                                </a>
                                <a href="{{url('ivf/payments/'.encrypt($patient->id))}}"   target="_blank" class="btn btn-primary pull-right"> IVF Payment</a>
                                <a href="{{URL::to('get-all-report/'.encrypt($patient->id).'?status=ivf')}}" target="_blank" class="">
                                    <button class="btn btn-primary pull-right">View Reports</button>
                                </a>
                        {{-- </li>
                    </ul> --}}
                    </div>
                </div>
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
                        {{Form::open(['class'=>'form ivf-result-review','files'=>true,'id'=>'','enctype'=>'multipart/form-data'])}}
                            {{Form::hidden('patients_id',encrypt($patient->id))}}
                            {{Form::hidden('plan',$plan)}}
                            {{Form::hidden('cycle_no',$cycle_no)}}
                            {{Form::hidden('ivf_result_id','',['class'=>'ivf_result_id'])}}
                            
                            <!-- H/O -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                <h4 class="panel-title">
                                    <a class="" role="button" data-toggle="collapse"data-parent="#ho_data" href="#ho_data" aria-expanded="true" aria-controls="ho_data">1. Patient's Information</a></h4>
                                </div>
                                <div id="ho_data" class="panel-collapse collapse show" role="tabpanel"
                                    aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    <b>Name : </b>{{ucwords(strtolower($patient->name))}}
                                                </label>
                                            </div>
                                            <div class="col-md-12 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    <b>Age : </b>{{!empty($patient->age) ? $patient->age.' Years' : ''}}
                                                </label>
                                            </div>
                                            <div class="col-md-12 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    <b>Type Of Infertility : </b>{{!empty($o_h) && isset($o_h->type_of_infertility) && !empty($o_h->type_of_infertility) ? $typeOfData[$o_h->type_of_infertility] : 'Primary'}} / {{!empty($o_h->first_marriage_life) ? $o_h->first_marriage_life.' years' : null}} {{!empty($o_h->second_marriage_details) ? $o_h->second_marriage_details.' years' : null}}
                                                </label>
                                            </div>
                                            <div class="col-md-12 pr-0">
                                                <label class="vertical-form-label pr-0">
                                                    <b>Previous history of Abortions and reason for abortion : </b>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="vertical-form-label pr-0">
                                                    <b>Reports : </b>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">TSH : &nbsp;</span>
                                                    {{Form::text("data[tsh]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->tsh) ? $ivfResultReviewDetail->tsh: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">AMH : &nbsp;</span>
                                                    {{Form::text("data[amh]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->amh) ? $ivfResultReviewDetail->amh: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Others : &nbsp;</span>
                                                    {{Form::text("data[other]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->other) ? $ivfResultReviewDetail->other: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_2">
                                <h4 class="panel-title">
                                    <a class="" role="button" data-toggle="collapse"data-parent="#ultgrasound" href="#ultgrasound" aria-expanded="true" aria-controls="ultgrasound">2. Ultgrasound parameters</a></h4>
                                </div>
                                <div id="ultgrasound" class="panel-collapse collapse show" role="tabpanel"
                                    aria-labelledby="headingThree_2">
                                    <div class="panel-body">
                                        <div class="row">
                                            
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Utreus : &nbsp;</span>
                                                    {{Form::text("data[utreus]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->utreus) ? $ivfResultReviewDetail->utreus: $uterus,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Tubal Factor(TL) : &nbsp;</span>
                                                    {{Form::text("data[tubal_factor]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->tubal_factor) ? $ivfResultReviewDetail->tubal_factor: $tubalFactor,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-md-2">Ovarian Factor Right:</span>
                                                    {{Form::text('data[ovarian_factor_right]',!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovarian_factor_right) ? $ivfResultReviewDetail->ovarian_factor_right: $right_ovary,['class'=>'form-control col-sm-4','placeholder'=>'Right Ovary'])}}
                                                    <span class="input-group-addon col-md-2">Ovarian Factor left:</span>
                                                    {{Form::text('data[ovarian_factor_left]',!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovarian_factor_left) ? $ivfResultReviewDetail->ovarian_factor_left: $left_ovary,['class'=>'form-control col-sm-4','placeholder'=>'Left Ovary'])}}
                                                    {{-- {{Form::text('visit_charges_desc','',['class'=>'form-control col-sm-4 drvisit_charge_day','placeholder'=>'Days'])}} --}}
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Ovarian Factor : &nbsp;</span>
                                                    {{Form::text("data[ovarian_factor]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovarian_factor) ? $ivfResultReviewDetail->ovarian_factor: '',['class'=>'form-control'])}}
                                                </div>
                                            </div> --}}
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Day of Serum Progestrone : &nbsp;</span>
                                                    {{Form::text("data[day_of_serum_progestrone]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->day_of_serum_progestrone) ? $ivfResultReviewDetail->day_of_serum_progestrone: '' ,['class'=>'form-control datetimepicker'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Serum Progestrone : &nbsp;</span>
                                                    {{Form::text("data[serum_progestrone]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->serum_progestrone) ? $ivfResultReviewDetail->serum_progestrone: '' ,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-12 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Endometrial Thickness : &nbsp;</span>
                                                    {{Form::text("data[endometrial]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->endometrial) ? $ivfResultReviewDetail->endometrial: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-12 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Endometrial Vascularity : &nbsp;</span>
                                                    {{Form::text("data[endometrial_vascularity]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->endometrial_vascularity) ? $ivfResultReviewDetail->endometrial_vascularity: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            {{-- <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Endometriosis : &nbsp;</span>
                                                    {{Form::text("data[endometriosis]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->endometriosis) ? $ivfResultReviewDetail->endometriosis: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Past History(TB, Generic, DM, HTN) : &nbsp;</span>
                                                    {{Form::text("data[past_history]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->past_history) ? $ivfResultReviewDetail->past_history: '',['class'=>'form-control'])}}
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_3">
                                <h4 class="panel-title">
                                    <a class="" role="button" data-toggle="collapse"data-parent="#laboratory" href="#laboratory" aria-expanded="true" aria-controls="laboratory">3. Laboratory Data</a></h4>
                                </div>
                                <div id="laboratory" class="panel-collapse collapse show" role="tabpanel"
                                    aria-labelledby="headingThree_3">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Semen analysis : &nbsp;</span>
                                                    {{Form::text("data[semen_analysis]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->semen_analysis) ? $ivfResultReviewDetail->semen_analysis: (!empty($ivfReport->ovum->count) ? $ivfReport->ovum->count : ''),['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Ovum Quality : &nbsp;</span>
                                                    {{Form::text("data[ovum_quality]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovum_quality) ? $ivfResultReviewDetail->ovum_quality: (!empty($ivfReport->ovum->quality) ? $ivfReport->ovum->quality : ''),['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Sperm Quality : &nbsp;</span>
                                                    {{Form::text("data[sperm_quality]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->sperm_quality) ? $ivfResultReviewDetail->sperm_quality: (!empty($ivfReport->ovum->semenreport) ? $ivfReport->ovum->semenreport : ''),['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Embryo Grade : &nbsp;</span>
                                                    {{Form::text("data[embryo_grade]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->embryo_grade) ? $ivfResultReviewDetail->embryo_grade: (!empty($ivfReport->ovum->blastcyst_rate) ? $ivfReport->ovum->blastcyst_rate : ''),['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Thaw to ET Time : &nbsp;</span>
                                                    {{Form::text("data[thaw_to_et_time]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->thaw_to_et_time) ? $ivfResultReviewDetail->thaw_to_et_time: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">ET Procedure : &nbsp;</span>
                                                    {{Form::text("data[et_procedure]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->et_procedure) ? $ivfResultReviewDetail->et_procedure: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Cervicl Mucus : &nbsp;</span>
                                                    {{Form::text("data[cervicl_mucus]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->cervicl_mucus) ? $ivfResultReviewDetail->cervicl_mucus: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Pickup D/B : &nbsp;</span>
                                                    {{Form::text("data[pickup]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->pickup) ? $ivfResultReviewDetail->pickup: (!empty($ivfReport->pickup) ? $ivfReport->pickup : ''),['class'=>'form-control datetimepicker'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Ovum denudation D/B : &nbsp;</span>
                                                    {{Form::text("data[ovum_denudation]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovum_denudation) ? $ivfResultReviewDetail->ovum_denudation: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">ICSI D/B : &nbsp;</span>
                                                    {{Form::text("data[icsi]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->icsi) ? $ivfResultReviewDetail->icsi: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">ET D/B : &nbsp;</span>
                                                    {{Form::text("data[et]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->et) ? $ivfResultReviewDetail->et: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_4">
                                <h4 class="panel-title">
                                    <a class="" role="button" data-toggle="collapse"data-parent="#result" href="#result" aria-expanded="true" aria-controls="result">4. Result</a></h4>
                                </div>
                                <div id="result" class="panel-collapse collapse show" role="tabpanel"
                                    aria-labelledby="headingThree_4">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">B-HCG : &nbsp;</span>
                                                    {{Form::text("data[b_hcg]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->b_hcg) ? $ivfResultReviewDetail->b_hcg: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Result : &nbsp;</span>
                                                    {{Form::text("data[result]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->result) ? $ivfResultReviewDetail->result: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Outcome : &nbsp;</span>
                                                    {{Form::text("data[outcome]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->outcome) ? $ivfResultReviewDetail->outcome: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pr-0">
                                                <div class="input-group skip-cycle">
                                                    <span class="input-group-addon text-danger">Package : &nbsp;</span>
                                                    {{Form::number("data[pkg]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->pkg) ? $ivfResultReviewDetail->pkg: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-12 pr-0">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Remark : &nbsp;</span>
                                                    {{Form::text("data[remark]",!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->remark) ? $ivfResultReviewDetail->remark: '',['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
                                <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                <a href="{{URL::to('ivf-result-review')}}" class="btn btn-default">Cancel</a>
                            </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('page-script')
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            // minDate:new Date(),
            clearButton: true,
            time:false,
            weekStart: 1
        });
        $(document).on('click','.submit',function(e){
            e.preventDefault();
            var formData = new FormData($(".ivf-result-review")[0]);

            if(this.value==1){
                formData.append('is_print', 1);
            }
            resultFormData(formData);
        });
        function resultFormData(data)
        {
            $('.submit').prop('disabled', true);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'{{URL::to("store-ivf-result-review")}}',
                type:'POST',
                dataType:'json',
                data:data,
                enctype: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data){
                var url = "{{URL::to('ivf-result-review')}}";
                if(data.status == 1){
                    window.location.href = url;
                }else if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }
                //     $('#anc_history_id').val(data.id);
                //     // window.location.href = url;
                //     $('#next-appointment-modal').modal('hide');
                // }else if(data.status == '2'){
                //     $('.how-much-error').text('Please enter valid number');
                // }else if(data.status == '3'){
                //     $('#anc_history_id').val(data.id);
                //     window.location.href = url;
                // }
                else{
                    location.reload();
                }
            });
        } 

        

    </script>
@stop
