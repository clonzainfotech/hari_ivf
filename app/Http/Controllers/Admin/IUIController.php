<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Exception;
use Session;
use Auth;
use View;
use Log;

class IUIController extends AdminController
{

    /**
     * Dispaly only IUI Appointment and not completed IUI Appointment
     * Request parameter:date range, patient, isprint
     * here serch functionality is working on appointment date and patient wise
     */
    public function index(Request $request){
        try{
            $patients = $this->getPatients();
            if($request->ajax()){
                $appointment = $this->Appointment->where('is_procedure',0)->where('is_done',0)->whereIn('category_id',['3','4'])->orderBy('id','DESC');

                // search text
                $patientId = $request->patient_id;
                if($patientId){
                    $appointment = $appointment->where(function($query) use($patientId){
                        $query->whereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->Where('id', $patientId);
                        });
                    });
                }
                if($request->hcg == 1){
                    $patientsId = $this->IuiHistory->where('description->hcg->type','yes')->pluck('patients_id');
                    $appointment = $appointment->whereIn('patients_id',$patientsId);
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
                    $appointment = $this->Appointment->where('is_done',0)->whereIn('category_id',['3','4'])->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['iui'] = View::make('admin.iui.print',compact('appointment'))->render();
                    return $data;
                }
                $appointment = $appointment->paginate(100);
                $patient_notification = $this->patientNotification->first();
                $data['status'] = 1;
                $data['iui'] = View::make('admin.iui.data',compact('appointment','patient_notification'))->render();
                return $data;
            }
            return view('admin.iui.index', compact('patients'));
        }catch(Exception $e){
            abort(500);
        }
    }

    // this function is use for open the page for iui first visit
    public function create($patientsId,$appointmentId = null){
        try{
            $apId = null;
            $appointmentData = null;
            if($appointmentId){
                $apId = decrypt($appointmentId);
                $appointmentData = $this->Appointment->find($apId);
            }
            $pId = decrypt($patientsId);
            $iuiPatients = $this->OpdPatients->find($pId);
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $cycleNoData = $this->IuiHistory->where('patients_id',$pId)->orderBY('id','DESC')->first();
            $hoData = $this->getHoData();
            $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $rmoDoctor = $this->User->whereRole('3')->where('is_rmo_doctor',1)->whereStatus('1')->pluck('name','id')->toArray();
            $durationOfData = getDurationOfData(2)['data'];
            $cycleNo = 1;
            if($cycleNoData && $cycleNoData->visit == 4){
                $cycleNo = $cycleNoData->cycle_no + 1;
            }
            $category = $this->Category
            ->whereStatus(1)
            ->whereNotIn('id', [7])
            ->pluck('name','id');
            return view('admin.iui.create',compact('rmoDoctor','personalData','pastData','familyData','iuiPatients','hoData','patientsId','referenceDoctor','complaints','medicines','hospitalTime','leftOvaryData','rightOvaryData','cycleNo','durationOfData','appointmentData','category','hospitalDoctor'));
        }catch(Exception $e){
            return back();
        }
    }

    /**
     * All visit of IUI store in iui and iui_history table If visit 1, data entry in iui table otherwise iui_history table
     * If result is fail then again patient move in IUI module and here first visit entry store
     * If result is consive then patient move in ANC moduel and here first visit entry store
     * If IVF yes come from request then patient move in IVF and here fist visit entry store
     */
    public function store(Request $request){
        try{
            $isProcudure = 0;
            $patientsId = decrypt($request->patients_id);
            $lastAppointmentData = $this->Appointment->where('patients_id',$patientsId)->orderBy('id','DESC')->first();
            // 1 visit
            $day = !empty($request->mh['lmd_date_diff']) ? $request->mh['lmd_date_diff'] : 0;
            $isSecondVisit = $day == 2 ? true : false;
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
            $msg = null;
            $seenBy = null;
            $fDate = null;
            $iuiStatus = 1;
            $ancStatus = 0;
            $ivfStatus = 0;
            $gynecStatus = 0;
            $bloodOldImages = [];
            $usgOldImages = [];
            $hsaOldImages = [];
            if($request->visit == 1){
                $iui = $this->IUI;
                $investigationData = $request->investigation;
                $hystroscopyOldImages = [];
                $laproscopyOldImages = [];
                $hcgOldImages = [];
                $hsaOldImages = [];
                
                if($request->iui_id){
                    $this->getImagesData('hystroscopy_old','iui',$request->iui_id,$request->hystroscopy_old ? $request->hystroscopy_old : [-1]);
                    $this->getImagesData('laproscopy_old','iui',$request->iui_id,$request->laproscopy_old ? $request->laproscopy_old : [-1]);
                    $this->getImagesData('hcg_old','iui',$request->iui_id,$request->hcg_old ? $request->hcg_old : [-1]);
                    $this->getImagesData('blood_report_old','iui',$request->iui_id,$request->blood_report_old ? $request->blood_report_old : [-1]);
                    $this->getImagesData('hsa_report_old','iui',$request->iui_id,$request->hsa_report_old ? $request->hsa_report_old : [-1]);
                    $iui = $iui->where('id',$request->iui_id)->first();
                    if(!empty($iui->investigation)){
                        $oldInvestigationData = json_decode($iui->investigation);
                        if(!empty($oldInvestigationData)){
                            $hystroscopyOldImages = !empty($oldInvestigationData->hystroscopy->images) ? (array)$oldInvestigationData->hystroscopy->images : [];
                            $laproscopyOldImages = !empty($oldInvestigationData->laproscopy->images) ? (array)$oldInvestigationData->laproscopy->images : [];
                            $hcgOldImages = !empty($oldInvestigationData->hcg->images) ? (array)$oldInvestigationData->hcg->images : [];
                            $bloodOldImages = !empty($oldInvestigationData->blood_report->image) ? (array)$oldInvestigationData->blood_report->image : [];
                            $hsaOldImages = !empty($oldInvestigationData->hsa_report->images) ? (array)$oldInvestigationData->hsa_report->images : [];
                        }
                    }
                }
                if(!empty($request['investigation']['hystroscopy']['images'])){
                    foreach($request['investigation']['hystroscopy']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/report');
                        $hystroscopyImagesData[] = 'public/upload/iui/report/' . $name;
                    }
                    $investigationData['hystroscopy']['images'] = array_merge($hystroscopyImagesData,$hystroscopyOldImages);
                }else{
                    $investigationData['hystroscopy']['images'] = $hystroscopyOldImages;
                }
                if(!empty($request['investigation']['laproscopy']['images'])){
                    foreach($request['investigation']['laproscopy']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/report');
                        $laproscopyImagesData[] = 'public/upload/iui/report/' . $name;
                    }
                    $investigationData['laproscopy']['images'] = array_merge($laproscopyImagesData,$laproscopyOldImages);
                }else{
                    $investigationData['laproscopy']['images'] = $laproscopyOldImages;
                }
                if(!empty($request['investigation']['hcg']['images'])){
                    foreach($request['investigation']['hcg']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/report');
                        $hcgImagesData[] = 'public/upload/iui/report/' . $name;
                    }
                    $investigationData['hcg']['images'] = array_merge($hcgImagesData,$hcgOldImages);
                }else{
                    $investigationData['hcg']['images'] = $hcgOldImages;
                }
                if(!empty($request['investigation']['blood_report']['image'])){
                    foreach($request['investigation']['blood_report']['image'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/blood/');
                        $bloodImagesData[] = 'public/upload/iui/blood/' . $name;
                    }
                    $investigationData['blood_report']['image'] = array_merge($bloodImagesData,$bloodOldImages);
                }else{
                    $investigationData['blood_report']['image'] = $bloodOldImages;
                }
                if(!empty($request['investigation']['hsa_report']['images'])){
                    foreach($request['investigation']['hsa_report']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/report/');
                        $hsaImagesData[] = 'public/upload/iui/report/' . $name;
                    }
                    $investigationData['hsa_report']['images'] = array_merge($hsaImagesData,$hsaOldImages);
                }else{
                    $investigationData['hsa_report']['images'] = $hsaOldImages;
                }
                if(!empty($request['p_detailes']['personal_history_history_type'])){
                    $this->storeIUIHoData($request['p_detailes']['personal_history_history_type'],1);
                }
                if(!empty($request['p_detailes']['past_history_type'])){
                    $this->storeIUIHoData($request['p_detailes']['past_history_type'],2);
                }
                if(!empty($request['p_detailes']['family_history'])){
                    $this->storeIUIHoData($request['p_detailes']['family_history'],3);
                }
                if(in_array($request->category,[5,6])){
                    $iui = $this->ANC->where('patients_id',$patientsId)->first();
                    if(!$iui){
                        $iui = $this->ANC;
                    }
                    $iuiStatus = 0;
                    $ancStatus = 1;
                }
                if(in_array($request->category,[1,2])){
                    $iui = $this->IVF->where('patients_id',$patientsId)->first();
                    if(!$iui){
                        $iui = $this->IVF;
                    }
                    $iuiStatus = 0;
                    $ivfStatus = 1;
                }
                if(in_array($request->category,[17,18])){
                    $iui = $this->Gynec->where('patients_id',$patientsId)->first();
                    if(!$iui){
                        $iui = $this->Gynec;
                    }
                    $iuiStatus = 0;
                    $gynecStatus = 1;
                }
                if($gynecStatus == 0){
                    $iui->patients_info = json_encode($request->p_info);
                }
                $seenBy = $request->seen_by;
                $iui->seen_by = $seenBy;
                $iui->rmo_doctor = !empty($request->rmo_doctor) ? $request->rmo_doctor : null;
                if($gynecStatus == 0){
                    $iui->h_o = json_encode($request->ho);
                    $iui->c_o = json_encode($request->co);
                }
                if($gynecStatus == 1){
                    $iui->ho = json_encode($request->ho);
                    $iui->co = json_encode($request->co);
                }
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
                if(!empty($request->ho['ho_details'])){
                    $this->hoData($request->ho['ho_details']);
                }
                if(!empty($dayData)){
                    durationData(2,$dayData);
                }
                if($gynecStatus == 0){
                    $iui->m_h = json_encode($mhData);
                }

                if($gynecStatus == 1){
                    $iui->mh = json_encode($mhData);
                    $iui->oe = json_encode($request->oe);
                }
                if($iuiStatus == 1 || $ivfStatus == 1){
                    if(!empty($request->investigation['investigation_details'])){
                        $investigationData['investigation_details'] =  array_filter($request->investigation['investigation_details']);
                    }
                    $iui->ho_rx = json_encode($request->ho_rx);
                    $iui->investigation = json_encode($investigationData);
                    $iui->husband_factor = json_encode($request->h_factor);
                    $iui->o_e = json_encode($request->oe);
                    $iui->o_h = json_encode($request->oh);
                    if($iuiStatus == 1){
                        $iui->plan_management = json_encode($request->plan_of_management);
                    }
                    $iui->possible_case_of_infertility = json_encode($request->possible_case_of_infertility);
                }
                if($ancStatus == 1){
                    $iui->patients_obstratics = json_encode($request->oh);
                }
                $iui->patients_details_ho = json_encode($request->p_detailes);
                $rightData = [];
                $leftData = [];
                if(!empty($request->oe['ovary']['right']['details']) || !empty($request->oe['ovary']['left']['details'])){
                    $rightData = !empty($request->oe['ovary']['right']['details']) ? $request->oe['ovary']['right']['details'] : [];
                    $leftData = !empty($request->oe['ovary']['left']['details']) ? $request->oe['ovary']['left']['details'] : [];
                }
                if(!empty($leftData)){
                    $data = array_unique($leftData);
                    addOvaryAbnormalData($data,1);
                }
                if(!empty($rightData)){
                    $data = array_unique($rightData);
                    addOvaryAbnormalData($data,2);
                }
                $this->complaintStore($request->co);
                // if(!empty($request->treatment['medicinedata'])){
                //     $this->medicineData($request->treatment['medicinedata']);
                //     $this->treatmentData($request->treatment);
                // }
                $followupDate = !empty($request->oe['follow_up']) ? $request->oe['follow_up'] : null;
                $appointmentTime = null;
                        $fDate = !empty($followupDate) ? Carbon::parse($followupDate)->format('Y-m-d') : null;
                        if($fDate){
                            $requestData = new \Illuminate\Http\Request();
                            $requestData->replace(['date' => $fDate,'status'=>true]);
                            $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                            if(!empty($nextAppontment['time']) || $nextAppontment['time'] == 0){
                                $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                                $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                                $followupDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $followDate;
                            }
                        }
                        // $checkAppointment = $this->Appointment->wherePatientsId($patientsId)->whereDate('date',$followDate)->orderBy('id','DESC')->first();
                        $appointment = $this->Appointment->where('patients_id',$patientsId)->orderBy('id','DESC')->first();
                        if($appointment){
                            if(!empty($request->data['ivf']) && $request->data['ivf'] == 'yes')
                            {
                                $appointmentData['category'] = 1;
                            }
                            $appointmentData['appointmentId'] = encrypt($appointment->id);
                            $appointmentData['date'] = $followupDate;
                            $appointmentData['time'] = $appointmentTime;
                            $nextAppointment = $this->nextAppointmentData($appointmentData);
                        }

                $iui->treatment = !empty($request->treatment) ? json_encode($request->treatment) : json_encode($request->old_treatment);
                // patients data update from iui
                $patients = $this->OpdPatients->find($patientsId);
                $patients->name = $request->name;
                $patients->weight = $request->weight;
                $patients->reference_doctor_id = $request->rd_reference;
                $patients->mobile_number = $request->mobile_number;
                $patients->residence = $request->residence;
                $patients->main_area = $request->main_area;
                $patients->city = $request->city;
                $patients->save();
                if($request->plan_of_management && !empty($request->plan_of_management['plan_of_management_data'])){
                    $firstVisitplanData = ['counceling'=>'Counceling','wait_watch'=>'Wait Watch',
                                            'management_by_rx'=>'Management by Rx.','hyperstimulation_iui'=>'Controlled Overian Hyperstimulation With I.U.I',
                                            'laproscopy'=>'Laproscopy','ivf'=>'I.V.F','male_factor'=>'Rx. Of Male Factor','reports'=>'Reports','induction_gonadotropins_cycle'=>'Induction Gonadotropins Cycle','other'=>'Other'];
                    $valueData = [];
                    foreach($request->plan_of_management['plan_of_management_data'] as $key=>$row){
                        $valueData[] = $firstVisitplanData[$row];
                    }
                    $msgData = implode(',',$valueData);
                    $msg = 'Advise '.$msgData;
                }
            }
            // 2 visit
            if($request->visit != 1){
                if(isset($request->data['hcg']['iui']['status']) && $request->data['hcg']['iui']['status'] == 'yes'){
                    $isProcudure = 1;
                }
                $isAnc = false;
                $iuiPatientsData = $this->IUI->where('patients_id',$patientsId)->orderBy('id','DESC')->first();
                if(!empty($request->data['lmp']['date'])){
                    $iuiPatientsData->lmp_date = Carbon::parse($request->data['lmp']['date'])->format('Y-m-d');
                    $iuiPatientsData->save();
                }
                $iui = $this->IuiHistory;
                if($request->iui_history_id){
                    $iui = $iui->find($request->iui_history_id);
                    if($iui)
                    {
                        $this->getImagesData('blood_report_old','iui_history',$request->iui_history_id,$request->blood_report_old ? $request->blood_report_old : [-1]);
                        $this->getImagesData('usg_old','iui_history',$request->iui_history_id,$request->usg_old ? $request->usg_old : [-1]);
                        $this->getImagesData('hsa_report_old','iui_history',$request->iui_history_id,$request->hsa_report_old ? $request->hsa_report_old : [-1]);
                        $iui = $iui->where('id',$request->iui_history_id)->first();
                    }
                }
                
                $iui->visit = $request->visit;
                $data = $request->data;
                if(!empty($request->iui_history_id) && $request->visit == 2) {
                    $isData = $this->IuiHistory->whereId($request->iui_history_id)->first();
                    if(!empty($isData)) {
                        $plan = json_decode($isData->description);
                        if(!empty($data['plan']['agenet'])){
                            $data['plan']['agenet'] = (!isset($data['plan']['agenet'])) ? $plan->plan->agenet : $data['plan']['agenet'];
                        }
                   }
                }
                $rightData = [];
                $leftData = [];
                if(!empty($request->data['oe']['ovary']['right']['details']) || !empty($request->data['oe']['ovary']['left']['details'])){
                    $rightData = !empty($request->data['oe']['ovary']['right']['details']) ? $request->data['oe']['ovary']['right']['details'] : [];
                    $leftData = !empty($request->data['oe']['ovary']['left']['details']) ? $request->data['oe']['ovary']['left']['details'] : [];
                }
                if(!empty($leftData)){
                    $ldata = array_unique($leftData);
                    addOvaryAbnormalData($ldata,1);
                }
                if(!empty($rightData)){
                    $rdata = array_unique($rightData);
                    addOvaryAbnormalData($rdata,2);
                }
                if(!empty($request['data']['treatment']) || !empty($request['data']['old_treatment'])){
                    $treatmentData['treatment'] = !empty($request['data']['treatment']) ? $request['data']['treatment'] : $request['data']['old_treatment'];
                    array_push($treatmentData,$data);
                }
                $followupDate = !empty($request->data['follow_up']) ? $request->data['follow_up'] : null;
                if($request->visit == 2){
                    $seenBy = $request->seen_by_2;
                    $iui->seen_by = $seenBy;
                    $iui->rmo_doctor = !empty($request->rmo_doctor) ? $request->rmo_doctor : null;
                    if(!empty($request['data']['plan']['plan_type'])){
                        $planData = $request['data']['plan']['plan_type'];
                        $planData = explode(' ', $planData);
                        $last_word = array_pop($planData);
                        $msg = $last_word == 'IUI' ? 'Advise IUI ' : 'Advise Medicines ';
                    }
                    $followupDate = !empty($request->data['plan']['follow_up']) ? $request->data['plan']['follow_up'] : null;
                }
                if($request->visit == 3){
                    $seenBy = $request->seen_by_3;
                    $iui->seen_by = $seenBy;
                    $iui->rmo_doctor = !empty($request->rmo_doctor) ? $request->rmo_doctor : null;
                    $msg = !empty($request['data']['ovalution']) && $request['data']['ovalution'] == 'yes' && !empty($request['data']['hcg']) && !empty($request['data']['hcg']['iui']) && !empty($request['data']['hcg']['iui']['status']) && $request['data']['hcg']['iui']['status'] == 'yes' && !empty($request['data']['hcg']['iui']['type']) ? 'IUI Done ' : 'Advise Ovalution Study ';
                }
                if($request->visit == 4){
                    $seenBy = $request->seen_by_4;
                    $iui->seen_by = $seenBy;
                    $iui->rmo_doctor = !empty($request->rmo_doctor) ? $request->rmo_doctor : null;
                    $msg = !empty($request->data['result']) ? 'Result '.ucfirst($request->data['result']) : null;
                    $followupDate = !empty($request->data['date']) ? $request->data['date'] : null;
                }
                if(!empty($request->data['result']) && $request->data['result'] == 'fail' && empty($request->iui_history_id) && (isset($request->data['upt_type']) && $request->data['upt_type'] == 'negative')){
                    $iuiFirstVisitData = $this->IUI;
                    $iuiFirstVisitData->patients_id = $iuiPatientsData->patients_id;
                    $iuiFirstVisitData->seen_by = ($seenBy) ? $seenBy : Auth::user()->id;
                    $iuiFirstVisitData->created_by = $iuiPatientsData->created_by;
                    $iuiFirstVisitData->patients_info = $iuiPatientsData->patients_info;
                    $iuiFirstVisitData->h_o = $iuiPatientsData->h_o;
                    $iuiFirstVisitData->c_o = $iuiPatientsData->c_o;
                    $iuiFirstVisitData->o_h = $iuiPatientsData->o_h;
                    $iuiFirstVisitData->m_h = $iuiPatientsData->m_h;
                    $iuiFirstVisitData->ho_rx = $iuiPatientsData->ho_rx;
                    $iuiFirstVisitData->investigation = $iuiPatientsData->investigation;
                    $iuiFirstVisitData->husband_factor = $iuiPatientsData->husband_factor;
                    $iuiFirstVisitData->patients_details_ho = $iuiPatientsData->patients_details_ho;
                    $iuiFirstVisitData->o_e = $iuiPatientsData->o_e;
                    $iuiFirstVisitData->plan_management = $iuiPatientsData->plan_management;
                    $iuiFirstVisitData->possible_case_of_infertility = $iuiPatientsData->possible_case_of_infertility;
                    $iuiFirstVisitData->treatment = $iuiPatientsData->treatment;
                    $iuiFirstVisitData->cycle_no = $iuiPatientsData->cycle_no + 1;
                    $iuiFirstVisitData->cycle_status = 1;
                    $iuiFirstVisitData->created_at = Carbon::now()->addSeconds(120)->format('Y-m-d H:i:s');
                    $iuiFirstVisitData->save();
                }
                if(!empty($request->data['result']) && $request->data['result'] == 'consive'){
                    $ancData = $this->ANC;
                    $autoRemark = [];
                    
                    //set EDD date and lmpdate from second visit
                    $iuiSecondVisit = $this->IuiHistory->where('patients_id',$patientsId)->whereCycleNo($request->cycle_no)->where('visit',2)->first();
                    $iuiSecondVisitData = json_decode($iuiSecondVisit->description);
                    $iui_mh_data = json_decode($iuiPatientsData->m_h);
                    $iui_mh_data->last_menstrual_date = !empty($iuiSecondVisitData->lmp->date) ? $iuiSecondVisitData->lmp->date : '';
                    $iui_mh_data->lmd_date_diff = !empty($iuiSecondVisitData->lmp->lmp_date_diff) ? $iuiSecondVisitData->lmp->lmp_date_diff : '';
                    $iui_mh_data->edd = !empty($iuiSecondVisitData->lmp->date) ? Carbon::parse($iuiSecondVisitData->lmp->date)->addMonths(9)->addDays(7)->format('Y-m-d') : '';
                    $iuiPatientsData->m_h = json_encode($iui_mh_data);
                    if(!empty($iuiPatientsData->h_o))
                    {
                        $hoData = json_decode($iuiPatientsData->h_o);
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
                        $oldDate = Carbon::parse(!empty($iuiSecondVisitData->lmp->date) ? $iuiSecondVisitData->lmp->date : date('Y-m-d'))->format('Y-m-d');
                        $nowDate = Carbon::now();
                        $diffDays = Carbon::parse($oldDate)->diffInDays($nowDate);
                        $totalDays = $monthDays + $diffDays;
                        $hoData->ho_details = (int)($totalDays/$days).' month '.$totalDays % $days.' day';
                        // $hoDetails->ho_details =  $hoDetails;
                        $iuiPatientsData->h_o = json_encode($hoData);
                    }
                    $ho_type = ['1'=>'Naturally','2'=>'Medicine','3'=>'IUI'];
                    $autoRemark['remark'] = "Conceived with ".(isset($request->data['ho_type']) && !empty($request->data['ho_type']) ? $ho_type[$request->data['ho_type']]: '');
                    $ancData->patients_id = $patientsId;
                    $ancData->seen_by = ($seenBy) ? $seenBy : Auth::user()->id;
                    $ancData->patients_info = $iuiPatientsData->patients_info;
                    $ancData->patients_details_ho = $iuiPatientsData->patients_details_ho;
                    $ancData->patients_obstratics = $iuiPatientsData->o_h;
                    $ancData->created_by = Auth::user()->id;
                    $ancData->m_h = $iuiPatientsData->m_h;
                    $ancData->h_o = $iuiPatientsData->h_o;
                    $ancData->c_o = $iuiPatientsData->c_o;
                    $ancData->o_e = json_encode($autoRemark);
                    $ancData->edd = $iui_mh_data->edd;
                    $ancData->treatment = $iuiPatientsData->treatment;
                    $ancData->save();
                    $isAnc = true;
                }
                // next appointment save
                if(!empty($followupDate)){
                    $currentDate = date('Y-m-d');
                    $followDate = date('Y-m-d',strtotime($followupDate));
                    unset($data['follow_up']);
                    $data['new_follow_up'] = $followDate;
                    if($request->visit == 2){
                        $data['plan']['follow_up'] = $followDate;
                    }
                    if($request->visit == 3){
                        $data['follow_up'] = $followDate;
                    }
                    if($request->visit == 4){
                        $data['date'] = $followDate;
                    }
                }
                if($followupDate && !$request->iui_history_id){
                    $currentDate = date('Y-m-d');
                    $followDate = date('Y-m-d',strtotime($followupDate));
                    unset($data['follow_up']);
                    $data['new_follow_up'] = $followDate;
                    if($request->visit == 2){
                        $data['plan']['follow_up'] = $followDate;
                    }
                    if($request->visit == 3){
                        $data['follow_up'] = $followDate;
                    }
                    if($request->visit == 4){
                        $data['date'] = $followDate;
                    }
                    $iuiPatientsData->follow_up = $followDate;
                    $iuiPatientsData->save();
                        
                        $appointmentTime = null;
                        $fDate = !empty($followDate) ? Carbon::parse($followDate)->format('Y-m-d') : null;
                        if($fDate){
                            $requestData = new \Illuminate\Http\Request();
                            $requestData->replace(['date' => $fDate,'status'=>true]);
                            $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                            if(!empty($nextAppontment['time']) || $nextAppontment['time'] == 0){
                                $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                                $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                                $followDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $followDate;
                            }
                        }
                        // $checkAppointment = $this->Appointment->wherePatientsId($patientsId)->whereDate('date',$followDate)->orderBy('id','DESC')->first();
                        $appointment = $this->Appointment->where('patients_id',$patientsId)->orderBy('id','DESC')->first();
                        if($appointment){
                            if(!empty($request->data['ivf']) && $request->data['ivf'] == 'yes')
                            {
                                $appointmentData['category'] = 1;
                            }
                            $appointmentData['appointmentId'] = encrypt($appointment->id);
                            $appointmentData['date'] = $followDate;
                            $appointmentData['isAnc'] = $isAnc;
                            $appointmentData['time'] = $appointmentTime;
                            $appointmentData['is_procedure'] = $isProcudure;
                            $nextAppointment = $this->nextAppointmentData($appointmentData);
                        }

                    // }
                }
                if(!empty($request->data['inducing'])){
                    $inducing_date = explode(',',$request->data['inducing']['date']);
                    foreach($inducing_date as $key => $inducing_date)
                    {
                        $inducingDate[$key  + 1] = array('date' => $inducing_date);
                    }
                    $data['inducing'] = $inducingDate;
                    // $inducingDate = $request->data['inducing'];
                    // $inducingDate = array_values($inducingDate);
                    // $inducingDate = array_combine(range(1, count($inducingDate)), $inducingDate);
                    // $data['inducing'] = $inducingDate;
                }
                
                if(!empty($iui->description))
                {
                    $description = json_decode($iui->description);
                    $bloodOldImages = !empty($description->blood_report->image) ? (array)$description->blood_report->image : [];
                    $usgOldImages = !empty($description->usg->images) ? (array)$description->usg->images : [];
                    $hsaOldImages = !empty($description->hsa_report->images) ? (array)$description->hsa_report->images : [];
                }
                if(!empty($request['data']['blood_report']['image'])){
                    foreach($request['data']['blood_report']['image'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/blood/');
                        $bloodImagesData[] = 'public/upload/iui/blood/' . $name;
                    }
                    $data['blood_report']['image'] = array_merge($bloodImagesData,$bloodOldImages);
                }else{
                    $data['blood_report']['image'] = $bloodOldImages;
                }
                if(!empty($request['data']['usg']['images'])){
                    foreach($request['data']['usg']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/report/');
                        $usgImagesData[] = 'public/upload/iui/report/' . $name;
                    }
                    $data['usg']['images'] = array_merge($usgImagesData,$usgOldImages);
                }else{
                    $data['usg']['images'] = $usgOldImages;
                }
                if(!empty($request['data']['hsa_report']['images'])){
                    foreach($request['data']['hsa_report']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/iui/report/');
                        $hsaImagesData[] = 'public/upload/iui/report/' . $name;
                    }
                    $data['hsa_report']['images'] = array_merge($hsaImagesData,$hsaOldImages);
                }else{
                    $data['hsa_report']['images'] = $hsaOldImages;
                }
               
                $iui->description = json_encode($data);
                $iui->husband_factor = isset($request['h_factor']) ? json_encode($request['h_factor']) : null;
                if(!empty($request->data['oe']['ovary']['right']['details']) || !empty($request->data['oe']['ovary']['left']['details'])){
                    $rightData = !empty($request->data['oe']['ovary']['right']['details']) ? $request->data['oe']['ovary']['right']['details'] : [];
                    $leftData = !empty($request->data['oe']['ovary']['left']['details']) ? $request->data['oe']['ovary']['left']['details'] : [];
                    $data = array_unique (array_merge ($rightData, $leftData));
                    if(!empty($data) && array_filter($data)){
                        $this->addOvaryAbnormalData($data);
                    }
                }
                // if(!empty($request->data['treatment']['medicinedata'])){
                //     $this->medicineData($request->data['treatment']['medicinedata']);
                //     $this->treatmentData($request->data['treatment']);
                // }
                // ivf data store
                if($iuiStatus == 1){
                    $iui->cycle_status = 1;
                }
                if(!empty($request->data['ivf']) && $request->data['ivf'] == 'yes'){
                    $lastIui = $this->IUI->wherePatientsIdAndCycleNo($patientsId, $request->cycle_no)->first();
                    $lastIUIHistory = $this->IuiHistory->wherePatientsIdAndCycleNo($patientsId, $request->cycle_no)->get();
                    $iuiSecondVisit = $this->IuiHistory->where('patients_id',$patientsId)->whereCycleNo($request->cycle_no)->where('visit',2)->first();
                    $iuiSecondVisitData = !empty($iuiSecondVisit) ? json_decode($iuiSecondVisit->description) : null;
                    
                    $checkIvf = $this->IVF->wherePatientsId($lastIui->patients_id)->first();
                    $ivf = (!$checkIvf) ? $this->IVF : $this->IVF->wherePatientsId($lastIui->patients_id)->first();
                    $ivf->patients_id = $lastIui->patients_id;
                    $ivf->created_by = $lastIui->created_by;
                    $ivf->patients_info = $lastIui->patients_info;
                    $ivf->h_o = $lastIui->h_o;
                    $ivf->c_o = $lastIui->c_o;
                    $ivf->o_h = $lastIui->o_h;
                    $ivf->m_h = $lastIui->m_h;
                    $ivf->ho_rx = $lastIui->ho_rx;
                    $ivf->investigation = $lastIui->investigation;
                    $ivf->husband_factor = $lastIui->husband_factor;
                    $ivf->patients_details_ho = $lastIui->patients_details_ho;
                    $ivf->o_e = $lastIui->o_e;
                    $ivf->plan_management = $lastIui->plan_management;
                    $ivf->possible_case_of_infertility = $lastIui->possible_case_of_infertility;
                    $ivf->treatment = $lastIui->treatment;
                    $ivf->lmp_date = !empty($iuiSecondVisitData->lmp->date) ? Carbon::parse($iuiSecondVisitData->lmp->date)->format('Y-m-d') : Carbon::parse(!empty($request->data['lmp']['date']) ? $request->data['lmp']['date'] : date('y-m-d'))->format('Y-m-d');
                    $ivf->save();
                    if($lastIUIHistory)
                    {
                        $inducingInjectionData = $this->inducingInjection()['inj'];
                        $injectionData = ['1'=>'Only HMG','2'=>'Only FSH','3'=>'FSH + HMG','4'=>'Lupride','5'=>'Letrozole + HMG','6'=>'Letrozole + FSH','7'=>'Clomiphene Citrate + HMG','8'=>'Clomiphene Citrate + FSH','9'=>'Antagonist'];
                        $lastCycleNo = $this->IvfHistory->where('patients_id',$patientsId)->where('plan',1)->get();
                        $lastivfHistory = $lastCycleNo->last();
                        $ivfVisit = 2;
                        $third_visit_Skey = 1;
                        $ivfHistorydata = [];
                        foreach($lastIUIHistory as $iuiHistory)
                        {
                            $iuiData = json_decode($iuiHistory->description);
                            $dateAndInjectionData = [];
                            $inducingDateArray = [];
                            if($iuiData)
                            {
                                $lmpDate = \Carbon\Carbon::parse($iuiData->lmp->date)->format('d-m-Y');
                                $createdAt = \Carbon\Carbon::parse($iuiHistory->created_at)->format('d-m-Y');
                                $diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($createdAt));
                                $diff = $diff + 1;
                                $agentData = !empty($iuiData->plan->inducing_agent) ? $iuiData->plan->inducing_agent : [];
                                $s_key = 1;
                                $protocol_key = 1;
                                if($iuiHistory->visit == 2){
                                    $appointmentDate = \Carbon\Carbon::parse($iuiHistory->created_at)->format('d-m-Y');
                                    $agentData = !empty($iuiData->plan->agenet) ? $iuiData->plan->agenet: [];
                                }
                                if(!empty($iuiData->inducing)){
                                    
                                    $agentDataValue = [];
                                    foreach($iuiData->inducing as $key => $value) {
                                        $inducingDateArray[] = \Carbon\Carbon::parse($value->date)->format('d-m-Y');
                                        $agentDataValue = !empty($iuiData->plan->inducing_agent) ? $iuiData->plan->inducing_agent : [];
                                        $value->injection = $agentDataValue;
                                    }
                                    $dateAndInjectionData[] = (array)$iuiData->inducing;
                                    // $dateAnd[] = (array)$data->inducing;
                                }
                                if($iuiHistory->visit == 2 && !in_array($appointmentDate,$inducingDateArray))
                                {
                                    $protocol[$protocol_key]['day'] = $diff;
                                    $protocol[$protocol_key]['s_day'] = $s_key;
                                    $protocol[$protocol_key]['date'] = \Carbon\Carbon::parse($appointmentDate)->format('D d M Y');
                                    $protocol[$protocol_key]['injection'] = null;
                                    $protocol[$protocol_key]['hmg'] = null;
                                    $protocol[$protocol_key]['hmg_brand_name'] = null;
                                    $protocol[$protocol_key]['fsh'] = null;
                                    $protocol[$protocol_key]['fsh_brand_name'] = null;
                                    $protocol[$protocol_key]['antagonist'] = null;
                                    $protocol[$protocol_key]['time'] = null;
                                    $s_key++;
                                    $third_visit_Skey = $s_key;
                                    
                                    $iuiData->protocol = $protocol;
                                    $iuiData->le = $iuiData->lmp->le;
                                   
                                    $iuiData->is_transfer = "no";
                                    $iuiData->is_transfer_print = "no";
                                    $iuiData->skip_reason = null;
                                    $iuiData->plan = null;
                                    $iuiData->follow_up = \Carbon\Carbon::parse($followupDate)->format('D d M Y');
                                    $iuiHistory->description = json_encode($iuiData);
                                    $ivfHistorydata[] = [
                                        "patients_id" => $iuiHistory->patients_id,
                                        "seen_by" => $iuiHistory->seen_by,
                                        "rmo_doctor" => $iuiHistory->rmo_doctor,
                                        "created_by" => Auth::user()->id,
                                        "plan" => 1,
                                        'cycle_no' => !empty($lastivfHistory) ? $lastivfHistory->cycle_no + 1 : 1,
                                        'visit' =>$ivfVisit,
                                        'cycle_status' => 1,
                                        'description' => stripslashes($iuiHistory->description),
                                        'investigation' => null,
                                        'trigger_date' => null,
                                        'trigger_time' => null,
                                        'created_at'=>\Carbon\Carbon::parse($createdAt)->format('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s')
                                    ];
                                }
                                if(!empty($dateAndInjectionData) && $iuiHistory->visit == 2)
                                {
                                    foreach(array_flatten($dateAndInjectionData) as $keyValue=>$valueData)
                                    {
                                        $protocol = [];
                                        $date = \Carbon\Carbon::parse($valueData->date)->format('d-m-Y');
                                        $inducing_diff = \Carbon\Carbon::parse($lmpDate)->diffInDays(\Carbon\Carbon::parse($valueData->date));
                                        $inducing_diff = $inducing_diff + 1;
                                        if($iuiHistory->visit == 2)
                                        {
                                            $inducingAgentDataValue = [];
                                            if(!empty($agentData))
                                            {
                                                foreach($agentData as $injection)
                                                {
                                                    if((!empty($injection)) && strpos($injection,'+') !== false)
                                                    {
                                                        $injection_name = explode('+',$injection)[1];
                                                        $spilt_from = (strpos($injection_name,'on') !== false) ? 'on' : '-';
                                                        $inj_name = explode($spilt_from,$injection_name)[0];
                                                        $inducingAgentDataValue[] = $inj_name;
                                                    }
                                                    else {
                                                        $inducingAgentDataValue[] = $injection;
                                                    }
                                                }
                                            }
                                            $inducing_agent = !empty($inducingAgentDataValue) ? implode(',',$inducingAgentDataValue) : '';
                                            if(!empty($inducing_agent) && (!empty($valueData->date)))
                                            {
                                                $protocol[$protocol_key]['day'] = $inducing_diff;
                                                $protocol[$protocol_key]['s_day'] = $s_key;
                                                $protocol[$protocol_key]['date'] = \Carbon\Carbon::parse($date)->format('D d M Y');
                                                
                                                $injection = '';
                                                $inducing_agent = trim($inducing_agent);
                                                $dose = explode(' ', $inducing_agent);
                                                
                                                $brand_name = $this->string_between_two_string($inducing_agent,'(',')');
                                                $protocol[$protocol_key]['injection'] = null;
                                                $protocol[$protocol_key]['hmg'] = null;
                                                $protocol[$protocol_key]['hmg_brand_name'] = null;
                                                $protocol[$protocol_key]['fsh'] = null;
                                                $protocol[$protocol_key]['fsh_brand_name'] = null;
                                                if(strpos($inducing_agent,'HMG') !== false) 
                                                {
                                                    $injection = 1;
                                                    // $brand_name = $this->string_between_two_string($inducing_agent,'(',')');
                                                    $protocol[$protocol_key]['injection'] = $injection;
                                                    $protocol[$protocol_key]['hmg'] = array_pop($dose);
                                                    $protocol[$protocol_key]['hmg_brand_name'] = $brand_name;
                                                }
                                                elseif(strpos($inducing_agent,'FSH') !== false)
                                                {
                                                    $injection = 2;
                                                    $protocol[$protocol_key]['injection'] = $injection;
                                                    $protocol[$protocol_key]['fsh'] = array_pop($dose);
                                                    $protocol[$protocol_key]['fsh_brand_name'] = $brand_name;
                                                }
                                                $protocol[$protocol_key]['antagonist'] = null;
                                                $protocol[$protocol_key]['time'] = null;
                                                $ivfVisit++;
                                                $iuiData->protocol = $protocol;
                                                $iuiData->le = $iuiData->lmp->le;
                                                $iuiData->is_transfer = "no";
                                                $iuiData->is_transfer_print = "no";
                                                $iuiData->skip_reason = null;
                                                $iuiData->plan = null;
                                                $iuiData->follow_up = \Carbon\Carbon::parse($followupDate)->format('D d M Y');
                                                $iuiHistory->description = json_encode($iuiData);
                                                $ivfHistorydata[] = [
                                                    "patients_id" => $iuiHistory->patients_id,
                                                    "seen_by" => $iuiHistory->seen_by,
                                                    "rmo_doctor" => $iuiHistory->rmo_doctor,
                                                    "created_by" => Auth::user()->id,
                                                    "plan" => 1,
                                                    'cycle_no' => !empty($lastivfHistory) ? $lastivfHistory->cycle_no + 1 : 1,
                                                    'visit' =>$ivfVisit,
                                                    'cycle_status' => 1,
                                                    'description' => stripslashes($iuiHistory->description),
                                                    'investigation' => null,
                                                    'trigger_date' => null,
                                                    'trigger_time' => null,
                                                    'created_at'=>\Carbon\Carbon::parse($valueData->date)->format('Y-m-d H:i:s'),
                                                    'updated_at'=>date('Y-m-d H:i:s')
                                                ];
                                            }
                                        }
                                        
                                        $third_visit_Skey = $s_key;
                                        $s_key++;
                                       
                                    }
                                }
                                if($iuiHistory->visit == 3)
                                {
                                    $agentData = !empty($iuiData->plan->inducing_agent) ? $iuiData->plan->inducing_agent : [];
                                    $InjectionData = '';
                                    if(!empty($agentData))
                                    {
                                        $ivfVisit++;
                                        $protocol = [];
                                        foreach($agentData as $agentData)
                                        {
                                            $third_visit_Skey++;
                                            $protocol[$protocol_key]['day'] = $diff;
                                            $protocol[$protocol_key]['s_day'] = $third_visit_Skey;
                                            $protocol[$protocol_key]['date'] = \Carbon\Carbon::parse($iuiHistory->created_at)->format('D d M Y');
                                            $injection = '';
                                            $inducingInjection = $inducingInjectionData[$agentData];
                                            $inducing_agent = trim($inducingInjection);
                                            preg_match_all('!\d+!', $inducing_agent, $dose);
                                            $brand_name = $this->string_between_two_string($inducingInjection,'(',')');
                                            $protocol[$protocol_key]['injection'] = null;
                                            $protocol[$protocol_key]['hmg'] = null;
                                            $protocol[$protocol_key]['hmg_brand_name'] = null;
                                            $protocol[$protocol_key]['fsh'] = null;
                                            $protocol[$protocol_key]['fsh_brand_name'] = null;
                                            if(strpos($inducingInjection,'HMG') !== false) 
                                            {
                                                $injection = 1;
                                                // $brand_name = $this->string_between_two_string($inducing_agent,'(',')');
                                                $protocol[$protocol_key]['injection'] = $injection;
                                                $protocol[$protocol_key]['hmg'] = array_pop($dose)[0];
                                                $protocol[$protocol_key]['hmg_brand_name'] = $brand_name;
                                            }
                                            elseif(strpos($inducingInjection,'FSH') !== false)
                                            {
                                                $injection = 2;
                                                $protocol[$protocol_key]['injection'] = $injection;
                                                $protocol[$protocol_key]['fsh'] = array_pop($dose)[0];
                                                $protocol[$protocol_key]['fsh_brand_name'] = $brand_name;
                                            }
                                        
                                            // $protocol[$protocol_key]['injection'] = $inducingInjectionData[$agentData];
                                            
                                            $protocol[$protocol_key]['antagonist'] = null;
                                            $protocol[$protocol_key]['time'] = null;
                                            // dd($protocol);
                                            $protocol_key++;
                                            
                                        }
                                        $iuiData->protocol = $protocol;
                                        $iuiData->is_transfer = "no";
                                        $iuiData->is_transfer_print = "no";
                                        $iuiData->skip_reason = null;
                                        $iuiData->plan = null;
                                        $iuiData->follow_up = \Carbon\Carbon::parse($followupDate)->format('D d M Y');
                                        $iuiHistory->description = json_encode($iuiData);
                                        $ivfHistorydata[] = [
                                            "patients_id" => $iuiHistory->patients_id,
                                            "seen_by" => $iuiHistory->seen_by,
                                            "rmo_doctor" => $iuiHistory->rmo_doctor,
                                            "created_by" => Auth::user()->id,
                                            "plan" => 1,
                                            'cycle_no' => !empty($lastivfHistory) ? $lastivfHistory->cycle_no + 1 : 1,
                                            'visit' =>$ivfVisit,
                                            'cycle_status' => 1,
                                            'description' => stripslashes($iuiHistory->description),
                                            'investigation' => null,
                                            'trigger_date' => null,
                                            'trigger_time' => null,
                                            'created_at'=>\Carbon\Carbon::parse($createdAt)->format('Y-m-d H:i:s'),
                                            'updated_at'=>date('Y-m-d H:i:s')
                                        ];
                                    }
                                    else
                                    {
                                        $ivfVisit++;
                                        $third_visit_Skey++;
                                        $protocol = [];
                                        $protocol[$protocol_key]['day'] = $diff;
                                        $protocol[$protocol_key]['s_day'] = $third_visit_Skey;
                                        $protocol[$protocol_key]['date'] = \Carbon\Carbon::parse($iuiHistory->created_at)->format('D d M Y');
                                        $protocol[$protocol_key]['injection'] = null;
                                        $protocol[$protocol_key]['hmg'] = null;
                                        $protocol[$protocol_key]['hmg_brand_name'] = null;
                                        $protocol[$protocol_key]['fsh'] = null;
                                        $protocol[$protocol_key]['fsh_brand_name'] = null;
                                        $protocol[$protocol_key]['antagonist'] = null;
                                        $protocol[$protocol_key]['time'] = null;
                                        $protocol_key++;
                                        $iuiData->protocol = $protocol;
                                        $iuiData->is_transfer = "no";
                                        $iuiData->is_transfer_print = "no";
                                        $iuiData->skip_reason = null;
                                        $iuiData->plan = null;
                                        $iuiData->follow_up = \Carbon\Carbon::parse($followupDate)->format('D d M Y');
                                        $iuiHistory->description = json_encode($iuiData);
                                        $ivfHistorydata[] = [
                                            "patients_id" => $iuiHistory->patients_id,
                                            "seen_by" => $iuiHistory->seen_by,
                                            "rmo_doctor" => $iuiHistory->rmo_doctor,
                                            "created_by" => Auth::user()->id,
                                            "plan" => 1,
                                            'cycle_no' => !empty($lastivfHistory) ? $lastivfHistory->cycle_no + 1 : 1,
                                            'visit' =>$ivfVisit,
                                            'cycle_status' => 1,
                                            'description' => stripslashes($iuiHistory->description),
                                            'investigation' => null,
                                            'trigger_date' => null,
                                            'trigger_time' => null,
                                            'created_at'=>\Carbon\Carbon::parse($createdAt)->format('Y-m-d H:i:s'),
                                            'updated_at'=>date('Y-m-d H:i:s')
                                        ];
                                        

                                    }
                                }
                            }
                        }
                        // dd($ivfHistorydata);
                        $this->IvfHistory->insert($ivfHistorydata);
                        $iui->cycle_status = 2;
                    }
                }
            }
            
            if($request->visit == 4){
                if(isset($request->data['upt_type']) && $request->data['upt_type'] == 'weak_positive' && $request->data['result'] == 'fail')
                {
                    $iui->cycle_status = 1;
                }
                else
                {
                    $this->IUI->wherePatientsId($patientsId)->orderBy('id','DESC')->update(['cycle_status'=>'2']);
                    $iui->cycle_status = 2;
                }
                
            }
            if(!empty($request->data['co_type'])){
                $this->complaintStore($request->data);
            }
            if($iuiStatus == 1){
                $iui->cycle_no = $request->cycle_no;
            }
            $iui->patients_id = $patientsId;
            if(isset($request->data['vascularity_of_endo']))
            {
                $iui->vascularity_of_endo = !empty($request->data['vascularity_of_endo']) ? $request->data['vascularity_of_endo'] : 0;
            }
            $iui->created_at = !empty($iui->created_at) ? $iui->created_at : Carbon::now()->format('Y-m-d H:i:s');
            $iui->created_by = Auth::user()->id;
            if($request->iui_history_id)
            {
                $editIvf = $this->IuiHistory->find($request->iui_history_id);
                $editIvfData = !empty($editIvf) ? json_decode($editIvf->description) : null;
                $checkIui = !empty($editIvfData) && !empty($editIvfData->hcg->type) && $editIvfData->hcg->type == 'yes' && $editIvfData->hcg->iui->status == 'yes' ? true : false;
                $notifyDate = $iuiDtae = Carbon::parse($editIvf->created_at)->format('Y-m-d');
                $category_Id = !empty($request->category) ? $request->category : 4;
                if($checkIui)
                {
                    $notify = $this->CategoryNotification->where('patients_id',$patientsId)->where('category_id',$category_Id)->whereDate('created_at',$notifyDate)->first();
                    if(!empty($editIvfData) && !empty($editIvfData->hcg_date))
                    {
                        $cDate = \Carbon\Carbon::parse(!empty($editIvfData->hcg_date) ? $editIvfData->hcg_date : null)->format('Y-m-d') .' '.$editIvfData->hcg->time;
                        $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('Y-m-d H:i');
                        $iuiDtae = Carbon::parse($iuiDtaeAndTime)->format('Y-m-d');
                        if(Carbon::parse($notify->date)->format('Y-m-d H:i') == $iuiDtaeAndTime)
                        {
                            $cDate = \Carbon\Carbon::parse(!empty($request->data['hcg_date']) ? $request->data['hcg_date'] : null)->format('Y-m-d') .' '.$request->data['hcg']['time'];
                            $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('Y-m-d H:i');
                            $iuiDtae = Carbon::parse($cDate)->format('Y-m-d');
                            $notify->date = $iuiDtaeAndTime;
                            $notify->reminder_date = Carbon::parse($iuiDtaeAndTime)->subDays(1)->format('Y-m-d');
                            $notify->save();
                        }
                        if($request->data['hcg']['iui']['status'] == 'no')
                        {
                            $notify->delete();
                        }
                    }
                }
            }
            $iui->save();
            $now = Carbon::now()->format('Y-m-d');
            if(!$request->iui_history_id && !$request->iui_id)
            {
                $appointmentFlag = $this->Appointment->wherePatientsId($patientsId)->where('date',$now)->update(['is_done'=>1,'seen_by'=>$iui->seen_by]);
                $updateConsulting = $this->Appointment->wherePatientsId($patientsId)->where('date',$now)->update(['in_consulting_room'=>0]);

                if(!empty($request->data['hcg']['type']) && $request->data['hcg']['type'] == 'yes' && !empty($request->data['hcg']['time']) && $request->data['hcg']['iui']['status'] == 'yes')
                {
                    $categoryPatientData = [];
                    $iui->hcg_time = $this->getTimeStatus(Carbon::parse($request->data['hcg']['time'])->format('g:i a'))['timeStatus'];
                    $cDate = \Carbon\Carbon::parse(!empty($request->data['hcg_date']) ? $request->data['hcg_date'] : null)->format('Y-m-d') .' '.$request->data['hcg']['time'];
                    $iuiDtaeAndTime = \Carbon\Carbon::parse($cDate)->addHours(35)->format('Y-m-d H:i');
                    $iuiDtae = Carbon::parse($cDate)->format('Y-m-d');
                    $categoryPatientData['patients_id'] = $patientsId;
                    $categoryPatientData['date'] = $iuiDtaeAndTime;
                    $categoryPatientData['reminder_date'] = Carbon::parse($iuiDtaeAndTime)->subDays(1)->format('Y-m-d');
                    $categoryPatientData['message'] = "Coming for IUI";
                    $categoryPatientData['category_id'] = !empty($request->category) ? $request->category : 4;
                    $nextAppontment = $this->storeCategoryNotification($categoryPatientData);
                }
            }
            
            if(!$request->iui_history_id && !$request->iui_id && $msg){
                $seenBy = getSeenByDoctor($seenBy);
                $patient = $this->OpdPatients->find($patientsId);
                $fDate = $fDate ? date('d M Y',strtotime($fDate)) : null;
                $this->SmsManager::sendReferenceDoctor($msg,$seenBy->name,$fDate,$patientsId);
            }
            if($request->isprint == 1 || $request->isprint == 2 || $request->isprint == 6){
                if($request->isprint == 2){
                    $iui->hcg_time = $this->getTimeStatus(Carbon::parse($request->data['hcg']['time'])->format('g:i a'))['timeStatus'];
                    $cDate = date('Y-m-d').' '.$request->data['hcg']['time'];
                    $new_time = date($cDate, strtotime('+1 hours'));
                    $iuiDtaeAndTime = Carbon::parse($cDate)->addHours(35)->format('Y-m-d g:i a');
                    $iui->iui_time = $this->getTimeStatus(Carbon::parse($iuiDtaeAndTime)->format('g:i a'))['timeStatus'];
                    $iui->iui_print = true;
                }
                
                $iuiData = null;
                $inducingInjectionData = $this->inducingInjection()['inj'];
                $currentdate = Carbon::now()->format("d-m-y");
                $iuiFirstVisit = null;
                $iuiSecondVisit = null;
                $iuiThirdVisit = null;
                $iuiHistoryData = null;
                $iuiSecondVisit_plan = null;
                if($request->isprint == 6){
                    $iuiFirstVisit = $this->IUI->wherePatientsId($patientsId)->whereCycleNo($request->cycle_no)->first();
                    $iuiHistoryData = collect($this->IuiHistory->wherePatientsId($patientsId)->whereCycleNo($request->cycle_no)->get());
                    $iuiSecondVisit = $iuiHistoryData->where('visit',2)->first();
                    if($iuiSecondVisit){
                        $iuiSecondVisit = json_decode($iuiSecondVisit->description);
                    }
                    
                    $iuiThirdVisit = $this->IuiHistory->wherePatientsId($patientsId)->whereCycleNo($request->cycle_no)->where('visit',3)->where('description->ovalution','yes')->first();
                    if($iuiThirdVisit){
                        $iuiThirdVisit = json_decode($iuiThirdVisit->description);
                    }
                    $iui->study_report = true;
                }
                
                if($gynecStatus == 1){
                    $iui->c_o = $iui->co;
                    $iui->h_o = $iui->ho;
                }
                $investigationReport = $this->allInvestigationReport();
                return response()->json([
                    'status' => 1,
                    'id' => $iui->id,
                    'data' => View::make('admin.iui.preview', compact('investigationReport','iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData'))->render()
                ]);
            }
            if(isset($request->is_iui_report) && $request->is_iui_report == 'yes' && $request->isprint == 8)
            {
                $printPreview = 1;
                return response()->json([
                    'status' => 8,
                    'data' => View::make('admin.iui.iuireportprint', compact('iuiReport','printPreview'))->render()
                ]);
            }
            if(isset($request->is_iui_deposit_print) && $request->is_iui_deposit_print == 4){
                $currentDeposit = $this->IndoorDeposit->wherePatientIdAndChargeType($patientsId, 1)->orderBy('id', 'DESC')->value('total');
                $iuiBill = $this->IuiBill;
                $iuiBill->patient_id = $patientsId;
                $iuiBill->cycle_no = $request->cycle_no;
                $iuiBill->o_study = $request->o_study;
                $iuiBill->o_study = $request->o_study;
                $iuiBill->injections = json_encode($request->deposit_injections);
                $iuiBill->iui = $request->iui;
                $iuiBill->discount = $request->discount;
                $iuiBill->discount_in = $request->discount_in;
                $iuiBill->total = $request->sub_total;
                $iuiBill->grand_total = $request->total;
                $iuiBill->save();
                if($currentDeposit > 0 && $request->total > 0) {
                    $indoorDeposit = $this->IndoorDeposit;
                    $indoorDeposit->patient_id = $patientsId;
                    $indoorDeposit->admin_id = Auth::user()->id;
                    $grandTotal = $request->total + $currentDeposit;
                    $indoorDeposit->amount = $currentDeposit;
                    $indoorDeposit->total = 0;

                    if ($grandTotal < $currentDeposit) {
                        $cutAmount = $currentDeposit - $grandTotal;
                        $indoorDeposit->amount = $request->total;
                        $indoorDeposit->total = $cutAmount;
                    }
                    $indoorDeposit->case_type = 'Debit';
                    $indoorDeposit->charge_type = 1;
                    $indoorDeposit->save();
                }

                return response()->json([
                    'status' => 5,
                    'data' => View::make('admin.iui.iui_deposit_print', compact('iuiBill', 'currentDeposit'))->render()
                ]);
            }

            Session::flash('msg','Record has been successfully added.');
            return ['status'=>'true','secondVisit'=>$isSecondVisit];
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return ['status'=>'false'];
        }
    }

    // add overy abnoram data in database if not exist in database
    private function addOvaryAbnormalData($data){
        $abnormalData = [];
        $ovaryData = $this->OvaryDetail->pluck('name','name')->toArray();
        $diffrent = array_diff($data,$ovaryData);
        foreach($diffrent as $row){
            $oData = new $this->OvaryDetail;
            $oData->name = $row;
            $oData->save();
        }
        return true;
    }

    /**
     * here open the page for iui second, third and fourth visit
     * and also data fetch for all visit using this function via IUI date wise
     * */
    public function iuiHistory(Request $request,$patientsId){
        try{
            $id = decrypt($patientsId);
            //if pt in iui and currently take tretment in ivf then transfer again in iui or cuurently take tretment and now start iui then auto fill first visit 
            $lastAppointment = $this->Appointment->where('patients_id',$id)->where('is_done',1)->orderBy('id', 'DESC')->first();
            if($lastAppointment && ($lastAppointment->category_id == 1 || $lastAppointment->category_id == 2))
            {
                $firstVisit = $this->IUI->where('patients_id',$id)->first();
                $firstVisitHistory = null;
                if($firstVisit)
                {
                    $firstVisitHistory = $this->IuiHistory->where('patients_id',$id)->where('cycle_no',$firstVisit->cycle_no)->first();
                    $cycleNo = ($firstVisit) ? $firstVisit->cycle_no + 1 : 1;
                    if(!$firstVisit)
                    {
                        $firstVisit = $this->IVF->where('patients_id',$id)->first();
                        $cycleNo = 1;
                    }
                }
                $iuiHistory = $this->IuiHistory->where('patients_id',$id)->where('cycle_no',$cycleNo)->first();
                $checkExistIUI = $this->IUI->where('patients_id',$id)->where('cycle_no',$cycleNo)->first();
                if($firstVisit && !$iuiHistory && !$checkExistIUI && !empty($firstVisitHistory))
                {
                    // dd('sdf');
                    $iui = $this->IUI;
                    $iui->patients_id = $id;
                    $iui->seen_by = $firstVisit->seen_by;
                    $iui->rmo_doctor = $firstVisit->rmo_doctor;
                    $iui->created_by = $firstVisit->created_by;
                    $iui->patients_info = $firstVisit->patients_info;
                    $iui->h_o = $firstVisit->h_o;
                    $iui->c_o = $firstVisit->c_o;
                    $iui->o_h = $firstVisit->o_h;
                    $iui->m_h = $firstVisit->m_h;
                    $iui->ho_rx = $firstVisit->ho_rx;
                    $iui->investigation = $firstVisit->investigation;
                    $iui->husband_factor = $firstVisit->husband_factor;
                    $iui->patients_details_ho = $firstVisit->patients_details_ho;
                    $iui->o_e = $firstVisit->o_e;
                    $iui->plan_management = $firstVisit->plan_management;
                    $iui->possible_case_of_infertility = $firstVisit->possible_case_of_infertility;
                    $iui->treatment = $firstVisit->treatment;
                    $iui->lmp_date = $firstVisit->lmp_date;
                    $iui->cycle_no = $cycleNo;
                    $iui->cycle_status = 1;
                    $iui->save();
                    // $view = redirect('iui/history/'.encrypt($id));
                }
            }
            $iui = $this->IUI->wherePatientsId($id)->orderBy('id','DESC')->first();
            if($request->iui_cycle_no)
            {
                $iui = $this->IUI->wherePatientsId($id)->whereCycleNo($request->iui_cycle_no)->first();
            }
            $firstVisitLmpDate = $iui->lmp_date;
            $lastAppointmentData = $this->Appointment->where('patients_id',$id)->orderBy('id','DESC')->first();
            $iuiSecondVisit = $this->IuiHistory->where('patients_id',$id)->whereVisit(2)->whereCycleNo($iui->cycle_no)->first();
            
            $oldDate = null;
            $lmdData = null;
            $historyCo = null;
            $cycleNo = $iui->cycle_no;
            $iuiSecondVisitData = null;
            $remark = null;
            $visitNo = 2;
            $iui_completed = false;
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $iuiRemarkData = json_decode($iui->o_h);
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            if(!empty($iuiRemarkData->remark)){
                $remark = $iuiRemarkData->remark;
            }
            if($iuiSecondVisit){
                $visitNo = 3;
                $iuiSecondVisitData = json_decode($iuiSecondVisit->description);
                $lmdData = $iuiSecondVisitData->lmp;
                $cycleNo = $iuiSecondVisit->cycle_no;
                if(!empty($iuiSecondVisitData->remark)){
                    $remark = $iuiSecondVisitData->remark;
                }
            }
            $iuiThirdVisit = $this->IuiHistory->wherePatientsIdAndVisit($id, 3)->whereCycleNo($iui->cycle_no)->orderBy('id', 'DESC')->first();
            if($iuiThirdVisit){
                $iuiRemarkData = json_decode($iuiThirdVisit->description);
                if(!empty($iuiRemarkData->remark)){
                    $remark = $iuiRemarkData->remark;
                }
            }
            $durationOfData = ['other'=>'Other'] + getDurationOfData(2)['data'];
            $thirdDescription = !empty($iuiThirdVisit) ? json_decode($iuiThirdVisit->description) : null;
            //if select cycle from dropdown thwn don't change visitNo
            if(isset($thirdDescription->ovalution) && $thirdDescription->ovalution == 'yes' && (!isset($request->iui_cycle_no))) {
                $cycleNo = $iuiThirdVisit->cycle_no;
                $visitNo = 4;
            }
            $date = [];
            $iuiHistoryId = null;
            $historyLmp = null;
            $historyOe = null;
            $historyPlan = null;
            $planData = $this->Injection;
            $historyData = null;
            $historyTreatment = null;
            $oeDataCount = null;
            $follicleString = null;
            $plan = null;
            $planOfSecondVisit = null;
            $iuiReportCycleNo = null;
            $historyInducingDate = null;
            $hystroscopyImagesData = [];
            $hcgImagesData = [];
            $laproscopyImagesData = [];
            $bloodImagesData = [];
            $usgImagesData = [];
            $hsaImagesData = [];
            $follicle = null;
            $iuiThirdVisitData = null;

            // cycle number
            $iuiCycleNo = $this->getCylcleNumber($id)['cycle_number'];
            $iuiCurrentCycleNo = $iui->cycle_no;

            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $inducingInjectionData = $this->inducingInjection()['inj'];
            $medicines = $this->Medicine->pluck('name','name');
            //check if patients is transfer on another plan
            $iuiHistoryData = $this->IuiHistory->wherePatientsId($id)->where('visit',4)->where('cycle_no',$iui->cycle_no)->where('cycle_status',2)->orderBy('created_at','desc')->first();
            if($iuiHistoryData)
            {
                
                $ivfTransfer = $this->IVF->wherePatientsId($id)->where('created_at','>=',$iuiHistoryData->created_at)->first();
                $ancTransfer = $this->ANC->wherePatientsId($id)->where('created_at','>=',$iuiHistoryData->created_at)->first();
                if(!empty($ivfTransfer) || !empty($ancTransfer))
                {
                    $iui_completed = true;
                }
            }
            
            if($request->ajax()){
                // date wise visit show
                $iuiReportCycleNo = $request->iui_cycle_no ? $request->iui_cycle_no :  $cycleNo;
                if($request->iuihistorydate || $request->iui_visit_id)
                {
                    if($request->iuihistorydate)
                    {
                        $iuiFirstVisit = $this->IUI->where('created_at',$request->iuihistorydate)->first();
                        $iuiHistory = $this->IuiHistory->where('created_at',$request->iuihistorydate)->first();
                    }
                    if($request->iui_visit_id)
                    {
                        $iuiVisitId = decrypt($request->iui_visit_id);
                        $iuiFirstVisit = $this->IUI->where('id',$iuiVisitId)->first();
                        $iuiHistory = $this->IuiHistory->where('id',$iuiVisitId)->first();

                    }
                    // dump($request->iuihistorydate);
                    
                    if($iuiFirstVisit){
                        $visitNo = 1;
                        $iui = $iuiFirstVisit;
                    }else{
                        switch ($iuiHistory->visit) {
                            case '2':
                                $visitNo = 2;
                                break;
                            case '3':
                                $visitNo = 3;
                                break;
                            case '4':
                                $visitNo = 4;
                                break;
                        }
                        $iuiSecondVisit = $this->IuiHistory->where('patients_id',$id)->whereVisit(2)->whereCycleNo($iuiHistory->cycle_no)->first();
                        $cycleNo = $iuiHistory->cycle_no;
                        $iui = $iuiHistory;

                        $historyData = json_decode($iui->description);
                        $planType = !empty($historyData->plan->plan_type) ? $historyData->plan->plan_type : null;

                        if($planType){
                            $planData = $planData->where('type',$planType)->where('category',1);
                        }
                        $iuiHistoryId = $iuiHistory->id;
                        $historyLmp = !empty($historyData->lmp) ? $historyData->lmp : null;
                        $historyOe = !empty($historyData->oe) ? $historyData->oe : null;
                        $historyCo = !empty($historyData->co_type) ? $historyData->co_type : null;
                        $historyPlan = !empty($historyData->plan) ? $historyData->plan : null;
                        $historyInducingDate = !empty($historyData->inducing) ? $historyData->inducing : null;
                        if(!empty($historyData->treatment) || !empty($historyData->old_treatment)){
                            $historyTreatment = !empty($historyData->treatment) ? $historyData->treatment : $historyData->old_treatment;
                        }
                        if(!empty($historyTreatment)){
                            unset($historyTreatment->medicinedata);
                        }
                    }
                }
                // cycle number wise get date
                $iuiCycleNoData = $request->iui_cycle_no;
                if($iuiCycleNoData){
                    $iuiSecondVisit = $this->IuiHistory->where('patients_id',$id)->whereVisit(2)->whereCycleNo($iuiCycleNoData)->first();
                    $iuiSecondVisitData = !empty($iuiSecondVisit) ? json_decode($iuiSecondVisit->description) : '';
                    $cycleNo = $iuiCycleNoData;
                    $iuiDate = $this->IUI->where('patients_id',$id)->whereCycleNo($iuiCycleNoData)->select('created_at')->first();
                    $iuiHistoryDate = collect($this->IuiHistory->select('visit','created_at','description->new_follow_up as follow_up')->where('patients_id',$id)->whereCycleNo($iuiCycleNoData)->get())->map(function ($q){
                        $q->follow_up = $q->visit. '. ' .Carbon::parse($q->follow_up)->format('d-m-Y').' '.Carbon::parse($q->created_at)->format('H:i:s');
                        return $q;
                    })->pluck('follow_up','created_at')->toArray();
                    // $iuiHistoryDate = $this->IuiHistory->where('patients_id',$id)->whereCycleNo($iuiCycleNoData)->selectRaw('CONCAT(visit, ".  ",created_at ) as date, created_at')->orderBy('visit','asc')->pluck('date','created_at')->toArray();
                    $iuiDate = [Carbon::parse($iuiDate['created_at'])->format('Y-m-d H:i:s')=>'1. '.Carbon::parse($iuiDate['created_at'])->format('d-m-Y H:i:s')];
                    $date = array_merge($iuiDate,$iuiHistoryDate);
                    $oldDate = $request->iui_date;
                }

                $planData = $planData->where('category',1)->whereNotNull('name')->pluck('name','name')->toArray();
                $patientsInfo = json_decode($iui->patients_info);
                $ho = json_decode($iui->h_o);
                $co = json_decode($iui->c_o);
                $oh = json_decode($iui->o_h);
                $mh = json_decode($iui->m_h);
                $hoRx = json_decode($iui->ho_rx);
                $description = json_decode($iui->description);
                $investigation = json_decode($iui->investigation);
                $husbandFactor = json_decode($iui->husband_factor);
                $planManagement = json_decode($iui->plan_management);
                $patientsDetailsHo = json_decode($iui->patients_details_ho);
                $possibleCaseOfInfertility = json_decode($iui->possible_case_of_infertility);
                $oe = json_decode($iui->o_e);
                $treatment = json_decode($iui->treatment);
                if(!empty($treatment)){
                    unset($treatment->medicinedata);
                }
                $complaints = $this->Complaint->pluck('name','name');
                $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
                $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
                $planType = $this->Injection->where('category',1)->pluck('type','type');
                $hoData = $this->getHoData();

                if(!empty($iuiThirdVisit)) {
                    $iuiFollicle = $this->IuiHistory
                        ->wherePatientsIdAndVisitAndCycleNo($id, 3, $iuiThirdVisit->cycle_no)
                        ->orderBy('id', 'DESC')
                        ->get();
                        
                    foreach($iuiFollicle as $iuiFollicle) {
                        $follicle = json_decode($iuiFollicle->description);
                        $iuiThirdVisitData[] = json_decode($iuiFollicle->description);
                        $follicleString[] = $follicle->no_follicle;
                    }

                    $follicleString = array_filter($follicleString, 'strlen');
                    $follicleString = implode(', ', $follicleString);
                    $plan = json_decode($iuiSecondVisit->description);
                    $planOfSecondVisit = !empty($plan->plan->plan_type) ? $plan->plan->plan_type : null;
                }
                $hystroscopyImages  = !empty($investigation->hystroscopy->images) ? $investigation->hystroscopy->images : null;
                if($hystroscopyImages){
                    foreach($hystroscopyImages as $key=>$row){
                        $hystroscopyImagesData[$key]['id'] = $key;
                        $hystroscopyImagesData[$key]['src'] = url($row);
                    }
                }

                $hcgImages  = !empty($investigation->hcg->images) ? $investigation->hcg->images : null;
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
                if($investigation)
                {
                    $bloodImages = !empty($investigation->blood_report->image) ? $investigation->blood_report->image : null;
                    if($bloodImages){
                        foreach($bloodImages as $key=>$row){
                            $bloodImagesData[$key]['id'] = $key;
                            $bloodImagesData[$key]['src'] = url($row);
                        }
                    }
                    $hsaImages = isset($investigation->hsa_report) && !empty($investigation->hsa_report->images) ? $investigation->hsa_report->images : null;
                    if($hsaImages){
                        foreach($hsaImages as $key=>$row){
                            $hsaImagesData[$key]['id'] = $key;
                            $hsaImagesData[$key]['src'] = url($row);
                        }
                    }
                }
                if($description)
                {
                    $bloodImages = !empty($description->blood_report->image) ? $description->blood_report->image : null;
                    $usgImages = !empty($description->usg->images) ? $description->usg->images : null;
                    $hsaImages = !empty($description->hsa_report->images) ? $description->hsa_report->images : null;
                    // dd($description->blood_report->image);
                    if($bloodImages){
                        foreach($bloodImages as $key=>$row){
                            $bloodImagesData[$key]['id'] = $key;
                            $bloodImagesData[$key]['src'] = url($row);
                        }
                    }
                    if($usgImages){
                        foreach($usgImages as $key=>$row){
                            $usgImagesData[$key]['id'] = $key;
                            $usgImagesData[$key]['src'] = url($row);
                        }
                    }
                    if($hsaImages){
                        foreach($hsaImages as $key=>$row){
                            $hsaImagesData[$key]['id'] = $key;
                            $hsaImagesData[$key]['src'] = url($row);
                        }
                    }
                }
                
                $medicineKey = [];
                if(!empty($treatment)){
                    $medicineKey = (array)$treatment;
                    $medicineKey = array_column($medicineKey,'medicine');
                    if(!empty($medicineKey)){
                        $medicineKey = array_combine($medicineKey,$medicineKey);
                    }
                }
                $historyMedicineKey = [];
                if(!empty($historyTreatment)){
                    $historyMedicineKey = (array)$historyTreatment;
                    $historyMedicineKey = array_column($historyMedicineKey,'medicine');
                    if(!empty($historyMedicineKey)){
                        $historyMedicineKey = array_combine($historyMedicineKey,$historyMedicineKey);
                    }
                }
                // dd($cycleNo);
                $data = [];
                $data['iui'] = $iui;
                $data['personalData'] = $personalData;
                $data['pastData'] = $pastData;
                $data['familyData'] = $familyData;
                $data['patientsInfo'] = $patientsInfo;
                $data['medicineKey'] = $medicineKey;
                $data['historyMedicineKey'] = $historyMedicineKey;
                $data['hystroscopyImages'] = json_encode($hystroscopyImagesData);
                $data['hcgImages'] = json_encode($hcgImagesData);
                $data['laproscopyImages'] = json_encode($laproscopyImagesData);
                $data['bloodImages'] = json_encode($bloodImagesData);
                $data['usgImages'] = json_encode($usgImagesData);
                $data['hsaImages'] = json_encode($hsaImagesData);
                $data['ho'] = $ho;
                $data['co'] = $co;
                $data['mh'] = $mh;
                $data['oh'] = $oh;
                $data['visit'] = true;
                $data['hoRx'] = $hoRx;
                $data['iuiSecondVisitDate'] = $lmdData;
                $data['investigation'] = $investigation;
                $data['husbandFactor'] = $husbandFactor;
                $data['planManagement'] = $planManagement;
                $data['patientsDetailsHo'] = $patientsDetailsHo;
                $data['possibleCaseOfInfertility'] = $possibleCaseOfInfertility;
                $data['oe'] = $oe;
                $data['treatment'] = $treatment;
                $data['referenceDoctor'] = $referenceDoctor;
                $data['complaints'] = $complaints;
                $data['medicines'] = $medicines;
                $data['leftOvaryData'] = $leftOvaryData;
                $data['rightOvaryData'] = $rightOvaryData;
                $data['iuiHistoryId'] = $iuiHistoryId;
                $data['historyLmp'] = $historyLmp;
                $data['historyPlan'] = $historyPlan;
                $data['historyOe'] = $historyOe;
                $data['visitNo'] = $visitNo;
                $data['planType'] = $planType;
                $data['planData'] = $planData;
                $data['thirdDescription'] = $thirdDescription;
                $data['historyTreatment'] = $historyTreatment;
                $data['historyData'] = $historyData;
                $data['iuiSecondVisit'] = $iuiSecondVisit;
                $data['iuiSecondVisitData'] = $iuiSecondVisitData;
                $data['cycleNo'] = $cycleNo;
                $data['follicleString'] = $follicleString;
                $data['iuiThirdVisitData'] = $iuiThirdVisitData;
                $data['planOfSecondVisit'] = $planOfSecondVisit;
                $data['inducingInjectionData'] = $inducingInjectionData;
                $data['date'] = $date;
                $data['iuiReportCycleNo'] = $iuiReportCycleNo;
                $data['oldDate'] = $oldDate;
                $data['historyCo'] = $historyCo;
                $data['historyInducingDate'] = $historyInducingDate;
                $data['durationOfData'] = $durationOfData;
                $data['hoData'] = $hoData;
                $data['lastAppointmentData'] = $lastAppointmentData;
                $data['remark'] = $remark;
                $data['firstVisitLmpDate'] = $firstVisitLmpDate;
                $data['investigationReport'] = $this->allInvestigationReport();
                $data['currentdate'] = Carbon::now()->format("d-m-y");
                $data['iuiFirstVisit'] = $this->IUI->wherePatientsId($id)->orderBy('id','DESC')->first();
                $data['iuiThirdVisit'] = $iuiThirdVisit;
                $data['iuiHistoryData'] = collect($this->IuiHistory->wherePatientsId(decrypt($patientsId))->whereCycleNo($cycleNo)->get());
                $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
                $data['rmoDoctor'] = $this->User->whereRole('3')->where('is_rmo_doctor',1)->whereStatus('1')->pluck('name','id')->toArray();
                $data['iui_completed'] = $iui_completed;
                if(($request->iuihistorydate) || ($request->iui_visit_id))
                {
                    $data['update_iui'] = View::make('admin.iui.edit1',$data)->render();
                }
                else
                {
                    $data['update_iui'] = View::make('admin.iui.edit',$data)->render();
                }
                return $data;
            }
            $iuiReport = null;
            $iuiReportStatus = null;
            $iuifourthVisit = $this->IuiHistory->wherePatientsIdAndVisit($id, 4)->whereCycleNo($cycleNo)->where('cycle_status',2)->orderBy('id', 'DESC')->first();
            $iuiFirstVisitData = $this->IUI->wherePatientsId($id)->orderBy('id','DESC')->first();
            $iuiReport = $this->IUIReport->wherePatientsId($id)->whereCycleNo($cycleNo)->orderBy('id','DESC')->first();
            $iuiReportStatus = $this->IuiHistory->wherePatientsId($id)->whereCycleNo($cycleNo)->where('description->hcg->iui->status','yes')->first();
            $cycleData = $this->IUI->wherePatientsId($id)->orderBy('cycle_no','asc')->pluck('cycle_no','cycle_no')->toArray();
            $view = view('admin.iui.history',compact('medicines','patientsId','hospitalTime','date','iuiCycleNo','iuiCurrentCycleNo','iui','iuiFirstVisitData','cycleData','referenceDoctor','iuiReport','iuiReportStatus'));
            
            //display old iui visit when patients is tranfer from iui to ANC or IVf
            if(($iuifourthVisit && ($lastAppointment->category_id == 1 || $lastAppointment->category_id == 2))){
                $ivfTransfer = $this->IVF->wherePatientsId($id)->where('created_at','>=',$iuifourthVisit->created_at)->first();
                $ancTransfer = $this->ANC->wherePatientsId($id)->where('created_at','>=',$iuifourthVisit->created_at)->first();
                if((empty($ivfTransfer) && empty($ancTransfer)))
                {
                    $view = redirect('iui/create/'.encrypt($id));
                }
            }
            return $view;
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    public function getPlanData($type){
        $planData = $this->Injection->where('type',$type)->where('category',1)->whereNotNull('name')->pluck('name','name');
        return ['planData'=>$planData];
    }

    // get all injection for IUI
    private function inducingInjection(){
        $inj= ["INJ R FSH 75 (GONAREC)","INJ R FSH 150 (GONAREC)","INJ R FSH 75 (FOLLISURGE)","INJ R FSH 150 (FOLLISURGE)","INJ FSH  HP 75(FOSTINE)","INJ HMG 75 (DIVA)","INJ HMG 150 (DIVA)","INJ R FSH 50 (FOLLISURGE)","INJ R FSH 250 (FOLLISURGE)","INJ OVITROP 75 IU","INJ  R FSH 150 + HMG 75","INJ  R FSH 75 (RELLANCE)","INJ 1/2 FOLLISURGE 75","INJ FOLISTI SURE HP 150",
            "INJ GMH 75","INJ F0lisurge 125","INJ Falisurge 100","INJ PERSIONAL 150","INJ PUREGRAPH 75","INJ R FSH 225 (FOLLISURGE)","INJ R FSH 200 (FOLLISURGE)","INJ R FSH 175 (FOLLISURGE)","INJ Foligraff 150 u","INJ Foligraff 100","INJ MENOGRAM 75","INJ FOLLISURGE 50","INJ FOSTINE 75","INJ HUMOG 225","INJ MENOSAR 75","INJ MENOSAR 150","INJ MENOSAR 100","INJ PERSINAL 225",
            "INJ CENTROLIZX","INJ FOSTINE 150","INJ  R FSH 150(FOSTINE)","INJ PERSINAL 150","INJ FOLIGRAPH 75","INJ PERSINAL 75","INJ HUMOG 300","INJ HUMOG 375","INJ MENOGO 150","INJ HUMOG 150","INJ PUREGRAPH 150","INJ HUMOG 75","INJ GMH HP (SUNPHARMA)150","INJ GRAFIA R 75","MENOTAS 150",
            "MENOTAS 75","INJ FOLLISURGE 35","INJ GONARAC 35","INJ GONARAC 50","INJ FOLLISURGE 100","INJ GONARAC 100",'INJ MENOGO 75'];
        return ['inj'=>$inj];
    }

    // patient have result from 4th visit then will be show in the result page
    public function iuiResult(){
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $iuiHistoryData = collect($this->IUI->with('getPatientsInfo')->whereNotNull('follow_up')->whereCycleStatus('1')->orderBy('follow_up','asc')->get())
                            ->groupBy('follow_up')->toArray();
        return view('admin.iui.result',compact('iuiHistoryData'));
    }

    // get all cycle of patient
    private function getCylcleNumber($patientsId){
        $iuiCycleNo = $this->IUI->where('patients_id',$patientsId)->pluck('cycle_no','cycle_no')->toArray();
        $iuiHistoryCycleNo = $this->IuiHistory->where('patients_id',$patientsId)->groupBy('cycle_no')->pluck('cycle_no','cycle_no')->toArray();
        $iuiCycleNo = array_unique(array_merge($iuiCycleNo,$iuiHistoryCycleNo));
        $iuiCycleNo = array_combine($iuiCycleNo,$iuiCycleNo);
        return ['cycle_number'=>$iuiCycleNo];
    }

    // get time status to time wise and this is used in print
    private function getTimeStatus($time){
        $time = strtotime($time);
        $morningStartTime = strtotime("00:01 am");
        $morningEndTime = strtotime("11:59 am");
        $afternoonStartTime = strtotime("12:00 pm");
        $afternoonEndTime = strtotime("03:59 pm");
        $eveningStartTime = strtotime("04:00 pm");
        $eveningEndTime = strtotime("08:59 pm");
        $nightStartTime = strtotime("09:00 pm");
        $nightEndTime = strtotime("11:59 pm");
        // $nightStartTime2 = strtotime("12:00 am");
        // $nightEndTime2 = strtotime("08:59 am");
        $timeStatus = null;
        switch (true) {
            case ($time >= $morningStartTime && $time <= $morningEndTime) :
                $timeStatus = 'સવારે';
                break;
            case ($time >= $afternoonStartTime && $time <= $afternoonEndTime) :
                $timeStatus = 'બપોરે';
                break;
            case ($time >= $eveningStartTime && $time <= $eveningEndTime) :
                $timeStatus = 'સાંજે';
                break;
            // case ($time >= $nightStartTime && $time <= $nightEndTime) || ($time >= $nightStartTime2 && $time <= $nightEndTime2):
            case ($time >= $nightStartTime && $time <= $nightEndTime):
                $timeStatus = 'રાત્રે';
                break;
        }
        return ['timeStatus'=>$timeStatus];
    }

    // get IUI bill data of patient to cycle wise
    public function getIuiBillData(Request $request) {

        try {
            $patientId = decrypt($request->patient_id);
        } catch (Exception $e) {
            return [
                'status' => 0,
                'message' => 'Invalid Id'
            ];
        }

        $secondVisit = json_decode($this->IuiHistory
            ->wherePatientsIdAndCycleNoAndVisit($patientId, $request->cycle_no, 2)
            ->first()
            ->value('description'), true);

        $secondVisitInjection = [];
        if(isset($secondVisit['plan']['agenet'])) {
            $secondVisitInjection = $secondVisit['plan']['agenet'];
        }
        $allThirdVisits = $this->IuiHistory->wherePatientsIdAndCycleNoAndVisit($patientId, $request->cycle_no, $request->visit)->get();

        $injections = [];

        foreach($allThirdVisits as $allThirdVisits) {
            $description = json_decode($allThirdVisits->description, true);
            if(isset($description['plan']['inducing_agent']) && !empty($description['plan']['inducing_agent'])) {
                $injections[] = $description['plan']['inducing_agent'];
            }
        }

        if($request->current_visit_injections != null) {
            $injections[] = $request->current_visit_injections;
        }
        $inducingInjectionData = $this->inducingInjection()['inj'];
        $injections = array_count_values(array_flatten($injections));
        $injectionArray = [];
        $i = 0;
        if(!empty($injections)){
            foreach($injections as $key => $value) {
                $injectionArray[$i]['name'] = $key;
                $injectionArray[$i]['quantity'] = $value;
                $injectionArray[$i]['price'] = 0;
                $injectionArray[$i]['injection_price'] = 0;
                $i++;
            }
        }

        $finalInjection = [];
        $finalInjection = array_map(function (array $array) use ($inducingInjectionData) {
            $array['name'] = $inducingInjectionData[$array['name']];
            return $array;
        }, $injectionArray);
        if(!empty($secondVisitInjection)) {
            $i = 0;
            foreach($secondVisitInjection as $key => $value) {
                $secondVisitInjectionArray[$i]['name'] = $value;
                $secondVisitInjectionArray[$i]['quantity'] = 1;
                $secondVisitInjectionArray[$i]['price'] = 0;
                $secondVisitInjectionArray[$i]['injection_price'] = 0;
                $i++;
            }
            $finalInjection = array_merge($finalInjection, $secondVisitInjectionArray);
        }
        $injectionPrice = collect($this->InjectionCharge->get())->toArray();
        if($injectionPrice != null) {
            foreach($injectionPrice as $key => $value) {
                $nameArray = explode(' ', str_replace(['(',')'], '', strtolower($value['name'])));
                $finalInjection = array_map(function (array $array)  use ($nameArray, $value) {
                    $mustHave = explode(' ', str_replace(['(',')'], '', strtolower($array['name'])));
                    if(count(array_diff($nameArray, $mustHave)) == 0) {
                        $array['price'] = $array['quantity'] * $value['mrp'];
                        $array['injection_price'] = $value['mrp'];
                    }
                    return $array;
                }, $finalInjection);
            }
        }

        $lastHormonDeposit = $this->IndoorDeposit->wherePatientIdAndChargeType($patientId, 1)->orderBy('id', 'DESC')->value('total');
        $deposit = ($lastHormonDeposit != null) ? $lastHormonDeposit : 0;
        $patientName = $this->OpdPatients->whereId($patientId)->value('name');

        return [
            'status' => 1,
            'patient_name' => ucwords(strtolower($patientName)),
            'injections' => $finalInjection,
            'deposit' => $deposit
        ];
    }

    // get iui report of patient
    public function iuiReport($patientId,$cycleNo) {
        $cycleNo = (int)decrypt($cycleNo);
        $patientId = decrypt($patientId);
        $iuireport = $this->IUIReport->where('patients_id',$patientId)->first();
        $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
        return view('admin.iui.iuireport',compact('patientId','cycleNo','iuireport','lastAppointmentData'));

    }

    // store the IUI report to cycle wise if exist the report then update data otherwise new entry add
    public function iuiReportStore(Request $request) {
        try{
            // $iuiReport = $this->IUIReport;
            $patientId = decrypt($request->iui_report_patient_id);
            $cycle_no = decrypt($request->iui_report_cycle_no);
            $iuiReport = $this->IUIReport->where('patients_id',$patientId)->where('cycle_no',$cycle_no)->first();
            if(!$iuiReport)
            {
                $iuiReport = $this->IUIReport;
            }
            $iuiReport->patients_id = $patientId;
            $iuiReport->cycle_no = $cycle_no;
            $iuiReport->description = json_encode($request->iui_report);
            $iuiReport->save();
            $appointmentTime = null;
            $followDate = !empty($request->iui_report['follow_up']) ? date('Y-m-d',strtotime($request->iui_report['follow_up'])) : null;
            $fDate = !empty($followDate) ? Carbon::parse($followDate)->format('Y-m-d') : null;
            if($fDate)
            {
                $requestData = new \Illuminate\Http\Request();
                $requestData->replace(['date' => $fDate,'status'=>true]);
                $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                if(!empty($nextAppontment['time']) || $nextAppontment['time'] == 0){
                    $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                    $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                    $followDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $followDate;
                }
                $appointment = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                if($appointment){
                    $appointmentData['appointmentId'] = encrypt($appointment->id);
                    $appointmentData['date'] = $followDate;
                    $appointmentData['time'] = $appointmentTime;
                    $nextAppointment = $this->nextAppointmentData($appointmentData);
                }
            }
            $iuiReport = $this->IUIReport->where('patients_id',$patientId)->where('cycle_no',$cycle_no)->first();
            if($request->isprint == 2){
                $printPreview = 1;
                return response()->json([
                    'status' => 2,
                    'data' => View::make('admin.iui.iuireportprint', compact('iuiReport','printPreview'))->render()
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 1,
                    'data' => 0
                ]);
            }
        }catch(Exception $e){
            abort(500);
            log::Debug($e);
            return ['status'=>'false'];
        }
    }

    // get images for investigation tab
    private function getImagesData($reportType,$type,$id,$data){
        if($type == 'iui'){
            $iui = $this->IUI->find($id);
        }
        if($type == 'iui_history'){
            $iui = $this->IuiHistory->find($id);
        }
        if($type == 'iui_extra_visit'){
            // dd($id);
            $iui = $this->IuiExtraVisit->find($id);
        }
        if(!empty($iui->investigation)){
            $iuiInvestigation = json_decode($iui->investigation);
            if($reportType == 'hystroscopy_old'){
                $iuiData = !empty($iuiInvestigation->hystroscopy) ? $iuiInvestigation->hystroscopy : [];
                if(!empty($iuiData)){
                    $hystroscopyImages = $this->getImagesKey($iuiData,$data)['key'];
                    if(!empty($hystroscopyImages)){
                        foreach($hystroscopyImages as $row){
                            $this->removeImage($iuiData->images[$row]);
                            unset($iuiData->images[$row]);
                        }
                        $iuiArray = (array)$iuiData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->images =  $iuiArrayData;
                        $iuiInvestigation->hystroscopy = $iuiData;
                        $iui->investigation = $iuiInvestigation;
                    }
                }
            }
            if($reportType == 'laproscopy_old'){
                $iuiData = !empty($iuiInvestigation->laproscopy) ? $iuiInvestigation->laproscopy : [];
                if(!empty($iuiData)){
                    $hystroscopyImages = $this->getImagesKey($iuiData,$data)['key'];
                    if(!empty($hystroscopyImages)){
                        foreach($hystroscopyImages as $row){
                            $this->removeImage($iuiData->images[$row]);
                            unset($iuiData->images[$row]);
                        }
                        $iuiArray = (array)$iuiData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->images =  $iuiArrayData;
                        $iuiInvestigation->laproscopy = $iuiData;
                        $iui->investigation = $iuiInvestigation;
                    }
                }
            }
            if($reportType == 'hcg_old'){
                $iuiData = !empty($iuiInvestigation->hcg) ? $iuiInvestigation->hcg : [];
                if(!empty($iuiData)){
                    $hystroscopyImages = $this->getImagesKey($iuiData,$data)['key'];
                    if(!empty($hystroscopyImages)){
                        foreach($hystroscopyImages as $row){
                            $this->removeImage($iuiData->images[$row]);
                            unset($iuiData->images[$row]);
                        }
                        $iuiArray = (array)$iuiData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->images =  $iuiArrayData;
                        $iuiInvestigation->hcg = $iuiData;
                        $iui->investigation = $iuiInvestigation;
                    }
                }
            }
            if($reportType == 'blood_report_old'){
                $iuiData = !empty($iuiInvestigation->blood_report) ? $iuiInvestigation->blood_report : [];
                if(!empty($iuiData)){
                    $bloodImages = $this->getBloodImagesKey($iuiData,$data)['key'];
                    if(!empty($bloodImages)){
                        foreach($bloodImages as $row){
                            $this->removeImage($iuiData->image[$row]);
                            unset($iuiData->image[$row]);
                        }
                        $iuiArray = (array)$iuiData->image;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->image =  $iuiArrayData;
                        $iuiInvestigation->blood_report = $iuiData;
                        $iui->investigation = $iuiInvestigation;
                    }
                }
            }
            if($reportType == 'hsa_report_old'){
                $iuiData = !empty($iuiInvestigation->hsa_report) ? $iuiInvestigation->hsa_report : [];
                if(!empty($iuiData)){
                    $hsaImages = $this->getImagesKey($iuiData,$data)['key'];
                    if(!empty($hsaImages)){
                        foreach($hsaImages as $row){
                            $this->removeImage($iuiData->images[$row]);
                            unset($iuiData->images[$row]);
                        }
                        $iuiArray = (array)$iuiData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->images =  $iuiArrayData;
                        $iuiInvestigation->hsa_report = $iuiData;
                        $iui->investigation = $iuiInvestigation;
                    }
                }
            }
            $iui->investigation = json_encode($iuiInvestigation);
            $iui->save();
        }
        if($type == 'iui_history')
        {
            if($reportType == 'blood_report_old')
            {
                $iuiDescription = json_decode($iui->description);
                $iuiData = !empty($iuiDescription->blood_report) ? $iuiDescription->blood_report : [];
                if(!empty($iuiData)){
                    $blood_reportImages = $this->getBloodImagesKey($iuiData,$data)['key'];
                    if(!empty($blood_reportImages)){
                        foreach($blood_reportImages as $row){
                            $this->removeImage($iuiData->image[$row]);
                            unset($iuiData->image[$row]);
                        }
                        $iuiArray = (array)$iuiData->image;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->image =  $iuiArrayData;
                        $iuiDescription->blood_report = $iuiData;
                        $iui->description = $iuiDescription;
                    }
                }
                // dd($iuiDescription);
                
            }
            if($reportType == 'usg_old'){
                $iuiDescription = json_decode($iui->description);
                $iuiData = !empty($iuiDescription->usg) ? $iuiDescription->usg : [];
                if(!empty($iuiData)){
                    $usg_reportImages = $this->getImagesKey($iuiData,$data)['key'];
                    if(!empty($usg_reportImages)){
                        foreach($usg_reportImages as $row){
                            $this->removeImage($iuiData->images[$row]);
                            unset($iuiData->images[$row]);
                        }
                        $iuiArray = (array)$iuiData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->images =  $iuiArrayData;
                        $iuiDescription->blood_report = $iuiData;
                        $iui->description = $iuiDescription;
                    }
                }
                // $iui->description = json_encode($iuiDescription);
                // $iui->save();
            }
            if($reportType == 'hsa_report_old')
            {
                $iuiDescription = json_decode($iui->description);
                $iuiData = !empty($iuiDescription->hsa_report) ? $iuiDescription->hsa_report : [];
                if(!empty($iuiData)){
                    $hsa_reportImages = $this->getImagesKey($iuiData,$data)['key'];
                    if(!empty($hsa_reportImages)){
                        foreach($hsa_reportImages as $row){
                            $this->removeImage($iuiData->images[$row]);
                            unset($iuiData->images[$row]);
                        }
                        $iuiArray = (array)$iuiData->images;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->images =  $iuiArrayData;
                        $iuiDescription->hsa_report = $iuiData;
                        $iui->description = $iuiDescription;
                    }
                }
                // dd($iuiDescription);
                
            }
            $iui->description = json_encode($iuiDescription);
            $iui->save();
        }
        if($type == 'iui_extra_visit')
        {
            if($reportType == 'extraVisit_blood_report_old')
            {
                $iuiInvestigation = !empty($iui->oe) ? json_decode($iui->oe) : null;
                $iuiData = !empty($iuiInvestigation->blood_report) ? $iuiInvestigation->blood_report : [];
                if(!empty($iuiData)){
                    $blood_reportImages = $this->getBloodImagesKey($iuiData,$data)['key'];
                    // dd($blood_reportImages);
                    if(!empty($blood_reportImages)){
                        foreach($blood_reportImages as $row){
                            $this->removeImage($iuiData->image[$row]);
                            unset($iuiData->image[$row]);
                        }
                        $iuiArray = (array)$iuiData->image;
                        $iuiArrayData = array_values($iuiArray);
                        $iuiData->image =  $iuiArrayData;
                        $iuiInvestigation->blood_report = $iuiData;
                        $iui->oe = $iuiInvestigation;
                    }
                }
                // dd($ivf->oe);
                $iui->oe = json_encode($iuiInvestigation);
                $iui->save();
            }
        }
        return ['status'=>true];
    }
    //get images key
    private function getImagesKey($iuiData,$data){
        $imagesKey = [];
        $removedImageKey = [];
        if(!empty($iuiData->images)){
            foreach($iuiData->images as $key=>$row){
                $imagesKey[] =$key;
            }
            $removedImageKey = array_diff($imagesKey,$data);
        }
        return ['key'=>$removedImageKey];
    }
    //get blood_report image keys
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
    // store Ho data in database if not exist in table
    public function storeIUIHoData($nameData,$type){
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

    // fetch data to extra visit of IUI
    public function extraVisit(Request $request,$id,$cycleNo){
        try{
            $pId = decrypt($id);
            $iuiPatients = $this->OpdPatients->find($pId);
            $complaints = $this->Complaint->pluck('name','name');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $iuiHistoryDate = $this->IuiExtraVisit->where('patient_id',$pId)->pluck('created_at','created_at')->toArray();
            $cycle_no = decrypt($cycleNo);
            $bloodReportImages = null;
            $bloodReportImagesData = [];
            $bloodReportImagesArray = [];
            $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $rmoDoctor = $this->User->whereRole('3')->where('is_rmo_doctor',1)->whereStatus('1')->pluck('name','id')->toArray();
            if($request->ajax()){
                $iuiHistoryData = null;
                $date = $request->date;
                
                $status = 0;
                if($date){
                    $iuiHistoryData = $this->IuiExtraVisit->where('created_at',$date)->first();
                    if($iuiHistoryData){
                        $oe = json_decode($iuiHistoryData->oe);
                        $bloodReportImages = !empty($oe->blood_report->image) ? $oe->blood_report->image : null;
                        if($bloodReportImages){
                            foreach($bloodReportImages as $key=>$row){
                                $bloodReportImagesData[$key]['id'] = $key;
                                $bloodReportImagesData[$key]['src'] = url($row);
                            }
                            
                        }
                        $status = 1;
                    }
                }
                $bloodReportImagesArray = json_encode($bloodReportImagesData,true);
                $data['status'] = 1;
                $data['extra_visit_data'] = View::make('admin.iui.extra_visit_data',compact('hospitalDoctor','rmoDoctor','bloodReportImagesArray','iuiHistoryData','complaints','leftOvaryData','rightOvaryData','medicines','iuiPatients'))->render();
                return $data;
            }
            return view('admin.iui.extra_visit',compact('iuiPatients','iuiHistoryDate','medicines','cycle_no'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    // all extra visit store in this function
    public function storeExtraVisit(Request $request){
        try{
            $patientId = decrypt($request->patient_id);
            $cycle_no = decrypt($request->cycle_no);
            $iuiPatients = $this->OpdPatients->find($patientId);
            if(!empty($request->oe['ovary']['right']['details']) || !empty($request->oe['ovary']['left']['details'])){
                $rightData = !empty($request->oe['ovary']['right']['details']) ? $request->oe['ovary']['right']['details'] : [];
                $leftData = !empty($request->oe['ovary']['left']['details']) ? $request->oe['ovary']['left']['details'] : [];
            }
            if(!empty($leftData)){
                $data = array_unique($leftData);
                addOvaryAbnormalData($data,1);
            }
            if(!empty($rightData)){
                $data = array_unique($rightData);
                addOvaryAbnormalData($data,2);
            }
            $this->complaintStore($request->co);
            // if(!empty($request->treatment['medicinedata'])){
            //     $this->medicineData($request->treatment['medicinedata']);
            //     $this->treatmentData($request->treatment);
            // }
            $iuiExtraVisit = $this->IuiExtraVisit;
            $bloodReportOldImages = [];
            $bloodReport = [];
            if($request->iui_extra_visit_id){
                $iuiExtraVisit = $this->IuiExtraVisit->find(decrypt($request->iui_extra_visit_id));
                if($iuiExtraVisit)
                {
                    $oe = !empty($iuiExtraVisit->oe) ? json_decode($iuiExtraVisit->oe) : null;
                    $this->getImagesData('extraVisit_blood_report_old','iui_extra_visit',$iuiExtraVisit->id,$request->extraVisit_blood_report_old ? $request->extraVisit_blood_report_old : [-1]);
                    $bloodReportOldImages = !empty($oe->blood_report->image) ? (array)$oe->blood_report->image : [];
                }
            }
            $iuiExtraVisitOe = $request->oe;
            if(!empty($request->oe['blood_report']['image'])){
                foreach($request->oe['blood_report']['image'] as $key=>$row){
                    $name = $this->uploadImage($row, 'public/upload/iui/blood/');
                    $bloodReport[] = 'public/upload/iui/blood/' . $name;
                }
                $iuiExtraVisitOe['blood_report']['image'] = array_merge($bloodReport,$bloodReportOldImages);
            }
            else{
                $iuiExtraVisitOe['blood_report']['image'] = $bloodReportOldImages;
            }
            $iuiExtraVisit->patient_id = $patientId;
            $iuiExtraVisit->seen_by = $request->seen_by;
            $iuiExtraVisit->rmo_doctor = $request->rmo_doctor;
            $iuiExtraVisit->cycle_no = $cycle_no;
            $iuiExtraVisit->co = json_encode($request->co);
            $iuiExtraVisit->lmp = json_encode($request->lmp);
            $iuiExtraVisit->oe = json_encode($iuiExtraVisitOe);
            $iuiExtraVisit->treatment = json_encode($request->treatment);
            $iuiExtraVisit->save();

            $now = Carbon::now()->format('Y-m-d');
            if(!$request->iui_extra_visit_id)
            {
                $appointmentFlag = $this->Appointment->wherePatientsId($patientId)->where('date',$now)->update(['is_done'=>1,'seen_by'=>$request->seen_by]);
                $updateConsulting = $this->Appointment->wherePatientsId($patientId)->where('date',$now)->update(['in_consulting_room'=>0]);
            }
            $followupDate = !empty($request->oe['follow_up']) ? $request->oe['follow_up'] : null;
            $appointmentTime = null;
            $followDate = !empty($followupDate) ? date('Y-m-d',strtotime($followupDate)) : null;
            $fDate = !empty($followDate) ? Carbon::parse($followDate)->format('Y-m-d') : null;
            if($fDate){
                
                $requestData = new \Illuminate\Http\Request();
                $requestData->replace(['date' => $fDate,'status'=>true]);
                $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                if(!empty($nextAppontment['time']) || $nextAppontment['time'] == 0){
                    $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                    $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                    $followDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $followDate;
                }
                $appointment = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                if($appointment){
                    $appointmentData['appointmentId'] = encrypt($appointment->id);
                    $appointmentData['date'] = $followDate;
                    $appointmentData['time'] = $appointmentTime;
                    $nextAppointment = $this->nextAppointmentData($appointmentData);
                }
            }
            $isExtraVisit = 1;
            if($request->isprint == 1)
            {
                return [
                    'status'=>2,
                    'id'=>$iuiExtraVisit->id,
                    'preview' => View::make('admin.iui.preview',compact('iuiExtraVisit','iuiPatients','isExtraVisit'))->render()
                ];

            }
            else{
                Session::flash('msg','Record has been successfully added.');
                return ['status'=>1,'id'=>$iuiExtraVisit->id];
            }
            
        }catch(Exception $e)
        {
            log::Debug($e);
            abort(500);
        }
    }

    // this function is use for generate file view of all visit in IUI
    public function getIuiDetails(Request $request){
        try{
            $inducingInjectionData = $this->inducingInjection()['inj'];
            $investigationReport = $this->allInvestigationReport();
            $currentdate = Carbon::now()->format("d-m-y");
            $iuiFirstVisit = null;
            $iuiSecondVisit = null;
            $iuiThirdVisit = null;
            $iuiHistoryData = null;
            if($request->ajax()){
                $patientId = decrypt($request->patient_id);
                $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                // $iuiHistorylastVisit = $this->IuiHistory->where('patients_id',$patientId)->orderBy('id','asc')->first();
                $type = 1;
                $cycle = $request->cycle_no;
                $iuiAllData = $this->IUI->where('patients_id',$patientId)->orderBy('id','ASC')->get();
                // $date = $request->appointment_date;
                $historyDate = $request->history_date;
                $iuiPatients = $this->OpdPatients->find($patientId);
                $viewAllVisit = [];
                $dateValue = [];
                $table_view = [];
                $extraVisit = [];
                $extraVisitDisplay = false;
                $isAppointmentView = false;
                // dd($request->cycle_no);
                if(isset($request->cycle_no) && !empty($request->cycle_no))
                {
                    $iuiVisitDate = [];
                    if($request->is_appointmentView && $request->is_appointmentView == 1)
                    {
                        $isAppointmentView = true;
                    }
                        $iuiHistoryDate = $this->IuiHistory->where('patients_id',$patientId)->orderBy('created_at','DESC')->where('cycle_no',$cycle)->pluck('cycle_no','created_at')->toArray();
                        $iuiDateData = $this->IUI->where('patients_id',$patientId)->orderBy('created_at','DESC')->where('cycle_no',$cycle)->first();
                        $iuiDate = [Carbon::parse($iuiDateData->created_at)->format('Y-m-d H:i:s')=>$cycle];
                        
                        $iuiVisitDate = array_merge($iuiHistoryDate,$iuiDate);
                        $iuiFirstVisit = $iuiDateData;
                        
                        $iuiHistoryData = collect($this->IuiHistory->wherePatientsId($patientId)->whereCycleNo($cycle)->get());
                        $iuiSecondVisit = $iuiHistoryData->where('visit',2)->first();
                        if($iuiSecondVisit){
                            $iuiSecondVisit = json_decode($iuiSecondVisit->description);
                        }
                        // dd($iuiVisitDate);
                    
                }
                //all IUI cycle wise
                else
                {
                    $iuiVisitDate = [];
                    $iuiDateData = $this->IUI->where('patients_id',$patientId)->first();
                    $iuiDate = [Carbon::parse($iuiDateData->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($iuiDateData->created_at)->format('Y-m-d H:i:s')];
                    $iuiVisitDate = array_merge($iuiDate,$iuiVisitDate);
                     $iuiFirstVisit = $iuiDateData;
                    foreach($iuiAllData as $key => $iui_all_data)
                    {
                        $iuiHistoryDate = $this->IuiHistory->where('patients_id',$patientId)->orderBy('created_at','DESC')->where('cycle_no',$iui_all_data->cycle_no)->pluck('cycle_no','created_at')->toArray();
                        // $iuiDateData = $this->IUI->where('patients_id',$patientId)->orderBy('created_at','DESC')->where('cycle_no',$iui_all_data->cycle_no)->first();
                        // $iuiDate = [Carbon::parse($iuiDateData->created_at)->format('Y-m-d H:i:s')=>$iui_all_data->cycle_no];
                        // $iuiVisits = array_merge($iuiHistoryDate,$iuiDate);
                        $iuiVisitDate = array_merge($iuiHistoryDate,$iuiVisitDate);
                        // $iuiFirstVisit = $iuiDateData;
                        
                    }
                    
                    // dd($iuiVisitDate);
                }
                
                if($historyDate)
                {
                    $iuiType = 2;
                    $iuiData = $this->IUI->where('patients_id',$patientId)->where('created_at','=',$historyDate)->first();
                    if(!$iuiData){
                        $iuiData = $this->IuiHistory->where('patients_id',$patientId)->where('created_at','=',$historyDate)->first();
                        if($iuiData && $iuiData->visit > 2)
                        {
                            $iuiData->study_report = true;
                        }
                    }
                    $iui = $iuiData;
                    if(!empty($request->extra_visit))
                    {
                        $isExtraVisit = 1;
                        $iuiExtraVisit = $this->IuiExtraVisit->where('patient_id',$patientId)->where('created_at','=',$historyDate)->first();
                        $viewAllVisit[] =  View::make('admin.iui.preview', compact('iuiPatients','iuiExtraVisit','isExtraVisit'))->render();
                    }
                    else
                    {
                        $viewAllVisit[] =  View::make('admin.iui.preview', compact('iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport'))->render();
                    }
                    
                }
                else{
                    $preview = 0;
                    $isTable_view = false;
                    $isAppointmentView = true;
                    $displayCycle = 0;
                    foreach($iuiVisitDate as $key => $value)
                    {
                        $iuiType = 1;
                        $iuiExtra = null;
                        $isExtraVisit = 0;
                        
                        $iuiHistoryData = collect($this->IuiHistory->wherePatientsId($patientId)->whereCycleNo($value)->get());
                        $iuiSecondVisit = $iuiHistoryData->where('visit',2)->first();
                        if($iuiSecondVisit){
                            $iuiSecondVisit = json_decode($iuiSecondVisit->description);
                        }
                        $iuiData = $this->IUI->where('patients_id',$patientId)->where('created_at','=',$key)->first();
                        if($iuiData)
                        {
                            $preview = 0;
                            $isTable_view = false;
                            
                        }
                        if(empty($iuiData))
                        {
                            $iuiData = $this->IuiHistory->where('patients_id',$patientId)->where('cycle_no',$value)->where('created_at','=',$key)->orderBy('id','DESC')->first();
                            if($displayCycle != $value)
                            {
                                $preview = 0;
                            }
                            if($iuiData && ($iuiData->visit == 3 || $iuiData->visit == 4 || $iuiData->visit == 2))
                            {
                                $displayCycle = $value;
                                $iuiData->study_report = true;
                                $isTable_view = true;
                                $preview++;
                            }
                        }
                        $iuiThirdVisit = $this->IuiHistory->wherePatientsId($patientId)->where('cycle_no',$value)->where('created_at',$key)->where('visit',3)->where('description->ovalution','yes')->first();
                        if($iuiThirdVisit){
                            $iuiThirdVisit = json_decode($iuiThirdVisit->description);
                        }
                        $iui = $iuiData;
                        //find extra visit after 1st visit
                        $firstVisit = $this->IUI->where('patients_id',$patientId)->where('cycle_no',$value)->where('created_at','=',$key)->first();
                        if($firstVisit)
                        {
                            $iuiExtra = $this->IuiExtraVisit->where('patient_id',$patientId)->where('cycle_no',$value)->where('created_at','>',$firstVisit->created_at)->get();
                            if(!empty($iuiExtra))
                            {
                                foreach($iuiExtra as $iuiExtraVisit)
                                {
                                    $isExtraVisit = 1; 
                                    $isTable_view = false;
                                    $iui->study_report = false;
                                    $viewAllVisit[] =  View::make('admin.iui.preview', compact('iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport','iuiExtraVisit','iuiPatients','isExtraVisit','isAppointmentView'))->render();
                                    $dateValue[] = $iuiExtraVisit->created_at;
                                    $table_view[] = $isTable_view;
                                    $extraVisit[] = 1;
                                }
                            }
                        }
                        if($preview == 1 || $preview == 0) //display only one time  table view
                        {
                            $viewAllVisit[] =  View::make('admin.iui.preview', compact('iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport','isAppointmentView'))->render();
                            $dateValue[] = $key;
                            $table_view[] = $isTable_view;
                            $extraVisit[] = 0;
                        }
                        //for add extra visit after ovalution start
                        
                        $lastThirdVisit = $this->IuiHistory->wherePatientsId($patientId)->where('cycle_no',$value)->where('visit',3)->where('created_at','=',$key)->where('description->ovalution','yes')->first();
                        if($lastThirdVisit)
                        {
                            $iuiExtra = $this->IuiExtraVisit->where('patient_id',$patientId)->where('cycle_no',$value)->where('created_at','>',$lastThirdVisit->created_at)->get();
                            if(!empty($iuiExtra))
                            {
                                foreach($iuiExtra as $iuiExtraVisit)
                                {
                                    $isExtraVisit = 1; 
                                    $isTable_view = false;
                                    $iui->study_report = false;
                                    $viewAllVisit[] =  View::make('admin.iui.preview', compact('iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport','iuiExtraVisit','iuiPatients','isExtraVisit','isAppointmentView'))->render();
                                    $dateValue[] = $iuiExtraVisit->created_at;
                                    $table_view[] = $isTable_view;
                                    $extraVisit[] = 1;

                                }
                                
                            }
                        }
                        
                    }
                }
                
                
                return response()->json([
                    'status' => 1,
                    // 'visit' => $iui->visit ? $iui->visit : 1,
                    // 'cycle' => $iui->cycle_no,
                    'type' => $type,
                    'iui_type' => $iuiType,
                    'date' => $dateValue,
                    'table_view' => $table_view,
                    'extraVisit' => $extraVisit,
                    // 'id' => $iuiData->id,
                    'data' => $viewAllVisit
                ]);
            }else{
                // this code is use for api to generet report
                $investigationReport = $this->allInvestigationReport();
                $historyDate = $request->date;
                $patients_remark = null;
                $patientId = decrypt($request->patient_id);
                $iuiPatients = $this->OpdPatients->find($patientId);
                $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                $iuiData = $this->IUI->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                $iuiFirstVisit = $this->IUI->wherePatientsId($patientId)->first();
                $getcycleNo = $this->IuiHistory->wherePatientsId($patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                $patient_view = 1;
                if($iuiData)
                {
                    $patientInfo = json_decode($iuiData->patients_info);
                    $patients_remark = !empty($patientInfo) ? $patientInfo->remark : '';
                }
                if($getcycleNo)
                {
                    $description = json_decode($getcycleNo->description);
                    $patients_remark = !empty($description) && isset($description->pt_remark) ? $description->pt_remark : '';
                    $iuiHistoryData = collect($this->IuiHistory->wherePatientsId($patientId)->whereCycleNo($getcycleNo->cycle_no)->get());
                    $iuiSecondVisit = $iuiHistoryData->where('visit',2)->first();
                }
                
                    
                    if($iuiSecondVisit){
                        $iuiSecondVisit = json_decode($iuiSecondVisit->description);
                    }
                    
                    $iuiThirdVisit = $this->IuiHistory->wherePatientsId($patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->where('visit',3)->where('description->ovalution','yes')->first();
                    if($iuiThirdVisit){
                        $iuiThirdVisit = json_decode($iuiThirdVisit->description);
                        
                    }
                    // $iui->study_report = true;
                    //if 2 report 
                // if($iuiData && $request->is_history == 1)
                // {
                //     $iuiData = $this->IuiHistory->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                //     if($iuiData && $iuiData->visit == 3){
                //         $iuiData->study_report = true;
                //     }
                // }
                if(!$iuiData || $request->is_history == 1){
                    $iuiData = $this->IuiHistory->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                    if($iuiData && $iuiData->visit == 3){
                        $iuiData->study_report = true;
                    }
                }
                $isExtraVisit = 0;
                $iuiExtraVisit = null;
                if($request->is_extraVisit == 1)
                {
                    $isExtraVisit = 1;
                    $iuiExtraVisit = $this->IuiExtraVisit->where('patient_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                    $oe = !empty($iuiExtraVisit) ? json_decode($iuiExtraVisit->oe) : null;
                    $patients_remark = !empty($oe) && isset($oe->pt_remark) ? $oe->pt_remark : '';
                }
                if(!$iuiData && empty($iuiExtraVisit)){
                    return 'no record available';
                }
                $iui = $iuiData;
                $printPreview = 1;
                return view('admin.iui.preview', compact('patient_view','iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport','printPreview','patients_remark','isExtraVisit','iuiExtraVisit','iuiPatients','isAppointmentView'));
            }
        }catch(Exception $e){
            log::Debug($e);
            return ['status'=>2];
        }
    }

    /**
     * delete IUI History
     */
    public function iuiHistoryDelete(Request $request)
    {
        $iuiHistoryId = decrypt($request->id);
        try{
            $IuiHistory = $this->IuiHistory->find($iuiHistoryId);
            $IuiHistory->delete();
            return response()->json([
                'success' => true,
                'message'   => 'Deleted successfully'
            ]);
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return response()->json([
                'success' => false,
                'message'   => 'Something Went Wrong. Please try again!'
            ]);
        }
    }
     /**
     * get Report of IUI history
     */
    public function getIuiHistoryReport(Request $request ,$id)
    {
        try
        {
            $id = decrypt($id);
            $iui = $this->IuiHistory->where('id',$id)->first();
            $iuiHistory = $this->IuiHistory->where('patients_id',$iui->patients_id)->whereCycleNo($iui->cycle_no)->get();
            
            foreach($iuiHistory as $iuiData)
            {
                $date = carbon::parse($iuiData->created_at)->format('d M Y H:i');
                $description = !empty($iuiData->description) ? json_decode($iuiData->description) : null;
                $data[$date]['blood_report'] = !empty($description->blood_report) && !empty($description->blood_report->image)  ? (array)$description->blood_report->image : [];
                $data[$date]['usg'] = !empty($description->usg) && !empty($description->usg->images)  ? (array)$description->usg->images : [];
                $data[$date]['hsa_report'] = !empty($description->hsa_report) && !empty($description->hsa_report->images)  ? (array)$description->hsa_report->images : [];
            } 
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
    /**
    * Update FollowUd date of iui history
    */
    public function updateFollowUp(Request $request)
    {
        try{
            $iui_id = $request->iui_id;
            $newDate = \Carbon\Carbon::parse($request->followUP)->format('d-m-Y');
            $iui = $this->IuiHistory->find($iui_id);
            
            $iuiData = json_decode($iui->description);
            if($iui->visit == 2)
            {
                $iuiData->plan->follow_up = $newDate;
            }
            $iuiData->new_follow_up = $newDate;
            $iuiData->follow_up = $newDate;
            $iui->description = json_encode($iuiData);
            $iui->save();
            return ['status'=>true];
        }catch(Exception $e){
            return [
                'status' => false,
                'message' => 'Internal server error'
            ];
        }
    }
    
    /**
     * return all appointment wise iui view
     * @return  view
     * @param 
     */
    public function getIuiAppointmentWiseVisit($historyDate,$patient_id,$cycleNo,$preview,$category)
    {
        $isTable_view = false;
        $isAppointmentView = true;
        $displayCycle = 0;
        $cycleNo = decrypt($cycleNo);
        $patientId = decrypt($patient_id);
        $inducingInjectionData = $this->inducingInjection()['inj'];
        $investigationReport = $this->allInvestigationReport();
        $currentdate = Carbon::now()->format("d-m-y");
        $iuiFirstVisit = null;
        $iuiSecondVisit = null;
        $iuiThirdVisit = null;
        $iuiHistoryData = null;
        $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
        $isExtraVisit = 0;
        $iuiFirstVisit = $this->IUI->wherePatientsId($patientId)->orderBy('created_at','desc')->first();
        $iuiPatients = $this->OpdPatients->find($patientId);
        $iuiHistoryData = collect($this->IuiHistory->wherePatientsId($patientId)->whereCycleNo($cycleNo)->get());
        $iuiSecondVisit = $iuiHistoryData->where('visit',2)->first();
        if($iuiSecondVisit){
            $iuiSecondVisit = json_decode($iuiSecondVisit->description);
        }
        $iuiData = $category == 3 ? $this->IUI->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i'))"),$historyDate)->first() : null;
        if($iuiData)
        {
            $isTable_view = false;
        }
        if(empty($iuiData))
        {
            $iuiData = $this->IuiHistory->where('patients_id',$patientId)->where('cycle_no',$cycleNo)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i'))"),$historyDate)->orderBy('id','DESC')->first();
            if($iuiData && $iuiData->cycle_no != $cycleNo)
            {
                $preview = 0;
            }
            if($iuiData && ($iuiData->visit == 3 || $iuiData->visit == 4 || $iuiData->visit == 2))
            {
                $iuiData->study_report = true;
                $isTable_view = true;
            }
        }
        $iuiThirdVisit = $this->IuiHistory->wherePatientsId($patientId)->where('cycle_no',$cycleNo)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i'))"),$historyDate)->where('visit',3)->where('description->ovalution','yes')->first();
        if($iuiThirdVisit){
            $iuiThirdVisit = json_decode($iuiThirdVisit->description);
        }
        $iui = $iuiData;
        $iuiExtraVisit = $this->IuiExtraVisit->where('patient_id',$patientId)->where('cycle_no',$cycleNo)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i'))"),$historyDate)->first();
        if(!empty($iuiExtraVisit))
        {
            $preview = 0;
            $isExtraVisit = 1; 
            $isTable_view = false;
            return View::make('admin.iui.preview', compact('iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport','iuiExtraVisit','iuiPatients','isExtraVisit','isAppointmentView'))->render();
        }
        $iuiReport = $this->IUIReport->where('patients_id',$patientId)->where('cycle_no',$cycleNo)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i'))"),$historyDate)->first();
        if($iuiReport)
        {
            return View::make('admin.iui.iuireportprint', compact('iuiReport'))->render();
        }
        if($preview == 1 || $preview == 0) //display only one time  table view
        {
            return View::make('admin.iui.preview', compact('iui', 'inducingInjectionData','currentdate','lastAppointmentData','iuiFirstVisit','iuiSecondVisit','iuiThirdVisit','iuiHistoryData','investigationReport','isAppointmentView'))->render();
        }
    }
}

