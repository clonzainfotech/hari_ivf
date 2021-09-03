<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Exception;
use Validator;
use View;
use Auth;
use Log;

class CategoryNotificationController extends AdminController
{
    /**
     * Display a listing of the notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $now = Carbon::now()->format('Y-m-d');
            $next= Carbon::now()->addDays(1)->format('Y-m-d');
            $user_id = Auth::user()->id;
            $notificationType = $request->notificationType;
            if($request->notificationType && $request->notificationType == 'payment')
            {
                if($request->type == "today")
                {
                    $data = $this->IvfPaymentReminder->whereDate('date','=',$now);
                }
                elseif($request->type == "next")
                {
                    $data = $this->IvfPaymentReminder->whereDate('date','=',$next);
                }
                else{
                    $data = $this->IvfPaymentReminder->whereDate('date','>',$now);
                }
            }
            else
            {
                $categoryNotification = $this->CategoryNotification->whereDate('date','=',$now)->get();
                foreach($categoryNotification as $categoryNotification)
                {
                    $notification = $this->CategoryNotification->find($categoryNotification->id);
                    
                    $json_string = !empty($categoryNotification->read_by) ? $categoryNotification->read_by.','.$user_id : $user_id ;
                    $array_uniq = array_unique(explode(',',$json_string));
                    $notification->read_by = implode(',',$array_uniq);
                    $notification->save();
                }
                if($request->type == "today")
                {
                    $data = $this->CategoryNotification->whereDate('date','=',$now);
                }
                elseif($request->type == "next")
                {
                    $data = $this->CategoryNotification->whereDate('date','=',$next);
                }
                else{
                    $data = $this->CategoryNotification->whereDate('date','>',$now);
                }
            }
            
            $data = $data->orderBy('date','asc')->paginate(10);
            
            return response()->json(['notification'=>View::make('admin.category_notification.data', compact('data','notificationType'))->render(),
                                        'type' => $notificationType
                                    ]);
        }
        return view('admin.category_notification.index');
    }
    /**
     * update read by for today notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function notificationAllRead(Request $request)
    {
        $user_id = Auth::user()->id;
        $now = Carbon::now()->format('Y-m-d');
        $categoryNotification = $this->CategoryNotification->whereDate('date','=',$now)->get();
        foreach($categoryNotification as $categoryNotification)
        {
            $notification = $this->CategoryNotification->find($categoryNotification->id);
            
            $json_string = !empty($notification->read_by) ? $notification->read_by.','.$user_id : $user_id ;
            $array_uniq = array_unique(explode(',',$json_string));
            $notification->read_by = implode(',',$array_uniq);
            $notification->save();
        }
        return redirect()->back();
    }
}
