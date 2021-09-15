@extends('layouts.main')
@section('parentPageTitle', 'IVF')
@section('title', 'IVF')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <style>
        .payment-form{
            padding: 5px 0px 1px 10px !important;
        }
        @media (min-width: 576px){
            .modal-dialog {
                max-width: 800px !important;
            }
        }
       


          @media (min-width: 360px){
           .data{
            margin-bottom: -22px;
         }
        }
        .data{
            margin-top: 10px;
        }
        .new_data{
            padding: 5px;
        }
        .row{
            margin-bottom: -17px;
        }
    </style>
@stop
@section('content')
    <!-- <div class="row clearfix ivf">
        <div class="col-md-12"> -->
    <div class="panel panel-primary">
        <div id="patients" class="" role="tabpanel" aria-labelledby="headingThree_1">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>IVF Payments</strong></h2>
                </div>
                    @if(Session::has('msg'))
                        <div class="alert alert-success">
                            <strong>Success!</strong> {{Session::get('msg')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="zmdi zmdi-close"></i>
                                </span>
                            </button>
                        </div>
                        @endif
                          <div class='alert alert-success d-none ivf-payment-msg'>
                            <strong>Success!</strong> Payment successfully added.
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>
                                    <i class='zmdi zmdi-close'></i>
                                </span>
                            </button>
                        </div> 

                    {{Form::open(['class'=>'form-inline','id'=>'ivf-payment-form','name' => 'form'])}}
                    {{csrf_field()}}
                     
                    @php
                        $is_readonly = (isset($is_deposite) && $is_deposite == 1) ? 'readonly' : '';    
                        $is_disabled = (isset($is_deposite) && $is_deposite == 1) ? 'disabled' : '';    
                    @endphp
                        <div class="modal-body col-md-12">
                            {{-- <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Deposit
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('deposit','0',['class'=>'form-control p-deposit','placeholder'=>'Deposit','min'=>1,'disabled'])}}
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row" >
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">{{__('payments.Patient')}} : &nbsp;</span>
                                        {{Form::text('p_name',$patients->name,['class'=>'form-control p-name-value'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">
                                            Date : &nbsp;
                                        </span>

                                        {{Form::text('date', \Carbon\Carbon::now()->format('D d M Y'), [
                                            'class' => 'form-control p-name-value date',
                                            'required'
                                        ])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8" style="margin-bottom: -15px;">
                                <div class="input-group">
                                        <span class="radio is-conceived">{{__('payments.Select')}} : &nbsp;</span>
                                        @php
                                            $history_lang = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->language)) ? $ivfPaymentHistory->language : '';
                                        @endphp
                                    <div class="radio is-conceived">
                                        
                                         {{Form::radio("language",'1',(!empty($history_lang) && $history_lang == '1') ? true : false,[
                                            'id'=>'gu',
                                            'class'=>'upt-type',''
                                        ])}}
                                        <label for="gu">
                                            Gujarati
                                        </label>

                                        {{Form::radio("language",'2',(!empty($history_lang) && $history_lang == '2') ? true : false,[
                                            'id'=>'in',
                                            'class'=>'upt-type',''
                                        ])}}
                                        <label for="in">
                                            Hindi
                                        </label>
                                         {{Form::radio("language",'3',(!empty($history_lang) && $history_lang == '3') ? true : false,['data-id' => $patientsId,
                                            'id'=>'en',
                                            'class'=>'english',''
                                        ])}}
                                        <label for="en">
                                            English
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Patient Name
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::text('p_name','',['class'=>'form-control','placeholder'=>'Enter Patient Name'])}}
                                    </div>
                                    <span class="form-error-msg cycle_error ml-5"></span>
                                </div>
                            </div> --}}
            
                            <div class="col-md-8" style="margin-bottom: -15px;">
                                <div class="input-group">
                                        <span class="radio is-conceived">Cycle Type :  &nbsp;</span>
                                    <div class="radio is-conceived">
                                        @php
                                            $cycle_type_check = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->cycle_type)) ? $ivfPaymentHistory->cycle_type : '';
                                        @endphp
                                        {{Form::radio("cycle_type",'SELF',(!empty($cycle_type_check) && $cycle_type_check == 'SELF') ? true : false ,[
                                            'id'=>'SELF',
                                            'class'=>'upt-type','checked',$is_disabled
                                        ])}}
                                        <label for="SELF">
                                            SELF
                                        </label>

                                        {{Form::radio("cycle_type",'OD',(!empty($cycle_type_check) && $cycle_type_check == 'OD') ? true : false,[
                                            'id'=>'OD',
                                            'class'=>'upt-type',$is_disabled
                                        ])}}
                                        <label for="OD">
                                            OD
                                        </label>
                                         {{Form::radio("cycle_type",'ED',(!empty($cycle_type_check) && $cycle_type_check == 'ED') ? true : false,[
                                            'id'=>'ED',
                                            'class'=>'upt-type',$is_disabled
                                        ])}}
                                        <label for="ED">
                                            ED
                                        </label>
                                         {{Form::radio("cycle_type",'SD',(!empty($cycle_type_check) && $cycle_type_check == 'SD') ? true : false,[
                                            'id'=>'SD',
                                            'class'=>'upt-type',$is_disabled
                                        ])}}
                                        <label for="SD">
                                            SD
                                        </label>
                                        {{Form::radio("cycle_type",'FET',(!empty($cycle_type_check) && $cycle_type_check == 'FET') ? true : false,[
                                            'id'=>'FET',
                                            'class'=>'upt-type',$is_disabled
                                        ])}}
                                        <label for="FET">
                                            FET
                                        </label>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-8" id="here">
                                <div class="input-group">
                                        <span class="radio is-conceived">No Attempt : &nbsp;</span>
                                        @php
                                            $history_cycle_no = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->cycle_no)) ? $ivfPaymentHistory->cycle_no : '';
                                            $display_multiple_no_cycle = (empty($history_cycle_no) || $history_cycle_no == 1) ? 'd-none' : '';
                                        @endphp
                                    <div class="radio is-conceived">
                                        {{Form::radio('no_cycle','1',(!empty($history_cycle_no) && $history_cycle_no == '1') ? true : false,[
                                            'id'=>'no_cycle',
                                            'class'=>'upt-type','checked',$is_disabled
                                        ])}}</a>
                                        <label for="no_cycle">
                                            One
                                        </label>

                                        {{Form::radio("no_cycle",'',(!empty($history_cycle_no) && $history_cycle_no != '1') ? true : false,[
                                            'id'=>'Multiple',
                                            'class'=>'upt-type',$is_disabled
                                        ])}}
                                        <label for="Multiple">
                                            Multiple
                                        </label>
                                    </div>
                                        <div class="{{'col-md-4 col-sm-6 radio is-conceived '.$display_multiple_no_cycle}}" id="multiple_no_cycle">
                                            {{Form::select('no_cycle',[2 =>'2',3=>'3',4=>'4',6 =>'6'],(!empty($history_cycle_no) && $history_cycle_no != '1') ? $history_cycle_no : null,['class'=>'form-control select-padding-0 multiple_no_cycle','multiple_no_cycle1' =>'new','placeholder'=>'Select No. Of Cycle',$is_disabled])}}
                                        </div>      
                                </div>
                            </div>
                            @php
                                $display_consulation = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->consulation)) ? '' : 'display:none';
                                $display_sonography_status = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->sonography_charge)) ? '' : 'display:none';
                                $display_ivf_lab_charge = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ivf_lab_charge)) ? '' : 'display:none';
                                $display_icsi_ivf_charge = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->icsi_ivf_charge)) ? '' : 'display:none';
                                $display_embroy_tranfer = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embroy_tranfer)) ? '' : 'display:none';
                                $display_embroy_freezing = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embroy_freezing)) ? '' : 'display:none';
                                $display_embryologist_charge = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embryologist_charge)) ? '' : 'display:none';
                                $display_surgeon_charge = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->surgeon_charge)) ? '' : 'display:none';
                                $display_semen_freezing_charge = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->semen_freezing_charge)) ? '' : 'display:none';
                                $display_medical_medicines = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->medical_medicines)) ? '' : 'display:none';
                                $display_anesthescis_doctor = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->anesthescis_doctor)) ? '' : 'display:none';
                                $display_blood_report_approx = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->blood_report_approx)) ? '' : 'display:none';
                                $display_tesa_pesa = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->tesa_pesa)) ? '' : 'display:none';
                                $display_ovum_embryopooling_approx = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ovum_embryopooling_approx)) ? '' : 'display:none';
                                $display_emdomatrial_report = (isset($ivfPaymentHistory) && (!empty($ivfPaymentHistory->TBPCR) || !empty($ivfPaymentHistory->PAMP) || !empty($ivfPaymentHistory->ERA))) ? '' : 'display:none';
                                $display_hystrocopy_approx = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->hystrocopy_approx)) ? '' : 'display:none';
                                $display_donor = (isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->donor_charge)) ? '' : 'display:none';
                                $display_gonadotropins = (isset($ivfPaymentHistory) && (!empty($ivfPaymentHistory->HMG_approx) || !empty($ivfPaymentHistory->RFSH_approx) || !empty($ivfPaymentHistory->GonalF_approx))) ? '' : 'display:none';

                            @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                            <div class="">
                                                <div class="checkbox" >
                                                    {{Form::checkbox('consulation_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->consulation)) ? true : false,['id'=>'consulation','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="consulation">
                                                      {{__('payments.consulation')}}
                                                    </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="consulation_show" style="{{$display_consulation}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon payment-form"> </span>
                                                    {{Form::number('consulation',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->consulation)) ? $ivfPaymentHistory->consulation : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','min'=>0,'id'=>'consulation_show1',$is_readonly])}} 
                                                    <!-- <span class="input-group-addon "> To </span> -->
                                                </div>
                                            </div>
                                        </div>  

                                         <!-- <div class="col-md-4">
                                            <div id="consulation_show2" style="display:none">
                                                <div class="input-group">
                                                    <span class="input-group-addon payment-form"> </span>
                                                    {{Form::number('consulation','',['class'=>'form-control col-md-6 new_sum ','min'=>0,'id'=>'consulation_show3'])}} 
                                                </div>
                                            </div>
                                        </div>   -->
                                    </div>  
                                </div>
                            </div>
                               
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                            <div class="">
                                                <div class="checkbox" >
                                                    {{Form::checkbox('sonography_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->sonography_charge)) ? true : false,['id'=>'sonography','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    
                                                    <label for="sonography">
                                                      {{__('payments.Sonography')}}
                                                    </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="Sonography_show" style="{{$display_sonography_status}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon payment-form"></span>
                                                {{Form::number('sonography',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->sonography_charge)) ? $ivfPaymentHistory->sonography_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'Sonography_show1','min'=>0,$is_readonly])}}
                                            </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('ivf_lab_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ivf_lab_charge)) ? true : false,['id'=>'ivf_lab_charge','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="ivf_lab_charge">
                                                  {{__('payments.IVF')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="ivf_lab_charge_show" style="{{$display_ivf_lab_charge}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                {{Form::number('ivf_lab_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ivf_lab_charge)) ? $ivfPaymentHistory->ivf_lab_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'ivf_lab_charge_show1','min'=>0,$is_readonly    ])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('gonadotropins_status','yes',(isset($ivfPaymentHistory) && (!empty($ivfPaymentHistory->HMG_approx) || !empty($ivfPaymentHistory->RFSH_approx) || !empty($ivfPaymentHistory->GonalF_approx))) ? true : false,['id'=>'gonadotropins_injection','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="gonadotropins_injection">
                                                  {{__('payments.Gonadotropins')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-sm-6">
                                            <div id="gonadotropins_injection_show" style="{{$display_gonadotropins}}">
                                                <div class="checkbox input-group" >
                                                    {{Form::checkbox('HMG_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->HMG_approx)) ? true : false,['id'=>'HMG_status','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="HMG_status">
                                                      HMG
                                                    </label>
                                                    <div class="input-group col-md-3 col-sm-6" id="HMG_show" style="{{!empty($ivfPaymentHistory->HMG_approx) ? '' : 'display:none'}}">
                                                        {{Form::number('HMG_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->HMG_approx)) ? $ivfPaymentHistory->HMG_approx : '',['class'=>'form-control col-md-6 emdomatrial_report1 new_sum ivf_payment','min'=>0,'id' => 'HMG1',$is_readonly])}}
                                                        <span class="input-group-addon"> To </span>
                                                         {{Form::number('HMG',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->HMG)) ? $ivfPaymentHistory->HMG : '',['class'=>'form-control col-md-6 sum ivf_payment','id'=>'HMG2','min'=>0,$is_readonly])}}
                                                    </div>
                                                <!-- </div>
                                                 <div class="checkbox input-group"> -->
                                                    {{Form::checkbox('RFSH_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->RFSH_approx)) ? true : false,['id'=>'RFSH_status','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="RFSH_status">
                                                      RFSH
                                                    </label>
                                                    <div class="input-group col-md-3 col-sm-6" id="RFSH_show" style="{{!empty($ivfPaymentHistory->RFSH_approx) ? '' : 'display:none'}}">
                                                        {{Form::number('RFSH_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->RFSH_approx)) ? $ivfPaymentHistory->RFSH_approx : '',['class'=>'form-control col-md-6 emdomatrial_report1 new_sum ivf_payment','min'=>0,'id' => 'RFSH1',$is_readonly])}}
                                                        <span class="input-group-addon"> To </span>
                                                         {{Form::number('RFSH',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->RFSH)) ? $ivfPaymentHistory->RFSH : '',['class'=>'form-control col-md-6 sum ivf_payment','id'=>'RFSH2','min'=>0,$is_readonly])}}
                                                    </div>
                                                <!-- </div>
                                                 <div class="checkbox input-group"> -->
                                                    {{Form::checkbox('Gonal_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->GonalF_approx)) ? true : false,['id'=>'Gonal_status','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="Gonal_status">
                                                      Gonal-F
                                                    </label>
                                                    <div class="input-group col-md-3 col-sm-6" id="Gonal_show" style="{{!empty($ivfPaymentHistory->GonalF_approx) ? '' : 'display:none'}}">
                                                        {{Form::number('GonalF_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->GonalF_approx)) ? $ivfPaymentHistory->GonalF_approx : '',['class'=>'form-control col-md-6 emdomatrial_report1 new_sum ivf_payment','min'=>0,'id' => 'Gonal_F1',$is_readonly])}}
                                                        <span class="input-group-addon"> To </span>
                                                         {{Form::number('Gonal_F',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->Gonal_F)) ? $ivfPaymentHistory->Gonal_F : '',['class'=>'form-control col-md-6 sum ivf_payment','id'=>'Gonal_F2','min'=>0,$is_readonly])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>

                            @php
                                $display_donor_div = (!empty($cycle_type_check) && $cycle_type_check == 'OD') ? '': 'display:none';
                            @endphp
                            <div class="row" id="donor" style="{{$display_donor_div}}">
                                <div class="col-md-12">
                                    <div class="input-group form-padding" >
                                            <div class="checkbox" >
                                                {{Form::checkbox('donor_charge_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->donor_charge)) ? true : false,['id'=>'donor_charge','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="donor_charge">
                                               {{__('payments.Donor')}} 
                                                </label>
                                            </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="donor_charge_show" style="{{$display_donor}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('donor_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->donor_charge)) ? $ivfPaymentHistory->donor_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'donor_charge_show1','min'=>0,$is_readonly])}}                                             
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('icsi_ivf_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->icsi_ivf_charge)) ? true : false,['id'=>'icsi_ivf_charge','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="icsi_ivf_charge">
                                                  {{__('payments.ICSI')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="icsi_ivf_charge_show" style="{{$display_icsi_ivf_charge}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                {{Form::number('icsi_ivf_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->icsi_ivf_charge)) ? $ivfPaymentHistory->icsi_ivf_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'icsi_ivf_charge_show1','min'=>0,$is_readonly])}}
                                                   
                                                </div>  
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('embryo_transfer_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embroy_tranfer)) ? true : false,['id'=>'embryo_transfer','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="embryo_transfer">
                                                  {{__('payments.Embryo')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="embryo_transfer_show" style="{{$display_embroy_tranfer}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('embryo_transfer',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embroy_tranfer)) ? $ivfPaymentHistory->embroy_tranfer : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'embryo_transfer_show1','min'=>0,$is_readonly])}}
                                                    
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('embryo_freezing_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embroy_freezing)) ? true : false,['id'=>'embryo_freezing','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="embryo_freezing">
                                                 {{__('payments.Embryo_Freezing')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="embryo_freezing_show" style="{{$display_embroy_freezing}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('embryo_freezing',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embroy_freezing)) ? $ivfPaymentHistory->embroy_freezing : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'embryo_freezing_show1','min'=>0,$is_readonly])}}
                                                    
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('embryologist_charge_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embryologist_charge)) ? true : false,['id'=>'embryologist_charge','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="embryologist_charge">
                                                 {{__('payments.Embryologist')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="embryologist_charge_show" style="{{$display_embryologist_charge}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('embryologist_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->embryologist_charge)) ? $ivfPaymentHistory->embryologist_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'embryologist_charge_show1','min'=>0,$is_readonly])}}
                                                     
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('surgeon_charge_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->surgeon_charge)) ? true : false,['id'=>'surgeon_charge','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="surgeon_charge">
                                                 {{__('payments.Surgeon')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="surgeon_charge_show" style="{{$display_surgeon_charge}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('surgeon_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->surgeon_charge)) ? $ivfPaymentHistory->surgeon_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'surgeon_charge_show1','min'=>0,$is_readonly])}}
                                                    
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('semen_freezing_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->semen_freezing_charge)) ? true : false,['id'=>'semen_freezing_charge','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="semen_freezing_charge">
                                                 {{__('payments.Semen')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div id="semen_freezing_charge_show" style="{{$display_semen_freezing_charge}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('semen_freezing_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->semen_freezing_charge)) ? $ivfPaymentHistory->semen_freezing_charge : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'semen_freezing_charge_show1','min'=>0,$is_readonly])}}
                                                   
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('medical_medicines_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->medical_medicines_approx)) ? true : false,['id'=>'medical_medicines','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="medical_medicines">
                                                 {{__('payments.Medical')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="medical_medicines_show" style="{{$display_medical_medicines}}">
                                                <div class="input-group">
                                                   <!-- <span class="input-group-addon payment-form"></span> -->
                                                    {{Form::number('medical_medicines_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->medical_medicines_approx)) ? $ivfPaymentHistory->medical_medicines_approx : '',['class'=>'form-control col-md-6 new_sum ivf_payment','id'=>'medical_medicines_show1','min'=>0,$is_readonly])}}
                                                     <span class="input-group-addon"> To </span>
                                                    {{Form::number('medical_medicines',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->medical_medicines)) ? $ivfPaymentHistory->medical_medicines : '',['class'=>'form-control col-md-6 sum ivf_payment','id'=>'medical_medicines_show2','min'=>0,$is_readonly])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('anesthescis_doctor_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->anesthescis_doctor)) ? true : false,['id'=>'anesthescis_doctor','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="anesthescis_doctor">
                                                 {{__('payments.Anesthesia')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="anesthescis_doctor_show" style="{{$display_anesthescis_doctor}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('anesthescis_doctor',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->anesthescis_doctor)) ? $ivfPaymentHistory->anesthescis_doctor : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'anesthescis_doctor_show1','min'=>0,$is_readonly])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('blood_report_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->blood_report_approx)) ? true : false,['id'=>'blood_report','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="blood_report">
                                                {{__('payments.Blood')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="blood_report_show" style="{{$display_blood_report_approx}}">
                                                <div class="input-group">
                                                   <!-- <span class="input-group-addon payment-form"></span> -->
                                                    {{Form::number('blood_report_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->blood_report_approx)) ? $ivfPaymentHistory->blood_report_approx : '',['class'=>'form-control col-md-6 new_sum ivf_payment','id'=>'blood_report_show1','min'=>0,$is_readonly])}}
                                                    <span class="input-group-addon"> To </span>
                                                    {{Form::number('blood_report',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->blood_report)) ? $ivfPaymentHistory->blood_report : '',['class'=>'form-control col-md-6 sum ivf_payment','id'=>'blood_report_show2','min'=>0,$is_readonly])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('tesa_pesa_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->tesa_pesa)) ? true : false,['id'=>'tesa_pesa','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="tesa_pesa">
                                                 {{__('payments.Tesa')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="tesa_pesa_show" style="{{$display_tesa_pesa}}">
                                                <div class="input-group">
                                                   <span class="input-group-addon payment-form"></span>
                                                    {{Form::number('tesa_pesa',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->tesa_pesa)) ? $ivfPaymentHistory->tesa_pesa : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','id'=>'tesa_pesa_show1','min'=>0,$is_readonly])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('ovum_embryopooling_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ovum_embryopooling_approx)) ? true : false,['id'=>'ovum_embryopooling','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="ovum_embryopooling">
                                                 {{__('payments.Ovum')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="ovum_embryopooling_show" style="{{$display_ovum_embryopooling_approx}}">
                                                <div class="input-group">
                                                   <!-- <span class="input-group-addon payment-form"></span> -->
                                                    {{Form::number('ovum_embryopooling_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ovum_embryopooling_approx)) ? $ivfPaymentHistory->ovum_embryopooling_approx : '',['class'=>'form-control col-md-6 new_sum ivf_payment','id'=>'ovum_embryopooling_show1','min'=>0,$is_readonly])}}
                                                    <span class="input-group-addon"> To </span>
                                                    {{Form::number('ovum_embryopooling',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ovum_embryopooling)) ? $ivfPaymentHistory->ovum_embryopooling : '',['class'=>'form-control col-md-6 sum ivf_payment','id'=>'ovum_embryopooling_show2','min'=>0,$is_readonly])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('hystrocopy_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->hystrocopy_approx)) ? true : false,['id'=>'hystrocopy','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="hystrocopy">
                                                 {{__('payments.hysteroscopy')}}
                                                </label>
                                            </div>  
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div id="hystrocopy_show" style="{{$display_hystrocopy_approx}}">
                                                <div class="input-group">
                                                   <!-- <span class="input-group-addon payment-form"></span> -->
                                                    {{Form::number('hystrocopy_approx',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->hystrocopy_approx)) ? $ivfPaymentHistory->hystrocopy_approx : '',['class'=>'form-control col-md-6 new_sum ivf_payment','min'=>0,'id' => 'hystrocopy_show1',$is_readonly])}}
                                                     <span class="input-group-addon"> To </span>
                                                    {{Form::number('hystrocopy',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->hystrocopy)) ? $ivfPaymentHistory->hystrocopy : '',['class'=>'form-control col-md-6 sum ivf_payment','min'=>0,'id' => 'hystrocopy_show2',$is_readonly])}}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">
                                       <div class="">
                                            <div class="checkbox" >
                                                {{Form::checkbox('emdomatrial_report','yes',(isset($ivfPaymentHistory) && (!empty($ivfPaymentHistory->TBPCR) || !empty($ivfPaymentHistory->PAMP) || !empty($ivfPaymentHistory->ERA))) ? true : false,['id'=>'emdomatrial_report','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                <label for="emdomatrial_report">
                                                  {{__('payments.Endomatrial')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-sm-10">
                                            <div id="emdomatrial_report_show" style="{{$display_emdomatrial_report}}">
                                                <div class="checkbox" id="data">
                                                    {{Form::checkbox('TBPCR_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->TBPCR)) ? true : false,['id'=>'TBPCR','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="TBPCR">
                                                      TBPCR (Approx 2000/-)
                                                    </label>
                                                        {{Form::number('TBPCR',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->TBPCR)) ? $ivfPaymentHistory->TBPCR : '',['class'=>'form-control col-md-6 emdomatrial_report1 sum new_sum ivf_payment','min'=>0,'id' => 'TBPCR_show','style'=>"display:none",$is_readonly])}}
                                               <!--  </div>
                                                 <div class="checkbox" > -->
                                                    {{Form::checkbox('PAMP_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->PAMP)) ? true : false,['id'=>'PAMP','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="PAMP">
                                                      PAMP (Approx 4200/-)
                                                    </label>
                                                        {{Form::number('PAMP',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->PAMP)) ? $ivfPaymentHistory->PAMP : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','min'=>0,'id' => 'PAMP_show','style'=>"display:none",$is_readonly])}}

                                                <!-- </div> -->
                                                 <!-- <div class="checkbox" > -->
                                                    {{Form::checkbox('ERA_status','yes',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ERA)) ? true : false,['id'=>'ERA','class'=>'plan-management','data-id'=>'cbc-mp-details',$is_disabled])}}
                                                    <label for="ERA">
                                                      ERA (Approx 40,000/-)
                                                    </label>
                                                        {{Form::number('ERA',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->ERA)) ? $ivfPaymentHistory->ERA : '',['class'=>'form-control col-md-6 sum new_sum ivf_payment','min'=>0,'id' => 'ERA_show','style'=>"display:none",$is_readonly])}}
                                                        <label hidden=""></label>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="row">
                                 <div class="col-md-3">
                                      <!--   <td class="input-group form-padding"><input type="text" name="package" id="sum" name="package">0</span></td> -->
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form" id="">Total : &nbsp;</span>
                                        {{Form::number('total','',['class'=>'form-control','id'=>'new_total','min'=>1,'readonly'])}}
                                        {{Form::number('total','',['class'=>'form-control','id'=>'total','min'=>1,'readonly'])}} 
                                         
                                    </div>
                                </div>
                                <div class="col-md-3">
                                      <!--   <td class="input-group form-padding"><input type="text" name="package" id="sum" name="package">0</span></td> -->
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form" id="">Package : &nbsp;</span>
                                        {{Form::number('package',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->package)) ? $ivfPaymentHistory->package : '',['class'=>'form-control add_sum ivf_payment p-total-package','id'=>'package','min'=>1,$is_readonly])}} 
                                    </div>
                                    <span class="form-error-msg p-total-package-error m-0 p-1"></span>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Payment : &nbsp;</span>
                                        {{Form::number('payment',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->payment)) ? $ivfPaymentHistory->payment : '',['class'=>'form-control p-total-payment add_sum ivf_payment','min'=>1,'id' => 'payment','onkeypress'=>"return event.charCode >= 48 && event.charCode <= 57",$is_readonly])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error m-0 p-1"></span>
                                </div> 
                                 <div class="col-md-3">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Extra Charge : &nbsp;</span>
                                        {{Form::number('extra_charge',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->extra_charge)) ? $ivfPaymentHistory->extra_charge : '',['class'=>'form-control add_sum ivf_payment','id' => 'extra_charge','min'=>1,'onkeypress'=>"return event.charCode >= 48 && event.charCode <= 57"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-extra_charge m-0"></span>

                                </div>
                                
                            </div>

                            <div class="row">   
                                <div class="col-md-3">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Discount : &nbsp;</span>
                                        {{Form::number('discount',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->discount)) ? $ivfPaymentHistory->discount : '',['class'=>'form-control p-total-payment add_sum ivf_payment','id' => 'discount','min'=>1,'onkeypress'=>"return event.charCode >= 48 && event.charCode <= 57",$is_readonly])}}
                                    </div>
                                </div>                           
                                <div class="col-md-3">
                                    <div class="form-group form-padding">
                                        {{Form::select('payment_type',['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->payment_type)) ? $ivfPaymentHistory->payment_type : '',['class'=>'form-control payment-method','placeholder'=>'Select Payment Type',$is_disabled])}}
                                    <span class="form-error-msg p-total-payment_type-error m-0 p-1"></span>
                                    </div>
                                    <span class="form-error-msg hchargeerror">
                                        {{$errors->first('hcharge')}}
                                    </span>
                                 </div>
                                <div class="col-md-3">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Next Payment Date : &nbsp;</span>
                                        {{Form::text('remaining_date','',['class'=>'form-control datetimepicker','placeholder'=>'Enter Only Day',])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Next Payment Amount : &nbsp;</span>
                                        {{Form::number('next_payment_amt','',['class'=>'form-control','placeholder'=>'Next Payment Amount',])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Condition : &nbsp;</span>
                                        {{Form::text('condition',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->condition)) ? $ivfPaymentHistory->condition : '',['class'=>'form-control'])}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group form-padding">
                                        <span class="input-group-addon payment-form">Remark : &nbsp;</span>
                                        {{Form::text('remark',(isset($ivfPaymentHistory) && !empty($ivfPaymentHistory->remark)) ? $ivfPaymentHistory->remark : '',['class'=>'form-control p-remark','autocomplete'=>'off'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">Net Amount:<span id="net_amount" class="sum"></span></div>
                                </div>
                            </div>
                             
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding"> {{__('payments.terms1')}}<span id="" class=""></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">  {{__('payments.terms2')}}<span id="" class=""></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group form-padding">  {{__('payments.terms3')}}<span id="" class=""></span></div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-padding">  {{__('payments.terms4')}}<span id="" class=""></span></div>
                                </div>
                            </div> -->

                          <!--  <div class="row">
                                <div class="col-md-6 text-left ml-2 total-paid-ivf-payment">
                                    
                                </div>
                            </div> -->
                            <!-- <hr> -->
                            <!-- {{Form::hidden('patients_id','',['class'=>'patients-id'])}} -->
                            <input type="hidden" name="patients_id" id="patients_id" value="{{$patientsId}}">
                            </div>
                        
                        <!-- footer -->
                        <!-- <div class="modal-footer"> -->
                        <div class="form-padding">
                            <div class="input-group form-padding">
                                <button type="button" class="btn btn-primary waves-effect ivf-payment-submit">Save</button>
                                <button type="button" class="btn btn-primary waves-effect ivf-payment-submit ml-3" value="1">Save & Preview</button>
                                <!-- <a href="{{URL::to('ivf')}}"><button type="button" class="btn btn-default waves-effect ml-3" data-dismiss="modal">Cancle</button></a> -->
                                <a href="{{url()->previous()}}"><button type="button" class="btn btn-default waves-effect ml-3" data-dismiss="modal">Cancle</button></a>

                            </div>
                        </div>

                    {{Form::close()}}
                </div>
            </div>
        </div>
    
@stop
@section('page-script')
<script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">

    $(document).ready(function(){
    });
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY',
        // minDate:new Date(),
        clearButton: true,
        time:false
    });
        $('.ivf_payment').on('input', function() {
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
        
        sum = 0;
        $('.new_sum').each(function () {
            sum+=Number($(this).val());
        })
        $('#new_total').val(sum);

        function newPackagedata() {
            newsum = 0;
            $('.new_sum').each(function () {
                newsum+=Number($(this).val());
            })
            $('#new_total').val(newsum);
         }
          $('.new_sum').keyup(function () {
             var new_sum = 0;
             $('.new_sum').each(function () {
                 if (!isNaN(this.value) && this.value.length != 0) {
                     new_sum += parseFloat(this.value);
                 }
             }); 
             $('#new_total').val(new_sum);
              packageData();

         });

        sum = 0;
        $('.sum').each(function () {
            sum+=Number($(this).val());
        })
        $('#total').val(sum);

        function addPackagedata() {
            sum = 0;
            $('.sum').each(function () {
                sum+=Number($(this).val());
            })
            $('#total').val(sum);
         }
        
        $('.sum').keyup(function () {
             var sum = 0;
             $('.sum').each(function () {
                 if (!isNaN(this.value) && this.value.length != 0) {
                     sum += parseFloat(this.value);
                 }
             }); 
             $('#total').val(sum);
              packageData();

         });
    
        $('#discount').keyup( function() {
            sum = 0;
            $('.sum').each(function() {
                sum += Number($(this).val());
            });
            if(parseInt($('#discount').val()) > parseInt($('#package').val())){
                return $('#discount').val($('#package').val());
            }
        });

        $('#payment').keyup( function() {
            sum = 0;
            $('.sum').each(function() {
                sum += Number($(this).val());
            });
            if(parseInt($('#payment').val()) > parseInt($('#package').val())){
                return $('#payment').val($('#package').val());
            }
            
        });

        function packageData() {
            var sum = $('#package').val();
            var payment = $('#payment').val();
            var extra_charge = $('#extra_charge').val();
            var discount = $('#discount').val();
            var sum_extracharge = Number(sum) + Number(extra_charge);
            var total = (sum_extracharge - discount);
            $('#net_amount').text(total);
        }

        $('.add_sum').keyup(function () {
            var sum = $('#package').val();
            var payment = $('#payment').val();
            var extra_charge = $('#extra_charge').val();
            var discount = $('#discount').val();
            var sum_extracharge = Number(sum) + Number(extra_charge);
            var total = (sum_extracharge - discount);
            $('#net_amount').text(total);
            // $('#total').val(total);

        });

        $('#en').prop("checked", true);
        var p_id = $('#patients_id').val();
        var url = "{{url('ivf/payments/').'/'}}"+p_id+"{{('/en')}}";
        var url1 = "{{url('ivf/payments/').'/'}}"+p_id+"{{('/in')}}";
        var url2 = "{{url('ivf/payments/').'/'}}"+p_id+"{{('/gu')}}";
        var new_url = window.location.href;

        if (url == new_url) {
            $('#en').prop("checked", true);
        }else if(url1 == new_url){
            $('#in').prop("checked", true);
        }else if (url2 == new_url) {
            $('#gu').prop("checked", true);
        }

        $(document).on('click','#en',function(e){
            var en = $('#patients_id').val();            
            window.location.href = "{{url('ivf/payments/').'/'}}"+en+"{{('/en')}}";

        })

        $(document).on('click','#in',function(e){
            var hi = $('#patients_id').val();
            window.location.href = "{{url('ivf/payments/').'/'}}"+hi+"{{('/in')}}";
            // window.location = "{{url('ivf/payments/'.$patientsId.'/hi')}}";
        })

        $(document).on('click','#gu',function(e){
            var gu = $('#patients_id').val();
            window.location.href = "{{url('ivf/payments/').'/'}}"+gu+"{{('/gu')}}";
            // window.location = "{{url('ivf/payments/'.$patientsId.'/gu')}}";
        })
       $('.date').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
        });

        $(function () {
            $("#Multiple").click(function () {
                $("#multiple_no_cycle").removeClass('d-none');
            });
        });
            
        $(function () {
            $("#no_cycle").click(function () {
                $('#multiple_no_cycle option').prop('selected', false)
                $("#multiple_no_cycle").addClass('d-none');
            });
        });

        // $(function () {
        $("#consulation").click(function () {
            if ($(this).is(":checked")) {
                $("#consulation_show").show();
                $("#consulation_show2").show();
                $("#consulation_show1").val(400);
                addPackagedata();newPackagedata();

            } else {
                $("#consulation_show").hide();
                $("#consulation_show2").hide();
                $("#consulation_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });
         
        // $(function () {
        $("#sonography").click(function () {
            if ($(this).is(":checked")) {
                $("#Sonography_show").show();
                $("#Sonography_show1").val(1000);
                addPackagedata();newPackagedata();

            } else {
                $("#Sonography_show").hide();
                $("#Sonography_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });
        
        // $(function () {
        $("#ivf_lab_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#ivf_lab_charge_show").show();
                $("#ivf_lab_charge_show1").val(40000);
                addPackagedata();newPackagedata();
            } else {
                $("#ivf_lab_charge_show").hide();
                $("#ivf_lab_charge_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#gonadotropins_injection").click(function () {
            if ($(this).is(":checked")) {
                $("#gonadotropins_injection_show").show();
            } else {
                $("#gonadotropins_injection_show").hide();
                $("#HMG_status").prop("checked", false);
                $('#HMG_show').hide('');
                $('#HMG1').val('');
                $('#HMG2').val('');
                $("#RFSH_status").prop("checked", false);
                $('#RFSH_show').hide('');
                $('#RFSH1').val('');
                $('#RFSH2').val('');
                $("#Gonal_status").prop("checked", false);
                $('#Gonal_show').hide('');
                $('#Gonal_F1').val('');
                $('#Gonal_F2').val('');
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#HMG_status").click(function () {
            if ($(this).is(":checked")) {
                $("#HMG_show").show();
                $("#HMG1").val(20000);
                $("#HMG2").val(25000);
                addPackagedata();newPackagedata();
            } else {
                $("#HMG_show").hide();
                $("#HMG1").val('');
                $('#HMG2').val('');
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#RFSH_status").click(function () {
            if ($(this).is(":checked")) {
                $("#RFSH_show").show();
                $("#RFSH1").val(25000);
                $("#RFSH2").val(35000);
                addPackagedata();newPackagedata();
            } else {
                $("#RFSH_show").hide();
                $("#RFSH1").val('');
                $('#RFSH2').val('');
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#Gonal_status").click(function () {
            if ($(this).is(":checked")) {
                $("#Gonal_show").show();
                $("#Gonal_F1").val(40000);
                $("#Gonal_F2").val(50000);
                addPackagedata();newPackagedata();
            } else {
                $("#Gonal_show").hide();
                $("#Gonal_F1").val('');
                $('#Gonal_F2').val('');
                addPackagedata();newPackagedata();
                }
            });
        // });
        
        // $(function () {
        $("#icsi_ivf_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#icsi_ivf_charge_show").show();
                $("#icsi_ivf_charge_show1").val(10000);
                addPackagedata();newPackagedata();
            } else {
                $("#icsi_ivf_charge_show").hide();
                $("#icsi_ivf_charge_show1").val('');
                addPackagedata();newPackagedata();

                }
            });
        // });
        
        // $(function () {
        $("#embryo_transfer").click(function () {
            if ($(this).is(":checked")) {
                $("#embryo_transfer_show").show();
                $("#embryo_transfer_show1").val(3000);
                addPackagedata();newPackagedata();
            } else {
                $("#embryo_transfer_show").hide();
                $("#embryo_transfer_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });
      
        // $(function () {
        $("#embryo_freezing").click(function () {
            if ($(this).is(":checked")) {
                $("#embryo_freezing_show").show();
                $("#embryo_freezing_show1").val(5000);
                addPackagedata();newPackagedata();
            } else {
                $("#embryo_freezing_show").hide();
                $("#embryo_freezing_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });
    
        // $(function () {
        $("#embryologist_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#embryologist_charge_show").show();
                $("#embryologist_charge_show1").val(8000);
                addPackagedata();newPackagedata();

            } else {
                $("#embryologist_charge_show").hide();
                $("#embryologist_charge_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });

        
        // $(function () {
        $("#surgeon_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#surgeon_charge_show").show();
                $("#surgeon_charge_show1").val(5000);
                addPackagedata();newPackagedata();
            } else {
                $("#surgeon_charge_show").hide();
                $("#surgeon_charge_show1").val('');
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#semen_freezing_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#semen_freezing_charge_show").show();
                $('#semen_freezing_charge_show1').val(2000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#semen_freezing_charge_show").hide();
                $('#semen_freezing_charge_show1').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });

      
         // $(function () {
        $("#medical_medicines").click(function () {
            if ($(this).is(":checked")) {
                $("#medical_medicines_show").show();
                $('#medical_medicines_show1').val(10000);
                $('#medical_medicines_show2').val(15000);

                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#medical_medicines_show").hide();
                $('#medical_medicines_show1').val('');
                $('#medical_medicines_show2').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });

        /* $(function () {
        $("#unconscious_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#unconscious_charge_show").show();
                $('#unconscious_charge_show1').val(15000);
                addPackagedata();packageData();
            } else {
                $("#unconscious_charge_show").hide();
                $('#unconscious_charge_show1').val('');
                addPackagedata();packageData();

                }
            });
        });*/

        // $(function () {
        $("#anesthescis_doctor").click(function () {
            if ($(this).is(":checked")) {
                $("#anesthescis_doctor_show").show();
                $('#anesthescis_doctor_show1').val(2500);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#anesthescis_doctor_show").hide();
                $('#anesthescis_doctor_show1').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#blood_report").click(function () {
            if ($(this).is(":checked")) {
                $("#blood_report_show").show();
                $('#blood_report_show1').val(2000);
                $('#blood_report_show2').val(3000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#blood_report_show").hide();
                $('#blood_report_show1').val('');
                $('#blood_report_show2').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });
        // $(function () {
        $("#tesa_pesa").click(function () {
            if ($(this).is(":checked")) {
                $("#tesa_pesa_show").show();
                $('#tesa_pesa_show1').val(15000);   
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#tesa_pesa_show").hide();
                $('#tesa_pesa_show1').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });
   
        // $(function () {
        $("#ovum_embryopooling").click(function () {
            if ($(this).is(":checked")) {
                $("#ovum_embryopooling_show").show();
                $('#ovum_embryopooling_show1').val(60000);
                $('#ovum_embryopooling_show2').val(80000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#ovum_embryopooling_show").hide();
                $('#ovum_embryopooling_show1').val('');
                $('#ovum_embryopooling_show2').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();

                }
            });
        // });

        // $(function () {
        $("#hystrocopy").click(function () {
            if ($(this).is(":checked")) {
                $("#hystrocopy_show").show();
                $('#hystrocopy_show1').val(12000);
                $('#hystrocopy_show2').val(15000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#hystrocopy_show").hide();
                $('#hystrocopy_show1').val('');
                $('#hystrocopy_show2').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });

        // $(function () {
        $("#donor_charge").click(function () {
            if ($(this).is(":checked")) {
                $("#donor_charge_show").show();
                $('#donor_charge_show1').val(25000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
            } else {
                $("#donor_charge_show").hide();
                $('#donor_charge_show1').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                }
            });
        // });
       /* $(function () {
            $("#gonadotropins_injection").click(function () {
                $("#gonadotropins_injection_show").toggle();
            });
        });*/
      
        // $(function () {
        $("#emdomatrial_report").click(function () {
            if ($(this).is(":checked")) {
                $("#emdomatrial_report_show").show();
                
                // addPackagedata();
            } else {
                $("#emdomatrial_report_show").hide();
                $('#TBPCR_show').val('');
                $("#TBPCR").prop("checked", false);
                $("#TBPCR_show").hide();
                $('#PAMP_show').val('');
                $("#PAMP").prop("checked", false);
                $("#PAMP_show").hide();
                $('#ERA_show').val('');
                $("#ERA").prop("checked", false);
                $("#ERA_show").hide();
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


                }
            });
        // });

         // $(function () {
            $("#OD").click(function () {
                $("#donor").show();
            });
        // });
         // $(function () {
            $("#SELF").click(function () {
                $("#donor").hide();
                $('#donor_charge_show1').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


            });
        // });
        // $(function () {
            $("#ED").click(function () {
                $("#donor").hide();
                $('#donor_charge_show1').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


            });
        // });
        // $(function () {
            $("#SD").click(function () {
                $('#donor_charge_show1').val('');
                $("#donor").hide();
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


            });
        // });
    
        // $(function () {
        $("#TBPCR").click(function () {
            if ($(this).is(":checked")) {
                $("#TBPCR_show").show();
                $('#TBPCR_show').val(2000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                addPackagedata();newPackagedata();

            } else {
                $("#TBPCR_show").hide();
                $('#TBPCR_show').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


                }
            });
        // });

        // $(function () {
        $("#PAMP").click(function () {
            if ($(this).is(":checked")) {
                $("#PAMP_show").show();
                $('#PAMP_show').val(4200);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();

            } else {
                $("#PAMP_show").hide();
                $('#PAMP_show').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


                }
            });
        // });
     
        // $(function () {
        $("#ERA").click(function () {
            if ($(this).is(":checked")) {
                $("#ERA_show").show();
                $('#ERA_show').val(40000);
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();
                
            } else {
                $("#ERA_show").hide();
                $('#ERA_show').val('');
                // addPackagedata();packageData();
                addPackagedata();newPackagedata();


                }
            });
        // });

        // get appointment data
        function getIvfData(qstring){
            $('.ivf-loader').removeClass('d-none');
            $('.ivfdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('ivf')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.ivf-data').html(data.ivf);
                    $('.ivf-loader').addClass('d-none');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.ivf);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }

        function getIvfPayment(pId){
            $.ajax({
                url: "{{URL::to('ivf-payment')}}"+'/'+pId,
                dataType: 'json',
            }).done(function(data) {
                $('.p-r-amount').text('');
                $('#ivf-payment-form').trigger('reset');
                var deposit = data.deposit;
                var paymentData = data.ivfPayment;
                if(deposit){
                    $('.p-deposit').val(deposit.total);
                }
                if(paymentData){
                    // $('.p-r-amount').text(paymentData.remaining_payment != null ? '₹'+paymentData.remaining_payment : '');
                    // $('.p-total-payment').val(paymentData.total_payment);
                    // $('.p-remark').val(paymentData.remark);
                    // $('.p-time').val(paymentData.time);
                    // $('.p-no-cycle').val(paymentData.cycle_no);
                }
            }).fail(function() {

            });
        }

        $(document).ready(function(){            
            $(document).on('click','.ivf-payment-submit',function(e){
                e.preventDefault();
                var paymentData = $('#ivf-payment-form').serialize();
                if('#Multiple:checked')
                {
                    var cycle_no = $('select.multiple_no_cycle').val();
                    paymentData = paymentData + '&multiple_cycle='+cycle_no;
                }
                var cycle_no = $('select.payment-method').val();
                    paymentData = paymentData + '&payment_type='+cycle_no;
                if(this.value == 1) {
                    paymentData = paymentData + '&isprint=1';
                }
                storeIvfPayment(paymentData);
            });
        });

        /* if (parseInt($('#extra_charge').val()) > parseInt($('#package').val())) {
                $('.p-total-payment-extra_charge').text('Extra Charge can not be greater than Package');
                return false;
            }else{
                 $('.p-total-payment-extra_charge').empty('');
            }*/

        function storeIvfPayment(data){
            
            $('.p-total-payment-error').text('');
            $('.p-total-package-error').text('');
            $('.p-total-payment_type-error').text('');
            $('.cycle_error').text('');
            var isError = 0;
            if($('.p-total-package').val() == ''){
                $('.p-total-package-error').text('This field is required.');
                isError = 1;
            }
            if($('.p-total-payment').val() != ''){
                if($('select.payment-method').val() == ''){
                    $('.p-total-payment_type-error').text('The payment method field is required.');
                    isError = 1;
                }
            }
            if($('.p-no-cycle').val() == ''){
                    $('.cycle_error').text('This field is required.');
                    isError = 1;
                }
            if(isError == 1){
                return true;
            }
            // return false;
            $.ajax({
                url: "{{URL::to('ivf-store-payment_newui')}}",
                dataType: 'json',
                type:"POST",
                data:data
            }).done(function(data) {
                if(data.status == 1){    
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    setTimeout(function() {
                        w.window.print();
                    }, 50);
                        // $('#ivf_history_id').val(data.id);
                }else if(data.status == 'true'){
                    $('.ivf-payment-msg').removeClass('d-none');
                    // $('#ivf-payment').modal('hide');
                    $('#ivf-payment-form').trigger('reset');
                }else{
                    location.reload();
                }
            }).fail(function() {

            });
        }

        function depositData(){
            var value = $('.p-total-payment').val();
            var deposit = $('.p-deposit').val();
            var finalValue = deposit - value;
            $('.p-r-amount').text('');
            if(typeof finalValue != 'undefined' && $.isNumeric(finalValue) && deposit != finalValue){
                if(finalValue < 0){
                    finalValue = Math.abs(finalValue);
                }
                $('.p-r-amount').text('₹'+finalValue);
            }
        }

    </script>
@stop
