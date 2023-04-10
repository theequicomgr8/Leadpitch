<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
 
 if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
// Ignores notices and reports all other kinds... and warnings
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}
Route::get('/',function(){
	if(Auth::check()){
		return redirect(url('/lead/all-leads'));
	}else{
		
		return redirect(url('/login'));
	}
});

// *****
// LOGIN clinet


 
	Route::auth();	 
	Route::get('/logout', 'Auth\AuthController@logout');
	Route::get('/genie', 'Genie\AdminController@index'); 
	Route::post('/genie', 'Genie\AdminController@authenticate'); 
	Route::get('/genie/otp','Genie\AdminController@getOTP');
	Route::post('/genie/otp','Genie\AdminController@authenticate');
	Route::get('/genie/clientDashboard','Genie\AdminController@clientDashboard');
	Route::get('getnotification', 'CounsellorDashboardController@getnotification'); 
	Route::get('getnotificationdemo', 'CounsellorDashboardController@getnotificationdemo');
	Route::get('/genie/clientAdd','Genie\AdminController@getRegister'); 
	Route::post('/genie/client','Genie\AdminController@postRegister'); 
	Route::get('/genie/clientList','Genie\AdminController@getClient');  
	Route::get('genie/reset', 'Genie\ClientController@resetpassword');
	Route::post('genie/reset/password', 'Genie\ClientController@passwordReset');
		
		/* Start  Profile routing */
	Route::get('/genie/profile','Genie\ClientController@index'); 
	Route::post('/genie/profile','Genie\ClientController@edit');
	Route::get('/genie/profile/del_icon/{id}','Genie\ClientController@del_icon');
	Route::get('/genie/profile/view/{id}','Genie\ClientController@view');
	Route::get('/genie/profile/delete/{id}','Genie\ClientController@destroy');
	Route::get('/genie/profile/status/{id}/{val}','Genie\ClientController@status');
	Route::get('/genie/changepassword/','Genie\ClientController@getPassword');
	Route::post('/genie/changepassword/','Genie\ClientController@changePassword');
	Route::get('/genie/logout/','Genie\ClientController@logout');
	
	 Route::get('/genie/update/{id}', 'Genie\ClientController@updateClient');
	Route::post('/genie/update/{id}', 'Genie\ClientController@updateThisClient');
  
	Route::post('/genie/otpEmail','Genie\ClientController@otpEmail');
	Route::get('/genie/delete/{id}','Genie\ClientController@clientDelte');
	
		Route::post('/client/downloadexcelformate','HomeController@downloadExcelFormate'); 
	Route::post('/client/excelformatedemo','HomeController@excelFormateDemo'); 
	
	// *******
	// email
	Route::get('genie/email','Genie\EmailController@index'); 
	Route::post('genie/email','Genie\EmailController@store'); 
	Route::get('genie/email/edit/{id}','Genie\EmailController@edit'); 
	Route::post('genie/email/edit/{id}','Genie\EmailController@update'); 
	Route::get('genie/email/delete/{id}','Genie\EmailController@destroy');	
	
	 
	// Mobile
	Route::get('genie/mobile','Genie\MobileController@index'); 
	Route::post('genie/mobile','Genie\MobileController@store'); 
	Route::get('genie/mobile/edit/{id}','Genie\MobileController@edit'); 
	Route::post('genie/mobile/edit/{id}','Genie\MobileController@update'); 
	Route::get('genie/mobile/delete/{id}','Genie\MobileController@destroy'); 
	
	
	// Mobile
 
	
	/* genie role permission */
	Route::get('/genie/permission','Genie\ClientRolesPermissionsController@permissionIndex');
	Route::post('/genie/permission','Genie\ClientRolesPermissionsController@permissionStore');
	Route::get('/genie/permission/getpermission','Genie\ClientRolesPermissionsController@getPaginatedPermissions');
	Route::get('/genie/permission/update/{id}','Genie\ClientRolesPermissionsController@editPermission');
	Route::post('/genie/permission/update/{id}','Genie\ClientRolesPermissionsController@updatePermission');
	Route::get('/genie/permission/delete/{id}','Genie\ClientRolesPermissionsController@destroyPermission');
	
	 Route::get('genie/role-permission','Genie\ClientRolesPermissionsController@rolePermissionIndex');
	Route::post('genie/role-permission','Genie\ClientRolesPermissionsController@rolePermissionStore');
	Route::get('genie/role-permission/getpermission','Genie\ClientRolesPermissionsController@getPaginatedRolesPermissions');
	Route::get('genie/role-permission/update/{id}','Genie\ClientRolesPermissionsController@editRolePermission');
	Route::post('genie/role-permission/update/{id}','Genie\ClientRolesPermissionsController@updateRolePermission');
	Route::get('genie/role-permission/delete/{id}','Genie\ClientRolesPermissionsController@destroyRolePermission');
	Route::get('genie/role-permission/{id}','Genie\ClientRolesPermissionsController@getRolePermissions');
	/* End admin Profile routing */
	
	
	
	
	
	Route::get('/login','Auth\AuthController@index');
	Route::post('/login','Auth\AuthController@login');
	Route::get('/check/login','Auth\AuthController@checklogin');
	Route::post('/check/login','Auth\AuthController@authenticate');
	//Route::get('/login/otp',function(){return view('auth.otp');});
	Route::get('/login/otp','Auth\AuthController@getOTP');
	Route::post('/login/otp','Auth\AuthController@authenticate');
	Route::get('/register','Auth\AuthController@getRegister');//->middleware('auth');
	Route::post('/register','Auth\AuthController@postRegister');//->middleware('auth');
	//Route::get('/users', 'Auth\AuthController@getUsers')->middleware('auth');
	Route::get('/users', function(){ return view('auth.list-users'); })->middleware('auth');
	Route::get('/users/pagination', 'Auth\AuthController@getUsers')->middleware('auth');
	Route::get('/user/update/{id}', 'Auth\AuthController@updateUser')->middleware('auth');
	Route::post('/user/update/{id}', 'Auth\AuthController@updateThisUser')->middleware('auth');
	Route::get('/user/delete/{id}', 'Auth\AuthController@deleteUser')->middleware('auth');
	
	Route::get('/reset', 'Auth\AuthController@resetpassword');
	Route::post('/reset/password', 'Auth\AuthController@passwordReset');
	 
	 
// LOGIN
// *****

/* Start  Profile routing */
Route::get('/profile','ProfileController@index'); 
Route::post('/profile','ProfileController@edit');
Route::get('/profile/del_icon/{id}','ProfileController@del_icon');
Route::get('/profile/view/{id}','ProfileController@view');
Route::get('/profile/delete/{id}','ProfileController@destroy');
Route::get('/profile/status/{id}/{val}','ProfileController@status');
Route::get('/changepassword/','ChangepasswordController@index');
Route::post('/changepassword/','ChangepasswordController@edit');

/* End admin Profile routing */
/* start trainer */
Route::get('/trainer/all-trainer','TrainerController@index')->middleware('auth');
Route::get('/trainer/gettrainer','TrainerController@getPaginatedTrainer')->middleware('auth');
Route::get('/trainer/add-trainer','TrainerController@create')->middleware('auth');
Route::post('/trainer/add-trainer','TrainerController@store')->middleware('auth');
Route::post('/trainer/selectdelete','TrainerController@destroy')->middleware('auth');
Route::get('/trainer/store-follow-up/{id}','TrainerController@storeFollowUp')->middleware('auth');
Route::get('/trainer/follow-up/{id}','TrainerController@followUp')->middleware('auth');
Route::get('/trainer/getfollowups/{id}','TrainerController@getFollowUps')->middleware('auth');
Route::get('/trainer/update/{id}','TrainerController@edit')->middleware('auth');
Route::post('/trainer/update/{id}','TrainerController@update')->middleware('auth');
Route::post('/trainer/gettrainerexcel','TrainerController@getTrainerExcel')->middleware('auth');	 
	 
/* End trainer */

/* start trainer Required*/
Route::get('/trainerrequired/all-trainer','TrainerRequiredController@index')->middleware('auth');
Route::get('/trainerrequired/gettrainer','TrainerRequiredController@getPaginatedTrainer')->middleware('auth');
Route::match(['get','post'],'/trainerrequired/add','TrainerRequiredController@add')->middleware('auth');
Route::match(['get','post'],'/trainerrequired/edit/{id}','TrainerRequiredController@edit')->middleware('auth');
Route::post('/trainerrequired/selectdelete','TrainerRequiredController@destroy')->middleware('auth');
Route::get('/trainerrequired/store-follow-up/{id}','TrainerRequiredController@storeFollowUp')->middleware('auth');
Route::get('/trainerrequired/follow-up/{id}','TrainerRequiredController@followUp')->middleware('auth');
Route::get('/trainerrequired/getfollowups/{id}','TrainerRequiredController@getFollowUps')->middleware('auth');
Route::post('/trainerrequired/gettrainerrequiredexcel','TrainerRequiredController@getTrainerExcel')->middleware('auth');
 
/* End trainer */


/* start Hiring Required*/
Route::get('/hiring/all-hiring','HiringController@index')->middleware('auth');
Route::get('/hiring/gethiring','HiringController@getPaginatedHiring')->middleware('auth');
Route::match(['get','post'],'/hiring/add','HiringController@add')->middleware('auth');
Route::match(['get','post'],'/hiring/edit/{id}','HiringController@edit')->middleware('auth');
Route::post('/hiring/status/{id}/{val}','HiringController@status')->middleware('auth');
Route::post('/hiring/selectdelete','HiringController@destroy')->middleware('auth');
Route::get('/hiring/store-follow-up/{id}','HiringController@storeFollowUp')->middleware('auth');
Route::get('/hiring/follow-up/{id}','HiringController@followUp')->middleware('auth');
Route::get('/hiring/getfollowups/{id}','HiringController@getFollowUps')->middleware('auth');
Route::post('/hiring/gettrainerrequiredexcel','HiringController@getTrainerExcel')->middleware('auth');
 
/* End Hiring */

/* start category Required*/
Route::get('category/all-category','CategoryController@index')->middleware('auth');
Route::get('/category/get-category','CategoryController@getCategoryPaginated')->middleware('auth');
Route::match(['get','post'],'/category/add','CategoryController@add')->middleware('auth');
Route::match(['get','post'],'/category/edit/{id}','CategoryController@edit')->middleware('auth');
Route::post('/category/status/{id}/{val}','CategoryController@status')->middleware('auth');
Route::get('/category/delete/{id}','CategoryController@destroy')->middleware('auth');

/* start category Required*/ 
Route::get('/categoryType/all-category-type/','CategoryTypeController@index')->middleware('auth'); 
Route::get('/categoryType/get-category-type','CategoryTypeController@getCategoryTypePaginated')->middleware('auth');
Route::match(['get','post'],'/categoryType/add','CategoryTypeController@add')->middleware('auth');
Route::match(['get','post'],'/categoryType/edit/{id}','CategoryTypeController@edit')->middleware('auth');
Route::post('/categoryType/status/{id}/{val}','CategoryTypeController@status')->middleware('auth');
Route::get('/categoryType/delete/{id}','CategoryTypeController@destroy')->middleware('auth');



/* start Sub category Required*/

Route::get('subcategory/all-sub-category','SubCategoryController@index')->middleware('auth');
Route::get('/subcategory/get-subcategory','SubCategoryController@getCategoryPaginated')->middleware('auth');
Route::match(['get','post'],'/subcategory/add','SubCategoryController@add')->middleware('auth');
Route::match(['get','post'],'/subcategory/edit/{id}','SubCategoryController@edit')->middleware('auth');
Route::post('/subcategory/status/{id}/{val}','SubCategoryController@status')->middleware('auth');
Route::get('/subcategory/delete/{id}','SubCategoryController@destroy')->middleware('auth');





/* start category Required*/ 
Route::get('/categoryType/all-category-type/','CategoryTypeController@index')->middleware('auth'); 


 
Route::get('/categoryType/get-category-type','CategoryTypeController@getCategoryTypePaginated')->middleware('auth');
Route::match(['get','post'],'/categoryType/add','CategoryTypeController@add')->middleware('auth');
Route::match(['get','post'],'/categoryType/edit/{id}','CategoryTypeController@edit')->middleware('auth');
Route::post('/categoryType/status/{id}/{val}','CategoryTypeController@status')->middleware('auth');
Route::get('/categoryType/delete/{id}','CategoryTypeController@destroy')->middleware('auth');



 Route::get('/coursepdf/all-course-pdf','CoursePdfController@index')->middleware('auth');
 Route::get('/coursepdf/course-pdf/{id}','CoursePdfController@coursepdf')->middleware('auth');
 Route::get('/coursepdf/category-type/{id}','CoursePdfController@categoryTypePdf')->middleware('auth');
 Route::get('/coursepdf/add/{tid?}/{cid?}/{sid?}','CoursePdfController@add')->middleware('auth');
 Route::post('/coursepdf/add/','CoursePdfController@add')->middleware('auth');
 Route::get('/coursepdf/edit/{id}','CoursePdfController@edit')->middleware('auth');
 Route::post('/coursepdf/edit/{id}','CoursePdfController@edit')->middleware('auth');
 Route::get('/coursepdf/get-course-pdf','CoursePdfController@getCoursePdfPaginated')->middleware('auth');

 Route::get('/coursepdf/deleted-icon/{id}/{pid}', 'CoursePdfController@deletedIcon');
 Route::get('/coursepdf/deleted-icon-table/{tid}/{id}/{pid}', 'CoursePdfController@deletedIcontable');
 Route::get('/coursepdf/delete/{id}', 'CoursePdfController@destroy');
 Route::get('/coursepdf/coursepdfstatus/{id}/{val}','CoursePdfController@coursepdfstatus')->middleware('auth');
  Route::get('/coursepdf/get-category-ajax/{id}','CoursePdfController@getCategoryAjax');
 Route::get('/coursepdf/get-subcategory-ajax/{id}','CoursePdfController@getSubCategoryAjax');
 
  Route::post('/coursepdf/download','CoursePdfController@coursPdfdownload');
   
  Route::post('/courses/ajax_view', 'CoursePdfController@ajax_view');
 
  Route::get('/coursepdf/pending','CoursePdfController@pending')->middleware('auth');
  Route::get('/coursepdf/get-coursepdfpending','CoursePdfController@getcoursepdfpendingPagination')->middleware('auth');
 
/* End coursepdf */

Route::get('/fees/all-fees','FeesManageController@index')->middleware('auth');
Route::get('/fees/add-fees','FeesManageController@add')->middleware('auth');
Route::post('/fees/save-fees','FeesManageController@store')->middleware('auth');
Route::get('/fees/get-all-fees','FeesManageController@getPaginatedAllFees')->middleware('auth');
Route::get('/fees/searchfeesLeaddata/{id}','FeesManageController@searchfeesLeaddata')->middleware('auth');
Route::get('/fees/searchfeesExperiensedata/{id}','FeesManageController@searchfeesExperiensedata')->middleware('auth');
Route::get('/fees/searchFeesCertificateData/{id}','FeesManageController@searchFeesCertificateData')->middleware('auth');
Route::get('/fees/searchPendingFeesExperienceData/{id}','FeesManageController@searchPendingFeesExperienceData')->middleware('auth');
Route::get('/fees/searchFeesPendingCertificateData/{id}','FeesManageController@searchFeesPendingCertificateData')->middleware('auth');
Route::get('/fees/searchfeesPendingdata/{id}','FeesManageController@searchfeesPendingdata')->middleware('auth');
Route::get('/fees/searchDemo/{id}','FeesManageController@searchDemo')->middleware('auth');
Route::post('/fees/selectdelete','FeesManageController@destroy')->middleware('auth');




//Route::get('lead/counsellor-payment-mode','FeesManageController@counsellorPaymentModeIndex');
//Route::get('lead/get-counsellor-payment-mode','FeesManageController@getcounsellorPaymentModePegination');


// ****
// LEAD

	Route::get('/lead/show-seo-lead','LeadController@showseolead')->middleware('auth');
	Route::get('/lead/get-show-seo-leads','LeadController@getPaginatedShowSeoLead')->middleware('auth');
	
	Route::get('/lead/all-leads','LeadController@index')->middleware('auth');
	Route::get('/lead/getleads','LeadController@getPaginatedLeads')->middleware('auth');
	Route::get('/lead/getNotInterestedleads','LeadController@getNotInterestedPaginatedLeads')->middleware('auth');
	Route::get('/lead/getexpectedleads','LeadController@getexpectedPaginatedLeads')->middleware('auth');
	Route::get('/lead/getexpectednewbatchleads','LeadController@getexpectednewbatchdLeads')->middleware('auth');
	Route::post('/lead/getleadsexcel','LeadController@getLeadsExcel')->middleware('auth');
	Route::post('/lead/getleadsSalesTeamexcel','LeadController@getleadsSalesTeamexcel')->middleware('auth');
	Route::post('/lead/getleadsSalesCounsellorTeamexcel','LeadController@getleadsSalesCounsellorTeamexcel')->middleware('auth');
	
	Route::post('/lead/getExcelDeletedLeads','LeadController@getExcelDeletedLeads')->middleware('auth');
	Route::post('/lead/getleadsexcelNotInterested','LeadController@getleadsexcelNotInterested')->middleware('auth');
	Route::get('/lead/add-lead','LeadController@create')->middleware('auth');
	Route::post('/lead/add-lead','LeadController@store')->middleware('auth');
	Route::get('/lead/update/{id}','LeadController@edit')->middleware('auth');
	Route::post('/lead/update/{id}','LeadController@update')->middleware('auth');
	Route::get('/lead/delete/{id}','LeadController@destroy')->middleware('auth');
	Route::get('/lead/force-delete/{id}','LeadController@forceDelete')->middleware('auth');
	Route::get('/lead/deleted-leads','LeadController@deletedLeads')->middleware('auth');
	Route::get('/lead/getdeletedleads','LeadController@getPaginatedDeletedLeads')->middleware('auth');
	Route::get('/lead/restore/{id}','LeadController@restore')->middleware('auth');
	Route::post('/lead/store-follow-up/{id}','LeadController@storeFollowUp')->middleware('auth');
	Route::get('/lead/follow-up/{id}','LeadController@followUp')->middleware('auth');
	Route::get('/lead/leadjoindededit/{id}','LeadController@leadjoindededit')->middleware('auth');	
	Route::get('/lead/storeleadjoind/{id}','LeadController@storeleadjoind')->middleware('auth');
	Route::get('/lead/getfollowups/{id}','LeadController@getFollowUps')->middleware('auth');
	Route::get('/lead/sendmail/{id}','LeadController@sendMailToCounsellor')->middleware('auth');
	Route::post('/lead/send-bulk-sms','LeadController@sendBulkSms')->middleware('auth');
	Route::post('/lead/move-not-interested','LeadController@moveNotInterested')->middleware('auth');
	Route::post('/lead/move-to-expected-lead','LeadController@moveToExpectedLead')->middleware('auth');
	
	Route::get('/lead/expect-follow-up/{id}','LeadController@expectfollowUp')->middleware('auth');
	Route::get('/lead/getexpectfollowups/{id}','LeadController@getExpectFollowUps')->middleware('auth');
	Route::get('/lead/store-expect-follow-up/{id}','LeadController@storeExpectFollowUp')->middleware('auth');
	Route::get('/lead/get_user_ajax','LeadController@getUserAjax')->middleware('auth');
 
	Route::post('/lead/move-to-lead','LeadController@moveToLeads')->middleware('auth');
	Route::post('/lead/expected-move-to-lead','LeadController@expectedMoveToLeads')->middleware('auth');
	Route::post('/lead/expected-new-batch-move-to-lead','LeadController@expectedNewBatchMoveToLeads')->middleware('auth');
	Route::post('lead/selectDelete','LeadController@selectDelete')->middleware('auth');
	
	Route::post('lead/selectForwardDelete','LeadController@selectForwardDelete')->middleware('auth');
	Route::post('lead/selectDeleteParmanent','LeadController@selectDeleteParmanent')->middleware('auth');
	Route::post('lead/selectToNewLeads','LeadController@selectToNewLeads')->middleware('auth');
	Route::post('/lead/update_job_notification','LeadController@update_job_notification')->middleware('auth');
	
	
	Route::get('/lead/lead-forward','LeadController@leadforward')->middleware('auth');
	Route::get('/lead/get-lead-forward','LeadController@getPaginatedLeadForward')->middleware('auth');
	
	Route::get('lead/lead-forward-form/{id}','LeadController@leadForwardForm')->middleware('auth');
	Route::get('/lead/store-lead-forward/{id}','LeadController@storeLeadForward')->middleware('auth');

// LEAD
// ****


    Route::get('/lead/mailer','LeadController@mailer')->middleware('auth');
	Route::get('/lead/mailer_data','LeadController@getPaginationMailerData')->middleware('auth');
	Route::post('lead/selectMailerDelete','LeadController@selectMailerDelete')->middleware('auth');
	
	
	Route::get('/lead/website-feedback','LeadController@websitefeedback')->middleware('auth');
	Route::get('/lead/get-website-feedback','LeadController@getPaginationwebsitefeedback')->middleware('auth');
	Route::post('lead/selectFeedbackDelete','LeadController@selectFeedbackDelete')->middleware('auth');

	
	
	Route::get('/lead/counsellor-feedback','LeadController@counsellorfeedback')->middleware('auth');
	Route::get('/lead/get-counsellor-feedback','LeadController@getPaginationCounsellorfeedback')->middleware('auth');
	Route::post('lead/counsellorFeedbackDelete','LeadController@counsellorFeedbackDelete')->middleware('auth');



//Enter leads
Route::get('/enterleads', 'EnterLeadsController@getForm')->middleware('auth');
Route::get('/enterSocailleads', 'EnterLeadsController@getSocialForm')->middleware('auth');
Route::post('/enterleadSave', 'EnterLeadsController@enterleadSave');
Route::get('genie/get_coursellor_course','EnterLeadsController@getCoursellorCourse');
Route::get('genie/get_international_course','EnterLeadsController@getinternationalcourse');




  //lead 
Route::get('genie/lead','LeadAssignController@index');
Route::get('genie/lead-analysis','LeadAssignController@leadanalysis');
Route::get('genie/lead-source','LeadAssignController@leadsource');
Route::get('genie/lead/get-source-count','LeadAssignController@getPaginationSourcecount'); 
Route::get('genie/get-lead','LeadAssignController@getLeadPagination');
Route::get('getleadcount','LeadAssignController@getleadcount');
Route::get('/genie/lead/get-source-view','LeadAssignController@getPaginationSourceView')->middleware('auth'); 
Route::get('genie/monthly-lead-analysis','LeadAssignController@monthlyleadanalysis');
Route::get('genie/lead/get-monthly-lead-analysis','LeadAssignController@getMonthlyPaginationLeadAnalysis');
Route::get('genie/course-assignment','LeadAssignController@courseassignment');  
Route::get('genie/get-assign-course','LeadAssignController@getCourseAssignmentPagination'); 

Route::post('genie/lead/selectTodeleteLeads','LeadAssignController@selectTodeleteLeads');


// ******************************
// NOT INTERESTED LEADS AND DEMOS
	Route::get('/lead/not-interested','LeadController@indexNotInterested')->middleware('auth');
	Route::get('/demo/not-interested','DemoController@indexNotInterested')->middleware('auth');
	Route::get('/demo/joined-demos','DemoController@joinedDemos')->middleware('auth');
	Route::get('/demo/trainer-status','DemoController@trainerStatus')->middleware('auth');
	Route::get('/demo/gettrainerstatus','DemoController@gettrainerstatus')->middleware('auth');
	Route::get('/lead/expected-lead','LeadController@indexExpectedLead')->middleware('auth');
	Route::get('/lead/expected-new-batch-lead','LeadController@expectedNewBatchLead')->middleware('auth');
	Route::get('/demo/expected-demo','DemoController@indexExpecteddemo')->middleware('auth');
	Route::get('/demo/expected-new-batch-demo','DemoController@expectedNewBatchDemo')->middleware('auth');
	Route::get('/lead/expected-lead-demo','LeadController@indexExpectedLeadDemo')->middleware('auth');	 
	Route::get('/lead/getexpectedleadsdemo','LeadController@getexpectedPaginatedLeadsDemo')->middleware('auth');
	Route::post('/lead/move-to-expected-lead-demo','LeadController@moveToExpectedLeadDemo')->middleware('auth');	 
	Route::post('/lead/expected-demo-move-to-lead','LeadController@expectedDemoMoveToLeads')->middleware('auth');
	Route::post('/lead/delete-move-to-lead','LeadController@deleteMoveToLeads')->middleware('auth');
	Route::post('/lead/expected-demo-move-to-demo-lead','LeadController@expectedDemoMoveToDemoLeads')->middleware('auth');
// NOT INTERESTED LEADS AND DEMOS
// ******************************
    Route::get('/content/add-content','ContentController@create')->middleware('auth');
	Route::post('/content/add-content','ContentController@store')->middleware('auth');
	Route::get('/content/all-content','ContentController@index')->middleware('auth');
	Route::get('/content/getcontent','ContentController@getPaginatedContent')->middleware('auth');
	Route::get('/content/update/{id}','ContentController@edit')->middleware('auth');
	Route::post('/content/update/{id}','ContentController@update')->middleware('auth');
	Route::get('/content/view/{id}','ContentController@view')->middleware('auth');
	Route::get('/content/delete/{id}','ContentController@destroy')->middleware('auth');
	Route::get('/content/getcontentexcel','ContentController@getContentExcel')->middleware('auth');

// ********
// TRANSFER
	Route::get('/permanent-transfer','TransferController@index')->middleware('auth');
	Route::post('/permanent-transfer','TransferController@transfer')->middleware('auth');
// TRANSFER
// ********

// ******
// SOURCE
	Route::get('/source','SourceController@index')->middleware('auth');
	Route::post('/source','SourceController@store')->middleware('auth');
	Route::get('/source/getsources','SourceController@getPaginatedSources')->middleware('auth');
	Route::get('/source/update/{id}','SourceController@edit')->middleware('auth');
	Route::post('/source/update/{id}','SourceController@update')->middleware('auth');
	Route::get('/source/delete/{id}','SourceController@destroy')->middleware('auth');
	Route::get('/source/status/{id}/{val}','SourceController@status')->middleware('auth');
	Route::post('/source/source-social','SourceController@social')->middleware('auth');
	Route::post('/source/source-dailystatus','SourceController@dailystatus')->middleware('auth');
	
// SOURCE
// ******

// ******
// COURSE
	Route::get('/course','CourseController@index')->middleware('auth');
	Route::post('/course','CourseController@store')->middleware('auth');
	Route::get('/course/getcourses','CourseController@getPaginatedCourses')->middleware('auth');
	Route::get('/course/get_c_ajax','CourseController@getCourseAjax')->middleware('auth');
	Route::get('/course/update/{id}','CourseController@edit')->middleware('auth');
	Route::post('/course/update/{id}','CourseController@update')->middleware('auth');
	Route::get('/course/delete/{id}','CourseController@destroy')->middleware('auth');
	Route::get('/course/getcoursecounsellors/{id}','CourseController@getCourseCounsellors')->middleware('auth');
	Route::post('/course/getCourseexcel','CourseController@getCourseExcel')->middleware('auth');
	
	Route::post('/course/assignment/addcourse','CourseController@saveAssignCourse')->middleware('auth');
	Route::get('/course/assignment/assigncourse','CourseController@assignCourse')->middleware('auth');
	Route::get('/course/assignment/getAssignCourse','CourseController@getAssignCourse')->middleware('auth');
	Route::get('/course/assignment/editassigncourse/{id}','CourseController@editcourse')->middleware('auth');	 
	Route::post('/course/assignment/editassigncourse/{id}','CourseController@editsavecourse')->middleware('auth');	 
	Route::get('/course/assignment/assigncoursedelete/{id}','CourseController@assigncoursedelete')->middleware('auth');
	 
 	 
	Route::get('/course/assignment/assigncurrentlead','Auth\AuthController@assigncurrentlead');	
	Route::get('/course/assignment/assignbeforelead','Auth\AuthController@assignbeforelead');	
	 
// COURSE
// ******
Route::get('/trainer/get_fees_trainer_ajax','DemoController@getFeesTrainerAjax')->middleware('auth');
// *******
// MESSAGE
	Route::get('/message','MessageController@index')->middleware('auth');
	Route::post('/message','MessageController@store')->middleware('auth');
	Route::get('/message/getmessageslist/{id}','MessageController@getMessagesList')->middleware('auth');
	Route::get('/message/update/{id}','MessageController@edit')->middleware('auth');
	Route::post('/message/update/{id}','MessageController@update')->middleware('auth');
	Route::get('/message/delete/{id}','MessageController@destroy')->middleware('auth');
// MESSAGE
// *******

// *******
// email
	Route::get('/email','EmailController@index');//->middleware('auth');
	Route::post('/email','EmailController@store');//->middleware('auth');
	Route::get('/email/edit/{id}','EmailController@edit');//->middleware('auth');
	Route::post('/email/edit/{id}','EmailController@update');//->middleware('auth');
	Route::get('/email/delete/{id}','EmailController@destroy')->middleware('auth');
	
	
	// MESSAGE
// *******

 

// *******
// Mobile
	Route::get('/mobile','MobileController@index');//->middleware('auth');
	Route::post('/mobile','MobileController@store');//->middleware('auth');
	Route::get('/mobile/edit/{id}','MobileController@edit');//->middleware('auth');
	Route::post('/mobile/edit/{id}','MobileController@update');//->middleware('auth');
	Route::get('/mobile/delete/{id}','MobileController@destroy')->middleware('auth');
	
	
	// Mobile
// *******
// ****
// DEMO
 

	Route::get('/demo/all-leads','DemoController@index')->middleware('auth');
	Route::get('/demo/getleads','DemoController@getPaginatedLeads')->middleware('auth');
	Route::get('/demo/get-joined-demos','DemoController@getPaginatedJoinedDemos')->middleware('auth');
	Route::get('/demo/getexpectedleads','DemoController@getExpectedPaginatedLeads')->middleware('auth');
	Route::get('/demo/getexpectednewbatchdemo','DemoController@getExpectednewbatchdemo')->middleware('auth');
	Route::post('/demo/getdemosexcel','DemoController@getDemosExcel')->middleware('auth');
	Route::post('/demo/getExcelDeletedDemo','DemoController@getExcelDeletedDemo')->middleware('auth');
	Route::post('/demo/getdemosexcelNotInterested','DemoController@getdemosexcelNotInterested')->middleware('auth');
	Route::get('/demo/add-lead','DemoController@create')->middleware('auth');
	Route::post('/demo/add-lead','DemoController@store')->middleware('auth');
	Route::get('/demo/leadjoinded','DemoController@leadjoinded')->middleware('auth');
	Route::get('/demo/add-lead-students','DemoController@createStudents')->middleware('auth');
	Route::post('/demo/add-lead-students','DemoController@storeStudents')->middleware('auth');
	Route::get('/demo/update/{id}','DemoController@edit')->middleware('auth');
	Route::post('/demo/update/{id}','DemoController@update')->middleware('auth');
	Route::get('/demo/delete/{id}','DemoController@destroy')->middleware('auth');
	Route::get('/demo/force-delete/{id}','DemoController@forceDelete')->middleware('auth');
	Route::get('/demo/deleted-leads','DemoController@deletedLeads')->middleware('auth');
	Route::get('/demo/getdeletedleads','DemoController@getPaginatedDeletedLeads')->middleware('auth');
	Route::get('/demo/restore/{id}','DemoController@restore')->middleware('auth');
	Route::post('/demo/store-follow-up/{id}','DemoController@storeFollowUp')->middleware('auth');
	Route::get('/demo/follow-up/{id}','DemoController@followUp')->middleware('auth');
	Route::get('/demo/getfollowups/{id}','DemoController@getFollowUps')->middleware('auth');
	Route::get('/demo/verify-demo/{id}','DemoController@verifyDemo')->middleware('auth');
	Route::get('/demo/leadDemoJoined-verify-lead/{id}','DemoController@leadDemoJoinedverifyLead')->middleware('auth');
	Route::get('/demo/leadDemoJoined-verify-demo/{id}','DemoController@leadDemoJoinedverifyDemo')->middleware('auth');
	Route::post('/demo/leadDemoJoined','DemoController@leadDemoJoined')->middleware('auth');
	
	Route::get('/lead/search-lead','DemoController@leadSearchLead')->middleware('auth');
	Route::get('/demo/searchLead/{id}','DemoController@searchLead')->middleware('auth');
	Route::get('/demo/searchDemo/{id}','DemoController@searchDemo')->middleware('auth');
	
	
	Route::get('/demo/sendmail/{id}','DemoController@sendMailToCounsellor')->middleware('auth');
	Route::post('/demo/send-bulk-sms','DemoController@sendBulkSms')->middleware('auth');
	Route::post('/demo/move-not-interested','DemoController@moveNotInterested')->middleware('auth');
	Route::post('/demo/move-to-demos','DemoController@moveToDemos')->middleware('auth');
	Route::post('/demo/move-to-join-demos','DemoController@moveToJoinDemos')->middleware('auth');
	Route::post('/demo/expected-move-to-demos','DemoController@expectedMoveToDemos')->middleware('auth');
	Route::post('/demo/expected-new-batch-move-to-demos','DemoController@expectedNewBatchMoveToDemos')->middleware('auth');
	Route::post('/demo/selectDelete','DemoController@selectDelete')->middleware('auth');
	Route::post('/demo/selectDeleteParmanent','DemoController@selectDeleteParmanent')->middleware('auth');
	Route::post('/demo/selectToNewDemos','DemoController@selectToNewDemos')->middleware('auth');
	Route::post('/demo/move-joined-demos','DemoController@moveJoinedDemos')->middleware('auth');
	Route::post('/demo/move-to-expected-demos','DemoController@moveToExpectedDemos')->middleware('auth');
	Route::post('/demo/move-to-expected-new-batch-demos','DemoController@moveToExpectedNewBatchDemos')->middleware('auth');
	Route::post('/demo/update_job_notification','DemoController@update_job_notification')->middleware('auth');
	
	
    Route::get('/demo/batch/addbatch','DemoController@addbatch')->middleware('auth');
	Route::post('/demo/batch/addbatch','DemoController@savebatch')->middleware('auth');
	Route::get('/demo/batch/batch','DemoController@batch')->middleware('auth');
	Route::get('/demo/batch/getbatch','DemoController@getbatch')->middleware('auth');
	Route::get('/demo/batch/editbatch/{id}','DemoController@editbatch')->middleware('auth');	 
	Route::post('/demo/batch/assignleadbatch','DemoController@assignLeadBatch')->middleware('auth');
	Route::get('/demo/batch/lead-batch','DemoController@LeadBatch')->middleware('auth');
	Route::get('/demo/batch/getleadbatch','DemoController@getLeadBatch')->middleware('auth');
	Route::post('/demo/batch/batch-move-to-demos','DemoController@BatchMoveToDemos')->middleware('auth');
 
 Route::get('/demo/demojoindededit/{id}','DemoController@demojoindededit')->middleware('auth');	
	Route::get('/demo/storedemojoind/{id}','DemoController@storedemojoind')->middleware('auth');
	
// DEMO
// ****

// ******
// STATUS
	Route::get('/status','StatusController@index')->middleware('auth');
	Route::post('/status','StatusController@store')->middleware('auth');
	Route::get('/status/getstatus','StatusController@getPaginatedStatus')->middleware('auth');
	Route::get('/status/update/{id}','StatusController@edit')->middleware('auth');
	Route::post('/status/update/{id}','StatusController@update')->middleware('auth');
	Route::get('/status/delete/{id}','StatusController@destroy')->middleware('auth');
// STATUS
// ******

// *********
// DASHBOARD
	Route::get('/dashboard/counsellor/{id?}','CounsellorDashboardController@index')->middleware('auth')->name('dashboard');
	Route::get('/dashboard/counsellor/pending-leads/getleads','CounsellorDashboardController@getPaginatedLeads')->middleware('auth');
	Route::post('/dashboard/counsellor/pending-leads/getleadsexcel','CounsellorDashboardController@getLeadsExcel')->middleware('auth');
	Route::get('/dashboard/counsellor/pending-demos/getdemos','CounsellorDashboardController@getPaginatedDemos')->middleware('auth');
	Route::post('/dashboard/counsellor/pending-demos/getdemosexcel','CounsellorDashboardController@getDemosExcel')->middleware('auth');
	Route::get('/dashboard/counsellor/draw-graph','CounsellorDashboardController@drawGraph')->middleware('auth');
	Route::post('/dashboard/counsellor/daily-calling-status/{id?}','CounsellorDashboardController@getCallingStatus')->middleware('auth');
	Route::get('/dashboard/demo-follow-up/{id}','CounsellorDashboardController@demofollowUp')->middleware('auth');
	Route::get('/dashboard/getfollowups/{id}','CounsellorDashboardController@getFollowUps')->middleware('auth');
	Route::get('/demodashboard/getfollowups/{id}','CounsellorDashboardController@getDemoFollowUps')->middleware('auth');
	Route::post('/dashboard/chating','CounsellorDashboardController@chatingSubmit')->middleware('auth');
	Route::get('//dashboard/getchating','CounsellorDashboardController@getchating')->middleware('auth');
	
// DASHBOARD
// *********

// *****************
// ROLES PERMISSIONS
	
	Route::get('/permission','RolesPermissionsController@permissionIndex')->middleware('auth');
	Route::post('/permission','RolesPermissionsController@permissionStore')->middleware('auth');
	Route::get('/permission/getpermission','RolesPermissionsController@getPaginatedPermissions')->middleware('auth');
	Route::get('/permission/update/{id}','RolesPermissionsController@editPermission')->middleware('auth');
	Route::post('/permission/update/{id}','RolesPermissionsController@updatePermission')->middleware('auth');
	Route::get('/permission/delete/{id}','RolesPermissionsController@destroyPermission')->middleware('auth');
	
	Route::get('/role-permission','RolesPermissionsController@rolePermissionIndex');
	Route::post('/role-permission','RolesPermissionsController@rolePermissionStore');
	Route::get('/role-permission/getpermission','RolesPermissionsController@getPaginatedRolesPermissions');
	Route::get('/role-permission/update/{id}','RolesPermissionsController@editRolePermission');
	Route::post('/role-permission/update/{id}','RolesPermissionsController@updateRolePermission');
	Route::get('/role-permission/delete/{id}','RolesPermissionsController@destroyRolePermission');
	Route::get('/role-permission/{id}','RolesPermissionsController@getRolePermissions');
// ROLES PERMISSIONS
// *****************

//Route::get('/home', 'HomeController@index');
Route::get('/dashboard/counsellor/pending-leads-demos/getleadsdemos','CounsellorDashboardController@getPaginatedLeadsDemos')->middleware('auth');
Route::get('/dashboard/counsellorlist','CounsellorDashboardController@getPaginatedCounsellorlist')->middleware('auth');
Route::post('/dashboard/counsellor/pending-leads-demos/getleadsdemosexcel','CounsellorDashboardController@getLeadsDemosExcel')->middleware('auth');
Route::get('/dashboard/counsellor/upcoming-batches/getbatches','upcomingBatchController@getPaginatedbatches')->middleware('auth');
Route::post('/upcomingBatch/save','upcomingBatchController@save')->middleware('auth');
Route::get('/upcomingBatch/destroy/{id}','upcomingBatchController@destroy')->middleware('auth');
/* Bulk Upload */
	Route::get('/bulkupload/leads','BulkUploadController@createBulkUpload');
	Route::post('/bulkupload/leads','BulkUploadController@storeBulkUpload');
	
	Route::get('/bulkupload/demos','BulkUploadController@createBulkUploadDemo');
	Route::post('/bulkupload/demos','BulkUploadController@storeBulkUploadDemo');
/* Bulk Upload */




Route::get('lead/all-lead-managment','LeadManagmentController@index')->middleware('auth');
Route::get('lead/all-delete-lead','LeadManagmentController@deleteindex')->middleware('auth');
Route::get('lead/get-all-received-leads','LeadManagmentController@getLeadPagination')->middleware('auth');
Route::get('lead/get-all-duplicate-leads','LeadManagmentController@getDuplicateLeadPagination')->middleware('auth');
Route::get('lead/get-all-deleted-leads','LeadManagmentController@getDeletedLeadPagination')->middleware('auth');
Route::get('lead/get-all-lead-assignment','LeadManagmentController@getLeadAssignmentPagination')->middleware('auth');
Route::get('lead/get-all-not-lead-assignment','LeadManagmentController@getNotLeadAssignmentPagination')->middleware('auth');
Route::get('lead/all-duplicate-lead','LeadManagmentController@duplicateleadindex')->middleware('auth');

Route::get('lead/all-counsllor-view','LeadManagmentController@counsllorviewindex')->middleware('auth');
Route::get('lead/all-counsllor','LeadManagmentController@allCounsllor')->middleware('auth');
Route::get('/lead/get-counsellor','LeadManagmentController@getPaginationCounsellor')->middleware('auth');
 

Route::get('/lead/get-counsellor-view','LeadManagmentController@getPaginationCounsellorView')->middleware('auth');
Route::get('/lead/getCourseWiseCountlead/{id}','LeadManagmentController@getCourseWiseCountlead')->middleware('auth');
Route::get('/lead/getCourseLead/{id}','LeadManagmentController@getCourseLead')->middleware('auth');
Route::get('lead/all-course-assignment','CourseAssignController@courseassignmentindex')->middleware('auth');
Route::get('lead/duplicate-course-assignment','CourseAssignController@duplicatecourseindex')->middleware('auth');
Route::post('lead/courseassignmentadd','CourseAssignController@courseAssignmentSave')->middleware('auth');
Route::post('lead/saveleadcount','CourseAssignController@saveleadcount')->middleware('auth');
Route::post('lead/duplicatecourseassignment','CourseAssignController@duplicatecourseassignment')->middleware('auth');
Route::get('/lead/editassigncourse/{id}','CourseAssignController@editcourse')->middleware('auth');	 
Route::post('/lead/editassigncourse/{id}','CourseAssignController@editsavecourse')->middleware('auth');	
	
Route::get('/lead/editduplicatecourse/{id}','CourseAssignController@editduplicatecourse')->middleware('auth');	 
Route::post('/lead/editduplicatecourse/{id}','CourseAssignController@editsaveduplicatecourse')->middleware('auth');	
Route::get('/course/assignment/assigncoursestatus/{id}/{val}','CourseAssignController@assigncoursestatus')->middleware('auth');
 Route::get('/lead/assigncoursedelete/{id}','CourseAssignController@assigncoursedelete')->middleware('auth');

Route::get('lead/lead-assignment','LeadManagmentController@leadassignmentindex')->middleware('auth');
Route::get('lead/not-lead-assignment','LeadManagmentController@notleadassignmentindex')->middleware('auth');
Route::get('lead/lead-assign-form/{id}','LeadManagmentController@leadAssignForm')->middleware('auth');
Route::get('/lead/store-lead-assign/{id}','LeadManagmentController@storeLeadAssign')->middleware('auth');

Route::get('lead/all-dual-lead','LeadManagmentController@duallead')->middleware('auth');
Route::get('lead/get-all-dual-leads','LeadManagmentController@getDualLeadPagination')->middleware('auth');
Route::get('lead/leaddelete/{id}','LeadManagmentController@leaddelete')->middleware('auth');
Route::get('lead/lead-assign/update/{id?}','LeadManagmentController@updateassignlead')->middleware('auth');
Route::post('lead/lead-assign/save/{id?}','LeadManagmentController@saveassignlead')->middleware('auth');
Route::post('/lead/getleadsreceivedexcel','LeadManagmentController@getleadsreceivedexcel')->middleware('auth');	
Route::post('/lead/getleadsreceivedDeleteexcel','LeadManagmentController@getleadsreceivedDeleteexcel')->middleware('auth');	
Route::post('/lead/getleadsreceivedunassignexcel','LeadManagmentController@getleadsreceivedunassignexcel')->middleware('auth');	
Route::post('lead/selectReceivedDelete','LeadManagmentController@selectReceivedDelete')->middleware('auth');	
Route::post('lead/selectReceivedSoftDelete','LeadManagmentController@selectReceivedSoftDelete')->middleware('auth');
Route::get('/lead/statususer/{id}/{val}','LeadManagmentController@statususer')->middleware('auth');
Route::post('/lead/getCourseAssignExcel','LeadManagmentController@getCourseAssignExcel')->middleware('auth');



Route::get('absent/all-absent-course','AbsentAssignController@courseassignmentindex')->middleware('auth');
Route::get('absent/absent-assign-course-view','AbsentAssignController@absentassignview')->middleware('auth');
Route::get('/absent/get-assign-domestic-course-ajax/{id}','AbsentAssignController@getassigndomestinccourse');
Route::post('/absent/absentAssignCourse/','AbsentAssignController@storeAbsentAssign');
Route::get('/absent/get-absent-assign-view/','AbsentAssignController@getpaginationAbsentAssignView');
Route::get('/absent/absentassigncoursedelete/{id}','AbsentAssignController@absentassigncoursedelete');
Route::get('/absent/editabsentassigncourse/{id}','AbsentAssignController@editabsentcourse')->middleware('auth');	
Route::post('/absent/editabsentassigncourse/{id}','AbsentAssignController@editAbsentsavecourse')->middleware('auth');


Route::get('bucket/all-bucket-course','BucketFullController@index')->middleware('auth');
Route::get('bucket/add-bucket-course','BucketFullController@addBucket')->middleware('auth');
Route::post('/bucket/save-bucketAssignCourse/','BucketFullController@storeBucketAssign');
Route::get('bucket/get-bucket-assign','BucketFullController@getPaginationBucket')->middleware('auth');
Route::get('/bucket/get-bucket-full-assign-view/','BucketFullController@getpaginationBucketAssignView');
Route::get('/bucket/bucketassigncoursedelete/{id}','BucketFullController@bucketassigncoursedelete');
Route::get('/bucket/editbucketassigncourse/{id}','BucketFullController@editbucketcourse')->middleware('auth');	
Route::post('/bucket/editbucketassigncourse/{id}','BucketFullController@editbucketsavecourse')->middleware('auth');	
Route::get('/bucket/get-bucket-domestic-course-ajax/{id}','BucketFullController@getbucketcourse');




//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});


Route::get('/clear/clear-cache', function() {
    
	$exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
	 $exitCode = Artisan::call('route:clear');
	  $exitCode = Artisan::call('view:clear');
    return '<h1>Cache facade value cleared cache from leads URL</h1>';
});
 
Route::get('/api/getValue', 'Api\WebserviceController@getValue');
Route::post('/api/catcourse/save', 'Api\WebserviceController@saveCatCourse');
Route::post('api/saveEnquiry', 'Api\WebserviceController@saveEnquiry');
Route::get('/api/inquiryForm', 'Api\WebserviceController@contactUs');
Route::post('/api/saveCorporateEnquiry', 'Api\WebserviceController@saveCorporateEnquiry');
Route::post('/api/saveFranchise', 'Api\WebserviceController@saveFranchise');
Route::post('/api/saveScholarship', 'Api\WebserviceController@saveScholarship');
Route::post('/api/saveHireCroma', 'Api\WebserviceController@saveHireCroma');

 
Route::post('/api/saveCoursePopup', 'Api\WebserviceController@saveCoursePopup');
Route::post('/api/saveEnquiryDownload', 'Api\WebserviceController@saveEnquiryDownload');
 
Route::post('/api/saveEnquirySide', 'Api\WebserviceController@saveEnquirySide');
 
Route::post('/api/franchiseForm', 'Api\WebserviceController@franchiseFormSave');
Route::post('/api/saveNewsLetter', 'Api\WebserviceController@saveNewsLetter');
Route::post('/api/saveNotifications', 'Api\WebserviceController@saveNotifications');
Route::post('/api/faceAnIssue', 'Api\WebserviceController@faceAnIssue');

 
Route::post('/api/saveChat', 'Api\WebserviceController@saveChat');

 
Route::post('/api/saveCalling', 'Api\WebserviceController@saveCalling');
Route::post('/api/saveInquiry', 'Api\WebserviceController@saveInquiry');

Route::post('/api/saveLead', 'Api\WebserviceController@saveLead');
Route::post('/api/saveWebinars', 'Api\WebserviceController@saveWebinars');
Route::post('/api/save-register-for-st/', 'Api\WebserviceController@saveRegisterForSt');

 Route::post('api/saveLeadSocial', 'Api\WebserviceController@saveLeadSocial');
Route::post('/api/saveTeamLead/', 'Api\WebserviceController@saveTeamLead');
Route::post('/api/saveRequestZone', 'Api\WebserviceController@saveRequestZone');
 
Route::post('/api/saveEnquiryContact', 'Api\WebserviceController@saveEnquiryContact');
  
Route::get('/api/getcounsellor', 'Api\WebserviceController@getCounsellor');
Route::get('/api/getcounsellor-name/{id}', 'Api\WebserviceController@getCounsellorName');


//category dashboard
Route::get('/dashboard/categorylead-analysis','CategoryDashboardController@index')->middleware('auth');
Route::get('/lead/get-category-view','CategoryDashboardController@getPaginationCategoryView')->middleware('auth');

Route::get('/lead/getCategoryCourseWiseCountlead/{id}','CategoryDashboardController@getCourseWiseCountlead')->middleware('auth');
Route::get('/lead/getCategoryCourseLead/{id}','CategoryDashboardController@getCourseLead')->middleware('auth');
  
  