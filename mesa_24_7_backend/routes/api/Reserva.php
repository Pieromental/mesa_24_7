<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('reservas', ReservaController::class);
});