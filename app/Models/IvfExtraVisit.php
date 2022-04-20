<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class IvfExtraVisit extends BaseModel
{
    public function getSeenBy(){
        return $this->belongsTo('App\user','seen_by','id');
    }
    public function getPatientsDetails(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id');
    }
    public function getAppointment() {
        $anc = Appointment::where('patients_id',$this->patient_id)
                ->whereDate('date','=',$this->created_at)
                ->first();
        return $anc;
    }
}
