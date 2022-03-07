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

class InjectionController extends AdminController
{
    //
    // Injection all listing using this function
    public function index(Request $request){
        try{
            $planList = $this->Injection->groupBy('type')->pluck('type','type');
            if($request->ajax()) {
                $injection = $this->Injection->whereNotNull('name')->orderBy('id','DESC');
            
                // search text
                $search = $request->search;
                if($search){
                    $injection = $injection->where(function($query) use($search){
                        $query->where('name', 'LIKE', '%'.$search.'%');
                    });
                }
                if(!empty($request->plan))
                {
                    $plan = str_replace('_', ' ', $request->plan);
                    $plan = str_replace('/', '+', $plan);
                    $injection = $injection->where(function($query) use($plan){
                        $query->where('type','=',$plan);
                    });
                }
                if($request->isprint){
                    $injection = $this->Injection->orderBy('id','DESC');
                    $injection = $injection->select('*')->get();
                    $data['status'] = 2;
                    $data['injection'] = View::make('admin.injection.preview',compact('injection'))->render();
                    return $data;   
                }
                $injection = $injection->paginate(100);
                $data['status'] = 1;
                $data['injection'] = View::make('admin.injection.data',compact('injection'))->render();
                return $data;   
            }
            return view('admin.injection.index',compact('planList'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
   
    // Injection store or update on database using this function
    public function store(Request $request){
        try{

            $injection = !empty($request->injId) ? $this->Injection->where('type',$request->plan)->where('name',$request->inj_name)->where('id','!=',decrypt($request->injId))->first() : $this->Injection->where('type',$request->plan)->where('name',$request->inj_name)->first();
            if(!empty($injection))
            {
                $data['status'] = 2;
                return $data;
            }
            if(!empty($request->injId))
            {
                $injId = decrypt($request->injId);
                $injection = $this->Injection->where('id','=',$injId)->first();
                $injection->name = $request->inj_name;
                $injection->type = $request->plan;
                $injection->category = $request->category;
                $injection->net_price = $request->net_price;
                $injection->quantity = $request->qty;
                $injection->save();
                $data['status'] = 1;
                return $data;

            }
            if(empty($injection) && empty($request->injId))
            {   
                $injection = $this->Injection->where('type',$request->plan)->first();
                $injection->name = $request->inj_name;
                $injection->net_price = $request->net_price;
                $injection->quantity = $request->qty;
                $injection->save();
                $data['status'] = 1;
            return $data;

            }
            
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }

    }
    // Injection delete using this function via injection id
    public function delete($id){
        try{
            $injection = $this->Injection->find(decrypt($id));
            $injection->delete();
            return 'true';
        }catch(Exception $e){
            return 'false';
        }
    }

    // fetch query on database using injection id
    public function edit($id){
        try{
            $injId = decrypt($id);
            $injection = $this->Injection->find($injId);
            if($injection)
            {
                $data['injection'] = $injection;
                $data['status'] = 1;
            }
            else{
                $data['status'] = 2;
            }
            return $data;
        }catch(Exception $e){
            log::debug($e);
            return back();
        }
    }

    //Bank module
    //get bank data using this function

    public function getBankData(Request $request)
    {
        try{
            if($request->ajax()) {
                $bank_detail = $this->BankDetail->orderBy('id','DESC');
            
                // search text
                $search = $request->search;
                if($search){
                    $bank_detail = $bank_detail->where(function($query) use($search){
                        $query->where('name', 'LIKE', '%'.$search.'%');
                    });
                }
                $bank_detail = $bank_detail->paginate(100);
                $data['status'] = 1;
                $data['bank_detail'] = View::make('admin.bank.data',compact('bank_detail'))->render();
                return $data;   
            }
            return view('admin.bank.index');
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    // bank detail store on database using this function
    public function storeBank(Request $request){
        try{
            if(empty($request->bank_id))
            {
                $bank_detail = $this->BankDetail;
                $bank_detail->name = $request->name;
                $bank_detail->save();
                $data['status'] = 1;
            }
            else{
                $bank_detail = $this->BankDetail->find($request->bank_id);
                $bank_detail->name = $request->name;
                $bank_detail->save();
                $data['status'] = 0;
            }
            return $data; 
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }

    }

    // bank detail delete using this function via bank id
    public function bankDelete($id){
        try{
            $bank_detail = $this->BankDetail->find($id);
            $bank_detail->delete();
            return 'true';
        }catch(Exception $e){
            return 'false';
        }
    }

    //
    public function getBank($id)
    {
        try{
            $id = decrypt($id);
            // dd($id);
            $bank_detail = $this->BankDetail->find($id);
            $data['status'] = 1;
            $data['bank_detail'] = $bank_detail;
            return $data;
        }catch(Exception $e){
            return 'false';
        }
    }

    //Plan

    /**
     * get all plan list
     */
    public function getPlanData(Request $request)
    {
        try{
            if($request->ajax()) {
                $planList = $this->Injection->groupBy('type');
                // search text
                $search = $request->search;
                if($search){
                    $planList = $planList->where(function($query) use($search){
                        $query->where('type', 'LIKE', '%'.$search.'%');
                    });
                }
                if($request->isprint){
                    $planList = $this->Injection->groupBy('type');
                    $planList = $planList->select('*')->get();
                    $data['status'] = 2;
                    $data['planList'] = View::make('admin.injection.plan_preview',compact('planList'))->render();
                    return $data;   
                }
                $planList = $planList->paginate(100);
                $data['status'] = 1;
                $data['planList'] = View::make('admin.injection.plan_data',compact('planList'))->render();
                return $data;   
            }
            return view('admin.injection.plan_index');
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
     
    /**
     * add or update new plan using plan name
     */

    public function planStore(Request $request)
    {
        try{

            $injection = !empty($request->injId) ? $this->Injection->where('type',$request->plan)->where('category',$request->category)->where('id','!=',decrypt($request->injId))->first() : $this->Injection->where('type',$request->plan)->where('category',$request->category)->first();
            // dd(decrypt($request->injId));
            
            if(!empty($injection))
            {
                $data['status'] = 2;
                return $data;
            }
            if(!empty($request->injId))
            {
                $injId = decrypt($request->injId);
                $injection = $this->Injection->where('id','=',$injId)->first();
                $injectionUpdate = $this->Injection->where('type','=',$injection->type)->update(['type' => $request->plan,'category' => $request->category]);
                // $injection->type = $request->plan;
                // $injection->category = $request->category;
                // $injection->save();
                $data['status'] = 1;
                return $data;
            }
            if(empty($injection) && empty($request->injId))
            {   
                $injection = $this->Injection;
                $injection->type = $request->plan;
                $injection->category = $request->category;
                $injection->save();
                $data['status'] = 1;
                return $data;
            }
            
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
     // fetch query on database using injection id
     public function planEdit($id){
        try{
            $injId = decrypt($id);
            $injection = $this->Injection->find($injId);
            if($injection)
            {
                $data['injection'] = $injection;
                $data['status'] = 1;
            }
            else{
                $data['status'] = 2;
            }
            return $data;
        }catch(Exception $e){
            log::debug($e);
            return back();
        }
    }
}
