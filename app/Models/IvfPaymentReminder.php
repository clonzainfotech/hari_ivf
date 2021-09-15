<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class IvfPaymentReminder extends BaseModel
{
    protected $table = 'ivf_payment_reminder';

    public function getPatientsData(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id');
    }
}
