<?php

namespace App\Http\Controllers\Api\DoctorApi;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Models\UserToken;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginController extends ApiController
{
    private $apiToken;

    public function __construct()
    {
        // Unique Token
        parent::__construct();
        $this->apiToken = uniqid(base64_encode(str_random(100)));
    }

    /**
    * user can login
    * Return API token
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $user = User::Select('id','name','email','password','dob_date','designation','degree','specialist','achievement','description','profile_picture','mobile_number','status','is_rmo_doctor','role',\DB::raw('(CASE
        WHEN role = "1" THEN "main-admin"
        WHEN role = "2" THEN "reception"
        WHEN role = "3" THEN "doctor"
        WHEN role = "4" THEN "accountant"
        WHEN role = "5" THEN "medical"
        WHEN role = "6" THEN "IVF"
        WHEN role = "7" THEN "IUI"
        WHEN role = "8" THEN "ANC"
        END) AS role'))->where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $token = $this->apiToken;
                $user_token = $this->UserToken;
                $user_token->user_id = $user->id;
                $user_token->token = $token;
                $user_token->save();
                $user->token = $token;
                return $this->sendResponse('Successfully login', $user);
            } else {
                return $this->sendError(__('please enter valid password'), 200);
            }
        } else {
            return $this->sendError(__('User is not found'), 401);
        }
    }
}
