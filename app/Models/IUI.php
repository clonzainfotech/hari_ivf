<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class IUI extends BaseModel
{
    protected $table = 'iui';

    public function getPatientsInfo(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
    public function getPatientsDetails(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
}
