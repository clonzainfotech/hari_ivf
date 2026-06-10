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
    <div class="row clearfix invoice-edit">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Invoice</strong>
                    </h2>
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
                            'id' => 'invoice-form',
                            'class'=>'form',
                            'files'=>'true'
                        ])}}
                        {{ Form::hidden('booked_id', !empty(Request::segment(2)) ? Request::segment(2) : null, [
                            'id' => 'booked_id'
                        ]) }}
                        {{ Form::hidden('patient_id', !empty($patientdata->id) ? encrypt($patientdata->id) : null, [
                            'id' => 'patient_id'
                        ]) }}
                        {{ Form::hidden('old_deposit', !empty($invoicedata->deposit_amt) ? $invoicedata->deposit_amt : 0, [
                            'id' => 'old_deposit'
                        ]) }}
                        {{ Form::hidden('old_total', !empty($invoicedata->form_total_amt) ? $invoicedata->form_total_amt : 0, [
                            'id' => 'old_total'
                        ]) }}
                        {{ Form::hidden('old_discount', !empty($invoicedata->discount_amt) ? $invoicedata->discount_amt : 0, [
                            'id' => 'old_discount'
                        ]) }}
                        {{ Form::hidden('old_current_deposit', !empty($currentDeposit) ? $currentDeposit : 0, [
                            'id' => 'old_current_deposit'
                        ]) }}
                        {{ Form::hidden('old_grand_total', !empty($invoicedata->grand_total_amt) ? $invoicedata->grand_total_amt : 0, [
                            'id' => 'old_grand_total'
                        ]) }}

                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            <!-- patients basic information -->
                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                                                aria-controls="patients">Patient Details</a> </h4>
                                </div>
                                
                                <div id="patients" class="" role="tabpanel" aria-labelledby="headingThree_1">
                                    <div class="panel-body">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Name:</span>
                                                    {{Form::text('booking_id',!empty($patientdata->name) ? $patientdata->name : null,['class'=>'form-control name col-md-9','placeholder'=>'Patient Name'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">DOA Date:</span>
                                                    {{Form::text('admitdate',$BookingData->doa_date ? \Carbon\Carbon::Parse($BookingData->doa_date)->format('D d M Y') : null,[
                                                       'class'=>'form-control col-md-9 datetimepicker admitdate',
                                                       'placeholder'=>'Date of Admission',
                                                   ])}}
                                                </div>
                                                <span class="form-error-msg doa-date"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">DOD Date:</span>
                                                    {{Form::text('dischargedate',$BookingData->dod_date ? \Carbon\Carbon::Parse($BookingData->dod_date)->format('D d M Y') : null,[
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
                                                    {{Form::text('room_type',!empty($BookingData->getRoomType['name']) ? $BookingData->getRoomType['name'] : null,['class'=>'form-control','disabled'])}}
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
                                                    {{Form::radio("bill_type",'1',!empty($invoicedata->bill_type) && $invoicedata->bill_type == '1' ? true : false,['id'=>'normal','class'=>'bill-type'])}}
                                                    <label for="normal">
                                                        Normal
                                                    </label>

                                                    {{Form::radio("bill_type",'2',!empty($invoicedata->bill_type) && $invoicedata->bill_type == '2' ? true : false,['id'=>'package','class'=>'bill-type'])}}
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
                                                    {{Form::text('reg_charge_desc',!empty($invoicedata->reg_charge_desc) ? $invoicedata->reg_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                    {{Form::number('reg_charge_amt',!empty($invoicedata->reg_charge_amt) ? $invoicedata->reg_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount reg_charge_amt',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'reg_charge_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : '',
                                                        
                                                    ])}}
                                                </div>
                                            </div>
                                   
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Blood  Charges: &nbsp;</span>
                                                        {{Form::text('blood_charge_desc',!empty($invoicedata->blood_charge_desc) ? $invoicedata->blood_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('blood_charge_amt',!empty($invoicedata->blood_charge_amt) ? $invoicedata->blood_charge_amt : null,[
                                                       'class'=>'form-control col-sm-2 amount',
                                                       'placeholder'=>'Amount',
                                                       'min' => 0,
                                                       'id' => 'blood_charge_amt',
                                                       'autocomplete' => 'off',
                                                       $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                   ])}}
                                                </div>
                                            </div>
                                        </div>
                          
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Room + Nursing Charges:</span>
                                                        {{Form::text('roomcharge_amt',!empty($roomtypedata->price) ? $roomtypedata->price : null,['class'=>'form-control col-sm-3 roomcharge_amt','placeholder'=>'Amount'])}}
                                                        {{Form::text('room_charge_desc',!empty($invoicedata->room_charge_desc) ? $invoicedata->room_charge_desc : null,['class'=>'form-control col-sm-4 room_charge_day','placeholder'=>'Day',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('room_charge_amt', !empty($invoicedata->room_charge_amt) ? $invoicedata->room_charge_amt : null,[
                                                            'class'=>'form-control col-sm-2 amount room_charge',
                                                            'placeholder'=>'Amount',
                                                            'min' => 0,
                                                            'id' => 'room_charge_amt',
                                                            'autocomplete' => 'off',
                                                            $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                        ])}}
                                                </div>
                                                <span class="form-error-msg room_charge_amt"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 1:</span>
                                                        {{Form::text('other_charge_desc',!empty($invoicedata->other_charge_desc) ? $invoicedata->other_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('other_charge_amt',!empty($invoicedata->other_charge_amt) ? $invoicedata->other_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'other_charge_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Nursing Charges:</span>
                                                    {{Form::text('nursing_charge_amt',!empty($roomtypedata->nursing_charge) ? $roomtypedata->nursing_charge :null,['class'=>'form-control col-sm-3 nursingcharge_amt','placeholder'=>'Amount'])}}
                                                        {{Form::text('nursing_charges_desc',!empty($invoicedata->nursing_charges_desc) ? $invoicedata->nursing_charges_desc : null,['class'=>'form-control col-sm-4 nursing_charge_day','placeholder'=>'Day',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('nursing_charges_amt',!empty($invoicedata->nursing_charges_amt) ? $invoicedata->nursing_charges_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount nursing_charge',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'nursing_charges_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                                
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-3">Dr's Visit Charges:</span>
                                                    {{Form::text('nursing_charge_amt',!empty($roomtypedata->dr_visit_charge) ? $roomtypedata->dr_visit_charge : null,[
                                                        'class'=>'form-control col-sm-3 drvisitcharge_amt',
                                                        'placeholder'=>'Amount'
                                                    ])}}
                                                    {{Form::text('visit_charges_desc',!empty($invoicedata->visit_charges_desc) ? $invoicedata->visit_charges_desc : null,['class'=>'form-control col-sm-4 drvisit_charge_day','placeholder'=>'Day',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                    {{Form::number('visit_charges_amt',!empty($invoicedata->visit_charges_amt) ? $invoicedata->visit_charges_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount drvisit_charge',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'visit_charges_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 2:</span>
                                                        {{Form::text('other_charge_descs',!empty($invoicedata->other_charge_descs) ? $invoicedata->other_charge_descs : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('other_charge_amts',!empty($invoicedata->other_charge_amts) ? $invoicedata->other_charge_amts : null,[
                                                            'class'=>'form-control col-sm-2 amount ',
                                                            'id'=>'other_charge_two_amount',
                                                            'placeholder'=>'Amount',
                                                            'min' => 0,
                                                            'id' => 'other_charge_amts',
                                                            'autocomplete' => 'off',
                                                            $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                        ])}}
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">LSCS Operation Charge:</span>
                                                    {{Form::text('lscs_operation_charge_description',!empty($invoicedata->lscs_operation_charge_description) ? $invoicedata->lscs_operation_charge_description : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                    {{Form::number('lscs_operation_charge',!empty($invoicedata->lscs_operation_charge) ? $invoicedata->lscs_operation_charge : null,['class'=>'form-control col-sm-2 amount ','placeholder'=>'LSCS Operation Charge','min' => 0,'id' => 'lscs_operation_charge','autocomplete' => 'off',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Specialist Dr. Visit :</span>
                                                        {{Form::number('spvisit_charges_amt',!empty($invoicedata->spvisit_charges_amt) ? $invoicedata->spvisit_charges_amt : null,[
                                                         'class'=>'form-control col-sm-6 amount',
                                                         'placeholder'=>'Dr.visit Charge Amount',
                                                         'min' => 0,
                                                         'id' => 'spvisit_charges_amt',
                                                         'autocomplete' => 'off',
                                                         $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                     ])}}
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Gynee Operation Charges:</span>
                                                        {{Form::text('op_charge_desc',!empty($invoicedata->op_charge_desc) ? $invoicedata->op_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('op_charge_amt',!empty($invoicedata->op_charge_amt) ? $invoicedata->op_charge_amt : null,[
                                                         'class'=>'form-control col-sm-2 amount ',
                                                         'placeholder'=>'Gynee Operation Charges',
                                                         'min' => 0,
                                                         'id' => 'op_charge_amt',
                                                         'autocomplete' => 'off',
                                                         $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                     ])}}
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Medicine  Charge:</span>
                                                        {{Form::number('medicine_charge_amt',!empty($invoicedata->medicine_charge_amt) ? $invoicedata->medicine_charge_amt : null,[
                                                        'class'=>'form-control col-sm-6 amount',
                                                        'placeholder'=>'Medicine Amount',
                                                        'min' => 0,
                                                        'id' => 'medicine_charge_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 3:</span>
                                                    {{Form::text('third_other_charge_desc',!empty($invoicedata->third_other_charge_desc) ? $invoicedata->third_other_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('third_other_charge_amt',!empty($invoicedata->third_other_charge_amt) ? $invoicedata->third_other_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',$BookingData->is_final_invoice == 1 ? 'disabled' : '',
                                                        'id' => 'third_other_charge_amt',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Other  Charges 4:</span>
                                                    {{Form::text('fourth_other_charge_desc',!empty($invoicedata->fourth_other_charge_desc) ? $invoicedata->fourth_other_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('fourth_other_charge_amt',!empty($invoicedata->fourth_other_charge_amt) ? $invoicedata->fourth_other_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',$BookingData->is_final_invoice == 1 ? 'disabled' : '',
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
                                                    {{Form::text('onco_surgeon_desc',!empty($invoicedata->onco_surgeon_desc) ? $invoicedata->onco_surgeon_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                    {{Form::number('onco_surgeon_amt',!empty($invoicedata->onco_surgeon_amt) ? $invoicedata->onco_surgeon_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount',
                                                        'placeholder'=>'Amount',
                                                        $BookingData->is_final_invoice == 1 ? 'disabled' : '',
                                                        'autocomplete' => 'off',
                                                    ])}}
                                                </div>
                                            </div>
                                            @php
                                                $total = 0;
                                                $total += !empty($invoicedata->reg_charge_amt) ? $invoicedata->reg_charge_amt : 0;
                                                $total += !empty($invoicedata->blood_charge_amt) ? $invoicedata->blood_charge_amt : 0;
                                                $total += !empty($invoicedata->room_charge_amt) ? $invoicedata->room_charge_amt : 0;
                                                $total += !empty($invoicedata->other_charge_amt) ? $invoicedata->other_charge_amt : 0;
                                                $total += !empty($invoicedata->nursing_charges_amt) ? $invoicedata->nursing_charges_amt : 0;
                                                $total += !empty($invoicedata->other_charge_amts) ? $invoicedata->other_charge_amts : 0;
                                                $total += !empty($invoicedata->visit_charges_amt) ? $invoicedata->visit_charges_amt : 0;
                                                $total += !empty($invoicedata->spvisit_charges_amt) ? $invoicedata->spvisit_charges_amt : 0;
                                                $total += !empty($invoicedata->op_charge_amt) ? $invoicedata->op_charge_amt : 0;
                                                $total += !empty($invoicedata->medicine_charge_amt) ? $invoicedata->medicine_charge_amt : 0;
                                                $total += !empty($invoicedata->op_charge_amts) ? $invoicedata->op_charge_amts : 0;
                                                $total += !empty($invoicedata->delivery_charge_amt) ? $invoicedata->delivery_charge_amt : 0;
                                                $total += !empty($invoicedata->ot_charge_amt) ? $invoicedata->ot_charge_amt : 0;
                                                $total += !empty($invoicedata->labour_charge_amt) ? $invoicedata->labour_charge_amt : 0;
                                                $total += !empty($invoicedata->anaesthesia_charge_amt) ? $invoicedata->anaesthesia_charge_amt : 0;
                                                $total += !empty($invoicedata->biopys_charge_amt) ? $invoicedata->biopys_charge_amt : 0;
                                                $total += !empty($invoicedata->sonography_charge_amt) ? $invoicedata->sonography_charge_amt : 0;
                                                $total += !empty($invoicedata->lscs_operation_charge) ? $invoicedata->lscs_operation_charge : 0;
                                                $total += !empty($invoicedata->third_other_charge_amt) ? $invoicedata->third_other_charge_amt : 0;
                                                $total += !empty($invoicedata->fourth_other_charge_amt) ? $invoicedata->fourth_other_charge_amt : 0;
                                            @endphp
                                            {{-- {{dd($invoicedata->form_total_amt)}} --}}
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Total:</span>
                                                        {{Form::number('form_total_amt', $total,[
                                                        'class'=>'form-control col-sm-6 final-amount',
                                                        'id'=>'total_amount',
                                                        'autocomplete' => 'off',
                                                        'placeholder'=>'Total',
                                                        'required'=>'required',
                                                        'readonly',
                                                        'min' => 0,
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                                
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">IVF Operation Charges:</span>
                                                        {{Form::text('op_charge_descs',!empty($invoicedata->op_charge_descs) ? $invoicedata->op_charge_descs : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('op_charge_amts',!empty($invoicedata->op_charge_amts) ? $invoicedata->op_charge_amts : null,[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'IVF Operation Charges',
                                                        'min' => 0,
                                                        'id' => 'op_charge_amts',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Discount:</span>
                                                    {{Form::number('discount_amt',!empty($invoicedata->discount_amt) ? $invoicedata->discount_amt : 0,['class'=>'form-control col-sm-6 final-amount','id'=>'discount','placeholder'=>'Discount',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                </div>
                                                <span class="form-error-msg discount discount_amt"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Delivery Charges:</span>
                                                        {{Form::text('delivery_charge_desc',!empty($invoicedata->delivery_charge_desc) ? $invoicedata->delivery_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('delivery_charge_amt',!empty($invoicedata->delivery_charge_amt) ? $invoicedata->delivery_charge_amt : null,[
                                                          'class'=>'form-control col-sm-2 amount',
                                                          'placeholder'=>'Amount',
                                                          'min' => 0,
                                                          'id' => 'delivery_charge_amt',
                                                          'autocomplete' => 'off',
                                                          $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                      ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Deposit:</span>
                                                    {{Form::number('deposit_amt', !empty($invoicedata->deposit_amt) ? $invoicedata->deposit_amt : 0, ['class' => 'form-control final-amount','id' => 'deposit','placeholder'=>'Deposit','min' => 0,$BookingData->is_final_invoice == 1 ? 'readonly' : '','autocomplete' => 'off'])}}
                                                    @if($BookingData->is_final_invoice != 1)
                                                        <a class="btn btn-sm text-white ml-1 deposit-refresh">Refresh</a>
                                                    @endif
                                                    {{-- <img src="{{URL::to('images/icons8-refresh-30.png')}}" width="20px" height="28px" class="col-md-1 mt-1 deposit-refresh"/> --}}
                                                </div>
                                                <span class="deposit_error_msg d-none">you don't have any deposit.</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">O.T Charges:</span>
                                                        {{Form::text('ot_charge_desc',!empty($invoicedata->ot_charge_desc) ? $invoicedata->ot_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('ot_charge_amt',!empty($invoicedata->ot_charge_amt) ? $invoicedata->ot_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount ',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'ot_charge_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Grand Total:</span>
                                                        {{Form::number('grand_total_amt',!empty($invoicedata->grand_total_amt) ? $invoicedata->grand_total_amt : 0,[
                                                        'class'=>'form-control col-sm-6  grand_total',
                                                        'id'=>'grand_total',
                                                        'required'=>'required',
                                                        'placeholder'=>'Grand Total',
                                                        'min' => 0,
                                                        'readonly',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                                <span class="form-error-msg grand_total_amt"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Labour Room Charges:</span>
                                                        {{Form::text('labour_charge_desc',!empty($invoicedata->labour_charge_desc) ? $invoicedata->labour_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('labour_charge_amt',!empty($invoicedata->labour_charge_amt) ? $invoicedata->labour_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'labour_charge_amt',
                                                        'autocomplete' => 'off', 
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row  payment input-group">
                                                    <div class="col-md-6 input-group-addon  unik-lbl-spn">Payment Mode:</div>
                                                    <div class="col-md-6 payment-mode">
                                                        <div class="radio is-conceived">
                                                            {{Form::radio("payment",'2',!(empty($invoicedata->payment_mode==2) ? true : false),['id'=>'cash','class'=>'cash-payment', $BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="cash">
                                                                Cash
                                                            </label>
                                                            {{Form::radio("payment",'1',!(empty($invoicedata->payment_mode==1) ? true : false),['id'=>'card','class'=>'card-payment', $BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="card">
                                                                Swipe
                                                            </label>

                                                            {{Form::radio("payment",'3',!(empty($invoicedata->payment_mode==3) ? true : false),['id'=>'cheque','class'=>'cheque-payment',$BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="cheque">
                                                                Cheque
                                                            </label>

                                                            {{Form::radio("payment",'4',!(empty($invoicedata->payment_mode==4) ? true : false),['id'=>'upi','class'=>'upi-payment',$BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="upi">
                                                                UPI
                                                            </label>

                                                            {{Form::radio("payment",'5',!(empty($invoicedata->payment_mode==5) ? true : false),['id'=>'neft','class'=>'neft-payment',$BookingData->is_final_invoice == 1 ? 'disabled' : ''])}}
                                                            <label for="neft">
                                                                NEFT
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <span class="form-error-msg payment"></span>
                                                </div>
                                            </div>
                                            @if($BookingData->is_final_invoice == 1)
                                                {{Form::hidden('payment', !empty($invoicedata->payment_mode) ? $invoicedata->payment_mode : null , ['id' => 'payment'])}}
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Anaesthesia Charges:</span>
                                                        {{Form::text('anaesthesia_charge_desc',!empty($invoicedata->anaesthesia_charge_desc) ? $invoicedata->anaesthesia_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('anaesthesia_charge_amt',!empty($invoicedata->anaesthesia_charge_amt) ? $invoicedata->anaesthesia_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'anaesthesia_charge_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Biopsy Charges:</span>
                                                        {{Form::text('biopys_charge_desc',!empty($invoicedata->biopys_charge_desc) ? $invoicedata->biopys_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('biopys_charge_amt',!empty($invoicedata->biopys_charge_amt) ? $invoicedata->biopys_charge_amt : null,[
                                                        'class'=>'form-control col-sm-2 amount',
                                                        'placeholder'=>'Amount',
                                                        'min' => 0,
                                                        'id' => 'biopys_charge_amt',
                                                        'autocomplete' => 'off',
                                                        $BookingData->is_final_invoice == 1 ? 'readonly' : ''
                                                    ])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group  unik-lbl-spn">
                                                    Current Deposit: <span class='current-deposit'>{{!empty($currentDeposit) ? $currentDeposit : 0}}</span>&nbsp;&nbsp;
                                                    <span class="text-danger">* If you want to use current deposit amount, then please click refresh button besides deposit amount.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon  unik-lbl-spn col-md-6">Ultra Sonography Charges:</span>
                                                        {{Form::text('sonography_charge_desc',!empty($invoicedata->sonography_charge_desc) ? $invoicedata->sonography_charge_desc : null,['class'=>'form-control col-sm-4','placeholder'=>'Desc',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                        {{Form::number('sonography_charge_amt',!empty($invoicedata->sonography_charge_amt) ? $invoicedata->sonography_charge_amt : null,['class'=>'form-control col-sm-2 amount','placeholder'=>'Amount','min' => 0,'id' => 'sonography_charge_amt','autocomplete' => 'off',$BookingData->is_final_invoice == 1 ? 'readonly' : ''])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                        </div>

                                        <div class="row">
                                            @if($BookingData->is_final_invoice == 0)
                                                {{Form::submit('Save', ['class'=>'btn btn-primary submit submit-button update-invoice invoice-save'])}}
                                                {{-- <a data-id="{{encrypt($invoicedata->booking_id)}}" class="final-invoice btn btn-primary update-invoice">Final Invoice</a> --}}
                                                <button type="submit" class="btn btn-primary submit update-invoice invoice-save" value="2">Final Invoice</button>
                                            @endif
                                            <button type="submit" data-id="{{encrypt($invoicedata->booking_id)}}" class="btn btn-primary submit update-invoice" value="1">Save & Preview</button>
                                            {{-- <button type="submit" class="btn btn-primary submit" value="3">Save & Send</button> --}}
                                            <a href="{{URL::previous()}}" class="btn btn-default update-invoice">Cancel</a>
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

            $('.amount').on('input', function(e) {
                value = $(this).val();
                $('#' + this.id).val(value);
                if(value < 0){
                    return $(this).val(Math.abs(value));
                }
                if(value.includes(".")){
                    return $(this).val(value.split('.')[0]);
                }
                if((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 6) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });
            $('.final-amount').on('input', function() {
                value = $(this).val();
                if(value < 0){
                    return $(this).val(Math.abs(value));
                }
                if(value.includes(".")){
                    return $(this).val(value.split('.')[0]);
                }
                var discountId = this.id;
                if(discountId == 'discount'){
                    var total = $('#total_amount').val();
                    if(parseInt(value) > parseInt(total)){
                        $(this).val(total);
                        return false;
                    }
                }
                if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                    return $('#discount').val($('#total_amount').val());
                }
                $('#' + this.id).val(value);
                if((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 10){
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });

            $('.grand_total').on('input', function() {
                value = $(this).val();
                if(value < 0){
                    return $(this).val(Math.abs(value));
                }
                if(value.includes(".")){
                    return $(this).val(value.split('.')[0]);          
                }
                $('#' + this.id).val(value);
                if ((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 10) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            }); 
            $(document).on('click','.submit',function(e){
                e.preventDefault();
                $(".invoice-save").attr("disabled", true);
                var invoice = $('#invoice-form').serialize();
                if(this.value == 1) {
                    invoice = invoice + '&isprint=1';
                }
                if(this.value == 2) {
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
                var url = "{{URL::to('/indoor')}}";
                $('.update-invoice').attr('readonly', true);
                $.ajax({
                    url: "{{URL::to('indoor/invoice/update')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: invoice,
                }).done(function(data){
                    if (data.status == 3) {
                        swal({ 
                            title: 'Oops',
                            text: 'Something went wrong',
                            type: 'error' 
                        },
                        function(){
                            window.location.href = url;
                        });
                    } else if (data.status == 2){
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(data.data);
                        w.document.close();
                        w.window.print();
                        window.location.href = url;
                    }else if(data.status == 4){
                        $('.deposit_error_msg').removeClass('d-none');
                        $('.update-invoice').attr('disabled', false);
                    }else if(data.status == 5){
                        $('.deposit_error_msg').removeClass('d-none');
                        $('.deposit_error_msg').text('your deposit limit reached');
                        $('.update-invoice').attr('disabled', false);
                    }else if(data.status == 6){
                        $('.deposit_error_msg').removeClass('d-none');
                        $('.deposit_error_msg').text("you can't add deposit more than the grand total amount");
                        $('.save-invoice').attr('disabled', false);
                    }else {
                        window.location.href = url;
                    }
                }).fail(function(error){
                    
                });
            }
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
                    if(discount == ''){
                        discount = 0;
                    }
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
                    if(sum == 0){
                        $('#discount').val(0);
                    }
                    if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                        return $('#discount').val($('#total_amount').val());
                    }
                    discount = $('#discount').val();
                    if(discount == ''){
                        discount = 0;
                    }
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
                    if(sum == 0){
                        $('#discount').val(0);
                    }
                    if(parseInt($('#discount').val()) > parseInt($('#total_amount').val())){
                        return $('#discount').val($('#total_amount').val());
                    }
                    discount = $('#discount').val();
                    if (discount == '') {
                        discount = 0;
                    }
                    deposit = $('#deposit').val();
                    cutDiscount = sum - discount;
                    gt = cutDiscount - deposit;
                    if(gt < 0){
                        return $('#grand_total').val(0);
                    }
                    $('#grand_total').val(gt);
                    total = Number(discount) + Number(deposit);
                    totalAmt = $('#total_amount').val();
                    if(totalAmt < total){
                        $('#grand_total').val(0);
                    }
                }
            });
            $('.amount').keyup(function() {
                sum = 0;
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
                if (discount == '') {
                    discount = 0;
                }
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
                
                discount = $('#discount').val();

                if (discount == '') {
                    discount = 0;
                }
                deposit = $('#deposit').val();
                cutDiscount = sum - discount;

                gt = cutDiscount - deposit;

                if (deposit > cutDiscount) {
                    gt = 0;
                }
                $('#grand_total').val(gt);
            });

            $(document).on('click','.deposit-refresh',function(e){
                var totalAmount = $('#total_amount').val();
                var discount = $('#discount').val();
                discount = discount == '' ? 0 : discount;
                var deposit = "{{$invoicedata->deposit_amt ? $invoicedata->deposit_amt : 0}}";
                var grandTotal = "{{$invoicedata->grand_total_amt ? $invoicedata->grand_total_amt : 0}}";
                var currentDeposit = "{{$currentDeposit}}";
                if((deposit != 0 && typeof deposit != 'undefined') || (currentDeposit != 0 && typeof currentDeposit != 'undefined')){
                    // var totalDeposit = parseInt(deposit) + parseInt(currentDeposit);
                    var totalDeposit = parseInt(currentDeposit);
                    grandTotal = (parseInt(totalAmount) - parseInt(discount)) - totalDeposit;
                    if(grandTotal < 0){
                        grandTotal = 0;
                    }
                    var subTotal = parseInt(totalAmount) - parseInt(discount);
                    var remainingDeposit = parseInt(totalDeposit) - parseInt(subTotal);
                    if(currentDeposit == 0){
                        currentDeposit = deposit;
                    }
                    if(subTotal > totalDeposit){
                        remainingDeposit = 0;
                        subTotal = totalDeposit;
                    }
                    // console.log('totalDeposit');
                    // console.log(totalDeposit);
                    // var useDeposit = parseInt(subTotal) - parseInt(deposit);
                    $('.current-deposit').text(remainingDeposit);
                    $('#old_current_deposit').val(remainingDeposit);
                    $('#grand_total').val(grandTotal);
                    $('#deposit').val(subTotal);
                }
            });
        });
    </script>
@stop

