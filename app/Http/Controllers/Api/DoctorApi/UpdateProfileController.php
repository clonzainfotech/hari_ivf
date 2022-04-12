<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\UserToken;
use App\User;

class UpdateProfileController extends ApiController
{
     /**
    *updated userprofile
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function doctorupdateprofile(Request $request)
    {
        $token = $request->header('Authorization');
        $userData = $this->UserToken->where('token', $token)->first();
        if($token && $userData) {
            $updateProfile=User::Select('id','name','email','degree','mobile_number','profile_picture')->where('id', $userData->user_id)->first();
            $updateProfile->update($request->all());
            return $this->sendResponse('Your DoctorProfile successfully updated',$updateProfile);

        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
