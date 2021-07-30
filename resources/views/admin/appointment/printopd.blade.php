<style type="text/css">
    .print-opd-table, .opd-header, .opd-receipt, .opd-data{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
        /* margin-top: 50px; */
    }
    .opd-header,.opd-data{
        border: 1px solid #ddd;
    }
    .opd-hospital{
        height: 50px;
        font-size: 28px;
        font-weight: 900;
    }
    .opd-address{
        text-align: center;
        height: 35px;
    }
    .opd-receipt{
        background-color: #ddd;
    }
    .opd-receipt-th{
        height: 35px;
        font-size: 18px;
        font-weight: 500;
    }
    .ttc{
        text-transform: capitalize;
    }
    .opd-left{
        text-align: left;
    }
    .opd-right{
        text-align: right;
    }
    .opd-data{
        padding: 10px 10px 0px;
        margin-top: 0px;
    }
    .opd-data-header{
        height: 50px;
        width: 50%;
        border-bottom: 2px solid #000;
    }
    .opd-for-radha{
        height: 60px;
        font-size: 18px;
    }
    .opd-net-amount-tr{
        padding-top: 10px;
    }
    .opd-net-amount{
        padding-top: 5px;
        display: inline-block;
        border-top: 1px dashed #868686;
    }
    .opd-amount{
        width: 70px;
        display: block;
        text-align: right;
    }
    td{
        height: 25px;
        font-size: 14px;
    }
    .opd-main-doctor{
        margin-top: 5px;
    }
    .patients-name{
        font-weight: 700 !important;
        font-size: 18px !important;
    }
    .p-name{
        font-size: 18px !important;
    }
</style>

<table id="print-opd-table" class="print-opd-table">
    <tbody>
        <tr>
            <td>
                <table class="opd-header">
                    <thead>
                        @php
                            $hAddress = hospitalAddress();
                        @endphp
                        <tr class="opd-hospital">
                            <th>{{strtoupper(config('app.hospitalname1'))}}</th>
                        </tr>
                        <tr class="opd-address">
                            <td>{{$hAddress->address}}</td>
                            {{-- <td>1st & 2nd Floor, Tapibuag Shopping Center, Mini Bazar, Varachha Road, Surat</td> --}}
                        </tr>
                        <tr class="opd-address">
                            <td>Ph.No: {{$hAddress->mobile}}</td>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="opd-receipt">
                    <thead>
                        <tr>
                            <th class="opd-receipt-th">OPD RECEIPT</th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="opd-data" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="opd-left opd-data-header">Receipt No. : {{$appointmentCharges->id}}</th><th class="opd-right opd-data-header">Date : 
                                {{\Carbon\Carbon::parse($appointmentCharges->created_at)->format('d-M-Y')}} 
                                <div class="opd-main-doctor">{{config('app.doctor') }}</div>
                            </th>
                        </tr>
                        <tr>
                            <th class="opd-left opd-receipt-th p-name" colspan="2">Name : <span class="patients-name">{{ucWords(strtolower($appointmentCharges->getAppointment->getPatientsDetails['name']))}}</span></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="opd-left opd-receipt-th ttc">Received With Thanks From {{$appointmentCharges->getUser['name']}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($appointmentCharges->consulting_charges>0)
                            <tr>
                                <td class="opd-right">Consulting Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->consulting_charges}}</span></td>
                            </tr>
                        @endif
                        @if($extraField1[1]>0)
                            <tr>
                                <td class="opd-right">{{$extraField1[0]}} Charge : </td><td class="opd-left"><span class="opd-amount">{{$extraField1[1]}}</span></td>
                            </tr>
                        @endif
                        @if($extraField2[1]>0)
                            <tr>
                                <td class="opd-right">{{$extraField2[0]}} Charge : </td><td class="opd-left"><span class="opd-amount">{{$extraField2[1]}}</span></td>
                            </tr>
                        @endif
                        @if($appointmentCharges->nst>0)
                            <tr>
                                <td class="opd-right">NST Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->nst}}</span></td>
                            </tr>
                        @endif
                        @if($appointmentCharges->cut>0)
                            <tr>
                                <td class="opd-right">CUT Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->cut}}</span></td>
                            </tr>
                        @endif
                        @if($appointmentCharges->procedure>0)
                            <tr>
                                <td class="opd-right">Procedure Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->procedure}}</span></td>
                            </tr>
                        @endif
                        @if($appointmentCharges->usg>0)
                            <tr>
                                <td class="opd-right">USG Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->usg}}</span></td>
                            </tr>
                        @endif
                        @if($appointmentCharges->dressing>0)
                            <tr>
                                <td class="opd-right">Dressing Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->dressing}}</span></td>
                            </tr>
                        @endif
                        @if($appointmentCharges->ivf>0)
                            <tr>
                                <td class="opd-right">IVF Charge : </td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->ivf}}</span></td>
                            </tr>
                        @endif
                        <tr>
                            <td class="opd-right opd-net-amount-tr"><span class="opd-net-amount">Net Amount : </span></td><td class="opd-left opd-net-amount-tr"><span class="opd-amount"><span class="opd-net-amount">{{$appointmentCharges->netamount}}</span></span></td>
                        </tr>
                        <tr>
                            <td class="opd-right">Received Amount :</td><td class="opd-left"><span class="opd-amount">{{$appointmentCharges->netamount}}</span></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="opd-right opd-for-radha">For {{strtoupper(config('app.hospitalname1'))}}</th>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>