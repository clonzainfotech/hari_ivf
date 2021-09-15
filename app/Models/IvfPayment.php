<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class IvfPayment extends BaseModel
{
    protected $table = 'ivf_payment';

    public function getPatientsData(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id');
    }
    public function getTotalAmount()
    {
        $amount = IndoorDeposit::where('charge_type',2)->where('patient_id',$this->patients_id)->where('cycle_no',$this->cycle_no)->sum('amount');
        if ($amount) {
            return $amount;
        }
        return $amount;
    }
    //ivf payment total discount
    public function getTotalDiscount()
    {
        $discount = IndoorDeposit::where([
            // ['id', '>=', $this->id],
            'patient_id' => $this->patients_id,
            'cycle_no' => $this->cycle_no,
            'charge_type' => 2,
        ])
        ->sum('discount');
        if ($discount) {
            return $discount;
        }
        return $discount;
    }
}
