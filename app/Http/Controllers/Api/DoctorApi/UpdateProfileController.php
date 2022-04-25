<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\UserToken;
use Validator;
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
        $get_token = $this->UserToken->where('token', $token)->first();
        if($token && $get_token) {
            $rule = [
                'mobile_number' => 'numeric|digits:10'
            ];

            $validator = Validator::make($request->all(),$rule);

            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }

            $updateProfile=User::Select('id','name','email','degree','mobile_number')->where('id', $get_token->user_id)->first();
            $updateProfile->update($request->all());
            $UserData = User::where('id', $get_token->user_id)->first();
            $file_name = basename($UserData->profile_picture);
            $image_path = "public/upload/patient/".$file_name;
            if ($request->hasFile('profile_picture')) {
                $this->removeImage($image_path);
                $image = $request->file('profile_picture');
                $profilePicture = $this->uploadImage($image, 'public/upload/patient');
                $UserData->profile_picture = url('public/upload/patient/'.$profilePicture);
            }

            $UserData->save();
            return $this->sendResponse('Update Doctor profile successfully',$UserData);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
