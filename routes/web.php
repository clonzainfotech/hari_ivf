<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('dashboard');
});
//privicy Policy
Route::get('privicy_policy', function () {
    return view('privicy_policy');
});
//App Html Routs
Route::get('/about_candor','Admin\AppHtmlController@about_candor');
Route::get('/getting_pragnant','Admin\AppHtmlController@getting_pragnant');
Route::get('/pre_pregnancy','Admin\AppHtmlController@pre_pregnancy');
Route::get('/pregnancy_after','Admin\AppHtmlController@pregnancy_after');
Route::get('/prenatal_vitamins','Admin\AppHtmlController@prenatal_vitamins');
Route::get('/pregnancy_tests','Admin\AppHtmlController@pregnancy_tests');
Route::get('/early_pregnancy','Admin\AppHtmlController@early_pregnancy');
Route::get('/what_to_expect','Admin\AppHtmlController@what_to_expect');
Route::get('/weeks_1-4','Admin\AppHtmlController@weeks_1_4');
Route::get('/weeks_5-8','Admin\AppHtmlController@weeks_5_8');
Route::get('/weeks_9-12','Admin\AppHtmlController@weeks_9_12');
Route::get('/whta_to_expect_second','Admin\AppHtmlController@whta_to_expect_second');
Route::get('/weeks_36-40','Admin\AppHtmlController@weeks_36_40');
Route::get('/weeks_41','Admin\AppHtmlController@weeks_41');
Route::get('/first_trimester_tests','Admin\AppHtmlController@first_trimester_tests');
Route::get('/weeks_13-16','Admin\AppHtmlController@weeks_13_16');
Route::get('/weeks_26-30','Admin\AppHtmlController@weeks_26_30');
Route::get('/weeks_21-25','Admin\AppHtmlController@weeks_21_25');
Route::get('/weeks_31-35','Admin\AppHtmlController@weeks_31_35');
Route::get('/weeks_17-20','Admin\AppHtmlController@weeks_17_20');
Route::get('html-page/view/{slug}','Admin\HtmlPageController@view');
Auth::routes();

Route::get('get-iui-report','Admin\IUIController@getIuiDetails');
Route::get('get-ivf-report','Admin\IVFController@getIvfDetails');
Route::get('get-anc-report','Admin\ANCController@getAncDetails');
Route::get('get-gynec-details','Admin\GynecController@getGynecDetails');
Route::post('login','Admin\UserController@login')->name('login');
Route::post('register','Admin\UserController@register')->name('register');
Route::get('update-lmp/{type}','Base\Admin\AdminController@updateLmp');
Route::get('/anc/get-existed-medicine-data','Base\Admin\AdminController@getExistedMedicineData')->middleware('login');
Route::get('get-complaint-wise-medicine','Base\Admin\AdminController@getComplaintWiseMedicine')->middleware('login');

//patient notification
Route::get('patient_notification','Base\Admin\AdminController@patient_notification');
Route::get('remove_notification','Base\Admin\AdminController@remove_notification');
Route::get('get-category-notification','Base\Admin\AdminController@get_category_notification');

 // report status
Route::get('report/{type}/{patientsId}','Base\Admin\AdminController@patientReport')->middleware('login');

Route::get('ancdata/{patientsId}','Admin\GetANCDataController@getANCData');
Route::get('anchistorydata/{patientsId}/{anccreatedate}','Admin\GetANCDataController@getANCHistoryData');
Route::group(['namespace'=>'Admin','middleware'=>'login'],function(){
    Route::post('saverec','HomeController@saverec')->name('saverec');
    // dashboard
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::post('/search-patient-data','HomeController@searchPatientData')->name('search-patient-data');
    // logout
        Route::get('logout','UserController@logout');
    // users
    Route::get('user','UserController@index');
    Route::group(['middleware'=>'adminAccess'],function(){

        // user store
        Route::post('user','UserController@store');
        Route::get('user/create','UserController@create');
        // delete user route
        Route::get('user/delete/{id}','UserController@delete');
    });

    Route::get('user/{id}/edit','UserController@edit');
    Route::post('update-user','UserController@update');
    Route::post('/delete-achievement-image','UserController@deleteAchievementImage');

    // patient
    Route::post('get-patient-data','PatientsController@edit');
    Route::any('patient-report','PatientsController@getPatientReport');
    Route::get('patient-history/{id}','PatientsController@getPatientHistory');

    //Appointment
    Route::get('appointment/{usg?}','AppointmentController@index');
    Route::post('appointment','AppointmentController@store');
    Route::get('appointment-create/{id?}','AppointmentController@create');
    Route::get('appointment/{id}/edit','AppointmentController@edit');
    Route::put('appointment/{id}','AppointmentController@update');
    Route::get('appointment/delete/{id}','AppointmentController@delete');
    Route::post('appointment/update-arrival-time','AppointmentController@updateArrivalTime');
    Route::get('appointment-update-remark','AppointmentController@updateRemark');
    Route::get('update-appointment-time','AppointmentController@updateTime');
    Route::get('update-appointment-date-time','AppointmentController@updateAppointmentDateAndTime');
    Route::get('get-appointment-popup-Detail','AppointmentController@getAppointmentPopUpDetail');


    //Donor
    Route::get('donor','DonorController@index');
    Route::get('create-donor','DonorController@create');
    Route::post('store-donor','DonorController@store');
    Route::get('get-patient-details','DonorController@getPatientDetails');
    Route::get('edit-donor/{id}/edit','DonorController@edit');
    Route::post('update-donor','DonorController@update');
    Route::post('donor-delete','DonorController@destroy');


    Route::resource('hormon','HormonController');
    Route::get('/hormon','HormonController@index');
    Route::get('hormon/delete/{id}','HormonController@delete');
    Route::post('hormon/change-amount','HormonController@hormonChangeAmount');
    Route::get('hormon/add','HormonController@create');
    Route::post('hormon/add','HormonController@store');
    Route::get('hormon/receipt/{hormonId}','HormonController@getHormonReceipt');

    Route::get('get-hormon-data','HormonController@getHormonData');

    Route::get('appointment-sticker','AppointmentController@sticker');
    Route::get('appointment-printview','AppointmentController@printview');

    Route::resource('reference-doctor','ReferenceDoctorController');
    Route::get('reference-doctor/delete/{id}','ReferenceDoctorController@delete');
    Route::post('appointment-charges/store','AppointmentChargesController@store');
    Route::post('get-appointment-charges','AppointmentChargesController@getAppointmentChargesData');
    Route::get('send-opd/{appointmentId}','indoor@sendOpd');

    //appointment requestdonor
    Route::get('appointment-request','AppointmentRequestController@index');
    Route::post('appointment-request/{id}/approve','AppointmentRequestController@appointmentApprove');
    Route::post('appointment-request/{id}/reject','AppointmentRequestController@appointmentReject');
    Route::get('emergency-appointment/{id}','AppointmentRequestController@emergencyAppointment');

    //self Booking
    Route::get('self-booking','AppointmentRequestController@getSelfBookingList');


    // next appointment
    Route::post('next-appointment','AppointmentController@nextAppointment');
    Route::post('next-appointment-store','AppointmentController@nextAppointmentStore');
    Route::post('get-next-appointment-time','AppointmentController@getNextAappointmentTime');
    Route::get('anc/next-appointment/{patients_id}/{appointmentId?}','ANCController@nextPatientsAppointment');

    // anc iui and ivf
    Route::get('anc-iui-ivf','HomeController@ancIuiIvf');
    Route::get('get-patient-popup-Detail','HomeController@getPatientPopUpDetail');


    // gynec route
    Route::get('gynec/create/{patientsId}/{appointmentId?}','GynecController@create');
    Route::get('gynec/history/{patientsId}/{appointmentId?}','GynecController@gynecHistory');
    Route::post('gynec','GynecController@store');

    // anc route
    Route::resource('anc','ANCController');
    Route::get('anc/history/{patientsId}/{appointmentId?}','ANCController@ancHistory');
    Route::post('get-ref-doctor-mobile-number','ANCController@getRefDoctorMobileNumber');
    Route::post('removeRemark/{id}/{tid}','ANCController@updateStatus');
    Route::get('get-anc-details','ANCController@getAncDetails');
    Route::get('get-anc-chart/{patientsId}/{ancId}','ANCController@getAncChart');

    // IUI Route
    Route::get('iui/create/{patientsId}/{appointmentId?}','IUIController@create');
    Route::get('iui/extra-visit/{patientsId}/{cycleNo}','IUIController@extraVisit');
    Route::post('iui/store-extra-visit','IUIController@storeExtraVisit');
    Route::get('iui/history/{patientsId}/{appointmentId?}','IUIController@iuiHistory');
    Route::get('iui','IUIController@index');
    Route::post('iui','IUIController@store');
    Route::get('iui-result','IUIController@iuiResult');
    Route::get('iui-report/{patientId}/{cycle}','IUIController@iuiReport');
    Route::post('iui_report_update','IUIController@iuiReportStore');
    Route::post('iui/history/delete','IUIController@iuiHistoryDelete');
    // Route::get('iui-final-print/{patientId}/{cycle}','IUIController@iuiFinalPrint');
    Route::get('get-iui-report/{id}','IUIController@getIuiHistoryReport');
    Route::get('iui-update-followUp','IUIController@updateFollowUp');


    // get plan data
    Route::get('get-plandata/{type}','IUIController@getPlanData');
    Route::post('get-iui-bill-data','IUIController@getIuiBillData');

    Route::get('call-reminder','CallReminderController@index');
    Route::post('/add-call-reminder','CallReminderController@store');
    Route::post('/delete-call-reminder','CallReminderController@destroy');


    // IVF Route
    Route::get('ivf/create/{patientsId}/{appointmentId?}','IVFController@create');
    Route::get('ivf/ivfedit/{patientsId}','IVFController@edit');
    Route::get('ivf/history/{patientsId}/{appointmentId?}','IVFController@ivfHistory');
    Route::get('ivf','IVFController@index');
    Route::get('get-visit-data/{ivfHistoryId}','IVFController@getVisitData');
    Route::post('ivf','IVFController@store');
    Route::get('ivf/cycle/{historyId}/{patientsId}/{pStatus}/{cNumber}','IVFController@getIvfCycleData');
    Route::post('update-ivf-transfer-report-data','IVFController@updateTransferReport');
    Route::get('get-ivf-transfer-report-data','IVFController@getIvfTransferReportData');
    // Route::get('ivf-report/{planId}/{patientsId}','IVFController@ivfReport');
    Route::get('remove-last-visit-cycle/{visitId}','IVFController@removeLastCycleVisit');
    Route::get('get-fet-report','IVFController@getFetReport');
    Route::get('get-ivf-report/{id}','IVFController@getIvfHistoryReport');
    Route::get('get-ivf-followup-date', 'IVFController@getIvfFollowupDate');
    Route::get('ivf-result-review', 'IVFController@getIvfResultReview');
    Route::get('ivf-result-review/{id}', 'IVFController@getIvfResultReviewDetail');
    Route::post('store-ivf-result-review', 'IVFController@storeIvfResultReviewDetail');

    Route::get('ivf-payment/{patientsId}','IVFController@ivfPayment');
    Route::post('ivf-store-payment','IVFController@ivfPaymentStore');
    Route::get('get-ivf-payment-total','IVFController@getIvfPaymentTotal');

    Route::get('ivf-plan-report/{planId}/{patientsId}/{cycleNo}','IVFController@ivfPlanReport');
    Route::post('ivf-plan-report','IVFController@ivfPlanReportStore');
    Route::get('get-ivf-report-data','IVFController@getIvfReportData');
    Route::post('edit-ivf-report-data','IVFController@updateIvfReportData');

    Route::get('ivf/payments/{patientsId}/{id?}','IVFController@payment');
    Route::get('create/payments/{patientsId}/','IVFController@create_payment');
    Route::get('ivf/payments/{patientsId}/{lang}','IVFController@payment_gujarati');
    Route::post(' ivf-store-payment_newui','IVFController@ivfPaymentStoreNewUi');
    Route::get('ivf-remaining-payment','ReportController@ivfRemainigPayment');
    Route::get('ivf-payment-report/remaining_payment','ReportController@remaining_payment');
    Route::get('ivf/extra-visit/{patientsId}/{cycleNo}/{plan}','IVFController@extraVisit');
    Route::post('ivf/store-extra-visit','IVFController@storeExtraVisit');

    Route::resource('expense-manager','ExpenseManagerController');
    Route::post('expense-manager','ExpenseManagerController@store');
    Route::get('expense-manager/create','ExpenseManagerController@create');
    Route::get('expense-manager/{id}/edit','ExpenseManagerController@edit');
    Route::post('expense-manager/{id}','ExpenseManagerController@update');
    Route::get('expense-manager/delete/{id}','ExpenseManagerController@delete');
    Route::resource('expense-category','ExpencecategoryController');
    Route::post('expense-category/update','ExpencecategoryController@update');
    Route::any('categoryAdd','ExpencecategoryController@store');

    Route::resource('income-manager','IncomeManagerController');
    Route::post('income-manager','IncomeManagerController@store');
    Route::get('income-manager/create','IncomeManagerController@create');
    Route::get('income-manager/{id}/edit','IncomeManagerController@edit');
    Route::post('income-manager/{id}','IncomeManagerController@update');
    Route::get('income-manager/delete/{id}','IncomeManagerController@delete');
    Route::get('income-category','IncomeManagerController@incomecategory');

    // report
    Route::resource('report','ReportController');
    Route::any('category-report','ReportController@getCategoryReport');
    Route::any('cut-report','ReportController@getCutReport');
    Route::any('ref-doctor-report','ReportController@getRefDoctorReport');
    Route::any('collection-report','ReportController@getCollectionReport');
    Route::get('ivf-payment-report','ReportController@ivfPaymentReport');
    Route::get('get-ivf-payment-report/{paymentId}','ReportController@getIvfPaymentReport');
    Route::post('update-ivf-payment-report','ReportController@updateIvfPaymentReport');
    Route::any('ca-expense-report','ReportController@getCaExpenseReport');
    Route::post('ca-expense-report/store','ReportController@storeCaExpense');
    Route::get('ca-expense-report/getCaExpense','ReportController@getCaExpense');
    Route::get('all-collection-report','ReportController@getAllCollectionReport');
    Route::get('hormon-inj-report','ReportController@getHormonInjectionReport');
    Route::get('pediatric-report','ReportController@getPedCollection');
    Route::get('medicare-report','ReportController@getMedicareCollection');
    Route::get('analysis-report','ReportController@analysisReport');


    // infertility report data
    Route::get('infertility-report','ReportController@infertilityReport');
    Route::get('ref-pro-doctor-report','ReportController@referenceDoctorProReport');
    Route::get('remark-appointment-report','ReportController@remarkAppointment');


    Route::resource('holiday-manager','HolidayManagerController');
    Route::post('holiday-manager/store','HolidayManagerController@store');
    Route::put('holiday-manager/update','HolidayManagerController@update');
    Route::get('holiday-manager/delete/{id}','HolidayManagerController@delete');

    Route::resource('category','CategoryController');

    Route::get('category/delete/{id}','CategoryController@delete');

    //Indoor
    Route::get('indoor','IndoorController@index')->name('indoor-index');
    Route::post('indoor/{id}','IndoorController@store');
    Route::get('indoor/create/{id?}/{room_id}','IndoorController@create');

    Route::get('get-patient-data/{id}/edit','IndoorController@edit');
    Route::get('indoor/{id}/bookingedit','IndoorController@bookingEdit');
    Route::post('indoor/update/{id}','IndoorController@bookingUpdate');


    Route::post('indoor/discharge/{id}','IndoorController@dischargeStore');
    Route::get('indoor/discardcreate/{id}','IndoorController@dischargeCreate');
    Route::get('indoor/{id}/discardedit','IndoorController@dischargeEdit');
    Route::post('indoor/discardupdate/{id}','IndoorController@dischargeUpdate');
    Route::get('get-indoor-beds','IndoorController@getIndoorBeds');
    Route::get('get-indoor-rooms','IndoorController@getIndoorRooms');
    Route::get('get-message-detail', 'IndoorController@getMessageDetail');
    Route::post('send-sms', 'IndoorController@sendSms');


    Route::post('indoor/invoice/store','IndoorController@invoiceStore');
    Route::get('indoor/invoicecreate/{id}','IndoorController@invoiceCreate');
    Route::get('indoor/{id}/invoiceedit','IndoorController@invoiceEdit');
    Route::post('indoor/invoiceupdate/{id}','IndoorController@invoiceUpdate');
    Route::post('indoor/finalinvoice/{id}','IndoorController@finalInvoice');
    Route::post('indoor/invoice/update','IndoorController@invoiceUpdate');
    Route::post('indoor/invoice/updateprint/{id}','IndoorController@invoiceUpdatePrint');

    Route::post('indoor/storedirectdischarge/{id}','IndoorController@directDischarge');
    Route::post('get-bookingpatients','IndoorController@getBookingPatientData');
    Route::post('indoor/storedeposit/{id}','IndoorController@depositStore');
    Route::post('indoor/printdeposit/{id}','IndoorController@depositPrint');

    Route::get('indoor/create/indoor/{id}/getbednumber','IndoorController@GetBedNumber');
    Route::get('{id}/getbednumber','IndoorController@GetBedNumber');

    Route::get('indoorsetting','IndoorController@indoorsetting');
    Route::get('indoorcreate','IndoorController@indoorcreate');
    Route::post('indoorstore','IndoorController@indoorstore');
    Route::get('indoor/config/{id}/edit','IndoorController@roomedit');
    Route::post('update-indoor-settings','IndoorController@update');
    Route::get('indoor/config/{id}/status','IndoorController@updatestatus');
    Route::post('indoor-setting/delete','IndoorController@indoorDelete');

    Route::get('patient-detail','IndoorPatientController@index');
    Route::get('indoor/indoor_preview/{id}','IndoorController@indoor_preview');

    Route::post('upload-birthCertificate','IndoorController@uploadBirthCertificate');

    //route for Note
    Route::post('note','NoteController@store');
    Route::get('usernote/delete/{id}','NoteController@delete');
    Route::get('/get-note-data','NoteController@editNoteData');
    Route::post('update-note', 'NoteController@updateNote');
    Route::get('get-all-notes','NoteController@getAllNotes');

    Route::resource('event','EventController');
    Route::post('store','EventController@store');
    Route::get('event/{id}/edit','EventController@edit');
    Route::put('event/{id}','EventController@update');
    Route::get('event/delete/{id}','EventController@destroy');

    // medical route
    Route::get('medical','MedicalController@index');
    Route::get('get-medicine/{patientsId}','MedicalController@getMedicines');
    Route::get('medical-given-status','MedicalController@medicineStatus');

  // send sms

    Route::post('/send-custom-sms', 'SendMessageController@sendCustomSms');
    //route for sms templete
    Route::get('/get-sms-template', 'SmsTemplateController@getSmsTemplate');

    Route::get('/review','ReviewController@index');
    Route::post('review/delete/{id}','ReviewController@delete');

    Route::post('indoor/print-admintion-consent/{id}','IndoorPrintController@printAdmisionConsent');
    Route::post('/get-patient-report-data','IndoorPrintController@getPatientReportData');

    // system route
    Route::get('/systemsetting','SystemSettingController@index');
    Route::post('/system-create','SystemSettingController@create');
    Route::post('/system-update','SystemSettingController@update');
    Route::post('general-configuration','SystemSettingController@generalConfiguration');
    Route::post('button','SystemSettingController@button');
    Route::post('patientdetail','SystemSettingController@patientdetail');
    Route::post('appointmentdata','SystemSettingController@appointmentdata');
    Route::post('preloader', 'SystemSettingController@preloader');
    Route::resource('testimonials','TestimonialController');
    Route::get('testimonials/delete/{id}','TestimonialController@destroy');
    Route::get('sms-manager','SMSManagerController@index');
    Route::post('testimonials/{id}','TestimonialController@update');

    // medicines route
    Route::get('medicines-setting','SystemSettingController@medicinesSetting');
    Route::get('get-medicine-data','SystemSettingController@getMedicine');
    Route::get('medicines-mapping/{type}','SystemSettingController@medicinesMapping');
    Route::post('store-medicine','SystemSettingController@storeMedicine');
    Route::get('medicines-setting/create','SystemSettingController@medicinesCreate');
    Route::get('medicines-setting/edit/{id}','SystemSettingController@editeMedicine');
    Route::get('get-medicines-data','SystemSettingController@getMedicinesData');
    Route::post('medicines-setting','SystemSettingController@medicineStore');
    Route::post('store-medicines-data','SystemSettingController@storeMedicineStore');
    Route::get('remove-medicine','SystemSettingController@removeMedicine');
    Route::post('delete_selected_medicine', 'SystemSettingController@delete_selected_medicine');

    Route::post('/store-hospital-address','HospitalAddressController@add');
    Route::post('/update-hospital-address','HospitalAddressController@update');
    Route::post('/delete-hospital-address','HospitalAddressController@destroy');
    Route::post('/get-hospital-address','HospitalAddressController@getHospitalAddress');

    Route::get('patient','PatientsController@index');
    Route::get('editpatient/{id}','PatientsController@edit');
    Route::get('create-patient','PatientsController@create');
    Route::put('update-patient/{id?}','PatientsController@update');
    Route::any('patient-report','PatientsController@getPatientReport');
    Route::get('get-all-report/{id}','PatientsController@getAllReports');


    // get iui details
    Route::get('get-iui-details','IUIController@getIuiDetails');


    // get edd patient to edd date wise
    Route::get('edd-patient','ANCController@eddPatientList');

    // get IVF file view
    Route::get('get-ivf-details','IVFController@getIvfDetails');
    Route::get('get-injection-details','IVFController@getInjectionDetails');

    // stich category
    Route::get('stich','StichController@index');
    Route::get('stich/create/{id}/{appointmentId?}','StichController@create');
    Route::get('stich/history/{patientsId}/{appointmentId?}','StichController@getStichHistory');
    Route::post('stich','StichController@store');

    // ref doctor pro resource
    Route::resource('reference-doctor-pro','ReferenceDoctorProController');
    Route::get('reference-doctor-pro/delete/{id}','ReferenceDoctorProController@delete');

    // ca expenses
    Route::get('injection','InjectionController@index');
    Route::get('injection/create','InjectionController@create');
    Route::post('injection/store','InjectionController@store');
    Route::get('injection/delete/{id}','InjectionController@delete');
    Route::get('injection/edit/{id}','InjectionController@edit');

    //plan

    Route::get('plan','InjectionController@getPlanData');
    Route::post('plan/store','InjectionController@planStore');
    Route::get('plan/edit/{id}','InjectionController@planEdit');
    //bank
    Route::get('bank','InjectionController@getBankData');
    Route::post('bank/storeBank','InjectionController@storeBank');
    Route::get('bank/delete/{id}','InjectionController@bankDelete');
    Route::get('bank/getBank/{id}','InjectionController@getBank');

    //charges
    Route::get('charge','HospitalChargeController@index');
    Route::post('charge/store','HospitalChargeController@store');
    Route::get('charge/delete/{id}','HospitalChargeController@chargeDelete');
    Route::get('charge/getHospitalCharge/{id}','HospitalChargeController@getHospitalCharge');
    //notification

    Route::get('notification','CategoryNotificationController@index');
    Route::get('notification-all-read','CategoryNotificationController@notificationAllRead');

    //injection charges
    Route::get('inj-charge','InjectionChargeController@index');
    Route::post('inj-charge/store','InjectionChargeController@store');
    Route::get('inj-charge/edit/{id}','InjectionChargeController@getInjCharge');
    Route::get('inj-charge/delete/{id}','InjectionChargeController@injChargeDelete');
    Route::get('getInjectionQtyType','InjectionChargeController@InjectionChargeController');

    //html pages
    Route::get('html-page','HtmlPageController@index');
    Route::get('html-page/create','HtmlPageController@create');
    Route::post('html-page/store','HtmlPageController@store');
    Route::get('html-page/edit/{id}','HtmlPageController@edit');
    Route::get('html-page/delete/{id}','HtmlPageController@delete');

    Route::any('html-page/uploadImage','HtmlPageController@upload')->name('ckeditor.upload');

    //report advice list
    Route::get('advice-report-list','PatientsController@getAdviceReportList');

    Route::get('print-preview','SystemSettingController@printpreview');

});

