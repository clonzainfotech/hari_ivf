<style type="text/css">
    .category-report-table-first, .category-report-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .category-report-table-first{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .category-report-table{
        text-align: left;
    }
    .category-report-table-first tr{
        height: 30px;
        font-size: 15px;
    }
    .doctor-category{
        color: #01d8da;
        -webkit-print-color-adjust: exact;
    }
    .category-report-table thead th{
        height: 35px;
    }
    .category-report-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .category-report-table tr {
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
    .data-font {
        font-size: 11px;
    }
    
    .td-padding {
        padding: 12px 12px;
    }
    .sub-heading {
        font-size: 13px;
    }
    
    .seperator {
        border-top: 0.5px solid #dee2e6;
    }
    
    tr td th {
        padding: 12px 12px;
    }
    </style>
    <table class="table m-b-0 table-hover category-report-table-first" id="category-report-table" cellspacing="0">
       
        <thead>
            <tr>
                <th colspan="6">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
            <tr>
                <th>Medicare Collection Report</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover category-report-table" id="category-report-table" cellspacing="0">
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Date</th>
                <th class="report-header-tr-th">Patient</th>
                <th class="report-header-tr-th">Category</th>
                <th class="report-header-tr-th">Given For</th>
                <th class="report-header-tr-th">Note</th>
                <th class="report-header-tr-th">Added By</th>
                <th class="report-header-tr-th">Amount</th>
            </tr>
            
        </thead>
        @php 
            $totalOpd = $totalIpd = $expenseGrandTotal = 0;
            $j = 1;
        @endphp
        <tbody>
            @forelse($income as $rowlist => $data)
                <tr class="refdocdata">
                    <td colspan="6" class="sub-headline amount">{{ ucWords(strtolower($rowlist))}}</td>
                </tr>
                @php
                    $total = 0;
                @endphp
                @foreach($data as $row)
                    <tr>
                        <td class="data-font seperator">{{ ($j++) . '.' }}</td>
                        <td class="data-font seperator">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td class="data-font seperator">{{ucWords(strtolower($row->getPatient['name']))}}</td>
                        <td class="data-font seperator"></td>
                        <td class="data-font seperator">{{$row->given_by}}</td>
                        <td class="data-font seperator">{{$row->note}}</td>
                        <td class="data-font seperator">{{$row->getUser['name']}}</td>
                        <td class="data-font seperator">
                            <div class="amount">
                                {{$row->amount}}
                                @php
                                $totalOpd += $row->amount; 
                                @endphp
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="bt-none" colspan="6"></td>
                    <th class="bt-none" colspan="1">Total :</th>
                    <th class="top-border-first upper-border">{{  $totalOpd }}</th>
                </tr>
            @empty
                <td colspan="8" class="text-center">No records available</td>
            @endforelse
            <tr>
                <td colspan="6"  class="sub-headline amount">IPD Income</td>
            </tr>
            @foreach($indoorCaseDeposit as $rowList => $data)
                <tr>
                    <td class="data-font seperator">{{$j}}</td>
                    <td class="data-font seperator">{{\Carbon\Carbon::parse($data->created_at)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{ucWords(strtolower($data->getPatientsDetails['name']))}}</td>
                    <td class="data-font seperator">{{$data->procedure_name}}</td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator">{{$data->amount}}</td>
                </tr>
                @php
                    $j++;
                    $totalIpd += $data->amount;
                @endphp
            @endforeach
            @forelse($indoorBook as $rowlist => $data)
                <tr>
                    <td class="data-font seperator">{{$j}}</td>
                    <td class="data-font seperator">{{\Carbon\Carbon::parse($data->date)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{ucWords(strtolower($data->getPatientsDetails['name']))}}</td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator"></td>
                    <td class="data-font seperator">{{$data->getInvoice['grand_total_amt']}}</td>
                </tr>
                @php
                    $j++;
                    $totalIpd += $data->getInvoice['grand_total_amt'];
                @endphp  
            @empty
                <td colspan="8" class="text-center">No records available</td>
                
            @endforelse
            <tr>
                <td colspan="7"></td>
                <td class="sub-headline upper-border">{{$totalIpd}}</td>
            </tr>
        </tbody>
    </table>
    <table class="table m-b-0 table-hover category-report-table" id="category-report-table" cellspacing="0">
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Date</th>
                <th class="report-header-tr-th">Patient</th>
                <th class="report-header-tr-th">Given For</th>
                <th class="report-header-tr-th">Note</th>
                <th class="report-header-tr-th">Added By</th>
                <th class="report-header-tr-th">Amount</th>
            </tr>
            
        </thead>
        @php 
            // $i = 1;
            $expenseGrandTotal = 0;
            $totalExpense = 0;
        @endphp
        <tbody>
            @forelse($expense as $rowlist => $data)
                <tr class="refdocdata">
                    <td colspan="6" class="sub-headline amount">{{ ucWords(strtolower($rowlist))}}</td>
                </tr>
               
                @foreach($data as $row)
                    <tr>
                        <td class="data-font seperator">{{ ($j++) . '.' }}</td>
                        <td class="data-font seperator">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td class="data-font seperator">{{ucWords(strtolower($row->getPatient['name']))}}</td>
                        <td class="data-font seperator">{{$row->given_for}}</td>
                        <td class="data-font seperator">{{$row->note}}</td>
                        <td class="data-font seperator">{{$row->getUser['name']}}</td>
                        <td class="data-font seperator">
                            <div class="amount">
                                {{$row->amount}}
                                @php
                                $totalExpense += $row->amount;
                                $expenseGrandTotal += $totalExpense;   
                                @endphp
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="bt-none" colspan="5"></td>
                    <th class="bt-none" colspan="1">Total :</th>
                    <th class="top-border-first upper-border">{{  $totalExpense }}</th>
                </tr>
            @empty
                <td colspan="8" class="text-center">No records available</td>
            @endforelse
            
        </tbody>
    </table>
    <table class="table m-b-0 table-hover category-report-table-first grand-total"  style="width:40%;" >
        
        <tbody>
            <tr class="border-none">
                <th class="no-border text-left">OPD Total</th>
                <th class="no-border">:</th>
                <th class="text-right">{{ $totalOpd }}</th>
            </tr>
            <tr class="border-none">
                <th class="no-border text-left">IPD Total</th>
                <th class="no-border">:</th>
                <th class="text-right">{{ $totalIpd }}</th>
            </tr>
            <tr>
                <th class="no-border text-left" >Total</th>
                <th class="no-border">:</th>
                <th class="top-border-first text-right upper-border">{{ $totalOpd + $totalIpd}}</th>
            </tr>
            <tr class="no-border">
                <th class="no-border text-left">Expense Total</th>
                <th class="no-border">:</th>
                <th class="text-right"> {{ $expenseGrandTotal }} </th>
            </tr>
            <tr>
                <th class="no-border text-left">Grand Total</th>
                <th class="no-border">:</th>
                <th class="top-border-first text-right upper-border"> {{($totalOpd + $totalIpd) - $expenseGrandTotal}} </th>
            </tr>
        </tbody>
    </table>
    