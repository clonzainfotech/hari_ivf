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
            )->where('user_type', 0)->where('user_id', $UserData->user_id)->orderBy('created_at', 'desc')->paginate(20);
            $msg = 'Notification not found';
            if (!empty($notification)) {
                $msg = 'Get Notification successfully';
            }
            return $this->sendResponse($msg, $notification);

            // return $this->sendResponse('Your Notification successfully get',$notificationList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
