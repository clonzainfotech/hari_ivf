<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware'=>'checkDB'],function(){
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->namespace('Api')->group(function () {

        Route::post('/login', 'AuthApiController@login');
        Route::post('/signup', 'AuthApiController@register');
        Route::post('/otp_verify', 'AuthApiController@otp_verify');
        Route::get('/hospital_tutorials', 'HomeController@hospital_tutorials');
        Route::get('/getQ_A', 'HomeController@getQAns');
        Route::get('getHtmlPages','HomeController@getHtmlPages');
        Route::post('getRegisterStatus','AuthApiController@registerStatus');
        Route::get('get-question/{id?}','FaqController@index');

        //Doctor API
        Route::post('/doctor-login', 'DoctorApi\LoginController@login');

        Route::middleware('APIToken')->group(function () {
            // Logout
            Route::get('/home', 'HomeController@home');

            Route::get('/appointments', 'AppointmentController@index');
            Route::post('/appointmentdetails', 'AppointmentController@appointmentDetail');
            Route::post('/add-appointment', 'AppointmentController@addAppointment');
            Route::post('/book-appointment', 'AppointmentController@bookAppointment');
            Route::post('/update-deviceToken', 'PatientController@updateDeviceToken');

            Route::get('getUsers',  'ReviewController@getUsers');
            Route::get('get-patients-review', 'ReviewController@getPatientsReview');
        //    Route::get('/reviewrole', 'ReviewController@getReviewRole');
            Route::post('/addreview', 'ReviewController@addReview');
            Route::get('getReview','ReviewController@getReview');
            Route::post('deleteReview','ReviewController@deleteReview');

            Route::get('/events','EventController@getEvents');
            Route::post('/get-all-events','EventController@getAllEvents');
            Route::post('/eventdetails','EventController@eventDetails');

            Route::get('/holidays','HolidayController@index');

            Route::get('/testimonials','TestimonialController@index');
            Route::post('/notification','NotificationController@index');


            Route::get('/getPatient','UserController@edit');
            // Route::post('/updateprofile','UserController@update');

            Route::post('/index','NotificationController@index');

            Route::post('/logout', 'AuthApiController@logout');

            // our staff
            Route::get('ourstaff','UserController@ourStaff');

            //our doctor
            Route::get('ourdoctor','UserController@ourDoctor');

            Route::get('about-us','UserController@aboutUs');
            Route::get('get_patient_report','PatientController@get_patient_report');

            // Route::get('notification','NotificationController@index' );

            //get patient details
            Route::get('getPaientDetails', 'PatientController@getPaientDetails');

            Route::post('getUserDetails', 'UserController@getUserDetails');

            Route::post('updateProfilePicture','PatientController@updateProfilePicture');
            Route::post('/add_profile', 'PatientController@add_profile');

            Route::get('all-appointment','AppointmentController@allAppointment');
            Route::post('get_medicines','MedicineController@get_medicines');

            //patient's memory

            Route::post('addPatientMemory','PatientController@addPatientMemory');
            Route::post('editPatientMemory','PatientController@editPatientMemory');
            Route::post('deletePatientMemory','PatientController@deletePatientMemory');
            Route::get('getPatientMemory','PatientController@getPatientMemory');

            //patient's weight list

            Route::post('addPatientWeight','PatientController@addPatientWeight');
            Route::post('editPatientWeight','PatientController@editPatientWeight');
            Route::post('deletePatientWeight','PatientController@deletePatientWeight');
            Route::get('getPatientWeight','PatientController@getPatientWeight');

            //patient's USG images
            Route::get('getPatientUsgImageList','PatientController@getPatientUsgImageList');

            //Appointment sloat
            Route::get('getappointmentDoctorList','AppointmentController@appointmentDoctorList');
            Route::post('getSloatBookCount','AppointmentController@getSloatBookCount');

            //Faq
            Route::post('add-question','FaqController@addQuestion');
            Route::post('add-answer','FaqController@addAnswer');
            Route::post('update-question','FaqController@updateQuestion');
            Route::post('update-answer','FaqController@updateAnswer');
            Route::post('delete-answer','FaqController@deleteAnswer');
            Route::post('delete-question','FaqController@deleteQuestion');

            //Doctor Notification
            Route::post('doctor-explore','DoctorApi\ExploreController@explore');
            Route::post('doctor-appointment','DoctorApi\AppointmentController@appointment');
            Route::post('doctor-notification','DoctorApi\NotificationController@notification');
            Route::post('doctor-profile','DoctorApi\ProfileController@doctorprofile');
            Route::post('doctor-patient','DoctorApi\MypatientController@doctorpatient');
            Route::post('doctor-updateprofile','DoctorApi\UpdateProfileController@doctorupdateprofile');
            Route::post('doctor-updatepassword','DoctorApi\UpdatePasswordController@doctorupdatepassword');
            Route::post('doctor-TodayPatients','DoctorApi\TodayPatientsController@doctortodaypatients');
            Route::post('doctor-PatientAppointmentRequest','DoctorApi\PatientAppointmentRequestController@PatientAppointmentRequest');

            //Patient's report
            Route::post('add-patient-report','PatientController@addPatientsReport');
            // Route::get('get-patient-report','PatientController@getPatientsReport');
            Route::delete('delete-patient-report/{id}','PatientController@deletePatientsReport');


        });

    });
});
