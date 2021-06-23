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

</style>
<div class="main-print-ivf-div">
        <div class="panel panel-primary">
            <h3 class="text-center">{{config('app.hospitalname1')}}</h3>

            <div class="row mt-5 p-2">
                <div class="col-md-12 p-name-text">
                    હું <b>{{$ivfPayment->patient_name}}</b>  અને મારા પતિ <b>{{$ivfPayment->husband_name ? $ivfPayment->husband_name : '-'}}</b> IVF ટેસ્ટ ટ્યુબ બેબીની સારવાર રાધા હોસ્પિટલ ખાતે શરૂ કરવામાં આવેલ છે.
                    <br>
                    <br>
                    અમારી સારવારનો અંદાજિત ખર્ચ <b>{{$ivfPayment->package}}</b> સમજાવેલ છે.
                </div>
            </div>
            <div class="row p-2">
                <ul>
                    <li>Sonography : <b>{{$ivfPayment->sonography_charge ? $ivfPayment->sonography_charge : 0}}</b></li>
                    <li>IVF Lab Charge : <b>{{$ivfPayment->ivf_lab_charge ? $ivfPayment->ivf_lab_charge : 0}}</b></li>
                    <li>ICSI - IVF : <b>{{$ivfPayment->icsi_ivf_charge ? $ivfPayment->icsi_ivf_charge : 0}}</b></li>
                    <li>Embryo Transfer (ગર્ભ મૂકવાનો ચાર્જ) : <b>{{$ivfPayment->icsi_ivf_charge ? $ivfPayment->icsi_ivf_charge : 0}}</b></li>
                    <li>Embryo Freezing (6 મહિના માટે) : <b>{{$ivfPayment->embryo_freezing ? $ivfPayment->embryo_freezing : 0}}</b></li>
                    <li>HYSTROSCOPY (ગર્ભશાયની દુરબીનની તપાસ અંદાજે 12000/- થી 15000/- ) : {{$ivfPayment->hystrocopy ? $ivfPayment->hystrocopy : 0}}</li>
                    <li>
                        જો સ્ત્રી બીજ બહારથી લેવાના થાય તો : {{$ivfPayment->donor_charge}}
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
                    <li>મેડીકલની દવા (અંદાજિત  10,000/- થી 15000/-) : {{$ivfPayment->medical_medicines ? $ivfPayment->medical_medicines : 0}}</li>
                    <li>બેભાન કરવાના ડોક્ટર નો ચાર્જ (અંદાજે-2000/-) : {{$ivfPayment->unconscious_charge ? $ivfPayment->unconscious_charge : 0}}</li>
                    <li>લોહી ના રિપોર્ટ(અંદાજિત– 2000/- થી 3000/-) : {{$ivfPayment->blood_report ? $ivfPayment->blood_report : 0}}</li>
                    <li>TESA / PESA (અંદાજિત – 10,000/-) : {{$ivfPayment->tesa_pesa ? $ivfPayment->tesa_pesa : 0}}</li>
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
            <br>
            <div class="row p-2">
                Remark:- {{$ivfPayment->remark}}
            </div>
            <div class="row p-2">
                Payment Condition:- {{$ivfPayment->condition}}
            </div>
            <br>
            <div class="row p-2">
                દર્દી ની સહી :- _______________________
            </div>
            <div class="row p-2">
                દર્દી ના સગા ની સહી :- _______________________
            </div>
        	{{-- <table cellspacing="0" cellpadding="0" class="table m-b-0 table-hover module-report-table">
                <tbody>
                    <tr>

                    	<th class="seperator">
                    		Payment: &nbsp; <span class="font">{{$ivfPayment->payment}}</span>
                    	</th>
                    </tr>
                    <tr>
                    	<th class="seperator">
                    		Payment Type: &nbsp; <span class="font">{{$ivfPayment->payment_type == 1 ? 'Card' : 'Cash'}}</span>
                    	</th>
                    </tr>
                    <tr>
                    	<th class="seperator">
                    		Cycle No: &nbsp; <span class="font">{{$ivfPayment->cycle_no}}</span>
                    	</th>
                    </tr>
                    <tr>
                    	<th class="seperator">
                    		Time: &nbsp; <span class="font">{{$ivfPayment->remark}}</span>
                    	</th>
                    </tr>
                    <tr>
                    	<th class="seperator">
                    		Condition : &nbsp; <span class="font">{{$ivfPayment->condition}}</span>
                    	</th>
                    </tr>
                    <tr>
                    	<th class="seperator">
                    		Remark: &nbsp; <span class="font">{{$ivfPayment->remark}}</span>
                    	</th>
                    </tr>
                    <tr>
                    	<th class="seperator">
                    		Package: &nbsp; <span class="font">{{$ivfPayment->package}}</span>
                    	</th>
                    </tr>
                </tbody>
            </table> --}}
        </div>
</div>
