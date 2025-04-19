
<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComensalController;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('comensales', ComensalController::class);
});