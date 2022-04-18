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
    /**
     * Get IUI second visit for based on cycle_no
     */
    public function getIuiSecondVisitCycleWise(){
        // return $this->belongsTo('App\Models\IuiHistory','patients_id','patients_id')->where('cycle_no',$this->cycle_no)->where('visit',2);
        $iui = IuiHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'visit' => 2
        ])->first();
        return !empty($iui) ? $iui : null;
    }
    /**
     * Get IUI forth visit for based on cycle_no
     */
    public function getIuiForthVisitCycleWise(){
        // return $this->belongsTo('App\Models\IuiHistory','patients_id','patients_id')->where('cycle_no',$this->cycle_no)->where('visit',2);
        $iui = IuiHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'visit' => 4
        ])->first();
        return !empty($iui) ? $iui : null;
    }
    /**
     * Get follicale's  visit for based on cycle_no
     */
    public function getOvalutionVisitCycleWise(){
        // return $this->belongsTo('App\Models\IuiHistory','patients_id','patients_id')->where('cycle_no',$this->cycle_no)->where('visit',2);
        $iui = IuiHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
            'visit' => 3
        ])->whereJsonContains('description',['ovalution' => 'yes'])->first();
        return !empty($iui) ? $iui : null;
    }
    /**
     * check that iui cycle is skip or not
     */
    public function checkIuiHistorySkip(){
        // return $this->belongsTo('App\Models\IuiHistory','patients_id','patients_id')->where('cycle_no',$this->cycle_no)->where('visit',2);
        $iui = IuiHistory::where([
            'cycle_no' => $this->cycle_no,
            'patients_id' => $this->patients_id,
        ])->whereJsonContains('description',['skip_cycle' => 'yes'])->first();
        return !empty($iui) ? $iui : null;
    }
    public function getSeenBy(){
        return $this->belongsTo('App\user','seen_by','id');
    }
    public function getAppointment() {
        $anc = Appointment::where('patients_id',$this->patients_id)
                ->whereDate('date','=',$this->created_at)
                ->first();
        return $anc;
    }
}
