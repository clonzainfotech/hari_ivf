<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Carbon\Carbon;

class OpdPatients extends BaseModel
{
    protected $table = 'patients';
    
    public function getReferenceDoctor(){
        return $this->belongsTo('App\Models\ReferenceDoctor','reference_doctor_id','id');
    }

    public function getReferenceDoctorPro(){
        return $this->belongsTo('App\Models\ReferenceDoctorPro','reference_doctor_pro_id','id');
    }

    public function getHospitalDoctor(){
        return $this->belongsTo('App\user','hospital_doctor_id','id');
    }

    // public function getAncAppointment(){
    //     return $this->hasMany('App\Models\Appointment','patients_id','id')->whereIn('category_id',[4,8]);
    // }

    public function getAppointment(){
        return $this->hasOne('App\Models\Appointment','patients_id','id');
    }

    public function getAppointments(){
        return $this->hasMany('App\Models\Appointment','patients_id','id');
    }

    public function getAnc(){
        return $this->hasOne('App\Models\ANC','patients_id','id');
    }

    public function getState(){
        return $this->belongsTo('App\Models\State','state','id');
    }

    public function getUser() {
        return $this->belongsTo('App\User','created_by');
    }

    public function getBookings(){
        return $this->hasMany('App\Models\IndoorBook','patient_id','id');
    }

    public function getIui(){
        return $this->hasOne('App\Models\IUI','patients_id','id');
    }
    public function getIuiHistory(){
        return $this->hasOne('App\Models\IuiHistory','patients_id','id');
    }

    public function getIvf(){
        return $this->hasOne('App\Models\IVF','patients_id','id');
    }
    public function getIvfHistory(){
        return $this->hasOne('App\Models\IvfHistory','patients_id','id');
    }

    public function getReviewData() {
        return $this->hasMany('App\Models\UserReview','patient_id', 'id');
    }
    
    public function lastAppointmentData(){
        return $this->hasOne('App\Models\Appointment','patients_id','id')->orderBy('id','DESC');
    }

    public function getGynec(){
        return $this->hasOne('App\Models\Gynec','patients_id','id');
    }

    public function getStich(){
        return $this->hasOne('App\Models\Stich','patients_id','id');
    }

    public function getIVFPayment(){
        return $this->hasOne('App\Models\IvfPayment','patients_id','id');
    }

    public function getCurrentDoneAppointment()
    {
        $appointment = null;
        $appointment = Appointment::where('patients_id',$this->id)
                            ->where('is_done','1')
                            ->whereDate('date',carbon::now()->format('Y-m-d'))
                            ->first();
        if($appointment)
        {
            $status = 1;
            return['status'=>$status,'medicine_status' => $appointment->is_medicine_given];
        }
        
        // return $this->hasOne('App\Models\Appointment','patients_id','id')->where('is_done','1')->whereDate('date',carbon::now()->format('Y-m-d'));
    }

}
