<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\PlantMasterService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Exception;

class PlantMasterController extends Controller
{
	protected $service;
	function __construct()
	{
		$this->service = new PlantMasterService();
	}

	public function index()
	{
		try {
			$data_all = $this->service->list();
			return view('superadm.plantmaster.list', compact('data_all'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function create(Request $req)
	{
		try {
			return view('superadm.plantmaster.create');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function save(Request $req)
	{

	$req->validate([
    'plant_code' => [
        'required',
        'max:255',
        Rule::unique('plant_masters', 'plant_code')->where(function ($query) {
            return $query->where('is_deleted', 0);
        }),
    ],
		'plant_name' => [
				'required',
				'max:255',
				'max:255',
				'regex:/^[a-zA-Z0-9\s]+$/',
				Rule::unique('plant_masters', 'plant_name')->where(function ($query) {
					return $query->where('is_deleted', 0);
				}),
			],
    'address' => 'required',
    'city' => [
            'required',
            'regex:/^[a-zA-Z\s]+$/', // only letters and spaces
        ],
    'plant_short_name' => 'nullable|max:255', 
], [
    'plant_code.required' => 'Enter plant code',
    'plant_code.unique' => 'This plant code already exists.',
    'plant_code.max' => 'Plant code must not exceed 255 characters.',
    'plant_name.required' => 'Enter plant name',
    'plant_name.max' => 'Plant name must not exceed 255 characters.',
     'plant_name.regex' => 'Plant Name must contain only letters, numbers, and spaces.',
    'address.required' => 'Enter address for plant',
   'city.required' => 'Enter city for plant',
        'city.regex' => 'City name must contain only letters and spaces.',

    'plant_short_name.required' => 'Enter plant short name',
]);


		try {
			$this->service->save($req);
			return redirect()->route('plantmaster.list')->with('success', 'Plant details added successfully.');
		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}

	}

	public function edit($encodedId)
	{
		try {
			$id = base64_decode($encodedId);
			$data = $this->service->edit($id);
			return view('superadm.plantmaster.edit', compact('data', 'encodedId'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function update(Request $req)
	{
		$req->validate([
			'plant_code' => [
				'required',
				Rule::unique('plant_masters', 'plant_code')
					->where(fn($query) => $query->where('is_deleted', 0))
					->ignore($req->id),
			],
			'plant_name' => 'required',
			'address' => 'required',
			 'city' => [
            'required',
            'regex:/^[a-zA-Z\s]+$/', // only letters and spaces
        ],
			'plant_short_name' => 'required',
			'id' => 'required',
			'is_active' => 'required'

		], [
			'plant_code.required' => 'Enter plant code',
			'plant_code.unique' => 'This plant code already exists.',
			'id.required' => 'ID required',
			'plant_name.required' => 'Enter plant name',
			'address.required' => 'Enter address for plant',
			 'city.required' => 'Enter city for plant',
        'city.regex' => 'City name must contain only letters and spaces.',
			'plant_short_name.required' => 'Enter plant short name',
			'is_active.required' => 'Select active or inactive required'
		]);

		try {
			$this->service->update($req);
			return redirect()->route('plantmaster.list')->with('success', 'Plant details updated successfully.');
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
			return redirect()->route('plantmaster.list')->with('success', 'Plant details deleted successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to delete plant: ' . $e->getMessage());
		}
	}

	public function validateData(Request $req)
	{

	}

	public function updateStatus(Request $req)
	{
		try {
			$this->service->updateStatus($req);
			return redirect()->route('plantmaster.list')->with('success', 'Plant details status updated successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
		}
	}
}
