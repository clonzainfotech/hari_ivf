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
        $this->apiToken = uniqid(base64_encode(str_random(100)));
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
                $patients = $this->OpdPatients->find($patientId);
                // $appointmentData = $this->Appointment::select('id','date','created_by','is_done','category_id','appontment_request_id','arrival_time',DB::raw("DATE_FORMAT(date,'%Y') as yearKey"))
                //                         ->where('patients_id', $patientId)
                //                         ->get();
                $appointmentRequestData = $this->AppointmentRequest::select('id','appointment_date as date','appointment_time as time','is_book',DB::raw("DATE_FORMAT(appointment_date,'%Y') as yearKey"))
                                            ->where('patients_id', $patientId)
                                            ->where('is_book','!=',1)
                                            ->get();
                // $data  = collect($appointmentData->merge($appointmentRequestData))->groupBy('yearKey');
                $data  = $appointmentRequestData;
                $aData = [];
                $appointmentData = [];
                // foreach ($data as $key=>$row) {
                    foreach ($appointmentRequestData as $value) {
                        $lastAppointment = $this->Appointment->where('patients_id', $patientId)->where('date',$value->date)->orderBy('id','DESC')->first();
                        if($lastAppointment)
                        {
                            $value->id = $lastAppointment->id;
                            $value->date = \Carbon\Carbon::parse($value->date)->format('d-m-Y');
                            $categoryData = $lastAppointment->category_id;
                            $value->category = $categoryData ? $lastAppointment['categoryDetails']['name'] : null; 
                            $value->category_id = $categoryData;
                            $currentDate = \Carbon\Carbon::now()->format('d-m-Y');
                            $currentTime = \Carbon\Carbon::now()->format('H:i:s');
                            $book = $value->is_book;
                            $status = $book == 0 ? 'Pending' : 'Rejected';
                            $value->reason = $value->remark;
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
                            }
                            $value->status = $status;
                            $value->profile_picture = $lastAppointment['getPatientsDetails']['profile_picture'];
                            unset($value->is_done,$value->yearKey,$value->categoryDetails,$value->getPatientsDetails,$value->appontment_request_id,$value->is_book,$value->arrival_time,$value->created_by);
                        }
                        $value->profile_picture = $patients->profile_picture;
                        $value->reason = $value->remark;
                        
                    }
                    // $appointmentData[][$key] = $aData;
                // }
                return $this->sendResponse('Get appointment details successfully', $appointmentRequestData);
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
        $msg = '';

        if($token) {
            // $patientId = $this->OpdPatients->where('token', $token)->pluck('id')->first();
            $patientId = $this->PatientToken->where('token', $token)->pluck('patients_id')->first();

            $lastAppointment = $this->AppointmentRequest->where('patients_id', $patientId)->orderBy('id','DESC')->first();

            if(!empty($patientId)) {
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
                            if (((strtotime($appointment->date.' '.$appointment->arrival_time) > strtotime($currentDate.' '.$currentTime)) && ($appointment->is_done == 0) || ($appointment->date == $currentDate && !$appointment->arrival_time))) {
                                $msg = "You have already appointment";
                            } else {
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
                                    $msg = "Your appointment is already aprooved";
                                }
                            }
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

        if($patientData){
            $appointmentData = collect($this->Appointment->select('id','patients_id','date','time','category_id')->where('patients_id',$patientData->patients_id)->orderBy('id','DESC')->get())->map(function($q){
                $q->status = date('Y-m-d') > $q->date ? 1 : 0;
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

    // private function checkCategory($categoryName)
    // {
    //     $ancArr = ['new anc','old anc'];
    //     $iuiArr = ['new iui','old iui'];
    //     $ivfArr = ['new ivf','old ivf'];

    //     if (in_array($categoryName, $ancArr)) {
    //         $patientCategory = 1;
    //     }
    //     if (in_array($categoryName, $iuiArr)) {
    //         $patientCategory = 2;
    //     }
    //     if (in_array($categoryName, $ivfArr)) {
    //         $patientCategory = 3;
    //     }
    //     return ['category' => $patientCategory];
    // }

}



