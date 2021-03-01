<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'Auth\LoginController@ShowLoginForm')->name('face.page');
Auth::routes();

Route::middleware('auth')->group(function ()
    {
        Route::get('/home', 'Auth\UserController@home')->name('credential_access.home-page');

        Route::get('/ganti-password/{user_id}', 'Master\MasterApp\UserController@showChangePasswordForm')->name('users.change-password');

        Route::post('/proses-ganti-password', 'Master\MasterApp\UserController@processChangePassword')->name('users.process-change-password');
        Route::get('/user-guide', 'Auth\UserCredentialController@userGuide')->name('credential_access.user-guide');
        Route::get('/halaman-help', 'Auth\UserCredentialController@halamanHelp')->name('credential_access.help-page');
    }
);

Route::group(['prefix' => 'master-apps','middleware'=>['auth','credential.check']], function()
{
    Route::get('/', 'Masterapp\HomeController@index')->name('master_app.show_home');

    Route::group(['prefix' => 'manage-user'], function() {
        Route::get('','Auth\UserController@index')->name('master_app.manage_user');
        Route::get('/get-data','Auth\UserController@getDataUser');
        Route::post('/edit-user', 'Auth\UserController@editDataUser');
        Route::post('/update-user','Auth\UserController@updateDataUser');
        Route::post('/change-status-user', 'Auth\UserController@changeStatusUser');
        Route::post('/verify-user', 'Auth\UserController@verifyUser');
		Route::post('/reset-password','Auth\UserController@resetPassword');
    });

	Route::group(['prefix' => 'manage-application'], function () {
        Route::get('', 'Masterapp\ApplicationController@index')->name('master_app.manage_applications');
        Route::get('/get-data','Masterapp\ApplicationController@getDataApplication');
		Route::post('/change-status-application', 'Masterapp\ApplicationController@changeStatusApplication');
		Route::post('/edit-application', 'Masterapp\ApplicationController@editDataApplication');
		Route::post('/update-application', 'Masterapp\ApplicationController@updateDataApplication');
		Route::post('/add-new-application-modal', 'Masterapp\ApplicationController@addNewApplicationModal');
		Route::post('/add-new-application', 'Masterapp\ApplicationController@addNewApplication');
	});

	Route::group(['prefix' => 'manage-menu'], function ()
	{
		Route::get('', 'Masterapp\MenuController@index')->name('master_app.manage_menu');
        Route::get('/get-data','Masterapp\MenuController@getDataMenu');

        Route::post('/add-new-menu-modal', 'Masterapp\MenuController@addNewMenuModal');
		Route::post('/change-application', 'Masterapp\MenuController@changeApplication');
		Route::post('/add-new-menu', 'Masterapp\MenuController@addNewMenu');

		Route::post('/edit-menu', 'Masterapp\MenuController@editDataMenu');
		Route::post('/update-menu', 'Masterapp\MenuController@updateDataMenu');

        Route::post('/change-status-menu', 'Masterapp\MenuController@changeStatusMenu');

    });


    Route::group(['prefix' => 'manage-user-permission'], function ()
    {
        Route::get('/', 'Masterapp\UserPermissionController@index')->name('master_app.user_permissions');
        Route::get('/get-data', 'Masterapp\UserPermissionController@getDataUser');
        Route::get('/get-data-application-permission','Masterapp\UserPermissionController@getApplicationPermission');

        Route::post('/change-application-permission', 'Masterapp\UserPermissionController@changeApplicationPermission');
        Route::post('/menu-permission-modal','Masterapp\UserPermissionController@menuPermissionModal');

        Route::get('/get-menu-permission','Masterapp\UserPermissionController@getMenuPermission');

        Route::post('/change-view-menu-permission','Masterapp\UserPermissionController@changeViewMenuPermission');
        Route::post('/change-create-menu-permission','Masterapp\UserPermissionController@changeCreateMenuPermission');
        Route::post('/change-edit-menu-permission','Masterapp\UserPermissionController@changeEditMenuPermission');
        Route::post('/change-delete-menu-permission','Masterapp\UserPermissionController@changeDeleteMenuPermission');


    });



    /* Master Data Rollie Route */

    Route::group(['prefix' => 'manage-filling-machine'], function ()
    {
        Route::get('', 'Masterapp\FillingMachineController@index')->name('master_app.manage_filling_machine');
        Route::get('/get-data', 'Masterapp\FillingMachineController@getData');
        Route::post('/change-status-filling-machine', 'Masterapp\FillingMachineController@changeFillingMachineStatus');

        Route::post('/add-new-filling-machine-modal', 'Masterapp\FillingMachineController@addFillingMachineModal');
        Route::post('/add-new-filling-machine', 'Masterapp\FillingMachineController@addFillingMachine');

        Route::post('/edit-filling-machine', 'Masterapp\FillingMachineController@editFillingMachine');
        Route::post('/update-filling-machine', 'Masterapp\FillingMachineController@updateFillingMachine');

		/* Route::get('', 'MasterAppController@manageProduct')->name('master_app.master_data.manage_products');
		Route::post('', 'Master\Rollie\ProductController@manageProduct');
		Route::get('/edit-produk/{product_id}', 'Master\Rollie\ProductController@editProduct'); */
    });
    Route::group(['prefix' => 'manage-filling-machine-group'], function ()
	{
		Route::get('', 'Masterapp\FillingMachineGroupController@index')->name('master_app.manage_filling_machine_group');
        Route::get('get-data', 'Masterapp\FillingMachineGroupController@getData');
        Route::get('get-filling-machine-detail', 'Masterapp\FillingMachineGroupController@getDataDetail');

        Route::post('/change-status-filling-machine-group', 'Masterapp\FillingMachineGroupController@changeFillingMachineStatus');
        Route::post('/change-status-filling-machine-group-detail', 'Masterapp\FillingMachineGroupController@changeFillingMachineDetailStatus');

        Route::post('/edit-filling-machine-group-head', 'Masterapp\FillingMachineGroupController@editFillingMachineGroupHead');
        Route::post('/update-filling-machine-group-head', 'Masterapp\FillingMachineGroupController@updateFillingMachineGroupHead');

        Route::post('/add-filling-machine-group-detail-modal', 'Masterapp\FillingMachineGroupController@addFillingMachineGroupDetailModal');
        Route::post('/add-filling-machine-group-detail', 'Masterapp\FillingMachineGroupController@addFillingMachineGroupDetail');

        Route::post('/add-new-filling-machine-group-modal', 'Masterapp\FillingMachineGroupController@addNewFillingMachineGroupModal');
        Route::post('/add-new-filling-machine-group', 'Masterapp\FillingMachineGroupController@addNewFillingMachineGroup');


	});
    Route::group(['prefix' => 'manage-product'], function ()
    {
        Route::get('', 'Masterapp\ProductController@index')->name('master_app.manage_product');
        Route::get('/get-data', 'Masterapp\ProductController@getData');
        Route::post('/add-new-product-modal', 'Masterapp\ProductController@addNewProductModal');
        Route::post('/add-new-product', 'Masterapp\ProductController@addNewProduct');

        Route::post('/edit-product-modal', 'Masterapp\ProductController@editProductModal');
        Route::post('/edit-product', 'Masterapp\ProductController@editProduct');

    });

    Route::get('perhitungan-penggunaan', 'MasterAppController@perhitunganPernggunaan')->name('master_app.manage_meteran');

	Route::group(['prefix' => 'kelola-event-mesin'], function ()
	{
		Route::get('','MasterAppController@manageEventFillingMachine')->name('master_app.master_data.manage_filling_sample_codes');
		Route::get('ubah-event/{filling_machine_id}/{product_type_id}','MasterAppController@showFormEventSampel');
		Route::post('ubah-event','Master\Rollie\FillingSampelCodeController@updateDataEvent');
	});

	Route::group(['prefix' => 'kelola-jenis-ppq'], function () {
		Route::get('', 'MasterAppController@manageJenisPpq')->name('master_app.master_data.manage_jenis_ppqs');
		Route::get('get-jenis-ppq/{jenis_ppq_id}', 'Master\Rollie\JenisPpqController@getJenisPpq');
		Route::post('', 'Master\Rollie\JenisPpqController@manageJenisPpq');
	});
	Route::group(['prefix' => 'kelola-kategori-ppq'], function ()
	{
		Route::get('','MasterAppController@manageKategoriPpq')->name('master_app.master_data.manage_kategori_ppqs');
		Route::get('get-kategori-ppq/{kategori_ppq_id}','Master\Rollie\KategoriPpqController@getKategoriPpq');
		Route::post('','Master\Rollie\KategoriPpqController@manageKategoriPpq');
	});

	Route::group(['prefix' => 'kelola-brand'], function ()
	{
		Route::get('','MasterAppController@manageBrand')->name('master_app.master_data.manage_brands');
		Route::get('/get-brand/{brand_id}','Master\Rollie\BrandController@getBrand');
		Route::post('','Master\Rollie\BrandController@manageBrand');
	});

	Route::group(['prefix' => 'kelola-subbrand'], function ()
	{
		Route::get('','MasterAppController@manageSubbrand')->name('master_app.master_data.manage_subbrands');
		Route::get('/get-subbrand/{subbrand_id}','Master\Rollie\SubbrandController@getSubbrand');
		Route::post('','Master\Rollie\SubbrandController@manageSubbrand');
	});

	Route::group(['prefix' => 'kelola-tipe-produk'], function ()
	{
		Route::get('','MasterAppController@manageProductType')->name('master_app.master_data.manage_product_types');
		Route::post('','Master\Rollie\ProductTypeController@manageProductType');
		Route::get('get-tipe-produk/{product_type_id}','Master\Rollie\ProductTypeController@getProductType');
	});

	Route::group(['prefix' => 'kelola-kategori-flowmeter'], function ()
	{
		Route::get('', 'MasterAppController@manageFlowmeterCategory')->name('master_app.master_data.manage_flowmeter_categories');
		Route::post('', 'Master\Emon\FlowmeterCategoryController@manageFlowmeterCategory');
		Route::get('edit-flowmeter-category/{flowmeter_category_id}', 'Master\Emon\FlowmeterCategoryController@editFlowmeterCategory');
	});

	Route::group(['prefix' => 'kelola-flowmeter-workcenter'], function () {
		Route::get('', 'MasterAppController@manageFlowmeterWorkcenter')->name('master_app.master_data.manage_flowmeter_workcenters');
		Route::post('', 'Master\Emon\FlowmeterWorkcenterController@manageFlowmeterWorkcenter');
		Route::get('edit-flowmeter-workcenter/{flowmeter_workcenter_id}', 'Master\Emon\FlowmeterWorkcenterController@editFlowmeterWorkcenter');
	});

	Route::group(['prefix' => 'kelola-flowmeter-unit'], function () {
		Route::get('', 'MasterAppController@manageFlowmeterUnit')->name('master_app.master_data.manage_flowmeter_units');
		Route::post('', 'Master\Emon\FlowmeterUnitController@manageFlowmeterUnit');
		Route::get('edit-flowmeter-unit/{flowmeter_unit_id}', 'Master\Emon\FlowmeterUnitController@editFlowmeterUnit');
	});

	Route::group(['prefix' => 'kelola-flowmeter-location'], function () {
		Route::get('', 'MasterAppController@manageFlowmeterLocation')->name('master_app.master_data.manage_flowmeter_locations');
		Route::post('', 'Master\Emon\FlowmeterLocationController@manageFlowmeterLocation');
		Route::get('edit-flowmeter-location/{flowmeter_unit_id}', 'Master\Emon\FlowmeterLocationController@editFlowmeterLocation');
	});


	Route::group(['prefix' => 'kelola-flowmeter'], function () {
		Route::get('', 'MasterAppController@manageFlowmeter')->name('master_app.master_data.manage_flowmeters');
		Route::post('', 'Master\Emon\FlowmeterController@manageFlowmeter');
		Route::get('edit-flowmeter/{flowmeter_id}', 'Master\Emon\FlowmeterController@editFlowmeter');
	});

	Route::group(['prefix' => 'kelola-flowmeter-usage'], function ()
	{
		Route::get('', 'MasterAppController@manageFlowmeterUsage')->name('master_app.master_data.manage_flowmeter_usages');
		Route::post('', 'Master\Emon\FlowmeterUsageController@manageFlowmeter');
		Route::get('edit-flowmeter-usage/{flowmeter_id}', 'Master\Emon\FlowmeterUsageController@editFlowmeter');
	});

	Route::group(['prefix' => 'kelola-flowmeter-formula'], function ()
	{
		Route::get('', 'MasterAppController@manageFlowmeterFormula')->name('master_app.master_data.manage_flowmeter_formulas');
		Route::post('', 'Master\Emon\FlowmeterFormulaController@manageFormula');
		Route::get('edit-flowmeter-formula/{flowmeter_id}', 'Master\Emon\FlowmeterUsageController@editFlowmeter');
	});

	Route::group(['prefix' => 'kelola-flowmeter-location-permission'], function ()
	{
		Route::get('', 'MasterAppController@manageLocationPermission')->name('master_app.master_data.manage_flowmeter_location_permissions');
		Route::get('tambah-akses', 'MasterAppController@showFormManageLocationPermission');
		Route::post('tambah-akses', 'Master\Emon\FlowmeterLocationPermissionsController@grantAccess');

		Route::get('get-location/{category_id}/{user_id}', 'Master\Emon\FlowmeterLocationPermissionsController@getFlowmeter');
		Route::post('ubah-akses', 'Master\Emon\FlowmeterLocationPermissionsController@changeAccess');

	});
});


Route::group(['prefix' => 'rollie','middleware'=>['auth','credential.check']], function()
{
    Route::get('/', 'Rollie\HomeController@index')->name('rollie.home');
    Route::group(['prefix' => 'production-schedule'], function ()
    {
        Route::get('', 'Rollie\ProductionScheduleController@index')->name('rollie.production_schedule');
        Route::get('/get-data', 'Rollie\ProductionScheduleController@getData');

        Route::get('/add-new-production-schedule', 'Rollie\ProductionScheduleController@newProductionScheduleForm');
        Route::get('/get-data-draft', 'Rollie\ProductionScheduleController@getDataDraft');


        Route::post('/upload-mtol-modal','Rollie\ProductionScheduleController@uploadMtolModal');
        Route::post('/upload-mtol','Rollie\ProductionScheduleController@uploadMtol');

        Route::post('/manual-add-modal','Rollie\ProductionScheduleController@manualAddModal');
        Route::post('/manual-add','Rollie\ProductionScheduleController@manualAdd');

        Route::post('/remove-draft-schedule','Rollie\ProductionScheduleController@removeDraftSchedule');

        Route::post('/update-draft-schedule-modal','Rollie\ProductionScheduleController@updateDraftScheduleModal');
        Route::post('/update-draft-schedule','Rollie\ProductionScheduleController@updateDraftSchedule');

        Route::post('/finalize-draft-schedule','Rollie\ProductionScheduleController@finalizeDraftSchedule');

        Route::post('update-schedule-modal','Rollie\ProductionScheduleController@updateScheduleModal');
        Route::post('update-schedule','Rollie\ProductionScheduleController@updateSchedule');

        Route::post('/cancel-production-schedule','Rollie\ProductionScheduleController@cancelProductionSchedule');

    });

    Route::group(['prefix' => 'rpd-filling'], function ()
    {
        Route::get('', 'Rollie\RPDFillingController@index')->name('rollie.rpd_filling');
        Route::get('get-data', 'Rollie\RPDFillingController@getData');

        Route::post('process-rpd-filling', 'Rollie\RPDFillingController@processRPDFilling');

        Route::group(['prefix' => 'form'], function ()
        {
            Route::get('/{rpd_filling_head}', 'Rollie\RPDFillingController@showRPDFillingForm');
        });
        Route::get('get-draft-filling-sampel','Rollie\RPDFillingController@getDraftFillingSample');
        Route::get('get-done-filling-sampel','Rollie\RPDFillingController@getDoneFillingSample');

        Route::post('add-filling-sampel-modal','Rollie\RPDFillingController@addFillingSampelModal');
        Route::post('get-filling-sampel-code', 'Rollie\RPDFillingController@getFillingSampelCode');

        Route::post('check-filling-sampel-code','Rollie\RPDFillingController@checkFillingSampelModal');
        Route::post('add-filling-sampel-code','Rollie\RPDFillingController@addFillingSampelCode');

        Route::post('add-batch-modal', 'Rollie\RPDFillingController@addBatchModal');
        Route::post('get-wo-number-batch','Rollie\RPDFillingController@getWoNumberBatch');
        Route::post('add-batch', 'Rollie\RPDFillingController@addBatch');

        Route::post('analisa-filling-sampel-modal', 'Rollie\RPDFillingController@analisaFillingSampelModal');
        Route::post('analisa-filling-sampel', 'Rollie\RPDFillingController@analisaFillingSampel');
        Route::post('analisa-filling-sampel-event', 'Rollie\RPDFillingController@analisaFillingSampelEvent');

    });
});
