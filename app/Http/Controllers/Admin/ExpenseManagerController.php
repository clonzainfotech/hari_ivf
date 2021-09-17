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


class ExpenseManagerController extends AdminController
{

    /**
    * Return to expense manager index page
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        if($request->ajax()){
            $expense = $this->ExpenseManager
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
                $expense = $expense->whereBetween('date', [$fromdate, $todate]);
            }

            //search by payment method
            $payment = $request->payment_method;
            if($request->payment_method){
                $expense = $expense->wherePaymentMethod($payment);
            }
            $categoryId=$request->categoryId;
            if($categoryId){
                $expense = $expense->WhereHas('getExpenseCategory', function($query) use($categoryId){
                    $query->where('expense_category',$categoryId);
                });
            }

            // search text
            $search = $request->search;
            if($search){
                $expense = $expense->where(function($query) use($search){
                    $query->where('given_for','LIKE','%'.$search.'%')
                        ->orWhere('note','LIKE','%'.$search.'%');
                        if(is_numeric($search)){
                            $query->orWhere('amount', $search);
                        }
                });
            }
            if($request->isprint){
                $expense = collect($expense->get())->groupBy('getExpenseCategory.name');
                $data['status'] = 2;
                $data['expense'] = View::make('admin.expense_manager.preview',compact('expense'))->render();
                return $data;
            }
            $expense = $expense->paginate(100);
            return response()->json([
                View::make('admin.expense_manager.data',compact('expense'))->render()
            ]);
        }
        $expensecategory = $this->ExpenseCategory->where('type','=','2')->whereStatus(1)->pluck('name','id');
        return view('admin.expense_manager.index',compact('expensecategory'));
    }

    public function create(){
        $category = $this->Category->whereStatus(1)->pluck('name','id');
        $expensecategory = $this->ExpenseCategory->where('type','=','2')->whereStatus(1)->pluck('name','id');
        return view('admin.expense_manager.create',compact('category','expensecategory'));
    }

    /**
    * Store expence manager
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $rule = [
                'date' => 'required',
                'amount' => 'required'
            ];
            $expense = $this->ExpenseManager;

            $valid = Validator::make($request->all(),$rule);

            if($valid->fails()){
                return redirect()->back()
                    ->withErrors($valid->errors())
                    ->withInput();
            }
            $exdate = Carbon::parse($request->date)->format('Y-m-d');
            $expense->date = $exdate;
            $expense->amount = $request->amount;
            $expense->payment_method = $request->payment_method;
            $expense->given_for = $request->given_for;
            $expense->note = $request->note;
            $expense->expense_category = $request->expensecategory;
            $expense->created_by = \Auth()->user()->id;
            $expense->save();
            $expensecategory = $this->ExpenseCategory->where('type','=','2')->whereStatus(1)->pluck('name','id');
            $paymentMethod = ['2'=>'Swipe','1'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'];
            $data = [Carbon::parse($request->date)->format('d-m-Y'),$request->amount,$paymentMethod[$request->payment_method],$request->given_for,$request->note,$expensecategory[$request->expensecategory],\Auth()->user()->name,Carbon::now()->format('d-m-Y H:i')];
            $this->addGoogleSheet($data);

            DB::commit();
            return redirect('expense-manager');
        }catch(Exception $e){
            DB::rollback();
        }

    }

    /**
    * Return on edit blade
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function edit($id,Request $request){
        if($request->ajax()){
            $expenseId = $id;
        }
        else {
            $expenseId = decrypt($id);
        }

        $expense = $this->ExpenseManager->where('id',$expenseId)->first();

        if($request->ajax()){
            $data = [];
            $data['expense'] = $expense;
            return $data;
        }
        $expensecategory = $this->ExpenseCategory->where('type','=','2')->whereStatus(1)->pluck('name','id');
        return view('admin.expense_manager.edit',compact('expense','expensecategory'));
    }

    /**
    * Update expence manager
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id){
        try{
            $expense = $this->ExpenseManager->find($id);
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

            $expense->date = Carbon::parse($request->date)->format('Y-m-d');
            $expense->amount = $request->amount;
            $expense->payment_method = $request->payment_method;
            $expense->given_for = $request->given_for;
            $expense->note = $request->note;
            $expense->expense_category = $request->expensecategory;
            $expense->created_by = \Auth()->user()->id;
            $expense->save();
            return redirect('expense-manager');

        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Delete expence manager
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function delete($id){
        try{
            $expense = $this->ExpenseManager->find($id);
            $expense->delete();
            return 'true';
        }catch(Exception $e){
            return 'false';
        }
    }
}
