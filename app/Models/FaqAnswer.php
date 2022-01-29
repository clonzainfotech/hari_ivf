<?php

namespace App\Models;

use App\Models\Base\BaseModel;

class FaqAnswer extends BaseModel
{
    protected $table = 'faq_answers';
    
    public function getPatient() {
        return $this->belongsTo('App\Models\OpdPatients','answer_by');
    }
    public function getQuestion() {
        return $this->belongsTo('App\Models\FaqQuestion','question_id');
    }
}
