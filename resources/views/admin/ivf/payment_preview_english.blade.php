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
    
</style>
<div class="main-print-ivf-div">
        <div class="panel panel-primary">
            <h3 class="text-center">{{config('app.hospitalname1')}}</h3>
            @php
            $yes = "yes";
            $consulation_status = $ivfPayment->consulation_status == $yes;
            $sonography_status = $ivfPayment->sonography_status == $yes;
            $ivf_lab_status = $ivfPayment->ivf_lab_status == $yes;
            $gonadotropins_status = $ivfPayment->gonadotropins_status == $yes;
            $icsi_ivf_status = $ivfPayment->icsi_ivf_status == $yes;
            $embryo_transfer_status = $ivfPayment->embryo_transfer_status == $yes;
            $embryo_freezing_status = $ivfPayment->embryo_freezing_status == $yes;
            $embryologist_charge_status = $ivfPayment->embryologist_charge_status == $yes;
            $surgeon_charge_status = $ivfPayment->surgeon_charge_status == $yes;
            $semen_freezing_status = $ivfPayment->semen_freezing_status == $yes;
            $hystrocopy_status = $ivfPayment->hystrocopy_status == $yes;
            $donor_charge_status = $ivfPayment->donor_charge_status == $yes;
            $medical_medicines_status = $ivfPayment->medical_medicines_status == $yes;
            $unconscious_charge_status = $ivfPayment->unconscious_charge_status == $yes;
            $anesthescis_doctor_status = $ivfPayment->anesthescis_doctor_status == $yes;
            $blood_report_status = $ivfPayment->blood_report_status == $yes;
            $tesa_pesa_status = $ivfPayment->tesa_pesa_status == $yes;
            $ovum_embryopooling_status = $ivfPayment->ovum_embryopooling_status == $yes;
            $TBPCR_status = $ivfPayment->TBPCR_status == $yes;
            $PAMP_status = $ivfPayment->PAMP_status == $yes;
            $ERA_status = $ivfPayment->ERA_status == $yes;

            $language = $ivfPayment->language;
            @endphp

            @if($language== "1")

            <div class="row">
                <div class="col-md-12 p-name-text">
                    હું <b>{{$ivfPayment->patient_name}}</b>  અને મારા પતિ <b>{{$ivfPayment->husband_name ? $ivfPayment->husband_name : '-'}}</b> IVF ટેસ્ટ ટ્યુબ બેબીની સારવાર રાધા હોસ્પિટલ ખાતે શરૂ કરવામાં આવેલ છે.
                </div>
            </div>
            <div class="row mt-5 p-2">
                <div class="col-md-12 p-name-text">
                    અમારી સારવારનો અંદાજિત ખર્ચ <b>{{$ivfPayment->package}}</b> સમજાવેલ છે.
                </div>
            </div>
            <div class="row p-2">
                <ul>
                    @if($consulation_status)
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> (અંદર થી)</li>
                    @else
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> (બહાર થી)</li>
                    @endif

                    @if($sonography_status)
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }}</b> (અંદર થી)</li>
                    @else
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }}</b> (બહાર થી)</li>
                    @endif

                    @if($ivf_lab_status)
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b> (અંદર થી)</li>
                    @else
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b> (બહાર થી)</li>
                    @endif

                    @if($gonadotropins_status)
                    <li>Gonadotropins Injection : <b>{{$ivfPayment->gonadotropins_injection }}</b> (અંદર થી) 
                        <ol>
                            1. HMG 
                        </ol>
                        <ol>
                            2. RFSH 
                        </ol>
                        <ol>
                            3. Gonal-F
                        </ol>

                    </li>
                    @else
                    <li>Gonadotropins Injection : <b>{{$ivfPayment->gonadotropins_injection}}</b> (બહાર થી)
                        <ol>
                            1. HMG 
                        </ol>
                        <ol>
                            2. RFSH 
                        </ol>
                        <ol>
                            3. Gonal-F
                        </ol></li>
                    @endif

                    @if($icsi_ivf_status)
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> (અંદર થી)</li>
                    @else
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> (બહાર થી)</li>
                    @endif

                    @if($embryo_transfer_status)
                    <li>Embryo Transfer (ગર્ભ મૂકવાનો ચાર્જ) : <b>{{$ivfPayment->embroy_tranfer}}</b> (અંદર થી)</li>
                    @else
                    <li>Embryo Transfer (ગર્ભ મૂકવાનો ચાર્જ) : <b>{{$ivfPayment->embroy_tranfer}}</b> (બહાર થી)</li>
                    @endif 

                    @if($embryo_freezing_status)
                    <li>Embryo Freezing (3 મહિના માટે) : <b>{{$ivfPayment->embryo_freezing }}</b> (અંદર થી)</li>
                    @else
                    <li>Embryo Freezing (3 મહિના માટે) : <b>{{$ivfPayment->embryo_freezing }}</b> (બહાર થી)</li>
                    @endif 

                    @if($embryologist_charge_status)
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> (અંદર થી)</li>
                    @else
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> (બહાર થી)</li>                   
                    @endif 

                    @if($surgeon_charge_status)
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> (અંદર થી)</li>
                    @else
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> (બહાર થી)</li>
                    @endif 

                    @if($semen_freezing_status)
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> (અંદર થી)</li>
                    @else
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> (બહાર થી)</li>
                    @endif 

                    @if($hystrocopy_status)
                    <li>HYSTROSCOPY (ગર્ભશાયની દુરબીનની તપાસ અંદાજે 12000/- થી 15000/- ) :<b> {{$ivfPayment->hystrocopy }}</b> (અંદર થી)</li>
                    @else
                    <li>HYSTROSCOPY (ગર્ભશાયની દુરબીનની તપાસ અંદાજે 12000/- થી 15000/- ) :<b> {{$ivfPayment->hystrocopy }}</b> (બહાર થી)</li>
                    @endif 
                       
                    @if($donor_charge_status && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                        જો સ્ત્રી બીજ અંદર થીથી લેવાના થાય તો : <b>{{$ivfPayment->donor_charge}}</b> (અંદર થી) 
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
                    @else
                      <li>
                        If the female seed is to be taken from outside: <b>{{$ivfPayment->donor_charge}}</b> (બહાર થી) 
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

                    @if($medical_medicines_status)
                    <li>મેડીકલની દવા (અંદાજિત  10,000/- થી 15000/-) : <b>{{$ivfPayment->medical_medicines }}</b> (અંદર થી)</li>
                    @else
                    <li>મેડીકલની દવા (અંદાજિત  10,000/- થી 15000/-) : <b>{{$ivfPayment->medical_medicines }}</b> (બહાર થી)</li>
                    @endif 

                    @if($unconscious_charge_status)
                    <li>anaesthetist Charge (Approx 10,000 to 15,000/-) : <b>{{$ivfPayment->unconscious_charge }}</b> (અંદર થી)</li>
                    @else
                    <li>anaesthetist Charge (Approx 10,000 to 15,000/-) : <b>{{$ivfPayment->unconscious_charge }}</b> (બહાર થી)</li>
                    @endif 

                    @if($anesthescis_doctor_status)
                    <li>બેભાન કરવાના ડોક્ટર નો ચાર્જ (અંદાજે-2000/-) : <b>{{$ivfPayment->anesthescis_doctor }}</b> (અંદર થી)</li>
                    @else
                    <li>બેભાન કરવાના ડોક્ટર નો ચાર્જ (અંદાજે-2000/-) : <b>{{$ivfPayment->anesthescis_doctor }}</b> (બહાર થી)</li>
                    @endif 

                    @if($blood_report_status)
                    <li>લોહી ના રિપોર્ટ(અંદાજિત– 2000/- થી 3000/-) : <b>{{$ivfPayment->blood_report }}</b> (અંદર થી)</li>
                    @else
                    <li>લોહી ના રિપોર્ટ(અંદાજિત– 2000/- થી 3000/-) : <b>{{$ivfPayment->blood_report }}</b> (બહાર થી)</li>
                    @endif 

                    @if($tesa_pesa_status)
                    <li>TESA / PESA (અંદાજિત – 10,000/-) : <b>{{$ivfPayment->tesa_pesa }}</b> (અંદર થી)</li>
                    @else
                    <li>TESA / PESA (અંદાજિત – 10,000/-) : <b>{{$ivfPayment->tesa_pesa }}</b> (બહાર થી)</li>
                    @endif 

                    @if($ovum_embryopooling_status)
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> (અંદર થી)</li>
                    @else
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> (બહાર થી)</li>
                    @endif 

                    <li>Endomatrial Biopsy Report :
                        @if($TBPCR_status)
                        <ol>
                            1. TBPCR  (અંદાજિત– 2000/-): <b>{{$ivfPayment->TBPCR }}</b> (અંદર થી)
                        </ol>
                        @else
                        <ol>
                            1. TBPCR  (અંદાજિત– 2000/-): <b>{{$ivfPayment->TBPCR }}</b> (બહાર થી)
                        </ol>
                        @endif 

                        @if($PAMP_status)
                        <ol>
                            2. PAMP  (અંદાજિત– 4200/-): <b>{{$ivfPayment->PAMP }}</b> (અંદર થી)
                        </ol>
                        @else
                        <ol>
                            2. PAMP  (અંદાજિત– 4200/-): <b>{{$ivfPayment->PAMP }}</b> (બહાર થી)
                        </ol>
                        @endif 

                        @if($ERA_status)
                        <ol>
                            3. ERA  (અંદાજિત– 40000/-): <b>{{$ivfPayment->ERA}}</b> (અંદર થી)
                        </ol>
                        @else
                        <ol>
                            3. ERA  (અંદાજિત– 40000/-): <b>{{$ivfPayment->ERA}}</b> (બહાર થી)
                        </ol>
                        @endif 

                </ul>
                
            </div>
             <div class="row p-2">
                બીજી વખત ગર્ભ (જો મૂકવાના થાય તો) મૂકવાનો ખર્ચ  અલગથી થસે
            </div>
            <div class="row p-2">
                એકવાર પ્રેગ્નેન્સી રીપોર્ટ પોજીટીવ આવ્યા પછી નો ખર્ચ પેકેજ માં આવશે નહિ 
            </div>
            <div class="row p-2">
                આ ઉપરાંત IVF સારવાર દરમિયાન કોઈ ભાગ્યે જ complication આવે તો એનો ખર્ચ અને 
            </div>
            <div class="row p-2">
                જો બીજી કોઈ હોસ્પિટલ માં સારવાર કરવાની થાય તો ત્યાં નો ખર્ચ અલગથી રહેશે.
            </div>
            
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
            </div>

            <div class="row ">
                 <div class="col-md-12 p-name-text">
        
                    हमारे उपचार की अनुमानित लागत <b>{{$ivfPayment->package}}</b> है।
                </div>
            </div>
            <div class="row p-2">
                <ul>
                    @if($consulation_status)
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> (अंदर से)</li>
                    @else
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> (बाहर से)</li>
                    @endif

                    @if($sonography_status)
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }}</b> (अंदर से)</li>
                    @else
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge }}</b> (बाहर से)</li>
                    @endif

                    @if($ivf_lab_status)
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge }}</b> (अंदर से)</li>
                    @else
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge }}</b> (बाहर से)</li>
                    @endif

                    @if($gonadotropins_status)
                    <li>Gonadotropins Injection : <b>{{$ivfPayment->gonadotropins_injection}}</b> (अंदर से) 
                        <ol>
                            1. HMG 
                        </ol>
                        <ol>
                            2. RFSH 
                        </ol>
                        <ol>
                            3. Gonal-F
                        </ol>

                    </li>
                    @else
                    <li>Gonadotropins Injection : <b>{{$ivfPayment->gonadotropins_injection}}</b> (बाहर से)
                        <ol>
                            1. HMG 
                        </ol>
                        <ol>
                            2. RFSH 
                        </ol>
                        <ol>
                            3. Gonal-F
                        </ol></li>
                    @endif

                    @if($icsi_ivf_status)
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> (अंदर से)</li>
                    @else
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> (बाहर से)</li>
                    @endif

                    @if($embryo_transfer_status)
                    <li>Embryo Transfer (Fetal placement charge) : <b>{{$ivfPayment->embroy_tranfer}}</b> (अंदर से)</li>
                    @else
                    <li>Embryo Transfer (Fetal placement charge) : <b>{{$ivfPayment->embroy_tranfer}}</b> (बाहर से)</li>
                    @endif 

                    @if($embryo_freezing_status)
                    <li>Embryo Freezing (3 महीने के लिए) : <b>{{$ivfPayment->embryo_freezing }}</b> (अंदर से)</li>
                    @else
                    <li>Embryo Freezing (3 महीने के लिए) : <b>{{$ivfPayment->embryo_freezing }}</b> (बाहर से)</li>
                    @endif 

                    @if($embryologist_charge_status)
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> (अंदर से)</li>
                    @else
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> (बाहर से)</li>                   
                    @endif 

                    @if($surgeon_charge_status)
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> (अंदर से)</li>
                    @else
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> (बाहर से)</li>
                    @endif 

                    @if($semen_freezing_status)
                    <li>Semen Freezing Charge (अनुमानित 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> (अंदर से)</li>
                    @else
                    <li>Semen Freezing Charge (अनुमानित 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> (बाहर से)</li>
                    @endif 

                    @if($hystrocopy_status)
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy }}</b> (अंदर से)</li>
                    @else
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy }}</b> (बाहर से)</li>
                    @endif 


                    @if($donor_charge_status && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                         यदि मादा बीज अंदर से लिया जाना है:  <b>{{$ivfPayment->donor_charge}}</b> (अंदर से) 
                        <ol>
                             1. दाता बीज
                        </ol>
                        <ol>
                            2. डोनर ब्लड रिपोर्ट
                        </ol>
                        <ol>
                            3. डोनर कम्यूटिंग कॉस्ट 
                        </ol>
                        <ol>
                            4. बीज बनाने के लिए दाता को दिया गया इंजेक्शन चार्ज = 25,000 / - से 45,000 / -
                        </ol>
                    </li>
                    @else
                      <li>
                         यदि मादा बीज अंदर से लिया जाना है:  <b>{{$ivfPayment->donor_charge}}</b> (बाहर से) 
                        <ol>
                             1. दाता बीज
                        </ol>
                        <ol>
                            2. डोनर ब्लड रिपोर्ट
                        </ol>
                        <ol>
                            3. डोनर कम्यूटिंग कॉस्ट 
                        </ol>
                        <ol>
                            4. बीज बनाने के लिए दाता को दिया गया इंजेक्शन चार्ज = 25,000 / - से 45,000 / -
                        </ol>
                    </li>
                    @endif 

                    @if($medical_medicines_status)
                    <li>Medical medicine (अनुमानित 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines }}</b> (अंदर से)</li>
                    @else
                    <li>Medical medicine (अनुमानित 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines }}</b> (बाहर से)</li>
                    @endif 

                    @if($unconscious_charge_status)
                    <li>एनेस्थेटिस्ट चार्ज (Approx 10,000 to 15,000/-) : <b>{{$ivfPayment->unconscious_charge }}</b> (अंदर से)</li>
                    @else
                    <li>एनेस्थेटिस्ट चार्ज (Approx 10,000 to 15,000/-) : <b>{{$ivfPayment->unconscious_charge }}</b> (बाहर से)</li>
                    @endif 

                    @if($anesthescis_doctor_status)
                    <li>एनेस्थीसिया के लिए डॉक्टर का चार्ज (approximately -2000 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> (अंदर से)</li>
                    @else
                    <li>एनेस्थीसिया के लिए डॉक्टर का चार्ज (approximately -2000 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> (बाहर से)</li>
                    @endif 

                    @if($blood_report_status)
                    <li> (अनुमानित - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report }}</b> (अंदर से)</li>
                    @else
                    <li>रक्त की रिपोर्ट (अनुमानित - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report }}</b> (बाहर से)</li>
                    @endif 

                    @if($tesa_pesa_status)
                    <li>TESA / PESA (अनुमानित - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> (अंदर से)</li>
                    @else
                    <li>TESA / PESA (अनुमानित - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> (बाहर से)</li>
                    @endif 

                    @if($ovum_embryopooling_status)
                    <li>Ovum Embryo Pooling (अनुमानित 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> (अंदर से)</li>
                    @else
                    <li>Ovum Embryo Pooling (अनुमानित 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> (बाहर से)</li>
                    @endif 

                    <li>Endomatrial Biopsy Report :
                        @if($TBPCR_status)
                        <ol>
                            1. TBPCR  (अनुमानित 2000/-): <b>{{$ivfPayment->TBPCR }}</b> (अंदर से)
                        </ol>
                        @else
                        <ol>
                            1. TBPCR  (अनुमानित 2000/-): <b>{{$ivfPayment->TBPCR }}</b> (बाहर से)
                        </ol>
                        @endif 

                        @if($PAMP_status)
                        <ol>
                            2. PAMP  (अनुमानित 4200/-): <b>{{$ivfPayment->PAMP }}</b> (अंदर से)
                        </ol>
                        @else
                        <ol>
                            2. PAMP  (अनुमानित 4200/-): <b>{{$ivfPayment->PAMP }}</b> (बाहर से)
                        </ol>
                        @endif 

                        @if($ERA_status)
                        <ol>
                            3. ERA  (अनुमानित 40000/-): <b>{{$ivfPayment->ERA}}</b> (अंदर से)
                        </ol>
                        @else
                        <ol>
                            3. ERA  (अनुमानित 40000/-): <b>{{$ivfPayment->ERA}}</b> (बाहर से)
                        </ol>
                        @endif 
                    </li>
                </ul>
                
            </div>
            <div class="row p-2">
               दूसरा भ्रूण (यदि रखा गया है) रखने की लागत अलग है
            </div>
            <div class="row p-2">
               एक बार गर्भावस्था रिपोर्ट सकारात्मक होने के बाद, पैकेज में लागत नहीं आएगी
            </div>
            <div class="row p-2">
                इसके अलावा, अगर आईवीएफ उपचार, लागत और के दौरान एक दुर्लभ जटिलता है 
            </div>
            <div class="row p-2">
                यदि आपको उपचार के लिए दूसरे अस्पताल जाना है, तो लागत अलग होगी।
            </div>

            <div class="row p-2 m-2">
                Remark:- {{$ivfPayment->remark}}
            </div>
            <div class="row p-2 m-2">
                Patment Condition:- {{$ivfPayment->condition}}
            </div>
       
            <div class="row p-2 m-2">              
                रोगी के हस्ताक्षर :- _______________________
            </div>
            <div class="row p-2">
                रोगी के रिश्तेदार का हस्ताक्षर :- _______________________
            </div>

    @elseif($language== "3")
            <div class="row mt-5 p-2">
                <div class="col-md-12 p-name-text">
                    I <b>{{$ivfPayment->patient_name}}</b>  And my husband<b>{{$ivfPayment->husband_name ? $ivfPayment->husband_name : '-'}}</b> Treatment of IVF test tube baby has been started at Radha Hospital.
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 p-name-text">
                    Estimated cost of our treatment <b>{{$ivfPayment->package}}</b> Explained.
                </div>
            </div>
            <div class="row p-2">
                <ul>
                    @if($consulation_status)
                    <li>Consulation : <b>{{$ivfPayment->consulation }}</b> (Include)</li>
                    @else
                    <li>Consulation : <b>{{$ivfPayment->consulation}}</b> (Not Include)</li>
                    @endif

                    @if($sonography_status)
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge}}</b> (Include)</li>
                    @else
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge}}</b> (Not Include)</li>
                    @endif

                    @if($ivf_lab_status)
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b> (Include)</li>
                    @else
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge}}</b> (Not Include)</li>
                    @endif

                    @if($gonadotropins_status)
                    <li>Gonadotropins Injection : <b>{{$ivfPayment->gonadotropins_injection}}</b> (Include) 
                        <ol>
                            1. HMG 
                        </ol>
                        <ol>
                            2. RFSH 
                        </ol>
                        <ol>
                            3. Gonal-F
                        </ol>

                    </li>
                    @else
                    <li>Gonadotropins Injection : <b>{{$ivfPayment->gonadotropins_injection}}</b> (Not Include)
                        <ol>
                            1. HMG 
                        </ol>
                        <ol>
                            2. RFSH 
                        </ol>
                        <ol>
                            3. Gonal-F
                        </ol></li>
                    @endif

                    @if($icsi_ivf_status)
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> (Include)</li>
                    @else
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge}}</b> (Not Include)</li>
                    @endif

                    @if($embryo_transfer_status)
                    <li>Embryo Transfer (Fetal placement charge) : <b>{{$ivfPayment->embroy_tranfer}}</b> (Include)</li>
                    @else
                    <li>Embryo Transfer (Fetal placement charge) : <b>{{$ivfPayment->embroy_tranfer}}</b> (Not Include)</li>
                    @endif 

                    @if($embryo_freezing_status)
                    <li>Embryo Freezing (For 3 months) : <b>{{$ivfPayment->embryo_freezing }}</b> (Include)</li>
                    @else
                    <li>Embryo Freezing (For 3 months) : <b>{{$ivfPayment->embryo_freezing }}</b> (Not Include)</li>
                    @endif 

                    @if($embryologist_charge_status)
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> (Include)</li>
                    @else
                    <li>Embryologist Charge : <b>{{$ivfPayment->embryologist_charge }}</b> (Not Include)</li>                   
                    @endif 

                    @if($surgeon_charge_status)
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> (Include)</li>
                    @else
                    <li>Surgeon Charge (Pick Up & Transfer) : <b>{{$ivfPayment->surgeon_charge }}</b> (Not Include)</li>
                    @endif 

                    @if($semen_freezing_status)
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> (Include)</li>
                    @else
                    <li>Semen Freezing Charge (Approx 2000/-) : <b>{{$ivfPayment->semen_freezing_charge }}</b> (Not Include)</li>
                    @endif 

                    @if($hystrocopy_status)
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy }}</b> (Include)</li>
                    @else
                    <li>HYSTROSCOPY (Examination of uterine telescope approximately 12000 / - to 15000 / -):<b> {{$ivfPayment->hystrocopy }}</b> (Not Include)</li>
                    @endif 

                    @if($donor_charge_status && ($ivfPayment->cycle_type != 'SELF'))
                    <li>
                        If the female seed is to be taken from outside: <b>{{$ivfPayment->donor_charge}}</b> (Include) 
                        <ol>
                            1. Donor seeds
                        </ol>
                        <ol>
                            2. Donor Blood Report
                        </ol>
                        <ol>
                            3. Donor commuting costs
                        </ol>
                        <ol>
                            4. Injection charge given to the donor for seed making = 25,000 / - to 45,000 / -
                        </ol>
                    </li>
                    @else
                      <li>
                        If the female seed is to be taken from outside: <b>{{$ivfPayment->donor_charge}}</b> (Not Include) 
                        <ol>
                            1. Donor seeds
                        </ol>
                        <ol>
                            2. Donor Blood Report
                        </ol>
                        <ol>
                            3. Donor commuting costs
                        </ol>
                        <ol>
                            4. Injection charge given to the donor for seed making = 25,000 / - to 45,000 / -
                        </ol>
                    </li>
                    @endif 

                    @if($medical_medicines_status)
                    <li>Medical medicine (estimated 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines }}</b> (Include)</li>
                    @else
                    <li>Medical medicine (estimated 10,000 / - to 15000 / -): <b>{{$ivfPayment->medical_medicines }}</b> (Not Include)</li>
                    @endif 

                    @if($unconscious_charge_status)
                    <li>anaesthetist Charge (Approx 10,000 to 15,000/-) : <b>{{$ivfPayment->unconscious_charge }}</b> (Include)</li>
                    @else
                    <li>anaesthetist Charge (Approx 10,000 to 15,000/-) : <b>{{$ivfPayment->unconscious_charge }}</b> (Not Include)</li>
                    @endif 

                    @if($anesthescis_doctor_status)
                    <li>Doctor's charge for anesthesia (approximately -2000 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> (Include)</li>
                    @else
                    <li>Doctor's charge for anesthesia (approximately -2000 / -): <b>{{$ivfPayment->anesthescis_doctor }}</b> (Not Include)</li>
                    @endif 

                    @if($blood_report_status)
                    <li>Blood Report (Estimated - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report }}</b> (Include)</li>
                    @else
                    <li>Blood Report (Estimated - 2000 / - to 3000 / -): <b>{{$ivfPayment->blood_report }}</b> (Not Include)</li>
                    @endif 

                    @if($tesa_pesa_status)
                    <li>TESA / PESA (Estimated - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> (Include)</li>
                    @else
                    <li>TESA / PESA (Estimated - 10,000 / -): <b>{{$ivfPayment->tesa_pesa }}</b> (Not Include)</li>
                    @endif 

                    @if($ovum_embryopooling_status)
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> (Include)</li>
                    @else
                    <li>Ovum Embryo Pooling (Approx 60,000/-): <b>{{$ivfPayment->ovum_embryopooling }}</b> (Not Include)</li>
                    @endif 

                    <li>Endomatrial Biopsy Report :
                        @if($TBPCR_status)
                        <ol>
                            1. TBPCR  (Approx 2000/-): <b>{{$ivfPayment->TBPCR }}</b> (Include)
                        </ol>
                        @else
                        <ol>
                            1. TBPCR  (Approx 2000/-): <b>{{$ivfPayment->TBPCR }}</b> (Not Include)
                        </ol>
                        @endif 

                        @if($PAMP_status)
                        <ol>
                            2. PAMP  (Approx 4200/-): <b>{{$ivfPayment->PAMP }}</b> (Include)
                        </ol>
                        @else
                        <ol>
                            2. PAMP  (Approx 4200/-): <b>{{$ivfPayment->PAMP }}</b> (Not Include)
                        </ol>
                        @endif 

                        @if($ERA_status)
                        <ol>
                            3. ERA  (Approx 40000/-): <b>{{$ivfPayment->ERA}}</b> (Include)
                        </ol>
                        @else
                        <ol>
                            3. ERA  (Approx 40000/-): <b>{{$ivfPayment->ERA}}</b> (Not Include)
                        </ol>
                        @endif 
                    </li>
                </ul>
                
            </div>
            <div class="row p-2">
               The cost of placing the second embryo (if placed) is separate
            </div>
            <div class="row p-2">
                Once the pregnancy report is positive, the cost will not come in the package
            </div>
            <div class="row p-2">
                In addition, if there is a rare complication during IVF treatment, the cost and 
            </div>
            <div class="row p-2">
                If you have to go to another hospital for treatment, the cost will be different.
            </div>
           
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