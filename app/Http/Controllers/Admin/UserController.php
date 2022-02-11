<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Validator;
use Exception;
use Session;
use View;
use Log;
use Auth;
use DB;

class UserController extends AdminController
{
    
    // user can login 
    public function login(Request $request){
        $rule = [
            'password' => 'required',
            'email' => 'required|email',
        ];

        $validator = Validator::make($request->all(),$rule);

        if($validator->fails()){
            return redirect()
            ->back()
            ->withInput()
            ->withErrors($validator->errors());
        }
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $patientsStatus = Auth::User()->status;
            if($patientsStatus=='1') {
                Session::flash('msg','Success');
                return redirect('/');
            }
            else
            {
                Auth::logout();
                Session::flash('msg','Your account is not active now so please contact to administration!');
                return back();
            }
        }
        else     
        {
            Session::flash('msg','These credentials do not match our records.');
            return back();
        }
            
    }
    // user can login 
    public function register(Request $request){
        $rule = [
            'f_name' => 'required',
            'l_name' => 'required',
            'surname' => 'required',
            'dob' => 'required',
            'mobile_number' => 'nullable|numeric|unique:patients|unique:patients_signup|digits:10',
            'other_mobile_number' => 'nullable|numeric|unique:patients|unique:patients_signup|digits:10',
            'dob' => 'required',
            'gender' => 'required',
            'residence' => 'required',
            'main_area' => 'required',
        ];

        $validator = Validator::make($request->all(),$rule);

        if($validator->fails()){
            // dd($validator->errors());
            return redirect()
            ->back()
            ->withInput()
            ->withErrors($validator->errors());
        }
        $patient = $this->PatientSignup;
        $patient->name = trim($request->f_name).' '.trim($request->l_name).' '.trim($request->surname);
        // dd($patient->name);
        $patient->dob = $request->dob;
        $patient->residence = $request->residence;
        $patient->mobile_number = $request->mobile_number;
        $patient->other_mobile_number = $request->other_mobile_number;
        $patient->gender = $request->gender;
        $patient->main_area = $request->main_area;
        $patient->city = $request->city;
        $patient->state = $request->state;
        $patient->reference_doctor = $request->reference_doctor;
        $patient->reference_patient = $request->reference_patient;
        $patient->other_reference = $request->other;
        $patient->reason = $request->reason;
        $patient->save();
        return view('errors.thankyou');
            
    }


    // user can logout using this funtion
    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    // get all user
    public function index(Request $request){

        if($request->ajax()){
            
            $user = $this->User
                        ->select("*",
                            \DB::raw('(CASE 
                                WHEN role = "1" THEN "Admin" 
                                WHEN role = "2" THEN "Reception" 
                                WHEN role = "3" THEN "Doctor" 
                                WHEN role = "4" THEN "Accountant"
                                WHEN role = "5" THEN "Medical"
                                WHEN role = "6" THEN "IVF"
                                WHEN role = "7" THEN "IUI"
                                WHEN role = "8" THEN "ANC"
                                WHEN role = "9" THEN "Telly Caller"
                                END) AS role'),
                            \DB::raw('(CASE 
                                WHEN status = "1" THEN "Active"
                                ELSE "Deactive"
                                END) AS status'));
            
            // search text
            $search = $request->search;
            if($search){
                $user = $user->where(function($query) use($search){
                    $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%')
                    ->orWhere('mobile_number','LIKE','%'.$search.'%');
                });
            }

            // status wise filter 
            $status = $request->status;
            if($request->status){
                $user = $user->whereStatus($status);
            }

            // role wise filter
            $role = $request->role;
            if($request->role){
                $user = $user->whereRole($role);
            }

            if(Auth::user()->role != 1){
                $user = $user->whereId(Auth::user()->id);
            }

            if($request->isprint == 1){
                $user = $this->User
                        ->select("*",
                            \DB::raw('(CASE WHEN role = "1" THEN "Admin" WHEN role = "2" THEN "Reception" WHEN role = "3" THEN "Doctor" WHEN role = "4" THEN "Accountant" WHEN role = "5" THEN "Medical" WHEN role = "6" THEN "IVF" WHEN role = "7" THEN "IUI" WHEN role = "8" THEN "ANC" WHEN role = "9" THEN "Telly Caller" END) AS role'),
                            \DB::raw('(CASE WHEN status = "1" THEN "Active" ELSE "Deactive" END) AS status'))->get();
                $data['status'] = 2;
                $data['data'] = View::make('admin.user.preview',compact('user'))->render();
                return $data; 
            }
            $user = $user->paginate(100);
            $data['status'] = 1;
            $data['data'] = View::make('admin.user.data',compact('user'))->render();
            return $data; 
        }

        return view('admin.user.index');
    }

    // open create user page
    public function create(){
        $role = $this->UserRole->pluck('role','id');
        return view('admin.user.create',compact('role'));
    }


    // user store using this function
    public function store(Request $request){
        
        try{
            $rule = [
                'name' => 'required',
                'password' => 'required',
                'email' => 'required|email|unique:users',
                'role' => 'required',
                'profile_picture' => 'nullable|image',
                'mobile_number' => 'nullable|numeric|unique:users|digits:10',
                'status' => 'required',
                'achievement.*' =>'nullable|image|max:1024',
                'dob_date' =>'nullable|before:' . date('Y-m-d')
            ];
            
            $message =[
                'achievement' => [
                    'mimes' => 'The achievement must be a type of jpg, jpeg and png',
                    'max'   => 'The achievement files should be less than 500 kb'
                ]
            ];
    
            $validator = Validator::make($request->all(),$rule, $message);
    
            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }
        
            // user save
            $user = $this->User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = $request->role;
            $user->dob_date = \Carbon\Carbon::parse($request->dob_date)->format('Y-m-d');
            $user->designation = $request->designation;
            $user->degree = $request->degree;
            $user->specialist = $request->specialist;
            $user->is_mobile_view = $request->is_mobile_view;
            $user->is_rmo_doctor = $request->is_rmo_doctor;

            $achievementImages = $request->achievement;
            // dd($achievementImages);
            $achievement = [];
            if($request->hasfile('achievement')) {
                foreach ($achievementImages as $row) {
                    $name = $this->uploadImage($row, 'public/upload/user/achievement');
                    $achievement[] = 'public/upload/user/achievement/' . $name;
                }
            }
            $user->achievement = json_encode($achievement, JSON_FORCE_OBJECT);
// dd($user->achievement);
            $user->description = $request->description;
            $user->mobile_number = $request->mobile_number;
            $user->status = $request->status;
            
            // profile picuture upload 
            $profilePicture = null;
            if ($request->hasFile('profile_picture')) {
                $image = $request->file('profile_picture');
                $profilePicture = $this->uploadImage($image, 'public/upload/user');
            }

            $user->profile_picture = $profilePicture;
            if(!empty($profilePicture)){
                $user->profile_picture = 'public/upload/user/' . $profilePicture;
            }
            // dd($user);
            $user->save();

            return redirect('user')->with('msg','Your event successfully added.');
            
        }catch(Exception $e){
            log::debug($e);
            abort(500);

        }
    }

    public function edit($id,Request $request){
        if($request->ajax()){
            $UserId = $id;
        }
        else {
            $UserId = decrypt($id);
        }

        $user = $this->User->where('id',$UserId)->first();

        if($request->ajax()){
            $data = [];
            $data['User'] = $User;
            return $data;
        }
        $role = $this->UserRole->pluck('role','id');
        return view('admin.user.edit',compact('user','role'));
    }
    // update 
    public function update(Request $request){

        try {
            $id = decrypt($request->user_id);
        } catch (Exception $exception) {
            return [
                'status' => 2,
                'message' => 'Something went wrong'
            ];
        }
        try{

            $rule = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'role' => 'required',
                'profile_picture.*' => 'nullable|image',
                'mobile_number' => 'required|numeric|digits:10|unique:users,mobile_number,'.$id,
                'status' => 'required',
                'achievement.*' =>'nullable|image|max:1024',
                'dob_date' =>'nullable|before:' . date('Y-m-d')
            ];
            
            $message = [
                'achievement' => [
                    'image' => 'The achievement must be an image',
                    'max'   => 'The achievement files should be less than 1 MB'
                ]
            ];
            $valid = Validator::make($request->all(),$rule, $message);
            
            if($valid->fails()){
                return [
                    'status' => 3,
                    'error' => $valid->errors()
                ];
            }

    
            $validator = Validator::make($request->all(),$rule);
    
            if($validator->fails()){
                return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
            }

            $user = $this->User->find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $role = $user->role;
            if ($user->role == 3 && ($role != $request->role)) {
                // dd("jere");
                $lastAchievements = json_decode($user->achievement, true);
                foreach ($lastAchievements as $row) {
                    $this->removeImage($row);
                }
                $user->achievement = null;
            }
            $user->role = $request->role;
            $user->dob_date = \Carbon\Carbon::parse($request->dob_date)->format('Y-m-d');
            $user->designation = $request->designation;
            $user->degree = $request->degree;
            $user->specialist = $request->specialist;
            $user->is_mobile_view = $request->is_mobile_view;
            $user->is_rmo_doctor = $request->is_rmo_doctor;

            $achievement = [];
            $lastAchievements = [];
            if($role == 3 && $request->hasfile('achievement')) {
                $achievementImages = $request->achievement;
                foreach ($achievementImages as $row) {
                    $name = $this->uploadImage($row, 'public/upload/user/achievement');
                    $achievement[] = 'public/upload/user/achievement/' . $name;
                }
                $lastAchievements = json_decode($this->User->whereId(decrypt($request->user_id))->value('achievement'), true);
                if ($lastAchievements == null) {
                    $lastAchievements = [];    
                }
                
                $user->achievement = json_encode(array_merge($lastAchievements, $achievement), JSON_FORCE_OBJECT);
            }
            $user->absence_dates = isset($request->absence_dates) ? $request->absence_dates : null;
            $user->description = $request->description;
            $user->mobile_number = $request->mobile_number;
            $user->status = $request->status;

            // profile picuture upload 
            if ($request->hasFile('profile_picture')) {
                $this->removeImage($user->profile_picture);
                $image = $request->file('profile_picture');
                $profilePicture = $this->uploadImage($image, 'public/upload/user');
                $user->profile_picture = 'public/upload/user/' . $profilePicture;
            }

            // change password
            if($request->password){
                $user->password = bcrypt($request->password);
            }
            $user->save();
            if ($role != $request->role) {
                $this->roleLogout($id);
            }
            return [
                'status' => 1,
                'message' => 'User successfullty updated'
            ];
            // return redirect('user')->with('msg','Your event successfully updated.');    
            
            
            
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    public function deleteAchievementImage(Request $request) {
        // dd($request->all());
        try {
            $achievementId = decrypt($request->achievement_id);
            $userId = decrypt($request->user_id);
        } catch (Exception $exception) {
            return [
                'status' => 2,
                'message' => 'Something went wrong'
            ]; 
        }
        // dd('jere');
        try {
            $userAchievementImage = $this->User->whereId($userId)->value('achievement');
            $achievement = json_decode($userAchievementImage, true);
            $this->removeImage($achievement[$achievementId]);
            unset($achievement[$achievementId]);
            $achievement = json_encode($achievement, JSON_FORCE_OBJECT);
            $this->User->whereId($userId)->update([
                'achievement' => $achievement
            ]);
            // dd($achievement);
            return [
                'status' => 1,
                'message' => 'Image deleted successfully',
                'achievement_id' => $achievementId
            ]; 
        } catch (Exception $exception) {
            log::debug($exception);
            return [
                'status' => 2,
                'message' => 'Internal server error'
            ]; 

        }
    }

    private function roleLogout($id)
    {
        $userId = Auth::user()->id;
        if($userId==$id)
        {
            Auth::logout();
            return redirect('/');
        }
    }

    public function delete($id){
        try{
            $user = $this->User->find($id);
            $this->removeImage($user->profile_picture);
            $achievements = json_decode($user->achievement, true);
            if($achievements){
                foreach ($achievements as $row) {
                    $this->removeImage($row);
                }
            }    
            $user->delete();
            return 'true';
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

}
