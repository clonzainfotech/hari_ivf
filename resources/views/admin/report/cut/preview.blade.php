<style type="text/css">
.category-report-table-first, .category-report-table{
    font-family: 'Montserrat', Arial, Tahoma, sans-serif;
    width: 100%;
}
.category-report-table-first{
    border-bottom: 1px solid #000;
    margin-bottom: 10px;
}
.category-report-table{
    text-align: left;
}
.category-report-table-first tr{
    height: 50px;
    font-size: 20px;
}
.category-report-table tr {
    height: 35px;
}
.doctor-category{
    color: #01d8da;
}
.category-report-table thead th{
    height: 35px;
}
.category-report-table thead th span{
    border-bottom: 1px solid #000000;
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
.charges {
    font-weight: 600;
}
.procedure-value {
    font-weight: 600;
}
.text-center {
    text-align: center;
}
.report-header-tr {
    text-align: left;
    height: 35px;
}
.report-header-tr-th {
    background-color: #c7dfe0;
    font-size: 13px;
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
</style>
<table class="table m-b-0 table-hover category-report-table-first" id="category-report-table">
    <thead>
        <tr>
            <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
        </tr>
        <tr>
            <th>CUT Report</th>
        </tr>
    </thead>
</table>
<table class="table m-b-0 table-hover category-report-table" id="category-report-table" cellspacing="0">
    <thead>
        <tr class="report-header-tr">
            <th class="report-header-tr-th">Sr No</th>
            <th class="report-header-tr-th">Date</th>
            <th class="report-header-tr-th">Patient Name</th>
            <th class="report-header-tr-th">Mobile</th>
            <th class="report-header-tr-th">Procedure</th>
            <th class="report-header-tr-th">Received Amt</th>
            <th class="report-header-tr-th">CUT Amt(30%)</th>
        </tr>

    </thead>
    <tbody>
    @php
        $i = 1;
        $totalIui = $grandTotal = $grandCutTotal = 0;
    @endphp
    @forelse ($cutReport as $cut => $rowList)
        @php 
            $doctorName = (strtolower(substr($cut, 0, 3)) == 'dr.') ? ucwords(strtolower(substr($cut, 3))) : ucwords(strtolower($cut));
        @endphp
        <tr>
            <th colspan="7">{{  'Dr. ' . $doctorName  }}</th>
        </tr>
        @php $totalNetAmount = $totalCutAmount = 0; @endphp
        @foreach ($rowList as $row)
            <tr>
                @php
                    $totalNetAmount += ($row->charge_type == 3) ? $row->amount : (($row->is_final_invoice == 1) ? $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : $row->netamount);
                    $amount = ($row->charge_type == 3) ? $row->amount : (($row->is_final_invoice == 1) ? $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : $row->netamount);
                    $totalCutAmount += round(($amount * 30 / 100));
                    $patient_name = ($row->charge_type == 3) ? strtoupper(@$row->getPatients['name']) : (($row->is_final_invoice == 1) ? strtoupper($row->getPatientsDetails['name']) : strtoupper($row->getAppointment->getPatientsDetails['name'])) ;
                    $mobile = ($row->charge_type == 3) ? @$row->getPatients['mobile_number'] : (($row->is_final_invoice == 1) ? $row->getPatientsDetails['mobile_number'] : $row->getAppointment->getPatientsDetails['mobile_number']) ;
                @endphp
                <td class="data-font seperator">{{ ($i + 1) . '.' }}</td>
                <td class="data-font seperator">{{ ($row->final_invoice_date) ? cdate($row->final_invoice_date)->format('d-m-Y') : cdate($row->created_at)->format('d-m-Y') }}</td>
                <td class="data-font seperator">{{ $patient_name}}</td>
                <td class="data-font seperator">{{ $mobile}}</td>
                <td class="data-font seperator">
                    @if ($row->charge_type == 3)
                        IUI
                    @elseif($row->procedure_id)
                        {{isset($row->procedure_name) ? $row->procedure_name : ''}}
                    @else
                        @if ($row->total = 0)
                            -
                        @endif
                        @if ($row->consulting_charges > 0)
                            Consulting Charges: <span class="charges">{!! $row->consulting_charges . '<br/>' !!}</span>
                        @endif
                        @if ($row->cut > 0)
                             CUT: <span class="charges">{!! $row->cut . '<br />'  !!} </span>
                        @endif
                        @if ($row->nst > 0)
                             NST: <span class="charges">{!! $row->nst . '<br/>' !!} </span>
                        @endif
                        @if ($row->usg > 0)
                             USG: <span class="charges">{!! $row->usg . '<br/>' !!} </span>
                        @endif
                        @if ($row->ivf > 0)
                             IVF: <span class="charges">{!! $row->ivf . '<br/>' !!} </span>
                        @endif
                        @if ($row->dressing > 0)
                             Dressing: <span class="charges">{!! $row->dressing . '</br>' !!} </span>
                        @endif
                    
                        @php
                            $extraField = @unserialize($row->extra_field);
                            $extraField1 = is_array($extraField) && isset($extraField[0]) ? $extraField[0] : null;
                            $extraField2 = is_array($extraField) && isset($extraField[1]) ? $extraField[1] : null;
                            if(is_array($extraField1) && !empty($extraField1[0]) && !empty($extraField1[1]) && $extraField1[1] > 0)
                            {

                                echo ucfirst($extraField1[0]) . ': <span class="charges">' . $extraField1[1] . '</span><br/>';
                            }
                            if(is_array($extraField2) && !empty($extraField2[0]) && !empty($extraField2[1]) && $extraField2[1] > 0)
                            {
                                echo ucfirst($extraField2[0]) . ': <span class="charges">' .  $extraField2[1] . '</span>';
                            }
                        @endphp
                    @endif
                </td>
                <td class="data-font seperator">
                    <div class="charges">
                        @if ($row->charge_type == 3)
                            {{$row->amount}}
                        @elseif($row->is_final_invoice == 1)
                            {{(!empty($row->getInvoice['grand_total_amt']) || !empty($row->getInvoice['deposit_amt'])) ?  $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : 0}}
                        @else
                            {{$row->netamount}}
                        @endif
                    </div>
                </td>
                <td class="data-font seperator">
                    <div class="charges">
                        {{round(($amount*30)/100)}}
                    </div>
                </td>
                @php
                    $i++;    
                @endphp
            </tr>
            
        @endforeach
        <tr>
            <th colspan="4"></th>
            <th colspan="1">Total :</th>
            <th class="upper-border" colspan="1"> {{$totalNetAmount}}</th>
            <th class="upper-border">{{$totalCutAmount}}</th>
            @php
                $grandTotal += $totalNetAmount;
                $grandCutTotal += $totalCutAmount;
            @endphp
        </tr>
    @empty
        <td colspan="7" class="text-center">No records available</td>
    @endforelse
    @if ($grandTotal > 0)
        <tr>
            <th colspan="4"></th>
            <th colspan="1">Grand Total :</th>
            <th class="upper-border" colspan="1"> {{$grandTotal}}</th>
            <th class="upper-border">{{$grandCutTotal}}</th>
        </tr>
    @endif
    </tbody>
</table>
