<?php

namespace App\models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends BaseModel
{
   public function getPatients() {
       return $this->belongsTo('App\Models\OpdPatients','patients_id')->where('is_approved',1);
   }
   public function getPatientscode() {
       return $this->belongsTo('App\Models\OpdPatients','patients_id');
   }
}
