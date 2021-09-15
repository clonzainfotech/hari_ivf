@extends(isset($printPreview) && $printPreview == 1 ? 'layouts.printpreview' : 'layouts.printPreviewBlank')
@section('page-style')
@stop
@if(isset($printPreview) && $printPreview != 0)
    @section('content')
@endif 
{{-- <html lang="en"> --}}
    {{-- <head> --}}
        {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
        <style>
            .ivf-transfer-report .module-report-table {
                text-align: left;
                width: 100%;
            }
            td, th{
                padding: 1px 0px 4px 0px;
            }
            .p-name{
                font-size: 18px;
            }
            .ivf-transfer-report .table thead th {
                border-bottom: 2px solid black !important;
            }
            .ivf-transfer-report .table-bordered td, .table-bordered th {
                border: 1px solid black !important;
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
            .ivf-transfer-report .font-bold
            {
                font-weight: bold !important;
            }
            .ivf-transfer-report .module-report-table>tbody>tr>td, .ivf-transfer-report .module-report-table>tbody>tr>th, .ivf-transfer-report .module-report-table>thead>tr>td,.ivf-transfer-report .module-report-table>thead>tr>th
            {
                border: none !important;
            }
            h3{
                color: #1e5f63;
                -webkit-print-color-adjust: exact;
            }
            .ivf-transfer-report .module-report-table
            {
                margin-bottom: 10px !important;
            }
        </style>
    
            <div class="row content watermark ivf-transfer-report">
                
                <div class="col-sm-12">
                    <h3 class="text-center"><u>IVF REPORT</u></h3>
                </div>
                
                
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                    <tbody>
                        <tr>
                            <th>
                                <span class="pb-1 ivf-label">Name : <span><span class="pb-1 font-bold ivf-label">{{ ucwords(strtolower($transferReport->getPatient['name']))}}</span>
                                <br>
                                <span class="pb-1 ivf-label">Indication : </span>{{ !empty($transferReport->indication) ? $transferReport->indication : '-' }}
                            </th>
                            <th>
                            <th class="pb-1 float-right ivf-label">
                                <span class="pb-1 ivf-label">Age :</span> {{ !empty($transferReport->getPatient['age']) ? $transferReport->getPatient['age'].' Year' : '-' }}<br>
                                <span class="pb-1 ivf-label">Weight :</span> {{ !empty($transferReport->getPatient['weight']) ?$transferReport->getPatient['weight'].' Kg' : '-' }}
                            </th>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered mt-2 transfer-table">
                   
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center">Embryo Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ET Date</td>
                            <td>{{ !empty($transferReport->et_date) ? Carbon\Carbon::parse($transferReport->et_date)->format('D d-M-Y') : '-' }}</td>
                            <td>Day</td>
                            <td>{{ !empty($transferReport->day) ? $transferReport->day : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Endo. Thickness</td>
                            <td>{{ !empty($transferReport->endo_thickness) ? $transferReport->endo_thickness : '-' }}</td>
                            <td>ET Procedure</td>
                            <td>{{ !empty($transferReport->et_procedure) ? $transferReport->et_procedure : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Embryos Transferred</td>
                            <td colspan="3">{{ !empty($transferReport->embryos_transferred) ? $transferReport->embryos_transferred : '-' }}
                            
                            </td>
                        </tr>
                        <tr>
                            <td>Frozen Embryos</td>
                            <td colspan="3">{{ !empty($transferReport->frozen_embryos) ? $transferReport->frozen_embryos : '-' }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Embryos Transferred Report</td>
                            <td colspan="3">
                                @if(!empty($transferReport->embryos_transferred_image))
                                <img src="{{url($transferReport->embryos_transferred_image)}}" height="100" width="100">
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
                
                <table cellspacing="0" cellpadding="0" class="{{'table m-b-0 module-report-table'}}">
                    <tbody>
                        @php
                            $todayDate = Carbon\Carbon::Now();
                        @endphp
                        <tr>
                            <th>
                                <span class="pb-1 ivf-label">Follow Up Date : {{Carbon\Carbon::parse(!empty($transferReport->created_at) ? $transferReport->created_at : $todayDate)->addDays(14)->format('D d-M-Y')}}</span><br>
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
@if(isset($printPreview) && $printPreview != 0)

    @endsection
@endif