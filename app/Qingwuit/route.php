<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', [App\Qingwuit\Controllers\SpaController::class,'index'])->where('any', '.*');
