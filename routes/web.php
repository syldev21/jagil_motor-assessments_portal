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

Route::get("/test", function (){
    $assessmentID = "4445";
    $email = "Sylvester.ouma@jubileekenya.com";
//    $message="You claim has been assessed and approved, pending payment";
//
//    $priceChange = App\PriceChange::where('assessmentID', $assessmentID)->first();
//    $aproved = isset($priceChange) ? $priceChange : 'false';
//
//    $assessment = App\Assessment::where(["id" => $assessmentID])->with("claim")->first();
//    $assessmentItems = App\AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
//    $jobDetails = App\JobDetail::where(["assessmentID" => $assessmentID])->get();
//    $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
//    $insured = App\CustomerMaster::where(["customerCode" => $customerCode])->first();
//    $documents = App\Document::where(["assessmentID" => $assessmentID])->get();
//    $adjuster = App\User::where(['id' => $assessment->claim->createdBy])->first();
//    $assessor = App\User::where(['id' => $assessment->assessedBy])->first();
//    $carDetail = App\CarModel::where(['makeCode' => isset($assessment['claim']['carMakeCode']) ? $assessment['claim']['carMakeCode'] : '', 'modelCode' => isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : ''])->first();
//    $pdf = App::make('dompdf.wrapper');
//    $pdf->loadView('adjuster.subrogation-report', compact('assessment', "assessmentItems", "jobDetails", "insured", 'documents', 'adjuster', 'assessor', 'aproved', 'carDetail', 'priceChange'));
////    return view('adjuster.send-subrogation-report', compact('assessment', "assessmentItems", "jobDetails", "insured", 'documents', 'adjuster', 'assessor', 'aproved', 'carDetail', 'priceChange'));

    $message="Your claim has been assessed and approved, pending payment";
    $assessment = App\Assessment::where(["id"=>$assessmentID])->first();
    $claim = App\Claim::where(["id"=>$assessment->claimID])->with('customer')->first();
    $company = App\Company::where(["id"=>$assessment->companyID])->first();
    $pdf = App::make('dompdf.wrapper');
    $pdf->loadView('adjuster.send-subrogation-report',compact('assessment', 'claim', 'company'));
    return $pdf->download();
});

//Route::get('/','HomeController@assessments')->middleware("auth");
Route::get('/', function () {
    return view('authentication.user-login');
});

Auth::routes();


Route::get('/home', 'HomeController@mainDashboard')->name('home');
Route::get('/assessments', 'HomeController@assessments')->name('assessments');
Route::post('/dashboard', 'HomeController@dashboard')->name('dashboard');
Route::post('/markNotification', 'HomeController@markNotification')->name('markNotification');

Route::view('forgot_password', 'auth.reset_password')->name('password.reset');
//Route::get('password/reset', 'ForgotPasswordController@passwordRest');

Route::post('password/email', 'ForgotPasswordController@forgot');

Route::get('password/verifyEmail', 'ForgotPasswordController@verifyEmail')->name('password.verifyEmail');

Route::get('password/reset/{token}', 'ForgotPasswordController@resetPage')->name('password.reset.page');

Route::post('/completePasswordRest', 'ForgotPasswordController@reset');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('user.logout');

// Adjuster Routes
$router->group(['prefix' => 'adjuster'], function($router)
{
    //Motor  assessment User Module
    $router->get('/uploadClaims','AdjusterController@fetchPremiaClaims');
    $router->post('/claim-form','AdjusterController@claimForm');
    $router->get('/claim-details/{claimID}','AdjusterController@claimDetails');
    $router->post('/addClaim','AdjusterController@addClaim');
    $router->get('/uploadDocumentsForm/{claimID}','AdjusterController@uploadDocumentsForm');
    $router->get('/assignAssessor','AdjusterController@assignAssessor');
    $router->post('/search','AdjusterController@searchClaim');
    $router->post('/assessments','AdjusterController@assessments');
    $router->get('/editClaimForm/{id}','AdjusterController@editClaimForm');
    $router->get('/assessment-details/{assessmentID}','AdjusterController@assessmentDetails');
    $router->post('/updateClaim','AdjusterController@updateClaim');
    $router->post('/filterPremia11ClaimsByDate','AdjusterController@filterPremia11ClaimsByDate');
    $router->post('/claimExceptionDetail','AdjusterController@claimExceptionDetail');
    $router->post('/fetch-claims','AdjusterController@claims');
    //Release Letter
    $router->get('/send-release-letter/{claim_id}', 'AdjusterController@generateReleaseLetter');
    //Re-inspection letter
    $router->get('/re-inspection-letter/{claim_id}', 'AdjusterController@reInspectionLetter');

    //send - discharge - voucher
    $router->get('/send-discharge-voucher/{claim_id}', 'AdjusterController@sendDischargeVoucher');

    $router->post('/archiveClaim', 'AdjusterController@archiveClaim');
    $router->post('/fetch-claim-types', 'AdjusterController@fetchClaimTypes');



    //Reports
    $router->post('/assessmentReport','AdjusterController@assessmentReport');
    $router->post('/supplementaries','AdjusterController@supplementaries');
    $router->post('/supplementary-report','AdjusterController@supplementaryReport');
    $router->post('/sendRepairAuthority', 'AdjusterController@SendRepairAuthority');
    $router->post('/sendSubrogationReport', 'AdjusterController@sendSubrogationReport');
    $router->post('/subrogationRegister', 'AdjusterController@showSubrogationRegister');
    $router->post('/emailReleaseletter', 'AdjusterController@emailReleaseletter');
    $router->post('/addLPO', 'AdjusterController@addLPO');
    $router->post('/edit-lpo-amount', 'AdjusterController@editLPO');

//    process courtesy car
    $router->post('/processCourtesy', 'AdjusterController@processCourtesy');
    $router->post('/showCourtesyCar', 'AdjusterController@showCourtesyCar');
    $router->post('/getCharge', 'AdjusterController@getCharge');
    $router->post('/addDays', 'AdjusterController@addDays');
});

// Assessor Routes
$router->group(['prefix' => 'assessor'], function($router)
{
    //Motor  assessment User Module
//    $router->get('/assessments','AssessorController@fetchAssessments');
    $router->post('/assessments','AssessorController@assessments');
    $router->post('/supplementaries','AssessorController@supplementaries');
    $router->get('/fillAssessmentReport/{id}','AssessorController@fillAssessmentReport');
    $router->get('/fillSupplementaryReport/{id}','AssessorController@fillSupplementaryReport');
    $router->get('/fillReInspectionReport/{id}','AssessorController@fillReInspectionReport');
    $router->post('/submitAssessment','AssessorController@submitAssessment');
    $router->post('/submitReInspection','AssessorController@submitReInspection');
    $router->post('/uploadDocuments','AssessorController@uploadDocuments');
    $router->post('/assessment-report','AssessorController@assessmentReport');
    $router->post('/supplementary-report','AssessorController@supplementaryReport');
    $router->post('/re-assessment-report','AssessorController@reInspectionReport');
    $router->get('/edit-assessment-report/{id}','AssessorController@editAssessmentReport');
    $router->get('/edit-supplementary-report/{id}','AssessorController@editSupplementaryReport');
    $router->post('/submit-edited-assessment','AssessorController@submitEditedAssessment');
    $router->post('/submit-edited-supplementary','AssessorController@submitEditedSupplementary');
    $router->post('/submitSupplementary','AssessorController@submitSupplementary');
    $router->post('/submit-edited-assessment','AssessorController@submitEditedAssessment');
    $router->post('/submitPriceChange','AssessorController@submitPriceChange');
    $router->get('/view-price-change/{id}', 'AssessorController@priceChange'); //Price Change
    $router->post('/price-change-report','AssessorController@priceChangeReport');
    $router->post('/deleteImage','AssessorController@deleteImage');
    $router->post('/resizeImages','AssessorController@resizeImages');
    $router->post('/submitPTVRequest','AssessorController@submitPTVRequest');

});

// Head Assessor Routes
$router->group(['prefix' => 'head-assessor'], function($router)
{
    //Motor  Head Assessor Module
    $router->get('/claims','HeadAssessorController@fetchClaims');
    $router->post('/claims','HeadAssessorController@claims');
    $router->post('/assignAssessor','HeadAssessorController@assignAssessor');
    $router->post('/reAssignAssessor','HeadAssessorController@reAssignAssessor');
    $router->post('/assessments','HeadAssessorController@assessments');
    $router->post('/supplementaries','HeadAssessorController@supplementaries');
    $router->post('/supplementary-report','HeadAssessorController@supplementaryReport');
    $router->post('/assessment-report','HeadAssessorController@assessmentReport');
    $router->post('/review-assessment','HeadAssessorController@reviewAssessment');
    $router->post('/review-supplementary','HeadAssessorController@reviewSupplementary');
    $router->post('/request-price-change','HeadAssessorController@requestPriceChange');
    $router->post('/request-supplementary-change','HeadAssessorController@requestSupplementaryChange');
    $router->post('/price-change-report','HeadAssessorController@priceChangeReport');
    $router->post('/review-price-change','HeadAssessorController@reviewPriceChange');
    $router->post('/request-assessment-change','HeadAssessorController@requestAssessmentChange');
});

// Manager Routes
$router->group(['prefix' => 'manager'], function($router)
{
    //Motor  Head Assessor Module
    $router->post('/claims','ManagerController@claims');
    $router->post('/assessments','ManagerController@assessments');
    $router->post('/supplementaries','ManagerController@supplementaries');
    $router->post('/supplementary-report','ManagerController@supplementaryReport');
    $router->post('/assessment-report','ManagerController@assessmentReport');
});

// Assessment Manager
$router->group(['prefix' => 'assessment-manager'], function($router)
{
    //Motor  assessment Manager Module
    $router->post('/claims','AssessmentManagerController@claims');
    $router->post('/assessments','AssessmentManagerController@assessments');
    $router->post('/assessment-report','AssessmentManagerController@assessmentReport');
    $router->post('/review-assessment','AssessmentManagerController@reviewAssessment');
    $router->post('/review-supplementary','AssessmentManagerController@reviewSupplementary');
    $router->post('/supplementaries','AssessmentManagerController@supplementaries');
    $router->post('/supplementary-report','AssessmentManagerController@supplementaryReport');
    $router->post('/request-assessment-change','AssessmentManagerController@requestAssessmentChange');
    $router->post('/request-supplementary-change','AssessmentManagerController@requestSupplementaryChange');
    $router->post('/price-change-report','AssessmentManagerController@priceChangeReport');
    $router->post('/assessment-manager-review-price-change','AssessmentManagerController@reviewPriceChange');
    $router->post('/request-assessment-change','AssessmentManagerController@requestAssessmentChange');
});
// Assistant head Assessor
$router->group(['prefix' => 'assistant-head-assessor'], function($router)
{
    $router->post('/claims','AssistantHeadAssessorController@claims');
    $router->post('/assessments','AssistantHeadAssessorController@assessments');
    $router->post('/assessment-report','AssistantHeadAssessorController@assessmentReport');
    $router->post('/request-assessment-change','AssistantHeadAssessorController@requestAssessmentChange');
    $router->post('/review-assessment','AssistantHeadAssessorController@reviewAssessment');
    $router->post('/supplementaries','AssistantHeadAssessorController@supplementaries');
    $router->post('/supplementary-report','AssistantHeadAssessorController@supplementaryReport');
    $router->post('/review-supplementary','AssistantHeadAssessorController@reviewSupplementary');
    $router->post('/request-price-change','AssistantHeadAssessorController@requestPriceChange');
    $router->post('/request-supplementary-change','AssistantHeadAssessorController@requestSupplementaryChange');
    $router->post('/price-change-report','AssistantHeadAssessorController@priceChangeReport');
    $router->post('/review-price-change','AssistantHeadAssessorController@reviewPriceChange');
});

// Admin Routes
$router->group(['prefix' => 'admin'], function($router)
{
    //Motor  assessment Assessor Module
    $router->post('/listUsers','AdminController@listUsers');
    $router->get('/assignRoleForm','AdminController@assignRoleForm');
    $router->get('/registerUserForm','AdminController@registerUserForm');
    $router->post('/assignRole','AdminController@assignRole');
    $router->post('/registerUser','AdminController@registerUser');
    $router->post('/listParts','AdminController@listParts');
    $router->post('/add-part','AdminController@addPart');
    $router->post('/permissions','AdminController@permissions');
    $router->post('/assignPermission','AdminController@assignPermission');
    $router->post('/add-permission','AdminController@addPermission');
    $router->post('/fetch-vendors','AdminController@fetchVendors');
    $router->post('/addVendorForm','AdminController@addVendorForm');
    $router->post('/addVendor','AdminController@addVendor');
    $router->get('/fetch-user-status','AdminController@getUser');
    $router->post('/set-status','AdminController@setStatus');
    $router->post('/getSubClassCode','AdminController@getSubClassCode');
});
$router->group(['prefix' => 'migration'], function($router)
{
    //Motor  assessment Assessor Module
    $router->post('/claims','AdminController@claims');
});
$router->group(['prefix' => 'common'], function($router)
{
    //Motor  assessment Assessor Module
    $router->post('/fetch-re-inspections','CommonController@fetchReInspections');
    $router->post('/fetch-claims-by-type','CommonController@fetchClaimsByType');
    $router->post('/sendNotification', 'CommonController@sendNotification');
    $router->post('/showActivityLog', 'CommonController@showActivityLog');
    $router->post('/fetchLogDetails', 'CommonController@fetchLogDetails');
    $router->post('/filter-logs', 'CommonController@filterLogs');
    $router->post('/flagged-assessments', 'CommonController@flaggedAssessments');
    $router->post('/flagged-supplementaries', 'CommonController@flaggedSupplementaries');
    $router->post('/getUsers', 'CommonController@getUsers');
    $router->post('/getClaimsWithoutClaimForm', 'CommonController@getClaimsWithoutClaimForm');
    $router->post('/sendClaimFormNotification', 'CommonController@sendClaimFormNotification');
    $router->post('/fetchDMSDocuments', 'CommonController@fetchDMSDocuments');
    $router->post('/fetchModelsByMake', 'CommonController@fetchModelsByMake');
    $router->post('/subrogation-report', 'CommonController@subrogationReport');
    $router->post('/submitSalvageRequest', 'CommonController@submitSalvageRequest');
    $router->post('/fetch-salvage-register', 'CommonController@fetchSalvageRegister');
    $router->post('/submitSaleSalvageRequest', 'CommonController@submitSaleSalvageRequest');
    $router->post('/salvage-release-letter', 'CommonController@salvageReleaseLetter');
    $router->post('/viewLPOReport', 'CommonController@viewLPOReport');
    $router->post('/fetch-theft-claims', 'CommonController@fetchTheftClaims');
    $router->post('/fetch-theft-assessments', 'CommonController@fetchTheftAssessments');
    $router->post('/PTVReport', 'CommonController@PTVReport');
    $router->post('/fetchEscalations', 'CommonController@fetchEscalations');
    $router->post('/reports/assessment-report', 'CommonController@sendAssessmentReport');
    $router->post('/reports/LPO-report', 'CommonController@sendLPOReport');
    $router->post('/reports/re-inspection-report', 'CommonController@sendReInspectionReport');
    $router->post('/changeTracker', 'CommonController@changeTracker');
    $router->post('/sendSubrogationReport', 'CommonController@sendSubrogationReport');


});

// migrate
Route::get('/users', 'MigrateController@users');
Route::post('/updatePdfType', 'MigrateController@updatePdfType');


//policy renewals

Route::get('/policy-renewals', 'RenewalsController@policyRenewal')->name("policy-renewals");


//Motor Renewals
Route::post('/fetchMotorRenewals', 'RenewalsController@fetchRenewals');
Route::post('/fetchPolicyDetail', 'RenewalsController@fetchPolicyDetail');
Route::post('/approveRenewalPolicy', 'RenewalsController@approveRenewalPolicy');
Route::post('/updatePolicyRenewal', 'RenewalsController@updatePolicyRenewal');
Route::post('/importData', 'RenewalsController@importData');

Route::post('/fetchSubRenewals', 'RenewalsController@fetchRenewals');

Route::get('/getselect', 'RenewalsController@getselect');


Route::post('/filterRenewals', 'RenewalsController@filterRenewals');
Route::post('/filterBy', 'RenewalsController@filterBy');
Route::get('/getMoreRenewals', 'RenewalsController@getMoreRenewals');
Route::get('/getMoreFilteredRenewals', 'RenewalsController@getMoreFilteredRenewals');
Route::get('/moreFilterBy', 'RenewalsController@moreFilterBy');
Route::get('/moreFilterByRange', 'RenewalsController@moreFilterByRange');



Route::post('/updatingRenewalPremium', 'RenewalsController@updatingRenewalPremium');
Route::post('/approveParentDetails', 'RenewalsController@approveParentDetails');
Route::post('/approveAllParents', 'RenewalsController@approveAllParents');


Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('user.logout');
Route::get('/dv', function(){

    $renewal = Renewal::find('556');
    $pdf = App::make('snappy.pdf.wrapper');
    $pdf = PDF::loadView('layouts.renewal_notice',compact('renewal'));
    return $pdf->stream();
    // return view('common.renewal_notice');
});

//Non-Motor Renewals
$router->group(['prefix' => 'non-motor'], function($router)
{
    $router->post('/display-premium-calculator-form', 'NonMotorRenewalController@displayPremiumCalculatorForm');
    $router->post('/fetchNonMotorRenewals', 'NonMotorRenewalController@fetchRenewals');
});

//Safaricom Home Fibre apis

$router->group(['prefix' => 'safaricom-home-fibre'], function($router)
{
    $router->get('/', 'homeFibre\SafaricomHomeFibreController@index')->name("safaricom-home-fibre");
    $router->post('/fetch-customers', 'homeFibre\SafaricomHomeFibreController@fetchCustomers');
    $router->post('/fetch-payments', 'homeFibre\SafaricomHomeFibreController@fetchPayments');
    $router->post('/fetch-customer-payments', 'homeFibre\SafaricomHomeFibreController@fetchCustomerPayments');
    $router->post('/fetch-policy-details', 'homeFibre\SafaricomHomeFibreController@fetchPolicyDetails');
    $router->post('/sendPolicyDocument', 'homeFibre\SafaricomHomeFibreController@sendPolicyDocument');
});

//Travel APIs
$router->group(['prefix' => 'travel'], function($router)
{
    $router->get('/', 'travel\TravelController@index')->name('travel-home');
    $router->post('/fetch-policies', 'travel\TravelController@fetchPolicies');
    $router->post('/fetch-policy-details', 'travel\TravelController@fetchPolicyDetails');
    $router->post('/update-policy', 'travel\TravelController@updatePolicy');
});

//NHIF APIs
$router->group(['prefix' => 'nhif'], function($router)
{
    $router->get('/', 'NHIF\NHIFController@index')->name('nhif-home');
    $router->post('/add-claim-form', 'NHIF\NHIFController@addClaimForm');
    $router->post('/save_nhif_claim', 'NHIF\NHIFController@saveNhifClaim');
    $router->post('/fetch-nhif-claims', 'NHIF\NHIFController@fetchClaims');
    $router->get('/claim-details', 'NHIF\NHIFController@claimDetails');
    $router->post('/uploadDocumentsForm', 'NHIF\NHIFController@uploadDocumentsForm');
    $router->post('/uploadDocuments', 'NHIF\NHIFController@uploadDocuments');
    $router->post('/fetch_proportions', 'NHIF\NHIFController@fetchProportions');
});

//Metropol integration
$router->group(['prefix' => 'metropol'], function($router)
{
    $router->post('/fetchCustomerData', 'CommonController@fetchCustomerData');
});

