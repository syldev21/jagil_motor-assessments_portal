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
    return view('authentication.user-login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/markNotification', 'HomeController@markNotification')->name('home');

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

    //Reports
    $router->post('/assessmentReport','AdjusterController@assessmentReport');
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

// Assessment Manager
$router->group(['prefix' => 'assessment-manager'], function($router)
{
    //Motor  assessment Manager Module
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
});
// Assistant head Assessor
$router->group(['prefix' => 'assistant-head-assessor'], function($router)
{
    $router->post('/assessments','AssistantHeadAssessorController@assessments');
    $router->post('/assessment-report','AssistantHeadAssessorController@assessmentReport');
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
});

// migrate
Route::get('/users', 'MigrateController@users');

