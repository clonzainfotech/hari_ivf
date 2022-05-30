@extends('layouts.main')
@section('parentPageTitle', 'Indoor')
        @section('title', 'Invoice')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <style>
        .deposit_error_msg{
            font-size: 12px;
            color: red;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix invoice-add">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Invoice</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::previous()}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        {{Form::open([
                            'class'=>'form',
                            'id' => 'invoice-form',
                            'files'=>'true'])}}

                        {{Form::hidden('booked_id', !empty(Request::segment(3)) ? Request::segment(3) : null, [
                            'id' => 'booked_id'
                        ])}}
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            <!-- patients basic information -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title">
                                        <a class="collapsed"
                                            role="button"
                                            data-toggle="collapse"
                                            data-parent="#accordion_1"
                                            href="#patients"
                                            aria-expanded="true"
                                            aria-controls="patients">
                                            Patient Details
                                        </a>
                                    </h4>
                                </div>
                                <div id="patients" class="" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Name: &nbsp;</span>
                                                    {{Form::text('booking_id',$bookdata->getPatientsDetails['name'],['class'=>'form-control name col-md-9','placeholder'=>'Patient Name'])}}
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">DOA Date:</span>
                                                    {{Form::text('admitdate',$bookdata->doa_date ? \Carbon\Carbon::Parse($bookdata->doa_date)->format('D d M Y') : null,[
                                                       'class'=>'form-control col-md-9 datetimepicker admitdate',
                                                       'placeholder'=>'Date of Admission',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg doa-date"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">DOD Date:</span>
                                                    {{Form::text('dischargedate',$bookdata->dod_date ? \Carbon\Carbon::Parse($bookdata->dod_date)->format('D d M Y') : null,[
                                                       'class'=>'form-control col-md-9 datetimepicker dischargedate',
                                                       'placeholder'=>'Date of Discharge',
                                                   ])}}
                                                </div>
                                                <span class="form-error-msg dod-date"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn">Room Type: &nbsp;</span>
                                                    {{Form::text('room_type',!empty($bookdata->getRoomType['name']) ? $bookdata->getRoomType['name'] : null,['class'=>'form-control','disabled'])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_4" role="tablist" aria-multiselectable="true">
                            <!-- patients basic information -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_4">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_4" href="#imp_note" aria-expanded="true"
                                                                aria-controls="imp_note">Patient's Important Note</a> </h4>
                                </div>
                                <div id="imp_note" class="" role="tabpanel" aria-labelledby="headingThree_4">
                                    <div class="panel-body">
                                        <div class="row">
                                            @if(count($appointmentRemark) > 0)
                                                @foreach($appointmentRemark as $key => $value)
                                                    <div class="col-md-6">
                                                        <div class="remark-details mb-2">
                                                            <div class="font-bold">{{$key}} :</div>
                                                            <div>{{$value}}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-12 text-center">No Important Notes</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            <!-- patients basic information -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                                                aria-controls="patients">Amounts</a> </h4>
                                </div>
                                <div id="patients" class="" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="vertical-form-label pr-0">
                                                    Bill Type :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="radio is-conceived">
                                                    {{Form::radio("bill_type",'1',true,['id'=>'normal','class'=>'bill-type'])}}
                                                    <label for="normal">
                                                        Normal
                                                    </label>

                                                    {{Form::radio("bill_type",'2','',['id'=>'package','class'=>'bill-type'])}}
                                                    <label for="package">
                                                        Package
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Registration Charges: &nbsp;</span>
                                                    {{Form::text('reg_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::text('reg_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'reg_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Blood  Charges: &nbsp;</span>
                                                    {{Form::text('blood_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::text('blood_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'blood_charge_amt',
                                                        'autocomplete' => 'off',
                                                        ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Room + Nursing Charges:</span>
                                                    {{Form::text('roomcharge_amt',!empty($roomtypedata->price) ? $roomtypedata->price : null,['class'=>'form-control col-sm-3 roomcharge_amt','placeholder'=>'Amount','disabled'])}}
                                                    {{Form::text('room_charge_desc','',['class'=>'form-control col-sm-4 room_charge_day','placeholder'=>'Days',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('room_charge_amt',!empty($roomtypedata->price) ? ($roomtypedata->price)*$days : 0,[
                                                        'class'=>'form-control col-sm-2 amount charges-charges room_charge',
                                                        'id'=>'room_charge','placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'room_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 1:</span>
                                                    {{Form::text('other_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('other_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',$bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'other_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Nursing Charges:</span>
                                                    {{Form::text('nursing_charge_amt',!empty($roomtypedata->nursing_charge) ? $roomtypedata->nursing_charge : null,['class'=>'form-control col-sm-3 nursingcharge_amt','placeholder'=>'Amount','disabled'])}}
                                                    {{Form::text('nursing_charges_desc','',['class'=>'form-control col-sm-4 nursing_charge_day','placeholder'=>'Days',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('nursing_charges_amt',!empty($roomtypedata->nursing_charge) ? ($roomtypedata->nursing_charge)*$days : null,[
                                                        'class'=>'form-control col-sm-2 amount charges-charges nursing_charge',
                                                        'placeholder'=>'Amount',$bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'nursing_charges_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Dr's Visit Charges:</span>
                                                    {{Form::text('nursing_charge_amt',!empty($roomtypedata->dr_visit_charge) ? $roomtypedata->dr_visit_charge : 0,['class'=>'form-control col-sm-3 drvisitcharge_amt','placeholder'=>'Amount','disabled'])}}
                                                    {{Form::text('visit_charges_desc','',['class'=>'form-control col-sm-4 drvisit_charge_day','placeholder'=>'Days',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('visit_charges_amt',!empty($roomtypedata->dr_visit_charge) ? ($roomtypedata->dr_visit_charge)*$days : 0,[
                                                        'class'=>'form-control col-sm-2 amount charges-charges drvisit_charge',
                                                        'placeholder'=>'Amount',$bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'visit_charges_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 2:</span>
                                                    {{Form::text('other_charge_descs','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('other_charge_amts','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'id'=>'other_charge_two_amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">LSCS Operation Charge:</span>
                                                    {{Form::text('lscs_operation_charge_description',null,[
                                                        'class'=>'form-control col-sm-4',
                                                        'placeholder'=>'Desc',
                                                    ])}}
                                                    {{Form::number('lscs_operation_charge',null,[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'LSCS Operation Charge',
                                                        'min' => 0,
                                                        'id' => 'lscs_operation_charge',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Specialist Dr. Visit :</span>
                                                    {{Form::number('spvisit_charges_amt','',[
                                                        'class'=>'form-control col-sm-6 amount',
                                                        'placeholder'=>'Dr.visit Charge Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'spvisit_charges_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Gynee Operation Charges:</span>
                                                    {{Form::text('op_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('op_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'op_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Medicine  Charge:</span>
                                                    {{Form::number('medicine_charge_amt','',[
                                                        'class'=>'form-control col-sm-6 amount',
                                                        'placeholder'=>'Medicine Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'medicine_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 3:</span>
                                                    {{Form::text('third_other_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('third_other_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',$bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'third_other_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 4:</span>
                                                    {{Form::text('fourth_other_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('fourth_other_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',$bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'fourth_other_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Onco (Surgeon) charge:</span>
                                                    {{Form::text('onco_surgeon_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('onco_surgeon_amt','',[
                                                        'class'=>'form-control col-sm-2 amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Total:</span>
                                                    {{Form::number('form_total_amt','',[
                                                        'class'=>'form-control col-sm-6 final-amount',
                                                        'id'=>'total_amount',
                                                        'placeholder'=>'Total',
                                                        'readonly',
                                                        'required'=>'required',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'autocomplete' => 'off',
                                                    ])}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">IVF Operation Charges:</span>
                                                    {{Form::text('op_charge_descs','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('op_charge_amts','',[
                                                        'class'=>'form-control col-sm-2 amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'op_charge_amts',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Discount:</span>
                                                    {{Form::number('discount_amt','',[
                                                        'class'=>'form-control col-sm-6 final-amount',
                                                        'id'=>'discount',
                                                        'placeholder'=>'Discount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg discount"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Delivery Charges:</span>
                                                    {{Form::text('delivery_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('delivery_charge_amt','',[
                                                        'class'=>'form-control col-sm-2  amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'delivery_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Deposit:</span>
                                                    {{Form::number('deposit_amt', !empty($currentDeposit) ? $currentDeposit : 0, [
                                                        'class'=>'form-control col-sm-6 final-amount',
                                                        'id'=>'deposit','placeholder'=>'Deposit',
                                                        // 'readonly',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                                <span class="deposit_error_msg d-none">you don't have any deposit.</span>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">O.T Charges:</span>
                                                    {{Form::text('ot_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('ot_charge_amt','',[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'ot_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group gt-input">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Grand Total:</span>
                                                    {{Form::number('grand_total_amt','',[
                                                        'class'=>'form-control col-sm-6 grand_total',
                                                        'id'=>'grand_total',
                                                        'required'=>'required',
                                                        'placeholder'=>'Grand Total',
                                                        'readonly',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Labour Room Charges:</span>
                                                    {{Form::text('labour_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('labour_charge_amt','',[
                                                        'class'=>'form-control col-sm-2  amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'labour_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row payment input-group">
                                                    <div class="col-md-6 input-group-addon  unik-lbl-spn">Payment Mode:</div>
                                                    <div class="col-md-6 payment-mode">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("payment",'2',true,['id'=>'cash','class'=>'cash-payment',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="cash">
                                                                Cash
                                                            </label>

                                                            {{Form::radio("payment",'1',false,['id'=>'card','class'=>'card-payment',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="card">
                                                                Swipe
                                                            </label>

                                                            {{Form::radio("payment",'3',false,['id'=>'cheque','class'=>'cheque-payment',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="cheque">
                                                                Cheque
                                                            </label>

                                                            {{Form::radio("payment",'4',false,['id'=>'upi','class'=>'upi-payment',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="upi">
                                                                UPI
                                                            </label>

                                                            {{Form::radio("payment",'5',false,['id'=>'neft','class'=>'neft-payment',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="neft">
                                                                NEFT
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Anaesthesia Charges:</span>
                                                    {{Form::text('anaesthesia_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('anaesthesia_charge_amt','',[
                                                        'class'=>'form-control col-sm-2  amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'anaesthesia_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group  unik-lbl-spn">
                                                    Current Deposit: {{!empty($currentDeposit) ? $currentDeposit : 0}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Biopsy Charges:</span>
                                                    {{Form::text('biopys_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('biopys_charge_amt','',[
                                                        'class'=>'form-control col-sm-2  amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'biopys_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Ultra Sonography Charges:</span>
                                                    {{Form::text('sonography_charge_desc','',['class'=>'form-control col-sm-4','placeholder'=>'Desc',$bookdata->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('sonography_charge_amt','',[
                                                        'class'=>'form-control col-sm-2  amount',
                                                        'placeholder'=>'Amount',
                                                        $bookdata->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'sonography_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="display-error"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            @if($bookdata->is_final_invoice == 0)
                                            {{Form::submit('Save', [
                                                'class'=>'btn btn-primary submit save-invoice'
                                            ])}}
                                            <button type="submit" class="btn btn-primary submit save-invoice" value="2">Final Invoice</button>
                                            @endif

                                            <button type="submit" class="btn btn-primary submit" value="1">Save & Preview</button>
                                            {{-- <button type="submit" class="btn btn-primary submit" value="3">Save & Send</button> --}}
                                            <a href="{{URL::previous()}}" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
     <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
     <script>
             $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
             $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';
             $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                time: false,
                weekStart: 1
             });
     </script>
     <script>
        $(document).ready(function() {
            var bookingId = '';
            var sum = 0;
            var day = '';
            var charge = '';
            var discount = '';
            var deposit =  '';
            var cutDiscount = '';
            var gt = '';
            var total = '';
            var totalAmt = '';

            $('.amount').on('input', function() {
                value = $(this).val();
                $('#' + this.id).val(value);
                if(value < 0){
                    return $(this).val(Math.abs(value));
                }
                if(value.includes(".")){
                    return $(this).val(value.split('.')[0]);
                }
                if ((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 8) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });
            $('.final-amount').on('input', function() {
                value = $(this).val();
                $('#' + this.id).val(value);
                if(value < 0){
                    return $(this).val(Math.abs(value));
                }
                if(value.includes(".")){
                    return $(this).val(value.split('.')[0]);
                }
                if ((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 10) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });
            $('.grand_total').on('input', function() {
                value = $(this).val();
                $('#' + this.id).val(value);
                if(value < 0){
                    return $(this).val(Math.abs(value));
                }
                if(value.includes(".")){
                    return $(this).val(value.split('.')[0]);
                }
                if ((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 10) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });
            
            
            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var invoice = $('#invoice-form').serialize();
                if (this.value == 1) {
                    invoice = invoice + '&isprint=1';
                }
                if (this.value == 2) {
                    invoice = invoice + '&is_final_invoice=1';
                }
                if (this.value == 3) {
                    invoice = invoice + '&is_send=1';
                }
                invoiceFormData(invoice);
            });

            function invoiceFormData(invoice) {
                $('.doa-date').text('');
                $('.dod-date').text('');
                if (parseInt($('#discount').val()) > parseInt($('#total_amount').val())) {
                    $('.discount').text('Discount amount can not be greater than total amount');
                    return false;
                }
                if($('.admitdate').val() == ''){
                    $('.doa-date').text('Please enter DOA');
                    $('html, body').animate({
                        scrollTop: ($('.doa-date').offset().top - 150)
                    }, 1000);
                    return false;
                }
                if($('.dischargedate').val() != '' && new Date($('.admitdate').val()) > new Date($('.dischargedate').val())){
                    $('.dod-date').text('DOD should not be less than DOA');
                    $('html, body').animate({
                    scrollTop: ($('.doa-date').offset().top - 150)
                    }, 1000);
                    return false;
                }
                $('.save-invoice').attr('disabled', true);
                $.ajax({
                    url: "{{URL::to('indoor/invoice/store')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: invoice,
                }).done(function(data){
                    var url = "{{URL::to('/indoor')}}";
                    if (data.status == 2) {
                        swal({ 
                            title: 'Oops',
                            text: 'Something went wrong',
                            type: 'error' 
                        },
                        function(){
                            window.location.href = url;
                        });
                    }
                    if (data.status == 1) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        w.window.print();
                        window.location.href = url;
                    } 
                    if (data.status == 0) {
                        window.location.href = url;
                    }
                    if(data.status == 3){
                        $('.deposit_error_msg').removeClass('d-none');
                        $('.save-invoice').attr('disabled', false);
                    }
                    if(data.status == 4){
                        $('.deposit_error_msg').removeClass('d-none');
                        $('.deposit_error_msg').text('your deposit limit reached');
                        $('.save-invoice').attr('disabled', false);
                    }
                    if(data.status == 5){
                        $('.deposit_error_msg').removeClass('d-none');
                        $('.deposit_error_msg').text("you can't add deposit more than the grand total amount");
                        $('.save-invoice').attr('disabled', false);
                    }
                    

                }).fail(function(error){
                });
            }

            sum = 0;
            $('.charges-charges').each(function () {
                sum+=Number($(this).val());
            })
            $('#total_amount').val(sum);
            if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                return $('#discount').val($('#total_amount').val());
            }
            $('#grand_total').val(sum);

            deposit = $('#deposit').val();
            gt = sum-deposit;
            $('#grand_total').val(gt);
            if(deposit > sum) {
                $('#grand_total').val(0);
            }

            $('.amount').keyup(function() {
                var sum = 0;
                $('.amount').each(function() {
                    sum += Number($(this).val());
                });
                $('#total_amount').val(sum);
                if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                    return $('#discount').val($('#total_amount').val());
                }
                if(sum == 0){
                    $('#discount').val(0);
                }
                discount = $('#discount').val();
                deposit = $('#deposit').val();
                cutDiscount = sum - discount;
                gt = cutDiscount - deposit;
                $('#grand_total').val(gt);
                total = Number(discount) + Number(deposit);
                totalAmt = $('#total_amount').val();
                if(totalAmt < total){
                    $('#grand_total').val(0);
                }
            });

            $('.final-amount').keyup( function() {
                sum = 0;
                $('.amount').each(function() {
                    sum += Number($(this).val());
                });
                if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                    return $('#discount').val($('#total_amount').val());
                }
                discount = $('#discount').val();
                deposit = $('#deposit').val();
                cutDiscount = sum - discount;
                gt = cutDiscount - deposit;
                // if(gt <= 0){}
                $('#grand_total').val(gt);
                total = Number(discount) + Number(deposit);
                totalAmt = $('#total_amount').val();
                if(totalAmt < total){
                    $('#grand_total').val(0);
                }
            });
            $('.room_charge_day').keyup(function () {
                sum = 0;
                day = parseInt($(this).val());
                charge = parseInt($('.roomcharge_amt').val());
                if(day != null) {
                    $('.room_charge').val(charge * day);
                    $('.amount').each(function() {
                        sum += Number($(this).val());
                    });
                    $('#total_amount').val(sum);
                    if(sum == 0){
                        $('#discount').val(0);
                    }
                    if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                        return $('#discount').val($('#total_amount').val());
                    }
                    discount = $('#discount').val();
                    deposit = $('#deposit').val();
                    cutDiscount = sum - discount;
                    gt = cutDiscount - deposit;
                    $('#grand_total').val(gt);
                    total = Number(discount) + Number(deposit);
                    totalAmt = $('#total_amount').val();
                    if(totalAmt < total){
                        $('#grand_total').val(0);
                    }
                }
            });
            $('.nursing_charge_day').keyup(function () {
                sum = 0;
                day = parseInt($(this).val());
                charge = parseInt($('.nursingcharge_amt').val());
                if(day != null) {
                    $('.nursing_charge').val(charge * day);
                    $('.amount').each(function() {
                        sum += Number($(this).val());
                    });
                    $('#total_amount').val(sum);
                    if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                        return $('#discount').val($('#total_amount').val());
                    }
                    discount = $('#discount').val();
                    deposit = $('#deposit').val();
                    cutDiscount = sum - discount;
                    gt = cutDiscount - deposit;
                    $('#grand_total').val(gt);
                    total = Number(discount) + Number(deposit);
                    totalAmt = $('#total_amount').val();
                    if(totalAmt < total){
                        $('#grand_total').val(0);
                    }
                }
            });
            $('.drvisit_charge_day').keyup(function () {
                sum = 0;
                day = parseInt($(this).val());
                charge = parseInt($('.drvisitcharge_amt').val());
                if(day != null) {
                    $('.drvisit_charge').val(charge * day);
                    $('.amount').each(function() {
                        sum += Number($(this).val());
                    });
                    $('#total_amount').val(sum);
                    if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                        return $('#discount').val($('#total_amount').val());
                    }
                    if(sum == 0){
                        $('#discount').val(0);
                    }
                    discount = $('#discount').val();
                    deposit = $('#deposit').val();
                    cutDiscount = sum - discount;
                    gt = cutDiscount - deposit;
                    $('#grand_total').val(gt);
                    total = Number(discount) + Number(deposit);
                    totalAmt = $('#total_amount').val();
                    if(totalAmt < total){
                        $('#grand_total').val(0);
                    }
                }
            });
        });
     </script>
@stop
