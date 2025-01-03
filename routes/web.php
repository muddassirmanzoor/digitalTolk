<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TransportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route to show the login page
Route::get('/', [LoginController::class, 'showLogin'])->name('login');

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Route for handling login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');



Route::middleware(['auth'])->group(function () {
    Route::any('data-list', [DataController::class, 'dataList'])->middleware('role:deo,reviewer,manager,transport,admin');
    Route::get('add-data', [DataController::class, 'showForm'])->middleware('role:deo,reviewer,manager,admin');
    Route::post('add-data', [DataController::class, 'submitData'])->middleware('role:deo,reviewer,manager,admin');
    Route::get('edit-data/{id}', [DataController::class, 'editForm'])->middleware('role:deo,reviewer,manager,admin');
    Route::post('update-data/{id}', [DataController::class, 'updateData'])->middleware('role:deo,reviewer,manager,admin');
    Route::post('update-status/{id}', [DataController::class, 'updateStatus'])->middleware('role:deo,reviewer,manager,admin');
    Route::get('edit-logs/{id}', [DataController::class, 'editLogs'])->middleware('role:manager,admin');

    Route::get('user-list', [UserController::class, 'userList'])->middleware('role:admin');
    Route::get('add-user', [UserController::class, 'showForm'])->middleware('role:admin');
    Route::post('add-user', [UserController::class, 'addUser'])->middleware('role:admin');
    Route::get('edit-user/{id}', [UserController::class, 'editForm'])->middleware('role:admin');
    Route::post('update-user-status/{id}', [UserController::class, 'updateStatus'])->middleware('role:admin');

    Route::get('agent-list', [AgentController::class, 'agentList'])->middleware('role:admin');
    Route::get('add-agent', [AgentController::class, 'showForm'])->middleware('role:admin');
    Route::post('add-agent', [AgentController::class, 'addAgent'])->middleware('role:admin');
    Route::get('edit-agent/{id}', [AgentController::class, 'editForm'])->middleware('role:admin');
    Route::post('update-agent-status/{id}', [AgentController::class, 'updateStatus'])->middleware('role:admin');

    Route::get('driver-list', [DriverController::class, 'driverList'])->middleware('role:transport,admin');
    Route::get('add-driver', [DriverController::class, 'showForm'])->middleware('role:transport,admin');
    Route::post('add-driver', [DriverController::class, 'addDriver'])->middleware('role:transport,admin');
    Route::get('edit-driver/{id}', [DriverController::class, 'editForm'])->middleware('role:transport,admin');
    Route::post('update-driver-status/{id}', [DriverController::class, 'updateStatus'])->middleware('role:admin');

    Route::any('operations-list', [TransportController::class, 'operationsList'])->middleware('role:transport,admin');
    Route::any('assign-driver-form', [TransportController::class, 'assignDriverFrom'])->middleware('role:transport,admin');
    Route::any('edit-assign-driver-form', [TransportController::class, 'editAssignDriverFrom'])->middleware('role:transport,admin');
    Route::any('assign-driver', [TransportController::class, 'assignDriver'])->middleware('role:transport,admin');

    Route::get('download-arrival-list', [TransportController::class, 'downloadArabicExcel']);

});
