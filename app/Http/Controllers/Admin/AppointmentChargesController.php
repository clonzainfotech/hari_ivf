<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Exception;
use Log;
use Auth;
use View;
use DateTime;
use Carbon\Carbon;

class AppointmentChargesController extends AdminController
{
    /**
     * Add  Appointment charges
     * @return  true or false
     * @param $request
     */
    public function store(Request $request){
        if(!$request->no_opd){
            $this->validate($request,[
                'procedure' => 'nullable|numeric',
                'nst' => 'nullable|numeric',
                'cut' => 'nullable|numeric',
                'usg' => 'nullable|numeric',
                'ivf' => 'nullable|numeric',
                'drashing' => 'nullable|numeric',
                'payment_mode' => 'required'
            ]);
        }

        try {
            if($request->select_appointment_id){
                $appointmentId = decrypt($request->select_appointment_id);
                $getOpdData =  $this->Appointment->with('getPatientsDetails')->where('id',$appointmentId)->first();
                $refDoctorId =  $getOpdData->getPatientsDetails->reference_doctor_id;
                $appointmentChargesData = $this->AppointmentCharges->where('appointment_id',$appointmentId)->first();
            }
            if($request->no_opd){
                if($appointmentChargesData){
                    $appointmentChargesData->delete();
                }
            }else{
                $chargeTypes = $this->getChargesTypes($request);
                if($appointmentChargesData){
                    $appointmentCharges = $appointmentChargesData;
                }else{
                    $appointmentCharges = $this->AppointmentCharges;
                }
                $extraField1 = is_array($request->extra_field1) ? $request->extra_field1 : [];
                $extraField2 = is_array($request->extra_field2) ? $request->extra_field2 : [];
                $extra = [$extraField1,$extraField2];
                if(count(array_filter($extraField1)) > 0 || count(array_filter($extraField2)) > 0){
                    $serialize = serialize($extra);
                    $appointmentCharges->extra_field = $serialize;
                } else {
                    $appointmentCharges->extra_field = null;
                }
                $appointmentCharges->appointment_id = $appointmentId;
                $appointmentCharges->refdoctor_id = $refDoctorId;
                $appointmentCharges->consulting_charges = $request->consulting_charges;
                $appointmentCharges->nst = $request->nst;
                $appointmentCharges->cut = $request->cut;
                $appointmentCharges->procedure = $request->procedure;
                $appointmentCharges->usg = $request->usg;
                $appointmentCharges->dressing = $request->dressing;
                $appointmentCharges->discount = $request->discount;
                $appointmentCharges->ivf = $request->ivf;
                $appointmentCharges->total = $request->total;
                $appointmentCharges->charge_types = $chargeTypes;
                $appointmentCharges->payment_mode = $request->payment_mode;
                $appointmentCharges->netamount = $request->netamount;
                $appointmentCharges->created_by = Auth::user()->id;
                $appointmentCharges->updated_at = Carbon::now()->format('Y-m-d H:i:s');
                $data = $appointmentCharges->save();
                $appointmentTime = $this->Appointment->where('id', $appointmentId)->value('arrival_time');
                if($appointmentTime == null){
                    $this->Appointment->whereId($appointmentId)->update([
                        'arrival_time' => \Carbon\Carbon::now()->format('H:i')
                    ]);
                }
                $patientid = $getOpdData->getPatientsDetails['id'];
                $refdoctor = $appointmentCharges->getAppointment->getPatientsDetails->getReferenceDoctor;
                if($refdoctor && (strtolower($refdoctor->name) != 'benner' || $refdoctor->id != 3)){
                    $this->SmsManager->sendAlrtOpdToDoctor($patientid);
                }
            }

            return ['status'=>true];
            
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
     * Get charges
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getChargesTypes(Request $request)
    {
        $charges = [];
        if($request->consulting_charges>0) {
            $charges[] = 'Consulting';
        }
        if($request->extra_field1>0 || $request->extra_field2>0) {
            if($request->extra_field1[0]){
                $charges[] = $request->extra_field1[0];
            }
            if($request->extra_field2[0])
            {
                $charges[] = $request->extra_field2[0];
            }
        }
        if($request->nst>0) {
            $charges[] = 'Nst';
        }
        if($request->cut>0) {
            $charges[] = 'Cut';
        }
        if($request->procedure>0) {
            $charges[] = 'Procedure';
        }
        if($request->usg>0) {
            $charges[] = 'Usg';
        }
        if($request->dressing>0) {
            $charges[] = 'Dressing';
        }
        if($request->ivf>0) {
            $charges[] = 'Ivf';
        }

        $charge = null;
        if(count($charges)>0){
            $charge = implode(',',$charges);
        }
        return $charge;
    }

     /**
     * Update
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        try{

        }catch(Exception $e){
            
        }
    }

     /**
     * Get Appoitment charges using appointment_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentChargesData(Request $request){
        try {
            $appointmentId = decrypt($request->appointment_id);
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
            
            if($request->isprint==1){
                return response()->json([
                    View::make('admin.appointment.printopd', compact('appointmentCharges','extraField1','extraField2'))->render()
                ]);
            }
            return ['appointmentData'=>$appointmentCharges,'extraField1'=>$extraField1,'extraField2'=>$extraField2];
        }catch(Exception $e){
            abort(500);
        }
    }

}
    
