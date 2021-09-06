<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IndoorDeposit extends BaseModel
{
    public function getPatients(){
        return $this->belongsTo('App\Models\OpdPatients','patient_id');
    }

    public function getUsers(){
        return $this->belongsTo('App\User','admin_id');
    }

    public function reDrName(){
        return $this->belongsTo('App\Models\ReferenceDoctor','reference_doctor_id');
    }

    public function checkIndorDeposit(){
        $checkPatients = self::where('patient_id',$this->patient_id)->where('charge_type',$this->charge_type)->orderBy('id','DESC')->first();
        $id = null;
        if($checkPatients){
            $id = $checkPatients->id;
        }
        return ['id'=>$id];
    }
    public function getIvfPaymentReminder(){
        $date = date('Y-m-d',strtotime($this->created_at));
        $next_payment = null;
        $next_payment_date = null;
        $checkPatients = IvfPaymentReminder::where('patients_id',$this->patient_id)->whereDate('date',$date)->orderBy('id','DESC')->where('status',0)->first();
        if($checkPatients){
            $next_payment = $checkPatients->payment;
            $next_payment_date = $checkPatients->date;
        }
        return ['next_payment'=>$next_payment,'next_payment_date'=>$next_payment_date];
    }

    public function getReferenceDoctors(){
        return $this->belongsTo('App\Models\ReferenceDoctor','reference_doctor_id');
    }
    public function getTotalDiscount()
    {
        $packageDis = 0;
        $discount = self::where([
            // ['id', '>=', $this->id],
            'patient_id' => $this->patient_id,
            'cycle_no' => $this->cycle_no,
            'charge_type' => $this->charge_type,
        ])
        ->sum('discount');
        $ivfPayment = IvfPayment::where('patients_id',$this->patient_id)->where('cycle_no',$this->cycle_no)->first();
        if($ivfPayment)
        {
            $packageDis = $ivfPayment->discount;
        }
        if ($discount) {
            return $discount + $packageDis;
        }
        return $discount + $packageDis;
    }
    public function getTotalPaidAmountIVF()
    {

        $paidAmount = self::where([
            // ['id', '>=', $this->id],
            'patient_id' => $this->patient_id,
            'cycle_no' => $this->cycle_no,
            'charge_type' => 2,
        ])
        ->sum('amount');
        if ($paidAmount) {
            return $paidAmount;
        }
        return $paidAmount;
    }
    public function getInjectionCharge(){
        return $this->belongsTo('App\Models\InjectionCharge','injection');
    }
    public function getTotalInjection()
    {
        $count = self::where('charge_type',1)->where('patient_id',$this->patient_id)->where('cycle_no',$this->cycle_no)->where('injection',$this->injection)->count();
        // $count = self::where('charge_type',1)->whereDate('created_at',$this->created_at)->where('patient_id',$this->patient_id)->where('cycle_no',$this->cycle_no)->where('injection',$this->injection)->count();
        if ($count) {
            return $count;
        }
        return $count;
    }
    public function getTotalAmount()
    {
        $amount = self::where('charge_type',1)->where('patient_id',$this->patient_id)->where('cycle_no',$this->cycle_no)->where('injection',$this->injection)->sum('amount');
        if ($amount) {
            return $amount;
        }
        return $amount;
    }
}
