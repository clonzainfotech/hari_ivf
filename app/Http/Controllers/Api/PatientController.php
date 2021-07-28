<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Api\ApiController;
use App\Models\OpdPatients;
use Carbon\Carbon;
use Validator;
use File;
// {{candorivf}}/getPaientDetails
class PatientController extends ApiController
{
    /**
    * Return Patient detail
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getPaientDetails(Request $request) {
        $token = $request->header('Authorization');
        // $user = OpdPatients::where('token', $token)->first();
        $get_token = $this->PatientToken->where('token', $token)->first();
        // $user->age = $user->age ? (string)$user->age : null;
        if ($get_token) {
            $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
            return $this->sendResponse('Get Patient Details', $user);
        } else {
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }   

    /**
    * Update patient profile
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function updateProfilePicture(Request $request) {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if($token && $get_token) {
            $rule = [
                'profile_picture' => 'required',
            ];
        
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }
            $patientData = OpdPatients::where('id', $get_token->patients_id)->first();
            $file_name = basename($patientData->profile_picture);
            $image_path = "public/upload/patient/".$file_name;
            
           /* if(File::exists($image_path)) {
                File::delete($image_path);
            }*/

            /*$patientData->dob = $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null; 
            $patientData->age = Carbon::parse($request->dob)->diff(Carbon::now())->format('%y years');*/

            // $patient = OpdPatients::find($patientData->id);
            if ($request->hasFile('profile_picture')) {
                $this->removeImage($image_path);
                $image = $request->file('profile_picture');
                $profilePicture = $this->uploadImage($image, 'public/upload/patient');
                $patientData->profile_picture = url('public/upload/patient/'.$profilePicture);
            }
            $patientData->save();
            return $this->sendResponse('Update patient profile successfully',$patientData);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Add patient profile
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function add_profile(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if ($get_token) {
            $user = OpdPatients::where('id', $get_token->patients_id)->first();
            if ($user && !empty($user->code)) {         
                $rule = [
                    'surname' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'dob' => 'required',
                    // 'dob' =>'nullable|before:' . date('Y-m-d'),
                    'gender' => 'required|in:1,2',
                    ];

                    $validator = Validator::make($request->all(),$rule);
                        if($validator->fails()){
                            return $this->sendError($validator->errors()->first(), 422);
                    }

                $surname = $request->surname;
                $firstname = $request->firstname;
                $lastname = $request->lastname;
                $name = strtoupper($firstname." ".$lastname." ".$surname);
                $dob = $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;
                $gender = $request->gender;
                // $age = (date('Y') - date('Y',strtotime($dob)));
                $generateCode = $this->generateCode($user->id,$name);
                $code = $generateCode['code'];

                $age = Carbon::parse($dob)->diff(Carbon::now())->format('%y years');

                $update_profile = [
                'code' => $code,
                'name' => $name,
                'dob' => $dob,
                'gender' => $gender,
                'age' => $age
                ];

                $update_data = $user->update($update_profile);
                if ($update_data) {
                    return $this->sendResponse('User Updated Successfully..',$user);
                }
            } else {
                return $this->sendError('User is not found or You are not Updated Profile');
            }
        }else{
            return $this->sendError(__('auth.failed'), 401);
        }

    }
    /**
    * Return patient code
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function generateCode($patientsId, $name)
    {
        $name = preg_replace('/\s+/', ' ', $name);
        $name = explode(' ', $name);
        $code = strtoupper($name[0][0]);

        $code .= (!empty($name[1])) ? strtoupper($name[1][0]) : 'R';
        $code .= (!empty($name[2])) ? strtoupper($name[2][0]) : 'R';

        $code .= $patientsId;
        $code = preg_replace('/[^A-Za-z0-9\-]/', 'R', $code);
        return ['code'=>$code];
    }
    /**
    * Return patient All Report
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function get_patient_report(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if ($get_token) 
        {
            $user = OpdPatients::where('id', $get_token->patients_id)->first();
            if ($user && !empty($user->code)) 
            {   
                $patients = $user->id;
                $ANCReports = [];
                $IVFReports = [];
                $IUIReports = [];
                $data = [];
                $ancAllVisit = $this->ANC->where('patients_id',$patients)->get();
                $ancAllHistoryVisit = $this->AncHistory->where('patients_id',$patients)->get();
                if($ancAllVisit)
                {
                    foreach($ancAllVisit as $ancVisit)
                    {
                        $reportDate = Carbon::parse($ancVisit->created_at)->format('Y-m-d H:i:s');
                        $investigationReport = !empty($ancVisit->investigation) ? json_decode($ancVisit->investigation,true) : '';
                        $usgReport = !empty($ancVisit->usg) ? json_decode($ancVisit->usg,true) : '';
                        if(!empty($investigationReport['investigation_early_scan_type']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'Early Scan','url' => $investigationReport['investigation_early_scan_type']['images']);
                        }
                        if(!empty($investigationReport['growth_report']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'Growth Report','url' => $investigationReport['growth_report']['images']);
                        }
                        if(!empty($investigationReport['other_report_data']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'Other Report','url' => $investigationReport['other_report_data']['images']);
                        }
                        if(!empty($investigationReport['anc']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'ANC Report','url' => $investigationReport['anc']['images']);
                        }
                        if(!empty($usgReport['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'USG Report','url' => $usgReport['images']);
                        }
                    }
                }
                if($ancAllHistoryVisit)
                {
                    foreach($ancAllHistoryVisit as $ancHistoryVisit)
                    {
                        $reportDate = Carbon::parse($ancHistoryVisit->created_at)->format('Y-m-d H:i:s');
                        $investigationHistoryReport = !empty($ancHistoryVisit->investigation) ? json_decode($ancHistoryVisit->investigation,true) : '';
                        $usgHistoryReport = !empty($ancHistoryVisit->usg) ? json_decode($ancHistoryVisit->usg,true) : '';
                        if(!empty($investigationHistoryReport['investigation_early_scan_type']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'Early Scan','url' => $investigationHistoryReport['investigation_early_scan_type']['images']);
                        }
                        if(!empty($investigationHistoryReport['growth_report']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'Growth Report','url' => $investigationHistoryReport['growth_report']['images']);
                        }
                        if(!empty($investigationHistoryReport['other_report_data']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'Other Report','url' => $investigationHistoryReport['other_report_data']['images']);
                        }
                        if(!empty($investigationHistoryReport['anc']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'ANC Report','url' => $investigationHistoryReport['anc']['images']);
                        }
                        if(!empty($usgHistoryReport['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'ANC',"report_type" => 'USG Report','url' => $usgHistoryReport['images']);
                        }
                    }
                }
                $ivfAllVisit = $this->IVF->where('patients_id', $patients)->get();
                $ivfAllHistoryVisit = $this->IvfHistory->where('patients_id',$patients)->get();
                if($ivfAllVisit)
                {
                    foreach($ivfAllVisit as $ivfVisit)
                    {
                        $reportDate = Carbon::parse($ivfVisit->created_at)->format('Y-m-d H:i:s');
                        $investigationReport = !empty($ivfVisit->investigation) ? json_decode($ivfVisit->investigation,true) : '';
                        if(!empty($investigationReport['hystroscopy']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Hystroscopy','url' => $investigationReport['hystroscopy']['images']);
                        }
                        if(!empty($investigationReport['laproscopy']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Laproscopy','url' => $investigationReport['laproscopy']['images']);
                        }
                        if(!empty($investigationReport['hcg']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Hcg','url' => $investigationReport['hcg']['images']);
                        }
                        if(!empty($investigationReport['blood_report']['image']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Blood Report','url' => $investigationReport['blood_report']['image']);
                        }
                    }
                }
                if($ivfAllHistoryVisit)
                {
                    foreach($ivfAllHistoryVisit as $ivfHistoryVisit)
                    {
                        $reportDate = Carbon::parse($ivfHistoryVisit->created_at)->format('Y-m-d H:i:s');
                        $investigationHistoryReport = !empty($ivfHistoryVisit->investigation) ? json_decode($ivfHistoryVisit->investigation,true) : '';
                        $investigationHistoryData = !empty($ivfHistoryVisit->description) ? json_decode($ivfHistoryVisit->description,true) : '';
                        if(!empty($investigationHistoryReport['hystroscopy']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Hystroscopy','url' => $investigationHistoryReport['hystroscopy']['images']);
                        }
                        if(!empty($investigationHistoryReport['laproscopy']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Laproscopy','url' => $investigationHistoryReport['laproscopy']['images']);
                        }
                        if(!empty($investigationHistoryData['usg']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'USG Report','url' => $investigationHistoryData['usg']['images']);
                        }
                        if(!empty($investigationHistoryData['blood_report']['image']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IVF',"report_type" => 'Blood Report','url' => $investigationHistoryData['blood_report']['image']);
                        }
                    
                    }
                }
                $iuiAllVisit = $this->IUI->where('patients_id', $patients)->get();
                $iuiAllHistoryVisit = $this->IuiHistory->where('patients_id',$patients)->get();
                if($iuiAllVisit)
                {
                    foreach($iuiAllVisit as $iuiVisit)
                    {
                        $reportDate = Carbon::parse($iuiVisit->created_at)->format('Y-m-d H:i:s');
                        $investigationReport = !empty($iuiVisit->investigation) ? json_decode($iuiVisit->investigation,true) : '';
                        if(!empty($investigationReport['hystroscopy']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IUI',"report_type" => 'Hystroscopy','url' => $investigationReport['hystroscopy']['images']);
                        }
                        if(!empty($investigationReport['laproscopy']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IUI',"report_type" => 'Laproscopy','url' => $investigationReport['laproscopy']['images']);
                        }
                        if(!empty($investigationReport['hcg']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IUI',"report_type" => 'HCG Report','url' => $investigationReport['hcg']['images']);
                        }
                        if(!empty($investigationReport['blood_report']['image']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IUI',"report_type" => 'Blood Report','url' => $investigationReport['blood_report']['image']);
                        }
                    }
                }
                if($iuiAllHistoryVisit)
                {
                    foreach($iuiAllHistoryVisit as $iuiHistoryVisit)
                    {
                        $reportDate = Carbon::parse($iuiHistoryVisit->created_at)->format('Y-m-d H:i:s');
                        $investigationHistoryData = !empty($iuiHistoryVisit->description) ? json_decode($iuiHistoryVisit->description,true) : '';
                        if(!empty($investigationHistoryData['blood_report']['image']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IUI',"report_type" => 'Blood Report','url' => $investigationHistoryData['blood_report']['image']);
                        }
                        if(!empty($investigationHistoryData['usg']['images']))
                        {
                            $data[] = array('date' => $reportDate,"category"=> 'IUI',"report_type" => 'USG Report','url' => $investigationHistoryData['usg']['images']);
                        }
                    }
                }
                usort($data, array( $this, 'cmp' ));//dort array in desc date wise
                return $this->sendResponse('get User Report Successfully',$data);
            } 
            else 
            {
                return $this->sendError('User is not found');
            }
        }else{
            return $this->sendError(__('auth.failed'), 401);
        }

    }
    public function addPatientMemory(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        $rule = [
            'title' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }
        if ($get_token) 
        {
            $user = OpdPatients::where('id', $get_token->patients_id)->first();
            if ($user && !empty($user->code)) 
            {   
                $patient_memory = $this->PatientMemory;
                $patient_memory->patients_id = $get_token->patients_id;
                $patient_memory->title = $request->title;
                $patient_memory->description = $request->description;
                if($request->hasFile('image'))
                {
                    $image = $request->file('image');
                    $profilePicture = $this->uploadImage($image, 'public/upload/patient/memory/');
                    $patient_memory->image = url('public/upload/patient/memory/'.$profilePicture);
                }
                $patient_memory->save();
                return $this->sendResponse('Add Memory Successfully',$patient_memory);
            } 
            else 
            {
                return $this->sendError('User is not found');
            }
        }else{
            return $this->sendError(__('auth.failed'), 401);
        }
    }
    public function editPatientMemory(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        $rule = [
            'id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }
        if ($get_token) 
        {
            $user = OpdPatients::where('id', $get_token->patients_id)->first();
            if ($user && !empty($user->code)) 
            {   
                $patient_memory = $this->PatientMemory->find($request->id);
                $patient_memory->title = $request->title;
                $patient_memory->description = $request->description;
                // $patient_memory->image = null;
                if($request->hasFile('image'))
                {
                    $image = $request->file('image');
                    $profilePicture = $this->uploadImage($image, 'public/upload/patient/memory/');
                    $patient_memory->image = url('public/upload/patient/memory/'.$profilePicture);
                }
                $patient_memory->save();
                return $this->sendResponse('Update Memory Successfully',$patient_memory);
            } 
            else 
            {
                return $this->sendError('User is not found');
            }
        }else{
            return $this->sendError(__('auth.failed'), 401);
        }
    }
    public function deletePatientMemory(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        $rule = [
            'id' => 'required',
        ];
        $validator = Validator::make($request->all(),$rule);
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), 422);
        }
        if ($get_token) 
        {
            $user = OpdPatients::where('id', $get_token->patients_id)->first();
            if ($user && !empty($user->code)) 
            {   
                $patient_memory = $this->PatientMemory->find($request->id);
                if($patient_memory)
                {
                    $patient_memory->delete();
                }
                return $this->sendResponse('Delete Memory Successfully');
            } 
            else 
            {
                return $this->sendError('User is not found');
            }
        }else{
            return $this->sendError(__('auth.failed'), 401);
        }
    }
    public function getPatientMemory(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if ($get_token) 
        {
            $user = OpdPatients::where('id', $get_token->patients_id)->first();
            if ($user && !empty($user->code)) 
            {   
                $patient_memory = $this->PatientMemory->where('patients_id',$user->id)->get();
                // if($patient_memory)
                // {
                //     $patient_memory->delete();
                // }
                return $this->sendResponse('Delete Memory Successfully',$patient_memory);
            } 
            else 
            {
                return $this->sendError('User is not found');
            }
        }else{
            return $this->sendError(__('auth.failed'), 401);
        }
    }
    public function cmp($a, $b) {
        if (strtotime($a['date']) == strtotime($b['date'])) return 0;
        return (strtotime($a['date']) > strtotime($b['date'])) ?-1:1;
    }
    
}
