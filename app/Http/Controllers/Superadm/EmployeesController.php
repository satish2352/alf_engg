<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\EmployeesService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use App\Models\
{
	PlantMasters,
	Departments,
	Designations,
	Roles,
	Projects,
	Employees,
}
;
use Exception;

class EmployeesController extends Controller
{
	protected $service;
	function __construct()
	{
		$this->service = new EmployeesService();
	}


	public function index()
	{
		try {
			$employees = $this->service->list();
			return view('superadm.employees.list', compact('employees'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function create(Request $req)
	{
		try {
			$plants = PlantMasters::where('is_deleted', 0)
				->where('is_active', 1)
				->orderBy('id', 'desc')
				->get();

			$departments = Departments::where('is_deleted', 0)
				->where('is_active', 1)
				->orderBy('id', 'desc')
				->get();

			$designations = Designations::where('is_deleted', 0)
				->where('is_active', 1)
				->orderBy('id', 'desc')
				->get();

			$roles = Roles::where('is_deleted', 0)
				->where('is_active', 1)
				->orderBy('id', 'desc')
				->get();

	

			// $projects = Projects::where('is_deleted', 0)
			// 	->where('is_active', 1)
			// 	->orderBy('id', 'desc')
			// 	->get();

			return view('superadm.employees.create', compact('plants', 'departments', 'designations', 'roles'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function save(Request $req)
	{

		$req->validate([

			'plant_id' => 'required',
			'department_id' => 'required',
			'projects_id' => 'required',
			'designation_id' => 'required',
			'role_id' => 'required',
			'employee_code'      => 'required|string|max:50|unique:employees,employee_code',
			'employee_name'      => 'required|string|max:255',
			'employee_type'      => 'required|string',
			'employee_email'     => 'required|email|max:255|unique:employees,employee_email',
			'employee_user_name' => 'required|string|max:100|unique:employees,employee_user_name',
			'employee_password'  => 'required|string|min:6|max:15',
			// 'reporting_to' => 'required',


		], [
			'plant_id.required' => 'Select plant',
			'department_id.required' => 'Select department',
			'projects_id.required' => 'Select projects',
			'designation_id.required' => 'Select designation',
			'role_id.required' => 'Select role',
			'employee_code.required' => 'Enter employee code',
			'employee_code.unique'   => 'This employee code already exists',
			'employee_name.required' => 'Enter employee name',
			'employee_type.required' => 'Select employee type',
			'employee_email.required' => 'Enter employee email',
			'employee_email.email'    => 'Enter a valid email address',
			'employee_email.unique'   => 'This email is already registered',
			'employee_user_name.required' => 'Enter username',
			'employee_user_name.unique'   => 'This username is already taken',
			'employee_password.required' => 'Enter employee password',
			'employee_password.min'      => 'Password must be at least 6 characters',
			'employee_password.max'      => 'Password cannot exceed 15 characters',
			// 'reporting_to.required' => 'Select reporting to name',
		]);

		try {
			$this->service->save($req);
			return redirect()->route('employees.list')->with('success', 'Department added successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}

	}

	public function edit($id)
	{

		$employee = Employees::findOrFail($id);
		$plants = PlantMasters::all();
		$departments = Departments::all();
		$projects = Projects::all();
		$designations = Designations::all();
		$roles = Roles::all();

		return view('superadm.employees.edit', compact(
			'employee',
			'plants',
			'departments',
			'projects',
			'designations',
			'roles'
		));
	}


	public function delete(Request $req)
	{
		try {
			$req->validate([
				'id' => 'required',
			], [
				'id.required' => 'ID required'
			]);

			$this->service->delete($req);
			return redirect()->route('employees.list')->with('success', 'Department deleted successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to delete role: ' . $e->getMessage());
		}
	}

	public function update(Request $req, $id)
	{

		$req->validate([

			'plant_id' => 'required',
			'department_id' => 'required',
			'projects_id' => 'required',
			'designation_id' => 'required',
			'role_id' => 'required',
		    'employee_code'      => 'required|string|max:50',
			'employee_name'      => 'required|string|max:255',
			'employee_type'      => 'required|string',
			'employee_email'     => 'required|email|max:255',
			'employee_user_name' => 'required|string|max:100',
			'employee_password'  => 'required|string|min:6|max:15',
			'reporting_to' => 'required',

			// 'id' => 'required',
			// 'is_active' => 'required'


		], [
			'plant_id.required' => 'Select plant',
			'department_id.required' => 'Select department',
			'projects_id.required' => 'Select projects',
			'designation_id.required' => 'Select designation',
			'role_id.required' => 'Select role',
		    'employee_code.required' => 'Enter employee code',
			// 'employee_code.unique'   => 'This employee code already exists',
			'employee_name.required' => 'Enter employee name',
			'employee_type.required' => 'Select employee type',
			'employee_email.required' => 'Enter employee email',
			'employee_email.email'    => 'Enter a valid email address',
			// 'employee_email.unique'   => 'This email is already registered',
			'employee_user_name.required' => 'Enter username',
			// 'employee_user_name.unique'   => 'This username is already taken',
			'employee_password.required' => 'Enter employee password',
			'employee_password.min'      => 'Password must be at least 6 characters',
			'employee_password.max'      => 'Password cannot exceed 15 characters',

			// 'id.required' => 'ID required',
			// 'is_active.required' => 'Select active or inactive required'
		]);
		

		try {
			$this->service->update($req, $id);
			return redirect()->route('employees.list')->with('success', 'Employee updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}


	public function listajaxlist(Request $req)
	{
		try {
			$employees = $this->service->listajaxlist($req);
			return response()->json(['employees' => $employees]);

		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}


	public function updateStatus(Request $req)
	{
		try {
			$this->service->updateStatus($req);
			
			return redirect()->route('employees.list')->with('success', 'Employees status updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
		}
	}

}
