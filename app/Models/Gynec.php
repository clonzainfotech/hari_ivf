<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class Gynec extends BaseModel
{
    protected $table = 'gynec';

    public function getGynecPatients(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
    public function getSeenBy(){
        return $this->belongsTo('App\user','seen_by','id');
    }
}
