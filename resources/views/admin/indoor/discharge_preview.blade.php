<link rel="stylesheet" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/css/themes.css')}}">
<style type="text/css">
    .print-discharge-table, .discharge-header, .invoice-receipt, .discharge-data {
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
        margin-top: 40px;
    }
    .discharge-header,.discharge-data{
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

    .discharge-data {
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
    .discharge-card{
        border: 1px solid black;
        margin: 20px;
    }
    .card-data{
        padding: 6px !important;
    }
    .main-title{
      justify-content: center !important;
      font-weight: bolder !important;
    }
   .hospital-address{
      justify-content: center !important;
    }
   .sub-title{
      justify-content: center !important;
   }
   hr{
      border-top: 1px solid rgba(0, 0, 0, 0.97) !important;
   }
   .p-name{
      font-size: 20px !important;
   }
   @media screen {
      div.divFooter {
         display: none;
      }
   }
@media print {
  div.divFooter {
    position: fixed;
    bottom: 110px;
    margin-left: 14%;
  }
}
</style>
{{-- <br>
<br>
<br>
<br>
<div class="discharge-card">
   <div class="row main-title">
      <div class="card-data">
         <h4 class="font-weight-bold">{{strtoupper(config('app.hospitalname2'))}}</h4>
      </div>
   </div>
   <div class="row hospital-address">
      <div class="card-data">1st & 2nd Floor, Tapibag Shopping Center -1, Nr. hans Soc., Mini Bazar, Varachha Road, Surat.</div>
   </div>
</div> --}}
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="discharge-card">
   <div class="discharge-card-data">
      <div class="row sub-title">
         <div class="card-data">
            <h5 class="font-weight-bold m-0">DISCHARGE SUMMARY/CARD</h5>
         </div>
      </div>
      <hr class="m-0">
      <div class="row">
         <div class="col-md-6 card-data">
            <span class="col-md-5">Patients Name :
               <span class="value font-weight-bold p-name">{{ucwords(strtolower($discharge->getIndoorBook->getPatientsDetails['name']))}}</span>
            </span>
         </div>
         <div class="col-md-6 card-data">
            <span class="col-md-2">Age / Gender:
               <span class="value font-weight-bold">{{($discharge->getIndoorBook->getPatientsDetails['age'] ? $discharge->getIndoorBook->getPatientsDetails['age'].'/ Years ' : '').($discharge->getIndoorBook->getPatientsDetails['months'] ? $discharge->getIndoorBook->getPatientsDetails['months'].'/ months ' : '').($discharge->getIndoorBook->getPatientsDetails['days'] ? $discharge->getIndoorBook->getPatientsDetails['days'].'/ days ' : '')}}</span>
            </span>
            <span class="value font-weight-bold">{{$discharge->getIndoorBook->getPatientsDetails['gender'] == 1 ? 'Male' : 'Female'}}</span>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6 card-data">
            <span class="col-md-2">Ward:
               <span class="value font-weight-bold">{{$discharge->getIndoorBook->getRoom['room_no']}}</span>
            </span>
         </div>
         <div class="col-md-6 card-data">
            <span class="col-md-2">Patients Code:
               <span class="value font-weight-bold">{{$discharge->getIndoorBook->getPatientsDetails['code']}}</span>
            </span>
         </div>
      </div>
      <div class="row">
         <div class="col-md-4 card-data">
            <span class="col-md-2">DOA :
               <span class="value font-weight-bold">{{carbon\carbon::parse($discharge->getIndoorBook['doa_date'])->format('d-m-Y')}}</span>
            </span>
         </div>
         <div class="col-md-2 card-data">
            <span class="col-md-2">TOA :
               <span class="value font-weight-bold">{{$discharge->getIndoorBook['admit_time'] ? carbon\carbon::parse($discharge->getIndoorBook['admit_time'])->format('h:i a') : '-'}}</span>
            </span>
         </div>
         <div class="col-md-4 card-data">
            <span class="col-md-2">DOD :
               <span class="value font-weight-bold">{{carbon\carbon::parse($discharge->getIndoorBook->dod_date)->format('d-m-Y')}}</span>
            </span>
         </div>
         <div class="col-md-2 card-data">
            <span class="col-md-2">TOD :
               <span class="value font-weight-bold">{{$discharge->getIndoorBook['discharge_time'] ? carbon\carbon::parse($discharge->getIndoorBook['discharge_time'])->format('h:i a') : '-'}}</span>
            </span>
         </div>
      </div>
      <hr>
      <div class="row card-data">
         <span class="col-md-2">Diagnosis : </span>
         <div class="col-md-10 value font-weight-bold">
            {!! str_replace(',', '<br />', $discharge->diagnosis) !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Summary : </span>
         <div class="col-md-10 value">
            {!! str_replace(',', '<br />', $discharge->summary) !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Tratment Given : </span>
         <div class="col-md-10 value font-weight-bold">
            {!! str_replace(',', '<br />', $discharge->treatment_given) !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Surgical Note : </span>
         <div class="col-md-10 value">
            {!! str_replace(',', '<br />', $discharge->surgical_note ? $discharge->surgical_note : 'Not Applicable') !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Complaint : </span>
         <div class="col-md-10 value">
            {!! str_replace(',', '<br />', $discharge->complaints) !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Rx(Treatment) : </span>
         <div class="col-md-10 value font-weight-bold">
            @php
               
               $treatment = json_decode($discharge->rx_treatment);
               unset($treatment->medicinedata);
            @endphp
            @if(!empty($treatment))
                   <table class="medicine-table" style="width:100%">
                       <thead>
                           <tr>
                               <th>Name</th>
                               <th>Dose</th>
                               <th>Timing</th>
                               <th>Freq.</th>
                               <th>Duration</th>
                               <th>Note</th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($treatment as $key=>$row)
                           <tr>
                               <?php
                                   $medicine_status = '';
                                   $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                   $firstCharacter = strtoupper(substr($mId, 0, 3));
                                   if($firstCharacter == "INJ"){
                                       if(!empty($row->medicine_time))
                                       {
                                           switch($row->medicine_time){
                                               case '1':
                                                   $medicine_status = 'IV';
                                                   break;
                                               case '2':
                                                   $medicine_status = 'IM';
                                                   break;
                                               case '3':
                                                   $medicine_status = 'SC';
                                                   break;
                                               case '4':
                                                   $medicine_status = 'Oral';
                                                   break;
                                               case '5':
                                                   $medicine_status = 'P/V';
                                                   break;
                                               case '6':
                                                   $medicine_status = 'P/A';
                                                   break;
                                           }
                                       }
                                       $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                       if($mData==$medicine_status) {
                                           $medicine_status = "-";
                                       }
                                   }else{
                                       $mData = [0,0,0,0];

                                       if(@$row->quantity>0) {
                                           $mData[0] = $row->quantity;
                                       }
                                       if(@$row->quantity_2>0) {
                                           $mData[1] = $row->quantity_2;
                                       }
                                       if(@$row->quantity_3>0) {
                                           $mData[2] = $row->quantity_3;
                                       }
                                       if(@$row->quantity_4>0) {
                                           $mData[3] = $row->quantity_4;
                                       }
                                       $mData = implode('-',$mData);
                                       switch($row->medicine_status){
                                           case '1':
                                               $medicine_status = 'જમ્યા પછી';
                                               break;
                                           case '2':
                                               $medicine_status = 'જમ્યા પહેલાં';
                                               break;
                                           case '3':
                                               $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                               break;
                                       }
                                   }
                               ?>
                               <td>{{$row->medicine}}</td>
                               <td>{{$mData}}</td>
                               <td>{{$medicine_status}}</td>
                               <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                               <td>{{(!empty($row->no)) ? $row->no.' days' : ''}}</td>
                               <td>{{isset($row->note) && !empty($row->note) ? $row->note : '-'}}</td>

                           </tr>
                       @endforeach
                       </tbody>
                   </table>
               @endif
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Admission Vitals : </span>
         <div class="col-md-10 value font-weight-bold">
            {!! str_replace(',', '<br />', $discharge->admission_vitals) !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Systemic Examination : </span>
         <div class="col-md-10 value">
            {{$discharge->examination}}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Clinical Summary : </span>
         <div class="col-md-10 value font-weight-bold">
            {!! str_replace(',', '<br />', $discharge->clinical_summary) !!}
         </div>
      </div>
      <div class="row card-data">
         <span class="col-md-2">Vital on Discharge : </span>
         <div class="col-md-10 value font-weight-bold">
            {{$discharge->vital_on_discharge}}
         </div>
      </div>
      <hr>
      <div class="row card-data">
         <span class="col-md-2">Condition on Discharge : </span>
         <div class="col-md-10 value font-weight-bold">
            {!! str_replace(',', '<br />', $discharge->cond_on_discharge) !!}
         </div>
      </div>
      <hr>
      <div class="row card-data">
         <span class="col-md-2 font-weight-bold">Follow Up : </span> 
         <div class="col-md-10 font-weight-bold">{{$discharge->follow_up}}</div>
      </div>
      <div class="row card-data">
         <span class="col-md-2 font-weight-bold">Follow Up Date :</span>
         <div class="col-md-10 font-weight-bold">{{$discharge->followup_date ? carbon\carbon::parse($discharge->followup_date)->format('d-m-Y') : null}}</div>
      </div>
      <div class="row card-data">
         <div class="col-md-6"></div>
         <span class="col-md-2 mt-2 font-weight-bold">Signature</span>
      </div>
   </div>
</div>
<div class="divFooter">For any emergency, you can call on <b>9558981800</b> or you can visit the hospital.</div>
{{-- <table id="print-discharge-table" class="print-discharge-table">
    <tbody>
        <tr>
           <td>Patient Name :</td>
            <td style="text-transform: capitalize">{{strtolower($discharge->getIndoorBook->getPatientsDetails['name'])}}</td>
        </tr>
        <tr>
            <td>Room Type :</td>
            <td style="text-transform: capitalize">{{!empty($discharge->getIndoorBook->getRoomType['name']) ? $discharge->getIndoorBook->getRoomType['name'] : null}}</td>
         </tr>
        <tr>
            <td>Admission Date :</td>
            <td>{{carbon\carbon::parse($discharge->getIndoorBook['doa_date'])->format('d-m-Y')}}</td>
         </tr>
         <tr>
            <td>Discharge Date :</td>
            <td>{{carbon\carbon::parse($discharge->dod_date)->format('d-m-Y')}}</td>
         </tr>

         @if(!empty($discharge->dos_date))
         <tr>
            <td>Date of Surgery :</td>
            <td>{{carbon\carbon::parse($discharge->dos_date)->format('d-m-Y')}}</td>
         </tr>
         @endif
         <tr>
            <td>Diagnosis :</td>
            <td>{{$discharge->diagnosis}}</td>
         </tr>
         <tr>
            <td>Tratment Given :</td>
            <td>{{$discharge->treatment_given}}</td>
         </tr>
         <tr>
            <td>HPE :</td>
            <td>{{$discharge->hpe}}</td>
         </tr>
         <tr>
            <td>Rx(Treatment) :</td>
            <td>{{$discharge->rx_treatment}}</td>
         </tr>
         <tr>
            <td>Summary :</td>
            <td>{{$discharge->summary}}</td>
         </tr>
         <tr>
            <td>Surgical Note :</td>
            <td>{{$discharge->surgical_note}}</td>
         </tr>
         <tr>
            <td>Report :</td>
            <td>{{$discharge->report}}</td>
         </tr>
         <tr>
            <td>Complaint :</td>
            <td>{{$discharge->complaints}}</td>
         </tr>
         <tr>
            <td>Vitals :</td>
            <td>{{$discharge->admission_vitals}}</td>
         </tr>
         <tr>
            <td>Examination :</td>
            <td>{{$discharge->examination}}</td>
         </tr>
         <tr>
            <td>Clinical Summary :</td>
            <td>{{$discharge->clinical_summary}}</td>
         </tr>
         <tr>
            <td>Vital On Discharge :</td>
            <td>{{$discharge->vital_on_discharge}}</td>
         </tr>
         <tr>
            <td>Condition on Discharge :</td>
            <td>{{$discharge->cond_on_discharge}}</td>   
         </tr>
         <tr>
            <td>Follow Up :</td>
            <td>{{$discharge->follow_up}}</td>
         </tr>
         <tr>
            <td>Follow Up Date :</td>
            <td>{{carbon\carbon::parse($discharge->followup_date)->format('d-m-Y')}}</td>
         </tr>
    </tbody>
</table> --}}
