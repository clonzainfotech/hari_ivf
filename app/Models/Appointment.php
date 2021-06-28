<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Base\BaseModel;
use Carbon\Carbon;
use App\Models\IvfHistory;

class Appointment extends BaseModel
{
    use SoftDeletes;
    
    public function getPatientsDetails(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id');
    }
    
    public function categoryDetails(){
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function getAppointmentCharges(){
        return $this->hasOne('App\Models\AppointmentCharges','appointment_id');
    }

    public function getPatientCategories(){
        return $this->hasMany('App\Models\PatientsCategory','appointment_id');
    }

    public function getSeenBy(){
        return $this->belongsTo('App\user','seen_by','id');
    }
    public function nextAppointmentDate() {
        $date = self::where([
            ['id', '>', $this->id],
            'patients_id' => $this->patients_id,
            ['date', '>', $this->date],
        ])
        ->value('date');
        if ($date) {
            return date('d-m-Y', strtotime($date));
        }
        return $date;
    }
    public function nextAppointmentUsg() {
        $isUsg = self::where([
            ['id', '>', $this->id],
            'patients_id' => $this->patients_id,
            ['date', '>', $this->date],
        ])
        ->value('usg_status');
        if ($isUsg) {
            return $isUsg;
        }
        return $isUsg;
    }

    public function nextAppointmentDateTime() {
        $time = self::where([
            ['id', '>', $this->id],
            'patients_id' => $this->patients_id
        ])->value('time');

        if ($time) {
            return  date('H:i A', strtotime($time));
        }
        return $time;
    }

    public function getDonorDetails(){
        return $this->belongsTo('App\Models\Donor','patients_id', 'patients_id');
    }

    public function getIVFPLan(){
        $ivfPlanPatients = IVF::where('patients_id',$this->patients_id)
                        ->orderBy('id','DESC')
                        // ->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$this->date)
                        ->first();
        $ivfHistoryPlanPatients = IvfHistory::where('patients_id',$this->patients_id)
                        ->orderBy('id','DESC')
                        // ->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$this->date)
                        ->first();
        $plan = null;
        if($ivfPlanPatients){
            $planData = json_decode($ivfPlanPatients->plan_management);
            $plan = !empty($planData->plan) ? $planData->plan : 1;
        }
        if($ivfHistoryPlanPatients){
            $plan = $ivfHistoryPlanPatients->plan;
        }
        return['plan'=>$plan];
    }

    public function getChildNumber(){
        $anc = ANC::where('patients_id',$this->patients_id)
                    ->where('o_e->new_follow_up',$this->date)
                    ->orderBy('id','DESC')
                    ->first();
        if(!$anc){
            $anc = AncHistory::where('patients_id',$this->patients_id)
                    ->where('o_e->new_follow_up',$this->date)
                    ->orderBy('id','DESC')
                    ->first();
        }
        $childNumber = 0;
        if($anc){
            $childType = ['1'=>"Single",'2'=>"Twins",'3'=>"Triplets",'4'=>'Quadruple'];
            $childNumber = json_decode($anc->o_e);
            $childNumber = $childType[$childNumber->oe_no];
        }
        $status = $childNumber ? $childNumber : 0;
        return $status;
    }
}
