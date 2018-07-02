<?php

/*
|--------------------------------------------------------------------------
| Authentication routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'login'], function() {

    Route::get('/', ['as' => 'LoginPage', 'uses' => 'AuthController@getLoginPage']);

    Route::post('login/user', ['as' => 'LoginUser', 'uses' => 'AuthController@loginUser']);
});

Route::get('logout/user', ['as' => 'LogoutUser', 'uses' => 'AuthController@logoutUser']);

Route::auth();

/*
|--------------------------------------------------------------------------
| Administration routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'authentication'], function() {

    Route::get('dashboard', ['as' => 'DashboardPage', 'middleware' => 'dashboard', 'uses' => 'DashboardController@getDashboard']);

    /*
    |--------------------------------------------------------------------------
    | Manipulations routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'manipulations', 'middleware' => 'dashboard'], function() {

        Route::post('dashboardData', ['uses' => 'ManipulationsController@getDashboardData']);

        Route::post('doManipulation', ['uses' => 'ManipulationsController@doManipulation']);

        Route::post('removeAdditionalSite', ['uses' => 'ManipulationsController@removeAdditionalSite']);
    });

    /*
    |--------------------------------------------------------------------------
    | Overview routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'overview', 'middleware' => 'overview'], function() {

        Route::get('resources/{type}', ['as' => 'ResourcesOverview', 'uses' => 'DashboardController@resourcesOverview']);

        Route::get('pdf/{type}', ['uses' => 'DashboardController@resourcesPdf']);
    });

    Route::group(['middleware' => 'sitesAndParking'], function() {

        /*
        |--------------------------------------------------------------------------
        | Sites routes
        |--------------------------------------------------------------------------
        */

        Route::group(['prefix' => 'sites'], function() {

            Route::get('list', ['as' => 'GetSites', 'uses' => 'SitesController@getSites']);

            Route::get('add', ['as' => 'AddSite', 'uses' => 'SitesController@addSite']);

            //ajax route
            Route::post('insert', ['as' => 'InsertSite', 'uses' => 'SitesController@insertSite']);

            Route::get('edit/{id}', ['as' => 'EditSite', 'uses' => 'SitesController@editSite']);

            //ajax route
            Route::post('update', ['as' => 'UpdateSite', 'uses' => 'SitesController@updateSite']);
        });

        /*
        |--------------------------------------------------------------------------
        | Parking routes
        |--------------------------------------------------------------------------
        */

        Route::group(['prefix' => 'parking'], function() {

            Route::get('list', ['as' => 'GetParking', 'uses' => 'ParkingController@getParking']);

            Route::get('add', ['as' => 'AddParking', 'uses' => 'ParkingController@addParking']);

            //ajax route
            Route::post('insert', ['as' => 'InsertParking', 'uses' => 'ParkingController@insertParking']);

            Route::get('edit/{id}', ['as' => 'EditParking', 'uses' => 'ParkingController@editParking']);

            //ajax route
            Route::post('update', ['as' => 'UpdateParking', 'uses' => 'ParkingController@updateParking']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Resources routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'resources', 'middleware' => 'resources'], function() {

        Route::group(['prefix' => 'machines'], function() {

            Route::get('list', ['as' => 'GetMachines', 'uses' => 'MachinesController@getMachines']);

            Route::get('add', ['as' => 'AddMachine', 'uses' => 'MachinesController@addMachine']);

            //ajax route
            Route::post('insert', ['uses' => 'MachinesController@insertMachine']);

            Route::get('edit/{id}', ['as' => 'EditMachine', 'uses' => 'MachinesController@editMachine']);

            //ajax route
            Route::post('update', ['uses' => 'MachinesController@updateMachine']);
        });

        Route::group(['prefix' => 'tools'], function() {

            Route::get('list', ['as' => 'GetTools', 'uses' => 'ToolsController@getTools']);

            Route::get('add', ['as' => 'AddTool', 'uses' => 'ToolsController@addTool']);

            //ajax route
            Route::post('insert', ['as' => 'InsertTool', 'uses' => 'ToolsController@insertTool']);

            Route::get('edit/{id}', ['as' => 'EditTool', 'uses' => 'ToolsController@editTool']);

            //ajax route
            Route::post('update', ['as' => 'UpdateTool', 'uses' => 'ToolsController@updateTool']);
        });

        Route::group(['prefix' => 'equipment'], function() {

            Route::get('list', ['as' => 'GetEquipment', 'uses' => 'EquipmentController@getEquipment']);

            Route::get('add', ['as' => 'AddEquipment', 'uses' => 'EquipmentController@addEquipment']);

            //ajax route
            Route::post('insert', ['as' => 'InsertEquipment', 'uses' => 'EquipmentController@insertEquipment']);

            Route::get('edit/{id}', ['as' => 'EditEquipment', 'uses' => 'EquipmentController@editEquipment']);

            //ajax route
            Route::post('update', ['as' => 'UpdateEquipment', 'uses' => 'EquipmentController@updateEquipment']);
        });

        Route::group(['prefix' => 'vehicles'], function() {

            Route::get('list', ['as' => 'GetVehicles', 'uses' => 'VehiclesController@getVehicles']);

            Route::get('add', ['as' => 'AddVehicle', 'uses' => 'VehiclesController@addVehicle']);

            //ajax route
            Route::post('insert', ['as' => 'InsertVehicle', 'uses' => 'VehiclesController@insertVehicle']);

            Route::get('edit/{id}', ['as' => 'EditVehicle', 'uses' => 'VehiclesController@editVehicle']);

            //ajax route
            Route::post('update', ['as' => 'UpdateVehicle', 'uses' => 'VehiclesController@updateVehicle']);
        });

        Route::group(['prefix' => 'employees'], function() {

            Route::get('list', ['as' => 'GetEmployees', 'uses' => 'EmployeesController@getEmployees']);

            Route::get('add', ['as' => 'AddEmployee', 'uses' => 'EmployeesController@addEmployee']);

            //ajax route
            Route::post('insert', ['as' => 'InsertEmployee', 'uses' => 'EmployeesController@insertEmployee']);

            Route::get('edit/{id}', ['as' => 'EditEmployee', 'uses' => 'EmployeesController@editEmployee']);

            //ajax route
            Route::post('update', ['as' => 'UpdateEmployee', 'uses' => 'EmployeesController@updateEmployee']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Statistic routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => 'statistic'], function() {

        Route::get('statistic', ['as' => 'StatisticPage', 'uses' => 'StatisticController@getStatistic']);

        Route::post('filterStatistic', ['uses' => 'StatisticController@filterStatistic']);
    });

    /*
    |--------------------------------------------------------------------------
    | Daily work activities routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'DWA'], function() {

        Route::get('list', ['as' => 'GetDWA', 'uses' => 'DWAController@getDWA']);

        Route::get('view/{id}', ['as' => 'ViewDWA', 'uses' => 'DWAController@getDWADetails']);

        Route::get('view/{id}', ['as' => 'ViewDWA', 'uses' => 'DWAController@getDWADetails']);

        Route::group(['middleware' => 'entryDWA'], function() {

            Route::get('entry', ['as' => 'DWAEntry', 'uses' => 'DWAController@newEntry']);

            Route::get('confirm/{id}', ['as' => 'ConfirmDWA', 'middleware' => 'confirmDWA', 'uses' => 'DWAController@confirmDWA']);

            //ajax route
            Route::post('initialData', ['uses' => 'DWAController@getInitialData']);

            //ajax route
            Route::post('checkMachineDWA', ['uses' => 'DWAController@checkMachineDWA']);

            //ajax route
            Route::post('createDWA', ['uses' => 'DWAController@createDWA']);

            //ajax route
            Route::post('saveActivity', ['uses' => 'DWAController@saveActivity']);

            //ajax route
            Route::post('saveFuel', ['uses' => 'DWAController@saveFuel']);

            //ajax route
            Route::post('saveFluid', ['uses' => 'DWAController@saveFluid']);

            //ajax route
            Route::post('saveFilter', ['uses' => 'DWAController@saveFilter']);

            //ajax route
            Route::post('saveNote', ['uses' => 'DWAController@saveNote']);

            //ajax route
            Route::post('getActivities', ['uses' => 'DWAController@getActivities']);

            //ajax route
            Route::post('getFuel', ['uses' => 'DWAController@getFuel']);

            //ajax route
            Route::post('getFluids', ['uses' => 'DWAController@getFluids']);

            //ajax route
            Route::post('getFilters', ['uses' => 'DWAController@getFilters']);

            //ajax route
            Route::post('getNotes', ['uses' => 'DWAController@getNotes']);

            Route::group(['middleware' => 'editDWA'], function() {

                Route::get('edit/{id}', ['as' => 'EditDWA', 'uses' => 'DWAController@editDWA']);

                //ajax route
                Route::post('deleteActivity', ['uses' => 'DWAController@deleteActivity']);

                //ajax route
                Route::post('deleteFuel', ['uses' => 'DWAController@deleteFuel']);

                //ajax route
                Route::post('deleteFluid', ['uses' => 'DWAController@deleteFluid']);

                //ajax route
                Route::post('deleteFilter', ['uses' => 'DWAController@deleteFilter']);
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Problem reports routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'problemReports'], function() {

        Route::get('list', ['as' => 'GetProblemReports', 'uses' => 'ProblemReportsController@getReports']);

        //ajax route
        Route::get('counter', ['uses' => 'ProblemReportsController@getCounter']);

        //ajax route
        Route::post('seen', ['middleware' => 'reportSeen', 'uses' => 'ProblemReportsController@seenReport']);
    });

    /*
    |--------------------------------------------------------------------------
    | AJAX routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'manufacturers'], function() {

        Route::get('list', ['uses' => 'ManufacturersController@getManufacturersSelect']);

        Route::post('insert', ['uses' => 'ManufacturersController@insertManufacturer']);
    });

    Route::group(['prefix' => 'generalTypes'], function() {

        Route::post('list', ['uses' => 'GeneralTypesController@getGeneralTypesSelect']);

        Route::post('insert', ['uses' => 'GeneralTypesController@insertGeneralType']);
    });

    Route::group(['prefix' => 'investors'], function() {

        Route::get('list', ['uses' => 'InvestorsController@getInvestorsSelect']);

        Route::post('insert', ['uses' => 'InvestorsController@insertInvestor']);
    });
});
