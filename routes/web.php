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
    $router->get('/claims','AdjusterController@fetchClaims');
    $router->get('/uploadDocumentsForm/{claimID}','AdjusterController@uploadDocumentsForm');
    $router->get('/assignAssessor','AdjusterController@assignAssessor');
    $router->post('/search','AdjusterController@searchClaim');
    $router->get('/assessments','AdjusterController@fetchAssessments');
    $router->get('/editClaimForm/{id}','AdjusterController@editClaimForm');
    $router->get('/fetchUploadedClaims','AdjusterController@fetchUploadedClaims');
    $router->get('/assignedClaims','AdjusterController@assignedClaims');
    $router->get('/fetchAssignedAssessments','AdjusterController@fetchAssignedAssessments');
    $router->get('/assessment-details/{assessmentID}','AdjusterController@assessmentDetails');
    $router->post('/updateClaim','AdjusterController@updateClaim');
    $router->post('/filterPremia11ClaimsByDate','AdjusterController@filterPremia11ClaimsByDate');
    $router->post('/claimExceptionDetail','AdjusterController@claimExceptionDetail');
});

// Assessor Routes
$router->group(['prefix' => 'assessor'], function($router)
{
    //Motor  assessment User Module
    $router->get('/assessments','AssessorController@fetchAssessments');
    $router->get('/fillAssessmentReport/{id}','AssessorController@fillAssessmentReport');


});

// Head Assessor Routes
$router->group(['prefix' => 'head-assessor'], function($router)
{
    //Motor  assessment Assessor Module
    $router->get('/claims','HeadAssessorController@fetchClaims');
    $router->post('/assignAssessor','HeadAssessorController@assignAssessor');
    $router->post('/reAssignAssessor','HeadAssessorController@reAssignAssessor');
    $router->get('/assessments','HeadAssessorController@fetchAssessments');
});

// Admin Routes
$router->group(['prefix' => 'admin'], function($router)
{
    //Motor  assessment Assessor Module
    $router->get('/assignRoleForm','AdminController@assignRoleForm');
    $router->post('/assignRole','AdminController@assignRole');
});

