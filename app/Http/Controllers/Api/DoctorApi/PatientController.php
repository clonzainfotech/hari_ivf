<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Models\Appointment;
use Validator;
use Carbon\Carbon;

class PatientController extends ApiController
{
    /**
    * Get patient appointment
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function doctorpatient(Request $request){
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $per_page = isset($request->per_page) ? $request->per_page : '';
        $page = isset($request->page) ? $request->page : '';

        if($token && $UserData) {
            $patientList = collect($this->OpdPatients->select('id','code','name','dob','mobile_number','profile_picture','reference_doctor_id')->where('hospital_doctor_id',$UserData->user_id)->paginate($per_page, $page)->all())->map(function($q){
                $q->reference_doctor = $q->getReferenceDoctor['name'];
                $q->category = isset($q->lastDoneAppointmentData->categoryDetails['name']) ? $q->lastDoneAppointmentData->categoryDetails['name'] : '';
                 unset($q->getReferenceDoctor,$q->lastDoneAppointmentData);
                return $q;

            });
            // dd($patientList);

            return $this->sendResponse('Your patients successfully get',$patientList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    *Get patient appointment request list
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function PatientAppointmentRequest(Request $request){

        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $opd_time = isset($request->opd) ? $request->opd : '';
        $doctor_id = isset($request->doctor_id) ? $request->doctor_id : '';
        // dd($doctor_id);
        if($token && $UserData) {
            $appointmentRequestList = collect($this->AppointmentRequest
            ->select('id','patients_id','seen_by','appointment_date','appointment_time','created_at',
             \DB::raw('(CASE
             WHEN is_book = "0" THEN "pending"
             WHEN is_book = "1" THEN "approve"
             WHEN is_book = "2" THEN "reject"
             END) AS is_book'))
            ->where('seen_by',$UserData->user_id)->get())->map(function($q) use($opd_time){
                $q->doctor = $q->getSeenBy['name'];
                $q->patient = $q->getPatients['name'];
                $q->profile_picture = $q->getPatients['profile_picture'];
                $q->category = $q->lastAppointmentData->categoryDetails['name'];
                $q->time = (strtotime(\Carbon\Carbon::parse($q->appointment_time)->format('g:i')) > strtotime('9:00') &&   strtotime(\Carbon\Carbon::parse($q->appointment_time)->format('g:i')) < strtotime('12:00') ? 'morning' : 'evening');
                if($opd_time == $q->time || $opd_time == null){
                    unset($q->getPatients,$q->getSeenBy,$q->lastAppointmentData);
                    return $q;
                }
            });
            $appointmentList = $appointmentRequestList->filter(function ($value, $key) {
                return $value != null;
            });
            return $this->sendResponse('Your appointment request successfully get',$appointmentList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

     /**
    *Get today appointment list
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function doctortodaypatients(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $opd_time = isset($request->opd) ? $request->opd : '';

        if($token && $UserData) {
            $appointmentList = collect($this->Appointment->select('id','patients_id','category_id','date','time','seen_by')->where('seen_by',$UserData->user_id)->whereDate('created_at', Carbon::today())->get())->map(function($q)use($opd_time){
                $q->doctor = $q->getSeenBy['name'];
                $q->mobile_number = $q->getSeenBy['mobile_number'];
                $q->profile_picture = $q->getPatientsDetails['profile_picture'];
                $q->patient_name = $q->getPatientsDetails['name'];
                $q->category = $q->categoryDetails['name'];
                $q->time = (strtotime(\Carbon\Carbon::parse($q->time)->format('g:i')) > strtotime('9:00') &&   strtotime(\Carbon\Carbon::parse($q->time)->format('g:i')) < strtotime('12:00') ? 'morning' : 'evening');
                if($opd_time == $q->time || $opd_time == null){
                    unset($q->getPatientsDetails,$q->getSeenBy,$q->categoryDetails);
                    return $q;
                }
            });
            $appointmentlist = $appointmentList->filter(function ($value, $key) {
                return $value != null;
            });
            return $this->sendResponse('Your Today appointment successfully get',$appointmentlist);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
     /**
    *Get Patient Details
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function patientDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patients_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $patients_id = isset($request->patients_id) ? $request->patients_id : '';
        if($token && $UserData) {
            $patientdetails = collect($this->OpdPatients->select('id','code','name','dob','mobile_number','residence','location','other_mobile_number','weight')->where('hospital_doctor_id',$UserData->user_id)->where('id',$patients_id)->get())->map(function($q){
                $q->date = $q->lastDoneAppointmentData['date'];
                $q->time = $q->lastDoneAppointmentData['time'];
                 unset($q->lastDoneAppointmentData);
                return $q;
            });
            return $this->sendResponse('Your Patient Details successfully get',$patientdetails);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
     /**
    *Get Appointment Patient Details
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function appointmentpatientDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patients_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $patients_id = isset($request->patients_id) ? $request->patients_id : '';

        if($token && $UserData) {
            $appointment = $this->Appointment->select('id','patients_id','category_id','date','time','remark')->where('patients_id',$patients_id)->latest()->take(1)->first();
            $appointment->profile_picture = $appointment->getPatientsDetails['profile_picture'];
            $appointment->patient_name = $appointment->getPatientsDetails['name'];
            $appointment->mobile_number = $appointment->getPatientsDetails['mobile_number'];
            $appointment->date_of_birth = $appointment->getPatientsDetails['dob'];
            $appointment->last_visit = $appointment->lastAppointmentData['date'];
            $appointment->age = $appointment->getPatientsDetails['age'];
            $appointment->weight = $appointment->getPatientsDetails['weight'];
            $appointment->category = $appointment->categoryDetails['name'];

            // $patientdetails = collect($this->Appointment->select('id','patients_id','category_id','date','time','remark')->where('patients_id',$patients_id)->latest()->take(1)->get())->map(function($q) {
            //     $q->profile_picture = $q->getPatientsDetails['profile_picture'];
            //     $q->patient_name = $q->getPatientsDetails['name'];
            //     $q->mobile_number = $q->getPatientsDetails['mobile_number'];
            //     $q->date_of_birth = $q->getPatientsDetails['dob'];
            //     $q->last_visit = $q->lastAppointmentData['date'];
            //     $q->age = $q->getPatientsDetails['age'];
            //     $q->weight = $q->getPatientsDetails['weight'];
            //     $q->category = $q->categoryDetails['name'];
                unset($appointment->categoryDetails,$appointment->getPatientsDetails,$appointment->lastAppointmentData);
            //     return $q;
            // });
            // dd($appointment);
            // $appointment_PopupDetail = new AppointmentController;

            // $appointment->note = $appointment_PopupDetail->appointmentPopupDetail($patients_id, $appointment->date, $appointment->categoryid);
            // dd($appointment->note);
            return $this->sendResponse('Your Appointment Patient Details successfully get',$appointment);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}