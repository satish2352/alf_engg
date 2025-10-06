<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeePlantAssignment;
use App\Models\Projects;

class EmpDashboardController extends Controller
{
	
	public function index(Request $req)
	{
		
		$employeeId = session('emp_user_id');
		$plantId = session('emp_plant_id');

		$assignments = EmployeePlantAssignment::where('employee_id', $employeeId)
			->where('plant_id', $plantId)
			->where('is_active', 1)
			->where('is_deleted', 0)
			->get();

		$projectIds = [];
		foreach ($assignments as $assignment) {
			if($assignment->projects_id) {
				$projectIds = array_merge($projectIds, explode(',', $assignment->projects_id));
			}
		}

		$projectIds = array_unique($projectIds);

		$projects = Projects::whereIn('id', $projectIds)
			->where('is_active', 1)
			->where('plant_id', $plantId)
			->get();

		// 🔹 Attach emp_code and financial_year_id to each project
		foreach ($projects as $project) {
			$project->emp_code = session('emp_code');
			$project->financial_year_id = session('emp_financial_year_id');
		}

		return view('dashboard.emp-dashboard', compact('projects'));
	}


    // public function index(Request $req)
    // {
    //     // $employeeId = session('user_id');
    //     // $plantId = session('plant_id');

	// 	$employeeId = session('emp_user_id');
	// 	$plantId = session('emp_plant_id');


    //     // 1️⃣ Get active employee plant assignments
    //     $assignments = EmployeePlantAssignment::where('employee_id', $employeeId)
    //         ->where('plant_id', $plantId)
    //         ->where('is_active', 1)
    //         ->where('is_deleted', 0)
    //         ->get();

    //     $projectIds = [];
    //     foreach ($assignments as $assignment) {
    //         if($assignment->projects_id) {
    //             $projectIds = array_merge($projectIds, explode(',', $assignment->projects_id));
    //         }
    //     }

    //     // Remove duplicates
    //     $projectIds = array_unique($projectIds);

    //     // 2️⃣ Fetch project details
    //     $projects = Projects::whereIn('id', $projectIds)
    //         ->where('is_active', 1)
    //         ->where('plant_id', $plantId)
    //         ->get();

    //     return view('dashboard.emp-dashboard', compact('projects'));
    // }
}
