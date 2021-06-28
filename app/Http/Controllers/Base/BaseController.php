<?php

namespace App\Http\Controllers\Base;

use File;
use App\User;
use App\Models\ANC;
use App\Models\IUI;
use App\Models\IVF;
use App\Models\City;
use App\Models\State;
use App\Models\Hormon;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Complaint;
use App\Models\IndoorBed;
use App\Models\AncHistory;
use App\Models\IndoorBook;
use App\Models\IndoorRoom;
use App\Models\IndoorType;
use App\Models\IuiHistory;
use App\Models\SmsManager;
use App\Models\SmsTemplate;
use App\Models\Appointment;
use App\Models\OpdPatients;
use App\Models\OvaryDetail;
use App\Models\IncomeManager;
use App\Models\IndoorDeposit;
use App\Models\IndoorInvoice;
use App\Models\ExpenseManager;
use App\Models\HolidayManager;
use App\Models\IndoorProcedure;
use App\Models\ReferenceDoctor;
use App\Models\PatientsCategory;
use App\Models\AppointmentCharges;
use App\Models\IndoorDischargeCard;
use App\Models\Note;
use App\Models\GivenTreatments;
use App\Models\Diagnosis;
use App\Models\Injection;
use App\Models\Event;
use App\Models\ReviewRole;
use App\Models\UserReview;
use App\Models\Dose;
use App\Models\IvfHistory;
use App\Models\CallReminder;
use App\Models\AppointmentRequest;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Donor;
use App\Models\SystemSetting;
use App\Models\HoCategory;
use App\Models\HospitalAddress;
use App\Models\IuiBill;
use App\Models\InjectionCharge;
use App\Models\IVFReport;
use App\Models\Testimonial;
use App\Models\IvfPayment;
use App\Http\Controllers\Controller;
use App\Models\IvfPlanReport;
use App\Models\HoDetails;
use App\Models\DurationData;
use App\Models\IvfTransferReport;
use App\Models\ExpenseCategory;
use App\Models\ComplaintMedicine;
use App\Models\AncHoHistory;
use App\Models\Gynec;
use App\Models\IuiExtraVisit;
use App\Models\Stich;
use App\Models\DischargeComplaint;
use App\Models\ReferenceDoctorPro;
use App\Models\SurgicalNote;
use App\Models\patientNotification;
use App\Models\CaExpense;
use App\Models\BankDetail;
use App\Models\PatientToken;
use App\Models\HospitalCharge;


class BaseController extends Controller
{
    public function __construct(){
        $this->User = new User;
        $this->Appointment = new Appointment;
        $this->Hormon = new Hormon;
        $this->OpdPatients = new OpdPatients;
        $this->Category = new Category;
        $this->ReferenceDoctor = new ReferenceDoctor;
        $this->State = new State;
        $this->AppointmentCharges = new AppointmentCharges;
        $this->ExpenseManager = new ExpenseManager;
        $this->IncomeManager = new IncomeManager;
        $this->HolidayManager = new HolidayManager;
        $this->ANC = new ANC;
        $this->City = new City;
        $this->Complaint = new Complaint;
        $this->AncHistory = new AncHistory;
        $this->PatientsCategory = new PatientsCategory;
        $this->Medicine = new Medicine;
        $this->IndoorType = new IndoorType;
        $this->IndoorRoom = new IndoorRoom;
        $this->IndoorBed = new IndoorBed;
        $this->IndoorProcedure = new IndoorProcedure;
        $this->IndoorBook = new IndoorBook;
        $this->IndoorDischargeCard = new IndoorDischargeCard;
        $this->SmsManager = new SmsManager;
        $this->SmsTemplate = new SmsTemplate;
        $this->IndoorInvoice = new IndoorInvoice;
        $this->IndoorDeposit = new IndoorDeposit;
        $this->IUI = new IUI;
        $this->IVF = new IVF;
        $this->Note = new Note;
        $this->OvaryDetail = new OvaryDetail;
        $this->IuiHistory = new IuiHistory;
        $this->GivenTreatments = new GivenTreatments;
        $this->Diagnosis = new Diagnosis;
        $this->Injection = new Injection;
        $this->Event = new Event;
        $this->Carbon = new Carbon;
        $this->ReviewRole = new ReviewRole;
        $this->UserReview = new UserReview;
        $this->CallReminder = new CallReminder;
        $this->Donor = new Donor;
        $this->AppointmentRequest = new AppointmentRequest;
        $this->Notification = new Notification;
        $this->Dose = new Dose;
        $this->IvfHistory = new IvfHistory;
        $this->SystemSetting = new SystemSetting;
        $this->HospitalAddress = new HospitalAddress;
        $this->IuiBill = new IuiBill;
        $this->InjectionCharge = new InjectionCharge;
        $this->IVFReport = new IVFReport;
        $this->Testimonial = new Testimonial;
        $this->IvfPayment = new IvfPayment;
        $this->IvfPlanReport = new IvfPlanReport;
        $this->DurationData = new DurationData;
        $this->HoDetails = new HoDetails;
        $this->IvfTransferReport = new IvfTransferReport;
        $this->ExpenseCategory = new ExpenseCategory;
        $this->ComplaintMedicine = new ComplaintMedicine;
        $this->Gynec = new Gynec;
        $this->AncHoHistory = new AncHoHistory;
        $this->HoCategory = new HoCategory;
        $this->IuiExtraVisit = new IuiExtraVisit;
        $this->Stich = new Stich;
        $this->DischargeComplaint = new DischargeComplaint;
        $this->ReferenceDoctorPro = new ReferenceDoctorPro;
        $this->SurgicalNote = new SurgicalNote;
        $this->patientNotification = new patientNotification;
        $this->CaExpense = new CaExpense;
        $this->BankDetail = new BankDetail;
        $this->PatientToken = new PatientToken;
        $this->HospitalCharge = new HospitalCharge;
        
    }

    // remove image form our server
    public function removeImage($image){
        if(File::exists($image)) {
            File::delete($image);
        }
    }

    // upload image function
    public function uploadImage($imageData, $path){
        $name = \Carbon\Carbon::now()->format('YmdHisu') . '.' . $imageData->getClientOriginalExtension();
        $destinationPath = $path;
        $imageData->move($destinationPath, $name);
        return $name;
    }

    // set notification msg
    public function notificationMsg(){
        $data['appointmentApprovalMsg'] = 'Your appointment has been approved';
        $data['appointmentRejectMsg'] = 'Your appointment has been rejected';
        return $data;
    }

    // store appointment notification in notification module
    public function storeAppointmentNotification($userId,$type){
        $notification = $this->Notification;
        $notification->user_type = 1;
        $notification->module = 1;
        $notification->user_id = $userId;
        $notificationMsg = $this->notificationMsg();
        $msg = $notificationMsg['appointmentRejectMsg'];
        if($type == 1){
            $msg = $notificationMsg['appointmentApprovalMsg'];
        }
        $notification->message = $msg;
        $notification->save();

    }

}
