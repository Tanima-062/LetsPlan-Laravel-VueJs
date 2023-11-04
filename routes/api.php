<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [\App\Http\Controllers\AuthenticationController::class, 'login']);
Route::get('/getPermissions', [App\Http\Controllers\UserController::class, 'getPermissions']);
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
Route::post('{companyId}/parent-registry', [\App\Http\Controllers\ParentRegistrationController::class, 'store']);
Route::get('google', [App\Http\Controllers\UserController::class, 'googleMapsDistance']);
Route::get('languages', [\App\Http\Controllers\ParentRegistrationController::class, 'languages']);
Route::get('nationalities', [\App\Http\Controllers\ParentRegistrationController::class, 'nationalities']);
Route::get('spielgruppens/{companyId}', [\App\Http\Controllers\ParentRegistrationController::class, 'spielgruppens']);
// Route::get('get-period-info/{companyId}', [\App\Http\Controllers\RegistryController::class, 'getRegistrationPeriodInfo']);
Route::get('get-period-info/{companyId}', [\App\Http\Controllers\DivisionController::class, 'getRegistrationPeriodInfo']);
Route::get('status', [\App\Http\Controllers\StatusController::class, 'index']);
Route::get('getLeads', [App\Http\Controllers\SpielgruppenController::class, 'getLeads']);
Route::get('getAssistants', [App\Http\Controllers\SpielgruppenController::class, 'getAssistants']);
Route::get('/getCities', [App\Http\Controllers\SpielgruppenController::class, 'getCities']);
Route::get('/getTimes', [App\Http\Controllers\SpielgruppenController::class, 'getTimes']);
Route::get('company/{company}', [\App\Http\Controllers\CompanyController::class, 'getInfo']);
Route::get('adminuser/{user}', [\App\Http\Controllers\AdminuserController::class, 'getInfo']);
Route::get('division/{division}', [\App\Http\Controllers\DivisionController::class, 'getInfo']);
Route::get('/spielgruppen/get/with-available-slots/{id}', [\App\Http\Controllers\SpielgruppenController::class, 'getPlaygroupWithAvailableSlots']);
Route::get('/spielgruppen/get/{id}', [\App\Http\Controllers\SpielgruppenController::class, 'show']);
Route::get('/get-filterable-nationalities/{query}', [\App\Http\Controllers\AnmeldungenController::class, 'getFilterableNationalities']);
Route::get('/get-filterable-mother_tongues/{query}', [\App\Http\Controllers\AnmeldungenController::class, 'getFilterableMotherTongues']);




Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::get('/startseite',[\App\Http\Controllers\DashboardController::class, 'index'])->withoutMiddleware('admin');

    Route::get('/startseite-filterable', [\App\Http\Controllers\DashboardController::class, 'getFilterableData'])->withoutMiddleware('admin');

    Route::get('/spielgruppen-next-id',  [\App\Http\Controllers\SpielgruppenController::class, 'getNextId']);
    Route::post('logout', [\App\Http\Controllers\AuthenticationController::class, 'logout'])->withoutMiddleware('admin');
    Route::post('registry', [\App\Http\Controllers\RegistryController::class, 'store']);
    Route::get('/anmeldungens', [\App\Http\Controllers\AnmeldungenController::class, 'index'])->withoutMiddleware('admin');
    Route::get('/anmeldungens-sendMail/{child_registration}', [\App\Http\Controllers\AnmeldungenController::class, 'sendMail'])->withoutMiddleware('admin');
    Route::post('anmeldungen', [\App\Http\Controllers\AnmeldungenController::class, 'store'])->withoutMiddleware('admin');
    Route::post('anmeldungen/{child_registration}', [\App\Http\Controllers\AnmeldungenController::class, 'update'])->withoutMiddleware('admin');
    Route::get('anmeldungens/filterable', [\App\Http\Controllers\AnmeldungenController::class, 'getFilterableData'])->withoutMiddleware('admin');
    Route::get('anmeldungens/get/{child_registration}', [\App\Http\Controllers\AnmeldungenController::class, 'show'])->withoutMiddleware('admin');
    Route::put('anmeldungens/{child_registration}/update-status', [\App\Http\Controllers\AnmeldungenController::class, 'updateStatus'])->withoutMiddleware('admin');
    Route::put('anmeldungens/{child_registration}/update-marked', [\App\Http\Controllers\AnmeldungenController::class, 'updateMarked'])->withoutMiddleware('admin');
    Route::get('last-inserted-children', [\App\Http\Controllers\AnmeldungenController::class, 'getLastRegisterdChildrenId'])->withoutMiddleware('admin');
    //Children delete


    Route::get('benutzer/last-user-id', [App\Http\Controllers\BenutzerController::class, 'getLastUserId']);
    Route::resource('benutzer', \App\Http\Controllers\BenutzerController::class)->except('update');
    Route::get('/filter/benutzer', [\App\Http\Controllers\BenutzerController::class, 'filter'])->withoutMiddleware('admin');
    Route::post('benutzer/update/{benutzer}', [App\Http\Controllers\BenutzerController::class, 'update']);
    Route::delete('benutzer/delete-image/{benutzer}', [App\Http\Controllers\BenutzerController::class, 'deleteImage']);
    Route::post('benutzer/send-inviatation/{benutzer}', [App\Http\Controllers\BenutzerController::class, 'sendInvitation'])->withoutMiddleware('admin');;
    Route::patch('benutzer/{benutzer}/status', [App\Http\Controllers\BenutzerController::class, 'status']);


    //new routes
    Route::get('benutzer-filterable', [\App\Http\Controllers\BenutzerController::class, 'getFilterData']);

    Route::get('adminuser-filterable', [\App\Http\Controllers\AdminuserController::class, 'getFilterData'])->withoutMiddleware('admin');


    Route::get('/spielgruppen', [\App\Http\Controllers\SpielgruppenController::class, 'index'])->withoutMiddleware('admin');
    Route::get('/spielgruppens-filterable', [\App\Http\Controllers\SpielgruppenController::class, 'getFilterableData'])->withoutMiddleware('admin');
    Route::get('/spielgruppen/edit/{id}', [\App\Http\Controllers\SpielgruppenController::class, 'edit'])->withoutMiddleware('admin');
    Route::get('/spielgruppen/show/{id}', [\App\Http\Controllers\SpielgruppenController::class, 'show'])->withoutMiddleware('admin');
    Route::post('/spielgruppen/update/{id}', [App\Http\Controllers\SpielgruppenController::class, 'update']);
    Route::put('/spielgruppen/{spielgruppen}/update-status', [App\Http\Controllers\SpielgruppenController::class, 'status']);
    Route::get('plannung', [App\Http\Controllers\PlannungController::class, 'index'])->withoutMiddleware('admin');
    Route::put('plannung', [App\Http\Controllers\PlannungController::class, 'update']);
    Route::post('plannung/order', [App\Http\Controllers\PlannungController::class, 'order'])->withoutMiddleware('admin');
    Route::post('plannung/status/{status}', [App\Http\Controllers\PlannungController::class, 'status']);
    Route::get('plannung/get-status', [App\Http\Controllers\PlannungController::class, 'getActiveStatus'])->withoutMiddleware('admin');
    Route::post('plannung/children-order-inside-slot', [App\Http\Controllers\PlannungController::class, 'orderChildrenInsideSameSlot']);
    Route::post('plannung/changechildrenplaygorup', [App\Http\Controllers\PlannungController::class, 'changeChildrenPlaygroup']);
    Route::get('planung/stats', [App\Http\Controllers\PlannungController::class, 'childrenStatistics'])->withoutMiddleware('admin');
    Route::delete('planung/delete-child/{childrenRegistrationOpeningHour}', [App\Http\Controllers\PlannungController::class, 'deleteChild']);

    Route::get('planung/filterable', [App\Http\Controllers\PlannungController::class, 'getFilterableData'])->withoutMiddleware('admin');


    Route::get('registry', [\App\Http\Controllers\RegistryController::class, 'show'])->withoutMiddleware('admin');
    Route::post('/spielgruppen/store', [\App\Http\Controllers\SpielgruppenController::class, 'store'])->withoutMiddleware('admin');
    Route::get('user', [App\Http\Controllers\UserController::class, 'show'])->withoutMiddleware('admin');
    Route::post('user/update', [App\Http\Controllers\UserController::class, 'update'])->withoutMiddleware('admin');
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->withoutMiddleware('admin');
    Route::get('childInformation/{childrenRegistration}', [App\Http\Controllers\PlannungController::class, 'childInformation'])->withoutMiddleware('admin');
    Route::get('/exports/childrens', [App\Http\Controllers\AnmeldungenController::class, 'export'])->name('export');
    Route::get('/company', [\App\Http\Controllers\CompanyController::class, 'index'])->withoutMiddleware('admin');;
    Route::post('company', [\App\Http\Controllers\CompanyController::class, 'store']);
    Route::post('company/activate/{company}', [App\Http\Controllers\CompanyController::class, 'activate']);
    Route::post('company/deactivate/{company}', [App\Http\Controllers\CompanyController::class, 'deactivate']);
    Route::get('company-get-max', [App\Http\Controllers\CompanyController::class, 'getmax']);
    Route::put('/adminuser/status/{user}', [App\Http\Controllers\AdminuserController::class, 'updateStatus'])->withoutMiddleware('admin');
    Route::get('adminuser', [\App\Http\Controllers\AdminuserController::class, 'index'])->withoutMiddleware('admin');
    Route::post('adminuser', [\App\Http\Controllers\AdminuserController::class, 'store']);
    Route::get('adminuser/get-max', [App\Http\Controllers\AdminuserController::class, 'getmax']);
    Route::get('division', [\App\Http\Controllers\DivisionController::class, 'index'])->withoutMiddleware('admin');
    Route::get('all-division', [\App\Http\Controllers\DivisionController::class, 'getAllDivisions']);
    Route::post('division', [\App\Http\Controllers\DivisionController::class, 'store']);
    Route::post('division/activate/{division}', [App\Http\Controllers\DivisionController::class, 'activate']);
    Route::post('division/deactivate/{division}', [App\Http\Controllers\DivisionController::class, 'deactivate']);
    Route::get('division-get-max', [App\Http\Controllers\DivisionController::class, 'getmax']);
    Route::post('division/update/{division}', [App\Http\Controllers\DivisionController::class, 'update']);
    Route::delete('division-delete/{division}', [App\Http\Controllers\DivisionController::class, 'destroy']);
    Route::get('getDivision/{division}', [\App\Http\Controllers\DivisionController::class, 'getDivision']);
    Route::put('division-status/{division}/update', [\App\Http\Controllers\DivisionController::class, 'updateDivisionStatus']);
});
Route::group(['middleware' => ['auth:sanctum', 'super_admin']], function () {
    Route::get('/startseite',[\App\Http\Controllers\DashboardController::class, 'index'])->withoutMiddleware('super_admin');
    Route::get('/startseite-filterable', [\App\Http\Controllers\DashboardController::class, 'getFilterableData'])->withoutMiddleware('super_admin');

    Route::get('company', [\App\Http\Controllers\CompanyController::class, 'index'])->withoutMiddleware('super_admin');
    Route::get('/company-lists', [\App\Http\Controllers\CompanyController::class, 'getActiveCompanyLists'])->withoutMiddleware('super_admin');
    Route::post('company', [\App\Http\Controllers\CompanyController::class, 'store']);
    Route::post('company/activate/{company}', [App\Http\Controllers\CompanyController::class, 'activate']);
    Route::post('company/deactivate/{company}', [App\Http\Controllers\CompanyController::class, 'deactivate']);
    Route::get('company-get-max', [App\Http\Controllers\CompanyController::class, 'getmax']);
    Route::post('company/update/{company}', [App\Http\Controllers\CompanyController::class, 'update']);
    Route::get('adminuser', [\App\Http\Controllers\AdminuserController::class, 'index'])->withoutMiddleware('super_admin');
    Route::post('adminuser', [\App\Http\Controllers\AdminuserController::class, 'store']);
    Route::get('admin-get-max', [App\Http\Controllers\AdminuserController::class, 'getmax']);
    Route::post('/adminuser/update/{user}', [App\Http\Controllers\AdminuserController::class, 'update']);
    Route::patch('benutzer/{benutzer}/status', [App\Http\Controllers\BenutzerController::class, 'status'])->withoutMiddleware('super_admin');;
});
