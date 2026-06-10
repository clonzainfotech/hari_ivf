<style type="text/css">
.category-report-table-first, .category-report-table{
    font-family: 'Montserrat', Arial, Tahoma, sans-serif;
    width: 100%;
    text-align: left;
}
.category-report-table-first{
    margin-bottom: 10px;
}
.category-report-table{
    text-align: left;
}
.category-report-table-first tr{
    height: 35px;
    font-size: 20px;
}
.doctor-category{
    color: #01d8da;
}
.category-report-table thead th{
    height: 35px;
}
.category-report-table thead th span{
    border-bottom: 1px solid #000;
}
.table-footer{
    font-weight: 900;
    color: #01d8da;
    height: 50px;
    font-size: 20px;
}
.reference-doctor-tr{
    font-size: 14px;
}
td {
    height: 25px;
    font-size: 11px;
}

.top-border-first {
    border-top: 2px #000000 solid;
}
.top-border-second {
    border-top: #000000 double;
}

.text-center {
    text-align: center;
}

.sub-heading {
    font-size: 13px;
}

.amount {
    font-weight: 600;
}
.report-header-tr-th {
    background-color: #c7dfe0;
    font-size: 13px;
    line-height: 7px;
    padding-left:12px;
    padding-right:12px;
}

.data-font {
    font-size: 11px;
}
.seperator {
    border-top: 0.5px solid #dee2e6;
}

tr td th {
    padding: 12px 12px;
}
.expense-icon-hide
{
    display: none;
}
.expense-icon-display
{
    display: revert;
}
</style>
@php
    $expense_icon = ($paymentType == 2) ? 'expense-icon-hide' : 'expense-icon-display';
@endphp
<table class="table m-b-0 table-hover category-report-table-first font" cellspacing="0">
    <thead>
        <tr>
            <th colspan="9" class="text-center">{{strtoupper(config('app.hospitalname1'))}}</th>
        </tr>
        <tr>
            <th colspan="9" class="text-center">CA Expense Report</th>
        </tr>
    </thead>
    <tr>
        <td colspan="9" class="text-center sub-heading seperator amount">OPD</td>
    </tr>
    <tr class="report-header-tr">
        <th class="report-header-tr-th w-100">Sr No</th>
        <th class="report-header-tr-th">Date</th>
        <th class="report-header-tr-th">Patient Name</th>
        <th class="report-header-tr-th">Category</th>
        <th class="report-header-tr-th">Amount</th>
        @if($paymentType != 2) 
            <th class="report-header-tr-th {{$expense_icon}}">Txt_Amt</th>
            <th class="report-header-tr-th {{$expense_icon}}">Invoice_No</th>
            <th class="report-header-tr-th {{$expense_icon}}">Bank</th>
            <th class="report-header-tr-th {{$expense_icon}}">Detail</th>
        @endif        
        {{-- <th class="report-header-tr-th w-100">Sr no.</th>
        <th class="report-header-tr-th">Date</th>
        <th class="report-header-tr-th">Patient Name</th>
        <th class="report-header-tr-th">Category</th>
        <th class="report-header-tr-th">Amount</th>
        <th class="report-header-tr-th {{$expense_icon}}">Txt_Amt</th>
        <th class="report-header-tr-th {{$expense_icon}}">Invoice_No</th>
        <th class="report-header-tr-th {{$expense_icon}}">Bank</th>
        <th class="report-header-tr-th {{$expense_icon}}">Detail</th> --}}
    </tr>
    @php 
        $totalUsg = $totalUsgLeft = $totalUsgRight = 0;
        $i = 0;
        $count = count($usg);
    @endphp
    @if ($count > 0)
    <tr>
        <th colspan="9" class="sub-heading">USG</th>
    </tr>
   
        @for ($i = 0; $i < $count; $i++)
            <tr>
                @php $totalUsgLeft +=  $usg[$i]->getAppointmentCharges['usg']; @endphp
                <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                <td class="data-font seperator">{{ cdate($usg[$i]->created_at)->format('d-m-Y')}}</td>
                <td class="data-font seperator">{{strtoupper(@$usg[$i]->getPatientsDetails['name'])}}</td>
                <td class="data-font seperator">{{ucfirst(@$usg[$i]->categoryDetails['name'])}}</td>
                <td class="data-font seperator">
                    <div class="amount">
                        {{ $usg[$i]->getAppointmentCharges['usg']}}
                    </div>
                </td>
                <td class="{{$expense_icon}} data-font seperator">{{$usg[$i]->getAppointmentCharges['txt_amount']}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$usg[$i]->getAppointmentCharges['invoice_no']}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$usg[$i]->getAppointmentCharges['bank_id']])) ? $bank_details[$usg[$i]->getAppointmentCharges['bank_id']] : ''}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$usg[$i]->getAppointmentCharges['detail']}}</td>
                {{-- @if ($count > 8) 
                    @php
                        $i++;
                    @endphp
                @endif
                @if ($i < $count && $count > 8) 
                    @php
                        $totalUsgRight +=  $usg[$i]->getAppointmentCharges['usg'];
                    @endphp
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                    <td class="data-font seperator">{{ cdate($usg[$i]->date)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{strtoupper(@$usg[$i]->getPatientsDetails['name'])}}</td>
                    <td class="data-font seperator">{{ucfirst(@$usg[$i]->categoryDetails['name'])}}</td>
                    <td class="data-font seperator">
                        <div class="amount">
                            {{ $usg[$i]->getAppointmentCharges['usg']}}
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$usg[$i]->getAppointmentCharges['txt_amount']}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$usg[$i]->getAppointmentCharges['invoice_no']}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$usg[$i]->getAppointmentCharges['bank_id']])) ? $bank_details[$usg[$i]->getAppointmentCharges['bank_id']] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$usg[$i]->getAppointmentCharges['detail']}}</td>
                @endif --}}
            </tr>
        @endfor
        @php
            $totalUsg = $totalUsgLeft + $totalUsgRight;
        @endphp
        <tr>
            <td class="bt-none" colspan="3"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="top-border-first">{{  $totalUsg }}</th>
        </tr>
    {{-- @else
        <td colspan='12' class="text-center">No records available</td> --}}
    @endif
    @php 
    $totalHormon = $totalHormonLeft = $totalHormonRight = 0;
    $i = 0;
    $count = count($hormon);
    // dd($count);
@endphp
@if ($count > 0)
    <tr>
        <th colspan="9" class="sub-heading">Hormon</th>
    </tr>

    
        @for ($i = 0; $i < $count; $i++)
            <tr>
                @php $totalHormonLeft +=  $hormon[$i]->amount; @endphp
                <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                <td class="data-font seperator">{{ cdate($hormon[$i]->created_at)->format('d-m-Y')}}</td>
                <td class="data-font seperator">{{strtoupper(@$hormon[$i]->getPatients['name'])}}</td>
                <td class="data-font seperator"></td>
                <td class="data-font seperator">
                    <div class="amount">
                        {{ $hormon[$i]->amount}}
                    </div>
                </td>
                <td class="{{$expense_icon}} data-font seperator">{{$hormon[$i]->txt_amount}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$hormon[$i]->invoice_no}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$hormon[$i]->bank_id])) ? $bank_details[$hormon[$i]->bank_id] : ''}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$hormon[$i]->detail}}</td>
                {{-- @if ($count > 8) 
                    @php
                        $i++;
                    @endphp
                @endif
                @if ($i < $count && $count > 8) 
                    @php
                        $totalHormonRight +=  $hormon[$i]->amount;
                    @endphp
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                    <td class="data-font seperator">{{ cdate($hormon[$i]->created_at)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{strtoupper(@$hormon[$i]->getPatients['name'])}}</td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator">
                        <div class="amount">
                            {{ $hormon[$i]->amount}}
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$hormon[$i]->txt_amount}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$hormon[$i]->invoice_no}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$hormon[$i]->bank_id])) ? $bank_details[$hormon[$i]->bank_id] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$hormon[$i]->detail}}</td>
                @endif --}}
            </tr>
        @endfor
        @php
            $totalHormon = $totalHormonLeft + $totalHormonRight;
        @endphp
        <tr>
            <td class="bt-none" colspan="3"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="top-border-first">{{  $totalHormon }}</th>
        </tr>
    {{-- @else
        <td colspan='12' class="text-center">No records available</td> --}}
    @endif
    @php 
    $totalIui = $totalIuiLeft = $totalIuiRight = 0;
    $i = 0;
    $count = count($iui);
    // dd($count);
@endphp
@if ($count > 0)
    <tr>
        <th colspan="9" class="sub-heading">INF</th>
    </tr>
    
        @for ($i = 0; $i < $count; $i++)
            <tr>
                @php 
                    $totalIuiLeft +=  $iui[$i]->amount;
                @endphp
                <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                <td class="data-font seperator">{{ cdate($iui[$i]->created_at)->format('d-m-Y')}}</td>
                <td class="data-font seperator">{{strtoupper(@$iui[$i]->getPatients['name'])}}</td>
                <td class="data-font seperator"></td>
                <td class="data-font seperator">
                    <div class="amount">
                        {{ $iui[$i]->amount}}
                    </div>
                </td>
                <td class="{{$expense_icon}} data-font seperator">{{$iui[$i]->txt_amount}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$iui[$i]->invoice_no}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$iui[$i]->bank_id])) ? $bank_details[$iui[$i]->bank_id] : ''}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$iui[$i]->detail}}</td>
                {{-- @if ($count > 8) 
                    @php
                        $i++;
                    @endphp
                @endif
                @if ($i < $count && $count > 8) 
                    @php 
                        $totalIuiRight +=  $iui[$i]->amount;
                    @endphp
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                    <td class="data-font seperator">{{ cdate($iui[$i]->created_at)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{strtoupper(@$iui[$i]->getPatients['name'])}}</td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator">
                        <div class="amount">
                            {{ $iui[$i]->amount}}
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$iui[$i]->txt_amount}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$iui[$i]->invoice_no}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$iui[$i]->bank_id])) ? $bank_details[$iui[$i]->bank_id] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$iui[$i]->detail}}</td>
                @endif --}}
            </tr>
        @endfor
        @php
            $totalIui = $totalIuiLeft + $totalIuiRight;
        @endphp
        <tr>
            <td class="bt-none" colspan="3"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="top-border-first">{{  $totalIui }}</th>
        </tr>
    {{-- @else
        <td colspan='12' class="text-center">No records available</td> --}}
    @endif
    @php 
    $totalIvf = $totalIvfLeft = $totalIvfRight = 0;
    $i = 0;
    $count = count($ivf);
@endphp
@if ($count > 0)
    <tr>
        <th colspan="9" class="sub-heading">IVF</th>
    </tr>
   
        @for ($i = 0; $i < $count; $i++)
            <tr>
                @php 
                    $totalIvfLeft +=  $ivf[$i]->amount;
                @endphp
                <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                <td class="data-font seperator">{{ cdate($ivf[$i]->created_at)->format('d-m-Y')}}</td>
                <td class="data-font seperator">{{strtoupper(@$ivf[$i]->getPatients['name'])}}</td>
                <td class="data-font seperator"></td>
                <td class="data-font seperator">
                    <div class="amount">
                        {{ $ivf[$i]->amount}}</td>
                    </div>
                </td>
                <td class="{{$expense_icon}} data-font seperator">{{$ivf[$i]->txt_amount}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$ivf[$i]->invoice_no}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$ivf[$i]->bank_id])) ? $bank_details[$ivf[$i]->bank_id] : ''}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$ivf[$i]->detail}}</td>
                {{-- @if ($count > 8) 
                    @php
                        $i++;
                    @endphp
                @endif
                @if ($i < $count && $count > 8) 
                    @php
                        $totalIvfRight +=  $ivf[$i]->amount;
                    @endphp
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                    <td class="data-font seperator">{{ cdate($ivf[$i]->created_at)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{strtoupper(@$ivf[$i]->getPatients['name'])}}</td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator">
                        <div class="amount">
                            {{ $ivf[$i]->amount}}
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$ivf[$i]->txt_amount}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$ivf[$i]->invoice_no}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$ivf[$i]->bank_id])) ? $bank_details[$ivf[$i]->bank_id] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$ivf[$i]->detail}}</td>
                @endif --}}
            </tr>
        @endfor
        @php
            $totalIvf = $totalIvfLeft + $totalIvfRight;
        @endphp
        <tr>
            <td class="bt-none" colspan="3"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="top-border-first">{{  $totalIvf }}</th>
        </tr>
    {{-- @else
        <td colspan='12' class="text-center">No records available</td> --}}
    @endif

    @if(!empty($ivfCash) || !empty($iuiCash) || !empty($ancCash) || !empty($gynecCash))
        <tr>
            <th colspan="9" class="sub-heading">{{config('app.doctor') }}</th>
        </tr>
    @endif
    @if(!empty($ivfCash))
        <tr>
            <th colspan="9" class="sub-heading">IVF</th>
        </tr>
        @include('admin.report.ca_expense.collection_preview', [
            'data' => $ivfCash
        ])
    @endif
    @php
        $totalIvfCash = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp
    @if(!empty($iuiCash))
        <tr>
            <th colspan="9" class="sub-heading">INF</th>
        </tr>
        @include('admin.report.ca_expense.collection_preview', [
            'data' => $iuiCash
        ])
    @endif
    @php
        $totalIuiCash = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp
    @if(!empty($ancCash))
        <tr>
            <th colspan="9" class="sub-heading">ANC</th>
        </tr>
        @include('admin.report.ca_expense.collection_preview', [
            'data' => $ancCash
        ])
    @endif
    @php
        $totalAncCash = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp
    @if(!empty($gynecCash))
        <tr>
            <th colspan="9" class="sub-headline">Gynec</th>
        </tr>
        @include('admin.report.collection.collection_data', [
            'data' => $gynecCash
        ])
    @endif
    @php
        $totalGynecCash = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp

    {{-- <tr>
        <th colspan="9" class="sub-heading">NEW - OLD</th>
    </tr>
    @include('admin.report.ca_expense.collection_preview', [
        'data' => $newOldCash
    ])
    @php
        $totalNewOldCash = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp --}}

    {{-- <tr>
        <th colspan="9" class="sub-heading">OLD</th>
    </tr>
    @include('admin.report.ca_expense.collection_preview', [
        'data' => $oldCash
    ])
    @php
        $totalOldCash = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp --}}

    {{-- <tr>
        <th colspan="9" class="sub-heading">NEW - OLD</th>
    </tr>
    @include('admin.report.ca_expense.collection_preview', [
        'data' => $newOldCard
    ])
    @php
        $totalNewOldCard = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp --}}
    {{-- end new old card --}}
{{-- 
    <tr>
        <th colspan="9" class="sub-heading">OLD</th>
    </tr>
    @include('admin.report.ca_expense.collection_preview', [
        'data' => $oldCard
    ])
    @php
        $totalOldCard = (Session::get('total') != null) ? Session::get('total') : 0;
        Session::forget('total');
    @endphp --}}
    @php
        $j = $totalIndoorCash = $totalIndoorCashLeft = $totalIndoorCashRight = 0;
        $count = count($indoorCash);
    @endphp
    @if ($count > 0)
    <tr>
        <th colspan="9" class="sub-heading">Indoor</th>
    </tr>
    
        <tr>
            <th colspan="9" class="sub-heading">Invoices</th>
        </tr>
        
        @for ($i = 0; $i < $count; $i++)
            <tr>
                <td class="data-font seperator">{{ ($j = $i + 1) . '.'}}</td>
                <td class="data-font seperator">{{ !empty($indoorCash[$i]['final_invoice_date']) ? cdate($indoorCash[$i]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                <td class="data-font seperator">{{ strtoupper($indoorCash[$i]['get_patients_details']['name']) }}</td>
                <td class="data-font seperator"> @php
                    $patientProcedure = explode(',', $indoorCash[$i]['procedure_id']);
                    foreach ($procedures as $key => $value) {
                        if (in_array($value['id'], $patientProcedure)) {
                            echo $value['name'] . '<br /> ';
                        }
                    }
                @endphp</td>
                <td class="data-font seperator">
                    <div class="amount">
                        @php
                            echo $indoorCash[$i]['get_invoice']['grand_total_amt'];
                            $totalIndoorCashLeft += $indoorCash[$i]['get_invoice']['grand_total_amt'];
                        @endphp
                    </div>
                </td>
                <td class="{{$expense_icon}} data-font seperator">{{$indoorCash[$i]['get_invoice']['txt_amount']}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$indoorCash[$i]['get_invoice']['invoice_no']}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$indoorCash[$i]['get_invoice']['bank_id']])) ? $bank_details[$indoorCash[$i]['get_invoice']['bank_id']] : ''}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$indoorCash[$i]['get_invoice']['detail']}}</td>
                {{-- @php 
                    if ($count > 8) {
                        $i++;
                    }
                @endphp
                @if ($count > 8 && $i < $count)
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.'}}</td>
                    <td class="data-font seperator">{{ !empty($indoorCash[$i]['final_invoice_date']) ? cdate($indoorCash[$i]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                    <td class="data-font seperator">{{ strtoupper($indoorCash[$i]['get_patients_details']['name']) }}</td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator">
                        <div class="amount">
                            @php
                                echo $indoorCash[$i]['get_invoice']['grand_total_amt'];
                                $totalIndoorCashRight += $indoorCash[$i]['get_invoice']['grand_total_amt']
                            @endphp
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$indoorCash[$i]['get_invoice']['txt_amount']}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$indoorCash[$i]['get_invoice']['invoice_no']}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$indoorCash[$i]['get_invoice']['bank_id']])) ? $bank_details[$indoorCash[$i]['get_invoice']['bank_id']] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$indoorCash[$i]['get_invoice']['detail']}}</td>
                @endif --}}
            </tr>
        @endfor
        @php
            $totalIndoorCash = $totalIndoorCashLeft + $totalIndoorCashRight;
        @endphp
        <tr>
            <td class="bt-none" colspan="3"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="top-border-first">{{  $totalIndoorCash }}</th>
        </tr>
    @endif

    @php
        $j = $totalIndoorDepositCash = $totalIndoorDepositCashLeft = $totalIndoorDepositCashRight = 0;
        $count = count($indoorCaseDeposit);
    @endphp
    @if ($count > 0)
        <tr>
            <th colspan="9" class="sub-heading">Deposits</th>
        </tr>
        
        @for ($i = 0; $i < $count; $i++)
            <tr>
                <td class="data-font seperator">{{ ($j = $i + 1) . '.'}}</td>
                <td class="data-font seperator">{{ cdate($indoorCaseDeposit[$i]['created_at'])->format('d-m-Y')}}</td>
                <td class="data-font seperator">{{ strtoupper($indoorCaseDeposit[$i]['get_patients']['name']) }}</td>
                <td class="data-font seperator">
                    @php
                        $patientProcedure = explode(',', $indoorCaseDeposit[$i]['procedure_id']);
                        foreach ($procedures as $key => $value) {
                            if (in_array($value['id'], $patientProcedure)) {
                                echo $value['name'] . '<br /> ';
                            }
                        }
                    @endphp
                </td>
                <td class="data-font seperator">
                    <div class="amount">
                        @php
                            echo $indoorCaseDeposit[$i]['amount'];
                            $totalIndoorDepositCashLeft += $indoorCaseDeposit[$i]['amount'];
                        @endphp
                    </div>
                </td>
                <td class="{{$expense_icon}} data-font seperator">{{$indoorCaseDeposit[$i]['txt_amount']}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$indoorCaseDeposit[$i]['invoice_no']}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$indoorCaseDeposit[$i]['bank_id']])) ? $bank_details[$indoorCaseDeposit[$i]['bank_id']] : ''}}</td>
                <td class="{{$expense_icon}} data-font seperator">{{$indoorCaseDeposit[$i]['detail']}}</td>
                {{-- @php 
                    if ($count > 8) {
                        $i++;
                    }
                @endphp
                @if ($count > 8 && $i < $count)
                    <td class="data-font seperator">{{($j = $i + 1) . '.'}}</td>
                    <td class="data-font seperator">{{cdate($indoorCaseDeposit[$i]['created_at'])->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{strtoupper($indoorCaseDeposit[$i]['get_patients']['name']) }}</td>
                    <td class="data-font seperator">
                        @php
                            $patientProcedure = explode(',', $indoorCaseDeposit[$i]['procedure_id']);
                            foreach ($procedures as $key => $value) {
                                if (in_array($value['id'], $patientProcedure)) {
                                    echo $value['name'] . '<br /> ';
                                }
                            }
                        @endphp
                    </td>
                    <td class="data-font seperator">
                        <div class="amount">
                            @php
                                echo $indoorCaseDeposit[$i]['amount'];
                                $totalIndoorDepositCashRight += $indoorCaseDeposit[$i]['amount'];
                            @endphp
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$indoorCaseDeposit[$i]['txt_amount']}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$indoorCaseDeposit[$i]['invoice_no']}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$indoorCaseDeposit[$i]['bank_id']])) ? $bank_details[$indoorCaseDeposit[$i]['bank_id']] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$indoorCaseDeposit[$i]['detail']}}</td>
                @endif --}}
            </tr>
        @endfor
        @php
            $totalIndoorDepositCash = $totalIndoorDepositCashLeft + $totalIndoorDepositCashRight;
        @endphp
        <tr>
            <td class="bt-none" colspan="3"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="top-border-first">{{  $totalIndoorDepositCash }}</th>
        </tr>
    @endif
</table>
@if(count($income) != 0 || count($expense) !=0 )
    <table class="table m-b-0 table-hover category-report-table-first" cellspacing="0">
        <tr class="report-header-tr seperator">
            <th class="report-header-tr-th w-100">Sr no.</th>
            <th class="report-header-tr-th">Date</th>
            <th class="report-header-tr-th">Payment</th>
            <th class="report-header-tr-th">Category</th>
            <th class="report-header-tr-th w-100">Given For</th>
            <th class="report-header-tr-th">Notes</th>
            <th class="report-header-tr-th">Amount</th>
            @if($paymentType != 2)
            <th class="report-header-tr-th {{$expense_icon}}">Txt_Amt</th>
            <th class="report-header-tr-th {{$expense_icon}}">Invoice_No</th>
            <th class="report-header-tr-th {{$expense_icon}}">Bank</th>
            <th class="report-header-tr-th {{$expense_icon}}">Detail</th>
            @endif
            {{-- <th class="report-header-tr-th w-100">Sr no.</th>
            <th class="report-header-tr-th">Date</th>
            <th class="report-header-tr-th">Payment</th>
            <th class="report-header-tr-th">Category</th>
            <th class="report-header-tr-th w-100">Given For</th>
            <th class="report-header-tr-th">Notes</th>
            <th class="report-header-tr-th">Amount</th>
            <th class="report-header-tr-th {{$expense_icon}}">Txt_Amt</th>
            <th class="report-header-tr-th {{$expense_icon}}">Invoice_No</th>
            <th class="report-header-tr-th {{$expense_icon}}">Bank</th>
            <th class="report-header-tr-th {{$expense_icon}}">Detail</th> --}}
        </tr>
        @php 
            $totalIncome = $totalIncomeLeft = $totalIncomeRight = 0;
            $i = 0;
            $count = count($income);
        @endphp
        @if ($count > 0)
        <tr>
            <th colspan="22" class="sub-heading">Income</th>
        </tr>
        
            @for ($i = 0; $i < $count; $i++)
                <tr>
                    @php 
                        $totalIncomeLeft +=  $income[$i]->amount;
                    @endphp
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                    <td class="data-font seperator">{{ cdate($income[$i]->date)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{$income[$i]->payment_mode}}</td>
                    <td class="data-font seperator">{{$income[$i]['getExpenseCategory']->name}}</td>
                    <td class="data-font seperator">{{strtoupper($income[$i]->given_by)}}</td>
                    <td class="data-font seperator">{{$income[$i]->note}}</td>
                    <td class="data-font seperator">
                        <div class="amount">
                            {{$income[$i]->amount}}
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$income[$i]->txt_amount}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$income[$i]->invoice_no}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$income[$i]->bank_id])) ? $bank_details[$income[$i]->bank_id] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$income[$i]->detail}}</td>
                    {{-- @if ($count > 8) 
                        @php
                            $i++;
                        @endphp
                    @endif
                    @if ($i < $count && $count > 8) 
                        @php
                            $totalIncomeRight +=  $income[$i]->amount;
                        @endphp
                        <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                        <td class="data-font seperator">{{ cdate($income[$i]->date)->format('d-m-Y')}}</td>
                        <td class="data-font seperator">{{$income[$i]->payment_mode}}</td>
                        <td class="data-font seperator">{{$income[$i]['getExpenseCategory']->name}}</td>
                        <td class="data-font seperator">{{strtoupper($income[$i]->given_by)}}</td>
                        <td class="data-font seperator">{{$income[$i]->note}}</td>
                        <td class="data-font seperator">
                            <div class="amount">
                                {{$income[$i]->amount}}
                            </div>
                        </td>
                        <td class="{{$expense_icon}} data-font seperator">{{$income[$i]->txt_amount}}</td>
                        <td class="{{$expense_icon}} data-font seperator">{{$income[$i]->invoice_no}}</td>
                        <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$income[$i]->bank_id])) ? $bank_details[$income[$i]->bank_id] : ''}}</td>
                        <td class="{{$expense_icon}} data-font seperator">{{$income[$i]->detail}}</td>
                    @endif --}}
                </tr>
            @endfor
            @php
                $totalIncome = $totalIncomeLeft + $totalIncomeRight;
            @endphp
            <tr>
                <td class="bt-none" colspan="5"></td>
                <th class="bt-none" colspan="1">Total :</th>
                <th class="top-border-first">{{  $totalIncome }}</th>
            </tr>
        {{-- @else
            <td colspan='12' class="text-center">No records available</td> --}}
        @endif
        @php 
            $totalExpense = $totalExpenseLeft = $totalExpenseRight = 0;
            $i = 0;
            $count = count($expense);
        @endphp
        @if ($count > 0)
        <tr>
            <th colspan="22" class="sub-heading">Expense</th>
        </tr>
        
            @for ($i = 0; $i < $count; $i++)
                <tr>
                    @php 
                        $totalExpenseLeft +=  $expense[$i]->amount;
                    @endphp
                    <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                    <td class="data-font seperator">{{ cdate($expense[$i]->date)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{$expense[$i]->payment_mode}}</td>
                    <td class="data-font seperator">{{$expense[$i]['getExpenseCategory']->name}}</td>
                    <td class="data-font seperator">{{strtoupper($expense[$i]->given_for)}}</td>
                    <td class="data-font seperator">{{$expense[$i]->note}}</td>
                    <td class="data-font seperator">
                        <div class="amount">
                            {{$expense[$i]->amount}}
                        </div>
                    </td>
                    <td class="{{$expense_icon}} data-font seperator">{{$expense[$i]->txt_amount}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$expense[$i]->invoice_no}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$expense[$i]->bank_id])) ? $bank_details[$expense[$i]->bank_id] : ''}}</td>
                    <td class="{{$expense_icon}} data-font seperator">{{$expense[$i]->detail}}</td>
                    {{-- @if ($count > 8) 
                        @php
                            $i++;
                        @endphp
                    @endif
                    @if ($i < $count && $count > 8) 
                        @php
                            $totalExpenseRight +=  $expense[$i]->amount;
                        @endphp
                        <td class="data-font seperator">{{ ($j = $i + 1) . '.' }}</td>
                        <td class="data-font seperator">{{ cdate($expense[$i]->date)->format('d-m-Y')}}</td>
                        <td class="data-font seperator">{{$expense[$i]->payment_mode}}</td>
                        <td class="data-font seperator">{{$expense[$i]['getExpenseCategory']->name}}</td>
                        <td class="data-font seperator">{{strtoupper($expense[$i]->given_for)}}</td>
                        <td class="data-font seperator">{{$expense[$i]->note}}</td>
                        <td class="data-font seperator">
                            <div class="amount">
                                {{$expense[$i]->amount}}
                            </div>
                        </td>
                        <td class="{{$expense_icon}} data-font seperator">{{$expense[$i]->txt_amount}}</td>
                        <td class="{{$expense_icon}} data-font seperator">{{$expense[$i]->invoice_no}}</td>
                        <td class="{{$expense_icon}} data-font seperator">{{(isset($bank_details[$expense[$i]->bank_id])) ? $bank_details[$expense[$i]->bank_id] : ''}}</td>
                        <td class="{{$expense_icon}} data-font seperator">{{$expense[$i]->detail}}</td>
                    @endif --}}
                </tr>
            @endfor
            @php
                $totalExpense = $totalExpenseLeft + $totalExpenseRight;
            @endphp
            <tr>
                <td class="bt-none" colspan="5"></td>
                <th class="bt-none" colspan="1">Total :</th>
                <th class="top-border-first">{{  $totalExpense }}</th>
            </tr>
        {{-- @else
            <td colspan='12' class="text-center">No records available</td> --}}
        @endif
        {{-- @php
            $number = $totalIndoorDebitCashLeft = $totalIndoorDepositCashLeft = $totalIndoorDebitCash = $totalIndoorDebitCashRight = 0;
            $debitCount = count($indoorDebit);
        @endphp
        @if ($debitCount > 0)
            <tr>
                <th colspan="9" class="sub-headline">Debit</th>
            </tr>
            @foreach ($indoorDebit as $key => $value)
                @php
                    if ($key > 0) {
                        $number++;
                    }
                @endphp
                @if ($number < $debitCount)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ cdate($indoorDebit[$number]['created_at'])->format('d-m-Y')}}</td>
                        <td>{{ strtoupper($indoorDebit[$number]['get_patients']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorDebit[$number]['procedure_id']);
                                foreach($procedures as $key => $value){
                                    if(in_array($value['id'], $patientProcedure)){
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp

                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorDebit[$number]['amount'];
                                    $totalIndoorDebitCashLeft += $indoorDebit[$number]['amount'];
                                @endphp
                            </div>
                        </td>
                        @php 
                            if ($debitCount > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($debitCount > 8 && $number < $debitCount)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorDebit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorDebit[$number]['get_patients']['name']) }}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorDebit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorDebit[$number]['amount'];
                                        $totalIndoorDebitCashRight += $indoorDebit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorDebitCash = $totalIndoorDebitCashLeft + $totalIndoorDebitCashRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorDebitCash}}</th>
            </tr>
        @endif --}}

    </table>
@endif
<table class="table m-b-0 table-hover category-report-table-first grand-total"  style="width:40%;" >
    <?php
        $totalOpd = $totalUsg+ $totalHormon  + $totalIui + $totalIvf + $incomeGrandTotal  + $totalIvfCash + $totalIuiCash + $totalAncCash  +  $totalIndoorCash + $totalIndoorDepositCash + $totalGynecCash;
    ?>
    <tbody>
        <tr class="border-none">
            <th class="no-border text-left">OPD Total</th>
            <th class="no-border">:</th>
            <th class="text-right">{{ $totalOpd }}</th>
        </tr>
        <tr>
            <th class="no-border text-left" >Total</th>
            <th class="no-border">:</th>
            <th class="top-border-first text-right">{{ $totalOpd }}</th>
        </tr>
        <tr class="no-border">
            <th class="no-border text-left">Expense Total</th>
            <th class="no-border">:</th>
            <th class="text-right"> {{ $expenseGrandTotal }} </th>
        </tr>
        {{-- <tr class="bt-none">
            <th class="bt-none">Debit Total </th>
            <th class="bt-none">:</th>
            <th class="total-upper-border text-right"> {{ $totalIndoorDebitCash }} </th>
        </tr> --}}
        <tr>
            <th class="no-border text-left">Grand Total</th>
            <th class="no-border">:</th>
            <th class="top-border-first text-right"> {{$totalOpd - $expenseGrandTotal}} </th>
        </tr>
    </tbody>
</table>