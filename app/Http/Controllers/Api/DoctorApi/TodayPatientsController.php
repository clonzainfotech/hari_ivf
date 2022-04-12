<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Api\ApiController;
use Carbon\Carbon;

class TodayPatientsController extends ApiController
{
     /**
    *Get today appointment list
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function doctortodaypatients(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();

        if($token && $UserData) {
            $appointmentList = collect($this->Appointment->select('id','patients_id','category_id','date','time','seen_by')->where('seen_by',$UserData->user_id)->whereDate('created_at', Carbon::today())->get())->map(function($q){
                $q->doctor = $q->getSeenBy['name'];
                $q->mobile_number = $q->getSeenBy['mobile_number'];
                $q->profile_picture = $q->getPatientsDetails['profile_picture'];
                $q->patient_name = $q->getPatientsDetails['name'];
                $q->category = $q->categoryDetails['name'];
                unset($q->getPatientsDetails,$q->getSeenBy,$q->categoryDetails);
                return $q;
            });
            // $data = $this->Appointment->Select('created_at')->whereDate('created_at', Carbon::today())->get();
            // dd($data);

            return $this->sendResponse('Your Today appointment successfully get',$appointmentList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
