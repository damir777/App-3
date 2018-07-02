<?php

Route::group(['middleware' => 'APIAuth'], function() {

    Route::post('login', ['uses' => 'APIController@loginUser']);

    Route::group(['middleware' => 'auth:api'], function() {

        Route::post('dashboardData', ['middleware' => 'dashboard', 'uses' => 'ManipulationsController@getDashboardData']);

        Route::group(['middleware' => 'entryDWA'], function() {

            Route::post('initialData', ['uses' => 'DWAController@getInitialData']);

            Route::post('checkMachineDWA', ['uses' => 'DWAController@checkMachineDWA']);

            Route::post('createDWA', ['uses' => 'DWAController@createDWA']);

            Route::post('saveActivity', ['uses' => 'DWAController@saveActivity']);

            Route::post('saveFuel', ['uses' => 'DWAController@saveFuel']);

            Route::post('saveFluid', ['uses' => 'DWAController@saveFluid']);

            Route::post('saveFilter', ['uses' => 'DWAController@saveFilter']);

            Route::post('saveNote', ['uses' => 'DWAController@saveNote']);

            Route::post('getActivities', ['uses' => 'DWAController@getActivities']);

            Route::post('getNotes', ['uses' => 'DWAController@getNotes']);
        });

        Route::post('saveProblem', ['uses' => 'ProblemReportsController@saveProblem']);
    });
});
