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

class AgentController extends Controller
{
    public function agentList(Request $request)
    {
        $agents = Agent::get();

        return view('agent.list', compact('agents'));
    }

    public function showForm(Request $request)
    {
        return view('agent.form');
    }

    public function addAgent(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'agent_name' => 'required|string|max:255',
            'agent_number' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the agent
        $agent = Agent::create([
            'agent_name' => $request->agent_name,
            'agent_number' => $request->agent_number,
            'status' => 1, // Default status
        ]);

        return redirect()->to('agent-list')->with('success', 'Agent added successfully!');
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
