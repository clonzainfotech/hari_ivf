<link rel="stylesheet" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/css/themes.css')}}">
<style type="text/css">
    .logo {
        text-align: center
    }
    .logo-image {
        width: 150px;
        height: 150px;
    }
    .addmission-form{
        border: 1px solid black;
        margin: 20px;
    }
    .admision-consent-form{
        /*margin: 20px;*/
    }
    .backside{
        margin: 20px;
    }
    .print-data {
    word-wrap: break-word;
    width: 100%;
    padding: 28px 28px;
    line-height: 32px;
    text-align: justify;
    }
    .title{
        border-bottom: 1px solid black;
    }
</style>
<div class="addmission-form">
<div class="admision-consent-form">
    <div class="pb-2 logo">
        {{-- <img src="{{url('images/candor-logo.jpg')}}" class="logo-image"> --}}
        <h5 class="font-weight-bold">{{config('app.hospitalname2')}}</h5>
    </div>
    @php
        $hAddress = hospitalAddress();
    @endphp
    <div class="hospital-address">{{$hAddress->address}}</div>
    <div class="indoor-patient-file">
        <span>Indoor Patient File</span>
    </div>

    <div class="patient-details">
        <div class="row">
            <div class="col-md-8">
                <div class="row form-group">
                    <div class="col-md-1">Name:</div>
                    <div class="col-md-11">
                        <div class="bottom-border">{{$patientData->name}}</div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-1">Age:</div>
                    <div class="col-md-5">
                        <div class="bottom-border">{{($patientData['age'] ? $patientData['age'].'/ Years ' : '').($patientData['months'] ? $patientData['months'].'/ months ' : '').($patientData['days'] ? $patientData['days'].'/ days ' : '')}}</div>
                    </div>
                    <div class="col-md-6">Gender: {{$patientData->gender == 2 ? 'F' : 'M'}}</div>
                </div>
                <div class="row form-group">
                    <div class="col-md-1">Add:</div>
                    <div class="col-md-11">
                        <div class="bottom-border">{{$patientData->residence.','.$patientData->main_area.','.$patientData->city}}</div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-1">Mo.:</div>
                    <div class="col-md-11">
                        <div class="bottom-border">{{$patientData->mobile_number}}</div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">Reference by:</div>
                    <div class="col-md-10">
                        <div class="bottom-border">{{$patientData->getReferenceDoctor['name']}}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <table class="table table-bordered indoor-details">
                    <tr>
                        <td>Indoor No.</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ward / Class</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Room No.</td>
                        <td>{{$room_id->getRoom['room_no']}}</td>
                    </tr>
                    <tr>
                        <td>Consultant</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-md-12">
                Mode of Payment : Cash / Cashless / Other
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-md-3">
                Mediclaim: Yes/No
            </div>
            <div class="col-md-6">
                Insurance Company : ___________________________
            </div>
            <div class="col-md-3">
                TPA : ____________________
            </div>
        </div>
        <div class="row pb-2">
            <table class="table table-bordered all-date-data">
                <tr>
                    <td>
                        <div class="p-2">Admission</div>
                        <div class="p-2">Date:&nbsp;&nbsp;&nbsp;<span>{{ Carbon\Carbon::parse($currentdate)->format('d-m-y') }}</span></div>
                        <div class="row p-2">
                            <div class="col-md-4">Time:&nbsp;&nbsp;&nbsp;<span>{{ Carbon\Carbon::now()->format('h:i') }}</span></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-right">{{ Carbon\Carbon::now()->format('A') }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="p-2">Discharge</div>
                        <div class="p-2">Date:</div>
                        <div class="row p-2">
                            <div class="col-md-4">Time:</div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-right">AM/PM</div>
                        </div>
                    </td>
                    <td>
                        <div class="p-2"> DOS /DOB.</div>
                        <div class="p-2">Date:</div>
                        <div class="row p-2">
                            <div class="col-md-4">Time:</div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-right">AM/PM</div>
                        </div>
                    </td>
                    <td>
                        <div class="p-2">DOR/Dama/Death</div>
                        <div class="p-2">Date:</div>
                        <div class="row p-2">
                            <div class="col-md-4">Time:</div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-right">AM/PM</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="table table-bordered diagnosis ml-3 mr-3">
                <tr>
                    <td class="">
                        <div>Provisional Diagnosis</div>
                    </td>
                    <td>
                        <div>Final Diagnosis</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row bed-number-info p-3">
            <div class="col-md-12 p-1">
                1. Admitted on __________________ at ________________ in ________________ Class on bed no.
            </div>
            <div class="col-md-12 p-1">
                1. Shifted on__________________ at ________________ in ________________ Class on bed no.
            </div>
            <div class="col-md-12 p-1">
                1. Shifted on __________________ at ________________ in ________________ Class on bed no.
            </div>
        </div>
        <div class="row p-2 pt-3">
            <div class="col-md-12">
                <h5>On Admission</h5>
            </div>
            <div class="col-md-12">
                Notified at Medico Legal case at: _________________________ Police Station to Shri: ___________________________
                Badge No.:_______________________ At ________________ On ________________
            </div>
        </div>
        <div class="row p-2 pt-3">
            <div class="col-md-12">
                <h5>On Discharge</h5>
            </div>
            <div class="col-md-12">
                Notified at Medico Legal case at: _________________________ Police Station to Shri: ___________________________
                Badge No.:_______________________ At ________________ On ________________
            </div>
        </div>
    </div>
</div>
</div>
<div class="addmission-form" style="margin-top: 400px;">
    <div class="backside">
<div class="text-center mt-5"><span class="title">AUTHORISATION FOR MEDICAL AND/ OR SURGICAL TRETMENT</span></div>

<div class="mt-3 print-data">હું __________________________________________ જાતે/મારા સ્વજન __________________________________________________________ ને  હોસ્પિટલના _______________________________ વોર્ડમાં દાખલ કરવાની વિનંતી કરું છું અને ચિકિત્સા કરવા માટે કોઈ પણ જાતની જરૂર લાગે તે તપાસ, મેડિકલ સારવાર, શસ્ત્રક્રિયા    (ઓપરેશન) તથા એનેસ્થેસિયા અને જરૂરી હોય તેવા દવાઓના બાટલા તથા લોહી આપવાની સંમતિ આપું છું. હું હોસ્પિટલના તમામ કાયદા કાનૂન નું પાલન કરવાની અને હોસ્પિટલમાં દ્વારા નક્કી કરેલ અગ્રીમ રકમ તથા હંગામી અને અંતિમ બિલ પૂર્ણપણે ભરવાની બાંહેધરી આપું છું.
</div>
<div class="mt-3 ml-2 row">
    <div class="col-md-9">
        <span>તારીખ:</span>
        <br>
        <br>
        <span>સમય:</span>
    </div>
    <div class="col-md-3">
        <div>
          <span class="text-center">હસ્તાક્ષર અને નામ :</span>  
          <br>
          <br>
          <span class="text-center"> __________________________</span>
        </div>
    </div>
</div>

<div class="text-center mt-5"><span class="title">DISCHARGE AGAINST MEDICAL ADVICEh</span></div>
<div class="mt-3 print-data">
હું ____________________________________________ જાતે/મારા સ્વજન _____________________________________________________ ને  હોસ્પિટલમાંથી દર્દીને વિનંતીથી/સારવાર કરનાર ડોક્ટર ની મરજી વિરુદ્ધ લઈ જાવ છું. આથી થનાર તકલીફ અને જોખમ વિશે સમજાવવામાં આવેલ છે. હું આ પરિસ્થિતિમાં હોસ્પિટલ છોડવાની સંપૂર્ણ જવાબદારી લઉં છું. અને ખાતરી આપું છું કે આના લીધે થનાર કોઈ પણ તકલીફ અથવા અનિચ્છનીય બનાવવાની જવાબદારી હોસ્પિટલ અથવા સારવાર કરનાર ડોક્ટર ની રહેશે નહીં.
</div>
<div class="mt-3 ml-2 row">
    <div class="col-md-9">
        <span>તારીખ:</span>
        <br>
        <br>
        <span>સમય:</span>
    </div>
    <div class="col-md-3">
        <div>
          <span class="text-center">હસ્તાક્ષર અને નામ :</span>  
          <br>
          <br>
          <span class="text-center"> __________________________ </span>
        </div>
    </div>
</div>

<div class="text-center mt-5"><span class="title">HIGH RISK CONSENT</span></div>
<div class="mt-3 print-data">
દર્દી ________________________________________________________ કે જેમને ____________________________________________________ બીમારી થયેલ છે. જેની બધી વિગતો અમને પૂરેપૂરી સમજાવેલ છે. દર્દીની સ્થિતિ અત્યંત નાજુક છે. અમને આ રોગના જોખમો સારવાર તથા તેના સંભવિત પરિણામ વિશે ની પૂરેપૂરી માહિતી આપવામાં આવેલી છે. સારવાર દરમિયાન દર્દીને જાન ના જોખમ જેવી ગંભીર તકલીફ પણ ઉદભવી શકે છે. ઉપરની બધી જ વિગતો સંપૂર્ણ સમજ્યા પછી અમે ડોક્ટર તેમજ તેમની ટીમને આગળની સારવાર કરવા માટેની સંમતિ આપીએ છીએ. કોઈપણ પ્રકારની ઇમરજન્સી સારવાર જેમ કે, એન્ડોટ્રેકીયલ ઇન્ટ્યુબેશન, કાર્ડિયોપલ્મોનરી રિસક્ષીટેશન વગેરે કરવા માટેની અમારી સંમતિ છે.
</div>
<div class="mt-3 ml-2 row">
    <div class="col-md-9">
        <span>તારીખ:</span>
        <br>
        <br>
        <span>સમય:</span>
    </div>
    <div class="col-md-3">
        <div>
          <span class="text-center">હસ્તાક્ષર અને નામ :</span>  
          <br>
          <br>
          <span class="text-center"> __________________________ </span>
        </div>
    </div>
</div>
</div>
</div>