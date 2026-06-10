@extends('layouts.main')
@section('parentPageTitle', 'ANC Appointment History')
@section('title', 'ANC Appointment History')
@section('page-style')
@php
$weekData =  [1=>'Normal Size',2=>'Just Bulky',3=>'6 Weeks',4=>'6 - 8 Weeks',5=>'8 Weeks',6=>'8 - 10 Weeks',7=>'10 - 12 Weeks',8=>'12 Weeks',9=>'Uterus Just Palpable',10=>'14 Weeks',11=>'16 Weeks',12=>'18 Weeks',13=>'20 Weeks',14=>'22 Weeks',15=>'24 Weeks',16=>'26 Weeks',17=>'28 Weeks',18=>'30 Weeks',19=>'32 Weeks',20=>'34 Weeks',21=>'36 Weeks',22=>'Full Term'];
    
@endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('css/image-uploader.css')}}" rel="stylesheet">
    <style>
        .anc-header
        {
            font-size: 15px;
        }
        .anc-header ul li{
            list-style: none;
        }
        .font-12
        {
            font-size: 12px;
        }
        
    </style>
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header d-flex">
                    <div class="col-md-6">
                        @php
                            $careOf = (!empty($patients->reference_doctor_id) && isset($referenceDoctor[$patients->reference_doctor_id])) ? $referenceDoctor[$patients->reference_doctor_id] : '';
                            if(!empty($patients->getPatients['reference_doctor_id']) && $patients->getPatients['reference_doctor_id'] == 1)
                            {
                                $careOf = !empty($patients->getPatients['reference_pt_name']) ? $patients->getPatients['reference_pt_name'].'('.$patients->getPatients['reference_pt_mobile'].')' :'SELF';
                            }
                        @endphp
                        <h2><strong class="text-secondary"> {{ucwords($patients->name)}}</strong>{{' care of '.$careOf}}</h2>
                    </div>
                    <div class="col-md-6">
                        <a href="{{URL::to('patient-history/'.encrypt($patients->id))}}" target="_blank" class="btn btn-primary pull-right">View History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="candor-color">ANC Chart</h5>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12 d-flex anc-header">
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>Patient's Name : </strong>{{ucwords(strtolower($patients->name))}}</li>
                                    <li><strong>Age / Weight: </strong>{{$patients->age.' Year / '.$patients->weight.' kg'}}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>LMP Date : </strong>{{\Carbon\Carbon::parse($otherDetails['lmp_date'])->format('d-m-Y')}}</li>
                                    <li><strong>EDD Date : </strong>{{\Carbon\Carbon::parse($otherDetails['edd'])->format('d-m-Y')}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex anc-header mt-3">
                            <div class="col-md-3">
                                <ul style="color: red;">
                                    <li class="candor-color"><h6><strong><u>Diagnosis: </u></strong></h6></li>
                                @if (isset($otherDetails['oe_remark']) && !empty($otherDetails['oe_remark']))
                                    <span class="anc-label ">*Last Remark:</span>
                                                {{$otherDetails['oe_remark']}}
                                @endif
                                @if (isset($ancFirst_patientsObstratics->gpal_status) && !empty($ancFirst_patientsObstratics->gpal_status))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*GPAL Status:</span>
                                                {{$ancFirst_patientsObstratics->gpal_status}}
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['blood_group']) &&  (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['blood_group_date'])))
                                    <span class="anc-label ">*Blood Group:</span>
                                        {{$ancAutoRemark['blood_group']}}
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['hbsag']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hbsag_date'])))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*HBSAG:</span>
                                        {{$ancAutoRemark['hbsag']}}
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['hiv']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hiv_date'])))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*HIV:</span>
                                        {{$ancAutoRemark['hiv']}}
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['vdrl']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['vdrl_date'])))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*VDRL:</span>
                                        {{$ancAutoRemark['vdrl']}}
                                @endif
                                
                                @if($ancAutoRemark && !empty($ancAutoRemark['late_concept']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['late_concept_date'])))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Late Conception:</span>
                                    Yes
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['cesarean']))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Previous:</span>
                                        {{$ancAutoRemark['cesarean']. ' - LSCS'}}
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['position']) && ($ancAutoRemark['position'] == 'breech' || $ancAutoRemark['position'] == 'transverse' || $ancAutoRemark['position'] == 'oblique'))
                                    @if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['position_date']))    
                                        <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Position:</span>
                                        {{$ancAutoRemark['position']}}
                                    @endif
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['liquor']) && ($ancAutoRemark['liquor'] == 'oligo' || $ancAutoRemark['liquor'] == 'poly') && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['liquor_date'])))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Liquor:</span>
                                        {{$ancAutoRemark['liquor']}}
                                @endif
                                @if($ancAutoRemark && !empty($ancAutoRemark['placenta']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['placenta_date'])))
                                    <span class="anc-label ">&nbsp;&nbsp;&nbsp;*Placenta:</span>
                                        {{$ancAutoRemark['placenta']}}
                                @endif
                                </ul>
                            </div>
                            
                            <div class="col-md-3">
                                <ul>
                                    <li class="candor-color"><h6><strong><u>USG : </u></strong></h6></li>
                                    <li><strong>NT Scan : </strong>{{!empty($otherDetails['nt_scan']) ? $otherDetails['nt_scan'] : ''}}</li>
                                    <li><strong>Anomalies Scan : </strong>{{!empty($otherDetails['anomalies_miles']) ? $otherDetails['anomalies_miles'] : ''}}</li>
                                    <li><strong>Double Marker : </strong>{{!empty($otherDetails['d_m_date']) ? $otherDetails['d_m_date'] : ''}}</li>
                                </ul>
                            </div>
                        {{-- </div> --}}
                        {{-- <div class="col-md-12 d-flex anc-header"> --}}
                            <div class="col-md-3">
                                <ul>
                                    <li class="candor-color"><h6><strong><u>History details: </u></strong></h6></li>
                                    <li><strong>Personal History : </strong>{{implode(',',$otherDetails['personal_history'])}}</li>
                                    <li><strong>Family History : </strong>{{implode(',',$otherDetails['family_history'])}}</li>
                                    <li><strong>Past History: </strong>{{implode(',',$otherDetails['past_history'])}}</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul>
                                    <li class="candor-color"><h6><strong><u>Injections : </u></strong></h6></li>
                                    <li><strong>T1 Date : </strong>{{!empty($otherDetails['tt1']) ? $otherDetails['tt1'] : ''}}</li>
                                    <li><strong>T2 Date : </strong>{{!empty($otherDetails['tt2']) ? $otherDetails['tt2'] : ''}}</li>
                                    <li><strong>Betnasol 1 : </strong>{{!empty($otherDetails['betnasol_1']) ? $otherDetails['betnasol_1'] : ''}}</li>
                                    <li><strong>Betnasol 2 : </strong>{{!empty($otherDetails['betnasol_2']) ? $otherDetails['betnasol_2'] : ''}}</li>
                                </ul>
                            </div>
                                {{-- <ul>
                                    <li><strong>LMP Date : </strong>{{$otherDetails['lmp_date']}}</li>
                                    <li><strong>EDD Date : </strong>{{$otherDetails['edd']}}</li>
                                    <li><strong>T1 Date : </strong>{{!empty($otherDetails['tt1']) ? $otherDetails['tt1'] : ''}}</li>
                                    <li><strong>T2 Date : </strong>{{!empty($otherDetails['tt2']) ? $otherDetails['tt2'] : ''}}</li>
                                    <li><strong>Betnasol 1 : </strong>{{!empty($otherDetails['betnasol_1']) ? $otherDetails['betnasol_1'] : ''}}</li>
                                    <li><strong>Betnasol 2 : </strong>{{!empty($otherDetails['betnasol_2']) ? $otherDetails['betnasol_2'] : ''}}</li>
                                    <li><strong>NT Scan : </strong>{{!empty($otherDetails['nt_scan']) ? $otherDetails['nt_scan'] : ''}}</li>
                                    <li><strong>Anomalies Scan : </strong>{{!empty($otherDetails['anomalies_miles']) ? $otherDetails['anomalies_miles'] : ''}}</li>
                                    <li><strong>Double Marker : </strong>{{!empty($otherDetails['d_m_date']) ? $otherDetails['d_m_date'] : ''}}</li>
                                </ul> --}}
                        </div>
                    </div>
                    <div class="row">
                        @php
                            $columns = array();
                            foreach ($chartData as $row) {
                                foreach ($row as $roll => $set) {
                                    foreach ($set as $class => $score) {
                                        $columns[$class] = $class;
                                    }
                                }
                            }
                        @endphp
                        <div class="col-md-12 col-lg-12 table-responsive">
                            @if(!empty($columns))
                                {{-- <h6>Investigation Detail:</h6> --}}
                                <table class="table table-bordered font-12">
                                    <thead>
                                        <tr>
                                            <th class=""><strong>Investigation Date</strong></th>
                                            @foreach ($columns as $column) 
                                                <th><strong>{{$column}}</strong></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chartData as $row)
                                            <tr>
                                            @foreach ($row as $investigation => $set)
                                                <td>{{$investigation }}</td>
                                                @foreach ($columns as $class) 
                                                    <td>{{((array_key_exists($class, $set)) ? $set[$class] : '')}}</td>
                                                @endforeach
                                            @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div class="col-md-12 col-lg-12 table-responsive">
                            {{-- <h6>Anc Details:</h6> --}}
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th><strong>Date</strong></th>
                                        <th><strong>Weight</strong></th>
                                        <th><strong>B.P</strong></th>
                                        <th><strong>Fundle Height</strong></th>
                                        <th><strong>FHS</strong></th>
                                        <th><strong>Liquor</strong></th>
                                        <th><strong>Position</strong></th>
                                        <th><strong>EBW</strong></th>
                                        <th><strong>Remark</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ancHistory as $anc)
                                    @php
                                        $ho = json_decode($anc->h_o);
                                        $oe = json_decode($anc->o_e,true);
                                    @endphp
                                    <tr>
                                        <td>{{\Carbon\Carbon::parse($anc->created_at)->format('d-m-Y')}}</td>
                                        <td>{{!empty($ho->weight) ? $ho->weight.' kg' : '-'}}</td>
                                        <td>{{!empty($oe['le']['bp']) ? $oe['le']['bp'] : ''}}</td>
                                        <td>{{isset($weekData[$oe['utdata'][1]['oe_ut_sac_1']]) ? $weekData[$oe['utdata'][1]['oe_ut_sac_1']] : ''}}</td>
                                        <td>{{isset($oe['utdata'][1]['fcp']) ? $oe['utdata'][1]['fcp'] : ''}}</td>
                                        <td>{{isset($oe['utdata'][1]['liquor_type']) ? $oe['utdata'][1]['liquor_type'] : ''}}</td>
                                        <td>{{isset($oe['utdata'][1]['position_type']) ? $oe['utdata'][1]['position_type'] : ''}}</td>
                                        <td>{{isset($oe['utdata'][1]['expected_birth_weight']) ? $oe['utdata'][1]['expected_birth_weight'] : ''}}</td>
                                        <td>{{isset($oe['utdata']['remark']) ? $oe['utdata']['remark'] : ''}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-script')
<script src="{{url('js/anc.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
$.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>


@stop
