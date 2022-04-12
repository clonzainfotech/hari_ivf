<?php

namespace App\models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends BaseModel
{
   public function getPatients() {
       return $this->belongsTo('App\Models\OpdPatients','patients_id');
   }
   public function getPatientscode() {
       return $this->belongsTo('App\Models\OpdPatients','patients_id');
   }
   public function getSeenBy(){
    return $this->belongsTo('App\user','seen_by','id');
   }
}
