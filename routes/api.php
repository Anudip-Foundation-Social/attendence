<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AttendanceController;
use App\Http\Controllers\api\TrainerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
///////.............FROM 2025-08-29 STUDENT PART IS CLOSED ONLY TRAINER PART IS PRESENT IN THIS APP.....//////
//Route::post('login',[UserController::class,'loginUser']);
Route::post('login',[UserController::class,'loginUserForTrainer']);
Route::post('login-student',[UserController::class,'loginUserForStudent']);
//Route::post('insertIntoAttendanceFromCMIS',[AttendanceController::class,'insertIntoAttendanceFromCMIS']);
Route::post('UpdateAttendance',[AttendanceController::class,'UpdateAttendance']);
//Route::post('insertIntoAttendanceFromCMISFromExcel',[AttendanceController::class,'insertIntoAttendanceFromCMISFromExcel']);

// Route::get('fetchDataForCheckingRedis',[AttendanceController::class,'fetchDataForCheckingRedis']);

Route::get('offlineSyncBulkPunchInOutAttendance_cron',[AttendanceController::class,'offlineSyncBulkPunchInOutAttendance_cron']);

Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::get('user',[UserController::class,'userDetails']);
    Route::get('logout',[UserController::class,'logout']);
    Route::post('store-attendance-new-student',[AttendanceController::class,'storeAttendance']);
    Route::post('offlineSync-new-student',[AttendanceController::class,'offlineSync']);
    Route::post('offlineSyncBulkPunchInOutAttendance-new',[AttendanceController::class,'offlineSyncBulkPunchInOutAttendance']);

    

    Route::get('fetch-attendance-student/{user_id}/{cur_month}/{cur_year}',[AttendanceController::class,'fetchAttendance']);
    Route::get('fetch-attendance-based-on-currentdate-student/{user_id}/{cur_date?}',[AttendanceController::class,'fetchAttendanceBasedOnCurrentDate']);

    Route::get('fetch-center-for-trainer/{trainer_id}',[TrainerController::class,'fetchCenterForTrainer']);
    Route::get('fetch-batch-by-center/{center_id}',[TrainerController::class,'fetchBatchByCenter']);
    Route::get('fetch-student-by-batch/{batch_id}',[TrainerController::class,'fetchStudentByBatch']);
    Route::post('store-bulk-punchin-out-attendance-new',[TrainerController::class,'storeBulkPunchInOutAttendance']);

    Route::get('fetchAllDetailsForTrainer/{username}',[AttendanceController::class,'fetchAllDetailsForTrainer']);
    Route::post('app-version-check',[TrainerController::class,'appVersionCheck']);
    
});

