<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Exception;
use Validator;
use View;
use Auth;
use Log;

class ReferenceDoctorController extends AdminController
{
    
    /**
    * Return on reference doctor index
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        try{
            $referenceDoctors = collect($this->ReferenceDoctor->orderBy('name','ASC')->pluck('name', 'id'))
                ->map(function ($query) {
                    $query = ucwords(strtolower($query));
                    return $query;
                });
            if($request->ajax()){
                $referenceDoctor = $this->ReferenceDoctor->orderBy('name','asc');

                $referenceDoctorId = $request->reference_doctor_id;
                if($referenceDoctorId){
                    $referenceDoctor = $referenceDoctor->where(function($query) use($referenceDoctorId){
                        $query->where('id', $referenceDoctorId);
                    });
                }
                $search = $request->search;
                if($search){
                    $referenceDoctor = $referenceDoctor->where(function($query) use($search){
                        $query->where('mobile_number','LIKE','%'.$search.'%')
                        ->orWhere('name','LIKE','%'.$search.'%');
                    });
                }
                if($request->isprint == 1){
                    $referenceDoctor = $this->ReferenceDoctor->orderBy('name','asc')->get();
                    $data['status'] = 2;
                    $data['reference_data'] = View::make('admin.reference_doctor.preview',compact('referenceDoctor'))->render();
                    return $data; 
                }
                $referenceDoctor = $referenceDoctor->paginate(100);
            
                $data['status'] = 1;
                $data['reference_data'] = View::make('admin.reference_doctor.data',compact('referenceDoctor'))->render();
                return $data; 
            }
            return view('admin.reference_doctor.index', compact('referenceDoctors'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on reference doctor craete
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function create(){
        $state = $this->State->pluck('name','id');
        return view('admin.reference_doctor.create',compact('state'));
    }

    /**
    * Return on reference doctor edir
    * @param  \Illuminate\Http\Request $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id){
        try{
            $referenceDoctorId = decrypt($id);
            $referenceDoctor = $this->ReferenceDoctor->find($referenceDoctorId);
            $state = $this->State->pluck('name','id');
            return view('admin.reference_doctor.edit',compact('state','referenceDoctor'));
        }catch(Exception $e){
            return back();
        }
    }

    /**
    * Add Reference doctor
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        try{

            $referenceDoctorId = null;
            
            $referenceDoctor = $this->ReferenceDoctor;

            if($request->id){
                $referenceDoctorId = decrypt($request->id);
                $referenceDoctor = $this->ReferenceDoctor->find($referenceDoctorId);
            }

            $rule = [
                'name' => 'required',
                'reference_type' => 'required',
                'mobile_number' => 'required|digits:10|min:0|numeric|unique:reference_doctors,mobile_number,'.$referenceDoctorId
            ];

            $message = [
                'mobile_number.min' => 'Please enter valid mobile number.',
            ];
            
            $valid = Validator::make($request->all(),$rule,$message);
            if($valid->fails()){
                return redirect()->back()
                        ->withErrors($valid->errors())
                        ->withInput();
            }
            // dd($request);
            $referenceDoctor->name = $request->name;
            $referenceDoctor->mobile_number = $request->mobile_number;
            $referenceDoctor->address = $request->address;
            $referenceDoctor->reference_type = $request->reference_type;
            $referenceDoctor->is_lead = !empty($request->is_lead) ? $request->is_lead : 0;
            $referenceDoctor->created_by = Auth::user()->id;
            $referenceDoctor->save();

            return redirect('reference-doctor');
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Delete reference doctor
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function delete($id){
        try{
            $referenceDoctorId = decrypt($id);
            $referenceDoctor = $this->ReferenceDoctor->find($referenceDoctorId);
            $referenceDoctor->deleted_by = Auth::user()->id;
            $referenceDoctor->save();
            $referenceDoctor->delete();
            return 'true';
        }catch(Exceptoin $e){
            return 'false';
        }
    }
}
