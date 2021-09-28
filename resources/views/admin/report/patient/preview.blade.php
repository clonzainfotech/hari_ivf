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
.category-report-table tr {
    height: 35px;
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
.category-report-table thead th span{
    border-bottom: 1px solid #000;
}
td{
    height: 25px;
    font-size: 12px;
    padding-left: 10px;
    padding-right: 10px;
}
.ttc{
    text-transform: uppercase;
}
.ref-doctor-name{
    background-color: #dddddd;
    color: #000;
    padding: 5px 10px !important;
}
.bt-none{
    border-top: none !important;
}
.net-total{
    border-top: 1px solid #ddd;
}
tr th.top-border-second {
    border-top: #000000 double;
}
.upper-border {
    border-top: 1px solid #000000;
}
.ttc{
    text-transform: uppercase;
}
.ref-doctor-name{
    background-color: #c7dfe0;
    color:black;
    font-size: 16px;
    padding: 12px 12px;
    height: 30px;
    font-weight: 700;
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
.sub-heading {
    font-size: 13px !important;
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
            <th>{{strtoupper(config('app.hospitalname1'))}}</th>
        </tr>
        <tr>
            <th>Patient Report</th>
        </tr>
    </thead>
</table>
<table class="table m-b-0 table-hover category-report-table" id="category-report-table" cellspacing="0">
    <thead>
        <tr class="report-header-tr seperator">
            <th class="report-header-tr-th">Sr No</th>
            <th class="report-header-tr-th">Date</th>
            <th class="report-header-tr-th">Category</th>
            <th class="report-header-tr-th">Deposit</th>
            <th class="report-header-tr-th">Service Given</th>
            <th class="report-header-tr-th">Payment</th>
            <th class="report-header-tr-th">Amount</th>
            
        </tr>
    </thead>
    <tbody>
        @php
                $i = 1;
                $totalIncome = 0;
            @endphp
        @forelse($patientReportOpd as $rowList => $data)
            @if (count($patientReportOpd) > 0)
                <tr>
                    <th colspan="6" class="sub-heading">{{ ucWords(strtolower($rowList)) }}</th>
                </tr>
            @endif
            @php
                $grandTotal = 0;
            @endphp
            @if (count($patientReportOpd) > 0)
                @forelse($data as $row)
                    <tr data-id="{{encrypt($row->id)}}">

                        <td class="data-font seperator">{{ ($i++) . '.' }}</td>
                        <td class="data-font seperator">{{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}</td>
                        <td class="data-font seperator">
                            @if(isset($row['is_final_invoice']) && isset($row['amount']))
                                IPD
                            @elseif(isset($row['amount']))
                                {{ucfirst($row->getPatients->lastAppointmentData->categoryDetails['name'])}}
                            @else
                                {{ucfirst($row->categoryDetails['name'])}}
                            @endif
                        </td>
                        <td class="data-font seperator">{{isset($row['amount']) ? 'Yes' : 'No'}}</td>
                        <td class="data-font seperator">
                            @if(isset($row['amount']))
                                @php
                                    $patientProcedure = explode(',', $row['procedure_id']);
                                        foreach($procedures as $key => $value) {
                                            if(in_array($value['id'], $patientProcedure)) {
                                                echo $value['name'] . '<br /> ';
                                            }
                                        }
                                @endphp
                            @else
                                OPD
                            @endif
                        </td>
                        <td class="data-font seperator">
                            @if(isset($row['amount']))
                                {{$row->payment_type == 1 ? 'Card' : 'Cash'}}
                            @else
                                {{($row->getAppointmentCharges['payment_mode'] == 1) ? 'Card' : 'Cash'}}
                            @endif
                        </td>
                        <th class="seperator">
                            @php
                                $consultingCharges = $nst = $cut = $usg = $dressing = $ivf = $total = 0;
                                if ($row->getAppointmentCharges != null) {
                                    if ($row->getAppointmentCharges['consulting_charges'] != null) {
                                        $total += $row->getAppointmentCharges['consulting_charges'];
                                    }

                                    if ($row->getAppointmentCharges['nst'] != null) {
                                        $total += $row->getAppointmentCharges['nst'];
                                    }

                                    if ($row->getAppointmentCharges['cut'] != null) {
                                        $total += $row->getAppointmentCharges['cut'];
                                    }

                                    if ($row->getAppointmentCharges['usg'] != null) {
                                        $total += $row->getAppointmentCharges['usg'];
                                    }

                                    if ($row->getAppointmentCharges['dressing'] != null) {
                                        $total += $row->getAppointmentCharges['dressing'];
                                    }

                                    if ($row->getAppointmentCharges['ivf'] != null) {
                                        $total += $row->getAppointmentCharges['ivf'];
                                    }

                                    if ($row->getAppointmentCharges['extra_field'] != null) {
                                        $extraField = unserialize($row->getAppointmentCharges['extra_field']);
                                        $extraField1 = $extraField[0];
                                        $extraField2 = $extraField[1];
                                        if($extraField1[1] != null) {
                                            $total += $extraField1[1];
                                        }
                                        if($extraField2[1] != null) {
                                            $total += $extraField2[1];
                                        }
                                    }
                                }
                                if(isset($row['amount'])){
                                    $total += $row['amount'];
                                }else{
                                    $total;
                                }
                                $grandTotal += $total;
                                $totalIncome +=$total;
                            @endphp
                                {{$total}}
                        </th>
                    </tr>
                @empty
                @endforelse
            @endif
            <tr class="table-footer">
                <td colspan="5"></td>
                <th colspan="1">Total : </th>
                <th colspan="1" class="upper-border">{{$grandTotal}}</td>
            </tr>
        @empty
        <td colspan="7" class="text-center cutdata">No records available</td>
        @endforelse
        @if ($totalIncome > 0)
            <tr class="bt-none cutdata">
                <th class="bt-none" colspan="5"></th>
                <th class="bt-none" colspan="1">Grand Total :</th>
                <th class="grand-total-top-border" colspan="1">
                    {{ $totalIncome }}
                </th>
            </tr>
        @endif
    </tbody>
</table>
