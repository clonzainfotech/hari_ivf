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

class HormonController extends AdminController
{
    // listing of all hormon, IUI and IVF deposit and Injection with search funcionality
    public function index(Request $request){
        try{
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $patients = $this->getPatients();
            $referenceDoctor = ['other' => 'Other'] + $this->ReferenceDoctor->pluck('name','id')->toArray();
            if($request->ajax()){
                $hormon = $this->IndoorDeposit
                                ->where('charge_type', '!=', 4)
                                ->orderBy('id','DESC');
                // search text
                $chargeType = $request->charge_type;
                $chargeValue = ($request->charge_value == 'Select Charge Category' || $request->charge_value == null) ? 'Hormon,IVF,IUI' : $request->charge_value;
                if($chargeType){
                    $hormon = $hormon->where('charge_type', $chargeType);
                }

                
                if($request->date){
                    $date = explode('-', $request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d') . ' 00:00:00';
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d') . ' 23:59:59';
                    $hormon = $hormon->where(function($query) use($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    });
                }
                $patientId = $request->patient_id;
                if($patientId){
                    $hormon = $hormon->where(function($query) use($patientId){
                        $query->orWhereHas('getPatients', function($query) use($patientId) {
                            $query->where('id', $patientId);
                        });
                    });
                }
                $search = $request->search;
                if($search){
                    $hormon = $hormon->where(function($query) use($search) {
                        $query
                        ->orWhereHas('getPatients', function($query) use($search) {
                            $query->where('mobile_number','LIKE',$search.'%');
                        });
                    });
                }
                if($request->is_print == 1){
                    $hormon = $this->IndoorDeposit->where('charge_type', '!=', 4)->orderBy('id','DESC')->get();
                    $data['data'] = View::make('admin.appointment.hormon.preview',compact('hormon','chargeValue','patients'))->render();
                    $data['status'] = 2;
                    return response()->json($data);
                }
                $hormon = $hormon->paginate(100);
                $data['data'] = View::make('admin.appointment.hormon.data',compact('hormon','chargeValue','patients'))->render();
                $data['status'] = 1;
                return response()->json($data);
            }
            return view('admin.appointment.hormon.index',compact('category','referenceDoctor', 'patients'));
        }catch(Exception $e){
            abort(500);
        }
    }

    // open the page of create hormon 
    public function create(){
        try{
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $patient = $this->OpdPatients->where(function($query) {
                $query->whereHas('getAppointments', function($query) {
                    $query->where([
                        ['category_id', '!=', 7],
                    ]);
                });
            })
            ->pluck('name','id');
            $referenceDoctor = ['other' => 'Other'] + $this->ReferenceDoctor->pluck('name','id')->toArray();
            return view('admin.appointment.hormon.create',compact('category','referenceDoctor', 'patient'));
        }catch(Exception $e){
            abort(500);
        }
    }

    // hormon, Indoor deposit and IVF payment data store
    public function store(Request $request){
        // $rule = [
        //     'hname' => 'required',
        //     'htype' => 'required',
        //     'hcharge' => 'required|numeric|digits_between:1,6',
        // ];
        // $messages = [
        //     'hname.required' => 'The name field is required.',
        //     'hname.min' => 'The name must be at least 2 characters.',
        //     'hname.max' => 'The name may not be greater than 255 characters.',
        //     'hname.regex' => 'The name format is invalid.',
        //     'hcharge.required' => 'The charge field is required.',
        //     'hcharge.numeric' => 'The charge field must be numeric.',
        //     'hcharge.digits_between' => 'The charge field must be of between 1 to 6 digits.',
        // ];
        // if ($request->input('htype') == 1) {
        //     $rule['hinjection'] = 'required';
        //     $messages['hinjection.required'] = 'The injection field is required.';
        // }
        // if ($request->input('hreference_doctor_id') == 'other') {
        //     $rule['doctor_name'] = 'required|regex:/(^[A-Za-z .]+$)+/|min:2|max:255';
        //     $rule['doctor_mobile_number'] = 'required|numeric|digits:10|unique:reference_doctors,mobile_number';                
        // }
        
        // $validator = Validator::make($request->all(),$rule, $messages);
    
        // if($validator->fails()){
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->withErrors($validator->errors());
        // }

        try {
            // $hormon = $this->IndoorDeposit->where('patient_id',$request->hname)->where('charge_type',$request->htype)->first();
            // if($hormon == null){
            $hormon = $this->IndoorDeposit;        
            // }
            if ($request->input('hreference_doctor_id') == 'other') {
                $referenceDoctor = $this->ReferenceDoctor->where('mobile_number',$request->doctor_mobile_number)->first();
                if($referenceDoctor == null){
                    $referenceDoctor = $this->ReferenceDoctor;
                    $referenceDoctor->name = $request->doctor_name;
                    $referenceDoctor->mobile_number = $request->doctor_mobile_number;
                    $referenceDoctor->save();    
                }else{
                    return['status'=>2];
                }
            }
            // $hormon = $this->Hormon;
            $hormon->patient_id = $request->hname;
            $hormon->admin_id = Auth::user()->id;
            $hormon->amount = $request->hcharge;
            $hormon->remark = $request->remark;

            $lastTotal = $this->IndoorDeposit
                ->whereChargeTypeAndPatientId($request->htype, $request->hname)
                ->orderBy('id', 'DESC')
                ->value('total');

            $hormon->total = ($lastTotal == null) ? $hormon->amount : ($lastTotal + $hormon->amount);
            $hormon->case_type = 'Credit';
            $hormon->payment_type = $request->payment_type;
            $hormon->charge_type = $request->htype;
            $opdPatient = $this->OpdPatients->find($request->hname);
            $hormon->injection = null;
            // $hormon->reference_doctor_id = ($request->input('hreference_doctor_id') == 'other') ? $referenceDoctor->id : $request->hreference_doctor_id;
            $hormon->reference_doctor_id = $opdPatient->reference_doctor_id;
            if($hormon->charge_type == 1) {
                $hormon->injection = $request->hinjection;
            }
            // if ($hormon->charge_type == 3) {
                
            // }

            $doctor = [];
            if($request->input('hreference_doctor_id') == 'other'){
                $doctor[] = $request->doctor_name;
            }else{
                $doctor=$this->ReferenceDoctor->where('id',$request->hreference_doctor_id)->pluck('name');
            }
            $reference=$request->hreference_doctor_id;
            $hormon->created_at = Carbon::parse($request->date)->format('Y-m-d'.' '.date('H:i:s'));
            $ivfPaymentData = $this->IvfPayment->wherePatientsId($hormon->patient_id)->where('visit',1)->where('is_completed',0)->orderBy('id','DESC')->first();
            if ( $request->htype == 2) {
                $hormon->package = (!empty($ivfPaymentData)) ? $ivfPaymentData->package : null;  
                $hormon->cycle_no = (!empty($ivfPaymentData)) ? $ivfPaymentData->cycle_no : null;  
            }
            $hormon->save();
            if($ivfPaymentData && $request->htype == 2){
                $checkTotalAmount = $this->IvfPayment->wherePatientsId($hormon->patient_id)->whereCycleNo($ivfPaymentData->cycle_no)->sum('payment');

                // print_r($checkTotalAmount);die();
                $totalAmount = $checkTotalAmount + $request->hcharge;
                $isCompleted = 0;
                if($ivfPaymentData->package <= $totalAmount){
                    $isCompleted = 1;
                }
                // $ivfPaymentNewData = $this->IvfPayment;
                // $ivfPaymentNewData->patients_id = $hormon->patient_id;
                // $patient = $this->OpdPatients->find($hormon->patient_id);
                // $ivfPaymentNewData->patient_name = $patient->name;
                // $ivfPaymentNewData->husband_name = $request->h_name;
                // $ivfPaymentNewData->sonography_charge = $request->sonography_charge;
                // $ivfPaymentNewData->ivf_lab_charge = $request->ivf_lab_charge;
                // $ivfPaymentNewData->embroy_tranfer = $request->embryo_transfer_charge;
                // $ivfPaymentNewData->embroy_freezing = $request->embryo_freezing;
                // $ivfPaymentNewData->hystrocopy = $request->hystrocopy;
                // $ivfPaymentNewData->icsi_ivf_charge = $request->icsi_ivf_charge;
                // $ivfPaymentNewData->medical_medicines = $request->medical_medicines;
                // // $ivfPaymentNewData->unconscious_charge = $request->unconscious_charge;
                // $ivfPaymentNewData->anesthescis_doctor = $request->anesthescis_doctor;
                // $ivfPaymentNewData->blood_report = $request->blood_report;
                // $ivfPaymentNewData->tesa_pesa = $request->tesa_pesa;
                // $ivfPaymentNewData->cycle_no = $ivfPaymentData->cycle_no;
                // $ivfPaymentNewData->payment = $request->hcharge;
                // $ivfPaymentNewData->package = $ivfPaymentData->package;
                // $ivfPaymentNewData->payment_type = $request->payment_type;
                // $ivfPaymentNewData->time = $ivfPaymentData->time;
                // $ivfPaymentNewData->created_by = Auth::user()->id;
                // $ivfPaymentNewData->visit = 2;
                // $ivfPaymentNewData->remark = $request->remark;
                // $ivfPaymentNewData->condition = $ivfPaymentData->condition;
                // $ivfPaymentNewData->donor_charge = $request->donor_charge;
                // $ivfPaymentNewData->is_completed = $isCompleted;
                // $ivfPaymentNewData->created_at = Carbon::parse($request->date)->format('Y-m-d'.' '.date('H:i:s'));
                // $ivfPaymentNewData->remaining_day = $request->remaining_day;

                // $ivfPaymentNewData->total_payment = ($ivfPaymentData->package) - (($lastTotal == null) ? $hormon->amount : ($lastTotal + $hormon->amount));
                // if ($request->remaining_day == '') {
                //    $ivfPaymentNewData->remaining_date = null;
                // }else{
                //     $today = Carbon::now();
                //     $next_date = Carbon::parse($today.$request->remaining_day."days")->format('Y-m-d');;
                //     $ivfPaymentNewData->remaining_date = $next_date;
                // }
                // $ivfPaymentNewData->save();
            }
            $patientname=$this->OpdPatients->where('id',$hormon->patient_id)->first();
            // print_r($patientname);die();
            if($request->isprint){
                if($request->htype == 2 && !empty($ivfPaymentData)){
                    $status = 1;
                    $ivfPayment = $ivfPaymentData;
                    $data = View::make('admin.ivf.payment_preview', compact('ivfPayment'))->render();
                }else{
                    $status = 1;
                    $data = View::make('admin.appointment.hormon.hormon_preview', compact('hormon','patientname','doctor'))->render();
                }
                return response()->json([
                    'status' => $status,
                    'data' => $data
                ]);
            }
            Session::flash('msg','Your charges added.');
            return['status'=>'true'];
        }catch(Exception $e){
            log::debug($e);
            abort(500);
            return['status'=>'false'];
        }
    }

    /**
    * Return On hormon index blade
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function edit($id, Request $request) {
        try{
            $hormonId = decrypt($id);
            
            $hormonData = $this->Hormon->where('id', $hormonId)->first();
            
            $category = $this->Category->whereStatus(1)->pluck('name','id');
            $referenceDoctor = ['other' => 'Other'] + $this->ReferenceDoctor->pluck('name','id')->toArray();

            return view('admin.appointment.hormon.edit',compact('hormonData', 'category','referenceDoctor'));
        }catch(Exception $e){
            abort(500);
            return['status'=>'false'];
        }

    }
    
    /**
    * Update hormon
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request){
        $rule = [
            'hname' => 'required|regex:/(^[A-Za-z ]+$)+/|min:2|max:255',
            'htype' => 'required',
            'hcharge' => 'required|numeric|digits_between:1,6',
        ];
        $messages = [
            'hname.required' => 'The name field is required.',
            'hname.min' => 'The name must be at least 2 characters.',
            'hname.max' => 'The name may not be greater than 255 characters.',
            'hname.regex' => 'The name format is invalid.',
            'hcharge.required' => 'The charge field is required.',
            'hcharge.numeric' => 'The charge field must be numeric.',
            'hcharge.digits_between' => 'The charge field must be of between 1 to 6 digits.',
        ];
        if ($request->input('htype') == 1) {
            $rule['hinjection'] = 'required';
            $rule['hcategory'] = 'required|numeric';
            $messages['hinjection.required'] = 'The injection field is required.';
            $messages['hcategory.required'] = 'The category field is required.';
            $messages['hcategory.numeric'] = 'The category field must be numeric.';
        }

        if ($request->input('hreference_doctor_id') == 'other') {
            $rule['doctor_name'] = 'required|regex:/(^[A-Za-z .]+$)+/|min:2|max:255';
            $rule['doctor_mobile_number'] = 'required|numeric|digits:10|unique:reference_doctors,mobile_number';                
        }

        $validator = Validator::make($request->all(),$rule, $messages);
    
        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        try {
            $hormonData = null;
            if($request->hormon_id) {
                $hormonId = decrypt($request->hormon_id);
                $hormonData =  $this->Hormon->where('id',$hormonId)->first();

                $hormon = $hormonData;
            }
            if($request->input('hreference_doctor_id') == 'other' && $hormon->charge_type == 3) {
                $referenceDoctor = $this->ReferenceDoctor;
                $referenceDoctor->name = $request->doctor_name;
                $referenceDoctor->mobile_number = $request->doctor_mobile_number;
                $referenceDoctor->save();
            }

            $hormon->name = $request->hname;
            $hormon->charge_type = $request->htype;
            $hormon->injection = null;
            $hormon->remark = $request->remark;
            $hormon->category_id = null;
            $hormon->reference_doctor_id = null;
            if($hormon->charge_type == 1) {
                $hormon->injection = $request->hinjection;
                $hormon->category_id = $request->hcategory;
            }
            $hormon->charge = $request->hcharge;
            
            if($hormon->charge_type == 3) {
                $hormon->reference_doctor_id = $request->hreference_doctor_id;
                if($request->input('hreference_doctor_id') == 'other') {
                    $hormon->reference_doctor_id = $referenceDoctor->id;
                }
            }
            $hormon->created_by = Auth::user()->id;
            if($hormon->save()) {
                return redirect('hormon')->with('msg','Your record successfully updated.');
            }
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Get hormon
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getHormonData(Request $request) {
        $hname = $_GET['hname'];
        $hormonData['patient'] = $this->OpdPatients->with('getReferenceDoctorPro')->find($hname);
        $hormonData['hormon'] = $this->IndoorDeposit
            ->where([
                ['patient_id', '=', $_GET['hname']],
                ['charge_type', '!=', 4]
            ])
            ->orderBy('id', 'DESC')
            ->first();

        $hormonData['injection_data'] = $this->IndoorDeposit
            ->where([
                ['patient_id', '=', $_GET['hname']],
                ['injection', '!=', null],
                ['charge_type', '!=', 4],
            ])
            ->orderBy('id', 'DESC')
            ->first();
                
        $hormonData['reference_doctor_data'] = $this->IndoorDeposit
            ->where([
                ['patient_id', '=', $_GET['hname']],
                ['reference_doctor_id', '!=', null],
                ['charge_type', '!=', 4],
            ])
            ->orderBy('id', 'DESC')
            ->first();
        return json_encode($hormonData);
    }
    /**
    * delete hormon
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function delete($id){
        try{
            $hormon = $this->Hormon->find($id);
            $hormon->delete();
            return 'true';
        }catch(Exception $e){
            abort(500);
            return 'false';
        }
    }

    /**
    * Change Hormon amount
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function hormonChangeAmount(Request $request,$hormonId){
        try{
            $hormonId = decrypt($hormonId);
            $hormon = $this->IndoorDeposit->whereId($hormonId)->first();
            $patientsId = $hormon->patient_id;
            $chargeType = $hormon->charge_type;
            $total = $request->total;
            $checkPoint = strpos($total, '.');
            if(!is_numeric($total) || $total < 0 || strlen($total) > 8 || $checkPoint){
                return ['status'=>2];
            }
            $hormonData = collect($this->IndoorDeposit->wherePatientId($patientsId)->whereChargeType($chargeType)->orderBy('id', 'asc')->get()->toArray());
            $hormonCount = $hormonData->count();
            $amount = 0;
            $type = 'Credit';
            unset($hormonData[$hormonCount - 1]);
            $lastHormonData = $hormonData->last();
            if(!empty($lastHormonData)){
                $amount = abs($total - $lastHormonData['total']);
                if($total > $lastHormonData['total']){
                    $type = 'Credit';
                }else{
                    $type = 'Debit';
                }
            }else{
                $amount = $total;
                $type = $hormon->case_type;
                if($total == 0){
                    $hormon->delete();        
                    return ['status'=>1];
                }
            }
            if($amount == 0){
                $hormon->delete();
            }else{
                $hormon->amount = $amount;
                if($request->charge_type){
                    $hormon->charge_type = $request->charge_type;
                }
                $hormon->total = $total;
                $hormon->case_type = $type;
                $hormon->save();
            }
            return ['status'=>1];
        }catch(Exception $e){
            abort(500);
            return ['status'=>2];
        }
    }
}
