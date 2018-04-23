<?php

Route::group(['middleware' => 'web'], function(){
    Route::get('/', 'Index@index');
    Route::get('/search', 'Index@searchEngine');
    Route::get('/category/{name}', 'Category@category');
    Route::get('/login', 'Login@index');
    Route::post('/register/user',  ['as' => 'register/user', 'uses' => 'Authenticator@register']);
    Route::post('/login/user',  ['as' => 'login/user', 'uses' => 'Authenticator@login']);
    Route::get('/logout', 'Authenticator@logout');
    Route::get('/simulation/{name}', 'Simulation@simulation');
    Route::get('/error', function () {
        return view('error');
    });
    Route::get('/theory/{name}', 'Theory@theory');
    Route::post('/theory/updateGlance', 'Theory@updateGlance');
    Route::post('/simulation/updateGlance', 'Simulation@updateGlance');
    Route::get('/startProduction', function(){
        Artisan::call('migrate:refresh');
        Artisan::call('storage:link');
        Artisan::call('db:seed');
    });
});

Route::get('/questionnaire/{name}', 'Questionnaire@showQuestionnaire') -> middleware('checkIfLoggedIn');
Route::get('/profile/', 'User@profile') -> middleware('checkIfIsStudent');
Route::get('/user/school/clubs/json', 'User@getGroupsBySchool') -> middleware('checkIfIsStudent');
Route::get('/user/getInfo/json', 'User@getInfoJson') -> middleware('checkIfIsStudent');
Route::get('/user/schools/json', 'User@getSchoolsJson') -> middleware('checkIfIsStudent');
Route::post('/questionnaire/{name}/evaluate', 'Questionnaire@evaluate') -> middleware('checkIfIsStudent');
Route::post('/questionnaire/answers', 'Questionnaire@getAnswers') -> middleware('checkIfIsStudent');
Route::post('/user/updateInfo', 'User@updateInfo') -> middleware('checkIfIsStudent');

Route::group(['prefix' => 'admin', 'middleware' => 'checkIfIsAdmin'], function(){
    Route::get('/notification/{id}', 'Admin@viewNotification');
    Route::get('/notification/theory/{id}', 'Admin@viewTheoryNotification');
    Route::get('/notification/questionnaire/{id}', 'Admin@viewQuestionnaireNotification');
    Route::get('/notification/simulation/{id}', 'Admin@viewSimulationNotification');
    Route::post('/notification/resolve', 'Admin@resolveNotification');
    Route::post('/notification/theory/resolve', 'Admin@resolveTheoryNotification');
    Route::post('/notification/simulation/resolve', 'Admin@resolveSimulationNotification');
    Route::post('/notification/questionnaire/resolve', 'Admin@resolveQuestionnaireNotification');
    Route::get('/statistics', 'Admin@statistics');
    Route::get('/users/ranking', 'Admin@usersRanking');
    Route::get('/groups/ranking', 'Admin@groupsRanking');
    Route::get('/schools/ranking', 'Admin@schoolsRanking');
    Route::get('/statistics/user/{name}', 'Admin@userStatistics');
    Route::get('/statistics/group/{name}', 'Admin@groupStatistics');
    Route::get('/statistics/school/{name}', 'Admin@schoolStatistics');
    Route::get('/user/ranking/{name}/theory', 'Admin@getUserTheoryStatistics');
    Route::get('/group/statistics/{name}/theory', 'Admin@getGroupTheoryStatistics');
    Route::get('/group/statistics/{name}/questionnaire', 'Admin@getGroupQuestionnaireStatistics');
    Route::get('/group/statistics/{name}/simulation', 'Admin@getGroupSimulationStatistics');
    Route::get('/school/statistics/{name}/theory', 'Admin@getSchoolTheoryStatistics');
    Route::get('/school/statistics/{name}/questionnaire', 'Admin@getSchoolQuestionnaireStatistics');
    Route::get('/school/statistics/{name}/simulation', 'Admin@getSchoolSimulationStatistics');
    Route::get('/user/ranking/{name}/questionnaire', 'Admin@getUserQuestionnaireStatistics');
    Route::get('/user/ranking/{name}/simulation', 'Admin@getUserSimulationStatistics');

});

Route::group(['prefix' => 'creator',  'middleware' => 'checkIfIsCreator'], function(){
    Route::get('/notification/{id}', 'Creator@viewNotification');
    Route::get('/categories', 'Creator@categories');
    Route::get('/topics', 'Creator@topics');
    Route::get('/categories/list', 'Creator@categoryList');
    Route::get('/topics/list', 'Creator@topicList');
    Route::get('/topic/{id}', 'Creator@displayTopic');
    Route::get('/topic/{id}/theory', 'Creator@topicTheoryManager');
    Route::get('/topic/{id}/simulation', 'Creator@topicSimulationManager');
    Route::get('/topic/{id}/questionnaire', 'Creator@topicQuestionnaireManager');
    Route::get('/categories/list/json', 'Creator@categoryListJSON');
    Route::post('/categories/register', 'Creator@registerCategory');
    Route::post('/topics/register', 'Creator@registerTopic');
    Route::post('/categories/delete', 'Creator@deleteCategory');
    Route::post('/topics/delete', 'Creator@deleteTopic');
    Route::post('/categories/edit', 'Creator@editCategory');
    Route::post('/topics/edit', 'Creator@editTopic');
    Route::post('/topic/theory/download/', 'Creator@downloadTheoryFile');
    Route::post('/topic/questionnaire/download/', 'Creator@downloadQuestionnaireFile');
    Route::post('/topic/theory/register/file', 'Creator@registerTheoryFile');
    Route::post('/topic/theory/edit/file','Creator@editTopicTheoryFile');
    Route::post('/topic/theory/edit/manually','Creator@editTopicTheoryManually');
    Route::post('/topic/theory/update/manually','Creator@updateTopicTheoryManually');
    Route::post('/topic/questionnaire/register/file', 'Creator@registerQuestionnaireFile');
    Route::post('/topic/questionnaire/edit/file', 'Creator@editTopicQuestionnaireFile');
    Route::post('/topic/questionnaire/edit/manually','Creator@editTopicQuestionnaireManually');
    Route::post('/topic/questionnaire/update/manually/', 'Creator@updateTopicQuestionnaireManually');
    Route::post('/topic/simulation/register/file', 'Creator@registerSimulationFile');
    Route::post('/topic/simulation/edit/file', 'Creator@editTopicSimulationFile');
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