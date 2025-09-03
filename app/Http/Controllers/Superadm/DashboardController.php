<?php
namespace App\Http\Controllers\Superadm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Service\Superadm\DashboardService;
use App\Models\{
	Roles,
	Designations,
	PlantMasters,
	Employees,
	Projects,
	Departments
};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	function __construct()
	{
		// $this->service=new DashboardService();
	}

	public function index(Request $req)
	{

		if ($req->session()->get('role_id') == 0) {
			$allSessions = $req->session()->all();
			// dd($allSessions); // dumps and stops execution

			$allRoles = Roles::where('is_deleted', 0)->count();
			$allDesignations = Designations::where('is_deleted', 0)->count();
			$allPlants = PlantMasters::where('is_deleted', 0)->count();
			$allProjects = Projects::where('is_deleted', 0)->count();
			$allDepartments = Departments::where('is_deleted', 0)->count();
			$allEmployees = Employees::where('is_deleted', 0)->count();
			return view('dashboard.dashboard', compact(
				'allRoles',
				'allDesignations',
				'allPlants',
				'allProjects',
				'allDepartments',
				'allEmployees'
			));
		} else {

			$projects = Employees::leftJoin('projects', function ($join) {
				$join->on(DB::raw("FIND_IN_SET(projects.id, employees.projects_id)"), ">", DB::raw("0"));
			})
				->where('employees.is_deleted', 0)
				->where('employees.id', session('user_id'))
				->select(
					'employees.*',
					'projects.id as project_id',
					'projects.project_name',
					'projects.project_description',
					'projects.project_url'
				)
				->get();


			$result = $projects->groupBy('id')->map(function ($rows) {
				$emp = $rows->first();
				return [
					'id' => $emp->id,
					'employee_name' => $emp->employee_name,
					'employee_email' => $emp->employee_email,

					'projects' => $rows->map(function ($r) {
						return [
							'id' => $r->project_id,
							'name' => $r->project_name,
							'description' => $r->project_description,
							'url' => $r->project_url,
						];
					})->values()->toArray(),
				];
			})->first();
			return view('dashboard.emp-dashboard', compact('projects'));
		}
	}
}
