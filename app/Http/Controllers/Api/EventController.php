<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Base\Api\ApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends ApiController
{
    /**
    * Get All event
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getEvents(Request $request) {
        $token = $request->header('Authorization');
        if($token) {
            $today = Carbon::now()->format('Y-m-d');
            $eventData = collect($this->Event::select('id','title','discription','event_picture','venue','time','start_date','end_Date')->where('end_date','>=',$today)->where('status',1)->get())->map(function($q){
                $q->event_picture = $q->event_picture ? cdnUrl($q->event_picture, null) : null;
                return $q;
            });

            
            if(!empty($eventData)) {
                return $this->sendResponse('Get Events succssfully',$eventData);
            }
            return $this->sendError('Events is not found', 401);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
    * Get prticular event detail using event_id
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function eventDetails(Request $request) {
        $token = $request->header('Authorization');
        if($token) {
            $rule = [
                'event_id' => 'required',
            ];

            $eventId = $request->event_id;
            $today = Carbon::now()->format('Y-m-d');
            $eventData = $this->Event::select('title', 'discription', 'event_picture','venue','time', 'start_date', 'end_Date')->where('id', $eventId)->where('status',1)->first();
            if (!empty($eventData)) {
                $eventData->event_picture = $eventData->event_picture ? cdnUrl($eventData->event_picture, null) : null;
                return $this->sendResponse('Get Event Details succssfully', $eventData);
            }
            return $this->sendError('Events is not found', 401);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
    * Get All event wirh past-recent-upcoming
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getAllEvents(Request $request) {
        $token = $request->header('Authorization');
        if($token) {
            $today = Carbon::now()->format('Y-m-d');
            //recent
            $eventData = collect($this->Event::select('id','title','discription','event_picture','venue','time','start_date','end_Date')->where('start_date','=',$today)->where('status',1)->get())->map(function($q){
                $q->event_picture = $q->event_picture ? cdnUrl($q->event_picture, null) : null;
                return $q;
            });
            if($request->event_type == 0)//past event
            {
                $eventData = collect($this->Event::select('id','title','discription','event_picture','venue','time','start_date','end_Date')->where('end_date','<',$today)->where('status',1)->get())->map(function($q){
                    $q->event_picture = $q->event_picture ? cdnUrl($q->event_picture, null) : null;
                    return $q;
                });
            }
            if($request->event_type == 2)//upcoming event
            {
                $eventData = collect($this->Event::select('id','title','discription','event_picture','venue','time','start_date','end_Date')->where('start_date','>',$today)->where('status',1)->get())->map(function($q){
                    $q->event_picture = $q->event_picture ? cdnUrl($q->event_picture, null) : null;
                    return $q;
                });
            }
            if(!empty($eventData)) {
                return $this->sendResponse('Get Events succssfully',$eventData);
            }
            return $this->sendError('Events is not found', 401);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}
