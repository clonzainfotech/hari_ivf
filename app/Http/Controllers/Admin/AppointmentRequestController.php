<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Session;
use Exception;
use View;
use Log;

class AppointmentRequestController extends AdminController
{
    /**
    * Return appointment request blade
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request) {
        try{
            $now = date('Y-m-d');
            $pastAppointment = $this->AppointmentRequest->where('is_book','0')->where('appointment_date','<',$now)->update(['is_book'=>2]);
            $appointmentRequest = $this->AppointmentRequest->where('is_book',0)->orderBy('appointment_date','DESC')->get();
            if($request->ajax()){
                $data['status'] = 1;
                $data['appointmentData'] = View::make('admin.appointment.appointmentrequest.apppointment_request_print',compact('appointmentRequest'))->render();
                return $data;
            }
            return view('admin.appointment.appointmentrequest.index',compact('appointmentRequest'));
        }catch(\Exception $e){
            abort(500);
        }
    }
    /**
    * Return appointment request blade
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function appointmentApprove($id, Request $request) {
        try{
            if($request->ajax()) {
                $apRequestId = decrypt($id);
                if($apRequestId) {
                    $appointmentRequests = $this->AppointmentRequest->where('id',$apRequestId)->first();
                    // $appointmentData = $this->checkLastAppointmentStatus($appointmentRequests);
                    $appointmentData = true;
                    
                    // $this->storeAppointmentNotification($appointmentRequests->patients_id,1);

                    $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($request);
                    $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                    $totalAppoointment = count($hospitalTime);
                    $aTime = !empty($nextAppontment['time']) || (isset($nextAppontment['time']) && $nextAppontment['time'] == 0) ? $hospitalTime[$nextAppontment['time']] : null;
                    $lastAppointment = $this->Appointment->where('patients_id',$appointmentRequests->patients_id)->orderBy('id','DESC')->first();
                    $checkAppointment = $this->Appointment->whereDate('date',$appointmentRequests->appointment_date)->count();
                    if($totalAppoointment != $checkAppointment){
                        $appointment = $this->Appointment;
                        $appointment->date = $appointmentRequests->appointment_date;
                        $appointment->time = $aTime;
                        $appointment->created_by = Auth::User()->id;
                        $appointment->seen_by = $appointmentRequests->seen_by;
                        $appointment->category_id = $lastAppointment->category_id;
                        $appointment->patients_id = $appointmentRequests->patients_id;
                        $appointment->appontment_request_id = $apRequestId;
                        $appointment->save();
                        $this->AppointmentRequest->whereId($apRequestId)->update(['is_book' => 1]);
                    }else{
                        $this->AppointmentRequest->whereId($apRequestId)->update(['is_book' => 2]);
                    }
                    $patient = $this->OpdPatients->find($appointmentRequests->patients_id);
                    if(!empty($patient->device_token))
                    {
                        $body = 'Dear ,'.ucwords($patient->name).' . This is Confirmation that you have booked appointment on '.\Carbon\Carbon::parse($appointment->date)->format('d M Y').' at '.$appointment->time.'. Your Appointment has been Approved. Thank You.';
                        $this->sendNotification($appointmentRequests->patients_id,$patient->device_token,$body,null);
                    }
                    return 'true';
                    // return $appointmentData['status'];

                    // if(!empty($appointmentData)) {
                    //     $nextAppointment = $appointmentData->nextAppointmentDate();
                    //     if(empty($nextAppointment)){
                    //         $this->AppointmentRequest
                    //             ->whereId($apRequestId)
                    //             ->update(['is_book' => 1]);

                    //         $appointmentData=$this->Appointment;
                    //         $appointmentData->date = $appointmentRequests->appointment_date;
                    //         $appointmentData->created_by = Auth::User()->id;
                    //         $appointmentData->patients_id = $appointmentRequests->patients_id;
                    //         $appointmentData->appontment_request_id = $apRequestId;
                    //         $appointmentData->save();
                    //         return ['status' => true];
                    //     }
                    //     Session::flash('msg',"You can't approve appointment for this patients because he has already appointment !");
                    //     return ['status' => false];
                    // }
                    // else {
                    //     $this->AppointmentRequest
                    //         ->whereId($apRequestId)
                    //         ->update([
                    //             'is_book' => 1
                    //         ]);

                    //     $appointmentData=$this->Appointment;
                    //     $appointmentData->date = $appointmentRequests->appointment_date;
                    //     $appointmentData->created_by = Auth::User()->id;
                    //     $appointmentData->patients_id = $appointmentRequests->patients_id;
                    //     $appointmentData->appontment_request_id = $apRequestId;
                    //     $appointmentData->save();

                    //     return ['status' => true];
                    // }
                }
            }
        }catch(Exception $e){
            abort(500);
        }
    }

    public function appointmentReject($id, Request $request) {
        if($request->ajax()) {
            $apRequestId = decrypt($id);
            if($apRequestId) {
                $appRequestData = $this->AppointmentRequest->whereId($apRequestId)->first();
                $appRequestData->is_book = 2;
                $appRequestData->remark = (!empty($request->reason)) ? $request->reason : '';
                $appRequestData->save();
                // $this->storeAppointmentNotification($appRequestData->patients_id,0);
                $patient = $this->OpdPatients->find($appRequestData->patients_id);
                    if(!empty($patient->device_token))
                    {
                        $body = 'Dear ,'.ucwords($patient->name).' . This is Inform you that you have booked appointment on '.\Carbon\Carbon::parse($appRequestData->appointment_date)->format('d M Y').' is Rejected due to some reason. For more information contact to Radha IVF center. Thank You.';
                        $this->sendNotification($appointmentRequests->patients_id,$patient->device_token,$body,null);
                    }
                    //$this->Notification::sendNotificationToPatients($apRequestId);
                return ['status' => true];
            }
            return ['status' => false];
        }
    }

    private function checkLastAppointmentStatus($appointmentRequests){
        $checkAppointment = $this->Appointment->wherePatientsId($appointmentRequests->patients_id)->orderBy('id','DESC')->first();
        $status = true;
        $appointmentId = null;
        if($checkAppointment){
            $appointmentId = $checkAppointment->id;
            if(!$checkAppointment->getAppointmentCharges || !$checkAppointment->arrival_time){
                $status = false;
            }
        }
        if($status && !$appointmentId){
            $checkAppointment = $this->Appointment;
        }
        $checkAppointment->date = $appointmentRequests->appointment_date;
        $checkAppointment->created_by = Auth::User()->id;
        $checkAppointment->category_id = $checkAppointment->category_id;
        $checkAppointment->patients_id = $appointmentRequests->patients_id;
        $checkAppointment->appontment_request_id = $appointmentRequests->id;
        $checkAppointment->save();
        return ['status'=>'true'];
    } 

    /**
    * Self Booking list return
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getSelfBookingList(Request $request)
    {
        try{
            if($request->ajax()) 
            {
                $patients = $this->PatientSignup->where('is_approved','0');
                if($request->search)
                {
                    $search = $request->search;
                    $patients = $patients->where(function($query) use($search){
                        $query->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('mobile_number', 'LIKE', '%'.$search.'%')
                        ->orWhere('created_at', 'LIKE', '%'.$search.'%');
                    });
                }
                $patients = $patients->paginate(100);
                $data['status'] = 1;
                $data['selfBookingList'] = View::make('admin.appointment.self_booking.data',compact('patients'))->render();
                return $data;  
            }
            return view('admin.appointment.self_booking.index');
        }catch(\Exception $e){
            log::debug($e);
            abort(500);
        }
    }
}
