<?php

namespace App\Http\Controllers\Api;

use App\Models\OpdPatients;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use Carbon\Carbon;
use DB;

class AppointmentController extends ApiController
{
    private $apiToken;

    public function __construct()
    {
        // Unique Token
        parent::__construct();
        $this->apiToken = uniqid(base64_encode(\Illuminate\Support\Str::random(100)));
    }

    // user can login
    /**
    * Return Appointment with category and status
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $token = $request->header('Authorization');
        if($token){
            // $patientData = $this->OpdPatients->where('token', $token)->first();
            $patientData = $this->PatientToken->where('token', $token)->first();
            if(!empty($patientData)){
                $patientId = $patientData->patients_id;
                $patients = $this->OpdPatients->where('id',$patientId)->first();
                // if(empty($patients))
                // {
                //     return $this->sendError('Patient is not approved');
                // }
                $appointmentData = $this->Appointment::select('id','date','time','created_by','is_done','category_id','appontment_request_id','arrival_time','is_procedure',DB::raw("DATE_FORMAT(date,'%Y') as yearKey"))
                                        ->where('patients_id', $patientId)
                                        ->orderBy('date','desc')
                                        ->get();
                $appointmentRequestData = $this->AppointmentRequest::select('id','appointment_date as date','appointment_time as time','is_book',DB::raw("DATE_FORMAT(appointment_date,'%Y') as yearKey"))
                                            ->where('patients_id', $patientId)
                                            ->where('is_book','!=',1)
                                            ->orderBy('date','desc')
                                            ->get();
                $data  = collect($appointmentData->merge($appointmentRequestData))->groupBy('date');
                // $data  = $appointmentRequestData;
                $aData = [];
                $appointmentData = [];
                // dd($data);
                foreach ($data as $key=>$value) {
                    // foreach ($appointmentRequestData as $value) 
                    // {
                
                        $value = $value[0];
                        $utersWeek = null;
                        $oldDate = null;
                        $lastAppointment = $this->Appointment->where('patients_id', $patientId)->where('date',$value->date)->orderBy('id','DESC')->first();
                        if($lastAppointment)
                        {
                            $value->id = $lastAppointment->id;
                            // $value->date = \Carbon\Carbon::parse($value->date)->format('d-m-Y');
                            $categoryData = $lastAppointment->category_id;
                            
                            if($lastAppointment->category_id == 5 || $lastAppointment->category_id == 6)
                            {
                                $anc = $this->ANC->where('patients_id',$patientId)->orderBy('created_at','desc')->first();
                                if($anc) 
                                {
                                    $mhData = json_decode($anc->m_h);
                                    $lmdDate = $mhData->last_menstrual_date;
                                    $oldDate = \Carbon\Carbon::parse($lmdDate)->format('Y-m-d');
                                    // $utersWeek = \Carbon\Carbon::parse($oldDate)->diffInWeeks(\Carbon\Carbon::parse($value->date)->format('Y-m-d'));
                                }
                            }
                        }   
                        $utersWeek =  \Carbon\Carbon::parse(!empty($oldDate) ? $oldDate : date('Y-m-d'))->diffInWeeks(\Carbon\Carbon::parse($value->date)->format('Y-m-d')); 
                            $value->category = !empty($lastAppointment) && $categoryData ? $lastAppointment['categoryDetails']['name'] : null; 
                            $value->category_id = !empty($lastAppointment) ? $categoryData : null;
                            $currentDate = \Carbon\Carbon::now()->format('d-m-Y');
                            $currentTime = \Carbon\Carbon::now()->format('H:i:s');
                            $book = $value->is_book;
                            $status = $book == 0 ? 'Pending' : 'Rejected';
                            $value->reason = $value->remark;
                            $value->oldDate = $oldDate;
                            $value->time = \Carbon\Carbon::parse($value->time)->format('g:i').' '.(strtotime(\Carbon\Carbon::parse($value->time)->format('g:i')) > strtotime('9:00') &&   strtotime(\Carbon\Carbon::parse($value->time)->format('g:i')) < strtotime('12:00') ? 'AM' : 'PM');
                            if($value->created_by){
                                // if (strtotime($value->date) < strtotime($currentDate) && $value->is_done == 0) {
                                //     $status = "Nsot Visited";
                                // } elseif ((strtotime($value->date) >= strtotime($currentDate)) && (strtotime($value->arrival_time) > strtotime($currentTime)) && ($value->is_done == 0)) {
                                //     $status = "Approved";
                                // } else {
                                //     $status = "Visited";
                                // }
                                if ($value->is_done == 0) {
                                    $status = "Not Visited";
                                    if (((strtotime($value->date.' '.$value->arrival_time) > strtotime($currentDate.' '.$currentTime)) && ($value->is_done == 0)) || (!$value->arrival_time && strtotime($value->date) >= strtotime($currentDate))) {
                                        $status = "Approved";
                                    }
                                    
                                } else {
                                    $status = "Visited";
                                }
                                // for procedure visit
                                if($value->is_procedure == 1)
                                {
                                    $pickUp = $this->IvfPlanReport->where('patients_id',$patientId)->whereDate('created_at',\Carbon\Carbon::parse($value->date)->format('Y-m-d'))->first();
                                    if(!empty($pickUp))
                                    {
                                        $status = "Visited";
                                    }
                                    // $transfer = $this->IvfTransferReport->where('patients_id',$patientId)->whereDate('craeted_at',$value->date)->first();
                                }
                            }
                            $value->status = $status;
                            unset($value->is_done,$value->is_procedure,$value->yearKey,$value->categoryDetails,$value->getPatientsDetails,$value->appontment_request_id,$value->is_book,$value->arrival_time,$value->created_by);
                        // }
                        $value->profile_picture = $patients->profile_picture;
                        $value->reason = $value->remark;
                        $value->week = $utersWeek;
                       array_push($appointmentData,$value);
                    }
                // }
                return $this->sendResponse('Get appointment details successfully', $appointmentData);
            }
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Return appointment detail with report view using appointment id
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function appointmentDetail(Request $request)
    {

        $token = $request->header('Authorization');
        if($token){
            // $patientData = $this->OpdPatients->where('token', $token)->first();
            $patientData = $this->PatientToken->where('token', $token)->first();

            $rule = [
                'id' => 'required',
            ];
            $appointmentId = $request->id;
            $appointment = $this->Appointment->with('getPatientsDetails')->where('id', $appointmentId)->first();
            $pId=$appointment->patients_id;

            if(!empty($appointment)) {
                $gender = 'Male';
                if ($appointment->getPatientsDetails['gender'] == 2) {
                    $gender = 'female';
                }
                $result = 'Yes';
                if ($appointment->getPatientsDetails['is_pregnant'] == 2) {
                    $result = 'No';
                }
                $categoryId =$appointment->category_id;

                $category = $this->Category->where('id', $appointment->category_id)->first();
                $categoryName = '';
                if (!empty($category)) {
                    $categoryName = strtolower($category->name);
                }

                // $categoryName = '';
                // if (!empty($category)) {
                //     $categoryName = strtolower($category->name);
                // }
                $aptCharges = $this->AppointmentCharges->select('extra_field','consulting_charges','nst','cut','usg','ivf','procedure','dressing','total','discount','netamount')->where('appointment_id',$appointmentId)->first();
                $charges = null;
                if(!empty($aptCharges) && $aptCharges->netamount >= 0){
                    $charges = $aptCharges;
                    $extraField = unserialize($aptCharges->extra_field);
                    if($extraField){
                        $extraField1 = $extraField[0];
                        $extraField2 = $extraField[1];
                        if(!empty($extraField1[1])){
                            $aptCharges[$extraField1[0]] = $extraField1[1];
                        }
                        if(!empty($extraField2[1])){
                            $aptCharges[$extraField2[0]] = $extraField2[1];
                        }
                        unset($aptCharges->extra_field);
                    }
                }

                $madicineData = [];
                $reportsData = [];
                $aptCreatedDate = ($appointment->date);
                $url = [];
                if(!empty($categoryId)) {
                    //  $patientCategory =  $this->checkCategory($categoryName);
                    $medicineTime = ['1'=>'Morning','2'=>'Afternoon','3'=>'Evening','4'=>'Night'];
                    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
                    $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];

                    if(in_array($categoryId, [5, 6,10,13])) {
                        $anc = $this->ANC->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->where('patients_id',$pId)->first();
                        $madicine_status = ["1"=>"After Meal","2"=>"Empty Stomach","3"=>"Instead of menstruation space"];

                        if(!empty($anc)) {
                            $madicineData = null;
                            // $url[] = url('get-anc-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                            if(!empty($anc->treatment)) {
                                $treatment = json_decode($anc->treatment, true);
                                unset($treatment["medicinedata"]);
                                $madicines = $treatment;
                                foreach ($madicines as $madicine) {
                                    if(!empty($madicine['dose'])){
                                        $madicine['dose'] = $dose[$madicine['dose']];
                                    }
                                    if(!empty($madicine['medicine_status'])){
                                        $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                                    }
                                    if(isset($madicine['medicine_time']) && is_array($madicine['medicine_time']))
                                    {
                                        $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                                        unset($madicine['medicine_time']);
                                        foreach($medicineTimeData as $key=>$row){
                                            $madicineTime[] = $medicineTime[$row];
                                        }
                                        $madicine['medicine_time'] =!empty($madicineTime) ? implode(',',$madicineTime) : null;
                                    }
                                    else
                                    {
                                        $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                                    }
                                    
                                    $madicineData[] = $madicine;
                                }
                            }
                            $reportsArr = null;
                            if(!empty($anc->o_e)) {
                                $reportsArr = json_decode($anc->o_e, true);
                            }
                            $reportsData[] = $reportsArr;

                            $patient_id = encrypt($appointment->patients_id);
                            // $url = url('ancdata/'.$patient_id);
                            $url[] = url('get-anc-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                        }
                        else {
                            $ancHistory = $this->AncHistory->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                            if(!empty($ancHistory)) {
                                $madicineData = null;
                                if(!empty($anc->treatment)) {
                                    $treatment = json_decode($ancHistory->treatment, true);
                                    unset($treatment["medicinedata"]);
                                    $madicines = $treatment;
                                    foreach ($madicines as $madicine) {
                                        if (!empty($madicine['dose'])) {
                                            $madicine['dose'] = $dose[$madicine['dose']];
                                        }
                                        if (!empty($madicine['medicine_status'])) {
                                            $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                                        }
                                        if(is_array($madicine['medicine_time']))
                                        {
                                            $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                                            unset($madicine['medicine_time']);
                                            foreach($medicineTimeData as $key=>$row){
                                                $madicineTime[] = $medicineTime[$row];
                                            }
                                            $madicine['medicine_time'] =!empty($madicineTime) ? implode(',',$madicineTime) : null;
                                        }
                                        else
                                        {
                                            $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                                        }
                                        $madicineData[] = $madicine;
                                    }
                                }
                                $reportsArr = null;
                                if(!empty($anc->o_e)) {
                                    $reportsArr = json_decode($anc->o_e, true);
                                }
                                $reportsData[] = $reportsArr;
                                $patient_id = encrypt($appointment->patients_id);
                                // $url = url('anchistorydata/'.$patient_id.'/'.$aptCreatedDate);
                                $url[] = url('get-anc-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                            }
                            else {
                                $madicineData = null;
                                $reportsData = null;
                                // $url=[];
                            }

                        }
                    }
                    if(in_array($categoryId, [3, 4])) {
                        $madicine_status = ["1"=>"After Meal","2"=>"Empty Stomach","3"=>"Instead of menstruation space"];
                        $iui = $this->IUI->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                        if(!empty($iui)) {
                            $madicineData = null;
                            $url[] = url('get-iui-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                            if(!empty($iui->treatment)) {
                                $treatment = json_decode($iui->treatment, true);
                                unset($treatment["medicinedata"]);
                                $madicines = $treatment;
                                unset($treatment["medicine_time"]);
                                if(!empty($madicines)){
                                    foreach ($madicines as $madicine) {
                                        $madicineTime = [];
                                        if(!empty($madicine['dose'])){
                                            $madicine['dose'] = $dose[$madicine['dose']];
                                        }
                                        if(!empty($madicine['medicine_status'])){
                                            $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                                        }
                                        if(isset($madicine['medicine_time']) && is_array($madicine['medicine_time']))
                                        {
                                            $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                                            unset($madicine['medicine_time']);
                                            foreach($medicineTimeData as $key=>$row){
                                                $madicineTime[] = $medicineTime[$row];
                                            }
                                            $madicine['medicine_time'] =!empty($madicineTime) ? implode(',',$madicineTime) : null;
                                        }
                                        else
                                        {
                                            $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                                        }
                                        $madicineData[] = $madicine;
                                    }
                                }
                            }
                            $reportsArr = null;
                            if(!empty($iui->o_e)) {
                                $reportsArr = json_decode($iui->o_e, true);
                            }
                            $reportsData[] = $reportsArr;
                        }
                        // else{
                            $iuiHistory = $this->IuiHistory->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                            if(!empty($iuiHistory)) {
                                $madicineData = null;
                                if(!empty($iui->treatment)) {
                                    $treatment = json_decode($iuiHistory->treatment, true);
                                    unset($treatment["medicinedata"]);
                                    $madicines = $treatment;
                                    if(!empty($madicines)){
                                        foreach ($madicines as $madicine) {
                                            if (!empty($madicine['dose'])) {
                                                $madicine['dose'] = $dose[$madicine['dose']];
                                            }
                                            if (!empty($madicine['medicine_status'])) {
                                                $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                                            }
                                            if(isset($madicine['medicine_time']) && is_array($madicine['medicine_time']))
                                            {
                                                $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                                                unset($madicine['medicine_time']);
                                                foreach($medicineTimeData as $key=>$row){
                                                    $madicineTime[] = $medicineTime[$row];
                                                }
                                                $madicine['medicine_time'] =!empty($madicineTime) ? implode(',',$madicineTime) : null;
                                            }
                                            else
                                            {
                                                $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                                            }
                                            $madicineData[] = $madicine;
                                        }
                                    }
                                }
                                $reportsArr = null;
                                if(!empty($iui->o_e)) {
                                    $reportsArr = json_decode($iui->o_e, true);
                                }
                                $reportsData[] = $reportsArr;
                                $url[] = url('get-iui-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_history=1');
                            }
                            $iuiReport = $this->IUIReport->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                            if(!empty($iuiReport))
                            {
                                $url[] = url('get-iui-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_iuiReport=1');
                            }
                            $iuiExtraVisit = $this->IuiExtraVisit->where('patient_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                            if(!empty($iuiExtraVisit))
                            {
                                $url[] = url('get-iui-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_extraVisit=1');
                            }
                            else {
                                // $url = [];
                                $madicineData = null;
                                $reportsData = null;
                            }
                        // }
                    }
                    if(in_array($categoryId, [1, 2])) {
                        $ivf = $this->IVF->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                        $madicine_status = ["1"=>"After Meal","2"=>"Empty Stomach","3"=>"Instead of menstruation space"];

                        if(!empty($ivf)) {
                            $madicineData = null;
                            if(!empty($ivf->treatment)) {
                                $treatment = json_decode($ivf->treatment, true);
                                unset($treatment["medicinedata"]);
                                $madicines = $treatment;
                                if(!empty($madicines)){
                                    foreach ($madicines as $madicine) {
                                        if(!empty($madicine['dose'])) {
                                            $madicine['dose'] = $dose[$madicine['dose']];
                                        }
                                        if(!empty($madicine['medicine_status'])){
                                            $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                                        }
                                        $madicineData[] = $madicine;
                                    }
                                }
                            }
                            if(!empty($ivf->o_e)) {
                                $reportsArr = json_decode($ivf->o_e, true);
                            }
                            $url[] = url('get-ivf-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                            $reportsData[] = $reportsArr;
                        }
                        // else {
                            //PickUp report
                            $ivfPlanReport = $this->IvfPlanReport->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                            if($ivfPlanReport)
                            {
                                $url[] = url('get-ivf-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_history=1&is_pickup=1');
                            }
                            $ivfAllHistory = $this->IvfHistory->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->get();
                            if(!empty($ivfAllHistory)) {
                                foreach($ivfAllHistory as $ivfHistory)
                                {
                                    $madicineData = null;
                                    $reportsArr = null;
                                    $reportsData[] = $reportsArr;
                                    $historyData = json_decode($ivfHistory->description);
                                    $collectionData = !empty($historyData->collection) ? $historyData->collection : [];
                                    //Transfer Report
                                    if (in_array('transfer',$collectionData))
                                    {
                                        $url[] = url('get-ivf-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_history=1&is_trasnfer=1');
                                    }
                                    else
                                    {
                                        $url[] = url('get-ivf-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_history=1&cycle_no='.encrypt($ivfHistory->cycle_no));
                                    }
                                }
                            }
                            
                            $ivfExtraVisit = $this->IvfExtraVisit->where('patient_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->first();
                            if(!empty($ivfExtraVisit))
                            {
                                $url[] = url('get-ivf-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id).'&is_extraVisit=1');
                            }
                            else {
                                $madicineData = null;
                                $reportsData = null;
                            }
                        // }
                    }
                    if(in_array($categoryId, [17,18])) {
                        $gynec = $this->Gynec->where('patients_id',$appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$aptCreatedDate)->where('patients_id',$pId)->first();
                        $madicine_status = ["1"=>"After Meal","2"=>"Empty Stomach","3"=>"Instead of menstruation space"];

                        if(!empty($gynec)) {
                            $madicineData = null;
                            // $url[] = url('get-anc-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                            
                            $reportsArr = null;
                            if(!empty($anc->o_e)) {
                                $reportsArr = json_decode($anc->o_e, true);
                            }
                            $reportsData[] = $reportsArr;

                            $patient_id = encrypt($appointment->patients_id);
                            // $url = url('ancdata/'.$patient_id);
                            $url[] = url('get-gynec-details?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                        }
                        
                    }
                }

                $mobilenumber = $appointment->getPatientsDetails['mobile_number'];
                $residence = $appointment->getPatientsDetails['residence'];
                $city = $appointment->getPatientsDetails['city'];
                $weight = $appointment->getPatientsDetails['weight'];
                $occupation = $appointment->getPatientsDetails['occupation'];
                $mainArea = $appointment->getPatientsDetails['main_area'];
                $state = $appointment->getPatientsDetails->getState['name'];
                $hospitalDoctorName = $appointment->getPatientsDetails->getHospitalDoctor['name'];
                $doctorMobileNumber = $appointment->getPatientsDetails->getHospitalDoctor['mobile_number'];
                $referenceDoctor = $appointment->getPatientsDetails->getReferenceDoctor['name'];
                $referenceDrNumber = $appointment->getPatientsDetails->getReferenceDoctor['mobile_number'];

                $appointmentDetail = [
                    'patient_name' => $appointment->getPatientsDetails['name'],
                    'age' => $appointment->getPatientsDetails['age'],
                    'category' => $categoryName,
                    'patient_mobile' => $mobilenumber,
                    'arrival_date' => $appointment->date,
                    'appointment_Time' => $appointment->time,
                    'arrival_time' => $appointment->time,
                    'gender' => $gender,
                    'doctor_name' => $hospitalDoctorName,
                    'doctor_mobile_number' => $doctorMobileNumber,
                    'reference_doctor_name' => $referenceDoctor,
                    'reference_doctor_mobile_number' => $referenceDrNumber,
                    'residence' => $residence,
                    'main_area' => $mainArea,
                    'city' => $city,
                    'state' => $state,
                    'patients_weight' => $weight,
                    'pregnant' => $result,
                    'occupation' => $occupation,
                    'medicine_data' => $madicineData,
                    'reports' => $reportsData,
                    'charges' => $charges,
                    'url' => $url
                ];
                return $this->sendResponse('Get pateint appointment details successfully', $appointmentDetail);
            }
            return $this->sendError('Patient Appointment is not found', 401);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Add Appointment
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function addAppointment(Request $request) {

        $rule = [
            'date' => 'required',
            'time' => 'required'
        ];

        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }

        $token = $request->header('Authorization');
        $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
        $currentTime = \Carbon\Carbon::now()->format('H:i:s');
        $date = \Carbon\Carbon::parse($request->date)->format('H:i:s');
        $msg = '';

        if($token) {
            // $patientId = $this->OpdPatients->where('token', $token)->pluck('id')->first();
            $patientId = $this->PatientToken->where('token', $token)->pluck('patients_id')->first();

            $lastAppointment = $this->AppointmentRequest->where('patients_id', $patientId)->orderBy('id','DESC')->first();
            
            if(!empty($patientId)) {
                $appointment = $this->Appointment->where('date', $request->date)->orderBy('id','DESC')->first();
                if(!empty($appointment))
                {
                    return $this->sendResponse('You have already appointment');
                }
                if ($request->date >= $currentDate || $request->time > $currentTime) {
                    if (!empty($lastAppointment)) {
                        if ($lastAppointment->is_book == 0) {
                            $this->AppointmentRequest
                                ->find($lastAppointment->id)
                                ->update([
                                    'appointment_date' => $request->date
                                ]);
                            $msg = "Your appointment is already in pending";
                        }
                        if($lastAppointment->is_book == 1) {
                            $appointment = $this->Appointment->where('date', $lastAppointment->appointment_date)->orderBy('id','DESC')->first();
                            // if ($lastAppointment->appointment_date < $currentDate) {
                            // if (((strtotime($appointment->date.' '.$appointment->arrival_time) > strtotime($currentDate.' '.$currentTime)) && ($appointment->is_done == 0) || ($appointment->date == $currentDate && !$appointment->arrival_time))) {
                            //     $msg = "You have already appointment";
                            // } else {
                                if (!empty($appointment)) {
                                    // $msg = "Your appointment is already aprooved";
                                    // if($appointment->is_done == 0){
                                        $appointmentRequest = $this->AppointmentRequest;
                                        $appointmentRequest->patients_id = $patientId;
                                        $appointmentRequest->appointment_date = $request->date;
                                        $appointmentRequest->appointment_time = $request->time;
                                        $appointmentRequest->save();
                                        $msg = "Your appointment is in pending";
                                    // }
                                } else {
                                    $msg = "Your appointment is already approved";
                                }
                            // }
                        }
                        if ($lastAppointment->is_book == 2) { 
                            // if($lastAppointment->appointment_date <= $currentDate){
                                $appointmentRequest = $this->AppointmentRequest;
                                $appointmentRequest->patients_id = $patientId;
                                $appointmentRequest->appointment_date = $request->date;
                                $appointmentRequest->appointment_time = $request->time;
                                $appointmentRequest->save();
                                return $this->sendResponse("Your appointment is in pending");
                            // }
                        }
                        return $this->sendResponse($msg);
                    } else {
                        $appointmentRequest = $this->AppointmentRequest;
                        $appointmentRequest->patients_id = $patientId;
                        $appointmentRequest->appointment_date = $request->date;
                        $appointmentRequest->appointment_time = $request->time;
                        $appointmentRequest->save();
                        return $this->sendResponse('Your requested appointment is now in pending. we will inform you after approve it');
                    }
                } else {
                    return $this->sendError('Please enter valid date and time');
                }
            }
            else {
                return $this->sendError('Patient is not found');
            }
        }
    }

    /**
     * Get All appointment
     * if date > currentdate then status 1 else 0
     */
    public function allAppointment(Request $request){
        $token = $request->header('Authorization');
        // $patientData = $this->OpdPatients->where('token', $token)->first();
        $patientData = $this->PatientToken->where('token', $token)->first();

        if($patientData)
        {
            $appointmentData = collect($this->Appointment->select('id','patients_id','date','time','category_id')->where('patients_id',$patientData->patients_id)->orderBy('id','DESC')->get())->map(function($q){
                $q->status = date('Y-m-d') >= $q->date ? 1 : 0;
                $q->profile_picture = $q->getPatientsDetails['profile_picture'];
                $q->category = $q->categoryDetails['name']; 
                $q->category_id = $q->categoryDetails['id']; 
                unset($q->categoryDetails,$q->getPatientsDetails);
                return $q;
            });
            return $this->sendResponse('Your appointment successfully get',$appointmentData);
        }else{
            return $this->sendError('Patient is not found');
        }
    }
    /**
    * Book Appointment
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function bookAppointment(Request $request) {

        $rule = [
            'date' => 'required|after_or_equal:today',
            'time' => 'required',
            'doctor_id'=>'required'
        ];

        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }

        $token = $request->header('Authorization');
        $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
        $currentTime = \Carbon\Carbon::now()->format('H:i:s');
        $msg = '';

        if($token) {
            $patientId = $this->PatientToken->where('token', $token)->pluck('patients_id')->first();
            $patient = $this->OpdPatients->find($patientId);

            $lastAppointment = $this->AppointmentRequest->where('patients_id', $patientId)->orderBy('id','DESC')->first();

            if(!empty($patient))  
            {
                $appointment = $this->Appointment->where('date', \Carbon\Carbon::parse($request->date)->format('Y-m-d'))->orderBy('id','DESC')->first();
                if(!empty($appointment))
                {
                    return $this->sendResponse('You have already appointment');
                }
                $absence_doctor = $this->User->where('id',$request->doctor_id)->whereRole('3')->whereStatus('1')->whereRaw("find_in_set('".\Carbon\Carbon::parse($request->date)->format('m/d/Y')."',absence_dates)")->first();
                if($absence_doctor)
                {
                    // return $this->sendResponse($absence_doctor->name.' is not available on '.\Carbon\Carbon::parse($request->date)->format('d M Y').'. Please Select other Date');
                    return $this->sendResponse('Sorry for inconvenience '.$absence_doctor->name.' not available on this date prefer other Doctor or Dates');
                }
                $appointmentTime = \Carbon\Carbon::parse($request->time)->format('H:i:s');
                $nextAppointmentTime = \Carbon\Carbon::parse($request->time)->addMinute(15)->format('H:i:s');
                $checkTotalAppointment = $this->AppointmentRequest->where('appointment_date',\Carbon\Carbon::parse($request->date)->format('Y-m-d'))->where('is_book',0)->whereBetween('appointment_time',[$appointmentTime,$nextAppointmentTime])->get();
                $totalappointment = $request->doctor_id == 11 ? 3 : 2; // for jaydev sir, set 3 appointment in 15 min and for shivani ma'am ,set 2 appointmment
                if(count($checkTotalAppointment) == $totalappointment) 
                {
                    return $this->sendResponse('Appointmnet already booked on this time. Please Choose other sloat or change Doctor' );
                }

                if ($request->date >= $currentDate || $request->time > $currentTime) {
                    if (!empty($lastAppointment)) {
                        if ($lastAppointment->is_book == 0) {
                            $this->AppointmentRequest
                                ->find($lastAppointment->id)
                                ->update([
                                    'appointment_date' => \Carbon\Carbon::parse($request->date)->format('Y-m-d'),
                                    'appointment_time' => $request->time
                                ]);
                            $msg = $patient->is_approval == 1 ? "Approval for appointment is pending, Sorry for inconvenience. We will reply soon." : "Your appointment is pending. Please contact to Civora IVF Hospital for Approve your Request";
                        }
                        if($lastAppointment->is_book == 1) {
                            $appointment = $this->Appointment->where('date', $lastAppointment->appointment_date)->orderBy('id','DESC')->first();
                            // if ($lastAppointment->appointment_date < $currentDate) {
                            // if (((strtotime($appointment->date.' '.$appointment->arrival_time) > strtotime($currentDate.' '.$currentTime)) && ($appointment->is_done == 0) || ($appointment->date == $currentDate && !$appointment->arrival_time))) {
                            //     $msg = "You have already appointment";
                            // } else {
                                if (!empty($appointment)) {
                                    // $msg = "Your appointment is already aprooved";
                                    // if($appointment->is_done == 0){
                                        $appointmentRequest = $this->AppointmentRequest;
                                        $appointmentRequest->patients_id = $patientId;
                                        $appointmentRequest->appointment_date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
                                        $appointmentRequest->appointment_time = $request->time;
                                        $appointmentRequest->seen_by = $request->doctor_id;
                                        $appointmentRequest->save();
                                        $msg = "Approval for appointment is pending, Sorry for inconvenience. We will reply soon.";
                                    // }
                                } else {
                                    $msg = "Your appointment is already aprooved";
                                }
                            // }
                        }
                        if ($lastAppointment->is_book == 2) { 
                            // if($lastAppointment->appointment_date <= $currentDate){
                                $appointmentRequest = $this->AppointmentRequest;
                                $appointmentRequest->patients_id = $patientId;
                                $appointmentRequest->appointment_date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
                                $appointmentRequest->appointment_time = $request->time;
                                $appointmentRequest->seen_by = $request->doctor_id;
                                $appointmentRequest->save();
                                return $this->sendResponse($patient->is_approval == 1 ? "Approval for appointment is pending, Sorry for inconvenience. We will reply soon." : "Your appointment is pending. Please contact to Civora IVF Hospital for Approve your Request");
                            // }
                        }
                        return $this->sendResponse($msg);
                    } else {
                        $appointmentRequest = $this->AppointmentRequest;
                        $appointmentRequest->patients_id = $patientId;
                        $appointmentRequest->appointment_date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
                        $appointmentRequest->appointment_time = $request->time;
                        $appointmentRequest->seen_by = $request->doctor_id;
                        $appointmentRequest->save();
                        return $this->sendResponse($patient->is_approval == 1 ? 'Your requested appointment is now in pending. we will inform you after approve it' : "Your appointment is pending. Please contact to Civora IVF Hospital for Approve your Request");
                    }
                } else {
                    return $this->sendError('Please enter valid date and time');
                }
            }
            else {
                return $this->sendError('Patient is not found');
            }
        }
    }

    /**
    * Return list of Doctor for appoinment
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function appointmentDoctorList(Request $request) {

        $token = $request->header('Authorization');
        
        $result = [];
        if($token) 
        {
            $doctors = $this->User->select('*','id as doctor_id')->whereIn('id',[11,42,62])->whereRole('3')->whereStatus('1')->get();
        }
        return $this->sendResponse('Get Doctor list successfully',$doctors);
    }
   
    /**
    * Return count of sloat book
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getSloatBookCount(Request $request)
    {
        $rule = [
            'doctor_id'=>'required',
            'date'=>'required|after_or_equal:today'
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }
        $result = [];
        $message = 'Get Sloat Count successfully';
        $doctor = $this->User->where('role',3)->where('id',$request->doctor_id)->first();
        // $absence_doctor = $this->User->where('id',$request->doctor_id)->whereRole('3')->whereStatus('1')->whereRaw("find_in_set('".' '.\Carbon\Carbon::parse($request->date)->format('m/d/Y')."',absence_dates)")->first();
        $absence_doctor = $this->User->where('id',$request->doctor_id)->whereRole('3')->whereStatus('1')->where('absence_dates','like','%'.\Carbon\Carbon::parse($request->date)->format('m/d/Y').'%')->first();
        if($absence_doctor)
        {
            // return $this->sendResponse($absence_doctor->name.' is not available on '.\Carbon\Carbon::parse($request->date)->format('d M Y').'. Please Select other Date');
            return $this->sendResponse('Sorry for inconvenience '.$absence_doctor->name.' not available on this date prefer other Doctor or Dates',[]);
        }
        if($doctor)
        {
            //for shivani shah
            $totalSloat = 2; // check 2 patients within pre 15 min
            $sloats = ['10:00','10:15','10:30','10:45','11:00','11:15','11:30','11:45','12:00','12:15','12:30','12:45','16:00','16:15','16:30','16:45','17:00','17:15','17:30','17:45','18:00','18:15','18:30','18:45'];
            if($request->doctor_id == 11)//for jaydev sir
            {
                $sloats = ['10:00','10:15','10:30','10:45','11:00','11:15','11:30','11:45','12:00','12:15','12:30','12:45'];
                $totalSloat = 3;// check 3 patients within pre 15 min
                $message = 'Sorry for inconvenience '.$doctor->name.' not available on this date prefer other Doctor or Dates';
            }
            if(\Carbon\Carbon::parse($request->date)->format('l') == 'Sunday')
            {
                $sloats = ['10:00','10:15','10:30','10:45','11:00','11:15','11:30','11:45','12:00'];
                $message = 'Sorry for inconvenience '.$doctor->name.' not available on this date prefer other Doctor or Dates';
            }
            foreach($sloats as $key => $sloat)
            {
                $start_sloat = $key != 0 ? \Carbon\Carbon::parse($sloat)->addMinute(1)->format('h:i') : $sloat;
                $appointmentTime = \Carbon\Carbon::parse($sloat)->format('h:i:s');
                $nextAppointmentTime = \Carbon\Carbon::parse($sloat)->addMinute(15)->format('h:i');
                $checkTotalAppointment = $this->AppointmentRequest->where('seen_by',$request->doctor_id)->where('appointment_date',\Carbon\Carbon::parse($request->date)->format('Y-m-d'))->where('is_book',0)->where('appointment_time',$appointmentTime)->get();
                $data['sloat'] = \Carbon\Carbon::parse($start_sloat)->format('h:i').'-'.$nextAppointmentTime;
                //count = how many sloat is available
                $data['count'] = ($totalSloat - count($checkTotalAppointment)) >= 0  ? ($totalSloat - count($checkTotalAppointment)) : 0;
                if(strtotime($sloat) <= time() && \Carbon\Carbon::parse($request->date)->format('Y-m-d') <= date('Y-m-d'))
                {
                    $data['count'] = 0;
                }
                $now = date('Y-m-d');
                $data['status'] = strtotime($sloat) >= strtotime($now.' 16:00') ? 1 : 0;
                array_push($result,$data);
            }
            return $this->sendResponse($message,$result);
        }
        else
        {
            return $this->sendError('Doctor is not found');
        }
        
    }

}



