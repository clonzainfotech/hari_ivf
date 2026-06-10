<style type="text/css">

    .report-table tr {
        border: 1px solid #000000;
        height: 36px;
    }
    .report-header-tr-th {
        font-size: 13px;
    }
    .report-table {
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .report-date-font {
        font-size: 14px;
    }

    .report-height-fifty {
        height: 50px;
    }
    .report-header {
        font-weight: 900;
        font-size: 20px;
    }
    .report-header-tr{
        text-align: left;
        height: 35px;
        background-color: #c7dfe0;
        border: 1px solid #000000;
    }
    .charges {
        font-weight: 600;
        font-size: 11px;
    }
    .upper-border {
        border-top: 1px solid #000000;
    }
    .text-left {
        text-align: left;
        font-size: 13px;
    }
    .seperator {
        border-top: 0.5px solid #dee2e6;
    }
    .data-font {
        font-size: 11px;
    }
    tr, th, td {
        padding: 12px 12px;
    }
    .report-body-last {
        text-align: center;
    }
</style>
<table id="report-table" class="report-table font" cellspacing="0">
    <thead>
        {{-- {{dd($date)}} --}}
        <tr><th colspan="8" class="report-header report-height-fifty">{{strtoupper(config('app.hospitalname1'))}}</th></tr>
        <tr><th colspan="8" class="report-header">FORM NO. 3C</th></tr>
        <tr><th colspan="8" class="report-header">FORM OF DAILY CASE REGISTER - OPD</th></tr>
        <tr><th colspan="8" class="report-height-fifty report-date-font">{{$date}}</th></tr>
        <tr><th colspan="8"><hr /></th></tr>
        <tr class="report-header-tr seperator">
            <th class="report-header-tr-th">Sr No</th>
            <th class="report-header-tr-th">Date</th>
            <th class="report-header-tr-th">Rec No</th>
            <th class="report-header-tr-th">Patient Name</th>
            <th class="report-header-tr-th">Category</th>
            <th class="report-header-tr-th">Service Given</th>
            <th class="report-header-tr-th">Payment</th>
            <th class="report-header-tr-th">Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1;?>
        @forelse($report as $row)
            <tr>
                <td class="data-font seperator">{{($i++) . '.'}}</td>
                <td class="data-font seperator">{{ cdate($row->getAppointment->date)->format('d-m-Y') }}</td>
                <td class="data-font seperator">{{$row->getAppointment->getPatientsDetails['code'] }}</td>
                <td class="data-font seperator">{{strtoupper($row->getAppointment->getPatientsDetails['name'])}}</td>
                <td class="data-font seperator">{{ucfirst($row->getAppointment->categoryDetails['name'])}}</td>
                <td class="data-font seperator">
                    @php
                        if (!empty($row->charge_types)) {
                        
                            $explodeChargeTypes = explode(',', $row->charge_types);
                            $implodeChargeTypes = implode("\n", $explodeChargeTypes);
                            echo nl2br($implodeChargeTypes);
                        }
                    @endphp
                </td>
                <td class="data-font seperator">{{$row->payment_mode}}</td>
                <td class="seperator">
                    <div class="charges">
                        {{$row->netamount}}
                    </div>
                </td>
            </tr>
        @empty
            <td colspan="8" class="report-body-last">No records available</td>
        @endforelse
        @if ($netAmountCount > 0)
            <tr>
                <td colspan="6"></td>
                <th class="text-left" colspan="1">Grand Total :</th>
                <th class="upper-border text-left">{{ $netAmountCount }}</th>
            </tr>
        @endif
    </tbody>
</table>