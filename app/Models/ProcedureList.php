<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class ProcedureList extends BaseModel
{
    protected $table = 'procedures_list';
    public function getPatientsDetails(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id');
    }
}
