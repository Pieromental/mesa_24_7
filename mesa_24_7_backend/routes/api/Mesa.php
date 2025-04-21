<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MesaController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('mesas', MesaController::class);
});