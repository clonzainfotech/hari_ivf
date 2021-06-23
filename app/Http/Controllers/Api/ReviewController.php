<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
class ReviewController extends ApiController
{

    /**
    * Return user list from admin site(doctor,staff)
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getUsers(Request $request) {
        $token = $request->header('Authorization');
        if($token){
            $user = $this->User->where('role','!=','1')->select('name','id')->get();
            return $this->sendResponse('Get user Successfully',$user);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    // public function addReview(Request $request) {
    //     $token = $request->header('Authorization');
    //     if($token){
    //         $patientId = $this->OpdPatients->where('token',$token)->pluck('id')->first();
    //         $rule = [
    //             'roleid' => 'required',
    //             'rate' => 'required',
    //         ];
    //         $validator = Validator::make($request->all(),$rule);
    //         if($validator->fails()){
    //             return $this->sendError($validator->errors()->first(), 422);
    //         }
    //         $userReviewData = $this->UserReview::updateOrCreate(
    //             ["patient_id"=>"$patientId","role_id"=>"$request->roleid"],
    //             ["patient_id"=>"$patientId","role_id"=>"$request->roleid","rate"=>"$request->rate","remark"=>"$request->remark"]
    //         );
    //         return $this->sendResponse('Successfully add review',$userReviewData);
    //     }
    //     return $this->sendError(__('auth.failed'), 401);
    // }

    /**
    * Add review
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function addReview(Request $request) {
        $token = $request->header('Authorization');
        if($token){
            // $patient = $this->OpdPatients->where('token',$token)->first();
            $patient = $this->PatientToken->where('token',$token)->first();
            if($patient) {
                $rule = [
                    // 'userid' => 'required',
                    'rate' => 'required',
                ];
                $validator = Validator::make($request->all(),$rule);
                if($validator->fails()){
                    return $this->sendError($validator->errors()->first(), 422);
                }
                $patientId = $patient->patients_id;
                $userReviewData = $this->UserReview::updateOrCreate(
                    ["patient_id"=>"$patientId"],
                    ["patient_id"=>"$patientId","rate"=>"$request->rate","remark"=>"$request->remark"]
                );
                return $this->sendResponse('Successfully add review',$userReviewData);
            }
            return $this->sendError(__('Invalid token'), 401);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Return patient review list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getPatientsReview(Request $request){
        // $token = $request->header('Authorization');
        // $patientId = $this->OpdPatients->where('token',$token)->pluck('id')->first();
        // if($token && $patientId){

            $userReview = collect($this->UserReview->select('id','patient_id','rate','remark','created_at')->orderBy('rate','DESC')->get())
            ->map(function($q){
                $q->patient_name = ucfirst($q->getPatientsData['name']);
                // $q->name = $q->getReviewUser['name'];
                // $q->profile_picture = $q->getReviewUser['profile_picture'] ? url($q->getReviewUser['profile_picture']) : null;
                unset($q->getReviewUser,$q->getPatientsData);
                return $q;
            });

            $msg = 'Get review successfully';
            if(empty($userReview)){
                $msg = 'Review not found';
            }
            return $this->sendResponse($msg,$userReview);
        // }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Return review detail
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getReview(Request $request) {
        $token = $request->header('Authorization');
        // $patient = $this->OpdPatients->where('token',$token)->first();
        $patient = $this->PatientToken->where('token',$token)->first();
        if($patient) {
            $rule = [
                'reviewid' => 'required',
            ];

            $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }

            $review = collect($this->UserReview->select('id','patient_id','user_id','rate','remark')->where('id', $request->reviewid)->get())
            ->map(function($q){
                $q->name = $q->getReviewUser['name'];
                $q->degree = $q->getReviewUser['degree'];
                $q->profile_picture = $q->getReviewUser['profile_picture'] ? url($q->getReviewUser['profile_picture']) : null;
                unset($q->getReviewUser);
                return $q;
            });

            // $review = $this->UserReview->select('id','user_id','rate','remark')->where('id', $request->reviewid)->get();

            return $this->sendResponse("Get Review detail",$review);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Delete review
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    
    public function deleteReview(Request $request) {
        $token = $request->header('Authorization');
        $patient = $this->PatientToken->where('token',$token)->first();

        if($patient) {
            $rule = [
                'reviewid' => 'required',
            ];

            $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }

            $review = $this->UserReview->where('id', $request->reviewid)->delete();
            return $this->sendResponse("Delete Review Successfully");
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}