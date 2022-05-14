<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;

class NotificationController extends ApiController
{
    /**
     *Get Notification list
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function notification(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $per_page = isset($request->per_page) ? $request->per_page : '';
        $page = isset($request->page) ? $request->page : '';
        //  dd($per_page);
        if ($token && $UserData) {
            $notification = $this->Notification->select(
                'id',
                'module',
                'message',
                'status',
                'created_at as date',
                \DB::raw('(CASE
                WHEN module = "1" THEN "appointment"
                WHEN module = "2" THEN "leave"
                WHEN module = "3" THEN "anc"
                WHEN module = "4" THEN "ivf"
                WHEN module = "5" THEN "iui"
                WHEN module = "6" THEN "event"
                WHEN module = "7" THEN "opd"
                WHEN module = "8" THEN "remind"
                WHEN module = "9" THEN "payment"
                END) AS module')
            )->where('user_type', 0)->where('user_id', $UserData->user_id)->orderBy('created_at', 'desc')->limit($per_page)->offset(($page - 1) * $per_page)->get()->toArray();
            $msg = 'Notification not found';
            if (!empty($notification)) {
                $msg = 'Get Notification successfully';
            }
            // dd($notification);

            return $this->sendResponse($msg, $notification);

            // return $this->sendResponse('Your Notification successfully get',$notificationList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
     *Get procedures list
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function proceduresList(Request $request)
    {
        $token = $request->header('Authorization');
        $per_page = isset($request->per_page) ? $request->per_page : '';
        $page = isset($request->page) ? $request->page : '';
        $date = isset($request->date) ? $request->date : 'date_format:y-m-d';
        if($token){
            $procedureslist = collect($this->ProcedureList->select('patients_id','procedure','date','description','remark')->where('date',$date)->paginate($per_page, $page)->all())->map(function($q){
                $q->name = $q->getPatientsDetails['name'];
                $q->mobile_number = $q->getPatientsDetails['mobile_number'];
                $q->other_mobile_number = $q->getPatientsDetails['other_mobile_number'];
                 unset($q->getPatientsDetails);
                return $q;
            });
            return $this->sendResponse('Your proceduresList successfully get',$procedureslist);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
