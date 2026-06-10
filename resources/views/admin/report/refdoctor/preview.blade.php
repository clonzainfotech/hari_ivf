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
    .category-report-table thead th{
        height: 35px;
        font-size: 14px;
        padding-left: 10px;
        padding-right: 10px;
    }
    .category-report-table tr {
        height: 35px;
    }
    tr td th {
        padding: 12px 12px;
    }
    .category-report-table thead th span{
        border-bottom: 1px solid #000;
    }
    td{
        font-size: 12px;
    }
    .bt-none{
        border-top: none !important;
    }
    .net-total{
        border-top: 1px solid #ddd;
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
    .text-center {
        text-align: center;
    }
    .charges {
        font-weight: 600;
    }
    .data-font {
        font-size: 11px;
    }
    .amount {
        font-weight: 600;
    }
    .sub-heading {
        font-size: 13px;
    }
    .seperator {
        border-top: 0.5px solid #dee2e6;
    }
</style>
 <table class="table m-b-0 table-hover category-report-table-first" id="category-report-table" cellspacing="0">
    <thead>
        <tr>
            <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
        </tr>
        <tr>
            <th>Reference Doctor Wise Report</th>
        </tr>
    </thead>
</table>
<table class="table m-b-0 table-hover category-report-table" id="category-report-table" cellspacing="0">
    <thead>
        <tr class="report-header-tr seperator">
            <th class="report-header-tr-th">Sr No</th>
            <th class="report-header-tr-th">Date</th>
            <th class="report-header-tr-th">Patient Name</th>
            <th class="report-header-tr-th">category</th>
            <th class="report-header-tr-th">mobile </th>
            <th class="report-header-tr-th">Service Given</th>
            <th class="report-header-tr-th">Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $i = 1;
        $grandTotal = $grandTotal = 0;  
    ?>
    @forelse ($refDoctorReport as $refdoctor => $rowList)
        @php 
            $doctorName = (strtolower(substr($refdoctor, 0, 3)) == 'dr.') ? ucwords(strtolower(substr($refdoctor, 3))) : ucwords(strtolower($refdoctor));
            $totalNetAmount = 0;
        @endphp
        <tr>
            <th colspan="7" class="sub-heading">{{ 'Dr. ' . $doctorName }}</th>
        </tr>

        @foreach ($rowList as $row)
            @php
                $totalNetAmount += ($row->charge_type == 3) ? $row->amount : $row->netamount;
            @endphp
            <tr>
                <td class="data-font seperator">{{ ($i++) . '.' }}</td>
                <td class="data-font seperator">{{ cdate($row->created_at)->format('d-m-Y') }}</td>
                <td class="data-font seperator">{{ ($row->charge_type == 3) ? strtoupper(@$row->getPatients['name']) : strtoupper(@$row->getAppointment->getPatientsDetails['name']) }}</td>
                <td class="data-font seperator">{{ ($row->charge_type == 3) ? $row->getPatients['mobile_number'] : @$row->getAppointment->getPatientsDetails['mobile_number']}}</td>
                <td class="data-font seperator">{{ucfirst(@$row->getAppointment->categoryDetails['name'])}}</td>
                <td class="data-font seperator">
                    @if ($row->charge_type == 3)
                        IUI
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
                        @else
                            {{$row->netamount}}
                        @endif
                    </div>
                </td>
                @php
                    $i++;    
                @endphp
            </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
            <th colspan="1">Total :</th>
            <th class="upper-border">{{$totalNetAmount}}</th>
            @php 
                $grandTotal += $totalNetAmount; 
            @endphp
        </tr>
    @empty
        <td colspan="7" class="text-center">No records available</td>
    @endforelse
    @if ($grandTotal > 0)
        <tr>
            <td colspan="5"></td>
            <th colspan="1">Grand Total :</th>
            <th class="upper-border">{{$grandTotal}}</th>
        </tr>
    @endif
    </tbody>
</table>
