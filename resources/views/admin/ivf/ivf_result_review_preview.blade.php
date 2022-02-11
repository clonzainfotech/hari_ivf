@php
    $systemSetting = systemSetting();
    $water_mark = isset($systemSetting->water_mark) && !empty($systemSetting->water_mark) ? $systemSetting->water_mark : null;

@endphp
<style type="text/css">
    .review-list, .review-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
        -webkit-print-color-adjust: exact;
    }
    .review-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
        -webkit-print-color-adjust: exact;
    }
    .review-table{
        text-align: left;
        -webkit-print-color-adjust: exact;
    }
    .review-list tr{
        height: 50px;
        font-size: 20px;
        -webkit-print-color-adjust: exact;
    }
    .review-table thead th{
        height: 35px;
        -webkit-print-color-adjust: exact;
    }
    .review-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .review-table tr {
        height: 27px;
    }
    .table-footer{
        font-weight: 900;
        color: #01d8da;
        height: 50px;
        font-size: 20px;
        -webkit-print-color-adjust: exact;
    }
    td{
        height: 25px;
        font-size: 14px;
    }
    .upper-border {
        border-top: 1px solid #000000;
    }
    .report-header-tr {
        text-align: left;
        height: 35px;
    }
    .report-header-tr-th {
        background-color: #c7dfe0;
        font-size: 13px;
        -webkit-print-color-adjust: exact;
    }
    .amount {
        font-weight: 600;
    }
    .text-center {
        text-align: center;
    }
    
    tr td th {
        padding: 12px 12px;
    }
    .watermark
    {
        background-repeat: no-repeat;
        background-position: center;
        -webkit-print-color-adjust: exact;
        position: relative;
    /* background-image:url({{url('public/images/' . $water_mark)}}); */
    }
    .watermark:before 
    {
        position: absolute;
        content: '';
        width: 600px;
        height: 100%;
        background-repeat: no-repeat;
        background-size: contain;
        top: 42%;
        left: 0;
        right: 0;
        margin: auto;
        z-index: 9;
        opacity: 0.2;
        background-image: url({{url('public/images/' . $water_mark)}});
    
    }
    </style>
    @php
        $ivfResultReviewDetail = json_decode($ivfResultReview->description);
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
        $abortion_reason = [];
        $total_abortion = null;
        if(!empty($o_h) && ($o_h->abortion_no != null && $o_h->abortion_no != 0 ))
        {
            $total_abortion = $o_h->abortion_no;
            foreach($o_h->abortion->abortion_data as $key=>$value)
            {
                $abortion_reason[] = isset($value->reason) && !empty($value->reason) ? $value->reason : null;
            }
        }
        if(!empty($o_h) && isset($o_h->second_marriage) && ($o_h->second_marriage->abortion_no != null && $o_h->second_marriage->abortion_no != 0 ))
        {
            // if(!empty($o_h) && isset($o_h->second_marriage) && ($o_h->second_marriage->child_no != null && $o_h->second_marriage->child_no != 0))
            // {
                $total_abortion = $o_h->second_marriage->abortion_no;
                $abortion_reason = [];
                foreach($o_h->second_marriage->abortion->abortion_data as $key=>$value)
                {
                    $abortion_reason[] = isset($value->reason) && !empty($value->reason) ? $value->reason : null;
                }
            // }
        }
    @endphp
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/themes.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <div class="watermark">
        <table class="table m-b-0 table-hover review-table" id="review-table" cellspacing="0">
            
            <tbody>
                <tr>
                    <td><span class="candor-color font-bold">Name : </span>{{ucwords(strtolower($ivfResultReview->getPatients['name']))}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Age : </span>{{!empty($ivfResultReview->getPatients['age']) ? $ivfResultReview->getPatients['age'].' Years' : ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Type Of Infertility : </span>{{!empty($o_h) && !empty($o_h->second_marriage_details) ? 'Secondary' : 'Primary'}} / {{!empty($o_h->second_marriage_details) ? $o_h->second_marriage_details.' years' : (!empty($o_h->first_marriage_life) ? $o_h->first_marriage_life.' years' : null)}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Previous history of Abortions and reason for abortion : </span>{{!empty($total_abortion) ? 'Total '.$total_abortion.' Abortion' : ''}}  {{count($abortion_reason) > 0 ? ' / Reason : '.implode(' , ',$abortion_reason) : ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">TSH : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->tsh) ? $ivfResultReviewDetail->tsh: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">AMH : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->amh) ? $ivfResultReviewDetail->amh: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Others :</span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->other) ? $ivfResultReviewDetail->other: ''}}</td>
                </tr>
            </tbody>
        </table>
        
        <h4>Ultgrasound parameters</h4>
        
        <table class="table m-b-0 table-hover review-table" id="review-table" cellspacing="0">
            <tbody>
            <tr>
                <td><span class="candor-color font-bold">Utreus : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->utreus) ? $ivfResultReviewDetail->utreus: $uterus}}</td>
            </tr>
            <tr>
                <td><span class="candor-color font-bold">Tubal Factor(TL) : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->tubal_factor) ? $ivfResultReviewDetail->tubal_factor: $tubalFactor}}</td>
            </tr>
            <tr>
                <td><span class="candor-color font-bold">Ovarian Factor Right : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovarian_factor_right) ? $ivfResultReviewDetail->ovarian_factor_right: $right_ovary}}</td>
            </tr>
            <tr>
                <td><span class="candor-color font-bold">Ovarian Factor left : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovarian_factor_left) ? $ivfResultReviewDetail->ovarian_factor_left: $left_ovary}}</td>
            </tr>
            <tr>
                <td><span class="candor-color font-bold">Day of Serum Progestrone : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->day_of_serum_progestrone) ? $ivfResultReviewDetail->day_of_serum_progestrone: ''}}</td>
            </tr>
            <tr>
                <td><span class="candor-color font-bold">Endometrial Thickness : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->endometrial) ? $ivfResultReviewDetail->endometrial: ''}}</td>
            </tr>
            <tr>
                <td><span class="candor-color font-bold">Endometrial Vascularity : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->endometrial_vascularity) ? $ivfResultReviewDetail->endometrial_vascularity: ''}}</td>
            </tr>
            </tbody>
        </table>
        
        <h4>Laboratory Data</h4>
        
        <table class="table m-b-0 table-hover review-table" id="review-table" cellspacing="0">
            <tbody>
                
                <tr>
                    <td><span class="candor-color font-bold">Semen analysis : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->semen_analysis) ? $ivfResultReviewDetail->semen_analysis: (!empty($ivfReport->ovum->count) ? $ivfReport->ovum->count : '')}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Ovum Quality : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovum_quality) ? $ivfResultReviewDetail->ovum_quality: (!empty($ivfReport->ovum->quality) ? $ivfReport->ovum->quality : '')}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Sperm Quality : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->sperm_quality) ? $ivfResultReviewDetail->sperm_quality: (!empty($ivfReport->ovum->semenreport) ? $ivfReport->ovum->semenreport : '')}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Embryo Grade : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->embryo_grade) ? $ivfResultReviewDetail->embryo_grade: (!empty($ivfReport->ovum->blastcyst_rate) ? $ivfReport->ovum->blastcyst_rate : '')}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Thaw to ET Time : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->thaw_to_et_time) ? $ivfResultReviewDetail->thaw_to_et_time: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">ET Procedure : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->et_procedure) ? $ivfResultReviewDetail->et_procedure: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Cervicl Mucus : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->cervicl_mucus) ? $ivfResultReviewDetail->cervicl_mucus: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Pickup D/B : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->pickup) ? $ivfResultReviewDetail->pickup: (!empty($ivfReport->pickup) ? $ivfReport->pickup : '')}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Ovum denudation D/B : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->ovum_denudation) ? $ivfResultReviewDetail->ovum_denudation: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">ICSI D/B : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->icsi) ? $ivfResultReviewDetail->icsi: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">ET D/B : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->et) ? $ivfResultReviewDetail->et: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">laboratory Remark : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->laboratory_remark) ? $ivfResultReviewDetail->laboratory_remark: ''}}</td>
                </tr>
            </tbody>
        </table>
        
        <h4>Result</h4>
        
        <table class="table m-b-0 table-hover review-table" id="review-table" cellspacing="0">
            <tbody>
                
                <tr>
                    <td><span class="candor-color font-bold">B-HCG : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->b_hcg) ? $ivfResultReviewDetail->b_hcg: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Result : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->result) ? $ivfResultReviewDetail->result: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Outcome : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->outcome) ? $ivfResultReviewDetail->outcome: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Package : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->pkg) ? $ivfResultReviewDetail->pkg: ''}}</td>
                </tr>
                <tr>
                    <td><span class="candor-color font-bold">Remark : </span>{{!empty($ivfResultReviewDetail) && isset($ivfResultReviewDetail->remark) ? $ivfResultReviewDetail->remark: ''}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    