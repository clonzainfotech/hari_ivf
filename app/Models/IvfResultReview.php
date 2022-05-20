<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use app\models\Base\BaseModel;
use Carbon\Carbon;

class IvfResultReview extends BaseModel
{ 
    protected $table = 'ivf_result_reviews';

    public function getPatients(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
    public function getTransferDate()
    {
        $ivf = null;
        $ivf = IvfHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'plan' => $this->plan
        ])->whereJsonContains('description',['collection' => 'progesterone'])->first();
        $data['transfer_date'] = !empty($ivf) ? carbon::parse(json_decode($ivf->description)->follow_up)->format('d-M-Y') : null;
        $data['transfer_by'] = !empty($ivf) ? $ivf->getSeenBy->name : null;
        return $data;
    }
    public function getResult()
    {
        $ivf = null;
        $ivf = IvfHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'plan' => $this->plan
        ])->whereJsonContains('description',['collection' => 'transfer'])->first();
        return !empty($ivf) ? json_decode($ivf->description,true) : null;
    }
    public function getResultValue()
    {
        $ivf = null;
        $ivf = IvfHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'plan' => $this->plan,
        ])->whereJsonContains('description',['is_transfer' => 'yes'])->orderBy('id','desc')->first();
        return !empty($ivf) ? json_decode($ivf->description,true) : null;
    }
}
