<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Validator;
use View;
use Auth;
use Log;
use DB;


class IncomeManagerController extends AdminController
{

    /**
    * Return on income manager index blade
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        try{
            if($request->ajax()){
                $income = $this->IncomeManager
                    ->select("*",
                        \DB::raw('(CASE
                                    WHEN payment_method = "1" THEN "Cash"
                                    WHEN payment_method = "2" THEN "Swipe"
                                    WHEN payment_method = "3" THEN "Cheque"
                                    WHEN payment_method = "4" THEN "UPI"
                                    WHEN payment_method = "5" THEN "NEFT"
                                    END) AS payment_method'))->orderBy('id', 'desc');
                
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if ($fromdate || $todate) {
                    $income = $income->whereBetween('date', [$fromdate, $todate]);
                }

                //search by payment method
                $payment = $request->payment_method;
                if($request->payment_method){
                    $income = $income->where('payment_method','=',$payment);
                }
                $categoryId=$request->categoryId;
                if($categoryId){
                    $income = $income->WhereHas('getExpenseCategory', function($query) use($categoryId){
                        $query->where('income_category',$categoryId);
                    });
                }

                // search text
                $search = $request->search;
                if($search){
                    $income = $income->where(function ($query) use ($search) {
                        $query->where('given_by','LIKE','%'.$search.'%')
                            ->orWhere('note','LIKE','%'.$search.'%');
                            if (is_numeric($search)) {
                                $query->orWhere('amount', $search);
                            }
                    });
                }
                $income = $income->paginate(100);
                return response()->json([
                    View::make('admin.income_manager.data',compact('income'))->render()
                ]);
            }
            $expensecategory = $this->ExpenseCategory->where('type','=','1')->whereStatus(1)->pluck('name','id');
            return view('admin.income_manager.index',compact('expensecategory'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on Create income manager blade
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function create(){
        try{
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $patients = $this->OpdPatients->where('is_approved',1)->pluck('name','id');
            $expensecategory = $this->ExpenseCategory->where('type','=','1')->whereStatus(1)->pluck('name','id');
            return view('admin.income_manager.create',compact('category','expensecategory','patients'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Store income manager data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $rule = [
                'date' => 'required',
                'amount' => 'required'
            ];
            $income = $this->IncomeManager;

            $valid = Validator::make($request->all(),$rule);
            if($valid->fails()){
                return redirect()->back()
                    ->withErrors($valid->errors())
                    ->withInput();
            }
            $income->date = Carbon::parse($request->date)->format('Y-m-d');
            $income->amount = $request->amount;
            $income->payment_method = $request->payment_method;
            $income->given_by = $request->given_by;
            $income->note = $request->note;
            $income->income_category = $request->incomecategory;
            $income->patients_id = !empty($request->patients_id) ? $request->patients_id : null;
            $income->created_by = \Auth()->user()->id;
            $income->save();
            $depositeWord = $this->getWordOfNumber($income->amount);
            DB::commit();

            if($request->is_print == 1)
            {
                // dd(View::make('admin.income_manager.preview', compact('income','depositeWord'))->render());
                return response()->json([
                   
                    'status' => 2,
                    'data' => View::make('admin.income_manager.preview', compact('income','depositeWord'))->render()
                ]);
            }
            return response()->json([
                'status' => 1,
            ]);

            // return redirect('income-manager');
        }catch(Exception $e){
            DB::rollback();
            \Log::debug($e->getMessage());
        }
    }

    /**
    * Return on income manager edit balde
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function edit($id,Request $request){
        if($request->ajax()){
            $incomeId = $id;
        }
        else {
            $incomeId = decrypt($id);
        }

        $income = $this->IncomeManager->where('id',$incomeId)->first();
        $expensecategory = $this->ExpenseCategory->where('type','=','1')->whereStatus(1)->pluck('name','id');
        $patients = $this->OpdPatients->where('is_approved',1)->pluck('name','id');
        if($request->ajax()){
            $data = [];
            $data['income'] = $income;
            return $data;
        }
        return view('admin.income_manager.edit',compact('income','expensecategory','patients'));
    }

    /**
    * Update income manager
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id){
        try{
            // $id = decrypt($id);
            // dd($income);
            $income = $this->IncomeManager->find($id);
            $rule = [
                'date' => 'required',
                'amount' => 'required'
            ];

            $valid = Validator::make($request->all(),$rule);

            if($valid->fails()){
                return redirect()->back()
                    ->withErrors($valid->errors())
                    ->withInput();
            }
            
            $income->date = Carbon::parse($request->date)->format('Y-m-d');
            $income->amount = $request->amount;
            $income->payment_method = $request->payment_method;
            $income->given_by = $request->given_by;
            $income->note = $request->note;
            $income->income_category = $request->incomecategory;
            $income->patients_id = !empty($request->patients_id) ? $request->patients_id : null;
            $income->created_by = \Auth()->user()->id;
            $income->save();
            // return redirect('income-manager');
            $depositeWord = $this->getWordOfNumber($income->amount);
            
            if($request->is_print == 1)
            {
                return response()->json([
                   
                    'status' => 2,
                    'data' => View::make('admin.income_manager.preview', compact('income','depositeWord'))->render()
                ]);
            }
            return response()->json([
                'status' => 1,
            ]);

        } catch(Exception $e) {
            log::Debug($e);
            abort(500);
        }
    }
    /**
    * delete income manager
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function delete($id){
        try{
            $income = $this->IncomeManager->find($id);
            $income->delete();
            return 'true';
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Get income manager using category filter
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function incomecategory(Request $request){

        if($request->ajax()){
            $expense = $this->ExpenseCategory->where('type','=','1')->orderBy('id', 'desc');
            $search = $request->search;
            if($search){
                $expense = $expense->where(function($query) use($search){
                    $query->where('name','LIKE','%'.$search.'%');
                });
            }
            $expense = $expense->paginate(100);
            return response()->json([
                View::make('admin.income_manager.categoryData',compact('expense'))->render()
            ]);
        }
        return view('admin.income_manager.category');
    }
}
