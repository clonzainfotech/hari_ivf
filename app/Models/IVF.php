<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class IVF extends BaseModel
{
    protected $table = 'ivf';

    public function getPatientsInfoData(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }

    public function getPatientsDetails(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
}
