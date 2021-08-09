@extends('layouts.main')
@section('parentPageTitle', 'IUI Appointment')
@section('title', 'Update IUI Appointment')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body{
            min-height:950px !important;
        }
        .dropdown-menu ul{
            max-height:140px !important
        }
        .is-conceived{
            margin-top:7px;
        }
        .input-group.input-group-focus .input-group-addon{
            border-color:#01d8da !important;
        }
        .input-group-addon{
            color:#a0a0a0 !important;
            font-size:small !important;
        }
        .form-label{
            padding:0px 10px 0px 10px !important;
            color:black;
        }
        .vertical-form-label{
            padding:8px 10px 0px 10px !important;
        }
        .same{
            margin-right: 30px
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }
        .complain-multi > .co_value_data.multi{
            padding:5px !important;
        }
        .complain-multi > .co_value_data{
            height: auto !important;
        }
        .selectize-input{
            height: 100% !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 17px !important;
            padding-left: 16px;
            padding-right: 16px;
        }
        .selectize-control.multi .selectize-input.has-items{
            height: auto !important;
            padding: 6px 16px !important;
        }
        .selectize-input > input{
            width: auto !important;
        }
        .selectize-dropdown-content{
            height: auto !important;
            font-size: 13px !important;
            line-height: 24px !important;
        }
        .selectize-dropdown{
            height: auto !important;
            z-index: 1000 !important;
            width: 100% !important;
            margin-top: 11px !important;
            background: #fff !important;
        }
        .form-error-msg{
            margin: 8px 10px 10px 11px !important;
        }
        .lmd-date-diff{
            color:green !important;
        }
        .visit-lable{
            color:#999;
        }
        .visit-lable-value{
            color:black;
        }
        .iui-table-visit-data {
            width: 50% !important;
        }

        /* .width-iui {
            width: 10%;
        } */

        .iui-history-visit-data{
            border: 1px solid;
            padding: 20px;
        }
        .selectize-control .selectize-input.disabled {
            opacity: 1 !important;
            background-color: #fff !important;

        }
        .selectize-control.multi .selectize-input.disabled [data-value]{
            color: black !important;
        }
        .remove-border{
            border : none !important;
        }
        .w-49{
            width: 49% !important;
        }
        [class^="iui-"], [class*="iui-"] {
            font-family: roboto, sans-serif, Arial !important;
        }
        
        .modal-dialog {
            width: 100%;
            /* height: 100%; */
            padding: 0;
        }
        .modal-content {
            height: 100%;
            border-radius: 0;
        }
        @media (min-width: 576px){
            .view-file-modal-dialog {
                /* max-width: 1800px !important; */
                max-width: 90% !important;
                margin: 120px auto;
            }

        }
        .footer-top-border{
            border-top: 1px solid #9ea2a2 !important;
            padding: 1rem !important;
        }
        .header-bottom-border{
            border-bottom: 1px solid #9ea2a2 !important;
            padding: 1rem 1rem !important;
        }
        .anc-details-close{
            margin-top: -22px !important;
        }
        .cycle-button{
            position: static !important;
        }
        .overy-popup{
            cursor: pointer;
        }
        .child-no-box
        {
            margin: 0px;
        }
        .ui-widget-content
        {
            background: white !important;
        }
        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #1e5f63 !important;
            background: #1e5f63 !important;
            color: white !important;
        }
        .ui-datepicker .ui-datepicker-calendar .ui-state-highlight a {
            background: #1e5f63 !important;
        }
        a.ui-state-default:hover, a.ui-datepicker-prev.ui-corner-all:hover ,a.ui-datepicker-next.ui-corner-all:hover {
            background: #1e5f63 !important;
        }
        
        .table-layout
        {
            table-layout: fixed !important;
            width: inherit;
        }
        .view-file-modal-dialog .modal-content .modal-body
        {
            line-height:0.9 !important;
        }
        .follicular_div_1, .follicular_div_2
        {
            text-align:left !important;
        }
        .follicular_div_2
        {
            padding-left : 9rem !important;
        }
    </style>
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header d-flex">
                    @php
                        $careof = isset($referenceDoctor[$iui->getPatientsInfo['reference_doctor_id']]) ? $referenceDoctor[$iui->getPatientsInfo['reference_doctor_id']]: '';
                    @endphp
                    <div class="col-md-6">
                        <h2><strong class="text-secondary">{{ucwords($iui->getPatientsInfo->name)}}</strong>{{' care of '.$careof}}</h2>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{URL::to('get-all-report/'.encrypt($iui->getPatientsInfo->id).'?status=iui')}}" class="btn btn-primary mr-1">View Reports</a>
                        <a class="btn btn-primary view-file-edit ml-2">View File & Edit</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="row clearfix iui-history">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>IUI Appointment</strong>
                    </h2>
                    <ul class="header-dropdown col-md-12 text-right">
                        <li class="w-25">
                            {{Form::select("cycle_no",$iuiCycleNo,'',['class'=>'form-control select-padding-0 iui-cycle-no','placeholder'=>'Select Cycle No.'])}}
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                {{Session::get('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                        @endif
                        {{Form::hidden('patients_id',$patientsId,['class'=>'patients-id'])}}
                        <div class="iui-history-data"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('modal')

<div class="modal fade" id="iui-deposit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg w-100" role="document">
        <div class="modal-content">
            <!-- header -->
            <div class="modal-header justify-content-center">
                <h4 class="title">IUI Deposit Print</h4>
            </div>
            <!-- body -->
            {{Form::open(['class'=>'form-inline','id'=>'iui-depost-print'])}}
            {{ Form::hidden('current_deposit', null, [
                'id' => 'current_deposit'
            ]) }}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        Name
                    </div>
                    <div class="col-md-5">
                        <span class="patient_name"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        O.Study
                    </div>
                    <div class="col-md-6">
                        {{Form::text('o_study',800,[
                            'class'=>'form-control iui-charges o-study',
                            'placeholder'=>'O.STUDY',
                            'min'=>0,
                            'maxlength' => 8,
                            'id' => 'o_study',
                            'onpaste' => 'return false'
                        ])}}
                        <span class="form-error-msg o-study-charge"></span>
                    </div>
                </div>

                <div class="append-injections">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        IUI
                    </div>
                    <div class="col-md-2">
                        x1
                    </div>
                    <div class="col-md-6">
                        {{Form::text('iui','',[
                            'class'=>'form-control iui-charges iui-charge',
                            'placeholder'=>'IUI',
                            'min'=>0,
                            'maxlength' => 8,
                            'id' => 'iui-charge',
                            'onpaste' => 'return false'
                        ])}}
                        <span class="form-error-msg iui-charge"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        Sub Total
                    </div>
                    <div class="col-md-6">
                        {{Form::number('sub_total','',[
                            'class'=>'form-control about-total sub-total',
                            'placeholder'=>'Total',
                            'min'=>0,
                            'id' => 'sub-total',
                            'readonly',
                            'oncopy' =>'return false',
                            'onpaste' =>'return false',
                            'oninput' =>'return false',
                        ])}}
                        <span class="form-error-msg sub-total"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        Discount
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {{Form::select("discount_in",['1'=>'%','2'=>'₹'],1,[
                                'class'=>'form-control select-padding-0 discount_in',
                                'id'=>'discount_in',
                            ])}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{Form::number('discount','',[
                            'class'=>'form-control about-total discount',
                            'placeholder'=>'Discount',
                            'min'=>0,
                            'id' => 'disocunt',
                            'maxlength' => 8,
                            'onpaste' => 'return false'
                        ])}}
                        <span class="form-error-msg discount"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        Deposit
                    </div>
                    <div class="col-md-6">
                        {{Form::number('deposit','',[
                            'class'=>'form-control about-total deposit',
                            'placeholder'=>'Deposit',
                            'min'=>0,
                            'id' => 'deposit',
                            'readonly',
                            'oncopy' =>'return false',
                            'onpaste' =>'return false',

                        ])}}
                        <span class="form-error-msg deposit"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        Grand Total
                    </div>
                    <div class="col-md-6">
                        {{Form::number('total','',[
                            'class'=>'form-control about-total grand-total',
                            'placeholder'=>'Grand Total',
                            'min'=>0,
                            'id' => 'grand-total',
                            'readonly',
                            'oncopy' =>'return false',
                            'onpaste' =>'return false',
                            'oninput' =>'return false',
                        ])}}
                        <span class="form-error-msg grand-total"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect submit iui-bill-preview" value="4">Save & Preview</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- footer -->

            {{Form::close()}}
        </div>
    </div>
</div>

<div class="modal fade" id="next-appointment-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- header -->
            <div class="modal-header justify-content-center">
                <h4 class="title" id="next-appointment">Next Appointment</h4>
            </div>
            <!-- body -->
            {{Form::open(['class'=>'form-inline','id'=>'next-appointment'])}}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            Day
                        </div>
                        <div class="col-md-5">
                            {{Form::number('day','',['class'=>'form-control next-day','placeholder'=>'Day','min'=>1])}}
                            <span class="form-error-msg day w-100"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            Date
                        </div>
                        <div class="col-md-5">
                            {{Form::date('date','',['class'=>'form-control next-date','placeholder'=>'Date','min'=>0])}}
                            <span class="form-error-msg date"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            Time
                        </div>
                        <div class="col-md-5">
                            {{Form::select('next-time',$hospitalTime,'',['class'=>'form-control next-time','placeholder'=>'Time'])}}
                            <span class="form-error-msg date"></span>
                        </div>
                            <span class="form-error-msg time"></span>
                        </div>
                    </div>
                </div>
                    {{Form::hidden('appointment-id','',['class'=>'appointment-id'])}}
                <!-- footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect next-appointment-form">SAVE CHANGES</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade view-file-edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog view-file-modal-dialog">
      <div class="modal-content">
        <div class="modal-header header-bottom-border">

            <!-- <div class="row">
                <div class="col-md-12">
                    <h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date"></span></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="modal-title" id="myModalLabel">Cycle No:- <span class="iui-appointment-cycle-no"></span></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="modal-title" id="myModalLabel">Visit:- <span class="iui-appointment-visit-no"></span></h5>
                </div>
            </div> -->
            <!-- <div class="row">
                <div class="col-md-12 mr-5">
                    <a class="btn edit-btn btn-sm btn-primary">Edit</a>
                    <a class="btn print-btn btn-sm btn-primary">Print</a>
                </div>
            </div> -->
            <div class="row">
                @foreach ($cycleData as $item)
                    <div class="col">
                        <button type="button" class="btn btn-primary cycle-no-value cycle-button" data-type="1" data-no="{{$item}}">Cycle No : {{$item}}</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="close anc-details-close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
            <div class="anc-details-data"></div>
        </div>

        <div class="modal-footer footer-top-border text-right d-inline-block">
        <button type="button" class="btn btn-primary mb-2" data-dismiss="modal" aria-hidden="true">Close</button>

        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="overy-data-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog ovary-modal-dialog" role="document">
        <div class="modal-content">
            <!-- header -->
            <div class="modal-header justify-content-center">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> --}}
                {{-- <h4 class="title" id="overy-popup-title"></h4> --}}
            </div>
            <!-- body -->
            <div class="modal-body">
                <table class="table m-b-0" id="ovary-table">
                    <tbody>
                        <tr>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="8"><span class="ovary-value-number ovary-number-8 odd-padding ovary-pre-selected-value">8</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="9"><span class="ovary-value-number ovary-number-9 odd-padding">9</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="10"><span class="ovary-value-number ovary-number-10 odd-padding">10</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="11"><span class="ovary-value-number ovary-number-11 odd-padding">11</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="12"><span class="ovary-value-number ovary-number-12 odd-padding">12</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="13"><span class="ovary-value-number ovary-number-13 odd-padding">13</span></td>
                        </tr>
                        <tr>
                            <td class="ovary-value" data-type="" data-class="" data-value="13.5"><span class="ovary-value-number ovary-number-13-5 odd-padding">13.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="14"><span class="ovary-value-number ovary-number-14 odd-padding ovary-pre-selected-value">14</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="14.5"><span class="ovary-value-number ovary-number-14-5 odd-padding">14.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="15"><span class="ovary-value-number ovary-number-15 odd-padding ovary-pre-selected-value">15</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="15.5"><span class="ovary-value-number ovary-number-15-5 odd-padding">15.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="16"><span class="ovary-value-number ovary-number-16 odd-padding ovary-pre-selected-value">16</span></td>
                        </tr>
                        <tr>
                            
                            <td class="ovary-value" data-type="" data-class="" data-value="16.5"><span class="ovary-value-number ovary-number-16-5 odd-padding">16.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="17"><span class="ovary-value-number ovary-number-17 odd-padding ovary-pre-selected-value">17</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="17.5"><span class="ovary-value-number ovary-number-17-5 odd-padding">17.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="18"><span class="ovary-value-number ovary-number-13 odd-padding ovary-pre-selected-value">18</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="18.5"><span class="ovary-value-number ovary-number-18-5 odd-padding">18.5</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="19"><span class="ovary-value-number ovary-number-19 odd-padding">19</span></td>
                        </tr>
                        <tr>
                            <td class="ovary-value" data-type="" data-class="" data-value="19.5"><span class="ovary-value-number ovary-number-19-5 odd-padding">19.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="20"><span class="ovary-value-number ovary-number-20 odd-padding ovary-pre-selected-value">20</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="20.5"><span class="ovary-value-number ovary-number-20-5 odd-padding">20.5</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="21"><span class="ovary-value-number ovary-number-21 odd-padding">21</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="21.5"><span class="ovary-value-number ovary-number-21-5 odd-padding">21.5</span></td>
                            <td class="ovary-value ovary-pre-selected-value" data-type="" data-class="" data-value="22"><span class="ovary-value-number ovary-number-22 odd-padding ovary-pre-selected-value">22</span></td>
                        </tr>
                        <tr>
                            <td class="ovary-value" data-type="" data-class="" data-value="22.5"><span class="ovary-value-number ovary-number-22-5 odd-padding">22.5</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="23"><span class="ovary-value-number ovary-number-23 odd-padding">23</span></td>
                            <td class="ovary-value" data-type="" data-class="" data-value="24"><span class="ovary-value-number ovary-number-24 odd-padding">24</span></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
            <!-- footer -->
            <div class="modal-footer next-appointment-footer">
                {{-- <a href="#" class="btn btn-primary waves-effect save-btn disabled next-appointment-form">Save</a>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
</div>
<div class="modal fade iui-report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <span class="modal-title font-20 iui-report-title candor-color font-bold"></span>
        </div>
        <div class="modal-body">
            <div class="iui-details-data">
                <div class="w3-content w3-display-container">
                    <div class="report-image">
                        
                    </div>
                  
                    <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
                    <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
                  </div>
            </div>
        </div>

        <div class="modal-footer text-right d-inline-block">
        </div>
      </div>
    </div>
</div>
@stop
@section('page-script')
    <script src="{{asset('public/js/iui.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script type="text/javascript">
        var code = '';
        var qstring = '';
        var histdate = '';
        var cycleNo = "{{$iuiFirstVisitData->cycle_no}}";
        var patientsId = $('.patients-id').val();
        var iuiString = '';
        var status = '1';
        var type = '2';
        var overyType = 0;
        var isFirst = 1;
        var appintmentDate = "{{$iuiFirstVisitData->created_at}}";
        var iuiId = '';
        var slideIndex = 1;
        $(document).ready(function(){

            $(document).on('click','.view-file-edit',function(e){
                e.preventDefault();
                $('.view-file-edit-modal').modal('show');
                // cycleNo = '{{$iuiCurrentCycleNo}}';
                iuiString = 'patient_id='+patientsId+'&appointment_date='+appintmentDate+'&status='+status+'&type='+type+'&is_first='+isFirst;
                getIuiHistoryData(iuiString);
            });

            $(document).on('click','.cycle-no-value',function(e){
                e.preventDefault();
                cycleNo = $(this).data('no');
                type = $(this).data('type');
                iuiString = 'patient_id='+patientsId+'&appointment_date='+appintmentDate+'&status='+status+'&type='+type+'&cycle_no='+cycleNo;
                getIuiHistoryData(iuiString);
            });

            $(document).on('click','.next-appointment-details',function(e){
                e.preventDefault();
                appintmentDate = $(this).data('date');
                status = $(this).data('status');
                type = $(this).data('type');
                cycleNo = $(this).data('cycle');
                iuiString = 'patient_id='+patientsId+'&appointment_date='+appintmentDate+'&status='+status+'&type='+type+'&cycle_no='+cycleNo;
                getIuiHistoryData(iuiString);
            });

            $(document).on('keyup','.iui-charges', function() {
                var sum = 0;
                var deposit = 0;
                var discount = 0;
                var cutAmount = 0;
                var subTotal = 0;
                var discountIn = '';
                $('.iui-charges').each(function() {
                    sum += Number($(this).val());
                });
                $('.sub-total').val(sum);
                if (sum == 0) {
                    $('.discount').val(0);
                }
                deposit = $('#current_deposit').val();
                discount = $('.discount').val() == '' ? 0 : $('.discount').val();
                discountIn = $("#discount_in option:selected").val();
                if (discountIn == 1) {
                    discount = (discount != 0) ? parseInt(parseInt(discount * sum) / 100) : 0
                }
                cutAmount = parseInt(sum - discount);
                $('.grand-total').val(0);
                if (cutAmount > deposit) {
                    $('.grand-total').val(parseInt(cutAmount - deposit));
                }

            });

            $(document).on('change','.discount_in', function() {
                discountValue = $('.discount').val();
                if ($("#discount_in option:selected").val() == 1 && discountValue > 100) {
                    $('.discount').val('');
                }

                var sum = 0;
                var deposit = 0;
                var discount = 0;
                var cutAmount = 0;
                var subTotal = 0;
                var discountIn = '';
                $('.iui-charges').each(function() {
                    sum += Number($(this).val());
                });
                $('.sub-total').val(sum);
                deposit = $('#current_deposit').val();

                discount = $('.discount').val() == '' ? 0 : $('.discount').val();

                discountIn = $("#discount_in option:selected").val();
                if (discountIn == 1) {
                    discount = (discount != 0) ? parseInt(parseInt(discount * sum) / 100) : 0
                }

                cutAmount = parseInt(sum - discount);
                $('.grand-total').val(0);
                if (cutAmount > deposit) {
                    $('.grand-total').val(parseInt(cutAmount - deposit));
                }
            });

            $(document).on('input','.discount', function() {
                value = $(this).val();
                if ($("#discount_in option:selected").val() == 1 && value > 100) {
                    $('.discount').val(value.substring(0, (value.length - 1)));
                } else if ($("#discount_in option:selected").val() == 2 && parseInt(value) > parseInt($('.sub-total').val())) {
                    $('.discount').val('');
                }


                var sum = 0;
                var deposit = 0;
                var discount = 0;
                var cutAmount = 0;
                var subTotal = 0;
                var discountIn = '';
                $('.iui-charges').each(function() {
                    sum += Number($(this).val());
                });
                $('.sub-total').val(sum);
                deposit = $('#current_deposit').val();

                discount = $('.discount').val() == '' ? 0 : $('.discount').val();

                discountIn = $("#discount_in option:selected").val();
                if (discountIn == 1) {
                    discount = (discount != 0) ? parseInt(parseInt(discount * sum) / 100) : 0
                }

                cutAmount = parseInt(sum - discount);

                $('.grand-total').val(0);
                if (cutAmount > deposit) {
                    $('.grand-total').val(parseInt(cutAmount - deposit));
                }

            });

            $(document).on('click','.iui-deposit-print',function(){
                var visit = $("input[name=visit]").val();
                if(visit == 3 && $('.hcg-type-value:checked').val() == 'yes'){
                    $('.hsg-injection-type-msg').text('');
                    if($('select.hsg-injection').val() == ''){
                        $('.hsg-injection-type-msg').text('This field is required.');
                        return false;
                    }
                }

                $('#iui-deposit-print').trigger('reset');
                $('.discount').val('');
                $('.append-injections').empty();
                $('.iui-print-preview').attr('disabled', false);
                var cycleNo = $('#cycle_no').val();
                var patientId = $('#patients_id').val();
                var visit = $('#visit').val();
                var hcgIuiType = '';
                var iui = 0;
                var currentVisitInjections = $('#plan-data').val();
                if ($("input[name='data[hcg][type]']:checked").val() == 'yes' && $("input[name='data[hcg][iui][status]']:checked").val() == 'yes') {
                    hcgIuiType = $( "#hcg-iui-type option:selected" ).val();
                    if (hcgIuiType == 2 || hcgIuiType == 3) {
                        iui = 2500;
                    } else if (hcgIuiType == 1) {
                        iui = 2000;
                    }
                } else {
                    iui = 2000;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{URL::to('get-iui-bill-data')}}",
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        cycle_no: cycleNo,
                        patient_id: patientId,
                        visit: visit,
                        current_visit_injections: currentVisitInjections
                    },
                }).done(function(data) {
                    var injection = data.injections;
                    var totalCharge = 0;
                    if (data.status == 1) {
                        $('.patient_name').text(data.patient_name);
                        $('.iui-charge').val(iui);
                        if (injection.length != 0) {
                            $('.append-injections').append(
                                '<div class="row">' +
                                    '<div class="col-md-5">' +
                                        'Injections' +
                                    '</div>' +
                                '</div>'
                            )
                            for (var i = 0; i < injection.length; i++) {
                                $('.append-injections').append(
                                    '<div class="row p-1">' +
                                        '<div class="col-md-4">' +
                                            '<input type="hidden" name="deposit_injections[' + i + '][quantity]" value="' + injection[i]['quantity'] + '">' +
                                            '<input type="hidden" name="deposit_injections[' + i + '][name]" value="' + injection[i]['name'] + '">' +
                                            '<input type="hidden" name="deposit_injections[' + i + '][injection_price]" value="' + injection[i]['injection_price'] + '">' +
                                            '<label class="float-left">' +
                                                injection[i]['name'] +
                                            '</label>' +
                                        '</div>' +
                                        '<div class="col-md-2">' +
                                            '<label>' +
                                                'x' + injection[i]['quantity'] +
                                            '</label>' +
                                        '</div>' +
                                        '<div class="col-md-6">' +
                                            '<div class="form-group">' +
                                                '<input type="text" placeholder="' + injection[i]['name'] + '" id="' + injection[i]['name'].replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_') + '" name="deposit_injections[' + i + '][price]" value="' + injection[i]['price'] + '" class="form-control iui-charges" maxlength="8" onpaste=return false();">' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>'
                                );
                            }

                        }
                        $('.iui-charges').each(function(key, object) {
                            totalCharge += parseInt($(object).val());
                        });
                        $('.sub-total').val(totalCharge);
                        $('#current_deposit').val(data.deposit);
                        $('.deposit').val(data.deposit);
                        var finalTotalCharge = parseInt(totalCharge - data.deposit);
                        if (totalCharge < data.deposit) {
                            finalTotalCharge = parseInt(data.deposit - totalCharge);
                        }
                        $('.grand-total').val(finalTotalCharge);

                    }
                    $('#iui-deposit-modal').modal('show');
                }).fail(function() {

                });
            });

            getIuiData(histdate);
            $('.complain-multi .show-tick').addClass('d-none');
            $('.select2-search__field').css('width','280px');

            $(document).on('click','.next-appointment-form',function(e){
                e.preventDefault();
                var date = $('.next-date-value').val();
                var time = $('select.next-time').find("option:selected").text();
                var timeVal = $('.next-time-value').val(time);
                var anc = $('#anc-form').serialize();
                anc = anc + '&isprint=' + '1';
                iuiFormData(anc);
            });

            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var iui = new FormData($("#iui-form")[0]);
                if(this.value==1){
                    iui.append('isprint', 1);
                }
                if(this.value==2){
                    iui.append('isprint', 2);
                }
                if(this.value==6){
                    iui.append('isprint', 6);
                }
                if(this.value==4){
                    var iuiDepositPrint = new FormData($("#iui-form")[0]);
                    iui.append('is_iui_deposit_print', 4);
                }
                iuiFormData(iui);
            });

            $(document).on('change','select.refence-doctor',function(e){
                var refDoctorId = $(this).val();
                var token = "{{csrf_token()}}";
                $.ajax({
                    url: "{{URL::to('get-ref-doctor-mobile-number')}}",
                    dataType: 'json',
                    type: 'POST',
                    data:{refDoctorId:refDoctorId,_token:token}
                }).done(function(data) {
                    if(data.mobile_number != null){
                        $('.ref-mobile-number').val(data.mobile_number);
                    }else{
                        $('.ref-mobile-number').val('');
                    }
                }).fail(function() {

                });

            });

            $(document).on('change','select.iui-date',function(e){
                histdate = $(this).val();
                getIuiData(histdate,cycleNo);
            });
            $(document).on('change','.hcg-date',function(e){
                var hcgDate = $(this).val();
                var hcgTime = $('.hcg-time').val();
                var hcgDateTime = new Date(hcgDate + ' ' + hcgTime);
                hcgDateTime = hcgDateTime.setHours(hcgDateTime.getHours() + 48);
                var hcgIuiDate = moment(hcgDateTime).format('dddd DD MMMM YYYY');
                $('.hcg-iui-date').val(hcgIuiDate);
            });

            $(document).on('change','select.plan-type',function(e){
                var type = $(this).val();
                getPlanData(type);
            });

            $(document).on('change','select.iui-cycle-no',function(e){
                if($(this).val() != '')
                {
                    cycleNo = $(this).val();
                    getIuiVisitData(cycleNo);
                }
                
            });

            $(document).on('input','.iui-charges',function() {
                value = $(this).val();
                $('#' + this.id).val(value);
                if ((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 8) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });

            $(document).on('input','.about-total',function() {
                value = $(this).val();
                $('#' + this.id).val(value);
                if ((/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) || value.length > 8) {
                    $('#' + this.id).val(value.substring(0, (value.length - 1)));
                }
            });

            $(document).on('change','select.duration-data',function(){
                var value = $(this).val();
                var dId = $(this).data('id');
                $('.'+dId).addClass('d-none');
                if(value == 'other'){
                    $('.'+dId).removeClass('d-none');
                }
            });

            $(document).on('click','.edit-btn',function(){
                date = $(this).data('date');
                getIuiData(date,cycleNo);
            });

            $(document).on('click','.print-btn',function(){
                date = $(this).data('date');
                iuiString = 'patient_id='+patientsId+'&history_date='+date+'&status='+status+'&type='+type+'&cycle_no='+cycleNo;
                getIuiHistoryData(iuiString);
            });
            $(document).on('click','.edit-iui-data',function(){
                iuiId = $(this).data('id');
                editIuiVisitData(iuiId);
            });
            $('.anc-details-close').on('click', function () {
                location.reload();
            });

        });

        function iuiFormData(iuiData){
            var valid = 1;
            $('.ho-data-msg').text('');
            $('.co-value-msg').text('');
            var visit = $("input[name=visit]").val();
            if(visit == 1){
                // $('.ho-tab').removeClass('show');
                $('.p-info').removeClass('show');
                var weight=$('#weight').val();
                var weight=$('#is_history_weight').val();
                if(weight == ''){
                    $('.weight').text('The weight is required');
                    valid = 0;
                    $('.p-info').addClass('show');
                }
                // if($('select.ho-data').val() == ''){
                //     valid = 0;
                //     $('.ho-data-msg').text('The ho field is required.');
                //     $('.ho-tab').addClass('show');
                // }
                if(valid == 0) {
                    $('html, body').animate({
                        scrollTop: ($('.weight').offset().top - 150)
                    }, 1000);
                    return true;
                }
            }
            if(visit == 2 || visit == 3 || visit == 4){
                $('.seen-by-error-2').text('');
                $('.seen-by-error-3').text('');
                $('.seen-by-error-4').text('');
                if(visit == 2){
                    $('.plan-type-msg').text('');
                    $('.lmp-date-msg').text('');
                    $('.lmp-tab').removeClass('show');
                    $('.plan-tab').removeClass('show');
                    // if($('select.plan-type').val() == ''){
                    //     $('.plan-type-msg').text('This field is required.');
                    //     valid = 0;
                    //     $('.plan-tab').addClass('show');
                    // }
                    if($('.second-visit-lmd-date').val() == ''){
                        $('.lmp-date-msg').text('This field is required.');
                        valid = 0;
                        $('.lmp-tab').addClass('show');
                    }
                    $('.plan-data-error').text('');
                    // if($('select.plan-data').val() == ''){
                    //     $('.plan-data-error').text('This field is required.');
                    //     $('.plan-tab').addClass('show');
                    //     valid = 0;
                    // }
                    if($('select.seen-by-2').val() == ''){
                        $('.seen-by-error-2').text('Please select doctor');
                        $('html, body').animate({
                            scrollTop: ($('.seen-by-2').offset().top - 150)
                        }, 1000);
                        return false;
                    }
                }
                if(visit == 3 && $('.hcg-type-value:checked').val() == 'yes'){
                    $('.hsg-injection-type-msg').text('');
                    if($('select.hsg-injection').val() == ''){
                        $('.hsg-injection-type-msg').text('This field is required.');
                        valid = 0;
                    }
                }
                if(visit == 3 && $('select.seen-by-3').val() == ''){
                    $('.seen-by-error-3').text('Please select doctor');
                    $('html, body').animate({
                        scrollTop: ($('.seen-by-3').offset().top - 150)
                    }, 1000);
                    return false;
                }

                if(visit != 4){
                    if($('.follow-up-date').val() == '' || typeof $('.follow-up-date').val() == 'undefined'){
                        $('.follow-date-msg').text('This field is required');
                        valid = 0;
                        $('.plan-tab').addClass('show');
                    }
                }

                var hcgType = $("input[name='data[hcg][type]']:checked").val();
                var iuiType = $("input[name='data[hcg][iui][status]']:checked").val();

                if (hcgType == 'yes') {
                    $(".hcg_time").text('');
                    if($('input[name="data[hcg][time]"]').val() == '') {
                        $(".hcg_time").text('Please enter HCG time');
                        valid = 0;
                    }
                    $(".iui_time").text('');
                    if(iuiType == 'yes' && $('input[name="data[hcg][iui][time]"]').val() == '') {
                        $(".iui_time").text('Please enter IUI time');
                        valid = 0;
                    }
                }
            }
            if(visit == 4){
                $('.r-type-msg').text('');
                if(typeof $('.r-type:checked').val() == 'undefined'){
                    $('.r-type-msg').text('This field is required.');
                    valid = 0;
                }
                $('.upt-type-msg').text('');
                if(typeof $('.upt-type:checked').val() == 'undefined'){
                    $('.upt-type-msg').text('This field is required.');
                    valid = 0;
                }
                if($('select.seen-by-4').val() == ''){
                    $('.seen-by-error-4').text('Please select doctor');
                    $('html, body').animate({
                        scrollTop: ($('.seen-by-4').offset().top - 150)
                    }, 1000);
                    valid = 0;
                }
            }
            if(valid == 0){
                return true;
            }
            var url = "{{URL::to('iui')}}";
            $('.iui-bill-preview').attr('disabled', true);
            $('.submit').prop('disabled', true);
            $.ajax({
                url:'{{URL::to("iui")}}',
                type:'POST',
                dataType:'json',
                data:iuiData,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == 'true' && data.secondVisit == false){
                    window.location.href = url;
                }else if(data.status == 1){
                    $('#iui_history_id').val(data.id);
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    setTimeout(function () {
                        w.window.print();
                    }, 300);
                    // window.location.href = url;
                } else if (data.status == 5) {
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    window.location.href = url;
                }else{
                    location.reload();
                }
            });
        }

        // get form data
        function getIuiData(histdate,cycleNo){
            var iuiDate = $('select.iui-date').val();
            var cycleData = '';
            if(typeof cycleNo != 'undefined'){
                cycleData = '&iui_cycle_no='+cycleNo;
            }
            var url = '/'+patientsId+'?iuihistorydate='+histdate+cycleData+'&iui_date='+iuiDate;
            $.ajax({
                url: "{{URL::to('iui/history')}}"+url,
                dataType: 'json',
                type:'GET',
            }).done(function(data) {
                if(data.iui_completed == true && data.historyData == null)
                {
                    var lastCycle = $('select.iui-cycle-no option:last').val();
                    
                    getIuiVisitData(lastCycle);
                }
                
                $('.iui-history-data').html(data.update_iui);
                var dateData = '<option value="0">Select Date</option>';
                $.each(data.date,function(key,value){
                    dateData +=  '<option value="' + key + '">'+value+'</option>';
                });
                $('select.iui-date').html(dateData);
                if(data.oldDate != ''){
                    $('select.iui-date').val(data.oldDate);
                }
                $('.ho-value .selectized').addClass('d-none');

                $(function () {
                    //Datetimepicker plugin
                    $('.datetimepicker').bootstrapMaterialDatePicker({
                        format: 'dddd DD MMMM YYYY',
                        // minDate:new Date(),
                        clearButton: true,
                        time:false,
                        weekStart: 1
                    });
                    $('.lmd-date').bootstrapMaterialDatePicker({
                        format: 'dddd DD MMMM YYYY',
                        clearButton: true,
                        time:false,
                        weekStart: 1
                    });

                    $('.timepicker').bootstrapMaterialDatePicker({
                        date: false,
                        shortTime: true,
                        format: 'hh:mm a',
                        switchOnClick: true
                    });

                    $('.co_value_data').selectize({
                        delimiter: ',',
                        persist: false,
                        create: function(input) {
                            return {
                                value: input,
                                text: input
                            }
                        },
                        createFilter: function(input) {
                            if (input.length > 250) {
                                alert('length must be less than 250 characters');
                            }
                            return input;
                        }
                    });
                    // $('.date-data').datepicker({
                    //     multidate: true,
                    //     format: 'dd-mm-yyyy'
                    // });

                    $('#treatment-medicine').select2();
                });
                if(data.hystroscopyImages != 'null') {
                    $('.hystroscopy-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.hystroscopyImages),
                        imagesInputName: 'investigation[hystroscopy][images]',
                        preloadedInputName: 'hystroscopy_old'
                    });
                }
                if(data.hcgImages != 'null') {
                    $('.hcg-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.hcgImages),
                        imagesInputName: 'investigation[hcg][images]',
                        preloadedInputName: 'hcg_old'
                    });
                }
                if(data.laproscopyImages != 'null') {
                    $('.laproscopy-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.laproscopyImages),
                        imagesInputName: 'investigation[laproscopy][images]',
                        preloadedInputName: 'laproscopy_old'
                    });
                }
                if(data.bloodImages != 'null') {
                    $('.blood-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.bloodImages),
                        imagesInputName: 'investigation[blood_report][image]',
                        preloadedInputName: 'blood_report_old'
                    });
                }
                if(data.hsaImages != 'null') {
                    $('.hsa-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.hsaImages),
                        imagesInputName: 'investigation[hsa_report][images]',
                        preloadedInputName: 'hsa_report_old'
                    });
                }
                if(data.historyData != null)
                {
                    if(data.bloodImages != 'null')
                    {
                        $('.blood-images').imageUploader({
                            preloaded: jQuery.parseJSON(data.bloodImages),
                            imagesInputName: 'investigation[blood_report][image]',
                            preloadedInputName: 'blood_report_old'
                        });
                        $('.data-blood-images').imageUploader({
                            preloaded: jQuery.parseJSON(data.bloodImages),
                            imagesInputName: 'data[blood_report][image]',
                            preloadedInputName: 'blood_report_old'
                        });
                    }
                    if(data.hsaImages != 'null') {
                        $('.hsa-images').imageUploader({
                            preloaded: jQuery.parseJSON(data.hsaImages),
                            imagesInputName: 'investigation[hsa_report][images]',
                            preloadedInputName: 'hsa_report_old'
                        });
                        $('.data-hsa-images').imageUploader({
                            preloaded: jQuery.parseJSON(data.hsaImages),
                            imagesInputName: 'data[hsa_report][images]',
                            preloadedInputName: 'hsa_report_old'
                        });
                    }
                    
                }
                if(data.historyData != null && data.usgImages != 'null')
                {
                    $('.data-usg-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.usgImages),
                        imagesInputName: 'data[usg][images]',
                        preloadedInputName: 'usg_old'
                    });
                }
                $('.view-file-edit-modal').modal('hide');
                $('.select-padding-0').selectpicker('refresh');
                follicleUpdateType($('.follicle').val());
                hcgTypeValue($("input[name='data[hcg][type]']:checked").val());
                regularType($('select.past-mh-2').val(),'past-ir-regular-data');
                regularType($('select.present-mh-2').val(),'present-ir-regular-data');
            }).fail(function() {

            });
        }

        // get form data
        function getIuiVisitData(cycleNo){
            // var iuiDate = $('select.iui-date').val();
            var cycleData = '';
            if(typeof cycleNo != 'undefined'){
                cycleData = 'iui_cycle_no='+cycleNo;
            }
            var url = '/'+patientsId+'?'+cycleData;
            $.ajax({
                url: "{{URL::to('iui/history')}}"+url,
                dataType: 'json',
                type:'GET',
            }).done(function(data) {
                // $('.panel-group').html('')
                $('.iui-history-data').html(data.update_iui);
                $('select.iui-cycle-no').val(cycleNo);
                    $('.iui-cycle-no').selectpicker('refresh');
                $('.div-seen-by').remove();
                $('.div-btn').remove();
                $('.ho-value .selectized').addClass('d-none');

            }).fail(function() {

            });
        }
        //edit iui visit based on iuiID
        function editIuiVisitData(iuiId){
            
            var url = '/'+patientsId+'?iui_visit_id='+iuiId;
            $.ajax({
                url: "{{URL::to('iui/history')}}"+url,
                dataType: 'json',
                type:'GET',
            }).done(function(data) {
                $('.iui-history-data').html(data.update_iui);
                $('.ho-value .selectized').addClass('d-none');

                $(function () {
                    //Datetimepicker plugin
                    $('.datetimepicker').bootstrapMaterialDatePicker({
                        format: 'dddd DD MMMM YYYY',
                        // minDate:new Date(),
                        clearButton: true,
                        time:false,
                        weekStart: 1
                    });
                    $('.lmd-date').bootstrapMaterialDatePicker({
                        format: 'dddd DD MMMM YYYY',
                        clearButton: true,
                        time:false,
                        weekStart: 1
                    });

                    $('.timepicker').bootstrapMaterialDatePicker({
                        date: false,
                        shortTime: true,
                        format: 'hh:mm a',
                        switchOnClick: true
                    });

                    $('.co_value_data').selectize({
                        delimiter: ',',
                        persist: false,
                        create: function(input) {
                            return {
                                value: input,
                                text: input
                            }
                        },
                        createFilter: function(input) {
                            if (input.length > 250) {
                                alert('length must be less than 250 characters');
                            }
                            return input;
                        }
                    });
                    // $('.date-data').datepicker({
                    //     multidate: true,
                    //     format: 'dd-mm-yyyy'
                    // });

                    $('#treatment-medicine').select2();
                });
                if(data.hystroscopyImages != 'null') {
                    $('.hystroscopy-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.hystroscopyImages),
                        imagesInputName: 'investigation[hystroscopy][images]',
                        preloadedInputName: 'hystroscopy_old'
                    });
                }
                if(data.hcgImages != 'null') {
                    $('.hcg-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.hcgImages),
                        imagesInputName: 'investigation[hcg][images]',
                        preloadedInputName: 'hcg_old'
                    });
                }
                if(data.laproscopyImages != 'null') {
                    $('.laproscopy-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.laproscopyImages),
                        imagesInputName: 'investigation[laproscopy][images]',
                        preloadedInputName: 'laproscopy_old'
                    });
                }
                if(data.bloodImages != 'null') {
                    $('.blood-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.bloodImages),
                        imagesInputName: 'investigation[blood_report][image]',
                        preloadedInputName: 'blood_report_old'
                    });
                    $('.data-blood-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.bloodImages),
                        imagesInputName: 'data[blood_report][image]',
                        preloadedInputName: 'blood_report_old'
                    });
                }
                if(data.hsaImages != 'null') {
                        $('.hsa-images').imageUploader({
                            preloaded: jQuery.parseJSON(data.hsaImages),
                            imagesInputName: 'investigation[hsa_report][images]',
                            preloadedInputName: 'hsa_report_old'
                        });
                        $('.data-hsa-images').imageUploader({
                            preloaded: jQuery.parseJSON(data.hsaImages),
                            imagesInputName: 'data[hsa_report][images]',
                            preloadedInputName: 'hsa_report_old'
                        });
                    }
                if(data.usgImages != 'null')
                {
                    $('.data-usg-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.usgImages),
                        imagesInputName: 'data[usg][images]',
                        preloadedInputName: 'usg_old'
                    });
                }
                $('.view-file-edit-modal').modal('hide');
                $('.select-padding-0').selectpicker('refresh');
                follicleUpdateType($('.follicle').val());
                hcgTypeValue($("input[name='data[hcg][type]']:checked").val());
                regularType($('select.past-mh-2').val(),'past-ir-regular-data');
                regularType($('select.present-mh-2').val(),'present-ir-regular-data');
            }).fail(function() {

            });
        }
        function getPlanData(type){
            $.ajax({
                url: "{{URL::to('get-plandata')}}"+'/'+type,
                dataType: 'json',
                type:'GET',
            }).done(function(data){
                var planData = [];
                $.each(data.planData, function(key, value) {
                    planData +=  '<option value="' + key + '">'+value+'</option>';
                });
                $('select.plan-data').html(planData);
                $('.plan-data').selectpicker('refresh');
            }).fail(function(error){

            });
        }

        function getIuiHistoryData(iuiString){
            
            $.ajax({
                url:'{{URL::to("get-iui-details")}}?'+iuiString,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                if(data.iui_type == 1){
                    
                    var buttonHtml = '';
                    var previewData = '';
                    $('.anc-details-data').empty();
                    var iuiPreview = $('.anc-details-data').html();
                    for(i=0; i<data.data.length;i++)
                    {
                        if(typeof data.date[i] != 'undefined'){
                            var linkDate = moment(new Date(data.date[i])).format('YYYY-MM-DD HH:mm:ss');
                            var date = moment(new Date(data.date[i])).format('DD MMMM YYYY');
                        }
                        buttonHtml = iuiPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn edit-btn btn-sm btn-primary" data-date="'+linkDate+'">Edit</a><a class="btn print-btn btn-sm btn-primary" data-date="'+linkDate+'">Print</a></div></div>';

                        if(data.table_view[i] == true)//table view
                        {
                            buttonHtml = iuiPreview + '<div class="row mb-1"><div class="col-md-6 text-left"></div><div class="col-md-6 text-right"><a class="btn print-btn btn-sm btn-primary" data-date="'+linkDate+'">Print</a></div></div>';

                        }
                        iuiPreview = buttonHtml + data.data[i];
                        $('.anc-details-data').html(iuiPreview);
                        // iuiPreview = iuiPreview + '<div class="row sepreator"></div>';
                    }
                }
                if(data.iui_type == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function(error){

            });
        }

    var medicinesValue = @json($medicines);
    $(document).on('click','.report-btn', function(){
            var iuiId = $(this).data('id');
            var date = $(this).data('date');
            var html = '';
            $.ajax({
                url:'{{URL::to("get-iui-report")}}'+'/'+iuiId,
                type:'GET',
                dataType:'json'
            }).done(function(data){
                $('.iui-report').modal('show');
                $('.iui-report-title').html('IUI Report of '+date);
                if(data.status == 1){
                    $.each(data, function() {
                        $.each(this, function(k, v) {
                            if(v.length > 0)
                            {
                                
                                $.each(v, function(index,image) {
                                    var extension = image.substr( (image.lastIndexOf('.') +1) );
                                    var path = "{{url('')}}" + '/'+image;
                                    if(extension == 'pdf')
                                    {
                                        html += '<embed type="application/pdf" src="'+path+'" frameborder="0" height="100%" width="100%" class="mySlides">';
                                    }
                                    else
                                    {
                                        html += '<img class="mySlides" src="'+path+'">';
                                    }
                                    
                                });
                                
                            }
                        });
                        
                    });
                    $('.report-image').html(html);
                    slideIndex= 1;
                    showDivs(slideIndex);
                }
            }).fail(function(error){

            });
        });
        

        function plusDivs(n) {
        showDivs(slideIndex += n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) {slideIndex = 1}
            if (n < 1) {slideIndex = x.length}
            for (i = 0; i < x.length; i++) {
                console.log('sdf');
                x[i].style.display = "none";  
            }
            x[slideIndex-1].style.display = "block";  
        }
        $(document).on('click','.edit-remark-icon',function(e){
            e.preventDefault();
            var dId = $(this).data('id');
            var value = $(this).data('value');
            if($('.follow-data').hasClass('remark-val')){
                var previousId = $('.remark-val').data('id');
                var previousRemark = $('.remark-val').data('value');
                var data = "<div class='edit-follow-data edit-follow-'"+previousId+"'>"+
                    wordwrap(""+previousRemark+"", 30,'<br>\n')+
                    "<span class='edit-follow'>"+
                        "<i class='material-icons edit-follow-icon' data-value="+previousRemark+" data-id="+previousId+">edit</i>"+
                    "</span>"+
                "</div>";
                $('.edit-follow-'+previousId).html(data);
            }
            var remarkData = "<input type ='text' name='total' value='"+value+"' class='form-control remark-val follow-data remark-value-"+dId+"' data-value='"+value+"' data-id="+dId+">";
            $('.edit-follow-'+dId).html(remarkData);
        });

        $(document).on('blur','.follow-data',function(){
            var remark = $(this).val();
            var iuiId = $(this).data('id');
            var remarkValue = 'followUP='+remark+'&iui_id='+iuiId;
            updateRemark(remarkValue,'blur');
        });

        $(document).on('keyup','.follow-data',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var remark = $(this).val();
                var iuiId = $(this).data('id');
                var remarkValue = 'followUP='+remark+'&iui_id='+iuiId;
                updateRemark(remarkValue,'keyup');
            }
        });
        function updateRemark(remarkValue,type){
            $.ajax({
                url: "{{URL::to('iui-update-followUp')}}?"+remarkValue,
                dataType: 'json',
            }).done(function(data) {
                if(type == 'blur'){
                    showNotification('bg-blue', 'FollowUp changed successfully.', 'bottom', 'right', "", "");
                }
               location.reload(true);
            }).fail(function() {
                location.reload(true);
            });
        }
</script>
@stop
