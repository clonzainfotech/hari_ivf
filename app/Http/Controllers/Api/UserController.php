<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Base\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class UserController extends ApiController
{
    /**
    * Update patient gender and state
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request){
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if($get_token) {
            $patientData = $this->OpdPatients->select('id','name','gender','residence','state','occupation','weight','location','residence','main_area','city','state','mobile_number','age','height','dob')->where('id', $get_token->patients_id)->first();
            if(!empty($patientData)) {
                $patientData->gender = $patientData->gender == 1 ? 'Male' : 'Female';
                $patientData->state = $patientData->getState['name'];
                unset($patientData->getState);
                return $this->sendResponse('Get pateint details sucessfully',$patientData);
            }
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Update patient all detail
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {
        $token = $request->header('Authorization');

        $patientData = $this->PatientToken->where('token', $token)->first();
        if($token && $patientData) {
            $patient = $patientData->patients_id;
            if(!empty($patientData)) {

                $patients = $this->OpdPatients->find($patient);
                $patients->name = $request->name;
                $patients->gender = $request->gender;
                $patients->occupation = $request->occupation;
                $patients->dob = $request->dob;
                $patients->height = $request->height;
                $patients->weight = $request->weight;
                $patients->residence = $request->residence;
                $patients->main_area = $request->main_area;
                $patients->city = $request->city;
                $patients->state = $request->state;
                $patients->save();

                return $this->sendResponse('Update User Data Successfully');
            }
           return $this->sendError('Invalid User',401);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Get list of hospital staff
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function ourStaff(Request $request){
        $token = $request->header('Authorization');
        $patientData = $this->PatientToken->where('token', $token)->first();
        if($patientData) {

            $user = collect($this->User
                ->whereIn('id',[43,48,53,32,54,55,29,52,51,19,56])
                ->whereStatus('1')
                ->select('id','name','profile_picture','designation')
                ->orderByRaw("FIELD(designation, 'Management Person', 'HR', 'RMO Doctor','Head Nurse','OT Nurse','Head Receptionist','Receptionist','Accountant','Telly Caller') ASC")
                ->get()
            )->map(function($q){
                $image = $q->profile_picture;
                $q->profile_picture = $image ? cdnUrl($image, null) : null;
                return $q;
            });
            return $this->sendResponse('get staff successfully',$user);
        }
        return $this->sendError(__('auth.failed'), 401);
 
    }

    /**
    * Get detail of user(Hospital Member)
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getUserDetails(Request $request) {
        $token = $request->header('Authorization');
        $patientData = $this->PatientToken->where('token', $token)->first();
        if($token && $patientData) {
            $rule = [
                'user_id' => 'required',
            ];

            $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }

            $userId = $request->user_id;
            $user = $this->User->where('id',$userId)->first();
            if ($user) {
                $user->profile_picture = $user->profile_picture ? cdnUrl($user->profile_picture, null) : null;
                $user->city = 'Surat';
                $hospitalAddress = $this->HospitalAddress->first();
                $user->address = $hospitalAddress->address;
                $user->available_time = date('l').' 11:00 AM - 1:00 PM and 4:00 PM - 8:00 PM';
                $achievementData = json_decode($user->achievement);
                    $achievement = null;
                    if (!empty($achievementData)) {
                        foreach ($achievementData as $key => $value) {
                            $achievement[]['image'] = cdnUrl($value, null);
                        }
                    }
                $user->achievement = $achievement;

                return $this->sendResponse('get user detail successfully',$user);
            }
            return $this->sendError(__('User Not Found..'), 401);
        }
        return $this->sendError(__('auth.failed'), 401);
 
    }

    /**
    * Get list of hospital doctor
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function ourDoctor(Request $request){
        $token = $request->header('Authorization');
        $patientData = $this->PatientToken->where('token', $token)->first();
        if($token && $patientData) {

            $user = collect($this->User
                ->where('role',3)
                ->whereStatus('1')
                ->where('is_mobile_view',1)
                ->select(
                    'id',
                    'name',
                    'profile_picture',
                    'achievement',
                    'degree',
                    'specialist',
                    'designation',
                    'description'
                )
                ->get()
            )->map(function($q){
                $achievementData = json_decode($q->achievement);
                $achievement = null;
                if (!empty($achievementData)) {
                    foreach ($achievementData as $key => $value) {
                        $achievement[]['image'] = cdnUrl($value, null);
                    }
                }
                $q->achievement = $achievement;
                $name = $q->name;
                $q->profile_picture = $q->profile_picture ? cdnUrl($q->profile_picture, null) : null;
                return $q;
            });

            return $this->sendResponse('get doctor successfully',$user);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Get general detail of Hospital
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function aboutUs(Request $request){
        $token = $request->header('Authorization');
        $patientData = $this->PatientToken->where('token', $token)->first();
        if ($token && $patientData) {
            $hospitalAddresses = $this->HospitalAddress
                ->select(
                    'id',
                    'address',
                    'mobile',
                    'email'
                )
                ->get();

            $settings = $this->SystemSetting
                ->select(
                    'header_logo',
                    'facebook_link',
                    'instagram_link',
                    'twitter_link',
                    'linked_in_link'
                )
                ->first();

                // dd(count($hospitalAddresses));
            $data['address'] = count($hospitalAddresses) != 0 ? $hospitalAddresses : null;
            $data['header'] = $settings;
            $data['aboutus'] = config('app.hospitalname2')." is organized since 2009 as a multi-specialty center and maternity home, offering the best possible facilities and services to the patients at an affordable cost. We are dedicated in providing our services since more than 10 years and are growing from strength to strength. Our hospital having 6000 sq. ft of area has carefully allotted OPD Section, IPD Section, Medical & Surgical departments and well-designed IVF department making it a center with a feel of comfort and elegance to our patients. Our hospital is situated in Surat, one of the fastest-growing cities situated at the heart of Gujarat state with easy of accessibility through all routes of transport. And in Surat, the hospital location is very close to the railway and bus station in quite an ambient atmosphere.";

            return $this->sendResponse('success', $data);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
    * Get App version
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getAppVersion()
    {
        $system_setting = $this->SystemSetting->first();
        $data['android_version'] = $system_setting->app_android_version;
        $data['ios_version'] = $system_setting->app_ios_version;
            return $this->sendResponse('success', $data);
    }
}
