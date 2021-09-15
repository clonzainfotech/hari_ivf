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
use File;

class SystemSettingController extends AdminController
{

    /**
    * Return on system_setting index page
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        try{
            $data = $this->SystemSetting->orderBy('id', 'DESC')->first();
            $hospitalAddress = $this->HospitalAddress->get();
            return view('admin.system_setting.index',compact('data', 'hospitalAddress'));
        }catch(Exception $e){
            abort(500);
        }
    }
    /**
    * Return on print_preview page
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function printpreview(Request $request)
    {
        try{
            $data = $this->SystemSetting->orderBy('id', 'DESC')->first();
            $hospitalAddress = $this->HospitalAddress->get();
            return view('layouts.printpreview',compact('data', 'hospitalAddress'));
        }catch(Exception $e){
            abort(500);
        }
    }
    /**
    * Return on system_setting index page
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        $systemSetting = $this->SystemSetting;
        if ($request->hasFile('html_favicon')) {
            $image = $request->file('html_favicon');
            $name = 'favicon_image.' . $image->getClientOriginalExtension();
            $destinationPath = 'assets/';
            $image->move($destinationPath, $name);
            $systemSetting->html_favicon = $name;
        }
        if ($request->hasFile('header_logo')) {
          $logoImage = $request->file('header_logo');
          $logoName = 'logo.' . $logoImage->getClientOriginalExtension();
          $path = 'public/images';
          $logoImage->move($path, $logoName);
          $systemSetting->header_logo = $logoName;

        }

        $systemSetting = $this->SystemSetting->insert([
            'html_favicon' => $systemSetting->html_favicon,
            'html_title' => $request->html_title,
            'header_logo' => $systemSetting->header_logo,
            'header_logo_width' => $request->header_logo_width,
            'header_logo_height' => $request->header_logo_height,
            'tag_line' => $request->tag_line,
            'header_logo_alt' => $request->header_logo_alt,
            'footer_copyright' => $request->footer_copyright,
            'email' => $request->email,
            'facebook_link' => $request->facebook_link,
            'instagram_link' => $request->instagram_link,
            'you_tube_link' => $request->you_tube_link,
            'twitter_link' => $request->twitter_link,
            'linked_in_link' => $request->linked_in_link,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        return redirect('systemsetting')->with('msg','Your setting successfully added.');
    }

    /**
    * Update system setting
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request){
        try {
            $id = decrypt($request->setting_id);
        } catch (Exception $exception) {
            return redirect('systemsetting')->with('msg','Something went wrong.');
        }

        try {
            $systemSetting = $this->SystemSetting->find($id);
            if ($request->hasFile('html_favicon')) {
                if (!empty($systemSetting->html_favicon)) {
                    $this->removeImage('assets/' . $systemSetting->html_favicon);
                }
                $image = $request->file('html_favicon');
                $name = 'favicon_image.' . $image->getClientOriginalExtension();
                $destinationPath = 'assets/';
                $image->move($destinationPath, $name);
                $systemSetting->html_favicon = $name;
            }
            $systemSetting->html_title = $request->html_title;
            if ($request->hasFile('header_logo')) {
                if (!empty($systemSetting->header_logo)) {
                    $this->removeImage('public/images/' . $systemSetting->header_logo);
                }
                $logoImage = $request->file('header_logo');
                $logoName = 'logo.' . $logoImage->getClientOriginalExtension();
                $path = 'public/images';
                $logoImage->move($path, $logoName);
                $systemSetting->header_logo = $logoName;
            }
            $systemSetting->header_logo_width = $request->header_logo_width;
            $systemSetting->header_logo_height = $request->header_logo_height;
            $systemSetting->tag_line = $request->header_welcome_text;
            $systemSetting->header_logo_alt = $request->header_logo_alt;
            $systemSetting->footer_copyright = $request->footer_copyright;
            $systemSetting->email = $request->other_email;
            $systemSetting->facebook_link = $request->facebook_link;
            $systemSetting->instagram_link = $request->instagram_link;
            $systemSetting->twitter_link = $request->twitter_link;
            $systemSetting->you_tube_link = $request->you_tube_link;
            $systemSetting->linked_in_link = $request->linked_in_link;
            $systemSetting->save();

            return redirect('systemsetting')->with('msg','Your setting has been updated.');
        }
        catch (Exception $exception) {
            return redirect('systemsetting')->with('msg','Something went wrong.');
        }
    }

    /**
    * Save General Configuration
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function generalConfiguration(Request $request){
        try{
            $rule = [
                'clear_log_days' => 'nullable|integer|between:1,180',
                'adv_video' => 'nullable|mimetypes:video/avi,video/mpeg,video/mp4,video/mkv,video/3gpp',
            ];

            $validator = Validator::make($request->all(),$rule);
            $systemSetting = $this->SystemSetting->first();
            if(!$systemSetting){
                $systemSetting = $this->SystemSetting;
            }
            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }
            if (!empty($systemSetting->adv_video)) {
                $this->removeImage($systemSetting->adv_video);
            }
            if($request->hasFile('adv_video')) {
                $video = $request->file('adv_video');
                $videoName = time() .'.'. $video->getClientOriginalExtension();
                $path = 'public/video/';
                $video->move($path, $videoName);
                $systemSetting->adv_video = $path.$videoName;
            }
            $systemSetting->clear_logs_day = $request->clear_log_days;
            $systemSetting->doctor = $request->doctor_name;
            $systemSetting->hospital_name = $request->hospital_name;
            $systemSetting->save();
            return redirect('systemsetting')->with('msg','Your setting has been updated.');
        }catch (Exception $e) {
            return redirect('systemsetting')->with('msg','Something went wrong.');
        }
    }

    /**
    * Update button related system_setting
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function button(Request $request){
        try{

            $systemSetting = $this->SystemSetting->first();
            if(!$systemSetting){
                $systemSetting = $this->SystemSetting;
            }
            $systemSetting->primary = $request->primary;
            $systemSetting->secondary = $request->secondary;
            $systemSetting->link = $request->link;
            $systemSetting->save();
            return redirect('systemsetting')->with('msg','Your setting has been updated.');
        }catch (Exception $e) {
            return redirect('systemsetting')->with('msg','Something went wrong.');
        }
    }
    /**
    * Update before visit-after visit color
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function appointmentdata(Request $request){
        try{

            $systemSetting = $this->SystemSetting->first();
            if(!$systemSetting){
                $systemSetting = $this->SystemSetting;
            }
            $systemSetting->before_visits = $request->before_visits;
            $systemSetting->after_visits = $request->after_visits;
            $systemSetting->unpaid_opd = $request->unpaid_opd;
            $systemSetting->save();
            return redirect('systemsetting')->with('msg','Your setting has been updated.');
        }catch (Exception $e) {
            return redirect('systemsetting')->with('msg','Something went wrong.');
        }
    }

    /**
    * Update Preview page  with footer and watermark
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function patientdetail(Request $request){
        try{

            $systemSetting = $this->SystemSetting->first();
            if(!$systemSetting){
                $systemSetting = $this->SystemSetting;
            }
            $systemSetting->footer_1 = $request->footer_1;
            $systemSetting->footer_2 = $request->footer_2;
            $systemSetting->docter_1 = $request->docter_1;
            $systemSetting->docter_2 = $request->docter_2;
            // $systemSetting->water_mark = $request->water_mark;
            if ($request->hasFile('water_mark')) {
                if (!empty($systemSetting->water_mark)) {
                    $this->removeImage('public/images/' . $systemSetting->water_mark);
                }
                $waterImage = $request->file('water_mark');
                $markName = 'watermark.' . $waterImage->getClientOriginalExtension();
                $path = 'public/images';
                $waterImage->move($path, $markName);
                $systemSetting->water_mark = $markName;
            }
            $systemSetting->save();
            return redirect('systemsetting')->with('msg','Your setting has been updated.');
        }catch (Exception $e) {
            return redirect('systemsetting')->with('msg','Something went wrong.');
        }
    }

    public function medicinesSetting(Request $request){
        try{
            $medicineId = $this->ComplaintMedicine->pluck('medicine_id','medicine_id')->toArray();
            // $medicine = $this->Medicine->whereNotIn('id',$medicineId)->orderBy('id','DESC');
            $medicine = $this->Medicine->orderBy('id','DESC');
            $co = $this->Complaint->pluck('name','id');
            $isOld = 0;
            $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
                $mTime = ["1"=>"IV","2"=>"IM","3"=>"SC","4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
                
            if($request->is_old){
                $isOld = 1;
            }
            if($request->ajax()){
                $status = 1;
                $search = $request->search;
            // $medicine = $this->Medicine->orderBy('id','DESC');

                $complaintMedicines =  collect($this->ComplaintMedicine->where('type',1)->get())->groupBy('type_id');
                if($search){
                    $medicine = $medicine->where(function($query) use($search){
                        $query->where('name', 'LIKE', '%'.$search.'%');
                    });
                }
                $medicine = $medicine->paginate(100);
                $data['complaintMedicines'] = $complaintMedicines;
                $data['status'] = $status;
                $data['medicine'] = $medicine;
                $data['mStatus'] = $mStatus;
                $data['dose'] = $dose;
                $data['mTime'] = $mTime;
                $data['co'] = $co;
                $data['isOld'] = $isOld;
                $data['medicines'] = View::make('admin.system_setting.medicine.data',$data)->render();
                return $data;
            }
            return view('admin.system_setting.medicine.index',compact('isOld','mStatus','dose','mTime'));
        }catch(Exceptoin $e){
            return redirect('medicines-setting')->with('msg','Something went wrong.');
        }
    }

     public function delete_selected_medicine(Request $request)
    {
        $ids = $request->ids;
        $co = $this->Medicine->whereIn('id',explode(",",$ids))->delete();
        return['status'=>1];
        // return response()->json(['success'=>"Products Deleted successfully."]);
    }

    public function medicinesCreate(){
        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
        $dose = [1=>"Daily",2=>"Once a week",3=>"Twice a week",4=>"Stat",5=>"SOS",6=>"Alternate Day",7=>"6 hourly",8=>"8 hourly",9=>"12 hourly",10=>"24 hourly"];
        $mTime = ["1"=>"IV","2"=>"IM","3"=>"SC","4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
        $co = $this->Complaint->pluck('name','id');
        $data['mStatus'] = $mStatus;
        $data['dose'] = $dose;
        $data['mTime'] = $mTime;
        $data['co'] = $co;
        return view('admin.system_setting.medicine.create',$data);
    }

    public function medicineStore(Request $request){
        try{
            $mId = $request->medicine_id;
            $medicine = $this->Medicine;
            if($mId){
                $mId = decrypt($mId);
                $medicine = $this->Medicine->find($mId);
            }
            $rule = [
                'name' => 'required|unique:medicines,name,'.$mId,
                'complaint' => 'required',
                'number' => 'nullable|numeric',
                'medicine_quantity' => 'nullable|numeric',
            ];

            $validator = Validator::make($request->all(),$rule);

            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }
            $medicine->name = strtoupper($request->name);
            $medicine->medicine_status = $request->medicine_status;
            $medicine->dose = $request->medicine_dose;
            $medicine->number = !empty($request->number) || $request->number != null  ? $request->number : 'N-A'; //Next Appointment
            $medicine->quantity = $request->medicine_quantity;
            $medicine->medicine_time = !empty($request->medicine_time) ? $request->medicine_time : null;
            $medicine->save();
            if(!empty($request->complaint)){
                $this->ComplaintMedicine->where('medicine_id',$medicine->id)->delete();
                foreach($request->complaint as $row){
                    $checkComplaint = $this->ComplaintMedicine->where('complaint_id',$row)->where('medicine_id',$medicine->id)->first();
                    if(!$checkComplaint){
                        $complaintMedicine = new $this->ComplaintMedicine;
                        $complaintMedicine->complaint_id = $row;
                        $complaintMedicine->medicine_id = $medicine->id;
                        $complaintMedicine->save();
                    }
                }
            }
            return redirect('medicines-setting')->with('s-msg','Your medicine has been added.');
        }catch(Exception $e){
            return redirect('medicines-setting')->with('msg','Something went wrong.');
        }
    }

    public function editeMedicine(Request $request,$id){
        try{
            $mId = decrypt($id);
            $medicine = $this->Medicine->find($mId);
            $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
            $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
            $mTime = ["1"=>"IV","2"=>"IM","3"=>"SC","4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
            $co = $this->Complaint->pluck('name','id');
            $mCo = $this->ComplaintMedicine->where('medicine_id',$medicine->id)->pluck('complaint_id','complaint_id')->toArray();
            $data['mStatus'] = $mStatus;
            $data['dose'] = $dose;
            $data['mTime'] = $mTime;
            $data['co'] = $co;
            $data['mCo'] = $mCo;
            $data['medicine'] = $medicine;
            return view('admin.system_setting.medicine.create',$data);
        }catch(Exception $e){
            return redirect('medicines-setting')->with('msg','Something went wrong.');
        }
    }

    public function storeMedicine(Request $request){
        $id = null;
        $medicine = $this->Medicine;
        if($request->m_id){
            $id = $request->m_id;
            $medicine = $this->Medicine->where('id',$id)->first();
        }
        $rule = [
            'name' => 'required|unique:medicines,name,'.$id,
        ];

        $message = [
            'name'=> 'The name field is required.'
        ];
        $valid = Validator::make($request->all(),$rule, $message);

        if($valid->fails()){
            return [
                'status' => 2,
                'error' => $valid->errors()
            ];
        }
        $medicine->name = strtoupper($request->name);
        $medicine->medicine_status = $request->status;
        $medicine->dose = $request->dose;
        $medicine->number = !empty($request->number) || $request->number != null  ? $request->number : 'N-A'; //Next Appointment
        // dd($medicine->number);
        $medicine->quantity = $request->quantity;
        $medicine->quantity_2 = $request->quantity_2;
        $medicine->quantity_3 = $request->quantity_3;
        $medicine->quantity_4= $request->quantity_4;
        $medicine->medicine_time = !empty($request->medicine_time) ? $request->medicine_time : null;
        $medicine->save();
        return['status'=>1];
    }

    public function getMedicine(Request $request){
        try{
            $mId = decrypt($request->m_id);
            $medicine = $this->Medicine->where('id',$mId)->first();
            $ee = encrypt(5);
            return ['status'=>1,'medicine'=>$medicine];
        }catch(Exception $e){
            // dd($e);
        }
    }

    public function medicinesMapping(Request $request,$type){
        $mType = decrypt($type);
        $data = [];
        $hocoType = 1;
        if($mType == 1){
            $hoco = $this->Complaint->pluck('name','id');
        }
        if($mType == 2){
            $hocoType = 2;
            $hoco = $this->HoCategory->pluck('name','id');
            // $hoco = $this->HoDetails->pluck('name','id');
        }
        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
        $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
        $mTime = ["1"=>"IV","2"=>"IM","3"=>"SC","4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
        $medicineData = $this->Medicine->pluck('name','id');
        if($request->ajax()){
            $complaintMedicines =  collect($this->ComplaintMedicine->where('type',$mType)->get())->groupBy('type_id');
            $data['complaintMedicines'] = $complaintMedicines;
            $data['hoco'] = $hoco;
            $data['mStatus'] = $mStatus;
            $data['dose'] = $dose;
            $data['mTime'] = $mTime;
            $data['status'] = 1;
            $data['hocoType'] = $hocoType;
            $data['map_data'] = View::make('admin.system_setting.medicine.mapping_data',$data)->render();
            return $data;
        }
        return view('admin.system_setting.medicine.mapping',compact('type','hoco','medicineData'));
    }

    public function getMedicinesData(Request $request){
        $type = $request->medicine_type;
        // dd($type);
        $id = $request->value;
        // $medicinesId = [];
        $medicinesId = $this->ComplaintMedicine->where('type',$type)->where('type_id',$id)->pluck('medicine_id','medicine_id')->toArray();
        $medicineArray = $this->Medicine->whereNotIn('id',$medicinesId)->pluck('name','id')->toArray();
        return ['medicines'=>$medicineArray];
    }

    public function storeMedicineStore(Request $request){
        $type = decrypt($request->type);
        $coId = $request->coId;
        $medicinesId = $request->medicinesId;
        if(!empty($medicinesId)){
            foreach($medicinesId as $row){
                $hocoData = new $this->ComplaintMedicine;
                $hocoData->type = $type;
                $hocoData->type_id = $coId;
                $hocoData->medicine_id = $row;
                $hocoData->save();
            }
        }
        return ['status'=>1];
    }

    public function removeMedicine(Request $request){
        $type = $request->type;
        $mId = decrypt($request->medicine_id);
        if($type == 1){
            $this->ComplaintMedicine->where('medicine_id',$mId)->delete();
            $this->Medicine->where('id',$mId)->delete();
        }else{
            $this->ComplaintMedicine->where('id',$mId)->delete();
        }
        return ['status'=>1];
    }
}
