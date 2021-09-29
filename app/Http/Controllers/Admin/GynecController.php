<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Exception;
use Carbon\Carbon;
use Session;
use Auth;
use Log;
use View;

class GynecController extends AdminController
{
    // open the gynec first visit page
    public function create($patientsId){
        try{
            $pId = decrypt($patientsId);
            $patient = $this->OpdPatients->where('id',$pId)->first();
            $hoData = $this->getHoData();
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $durationOfData = getDurationOfData(1)['data'];
            $isGynec = 0;
            $oe = null;
            $anc = $this->ANC->where('patients_id',$pId)->where('is_gynec',1)->orderBy('id','DESC')->first();
            if($anc && !$this->AncHistory->where('patients_id',$pId)->exists()){
                $isGynec = 1;
                $oe = json_decode($anc->o_e);
            }
            
            $ancHistory = $this->AncHistory->whereRaw('Created_at IN (select MAX(Created_at) FROM anc_history GROUP BY patients_id)')
                                    ->where('patients_id',$pId)
                                    ->where('is_gynec',1)
                                    ->first();
            if($ancHistory){
                $oe = json_decode($ancHistory->o_e);
                $isGynec = 1;
            }
            $checkIsGynec = $this->Gynec->where('patients_id',$pId)->orderBy('id','DESC')->first();
            if($checkIsGynec){
                $isGynec = $checkIsGynec->is_gynec;
            }
            // need Changes
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $surgicallyData = $this->surgicallyType()['data'];
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $isIvfHistory = !empty($this->IvfHistory->where('patients_id',$pId)->first()) ? true : false;
            $isAncHistory = !empty($this->AncHistory->where('patients_id',$pId)->first()) ? true : false;
            return view('admin.gynec.create',compact('personalData','pastData','familyData','hoData','complaints','patient','medicines','patientsId','durationOfData','leftOvaryData','rightOvaryData','surgicallyData','isGynec','oe','hospitalDoctor','isIvfHistory','isAncHistory'));
        }catch(Exception $e){
            log::debug($e->getMessage());
            abort(500);
        }
    }

    private function surgicallyType(){
        $data = $this->SurgicalNote->pluck('name','name')->toArray();
        return ['data'=>$data];
    }

    // this function is use for all visit data store in gynec table 
    public function store(Request $request){
        try{
            $pId = decrypt($request->patients_id);
            // if(!empty($request->treatment['medicinedata'])){
            //     $this->medicineData($request->treatment['medicinedata']);
            //     $this->treatmentData($request->treatment);
            // }
            if(!empty($request->plan_of_management['surgically_details'])){
                $this->storeSurgicalData($request->plan_of_management['surgically_details']);
            }
            $isProcudure = 0;
            $followDate = null;
            $gynec = $this->Gynec;
            $reportImages = [];
            $reportOldImages = [];
            $ancImages = [];
            $ancOldImages = [];
            if($request->gynec_id){
                $gynec = $this->Gynec->find(decrypt($request->gynec_id));
                $this->getImagesData('report_old',decrypt($request->gynec_id),$request->report_old ? $request->report_old : [-1]);
                $this->getImagesData('anc_old',decrypt($request->gynec_id),$request->anc_old ? $request->anc_old : [-1]);
                $newGynec = $this->Gynec->find(decrypt($request->gynec_id));
                if(!empty($newGynec->investigation)){
                    $oldInvestigationData = json_decode($newGynec->investigation);
                    if(!empty($oldInvestigationData)){
                        $reportOldImages = !empty($oldInvestigationData->report->images) ? (array)$oldInvestigationData->report->images : [];
                        $ancOldImages = !empty($oldInvestigationData->anc->images) ? (array)$oldInvestigationData->anc->images : [];
                    }
                }
            }
            $gynec->patients_id = $pId;
            if(!empty($request->ho['ho_details'])){
                $this->hoData($request->ho['ho_details']);
            }
            if(!empty($request['p_detailes']['personal_history_history_type'])){
                $this->storeGynecHoData($request['p_detailes']['personal_history_history_type'],1);
            }
            if(!empty($request['p_detailes']['past_history_type'])){
                $this->storeGynecHoData($request['p_detailes']['past_history_type'],2);
            }
            if(!empty($request['p_detailes']['family_history'])){
                $this->storeGynecHoData($request['p_detailes']['family_history'],3);
            }
            $this->complaintStore($request->co);
            $gynec->seen_by = $request->seen_by;
            $gynec->ho = json_encode($request->ho);
            $gynec->co = json_encode($request->co);
            $gynec->oh = json_encode($request->oh);
            $gynec->mh = json_encode($request->mh);
            $gynec->patients_details_ho = json_encode($request->p_detailes);
            $gynec->oe = json_encode($request->oe);
            $gynec->plan_of_management = json_encode($request->plan_of_management);
            $investigationData = $request->investigation;
            if(!empty($request['investigation']['report']['images'])){
                foreach($request['investigation']['report']['images'] as $key=>$row){
                    $name = $this->uploadImage($row, 'public/upload/gynec/report');
                    $reportImages[] = 'public/upload/gynec/report/' . $name;
                }
                $investigationData['report']['images'] = array_merge($reportImages,$reportOldImages);
            }else{
                $investigationData['report']['images'] = $reportOldImages;
            }
            if(!empty($request['investigation']['anc']['images'])){
                foreach($request['investigation']['anc']['images'] as $key=>$row){
                    $name = $this->uploadImage($row, 'public/upload/gynec/report');
                    $ancImages[] = 'public/upload/gynec/report/' . $name;
                }
                $investigationData['anc']['images'] = array_merge($ancImages,$ancOldImages);
            }else{
                $investigationData['anc']['images'] = $ancOldImages;
            }
            if(!empty($request->investigation['investigation_details'])){
                $investigationData['investigation_details'] =  array_filter($request->investigation['investigation_details']);
            }
            $gynec->investigation = json_encode($investigationData);
            $gynec->treatment = json_encode($request->treatment);
            $gynec->created_by = Auth::user()->id;
            $gynec->is_gynec = $request->is_gynec;
            $gynec->save();
            $now = Carbon::now()->format('Y-m-d');
            $appointmentFlag = $this->Appointment->wherePatientsId($pId)->where('date',$now)->update(['is_done'=>1]);
            $updateConsulting = $this->Appointment->wherePatientsId($pId)->where('date',$now)->update(['in_consulting_room'=>0]);

            Session::flash('msg','Record has been successfully added.');
            if(isset($request->plan_of_management['plan_of_management_data']) && in_array('surgically', $request->plan_of_management['plan_of_management_data'])){
                $isProcudure = 1;
            }
            if(!empty($request->ho['follow_up'])){
                $followupDate = $request->ho['follow_up'];
                $followDate = date('Y-m-d',strtotime($followupDate));
                $appointmentTime = null;
                $fDate = !empty($followDate) ? Carbon::parse($followDate)->format('Y-m-d') : null;
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
                // $checkAppointment = $this->Appointment->wherePatientsId($pId)->whereDate('date',$followDate)->orderBy('id','DESC')->first();
                // if(!$checkAppointment){
                $appointment = $this->Appointment->where('patients_id',$pId)->orderBy('id','DESC')->first();
                if($appointment){
                    $appointmentData['appointmentId'] = encrypt($appointment->id);
                    $appointmentData['date'] = $followDate;
                    $appointmentData['time'] = $appointmentTime;
                    $appointmentData['is_procedure'] = $isProcudure;
                    $nextAppointment = $this->nextAppointmentData($appointmentData);
                }
            }
            
            if(!$request->gynec_id){
                $seenBy = getSeenByDoctor($gynec->seen_by);
                $patient = $this->OpdPatients->find($pId);
                $followDate = $followDate ? date('d M Y',strtotime($followDate)) : null;
                $this->SmsManager::sendReferenceDoctor('Advise Gynec',$seenBy->name,$followDate,$pId);
            }

            if($request->is_print == 1){
                $surgicallyData = $this->surgicallyType()['data'];
                $investigationReport = $this->allInvestigationReport();
                return response()->json([
                    'status'=>1,
                    'id' => encrypt($gynec->id),
                    'data' => View::make('admin.gynec.preview', compact('investigationReport','gynec','surgicallyData'))->render()
                ]);
            }
            return ['status'=>'true'];
        }catch(Exception $e){
            log::debug($e);
            abort(500);            
        }
    }


    // here this function is use for fetch of all visit data of gynec module via gynec visit date wise
    public function gynecHistory(Request $request,$patientsId,$appointmentId = null){
        try{
            $data = [];
            $ancImagesData = [];
            $isIvfHistory = null;
            $isAncHistory = null;
            $pId = decrypt($patientsId);
            $patient = $this->OpdPatients->where('id',$pId)->first();
            $medicines = $this->Medicine->pluck('name','name');
            $date = $this->Gynec->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
            $checkGynec = $this->Gynec->where('patients_id',$pId)->orderBy('id','DESC')->first();
            $isGynec = 0;
            if($checkGynec){
                $isGynec = $checkGynec->is_gynec;
            }
            $gynec = null;
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            if($request->ajax()){
                if($checkGynec){
                    $gynec = $checkGynec;
                }
                if($request->date){
                    $gynec = $this->Gynec->where('created_at',$request->date)->first();
                    $isGynec = $gynec->is_gynec;
                    $data['gynecId'] = $gynec->id;
                }
                $ho = json_decode($gynec->ho);
                $co = json_decode($gynec->co);
                $mh = json_decode($gynec->mh);
                $oh = json_decode($gynec->oh);
                $pastHistory = json_decode($gynec->past_history);
                $oe = json_decode($gynec->oe);
                $patientsDetails = json_decode($gynec->patients_details_ho);
                $patientsInvestigation = json_decode($gynec->investigation);
                $reportImagesData = [];
                $reportImages = !empty($patientsInvestigation->report->images) ? $patientsInvestigation->report->images : null;
                if($reportImages){
                    foreach($reportImages as $key=>$row){
                        $reportImagesData[$key]['id'] = $key;
                        $reportImagesData[$key]['src'] = url($row);
                    }
                }
                $ancImages = !empty($patientsInvestigation->anc->images) ? $patientsInvestigation->anc->images : null;
                if($ancImages){
                    foreach($ancImages as $key=>$row){
                        $ancImagesData[$key]['id'] = $key;
                        $ancImagesData[$key]['src'] = url($row);
                    }
                }
                $planOfManagement = json_decode($gynec->plan_of_management);
                $investigation = json_decode($gynec->investigation);
                $treatment = json_decode($gynec->treatment);
                unset($treatment->medicinedata);
                $data['ho'] = $ho;
                $data['co'] = $co;
                $data['oh'] = $oh;
                $data['mh'] = $mh;
                $data['pastHistory'] = $pastHistory;
                $data['oe'] = $oe;
                $data['patientsDetails'] = $patientsDetails;
                $data['patientsInvestigation'] = $patientsInvestigation;
                $data['planOfManagement'] = $planOfManagement;
                $data['investigation'] = $investigation;
                $data['treatment'] = $treatment;
                $data['isGynec'] = $isGynec;
                $data['ancImagesData'] = json_encode($ancImagesData);
                $data['reportImagesData'] = json_encode($reportImagesData);
                $medicineKey = [];
                if(!empty($treatment)){
                    $medicineKey = (array)$treatment;
                    $medicineKey = array_column($medicineKey,'medicine');
                    if(!empty($medicineKey)){
                        $medicineKey = array_combine($medicineKey,$medicineKey);
                    }
                }
                $data['medicineKey'] = $medicineKey;
                // pre data
                $hoData = $this->getHoData();
                $complaints = $this->Complaint->pluck('name','name');
                $durationOfData = getDurationOfData(1)['data'];
                // need Changes
                $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
                $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
                $surgicallyData = $this->surgicallyType()['data'];
                // 
                
                $data['personalData'] = $personalData;
                $data['familyData'] = $familyData;
                $data['pastData'] = $pastData;
                $data['isGynec'] = $isGynec;
                $data['hoData'] = $hoData;
                $data['complaints'] = $complaints;
                $data['medicines'] = $medicines;
                $data['durationOfData'] = $durationOfData;
                $data['leftOvaryData'] = $leftOvaryData;
                $data['rightOvaryData'] = $rightOvaryData;
                $data['surgicallyData'] = $surgicallyData;
                $data['gynecData'] = $gynec;
                $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
                $data['editGynec'] = View::make('admin.gynec.edit',$data)->render();
               
                return $data;
            }
            $isIvfHistory = !empty($this->IvfHistory->where('patients_id',$pId)->first()) ? true : false;
            $isAncHistory = !empty($this->AncHistory->where('patients_id',$pId)->first()) ? true : false;
            return view('admin.gynec.history',compact('date','patientsId','medicines','patient','isIvfHistory','isAncHistory'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    /**
    * Store  Gynec Data
    * @param  \Illuminate\Http\Request  $nameData,$type
    * @return \Illuminate\Http\Response
    */
    public function storeGynecHoData($nameData,$type){
        if(!empty($nameData)){
            foreach($nameData as $key=>$row){
                $checkAncHoData = $this->AncHoHistory->where('name',$row)->where('type',$type)->first();
                if(!$checkAncHoData){
                    $ancHoData = new $this->AncHoHistory;
                    $ancHoData->name = $row;
                    $ancHoData->type = $type;
                    $ancHoData->save();
                }
            }
        }
        return ['status'=>true];
    }

    /**
    * Get Gynec image
    * @param  \Illuminate\Http\Request $reportType,$id,$data
    * @return \Illuminate\Http\Response
    */
    private function getImagesData($reportType,$id,$data){
        $gynec = $this->Gynec->find($id);
        $gynecInvestigation = json_decode($gynec->investigation);
        if($reportType == 'report_old'){
            $gynecData = !empty($gynecInvestigation->report) ? $gynecInvestigation->report : [];
            if(!empty($gynecData)){
                $reportImages = $this->getImagesKey($gynecData,$data)['key'];
                if(!empty($reportImages)){
                    foreach($reportImages as $row){
                        $this->removeImage($gynecData->images[$row]);
                        unset($gynecData->images[$row]);
                    }
                    $gynecArray = (array)$gynecData->images;
                    $gynecArrayData = array_values($gynecArray);
                    $gynecData->images =  $gynecArrayData;
                    $gynecInvestigation->report = $gynecData;
                    $gynec->investigation = $gynecInvestigation;
                }
            }
        }
        if($reportType == 'anc_old'){
            $gynecData = !empty($gynecInvestigation->anc) ? $gynecInvestigation->anc : [];
            if(!empty($gynecData)){
                $ancImages = $this->getImagesKey($gynecData,$data)['key'];
                if(!empty($ancImages)){
                    foreach($ancImages as $row){
                        $this->removeImage($gynecData->images[$row]);
                        unset($gynecData->images[$row]);
                    }
                    $gynecArray = (array)$gynecData->images;
                    $gynecArrayData = array_values($gynecArray);
                    $gynecData->images =  $gynecArrayData;
                    $gynecInvestigation->anc = $gynecData;
                    $gynec->investigation = $gynecInvestigation;
                }
            }
        }
        $gynec->investigation = json_encode($gynecInvestigation);
        $gynec->save();
        return ['status'=>true];
    }

    /**
    * Get Gynec imagekey
    * @param  \Illuminate\Http\Request $gynecData,$data
    * @return \Illuminate\Http\Response
    */
    private function getImagesKey($gynecData,$data){
        $imagesKey = [];
        $removedImageKey = [];
        if(!empty($gynecData->images)){
            foreach($gynecData->images as $key=>$row){
                $imagesKey[] =$key;
            }
            $removedImageKey = array_diff($imagesKey,$data);
        }
        return ['key'=>$removedImageKey];
    }

    /**
    * Store SurgicalNote
    * @param  \Illuminate\Http\Request $nameData
    * @return \Illuminate\Http\Response
    */
    private function storeSurgicalData($nameData){
        if(!empty($nameData)){
            foreach($nameData as $row){
                $checkSurgicalData = $this->SurgicalNote->whereName($row)->first();
                if(!$checkSurgicalData){
                    $surgicalNoteData = new $this->SurgicalNote;
                    $surgicalNoteData->name = $row;
                    $surgicalNoteData->created_by = Auth::user()->id;
                    $surgicalNoteData->save();
                }
            }
        }
    }
    /**
    * Return gynec preview 
    * @param  \Illuminate\Http\Request $nameData
    * @return \Illuminate\Http\Response
    */
    public function getGynecDetails(Request $request)
    {
        try
        {
            $patient = decrypt($request->patient_id);
            $data = [];
            $date = [];
            if($request->ajax())
            {
                if($request->history_date)
                {
                    $gynec_type = 2;
                    $gynec = $this->Gynec->where('patients_id',$patient)->where('created_at',$request->history_date)->first();
                    $surgicallyData = $this->surgicallyType()['data'];
                    $investigationReport = $this->allInvestigationReport();
                    $data[] = View::make('admin.gynec.preview', compact('investigationReport','gynec','surgicallyData'))->render();
                    return response()->json([
                        'status'=> 1,
                        'gynec_type' => $gynec_type,
                        // 'id' => encrypt($gynec->id),
                        'data' => $data
                    ]);
                }
                else
                {
                    $gynec_type = 1;
                    $gynecAll = $this->Gynec->where('patients_id',$patient)->orderBy('created_at','desc')->get();
                    $surgicallyData = $this->surgicallyType()['data'];
                    $investigationReport = $this->allInvestigationReport();
                    foreach($gynecAll as $gynec)
                    {
                        $gynec = $this->Gynec->find($gynec->id);
                        $date[] = $gynec->created_at;
                        $data[] = View::make('admin.gynec.preview', compact('investigationReport','gynec','surgicallyData'))->render();
                    }
                    return response()->json([
                        'status'=>1,
                        'date'=> $date,
                        'gynec_type' => $gynec_type,
                        // 'id' => encrypt($gynec->id),
                        'data' => $data
                    ]);
                }
                return ['status'=>'true'];
            }
            else
            {
                if($request->date)
                {
                    $printPreview = 1;
                    $gynec = $this->Gynec->where('patients_id',$patient)->whereDate('created_at',$request->date)->first();
                    $surgicallyData = $this->surgicallyType()['data'];
                    $investigationReport = $this->allInvestigationReport();
                    return view('admin.gynec.preview', compact('investigationReport','gynec','surgicallyData','printPreview'));
                }
                
            }
            
        }catch(Exception $e){
            log::debug($e);
            abort(500);            
        }
        
        
    }
}
