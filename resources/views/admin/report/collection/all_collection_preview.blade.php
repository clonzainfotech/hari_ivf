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
    height: 25px;
    font-size: 12px;
    text-align:center;
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
    -webkit-print-color-adjust: exact;
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
    border: 1px #000000 solid;
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
    -webkit-print-color-adjust: exact;
    font-size: 13px;
    line-height: 7px;
    padding-left:12px;
    padding-right:12px;
    border:1px solid;
}

.data-font {
    font-size: 11px;
}
.seperator {
    border: 1px solid black;
}

tr td th {
    padding: 12px 12px;
}
</style>
<!-- usg -->
@php 
            $count = count($usg);
            $monthUsgArray = [];
            $totalUsg = 0;
            $totalOpd = 0;
            $totalIpd = 0;
            $totalIncome = 0;
            $totalExpense = 0;
        @endphp
        @if ($count > 0)
            @foreach ($usg as $key => $value)
                @php 
                   
                    $date = cdate($usg[$key]['date'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['usg']))
                    {
                        $monthUsgArray[$date]['usg'] += $usg[$key]['getAppointmentCharges']['usg'];
                    }
                    else{
                        $monthUsgArray[$date]['usg'] = $usg[$key]['getAppointmentCharges']['usg'];
                    }
                    $totalUsg += $usg[$key]['getAppointmentCharges']['usg'];
                @endphp
            @endforeach
        @endif
        <!-- //hormon -->
        @php 
            $number = $totalHormon = $totalHormonLeft = $totalHormonRight = 0;
            $count = count($hormon);
            $totalHormon = 0;
        @endphp
        @if ($count > 0)
            @foreach ($hormon as $key => $value)
                @php 
                    $date = cdate($hormon[$key]['created_at'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['hormon']))
                    {
                        $monthUsgArray[$date]['hormon'] += $hormon[$key]['amount'];
                    }
                    else{
                        $monthUsgArray[$date]['hormon'] = $hormon[$key]['amount'];
                    }
                    $totalHormon += $hormon[$key]['amount'];
                @endphp
                
            @endforeach
        @endif
        @php 
        $number = $totalIui = $totalIuiLeft = $totalIuiRight = 0;
        $count = count($iui);
        $totalIui = 0;
    @endphp
    <!-- //iui -->
    @if ($count > 0)
            @foreach ($iui as $key => $value)
                @php
                   
                    $date = cdate($iui[$key]['created_at'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['iui']))
                    {
                        $monthUsgArray[$date]['iui'] += $iui[$key]['amount'];
                    }
                    else{
                        $monthUsgArray[$date]['iui'] = $iui[$key]['amount'];
                    }
                    $totalIui +=  $iui[$key]['amount'];
                @endphp
            @endforeach
        @endif
        <!-- //ivf -->
        @php 
            $count = count($ivf);
            $totalIvf = 0;
        @endphp
        @if ($count > 0)
        
            @foreach($ivf as $key => $value)
                @php 
                    $date = cdate($ivf[$key]['created_at'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['ivf']))
                    {
                        $monthUsgArray[$date]['ivf'] += $ivf[$key]['amount'];
                    }
                    else{
                        $monthUsgArray[$date]['ivf'] = $ivf[$key]['amount'];
                    }
                    $totalIvf += $ivf[$key]['amount'];
                @endphp
            @endforeach
        @endif
        <!-- //opd -->
        @if(!empty($ivfCash))
            @foreach($ivfCash as $key => $value)
                @php
                    if(isset($ivfCash[$key]))
                    {
                        $date = cdate($ivfCash[$key]['date'])->format('d-m-Y');
                        if(isset($monthUsgArray[$date]['opd']))
                        {
                            $monthUsgArray[$date]['opd'] += $ivfCash[$key]['get_appointment_charges']['total'] - $ivfCash[$key]['get_appointment_charges']['usg'];
                        }
                        else{
                            $monthUsgArray[$date]['opd'] = $ivfCash[$key]['get_appointment_charges']['total']- $ivfCash[$key]['get_appointment_charges']['usg'];
                        }
                        $totalOpd += $ivfCash[$key]['get_appointment_charges']['total'] - $ivfCash[$key]['get_appointment_charges']['usg'];
                    }
                @endphp
            @endforeach
        @endif
        @if(!empty($iuiCash))
            @foreach($iuiCash as $key => $value)
                @php
                    if(isset($iuiCash[$key]))
                    {
                    $date = cdate($iuiCash[$key]['date'])->format('d-m-Y');

                        if(isset($monthUsgArray[$date]['opd']))
                        {
                            $monthUsgArray[$date]['opd'] += $iuiCash[$key]['get_appointment_charges']['total'] - $iuiCash[$key]['get_appointment_charges']['usg'];
                        }
                        else{
                            $monthUsgArray[$date]['opd'] = $iuiCash[$key]['get_appointment_charges']['total']- $iuiCash[$key]['get_appointment_charges']['usg'];
                        }
                        $totalOpd += $iuiCash[$key]['get_appointment_charges']['total'] - $iuiCash[$key]['get_appointment_charges']['usg'];
                    }
                @endphp
            @endforeach
        @endif
        @if(!empty($ancCash))
            @foreach($ancCash as $key => $value)
                @php
                    if(isset($ancCash[$key]))
                    {
                    $date = cdate($ancCash[$key]['date'])->format('d-m-Y');

                        if(isset($monthUsgArray[$date]['opd']))
                        {
                            $monthUsgArray[$date]['opd'] += $ancCash[$key]['get_appointment_charges']['total'] - $ancCash[$key]['get_appointment_charges']['usg'];
                        }
                        else{
                            $monthUsgArray[$date]['opd'] = $ancCash[$key]['get_appointment_charges']['total']- $ancCash[$key]['get_appointment_charges']['usg'];
                        }
                        $totalOpd +=  $ancCash[$key]['get_appointment_charges']['total'] - $ancCash[$key]['get_appointment_charges']['usg'];
                    }
                @endphp
            @endforeach
        @endif
        @if(!empty($gynecCash))
            @foreach($gynecCash as $key => $value)
                @php
                    if(isset($gynecCash[$key]))
                    {
                    $date = cdate($gynecCash[$key]['date'])->format('d-m-Y');

                        if(isset($monthUsgArray[$date]['opd']))
                        {
                            $monthUsgArray[$date]['opd'] += $gynecCash[$key]['get_appointment_charges']['total'] - $gynecCash[$key]['get_appointment_charges']['usg'];
                        }
                        else{
                            $monthUsgArray[$date]['opd'] = $gynecCash[$key]['get_appointment_charges']['total']- $gynecCash[$key]['get_appointment_charges']['usg'];
                        }
                        $totalOpd += $gynecCash[$key]['get_appointment_charges']['total'] - $gynecCash[$key]['get_appointment_charges']['usg'];
                    }
                @endphp
            @endforeach
        @endif
         <!-- //ipd   -->
        @php
        $count = count($indoorCash);
        @endphp
    @if ($count > 0)
            @foreach ($indoorCash as $key => $value)
                @php
                    
                    if(isset($indoorCash[$key]) && !empty($indoorCash[$key]['final_invoice_date']))
                    {
                        $date = cdate($indoorCash[$key]['final_invoice_date'])->format('d-m-Y');

                        if(isset($monthUsgArray[$date]['ipd']))
                        {
                            $monthUsgArray[$date]['ipd'] += $indoorCash[$key]['get_invoice']['grand_total_amt'];
                        }
                        else{
                            $monthUsgArray[$date]['ipd'] = $indoorCash[$key]['get_invoice']['grand_total_amt'];
                        }
                        $totalIpd +=  $indoorCash[$key]['get_invoice']['grand_total_amt'];
                    }
                @endphp
                    
            @endforeach
        @endif
        @php
            $count = count($indoorCaseDeposit);
        @endphp
        @if ($count > 0)
            @foreach ($indoorCaseDeposit as $key => $value)
                @php
                    
                    if(isset($indoorCaseDeposit[$key]))
                    {
                        $date = cdate($indoorCaseDeposit[$key]['created_at'])->format('d-m-Y');

                        if(isset($monthUsgArray[$date]['ipd']))
                        {
                            $monthUsgArray[$date]['ipd'] += $indoorCaseDeposit[$key]['amount'];
                        }
                        else{
                            $monthUsgArray[$date]['ipd'] = $indoorCaseDeposit[$key]['amount'];
                        }
                        $totalIpd += $indoorCaseDeposit[$key]['amount'];
                    }
                @endphp
                
            @endforeach
        @endif

        <!-- income & expense -->
@if(count($income) != 0 || count($expense) !=0 )
            @php 
                $number = $totalIncome = $totalIncomeLeft = $totalIncomeRight = 0;
                $count = count($income);
            @endphp
            @if ($count > 0)
                @foreach ($income as $key => $value)
                    @php 
                        
                        $date = cdate($income[$key]['date'])->format('d-m-Y');

                        if(isset($monthUsgArray[$date]['income']))
                        {
                            $monthUsgArray[$date]['income'] += $income[$key]['amount'];
                        }
                        else{
                            $monthUsgArray[$date]['income'] = $income[$key]['amount'];
                        }
                        $totalIncome += $income[$key]['amount'];
                    @endphp
                @endforeach
            @endif
        @php 
            $count = count($expense);
        @endphp
        @if ($count > 0)
                @foreach ($expense as $key => $value)
                    @php 
                        $date = cdate($expense[$key]['date'])->format('d-m-Y');
                        if(isset($monthUsgArray[$date]['expense']))
                        {
                            $monthUsgArray[$date]['expense'] += $expense[$key]['amount'];
                        }
                        else{
                            $monthUsgArray[$date]['expense'] = $expense[$key]['amount'];
                        }
                        $totalExpense += $expense[$key]['amount'];
                    @endphp
                @endforeach
            @endif
@endif
<table class="table m-b-0 table-hover category-report-table-first font" cellspacing="0">
    <thead>
            <?php
                $first_key = array_key_first($monthUsgArray);
            ?>
        <tr>
            <th colspan="12" class="text-center">{{strtoupper(config('app.hospitalname1'))}}</th>
        </tr>
        <tr>
            <th colspan="12" class="text-center">New Collection Report - {{cdate($first_key)->format('M Y')}}</th>
            
        </tr>
    </thead>
    
    <tr class="report-header-tr">
        <th class="report-header-tr-th w-100">Date</th>
        <th class="report-header-tr-th">USG</th>
        <th class="report-header-tr-th">H. INJ</th>
        <th class="report-header-tr-th">IUI</th>
        <th class="report-header-tr-th">IVF</th>
        <th class="report-header-tr-th">OPD</th>
        <th class="report-header-tr-th">IPD</th>
        <th class="report-header-tr-th">O.INCOME</th>
        <th class="report-header-tr-th">Expense</th>
    </tr>
        @php
            ksort($monthUsgArray);
        @endphp
        @foreach($monthUsgArray as $key => $value)
    <tr>
        <td  class="data-font seperator">{{$key}}</td>
        <td  class="data-font seperator">{{isset($value['usg']) ? $value['usg'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['hormon']) ? $value['hormon'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['iui']) ? $value['iui'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['ivf']) ? $value['ivf'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['opd']) ? $value['opd'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['ipd']) ? $value['ipd'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['income']) ? $value['income'] : '-'}}</td>
        <td class="data-font seperator">{{isset($value['expense']) ? $value['expense'] : '-'}}</td>
    </tr>
    @endforeach
    <tr>
        <td class="top-border-first amount">Total:</td>
        <td class="top-border-first amount">{{$totalUsg}}</td>
        <td class="top-border-first amount">{{$totalHormon}}</td>
        <td class="top-border-first amount">{{$totalIui}}</td>
        <td class="top-border-first amount">{{$totalIvf}}</td>
        <td class="top-border-first amount">{{$totalOpd}}</td>
        <td class="top-border-first amount">{{$totalIpd}}</td>
        <td class="top-border-first amount">{{$totalIncome}}</td>
        <td class="top-border-first amount">{{$totalExpense}}</td>
    </tr>
    <?php $totalGrandIncome = $totalUsg + $totalHormon + $totalIui + $totalIvf + $totalOpd + $totalIpd + $totalIncome; ?>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="amount">Total Income:</td>
        <td class="amount">{{$totalGrandIncome}}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="amount">Total Expense:</td>
        <td class="amount" >{{$totalExpense}}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="amount">Total</td>
        <td class="top-border-first amount">{{$totalGrandIncome-$totalExpense}}</td>
        <td></td>
    </tr>
</table>
<div class="row" style="display: flex;">
    <div class="col-md-6">
        <table class="table m-b-0 table-hover category-report-table-first font">
            <tr class="bt-none">
                <th class="bt-none">New Collection Income</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $totalGrandIncome }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Pediatric Total Income</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $pediatric_income }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Medicare Total Income</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $medicare_income }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total Income</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{ $totalGrandIncome + $pediatric_income + $medicare_income}} </th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $totalExpense + $pediatric_expense + $medicare_expense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Grand Total</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{($totalGrandIncome + $pediatric_income + $medicare_income) - ($totalExpense + $pediatric_expense + $medicare_expense)}} </th>
            </tr>
        </table>
    </div> 
    <div class="col-md-6">
        <table class="table m-b-0 table-hover category-report-table-first font">
            <tr class="bt-none">
                <th class="bt-none">New Collection Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $totalExpense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Pediatric Total Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $pediatric_expense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Medicare Total Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $medicare_expense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total Expense</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{ $totalExpense + $pediatric_expense + $medicare_expense}} </th>
            </tr>
        </table>
    </div> 
</div>
