<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;

class MyPatientController extends ApiController
{

    /**
    * Get patient appointment
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function doctorpatient(Request $request){
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();

        if($token && $UserData) {
            $patientList = collect($this->OpdPatients->select('id','code','name','dob','mobile_number','profile_picture','reference_doctor_id')->where('hospital_doctor_id',$UserData->user_id)->get())->map(function($q){
                $q->reference_doctor = $q->getReferenceDoctor['name'];
                 $q->category = $q->lastDoneAppointmentData->categoryDetails['name'];
                 unset($q->getReferenceDoctor,$q->lastDoneAppointmentData);
                return $q;

            });
            // dd($patientList);

            return $this->sendResponse('Your patients successfully get',$patientList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
