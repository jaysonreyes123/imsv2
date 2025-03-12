<?php

use App\Events\UserLogout;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RelatedController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::post("auth/login",'login');
    Route::get("auth/forgot-password/{email}",'forgotPassword');
    Route::post("auth/reset-password",'resetpassword');
    Route::get("auth/logout",'logout')->middleware('auth:sanctum');
    route::post("auth/verify",'verify');
});

Route::middleware(['auth:sanctum'])->group(function(){

    Route::get('users/user_details',[UserController::class,'user_details']); 
    Route::post("users/profile/edit/{action}/{id}",[UserController::class,'edit_profile']);
    Route::get("users/edit/{id}",[UserController::class,'edit']);
    Route::apiResource("users",UserController::class);

    Route::prefix('dashboard')->group(function(){
        Route::get("widget/{module}/{field?}/{operator?}/{value?}",[DashboardController::class,'get_widget']);
        Route::get("chart/incident_trend_month",[DashboardController::class,'incident_trend_month']);
        Route::get("chart/incident_status",[DashboardController::class,'incident_status']);
        Route::get("systemlogs",[DashboardController::class,'get_systemlogs']);
    });

    Route::get('module/get_modules',[ModuleController::class,'get_modules']);
    Route::get("module/edit/{module}/{id}",[ModuleController::class,'edit']);
    Route::get("module/{module}/{id}",[ModuleController::class,'show']);
    Route::post("module/delete",[ModuleController::class,'delete']);
    Route::apiResource('module',ModuleController::class);


    route::post("related/save_selected_row",[RelatedController::class,'save_selected_row']);
    Route::get("related/delete/{module}/{related_module}/{module_id}/{related_module_id}",[RelatedController::class,'delete']);
    route::apiResource('related',RelatedController::class);

    
    Route::get('dropdown/get_dropdown/{field}',[DropdownController::class,'get_dropdown']);
    //media
    Route::get("media/related_list/{module}/{related_module}/{id}",[MediaController::class,'related_list']);
    Route::post("media/save",[MediaController::class,'save']);

    //comment
    Route::prefix('comments')->group(function(){
      Route::get("delete/{module}/{related_module}/{module_id}/{related_id}",[CommentController::class,'delete']);
      Route::post("save",[CommentController::class,'save']);
      Route::post("save_reply",[CommentController::class,'save_reply']);
      Route::get("get_comments/{module}/{id}",[CommentController::class,'index']);
      Route::get("get_comment_replies/{commentid}",[CommentController::class,'comment_reply_index']);
    });

    Route::prefix('system')->group(function(){
        Route::get("activity_logs/{module}/{module_id}",[SystemController::class,'activity_logs']);
        Route::get("login_history",[SystemController::class,'login_history']);
        Route::get("notification",[SystemController::class,'notification']);
    });

    //map api
    Route::get("map/{module}/{start?}/{end?}",[MapController::class,'index']);

     //import 
     Route::post("import/import_data",[ImportController::class,'import_data']);
     Route::post("import/save",[ImportController::class,'save']);

     //report
     Route::get("reports/edit/{id}",[ReportController::class,'edit']);
     Route::get("reports/generate/export/{type}/{id}",[ReportController::class,'generate']);
     Route::apiResource('reports',ReportController::class);

});