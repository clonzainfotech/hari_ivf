
<style type="text/css">
    .print-deposit-table, .deposit-header, .deposit-receipt, .deposit-data {
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .deposit-header,.deposit-data{
        border: 1px solid #ddd;
    }
    .print-deposit-table .deposite-receipt {
        width:100%;
    }
    .deposit-width {
        width: 100%;
    }
    .deposit-hospital{
        height: 50px;
        font-size: 28px;
        font-weight: 900;
    }
    .deposit-address{
        text-align: center;
        height: 25px;
    }

    .deposit-receipt {
        background-color: #ddd;
    }

    .deposit-receipt-th {
        line-height: 15px;
        font-size: 18px;
        font-weight: 900;
        text-transform: uppercase;
        background-color: darkgrey;
    }

    .deposit-data {
        padding: 7px 10px;
    }
    .deposit-data .deposit-patient-name {
        text-transform: capitalize;
    }

    .deposit-data .hospital-name {
        text-transform: uppercase;
    }
    .deposit-data tr td h4{
        margin-bottom: 0 !important;
        margin-top: 0 !important;
    }

    .deposit-data tr td h3{
        margin-bottom: 0 !important;
        margin-top: 0 !important;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right
    }
    th {
        text-align: left;
        padding: 10px 10px;
    }

    .deposit td {
        height: 25px;
        font-size: 14px;
        padding: 6px 10px;
    }

    .all-side-border {
        border: 0.5px solid #dee2e6;
    }

    .left-right-side-border {
        border-left: 0.5px solid #dee2e6;
        border-right: 0.5px solid #dee2e6;
    }

</style>
@php
    $pMethod = ['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'];
@endphp
<table id="print-deposit-table" class="print-deposit-table">
    <tbody>
    <tr>
        <td>
            <table class="deposit-header invoice">
                <thead>
                    @php
                        $hAddress = hospitalAddress();
                    @endphp
                <tr class="deposit-hospital">
                    <th class="text-center">{{strtoupper(config('app.hospitalname1'))}}</th>
                </tr>
                <tr class="deposit-address">
                    <td>{{$hAddress->address}}</td>
                </tr>
                <tr class="deposit-address">
                    <td>Email: {{$hAddress->email}}</td>
                </tr>
                <tr class="deposit-address">
                    <td>Ph.No: {{$hAddress->mobile}}</td>
                </tr>
                </thead>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="deposite-receipt" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th class="deposit-receipt-th text-center">Receipt</th>
                </tr>
                </thead>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="deposit-data deposit" cellpadding="0" cellspacing="0">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td colspan="2" class="text-right">Date : {{$hormon->created_at->format('d-m-Y')}}</td>
                </tr>
                    @if($hormon->charge_type == 1)
                        <tr>
                            <td>Charge Type : <b>HORMON</b></td>
                        </tr>
                         <tr>
                            <td>Injection : {{$hormon->injection}}</td>
                        </tr>
                    @elseif($hormon->charge_type == 2)
                        <tr>
                            <td>Charge Type : IVF</td>
                        </tr>
                    @else
                        <tr>
                            <td>Charge Type : IUI</td>
                        </tr>
                    @endif
                <tr>
                    <td>Payment Type: {{!empty($hormon->payment_type) ? $pMethod[$hormon->payment_type] : null}}</td>
                </tr>
                <tr>
                    <td><h4>Received With Thanks From</h4></td>
                </tr>
                <tr>
                    <td class="text-center deposit-patient-name"><h3>{{ucwords(strtolower($patientname->name))}}</h3></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <h4>The Sum of Rs. {{$depositeWord}}.</h4>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><h3>{{$hormon->amount}}</h3></td>
                </tr>
                <tr>
                    <td class="text-right hospital-name">For {{strtoupper(config('app.hospitalname1'))}}</td>
                </tr>

                <tr>
                    <td class="text-center" colspan="2" style="border: 1px solid #000000"><h3>Thank You</h3></td>
                </tr>
                <tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
