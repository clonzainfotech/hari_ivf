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

class EventController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $today = Carbon::now()->format('Y-m-d');
            $event = $this->Event->paginate(100);

            return response()->json([View::make('admin.event.data', compact('event','today'))->render()]);
        }
        return view('admin.event.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add validation
         try{
            $rule = [
                'title' => 'required',
                'discription' => 'required',
                'venue' => 'required',
                'time' => 'required',
                // 'startDate' => 'required|date|after_or_equal:'. date('Y-m-d'),
                // 'endDate' => 'required|date|after_or_equal:startDate',
                'event_picture' => 'nullable|mimes:jpg,png,jpeg',
            ];

            $validator = Validator::make($request->all(),$rule);

            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }
                // echo $request->title;


            // user save
            $event = $this->Event;
            $event->title = $request->title;
            $event->created_by = Auth::user()->id;
            $event->discription = $request->discription;
            $event->venue = $request->venue;
            $event->time = $request->time;
            $event->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
            $event->end_date = Carbon::parse($request->endDate)->format('Y-m-d');
            $event->status = 1;

            // profile picuture upload
            if ($request->hasFile('event_picture')) {
                $image = $request->file('event_picture');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = 'public/upload/event';
                $image->move($destinationPath, $name);
                $event->event_picture = $destinationPath.'/'.$name;
            }

            $event->save();

            return redirect('event')->with('msg','Your record successfully added.');
            }catch(Exception $e){
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
       if($request->ajax()){
            $eventId = $id;
        }
        else {
            $eventId = decrypt($id);
        }

            $event = $this->Event->where('id',$eventId)->first();

        if($request->ajax()){
            $data = [];
            $data['Event'] = $event;
            return $data;
        }
            return view('admin.event.edit',compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         try{

            $rule = [
                'title' => 'required',
                'discription' => 'required',
                'venue' => 'required',
                'time' => 'required',
                // 'startDate' => 'required|date|after_or_equal:'. date('Y-m-d'),
                // 'endDate' => 'required|date|after_or_equal:startDate',
                'event_picture' => 'nullable|mimes:jpg,png,jpeg',
                'status' => 'required',
            ];

            $validator = Validator::make($request->all(),$rule);

            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }

            $event = $this->Event->find($id);
            $event->title = $request->title;
            $event->created_by = Auth::user()->id;
            $event->discription = $request->discription;
            $event->venue = $request->venue;
            $event->time = $request->time;
            $event->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
            $event->end_date = Carbon::parse($request->endDate)->format('Y-m-d');
             $event->status =$request->status;

            // profile picuture upload
            if ($request->hasFile('event_picture')) {

            $this->removeImage($event->event_picture);

            $image = $request->file('event_picture');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = 'public/upload/event';
            $image->move($destinationPath, $name);
            $event->event_picture = $destinationPath.'/'.$name;
            }

            // change password

            $event->save();
            return redirect('event')->with('msg','Your record successfully updated.');
            }catch(Exception $e){
            abort(500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $event = $this->Event->find($id);
            $this->removeImage($event->event_picture);
            $this->Event
                ->where('id',$event->id)
                ->update([
                    'status' => 2
                ]);
            $event->delete();


            return 'true';
        }catch(Exception $e){

        }
    }
}
