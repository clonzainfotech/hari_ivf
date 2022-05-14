<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Api\ApiController;
use Carbon\Carbon;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;

class DoctorController extends ApiController
{
    /**
    * Get appointment
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
    public function appointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $per_page = isset($request->per_page) ? $request->per_page : '';
        $page = isset($request->page) ? $request->page : '';
        $date = isset($request->date) ? $request->date : '';

        if($token && $UserData) {
            $appointmentList = collect($this->Appointment->select('id','patients_id','category_id','date','time','seen_by')->where('seen_by',$UserData->user_id)->whereDate('date',$date)->paginate($per_page, $page)->all())->map(function($q) {
                $q->profile_picture = $q->getPatientsDetails['profile_picture'];
                $q->patient_name = $q->getPatientsDetails['name'];
                $q->category = $q->categoryDetails['name'];
                unset($q->categoryDetails,$q->getPatientsDetails);
                return $q;
            });
            return $this->sendResponse('Your appointment successfully get',$appointmentList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

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

            $totalApp = $this->Appointment::where('is_procedure', 0)->where('seen_by', $user_id )->where('date', Carbon::now()->format('Y-m-d'))->get()->count();
            $completeApp = $this->Appointment::where('is_done', 1)->where('seen_by', $user_id )->get()->count();
            $upcomingApp = $this->Appointment::where('is_procedure', 0)->where('seen_by', $user_id )->where('date','>', Carbon::now()->format('Y-m-d'))->get()->count();
            $cancellApp = $this->Appointment::where('arrival_time', null)->where('seen_by', $user_id )->where('is_done',0)->get()->count();

            $appointmentdata =['today_appointment'=>$totalApp,'completed_appointment'=>$completeApp, 'upcoming_appointment'=>$upcomingApp, 'cancell_appointment'=>$cancellApp];
            return $this->sendResponse('Successfully', $appointmentdata);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    *Get DoctorProfile
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
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
