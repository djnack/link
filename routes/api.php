<?php

use App\Http\Controllers\Api\V1\CreateController;
use App\Http\Controllers\Api\V1\CreateGroupController;
use App\Http\Controllers\Api\V1\LoginController;
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

Route::prefix('v1')->group(
    function () {
        Route::post('register', [LoginController::class, 'register']);
    }
);
Route::middleware(['token'])->prefix('v1')->group(
    function () {
        Route::post('create', [CreateController::class, 'index']);
        Route::post('create_group', [CreateGroupController::class, 'index']);
    }
);
