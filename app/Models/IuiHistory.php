<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class IuiHistory extends BaseModel
{
    use SoftDeletes;
    
    protected $table = 'iui_history';

    public function getPatientsInfoData(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id','id');
    }
    public function getPatientsDetails(){
        return $this->belongsTo('App\Models\OpdPatients','patients_id');
    }
    public function lastAppointmentData(){
        return $this->hasOne('App\Models\Appointment','patients_id','patients_id')->orderBy('id','DESC');
    }
    public function getIuiSecondVisitCycleWise(){
        // return $this->belongsTo('App\Models\IuiHistory','patients_id','patients_id')->where('cycle_no',$this->cycle_no)->where('visit',2);
        $iui = IuiHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'visit' => 2
        ])->first();
        return !empty($iui) ? $iui : null;
    }
    public function getSeenBy(){
        return $this->belongsTo('App\user','seen_by','id');
    }
}
