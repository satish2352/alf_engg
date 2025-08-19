<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\ProjectsService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Exception;

class ProjectsController extends Controller
{
	protected $service;
	function __construct()
	{
		$this->service = new ProjectsService();
	}

	public function index()
	{
		try {
			$dataAll = $this->service->list();
			return view('superadm.projects.list', compact('dataAll'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function create(Request $req)
	{
		try {
			return view('superadm.projects.create');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function save(Request $req)
	{

		$req->validate([
			'project_name' => [
				'required',
				Rule::unique('projects', 'project_name')->where(function ($query) {
					return $query->where('is_deleted', 0);
				}),
			],
			'project_url' => [
				'required',
				Rule::unique('projects', 'project_url')->where(function ($query) {
					return $query->where('is_deleted', 0);
				}),
			],
			'project_description' => 'required',

			
		], [
			'project_name.required' => 'Enter project name',
			'project_name.unique' => 'This project name already exists.',

			'project_url.required' => 'Enter project url',
			'project_url.unique' => 'This project url already exists.',

			'project_description.required' => 'Project short description required.',
		]);

		try {
			$this->service->save($req);
			return redirect()->route('projects.list')->with('success', 'Project added successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}

	}

	public function edit($encodedId)
	{
		try {
			$id = base64_decode($encodedId);
			$data = $this->service->edit($id);
			return view('superadm.projects.edit', compact('data', 'encodedId'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function update(Request $req)
	{
		$req->validate([
			'project_name' => [
				'required',
				Rule::unique('projects', 'project_name')
					->where(fn($query) => $query->where('is_deleted', 0))
					->ignore($req->id),

			],
			'project_url' => [
				'required',
				Rule::unique('projects', 'project_url')
					->where(fn($query) => $query->where('is_deleted', 0))
					->ignore($req->id),
			],
			'project_description' => 'required',
			'id' => 'required',
			'is_active' => 'required'
		], [
			'project_name.required' => 'Enter project name',
			'project_name.unique' => 'This project name already exists.',

			'project_url.required' => 'Enter project url',
			'project_url.unique' => 'This project url already exists.',

			'project_description.required' => 'Project short description required.',
			'id.required' => 'ID required',
			'is_active.required' => 'Select active or inactive required'
		]);

		try {
			$this->service->update($req);
			return redirect()->route('projects.list')->with('success', 'Project updated successfully.');
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
			return redirect()->route('projects.list')->with('success', 'Project deleted successfully.');
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
			return redirect()->route('projects.list')->with('success', 'Project status updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
		}
	}
}
