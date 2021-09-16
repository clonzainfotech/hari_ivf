<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Exception;
use Session;
use View;
use Auth;
use DB;
use Log;

class InjectionChargeController extends AdminController
{
    /**
    * Return on Inj charge list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        try{
            if($request->ajax()) {
                $injection = $this->InjectionCharge->orderBy('id','DESC');
            
                // search text
                $search = $request->search;
                if($search){
                    $injection = $injection->where(function($query) use($search){
                        $query->where('name', 'LIKE', '%'.$search.'%');
                    });
                }
                $injection = $injection->paginate(100);
                $data['status'] = 1;
                $data['injection'] = View::make('admin.inj_charge.data',compact('injection'))->render();
                return $data;   
            }
            return view('admin.inj_charge.index');
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    
    /**
    * Store and edit injection charge
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        try
        {
            $injection = !empty($request->injId) ? $this->InjectionCharge->where('name',$request->inj_name)->where('id','!=',decrypt($request->injId))->first() : $this->InjectionCharge->where('name',$request->inj_name)->first();
            if(!empty($injection))
            {
                $data['status'] = 2;
            }
            if(!empty($request->injId))
            {
                $injId = decrypt($request->injId);
                $injection = $this->InjectionCharge->where('id','=',$injId)->first();
                $injection->name = $request->inj_name;
                $injection->net_price = $request->net_amount;
                $injection->mrp = $request->mrp;
                $injection->quantity = $request->quantity;
                $injection->qty_type = $request->qty_type;
                $injection->type = 1;
                $injection->save();
                $data['status'] = 1;
            }
            if(empty($injection) && empty($request->injId))
            {   
                $injection = $this->InjectionCharge;
                $injection->name = $request->inj_name;
                $injection->net_price = $request->net_amount;
                $injection->mrp = $request->mrp;
                $injection->quantity = $request->quantity;
                $injection->qty_type = $request->qty_type;
                $injection->type = 1;
                $injection->save();
                $data['status'] = 1;
            }
            
            return $data;
        }
        catch(Exception $e)
        {
            log::debug($e);
            abort(500);
        }
    }
    /**
    * get data of Injection Charges
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function getInjCharge($id)
    {
        try{
            $id = decrypt($id);
            // dd($id);
            $inj_charge = $this->InjectionCharge->find($id);
            $data['status'] = 1;
            $data['injection'] = $inj_charge;
            return $data;
        }catch(Exception $e){
            log::Debug($e);
            return 'false';
        }
    }
    /**
    * delete Inj Charges
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function injChargeDelete($id){
        try{
            $id = decrypt($id);
            $inj_charge = $this->InjectionCharge->find($id);
            $inj_charge->delete();
            return 'true';
        }catch(Exception $e){
            log::Debug($e);
            return 'false';
        }
    }
    /**
    * Return Inj Qty type
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function InjectionChargeController(Request $request)
    {
        try{
            // dd($request->injId);
            $qty_type = ['1'=>'QTY','2'=>'ML'];
            $inj_charge = $this->InjectionCharge->where('name',$request->injId)->first();
            $data['type'] = isset($qty_type[$inj_charge->qty_type]) ? $qty_type[$inj_charge->qty_type] : '-';
            $data['qty'] = $inj_charge->quantity;
            return $data;
        }catch(Exception $e){
            log::Debug($e);
            return 'false';
        }
    }
}