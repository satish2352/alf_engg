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

public function sendApi(Request $request)
{
    try {
        $request->validate([
            'id' => 'required',
            'projects' => 'required|array|min:1',
        ]);

        return $this->service->sendApi($request);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
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
				// 'regex:/^[a-zA-Z0-9\s]+$/',
				Rule::unique('roles', 'role')->where(function ($query) {
					return $query->where('is_deleted', 0);

				}),
			],
			 'short_description' => 'required|max:255',
		], [
			'role.required' => 'Enter Role Name',
			// 'role.regex' => 'Role Must Contain Only Letters, Numbers, And Spaces.',
			'role.unique' => 'This Role Already Exists.',
			'role.max' => 'Role Name Must Not Exceed 255 Characters.',
			'short_description.required' => 'Enter Description',
			 'short_description.max' => 'Description Must Not Exceed 255 Characters.',
		]);

		try {
			$this->service->save($req);
			return redirect()->route('roles.list')->with('success', 'Role Added Successfully.');
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
			'role.unique' => 'This Role Already Exists.',
			'role.max' => 'Role Name Must Not Exceed 255 Characters.',
			'id.required' => 'ID Required',
			'is_active.required' => 'Select Active Or Inactive Required',
			'short_description.required' => 'Enter Description',
			'short_description.max' => 'Description Must Not Exceed 255 Characters.',
		]);

		try {
			    // FORCE SEND API = 0
				$req->merge([
					'send_api' => 0,
					'send_api_project_id' => null   // You can reset this also (same plant logic)
				]);
				
			$this->service->update($req);
			return redirect()->route('roles.list')->with('success', 'Role Updated Successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}



	public function delete(Request $req)
	{
		try {
			$req->validate([
				'id' => 'required',
			]);

			return $this->service->delete($req);

		} catch (Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}
	}


	public function validateData(Request $req)
	{

	}


	public function updateStatus(Request $request)
	{
		try {
			return $this->service->updateStatus($request);
		} catch (Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Failed to update status: ' . $e->getMessage()
			], 500);
		}
	}


}
