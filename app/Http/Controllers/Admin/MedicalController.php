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
                                $query->whereBetween('date', [$startDate, $endDate])->where('is_done',1)->where('is_medicine_given','!=',2); // 2 = medicine is not given from opd
                            });
                        });
                    }
                    if($date){
                        $patients = $patients->where(function($query) use($startDate,$endDate){
                            $query
                            ->whereHas('getAppointments', function($query) use($startDate,$endDate){
                                $query->whereBetween('date', [$startDate, $endDate])->where('is_done',1)->where('is_medicine_given','!=',2);// 2 = medicine is not given from opd
                            });
                        });
                    }
                }
                // $patients = $patients->paginate(20);
                $patients = $patients->paginate(100);
                $data['status'] = 1;
                $data['patients'] = View::make('admin.medical.data',compact('patients'))->render();
                return $data;
            }
            return view('admin.medical.index',compact('patientsData'));
        }catch(Exception $e){
            log::debug($e);
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
            // dd(decrypt($patientsId));
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
            $stichDate = [];
            $patients = $this->OpdPatients->find($pId);
            $appointmentData = $this->Appointment->wherePatientsId($pId)->whereIn('category_id',['1','2','3','4','5','6','17','18','22'])->where('is_done',1)->orderBy('id','DESC')->pluck('category_id','category_id')->toArray();
            $appointmentData = array_unique($appointmentData);
            $lastCatgoryId = array_first($appointmentData);
            $lastType = null;
            if(array_key_exists(1,$appointmentData) || array_key_exists(2,$appointmentData)){
                $categoryData['1'] = 'IVF'; 
                // $ivfHistoryDate = $this->IvfHistory->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
                // $ivf = $this->IVF->where('patients_id',$pId)->first();
                // if($ivf){
                //     $ivfDate = [Carbon::parse($ivf->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ivf->created_at)->format('Y-m-d H:i:s')];
                // }
                // $ivfExtraVisitDate = $this->IvfExtraVisit->where('patient_id',$pId)->pluck('created_at','created_at')->toArray();
                // $ivfDate = array_merge($ivfHistoryDate,$ivfDate);
                // $ivfDate = array_merge($ivfDate,$ivfExtraVisitDate);
            }
            if(array_key_exists(3,$appointmentData) || array_key_exists(4,$appointmentData)){
                $categoryData['2'] = 'IUI'; 
                $iuiHistoryDate = $this->IuiHistory->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
                $iui = $this->IUI->where('patients_id',$pId)->first();
                if($iui){
                    $iuiDate = [Carbon::parse($iui->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($iui->created_at)->format('Y-m-d H:i:s')];
                }
                $iuiExtraVisitDate = $this->IuiExtraVisit->where('patient_id',$pId)->pluck('created_at','created_at')->toArray();

                $iuiDate = array_merge($iuiHistoryDate,$iuiDate);
                $iuiDate = array_merge($iuiDate,$iuiExtraVisitDate);
            }
            if(array_key_exists(5,$appointmentData) || array_key_exists(6,$appointmentData)){
                $categoryData['3'] = 'ANC'; 
                // $ancHistoryDate = $this->AncHistory->where('patients_id',$patients)->pluck('created_at','created_at')->toArray();
                // $ancDateData = $this->ANC->where('patients_id',$pId)->first();
                // if($ancDateData){
                //     $ancDate = [Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')];
                // }
                // $ancDate = array_merge($ancHistoryDate,$ancDate);
            }
            if(array_key_exists(17,$appointmentData) || array_key_exists(18,$appointmentData)){
                $categoryData['4'] = 'Gynec';
                // $gynecDate = $this->Gynec->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
            }
            if(array_key_exists(22,$appointmentData)){
                $categoryData['5'] = 'Stich';
                // $stichDate = $this->Stich->where('patients_id',$pId)->pluck('created_at','created_at')->toArray();
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
                if($lastCatgoryId == 17 || $lastCatgoryId == 18){
                    $lastType = 'category-data-4';
                }
                if($lastCatgoryId == 22){
                    $lastType = 'category-data-5';
                }
            }
            $mData = new Collection();
            $ancHistory = $this->AncHistory->where('patients_id',$pId)->orderBy('id','DESC');
            $anc = $this->ANC->where('patients_id',$pId)->orderBy('id','DESC');
            $ivfHistory = $this->IvfHistory->where('patients_id',$pId)->orderBy('id','DESC');
            $ivf = $this->IVF->where('patients_id',$pId)->orderBy('id','DESC');
            $ivfExtraVisit = $this->IvfExtraVisit->where('patient_id',$pId)->orderBy('id','DESC');
            $iuiHistory = $this->IuiHistory->where('patients_id',$pId)->orderBy('id','DESC');
            $iui = $this->IUI->where('patients_id',$pId)->orderBy('id','DESC');
            $iuiExtraVisit = $this->IuiExtraVisit->where('patient_id',$pId)->orderBy('id','DESC');

            $gynec = $this->Gynec->where('patients_id',$pId)->orderBy('id','DESC');
            $stich = $this->Stich->where('patients_id',$pId)->orderBy('id','DESC');
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
                    $iuiExtraVisit = $iuiExtraVisit->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-2';
                }
                if($type == 'ivf-date'){
                    $ivf = $ivf->whereBetween('created_at', [$startDate, $endDate]);
                    $ivfHistory = $ivfHistory->whereBetween('created_at', [$startDate, $endDate]);
                    $ivfExtraVisit = $ivfExtraVisit->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-1';
                }
                if($type == 'gynec-date'){
                    $gynec = $gynec->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-4';
                }
                if($type == 'stich-date'){
                    $stich = $stich->whereBetween('created_at', [$startDate, $endDate]);
                    $lastType = 'category-data-5';
                }
            }
            $anc = $anc->get();
            $ivf = $ivf->get();
            $iui = $iui->get();
            $gynec = $gynec->get();
            $stich = $stich->get();
            $ancHistory = $ancHistory->get();
            $iuiHistory = $iuiHistory->get();
            $iuiExtraVisit = $iuiExtraVisit->get();

            $ivfHistory = $ivfHistory->get();
            $ivfExtraVisit = $ivfExtraVisit->get();
            $ancData = $ancHistory->merge($anc)->sortbyDesc('created_at');

            $ivfData = $ivfHistory->merge($ivf);
            $ivfData = $ivfExtraVisit->merge($ivfData)->sortbyDesc('created_at');

            $iuiData = $iuiHistory->merge($iui);
            $iuiData = $iuiExtraVisit->merge($iuiData)->sortbyDesc('created_at');
            $data['mData'] = $mData;
            if($request->ajax()){
                $data['status'] = 1;
                $data['ancData'] = $ancData;
                $data['ivfData'] = $ivfData;
                $data['iuiData'] = $iuiData;
                $data['gynecData'] = $gynec;
                $data['stichData'] = $stich;
                // $data['ivfDate'] = $ivfDate;
                $data['dateType'] = $type;
                // $data['ancDate'] = $ancDate;
                $data['date'] = $request->date;
                $data['lastType'] = $lastType;
                // $data['gynecDate'] = $gynecDate;
                // $data['stichDate'] = $stichDate;
                $data['patientsId'] = $patientsId;
                if($request->is_print){
                    $ancHistory = $this->AncHistory->where('patients_id',$pId)->orderBy('id','DESC');
                    $anc = $this->ANC->where('patients_id',$pId)->orderBy('id','DESC');
                    $ivfHistory = $this->IvfHistory->where('patients_id',$pId)->orderBy('id','DESC');
                    $ivf = $this->IVF->where('patients_id',$pId)->orderBy('id','DESC');
                    $iuiHistory = $this->IuiHistory->where('patients_id',$pId)->orderBy('id','DESC');
                    $iui = $this->IUI->where('patients_id',$pId)->orderBy('id','DESC');
                    $gynec = $this->Gynec->where('patients_id',$pId)->orderBy('id','DESC');
                    $stich = $this->Stich->where('patients_id',$pId)->orderBy('id','DESC');
                    $anc = $anc->get();
                    $ivf = $ivf->get();
                    $iui = $iui->get();
                    $gynec = $gynec->get();
                    $stich = $stich->get();
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

     /**
    * Change medicine status in appointment table
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function medicineStatus(Request $request)
    {
        try
        {
           $appointment = $this->Appointment->where('patients_id',$request->pid)->whereDate('date',Carbon::now()->format('Y-m-d'))->where('is_done',1)->update(['is_medicine_given'=>1]);
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
        
    }
}
