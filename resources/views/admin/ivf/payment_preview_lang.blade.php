<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<style type="text/css">
    .seperator {
        border-top: 0.5px solid #dee2e6;
    }
    tbody tr th .font{
        color: #999;
    }
     @media print {
     {page-break-after: always;}
    }
    .row{
        margin-left: 0px !important;
    }
    .panel-primary{
        font-size: 20px !important;
    }
    #consulation{
        display: block;
    }
    .border-top-1
    {
        border-top: 1px solid black;
    }
    
</style>
<div class="main-print-ivf-div">
        <div class="panel panel-primary">
            <h3 class="text-center">{{config('app.hospitalname1')}}</h3>
            @php
            $cycle_type = $ivfPayment->cycle_type;
            $consulation = $ivfPayment->consulation;
            $sonography_charge = $ivfPayment->sonography_charge;
            $ivf_lab_charge = $ivfPayment->ivf_lab_charge;
            $HMG = $ivfPayment->HMG;
            $RFSH = $ivfPayment->RFSH;
            $Gonal_F = $ivfPayment->Gonal_F;
            $icsi_ivf_charge = $ivfPayment->icsi_ivf_charge;
            $embroy_tranfer = $ivfPayment->embroy_tranfer;
            $embroy_freezing = $ivfPayment->embroy_freezing;
            $embryologist_charge = $ivfPayment->embryologist_charge;
            $surgeon_charge = $ivfPayment->surgeon_charge;
            $semen_freezing_charge = $ivfPayment->semen_freezing_charge;
            $hystrocopy = $ivfPayment->hystrocopy;
            $donor_charge = $ivfPayment->donor_charge;
            $medical_medicines = $ivfPayment->medical_medicines;
            $unconscious_charge_status = $ivfPayment->unconscious_charge_status;
            $anesthescis_doctor = $ivfPayment->anesthescis_doctor;
            $blood_report = $ivfPayment->blood_report;
            $tesa_pesa = $ivfPayment->tesa_pesa;
            $ovum_embryopooling = $ivfPayment->ovum_embryopooling;
            $emdomatrial_report = $ivfPayment->emdomatrial_report;
            $TBPCR = $ivfPayment->TBPCR;
            $PAMP = $ivfPayment->PAMP;
            $ERA = $ivfPayment->ERA;

            $language = $ivfPayment->language;
            @endphp

             @if($language== "1")

            <div class="row">
            
                <div class="col-md-9 p-name-text">
                    હું <b>{{$ivfPayment->patient_name}}</b>  અને મારા પતિ <b>{{$ivfPayment->husband_name ? $ivfPayment->husband_name : '-'}}</b> IVF ટેસ્ટ ટ્યુબ બેબીની સારવાર રાધા હોસ્પિટલ ખાતે શરૂ કરવામાં આવેલ છે.
                </div>
                <div class="col-md-3 p-name-text">
                    Date : <b>{{\Carbon\Carbon::parse($ivfPayment->date)->format('d-m-Y')}}</b>  
                </div>
            </div>
            <div class="row mt-3 p-1">
                <div class="col-md-12 p-name-text">
                    અમારી સારવારનો અંદાજિત ખર્ચ <b>{{$ivfPayment->package}}</b> અને સાઇકલ <b>{{$ivfPayment->cycle_no}}</b> સમજાવેલ છે.
                </div>
            </div>
           <div class="row">
            <div class="col-md-6">
                <h4 class="row p-2">Include</h4>
                <ul>
                    @if(!empty($cycle_type))
                    <li>Cycle Type : <b>{{$ivfPayment->cycle_type }}</b> </li>
                    @endif
                    
                    @if(!empty($consulation))
                    <li>Consulation : <b>{{$ivfPayment->consulation }} /-</b> Per Visit </li>
                    @endif

                    @if(!empty($sonography_charge))
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }} /-</b> Per Visit</li>
                    @endif

                    @if(!empty($ivf_lab_charge))
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}} /-</b> (1 time)</li>
                    @endif

                    @if(!empty($HMG) || !empty($RFSH) || !empty($Gonal_F))
                    <li>Gonadotropins Injection :
                        @if(!empty($HMG))
                        <ol>
                            1. HMG : <b>{{$ivfPayment->HMG_approx }} To {{$ivfPayment->HMG }}</b> 
                        </ol>
                        @endif 
                        @if(!empty($RFSH))
                        <ol>
                            2. RFSH : <b>{{$ivfPayment->RFSH_approx }} To {{$ivfPayment->RFSH }}</b> 
                        </ol>
                        @endif 
                        @if(!empty($Gonal_F))
                        <ol>
                            3. Gonal-F : <b>{{$ivfPayment->GonalF_approx}} To {{$ivfPayment->Gonal_F}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                    @if(!empty($icsi_ivf_charge))
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}} /-</b> (1 time) </li>
                    @endif

                    @if(!empty($embroy_tranfer))
                    <li>Embryo Transfer (ગર્ભ મૂકવાનો ચાર્જ) : <b>{{$ivfPayment->embroy_tranfer}} /-</b> (1 time) </li>
                    @endif 

                    @if(!empty($embroy_freezing))
                    <li>Embryo Freezing (3 મહિના માટે) : <b>{{$ivfPayment->embroy_freezing}} /-</b> Per loop </li>
                    @endif 

                    @if(!empty($embryologist_charge))
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> </li>
                    @endif 

                    @if(!empty($surgeon_charge))
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }} /-</b> Per Surgery</li>
                    @endif 

                    @if(!empty($semen_freezing_charge))
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> </li>
                    @endif 

                    @if(!empty($hystrocopy))
                    <li>HYSTROSCOPY (ગર્ભશાયની દુરબીનની તપાસ અંદાજે 12000/- થી 15000/- ) :<b> {{$ivfPayment->hystrocopy_approx}} To {{$ivfPayment->hystrocopy }}</b> </li>
                    @endif 
                       
                    @if(!empty($donor_charge) && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                        જો સ્ત્રી બીજ અંદર થીથી લેવાના થાય તો : <b>{{$ivfPayment->donor_charge}}</b>  
                        <ol>
                           1.ડોનર બીજ
                        </ol>
                        <ol>
                            2. ડોનર બ્લડ રીપોર્ટ
                        </ol>
                        <ol>
                            3. ડોનર ને આવવા-જવાનો ખર્ચ  
                        </ol>
                        <ol>
                            4. ડોનરને બીજ બનાવવાના આપવામાં આવતા ઈંજેક્સન ચાર્જ =25,000/-થી 45,000/-
                        </ol>
                    </li>
                    @endif 

                    @if(!empty($medical_medicines))
                    <li>મેડીકલની દવા (અંદાજિત  10,000/- થી 15000/-) : <b>{{$ivfPayment->medical_medicines_approx }} To {{$ivfPayment->medical_medicines }}</b> </li>
                    @endif 

                    @if(!empty($anesthescis_doctor))
                    <li>બેભાન કરવાના નો ચાર્જ (અંદાજે-2500/-) : <b>{{$ivfPayment->anesthescis_doctor }}</b> </li>
                    @endif 

                    @if(!empty($blood_report))
                    <li>લોહી ના રિપોર્ટ(અંદાજિત– 2000/- થી 3000/-) : <b>{{$ivfPayment->blood_report_approx }} To {{$ivfPayment->blood_report }}</b> </li>
                    @endif 

                    @if(!empty($tesa_pesa))
                    <li>TESA / PESA (અંદાજિત – 10,000/-) : <b>{{$ivfPayment->tesa_pesa }}</b> </li>
                    @endif 

                    @if(!empty($ovum_embryopooling))
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling_approx}} To {{$ivfPayment->ovum_embryopooling }}</b> </li>
                    @endif 

                    @if(!empty($TBPCR) || !empty($PAMP) || !empty($ERA))
                        <li>Endomatrial Biopsy Report :
                            @if(!empty($TBPCR))
                            <ol>
                                1. TBPCR  (અંદાજિત– 2000/-): <b>{{$ivfPayment->TBPCR }}</b> 
                            </ol>
                            @endif 

                            @if(!empty($PAMP))
                            <ol>
                                2. PAMP  (અંદાજિત– 4200/-): <b>{{$ivfPayment->PAMP }}</b> 
                            </ol>
                            @endif 

                            @if(!empty($ERA))
                            <ol>
                                3. ERA  (અંદાજિત– 40000/-): <b>{{$ivfPayment->ERA}}</b> 
                            </ol>
                            @endif 
                        </li>
                    @endif 
                    
                </ul>
            </div>
            <div class="col-md-6">
                <h4 class="row p-2">Not Include</h4>
                <ul>
                    @if(empty($cycle_type))
                    <li>Cycle Type : <b>{{$ivfPayment->cycle_type }}</b> </li>
                    @endif

                    @if(empty($consulation))
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> </li>
                    @endif

                    @if(empty($sonography_charge))
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }}</b> </li>
                    @endif

                    @if(empty($ivf_lab_charge))
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b> </li>
                    @endif

                    @if(empty($HMG) && empty($RFSH) && empty($Gonal_F))
                    <li>Gonadotropins Injection :
                        @if(empty($HMG))
                        <ol>
                            1. HMG  : <b>{{$ivfPayment->HMG }}</b> 
                        </ol>
                        @endif 
                        @if(empty($RFSH))
                        <ol>
                            2. RFSH : <b>{{$ivfPayment->RFSH }}</b> 
                        </ol>
                        @endif 
                        @if(empty($Gonal_F))
                        <ol>
                            3. Gonal-F : <b>{{$ivfPayment->Gonal_F}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                    @if(empty($icsi_ivf_charge))
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> </li>
                    @endif

                    @if(empty($embroy_tranfer))
                    <li>Embryo Transfer (ગર્ભ મૂકવાનો ચાર્જ) : <b>{{$ivfPayment->embroy_tranfer}}</b> </li>
                    @endif 

                    @if(empty($embroy_freezing))
                    <li>Embryo Freezing (3 મહિના માટે) : <b>{{$ivfPayment->embroy_freezing }}</b> </li>
                    @endif 

                    @if(empty($embryologist_charge))
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> </li>
                    @endif 

                    @if(empty($surgeon_charge))
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> </li>
                    @endif 

                    @if(empty($semen_freezing_charge))
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> </li>
                    @endif 

                    @if(empty($hystrocopy))
                    <li>HYSTROSCOPY (ગર્ભશાયની દુરબીનની તપાસ અંદાજે 12000/- થી 15000/- ) :<b> {{$ivfPayment->hystrocopy }}</b> </li>
                    @endif 
                       
                    @if(empty($donor_charge) && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                        જો સ્ત્રી બીજ અંદર થી લેવાના થાય તો : <b>{{$ivfPayment->donor_charge}}</b>  
                        <ol>
                           1.ડોનર બીજ
                        </ol>
                        <ol>
                            2. ડોનર બ્લડ રીપોર્ટ
                        </ol>
                        <ol>
                            3. ડોનર ને આવવા-જવાનો ખર્ચ  
                        </ol>
                        <ol>
                            4. ડોનરને બીજ બનાવવાના આપવામાં આવતા ઈંજેક્સન ચાર્જ=25,000/-થી 45,000/-
                        </ol>
                    </li>
                   
                    @endif 

                    @if(empty($medical_medicines))
                    <li>મેડીકલની દવા (અંદાજિત  10,000/- થી 15000/-) : <b>{{$ivfPayment->medical_medicines }}</b> </li>
                    @endif 

                    @if(empty($anesthescis_doctor))
                    <li>બેભાન કરવાના નો ચાર્જ (અંદાજે-2500/-) : <b>{{$ivfPayment->anesthescis_doctor }}</b> </li>
                    @endif 

                    @if(empty($blood_report))
                    <li>લોહી ના રિપોર્ટ(અંદાજિત– 2000/- થી 3000/-) : <b>{{$ivfPayment->blood_report }}</b> </li>
                    @endif 

                    @if(empty($tesa_pesa))
                    <li>TESA / PESA (અંદાજિત – 10,000/-) : <b>{{$ivfPayment->tesa_pesa }}</b> </li>
                    @endif 

                    @if(empty($ovum_embryopooling))
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> </li>
                    @endif 

                    @if(empty($TBPCR) && empty($PAMP) && empty($ERA))
                    <li>Endomatrial Biopsy Report :
                        @if(empty($TBPCR))
                        <ol>
                            1. TBPCR  (અંદાજિત– 2000/-): <b>{{$ivfPayment->TBPCR }}</b> 
                        </ol>
                        @endif 

                        @if(empty($PAMP))
                        <ol>
                            2. PAMP  (અંદાજિત– 4200/-): <b>{{$ivfPayment->PAMP }}</b> 
                        </ol>
                        @endif 

                        @if(empty($ERA))
                        <ol>
                            3. ERA  (અંદાજિત– 40000/-): <b>{{$ivfPayment->ERA}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                </ul>
            </div>

            </div>
                
            </div>
            <h5 class="row p-2">Notes :</h5>
             <div class="row p-2">
                બીજી વખત ગર્ભ (જો મૂકવાના થાય તો) મૂકવાનો ખર્ચ  અલગથી થસે
            </div>
            <div class="row p-2">
                એકવાર પ્રેગ્નેન્સી રીપોર્ટ પોજીટીવ આવ્યા પછી નો ખર્ચ પેકેજ માં આવશે નહિ 
            </div>
            <div class="row p-2">
                આ ઉપરાંત IVF સારવાર દરમિયાન કોઈ ભાગ્યે જ complication આવે તો એનો ખર્ચ અને  જો બીજી કોઈ હોસ્પિટલ માં સારવાર કરવાની થાય તો ત્યાં નો ખર્ચ અલગથી રહેશે.
            </div>
           <!--  <div class="row p-2">
                જો બીજી કોઈ હોસ્પિટલ માં સારવાર કરવાની થાય તો ત્યાં નો ખર્ચ અલગથી રહેશે.
            </div> -->

            <div class="row p-2 m-2">
                Remark:- {{$ivfPayment->remark}}
            </div>
            <div class="row p-2 m-2">
                Payment Condition:- {{$ivfPayment->condition}}
            </div>
           
            <div class="row p-2 m-2">              
                દર્દી ની સહી :- _______________________
            </div>
            <div class="row p-2">
                દર્દી ના સગા ની સહી :- _______________________
            </div>


        @elseif($language== "2")
            <div class="row mt-5 p-2">
                 <div class="col-md-12 p-name-text">
                    मे <b>{{$ivfPayment->patient_name}}</b>  और मेरे पति <b>{{$ivfPayment->husband_name ? $ivfPayment->husband_name : '-'}}</b> आईवीएफ टेस्ट ट्यूब बेबी का उपचार राधा अस्पताल में शुरू किया गया है।
                </div>
                <div class="col-md-3 p-name-text">
                    Date : <b>{{\Carbon\Carbon::parse($ivfPayment->date)->format('d-m-Y')}}</b>
                </div>  
            </div>
            <div class="row mt-3 p-1">
                 <div class="col-md-12 p-name-text">
                    हमारे उपचार का अनुमानित चार्ज <b>{{$ivfPayment->package}}</b> तथा साईकल <b>{{$ivfPayment->cycle_no}}</b> समजाया  है।
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                <h4 class="row p-2">Include</h4>
                <ul>
                    @if(!empty($cycle_type))
                    <li>Cycle Type : <b>{{$ivfPayment->cycle_type }}</b> </li>
                    @endif

                    @if(!empty($consulation))
                    <li>Consulation : <b>{{$ivfPayment->consulation }}/-</b> Per Visit </li>
                    @endif

                    @if(!empty($sonography_charge))
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }} /-</b> Per Visit </li>
                    @endif

                    @if(!empty($ivf_lab_charge))
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge }} /-</b> (1 time) </li>
                    @endif

                    @if(!empty($HMG) || !empty($RFSH) || !empty($Gonal_F))
                    <li>Gonadotropins Injection :
                        @if(!empty($HMG))
                        <ol>
                            1. HMG  : <b>{{$ivfPayment->HMG_approx }} to {{$ivfPayment->HMG }}</b> 
                        </ol>
                        @endif 

                        @if(!empty($RFSH)) 
                        <ol>
                            2. RFSH : <b>{{$ivfPayment->RFSH_approx }} to {{$ivfPayment->RFSH }}</b> 
                        </ol>
                        @endif 

                        @if(!empty($Gonal_F))
                        <ol>
                            3. Gonal-F : <b>{{$ivfPayment->GonalF_approx}} to {{$ivfPayment->Gonal_F}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif
                           
                    @if(!empty($icsi_ivf_charge))
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}} /-</b> (1 time) </li>
                    @endif

                    @if(!empty($embroy_tranfer))
                    <li>Embryo Transfer  : <b>{{$ivfPayment->embroy_tranfer}} /-</b> (1 time) </li>
                    @endif 

                    @if(!empty($embroy_freezing))
                    <li>Embryo Freezing (3 महीने के लिए) : <b>{{$ivfPayment->embroy_freezing }} /-</b> Per loop </li>
                    @endif 

                    @if(!empty($embryologist_charge))
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> </li>
                    @endif 

                    @if(!empty($surgeon_charge)) 
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }} /-</b> Per Surgery </li>
                    @endif 

                    @if(!empty($semen_freezing_charge))
                    <li>Semen Freezing Charge (अनुमानित 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> </li>
                    @endif 

                    @if(!empty($hystrocopy))
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy_approx }} to {{$ivfPayment->hystrocopy }}</b> </li>
                    @endif 


                    @if(!empty($donor_charge) && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                         यदि स्त्री बीज अंदर से लिया जाना है:  <b>{{$ivfPayment->donor_charge}}</b>  
                        <ol>
                             1. डोनर बीज
                        </ol>
                        <ol>
                            2. डोनर ब्लड रिपोर्ट
                        </ol>
                        <ol>
                            3. डोनर कम्यूटिंग कॉस्ट 
                        </ol>
                        <ol>
                            4. बीज बनाने के लिए डोनर को दिया गया इंजेक्शन चार्ज = 25,000 / - से 45,000 / -
                        </ol>
                    </li>
                    @endif 

                    @if(!empty($medical_medicines))
                    <li>Medical medicine (अनुमानित 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines_approx }} to {{$ivfPayment->medical_medicines }}</b> </li>
                    @endif 

                    @if(!empty($anesthescis_doctor))
                    <li>एनेस्थीसिया का चार्ज (अनुमानित  -2500 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> </li>
                    @endif 

                    @if(!empty($blood_report))
                    <li> रक्त (Blood) रिपोर्ट(अनुमानित - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report_approx }} to {{$ivfPayment->blood_report }}</b> </li>
                    @endif 

                    @if(!empty($tesa_pesa))
                    <li>TESA / PESA (अनुमानित - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> </li>
                    @endif 

                    @if(!empty($ovum_embryopooling))
                    <li>Ovum Embryo Pooling (अनुमानित 60,000/- to अनुमानित 80,000/-): <b>{{$ivfPayment->ovum_embryopooling_approx }} to {{$ivfPayment->ovum_embryopooling }}</b> </li>
                    @endif 

                    @if(!empty($TBPCR) || !empty($PAMP) || !empty($ERA))
                    <li>Endomatrial Biopsy Report :
                        @if(!empty($TBPCR))
                        <ol>
                            1. TBPCR  (अनुमानित 2000/-): <b>{{$ivfPayment->TBPCR }}</b> 
                        </ol>
                        @endif 

                        @if(!empty($PAMP))
                        <ol>
                            2. PAMP  (अनुमानित 4200/-): <b>{{$ivfPayment->PAMP }}</b> 
                        </ol>
                        @endif 

                        @if(!empty($ERA))
                        <ol>
                            3. ERA  (अनुमानित 40000/-): <b>{{$ivfPayment->ERA}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                </ul>
            </div>

            <div class="col-md-6">
                <h4 class="row p-2">Not Include</h4>
                <ul>
                    @if(empty($cycle_type))
                    <li>Cycle Type : <b>{{$ivfPayment->cycle_type }}</b> </li>
                    @endif

                    @if(empty($consulation))
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> </li>
                    @endif

                    @if(empty($sonography_charge))
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }}</b> </li>
                    @endif

                    @if(empty($ivf_lab_charge))
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge }}</b> </li>
                    @endif

                    @if(empty($HMG) && empty($RFSH) && empty($Gonal_F))
                    <li>Gonadotropins Injection :
                        @if(empty($HMG))
                        <ol>
                            1. HMG  : <b>{{$ivfPayment->HMG }}</b> 
                        </ol>
                        @endif 

                        @if(empty($RFSH))
                        <ol>
                            2. RFSH : <b>{{$ivfPayment->RFSH }}</b> 
                        </ol>
                        @endif 

                        @if(empty($Gonal_F))
                        <ol>
                            3. Gonal-F : <b>{{$ivfPayment->Gonal_F}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                    @if(empty($icsi_ivf_charge))
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> </li>
                    @endif

                    @if(empty($embroy_tranfer))
                    <li>Embryo Transfer  : <b>{{$ivfPayment->embroy_tranfer}}</b> </li>
                    @endif 

                    @if(empty($embroy_freezing))
                    <li>Embryo Freezing (3 महीने के लिए) : <b>{{$ivfPayment->embroy_freezing}}</b> </li>
                    @endif 

                    @if(empty($embryologist_charge))
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> </li>
                    @endif 

                    @if(empty($surgeon_charge))
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> </li>
                    @endif 

                    @if(empty($semen_freezing_charge))
                    <li>Semen Freezing Charge (अनुमानित 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> </li>
                    @endif 

                    @if(empty($hystrocopy))
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy }}</b> </li>
                    @endif 

                    @if(empty($donor_charge) && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                         यदि मादा बीज अंदर से लिया जाना है:  <b>{{$ivfPayment->donor_charge}}</b>  
                        <ol>
                             1. डोनर बीज
                        </ol>
                        <ol>
                            2. डोनर ब्लड रिपोर्ट
                        </ol>
                        <ol>
                            3. डोनर कम्यूटिंग कॉस्ट 
                        </ol>
                        <ol>
                            4. बीज बनाने के लिए डोनर को दिया गया इंजेक्शन चार्ज = 25,000 / - से 45,000 / -
                        </ol>
                    </li>
                    @endif 

                    @if(empty($medical_medicines))
                    <li>Medical medicine (अनुमानित 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines }}</b> </li>
                    @endif 

                    @if(empty($anesthescis_doctor))
                    <li>एनेस्थीसिया का चार्ज (अनुमानित  -2500 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> </li>
                    @endif 

                    @if(empty($blood_report))
                    <li> रक्त (Blood) रिपोर्ट (अनुमानित - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report }}</b> </li>
                    @endif 

                    @if(empty($tesa_pesa))
                    <li>TESA / PESA (अनुमानित - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> </li>
                    @endif 

                    @if(empty($ovum_embryopooling))
                    <li>Ovum Embryo Pooling (अनुमानित 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> </li>
                    @endif 

                    @if(empty($TBPCR) && empty($PAMP) && empty($ERA))
                    <li>Endomatrial Biopsy Report :
                        @if(empty($TBPCR))
                        <ol>
                            1. TBPCR  (अनुमानित 2000/-): <b>{{$ivfPayment->TBPCR }}</b> 
                        </ol>
                        @endif 

                        @if(empty($PAMP))
                        <ol>
                            2. PAMP  (अनुमानित 4200/-): <b>{{$ivfPayment->PAMP }}</b> 
                        </ol>
                        @endif 

                        @if(empty($ERA))
                        <ol>
                            3. ERA  (अनुमानित 40000/-): <b>{{$ivfPayment->ERA}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif
                </ul>
            </div>
        </div>
                
            </div>
            <h5 class="row p-2">Notes :</h5>
            <div class="row p-2">
               दूसरा Embryo (यदि रखा गया है तो) रखने का चार्ज अलग से होगा  
            </div>
            <div class="row p-2">
               एक बार गर्भावस्था (Pregnancy) रिपोर्ट पोजिटिव होने के बाद का चार्ज पैकेज मे नहीं आएगा 
            </div>
            <div class="row p-2">
                इसके अलावा अगर IVF उपचार के दौरान दुर्लभ से complication आए तो उसका चार्ज और यदि आपको उपचार के लिए दूसरे अस्पताल जाना है तो वहाका चार्ज अलग से होगा ।
            </div>
           <!--  <div class="row p-2">
                यदि आपको उपचार के लिए दूसरे अस्पताल जाना है तो वहाका चार्ज अलग से होगा ।
            </div> -->

            <div class="row p-2 m-2">
                Remark:- {{$ivfPayment->remark}}
            </div>
            <div class="row p-2 m-2">
                Payment Condition:- {{$ivfPayment->condition}}
            </div>
            <div class="row p-2 m-2">              
                रोगी के हस्ताक्षर :- _______________________
            </div>
            <div class="row p-2">
                रोगी के रिश्तेदार का हस्ताक्षर :- _______________________
            </div>


        @elseif($language == "3")

            <div class="row mt-5 p-2">
                <div class="col-md-9 p-name-text">
                    I <b>{{$ivfPayment->patient_name}}</b>  Andddd my husband<b>{{$ivfPayment->husband_name ? $ivfPayment->husband_name : '-'}}</b> Treatment of IVF test tube baby has been started at Radha Hospital.
                </div>
                <div class="col-md-3 p-name-text">
                    Date : <b>{{\Carbon\Carbon::parse($ivfPayment->date)->format('d-m-Y')}}</b>  
                </div>
            </div>
            <div class="row mt-3 p-1">
                <div class="col-md-12 p-name-text">
                    Estimated cost of our treatment <b>{{$ivfPayment->package}}</b> And Cycle <b>{{$ivfPayment->cycle_no}}</b> Explained.
                </div>
            </div>  

            <div class="row">
                <div class="col-md-6">
                <ul>
                    <h4 class="row p-2">Include</h4>
                    @if(!empty($cycle_type))
                    <li>Cycle Type : <b>{{$ivfPayment->cycle_type }}</b> </li>
                    @endif
                    @if(!empty($consulation))
                    <li>Consulation : <b>{{$ivfPayment->consulation}}/-</b> Per Visit</li>
                    @endif

                    @if(!empty($sonography_charge))
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge}}/-</b> Per Visit</li>
                    @endif

                    @if(!empty($ivf_lab_charge))
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b> (1 time)</li>
                    @endif

                    @if(!empty($HMG) || !empty($RFSH) || !empty($Gonal_F))
                    <li>Gonadotropins Injection 
                        @if(!empty($HMG))
                        <ol>
                            1. HMG : <b>{{$ivfPayment->HMG_approx }} To {{$ivfPayment->HMG }}</b> 
                        </ol>
                        @endif 

                        @if(!empty($RFSH))
                        <ol>
                            2. RFSH : <b>{{$ivfPayment->RFSH_approx }} To {{$ivfPayment->RFSH }}</b> 
                        </ol>
                        @endif 

                        @if(!empty($Gonal_F))
                        <ol>
                            3. Gonal-F : <b>{{$ivfPayment->GonalF_approx}} To {{$ivfPayment->Gonal_F}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                    @if(!empty($icsi_ivf_charge))
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}/-</b>(1 time) </li>
                    @endif

                    @if(!empty($embroy_tranfer))
                    <li>Embryo Transfer : <b>{{$ivfPayment->embroy_tranfer}}/-</b> (1 time) </li>
                    @endif 

                    @if(!empty($embroy_freezing))
                    <li>Embryo Freezing (For 3 months) : <b>{{$ivfPayment->embroy_freezing }}/-</b> Per loop </li>
                    @endif 

                    @if(!empty($embryologist_charge))
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> </li>
                    @endif 

                    @if(!empty($surgeon_charge))
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}/-</b> Per Surgery </li>
                    @endif 

                    @if(!empty($semen_freezing_charge))
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> </li>
                    @endif 

                    @if(!empty($hystrocopy))
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy_approx }} To {{$ivfPayment->hystrocopy }}</b> </li>
                    @endif 

                    @if(!empty($donor_charge) && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                        If the female Egg is to be taken from outside: <b>{{$ivfPayment->donor_charge}}</b>  
                        <ol>
                            1. Donor Eggs
                        </ol>
                        <ol>
                            2. Donor Blood Report
                        </ol>
                        <ol>
                            3. Donor Travel Costs
                        </ol>
                        <ol>
                            4. Injection charge given to the donor for stimulation = 25,000 /- to 45,000 /-
                        </ol>
                    </li>               
                    @endif 

                    @if(!empty($medical_medicines))
                    <li>Medical medicine (estimated 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines_approx }} To {{$ivfPayment->medical_medicines }}</b> </li>
                    @endif 

                    @if(!empty($anesthescis_doctor))
                    <li>Anaesthetist Charge (approximately -2500 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> </li>
                    @endif 

                    @if(!empty($blood_report))
                    <li>Blood Report (Estimated - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report_approx }}  To {{$ivfPayment->blood_report }}</b> </li>
                    @endif 

                    @if(!empty($tesa_pesa))
                    <li>TESA / PESA (Estimated - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> </li>
                    @endif 

                    @if(!empty($ovum_embryopooling))
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling_approx }} To {{$ivfPayment->ovum_embryopooling }}</b> </li>
                    @endif 

                    @if(!empty($TBPCR) || !empty($PAMP) || !empty($ERA))
                        <li>Endomatrial Biopsy Report :
                            @if(!empty($TBPCR))
                            <ol>
                                1. TBPCR  (Approx 2000/-): <b>{{$ivfPayment->TBPCR }}</b> 
                            </ol>
                            @endif 

                            @if(!empty($PAMP))
                            <ol>
                                2. PAMP  (Approx 4200/-): <b>{{$ivfPayment->PAMP }}</b> 
                            </ol>
                            @endif 

                            @if(!empty($ERA))
                            <ol>
                                3. ERA  (Approx 40000/-): <b>{{$ivfPayment->ERA}}</b> 
                            </ol>
                            @endif 
                        </li>
                    @endif
                </ul>
                </div>

                <div class="col-md-6">
                <ul>
                <h4 class="row p-2">Not Include</h4>
                   @if(empty($cycle_type))
                    <li>Cycle Type : <b>{{$ivfPayment->cycle_type }}</b> </li>
                    @endif
                    
                    @if(empty($consulation))
                         <li>Consulation : <b>{{$ivfPayment->consulation}}</b></li>               
                    @endif
                    @if(empty($sonography_charge))  
                        <li>Sonography : <b>{{$ivfPayment->sonography_charge}}</b> </li>
                    @endif
                    @if(empty($ivf_lab_charge))
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b></li>
                    @endif

                    @if(empty($HMG) && empty($RFSH) && empty($Gonal_F))
                    <li>Gonadotropins Injection 
                        @if(empty($HMG))
                        <ol>
                            1. HMG : <b>{{$ivfPayment->HMG }}</b> 
                        </ol>
                        @endif 

                        @if(empty($RFSH))
                        <ol>
                            2. RFSH : <b>{{$ivfPayment->RFSH }}</b> 
                        </ol>
                        @endif 

                        @if(empty($Gonal_F))
                        <ol>
                            3. Gonal-F : <b>{{$ivfPayment->Gonal_F}}</b> 
                        </ol>
                        @endif 
                    </li>
                    @endif

                    @if(empty($icsi_ivf_charge))
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b></li>
                    @endif
                    @if(empty($embroy_tranfer))
                    <li>Embryo Transfer  : <b>{{$ivfPayment->embroy_tranfer}}</b> </li>
                    @endif 

                    @if(empty($embroy_freezing))
                    <li>Embryo Freezing (For 3 months) : <b>{{$ivfPayment->embroy_freezing }}</b> </li>
                    @endif 

                    @if(empty($embryologist_charge))
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> </li>
                    @endif 

                    @if(empty($surgeon_charge))
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> </li>
                    @endif 

                    @if(empty($semen_freezing_charge))
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> </li>
                    @endif 

                    @if(empty($hystrocopy))
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy }}</b> </li>
                    @endif 

                    @if(empty($donor_charge) && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                        If the female Egg is to be taken from outside: <b>{{$ivfPayment->donor_charge}}</b>  
                        <ol>
                            1. Donor Eggs
                        </ol>
                        <ol>
                            2. Donor Blood Report
                        </ol>
                        <ol>
                            3. Donor commuting costs
                        </ol>
                        <ol>
                            4. Injection charge given to the donor for stimulation = 25,000 / - to 45,000 / -
                        </ol>
                    </li>
                   
                    @endif 

                    @if(empty($medical_medicines))
                    <li>Medical medicine (estimated 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines }}</b> </li>
                    @endif 

                    @if(empty($anesthescis_doctor))
                    <li>Anaesthetist Charge (approximately -2500 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> </li>
                    @endif 

                    @if(empty($blood_report))
                    <li>Blood Report (Estimated - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report }}</b> </li>
                    @endif 

                    @if(empty($tesa_pesa))
                    <li>TESA / PESA (Estimated - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> </li>
                    @endif 

                    @if(empty($ovum_embryopooling))
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> </li>
                    @endif 

                    @if(empty($TBPCR) && empty($PAMP) && empty($ERA))
                    <li>Endomatrial Biopsy Report :
                        @if(empty($TBPCR))
                        <ol>
                            1. TBPCR  (Approx 2000/-): <b>{{$ivfPayment->TBPCR }}</b> 
                        </ol>
                        @endif 

                        @if(empty($PAMP))
                        <ol>
                            2. PAMP  (Approx 4200/-): <b>{{$ivfPayment->PAMP }}</b> 
                        </ol>
                        @endif 

                        @if(empty($ERA))
                        <ol>
                            3. ERA  (Approx 40000/-): <b>{{$ivfPayment->ERA}}</b> 
                        </ol>
                        @endif
                    </li>
                    @endif
                     
                </ul>
                </div>
            <!-- </div> -->
                
            </div>
            <h5 class="row p-2">Net Amount :</h5>
                <div class="row">
                    <div class="col-md-12"><span>Package : {{$ivfPayment->package}}</span></div>
                    <div class="col-md-12"><span>Discount : {{$ivfPayment->discount}}</span></div>
                    <div class="col-md-3 border-top-1"><span><strong>Total Amount: {{$ivfPayment->package - $ivfPayment->discount}}</strong></span></div>
                </div>
                <br>
            <h5 class="row p-2">Notes :</h5>
            <div class="row p-2">
               The cost of placing the second embryo (if placed) is requered
            </div>
            <div class="row p-2">
                Once the pregnancy report is positive, the cost will not come in the package
            </div>
            <div class="row p-2">
                In addition, if there is a rare complication during IVF treatment, you may have to go to another hospital for treatment, the cost of treatment will be borne by you.
            </div>
            <!-- <div class="row p-2">
                If you have to go to another hospital for treatment, the cost will be different.
            </div> -->
           
            <div class="row p-2 m-2">
                Remark:- {{$ivfPayment->remark}}
            </div>
            <div class="row p-2 m-2">
                Payment Condition:- {{$ivfPayment->condition}}
            </div>
            <div class="row p-2 m-2">              
                Patient's signature :- _______________________
            </div>
            <div class="row p-2">
                Signature of the patient's relative :- _______________________
            </div>
     @endif

    </div>
</div>



