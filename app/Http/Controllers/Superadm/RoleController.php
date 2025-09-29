<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\RoleService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Exception;

class RoleController extends Controller
{
	protected $service;
	function __construct()
	{
		$this->service = new RoleService();
	}

	public function index()
	{
		try {
			$roles = $this->service->list();
			return view('superadm.role.list', compact('roles'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function create(Request $req)
	{
		try {
			return view('superadm.role.create');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function save(Request $req)
	{

		$req->validate([
			'role' => [
				'required',
				'max:255',
				'regex:/^[a-zA-Z0-9\s]+$/',
				Rule::unique('roles', 'role')->where(function ($query) {
					return $query->where('is_deleted', 0);

				}),
			],
			 'short_description' => 'required|max:255',
		], [
			'role.required' => 'Enter Role Name',
			'role.regex' => 'Role must contain only letters, numbers, and spaces.',
			'role.unique' => 'This role already exists.',
			'role.max' => 'Role name must not exceed 255 characters.',
			'short_description.required' => 'Enter Description',
			 'short_description.max' => 'Description must not exceed 255 characters.',
		]);

		try {
			$this->service->save($req);
			return redirect()->route('roles.list')->with('success', 'Role added successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}

	}

	public function edit($encodedId)
	{
		try {
			$id = base64_decode($encodedId);
			$data = $this->service->edit($id);
			return view('superadm.role.edit', compact('data', 'encodedId'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function update(Request $req)
	{
		$req->validate([
			'role' => [
				'required',
				'max:255',
				Rule::unique('roles', 'role')
					->where(fn($query) => $query->where('is_deleted', 0))
					->ignore($req->id),
			],
			 'short_description' => 'required|max:255',
			'id' => 'required',
			'is_active' => 'required'
		], [
			'role.required' => 'Enter Role Name',
			'role.unique' => 'This role already exists.',
			'role.max' => 'Role name must not exceed 255 characters.',
			'id.required' => 'ID required',
			'is_active.required' => 'Select active or inactive required',
			'short_description.required' => 'Enter Description',
			'short_description.max' => 'Description must not exceed 255 characters.',
		]);

		try {
			$this->service->update($req);
			return redirect()->route('roles.list')->with('success', 'Role updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}


	// public function delete(Request $req)
	// {
	// 	try {
	// 		$req->validate([
	// 			'id' => 'required',
	// 		], [
	// 			'id.required' => 'ID required'
	// 		]);

	// 		$this->service->delete($req);
	// 		return redirect()->route('roles.list')->with('success', 'Role deleted successfully.');
	// 	} catch (Exception $e) {
	// 		return redirect()->back()->with('error', 'Failed to delete role: ' . $e->getMessage());
	// 	}
	// }

	public function delete(Request $req)
	{
		try {
			$req->validate([
				'id' => 'required',
			], [
				'id.required' => 'ID required'
			]);

			$this->service->delete($req);
			return redirect()->route('roles.list')->with('success', 'Role deleted successfully.');
		} catch (Exception $e) {
			// Show the custom message if role is assigned to employees
			return redirect()->back()->with('error', $e->getMessage());
		}
	}

	public function validateData(Request $req)
	{

	}

	public function updateStatus(Request $req)
	{
		try {
			$this->service->updateStatus($req);
			return redirect()->route('roles.list')->with('success', 'Role status updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
		}
	}
}
