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
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AvailabilityController extends Controller
{
    protected $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function getNextSlot($addressId, $appointmentTypeId)
    {
        $slot = $this->availabilityService->getNextDisponibilite($addressId, $appointmentTypeId);

        return $slot ? response()->json(['next_slot' => $slot])
            : response()->json(['message' => 'No availability found'], 404);
    }

}
