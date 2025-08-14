<?php
namespace App\Http\Controllers\Superadm\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\ServiceAll\Superadm\Role\RoleService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;


//Dashboard
class RoleController extends Controller
{
	function __construct()
	{
		$this->serviceAll=new RoleService();
	}

	public function index()
	{
    	$roles=$this->serviceAll->list();
    	return view('superadm.role.list',compact('roles'));
	}

	public function create(Request $req)
	{
		return view('superadm.role.create');

	}

	public function save(Request $req)
	{
		$this->validateData($req);
		$this->serviceAll->save($req);
		return redirect()->route('roles.list')->with('success', 'Role added successfully.');


	}

	public function edit($encodedId)
	{
		$id = base64_decode($encodedId);

		$data = $this->serviceAll->edit($id);
		return view('superadm.role.edit',compact('data','encodedId'));

	}

	public function update(Request $req)
	{

		$req->validate([
			'role' => [
				'required',
				Rule::unique('roles', 'role')
					->where(fn ($query) => $query->where('is_active', 1))
					->ignore($req->id),
			],
			'id' => 'required',
			'is_active' => 'required'
		], [
			'role.required' => 'Enter Role Name',
			'role.unique'   => 'This role already exists.',
			'id.required' => 'ID required',
			'is_active.required' => 'Select active or in active required'
		]);

		$this->serviceAll->update($req);
		return redirect()->route('roles.list')->with('success', 'Role updated successfully.');


	}


	public function delete(Request $req)
	{

		$req->validate([
			'id' => 'required'
		], [
			'id.required' => 'ID required'
		]);

		$this->serviceAll->delete($req);
		return redirect()->route('roles.list')->with('success', 'Role deleted successfully.');

	}

	public function validateData(Request $req)
	{
		 $req->validate([
			'role' => [
				'required',
				Rule::unique('roles', 'role')->where(function ($query) {
					return $query->where('is_active', 1);
				}),
			],
		], [
			'role.required' => 'Enter Role Name',
			'role.unique'   => 'This role already exists.',
		]);

	}
}

