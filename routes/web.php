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




Route::group(['middleware' => 'web'], function(){
    Route::get('/', function () {
        return view('index');
    });
    Route::get('/category/{name}', 'Category@category');
    Route::get('/login', 'Login@index');
    Route::post('/register/user',  ['as' => 'register/user', 'uses' => 'Authenticator@register']);
    Route::post('/login/user',  ['as' => 'login/user', 'uses' => 'Authenticator@login']);
    Route::get('/logout', 'Authenticator@logout');
    Route::get('/simulation/{name}', 'Simulation@simulation');
    Route::get('/theory/{name}', 'Theory@theory');
    Route::post('/theory/updateGlance', 'Theory@updateGlance');
    Route::post('/simulation/updateGlance', 'Simulation@updateGlance');
});

Route::get('/questionnaire/{name}', 'Questionnaire@showQuestionnaire') -> middleware('checkIfLoggedIn');
Route::get('/profile/', 'User@profile') -> middleware('checkIfIsStudent');
Route::post('/questionnaire/{name}/evaluate', 'Questionnaire@evaluate') -> middleware('checkIfIsStudent');
Route::post('/questionnaire/answers', 'Questionnaire@getAnswers') -> middleware('checkIfIsStudent');


Route::group(['prefix' => 'admin', 'middleware' => 'checkIfIsAdmin'], function(){
    Route::get('/notification/{id}', 'Admin@viewNotification');
    Route::post('/notification/resolve', 'Admin@resolveNotification');

});

Route::group(['prefix' => 'creator',  'middleware' => 'checkIfIsCreator'], function(){
    Route::get('/categories', 'Creator@categories');
    Route::get('/topics', 'Creator@topics');
    Route::get('/statistics', 'Creator@statistics');
    Route::get('/categories/list', 'Creator@categoryList');
    Route::get('/topics/list', 'Creator@topicList');
    Route::get('/topic/{id}', 'Creator@displayTopic');
    Route::get('/topic/{id}/theory', 'Creator@topicTheoryManager');
    Route::get('/topic/{id}/simulation', 'Creator@topicSimulationManager');
    Route::get('/topic/{id}/questionnaire', 'Creator@topicQuestionnaireManager');
    Route::get('/categories/list/json', 'Creator@categoryListJSON');
    Route::get('/users/ranking', 'Creator@usersRanking');
    Route::get('/statistics/user/{name}', 'Creator@userStatistics');
    Route::get('/statistics/group/{name}', 'Creator@groupStatistics');
    Route::get('/statistics/school/{name}', 'Creator@schoolStatistics');
    Route::get('/groups/ranking', 'Creator@groupsRanking');
    Route::get('/schools/ranking', 'Creator@schoolsRanking');
    Route::get('/user/ranking/{name}/theory', 'Creator@getUserTheoryStatistics');
    Route::get('/group/statistics/{name}/theory', 'Creator@getGroupTheoryStatistics');
    Route::get('/group/statistics/{name}/questionnaire', 'Creator@getGroupQuestionnaireStatistics');
    Route::get('/group/statistics/{name}/simulation', 'Creator@getGroupSimulationStatistics');
    Route::get('/school/statistics/{name}/theory', 'Creator@getSchoolTheoryStatistics');
    Route::get('/school/statistics/{name}/questionnaire', 'Creator@getSchoolQuestionnaireStatistics');
    Route::get('/school/statistics/{name}/simulation', 'Creator@getSchoolSimulationStatistics');
    Route::get('/user/ranking/{name}/questionnaire', 'Creator@getUserQuestionnaireStatistics');
    Route::get('/user/ranking/{name}/simulation', 'Creator@getUserSimulationStatistics');
    Route::post('/categories/register', 'Creator@registerCategory');
    Route::post('/topics/register', 'Creator@registerTopic');
    Route::post('/categories/delete', 'Creator@deleteCategory');
    Route::post('/topics/delete', 'Creator@deleteTopic');
    Route::post('/categories/edit', 'Creator@editCategory');
    Route::post('/topics/edit', 'Creator@editTopic');
    Route::post('/topic/theory/register/file', 'Creator@registerTheoryFile');
    Route::post('/topic/theory/edit/file','Creator@editTopicTheoryFile');
    Route::post('/topic/questionnaire/register/file', 'Creator@registerQuestionnaireFile');
    Route::post('/topic/questionnaire/edit/file', 'Creator@editTopicQuestionnaireFile');
    Route::post('/topic/simulation/register/file', 'Creator@registerSimulationFile');
    Route::post('topic/simulation/edit/file', 'Creator@editTopicSimulationFile');
    Route::post('/topic/theory/register/manually', 'Creator@registerTheoryManually');
    Route::post('/topic/questionnaire/register/manually', 'Creator@registerQuestionnaireManually');
    Route::post('/topic/theory/register/manually/save', 'Creator@saveTheoryManually');
    Route::post('/topic/questionnaire/register/manually/save', 'Creator@saveQuestionnaireManually');
    Route::post('/categories/submitReview', 'Creator@submitCategoryReview');
    Route::post('/topics/submitReview', 'Creator@submitTopicReview');
    Route::post('/topic/theory/submitReview', 'Creator@submitTopicTheoryReview');
    Route::post('/topic/simulation/submitReview', 'Creator@submitTopicSimulationReview');
    Route::post('/topic/questionnaire/submitReview','Creator@submitTopicQuestionnaireReview');
});