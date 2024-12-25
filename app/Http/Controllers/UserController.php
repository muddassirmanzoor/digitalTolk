<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ArrivalSection;
use App\Models\AuditLog;
use App\Models\DepartureSection;
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

class UserController extends Controller
{
    public function userList(Request $request)
    {
        $users = User::with('roles')->where('id', '!=', auth()->id())->get();

        return view('user.list', compact('users'));
    }

    public function showForm(Request $request)
    {
        $roles = Role::all();
        return view('user.form', compact('roles'));
    }

    public function editForm($operation_id)
    {
        $agents = Agent::get();
        $operation = OperationInformation::where('id', $operation_id)->with('arrival', 'movement', 'mzarat', 'departure')->first();
        return view('data.editForm', compact('agents', 'operation'));
    }

    public function addUser(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:6|same:re_password',
            're_password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 1, // Default status
        ]);

        // Assign role
        $user->assignRole($request->role);

        return redirect()->to('user-list')->with('success', 'User added successfully!');

    }

    public function updateData(Request $request,  $operational_id)
    {
        // Validate request data
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'voucher_number' => 'required|unique:operation_information,voucher_number,' . $operational_id . ',operational_id',
        ]);

        DB::beginTransaction();

        try {
            // Find the existing operational information record
            $operationalInfo = OperationInformation::where('operational_id', $operational_id)->firstOrFail();

            // Update the operational information
            $operationalInfo->update([
                'operational_id' => $request->input('operational_id'),
                'agent_id' => $request->input('agent_id'),
                'people_quantity' => $request->input('people_quantity'),
                'voucher_number' => $request->input('voucher_number'),
                'nationality' => $request->input('nationality'),
                'group_leader_name' => $request->input('group_leader_name'),
                'group_leader_number' => $request->input('group_leader_number'),
            ]);

            // Update Arrival Section
            if ($request->has('arrival')) {
                $arrivalData = $request->input('arrival');
                ArrivalSection::updateOrCreate(
                    ['operational_information_id' => $operationalInfo->id],
                    [
                        'operational_id' => $request->input('operational_id'),
                        'arrival_date' => $arrivalData['arrival_date'],
                        'arrival_time' => $arrivalData['arrival_time'],
                        'arrival_flight_no' => $arrivalData['arrival_flight_no'],
                        'terminal_name' => $arrivalData['terminal_name'],
                        'transport_time' => $arrivalData['transport_time'],
                        'transport_company' => $arrivalData['transport_company'],
                    ]
                );
            }

            // Update Movement Section
            if ($request->has('movement')) {
                $movementData = $request->input('movement');
                $movement = $request->input('movement');

                // Check if any value in the 'movement' array is not empty
                $hasMovementData = collect($movement)->filter(function ($value) {
                    return !empty($value);
                })->isNotEmpty();

                if ($hasMovementData) {
                    MovementSection::updateOrCreate(
                        ['operational_information_id' => $operationalInfo->id],
                        [
                            'operational_id' => $request->input('operational_id'),
                            'travel_from' => $movementData['travel_from'],
                            'travel_to' => $movementData['travel_to'],
                            'travel_date' => $movementData['travel_date'],
                            'travel_time' => $movementData['travel_time'],
                            'transport_time' => $movementData['transport_time'],
                            'transport_company' => $movementData['transport_company'],
                        ]
                    );
                }
            }

            // Update Mzarat Section
            if ($request->has('mzarat')) {
                $mzaratData = $request->input('mzarat');
                $mzarat = $request->input('mzarat');

                // Check if any value in the 'movement' array is not empty
                $hasMzaratData = collect($mzarat)->filter(function ($value) {
                    return !empty($value);
                })->isNotEmpty();

                if ($hasMzaratData) {
                    MzaratSection::updateOrCreate(
                        ['operational_information_id' => $operationalInfo->id],
                        [
                            'operational_id' => $request->input('operational_id'),
                            'travel_from' => $mzaratData['travel_from'],
                            'travel_to' => $mzaratData['travel_to'],
                            'travel_date' => $mzaratData['travel_date'],
                            'travel_time' => $mzaratData['travel_time'],
                            'transport_time' => $mzaratData['transport_time'],
                            'transport_company' => $mzaratData['transport_company'],

                        ]
                    );
                }
            }

            // Update Departure Section
            if ($request->has('departure')) {
                $departureData = $request->input('departure');
                $departure = $request->input('departure');

                // Check if any value in the 'movement' array is not empty
                $hasDepartureData = collect($departure)->filter(function ($value) {
                    return !empty($value);
                })->isNotEmpty();

                if ($hasDepartureData) {
                    DepartureSection::updateOrCreate(
                        ['operational_information_id' => $operationalInfo->id],
                        [
                            'operational_id' => $request->input('operational_id'),
                            'departure_date' => $departureData['departure_date'],
                            'departure_time' => $departureData['departure_time'],
                            'departure_flight_no' => $departureData['departure_flight_no'],
                            'terminal_name' => $departureData['terminal_name'],
                            'transport_time' => $departureData['transport_time'],
                            'transport_company' => $departureData['transport_company'],
                        ]
                    );
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->to('data-list')->with('success', 'Operational Information Updated Successfully!');

        } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();

                return response()->json(['success' => false, 'message' => 'An error occurred. ' . $e->getMessage()]);
            }
    }

    public function updateStatus(Request $request,  $id)
    {
        // Find the operation by ID
        $user = User::findOrFail($id);

        // Toggle status (e.g., 0 -> 1 or 1 -> 0)
        $user->status = $user->status == 0 ? 1 : 0;

        // Save the changes
        $user->save();

        // Return a success response or redirect back
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

}
