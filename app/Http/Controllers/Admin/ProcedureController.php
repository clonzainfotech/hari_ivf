<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Exception;
use App\Models\Note;
use Session;
use View;
use Log;
use Auth;
use DB;
use Carbon\Carbon;

class ProcedureController extends AdminController
{

    /**
    * Get procedure list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        if ($request->ajax()) {
            $old_procedures = $this->ProcedureList->whereDate('date','<',carbon::now()->format('Y-m-d'))->delete();
            $procedure = $this->ProcedureList->orderBy('date','asc');
            $procedure = collect($procedure->get())->map(function ($query) {
                        $query->day = carbon::parse($query->date)->format('l');
                        $query->date = carbon::parse($query->date)->format('d-m-Y');
                        return $query;
                    });
            $procedure = $procedure->groupBy('day');
            return [
                'data' => View::make('admin.procedure_list.data', compact('procedure'))->render()
            ];
        }
        return view('admin.procedure_list.index');
    }
    
   /**
    * Update note
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function updateRemark(Request $request)
    {
        try{
            $procedure_id = decrypt($request->procedure_id);
            $procedureData = $this->ProcedureList->find($procedure_id);
            $procedureData->remark = $request->remark;
            $procedureData->save();
            return ['status'=>true];
        }catch(Exception $e){
            return [
                'status' => false,
                'message' => 'Internal server error'
            ];
        }
    }
}
