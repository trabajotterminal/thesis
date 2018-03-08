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
});

Route::get('/questionnaire/{name}', 'Questionnaire@showQuestionnaire') -> middleware('checkIfLoggedIn');
Route::get('/profile/', 'User@profile') -> middleware('checkIfIsStudent');
Route::post('/questionnaire/{name}/evaluate', 'Questionnaire@evaluate') -> middleware('checkIfIsStudent');
Route::post('/questionnaire/answers', 'Questionnaire@getAnswers') -> middleware('checkIfIsStudent');

Route::group(['prefix' => 'admin',  'middleware' => 'checkIfIsAdmin'], function(){
    Route::get('/categories', 'Admin@categories');
    Route::get('/topics', 'Admin@topics');
    Route::get('/statistics', 'Admin@statistics');
    Route::get('/categories/list', 'Admin@categoryList');
    Route::get('/topics/list', 'Admin@topicList');
    Route::get('/topic/{id}', 'Admin@displayTopic');
    Route::get('/topic/{id}/theory', 'Admin@topicTheoryManager');
    Route::get('/topic/{id}/simulation', 'Admin@topicSimulationManager');
    Route::get('/topic/{id}/questionnaire', 'Admin@topicQuestionnaireManager');
    Route::get('/categories/list/json', 'Admin@categoryListJSON');
    Route::get('/users/ranking', 'Admin@usersRanking');
    Route::get('/statistics/user/{name}', 'Admin@userRanking');
    Route::get('/groups/ranking', 'Admin@groupsRanking');
    Route::get('/schools/ranking', 'Admin@schoolsRanking');
    Route::get('/user/ranking/{name}/theory', 'Admin@getUserTheoryStatistics');
    Route::get('/user/ranking/{name}/questionnaire', 'Admin@getUserQuestionnaireStatistics');
    Route::post('/categories/register', 'Admin@registerCategory');
    Route::post('/topics/register', 'Admin@registerTopic');
    Route::post('/categories/delete', 'Admin@deleteCategory');
    Route::post('/topics/delete', 'Admin@deleteTopic');
    Route::post('/categories/edit', 'Admin@editCategory');
    Route::post('/topics/edit', 'Admin@editTopic');
    Route::post('/topic/theory/register/file', 'Admin@registerTheoryFile');
    Route::post('/topic/questionnaire/register/file', 'Admin@registerQuestionnaireFile');
    Route::post('/topic/theory/register/manually', 'Admin@registerTheoryManually');
    Route::post('/topic/questionnaire/register/manually', 'Admin@registerQuestionnaireManually');
    Route::post('/topic/theory/register/manually/save', 'Admin@saveTheoryManually');
    Route::post('/topic/questionnaire/register/manually/save', 'Admin@saveQuestionnaireManually');
});