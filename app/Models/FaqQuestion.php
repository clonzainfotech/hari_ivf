<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class FaqQuestion extends BaseModel
{
    protected $table = 'faq_questions';
    
    public function getPatient() {
        return $this->belongsTo('App\Models\OpdPatients','question_by');
    }
    public function getAnswer() {
        return $this->hasMany('App\Models\FaqAnswer','que_id');
    }
}
