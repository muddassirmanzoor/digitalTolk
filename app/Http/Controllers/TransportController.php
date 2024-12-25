<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ArrivalSection;
use App\Models\AuditLog;
use App\Models\DepartureSection;
use App\Models\Driver;
use App\Models\DriverAssignment;
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

class TransportController extends Controller
{
    public function operationsList(Request $request)
    {
        $operation_date = $request->operation_date ?? now()->addDay()->format('Y-m-d'); // Default to today if not provided

        // Model mapping for different sections
        $modelMapping = [
            'arrival' => [ArrivalSection::class, 'arrival_date', 'arrival_time', null, null],
            'departure' => [DepartureSection::class, 'departure_date', 'departure_time', null, null],
            'movement' => [MovementSection::class, 'travel_date', 'travel_time', 'travel_from', 'travel_to'],
            'mzarat' => [MzaratSection::class, 'travel_date', 'travel_time', 'travel_from', 'travel_to'],
        ];

        // Initialize an empty array for combined operations
        $combinedOperations = [];

        foreach ($modelMapping as $section => [$model, $dateColumn, $timeColumn, $travelFromColumn, $travelToColumn]) {
            $sectionOperations = $model::where($dateColumn, $operation_date)
                ->with('operation', 'driverAssignment.driver') // Load related models
                ->get()
                ->map(function ($operation) use ($section, $dateColumn, $timeColumn, $travelFromColumn, $travelToColumn) {
                    $operation->section = $section; // Dynamically add section
                    $operation->dateColumn = $dateColumn;
                    $operation->timeColumn = $timeColumn;

                    if ($travelFromColumn && $travelToColumn) {
                        $operation->travel_from = $operation->$travelFromColumn;
                        $operation->travel_to = $operation->$travelToColumn;
                    }

                    return $operation;
                });

            $combinedOperations = array_merge($combinedOperations, $sectionOperations->toArray());
        }
        return view('transport.operationList', compact('combinedOperations', 'operation_date'));
    }

    public function operationsListOld(Request $request)
    {
        $operation_date = $request->operation_date ?? now()->addDay()->format('Y-m-d'); // Default to today if not provided
        $section = $request->section ?? 'arrival'; // Default to today if not provided

        $modelMapping = [
            'arrival' => [ArrivalSection::class, 'arrival_date', 'arrival_time'],
            'departure' => [DepartureSection::class, 'departure_date', 'departure_time'],
            'movement' => [MovementSection::class, 'travel_date', 'travel_time'],
            'mzarat' => [MzaratSection::class, 'travel_date', 'travel_time'],
        ];

        // Determine the model and column based on the section
        [$model, $dateColumn, $timeColumn] = $modelMapping[$section] ?? [ArrivalSection::class, 'arrival_date'];

        // Fetch the operations
        $operations = $model::where($dateColumn, $operation_date)
            ->with('operation')
            ->get();

        return view('transport.operationList', compact('operations', 'operation_date',
            'section', 'dateColumn','timeColumn'));
    }

    public function assignDriverFrom(Request $request)
    {
        $operational_id = $request->operation_id;
        $section = $request->section;

        $modelMapping = [
            'arrival' => [ArrivalSection::class, 'arrival_date', 'arrival_time'],
            'departure' => [DepartureSection::class, 'departure_date', 'departure_time'],
            'movement' => [MovementSection::class, 'travel_date', 'travel_time'],
            'mzarat' => [MzaratSection::class, 'travel_date', 'travel_time'],
        ];

        // Determine the model and column based on the section
        [$model, $dateColumn, $timeColumn] = $modelMapping[$section];


        // Fetch the operations
        $operation = $model::where('operational_id', $operational_id)
            ->with('operation', 'driverAssignment.driver')
            ->first();

        $drivers = Driver::where('status', 1)->get();
        return view('transport.assignDriverForm', compact('drivers', 'operation', 'dateColumn','timeColumn'));
    }

    public function assignDriver(Request $request)
    {
        // Assign Driver
        DriverAssignment::create([
            'operational_id' => $request->operational_id,
            'section_type' => $request->section_type,
            'section_id' => $request->section_id,
            'driver_id' => $request->driver_id,
            'comments' => $request->comments,
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->to('operations-list')->with('success', 'Driver assigned successfully!');
    }

    public function updateStatus(Request $request,  $id)
    {
        // Find the operation by ID
        $agent = Agent::findOrFail($id);

        // Toggle status (e.g., 0 -> 1 or 1 -> 0)
        $agent->status = $agent->status == 0 ? 1 : 0;

        // Save the changes
        $agent->save();

        // Return a success response or redirect back
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

}
