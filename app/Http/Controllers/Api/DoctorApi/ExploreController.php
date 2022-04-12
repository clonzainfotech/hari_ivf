<?php

namespace App\Http\Controllers\Api\DoctorApi;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Models\Appointment;
use Carbon\Carbon;

class ExploreController extends ApiController
{
    private $apiToken;

    public function __construct()
    {
        // Unique Token
        parent::__construct();
        $this->apiToken = uniqid(base64_encode(str_random(100)));
    }
    /**
    * Get shedule wise appointment
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function explore(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();

        if($token && $UserData) {
            $user_id = $UserData->user_id;
            $totalApp =Appointment::where('is_procedure', '=', 0)
            ->where('seen_by', '=', $user_id )
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->get()
            ->count();

            $completeApp = Appointment::where('is_done', '=', 1)
            ->get()
            ->count();

            $upcomingApp = Appointment::where('is_procedure', '=', 0)
            ->where('seen_by', '=', $user_id )
            ->where('date','>', Carbon::now()->format('Y-m-d'))
            ->get()
            ->count();

            $cancellApp = Appointment::where('arrival_time', '=', null)
            ->where('is_done','=',1)
            ->get()
            ->count();
            $appointmentdata =['today_appointment'=>$totalApp,'completed_appointment'=>$completeApp, 'upcoming_appointment'=>$upcomingApp, 'cancell_appointment'=>$cancellApp];
            return $this->sendResponse('Successfully', $appointmentdata);
        }
        return $this->sendError(__('auth.failed'), 401);
    }


}
