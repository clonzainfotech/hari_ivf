<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Admin\AdminController;
use DB;
use Log;
use Auth;
use View;
class HospitalChargeController extends AdminController
{
    /**
    * Return on hospital-charges with all required data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        try{
            if($request->ajax()) {
                $charge = $this->HospitalCharge->orderBy('id','DESC');
            

                // search text
                $search = $request->search;
                if($search){
                    $charge = $charge->where(function($query) use($search){
                        $query->where('title', 'LIKE', '%'.$search.'%');
                    });
                }

                if($request->isprint){
                    $charge = $charge->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['charge'] = View::make('admin.hospital_charge.preview',compact('charge'))->render();
                    return $data;   
                }
                $charge = $charge->paginate(100);
                $data['status'] = 1;
                $data['charge'] = View::make('admin.hospital_charge.data',compact('charge'))->render();
                return $data;   
            }
            return view('admin.hospital_charge.index');
        }catch(Exception $e){
            abort(500);
        }
    }
    /**
    * store hospital Charges
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        try{
            if(empty($request->charge_id))
            {
                $hospital_charge = $this->HospitalCharge;
                $hospital_charge->title = $request->title;
                $hospital_charge->charge = $request->charge;
                $hospital_charge->save();
                $data['status'] = 1;
            }
            else{
                $hospital_charge = $this->HospitalCharge->find($request->charge_id);
                $hospital_charge->title = $request->title;
                $hospital_charge->charge = $request->charge;
                $hospital_charge->save();
                $data['status'] = 0;
            }
            return $data; 
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }

    }

    /**
    * delete hospital Charges
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function chargeDelete($id){
        try{
            $hospital_charge = $this->HospitalCharge->find($id);
            $hospital_charge->delete();
            return 'true';
        }catch(Exception $e){
            return 'false';
        }
    }

    /**
    * get data of hospital Charges
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function getHospitalCharge($id)
    {
        try{
            $id = decrypt($id);
            // dd($id);
            $hospital_charge = $this->HospitalCharge->find($id);
            $data['status'] = 1;
            $data['hospital_charge'] = $hospital_charge;
            return $data;
        }catch(Exception $e){
            return 'false';
        }
    }
}
