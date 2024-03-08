<?php

use App\Http\Controllers\phanboluongController;
use Illuminate\Support\Facades\Route;

Route::prefix('phanboluong')->group(function(){
    Route::prefix('/donvi')->group(function(){
        Route::get('/',[phanboluongController::class,'index']);
        Route::post('/thongtin',[phanboluongController::class,'thongtin_phanbo']);
        Route::post('/store',[phanboluongController::class,'tao_phanbo']);
        Route::post('/del/{id}',[phanboluongController::class,'destroy']);

        Route::post('/senddata',[phanboluongController::class,'senddata']);
        Route::get('/lydo',[phanboluongController::class,'getlydo']);
    });
});