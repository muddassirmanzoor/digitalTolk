<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ArrivalSection;
use App\Models\AuditLog;
use App\Models\DepartureSection;
use App\Models\Driver;
use App\Models\InterviewResult;
use App\Models\Markaz;
use App\Models\MovementSection;
use App\Models\MzaratSection;
use App\Models\OperationInformation;
use App\Models\PaperResult;
use App\Models\School;
use App\Models\SearchLogs;
use App\Models\TeacherActivation;
use App\Models\Tehsil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class DriverController extends Controller
{
    public function driverList(Request $request)
    {
        $drivers = Driver::get();

        return view('driver.list', compact('drivers'));
    }

    public function showForm(Request $request)
    {
        return view('driver.form');
    }

    public function addDriver(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'driver_name' => 'required|string|max:255',
            'driver_number' => 'required|string|max:255',
            'driver_company' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the Driver
         Driver::create([
            'driver_name' => $request->driver_name,
            'driver_number' => $request->driver_number,
            'driver_company' => $request->driver_company,
            'status' => 1, // Default status
        ]);

        return redirect()->to('driver-list')->with('success', 'Driver added successfully!');
    }

    public function updateStatus(Request $request,  $id)
    {
        // Find the operation by ID
        $driver = Driver::findOrFail($id);

        // Toggle status (e.g., 0 -> 1 or 1 -> 0)
        $driver->status = $driver->status == 0 ? 1 : 0;

        // Save the changes
        $driver->save();

        // Return a success response or redirect back
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

}
