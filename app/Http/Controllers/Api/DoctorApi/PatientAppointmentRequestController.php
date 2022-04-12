<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Api\ApiController;


class PatientAppointmentRequestController extends ApiController
{
    /**
    *Get patient appointment request list
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function PatientAppointmentRequest(Request $request){
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();

        if($token && $UserData) {
            $appointmentRequestList = collect($this->AppointmentRequest
            ->select('id','patients_id','seen_by','appointment_date','appointment_time',
             \DB::raw('(CASE
             WHEN is_book = "0" THEN "pending"
             WHEN is_book = "1" THEN "approve"
             WHEN is_book = "2" THEN "reject"
             END) AS is_book'))
            ->where('seen_by',$UserData->user_id)->get())->map(function($q){
                $q->doctor = $q->getSeenBy['name'];
                $q->patient = $q->getPatients['name'];
                $q->profile_picture = $q->getPatients['profile_picture'];
                // $q->category = $q->getPatientCategories['name'];
                unset($q->getPatients,$q->getSeenBy);
                return $q;
            });


            return $this->sendResponse('Your appointment request successfully get',$appointmentRequestList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
