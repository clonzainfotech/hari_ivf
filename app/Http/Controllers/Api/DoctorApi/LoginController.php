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
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $user = User::where('email', $request->email)->first();

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
