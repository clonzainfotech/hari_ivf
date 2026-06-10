<style type="text/css">
    .print-invoice-table, .invoice-header, .invoice-receipt, .invoice-data {
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .invoice-header,.invoice-data{
        border: 1px solid #ddd;
    }

    .invoice-width {
        width: 100%;
    }
    .invoice-hospital{
        height: 50px;
        font-size: 28px;
        font-weight: 900;
    }
    .invoice-address{
        text-align: center;
        height: 45px;
    }

    .invoice-receipt {
        background-color: #ddd;
    }

    .invoice-receipt-th {
        line-height: 15px;
        font-size: 18px;
        font-weight: 900;
    }

    .invoice-data {
        padding: 10px 10px;
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

    .invoice td {
        height: 25px;
        font-size: 14px;
        padding: 10px 10px;
    }

    .all-side-border {
        border: 0.5px solid #dee2e6;
    }

    .left-right-side-border {
        border-left: 0.5px solid #dee2e6;
        border-right: 0.5px solid #dee2e6;
    }

</style>
<table id="print-invoice-table" class="print-invoice-table">
    <tbody>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
        {{-- <tr>
            <td>
                <table class="invoice-header invoice">
                    <thead>
                        <tr class="invoice-hospital">
                            <th class="text-center">{{strtoupper(config('app.hospitalname1'))}}</th>
                        </tr>
                        <tr class="invoice-address">
                            <td>1st & 2nd Floor, Tapibuag Shopping Center, Mini Bazar, Varachha Road, Surat</td>
                        </tr>k
                        <tr class="invoice-address">
                            <td>Ph.No: 2548096, Mob.No: 9925394276</td>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr> --}}
        <tr>
            <td>
                <table class="invoice-receipt" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="invoice-receipt-th text-center">INDOOR INVOICE</th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="invoice-data invoice" cellpadding="0" cellspacing="0">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Patient Name :</td>
                            <th>{{ ucwords(strtolower($invoice->getBookedBed->getPatientsDetails['name'])) }} </th>
                        </tr>
                        <tr>
                            <td>Room Number :</td>
                            <td>{{ $invoice->getBookedBed->getRoom['room_no'] }} </td>
                            <td>DOA :</td>
                            <td>{{ cdate($invoice->getBookedBed['doa_date'])->format('d-M-Y') }} </td>
                        </tr>
                        <tr>
                            <td>Room Type :</td>
                            <td>{{!empty($invoice->getBookedBed->getRoomType['name']) ? $invoice->getBookedBed->getRoomType['name'] : null}} </td>
                        </tr>
                        <tr>
                            <td>Under C/O Doctor :</td>
                            <td>{{ ucwords(strtolower($invoice->getBookedBed->getPatientsDetails->getHospitalDoctor['name'])) }} </td>
                            <td>DOD :</td>
                            <td>{{ cdate($invoice->getBookedBed['dod_date'])->format('d-M-Y') }} </td>
                        </tr>
                        @if(!empty($invoice->bill_type))
                            <tr>
                                <td>Bill Type</td>
                                <td>{{$invoice->bill_type == 1 ? 'Normal' : 'Package'}}</td>
                            </tr>
                        @endif
                    <tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="invoice-width invoice" cellpadding="0" cellspacing="0">
                    <thead>
                        <th colspan="2" class="all-side-border">Particulars</th>
                        <th class="all-side-border">Description</th>
                        <th class="all-side-border text-right">Amount</th>
                    </thead>
                    <tbody>
                        @if ($invoice->reg_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Registration Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->reg_charge_desc)) ? ucwords(strtolower($invoice->reg_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->reg_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->blood_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Blood Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->blood_charge_desc)) ? ucwords(strtolower($invoice->blood_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->blood_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->room_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Room + Nursing Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->room_charge_desc)) && !empty($invoice->getBookedBed->getRoomType['price']) ? ucwords(strtolower($invoice->room_charge_desc)).' X '.$invoice->getBookedBed->getRoomType['price'] : ucwords(strtolower($invoice->room_charge_desc)) }} </td>
                                <td class="all-side-border text-right">{{ $invoice->room_charge_amt }} </td>
                            </tr>
                        @endif
                        {{-- @if ($invoice->nursing_charges_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Nursing Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->nursing_charges_desc)) ? ucwords(strtolower($invoice->nursing_charges_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->nursing_charges_amt }} </td>
                            </tr>
                        @endif --}}
                        @if ($invoice->visit_charges_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Visit Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->visit_charges_desc)) && !empty($invoice->getBookedBed->getRoomType['dr_visit_charge']) ? ucwords(strtolower($invoice->visit_charges_desc)).' X '.$invoice->getBookedBed->getRoomType['dr_visit_charge'] : ucwords(strtolower($invoice->visit_charges_desc)) }} </td>
                                <td class="all-side-border text-right">{{ $invoice->visit_charges_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->op_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Gynee Opration Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->op_charge_desc)) ? ucwords(strtolower($invoice->op_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->op_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->onco_surgeon_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Onco (Surgeon) charge</td>
                                <td class="all-side-border">{{ (!empty($invoice->onco_surgeon_desc)) ? ucwords(strtolower($invoice->onco_surgeon_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->onco_surgeon_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->op_charge_amts > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">IVF Operation Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->op_charge_descs)) ? ucwords(strtolower($invoice->op_charge_descs)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->op_charge_amts }} </td>
                            </tr>
                        @endif


                        @if ($invoice->lscs_operation_charge > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">LSCS Operation Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->lscs_operation_charge_description)) ? ucwords(strtolower($invoice->lscs_operation_charge_description)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->lscs_operation_charge }} </td>
                            </tr>
                        @endif

                        @if ($invoice->spvisit_charges_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">SP Visit Charge</td>
                                <td class="all-side-border"></td>
                                <td class="all-side-border text-right">{{ $invoice->spvisit_charges_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->delivery_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Delivery Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->delivery_charge_desc)) ? ucwords(strtolower($invoice->delivery_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->delivery_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->medicine_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Medicine Charges</td>
                                <td class="all-side-border"></td>
                                <td class="all-side-border text-right">{{ $invoice->medicine_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->ot_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">O.T. Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->ot_charge_desc)) ? ucwords(strtolower($invoice->ot_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->ot_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->labour_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Labour Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->labour_charge_desc)) ? ucwords(strtolower($invoice->labour_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->labour_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->anaesthesia_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Anaesthesia Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->anaesthesia_charge_desc)) ? ucwords(strtolower($invoice->anaesthesia_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->anaesthesia_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->biopys_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Biopys Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->biopys_charge_desc)) ? ucwords(strtolower($invoice->biopys_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->biopys_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->sonography_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">Sonography Charges</td>
                                <td class="all-side-border">{{ (!empty($invoice->sonography_charge_desc)) ? ucwords(strtolower($invoice->sonography_charge_desc)) : null }} </td>
                                <td class="all-side-border text-right">{{ $invoice->sonography_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->other_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">{{ (!empty($invoice->other_charge_desc)) ? ucwords(strtolower($invoice->other_charge_desc)) : null }}</td>
                                <td class="all-side-border"></td>
                                <td class="all-side-border text-right">{{ $invoice->other_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->other_charge_amts > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">{{ (!empty($invoice->other_charge_descs)) ? ucwords(strtolower($invoice->other_charge_descs)) : null }}</td>
                                <td class="all-side-border"></td>
                                <td class="all-side-border text-right">{{ $invoice->other_charge_amts }} </td>
                            </tr>
                        @endif
                        @if ($invoice->third_other_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">{{ (!empty($invoice->third_other_charge_desc)) ? ucwords(strtolower($invoice->third_other_charge_desc)) : null }}</td>
                                <td class="all-side-border"></td>
                                <td class="all-side-border text-right">{{ $invoice->third_other_charge_amt }} </td>
                            </tr>
                        @endif
                        @if ($invoice->fourth_other_charge_amt > 0)
                            <tr>
                                <td colspan="2" class="all-side-border">{{ (!empty($invoice->fourth_other_charge_desc)) ? ucwords(strtolower($invoice->fourth_other_charge_desc)) : null }}</td>
                                <td class="all-side-border"></td>
                                <td class="all-side-border text-right">{{ $invoice->fourth_other_charge_amt }} </td>
                            </tr>
                        @endif
                        <tr>
                            <th colspan="2" class="all-side-border">Total Bill Amount</th>
                            <td class="all-side-border"></td>
                            <th class="all-side-border text-right">
                                 {{($invoice->form_total_amt > 0) ? $invoice->form_total_amt : 0}}
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="all-side-border">Less Discount</td>
                            <td class="all-side-border"></td>
                            <td class="all-side-border text-right">{{ ($invoice->discount_amt > 0) ? $invoice->discount_amt : 0 }} </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="all-side-border">Less Deposit</td>
                            <td class="all-side-border"></td>
                            <td class="all-side-border text-right">{{ ($invoice->deposit_amt > 0) ? $invoice->deposit_amt : 0 }} </td>
                        </tr>
                        <tr>
                            <th colspan="2" class="all-side-border">Final Payable Amount</th>
                            <td class="all-side-border"></td>
                            <th class="all-side-border text-right">
                                {{($invoice->grand_total_amt > 0) ? $invoice->grand_total_amt : 0}}
                            </th>
                        </tr>
                        <tr>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td>
                                <div class="text-center">
                                    For, <br />
                                    {{strtoupper(config('app.hospitalname1'))}}
                                </div>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
