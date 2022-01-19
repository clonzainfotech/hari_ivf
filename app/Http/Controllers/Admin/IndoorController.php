<?php

namespace App\Http\Controllers\Admin;


use App\Models\IndoorRoom;
use http\Env\Response;
use Log;
use Auth;
use View;
use Session;
use Exception;
use Validator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Support\Facades\Redirect;
use File;
use PDF;

class IndoorController extends AdminController
{
    /**
    * Return on indoor index page
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function index() {
        try{
            $indoorData = $this->IndoorBook;
            $indoorTypes = $this->IndoorType->where('status',1)->get();
            $indoorType = $this->IndoorType->where('status',1)->pluck('name', 'id');
            $typeId = $this->IndoorType->pluck('id');
            $patientDetail = [];
            foreach ($typeId as $type) {
                $indoorBed[$type] = $this->IndoorBed->where(function($query) use($type) {
                    $query->whereHas('room', function($query) use($type) {
                        $query->where([
                            ['type_id', '=', $type],
                            ['status', '=', 1],
                        ]);
                    });
                })->where([
                    ['flag', '=', 0],
                    ['status', '=', 1]
                ])->count();

                $bookingPatients = $this->IndoorBook
                    ->with([
                        'getPatientsDetails'
                    ])
                    ->where('type_id', $type)
                    ->where('is_direct_discharge',0)
                    ->get();

                $ptcount = $bookingPatients->count();
                $ptcount = 0;
                if($bookingPatients->count() > 0) {
                    $ptcount = $bookingPatients->count();
                }
                $ptdata = [];

                foreach($bookingPatients as $key => $bookingPatient) {
                    $ptdata[$key] = $bookingPatient;
                    $ptdata[$key]['procedure_name'] = implode(', ', $this->IndoorProcedure
                        ->whereIn('id', explode(',', $bookingPatient->procedure_id))
                        ->pluck('name')
                        ->toArray());
                }

                $patientDetail[$type]['data'] = $ptdata;
                $patientDetail[$type]['count'] = $ptcount;
            }
            $proceduresId = $this->IndoorProcedure->pluck('name','id');
            return view('admin.indoor.index',compact('indoorTypes','indoorType', 'patientDetail','indoorBed','ptcount','proceduresId'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on indoor index page
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function create($typeid, $id = null)
    {
        try {
            $roomTypeId = decrypt($typeid);
            $roomId = decrypt($id);
            $roomData = $this->IndoorRoom->find($roomId);
            $roomtype = $this->IndoorType->where('id',$roomTypeId)->pluck('name')->first();
            $patients = $this->getPatients();
            $patientcode = $this->getPatientscode();
            $city = $this->City->pluck('name','name');
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $doctor = $this->getDoctor();
            $referenceDoctor = ['other'=>'Other'] + $doctor['referenceDoctor']->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $state = $this->getState()['state'];
            $procedures = $this->IndoorProcedure->pluck('name','id');
            $indoorRoom = $this->IndoorRoom->where('type_id',$roomTypeId)->where('flag',0)->whereNotNull('room_no')->pluck('room_no','id')->toArray();
            // $bedid = $this->IndoorBed->where('flag',0)->where('status',1)->get('room_id');
            // $indoorRoom = $this->IndoorRoom->where(function($query) use($roomTypeId) {
            //     $query->whereHas('Beds', function($query) use($roomTypeId) {
            //         $query->where([
            //             ['flag', '=', 0],
            //             ['status', '=', 1]
            //         ]);
            //     });
            // })
            // ->where([
            //     ['type_id', '=', $roomTypeId],
            //     ['status', '=', 1],
            // ])
            // ->pluck('room_no', 'id');
            // dd($indoorRoom);
            $indoorBed['indoor_bed'] = $this->IndoorBed->where(function($query) use($roomTypeId) {
                $query->whereHas('room', function($query) use($roomTypeId) {
                    $query->where([
                        ['type_id', '=', $roomTypeId],
                        ['status', '=', 1],
                    ]);
                });
            })->where([
                ['flag', '=', 0],
                ['status', '=', 1]

            ])->pluck('bed_no', 'bed_no');
            // if ($id) {
            //     try {
            //         $decryptedId = decrypt($id);
            //         $patientData = $this->OpdPatients->where('id', $decryptedId)->get()->first();
            //         $appointmentdata = $this->Appointment->where('patients_id',$decryptedId)->get();
            //         return view('admin.indoor.create',compact('rooms','roomTypeId','patientcode','roomtype','patientData','appointmentdata','roomtypes', 'category','referenceDoctor','hospitalDoctor','state','procedures','city','indoorroom', 'indoorBed','patients'));
            //     } catch (Exception $exception) {
            //         return redirect('patient');
            //     }
            // }
            return view('admin.indoor.create',compact('roomTypeId','roomData','patientcode','roomtype','category','referenceDoctor','hospitalDoctor','state','city','procedures','indoorRoom', 'indoorBed','patients'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Get Indoor bed list
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getIndoorBeds(Request $request) {
        $typeId = $request->type_id;
        $roomId = $request->room_id;
        $indoorBed = $this->IndoorBed->where(function($query) use ($typeId) {
            $query->whereHas('room', function($query) use ($typeId) {
                $query->where([
                    ['type_id', '=', $typeId],
                    ['status', '=', 1]
                ]);
            });
        })
        ->where([
            ['flag', '=', 0],
            ['status', '=', 1],
            ['room_id', '=', $roomId],
        ])
        ->pluck('bed_no', 'id');
        return $indoorBed;
    }

    /**
    * Get Indoor Room
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getIndoorRooms(Request $request) {
        $typeId = $request->type_id;
        // $indoor['rooms'] = $this->IndoorBed->where(function($query) use ($typeId) {
        //     $query->whereHas('room', function($query) use ($typeId) {
        //         $query->where([
        //             ['type_id', '=', $typeId],
        //             ['status', '=', 1]
        //         ]);
        //     });
        // })
        // ->where([
        //     ['flag', '=', 0],
        //     ['status', '=', 1],
        // ])
        // ->pluck('room_id', 'room_id');
        $indoor['rooms'] = $this->IndoorRoom->where('type_id',$typeId)->where('status',1)->where('flag',0)->pluck('room_no','id');
        $indoor['beds'] = $this->IndoorBed->where(function($query) use ($typeId) {
            $query->whereHas('room', function($query) use ($typeId) {
                $query->where([
                    ['type_id', '=', $typeId],
                    ['status', '=', 1]
                ]);
            });
        })
        ->where([
            ['flag', '=', 0],
            ['status', '=', 1],
        ])
        ->pluck('bed_no', 'id');

        return $indoor;
    }
    /**
    * Store indoor data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store($id, Request $request) {
        try{
            $patientsId = $this->OpdPatients->whereCode($request->code)->value('id');

            if ($patientsId == null) {
                return redirect()->back()
                    ->withErrors([
                        'patient' => 'This patient is not exists'
                    ])
                    ->withInput();
            }

            $indoorBooks = $this->IndoorBook
                ->wherePatientId($patientsId)
                ->where('is_direct_discharge',0)
                ->orderBy('doa_date', 'DESC')
                ->first();

            if ($indoorBooks != null && $indoorBooks->is_discharged_card == 0) {
                Session::flash('msg', "You can't add new room for this patient because this patient has already room !");
                return back()->withInput();
            }

            $lastArray = $this->storeprocedure($request->pro);
            $storeNewProcedure = array_values($lastArray);
            // $roomId = $this->IndoorRoom->where('id',$request->room)->value('room_no');
            $price = $this->IndoorType->where('id',$id)->value('price');
            $indoorbookdata = $this->IndoorBook;
            $indoorbookdata->patient_id = $patientsId;
            $indoorbookdata->type_id = $id;
            $indoorbookdata->created_by = Auth::user()->id;
            $indoorbookdata->room_id = $request->room;
            $indoorbookdata->is_pediatric_patient = $request->is_pediatric_patient;
            $indoorbookdata->is_medicare_patient = $request->is_medicare_patient;
            // $roomBedId = $request->room_bed;
            // if ($request->indoor_bed == null) {

            //     if (!empty($request->room)) {
            //         $indoorBed = $this->IndoorBed->where('room_id', $request->room)->first();
            //         $indoorbookdata->room_id = $roomId;
            //         $roomBedId = $indoorBed->id;
            //     } else {
            //         $indoorBed = $this->IndoorBed
            //             ->where([
            //                 ['flag', '=', 0],
            //                 ['status', '=', 1],
            //             ])
            //             ->where(function($query) use($id) {
            //                 $query->whereHas('room', function($query) use($id) {
            //                     $query->where([
            //                         ['type_id', '=', $id],
            //                         ['status', '=', 1]
            //                     ]);
            //                 });
            //             })
            //             ->first();
            //         $indoorbookdata->room_id = $indoorBed->room['room_no'];
            //         $roomBedId = $indoorBed->id;
            //     }
            // }
            // $indoorbookdata->bed_id = $roomBedId;
            $ldate = date('Y-m-d');
            $indoorbookdata->doa_date = Carbon::parse($request->date)->format('Y-m-d');
            $indoorbookdata->dod_date = (!empty($request->doddate)) ? Carbon::parse($request->doddate)->format('Y-m-d') : null;
            $indoorbookdata->admit_time = $request->admissiontime ? Carbon::parse($request->admissiontime)->format('H:i:s') : null;
            $indoorbookdata->discharge_time = $request->dischargetime ? Carbon::parse($request->dischargetime)->format('H:i:s') : null;
            $indoorbookdata->price = $price;
            $indoorbookdata->remark = $request->remark;
            $indoorbookdata->procedure_id = json_encode($request->pro);
            $indoorbookdata->save();
            $produreData = $request->pro;
            $prodataids = '';
            if(is_array($produreData))
            {
                if(is_array($produreData['pro_type']))
                {
                    $procedureArray = $this->IndoorProcedure->pluck('id','id')->toArray();
                    $proArray = array_values($produreData['pro_type']);
                    $prokeys = array_intersect($procedureArray, $proArray);
                    if(count($lastArray) >0){
                        $prokeys = array_merge($prokeys, $storeNewProcedure);
                    }
                    $prodataids = implode(',', $prokeys);
                }
            }

            $indoorbookdata->procedure_id = $prodataids;
            $indoorbookdata->save();
            $this->SmsManager->sendRoomRegistration($indoorbookdata->id);
            //update indoor_beds, set flag 0 to 1
            $iRoom = $this->IndoorRoom->where('id', $request->room)->first();
            $iRoom->flag = 1;
            $iRoom->save();
            return redirect('indoor');
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on indoor book-edit page 
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function bookingEdit($id, Request $request) {
        try {
            $bookingid = decrypt($id);
        } catch (Exception $exception) {
            return redirect('indoor');
        }
        try {
            $patientid = $this->IndoorBook->where('id', $bookingid)->pluck('patient_id')->first();
            $city = $this->City->pluck('name','name');
            $appointment = $this->Appointment
                                ->with('getPatientsDetails')
                                ->where('patients_id',$patientid)
                                ->first();
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $doctor = $this->getDoctor();
            $referenceDoctor = ['other' => 'Other'] + $doctor['referenceDoctor']->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $state = $this->getState()['state'];
            $typeId = $this->IndoorBook->where('id',$bookingid)->pluck('type_id')->first();
            $roomTypes = $this->IndoorType
                ->where([
                    ['status', '=', 1],
                ])
                // ->where(function($query) {
                //     $query->whereHas('Rooms', function($query) {
                //             $query->where([
                //                 ['flag', '=', 0],
                //                 ['status', '=', 1],
                //             ]);
                //     });
                // })
                ->pluck('name', 'id');
            $bookingdata = $this->IndoorBook->with('getRoomBed')->where('id',$bookingid)->first();
            $bookingdata['procedure_id'] = $bookingdata->procedure_id;
            $procedureData = explode(",",$bookingdata['procedure_id']);
            $procedures = $this->IndoorProcedure->pluck('name','id');
            $indoorRoom = $this->IndoorRoom
                ->where([
                    ['type_id', '=', $typeId],
                    ['status', '=', 1],
                ])
                ->pluck('room_no', 'id');
            // $indoorRoom = [$bookingdata->room_id => $bookingdata->room_id] + $indoorRoom->toArray();

            // $indoorBed = $this->IndoorBed->where(function($query) use ($typeId) {
            //     $query->whereHas('room', function($query) use ($typeId) {
            //         $query->where([
            //             ['status', '=', 1],
            //             ['type_id', '=', $typeId],
            //         ]);
            //     });
            // })
            // ->where([
            //     ['flag', '=', 0],
            //     ['status', '=', 1],
            // ])
            // ->pluck('bed_no', 'id');

            // $indoorBed = [$bookingdata->bed_id => $bookingdata->getRoomBed->bed_no] + $indoorBed->toArray();
            // $bedid = $this->IndoorBed->where('flag',1)->where('status',1)->get('room_id');
            $patients = $this->getPatients();
            $patientcode = $this->getPatientscode();
            return view('admin.indoor.edit',compact('patientid','category','referenceDoctor','hospitalDoctor','state','city','appointment','roomTypes','indoorRoom','bookingdata','procedures','procedureData', 'typeId','patients','patientcode'));
        } catch (Exception $exception) {
            abort(500);
        }
    }

    /**
    * Return on indoor edit page
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function edit($id,Request $request){
        try{
            if($request->ajax()){
                $patients = $this->OpdPatients
                    ->with('lastAppointmentData')
                    ->whereCode($id)
                    ->first();
                if(!$patients){
                    $patients = $this->OpdPatients
                    ->with('lastAppointmentData')
                    ->whereId($id)
                    ->first();
                }
                if(!$patients){
                    $patients = $this->OpdPatients
                    ->with('lastAppointmentData')
                    ->whereMobileNumber($id)
                    ->first();
                }

                $appointmentId = null;
                if (!empty($patients)) {
                    $appointmentId = $patients->last_appointment_data['id'];
                    $type = 'code';
                }

            }
            else {
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
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $doctor = $this->getDoctor();
            $referenceDoctor = ['other' => 'Other'] + $doctor['referenceDoctor']->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $state = $this->getState()['state'];
            $patientsData = $this->getPatients();
            $patientcode = $this->getPatientscode();
            if($request->ajax()){
                $data = [];
                $data['patients'] = $patients;
                return $data;
            }
            return view('admin.indoor.edit',compact('appointment','category','referenceDoctor','hospitalDoctor','state','city','patientsData','patientcode'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Update indoor book detail
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function bookingUpdate($id,Request $request) {
        try{
            $lastArray = $this->storeprocedure($request->pro);
            $storeNewProcedure = array_values($lastArray);

            $bookupdatedata = $this->IndoorBook
                ->with('getRoomBed')
                ->where('id', $id)
                ->first();

            if(($request->type_id != $bookupdatedata->type_id) || ($request->room != $bookupdatedata->room_id)) {
                // remove flag = 1 of old allocate room or bed
                $indoorBed = $this->IndoorRoom->whereId($bookupdatedata->room_id)->first();
                $indoorBed->flag = 0;
                $indoorBed->save();
                // change flag of new allocated room or bed
                $indoorBed = $this->IndoorRoom->whereId($request->room)->first();
                $indoorBed->flag = 1;
                $indoorBed->save();
            }
            // dd('ok');
            $bookupdatedata->type_id = $request->room_type;
            $bookupdatedata->created_by = Auth::user()->id;
            // $roomId = $this->IndoorRoom->where('id',$request->room)->value('room_no');
            $bookupdatedata->room_id = $request->room;
            // $bookupdatedata->bed_id = $request->room_bed;
            $bookupdatedata->doa_date = Carbon::parse($request->date)->format('Y-m-d');
            $ldate = date('Y-m-d');
            $bookupdatedata->dod_date = (!empty($request->doddate)) ? Carbon::parse($request->doddate)->format('Y-m-d') : null;
            $bookupdatedata->admit_time = $request->admissiontime ? Carbon::parse($request->admissiontime)->format('H:i:s') : null;
            $bookupdatedata->discharge_time = $request->dischargetime ? Carbon::parse($request->dischargetime)->format('H:i:s') : null;
            $bookupdatedata->price = $this->IndoorType->whereId($request->room_type)->value('price');
            $bookupdatedata->remark = $request->remark;
            $bookupdatedata->is_pediatric_patient = $request->is_pediatric_patient;
            $bookupdatedata->is_medicare_patient = $request->is_medicare_patient;
            $produreData = $request->pro;
            $prodataids = '';
            if(is_array($produreData))
            {
                if(is_array($produreData['pro_type']))
                {
                    $procedureArray = $this->IndoorProcedure->pluck('id','id')->toArray();
                    $proArray = array_values($produreData['pro_type']);
                    $prokeys = array_intersect($procedureArray, $proArray);
                    if(count($lastArray) >0) {
                        $prokeys = array_merge($prokeys, $storeNewProcedure);
                    }
                    $prodataids = implode(',', $prokeys);
                }
            }
            $bookupdatedata->procedure_id = $prodataids;
            $bookupdatedata->save();
            return redirect('indoor');
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on create discharge page
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function dischargeCreate($id) {
        try{
            $bookingId = decrypt($id);

            $doctor = $this->getDoctor();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            foreach ($hospitalDoctor as $inchargedoctor) {}
            $medicines = $this->Medicine->pluck('name','name');
            $surgicalNotes = $this->SurgicalNote->pluck('name','name');
            $treatments = $this->GivenTreatments->pluck('name','name');
            $diagnosis = $this->Diagnosis->pluck('name','name');
            $complaint = $this->DischargeComplaint->where('status',1)->pluck('name','name')->toArray();

            if ($bookingId) {
                try {
                    $bookedPatientData = $this->IndoorBook
                        ->with('getPatientsDetails')
                        ->where('id', $bookingId)
                        ->first();

                    $dodDate = $bookedPatientData->dod_date;

                    if(!empty($dodDate)) {
                        $dischargeDate =Carbon::parse($dodDate)->format('Y-m-d');
                    }
                    else {
                        $dischargeDate = '';
                    }
                    $bednumber = $this->IndoorBook->with('getRoomBed')->where('id', $bookingId)->first();
                    $gender = ($bookedPatientData->getPatientsDetails['gender'] == 2) ? 'Female' : 'Male';
                        return view('admin.indoor.dischargecard',compact('surgicalNotes','complaint','medicines','bednumber','inchargedoctor','gender','bookedPatientData','dischargeDate','treatments','diagnosis'));

                } catch (Exception $exception) {
                    return redirect('indoor');
                }
            }
            return view('admin.indoor.dischargecard',compact('surgicalNotes','patientData','medicines','hospitalDoctor','gender'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    /**
    * Store discharge detail
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function dischargeStore($id, Request $request) {
        try {
            $bookingId = decrypt($id);
            $this->medicineData2($request->treatment);
            $this->giventratment($request->treatments);
            $this->diagnosisData($request->diagnosis);
            $dischargeData = $this->IndoorDischargeCard;
            $dischargeData->booking_id = $bookingId;
            $dischargeData->created_by = Auth::user()->id;
            $dischargeData->dos_date = $request->surgerydate ? Carbon::parse($request->surgerydate)->format('Y-m-d') : null;
            $ldate = date('Y-m-d');
            // $dischargeData->dod_date = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;
            $dodDate = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;

            $diagnisises = null;
            $diagnosis = $request->diagnosis;
            if(is_array($diagnosis))
            {
                if(is_array($diagnosis['diagnosis']))
                {
                    $diagnosisArray = array_values($diagnosis['diagnosis']);
                    $diagnisises = implode(',', $diagnosisArray);
                }
            }

            $dischargeData->diagnosis = $diagnisises;
            $given_treatment = null;
            $givenTreatment = $request->treatments;
            if(is_array($givenTreatment))
            {
                if(is_array($givenTreatment['treatments']))
                {
                    $treatmentArray = array_values($givenTreatment['treatments']);
                    $given_treatment = implode(',', $treatmentArray);
                }
            }
            $dischargeData->treatment_given = $given_treatment;
            $dischargeData->hpe = $request->hpe;
            $rx_treatment = null;
            $rxtreatment = $request->treatment;
            if(is_array($rxtreatment))
            {
                if(is_array($rxtreatment['medicinedata']))
                {
                    $treatmentArray = array_values($rxtreatment['medicinedata']);
                    $rx_treatment = implode(',', $treatmentArray);
                }
            }
            $complaintData = null;
            if(!empty($request->complaints)){
                $complaintData = implode(',',$request->complaints);
                $this->storeDischargeComplaint($request->complaints);
            }
            $dischargeData->rx_treatment = $rx_treatment;
            $dischargeData->admission_vitals = $request->vitals;
            $dischargeData->clinical_summary = $request->clinicalsummary;
            $dischargeData->vital_on_discharge = $request->dischargevital;
            $dischargeData->cond_on_discharge = $request->condition;
            $dischargeData->summary = $request->summary;
            $dischargeData->surgical_note = $request->surgicalnote;
            $dischargeData->report = $request->report;
            $dischargeData->complaints = $complaintData;
            $dischargeData->examination = $request->examination;
            $birthCertificate = null;
            $birthImage = null;
            if(!empty($request->birth_certificate['image'])){
                foreach($request->birth_certificate['image'] as $key=>$row){
                    $name = $this->uploadImage($row, 'public/upload/anc/report/');
                    $birthImage = 'public/upload/anc/report/' . $name;
                }
                $birthCertificate['image'] = $birthImage;
            }
            else{
                $birthCertificate['image'] = $birthImage;
            }
            $birthCertificate['remark'] = $request->birth_certificate['remark'];
            $dischargeData->birth_certificate = json_encode($birthCertificate);
            $dischargeData->follow_up = $request->followup;
            $dischargeData->followup_date = Carbon::parse($request->followdate)->format('Y-m-d');
            $dischargeData->save();
            $admitTime = $request->admissiontime ? Carbon::parse($request->admissiontime)->format('H:i:s') : null;
            $dischargeTime = $request->dischargetime ? Carbon::parse($request->dischargetime)->format('H:i:s') : null;
            $this->IndoorBook->whereId($bookingId)->update(['is_discharge_card' => 1,'dod_date' => $dodDate,'admit_time'=>$admitTime,'discharge_time'=>$dischargeTime]);
            $iBook = $this->IndoorBook->find($bookingId);
            if(!empty($request->followdate)){
                $followupDate = $request->followdate;
                $followDate = date('Y-m-d',strtotime($followupDate));
                $appointmentTime = null;
                $fDate = !empty($followDate) && $followDate >= Carbon::now()->format('Y-m-d')  ? Carbon::parse($followDate)->format('Y-m-d') : null;
                if($fDate){
                    $requestData = new \Illuminate\Http\Request();
                    $requestData->replace(['date' => $fDate,'status'=>true]);
                    $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                    if(!empty($nextAppontment['time']) || $nextAppontment['time'] == 0){
                        $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                        $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                        $followDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $followDate;
                    }
                }
                
                $appointment = $this->Appointment->where('patients_id',$iBook->patient_id)->orderBy('id','DESC')->first();
                if($appointment){
                    $procedures = $this->IndoorProcedure->pluck('name', 'id');
                    $appointmentData['appointmentId'] = encrypt($appointment->id);
                    $appointmentData['date'] = $followDate;
                    $appointmentData['time'] = $appointmentTime;
                    $appointmentData['is_procedure'] = 0;
                    $appointmentData['remark'] = isset($procedures[$iBook->procedure_id]) ? $procedures[$iBook->procedure_id].' follow up' : null;
                    $nextAppointment = $this->nextAppointmentData($appointmentData);
                }
            }
            $pId = explode(',',$iBook->procedure_id);
            if($dodDate && (in_array('1',$pId) || in_array('2',$pId))){
                // $this->SmsManager::sendDischargeCardToRefDoctor($bookingId);
            }
            if($request->ajax()) {
                if ($request->isprint) {
                    $discharge = $this->IndoorDischargeCard
                        ->whereId($dischargeData->id)
                        ->first();

                    return response()->json([
                        'status' => 1,
                        'data' => View::make('admin.indoor.discharge_preview', compact('discharge'))->render()
                    ]);
                }
                return response()->json([
                    'status' => 0,
                    'discharge' => $dischargeData
                ]);
            }
            return redirect('indoor');
        } catch (Exception $exception) {
            log::debug($exception);
            abort(500);
        }
    }

    /**
    * Return on eit discharge page
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function dischargeEdit($id, Request $request) {
        try {
            $bookingid = decrypt($id);
            $dischargedata = $this->IndoorDischargeCard->where('booking_id',$bookingid)->first();
            $discharge['diagnosises'] = !empty($dischargedata->diagnosis) ? $dischargedata->diagnosis : null;
            $diagnosisData = explode(",",$discharge['diagnosises']);
            $diagnosis = $this->Diagnosis->pluck('name','name');
            $discharge['treatment_given'] = $dischargedata->treatment_given;
            $giventreatmentData = explode(",",$discharge['treatment_given']);
            $givenTreatment = $this->GivenTreatments->pluck('name','name');
            $surgicalNotes = $this->SurgicalNote->pluck('name','name');
            $dischargedata['rx_treatment'] = $dischargedata->rx_treatment;
            $treatmentData = explode(",",$dischargedata['rx_treatment']);
            $medicines = $this->Medicine->pluck('name','name');
            $complaint = $this->DischargeComplaint->where('status',1)->pluck('name','name')->toArray();
            $doctor = $this->getDoctor();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            foreach ($hospitalDoctor as $inchargedoctor) {}
            $patientid = $this->IndoorBook->where('id',$bookingid)->pluck('patient_id')->first();
            $patientdata = $this->OpdPatients->with('getAppointment')->where('id',$patientid)->first();
            $gender = ($patientdata->gender == 2) ? 'Female' : 'Male';
            $bookpatientdata = $this->IndoorBook->where('patient_id',$patientid)->first();
            $bookid = $bookpatientdata->id;
            // $bedid = $this->IndoorBook->where('id',$bookid)->pluck('bed_id')->first();
            $bedNumber = $this->IndoorBook->with('getRoomBed')->where('id', $bookingid)->first();
            $birth_certificate = !empty($dischargedata->birth_certificate) ? json_decode($dischargedata->birth_certificate) : null;
            $birthImages = !empty($birth_certificate->image) ? (array)$birth_certificate->image : [];
            $birthImagesData = [];
            if($birthImages){
                foreach($birthImages as $key=>$row){
                    $birthImagesData[$key]['id'] = $key;
                    $birthImagesData[$key]['src'] = url($row);
                }
            }
            $birthImagesData = json_encode($birthImagesData,true);
            return view('admin.indoor.dischargeedit',compact('surgicalNotes','complaint','dischargedata','patientdata','bookpatientdata','bedNumber','inchargedoctor','medicines','gender','treatmentData','givenTreatment','giventreatmentData','diagnosisData','diagnosis','birthImagesData'));
        } catch (Exception $exception) {
            abort(500);
        }
    }

    /**
    * Update discharge detail
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function dischargeUpdate($id,Request $request) {
        try {
            $this->medicineData2($request->treatment);
            $this->giventratment($request->treatments);
            $this->diagnosisData($request->diagnosis);

            $discarddata = $this->IndoorDischargeCard->find(decrypt($id));
            $checkDate = $discarddata->dod_date;
            $birth_certificate = !empty($discarddata->birth_certificate) ? json_decode($discarddata->birth_certificate)->image : null;
            $discarddata->dos_date = $request->surgerydate ? Carbon::parse($request->surgerydate)->format('Y-m-d') : null;
            $ldate = date('Y-m-d');
            // $discarddata->dod_date = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;
            $dodDate = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;
            $diagnosis = $request->diagnosis;
            $diagnosises = null;
            $given_treatment = null;
            $rx_treatment = null;
            if(is_array($diagnosis))
            {
                if(is_array($diagnosis['diagnosis']))
                {
                    $diagnosisArray = array_values($diagnosis['diagnosis']);
                    $diagnosises = implode(',', $diagnosisArray);
                }
            }

            $discarddata->diagnosis = $diagnosises;
            $givenTreatment = $request->treatments;
            if(is_array($givenTreatment))
            {
                if(is_array($givenTreatment['treatments']))
                {
                    $treatmentArray = array_values($givenTreatment['treatments']);
                    $given_treatment = implode(',', $treatmentArray);
                }
            }

            $discarddata->treatment_given = $given_treatment;
            $discarddata->hpe = $request->hpe;
            $rxtreatment = $request->treatment;
            if(is_array($rxtreatment))
            {
                if(is_array($rxtreatment['medicinedata']))
                {
                    $treatmentArray = array_values($rxtreatment['medicinedata']);
                    $rx_treatment = implode(',', $treatmentArray);
                }
            }
            $complaintData = null;
            if(!empty($request->complaints)){
                $complaintData = implode(',',$request->complaints);
                $this->storeDischargeComplaint($request->complaints);
            }
            $discarddata->created_by = Auth::user()->id;
            $discarddata->rx_treatment = $rx_treatment;
            $discarddata->admission_vitals = $request->vitals;
            $discarddata->clinical_summary = $request->clinicalsummary;
            $discarddata->vital_on_discharge = $request->dischargevital;
            $discarddata->cond_on_discharge = $request->condition;
            $discarddata->summary = $request->summary;
            $discarddata->surgical_note = $request->surgicalnote;
            $discarddata->report = $request->report;
            $discarddata->complaints = $complaintData;
            $discarddata->examination = $request->examination;
            $birthCertificate = null;
            $birthImage = null;
            if(!empty($request->birth_certificate['image'])){
                foreach($request->birth_certificate['image'] as $key=>$row){
                    $name = $this->uploadImage($row, 'public/upload/anc/report/');
                    $birthImage = 'public/upload/anc/report/' . $name;
                }
                $birthCertificate['image'] = $birthImage;
            }
            else{
                $birthCertificate['image'] = $birth_certificate;
            }
            $birthCertificate['remark'] = $request->birth_certificate['remark'];
            $discarddata->birth_certificate = json_encode($birthCertificate);
            $discarddata->follow_up = $request->followup;
            $discarddata->followup_date = $request->followdate ? Carbon::parse($request->followdate)->format('Y-m-d') : null;
            $discarddata->save();

            $discharge = $this->IndoorDischargeCard->where('id', decrypt($id))->first();
            $deschargeDate = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;
            if(($checkDate != $deschargeDate) && $discarddata->dod_date){
                // $this->SmsManager::sendDischargeCardToRefDoctor($discharge->booking_id);
            }
            $admitTime = $request->admissiontime ? Carbon::parse($request->admissiontime)->format('H:i:s') : null;
            $dischargeTime = $request->dischargetime ? Carbon::parse($request->dischargetime)->format('H:i:s') : null;
            $this->IndoorBook->where('id',$discharge->booking_id)->update(['dod_date' => $dodDate,'admit_time'=>$admitTime,'discharge_time'=>$dischargeTime]);

            if($request->ajax()) {
                if ($request->isprint) {
                    $discharge = $this->IndoorDischargeCard
                        ->whereId(decrypt($id))
                        ->first();

                    return response()->json([
                        'status' => 1,
                        'data' => View::make('admin.indoor.discharge_preview', compact('discharge'))->render()
                    ]);
                }
                return response()->json([
                    'status' => 0,
                    'discharge' => $discharge
                ]);
            }
        return redirect('indoor');
        } catch (Exception $exception) {
            log::debug($exception);
            abort(500);
        }
    }

    /**
    * Return on invoice create  page
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function invoiceCreate($id)
    {
        try{
            $bookingid = decrypt($id);
            if ($bookingid) {
                $bookdata = $this->IndoorBook
                    ->with('getPatientsDetails')
                    ->where('id',$bookingid)
                    ->first();
                $bookingData = $this->IndoorDischargeCard->where('booking_id',$bookingid)->first();
                if(!empty($bookingData)) {
                    $datetime1 = \Carbon\Carbon::parse($bookdata->doa_date);
                    $ldate = date('Y-m-d');
                    $datetime2 = $bookingData->dod_date ? \Carbon\Carbon::parse($bookingData->dod_date) : $ldate;
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');
                }
                else {
                    $days = 1;
                }
                $typeID = $bookdata->type_id;
                $roomtypedata = $this->IndoorType->where('id',$typeID)->first();

                $currentDeposit = $this->IndoorDeposit->wherePatientIdAndChargeType($bookdata->patient_id, 4)->orderBy('id', 'DESC')->value('total');

                return view('admin.indoor.invoice',compact('bookdata','roomtypedata','days', 'currentDeposit'));
            }
        }catch(Exception $e){
            abort(500);
        }

    }

    /**
    * Store indoor invoice
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function invoiceStore(Request $request) {
        try {
            $bookingid = decrypt($request->booked_id);
            $invoiceData = $this->IndoorInvoice->whereBookingId($bookingid)->first();
            // if ($invoiceData != null) {
            //     return response()->json([
            //         'status' => 2,
            //         'message' => 'Something went wrong.'
            //     ]);
            // }
            $invoice = $this->IndoorInvoice;
            $invoice->booking_id = $bookingid;
            $invoice->reg_charge_desc = $request->reg_charge_desc;
            $invoice->reg_charge_amt = $request->reg_charge_amt;
            $invoice->blood_charge_desc = $request->blood_charge_desc;
            $invoice->blood_charge_amt = $request->blood_charge_amt;
            $invoice->room_charge_desc = $request->room_charge_desc;
            $invoice->room_charge_amt = $request->room_charge_amt;
            $invoice->other_charge_desc = $request->other_charge_desc;
            $invoice->other_charge_amt = $request->other_charge_amt;
            $invoice->nursing_charges_desc = $request->nursing_charges_desc;
            $invoice->nursing_charges_amt = $request->nursing_charges_amt;
            $invoice->other_charge_descs = $request->other_charge_descs;
            $invoice->other_charge_amts = $request->other_charge_amts;
            $invoice->visit_charges_desc = $request->visit_charges_desc;
            $invoice->visit_charges_amt = $request->visit_charges_amt;
            $invoice->form_total_amt = $request->form_total_amt;
            $invoice->op_charge_desc = $request->op_charge_desc;
            $invoice->op_charge_amt = $request->op_charge_amt;
            $invoice->op_charge_descs = $request->op_charge_descs;
            $invoice->op_charge_amts = $request->op_charge_amts;
            $invoice->onco_surgeon_desc = $request->onco_surgeon_desc;
            $invoice->onco_surgeon_amt = $request->onco_surgeon_amt;
            $invoice->bill_type = !empty($request->bill_type) ? $request->bill_type : 1;
            $invoice->lscs_operation_charge_description = $request->lscs_operation_charge_description;
            $invoice->lscs_operation_charge = $request->lscs_operation_charge;
            $invoice->spvisit_charges_amt = $request->spvisit_charges_amt;
            $invoice->delivery_charge_desc = $request->delivery_charge_desc;
            $invoice->delivery_charge_amt = $request->delivery_charge_amt;
            $invoice->medicine_charge_amt = $request->medicine_charge_amt;
            $invoice->fourth_other_charge_amt = $request->fourth_other_charge_amt;
            $invoice->third_other_charge_amt = $request->third_other_charge_amt;
            $invoice->third_other_charge_desc = $request->third_other_charge_desc;
            $invoice->fourth_other_charge_desc = $request->fourth_other_charge_desc;
            $invoice->ot_charge_desc = $request->ot_charge_desc;
            $invoice->ot_charge_amt = $request->ot_charge_amt;
            $invoice->discount_amt = ($request->discount_amt != null) ? $request->discount_amt : 0;
            $invoice->labour_charge_desc = $request->labour_charge_desc;
            $invoice->labour_charge_amt = $request->labour_charge_amt;
            $invoice->created_by = Auth::user()->id;
            $depositAmount = ($request->deposit_amt != null) ? $request->deposit_amt : 0;
            $invoice->deposit_amt = $depositAmount;
            // fetch inddor book data
            $indoorBook = $this->IndoorBook->whereId($bookingid)->first();
            $patientLastDeposit = $this->IndoorDeposit->where('patient_id',$indoorBook->patient_id)->orderBy('id','DESC')->first();
            $checkTotalAmount = $request->form_total_amt - $request->discount_amt;
            if($request->deposit_amt != 0 && ($checkTotalAmount < $request->deposit_amt)){
                return ['status'=>5];
            }
            if($patientLastDeposit && $request->deposit_amt != 0 && $request->deposit_amt > $patientLastDeposit->total){
                return ['status'=>4];
            }
            if($request->deposit_amt != 0 && (empty($patientLastDeposit) || $patientLastDeposit->total == 0)){
                return ['status'=>3];
            }
            $indoorBook->doa_date = $request->admitdate ? Carbon::parse($request->admitdate)->format('Y-m-d') : null;
            $ldate = date('Y-m-d');
            $indoorBook->dod_date = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;
            $indoorBook->save();
            $invoice->anaesthesia_charge_desc = $request->anaesthesia_charge_desc;
            $invoice->anaesthesia_charge_amt = $request->anaesthesia_charge_amt;
            $invoice->biopys_charge_desc = $request->biopys_charge_desc;
            $invoice->biopys_charge_amt = $request->biopys_charge_amt;
            $invoice->sonography_charge_desc = $request->sonography_charge_desc;
            $invoice->sonography_charge_amt = $request->sonography_charge_amt;
            $invoice->grand_total_amt = $request->grand_total_amt;
            $invoice->payment_mode = !empty($request->payment) ? $request->payment : 2;
            $invoice->save();
            $invoice = $this->IndoorInvoice->whereId($invoice->id)->first();
            $this->IndoorBook->where('id', $bookingid)->update(['is_invoice' => 1]);
            if ($request->is_final_invoice) {
                $bookingData = $this->IndoorBook
                    ->whereId($bookingid)
                    ->update([
                        'is_final_invoice' => 1,
                        'final_invoice_date' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                if($depositAmount > 0 && $patientLastDeposit->total > 0 && $request->form_total_amt > 0) {
                    // dd('ok');
                    $deposit = $this->IndoorDeposit;
                    $deposit->patient_id = $indoorBook->patient_id;
                    $deposit->admin_id = Auth::user()->id;
                    $deposit->amount = $request->deposit_amt;
                    $deposit->total = $patientLastDeposit->total - $request->deposit_amt;
                    $deposit->procedure_id = $indoorBook->procedure_id;
                    $invoice->deposit_amt = $depositAmount;
                    $totalAmount = $request->form_total_amt - $request->discount_amt;
                    if ($request->deposit_amt > $totalAmount) {
                        // $deposit->amount = $totalAmount;
                        // $deposit->total = $patientLastDeposit->total - $request->deposit_amt;
                        $invoice->deposit_amt = $totalAmount;
                    }
                    $deposit->charge_type = 4;
                    $deposit->comment = 'Debit by invoice';
                    $deposit->case_type = 'Debit';
                    $deposit->save();
                }
            }
            if($request->ajax()) {
                if ($request->isprint) {
                    $invoice = $this->IndoorInvoice->with('getBookedBed')
                        ->whereId($invoice->id)
                        ->first();

                    return response()->json([
                        'status' => 1,
                        'data' => View::make('admin.indoor.invoice_preview', compact('invoice'))->render()
                    ]);
                }
                if($request->is_send){
                    $data['invoice'] = $invoice;
                    $pdf = PDF::loadView('admin.indoor.invoice_preview', $data);
                    $pdfPath = 'public/pdf';
                    if(!File::isDirectory($pdfPath)){
                        File::makeDirectory($pdfPath, 0777, true, true);
                    }
                    $mobileNumber = $invoice->getBookedBed->getPatientsDetails['mobile_number'];
                    $mobileNumber = 8469360755;
                    $pdfName = $invoice->id.'_'.date('Y_m_d_H_i_s').'.pdf';
                    $pdf->save($pdfPath.'/'.$pdfName);
                    // $this->uploadPdfToWassenger(url($pdfPath.'/'.$pdfName),$mobileNumber);
                }
                return response()->json([
                    'status' => 0,
                    'invoice' => $invoice
                ]);
            }
        } catch (Exception $exception) {
            abort(500);
        }
    }

    /**
    * Return on indoor invoice edit page
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function invoiceEdit($id) {
        try {
            $bookingid = decrypt($id);
            if(!empty($bookingid)){
                $invoicedata = $this->IndoorInvoice->where('booking_id', $bookingid)->first();
                $currentDeposit = $this->IndoorDeposit->wherePatientIdAndChargeType($invoicedata->getBookedBed->patient_id, 4)->orderBy('id', 'DESC')->value('total');
                $patientid = $this->IndoorBook->where('id', $bookingid)->pluck('patient_id')->first();
                $patientdata = $this->OpdPatients->where('id', $patientid)->first();
                $BookingData = $this->IndoorBook ->with('getPatientsDetails')->where('id', $bookingid)->first();
                $bookData = $this->IndoorDischargeCard->where('booking_id',$bookingid)->first();
                if(!empty($bookData)) {
                    $datetime1 = \Carbon\Carbon::parse($BookingData->doa_date);
                    $datetime2 = \Carbon\Carbon::parse($bookData->dod_date);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');
                }
                else {
                    $days = 1;
                }
                $patientid = $BookingData->patient_id;
                $typeID = $BookingData->type_id;
                $roomtypedata = $this->IndoorType->where('id',$typeID)->first();

                return view('admin.indoor.invoiceedit', compact('invoicedata', 'patientdata', 'BookingData','roomtypedata','days', 'currentDeposit'));
            }
        }catch(Exception $exception) {
            abort(500);
        }
    }

    /**
    * Update indoor invoice
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function invoiceUpdate(Request $request) {
        try {
            // dd($request->all());
            $invoiceid = decrypt($request->booked_id);
            $invoice = $this->IndoorInvoice->whereBookingId($invoiceid)->first();
            if(($invoice != null) && ($request->old_grand_total != $invoice->grand_total_amt)|| ($request->old_discount != $invoice->discount_amt) || ($request->old_total != $invoice->form_total_amt) || ($request->old_deposit != $invoice->deposit_amt)) {
                return response()->json(['status' => 3,'message' => 'Something went wrong.']);
            }
            $invoice->reg_charge_desc = $request->reg_charge_desc;
            $invoice->reg_charge_amt = $request->reg_charge_amt;
            $invoice->blood_charge_desc = $request->blood_charge_desc;
            $invoice->blood_charge_amt = $request->blood_charge_amt;
            $invoice->room_charge_desc = $request->room_charge_desc;
            $invoice->room_charge_amt = $request->room_charge_amt;
            $invoice->other_charge_desc = $request->other_charge_desc;
            $invoice->other_charge_amt = $request->other_charge_amt;
            $invoice->nursing_charges_desc = $request->nursing_charges_desc;
            $invoice->nursing_charges_amt = $request->nursing_charges_amt;
            $invoice->other_charge_descs = $request->other_charge_descs;
            $invoice->other_charge_amts = $request->other_charge_amts;
            $invoice->visit_charges_desc = $request->visit_charges_desc;
            $invoice->visit_charges_amt = $request->visit_charges_amt;
            $invoice->form_total_amt = $request->form_total_amt;
            $invoice->op_charge_desc = $request->op_charge_desc;
            $invoice->op_charge_amt = $request->op_charge_amt;
            $invoice->op_charge_descs = $request->op_charge_descs;
            $invoice->op_charge_amts = $request->op_charge_amts;
            $invoice->onco_surgeon_desc = $request->onco_surgeon_desc;
            $invoice->onco_surgeon_amt = $request->onco_surgeon_amt;
            $invoice->lscs_operation_charge_description = $request->lscs_operation_charge_description;
            $invoice->lscs_operation_charge = $request->lscs_operation_charge;
            $invoice->spvisit_charges_amt = $request->spvisit_charges_amt;
            $invoice->delivery_charge_desc = $request->delivery_charge_desc;
            $invoice->delivery_charge_amt = $request->delivery_charge_amt;
            $invoice->medicine_charge_amt = $request->medicine_charge_amt;
            $invoice->fourth_other_charge_amt = $request->fourth_other_charge_amt;
            $invoice->third_other_charge_amt = $request->third_other_charge_amt;
            $invoice->third_other_charge_desc = $request->third_other_charge_desc;
            $invoice->fourth_other_charge_desc = $request->fourth_other_charge_desc;
            $invoice->ot_charge_desc = $request->ot_charge_desc;
            $invoice->ot_charge_amt = $request->ot_charge_amt;
            $invoice->bill_type = !empty($request->bill_type) ? $request->bill_type : 1;
            $invoice->discount_amt = ($request->discount_amt != null) ? $request->discount_amt : 0;
            $invoice->labour_charge_desc = $request->labour_charge_desc;
            $invoice->labour_charge_amt = $request->labour_charge_amt;
            $invoice->created_by = Auth::user()->id;
            $depositAmount = $request->deposit_amt;
            $indoorBook = $this->IndoorBook->whereId($invoiceid)->first();
            $lastDeposit = $this->IndoorDeposit->wherePatientId($indoorBook->patient_id)->orderBy('id','DESC')->first();
            $checkTotalAmount = $request->form_total_amt - $request->discount_amt;
            if($indoorBook->is_final_invoice == 0 && ($request->deposit_amt != 0 && ($checkTotalAmount < $request->deposit_amt))){
                return ['status'=>6];
            }
            if($indoorBook->is_final_invoice == 0 && ($lastDeposit && $request->deposit_amt != 0 && $request->deposit_amt > $lastDeposit->total)){
                return ['status'=>5];
            }
            if($indoorBook->is_final_invoice == 0 && ($request->deposit_amt != 0 && (empty($lastDeposit) || $lastDeposit->total == 0))){
                return ['status'=>4];
            }
            $oldCurrentDeposit = $request->old_current_deposit;
            $oldFinalTotal = $request->old_total - $request->old_discount;
            $newFinalTotal = $request->form_total_amt - $request->discount_amt;
            $indoorBook->doa_date = $request->admitdate ? Carbon::parse($request->admitdate)->format('Y-m-d') : null;
            $ldate = date('Y-m-d');
            $indoorBook->dod_date = $request->dischargedate ? Carbon::parse($request->dischargedate)->format('Y-m-d') : $ldate;
            $indoorBook->save();

            $invoice->deposit_amt = ($request->deposit_amt != null) ? $request->deposit_amt : 0;
            $invoice->anaesthesia_charge_desc = $request->anaesthesia_charge_desc;
            $invoice->anaesthesia_charge_amt = $request->anaesthesia_charge_amt;
            $invoice->biopys_charge_desc = $request->biopys_charge_desc;
            $invoice->biopys_charge_amt = $request->biopys_charge_amt;
            $invoice->sonography_charge_desc = $request->sonography_charge_desc;
            $invoice->sonography_charge_amt = $request->sonography_charge_amt;
            $invoice->grand_total_amt = $request->grand_total_amt;
            $invoice->payment_mode = !empty($request->payment) ? $request->payment : 2;
            $invoice->save();
            $invoice = $this->IndoorInvoice->whereBookingId($invoiceid)->first();
            if ($request->is_final_invoice) {
                $bookingData = $this->IndoorBook
                    ->whereId($invoiceid)
                    ->update([
                        'is_final_invoice' => 1,
                        'final_invoice_date' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                // // store deposit table
                // if($lastDeposit && $lastDeposit->total != $oldCurrentDeposit){
                //     $type = 'Debit';
                //     $comment = 'Debit by invoice';
                //     $total = $lastDeposit->total - $oldCurrentDeposit;
                //     if($oldCurrentDeposit > $lastDeposit->total){
                //         $type = 'Credit';
                //         $comment = 'Credit by invoice';
                //         $total = $oldCurrentDeposit - $lastDeposit->total;
                //     }
                //     if($oldCurrentDeposit == 0){
                //         $oldCurrentDeposit = 0;
                //         $total = $lastDeposit->total;
                //     }
                //     $deposit = $this->IndoorDeposit;
                //     $deposit->patient_id = $indoorBook->patient_id;
                //     $deposit->admin_id = Auth::user()->id;
                //     $deposit->amount = $total;
                //     $deposit->comment = $comment;
                //     $deposit->case_type = $type;
                //     $deposit->procedure_id = $indoorBook->procedure_id;
                //     $deposit->total = $oldCurrentDeposit;
                //     $deposit->charge_type = 4;
                //     $deposit->save();
                // }
                // store deposit table
                if($depositAmount > 0 && $request->form_total_amt > 0) {
                    $deposit = $this->IndoorDeposit;
                    $deposit->patient_id = $indoorBook->patient_id;
                    $deposit->admin_id = Auth::user()->id;
                    $deposit->amount = $request->deposit_amt;
                    $deposit->total = $lastDeposit->total - $request->deposit_amt;
                    $deposit->procedure_id = $indoorBook->procedure_id;
                    $invoice->deposit_amt = $depositAmount;
                    $totalAmount = $request->form_total_amt - $request->discount_amt;
                    if ($request->deposit_amt > $totalAmount) {
                        // $deposit->amount = $totalAmount;
                        // $deposit->total = $patientLastDeposit->total - $request->deposit_amt;
                        $invoice->deposit_amt = $totalAmount;
                    }
                    $deposit->charge_type = 4;
                    $deposit->comment = 'Debit by invoice';
                    $deposit->case_type = 'Debit';
                    $deposit->save();
                }
            }

            if ($request->isprint) {
                return response()->json([
                    'status' => 2,
                    'data' => View::make('admin.indoor.invoice_preview', compact('invoice'))->render()
                ]);
            }
            if($request->is_send){
                $data['invoice'] = $invoice;
                $pdf = PDF::loadView('admin.indoor.invoice_preview', $data);
                $pdfPath = 'public/pdf';
                if(!File::isDirectory($pdfPath)){
                    File::makeDirectory($pdfPath, 0777, true, true);
                }
                $mobileNumber = $invoice->getBookedBed->getPatientsDetails['mobile_number'];
                $mobileNumber = 8469360755;
                $pdfName = $invoice->id.'_'.date('Y_m_d_H_i_s').'.pdf';
                $pdf->save($pdfPath.'/'.$pdfName);
                // $this->uploadPdfToWassenger(url($pdfPath.'/'.$pdfName),$mobileNumber);
            }
            if($request->ajax()) {
                return response()->json([
                    'status' => 1,
                    'invoice' => $invoice
                ]);
            }
        }catch(Exception $exception){
            abort(500);
        }
    }

    // public function  invoiceUpdatePrint($id,Request $request) {

    //  $bookingId= decrypt($id);
    // //  $invoice = $this->IndoorInvoice->where('booking_id',$bookingId)->first()->toArray();
    //  $invoice = $this->IndoorInvoice->where('booking_id',$bookingId)->first();
    // // dd($invoice);
    // //  $invoice = $invoice
    // // dd($invoice);
    // }

    /**
    * Direct Discharge patient
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function directDischarge($id,Request $request) {
        try {
            $bookingId = decrypt($id);
            $roomId = $this->IndoorBook->where('id',$bookingId)->pluck('room_id')->first();
            if($bookingId > 0){
                $ldate = date('Y-m-d');
                $this->IndoorBook->where('id', $bookingId)->update(['is_direct_discharge' => 1,'dod_date'=>$ldate]);
                //update indoor_beds, set flag 1 to 0
                $iRoom = $this->IndoorRoom->where('id', $roomId)->first();
                $iRoom->flag = 0;
                $iRoom->save();
                return ['status'=>'true'];
            }
            return ['status'=>'false'];
        }catch(Exception $e){
            abort(500);
        }

    }


    /**
    * Get Booking patient data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getBookingPatientData(Request $request) {
        // dd($request->booking_id);
        try {
            $bookingId = decrypt($request->booking_id);
        } catch (Exception $e) {
            return [
                'status' => 'false',
                'message' => 'Something went wrong',
            ];
        }
        try {
            $patientId = $request->patient_id;
            $deposite = 0;
            if ($patientId > 0) {
                $procedure = $this->IndoorBook->whereId($bookingId)->orderBy('id')->value('procedure_id');
                $indoorBook = $this->IndoorBook->whereId($bookingId)->first();
                $deposite = $this->IndoorDeposit->with('getPatients')->wherePatientIdAndChargeType($patientId, 4)->orderBy('id','DESC')->first();
                // $deposite = $this->IndoorDeposit->with('getPatients')->wherePatientIdAndChargeType($patientId, 4)->latest()->first();
                $depositData = $this->IndoorDeposit->wherePatientIdAndChargeType($patientId, 4)->whereProcedureId($procedure)->whereDate('created_at','>=',$indoorBook->doa_date)->get();
                $patientname = ucwords(strtolower($this->OpdPatients->where('id',$patientId)->value('name')));
                if (!empty($deposite) || !empty($patientname)) {
                    return [
                        'status' => 'true',
                        'deposite' => $deposite,
                        'patientname' => $patientname,
                        'depositData' => $depositData,
                        'procedure_id' => $procedure
                    ];
                }
            }
            return [
                'status' => 'false',
                'deposite' => $deposite,
                'patientname' => $patientname
            ];
        } catch (Exception $e) {
            abort(500);
            log::debug($e);

        }
    }

    /**
    * Store Deposit
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function depositStore($id,Request $request) {
        try{
            $validator = \Validator::make($request->all(), [
                'deposit_amount' => 'required|numeric|digits_between:0,6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 4,
                    'errors' => $validator->errors()->first()
                ]);
            }
            $lastTotal = $this->IndoorDeposit->wherePatientIdAndChargeType($id, 4)->orderBy('id', 'DESC')->value('total');
            if ($lastTotal != null && $request->current_deposit != $lastTotal) {

                return response()->json([
                    'status' => 3,
                    'message' => 'Something went wrong.'
                ]);
            }
            $deposit = $this->IndoorDeposit->with('getPatients')->wherePatientIdAndChargeType($id, 4)->orderBy('id','DESC')->first();
            if($deposit){
                $lastDate = Carbon::parse($deposit->created_at)->format('Y-m-d');
            }
            // if($deposit && $deposit->case_type == 'Credit' && $lastDate == date('Y-m-d') && $request->deposit_type == 'debit'){
            if($deposit && $lastDate == date('Y-m-d') && $deposit->payment_type == $request->payment_type){
                $amount = 0;
                $totalAmount = 0;
                $amount = $deposit->amount ? $deposit->amount : 0;
                if($deposit->case_type == 'Credit'){
                    $caseType = 'Credit';
                    if($request->deposit_type == 'credit'){
                        $amount = $request->deposit_amount + $amount;
                        $totalAmount = $deposit->total + $request->deposit_amount;
                    }
                    if($request->deposit_type == 'debit'){
                        if($amount < $request->deposit_amount){
                            $caseType = 'Debit';
                        }else{
                            $caseType = 'Credit';
                        }
                        $amount = abs($amount - $request->deposit_amount);
                        $totalAmount = $deposit->total - $request->deposit_amount;
                        if($amount <= 0){
                            $deposit->delete();
                        }
                    }
                }
                // if($request->deposit_type == 'credit'){
                //     $amount = $request->deposit_amount + $amount;
                //     $totalAmount = $deposit->total + $request->deposit_amount;
                // }else{
                if($deposit->case_type == 'Debit'){
                    if($request->deposit_type == 'debit'){
                        $amount = $amount + $request->deposit_amount;
                        $totalAmount = $deposit->total - $request->deposit_amount;
                        $caseType = 'Debit';
                        if($totalAmount <= 0){
                            $deposit->delete();
                        }
                    }
                    if($request->deposit_type == 'credit'){
                        if($amount > $request->deposit_amount){
                            $caseType = 'Debit';
                            $amount = $amount - $request->deposit_amount;
                        }else{
                            $caseType = 'Credit';
                            $amount = $request->deposit_amount - $amount;
                        }
                        $totalAmount = $deposit->total + $request->deposit_amount;
                        if($totalAmount <= 0 || $amount <= 0){
                            $deposit->delete();
                        }
                    }
                }
                if($amount > 0 && $totalAmount > 0){
                    $deposit->amount = $amount;
                    $deposit->total = $totalAmount;
                    $deposit->payment_type = $request->payment_type;
                    $deposit->case_type = $caseType;
                    $deposit->save();
                    $patientDepositData = $deposit;
                }
                $patientDepositData = $this->IndoorDeposit->with('getPatients')->wherePatientIdAndChargeType($id, 4)->orderBy('id','DESC')->first();
            }else{
                $patientDepositData = $this->IndoorDeposit;
                $patientDepositData->patient_id = $id;
                $patientDepositData->amount = $request->deposit_amount;
                $total = $request->deposit_amount;
                if(!empty($deposit)) {
                    $total = ($request->deposit_type == 'credit') ? ($deposit->total + $request->deposit_amount) : ($deposit->total - $request->deposit_amount);
                }
                $patientDepositData->total = $total;
                $patientDepositData->case_type = ucwords(strtolower($request->deposit_type));
                $patientDepositData->admin_id = Auth::id();
                $patientDepositData->charge_type = 4;
                $patientDepositData->procedure_id = $request->procedure_id;
                $patientDepositData->comment = $request->comment;
                $patientDepositData->payment_type = $request->payment_type;
            }

            if($patientDepositData->total >= 0 && $patientDepositData->amount >= 0) {
                $patientDepositData->save();
            } else {
                return response()->json([
                    'status' => 3,
                    'message' => 'Something went wrong.'
                ]);
            }

            if (!empty($deposit)) {

                $depositAmount = $request->deposit_amount;

                $deposit->valuinword = $this->getWordOfNumber($depositAmount);
                $depositeWord = $deposit->valuinword;

                if ($request->isprint) {

                    return response()->json([
                        'status' => 2,
                        'data' => View::make('admin.indoor.deposit_preview', compact('deposit', 'depositeWord', 'depositAmount'))->render()
                    ]);
                }
            }
            return response()->json([
                'status' => 1,
            ]);

        }catch(Exception $e){
           abort(500);
        }
    }

    /**
    * Get print of Deposit
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function depositPrint($id,Request $request) {

        $deposit = $this->IndoorDeposit->with('getPatients')->where('id', $id)->first();
        if(!empty($deposit)) {
            $deposit->valuinword = $this->getWordOfNumber($deposit->amount);
            $depositeWord = $deposit->valuinword;
            if ($request->isprint) {
                return response()->json([
                    'status' => 1,
                    'data' => View::make('admin.indoor.patient_deposit_preview', compact('deposit','depositeWord'))->render()
                ]);
            }
        }
        else {
            return response()->json([
                'status' => 0
            ]);
        }
    }

    /**
    * Get BedNumber
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function GetBedNumber($id) {
        $beds = $this->IndoorBed->where("room_id",$id)->pluck("bed_no","bed_no");
        return response()->json($beds);
    }

    /**
    * Store Procedure
    * @param  \Illuminate\Http\Request $requestProcedures
    * @return \Illuminate\Http\Response
    */
    private function storeprocedure($requestProcedures){
        $lastarray = [];
        $procedure = $this->IndoorProcedure->pluck('id','id')->toArray();
        if(!empty($requestProcedures['pro_type'])){
            $diffrent = array_diff($requestProcedures['pro_type'],$procedure);
            foreach($diffrent as $row){
                $lastId = $this->IndoorProcedure->insertGetId(
                    [ 'name' => $row ]
                );
                $lastarray[] = $lastId;
            }
        }
        return $lastarray;
    }

    /**
    * Add Medicine
    * @param  \Illuminate\Http\Request $requestMedicine
    * @return \Illuminate\Http\Response
    */
    private function medicineData2($requestMedicine){
         $medicine = $this->Medicine->pluck('name','name')->toArray();

         if(!empty($requestMedicine['medicinedata'])){
             $diffrent = array_diff($requestMedicine['medicinedata'],$medicine);
             foreach($diffrent as $row){
                 $medicineData = new $this->Medicine;
                 $medicineData->name = $row;
                 $medicineData->save();
             }
         }
    }

    /**
    * Add Treatments
    * @param  \Illuminate\Http\Request $requestTreatment
    * @return \Illuminate\Http\Response
    */
    private function giventratment($requestTreatment){
        $treatment = $this->GivenTreatments->pluck('name','name')->toArray();

        if(!empty($requestTreatment['treatments'])){
            $diffrent = array_diff($requestTreatment['treatments'],$treatment);
            foreach($diffrent as $row){
                $medicineData = new $this->GivenTreatments;
                $medicineData->name = $row;
                $medicineData->save();
            }
        }
    }

    /**
    * Add diagnosis
    * @param  \Illuminate\Http\Request $requestDiagnosis
    * @return \Illuminate\Http\Response
    */
    private function diagnosisData($requestDiagnosis){
        $diagnosis = $this->Diagnosis->pluck('name','name')->toArray();

        if(!empty($requestDiagnosis['diagnosis'])){
            $diffrent = array_diff($requestDiagnosis['diagnosis'],$diagnosis);
            foreach($diffrent as $row){
                $medicineData = new $this->Diagnosis;
                $medicineData->name = $row;
                $medicineData->save();
            }
        }
    }

    /**
    * Generate code
    * @param  \Illuminate\Http\Request $patientsId,$latter
    * @return \Illuminate\Http\Response
    */
    private function generateCode($patientsId,$latter){
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('y');
        $code = 'RI'.ucfirst($latter).$month.$year.$patientsId;
        return ['code'=>$code];
    }

    /**
    * Get doctor list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function getDoctor(){
        $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
        $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
        return['referenceDoctor'=>$referenceDoctor,'hospitalDoctor'=>$hospitalDoctor];
    }
    /**
    * Get State list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function getState(){
        $state = $this->State->pluck('name','id');
        return ['state'=>$state];
    }

    /**
    * Return on indoor config
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function indoorsetting(Request $request){
        if($request->ajax()){

            $indoorRooms = $this->IndoorType
                ->withCount([
                    'Rooms' => function ($query) {
                        $query->whereStatus(1);
                    }
                ])
                ->whereStatus(1)
                ->paginate(100);

            $indoorBeds = $this->IndoorType
                ->leftJoin('indoor_rooms', 'indoor_types.id' , '=', 'indoor_rooms.type_id')
                ->leftJoin('indoor_beds', 'indoor_rooms.id', '=', 'indoor_beds.room_id')
                ->selectRaw(
                    'indoor_types.id, indoor_types.name, COUNT("indoor_beds.room_id") as bed_count'
                )
                ->where([
                    ['indoor_types.status', '=', 1],
                    ['indoor_rooms.status', '=', 1],
                    ['indoor_beds.status', '=', 1]
                ])
                ->groupBy('indoor_types.id', 'indoor_types.name')
                ->get();

            return response()->json([
                View::make('admin.indoor.config.data',compact('indoorRooms','indoorBeds'))->render()
            ]);
        }
        return view('admin.indoor.config.index');
    }

    /**
    * Return on indoor config create page
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function indoorcreate(){
        $indoortypes = $this->IndoorType->all();
        return view('admin.indoor.config.create',compact('indoortypes'));
    }

    /**
    * Store indoor config
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function indoorstore(Request $request){
        try{
            $indoorType = $this->IndoorType;
            $indoorType->name = $request->roomtype;
            $indoorType->price = $request->price;
            $indoorType->nursing_charge = $request->nursing_charge;
            $indoorType->dr_visit_charge = $request->dr_visit_charge;
            $indoorType->save();
            $room = $request->room;
            for ($i = 1; $i <= $room; $i++) {
                $indoorRoom = new $this->IndoorRoom;
                $indoorRoom->type_id = $indoorType->id;
                $indoorRoom->save();
                $bedCount = $request->input('bed_' . $i);
                for ($j = 1; $j <= $bedCount; $j++) {
                    $indoorBed = new $this->IndoorBed;
                    $indoorBed->room_id = $indoorRoom->id;
                    $indoorBed->bed_no = $j;
                    $indoorBed->save();
                }
            }
            return redirect('indoorsetting');
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on Edit room page
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function roomedit($id, Request $request){

        try {
            $roomTypeId = decrypt($id);

            $indoorRooms = $this->IndoorType
                ->where(function($query) use($roomTypeId) {
                    $query->whereHas('Rooms', function ($query) use ($roomTypeId) {
                        $query->whereTypeId($roomTypeId);
                    });
                })
                ->whereId($roomTypeId)
                ->first();

            $roomWiseBeds = $this->IndoorRoom
                ->with([
                    'Beds' => function ($query) {
                        $query->whereStatus(1);
                    }
                ])
                ->whereTypeId($roomTypeId)
                ->get();


            $indoorBeds = $this->IndoorType
                ->leftJoin('indoor_rooms', 'indoor_types.id' , '=', 'indoor_rooms.type_id')
                ->leftJoin('indoor_beds', 'indoor_rooms.id', '=', 'indoor_beds.room_id')
                ->selectRaw(
                    'indoor_types.id, indoor_types.name, COUNT("indoor_beds.room_id") as bed_count'
                )
                ->where([
                    ['indoor_types.status', '=', 1],
                    ['indoor_beds.status', '=', 1],
                    ['indoor_types.id', '=', $roomTypeId]
                ])
                ->groupBy('indoor_types.id', 'indoor_types.name')
                ->first();


            return view('admin.indoor.config.edit', compact('indoorRooms', 'indoorBeds', 'roomWiseBeds'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Update Room
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {
        try {
            $roomTypeId = decrypt($request->room_type_id);
        } catch (Exception $exception) {
            return 'false';
        }

        try {
            $this->IndoorRoom
                ->whereIn('id', explode(',', $request->room_status_on))
                ->update([
                    'status' => 1
            ]);

            $this->IndoorRoom
                ->whereIn('id', explode(',', $request->room_status_off))
                ->update([
                    'status' => 0
                ]);

            $rooms = $request->room;
            if ($request->total_rooms > $request->total_room) {
                for ($i = ($request->total_room + 1); $i <= ($request->total_room + count($rooms)); $i++) {
                    $indoorRoom = new $this->IndoorRoom;
                    $indoorRoom->type_id = $roomTypeId;
                    $indoorRoom->status = 1;
                    $indoorRoom->save();
                    for ($j = 1; $j <= $rooms[$i]['bed']; $j++) {
                        $indoorBed = new $this->IndoorBed;
                        $indoorBed->room_id = $indoorRoom->id;
                        $indoorBed->bed_no = $j;
                        $indoorBed->save();
                    }
                }
            }

            $this->IndoorType->whereId($roomTypeId)->update([
                'name' => $request->room_type,
                'price' => $request->price,
                'nursing_charge' => $request->nursing_charge,
                'dr_visit_charge' => $request->dr_visit_charge
            ]);

            return 'true';
        } catch (Exception $exception) {
            abort(500);
        }
    }

    /**
    * Update indoor type status
    * @param  \Illuminate\Http\Request $id,$request
    * @return \Illuminate\Http\Response
    */
    public function updatestatus($id,Request $request)
    {
        $id=decrypt($id);
        $this->IndoorType->where('id',$id)->update(['status' => 0]);
        return redirect('indoorsetting');
    }
    /**
    * Delete indoor
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function indoorDelete(Request $request) {
        try {
            $roomTypeId = decrypt($request->id);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message'   => 'Invalid room type, please try again'
            ]);
        }
        try{
            $indoorBeds = $this->IndoorType
                ->leftJoin('indoor_rooms', 'indoor_types.id' , '=', 'indoor_rooms.type_id')
                ->leftJoin('indoor_beds', 'indoor_rooms.id', '=', 'indoor_beds.room_id')
                ->selectRaw(
                    'COUNT("indoor_beds.room_id") as bed_count'
                )
                ->where([
                    ['indoor_types.id', '=', $roomTypeId],
                    ['indoor_beds.flag', '=', 1],
                ])
                ->groupBy('indoor_types.id')
                ->first();

            if (!empty($indoorBeds) && $indoorBeds->bed_count > 0) {
                return response()->json([
                    'success' => false,
                    'message'   => 'You can not deactivate this type becuase some patients are allocated in that room type\'s beds'
                ]);
            }

            $this->IndoorType
                ->whereId($roomTypeId)
                ->update([
                    'status' => 0
                ]);

            return response()->json([
                'success' => true,
                'message'   => 'Your room type has been deactivated successfully'
            ]);
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Get message detail
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getMessageDetail(Request $request) {
        try{
            if($request->ajax()) {
                $dischargeData = $this->IndoorDischargeCard->with('getIndoorBook')
                ->whereId($request->id)
                ->orderBy('id','DESC')
                ->first();
                $pId = explode(',',$dischargeData->getIndoorBook->procedure_id);
                $msg = 'deliverd a';
                if(in_array('2',$pId)){
                    $msg = 'and LSCS done';
                }
                $sNotes = $dischargeData->surgical_note;
                $gender = null;
                $weight = null;
                $time = null;
                $date = null;
                if($sNotes){
                    $sNotes = strtolower($sNotes);
                    $var = explode('on ', $sNotes);
                    $date = $var[1];
                    if(strpos($sNotes, 'male') !== false){
                        $gender = "Male";
                    } else{
                        $gender = "Female";
                    }
                    $weight = $this->getStringBetween($sNotes,'wt','kg');
                    $time = $this->getStringBetween($sNotes,'at','on');
                }
                $getPatientName = $dischargeData->getIndoorBook->getPatientsDetails['name'];
                $getDoctorName = $dischargeData->getIndoorBook->getPatientsDetails->getReferenceDoctor['name'];
                $getDischargeDate =  $dischargeData->getIndoorBook->dod_date;

                $textmessage = config('app.sendDischargeCardToRefDoctor');

                $textmessage = $this->replaceMessage('{{patient_fullname}}', $getPatientName,$textmessage);
                $textmessage = $this->replaceMessage('{{reff_drname}}', $getDoctorName,$textmessage);
                $textmessage = $this->replaceMessage('{{msg}}', $msg,$textmessage);
                $textmessage = $this->replaceMessage('{{gender}}', $gender,$textmessage);
                $textmessage = $this->replaceMessage('{{date}}', $date,$textmessage);
                $textmessage = $this->replaceMessage('{{weight}}', $weight,$textmessage);
                $textmessage = $this->replaceMessage('{{time}}', $time,$textmessage);
                $textmessage = $this->replaceMessage('{{app_name}}',config('app.name'),$textmessage);
                
                // $textmessage = "Dear ".$getDoctorName." Your reffer ".$getPatientName." has addmited our hospital ".$msg." ".$gender." child on ".$date." at ".$time." with ".$weight. "Thank You for reference and expecting your favourable supprt future. ".config('app.name');
                
                $data = [
                    'message' => $textmessage,
                    'mobile_number' => $dischargeData->getIndoorBook->getPatientsDetails->getReferenceDoctor['mobile_number']
                ];

                return ['status' => 'true', 'data' => $data];
            }
        }catch(Exception $e) {
            // dd($e);
            abort(500);
        }
    }

    /**
    * Send Discharge message
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function sendSms(Request $request) {
        try{
            $mobile = $request->mobile;
            $message = $request->message;
            $this->SmsManager->sendDischargeMsgToDoctor($mobile, $message);

            return redirect('indoor');
        }catch(Exception $e) {
            // dd($e);
            abort(500);
        }
    }

    /**
    * Store discharge complaint
    * @param  \Illuminate\Http\Request $commentData
    * @return \Illuminate\Http\Response
    */
    private function storeDischargeComplaint($commentData){
        if(!empty($commentData)){
            foreach($commentData as $row){
                $dischargeComplaint = $this->DischargeComplaint::where('name',$row)->first();
                if(!$dischargeComplaint){
                    $discardComplaint = new $this->DischargeComplaint;
                    $discardComplaint->name = $row;
                    $discardComplaint->status = 1;
                    $discardComplaint->created_by = Auth::user()->id;
                    $discardComplaint->save();
                }
            }
        }
    }

    /**
    * Delete indoor
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public static function replaceMessage($find,$replace='',$sms){

        
        if (!empty($find) && !empty($replace)) {
            if ($find == '{{reff_drname}}' || $find == '{{patient_fullname}}') {
                $replace = ucwords(strtolower($replace));
            }

            if($find == '{{msg}}') {
                $replace = $replace;
            }
            if($find == '{{gender}}') {
                $replace = $replace;
            }
            if($find == '{{date}}') {
                $replace = $replace;
            }
            if($find == '{{weight}}') {
                $replace = $replace;
            }
            if($find == '{{time}}') {
                $replace = $replace;
            }
            if($find == '{{app_name}}') {
                $replace = ucwords(strtolower($replace));
            }
            return str_replace($find, $replace, $sms);
        }
        return $sms;
    }

    /**
    * Get SubString
    * @param  \Illuminate\Http\Request $str,$from,$to
    * @return \Illuminate\Http\Response
    */
    public static function getStringBetween($str,$from,$to){
        $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
        return substr($sub,0,strpos($sub,$to));
    }
    /**
    * Return indoor preview
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function indoor_preview($id)
    {
        try{
            $floor= ($id == 0) ? 0 : $id;
            $indoorData = $this->IndoorBook;
            $indoorTypes = $this->IndoorType->where('status',1)->get();
            $indoorType = $this->IndoorType->where('status',1)->pluck('name', 'id');
            $typeId = $this->IndoorType->pluck('id');
            $patientDetail = [];
            foreach ($typeId as $type) {
                $indoorBed[$type] = $this->IndoorBed->where(function($query) use($type) {
                    $query->whereHas('room', function($query) use($type) {
                        $query->where([
                            ['type_id', '=', $type],
                            ['status', '=', 1]
                        ]);
                    });
                })->where([
                    ['flag', '=', 0],
                    ['status', '=', 1]
                ])->count();

                $bookingPatients = $this->IndoorBook
                    ->with([
                        'getPatientsDetails'
                    ])
                    ->where('type_id', $type)
                    ->where('is_direct_discharge',0)
                    ->get();

                $ptcount = $bookingPatients->count();
                $ptcount = 0;
                if($bookingPatients->count() > 0) {
                    $ptcount = $bookingPatients->count();
                }
                $ptdata = [];

                foreach($bookingPatients as $key => $bookingPatient) {
                    $ptdata[$key] = $bookingPatient;
                    $ptdata[$key]['procedure_name'] = implode(', ', $this->IndoorProcedure
                        ->whereIn('id', explode(',', $bookingPatient->procedure_id))
                        ->pluck('name')
                        ->toArray());
                }

                $patientDetail[$type]['data'] = $ptdata;
                $patientDetail[$type]['count'] = $ptcount;
            }
            $proceduresId = $this->IndoorProcedure->pluck('name','id');
            if($floor != 0)
            {
                $indoorRoom = $this->IndoorRoom->where('remark','=',$id)->orderBy('type_id','ASC')->get();
                $bookingPatients_floor = $this->IndoorBook
                    ->with([
                        'getPatientsDetails'
                    ])
                    ->where('is_direct_discharge',0)
                    ->get();
                $procedure_Type = $this->IndoorProcedure->pluck('name', 'id');
            }
            else
            {
                $bookingPatients_floor = [];
                $indoorRoom = [];
                $procedure_Type = [];
            }
            return response()->json([
                'status' => 1,
                'data' => View::make('admin.indoor.indoor_preview', compact('indoorTypes','indoorType', 'patientDetail','indoorBed','ptcount','proceduresId','floor','indoorRoom','bookingPatients_floor','procedure_Type'))->render()
            ]);
        }catch(Exception $e){
            log::debug($e);
            return response()->json([
                'status' => 0
            ]);
        }
    }

    /**
    * Upload Birth Certificate
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function uploadBirthCertificate(Request $request)
    {
        dd($request);
    }
}
