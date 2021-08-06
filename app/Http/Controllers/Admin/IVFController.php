<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Exception;
use Session;
use View;
use Auth;
use Log;
use App\Models\OpdPatients as opd;
use App;

class IVFController extends AdminController
{
    /**
     * Dispaly only IVF Appointment and not completed IUI Appointment
     * Request parameter:date range, patient, isprint, plan
     * here serch functionality is working on appointment date, patient wise and plan wise
     */
    public function index(Request $request){
        try{
            $patients = $this->getPatients();
            if($request->ajax()){
                $appointment = $this->Appointment->where('is_procedure',0)->where('is_done',0)->whereIn('category_id',['1','2'])->orderBy('id','DESC');
                // search text
                $patientId = $request->patient_id;
                if($patientId){
                    $appointment = $appointment->where(function($query) use($patientId){
                        $query->whereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->Where('id', $patientId);
                        });
                    });
                }
                $plan = $request->plan;
                if($plan){
                    $ivfHistoryPatientsId = $this->IvfHistory->pluck('patients_id','patients_id')->toArray();
                    $ivfPatientsId = [];
                    if($plan == 1){
                        $ivfPatientsId = $this->IVF->whereNotIn('patients_id',$ivfHistoryPatientsId)->where(function($query) use($plan){
                                            $query->Where('plan_management->plan', $plan)
                                            ->orWhere('plan_management->plan','null');
                                        })->pluck('patients_id','patients_id')->toArray();
                    }else{
                        $ivfPatientsId = $this->IVF->whereNotIn('patients_id',$ivfHistoryPatientsId)->where(function($query) use($plan){
                            $query->Where('plan_management->plan', $plan);
                        })->pluck('patients_id','patients_id')->toArray();
                    }
                    $ivfHistoryPatientsId = $this->IvfHistory->whereRaw('Created_at IN (select MAX(Created_at) FROM ivf_history GROUP BY patients_id)')
                                                    ->wherePlan($plan)
                                                    ->pluck('patients_id','patients_id')
                                                    ->toArray();
                    $ivfPId = array_merge($ivfHistoryPatientsId,$ivfPatientsId);
                    $appointment = $appointment->whereIn('patients_id',$ivfPId);
                }

                if($request->date){
                    $date = explode("-",$request->date);

                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');

                    if($date){
                        $appointment = $appointment->whereBetween('date', [$startDate, $endDate]);
                    }
                }
                $search = $request->search;
                if($search){
                    $appointment = $appointment->where(function($query) use($search) {
                        $query
                        ->orWhereHas('getPatientsDetails', function($query) use($search) {
                            $query->where('mobile_number','LIKE',$search.'%');
                        });
                    });
                }

                if($request->isprint == 1){
                    $appointment = $this->Appointment->where('is_done',0)->whereIn('category_id',['1','2'])->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['ivf'] = View::make('admin.ivf.print',compact('appointment'))->render();
                    return $data;
                }
                $appointment = $appointment->paginate(100);
                $patient_notification = $this->patientNotification->first();
                $data['status'] = 1;
                $data['ivf'] = View::make('admin.ivf.data',compact('appointment','patient_notification'))->render();
                return $data;

            }
            return view('admin.ivf.index', compact('patients'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Return on iVF create page
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function create($patientsId,$appointmentId = null){
        try{
            $apId = null;
            $appointmentData = null;
            if($appointmentId){
                $apId = decrypt($appointmentId);
                $appointmentData = $this->Appointment->find($apId);
            }
            $pId = decrypt($patientsId);
            $ancPatients = $this->OpdPatients->find($pId);
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $hoData = $this->getHoData();
            $durationOfData = getDurationOfData(3)['data'];
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $lastAppointment = $this->Appointment->wherePatientsId($pId)->orderBy('id','DESC')->first();
            $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $category = $this->Category
            ->whereStatus(1)
            ->whereNotIn('id', [7])
            ->pluck('name','id');
            return view('admin.ivf.create',compact('hospitalDoctor','familyData','pastData','personalData','ancPatients','hoData','lastAppointment','patientsId','referenceDoctor','complaints','medicines','hospitalTime','leftOvaryData','rightOvaryData','durationOfData','category','appointmentData'));
        }catch(Exception $e){
            return back();
        }
    }

    /**
    * Return on ivf edit page
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function edit($patientsId){
        try{
            $patientsId = decrypt($patientsId);
            $ivf = $this->IVF->wherePatientsId($patientsId)->first();
            $hystroscopyImagesData = [];
            $hcgImagesData = [];
            $laproscopyImagesData = [];
            $bloodReportImagesData = [];
            $hsaReportImagesData = [];
            $patientsInfo = json_decode($ivf->patients_info);
            $ho = json_decode($ivf->h_o);
            $co = json_decode($ivf->c_o);
            $oh = json_decode($ivf->o_h);
            $mh = json_decode($ivf->m_h);
            $hoRx = json_decode($ivf->ho_rx);
            $donor = json_decode($ivf->donors);
            $investigation = json_decode($ivf->investigation);
            $husbandFactor = json_decode($ivf->husband_factor);
            $planManagement = json_decode($ivf->plan_management);
            $patientsDetailsHo = json_decode($ivf->patients_details_ho);
            $possibleCaseOfInfertility = json_decode($ivf->possible_case_of_infertility);
            $oe = json_decode($ivf->o_e);
            $treatment = json_decode($ivf->treatment);
            if(!empty($treatment)){
                unset($treatment->medicinedata);
            }
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $doseData = $this->Dose->pluck('name','name');
            $hoData = $this->getHoData();
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $durationOfData = ['other'=>'Other'] + getDurationOfData(3)['data'];
            $medicineKey = [];
            $hystroscopyImages  = !empty($investigation->hystroscopy->images) ? $investigation->hystroscopy->images : null;
            if($hystroscopyImages){
                foreach($hystroscopyImages as $key=>$row){
                    $hystroscopyImagesData[$key]['id'] = $key;
                    $hystroscopyImagesData[$key]['src'] = url($row);
                }
            }

            $hcgImages = !empty($investigation->hcg->images) ? $investigation->hcg->images : null;
            if($hcgImages){
                foreach($hcgImages as $key=>$row){
                    $hcgImagesData[$key]['id'] = $key;
                    $hcgImagesData[$key]['src'] = url($row);
                }
            }

            $laproscopyImages = !empty($investigation->laproscopy->images) ? $investigation->laproscopy->images : null;
            if($laproscopyImages){
                foreach($laproscopyImages as $key=>$row){
                    $laproscopyImagesData[$key]['id'] = $key;
                    $laproscopyImagesData[$key]['src'] = url($row);
                }
            }
            $bloodReportImages = !empty($investigation->blood_report->image) ? $investigation->blood_report->image : null;
            if($bloodReportImages){
                foreach($bloodReportImages as $key=>$row){
                    $bloodReportImagesData[$key]['id'] = $key;
                    $bloodReportImagesData[$key]['src'] = url($row);
                }
            }
            $hsaReportImages = !empty($investigation->hsa_report->images) ? $investigation->hsa_report->images : null;
            if($hsaReportImages){
                foreach($hsaReportImages as $key=>$row){
                    $hsaReportImagesData[$key]['id'] = $key;
                    $hsaReportImagesData[$key]['src'] = url($row);
                }
            }
            if(!empty($treatment)){
                $medicineKey = (array)$treatment;
                $medicineKey = array_column($medicineKey,'medicine');
                if(!empty($medicineKey)){
                    $medicineKey = array_combine($medicineKey,$medicineKey);
                }
            }
            $data = [];
            $data['personalData'] = $personalData;
            $data['pastData'] = $pastData;
            $data['familyData'] = $familyData;
            $data['ivf'] = $ivf;
            $data['medicineKey'] = $medicineKey;
            $data['patientsInfo'] = $patientsInfo;
            $data['ho'] = $ho;
            $data['co'] = $co;
            $data['mh'] = $mh;
            $data['donor'] = $donor;
            $data['oh'] = $oh;
            $data['hoRx'] = $hoRx;
            $data['hystroscopyImagesData'] = json_encode($hystroscopyImagesData,true);
            $data['hcgImagesData'] = json_encode($hcgImagesData,true);
            $data['laproscopyImagesData'] = json_encode($laproscopyImagesData,true);
            $data['bloodReportImagesData'] = json_encode($bloodReportImagesData,true);
            $data['hsaReportImagesData'] = json_encode($hsaReportImagesData,true);
            $data['investigation'] = $investigation;
            $data['husbandFactor'] = $husbandFactor;
            $data['planManagement'] = $planManagement;
            $data['patientsDetailsHo'] = $patientsDetailsHo;
            $data['possibleCaseOfInfertility'] = $possibleCaseOfInfertility;
            $data['oe'] = $oe;
            $data['durationOfData'] = $durationOfData;
            $data['treatment'] = $treatment;
            $data['referenceDoctor'] = $referenceDoctor;
            $data['complaints'] = $complaints;
            $data['patientsId'] = $patientsId;
            $data['medicines'] = $medicines;
            $data['hospitalTime'] = $hospitalTime;
            $data['doseData'] = $doseData;
            $data['leftOvaryData'] = $leftOvaryData;
            $data['hoData'] = $hoData;
            $data['rightOvaryData'] = $rightOvaryData;
            $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            return view('admin.ivf.edit',$data);
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return back();
        }
    }

    /**
    * Update IVF 
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id){
        try{
            $patientsId = decrypt($request->patients_id);
        }catch(Exception $e){
            return ['status'=>'false'];
        }
    }

    /**
    * Store IVF 
    * if result is negative then tranfer plan according to new-old request and start new cycle
    * if result is positive  then tranfer in ANC and insert first visit here
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        try{

            $isProcudure = 0;
            $appointmentTime = null;
            if($request->appointment_time){
                $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                $appointmentTime = $request->appointment_time || $request->appointment_time == 0 ? $hospitalTime[$request->appointment_time] : null;
            }
            $patientsId = decrypt($request->patients_id);
            $lastAppointmentData = $this->Appointment->wherePatientsId($patientsId)->orderBy('id','DESC')->first();
            if($request->appointment_id){
                $appointmentId = decrypt($request->appointment_id);
                $appointment = $this->Appointment->find($appointmentId);
                $appointment->category_id = $request->category;
                $appointment->save();
                $patientsCategory = $this->PatientsCategory;
                $patientsCategory->patients_id = $patientsId;
                $patientsCategory->appointment_id = $appointment->id;
                $patientsCategory->category_id = $request->category;
                $patientsCategory->save();
            }
            $ivfStatus = 1;
            $ancStatus = 0;
            $iuiStatus = 0;
            $gynecStatus = 0;
            if(!$request->visit){
                $ivf = $this->IVF;
                $hystroscopyOldImages = [];
                $laproscopyOldImages = [];
                $hcgOldImages = [];
                $bloodOldImages = [];
                $hsaOldImages = [];
                $checkIvf = $ivf->wherePatientsId($patientsId)->first();
                if($checkIvf){
                // dd($request);

                    $this->getImagesData('hystroscopy_old','ivf',$patientsId,$request->hystroscopy_old ? $request->hystroscopy_old : [-1]);
                    $this->getImagesData('laproscopy_old','ivf',$patientsId,$request->laproscopy_old ? $request->laproscopy_old : [-1]);
                    $this->getImagesData('hcg_old','ivf',$patientsId,$request->hcg_old ? $request->hcg_old : [-1]);
                    $this->getImagesData('blood_report_old','ivf',$patientsId,$request->blood_report_old ? $request->blood_report_old : [-1]);
                    $this->getImagesData('hsa_report_old','ivf',$patientsId,$request->hsa_report_old ? $request->hsa_report_old : [-1]);
                    $checkIvf = $ivf->wherePatientsId($patientsId)->first();
                    $ivf = $checkIvf;
                    $oldInvestigationData = json_decode($ivf->investigation);
                    if(!empty($oldInvestigationData)){
                        $hystroscopyOldImages = !empty($oldInvestigationData->hystroscopy->images) ? (array)$oldInvestigationData->hystroscopy->images : [];
                        $laproscopyOldImages = !empty($oldInvestigationData->laproscopy->images) ? (array)$oldInvestigationData->laproscopy->images : [];
                        $hcgOldImages = !empty($oldInvestigationData->hcg->images) ? (array)$oldInvestigationData->hcg->images : [];
                        $bloodOldImages = !empty($oldInvestigationData->blood_report->image) ? (array)$oldInvestigationData->blood_report->image : [];
                        $hsaOldImages = !empty($oldInvestigationData->hsa_report->images) ? (array)$oldInvestigationData->hsa_report->images : [];
                    }
                }
                if(!empty($request->ho['ho_details'])){
                    $this->hoData($request->ho['ho_details']);
                }
                if(!empty($request['p_detailes']['personal_history_history_type'])){
                    $this->storeIVFHpData($request['p_detailes']['personal_history_history_type'],1);
                }
                if(!empty($request['p_detailes']['past_history_type'])){
                    $this->storeIVFHpData($request['p_detailes']['past_history_type'],2);
                }
                if(!empty($request['p_detailes']['family_history'])){
                    $this->storeIVFHpData($request['p_detailes']['family_history'],3);
                }
                if(in_array($request->category,[5,6])){
                    $ivf = $this->ANC->where('patients_id',$patientsId)->first();
                    if(!$ivf){
                        $ivf = $this->ANC;
                    }
                    $ivfStatus = 0;
                    $ancStatus = 1;
                }
                if(in_array($request->category,[3,4])){
                    $ivf = $this->IUI->where('patients_id',$patientsId)->first();
                    if(!$ivf){
                        $ivf = $this->IUI;
                    }
                    $ivfStatus = 0;
                    $iuiStatus = 1;
                }
                if(in_array($request->category,[17,18])){
                    $ivf = $this->Gynec->where('patients_id',$patientsId)->first();
                    if(!$ivf){
                        $ivf = $this->Gynec;
                    }
                    $ivfStatus = 0;
                    $gynecStatus = 1;
                }
                if($gynecStatus == 0){
                    $ivf->patients_info = json_encode($request->p_info);
                    $ivf->h_o = json_encode($request->ho);
                    $ivf->c_o = json_encode($request->co);
                }
                if($gynecStatus == 1){
                    $ivf->ho = json_encode($request->ho);
                    $ivf->oe = json_encode($request->oe);
                    $ivf->co = json_encode($request->co);
                }
                $investigationData = $request->investigation;
                if($ancStatus == 1){
                    $ivf->patients_obstratics = json_encode($request->oh);
                }
                if($ivfStatus == 1 || $iuiStatus == 1){
                    if($ivfStatus == 1){
                        $ivf->donors = json_encode($request->donor);
                    }
                    $ivf->o_h = json_encode($request->oh);
                }
                $ivf->seen_by = $request->seen_by;
                $mhData = $request->mh;
                $pastDurationDay = !empty($request['mh']['past_duration_of_day']) ? $request['mh']['past_duration_of_day'] : [];
                $presentDay = !empty($request['mh']['present_duration_of_day']) ? $request['mh']['present_duration_of_day'] : [];
                $pastDurationDetails = !empty($request['mh']['past_duration_of_day_details']) ? $request['mh']['past_duration_of_day_details'] : [];
                $presentDayDetails = !empty($request['mh']['present_duration_of_day_details']) ? $request['mh']['present_duration_of_day_details'] : [];
                if(!empty($presentDay) || !empty($pastDurationDay)){
                    $dayData = array_merge((array)$pastDurationDay,(array)$presentDay);
                }
                if(!empty($pastDurationDetails) || !empty($presentDayDetails)){
                    $mhData['past_duration_of_day'] = !empty($pastDurationDetails) ? $pastDurationDetails : $pastDurationDay;
                    $mhData['present_duration_of_day'] = !empty($presentDayDetails) ? $presentDayDetails : $presentDay;
                    $dayData = array_merge((array)$pastDurationDetails,(array)$presentDayDetails);
                }
                if(!empty($dayData)){
                    durationData(3,$dayData);
                }
                if(!empty($request['investigation']['hystroscopy']['images'])){
                    foreach($request['investigation']['hystroscopy']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/ivf/report');
                        $hystroscopyImagesData[] = 'public/upload/ivf/report/' . $name;
                    }
                    $investigationData['hystroscopy']['images'] = array_merge($hystroscopyImagesData,$hystroscopyOldImages);
                }else{
                    $investigationData['hystroscopy']['images'] = $hystroscopyOldImages;
                }
                if(!empty($request['investigation']['laproscopy']['images'])){
                    foreach($request['investigation']['laproscopy']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/ivf/report');
                        $laproscopyImagesData[] = 'public/upload/ivf/report/' . $name;
                    }
                    $investigationData['laproscopy']['images'] = array_merge($laproscopyImagesData,$laproscopyOldImages);
                }else{
                    $investigationData['laproscopy']['images'] = $laproscopyOldImages;
                }
                if(!empty($request['investigation']['hcg']['images'])){
                    foreach($request['investigation']['hcg']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/ivf/report');
                        $hcgImagesData[] = 'public/upload/ivf/report/' . $name;
                    }
                    $investigationData['hcg']['images'] = array_merge($hcgImagesData,$hcgOldImages);
                }else{
                    $investigationData['hcg']['images'] = $hcgOldImages;
                }
                
                if(!empty($request['investigation']['blood_report']['image'])){
                    foreach($request['investigation']['blood_report']['image'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/ivf/blood/');
                        $bloodImagesData[] = 'public/upload/ivf/blood/' . $name;
                    }
                    $investigationData['blood_report']['image'] = array_merge($bloodImagesData,$bloodOldImages);
                }else{
                    $investigationData['blood_report']['image'] = $bloodOldImages;
                }
                if(!empty($request['investigation']['hsa_report']['images'])){
                    foreach($request['investigation']['hsa_report']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/ivf/report/');
                        $hsaImagesData[] = 'public/upload/ivf/report/' . $name;
                    }
                    $investigationData['hsa_report']['images'] = array_merge($hsaImagesData,$hsaOldImages);
                }else{
                    $investigationData['hsa_report']['images'] = $hsaOldImages;
                }
                if($gynecStatus == 0){
                    $ivf->m_h = json_encode($mhData);
                }else{
                    $ivf->mh = json_encode($mhData);
                }
                if($ivfStatus == 1 || $iuiStatus == 1){
                    $ivf->ho_rx = json_encode($request->ho_rx);
                    if(!empty($request->investigation['investigation_details'])){
                        $investigationData['investigation_details'] =  array_filter($request->investigation['investigation_details']);
                    }
                    $ivf->investigation = json_encode($investigationData);
                    $ivf->husband_factor = json_encode($request->h_factor);
                    $ivf->o_e = json_encode($request->oe);
                    if($ivfStatus == 1){
                        $ivf->plan_management = json_encode($request->plan_of_management);
                    }
                    $ivf->possible_case_of_infertility = json_encode($request->possible_case_of_infertility);
                }
                if($iuiStatus == 1){
                    $ivf->cycle_no = 1;
                    $ivf->cycle_status = 1;
                }
                $ivf->patients_details_ho = json_encode($request->p_detailes);
                $rightOveryData = !empty($request->oe['ovary']['right']['details']) ? array_filter($request->oe['ovary']['right']['details']) : [];
                $leftOveryData = !empty($request->oe['ovary']['left']['details']) ? array_filter($request->oe['ovary']['left']['details']) : [];
                if(!empty($leftOveryData)){
                    $data = array_unique($leftOveryData);
                    addOvaryAbnormalData($data,1);
                }
                if(!empty($rightOveryData)){
                    $data = array_unique($rightOveryData);
                    addOvaryAbnormalData($data,2);
                }

                $treatmentData = !empty($request->treatment['medicinedata']) ? array_filter($request->treatment['medicinedata']) : [];
                $this->complaintStore($request->co);
                if(!empty($treatmentData)){
                    $this->medicineData($treatmentData);
                    $this->treatmentData($request->treatment);
                }
                $ivf->treatment = !empty($request->treatment) ? json_encode($request->treatment) : json_encode($request->old_treatment);
                // if(!empty($treatmentData)){
                //     $this->medicineData($request->treatment['medicinedata']);
                // }
                $ivf->patients_id = $patientsId;
                $ivf->created_by = Auth::user()->id;
                $ivf->save();
                $ivfId = $ivf->id;
                $patients = $this->OpdPatients->find($patientsId);
                $patients->name = $request->name;
                $patients->weight = $request->weight;
                $patients->reference_doctor_id = $request->rd_reference;
                $patients->mobile_number = $request->mobile_number;

                $patients->residence = $request->residence;
                $patients->main_area = $request->main_area;
                $patients->city = $request->city;
                $patients->save();
            }
            if($request->visit){
                $lmpDateValue = !empty($request->data['lmp']['date']) ? $request->data['lmp']['date'] : null;
                $bloodReportOldImages = [];
                if($lmpDateValue){
                    $ivf = $this->IVF->wherePatientsId($patientsId)->orderBy('id','DESC')->first();
                    $ivf->lmp_date = Carbon::parse($lmpDateValue)->format('Y-m-d');
                    $ivf->save();
                }
                $msg = null;
                if($request->visit == 2 && $request->plan_type == 1){
                    $msg = 'Stimulation Start';
                }
                if($request->visit == 2 && $request->plan_type != 1){
                    $msg = 'Advise ';
                }
                if(!empty($request->data['co_type'])){
                    $this->complaintStore($request->data);
                }
                $isAnc = false;
                $ivfHistory = $this->IvfHistory;
                $checkIvfHistory = $this->IvfHistory->wherePatientsId($patientsId)
                                                    ->wherePlan($request->plan_type)
                                                    ->whereCycleNo($request->cycle_no)
                                                    ->whereVisit($request->visit)
                                                    ->first();
                if($checkIvfHistory){
                    $ivfHistory = $checkIvfHistory;
                }
                if($request->ivf_visit_id){
                    $ivfHistory = $this->IvfHistory->whereId($request->ivf_visit_id)->first();
                }
                $data = $request->data;
                
                $hcgTime = !empty($data['trigger']['hcg']['time']) ? Carbon::parse($data['trigger']['hcg']['time']) : null;
                $decapeptylTime = !empty($data['trigger']['decapeptyl']['time']) ? Carbon::parse($data['trigger']['decapeptyl']['time']) : null;
                $tDate = !empty($data['trigger_date']) ? Carbon::parse($data['trigger_date'])->format('Y-m-d') : null;
                $collectData = !empty($data['collection']) ? $data['collection'] : [];
                if((in_array('trigger',$collectData) && !empty($tDate) && $request->is_trigger == 'yes') && (!empty($hcgTime) || !empty($decapeptylTime))){
                    if($request->plan_type == 1){
                        $msg = 'Advise Trigger ';
                    }
                    if(!empty($decapeptylTime)){
                        $dTime = Carbon::parse($decapeptylTime)->format('H:i:s');
                        $checkTriggerTime = $dTime;
                    }
                    if(!empty($hcgTime)){
                        $hTime = Carbon::parse($hcgTime)->format('H:i:s');
                        $checkTriggerTime = $hTime;
                    }
                    if(!empty($hcgTime) && !empty($decapeptylTime)){
                        $checkHcgTime = strtotime($hcgTime);
                        $checkDecapeptylTime = strtotime($decapeptylTime);
                        if($checkHcgTime > $checkDecapeptylTime){
                            $checkTriggerTime = $dTime;
                        }
                    }
                    $checkTrigger = $this->IvfHistory->where('trigger_date',$tDate)->whereNotNull('trigger_time')->get();
                    foreach($checkTrigger as $row){
                        $totalDuration = Carbon::parse($row->trigger_time)->diffInMinutes(Carbon::parse($checkTriggerTime));
                        if($totalDuration < 15){
                            return ['status'=>4];
                        }
                    }
                }
                if((empty($data['oe']['oe_type']['type']) && empty($data['oe']['ut']['ut_type'])
                && ($data['is_upt'] == 'no')  && empty($data['ovary']['ovary_status']) && empty($data['ovary']['ovary_type']['left']['details']) && empty($data['ovary']['ovary_type']['right']['details']) && empty($data['oe']['endometrial_cavity']['cavity']) && empty($data['oe']['endometrial_cavity']['size']) && empty($data['et_details'])
                && empty($data['medicinedata']) && empty($data['remark']) && (empty($data['collection']) || (in_array('trigger',$collectData) && $data['trigger']['update_status'] == 'yes'))) && !empty($data['plan']) && !$request->ivf_visit_id && empty($data['skip_cycle'])){
                    $request->isprint = null;
                    $lastVisitData = $this->IvfHistory->wherePatientsId($patientsId)->orderBy('id','DESC')->first();
                    if($lastVisitData){
                            $lastData = json_decode($lastVisitData->description);
                            $lastData->plan = $data['plan'];
                            $lastVisitData->cycle_status = 2;
                            $lastVisitData->description = json_encode($lastData);
                            $lastVisitData->save();
                    }else{
                        $ivfData = $this->IVF->wherePatientsId($patientsId)->orderBy('id','DESC')->first();
                        $ivfPlanData = json_decode($ivfData->plan_management);
                        $ivfPlanData->plan = $data['plan'];
                        $ivfData->plan_management = json_encode($ivfPlanData);
                        $ivfData->save();
                    }
                }
                else{
                    
                    $rightOveryData = !empty($request->data['oe']['ovary']['right']['details']) ? array_filter($request->data['oe']['ovary']['right']['details']) : [];
                    $leftOveryData = !empty($request->data['oe']['ovary']['left']['details']) ? array_filter($request->data['oe']['ovary']['left']['details']) : [];
                    if(!empty($leftOveryData)){
                        $ldata = array_unique($leftOveryData);
                        addOvaryAbnormalData($ldata,1);
                    }
                    if(!empty($rightOveryData)){
                        $rdata = array_unique($rightOveryData);
                        addOvaryAbnormalData($rdata,2);
                    }
                    
                    $data['medicinedata'] = !empty($data['medicinedata']) ? $data['medicinedata'] : (!empty($data['old_medicine']) ? $data['old_medicine'] : []);
                    $ivfHistory->cycle_status = 1;
                    $skip = false;
                    if((!empty($data['plan']) || !empty($data['skip_cycle']) && $data['skip_cycle'] == 'yes') || !empty($data['transfer']['result_type']) && $data['transfer']['result_type'] == 'conceive'){
                        $ivfHistory->cycle_status = 2;
                        $this->IvfHistory->where('patients_id',$patientsId)->where('plan',!empty($data['plan']) ? $data['plan'] : $request['plan_type'])->update(['cycle_status'=>2]);
                        $skip = true;
                    }
                    $isSkip = false;
                    if(!empty($data['skip_cycle']) && $data['skip_cycle'] == 'yes'){
                        $isSkip = true;
                    }
                    $skipCycle = $this->IvfHistory->wherePatientsId($patientsId)->wherePlan(!empty($data['plan']) ? $data['plan'] : $request['plan_type'])->where('cycle_status',2)->orderBy('id','DESC')->first();
                    if($skipCycle)
                    {
                        $skipCycleData = json_decode($skipCycle->description);
                        if(!empty($skipCycleData->skip_cycle) && $skipCycleData->skip_cycle == 'yes'){
                            $display_cycle = $skipCycle->cycle_no;
                        }
                    }
                    
                    $report = [];
                    // tranfer report upload
                    if(!empty($data['transfer']['report'])){
                        foreach ($data['transfer']['report'] as $row) {
                            $name = $this->uploadImage($row, 'public/upload/ivf/transfer_report');
                            $report[] = 'public/upload/ivf/transfer_report/' . $name;
                        }
                        $data['transfer']['report'] = $report;
                    }
                    if(!empty($data['transfer']['upt_type']) && $data['transfer']['upt_type'] == 'positive' && !empty($data['transfer']['result_type']) && $data['transfer']['result_type'] == 'conceive'){
                        $data['plan'] = !empty($data['plan']) ? $data['plan'] : $request['plan_type'];
                        $data['transfer_type'] = 'new';
                        $data['skip_reason'] = null;
                        $category_id = 5;
                        $this->IvfHistory->where('patients_id',$patientsId)->where('plan',!empty($data['plan']) ? $data['plan'] : $request['plan_type'])->update(['cycle_status'=>2]);
                        $ivfFirstVisitData = $this->IVF->wherePatientsId($patientsId)->orderBy('id','DESC')->first();
                        //set EDD date and lmpdate from second visit
                        $ivfSecondVisit = $this->IvfHistory->where('patients_id',$patientsId)->where('plan',!empty($data['plan']) ? $data['plan'] : $request['plan_type'])->where('cycle_no',$request->cycle_no)->where('visit',2)->first();
                        $ivfSecondVisitData = json_decode($ivfSecondVisit->description);
                        $ivf_mh_data = json_decode($ivfFirstVisitData->m_h);
                        $ivf_mh_data->last_menstrual_date = !empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : '';
                        $ivf_mh_data->lmd_date_diff = !empty($ivfSecondVisitData->lmp->lmp_date_diff) ? $ivfSecondVisitData->lmp->lmp_date_diff : '';
                        $ivf_mh_data->edd = !empty($ivfSecondVisitData->lmp->date) ? Carbon::parse($ivfSecondVisitData->lmp->date)->addMonths(9)->addDays(7)->format('Y-m-d') : '';
                        $ivfFirstVisitData->m_h = json_encode($ivf_mh_data);
                        if(!empty($ivfFirstVisitData->h_o))
                        {
                            $hoData = json_decode($ivfFirstVisitData->h_o);
                            $hoDetails = $hoData->ho_details;
                            $hoDetails = strtolower($hoDetails);
                            $hoMonth = str_replace('month', '-', $hoDetails);
                            if(strpos($hoDetails,'months') !== false || strpos($hoDetails,'days') !== false){
                                $hoDetails = str_replace('s', '', $hoMonth);
                            }else{
                                $hoDetails = $hoMonth;
                            }
                            if(strpos($hoDetails,'day') !== false){
                                $hoDetails = str_replace('day', '', $hoDetails);
                            }
                            $hoDetails = str_replace(' ', '', $hoDetails);
                            $hoMonth = explode('-',$hoDetails);
                            $hoMonthData = !empty($hoMonth[0]) ? $hoMonth[0] : 0;
                            // dd($hoMonthData);
                            $hoDay = !empty($hoMonth[1]) ? $hoMonth[1] : 0;
                            $days = 30;
                            $monthDays = ((int)$hoMonthData * (int)$days) + (int)str_replace(' ', '', $hoDay);
                            $oldDate = Carbon::parse(!empty($ivfSecondVisitData->lmp->date) ? $ivfSecondVisitData->lmp->date : date('Y-m-d'))->format('Y-m-d');
                            $nowDate = Carbon::now();
                            $diffDays = Carbon::parse($oldDate)->diffInDays($nowDate);
                            $totalDays = $monthDays + $diffDays;
                            $hoData->ho_details = (int)($totalDays/$days).' month '.$totalDays % $days.' day';
                            // $hoDetails->ho_details =  $hoDetails;
                            $ivfFirstVisitData->h_o = json_encode($hoData);
                        }
                        $autoRemark = [];
                        $ancData = $this->ANC;
                        $autoRemark['remark'] = "Conceived from IVF";
                        $ancData->patients_id = $patientsId;
                        $ancData->patients_info = $ivfFirstVisitData->patients_info;
                        $ancData->patients_details_ho = $ivfFirstVisitData->patients_details_ho;
                        $ancData->patients_obstratics = $ivfFirstVisitData->o_h;
                        $ancData->created_by = Auth::user()->id;
                        $ancData->m_h = $ivfFirstVisitData->m_h;
                        $ancData->h_o = $ivfFirstVisitData->h_o;
                        $ancData->c_o = $ivfFirstVisitData->c_o;
                        $ancData->o_e = json_encode($autoRemark);
                        $ancData->treatment = $ivfFirstVisitData->treatment;
                        $ancData->edd = $ivf_mh_data->edd;
                        $ancData->save();
                        $isAnc = true;
                    }
                    if(!empty($request->data['protocol'])){
                        $protocol = array_filter(array_column($request->data['protocol'],'dose'));
                        if(!empty($protocol)){
                            $this->doseData($protocol);
                        }
                    }
                    $triggerTime = null;
                    if(!empty($hcgTime) || !empty($decapeptylTime)){
                        if(!empty($decapeptylTime)){
                            $dTime = Carbon::parse($decapeptylTime)->format('H:i:s');
                            $triggerTime = $dTime;
                        }
                        if(!empty($hcgTime)){
                            $hTime = Carbon::parse($hcgTime)->format('H:i:s');
                            $triggerTime = $hTime;
                        }
                        if(!empty($hcgTime) && !empty($decapeptylTime)){
                            $hcgTime = strtotime($hcgTime);
                            $decapeptylTime = strtotime($decapeptylTime);
                            if($hcgTime > $decapeptylTime){
                                $triggerTime = $dTime;
                            }
                        }
                    }
                    $investigationData = $request->investigation;
                    $hystroscopyOldImages = [];
                    $laproscopyOldImages = [];
                    $bloodReportOldImages = [];
                    $usgReportOldImages = [];
                    $hsaReportOldImages = [];
                    if(!empty($checkIvfHistory))
                    {
                        
                        $this->getImagesData('hystroscopy_old','ivf_history',$checkIvfHistory->id,$request->hystroscopy_old ? $request->hystroscopy_old : [-1]);
                        $this->getImagesData('laproscopy_old','ivf_history',$checkIvfHistory->id,$request->laproscopy_old ? $request->laproscopy_old : [-1]);
                        $this->getImagesData('blood_report_old','ivf_history',$checkIvfHistory->id,$request->blood_report_old ? $request->blood_report_old : [-1]);
                        $this->getImagesData('usg_old','ivf_history',$checkIvfHistory->id,$request->usg_old ? $request->usg_old : [-1]);
                        $this->getImagesData('hsa_report_old','ivf_history',$checkIvfHistory->id,$request->hsa_report_old ? $request->hsa_report_old : [-1]);
                        $checkIvfHistory = $this->IvfHistory->wherePatientsId($patientsId)
                                                    ->wherePlan($request->plan_type)
                                                    ->whereCycleNo($request->cycle_no)
                                                    ->whereVisit($request->visit)
                                                    ->first();
                        $oldInvestigationData = json_decode($checkIvfHistory->investigation);
                        $ivfHistoryData = json_decode($checkIvfHistory->description);
                        if(!empty($oldInvestigationData)){
                            $hystroscopyOldImages = !empty($oldInvestigationData->hystroscopy->images) ? (array)$oldInvestigationData->hystroscopy->images : [];
                            $laproscopyOldImages = !empty($oldInvestigationData->laproscopy->images) ? (array)$oldInvestigationData->laproscopy->images : [];
                            $hcgOldImages = !empty($oldInvestigationData->hcg->images) ? (array)$oldInvestigationData->hcg->images : [];
                        }
                        if($ivfHistoryData)
                        {
                            $bloodReportOldImages = !empty($ivfHistoryData->blood_report->image) ? (array)$ivfHistoryData->blood_report->image : [];
                            $usgReportOldImages = !empty($ivfHistoryData->usg->images) ? (array)$ivfHistoryData->usg->images : [];
                            $hsaReportOldImages = !empty($ivfHistoryData->hsa_report->images) ? (array)$ivfHistoryData->hsa_report->images : [];
                        }
                    }
                    if(!empty($request['investigation']['hystroscopy']['images'])){
                        foreach($request['investigation']['hystroscopy']['images'] as $key=>$row){
                            $name = $this->uploadImage($row, 'public/upload/ivf/report');
                            $hystroscopyImagesData[] = 'public/upload/ivf/report/' . $name;
                        }
                        $investigationData['hystroscopy']['images'] = array_merge($hystroscopyImagesData,$hystroscopyOldImages);

                    }else{
                        $investigationData['hystroscopy']['images'] = $hystroscopyOldImages;
                    }
                    if(!empty($request['investigation']['laproscopy']['images'])){
                        foreach($request['investigation']['laproscopy']['images'] as $key=>$row){
                            $name = $this->uploadImage($row, 'public/upload/ivf/report');
                            $laproscopyImagesData[] = 'public/upload/ivf/report/' . $name;
                        }
                        $investigationData['laproscopy']['images'] = array_merge($laproscopyImagesData,$laproscopyOldImages);
                    }else{
                        $investigationData['laproscopy']['images'] = $laproscopyOldImages;

                    }
                    if(!empty($request->data['blood_report']['image'])){
                        foreach($request->data['blood_report']['image'] as $key=>$row){
                            $name = $this->uploadImage($row, 'public/upload/ivf/blood/');
                            $bloodReport[] = 'public/upload/ivf/blood/' . $name;
                        }
                        $data['blood_report']['image'] = array_merge($bloodReport,$bloodReportOldImages);
                    }
                    else{
                        $data['blood_report']['image'] = $bloodReportOldImages;
                    }
                    if(!empty($request->data['usg']['images'])){
                        foreach($request->data['usg']['images'] as $key=>$row){
                            $name = $this->uploadImage($row, 'public/upload/ivf/report/');
                            $usgReport[] = 'public/upload/ivf/report/' . $name;
                        }
                        $data['usg']['images'] = array_merge($usgReport,$usgReportOldImages);
                    }
                    else{
                        $data['usg']['images'] = $usgReportOldImages;
                    }
                    if(!empty($request->data['hsa_report']['images'])){
                        foreach($request->data['hsa_report']['images'] as $key=>$row){
                            $name = $this->uploadImage($row, 'public/upload/ivf/report/');
                            $hsaImagesData[] = 'public/upload/ivf/report/' . $name;
                        }
                        $data['hsa_report']['images'] = array_merge($hsaImagesData,$hsaReportOldImages);
                    }else{
                        $data['hsa_report']['images'] = $hsaReportOldImages;
                    }
                    $ivfHistory->description = json_encode($data);
                    $ivfHistory->investigation = isset($request->investigation) ? json_encode($investigationData) : null;
                    $ivfHistory->trigger_date = !empty($request->data['trigger_date']) ? Carbon::parse($request->data['trigger_date'])->format('Y-m-d') : null;
                    $ivfHistory->trigger_time = $triggerTime;
                    $ivfHistory->visit = $request->visit;
                    $ivfHistory->cycle_no = $request->cycle_no;
                    $ivfHistory->display_cycle = isset($display_cycle) ? $display_cycle : $request->cycle_no;
                    $ivfHistory->plan = $request->plan_type;
                    $ivfHistory->patients_id = $patientsId;
                    $ivfHistory->seen_by = $request->seen_by;
                    $ivfHistory->created_by = Auth::user()->id;
                    // dd($ivfHistory);
                    $ivfHistory->save();
                    $ivf = $ivfHistory;
                    $ivfId = $ivfHistory->id;
                    // ivf repoet save
                    if(!empty($request->data['collection']) && in_array('collected',$request->data['collection']) && !empty($request->data['collected']['report']) && !empty($request->data['collected']['report']['type']) && $request->data['collected']['report']['type'] == 'report'){
                        $ivfReport = $this->IVFReport;
                        if(!empty($request->ivf_report_id)){
                            $ivfReport = $ivfReport->whereId(decrypt($request->ivf_report_id))->first();
                        }
                        $ivfReport->patients_id = $patientsId;
                        $ivfReport->plan = $request->plan_type;
                        $ivfReport->cycle_no = $request->cycle_no;
                        $ivfReport->visit = $request->visit;
                        $ivfReport->created_by = Auth::user()->id;
                        $ivfReport->date = !empty($request->report_date) ? \Carbon\Carbon::parse($request->report_date)->format('Y-m-d') : \Carbon\Carbon::parse($lastAppointmentData->date)->format('Y-m-d');
                        $ivfReport->reason = $request->reason;
                        $ivfReport->volume = json_encode($request->volume);
                        $ivfReport->sperm_count = json_encode($request->sperm);
                        $ivfReport->total_motility = json_encode($request->motility);
                        $ivfReport->actively = json_encode($request->actively);
                        $ivfReport->sluggishly = json_encode($request->sluggishly);
                        $ivfReport->non_motile = json_encode($request->motile);
                        $ivfReport->morphology = json_encode($request->morphology);
                        $ivfReport->pus_cells = json_encode($request->cells);
                        $ivfReport->save();
                    }
                    // next appointment save
                    $followupDate = !empty($request->data['follow_up']) ? $request->data['follow_up'] : null;
                    $transferDate = !empty($request->data['transfer']['follow_up']) ? $request->data['transfer']['follow_up'] : null;
                    if($transferDate){
                        $followupDate = $transferDate;
                    }
                    if(!empty($request->data['progesterone']['type']) || (isset($request->data['collection']) && in_array('trigger', $request->data['collection']))){
                        $isProcudure = 1;
                    }
                    if($followupDate){
                        $currentDate = date('Y-m-d');
                        $followDate = date('Y-m-d',strtotime($followupDate));
                            $appointmentTime = null;
                            $fDate = !empty($followDate) ? Carbon::parse($followDate)->format('Y-m-d') : null;
                            if($fDate){
                                $requestData = new \Illuminate\Http\Request();
                                $requestData->replace(['date' => $fDate,'status'=>true]);
                                $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);

                                if(!empty($nextAppontment['time']) || (!empty($nextAppontment['time']) || $nextAppontment['time'] == 0)){
                                    $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                                    $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                                    $followDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $followDate;
                                }
                            }
                            $appointment = $this->Appointment->where('patients_id',$patientsId)->orderBy('id','DESC')->first();
                            // $checkAppointment = $this->Appointment->wherePatientsId($patientsId)->whereDate('date',$followDate)->orderBy('id','DESC')->first();
                            // if(!$checkAppointment && !$isSkip){
                            if($appointment){
                                $appointmentData['appointmentId'] = encrypt($appointment->id);
                                $appointmentData['date'] = $followDate;
                                $appointmentData['isAnc'] = $isAnc;
                                $appointmentData['time'] = $appointmentTime;
                                $appointmentData['is_procedure'] = $isProcudure;
                                $appointmentData['remark'] = isset($request->data['collection']) && in_array('transfer', $request->data['collection']) ? 'IVF Result' : null;
                                $nextAppointment = $this->nextAppointmentData($appointmentData);
                            }
                    }
                    // transfer report save
                    if(isset($request->data['collection']) && in_array('transfer', $request->data['collection'])) {
                        $transferReport = $this->IvfTransferReport;
                        $transferReport->patient_id = $patientsId;
                        $transferReport->cycle_no = $request->cycle_no;
                        $transferReport->plan = $request->plan_type;
                        $transferReport->visit_no = $request->visit;
                        if ($request->ivf_transfer_report_id){
                            $transferReport = $this->IvfTransferReport->whereId(decrypt($request->ivf_transfer_report_id))->first();
                        }
                        $transferReport->indication = $request->indication;
                        // $transferReport->et_date = !empty($request->et_date) ? \Carbon\Carbon::parse($request->et_date)->format('Y-m-d') : \Carbon\Carbon::parse($lastAppointmentData->date)->format('Y-m-d');
                        $transferReport->et_date = !empty($request->et_date) ? \Carbon\Carbon::parse($request->et_date)->format('Y-m-d') : null;
                        $transferReport->day = $request->day;
                        $transferReport->endo_thickness = $request->endo_thickness;
                        $transferReport->et_procedure = $request->et_procedure;
                        if(!empty($request->embryos_transferred_image)){
                            $imagePath = 'public/upload/ivf/report';
                            $picture = $request->embryos_transferred_image;
                            $imageName = $this->uploadImage($picture, $imagePath);
                            $request->embryos_transferred_image = $imagePath.'/'.$imageName;
                        }
                        // dd($request->embryos_transferred_image);
                        $transferReport->embryos_transferred = $request->embryos_transferred;
                        $transferReport->embryos_transferred_image = $request->embryos_transferred_image;
                        $transferReport->frozen_embryos = $request->frozen_embryos;
                        $transferReport->pickup_date = !empty($request->pick_up_date) ? \Carbon\Carbon::parse($request->pick_up_date)->format('Y-m-d') : null;
                        $transferReport->simulation_protocol = $request->simulation_protocol;
                        $transferReport->total_occ = $request->total_occ;
                        $transferReport->mll = $request->mll;
                        $transferReport->ml = $request->ml;
                        $transferReport->gv = $request->gv;
                        $transferReport->oocycle_quality = $request->oocycle_quality;
                        $transferReport->sperm_quality = $request->sperm_quality;
                        $transferReport->fertilization_procedure = $request->fertilization_procedure;
                        $transferReport->remark = $request->transfer_remark;
                        $transferReport->save();
                        //set Notification
                        $categoryPatientData['patients_id'] = $patientsId;
                        $categoryPatientData['date'] = $followDate;
                        $categoryPatientData['reminder_date'] = Carbon::parse($followDate)->subDays(1)->format('Y-m-d');
                        $categoryPatientData['message'] = "IVF Result";
                        $categoryPatientData['category_id'] = !empty($request->category) ? $request->category : 2;
                        $nextAppontment = $this->storeCategoryNotification($categoryPatientData);
                    }
                }
            }
            $now = Carbon::now()->format('Y-m-d');
            // dd($request->ivf_visit_id);
            if(!$request->ivf_visit_id)
            {
                $appointmentFlag = $this->Appointment->wherePatientsId($patientsId)->where('date',$now)->update(['is_done'=>1]);
            }
            $isIvfHistory =  '1';
            $ivfCycleData = null;
            $isTableView = '0';
            $historyData = null;
            $doseData = null;
            $transferDate = null;
            $remark = null;
            $ivfSecondVisitData = null;
            $ohData = null;
            $currentdate =Carbon::now()->format('d-m-y');
            Session::flash('msg','Record has been successfully added.');
            // send SMS
            if(!empty($ivf)){
                $seenBy = getSeenByDoctor($ivf->seen_by);
                $patient = $this->OpdPatients->find($patientsId);
                $plan = null;
                // if(!empty($ivf->plan) && !empty($ivf->description)){
                $planData = ['1'=>'Self','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
                if(isset($request->data['collection']) && in_array('progesterone', $request->data['collection'])) {
                    $msg = 'Advise sp2 start ';
                }
                if($ivfStatus == 1){
                    if(!empty($ivf->plan_management)){
                        $msg = 'Advise IVF ';
                        $planValue = json_decode($ivf->plan_management);
                        if(!empty($planValue) && !empty($planValue->plan)){
                            $plan = $planData[$planValue->plan];
                        }
                        $msg .= $plan;
                    }else{
                        $plan = $planData[$ivf->plan];
                        if($request->visit == 4 && !empty($data['transfer']['result_type']) && $data['transfer']['result_type']){
                            $msg = 'Result '.$data['transfer']['result_type'] .' ';
                        }
                        $msg .= $plan;
                    }
                }
                // }
                if(!$request->ivf_visit_id){
                    $followDate = !empty($followDate) ? date('d M Y',strtotime($followDate)) : null;
                    $triggerDate = !empty($request['data']['trigger_date'])  ? date('d M Y',strtotime($request['data']['trigger_date'])) : null;
                    if($triggerDate){
                        $followDate = $triggerDate;
                        $nowDate = \Carbon\Carbon::parse($triggerDate)->format('Y-m-d');
                        $hcgTime = !empty($data['trigger']['hcg']['time']) ? Carbon::parse($data['trigger']['hcg']['time']) : null;
                        $decapeptylTime = !empty($data['trigger']['decapeptyl']['time']) ? Carbon::parse($data['trigger']['decapeptyl']['time']) : null;
                        $nowTime = \Carbon\Carbon::parse(!empty($hcgTime) ? $hcgTime : (!empty($decapeptylTime) ? $decapeptylTime : null))->format('H:i:s');
                        $pickUpDateTime = \Carbon\Carbon::parse($nowDate.' '.$nowTime)->addHours(35)->format('Y-m-d H:i:s');
                        
                        $pickUpDate = Carbon::parse($pickUpDateTime)->format('Y-m-d');
                        $categoryPatientData['patients_id'] = $patientsId;
                        $categoryPatientData['date'] = $pickUpDateTime;
                        $categoryPatientData['reminder_date'] = Carbon::parse($pickUpDate)->subDays(1)->format('Y-m-d');
                        $categoryPatientData['message'] = "Coming for PickUp";
                        $categoryPatientData['category_id'] = !empty($request->category) ? $request->category : 2;
                        $nextAppontment = $this->storeCategoryNotification($categoryPatientData);
                    }
                    if(!empty($request->data['progesterone']['type']))
                    {
                        // $day = $request->data['progesterone']['type'] == 'day_3' ? '3' : '5';
                        $categoryPatientData['patients_id'] = $patientsId;
                        $categoryPatientData['date'] = Carbon::parse($followDate)->format('Y-m-d H:i:s');
                        $categoryPatientData['reminder_date'] = Carbon::parse($followDate)->subDays(1)->format('Y-m-d');
                        $categoryPatientData['message'] = "Coming for Transfer";
                        $categoryPatientData['category_id'] = !empty($request->category) ? $request->category : 2;
                        $nextAppontment = $this->storeCategoryNotification($categoryPatientData);
                    }
                    
                    if($ivfStatus == 1){
                        $this->SmsManager::sendReferenceDoctor($msg,$seenBy->name,$followDate,$patientsId);
                    }
                }
            }
            // end send sms
            if($request->isprint){
                if($gynecStatus == 1){
                    $ivf->c_o = $ivf->co;
                    $ivf->h_o = $ivf->ho;
                }
                if(!empty($request->visit)){
                    $historyData = json_decode($ivf->description);
                    $isIvfHistory = '2';
                    $isTableView = '1';
                    $ivfCycleData = $this->IvfHistory->wherePatientsId($patientsId)->whereCycleNo($request->cycle_no)->wherePlan(!empty($data['plan']) ? $data['plan'] : $request['plan_type'])->get();
                    $ivfSecondVisit = $this->IvfHistory->where('patients_id',$patientsId)->where('plan',!empty($data['plan']) ? $data['plan'] : $request['plan_type'])->where('cycle_no',$request->cycle_no)->where('visit',2)->first();
                        $ivfSecondVisitData = json_decode($ivfSecondVisit->description);
                        $ivfFirst = $this->IVF->wherePatientsId($patientsId)->orderBy('id','DESC')->first();
                        $lmpDate = null;
                        $uterusData = null;
                        if($ivfFirst){
                            $lmpDate = $ivfFirst->lmp_date;
                            $oeData = json_decode($ivfFirst->o_e);
                            $ohData = json_decode($ivfFirst->o_h);
                            if(!empty($oeData->uterus->details)){
                                $uterusData = $oeData->uterus->details;
                            }

                        }
                    // dd($patientsId);
                    
                    $doseData = $this->Dose->pluck('name','name');
                }
                if($request->isprint == 2){
                    $day = $request->data['progesterone']['type'] == 'day_3' ? '3' : '5';
                    $transferDate = Carbon::parse(!empty($request->data['progesterone_date']) ? $request->data['progesterone_date'] : $lastAppointmentData->date)->addDays($day)->format('d-m-Y');
                    if($request->progesterone_status == 'yes'){
                        $transferDate = Carbon::parse($fDate)->format('d-m-Y');
                    }
                    $isIvfHistory = '3';
                    $isTableView = '0';
                    $remark = $request->remark;
                }
                if($request->isprint == 3){
                    $isTableView = '0';
                    $isIvfHistory = '4';
                }
                $investigationReport = $this->allInvestigationReport();
                return response()->json([
                    'status' => 1,
                    'id' => $ivfId,
                    'data' => View::make('admin.ivf.preview', compact('investigationReport','ivf','ivfCycleData', 'historyData', 'isIvfHistory','isTableView','doseData','remark','transferDate','currentdate','lastAppointmentData','ivfSecondVisitData','ohData'))->render()
                ]);
            }
            if($request->is_ivf_report_print){
                return response()->json([
                    'status' => 1,
                    'id' => $ivfId,
                    'ivf_report_id' => encrypt($ivfReport->id),
                    'data' => View::make('admin.ivf.ivf_report_print', compact('ivfReport'))->render()
                ]);
            }
            if($request->is_ivf_transfer_print) {
                return response()->json([
                    'status' => 3,
                    'id' => $ivfId,
                    'ivf_transfer_report_id' => encrypt($transferReport->id),
                    'data' => View::make('admin.ivf.transfer_report', compact('transferReport'))->render()
                ]);
            }
            if($request->ajax()){
                return ['status'=>'true'];
            }
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return ['status'=>'false'];
        }
    }

    /**
     * generate new cycle to previews visit wise and IF appointment date match to current date , open new cycle otherwise not open the new cycle
     * In which plan will be open the cycle it will be decided from previous visit of plan parameters
     * Skip cycle color is the red and current running cycle color is green
     */
   
    public function ivfHistory(Request $request,$patientsId){
        try{
            $id = decrypt($patientsId);
            $lastCycleId = null;
            $visit = 2;
            $isCycle = false;
            $isNewCycle = true;
            $lastCycleNo= null;
            $ivfReport = false;
            $ivfHistoryData = collect($this->IvfHistory->wherePatientsId($id)->get());
            // dd($ivfHistoryData);
            if(count($ivfHistoryData) == 0){
                $isCycle = true;
                $isNewCycle = false;
            }
            $ivfHistory = $ivfHistoryData->last();
            if($ivfHistory){
                $visit = $ivfHistory->visit + 1;
            }
            $ivfSecondVisitData = $ivfHistoryData->first();
            if($ivfSecondVisitData){
                $ivfSecondVisitData = json_decode($ivfSecondVisitData->description);
            }
            $ivf = $this->IVF->wherePatientsId($id)->first();
            $planManagement = json_decode($ivf->plan_management);
            $planTransfer = !empty($planManagement->plan) ? $planManagement->plan : 1;
            $lastPlan = $planTransfer;
            if($ivfHistory){
                $planTransfer = $ivfHistory->plan;
                $lastPlan = $planTransfer;
                $lastCycleId = $ivfHistory->id;
                $lastIvfHistory = json_decode($ivfHistory->description);
                $lastCycleNo = $ivfHistory->cycle_no;
                if((!isset($lastIvfHistory->plan) || (isset($lastIvfHistory->plan) && (empty($lastIvfHistory->plan) || $lastIvfHistory->plan == null || $lastIvfHistory->plan == ''))) && $ivfHistory->plan == 1)
                {
                    $ivfReport = $this->IvfPlanReport->wherePlanAndPatientsIdAndCycleNo($ivfHistory->plan, $id, $ivfHistory->cycle_no)->first();
                    if($ivfReport){
                        $lastIvfHistory->plan = 2;
                        $ivfHistory->description = json_encode($lastIvfHistory);
                        $ivfHistory->cycle_status = 2;
                        $ivfHistory->save();
                        $ivfReport = true;
                        $planTransfer = 2;
                    }
                }

                if($ivfHistory->cycle_status == 2){
                    $isCycle = true;
                    $planTransfer = (int)!empty($lastIvfHistory->plan) ? $lastIvfHistory->plan : null;
                    $lastPlan = $planTransfer;
                    if(!$planTransfer){
                        $lastPlan = $ivfHistory->plan;
                    }
                }
                $ivfHistory = $lastIvfHistory;
            }
            $pickupData = $ivfHistoryData->where('cycle_status',1)->where('plan',1)->all();
            $fetData = $ivfHistoryData->where('cycle_status',1)->where('plan',2)->all();
            $fetOdData = $ivfHistoryData->where('cycle_status',1)->where('plan',3)->all();
            $fetEdData = $ivfHistoryData->where('cycle_status',1)->where('plan',4)->all();
            $planType = $ivfHistoryData->pluck('plan')->all();
            $pickupCycle = array_unique($ivfHistoryData->where('plan',1)->pluck('cycle_no','id')->toArray());
            if(count(array_unique($ivfHistoryData->where('plan',2)->pluck('cycle_no','id')->toArray())) > 1){
                $fetCycle = array_unique($ivfHistoryData->where('plan',2)->pluck('cycle_no','id')->toArray());
            }else{
                $fetCycle = array_unique($ivfHistoryData->where('plan',2)->sortByDesc('id')->pluck('cycle_no','id')->toArray());
            }
            $fetOdCycle = array_unique($ivfHistoryData->where('plan',3)->pluck('cycle_no','id')->toArray());
            $fetEdCycle = array_unique($ivfHistoryData->where('plan',4)->pluck('cycle_no','id')->toArray());
            $pickupCycleNo = last($pickupCycle);
            $fetCycleNo = last($fetCycle);
            $fetOdCycleNo = last($fetOdCycle);
            $fetEdCycleNo = last($fetEdCycle);
            $lastAppointment = $this->Appointment->where('patients_id',$id)->orderBy('id','DESC')->first();
            $currentDate = Carbon::now()->format('Y-m-d');
            $newCycle = false;
            switch ($planTransfer) {
                case 1:
                    if($isCycle){
                        $key = $lastCycleId;
                        $value = $pickupCycleNo;
                        if($lastAppointment->date <= $currentDate){
                            $newCycle = true;
                            $value = $pickupCycleNo + 1;
                        }
                        if(!$pickupCycleNo){
                            $value = 1;
                        }
                        $lastCycleNo = $value;
                        if($newCycle || !$isNewCycle){
                            $pickupCycle[array_key_exists($key,$pickupCycle) ? $key.'_' : $key] = $value;
                        }
                    }
                    break;
                case 2:
                    if($isCycle){
                        $key = $lastCycleId;
                        $value = $fetCycleNo;
                        if($lastAppointment->date <= $currentDate || ($ivfReport && $lastAppointment->date <= $currentDate)){
                            $newCycle = true;
                            $value = $fetCycleNo + 1;
                        }
                        if(!$fetCycleNo){
                            $value = 1;
                        }
                        $lastCycleNo = $value;
                        if($newCycle || !$isNewCycle){
                            $fetCycle[array_key_exists($key,$fetCycle) ? $key.'_' : $key] = $value;
                        }

                    }
                    break;
                case 3:
                    if($isCycle){
                        $key = $lastCycleId;
                        $value = $fetOdCycleNo;
                        if($lastAppointment->date <= $currentDate){
                            $newCycle = true;
                            $value = $fetOdCycleNo + 1;
                        }
                        if(!$fetOdCycleNo){
                            $value = 1;
                        }
                        $lastCycleNo = $value;
                        if($newCycle || !$isNewCycle){
                            $fetOdCycle[array_key_exists($key,$fetOdCycle) ? $key.'_' : $key] = $value;
                        }
                    }
                    break;
                case 4:
                    if($isCycle){
                        $key = $lastCycleId;
                        $value = $fetEdCycleNo;
                        if($lastAppointment->date <= $currentDate){
                            $newCycle = true;
                            $value = $fetEdCycleNo + 1;
                        }
                        if(!$fetEdCycleNo){
                            $value = 1;
                        }
                        $lastCycleNo = $value;
                        if($newCycle || !$isNewCycle){
                            $fetEdCycle[array_key_exists($key,$fetEdCycle) ? $key.'_' : $key] = $value;
                        }
                    }
                    break;
            }
            if(!empty($lastIvfHistory) && !empty($lastIvfHistory->transfer_type) && $lastIvfHistory->transfer_type == 'old'){
                if(!empty($pickupCycle) && count($pickupCycle) > 1){
                    array_pop($pickupCycle);
                }
                if(!empty($fetCycle) && count($fetCycle) > 1){
                    array_pop($fetCycle);
                }
                if(!empty($fetOdCycle) && count($fetOdCycle) > 1){
                    array_pop($fetOdCycle);
                }
                if(!empty($fetEdCycle) && count($fetEdCycle) > 1){
                    array_pop($fetEdCycle);
                }
                $lastCycleNo = $lastCycleNo == 1 ? $lastCycleNo : $lastCycleNo - 1;
            }
            $dataForSkipPlan = collect($this->IvfHistory
                                ->wherePatientsId($id)
                                ->where('description->skip_cycle', 'yes')
                                ->get());
            $dataForSkipPlans = $dataForSkipPlan->mapWithKeys(function($value){
                return [$value->plan.'_'.$value->cycle_no  => $value->plan];
            })->all();
            $dataForSkipReason = $dataForSkipPlan->mapWithKeys(function($value){
                $skipDescription = json_decode($value->description);
                return [$value->plan.'_'.$value->cycle_no  => $skipDescription->skip_reason];
            })->all();
            // dd($dataForSkipPlan);
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $doseData = $this->Dose->pluck('name','name');
            $isIvf = 'no';
            $data['isIvf'] = $isIvf;
            $data['ivf'] = $ivf;
            $data['leftOvaryData'] = $leftOvaryData;
            $data['rightOvaryData'] = $rightOvaryData;
            $data['medicines'] = $medicines;
            $data['doseData'] = $doseData;
            $data['visit'] = $visit;
            $data['ivfHistoryData'] = $ivfHistoryData;
            $data['ivfSecondVisitData'] = $ivfSecondVisitData;
            $data['ivfHistory'] = $ivfHistory;
            $data['pickupData'] = $pickupData;
            $data['fetData'] = $fetData;
            $data['fetOdData'] = $fetOdData;
            $data['planType'] = $planType;
            $data['planTransfer'] = $planTransfer;
            $data['lastCycleNo'] = $lastCycleNo;
            $data['isCycle'] = $isCycle;
            $data['fetCycle'] = $fetCycle;
            $data['fetOdCycle'] = $fetOdCycle;
            $data['pickupCycle'] = $pickupCycle;
            $data['fetEdCycle'] = $fetEdCycle;
            $data['pickupCycleNo'] = $pickupCycleNo;
            $data['lastPlan'] = $lastPlan;
            $data['fetCycleNo'] = $fetCycleNo;
            $data['fetOdCycleNo'] = $fetOdCycleNo;
            $data['fetEdCycleNo'] = $fetEdCycleNo;
            $data['patientsId'] = $patientsId;
            $data['dataForSkipPlans'] = $dataForSkipPlans;
            $data['dataForSkipReason'] = $dataForSkipReason;
            if($request->ajax()){
                $data['history'] = View::make('admin.ivf.edit',$data)->render();
                return $data;
            }
            return view('admin.ivf.new_history',$data)->with('ivf-msg','Your Record successfully added.');
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return redirect('ivf');
        }
    }

    // fetch the IVF report of patient
    public function ivfReport($planId,$patientId) {
        $planId = decrypt($planId);
        $patientId = decrypt($patientId);
        $ivfReport = $this->IVFReport->where('patients_id',$patientId)->first();
        return view('admin.ivf.ivfreport',compact('planId','patientId','ivfReport'));
    }

    // Update the IVF report data
    public function updateIvfReportData(Request $request) {
        // dd($request->all());
        try {
            $ivfReportId = decrypt($request->update_ivf_report_id);
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'something went wrong'
            ];
        }
        try{
            // $ivfReport = $this->IVFReport;
            $ivfReport = $this->IVFReport->where('id', $ivfReportId)->first();
            // if(!empty($ivfReportData)){
            //     $ivfReport = $ivfReportData;
            // }
            // $ivfReport->patients_id = $patientId;
            // $ivfReport->plan = $planId;
            $ivfReport->date = !empty($request->report_date) ? \Carbon\Carbon::parse($request->report_date)->format('Y-m-d') : null;
            $ivfReport->reason = $request->reason;
            $ivfReport->volume = json_encode($request->volume);
            $ivfReport->sperm_count = json_encode($request->sperm);
            $ivfReport->total_motility = json_encode($request->motility);
            $ivfReport->actively = json_encode($request->actively);
            $ivfReport->sluggishly = json_encode($request->sluggishly);
            $ivfReport->non_motile = json_encode($request->motile);
            $ivfReport->morphology = json_encode($request->morphology);
            $ivfReport->pus_cells = json_encode($request->cells);
            $ivfReport->created_by = Auth::user()->id;
            $ivfReport->save();
            if($request->is_print){
                return response()->json([
                    'status' => 1,
                    'data' => View::make('admin.ivf.ivf_report_print', compact('ivfReport'))->render()
                ]);
            }
            return response()->json([
                'status' => 2,
                'data' => null
            ]);
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return [
                'status'=>'false',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
    * Get Dose list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function doseData($pData){
        $data = $this->Dose->pluck('name','name')->toArray();
        $diffrent = array_diff($pData,$data);
        foreach($diffrent as $row){
            $dData = new $this->Dose;
            $dData->name = $row;
            $dData->save();
        }
        return true;
    }

    // this function is open the new form visit and plan wise this function is most importtan for open the form
    public function getIvfCycleData(Request $request,$historyId,$patientsId,$pStatus,$cNumber){
        try{
            $checkStatus = null;
            $lastVisitValue = null;
            $lastCycleValue = null;
            $id = decrypt($patientsId);
            $historyId = decrypt($historyId);
            $pStatus = decrypt($pStatus);
            $cycleData = $this->IvfHistory->whereId($historyId)->first();
            $isForm = true;
            $visit = 2;
            $cycleNumber = 1;
            $lastIvfHistory = null;
            $ivf = $this->IVF->wherePatientsId($id)->orderBy('id','DESC')->first();
            $lmpDate = null;
            $uterusData = null;
            if($ivf){
                $lmpDate = $ivf->lmp_date;
                $oeData = json_decode($ivf->o_e);
                $ohData = json_decode($ivf->o_h);
                if(!empty($oeData->uterus->details)){
                    $uterusData = $oeData->uterus->details;
                }

            }
            $planManagement = json_decode($ivf->plan_management);
            $plan = !empty($planManagement->plan) ? $planManagement->plan : null;
            $skipPlan = $pStatus;
            $cNumber = decrypt($cNumber);
            if($cycleData){
                // $isForm = false;
                $cNo = $cycleData->cycle_status == 2 ?  $cycleData->cycle_no + 1 : $cycleData->cycle_no;
            }
            $ivfReport = null;
            // $ivfReport = $this->IVFReport->where('patients_id',$patientsId)->first();
            $ivfSecondHistory = $this->IvfHistory->wherePatientsId($id)->wherePlan($pStatus)->whereCycleNo($cNumber)->whereVisit(2)->first();
            $cycle = collect($this->IvfHistory->wherePatientsId($id)->wherePlan($pStatus)->whereCycleNo($cNumber)->get());
            $ivfSecondVisitData = $cycle->first();
            // dd($cycle);
            $skipPlan = $ivfSecondVisitData ? $ivfSecondVisitData->plan :  $pStatus;
            $ivfHistory = $cycle->last();
            $lastIvfHistoryData = $this->IvfHistory->wherePatientsId($id)->whereCycleNo($cNumber)->wherePlan($plan)->orderBy('id','DESC')->first();
            if($lastIvfHistoryData){
                $lastCycleValue = $lastIvfHistoryData->cycle_no;
                $lastVisitValue = $lastIvfHistoryData->visit;
            }
            $sDay = 0;
            $pDate = null;
            $ivfHistoryData = $this->IvfHistory->wherePatientsId($id)->orderBy('id','DESC')->first();
            if($ivfHistoryData){
                $ivfReport = $this->IvfPlanReport->wherePlanAndPatientsIdAndCycleNo($pStatus, $id, $cNumber)->first();
            }
            if($cycleData && $cycleData->cycle_status == 1){
                if($ivfHistory && $ivfHistory->cycle_status == 1){
                    $isForm = true;
                    // $plan = $ivfHistory->plan;
                    $lastIvfHistory = json_decode($cycleData->description);
                    $protocolIvfHistoryData = $this->IvfHistory->wherePatientsId($id)->where('description->protocol',"NOT LIKE",'%[]%')->orderBy('id','DESC')->first();
                    if($protocolIvfHistoryData){
                        $lastProtocolData = json_decode($protocolIvfHistoryData->description);
                        $protocolTable = !empty($lastProtocolData->protocol) ? $lastProtocolData->protocol : [];
                        $countProtocolTable = count((array)$protocolTable);
                        if($countProtocolTable > 0){
                            if(isset($protocolData[$countProtocolTable]))
                            {
                                $protocolData = (array)$lastProtocolData->protocol;
                                $sDay = $protocolData[$countProtocolTable]->s_day;
                                $pDate = $protocolData[$countProtocolTable]->date;
                            }
                            
                        }
                    }
                    $skipPlan = (int)!empty($lastIvfHistory->plan) ? $lastIvfHistory->plan : null;
                    $visit = $ivfHistory->visit + 1;
                    if($pStatus == $cycleData->plan){
                        $skipPlan = $pStatus;
                    }
                }else{
                    if($ivfHistory){
                        $plan = $ivfHistory->plan;
                    }
                }
            }else{
                if($cycleData && $cycleData->cycle_status == 2){
                    $cycleNumber = $cycleData->cycle_no + 1;
                }
                $checkStatus = $this->IvfHistory->wherePatientsId($id)->wherePlan($pStatus)->orderBy('id','DESC')->first();
                if(!$checkStatus){
                    $cycleNumber = 1;
                }
            }
            $lastFollowDate = $this->Appointment->wherePatientsId($id)->orderBy('id','DESC')->value('date');
            if($ivfHistoryData){
                $lasttData = json_decode($ivfHistoryData->description);
                if($ivfHistoryData->cycle_no != $cNumber || !empty($lasttData->skip_cycle) && $lasttData->skip_cycle == 'yes'){
                    $isForm = false;
                }
                $lastFollowDate = !empty($lasttData->follow_up) ? $lasttData->follow_up : null;
                $cData = $cNumber;
                if($ivfHistory && $ivfHistory->cycle_no != $cNumber){
                    $cData = $cNumber;
                }
                if(empty($lasttData->transfer_type) || (!empty($lasttData->transfer_type) && $lasttData->transfer_type != 'old')){
                    if($checkStatus){
                        $cData = !$checkStatus ? 1 : $checkStatus->cycle_no + 1;
                    }else{
                        $cData = !$checkStatus ? 1 : $ivfHistoryData->cycle_no + 1;
                    }
                }
                if($ivfHistoryData->cycle_status == 2 && !empty($lasttData->skip_cycle) && $lasttData->skip_cycle == 'yes' && $lasttData->plan == $pStatus && ($cData == $cNumber)){
                    $isForm = true;
                }
                $checkLastPlan = !empty($lasttData->plan) ? $lasttData->plan : null;

                if($ivfHistoryData->cycle_status == 2 && $checkLastPlan == $pStatus && ($cData == $cNumber)){

                    $isForm = true;
                }

            }
            // $checkIsTransfer = $this->IvfHistory->wherePatientsId($id)->where('description->is_transfer','yes')->first();
            $checkIsTransfer = $this->IvfHistory->wherePatientsId($id)->orderBy('id','DESC')->first();

            if($checkIsTransfer){
                $checkIsTransferData = json_decode($checkIsTransfer->description);
                $checkIsTransfer = !empty($checkIsTransferData->is_transfer) && $checkIsTransferData->is_transfer == 'yes' ? 1 : 0;

                if($checkIsTransfer){
                    $checkIsTransfer = !empty($checkIsTransferData->plan) ? 0 : 1;
                }
                if(isset($checkIsTransferData->is_transfer) && $checkIsTransferData->is_transfer == 'yes' && $checkIsTransferData->is_upt == 'yes' && ($cData != $cNumber))
                {
                    // dd($pStatus);
                    $checkIsTransfer = !empty($checkIsTransferData->plan) ? 1 : 0;
                }

            }
            $complaints = $this->Complaint->pluck('name','name');
            $lastAppointment = $this->Appointment->wherePatientsId($id)->orderBy('id','DESC')->first();
            $isTransfer = $checkIsTransfer ? true : false;
            $medicines = $this->Medicine->pluck('name','name');
            $doseData = $this->Dose->pluck('name','name');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $ivfPlanReportData = $this->IvfPlanReport->wherePatientsId($id)->orderBy('id','DESC')->first();
            $pickupDate = null;
            $sProtocol = null;
            $totalocc = null;
            $mi = null;
            $gv = null;
            $mii = null;
            $quality = null;
            $sperm = null;
            $nextVisitValue = null;
            $cycleStatus = null;
            if($ivfPlanReportData){
                $descriptiondata = json_decode($ivfPlanReportData->description);
                $pickupDate = !empty($descriptiondata->ovum->date) ? $descriptiondata->ovum->date : null;
                $totalocc = !empty($descriptiondata->ovum->totalocc) ? $descriptiondata->ovum->totalocc : null;
                $mi = !empty($descriptiondata->ovum->mi) ? $descriptiondata->ovum->mi : null;
                $mii = !empty($descriptiondata->ovum->mii) ? $descriptiondata->ovum->mii : null;
                $gv = !empty($descriptiondata->ovum->gv) ? $descriptiondata->ovum->gv : null;
                $sperm = !empty($descriptiondata->ovum->sperm) ? $descriptiondata->ovum->sperm : null;
                $quality = !empty($descriptiondata->ovum->quality) ? $descriptiondata->ovum->quality : null;
                $sProtocol = !empty($descriptiondata->simulation->protocol) ? $descriptiondata->simulation->protocol : null;
            }


            $ivfHistory = $this->IvfHistory->where('patients_id',$id)->where('cycle_no',$cycleNumber)->where('plan',$plan)->orderBy('id','asc')->get();
            $triggerHistory = $this->IvfHistory->where('patients_id',$id)->where('cycle_no',$cNumber)->where('plan',$pStatus)->whereNotNull('trigger_date')->orderBy('id','DESC')->first();
            // dd($plan);
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $historySemenFreezing = $this->IvfHistory
                                ->where('patients_id',$id)
                                ->where('cycle_no',$cNumber)
                                ->where('plan',$pStatus)
                                ->where('description->collected->frozen->type', 'yes')
                                ->first();
            $historyEmbroyReady = $this->IvfHistory
                                ->where('patients_id',$id)
                                ->where('cycle_no',$cNumber)
                                ->where('plan',$pStatus)
                                ->where('description->collected->report->embroy->type', 'yes')
                                ->first();
            $getIvfByPlanAndCycle = $this->IvfHistory->where('patients_id',$id)->where('cycle_no',$cNumber)->where('plan',$pStatus)->orderBy('id','DESC')->first();
            
            $data = [];
            $data['pickupDate'] = $pickupDate;
            $data['sProtocol'] = $sProtocol;
            $data['totalocc'] = $totalocc;
            $data['mi'] = $mi;
            $data['mii'] = $mii;
            $data['gv'] = $gv;
            $data['quality'] = $quality;
            $data['sperm'] = $sperm;
            $data['visit'] = $visit;
            $data['patient_id'] =$id;
            $data['cycleNumber'] = $cNumber;
            $data['cycleData'] = $cycleData ? $cycleData : [];
            $data['medicines'] = $medicines;
            $data['ivfSecondVisitData'] = !empty($ivfSecondHistory) ? json_decode($ivfSecondHistory->description) : null;
            $data['isForm'] = $isForm;
            $data['visit'] = $visit;
            $data['plan'] = $plan;
            $data['doseData'] = $doseData;
            $data['patientsId'] = $patientsId;
            $data['cycle'] = !empty($cycle) ? $cycle : [];
            $data['pStatus'] = $pStatus;
            $data['skipPlan'] = $skipPlan;
            $data['isTransfer'] = $isTransfer;
            $data['leftOvaryData'] = $leftOvaryData;
            $data['rightOvaryData'] = $rightOvaryData;
            $data['lastAppointment'] = $lastAppointment;
            $data['lastFollowDate'] = $lastFollowDate;
            $data['LMPDate'] = $lmpDate;
            $data['lastVisitValue'] = $lastVisitValue;
            $data['lastCycleValue'] = $lastCycleValue;
            $data['sDay'] = $sDay;
            $data['pDate'] = $pDate;
            $data['ivfReport'] = $ivfReport;
            $data['complaints'] = $complaints;
            $data['uterusData'] = $uterusData;
            $data['ivfHistory'] = $ivfHistory;
            $data['triggerHistory'] = $triggerHistory;
            $data['referenceDoctor'] = $referenceDoctor;
            $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $data['investigationReport'] = $this->allInvestigationReport();
            $data['ohData'] = $ohData;
            $data['historySemenFreezing'] = !empty($historySemenFreezing) ? 'Yes' : 'No';
            $data['historyEmbroyReady'] = !empty($historyEmbroyReady) ? 'Yes' : 'No';
            $data['nextVisitValue'] = !empty($getIvfByPlanAndCycle) ? $getIvfByPlanAndCycle->visit : 0 ;
            $data['cycleStatus'] = !empty($getIvfByPlanAndCycle) ? $getIvfByPlanAndCycle->cycle_status : 0 ;
            return view('admin.ivf.cycle_data',$data);
        }catch(Exception $e){
            log::debug($e);
            return back();
        }
    }
    
    /**
    * Get Dose list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function payment_gujarati(Request $request,$patientsId,$lang){
        // $patientsId = decrypt($patientsId);
        App::setlocale($lang);
            $patients_Id = decrypt($patientsId);
          $patients = opd::where(['id' => $patients_Id])->first();
          $ivfPaymentHistory = $this->IvfPayment->wherePatientsId($patients_Id)->orderBy('id','DESC')->first();
        return view('admin.ivf.payments',['patientsId' => $patientsId,'patients' => $patients,'ivfPaymentHistory' => $ivfPaymentHistory]);
    }

    public function payment(Request $request,$patientsId){
        $patients_Id = decrypt($patientsId);
        $patients = opd::where(['id' => $patients_Id])->first();
        $ivfPaymentHistory = $this->IvfPayment->wherePatientsId($patients_Id)->orderBy('id','DESC')->first();
        $opdCollection = $this->IndoorDeposit->wherePatientId($patients_Id)->whereChargeType(2)->orderBy('id','DESC')->first();
        $is_deposite = (!empty($opdCollection)) ? 1 : 0;
        return view('admin.ivf.payments',['patientsId' => $patientsId,'patients' => $patients,'ivfPaymentHistory' => $ivfPaymentHistory, 'is_deposite' => $is_deposite]);
    }

    public function ivfPayment($patientsId){
        $patientsId = decrypt($patientsId);
        $ivfDeposit = $this->IndoorDeposit->wherePatientId($patientsId)->whereChargeType(2)->orderBy('id','DESC')->first();
        $ivfPayment = $this->IvfPayment->wherePatientsId($patientsId)->first();
        return ['deposit'=>$ivfDeposit,'ivfPayment'=>$ivfPayment];
    }

    /**
    * Store IVF payment
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
     
     public function ivfPaymentStoreNewUi(Request $request){
        $patientsId = decrypt($request->patients_id);
        $no_cycle = 1;
        if($request->no_cycle>1) {
            $no_cycle = $request->no_cycle;
        }
        if($request->multiple_cycle)
        {
            $no_cycle = $request->multiple_cycle;
        }
        $ivfPaymentData = $this->IvfPayment->wherePatientsId($patientsId)->whereCycleNo($no_cycle)->first();
    
        $ivfPayment = $this->IvfPayment;
        if($ivfPaymentData){
            $ivfPayment = $ivfPaymentData;
        }

       if ($request->cycle_type == "OD") {
            $ivfPayment->donor_charge = $request->donor_charge;
       }else{
            $ivfPayment->donor_charge = null;
       }

        $ivfPayment->patients_id = $patientsId;
        $ivfPayment->patient_name = $request->p_name;
        $ivfPayment->husband_name = $request->h_name;
        $ivfPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $ivfPayment->consulation = $request->consulation;
        $ivfPayment->sonography_charge = $request->sonography;
        // $ivfPayment->consulation_status = $request->consulation_status;
        $ivfPayment->ivf_lab_charge = $request->ivf_lab_charge;   
        $ivfPayment->HMG = $request->HMG;   
        $ivfPayment->HMG_approx = $request->HMG_approx;   
        $ivfPayment->RFSH = $request->RFSH;     
        $ivfPayment->RFSH_approx = $request->RFSH_approx;     
        $ivfPayment->Gonal_F = $request->Gonal_F;   
        $ivfPayment->GonalF_approx = $request->GonalF_approx;   
        $ivfPayment->language = $request->language;   
        $ivfPayment->embroy_tranfer = $request->embryo_transfer;               
        $ivfPayment->embroy_freezing = $request->embryo_freezing;
        $ivfPayment->embryologist_charge = $request->embryologist_charge;
        $ivfPayment->surgeon_charge =$request->surgeon_charge;
        $ivfPayment->semen_freezing_charge = $request->semen_freezing_charge;
        $ivfPayment->hystrocopy = $request->hystrocopy;
        $ivfPayment->icsi_ivf_charge = $request->icsi_ivf_charge;
        $ivfPayment->medical_medicines = $request->medical_medicines;
        $ivfPayment->medical_medicines_approx = $request->medical_medicines_approx;
        // $ivfPayment->unconscious_charge = $request->unconscious_charge;
        $ivfPayment->anesthescis_doctor = $request->anesthescis_doctor;
        $ivfPayment->blood_report = $request->blood_report;
        $ivfPayment->blood_report_approx = $request->blood_report_approx;
        $ivfPayment->tesa_pesa = $request->tesa_pesa;
        $ivfPayment->ovum_embryopooling_approx = $request->ovum_embryopooling_approx;
        $ivfPayment->ovum_embryopooling = $request->ovum_embryopooling;
        $ivfPayment->hystrocopy_approx = $request->hystrocopy_approx;
        $ivfPayment->created_by = Auth::user()->id;
        $ivfPayment->condition = $request->condition;
        $ivfPayment->payment_type = $request->payment_type;
        $ivfPayment->payment = $request->payment;
        $ivfPayment->package = $request->package; 
        $ivfPayment->extra_charge = $request->extra_charge; 
        $ivfPayment->discount = $request->discount; 
        // $ivfPayment->emdomatrial_report = $request->emdomatrial_report; 
        $ivfPayment->TBPCR = $request->TBPCR; 
        $ivfPayment->PAMP = $request->PAMP; 
        $ivfPayment->ERA = $request->ERA;
        $ivfPayment->total_payment = ($request->package - $request->payment);

        $ivfPayment->remaining_day = $request->remaining_day;
        if ($request->remaining_day == '') {
           $ivfPayment->remaining_date = null;
        }else{
            $today = Carbon::now();
            $next_date = Carbon::parse($today.$request->remaining_day."days")->format('Y-m-d');;
            $ivfPayment->remaining_date = $next_date;
        }

        $ivfPayment->cycle_type = $request->cycle_type;
        $ivfPayment->cycle_no = $no_cycle;
        $ivfPayment->time = $request->time;
        $ivfPayment->remark = $request->remark;
        $ivfPayment->visit = 1;
        $ivfPayment->save();  

        $ivfDepositData = $this->IndoorDeposit->where('patient_id',$patientsId)->whereCycleNo($no_cycle)->first();
        $ivfDeposit = $this->IndoorDeposit;
        if($ivfDepositData){
            $ivfDeposit = $ivfDepositData;
        }
        //Add Indoor deposits
        $ivfDeposit->patient_id = $patientsId;
        $ivfDeposit->admin_id = Auth::user()->id;
        $ivfDeposit->amount = $request->payment;
        $ivfDeposit->total = $request->payment;
        $ivfDeposit->package = $request->package;
        $ivfDeposit->charge_type = 2;
        $ivfDeposit->case_type = 'Credit';
        if ($request->no_cycle == '') {
            $ivfDeposit->cycle_no = 1;
        }
        else{   
            $ivfDeposit->cycle_no = $request->no_cycle;
        } 
        if($request->payment > 0)
        {
            $ivfDeposit->save(); 
        }

        if($request->isprint){
            return response()->json([
                'status' => 1,
                'data' => View::make('admin.ivf.payment_preview_lang', compact('ivfPayment'))->render()
            ]);
        }else{
            return ['status'=>'true'];
        }
    }


    // ivf payment store and also all charges store in ivf_payment table
   /* public function ivfPaymentStore(Request $request){
        $patientsId = decrypt($request->patients_id);
        // $patientsId = $request->patients_id;
        $ivfPaymentData = $this->IvfPayment->wherePatientsId($patientsId)->whereCycleNo($request->no_cycle)->first();
        $ivfPayment = $this->IvfPayment;
        if($ivfPaymentData){
            $ivfPayment = $ivfPaymentData;
        }
         $ivfPayment->patients_id = $patientsId;
        $ivfPayment->patient_name = $request->p_name;
        $ivfPayment->husband_name = $request->h_name;
        $ivfPayment->sonography_charge = $request->sonography;
        $ivfPayment->ivf_lab_charge = $request->ivf_lab_charge;
        $ivfPayment->embroy_tranfer = $request->embryo_transfer;
        $ivfPayment->embroy_freezing = $request->embryo_freezing;
        $ivfPayment->hystrocopy = $request->hystrocopy;
        $ivfPayment->icsi_ivf_charge = $request->icsi_ivf_charge;
        $ivfPayment->medical_medicines = $request->medical_medicines;
        $ivfPayment->unconscious_charge = $request->unconscious_charge;
        $ivfPayment->blood_report = $request->blood_report;
        $ivfPayment->tesa_pesa = $request->tesa_pesa;
        $ivfPayment->hystrocopy = $request->hystrocopy;
        $ivfPayment->created_by = Auth::user()->id;
        $ivfPayment->condition = $request->condition;
        $ivfPayment->payment_type = $request->payment_type;
        $ivfPayment->payment = $request->payment;
        $ivfPayment->package = $request->package;   
        // $ivfDeposit = $this->IndoorDeposit->wherePatientId($patientsId)->whereChargeType(2)->orderBy('id','DESC')->first();
        $ivfPayment->cycle_no = $request->no_cycle;
        $ivfPayment->time = $request->time;
        $ivfPayment->remark = $request->remark;
        $ivfPayment->visit = 1;
        // $ivfPayment->remaining_payment = $request->total_payment;
        // if($ivfDeposit){
        //     $ivfPayment->remaining_payment = abs($ivfDeposit->total - ($ivfPaymentData ? (int)$ivfPaymentData->remaining_payment : $request->total_payment));
        // }
        // $ivfPayment->total_payment = $request->total_payment;
        $ivfPayment->save();
        
            if($request->isprint){
                return response()->json([
                    'status' => 1,
                    'data' => View::make('admin.ivf.payment_preview', compact('ivfPayment'))->render()
                ]);
            }else{
                return ['status'=>'true'];  
            }  
    }
*/

    public function ivfPlanReport($plan, $patientId, $cycleNo) {
        try {
            $plan = decrypt($plan);
            $patientId = decrypt($patientId);
            $cycleNo = decrypt($cycleNo);

        } catch (Exception $e) {
            log::debug($e);
            return back();
        }
        try {
            $ivf = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->whereCycleNo($cycleNo)->get();
            $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
            $ivfReport = $this->IvfPlanReport->wherePlanAndPatientsIdAndCycleNo($plan, $patientId, $cycleNo)->first();
            return view('admin.ivf.ivf_plan_report',compact('patientId','cycleNo','plan', 'ivfReport','lastAppointmentData','ivf'));

        } catch (Exception $e) {
            abort(500);
            log::debug($e);
            return back();
        }
    }
    public function ivfPlanReportStore(Request $request) {
        try {
            $patientId = decrypt($request->patient_id);
            $cycleNo = decrypt($request->cycle_no);
            $plan = decrypt($request->plan);
            $ivfReportPlanId = !empty($request->ivf_report_plan_id) ? decrypt($request->ivf_report_plan_id) : null;
        } catch (Exception $e) {
            log::debug($e);
            return [
                'status' => false,
                'message' => 'something went wrong'
            ];
        }
        try{
            $ivfReport = $this->IvfPlanReport;
            if(!empty($ivfReportPlanId)){
                $ivfReport = $ivfReport->whereId($ivfReportPlanId)->first();
            }
            $ivfReport->patients_id = $patientId;
            $ivfReport->cycle_no = $cycleNo;
            $ivfReport->plan = $plan;
            $ivfReport->created_by = Auth::user()->id;

            $data = $request->data;

            $lastIvfPlanReport = null;
            if ($ivfReportPlanId) {
                $lastIvfPlanReport = json_decode($this->IvfPlanReport->whereId($ivfReportPlanId)->value('description'), true);
            }
            if(!empty($request->data['ovum']['erphoto'])){
                if (isset($lastIvfPlanReport['ovum']['erphoto']) && !empty($lastIvfPlanReport['ovum']['erphoto'])) {
                    $this->removeImage($lastIvfPlanReport['ovum']['erphoto']);
                }
                $imagePath = 'public/upload/ivf/er';
                $picture = $request->data['ovum']['erphoto'];
                $imageName = $this->uploadImage($picture, $imagePath);
                $data['ovum']['erphoto'] = $imagePath.'/'.$imageName;
            }
            $ivfReport->description = json_encode($data);
            $ivfReport->save();


            if($request->isprint){
                return response()->json([
                    'status' => 1,
                    'ivf_plan_report_id' => ($ivfReport != null) ? encrypt($ivfReport->id) : null,
                    'data' => View::make('admin.ivf.ivf_plan_report_print', compact('ivfReport'))->render()
                ]);
            }

        } catch(Exception $e) {
            log::debug($e);
            abort(500);
            // dd($e);
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    public function getIvfReportData(Request $request) {
        try {
            $patientId = decrypt($request->patient_id);
            $cycleNo = decrypt($request->cycle_no);
            $plan = decrypt($request->plan);
            $visit = decrypt($request->visit);

        } catch (Exception $e) {
            abort(500);
            log::debug($e);
            return [
                'status' => false,
                'message' => 'something went wrong'
            ];
        }

        try{
            $ivfReport = $this->IVFReport->wherePatientsIdAndCycleNoAndPlanAndVisit($patientId, $cycleNo,  $plan, $visit)->first();

            return response()->json([
                'status' => 1,
                'ivf_report_id' => !empty($ivfReport) ? encrypt($ivfReport->id) : null,
                'data' => $ivfReport
            ]);


        } catch(Exception $e) {
            // dd($e);
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // get all visit data
    public function getVisitData($ivfHistoryId){
        try{
            $hystroscopyImagesData = [];
            $laproscopyImagesData = [];
            $bloodReportImagesData = [];
            $usgReportImagesData = [];
            $hsaReportImagesData = [];
            $ivfHistoryId = decrypt($ivfHistoryId);
            $data['ivf'] = $this->IvfHistory->find($ivfHistoryId);
            $lastIvf = $this->IvfHistory->where('id','<',$ivfHistoryId)->where('cycle_no',$data['ivf']['cycle_no'])->where('plan',$data['ivf']['plan'])->where('patients_id',$data['ivf']['patients_id'])->orderBy('id','DESC')->first();
            $data['lastIvfHistory'] = null;
            if($lastIvf){
                $data['lastIvfHistory'] = json_decode($lastIvf->description);
            }
            $ivfReport = $this->IVFReport->where('patients_id',$data['ivf']['patients_id'])
                                         ->where('cycle_no',$data['ivf']['cycle_no'])
                                         ->where('plan',$data['ivf']['plan'])
                                         ->where('visit',$data['ivf']['visit'])
                                         ->first();
            $medicines = $this->Medicine->pluck('name','name');
            $doseData = $this->Dose->pluck('name','name');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $historyMedicineKey = [];
            if(!empty($historyTreatment)){
                $historyMedicineKey = (array)$historyTreatment;
                $historyMedicineKey = array_column($historyMedicineKey,'medicine');
                if(!empty($historyMedicineKey)){
                    $historyMedicineKey = array_combine($historyMedicineKey,$historyMedicineKey);
                }
            }
            if($data['ivf'])
            {
                $description = !empty($data['ivf']) ? json_decode($data['ivf']['description']) : null;
                $investigation = json_decode($data['ivf']['investigation']);
                $hystroscopyImages  = !empty($investigation->hystroscopy->images) ? $investigation->hystroscopy->images : null;
                if($hystroscopyImages){
                    foreach($hystroscopyImages as $key=>$row){
                        $hystroscopyImagesData[$key]['id'] = $key;
                        $hystroscopyImagesData[$key]['src'] = url($row);
                    }
                }
                $laproscopyImages = !empty($investigation->laproscopy->images) ? $investigation->laproscopy->images : null;
                if($laproscopyImages){
                    foreach($laproscopyImages as $key=>$row){
                        $laproscopyImagesData[$key]['id'] = $key;
                        $laproscopyImagesData[$key]['src'] = url($row);
                    }
                }
                $bloodReportImages = !empty($description) && !empty($description->blood_report->image) ? $description->blood_report->image : null;
                if($bloodReportImages){
                    foreach($bloodReportImages as $key=>$row){
                        $bloodReportImagesData[$key]['id'] = $key;
                        $bloodReportImagesData[$key]['src'] = url($row);
                    }
                }
                $usgReportImages = !empty($description) && !empty($description->usg->images) ? $description->usg->images : null;
                if($usgReportImages){
                    foreach($usgReportImages as $key=>$row){
                        $usgReportImagesData[$key]['id'] = $key;
                        $usgReportImagesData[$key]['src'] = url($row);
                    }
                }
                $hsaReportImages = !empty($description) && !empty($description->hsa_report->images) ? $description->hsa_report->images : null;
                if($hsaReportImages){
                    foreach($hsaReportImages as $key=>$row){
                        $hsaReportImagesData[$key]['id'] = $key;
                        $hsaReportImagesData[$key]['src'] = url($row);
                    }
                }
            }
            $firstVisitIvf = $this->IVF->wherePatientsId($data['ivf']['patients_id'])->orderBy('id','DESC')->first();
            $lmpDate = $firstVisitIvf->lmp_date;
            $complaints = $this->Complaint->pluck('name','name');
            $semenFreezing = $this->IvfHistory
                                ->wherePatientsId($data['ivf']['patients_id'])
                                ->where('description->collected->frozen->type', 'yes')
                                ->first();
            $embroyReady = $this->IvfHistory
                                ->wherePatientsId($data['ivf']['patients_id'])
                                ->where('description->collected->report->embroy->type', 'yes')
                                ->first();
            $data['isTransfer'] = false;
            $data['doseData'] = $doseData;
            $data['complaints'] = $complaints;
            $data['leftOvaryData'] = $leftOvaryData;
            $data['rightOvaryData'] = $rightOvaryData;
            $data['medicines'] = $medicines;
            $data['id'] = $data['ivf']['id'];
            $data['ivfReport'] = $ivfReport;
            $data['lmpDate'] = $lmpDate;
            $data['hystroscopyImagesData'] = json_encode($hystroscopyImagesData,true);
            $data['laproscopyImagesData'] = json_encode($laproscopyImagesData,true);
            $data['bloodReportImagesData'] = json_encode($bloodReportImagesData,true);
            $data['usgReportImagesData'] = json_encode($usgReportImagesData,true);
            $data['usgReportImagesData'] = json_encode($usgReportImagesData,true);
            $data['hsaReportImagesData'] = json_encode($hsaReportImagesData,true);
            $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $data['semenFreezing'] = !empty($semenFreezing) ? 1 : 0;
            $data['embroyReady'] = !empty($embroyReady) ? 1 : 0;
            $data['visitData'] = View::make('admin.ivf.visit_form',$data)->render();
            return $data;
            // return view('admin.ivf.edit1', compact('data'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return ['status'=>false];
        }
    }

    public function getIvfTransferReportData(Request $request) {
        try {
            $patientId = decrypt($request->patient_id);
            $cycleNo = decrypt($request->cycle_no);
            $plan = decrypt($request->plan);
            $visit = decrypt($request->visit);
            $ivfTransferReport = $this->IvfTransferReport->wherePatientIdAndCycleNoAndPlanAndVisitNo($patientId, $cycleNo,  $plan, $visit)->first();
            return response()->json([
                'status' => 1,
                'ivf_transfer_report_id' => !empty($ivfTransferReport) ? encrypt($ivfTransferReport->id) : null,
                'data' => $ivfTransferReport
            ]);
        } catch(Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateTransferReport(Request $request) {
        try {
            // dd($request->update_ivf_transfer_report_id);
            $ivfTransferId = decrypt($request->update_ivf_transfer_report_id);
            $transferReport = $this->IvfTransferReport->whereId($ivfTransferId)->first();
            $transferReport->indication = $request->indication;
            $transferReport->et_date = !empty($request->et_date) ? \Carbon\Carbon::parse($request->et_date)->format('Y-m-d') : null;
            $transferReport->day = $request->day;
            $transferReport->endo_thickness = $request->endo_thickness;
            $transferReport->et_procedure = $request->et_procedure;
            if(!empty($request->embryos_transferred_image)){
                $imagePath = 'public/upload/ivf/report';
                $picture = $request->embryos_transferred_image;
                $imageName = $this->uploadImage($picture, $imagePath);
                $request->embryos_transferred_image = $imagePath.'/'.$imageName;
                $transferReport->embryos_transferred_image = $request->embryos_transferred_image;
            }
            $transferReport->embryos_transferred = $request->embryos_transferred;
            $transferReport->frozen_embryos = $request->frozen_embryos;
            $transferReport->pickup_date = !empty($request->pick_up_date) ? \Carbon\Carbon::parse($request->pick_up_date)->format('Y-m-d') : null;
            $transferReport->simulation_protocol = $request->simulation_protocol;
            $transferReport->total_occ = $request->total_occ;
            $transferReport->mll = $request->mll;
            $transferReport->ml = $request->ml;
            $transferReport->gv = $request->gv;
            $transferReport->oocycle_quality = $request->oocycle_quality;
            $transferReport->sperm_quality = $request->sperm_quality;
            $transferReport->fertilization_procedure = $request->fertilization_procedure;
            $transferReport->remark = $request->remark;
            $transferReport->save();
            $status = 1;
            $data = null;
            if ($request->is_print) {
                $status = 2;
                $data = View::make('admin.ivf.transfer_report', compact('transferReport'))->render();
            }
            return [
                'status' => $status,
                'data' => $data
            ];
        } catch (Exception $e) {
            log::Debug($e);
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // get invesitigation all report type wise
    private function getImagesData($reportType,$type,$id,$data){
        if($type == 'ivf'){
            $ivf = $this->IVF->wherePatientsId($id)->first();
        }
        if($type == 'ivf_history'){
            $ivf = $this->IvfHistory->find($id);
        }
        $ivfInvestigation = json_decode($ivf->investigation);
        if($reportType == 'hystroscopy_old'){
            $ivfData = !empty($ivfInvestigation->hystroscopy) ? $ivfInvestigation->hystroscopy : [];
            if(!empty($ivfData)){
                $hystroscopyImages = $this->getImagesKey($ivfData,$data)['key'];
                // dd($hystroscopyImages);
                if(!empty($hystroscopyImages)){
                    foreach($hystroscopyImages as $row){
                        $this->removeImage($ivfData->images[$row]);
                        unset($ivfData->images[$row]);
                    }
                    $iuiArray = (array)$ivfData->images;
                    $iuiArrayData = array_values($iuiArray);
                    $ivfData->images =  $iuiArrayData;
                    $ivfInvestigation->hystroscopy = $ivfData;
                    $ivf->investigation = $ivfInvestigation;
                }
            }
        }
        if($reportType == 'laproscopy_old'){
            $ivfData = !empty($ivfInvestigation->laproscopy) ? $ivfInvestigation->laproscopy : [];
            if(!empty($ivfData)){
                $hystroscopyImages = $this->getImagesKey($ivfData,$data)['key'];
                if(!empty($hystroscopyImages)){
                    foreach($hystroscopyImages as $row){
                        $this->removeImage($ivfData->images[$row]);
                        unset($ivfData->images[$row]);
                    }
                    $iuiArray = (array)$ivfData->images;
                    $iuiArrayData = array_values($iuiArray);
                    $ivfData->images =  $iuiArrayData;
                    $ivfInvestigation->laproscopy = $ivfData;
                    $ivf->investigation = $ivfInvestigation;
                }
            }
        }
        if($reportType == 'hcg_old'){
            $ivfData = !empty($ivfInvestigation->hcg) ? $ivfInvestigation->hcg : [];
            if(!empty($ivfData)){
                $hystroscopyImages = $this->getImagesKey($ivfData,$data)['key'];
                if(!empty($hystroscopyImages)){
                    foreach($hystroscopyImages as $row){
                        $this->removeImage($ivfData->images[$row]);
                        unset($ivfData->images[$row]);
                    }
                    $iuiArray = (array)$ivfData->images;
                    $iuiArrayData = array_values($iuiArray);
                    $ivfData->images =  $iuiArrayData;
                    $ivfInvestigation->hcg = $ivfData;
                    $ivf->investigation = $ivfInvestigation;
                }
            }
        }
        if($reportType == 'hsa_report_old'){
            // $ivfDescription = json_decode($ivf->description);
            $ivfData = !empty($ivfInvestigation->hsa_report) ? $ivfInvestigation->hsa_report : [];
            if(!empty($ivfData)){
                $hsaImages = $this->getImagesKey($ivfData,$data)['key'];
                if(!empty($hsaImages)){
                    foreach($hsaImages as $row){
                        $this->removeImage($ivfData->images[$row]);
                        unset($ivfData->images[$row]);
                    }
                    $iuiArray = (array)$ivfData->images;
                    $iuiArrayData = array_values($iuiArray);
                    $ivfData->images =  $iuiArrayData;
                    $ivfInvestigation->hsa_report = $ivfData;
                    $ivf->investigation = $ivfInvestigation;
                }
            }
        }
        if($reportType == 'blood_report_old'){
            if($type == 'ivf_history')
            {
                $ivfDescription = json_decode($ivf->description);
                $ivfData = !empty($ivfDescription->blood_report) ? $ivfDescription->blood_report : [];
                if(!empty($ivfData)){
                    $blood_reportImages = $this->getBloodImagesKey($ivfData,$data)['key'];
                    if(!empty($blood_reportImages)){
                        foreach($blood_reportImages as $row){
                            $this->removeImage($ivfData->image[$row]);
                            unset($ivfData->image[$row]);
                        }
                        $iuiArray = (array)$ivfData->image;
                        $iuiArrayData = array_values($iuiArray);
                        $ivfData->image =  $iuiArrayData;
                        $ivfDescription->blood_report = $ivfData;
                        $ivf->description = $ivfDescription;
                    }
                }
                $ivf->description = json_encode($ivfDescription);
            }
            else
            {
                $ivfData = !empty($ivfInvestigation->blood_report) ? $ivfInvestigation->blood_report : [];
                if(!empty($ivfData)){
                    $blood_reportImages = $this->getBloodImagesKey($ivfData,$data)['key'];
                    if(!empty($blood_reportImages)){
                        foreach($blood_reportImages as $row){
                            $this->removeImage($ivfData->image[$row]);
                            unset($ivfData->image[$row]);
                        }
                        $iuiArray = (array)$ivfData->image;
                        $iuiArrayData = array_values($iuiArray);
                        $ivfData->image =  $iuiArrayData;
                        $ivfInvestigation->blood_report = $ivfData;
                        $ivf->investigation = $ivfInvestigation;
                    }
                }
            }
            
        }
        if($type == 'ivf_history'){
            $ivfDescription = json_decode($ivf->description);
            if($reportType == 'usg_old')
            {
                $ivfData = !empty($ivfDescription->usg) ? $ivfDescription->usg : [];
                if(!empty($ivfData)){
                    $usg_reportImages = $this->getImagesKey($ivfData,$data)['key'];
                    if(!empty($usg_reportImages)){
                        foreach($usg_reportImages as $row){
                            $this->removeImage($ivfData->images[$row]);
                            unset($ivfData->images[$row]);
                        }
                        $iuiArray = (array)$ivfData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $ivfData->images =  $iuiArrayData;
                        $ivfDescription->usg = $ivfData;
                        $ivf->description = $ivfDescription;
                    }
                }
            }
            if($reportType == 'hsa_report_old'){
                $ivfData = !empty($ivfDescription->hsa_report) ? $ivfDescription->hsa_report : [];
                if(!empty($ivfData)){
                    $hsaImages = $this->getImagesKey($ivfData,$data)['key'];
                    if(!empty($hsaImages)){
                        foreach($hsaImages as $row){
                            $this->removeImage($ivfData->images[$row]);
                            unset($ivfData->images[$row]);
                        }
                        $iuiArray = (array)$ivfData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $ivfData->images =  $iuiArrayData;
                        $ivfDescription->hsa_report = $ivfData;
                        $ivf->description = $ivfDescription;
                    }
                }
            }
            $ivf->description = json_encode($ivfDescription);
        }
        $ivf->investigation = json_encode($ivfInvestigation);
        $ivf->save();
        return ['status'=>true];
    }

    private function getImagesKey($ivfData,$data){
        $imagesKey = [];
        $removedImageKey = [];
        if(!empty($ivfData->images)){
            foreach($ivfData->images as $key=>$row){
                $imagesKey[] =$key;
            }
            $removedImageKey = array_diff($imagesKey,$data);
        }
        return ['key'=>$removedImageKey];
    }
    //get blood_report keys
    private function getBloodImagesKey($ivfData,$data){
        $imagesKey = [];
        $removedImageKey = [];
        if(!empty($ivfData->image)){
            foreach($ivfData->image as $key=>$row){
                $imagesKey[] =$key;
            }
            $removedImageKey = array_diff($imagesKey,$data);
        }
        return ['key'=>$removedImageKey];
    }

    // store ho tab data in anc_ho_history table if not exists data in table
    public function storeIVFHpData($nameData,$type){
        if(!empty($nameData)){
            foreach($nameData as $key=>$row){
                $checkAncHoData = $this->AncHoHistory->where('name',$row)->where('type',$type)->first();
                if(!$checkAncHoData){
                    $ancHoData = new $this->AncHoHistory;
                    $ancHoData->name = $row;
                    $ancHoData->type = $type;
                    $ancHoData->save();
                }
            }
        }
        return ['status'=>true];
    }

    // get sum of payment total
    public function getIvfPaymentTotal(Request $request){
        try{
            $pId = decrypt($request->patient_id);
            // $ivfPayment = $this->IvfPayment->where('patients_id',$pId)->where('is_completed',0)->sum('payment');
            $ivfPayment = $this->IvfPayment->where('patients_id',$pId)->sum('payment');
            return['status'=>true,'total'=>$ivfPayment];
        }catch(Exception $e){
            abort(500);
            return ['status'=>false];
        }
    }

    // this function is use for generate file view of all visit in IVF
    public function getIvfDetails(Request $request){
        try{
            $historyData = null;
            $doseData = null;
            $remark = null;
            $transferDate = null;
            $currentdate =Carbon::now()->format('d-m-y');
            $investigationReport = $this->allInvestigationReport();
            if($request->ajax()){
                $patientId = decrypt($request->patient_id);
                $lastAppointmentData = $this->Appointment->wherePatientsId($patientId)->orderBy('id','DESC')->first();
                // $ivfData = $this->IVF->wherePatientsId($patientId)->orderBy('id','DESC')->first();
                // $ivf = $ivfData;
                $isIvfHistory = '1';
                $plan = $request->plan;
                $cycleNo = $request->cycle_no;
                $visitNo = $request->visit == 'null' ? 1 : $request->visit;
                $type = $request->type;
                $ivfHistoryDate = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->where('cycle_no',$cycleNo)->orderBy('created_at','DESC')->pluck('created_at','created_at')->toArray();
                $ivfDateData = $this->IVF->where('patients_id',$patientId)->orderBy('created_at','DESC')->first();
                $ivfDate = [Carbon::parse($ivfDateData->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ivfDateData->created_at)->format('Y-m-d H:i:s')];
                $ivfVisitDate = array_merge($ivfHistoryDate,$ivfDate);
                $encIvfId = [];
                if($request->visitDate && !empty($request->visitDate))
                {
                    $visitDate = $request->visitDate;
                    $ivf = $this->IVF->where('patients_id',$patientId)->where('created_at',$visitDate)->first();
                    $isIvfHistory = '1';
                    $ivfType = 2;
                    if(!$ivf)
                    {
                        $isIvfHistory = '2';
                        $ivfHistory = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->where('cycle_no',$cycleNo)->where('created_at',$visitDate)->first();
                        $ivf = $ivfHistory;
                       
                        $historyData = json_decode($ivf->description);
                        $doseData = $this->Dose->pluck('name','name');
                    }
                    $visitNumber[] = $isIvfHistory;
                    $viewAllVisit[] =  View::make('admin.ivf.preview', compact('investigationReport','ivf', 'historyData', 'isIvfHistory','doseData','remark','transferDate','currentdate','lastAppointmentData'))->render();
                        // $viewAllVisit,$preview);
                    $dateValue[] = $visitDate;
                }
                else
                {
                    foreach($ivfVisitDate as $key => $date)
                    {
                        $ivf = $this->IVF->where('patients_id',$patientId)->where('created_at',$key)->first();
                        $isIvfHistory = '1';
                        $ivfType = 1;
                        if($ivf)
                        {
                            $visitNumber[]  = '1';
                        }
                        if(!$ivf)
                        {
                            $isIvfHistory = '2';
                            $ivfHistory = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->where('cycle_no',$cycleNo)->where('created_at',$key)->first();
                            $ivf = $ivfHistory;
                            $historyData = json_decode($ivf->description);
                            $doseData = $this->Dose->pluck('name','name');
                            $visitNumber[] = $ivf->visit;
                        }
                        
                        $viewAllVisit[] =  View::make('admin.ivf.preview', compact('investigationReport','ivf', 'historyData', 'isIvfHistory','doseData','remark','transferDate','currentdate','lastAppointmentData'))->render();
                        // $viewAllVisit,$preview);
                        $dateValue[] = $key;
                        $ivfId = $ivf->id;
                        $encIvfId[] = encrypt($ivfId);
                    }
                }
                
                // if($ivfHistory){
                //     $isIvfHistory = '2';
                //     if($type){
                //         $ivfHistory = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->where('cycle_no',$cycleNo);
                //         if($type == 2){
                //             $visitNo = $visitNo + 1;
                //         }else{
                //             $visitNo = $visitNo - 1;
                //             if($visitNo == 1){
                //                 $isIvfHistory = '1';
                //             }
                //         }
                //         $ivfHistory = $ivfHistory->where('visit',$visitNo)->first();
                //     }
                // }
                // if($visitNo == 1 && $request->visit == 'null'){
                //     $ivfHistory = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->where('cycle_no',$cycleNo)->orderBy('id','asc')->first();
                // }
                // if($isIvfHistory != '1' && $ivfHistory){
                //     $ivf = $ivfHistory;
                //     $historyData = json_decode($ivf->description);
                //     $doseData = $this->Dose->pluck('name','name');
                // }
                // $ivfType = 1;
                // if($request->is_print == 1){
                //     $ivfType = 2;
                //     if($request->visit == 'null'){
                //         $ivf = $ivfData;
                //         $isIvfHistory = '1';
                //     }else{
                //         $ivfHistory = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$plan)->where('visit',$request->visit)->where('cycle_no',$cycleNo)->first();
                //         $ivf = $ivfHistory;
                //         $historyData = json_decode($ivf->description);
                //         $doseData = $this->Dose->pluck('name','name');
                //         $isIvfHistory = '2';
                //     }
                // }
                $planData = ['1'=>'Self','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
                
                return response()->json([
                    'status'=>1,
                    'ivf_type'=>$ivfType,
                    'cycle'=>$cycleNo,
                    'status' => 1,
                    // 'id' => $ivfId,
                    'enc_ivf_id' => $encIvfId,
                    'plan' => $planData[$plan],
                    // 'type' => $type,
                    'date' => $dateValue,
                    'visitNumber'=>$visitNumber,
                    'data' => $viewAllVisit
                ]);
                // dd($printPreview);
                // return response()->json([
                //     'ivf_type' => $ivfType,
                //     'cycle'=>$ivf->cycle_no,
                //     'visit'=>$ivf->visit,
                //     'date'=>Carbon::parse($ivf->created_at)->format('Y-m-d H:i:s'),
                //     'status' => 1,
                //     'id' => $ivfId,
                //     'enc_ivf_id' => $encIvfId,
                //     'data' => View::make('admin.ivf.preview', compact('investigationReport','ivf', 'historyData', 'isIvfHistory','doseData','remark','transferDate','currentdate','lastAppointmentData'))->render()
                // ]);
            }else{
                $pt_view = 1;
                $ohData = null;
                $isTableView = '0';
                $ivfCycleData = null;
                $patientId = decrypt($request->patient_id);
                $historyDate = $request->date;
                $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                if(isset($request->is_trasnfer) && $request->is_trasnfer == 1)
                {
                    $ivfData = $this->IvfHistory->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                    if($ivfData)
                    {
                        $transferReport = $this->IvfTransferReport->where('patient_id',$patientId)->where('cycle_no',$ivfData->cycle_no)->first();
                        return view('admin.ivf.transfer_report', compact('transferReport','pt_view'));
                    }
                    return 'no record available';
                }
                elseif(isset($request->is_pickup) && $request->is_pickup == 1)
                {
                    $ivfReport = $this->IvfPlanReport->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                    if($ivfReport)
                    {
                        $printPreview = 1;
                        return view('admin.ivf.ivf_plan_report_print', compact('ivfReport','printPreview','pt_view'));
                    }
                    return 'no record available';
                }
                else
                {
                    $investigationReport = $this->allInvestigationReport();
                    $historyDate = $request->date;
                    $patientId = decrypt($request->patient_id);
                    $cycle_no = decrypt($request->cycle_no);
                    $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                    $ivfData = $this->IVF->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                    $isIvfHistory = '1';
                    $ivfSecondVisitData = null;
                    
                    if(!$ivfData || $request->is_history == 1){
                        $ivfData = $this->IvfHistory->where('patients_id',$patientId)->where('cycle_no',$cycle_no)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                        $ivf = $ivfData;
                        $historyData = json_decode($ivf->description);
                        $doseData = $this->Dose->pluck('name','name');
                        $isIvfHistory = '2';
                        $isTableView = '1';
                        $ivfCycleData = $this->IvfHistory->wherePatientsId($patientId)->whereCycleNo($ivfData->cycle_no)->wherePlan($ivfData->plan)->get();
                        $ivfSecondVisit = $this->IvfHistory->where('patients_id',$patientId)->where('plan',$ivfData->plan)->where('cycle_no',$ivfData->cycle_no)->where('visit',2)->first();
                            $ivfSecondVisitData = json_decode($ivfSecondVisit->description);
                            $ivfFirst = $this->IVF->wherePatientsId($patientId)->orderBy('id','DESC')->first();
                            $lmpDate = null;
                            $uterusData = null;
                            if($ivfFirst){
                                $lmpDate = $ivfFirst->lmp_date;
                                $oeData = json_decode($ivfFirst->o_e);
                                $ohData = json_decode($ivfFirst->o_h);
                                if(!empty($oeData->uterus->details)){
                                    $uterusData = $oeData->uterus->details;
                                }

                            }
                    }
                    if(!$ivfData){
                        return 'no record available';
                    }
                    $ivf = $ivfData;
                    $printPreview = 1;
                    
                    return view('admin.ivf.preview', compact('investigationReport','ivf', 'historyData', 'isIvfHistory','doseData','remark','transferDate','currentdate','lastAppointmentData','printPreview','pt_view','isTableView','ohData','ivfSecondVisitData','ivfCycleData'));
                }
                
            }
        }catch(Exception $e){
            log::debug($e);
            return ['status'=>2];
        }
    }

    // injection report data to cycle vise and pickup plan vise
    public function getInjectionDetails(Request $request){
        try{
            $cycleNo = $request->cycle_no;
            $pId = decrypt($request->patient_id);
            $plan = $request->plan;
            $ivfHistory = $this->IvfHistory->where('patients_id',$pId)->where('cycle_no',$cycleNo)->where('plan',$plan)->orderBy('id','asc')->get();
            $triggerHistory = $this->IvfHistory->where('patients_id',$pId)->where('cycle_no',$cycleNo)->where('plan',$plan)->whereNotNull('trigger_date')->orderBy('id','DESC')->first();
            return response()->json([
                'status' => 1,
                'data' => View::make('admin.ivf.injection_preview', compact('ivfHistory','triggerHistory'))->render()
            ]);
        }catch(Exception $e){
            log::debug($e);
            return ['status'=>2];
        }
    }

    public function removeLastCycleVisit(Request $request,$visitId){
        try{
            $visitId = decrypt($visitId);
            $ivfHistory = $this->IvfHistory->find($visitId);
            if($ivfHistory){
                $ivfHistory->delete();
                Session::flash('msg','Record has been successfully removed.');
            }
            return ['status'=>1];
        }catch(Exception $e){
            return ['status'=>2];
        }
    }

    public function getFetReport(Request $request){
        try{
            $pId = decrypt($request->patient_id);
            $patient = $this->OpdPatients->find($pId);
            $cycleNo = $request->cycle_no;
            $plan = $request->plan;
            $seconivfHistoryData = $this->IvfHistory->where('patients_id',$pId)->where('visit',2)->where('cycle_no',$cycleNo)->where('plan',$plan)->first();
            $ivfHistoryData = $this->IvfHistory->where('patients_id',$pId)->where('cycle_no',$cycleNo)->where('plan',$plan)->where('visit','!=','2')->get();
            $ivfPlanReport = $this->IvfPlanReport->where('patients_id',$pId)->where('cycle_no',$cycleNo)->where('plan',1)->orderBy('id','DESC')->first();
            $data['seconivfHistoryData'] = $seconivfHistoryData;
            $data['ivfHistoryData'] = $ivfHistoryData;
            $data['patient'] = $patient;
            $data['ivfPlanReport'] = $ivfPlanReport;
            $status = $request->is_print ? 2 : 1;
            $data['status'] = $status;
            // dd(View::make('admin.ivf.fet_report_data',$data)->render());
            return response()->json([
                'status' => $status,
                'data' => View::make('admin.ivf.fet_report_data',$data)->render()
            ]);
            
        }catch(Exception $e){
            // dd($e);
            Log::debug($e);
            return ['status'=>2];
        }
    }
     /**
     * get Report of IVF history
     */
    public function getIvfHistoryReport(Request $request,$id)
    {
        try
        {
            $id = decrypt($id);
            $ivf = $this->IvfHistory->where('id',$id)->first();
            $investigation = !empty($ivf->investigation) ? json_decode($ivf->investigation) : null;
            $description = !empty($ivf->description) ? json_decode($ivf->description) : null;
            $data['hystroscopy'] = !empty($investigation->hystroscopy) && !empty($investigation->hystroscopy->images)  ? (array)$investigation->hystroscopy->images : [];
            $data['laproscopy'] = !empty($investigation->laproscopy) && !empty($investigation->laproscopy->images)  ? (array)$investigation->laproscopy->images : [];
            $data['blood_report'] = !empty($description->blood_report) && !empty($description->blood_report->image)  ? (array)$description->blood_report->image : [];
            $data['usg'] = !empty($description->usg) && !empty($description->usg->images)  ? (array)$description->usg->images : [];
            $data['hsa'] = !empty($description->hsa_report) && !empty($description->hsa_report->images)  ? (array)$description->hsa_report->images : [];
            return response()->json([
                'status' => 1,
                'data' => $data
            ]);
        }
        catch(Exception $e){
            // dd($e);
            Log::debug($e);
            return ['status'=>2];
        }
    }
}
