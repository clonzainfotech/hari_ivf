<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Exception;
use View;
use Auth;
use Log;

class StichController extends AdminController
{
    /**
    * Return on stich create page
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request,$id){
        try{
            $pId = decrypt($id);
            $patient = $this->OpdPatients->find($pId);
            $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $hoData = $this->getHoData();
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name')->toArray();
            $lastAppointment = $this->Appointment->wherePatientsId($pId)->orderBy('id','DESC')->first();
            return view('admin/stich/create',compact('patient','hospitalDoctor','hoData','complaints','medicines','lastAppointment'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Store stich data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        try{
            $patientId = decrypt($request->patient_id);
            $stich = $this->Stich;
            if($request->stich_id){
                $stich = $this->Stich->find(decrypt($request->stich_id));
            }
            $stich->patients_id = $patientId;
            $stich->seen_by = $request->seen_by;
            $stich->created_by = Auth::user()->id;
            $stich->co = json_encode($request->co);
            $stich->stich_line = json_encode($request->stich_line);
            $stich->ho = json_encode($request->ho);
            $stich->oe = json_encode($request->oe);
            $this->complaintStore($request->co);
            // if(!empty($request->treatment['medicinedata'])){
            //     $this->medicineData($request->treatment['medicinedata']);
            //     $this->treatmentData($request->treatment);
            // }
            $stich->treatment = json_encode($request->treatment);
            $stich->save();
            // update appointment flag
            $now = Carbon::now()->format('Y-m-d');
            $is_medicine_given_from_opd = isset($request->treatment['medicinedata'][0]) && !empty($request->treatment['medicinedata'][0]) ? 0 : 2; // 0= given from opd but not done from medical, 2= not given from opd
            $appointmentFlag = $this->Appointment->wherePatientsId($patientId)->where('date',$now)->update(['is_done'=>1,'seen_by'=>$stich->seen_by,'in_consulting_room'=>0,'is_medicine_given'=>$is_medicine_given_from_opd]);
            $updateConsulting = $this->Appointment->wherePatientsId($patientId)->where('date',$now)->update([]);

            if(!empty($request->oe['follow_up'])){
                $followupDate = $request->oe['follow_up'];
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
                $appointment = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                if($appointment){
                    $appointmentData['appointmentId'] = encrypt($appointment->id);
                    $appointmentData['date'] = $followDate;
                    $appointmentData['time'] = $appointmentTime;
                    $nextAppointment = $this->nextAppointmentData($appointmentData);
                }
            }
            if($request->isprint){
                return response()->json([
                    'status'=>2,
                    'id' => encrypt($stich->id),
                    'data' => View::make('admin.stich.preview', compact('stich'))->render()
                ]);
            }
            return ['status'=>1];
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Get stach History
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getStichHistory(Request $request,$pId){
        try{
            $patientId = decrypt($pId);
            $patient = $this->OpdPatients->find($patientId);
            $date = $this->Stich->where('patients_id',$patientId)->pluck('created_at','created_at')->toArray();
            $medicines = $this->Medicine->pluck('name','name');
            $stich = null;
            if($request->ajax()){
                if($request->date){
                    $stich = $this->Stich->where('created_at',$request->date)->first();
                    $ho = json_decode($stich->ho);
                    $co = json_decode($stich->co);
                    $oe = json_decode($stich->oe);
                    $stichLine = json_decode($stich->stich_line);
                    $treatment = json_decode($stich->treatment);
                    unset($treatment->medicinedata);
                    $data['ho'] = $ho;
                    $data['co'] = $co;
                    $data['oe'] = $oe;
                    $data['stichLine'] = $stichLine;
                    $data['treatment'] = $treatment;
                    $data['stich'] = $stich;
                    $medicineKey = [];
                    if(!empty($treatment)){
                        $medicineKey = (array)$treatment;
                        $medicineKey = array_column($medicineKey,'medicine');
                        if(!empty($medicineKey)){
                            $medicineKey = array_combine($medicineKey,$medicineKey);
                        }
                    }
                    $data['medicineKey'] = $medicineKey;
                    $data['stichId'] = $stich->id;
                }
                $hoData = $this->getHoData();
                $complaints = $this->Complaint->pluck('name','name');
                $data['hoData'] = $hoData;
                $data['complaints'] = $complaints;
                $data['medicines'] = $medicines;
                $data['stich'] = $stich;
                $data['patient'] = $patient;
                $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();;
                $data['editGynec'] = View::make('admin.stich.edit',$data)->render();
                return $data;
            }
            return view('admin.stich.history',compact('pId','patient','date','medicines'));
        }catch(Exception $e){
            abort(500);
        }
    }
    /**
     * return all appointment wise ivf view
     * @return  view
     * @param 
     */
    public function getStichAppointmentWiseVisit($date,$patient_id)
    {
        try
        {

            $patientId = decrypt($patient_id);
            $stich = $this->Stich->where('created_at',$date)->where('patients_id',$patientId)->first();
            return View::make('admin.stich.preview', compact('stich'))->render();
        }catch(Exception $e){
            log::Debug($e);
            // abort(500);
        }

    }

}
