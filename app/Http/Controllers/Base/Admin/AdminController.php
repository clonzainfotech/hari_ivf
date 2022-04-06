<?php

namespace App\Http\Controllers\Base\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Pusher\Pusher;
use Carbon\Carbon;
use Exception;
use Revolution\Google\Sheets\Facades\Sheets;
use View;
use Auth;
use Log;

class AdminController extends BaseController
{
    /**
     * @param null $pId
     * @return Collection
     */
    public function getPatients($pId = null) {
        $patients = $this->OpdPatients->where('is_approved',1)->orderBy('name','ASC');
        if(!empty($pId)){
            $patients = $patients->whereIn('id',$pId);
        }
        return collect($patients->pluck('name', 'id'))
            ->map(function ($query) {
                $query = ucwords(strtolower($query));
                return $query;
            });
    }

    /**
     * @param null $pId
     * @return mixed
     */
    public function getPatientsId($pId = null) {
        $patients = $this->OpdPatients;
        if(!empty($pId)){
            $patients = $patients->where('id',$pId);
        }
        return $patients->first();
    }

    /**
     * @return mixed
     */
    public function getPatientscode() {
        return $this->OpdPatients->where('is_approved',1)->pluck('code','code');
    }

    /**
     * @return mixed
     */
    public function getHoData() {
        return  $this->HoDetails->pluck('name','name')->toArray();
    }

    /**
     * @param $categoryId
     * @return int
     */
    public function categoryNewToOld($categoryId) {
        switch (true) {
            case ($categoryId == 1):
                $categoryId = 2;
                break;
            case ($categoryId == 3):
                $categoryId = 4;
                break;
            case ($categoryId == 5):
                $categoryId = 6;
                break;
            case ($categoryId == 8):
                $categoryId = 9;
                break;
            default:
                $categoryId = $categoryId;
        }
        return $categoryId;
    }

    /**
     * @param $data
     * @return array
     */
    public function nextAppointmentData($data){
        // dd('gdfg');
        $isProcedure = !empty($data['is_procedure']) ? 1 : 0;
        $appointmentId = decrypt($data['appointmentId']);
        $appointmentData = $this->Appointment->where('id',$appointmentId)->first();
        $lastAppointment = $this->Appointment->where('patients_id',$appointmentData->patients_id)->where('is_done',1)->orderBy('id','desc')->first();
        $date = Carbon::parse($data['date'])->format('Y-m-d');
        if($appointmentData['date'] > date('Y-m-d') && $appointmentData->usg_status == 0){
            $appointmentData->date = $date;
            $appointmentData->usg_status = !empty($data['usg_status']) ? $data['usg_status'] : 0;
            $appointmentData->time = (!empty($data['time'])) ? Carbon::parse($data['time'])->format('H:i:s') : null;
            if(!empty($data['is_gynec']) && $data['is_gynec'] == 1){
                $appointmentData->category_id = 17;
            }
            $appointmentData->is_procedure = $isProcedure;
            $appointmentData->category_id = !empty($data['category']) ? $data['category'] : $appointmentData->category_id;
            $appointmentData->updated_by = Auth::user()->id;
            $appointmentData->medical_note = !empty($lastAppointment) ? $lastAppointment->medical_note : '';
            $appointmentData->save();
            // update Age
            $patient_check = $this->OpdPatients->find($appointmentData->patients_id);
            if(!empty($patient_check))
            {
                $patient_check->age = !empty($patient_check->dob) ? \Carbon\Carbon::parse($patient_check->dob)->age : $patient_check->age;
                $patient_check->save();
            }
        }else{
            

            if($appointmentData['date'] != $date && $appointmentData['date'] < $date){
                $appointment = $this->Appointment;
                $appointment->date = $date;
                $appointment->is_procedure = $isProcedure;
                $appointment->remark = !empty($data['remark']) ? $data['remark'] : null;
                $appointment->time = (!empty($data['time'])) ? Carbon::parse($data['time'])->format('H:i:s') : null;
                $appointment->arrival_time = !empty($data['arrival_time']) ? $data['arrival_time'] : null;
                $categoryId = $appointmentData->category_id;
                $ancCategoryId = 5;
                switch (true) {
                    case ($categoryId == 1):
                        $appointment->category_id = 2;
                        break;
                    case ($categoryId == 3):
                        $appointment->category_id = 4;
                        break;
                    case ($categoryId == 5):
                        $ancCategoryId = 6;
                        $appointment->category_id = 6;
                        break;
                    case ($categoryId == 8):
                        $appointment->category_id = 9;
                        break;
                    default:
                        $appointment->category_id = $categoryId;
                }
                if(!empty($data['isAnc']) && $data['isAnc'] == true){
                    $appointment->category_id = $ancCategoryId;
                }
                if(!empty($data['is_gynec']) && $data['is_gynec'] == 1){
                    $appointment->category_id = 17;
                }
                $appointment->category_id = !empty($data['category']) ? $data['category'] : $appointment->category_id;
                $appointment->created_by = Auth::user()->id;
                $appointment->updated_by = Auth::user()->id;
                $appointment->seen_by = !empty($appointmentData->getPatientsDetails['hospital_doctor_id']) ? $appointmentData->getPatientsDetails['hospital_doctor_id'] : 0;
                $appointment->usg_status = !empty($data['usg_status']) ? $data['usg_status'] : 0;
                $appointment->patients_id = $appointmentData->patients_id;
                $appointment->medical_note = !empty($lastAppointment) ? $lastAppointment->medical_note : '';
                $appointment->save();
                // update Age
                $patient_check = $this->OpdPatients->find($appointmentData->patients_id);
                if(!empty($patient_check))
                {
                    $patient_check->age = !empty($patient_check->dob) ? Carbon::parse($patient_check->dob)->age : $patient_check->age;
                    $patient_check->save();
                }
                $date = Carbon::parse($appointment->date)->format('d/m/Y');
            }

        }
        return ['date'=>$date];
    }

    /**
     * @param $start
     * @param $end
     * @param $interval
     * @param string $format
     * @return array
     */
    public function appointmentTime($start, $end, $interval, $format = '24') {
        $startTime = strtotime($start);
        $endTime   = strtotime($end);
        $format = '12';
        $returnTimeFormat = ($format == '12')?'g:i A':'G:i:s';
        // $returnTimeFormat = ($format == '12')?'g:i':'G:i:s';
        $current   = time();
        $addTime   = strtotime('+'.$interval, $current);
        $diff      = $addTime - $current;
        $times = [];
        $startLunch = strtotime('14:00:00');
        $endLunch = strtotime('15:55:00');
        while ($startTime < $endTime) {
            $times[] = date($returnTimeFormat, $startTime);
            $startTime += $diff;
        }
        $times[] = date($returnTimeFormat, $startTime);
        unset($times[72]);
        return $times;
    }

    /**
     * @param $requestMedicine
     */
    public function medicineData($requestMedicine){
        $medicine = $this->Medicine->pluck('name','name')->toArray();
        if(!empty($requestMedicine)){
            $diffrent = array_diff($requestMedicine,$medicine);
            foreach($diffrent as $row){
                $medicineData = new $this->Medicine;
                $medicineData->name = $row;
                $medicineData->save();
            }
        }
    }

    /**
     * @param $requestComplaints
     */
    public function complaintStore($requestComplaints){
        $complaint = $this->Complaint->pluck('name','name')->toArray();
        if(!empty($requestComplaints['co_type'])){
            $diffrent = array_diff($requestComplaints['co_type'],$complaint);
            foreach($diffrent as $row){
                $complaintData = new $this->Complaint;
                $complaintData->name = $row;
                $complaintData->save();
            }
        }
    }

    /**
     * @param $treatmentData
     */
    public function treatmentData($treatmentData){
        // unset($treatmentData['medicinedata']);
        // foreach($treatmentData as $row){
        //     if(@$row['medicine']) {
        //         $medicine = $this->Medicine->where('name',$row['medicine'])->first();
        //         $medicine->quantity = @$row['quantity']?$row['quantity_2']:0;
        //         $medicine->quantity_2 = @$row['quantity_2']?$row['quantity_2']:0;
        //         $medicine->quantity_3 = @$row['quantity_3']?$row['quantity_3']:0;
        //         $medicine->quantity_4 = @$row['quantity_4']?$row['quantity_4']:0;
        //         $medicine->medicine_status = $row['medicine_status'];
        //         $medicine->dose = $row['dose'];
        //         $medicine->number = $row['no'];
        //         $medicine->save();
        //     }
        // }
    }

    /**
     * @param $number
     * @return string
     */
    public function getWordOfNumber($number)
    {
        $number = 	$number;
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $plural = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $counter = count($str);
                // $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
        return $result.' '.$points;
    }

    /**
     * @param $hoData
     * @return bool
     */
    public function hoData($hoData){
        $checkHoData = $this->HoDetails->where('name',$hoData)->first();
        if(!$checkHoData){
            $hoDataStore = $this->HoDetails;
            $hoDataStore->name = $hoData;
            $hoDataStore->save();
        }
        return true;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getExistedMedicineData(Request $request) {
        return [
            'status' => true,
            'data' => $this->Medicine->whereName($request->medicine_name)->first()
        ];
    }

    /**
     * @param $request
     * @param $data
     * @param $numberOfData
     * @return LengthAwarePaginator
     */
    public function paginate($request, $data, $numberOfData) {
        $dataPagination = [];
        $currentPage = !empty($request->page) ? $request->page : 1;
        $collection = new Collection($data);
        $perPage = !empty($numberOfData) ? $numberOfData : 10;
        $currentPageResults = $collection->slice(($currentPage-1) * $perPage, $perPage)->values();
        $dataPagination = new LengthAwarePaginator($currentPageResults, count($collection), $perPage);
        return $dataPagination->setPath($request->url());
    }

    /**
     * @param Request $request
     * @return array|void
     */
    public function getComplaintWiseMedicine(Request $request){
        try{
            $cMedicine = [];
            $hoMedicine = [];
            $triggerMedicine = [];
            $complaintMedicine = [];
            $medicineArray = [];
            $hocoId = [];
            if($request->co){
                $cId = $this->Complaint->whereIn('name',$request->co)->pluck('id')->toArray();
                $cMedicine = $this->ComplaintMedicine->with('getMedicinesData')->where('type',1)->whereIn('type_id',$cId)->pluck('medicine_id','medicine_id')->toArray();
            }
            if($request->ho){
                $hId = $this->HoDetails->where('name',$request->ho)->first();
                $hoDetails = null;
                $bothZero = false;
                if($hId){
                    $hoDetails = strtolower($hId->name);
                    $hoMonth = str_replace('month', '-', $hoDetails);
                    if(strpos($hoDetails,'months') !== false || strpos($hoDetails,'days') !== false){
                        $hoDetails = str_replace('s', '', $hoMonth);
                    }else{
                        $hoDetails = $hoMonth;
                    }
                    if(strpos($hoDetails,'day') !== false){
                        $hoDetails = str_replace('day', '', $hoDetails);
                    }
                    if($hoDetails == '0 - 0 '){
                        $bothZero = true;
                    }
                }

                $hoDetails = str_replace(' ', '', $hoDetails);
                $hoMonth = explode('-',$hoDetails);
                $hoMonthData = !empty($hoMonth[0]) ? (int)$hoMonth[0] : 0;
                $hoDay = !empty($hoMonth[1]) ? (int)$hoMonth[1] : 0;
                $hcId = null;
                if($hoMonthData == 0 && $hoDay == 0 && !$bothZero){
                    $hoName = strtolower($request->ho);
                    $hoCategoryId = $this->HoCategory->whereRaw('LOWER(`name`) LIKE ? ',$hoName)->first();
                    if($hoCategoryId){
                        $hcId = $hoCategoryId->id;
                    }
                }else{
                    if($hoMonthData >= 0 && ($hoMonthData < 3) || ($hoMonthData == 3 && $hoDay == 0)){
                        $hoCategoryId = $this->HoCategory->where('name','1 to 3 month')->first();
                        if($request->isIvf == 'true'){
                            $hoCategoryId = $this->HoCategory->where('name','1 to 3 month IVF')->first();
                        }
                        $hcId = $hoCategoryId->id;
                    }
                    if($hoMonthData > 3 && ($hoMonthData < 9) || ($hoMonthData == 3 && $hoDay > 0) || ($hoMonthData == 9 && $hoDay == 0)){
                        $hoCategoryId = $this->HoCategory->where('name','3 to 9 month')->first();
                        $hcId = $hoCategoryId->id;
                    }
                }
                $hoMedicine = $this->ComplaintMedicine->with('getMedicinesData')->where('type',2)->where('type_id',$hcId)->pluck('medicine_id','medicine_id')->toArray();
            }
            if($request->isSp2 == 'true'){
                $hoCategoryId = $this->HoCategory->where('name','sp2 start')->first();
                if($hoCategoryId){
                    $hcId = $hoCategoryId->id;
                    $hoMedicine = $this->ComplaintMedicine->with('getMedicinesData')->where('type',2)->where('type_id',$hcId)->pluck('medicine_id','medicine_id')->toArray();
                }
            }
            if($request->isOvalution == 'true'){
                $hoCategoryId = $this->HoCategory->where('name','IUI - Rup')->first();
                if($hoCategoryId){
                    $hcId = $hoCategoryId->id;
                    $hoMedicine = $this->ComplaintMedicine->with('getMedicinesData')->where('type',2)->where('type_id',$hcId)->pluck('medicine_id','medicine_id')->toArray();
                }
            }
            if($request->isTrigger == 'true'){
                $hoCategoryId = $this->HoCategory->where('name','Trigger medicine')->first();
                if($hoCategoryId){
                    $hcId = $hoCategoryId->id;
                    $triggerMedicine = $this->ComplaintMedicine->with('getMedicinesData')->where('type',2)->where('type_id',$hcId)->pluck('medicine_id','medicine_id')->toArray();
                    $hoMedicine = array_merge($hoMedicine,$triggerMedicine);
                }
            }
            if($request->isTransfer == 'true'){
                $hoCategoryId = $this->HoCategory->where('name','Transfer report clear')->first();
                if($hoCategoryId){
                    $hcId = $hoCategoryId->id;
                    $transferMedicine = $this->ComplaintMedicine->with('getMedicinesData')->where('type',2)->where('type_id',$hcId)->pluck('medicine_id','medicine_id')->toArray();
                    $hoMedicine = array_merge($hoMedicine,$transferMedicine);
                }
            }
            $coHoMedicines = array_merge($cMedicine,$hoMedicine);
            if(!empty($coHoMedicines)){
                $complaintMedicine = collect($this->ComplaintMedicine->with('getMedicinesData')->whereIn('medicine_id',$coHoMedicines)->groupBy('medicine_id')->get());
                $medicineArray = $complaintMedicine->pluck('getMedicinesData.name','getMedicinesData.name')->toArray();
            }
            return ['medicines'=>$complaintMedicine,'medicineArray'=>$medicineArray];
        }catch(Exception $e){
            // dd($e);
        }
    }

    /**
     * @param $type
     * @param $patientsId
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function patientReport($type,$patientsId,Request $request){
        try{
            $rType = decrypt($type);
            $pId = decrypt($patientsId);
            $iuiData = null;
            $report = null;
            if($rType == 1){
                $iuiCycle = $this->IUI->wherePatientsId($pId)->pluck('cycle_no','cycle_no')->toArray();
                $cycleNo = $request->cycle_no;
                if(!$cycleNo){
                    $cycleNo = $this->IUI->wherePatientsId($pId)->orderBy('cycle_no','DESC')->value('cycle_no');
                }
                $iuiData = $this->IUI->whereCycleNo($cycleNo)->wherePatientsId($pId)->first();
                $report = !empty($iuiData->investigation) ? json_decode($iuiData->investigation) : null;
            }
            if($request->ajax()){
                $data['status'] = 1;
                $data['iuiData'] = $iuiData;
                $data['iuiCycle'] = $iuiCycle;
                $data['report'] = $report;
                $data['report_data'] = View::make('admin.iui.report_data',$data)->render();
                return $data;
            }
            return view('admin.iui.report',compact('report','iuiData','patientsId','type','iuiCycle','cycleNo'));
        }catch(Exception $e){
            // dd($e);
            return redirect('iui');
        }
    }

    /**
     * @param $request
     * @param $logType
     * @param $response
     */
    public function storeLog($request,$logType,$response){
        $url = $request->url();
        $parameter = $request->all();
    }

    /**
     * @return \string[][]
     */
    public function allInvestigationReport(){
        $reportData = ['1'=>'CBC / MP','2'=>'FBS','3'=>'Urine - R','4'=>'PPBS','5'=>'ESR','6'=>'RBS','7'=>'SGPT','8'=>'HBsAg','9'=>'S.Creatinine','10'=>'HIV','11'=>'CRP','12'=>'Blood Group','13'=>'Serum Widal',
            '14'=>'TSH','15'=>'Typhidot lgM','16'=>'T3, T4, TSH','17'=>'Lipid Profile','18'=>'Vit B-12','19'=>'Tube Widal','20'=>'Vit D-3','21'=>'LFT','22'=>'ANC Profile','23'=>'RFT','24'=>'Pre oper.Profile(Major)',
            '25'=>'S.Calcium','26'=>'Pre oper.Profile(Minor)','27'=>'S.Eletrolytes','28'=>'Dengue Duo','29'=>'S.Billirubin','30'=>'Dengue NS1','31'=>'HB','32'=>'Pre oper.Profile(Minor-wife)'
        ];
        return ['reportData'=>$reportData];
    }

    /**
     * @param $type
     */
    public function updateLmp($type){
        if($type == 1){
            $ivfHistoryPatientList = $this->IuiHistory->where('visit',2)->groupBy('patients_id')->get();
            $lmpdate = null;
            foreach($ivfHistoryPatientList as $row){
                $data = json_decode($row->description);
                $lmpData = $data->lmp;
                if(!empty($lmpData->date)){
                    $lmpdate = Carbon::parse($lmpData->date)->format('Y-m-d');
                }
                $this->IUI->where('patients_id',$row->patients_id)->update(['lmp_date'=>$lmpdate]);
            }
        }else{
            $ivfHistoryPatientsId = $this->IvfHistory->where('visit',2)->get();
            foreach($ivfHistoryPatientsId as $row){
                $data = json_decode($row->description);
                $lmpData = $data->lmp;
                if(!empty($lmpData->date)){
                    $lmpdate = Carbon::parse($lmpData->date)->format('Y-m-d');
                }
                $this->IVF->where('patients_id',$row->patients_id)->update(['lmp_date'=>$lmpdate]);
            }
        }
    }

    /**
     * @param Request $request
     * @throws \Pusher\PusherException
     */
    public function patient_notification(Request $request)
    {
        $name = $request->name;
        $cat = $request->cat;
        $title = $request->title;
        $remove_notification = $this->patientNotification->truncate();
        $patientnotify = $this->patientNotification;
        $patientnotify->name = $name ;
        $patientnotify->category = $cat;
        $patientnotify->opd_area = $title;
        $patientnotify->save();
        $patientsId = decrypt($request->pId);
        $now = Carbon::now()->format('Y-m-d');
        $patients = $this->OpdPatients->find($patientsId);
        $updateApooitnment = $this->Appointment->where('patients_id',$patientsId)->where('date',$now)->update(['in_consulting_room'=>1]);

        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $user = ucwords(strtoupper(\Auth::user()->name));
        $body = 'Dear ,'.$name.'. '.$user .' is Calling you in OPD Number '.$patientnotify->opd_area.' . Please Follow the Information and take your Visit. Thank you.';
        $this->sendNotification($patientsId,$patients->device_token,$body,1);
        $data = [
            'name' => $patientnotify->name,
            'category' => $patientnotify->category,
            'opd_area' => $patientnotify->opd_area,
            'id' => $patientnotify->id,
            'user' => $user
        ] ;

        $pusher->trigger('notify-channel', 'App\\Events\\Notify', $data);
    }

    /**
     * @param Request $request
     */
    public function remove_notification(Request $request)
    {
            $patientNotification = $this->patientNotification->first();
            $read_by = Auth::user()->name;
            $patientNotification->read_by = $read_by;
            $patientNotification->save();
    }

    /**
     * @param $str
     * @param $starting_word
     * @param $ending_word
     * @return mixed|string
     */
    public function string_between_two_string($str, $starting_word, $ending_word){
        $arr = explode($starting_word, $str);
        if (isset($arr[1])){
            $arr = explode($ending_word, $arr[1]);
            return $arr[0];
        }
        return '';

      }

    /**
     * store category wise notification data
     * @param $data
     */
    public function storeCategoryNotification($data)
    {
        $categoryNotification =  $this->CategoryNotification->whereDate('date',$data['date'])->where('patients_id',$data['patients_id'])->where('category_id',$data['category_id'])->get();
        if(!empty($categoryNotification))
        {
            $categoryNotification = $this->CategoryNotification;
            $categoryNotification->patients_id = $data['patients_id'];
            $categoryNotification->date = $data['date'];
            $categoryNotification->reminder_date = $data['reminder_date'];
            $categoryNotification->message = $data['message'];
            $categoryNotification->category_id = $data['category_id'];
            // dd($categoryNotification);
            $categoryNotification->save();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function get_category_notification(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $payment = [];
        $yester = Carbon::parse($now)->subDays(1)->format('Y-m-d');
        $data = $this->CategoryNotification->with('getPatients')->whereDate('reminder_date','<',$yester)->delete();
        $auth_id = Auth::user()->id;
        $category = $this->CategoryNotification->with('getPatients')->whereDate('date','=',$now)->groupBy('patients_id');
        $category = collect($category->get())
                    ->map(function ($query) use($auth_id){
                        $read_by = !empty($query->read_by) ? explode(',',$query->read_by) : [];
                        if(empty($query->read_by) || !in_array($auth_id,$read_by))
                        {
                            $query->patient_name = ucWords($query->getPatients['name']);
                            $query->date = Carbon::parse($query->date)->format('d M Y h:i a');
                            return $query;
                        }
                    });
        if(in_array(Auth::user()->role,['1,2']))
        {
            $payment = $this->IvfPaymentReminder->whereDate('date',$now)->where('status',0);
            $payment = collect($payment->get())
                        ->map(function ($query)
                        {
                                $query->patient_name = ucWords($query->getPatientsData['name']);
                                $query->date = Carbon::parse($query->date)->format('d M Y');
                                return $query;
                        });
        }
        $fillUPdate = Carbon::now()->addDays(5)->format('Y-m-d');
        $report = $this->IvfPlanReport->where('plan',1);
        $report = collect($report->get())
            ->map(function ($query) use($now){
                $description = json_decode($query->description);
                if((empty($description->loop_1) && empty($description->loop_2) && empty($description->loop_3) && empty($description->loop_4)) && Carbon::parse($query->created_at)->format('Y-m-d') > '2021-11-10' && Carbon::parse($query->created_at)->diffInDays($now) >= 5)
                {
                    // dd($query->patients_id);
                    $query->patient_name = ucWords($query->getPatients['name']);
                    $query->date = Carbon::parse($query->created_at)->format('d M Y');
                    return $query;
                }
        });
        return response()->json([
            'status'=>1,
            'data' => array('category'=>$category,"payment"=>$payment,"report" =>$report)
        ]);
    }

    /**
     * @param $data
     */
    public function addGoogleSheet($data) {
        Sheets::spreadsheet(config('app.expense_sheet_id'))
            ->sheet(config('app.expense_sheet_name'))
            ->append([$data]);
        return true;
    }
    
}
