<?php

namespace App\Http\Controllers\Admin;


use DB;
use Log;
use Auth;
use View;
use Session;
use Exception;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Base\Admin\AdminController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Sesson;
use File;
use PDF;

class AppointmentController extends AdminController
{
     /**
     * Return list of appoitment with filtering
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$usg=null){
        try{
            $usgType = $request->usg_status;
            $usgStatus = false;
            if($usg){
                $usgStatus = decrypt($usg);
                if($usgStatus == 'usg'){
                    $usgStatus = true;
                }
            }
            $doctor = $this->getDoctor();
            $referenceDoctor = $doctor['referenceDoctor'];
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $patientsData = $this->getPatients();
            $hospitalTime = $this->appointmentTime('09:00', '20:00', '5 mins');
            // foreach($hospitalTime as $key=>$row){
            //     $appointment = new $this->Appointment;
            //     $appointment->patients_id = 1;
            //     $appointment->category_id = 2;
            //     $appointment->date = '2020-06-17';
            //     $appointment->time = \Carbon\Carbon::parse($row)->format('H:i:s');
            //     $appointment->save();
            // }
            // dd('ok');
            $category = $this->Category->pluck('name','id');
            $nowDate = Carbon::now()->format('Y-m-d');

            if($request->ajax()){
                $appointment = $this->Appointment;
                    // $appointment = $this->Appointment->where('is_procedure',0);
                    // ->where('category_id', '!=', 7);
                if($usgType == 'yes'){
                    $appointment = $appointment->where(function($query) {
                        $query->where('usg_status',1);
                    });
                }else{
                    $appointment = $appointment->where(function($query) {
                        $query->where('usg_status',0);
                    });
                }
                    // ->orderBy('id','DESC');
                // search text

                $patientId = $request->patient_id;

                if($patientId) {
                    $appointment = $appointment->where(function($query) use($patientId) {
                        $query
                        ->orWhereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->where('id', $patientId);
                        });
                    });
                }
                $categoryId = $request->categoryId;
                if($categoryId){
                    $reportDatails['category'] = $this->Category->where('id',$categoryId)->value('name');
                    $appointment = $appointment->WhereHas('categoryDetails', function ($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                }

                $search = $request->search;
                if($search){
                    $appointment = $appointment->where(function($query) use($search) {
                        $query
                        ->orWhereHas('getPatientsDetails', function($query) use($search) {
                            $query->where('mobile_number','LIKE',$search.'%');
                        });
                    });
                }

                $referenceDoctorId = $request->reference_doctor_id;
                $hospitalDoctorId = $request->hospital_doctor_id;
                if($referenceDoctorId){
                    $appointment = $appointment->WhereHas('getPatientsDetails', function($query) use($referenceDoctorId){
                        $query->where('reference_doctor_id',$referenceDoctorId);
                    });
                }
                if($hospitalDoctorId){
                    $appointment = $appointment->WhereHas('getPatientsDetails', function($query) use($hospitalDoctorId){
                        $query->where('hospital_doctor_id',$hospitalDoctorId);
                    });
                }

                if($request->date){
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    // dd($startDate);
                    if($date){
                        $pId = $this->Appointment->whereBetween('date', [$startDate, $endDate])->pluck('patients_id','patients_id')->toArray();
                        $patientsData = $this->getPatients($pId);
                        $appointment = $appointment->whereBetween('date', [$startDate, $endDate]);
                    }
                }
                if($request->date && $startDate == $nowDate && $endDate == $nowDate){
                    $appointment = $appointment
                                                ->orderBy(DB::raw('ISNULL(arrival_time), arrival_time'), 'ASC')
                                                // ->orderBy('arrival_time','ASC')
                                                ->orderBy('date','asc')
                                                ->orderBy(DB::raw('ISNULL(time), time'), 'ASC')
                                                // ->orderBy('time','asc')
                                                ->orderBy('is_done','ASC');
                }else{
                    $appointment = $appointment->orderBy('date','asc')
                                                ->orderBy('time','ASC');
                }
                // dd($appointment->get());
                $appointmentId = $appointment->pluck('id');

                if($request->isprint == 1){
                    $appointment = $this->Appointment->where('is_procedure',0)->where('category_id', '!=', 7)->orderBy('date','ASC');
                    if($usgType == 'yes'){
                        $appointment = $appointment->where(function($query) {
                            $query->where('usg_status',1);
                        });
                    }else{
                        $appointment = $appointment->where(function($query) {
                            $query->where('usg_status',0);
                        });
                    }
                    $appointment = $appointment->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['appointmentData'] = View::make('admin.appointment.appointment_print',compact('appointment'))->render();
                    return $data;
                }

                $appointment = $appointment->paginate(100);
                foreach($appointment as $row){
                    $row->next_appointment = true;
                    $patients = $this->getPatientsLastAppoinment($row->patients_id);
                    if(!empty($patients['lastAppointment'])){
                        $row->next_appointment = false;
                    }
                }

                $totalOpd = $this->AppointmentCharges->whereIn('appointment_id',$appointmentId)->count();
                $data['status'] = 1;
                $data['pId'] = $request->patient_id;
                $data['appointmentData'] = View::make('admin.appointment.data',compact('appointment','hospitalTime'))->render();
                $data['totalOpd'] = $totalOpd;
                $data['patientsData'] = $patientsData;
                return $data;
            }
            $isUsg = $usgStatus ? encrypt($usgStatus) : 0;
            return view('admin.appointment.index',compact('usgStatus','patientsData','category','referenceDoctor','hospitalDoctor','hospitalTime','isUsg'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    /**
     * Return on create appointment blade with required parameters
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create($id = null,Request $request) {
        try{
            $usgType = 0;
            if($request->usgType){
                $usgType = decrypt($request->usgType);
            }
            $already = null;
            $patients = $this->getPatients();
            $patientcode = $this->getPatientscode();
            $city = $this->City->pluck('name','name');
            $hospitalTime = $this->appointmentTime('09:00', '20:00', '5 mins');
            $category = $this->Category
                ->whereStatus(1)
                ->whereNotIn('id', [7])
                ->pluck('name','id');
            $doctor = $this->getDoctor();
            $referenceDoctor = ['other'=>'Other'] + $doctor['referenceDoctor']->toArray();
            $proReferenceDoctor = ['other'=>'Other'] + $this->ReferenceDoctorPro->pluck('name','id')->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $state = $this->getState()['state'];
            if($id){
                try{
                    $decryptedId = decrypt($id);
                    $patientData = $this->OpdPatients->where('id', $decryptedId)->get()->first();
                    return view('admin.appointment.create',compact('proReferenceDoctor','patientData', 'category','referenceDoctor','hospitalDoctor','state','city'));
                }catch (Exception $exception) {
                    return redirect('patient');
                }
            }
            return view('admin.appointment.create',compact('proReferenceDoctor','patients', 'category','referenceDoctor','hospitalDoctor','state','city','patientcode','hospitalTime','already','usgType'));
        }catch(Exception $e){
            abort(500);
        }
    }
    /**
     * Add appointments 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $requestData = $request;
            $rule = [
                'name' => 'required',
                'date' => 'required',
                // 'age' => 'required|numeric|between:1,100',
                // 'gender' => 'required',
                // 'mobile_number' => 'nullable|numeric|digits:10|unique:patients,mobile_number',
                // 'category' => 'required',
                // 'city_1' => 'required',
                // 'dob' =>'nullable|before:' . date('Y-m-d'),
                // 'dob'=>'nullable|before:'. date('Y-m-d')
            ];

            // $rule['mobile_number'] = 'nullable|numeric|digits:10|unique:patients,mobile_number';
            if($request->ajax()){
                $mobile_number = $this->OpdPatients->where('mobile_number',$request->mobile_number)->first();
                if(!empty($mobile_number)) {
                    return['status'=>1];
                }
            }
            if(!empty($request->code)) {
                $patientId = $this->OpdPatients
                    ->whereCode($request->code)
                    ->value('id');
                    $rule['mobile_number'] = 'required|numeric|digits:10|unique:patients,mobile_number,' . $patientId;
            }


            if ($request->input('reference_doctor') == 'other') {
                $rule['doctor_name'] = 'required|max:255';
                $rule['doctor_mobile_number'] = 'required|numeric|digits:10|unique:reference_doctors,mobile_number';
            }

            if ($request->input('pro_reference_doctor') == 'other') {
                $rule['pro_reference_doctor_name'] = 'required|max:255';
                $rule['pro_reference_doctor_mobile_number'] = 'required|numeric|digits:10|unique:reference_doctor_pro,mobile_number';
            }

            $message =[
                'city_1' => 'The city field is required',
            ];

            $patients = $this->OpdPatients;
            $appointment = $this->Appointment;
            $appointmentDate = Carbon::parse($request->date)->format('Y-m-d');
            $isNext = 0;
            $already = 0;
            if($request->seen_by)
            {
                $absence_doctor = $this->User->where('id',$request->seen_by)->whereRole('3')->whereStatus('1')->whereRaw("find_in_set('".Carbon::parse($request->date)->format('m/d/Y')."',absence_dates)")->first();
                if(!empty($absence_doctor))
                {
                    $already = 4;
                    $isNext = 1;
                    Session::flash('doctor',$absence_doctor->name);
                    Session::flash('apt_date',Carbon::parse($request->date)->format('d M Y'));
                }
            }
            
            
            $appointmentDay = Carbon::parse($appointmentDate)->format('D');
            if($appointmentDay == 'Sun'){
                $already = 2;
                $isNext = 1;
            }
            if($request->is_next == 1){
                $isNext = 0;
            }
            if ($request->code) {
                $patientsId = $this->OpdPatients->whereCode($request->code)->value('id');
                if ($patientsId == null) {
                    Session::flash('msg',"Please enter valid code!");
                    return back()->withInput();
                }
                $nowDate = Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d');
                $nextAppointmentData = $this->Appointment->where('patients_id',$patientsId)
                                ->where('date','>=',$nowDate)
                                ->orderBy('id','DESC');
                $usgStatusValue = 0;
                if($request->is_usg == 1){
                    $usgStatusValue = 1;
                }
                $nextAppointmentData = $nextAppointmentData->where('usg_status',$usgStatusValue)->first();
                // $nextAppointmentData = $this->getPatientsLastAppoinment($patientsId);
                if(!empty($nextAppointmentData)){
                    $already = 1;
                    $isNext = 1;
                    if(($nextAppointmentData['date'] == date('Y-m-d') && $appointmentDate != date('Y-m-d'))){
                        $isNext = 0;
                    }
                }
                $holiday = $this->HolidayManager->where('from_date','<=',$appointmentDate)->Where('to_date','>=',$appointmentDate)->first();
                if($holiday){
                    Session::flash('holidayName',$holiday->name);
                    $already = 3;
                    $isNext = 1;
                }
                $rule['mobile_number'] = 'nullable|numeric|digits:10|unique:patients,mobile_number,' . $patientsId;
            }
            if($isNext == 1){
                // Session::flash('msg',"You can't add next appointment for this patients because he has already appointment !");
                if(!empty($nextAppointmentData)){
                    Session::flash('date',Carbon::parse($nextAppointmentData['date'])->format('d-m-Y'));
                    Session::flash('time',$nextAppointmentData['time'] ? Carbon::parse($nextAppointmentData['time'])->format('H:i') : null);
                    Session::flash('appointmentId',$nextAppointmentData['id']);
                }
                if(!empty($patientsId)){
                    Session::flash('patientId',$patientsId);
                }
                Session::flash('p_name',$request->name);
                
                return back()->with('already',$already)->withInput();
            }


            $valid = Validator::make($request->all(),$rule,$message);
            if($valid->fails()){
                return redirect()->back()
                    ->withErrors($valid->errors())
                    ->withInput();
            }


            //reference doctor
            if ($request->input('reference_doctor') == 'other') {
                $referenceDoctor = $this->ReferenceDoctor;
                $referenceDoctor->name = $request->doctor_name;
                $referenceDoctor->mobile_number = $request->doctor_mobile_number;
                $referenceDoctor->created_by = Auth::user()->id;
                $referenceDoctor->save();
            }

            //reference doctor
            if ($request->input('pro_reference_doctor') == 'other') {
                $proReferenceDoctor = $this->ReferenceDoctorPro;
                $proReferenceDoctor->name = $request->pro_reference_doctor_name;
                $proReferenceDoctor->mobile_number = $request->pro_reference_doctor_mobile_number;
                $proReferenceDoctor->created_by = Auth::user()->id;
                $proReferenceDoctor->save();
            }

            $patients->reference_doctor_id = ($request->input('reference_doctor') == 'other') ? $referenceDoctor->id : $request->reference_doctor;
            $patients->reference_doctor_pro_id = ($request->input('pro_reference_doctor') == 'other') ? $proReferenceDoctor->id : $request->pro_reference_doctor;
            $patients->other_patient_reference=$request->other_patient_reference;

            $patients->city = $request->city_1;

            if($request->city_1 == 'Other'){
                $city = $this->City;
                $city->name = $request->city_2;
                $city->save();
                $patients->city = $request->city_2;
            }

            $updatePatient = $this->OpdPatients;
            $url = 'patient';
            $message = 'Patient has been added!';

            if ($request->code == null) {
                if (!empty($request->patient_id)) {
                    $updatePatient = $this->OpdPatients->where('id', decrypt($request->patient_id))->first();
                    $message = 'Patient has been updated!';
                }

            }
            else {
                $updatePatient = $this->OpdPatients->whereCode($request->code)->first();
                $message = 'Patient has been updated!';

            }
            $dateFilter = Carbon::createFromFormat('D d M Y', $request->date)->format('d/m/Y');
            Session(['date' => $dateFilter . ' - ' . $dateFilter]);
            $message = 'Appointment has been added!';
            $url = 'appointment';
            $requestData = new \Illuminate\Http\Request();
            $requestData->replace(['date' => $request->date]);
            $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
            if(empty($request->time) && (!empty($nextAppontment['time']) || (!empty($nextAppontment['time']) || $nextAppontment['time'] == 0))){
                $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                $request->time = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                $request->date = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $fDate;
            }
            // if($request->next_time || $request->next_time == 0){
            //     $now = Carbon::now()->format('Y-m-d');
            //     $date = Carbon::parse($request->date)->format('Y-m-d');
            //     $now = strtotime($now);
            //     $date = strtotime($date);
            //     if($date > $now){
            //         $hospitalTime = $this->appointmentTime('10:00', '20:00', '5 mins');
            //         $aTime = $hospitalTime[$request->next_time];
            //         $request->time = $aTime;
            //     }else{
            //         $request->time = null;
            //     }
            // }
            $this->savePatientAndAppointment($request, $updatePatient, $patients, $appointment);
            Session::flash('msg', $message);
            return redirect($url);
        }catch(Exception $e) {
            // dd($e);
            log::Debug($e);
            abort(500);
        }

    }

    /**
     * Add patients and appointment data
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePatientAndAppointment($request, $updatePatient, $patients, $appointment) {
        $patient_check = $this->OpdPatients->where('mobile_number',$request->mobile_number)->first();
        $arrivalTime = null;
        if($patient_check == null){
            $updatePatient->name = $request->name;
            $updatePatient->gender = $request->gender;
            $updatePatient->age = $request->age;
            $updatePatient->months = $request->months;
            $updatePatient->days = $request->days;
            $updatePatient->mobile_number = $request->mobile_number;
            $updatePatient->weight = $request->weight;
            $updatePatient->other_mobile_number = $request->other_mobile_number;
            $updatePatient->reference_doctor_id =  $patients->reference_doctor_id;
            $updatePatient->other_patient_reference=$request->other_patient_reference;
            $updatePatient->reference_doctor_pro_id =  $patients->reference_doctor_pro_id;
            $updatePatient->hospital_doctor_id = $request->hospital_doctor;
            $updatePatient->residence = $request->residence;
            $updatePatient->main_area = $request->main_area;
            $updatePatient->city = $patients->city;
            $updatePatient->state = $request->state;
            $updatePatient->occupation = $request->occupation;
            $updatePatient->is_pregnant = $request->pregnant;
            $updatePatient->dob = $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;
            $updatePatient->height = $request->height;
            $updatePatient->created_by = Auth::user()->id;
            $updatePatient->save();

            if(!$request->code) {
                $generateCode = $this->generateCode($updatePatient->id,$updatePatient->name);
                $updateCode = $this->OpdPatients->whereId($updatePatient->id)->first();
                $updateCode->code = $generateCode['code'];
                $updateCode->save();
            }
            // $checkAppointment = $this->checkAppointment($updatePatient->id,$request->date,$request->time,$request->arrival_time);
            $arrivalTime = null;
            // $time = null;
            // if($checkAppointment['status']){
            //     $appointment = $checkAppointment['data'];
            //     $arrivalTime = $appointment->arrival_time;
            //     $time = $appointment->time;
            // }
            $appointment->date = Carbon::parse($request->date)->format('Y-m-d');
            // if(Carbon::parse($request->date)->format('Y-m-d') == date('Y-m-d')){
            //     $appointment->time = null;
            // }else{
            $appointment->time =  !empty($request->time) ? Carbon::parse($request->time)->format('H:i') : $time;
            // }
            $appointment->arrival_time = !empty($request->arrival_time) ? Carbon::parse($request->arrival_time)->format('H:i') : $arrivalTime;
            $appointment->category_id = $request->category;
            $appointment->created_by = Auth::user()->id;
            $appointment->patients_id = $updatePatient->id;
            $appointment->remark = $request->remark;
            $appointment->usg_status= $request->is_usg ? 1 : 0;

            $appointment->seen_by = !empty($request->seen_by) ? $request->seen_by :$request->hospital_doctor;
            $appointment->is_new_anc = !empty($request->is_new_anc) ? $request->is_new_anc :0;

            $appointment->save();
            if($request->category){
                // store on patients category table
                $patientsCategory = $this->PatientsCategory;
                $patientsCategory->patients_id = $updatePatient->id;
                $patientsCategory->appointment_id = $appointment->id;
                $patientsCategory->category_id = $request->category;
                $patientsCategory->save();
            }
            // }

            return;
        }else{
            $updatePatient->other_patient_reference=$request->other_patient_reference;
            $updatePatient->reference_doctor_id =  $patients->reference_doctor_id;
            $updatePatient->reference_doctor_pro_id =  $patients->reference_doctor_pro_id;
            $updatePatient->save();
            // $checkAppointment = $this->checkAppointment($patient_check->id,$request->date,$request->time,$request->arrival_time);
            $arrivalTime = null;
            $time = null;
            // if($checkAppointment['status']){
            //     $appointment = $checkAppointment['data'];
            //     $arrivalTime = $appointment->arrival_time;
            //     $time = $appointment->time;
            // }
            $appointment->date = Carbon::parse($request->date)->format('Y-m-d');
            $aDate = Carbon::parse($request->date)->format('Y-m-d');
            // if($aDate != date('Y-m-d')){
            $appointment->time =  !empty($request->time) ? Carbon::parse($request->time)->format('H:i') : $time;
            // }
            $appointment->arrival_time = !empty($request->arrival_time) ? Carbon::parse($request->arrival_time)->format('H:i') : $arrivalTime;
            $appointment->category_id = $request->category;
            $appointment->created_by = Auth::user()->id;
            $appointment->patients_id = $patient_check->id;
            $appointment->usg_status= $request->is_usg ? 1 : 0;
            $appointment->remark = $request->remark;
            $appointment->seen_by = !empty($request->seen_by) ? $request->seen_by : $request->hospital_doctor;
            $appointment->is_new_anc = !empty($request->is_new_anc) ? $request->is_new_anc :0;
            
            $appointment->save();
            // store on patients category table
            if($request->category){
                $patientsCategory = $this->PatientsCategory;
                $patientsCategory->patients_id = $patient_check->id;
                $patientsCategory->appointment_id = $appointment->id;
                $patientsCategory->category_id = $request->category;
                $patientsCategory->save();
            }
            // }

            return;
        }
    }

    /**
     * Check Appointment and return data
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function checkAppointment($patientId,$date,$time,$arrivalTime){
        $checkAppointment = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC');
        // if($date){
        //     $checkAppointment = $checkAppointment->where('date',Carbon::parse($date)->format('Y-m-d'));
        // }
        $nowDate = Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d H:i:s');
        $checkAppointment = $this->Appointment->where('patients_id',$patientId)
                                ->where(\DB::raw('concat(date," ",time)'),'>',$nowDate)
                                // ->whereDate('date','>=',$nowDate)
                                ->orderBy('id','DESC')
                                ->first();
        // if($time){
        //     // $checkAppointment = $checkAppointment->where('time',Carbon::parse($time)->format('H:i:s'));
        // }
        // if($arrivalTime){
        //     $checkAppointment = $checkAppointment->where('arrival_time',Carbon::parse($arrivalTime)->format('H:i:s'));
        // }
        // $checkAppointment = $checkAppointment->first();
        // dd($checkAppointment);
        if($checkAppointment){
            return ['status'=>true,'data'=>$checkAppointment];
        }
        return ['status'=>false,'data'=>null];
    }

    /**
     * return on edit blade with require parameter for appointment edit
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request){
        try{
            $proReferenceDoctor = ['other'=>'Other'] + $this->ReferenceDoctorPro->pluck('name','id')->toArray();
            if($request->ajax()){
                $patients = $this->OpdPatients
                    ->whereId($id)
                    ->first();
                    if(!$patients){
                        $patientsNumber = $this->OpdPatients
                            ->where('mobile_number',$id)
                            ->first();
                            $patients = $patientsNumber;
                    }
                if(!$patients){
                    $patientsCode = $this->OpdPatients
                                        ->whereCode($id)
                                        ->first();
                    $patients = $patientsCode;
                }
                $categoryId =null;
                $appointmentId = null;
                if (!empty($patients)) {
                    $lastAppointment = $this->Appointment->where('patients_id',$patients->id)->orderBy('id','DESC')->first();
                    if($lastAppointment){
                        $categoryId = $lastAppointment->category_id;
                        $appointmentId = $patients->getAppointment['id'];
                        $categoryName = $lastAppointment->categoryDetails['name'];
                        $categoryId = $this->categoryNewToOld($categoryId);
                    }
                    $type = 'code';
                }
            }else{
                $appointmentId = decrypt($id);
                $type = 'id';
            }
            $city = $this->City->pluck('name','name');
            $appointment = null;
            if (!empty($appointmentId)) {
                $appointment = $this->Appointment
                    ->with('getPatientsDetails')
                    ->where('id',$appointmentId)
                    ->first();
            }
            $category = $this->Category
                ->whereStatus(1)
                ->whereNotIn('id', [7])
                ->pluck('name','id');

            $doctor = $this->getDoctor();
            $referenceDoctor = ['other' => 'Other'] + $doctor['referenceDoctor']->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $state = $this->getState()['state'];

            if($request->ajax()){
                $data = [];
                $data['patients'] = $patients;
                $data['appointmentCategoryId'] = $categoryId;
                return $data;
            }
            return view('admin.appointment.edit',compact('proReferenceDoctor','appointment','category','referenceDoctor','hospitalDoctor','state','city'));
        }catch(Exception $e){
            app('App\Http\Controllers\Base\Admin\AdminController')->storeLog($request,500,$e->getMessage());
            return view('errors.500');
        }
    }

    /**
     * edit appoinment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        try {
            $id = decrypt($id);
            $appointment = $this->Appointment->find($id);
            // dd($appointment);
            $patientsId = $appointment->getPatientsDetails['id'];
            $rule = [
                'name' => 'required',
                'date' => 'required',
                // 'time' => 'required',
                'gender' => 'required',
                'mobile_number' => 'required|numeric|digits:10|unique:patients,mobile_number,'.$patientsId,
                // 'age' => 'required',
                'reference_doctor' => 'required',
                'hospital_doctor' => 'required',
                'residence' => 'required',
                'state' => 'required',
                'dob' =>'nullable|before:' . date('Y-m-d'),
                // 'dob'=>'nullable|before:'. date('Y-m-d')
            ];

            if ($request->input('reference_doctor') == 'other') {
                $rule = [
                    'doctor_name' => 'required|max:255',
                    'doctor_mobile_number' => 'required|numeric|digits:10|unique:reference_doctors,mobile_number',
                ];
            }
            if ($request->input('pro_reference_doctor') == 'other') {
                $rule['pro_reference_doctor_name'] = 'required|max:255';
                $rule['pro_reference_doctor_mobile_number'] = 'required|numeric|digits:10|unique:reference_doctor_pro,mobile_number';
            }
            $valid = Validator::make($request->all(),$rule);
            if($valid->fails()){
                return redirect()->back()
                    ->withErrors($valid->errors())
                    ->withInput();
            }

            if($request->input('reference_doctor') == 'other') {
                $referenceDoctor = $this->ReferenceDoctor;
                $referenceDoctor->name = $request->doctor_name;
                $referenceDoctor->mobile_number = $request->doctor_mobile_number;
                $referenceDoctor->created_by = Auth::user()->id;
                $referenceDoctor->save();
            }

            if($request->input('pro_reference_doctor') == 'other') {
                $proReferenceDoctor = $this->ReferenceDoctorPro;
                $proReferenceDoctor->name = $request->pro_reference_doctor_name;
                $proReferenceDoctor->mobile_number = $request->pro_reference_doctor_mobile_number;
                $proReferenceDoctor->created_by = Auth::user()->id;
                $proReferenceDoctor->save();
            }
            $patients = $this->OpdPatients->find($patientsId);
            $patients->name = $request->name;
            $patients->gender = $request->gender;
            $patients->age = $request->age;
            $patients->months = $request->months;
            $patients->days = $request->days;
            $patients->mobile_number = $request->mobile_number;
            $patients->name = $request->name;
            $patients->dob = $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;
            $patients->weight = $request->weight;
            $patients->height = $request->height;
            $patients->age = $request->age;
            $patients->other_mobile_number = $request->other_mobile_number;
            $patients->reference_doctor_id = ($request->input('reference_doctor') == 'other') ? $referenceDoctor->id : $request->reference_doctor;
            $patients->reference_doctor_pro_id = ($request->input('pro_reference_doctor') == 'other') ? $proReferenceDoctor->id : $request->pro_reference_doctor;
            $patients->other_patient_reference=$request->other_patient_reference;

            if($request->hospital_doctor > 0){
                $patients->hospital_doctor_id = $request->hospital_doctor;
            }
            $patients->residence = $request->residence;
            $patients->main_area = $request->main_area;
            $patients->city = $request->city_1;
            // city entry

            if($request->city_1 == 'Other'){
                $city = $this->City;
                $city->name = $request->city_2;
                $city->save();
                $patients->city = $request->city_2;
            }
            $patients->state = $request->state;
            $patients->occupation = $request->occupation;
            $patients->is_pregnant = $request->pregnant;
            $patients->created_by = Auth::user()->id;
            $patients->save();
            if($request->category != $appointment->category_id){
                $this->storePatientCategory($appointment->id,$request->category,$patients->id);
            }
            $oldDate = $appointment->date;
            $oldTime = $appointment->time;
            $appointment->date = Carbon::parse($request->date)->format('Y-m-d');
            
            $dateFilter = Carbon::createFromFormat('D d M Y', $request->date)->format('d/m/Y');
            Session(['date' => $dateFilter . ' - ' . $dateFilter]);
            $appointment->time =  $request->time ? Carbon::parse($request->time)->format('H:i') : null;
            $appointment->arrival_time = (!empty($request->arrival_time)) ? Carbon::parse($request->arrival_time)->format('H:i') : null;
            $appointment->category_id = $request->category;
            $appointment->updated_by = Auth::user()->id;
            $appointment->remark = $request->remark;
            $appointment->seen_by = !empty($request->seen_by) ? $request->seen_by : $patients->hospital_doctor_id;
            $appointment->is_new_anc = !empty($request->is_new_anc) ? $request->is_new_anc :0;
            $appointment->save();
            $newFollowUpDate = $appointment->date;
            $lastAppointment = $this->Appointment->where('date','<',$appointment->date)->where('patients_id',$patients->id)->orderBy('id','DESC')->first();
            //update followup date when change appintment date
            if($oldDate != $appointment->date)
            {
                if(in_array($appointment->category_id, [5, 6])) 
                {
                    $anc = $this->ANC->where('patients_id',$patients->id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$lastAppointment->date)->first();
                    if(!empty($anc)) {
                        $anc_oe_data = json_decode($anc->o_e);
                        $anc_oe_data->follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                        $anc_oe_data->new_follow_up = Carbon::parse($newFollowUpDate)->format('Y-m-d');
                        
                        $anc->o_e = json_encode($anc_oe_data);
                        $anc->save();
                    }
                    else
                    {
                        $ancHistory = $this->AncHistory->where('patients_id',$patients->id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$lastAppointment->date)->first();
                        if($ancHistory)
                        {
                            $anc_oe_data = json_decode($ancHistory->o_e);
                            $anc_oe_data->follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                            $anc_oe_data->new_follow_up = Carbon::parse($newFollowUpDate)->format('Y-m-d');

                            
                            $ancHistory->o_e = json_encode($anc_oe_data);
                            $ancHistory->save();
                        }
                    }
                }
                if(in_array($appointment->category_id, [3, 4])) 
                {
                    $iui = $this->IUI->where('patients_id',$patients->id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$lastAppointment->date)->first();
                    if(!empty($iui)) {
                        // dd($iui);
                        $iui->follow_up = Carbon::parse($newFollowUpDate)->format('Y-m-d');
                        $iui->save();
                    }
                    else
                    {
                        $iuiHistory = $this->IuiHistory->where('patients_id',$patients->id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$lastAppointment->date)->first();
                        if($iuiHistory){
                            $iuiData = json_decode($iuiHistory->description);
                            if($iuiHistory->visit == 2)
                            {
                                // dd($iuiData->plan->follow_up);

                                $iuiData->plan->follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                                $iuiData->new_follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                                // dd($iuiData);
                                $iuiHistory->description = json_encode($iuiData);
                                $iuiHistory->save();
                            }
                            else
                            {
                                $iuiData->follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                                // dd($iuiData);
                                $iuiHistory->description = json_encode($iuiData);

                                $iuiHistory->save();
                            }
                        }
                    }
                }
                if(in_array($appointment->category_id, [1, 2])) 
                {
                    $ivfHistory = $this->IvfHistory->where('patients_id',$patients->id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$lastAppointment->date)->first();
                    if($ivfHistory){
                        $ivfData = json_decode($ivfHistory->description);
                        
                            $ivfData->follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                            $ivfData->transfer->follow_up = Carbon::parse($newFollowUpDate)->format('D d M Y');
                            
                            $ivfHistory->description = json_encode($ivfData);

                            $ivfHistory->save();
                    }
                }
            }
            
            $generateCode = $this->generateCode($patientsId,$patients->name);
            $updateCode = $this->OpdPatients->whereId($patientsId)->first();
            $updateCode->code = $generateCode['code'];
            $updateCode->save();
            Session::flash('msg','Appointment has been updated!');
            return redirect('appointment');
        }
        catch(Exception $e){
            // dd($e);
            log::Debug($e);
            abort(500);
        }
    }

    /**
     * Delete appoinment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,$id){
        try{
            $appointment = $this->Appointment->find($id);
            $appointment->deleted_by = Auth::user()->id;
            $appointment->save();
            $appointment->delete();
            Session::flash('msg','Appointment removed.');
            if($request->ajax()){
                return 'true';
            }else{
                return redirect('appointment');
            }
        }catch(Exception $e){
            return 'false';
        }
    }

    /**
    * Get list of state
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function getState(){
        $state = $this->State->pluck('name','id');
        return ['state'=>$state];
    }

    /**
    * Get list of doctor
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function getDoctor(){
        $referenceDoctor = $this->ReferenceDoctor->orderBy('name','ASC')->pluck('name','id');
        $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
        return['referenceDoctor'=>$referenceDoctor,'hospitalDoctor'=>$hospitalDoctor];
    }

    /**
    * Generate QR code with patient detail
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function sticker(Request $request)
    {
        if(!isset($request->is_label))
        {

            $is_indoor = $request->is_indoor;
            $appointmentId = decrypt($request->appointmentId);
            $appointment = $this->Appointment
                ->with('getPatientsDetails','getPatientsDetails.getReferenceDoctor')
                ->where('id',$appointmentId)
                ->first();
                if($request->is_indoor == 1){
                    $indoorBook = $this->IndoorBook->wherePatientId($appointment->getPatientsDetails['id'])->first();
                    $appointment->date =  $request->date;
                }
            $gender = 'Male';
            if($appointment->getPatientsDetails['gender']==2)
            {
                $gender = 'Female';
            }
            $appointment->gender = $gender;
            $qrcode=QrCode::size(500)
                ->format('png')
                ->generate(public_path('images/qrcode.png'));
            return response()->json([
                View::make('admin.appointment.sticker', compact('appointment','qrcode','is_indoor'))->render()
            ]);
        }
        else
        {
            $procedure_name = $request->procedure_name;
            $label_name = $request->label_name;
            $is_label = 1;
            return response()->json([
                View::make('admin.appointment.sticker', compact('procedure_name','label_name','is_label'))->render()
            ]);
        }

        
    }

     /**
    * print appointment detail
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function printview(Request $request)
    {
        $appointmentId = decrypt($request->appointmentId);
        $appointment = $this->Appointment->with('getPatientsDetails')->where('id',$appointmentId)->first();
        return response()->json([
            View::make('admin.appointment.print', compact('appointment'))->render()
        ]);
    }
    /**
    * Get next appointment
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function nextAppointment(Request $request){
        try{
            $currentDate = Carbon::now();
            $addDate = $currentDate->addDays($request->day)->format('Y-m-d');

            if($request->date){
                $addDate = $request->date;
            }
            if($request->appointment_req_id){
                $apId = decrypt($request->appointment_req_id);
                $appointmentReqData = $this->AppointmentRequest->find($apId);
                $addDate = $appointmentReqData->appointment_date;
            }
            $date = $this->holiday($addDate);
            $time = $request->time ? $request->time : null;
            $status = false;
            if($request->status){
                $status = true;
            }
            $appointmentTime = $this->nextAppointmentTime($date,$time,$status);
            $date = Carbon::parse($appointmentTime['date']);
            if($request->date){
                $addDate = Carbon::now();
                $addDate = $addDate->subDays(1);
            }
            $time = $appointmentTime['time'];
            $diff = $date->diffIndays($addDate) + ($request->date ? 0 : $request->day);
            $date = Carbon::parse($date)->format('Y-m-d');
            $status = !empty($appointmentTime['status']) ? $appointmentTime['status'] : null;
            return ['date'=>$date,'diff'=>$diff,'time'=>$time,'status'=>$status];

        }catch(Exception $e){
            abort(500);
        }
    }
    // check holiday for next appointment date
    private function checkHoliday($date){

        $holiday = $this->HolidayManager
            ->where('from_date','<=',$date)
            ->Where('to_date','>=',$date)
            ->first();
        if($holiday){
            $holidayaDate = $holiday->to_date;
            $diifDay =  Carbon::parse($date)->diffInDays($holidayaDate);
            return  $diifDay + 1;
        }
        return 0;
    }
    // check sunday for next appointment date
    private function checkSunday($date){
        $sunday = Carbon::parse($date)->format('D');
        if($sunday == 'Sun'){
            return false;
        }
        return true;
    }
    // final holiday function for next appointment date
    private function holiday($date){
        if($this->checkSunday($date)){
            $result = $this->checkHoliday($date);
            if($result){
                $date = Carbon::parse($date)->addDays($result);
                return $this->holiday($date);
            } else {
                return Carbon::parse($date);
            }
        } else {
            return $date;
            // $date = Carbon::parse($date)->addDays(1); //(day)
            // return $this->holiday($date);
        }
    }
    // next appointment store in appointment table
    public function nextAppointmentStore(Request $request){
        $this->validate($request,[
            'day' => 'required|numeric',
            'date' => 'required',
        ]);
        try{
            $appointmentDate = $request->date;
            $appointmentDay = Carbon::parse($appointmentDate)->format('D');
            $appointmentId = decrypt($request->appointmentId);
            $appointmentData = $this->Appointment->find($appointmentId);
            Session::flash('date',$appointmentDate);
            Session::flash('time',$request->time);
            Session::flash('appointmentId',$appointmentId);
            Session::flash('patientId',$appointmentData->patients_id);
            if($appointmentDay == 'Sun'){
                return ['main_status'=>2,'status'=>2];
            }
            $holiday = $this->HolidayManager->where('from_date','<=',$appointmentDate)->Where('to_date','>=',$appointmentDate)->first();
            if($holiday){
                return ['main_status'=>2,'status'=>3,'name'=>$holiday];
            }
            $date = $this->nextAppointmentData($request->all());
            $checkDate = explode('/',$date['date']);
            $checkDate = count($checkDate);
            if($checkDate == 1){
                $date['date'] = Carbon::parse($date['date'])->format('d/m/Y');
            }
            return ['status'=>1,'date'=>$date['date']];
        }catch(Exception $e){
            // dd($e);
            log::debug($e);
        }
    }

    /**
    * Get patients Last appointment
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function getPatientsLastAppoinment($patientsId){
        $nowDate = Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d H:i:s');
        // $nowDate = $nowDate.' '.'00:00:00';
        $lastAppointment = $this->Appointment->where('patients_id',$patientsId)
                                ->where(\DB::raw('concat(date," ",time)'),'>',$nowDate)
                                // ->whereDate('date','>=',$nowDate)
                                ->orderBy('id','DESC')
                                ->first();
        if($lastAppointment && $lastAppointment->date == date('Y-m-d') && !$lastAppointment->nextAppointmentDate() && $lastAppointment->arrival_time){
            $lastAppointment = false;
        }
        return['lastAppointment'=>$lastAppointment];
    }

    /**
    * Generate patient code
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function generateCode($patientsId, $name){
        $name = preg_replace('/\s+/', ' ', $name);
        $name = explode(' ', $name);
        $code = strtoupper($name[0][0]);

        $code .= (!empty($name[1])) ? strtoupper($name[1][0]) : 'R';
        $code .= (!empty($name[2])) ? strtoupper($name[2][0]) : 'R';

        $code .= $patientsId;
        $code = preg_replace('/[^A-Za-z0-9\-]/', 'R', $code);
        return ['code'=>$code];
    }

    /**
    * Return next appointment time
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function nextAppointmentTime($date,$time,$status=null){
        $maxTime = '20:00';
        if($status){
            $maxTime = '23:55';
        }
        $appointmentTime = $this->Appointment->where('date',$date);
        $today = Carbon::now()->format('Y-m-d');
        $hospitalTime = $this->appointmentTime('09:00', $maxTime, '5 mins');
        $hospitalTime = collect($hospitalTime)->map(function($q){
            return Carbon::parse($q)->format('H:i:s');
        })->toArray();
        if(Carbon::parse($date)->format('Y-m-d') == $today ){
            if(!empty($time) && $time < date('H:i:s')){
                $currentMinute = (ceil(date('i') / 5)) * 5;
                if(strlen($currentMinute) == 1){
                    $currentMinute = '0' . $currentMinute;
                }
                $time = date('H') . ':' . $currentMinute . ':00';
            }
            $appointmentTime = $appointmentTime->where('time','>',date('H:i:s'));
            $hospitalTime = collect($hospitalTime)->map(function($q){
                if($q > date('H:i:s')){
                    return $q;
                }
            })->toArray();
            $hospitalTime = array_filter($hospitalTime);
        }
        if($time != null && !$this->Appointment->where(['date'=>$date,'time'=>$time])->exists()){
            $hospitalTime = $this->appointmentTime('09:00', $maxTime, '5 mins');
            $time = array_search($time,$hospitalTime);
            return ['date'=>$date,'time'=>$time,'status'=>1];
        }
        $appointmentTime = $appointmentTime->orderBy('time','asc')->pluck('time')->toArray();
        $remaingTime = array_diff($hospitalTime,$appointmentTime);
        if(count($remaingTime) == 0){
            $date = Carbon::parse($date)->addDays(1);
            $checkHoliday = $this->holiday($date);
            return $this->nextAppointmentTime($checkHoliday,null);
        }else{
            $key = array_key_first($remaingTime);
            $date = Carbon::parse($date)->format('Y-m-d');
            return ['date'=>$date,'time'=>$key];
        }
    }

    /**
    * Store patient category
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    private function storePatientCategory($appointmentId,$categoryId,$patientsId) {
        $patientsCategory = $this->PatientsCategory;
        $patientsCategory->patients_id = $patientsId;
        $patientsCategory->appointment_id = $appointmentId;
        $patientsCategory->category_id = $categoryId;
        $patientsCategory->save();
        return true;
    }

    /**
    * Update ArrivalTime for appointment
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function updateArrivalTime(Request $request) {
        // dd($request->all());
        try {
            $id = decrypt($request->appointment_id);
        } catch (Exception $exception) {
            return [
                'status' => false,
                'message' => 'Invalid Appointment ID'
            ];
        }

        try {

            $updateArrivalTime = $this->Appointment->whereId($id)->update([
                'arrival_time' => Carbon::now()->format('H:i:s')
            ]);
            if ($updateArrivalTime) {
                return [
                    'status' => true,
                    'message' => 'Successfully updated arrival time'
                ];
            }

        } catch (Exception $exception) {
            log::debug($exception);
            return [
                'status' => false,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
    * Update Remark
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function updateRemark(Request $request){
        try{
            $appointmentId = decrypt($request->appointmet_id);
            $appointmentData = $this->Appointment->find($appointmentId);
            $appointmentData->visit_remark = $request->visit_remark;
            $appointmentData->remark = $request->remark;
            $appointmentData->save();
            return ['status'=>true];
        }catch(Exception $e){
            return [
                'status' => false,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
    * Update appointment time
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function updateTime(Request $request){
        $appointmentId = decrypt($request->appointment_id);
        $appointment = $this->Appointment->find($appointmentId);
        $hospitalTime = $this->appointmentTime('09:00', '20:00', '5 mins');
        $appointment->time = null;
        $appointment->arrival_time = null;
        if(($request->time || $request->time == 0) && $request->time != null){
            $appointment->time = Carbon::parse($hospitalTime[$request->time ? $request->time : 0])->format('H:i:s');
        }
        if(($request->arrival || $request->arrival == 0) && $request->arrival != null){
            $appointment->arrival_time = Carbon::parse($hospitalTime[$request->arrival ? $request->arrival : 0])->format('H:i:s');
        }
        if($request->date){
            $appointment->date = Carbon::parse($request->date)->format('Y-m-d');
        }
        $appointment->save();
        return ['status'=>true];
    }
    /**
    * Update appointment date and time
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function updateAppointmentDateAndTime(Request $request){
        try{
            $appointment = $this->Appointment->where('id',$request->appointment_id)->first();
            $appointmentDate = Carbon::parse($request->date)->format('Y-m-d');
            $appointmentDay = Carbon::parse($appointmentDate)->format('D');
            $isNext = 0;
            if($appointmentDay == 'Sun'){
                $isNext = 1;
            }
            $holiday = $this->HolidayManager->where('from_date','<=',$appointmentDate)->Where('to_date','>=',$appointmentDate)->first();
            if($holiday){
                $isNext = 1;
            }

            if($isNext == 1){
                $appointment = $this->Appointment->where('patients_id',$request->patient_id)->orderBy('id','DESC')->first();
                if($appointment){
                    $requestData = new \Illuminate\Http\Request();
                    $requestData->replace(['date' => $request->date]);
                    $appointmentTime = date('H:i:s');
                    $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                    if((!empty($nextAppontment['time']) || $nextAppontment['time'] == 0)){
                        $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                        $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                    }
                    $appointmentData['appointmentId'] = encrypt($appointment->id);
                    $appointmentData['date'] = $appointmentDate;
                    $appointmentData['time'] = $appointmentTime;
                    $nextAppointment = $this->nextAppointmentData($appointmentData);
                }
                $message = 'Appointment has been added!';
                Session::flash('msg', $message);
                return ['status'=>'1'];
            }

            $appointment->date = Carbon::parse($request->date)->format('Y-m-d');
            $appointment->time = !empty($request->time) ? Carbon::parse($request->time)->format('H:i:s') : null;
            $appointment->save();

            $message = 'Appointment has been added!';
            Session::flash('msg', $message);
            return ['status'=>'1'];
        }catch(Exception $e){
            log::debug($e);
        }
    }

    /**
    * Send OPD PDF to patients
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function sendOpd(Request $request,$appointmentId){
        try{
            $appointmentId = decrypt($appointmentId);
            $appointmentCharges = $this->AppointmentCharges
                                        ->whereAppointmentId($appointmentId)
                                        ->first();
            $checkDate = 'no';
            if($appointmentCharges && Carbon::parse($this->Appointment->where('id', $appointmentId)->value('date'))->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                $checkDate = 'yes';
            }
            $extraField1 = null;
            $extraField2 = null;

            if($appointmentCharges){
                $extraField = unserialize($appointmentCharges->extra_field);
                $extraField1 = $extraField[0];
                $extraField2 = $extraField[1];
                $appointmentCharges['is_submit_button'] = $checkDate;
            }
            $data['appointmentCharges'] = $appointmentCharges;
            $data['extraField1'] = $extraField1;
            $data['extraField2'] = $extraField2;
            $pdf = PDF::loadView('admin.appointment.printopd', $data);
            $pdfPath = 'public/pdf';
            if(!File::isDirectory($pdfPath)){
                File::makeDirectory($pdfPath, 0777, true, true);
            }
            $mobileNumber = $appointmentCharges->getAppointment->getPatientsDetails['mobile_number'];
            $pdfName = 'OPD-Invoice_'.date('Y-m-d-H-i-s').'_'.$appointmentId.'.pdf';
            if($pdf->save($pdfPath.'/'.$pdfName)){
            //code here to send opd
            //$this->uploadPdfToWassenger(url($pdfPath.'/'.$pdfName),$pdfPath.'/'.$pdfName,$mobileNumber);

            }
            // $this->sendOpdPdf(url('public/pdf/'.$pdfName),'8469360755');
            Session::flash('msg','OPD PDF successfully sent to patient');
            return back();
        }catch(Exception $e){
            abort(500);
            return ['status'=>0];
        }
    }

    public function storeReferenceDoctorPro($data){

    }
    /**
    * Return PopUp detailof patient as category wise
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getAppointmentPopUpDetail(Request $request)
    {
        try
        {

            $patients_id = decrypt($request->patients_id);
            $appoitmentDate = \Carbon\Carbon::parse($request->appoitmentDate)->format('Y-m-d');
            $opdPatient = $this->OpdPatients->find($patients_id);
            $data = '';
            $payment = '';
            $pMethod = ['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'];
            $charge = ['1'=>'Hormon','2'=>'IVF','3'=>'IUI'];
            $opdCollecions = $this->IndoorDeposit->where('patient_id',$patients_id)->get();
            $TotalAmount = 0;
            foreach($opdCollecions as $opdCollecion)
            {
                $payment_date = \Carbon\Carbon::parse($opdCollecion->created_at)->format('d-m-Y');
                if($opdCollecion->charge_type)
                {
                    $TotalAmount += $opdCollecion->amount;
                }
                $payment .= '<p>'.$payment_date.' | '.$opdCollecion->amount.' &#x20b9; | '.(!empty($opdCollecion->injection) ? $opdCollecion->injection : '').' | '.(isset($charge[$opdCollecion->charge_type]) ? $charge[$opdCollecion->charge_type] : '-') .' | '.(isset($pMethod[$opdCollecion->payment_type]) ? $pMethod[$opdCollecion->payment_type] : '-').' | '.(!empty($opdCollecion->remark) ? 'Remark : '.$opdCollecion->remark : '').'</p>';
            }
            if($request->category && in_array($request->category,[5,6,10,13]))
            {
                $report = '';
                $investigationReport = $this->allInvestigationReport();
                $investigationData = [];
                $investigationValueDetails = [];
                $investigationReport = $investigationReport['reportData'];
                $ancHistory = $this->AncHistory->where('patients_id','=',$patients_id)
                    ->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)
                    ->orderBy('id','desc')
                    ->first();
                    $ancId = !empty($ancHistory) ? $ancHistory->anc_id : null;    
                    if(!$ancHistory)
                    {
                        $ancHistory= $this->ANC->where('patients_id',$patients_id)->orderBy('created_at','desc')->first();
                        $ancId = !empty($ancHistory) ? $ancHistory->id : null;
                    }
                    if($ancHistory)
                    {
                        $investigation = !empty($ancHistory->investigation) ? json_decode($ancHistory->investigation) : null;
                        $data = !empty($investigation->investigation_data) ? $investigation->investigation_data : [];
                        if(isset($investigation->investigation_details))
                        {
                            $investigationValueData = (array)$investigation->investigation_details;
                            foreach($data as $key => $value){
                                if(!empty($investigationValueData[$value])){
                                    $investigationValueDetails[$investigationReport[$value]] = $investigationValueData[$value];
                                }else{
                                    $investigationData[] = $investigationReport[$value];
                                }
                            }
                        }
                    }
                    
                    $report = !empty($investigationData) ? implode(', ',$investigationData) : '';
                    $report .= !empty($investigation) && isset($investigation->investigation_extra) && !empty($investigation->investigation_extra) ? ', '.$investigation->investigation_extra : '';
                    $data = '<p><span class="font-bold candor-color">Advise Reports : </span>'.$report.'</p>
                        <button class="btn btn-primary preview-file" data-category="'.$request->category.'" data-date="'.$appoitmentDate.'" data-id="'.encrypt($ancId).'" data-patient = "'.$request->patients_id.'">Visit</button>';
            }
            if($request->category && in_array($request->category,[1,2]))
            {
                $report = '';
                $ivfId = null;
                $plan = '';
                $cycle_no = '';
                $currentHistory = $this->IvfHistory->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)->orderBy('id','desc')->first();
                if($currentHistory)
                {
                    $ivfData = !empty($currentHistory) ? json_decode($currentHistory->description) : null;
                    $report .= !empty($ivfData) && isset($ivfData->investigation_extra) && !empty($ivfData->investigation_extra) ? ', '.$ivfData->investigation_extra : '';
                    $plan = $currentHistory->plan;
                    $cycle_no = $currentHistory->cycle_no;
                }
                $ivfFirst = $this->IVF->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)->first();
                if(!$ivfFirst)
                {
                    $investigation = !empty($ivfFirst) ? json_decode($ivfFirst->investigation) : null;
                    $report .= !empty($investigation) && isset($investigation->investigation_extra) && !empty($investigation->investigation_extra) ? ', '.$investigation->investigation_extra : '';
                }
                $isExtraVisit = $this->IvfExtraVisit->where('patient_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)->orderBy('id','desc')->first();
                $extraVisit = !empty($isExtraVisit) ? '1' : '';
                if($isExtraVisit)
                {
                    $extraOeData = json_decode($isExtraVisit->oe);
                    $report .= !empty($extraOeData) && isset($extraOeData->investigation_extra) ? $extraOeData->investigation_extra : '';
                }
                $package = $this->IvfPayment->where('patients_id',$patients_id)->orderBy('id','desc')->first();
                $data = '<p><span class="font-bold candor-color">Advise Reports : </span>'.$report.'</p>
                        <p><span class="font-bold candor-color">Package: </span>'.(!empty($package) ? $package->package : '-').'</p>
                        <p><span class="font-bold candor-color">Due Amount: </span>'.(!empty($package->package) ? ($package->package - $TotalAmount) : '-').'</p>
                        <p><span class="font-bold candor-color">Package Condition: </span>'.(!empty($package) ? $package->condition : '-').'</p>
                        <p><span class="font-bold candor-color">Package Remark: </span>'.(!empty($package) ? $package->remark : '-').'</p>
                        <p><span class="font-bold candor-color">Payment : </span></p>';
                $data .= $payment;   
                $data .= '<button class="btn btn-primary preview-file" data-plan="'.$plan.'" data-cycleno="'.$cycle_no.'" data-extravisit="'.$extraVisit.'" data-category="'.$request->category.'" data-date="'.$appoitmentDate.'" data-id="" data-patient = "'.$request->patients_id.'">Visit</button>';
            }
            if($request->category && in_array($request->category,[3,4]))
            {
                $report = '';
                $cycle_no = '';
                $currentHistory = $this->IuiHistory->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)->first();
                if($currentHistory)
                {
                    $cycle_no = $currentHistory->cycle_no;
                    $ivfData = !empty($currentHistory) ? json_decode($currentHistory->description) : null;
                    $report .= !empty($ivfData) && isset($ivfData->investigation_extra) && !empty($ivfData->investigation_extra) ? ', '.$ivfData->investigation_extra : '';
                }
                $iuiFirst = $this->IUI->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)->first();
                if($iuiFirst)
                {
                    $cycle_no = $iuiFirst->cycle_no;
                    $investigation = !empty($iuiFirst) ? json_decode($iuiFirst->investigation) : null;
                    $report .= !empty($investigation) && isset($investigation->investigation_extra) && !empty($investigation->investigation_extra) ? ', '.$investigation->investigation_extra : '';
                }
                $package = $this->IvfPayment->where('patients_id',$patients_id)->orderBy('id','desc')->first();
                $isExtraVisit = $this->IuiExtraVisit->where('patient_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',$appoitmentDate)->orderBy('id','desc')->first();
                $extraVisit = !empty($isExtraVisit) ? '1' : '';
                if($isExtraVisit)
                {
                    $extraOeData = json_decode($isExtraVisit->oe);
                    $report .= !empty($extraOeData) && isset($extraOeData->investigation_extra) ? $extraOeData->investigation_extra : '';
                }
                $data = '<p><span class="font-bold candor-color">Advise Reports : </span>'.$report.'</p>
                        <p><span class="font-bold candor-color">Payment : </span></p>';
                $data .= $payment;   
                $data .= '<button class="btn btn-primary preview-file" data-cycleno="'.$cycle_no.'" data-category="'.$request->category.'" data-date="'.$appoitmentDate.'" data-id="" data-patient = "'.$request->patients_id.'">Visit</button>';

            }
            return response()->json([
                'status'=>1,
                'data' => $data
            ]);  
        }
        catch(Exception $e)
        {
            log::Debug($e);
        }
    }
}
