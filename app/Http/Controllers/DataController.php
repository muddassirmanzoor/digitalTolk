<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ArrivalSection;
use App\Models\AuditLog;
use App\Models\DepartureSection;
use App\Models\EditComment;
use App\Models\InterviewResult;
use App\Models\Markaz;
use App\Models\MovementSection;
use App\Models\MzaratSection;
use App\Models\OperationImage;
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

class DataController extends Controller
{
    public function dataList(Request $request)
    {
        // Check if the user has the 'transport' role
        if (auth()->user()->hasRole('transport')) {
            $arrival_date = $request->arrival_date ?? now()->format('Y-m-d'); // Default to today if not provided
        } else {
            $arrival_date = $request->arrival_date; // Use provided date or null
        }

        $operations = OperationInformation::with('arrival', 'movement', 'mzarat', 'departure', 'audit')
            ->when($arrival_date, function ($query) use ($arrival_date) {
                return $query->whereHas('arrival', function ($query) use ($arrival_date) {
                    $query->where('arrival_date', $arrival_date);
                });
            })->get();

        return view('data.list', compact('operations', 'arrival_date'));
    }

    public function showForm(Request $request)
    {
        $agents = Agent::where('status', 1)->get();
        return view('data.form', compact('agents'));
    }

    public function editForm($operation_id)
    {
        $agents = Agent::where('status', 1)->get();
        $operation = OperationInformation::where('id', $operation_id)
            ->with('arrival', 'movement', 'mzarat', 'departure', 'comments', 'images')->first();
        return view('data.editForm', compact('agents', 'operation'));
    }

    public function editLogs($operation_id)
    {
        $fieldNames = [
            'agent_id' => 'Agent',
            'voucher_number' => 'Voucher Number',
            'group_leader_name' => 'Group Leader Name',
            'group_leader_number' => 'Group Leader Number',
            'people_quantity' => 'People Quantity',
            'arrival_date' => 'Arrival Date',
            'arrival_flight_no' => 'Arrival Flight Number',
            'departure_flight_no' => 'Departure Flight Number',
            'arrival_time' => 'Arrival Time',
            'travel_time' => 'Travel Time',
            'departure_time' => 'Departure Time',
            'terminal_name' => 'Terminal Name',
            'transport_time' => 'Transport Time',
            'transport_company' => 'Transport Company',
            'travel_from' => 'Travel From',
            'travel_to' => 'Travel To',
            'departure_date' => 'Departure Date',
            'travel_date' => 'Travel Date',
        ];

        $logs = AuditLog::where('operational_id', $operation_id)->with('user')->get();
        $comments = EditComment::where('operational_id', $operation_id)->with('user')->first();
        $agentNames = Agent::pluck('agent_name', 'id'); // Fetch agent names indexed by their IDs
        return view('data.editLogs', compact('logs', 'fieldNames', 'agentNames', 'comments'));
    }

    public function submitData(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'operational_id' => 'required|unique:operation_information',
            'agent_id' => 'required|exists:agents,id',
            'voucher_number' => 'required|unique:operation_information'
        ]);

        DB::beginTransaction();
        try {
            $groupNumbers = explode(',', $request->input('group_numbers')); // Split comma-separated values into an array

            // Create the operational information record
            $operationalInfo = OperationInformation::create([
                'operational_id' => $request->input('operational_id'),
                'agent_id' => $request->input('agent_id'),
                'people_quantity' => $request->input('people_quantity'),
                'voucher_number' => $request->input('voucher_number'),
                'nationality' => $request->input('nationality'),
                'group_leader_name' => $request->input('group_leader_name'),
                'group_leader_number' => $request->input('group_leader_number'),
                'group_numbers' => json_encode($groupNumbers),
                'created_by' => auth()->user()->id,
                'status' => 0,
            ]);

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('operations/images', 'public');
                    OperationImage::create(['image_path' => $path,
                        'operational_id' => $request->input('operational_id'),
                        'operation_information_id' => $operationalInfo->id]);
                }
            }

            // Handle Arrival Section if data is provided
            if ($request->has('arrival')) {
                ArrivalSection::create([
                    'operational_information_id' => $operationalInfo->id,
                    'operational_id' => $request->input('operational_id'),
                    'arrival_date' => $request->input('arrival.arrival_date'),
                    'arrival_time' => $request->input('arrival.arrival_time'),
                    'travel_from' => $request->input('arrival.travel_from'),
                    'travel_to' => $request->input('arrival.travel_to'),
                    'arrival_flight_no' => $request->input('arrival.arrival_flight_no'),
                    'terminal_name' => $request->input('arrival.terminal_name'),
                    'transport_time' => $request->input('arrival.transport_time'),
                    'transport_company' => $request->input('arrival.transport_company'),
                ]);
            }

            // Handle Movement Section if data is provided
            if ($request->has('movement')) {
                $movement = $request->input('movement');

                // Check if any value in the 'movement' array is not empty
                $hasMovementData = collect($movement)->filter(function ($value) {
                    return !empty($value);
                })->isNotEmpty();

                if ($hasMovementData) {
                    MovementSection::create([
                        'operational_information_id' => $operationalInfo->id,
                        'operational_id' => $request->input('operational_id'),
                        'travel_from' => $request->input('movement.travel_from'),
                        'travel_to' => $request->input('movement.travel_to'),
                        'travel_date' => $request->input('movement.travel_date'),
                        'travel_time' => $request->input('movement.travel_time'),
                        'transport_time' => $request->input('movement.transport_time'),
                        'transport_company' => $request->input('movement.transport_company'),
                    ]);
                }
            }

            // Handle Mzarat Section if data is provided
            if ($request->has('mzarat')) {
                $mzarat = $request->input('mzarat');

                // Check if any value in the 'movement' array is not empty
                $hasMzaratData = collect($mzarat)->filter(function ($value) {
                    return !empty($value);
                })->isNotEmpty();

                if ($hasMzaratData) {
                    MzaratSection::create([
                        'operational_information_id' => $operationalInfo->id,
                        'operational_id' => $request->input('operational_id'),
                        'travel_from' => $request->input('mzarat.travel_from'),
                        'travel_to' => $request->input('mzarat.travel_to'),
                        'travel_date' => $request->input('mzarat.travel_date'),
                        'travel_time' => $request->input('mzarat.travel_time'),
                        'transport_time' => $request->input('mzarat.transport_time'),
                        'transport_company' => $request->input('mzarat.transport_company'),
                    ]);
                }
            }

            // Handle Departure Section if data is provided
            if ($request->has('departure')) {
                $departure = $request->input('departure');

                // Check if any value in the 'movement' array is not empty
                $hasDepartureData = collect($departure)->filter(function ($value) {
                    return !empty($value);
                })->isNotEmpty();

                if ($hasDepartureData) {
                    DepartureSection::create([
                        'operational_information_id' => $operationalInfo->id,
                        'operational_id' => $request->input('operational_id'),
                        'departure_date' => $request->input('departure.departure_date'),
                        'departure_time' => $request->input('departure.departure_time'),
                        'travel_from' => $request->input('departure.travel_from'),
                        'travel_to' => $request->input('departure.travel_to'),
                        'departure_flight_no' => $request->input('departure.departure_flight_no'),
                        'terminal_name' => $request->input('departure.terminal_name'),
                        'transport_time' => $request->input('departure.transport_time'),
                        'transport_company' => $request->input('departure.transport_company'),
                    ]);
                }
            }

            // Commit the transaction if all is successful
            DB::commit();

            return redirect()->to('data-list')->with('success', 'Operational Information Added Successfully!');

        } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();

                return response()->json(['success' => false, 'message' => 'An error occurred. ' . $e->getMessage()]);
            }
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

            $groupNumbers = explode(',', $request->input('group_numbers')); // Split comma-separated values into an array

            // Update the operational information
            $operationalInfo->update([
                'operational_id' => $request->input('operational_id'),
                'agent_id' => $request->input('agent_id'),
                'people_quantity' => $request->input('people_quantity'),
                'voucher_number' => $request->input('voucher_number'),
                'nationality' => $request->input('nationality'),
                'group_leader_name' => $request->input('group_leader_name'),
                'group_leader_number' => $request->input('group_leader_number'),
                'group_numbers' => json_encode($groupNumbers),

            ]);

            // Handle new images using a batch insert for efficiency
            if ($request->hasFile('images')) {
                $imageData = [];

                foreach ($request->file('images') as $image) {
                    $path = $image->store('operations/images', 'public');
                    $imageData[] = [
                        'image_path' => $path,
                        'operational_id' => $operationalInfo->operational_id,
                        'operation_information_id' => $operationalInfo->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert all image data at once
                DB::table('operation_images')->insert($imageData);
            }

            // Optionally handle removing existing images (e.g., via request input)
            if ($request->has('remove_images')) {
                if($operationalInfo->status == 1) {
                    $deletedImages = OperationImage::whereIn('id', $request->remove_images)->get();

                    foreach ($deletedImages as $image) {
                        AuditLog::create([
                            'section' => 'Images',
                            'record_id' => $image->id,
                            'operational_id' => $image->operational_id,
                            'user_id' => Auth::id(),
                            'field' => 'image_path',
                            'old_value' => $image->image_path,
                            'new_value' => null,
                        ]);
                    }
                }
                OperationImage::whereIn('id', $request->remove_images)->delete();
            }

            // Update Arrival Section
            if ($request->has('arrival')) {
                $arrivalData = $request->input('arrival');
                ArrivalSection::updateOrCreate(
                    ['operational_information_id' => $operationalInfo->id],
                    [
                        'operational_id' => $request->input('operational_id'),
                        'arrival_date' => $arrivalData['arrival_date'],
                        'arrival_time' => $arrivalData['arrival_time'],
                        'travel_from' => $arrivalData['travel_from'],
                        'travel_to' => $arrivalData['travel_to'],
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
                            'travel_from' => $departureData['travel_from'],
                            'travel_to' => $departureData['travel_to'],
                            'departure_flight_no' => $departureData['departure_flight_no'],
                            'terminal_name' => $departureData['terminal_name'],
                            'transport_time' => $departureData['transport_time'],
                            'transport_company' => $departureData['transport_company'],
                        ]
                    );
                }
            }

            if ($request->has('comments')) {
                if (!empty(trim($request->input('comments', '')))) {
                    EditComment::updateOrCreate(
                        ['operational_id' => $request->input('operational_id')],
                        [
                            'comments' => $request->input('comments'),
                            'added_by' => Auth::id(),
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
        $operation = OperationInformation::findOrFail($id);

        // Toggle status (e.g., 0 -> 1 or 1 -> 0)
        $operation->status = $operation->status == 0 ? 1 : 0;

        // Save the changes
        $operation->save();

        // Return a success response or redirect back
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function filterData(Request $request)
    {
        // Get the selected district and tehsil ID from the request
        $selectedDistrict = $request->input('district_id');
        $selectedTehsil = $request->input('tehsil_id');
        $selectedMarkaz = $request->input('markaz_id');
        $selectedSchool = $request->input('school_id');
        $selectedType = $request->input('teacher_type');
        $selectedQualification = $request->input('teacher_qualification');

        // Retrieve tehsils based on the selected district
        $tehsils = [];
        if ($selectedDistrict && $selectedDistrict != 0) {
            $tehsils = User::select('t_name')->distinct('t_name')->where('d_name', $selectedDistrict)->orderBy('t_name')->get();
        }

        // Retrieve markaz based on the selected tehsil
        $markaz = [];
        if ($selectedTehsil && $selectedTehsil != 0) {
            $markaz = User::select('m_name')->distinct('m_name')->where('t_name', $selectedTehsil)->orderBy('m_name')->get();
        }

        $schools = [];
        if ($selectedMarkaz && $selectedMarkaz != 0) {
            $schools = User::select('s_emis_code','s_name')->distinct('s_emis_code')->where('m_name', $selectedMarkaz)->get();
        }


        return view('dashboard.index', [
            'tehsils' => $tehsils,
            'markaz' => $markaz,
            'schools' => $schools,
            'selectedDistrict' => $selectedDistrict,
            'selectedTehsil' => $selectedTehsil,
            'selectedMarkaz' => $selectedMarkaz,
            'selectedSchool' => $selectedSchool,
            'selectedType' => $selectedType,
            'selectedQualification' => $selectedQualification,
        ]);
    }

    public function ministerFilterData(Request $request)
    {
        // Get the selected district and tehsil ID from the request
        $selectedDistrict = $request->input('district_id');
        $selectedTehsil = $request->input('tehsil_id');
        $selectedType = $request->input('teacher_type');
        $selectedDate = $request->input('date');

        // Retrieve tehsils based on the selected district
        $tehsils = [];
        if ($selectedDistrict && $selectedDistrict != 0) {
            $tehsils = User::select('t_name')->distinct('t_name')->where('d_name', $selectedDistrict)->orderBy('t_name')->get();
        }

        // Prepare the procedure call with parameters
        $procedureCall = 'CALL get_info(?,?,?,?)';

        $parameters = [$selectedDistrict, $selectedTehsil, $selectedType, $selectedDate];
        $pdo = DB::connection('mysql')->getPdo();
        $statement = $pdo->prepare($procedureCall);
        $statement->execute($parameters);

        // Fetch the first result set
        $count_data = $statement->fetchAll();
        $statement->closeCursor();

        $teachers = User::where('is_submitted', 'yes')
            ->when($selectedDistrict, function ($query, $selectedDistrict) {
                return $query->where('d_name', $selectedDistrict);
            })
            ->when($selectedTehsil, function ($query, $selectedTehsil) {
                return $query->where('t_name', $selectedTehsil);
            })
            ->whereHas('answer', function ($query) {
                $query->whereDate('created_at', now()->toDateString()); // Filters answers created today
            })
            ->with('answer')
            ->get();

        $todayAttempts = DB::table('answers')
            ->whereDate('created_at', now()->toDateString()) // Filter records created today
            ->distinct('cnic') // Ensure only distinct 'cnic' values are counted
            ->count('cnic');

        $todayInterviews = DB::table('interview_results')
            ->whereDate('created_at', now()->toDateString()) // Filter records created today
            ->distinct('teacher_cnic') // Ensure only distinct 'cnic' values are counted
            ->count('teacher_cnic');

        $todayActive = DB::table('teacher_activation')
            ->whereDate('created_at', now()->toDateString()) // Filter records created today
            ->distinct('teacher_cnic') // Ensure only distinct 'cnic' values are counted
            ->count('teacher_cnic');

        return view('dashboard.ministerDashboard', [
            'tehsils' => $tehsils,
            'selectedDistrict' => $selectedDistrict,
            'selectedTehsil' => $selectedTehsil,
            'selectedType' => $selectedType,
            'selectedDate' => $selectedDate,
            'count_data' => $count_data[0],
            'teachers' => $teachers,
            'todayAttempts' => $todayAttempts,
            'todayInterviews' => $todayInterviews,
            'todayActive' => $todayActive,
        ]);
    }

    public function districtList(Request $request)
    {
        return view('dashboard.district_list');
    }

    public function schoolList(Request $request)
    {
        return view('dashboard.school_list');
    }

    public function teacherList(Request $request)
    {
        return view('dashboard.teacher_list');
    }

    public function interviewerTeacherList(Request $request)
    {
        $tehsil = Auth::user()->tehsil;
        $teachers = User::where('t_name', $tehsil)->where('is_submitted', 'yes')->with('interview')->get();
        return view('dashboard.interviewer_teacher_list', compact('teachers'));
    }
    public function invigilatorTeacherList(Request $request)
    {
        $cnic = Auth::user()->cnic;
        $activated_teachers = TeacherActivation::where('invigilator_cnic', $cnic)->with('teacher')->get();
        return view('dashboard.invigilator_teacher_list', compact('activated_teachers'));
    }

    public function interviewForm(Request $request, $cnic)
    {
        $user = Auth::user();
        $tehsil = Auth::user()->tehsil;


        $teacher = User::where('st_cnic', $cnic)->where('t_name', $tehsil)->where('is_submitted', 'yes')->first();
        if($teacher){

            SearchLogs::create([
                'user_cnic' => $user->cnic,
                'search_cnic' => $cnic,
                'search_at' => now(),
                'search_status' => 'success',
            ]);

            $count = InterviewResult::where('teacher_cnic', $cnic)->count();
            if($count >= 2){
                return redirect()->intended('interviewer-teacher-list')->with('error', 'Already 2 interviews conducted this teacher');
            }else{
                $result = InterviewResult::where('teacher_cnic', $cnic)->where('interviewer_cnic', Auth::user()->cnic)->first();

                return view('dashboard.interview_form', compact('teacher', 'result'));
            }
        }

        SearchLogs::create([
            'user_cnic' => $user->cnic,
            'search_cnic' => $cnic,
            'search_at' => now(),
            'search_status' => 'wrong CNIC',
        ]);

        return redirect()->intended('interviewer-teacher-list')->with('error', 'Teacher Not Exist');
    }

    public function invigilatorForm(Request $request, $cnic)
    {
        $i_cnic = Auth::user()->cnic;

        $teacher = User::where('st_cnic', $cnic)->first();
        if($teacher){
            if($teacher->is_active == 0) {
                $activated_teachers = TeacherActivation::where('invigilator_cnic', $i_cnic)->with('teacher')->get();
                return view('dashboard.invigilator_teacher_list', compact('teacher', 'activated_teachers'));
            }else{
                return redirect()->intended('invigilator-teacher-list')->with('error', 'Teacher Already Activated');
            }
        }

        return redirect()->intended('invigilator-teacher-list')->with('error', 'Teacher Not Exist');
    }

    public function submitInterviewForm(Request $request)
    {
        $teacher_cnic = $request->input('teacher_cnic');
        $interviewer_cnic = Auth::user()->cnic;

        $result = InterviewResult::where('teacher_cnic', $teacher_cnic)->where('interviewer_cnic', $interviewer_cnic)->first();
        if($result){
            return redirect()->intended('interviewer-teacher-list')->with('error', 'You have already submitted form of this teacher');
        }

        $count = InterviewResult::where('teacher_cnic', $teacher_cnic)->count();

        if($count >= 2){
            return redirect()->intended('interviewer-teacher-list')->with('error', 'Already 2 interviews conducted this teacher');
        }

        // Store the evaluation in the database
        $evaluation = new InterviewResult();
        $evaluation->teacher_cnic = $teacher_cnic;
        $evaluation->interviewer_cnic = $interviewer_cnic;
        $evaluation->attendance = $request->input('attendance');
        $evaluation->verbal_skills = $request->input('communication_skills');
        $evaluation->non_verbal_skills = $request->input('non_communication_skills');
        $evaluation->proffessional_attire = $request->input('professional_attire');
        $evaluation->teaching_aids = $request->input('teaching_aids');
        $evaluation->interview_date = now();

        // Save the evaluation to the database
        $evaluation->save();

        User::where('st_cnic', $teacher_cnic)
            ->increment('interview_status', 1);

        return redirect()->intended('interviewer-teacher-list')->with('success', 'Interview Form Submitted');
    }

    public function activateTeacherLogin(Request $request)
    {
        $teacher_cnic = $request->input('teacher_cnic');

        User::where('st_cnic', $teacher_cnic)
            ->where('is_active', 0) // Check if is_active is 0
            ->update(['is_active' => 1]);

        TeacherActivation::updateOrCreate(
            ['teacher_cnic' => $teacher_cnic], // Condition to check if the record exists
            [
                'invigilator_cnic' => Auth::user()->cnic,
                'activated_at' => now(),
            ]);
        return redirect()->intended('invigilator-teacher-list')->with('success', 'Teacher login Activated');
    }

    public function rankingDetail(Request $request)
    {
        return view('dashboard.ranking_detail');
    }

    public function teacherAppeared(Request $request)
    {
        return view('dashboard.teacher_appeared');
    }
}
