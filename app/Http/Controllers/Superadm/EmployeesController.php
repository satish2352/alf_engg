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
	Projects
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
			$dataAll = $this->service->list();
			return view('superadm.employees.list', compact('dataAll'));
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
				
			return view('superadm.employees.create', compact('plants', 'departments','designations','roles'));
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
			'employee_code' => 'required',
			'employee_name' => 'required',
			'employee_type' => 'required',
			'employee_email' => 'required',
			'employee_user_name' => 'required',
			'employee_password' => 'required',


		], [
			'plant_id.required' => 'plant_id required',
			'department_id.required' => 'department_id required',
			'projects_id.required' => 'projects_id required',
			'designation_id.required' => 'designation_id required',
			'role_id.required' => 'role_id required',
			'employee_code.required' => 'employee_code required',
			'employee_name.required' => 'employee_name required',
			'employee_type.required' => 'employee_type required',
			'employee_email.required' => 'employee_email required',
			'employee_user_name.required' => 'employee_user_name required',
			'employee_password.required' => 'employee_password required',
		]);

		try {
			$this->service->save($req);
			return redirect()->route('employees.list')->with('success', 'Department added successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}

	}

	public function edit($encodedId)
	{
		try {

			$plants = PlantMasters::where('is_deleted', 0)
				->where('is_active', 1)
				->orderBy('id', 'desc')
				->get();


			$id = base64_decode($encodedId);
			$data = $this->service->edit($id);
			return view('superadm.employees.edit', compact('data', 'plants', 'encodedId'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function update(Request $req)
	{
		$req->validate([
			'plant_id' => 'required',
			'department_id' => 'required', 
			'projects_id' => 'required', 
			'designation_id' => 'required',
			'role_id' => 'required',
			'employee_code' => 'required',
			'employee_name' => 'required',
			'employee_type' => 'required',
			'employee_email' => 'required',
			'employee_user_name' => 'required',
			'employee_password' => 'required',

			'id' => 'required',
			'is_active' => 'required'
		], [
			'plant_id.required' => 'plant_id required',
			'department_id.required' => 'department_id required',
			'projects_id.required' => 'projects_id required',
			'designation_id.required' => 'designation_id required',
			'role_id.required' => 'role_id required',
			'employee_code.required' => 'employee_code required',
			'employee_name.required' => 'employee_name required',
			'employee_type.required' => 'employee_type required',
			'employee_email.required' => 'employee_email required',
			'employee_user_name.required' => 'employee_user_name required',
			'employee_password.required' => 'employee_password required',
			'id.required' => 'ID required',
			'is_active.required' => 'Select active or inactive required'
		]);

		try {
			$this->service->update($req);
			return redirect()->route('employees.list')->with('success', 'Department updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
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

	public function validateData(Request $req)
	{

	}

	public function updateStatus(Request $req)
	{
		try {
			$this->service->updateStatus($req);
			return redirect()->route('employees.list')->with('success', 'Department status updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
		}
	}
}
