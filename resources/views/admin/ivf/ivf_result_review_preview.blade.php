<style type="text/css">
    .review-list, .review-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .review-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .review-table{
        text-align: left;
    }
    .review-list tr{
        height: 50px;
        font-size: 20px;
    }
    .review-table thead th{
        height: 35px;
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
    </style>
    @php
        $description = json_decode($ivfResultReview->description);
        $typeOfData = [1=>'Primary',2=>'Secondary'];
        $o_h = !empty($ivf->o_h) ? json_decode($ivf->o_h) : null;
    @endphp
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/themes.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    
    <table class="table m-b-0 table-hover review-table" id="review-table" cellspacing="0">
        
        <tbody>
            <tr>
                <td><span class="">Name : </span>{{ucwords(strtolower($ivfResultReview->getPatients['name']))}}</td>
            </tr>
            <tr>
                <td><span>Age : </span>{{!empty($ivfResultReview->getPatients['age']) ? $ivfResultReview->getPatients['age'].' Years' : ''}}</td>
            </tr>
            <tr>
                <td><span>Type Of Infertility : </span>{{!empty($o_h) && isset($o_h->type_of_infertility) && !empty($o_h->type_of_infertility) ? $typeOfData[$o_h->type_of_infertility] : 'Primary'}} / {{!empty($o_h->first_marriage_life) ? $o_h->first_marriage_life.' years' : null}} {{!empty($o_h->second_marriage_details) ? $o_h->second_marriage_details.' years' : null}}</td>
            </tr>
            <tr>
                <td><span>Previous history of Abortions and reason for abortion :</span></td>
            </tr>
        </tbody>
    </table>
    