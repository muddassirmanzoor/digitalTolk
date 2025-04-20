<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/availability/{address}/{appointmentType}', [\App\Http\Controllers\AvailabilityController::class, 'getNextSlot']);


