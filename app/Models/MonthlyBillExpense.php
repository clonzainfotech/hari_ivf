<?php

namespace App\Models;
use App\Models\Base\BaseModel;

class MonthlyBillExpense extends BaseModel
{

    public function getExpenseCategoryDetail()
    {
        return $this->belongsTo('App\Models\ExpenseCategory','expense_category','id');

    }

}
