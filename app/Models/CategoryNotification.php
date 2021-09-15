<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class CategoryNotification extends BaseModel
{
    protected $table = 'category_notifications';
    
    public function getPatients(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
    public function categoryDetails(){
        return $this->belongsTo('App\Models\Category','category_id');
    }
}
