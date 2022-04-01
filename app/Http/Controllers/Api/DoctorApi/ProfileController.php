<?php

namespace App\Http\Controllers\Api\DoctorApi;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\UserToken;

class ProfileController extends ApiController
{
    public function doctorprofile(Request $request)
    {
        $token = $request->header('Authorization');
        $userData = $this->UserToken->where('token', $token)->first();
        if($token && $userData) {
            $profileData = User::where('id', $userData->user_id)->first();
            return $this->sendResponse('Your DoctorProfile successfully get',$profileData);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
