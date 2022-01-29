<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Base\Api\ApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Log;
use Exception;

class FaqController extends ApiController
{
    /**
    * Get All Questions
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request,$id = null) 
    {
        try
        {
            if(!empty($id))
            {
                $question = $this->FaqQuestion->with('getAnswer')->where('id',$id)->orderBy('created_at','desc')->get();
            }
            else
            {
                $question = $this->FaqQuestion->with('getAnswer')->orderBy('created_at','desc')->get();
                
            }
            return $this->sendResponse('Get Question Successfully',$question);
        }
        catch(Exception $e)
        {
            Log::Debug($e);
        }
    }
    /**
     * Add Question
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function addQuestion(Request $request)
    {
        try
        {
            $token = $request->header('Authorization');
            $get_token = $this->PatientToken->where('token', $token)->first();
            // $user->age = $user->age ? (string)$user->age : null;
            if ($get_token) 
            {
                $rule = [
                    'question' => 'required|unique:faq_questions',
                ];
            
                $validator = Validator::make($request->all(),$rule);
                if($validator->fails()){
                    return $this->sendError($validator->errors()->first(), 422);
                }
                $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
                $question = $this->FaqQuestion;
                $question->question_by = $user->id;
                $question->question = $request->question;
                $question->save();
                return $this->sendResponse('Question add Successfully',$question);
            } else {
                return $this->sendError('User is not found');
            }
            return $this->sendError(__('auth.failed'), 401);
        }
        catch(Exception $e)
        {
            log::Debug($e);
        }
    }
    /**
     * Add Answer
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function addAnswer(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        // $user->age = $user->age ? (string)$user->age : null;
        if ($get_token) 
        {
            $rule = [
                'question_id' => 'required',
                'answer' => 'required',
            ];
        
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails()){
                return $this->sendError($validator->errors()->first(), 422);
            }
            $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
            $question = $this->FaqQuestion->find($request->question_id);
            if($question)
            {
                $answer = $this->FaqAnswer;
                $answer->answer_by = $user->id;
                $answer->que_id = $request->question_id;
                $answer->answer = $request->answer;
                $answer->save();
                return $this->sendResponse('Answer add Successfully',$answer);
            }
            else
            {
                return $this->sendError('Quetion is not available');
            }
            
        } else {
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
     * Update Question
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function updateQuestion(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        // $user->age = $user->age ? (string)$user->age : null;
        if ($get_token) 
        {
            $rule = [
                'question_id' => 'required|exists:faq_questions,id',
                'question' => 'required',
            ];
        
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(), 422);
            }
            $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
            $question = $this->FaqQuestion->where('id',$request->question_id)->where('question_by',$user->id)->first();
            if($question)
            {
                $question->question_by = $user->id;
                $question->question = $request->question;
                $question->save();
                return $this->sendResponse('Question update Successfully',$question);
            }
            else{
                return $this->sendError('You are not Authorized to update this question');
            }
            
        } else {
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
     * Update Answer
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function updateAnswer(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        // $user->age = $user->age ? (string)$user->age : null;
        if ($get_token) 
        {
            $rule = [
                'answer_id' => 'required|exists:faq_answers,id',
                'answer' => 'required',
            ];
        
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(), 422);
            }
            $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
            $answer = $this->FaqAnswer->where('id',$request->answer_id)->where('answer_by',$user->id)->first();
            if($answer)
            {
                $answer->answer = $request->answer;
                $answer->save();
                return $this->sendResponse('Question update Successfully',$answer);
            }
            else
            {
                return $this->sendError('You are not Authorized to update this Answer');
            }
            
        } else {
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
     * Delete Answer
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function DeleteAnswer(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if ($get_token) 
        {
            $rule = [
                'answer_id' => 'required|exists:faq_answers,id',
            ];
        
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(), 422);
            }
            $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
            $answer = $this->FaqAnswer->where('id',$request->answer_id)->where('answer_by',$user->id)->first();
            if($answer)
            {
                $answer->delete();
                return $this->sendResponse('Answer delete Successfully');
            }
            else
            {
                return $this->sendError('You are not Authorized to delete this Answer');
            }
            
        } else {
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
     * Delete Question
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */
    public function DeleteQuestion(Request $request)
    {
        $token = $request->header('Authorization');
        $get_token = $this->PatientToken->where('token', $token)->first();
        if ($get_token) 
        {
            $rule = [
                'question_id' => 'required|exists:faq_questions,id',
            ];
        
            $validator = Validator::make($request->all(),$rule);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(), 422);
            }
            $user = $this->OpdPatients->where('id', $get_token->patients_id)->first();
            $question = $this->FaqQuestion->where('id',$request->question_id)->where('question_by',$user->id)->first();
            if($question)
            {
                $question->delete();
                $answer = $this->FaqAnswer->where('que_id',$request->question_id)->delete();
                return $this->sendResponse('Question delete Successfully');
            }
            else
            {
                return $this->sendError('You are not Authorized to delete this Question');
            }
            
        } else {
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
