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

    //Reports
    $router->post('/assessmentReport','AdjusterController@assessmentReport');
});

// Assessor Routes
$router->group(['prefix' => 'assessor'], function($router)
{
    //Motor  assessment User Module
//    $router->get('/assessments','AssessorController@fetchAssessments');
    $router->post('/assessments','AssessorController@assessments');
    $router->get('/fillAssessmentReport/{id}','AssessorController@fillAssessmentReport');
    $router->get('/fillReInspectionReport/{id}','AssessorController@fillReInspectionReport');
    $router->post('/submitAssessment','AssessorController@submitAssessment');
    $router->post('/uploadDocuments','AssessorController@uploadDocuments');
    $router->post('/assessment-report','AssessorController@assessmentReport');


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
    $router->post('/assessment-report','HeadAssessorController@assessmentReport');
    $router->post('/review-assessment','HeadAssessorController@reviewAssessment');
});

// Assessment Manager
$router->group(['prefix' => 'assessment-manager'], function($router)
{
    //Motor  assessment Manager Module
    $router->post('/assessments','AssessmentManagerController@assessments');
    $router->post('/assessment-report','AssessmentManagerController@assessmentReport');
    $router->post('/review-assessment','ApproverController@reviewAssessment');
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

