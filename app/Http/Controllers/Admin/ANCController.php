<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Validation;
use Exception;
use Session;
use View;
use Auth;
use File;
use PDF;
use Log;
use DB;

class ANCController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Dispaly only ANC Appointment and not completed ANC Appointment
     * Request parameter:  usg, date range, patient, isprint
     * here serch functionality is working on usg type, appointment date and patient wise
     */
    public function index(Request $request)
    {
        try{
            $patients = $this->getPatients();
            if($request->ajax()){

                $appointment = $this->Appointment->where('is_procedure',0)->where('is_done','0')->whereIn('category_id',['5','6'])->orderBy('id','DESC');
                // search text
                $patientId = $request->patient_id;
                if($patientId){
                    $appointment = $appointment->where(function($query) use($patientId){
                        $query->whereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->where('id', $patientId);
                        });
                    });
                }

                if(!$request->usg && $request->date){
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

                if($request->usg && $request->date){
                    $type = null;
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');

                    switch ($request->usg) {
                        case '1':
                            $type = 'usg->early_scan';
                            break;
                        case '2':
                            $type = 'usg->nt_scan';
                            break;
                        case '3':
                            $type = 'usg->anomalies_miles';
                            break;
                        case '4':
                            $type = 'usg->growth_scan';;
                            break;
                        default:
                            break;
                    }
                    $ancPatientsId = $this->ANC->where($type,'>=',$startDate)->where($type,'<=',$endDate)->pluck('patients_id')->toArray();
                    $ancHiatoryPatientsId = $this->AncHistory->where($type,'>=',$startDate)->where($type,'<=',$endDate)->pluck('patients_id')->toArray();
                    $patientsId = array_merge($ancPatientsId,$ancHiatoryPatientsId);
                    $appointment = $appointment->whereIn('patients_id',$patientsId);
                }
                if($request->isprint == 1){
                    $appointment = $this->Appointment->where('is_done','0')->whereIn('category_id',['5','6'])->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['anc'] = View::make('admin.anc.print',compact('appointment'))->render();
                    return $data;
                }
                $patient_notification = $this->patientNotification->first();
                $appointment = $appointment->paginate(100);
                $data['status'] = 1;
                $data['anc'] = View::make('admin.anc.data',compact('appointment', 'patient_notification'))->render();
                return $data;
            }

            return view('admin.anc.index', compact('patients'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * store anc all visit of appointment data
     * here two table use for store data that is anc and anc_history
     */
    public function store(Request $request)
    {
        try{
            $urlValue = null;
            $isANCStatus = 1;
            $iuiIvfStatus = 0;
            $isGynecStatus = 0;
            $ancData = null;
            $ancHistory = null;
            $isNextAppointment = null;
            $nextAppointmentDate = null;
            $ancHistoryCreatedAtDate = null;
            $ancCreatedAtDate = null;
            $lmdDate = null;
            $eddDate = null;
            $usgEddDate = null;
            $isFirstVisit = false;
            $howMuchNumber = !empty($request['oe']['how_much']['number']) ? $request['oe']['how_much']['number'] : null;
            $patientsId = decrypt($request->patients_id);
            $appointmentTime = null;
            $nextFollowDate = null;
            $fDate = !empty($request->oe['follow_up']) ? Carbon::parse($request->oe['follow_up'])->format('Y-m-d') : null;
            if($request->visit == 2){
                $fDate = !empty($request->data['plan']['follow_up']) ? $request->data['plan']['follow_up'] : null;
            }
            if($fDate){
                $requestData = new \Illuminate\Http\Request();
                $requestData->replace(['date' => $fDate,'status'=>true]);
                $nextAppontment = app('App\Http\Controllers\Admin\AppointmentController')->nextAppointment($requestData);
                if(!empty($nextAppontment['time']) || (!empty($nextAppontment['time']) || $nextAppontment['time'] == 0)){
                    $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
                    $appointmentTime = $nextAppontment['time'] || $nextAppontment['time'] == 0 ? $hospitalTime[$nextAppontment['time']] : null;
                    $nextFollowDate = !empty($nextAppontment['date']) ? $nextAppontment['date'] : $fDate;
                }
            }
            // in anc table store data
            

            if(!empty($request->ho['ho_details'])){
                $this->hoData($request->ho['ho_details']);
            }

            if($request->appointment_id){
                $appointmentId = decrypt($request->appointment_id);
                $appointment = $this->Appointment->find($appointmentId);
                if($request->category != 5 || $request->category != 6){
                    $appointment->category_id = "5";
                }else{
                    $appointment->category_id = $request->category;
                }
                // $appointment->category_id = $request->category;
                $appointment->save();
                $patientsCategory = $this->PatientsCategory;
                $patientsCategory->patients_id = $patientsId;
                $patientsCategory->appointment_id = $appointment->id;
                if($request->category != 5 || $request->category != 6){
                    $patientsCategory->category_id = "6";
                }else{
                    $patientsCategory->category_id = $request->category;
                }
                // $patientsCategory->category_id = $request->category;
                $patientsCategory->save();
            }
            $data['early_scan'] = !empty($request->usg['early_scan']) ? Carbon::parse($request->usg['early_scan'])->format('Y-m-d') : null;
            $data['nt_scan'] = !empty($request->usg['nt_scan']) ? Carbon::parse($request->usg['nt_scan'])->format('Y-m-d') : null;
            $data['anomalies_miles'] = !empty($request->usg['anomalies_miles']) ? Carbon::parse($request->usg['anomalies_miles'])->format('Y-m-d') : null;
            // die($request->investigation['other_report']['other_images']);
            $data['growth_scan'] = !empty($request->usg['growth_scan']) ? Carbon::parse($request->usg['growth_scan'])->format('Y-m-d') : null;
            if(!empty($request['p_detailes']['personal_history_history_type'])){
                $this->storeAncHoData($request['p_detailes']['personal_history_history_type'],1);
            }
            if(!empty($request['p_detailes']['past_history_type'])){
                $this->storeAncHoData($request['p_detailes']['past_history_type'],2);
            }
            if(!empty($request['p_detailes']['family_history'])){
                $this->storeAncHoData($request['p_detailes']['family_history'],3);
            }
            if($request->anc_history_type == 'anc'){
                $isFirstVisit = true;
                if(!empty($howMuchNumber)){
                    $validChild = $this->checkChild($request->how_much_number_data,$howMuchNumber);
                    if($validChild){
                        return ['status'=>2];
                    }
                }
                $anc = $this->ANC;
                if($request->anc_id){
                    $anc = $this->ANC->where('id', decrypt($request->anc_id))->first();
                }
                if(in_array($request->category,[1,2])){
                    $anc = $this->IVF->where('patients_id',$patientsId)->first();
                    if(!$anc){
                        $anc = $this->IVF;
                    }
                    $isANCStatus = 0;
                    $iuiIvfStatus = 1;
                    $urlValue = url('ivf/history/').encrypt($patientsId);
                }
                if(in_array($request->category,[17,18])){
                    $anc = $this->Gynec->where('patients_id',$patientsId)->first();
                    if(!$anc){
                        $anc = $this->Gynec;
                    }
                    $isANCStatus = 0;
                    $isGynecStatus = 1;
                    $urlValue = url('gynec/history/').encrypt($patientsId);
                }
                if(in_array($request->category,[3,4])){
                    $anc = $this->IUI->where('patients_id',$patientsId)->first();
                    if(!$anc){
                        $anc = $this->IUI;
                    }
                    $anc->cycle_no = 1;
                    $anc->cycle_status = 1;
                    $isANCStatus = 0;
                    $iuiIvfStatus = 1;
                    $urlValue = url('iui/history/').encrypt($patientsId);
                }
                if($isGynecStatus == 0){
                    $anc->patients_info = json_encode($request->p_info);
                    $anc->h_o = json_encode($request->ho);
                    $anc->c_o = json_encode($request->co);
                }
                if($iuiIvfStatus == 1){
                    $anc->o_h = json_encode($request->p_obstratics);
                }
                if($isGynecStatus == 1){
                    $anc->ho = json_encode($request->ho);
                    $anc->co = json_encode($request->co);
                }
                if($isANCStatus == 1){
                    $anc->patients_obstratics = json_encode($request->p_obstratics);
                    $anc->past_history = json_encode($request->past_history);
                    $oeData = $request->oe;
                    $oeData['new_follow_up'] = Carbon::parse($request->oe['follow_up'])->format('Y-m-d');
                    $investigationData = $request->investigation;
                }
                $usgData = $request->usg;
                $ancImages = [];
                $earlyScanTypeImages = [];
                $otherImages = [];
                $growthReportImages = [];
                if($request->anc_id){
                        $this->getImagesData('usg_old','anc',decrypt($request->anc_id),$request->usg_old ? $request->usg_old : [-1]);
                    // if(empty($request['investigation']['investigation_early_scan_type']['images'])){
                        $this->getImagesData('early_old','anc',decrypt($request->anc_id),$request->early_old ? $request->early_old : [-1]);
                    // }
                    // if(empty($request['investigation']['anc']['images'])){
                        $this->getImagesData('anc_old','anc',decrypt($request->anc_id),$request->anc_old ? $request->anc_old : [-1]);
                    // }
                    // if(empty($request['investigation']['growth_report']['images'])){
                        $this->getImagesData('growth_old','anc',decrypt($request->anc_id),$request->growth_old ? $request->growth_old : [-1]);
                    // }
                    // if(empty($request['investigation']['other_report_data']['images'])){
                        $this->getImagesData('other_old','anc',decrypt($request->anc_id),$request->other_old ? $request->other_old : [-1]);
                    // }
                }
                $oldInvestigationData = [];
                $earlyOldImages = [];
                $ancOldImages = [];
                $growthOldImages = [];
                $otherOldImages = [];
                $usgOldImages = [];
                if($request->anc_id){
                    $newAnc = $this->ANC->where('id', decrypt($request->anc_id))->first();
                    $oldUsgData = json_decode($newAnc->usg);
                    if(!empty($newAnc->investigation)){
                        $oldInvestigationData = json_decode($newAnc->investigation);
                        if(!empty($oldInvestigationData)){
                            $earlyOldImages = !empty($oldInvestigationData->investigation_early_scan_type->images) ? (array)$oldInvestigationData->investigation_early_scan_type->images : [];
                            $ancOldImages = !empty($oldInvestigationData->anc->images) ? (array)$oldInvestigationData->anc->images : [];
                            $growthOldImages = !empty($oldInvestigationData->growth_report->images) ? (array)$oldInvestigationData->growth_report->images : [];
                            $otherOldImages = !empty($oldInvestigationData->other_report_data->images) ? (array)$oldInvestigationData->other_report_data->images : [];
                        }
                    }
                    if(!empty($oldUsgData)){
                        $usgOldImages = !empty($oldUsgData->images) ? (array)$oldUsgData->images : [];
                    }
                }

                // dump($request['investigation']['other_report_data']['images']);
                // dd($otherOldImages);
                if(!empty($request['investigation']['investigation_early_scan_type']['images'])){
                    foreach($request['investigation']['investigation_early_scan_type']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $earlyScanTypeImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['investigation_early_scan_type']['images'] = array_merge($earlyScanTypeImages,$earlyOldImages);
                }else{
                    $investigationData['investigation_early_scan_type']['images'] = $earlyOldImages;
                }
                if(!empty($request['investigation']['anc']['images'])){
                    foreach($request['investigation']['anc']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $ancImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['anc']['images'] = array_merge($ancImages,$ancOldImages);
                }else{
                    $investigationData['anc']['images'] = $ancOldImages;
                }
                if(!empty($request['investigation']['growth_report']['images'])){
                    foreach($request['investigation']['growth_report']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $growthReportImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['growth_report']['images'] = array_merge($growthReportImages,$growthOldImages);
                }else{
                    $investigationData['growth_report']['images'] = $growthOldImages;
                }
                if(!empty($request['investigation']['other_report_data']['images'])){
                    foreach($request['investigation']['other_report_data']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $otherImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['other_report_data']['images'] = array_merge($otherImages,$otherOldImages);
                }else{
                    $investigationData['other_report_data']['images'] = $otherOldImages;
                }
                if(!empty($request['usg']['images'])){
                    foreach($request['usg']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $usgImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $usgData['images'] = array_merge($usgImages,$usgOldImages);
                }else{
                    $usgData['images'] = $usgOldImages;
                }
                if(isset($oeData['how_much']['type']) && $oeData['how_much']['type'] == 'yes' && isset($oeData['fefal_reduction']['how_much_value']) && !empty($oeData['fefal_reduction']['how_much_value'])) {
                    $j = $oeData['oe_no'];
                    for($i = 1; $i <= $oeData['fefal_reduction']['how_much_value']; $i++) {
                        unset($oeData['utdata'][$j]);
                        $j--;
                    }
                }
                $mhData = $request->mh;
                $pastDurationDay = !empty($request['mh']['past_duration_of_day']) ? $request['mh']['past_duration_of_day'] : [];
                $presentDay = !empty($request['mh']['present_duration_of_day']) ? $request['mh']['present_duration_of_day'] : [];
                $pastDurationDetails = !empty($request['mh']['past_duration_of_day_details']) ? $request['mh']['past_duration_of_day_details'] : [];
                $presentDayDetails = !empty($request['mh']['present_duration_of_day_details']) ? $request['mh']['present_duration_of_day_details'] : [];
                $eddDateValue = !empty($mhData['edd']) ? Carbon::parse($mhData['edd'])->format('Y-m-d') : null;
                if($isANCStatus == 1){
                    $anc->edd = $eddDateValue;
                }
                if(!empty($presentDay) || !empty($pastDurationDay)){
                    $dayData = array_merge((array)$pastDurationDay,(array)$presentDay);
                }
                if(!empty($pastDurationDetails) || !empty($presentDayDetails)){
                    $mhData['past_duration_of_day'] = !empty($pastDurationDetails) ? $pastDurationDetails : $pastDurationDay;
                    $mhData['present_duration_of_day'] = !empty($presentDayDetails) ? $presentDayDetails : $presentDay;
                    $dayData = array_merge((array)$pastDurationDetails,(array)$presentDayDetails);
                }
                if(!empty($dayData)){
                    durationData(1,$dayData);
                }
                $lastOE = null;
                if($request->anc_id){
                    $lastOE = json_decode($this->ANC->whereId(decrypt($request->anc_id))->value('o_e'), true);
                }

                if(isset($oeData['expert_usg_image']) && !empty($oeData['expert_usg_image'])){
                    if(isset($lastOE['expert_usg_image']) && !empty($lastOE['expert_usg_image'])) {
                        $this->removeImage($lastOE['expert_usg_image']);
                    }
                    $image = $oeData['expert_usg_image'];
                    $name = \Carbon\Carbon::now()->format('YmdHisu').'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/anc');
                    $image->move($destinationPath, $name);
                    $oeData['expert_usg_image'] = 'public/upload/anc/' . $name;
                }
                if(isset($oeData['blood_report_image']) && !empty($oeData['blood_report_image'])){
                    if(isset($lastOE['blood_report_image']) && !empty($lastOE['blood_report_image'])) {
                        $this->removeImage($lastOE['blood_report_image']);
                    }
                    $image = $oeData['blood_report_image'];
                    $name = \Carbon\Carbon::now()->format('YmdHisu').'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/anc');
                    $image->move($destinationPath, $name);
                    $oeData['blood_report_image'] = 'public/upload/anc/' . $name;
                }
                if($isANCStatus == 1){
                    if(!empty($request->investigation['investigation_details'])){
                        $investigationData['investigation_details'] = array_filter($request->investigation['investigation_details']);
                    }
                    $anc->o_e = json_encode($oeData);
                    $anc->investigation = json_encode($investigationData);
                    $anc->injection = json_encode($request->injection);
                    $anc->usg = json_encode($usgData);
                    $anc->is_gynec = $request->is_gynec;
                }
                if($isGynecStatus == 0){
                    $anc->m_h = json_encode($mhData);
                }
                if($isGynecStatus == 1){
                    $anc->mh = json_encode($mhData);
                }
                $anc->patients_details_ho = json_encode($request->p_detailes);
                $anc->treatment = !empty($request->treatment) ? json_encode($request->treatment) : json_encode($request->old_treatment);
                $anc->patients_id = $patientsId;
                $anc->seen_by = $request->seen_by;
                $anc->created_by = Auth::user()->id;
                $anc->save();
                $ancData = $anc;
            }
            // save patients data table
            $patients = $this->OpdPatients->find($patientsId);
            $patients->name = $request->name;
            // $patients->weight = $request->weight;
            $patients->reference_doctor_id = $request->rd_reference;
            $patients->mobile_number = $request->mobile_number;
            $patients->residence = $request->residence;
            $patients->main_area = $request->main_area;
            $patients->city = $request->city;
            $patients->save();

            // save anc_history table
            if($request->anc_history_type == 'anc_history'){
                if(!empty($howMuchNumber)){
                    $validChild = $this->checkChild($request->how_much_number_data,$howMuchNumber);
                    if($validChild){
                        return ['status'=>'2'];
                    }
                }
                $ancHistory = $this->AncHistory;
                if($request->anc_history_id){
                    $ancHistory = $this->AncHistory->where('id', decrypt($request->anc_history_id))->first();
                }
                // $oldInvestigationData = null;
                // $earlyOldImages = null;
                // $ancOldImages = null;
                // $growthOldImages = null;
                // $otherOldImages = null;
                // if(!empty($anc->investigation)){
                //     $oldInvestigationData = json_decode($ancHistory->investigation);
                //     if(!empty($oldInvestigationData)){
                //         $earlyOldImages = !empty($oldInvestigationData->investigation_early_scan_type->images) ? $oldInvestigationData->investigation_early_scan_type->images : null;
                //         $ancOldImages = !empty($oldInvestigationData->anc->images) ? $oldInvestigationData->anc->images : null;
                //         $growthOldImages = !empty($oldInvestigationData->growth_report->images) ? $oldInvestigationData->growth_report->images : null;
                //         $otherOldImages = !empty($oldInvestigationData->other_report_data->images) ? $oldInvestigationData->other_report_data->images : null;
                //     }
                // }
                $ancHistory->patients_id = $patients->id;
                $hoDataType['ho_details'] = $request->ho['ho_details'];
                $hoDataType['weight'] = $request->ho['weight'];
                $ancHistory->h_o = json_encode($hoDataType);
                $oeData = $request->oe;
                $oeData['new_follow_up'] = Carbon::parse($request->oe['follow_up'])->format('Y-m-d');
                $investigationData = $request->investigation;
                $usgData = $request->usg;
                $ancImages = [];
                $earlyScanTypeImages = [];
                $otherImages = [];
                $growthReportImages = [];
                if($request->anc_history_id){
                        $this->getImagesData('usg_old','anc_history',decrypt($request->anc_history_id),$request->usg_old ? $request->usg_old : [-1]);
                    // if(empty($request['investigation']['investigation_early_scan_type']['images'])){
                        $this->getImagesData('early_old','anc_history',decrypt($request->anc_history_id),$request->early_old ? $request->early_old : [-1]);
                    // }
                    // if(empty($request['investigation']['anc_history']['images'])){
                        $this->getImagesData('anc_old','anc_history',decrypt($request->anc_history_id),$request->anc_old ? $request->anc_old : [-1]);
                    // }
                    // if(empty($request['investigation']['growth_report']['images'])){
                        $this->getImagesData('growth_old','anc_history',decrypt($request->anc_history_id),$request->growth_old ? $request->growth_old : [-1]);
                    // }
                    // if(empty($request['investigation']['other_report_data']['images'])){
                        $this->getImagesData('other_old','anc_history',decrypt($request->anc_history_id),$request->other_old ? $request->other_old : [-1]);
                    // }
                }
                $oldInvestigationData = [];
                $earlyOldImages = [];
                $ancOldImages = [];
                $growthOldImages = [];
                $otherOldImages = [];
                $usgOldImages = [];
                if($request->anc_history_id){
                    $oldAncHistory = $this->AncHistory->where('id', decrypt($request->anc_history_id))->first();
                    $oldUsgData = json_decode($oldAncHistory->usg);
                    if(!empty($ancHistory->investigation)){
                        $oldInvestigationData = json_decode($oldAncHistory->investigation);
                        if(!empty($oldInvestigationData)){
                            $earlyOldImages = !empty($oldInvestigationData->investigation_early_scan_type->images) ? (array)$oldInvestigationData->investigation_early_scan_type->images : [];
                            $ancOldImages = !empty($oldInvestigationData->anc->images) ? (array)$oldInvestigationData->anc->images : [];
                            $growthOldImages = !empty($oldInvestigationData->growth_report->images) ? (array)$oldInvestigationData->growth_report->images : [];
                            $otherOldImages = !empty($oldInvestigationData->other_report_data->images) ? (array)$oldInvestigationData->other_report_data->images : [];
                        }
                    }
                    if(!empty($oldUsgData)){
                        $usgOldImages = !empty($oldUsgData->images) ? (array)$oldUsgData->images : [];
                    }
                }

                if(!empty($request['investigation']['investigation_early_scan_type']['images'])){
                    foreach($request['investigation']['investigation_early_scan_type']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $earlyScanTypeImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['investigation_early_scan_type']['images'] = array_merge($earlyScanTypeImages,$earlyOldImages);
                }else{
                    // if(!$request->anc_history_id && !$request->early_old){
                    //     $earlyOldImages = [];
                    // }
                    $investigationData['investigation_early_scan_type']['images'] = $earlyOldImages;
                }
                if(!empty($request['investigation']['anc']['images'])){
                    foreach($request['investigation']['anc']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $ancImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['anc']['images'] = array_merge($ancImages,$ancOldImages);
                }else{
                    // if(!$request->anc_history_id && !$request->anc_old){
                    //     $ancOldImages = [];
                    // }
                    $investigationData['anc']['images'] = $ancOldImages;
                }
                if(!empty($request['investigation']['growth_report']['images'])){
                    foreach($request['investigation']['growth_report']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $growthReportImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['growth_report']['images'] = array_merge($growthReportImages,$growthOldImages);
                }else{
                    // if(!$request->anc_history_id && !$request->growth_old){
                    //     $growthOldImages = [];
                    // }
                    $investigationData['growth_report']['images'] = $growthOldImages;
                }
                if(!empty($request['investigation']['other_report_data']['images'])){
                    foreach($request['investigation']['other_report_data']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $otherImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $investigationData['other_report_data']['images'] = array_merge($otherImages,$otherOldImages);
                }else{
                    // if(!$request->anc_history_id && !$request->other_old){
                    //     $otherOldImages = [];
                    // }
                    $investigationData['other_report_data']['images'] = $otherOldImages;
                }
                if(!empty($request['usg']['images'])){
                    foreach($request['usg']['images'] as $key=>$row){
                        $name = $this->uploadImage($row, 'public/upload/anc/report');
                        $usgImages[] = 'public/upload/anc/report/' . $name;
                    }
                    $usgData['images'] = array_merge($usgImages,$usgOldImages);
                }else{
                    $usgData['images'] = $usgOldImages;
                }
                if (isset($oeData['how_much']['type']) && $oeData['how_much']['type'] == 'yes' && isset($oeData['fefal_reduction']['how_much_value']) && !empty($oeData['fefal_reduction']['how_much_value'])) {
                    $j = $oeData['oe_no'];
                    for ($i = 1; $i <= $oeData['fefal_reduction']['how_much_value']; $i++) {
                        unset($oeData['utdata'][$j]);
                        $j--;
                    }
                }
                $lastOE = null;
                if (!empty($request->anc_history_id)) {
                    $lastOE = json_decode($this->AncHistory->whereId(decrypt($request->anc_history_id))->value('o_e'), true);
                }
                if (!empty($oeData['expert_usg_image'])){
                    if (isset($lastOE['expert_usg_image']) && !empty($lastOE['expert_usg_image'])) {
                        $removedImage = $this->removeImage($lastOE['expert_usg_image']);
                    }
                    $image = $oeData['expert_usg_image'];
                    $name = \Carbon\Carbon::now()->format('YmdHisu') . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/anc');
                    $image->move($destinationPath, $name);
                    $oeData['expert_usg_image'] = 'public/upload/anc/' . $name;
                }
                if(!empty($oeData['blood_report_image'])){
                    if(isset($lastOE['blood_report_image']) && !empty($lastOE['blood_report_image'])){
                        $removedImage = $this->removeImage($lastOE['blood_report_image']);
                    }
                    $image = $oeData['blood_report_image'];
                    $name = \Carbon\Carbon::now()->format('YmdHisu') .'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/anc');
                    $image->move($destinationPath, $name);
                    $oeData['blood_report_image'] = 'public/upload/anc/' . $name;
                }

                $ancHistory->o_e = json_encode($oeData);
                $ancHistory->c_o = json_encode($request->co);
                $ancHistory->investigation = json_encode($investigationData);
                $ancHistory->treatment = !empty($request->treatment) ? json_encode($request->treatment) : json_encode($request->old_treatment);
                $ancHistory->patients_details_ho = json_encode($request->p_detailes);
                $ancHistory->injection = json_encode($request->injection);
                $ancHistory->usg = json_encode($usgData);
                $ancHistory->is_gynec = $request->is_gynec;
                $ancHistory->seen_by = $request->seen_by;
                $ancHistory->created_by = Auth::user()->id;
                $ancHistory->updated_at = Carbon::now()->format('Y-m-d');
                $ancHistory->save();
                $ancData = $ancHistory;
                $lmdDate = $request->oe_lmd_date;
                $usgEddDate = $request->oe_usg_edd_date;
                $eddDate = $request->oe_edd_date;
            }

            // update appointment flag
            $now = Carbon::now()->format('Y-m-d');
            $usgStatus = 0;
            $appointmentFlag = $this->Appointment->wherePatientsId($patientsId)->where('date',$now)->update(['is_done'=>1]);
            if(!empty($request->oe['follow_up']) && ((!empty($request->usg['nt_scan']) && strtotime($request->usg['nt_scan']) == strtotime($request->oe['follow_up'])) || (!empty($request->usg['early_scan']) && strtotime($request->usg['early_scan']) == strtotime($request->oe['follow_up'])) || (!empty($request->usg['anomalies_miles']) && strtotime($request->usg['anomalies_miles']) == strtotime($request->oe['follow_up'])) || (!empty($request->usg['growth_scan']) && strtotime($request->usg['growth_scan']) == strtotime($request->oe['follow_up'])))){
                $usgStatus = 1;
                // $usgAppointment = $this->Appointment->wherePatientsId($patientsId)->where('date',$now)->update(['usg_status'=>1]);
            }
            $followupDate = !empty($request->data['follow_up']) ? $request->data['follow_up'] : null;
            if($request->visit == 2){
                $followupDate = !empty($request->data['plan']['follow_up']) ? $request->data['plan']['follow_up'] : null;
            }

            if(isset($request->oe['follow_up']) && !empty($request->oe['follow_up'])) {
                $followupDate = $request->oe['follow_up'];
                $currentDate = date('Y-m-d');
                $followDate = date('Y-m-d',strtotime($followupDate));
                    $appointment = $this->Appointment->where('patients_id',$patientsId)->orderBy('id','DESC')->first();
                    if($appointment){
                        $appointmentData['appointmentId'] = encrypt($appointment->id);
                        if($isANCStatus == 0){
                            $appointmentData['category'] = $request->category;
                        }
                        $appointmentData['date'] = $nextFollowDate;
                        $appointmentData['time'] = $appointmentTime;
                        $appointmentData['usg_status'] = $usgStatus;
                        $appointmentData['is_gynec'] = $request->is_gynec;
                        $nextAppointment = $this->nextAppointmentData($appointmentData);
                    }
                // }
            }
            $howMuchNumber = !empty($request['oe']['how_much']['number']) ? $request['oe']['how_much']['number'] : null;
            if(!empty($howMuchNumber)){
                $utdata = json_decode($ancData->o_e,1);
                $utdata = $utdata['utdata'];
                $removeKeys = range(1,$howMuchNumber);
                $arr = array_diff_key($utdata, array_flip($removeKeys));
                if(!empty($arr)){
                    $arr = array_combine(range(1, count($arr)), array_values($arr));
                }
                $data = json_decode($ancData->o_e);
                $data->utdata = $arr;
                $ancData->o_e = json_encode($data);
                $ancData->save();
            }
            $patientsId = decrypt($request->patients_id);
            if(!(empty($ancHistory->created_at))){
                $previousAnc = $this->AncHistory
                    ->where([
                        ['created_at', '<', $ancHistory->created_at],
                        ['patients_id', '=', $patientsId]
                    ])
                    ->orderBy('id', 'DESC')
                    ->first();
                // dd($previousAnc);
                if($previousAnc == null){
                    $previousAnc=$this->ANC
                    ->where([
                        ['patients_id', '=', $patientsId]
                    ])
                    ->orderBy('id', 'DESC')
                    ->first();
                }
            }else{
                $previousAnc="";
            }
            $isGsac = false;
            $placenta = $this->getPlacenta()['placenta'];
            $ancFirstVisitData = $this->ANC->where('patients_id',$patientsId)->first();
            $upt = !empty($ancFirstVisitData->patients_obstratics) ? json_decode($ancFirstVisitData->patients_obstratics, true) : null;
            $oe = !empty($ancFirstVisitData->o_e) ? json_decode($ancFirstVisitData->o_e, true) : null;
            $personal_history_type = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $personal_past_history_type = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $currentdate=Carbon::now()->format('d-m-y');
            $weekData =  [1=>'Normal Size',2=>'Just Bulky',3=>'6 Weeks',4=>'6 - 8 Weeks',5=>'8 Weeks',6=>'8 - 10 Weeks',7=>'10 - 12 Weeks',8=>'12 Weeks',9=>'Uterus Just Palpable',10=>'14 Weeks',11=>'16 Weeks',12=>'18 Weeks',13=>'20 Weeks',14=>'22 Weeks',15=>'24 Weeks',16=>'26 Weeks',17=>'28 Weeks',18=>'30 Weeks',19=>'32 Weeks',20=>'34 Weeks',21=>'36 Weeks',22=>'Full Term'];
            $weight = isset($request->ho['weight']) ? $request->ho['weight'] : $request->p_info['weight'];
            if(!empty($upt['upt_type']) && $upt['upt_type'] == 'positive' && isset($oe['utdata'][1]['ut_type']) && $oe['utdata'][1]['ut_type'] == 'g-sac' && (strtolower($oe['utdata'][1]['oe_ut_sac']) == 'no' || strtolower($oe['utdata'][1]['oe_ut_sac_2']) == 'no')) {
                $isGsac = true;
            }
            if(!$request->anc_history_id && !$request->anc_id){
                $seenBy = getSeenByDoctor($ancData->seen_by);
                $patient = $this->OpdPatients->find($patientsId);
                // $this->SmsManager::sendReferenceDoctor('Advise ANC',$seenBy->name,date('d M Y',strtotime($followupDate)),$patientsId);
            }
            $investigationReport = $this->allInvestigationReport();
            $ancAutoRemark = $this->getAutoRemark($patientsId);

            if($request->isprint){
                return response()->json([
                    'status'=>1,
                    'id' => encrypt($ancData->id),
                    'data' => View::make('admin.anc.preview', compact('investigationReport','weight','personal_past_history_type','personal_history_type','placenta', 'ancData','ancHistory','isNextAppointment','nextAppointmentDate','lmdDate','usgEddDate','eddDate', 'isGsac', 'isFirstVisit','currentdate','previousAnc','weekData','usgStatus','patients','ancAutoRemark'))->render()
                ]);
            }
            if($request->is_pdf == 1){
                $pdfData['personal_past_history_type'] = $personal_past_history_type;
                $pdfData['personal_history_type'] = $personal_history_type;
                $pdfData['placenta'] = $placenta;
                $pdfData['ancData'] = $ancData;
                $pdfData['ancHistory'] = $ancHistory;
                $pdfData['isNextAppointment'] = $isNextAppointment;
                $pdfData['nextAppointmentDate'] = $nextAppointmentDate;
                $pdfData['isGsac'] = $isGsac;
                $pdfData['eddDate'] = $eddDate;
                $pdfData['lmdDate'] = $lmdDate;
                $pdfData['isFirstVisit'] = $isFirstVisit;
                $pdfData['currentdate'] = $currentdate;
                $pdfData['previousAnc'] = $previousAnc;
                $pdfData['weekData'] = $weekData;
                $pdfData['usgStatus'] = $usgStatus;

                $pdf = PDF::loadView('admin.anc.pdf', $pdfData);
                $pdfPath = 'public/pdf';
                if(!File::isDirectory($pdfPath)){
                    File::makeDirectory($pdfPath, 0777, true, true);
                }
                $pdfName = 'ANC-Appointment'.date('Y-m-d-H-i-s').'_'.$ancData->id.'.pdf';
                if($pdf->save($pdfPath.'/'.$pdfName)){
                    $patients = $this->OpdPatients->find($patientsId);
                    //code here to send pdf
                }
                return ['status'=>3,'id'=>$ancData->id,'url_value'=>$urlValue];
            }
            Session::flash('msg','Record has been successfully added.');
            return ['status'=>'true'];
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return ['status'=>'false'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // open the anc first visit create page
    public function nextPatientsAppointment(Request $request,$patients_id,$appointmentId = null){
        try{
            $pId = decrypt($patients_id);
            $apId = null;
            $appointmentData = null;
            if($appointmentId){
                $apId = decrypt($appointmentId);
                $appointmentData = $this->Appointment->find($apId);
            }
            $hospitalTime = $this->appointmentTime('09:00', '23:55', '5 mins');
            $isIvf = false;
            $plan = null;
            $frozen = null;
            $transfer = null;
            $ivfPatients = $this->IvfHistory->wherePatientsId($pId)->where('description->transfer->upt_type','positive')->where('description->transfer->result_type','conceive')->orderBy('id','DESC')->first();
            $lastVisitLmpDate = null;
            if($ivfPatients){
                $ivfSecondData = $this->IvfHistory->where('visit',2)->wherePatientsId($pId)->first();
                $lmpData = json_decode($ivfSecondData->description);
                if(!empty($lmpData->lmp->date)){
                    $lastVisitLmpDate = $lmpData->lmp->date;
                }
                $plan = $ivfPatients->plan;
                $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
                $plan = $planData[$plan];
                $isIvf = true;
                $transfer = $this->IvfHistory->wherePatientsId($pId)->where('description->progesterone->status','!=',null)->where('description->progesterone->type','!=',null)->orderBy('id','DESC')->first();
                if($transfer){
                    $transferData = json_decode($transfer->description);
                    if(!empty($transferData)){
                        $transfer = $transferData->progesterone->type;
                    }
                    $frozen = $this->IvfHistory->wherePatientsId($pId)->where('description->collected->frozen','!=',null)->orderBy('id','DESC')->first();
                    $frozenData = json_decode($frozen->description);
                    if(!empty($frozenData)){
                        $frozen = $frozenData->collected->frozen->type;
                    }
                }

            }
            $durationOfData = getDurationOfData(1)['data'];
            $ancPatients = $this->OpdPatients->find($pId);
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $complaints = $this->Complaint->pluck('name','name');
            $medicines = $this->Medicine->pluck('name','name');
            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $placenta = $this->getPlacenta()['placenta'];
            $category = $this->Category
            ->whereStatus(1)
            ->whereNotIn('id', [7])
            ->pluck('name','id');
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $hoData = $this->getHoData();
            $lastAppointment = $this->Appointment->wherePatientsId($pId)->orderBy('id','DESC')->first();
            $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            $weekData =  [1=>'Normal Size',2=>'Just Bulky',3=>'6 Weeks',4=>'6 - 8 Weeks',5=>'8 Weeks',6=>'8 - 10 Weeks',7=>'10 - 12 Weeks',8=>'12 Weeks',9=>'Uterus Just Palpable',10=>'14 Weeks',11=>'16 Weeks',12=>'18 Weeks',13=>'20 Weeks',14=>'22 Weeks',15=>'24 Weeks',16=>'26 Weeks',17=>'28 Weeks',18=>'30 Weeks',19=>'32 Weeks',20=>'34 Weeks',21=>'36 Weeks',22=>'Full Term'];
            return view('admin.anc.create',compact('lastVisitLmpDate','familyData','pastData','personalData','placenta','hoData','ancPatients','patients_id','referenceDoctor','complaints','medicines','hospitalTime','isIvf','plan','frozen','transfer','durationOfData','lastAppointment','appointmentData','category','weekData','hospitalTime','hospitalDoctor','leftOvaryData','rightOvaryData'));
        }catch(Exception $e){
            return back();
        }
    }

    public function getRefDoctorMobileNumber(Request $request){
        try{
            $mobileNumber = $this->ReferenceDoctor->find($request->refDoctorId);
            return ['mobile_number'=>$mobileNumber['mobile_number']];
        }catch(Exception $e){
            abort(500);
        }
    }

    private function getPlacenta() {
        $placenta = [
            '1'=>'Anterior',
            '2'=>'Fundal',
            '3'=>'Anterior & Fundal',
            '4'=>'Left Lateral Wall',
            '5'=>'Posterior',
            '6'=>'Anterior Reaching to OS',
            '7'=>'Anterior Lowlying',
            '8'=>'Anterior Circumvallate',
            '9'=>'Anterior Covering the OS',
            '10'=>'Anterior Low Laying Reaching Internal OS',
            '11'=>'Centraly Covering the OS',
            '12'=>'Fundal & Posterior',
            '13'=>'Fundal Extending Anteriorly and Posteriorly',
            '14'=>'Fundo Posterior',
            '15'=>'Posterior Low Laying',
            '16'=>'Posterior Low Laying Covering Internal OS',
            '17'=>'Posterior Reaching up to OS',
            '18'=>'Right Lateral Wall',
            '19'=>'Shows Anhypoechoic lesion',
        ];
        return ['placenta' => $placenta];
    }

    /**
     * here open the anc page after first visit
     * in this function also fetch data from all anc history via ANC visit date wise date
     */
    public function ancHistory(Request $request,$patientsId){
        try{
            $patients = decrypt($patientsId);
            $isGsac = false;
            $ancData = $this->AncHistory->where('patients_id',$patients)->orderBy('id','DESC')->first();
            $personalData = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $pastData = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $familyData = $this->AncHoHistory->where('type',3)->pluck('name','name')->toArray();
            $lmdDate = null;
            $eddDate = null;
            $medicineData = null;
            $historyOE = null;
            $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
            $leftOvaryData = $this->OvaryDetail->where('type',1)->pluck('name','name');
            $rightOvaryData = $this->OvaryDetail->where('type',2)->pluck('name','name');
            if($ancData){
                $mhData = json_decode($ancData->getAncs->m_h);
                // $mhData = json_decode($ancData->getAncs->m_h);
                $ancFirstVisitData = $this->ANC->where('patients_id',$patients)->first();
                $upt = json_decode($ancFirstVisitData->patients_obstratics, true);
                $oe = json_decode($ancFirstVisitData->o_e, true);
                $historyOE = json_decode($ancData->o_e, true);
                if(isset($upt['upt_type']) && $upt['upt_type'] == 'positive' && isset($oe['utdata'][1]) && !empty($oe['utdata'][1]['ut_type'])  && $oe['utdata'][1]['ut_type'] == 'g-sac' && (strtolower($oe['utdata'][1]['oe_ut_sac']) == 'no' || strtolower($oe['utdata'][1]['oe_ut_sac_2']) == 'no')) {
                    $isGsac = true;
                }
                else{
                    $ancData = $ancData;
                }
            }
            else{
                $ancData = $this->ANC->where('patients_id',$patients)->first();
                $mhData = json_decode($ancData->m_h);
                // $ancData = $this->ANC->where('patients_id',$patients)->first();
                $upt = json_decode($ancData->patients_obstratics, true);
                $oe = json_decode($ancData->o_e, true);
                if (isset($upt['upt_type']) && $upt['upt_type'] == 'positive' && isset($oe['utdata'][1]) && !empty($oe['utdata'][1]['ut_type']) && $oe['utdata'][1]['ut_type'] == 'g-sac' && (strtolower($oe['utdata'][1]['oe_ut_sac']) == 'no' || strtolower($oe['utdata'][1]['oe_ut_sac_2']) == 'no')) {
                    $isGsac = true;
                }
                // $mhData = json_decode($ancData->m_h);
            }
            $hoDate = null;
            $hoData = json_decode($ancData->h_o);
            if(!empty($hoData)){
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
                $oldDate = Carbon::parse($ancData->created_at)->format('Y-m-d');
                $nowDate = Carbon::now();
                $diffDays = Carbon::parse($oldDate)->diffInDays($nowDate);
                $totalDays = $monthDays + $diffDays;
                $hoDate = (int)($totalDays/$days).'-'.$totalDays % $days;
            }
            $lmdDate = $mhData->last_menstrual_date;
            $eddDate = !empty($mhData->edd) ? $mhData->edd : null;
            $usgEddDate = !empty($mhData->usg_edd) ? $mhData->usg_edd : null;
            $anc = null;
            $ancHistory = null;
            $utType = 'yes';
            $ancHistoryId = null;
            $hoMonth = 'yes';
            $previousAnc = null;
            $ovaryData = [];
            if($request->date){
                $anc = $this->ANC->where('created_at',$request->date)->first();
                $ancHistory = $this->AncHistory->where('created_at',$request->date)->first();
                $hoMonth = 'no';
                $historyOE = null;
                if($anc){
                    $utType = 'no';
                    $ancData = $anc;
                }else{
                    $ancHistoryId = !empty($ancHistory->id) ? $ancHistory->id : null;
                    $ancData = $ancHistory;
                    $ancFirstVisitData = $this->ANC->where('patients_id',$patients)->first();
                    $upt = json_decode($ancFirstVisitData->patients_obstratics, true);
                    $oe = json_decode($ancFirstVisitData->o_e, true);
                    $historyOE = !empty($ancData->o_e) ? json_decode($ancData->o_e, true) : null;
                    if(isset($upt['upt_type']) && $upt['upt_type'] == 'positive' && isset($oe['utdata'][1]) && !empty($oe['utdata'][1]['ut_type']) &&  $oe['utdata'][1]['ut_type'] == 'g-sac' && (strtolower($oe['utdata'][1]['oe_ut_sac']) == 'no' || strtolower($oe['utdata'][1]['oe_ut_sac_2']) == 'no')) {
                        $isGsac = true;
                    } else {
                        $ancData = $ancHistory;
                    }
                }
                $previousAnc = $this->AncHistory
                    ->where([
                        ['created_at', '<', $request->date],
                        ['patients_id', '=', $patients]
                    ])
                    ->orderBy('id', 'DESC')
                    ->first();
                if($previousAnc == null){
                    $previousAnc = $this->ANC
                    ->where([
                        ['created_at', '<', $request->date],
                        ['patients_id', '=', $patients]
                    ])
                    ->orderBy('id', 'DESC')
                    ->first();
                    if($previousAnc == null){
                        $previousAnc="null";
                    }
                }
                // dd($previousAnc->o_e);
            }else{
                $previousAnc = $this->AncHistory
                    ->where([
                        ['patients_id', '=', $patients]
                    ])
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if($previousAnc == null){
                    $previousAnc = $this->ANC
                    ->where([
                        ['patients_id', '=', $patients]
                    ])
                    ->orderBy('id', 'DESC')
                    ->first();
                }
            }
            $ancPatients = $this->OpdPatients->find($patients);
            $durationOfData = ['other'=>'Other'] + getDurationOfData(1)['data'];
            $complaints = $this->Complaint->pluck('name','name');
            $patientsInfo = json_decode($ancData->patients_info);
            $patientsObstratics = json_decode($ancData->patients_obstratics);
            $ho = json_decode($ancData->h_o);
            $co = json_decode($ancData->c_o);
            $mh = json_decode($ancData->m_h);
            $pastHistory = json_decode($ancData->past_history);
            $oe = json_decode($ancData->o_e);
            // // $previousAnc = (!empty($previousAnc)) ? json_decode($previousAnc->o_e) : null;
            // $previousAnc = (!empty($previousAnc)) ? json_decode($previousAnc) : null;
            $previousAncOe = (!empty($previousAnc->o_e)) ? json_decode($previousAnc->o_e) : null;
            $previousAncinvestigation = (!empty($previousAnc->investigation)) ? json_decode($previousAnc->investigation) : null;
            $previousAncPatientObs = (!empty($previousAnc->patients_obstratics)) ? json_decode($previousAnc->patients_obstratics) : null;
            $patientsDetails = json_decode($ancData->patients_details_ho);
            $patientsInvestigation = json_decode($ancData->investigation);
            $patientsInjection = json_decode($ancData->injection);
            $treatment = json_decode($ancData->treatment);
            $usg = json_decode($ancData->usg);
            $date = [];
            $ancHistoryDate = $this->AncHistory->where('patients_id',$patients)->orderBy('created_at','DESC')->pluck('created_at','created_at')->toArray();
            // $ancHistoryDate = collect($this->AncHistory->select('created_at','o_e->follow_up as follow_up')->where('patients_id',$patients)->get())->map(function ($q){
            //     $q->follow_up = Carbon::parse($q->follow_up)->format('d-m-Y').' '.Carbon::parse($q->created_at)->format('H:i:s');
            //     return $q;
            // })->pluck('follow_up','created_at')->toArray();
            // $ancDate = collect($this->ANC->select('created_at','o_e->follow_up as follow_up')->where('patients_id',$patients)->get())->map(function ($q){
            //     $q->follow_up = Carbon::parse($q->follow_up)->format('d-m-Y').' '.Carbon::parse($q->created_at)->format('H:i:s');
            //     return $q;
            // })->pluck('follow_up','created_at')->toArray();
            $ancDateData = $this->ANC->where('patients_id',$patients)->first();
            $ancDate = [Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')];
            $date = array_merge($ancHistoryDate,$ancDate);
            $medicines = $this->Medicine->pluck('name','name')->toArray();
            $hospitalTime = $this->appointmentTime('09:00', '17:00', '5 mins');
            $ovaryData = $this->OvaryDetail->pluck('name','name');
            $hoData = $this->getHoData();
            if($hoDate){
                $hoDate = explode('-',$hoDate);
                $mon = $hoDate[0]. ' month';
                $day = $hoDate[1]. ' day';
                $hoDate = $mon.' '.$day;
                $hoData[$hoDate] = $hoDate;
            }
            $lastAppointment = $this->Appointment->wherePatientsId($patients)->orderBy('id','DESC')->first();
            if(!empty($treatment)){
                $medicineData = !empty($treatment->medicinedata) ? $treatment->medicinedata : null;
                unset($treatment->medicinedata);
            }
            $ancImagesValue = null;
            $growthImagesValue = null;
            $earlyScanImagesValue = null;
            $otherImagesValue = null;
            $usgImagesValue = null;
            $ancImagesData = !empty($patientsInvestigation->anc->images) ? $patientsInvestigation->anc->images : null;
            if($ancImagesData){
                foreach($ancImagesData as $key=>$row){
                    $ancImagesValue[$key]['id'] = $key;
                    $ancImagesValue[$key]['src'] = url($row);
                }
            }
            $earlyScanImagesData = !empty($patientsInvestigation->investigation_early_scan_type->images) ? $patientsInvestigation->investigation_early_scan_type->images : null;
            if($earlyScanImagesData){
                foreach($earlyScanImagesData as $key=>$row){
                    $earlyScanImagesValue[$key]['id'] = $key;
                    $earlyScanImagesValue[$key]['src'] = url($row);
                }
            }
            $growthImagesData = !empty($patientsInvestigation->growth_report->images) ? $patientsInvestigation->growth_report->images : null;
            if($growthImagesData){
                foreach($growthImagesData as $key=>$row){
                    $growthImagesValue[$key]['id'] = $key;
                    $growthImagesValue[$key]['src'] = url($row);
                }
            }
            $otherImagesData = !empty($patientsInvestigation->other_report_data->images) ? $patientsInvestigation->other_report_data->images : null;
            if($otherImagesData){
                foreach($otherImagesData as $key=>$row){
                    $otherImagesValue[$key]['id'] = $key;
                    $otherImagesValue[$key]['src'] = url($row);
                }
            }
            $usgImages = !empty($usg->images) ? $usg->images : null;
            if($usgImages){
                foreach($usgImages as $key=>$row){
                    $usgImagesValue[$key]['id'] = $key;
                    $usgImagesValue[$key]['src'] = url($row);
                }
            }
            $ancImagesValue = json_encode($ancImagesValue);
            $earlyScanImagesValue = json_encode($earlyScanImagesValue);
            $growthImagesValue = json_encode($growthImagesValue);
            $otherImagesValue = json_encode($otherImagesValue);
            $usgImagesValue = json_encode($usgImagesValue);
            $medicineKey = [];
            if(!empty($treatment)){
                $medicineKey = (array)$treatment;
                $medicineKey = array_column($medicineKey,'medicine');
                if(!empty($medicineKey)){
                    $medicineKey = array_combine($medicineKey,$medicineKey);
                }
            }
            $weekData = [1=>'Normal Size',2=>'Just Bulky',3=>'6 Weeks',4=>'6 - 8 Weeks',5=>'8 Weeks',6=>'8 - 10 Weeks',7=>'10 - 12 Weeks',8=>'12 Weeks',9=>'Uterus Just Palpable',10=>'14 Weeks',11=>'16 Weeks',12=>'18 Weeks',13=>'20 Weeks',14=>'22 Weeks',15=>'24 Weeks',16=>'26 Weeks',17=>'28 Weeks',18=>'30 Weeks',19=>'32 Weeks',20=>'34 Weeks',21=>'36 Weeks',22=>'Full Term'];
            $ancLastVisitData = $this->AncHistory->where('patients_id',$patients)->orderBy('id','DESC')->first();
            if(!$ancLastVisitData){
                $ancLastVisitData = $this->ANC->where('patients_id',$patients)->first();
            }
            
            if($request->ajax()){
                $oeDataCount = !empty($oe->utdata) ? count((array)$oe->utdata) : 0;
                $data['patientsInfo'] = $patientsInfo;
                $data['medicineKey'] = $medicineKey;
                $data['patientsObstratics'] = $patientsObstratics;
                $data['familyData'] = $familyData;
                $data['usgEddDate'] = $usgEddDate;
                $data['ho'] = $ho;
                $data['co'] = $co;
                $data['mh'] = $mh;
                $data['oe'] = $oe;
                $data['isGsac'] = $isGsac;
                $data['ancImagesValue'] = $ancImagesValue;
                $data['earlyScanImagesValue'] = $earlyScanImagesValue;
                $data['growthImagesValue'] = $growthImagesValue;
                $data['otherImagesValue'] = $otherImagesValue;
                $data['usgImagesValue'] = $usgImagesValue;
                // $data['previousAncRemark'] = $previousAnc;
                $data['previousAnc'] = $previousAnc;
                $data['previousAncOe'] = $previousAncOe;
                $data['previousAncPatientObs'] = $previousAncPatientObs;
                $data['previousAncinvestigation'] =$previousAncinvestigation;
                // dd($data['previousAncRemark']);
                $data['usg'] = $usg;
                $data['hoMonth'] = $hoMonth;
                $data['pastHistory'] = $pastHistory;
                $data['patientsDetails'] = $patientsDetails;
                $data['patientsInvestigation'] = $patientsInvestigation;
                $data['patientsInjection'] = $patientsInjection;
                $data['utType'] = $utType;
                $data['complaints'] = $complaints;
                // data date wise
                $data['anc'] = $anc;
                $data['ancHistory'] = $ancHistory;
                $data['ancData'] = $ancData;
                $data['ancHistoryId'] = $ancHistoryId;
                $data['medicines'] = $medicines;
                $data['treatment'] = $treatment;
                $data['lmdDate'] = $lmdDate;
                $data['eddDate'] = $eddDate;
                $data['hoDate'] = $hoDate;
                $data['medicineData'] = $medicineData;
                $data['oeDataCount'] = $oeDataCount;
                $data['referenceDoctor'] = $referenceDoctor;
                $data['ovaryData'] = $ovaryData;
                $data['durationOfData'] = $durationOfData;
                $data['placenta'] = $this->getPlacenta()['placenta'];
                $data['hoData'] = $hoData;
                $data['lastAppointment'] = $lastAppointment;
                $data['ancPatients'] = $ancPatients;
                $data['weekData'] = $weekData;
                $data['personalData'] = $personalData;
                $data['pastData'] = $pastData;
                $data['hospitalDoctor'] = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
                $data['ancLastVisitData'] = $ancLastVisitData;
                $data['leftOvaryData'] = $leftOvaryData;
                $data['rightOvaryData'] = $rightOvaryData;
                $data['ancAutoRemark'] = $this->getAutoRemark($patients);
                $data['editAnc'] = View::make('admin.anc.edit',$data)->render();
                return $data;
            }

            return view('admin.anc.history',compact('ancData','patientsId','date','hospitalTime','weekData','medicines','ancPatients','ancLastVisitData','referenceDoctor'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    private function checkChild($oldNumber,$newNumber){
        if($oldNumber < $newNumber){
            return true;
        }
        return false;
    }

    // get images data for investigation
    private function getImagesData($reportType,$type,$id,$data){
        if($type == 'anc'){
            $anc = $this->ANC->find($id);
        }
        if($type == 'anc_history'){
            $anc = $this->AncHistory->find($id);
        }
        $ancInvestigationData = json_decode($anc->investigation);
        $usgData = json_decode($anc->usg);
        if($reportType == 'early_old'){
            $ancData = !empty($ancInvestigationData->investigation_early_scan_type) ? $ancInvestigationData->investigation_early_scan_type : [];
            if(!empty($ancData)){
                $earlyImages = $this->getImagesKey($ancData,$data)['key'];
                if(!empty($earlyImages)){
                    foreach($earlyImages as $row){
                        $this->removeImage($ancData->images[$row]);
                        unset($ancData->images[$row]);
                    }
                    $ancArray = (array)$ancData->images;
                    $ancArrayData = array_values($ancArray);
                    $ancData->images =  $ancArrayData;
                    $ancInvestigationData->investigation_early_scan_type = $ancData;
                    $anc->investigation = $ancInvestigationData;
                }
            }
        }
        if($reportType == 'usg_old'){
            $ancData = !empty($usgData) ? $usgData : [];
            if(!empty($ancData)){
                $usgImages = $this->getImagesKey($ancData,$data)['key'];
                 if(!empty($usgImages)){
                    foreach($usgImages as $row){
                        $this->removeImage($ancData->images[$row]);
                        unset($ancData->images[$row]);
                    }
                    $ancArray = (array)$ancData->images;
                    $ancArrayData = array_values($ancArray);
                    $usgData->images = $ancArrayData;
                }
            }
        }
        if($reportType == 'anc_old'){
            $ancData = !empty($ancInvestigationData->anc) ? $ancInvestigationData->anc : [];
            if(!empty($ancData)){
                $ancImages = $this->getImagesKey($ancData,$data)['key'];
                if(!empty($ancImages)){
                    foreach($ancImages as $row){
                        $this->removeImage($ancData->images[$row]);
                        unset($ancData->images[$row]);
                    }
                    $ancArray = (array)$ancData->images;
                    $ancArrayData = array_values($ancArray);
                    $ancData->images =  $ancArrayData;
                    $ancInvestigationData->anc = $ancData;
                    $anc->investigation = $ancInvestigationData;
                }
            }
        }
        if($reportType == 'growth_old'){
            $ancData = !empty($ancInvestigationData->growth_report) ? $ancInvestigationData->growth_report : [];
            if(!empty($ancData)){
                $growthImages = $this->getImagesKey($ancData,$data)['key'];
                if(!empty($growthImages)){
                    foreach($growthImages as $row){
                        $this->removeImage($ancData->images[$row]);
                        unset($ancData->images[$row]);
                    }
                    $ancArray = (array)$ancData->images;
                    $ancArrayData = array_values($ancArray);
                    $ancData->images =  $ancArrayData;
                    $ancInvestigationData->growth_report = $ancData;
                    $anc->investigation = $ancInvestigationData;
                }
            }
        }
        if($reportType == 'other_old'){
            $ancData = !empty($ancInvestigationData->other_report_data) ? $ancInvestigationData->other_report_data : [];
            if(!empty($ancData)){
                $otherImages = $this->getImagesKey($ancData,$data)['key'];
                if(!empty($otherImages)){
                    foreach($otherImages as $row){
                        $this->removeImage($ancData->images[$row]);
                        unset($ancData->images[$row]);
                    }
                    $ancArray = (array)$ancData->images;
                    $ancArrayData = array_values($ancArray);
                    $ancData->images =  $ancArrayData;
                    // dump('finalImages');
                    // dump($ancData);
                    $ancInvestigationData->other_report_data = $ancData;
                    $anc->investigation = $ancInvestigationData;
                }
            }
        }
        $anc->investigation = json_encode($ancInvestigationData);
        $anc->usg = json_encode($usgData);
        $anc->save();
        return ['status'=>true];
    }

    private function getImagesKey($ancData,$data){
        $imagesKey = [];
        $removedImageKey = [];
        if(!empty($ancData->images)){
            foreach($ancData->images as $key=>$row){
                $imagesKey[] =$key;
            }
            $removedImageKey = array_diff($imagesKey,$data);
        }
        return ['key'=>$removedImageKey];
    }
/**
 * update anc history detail
 */
    public function updateStatus($id,$tid) {
        $i="1";
        if($tid == "2"){
            $this->AncHistory->whereId($id)->update([
                'status' => $i
            ]);
        }else{
            $this->ANC->whereId($id)->update([
                'status' => $i
            ]);
        }
    }

    // store ANC HO data from H/O tab
    public function storeAncHoData($nameData,$type){
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

    // this function is use for generate file view of all visit
    public function getAncDetails(Request $request){
        try{
            $patientId = decrypt($request->patient_id);
            $opdPatient = $this->OpdPatients->find($patientId);
            $patients = $this->OpdPatients->find($patientId);
            $personal_history_type = $this->AncHoHistory->where('type',1)->pluck('name','name')->toArray();
            $personal_past_history_type = $this->AncHoHistory->where('type',2)->pluck('name','name')->toArray();
            $currentdate=Carbon::now()->format('d-m-y');
            $weekData =  [1=>'Normal Size',2=>'Just Bulky',3=>'6 Weeks',4=>'6 - 8 Weeks',5=>'8 Weeks',6=>'8 - 10 Weeks',7=>'10 - 12 Weeks',8=>'12 Weeks',9=>'Uterus Just Palpable',10=>'14 Weeks',11=>'16 Weeks',12=>'18 Weeks',13=>'20 Weeks',14=>'22 Weeks',15=>'24 Weeks',16=>'26 Weeks',17=>'28 Weeks',18=>'30 Weeks',19=>'32 Weeks',20=>'34 Weeks',21=>'36 Weeks',22=>'Full Term'];
            $placenta = $this->getPlacenta()['placenta'];
            $isGsac = false;
            $nextAppointmentDate = null;
            $ancHistory = null;
            $isNextAppointment = null;
            $usgStatus = null;
            $isFirstVisit = true;
            if($request->ajax()){
                // $date = $request->appointment_date;
                $historyDate = $request->history_date;
                
                
                $type = 0;
                $ancVisitDate = [];
                $viewAllVisit = [];
                $dateValue = [];
                $ancHistoryDate = $this->AncHistory->where('patients_id',$patientId)->orderBy('created_at','DESC')->pluck('created_at','created_at')->toArray();
                $ancDateData = $this->ANC->where('patients_id',$patientId)->first();
                $ancDate = [Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')=>Carbon::parse($ancDateData->created_at)->format('Y-m-d H:i:s')];
                $ancVisitDate = array_merge($ancHistoryDate,$ancDate);
                if($historyDate)
                {
                    $date = $historyDate;
                    $ancType = 2;
                    $ancData = $this->ANC->where('patients_id',$patientId)->where('created_at','=',$historyDate)->first();
                    $p_info = !empty($ancData->patients_info) ? json_decode($ancData->patients_info) : null;
                        $weight = !empty($p_info->weight) ? $p_info->weight : null;
                    if(!$ancData){
                        $ancData = $this->AncHistory->where('patients_id',$patientId)->where('created_at','=',$historyDate)->first();
                        $h_o = !empty($ancData->h_o) ? json_decode($ancData->h_o) : null;
                        $weight = !empty($h_o->weight) ? $h_o->weight : null;

                    }
                    $ancFirstVisitData = $this->ANC->where('patients_id',$patientId)->first();
                        $upt = json_decode($ancFirstVisitData->patients_obstratics, true);
                        $oe = json_decode($ancFirstVisitData->o_e, true);
                        $mhData = !empty($ancData->m_h) ? json_decode($ancData->m_h) : null;
                        $lmdDate = !empty($mhData->last_menstrual_date) ? $mhData->last_menstrual_date : null;
                        $eddDate = !empty($mhData->edd) ? $mhData->edd : null;
                        $usgEddDate = !empty($mhData->usg_edd) ? $mhData->usg_edd : null;
                        $previousAnc = null;
                        $investigationReport = $this->allInvestigationReport();
                        $now = Carbon::now()->format('Y-m-d');
                        $usgStatus = 0;
                        $usg = json_encode($ancData->usg);
                        if(((!empty($usg['nt_scan']) && $usg['nt_scan'] == $usg['follow_up']) || (!empty($usg['early_scan']) && $usg['early_scan'] == $usg['follow_up']) || (!empty($usg['anomalies_miles']) && $usg['anomalies_miles'] == $usg['follow_up']) || (!empty($usg['growth_scan']) && $usg['growth_scan'] == $usg['follow_up']))){
                            $usgStatus = 1;
                        }

                        if(!empty($upt['upt_type']) && $upt['upt_type'] == 'positive' && isset($oe['utdata'][1]['ut_type']) && $oe['utdata'][1]['ut_type'] == 'g-sac' && (strtolower($oe['utdata'][1]['oe_ut_sac']) == 'no' || strtolower($oe['utdata'][1]['oe_ut_sac_2']) == 'no')) {
                            $isGsac = true;
                        }
                        $ancAutoRemark = $this->getAutoRemark($patientId);

                    $viewAllVisit[] =  View::make('admin.anc.preview', compact('investigationReport','weight','personal_past_history_type','personal_history_type','placenta', 'ancData','ancHistory','isNextAppointment','nextAppointmentDate','lmdDate','usgEddDate','eddDate', 'isGsac', 'isFirstVisit','currentdate','previousAnc','weekData','usgStatus','date','patients','ancAutoRemark'))->render();
                }
                else
                {
                    foreach($ancVisitDate as $key => $date)
                    {
                        $ancType = 1;
                        $ancData = $this->ANC->where('patients_id',$patientId)->where('created_at','=',$key)->first();
                        $p_info = !empty($ancData->patients_info) ? json_decode($ancData->patients_info) : null;
                        $weight = !empty($p_info->weight) ? $p_info->weight : null;

                        if(!$ancData)
                        {
                            $ancData = $this->AncHistory->where('patients_id',$patientId)->where('created_at','=',$key)->first();
                            $h_o = !empty($ancData->h_o) ? json_decode($ancData->h_o) : null;
                            $weight = !empty($h_o->weight) ? $h_o->weight : null;
                        }
                        $ancFirstVisitData = $this->ANC->where('patients_id',$patientId)->first();
                        $upt = json_decode($ancFirstVisitData->patients_obstratics, true);
                        $oe = json_decode($ancFirstVisitData->o_e, true);
                        $mhData = !empty($ancData->m_h) ? json_decode($ancData->m_h) : null;
                        $lmdDate = !empty($mhData->last_menstrual_date) ? $mhData->last_menstrual_date : null;
                        $eddDate = !empty($mhData->edd) ? $mhData->edd : null;
                        $usgEddDate = !empty($mhData->usg_edd) ? $mhData->usg_edd : null;
                        $previousAnc = null;
                        $investigationReport = $this->allInvestigationReport();
                        $now = Carbon::now()->format('Y-m-d');
                        $usgStatus = 0;
                        $usg = json_encode($ancData->usg);
                        
                        // dd($weight);
                        if(((!empty($usg['nt_scan']) && $usg['nt_scan'] == $usg['follow_up']) || (!empty($usg['early_scan']) && $usg['early_scan'] == $usg['follow_up']) || (!empty($usg['anomalies_miles']) && $usg['anomalies_miles'] == $usg['follow_up']) || (!empty($usg['growth_scan']) && $usg['growth_scan'] == $usg['follow_up']))){
                            $usgStatus = 1;
                        }

                        if(!empty($upt['upt_type']) && $upt['upt_type'] == 'positive' && isset($oe['utdata'][1]['ut_type']) && $oe['utdata'][1]['ut_type'] == 'g-sac' && (strtolower($oe['utdata'][1]['oe_ut_sac']) == 'no' || strtolower($oe['utdata'][1]['oe_ut_sac_2']) == 'no')) {
                            $isGsac = true;
                        }
                        $ancAutoRemark = $this->getAutoRemark($patientId);

                        $viewAllVisit[] =  View::make('admin.anc.preview', compact('investigationReport','weight','personal_past_history_type','personal_history_type','placenta', 'ancData','ancHistory','isNextAppointment','nextAppointmentDate','lmdDate','usgEddDate','eddDate', 'isGsac', 'isFirstVisit','currentdate','previousAnc','weekData','usgStatus','date','patients','ancAutoRemark'))->render();
                        // $viewAllVisit,$preview);
                        $dateValue[] = $date;

                    }
                }
                return response()->json([
                    'status'=>1,
                    'anc_type'=>$ancType,
                    // 'type' => $type,
                    'date' => $dateValue,
                    // 'id' => encrypt($ancData->id),
                    'data' => $viewAllVisit
                ]);
            }else{
                $historyDate = $request->date;
                // $weight = $opdPatient->weight;
                $lastAppointmentData = $this->Appointment->where('patients_id',$patientId)->orderBy('id','DESC')->first();
                $ancType = 2;
                $ancFirstVisitData = $this->ANC->where('patients_id',$patientId)->first();
                $upt = json_decode($ancFirstVisitData->patients_obstratics, true);
                $oe = json_decode($ancFirstVisitData->o_e, true);
                $mhData = !empty($ancData->m_h) ? json_decode($ancData->m_h) : null;
                $lmdDate = !empty($mhData->last_menstrual_date) ? $mhData->last_menstrual_date : null;
                $eddDate = !empty($mhData->edd) ? $mhData->edd : null;
                $usgEddDate = !empty($mhData->usg_edd) ? $mhData->usg_edd : null;
                $previousAnc = null;
                $ancData = $this->ANC->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                $p_info = !empty($ancData->patients_info) ? json_decode($ancData->patients_info) : null;
                $weight = !empty($p_info->weight) ? $p_info->weight : null;
                if(!$ancData){
                    $ancData = $this->AncHistory->where('patients_id',$patientId)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$historyDate)->first();
                    $h_o = !empty($ancData->h_o) ? json_decode($ancData->h_o) : null;
                    $weight = !empty($h_o->weight) ? $h_o->weight : null;
                }
                if(!$ancData){
                    return 'no record available';
                }
                $date = $ancData->created_at;
                
                $investigationReport = $this->allInvestigationReport();
                $printPreview = 1;
                $ancAutoRemark = $this->getAutoRemark($patientId);

                return view('admin.anc.preview', compact('investigationReport','weight','personal_past_history_type','personal_history_type','placenta', 'ancData','ancHistory','isNextAppointment','nextAppointmentDate','lmdDate','usgEddDate','eddDate', 'isGsac', 'isFirstVisit','currentdate','previousAnc','weekData','usgStatus','date','patients','printPreview','ancAutoRemark'));
            }
        }catch(Exception $e){
            log::debug($e);
            return ['status'=>2];
        }
    }

    // get patient to edd date wise from M H column
    public function eddPatientList(Request $request){
        $patients = $this->getPatients();
        $eddPatientData = $this->ANC->where('m_h->edd','!=','null');
        if($request->ajax()){
            if($request->date){
                $date = explode("-",$request->date);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                $eddPatientData = $eddPatientData->whereBetween('edd', [$startDate, $endDate]);
            }
            $search = $request->search;
            if($search){
                $eddPatientData = $eddPatientData->where(function($query) use($search){
                    $query->whereHas('getPatients', function($q) use($search) {
                        $q->where('mobile_number','LIKE','%'.$search.'%');
                    });
                });
            }
            if($request->patient_id){
                $eddPatientData = $eddPatientData->where('patients_id',$request->patient_id);
            }
            if($request->isprint == 1){
                $eddPatientData = $eddPatientData->orderBy('id','DESC')->get();
                $status = 2;
                $viewName = 'edd_print';
            }else{
                $status = 1;
                $viewName = 'edd_data';
                $eddPatientData = $eddPatientData->orderBy('id','DESC')->paginate(50);
            }
            return response()->json([
                'status'=>$status,
                'data' => View::make('admin.anc.'.$viewName,compact('eddPatientData'))->render()
            ]);
        }
        return view('admin.anc.edd', compact('patients'));
    }
    /**
     * return remark for all anc visit
     * @return  array
     * @param $patients(Patient's id)
     */
    public function getAutoRemark($patients)
    {
        //for auto remark
        $ancAutoRemark = [];
        $placenta = $this->getPlacenta()['placenta'];
        $ancFirstVisit = $this->ANC->where('patients_id',$patients)->orderBy('id','DESC')->first();
        $ancHistoryVisit = $this->AncHistory->where('patients_id',$patients)->get();
        if($ancFirstVisit)
        {
            $auroRemarkInv = (!empty($ancFirstVisit->investigation)) ? json_decode($ancFirstVisit->investigation) : null;
            $investigationDetails = !empty($auroRemarkInv->investigation_details) ? (array)$auroRemarkInv->investigation_details : null;
            $historyAncPatientObs = (!empty($ancFirstVisit->patients_obstratics)) ? json_decode($ancFirstVisit->patients_obstratics) : null;
            $historyPatientObs = !empty($historyAncPatientObs->child->child_data) ? $historyAncPatientObs->child->child_data : [];
            $historyAncOe = (!empty($ancFirstVisit->o_e)) ? json_decode($ancFirstVisit->o_e) : null;
            $historyUTData = !empty($historyAncOe->utdata) ? $historyAncOe->utdata : [];

            if($investigationDetails && isset($investigationDetails['12'])  && (substr (strtolower($investigationDetails['12']), -3) == '-ve' || strtolower($investigationDetails['12']) == 'negative' || strpos(strtolower($investigationDetails['12']), 'negative') !== false))
            {
                $ancAutoRemark['blood_group'] = $investigationDetails['12'];
            }
            if($investigationDetails && isset($investigationDetails['8']) && (substr (strtolower($investigationDetails['8']), -3) == '+ve' || strtolower($investigationDetails['8']) == 'positive' || strpos(strtolower($investigationDetails['8']), 'positive') !== false))
            {
                $ancAutoRemark['hbsag'] = $investigationDetails['8'];
            }
            if($investigationDetails && isset($investigationDetails['10']) && (substr (strtolower($investigationDetails['10']), -3) == '+ve' || strtolower($investigationDetails['10']) == 'positive' || strpos(strtolower($investigationDetails['10']), 'positive') !== false))
            {
                $ancAutoRemark['hiv'] = $investigationDetails['10'];
            }
            if(!empty($auroRemarkInv->anc_hiv) && strtolower($auroRemarkInv->anc_hiv) == 'positive')
            {
                $ancAutoRemark['hiv'] = $auroRemarkInv->anc_hiv;
            }
            if(!empty($auroRemarkInv->anc_hbsag) && strtolower($auroRemarkInv->anc_hbsag) == 'positive')
            {
                $ancAutoRemark['hbsag'] = $auroRemarkInv->anc_hbsag;
            }
            if(!empty($auroRemarkInv->anc_vdrl) && strtolower($auroRemarkInv->anc_vdrl) == 'positive')
            {
                $ancAutoRemark['vdrl'] = $auroRemarkInv->anc_vdrl;
            }
            if (!empty($historyAncOe->late_data) && !empty($historyAncOe->late_data->late_concept) && $historyAncOe->late_data->late_concept == 'Yes' && !empty(($historyAncOe->late_data->late_concept_week)))
            {
                $ancAutoRemark['late_concept'] = 'Yes';
            }

            foreach($historyPatientObs as $key => $value)
            {
                if(isset($value->ho_type_value) && $value->ho_type_value == 'cesarean')
                {
                    $ancAutoRemark['cesarean'] = $key;
                }
            }
            foreach($historyUTData as $key => $value)
            {
                if(!empty($value->position_type) && ($value->position_type == 'breech' || $value->position_type == 'transverse' || $value->position_type == 'oblique'))
                {
                    $ancAutoRemark['position'] = $key;
                }
           
            }
        }
        if($ancHistoryVisit)
        {
            foreach($ancHistoryVisit as $visit)
            {
                $auroRemarkInv = (!empty($visit->investigation)) ? json_decode($visit->investigation) : null;
                $investigationDetails = !empty($auroRemarkInv->investigation_details) ? (array)$auroRemarkInv->investigation_details : null;
                $historyAncOe = (!empty($visit->o_e)) ? json_decode($visit->o_e) : null;
                $historyUTData = !empty($historyAncOe->utdata) ? $historyAncOe->utdata : [];
                if($investigationDetails && isset($investigationDetails['12'])  && (substr (strtolower($investigationDetails['12']), -3) == '-ve' || strtolower($investigationDetails['12']) == 'negative' || strpos(strtolower($investigationDetails['12']), 'negative') !== false))
                {
                    $ancAutoRemark['blood_group'] = $investigationDetails['12'];
                }
                if($investigationDetails && isset($investigationDetails['8']) && (substr (strtolower($investigationDetails['8']), -3) == '+ve' || strtolower($investigationDetails['8']) == 'positive' || strpos(strtolower($investigationDetails['8']), 'positive') !== false))
                {
                    $ancAutoRemark['hbsag'] = $investigationDetails['8'];
                }
                if($investigationDetails && isset($investigationDetails['10']) && (substr (strtolower($investigationDetails['10']), -3) == '+ve' || strtolower($investigationDetails['10']) == 'positive' || strpos(strtolower($investigationDetails['10']), 'positive') !== false))
                {
                    $ancAutoRemark['hiv'] = $investigationDetails['10'];
                }
                if(!empty($auroRemarkInv->anc_hiv) && strtolower($auroRemarkInv->anc_hiv) == 'positive')
                {
                    $ancAutoRemark['hiv'] = $auroRemarkInv->anc_hiv;
                }
                if(!empty($auroRemarkInv->anc_hbsag) && strtolower($auroRemarkInv->anc_hbsag) == 'positive')
                {
                    $ancAutoRemark['hbsag'] = $auroRemarkInv->anc_hbsag;
                }
                if(!empty($auroRemarkInv->anc_vdrl) && strtolower($auroRemarkInv->anc_vdrl) == 'positive')
                {
                    $ancAutoRemark['vdrl'] = $auroRemarkInv->anc_vdrl;
                }
                if (!empty($historyAncOe->late_data) && !empty($historyAncOe->late_data->late_concept) && $historyAncOe->late_data->late_concept == 'Yes' && !empty(($historyAncOe->late_data->late_concept_week)))
                {
                    $ancAutoRemark['late_concept'] = 'Yes';
                }
                foreach($historyUTData as $key => $value)
                {
                    if(!empty($value->position_type) && ($value->position_type == 'breech' || $value->position_type == 'transverse' || $value->position_type == 'oblique'))
                    {
                        $ancAutoRemark['position'] = $value->position_type;
                    }
                    if(!empty($value->liquor_type) && ($value->liquor_type == 'oligo' || $value->liquor_type == 'poly'))
                    {
                        $ancAutoRemark['liquor'] = $value->liquor_type;
                    }
                    if(!empty($value->placenta)){
                    
                        $placentaValue = '';
                        foreach($value->placenta as $value1)
                        {
                            $placentaValue = !empty($placentaValue) ? $placentaValue.', '.$placenta[$value1] : $placenta[$value1];
                        }
                        $ancAutoRemark['placenta'] =  $placentaValue;
                    }     
                }
            }
        }
        return $ancAutoRemark;
    }
}
