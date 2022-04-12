<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Base\Api\ApiController;

class UpdatePasswordController extends ApiController
{
     /**
    *updated password
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function doctorupdatepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' =>  'required',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        try {
            $token = $request->header('Authorization');
            $userData = $this->UserToken->where('token', $token)->first();
            if ($userData) {
                $users = $this->User::find($userData->user_id);
                if (Hash::check($request->current_password, $users->password)) {
                    if ($request->new_password == $request->confirm_password) {
                        $users = $this->User::find($userData->user_id);
                        $updatepassword = Hash::make($request->new_password);
                        $users->password = $updatepassword;
                        $users->save();
                        return $this->sendResponse('Successfully Password Updated', $users);
                    }
                    return $this->sendResponse('ConfirmPassword does not match');
                }
                return $this->sendResponse('CurrentPassword does not match');
            }
            return $this->sendError('Invalid user', 401);
        } catch (Exception $e) {
        }
    }
}
