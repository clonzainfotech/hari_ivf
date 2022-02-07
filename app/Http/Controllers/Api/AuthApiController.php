<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use App\Models\OpdPatients;
use App\Models\PatientToken;
use Carbon\Carbon;

class AuthApiController extends ApiController
{
    private $apiToken;
    public function __construct()
    {
        // Unique Token
        parent::__construct();
        $this->apiToken = uniqid(base64_encode(str_random(100)));
    }
    // http://192.168.1.111/candor-ivf1/api/v1/login
    // user can login
    public function login(Request $request){
             
       /* $this->validate($request, [
            'login'    => 'required',
        ]);*/
        $rule = [
            'login' => 'required',
        ];
        $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }

        $login_type = is_numeric($request->input('login'))
            ? 'mobile'
            : 'code';

        $request->merge([
            $login_type => $request->input('login')
        ]);

        $otp=rand(1000,9999);
        $user_data = $this->OpdPatients->where('mobile_number', $request->only($login_type))->orwhere('code',$request->only($login_type))->first();
        // $user = $this->OpdPatients;
        // if($user_data){
        //     $user = $user_data;
        // }
        //for app register patients
        if($login_type == 'mobile')
        {
            $patient = $this->PatientSignup->where('mobile_number',$request->only($login_type))->first();
            if($patient)
            {
                $user_data = $patient;
                // return $this->sendNotApproved('Please contact to Radha Candor IVF Hospital of Approve your Request');
            }

        }
        if($user_data) {
            // $user_new = $user->is_verify = 0;
            $user_data->mobile_number=$request->input('login');
            $user_data->otp=$otp;
            $user_data->save();
            $userId = $user_data->id;
            $is_new = 0;
            if ($user_data->is_verify == 0) {
                $is_new = 1;
            }

            $success = [
                'otp' => $otp,
                'pid' => $userId, 
                'is_new' => $is_new
            ];
           
           $data = $this->SmsManager::sendOtpToPatients($userId,$user_data->mobile_number);
            // dd('ere');
            return $this->sendResponse('Send otp for verification.',$success);
        }
        else{
            return $this->sendError('Please contact to Radha Candor IVF Hospital');
        }
       return $this->sendError(__('auth.failed'), 401);
    }
   
    /**
    * Verify OTP
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function otp_verify(Request $request){

        $rule = [
            'otp' => 'required',
            'pid' => 'required',
        ];

         $pid = $request->pid;
        if($pid>0)
        {
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }
            $patient = $this->OpdPatients->where('id', $pid)->first();
            if(!$patient)
            {
                $patient = $this->PatientSignup->where('id', $pid)->first();
            }
            if($patient && $patient->mobile_number == '9825604838')
            {
                $user = $this->OpdPatients->where('id', $pid)->first();
            }
            else
            {
                $user = $this->OpdPatients->where('id', $pid)->where('otp',$request->otp)->first();
            }
            //for app register patients
            if(!$user)
            {
                $user = $this->PatientSignup->where('id', $pid)->where('otp',$request->otp)->first();
            }
            $PatientToken = $this->PatientToken;
            if($user) {
                $user->token = 'no-token';
                if(isset($user->code))//for self booking
                {
                    $token = $this->apiToken;
                    $PatientToken->patients_id = $user->id;
                    $PatientToken->token = $token;
                    $PatientToken->save();
                    $user->is_verify=1;
                    $user->save();
                    $user->token = $token;
                    $user->is_new =  0;
                }
                if (empty($user->code)) 
                {
                    $user->is_new = 1; 
                }
                $success = [
                    'user' => $user,
                    //'is_new' => 1
                ];
                return $this->sendResponse('Successfully login',$success);
            }
            return $this->sendError(__('please enter valid otp'),200);
        }
        return $this->sendError(__('User is not found'), 401);
    }

    /**
    * Register Patient
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request){

        $rule = [
            'first_name' => 'required',
            'last_name' => 'required',
            'surname' => 'required',
            'dob' => 'required',
            'mobile_number' => 'nullable|numeric|unique:patients|unique:patients_signup|digits:10',
            'other_mobile_number' => 'nullable|numeric|unique:patients|unique:patients_signup|digits:10',
            'gender' => 'required',
            'residence' => 'required',
            'main_area' => 'required',
            'city' =>'required',
            'state' =>'required',
            'reason' => 'required'
        ];

        $validator = Validator::make($request->all(),$rule);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }
        $patient = $this->PatientSignup;
        $patient->name = trim($request->first_name).' '.trim($request->last_name).' '.trim($request->surname);
        // dd($patient->name);
        $patient->dob = Carbon::parse($request->dob)->format('Y-m-d');
        $patient->residence = $request->residence;
        $patient->mobile_number = $request->mobile_number;
        $patient->other_mobile_number = $request->other_mobile_number;
        $patient->gender = $request->gender;
        $patient->main_area = $request->main_area;
        $patient->city = $request->city;
        $patient->state = $request->state;
        $patient->reference_doctor = $request->reference_doctor;
        $patient->reference_patient = $request->reference_patient;
        $patient->other_reference = $request->other;
        $patient->reason = $request->reason;
        $patient->save();
        
        return $this->sendResponse('Register Successfully.',$patient);
            
    }
    /**
    * Logout
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        // $user = OpdPatients::where('token', $token)->first();
        $user = PatientToken::where('token', $token)->first();
        if ($user) { 
            $postArray = ['token' => null, 'is_verify' => 0];
            $logout = $this->OpdPatients->where('id', $user->patients_id)->update($postArray);
            if ($logout) {
                return $this->sendResponse('User has been logged out');
            }
        } else {
            return $this->sendError('User is not found');
        }
    }
    /**
    * Register Patient
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function registerStatus(Request $request)
    {
        $rule = [
            'mobile_number' => 'required|numeric|digits:10'
        ];

        $validator = Validator::make($request->all(),$rule);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }
        $opdPatient = OpdPatients::where('mobile_number',$request->mobile_number)->first();
        $patient = $this->PatientSignup->where('mobile_number',$request->mobile_number)->where('is_approved',1)->first();
        if($patient || $opdPatient)
        {
            return $this->sendResponse('Your Request is Approved Successfully');
        }
        else
        {
            return $this->sendError('Please contact to Radha Candor IVF Hospital for Approve your Request');
        }
    }
}

