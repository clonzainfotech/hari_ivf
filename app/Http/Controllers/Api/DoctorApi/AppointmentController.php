<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\UserToken;
use App\Models\Appointment;



class AppointmentController extends ApiController
{
    /**
    * Get appointment
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function appointment(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();

        if($token && $UserData) {
            $appointmentList = collect($this->Appointment->select('id','patients_id','category_id','date','time','seen_by')->where('seen_by',$UserData->user_id)->get())->map(function($q){
                $q->profile_picture = $q->getPatientsDetails['profile_picture'];
                $q->patient_name = $q->getPatientsDetails['name'];
                $q->category = $q->categoryDetails['name'];
                unset($q->categoryDetails,$q->getPatientsDetails);
                return $q;
            });
            return $this->sendResponse('Your appointment successfully get',$appointmentList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
