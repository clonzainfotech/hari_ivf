@extends('layouts.printpreview')
@section('page-style')
@stop
@section('content')
{{-- <html lang="en"> --}}
    {{-- <head> --}}
        {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
        <style>
            .module-report-table {
                margin-top: -16px;
                text-align: left;
                width: 100%;
            }
            td, th{
                padding: 1px 0px 4px 0px;
            }
            .p-name{
                font-size: 18px;
            }
            .table thead th {
                border-bottom: 2px solid black !important;
            }
            .table-bordered td, .table-bordered th {
                border: 1px solid black !important;
                padding: 5px !important;
            }
            .pl-10
            {
                padding-left: 5rem;
            }
            .ivf-label{
                font-weight: normal;
            }
            .text-center
            {
             text-align: center;
            }
            .float-right
            {
                float: right;
            }
            .font-bold
            {
                font-weight: bold;
            }
            .module-report-table>tbody>tr>td, .module-report-table>tbody>tr>th, .module-report-table>thead>tr>td, .module-report-table>thead>tr>th
            {
                
                border: none !important;
            }
            h3{
                color: #1e5f63;
                -webkit-print-color-adjust: exact;
            }
            .module-report-table
            {
                margin-bottom: 10px !important;
            }
        </style>
        @php
        $iuireportData = json_decode($iuiReport->description);    
        // dd($iuireportData->ovum->erphoto);
        @endphp
    {{-- </head> --}}
    {{-- <body> --}}
        {{-- <div class="container-fluid"> --}}
            <div class="row content watermark">
                
                <div class="col-sm-12">
                    <h3 class="text-center"><u>IUI REPORT</u></h3>
                </div>
                
                
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                    <tbody>
                        <tr>
                            <th>
                                <span class="pb-1 ivf-label">Name : <span><span class="pb-1 font-bold ivf-label">{{ ucwords(strtolower($iuiReport->getPatients['name']))}}</span>
                                <br>
                                <span class="pb-1 ivf-label">Age : </span>{{!empty($iuiReport->getPatients['age']) ? $iuiReport->getPatients['age'].' Year' : '-'}}
                            </th>
                            <th>
                            <th class="pb-1 float-right ivf-label">
                                <span class="pb-1 ivf-label">Date :</span> <span class="pb-1 font-bold ivf-label">{{\Carbon\Carbon::parse($iuiReport->created_at)->format('d-m-Y')}}</span><br>
                                <span class="pb-1 ivf-label">Reason :</span> <span class="pb-1 font-bold ivf-label"> {{!empty($iuireportData->reason) ? $iuireportData->reason : '-'}}</span>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered mt-2 transfer-table">
                   
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Pre-wash</th>
                            <th>Post-wah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Volume:</td>
                            <td><strong>{{!empty($iuireportData->volume_pre) ? $iuireportData->volume_pre.' ml' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->volume_post) ? $iuireportData->volume_post.' ml' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Sperm Count/ml</strong></td>
                            <td><strong>{{!empty($iuireportData->sperm_count_pre) ? $iuireportData->sperm_count_pre.' mili/ml' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->sperm_count_post) ? $iuireportData->sperm_count_post.' mili/ml' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Count(mili) :</strong></td>
                            <td><strong>{{!empty($iuireportData->total_count_pre) ? $iuireportData->total_count_pre.' mili' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->total_count_post) ? $iuireportData->total_count_post.' mili' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Motility(%) :</strong></td>
                            <td><strong>{{!empty($iuireportData->total_motility_pre) ? $iuireportData->total_motility_pre.' %' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->total_motility_post) ? $iuireportData->total_motility_post.' %' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Actively Motile(%) :</strong></td>
                            <td><strong>{{!empty($iuireportData->actively_motile_pre) ? $iuireportData->actively_motile_pre.' %' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->actively_motile_post) ? $iuireportData->actively_motile_post.' %' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Sluggishly Motile(%) :</strong></td>
                            <td><strong>{{!empty($iuireportData->sluggishly_motile_pre) ? $iuireportData->sluggishly_motile_pre.' %' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->sluggishly_motile_post) ? $iuireportData->sluggishly_motile_post.' %' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Non-Motile(%) :</strong></td>
                            <td><strong>{{!empty($iuireportData->non_motile_pre) ? $iuireportData->non_motile_pre.' %' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->non_motile_post) ? $iuireportData->non_motile_post.' %' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Normal Morphology(%) :</strong></td>
                            <td><strong>{{!empty($iuireportData->normal_morphology_pre) ? $iuireportData->normal_morphology_pre.' %' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->normal_morphology_post) ? $iuireportData->normal_morphology_post.' %' : '-'}}</strong></td>
                        </tr>
                        <tr>
                            <td>Pus Cells / hpf :</strong></td>
                            <td><strong>{{!empty($iuireportData->pus_cells_pre) ? $iuireportData->pus_cells_pre.' /hpf' : '-'}}</strong></td>
                            <td><strong>{{!empty($iuireportData->pus_cells_post) ? $iuireportData->pus_cells_post.' /hpf' : '-'}}</strong></td>
                        </tr>
                    </tbody>
                </table>
                
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                    <tbody>
                        @php
                            $todayDate = Carbon\Carbon::Now();
                        @endphp
                        <tr>
                            <th><span class="pb-1 ivf-label"><strong>Remark : </strong>{{!empty($iuireportData->remark) ? $iuireportData->remark : '-'}} </span></th>
                        </tr>
                        <tr>
                            <th>
                                <span class="pb-1 ivf-label">Follow Up Date : {{Carbon\Carbon::parse(!empty($iuireportData->follow_up) ? $iuireportData->follow_up : null)->format('D d M Y')}}</span><br>
                                <span class="pb-1 ivf-label">Best Of Luck</span>
                            </th>
                            <th>
                                <span class="pb-1 ivf-label">{{config('app.doctor') }}</span>
                                <br>
                                <span class="pb-1 ivf-label">Chief Consultant</span>
                            </th>
                            <th>
                            <th class="pb-1 float-right ivf-label text-center">
                                <span class="pb-1 ivf-label">Dr. Bhavna Borkhataria</span>
                                <br>
                                <span class="pb-1 ivf-label">Embryologist</span>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        {{-- </div> --}}
    {{-- </body> --}}
{{-- </html> --}}
@endsection