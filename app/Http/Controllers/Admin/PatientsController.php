<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Validator;
use Session;
use View;
use Auth;
use Log;
use DB;


class PatientsController extends AdminController
{
    /**
    * Get Dose list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request) {
        try{
            $state = $this->getState()['state'];
            $doctor = $this->getDoctor();
            $referenceDoctor = $doctor['referenceDoctor'];
            $city = $this->City->pluck('name','name');
            $patients = collect($this->OpdPatients->orderBy('name','ASC')->pluck('name', 'id'))
                ->map(function ($query) {
                    $query = ucwords(strtolower($query));
                    return $query;
                });
            $patient = $this->OpdPatients->select("*",
                \DB::raw('
                    (CASE
                        WHEN gender = "1" THEN "Male"
                        WHEN gender = "2" THEN "Female"
                    END)
                    AS gender')
                )->orderBy('id','DESC');
            if($request->ajax()){
            
                // search text
                $patientId = $request->patient_id;

                if ($patientId) {
                    $patient = $patient->where('id', $patientId);
                }
                $referenceDoctorId = $request->reference_doctor_id;

                if($referenceDoctorId){
                    $patient = $patient->where('reference_doctor_id', $referenceDoctorId);
                }
                $search = $request->search;
                if($search){
                    $patient = $patient->where('mobile_number','LIKE',$search.'%')->orWhere('main_area','LIKE',$search.'%');
                    }
                if($request->date){
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('m-d');
                    if($date){
                        $patient = $patient->whereBetween(DB::raw("(DATE_FORMAT(dob,'%m-%d'))"), [$startDate, $endDate]);

                    }
                }
                if($request->is_print == 1){
                    $patient = $this->OpdPatients->select("*",
                \DB::raw('
                    (CASE
                        WHEN gender = "1" THEN "Male"
                        WHEN gender = "2" THEN "Female"
                    END)
                    AS gender')
                )->orderBy('id','DESC')->get();
                    $data['patient'] = View::make('admin.appointment.patient.preview',compact('patients', 'patient', 'city', 'state', 'doctor', 'referenceDoctor'))->render();
                    $data['status'] = 2;
                    return response()->json($data);
                }
                $patient = $patient->paginate(100);
                $data['patient'] = View::make('admin.appointment.patient.data',compact('patients', 'patient', 'city', 'state', 'doctor', 'referenceDoctor'))->render();
                $data['status'] = 1;
                return response()->json($data);
            }
            return view('admin.appointment.patient.index', compact('patients', 'patient', 'city', 'state', 'doctor', 'referenceDoctor'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Return on appointment patient edit page
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function edit($id,Request $request) {
        try{
            $patientId = decrypt($id);
            $patient = $this->OpdPatients->find($patientId);
            $city = $this->City->pluck('name','name');
            $state = $this->getState()['state'];
            $doctor = $this->getDoctor();
            $referenceDoctor = ['other'=>'Other'] + $doctor['referenceDoctor']->toArray();
            $proReferenceDoctor = ['other'=>'Other'] + $this->ReferenceDoctorPro->pluck('name','id')->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            return view('admin.appointment.patient.edit', compact('patient','city','state','referenceDoctor','proReferenceDoctor','hospitalDoctor'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on appointment patient create page
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request){
        try{
            $patient = null;
            $self_bookingId = null;
            if($request->booking_id)
            {
                $self_bookingId = decrypt($request->booking_id);
                $patient = $this->PatientSignup->select('*',DB::raw('reason as is_pregnant'))->where('id',$self_bookingId)->first();
                // dd($patient);
            }
            $city = $this->City->pluck('name','name');
            $state = $this->getState()['state'];
            $doctor = $this->getDoctor();
            $referenceDoctor = ['other'=>'Other'] + $doctor['referenceDoctor']->toArray();
            $proReferenceDoctor = ['other'=>'Other'] + $this->ReferenceDoctorPro->pluck('name','id')->toArray();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            return view('admin.appointment.patient.edit', compact('city','state','patient','self_bookingId','referenceDoctor','proReferenceDoctor','hospitalDoctor'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    
    /**
    * Update patient
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id=null) {
        $patient = $this->OpdPatients;
        $patientId = null;
        if($id != null){
            $patientId = decrypt($id);
            $patient = $this->OpdPatients->find($patientId);
        }
        $this->validate($request,[
            'name' => 'required|min:2',
            'gender' => 'required',
            'state' => 'required',
            'mobile_number' => 'required|numeric|digits:10|unique:patients,mobile_number,'.$patientId,
            'dob' =>'nullable|before:' . date('Y-m-d')
        ]);
        try{
           
                $patient->name=$request->name;
                $patient->age=$request->age;
                $patient->months = $request->months;
                $patient->days = $request->days;
                $patient->gender=$request->gender;
                $patient->location=$request->loaction;
                $patient->residence=$request->residence;
                $patient->main_area=$request->main_area;
                $patient->city = $request->city_1;
                if($request->city_1 == 'Other'){
                    $city = $this->City;
                    $city->name = $request->city_2;
                    $city->save();
                    $patient->city = $request->city_2;
                }
                $patient->state=$request->state;
                //reference doctor
                if ($request->input('reference_doctor') == 'other') {
                    $referenceDoctor = $this->ReferenceDoctor;
                    $referenceDoctor->name = $request->doctor_name;
                    $referenceDoctor->mobile_number = $request->doctor_mobile_number;
                    $referenceDoctor->created_by = Auth::user()->id;
                    $referenceDoctor->save();
                }

                //reference doctor
                if ($request->input('pro_reference_doctor') == 'other') {
                    $proReferenceDoctor = $this->ReferenceDoctorPro;
                    $proReferenceDoctor->name = $request->pro_reference_doctor_name;
                    $proReferenceDoctor->mobile_number = $request->pro_reference_doctor_mobile_number;
                    $proReferenceDoctor->created_by = Auth::user()->id;
                    $proReferenceDoctor->save();
                }

                $patient->reference_doctor_id = ($request->input('reference_doctor') == 'other') ? $referenceDoctor->id : $request->reference_doctor;
                $patient->reference_doctor_pro_id = ($request->input('pro_reference_doctor') == 'other') ? $proReferenceDoctor->id : $request->pro_reference_doctor;
                $patient->mobile_number=$request->mobile_number;
                $patient->other_mobile_number=$request->other_mobile_number;
                $patient->other_patient_reference=$request->other_patient_reference;
                $patient->email=$request->email;
                $patient->weight=$request->weight;
                $patient->height=$request->height;
                $patient->is_pregnant=$request->pregnant;
                $patient->occupation=$request->occupation;
                $patient->dob=$request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;   
                $patient->save();
                if($request->self_bookingId)
                {
                    $user_id = Auth::user()->id;
                    $self_booking = $this->PatientSignup->where('id',decrypt($request->self_bookingId))->update(['is_approved' => '1','approved_by'=>$user_id]);
                }
                if (!$request->code) {
                $generateCode = $this->generateCode($patient->id,$patient->name);
                $patient = $this->OpdPatients->whereId($patient->id)->first();
                $patient->code = $generateCode['code'];
                // if ($request->hasFile('image')) {
                //     $this->removeImage($patient->image);
                //     $image = $request->file('image');
                //     $profilePicture = $this->uploadImage($image, 'public/upload/patient');
                //     $patient->image = 'public/upload/patient/' . $profilePicture;
                // }
                $patient->save();
                $doctor = $this->getDoctor();
                $referenceDoctor = $doctor['referenceDoctor'];
                
            }
                return redirect('patient');
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Get patient report
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getPatientReport(Request $request)
    {
        try{
            $patientReportOpd = [];
            $patientReportHormon = [];

            $patient = $this->getPatients();
            $pMobileNumber = $this->OpdPatients->pluck('mobile_number','id')->toArray();
            $category = $this->Category->pluck('name','id')->toArray();
            $pIds = [];
            if($request->ajax()){
                DB::enableQueryLog();
                $patientId = $request->patient_id;
                $category = $request->category;
                $procedures = $this->IndoorProcedure->select('id', 'name')->get()->toArray();
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                $patientReportOpd = $this->Appointment->whereHas('getAppointmentCharges');
                $indoorDeposit = $this->IndoorDeposit->where('case_type','Credit');
                $indoorBook = $this->IndoorBook
                    ->whereIsFinalInvoice(1)
                    ->whereNotNull('final_invoice_date');
                if($patientId)
                {
                    $patientReportOpd = $patientReportOpd->wherePatientsId($patientId);
                    $indoorDeposit = $indoorDeposit->wherePatientId($patientId);
                    $indoorBook = $indoorBook->wherePatientId($patientId);
                }
                if(empty($request->allIncome_fromDate) ||  empty($request->allIncome_toDate))
                {
                    if($fromdate || $todate)
                    {
                        $fromdate = $fromdate;
                        $todate = $todate;
                        $indoorDeposit = $indoorDeposit->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                        $patientReportOpd = $patientReportOpd->whereBetween('date', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);
                        $indoorBook = $indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);
                        
                    }
                }
                if($category)
                {
                    $patientReportOpd = $patientReportOpd->whereCategoryId($category);
                    $pIds = $patientReportOpd->pluck('patients_id','patients_id')->toArray();
                    $indoorDeposit = $indoorDeposit->whereIN('patient_id',$pIds);
                    $indoorBook = $indoorBook->whereIN('patient_id',$pIds);
                }
                //for display all income which is between above range
                if(!empty($request->allIncome_fromDate) &&  !empty($request->allIncome_toDate))
                {
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $pIds = $patientReportOpd->whereBetween('date', [$fromdate . ' 00:00:00', $todate. ' 23:59:59'])->pluck('patients_id','patients_id')->toArray();
                    $allIncome_fromDate = Carbon::parse($request->allIncome_fromDate)->format('Y-m-d');
                    $allIncome_toDate = Carbon::parse($request->allIncome_toDate)->format('Y-m-d');

                    $patientReportOpd = $patientReportOpd->whereIN('patients_id',$pIds);
                    $indoorDeposit = $indoorDeposit->whereIN('patient_id',$pIds);
                    $indoorBook = $indoorBook->whereIN('patient_id',$pIds);

                    $indoorDeposit = $indoorDeposit->whereBetween(\DB::raw('DATE(created_at)'), [$allIncome_fromDate, $allIncome_toDate]);
                    $patientReportOpd = $patientReportOpd->whereBetween('date', [$allIncome_fromDate . ' 00:00:00', $allIncome_toDate. ' 23:59:59']);
                    $indoorBook = $indoorBook->whereBetween('final_invoice_date', [$allIncome_fromDate . ' 00:00:00', $allIncome_toDate. ' 23:59:59']);
                }
                $patientReportOpd = collect($patientReportOpd->get())
                ->map(function ($query) {
                    $query->date = $query->date;
                    return $query;
                });
                $indoorDeposit = collect($indoorDeposit->get())
                    ->map(function ($query) {
                        $query->date = $query->created_at;
                        return $query;
                    });
                $indoorBook = collect($indoorBook->get())
                    ->map(function ($query) {
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        $query->date = $query->final_invoice_date;
                        $query->amount = $query->getInvoice['grand_total_amt'];
                        return $query;
                    });
                    // dd($indoorBook);
                // $patientReportOpd = collect($patientReportOpd);
                // if(!$category)
                // {
                    $patientReportOpd = collect($patientReportOpd)->merge($indoorDeposit);
                    $patientReportOpd = $patientReportOpd->merge($indoorBook);
                // }
                
                $patientReportOpd = $patientReportOpd->sortby('date');
                $patientReportOpd = $patientReportOpd->groupBy('getPatientsDetails.name');
                // dd($patientReportOpd);
                if($request->isprint == 1) {
                    return response()->json([
                        View::make('admin.report.patient.preview', compact('patient','procedures','patientReportOpd','patientReportHormon'))->render()
                    ]);
                }
                return response()->json([
                    View::make('admin.report.patient.data', compact('patientReportOpd','procedures','patientReportHormon', 'patient'))->render()
                ]);
            }
            return view('admin.report.patient.index',compact('patientReportOpd', 'patientReportHormon', 'patient','pMobileNumber','category'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    
     /**
    * Get state list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function getState(){
        $state = $this->State->pluck('name','id');
        return ['state'=>$state];
    }

    /**
    * Get docotr list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function getDoctor(){
        $referenceDoctor = $this->ReferenceDoctor->orderBy('name','ASC')->pluck('name','id');
        $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
        return['referenceDoctor'=>$referenceDoctor,'hospitalDoctor'=>$hospitalDoctor];
    }
     /**
    * Generate patient code
    * @param  \Illuminate\Http\Request $patientsId, $name
    * @return \Illuminate\Http\Response
    */
     private function generateCode($patientsId, $name){
        $name = preg_replace('/\s+/', ' ', $name);
        $name = explode(' ', $name);
        $code = strtoupper($name[0][0]);

        $code .= (!empty($name[1])) ? strtoupper($name[1][0]) : 'R';
        $code .= (!empty($name[2])) ? strtoupper($name[2][0]) : 'R';

        $code .= $patientsId;
        $code = preg_replace('/[^A-Za-z0-9\-]/', 'R', $code);
        return ['code'=>$code];
    }
    /**
     * return all anc ,iui amd ivf history reports
     * @return  array
     * @param $patients(Patient's id)
     */
    public function getAllReports(Request $request,$patient_id)
    {
        //Get all uploded reports
        $patients = decrypt($patient_id);
        $ANCReports = [];
        $IVFReports = [];
        $IUIReports = [];
        $GynecReports = [];
        $patientsDetails = $this->OpdPatients->whereId($patients)->first();
        $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
        $ancAllVisit = $this->ANC->where('patients_id',$patients)->get();
        $ancAllHistoryVisit = $this->AncHistory->where('patients_id',$patients)->get();
        $status = !empty($request->status) ? $request->status : null;
        if($ancAllVisit)
        {
            foreach($ancAllVisit as $ancVisit)
            {
                $reportDate = Carbon::parse($ancVisit->created_at)->format('Y-m-d H:i:s');
                $investigationReport = !empty($ancVisit->investigation) ? json_decode($ancVisit->investigation,true) : '';
                $usgReport = !empty($ancVisit->usg) ? json_decode($ancVisit->usg,true) : '';
                $ANCReports[$reportDate]['early_scan'] = !empty($investigationReport['investigation_early_scan_type']['images']) ? $investigationReport['investigation_early_scan_type']['images'] : [];
                $ANCReports[$reportDate]['growth_report'] = !empty($investigationReport['growth_report']['images']) ? $investigationReport['growth_report']['images'] : [];
                $ANCReports[$reportDate]['other_report'] = !empty($investigationReport['other_report_data']['images']) ? $investigationReport['other_report_data']['images'] : [];
                $ANCReports[$reportDate]['anc_report'] = !empty($investigationReport['anc']['images']) ? $investigationReport['anc']['images'] : [];
                $ANCReports[$reportDate]['usg_report'] = !empty($usgReport['images']) ? $usgReport['images'] : [];
            }
        }
        if($ancAllHistoryVisit)
        {
            foreach($ancAllHistoryVisit as $ancHistoryVisit)
            {
                $reportDate = Carbon::parse($ancHistoryVisit->created_at)->format('Y-m-d H:i:s');
                $investigationHistoryReport = !empty($ancHistoryVisit->investigation) ? json_decode($ancHistoryVisit->investigation,true) : '';
                $usgHistoryReport = !empty($ancHistoryVisit->usg) ? json_decode($ancHistoryVisit->usg,true) : '';
                $ANCReports[$reportDate]['early_scan'] = !empty($investigationHistoryReport['investigation_early_scan_type']['images']) ? $investigationHistoryReport['investigation_early_scan_type']['images'] : [];
                $ANCReports[$reportDate]['growth_report'] = !empty($investigationHistoryReport['growth_report']['images']) ? $investigationHistoryReport['growth_report']['images'] : [];
                $ANCReports[$reportDate]['other_report'] = !empty($investigationHistoryReport['other_report_data']['images']) ? $investigationHistoryReport['other_report_data']['images'] : [];
                $ANCReports[$reportDate]['anc_report'] = !empty($investigationHistoryReport['anc']['images']) ? $investigationHistoryReport['anc']['images'] : [];
                $ANCReports[$reportDate]['usg_report'] = !empty($usgHistoryReport['images']) ? $usgHistoryReport['images'] : [];
            }
        }
        $ivfAllVisit = $this->IVF->where('patients_id', $patients)->get();
        $ivfAllHistoryVisit = $this->IvfHistory->where('patients_id',$patients)->get();
        $ivfAllExtraVisit = $this->IvfExtraVisit->where('patient_id',$patients)->get();

        if($ivfAllVisit)
        {
            foreach($ivfAllVisit as $ivfVisit)
            {
                $reportDate = Carbon::parse($ivfVisit->created_at)->format('Y-m-d H:i:s');
                $investigationReport = !empty($ivfVisit->investigation) ? json_decode($ivfVisit->investigation,true) : '';
                $IVFReports[$reportDate]['hystroscopy'] = !empty($investigationReport['hystroscopy']['images']) ? $investigationReport['hystroscopy']['images'] : [];
                $IVFReports[$reportDate]['laproscopy'] = !empty($investigationReport['laproscopy']['images']) ? $investigationReport['laproscopy']['images'] : [];
                $IVFReports[$reportDate]['hcg'] = !empty($investigationReport['hcg']['images']) ? $investigationReport['hcg']['images'] : [];
                $IVFReports[$reportDate]['blood_report'] = !empty($investigationReport['blood_report']['image']) ? $investigationReport['blood_report']['image'] : [];
                $IVFReports[$reportDate]['hsa_report'] = !empty($investigationReport['hsa_report']['images']) ? $investigationReport['hsa_report']['images'] : [];

            }
        }
        if($ivfAllHistoryVisit)
        {
            foreach($ivfAllHistoryVisit as $ivfHistoryVisit)
            {
                $reportDate = Carbon::parse($ivfHistoryVisit->created_at)->format('Y-m-d H:i:s');
                $investigationHistoryReport = !empty($ivfHistoryVisit->investigation) ? json_decode($ivfHistoryVisit->investigation,true) : '';
                $investigationHistoryData = !empty($ivfHistoryVisit->description) ? json_decode($ivfHistoryVisit->description,true) : '';
                // $investigationTransferData = isset($investigationHistoryData['transfer']) && !empty($investigationHistoryData['transfer']) ? json_decode($investigationHistoryData['transfer'],true) : '';
                // dd($investigationHistoryReport['hystroscopy']['images']);
                $IVFReports[$reportDate]['hystroscopy'] = !empty($investigationHistoryReport['hystroscopy']['images']) ? $investigationHistoryReport['hystroscopy']['images'] : [];
                $IVFReports[$reportDate]['laproscopy'] = !empty($investigationHistoryReport['laproscopy']['images']) ? $investigationHistoryReport['laproscopy']['images'] : [];
                $IVFReports[$reportDate]['blood_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['blood_report']['image']) ? $investigationHistoryData['blood_report']['image'] : [];
                $IVFReports[$reportDate]['usg_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['usg']['images']) ? $investigationHistoryData['usg']['images'] : [];
                $IVFReports[$reportDate]['hsa_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['hsa_report']['images']) ? $investigationHistoryData['hsa_report']['images'] : [];
                if(isset($investigationHistoryData['transfer']['report']))
                {
                    $IVFReports[$reportDate]['blood_report'] = isset($investigationHistoryData['transfer']['report']) && !empty($investigationHistoryData['transfer']['report']) ? $investigationHistoryData['transfer']['report'] : [];
                }
            }
        }
        if($ivfAllExtraVisit)
        {
            foreach($ivfAllExtraVisit as $ivfExtraVisit)
            {
                $reportDate = Carbon::parse($ivfExtraVisit->created_at)->format('Y-m-d H:i:s');
                $investigationHistoryData = !empty($ivfExtraVisit->oe) ? json_decode($ivfExtraVisit->oe,true) : '';
                // dd($investigationHistoryData);
                // dd($investigationHistoryReport['hystroscopy']['images']);
                $IVFReports[$reportDate]['blood_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['blood_report']['image']) ? $investigationHistoryData['blood_report']['image'] : [];
            }
        }
        $iuiAllVisit = $this->IUI->where('patients_id', $patients)->get();
        $iuiAllHistoryVisit = $this->IuiHistory->where('patients_id',$patients)->get();
        $iuiAllExtraVisit = $this->IuiExtraVisit->where('patient_id',$patients)->get();
        if($iuiAllVisit)
        {
            foreach($iuiAllVisit as $iuiVisit)
            {
                $reportDate = Carbon::parse($iuiVisit->created_at)->format('Y-m-d H:i:s');
                $investigationReport = !empty($iuiVisit->investigation) ? json_decode($iuiVisit->investigation,true) : '';
                $IUIReports[$reportDate]['hystroscopy'] = !empty($investigationReport['hystroscopy']['images']) ? $investigationReport['hystroscopy']['images'] : [];
                $IUIReports[$reportDate]['laproscopy'] = !empty($investigationReport['laproscopy']['images']) ? $investigationReport['laproscopy']['images'] : [];
                $IUIReports[$reportDate]['hcg'] = !empty($investigationReport['hcg']['images']) ? $investigationReport['hcg']['images'] : [];
                $IUIReports[$reportDate]['blood_report'] = !empty($investigationReport['blood_report']['image']) ? $investigationReport['blood_report']['image'] : [];
                $IUIReports[$reportDate]['hsa_report'] = !empty($investigationReport['hsa_report']['images']) ? $investigationReport['hsa_report']['images'] : [];

            }
        }
        if($iuiAllHistoryVisit)
        {
            foreach($iuiAllHistoryVisit as $iuiHistoryVisit)
            {
                $reportDate = Carbon::parse($iuiHistoryVisit->created_at)->format('Y-m-d H:i:s');
                $investigationHistoryData = !empty($iuiHistoryVisit->description) ? json_decode($iuiHistoryVisit->description,true) : '';
                // dd($investigationHistoryData);
                // dd($investigationHistoryReport['hystroscopy']['images']);
                $IUIReports[$reportDate]['blood_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['blood_report']['image']) ? $investigationHistoryData['blood_report']['image'] : [];
                $IUIReports[$reportDate]['usg_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['usg']['images']) ? $investigationHistoryData['usg']['images'] : [];
                $IUIReports[$reportDate]['hsa_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['hsa_report']['images']) ? $investigationHistoryData['hsa_report']['images'] : [];
                
            }
        }
        if($iuiAllExtraVisit)
        {
            foreach($iuiAllExtraVisit as $iuiExtraVisit)
            {
                $reportDate = Carbon::parse($iuiExtraVisit->created_at)->format('Y-m-d H:i:s');
                $investigationHistoryData = !empty($iuiExtraVisit->oe) ? json_decode($iuiExtraVisit->oe,true) : '';
                // dd($investigationHistoryData);
                // dd($investigationHistoryReport['hystroscopy']['images']);
                $IUIReports[$reportDate]['blood_report'] = !empty($investigationHistoryData) && !empty($investigationHistoryData['blood_report']['image']) ? $investigationHistoryData['blood_report']['image'] : [];
            }
        }
        $gynecAllVisit = $this->Gynec->where('patients_id', $patients)->get();
        if($gynecAllVisit)
        {
            foreach($gynecAllVisit as $gynecVisit)
            {
                $reportDate = Carbon::parse($gynecVisit->created_at)->format('Y-m-d H:i:s');
                $investigationReport = !empty($gynecVisit->investigation) ? json_decode($gynecVisit->investigation,true) : '';
                $GynecReports[$reportDate]['report'] = !empty($investigationReport['report']['images']) ? $investigationReport['report']['images'] : [];
            }
        }
        // dd($IVFReports);
        krsort($ANCReports);
        krsort($IVFReports);
        krsort($IUIReports);
        krsort($GynecReports);
        return view('admin.appointment.patient.all-reports',compact('ANCReports','IVFReports','IUIReports','GynecReports','patientsDetails','referenceDoctor','status'));
    }

    /**
     * return all appointment review
     * @return  array
     * @param $patients(Patient's id)
     */
    public function getPatientHistory(Request $request,$patient_id)
    {
        $pId = decrypt($patient_id);
        $patients = $this->OpdPatients->find($pId);
        $doctor = $this->getDoctor();
        $referenceDoctor = $doctor['referenceDoctor'];
        // $appointments = $this->Appointment->where('patients_id',$pId)->where('is_done','1')->orderBy('date','desc')->get();
        $anc = $this->ANC->select('created_at',DB::raw("5 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $ancHistory = $this->AncHistory->select('created_at',DB::raw("6 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $iui = $this->IUI->select('created_at',DB::raw("3 as category_id"))->where('patients_id',$pId)->groupBy('patients_id')->pluck('category_id','created_at')->toArray();
        $iuiHistory = $this->IuiHistory->select('created_at',DB::raw("4 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $iuiReport = $this->IUIReport->select('created_at',DB::raw("4 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $iuiExtraVisit = $this->IuiExtraVisit->select('created_at',DB::raw("4 as category_id"))->where('patient_id',$pId)->pluck('category_id','created_at')->toArray();
        $ivf = $this->IVF->select('created_at',DB::raw("1 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $ivfHistory = $this->IvfHistory->select('created_at',DB::raw("2 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $ivfExtraVisit = $this->IvfExtraVisit->select('created_at',DB::raw("2 as category_id"))->where('patient_id',$pId)->pluck('category_id','created_at')->toArray();
        $gynec = $this->Gynec->select('created_at',DB::raw("18 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $stich = $this->Stich->select('created_at',DB::raw("22 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        // $ivfTransfer = $this->IvfTransferReport->select('created_at',DB::raw("2 as category_id"))->where('patient_id',$pId)->pluck('category_id','created_at')->toArray();
        // $ivfplanReport = $this->IvfPlanReport->select('created_at',DB::raw("2 as category_id"))->where('patients_id',$pId)->pluck('category_id','created_at')->toArray();
        $history = [];
        // $allVisit = [];
        $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
        $allVisit = array_merge($anc,$ancHistory,$iui,$iuiHistory,$iuiExtraVisit,$iuiReport,$ivf,$ivfHistory,$ivfExtraVisit,$gynec,$stich);
        krsort($allVisit);
        // $preview = 0;
        foreach($allVisit as $date => $category_id)
        {
            // $date = Carbon::parse($date)->format('Y-m-d H:i');
            //ivf
            if($category_id == 1 || $category_id == 2)
            {
                $ivf = $this->IvfHistory->where('patients_id',$pId)->where('created_at',$date)->first();
                if($ivf)
                {
                    $historyData = json_decode($ivf->description);
                    $collectionData = !empty($historyData->collection) ? $historyData->collection : [];
                    $cycle_no = encrypt($ivf->cycle_no);
                    $plan = encrypt($ivf->plan);
                    $created_at = Carbon::parse($ivf->created_at)->format('Y-m-d H:i');
                    $preview = $ivf->visit-2 >= 0  ? $ivf->visit - 2 : 1; // for display table only once time (if prebiew ==1 then table is displaying only once time in patient's history)
                    // for display table only once time (if prebiew ==1 then table is displaying only once time in patient's history)
                    //Transfer Report
                    if (in_array('transfer',$collectionData))
                    {
                        // $preview = 0;
                        $history[$created_at]['IVF'][$ivf->cycle_no.'/'.$planData[$ivf->plan]] = app('App\Http\Controllers\Admin\IVFController')->getIvfAppointmentWiseVisit($date,$patient_id,$cycle_no,$plan,$preview);
                        // $history[$created_at] = $preview;
                    }
                    else
                    {
                        $history[$created_at]['IVF'][$ivf->cycle_no.'/'.$planData[$ivf->plan]] = app('App\Http\Controllers\Admin\IVFController')->getIvfAppointmentWiseVisit($date,$patient_id,$cycle_no,$plan,$preview);
                        // $history[$created_at] = $preview;
                    }
                }
                $ivfExtra = $this->IvfExtraVisit->where('patient_id',$pId)->where('created_at',$date)->first();
                if($ivfExtra)
                {
                    $cycle_no = encrypt($ivfExtra->cycle_no);
                    $plan = encrypt($ivfExtra->plan);
                    $preview = 0;
                    $created_at = Carbon::parse($ivfExtra->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['IVF'][$ivfExtra->cycle_no.'/'.(isset($planData[$ivfExtra->plan]) ? $planData[$ivfExtra->plan] : '')] = app('App\Http\Controllers\Admin\IVFController')->getIvfAppointmentWiseVisit($date,$patient_id,$cycle_no,$plan,$preview);
                    // $history[$created_at] = $preview;
                }
                $ivf = $this->IVF->where('patients_id',$pId)->where('created_at',$date)->first();
                if($ivf && $category_id == 1)
                {
                    $preview = 0;
                    $created_at = Carbon::parse($ivf->created_at)->format('Y-m-d H:i');
                    $cycle_no = 0;
                    $plan = 0;
                    // $history[$created_at] = $preview;
                    $history[$created_at]['IVF'][''] = app('App\Http\Controllers\Admin\IVFController')->getIvfAppointmentWiseVisit($date,$patient_id,$cycle_no,$plan,$preview);
                }
            }
            // //iui
            if($category_id == 3 || $category_id == 4)
            {
                // $preview = [];
                $iui = $this->IuiHistory->where('patients_id',$pId)->where('created_at',$date)->first();
                if($iui)
                {
                    $cycle_no = encrypt($iui->cycle_no);
                    $preview = $iui->visit - 1;
                    $created_at = Carbon::parse($iui->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['IUI'][$iui->cycle_no] = app('App\Http\Controllers\Admin\IUIController')->getIuiAppointmentWiseVisit($date,$patient_id,$cycle_no,$preview,$category_id);
                    // $history[$created_at] = $preview;
                }
                $iuiExtra = $this->IuiExtraVisit->where('patient_id',$pId)->where('created_at',$date)->first();
                if($iuiExtra)
                {
                    $cycle_no = encrypt($iuiExtra->cycle_no);
                    $preview = 0;
                    $created_at = Carbon::parse($iuiExtra->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['IUI'][$iuiExtra->cycle_no] = app('App\Http\Controllers\Admin\IUIController')->getIuiAppointmentWiseVisit($date,$patient_id,$cycle_no,$preview,$category_id);
                    // $history[$created_at] = $preview;
                }
                $iui = $this->IUI->where('patients_id',$pId)->where('created_at',$date)->first();
                if($iui && $category_id == 3)
                {
                    $cycle_no = encrypt($iui->cycle_no);
                    $preview = 0;
                    $created_at = Carbon::parse($iui->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['IUI'][''] = app('App\Http\Controllers\Admin\IUIController')->getIuiAppointmentWiseVisit($date,$patient_id,$cycle_no,$preview,$category_id);
                    // $history[$created_at] = $preview;
                }
                $iuiReport = $this->IUIReport->where('patients_id',$pId)->where('created_at',$date)->first();
                if($iuiReport)
                {
                    $cycle_no = encrypt($iuiReport->cycle_no);
                    $preview = 0;
                    $created_at = Carbon::parse($iuiReport->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['IUI'][''] = app('App\Http\Controllers\Admin\IUIController')->getIuiAppointmentWiseVisit($date,$patient_id,$cycle_no,$preview,$category_id);
                }

            }
            //anc & usg
            if($category_id == 5 || $category_id == 6 || $category_id == 10 || $category_id == 13)
            {
                // $preview = [];
                $anc = $this->AncHistory->where('patients_id',$pId)->where('created_at',$date)->first();
                if($anc)
                {
                    $created_at = Carbon::parse($anc->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['ANC'][''] = app('App\Http\Controllers\Admin\ANCController')->getAncAppointmentWiseVisit($date,$patient_id,$anc->anc_id);
                    // $history[$created_at] = $created_at;
                }
                $anc = $this->ANC->where('patients_id',$pId)->where('created_at',$date)->first();
                if($anc && $category_id == 5)
                {
                    $created_at = Carbon::parse($anc->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['ANC'][''] = app('App\Http\Controllers\Admin\ANCController')->getAncAppointmentWiseVisit($date,$patient_id,$anc->id);
                    // $history[$created_at] = $created_at;
                }
            }
            if($category_id == 18)
            {
                // $preview = [];
                $gynec = $this->Gynec->where('patients_id',$pId)->where('created_at',$date)->first();
                if($gynec)
                {
                    $created_at = Carbon::parse($gynec->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['GYNEC'][''] = app('App\Http\Controllers\Admin\GynecController')->getGynecAppointmentWiseVisit($date,$patient_id);
                    // $history[$created_at] = $created_at;
                }
            }
            if($category_id == 22)
            {
                // $preview = [];
                $stich = $this->Stich->where('patients_id',$pId)->where('created_at',$date)->first();
                if($stich)
                {
                    $created_at = Carbon::parse($stich->created_at)->format('Y-m-d H:i');
                    $history[$created_at]['STICH'][''] = app('App\Http\Controllers\Admin\StichController')->getStichAppointmentWiseVisit($date,$patient_id);
                    // $history[$created_at] = $created_at;
                }
            }
        }
        // dd(krsort($history));
        return view('admin.appointment.patient.patient_history',compact('patients','history','referenceDoctor'));
    }
}
