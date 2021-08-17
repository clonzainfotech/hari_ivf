<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;

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

    public function getReferenceDoctors(){
        return $this->belongsTo('App\Models\ReferenceDoctor','reference_doctor_id');
    }
    public function getTotalDiscount()
    {
        $discount = self::where([
            // ['id', '>=', $this->id],
            'patient_id' => $this->patient_id,
            'cycle_no' => $this->cycle_no,
            'charge_type' => $this->charge_type,
        ])
        ->sum('discount');
        if ($discount) {
            return $discount;
        }
        return $discount;
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
