<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Database\Eloquent\Collection;
use \Carbon\Carbon;
use Exception;
use Session;
use View;
use Auth;
use Log;

class MedicalController extends AdminController
{
    /**
    * Return on medicine index page
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        try{
            $patients = $this->OpdPatients;
            //                 ->leftJoin('appointments', 'patients.id', '=', 'appointments.patients_id')
            //                 ->select('patients.*','appointments.*')
            //                 ->orderBy('patients.id','DESC')
            //                 ->groupBy('patients.id');
            $patientsData = $this->OpdPatients->orderBy('name','ASC')->pluck('name','id');
            if($request->ajax()){
                $pId = $request->patient_id;
                if($pId){
                    $patients = $patients->where('id',$pId);
                }
                $search = $request->search;
                if($search){
                    $patients = $patients->where('mobile_number','LIKE',$search.'%');
                }
                $appointment = $this->Appointment;
                if($request->date){
                    $date = explode("-",$request->date);
                    $now = Carbon::now()->format('Y-m-d');
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    if($startDate == $now  && $endDate == $now){
                        $patients = $patients->where(function($query) use($startDate,$endDate){
                            $query
                            ->whereHas('getAppointments', function($query) use($startDate,$endDate){
                                $query->whereBetween('date', [$startDate, $endDate]);
                                $query->where('is_done',1);
                            });
                        });
                    }
                    if($date){
                        $patients = $patients->where(function($query) use($startDate,$endDate){
                            $query
                            ->whereHas('getAppointments', function($query) use($startDate,$endDate){
                                $query->whereBetween('date', [$startDate, $endDate]);
                            });
                        });
                    }
                }
                // $patients = $patients->paginate(20);
                $patients = $patients->paginate(20);
                $data['status'] = 1;
                $data['patients'] = View::make('admin.medical.data',compact('patients'))->render();
                return $data;
            }
            return view('admin.medical.index',compact('patientsData'));
        }catch(Exception $e){
            abort(500);
        }
    }

     /**
    * Get Medicine
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getMedicines(Request $request,$patientsId){
        try{
            $pId = decrypt($patientsId);
            $categoryData = [];
            $ancData = [];
            $ivfData = [];
            $iuiData = [];
            $gynecData = [];
            $ancDate = [];
            $ivfDate = [];
            $iuiDate = [];
            $gynecDate = [];
            $patients = $this->OpdPatients->find($pId);
            $appointmentData = $this->Appointment->wherePatientsId($pId)->whereIn('category_id',['1','2','3','4','5','6','17'])->where('is_done',1)->orderBy('id','DESC')->pluck('category_id','category_id')->toArray();
            $appointmentData = array_unique($appointmentData);
            $lastCatgoryId = array_first($appointmentData);
            $lastType = null;
            if(array_key_exists(1,$appointmentData) || array_key_exists(2,$appointmentData)){
                $categoryData['1'] = 'IVF'; 
                $ivfHistoryDate = $this->IvfHistory->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
                $ivf = $this->IVF->where('patients_id',$pId)->first();
                if($ivf){
                    $ivfDate = [Carbon::parse($ivf->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ivf->created_at)->format('Y-m-d H:i:s')];
                }
                $ivfDate = array_merge($ivfHistoryDate,$ivfDate);
            }
            if(array_key_exists(3,$appointmentData) || array_key_exists(4,$appointmentData)){
                $categoryData['2'] = 'IUI'; 
                $iuiHistoryDate = $this->IuiHistory->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
                $iui = $this->IUI->where('patients_id',$pId)->first();
                if($iui){
                    $iuiDate = [Carbon::parse($iui->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($iui->created_at)->format('Y-m-d H:i:s')];
                }
                $iuiDate = array_merge($iuiHistoryDate,$iuiDate);
            }
            if(array_key_exists(5,$appointmentData) || array_key_exists(6,$appointmentData)){
                $categoryData['3'] = 'ANC'; 
                $ancHistoryDate = $this->AncHistory->where('patients_id',$patients)->pluck('created_at','created_at')->toArray();
                $ancDateData = $this->ANC->where('patients_id',$pId)->first();
                if($ancDateData){
                    $ancDate = [Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')];
                }
                $ancDate = array_merge($ancHistoryDate,$ancDate);
            }
            if(array_key_exists(17,$appointmentData)){
                $categoryData['4'] = 'Gynec';
                $gynecDate = $this->Gynec->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
            }
            if(!$request->date){
                if($lastCatgoryId == 1 || $lastCatgoryId == 2){
                    $lastType = 'category-data-1';
                }
                if($lastCatgoryId == 3 || $lastCatgoryId == 4){
                    $lastType = 'category-data-2';
                }
                if($lastCatgoryId == 5 || $lastCatgoryId == 6){
                    $lastType = 'category-data-3';
                }
                if($lastCatgoryId == 17){
                    $lastType = 'category-data-4';
                }
            }
            $mData = new Collection();
            $ancHistory = $this->AncHistory->where('patients_id',$pId)->orderBy('id','DESC');
            $anc = $this->ANC->where('patients_id',$pId)->orderBy('id','DESC');
            $ivfHistory = $this->IvfHistory->where('patients_id',$pId)->orderBy('id','DESC');
            $ivf = $this->IVF->where('patients_id',$pId)->orderBy('id','DESC');
            $iuiHistory = $this->IuiHistory->where('patients_id',$pId)->orderBy('id','DESC');
            $iui = $this->IUI->where('patients_id',$pId)->orderBy('id','DESC');
            $gynec = $this->Gynec->where('patients_id',$pId)->orderBy('id','DESC');
            $date = $request->date;
            $type = null;
            if(!empty($date)){
                $date = explode('-',$date);
                $type = $request->type;
                $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $endDate = Carbon::parse($endDate)->addDays(1)->format('Y-m-d');
                $startDate = $startDate.' 00:00:00';
                $endDate = $endDate.' 00:00:00';
                if($type == 'anc-date'){
                    $anc = $anc->whereBetween('created_at', [$startDate, $endDate]);
                    $ancHistory = $ancHistory->whereBetween('created_at', [$startDate, $endDate]);   
                    $lastType = 'category-data-3';
                }
                if($type == 'iui-date'){
                    $iui = $iui->whereBetween('created_at', [$startDate, $endDate]);
                    $iuiHistory = $iuiHistory->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-2';
                }
                if($type == 'ivf-date'){
                    $ivf = $ivf->whereBetween('created_at', [$startDate, $endDate]);
                    $ivfHistory = $ivfHistory->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-1';
                }
                if($type == 'gynec-date'){
                    $gynec = $gynec->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-4';
                }
            }
            $anc = $anc->get();
            $ivf = $ivf->get();
            $iui = $iui->get();
            $gynec = $gynec->get();
            $ancHistory = $ancHistory->get();
            $iuiHistory = $iuiHistory->get();
            $ivfHistory = $ivfHistory->get();
            $ancData = $ancHistory->merge($anc);
            $ivfData = $ivfHistory->merge($ivf);
            $iuiData = $iuiHistory->merge($iui);
            $data['mData'] = $mData;
            if($request->ajax()){
                $data['status'] = 1;
                $data['ancData'] = $ancData;
                $data['ivfData'] = $ivfData;
                $data['iuiData'] = $iuiData;
                $data['gynecData'] = $gynec;
                $data['ivfDate'] = $ivfDate;
                $data['ivfDate'] = $ivfDate;
                $data['dateType'] = $type;
                $data['ancDate'] = $ancDate;
                $data['date'] = $request->date;
                $data['lastType'] = $lastType;
                $data['gynecDate'] = $gynecDate;
                if($request->is_print){
                    $ancHistory = $this->AncHistory->where('patients_id',$pId)->orderBy('id','DESC');
                    $anc = $this->ANC->where('patients_id',$pId)->orderBy('id','DESC');
                    $ivfHistory = $this->IvfHistory->where('patients_id',$pId)->orderBy('id','DESC');
                    $ivf = $this->IVF->where('patients_id',$pId)->orderBy('id','DESC');
                    $iuiHistory = $this->IuiHistory->where('patients_id',$pId)->orderBy('id','DESC');
                    $iui = $this->IUI->where('patients_id',$pId)->orderBy('id','DESC');
                    $gynec = $this->Gynec->where('patients_id',$pId)->orderBy('id','DESC');
                    $anc = $anc->get();
                    $ivf = $ivf->get();
                    $iui = $iui->get();
                    $gynec = $gynec->get();
                    $ancHistory = $ancHistory->get();
                    $iuiHistory = $iuiHistory->get();
                    $ivfHistory = $ivfHistory->get();
                    $ancData = $ancHistory->merge($anc);
                    $ivfData = $ivfHistory->merge($ivf);
                    $iuiData = $iuiHistory->merge($iui);
                    $data['status'] = 2;
                    $data['patients'] = View::make('admin.medical.preview',$data)->render();
                    return $data;   
                }
                $data['status'] = 1;
                $data['patients'] = View::make('admin.medical.medicine_data',$data)->render();
                return $data;
            }
            return view('admin.medical.medicine',compact('patientsId','patients','categoryData'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }
}
